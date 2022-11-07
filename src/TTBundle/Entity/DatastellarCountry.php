<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatastellarCountry
 *
 * @ORM\Table(name="datastellar_country", uniqueConstraints={@ORM\UniqueConstraint(name="title", columns={"title"}), @ORM\UniqueConstraint(name="slug", columns={"slug"}), @ORM\UniqueConstraint(name="iso_country_code_2", columns={"iso_country_code_2"})})
 * @ORM\Entity
 */
class DatastellarCountry
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_country_code_2", type="string", length=2, nullable=false)
     */
    private $isoCountryCode2;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_country_code_3", type="string", length=3, nullable=false)
     */
    private $isoCountryCode3;

    /**
     * @var string
     *
     * @ORM\Column(name="calling_code", type="string", length=255, nullable=false)
     */
    private $callingCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;



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
     * Set title
     *
     * @param string $title
     *
     * @return DatastellarCountry
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return DatastellarCountry
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set isoCountryCode2
     *
     * @param string $isoCountryCode2
     *
     * @return DatastellarCountry
     */
    public function setIsoCountryCode2($isoCountryCode2)
    {
        $this->isoCountryCode2 = $isoCountryCode2;

        return $this;
    }

    /**
     * Get isoCountryCode2
     *
     * @return string
     */
    public function getIsoCountryCode2()
    {
        return $this->isoCountryCode2;
    }

    /**
     * Set isoCountryCode3
     *
     * @param string $isoCountryCode3
     *
     * @return DatastellarCountry
     */
    public function setIsoCountryCode3($isoCountryCode3)
    {
        $this->isoCountryCode3 = $isoCountryCode3;

        return $this;
    }

    /**
     * Get isoCountryCode3
     *
     * @return string
     */
    public function getIsoCountryCode3()
    {
        return $this->isoCountryCode3;
    }

    /**
     * Set callingCode
     *
     * @param string $callingCode
     *
     * @return DatastellarCountry
     */
    public function setCallingCode($callingCode)
    {
        $this->callingCode = $callingCode;

        return $this;
    }

    /**
     * Get callingCode
     *
     * @return string
     */
    public function getCallingCode()
    {
        return $this->callingCode;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return DatastellarCountry
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }
}
