<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CorpoProfilePermissionsServices
{
    protected $utils;
    protected $em;
    protected $container;

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container)
    {
        $this->utils              = $utils;
        $this->em                 = $em;
        $this->container          = $container;
    }

    public function prepareProfilesPermissionDtQuery()
    {
        return $this->em->getRepository('CorporateBundle:CorpoProfilePermissions')->prepareProfilesPermissionDtQuery();
    }
}