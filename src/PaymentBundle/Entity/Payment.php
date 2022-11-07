<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FlightBundle\Entity\PassengerNameRecord;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Payment
 
 *
 * @ORM\Entity
 * @ORM\Table(name="payment")
 */
class Payment
{
    /**
     * The payment uuid
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=33, nullable=false)
     * @ORM\Id
     */
    private $uuid;
    
    /**
     * The merchant Reference
     * @var string
     *
     * @ORM\Column(name="merchant_reference", type="string", length=20, nullable=false)
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
     * The user ID
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11, options={"default": 0, "unsigned": true})
     */
    private $userId = 0;
    
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
     * The type of purchase (Flight, Package, Hotel...)
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type;
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="credit_card_number", type="string", length=22, nullable=true)
     */
    private $creditCardNumber;
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="customer_name", type="string", length=255, nullable=true)
     */
    private $customerName;
    
    /**
     * The device finger print
     * @var string
     *
     * @ORM\Column(name="device_fingerprint", type="text")
     */
    private $deviceFingerPrint;
    
    /**
     * The user agent
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255)
     */
    private $userAgent;
    
    /**
     * Is mobile sdk
     * @var boolean
     *
     * @ORM\Column(name="mobile_sdk", type="boolean")
     */
    private $mobileSdk;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=false)
     */
    private $updatedDate;
    
    /**
     * @ORM\OneToOne(targetEntity="FlightBundle\Entity\PassengerNameRecord", mappedBy="payment", cascade={"persist"})
     */
    private $passengerNameRecord;
    
    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3, nullable=false)
     */
    private $currency;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="original_amount", type="decimal", nullable=false)
     */
    private $originalAmount;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="amount", type="decimal", nullable=false)
     */
    private $amount;
    
    /**
     * @var string
     *
     * @ORM\Column(name="display_currency", type="string", length=3, nullable=false)
     */
    private $displayCurrency;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="display_original_amount", type="decimal", nullable=false)
     */
    private $displayOriginalAmount;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="display_amount", type="decimal", nullable=false)
     */
    private $displayAmount;
    
    /**
     * campaign_id
     * @var integer
     *
     * @ORM\Column(name="campaign_id", type="bigint", options={"default": 0, "unsigned": true})
     */
    private $campaignId = 0;
    
    /**
     * Coupon Code
     * @var string
     *
     * @ORM\Column(name="coupon_code", type="string", length=100, nullable=true)
     */
    private $couponCode;
    
    /**
     * Email
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;
    
    /**
     * paymentType
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", length=25, nullable=true)
     */
    private $paymentType;
    
    /**
     * moduleTransactionId
     * The Id sent by other module
     * @var string
     *
     * @ORM\Column(name="moduleTransactionId", type="string", length=255, nullable=true)
     */
    private $moduleTransactionId;
    
    /**
     * payment provider
     * The Id sent by other module
     * @var string
     *
     * @ORM\Column(name="paymentProvider", type="integer", length=11)
     */
    private $paymentProvider;
    
    /**
     * moduleId
     * the id of the module ( example 1 flight, 2 hotels, .. )
     * @var string
     *
     * @ORM\Column(name="module_id", type="integer", length=11)
     */
    private $moduleId;
    
    /**
     * $amountFBC
     * first basic currency Amount, AED is our first currency
     * @var decimal
     *
     * @ORM\Column(name="amount_fbc", type="decimal", nullable=false)
     */
    private $amountFBC;
    
    /**
     * $amountSBC
     * second basic currency Amount, EUR is our second currency
     * @var decimal
     *
     * @ORM\Column(name="amount_sbc", type="decimal", nullable=false)
     */
    private $amountSBC;
    
    /**
     * $accountCurrency
     * Account Currency based on the corpo account settings
     * @var decimal
     *
     * @ORM\Column(name="account_currency", type="string", nullable=false)
     */
    private $accountCurrency;
    
    /**
     * $accountCurrencyAmount
     * Account Amount based on the Currency from the corpo account settings
     * @var decimal
     *
     * @ORM\Column(name="account_currency_amount", type="decimal", nullable=false)
     */
    private $accountCurrencyAmount;
    
    /**
     * @ORM\OneToMany(targetEntity="PaymentTransactionFeedback", mappedBy="payment", cascade={"persist"})
     */
    private $paymentTransactionFeedbackList;
    private $amountAccountCurrency;
    
    /**
     * @ORM\Column(name="module_currency", type="string", length=3, nullable=true)
     */
    private $moduleCurrency;
    
    /**
     * @ORM\Column(name="module_amount", type="decimal", nullable=true)
     */
    private $moduleAmount;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paymentTransactionFeedbackList = new ArrayCollection();
    }
    
    /**
     * Get payment uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
    
    /**
     * Set payment uuid
     *
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }
    
    /**
     * Get merchant reference
     *
     * @return string
     */
    public function getMerchantReference()
    {
        return $this->merchantReference;
    }
    
    /**
     * Set merchant paymentType
     *
     * @param string $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
        return $this;
    }
    
    /**
     * Get paymentType
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }
    
    /**
     * Set merchant reference
     *
     * @param string $merchantReference
     */
    public function setMerchantReference($merchantReference)
    {
        $this->merchantReference = $merchantReference;
        return $this;
    }
    
    /**
     * Get fort ID
     *
     * @return string
     */
    public function getFortId()
    {
        return $this->fortId;
    }
    
    /**
     * Set fort ID
     *
     * @param string $fortId
     */
    public function setFortId($fortId)
    {
        $this->fortId = $fortId;
    }
    
    /**
     * Get user ID
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * Set user ID
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    /**
     * Get the command
     *
     * @return string
     */
    function getCommand()
    {
        return $this->command;
    }
    
    /**
     * Set the command
     *
     * @param string $command
     */
    function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }
    
    /**
     * Get the token name
     *
     * @return string
     */
    function getTokenName()
    {
        return $this->tokenName;
    }
    
    /**
     * Set token name
     *
     * @param string $tokenName
     */
    function setTokenName($tokenName)
    {
        $this->tokenName = $tokenName;
    }
    
    /**
     * Get the customer ip
     *
     * @return string
     */
    function getCustomerIp()
    {
        return $this->customerIp;
    }
    
    /**
     * Set customer ip
     *
     * @param string $customerIp
     */
    function setCustomerIp($customerIp)
    {
        $this->customerIp = $customerIp;
    }
    
    /**
     * Get the status
     *
     * @return string
     */
    function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set status
     *
     * @param string $status
     */
    function setStatus($status)
    {
        $this->status = $status;
    }
    
    /**
     * Get the language
     *
     * @return string
     */
    function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * Set language
     *
     * @param string $language
     */
    function setLanguage($language = 'en')
    {
        $this->language = $language;
        return $this;
    }
    
    /**
     * Get the response code
     *
     * @return string
     */
    function getResponseCode()
    {
        return $this->responseCode;
    }
    
    /**
     * Set response code
     *
     * @param string $responseCode
     */
    function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }
    
    /**
     * Get the response message
     *
     * @return string
     */
    function getResponseMessage()
    {
        return $this->responseMessage;
    }
    
    /**
     * Set response message
     *
     * @param string $responseMessage
     */
    function setResponseMessage($responseMessage)
    {
        $this->responseMessage = $responseMessage;
    }
    
    /**
     * Is remember me
     *
     * @return boolean
     */
    function isRememberMe()
    {
        return $this->rememberMe;
    }
    
    /**
     * Set remember me
     *
     * @param boolean $rememberMe
     */
    function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }
    
    /**
     * Get the type
     *
     * @return string
     */
    function getType()
    {
        return $this->type;
    }
    
    /**
     * Set type
     *
     * @param string $type
     */
    function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Get the creditCardNumber
     *
     * @return string
     */
    function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }
    
    /**
     * Set creditCardNumber
     *
     * @param string $creditCardNumber
     */
    function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;
    }
    
    /**
     * Get the customer name
     *
     * @return string
     */
    function getCustomerName()
    {
        return $this->customerName;
    }
    
    /**
     * Set customer Name
     *
     * @param string $customerName
     */
    function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }
    
    /**
     * Get the device finger print
     *
     * @return string
     */
    function getDeviceFingerPrint()
    {
        return $this->deviceFingerPrint;
    }
    
    /**
     * Set device finger print
     *
     * @param string $deviceFingerPrint
     */
    function setDeviceFingerPrint($deviceFingerPrint)
    {
        $this->deviceFingerPrint = $deviceFingerPrint;
    }
    
    /**
     * Is mobile sdk
     *
     * @return boolean
     */
    function isMobileSdk()
    {
        return $this->mobileSdk;
    }
    
    /**
     * Set mobile SDK
     *
     * @param boolean $mobileSdk
     */
    function setMobileSdk($mobileSdk)
    {
        $this->mobileSdk = $mobileSdk;
    }
    
    /**
     * Get the user agent
     *
     * @return string
     */
    function getUserAgent()
    {
        return $this->type;
    }
    
    /**
     * Set user agent
     *
     * @param string $userAgent
     */
    function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }
    
    /**
     * Get creation date
     *
     * @return datetime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    
    /**
     * Set creation date
     *
     * @param datetime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
    }
    
    /**
     * Get updated date
     *
     * @return datetime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
    
    /**
     * Set updated date
     *
     * @param datetime $updatedDate
     */
    public function setUpdatedDate(\DateTime $updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }
    
    /**
     * Get passenger name record
     *
     * @return PassengerNameRecord $passengerNameRecord
     */
    public function getPassengerNameRecord()
    {
        return $this->passengerNameRecord;
    }
    
    /**
     * Set flight info
     *
     * @param PassengerNameRecord $passengerNameRecord
     */
    public function setPassengerNameRecord(PassengerNameRecord $passengerNameRecord)
    {
        $passengerNameRecord->setPayment($this);
        $this->passengerNameRecord = $passengerNameRecord;
    }
    
    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    
    /**
     * Set currency
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    /**
     * Get original amount
     *
     * @return decimal
     */
    public function getOriginalAmount()
    {
        return $this->originalAmount;
    }
    
    /**
     * Set original amount
     *
     * @param decimal $amount
     */
    public function setOriginalAmount($amount)
    {
        $this->originalAmount = $amount;
    }
    
    /**
     * Get amount
     *
     * @return decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Set amount
     *
     * @param decimal $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
    
    /**
     * Get display currency
     *
     * @return string
     */
    public function getDisplayCurrency()
    {
        return $this->displayCurrency;
    }
    
    /**
     * Set display currency
     *
     * @param string $currency
     */
    public function setDisplayCurrency($currency)
    {
        $this->displayCurrency = $currency;
    }
    
    /**
     * Get display original amount
     *
     * @return decimal
     */
    public function getDisplayOriginalAmount()
    {
        return $this->displayOriginalAmount;
    }
    
    /**
     * Set display original amount
     *
     * @param decimal $amount
     */
    public function setDisplayOriginalAmount($amount)
    {
        $this->displayOriginalAmount = $amount;
    }
    
    /**
     * Get display amount
     *
     * @return decimal
     */
    public function getDisplayAmount()
    {
        return $this->displayAmount;
    }
    
    /**
     * Set display amount
     *
     * @param decimal $amount
     */
    public function setDisplayAmount($amount)
    {
        $this->displayAmount = $amount;
    }
    
    /**
     * Get Campaign ID
     *
     * @return integer
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }
    
    /**
     * Set Campaign ID
     *
     * @param integer $campaign_id
     */
    public function setCampaignId($campaign_id = 0)
    {
        $this->campaignId = $campaign_id;
    }
    
    /**
     * Get Coupon Code
     *
     * @return string
     */
    function getCouponCode()
    {
        return $this->couponCode;
    }
    
    /**
     * Set Coupon Code
     *
     * @param string $command
     */
    function setCouponCode($coupon_code)
    {
        $this->couponCode = $coupon_code;
    }
    
    /**
    
    * Get Email
    *
    * @return string
    */
    function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Set Email
     *
     * @param string $email
     */
    function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
    
    * Get moduleTransactionId
    *
    * @return string
    */
    function getModuleTransactionId()
    {
        return $this->moduleTransactionId;
    }
    
    /**
     * Set moduleTransactionId
     *
     * @param string $moduleTransactionId
     */
    public function setModuleTransactionId($moduleTransactionId)
    {
        $this->moduleTransactionId = $moduleTransactionId;
    }
    
    /**
     * Get paymentProvider
     *
     * @return string
     */
    public function getPaymentProvider()
    {
        return $this->paymentProvider;
    }
    
    /**
     * Set paymentProvider
     *
     * @param string $paymentProvider
     */
    public function setPaymentProvider($paymentProvider)
    {
        $this->paymentProvider = $paymentProvider;
    }
    
    /**
     * Get paymentProvider
     *
     * @return string
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }
    
    /**
     * Set module id
     *
     * @param string $moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }
    
    function getAmountFBC()
    {
        return $this->amountFBC;
    }
    
    /**
     *
     * get Amount SBC
     * @return decimal
     *
     */
    function getAmountSBC()
    {
        return $this->amountSBC;
    }
    
    /**
     * get Account Currency
     * @return string
     */
    function getAccountCurrency()
    {
        return $this->accountCurrency;
    }
    
    /**
     * get accountCurrencyAmount
     * @return decimal
     */
    function getAccountCurrencyAmount()
    {
        return $this->accountCurrencyAmount;
    }
    
    /**
     * set amount
     * @return decimal
     */
    function setAmountFBC($amountFBC)
    {
        $this->amountFBC = $amountFBC;
    }
    
    /**
     * set amount SBC
     * $return decimal
     */
    function setAmountSBC($amountSBC)
    {
        $this->amountSBC = $amountSBC;
    }
    
    /**
     * set account Currency
     * @param string $accountCurrency
     */
    function setAccountCurrency($accountCurrency)
    {
        $this->accountCurrency = $accountCurrency;
    }
    
    function setAccountCurrencyAmount($accountCurrencyAmount)
    {
        $this->accountCurrencyAmount = $accountCurrencyAmount;
    }
    
    /**
     * @return mixed
     */
    public function getModuleAmount()
    {
        return $this->moduleAmount;
    }
    
    /**
     * @param mixed $moduleAmount
     */
    public function setModuleAmount($moduleAmount)
    {
        $this->moduleAmount = $moduleAmount;
        return $this;
    }
    
    /**
     * Get Payment Transaction FeedBacks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPaymentTransactionFeedbackList()
    {
        return $this->paymentTransactionFeedbackList;
    }
    
    /**
     * Set Payment Transaction FeedBacks
     *
     * @param FlightDetail $paymentTransactionFeedbackList
     */
    public function setPaymentTransactionFeedbackList(PaymentTransactionFeedback $paymentTransactionFeedbackList)
    {
        $this->paymentTransactionFeedbackList[] = $paymentTransactionFeedbackList;
    }
    
    /**
     * Add paymentTransactionFeedback
     *
     * @param PaymentTransactionFeedback $paymentTransactionFeedback
     *
     * @return PaymentTransactionFeedback
     */
    public function addPaymentTransactionFeedback(PaymentTransactionFeedback $paymentTransactionFeedback)
    {
        $paymentTransactionFeedback->setPayment($this);
        $this->paymentTransactionFeedbackList->add($paymentTransactionFeedback);
    }
    
    /**
     * @return mixed
     */
    public function getModuleCurrency()
    {
        return $this->moduleCurrency;
    }
    
    /**
     * @param mixed $moduleCurrency
     */
    public function setModuleCurrency($moduleCurrency)
    {
        $this->moduleCurrency = $moduleCurrency;
        return $this;
    }
    
    /**
     * Remove paymentTransactionFeedback
     *
     * @param PaymentTransactionFeedback $paymentTransactionFeedback
     */
    public function removePaymentTransactionFeedback(PaymentTransactionFeedback $paymentTransactionFeedback)
    {
        $this->paymentTransactionFeedbackList->removeElement($paymentTransactionFeedback);
    }
    
    /**
     * Check if payment has been processed already
     * @return boolean
     */
    function isPaymentProcessed()
    {
        
        $processed = false;
        
        if ($this->getCommand() == 'PURCHASE' && $this->getResponseMessage() == 'Success') {
            $processed = true;
        }
        
        return $processed;
    }
}
