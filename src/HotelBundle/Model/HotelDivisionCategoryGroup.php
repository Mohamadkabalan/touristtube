<?php

namespace HotelBundle\Model;

/**
 * Description of HotelDivisionCategoryGroup
 *
 */
class HotelDivisionCategoryGroup
{
    private $id;
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
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

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
