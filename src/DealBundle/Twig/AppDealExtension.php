<?php

namespace DealBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class AppDealExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('generateDefaultDealImage',
                function ($params = array()) {
                    return $this->container->get('app.utils')->generateDefaultDealImage($params);
                }),
        );
    }
    
    public function getName()
    {
        return 'app_deal_extension';
    }
}