<?php

namespace DealBundle\Model;

/**
 *  DealSC is the class that will hold the search criteria of deals section
 * as an object class with the attributes for search set
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealSC
{
    private $isCorpo          = 0;
    private $attractions      = array();
    private $apiSupplierId    = array();
    private $langCode         = '';
    private $dynamicSorting   = false;
    private $limit            = 0;
    private $priority         = 0;
    private $category         = 'all';
    private $searchAll        = false;
    private $dealNameSearch   = '';
    private $minPrice         = '';
    private $maxPrice         = '';
    private $allTypes         = false;
    private $offSet           = '';
    private $hasSearch        = false;
    private $maxResults       = 0;
    private $selectedCurrency = 'USD';
    private $categoryIds      = array();

    /**
     * 
     */
    private $commonSC;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->commonSC = new DealsCommonSC();
    }

    /**
     * Get Common search criteria object
     * @return DealsCommonSC object
     */
    function getCommonSC()
    {
        return $this->commonSC;
    }

    /**
     * Get isCorpo
     * @return Integer
     */
    function getIsCorpo()
    {
        return $this->isCorpo;
    }

    /**
     * Get attractions
     * @return Array
     */
    function getAttractions()
    {
        return $this->attractions;
    }

    /**
     * Get apiSupplierId
     * @return Array
     */
    function getApiSupplierId()
    {
        return $this->apiSupplierId;
    }

    /**
     * Get langCode
     * @return String
     */
    function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * Get dynamicSorting
     * @return Boolean
     */
    function getDynamicSorting()
    {
        return $this->dynamicSorting;
    }

    /**
     * Get limit
     * @return Integer
     */
    function getLimit()
    {
        return $this->limit;
    }

    /**
     * Get priority
     * @return Integer
     */
    function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get category
     * @return String
     */
    function getCategory()
    {
        return $this->category;
    }

    /**
     * Get searchAll
     * @return Boolean
     */
    function getSearchAll()
    {
        return $this->searchAll;
    }

    /**
     * Get dealNameSearch
     * @return String
     */
    function getDealNameSearch()
    {
        return $this->dealNameSearch;
    }

    /**
     * Get minPrice
     * @return String
     */
    function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Get maxPrice
     * @return String
     */
    function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Get allTypes
     * @return Boolean
     */
    function getAllTypes()
    {
        return $this->allTypes;
    }

    /**
     * Get offSet
     * @return String
     */
    function getOffSet()
    {
        return $this->offSet;
    }

    /**
     * Get hasSearch
     * @return Boolean
     */
    function getHasSearch()
    {
        return $this->hasSearch;
    }

    /**
     * Get maxResults
     * @return Integer
     */
    function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * Get selectedCurrency
     * @return String
     */
    function getSelectedCurrency()
    {
        return $this->selectedCurrency;
    }

    function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * Set isCorpo
     * @param Integer $isCorpo
     */
    function setIsCorpo($isCorpo)
    {
        $this->isCorpo = $isCorpo;
    }

    /**
     * Set attractions
     * @param Array $attractions
     */
    function setAttractions(array $attractions)
    {
        $this->attractions = $attractions;
    }

    /**
     * Set apiSupplierId
     * @param Array $apiSupplierId
     */
    function setApiSupplierId(array $apiSupplierId)
    {
        $this->apiSupplierId = $apiSupplierId;
    }

    /**
     * Set langCode
     * @param String $langCode
     */
    function setLangCode($langCode)
    {
        $this->langCode = $langCode;
    }

    /**
     * Set dynamicSorting
     * @param Boolean $dynamicSorting
     */
    function setDynamicSorting($dynamicSorting)
    {
        $this->dynamicSorting = $dynamicSorting;
    }

    /**
     * Set limit
     * @param Integer $limit
     */
    function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * Set priority
     * @param Integer $priority
     */
    function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * Set category
     * @param String $category
     */
    function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Set searchAll
     * @param Boolean $searchAll
     */
    function setSearchAll($searchAll)
    {
        $this->searchAll = $searchAll;
    }

    /**
     * Set dealNameSearch
     * @param String $dealNameSearch
     */
    function setDealNameSearch($dealNameSearch)
    {
        $this->dealNameSearch = $dealNameSearch;
    }

    /**
     * Set minPrice
     * @param String $minPrice
     */
    function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
    }

    /**
     * Set maxPrice
     * @param String $maxPrice
     */
    function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * Set allTypes
     * @param Boolean $allTypes
     */
    function setAllTypes($allTypes)
    {
        $this->allTypes = $allTypes;
    }

    /**
     * Set offSet
     * @param String $offSet
     */
    function setOffSet($offSet)
    {
        $this->offSet = $offSet;
    }

    /**
     * Set hasSearch
     * @param Boolean $hasSearch
     */
    function setHasSearch($hasSearch)
    {
        $this->hasSearch = $hasSearch;
    }

    /**
     * Set maxResults
     * @param Integer $maxResults
     */
    function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }

    /**
     * Set selectedCurrency
     * @param String $selectedCurrency
     */
    function setSelectedCurrency($selectedCurrency)
    {
        $this->selectedCurrency = $selectedCurrency;
    }

    function setCategoryIds(array $categoryIds)
    {
        $this->categoryIds = $categoryIds;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}