<?php

namespace HotelBundle\vendors\Amadeus;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{

    public function __construct(ContainerInterface $container)
    {
        $this->{'endpoint_url'}         = $container->getParameter('modules')['hotels']['vendors']['amadeus']['endpoint_url'];
        $this->{'wsdl_environment'}     = $container->getParameter('modules')['hotels']['vendors']['amadeus']['wsdl_environment'];
        $this->{'uat_endpoint_url'}     = $container->getParameter('modules')['hotels']['vendors']['amadeus']['uat_endpoint_url'];
        $this->{'uat_wsdl_environment'} = $container->getParameter('modules')['hotels']['vendors']['amadeus']['uat_wsdl_environment'];
        $this->{'default_api_language'} = $container->getParameter('modules')['hotels']['vendors']['amadeus']['default_api_language'];
        $this->{'default_api_currency'} = $container->getParameter('modules')['hotels']['vendors']['amadeus']['default_api_currency'];
        $this->{'distribution'}         = $container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['distribution'];
        $this->{'leisure'}              = $container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['leisure'];
        $this->{'multisource'}          = $container->getParameter('modules')['hotels']['vendors']['amadeus']['infosource']['multisource'];
    }
}
