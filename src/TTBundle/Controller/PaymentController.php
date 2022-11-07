<?php

namespace TTBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends DefaultController {

	private $paymentGateways = array('payfort');
	private $allowedPaymentGatewayReferers = array('touristtube' => "/https\:\/\/.+\.touristtube\.com/", 'payfort' => "/https\:\/\/checkout\.payfort\.com/", 'ttfiras' => "/http\:\/\/tt\.ttfirasboukarroum/");

    /**
     * in this Action is responsible when the payment page is opened first the tokinazationService is called, then used for generatre a signuatre. and other parameters for MasterPass, and  VISA_CHECKOUT
     * @return twig render the payment twig.
     */
    public function paymentAction() {

	/*
	  if(!$this->data['isUserLoggedIn'] || !$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
	  return $this->redirect('/'); redirection to root
	  }
	 */

	$this->data['showHeaderSearch'] = 0;
	/**
	 * Getting the Parameter from the URL, in this case we are getting the Transaction_id, $uuid
	 */
	$request = $this->getRequest();
	$request_params = $request->request->all();
	if (!$request_params)
		$request_params = $request->query->all();

	$this->addFlightLog('Payment processing with criteria: {criteria}', array('criteria' => $request_params));
	/**
	 * @param $uuid variable that get contains the transaction_id value from the URL
	 */
	$uuid = null;
	if (isset($request_params['transaction_id']))
		$uuid = $request_params['transaction_id'];

	// $userId = $this->data['USERID'];

	/**
	 * in case if transaction_id doesn't exist, redirect to flight_booking
	 */
	if (!$uuid) {
	    return $this->redirectToRoute('_flight_booking', ['error' => "Error! Invalid transaction ID"]);
	}

	/**
	 * we check if the transaction_id exists in the payment table, column name uuid
	 */
	$payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find($uuid);
	/**
	 * if transaction_id exists in the payment table, we get the user IP, and we update the row, if the user change the transaction_id or anything else happened it will be redirect to flight_booking
	 */
	if ($payment) {
	    $payment->setCustomerIp($this->get('app.utils')->getUserIP());

	    $em = $this->getDoctrine()->getManager();
	    $em->persist($payment);
	    $em->flush();
	} else {
	    return $this->redirectToRoute('_flight_booking', ['error' => "Error! Invalid transaction ID"]);
	}

	$this->data['error'] = '';
	if (isset($request_params['response_message']))
		$this->data['error'] = $request_params['response_message'];

	$parameters = $this->get('PayFortServices')->tokenizationService($uuid, $payment->getType());
	$signature = $this->get('PayFortServices')->generateSignature($parameters, 'RQ');
	$parameters['signature'] = $signature;

	$this->addFlightLog('[payment] Getting API PayFortServices_Signature with response: ' . $signature);
	/**
	 * @var array $payFortServiceParams is an array contains pricing info and coresponding paramter for Payfort Request, used for MasterPass or VISA_CHECKOUT
	 */
	$payFortServiceParams = $this->get('PayFortServices')->getServiceParameters($payment, $parameters);
	$this->addFlightLog('[payment] PayFortServices_Params with response:: {criteria}', array('criteria' => $payFortServiceParams));

	$this->data['checkoutURL'] = $this->get('PayFortServices')->getcheckOutURL();
	$this->data['parameters'] = $parameters;
	/**
	 * MasterPass
	 */
	$masterPassParameters = $payFortServiceParams;
	$masterPassParameters['digital_wallet'] = 'MASTERPASS';
	$masterPassParameters['signature'] = $this->get('PayFortServices')->generateSignature($masterPassParameters, 'RQ');
	$this->addFlightLog('[payment] PayFortServices_MasterSignature:: ' . $masterPassParameters['signature']);
	$this->data['masterpass_parameters'] = $masterPassParameters;
	/**
	 * VISA_CHECKOUT
	 */
	$visaCheckOutParameters = $payFortServiceParams;
	unset($visaCheckOutParameters['cart_details']);
	$visaCheckOutParameters['digital_wallet'] = 'VISA_CHECKOUT';
	$visaCheckOutParameters['signature'] = $this->get('PayFortServices')->generateSignature($visaCheckOutParameters, 'RQ');
	$this->addFlightLog('[payment] PayFortServices_VISASignature:: ' . $visaCheckOutParameters['signature']);
	$this->data['visa_checkout_parameters'] = $visaCheckOutParameters;

	$this->data['card_expiration_year_start'] = date('Y');

	/*
	  if($payment->getUserId() == $userId) {
	  return $this->render('default/payment.twig', $this->data);
	  }
	  else {
	  return $this->render('@Flight/flight/flight-no-data.twig', $this->data);
	  }
	 */

	return $this->render('default/payment.twig', $this->data);
    }

