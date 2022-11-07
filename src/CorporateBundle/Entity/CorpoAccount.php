<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorpoAccount
 *
 * @ORM\Table(name="corpo_account")
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CorpoAccountRepository")
 */
class CorpoAccount
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=500, nullable=true)
     */
    private $address;

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
     * @var decimal
     *
     * @ORM\Column(name="credit_limit", type="decimal", nullable=false)
     */
    private $creditLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_users", type="integer", nullable=false)
     */
    private $numberOfUsers;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=3, nullable=false)
     */
    private $currencyCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_period", type="integer", nullable=false)
     */
    private $paymentPeriod;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=100, nullable=false)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="accounting_email", type="string", length=100, nullable=false)
     */
    private $accountingEmail;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_type_id", type="integer", nullable=false)
     */
    private $paymentTypeId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_account_currency_amount", type="boolean", nullable=false)
     */
    private $showAccountCurrencyAmount = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="preferred_currency", type="string", length=3, nullable=false)
     */
    private $preferredCurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_by", type="integer", nullable=true)
     */
    private $updatedBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_account_id", type="integer", nullable=false)
     */
    private $parentAccountId;

    /**
     * @var integer
     *
     * @ORM\Column(name="agency_id", type="integer", nullable=false)
     */
    private $agencyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_type_id", type="integer", nullable=false)
     */
    private $accountTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=100, nullable=true)
     */
    private $path;

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
     * Get name
     *
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get cityName
     *
     * @return string
     */
    function getCityName()
    {
        return $this->cityName;
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
     * Get address
     *
     * @return string
     */
    function getAddress()
    {
        return $this->address;
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
     * Get creditLimit
     *
     * @return decimal
     */
    function getCreditLimit()
    {
        return $this->creditLimit;
    }

    /**
     * Get numberOfUsers
     *
     * @return integer
     */
    function getNumberOfUsers()
    {
        return $this->numberOfUsers;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Get paymentPeriod
     *
     * @return integer
     */
    function getPaymentPeriod()
    {
        return $this->paymentPeriod;
    }

    /**
     * Get preferredCurrency
     *
     * @return string
     */
    function getPreferredCurrency()
    {
        return $this->preferredCurrency;
    }

    /**
     * Get website
     *
     * @return string
     */
    function getWebsite()
    {
        return $this->website;
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
     * Get accountingEmail
     *
     * @return string
     */
    function getAccountingEmail()
    {
        return $this->accountingEmail;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Get showAccountCurrencyAmount
     *
     * @return boolean
     */
    function getShowAccountCurrencyAmount()
    {
        return $this->showAccountCurrencyAmount;
    }

    /**
     * Get paymentTypeId
     *
     * @return integer
     */
    function getPaymentTypeId()
    {
        return $this->paymentTypeId;
    }

    /**
     * Get agencyId
     *
     * @return integer
     */
    function getAgencyId()
    {
        return $this->agencyId;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Get updatedBy
     *
     * @return integer
     */
    function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get accountTypeId
     *
     * @return integer
     */
    function getAccountTypeId()
    {
        return $this->accountTypeId;
    }

    /**
     * Get path
     *
     * @return string
     */
    function getPath()
    {
        return $this->path;
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
        $this->id = $id;

        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return name
     */
    function setName($name)
    {
        $this->name = $name;

        return $this->name;
    }

    /**
     * Set cityName
     *
     * @param string $cityName
     *
     * @return cityName
     */
    function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this->cityName;
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
        $this->cityId = $cityId;

        return $this->cityId;
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
     * Set creditLimit
     *
     * @param decimal $creditLimit
     *
     * @return creditLimit
     */
    function setCreditLimit($creditLimit)
    {
        $this->creditLimit = $creditLimit;

        return $this->creditLimit;
    }

    /**
     * Set numberOfUsers
     *
     * @param integer $numberOfUsers
     *
     * @return numberOfUsers
     */
    function setNumberOfUsers($numberOfUsers)
    {
        $this->numberOfUsers = $numberOfUsers;

        return $this->numberOfUsers;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     *
     * @return currencyCode
     */
    function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this->currencyCode;
    }

    /**
     * Set paymentPeriod
     *
     * @param integer $paymentPeriod
     *
     * @return paymentPeriod
     */
    function setPaymentPeriod($paymentPeriod)
    {
        $this->paymentPeriod = $paymentPeriod;

        return $this->paymentPeriod;
    }

    /**
     * Set preferredCurrency
     *
     * @param string $preferredCurrency
     *
     * @return preferredCurrency
     */
    function setPreferredCurrency($preferredCurrency)
    {
        $this->preferredCurrency = $preferredCurrency;

        return $this->preferredCurrency;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return website
     */
    function setWebsite($website)
    {
        $this->website = $website;

        return $this->website;
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
     * Set accountingEmail
     *
     * @param string $accountingEmail
     *
     * @return accountingEmail
     */
    function setAccountingEmail($accountingEmail)
    {
        $this->accountingEmail = $accountingEmail;

        return $this->accountingEmail;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this->isActive;
    }

    /**
     * Set showAccountCurrencyAmount
     *
     * @param boolean $showAccountCurrencyAmount
     *
     * @return showAccountCurrencyAmount
     */
    public function setShowAccountCurrencyAmount($showAccountCurrencyAmount)
    {
        $this->showAccountCurrencyAmount = $showAccountCurrencyAmount;

        return $this->showAccountCurrencyAmount;
    }

    /**
     * Set paymentTypeId
     *
     * @param integer $paymentTypeId
     *
     * @return paymentTypeId
     */
    function setPaymentTypeId($paymentTypeId)
    {
        $this->paymentTypeId = $paymentTypeId;

        return $this->paymentTypeId;
    }

    /**
     * Set agencyId
     *
     * @param integer $agencyId
     *
     * @return agencyId
     */
    function setAgencyId($agencyId)
    {
        $this->agencyId = $agencyId;

        return $this->agencyId;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return createdBy
     */
    function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param integer $updatedBy
     *
     * @return updatedBy
     */
    function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this->updatedBy;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return createdAt
     */
    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return updatedAt
     */
    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this->updatedAt;
    }

    function getParentAccountId()
    {
        return $this->parentAccountId;
    }

    function setParentAccountId($parentAccountId)
    {
        $this->parentAccountId = $parentAccountId;
    }

    /**
     * Set accountTypeId
     *
     * @param integer $accountTypeId
     *
     * @return accountTypeId
     */
    function setAccountTypeId($accountTypeId)
    {
        $this->accountTypeId = $accountTypeId;

        return $this->accountTypeId;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return path
     */
    function setPath($path)
    {
        $this->path = $path;

        return $this->path;
    }
}
