<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PaymentBundle\Model\Payment;

class TTRestController extends FOSRestController
{
    public $data = array();
    public $on_production_server               = true;

    public $_tt_global_variables               = array();
    public $translator;
    public $max_api_call_attempts              = 3; // this includes the first call, and any subsequent call(s) due to failure
    public $pause_between_retries_us           = 500000; // number of micro seconds to pause between retries in case of failure
    public $user_pin_validation_mode           = true;
    public $show_deals_block                   = 0;
    public $show_flights_block                 = 1;
    public $show_flights_corporate_block       = 1;
    public $show_flights_multiple_destinations = 1;
    public $storage_engine                     = '';


    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->translator = $this->get('translator');

        $requestData = $this->fetchRequestData();
        $lang        = (isset($requestData['lang'])) ? $requestData['lang'] : '';

        $this->LanguageSet($lang);
        $this->containerInitialized();

    }

    public function __construct()
    {

    }


    private function containerInitialized()
    {
        $this->on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');

        if ($this->container->hasParameter('MAX_API_CALL_ATTEMPTS')) $this->max_api_call_attempts = $this->container->getParameter('MAX_API_CALL_ATTEMPTS');

        if ($this->container->hasParameter('PAUSE_BETWEEN_RETRIES_US')) $this->pause_between_retries_us = $this->container->getParameter('PAUSE_BETWEEN_RETRIES_US');

        if ($this->container->hasParameter('SHOW_DEALS_BLOCK')) $this->show_deals_block         = $this->container->getParameter('SHOW_DEALS_BLOCK');
        $this->data['SHOW_DEALS_BLOCK'] = $this->show_deals_block;

        if ($this->container->hasParameter('SHOW_FLIGHTS_BLOCK')) $this->show_flights_block         = $this->container->getParameter('SHOW_FLIGHTS_BLOCK');
        $this->data['SHOW_FLIGHTS_BLOCK'] = $this->show_flights_block;

        if ($this->container->hasParameter('SHOW_FLIGHTS_MULTIPLE_DESTINATIONS')) $this->show_flights_multiple_destinations         = $this->container->getParameter('SHOW_FLIGHTS_MULTIPLE_DESTINATIONS');
        $this->data['SHOW_FLIGHTS_MULTIPLE_DESTINATIONS'] = $this->show_flights_multiple_destinations;

        if ($this->container->hasParameter('STORAGE_ENGINE')) $this->storage_engine         = $this->container->getParameter('STORAGE_ENGINE');
        $this->data['STORAGE_ENGINE'] = $this->storage_engine;
        $user      = $this->get('security.token_storage')->getToken()->getUser();



        if(is_object($user)){
            $this->data['isUserLoggedIn'] = 1;
            $this->data['USERID'] =$user->getId();
                $this->data['isUserLoggedIn']  = 1;

                $this->data['userName']        = $user->getFullname();

            $this->data['is_corporate_account']   = $user->getIsCorporateAccount();
        }else{
            $this->data['isUserLoggedIn'] = 0;
        }



    }


    public function LanguageGet()
    {
        global $GLOBAL_LANG;
        if (!isset($GLOBAL_LANG) || !$GLOBAL_LANG) $GLOBAL_LANG = 'en';
        return $GLOBAL_LANG;
    }

    public function LanguageSet($lang)
    {
        global $GLOBAL_LANG;
        if (!isset($lang) || !$lang) $GLOBAL_LANG = 'en';
        else $GLOBAL_LANG = $lang;
        return $GLOBAL_LANG;
    }

    /**
     * This method retrieves the user id from the security token provided
     * @return Integer
     */
    public function userGetID()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $user->getId();
    }

    /**
     * This method returns the details of a corporate payment
     *
     * @param PaymentBundle\Model\Payment $payment
     * @param Integer $reservationId
     * @param Integer $moduleId
     * @param Integer $transactionUserId
     * @return Array
     */
    public function processPayment($payment, $reservationId, $moduleId, $transactionUserId)
    {
        //Get the logged in user account id
        $user      = $this->get('security.token_storage')->getToken()->getUser();
        $accountId = $user->getCorpoAccountId();

        $crsResult = $this->get('CorpoAdminServices')->getPendingRequestDetailsId(array('reservationId' => $reservationId, 'moduleId' => $moduleId));

        $result = array();
        if (empty($crsResult)) {
            $result = $this->paymentApproval($payment, $moduleId);
        } else {
            $result["success"] = true;
            if ($this->get('CorpoApprovalFlowServices')->allowedToApproveForUser($this->userGetID(), $transactionUserId, $accountId)) {
                $payInit = $this->get('PaymentServiceImpl')->initializePayment($payment);

                if ($payInit->getCallBackUrl() == '_corpo_on_account_payment_process') {
                    $result["message"]      = 'needs_coa_payment';
                    $result["callback_url"] = '_rest'.$payInit->getCallBackUrl();
                } else {
                    $result["message"]        = 'needs_cc_payment';
                    $result["reservation_id"] = $reservationId;
                    $result["module_id"]      = $moduleId;
                    $result["amount"]         = $payment->getAmount();
                    $result["currency"]       = $payment->getCurrency();
                }
                $result["transaction_id"] = $payInit->getTransactionId();
            } else {
                $result["message"] = "corporate_account_waiting_approval";
            }
        }
        return $result;
    }

    /**
     * Manage the payment process and the corporate approval flow
     *
     * @return
     */
    public function paymentApproval($payment, $moduleId)
    {

        $result            = array();
        $result["success"] = true;

        $user               = $this->get('security.token_storage')->getToken()->getUser();
        $isCorporateAccount = $user->getIsCorporateAccount();

        if ($isCorporateAccount) {
            $userId             = $this->userGetID();
            $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
            $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

            $reqSerParams = array(
                "userId" => $userId,
                "accountId" => $userCorpoAccountId,
                "moduleId" => $moduleId, //$this->container->getParameter('MODULE_FLIGHTS'),
                "reservationId" => $payment->getModuleTransactionId(),
                "currencyCode" => $payment->getCurrency(),
                "amount" => $payment->getAmount()
            );

            $status = $this->container->getParameter('CORPO_APPROVAL_PENDING');

            // Create Request Details
            $crsResult = $this->get('CorpoApprovalFlowServices')->addPendingRequestServices($reqSerParams, $status);
        }

        $result = $this->getPaymentMethod($payment->getAmount(), $payment->getCurrency(), $payment->getPaymentType());

        if ($result["message"] == 'corporate_account_waiting_approval') {
            $result["status_id"] = $status;
        } else {
            $payInit = $this->get('PaymentServiceImpl')->initializePayment($payment);

            $result["transaction_id"] = $payInit->getTransactionId();

            if ($result["message"] == 'needs_coa_payment') {
                $result["callback_url"] = '_rest'.$payInit->getCallBackUrl();
            } elseif ($result["message"] == 'needs_cc_payment') {
                $result["reservation_id"] = $payment->getModuleTransactionId();
                $result["module_id"]      = $moduleId;
                $result["amount"]         = $payment->getAmount();
                $result["currency"]       = $payment->getCurrency();
            }
        }

        return $result;
    }

    /**
     * This method determines the payment type of the user
     *
     * @param integer $userId
     * @return
     */
    public function getPaymentMethod($amount, $currencyCode, $accountPaymentType)
    {
        $result        = array();
        $result["otp"] = true;

        $user               = $this->get('security.token_storage')->getToken()->getUser();
        $isCorporateAccount = $user->getIsCorporateAccount();

        if ($isCorporateAccount) {
            $userId             = $this->userGetID();
            $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
            $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

            $reqSerParams = array(
                "accountId" => $userCorpoAccountId,
                "currencyCode" => $currencyCode,
                "amount" => $amount
            );

            // If allowed user . . .
            $checkLimitBudget = $this->get('CorpoAccountServices')->isAccountBudgetAllowed($reqSerParams);
            if ($checkLimitBudget) {
                if ($this->get('CorpoApprovalFlowServices')->userAllowedToApprove($userId, $userCorpoAccountId)) {
                    if ($accountPaymentType == Payment::CORPO_ON_ACCOUNT) {
                        $result["message"] = 'needs_coa_payment';
                    } else {
                        $result["message"] = 'needs_cc_payment';
                    }
                } else {
                    $result["message"] = "corporate_account_waiting_approval";
                    $result["otp"]     = false;
                }
            } else {
                $result["message"]            = 'needs_cc_payment';
                $result["additional_message"] = $this->translator->trans("Kindly, note that you have reached your budget limit, hence proceeding with credit card payment.");
            }
        } else {
            $result["message"] = 'needs_cc_payment';
        }

        return $result;
    }

    /**
     * This method determines the payment type of the user.
     *
     * @param decimal $amount
     * @param string $currencyCode
     *
     * @return String The payment method of the user
     */
    public function checkPaymentType($amount, $currencyCode)
    {
        //Get the logged in user account id
        $user      = $this->get('security.token_storage')->getToken()->getUser();
        $accountId = $user->getCorpoAccountId();

        // Get the account default payment type
        $accountPaymentType = $this->container->get('CorpoAccountServices')->getCorpoAccountPaymentType($accountId);

        return $this->getPaymentMethod($amount, $currencyCode, $accountPaymentType['code']);
    }

    /**
     * This method forwards to the given route
     *
     * @param string $route
     * @param array $requestParam
     */
    public function forwardToRoute($route, $requestParam)
    {
        $routes              = $this->container->get('router')->getRouteCollection();
        $path['_controller'] = $routes->get($route)->getDefaults()['_controller'];
        return $this->forward($path['_controller'], array(), $requestParam);
    }

    /**
     * This method fetch and validate the json data posted in array format,
     *
     * @param Array $requirements   The requirements.
     * @return Array    The data.
     */
    public function fetchRequestData(array $requirements = array())
    {
        $request = Request::createFromGlobals();

        $data = array_merge($request->request->all(), $request->query->all());

        $content = $this->get("request")->getContent();
        if (!empty($content) && is_string($content)) {
            $data = array_merge($data, json_decode($content, true));
        }

        // validate fetched request data
        $this->validateFetchedRequestData($data, $requirements);

        return $data;
    }

    /**
     * This method validates the fetched data per provided requirements.
     *
     * @param array $fetchedData    The fetched data.
     * @param array $requirements   The requirements.
     */
    public function validateFetchedRequestData(array $fetchedData, array $requirements)
    {
        foreach ($requirements as $field) {
            if (is_array($field)) {
                $this->validateConstraints($fetchedData, $field);
            } else {
                if (!isset($fetchedData[$field]) || (empty($fetchedData[$field]) && $fetchedData[$field] != "0")) {
                    $action_array   = array();
                    $action_array[] = $field;
                    $ms             = vsprintf($this->translator->trans("%s is required."), $action_array);
                    throw new HttpException(403, $ms);
                }
            }
        }
    }

    /**
     * This method validates the fetched data per provided requirements.
     *
     * @param array $fetchedData    The fetched data.
     * @param array $requirements   The requirements.
     */
    public function validateConstraints(array $fetchedData, array $requirements)
    {
        $validator = $this->container->get('validator');

        $constraints = array();
        $name        = $requirements['name'];
        $required    = false;

        // validate required
        if (isset($requirements['required']) && $requirements['required']) {
            $required = true;
            if (!isset($fetchedData[$name])) {
                $action_array   = array();
                $action_array[] = $name;
                $ms             = vsprintf($this->translator->trans("Missing %s parameter."), $action_array);
                throw new HttpException(403, $ms);
            }
        }

        if (($required && !isset($requirements['nullable'])) || (isset($requirements['nullable']) && $requirements['nullable'] === false)) {
            $constraints[] = new \Symfony\Component\Validator\Constraints\NotNull();
            $constraints[] = new \Symfony\Component\Validator\Constraints\NotBlank();
        }

        if (isset($requirements['type'])) {
            $constraints[] = new \Symfony\Component\Validator\Constraints\Type(array('type' => $requirements['type']));
        }

        if (isset($requirements['constraints']) && count($requirements['constraints']) > 0) {
            foreach ($requirements['constraints'] as $constraintName => $params) {
                switch ($constraintName) {
                    case 'date':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\Date();
                        break;
                    case 'email':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\Email();
                        break;
                    case 'gt':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\GreaterThan($params);
                        break;
                    case 'gte':
                        $constraints[] = new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual($params);
                        break;
                    default:
                        break;
                }
            }
        }

        if (count($constraints) > 0 && isset($fetchedData[$name])) {
            $error = $validator->validateValue($fetchedData[$name], $constraints);
            if (count($error) > 0) {
                $action_array   = array();
                $action_array[] = $name;
                $ms             = vsprintf($this->translator->trans("Invalid %s parameter."), $action_array);
                throw new HttpException(403, $ms." $error");
            }
        }
    }

    /**
     * This method sets the request variable isRestCorporate to true
     *
     */
    protected function setIsRestCorporate()
    {
        $this->container->get('request_stack')->getCurrentRequest()->attributes->set('isRestCorporate', 'true');
    }

    // converting array to json
    public function convertToJson($param)
    {
        return $this->get('app.utils')->createJSONResponse($param);
    }
    public function userIsCorporateAccount()
    {
        if (!$this->data['isUserLoggedIn'] || !$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return false;
        }

        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return $userInfo['isCorporateAccount'];
    }
    public function getUserEmail()
    {
        if (!$this->data['isUserLoggedIn']) {
            return '';
        }
        if (!$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return '';
        }
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return $userInfo['email'];
    }
    public function checkTimeZoneCookie()
    {
        global $request;
        $timezone_cookie = $request->cookies->get('timezone', '');
        if (!isset($timezone_cookie)) {
            $expiret    = time() + 365 * 24 * 3600;
            $expire     = gmdate("r", $expiret);
            $pathcookie = '/';
            return '<script type="text/javascript">
                if (navigator.cookieEnabled) document.cookie = "timezone="+ (- new Date().getTimezoneOffset()) +";expires=('.$expire.');path=/";
                if (navigator.cookieEnabled) document.location.reload();
            </script>';
        } else {
            return $this->updateCheckTimeZoneCookie();
        }
    }
    public function updateCheckTimeZoneCookie()
    {
        $expiret    = time() + 365 * 24 * 3600;
        $expire     = gmdate("r", $expiret);
        $pathcookie = '/';
        return '<script type="text/javascript">
             if (navigator.cookieEnabled) document.cookie = "timezone="+ (- new Date().getTimezoneOffset()) +";expires=('.$expire.');path=/";
        </script>';
    }
}
