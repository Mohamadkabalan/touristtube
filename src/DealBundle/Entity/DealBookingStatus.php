<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealBookingStatus
 *
 * @ORM\Table(name="deal_booking_status")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 * @ORM\Entity
 */
class DealBookingStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="booking_status", type="string", length=50, nullable=false)
     */
    private $bookingStatus;

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

    function getId()
    {
        return $this->id;
    }

    function getBookingStatus()
    {
        return $this->bookingStatus;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setBookingStatus($bookingStatus)
    {
        $this->bookingStatus = $bookingStatus;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}