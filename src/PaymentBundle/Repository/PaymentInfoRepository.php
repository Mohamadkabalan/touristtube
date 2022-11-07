<?php

namespace PaymentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PaymentBundle\vendors\paytabs\v3\Model\PaytabsPaymentResponse;
use PaymentBundle\Entity\PaymentInfo;

class PaymentInfoRepository extends BaseRepository{
    
    
    public function findByPaymentId( $paymentId ){
        return $this->findOneBy([
            'payment_id' => $paymentId
        ]);
    }
    
    
    public function createPaymentInfo( PaytabsPaymentResponse $paymentResponse){
        
        $paymentInfo = new PaymentInfo();
        $paymentInfo->setAmount($paymentResponse->getTransaction_amount());
        $paymentInfo->setCurrency($paymentResponse->getTransaction_currency());
        $paymentInfo->setCustomerName($paymentResponse->getCustomer_name());
        $paymentInfo->setCustomerEmail($paymentResponse->getCustomer_email());
        $paymentInfo->setCustomerPhone($paymentResponse->getCustomer_phone());
        $paymentInfo->setLast4Digits($paymentResponse->getLast_4_digits());
        $paymentInfo->setFirst4Digits($paymentResponse->getFirst_4_digits());
        $paymentInfo->setCardType($paymentResponse->getCard_brand());
        $paymentInfo->setPaymentDate($paymentResponse->getTrans_date());
        $paymentInfo->setSecureSign($paymentResponse->getSecure_sign());
        $paymentInfo->setPaymentId($paymentResponse->getOrder_id());
        $paymentInfo->setResponseCode($paymentResponse->getResponse_code());
        $paymentInfo->setStatus($paymentResponse->getStatus());
        
        return $this->save( $paymentInfo );
    }
    
    
    
}