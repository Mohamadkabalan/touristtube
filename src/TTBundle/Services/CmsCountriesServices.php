<?php

namespace TTBundle\Services;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use TTBundle\Services\libraries\CombogridService;


class CmsCountriesServices
{

    /**
     * The __construct when we make a new instance of CountriesServices class.
     * 
     * @param Utils $utils
     * @param EntityManager $em
     */
    public function __construct(Utils $utils, EntityManager $em)
    {
        $this->container = $utils->container;
        $this->em        = $em;

        $this->countriesRepo = $this->em->getRepository('TTBundle:CmsCountries');
    }

    /**
     * This method retrieves the list of countries.
     * @return Array    List of countries.
     */
    public function getCountryList()
    {
        return $this->countriesRepo->getCountryList();
    }

    /**
     * This method returns the ISO3 country code for a certain country code.
     *
     * @param String $countryCode The country code.
     * @return String   The ISO3 country code.
     */
    public function getIso3CountryByCode($countryCode)
    {
        return $this->countriesRepo->getIso3CountryByCode($countryCode);
    }

    /**
     * This method returns the ISO3 country code for a certain IP.
     * 
     * @param String $ip    The IP address.
     * @return String   The ISO3 country code.
     */
    public function getIso3CountryByIp($ip)
    {
        return $this->countriesRepo->getIso3CountryByIp($ip);
    }

    /**
     * This method retrieves the list of country codes where dialing code is not equal to '0'.
     *
     * @return Array    List of mobile country codes.
     */
    public function getMobileCountryCodeList()
    {
        return $this->countriesRepo->getMobileCountryCodeList();
    }

    /**
     * This method returns the country name when given the 3-letter ISO code
     *
     * @param $countryCode The ISO3 country code
     * @return The country name
     */
    public function getNameByIso3Code($countryCode)
    {
        return $this->countriesRepo->getNameByIso3Code($countryCode);
    }

    /**
     * This method returns a list of all countries sorted by name
     *
     * @return An array that contains the id, the ISO-2 code and the name of the country
     */
    public function getCountries()
    {
        return $this->countriesRepo->getCountries();
    }

    /**
     * This method returns a list of dialing codes of all countries sorted by name
     *
     * @return An array that contains the id, code(ISO-2), code(ISO-3), name, dialing_code and flag_icon of all countries
     */
    public function getCountriesDialingCodes()
    {
        return $this->countriesRepo->getCountriesDialingCodes();
    }
    
    public function getCountryCombo(Request $request)
    {
        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        //
        $combogrid_cats_res = $this->em->getRepository('TTBundle:CmsCountries')->getCountryCombo($tt_search_critiria_obj);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);
        //
        return $res;
    }

    /*
    * @countryGetInfo function return countryGet Info
    */
    public function countryGetInfo( $code )
    {
        return $this->countriesRepo->countryGetInfo( $code );
    }

    /*
    * @countryGetList function return countryGet list
    */
    public function countryGetList()
    {
        return $this->countriesRepo->countryGetList();
    }
}
