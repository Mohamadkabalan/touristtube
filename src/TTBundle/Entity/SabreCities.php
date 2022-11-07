<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sabre Airport
 *
 * @ORM\Table(name="sabre_cities")
 */
class SabreCities {

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
     * @ORM\OneToMany(targetEntity="SabreAirport", mappedBy="sabreCity", cascade={"persist"})
     */
    private $sabreAirport;

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
     * Get sabre airport
     *
     * @return SabreAirport
     */
    public function getSabreAirport() {
	return $this->sabreAirport;
    }

    /**
     * Set sabre airport
     *
     * @param SabreAirport $sabreAirport
     */
    public function setSabreAirport(SabreAirports $sabreAirport) {
	$this->sabreAirport = $sabreAirport;
    }

}
