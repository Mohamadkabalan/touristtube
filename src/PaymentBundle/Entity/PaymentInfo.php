<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PaymentInfo
 
 *
 * @ORM\Entity
 * @ORM\Table(name="payment_info")
 */
class PaymentInfo
{
    /**
     * @var id
     * @ORM\Column(name="id", type="int", length=11, nullable=false)
     * @ORM\Id
     */
    private $id;
    
    /**
     * @var decimal
     * @ORM\Column(name="amount", type="decimal", nullable=false)
     */
    private $amount;
    
    /**
     * @var string
     * @ORM\Column(name="currency", type="String", length=10, nullable=false)
     */
    private $currency;
    
    /**
     * @var string
     * @ORM\Column(name="customer_name", type="String", length=100, nullable=false)
     */
    private $customerName;
    
    /**
     * @var string
     * @ORM\Column(name="customer_email", type="String", length=50, nullable=false)
     */
    private $customerEmail;
    
    /**
     * @var string
     * @ORM\Column(name="customer_phone", type="String", length=20, nullable=false)
     */
    private $customerPhone;
    
    /**
     * @var string
     * @ORM\Column(name="last_4_digits", type="String", length=4, nullable=true)
     */
    private $last4Digits;
    
    /**
     * @var string
     * @ORM\Column(name="first_4_digits", type="String", length=4, nullable=true)
     */
    private $first4Digits;
    
    /**
     * @var string
     * @ORM\Column(name="card_type", type="String", length=20, nullable=false)
     */
    private $cardType;
    
    /**
     * @var datetime
     * @ORM\Column(name="payment_date", type="String", length=50, nullable=false)
     */
    private $paymentDate;
    
    /**
     * @var string
     * @ORM\Column(name="secure_sign", type="String", length=100, nullable=true)
     */
    private $secureSign;
    
    /**
     * @var string
     * @ORM\Column(name="payment_id", type="String", length=36, nullable=false)
     */
    private $paymentId;
    
    /**
     * @var string
     * @ORM\Column(name="response_code", type="String", length=20, nullable=false)
     */
    private $responseCode;
    
    /**
     * @var string
     * @ORM\Column(name="status", type="int", length=5, nullable=false)
     */
    private $status;
    
    

    /**
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param string $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \PaymentBundle\Entity\id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \PaymentBundle\Entity\decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @return string
     */
    public function getCustomerPhone()
    {
        return $this->customerPhone;
    }

    /**
     * @return string
     */
    public function getLast4Digits()
    {
        return $this->last4Digits;
    }

    /**
     * @return string
     */
    public function getFirst4Digits()
    {
        return $this->first4Digits;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @return \PaymentBundle\Entity\datetime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @return string
     */
    public function getSecureSign()
    {
        return $this->secureSign;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param \PaymentBundle\Entity\id $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param \PaymentBundle\Entity\decimal $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param string $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @param string $customerEmail
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * @param string $customerPhone
     */
    public function setCustomerPhone($customerPhone)
    {
        $this->customerPhone = $customerPhone;
    }

    /**
     * @param string $last4Digits
     */
    public function setLast4Digits($last4Digits)
    {
        $this->last4Digits = $last4Digits;
    }

    /**
     * @param string $first4Digits
     */
    public function setFirst4Digits($first4Digits)
    {
        $this->first4Digits = $first4Digits;
    }

    /**
     * @param string $cardType
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
    }

    /**
     * @param \PaymentBundle\Entity\datetime $paymentDate
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;
    }

    /**
     * @param string $secureSign
     */
    public function setSecureSign($secureSign)
    {
        $this->secureSign = $secureSign;
    }

    /**
     * @param string $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    
    
   

    
}