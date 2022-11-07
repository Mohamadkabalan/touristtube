<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoProfiles;

class CorpoProfilesRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all Profiles of corporate
     * @param 
     * @return doctrine object result of Profiles or false in case of no data
     * */
    public function getProfilesList($accountId)
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
     * This method will retrieve all Profile of corporate
     * @param id of Profile
     * @return doctrine object result of Profile or false in case of no data
     * */
    public function getProfileById($id)
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
     * This method will add a Profile for corporate Profile
     * @param $profilesObj
     * @return doctrine result of Profile id or false in case of no data
     * */
    public function addProfiles($profilesObj)
    {
        $this->em = $this->getEntityManager();
        $profile  = new CorpoProfiles();
        $profile->setName($profilesObj->getName());
        $profile->setAccountId($profilesObj->getAccount()->getId());
        $this->em->persist($profile);
        $this->em->flush();
        if ($profile) {
            return $profile->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update a Profile
     * @param $profilesObj
     * @return doctrine object result of Profile or false in case of no data
     * */
    public function updateProfiles($profilesObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoProfiles', 'p')
            ->set("p.name", ":name")
            ->set("p.accountId", ":accountId")
            ->where("p.id=:Id")
            ->setParameter(':name', $profilesObj->getName())
            ->setParameter(':accountId', $profilesObj->getAccount()->getId())
            ->setParameter(':Id', $profilesObj->getId());

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will delete a Profile
     * @param id of Profile
     * @return doctrine object result of Profile or false in case of no data
     * */
    public function deleteProfiles($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoProfiles', 'p')
            ->where("p.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
}