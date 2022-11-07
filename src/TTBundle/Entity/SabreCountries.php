<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sabre Airport
 *
 * @ORM\Table(name="sabre_countries")
 */
class SabreCountries {

    /**
     * The code
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2, nullable=false)
     * @ORM\Id
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="SabreAirports", inversedBy="sabreCountry")
     * @ORM\JoinColumn(name="code", referencedColumnName="code")
     */
    private $sabreAirport;

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
