<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;

class AccountTypeMenu {
    
    private $id;

    private $type;

    private $menus = array();

    public function __construct() {
        $this->type = new AccountType();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMenus()
    {
        return $this->menus;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMenu($menus)
    {
        $this->menus = $menus;
    }

    public function arrayToObject($params)
    {
        $acctTypeMenu = new AccountTypeMenu();
        if (!empty($params)) {
            $acctTypeMenu->getType()->setId($params['typeId']);

            $menuAccess = [];
            foreach ($params['menu'] as $menu) {
                $menuAccess[] = $menu;
            }
            $acctTypeMenu->setMenu($menuAccess);
        }
        return Utils::array_to_obj($params,$acctTypeMenu);
    }
}