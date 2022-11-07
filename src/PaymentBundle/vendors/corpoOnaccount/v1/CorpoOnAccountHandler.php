<?php

namespace PaymentBundle\vendors\corpoOnaccount\v1;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;
use PaymentBundle\vendors\Config as PaymentConfig;

class CorpoOnAccountHandler implements \PaymentBundle\Services\PaymentServiceProvider
{

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = new PaymentConfig($container);
    }

    public function captureOnHoldPayment($trxId)
    {

    }

    public function processPayment($trxId, $paymentInfo, $resultJson)
    {

        return array('success' => true, 'return_url' => '_payment_processRequest', 'data' => array('transaction_id' => $trxId, 'command' => $this->config->bypassCommand));
    }

    public function processResponse($trxId, $paymentInfo, $jsonResponse)
    {

    }

    public function refund($trxId)
    {
        return array('success' => true, 'data' => array('transaction_id' => $trxId, 'status' => '06', 'response_code' => '0600', 'response_message' => $this->config->bypassCommand, 'command' => $this->config->refundCommand));
    }

    public function tokenise($param, $uuid, $paymentInfo)
    {
        return array('success' => true, 'return_url' => '_payment_processRequest', 'data' => array('transaction_id' => $param, 'command' => $this->config->bypassCommand));
    }

    public function voidOnHoldPayment($trxId)
    {

    }
    
}
