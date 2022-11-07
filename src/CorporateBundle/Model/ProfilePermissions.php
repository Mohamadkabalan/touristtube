<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use CorporateBundle\Model\UserProfiles;
use TTBundle\Model\User;

class ProfilePermissions {
    
    private $id;

    private $profile;

    private $menus = array();

    private $updatedBy;

    public function __construct() {
        $this->profile = new UserProfiles();
        $this->updatedBy = new User();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    
   public function getMenus()
   {
       return $this->menus;
   }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    public function setMenu($menus)
    {
        $this->menus = $menus;
    }

    public function arrayToObject($params)
    {
        $profilePermissions = new ProfilePermissions();
        if (!empty($params)) {
            $profilePermissions->getProfile()->setId($params['profileId']);
            $profilePermissions->getUpdatedBy()->setId($params['updatedBy']);

            $menuAccess = [];
            foreach ($params['menu'] as $menu) {
                $menuAccess[] = $menu;
            }
            $profilePermissions->setMenu($menuAccess);
        }
        return Utils::array_to_obj($params,$profilePermissions);
    }
}