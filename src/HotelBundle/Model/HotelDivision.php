<?php

namespace HotelBundle\Model;

/**
 * Description of HotelDivision
 *
 */
class HotelDivision
{
    private $id;
    private $name;
    private $category;
    private $order;
    private $parent;

    public function __construct()
    {
        $this->category = null;
        $this->parent   = null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getParent()
    {
        return $this->parent;
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

    public function setCategory(HotelDivisionCategory $category)
    {
        $this->category = $category;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function setParent(HotelDivision $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toArray = get_object_vars($this);

        if (!empty($this->getCategory())) {
            $toArray['category'] = $this->getCategory()->toArray();
        }

        if (!empty($this->getParent())) {
            $toArray['parent'] = $this->getParent()->toArray();
        }

        return $toArray;
    }
}