    /**
     * After Submitting the FORM, and filling the payment information, the return URL is Handeled by the Action paymentOperation, and it returns a URL that contain parameters, based on the values of these parameters we handle the request, and we perform actions. paymente or redirect
     * @return twig it redirect based on the response of the form value.
     */
    public function paymentOperationAction() {

	$http_referer = $this->getRequest()->headers->get('referer');
	$this->addFlightLog("Payment operation request:: referer:: $http_referer");

	$refererAllowed = false;
	$matchedRefererSource = null;

	if ($http_referer)
	{
		foreach ($this->allowedPaymentGatewayReferers as $referer_source => $allowedRefererRegex)
		{
			if (preg_match($allowedRefererRegex, $http_referer))
			{
				$refererAllowed = true;

				$matchedRefererSource = $referer_source;

				break;
			}
		}
	}

	if (!$refererAllowed)
		return $this->redirectToRoute('_my_bookings', ['timedOut' => true]);

	$server = filter_input_array(INPUT_SERVER);
	$queryString = array();
	/**
	 * parse the url, and their parameter, in queryString,
	 */
	parse_str($server['QUERY_STRING'], $queryString);

	$this->addFlightLog('Payment operation request with criteria: {criteria}', array('criteria' => $queryString));

	if (!isset($queryString['transaction_id']) || !$queryString['transaction_id'])
	    return $this->redirectToRoute('_my_bookings', array());

	if (isset($queryString['status'])) {
	    /**
	     * getting the recored from DB with the UUID value = to the transaction_id sent by Payfort.
	     */
	    $payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneByUuid($queryString['transaction_id']);

	    if (!$payment) {
		$redirect = $this->redirectToRoute('_payment', array('transaction_id' => $queryString['transaction_id'], 'response_message' => $this->translator->trans('Error! No transaction found')));
	    }
	    /**
	     * if this record exists , checking the status in Database if it's diffrent the 14 ( payed successfully ) or it's 06 ( refunded ) we update the record by the information returned by Payfort
	     */
	    if ($payment->getStatus() != '14' && $payment->getStatus() != '06') { // from DB
		$this->get('PayFortServices')->paymentPersist($payment, $queryString, $queryString['transaction_id'], 0);
		/**
		 * we check the type of payment in the payment table if it is a flight we continue,
		 * IN THE FUTURE, the deal will use the same payment methode we have to add condition of type == deals
		 */
		$db_payment_type = $payment->getType();

		if ($db_payment_type == 'flight') {
		    $currentTime = new \DateTime('-10 minutes');
		    /**
		     * we check if the status Of the PNR in the database is SUCCESS and the creationDate of this record in Payment Table is under 10 minutes .
		     */
		    if ($payment->getPassengerNameRecord()->getStatus() == 'SUCCESS' && $payment->getPassengerNameRecord()->getCreationDate() > $currentTime) {
			/**
			 * checking the status returned by PayFort
			 */
			switch ($queryString['status']) { // /from Query String
			    /**
			     * case 10, means status incomplete
			     * case 18 tokenization success
			     */
			    case "10":
			    case "18":

					$paymentOperation = $this->get('PayFortServices')->paymentService($payment, $queryString);
					/**
					 * the status 20 means that the transaction status is ON HOLD to check the 3D_SECURE
					 */
					if ($paymentOperation['status'] == '20')
						$redirect = $this->redirect($paymentOperation['data']['3ds_url']);
					else
						$redirect = $this->redirectToRoute('_payment', array('transaction_id' => $queryString['transaction_id'], 'type' => $db_payment_type, 'status' => $paymentOperation['status'], 'response_code' => $paymentOperation['response_code'], 'response_message' => $paymentOperation['response_message']));
				break;
			    /**
			     * in case the returned status is 14 means the the payment is Done successfully, we Issue the ticket, we redirect to the link responsible of ticket issuing that make calls with DATANA and SABRE for Issuing the ticket.
			     */
			    case "14":
					if (!in_array($matchedRefererSource, $this->paymentGateways))
						$redirect = $this->redirectToRoute('_flight_booking', ['timedOut' => true]);
					else
						$redirect = $this->redirectToRoute('_issue_ticket', array('transaction_id' => $queryString['transaction_id'], 'type' => $db_payment_type, 'from_mobile' => 0));
					break;
			    default:
					$redirect = $this->redirectToRoute('_payment', array('transaction_id' => $queryString['transaction_id'], 'type' => $db_payment_type, 'status' => $queryString['status'], 'response_code' => $queryString['response_code'], 'response_message' => $queryString['response_message']));
			}
		    } else {
				$redirect = $this->redirectToRoute('_flight_booking', ['timedOut' => true]);
		    }
		} else {
                    // This is a temporarily code to redirect to tt.ttfirasboukarroum sandbox for the testing of payment gateway <firas.boukarroum@touristtube.com>
//		    $redirect = $this->redirectToRoute('_payment', array('transaction_id' => $queryString['transaction_id'], 'type' => $db_payment_type, 'response_message' => $this->translator->trans('Error! Not a flight')));
                    $url = $this->container->getParameter('FIRAS_PAYMENT_URL');
//                    $params = array('transaction_id' => $queryString['transaction_id'], 'type' => $db_payment_type, 'status' => $queryString['status'], 'response_code' => $queryString['response_code'], 'response_message' => $queryString['response_message']);
                    $params = http_build_query($queryString);
                    $transactionUrl = $url."?".$params;
//                    $transactionUrl = $this->generateUrl($url,$params);
                    return $this->redirect($transactionUrl);
                }
	    } else {
			$redirect = $this->redirectToRoute('_flight_booking', ['error' => $this->translator->trans('Error! Order already processed')]);
	    }
	} else {
	    $redirect_params = array('transaction_id' => $queryString['transaction_id']);

	    if (isset($queryString['type']))
			$redirect_params['type'] = $queryString['type'];

			$redirect = $this->redirectToRoute('_payment', $redirect_params);
	}
	return $redirect;
    }

