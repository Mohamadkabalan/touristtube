<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use TTBundle\Model\User;
use TTBundle\Model\Currency;

class Payment {
    
    private $id;

    private $account;

    private $user;

    private $moduleId;

    private $currency;

    private $amount;

    private $amountFBC;

    private $amountSBC;

    private $amountAccountCurrency;

    private $description;

    private $paymentDate;

    private $createdBy;

    public function __construct()
    {
        $this->account = new Account();
        $this->user = new User();
        $this->currency = new Currency();
        $this->createdBy = new User();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getModuleId()
    {
        return $this->moduleId;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getAmountFBC() {
        return $this->amountFBC;
    }

    public function getAmountSBC() {
        return $this->amountSBC;
    }

    public function getAmountAccountCurrency() {
        return $this->amountAccountCurrency;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPaymentDate() {
        return $this->paymentDate;
    }

    public function getCreatedBy() {
        return $this->createdBy;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setAmountFBC($amountFBC)
    {
        $this->amountFBC = $amountFBC;
    }

    public function setAmountSBC($amountSBC)
    {
        $this->amountSBC = $amountSBC;
    }

    public function setAmountAccountCurrency($amountAccountCurrency)
    {
        $this->amountAccountCurrency = $amountAccountCurrency;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setPaymentDate($date)
    {
        $this->paymentDate = new \DateTime($date);
    }

    public function arrayToObject($params)
    {
        $payment = new Payment();
        if (!empty($params)) {
            /**'Code' suffix appended by TTAutocomplete */
            $payment->getAccount()->setId(isset($params['accountCode']) ? $params['accountCode'] : '');
            $payment->getUser()->setId(isset($params['userId']) ? $params['userId'] : '');
            $payment->getCurrency()->setCode(isset($params['currencyCode']) ? $params['currencyCode'] : '');
            $payment->getCreatedBy()->setId(isset($params['createdBy']) ? $params['createdBy'] : '');
        }
        return Utils::array_to_obj($params,$payment);
    }
}