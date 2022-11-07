<?php

namespace NewFlightBundle\Model;

class PassengerNameRecord extends flightVO
{
    /**
     * 
     */
    private $passengerDetails;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $paymentUUID;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $PNR;

    /**
     * @var string
     */
    private $airlinePNR;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $mobileNumber;

    /**
     * @var string
     */
    private $mobileCountryCode;

    /**
     * @var string
     */
    private $countryOfResidence;

    /**
     * @var string
     */
    private $alternativeNumber;

    /**
     * @var integer
     */
    private $membershipId;

    /**
     * @var string
     */
    private $specialRequirement;

    /**
     * @var integer
     */
    private $numberInParty;

    /**
     * @var integer
     */
    private $ADTCount;

    /**
     * @var integer
     */
    private $INFCount;

    /**
     * @var integer
     */
    private $CNNCount;

    /**
     * @var integer (0: WEB, 1: Corporate)
     */
    private $isCorporateSite;

    /**
     * @var boolean true|false
     */
    private $isValidForPayment;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->passengerDetails = new PassengerDetails();
    }

    /**
     * Get PassengerDetails Model object
     * @return object
     */
    public function getPassengerDetails()
    {
        return $this->passengerDetails;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get paymentUUID
     * @return string
     */
    public function getPaymentUUID()
    {
        return $this->paymentUUID;
    }

    /**
     * Get firstName
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get surname
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Get userId
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get PNR
     * @return string
     */
    public function getPNR()
    {
        return $this->PNR;
    }

    /**
     * Get airlinePNR
     * @return string
     */
    public function getAirlinePNR()
    {
        return $this->airlinePNR;
    }

    /**
     * Get email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get mobileNumber
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * Get mobileCountryCode
     * @return string
     */
    public function getMobileCountryCode()
    {
        return $this->mobileCountryCode;
    }

    /**
     * Get countryOfResidence
     * @return string
     */
    public function getCountryOfResidence()
    {
        return $this->countryOfResidence;
    }

    /**
     * Get alternativeNumber
     * @return string
     */
    public function getAlternativeNumber()
    {
        return $this->alternativeNumber;
    }

    /**
     * Get membershipId
     * @return integer
     */
    public function getMembershipId()
    {
        return $this->membershipId;
    }

    /**
     * Get specialRequirement
     * @return string
     */
    public function getSpecialRequirement()
    {
        return $this->specialRequirement;
    }

    /**
     * Get numberInParty
     * @return integer
     */
    public function getNumberInParty()
    {
        return $this->numberInParty;
    }

    /**
     * Get ADTCount
     * @return integer
     */
    public function getADTCount()
    {
        return $this->ADTCount;
    }

    /**
     * Get INFCount
     * @return integer
     */
    public function getINFCount()
    {
        return $this->INFCount;
    }

    /**
     * Get CNNCount
     * @return integer
     */
    public function getCNNCount()
    {
        return $this->CNNCount;
    }

    /**
     * Set PassengerDetails Model object
     * @return object
     */
    public function setPassengerDetails($passengerDetails)
    {
        $this->passengerDetails = $passengerDetails;
    }

    /**
     * Set id
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set paymentUUID
     * @param string $paymentUUID
     */
    public function setPaymentUUID($paymentUUID)
    {
        $this->paymentUUID = $paymentUUID;
    }

    /**
     * Set firstName
     * @param integer $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Set surname
     * @param integer $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Set userId
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Set PNR
     * @param string $PNR
     */
    public function setPNR($PNR)
    {
        $this->PNR = $PNR;
    }

    /**
     * Set airlinePNR
     * @param string $airlinePNR
     */
    public function setAirlinePNR($airlinePNR)
    {
        $this->airlinePNR = $airlinePNR;
    }

    /**
     * Set email
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set mobileNumber
     * @param string $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * Set mobileCountryCode
     * @param string $mobileCountryCode
     */
    public function setMobileCountryCode($mobileCountryCode)
    {
        $this->mobileCountryCode = $mobileCountryCode;
    }

    /**
     * Set countryOfResidence
     * @param string $countryOfResidence
     */
    public function setCountryOfResidence($countryOfResidence)
    {
        $this->countryOfResidence = $countryOfResidence;
    }

    /**
     * Set alternativeNumber
     * @param string $alternativeNumber
     */
    public function setAlternativeNumber($alternativeNumber)
    {
        $this->alternativeNumber = $alternativeNumber;
    }

    /**
     * Set membershipId
     * @param integer $membershipId
     */
    public function setMembershipId($membershipId)
    {
        $this->membershipId = $membershipId;
    }

    /**
     * Set specialRequirement
     * @param string $specialRequirement
     */
    public function setSpecialRequirement($specialRequirement)
    {
        $this->specialRequirement = $specialRequirement;
    }

    /**
     * Set numberInParty
     * @param integer $numberInParty
     */
    public function setNumberInParty($numberInParty)
    {
        $this->numberInParty = $numberInParty;
    }

    /**
     * Set ADTCount
     * @param integer $ADTCount
     */
    public function setADTCount($ADTCount)
    {
        $this->ADTCount = $ADTCount;
    }

    /**
     * Set INFCount
     * @param integer $INFCount
     */
    public function setINFCount($INFCount)
    {
        $this->INFCount = $INFCount;
    }

    /**
     * Set CNNCount
     * @param integer $CNNCount
     */
    public function setCNNCount($CNNCount)
    {
        $this->CNNCount = $CNNCount;
    }

    /**
     * Get isCorporateSite
     * @return integer $isCorporateSite
     */
    public function getIsCorporateSite()
    {
        return $this->isCorporateSite;
    }

    /**
     * Set isCorporateSite
     * @param integer $isCorporateSite
     */
    public function setIsCorporateSite($isCorporateSite)
    {
        $this->isCorporateSite = $isCorporateSite;
    }

    /**
     * Get isValidForPayment
     * @return boolean true|false
     */
    public function getIsValidForPayment()
    {
        return $this->isValidForPayment;
    }

    /**
     * Set isValidForPayment
     * @param boolean true|false
     */
    public function setIsValidForPayment($isValidForPayment)
    {
        $this->isValidForPayment = $isValidForPayment;
    }
}
