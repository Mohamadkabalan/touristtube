<?php

namespace RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultController extends \TTBundle\Controller\DefaultController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
    }

}
