<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealCategory
 *
 * @ORM\Table(name="deal_cancel_policies")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealCancelPolicies
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
     * @var integer
     *
     * @ORM\Column(name="deal_booking_id", type="integer", nullable=true)
     */
    private $dealBookingId;

    /**
     * @var string
     *
     * @ORM\Column(name="penalty_amount", type="string", length=100, nullable=true)
     */
    private $penaltyAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="valid_to", type="string", length=100, nullable=true)
     */
    private $validTo;

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

    function getDealBookingId()
    {
        return $this->dealBookingId;
    }

    function getPenaltyAmount()
    {
        return $this->penaltyAmount;
    }

    function getValidTo()
    {
        return $this->validTo;
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

    function setDealBookingId($dealBookingId)
    {
        $this->dealBookingId = $dealBookingId;
    }

    function setPenaltyAmount($penaltyAmount)
    {
        $this->penaltyAmount = $penaltyAmount;
    }

    function setValidTo($validTo)
    {
        $this->validTo = $validTo;
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