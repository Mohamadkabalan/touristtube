<?php

namespace HotelBundle\Model;

/**
 * Description of HotelBookingOrderer
 */
class HotelBookingOrderer
{
    private $title;
    private $firstName;
    private $lastName;
    private $country;
    private $dialingCode;
    private $phone;
    private $email;

    /**
     * Get title
     * @return String
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     * @param String $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get firstName
     * @return String
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     * @param String $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get lastName
     * @return String
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     * @param String $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get country
     * @return String
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     * @param String $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get dialingCode
     * @return String
     */
    public function getDialingCode()
    {
        return $this->dialingCode;
    }

    /**
     * Set dialingCode
     * @param String $dialingCode
     * @return $this
     */
    public function setDialingCode($dialingCode)
    {
        $this->dialingCode = $dialingCode;
    }

    /**
     * Get phone
     * @return String
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     * @param String $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get email
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     * @param String $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