    /**
     * this Function is used either to Refund or to Capture ( capture must be used after Authorisation "HOLD the amount of money" Command.
     * Capture NOTE: the capture must be called within 7 days, else it will be AUTORefunded to the customer.
     * @return JSON contains the status of the Refund action and the paramters returned by PayFort
     */
    public function captureRefundAction() {

	$request = $this->getRequest();
	$this->addFlightLog('Payment refund request with criteria: {criteria}', array('criteria' => $request->request->all()));

	$params = array();
	/**
	 * transaction_id
	 * operation = if REFUND or CAPTURE ( UPPERCASE )
	 * type = flight or deals
	 */
	$params['uuid'] = ($request->request->get('transaction_id') === null) ? $request->query->get('transaction_id') : $request->request->get('transaction_id');
	$params['operation'] = ($request->request->get('operation') === null) ? $request->query->get('operation') : $request->request->get('operation');
	$params['type'] = ($request->request->get('type') === null) ? $request->query->get('type') : $request->request->get('type');

	$payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneByUuid($params['uuid']);

	$response = $this->get('PayFortServices')->refundCaptureService($payment, $params);

	$res = new Response($response);
	$this->addFlightLog('Getting RefundCaptureService response: ' . $res);
	$res->headers->set('Content-Type', 'application/json');

	return $res;
    }

