<?php

namespace RestBundle\Controller\otp;

use OTPBundle\Entity\RequestOTP;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use RestBundle\Controller\TTRestController;
use Symfony\Component\HttpFoundation\Response;

class OtpController extends TTRestController
{
    
    public function generateCodeAction()
    {
        $requestForm = $this->getRequest()->query->all();
        
        $requirements = array(
            array(
                'name' => 'module_id',
                'required' => true
            ),
            array(
                'name' => 'reservation_id',
                'required' => true
            ),
            array(
                'name' => 'user_id',
                'required' => true
            )
        );
        
        $this->validateFetchedRequestData($requestForm, $requirements);
        
        $response = $this->getGeneratedCode($requestForm);
        
        
        $response = new Response(json_encode($response));
        $response->setStatusCode(200);
        return $response;
    }
    
    public function verifyCodeAction()
    {
        $requestForm  = $this->getRequest()->request->all();
        $requirements = array(
            array(
                'name' => 'otp_id',
                'required' => true
            ),
            array(
                'name' => 'code',
                'required' => true
            )
        );
        
        $this->validateFetchedRequestData($requestForm, $requirements);
        
        $otp_id = $requestForm['otp_id'];
        $code   = $requestForm['code'];
        
        $verifyCode = $this->get('otpService')->verifyCode(rawurldecode($otp_id), $code);
        //
        if ($verifyCode->getSuccess() == "true") {
            
            $response = [
                'status' => 'true',
                'code' => '200',
                'message' => 'Success'
            ];
        } else {
            
            $response = [
                'status' => $verifyCode->getSuccess(),
                'code' => $verifyCode->getCode(),
                'message' => $verifyCode->getMessage()
            ];
        }
        
        $response = new Response(json_encode($response));
        $response->setStatusCode($verifyCode->getCode());
        
        return $response;
    }
    
    public function resendCodeAction()
    {
        $requestForm  = $this->getRequest()->request->all();
        $requirements = array(
            array(
                'name' => 'module_id',
                'required' => true
            ),
            array(
                'name' => 'reservation_id',
                'required' => true
            ),
            array(
                'name' => 'user_id',
                'required' => true
            ),
            array(
                'name' => 'old_otp_id',
                'required' => true
            )
        );
        
        $this->validateFetchedRequestData($requestForm, $requirements);
        
        $gen_id         = $requestForm['old_otp_id'];
        $recordToUpdate = rawurldecode($gen_id);
        $update         = $this->get('otpService')->updatePreviousRecord($recordToUpdate);
        
        $response = $this->getGeneratedCode($requestForm);
        
        $response = new Response(json_encode($response));
        $response->setStatusCode(200);
        return $response;
    }
    
    public function getGeneratedCode($requestParams)
    {
        
        $module_id      = $requestParams['module_id'];
        $reservation_id = $requestParams['reservation_id'];
        $user_id        = $requestParams['user_id'];
        
        $request = new RequestOTP();
        $request->setModule_id($module_id);
        $request->setTransaction_id($reservation_id);
        $request->setIs_used(0);
        $request->setUser_id($user_id);
        $request->setGenerated_id("");
        //
        $email   = "";
        if ($user_id == 0) {
            $email = $requestForm['email'];
        }
        //
        //
        $request->setUrl_params(json_encode($requestParams));
        //
        $genId = $this->get('otpService')->generateCode($request, $email);
        
        $response = [
            'status' => 'true',
            'code' => '200',
            'message' => 'Success',
            'otp_id' => rawurlencode($genId)
        ];
        
        
        
        return $response;
    }
}