<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use TTBundle\Model\City;
use TTBundle\Model\Currency;
use TTBundle\Model\User;

class Account {
    
    private $id;

    private $name;

    private $city;

    private $address;

    private $mobile;

    private $phone1;

    private $phone2;

    private $creditLimit;

    private $numberOfUsers;

    private $paymentPeriod;

    private $website;

    private $email;

    private $accountingEmail;

    private $currency;

    private $preferredCurrency;

    private $showAccountCurrencyAmount;

    private $paymentType;

    private $agency;

    private $parentId;

    private $isActive;

    private $accountType;

    private $createdBy;

    private $updatedBy;

    private $path;

    public function __construct() {
        $this->city = new City();
        $this->currency = new Currency();
        $this->preferredCurrency = new Currency();
        $this->paymentType = new PaymentType();
        $this->agency = new Agencies();
        $this->accountType = new AccountType();
        $this->createdBy = new User();
        $this->updatedBy = new User();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getPhone1()
    {
        return $this->phone1;
    }

    public function getPhone2()
    {
        return $this->phone2;
    }

    public function getCreditLimit()
    {
        return $this->creditLimit;
    }

    public function getNumberOfUsers()
    {
        return $this->numberOfUsers;
    }

    public function getPaymentPeriod()
    {
        return $this->paymentPeriod;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getAccountingEmail()
    {
        return $this->accountingEmail;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getPreferredCurrency()
    {
        return $this->preferredCurrency;
    }

    public function getShowAccountCurrencyAmount()
    {
        return $this->showAccountCurrencyAmount;
    }

    public function getPaymentType()
    {
        return $this->paymentType;
    }

    public function getAgency()
    {
        return $this->agency;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getAccountType()
    {
        return $this->accountType;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;
    }

    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
    }

    public function setCreditLimit($creditLimit)
    {
        $this->creditLimit = $creditLimit;
    }

    public function setNumberOfUsers($numberOfUsers)
    {
        $this->numberOfUsers = $numberOfUsers;
    }

    public function setPaymentPeriod($paymentPeriod)
    {
        $this->paymentPeriod = $paymentPeriod;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setAccountingEmail($accountingEmail)
    {
        $this->accountingEmail = $accountingEmail;
    }

    public function setShowAccountCurrencyAmount($showAccountCurrencyAmount)
    {
        $this->showAccountCurrencyAmount = $showAccountCurrencyAmount;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function arrayToObject($params)
    {
        $account = new Account();
        if (!empty($params)) {
            /**'Code' suffix appended by TTAutocomplete */
            $account->getCity()->setId(isset($params['cityId']) ? $params['cityId'] : '');
            $account->getCurrency()->setCode(isset($params['currencyCode']) ? $params['currencyCode'] : '');
            $account->getPreferredCurrency()->setCode(isset($params['preferredCurrencyCode']) ? $params['preferredCurrencyCode'] : '');

            if(isset($params['showAccountCurrencyAmount'])) {
                $params['showAccountCurrencyAmount'] = 1;
            } else {
                $params['showAccountCurrencyAmount'] = 0;
            }

            $account->getPaymentType()->setId(isset($params['paymentType']) ? $params['paymentType'] : '');
            $account->getAgency()->setId(isset($params['agencyCode']) ? $params['agencyCode'] : '');

            if (!empty($params['accountCode'])) {
                $params['parentId'] = $params['accountCode'];
            }

            if(isset($params['isActive'])) {
                $params['isActive'] = 1;
            } else {
                $params['isActive'] = 0;
            }
            $account->getAccountType()->setId(isset($params['accountType']) ? $params['accountType'] : '');
            $account->getCreatedBy()->setId(isset($params['userId']) ? $params['userId'] : '');
            $account->getUpdatedBy()->setId(isset($params['userId']) ? $params['userId'] : '');
        }
        return Utils::array_to_obj($params,$account);
    }
}