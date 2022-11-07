<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsMobileCountryxy
 *
 * @ORM\Table(name="cms_mobile_countryXY", indexes={@ORM\Index(name="country_code", columns={"country_code"})})
 * @ORM\Entity
 */
class CmsMobileCountryxy
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
     * @ORM\Column(name="country_code", type="string", length=2, nullable=false)
     */
    private $countryCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="x", type="integer", nullable=false)
     */
    private $x;

    /**
     * @var integer
     *
     * @ORM\Column(name="y", type="integer", nullable=false)
     */
    private $y;

    /**
     * @var integer
     *
     * @ORM\Column(name="iscenter", type="integer", nullable=false)
     */
    private $iscenter;



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
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return CmsMobileCountryxy
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set x
     *
     * @param integer $x
     *
     * @return CmsMobileCountryxy
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return integer
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     *
     * @return CmsMobileCountryxy
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return integer
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set iscenter
     *
     * @param integer $iscenter
     *
     * @return CmsMobileCountryxy
     */
    public function setIscenter($iscenter)
    {
        $this->iscenter = $iscenter;

        return $this;
    }

    /**
     * Get iscenter
     *
     * @return integer
     */
    public function getIscenter()
    {
        return $this->iscenter;
    }
}
