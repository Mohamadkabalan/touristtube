<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoUserProfiles;
use CorporateBundle\Entity\CorpoProfilePermissions;
use TTBundle\Utils\Utils;

class CorpoUserProfilesRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all user profiles of corporate
     * @param 
     * @return doctrine object result of user profiles or false in case of no data
     * */
    public function getUserProfilesList()
    {
        $query = $this->createQueryBuilder('up')
            ->select('up');

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    public function prepareUserProfilesDtQuery()
    {
        $query = "SELECT up.id up__id, up.name up__name, GROUP_CONCAT(m.name SEPARATOR ' | ') menu_access, section_title, up.published up__published, up.slug up__slug, up.level up__level 
            FROM corpo_user_profiles up
            LEFT JOIN corpo_user_profile_menus_permission mp ON (mp.user_profile_id = up.id)
            LEFT JOIN corpo_admin_menu m ON (m.id = mp.corpo_menu_id)
        ";
        $result_arr["all_query"] = $query;
        return Utils::prepareDatatableObj($result_arr);
    }

    /**
     * This method will retrieve user profiles by id
     * @param 
     * @return doctrine object result of user profile or false in case of no data
     * */
    public function getUserProfileById($id)
    {
        $query = $this->createQueryBuilder('up')
            ->select('up.id, up.name, up.sectionTitle, up.slug, up.level')
            ->where('up.id = :id')
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
     * This method will add a user profile for corporate account
     * @param $userProfileObj
     * @return doctrine result of user profile id or false in case of no data
     * */
    public function addUserProfile($userProfileObj)
    {
        $this->em = $this->getEntityManager();
        $userProfiles  = new CorpoUserProfiles();

        $name = $userProfileObj->getName();
        $sectionTitle = $userProfileObj->getSectionTitle();
        $slug = $userProfileObj->getSlug();
        $level = $userProfileObj->getLevel();

        if (isset($name) && $name != '') {
            $userProfiles->setName($name);
        }
        if (isset($sectionTitle) && $sectionTitle != '') {
            $userProfiles->setSectionTitle($sectionTitle);
        }
        if (isset($slug) && $slug != '') {
            $userProfiles->setSlug($slug);
        }
        if (isset($level) && $level != '') {
            $userProfiles->setLevel($level);
        }
        $userProfiles->setPublished(1);
        
        $this->em->persist($userProfiles);

        $this->em->flush();
        if ($userProfiles) {
            return $userProfiles->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update a user profile
     * @param $userProfileObj
     * @return doctrine result of user profile id or false in case of no data
     * */
    public function updateUserProfile($userProfileObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('up')
            ->update('CorporateBundle:CorpoUserProfiles', 'up');

        $id = $userProfileObj->getId();
        $name = $userProfileObj->getName();
        $published = $userProfileObj->getPublished();
        $sectionTitle = $userProfileObj->getSectionTitle();
        $slug = $userProfileObj->getSlug();
        $level = $userProfileObj->getLevel();

        if (isset($name) && $name != '') {
            $qb->set("up.name", ":name")
                ->setParameter(':name', $name);
        }

        if (isset($published) && $published != '') {
            $qb->set("up.published", ":published")
                ->setParameter(':published', $published);
        }

        if (isset($sectionTitle) && $sectionTitle != '') {
            $qb->set("up.sectionTitle", ":sectionTitle")
                ->setParameter(':sectionTitle', $sectionTitle);
        }

        if (isset($slug) && $slug != '') {
            $qb->set("up.slug", ":slug")
                ->setParameter(':slug', $slug);
        }

        if (isset($level) && $level != '') {
            $qb->set("up.level", ":level")
                ->setParameter(':level', $level);
        }

        $qb->where("up.id=:id")
            ->setParameter(':id', $id);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /*
     *
     * This method checks for duplicate entry user profile
     * Can be used both in modify and adding user profile
     *
     * @param $params
     */

    public function checkDuplicateUserProfile($params)
    {
        $query = $this->createQueryBuilder('up')
            ->select('up');
        
        if(isset($params['name'])) {
            $query->andwhere('LOWER(up.name) = :name')
                ->setParameter(':name', strtolower($params['name']));
        }
        if(isset($params['slug'])) {
            $query->andwhere('LOWER(up.slug) = :slug')
                ->setParameter(':slug', strtolower($params['slug']));
        }


        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        $duplicate = (!empty($result)) ? true : false;
        return $duplicate;
    }

    /**
     * This method will publish a user profile
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function publish($id, $published)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('up')
            ->update('CorporateBundle:CorpoUserProfiles', 'up')
            ->set("up.published", ":published")
            ->where("up.id = :ID")
            ->setParameter(':published', $published)
            ->setParameter(':ID', $id);

        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /* This method will retrieve all active account types of corporate
     * @param 
     * @return doctrine object result of account types or false in case of no data
     * */
    public function getUserProfileCombo($tt_search_critiria_obj)
    {
        $em = $this->getEntityManager();

        $searchTerm = $tt_search_critiria_obj->getTerm();
        $page       = $tt_search_critiria_obj->getPage();
        $limit      = $tt_search_critiria_obj->getLimit();
        $sortOrder  = $tt_search_critiria_obj->getSortOrder();
        $start      = $tt_search_critiria_obj->getStart();
        $params     = $tt_search_critiria_obj->getParam();
        //
        $userProfileLevel = null;
        if(isset($params['userProfileLevel'])) $userProfileLevel   = $params['userProfileLevel'];
        
        $addWhere = ( (!empty($userProfileLevel) && isset($userProfileLevel) ) ? " AND p.level >= $userProfileLevel" : "");

        $query ="SELECT count(p)  FROM CorporateBundle:CorpoUserProfiles p 
                WHERE p.published=1 AND p.name like :searchterm $addWhere ";
        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");

        $query_res = $query_exec->getResult();

        $count = $query_res[0][1];
        
        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";

        $SQL = "SELECT p.id,p.name FROM CorporateBundle:CorpoUserProfiles p 
                WHERE p.published=1 AND p.name like :searchterm $addWhere " . $sortOrder;
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);
        $combogrid_cats = $query2_exec->getArrayResult();
        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;

        return $result_arr;
    }

    /**
     * This method will retrieve profile menu list by id
     * @param 
     * @return doctrine object result of profile menus or false in case of no data
     * */
    public function getProfileMenuListById($id)
    {
        $query = $this->createQueryBuilder('up')
            ->select('pp.menuId')
            ->leftJoin('CorporateBundle:CorpoProfilePermissions', 'pp', 'WITH', "pp.profileId = up.id")
            ->where('up.id = :id')
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
     * This method will add menus for an account type for corporate accounts
     * @param $menuObj
     * @return doctrine result of account id or false in case of no data
     * */
    public function addProfileMenus($menuObj)
    {
        $this->em = $this->getEntityManager();
        $profileId = $menuObj->getProfile()->getId();
        $menus = $menuObj->getMenus();
        $updatedBy = $menuObj->getUpdatedBy()->getId();

        foreach($menus as $menu) {
            $profileMenu  = new CorpoProfilePermissions();
            $profileMenu->setProfileId($profileId);
            $profileMenu->setMenuId($menu);
            $profileMenu->setUpdatedBy($updatedBy);
            $profileMenu->setUpdatedOn(new \DateTime("now"));
            $this->em->persist($profileMenu);
        }
        $this->em->flush(); 
        
        if ($profileMenu) {
            return $profileMenu->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will delete a user profile menu list
     * @param $id
     * @return doctrine result of account type menu list or false in case of no data
     * */
    public function deleteUserProfileMenuList($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('pp')
            ->delete('CorporateBundle:CorpoProfilePermissions', 'pp')
            ->where("pp.profileId = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will retrieve profile by slug
     * @param 
     * @return doctrine object result of profile or false in case of no data
     * */
    public function getCorpoProfileBySlug($slug)
    {
        $query = $this->createQueryBuilder('up')
            ->select('up')
            ->where('up.slug = :slug')
            ->setParameter(':slug', $slug);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }
}