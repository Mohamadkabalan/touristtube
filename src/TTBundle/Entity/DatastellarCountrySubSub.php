<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatastellarCountrySubSub
 *
 * @ORM\Table(name="datastellar_country_sub_sub", indexes={@ORM\Index(name="country_sub_id", columns={"country_sub_id", "country_id"}), @ORM\Index(name="country_id", columns={"country_id"}), @ORM\Index(name="title", columns={"title"})})
 * @ORM\Entity
 */
class DatastellarCountrySubSub
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
     * @var integer
     *
     * @ORM\Column(name="country_sub_id", type="integer", nullable=false)
     */
    private $countrySubId;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     */
    private $countryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId = '0';



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
     * @return DatastellarCountrySubSub
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
     * Set countrySubId
     *
     * @param integer $countrySubId
     *
     * @return DatastellarCountrySubSub
     */
    public function setCountrySubId($countrySubId)
    {
        $this->countrySubId = $countrySubId;

        return $this;
    }

    /**
     * Get countrySubId
     *
     * @return integer
     */
    public function getCountrySubId()
    {
        return $this->countrySubId;
    }

    /**
     * Set countryId
     *
     * @param integer $countryId
     *
     * @return DatastellarCountrySubSub
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return DatastellarCountrySubSub
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

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return DatastellarCountrySubSub
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }
}
