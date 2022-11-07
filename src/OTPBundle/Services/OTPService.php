<?php

namespace OTPBundle\Services;

use OTPBundle\Entity\ResponseObject;
use OTPBundle\Repositories\OTPRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use TTBundle\Services\EmailServices;
use Symfony\Component\Translation\TranslatorInterface;
use OTPBundle\Entity\RequestOTP;

class OTPService
{
    protected $repo;
    protected $utils;
    protected $emailservice;
    
    public function __construct(Utils $utils, ContainerInterface $container, OTPRequestRepository $repo, EmailServices $emailservices, TranslatorInterface $translator)
    {
        $this->container    = $container;
        $this->repo         = $repo;
        $this->utils        = $utils;
        $this->emailservice = $emailservices;
        $this->translator   = $translator;
        date_default_timezone_set('UTC');
    }
    
    /**
     *
     * @param
     *            $n_chars
     * @param
     *            $otpRequest
     * @param
     *            $options
     * @return string This function takes a RequestOTP object containing the moduleId, TransactionId and userId in addition to the
     *         email of the user and generates a random password that will be added to the request_otp table and then an email will be send
     *         to the user with the generated code
     */
    public function generateCode(RequestOTP $otpRequest, $email, $options = null)
    {
        $n_chars = $this->container->getParameter('otp')['password_length'];
        
        $randomString = $this->utils->randomString($n_chars);
        
        $date   = new \DateTime();
        $otpRequest->setCreation_date($date);
        $otpRequest->setCode($randomString);
        $otpRequest->setIs_used(0);
        $otpRequest->setGenerated_id("");
        $otp_id = $this->repo->addCode($otpRequest);
        $this->notifyUser($otpRequest->getUser_id(), $randomString, $email);
        
        return $otp_id;
    }
    
    /**
     *
     * @param
     *            $id
     * @param
     *            $requestedCode
     * @return \OTPBundle\Entity\Response This function verifies all the conditions (time limit, if the code is already used and
     *         the code itself) and return a response with the status of the request the message and success
     */
    public function verifyCode($id, $requestedCode)
    {
        $response = new ResponseObject();
        
		$identifier = "";
		
        $record = $this->repo->getRequestByCode($requestedCode);
        
		if ($record)
            $identifier = $record->getId();
		
        // if (!password_verify($identifier, $id)) {
        if (!$identifier || $id != $this->repo->generateIdHash($identifier)) {
            
            $response->setSuccess("false");
            $response->setCode('404');
            $response->setMessage($this->translator->trans("Code is invalid"));
			
			return $response;
        }
			
		if ($record->getIs_used()) {
			$response->setSuccess("false");
			$response->setCode('403');
			$response->setMessage($this->translator->trans("Code has already been used"));
			
			return $response;
		}
			
		$current_time = new \DateTime();
		
		$time_limit = $this->container->getParameter('otp')['time_limit'];
		
		if ($this->pastTimeLimit($record->getCreation_date(), $current_time, $time_limit)) {
			$response->setSuccess("false");
			$response->setCode('403');
			$response->setMessage($this->translator->trans("Code has expired"));
			
			return $response;
		}
		
		$response->setSuccess("true");
		$response->setCode('200');
		$response->setMessage($this->translator->trans("Code is valid"));
		$this->repo->updateRecord($identifier);
		$response->setData($record);
		
		return $response;
    }
    
    /**
     *
     * @param
     *            $id
     * @param $code This
     *            function sends an email to the user with the code generated
     */
    public function notifyUser($id, $code, $emailUser)
    {
        if ($id == 0) {
            $username = "User";
            $email    = $emailUser;
        } else {
            $user_info = $this->repo->getUserInfo($id);
            $username  = $user_info[0]['YourUserName'];
            $email     = $user_info[0]['YourEmail'];
        }
        $email_data            = array(
            'user_name' => $username,
            'pin' => $code
        );
        $message               = $this->container->get('templating')->render('emails/email_pin.twig', $email_data);
        $message_subject_title = $this->translator->trans('Your new TouristTube PIN');
        $this->emailservice->addEmailData($email, $message, $message_subject_title, $message_subject_title, 0);
    }
    
    public function pastTimeLimit($creation_time, $check_time, $time_limit_secs = 20)
	{
		if (!$time_limit_secs)
			return false;
		
		// function add modifies the DateTime object itself, work on a copy of creation_time
		$dt_creation_time = $creation_time;
		$dt_limit = $dt_creation_time->add(new \DateInterval("PT${time_limit_secs}S"));
		
		// As of PHP 5.2.2, DateTime objects can be compared using comparison operators.
		return ($check_time > $dt_limit);
	}
    
    public function verifyCodeValidation($module_id, $transaction_id, $generated_id)
    {
        $validation = $this->repo->verifyCodeValidation($module_id, $transaction_id, $generated_id);
        if ($validation) {
            if ($validation[0]['is_used'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * @param unknown $id
     * @return boolean The following function gets the id of the row of the currrent generated id and then update is_used variable to 1
     */
    public function updatePreviousRecord($id)
    {
        $record     = $this->repo->getRequestByGenId($id);
        $currentId  = $identifier = $record->getId();
        $validation = $this->repo->updateRecord($currentId);
        if ($validation) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param unknown $genId
     * @return unknown The following function returns the module transaction and user id from the generated code
     */
    public function getRequestParameters($genId)
    {
        $record = $this->repo->getRequestByGenId($genId);
        return $record;
    }
}