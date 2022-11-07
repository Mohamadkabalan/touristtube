<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAccountPayment
 *
 * @ORM\Table(name="corpo_account_payment")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAccountPaymentRepository")
 */
class CorpoAccountPayment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $accountId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="request_detail_id", type="integer", nullable=false)
     */
    private $requestDetailId;

    /**
     * @var integer
     *
     * @ORM\Column(name="module_id", type="integer", nullable=false)
     */
    private $moduleId;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=3, nullable=false)
     */
    private $currencyCode;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount", type="decimal", nullable=false)
     */
    private $amount;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount_fbc", type="decimal", nullable=false)
     */
    private $amountFBC;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount_sbc", type="decimal", nullable=false)
     */
    private $amountSBC;

    /**
     * @var decimal
     *
     * @ORM\Column(name="account_currency_amount", type="decimal", nullable=false)
     */
    private $amountAccountCurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="payment_date", type="datetime", nullable=false)
     */
    private $paymentDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Get id
     *
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get accountId
     *
     * @return integer
     */
    function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get requestDetailId
     *
     * @return integer
     */
    function getRequestDetailId()
    {
        return $this->requestDetailId;
    }

    /**
     * Get moduleId
     *
     * @return integer
     */
    function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Get amount
     *
     * @return decimal
     */
    function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get amountFBC
     *
     * @return decimal
     */
    function getAmountFBC()
    {
        return $this->amountFBC;
    }

    /**
     * Get amountSBC
     *
     * @return decimal
     */
    function getAmountSBC()
    {
        return $this->amountSBC;
    }

    /**
     * Get amountAccountCurrency
     *
     * @return decimal
     */
    function getAmountAccountCurrency()
    {
        return $this->amountAccountCurrency;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get paymentDate
     *
     * @return DateTime
     */
    function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return id
     */
    function setId($id)
    {
        $this->id = $id;

        return $this->id;
    }

    /**
     * Set accountId
     *
     * @param integer $accountId
     *
     * @return accountId
     */
    function setAccountId($cityId)
    {
        $this->accountId = $cityId;

        return $this->accountId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return userId
     */
    function setUserId($userId)
    {
        $this->userId = $userId;

        return $this->userId;
    }

    /**
     * Set requestDetailId
     *
     * @param integer $requestDetailId
     *
     * @return requestDetailId
     */
    function setRequestDetailId($requestDetailId)
    {
        $this->requestDetailId = $requestDetailId;

        return $this->requestDetailId;
    }

    /**
     * Set moduleId
     *
     * @param integer $moduleId
     *
     * @return moduleId
     */
    function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;

        return $this->moduleId;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     *
     * @return currencyCode
     */
    function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this->currencyCode;
    }

    /**
     * Set amount
     *
     * @param decimal $amount
     *
     * @return amount
     */
    function setAmount($amount)
    {
        $this->amount = $amount;

        return $this->amount;
    }

    /**
     * Set amountSBC
     *
     * @param decimal $amountSBC
     *
     * @return amountSBC
     */
    function setAmountSBC($amountSBC)
    {
        $this->amountSBC = $amountSBC;

        return $this->amountSBC;
    }

    /**
     * Set amountFBC
     *
     * @param decimal $amountFBC
     *
     * @return amountFBC
     */
    function setAmountFBC($amountFBC)
    {
        $this->amountFBC = $amountFBC;

        return $this->amountFBC;
    }

    /**
     * Set amountAccountCurrency
     *
     * @param decimal $amountAccountCurrency
     *
     * @return amountAccountCurrency
     */
    function setAmountAccountCurrency($amountAccountCurrency)
    {
        $this->amountAccountCurrency = $amountAccountCurrency;

        return $this->amountAccountCurrency;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this->description;
    }

    /**
     * Set paymentDate
     *
     * @param DateTime $paymentDate
     *
     * @return paymentDate
     */
    function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this->paymentDate;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return createdBy
     */
    function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this->createdBy;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return createdAt
     */
    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this->createdAt;
    }
}