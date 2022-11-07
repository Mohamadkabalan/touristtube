<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PaymentBundle\vendors\payfort\v2;

use PaymentBundle\Model\Payment;

/**
 * Description of payfortMethod
 *
 * @author para-soft7
 */
//TODO implements PaymentServiceProvider
class PayfortHandler implements \PaymentBundle\Services\PaymentServiceProvider
{
    protected $payment;
    
    //TODO MAKE THE MATCH WITH THE PAYMENT ENTITY COMMAND TYPES
    private function matchCommands($command)
    {
        $result = $command;
        switch ($command) {
            case Payment::CMD_HOLD_PAYMENT;
            $result = "AUTHORIZATION";
            break;
            case Payment::CMD_PROCESS_PAYMENT;
            $result = "PURCHASE";
        }
        //
        return $result;
    }
    
    public function __construct()
    {
        
    }
    
    public function tokenise($param, $uuid, $paymentInfo)
    {

        $paymentInfo->setCommand($this->matchCommands($paymentInfo->getCommand()));
        
        $objPayfort = new PayfortIntegration($paymentInfo);
        
        return $objPayfort->tokenize($param, $uuid);
    }
    
    public function processPayment($trxId, $paymentInfo, $tokenResultJson, $userInfo = NULL)
    {
        
        $paymentInfo->setCommand($this->matchCommands($paymentInfo->getCommand()));
        
        $objPayfort = new PayfortIntegration($paymentInfo);
        
        return $objPayfort->processMerchantPageResponse($trxId, $tokenResultJson["data"]);
        
    }
    
    public function processResponse($uuid, $payment, $jsonResponse)
    {
        $payment->setCommand($this->matchCommands($payment->getCommand()));
        $objPayfort = new PayfortIntegration($payment);
        
        $objPayfort->processResponse($uuid, $jsonResponse);
    }
    
    public function captureOnHoldPayment($paymentInfo)
    {
        $paymentInfo->setCommand($this->matchCommands($paymentInfo->getCommand()));
        $objPayfort    = new PayfortIntegration($paymentInfo);
        $captureStatus = $objPayfort->capture();
        
        if ($captureStatus["status"] == "04" && $captureStatus["response_message"] == "Success") {
            $captureResponse = array("success" => true, "data" => $captureStatus);
        } else {
            $captureResponse = array("success" => false, "data" => $captureStatus);
        }
        return $captureResponse;
    }
    
    public function refund($paymentInfo)
    {
        $paymentInfo->setCommand($this->matchCommands($paymentInfo->getCommand()));
        $objPayfort   = new PayfortIntegration($paymentInfo);
        $refundStatus = $objPayfort->refund();
        
        if ($refundStatus["status"] == "06" && $refundStatus["response_message"] == "Success") {
            $refundResponse = array("success" => true, "data" => $refundStatus);
        } else {
            $refundResponse = array("success" => false, "data" => $refundStatus);
        }
        return $refundResponse;
    }
    
    public function voidOnHoldPayment($paymentInfo)
    {
        
        $paymentInfo->setCommand($this->matchCommands($paymentInfo->getCommand()));
        $objPayfort       = new PayfortIntegration($paymentInfo);
        $voidOnHoldStatus = $objPayfort->VoidOnHold();
        
        if ($voidOnHoldStatus["status"] == "08" && $voidOnHoldStatus["response_message"] == "Success") {
            $voidOnHoldResponse = array("success" => true, "data" => $voidOnHoldStatus);
        } else {
            $voidOnHoldResponse = array("success" => false, "data" => $voidOnHoldStatus);
        }
        return $voidOnHoldResponse;
    }
}