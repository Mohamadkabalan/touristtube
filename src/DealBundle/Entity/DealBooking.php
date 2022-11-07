<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealDetail
 *
 * @ORM\Table(name="deal_booking")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 * @ORM\Entity
 */
class DealBooking
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
     * @var integer
     *
     * @ORM\Column(name="deal_details_id", type="integer", nullable=false)
     */
    private $dealDetailsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="api_id", type="integer", nullable=false)
     */
    private $apiId;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_uuid", type="string", length=36, nullable=false)
     */
    private $paymentUuid;

    /**
     * @var string
     *
     * @ORM\Column(name="booking_reference", type="string", length=100, nullable=true)
     */
    private $bookingReference;

    /**
     * @var string
     *
     * @ORM\Column(name="booking_status", type="string", length=45, nullable=true)
     */
    private $bookingStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="booking_date", type="datetime", nullable=false)
     */
    private $bookingDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_of_adults", type="integer", nullable=true)
     */
    private $numOfAdults;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_of_children", type="integer", nullable=true)
     */
    private $numOfChildren;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_of_infants", type="integer", nullable=true)
     */
    private $numOfInfants;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="dialing_code", type="integer", nullable=true)
     */
    private $dialingCode;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=45, nullable=false)
     */
    private $mobilePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=45, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=45, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=25, nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="booking_notes", type="text", nullable=true)
     */
    private $bookingNotes;

    /**
     * @var integer
     *
     * @ORM\Column(name="activity_price_id", type="integer", nullable=false)
     */
    private $activityPriceId;

    /**
     * @var decimal
     *
     * @ORM\Column(name="total_price", type="decimal", nullable=true)
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="deal_name", type="string", length=255, nullable=true)
     */
    private $dealName;

    /**
     * @var string
     *
     * @ORM\Column(name="deal_description", type="text", nullable=true)
     */
    private $dealDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(name="voucher_information", type="text", nullable=true)
     */
    private $voucherInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="cancellation_policy", type="text", nullable=true)
     */
    private $cancellationPolicy;

    /**
     * @var string
     *
     * @ORM\Column(name="deal_code",type="string", length=45, nullable=true)
     */
    private $dealCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=true)
     */
    private $countryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_city_id", type="integer", nullable=true)
     */
    private $dealCityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_type_id", type="integer", nullable=true)
     */
    private $dealTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="currency",type="string", length=100, nullable=true)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="duration",type="string", length=100, nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="starting_place",type="string", length=100, nullable=true)
     */
    private $startingPlace;

    /**
     * @var text
     *
     * @ORM\Column(name="deal_highlights",type="text", nullable=true)
     */
    private $dealHighlights;

    /**
     * @var string
     *
     * @ORM\Column(name="departure_time",type="string", length=100, nullable=true)
     */
    private $departureTime;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount_fbc", type="decimal", nullable=false)
     */
    private $amountFbc;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount_sbc", type="decimal", nullable=false)
     */
    private $amountSbc;

    /**
     * @var decimal
     *
     * @ORM\Column(name="account_currency_amount", type="decimal", nullable=true)
     */
    private $accountCurrencyAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_source_id", type="integer", nullable=false)
     */
    private $transactionSourceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_booking_quote_id", type="integer", nullable=true)
     */
    private $dealBookingQuoteId;

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

    function getDealDetailsId()
    {
        return $this->dealDetailsId;
    }

    function getApiId()
    {
        return $this->apiId;
    }

    function getPaymentUuid()
    {
        return $this->paymentUuid;
    }

    function getBookingReference()
    {
        return $this->bookingReference;
    }

    function getBookingStatus()
    {
        return $this->bookingStatus;
    }

    function getBookingDate()
    {
        return $this->bookingDate;
    }

    function getNumOfAdults()
    {
        return $this->numOfAdults;
    }

    function getNumOfChildren()
    {
        return $this->numOfChildren;
    }

    function getNumOfInfants()
    {
        return $this->numOfInfants;
    }

    function getUserId()
    {
        return $this->userId;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getDialingCode()
    {
        return $this->dialingCode;
    }

    function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getFirstName()
    {
        return $this->firstName;
    }

    function getLastName()
    {
        return $this->lastName;
    }

    function getAddress()
    {
        return $this->address;
    }

    function getPostalCode()
    {
        return $this->postalCode;
    }

    function getBookingNotes()
    {
        return $this->bookingNotes;
    }

    function getActivityPriceId()
    {
        return $this->activityPriceId;
    }

    function getTotalPrice()
    {
        return $this->totalPrice;
    }

    function getDealName()
    {
        return $this->dealName;
    }

    function getDealDescription()
    {
        return $this->dealDescription;
    }

    function getStartTime()
    {
        return $this->startTime;
    }

    function getEndTime()
    {
        return $this->endTime;
    }

    function getVoucherInformation()
    {
        return $this->voucherInformation;
    }

    function getCancellationPolicy()
    {
        return $this->cancellationPolicy;
    }

    function getDealCode()
    {
        return $this->dealCode;
    }

    function getCountryId()
    {
        return $this->countryId;
    }

    function getDealCityId()
    {
        return $this->dealCityId;
    }

    function getDealTypeId()
    {
        return $this->dealTypeId;
    }

    function getCurrency()
    {
        return $this->currency;
    }

    function getDuration()
    {
        return $this->duration;
    }

    function getStartingPlace()
    {
        return $this->startingPlace;
    }

    function getDealHighlights()
    {
        return $this->dealHighlights;
    }

    function getDepartureTime()
    {
        return $this->departureTime;
    }

    function getTransactionSourceId()
    {
        return $this->transactionSourceId;
    }

    function getDealBookingQuoteId()
    {
        return $this->dealBookingQuoteId;
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

    function setDealDetailsId($dealDetailsId)
    {
        $this->dealDetailsId = $dealDetailsId;
    }

    function setApiId($apiId)
    {
        $this->apiId = $apiId;
    }

    function setPaymentUuid($paymentUuid)
    {
        $this->paymentUuid = $paymentUuid;
    }

    function setBookingReference($bookingReference)
    {
        $this->bookingReference = $bookingReference;
    }

    function setBookingStatus($bookingStatus)
    {
        $this->bookingStatus = $bookingStatus;
    }

    function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;
    }

    function setNumOfAdults($numOfAdults)
    {
        $this->numOfAdults = $numOfAdults;
    }

    function setNumOfChildren($numOfChildren)
    {
        $this->numOfChildren = $numOfChildren;
    }

    function setNumOfInfants($numOfInfants)
    {
        $this->numOfInfants = $numOfInfants;
    }

    function setUserId($userId)
    {
        $this->userId = $userId;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setDialingCode($dialingCode)
    {
        $this->dialingCode = $dialingCode;
    }

    function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    function setAddress($address)
    {
        $this->address = $address;
    }

    function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    function setBookingNotes($bookingNotes)
    {
        $this->bookingNotes = $bookingNotes;
    }

    function setActivityPriceId($activityPriceId)
    {
        $this->activityPriceId = $activityPriceId;
    }

    function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    function setDealName($dealName)
    {
        $this->dealName = $dealName;
    }

    function setDealDescription($dealDescription)
    {
        $this->dealDescription = $dealDescription;
    }

    function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    function setVoucherInformation($voucherInformation)
    {
        $this->voucherInformation = $voucherInformation;
    }

    function setCancellationPolicy($cancellationPolicy)
    {
        $this->cancellationPolicy = $cancellationPolicy;
    }

    function setDealCode($dealCode)
    {
        $this->dealCode = $dealCode;
    }

    function setCountryId($countryId)
    {
        $this->countryId = $countryId;
    }

    function setDealCityId($dealCityId)
    {
        $this->dealCityId = $dealCityId;
    }

    function setDealTypeId($dealTypeId)
    {
        $this->dealTypeId = $dealTypeId;
    }

    function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    function setDuration($duration)
    {
        $this->duration = $duration;
    }

    function setStartingPlace($startingPlace)
    {
        $this->startingPlace = $startingPlace;
    }

    function setDealHighlights($dealHighlights)
    {
        $this->dealHighlights = $dealHighlights;
    }

    function setDepartureTime($departureTime)
    {
        $this->departureTime = $departureTime;
    }

    function setDealBookingQuoteId($dealBookingQuoteId)
    {
        $this->dealBookingQuoteId = $dealBookingQuoteId;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    function getAmountFbc()
    {
        return $this->amountFbc;
    }

    function getAmountSbc()
    {
        return $this->amountSbc;
    }

    function getAccountCurrencyAmount()
    {
        return $this->accountCurrencyAmount;
    }

    function setAccountCurrencyAmount($accountCurrencyAmount)
    {
        $this->accountCurrencyAmount = $accountCurrencyAmount;
    }

    function setAmountFbc($amountFbc)
    {
        $this->amountFbc = $amountFbc;
    }

    function setAmountSbc($amountSbc)
    {
        $this->amountSbc = $amountSbc;
    }

    function setTransactionSourceId($transactionSourceId)
    {
        $this->transactionSourceId = $transactionSourceId;
    }

    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}