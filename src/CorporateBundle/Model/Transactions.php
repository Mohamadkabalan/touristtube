<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use TTBundle\Model\User;
use TTBundle\Model\Currency;

class Transactions {
    
    private $id;

    private $user;

    private $requestDetail;

    private $account;

    private $moduleId;

    private $reservation;

    private $currency;

    private $amount;

    private $amountFBC;

    private $amountSBC;

    private $amountAccountCurrency;

    private $description;

    private $paymentDate;

    
    private $createdBy;

    public function __construct() {
        $this->user = new User();
        $this->requestDetail = new Payment();
        $this->account = new Account();
        $this->reservation = new Payment();
        $this->currency = new Currency();
        $this->createdBy = new User();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getRequestDetail()
    {
        return $this->requestDetail;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getModuleId()
    {
        return $this->moduleId;
    }

    public function getReservation()
    {
        return $this->reservation;
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

    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = new \DateTime($paymentDate);
    }

    public function arrayToObject($params)
    {
        $transactions = new Transactions();
        if (!empty($params)) {
            /**'Code' suffix appended by TTAutocomplete */
            $transactions->getUser()->setId(isset($params['userId']) ? $params['userId'] : '');
            $transactions->getRequestDetail()->setId(isset($params['requestDetailId']) ? $params['requestDetailId'] : '');
            $transactions->getAccount()->setId(isset($params['accountCode']) ? $params['accountCode'] : '');
            $transactions->getReservation()->setId(isset($params['reservationId']) ? $params['reservationId'] : '');
            $transactions->getCurrency()->setCode(isset($params['currencyCode']) ? $params['currencyCode'] : '');
            $transactions->getCreatedBy()->setId(isset($params['userId']) ? $params['userId'] : '');
        }
        return Utils::array_to_obj($params,$transactions);
    }
}