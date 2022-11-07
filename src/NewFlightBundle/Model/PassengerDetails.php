<?php

namespace NewFlightBundle\Model;

class PassengerDetails extends flightVO
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var date
     */
    private $dateOfBirth;

    /**
     * @var string(adult/INF/CNN)
     */
    private $type;

    /**
     * @var string
     */
    private $passportNumber;

    /**
     * @var date
     */
    private $passportExpiry;

    /**
     * 
     */
    private $passportNationalityCountry;

    /**
     * 
     */
    private $countryOfResidence;

    /**
     * 
     */
    private $passportIssuingCountry;

    /**
     * 
     */
    private $cityModel;

    /**
     * 
     */
    private $idNo;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->passportNationalityCountry = new Country();
        $this->countryOfResidence         = new Country();
        $this->passportIssuingCountry     = new Country();
        $this->cityModel                  = new City();
    }

    /**
     * Get Country Model object
     * @return object
     */
    public function getPassportNationalityCountry()
    {
        return $this->passportNationalityCountry;
    }

    /**
     * Get Country Model object
     * @return object
     */
    public function getCountryOfResidence()
    {
        return $this->countryOfResidence;
    }

    /**
     * Get Country Model object
     * @return object
     */
    public function getPassportIssuingCountry()
    {
        return $this->passportIssuingCountry;
    }

    /**
     * Get City Model object
     * @return object
     */
    public function getCityModel()
    {
        return $this->cityModel;
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
     * Get firstName
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get middleName
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
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
     * Get gender
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Get dateOfBirth
     * @return date
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get passportnumber
     * @return string
     */
    public function getPassportNumber()
    {
        return $this->passportNumber;
    }

    /**
     * Get passportExpiry
     * @return date
     */
    public function getPassportExpiry()
    {
        return $this->passportExpiry;
    }

    /**
     * Get idNo
     * @return integer
     */
    public function getIdNo()
    {
        return $this->idNo;
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
     * Set firstName
     * @param String $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Set middleName
     * @param String $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * Set surname
     * @param String $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Set gender
     * @param String $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Set dateOfBirth
     * @param date $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Set type
     * @param String $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set passportnumber
     * @param String $passportnumber
     */
    public function setPassportNumber($passportNumber)
    {
        $this->passportNumber = $passportNumber;
    }

    /**
     * Set passportExpiry
     * @param date $passportExpiry
     */
    public function setPassportExpiry($passportExpiry)
    {
        $this->passportExpiry = $passportExpiry;
    }

    /**
     * Set idNo
     * @param integer $idNo
     */
    public function setIdNo($idNo)
    {
        $this->idNo = $idNo;
    }
}