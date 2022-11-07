<?php

namespace HotelBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AppHotelExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getDefaultHotelImage', function ($params = array()) {
                    return $this->container->get('app.utils')->getDefaultHotelImage($params);
                }),
        );
    }

    public function getName()
    {
        return 'app_hotel_extension';
    }
}
