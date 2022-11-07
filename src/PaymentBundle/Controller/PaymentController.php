<?php

namespace PaymentBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PaymentBundle\Model\PaymentInitiliser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use PaymentBundle\Model\PaymentGatewayInterface;
use PaymentBundle\Form\PaymentMethodType;
use PaymentBundle\Entity\Payment;
use Doctrine\ORM\EntityNotFoundException;
use PaymentBundle\Exception\InvalidHttpRefererException;
use PaymentBundle\vendors\paytabs\v3\PaytabsHandler;
use PaymentBundle\Services\impl\ResponseStatusIdentifier;
use PaymentBundle\vendors\paytabs\v3\PaytabsResponseCodes;

class PaymentController extends \TTBundle\Controller\DefaultController
{
    /**
     *
     * @var paymentService
     */
    private $paymentService;
    private $PROCESSED_PAYMENT = "Your payment has already been processed";

    /**
     * Load the Dafault Payment Service
     *
     * @InjectParams({
     *      "paymentService" = @Inject("payment_service"),
     * })
     */
    public function __construct(PaymentGatewayInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->PROCESSED_PAYMENT = $this->translator->trans("Your payment has already been processed");
    }

    /**
     * The callback URL to which the response from the provider to return to
     *
     * @return json
     */
    public function callbackUrlAction()
    {
        if (!$this->paymentService->isMatchHttpReferer($_SERVER['HTTP_REFERER'])) {
            throw new InvalidHttpRefererException('Invalid HTTP Referer');
        }

        $request = $this->getRequest();
        return new JsonResponse($request->query->all());
    }

    /**
     * Used to load the default payment form of the application
     * On submit the param "r=paymentTokeniser" is sent therefore the payment process
     * will be launched, if 3ds_url exists the application will be redirected to the 3ds_url
     *
     * @return \TTBundle\Controller\type|unknown
     */
    public function onHoldPaymentViewAction()
    {
        $request = $this->getRequest();
        $type    = ($request->request->get('type') === null) ? $request->query->get('type') : $request->request->get('type');

        $transaction_id = $request->query->get('transaction_id');

        $payment = $this->paymentService->getRepository()->findByUuid($transaction_id);

        // Make sure Payment exist
        if (!$payment instanceof Payment) {
            throw new EntityNotFoundException('Payment not found');
        }

        // Check if Payment is already proccessed
        if ($payment->isPaymentProcessed()) {
            $callBackUrl = $this->get('PaymentServiceImpl')->getModuleCallBackUrl($payment->getModuleId());
            return $this->redirectToLangRoute($callBackUrl["success"], array(
                'moduleId' => $payment->getModuleId(),
                'transaction_id' => $transaction_id,
                'moduleTrxId' => $transaction_id
            ));
        }

        $paymentInfo = $this->get('PaymentServiceImpl')->getPaymentInformation($transaction_id);

        // TODO IF $paymentInfo IS NULL OR EMPTY OR PROCESSED -> REDIRECT TO PAYMENT PROCESSED OR NOT VALID

        $this->data["amount"]                     = $paymentInfo->getAmount();
        $this->data["currency"]                   = $paymentInfo->getCurrency();
        $this->data["showHeaderSearch"]           = 0;
        $this->data["card_expiration_year_start"] = date('Y');
        $this->data['msg']                        = $request->query->get('msg');
        $this->data['transactionId']              = $transaction_id;
        $this->data['type']                       = $paymentInfo->getModuleName();
        $this->data['currency']                   = $paymentInfo->getCurrency();
        $this->data['customerName']               = $paymentInfo->getCustomerFullName();

        if ($request->query->get('r') == 'paymentTokeniser') {
            $uuid = $paymentInfo->getNumber();

            $ccInfo                     = array();
            $ccInfo["card_holder_name"] = $request->request->get('card_holder_name');
            $ccInfo["expiry_month"]     = $request->request->get('expiry_month');
            $ccInfo["expiry_year"]      = $request->request->get('expiry_year');
            $ccInfo["cardnumber"]       = $request->request->get('card)number');
            $ccInfo["cvv"]              = $request->request->get('cvv');

            $tokenisation = $this->get('PaymentServiceImpl')->processPayment($uuid, $ccInfo);

            // if (isset($toknisation['return_url'])) return $this->redirect($toknisation['return_url']);
            if (isset($tokenisation['return_url'])) {
                $return_url = $tokenisation['return_url'];

                if ($tokenisation['success']) {
                    $tmp          = isset($tokenisation["data"]) ? $tokenisation["data"] : array(); // array("success" => $tokenisation["success"]);
                    $tokenisation = array_merge($tokenisation, $tmp);
                    unset($tokenisation['3ds_url']);
                    unset($tokenisation['return_url']);
                }
                unset($tokenisation['data']);

                return $this->redirectDynamicRoute($return_url, $tokenisation);
            }
        }

        $is_corporate = $this->get('FlightServices')->isCorpoSite();
        if ($is_corporate) {
            return $this->render('@Corporate/corporate/corporate-payment_1.twig', $this->data);
        } else {
            return $this->render('@Payment/payment/payment_view_2.twig', $this->data);
        }
    }

