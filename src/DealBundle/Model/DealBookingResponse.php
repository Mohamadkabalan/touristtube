<?php

namespace DealBundle\Model;

/**
 * DealBookingResponse is used to save booking objects specifically for tours.
 * Separated this object from main class DealResponse in order for it not to be confusing since we also have another set of attributes for booking a transport.
 * We have an attribute $dealBooking in main class DealReponse for this.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealBookingResponse
{
    /**
     * @var date
     */
    private $bookingDate = '';

    /**
     * @var string
     */
    private $bookingReference = '';

    /**
     * @var string
     */
    private $bookingStatus = '';

    /**
     * @var string
     */
    private $bookingEmail = '';

    /**
     * @var string
     */
    private $priceId = '';

    /**
     * @var string
     */
    private $bookingVeltraId = '';

    /**
     * @var string
     */
    private $firstName = '';

    /**
     * @var string
     */
    private $lastName = '';

    /**
     * @var date
     */
    private $activityDate = '';

    /**
     * @var string
     */
    private $activitySupplier = '';

    /**
     * @var string
     */
    private $bookingVoucherInformation = '';

    /**
     * @var string
     */
    private $selectedCurrency = '';

    /**
     * @var integer
     */
    private $bookingId = '';

    /**
     * @var string
     */
    private $dbFields = '';

    /**
     * @var string
     */
    private $leftJoinTransfers = false;

    /**
     * @var string
     */
    private $uuid = '';

    /**
     * @var string
     */
    private $address = '';

    /**
     * @var string
     */
    private $numOfAdults = '';

    /**
     * @var decimal
     */
    private $totalPrice = '';

    /**
     * @var integer
     */
    private $userId = '';

    /**
     * @var integer
     */
    private $transactionSourceId = '';

    /**
     * @var integer
     */
    private $bookingQuoteId = '';

    /**
     * @var string
     */
    private $bookingNotes = '';

    /**
     * @var string
     */
    private $cancellationPolicy = '';

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $dialingCode = '';

    /**
     * @var string
     */
    private $mobile = '';

    /**
     * @var string
     */
    private $postalCode = '';

    /**
     * @var integer
     */
    private $numOfChildren = '';

    /**
     * @var integer
     */
    private $numOfInfants = '';

    /**
     * @var string
     */
    private $duration = '';

    /**
     * @var string
     */
    private $startingPlace = '';

    /**
     * @var time
     */
    private $startTime = '';

    /**
     * @var time
     */
    private $endTime = '';

    /**
     * @var time
     */
    private $bookingTime = '';

    /**
     * @var decimal
     */
    private $amountFBC = '';

    /**
     * @var decimal
     */
    private $amountSBC = '';

    /**
     * @var decimal
     */
    private $accountCurrencyAmount = '';

    /**
     * @var string
     */
    private $onAccountCCType = '';

    /**
     * @var string
     */
    private $userAgent = '';

    /**
     * @var string
     */
    private $customerIP = '';

    /**
     * @var string
     */
    private $customerfullName = '';

    /**
     * @var string
     */
    private $langCode = '';

    /**
     * @var string
     */
    private $preferredCurrency = '';

    /**
     * @var integer
     */
    private $age = '';

    /**
     * @var string
     */
    private $paymentRequired = '';

    /**
     * @var string
     */
    private $paymentType = '';

    /**
     * @var integer
     */
    private $numOfpassengers = '';

    /**
     * @var string
     */
    private $typeOfTransfer = '';

    /**
     * @var string
     */
    private $goingTo = '';

    /**
     * @var string
     */
    private $serviceType = '';

    /**
     * @var string
     */
    private $carModel = '';

    /**
     * @var string
     */
    private $destinationAddress = '';

    /**
     * @var string
     */
    private $accountId = '';

    /**
     * @var string
     */
    private $transactionUserId = '';

    /**
     * @var string
     */
    private $requestServicesDetailsId = '';

    /**
     * @var string
     */
    private $isAvailable = '';

    /**
     * @var string
     */
    private $reservationId = '';

    /**
     * 
     */
    private $airport;

    /**
     * 
     */
    private $arrivalDeparture;

    /**
     * 
     */
    private $commonSC;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->commonSC         = new DealsCommonSC();
        $this->arrivalDeparture = new DealArrivalDeparture();
        $this->typeOfTransfer   = new DealTransferType();
        $this->airport          = new DealAirport();
    }

    /**
     * Get Common search criteria object
     * @return DealsCommonSC object
     */
    function getCommonSC()
    {
        return $this->commonSC;
    }

    /**
     * Get typeOfTransfer
     * @return DealTransferType object
     */
    function getTypeOfTransfer()
    {
        return $this->typeOfTransfer;
    }

    /**
     * Get airport
     * @return DealAirport object
     */
    function getAirport()
    {
        return $this->airport;
    }

    /**
     * Get DealArrivalDeparture
     * @return DealArrivalDeparture object
     */
    function getArrivalDeparture()
    {
        return $this->arrivalDeparture;
    }

    /**
     * Get bookingDate
     * @return String
     */
    function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Get bookingReference
     * @return String
     */
    function getBookingReference()
    {
        return $this->bookingReference;
    }

    /**
     * Get bookingStatus
     * @return String
     */
    function getBookingStatus()
    {
        return $this->bookingStatus;
    }

    /**
     * Get bookingEmail
     * @return String
     */
    function getBookingEmail()
    {
        return $this->bookingEmail;
    }

    /**
     * Get priceId
     * @return integer
     */
    function getPriceId()
    {
        return $this->priceId;
    }

    /**
     * Get bookingVeltraId
     * @return String
     */
    function getBookingVeltraId()
    {
        return $this->bookingVeltraId;
    }

    /**
     * Get firstName
     * @return String
     */
    function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get lastName
     * @return String
     */
    function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get activityDate
     * @return String
     */
    function getActivityDate()
    {
        return $this->activityDate;
    }

    /**
     * Get activitySupplier
     * @return String
     */
    function getActivitySupplier()
    {
        return $this->activitySupplier;
    }

    /**
     * Get bookingVoucherInformation
     * @return String
     */
    function getBookingVoucherInformation()
    {
        return $this->bookingVoucherInformation;
    }

    /**
     * Get selectedCurrency
     * @return String
     */
    function getSelectedCurrency()
    {
        return $this->selectedCurrency;
    }

    /**
     * Get bookingId
     * @return integer
     */
    function getBookingId()
    {
        return $this->bookingId;
    }

    /**
     * Get dbFields
     * @return String
     */
    function getDbFields()
    {
        return $this->dbFields;
    }

    /**
     * Get leftJoinTransfers
     * @return String
     */
    function getLeftJoinTransfers()
    {
        return $this->leftJoinTransfers;
    }

    /**
     * Get uuid
     * @return String
     */
    function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Get address
     * @return String
     */
    function getAddress()
    {
        return $this->address;
    }

    /**
     * Get numOfAdults
     * @return integer
     */
    function getNumOfAdults()
    {
        return $this->numOfAdults;
    }

    /**
     * Get totalPrice
     * @return decimal
     */
    function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Get userId
     * @return integer
     */
    function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get transactionSourceId
     * @return integer
     */
    function getTransactionSourceId()
    {
        return $this->transactionSourceId;
    }

    /**
     * Get bookingQuoteId
     * @return integer
     */
    function getBookingQuoteId()
    {
        return $this->bookingQuoteId;
    }

    /**
     * Get bookingNotes
     * @return String
     */
    function getBookingNotes()
    {
        return $this->bookingNotes;
    }

    /**
     * Get cancellationPolicy
     * @return String
     */
    function getCancellationPolicy()
    {
        return $this->cancellationPolicy;
    }

    /**
     * Get title
     * @return String
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * Get dialingCode
     * @return String
     */
    function getDialingCode()
    {
        return $this->dialingCode;
    }

    /**
     * Get mobile
     * @return String
     */
    function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Get postalCode
     * @return String
     */
    function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Get numOfChildren
     * @return String
     */
    function getNumOfChildren()
    {
        return $this->numOfChildren;
    }

    /**
     * Get numOfInfants
     * @return String
     */
    function getNumOfInfants()
    {
        return $this->numOfInfants;
    }

    /**
     * Get duration
     * @return String
     */
    function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get startingPlace
     * @return String
     */
    function getStartingPlace()
    {
        return $this->startingPlace;
    }

    /**
     * Get startTime
     * @return String
     */
    function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Get endTime
     * @return String
     */
    function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Get bookingTime
     * @return String
     */
    function getBookingTime()
    {
        return $this->bookingTime;
    }

    /**
     * Get amountFBC
     * @return String
     */
    function getAmountFBC()
    {
        return $this->amountFBC;
    }

    /**
     * Get amountSBC
     * @return String
     */
    function getAmountSBC()
    {
        return $this->amountSBC;
    }

    /**
     * Get accountCurrencyAmount
     * @return String
     */
    function getAccountCurrencyAmount()
    {
        return $this->accountCurrencyAmount;
    }

    /**
     * Get onAccountCCType
     * @return String
     */
    function getOnAccountCCType()
    {
        return $this->onAccountCCType;
    }

    /**
     * Get userAgent
     * @return String
     */
    function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Get customerIP
     * @return String
     */
    function getCustomerIP()
    {
        return $this->customerIP;
    }

    /**
     * Get customerfullName
     * @return String
     */
    function getCustomerfullName()
    {
        return $this->customerfullName;
    }

    /**
     * Get langCode
     * @return String
     */
    function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * Get preferredCurrency
     * @return String
     */
    function getPreferredCurrency()
    {
        return $this->preferredCurrency;
    }

    /**
     * Get age
     * @return String
     */
    function getAge()
    {
        return $this->age;
    }

    /**
     * Get paymentRequired
     * @return String
     */
    function getPaymentRequired()
    {
        return $this->paymentRequired;
    }

    /**
     * Get paymentType
     * @return String
     */
    function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Get numOfpassengers
     * @return String
     */
    function getNumOfpassengers()
    {
        return $this->numOfpassengers;
    }

    /**
     * Get goingTo
     * @return String
     */
    function getGoingTo()
    {
        return $this->goingTo;
    }

    /**
     * Get serviceType
     * @return String
     */
    function getServiceType()
    {
        return $this->serviceType;
    }

    /**
     * Get carModel
     * @return String
     */
    function getCarModel()
    {
        return $this->carModel;
    }

    /**
     * Get accountId
     * @return String
     */
    function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Get transactionUserId
     * @return String
     */
    function getTransactionUserId()
    {
        return $this->transactionUserId;
    }

    /**
     * Get requestServicesDetailsId
     * @return String
     */
    function getRequestServicesDetailsId()
    {
        return $this->requestServicesDetailsId;
    }

    /**
     * Get isAvailable
     * @return String
     */
    function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * Get reservationId
     * @return String
     */
    function getReservationId()
    {
        return $this->reservationId;
    }

    /**
     * Get destinationAddress
     * @return String
     */
    function getDestinationAddress()
    {
        return $this->destinationAddress;
    }

    /**
     * Set bookingReference
     * @param String $bookingReference
     */
    function setBookingReference($bookingReference)
    {
        $this->bookingReference = $bookingReference;
    }

    /**
     * Set bookingStatus
     * @param String $bookingStatus
     */
    function setBookingStatus($bookingStatus)
    {
        $this->bookingStatus = $bookingStatus;
    }

    /**
     * Set bookingEmail
     * @param String $bookingEmail
     */
    function setBookingEmail($bookingEmail)
    {
        $this->bookingEmail = $bookingEmail;
    }

    /**
     * Set priceId
     * @param String $priceId
     */
    function setPriceId($priceId)
    {
        $this->priceId = $priceId;
    }

    /**
     * Set bookingVeltraId
     * @param String $bookingVeltraId
     */
    function setBookingVeltraId($bookingVeltraId)
    {
        $this->bookingVeltraId = $bookingVeltraId;
    }

    /**
     * Set firstName
     * @param String $firstName
     */
    function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Set lastName
     * @param String $lastName
     */
    function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Set activityDate
     * @param String $activityDate
     */
    function setActivityDate($activityDate)
    {
        $this->activityDate = $activityDate;
    }

    /**
     * Set activitySupplier
     * @param String $activitySupplier
     */
    function setActivitySupplier($activitySupplier)
    {
        $this->activitySupplier = $activitySupplier;
    }

    /**
     * Set bookingVoucherInformation
     * @param String $bookingVoucherInformation
     */
    function setBookingVoucherInformation($bookingVoucherInformation)
    {
        $this->bookingVoucherInformation = $bookingVoucherInformation;
    }

    /**
     * Set selectedCurrency
     * @param String $selectedCurrency
     */
    function setSelectedCurrency($selectedCurrency)
    {
        $this->selectedCurrency = $selectedCurrency;
    }

    /**
     * Set bookingId
     * @param String $bookingId
     */
    function setBookingId($bookingId)
    {
        $this->bookingId = $bookingId;
    }

    /**
     * Set dbFields
     * @param String $dbFields
     */
    function setDbFields($dbFields)
    {
        $this->dbFields = $dbFields;
    }

    /**
     * Set leftJoinTransfers
     * @param String $leftJoinTransfers
     */
    function setLeftJoinTransfers($leftJoinTransfers)
    {
        $this->leftJoinTransfers = $leftJoinTransfers;
    }

    /**
     * Set uuid
     * @param String $uuid
     */
    function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Set address
     * @param String $address
     */
    function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Set numOfAdults
     * @param String $numOfAdults
     */
    function setNumOfAdults($numOfAdults)
    {
        $this->numOfAdults = $numOfAdults;
    }

    /**
     * Set totalPrice
     * @param String $totalPrice
     */
    function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * Set userId
     * @param integer $userId
     */
    function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Set transactionSourceId
     * @param integer $transactionSourceId
     */
    function setTransactionSourceId($transactionSourceId)
    {
        $this->transactionSourceId = $transactionSourceId;
    }

    /**
     * Set bookingQuoteId
     * @param integer $bookingQuoteId
     */
    function setBookingQuoteId($bookingQuoteId)
    {
        $this->bookingQuoteId = $bookingQuoteId;
    }

    /**
     * Set bookingNotes
     * @param String $bookingNotes
     */
    function setBookingNotes($bookingNotes)
    {
        $this->bookingNotes = $bookingNotes;
    }

    /**
     * Set cancellationPolicy
     * @param String $cancellationPolicy
     */
    function setCancellationPolicy($cancellationPolicy)
    {
        $this->cancellationPolicy = $cancellationPolicy;
    }

    /**
     * Set title
     * @param String $title
     */
    function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set dialingCode
     * @param String $dialingCode
     */
    function setDialingCode($dialingCode)
    {
        $this->dialingCode = $dialingCode;
    }

    /**
     * Set mobile
     * @param String $mobile
     */
    function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Set postalCode
     * @param String $postalCode
     */
    function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * Set numOfChildren
     * @param String $numOfChildren
     */
    function setNumOfChildren($numOfChildren)
    {
        $this->numOfChildren = $numOfChildren;
    }

    /**
     * Set numOfInfants
     * @param String $numOfInfants
     */
    function setNumOfInfants($numOfInfants)
    {
        $this->numOfInfants = $numOfInfants;
    }

    /**
     * Set duration
     * @param String $duration
     */
    function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Set startingPlace
     * @param String $startingPlace
     */
    function setStartingPlace($startingPlace)
    {
        $this->startingPlace = $startingPlace;
    }

    /**
     * Set startTime
     * @param String $startTime
     */
    function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * Set setEndTime
     * @param String $setEndTime
     */
    function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * Set bookingTime
     * @param String $bookingTime
     */
    function setBookingTime($bookingTime)
    {
        $this->bookingTime = $bookingTime;
    }

    /**
     * Set amountFBC
     * @param String $amountFBC
     */
    function setAmountFBC($amountFBC)
    {
        $this->amountFBC = $amountFBC;
    }

    /**
     * Set amountSBC
     * @param String $amountSBC
     */
    function setAmountSBC($amountSBC)
    {
        $this->amountSBC = $amountSBC;
    }

    /**
     * Set accountCurrencyAmount
     * @param String $accountCurrencyAmount
     */
    function setAccountCurrencyAmount($accountCurrencyAmount)
    {
        $this->accountCurrencyAmount = $accountCurrencyAmount;
    }

    /**
     * Set onAccountCCType
     * @param String $onAccountCCType
     */
    function setOnAccountCCType($onAccountCCType)
    {
        $this->onAccountCCType = $onAccountCCType;
    }

    /**
     * Set userAgent
     * @param String $userAgent
     */
    function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Set customerIP
     * @param String $customerIP
     */
    function setCustomerIP($customerIP)
    {
        $this->customerIP = $customerIP;
    }

    /**
     * Set customerfullName
     * @param String $customerfullName
     */
    function setCustomerfullName($customerfullName)
    {
        $this->customerfullName = $customerfullName;
    }

    /**
     * Set langCode
     * @param String $langCode
     */
    function setLangCode($langCode)
    {
        $this->langCode = $langCode;
    }

    /**
     * Set preferredCurrency
     * @param String $preferredCurrency
     */
    function setPreferredCurrency($preferredCurrency)
    {
        $this->preferredCurrency = $preferredCurrency;
    }

    /**
     * Set age
     * @param String $age
     */
    function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * Set paymentRequired
     * @param String $paymentRequired
     */
    function setPaymentRequired($paymentRequired)
    {
        $this->paymentRequired = $paymentRequired;
    }

    /**
     * Set paymentType
     * @param String $paymentType
     */
    function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * Set numOfpassengers
     * @param String $numOfpassengers
     */
    function setNumOfpassengers($numOfpassengers)
    {
        $this->numOfpassengers = $numOfpassengers;
    }

    /**
     * Set goingTo
     * @param String $goingTo
     */
    function setGoingTo($goingTo)
    {
        $this->goingTo = $goingTo;
    }

    /**
     * Set serviceType
     * @param String $serviceType
     */
    function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
    }

    /**
     * Set carModel
     * @param String $carModel
     */
    function setCarModel($carModel)
    {
        $this->carModel = $carModel;
    }

    /**
     * Set accountId
     * @param String $accountId
     */
    function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * Set transactionUserId
     * @param String $transactionUserId
     */
    function setTransactionUserId($transactionUserId)
    {
        $this->transactionUserId = $transactionUserId;
    }

    /**
     * Set requestServicesDetailsId
     * @param String $requestServicesDetailsId
     */
    function setRequestServicesDetailsId($requestServicesDetailsId)
    {
        $this->requestServicesDetailsId = $requestServicesDetailsId;
    }

    /**
     * Set isAvailable
     * @param String $isAvailable
     */
    function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;
    }

    /**
     * Set reservationId
     * @param String $reservationId
     */
    function setReservationId($reservationId)
    {
        $this->reservationId = $reservationId;
    }

    /**
     * Set destinationAddress
     * @param String $destinationAddress
     */
    function setDestinationAddress($destinationAddress)
    {
        $this->destinationAddress = $destinationAddress;
    }

    /**
     * Set bookingDate
     * @param String $bookingDate
     */
    function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}