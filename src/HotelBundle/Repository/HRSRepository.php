<?php

namespace HotelBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HotelBundle\Entity\CmsHotelImage;

class HRSRepository extends EntityRepository
{

    /**
     * CMS Hotel - Get hotel data given its unique id (HRS)
     *
     * @param  Integer $hotelId
     * @param  String  $langCode The language code (Optional; default = 'en').
     * @return Mixed   The Hotel OR FALSE if not found.
     */
    public function getHotelDataById($hotelId, $langCode = 'en')
    {
        $hotelName = ($langCode != 'en') ? 'COALESCE(ml.name, h.name)' : 'h.name';
        $hotelDesc = ($langCode != 'en') ? 'ml.description' : 'h.description';
        $query     = $this->createQueryBuilder('h')
            ->select($hotelDesc.' AS description, h.name, '.$hotelName.' AS nameTrans,
                    h.id,
                    h.stars,
                    h.street,
                    h.district,
                    h.zipCode,
                    wgc.name AS city,
                    wgc.id AS cityId,
                    h.latitude,
                    h.longitude,
                    c.code AS countryCode,
                    (CASE WHEN (c.name is NULL) THEN h.iso3CountryCode ELSE c.name END) AS country,
                    hs.source,
                    h.has360,
                    hs.isActive')
            ->innerJoin('HotelBundle:CmsHotelSource', 'hs', 'WITH', 'h.id = hs.hotelId')
            ->innerJoin('HotelBundle:CmsHotelCity', 'chc', 'WITH', 'hs.locationId = chc.locationId')
            ->leftJoin('TTBundle:Webgeocities', 'wgc', 'WITH', 'chc.cityId = wgc.id')
            ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'wgc.countryCode = c.code');

        if ($langCode != 'en') {
            $query->leftJoin('HotelBundle:MlHotel', 'ml', 'WITH', 'h.id = ml.hotelId and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }
        $query = $query->where("h.id = :hotelId")
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * CMS Hotel - Get hotel data given its unique id
     *
     * @param  Integer $hotelId The hotel id.
     * @return Mixed   The hotel OR FALSE if not found.
     */
    public function getHotelById($hotelId)
    {
        $query = $this->createQueryBuilder('h')
            ->select('h')
            ->where("h.id = :hotelId")
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * CMS Hotel - Get hotels data given its list_id
     *
     * @param  String $list_id The list hotel id.
     * @return list   of hotels.
     */
    public function getHotelByListId($list_id)
    {
        $qb = $this->createQueryBuilder('h')
            ->select('h');
        $qb->add('where', $qb->expr()->in('h.id', ':list_id'));
        $qb->setParameter(':list_id', explode(',', $list_id));

        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /**
     * CMS Hotel - Get hotel name (multi-language) given its unique id
     *
     * @param  Integer $hotelId  The hotel id.
     * @param  String  $langCode The language code (Optional; default = 'en').
     * @return Mixed   The hotel OR FALSE if not found.
     */
    public function getHotelNameById($hotelId, $langCode = 'en')
    {
        $hotelName = ($langCode != 'en') ? 'COALESCE(ml.name, h.name)' : 'h.name';
        $query     = $this->createQueryBuilder('h')->select($hotelName.' AS name');

        if ($langCode != 'en') {
            $query->leftJoin('HotelBundle:MlHotel', 'ml', 'WITH', 'h.id = ml.hotelId and ml.langCode=:langCode')->setParameter(':langCode', $langCode);
        }
        $query = $query->where("h.id = :hotelId")
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['name'];
        } else {
            return false;
        }
    }

    /**
     * CMS Hotel - Gets the hotel iso3 country code given the hotelId(PK)
     *
     * @param integer   $hotelId
     * @return Mixed    The iso3 OR FALSE if not found.
     */
    public function getHotelIso3Country($hotelId)
    {
        $query = $this->createQueryBuilder('h')
            ->select('h.iso3CountryCode')
            ->where('h.id = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();
        if (!empty($result) && isset($result[0])) {
            return $result[0]['iso3CountryCode'];
        } else {
            return false;
        }
    }

    /**
     * CMS Hotel Facility - Get list of facilities given a hotel id
     *
     * @param  Integer $hotelId  The hotel id.
     * @param  String  $langCode The language code (Optional; default='en').
     * @return Array   list of hotel facilities
     */
    public function getHotelFacilities($hotelId, $langCode = 'en')
    {
        $facility_name = ($langCode != 'en') ? 'COALESCE(fl.name, f.name)' : 'f.name';
        $type_name     = ($langCode != 'en') ? 'COALESCE(ftl.name, ft.name)' : 'ft.name';
        $query         = $this->createQueryBuilder('hf')
            ->select($facility_name.' as facilityName, ft.id as typeId, '.$type_name.' as typeName')
            ->innerJoin('HotelBundle:CmsFacility', 'f', 'WITH', 'f.id = hf.facilityId AND f.published=1')
            ->innerJoin('HotelBundle:CmsFacilityType', 'ft', 'WITH', 'ft.id = f.typeId')
            ->where('hf.hotelId = :hotelId')
            ->setParameter(':hotelId', $hotelId);
        if ($langCode != 'en') {
            $query->leftJoin('HotelBundle:MlFacilitiesAmenities', 'fl', 'WITH', 'fl.entityId = f.id and fl.langCode=:langCode')
                ->leftJoin('HotelBundle:MlFacilityType', 'ftl', 'WITH', 'ftl.entityId = ft.id and ftl.langCode=:langCode')
                ->setParameter(':langCode', $langCode);
        }
        $query = $query->getQuery();

        return $query->getScalarResult();
    }

    /**
     * CMS Hotel Facility - Get list of facilities given a hotel id
     *
     * @param  Integer $hotelId  The hotel id.
     * @param  String  $langCode The language code (Optional; default='en').
     * @return Array   List of hotel facilities ordered from highest to lowest amenity level.
     */
    public function getHotelHighestFacilities($hotelId, $langCode = 'en')
    {
        $facility_name = ($langCode != 'en') ? 'COALESCE(fl.name, f.name)' : 'f.name';
        $query         = $this->createQueryBuilder('hf')
            ->select($facility_name.' as facilityName, ft.id as typeId, ft.name as typeName')
            ->innerJoin('HotelBundle:CmsFacility', 'f', 'WITH', 'f.id = hf.facilityId AND f.published=1 AND f.amenityLevel != 0')
            ->innerJoin('HotelBundle:CmsFacilityType', 'ft', 'WITH', 'ft.id = f.typeId')
            ->where('hf.hotelId = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->addOrderBy('f.amenityLevel', 'DESC')
            ->setMaxResults(12);
        if ($langCode != 'en') {
            $query->leftJoin('HotelBundle:MlFacilitiesAmenities', 'fl', 'WITH', 'fl.entityId = f.id and fl.langCode=:langCode')->setParameter(':langCode', $langCode);
        }
        $query = $query->getQuery();

        return $query->getScalarResult();
    }

    /**
     * CMS Hotel Image - Get images for specific hotel
     *
     * @param  Integer $hotelId The hotel id.
     * @param  Integer $limit   The limit (Optional; default = null).
     * @param  Integer $user_id The user id (Optional; default = 0).
     * @return Array   List of hotel image information
     */
    public function getHotelImages($hotelId, $limit = null, $user_id = 0)
    {
        $preQuery = $this->createQueryBuilder('hi')
            ->select('hi.id, hi.hotelId, hi.filename as imageSource, hi.location as imageLocation, hi.userId')
            ->where('hi.hotelId = :hotelId AND hi.mediaTypeId = :mediaTypeId')
            ->setParameter(':mediaTypeId', 1)
            ->setParameter(':hotelId', $hotelId);

        //value -1: don't check user id (return all photos)
        if ($user_id != -1) {
            $preQuery->andwhere('hi.userId=:User_id')
                ->setParameter(':User_id', $user_id);
        }

        if ($limit > 0) {
            $preQuery->setMaxResults($limit);
        }

        $query = $preQuery->getQuery();
        return $query->getScalarResult();
    }

    /**
     * CMS Hotel Image - Get default image for a specific hotel
     *
     * @param  Integer $hotelId The hotel id.
     * @return Mixed   Array image data OR FALSE if not found.
     */
    public function getHotelMainImage($hotelId)
    {
        $query = $this->createQueryBuilder('hi')
            ->select('hi.hotelId, hi.filename as imageSource, hi.location as imageLocation')
            ->where("hi.hotelId = :hotelId AND hi.mediaTypeId = :mediaTypeId AND hi.defaultPic = :defaultPic")
            ->setParameter(':hotelId', $hotelId)
            ->setParameter(':mediaTypeId', 1)
            ->setParameter(':defaultPic', 1)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * CMS Hotel Image - Get room images for a specific hotel
     *
     * @param  Integer $hotelId
     * @param  Array   $roomTypes The room types.
     * @param  Integer $limit     The limit (Optional; default=null).
     * @return Array   List of hotel room images information.
     */
    public function getHotelRoomImages($hotelId, $roomTypes, $limit = null)
    {
        $preQuery = $this->createQueryBuilder('hi')
            ->select('
                    hi.hotelId,
                    hi.filename as imageSource,
                    hi.location as imageLocation
                ')
            ->where('hi.hotelId = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->addOrderBy('locationSort', 'ASC');

        $sort     = '(CASE ';
        $location = array();
        foreach ($roomTypes as $key => $roomType) {
            $sort       .= ' WHEN hi.location = :'.$roomType.' THEN '.$key;
            $location[] = 'hi.location = :'.$roomType;
            $preQuery->setParameter(':'.$roomType, $roomType);
        }
        $sort .= ' ELSE 100 END) AS HIDDEN locationSort';
        $preQuery->addSelect($sort);
        $preQuery->andWhere(implode(' OR ', $location));

        if ($limit > 0) {
            $preQuery->setMaxResults($limit);
        }

        $query = $preQuery->getQuery();
        return $query->getScalarResult();
    }

    /**
     * CMS Hotel Source - Get all districts associated for a specific location_id (this is for now used only for Paris arrondissements) (HRS)
     *
     * @param  Integer $locationIdentifier The location id.
     * @return Array   List of districts (arrondissements)
     */
    public function getDistricts($locationIdentifier)
    {
        $preQuery = $this->createQueryBuilder('hs')
            ->select("h.district")
            ->innerJoin("HotelBundle:CmsHotel", "h", "WITH", "h.id = hs.hotelId")
            ->where("hs.source = :source")
            ->andWhere("h.district != '' OR h.district IS NOT NULL")
            ->andWhere("hs.locationId = :locationIdentifier")
            ->setParameter(":locationIdentifier", $locationIdentifier)
            ->groupBy("h.district")
            ->setParameter(":source", 'hrs');

        $query = $preQuery->getQuery();
        return $query->getScalarResult();
    }

    /**
     * CMS Hotel Source - This method retrieve a list of hotels/hotel count/hotel max distance for a certain search criteria.
     *
     * @param  HotelSC $hotelSC                 [description]
     * @param  string $selectiveReturn         [description]
     * @param  array  $excludeSourceIdentifier [description]
     * @return Array/Integer    The list of hotels/hotel count/hotel max distance.
     */
    public function getHotelBySearchCriteria($hotelSC, $selectiveReturn = '', $excludeSourceIdentifier = array())
    {
        $preQuery = $this->createQueryBuilder('hs')
            ->innerJoin('HotelBundle:CmsHotel', 'h', 'WITH', 'h.id = hs.hotelId')
            ->innerJoin('HotelBundle:CmsHotelCity', 'chc', 'WITH', 'hs.locationId = chc.locationId')
            ->leftJoin('TTBundle:Webgeocities', 'wgc', 'WITH', 'chc.cityId = wgc.id')
            ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'wgc.countryCode = c.code')
            ->where('hs.source = :source AND hs.isActive = :isActive')
            ->setParameter(':source', 'hrs')
            ->setParameter(':isActive', 1);

        if (!empty($excludeSourceIdentifier)) {
            $preQuery->andWhere('hs.sourceId NOT IN ( :excludeSourceIdentifier )')->setParameter(':excludeSourceIdentifier', $excludeSourceIdentifier);
        }

        // make sure to use location id when making a call to HRS hotelAvail api
        if (!empty($hotelSC->getLocationId())) {
            $preQuery->andWhere('hs.locationId = :locationIdentifier')->setParameter(':locationIdentifier', $hotelSC->getLocationId());
        } elseif (!empty($hotelSC->getLongitude()) && !empty($hotelSC->getLatitude())) {
            $perimeter     = (!empty($hotelSC->getPerimeter())) ? $hotelSC->getPerimeter() : 50000; // 50km is default from hrs
            $perimeter_str = "(
                                            6371000 *
                                            acos(
                                                cos(radians(:latitude)) *
                                                cos(radians(h.latitude)) *
                                                cos(radians(h.longitude) - radians( :longitude )) +
                                                sin(radians(:latitude)) *
                                                sin(radians(h.latitude))
                                            )
                                        )";

            $preQuery->addSelect("{$perimeter_str} AS perimeter") // perimeter is in meters, constant above is meters from center of the earth
                ->setParameter(':longitude', $hotelSC->getLongitude())
                ->setParameter(':latitude', $hotelSC->getLatitude())
                ->andWhere("{$perimeter_str} < :perimeter")
                ->setParameter(':perimeter', $perimeter);
        }

        if (!empty($hotelSC->getNbrStars())) {
            if (in_array('5', $hotelSC->getNbrStars())) {
                $preQuery->andWhere('h.stars IN (:stars) OR h.stars > 5')->setParameter(':stars', $hotelSC->getNbrStars());
            } else {
                $preQuery->andWhere('h.stars IN (:stars)')->setParameter(':stars', $hotelSC->getNbrStars());
            }
        }

        if (!empty($hotelSC->getDistrict())) {
            $preQuery->andWhere('h.district IN (:district)')->setParameter(':district', $hotelSC->getDistrict());
        }

        if (!empty($hotelSC->getDistanceRange())) {
            // Convert values to meters
            $maxDistance = $hotelSC->getMaxDistance() * 1000;
            $min         = $hotelSC->getDistanceRange()[0] * 1000;
            $max         = $hotelSC->getDistanceRange()[1] * 1000;

            if ($min > 0) {
                $preQuery->andWhere('h.distanceFromDowntown >= :minDistance')->setParameter(':minDistance', $min);
            }

            if ($max < $maxDistance) {
                $preQuery->andWhere('h.distanceFromDowntown <= :maxDistance')->setParameter(':maxDistance', $max);
            }
        }

        if ($hotelSC->has360()) {
            $preQuery->andWhere('h.has360 = 1');
        }

        if ($selectiveReturn == 'count') {
            $query = $preQuery->select('count(h.id)')->getQuery();

            return $query->getSingleScalarResult();
        } elseif ($selectiveReturn == 'maxDistance') {
            $query = $preQuery->select('max(h.distanceFromDowntown)')->getQuery();

            return $query->getSingleScalarResult();
        } else {
            $preQuery->select("
                    hs.sourceId as hotelKey,
                    h.id as hotelId,
                    h.name as hotelName,
                    h.district,
                    wgc.name AS city,
                    h.stars as category,
                    h.downtown,
                    h.airport,
                    h.trainStation,
                    h.distanceFromDowntown,
                    h.distanceFromAirport,
                    h.distanceFromTrainStation,
                    (CASE WHEN (c.name is NULL) THEN h.iso3CountryCode ELSE c.name END) AS country,
                    hs.source,
                    h.has360");

            if (!empty($hotelSC->getSortBy())) {
                if ($hotelSC->getSortBy() == 'distance') {
                    // Those that dont have value, move it to the bottom of the result
                    $pushLast = ($hotelSC->getSortOrder() == 'asc') ? 10000000 : -10000000;
                    $preQuery->addSelect("
                            (CASE
                                WHEN (h.distanceFromDowntown is NULL) THEN (h.id + :pushLast)
                                WHEN (h.distanceFromDowntown = 0) THEN (h.id + :pushLast)
                                WHEN (h.distanceFromDowntown = '') THEN (h.id + :pushLast)
                                ELSE h.distanceFromDowntown END
                            ) AS HIDDEN distanceSort")
                        ->setParameter(':pushLast', $pushLast)
                        ->addOrderBy('distanceSort', $hotelSC->getSortOrder());
                } elseif ($hotelSC->getSortBy() == 'category') {
                    $preQuery->addOrderBy('h.stars', $hotelSC->getSortOrder());
                }
            }

            // prioritize hotels with 360 images
            $preQuery->addOrderBy('h.has360', 'DESC');

            // For mobile request, they dont use pagination, so return all
            // Else, return only 2 per page
            if ($hotelSC->getLimit() > 0) {
                $query = $preQuery->setFirstResult(($hotelSC->getPage() - 1) * $hotelSC->getLimit())
                    ->setMaxResults($hotelSC->getLimit())
                    ->getQuery();
            } else {
                $query = $preQuery->getQuery();
            }

            return $query->getScalarResult();
        }
    }

    /**
     * CMS Hotel Source - Get data for a list of hotels or a specific hotel based on hotelId or hotelCode and always put the directly queried hotel on top of the result set (HRS)
     *
     * @param Mixed $sourceIdentifier    The sourceId or List of sourceIds.
     * @param Integer $firstHotelSearched  The sourceId to be first in the list.
     * @param Integer $hotelId  The hotelId to be first in the list
     * @param Integer $limit    The limit.
     * @return Array    hotels.
     */
    public function getHotelBySourceIdentifier($sourceIdentifier, $firstHotelSearched, $hotelId = 0, $limit = 0)
    {
        $preQuery = $this->createQueryBuilder('hs')
            ->select("
                    hs.sourceId as hotelKey,
                    h.id as hotelId,
                    h.name as hotelName,
                    h.district,
                    wgc.name AS city,
                    h.stars as category,
                    h.downtown,
                    h.airport,
                    h.trainStation,
                    h.distanceFromDowntown,
                    h.distanceFromAirport,
                    h.distanceFromTrainStation,
                    (CASE WHEN (c.name is NULL) THEN h.iso3CountryCode ELSE c.name END) AS country,
                    hs.source,
                    h.has360")
            ->innerJoin('HotelBundle:CmsHotel', 'h', 'WITH', 'h.id = hs.hotelId')
            ->innerJoin('HotelBundle:CmsHotelCity', 'chc', 'WITH', 'hs.locationId = chc.locationId')
            ->leftJoin('TTBundle:Webgeocities', 'wgc', 'WITH', 'chc.cityId = wgc.id')
            ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'wgc.countryCode = c.code')
            ->where('hs.source IN(:source) AND hs.isActive = :isActive')
            ->andWhere('hs.sourceId IN ( :sourceIdentifier )')
            ->setParameter(':source', array('hrs', 'tt'))
            ->setParameter(':isActive', 1)
            ->setParameter(':sourceIdentifier', $sourceIdentifier)
            ->addOrderBy('hotelSort', 'DESC');

        if ($firstHotelSearched === -1 and $hotelId > 0) {
            // for now hotels with source_id < 0 are considered hotels belonging to 'tt' source
            $preQuery->addSelect("(CASE WHEN hs.hotelId = :firstHotelSearched THEN 1 ELSE 0 END) AS HIDDEN hotelSort")
                ->setParameter(':firstHotelSearched', $hotelId);
        } else {
            // hotels with source 'hrs'
            $preQuery->addSelect("(CASE WHEN hs.sourceId = :firstHotelSearched THEN 1 ELSE 0 END) AS HIDDEN hotelSort")
                ->setParameter(':firstHotelSearched', $firstHotelSearched);
        }

        // prioritize hotels with 360 images on the list
        $preQuery->addOrderBy('h.has360', 'DESC');

        if ($limit) {
            $preQuery->setMaxResults($limit);
        }

        $result = $preQuery->getQuery()->getScalarResult();
        if (!is_array($sourceIdentifier) && !empty($sourceIdentifier)) {
            $result = $result[0];
        }

        return $result;
    }

    /**
     * CMS Hotel Source - Get a specific hotel source column given a specific param (HRS)
     *
     * @param  String $field  The field to return.
     * @param  Array  $params The field-value pair condition.
     * @return Mixed  The field/column value OR FALSE if not found.
     */
    public function getHotelSourceField($field, $params)
    {
        $query = $this->createQueryBuilder('hs')
            ->select('hs.'.$field)
            ->where('hs.'.$params[0].' = :'.$params[0].' and hs.source IN (:source)')
            ->setParameter(':'.$params[0], $params[1])
            ->setParameter(':source', array('hrs', 'tt'))
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0][$field];
        } else {
            return false;
        }
    }

    /**
     * Adding a new cms hotel image.
     *
     * @param integer $user_id
     * @param integer $hotel_id
     * @param string $filename
     * @param string $location
     * @return hotelImageId
     */
    public function addHotelImage($user_id, $hotel_id, $filename, $location)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('HotelBundle:CmsHotelImage')->getFieldNames();

        $hotelImage = new CmsHotelImage();
        $hotelImage->setUserId($user_id);
        $hotelImage->setHotelId($hotel_id);
        $hotelImage->setFilename($filename);
        $hotelImage->setHotelDivisionId(NULL);
        $hotelImage->setMediaTypeId(1);
        $hotelImage->setMediaSettings(NULL);
        $hotelImage->setLocation($location);
        $hotelImage->setDefaultPic(0);
        $hotelImage->setFeatured(0);
        $hotelImage->setSortOrder(999);
        $em->persist($hotelImage);
        $em->flush();

        if ($hotelImage->getId()) {
            return $hotelImage->getId();
        } else {
            return false;
        }
    }

    /**
     * Get CmsHotelCity info
     *
     * @param  $cityId The cityId
     * @param  $locationId The locationId
     *
     * @return The table row info
     */
    public function getCmsHotelCityInfo($cityId = 0, $locationId = 0)
    {
        $qb = $this->createQueryBuilder('hc');

        if ($cityId != 0) {
            $qb->where("hc.cityId=:CityId")
                ->setParameter(':CityId', $cityId);
        } else if ($locationId != 0) {
            $qb->where("hc.locationId=:LocationId")
                ->setParameter(':LocationId', $locationId);
        }

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * HotelSelectedCityImage - Get Selected Images of Hotels-In
     *
     * @param  $HotelSelectedCityId
     *
     * @return
     */
    public function getHotelSelectedCityImages($HotelSelectedCityId)
    {
        $qb = $this->createQueryBuilder('hc')
            ->select('hc, h, h.street AS street, h.district AS district, h.zipCode AS zipCode, wgc.name AS city, c.name AS country')
            ->innerJoin('HotelBundle:CmsHotel', 'h', 'WITH', 'h.id = hc.hotelId')
            ->innerJoin('HotelBundle:CmsHotelSource', 'hs', 'WITH', 'h.id = hs.hotelId')
            ->innerJoin('HotelBundle:CmsHotelCity', 'chc', 'WITH', 'hs.locationId = chc.locationId')
            ->leftJoin('TTBundle:Webgeocities', 'wgc', 'WITH', 'chc.cityId = wgc.id')
            ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=h.countryCode')
            ->where('hc.hotelSelectedCityId=:Id AND hc.published=1')
            ->setParameter(':Id', $HotelSelectedCityId)
            ->orderBy("hc.displayOrder", "DESC");

        $query = $qb->getQuery();

        return $query->getScalarResult();
    }

    /**
     * HotelSelectedCity - Get Selected Hotel City Id
     *
     * @param  $srch_options
     *
     * @return
     */
    public function getHotelSelectedCityId($srch_options = array())
    {
        $default_opts = array(
            'limit' => null,
            'id' => 0,
            'country_code' => ''
        );
        $options      = array_merge($default_opts, $srch_options);

        $qb = $this->createQueryBuilder('hc')
            ->select('hc,w,c,s');
        $qb->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id = hc.cityId AND hc.cityId != 0 AND hc.cityId IS NOT NULL');
        $qb->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=w.countryCode OR c.code=hc.countryCode');
        $qb->leftJoin('TTBundle:States', 's', 'WITH', 's.countryCode=w.countryCode AND s.stateCode=w.stateCode ');
        $qb->where('hc.published=1');

        if ($options['id'] > 0) {
            $qb->andwhere("hc.cityId=:Id");
            $qb->setParameter(':Id', $options['id']);
        } else if ($options['country_code']) {
            $qb->andwhere("hc.countryCode=:CountryCode");
            $qb->setParameter(':CountryCode', $options['country_code']);
        }

        if ($options['limit'] != null) {
            $qb->setMaxResults(intval($options['limit']));
        }

        $query = $qb->getQuery();

        return $query->getScalarResult();
    }

    /**
     * CmsHotelImage - Deletes an image added by a user
     *
     * @param  $id
     * @param  $userId
     *
     * @return
     */
    public function deleteUserAddedImage($id, $userId)
    {
        $qb = $this->createQueryBuilder('UP')
            ->delete('HotelBundle:CmsHotelImage', 'v')
            ->where("v.id = :ID AND v.userId=:UserId")
            ->setParameter(':ID', $id)
            ->setParameter(':UserId', $userId);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    //********************************************************************************************
    // 360 Tour Methods
    /**
     * This method returns all the divisions of a certain hotel with the available 360 media
     *
     * @param integer $hotelId
     * @param integer $mediaType
     * @param integer $categoryId
     * @param integer $divisionId
     * @param boolean $withSubDivisions
     * @param boolean $sortByGroup
     *
     * @return list
     */
    public function getHotelDivisions($hotelId, $mediaType, $categoryId, $divisionId, $withSubDivisions, $sortByGroup)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = 'SELECT
                    h.id AS hotel_id,
                    h.name AS hotel_name,
                    wc.country_code AS hotel_country_code,
                    h.logo AS hotel_logo,
                    hdc.id AS category_id,
                    IF( (hdc.id IS NULL OR hhdc.name is null OR hhdc.name = \'\') , hdc.name, hhdc.name) AS category_name,
                    cg.id AS group_id,
                    cg.name AS group_name,
                    hd.id AS id,
                    IF( (hd.id IS NULL OR hhd.name is null OR hhd.name = \'\') , hd.name, hhd.name) AS name,
                    hd.parent_id AS parent_id,
                    IF( (phd.name IS NULL OR phd.name = \'\') , phd.name, phhd.name) AS parent_name,
                    hi.filename AS image,
                    hi.tt_media_type_id AS media_type,
                    hi.media_settings,
                    hi.default_pic AS is_main_image
                FROM
                    cms_hotel_source hs,
                    cms_hotel_city hc,
                    webgeocities wc,
                    hotel_divisions_categories hdc
                        LEFT JOIN(hotel_divisions_categories_group cg) ON (cg.id = hdc.hotel_division_category_group_id),
                    cms_hotel h,
                    (hotel_to_hotel_divisions hhd, hotel_to_hotel_divisions_categories hhdc)
                        LEFT JOIN(hotel_divisions hd) ON (hhdc.hotel_division_category_id = hd.hotel_division_category_id AND hhd.hotel_division_id = hd.id)
                        LEFT JOIN (cms_hotel_image hi) ON (hi.hotel_id = hhd.hotel_id AND hi.hotel_division_id = hd.id AND hi.tt_media_type_id = '.$mediaType.')

                        LEFT JOIN (hotel_divisions phd) ON (hd.parent_id = phd.id)
                        LEFT JOIN (hotel_to_hotel_divisions phhd) ON (phd.id = phhd.hotel_division_id AND phhd.hotel_id = hhd.hotel_id)
                WHERE
                    h.id = hs.hotel_id
                    AND hs.location_id = hc.location_id
                    AND hc.city_id = wc.id
                    AND h.id = hhdc.hotel_id
                    AND h.id = hhd.hotel_id
                    AND hhdc.hotel_division_category_id = hdc.id
                    AND hhd.hotel_division_id = hd.id';

        if (isset($categoryId) && !empty($categoryId)) {
            $sql = $sql.' AND hdc.id = \''.$categoryId.'\'';
        }

        if (isset($divisionId) && !empty($divisionId)) {
            if ($withSubDivisions == false) {
                $sql = $sql.' AND hd.id = \''.$divisionId.'\'';
            } else {
                $sql = $sql.' AND (hd.id = \''.$divisionId.'\' OR hd.parent_id = \''.$divisionId.'\')';
            }
        } else {
            if ($withSubDivisions == false) {
                $sql = $sql.' AND (hd.parent_id IS NULL OR hd.parent_id = \'\' )';
            }
        }

        $sql = $sql.' AND h.id = \''.$hotelId.'\' ORDER BY '.($sortByGroup ? ' cg.sort_order ASC, ' : '').' hhd.sort_order ASC, hd.sort_order ASC, name ASC';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * This method retrieves the hotel images by mediaType
     *
     * @param Integer $hotelId
     * @param Integer $mediaType
     *
     * @return list
     */
    public function getHotelImagesByType($hotelId, $mediaType)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT hi.hotel_id AS hotelId,
			h.name AS hotelName,
			wc.country_code AS countryCode,
			hi.filename AS imageSource,
			hi.default_pic AS isMainImage,
			hi.hotel_division_id AS divisionId,
			IF( (hd.id IS NULL OR hhd.name IS NULL OR hhd.name = '') , hd.name, hhd.name) AS divisionName,
			hdc.name AS categoryName,
			hd.hotel_division_category_id AS categoryId,
			hd.parent_id AS parentId,
			phd.name AS parentName,
			phd.sort_order p_order,
			hhd.sort_order
			FROM cms_hotel_image hi
			LEFT JOIN hotel_to_hotel_divisions hhd ON (hhd.hotel_id = $hotelId AND hhd.hotel_division_id = hi.hotel_division_id)
			LEFT JOIN hotel_divisions hd ON (hi.hotel_division_id = hd.id AND hi.hotel_id = $hotelId)
			LEFT JOIN hotel_divisions_categories hdc ON (hd.hotel_division_category_id = hdc.id)
			LEFT JOIN hotel_to_hotel_divisions phd ON (hd.parent_id = phd.hotel_division_id AND phd.hotel_id = hhd.hotel_id)
			INNER JOIN cms_hotel h ON (hi.hotel_id = h.id)
			INNER JOIN cms_hotel_source hs ON (hi.hotel_id = hs.hotel_id)
			INNER JOIN cms_hotel_city hc ON (hs.location_id = hc.location_id)
			INNER JOIN webgeocities wc ON (hc.city_id = wc.id AND wc.country_code IS NOT NULL)
			WHERE hi.hotel_id = $hotelId AND hi.tt_media_type_id = $mediaType AND hi.is_featured = 1
			ORDER BY hi.sort_order ASC, hi.is_featured DESC, phd.sort_order ASC, hhd.sort_order ASC, hd.sort_order ASC, hd.parent_id ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * This method retrieves a related Things-To-Do per hotel city
     *
     * @param Integer $hotelId
     *
     * @return list
     */
    public function getHotel360ThingsToDo($hotelId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = 'SELECT
	            ttd.title
                FROM
                    cms_hotel h
                    LEFT JOIN (cms_hotel_source hs) ON (h.id = hs.hotel_id)
                    LEFT JOIN (cms_hotel_city hc) ON (hs.location_id = hc.location_id)
                    LEFT JOIN (webgeocities wc) ON (hc.city_id = wc.id)
                    JOIN (cms_thingstodo ttd) ON (wc.id = ttd.city_id)
                WHERE
                    h.id = '.$hotelId.' LIMIT 1';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }
}
