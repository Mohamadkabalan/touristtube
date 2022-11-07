<?php

namespace HotelBundle\Model;

/**
 * Description of HotelBooking
 *
 */
class HotelBooking
{
    private $pageSource   = '';
    private $hotelReservationId;
    private $controlNumber;
    private $bookingPassword;
    private $userId;
    private $orderer;
    private $bookingDate;
    private $fromDate;
    private $toDate;
    private $hotelId;
    private $hotelCode    = '';
    private $source;
    private $hotelName;
    private $reference;
    private $transactionSourceId;
    private $roomCriteria = [];

    /**
     * The rooms to reserved.
     * @var array
     */
    private $rooms;
    private $reservationMode;
    private $cancelable;
    private $doubleRooms;
    private $singleRooms;
    private $activeRoomsCount;
    private $hotelGrandTotal;
    private $hotelCurrency;
    private $customerGrandTotal;
    private $customerCurrency;
    private $amountFbc;
    private $amountSbc;
    private $accountCurrencyAmount;
    private $reservationStatus;
    private $remarks;

    public function __construct()
    {
        $this->orderer = new HotelBookingOrderer();
    }

    /**
     * Get pageSource.
     * @return String
     */
    public function getPageSource()
    {
        return $this->pageSource;
    }

    /**
     * Set pageSource.
     * @param String $pageSource
     */
    public function setPageSource($pageSource)
    {
        $this->pageSource = $pageSource;
    }

    /**
     * Get hotelReservationId.
     * @return Integer
     */
    public function getHotelReservationId()
    {
        return $this->hotelReservationId;
    }

    /**
     * Set hotelReservationId.
     * @param Integer $hotelReservationId
     */
    public function setHotelReservationId($hotelReservationId)
    {
        $this->hotelReservationId = $hotelReservationId;
    }

    /**
     * Get controlNumber.
     * @return String
     */
    public function getControlNumber()
    {
        return $this->controlNumber;
    }

    /**
     * Set controlNumber.
     * @param String $controlNumber
     */
    public function setControlNumber($controlNumber)
    {
        $this->controlNumber = $controlNumber;
    }

    /**
     * Get bookingPassword.
     * @return String
     */
    public function getBookingPassword()
    {
        return $this->bookingPassword;
    }

    /**
     * Set bookingPassword.
     * @param String $bookingPassword
     */
    public function setBookingPassword($bookingPassword)
    {
        $this->bookingPassword = $bookingPassword;
    }

    /**
     * Get userId.
     * @return type
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId.
     * @param type $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get orderer information.
     * @return \HotelBundle\Model\HotelBookingOrderer.
     */
    public function getOrderer()
    {
        return $this->orderer;
    }

    /**
     * Set orderer information.
     * @param \HotelBundle\Model\HotelBookingOrderer $orderer
     * @return $this
     */
    public function setOrderer(HotelBookingOrderer $orderer)
    {
        $this->orderer = $orderer;
        return $this;
    }

    /**
     * Get bookingDate.
     * @return String
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set bookingDate.
     * @param String $bookingDate
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;
    }

    /**
     * Get fromDate.
     * @return String
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set fromDate.
     * @param String $fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * Get toDate.
     * @return type
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set toDate.
     * @param type $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * Get hotelId.
     * @return Integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set hotelId.
     * @param Integer $hotelId
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    /**
     * Get hotelCode.
     * @return String
     */
    public function getHotelCode()
    {
        return $this->hotelCode;
    }

