<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deal
 *
 * @ORM\Table(name="deal", indexes={@ORM\Index(name="currency_id", columns={"currency_id"})})
 * @ORM\Entity
 */
class Deal
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_ts", type="datetime", nullable=false)
     */
    private $createTs = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="currency_id", type="integer", nullable=false)
     */
    private $currencyId='0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="highlights", type="text", length=65535, nullable=true)
     */
    private $highlights;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=false)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="summary_title", type="string", length=255, nullable=false)
     */
    private $summaryTitle;
	
    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text", length=65535, nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_summary_title", type="string", length=255, nullable=false)
     */
    private $hotelSummaryTitle;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hotel_summary", type="text", length=65535, nullable=true)
     */
    private $hotelSummary;
	
    /**
     * @var \Date
     *
     * @ORM\Column(name="tour_from_date", type="date", nullable=false)
     */
    private $tourFromDate;
	
    /**
     * @var \Date
     *
     * @ORM\Column(name="tour_to_date", type="date", nullable=false)
     */
    private $tourToDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="min_days", type="boolean", nullable=false)
     */
    private $minDays = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="max_days", type="boolean", nullable=false)
     */
    private $maxDays = '0';
	
    /**
     * @var string
     *
     * @ORM\Column(name="terms_conditions", type="text", length=65535, nullable=true)
     */
    private $termsConditions;

    /**
     * @var string
     *
     * @ORM\Column(name="optional_terms_conditions", type="text", length=65535, nullable=true)
     */
    private $optionalTermsConditions;

    /**
     * @var string
     *
     * @ORM\Column(name="deal_includes", type="text", length=65535, nullable=true)
     */
    private $dealIncludes;

    /**
     * @var string
     *
     * @ORM\Column(name="deal_not_include", type="text", length=65535, nullable=true)
     */
    private $dealNotInclude;

    /**
     * @var integer
     *
     * @ORM\Column(name="dealer_id", type="integer", nullable=false)
     */
    private $dealerId='0';

    /**
     * @var string
     *
     * @ORM\Column(name="tour_route", type="string", length=255, nullable=false)
     */
    private $tourRoute;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=false)
     */
    private $category;

    /**
     * @var boolean
     *
     * @ORM\Column(name="order_display", type="integer", nullable=false)
     */
    private $orderDisplay = '1';
    
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
     * Set name
     *
     * @param string $name
     *
     * @return Deal
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
     * Set createTs
     *
     * @param \DateTime $createTs
     *
     * @return Deal
     */
    public function setCreateTs($createTs)
    {
        $this->createTs = $createTs;

        return $this;
    }

    /**
     * Get createTs
     *
     * @return \DateTime
     */
    public function getCreateTs()
    {
        return $this->createTs;
    }

    /**
     * Set currencyId
     *
     * @param integer $currencyId
     *
     * @return Deal
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    /**
     * Get currencyId
     *
     * @return integer
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Deal
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set highlights
     *
     * @param string $highlights
     *
     * @return Deal
     */
    public function setHighlights($highlights)
    {
        $this->highlights = $highlights;

        return $this;
    }

    /**
     * Get highlights
     *
     * @return string
     */
    public function getHighlights()
    {
        return $this->highlights;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Deal
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set summaryTitle
     *
     * @param string $summaryTitle
     *
     * @return Deal
     */
    public function setSummaryTitle($summaryTitle)
    {
        $this->summaryTitle = $summaryTitle;

        return $this;
    }

    /**
     * Get summaryTitle
     *
     * @return string
     */
    public function getSummaryTitle()
    {
        return $this->summaryTitle;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Deal
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set hotelSummaryTitle
     *
     * @param string $hotelSummaryTitle
     *
     * @return Deal
     */
    public function setHotelSummaryTitle($hotelSummaryTitle)
    {
        $this->hotelSummaryTitle = $hotelSummaryTitle;

        return $this;
    }

    /**
     * Get hotelSummaryTitle
     *
     * @return string
     */
    public function getHotelSummaryTitle()
    {
        return $this->hotelSummaryTitle;
    }

    /**
     * Set hotelSummary
     *
     * @param string $hotelSummary
     *
     * @return Deal
     */
    public function setHotelSummary($hotelSummary)
    {
        $this->hotelSummary = $hotelSummary;

        return $this;
    }

    /**
     * Get hotelSummary
     *
     * @return string
     */
    public function getHotelSummary()
    {
        return $this->hotelSummary;
    }

    /**
     * Set tourFromDate
     *
     * @param string $tourFromDate
     *
     * @return Deal
     */
    public function setTourFromDate($tourFromDate)
    {
        $this->tourFromDate = $tourFromDate;

        return $this;
    }

    /**
     * Get tourFromDate
     *
     * @return string
     */
    public function getTourFromDate()
    {
        return $this->tourFromDate;
    }

    /**
     * Set tourToDate
     *
     * @param string $tourToDate
     *
     * @return Deal
     */
    public function setTourToDate($tourToDate)
    {
        $this->tourToDate = $tourToDate;

        return $this;
    }

    /**
     * Get tourToDate
     *
     * @return string
     */
    public function getTourToDate()
    {
        return $this->tourToDate;
    }

    /**
     * Set minDays
     *
     * @param string $minDays
     *
     * @return Deal
     */
    public function setMinDays($minDays)
    {
        $this->minDays = $minDays;

        return $this;
    }

    /**
     * Get minDays
     *
     * @return string
     */
    public function getMinDays()
    {
        return $this->minDays;
    }

    /**
     * Set maxDays
     *
     * @param string $maxDays
     *
     * @return Deal
     */
    public function setMaxDays($maxDays)
    {
        $this->maxDays = $maxDays;

        return $this;
    }

    /**
     * Get maxDays
     *
     * @return string
     */
    public function getMaxDays()
    {
        return $this->maxDays;
    }

    /**
     * Set termsConditions
     *
     * @param string $termsConditions
     *
     * @return Deal
     */
    public function setTermsConditions($termsConditions)
    {
        $this->termsConditions = $termsConditions;

        return $this;
    }

    /**
     * Get termsConditions
     *
     * @return string
     */
    public function getTermsConditions()
    {
        return $this->termsConditions;
    }

    /**
     * Set optionalTermsConditions
     *
     * @param string $optionalTermsConditions
     *
     * @return Deal
     */
    public function setOptionalTermsConditions($optionalTermsConditions)
    {
        $this->optionalTermsConditions = $optionalTermsConditions;

        return $this;
    }

    /**
     * Get optionalTermsConditions
     *
     * @return string
     */
    public function getOptionalTermsConditions()
    {
        return $this->optionalTermsConditions;
    }

    /**
     * Set dealIncludes
     *
     * @param string $dealIncludes
     *
     * @return Deal
     */
    public function setDealIncludes($dealIncludes)
    {
        $this->dealIncludes = $dealIncludes;

        return $this;
    }

    /**
     * Get dealIncludes
     *
     * @return string
     */
    public function getDealIncludes()
    {
        return $this->dealIncludes;
    }

    /**
     * Set dealNotInclude
     *
     * @param string $dealNotInclude
     *
     * @return Deal
     */
    public function setDealNotInclude($dealNotInclude)
    {
        $this->dealNotInclude = $dealNotInclude;

        return $this;
    }

    /**
     * Get dealNotInclude
     *
     * @return string
     */
    public function getDealNotInclude()
    {
        return $this->dealNotInclude;
    }

    /**
     * Set dealerId
     *
     * @param integer $dealerId
     *
     * @return Deal
     */
    public function setDealerId($dealerId)
    {
        $this->dealerId = $dealerId;

        return $this;
    }

    /**
     * Get dealerId
     *
     * @return integer
     */
    public function getDealerId()
    {
        return $this->dealerId;
    }

    /**
     * Set tourRoute
     *
     * @param string $tourRoute
     *
     * @return Deal
     */
    public function setTourRoute($tourRoute)
    {
        $this->tourRoute = $tourRoute;

        return $this;
    }

    /**
     * Get tourRoute
     *
     * @return string
     */
    public function getTourRoute()
    {
        return $this->tourRoute;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Deal
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set orderDisplay
     *
     * @param integer $orderDisplay
     *
     * @return DealImage
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
}
