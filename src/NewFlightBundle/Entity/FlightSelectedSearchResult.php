<?php

namespace NewFlightBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FlightSelectedSearchResult
 *
 * @ORM\Entity
 * @ORM\Table(name="flight_selected_search_result")
 */
class FlightSelectedSearchResult
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
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_ip", type="string", length=18, nullable=false)
     */
    private $customerIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="search_date_time", type="datetime", nullable=false)
     */
    private $searchDateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="flight_type", type="string", length=18, nullable=false)
     */
    private $flightType;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_pnr_created", type="integer", nullable=false)
     */
    private $isPnrCreated;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_ticket_issued", type="integer", nullable=false)
     */
    private $isTicketIssued;

    /**
     * @var integer
     *
     * @ORM\Column(name="adt_count", type="integer", nullable=false)
     */
    private $adtCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="cnn_count", type="integer", nullable=false)
     */
    private $cnnCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="inf_count", type="integer", nullable=false)
     */
    private $infCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="cabin_selected", type="integer", nullable=false)
     */
    private $cabinSelected;

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
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get customerIp
     *
     * @return string
     */
    public function getCustomerIp()
    {
        return $this->customerIp;
    }

    /**
     * Get searchDateTime
     *
     * @return datetime
     */
    public function getSearchDateTime()
    {
        return $this->searchDateTime;
    }

    /**
     * Get flightType
     *
     * @return string
     */
    public function getFlightType()
    {
        return $this->flightType;
    }

    /**
     * Get isPnrCreated
     *
     * @return integer
     */
    public function getIsPnrCreated()
    {
        return $this->isPnrCreated;
    }

    /**
     * Get isTicketIssued
     *
     * @return integer
     */
    public function getIsTicketIssued()
    {
        return $this->isTicketIssued;
    }

    /**
     * Get adtCount
     *
     * @return integer
     */
    public function getAdtCount()
    {
        return $this->adtCount;
    }

    /**
     * Get cnnCount
     *
     * @return integer
     */
    public function getCnnCount()
    {
        return $this->cnnCount;
    }

    /**
     * Get infCount
     *
     * @return integer
     */
    public function getInfCount()
    {
        return $this->infCount;
    }

    /**
     * Get cabinSelected
     *
     * @return integer
     */
    public function getCabinSelected()
    {
        return $this->cabinSelected;
    }

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Set customerIp
     *
     * @param string $customerIp
     */
    public function setCustomerIp($customerIp)
    {
        $this->customerIp = $customerIp;
    }

    /**
     * Set searchDateTime
     *
     * @param datetime $searchDateTime
     */
    public function setSearchDateTime($searchDateTime)
    {
        $this->searchDateTime = $searchDateTime;
    }

    /**
     * Set flightType
     *
     * @param string $flightType
     */
    public function setFlightType($flightType)
    {
        $this->flightType = $flightType;
    }

    /**
     * Set isPnrCreated
     *
     * @param integer $isPnrCreated
     */
    public function setIsPnrCreated($isPnrCreated)
    {
        $this->isPnrCreated = $isPnrCreated;
    }

    /**
     * Set isTicketIssued
     *
     * @param integer $isTicketIssued
     */
    public function setIsTicketIssued($isTicketIssued)
    {
        $this->isTicketIssued = $isTicketIssued;
    }

    /**
     * Set adtCount
     *
     * @param integer $adtCount
     */
    public function setAdtCount($adtCount)
    {
        $this->adtCount = $adtCount;
    }

    /**
     * Set cnnCount
     *
     * @param integer $cnnCount
     */
    public function setCnnCount($cnnCount)
    {
        $this->cnnCount = $cnnCount;
    }

    /**
     * Set infCount
     *
     * @param integer $infCount
     */
    public function setInfCount($infCount)
    {
        $this->infCount = $infCount;
    }

    /**
     * Set cabinSelected
     *
     * @param integer $cabinSelected
     */
    public function setCabinSelected($cabinSelected)
    {
        $this->cabinSelected = $cabinSelected;
    }
}