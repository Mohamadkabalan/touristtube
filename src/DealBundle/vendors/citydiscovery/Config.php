<?php
namespace DealBundle\vendors\citydiscovery;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{
    public function __construct(ContainerInterface $container)
    {
        $this->{'http_test_static_url'} = $container->getParameter('deals')['vendors']['citydiscovery']['http_test_static_url'];
        $this->{'http_test_transfer_url'}         = $container->getParameter('deals')['vendors']['citydiscovery']['http_test_transfer_url'];
        $this->{'http_test_username'}              = $container->getParameter('deals')['vendors']['citydiscovery']['http_test_username'];
        $this->{'http_test_password'}          = $container->getParameter('deals')['vendors']['citydiscovery']['http_test_password'];

        $this->{'http_prod_static_url'} = $container->getParameter('deals')['vendors']['citydiscovery']['http_prod_static_url'];
        $this->{'http_prod_transfer_url'}         = $container->getParameter('deals')['vendors']['citydiscovery']['http_prod_transfer_url'];
        $this->{'http_prod_username'}              = $container->getParameter('deals')['vendors']['citydiscovery']['http_prod_username'];
        $this->{'http_prod_password'}          = $container->getParameter('deals')['vendors']['citydiscovery']['http_prod_password'];
        $this->{'test_mode'}          = $container->getParameter('deals')['vendors']['citydiscovery']['test_mode'];
    }
}