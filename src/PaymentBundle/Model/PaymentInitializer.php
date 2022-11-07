<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PaymentBundle\Model;


/**
 * Description of PaymentInitializer
 *
 * @author para-soft7
 */
class PaymentInitializer
{
    /**
     * get the call back url of the payment, ( if creditcard the _paymentView is opened, else the onAccount url is set )
     * @var Url String
     */
    private $callBackUrl;

    /**
     * the transaction Id of a payment
     * @var  varcHar unique Id
     */
    private $transactionId;

    /*
     * set the callBackUrl Based on the PaymentType
     */
    public function setCallBackUrl($callBackUrl)
    {
        $this->callBackUrl = $callBackUrl;
    }

    /*
     * get the Call Back Url
     */
   public function getCallBackUrl()
    {
        return $this->callBackUrl;
    }

     /*
     * set the callBackUrl Based on the PaymentType
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /*
     * get the Call Back Url
     */
   public function getTransactionId()
    {
        return $this->transactionId;
    }

}
