<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoDefineServices;

class CorpoDefineServicesRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all Define Services of corporate
     * @param
     * @return doctrine object result of accounts or false in case of no data
     * */
    public function getDefineServicesList()
    {
        $query = $this->createQueryBuilder('ds')
            ->select('ds')
            ->where("ds.active = 1");

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will add a Define Service for corporate Define Service
     * @param Define Service info list
     * @return doctrine result of Define Service id or false in case of no data
     * */
    public function addDefineServices($parameters, $active)
    {
        $this->em       = $this->getEntityManager();
        $defineServices = new CorpoDefineServices();
        $defineServices->setName($parameters['name']);
        $defineServices->setActive($active);
        $this->em->persist($defineServices);
        $this->em->flush();
        if ($defineServices) {
            return $defineServices->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve a Define Service of corporate
     * @param id of Define Service
     * @return doctrine object result of Define Service or false in case of no data
     * */
    public function getDefineServicesById($id)
    {
        $query = $this->createQueryBuilder('ds')
            ->select('ds')
            ->where("ds.id = :ID")
            ->setParameter(':ID', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will delete a Define Service
     * @param id of Define Service
     * @return doctrine object result of Define Service or false in case of no data
     * */
    public function deleteDefineServices($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ds')
            ->delete('CorporateBundle:CorpoDefineServices', 'ds')
            ->where("ds.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will update a Define Service
     * @param id of Define Service
     * @return doctrine object result of Define Service or false in case of no data
     * */
    public function updateDefineServices($parameters, $active)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoDefineServices', 'ds')
            ->set("ds.name", ":name")
            ->set("ds.active", ":active")
            ->where("ds.id=:Id")
            ->setParameter(':name', $parameters['name'])
            ->setParameter(':active', $active)
            ->setParameter(':Id', $parameters['id']);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $query->getResult();
        } else {
            return false;
        }
    }
}