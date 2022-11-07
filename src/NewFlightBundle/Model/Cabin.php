<?php

namespace NewFlightBundle\Model;

class Cabin extends flightVO
{
    /**
     *
     */
    private $id = '';

    /**
     *
     */
    private $name = '';

    /**
     *
     */
    private $code = '';

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get code
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set id
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set code
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}