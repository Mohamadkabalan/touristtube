<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sabre Airport
 *
 * @ORM\Table(name="sabre_airports")
 */
class SabreAirports {

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
     * @ORM\Column(name="code", type="string", length=3, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=2, nullable=false)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city_code", type="string", length=3, nullable=false)
     */
    private $cityCode;

    /**
     * @ORM\OneToOne(targetEntity="SabreCities", mappedBy="sabreCountry", cascade={"persist"})
     */
    private $sabreCountry;

    /**
     * @ORM\OneToOne(targetEntity="SabreCities", mappedBy="sabreCity", cascade={"persist"})
     */
    private $sabreCity;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
	return $this->id;
    }

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
     *
     * @return string
     */
    public function setCode($code) {
	$this->code = $code;
	return $this;
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
     *
     * @return string
     */
    public function setName($name) {
	$this->name = $name;
	return $this;
    }

    /**
     * Get country code
     *
     * @return string
     */
    public function getCountryCode() {
	return $this->countryCode;
    }

    /**
     * Set country code
     *
     * @param string $countryCode
     *
     * @return string
     */
    public function setCountryCode($countryCode) {
	$this->countryCode = $countryCode;

	return $this;
    }

    /**
     * Get city code
     *
     * @return string
     */
    public function getCityCode() {
	return $this->cityCode;
    }

    /**
     * Set city code
     *
     * @param string $cityCode
     *
     * @return string
     */
    public function setCityCode($cityCode) {
	$this->cityCode = $cityCode;

	return $this;
    }

    /**
     * Get sabre country
     *
     * @return SabreCountry
     */
    public function getSabreCountry() {
	return $this->sabreCountry;
    }

    /**
     * Set sabre country
     *
     * @param SabreCountries $sabreCountry
     */
    public function setSabreCountry(SabreCountries $sabreCountry) {
	$sabreCountry->setSabreCountries($this);
	$this->sabreCountry = $sabreCountry;
    }

    /**
     * Get sabre city
     *
     * @return SabreCity
     */
    public function getSabreCity() {
	return $this->sabreCity;
    }

    /**
     * Set sabre city
     *
     * @param SabreCities $sabreCity
     */
    public function setSabreCity(SabreCities $sabreCity) {
	$sabreCity->setSabreCities($this);
	$this->sabreCity = $sabreCity;
    }

}
