<?php

namespace FlightBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TTBundle\Entity\CmsCountries;
use PaymentBundle\Entity\Payment;

/**
 * PassengerNameRecord
 *
 * @ORM\Entity
 * @ORM\Table(name="passenger_name_record")
 */
class PassengerNameRecord
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
     * The payment UUID
     * @ORM\Column(name="payment_uuid", type="string", length=36, nullable=false)
     */
    private $paymentUUID;

    /**
     * The passenger name record
     * @ORM\Column(name="pnr", type="string", length=11, nullable=false)
     */
    private $pnr;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
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
     *      maxMessage = "Your surname cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=false)
     */
    private $surname;

    /**
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(name="country_of_residence", type="string", length=3, nullable=false)
     */
    private $countryOfResidence;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
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
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="country_dial_code", type="string", length=255, nullable=false)
     */
    private $mobileCountryCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Your mobile cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable=false)
     */
    private $mobile;

    /**
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Your mobile cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="alternative_number", type="string", length=255)
     */
    private $alternativeNumber;

    /**
     * @Assert\Length(
     *      max = 70,
     *      maxMessage = "Your special requirement cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="special_requirement", type="string")
     */
    private $specialRequirement;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     */
    private $status;

    /**
     * @var string
     */
//    private $pin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var Boolean
     *
     * @ORM\Column(name="is_corporate_site", type="boolean", nullable=false)
     */
    private $isCorporateSite;
    /**
     * @ORM\OneToMany(targetEntity="PassengerDetail", mappedBy="passengerNameRecord", cascade={"persist"})
     */
    private $passengerDetails;

    /**
     * @ORM\OneToMany(targetEntity="FlightDetail", mappedBy="passengerNameRecord", cascade={"persist"})
     */
    private $flightDetails;

    /**
     * @ORM\OneToOne(targetEntity="FlightInfo", mappedBy="passengerNameRecord", cascade={"persist"})
     */
    private $flightInfo;

    /**
     * @ORM\OneToOne(targetEntity="PaymentBundle\Entity\Payment", inversedBy="passengerNameRecord")
     * @ORM\JoinColumn(name="payment_uuid", referencedColumnName="uuid")
     */
    private $payment;

    /**
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Your membership_id cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="membership_id", type="string", length=100)
     */
    private $membershipId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->passengerDetails = new ArrayCollection();
        $this->flightDetails    = new ArrayCollection();
    }

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
     * Get passenger name record ID
     *
     * @return string
     */
    public function getPnr()
    {
        return $this->pnr;
    }

    /**
     * Set passenger name record ID
     *
     * @param string $pnr
     */
    public function setPnr($pnr)
    {
        $this->pnr = $pnr;
    }

    /**
     * Get payment UUID
     *
     * @return string
     */
    public function getPaymentUUID()
    {
        return $this->paymentUUID;
    }

    /**
     * Set payment UUID
     *
     * @param string $paymentUUID
     */
    public function setPaymentUUID($paymentUUID)
    {
        $this->paymentUUID = $paymentUUID;
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
     * Set user ID
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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
     * Set first name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get country of residence
     *
     * @return string
     */
    public function getCountryOfResidence()
    {
        return $this->countryOfResidence;
    }

    /**
     * Set country of residence
     *
     * @param CmsCountries $countryOfResidence
     */
    public function setCountryOfResidence(CmsCountries $countryOfResidence)
    {
        $this->countryOfResidence = $countryOfResidence->getCode();
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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobileCountryCode()
    {
        return $this->mobileCountryCode;
    }

    /**
     * Set mobile
     *
     * @param CmsCountries $mobileCountryCode
     */
    public function setMobileCountryCode(CmsCountries $mobileCountryCode)
    {
        $this->mobileCountryCode = $mobileCountryCode->getDialingCode();
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
     * Set mobile
     *
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        //$this->mobile = '+'.$this->mobileCountryCode.$mobile;
        $this->mobile =  $mobile;
    }

    /**
     * Get alternative number
     *
     * @return string
     */
    public function getAlternativeNumber()
    {
        return $this->alternativeNumber;
    }

    /**
     * Set alternative number
     *
     * @param string $alternativeNumber
     */
    public function setAlternativeNumber($alternativeNumber)
    {
        $this->alternativeNumber = $alternativeNumber;
    }

    /**
     * Get special requirement
     *
     * @return string
     */
    public function getSpecialRequirement()
    {
        return $this->specialRequirement;
    }

    /**
     * Set special requirement
     *
     * @param string $specialRequirement
     */
    public function setSpecialRequirement($specialRequirement)
    {
        $this->specialRequirement = $specialRequirement;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    /**
     * Get pin
     *
     * @return string
     */
//    public function getPin() {
//        return $this->pin;
//    }

    /**
     * Set pin
     *
     * @param string $pin
     */
//    public function setPin($pin) {
//        $this->pin = $pin;
//    }

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
     * Set creation date
     *
     * @param datetime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
    }

   /**
     * Set if the site now is corporate
     *
     * @param bool $isCorporateSite
     */
    public function setIsCorporateSite($isCorporateSite)
    {
        $this->isCorporateSite = $isCorporateSite;
    }

     /**
     * return if the site now is corporate
     *
     * @return bool $isCorporateSite
     */
    public function getIsCorporateSite()
    {
        return $this->isCorporateSite;
    }

    /**
     * Get passenger details
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPassengerDetails()
    {
        return $this->passengerDetails;
    }

    /**
     * Set passenger details
     *
     * @param PassengerDetail $passengerDetails
     */
    public function setPassengerDetails(PassengerDetail $passengerDetails)
    {
        $this->passengerDetails[] = $passengerDetails;
    }

    /**
     * Add passengerDetail
     *
     * @param PassengerDetail $passengerDetail
     *
     * @return PassengerNameRecord
     */
    public function addPassengerDetail(PassengerDetail $passengerDetail)
    {
        $passengerDetail->setPassengerNameRecord($this);
        $this->passengerDetails->add($passengerDetail);
    }

    /**
     * Remove passengerDetail
     *
     * @param PassengerDetail $passengerDetail
     */
    public function removePassengerDetail(PassengerDetail $passengerDetail)
    {
        $this->passengerDetails->removeElement($passengerDetail);
    }

    /**
     * Get flight details
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getFlightDetails()
    {
        return $this->flightDetails;
    }

    /**
     * Set flight details
     *
     * @param FlightDetail $flightDetails
     */
    public function setFlightDetails(FlightDetail $flightDetails)
    {
        $this->flightDetails[] = $flightDetails;
    }

    /**
     * Add flightDetail
     *
     * @param FlightDetail $flightDetail
     *
     * @return PassengerNameRecord
     */
    public function addFlightDetail(FlightDetail $flightDetail)
    {
        $flightDetail->setPassengerNameRecord($this);
        $this->flightDetails->add($flightDetail);
    }

    /**
     * Remove flightDetail
     *
     * @param FlightDetail $flightDetail
     */
    public function removeFlightDetail(PassengerDetail $flightDetail)
    {
        $this->flightDetails->removeElement($flightDetail);
    }

    /**
     * Get flight info
     *
     * @return FlightInfo $flightInfo
     */
    public function getFlightInfo()
    {
        return $this->flightInfo;
    }

    /**
     * Set flight info
     *
     * @param FlightInfo $flightInfo
     */
    public function setFlightInfo(FlightInfo $flightInfo)
    {
        $flightInfo->setPassengerNameRecord($this);
        $this->flightInfo = $flightInfo;
    }

    /**
     * Get payment
     *
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set payment
     *
     * @param Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get membershipId
     *
     * @return string
     */
    public function getMembershipId()
    {
        return $this->membershipId;
    }

    /**
     * Set membershipId
     *
     * @param string $membershipId
     */
    public function setMembershipId($membershipId)
    {
        $this->membershipId = $membershipId;
    }
}
