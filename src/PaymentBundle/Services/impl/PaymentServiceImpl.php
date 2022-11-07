<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PaymentBundle\Services\impl;

use PaymentBundle\Entity\Payment as pay;
use PaymentBundle\Entity\PaymentInfo;
use PaymentBundle\Model\Payment;
use TTBundle\Utils\Utils;
use TTBundle\Utils\TTSerializerUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Yaml;
use PaymentBundle\Model\PaymentInitializer;
use TTBundle\Services\CurrencyService;
use CorporateBundle\Services\Admin\CorpoAccountServices;
use CorporateBundle\Services\Admin\CorpoAdminServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Services\UserServices;
use PaymentBundle\vendors\paytabs\v3\Model\PaytabsPaymentResponse;

//use PaymentBundle\vendors\payfort\v2\PayfortHandler;

/**
 * Description of PaymentServices
 *
 * @author para-soft7
 */
class PaymentServiceImpl implements \PaymentBundle\Services\PaymentService
{
    protected $utils;
    protected $payment;
    protected $payProviderHandler;
    protected $em;
    private $currencyPCC                 = "AED";
    public $currencyExchange;
    protected $corpoAccountServices;
    protected $corpoAdminServices;
    protected $container;
    protected $userServices;
    private $corpoDefaultPaymentStatus   = '14';
    private $corpoDefaultResponseCode    = '14000';
    private $corpoDefaultResponseMessage = 'BYPASSED';
    private $ccDefaultPaymentStatus      = '';
    private $ccDefaultResponseCode       = '00101';
    private $ccDefaultResponseMessage    = 'Pending';

    public function __construct(Utils $utils, Payment $payment, EntityManager $em, CurrencyService $currencyService, CorpoAccountServices $corpoAccountServices, CorpoAdminServices $corpoAdminServices,
                                ContainerInterface $container, UserServices $userServices)
    {
        $this->utils                = $utils;
        $this->payment              = $payment;
        $this->em                   = $em;
        $this->currencyExchange     = $currencyService;
        $this->corpoAccountServices = $corpoAccountServices;
        $this->corpoAdminServices   = $corpoAdminServices;
        $this->container            = $container;
        $this->userServices         = $userServices;
    }

    /**
     * get the module callBack Url from yml file
     * @param Payment $payment
     * @return type
     */
    public function getModuleCallBackUrl($module)
    {
        if (!isset($payment)) $payment = new Payment();

        if (!isset($module)) $module = "0";

        return $this->container->getParameter('payment_parameters')['callBackUrl'][$module];
    }

    /**
     * get information for FBC, SBC and Corpo Account Currency
     */
    public function getPaymentCurrencyInfo($amount, $currency)
    {
        $paymentConvertedAmounts = array();

        $fbc_currencyCode = $this->container->getParameter('FBC_CODE');
        $sbc_currencyCode = $this->container->getParameter('SBC_CODE');

        //        $user = $this->container->get('security.context')->getToken()->getUser();
        //        $userInfo = array();
        //        $userId   = $user->getId();
        $sessionInfo        = $this->container->get('CorpoAdminServices')->getLoggedInSessionInfo();
        //        $userId   = $sessionInfo['userId'];
        $userCorpoAccountId = $sessionInfo['accountId'];

        //        $userArray          = $this->userServices->getUserDetails(array('id' => $userId));
        //        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];
        //        $userInfo = $this->corpoAdminServices->getLoggedInSessionInfo();

        if ($userCorpoAccountId) {
            $paymentConvertedAmounts['accountCurrency'] = $this->corpoAccountServices->getAccountPreferredCurrency($userCorpoAccountId);
        }



        if (isset($paymentConvertedAmounts['accountCurrency'])) {

            $paymentConvertedAmounts['accountCurrencyAmount'] = $this->currencyExchange->exchangeAmount($amount, $currency, $paymentConvertedAmounts['accountCurrency']);
        } else {

            $paymentConvertedAmounts['accountCurrency']       = NULL;
            $paymentConvertedAmounts['accountCurrencyAmount'] = NULL;
        }
        $paymentConvertedAmounts['amountFBC'] = $this->currencyExchange->exchangeAmount($amount, $currency, $fbc_currencyCode);
        $paymentConvertedAmounts['amountSBC'] = $this->currencyExchange->exchangeAmount($amount, $currency, $sbc_currencyCode);


        return $paymentConvertedAmounts;
    }

