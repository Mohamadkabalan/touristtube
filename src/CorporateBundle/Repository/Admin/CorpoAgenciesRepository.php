<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAgencies;
use TTBundle\Utils\Utils;

class CorpoAgenciesRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all Agencies of corporate
     * @param
     * @return doctrine object result of Agencies or false in case of no data
     * */
    public function getAgenciesList()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p,ca.name as countryName')
            ->leftJoin('CorporateBundle:CmsCountries', 'ca', 'WITH', "ca.id = p.countryId");

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    public function prepareAgenciesDtQuery()
    {
        $query = 'SELECT ca.id ca__id, ca.name ca__name, cc.name cc__name FROM corpo_agencies ca LEFT JOIN cms_countries cc ON (cc.id = ca.country_id)';
        $result_arr["all_query"] = $query;
        return Utils::prepareDatatableObj($result_arr);
    }

    /**
     * This method will retrieve all Agencies of corporate
     * @param
     * @return doctrine object result of Agencies or false in case of no data
     * */
    public function getCorpoAdminLikeAgencies($term, $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.name,p.id');
        if (isset($term)) {
            $query->where('p.name LIKE :term')
                ->setParameter(':term', '%'.$term.'%');
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
     * This method will retrieve a Agencies of corporate
     * @param
     * @return doctrine object result of Agencies or false in case of no data
     * */
    public function getAgencyById($id)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p,ca.name as countryName')
            ->leftJoin('CorporateBundle:CmsCountries', 'ca', 'WITH', "ca.id = p.countryId")
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
     * This method will add a Agencies for corporate Agencies
     * @param $agenciesObj
     * @return doctrine result of Agencies id or false in case of no data
     * */
    public function addAgencies($agenciesObj)
    {
        $this->em = $this->getEntityManager();
        $agency = new CorpoAgencies();
        $agency->setName($agenciesObj->getName());
        $agency->setCountryId($agenciesObj->getCountry()->getId());
        $this->em->persist($agency);
        $this->em->flush();
        if ($agency) {
            return $agency->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update a Agencies
     * @param $agenciesObj
     * @return doctrine object result of Agencies or false in case of no data
     * */
    public function updateAgencies($agenciesObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoAgencies', 'p')
            ->set("p.name", ":name")
            ->set("p.countryId", ":countryId")
            ->where("p.id=:Id")
            ->setParameter(':name', $agenciesObj->getName())
            ->setParameter(':countryId', $agenciesObj->getCountry()->getId())
            ->setParameter(':Id', $agenciesObj->getId());

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will delete a Agencies
     * @param id of Agencies
     * @return doctrine object result of Agencies or false in case of no data
     * */
    public function deleteAgencies($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoAgencies', 'p')
            ->where("p.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will retrieve all Agencies of corporate
     * @param
     * @return doctrine object result of Agencies or false in case of no data
     * */
    public function getAgencyCombo($tt_search_critiria_obj)
    {
        $em = $this->getEntityManager();

        $searchTerm = $tt_search_critiria_obj->getTerm();
        $page       = $tt_search_critiria_obj->getPage();
        $limit      = $tt_search_critiria_obj->getLimit();
        $sortOrder  = $tt_search_critiria_obj->getSortOrder();
        $start      = $tt_search_critiria_obj->getStart();

        $query ="SELECT count(p)  FROM CorporateBundle:CorpoAgencies p 
                WHERE p.name like :searchterm ";
        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");

        $query_res = $query_exec->getResult();

        $count = $query_res[0][1];
        
        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";

        $SQL = "SELECT p.name,p.id  FROM CorporateBundle:CorpoAgencies p 
                WHERE p.name like :searchterm " . $sortOrder;
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);
        $combogrid_cats = $query2_exec->getArrayResult();
        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;

        return $result_arr;
    }
}