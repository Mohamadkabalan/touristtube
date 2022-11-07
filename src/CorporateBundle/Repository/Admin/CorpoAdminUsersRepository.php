<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAdminUsers;
use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CorpoAdminUsersRepository extends EntityRepository implements ContainerAwareInterface
{
    protected $utils;
    protected $em;
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }    

    /**
     * This method will retrieve all Users of corporate
     * @param
     * @return doctrine object result of Users or false in case of no data
     * */
    public function getUsersList()
    {
        $query = $this->createQueryBuilder('u')
        ->select('u');
        
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    public function prepareUserDtQuery($request)
    {
        $where = '';        
        $addJoin = "INNER JOIN corpo_user_profiles up ON up.id = cu.corpo_user_profile_id ";
        $addSelect = "";
        if(isset($request['params']['slug'])) {
            $addJoin .= " AND up.slug = '" . $request['params']['slug'] . "'";
        }

        //administrator can see all affiliates, sales, companies, agencies in all accounts
        $slug = $request['params']['slug'];
        if($request['groupId'] != $this->container->getParameter('ROLE_SYSTEM')){
            if(isset($request['params']['slug']) && 
                (
                    $request['profileId'] == $this->container->getParameter('SALES_PERSON') ||
                    $request['profileId'] == $this->container->getParameter('AFFILIATES') ||
                    $request['profileId'] == $this->container->getParameter('COMPANY') ||
                    $request['profileId'] == $this->container->getParameter('AGENCY') ||
                    $request['profileId'] == $this->container->getParameter('RETAIL_AGENCY')
                )
            ) {
                if($slug == 'sales' || $slug == 'users' ){
                    $where .= " AND cu.parent_user_id = " . $request['userId'] . "";
                }
            }
        } else {
            /**affiliate page is only visible to admin so we add it here
             * filter sales users parent to affiliate user selected  ~else */
            if(!empty($request['params']['affiliateUserId'])) {
                $salesProfileId = $this->container->getParameter('SALES_PERSON');
                $where .= " AND cu.parent_user_id = " . $request['params']['affiliateUserId'] . " AND cu.corpo_user_profile_id = $salesProfileId";
            }
        }

        if(!empty($slug) && $slug == 'sales') {
            $companyProfileId = $this->container->getParameter('COMPANY');
            $agencyProfileId = $this->container->getParameter('AGENCY');
            $rAgencyProfileId = $this->container->getParameter('RETAIL_AGENCY');
            $addSelect .= ", company.companyId company__companyId, agency.agencyId agency__agencyId, rAgency.rAgencyId rAgency__rAgencyId";
            $addJoin .= " LEFT JOIN (
                SELECT ca.created_by createdBy, ca.id companyId FROM corpo_account ca
                INNER JOIN cms_users cu ON (cu.corpo_account_id = ca.id AND cu.corpo_user_profile_id = $companyProfileId)
            ) company ON (company.createdBy = cu.id)
            LEFT JOIN (
                SELECT ca.created_by createdBy, ca.id agencyId FROM corpo_account ca
                INNER JOIN cms_users cu ON (cu.corpo_account_id = ca.id AND cu.corpo_user_profile_id = $agencyProfileId)
            ) agency ON (agency.createdBy = cu.id)
            LEFT JOIN (
                SELECT ca.created_by createdBy, ca.id rAgencyId FROM corpo_account ca
                INNER JOIN cms_users cu ON (cu.corpo_account_id = ca.id AND cu.corpo_user_profile_id = $rAgencyProfileId)
            ) rAgency ON (rAgency.createdBy = cu.id)
             ";
        }

        $query = "SELECT cu.id cu__id, FullName, gender, YourEmail, YourCountry, cu.corpo_account_id, ca.name ca__name, allow_approval_all_user $addSelect
            FROM cms_users cu
            LEFT JOIN corpo_account ca ON (ca.id = cu.corpo_account_id)
            $addJoin
        ";

        $result_arr["all_query"] = $query;
        $result_arr['where'] = $where;
        return Utils::prepareDatatableObj($result_arr);
    }
    
    public function profileListUserDtQuery($request){
        $where = '';        
        $addJoin = "INNER JOIN corpo_user_profiles up ON up.id = cu.corpo_user_profile_id ";

        $where .= " AND cu.parent_user_id = " . $request['params']['parentId'] . "";

        $query = "SELECT cu.id cu__id, FullName, gender, YourEmail, YourCountry, cu.corpo_account_id, ca.name ca__name, allow_approval_all_user
            FROM cms_users cu
            LEFT JOIN corpo_account ca ON (ca.id = cu.corpo_account_id)
            $addJoin
        ";

        $result_arr["all_query"] = $query;
        $result_arr['where'] = $where;

        return Utils::prepareDatatableObj($result_arr);
    }

    public function prepareCustomerTransactionsDtQuery($request)
    {
        $addJoin = "INNER JOIN corpo_user_profiles up ON up.id = cu.corpo_user_profile_id ";
        $addWhere = '';

        $addWhere = " AND cu.published != -2";
        if (isset($request['params']['accountId']) && intval($request['params']['accountId']) != 0) {
            $addWhere .= " AND ca.id = ".$request['params']['accountId'];
        } else {
            $accountId = $request['accountId'];
            $addWhere  .= " AND ca.id = $accountId";
        }

        if (isset($request['params']['accountTypeId']) && intval($request['params']['accountTypeId']) != 0) {
            $addWhere .= " AND up.id = ".$request['params']['accountTypeId'];
        }        

        //administrator can see all affiliates, sales, companies, agencies in all accounts
        $affiliates = $this->container->getParameter('AFFILIATES');
        $salesPerson = $this->container->getParameter('SALES_PERSON');
        $company = $this->container->getParameter('COMPANY');
        $agency = $this->container->getParameter('AGENCY');
        $users = $this->container->getParameter('USERS');

        if($request['groupId'] != $this->container->getParameter('ROLE_SYSTEM')){

            if($request['parentsIds'] && count($request['parentsIds']) > 1){
                $addWhere .= " AND cu.parent_user_id IN  (" . implode(',', $request['parentsIds']) . ")";    
            }else{
                $addWhere .= " AND cu.parent_user_id = " . $request['userId'] . "";
            }

            //affiliates, can access its Sales, Compnay, Agency and Users
            if ($request['profileId'] == $affiliates){
                $addWhere .= " AND up.id IN (".$salesPerson.",".$company.",".$agency.",".$users.")";
            }
            //sales person, can access its Compnay, Agency and Users
            if ($request['profileId'] == $salesPerson){
                $addWhere .= " AND up.id IN (".$company.",".$agency.",".$users.")";
            }
            //Compnay, Agency , its own users
            if ($request['profileId'] == $company || $request['profileId'] == $agency){
                $addWhere .= " AND up.id = $users";
            }
        }

        $query = "SELECT cu.id cu__id, FullName, cu.YourUserName as username, gender, YourEmail, YourCountry, cu.corpo_account_id, ca.name ca__name, allow_approval_all_user, up.name as userProfile
            FROM cms_users cu
            LEFT JOIN corpo_account ca ON (ca.id = cu.corpo_account_id)
            $addJoin
        ";

        $result_arr["all_query"] = $query;
        $result_arr['where']     = $addWhere;

        return Utils::prepareDatatableObj($result_arr);
    }

    /**
     * This method will retrieve all User of corporate
     * @param id of User
     * @return doctrine object result of User or false in case of no data
     * */
    public function getUserById($id)
    {
        $query = $this->createQueryBuilder('p')
        ->select('p')
        ->where("p.id = :ID")
        ->setParameter(':ID', $id);
        
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        
        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }
    
    /**
     * This method will add a User for corporate User
     * @param User info list
     * @return doctrine result of User id or false in case of no data
     * */
    public function addUsers($parameters)
    {
        $this->em = $this->getEntityManager();
        $users    = new CorpoAdminUsers();
        $users->setFname($parameters['fname']);
        $users->setLname($parameters['lname']);
        $users->setPassword($parameters['password']);
        $users->setProfileId($parameters['profileId']);
        $this->em->persist($users);
        $this->em->flush();
        if ($users) {
            return $users->getId();
        } else {
            return false;
        }
    }
    
    /**
     * This method will update a User
     * @param id of User
     * @return doctrine object result of User or false in case of no data
     * */
    public function updateUsers($parameters)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
        ->update('CorporateBundle:CorpoAdminUsers', 'p')
        ->set("p.fname", ":fname")
        ->set("p.lname", ":lname")
        ->set("p.password", ":password")
        ->set("p.profileId", ":profileId")
        ->where("p.id=:Id")
        ->setParameter(':fname', $parameters['fname'])
        ->setParameter(':lname', $parameters['lname'])
        ->setParameter(':password', $parameters['password'])
        ->setParameter(':profileId', $parameters['profileId'])
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
     * This method will delete a User
     * @param id of User
     * @return doctrine object result of User or false in case of no data
     * */
    public function deleteUsers($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
        ->delete('CorporateBundle:CorpoAdminUsers', 'p')
        ->where("p.id = :ID")
        ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
    
    /**
     * This method will retrieve all Users of corporate
     * @param
     * @return doctrine object result of Users or false in case of no data
     * */
    public function getCorpoAdminAllUsers()
    {
        $query = $this->createQueryBuilder('u')
        ->select('u');
        
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * This method will retrieve all Users of corporate
     * @param
     * @return doctrine object result of Users or false in case of no data
     * */
    public function getCorpoAdminLikeUsers($term, $limit)
    {
        $query = $this->createQueryBuilder('u')
        ->select("concat(u.fname,' ', u.lname) as name,u.id")
        ->where('u.fname LIKE :term OR u.lname LIKE :term ')
        ->setParameter('term', '%'.$term.'%')
        ->setMaxResults($limit);
        
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }
}
