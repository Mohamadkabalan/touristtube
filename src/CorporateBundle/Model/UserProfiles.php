<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;

class UserProfiles {
    
    private $id;

    private $name;

    private $published;

    private $sectionTitle;

    private $slug;

    private $level;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function getSectionTitle()
    {
        return $this->sectionTitle;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function setSectionTitle($sectionTitle)
    {
        $this->sectionTitle = $sectionTitle;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function arrayToObject($params)
    {
        $userProfiles = new UserProfiles();
        
        return Utils::array_to_obj($params,$userProfiles);
    }
}