<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;

class Menu
{
    private $id     = 0;
    private $name   = '';
    private $parent = null;
    private $method = null;

    private $path;

    private $onClick;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(Menu $parent)
    {
        $this->parent = $parent;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function removeAttributes()
    {
        unset($this->method);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getOnClick()
    {
        return $this->onClick;
    }

    public function setOnClick($onClick)
    {
        $this->onClick = $onClick;
    }

    public function toArray()
    {
        $toreturn = get_object_vars($this);

        if ($this->getParent()) {
            $parent             = $this->getParent()->toArray();
            unset($parent['parent']);
            $toreturn['parent'] = $parent;
        }

        return $toreturn;
    }

    public function arrayToObject($params)
    {
        $menu = new Menu();

        return Utils::array_to_obj($params,$menu);
    }
}