    /**
     * Set hotelCode.
     * @param String $hotelCode
     */
    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;
    }

    /**
     * Get source.
     * @return AmadeusHotelSource
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set source.
     * @param AmadeusHotelSource $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Get hotelName.
     * @return String
     */
    public function getHotelName()
    {
        return $this->hotelName;
    }

    /**
     * Set hotelName.
     * @param String $hotelName
     */
    public function setHotelName($hotelName)
    {
        $this->hotelName = $hotelName;
    }

    /**
     * Get reference.
     * @return String
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set reference
     * @param String $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Get transactionSourceId.
     * @return Integer
     */
    public function getTransactionSourceId()
    {
        return $this->transactionSourceId;
    }

    /**
     * Set transactionSourceId.
     * @param Integer $transactionSourceId
     */
    public function setTransactionSourceId($transactionSourceId)
    {
        $this->transactionSourceId = $transactionSourceId;
    }

    /**
     * Get roomCriteria.
     * @return array
     */
    public function getRoomCriteria()
    {
        return $this->roomCriteria;
    }

    /**
     * Set roomCriteria
     * @param array $roomCriteria
     * @return $this
     */
    public function setRoomCriteria(array $roomCriteria)
    {
        $this->roomCriteria = $roomCriteria;
        return $this;
    }

    /**
     * Get rooms.
     * @return array
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set rooms
     * @param array $rooms
     */
    public function setRooms(array $rooms)
    {
        $this->rooms = $rooms;
    }

    /**
     * Get reservationMode.
     * @return String
     */
    public function getReservationMode()
    {
        return $this->reservationMode;
    }

    /**
     * Set reservationMode.
     * @param String $reservationMode
     */
    public function setReservationMode($reservationMode)
    {
        $this->reservationMode = $reservationMode;
    }

    /**
     * Get cancelable.
     * @return Boolean
     */
    public function isCancelable()
    {
        return $this->cancelable;
    }

    /**
     * Set cancelable.
     * @param Boolean $cancelable
     */
    public function setCancelable($cancelable)
    {
        $this->cancelable = $cancelable;
    }

    /**
     * Get double rooms count.
     * @return Integer
     */
    public function getDoubleRooms()
    {
        return $this->doubleRooms;
    }

    /**
     * Set double rooms count.
     * @param Integer $doubleRooms
     */
    public function setDoubleRooms($doubleRooms)
    {
        $this->doubleRooms = $doubleRooms;
    }

    /**
     * Get single rooms count.
     * @return Integer
     */
    public function getSingleRooms()
    {
        return $this->singleRooms;
    }

    /**
     * Set single rooms count.
     * @param Integer $singleRooms
     */
    public function setSingleRooms($singleRooms)
    {
        $this->singleRooms = $singleRooms;
    }

    /**
     * Get active rooms count.
     * @return Integer
     */
    public function getActiveRoomsCount()
    {
        return $this->activeRoomsCount;
    }

    /**
     * Set active rooms count.
     * @param Integer $activeRoomsCount
     */
    public function setActiveRoomsCount($activeRoomsCount)
    {
        $this->activeRoomsCount = $activeRoomsCount;
    }

    /**
     * Get hotelGrandTotal.
     * @return Integer
     */
    public function getHotelGrandTotal()
    {
        return $this->hotelGrandTotal;
    }

    /**
     * Set hotelGrandTotal.
     * @param Integer $hotelGrandTotal
     */
    public function setHotelGrandTotal($hotelGrandTotal)
    {
        $this->hotelGrandTotal = $hotelGrandTotal;
    }

    /**
     * Get hotelCurrency.
     * @return String
     */
    public function getHotelCurrency()
    {
        return $this->hotelCurrency;
    }

    /**
     * Set hotelCurrency.
     * @param String $hotelCurrency
     */
    public function setHotelCurrency($hotelCurrency)
    {
        $this->hotelCurrency = $hotelCurrency;
    }

    /**
     * Get customerGrandTotal.
     * @return Double
     */
    public function getCustomerGrandTotal()
    {
        return $this->customerGrandTotal;
    }

    /**
     * Set customerGrandTotal.
     * @param Double $customerGrandTotal
     */
    public function setCustomerGrandTotal($customerGrandTotal)
    {
        $this->customerGrandTotal = $customerGrandTotal;
    }

    /**
     * Get customerCurrency
     * @return String
     */
    public function getCustomerCurrency()
    {
        return $this->customerCurrency;
    }

    /**
     * Set customerCurrency.
     * @param String $customerCurrency
     */
    public function setCustomerCurrency($customerCurrency)
    {
        $this->customerCurrency = $customerCurrency;
    }

    /**
     * Get amountFbc.
     * @return Double
     */
    public function getAmountFbc()
    {
        return $this->amountFbc;
    }

    /**
     * Set amountFbc.
     * @param Double $amountFbc
     */
    public function setAmountFbc($amountFbc)
    {
        $this->amountFbc = $amountFbc;
    }

    /**
     * Get amountSbc.
     * @return Double
     */
    public function getAmountSbc()
    {
        return $this->amountSbc;
    }

    /**
     * Set amountSbc.
     * @param Double $amountSbc
     */
    public function setAmountSbc($amountSbc)
    {
        $this->amountSbc = $amountSbc;
    }

    /**
     * Get accountCurrencyAmount.
     * @return Double
     */
    public function getAccountCurrencyAmount()
    {
        return $this->accountCurrencyAmount;
    }

    /**
     * Set accountCurrencyAmount.
     * @param Double $accountCurrencyAmount
     */
    public function setAccountCurrencyAmount($accountCurrencyAmount)
    {
        $this->accountCurrencyAmount = $accountCurrencyAmount;
    }

    /**
     * Get reservationStatus.
     * @return String
     */
    public function getReservationStatus()
    {
        return $this->reservationStatus;
    }

    /**
     * Set reservationStatus.
     * @param String $reservationStatus
     */
    public function setReservationStatus($reservationStatus)
    {
        $this->reservationStatus = $reservationStatus;
    }

    /**
     * Get remark(s)
     * @return Mixed    Array of remarks or a remark.
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set remark(s)
     * @param Mixed $remarks    Array of remarks or a remark.
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $toreturn[$key] = $value->toArray();
            } else {
                $toreturn[$key] = $value;
            }
        }
        return $toreturn;
    }
}
