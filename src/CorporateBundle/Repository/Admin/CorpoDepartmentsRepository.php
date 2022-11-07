<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoDepartments;

class CorpoDepartmentsRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all Departments of corporate
     * @param 
     * @return doctrine object result of Departments or false in case of no data
     * */
    public function getDepartmentsList($accountId)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p,ca.name as accountName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = p.accountId");
        if ($accountId) {
            $query->where("p.accountId = :accountId")
                ->setParameter(':accountId', $accountId);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve all Departments of corporate
     * @param
     * @return doctrine object result of Departments or false in case of no data
     * */
    public function getCorpoAdminLikeDepartments($term, $limit, $accountId)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.name,p.id');
        if ($term && $term != '') {
            $query->where('p.name LIKE :term')
                ->setParameter(':term', '%'.$term.'%');
        }
        if ($accountId && $accountId != '') {
            $query->andwhere("p.accountId = :accountId")
                ->setParameter(':accountId', $accountId);
        }
        $query->setMaxResults($limit);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve a Department of corporate
     * @param
     * @return doctrine object result of Department or false in case of no data
     * */
    public function getDepartmentById($id)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p,ca.name as accountName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = p.accountId")
            ->where("p.id = :ID")
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
     * This method will add a Department for corporate Department
     * @param Department info list
     * @return doctrine result of Department id or false in case of no data
     * */
    public function addDepartments($parameters)
    {
        $this->em = $this->getEntityManager();
        $profile  = new CorpoDepartments();
        $profile->setName($parameters['name']);
        $profile->setAccountId($parameters['accountId']);
        $this->em->persist($profile);
        $this->em->flush();
        if ($profile) {
            return $profile->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update a Department
     * @param id of Department
     * @return doctrine object result of Department or false in case of no data
     * */
    public function updateDepartments($parameters)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoDepartments', 'p')
            ->set("p.name", ":name")
            ->set("p.accountId", ":accountId")
            ->where("p.id=:Id")
            ->setParameter(':name', $parameters['name'])
            ->setParameter(':accountId', $parameters['accountId'])
            ->setParameter(':Id', $parameters['id']);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    /**
     * This method will delete a Department
     * @param id of Department
     * @return doctrine object result of Department or false in case of no data
     * */
    public function deleteDepartments($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoDepartments', 'p')
            ->where("p.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
}