    /**
     * NOT USABLE
     * @param type $seotitle
     * @param type $seodescription
     * @param type $seokeywords
     * @return type
     */
    public function paymentSuccessAction($seotitle, $seodescription, $seokeywords) {

	if ($this->data['aliasseo'] == '') {
	    $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($seotitle);
	    $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($seodescription);
	    $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($seokeywords);
	}

	$request = $this->getRequest();
	$this->addFlightLog('Payment successful with criteria: {criteria}', array('criteria' => $request->request->all()));

	$this->data['showfooter'] = 0;
	$types = $request->query->get('type');
	$this->data['types'] = $types;

	return $this->render('default/register.success.twig', $this->data);
    }

    /**
     * generating SDK for Mobile application SDK
     * @return JSON contains sdk_token and other parameters used for mobile SDK
     */
    public function getSdkTokenAction() {

	$request = $this->getRequest();

	$uuid = ($request->request->get('transaction_id') === null) ? $request->query->get('transaction_id') : $request->request->get('transaction_id');
	$payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find($uuid);

	if (!$payment) {
	    $response = $this->get('PayFortServices')->responseData('', '00', '00103', $this->translator->trans('Invalid transaction ID'));
	    $res = new Response(json_encode($response));
	    $res->headers->set('Content-Type', 'application/json');

	    return $res;
	}

	for ($attemptNumber = 1; $attemptNumber <= $this->max_api_call_attempts; $attemptNumber++) {
	    $sdkToken = $this->get('PayFortServices')->sdkTokenService($payment, $request->request);
	    $this->addFlightLog("Getting sdkToken[$attemptNumber] with status:: " . $sdkToken["status"]);
	    $this->addFlightLog('With criteria: {criteria}', array('criteria' => $sdkToken));

	    if ($sdkToken["status"] == "22") { // SDK Token creation success
		break;
	    }

	    if ($attemptNumber != $this->max_api_call_attempts)
		usleep($this->pause_between_retries_us);
	}


	$this->addFlightLog('PayFortServices_SDKToken:: {criteria}', array('criteria' => $sdkToken));

	$priceInfo = $this->get('PayFortServices')->getPriceInfo($payment);
	$currency = $this->get('PayFortServices')->TEST_MODE ? 'AED' : $priceInfo['currency'];
	$price = $this->get('PayFortServices')->TEST_MODE ? 100 : $priceInfo['price'];

	$sdkInfo = array();
	$sdkInfo['command'] = "PURCHASE";
	$sdkInfo['status'] = $sdkToken['status'];
	$sdkInfo['response_code'] = $sdkToken['response_code'];
	$sdkInfo['merchant_reference'] = $sdkToken['merchant_reference'];
	$sdkInfo['response_message'] = $sdkToken['response_message'];
	$sdkInfo['sdk_token'] = array_key_exists('sdk_token', $sdkToken['data']) ? $sdkToken['data']['sdk_token'] : null;
	$sdkInfo['currency'] = $currency;
	$sdkInfo['price'] = $price;

	$res = new Response(json_encode($sdkInfo));
	$res->headers->set('Content-Type', 'application/json');

	return $res;
    }