    /**
     * This function is to insert the payment information into the database
     * @param Payment $payment
     * @return transaction_id
     */
    public function initializePayment(Payment $payment)
    {

        $paymentInit = new PaymentInitializer();
        $vendor      = $this->getProviderInfo($payment);

        $uuid = $payment->getNumber();

        if (!isset($uuid)) {

            $uuid = $this->utils->GUID();
        } else $paymentDb = $this->em->getRepository('PaymentBundle:Payment')->find($uuid);

        if (!isset($paymentDb)) {

            $paymentDb = new pay();
            $uuid      = $this->utils->GUID();
            $paymentDb->setUuid($uuid);
        }

        $amount = $payment->getDisplayOriginalAmount();

        $originalAmount = $payment->getOriginalAmount();

        $currency         = $payment->getDisplayedCurrency();
        $originalCurrency = $payment->getCurrency();

        $paymentAmountExchanged = array();
        $originalAmount         = $this->currencyExchange->exchangeAmount($originalAmount, $originalCurrency, $currency);
        $paymentAmountExchanged = $this->getPaymentCurrencyInfo($amount, $currency);
//        $isCorporatePage =  $this->utils->isCorporateSite();
//        if ($payment->getPaymentType() == Payment::CORPO_ON_ACCOUNT && $isCorporatePage){
        if ($payment->getPaymentType() == Payment::CORPO_ON_ACCOUNT) {

            $paymentStatus   = $this->corpoDefaultPaymentStatus;
            $responseCode    = $this->corpoDefaultResponseCode;
            $responseMessage = $this->corpoDefaultResponseMessage;

            $paymentInit->setCallBackUrl('_corpo_on_account_payment_process');
        } else { //if ($payment->getPaymentType() == Payment::CREDIT_CARD)
            $paymentStatus   = $this->ccDefaultPaymentStatus;
            $responseCode    = $this->ccDefaultResponseCode;
            $responseMessage = $this->ccDefaultResponseMessage;

            $supportedCurrencies = $vendor['currencies'];

            if (!in_array($currency, $supportedCurrencies)) {

                $amount   = $this->currencyExchange->exchangeAmount($amount, $currency, $supportedCurrencies[0]);
                $currency = $supportedCurrencies[0];
            }

            $paymentInit->setCallBackUrl('_paymentview');
        }


        $paymentInit->setTransactionId($uuid);

        $paymentDb->setCurrency($currency); //$this->currencyPCC;
        $paymentDb->setOriginalAmount($originalAmount);
        $paymentDb->setAmount($amount);


        $paymentDb->setDisplayCurrency($payment->getDisplayedCurrency());
        $paymentDb->setDisplayOriginalAmount($payment->getDisplayOriginalAmount());
        $paymentDb->setDisplayAmount($payment->getDisplayOriginalAmount());

        $paymentDb->setEmail($payment->getCustomerEmail());
        $paymentDb->setModuleTransactionId($payment->getModuleTransactionId()); //$payment->getModuleTransactionId());
        $paymentDb->setCustomerName($payment->getCustomerFullName());
        $paymentDb->setUserAgent($payment->getUserAgent());
        $paymentDb->setCustomerIp($payment->getCustomerIp());

        $paymentDb->setCommand($payment->getCommand());
        $paymentDb->setCreationDate(new \DateTime("now"));
        $paymentDb->setUpdatedDate(new \DateTime("now"));

        $paymentDb->setStatus($paymentStatus);
        $paymentDb->setResponseCode($responseCode);
        $paymentDb->setResponseMessage($responseMessage);
        $paymentDb->setLanguage('en');

        $paymentDb->setType($payment->getModuleName());
        $paymentDb->setPaymentType($payment->getPaymentType());

        $paymentDb->setPaymentProvider($vendor['id']); //TODO GET KEY IF NOT ABLE ADD ID FIELD TO YML
        $paymentDb->setModuleId($payment->getTrxTypeId());

        //Adding FBC andSBC To DATABASE
        $paymentDb->setAmountFBC($paymentAmountExchanged['amountFBC']);
        $paymentDb->setAmountSBC($paymentAmountExchanged['amountSBC']);
        $paymentDb->setAccountCurrency($paymentAmountExchanged['accountCurrency']);
        $paymentDb->setAccountCurrencyAmount($paymentAmountExchanged['accountCurrencyAmount']);

        $paymentDb->setModuleCurrency($payment->getModuleCurrency());
        $paymentDb->setModuleAmount($payment->getModuleAmount());

        $this->em->persist($paymentDb);
        $this->em->flush();

        return $paymentInit;
    }

    public function createCheckoutSession($trxId)
    {
        $paymentInfo = $this->getPaymentInformation($trxId);

        return $this->getProvider($paymentInfo)->createCheckoutSession($paymentInfo);
    }

