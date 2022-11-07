<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class CorpoDefineServicesServices
{
    protected $utils;
    protected $em;

    public function __construct(Utils $utils, EntityManager $em)
    {
        $this->utils     = $utils;
        $this->em        = $em;
    }

    /**
     * getting from the repository all Define Services
     *
     * @return list
     */
    public function getCorpoAdminDefineServicesList()
    {
        $defineServicesList = $this->em->getRepository('CorporateBundle:CorpoDefineServices')->getDefineServicesList();
        return $defineServicesList;
    }

    /**
     * adding a Define Service
     *
     * @return list
     */
    public function addDefineServices($parameters)
    {
        if (array_key_exists('active', $parameters)) {
            $active = 1;
        } else {
            $active = 0;
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoDefineServices')->addDefineServices($parameters, $active);
        return $addResult;
    }

    /**
     * deleting a Define Service
     *
     * @return list
     */
    public function deleteDefineServices($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoDefineServices')->deleteDefineServices($id);
        return $addResult;
    }

    /**
     * getting from the repository a Define Service
     *
     * @return list
     */
    public function getCorpoAdminDefineServices($id)
    {
        $defineService = $this->em->getRepository('CorporateBundle:CorpoDefineServices')->getDefineServicesById($id);

        if (is_null($defineService['ds_active']) || $defineService['ds_active'] == '') {
            $active = 0;
        } else {
            $active = 1;
        }
        $defineService['ds_active'] = $active;
        return $defineService;
    }

    /**
     * updating a Define Service
     *
     * @return list
     */
    public function updateDefineServices($parameters)
    {
        if (array_key_exists('active', $parameters)) {
            $active = 1;
        } else {
            $active = 0;
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoDefineServices')->updateDefineServices($parameters, $active);
        return $addResult;
    }
}