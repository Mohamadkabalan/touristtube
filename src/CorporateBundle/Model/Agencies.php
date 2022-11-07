<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use TTBundle\Model\Country;

class Agencies {
    
    private $id;

    private $name;

    private $country;

    public function __construct() {
        $this->country = new Country();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCountry()
    {
        return $this->country;
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
        $agencies = new Agencies();
        if (!empty($params)) {
            /**'Code' suffix appended by TTAutocomplete */
            $agencies->getCountry()->setId($params['countryCode']);
        }
        
        return Utils::array_to_obj($params,$agencies);
    }
}