    /**
     * Do process payment with vendor
     * @param type $trxId
     * @param type $paymentInfo
     * @return ARRAY with success(bool) and return_url
     */
    public function processPayment($trxId, $ccInfo)
    {
        $result = array("success" => false, "return_url" => "_payment_processRequest");

        $paymentInfo = $this->getPaymentInformation($trxId);

        $jsonResponse = $this->tokenise($ccInfo, $trxId, $paymentInfo);
        $result       = $jsonResponse;


        $result['transaction_id'] = $trxId;
        $result['module']         = $paymentInfo->getTrxTypeId();

        if ($jsonResponse['success'] == true) {
            $jsonResponse = $this->getProvider($paymentInfo)->processPayment($trxId, $paymentInfo, $jsonResponse);

            $result                   = $jsonResponse;
            $result['transaction_id'] = $trxId;
            $result['module']         = $paymentInfo->getTrxTypeId();

            $this->updateDBHistory($jsonResponse, $trxId);

            if ($jsonResponse['success'] == true) $this->processResponse($trxId, $paymentInfo, $jsonResponse);
        }

        return $result;
    }

    /**
     * this function is to tokenize the information and the form
     * @param type $param
     * @param type $uuid
     * @param type $paymentInfo
     * @return type
     */
    public function tokenise($param, $uuid, $paymentInfo)
    {
        $toknisation = $this->getProvider($paymentInfo)->tokenise($param, $uuid, $paymentInfo);


        $trxId           = $toknisation['data']['transaction_id'];
        $data            = $toknisation['data'];
        $data['command'] = $paymentInfo->getCommand();
        unset($data['service_command']);

        $trxId = $this->updateDBHistory($data, $trxId);

        return $toknisation;
    }

    /**
     * capture the payment after holding in it
     */
    public function captureOnHoldPayment($trxId)
    {
        $paymentInfo = $this->getPaymentInformation($trxId);

        $captureStatus = $this->getProvider($paymentInfo)->captureOnHoldPayment($paymentInfo);

        $this->updateDBHistoryCaptureRefund($captureStatus['data'], $trxId);

        return $captureStatus;
    }

    /**
     * make a refund for a specific successful payment. used by CorpoOnAccountHandler, AreebaHandler
     */
    public function refund($trxId)
    {
        $paymentInfo  = $this->getPaymentInformation($trxId);
        $refundStatus = $this->getProvider($paymentInfo)->refund($paymentInfo);

        $this->updateDBHistoryCaptureRefund($refundStatus['data'], $trxId);


        return $refundStatus;
    }

    /**
     * void the hold from client credit card
     */
    public function voidOnHoldPayment($trxId)
    {
        $paymentInfo                 = $this->getPaymentInformation($trxId);
        $voidOnHoldStatus            = $this->getProvider($paymentInfo)->voidOnHoldPayment($paymentInfo);
        //
        $genericCommands             = new ResponseStatusIdentifier();
        $voidOnHoldStatus['command'] = $genericCommands->getGenericCommand($genericCommands['status']);

        $this->updateDBHistoryCaptureRefund($voidOnHoldStatus['data'], $trxId);

        return $voidOnHoldStatus;
    }

    /**
     * this function is to get the latest information of the payment with transaction id $uuid, it return a Payment Object
     */
    public function getPaymentInformation($uuid)
    {
        $paymentDb = $this->em->getRepository('PaymentBundle:Payment')->find($uuid);

        $merchantReference = $paymentDb->getMerchantReference();

        $payment = new Payment();
        $payment->setAmount($paymentDb->getAmount());

        $payment->setCurrency($paymentDb->getCurrency());
        $payment->setCommand($paymentDb->getCommand());

        $payment->setCustomerEmail($paymentDb->getEmail());
        $payment->setCustomerFullName($paymentDb->getCustomerName());

        $payment->setModuleName($paymentDb->getType());
        $payment->setMerchantReference($merchantReference);

        $payment->setNumber($uuid);
        $payment->setModuleTransactionId($paymentDb->getModuleTransactionId());

        $payment->setTrxTypeId($paymentDb->getModuleId());
        $payment->setUserAgent($paymentDb->getUserAgent());


        $payment->setPaymentType($paymentDb->getPaymentType());
        $payment->setPaymentProvider($paymentDb->getPaymentProvider());

        return $payment;
    }

    /**
     * this function is to get the gateway transaction information from the response received
     */
    public function verifyPaymentTransaction($response)
    {
        $paymentInfo = $this->getPaymentInformation($response["orderId"]);

        return $this->getProvider($paymentInfo)->verifyPaymentTransaction($response);
    }

