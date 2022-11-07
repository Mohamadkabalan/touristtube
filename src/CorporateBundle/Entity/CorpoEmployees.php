<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoEmployees
 *
 * @ORM\Table(name="corpo_employees")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoEmployeesRepository")
 */
class CorpoEmployees
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
     * @ORM\Column(name="fname", type="string", length=100, nullable=false)
     */
    private $fname;

    /**
     * @var string
     *
     * @ORM\Column(name="lname", type="string", length=100, nullable=false)
     */
    private $lname;

    /**
     * @var string
     *
     * @ORM\Column(name="mname", type="string", length=100, nullable=false)
     */
    private $mname;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $accountId;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", length=100, nullable=true)
     */
    private $profilePicture;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=20, nullable=false)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="phone1", type="string", length=20, nullable=true)
     */
    private $phone1;

    /**
     * @var string
     *
     * @ORM\Column(name="phone2", type="string", length=20, nullable=true)
     */
    private $phone2;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=500, nullable=true)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", nullable=false, length=2)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="passport_number", type="string", length=20, nullable=false)
     */
    private $passportNumber;

    /**
     * @var datetime
     *
     * @ORM\Column(name="passport_expiry_date", type="datetime", length=20, nullable=false)
     */
    private $passportExpiryDate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="issue_date", type="datetime", length=20, nullable=false)
     */
    private $issueDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="department_id", type="integer", nullable=false)
     */
    private $departmentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param string $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

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
     * Get fname
     *
     * @return string
     */
    function getFname()
    {
        return $this->fname;
    }

    /**
     * Get lname
     *
     * @return string
     */
    function getLname()
    {
        return $this->lname;
    }

    /**
     * Get mname
     *
     * @return string
     */
    function getMname()
    {
        return $this->mname;
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
     * Get mobile
     *
     * @return string
     */
    function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Get phone1
     *
     * @return string
     */
    function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Get address
     *
     * @return string
     */
    function getAddress()
    {
        return $this->address;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Get email
     *
     * @return string
     */
    function getEmail()
    {
        return $this->email;
    }

    /**
     * Get passportNumber
     *
     * @return string
     */
    function gePassportNumber()
    {
        return $this->passportNumber;
    }

    /**
     * Get passportExpiryDate
     *
     * @return DateTime
     */
    function getPassportExpiryDate()
    {
        return $this->passportExpiryDate;
    }

    /**
     * Get issueDate
     *
     * @return DateTime
     */
    function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * Get departmentId
     *
     * @return integer
     */
    function getDepartmentId()
    {
        return $this->departmentId;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    function getUserId()
    {
        return $this->userId;
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
        $this->id = intval($id);

        return $this->id;
    }

    /**
     * Set fname
     *
     * @param string $fname
     *
     * @return fname
     */
    function setFname($fname)
    {
        $this->fname = $fname;

        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     *
     * @return lname
     */
    function setLname($lname)
    {
        $this->lname = $lname;

        return $this->lname;
    }

    /**
     * Set mname
     *
     * @param string $mname
     *
     * @return mname
     */
    function setMname($mname)
    {
        $this->mname = $mname;

        return $this->mname;
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
        $this->accountId = intval($accountId);

        return $this->accountId;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return mobile
     */
    function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this->mobile;
    }

    /**
     * Set phone1
     *
     * @param string $phone1
     *
     * @return phone1
     */
    function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this->phone1;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return phone2
     */
    function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this->phone2;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return address
     */
    function setAddress($address)
    {
        $this->address = $address;

        return $this->address;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return cityId
     */
    function setCityId($cityId)
    {
        $this->cityId = intval($cityId);

        return $this->cityId;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return countryCode
     */
    function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this->countryCode;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return email
     */
    function setEmail($email)
    {
        $this->email = $email;

        return $this->email;
    }

    /**
     * Set passportNumber
     *
     * @param string $passportNumber
     *
     * @return passportNumber
     */
    function setPassportNumber($passportNumber)
    {
        $this->passportNumber = $passportNumber;

        return $this->passportNumber;
    }

    /**
     * Set passportExpiryDate
     *
     * @param DateTime $passportExpiryDate
     *
     * @return passportExpiryDate
     */
    function setPassportExpiryDate($passportExpiryDate)
    {
        $this->passportExpiryDate = ($passportExpiryDate instanceof \DateTime) ? $passportExpiryDate : new \DateTime($passportExpiryDate);

        return $this->passportExpiryDate;
    }

    /**
     * Set issueDate
     *
     * @param DateTime $issueDate
     *
     * @return issueDate
     */
    function setIssueDate($issueDate)
    {
        $this->issueDate = ($issueDate instanceof \DateTime) ? $issueDate : new \DateTime($issueDate);

        return $this->issueDate;
    }

    /**
     * Set departmentId
     *
     * @param integer $departmentId
     *
     * @return departmentId
     */
    function setDepartmentId($departmentId)
    {
        $this->departmentId = intval($departmentId);

        return $this->departmentId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return userId
     */
    function setUserId($userId)
    {
        $this->userId = intval($userId);

        return $this->userId;
    }
}
