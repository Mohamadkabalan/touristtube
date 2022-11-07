<?php

namespace PaymentBundle\vendors;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{

    public function __construct(ContainerInterface $container)
    {
        $this->{'bypassCommand'}    = $container->getParameter('commands')['bypassCommand'];
        $this->{'refundCommand'}    = $container->getParameter('commands')['refundCommand'];
        $this->{'keyValidationUrl'} = $container->getParameter('modules')['payment']['vendors']['paytabs']['keyValidationUrl'];
        $this->{'createPayPageUrl'} = $container->getParameter('modules')['payment']['vendors']['paytabs']['createPayPageUrl'];
        $this->{'verifyPaymentUrl'} = $container->getParameter('modules')['payment']['vendors']['paytabs']['verifyPaymentUrl'];
        $this->{'refundTransactionUrl'} = $container->getParameter('modules')['payment']['vendors']['paytabs']['refundTransactionUrl'];
        $this->{'merchant_email'}       = $container->getParameter('modules')['payment']['vendors']['paytabs']['merchant_email'];
        $this->{'merchant_id'}          = $container->getParameter('modules')['payment']['vendors']['paytabs']['merchant_id'];
        $this->{'secret_key'}           = $container->getParameter('modules')['payment']['vendors']['paytabs']['secret_key'];
        $this->{'callback_url'}         = $container->getParameter('modules')['payment']['vendors']['paytabs']['callback_url'];
        $this->{'corpo_callback_url'}   = $container->getParameter('modules')['payment']['vendors']['paytabs']['corpo_callback_url'];
    }
    
}