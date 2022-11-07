<?php

namespace DealBundle\Repository\Deal;

use Doctrine\ORM\EntityRepository;
use DealBundle\Entity\DealApiSupplier;
use DealBundle\Entity\DealSupplierTypeStatus;
use DealBundle\Entity\DealType;
use DealBundle\Entity\DealDetails;
use DealBundle\Entity\DealBooking;
use DealBundle\Entity\DealTransferBookingDetails;
use DealBundle\Entity\DealCity;
use DealBundle\Entity\DealBookingPassengers;
use DealBundle\Entity\DealBookingDetails;
use DealBundle\Entity\DealImage;
use DealBundle\Entity\DealBookingQuote;
use DealBundle\Entity\DealCancelPolicies;

//use TTBundle\Entity\Webgeocities;

class PackagesRepository extends EntityRepository
{

    /**
     * This method will retrieve all the deals by city from DB based on its category ( packages, tours, transfers, activities .. ) and city
     * These deals have the status active for the assigned API supplier with respect to the category or deal type
     * @param cityId this is the id of table webgeocities where we will join this to deal cities
     * @param category this is the deal type we wish to retrieve  ( packages, tours, transfers, activities .. )
     * @return doctrine object result of corresponding deals or false in case of no data
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getDealsByCity($cityId, $category = 'packages')
    {
        /*
         * SELECT *
          FROM  `deal_details` dd
          INNER JOIN deal_city dc ON dd.deal_city_id = dc.id
          INNER JOIN deal_api_supplier ds ON dd.deal_api_id = ds.id
          INNER JOIN deal_supplier_type_status dsts ON ds.id = dsts.deal_api_supplier_id
          INNER JOIN deal_type dt ON dsts.deal_type_id = dt.id
          WHERE dc.city_id = 922018
          AND dsts.is_active =1
          AND dt.category =  'packages'
          LIMIT 0 , 30
         */
        $isDefault = 1;
        $query     = $this->createQueryBuilder('dd')
            ->select('dd', 'di.path')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dd.dealTypeId=dt.id")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dc.cityId = :cityId')
            ->andWhere('dd.published =1')
            ->andWhere('dt.category =  :category')
            ->andWhere('dsts.isActive =1')
            ->setParameter(':cityId', $cityId)
            ->setParameter(':category', $category)
            ->setParameter(':isDefault', $isDefault)
            ->groupBy('dd.id')
            ->getQuery();

        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve all the deals from DB based on its category ( packages, tours, transfers, activities .. )
     * These deals have the status active for the assigned API supplier with respect to the category or deal type
     * @param category this is the deal type we wish to retrieve  ( packages, tours, transfers, activities .. )
     * @param $langCode - English by default
     * @return doctrine object result of corresponding deals or false in case of no data
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getDeals($category = 'all', $priority = 0, $langCode = 'en', $unlimited = false, $offSet = 0)
    {
        $isDefault = 1;
        $dealName  = ($langCode != 'en') ? ' COALESCE(ml.dealName, dd.dealName) ' : ' dd.dealName ';
        $dealDesc  = ($langCode != 'en') ? ' COALESCE(ml.dealDescription, dd.description) ' : ' dd.description ';
        $query     = $this->createQueryBuilder('dd')
            ->select('dd,di.path,di.id as imageId,dc.cityName,dcn.countryName,dcat.name as categoryName,dt.category as dealType,'
                .$dealDesc.' AS descriptionTrans , '.$dealName.'AS dealNameTrans, ddtc.dealCategoryId as categoryId')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->leftJoin('DealBundle:DealDetailToCategory', 'ddtc', 'WITH', "ddtc.dealDetailsId=dd.id")
            ->leftJoin('DealBundle:DealCategory', 'dcat', 'WITH', "dcat.apiCategoryId=ddtc.dealCategoryId")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dd.dealTypeId=dt.id")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dsts.isActive =1')
            ->andWhere('dd.published =1')
            ->setParameter(':isDefault', $isDefault);

        if ($langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'dd.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }

        if (!empty($category) && strtolower($category) != 'all') {
            $query->andWhere('dt.category =  :category')
                ->setParameter(':category', $category);
        }

        // sets starting records to fetch
        if ($offSet > 0) {
            $query->setFirstResult($offSet);
        }

        if ($priority) {
            $query->orderBy('dd.priority', 'DESC');
        }

        if ($unlimited) {
            $query->groupBy('dd.id');
        } else {
            $query->groupBy('dd.id')->setMaxResults(20);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve all the deals from DB based on its category ( packages, tours, transfers, activities .. )
     * These deals have the status active for the assigned API supplier with respect to the category or deal type
     * @return doctrine object result of corresponding deals or false in case of no data
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getLandingPageTopDestinations($searchObject)
    {
        //temporary setting to attractions as we are only implementing attractions for now
        $query = $this->createQueryBuilder('dd')
            ->select('count(dd.id) as tours_number', 'dt.category', 'dc.image as img', 'dc.priority', 'dc.cityName as name', 'dcn.countryName')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dd.dealTypeId=dt.id")
            ->where('dsts.isActive =1')
            ->andWhere('dd.published =1');

        // city is sent in the filters of search
        if ($searchObject->getDynamicSorting()) {
            $query->andWhere('dc.priority >=1');
        } else {
            $query->andWhere('dc.displayOrder >=1');
        }
        // city is sent in the filters of search
        if ($searchObject->getCommonSC()->getCity()->getId()) {
            $query->andWhere('dc.cityId = :cityId')
                ->setParameter(':cityId', $searchObject->getCommonSC()->getCity()->getId());
        }
        // location to use like city name in search criteria
        if ($searchObject->getCommonSC()->getCity()->getName()) {
            $query->andWhere('LOWER(dc.cityName) LIKE :cityName')
                ->setParameter(':cityName', '%'.strtolower($searchObject->getCommonSC()->getCity()->getName()).'%');
        }
        // set the limit of records
        if ($searchObject->getLimit()) {
            $query->setMaxResults($searchObject->getLimit());
        }

        if ($searchObject->getDynamicSorting()) {
            $query->orderBy('tours_number', 'DESC');
        } else {
            $query->orderBy('dc.displayOrder', 'ASC');
        }
        $query->groupBy('dd.dealCityId');
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve all the deals from DB based on its category ( packages, tours, transfers, activities .. )
     * These deals have the status active for the assigned API supplier with respect to the category or deal type
     * @param $searchObj this is the objects of all search criteria fields
     * @param $category - this is the deal type we wish to retrieve  ( packages, tours, transfers, activities .. )
     * @param $langCode - English by default
     * @return doctrine object result of corresponding deals or false in case of no data
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getLandingPageTopTours($searchObj, $category = 'attractions', $langCode = 'en')
    {
        $isDefault = 1;
        $dealName  = ($langCode != 'en') ? ' COALESCE(ml.dealName, dd.dealName) ' : ' dd.dealName ';

        $query = $this->createQueryBuilder('dd');
        if ('tours' == $category) {
            $query->select('dd.id as packageId', $dealName.'AS dealName', 'dd.dealCode', 'dd.currency', 'dd.price', 'dd.priceBeforePromo', 'dd.duration', 'dd.dealApiId', 'di.id as imageId', 'dc.cityName', 'dcn.countryName', 'dt.category as dealType');
        } else {
            $query->select('count(dd.id) as numOfResults', 'dt.category', 'di.path', 'dc.cityName', 'dcn.countryName');
        }

        $query->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dd.dealTypeId=dt.id")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dsts.isActive =1')
            ->andWhere('dd.published =1')
            ->setParameter(':isDefault', $isDefault)
            ->andWhere('dt.category =  :category')
            ->setParameter(':category', $category);

        if ('tours' == $category && $langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'dd.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }

        // city is sent in the filters of search
        if ($searchObj->getCommonSC()->getCity()->getId()) {
            $query->andWhere('dc.cityId = :cityId')
                ->setParameter(':cityId', $searchObj->getCommonSC()->getCity()->getId());
        }
        // location to use like city name in search criteria
        if ($searchObj->getCommonSC()->getCity()->getName()) {
            $query->andWhere('LOWER(dc.cityName) LIKE :cityName')
                ->setParameter(':cityName', '%'.strtolower($searchObj->getCommonSC()->getCity()->getName()).'%');
        }

        if ($category != 'tours') {
            $query->groupBy('dd.dealCityId');
        }

        $query->orderBy('dd.priority', 'DESC');
        $query->setMaxResults(12);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve all the deals by search criteria params from DB based on its category ( packages, tours, transfers, activities .. )
     * These deals have the status active for the assigned API supplier with respect to the category or deal type
     * @param params this is the object of all search criteria fields
     * @param category this is the deal type we wish to retrieve  ( packages, tours, transfers, activities .. )
     * @param priority if 1 order by deal_details priority
     * @param $langCode - English by default
     * @return doctrine object result of corresponding deals or false in case of no data
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getDealsBySearchCriteria($searchObj, $category = 'all', $priority = 0, $langCode = 'en')
    {
        $isDefault = 1;
        $dealName  = ($langCode != 'en') ? ' COALESCE(ml.dealName, dd.dealName) ' : ' dd.dealName ';
        $dealDesc  = ($langCode != 'en') ? ' COALESCE(ml.dealDescription, dd.description) ' : ' dd.description ';
        $query     = $this->createQueryBuilder('dd')
            ->select('dd,di.path,di.id as imageId,dc.cityName,dcn.countryName,dcat.name as categoryName, dt.category as dealType,'
                .$dealDesc.' AS descriptionTrans , '.$dealName.'AS dealNameTrans, ddtc.dealCategoryId as categoryId')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->leftJoin('DealBundle:DealDetailToCategory', 'ddtc', 'WITH', "ddtc.dealDetailsId=dd.id")
            ->leftJoin('DealBundle:DealCategory', 'dcat', 'WITH', "dcat.apiCategoryId=ddtc.dealCategoryId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dt.id=dd.dealTypeId")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dsts.isActive =1')
            ->andWhere('dd.published =1')
            ->setParameter(':isDefault', $isDefault);

        if ($langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'dd.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }

        //get all deal types
        if (!empty($category) && strtolower($category) != 'all') {
            $query->andWhere('dt.category = :category')
                ->setParameter(':category', $category);
        }

        // dealName is passed from deals page top attractions portion
        if ($searchObj->getCommonSC()->getPackage()->getName()) {
            $query->andWhere('LOWER(dd.dealName) LIKE :dealName')
                ->setParameter(':dealName', '%'.strtolower($searchObj->getCommonSC()->getPackage()->getName()).'%');
        }

        // city is sent in the filters of search
        if ($searchObj->getCommonSC()->getCity()->getId()) {
            $query->andWhere('dc.cityId = :cityId')
                ->setParameter(':cityId', $searchObj->getCommonSC()->getCity()->getId());
        }

        // dates ( from and to ) are sent in the filters of search
        if ($searchObj->getCommonSC()->getPackage()->getStartDate() && $searchObj->getCommonSC()->getPackage()->getEndDate()) {
            $query->andWhere('dd.startTime >= :startDate OR dd.endTime <= :endDate')
                ->setParameter(':startDate', $searchObj->getCommonSC()->getPackage()->getStartDate())
                ->setParameter(':endDate', $searchObj->getCommonSC()->getPackage()->getEndDate());
        }

        // location to use like city name in search criteria
        if ($searchObj->getCommonSC()->getCity()->getName()) {
            $query->andWhere('LOWER(dc.cityName) LIKE :cityName')
                ->setParameter(':cityName', '%'.strtolower($searchObj->getCommonSC()->getCity()->getName()).'%');
        }

        // sets minimum price in search criteria
        if ($searchObj->getMinPrice()) {
            $query->andWhere('dd.priceBeforePromo >= :minPrice')
                ->setParameter(':minPrice', $searchObj->getMinPrice());
        }

        // sets maximum price in search criteria
        if ($searchObj->getMaxPrice()) {
            $query->andWhere('dd.priceBeforePromo <= :maxPrice')
                ->setParameter(':maxPrice', $searchObj->getMaxPrice());
        }

        if (!empty($searchObj->getCategoryIds())) {
            $condition = 'ddtc.dealCategoryId IN (:categoryIds)';
            if (in_array(0, $searchObj->getCategoryIds())) {
                $condition .= ' OR ddtc.dealCategoryId IS NULL ';
            }
            $query->andWhere($condition)
                ->setParameter(':categoryIds', $searchObj->getCategoryIds());
        }
        // sets starting records to fetch
        if ($searchObj->getOffSet()) {
            $query->setFirstResult($searchObj->getOffSet());
        }
        // sets starting records to fetch
        if ($searchObj->getMaxResults()) {
            $query->setMaxResults($searchObj->getMaxResults());
        } else {
            $query->setMaxResults(20);
        }

        $query->groupBy('dd.id');

        if ($priority) {
            $query->orderBy('dd.priority', 'DESC');
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    public function getDealsSearchCount($params = array(), $category = 'all')
    {
        $query = $this->createQueryBuilder('dd')
            ->select('count(dd.id) as num')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dt.id=dd.dealTypeId")
            ->where('dsts.isActive =1')
            ->andWhere('dd.published =1');

        //get all deal types
        if (!empty($category) && strtolower($category) != 'all') {
            $query->andWhere('dt.category = :category')
                ->setParameter(':category', $category);
        }

        // dealName is passed from deals page top attractions portion
        if (isset($params['dealName']) && !empty($params['dealName'])) {
            $query->andWhere('LOWER(dd.dealName) LIKE :dealName')
                ->setParameter(':dealName', '%'.strtolower($params['dealName']).'%');
        }

        // city is sent in the filters of search
        if (isset($params['city']) && !empty($params['city'])) {
            $query->andWhere('dc.cityId = :cityId')
                ->setParameter(':cityId', $params['city']);
        }

        // dates ( from and to ) are sent in the filters of search
        if (isset($params['startDate']) && !empty($params['startDate']) && isset($params['endDate']) && !empty($params['endDate'])) {
            $query->andWhere('dd.startTime >= :startDate AND dd.endTime <= :endDate')
                ->setParameter(':startDate', $params['startDate'])
                ->setParameter(':endDate', $params['endDate']);
        }

        // location to use like city name in search criteria
        if (isset($params['cityName']) && !empty($params['cityName'])) {
            $query->andWhere('LOWER(dc.cityName) LIKE :cityName')
                ->setParameter(':cityName', '%'.strtolower($params['cityName']).'%');
        }

        // sets minimum price in search criteria
        if (isset($params['minPrice']) && !empty($params['minPrice'])) {
            $query->andWhere('dd.priceBeforePromo >= :minPrice')
                ->setParameter(':minPrice', $params['minPrice']);
        }

        // sets maximum price in search criteria
        if (isset($params['maxPrice']) && !empty($params['maxPrice'])) {
            $query->andWhere('dd.priceBeforePromo <= :maxPrice')
                ->setParameter(':maxPrice', $params['maxPrice']);
        }
        $quer = $query->getQuery();
        return $quer->getSingleScalarResult();
    }

    /**
     * This method will retrieve all deals by city searched based from deal names
     * These deals have the status active for the assigned API supplier with respect to the category or deal type
     * @param $searchObj this is the array of all search criteria fields
     * @param $langCode - English by default
     * @return doctrine object result of corresponding deals or false in case of no data
     * @author Anna Lou Parejo <anna.parejo@toursittube.com>
     * */
    public function getCitySearchByDealNames($searchObj, $langCode = 'en')
    {
        $isDefault = 1;
        $dealName  = ($langCode != 'en') ? ' COALESCE(ml.dealName, dd.dealName) ' : ' dd.dealName ';
        $dealDesc  = ($langCode != 'en') ? ' COALESCE(ml.dealDescription, dd.description) ' : ' dd.description ';
        $query     = $this->createQueryBuilder('dd')
            ->select('dd,di.path,di.id as imageId,dc.cityName,dcn.countryName,dcat.name as categoryName,dt.category as dealType,'
                .$dealDesc.' AS descriptionTrans , '.$dealName.'AS dealNameTrans, dcat.id as categoryId')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->leftJoin('DealBundle:DealDetailToCategory', 'ddtc', 'WITH', "ddtc.dealDetailsId=dd.id")
            ->leftJoin('DealBundle:DealCategory', 'dcat', 'WITH', "dcat.apiCategoryId=ddtc.dealCategoryId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dt.id=dd.dealTypeId")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dsts.isActive =1')
            ->andWhere('dd.published =1')
            ->setParameter(':isDefault', $isDefault);

        if ($langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'dd.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }

        // city is sent in the filters of search in autosuggest
        if ($searchObj->getCommonSC()->getCity()->getId()) {
            $query->andWhere('dc.cityId = :cityId')
                ->setParameter(':cityId', $searchObj->getCommonSC()->getCity()->getId());
        }

        // location to use like city name in search criteria
        if ($searchObj->getCommonSC()->getCity()->getName()) {
            $query->andWhere('LOWER(dc.cityName) LIKE :cityName')
                ->setParameter(':cityName', '%'.strtolower($searchObj->getCommonSC()->getCity()->getName()).'%');
        }

        // sets minimum price in search criteria
        if ($searchObj->getMinPrice()) {
            $query->andWhere('dd.priceBeforePromo >= :minPrice')
                ->setParameter(':minPrice', $searchObj->getMinPrice());
        }

        // sets maximum price in search criteria
        if ($searchObj->getMaxPrice()) {
            $query->andWhere('dd.priceBeforePromo <= :maxPrice')
                ->setParameter(':maxPrice', $searchObj->getMaxPrice());
        }

        if (!empty($searchObj->getCategoryIds())) {
            $condition = 'ddtc.dealCategoryId IN (:categoryIds)';
            if (in_array(0, $searchObj->getCategoryIds())) {
                $condition .= ' OR ddtc.dealCategoryId IS NULL ';
            }
            $query->andWhere($condition)
                ->setParameter(':categoryIds', $searchObj->getCategoryIds());
        }
        // sets starting records to fetch
        if ($searchObj->getOffSet()) {
            $query->setFirstResult($searchObj->getOffSet());
        }

        // dealName is passed from deals page top attractions portion
        if ($searchObj->getCommonSC()->getPackage()->getName()) {
            $searchWords = array();

            //exact word(s)
            $searchWords[] = $searchObj->getCommonSC()->getPackage()->getName();

            //get all possible word combinations
            $words       = explode(" ", $searchObj->getCommonSC()->getPackage()->getName());
            $searchWords = array_merge($searchWords, $words);
            if ($words) {
                $combinations = array();
                foreach ($words as $x) {
                    foreach ($words as $y) {
                        if ($x != $y && !in_array($y.' '.$x, $combinations)) {
                            array_push($combinations, $x.' '.$y);
                        }
                    }
                }
                $searchWords = array_merge($searchWords, $combinations);
            }

            //filter unique words
            $queryWords = array_unique($searchWords);
            $orWhere    = '';
            foreach ($queryWords as $key => $word) {
                if (strlen(trim($word)) > 2) {
                    $orWhere .= ($key > 0) ? ' OR ' : '';
                    $orWhere .= 'LOWER(dd.dealName) LIKE :dealName'.$key;
                    $query->setParameter(':dealName'.$key, '%'.strtolower(trim($word)).'%');
                }
            }
            $query->andWhere($orWhere);
        }

        $query->groupBy('dd.dealCode')->setMaxResults(20);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }
    /*
     * Getting list of deals with lowest price deal that match deal name, limit and cityid
     *
     * @param $searchObj
     *
     * @return list of data
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getEnhancedSearchByDealName($searchObj, $langCode = 'en')
    {
        $isDefault = 1;
        $dealName  = ($langCode != 'en') ? ' COALESCE(ml.dealName, dd.dealName) ' : ' dd.dealName ';
        $dealDesc  = ($langCode != 'en') ? ' COALESCE(ml.dealDescription, dd.description) ' : ' dd.description ';
        $query     = $this->createQueryBuilder('dd')
            ->select('dd,di.path,di.id as imageId,dc.cityName,dcn.countryName,dcat.name as categoryName,dt.category as dealType,'
                .$dealDesc.' AS descriptionTrans , '.$dealName.'AS dealNameTrans, dcat.id as categoryId')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->leftJoin('DealBundle:DealDetailToCategory', 'ddtc', 'WITH', "ddtc.dealDetailsId=dd.id")
            ->leftJoin('DealBundle:DealCategory', 'dcat', 'WITH', "dcat.apiCategoryId=ddtc.dealCategoryId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dt.id=dd.dealTypeId")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dsts.isActive =1')
            ->andWhere('dd.published =1')
            ->setParameter(':isDefault', $isDefault);

        if ($langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'dd.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }

        if ($searchObj->getCommonSC()->getCity()->getId()) {
            $query->andWhere('dc.cityId = :cityId')
                ->setParameter(':cityId', $searchObj->getCommonSC()->getCity()->getId());
        }

        if ($searchObj->getLimit()) {
            $query->setMaxResults($searchObj->getLimit());
        }

        if ($searchObj->getCommonSC()->getPackage()->getName()) {
            $searchWords = array();

            //exact word(s)
            $searchWords[] = $searchObj->getCommonSC()->getPackage()->getName();

            //get all possible word combinations
            $words       = explode(" ", $searchObj->getCommonSC()->getPackage()->getName());
            $searchWords = array_merge($searchWords, $words);
            if ($words) {
                $combinations = array();
                foreach ($words as $x) {
                    foreach ($words as $y) {
                        if ($x != $y && !in_array($y.' '.$x, $combinations)) {
                            array_push($combinations, $x.' '.$y);
                        }
                    }
                }
                $searchWords = array_merge($searchWords, $combinations);
            }

            //filter unique words
            $queryWords = array_unique($searchWords);
            $orWhere    = '';
            foreach ($queryWords as $key => $word) {
                if (strlen(trim($word)) > 2) {
                    $orWhere .= ($key > 0) ? ' OR ' : '';
                    $orWhere .= 'LOWER(dd.dealName) LIKE :dealName'.$key;
                    $query->setParameter(':dealName'.$key, '%'.strtolower(trim($word)).'%');
                }
            }
            $query->andWhere($orWhere);
        }

        $query->orderBy('dd.priceBeforePromo', 'ASC')->groupBy('dd.dealCode');
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve additional information(deal_details,deal_city) from DB of a certain package base on the given packageId.
     * @param $packageId
     * @param $langCode - English by default
     * @return doctrine object result of corresponding deals or false in case of no data
     * */
    public function getPackageById($packageId, $langCode = 'en')
    {
        $dealName       = ($langCode != 'en') ? ' COALESCE(ml.dealName, dd.dealName)' : ' dd.dealName ';
        $dealDesc       = ($langCode != 'en') ? ' COALESCE(ml.dealDescription, dd.description)' : ' dd.description ';
        $dealHighlights = ($langCode != 'en') ? ' ,ml.dealHighlights as dealHighlightsTrans ' : '';

        $query = $this->createQueryBuilder('dd')
            ->select('dd,dc,dt,ddtc,dcat,di,'.$dealDesc.'AS descriptionTrans , '.$dealName.'AS dealNameTrans'.$dealHighlights)
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dd.dealTypeId=dt.id")
            ->leftJoin('DealBundle:DealDetailToCategory', 'ddtc', 'WITH', "ddtc.dealDetailsId=dd.id")
            ->leftJoin('DealBundle:DealCategory', 'dcat', 'WITH', "dcat.apiCategoryId=ddtc.dealCategoryId")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dd.id = :packageId')
            ->andWhere('dd.published =1')
            ->setParameter(':isDefault', 1)
            ->setParameter(':packageId', $packageId);

        if ($langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'dd.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve top selling deals per city or country.
     * @param $cityId
     * @param $countryId
     * @param $langCode - English by default
     * @return doctrine object result of corresponding deals or false in case of no data
     * */
    public function getTopDeals($packageId = 0, $cityId = 0, $countryId = 0, $langCode = 'en')
    {

        if ($packageId == 0 && $cityId == 0 && $countryId == 0) {
            return false;
        }

        $dealName = ($langCode != 'en') ? ' COALESCE(ml.dealName, dd.dealName)' : ' dd.dealName ';
        $query    = $this->createQueryBuilder('dd')
            ->select('dd.id as packageId', $dealName.'AS dealName', 'dd.dealCode as dealCode', 'dd.dealApiId as apiId', 'dd.duration AS duration', 'dd.price AS price', 'dd.priceBeforePromo AS priceBeforePromo', 'dd.currency AS currency', 'dc.cityName as cityName', 'di.id as imageId', 'dcat.name as categoryName', 'dt.category as dealType')
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dd.dealTypeId=dt.id")
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->leftJoin('DealBundle:DealDetailToCategory', 'ddtc', 'WITH', "ddtc.dealDetailsId=dd.id")
            ->leftJoin('DealBundle:DealCategory', 'dcat', 'WITH', "dcat.apiCategoryId=ddtc.dealCategoryId")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId AND di.isDefault=:isDefault")
            ->where('dd.published =1')
            ->andWhere('dd.priority =1')
            ->andWhere('dd.id != :packageId')
            ->andWhere('dd.dealCityId = :cityId OR dd.countryId = :countryId')
            ->setParameter(':packageId', $packageId)
            ->setParameter(':cityId', $cityId)
            ->setParameter(':countryId', $countryId)
            ->setParameter(':isDefault', 1)
            ->groupBy('dd.id')
            ->setMaxResults(6);

        if ($langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'dd.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve booking details
     * @param $bookingObj booking object
     * @param $bookingObj->getDbFields() array - this is the fields that you wanted to be returned by this function
     * @param $langCode - English by default
     * @return doctrine object result of corresponding deals or false in case of no data
     * */
    public function getBookingDetails($bookingObj, $langCode = 'en')
    {
        $dealName       = ($langCode != 'en') ? ' COALESCE(ml.dealName, db.dealName) ' : ' db.dealName ';
        $dealDesc       = ($langCode != 'en') ? ' COALESCE(ml.dealDescription, db.dealDescription) ' : ' db.dealDescription ';
        $dealHighlights = ($langCode != 'en') ? ' ,ml.dealHighlights as dealHighlightsTrans ' : '';

        //If you want to specifiy the fields you wanted to retrieve. Place them inside an array().
        if ($bookingObj->getDbFields()) {
            $dbFields = implode(',', $bookingObj->getDbFields());
        } else {
            $dbFields = 'db,dc,dcn,das,dt,'.$dealDesc.'AS descriptionTrans,'.$dealName.'AS dealNameTrans'.$dealHighlights;
        }

        $query = $this->createQueryBuilder('db')
            ->select($dbFields)
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "db.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "db.countryId=dcn.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "db.apiId=das.id")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "db.dealTypeId=dt.id")
//            ->where('db.userId = :userId')
            ->where('db.id = :bookingId')
//            ->setParameter(':userId', $params['userId'])
            ->setParameter(':bookingId', $bookingObj->getBookingId());

        if ($langCode != 'en') {
            $query->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'db.dealCode = ml.dealCode and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }
        //get also DealTransferBookingDetails data
        if ($bookingObj->getLeftJoinTransfers()) {
            $query->leftJoin('DealBundle:DealTransferBookingDetails', 'dtbd', 'WITH', 'db.id = dtbd.dealBookingId');
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve all itinerary items in deal_details_itinerary table for a specific deal
     * @param $id - deal_details_id
     * @return doctrine object result of corresponding deals or false in case of no data
     * */
    public function getItineraryByDetailsId($id)
    {
        $query = $this->createQueryBuilder('ddi')
            ->select('ddi')
            ->where('ddi.dealDetailsId = :id')
            ->setParameter(':id', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will save the data in booking table
     * @param $bookingDetailsObj
     * @return the object
     * */
    public function saveBookingData($bookingObj)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('DealBundle:DealBooking')->getFieldNames();
        $createdAt   = new \DateTime("now");

        $booking = new DealBooking();

        // common for both deals and transport
        $booking->setPaymentUuid($bookingObj->getUuid());
        $booking->setApiId($bookingObj->getCommonSC()->getPackage()->getApiId());
        $booking->setBookingReference($bookingObj->getBookingReference());
        $booking->setBookingStatus($bookingObj->getBookingStatus());
        $booking->setFirstName($bookingObj->getFirstName());
        $booking->setLastName($bookingObj->getLastName());
        $booking->setAddress($bookingObj->getAddress());
        $booking->setNumOfAdults($bookingObj->getNumOfAdults());
        $booking->setTotalPrice($bookingObj->getTotalPrice());
        $booking->setCountryId($bookingObj->getCommonSC()->getCountry()->getId());
        $booking->setDealCityId($bookingObj->getCommonSC()->getCity()->getId());
        $booking->setDealTypeId($bookingObj->getCommonSC()->getPackage()->getTypeId());
        $booking->setCurrency($bookingObj->getCommonSC()->getPackage()->getCurrency());
        $booking->setCreatedAt($createdAt);
        $booking->setUpdatedAt($createdAt);
        $booking->setUserId($bookingObj->getUserId());
        $booking->setEmail($bookingObj->getBookingEmail());
        $booking->setTransactionSourceId($bookingObj->getTransactionSourceId());

        if ($bookingObj->getCommonSC()->getPackage()->getId()) {
            $booking->setDealDetailsId($bookingObj->getCommonSC()->getPackage()->getId());
        }
        if ($bookingObj->getBookingQuoteId()) {
            $booking->setDealBookingQuoteId($bookingObj->getBookingQuoteId());
        }

        if ($bookingObj->getBookingDate()) {
            $booking->setBookingDate(date("Y-m-d H:i:s", strtotime($bookingObj->getBookingDate())));
        }
        if ($bookingObj->getBookingNotes()) {
            $booking->setBookingNotes($bookingObj->getBookingNotes());
        }
        if ($bookingObj->getPriceId()) {
            $booking->setActivityPriceId($bookingObj->getPriceId());
        }
        if ($bookingObj->getCommonSC()->getPackage()->getName()) {
            $booking->setDealName($bookingObj->getCommonSC()->getPackage()->getName());
        }
        if ($bookingObj->getCommonSC()->getPackage()->getDescription()) {
            $booking->setDealDescription($bookingObj->getCommonSC()->getPackage()->getDescription());
        }
        if ($bookingObj->getCancellationPolicy()) {
            $booking->setCancellationPolicy($bookingObj->getCancellationPolicy());
        }
        if ($bookingObj->getTitle()) {
            $booking->setTitle($bookingObj->getTitle());
        }
        if ($bookingObj->getDialingCode()) {
            $booking->setDialingCode($bookingObj->getDialingCode());
        }
        if ($bookingObj->getMobile()) {
            $booking->setMobilePhone($bookingObj->getMobile());
        }
        if ($bookingObj->getPostalCode()) {
            $booking->setPostalCode($bookingObj->getPostalCode());
        }
        if ($bookingObj->getNumOfChildren()) {
            $booking->setNumOfChildren($bookingObj->getNumOfChildren());
        }
        if ($bookingObj->getNumOfInfants()) {
            $booking->setNumOfInfants($bookingObj->getNumOfInfants());
        }
        if ($bookingObj->getBookingVoucherInformation()) {
            $booking->setVoucherInformation($bookingObj->getBookingVoucherInformation());
        }
        if ($bookingObj->getCommonSC()->getPackage()->getCode()) {
            $booking->setDealCode($bookingObj->getCommonSC()->getPackage()->getCode());
        }
        if ($bookingObj->getDuration()) {
            $booking->setDuration($bookingObj->getDuration());
        }
        if ($bookingObj->getStartingPlace()) {
            $booking->setStartingPlace($bookingObj->getStartingPlace());
        }
        if ($bookingObj->getCommonSC()->getPackage()->getHighlights()) {
            $booking->setDealHighlights($bookingObj->getCommonSC()->getPackage()->getHighlights());
        }
        if ($bookingObj->getStartTime()) {
            $booking->setStartTime($bookingObj->getStartTime());
        }
        if ($bookingObj->getEndTime()) {
            $booking->setEndTime($bookingObj->getEndTime());
        }
        // field is both used for deals and transfer but sometimes we only have arrival and no departure
        if ($bookingObj->getBookingTime()) {
            $booking->setDepartureTime($bookingObj->getBookingTime());
        }
        if ($bookingObj->getAmountFBC()) {
            $booking->setAmountFbc($bookingObj->getAmountFBC());
        }
        if ($bookingObj->getAmountSBC()) {
            $booking->setAmountSbc($bookingObj->getAmountSBC());
        }
        if ($bookingObj->getAccountCurrencyAmount()) {
            $booking->setAccountCurrencyAmount($bookingObj->getAccountCurrencyAmount());
        }

        $em->persist($booking);
        $em->flush();
        return $booking;
    }

    /**
     * This method will save the data in transfer_booking_details table.
     * This is for all data regarding the transport.
     * @param $bookingObj
     * @return true
     * */
    public function saveTransferBookingDetailsData($bookingObj)
    {
        $em             = $this->getEntityManager();
        $tableFields    = $em->getClassMetadata('DealBundle:DealTransferBookingDetails')->getFieldNames();
        $createdAt      = new \DateTime("now");
        $datetime       = new \DateTime();
        $serviceCodeArr = array('oneWayFromAirport' => 0, 'oneWayToAirport' => 1, 'roundtrip' => 2);
        $arrivalTime    = $bookingObj->getArrivalDeparture()->getArrivalHour().':'.$bookingObj->getArrivalDeparture()->getArrivalMinute();

        $details = new DealTransferBookingDetails();
        $details->setDealBookingId($bookingObj->getBookingId());
        $details->setArrivalPriceId($bookingObj->getArrivalDeparture()->getArrivalPriceId());
        $details->setDeparturePriceId($bookingObj->getArrivalDeparture()->getDeparturePriceId());
        $details->setAirportName($bookingObj->getAirport()->getName());
        $details->setAirportCode($bookingObj->getAirport()->getCode());
        $details->setAddress($bookingObj->getDestinationAddress());
        $details->setServiceCode($serviceCodeArr[$bookingObj->getTypeOfTransfer()->getName()]);
        $details->setArrivalTime($arrivalTime);
        $details->setArrivalFlightDetails($bookingObj->getArrivalDeparture()->getArrivalCompany());
        $details->setArrivalFrom($bookingObj->getArrivalDeparture()->getArrivalFrom());
        $details->setArrivalDestinationAddress($bookingObj->getArrivalDeparture()->getArrivalDestinationAddress());
        $details->setDepartureFlightDetails($bookingObj->getArrivalDeparture()->getDepartureCompany());
        $details->setDepartTo($bookingObj->getGoingTo());
        $details->setDeparturePickupAddress($bookingObj->getArrivalDeparture()->getDepartureDestinationAddress());
        $details->setServiceType($bookingObj->getServiceType());
        $details->setCarModel($bookingObj->getCarModel());
        $details->setCreatedAt($createdAt);
        $details->setUpdatedAt($createdAt);


        if ($bookingObj->getArrivalDeparture()->getArrivalDate()) {
            $arrivalDate = $datetime->createFromFormat('Y-m-d', $bookingObj->getArrivalDeparture()->getArrivalDate());
            $details->setArrivalDate($arrivalDate);
        }
        if ($bookingObj->getArrivalDeparture()->getDepartureDate()) {
            $departureDate = $datetime->createFromFormat('Y-m-d', $bookingObj->getArrivalDeparture()->getDepartureDate());
            $details->setDepartureDate($departureDate);
        }

        $em->persist($details);
        $em->flush();

        return true;
    }

    /**
     * This method will save the data in deal_booking_passengers table.
     * This is for all the passengers of a particular booking.
     * @param $bookingObj
     * @return true
     * */
    public function saveBookingPassengersData($bookingObj)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('DealBundle:DealBookingPassengers')->getFieldNames();
        $createdAt   = new \DateTime("now");

        if ($bookingObj->getAge()) {
            $passengerCount = count($bookingObj->getAge());
            for ($i = 0; $i < $passengerCount; $i++) {
                $age     = $bookingObj->getAge()[$i];
                $ageType = ( $age > 17 ) ? 'adult' : 'child';

                $passengers = new DealBookingPassengers();
                $passengers->setDealBookingId($bookingObj->getBookingId());
                $passengers->setFirstName($bookingObj->getFirstName()[$i]);
                $passengers->setLastName($bookingObj->getLastName()[$i]);
                $passengers->setAge($age);
                $passengers->setAgeType($ageType);
                $passengers->setCreatedAt($createdAt);
                $passengers->setUpdatedAt($createdAt);

                $em->persist($passengers);
                $em->flush();
            }
        }

        return true;
    }

    /**
     * This method will save the data in deal_booking_details table.
     * This is for all the itineraries related to booking.
     * @param $dealBookingId
     * @param $params array()
     * @param $prefix doctrine return aliases from db so I added this field
     * @return true
     * */
    public function saveBookingDetailsData($dealBookingId, $params = array(), $prefix = '')
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('DealBundle:DealBookingDetails')->getFieldNames();
        $createdAt   = new \DateTime("now");

        foreach ($params as $key => $val) {
            $details = new DealBookingDetails();
            $details->setDealBookingId($dealBookingId);
            $details->setTitle($val[$prefix.'title']);
            $details->setDescription($val[$prefix.'description']);
            $details->setBreakfastInclude($val[$prefix.'breakfastInclude']);
            $details->setLunchInclude($val[$prefix.'lunchInclude']);
            $details->setDinnerInclude($val[$prefix.'dinnerInclude']);
            $details->setHotelCode($val[$prefix.'hotelCode']);
            $details->setHotelName($val[$prefix.'hotelName']);
            $details->setItemCode($val[$prefix.'itemCode']);
            $details->setItemType($val[$prefix.'itemType']);
            $details->setStart($val[$prefix.'start']);
            $details->setEnd($val[$prefix.'end']);
            $details->setDuration($val[$prefix.'duration']);
            $details->setStatus($val[$prefix.'status']);
            $details->setCreatedAt($createdAt);
            $details->setUpdatedAt($createdAt);

            $em->persist($details);
            $em->flush();
        }

        return true;
    }

    /**
     * This method will cancel a booking data
     * @param $bookingObj object of criteria
     * @return the object
     * */
    public function updateBookingStatus($bookingObj)
    {
        $updatedAt = new \DateTime("now");
        $qb        = $this->createQueryBuilder('u');
        $query     = $qb->update('DealBundle:DealBooking db')
            ->set('db.bookingStatus', ':bookingStatus')
            ->set('db.updatedAt', ':updatedAt')
            ->Where('db.id = :bookingId')
            ->setParameter('bookingStatus', $bookingObj->getBookingStatus())
            ->setParameter('updatedAt', $updatedAt)
            ->setParameter('bookingId', $bookingObj->getBookingId())
            ->getQuery()
            ->getResult();
        return $qb;
    }

    /**
     * This method will get the list of images of a particular deal_details_id
     * This function will be used to locate the next available image if the default image is not available
     * @param $id - deal_details_id
     * @return array of images
     * */
    public function getNextDealImage($id)
    {
        $query = $this->createQueryBuilder('dd')
            ->select('dd.dealCode', 'dd.dealApiId as apiId', 'dcat.name as categoryName', 'dc.cityName', 'di.id as image')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->leftJoin('DealBundle:DealDetailToCategory', 'ddtc', 'WITH', "dd.id=ddtc.dealDetailsId")
            ->leftJoin('DealBundle:DealCategory', 'dcat', 'WITH', "ddtc.dealCategoryId=dcat.apiCategoryId")
            ->leftJoin('DealBundle:DealImage', 'di', 'WITH', "dd.id=di.dealDetailId")
            ->where('dd.id = :detailsId')
            ->setParameter(':detailsId', $id)
            ->orderBy('di.isDefault', 'DESC')
            ->getQuery();

        $result = $query->getScalarResult();
        return $result;
    }

    /**
     * This method will update the publish column for an activity
     *
     * @param $activityId
     * @param $val
     * @return $qb objects
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     * */
    public function updatePublished($activityId, $val = 1)
    {
        $updatedAt = new \DateTime("now");
        $qb        = $this->createQueryBuilder('u');
        $query     = $qb->update('DealBundle:DealDetails dd')
            ->set('dd.published', ':published')
            ->set('dd.updatedAt', ':updatedAt')
            ->Where('dd.id = :activityId')
            ->setParameter(':published', $val)
            ->setParameter(':updatedAt', $updatedAt)
            ->setParameter(':activityId', $activityId)
            ->getQuery()
            ->getResult();
        return $qb;
    }

    /**
     * This method will update booking payment UUID
     * @param $bookingId
     * @param $paymentId
     * @return the object
     * */
    public function updateBookingUUID($bookingId, $paymentId)
    {
        $updatedAt = new \DateTime("now");
        $qb        = $this->createQueryBuilder('u');
        $query     = $qb->update('DealBundle:DealBooking db')
            ->set('db.paymentUuid', ':uuid')
            ->set('db.updatedAt', ':updatedAt')
            ->Where('db.id = :bookingId')
            ->setParameter(':uuid', $paymentId)
            ->setParameter(':updatedAt', $updatedAt)
            ->setParameter(':bookingId', $bookingId)
            ->getQuery()
            ->getResult();
        return $qb;
    }

    /**
     *  This is the method is used to sync images with its corresponding dealDetailsId
     *
     */
    public function syncDealImages()
    {
        $qb = $this->createQueryBuilder('dd')
            ->select('dd.id, dd.dealCode, di.id AS deal_image_id, di.path')
            ->from('DealBundle:DealDetails', 'dd')
            ->innerJoin('DealBundle:DealImage', 'di', 'WITH', "di.path LIKE CONCAT(  '%', dd.dealCode,  '\_%' )")
            ->andWhere('dd.published =1');

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        foreach ($result as $key => $val) {
            echo $val['deal_image_id'].'==='.$val['id'].'<br />';
            $em2 = $this->getDoctrine()->getManager();
            $img = $this->getDoctrine()->getRepository('DealBundle:DealImage')->find($val['deal_image_id']);
            $img->setDealDetailId($val['id']);
            $em2->flush();
        }
    }

    /**
     * This method will git CityId depending on the countryCode and cityName
     *
     * @param $params
     * @return cityId
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     * */
    public function getCityIdByParams($params = array())
    {
        $query = $this->createQueryBuilder('dc')
            ->select('dc.id');

        if (isset($params['countryCode'])) {
            $query->andWhere('dc.countryCode = :countryCode')
                ->setParameter(':countryCode', $params['countryCode']);
        }
        if (isset($params['cityName'])) {
            $query->andWhere('dc.cityName = :cityName')
                ->setParameter(':cityName', $params['cityName']);
        }

        $quer   = $query->setMaxResults(1)->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['id'];
        } else {
            return 0;
        }
    }

    /**
     *  This is the method is update Booking data. This is used in PackagesCorpoController.
     *
     */
    public function updateBookingData($bookingObj)
    {
        $em        = $this->getEntityManager();
        $booking   = $em->getRepository('DealBundle:DealBooking')->findOneById($bookingObj->getBookingId());
        $updatedAt = new \DateTime("now");

        if ($bookingObj->getBookingReference()) {
            $booking->setBookingReference($bookingObj->getBookingReference());
        }
        if ($bookingObj->getBookingStatus()) {
            $booking->setBookingStatus($bookingObj->getBookingStatus());
        }
        if ($bookingObj->getBookingVoucherInformation()) {
            $booking->setVoucherInformation($bookingObj->getBookingVoucherInformation());
        }
        if ($bookingObj->getCancellationPolicy()) {
            $booking->setCancellationPolicy($bookingObj->getCancellationPolicy());
        }
        if ($bookingObj->getBookingQuoteId()) {
            $booking->setDealBookingQuoteId($bookingObj->getBookingQuoteId());
        }

        $booking->setUpdatedAt($updatedAt);

        $em->flush();
        return true;
    }

    /**
     * This method will retrieve deal_transfer_booking_details data together with deal_booking, deal_city, deal_country
     * @param $bookingId
     * @return array
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getTransferDetailsByBookingId($bookingId)
    {
        $query = $this->createQueryBuilder('dtbd')
            ->select('dtbd.serviceCode as serviceCode,dtbd.airportCode as airportCode,'
                .' dtbd.arrivalPriceId as arrivalPriceId, dtbd.arrivalTime as arrivalTime, dtbd.arrivalDate as arrivalInput, dtbd.arrivalFlightDetails as arrivalFlightDetails, dtbd.arrivalDestinationAddress as arrivalCompleteAddress, dtbd.arrivalFrom as arrivingFrom,'
                .' dtbd.departurePriceId as departurePriceId, db.departureTime as departureTime, dtbd.departureDate as departureInput, dtbd.departureFlightDetails as departureFlightDetails, dtbd.departurePickupAddress as departureCompleteAddress, dtbd.departTo as goingTo,'
                .' db.firstName as firstName, db.lastName as lastName, db.postalCode as postalCode, db.mobilePhone as mobile, db.email as email, db.totalPrice as bookingTotal, db.currency as currency, db.address as ccBillingAddress, db.numOfAdults as numOfpassengers, db.bookingNotes as bookingNotes,'
                .' dc.cityName as city, dcntry.countryCode as country')
            ->innerJoin('DealBundle:DealBooking', 'db', 'WITH', "dtbd.dealBookingId=db.id")
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "db.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcntry', 'WITH', "db.countryId=dcntry.id")
            ->where('dtbd.dealBookingId = :bookingId')
            ->setParameter(':bookingId', $bookingId)
            ->getQuery();

        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve activities from the deal_details that are published
     * @param $bookingId
     * @return array
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getPublishedActivities()
    {

        $query = $this->createQueryBuilder('dd')
            ->select('dd')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "dd.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealCountry', 'dcn', 'WITH', "dd.countryId=dcn.id")
            ->innerJoin('DealBundle:DealApiSupplier', 'das', 'WITH', "dd.dealApiId=das.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dd.dealTypeId=dt.id")
            ->where('dsts.isActive =1')
            ->andWhere('dd.dealApiId = 3');

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve deal details info by id
     * @param $id
     * @param $langCode
     *
     * @return array
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getDealDetailsInfo($id, $langCode = 'en')
    {
        $qb = $this->createQueryBuilder('dd')
            ->select('dd,ml,c,dt.category as dealType')
            ->innerJoin('DealBundle:DealCity', 'c', 'WITH', 'c.id = dd.dealCityId')
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dt.id=dd.dealTypeId");
        if ($langCode == 'en') {
            $qb->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'ml.dealCode = dd.id AND ml.langCode=:Language');
        } else {
            $qb->innerJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'ml.dealCode = dd.id AND ml.langCode=:Language');
        }
        $qb->where("dd.id = :Id ")
            ->setParameter(':Language', $langCode)
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        $res   = $query->getScalarResult();
        $ret   = count($res);
        if ($res && $ret != 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve deal City info by id
     * @param $id
     *
     * @return array
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getDealCityInfo($id)
    {
        $qb    = $this->createQueryBuilder('c')
            ->select('c')
            ->where("c.id = :Id ")
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        $res   = $query->getScalarResult();
        $ret   = count($res);
        if ($res && $ret != 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve deal City info list id
     * @param $list_id
     *
     * @return array
     * */
    public function getDealCityInfoList($list_id)
    {
        $qb    = $this->createQueryBuilder('c')
            ->select('c');
        $qb->add('where', $qb->expr()->in('c.id', ':List_id'));
        $qb->setParameter(':List_id', explode(',',$list_id));

        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /**
     * This method will retrieve Deal Top Attractions Info by id
     * @param $id
     *
     * @return array
     * @author Firas Bou karroum <firas.boukarroum@toursittube.com>
     * */
    public function getDealTopAttractionsInfo($id)
    {
        $qb    = $this->createQueryBuilder('a')
            ->select('a,w.name as w_name,w.countryCode as w_countryCode,w.stateCode as w_stateCode')
            ->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id= a.cityId')
            ->where("a.id = :Id ")
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        $res   = $query->getScalarResult();
        $ret   = count($res);
        if ($res && $ret != 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve Deal Top Attractions Info list id
     * @param $list_id
     *
     * @return array
     * */
    public function getDealTopAttractionsInfoList($list_id)
    {
        $qb    = $this->createQueryBuilder('a')
            ->select('a,w.name as w_name,w.countryCode as w_countryCode,w.stateCode as w_stateCode')
            ->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id= a.cityId');
        $qb->add('where', $qb->expr()->in('a.id', ':List_id'));
        $qb->setParameter(':List_id', explode(',',$list_id));

        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /**
     * This method will save quote response to deal_booking_quote
     * @param $quoteObj
     * @return the object
     * */
    public function saveBookingQuote($quoteObj)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('DealBundle:DealBookingQuote')->getFieldNames();

        $createdAt       = new \DateTime("now");
        $packageId       = $quoteObj->getCommonSC()->getPackage()->getId();
        $tourCode        = $quoteObj->getCommonSC()->getPackage()->getCode();
        $activityPriceId = $quoteObj->getActivityPriceId();
        $priceId         = $quoteObj->getPriceId();
        $dynamicFields   = array();
        $return          = array();
        $unitLabels      = array();

        foreach ($quoteObj->getUnits() as $ulKey => $ulVal) {
            $unitLabels[$ulVal->getUnitId()] = $ulVal->getLabel();
        }

        if ($quoteObj->getMandatoryFields()) {
            foreach ($quoteObj->getMandatoryFields() as $mKey => $mVal) {
                $dynamicFields['Mandatory'][] = $mVal->toArray();
            }
        }

        //$dynamicFields['Transportation'] = isset($params['SelectTransportation']['Transportation']) ? $params['SelectTransportation']['Transportation'] : array();

        $firstRow = true;
        $bQuoteId = array();
        foreach ($quoteObj->getQuote() as $qKey => $qVal) {
            $numOfPassengers = 0;
            foreach ($qVal->getUnits() as $uKey => $uVal) {
                $tmpUnitId                = $uVal->getUnitId();
                $dynamicFields['Units'][] = array('unitId' => $tmpUnitId,
                    'quantity' => $uVal->getQuantity(),
                    'unitLabel' => (isset($unitLabels[$tmpUnitId]) ? $unitLabels[$tmpUnitId] : ''));
                $numOfPassengers          += $uVal->getQuantity();
            }

            $quote  = new DealBookingQuote();
            $timeId = $qVal->getTimeId();
            $time   = $qVal->getTime();
            $total  = $qVal->getTotal();

            $quote->setPackageId($packageId);
            $quote->setTourCode($tourCode);
            $quote->setActivityPriceId($activityPriceId);
            $quote->setPriceId($priceId);
            $quote->setQuoteKey($qVal->getQuoteKey());
            $quote->setTotal($total);
            $quote->setTimeId($timeId);
            $quote->setTime($time);
            $quote->setDynamicFields(json_encode($dynamicFields));
            $quote->setCreatedAt($createdAt);
            $quote->setUpdatedAt($createdAt);

            $em->persist($quote);
            $em->flush();

            unset($dynamicFields['Units']);

            $bQuoteId[] = $quote->getId();

            if ($firstRow) {
                $return['total']           = $total;
                $return['numOfPassengers'] = $numOfPassengers;
                $firstRow                  = false;
            }
        }

        $return['quoteId'] = $bQuoteId;
        return $return;
    }

    /**
     * This method will save the answer of each Mandatory field in deal_booking_quote
     * @param $fieldAnswersObj
     * @return the object
     * */
    public function saveMandatoryFieldAnswers($fieldAnswersObj)
    {
        $updatedAt = new \DateTime("now");
        $qb        = $this->createQueryBuilder('u');
        $query     = $qb->update('DealBundle:DealBookingQuote dbq')
            ->set('dbq.dynamicFieldsValues', ':answer')
            ->set('dbq.updatedAt', ':updatedAt')
            ->Where('dbq.id = :quoteKey')
            ->setParameter(':answer', $fieldAnswersObj->getFieldAnswers())
            ->setParameter(':updatedAt', $updatedAt)
            ->setParameter(':quoteKey', $fieldAnswersObj->getBookingQuoteId())
            ->getQuery()
            ->getResult();
        return $qb;
    }

    /**
     * This method will retrieve available DealApiSupplier
     * @param $dealType
     * @return array
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     * */
    public function getActiveApiPerDealType($dealType = 'attractions')
    {
        $query = $this->createQueryBuilder('das')
            ->select('das')
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "das.id=dsts.dealApiSupplierId")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dsts.dealTypeId=dt.id")
            ->where('dsts.isActive =1')
            ->andWhere('dt.category = :category')
            ->setParameter(':category', $dealType);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve data from DealTopAttractions table based on available DealApiSupplier
     * @param $dealSC
     * @return array
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     * */
    public function getTopAttractionsPerApi($dealSC)
    {
        if (!$dealSC->getApiSupplierId()) {
            return false;
        }

        $query = $this->createQueryBuilder('dta')
            ->select('dta.name as name', 'dta.imageUrl as img')
            ->where('dta.dealApiSupplierId IN (:apiId)')
            ->setParameter(':apiId', $dealSC->getApiSupplierId());

        if ($dealSC->getLimit()) {
            $query->setMaxResults($dealSC->getLimit());
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will retrieve booking data that will be cancelled from deal_booking join deal_type
     * @param $fieldValue
     * @param $dbField
     * @return array
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     * */
    public function getBookingDataForCancellation($fieldValue, $dbField = 'id')
    {
        $query = $this->createQueryBuilder('db')
            ->select('db.bookingReference as bookingReference', 'db.email as email', 'dt.category as dealType')
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "db.dealTypeId = dt.id");

        switch ($dbField) {
            case 'bookingReference':
                $query->Where('db.bookingReference = :fieldValue');
                break;
            default:
                $query->Where('db.id = :fieldValue');
        }

        $query->setParameter(':fieldValue', $fieldValue);
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * This method will save the data in DealCancelPolicies table.
     * @param $dealBookingId
     * @param $price
     * @return true
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     * */
    public function saveCancelPolicies($dealBookingId, $price)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('DealBundle:DealCancelPolicies')->getFieldNames();
        $createdAt   = new \DateTime("now");

        $dealCancelPolicies = new DealCancelPolicies();
        $dealCancelPolicies->setDealBookingId($dealBookingId);
        $dealCancelPolicies->setPenaltyAmount($price);
        $dealCancelPolicies->setCreatedAt($createdAt);
        $dealCancelPolicies->setUpdatedAt($createdAt);

        $em->persist($dealCancelPolicies);
        $em->flush();

        return true;
    }

    /**
     * This method will get category list
     *
     *
     * @return category list
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    public function getCategoryList()
    {
        $query  = $this->createQueryBuilder('dc')
            ->innerJoin('DealBundle:DealApiSupplier', 'ds', 'WITH', "dc.dealApiId=ds.id")
            ->innerJoin('DealBundle:DealSupplierTypeStatus', 'dsts', 'WITH', "ds.id=dsts.dealApiSupplierId")
            ->where('dsts.isActive =1')
            ->orderBy('dc.name', 'ASC')
            ->getQuery();
        $result = $query->getScalarResult();

        $categoryList = array();
        if (!empty($result)) {
            foreach ($result as $key => $val) {
                $lists['id']    = $val['dc_id'];
                $lists['name']  = $val['dc_name'];
                $categoryList[] = $lists;
            }
            return $categoryList;
        } else {
            return $categoryList;
        }
    }
    /**
     * This method will retrieve deal details info list id
     * @param $list_id
     * @param $langCode
     *
     * @return array
     * */
    public function getDealDetailsInfoList($list_id, $langCode = 'en')
    {
        $qb = $this->createQueryBuilder('dd')
            ->select('dd,ml,c,dt.category as dealType')
            ->innerJoin('DealBundle:DealCity', 'c', 'WITH', 'c.id = dd.dealCityId')
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "dt.id=dd.dealTypeId");
        if ($langCode == 'en') {
            $qb->leftJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'ml.dealCode = dd.id AND ml.langCode=:Language');
        } else {
            $qb->innerJoin('TTBundle:MlDealTexts', 'ml', 'WITH', 'ml.dealCode = dd.id AND ml.langCode=:Language');
        }
        $qb->add('where', $qb->expr()->in('dd.id', ':List_id'));
        $qb->setParameter(':List_id', explode(',',$list_id));
        $qb->setParameter(':Language', $langCode);
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }
}
