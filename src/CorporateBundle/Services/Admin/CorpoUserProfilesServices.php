<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CorpoUserProfilesServices
{
    protected $utils;
    protected $em;
    protected $container;

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container)
    {
        $this->utils              = $utils;
        $this->em                 = $em;
        $this->container          = $container;
    }

    /**
     * getting from the repository all user profiles
     *
     * @return list
     */
    public function getCorpoUserProfilesList()
    {
        $userProfiles = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->getUserProfilesList();
        return $userProfiles;
    }

    public function prepareUserProfilesDtQuery()
    {
        return $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->prepareUserProfilesDtQuery();
    }

    /**
     * getting from the repository profile accessible menus by id
     *
     * @return list
     */
    public function getProfileMenuListById($id)
    {
        $menus = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->getProfileMenuListById($id);
        return $menus;
    }

    /**
     * getting from the repository a user profile by id
     *
     * @return list
     */
    public function getCorpoUserProfileById($id)
    {
        $userProfile = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->getUserProfileById($id);
        return $userProfile;
    }

    /**
     * adding a user profile
     *
     * @return list
     */
    public function addUserProfile($userProfileObj)
    {
        $id = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->addUserProfile($userProfileObj);
        return $id;
    }

    /**
     * updating a user profile
     *
     * @return list
     */
    public function updateUserProfile($userProfileObj)
    {
        $userProfile = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->updateUserProfile($userProfileObj);
        $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->deleteUserProfileMenuList($userProfileObj->getId());
        return $userProfile;
    }

    /*
     *
     * This method checks for duplicate entry of user profile
     * Can be used both in modify and adding user profile
     *
     * @param $params
     *
     * @return boolean true or false
     */

    public function checkDuplicate($params)
    {
        return $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->checkDuplicateUserProfile($params);
    }

    /**
     * publish a user profile
     *
     * @return list
     */
    public function publish($id, $published)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->publish($id, $published);
        return $result;
    }

    /**
     * adding an account type menu access
     *
     * @return list
     */
    public function addProfileMenus($menuObj)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->addProfileMenus($menuObj);
        return $result;
    }

    /**
     * get section by slug
     * 
     * @return $userProfile
     */
    public function getCorpoProfileBySlug($slug) {
        $userProfile = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->getCorpoProfileBySlug($slug);
        return $userProfile;
    }
}