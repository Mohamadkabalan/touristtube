<?php

namespace PaymentBundle\vendors\paytabs\v3\Model;

class PaytabsPaymentResponse
{
    private $transaction_id;
    private $order_id;
    private $response_code;
    private $response_message;
    private $customer_name;
    private $customer_email;
    private $transaction_amount;
    private $transaction_currency;    
    private $customer_phone;
    private $last_4_digits;
    private $first_4_digits;
    private $card_brand;
    private $trans_date;
    private $secure_sign;
    private $status;
    

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getOrder_id()
    {
        return $this->order_id;
    }
    
    /**
     * @return mixed
     */
    public function getTransaction_id()
    {
        return $this->transaction_id;
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
    public function getResponse_message()
    {
        return $this->response_message;
    }
    
    /**
     * @return mixed
     */
    public function getTransaction_amount()
    {
        return $this->transaction_amount;
    }
    
    /**
     * @return mixed
     */
    public function getTransaction_currency()
    {
        return $this->transaction_currency;
    }
    
    /**
     * @return mixed
     */
    public function getCustomer_name()
    {
        return $this->customer_name;
    }
    
    /**
     * @return mixed
     */
    public function getCustomer_email()
    {
        return $this->customer_email;
    }
    
    /**
     * @return mixed
     */
    public function getCustomer_phone()
    {
        return $this->customer_phone;
    }
    
    /**
     * @return mixed
     */
    public function getLast_4_digits()
    {
        return $this->last_4_digits;
    }
    
    /**
     * @return mixed
     */
    public function getFirst_4_digits()
    {
        return $this->first_4_digits;
    }
    
    /**
     * @return mixed
     */
    public function getCard_brand()
    {
        return $this->card_brand;
    }
    
    /**
     * @return mixed
     */
    public function getTrans_date()
    {
        return $this->trans_date;
    }
    
    /**
     * @return mixed
     */
    public function getSecure_sign()
    {
        return $this->secure_sign;
    }
    
    /**
     * @param mixed $order_id
     */
    public function setOrder_id($order_id)
    {
        $this->order_id = $order_id;
    }
    
    /**
     * @param mixed $transaction_id
     */
    public function setTransaction_id($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }
    
    /**
     * @param mixed $response_code
     */
    public function setResponse_code($response_code)
    {
        $this->response_code = $response_code;
    }
    
    /**
     * @param mixed $response_message
     */
    public function setResponse_message($response_message)
    {
        $this->response_message = $response_message;
    }
    
    /**
     * @param mixed $transaction_amount
     */
    public function setTransaction_amount($transaction_amount)
    {
        $this->transaction_amount = $transaction_amount;
    }
    
    /**
     * @param mixed $transaction_currency
     */
    public function setTransaction_currency($transaction_currency)
    {
        $this->transaction_currency = $transaction_currency;
    }
    
    /**
     * @param mixed $customer_name
     */
    public function setCustomer_name($customer_name)
    {
        $this->customer_name = $customer_name;
    }
    
    /**
     * @param mixed $customer_email
     */
    public function setCustomer_email($customer_email)
    {
        $this->customer_email = $customer_email;
    }
    
    /**
     * @param mixed $customer_phone
     */
    public function setCustomer_phone($customer_phone)
    {
        $this->customer_phone = $customer_phone;
    }
    
    /**
     * @param mixed $last_4_digits
     */
    public function setLast_4_digits($last_4_digits)
    {
        $this->last_4_digits = $last_4_digits;
    }
    
    /**
     * @param mixed $first_4_digits
     */
    public function setFirst_4_digits($first_4_digits)
    {
        $this->first_4_digits = $first_4_digits;
    }
    
    /**
     * @param mixed $card_brand
     */
    public function setCard_brand($card_brand)
    {
        $this->card_brand = $card_brand;
    }
    
    /**
     * @param mixed $trans_date
     */
    public function setTrans_date($trans_date)
    {
        $this->trans_date = $trans_date;
    }
    
    /**
     * @param mixed $secure_sign
     */
    public function setSecure_sign($secure_sign)
    {
        $this->secure_sign = $secure_sign;
    }
}