<?php

namespace PaymentBundle\vendors\paytabs\v3\Model;

class PaytabsResponse
{
    private $result;
    private $response_code;
    private $amount;
    private $currency;
    private $transaction_id;
    private $card_last_four_digits;
    private $order_id;
    private $pt_invoice_id;
    
    /**
     * @return mixed
     */
    public function getCard_last_four_digits()
    {
        return $this->card_last_four_digits;
    }
    
    /**
     * @return mixed
     */
    public function getOrder_id()
    {
        return $this->order_id;
    }
    
    /**
     * @param mixed $card_last_four_digits
     */
    public function setCard_last_four_digits($card_last_four_digits)
    {
        $this->card_last_four_digits = $card_last_four_digits;
    }
    
    /**
     * @param mixed $order_id
     */
    public function setOrder_id($order_id)
    {
        $this->order_id = $order_id;
    }
    
    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * @return mixed
     */
    public function getResponse_code()
    {
        return $this->response_code;
    }
    
    /**
     * @return mixed
     */
    public function getPt_invoice_id()
    {
        return $this->pt_invoice_id;
    }
    
    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    
    /**
     * @return mixed
     */
    public function getTransaction_id()
    {
        return $this->transaction_id;
    }
    
    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
    
    /**
     * @param mixed $response_code
     */
    public function setResponse_code($response_code)
    {
        $this->response_code = $response_code;
    }
    
    /**
     * @param mixed $pt_invoice_id
     */
    public function setPt_invoice_id($pt_invoice_id)
    {
        $this->pt_invoice_id = $pt_invoice_id;
    }
    
    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
    
    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    /**
     * @param mixed $transaction_id
     */
    public function setTransaction_id($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }
}