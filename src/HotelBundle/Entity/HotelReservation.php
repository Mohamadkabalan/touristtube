<?php

namespace HotelBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * HotelReservation
 *
 * @ORM\Table(name="hotel_reservation")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HotelRepository")
 */
class HotelReservation
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
     * @ORM\ManyToOne(targetEntity="HotelBundle\Entity\AmadeusHotelSource", inversedBy="reservations")
     * @ORM\JoinColumn(name="hotel_source_id", referencedColumnName="id")
     */
    private $source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=4, nullable=true)
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "First Name cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Last Name cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=3, nullable=false)
     */
    private $country;

    /**
     * @var integer
     *
     * @ORM\Column(name="dialing_code", type="integer", nullable=true)
     */
    private $dialingCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Mobile number cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=30, nullable=false)
     */
    private $mobile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="from_date", type="datetime", nullable=true)
     */
    private $fromDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="to_date", type="datetime", nullable=true)
     */
    private $toDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="double_rooms", type="integer", nullable=false)
     */
    private $doubleRooms = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="single_rooms", type="integer", nullable=false)
     */
    private $singleRooms = '0';

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer")
     */
    private $hotelId;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="hotel_source_id", type="integer")
     */
    private $hotelSourceId;

    /**
     * @var decimal
     *
     * @ORM\Column(name="hotel_grand_total", type="decimal", nullable=false)
     */
    private $hotelGrandTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_currency", type="string", length=3, nullable=false)
     */
    private $hotelCurrency;

    /**
     * @var decimal
     *
     * @ORM\Column(name="customer_grand_total", type="decimal", nullable=false)
     */
    private $customerGrandTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_currency", type="string", length=3, nullable=false)
     */
    private $customerCurrency;

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
     *
     * @var string
     *
     * @ORM\Column(name="reservation_process_key", type="string", nullable=true)
     */
    private $reservationProcessKey;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reservation_process_password", type="string", nullable=true)
     */
    private $reservationProcessPassword;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="control_number", type="string", nullable=true)
     */
    private $controlNumber;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reservation_status", type="string", options={"comment":"Options: 'Confirmed', 'Modified', 'Canceled'"}, nullable=false)
     */
    private $reservationStatus;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reference", type="string", nullable=false)
     */
    private $reference;

    /**
     * The payment UUID
     * @ORM\Column(name="payment_uuid", type="string", length=36, nullable=true)
     */
    private $paymentUUID;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="details", type="string", nullable=true)
     */
    private $details;

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_source_id", type="integer", nullable=false)
     */
    private $transactionSourceId;

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
     * Get source
     *
     * @return AmadeusHotelSource $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set source
     *
     * @param AmadeusHotelSource $source
     */
    public function setSource(AmadeusHotelSource $source)
    {
        $this->source = $source;
    }

    /**
     * Set creation date
     *
     * @param datetime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * Get creation date
     *
     * @return datetime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set user ID
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get user ID
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set first name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set last name
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set dialing code
     *
     * @param string $dialingCode
     */
    public function setDialingCode($dialingCode)
    {
        $this->dialingCode = $dialingCode;
    }

    /**
     * Get dialing code
     *
     * @return string
     */
    public function getDialingCode()
    {
        return $this->dialingCode;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set fromDate
     *
     * @param datetime $fromDate
     */
    public function setFromDate(\DateTime $fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * Get fromDate
     *
     * @return datetime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param datetime $toDate
     */
    public function setToDate(\DateTime $toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * Get toDate
     *
     * @return datetime
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set doubleRooms
     *
     * @param integer $doubleRooms
     */
    public function setDoubleRooms($doubleRooms)
    {
        $this->doubleRooms = $doubleRooms;
    }

    /**
     * Get doubleRooms
     *
     * @return integer
     */
    public function getDoubleRooms()
    {
        return $this->doubleRooms;
    }

    /**
     * Set singleRooms
     *
     * @param integer $singleRooms
     */
    public function setSingleRooms($singleRooms)
    {
        $this->singleRooms = $singleRooms;
    }

    /**
     * Get singleRooms
     *
     * @return integer
     */
    public function getSingleRooms()
    {
        return $this->singleRooms;
    }

    /**
     * Set hotel id
     *
     * @param integer $hotelId
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    /**
     * Get hotel id
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set hotel source id
     *
     * @param integer $hotelSourceId
     */
    public function setHotelSourceId($hotelSourceId)
    {
        $this->hotelSourceId = $hotelSourceId;
    }

    /**
     * Get hotel source id
     *
     * @return integer
     */
    public function getHotelSourceId()
    {
        return $this->hotelSourceId;
    }

    /**
     * Get hotelGrandTotal
     *
     * @return decimal
     */
    public function getHotelGrandTotal()
    {
        return $this->hotelGrandTotal;
    }

    /**
     * Set hotelGrandTotal
     *
     * @param decimal $hotelGrandTotal
     */
    public function setHotelGrandTotal($hotelGrandTotal)
    {
        $this->hotelGrandTotal = $hotelGrandTotal;
    }

    /**
     * Get hotelCurrency
     *
     * @return string
     */
    public function getHotelCurrency()
    {
        return $this->hotelCurrency;
    }

    /**
     * Set hotelCurrency
     *
     * @param string $hotelCurrency
     */
    public function setHotelCurrency($hotelCurrency)
    {
        $this->hotelCurrency = $hotelCurrency;
    }

    /**
     * Get customerGrandTotal
     *
     * @return decimal
     */
    public function getCustomerGrandTotal()
    {
        return $this->customerGrandTotal;
    }

    /**
     * Set customerGrandTotal
     *
     * @param decimal $customerGrandTotal
     */
    public function setCustomerGrandTotal($customerGrandTotal)
    {
        $this->customerGrandTotal = $customerGrandTotal;
    }

    /**
     * Get customerCurrency
     *
     * @return string
     */
    public function getCustomerCurrency()
    {
        return $this->customerCurrency;
    }

    /**
     * Set customerCurrency
     *
     * @param string $customerCurrency
     */
    public function setCustomerCurrency($customerCurrency)
    {
        $this->customerCurrency = $customerCurrency;
    }

    /**
     * Get amountFbc
     *
     * @return decimal
     */
    function getAmountFbc()
    {
        return $this->amountFbc;
    }

    /**
     * Set amountFbc
     *
     * @param decimal $amountFbc
     */
    function setAmountFbc($amountFbc)
    {
        $this->amountFbc = $amountFbc;
    }

    /**
     * Get amountSbc
     *
     * @return decimal
     */
    function getAmountSbc()
    {
        return $this->amountSbc;
    }

    /**
     * Set amountSbc
     *
     * @param decimal $amountSbc
     */
    function setAmountSbc($amountSbc)
    {
        $this->amountSbc = $amountSbc;
    }

    /**
     * Get accountCurrencyAmount
     *
     * @return decimal
     */
    function getAccountCurrencyAmount()
    {
        return $this->accountCurrencyAmount;
    }

    /**
     * Set accountCurrencyAmount
     *
     * @param decimal $accountCurrencyAmount
     */
    function setAccountCurrencyAmount($accountCurrencyAmount)
    {
        $this->accountCurrencyAmount = $accountCurrencyAmount;
    }

    /**
     * Set Reservation Process Key
     *
     * @param string $reservationProcessKey
     */
    public function setReservationProcessKey($reservationProcessKey)
    {
        $this->reservationProcessKey = $reservationProcessKey;
    }

    /**
     * Get Reservation Process Key
     *
     * @return string
     */
    public function getReservationProcessKey()
    {
        return $this->reservationProcessKey;
    }

    /**
     * Set Reservation Process Password
     *
     * @param string $reservationProcessPassword
     */
    public function setReservationProcessPassword($reservationProcessPassword)
    {
        $this->reservationProcessPassword = $reservationProcessPassword;
    }

    /**
     * Get Reservation Process Password
     *
     * @return string
     */
    public function getReservationProcessPassword()
    {
        return $this->reservationProcessPassword;
    }

    /**
     * Set Control Number
     *
     * @param string $controlNumber
     */
    public function setControlNumber($controlNumber)
    {
        $this->controlNumber = $controlNumber;
    }

    /**
     * Get Control Number
     *
     * @return string
     */
    public function getControlNumber()
    {
        return $this->controlNumber;
    }

    /**
     * Set reservation status
     *
     * @param string $reservationStatus
     */
    public function setReservationStatus($reservationStatus)
    {
        $this->reservationStatus = $reservationStatus;
    }

    /**
     * Get reservation status
     *
     * @return string
     */
    public function getReservationStatus()
    {
        return $this->reservationStatus;
    }

    /**
     * Set Reference
     *
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Get Reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Get payment uuid
     *
     * @return string
     */
    public function getPaymentUUID()
    {
        return $this->paymentUUID;
    }

    /**
     * Set passenger name record ID
     *
     * @param string $paymentUUID
     */
    public function setPaymentUUID($paymentUUID)
    {
        $this->paymentUUID = $paymentUUID;
    }

    /**
     * Set Details
     *
     * @param string $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * Get Details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set transaction source ID
     *
     * @param integer $transactionSourceId
     */
    public function setTransactionSourceId($transactionSourceId)
    {
        $this->transactionSourceId = $transactionSourceId;
    }

    /**
     * Get transaction source ID
     *
     * @return integer
     */
    public function getTransactionSourceId()
    {
        return $this->transactionSourceId;
    }
}
