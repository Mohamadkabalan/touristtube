<?php

namespace HotelBundle\vendors\TrustYou;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{

    public function __construct(ContainerInterface $container)
    {
        $this->{'api_key'} = $container->getParameter('modules')['hotels']['vendors']['trustyou']['api_key'];
        $this->{'url'}     = $container->getParameter('modules')['hotels']['vendors']['trustyou']['endpoint_url'];
        $this->{'version'} = $container->getParameter('modules')['hotels']['vendors']['trustyou']['version'];
        $this->{'scale'}   = $container->getParameter('modules')['hotels']['vendors']['trustyou']['scale'];
    }
}
