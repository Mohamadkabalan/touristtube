<?php

namespace TTBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class RestaurantsServices
{
    protected $em;
    protected $utils;
    protected $container;

    public function __construct(EntityManager $em, Utils $utils, ContainerInterface $container)
    {
        $this->em            = $em;
        $this->utils         = $utils;
        $this->container     = $container;
        $this->translator    = $this->container->get('translator');
    }

}
