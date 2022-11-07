<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAccountType;
use TTBundle\Utils\Utils;

class CorpoAccountTypeRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all account types of corporate
     * @param 
     * @return doctrine object result of account types or false in case of no data
     * */
    public function getAccountTypeList()
    {
        $query = $this->createQueryBuilder('atl')
            ->select('atl, u.fullname')
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = atl.createdBy");

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    public function prepareAccountTypeDtQuery()
    {
        $query = "
            SELECT cat.id cat__id, cat.name cat__name, FullName, cat.created_at cat__created_at,
                CASE
                    WHEN is_active = 1 THEN 'Active'
                    ELSE 'Inactive'
                END is_active
            FROM corpo_account_type cat
            LEFT JOIN cms_users cu ON (cu.id = cat.created_by)
        ";
        $result_arr["all_query"] = $query;
        return Utils::prepareDatatableObj($result_arr);
    }

    /**
     * This method will retrieve all active account types of corporate
     * @param 
     * @return doctrine object result of account types or false in case of no data
     * */
    public function getAccountTypeActiveList()
    {
        $query = $this->createQueryBuilder('atl')
            ->select('atl, u.fullname')
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = atl.createdBy")
            ->where('atl.isActive = 1');

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve account type menu list by id
     * @param 
     * @return doctrine object result of account type menus or false in case of no data
     * */
    public function getAccountTypeMenusById($id)
    {
        $query = $this->createQueryBuilder('atm')
            ->select('m.menuId')
            ->leftJoin('CorporateBundle:CorpoAccountTypeMenu', 'm', 'WITH', "m.accountTypeId = atm.id")
            ->where('atm.id = :id')
            ->setParameter(':id', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve account type by id
     * @param 
     * @return doctrine object result of account type or false in case of no data
     * */
    public function getAccountTypeById($id)
    {
        $query = $this->createQueryBuilder('at')
            ->select('at.id, at.name, at.slug')
            ->where('at.id = :id')
            ->setParameter(':id', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will add an account type for corporate accounts
     * @param $acctTypeObj
     * @return doctrine result of account type id or false in case of no data
     * */
    public function addAccountType($acctTypeObj)
    {
        $this->em = $this->getEntityManager();
        $accountType  = new CorpoAccountType();

        $name = $acctTypeObj->getName();
        $createdBy = $acctTypeObj->getCreatedBy()->getId();
        $slug = $acctTypeObj->getSlug();

        if (isset($name) && $name != '') {
            $accountType->setName($name);
        }
        if (isset($createdBy) && $createdBy != '') {
            $accountType->setCreatedBy($createdBy);
        }
        if (isset($slug) && $slug != '') {
            $accountType->setSlug($slug);
        }
        $accountType->setCreatedAt(new \DateTime());
        $accountType->setIsActive(1);
        
        $this->em->persist($accountType);
        $this->em->flush();
        if ($accountType) {
            return $accountType->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update an account 
     * @param $acctTypeObj
     * @return doctrine result of account type id or false in case of no data
     * */
    public function updateAccountType($acctTypeObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('at')
            ->update('CorporateBundle:CorpoAccountType', 'at');

        $id = $acctTypeObj->getId();
        $name = $acctTypeObj->getName();
        $slug = $acctTypeObj->getSlug();

        if (isset($name) && $name != '') {
            $qb->set("at.name", ":name")
                ->setParameter(':name', $name);
        }
        if (isset($slug) && $slug != '') {
            $qb->set("at.slug", ":slug")
                ->setParameter(':slug', $slug);
        }

        $qb->where("at.id=:id")
            ->setParameter(':id', $id);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will deactivate an account type
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function deleteAccountType($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('at')
            ->update('CorporateBundle:CorpoAccountType', 'at')
            ->set("at.isActive", "0")
            ->where("at.id = :ID")
            ->setParameter(':ID', $id);

        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will activate an account type
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function activateAccountType($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('at')
            ->update('CorporateBundle:CorpoAccountType', 'at')
            ->set("at.isActive", "1")
            ->where("at.id = :ID")
            ->setParameter(':ID', $id);

        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will deactivate an account type
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function deactivateAccountType($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('at')
            ->update('CorporateBundle:CorpoAccountType', 'at')
            ->set("at.isActive", "0")
            ->where("at.id = :ID")
            ->setParameter(':ID', $id);

        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /*
     *
     * This method checks for duplicate entry account type name
     * Can be used both in modify and adding account type
     *
     * @param $params()
     */

    public function checkDuplicateAccountType($params)
    {
        $query = $this->createQueryBuilder('at')
            ->select('at');
        
        if(isset($params['name'])) {
            $query->andwhere('LOWER(at.name) = :name')
                ->setParameter(':name', strtolower($params['name']));
        }
        if(isset($params['slug'])) {
            $query->andwhere('LOWER(at.slug) = :slug')
                ->setParameter(':slug', strtolower($params['slug']));
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        $duplicate = (!empty($result)) ? true : false;
        return $duplicate;
    }

    /**
     * This method will retrieve all active account types of corporate
     * @param 
     * @return doctrine object result of account types or false in case of no data
     * */
    public function getAccountTypeCombo($tt_search_critiria_obj)
    {
        $em = $this->getEntityManager();

        $searchTerm = $tt_search_critiria_obj->getTerm();
        $page       = $tt_search_critiria_obj->getPage();
        $limit      = $tt_search_critiria_obj->getLimit();
        $sortOrder  = $tt_search_critiria_obj->getSortOrder();
        $start      = $tt_search_critiria_obj->getStart();

        $query ="SELECT count(p)  FROM CorporateBundle:CorpoAccountType p 
                LEFT JOIN CorporateBundle:CmsUsers u WITH u.id = p.createdBy 
                WHERE p.isActive=1 AND p.name like :searchterm ";
        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");

        $query_res = $query_exec->getResult();

        $count = $query_res[0][1];
        
        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";

        $SQL = "SELECT p.id,p.name,u.fullname FROM CorporateBundle:CorpoAccountType p 
                LEFT JOIN CorporateBundle:CmsUsers u WITH u.id = p.createdBy 
                WHERE p.isActive=1 AND p.name like :searchterm " . $sortOrder;
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);
        $combogrid_cats = $query2_exec->getArrayResult();
        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;

        return $result_arr;
    }
}