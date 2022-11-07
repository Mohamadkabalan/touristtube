<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * States
 *
 * @ORM\Table(name="states", indexes={@ORM\Index(name="country_code", columns={"country_code"}), @ORM\Index(name="state_code", columns={"state_code"}), @ORM\Index(name="state_name", columns={"state_name"}), @ORM\Index(name="country_code_2", columns={"country_code", "state_code"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\CitiesRepository")
 */
class States
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
     * @ORM\Column(name="country_code", type="string", length=2)
     */
    private $countryCode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="state_code", type="string", length=20)
     */
    private $stateCode = '';

    /**
     * @var string
     *
     * @ORM\Column(name="state_name", type="string", length=255, nullable=true)
     */
    private $stateName;

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
     * @return States
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
     * @return States
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
     * Set stateName
     *
     * @param string $stateName
     *
     * @return States
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;

        return $this;
    }

    /**
     * Get stateName
     *
     * @return string
     */
    public function getStateName()
    {
        return $this->stateName;
    }
    /**
     * Set popularity
     *
     * @param integer $popularity
     *
     * @return States
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
