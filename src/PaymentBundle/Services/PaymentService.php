<?php

namespace PaymentBundle\Services;

use PaymentBundle\Model\Payment;

interface PaymentService
{

    public function initializePayment(Payment $payment);

    public function getPaymentInformation($uuid);

    // public function onHoldPayment();
    public function processPayment($trxId, $ccInfo);

    public function voidOnHoldPayment($trxId);

    public function captureOnHoldPayment($trxId);

    public function refund($trxId);
}