    /**
     * after returning the valid SDK_TOKEN to MobileApplication and the mobile application make the payment, mobile send a json information to the server and the paymentOperationApiAction take the action to save the data into the database, and issuing the ticket in case of success payment.
     * @return Response
     */
    public function paymentOperationApiAction() {
	$params = array();
	$content = $this->get("request")->getContent();

	if (!empty($content)) {
	    $params = json_decode($content, true);
	}

	$request = $this->getRequest();
	$this->addFlightLog('[mobile] Payment operation request with criteria: {criteria}', array('criteria' => $request->request->all()));
	$transactionId = $request->query->get('transaction_id', '');
	$type = $request->query->get('type', '');

	$payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneByUuid($transactionId);

	$persist = $this->get('PayFortServices')->paymentPersist($payment, $params, $transactionId, 1);

	if (!$persist) {
	    $response = $this->get('PayFortServices')->responseData('', '00', '00103', $this->translator->trans('Invalid transaction ID'));
	    $res = new Response(json_encode($response));
	    $res->headers->set('Content-Type', 'application/json');

	    return $res;
	} else {
	    return $this->redirectToRoute('_issue_ticket', array('transaction_id' => $transactionId, 'type' => $type, 'from_mobile' => 1));
	}
    }

    /**
     * PayFort is always reporting to this Feedback URL, and we are saving into the database
     * @return JsonResponse
     */
    public function directTransactionFeedbackAction()
	{
		$request = $this->getRequest();

		$http_referer = $this->getRequest()->headers->get('referer');
		$this->addFlightLog("Direct Transaction Feedback:: referer:: $http_referer");

		$refererAllowed = ($http_referer?false:true);
		// $matchedRefererSource = null;

		if ($http_referer)
		{
			foreach ($this->allowedPaymentGatewayReferers as $referer_source => $allowedRefererRegex)
			{
				if (!in_array($referer_source, $this->paymentGateways)) // this is only allowed to be called by Payment Gateways
					continue;

				if (preg_match($allowedRefererRegex, $http_referer))
				{
					$refererAllowed = true;

					// $matchedRefererSource = $referer_source;

					break;
				}
			}
		}

		$response = new JsonResponse();

		$transaction = false;
		$status = 300;
		$msg = 'Failed';

		if ($refererAllowed)
		{
			$params = $request->request->all();
			if (!$params)
				$params = $request->query->all();

			if ($params)
				$transaction = $this->get('PayFortServices')->paymentTransactionFeedbackPersist($params);
		}

		if ($transaction)
		{
			$status = 200;
			$msg = 'Success';
		}

		$response->setData(array(
				'status' => $status,
				'message' => $msg
			));

		return $response;
    }

    /*
     * deviceFingerPrintAction is responsible to save into the payment table when the JavaScript in the Payment-all.js create a deviceFingerPRint and send it to the server, this finger print is used in PayFort Form Submition
     */

    public function deviceFingerPrintAction() {
	$response = new JsonResponse();

	$content = $this->get("request")->getContent();
	$responseArr = ['status' => 0, 'message' => ""];
	if (!empty($content)) {

		$transaction = null;

		if (isset($content['transaction_id']) && strlen(trim($content['transaction_id'])))
		{
			$content = json_decode($content, true);
			$transaction = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->find($content['transaction_id']);
		}

	    if ($transaction) {
		$transaction->setDeviceFingerPrint($content['device_fingerprint']);

		$em = $this->getDoctrine()->getManager();
		$em->persist($transaction);
		$em->flush();

		$responseArr = ['status' => 200, 'message' => "Success"];
	    } else {
		$responseArr = ['status' => 300, 'message' => "Failed, no transaction id"];
	    }
	} else {
	    $responseArr = ['status' => 300, 'message' => "Failed, JSON format error"];
	}

	$response->setData($responseArr);

	return $response;
    }

    public function addFlightLog($message, $params = array(), $cleanParams = false)
	{
		if ($cleanParams)
		{
			foreach (array_keys($params) as $param_key)
			{
				$this->cleanParams($params[$param_key]);
			}
		}

		foreach (array_keys($params) as $param_key)
		{
			$params[$param_key] = json_encode($params[$param_key]);
		}

		$params['userId'] = ($this->data['isUserLoggedIn'])? $this->userGetID() : 0;

		$logger = $this->get('monolog.logger.flights');
		$logger->info("\nUser {userId} - " . $message, $params);
    }
}