    public function countPaymentAttempt($uuid)
    {
        $attemptCount = $this->em->getRepository('PaymentBundle:PaymentDetails')->getPaymentAttemptCount($uuid);

        if ($attemptCount < $this->container->getParameter('areeba')['paymentAttempts']) {
            // get latest saved payment attempt
            $latestSavedAttempt = $this->em->getRepository('PaymentBundle:PaymentDetails')->getLatestPaymentAttempt($uuid);
            if ($latestSavedAttempt) {
                $latestSavedAttempt = json_decode($latestSavedAttempt->getApiResponse(), true);
            }
            $paymentInfo = $this->getPaymentInformation($uuid);

            // get latest payment attempt details
            $paymentTransaction = $this->getProvider($paymentInfo)->getLatestPaymentTransaction($uuid);

            if (!empty($paymentTransaction) && isset($paymentTransaction['latestTransaction.transaction.id']) &&
                (!$latestSavedAttempt || ($latestSavedAttempt['latestTransaction.transaction.id'] != $paymentTransaction['latestTransaction.transaction.id']))) {

                // insert payment attempt details
                $paymentDetails = new \PaymentBundle\Entity\PaymentDetails();
                $paymentDetails->setUuid($uuid);
                $paymentDetails->setApiResponse(json_encode($paymentTransaction));
				$paymentDetails->setCreatedAt(new \DateTime("now"));
				$paymentDetails->setUpdatedAt(new \DateTime("now"));
				
                $this->em->persist($paymentDetails);
                $this->em->flush();

                $attemptCount++;
            }
        }
        return $attemptCount;
    }

    /**
     * preparing the parameter to make the persist in the DB
     */
    public function updateDBHistory($param, $trxId)
    {
        $paramToUpdate          = array();
        $paramToUpdate["trxId"] = $trxId;
        //TODO TO BE CHECKED WHEN WE DON'T HV ALL THE INFO TO AVOID DB ISSUES
        if (!isset($param['merchant_reference']) || !isset($param['response_message'])) return false;
        else {
            $paramToUpdate["command"] = isset($param['service_command']) ? $param['service_command'] : $param['command'];

            $paramToUpdate["status"]              = isset($param['status']) ? $param['status'] : 0;
            $paramToUpdate["responseCode"]        = isset($param['response_code']) ? $param['response_code'] : 0;
            $paramToUpdate["responseMessage"]     = isset($param['response_message']) ? $param['response_message'] : '';
            $paramToUpdate["merchantReference"]   = isset($param['merchant_reference']) ? $param['merchant_reference'] : '';
            $paramToUpdate["tokenName"]           = isset($param['token_name']) ? $param['token_name'] : "";
            $paramToUpdate["paymentGatewayTrxId"] = isset($param['fort_id']) ? $param['fort_id'] : null;
            $paramToUpdate["creditCardNumber"]    = isset($param['card_number']) ? $param['card_number'] : null;

            $trxId = $this->updatePaymentTransaction($trxId, $paramToUpdate);
        }
        return $trxId;
    }

    /**
     * process the response of the processPayment function,
     */
    private function processResponse($trxId, $paymentInfo, $jsonResponse)
    {
        $this->getProvider($paymentInfo)->processResponse($trxId, $paymentInfo, $jsonResponse);
    }

    /**
     * Updating the payment table
     */
    private function updatePaymentTransaction($trxId, $param)
    {
        $paymentDb = $this->em->getRepository('PaymentBundle:Payment')->find($trxId);

        $paymentDb->setCommand($param["command"]);
        if ($param['tokenName']) {
            $paymentDb->setTokenName($param["tokenName"]);
        }
        $paymentDb->setStatus($param["status"]);
        $paymentDb->setResponseCode($param["responseCode"]);
        $paymentDb->setResponseMessage($param["responseMessage"]);
        $paymentDb->setMerchantReference($param["merchantReference"]);
        $paymentDb->setUpdatedDate(new \DateTime("now"));
        $paymentDb->setCreditCardNumber(sha1($param["creditCardNumber"]));
        $paymentDb->setFortID($param["paymentGatewayTrxId"]);

        $this->em->persist($paymentDb);
        $this->em->flush();

        $result['moduleTrxId'] = $paymentDb->getModuleTransactionId();

        $result['moduleId'] = $paymentDb->getModuleId();

        return $result;
    }

