<?php
namespace PaymentBundle\Services;

//USED FOR PAYMENT GATEWAY PROVIDERS TO MAKE SURE ALL PROVIDERS WILL HAVE THE SAME METHODS SO WE CAN CALL THEM DYNAMICALLY
interface PaymentServiceProvider
{
    public function tokenise($param, $uuid, $paymentInfo);
  //  public function getPaymentInformation($uuid);
//    public function onHoldPayment();
    public function processPayment($trxId, $paymentInfo, $resultJson);
    public function voidOnHoldPayment($trxId);
    public function captureOnHoldPayment($trxId);
    public function refund($trxId);
    public function processResponse($trxId, $paymentInfo, $jsonResponse);
}
