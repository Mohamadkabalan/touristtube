<?php

namespace HotelBundle\Model;

/**
 * Description of HotelDivisionCategory
 *
 */
class HotelDivisionCategory
{
    private $id;
    private $name;
    private $group;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setGroup(HotelDivisionCategoryGroup $group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
