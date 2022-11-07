<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Webgeocities
 *
 * @ORM\Table(name="webgeocities", indexes={@ORM\Index(name="state_code", columns={"state_code"}), @ORM\Index(name="country_code", columns={"country_code"}), @ORM\Index(name="accent", columns={"accent"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="latitude", columns={"latitude"}), @ORM\Index(name="longitude", columns={"longitude"}), @ORM\Index(name="order_display", columns={"order_display"})})
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\WebgeocitiesRepository")
 */
class Webgeocities
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
     * @ORM\Column(name="country_code", type="string", length=2, nullable=true)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=20, nullable=true)
     */
    private $stateCode;

    /**
     * @var string
     *
     * @ORM\Column(name="accent", type="string", length=255, nullable=true)
     */
    private $accent;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="timezoneid", type="string", length=255, nullable=true)
     */
    private $timezoneid;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_display", type="integer", nullable=false)
     */
    private $orderDisplay = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="from_source", type="string", length=10, nullable=false)
     */
    private $fromSource = 'old';

    /**
     * @var integer
     *
     * @ORM\Column(name="popularity", type="integer", nullable=false)
     */
    private $popularity = '0';

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
     * @return Webgeocities
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
     * Set stateCode
     *
     * @param string $stateCode
     *
     * @return Webgeocities
     */
    public function setStateCode($stateCode)
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    /**
     * Get stateCode
     *
     * @return string
     */
    public function getStateCode()
    {
        return $this->stateCode;
    }

    /**
     * Set accent
     *
     * @param string $accent
     *
     * @return Webgeocities
     */
    public function setAccent($accent)
    {
        $this->accent = $accent;

        return $this;
    }

    /**
     * Get accent
     *
     * @return string
     */
    public function getAccent()
    {
        return $this->accent;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Webgeocities
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Webgeocities
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Webgeocities
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set timezoneid
     *
     * @param string $timezoneid
     *
     * @return Webgeocities
     */
    public function setTimezoneid($timezoneid)
    {
        $this->timezoneid = $timezoneid;

        return $this;
    }

    /**
     * Get timezoneid
     *
     * @return string
     */
    public function getTimezoneid()
    {
        return $this->timezoneid;
    }

    /**
     * Set orderDisplay
     *
     * @param integer $orderDisplay
     *
     * @return Webgeocities
     */
    public function setOrderDisplay($orderDisplay)
    {
        $this->orderDisplay = $orderDisplay;

        return $this;
    }

    /**
     * Get orderDisplay
     *
     * @return integer
     */
    public function getOrderDisplay()
    {
        return $this->orderDisplay;
    }

    /**
     * Set fromSource
     *
     * @param string $fromSource
     *
     * @return Webgeocities
     */
    public function setFromSource($fromSource)
    {
        $this->fromSource = $fromSource;

        return $this;
    }

    /**
     * Get fromSource
     *
     * @return string
     */
    public function getFromSource()
    {
        return $this->fromSource;
    }

    /**
     * Set popularity
     *
     * @param integer $popularity
     *
     * @return Webgeocities
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;

        return $this;
    }

    /**
     * Get popularity
     *
     * @return integer
     */
    public function getPopularity()
    {
        return $this->popularity;
    }
}