    /**
     * updating the payment table when the commands are Capture or Refund
     */
    private function updatePaymentTrxCaptureRefund($param, $trxId)
    {
        $paymentDb = $this->em->getRepository('PaymentBundle:Payment')->find($trxId);

        $paymentDb->setCommand($param["command"]);
        $paymentDb->setResponseCode($param["responseCode"]);
        $paymentDb->setResponseMessage($param["responseMessage"]);
        $paymentDb->setUpdatedDate(new \DateTime("now"));
        $paymentDb->setStatus($param["status"]);

        $this->em->persist($paymentDb);
        $this->em->flush();

        return $paymentDb->getUuid();
    }

    /**
     * prepare the param for updating the payment table in case of capture or refund
     */
    private function updateDBHistoryCaptureRefund($param, $trxId)
    {
        $paramToUpdate          = array();
        $paramToUpdate["trxId"] = $trxId;

        $paramToUpdate["command"] = isset($param['service_command']) ? $param['service_command'] : $param['command'];

        $paramToUpdate["status"]          = $param['status'];
        $paramToUpdate["responseCode"]    = $param['response_code'];
        $paramToUpdate["responseMessage"] = $param['response_message'];

        $trxId = $this->updatePaymentTrxCaptureRefund($paramToUpdate, $trxId);

        return $trxId;
    }

    /**
     * Retrieve the payment provider information from config
     *
     * @param Payment $payment
     * @return mixed|unknown
     */
    private function getProviderInfo(Payment $payment)
    {
        if (!isset($payment)) $payment = new Payment();

        $vendors = $this->container->getParameter('payment_parameters')['vendors'][$payment->getPaymentType()];

        if (null !== $payment->getPaymentProvider()) {
            $vendorClass = $vendors[$payment->getPaymentProvider()];
        } else {
            //retrieve first record from yml config file
            //later on to add special condition to choose provider
            reset($vendors);
            $vendorClass = current($vendors);
        }

        return $vendorClass;
    }

    /**
     * This function is to identify which payment gateway we will be using
     * @param Payment $payment
     * @return provider class name
     */
    private function getProvider(Payment $payment)
    {
        $vendorClass = $this->getProviderInfo($payment);

        $this->payProviderHandler = new $vendorClass['className']($this->container);

        return $this->payProviderHandler;
    }

    /**
     * This function is to identify which payment gateway we will be using
     * @param Payment $payment
     * @return provider class name
     */
    public function getGatewayService($module)
    {
        return $this->container->getParameter('payment_parameters')['vendors']['cc'][$module];
    }

    /**
     * this function returns the payment information from a given Payment UUID
     *
     * @param transactionID $trxId
     * @return Payment Object
     */
    public function getPaymentByUUID($trxId)
    {
        $paymentDb = $this->em->getRepository('PaymentBundle:Payment')->findOneByUuid($trxId);

        return $paymentDb;
    }

    /**
     * This function for now will return statically the path of paytabs twig
     * Later on it will take into consideration incoming parameters to check whether to choose the paytabs payment process or payfort payment process
     */
    public function checkPaymentVendor()
    {
        $twigUrl = '@Payment/paytabs/paytabsPaymentCheckout.twig';
        return $twigUrl;
    }

    /**
     * This fuction transform the incoming json from paytabs response to PaytabsPaymentResponse object
     */
    public function getPaymentResponseObject($jsonResult)
    {
        $serializer = new TTSerializerUtils();
        $object     = $serializer->deserializeJsonToObject($jsonResult, PaytabsPaymentResponse::class);
        //$object = $this->container->get('PaytabsHandler')->deserializeJsonToObject($jsonResult, PaytabsPaymentResponse::class);
        return $object;
    }

    /**
     * This function returs the status of the payment in paytabs
     */
    public function getPaymentStatus($responseObject)
    {
        $response = $this->container->get('PaytabsHandler')->checkPaymentStatus($responseObject);
        return $response;
    }

    public function createPaymentInfo($parameters)
    {
        $paymentInfo = $this->em->getRepository('PaymentBundle:PaymentInfo')->createPaymentInfo($parameters);

        return $paymentInfo;
    }

    public function getPhoneNumberInfo($parameters)
    {
        $parameters ['flights_module_id'] = $this->container->getParameter('MODULE_FLIGHTS');
        $parameters ['hotels_module_id']  = $this->container->getParameter('MODULE_HOTELS');
        $parameters ['deals_module_id']   = $this->container->getParameter('MODULE_DEALS');

        $phoneDetails = $this->em->getRepository('PaymentBundle:Payment')->getPhoneNumber($parameters);

        return $phoneDetails;
    }
}
