<?php

namespace HotelBundle\vendors\TTApi;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{

    public function __construct(ContainerInterface $container)
    {
        $this->{'url'}             = $container->getParameter('modules')['hotels']['vendors']['tt_api']['endpoint_url'];
        $this->{'port'}            = $container->getParameter('modules')['hotels']['vendors']['tt_api']['port'];
        $this->{'env'}             = $container->getParameter('modules')['hotels']['vendors']['tt_api']['env'];
        $this->{'ssl_verify_peer'} = $container->getParameter('modules')['hotels']['vendors']['tt_api']['ssl_verify_peer'];
        $this->{'max_attempts'}    = $container->getParameter('modules')['hotels']['vendors']['tt_api']['max_attempts'];
        $this->{'batch_request'}   = $container->getParameter('modules')['hotels']['vendors']['tt_api']['batch_request'];
        $this->{'grant_type'}      = $container->getParameter('modules')['hotels']['vendors']['tt_api']['auth_params']['grant_type'];
        $this->{'username'}        = $container->getParameter('modules')['hotels']['vendors']['tt_api']['auth_params']['username'];
        $this->{'password'}        = $container->getParameter('modules')['hotels']['vendors']['tt_api']['auth_params']['password'];
    }
}
