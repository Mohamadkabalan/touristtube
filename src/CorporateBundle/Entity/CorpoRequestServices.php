<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoRequestServices
 *
 * @ORM\Table(name="corpo_request_services")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoRequestServicesRepository")
 */
class CorpoRequestServices
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $accountId;

    /**
     * @var Date
     *
     * @ORM\Column(name="from_date", type="date", nullable=false)
     */
    private $fromDate;

    /**
     * @var Date
     *
     * @ORM\Column(name="to_date", type="date", nullable=false)
     */
    private $toDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="destination_city_id", type="integer", nullable=false)
     */
    private $destinationCityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="departure_city_id", type="integer", nullable=false)
     */
    private $departureCityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     */
    private $countryId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_approved", type="boolean", nullable=false)
     */
    private $isApproved = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="approved_by", type="integer", nullable=false)
     */
    private $approvedBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="approved_at", type="datetime", nullable=false)
     */
    private $approvedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

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
     * Get title
     *
     * @return string
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * Get accountId
     *
     * @return integer
     */
    function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Get toDate
     *
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get destinationCityId
     *
     * @return integer
     */
    function getDestinationCityId()
    {
        return $this->destinationCityId;
    }

    /**
     * Get departureCityId
     *
     * @return integer
     */
    function getDepartureCityId()
    {
        return $this->departureCityId;
    }

    /**
     * Get countryId
     *
     * @return integer
     */
    function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Get isApproved
     *
     * @return boolean
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

    /**
     * Get approvedBy
     *
     * @return integer
     */
    function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * Get approvedAt
     *
     * @return DateTime
     */
    function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    function getCreatedBy()
    {
        return $this->createdBy;
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

        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return title
     */
    function setTitle($title)
    {
        $this->title = $title;

        return $this->title;
    }

    /**
     * Set accountId
     *
     * @param integer $accountId
     *
     * @return accountId
     */
    function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this->accountId;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     *
     * @return toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this->toDate;
    }

    /**
     * Set destinationCityId
     *
     * @param integer $destinationCityId
     *
     * @return destinationCityId
     */
    function setDestinationCityId($destinationCityId)
    {
        $this->destinationCityId = $destinationCityId;

        return $this->destinationCityId;
    }

    /**
     * Set departureCityId
     *
     * @param integer $departureCityId
     *
     * @return departureCityId
     */
    function setDepartureCityId($departureCityId)
    {
        $this->departureCityId = $departureCityId;

        return $this->departureCityId;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this->description;
    }

    /**
     * Set countryId
     *
     * @param integer $countryId
     *
     * @return countryId
     */
    function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this->countryId;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     *
     * @return isApproved
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this->isApproved;
    }

    /**
     * Set approvedBy
     *
     * @param integer $approvedBy
     *
     * @return approvedBy
     */
    function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;

        return $this->approvedBy;
    }

    /**
     * Set approvedAt
     *
     * @param DateTime $approvedAt
     *
     * @return approvedAt
     */
    function setApprovedAt($approvedAt)
    {
        $this->approvedAt = $approvedAt;

        return $this->approvedAt;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return createdAt
     */
    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this->createdAt;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return createdBy
     */
    function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this->createdBy;
    }
}