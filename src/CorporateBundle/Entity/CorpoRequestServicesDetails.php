<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoRequestServicesDetails
 *
 * @ORM\Table(name="corpo_request_services_details")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoRequestServicesRepository")
 */
class CorpoRequestServicesDetails
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
     * @ORM\Column(name="request_id", type="integer", nullable=true)
     */
    private $requestId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="reservation_id", type="integer", nullable=false)
     */
    private $reservationId;

    /**
     * @var integer
     *
     * @ORM\Column(name="module_id", type="integer", nullable=false)
     */
    private $moduleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $accountId;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=3, nullable=false)
     */
    private $currencyCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

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
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

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
     * Get requestId
     *
     * @return integer
     */
    function getRequestId()
    {
        return $this->requestId;
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
     * Get moduleId
     *
     * @return integer
     */
    
    function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Get reservationId
     *
     * @return integer
     */
    
    function getreservationId()
    {
        return $this->reservationId;
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
     * Get currencyCode
     *
     * @return string
     */
    function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Get status
     *
     * @return integer
     */
    function getStatus()
    {
        return $this->status;
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
     * Get createdAt
     *
     * @return DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
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
    function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this->accountId;
    }

    /**
     * Set reservationId
     *
     * @param integer $reservationId
     *
     * @return reservationId
     */
    function setReservationId($reservationId)
    {
        $this->reservationId = $reservationId;

        return $this->reservationId;
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
     * Set requestId
     *
     * @param integer $requestId
     *
     * @return requestId
     */
    function setRequestId($requestId)
    {
        $this->requestId = $requestId;

        return $this->requestId;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return status
     */
    function setStatus($status)
    {
        $this->status = $status;

        return $this->status;
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
}