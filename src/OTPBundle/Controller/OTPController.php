<?php

namespace OTPBundle\Controller;

use OTPBundle\Entity\RequestOTP;
use OTPBundle\Entity\ResponseObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OTPController extends \TTBundle\Controller\DefaultController
{
    
    /**
     *
     * @param
     *            $module_id
     * @param
     *            $reservation_id
     * @param
     *            $user_id
     * @param Request $requestForm
     * @return This function gets all the values sent through the url and generates a random code and it to the otp_request table and then redirects to the validation form
     */
    public function generateCodeAction($module_id, $reservation_id, $user_id, Request $requestForm)
    {
        $request = new RequestOTP();
        $request->setModule_id($module_id);
        $request->setTransaction_id($reservation_id);
        $request->setUser_id($user_id);
        //
        $email   = "";
        if ($user_id == 0) {
            $email = $requestForm->query->get('email');
        }
        $query = $requestForm->query->all();
        //
        //
        $request->setUrl_params(json_encode($query));
        //
        $genId = $this->get('otpService')->generateCode($request, $email);

        //if no code is sent redirect them to the home page
        if (!$genId){
            return $this->redirectToRoute('_index');
        }

        $params = array();
        $params['id'] = $genId;
        if(isset($query['resend'])){
            $params['resend'] = 1;
        } 
        //
        // To notify here about the generated code
        // $id = rawurldecode($genId);
        //
        return $this->redirectToRoute('verifyOtpRoute', $params);
    }
    
    /**
     * This is a test function for the otp redirection process
     */
    public function defaultCallBackAction()
    {
        echo "Default Callback: Verified Code";
        exit();
    }
    
    /**
     *
     * @param
     *            $id
     * @param
     *            $module_id
     * @param
     *            $reservation_id
     * @param
     *            $user_id
     * @param Request $request
     * @return This function validates if the code entered is correct and then returns a response object as JSON
     */
    public function verifyCodeAction($id, Request $request)
    {
        $form = $this->createFormBuilder()
        ->add('Code', 'text')
        ->add('Check', 'button')
        ->getForm();

        $form->handleRequest($request);
		
        // if ($form->isSubmitted() && $form->isValid()) {
		$code = null;
		$continue_checking_code = $form->isSubmitted();
		if ($continue_checking_code)
		{
			$requestedCode = $form->getData();
			$code = trim($requestedCode['Code']);
			
			$continue_checking_code = (strlen($code) && preg_match("/^[a-z0-9]+$/i", $code) == 1);
		}
		
		if ($continue_checking_code) {

//             if ($form->get('Check')->isClicked()) {}

            // $requestedCode = $form->getData();
            // $code = trim($requestedCode['Code']);
            $verifyCode = $this->get('otpService')->verifyCode($id, $code);
            //
            if ($verifyCode->getSuccess() == "true") {
                $route     = null;
                $data      = $verifyCode->getData();
                $module_id = $data->getModule_id();

//                $route = $this->container->getParameter('otp')["callbacks"][(in_array($module_id, array(1, 2, 3, 4))?$module_id:"default")]["success"];

               if (!in_array($module_id, array(1, 2, 3, 4))) {
                    $route = $this->container->getParameter('otp')["callbacks"]["default"]["success"];
                } else {
                    if($this->container->get('app.utils')->isCorporateSite()){
                        $route = $this->container->getParameter('otp')["callback_corporate"][$module_id]["success"];
                    }else{
                        $route = $this->container->getParameter('otp')["callback_web"][$module_id]["success"];
                    }
                }

                $param = (array) json_decode($data->getUrl_params());

                $params = array_merge(array(
                    'module_id' => $module_id,
                    'reservation_id' => $data->getTransaction_id(),
                    'user_id' => $data->getUser_id()
                ), $param);

                return $this->redirect($this->generateUrl($route, $params, UrlGeneratorInterface::ABSOLUTE_URL), 308); 
            } else {
                $this->data['message'] = $verifyCode->getMessage();
            }
        }
        //
        $record                       = $this->get('otpService')->getRequestParameters($id);
        //
        $this->data['form']           = $form->createView();
        $this->data['isCorpoSite']    = $this->container->get('app.utils')->isCorporateSite();
        $this->data['module_id']      = $record->getModule_id();
        $this->data['reservation_id'] = $record->getTransaction_id();
        $this->data['user_id']        = $record->getUser_id();
        $this->data['genId']          = $id;

        if($request->query->has('resend') && $request->query->get('resend')){
            $this->addInfoNotification($this->translator->trans('Please check your inbox, a new code has been sent to your email'));
        }
        return $this->render('@OTPBundle/Resources/views/checkForm.html.twig', $this->data);
    }

    /**
     * @param unknown $module_id
     * @param unknown $reservation_id
     * @param unknown $gen_id
     * @param unknown $user_id
     * @param Request $request
     * @return unknown The following function creates a new OTP code when the user clicks on resend code and changes the status of the old otp code generated to is_used
     */
    public function resendCodeAction($module_id, $reservation_id, $gen_id, $user_id, Request $request)
    {
        $recordToUpdate = rawurldecode($gen_id);
        $update         = $this->get('otpService')->updatePreviousRecord($recordToUpdate);
        
        return $this->redirectToRoute('otpRoute', array(
            'module_id' => $module_id,
            'reservation_id' => $reservation_id,
            'user_id' => $user_id,
            'resend' => 1
        ));
    }
}
