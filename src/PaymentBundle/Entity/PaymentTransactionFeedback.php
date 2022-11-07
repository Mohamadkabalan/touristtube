<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 * 
 * @ORM\Entity
 * @ORM\Table(name="payment_transaction_feedback")
 */
class PaymentTransactionFeedback {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * The payment UUID
     * @ORM\Column(name="payment_uuid", type="string", length=36, nullable=false)
     */
    private $paymentUUID;

    /**
     * The merchant Reference
     * @var string
     *
     * @ORM\Column(name="merchant_reference", type="string", length=20)
     */
    private $merchantReference;

    /**
     * The fort ID
     * @var string
     *
     * @ORM\Column(name="fort_id", type="string", length=20)
     */
    private $fortId;

    /**
     * The command
     * @var string
     *
     * @ORM\Column(name="command", type="string", length=45)
     */
    private $command;

    /**
     * The token name
     * @var string
     *
     * @ORM\Column(name="token_name", type="string", length=100)
     */
    private $tokenName;

    /**
     * The customer IP
     * @var string
     *
     * @ORM\Column(name="customer_ip", type="string", length=45)
     */
    private $customerIp;

    /**
     * The language
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=2)
     */
    private $language;

    /**
     * status
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=2)
     */
    private $status;

    /**
     * The response code
     * @var string
     *
     * @ORM\Column(name="response_code", type="string", length=5)
     */
    private $responseCode;

    /**
     * The response_message
     * @var string
     *
     * @ORM\Column(name="response_message", type="string", length=255)
     */
    private $responseMessage;

    /**
     * The remember me
     * @var boolean
     *
     * @ORM\Column(name="remember_me", type="boolean")
     */
    private $rememberMe;

    /**
     * The eci
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=16)
     */
    private $eci;

    /**
     * The customer email
     * @var string
     *
     * @ORM\Column(name="customer_email", type="string", length=255)
     */
    private $customerEmail;

    /**
     * The currency
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3)
     */
    private $currency;

    /**
     * The amount
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=10)
     */
    private $amount;

    /**
     * The payment option
     * @var string
     *
     * @ORM\Column(name="payment_option", type="string", length=10)
     */
    private $paymentOption;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="Payment", inversedBy="paymentTransactionFeedbackList")
     * @ORM\JoinColumn(name="payment_uuid", referencedColumnName="uuid")
     */
    private $payment;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get merchant reference
     *
     * @return string
     */
    public function getMerchantReference() {
        return $this->merchantReference;
    }

    /**
     * Set merchant reference
     *
     * @param string $merchantReference
     */
    public function setMerchantReference($merchantReference) {
        $this->merchantReference = $merchantReference;
        return $this;
    }

    /**
     * Get fort ID
     *
     * @return string
     */
    public function getFortId() {
        return $this->fortId;
    }

    /**
     * Set fort ID
     *
     * @param string $fortId
     */
    public function setFortId($fortId) {
        $this->fortId = $fortId;
    }

    /**
     * Get the command
     *
     * @return string
     */
    function getCommand() {
        return $this->command;
    }

    /**
     * Set the command
     *
     * @param string $command
     */
    function setCommand($command) {
        $this->command = $command;
        return $this;
    }

    /**
     * Get the token name
     *
     * @return string
     */
    function getTokenName() {
        return $this->tokenName;
    }

    /**
     * Set token name
     *
     * @param string $tokenName
     */
    function setTokenName($tokenName) {
        $this->tokenName = $tokenName;
    }

    /**
     * Get the customer ip
     *
     * @return string
     */
    function getCustomerIp() {
        return $this->customerIp;
    }

    /**
     * Set customer ip
     *
     * @param string $customerIp
     */
    function setCustomerIp($customerIp) {
        $this->customerIp = $customerIp;
    }

    /**
     * Get the language
     *
     * @return string
     */
    function getLanguage() {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param string $language
     */
    function setLanguage($language = 'en') {
        $this->language = $language;
        return $this;
    }

    /**
     * Get the status
     *
     * @return string
     */
    function getStatus() {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Get the response code
     *
     * @return string
     */
    function getResponseCode() {
        return $this->responseCode;
    }

    /**
     * Set response code
     *
     * @param string $responseCode
     */
    function setResponseCode($responseCode) {
        $this->responseCode = $responseCode;
    }

    /**
     * Get the response message
     *
     * @return string
     */
    function getResponseMessage() {
        return $this->responseMessage;
    }

    /**
     * Set response message
     *
     * @param string $responseMessage
     */
    function setResponseMessage($responseMessage) {
        $this->responseMessage = $responseMessage;
    }

    /**
     * Is remember me
     *
     * @return boolean
     */
    function isRememberMe() {
        return $this->rememberMe;
    }

    /**
     * Set remember me
     *
     * @param boolean $rememberMe
     */
    function setRememberMe($rememberMe) {
        $this->rememberMe = $rememberMe;
    }

    /**
     * Get the eci
     *
     * @return string
     */
    function getEci() {
        return $this->eci;
    }

    /**
     * Set eci
     *
     * @param string $eci
     */
    function setEci($eci) {
        $this->eci = $eci;
    }

    /**
     * Get the customer email
     *
     * @return string
     */
    function getCustomerEmail() {
        return $this->customerEmail;
    }

    /**
     * Set customer email
     *
     * @param string $customerEmail
     */
    function setCustomerEmail($customerEmail) {
        $this->customerEmail = $customerEmail;
    }

    /**
     * Get the currency
     *
     * @return string
     */
    function getCurrency() {
        return $this->currency;
    }

    /**
     * Set currency
     *
     * @param string $currency
     */
    function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * Get the amount
     *
     * @return string
     */
    function getAmount() {
        return $this->amount;
    }

    /**
     * Set amount
     *
     * @param string $amount
     */
    function setAmount($amount) {
        $this->amount = $amount;
    }
    
        /**
     * Get the payment option
     *
     * @return string
     */
    function getPaymentOption() {
        return $this->paymentOption;
    }

    /**
     * Set payment option
     *
     * @param string $paymentOption
     */
    function setPaymentOption($paymentOption) {
        $this->paymentOption = $paymentOption;
    }

    /**
     * Get creation date
     *
     * @return datetime
     */
    public function getCreationDate() {
        return $this->creationDate;
    }

    /**
     * Set creation date
     *
     * @param datetime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

     /**
     * Get payment UUID
     *
     * @return string
     */
    public function getPaymentUUID()
    {
        return $this->paymentUUID;
    }

    /**
     * Set payment UUID
     *
     * @param string $paymentUUID
     */
    public function setPaymentUUID($paymentUUID)
    {
        $this->paymentUUID = $paymentUUID;
    }

    /**
     * Get Payment
     *
     * @return Payment
     */
    public function getPayment() {
        return $this->payment;
    }

    /**
     * Set Payment
     *
     * @param Payment $payment
     */
    public function setPayment(Payment $payment) {
        $this->payment = $payment;
    }
}
