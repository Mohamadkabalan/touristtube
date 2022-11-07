<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CmsUsers;

class CmsUsersRepository extends EntityRepository
{
    protected $utils;
    protected $em;
    
    /**
     * This method will retrieve all Users of corporate
     * @param
     * @return doctrine object result of Users or false in case of no data
     * */
    public function getCorpoAdminLikeUsers($term, $limit, $id, $accountId)
    {

        $query = $this->createQueryBuilder('u')
        ->select("u.fullname as name,u.id")
        ->where('u.isCorporateAccount = 1');
        if(isset($accountId) && $accountId != 0){
            $query->andwhere('u.corpoAccountId=:accountId')
            ->setParameter('accountId', $accountId);
        }
        if (isset($term) && $term != '') {
            $query->andwhere('u.fname LIKE :term OR u.lname LIKE :term OR u.yourusername LIKE :term')
            ->setParameter('term', '%'.$term.'%');
        }
        if (isset($id) && $id != '') {
            // $query->andwhere('u.id NOT IN(:Id)')
            $query->leftJoin('CorporateBundle:CorpoApprovalFlow', 'a', 'WITH', "a.userId = u.id")
            ->orwhere('a.path NOT LIKE :Id')
            ->setParameter('Id', '%,'.$id.',%');
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
    
    public function updateProfilePicture($parameters)
    {
        
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
        ->update('CorporateBundle:CmsUsers', 'p')
        ->set("p.profilePic", ":profile")
        ->where("p.id=:Id")
        ->setParameter(':profile', $parameters['profile'])
        ->setParameter(':Id', $parameters['id']);
        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function getUserCombo($tt_search_critiria_obj, $moreWhere)
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
        $userProfileLevel = null;
        if(isset($params['userProfileLevel'])) $userProfileLevel   = $params['userProfileLevel'];
        
        if(isset($userProfileLevel)) $addWhere = " AND up.level >= " . $userProfileLevel;
        
        $query ="SELECT count(p)  FROM TTBundle:CmsUsers p
                INNER JOIN CorporateBundle:CorpoUserProfiles up WITH up.id = p.corpoUserProfileId
                INNER JOIN CorporateBundle:CorpoAccount ca WITH (ca.id = p.corpoAccountId)
                WHERE p.published=1 AND p.isCorporateAccount = 1 AND p.fullname like :searchterm $addWhere $moreWhere";
        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");
        
        $query_res = $query_exec->getResult();
        $count = $query_res[0][1];
        
        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";
        
        $SQL = "SELECT p.id,p.fullname FROM TTBundle:CmsUsers p
                INNER JOIN CorporateBundle:CorpoUserProfiles up WITH up.id = p.corpoUserProfileId
                INNER JOIN CorporateBundle:CorpoAccount ca WITH (ca.id = p.corpoAccountId)
                WHERE p.published=1 AND p.isCorporateAccount = 1 AND p.fullname like :searchterm $addWhere $moreWhere $sortOrder ";
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);
        
        $combogrid_cats = $query2_exec->getArrayResult();
        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;
        
        return $result_arr;
    }

    public function unsetAllowApprove($accountId)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
        ->update('TTBundle:CmsUsers', 'p')
        ->set("p.allowApproval", 0)
        ->where("p.corpoAccountId=:accountId")
        ->setParameter(':accountId', $accountId);
        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $query->getResult();
        } else {
            return false;
        }
    }
}