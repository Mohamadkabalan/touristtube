<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;

class Profiles {
    
    private $id;

    private $name;

    private $account;

    public function __construct()
    {
        $this->account = new Account();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function arrayToObject($params)
    {
        $profiles = new Profiles();
        if (!empty($params)) {
            /**'Code' suffix appended by TTAutocomplete */
            $profiles->getAccount()->setId($params['accountCode']);
        }
        return Utils::array_to_obj($params,$profiles);
    }
}