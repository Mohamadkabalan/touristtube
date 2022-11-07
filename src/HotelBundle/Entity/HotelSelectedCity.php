<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelSelectedCity
 *
 * @ORM\Table(name="hotel_selected_city", indexes={@ORM\Index(name="city_id", columns={"city_id"}), @ORM\Index(name="published", columns={"published"})})
 */
class HotelSelectedCity
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
     * @ORM\Column(name="country_code", type="string", length=2, nullable=true)
     */
    private $countryCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", nullable=false)
     */
    private $locationId;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="images_path", type="string", length=255, nullable=false)
     */
    private $imagesPath;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg_stars3", type="integer", nullable=false)
     */
    private $avgStars3;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg_stars4", type="integer", nullable=false)
     */
    private $avgStars4;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg_stars5", type="integer", nullable=false)
     */
    private $avgStars5;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

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
     * @return HotelSelectedCity
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
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return HotelSelectedCity
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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return HotelSelectedCity
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

    /**
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return HotelSelectedCity
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get locationId
     *
     * @return integer
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return HotelSelectedCity
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set imagesPath
     *
     * @param string $imagesPath
     *
     * @return HotelSelectedCity
     */
    public function setImagesPath($imagesPath)
    {
        $this->imagesPath = $imagesPath;

        return $this;
    }

    /**
     * Get imagesPath
     *
     * @return string
     */
    public function getImagesPath()
    {
        return $this->imagesPath;
    }

    /**
     * Set avgStars3
     *
     * @param integer $avgStars3
     *
     * @return HotelSelectedCity
     */
    public function setAvgStars3($avgStars3)
    {
        $this->avgStars3 = $avgStars3;

        return $this;
    }

    /**
     * Get avgStars3
     *
     * @return integer
     */
    public function getAvgStars3()
    {
        return $this->avgStars3;
    }

    /**
     * Set avgStars4
     *
     * @param integer $avgStars4
     *
     * @return HotelSelectedCity
     */
    public function setAvgStars4($avgStars4)
    {
        $this->avgStars4 = $avgStars4;

        return $this;
    }

    /**
     * Get avgStars4
     *
     * @return integer
     */
    public function getAvgStars4()
    {
        return $this->avgStars4;
    }

    /**
     * Set avgStars5
     *
     * @param integer $avgStars5
     *
     * @return HotelSelectedCity
     */
    public function setAvgStars5($avgStars5)
    {
        $this->avgStars5 = $avgStars5;

        return $this;
    }

    /**
     * Get avgStars5
     *
     * @return integer
     */
    public function getAvgStars5()
    {
        return $this->avgStars5;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return HotelSelectedCity
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
}
