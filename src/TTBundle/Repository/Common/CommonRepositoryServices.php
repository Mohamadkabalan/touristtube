<?php

namespace TTBundle\Repository\Common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

/**
 * Description of CommonRepositoryServices this is a service responsible to make queries to the database using entities, 
 * to prevent repetition of the queries in the services and controllers, and to make any update easier.  
 *
 * @author Mathewk
 */
class CommonRepositoryServices extends EntityRepository
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * cmsUsersInfo is a function tat get user information based on the user id or user email
     * @param type $userId or $userEmail and the method, method of find example: findOneById or findOneByYourEmail
     * 
     */
    public function cmsUsersInfo($userRef, $method)
    {

        if ($method == "OneById") {
            $userInfo = $this->em->getRepository('TTBundle:CmsUsers')->findOneById($userRef);
        } elseif ($method == "OneByYourEmail") {
            $userInfo = $this->em->getRepository('TTBundle:CmsUsers')->findOneByYouremail($userRef);
        }
        return $userInfo;
    }

    /**
     * cmsHotelCityInfo contains the query to get information from CmshotelCity table by ID
     * @param type $cityId
     * 
     */
    public function cmsHotelCityInfo($cityId)
    {

        $cmsCityInfo = $this->em->getRepository('HotelBundle:CmsHotelCity')->findOneByCityId($cityId);

        return $cmsCityInfo;
    }

    /**
     * this function is responsible to get the default image of an hotel,
     * @param type $sourceId
     * 
     */
    public function cmsHotelImageInfo($sourceId)
    {

        $cmsCityInfo = $this->em->getRepository('HotelBundle:CmsHotelImage')->getHotelImages($sourceId, 1, 'defaultPic');

        return $cmsCityInfo;
    }

    /**
     * cmsHotelImageInfo is the function where you fetch information from cmsCountries table
     * @param type countryOfResidence  or mobileCountryCode
     *
     */
    public function cmsCountriesInfo($sourceId)
    {

        $cmsCountry = $this->em->getRepository('TTBundle:CmsCountries')->find($sourceId);

        return $cmsCountry;
    }

    /**
     * this function returns the information about a currency from the CurrencyRate table based on the currency code
     * @param type $currency_code
     * 
     */
    public function currencyInfo($currency_code)
    {

        $currencyInfo = $this->getDoctrine()->getRepository('TTBundle:CurrencyRate')->findOneBycurrencyCode($currency_code);

        return $currencyInfo;
    }
}