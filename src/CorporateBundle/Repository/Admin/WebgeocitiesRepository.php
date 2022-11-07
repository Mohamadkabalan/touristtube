<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\Webgeocities;

class WebgeocitiesRepository extends EntityRepository
{

    /**
     * This method will retrieve list of cities like term
     * @param term
     * @return doctrine object result of Cities or false in case of no data
     * */
    public function getListLikeCities($term, $includeId='', $limit)
    {
        $query = $this->createQueryBuilder('w')
            ->select("w.name,w.id,concat(w.name,',',s.stateName,',',cc.name) as fullname")
            ->leftJoin('CorporateBundle:CmsCountries', 'cc', 'WITH', "cc.code = w.countryCode")
            ->leftJoin('CorporateBundle:States', 's', 'WITH', "s.stateCode = w.stateCode and s.countryCode = cc.code");
        if ($term) {
            $query->where("concat(w.name,' ',cc.name) LIKE :term")
                ->setParameter('term', '%'.$term.'%');
        }
        if ($includeId) {
            $query->andwhere("cc.id=:countryId")
                ->setParameter(':countryId', $includeId);
        }
        $query->setMaxResults($limit);

        $quer   = $query->getQuery();
        $result = $quer->getResult();
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * getting cityinfo by id
     * @param cityId
     * @return cityinfo
     * */
    public function getCityInfo($cityId)
    {
        $query = $this->createQueryBuilder('w')
            ->select("w.name,w.id,concat(w.name,',',s.stateName,',',cc.name) as fullname, cc.code as countryCode, cc.name as countryName")
            ->leftJoin('CorporateBundle:CmsCountries', 'cc', 'WITH', "cc.code = w.countryCode")
            ->leftJoin('CorporateBundle:States', 's', 'WITH', "s.stateCode = w.stateCode and s.countryCode = cc.code")
            ->where("w.id = :cityId")
            ->setParameter(':cityId', $cityId);

        $quer   = $query->getQuery();
        $result = $quer->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    public function getCityCombo($tt_search_critiria_obj)
    {
        $em = $this->getEntityManager();

        $searchTerm = $tt_search_critiria_obj->getTerm();
        $page       = $tt_search_critiria_obj->getPage();
        $limit      = $tt_search_critiria_obj->getLimit();
        $sortOrder  = $tt_search_critiria_obj->getSortOrder();
        $start      = $tt_search_critiria_obj->getStart();
        $params     = $tt_search_critiria_obj->getParam();
        //
        $addWhere = '';
        $countryCode = null;
        if(isset($params['countryCode'])) $countryCode   = $params['countryCode'];
        
        if(isset($countryCode)) $addWhere = " AND p.countryCode like '%$countryCode%' ";

        $query ="SELECT count(p)  FROM CorporateBundle:Webgeocities p 
                LEFT JOIN CorporateBundle:CmsCountries cc WITH cc.code = p.countryCode 
                LEFT JOIN CorporateBundle:States s WITH s.stateCode = p.stateCode AND s.countryCode = cc.code 
                WHERE p.name like :searchterm " . $addWhere;
        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");

        $query_res = $query_exec->getResult();

        $count = $query_res[0][1];
        
        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";

        $SQL = "SELECT p.name,p.id,concat(p.name,',',s.stateName,',',cc.name) as fullname FROM CorporateBundle:Webgeocities p 
                LEFT JOIN CorporateBundle:CmsCountries cc WITH cc.code = p.countryCode 
                LEFT JOIN CorporateBundle:States s WITH s.stateCode = p.stateCode AND s.countryCode = cc.code 
                WHERE p.name like :searchterm " . $addWhere . $sortOrder;
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);
        $combogrid_cats = $query2_exec->getArrayResult();
        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;

        return $result_arr;
    }
}
