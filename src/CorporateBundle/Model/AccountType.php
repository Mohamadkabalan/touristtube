<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use TTBundle\Model\User;

class AccountType {
    
    private $id;

    private $name;

    private $createdBy;

    private $isActive;

    private $slug;

    public function __construct() {
        $this->createdBy = new User();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function arrayToObject($params)
    {
        $accountType = new AccountType();
        if (!empty($params)) {
            $accountType->getCreatedBy()->setId($params['createdBy']);
        }
        return Utils::array_to_obj($params,$accountType);
    }
}