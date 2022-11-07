<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Airline
 *
 * @ORM\Table(name="airline", indexes={@ORM\Index(name="code")})
 */
class Airline
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=4, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="alternative_business_name", type="string", length=255, nullable=false)
     */
    private $alternativeBusinessName;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=false)
     */
    private $logo;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Airline
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Airline
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set alternativeBusinessName
     *
     * @param string $alternativeBusinessName
     *
     * @return Airline
     */
    public function setAlternativeBusinessName($alternativeBusinessName)
    {
        $this->alternativeBusinessName = $alternativeBusinessName;

        return $this;
    }

    /**
     * Get alternativeBusinessName
     *
     * @return string
     */
    public function getAlternativeBusinessName()
    {
        return $this->alternativeBusinessName;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Airline
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
