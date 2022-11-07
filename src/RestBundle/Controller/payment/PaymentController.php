<?php

namespace RestBundle\Controller\payment;

use RestBundle\Controller\TTRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use TTBundle\Utils\TTSerializerUtils;
use RestBundle\Model\RestPaymentCallBackVO;
use RestBundle\Model\RestPaymentGatewayVendor;
use RestBundle\Model\RestBookingResponseVO;

class PaymentController extends TTRestController
{

    /**
     * This method handles the payment of corporate-on-account type and redirects to the correct route computed
     */
    public function corpoOnAccountAction()
    {
        $trxId = $this->get("request")->query->get('transaction_id');

        $userInfo = array();
        $userId   = $this->userGetID();

        $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

        $userInfo['userId']    = $userId;
        $userInfo['accountId'] = $userCorpoAccountId;

        $proccess = $this->get('PaymentServiceImpl')->processPayment($trxId, NULL);

        $paymentInfo = $this->get('PaymentServiceImpl')->getPaymentInformation($trxId);

        $params = array(
            'accountId' => $userInfo['accountId'],
            'userId' => $userId,
            'paymentId' => $trxId,
            'moduleId' => $paymentInfo->getTrxTypeId(), //$this->container->getParameter('MODULE_FLIGHTS'),
            'reservationId' => $paymentInfo->getModuleTransactionId(),
            'currencyCode' => $paymentInfo->getCurrency(),
            'amount' => $paymentInfo->getAmount()
        );

        $addAccountTrx = $this->get('CorpoAccountTransactionsServices')->addAccountTransactions($params);

        if (isset($proccess['return_url'])) {
            return $this->forwardToRoute('_rest'.$proccess['return_url'], $proccess);
        } else {
            throw new HttpException(400, $this->translator->trans("Invalid transaction id."));
        }
    }

    /**
     * This callback action will be called after processing the payment
     *
     * @return unknown
     */
    public function paymentProcessRequestAction()
    {
        $response = $this->get("request")->query->all();

        $trxId = null;
        if (isset($response["uuid"])) {
            $trxId = $response["uuid"];
        } else if (isset($response['data']['transaction_id'])) {
            $trxId = $response['data']['transaction_id'];
        }

        if (!$trxId) {
            throw new HttpException(400, $this->translator->trans("Missing payment transaction id."));
        }

        $paymentInfo = $this->get('PaymentServiceImpl')->getPaymentInformation($trxId);

        $response['module'] = $paymentInfo->getTrxTypeId();
        if (!isset($response['merchant_reference'])) {
            $response['merchant_reference'] = $paymentInfo->getMerchantReference();
        }

        if (!$paymentInfo) {
            throw new HttpException(400, $this->translator->trans("Missing payment info."));
        }

        if (!isset($response['success']) && isset($response['message']) && strtolower($response['message']) == "success") {
            $response['success'] = true;
        }

        $message = ($response['success']) ? 'success' : failed;

        $params = array();

        if ($response['success']) {
            $params = array(
                'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_APPROVED'),
                'moduleId' => $paymentInfo->getTrxTypeId(), //$this->container->getParameter('MODULE_FLIGHTS'),
                'reservationId' => $paymentInfo->getModuleTransactionId()
            );
        } else {
            $params = array(
                'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_CANCELED'),
                'moduleId' => $paymentInfo->getTrxTypeId(), //$this->container->getParameter('MODULE_FLIGHTS'),
                'reservationId' => $paymentInfo->getModuleTransactionId()
            );
        }

        $updatingPendingRequest = $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($params);

        unset($response['return_url']);

        if (!isset($response['module']) && isset($response['data']) && isset($trxId)) {
            $paymentInfo        = $this->get('PaymentServiceImpl')->getPaymentInformation($trxId);
            $response['module'] = $paymentInfo->getTrxTypeId();
        }

        $this->get('PaymentServiceImpl')->updateDBHistory($response['data'], $trxId);

        $view = $this->routeRedirectView('_rest_payment_callback', array(
            'success' => $response['success'],
            'moduleId' => $response['module'],
            'paymentId' => $trxId,
            'moduleTransactionId' => $trxId,
            'vendor' => array(
                'id' => "",
                'responseCode' => "",
                'message' => $message
            )), 301);

        return $this->handleView($view);
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
        $return = new RestBookingResponseVO;

        if (!empty($this->get("request")->getContent())) {
            $request = $this->get("request")->getContent();
        } else {
            $request = json_encode($this->get("request")->query->all());
        }

        $serializer = new TTSerializerUtils();
        $result     = $serializer->deserializeJsonToObject($request, RestPaymentCallBackVO::class);
        $vendor     = $serializer->deserializeJsonToObject(json_encode($result->getVendor()), RestPaymentGatewayVendor::class);

        if (!empty($result->getPaymentId())) {
            $callBackUrl = $this->get('PaymentServiceImpl')->getModuleCallBackUrl($result->getModuleId());

            $message = $vendor->getMessage();
            if ($vendor->getMessage() !== "success") {
                $success     = false;
                $callBackUrl = $callBackUrl["rest_failed"];
            } else {
                $success     = true;
                $callBackUrl = $callBackUrl["rest_success"];
            }

            $routes = $this->container->get('router')->getRouteCollection();
            $action = $routes->get($callBackUrl)->getDefaults()['_controller'];
            $query  = array(
                'success' => $success,
                'moduleId' => $result->getModuleId(),
                'transaction_id' => $result->getPaymentId(),
                'moduleTrxId' => $result->getModuleTransactionId(),
            );

            try {
                //forward
                $response = $this->forward($action, array(), $query);

                if (!empty($response->getContent())) {
                    $response = json_decode($response->getContent(), true);

                    if (isset($response['code']) && $response['code'] == 400) {
                        $success = false;
                        $message = $response['message'];

                        unset($response['code'], $response['message']);
                    }

                    $return->setSuccess($success);
                    $return->setMessage($message);

                    switch ($result->getModuleId()) {
                        case $this->container->getParameter('MODULE_HOTELS'):
                            $return->setHotelsBookingVO($response);
                            break;

                        default:
                            break;
                    }
                } else {
                    return $response;
                }
            } catch (Exception $ex) {
                $return->setSuccess(false);
                $return->setMessage($ex);
            }
        }
        return $return;
    }
}
