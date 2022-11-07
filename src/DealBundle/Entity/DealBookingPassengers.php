<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealBookingPassengers
 *
 * @ORM\Table(name="deal_booking_passengers")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealBookingPassengers
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
     * @var int
     *
     * @ORM\Column(name="deal_booking_id", type="int", length=11, nullable=false)
     */
    private $dealBookingId;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="int", length=11, nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="age_type", type="string", length=100, nullable=true)
     */
    private $ageType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get dealBookingId
     *
     * @return integer
     */
    function getDealBookingId()
    {
        return $this->dealBookingId;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get age
     *
     * @return integer
     */
    function getAge()
    {
        return $this->age;
    }

    /**
     * Get ageType
     *
     * @return string
     */
    function getAgeType()
    {
        return $this->ageType;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return id
     */
    function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set dealBookingId
     *
     * @param integer $dealBookingId
     *
     * @return dealBookingId
     */
    function setDealBookingId($dealBookingId)
    {
        $this->dealBookingId = $dealBookingId;

        return $this;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return firstName
     */
    function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return lastName
     */
    function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return age
     */
    function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Set ageType
     *
     * @param string $ageType
     *
     * @return ageType
     */
    function setAgeType($ageType)
    {
        $this->ageType = $ageType;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     *
     * @return createdAt
     */
    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     *
     * @return updatedAt
     */
    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}