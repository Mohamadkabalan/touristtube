<?php

namespace TTBundle\Services;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use PaymentBundle\Entity\PaymentTransactionFeedback;
use Symfony\Component\DependencyInjection\ContainerInterface;

if (!defined('RESPONSE_SUCCESS'))
    define('RESPONSE_SUCCESS', 0);

if (!defined('RESPONSE_ERROR'))
    define('RESPONSE_ERROR', 1);

class PayFortServices {

    private $utils;
    public $TEST_MODE = true;
    private $ONLINE = false;
    private $URL = '';
    private $checkoutURL = '';
    private $container;
    private $production_server = false;
    private $HTTP_TEST_URL = 'https://sbpaymentservices.PayFort.com/FortAPI/paymentApi';
    private $HTTP_PROD_URL = 'https://paymentservices.PayFort.com/FortAPI/paymentApi';
    private $HTTP_PAYMENT_RETURN_URL = '/payment-operation';
    private $HTTP_AUTH_USER = null;
    private $HTTP_AUTH_PASSWORD = '';
    private $HTTP_AUTH_METHOD = 'none'; // none, basic, digest
    private $ADDITIONAL_HEADERS = array("Content-Type" => "application/json;charset=UTF-8", "Connection" => "Keep-Alive");
    private $em;
    private $supportedCurrencies = array('USD', 'EUR', 'AED');
	private $feedbackTiming = array('pause_between_retries_secs' => 10, 'time_limit_mins' => 1);

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container) {
	$this->container = $container;
	$this->production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');

	if ($this->production_server) {
	    $this->TEST_MODE = false;
	    $this->ONLINE = true;
	}

	$this->URL = ($this->TEST_MODE) ? $this->HTTP_TEST_URL : $this->HTTP_PROD_URL;
	$this->checkoutURL = ($this->TEST_MODE) ? 'https://sbcheckout.PayFort.com/FortAPI/paymentPage' : 'https://checkout.payfort.com/FortAPI/paymentPage';
	$this->HTTP_PAYMENT_RETURN_URL = (($this->ONLINE) ? 'https://www.touristtube.com' : 'http://89.249.212.9/app_dev.php') . $this->HTTP_PAYMENT_RETURN_URL;
	$this->utils = $utils;
	$this->em = $em;
    }

    /**
     * get checkooutURl defined, int private variable.
     * @return string checkout URL
     */
    public function getcheckOutURL() {
	return $this->checkoutURL;
    }

    /**
     * this function  set the parameter Default value service_command = 'TOKENIZATION' and language = 'en', and to precise the return url for payfort to return after payment is done, using the transaction_id
     * @param string $uuid this is the transaction_id, that we get from the URL to use it in the return url.
     * @return array returns the 3 parameters from getDefaultParams, and the 3 others are service_command, language and the return_url
     */
    public function tokenizationService($uuid, $paymentType = null) {
	$parameters = $this->getDefaultParams();

	$parameters['service_command'] = 'TOKENIZATION';
	$parameters['language'] = 'en';
	$parameters['return_url'] = $this->HTTP_PAYMENT_RETURN_URL . '?transaction_id=' . $uuid . "&type=" . ($paymentType ? $paymentType : '');

	return $parameters;
    }

    /**
     * calling PayFort API with the 'SDK_TOKEN' service_command to get a valid token to send it back to mobile application
     * @param type $payment
     * @param type $request
     * @return array that contains SDK_TOKEN and other paramters for mobile
     */
    public function sdkTokenService($payment, $request) {

	$parameters = $this->getDefaultParams();

	$merchantReference = $parameters['merchant_reference'];

	// $transactionId = $request->get('transaction_id');
	$transactionId = $payment->getUuid();

	unset($parameters['merchant_reference']);
	unset($parameters['transaction_id']);

	$parameters['service_command'] = 'SDK_TOKEN';
	$parameters['device_id'] = $request->get('device_id');
	$parameters['language'] = $request->get('language', 'en');

	$signature = $this->generateSignature($parameters, 'RQ');

	$parameters['signature'] = $signature;

	$jsonReq = json_encode($parameters);

	$data = $this->webServiceCall($jsonReq);
	$data['data'] = json_decode($data['data'], true);

	if ($this->validateSignature($data['data'])) {
	    $data = $this->responseData($data['data'], $data['data']['status'], $data['data']['response_code'], $data['data']['response_message']);
	    $data['data']['response_message'] = "Pending";
	    $data['data']['merchant_reference'] = $merchantReference;
	    $this->paymentPersist($payment, $data['data'], $transactionId, 1);
	} else {
	    $data = $this->responseData($data['data'], '00', '00008', 'Response signature mismatch');
	}

	$data['merchant_reference'] = $merchantReference;

	return $data;
    }

    /**
     * \this is where we make the payment , after checking the validity of everything with payfort.
     * @param array $payment Payment information like currency, amount and other
     * @param array $queryString array that contains values returned by PayFort after submiting the form.
     * @return array it return the status of payment service.
     */
    public function paymentService($payment, $queryString) {
	/**
	 * First we validate the Signature returned by QueryString
	 */
	if ($this->validateSignature($queryString)) {
	    /**
	     * assigning parameter into $pruchaseParameters variable
	     */
	    $purchaseParameters = $this->purchaseOperation($payment, $queryString);

	    if ($purchaseParameters) {
		/**
		 * Encoding paramters to JSON and assigning it to $jsonPurchaseReq variable
		 */
		$jsonPurchaseReq = json_encode($purchaseParameters);
		/**
		 * make the Call to PurchaseOperation to PayFort, this is where the payment it's Done, and sending it with JSON parameter
		 */
		$data = $this->webServiceCall($jsonPurchaseReq);
		/**
		 * after making the call we decode the JSON response and put it in $data['data']
		 */
		$data['data'] = json_decode($data['data'], true);
		/**
		 * in this case we validate the Signature sent by Payfort in the JSON response when making the PurchaseOperation .
		 */
		if ($this->validateSignature($data['data'])) {
		    /**
		     * response data is just a structuring of a response, to handle it in any case
		     */
		    $data = $this->responseData($data['data'], $data['data']['status'], $data['data']['response_code'], $data['data']['response_message']);
		    /**
		     * paymentPersust, is the Function when we update rows in the payment table.
		     */
		    $this->paymentPersist($payment, $data['data'], $queryString['transaction_id'], 0);
		} else {
		    $data = $this->responseData($data['data'], '00', '00008', 'Response signature mismatch');
		}
	    } else {
		$data = $this->responseData(array(), '00', '00103', 'Invalid transaction ID');
	    }
	} else {
	    $data = $this->responseData(array(), '00', '00008', 'Response signature mismatch');
	}

	return $data;
    }

    /**
     * This Function is to prepare the parameter of purchaseOperation
     * @param array $payment payment information from database
     * @param array $queryString payment Information returned from in QueryString From PayFort After submission
     * @return array contains all needed parameter to make the purchaseOperations
     */
    public function purchaseOperation($payment, $queryString) {

	// $payment = $this->em->getRepository('PaymentBundle:Payment')->find($queryString['transaction_id']);

	if (!$payment) {
	    return false;
	}

	$parameters = $this->getDefaultParams();
	$parameters['command'] = 'PURCHASE';
	$parameters['language'] = $queryString['language'];
	$parameters['return_url'] = $this->HTTP_PAYMENT_RETURN_URL . '?type=' . $payment->getType() . '&transaction_id=' . $queryString['transaction_id'];
//	$customerEmail = ($payment->getType() == 'flight') ? $payment->getPassengerNameRecord()->getEmail() : 'firas.boukarroum@touristtube.com';
//	$parameters['customer_email'] = $customerEmail;
	$parameters['customer_email'] = $payment->getPassengerNameRecord()->getEmail();

	$priceInfo = $this->getPriceInfo($payment);

	$parameters['currency'] = $this->TEST_MODE ? "AED" : $priceInfo['currency'];
	$parameters['amount'] = $this->TEST_MODE ? 100 : $priceInfo['price'];
	$parameters['token_name'] = isset($queryString['token_name']) ? $queryString['token_name'] : null;
	$parameters['merchant_reference'] = $queryString['merchant_reference'];
	$parameters['customer_ip'] = $payment->getCustomerIp();
	$parameters['remember_me'] = $queryString['remember_me'];
	$parameters['device_fingerprint'] = $payment->getDeviceFingerPrint();

	$signature = $this->generateSignature($parameters, 'RQ');

	$parameters['signature'] = $signature;

	return $parameters;
    }

    /**
     * this Function is used to update rows in the Payment table.
     * @param type $payment
     * @param type $data
     * @param type $transactionId
     * @param type $mobileSdk
     * @return boolean
     */
    public function paymentPersist($payment, $data, $transactionId, $mobileSdk) {
	// $payment = $this->em->getRepository('TTBundle:Payment')->find($transactionId);

	if (!$payment)
	    return false;

	$payment->setMerchantReference($data['merchant_reference']);
	$payment->setStatus($data['status']);
	$payment->setResponseMessage($data['response_message']);
	$payment->setResponseCode($data['response_code']);
	$payment->setCommand(isset($data['service_command']) ? $data['service_command'] : $data['command']);
	$payment->setFortId(isset($data['fort_id']) ? $data['fort_id'] : null);
	$tokenName = isset($data['token_name']) ? $data['token_name'] : "";
	$sdkToken = isset($data['sdk_token']) ? $data['sdk_token'] : "";
	$token = ($tokenName != "") ? $tokenName : $sdkToken;
	if ($token != "") {
	    $payment->setTokenName($token);
	}
	// $payment->setCustomerIp(isset($data['customer_ip']) ? $data['customer_ip'] : null);
	$payment->setLanguage($data['language']);
	$payment->setMobileSdk($mobileSdk);
	$payment->setCreationDate(new \DateTime("now"));
	$payment->setUpdatedDate(new \DateTime("now"));

	$payment->setRememberMe(isset($data['remember_me']) && $data['remember_me'] == 'YES');

	$this->em->persist($payment);
	$this->em->flush();

	return $payment;
    }

    /**
     * captre or refund api is called
     * @param type $payment
     * @param type $params
     * @return JSON contains status of the response, and the other needed parameters
     */
    public function refundCaptureService($payment, $params) {

	// $payment = $this->em->getRepository('TTBundle:Payment')->find($params['uuid']);

	if (!$payment) {
	    return false;
	}
	/**
	 * prepearing refundCapture parameter and assigning it to $parameters
	 */
	$parameters = $this->refundCaptureOperation($payment, $params);
	/**
	 * making the REST call to refundCapture
	 */
	$refundCaptureJSON = $this->webServiceCall(json_encode($parameters));
	$refundCapture = json_decode($refundCaptureJSON['data'], true);
	/**
	 * validating the signuatre received from the  response of refundCapture Api Call
	 */
	if ($this->validateSignature($refundCapture)) {
	    // if ($refundCapture['status'] == '06') {
	    /**
	     * Updating Returned information in the database.
	     */
	    $this->refundCapturePersist($payment, $refundCapture);
	    $response = json_encode($this->responseData($refundCapture, $refundCapture['status'], $refundCapture['response_code'], $refundCapture['response_message']));
	} else {
	    $response = json_encode($this->responseData($refundCaptureJSON['data'], '00', '00008', 'Response signature mismatch'));
	}

	return $response;
    }

    /**
     * this function prepeare the parameters needed to make the refundCapture call
     * @param type $payment
     * @param type $params
     * @return array contains needed parameters
     */
    public function refundCaptureOperation($payment, $params) {

	$parameters = $this->getDefaultParams();
	$parameters['fort_id'] = $payment->getFortId();
	$parameters['command'] = strtoupper($params['operation']);

	$priceInfo = $this->getPriceInfo($payment);

	$parameters['currency'] = $this->TEST_MODE ? "AED" : $priceInfo['currency'];
	$parameters['amount'] = $this->TEST_MODE ? 100 : $priceInfo['price'];

	$parameters['language'] = $payment->getLanguage();
	$parameters['merchant_reference'] = $payment->getMerchantReference();

	$signature = $this->generateSignature($parameters, 'RQ');
	$parameters['signature'] = $signature;

	return $parameters;
    }

    /**
     * Updating the database with the information returned by refundCaptureSErvice
     * @param type $payment
     * @param type $refundCapture
     * @return boolean
     */
    public function refundCapturePersist($payment, $refundCapture) {

	$payment->setStatus($refundCapture['status']);
	$payment->setResponseMessage('Refunded');
	$payment->setResponseCode($refundCapture['response_code']);
	$payment->setCommand($refundCapture['command']);
	$payment->setUpdatedDate(new \DateTime("now"));

	$this->em->persist($payment);
	$this->em->flush();

	return true;
    }

    /**
     * this function is updating the database, from the returned data getting it from the Feedback URL
     * @param array $params
     * @return PaymentTransactionFeedback
     */
    public function paymentTransactionFeedbackPersist($params) {
	$transaction = new PaymentTransactionFeedback();

	$transaction->setMerchantReference(isset($params['merchant_reference']) ? $params['merchant_reference'] : "");
	$transaction->setFortId(isset($params['fort_id']) ? $params['fort_id'] : "");
	$transaction->setCommand(isset($params['command']) ? $params['command'] : "");
	$transaction->setTokenName(isset($params['token_name']) ? $params['token_name'] : "");
	$transaction->setCustomerIp(isset($params['customer_ip']) ? $params['customer_ip'] : "");
	$transaction->setLanguage(isset($params['language']) ? $params['language'] : "");
	$transaction->setStatus(isset($params['status']) ? $params['status'] : "");
	$transaction->setResponseCode(isset($params['response_code']) ? $params['response_code'] : "");
	$transaction->setResponseMessage(isset($params['response_message']) ? $params['response_message'] : "");

	$transaction->setRememberMe(isset($params['remember_me']) && $params['remember_me'] == 'YES');

	$transaction->setEci(isset($params['eci']) ? $params['eci'] : "");
	$transaction->setCustomerEmail(isset($params['customer_email']) ? $params['customer_email'] : "");
	$transaction->setCurrency(isset($params['currency']) ? $params['currency'] : "");
	$transaction->setAmount(isset($params['amount']) ? $params['amount'] : "");
	$transaction->setPaymentOption(isset($params['payment_option']) ? $params['payment_option'] : "");
	$transaction->setCreationDate(new \DateTime("now"));

	$this->em->persist($transaction);
	$this->em->flush();

	return $transaction;
    }

    /**
     * this function make the rest api call to payfort to make the PurchaseOperation.
     * @param JSON $params paramtere already prepeared by the function purchaseOperation and encoded to JSON
     * @return JSON
     */
    public function webServiceCall($params) {
	/**
	 * makin the call using the function sendData in utils to make REST API call
	 */
	$operationResponse = $this->utils->send_data($this->URL, $params, \HTTP_Request2::METHOD_POST, array('auth_method' => $this->HTTP_AUTH_METHOD, 'username' => $this->HTTP_AUTH_USER, 'password' => $this->HTTP_AUTH_PASSWORD), $this->ADDITIONAL_HEADERS);
	/**
	 * generaly this is the strucutre of responses usinf the $data[] array
	 */
	if ($operationResponse['response_error'] == RESPONSE_ERROR) {
	    $data['status'] = '00';
	    $data['response_code'] = '00102';
	    $data['response_message'] = 'Server error';
	    $data['data'] = '';
	} else {
	    $data['data'] = $operationResponse['response_text'];
	}

	return $data;
    }

    /**
     * this function use the tokinazation parameters, then we sort the parameters alphabeticaly, then we add the the predifined $phrase at the begining and the end of the sorted parameter, finally we hash everything using sha256 and returning.
     * @param array $params this array get 6 paramater to be sent in the request, those paramter we get from tokinazationService
     * @param string $type if its a request the $type value is RQ, else it's RS, based on the value $type the $phrase variable change.
     * @return string return the $signature encrytpted ysubg sha256.
     */
    public function generateSignature($params, $type) {
	if ($this->TEST_MODE)
	    $phrase = ($type === "RQ") ? "sdetgsegtest" : "sgtsetgswtaet";
	else
	    $phrase = ($type === "RQ") ? "d9c8e8b8fc4eabe" : "b8449123f551e9d";

	if ($params) {
	    if (!is_array($params))
		$params = array($params);

	    ksort($params);
	}

	$sortedParams = "";

	if ($params) {
	    foreach ($params as $key => $value) {
		$sortedParams .= $key . '=' . $value;
	    }
	}

	$signaturePattern = $phrase . $sortedParams . $phrase;

	$signature = hash('sha256', $signaturePattern);

	return $signature;
    }

    /**
     * we generate signuatre using the the generateSignature function with parameters sent by Payfort response, if we get the same signuatre sent by Payfort in the request then it's VAlide
     * @param array $queryString parameters send in URL from PayFort response
     * @return Bool in case it's valid or not
     */
    public function validateSignature($queryString) {
	$validateRq = $queryString;
	unset($validateRq['type']);
	unset($validateRq['transaction_id']);
	unset($validateRq['signature']);

	$validateSignature = $this->generateSignature($validateRq, 'RS');

	return ($validateSignature == $queryString['signature']);
    }

    /**
     * this function is called each tiome we are sending request to PayFort, to fill 3 parameters of the request.
     * @return array contains merchant_reference, access_code, merchant_identifier,
     */
    public function getDefaultParams() {
	/**
	 * @var string $guid generate a unique ID, to use it in creating new unique id for merchant_reference
	 */
	$guid = $this->utils->GUID();
	/**
	 * @var string $merchantReference is creating uniqid using the guid param.
	 */
	$merchantReference = uniqid(substr($guid, 5, 7));
	/**
	 * @var array $params that contains merchant_reference, access_code, merchant_identifier
	 */
	$params = array();
	$params['merchant_reference'] = $merchantReference;
	$params['access_code'] = $this->TEST_MODE ? "lXJ9rz4VF2VVk7pRrRJy" : "AQZTIksoaE31zIPUXI9F";
	$params['merchant_identifier'] = $this->TEST_MODE ? "qvnFNPMX" : "PnuSSuFh";

	return $params;
    }

    /**
     * this functions get payment information, from the parameter $payment, and the $params parameter is used for the parameter of the request
     * @param array $payment array that conatins all information from database about the pricing.
     * @param aray $params parameter used for making PayFortRequest
     * @return array that contains parameters of the PayFort Request
     */
    public function getServiceParameters($payment, $params) {

	$serviceParams = array();
	$serviceParams['merchant_reference'] = $params['merchant_reference'];
	$serviceParams['access_code'] = $params['access_code'];
	$serviceParams['merchant_identifier'] = $params['merchant_identifier'];

	$priceInfo = $this->getPriceInfo($payment);

	$serviceParams['currency'] = $this->TEST_MODE ? "AED" : $priceInfo['currency'];
	$serviceParams['amount'] = $this->TEST_MODE ? 100 : $priceInfo['price'];
	$serviceParams['language'] = $params['language'];
	$serviceParams['command'] = 'PURCHASE';
//	$customerEmail = ($payment->getType() == 'flight') ? $payment->getPassengerNameRecord()->getEmail() : 'firas.boukarroum@touristtube.com';
//	$serviceParams['customer_email'] = $customerEmail;
	$serviceParams['customer_email'] = $payment->getPassengerNameRecord()->getEmail();
	$serviceParams['cart_details'] = '{"cart_items":[{"item_name":"' . $payment->getType() . '","item_description":"' . $payment->getType() . '","item_quantity":"1","item_price":"' . "100" . '","item_image":"https://static.touristtube.com/media/images/Logo.png"}],"sub_total":"' . "100" . '"}';
	$serviceParams['return_url'] = $params['return_url'];

	return $serviceParams;
    }

    /**
     * we make the Structure of the Response like this. to be used in all cases when making calls to Payfort
     * @param array $data the data returend by the api
     * @param string $status the status of the api call, if it's successfully done or not,
     * @param string $responseCode Code sent by the provider conserning a response on an API call
     * @param string $responseMsg the response msg we get from the api call
     * @return array that contains specific fields. data,status,response_code,response_message/
     */
    public function responseData($data, $status, $responseCode, $responseMsg) {
	$resp = array();
	$resp['data'] = $data;
	$resp['status'] = $status;
	$resp['response_code'] = $responseCode;
	$resp['response_message'] = $responseMsg;

	return $resp;
    }

    /**
     * function to get price info from mobile JSON request send it to the server
     * @param type $payment
     * @return type
     */
    public function getPriceInfo($payment) {

	$priceInfo = ['price' => 0.00, 'currency' => ''];

	// $paymentCurrency = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();
	if ($payment) {
	    $paymentCurrency = $payment->getDisplayCurrency();

	    if (in_array($paymentCurrency, $this->supportedCurrencies)) {
		// $price = $payment->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice() * 100;
		$price = $payment->getDisplayAmount() * 100;
	    } else {
		// $paymentCurrency = $payment->getPassengerNameRecord()->getFlightInfo()->getCurrency();
		$paymentCurrency = $payment->getCurrency();
		// $price = $payment->getPassengerNameRecord()->getFlightInfo()->getPrice() * 100;
		$price = $payment->getAmount() * 100;
	    }

	    $priceInfo['price'] = $price;
	    $priceInfo['currency'] = $paymentCurrency;
	}
	return $priceInfo;
    }

	/**
     * Get payment_transaction_feedback.status for the given payment
     * @param payment $payment Payment object for which to fetch the status from the feedback sent by PayFort.
	 * @param array $timing_specs An array containing timing control values (pause between retries, and maximum waiting time in minutes).
     * @return string status of the payment as last returned by PayFort in the feedback channel.
     */
	public function getPaymentFeedbackStatus($payment, $timing_specs = array())
	{
		$effective_timing_specs = $this->feedbackTiming;
		if ($timing_specs)
			$effective_timing_specs = array_merge($effective_timing_specs, $timing_specs);

		$payment_status = $payment->getStatus();

		$feedback_status = null;
		$time_limit_mins = ($effective_timing_specs['time_limit_mins'] * 60);

		$pgwIdSqlString = ''; // payment gateway ID SQL string
		if ($payment->getFortId())
		{
			$pgwIdSqlString = ' AND ptf.fortId = :pgw_id';
		}

		//$em = $this->getDoctrine()->getManager();
		$query = $ $this->em->createQuery("SELECT ptf.status FROM PaymentBundle:PaymentTransactionFeedback ptf WHERE ptf.merchantReference = :merchant_reference$pgwIdSqlString ORDER BY ptf.creationDate DESC");
		$query->setParameter(':merchant_reference', $payment->getMerchantReference());

		if ($pgwIdSqlString)
			$query->setParameter(':pgw_id', $payment->getFortId());

		$query->setMaxResults(1);

		$startTime = microtime(true);

		do
		{
			// Wait to find the same status as in the payment table, within the limit specified in effective_timing_specs
			$feedback_status = $query->getSingleScalarResult();

			if ($payment_status != null && $payment_status == $feedback_status)
				break;

			sleep($effective_timing_specs['pause_between_retries_secs']);
		}
		while ((microtime(true) - $startTime) < $time_limit_mins);

		return $feedback_status;
	}

	/**
     * Get payment_transaction_feedback.fort_id, status, command, response_code, response_message for the given payment
     * @param payment $payment Payment object for which to fetch the status from the feedback sent by PayFort.
	 * @param array $timing_specs An array containing timing control values (pause between retries, and maximum waiting time in minutes).
     * @return array Associative array with the following keys:: fort_id, status, command, response_code, response_message of the payment as last returned by PayFort in the feedback channel.
     */
	public function getPaymentFeedback($payment, $timing_specs = array())
	{
		$effective_timing_specs = $this->feedbackTiming;
		if ($timing_specs)
			$effective_timing_specs = array_merge($effective_timing_specs, $timing_specs);

		$payment_status = $payment->getStatus();

		$pgw_feedback = array();
		$time_limit_mins = ($effective_timing_specs['time_limit_mins'] * 60);

		$pgwIdSqlString = ''; // payment gateway ID SQL string
		if ($payment->getFortId())
		{
			$pgwIdSqlString = ' AND ptf.fortId = :pgw_id';
		}

		//$em = $this->getDoctrine()->getManager();
		$query = $this->em->createQuery("SELECT ptf.fortId AS fort_id, ptf.status, ptf.command, ptf.responseCode AS response_code, ptf.responseMessage AS response_message FROM PaymentBundle:PaymentTransactionFeedback ptf WHERE ptf.merchantReference = :merchant_reference$pgwIdSqlString ORDER BY ptf.creationDate DESC");
		$query->setParameter(':merchant_reference', $payment->getMerchantReference());

		if ($pgwIdSqlString)
			$query->setParameter(':pgw_id', $payment->getFortId());

		$query->setMaxResults(1);

		$startTime = microtime(true);

		do
		{
			// Wait to find the same status as in the payment table, within the limit specified in effective_timing_specs
			$pgw_feedback = $query->getScalarResult();

			if ($pgw_feedback)
				$pgw_feedback = $pgw_feedback[0];
			else
				$pgw_feedback = array();

			if ($payment_status != null && $pgw_feedback && $payment_status == $pgw_feedback['status'])
				break;

			sleep($effective_timing_specs['pause_between_retries_secs']);
		}
		while ((microtime(true) - $startTime) < $time_limit_mins);

		return $pgw_feedback;
	}
}
