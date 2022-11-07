<?php

namespace CorporateBundle\Model;

class AccountTransaction
{
    private $transactionId;
    private $reservationId;
    private $moduleId;
    private $amountToRefund   = 0;
    private $cancellationFees = 0;
    private $currency;

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function getReservationId()
    {
        return $this->reservationId;
    }

    public function setReservationId($reservationId)
    {
        $this->reservationId = $reservationId;
    }

    public function getModuleId()
    {
        return $this->moduleId;
    }

    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    public function getAmountToRefund()
    {
        return $this->amountToRefund;
    }

    public function setAmountToRefund($amountToRefund)
    {
        $this->amountToRefund = $amountToRefund;
    }

    public function getcancellationFees()
    {
        return $this->cancellationFees;
    }

    public function setcancellationFees($cancellationFees)
    {
        $this->cancellationFees = $cancellationFees;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
}
