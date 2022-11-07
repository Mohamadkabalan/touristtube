<?php

namespace TTBundle\Model;

/**
 * Country contains the attributes for Country.
 *
 * @author Anna Lou Parejo <anna.parejo@touristtube.com>
 */
class Country
{
    private $id = 0;

    private $name = '';

    private $code = '';

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getCode()
    {
        return $this->code;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setCode($code)
    {
        $this->code = $code;
    }
}