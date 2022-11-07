<?php

namespace FlightBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FlightCabin
 * 
 * @ORM\Entity
 * @ORM\Table(name="flight_cabin")
 */
class FlightCabin {

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string")
     * @ORM\Id
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * Get code
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }
}