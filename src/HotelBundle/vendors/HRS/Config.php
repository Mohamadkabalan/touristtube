<?php

namespace HotelBundle\vendors\HRS;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{

    public function __construct(ContainerInterface $container)
    {
        $this->{'endpoint_url'}     = $container->getParameter('modules')['hotels']['vendors']['hrs']['endpoint_url'];
        $this->{'client_type'}      = $container->getParameter('modules')['hotels']['vendors']['hrs']['client_type'];
        $this->{'client_key'}       = $container->getParameter('modules')['hotels']['vendors']['hrs']['client_key'];
        $this->{'client_password'}  = $container->getParameter('modules')['hotels']['vendors']['hrs']['client_password'];
        $this->{'default_language'} = $container->getParameter('modules')['hotels']['vendors']['hrs']['default_language'];
        $this->{'default_currency'} = $container->getParameter('modules')['hotels']['vendors']['hrs']['default_currency'];
        $this->{'request_timeout'}  = $container->getParameter('modules')['hotels']['vendors']['hrs']['request_timeout'];
    }
}