    /**
     * This function is in progress of updateds it will take over soon
     *
     * This action is used to load the default payment form of the application
     * This action implements the standard Form Types
     * Once payment is valid, It redirects you to the page based on return url
     *
     * @return mixed
     */
    public function paymentViewAction(Request $request)
    {
        $transaction_id = $request->query->get('transaction_id');

        $payment = $this->paymentService->getRepository()->findByUuid($transaction_id);

        // Make sure Payment exist
        if (!$payment instanceof Payment) {
            throw new EntityNotFoundException('Payment not found');
        }

        // Check if Payment is already proccessed
        if ($payment->isPaymentProcessed()) {
            $callBackUrl = $this->get('PaymentServiceImpl')->getModuleCallBackUrl($payment->getModuleId());
            return $this->redirectToLangRoute($callBackUrl["success"], array(
                'moduleId' => $payment->getModuleId(),
                'transaction_id' => $transaction_id,
                'moduleTrxId' => $transaction_id
            ));
        }

        // Instantiate Payment Service based on type of provider.
        // Thru Payment Factory
        // 2 = Payfort else Coporate|Other?
        // $gatewayService = $this->get('PaymentServiceImpl')->getGatewayService( $payment->getModuleId() );

        $gatewayService = $this->get('PaymentServiceImpl')->getGatewayService($payment->getPaymentProvider());

        if ($gatewayService['id'] == 3) {
            $this->paymentService = $this->get('payment_gateway_factory')
            ->setPaymentGateway($this->get($gatewayService['gatewayService']))
            ->build();

            $form                                     = $this->createForm(new PaymentMethodType(), $payment, array(
                'payment_service' => $this->paymentService
            ));
            $this->data["card_expiration_year_start"] = date('Y');
            if ($request->getMethod() === 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $callback_data = get_object_vars(json_decode($form->get('server_callback_data')->getData()));
                    $return_url    = $callback_data['return_url'];
                    return $this->redirectDynamicRoute($return_url, $callback_data);
                }
            }

            // TODO: check if corporate account
            $is_corporate = $this->data['is_corporate_account'];

            $this->data['form']    = $form->createView();
            $this->data['payment'] = $payment;

            if ($is_corporate) {
                $msg = $request->query->get('msg');
                if ($msg) {
                    $corporateNotificationArray[]             = $this->addNotification($msg, "warning", 0);
                    $this->data['corporateNotificationArray'] = json_encode($corporateNotificationArray);
                }
                return $this->render('@Corporate/corporate/corporate-payment.twig', $this->data);
            } else {
                return $this->render('@Payment/payment/payment_view.twig', array(
                    'form' => $form->createView(),
                    'payment' => $payment,
                    'showHeaderSearch' => 0
                ));
            }
        } elseif ($gatewayService['id'] == 4) {
            $this->data['showHeaderSearch'] = 0;
            $this->data['isCorpoSite']      = $this->container->get('app.utils')->isCorporateSite();

            $this->data['payment']     = $payment;
            $this->data['description'] = $this->translator->trans('TouristTube Booking');

            // check payment attempts made with this uuid
            $this->data['attemptCount']      = $this->get('PaymentServiceImpl')->countPaymentAttempt($transaction_id);
            $this->data['moduleLandingPage'] = '';

            if ($this->data['attemptCount'] < $this->container->getParameter('modules')['payment']['vendors']['areeba']['paymentAttempts']) {
                // get session id from provider
                $session = $this->get('PaymentServiceImpl')->createCheckoutSession($transaction_id);

                if ($session['success']) {
                    $this->data['sessionId']            = $session['session.id'];
                    $this->data['sessionVersion']       = $session['session.version'];
                    $this->data['successIndicator']     = $session['successIndicator'];
                    $this->data['transactionReference'] = $session['transactionReference'];
                    $this->data['orderId']              = $payment->getUuid();

                    $this->data['callbackUrl'] = "/payment-checkout-callback";
                } else {
                    $this->data['error'] = $session['error'];
                }
            } else {
                $callBackUrl                     = $this->get('PaymentServiceImpl')->getModuleCallBackUrl($payment->getModuleId());
                $this->data['moduleLandingPage'] = $this->generateUrl($callBackUrl["module_landing_page"]);

                $this->data['error'] = $this->translator->trans('You have exceeded the number of tries allowed for your transaction. You are being redirected to try again.');
            }

            $this->data['gatewayCheckoutURL'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['gatewayCheckoutURL'];

            $this->data['merchant_name'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_name'];

            $this->data['merchant_email'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_email'];

            $this->data['merchant_phone'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_phone'];

            $this->data['merchant_logo'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_logo'];

            $this->data['merchant_id'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_id'];

            $this->data['paymentAttempts'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['paymentAttempts'];

            return $this->render('@Payment/areeba/areeba_payment_checkout.twig', $this->data);
        } else {
            $this->data['payment']     = $payment;
            $this->data['isCorpoSite'] = $this->container->get('app.utils')->isCorporateSite();
            $this->data['merchant']    = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_id'];
            $this->data['key']         = $this->container->getParameter('paytabs')['secret_key'];
            $isCorporate               = $this->container->get('app.utils')->isCorporateSite();
            if ($isCorporate) {
                $this->data['callbackUrl'] = $this->container->getParameter('paytabs')['corpo_callback_url'];
            } else {
                $this->data['callbackUrl'] = $this->container->getParameter('paytabs')['callback_url'];
            }
            $parameters ['module_transaction_id'] = $payment->getModuleTransactionId();
            $parameters ['uuid']                  = $payment->getUuid();

            $phoneDetails = $this->get('PaymentServiceImpl')->getPhoneNumberInfo($parameters);
            $moduleId     = $payment->getModuleId();
            if ($moduleId == $this->container->getParameter('MODULE_FLIGHTS')) {
                $this->data['mobileCountryCode'] = $phoneDetails[0]['flightsDialingCode'];
                $this->data['mobileNumber']      = $phoneDetails[0]['flightsMobilePhone'];
            } elseif ($moduleId == $this->container->getParameter('MODULE_HOTELS')) {
                $this->data['mobileCountryCode'] = $phoneDetails[0]['hotelsDialingCode'];
                $this->data['mobileNumber']      = $phoneDetails[0]['hotelsMobilePhone'];
            } elseif ($moduleId == $this->container->getParameter('MODULE_DEALS')) {
                $this->data['mobileCountryCode'] = $phoneDetails[0]['dealsDialingCode'];
                $this->data['mobileNumber']      = $phoneDetails[0]['dealsMobilePhone'];
            }

            return $this->render('@Payment/paytabs/paytabsPaymentCheckout.twig', $this->data);
        }
    }

    public function paymentNewAction(Request $request)
    {
        return $this->render('@Payment/payment/payment_new.twig', $this->data);
    }

    /**
     * This callback action will be called after processing the payment
     * usually after the 3ds from payfort
     *
     * @return unknown
     */
    public function paymentProcessCallbackAction()
    {
        if (isset($_SERVER['HTTP_REFERER']) && !$this->paymentService->isMatchHttpReferer($_SERVER['HTTP_REFERER'])) {
            // throw new InvalidHttpRefererException('Invalid HTTP Referer');
        }
        /* Those params are used in payfort case */
        // $request = $this->getRequest();
        // $response['success'] = $request->query->get('success');
        // $response['message'] = $request->query->get('message');
        // $response['status'] = $request->query->get('status');
        // $response['return_url'] = $request->query->get('return_url');
        // $response['code'] = $request->query->get('response_code');
        // $response['message'] = $request->query->get('response_message');
        // $response['module'] = $request->query->get('module'); //module code
        // $response['data'] = $request->query->all();

        $request  = $this->getRequest();
        $response = $request->query->all();

        $trxId = null;
        if (isset($response["transaction_id"])) {
            $trxId = $response["transaction_id"];
        }

        /*
         * if (isset($response['data']['transaction_id'])) {
         * $trxId = $response['data']['transaction_id'];
         * }
         */

        if (!$trxId) return $this->redirectToLangRoute('_welcome');

        $paymentInfo        = $this->get('PaymentServiceImpl')->getPaymentInformation($trxId);
        $response['module'] = $paymentInfo->getTrxTypeId();
        if (!isset($response['merchant_reference']) || !$response['merchant_reference']) {
            $response['merchant_reference'] = $paymentInfo->getMerchantReference();
        }

        if (!$paymentInfo) return $this->redirectToLangRoute('_welcome');

        if (!isset($response['success']) && strtolower($response['message']) == "success") {
            $response['success'] = true;
        }

        $params = array();

        if ($response) {
            $params = array(
                'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_APPROVED'),
                'moduleId' => $paymentInfo->getTrxTypeId(), // $this->container->getParameter('MODULE_FLIGHTS'),
                'reservationId' => $paymentInfo->getModuleTransactionId()
            );
        } else {
            $params = array(
                'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_CANCELED'),
                'moduleId' => $paymentInfo->getTrxTypeId(), // $this->container->getParameter('MODULE_FLIGHTS'),
                'reservationId' => $paymentInfo->getModuleTransactionId()
            );
        }

        $updatingPendingRequest = $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($params);

        unset($response['data']['return_url']);

        // if (!isset($response['module']) && isset($response['data']) && isset($trxId)) {
        if (!isset($response['module']) && isset($response) && isset($trxId)) {
            $paymentInfo        = $this->get('PaymentServiceImpl')->getPaymentInformation($trxId);
            $response['module'] = $paymentInfo->getTrxTypeId();
        }
        //
        // $this->get('PaymentServiceImpl')->updateDBHistory($response['data'], $trxId);
        $this->get('PaymentServiceImpl')->updateDBHistory($response, $trxId);

        //
        return $this->redirectToRoute('_root_result', array(
            'success' => $response['success'],
            'transaction_id' => $trxId,
            'module_trx_id' => $trxId,
            'moduleId' => $response['module']
        ));
    }

    /**
     * Called after finalizing the payment process and this action will be used to
     * retrieve the last URL to be called, if the used module has a callback url
     * it will be redirected to it, otherwise we will redirect our page to the default URL
     *
     * @param Request $request
     * @return \TTBundle\Controller\type
     */
    public function resultAction(Request $request)
    {
        $request           = $this->getRequest();
        $result            = array();
        $result['success'] = $request->query->get('success');

        $result['moduleId']       = $request->query->get('moduleId');
        $result['transaction_id'] = $request->query->get('transaction_id');
        $result['moduleTrxId']    = $request->query->get('module_trx_id');
        $callBackUrl              = $this->get('PaymentServiceImpl')->getModuleCallBackUrl($result['moduleId']);

        if ($result['success'] == false) {
            $callBackUrl = $callBackUrl["failed"];
        } else {
            $callBackUrl = $callBackUrl["success"];
        }

        return $this->redirectToLangRoute($callBackUrl, $result);
    }

    /**
     * paymentCaptureAction
     *
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function paymentCaptureAction()
    {
        $request = $this->getRequest();
        $trxId   = $request->query->get('transaction_id');

        $response = $this->get('PaymentServiceImpl')->captureOnHoldPayment($trxId);

        return new JsonResponse($response);
    }

    /**
     * paymentRefundAction
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function paymentRefundAction()
    {
        $request = $this->getRequest();
        $trxId   = $request->query->get('transaction_id');

        $response = $this->get('PaymentServiceImpl')->refund($trxId);

        return new JsonResponse($response);
    }

    /**
     * paymentVoidHoldAction
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function paymentVoidHoldAction()
    {
        $request = $this->getRequest();
        $trxId   = $request->query->get('transaction_id');

        $response = $this->get('PaymentServiceImpl')->voidOnHoldPayment($trxId);

        return new JsonResponse($response);
    }

    public function corpoOnAccountAction()
    {
        $request = $this->getRequest();
        $trxId   = $request->query->get('transaction_id');

        $userInfo = array();
        $userId   = $this->userGetID();

        $userArray          = $this->get('UserServices')->getUserDetails(array(
            'id' => $userId
        ));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

        $userInfo['userId']    = $userId;
        $userInfo['accountId'] = $userCorpoAccountId;

        $proccess = $this->get('PaymentServiceImpl')->processPayment($trxId, NULL);

        $paymentInfo = $this->get('PaymentServiceImpl')->getPaymentInformation($trxId);

        $params = array(
            'accountId' => $userInfo['accountId'],
            'userId' => $userId,
            'paymentId' => $trxId,
            'moduleId' => $paymentInfo->getTrxTypeId(), // $this->container->getParameter('MODULE_FLIGHTS'),
            'reservationId' => $paymentInfo->getModuleTransactionId(),
            'currencyCode' => $paymentInfo->getCurrency(),
            'amount' => $paymentInfo->getAmount()
        );

        $addAccountTrx = $this->get('CorpoAccountTransactionsServices')->addAccountTransactions($params);

        if (isset($proccess['return_url'])) {

            return $this->redirectDynamicRoute($proccess['return_url'], $proccess);
        }
    }

    /**
     * This function handles the callback of the paymentgateway and check the status of the transaction_id and paymentgatewayid to
     * see if the payment was already processed or not and adds the customer info in the payment info table
     * @return unknown
     */
    public function paymentGatewayCallbackAction()
    {
        $validPayment = 4;
        $response     = json_encode($_REQUEST);

        $paymentResponseObject                = $this->get('PaymentServiceImpl')->getPaymentResponseObject($response);
        $trxId                                = $paymentResponseObject->getOrder_id();
        $payment                              = $this->paymentService->getRepository()->findByUuid($trxId);
        //
        $parameters ['module_transaction_id'] = $payment->getModuleTransactionId();
        $parameters ['uuid']                  = $payment->getUuid();
        $phoneDetails                         = $this->get('PaymentServiceImpl')->getPhoneNumberInfo($parameters);
        $moduleId                             = $payment->getModuleId();


        if ($moduleId == $this->container->getParameter('MODULE_FLIGHTS')) {
            $this->data['mobileCountryCode'] = $phoneDetails[0]['flightsDialingCode'];
            $this->data['mobileNumber']      = $phoneDetails[0]['flightsMobilePhone'];
        } elseif ($moduleId == $this->container->getParameter('MODULE_HOTELS')) {
            $this->data['mobileCountryCode'] = $phoneDetails[0]['hotelsDialingCode'];
            $this->data['mobileNumber']      = $phoneDetails[0]['hotelsMobilePhone'];
        } elseif ($moduleId == $this->container->getParameter('MODULE_DEALS')) {
            $this->data['mobileCountryCode'] = $phoneDetails[0]['dealsDialingCode'];
            $this->data['mobileNumber']      = $phoneDetails[0]['dealsMobilePhone'];
        }
        //
        $status = $payment->getStatus();
        if ($status == $validPayment) {
            $this->data['message'] = $this->PROCESSED_PAYMENT;
            return $this->render('@Payment/paytabs/paymentNotApproved.twig', $this->data);
        }
        //
        else {

            $status          = 0;
            $merchantid      = $paymentResponseObject->getTransaction_id();
            $merchantIdCheck = $this->paymentService->getRepository()->findByFortId($merchantid);
            if ($merchantIdCheck) {
                $status = $merchantIdCheck->getStatus();
                if ($status == $validPayment) {
                    $this->data['message'] = $this->PROCESSED_PAYMENT;
                    return $this->render('@Payment/paytabs/paymentNotApproved.twig', $this->data);
                }
            }

            $this->data['key']      = $this->container->getParameter('paytabs')['secret_key'];
            $this->data['merchant'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_id'];
            $isCorporate            = $this->container->get('app.utils')->isCorporateSite();
            //
            if ($isCorporate) {
                $this->data['callbackUrl'] = $this->container->getParameter('paytabs')['corpo_callback_url'];
            } else {
                $this->data['callbackUrl'] = $this->container->getParameter('paytabs')['callback_url'];
            }
            //
            $paytabsResponseCodes = new PaytabsResponseCodes();
            $genericResponseCodes = $paytabsResponseCodes->getGenericResponseCode($paymentResponseObject->getResponse_code());
            $paymentResponseObject->setStatus($genericResponseCodes);
            $paymentResponseObject->setResponse_code($paymentResponseObject->getResponse_code());

            $paymentInfo = $this->get('PaymentServiceImpl')->createPaymentInfo($paymentResponseObject);
            //
            //
            if (ResponseStatusIdentifier::isSuccess($genericResponseCodes)) {

                $response = $this->get('PaymentServiceImpl')->getPaymentStatus($paymentResponseObject);

                if ($response['success'] == "true") {
                    $response['transaction_id'] = $paymentResponseObject->getOrder_id();
                    return $this->redirectToLangRoute('_payment_processRequest', $response);
                } else {
                    /* if verify payment is not true */
                    $this->data['payment']     = $payment;
                    $this->data['paymentInfo'] = $paymentInfo;
                    $this->data['isCorpoSite'] = $this->container->get('app.utils')->isCorporateSite();

                    return $this->render('@Payment/paytabs/paytabsPaymentCheckout.twig', $this->data);
                }
            } else {
                /* if response is invalid in payment */
                $this->data['payment']     = $payment;
                $this->data['paymentInfo'] = $paymentInfo;
                $this->data['isCorpoSite'] = $this->container->get('app.utils')->isCorporateSite();

                return $this->render('@Payment/paytabs/paytabsPaymentCheckout.twig', $this->data);
            }
        }
    }

    public function paymentGatewayCheckoutCallbackAction()
    {
        $request  = $this->getRequest();
        $response = $request->query->all();

        $this->data['results'] = array('response' => $response);

        $this->get('PaymentServiceImpl')->countPaymentAttempt($response['orderId']);

        $this->data['gatewayCheckoutURL'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['gatewayCheckoutURL'];

        $this->data['merchant_name'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_name'];

        $this->data['merchant_email'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_email'];

        $this->data['merchant_phone'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_phone'];

        $this->data['merchant_logo'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_logo'];

        $this->data['merchant_id'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['merchant_id'];

        $this->data['paymentAttempts'] = $this->container->getParameter('modules')['payment']['vendors']['areeba']['paymentAttempts'];

        if ($response['successIndicator'] == $response['resultIndicator']) {
            $transactionResponse = $this->get('PaymentServiceImpl')->verifyPaymentTransaction($response);

            if ($transactionResponse['success']) {
                return $this->redirectToLangRoute('_payment_processRequest', $transactionResponse);
            } else {
                $this->data['error'] = $transactionResponse['error'];
                return $this->render('@Payment/areeba/areeba_payment_checkout.twig', $this->data);
            }
        } else {
            $this->data['error'] = 'Payment Failed.';
            return $this->render('@Payment/areeba/areeba_payment_checkout.twig', $this->data);
        }
    }
}
