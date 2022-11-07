<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\Currency;

class CurrencyRepository extends EntityRepository
{

    /**
     * This method will retrieve list of currencies like term
     * @param term
     * @return doctrine object result of currencies or false in case of no data
     * */
    public function getCorpoAdminLikeCurrency($term, $limit)
    {
        $query = $this->createQueryBuilder('w')
            ->select('w.name,w.id,w.code');
        if ($term) {
            $query->where('concat_ws(w.code,w.name, " ") LIKE :term')
                ->setParameter('term', '%'.$term.'%');
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
     * This method will retrieve list of currencies like term
     * @param term
     * @return doctrine object result of currencies or false in case of no data
     * */
    public function getCurrencyCombo($tt_search_critiria_obj)
    {
        $em = $this->getEntityManager();

        $searchTerm = $tt_search_critiria_obj->getTerm();
        $page       = $tt_search_critiria_obj->getPage();
        $limit      = $tt_search_critiria_obj->getLimit();
        $sortOrder  = $tt_search_critiria_obj->getSortOrder();
        $start      = $tt_search_critiria_obj->getStart();

        $query ="SELECT count(p)  FROM CorporateBundle:Currency p  
                WHERE concat_ws(p.code, p.name, ' ') like :searchterm ";
        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");

        $query_res = $query_exec->getResult();

        $count = $query_res[0][1];
        
        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";

        $SQL = "SELECT p.name,p.id,p.code FROM CorporateBundle:Currency p 
                WHERE concat(p.name,p.code) like :searchterm " . $sortOrder;
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);
        $combogrid_cats = $query2_exec->getArrayResult();
        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;

        return $result_arr;
    }
}