<?php

namespace HotelBundle\Repository;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;
use HotelBundle\Entity\HotelReservation;
use HotelBundle\Entity\HotelRoomReservation;
use HotelBundle\Entity\HotelSearchRequest;
use HotelBundle\Entity\HotelSearchResponse;
use HotelBundle\Model\HotelTeaserData;

class HotelRepository extends EntityRepository implements ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Hotel Reservation - Get one row from hotel_reservation table based on reference
     *
     * @param  String           $reference The reference
     * @return HotelReservation
     */
    public function getHotelReservation($reference)
    {
        $query = $this->createQueryBuilder('hr')
            ->where('hr.reference = :reference')
            ->setParameter(':reference', $reference)
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
     * Hotel Reservation - Save entry to hotel_reservation table
     *
     * @param  HotelBookingCriteria $hotelBC The HotelBooking object.
     * @param  Integer              $id      The reservation id. (Optional)
     * @return HotelReservation
     */
    public function saveReservation($hotelBC, $id = null)
    {
        $em = $this->getEntityManager();

        if (!empty($id)) {
            $obj = $this->find($id);
            $obj->setReservationStatus('Modified');
        } else {
            if (!empty($hotelBC->getReference())) {
                $reference = $hotelBC->getReference();
            } else {
                $reference = bin2hex(openssl_random_pseudo_bytes(16)); //used in web/email URLs
            }
            $reservationDate   = new \DateTime("now");
            $reservationStatus = (!empty($hotelBC->getReservationStatus())) ? $hotelBC->getReservationStatus() : 'Confirmed';

            $obj = new HotelReservation();
            $obj->setCreationDate($reservationDate);
            $obj->setHotelId($hotelBC->getHotelId());
            $obj->setReservationStatus($reservationStatus);
            $obj->setReference($reference);
            $obj->setTransactionSourceId($hotelBC->getTransactionSourceId());

            if ($hotelBC->getPageSource() != 'HRS_DIRECT') {
                $obj->setSource($hotelBC->getSource());
            }
        }

        $fromDate = new \DateTime($hotelBC->getFromDate());
        $toDate   = new \DateTime($hotelBC->getToDate());
        $obj->setFromDate($fromDate);
        $obj->setToDate($toDate);

        $orderer = $hotelBC->getOrderer();
        $obj->setUserId($hotelBC->getUserId());
        $obj->setTitle($orderer->getTitle());
        $obj->setFirstName($orderer->getFirstName());
        $obj->setLastName($orderer->getLastName());
        $obj->setEmail($orderer->getEmail());
        $obj->setCountry($orderer->getCountry());
        $obj->setDialingCode((!empty($orderer->getDialingCode())) ? $orderer->getDialingCode() : '');
        $obj->setMobile((!empty($orderer->getPhone())) ? $orderer->getPhone() : '');

        $obj->setDoubleRooms($hotelBC->getDoubleRooms());
        $obj->setSingleRooms($hotelBC->getSingleRooms());

        $obj->setHotelGrandTotal($hotelBC->getHotelGrandTotal());
        $obj->setHotelCurrency($hotelBC->getHotelCurrency());
        $obj->setCustomerGrandTotal($hotelBC->getCustomerGrandTotal());
        $obj->setCustomerCurrency($hotelBC->getCustomerCurrency());

        if (!empty($hotelBC->getAmountFbc())) {
            $obj->setAmountFbc($hotelBC->getAmountFbc());
        }

        if (!empty($hotelBC->getAmountSbc())) {
            $obj->setAmountSbc($hotelBC->getAmountSbc());
        }

        if (!empty($hotelBC->getAccountCurrencyAmount())) {
            $obj->setAccountCurrencyAmount($hotelBC->getAccountCurrencyAmount());
        }

        $obj->setControlNumber($hotelBC->getControlNumber());
        $obj->setDetails(json_encode($hotelBC->getDetails()));

        if (!$obj->getId()) {
            $em->persist($obj);
        }
        $em->flush();

        return $obj;
    }

    /**
     * Hotel Room Reservation - Get all rooms associated to a specific hotel_reservation
     *
     * @param  Integer          $reservationId The reservation id.
     * @return HotelReservation
     */
    public function findByReservationId($reservationId)
    {
        $query = $this->createQueryBuilder('hrr')
            ->where('hrr.hotelReservationId = :reservationId')
            ->setParameter(':reservationId', $reservationId)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Hotel Room Reservation - Get a specific room based on it's reservation_key
     *
     * @param  String $reservationKey The room reservation key.
     * @return Mixed  The HotelRoomReservation OR Boolean FALSE if not found.
     */
    public function findByReservationKey($reservationKey)
    {
        $query = $this->createQueryBuilder('hrr')
            ->where('hrr.reservationKey = :reservationKey')
            ->setParameter(':reservationKey', $reservationKey)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Hotel Room Reservation - Get all non-cancelled rooms for a specific hotel reservation
     *
     * @param  Integer                $reservationID The reservation id.
     * @return HotelRoomReservation[]
     */
    public function getActiveRooms($reservationID)
    {
        $query = $this->createQueryBuilder('hrr')
            ->where('hrr.hotelReservationId = :reservationID AND hrr.roomStatus != :roomStatus')
            ->setParameter(':reservationID', $reservationID)
            ->setParameter(':roomStatus', 'Canceled')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Hotel Room Reservation - Get all canceled rooms for a specific hotel reservation
     *
     * @param  Integer                $reservationID The reservation id.
     * @return HotelRoomReservation[]
     */
    public function getCanceledRooms($reservationID)
    {
        $query = $this->createQueryBuilder('hrr')
            ->where('hrr.hotelReservationId = :reservationID AND hrr.roomStatus = :roomStatus')
            ->setParameter(':reservationID', $reservationID)
            ->setParameter(':roomStatus', 'Canceled')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Hotel Room Reservation - Save entry to hotel_room_reservation table
     *
     * @param  Array                $data The hotel room reservation data.
     * @param  HotelRoomReservation $obj  Is optional; and by default = null.
     * @return HotelRoomReservation
     */
    public function saveRoomReservation($data, $obj = null)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('HotelBundle:HotelRoomReservation')->getFieldNames();

        if (is_null($obj)) {
            $obj = $this->findOneBy(array(
                'hotelReservationId' => $data['hotelReservationId'],
                'reservationKey' => $data['reservationKey']
            ));

            if (empty($obj)) {
                $obj = new HotelRoomReservation();
            }
        }

        foreach ($data as $column => $value) {
            if (in_array($column, $tableFields)) {
                $func = 'set'.ucwords($column);
                $obj->{$func}($value);
            }
        }

        $em->persist($obj);
        $em->flush();

        return $obj;
    }

    /**
     * Hotel Search Request - Delete a row from hotel_search_request given its unique id
     *
     * @param  Integer $id The hotel search request id
     * @return Boolean true
     */
    public function deleteHotelSearchRequest($id)
    {
        $entity = $this->find($id);
        $em     = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();

        return true;
    }

    /**
     * Hotel Search Request - (HRS/Amadeus) Get one row from hotel_search_request based on given params
     *
     * @param  HotelSC $hotelSC HotelSC object.
     * @return Mixed   The HotelSearchRequest OR Boolean FALSE if not found.
     */
    public function getHotelSearchRequest($hotelSC)
    {
        $fromDate = new \DateTime($hotelSC->getFromDate());
        $toDate   = new \DateTime($hotelSC->getToDate());

        $query = $this->createQueryBuilder('req')
            ->where('req.hotelId = :hotelId')
            ->andWhere('req.hotelCityName = :hotelCityName')
            ->andWhere('req.country = :country')
            ->andWhere('req.longitude = :longitude')
            ->andWhere('req.latitude = :latitude')
            ->andWhere('req.entityType = :entityType')
            ->andWhere('req.fromDate = :fromDate')
            ->andWhere('req.toDate = :toDate')
            ->andWhere('req.singleRooms = :singleRooms')
            ->andWhere('req.doubleRooms = :doubleRooms')
            ->andWhere('req.adultCount = :adultCount')
            ->andWhere('req.childCount = :childCount')
            ->setParameter(':hotelId', $hotelSC->getHotelId())
            ->setParameter(':hotelCityName', $hotelSC->getCity()->getName())
            ->setParameter(':country', $hotelSC->getCountry())
            ->setParameter(':longitude', $hotelSC->getLongitude())
            ->setParameter(':latitude', $hotelSC->getLatitude())
            ->setParameter(':entityType', $hotelSC->getEntityType())
            ->setParameter(':fromDate', $fromDate)
            ->setParameter(':toDate', $toDate)
            ->setParameter(':singleRooms', $hotelSC->getSingleRooms())
            ->setParameter(':doubleRooms', $hotelSC->getDoubleRooms())
            ->setParameter(':adultCount', $hotelSC->getAdultCount())
            ->setParameter(':childCount', $hotelSC->getChildCount());

        if ($hotelSC->getLocationId()) {
            // HRS
            $query->andWhere('req.locationId = :locationId')->setParameter(':locationId', $hotelSC->getLocationId());
        } elseif (!empty($hotelSC->getCity()->getCode())) {
            // Amadeus
            $query->andWhere('req.hotelCityCode = :hotelCityCode')->setParameter(':hotelCityCode', $hotelSC->getCity()->getCode());
        }

        $result = $query->addOrderBy('req.creationDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Hotel Search Request - Save entry to hotel_search_request
     *
     * @param Integer   The primary key.
     * @return HotelSearchRequest
     */
    public function getHotelSearchRequestById($hotelSearchRequestId)
    {
        $query = $this->createQueryBuilder('req')
            ->where('req.id = :id')
            ->setParameter(':id', $hotelSearchRequestId)
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
     * Hotel Search Request - Save entry to hotel_search_request
     *
     * @param  Array              $data The data.
     * @return HotelSearchRequest
     */
    public function insertHotelSearchRequest($data)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('HotelBundle:HotelSearchRequest')->getFieldNames();

        $obj = new HotelSearchRequest();

        $obj->setCreationDate(new \DateTime());
        $obj->setFromDate(new \DateTime($data['fromDate']));
        $obj->setToDate(new \DateTime($data['toDate']));

        unset($data['fromDate']);
        unset($data['toDate']);

        foreach ($data as $column => $value) {
            if (in_array($column, $tableFields)) {
                $func = 'set'.ucwords($column);
                $obj->{$func}($value);
            }
        }

        $em->persist($obj);
        $em->flush();

        return $obj;
    }

    /**
     * Hotel Search Response - Get list of hotels for a specific search criteria.
     *
     * @param HotelSC $hotelSC
     * @param String $targetField
     * @return Array    hotels.
     */
    public function getHotelSearchResponse($hotelSC, $targetField = '')
    {
        $preQuery = $this->createQueryBuilder('res')
            ->where('res.hotelSearchRequestId = :reqId')
            ->setParameter(':reqId', $hotelSC->getHotelSearchRequestId());

        if (!empty($hotelSC->getNbrStars())) {
            if (in_array('5', $hotelSC->getNbrStars())) {
                $preQuery->andWhere('res.category IN (:category) OR res.category > 5')->setParameter(':category', $hotelSC->getNbrStars());
            } else {
                $preQuery->andWhere('res.category IN (:category)')->setParameter(':category', $hotelSC->getNbrStars());
            }
        }

        if (!empty($hotelSC->getDistrict())) {
            $preQuery->andWhere('res.district IN (:district)')->setParameter(':district', $hotelSC->getDistrict());
        }

        if (!empty($hotelSC->getPriceRange())) {
            if ($hotelSC->getPriceRange()[0] > 0) {
                $preQuery->andWhere('res.price >= :minPrice')->setParameter(':minPrice', $hotelSC->getPriceRange()[0]);
            }
            if ($hotelSC->getPriceRange()[1] < $hotelSC->getMaxPrice()) {
                $preQuery->andWhere('res.price <= :maxPrice')->setParameter(':maxPrice', $hotelSC->getPriceRange()[1]);
            }
        }

        if (!empty($hotelSC->getBudgetRange())) {
            $budgetQuery = '';
            foreach ($hotelSC->getBudgetRange() as $key => $value) {
                list($min, $max) = explode('-', $value);
                if ($max == '+') {
                    $budgetQuery .= ' (res.avgPrice >= :min'.$key.') OR';
                    $preQuery->setParameter(':min'.$key, $min);
                } else {
                    $budgetQuery .= ' (res.avgPrice BETWEEN :min'.$key.' AND :max'.$key.') OR';
                    $preQuery->setParameter(':min'.$key, $min)->setParameter(':max'.$key, $max);
                }
            }
            $preQuery->andWhere(substr($budgetQuery, 0, -3));
        }

        if (!empty($hotelSC->getDistanceRange())) {
            if ($hotelSC->getDistanceRange()[0] > 0) {
                $preQuery->andWhere('res.distance >= :minDistance')->setParameter(':minDistance', $hotelSC->getDistanceRange()[0]);
            }
            if ($hotelSC->getDistanceRange()[1] < $hotelSC->getMaxDistance()) {
                $preQuery->andWhere('res.distance <= :maxDistance')->setParameter(':maxDistance', $hotelSC->getDistanceRange()[1]);
            }
        }

        if ($hotelSC->isCancelable()) {
            $preQuery->andWhere('res.cancelable > 0');
        }

        if ($hotelSC->hasBreakfast()) {
            $preQuery->andWhere('res.breakfast > 0');
        }

        if ($hotelSC->has360()) {
            $preQuery->andWhere('res.has360 = 1');
        }

        if (!empty($targetField)) {
            $query = $preQuery->select($targetField)->setMaxResults(1)->getQuery();

            return $query->getSingleScalarResult();
        } else {
            $preQuery->select("
                res.hotelSearchRequestId,
                res.hotelId,
                res.hotelKey,
                res.hotelCode,
                res.hotelName,
                res.hotelNameURL,
                res.category,
                res.district,
                res.city,
                res.country,
                res.isoCurrency,
                res.price,
                res.avgPrice,
                res.distance,
                res.distances,
                res.mapImageUrl,
                res.mainImage,
                res.mainImageMobile,
                res.cancelable,
                res.breakfast,
                res.has360");

            if (!empty($hotelSC->getSortBy())) {
                if ($hotelSC->getSortBy() == 'distance') {
                    // Those that dont have value, move it to the bottom of the result
                    $pushLast = ($hotelSC->getSortOrder() == 'asc') ? 1000000 : -1000000;
                    $preQuery->addSelect("
                        (CASE
                        WHEN res.distance = 0 THEN (res.id + :pushLast)
                        ELSE res.distance END) AS HIDDEN distanceSort
                    ")->setParameter(':pushLast', $pushLast);
                    $preQuery->addOrderBy('distanceSort', $hotelSC->getSortOrder());
                } else {
                    $preQuery->addOrderBy('res.'.$hotelSC->getSortBy(), $hotelSC->getSortOrder());
                }
            }

            $preQuery->addOrderBy('res.has360', 'DESC');

            if (!$hotelSC->isFromMobile()) {
                $preQuery->setFirstResult(($hotelSC->getPage() - 1) * $hotelSC->getLimit())->setMaxResults($hotelSC->getLimit());
            }
            $query = $preQuery->getQuery();

            return $query->getScalarResult();
        }
    }

    /**
     * Hotel Search Response - Get all hotel_search_response for a specific search request with some additional params (filters / sort / page) specific to TTApi usage
     * This method also fill-in empty spots on our results so that pagination will work.
     *
     * @param  HotelSC $hotelSC HotelSC object.
     * @param  Boolean $isRest  Determines if used via REST.
     * @return Array   The list of (Array) HotelSearchResponse
     */
    public function getHotelSearchResponseTTApi(\HotelBundle\Model\HotelSC $hotelSC, $isRest = false)
    {
        $toReturn = array();

        $where       = array();
        $whereParams = array();

        $uWhere       = array();
        $uWhereParams = array();

        $uHaving       = array();
        $uHavingParams = array();

        $where[]       = 'res.hotel_search_request_id = ?';
        $whereParams[] = $hotelSC->getHotelSearchRequestId();

        $allowUnion = $hotelSC->isUseTTApi();

        if (!empty($hotelSC->getNbrStars())) {
            $whereStmt = 'res.category IN('.preg_replace("/[^\,]+/", '?', implode(',', $hotelSC->getNbrStars())).')';
            if (in_array('5', $hotelSC->getNbrStars())) {
                $whereStmt .= ' OR res.category > 5';
            }

            $whereStmt = '('.trim($whereStmt).')';

            $where[]     = $whereStmt;
            $whereParams = array_merge($whereParams, $hotelSC->getNbrStars());

            $uWhere[]     = str_replace('res.category', 'ah.stars', $whereStmt);
            $uWhereParams = array_merge($uWhereParams, $hotelSC->getNbrStars());
        }

        if (!empty($hotelSC->getDistrict())) {
            $whereStmt = 'res.district IN ('.preg_replace("/[^\,]+/", '?', implode(',', $hotelSC->getDistrict())).')';

            $where[]     = $whereStmt;
            $whereParams = array_merge($whereParams, $hotelSC->getDistrict());

            $uWhere[]     = str_replace('res.district', 'ah.district', $whereStmt);
            $uWhereParams = array_merge($uWhereParams, $hotelSC->getDistrict());
        }

        if (!empty($hotelSC->getPriceRange())) {
            $whereStmt      = '';
            $whereStmtParam = '';

            // min price
            $whereStmt      = 'res.price >= ?';
            $whereStmtParam = $hotelSC->getPriceRange()[0];

            $where[]       = $whereStmt;
            $whereParams[] = $whereStmtParam;

            // Do not put our union query when we have a price to consider for a certain scenario
            if (($hotelSC->getPriceRange()[0] != 0 && $hotelSC->getPriceRange()[1] != $hotelSC->getMaxPrice()) || $isRest) {
                $allowUnion = false;
            }

            // max price
            if ($hotelSC->getPriceRange()[1] < $hotelSC->getMaxPrice()) {
                $whereStmt      = 'res.price <= ?';
                $whereStmtParam = $hotelSC->getPriceRange()[1];

                $where[]       = $whereStmt;
                $whereParams[] = $whereStmtParam;
            }
        }

        if (!empty($hotelSC->getBudgetRange())) {
            // Do not put our union query when we have a minimum and maximum budget range
            $allowUnion = false;

            $budgetQuery = '';
            foreach ($hotelSC->getBudgetRange() as $key => $value) {
                list($min, $max) = explode('-', $value);

                if ($max == '+') {
                    $budgetQuery   .= ' (res.avg_price >= ?) OR ';
                    $whereParams[] = $min;
                } else {
                    $budgetQuery   .= ' (res.avg_price BETWEEN ? AND ?) OR ';
                    $whereParams[] = $min;
                    $whereParams[] = $max;
                }
            }

            $whereStmt = '('.trim(substr($budgetQuery, 0, -3)).')';

            $where[] = $whereStmt;
            //$uHaving[] = str_replace('res.avg_price', 'avgPrice', $whereStmt);
        }

        if (!empty($hotelSC->getDistanceRange())) {
            $whereStmt      = '';
            $whereStmtParam = '';

            // Do not put our union query when we have a distance to consider for a certain scenario
            if (($hotelSC->getDistanceRange()[0] != 0 && $hotelSC->getDistanceRange()[1] != $hotelSC->getMaxDistance()) || $isRest) {
                $allowUnion = false;
                $where[]    = "res.distances <> ''";
            }

            // min distance
            $whereStmt      = 'res.distance >= ?';
            $whereStmtParam = $hotelSC->getDistanceRange()[0];

            $where[]       = $whereStmt;
            $whereParams[] = $whereStmtParam;

            $uHaving[]       = str_replace('res.distance', 'distance', $whereStmt);
            $uHavingParams[] = $whereStmtParam;

            // max distance
            if ($hotelSC->getDistanceRange()[1] < $hotelSC->getMaxDistance()) {
                $whereStmt      = 'res.distance <= ?';
                $whereStmtParam = $hotelSC->getDistanceRange()[1];

                $where[]       = $whereStmt;
                $whereParams[] = $whereStmtParam;

                $uHaving[]       = str_replace('res.distance', 'distance', $whereStmt);
                $uHavingParams[] = $whereStmtParam;
            }
        }

        if (!empty($hotelSC->isCancelable())) {
            if ($hotelSC->isCancelable() > 0) {
                $where[] = 'res.cancelable > 0';

                // no need for union query since union records are not-available hotels
                $allowUnion = false;
                //$uHaving[] = 'cancelable > 0';
            }
        }

        if (!empty($hotelSC->hasBreakfast())) {
            if ($hotelSC->hasBreakfast() > 0) {
                $where[] = 'res.breakfast > 0';

                // no need for union query since union records are not-available hotels
                $allowUnion = false;
                //$uHaving[] = 'breakfast > 0';
            }
        }

        if ($hotelSC->has360()) {
            $where[] = 'res.has_360 = 1';

            // no need for union query since union records are not-available hotels
            $allowUnion = false;
        }

        $select            = array();
        $orderBy           = array();
        $offsetLimitStmt   = '';
        $unionColumnFilter = array();

        $select[] = "
                res.hotel_search_request_id AS hotelSearchRequestId,
                res.hotel_id AS hotelId,
                res.hotel_key AS hotelKey,
                res.hotel_code AS hotelCode,
                res.hotel_name AS hotelName,
                res.hotel_name_clean_title AS hotelNameURL,
                res.category,
                res.district,
                c.id AS amadeuHotelCityId,
                c.city_id AS cityId,
                c.city_code AS cityCode,
                wgc.name AS city,
                cc.name AS country,
                res.iso_currency AS currencyCode,
                res.price,
                res.avg_price AS avgPrice,
                res.distance,
                res.distances,
                res.map_image_url AS mapImageUrl,
                res.main_image AS mainImage,
                res.main_image_mobile AS mainImageMobile,
                res.cancelable,
                res.breakfast,
                res.has_360 AS has360";

        if (!empty($hotelSC->getSortBy())) {
            // Those that dont have value, move it to the bottom of the result
            $pushLast = ($hotelSC->getSortOrder() == 'asc') ? 1000000 : -1000000;

            switch ($hotelSC->getSortBy()) {
                case 'distance':
                    $select[] = "
                        (CASE
                        WHEN res.distance = 0 THEN (res.id + {$pushLast})
                        ELSE res.distance END) AS distanceSort ";

                    $unionColumnFilter[] = "$pushLast AS distanceSort";

                    $orderBy[] = "distanceSort {$hotelSC->getSortOrder()}";
                    break;
                case 'price':
                    $select[]  = "
                        (CASE
                        WHEN res.price = 0 THEN (res.id + {$pushLast})
                        ELSE res.price END) AS priceSort ";

                    $unionColumnFilter[] = "$pushLast AS priceSort";

                    $orderBy[] = "priceSort {$hotelSC->getSortOrder()}";
                    break;
                default:
                    $sortBy    = trim($hotelSC->getSortBy());
                    $orderBy[] = "res.{$sortBy} {$hotelSC->getSortOrder()}";
            }
        }

        // prioritize hotels with 360 images
        $orderBy[] = "res.has_360 DESC";

        $offset          = ($hotelSC->getPage() - 1) * $hotelSC->getLimit();
        $offsetLimitStmt = "LIMIT {$offset}, {$hotelSC->getLimit()}";

        // initialize sql
        $sql = "SELECT ".implode(' , ', $select)." ";
        $sql .= "FROM hotel_search_response res
                    INNER JOIN amadeus_hotel h
                        ON res.hotel_id = h.id
                    INNER JOIN amadeus_hotel_city c
                        ON h.amadeus_city_id = c.id
                    LEFT OUTER JOIN webgeocities wgc
                        ON c.city_id = wgc.id
                    LEFT OUTER JOIN cms_countries cc
                        ON wgc.country_code = cc.code ";
        $sql .= ((count($where) > 0) ? "WHERE ".implode(" AND ", $where) : "")." ";

        // we don't allow union when entityType is not equal to SOCIAL_ENTITY_HOTEL and SOCIAL_ENTITY_CITY (searching via longitude and latitude)
        if ($hotelSC->isGeoLocationSearch()) {
            $allowUnion = false;
        }

        // append hotels that should be included when we did search but was not yet inserted on our hotel_search_response table
        if ($allowUnion) {
            if (!empty($hotelSC->getCity()->getCode())) {
                $uWhere[]       = 'ahc.city_code LIKE ?';
                $uWhereParams[] = $hotelSC->getCity()->getCode();
            } elseif (!empty($hotelSC->getCity()->getId())) {
                $uWhere[]       = 'ahc.city_id = ?';
                $uWhereParams[] = $hotelSC->getCity()->getId();
            }

            if (count($where)) {
                $whereStmt = implode(" AND ", $where);
                $whereStmt = "WHERE ".str_replace('res.', 'hsr.', $whereStmt);

                $whereStmt = "
                    ahs.hotel_id NOT IN (
                        SELECT
                            hsr.hotel_id
                        FROM hotel_search_response hsr
                        {$whereStmt}
                    )";

                $uWhere[]     = $whereStmt;
                $uWhereParams = array_merge($uWhereParams, $whereParams);
            }

            // only include non-available hotels that are published=1.
            $uWhere[] = "ahs.published = 1";

            $whereStmt = "";
            if (count($uWhere) > 0) {
                $whereStmt = "WHERE ".implode(" AND ", $uWhere)." ";
            }

            $havingStmt = '';
            if (count($uHaving) > 0) {
                $havingStmt = "HAVING ".implode(" AND ", $uHaving)." ";
            }

            $unionColumnFilter = (count($unionColumnFilter) > 0) ? (", ".implode(", ", $unionColumnFilter)) : "";

            $sql = "
                ({$sql})

                UNION

                (SELECT
                    0 AS hotelSearchRequestId,
                    ahs.hotel_id AS hotelId,
                    '' AS hotelKey,
                    ahs.hotel_code AS hotelCode,
                    ah.property_name AS hotelName,
                    ah.property_name AS hotelNameURL,
                    ah.stars AS category,
                    ah.district AS district,
                    ahc.id AS amadeuHotelCityId,
                    ahc.city_id AS cityId,
                    ahc.city_code AS cityCode,
                    wgc.name AS city,
                    cc.name AS country,
                    '' AS currencyCode,
                    0.00 AS price,
                    0.00 AS avgPrice,

                    IFNULL(
                        (SELECT
                            TRUNCATE((poi.distance/1000.00), 2) AS poi_distance
                         FROM hotel_poi poi
                         INNER JOIN distance_poi_type dpt
                            ON poi.distance_poi_type_id = dpt.id
                         WHERE
                            dpt.name LIKE 'downtown'
                         AND poi.hotel_id = ahs.hotel_id
                         LIMIT 1)
                         , 0.00
                    ) AS distance,

                    '' AS distances,
                    '' AS mapImageUrl,
                    '' AS mainImage,
                    '' AS mainImageMobile,
                    0 AS cancelable,
                    0 AS breakfast,
                    ah.has_360 AS has360
                    $unionColumnFilter
                FROM amadeus_hotel_source ahs
                INNER JOIN amadeus_hotel ah
                    ON ahs.hotel_id = ah.id
                INNER JOIN amadeus_hotel_city ahc
                    ON ah.amadeus_city_id = ahc.id
                LEFT OUTER JOIN webgeocities wgc
                    ON ahc.city_id = wgc.id
                LEFT OUTER JOIN cms_countries cc
                    ON wgc.country_code = cc.code
                {$whereStmt}
                GROUP BY ahs.hotel_id
                {$havingStmt}
                )
            ";

            $whereParams = array_merge($whereParams, $uWhereParams);
            $whereParams = array_merge($whereParams, $uHavingParams);
        }

        if (!empty($sql)) {
            // order by
            if (count($orderBy)) {
                $orderByStmt = implode(' , ', $orderBy);
                if ($allowUnion) {
                    $orderByStmt = str_replace("res.", "tbl.", $orderByStmt);
                    $orderByStmt = str_replace("distanceSort", "tbl.distanceSort", $orderByStmt);
                    $orderByStmt = str_replace("priceSort", "tbl.priceSort", $orderByStmt);
                    $orderByStmt = str_replace('has_360', 'has360', $orderByStmt);

                    $sql = "SELECT * FROM ({$sql}) AS tbl ";
                }

                $sql .= " ORDER BY {$orderByStmt} ";
            }

            $sql .= $offsetLimitStmt;

            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($whereParams);

            while ($row = $stmt->fetch()) {
                $teaserData = new HotelTeaserData();
                $teaserData->setHotelSearchRequestId($row['hotelSearchRequestId']);
                $teaserData->setHotelId($row['hotelId']);
                $teaserData->setHotelKey($row['hotelKey']);
                $teaserData->setHotelCode($row['hotelCode']);
                $teaserData->setHotelNameURL($row['hotelNameURL']);
                $teaserData->setCurrencyCode($row['currencyCode']);
                $teaserData->setPrice($row['price']);
                $teaserData->setAvgPrice($row['avgPrice']);
                $teaserData->setDistance($row['distance']);
                $teaserData->setDistances($row['distances']);
                $teaserData->setMapImageUrl($row['mapImageUrl']);
                $teaserData->setMainImage($row['mainImage']);
                $teaserData->setMainImageMobile($row['mainImageMobile']);
                $teaserData->setCancelable($row['cancelable']);
                $teaserData->setBreakfast($row['breakfast']);

                $teaserData->getHotel()->setPropertyName($row['hotelName']);
                $teaserData->getHotel()->setStars($row['category']);
                $teaserData->getHotel()->setDistrict($row['district']);
                $teaserData->getHotel()->setHas360($row['has360']);

                $teaserData->getHotel()->getCity()->setId($row['amadeuHotelCityId']);
                $teaserData->getHotel()->getCity()->setCityId($row['cityId']);
                $teaserData->getHotel()->getCity()->setCode($row['cityCode']);
                $teaserData->getHotel()->getCity()->setName($row['city']);
                $teaserData->getHotel()->getCity()->setCountryName($row['country']);

                $toReturn[] = $teaserData;
                unset($row);
            }
            $stmt->closeCursor();
        }

        return $toReturn;
    }

    /**
     * Hotel Search Response - Get one or multiple rows of hotel_search_response based on hotel_search_request_id only
     *
     * @param  type  $reqId       The search request id.
     * @param  type  $countOnly   The flag to return only the count.
     * @param  type  $returnField The specific column/field to retrieve.
     * @return Mixed List of HotelSearchResponse or number of search responses.
     */
    public function getHotelSearchResponseByRequestIdOnly($reqId, $countOnly, $returnField = '')
    {
        $query = $this->createQueryBuilder('res')
            ->where('res.hotelSearchRequestId = :reqId')
            ->setParameter(':reqId', $reqId);

        if ($countOnly) {
            return $query->select('count(res.id)')->getQuery()->getSingleScalarResult();
        } elseif ($returnField) {
            return $query->getQuery()->getResult();
        }
    }

    /**
     * Hotel Search Response - Save entry to hotel_search_response
     *
     * @param  Array   $data The data.
     * @return Boolean TRUE.
     */
    public function insertHotelSearchResponse($data)
    {
        $em          = $this->getEntityManager();
        $tableFields = $em->getClassMetadata('HotelBundle:HotelSearchResponse')->getFieldNames();

        $obj = new HotelSearchResponse();
        foreach ($data as $column => $value) {
            if (in_array($column, $tableFields)) {
                $func = 'set'.ucwords($column);
                $obj->{$func}($value);
            }
        }

        // Since this is called in a loop, we'll only flush after the loop, that is handled in the calling function
        $em->persist($obj);

        return true;
    }

    /**
     * Amadeus Hotel Image - Get images for specific hotel
     *
     * @param  Integer           $hotelId The hotel id.
     * @return AmadeusHotelImage List of hotel image information
     */
    public function getHotelImages($hotelId)
    {
        $query = $this->createQueryBuilder('hi')
            ->where('hi.hotelId = :hotelId AND hi.mediaTypeId = :mediaTypeId')
            ->setParameter(':hotelId', $hotelId)
            ->setParameter(':mediaTypeId', 1)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Amadeus Hotel Source - Get a specific hotel given some criteria (Amadeus)
     *
     * @param  HotelSC            $hotelSC         HotelSC object.
     * @param  String             $selectiveReturn The type of return we want if provided (e.g. count, maxDistance) (Optional; default='').
     * @return AmadeusHotelSource
     */
    public function getHotelBySearchCriteria($hotelSC, $selectiveReturn = '')
    {
        $preQuery = $this->createQueryBuilder('hs')
            ->innerJoin('HotelBundle:AmadeusHotel', 'h', 'WITH', 'h.id = hs.hotelId')
            ->innerJoin('HotelBundle:AmadeusHotelCity', 'hc', 'WITH', 'hc.id = h.amadeusCityId')
            ->leftJoin('TTBundle:Webgeocities', 'wgc', 'WITH', 'hc.cityId = wgc.id')
            ->leftJoin('TTBundle:CmsCountries', 'cc', 'WITH', 'wgc.countryCode = cc.code');

        if (!empty($hotelSC->getLongitude()) && !empty($hotelSC->getLatitude())) {
            $perimeter     = (!empty($hotelSC->getDistance())) ? ($hotelSC->getDistance() * 1000) : 300000; // value is in km, convert to meters - 300km is max from amadeus
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
        } else {
            $preQuery->andWhere('hc.cityId = :locationIdentifier')->setParameter(':locationIdentifier', $hotelSC->getCity()->getId());
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

        if ($hotelSC->has360()) {
            $preQuery->andWhere('h.has360 = 1');
        }

        if ($selectiveReturn == 'count') {
            $query = $preQuery->select('count(h.id)')->getQuery();

            return $query->getSingleScalarResult();
        } else {
            $preQuery->select('hs, wgc.name AS geocityname, cc.name as countryName');

            if (!empty($hotelSC->getSortBy()) && ($hotelSC->getSortBy() == 'category')) {
                $preQuery->addOrderBy('h.stars', $hotelSC->getSortOrder());
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

            $result = $query->getResult();

            // city name and country should be retrieved from webgeocities and cmscountries respectively
            $toreturn = $this->fixCityNameAndCountryName($result);

            return $toreturn;
        }
    }

    /**
     * Amadeus Hotel Source - Get a specific non available hotel given some criteria (Amadeus).
     * This non available hotels are what we insert on the 4th and 14th on our availability listing page/rest results.
     *
     * @param  HotelSC Object     $hotelSC                 The data (filters, etc).
     * @param  type               $index                   To retrieved the 1st (for the 4th non available hotel) or 2nd (for the 14th non available hotel) (optional; default=0)
     * @param  Array              $excludeSourceIdentifier The hotelIds to exclude (Optional; default = array()).
     * @param  Array              $nbrStars                The selected hotel categories
     * @param  Array              $distanceRange           The distance filter
     * @return AmadeusHotelSource
     */
    public function getNonAvailableHotelsBySearchCriteria($hotelSC, $index, $excludeSourceIdentifier, $nbrStars, $distanceRange)
    {
        $toReturn = array();

        $whereParams = array();
        $where       = array();

        $havingParams = array();
        $having       = array();

        $sql = "SELECT
                ahs.hotel_id AS hotelId,
                '' AS hotelKey,
                ahs.hotel_code AS hotelCode,
                ah.property_name AS hotelNameURL,
                '' AS currencyCode,
                0.00 AS price,
                0.00 AS avgPrice,

                IFNULL(
                    (SELECT
                        TRUNCATE((poi.distance/1000.00), 2) AS poi_distance
                     FROM hotel_poi poi
                     INNER JOIN distance_poi_type dpt
                        ON poi.distance_poi_type_id = dpt.id
                     WHERE
                        dpt.name LIKE 'downtown'
                     AND poi.hotel_id = ahs.hotel_id
                     LIMIT 1)
                     , 0.00
                ) AS distance,

                '' AS distances,
                '' AS mapImageUrl,
                '' AS mainImage,
                '' AS mainImageMobile,
                0 AS cancelable,
                0 AS breakfast,
		ah.has_360 AS has360,

                ah.property_name AS hotelName,
                ah.stars AS category,
                ah.district AS district,

                ahc.id AS amadeusCityId,
                ahc.city_id AS cityId,
                ahc.city_code AS cityCode,
                wgc.name AS city,
                cc.name AS country
            FROM amadeus_hotel_source ahs
            INNER JOIN amadeus_hotel ah
                ON ahs.hotel_id = ah.id
            LEFT OUTER JOIN amadeus_hotel_city ahc
                ON ah.amadeus_city_id = ahc.id
            LEFT OUTER JOIN webgeocities wgc
                ON ahc.city_id = wgc.id
            INNER JOIN cms_countries cc
                ON wgc.country_code = cc.code";

        $where[] = 'ahs.published = 1';

        if (!empty($excludeSourceIdentifier)) {
            $where[]       = 'ahs.hotel_id NOT IN (?)';
            $whereParams[] = implode(', ', $excludeSourceIdentifier);
        }

        if ($hotelSC->isGeoLocationSearch()) {
            if (!empty($hotelSC->getLongitude()) && !empty($hotelSC->getLatitude())) {
                $perimeter = (!empty($hotelSC->getDistance())) ? ($hotelSC->getDistance() * 1000) : 300000; // value is in km, convert to meters - 300km is max from amadeus
                $where[]   = "(
                        6371000 *
                        acos(
                                cos(radians(?)) *
                                cos(radians(ah.latitude)) *
                                cos(radians(ah.longitude) - radians(?)) +
                                sin(radians(?)) *
                                sin(radians(ah.latitude))
                        )
                    ) < ? ";

                $whereParams[] = $hotelSC->getLatitude();
                $whereParams[] = $hotelSC->getLongitude();
                $whereParams[] = $hotelSC->getLatitude();
                $whereParams[] = $perimeter;
            }
        } else {
            $where[]       = 'ahc.city_id = ?';
            $whereParams[] = $hotelSC->getCity()->getId();
        }

        if (!empty($nbrStars)) {
            if (in_array('5', $nbrStars)) {
                $where[] = 'ah.stars IN (?) OR ah.stars > 5';
            } else {
                $where[] = 'ah.stars IN (?)';
            }

            $whereParams[] = implode(', ', $nbrStars);
        }

        if (!empty($hotelSC->getDistrict())) {
            $where[]       = 'ah.district IN (?)';
            $whereParams[] = '\''.implode('\', \'', $hotelSC->getDistrict()).'\'';
        }

        if ($hotelSC->has360()) {
            $where[] = 'ah.has_360 = 1';
        }

        $orderBy = array();
        switch ($hotelSC->getSortBy()) {
            case 'category':
                $orderBy[] = "ah.stars {$hotelSC->getSortOrder()}";
                break;
            case 'distance':
                $orderBy[] = "distance {$hotelSC->getSortOrder()}";

                $having[]       = 'distance > ?';
                $havingParams[] = $distanceRange[0];
                break;
        }

        // prioritize hotels with 360 images
        $orderBy[] = "ah.has_360 DESC";

        $where   = (count($where)) ? (" WHERE ".implode(' AND ', $where)." ") : '';
        $having  = (count($having)) ? (" HAVING ".implode(' AND ', $having)." ") : '';
        $orderBy = (count($orderBy)) ? (" ORDER BY ".implode(',  ', $orderBy)." ") : '';

        $sql         = "{$sql} {$where} {$having} {$orderBy} LIMIT ".(($hotelSC->getPage() - 1) * 2).", 2 ";
        $whereParams = array_merge($whereParams, $havingParams);

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($whereParams);

        while ($row = $stmt->fetch()) {
            $teaserData = new HotelTeaserData();
            $teaserData->setHotelId($row['hotelId']);
            $teaserData->setHotelCode($row['hotelCode']);
            $teaserData->setHotelNameURL($row['hotelNameURL']);
            $teaserData->setCurrencyCode($row['currencyCode']);
            $teaserData->setPrice($row['price']);
            $teaserData->setAvgPrice($row['avgPrice']);
            $teaserData->setDistance($row['distance']);
            $teaserData->setDistances($row['distances']);
            $teaserData->setMapImageUrl($row['mapImageUrl']);
            $teaserData->setMainImage($row['mainImage']);
            $teaserData->setMainImageMobile($row['mainImageMobile']);
            $teaserData->setCancelable($row['cancelable']);
            $teaserData->setBreakfast($row['breakfast']);

            $teaserData->getHotel()->setPropertyName($row['hotelName']);
            $teaserData->getHotel()->setStars($row['category']);
            $teaserData->getHotel()->setDistrict($row['district']);
            $teaserData->getHotel()->setHas360($row['has360']);

            $teaserData->getHotel()->getCity()->setId($row['amadeusCityId']);
            $teaserData->getHotel()->getCity()->setCityId($row['cityId']);
            $teaserData->getHotel()->getCity()->setCode($row['cityCode']);
            $teaserData->getHotel()->getCity()->setName($row['city']);
            $teaserData->getHotel()->getCity()->setCountryName($row['country']);

            $toReturn[] = $teaserData;
            unset($row);
        }
        $stmt->closeCursor();

        if (isset($toReturn[$index])) {
            return $toReturn[$index];
        } else {
            return false;
        }
    }

    /**
     * Amadeus Hotel Source - Get data for a list of hotels or a specific hotel based on hotelId or hotelCode and always put the directly queried hotel on top of the result set (Amadeus)
     *
     * @param  String             $field              The identifier column name
     * @param  Mixed              $sourceIdentifier   The identifier value OR list of identifier values
     * @param  Mixed              $firstHotelSearched The specific identifier value that should be on top of the list
     * @return AmadeusHotelSource
     */
    public function getHotelBySourceIdentifier($field, $sourceIdentifier, $firstHotelSearched)
    {
        $query = $this->createQueryBuilder('hs')
            ->select("hs, wgc.name AS geocityname,
                cc.name as countryName,
                (CASE WHEN hs.".$field." = :firstHotelSearched THEN 1 ELSE 0 END) AS HIDDEN hotelSort")
            ->innerJoin('HotelBundle:AmadeusHotel', 'h', 'WITH', 'h.id = hs.hotelId')
            ->innerJoin('HotelBundle:AmadeusHotelCity', 'hc', 'WITH', 'hc.id = h.amadeusCityId')
            ->leftJoin('TTBundle:Webgeocities', 'wgc', 'WITH', 'hc.cityId = wgc.id')
            ->leftJoin('TTBundle:CmsCountries', 'cc', 'WITH', 'wgc.countryCode = cc.code')
            ->where('hs.'.$field.' IN ( :sourceIdentifier )')
            ->andWhere('hs.published = 1')
            ->setParameter(':firstHotelSearched', $firstHotelSearched)
            ->setParameter(':sourceIdentifier', $sourceIdentifier)
            ->addOrderBy('hotelSort', 'DESC')
            ->addOrderBy('h.has360', 'DESC')
            ->getQuery();

        $result = $query->getResult();

        // city name and country should be retrieved from webgeocities and cmscountries respectively
        $toreturn = $this->fixCityNameAndCountryName($result);

        if (!is_array($sourceIdentifier) && !empty($sourceIdentifier)) {
            $toreturn = $toreturn[0];
        }

        unset($query, $field, $sourceIdentifier, $firstHotelSearched, $result);
        return $toreturn;
    }

    /**
     * Amadeus Hotel Source - Get data for a list of hotels or a specific hotel based on hotelId or hotelCode and always put the directly queried hotel on top of the result set (Amadeus)
     * This method also fill-in empty spots on our results so that pagination will work.
     *
     * @param  Mixed                      $sourceIdentifier
     * @param  \HotelBundle\Model\HotelSC $hotelSC
     * @return Array                      List of HotelTeaserData object
     */
    public function getHotelBySourceIdentifierTTApi($sourceIdentifier, \HotelBundle\Model\HotelSC $hotelSC)
    {
        $doQuery  = true;
        $toReturn = array();

        $where       = array();
        $whereParams = array();

        // hotelSort select statement
        $hotelSort = array();
        if (!empty($hotelSC->getHotelCode())) {
            $hotelSort[]   = "WHEN ahs.hotel_code  = ? THEN '1'";
            $whereParams[] = $hotelSC->getHotelCode();
        }

        if (!is_array($sourceIdentifier)) {
            $sourceIdentifier = array($sourceIdentifier);
        }

        $caseWhere = array();
        foreach ($sourceIdentifier as $hotelCode) {
            if (!empty($hotelCode) && $hotelCode === $hotelSC->getHotelCode()) {
                continue;
            }

            $caseWhere[]   = "ahs.hotel_code  = ?";
            $whereParams[] = $hotelCode;
        }

        if (count($caseWhere)) {
            $hotelSort[] = "WHEN (".implode(' OR ', $caseWhere).") THEN '2'";
        }

        $hotelSort = (count($hotelSort)) ? ', (CASE '.implode(' ', $hotelSort)." ELSE '3' END) AS hotelSort" : ', 0 AS hotelSort ';

        // where conditions
        $where[] = "ahs.published = 1";

        $city = $hotelSC->getCity();
        if (!empty($city->getCode())) {
            $where[]       = 'ahc.city_code LIKE ?';
            $whereParams[] = $city->getCode();
        } elseif (!empty($city->getId())) {
            $where[]       = 'ahc.city_id = ?';
            $whereParams[] = $city->getId();
        } elseif ($hotelSC->isGeoLocationSearch()) {
            $perimeter = (!empty($hotelSC->getDistance())) ? ($hotelSC->getDistance() * 1000) : 300000; // value is in km, convert to meters - 300km is max from amadeus
            $where[]   = "(
                6371000 *
                acos(
                        cos(radians(?)) *
                        cos(radians(ah.latitude)) *
                        cos(radians(ah.longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(ah.latitude))
                    )
                ) < ? ";

            $whereParams[] = $hotelSC->getLatitude();
            $whereParams[] = $hotelSC->getLongitude();
            $whereParams[] = $hotelSC->getLatitude();
            $whereParams[] = $perimeter;
        } else {
            // we don't have criterias: hotelId, hotelCityCode, OR cityId; don't do query
            $doQuery = false;
        }

        $whereStmt = '';
        if (count($where) > 0) {
            $whereStmt = "WHERE ".implode(" AND ", $where)." ";
        }

        $sql = "SELECT *  FROM (
            SELECT
                ahs.hotel_id,
                ahs.hotel_code,
                ah.stars,
                ah.district,
                ah.property_name AS hotelName,
                ahc.id AS amadeus_hotel_city_id,
                ahc.city_id AS city_id,
                ahc.city_code,
                wgc.name AS city_name,
                cc.name AS city_country_name,
                ah.has_360 AS has360,
                ahs.source
                $hotelSort
            FROM amadeus_hotel_source ahs
            INNER JOIN amadeus_hotel ah
                ON ahs.hotel_id = ah.id
            INNER JOIN amadeus_hotel_city ahc
                ON ah.amadeus_city_id = ahc.id
            LEFT OUTER  JOIN webgeocities wgc
                ON ahc.city_id = wgc.id
            LEFT OUTER  JOIN cms_countries cc
                ON wgc.country_code = cc.code
            {$whereStmt}
            ORDER BY hotelSort, ah.has_360 DESC
            LIMIT 50
        ) tbl
        GROUP BY tbl.hotel_id
        ORDER BY tbl.hotelSort, tbl.has360 DESC
        LIMIT {$hotelSC->getLimit()}";

        if ($doQuery) {
            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($whereParams);

            //$result = $stmt->fetchAll();
            while ($row = $stmt->fetch()) {
                $teaserData = new HotelTeaserData();
                $teaserData->setHotelCode($row['hotel_code']);
                $teaserData->setHotelId($row['hotel_id']);
                $teaserData->setSource($row['source']);

                $teaserData->getHotel()->setPropertyName($row['hotelName']);
                $teaserData->getHotel()->setStars($row['stars']);
                $teaserData->getHotel()->setDistrict($row['district']);
                $teaserData->getHotel()->setHas360($row['has360']);

                $teaserData->getHotel()->getCity()->setId($row['amadeus_hotel_city_id']);
                $teaserData->getHotel()->getCity()->setCityId($row['city_id']);
                $teaserData->getHotel()->getCity()->setCode($row['city_code']);
                $teaserData->getHotel()->getCity()->setName($row['city_name']);
                $teaserData->getHotel()->getCity()->setCountryName($row['city_country_name']);

                $toReturn[] = $teaserData;
            }
        }

        return $toReturn;
    }

    /**
     * Amadeus Hotel Source - Retrieves all Hotel Codes for a certain Hotel City Code
     *
     * @param  type  $hotelCityCode The hotel city code.
     * @param  type  $sourceType    The source type (Optional; default='MultiSource').
     * @param  type  $hotelId       The hotel id (Optional; default=0).
     * @return Array List of hotel codes.
     */
    public function getHotelCodesByHotelCityCode($hotelCityCode, $sourceType = 'MultiSource', $hotelId = 0)
    {
        $select      = array();
        $where       = array();
        $whereParams = array();

        if ($hotelId > 0) {
            $select[] = "(SELECT ahs1.hotel_code AS code
                FROM amadeus_hotel_source ahs1
                WHERE ahs1.hotel_id = ?) ";

            $whereParams[] = $hotelId;

            $where[]       = "ahs2.hotel_id <> ?";
            $whereParams[] = $hotelId;
        }

        $where[]       = 'ahc.city_code LIKE ?';
        $whereParams[] = $hotelCityCode;

        switch (strtoupper($sourceType)) {
            case 'LEISURE':
                $where[] = "ahs2.source NOT LIKE 'gds'";
                break;
            case 'DISTRIBUTION':
                $where[] = "ahs2.source LIKE 'gds'";
            default:
                break;
        }

        $whereStmt = '';
        if (count($where) > 0) {
            $whereStmt = "WHERE ".implode(" AND ", $where)." ";
        }

        $select[] = "(SELECT ahs2.hotel_code AS code
                FROM amadeus_hotel_source ahs2
                INNER JOIN amadeus_hotel ah
                    ON ahs2.hotel_id = ah.id
                INNER JOIN amadeus_hotel_city ahc
                    ON ah.amadeus_city_id = ahc.id
                {$whereStmt}
            )";

        $results = array();
        if (count($select) > 0) {
            $sql = implode(" UNION ", $select);

            $conn = $this->getEntityManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($whereParams);

            $results = $stmt->fetchAll();
            $stmt->closeCursor();
        }

        return $results;
    }

    /**
     * Amadeus Hotel Source - Retrieves all Hotel Codes for a given search criteria
     *
     * @param  HotelSC $hotelSC The search criteria.
     * @return Array   List of hotel codes.
     */
    public function getHotelCodesBySearchCriteria($hotelSC)
    {
        $results = array();

        $select      = array();
        $where       = array();
        $whereParams = array();

        $doQuery = true;
        if ($hotelSC->getHotelId() > 0) {
            $select[] = "(SELECT
                    ahs1.hotel_code AS code,
                    ahs1.hotel_id AS id,
                    ahs1.source AS source,
                    tvs1.tt_vendor_id AS vendor_id,
                    tv1.name AS vendor_name
                FROM amadeus_hotel_source ahs1
                INNER JOIN tt_vendors_source tvs1
                    ON ahs1.source = tvs1.name
                INNER JOIN tt_vendors tv1
                    ON tvs1.tt_vendor_id = tv1.id
                WHERE ahs1.hotel_id = ? AND published = 1) ";

            $whereParams[] = $hotelSC->getHotelId();

            $where[]       = "ahs2.hotel_id <> ?";
            $whereParams[] = $hotelSC->getHotelId();
        }

        $where[] = "ahs2.published = 1";

        $city = $hotelSC->getCity();
        if (!empty($city->getCode())) {
            $where[]       = 'ahc.city_code LIKE ?';
            $whereParams[] = $city->getCode();
        } elseif (!empty($city->getId())) {
            $where[]       = 'ahc.city_id = ?';
            $whereParams[] = $city->getId();
        } else {
            // we don't have criterias: hotelId, hotelCityCode, OR cityId; don't do query
            $doQuery = false;
        }

        if ($doQuery) {
            switch (strtoupper($hotelSC->getInfoSource())) {
                case 'LEISURE':
                    $where[] = "ahs2.source NOT LIKE 'gds'";
                    break;
                case 'DISTRIBUTION':
                    $where[] = "ahs2.source LIKE 'gds'";
                default:
                    break;
            }

            $whereStmt = '';
            if (count($where) > 0) {
                $whereStmt = "WHERE ".implode(" AND ", $where)." ";
            }

            $select[] = "(SELECT
                    ahs2.hotel_code AS code,
                    ahs2.hotel_id AS id,
                    ahs2.source AS source,
                    tvs2.tt_vendor_id AS vendor_id,
                    tv2.name AS vendor_name
                FROM amadeus_hotel_source ahs2
                INNER JOIN tt_vendors_source tvs2
                    ON ahs2.source = tvs2.name
                INNER JOIN tt_vendors tv2
                    ON tvs2.tt_vendor_id = tv2.id
                INNER JOIN amadeus_hotel ah
                    ON ahs2.hotel_id = ah.id
                INNER JOIN amadeus_hotel_city ahc
                    ON ah.amadeus_city_id = ahc.id
                {$whereStmt}
                ORDER BY ah.has_360 DESC
            )";

            if (count($select) > 0) {
                $sql = implode(" UNION ", $select);

                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute($whereParams);

                while ($row = $stmt->fetch()) {
                    $item = new \HotelBundle\Model\TTApiHotelSource();
                    $item->setCode($row['code']);
                    $item->setId($row['id']);
                    $item->getVendor()->setId($row['vendor_id']);
                    $item->getVendor()->setName($row['vendor_name']);
                    $item->getVendor()->getSource()->setCode($row['source']);

                    $results[] = $item;
                    unset($row, $item);
                }
                $stmt->closeCursor();
            }
        }

        return $results;
    }

    /**
     * Amadeus Hotel Source - Retrieves all Hotel Codes for a hotel id and source type
     *
     * @param  Integer $hotelId    The search criteria.
     * @param  String  $sourceType The source type (Optional; default='MultiSource').
     * @return Array   List of hotel codes.
     */
    public function getHotelCodesByHotelId($hotelId, $sourceType = 'MultiSource')
    {
        $preQuery = $this->createQueryBuilder('hs')
            ->distinct()
            ->select('hs.hotelCode')
            ->where('hs.hotelId = :hotelId AND hs.published = 1')
            ->setParameter(':hotelId', $hotelId);

        switch (strtoupper($sourceType)) {
            case 'LEISURE':
                $preQuery->andWhere("hs.source NOT LIKE 'gds'");
                break;
            case 'DISTRIBUTION':
                $preQuery->andWhere("hs.source LIKE 'gds'");
            default:
                break;
        }

        $query = $preQuery->getQuery();

        $result = $query->getScalarResult();
        if (!empty($result)) {
            $result = array_column($result, 'hotelCode');
        }

        return $result;
    }

    /**
     * Amadeus Hotel Source - returns the total count of unique hotels for a certain search criteria
     *
     * @param  HotelSC $hotelSC HotelSC object.
     * @param  Boolean $isRest  Determines if used via REST.
     * @return Integer The total count.
     */
    public function getTotalUniqueHotelsBySearchCriteria(\HotelBundle\Model\HotelSC $hotelSC, $isRest = false)
    {
        $where       = array();
        $whereParams = array();

        $uWhere       = array();
        $uWhereParams = array();

        $uHaving       = array();
        $uHavingParams = array();

        $where[]       = 'res.hotel_search_request_id = ?';
        $whereParams[] = $hotelSC->getHotelSearchRequestId();

        $allowUnion = $hotelSC->isUseTTApi();

        if (!empty($hotelSC->getNbrStars())) {
            $whereStmt = 'res.category IN('.preg_replace("/[^\,]+/", '?', implode(',', $hotelSC->getNbrStars())).')';
            if (in_array('5', $hotelSC->getNbrStars())) {
                $whereStmt .= ' OR res.category > 5';
            }

            $whereStmt = '('.trim($whereStmt).')';

            $where[]     = $whereStmt;
            $whereParams = array_merge($whereParams, $hotelSC->getNbrStars());

            $uWhere[]     = str_replace('res.category', 'ah.stars', $whereStmt);
            $uWhereParams = array_merge($uWhereParams, $hotelSC->getNbrStars());
        }

        if (!empty($hotelSC->getDistrict())) {
            $whereStmt = 'res.district IN ('.preg_replace("/[^\,]+/", '?', implode(',', $hotelSC->getDistrict())).')';

            $where[]     = $whereStmt;
            $whereParams = array_merge($whereParams, $hotelSC->getDistrict());

            $uWhere[]     = str_replace('res.district', 'ah.district', $whereStmt);
            $uWhereParams = array_merge($uWhereParams, $hotelSC->getDistrict());
        }

        if (!empty($hotelSC->getPriceRange())) {
            $whereStmt      = '';
            $whereStmtParam = '';

            // min price
            $whereStmt      = 'res.price >= ?';
            $whereStmtParam = $hotelSC->getPriceRange()[0];

            $where[]       = $whereStmt;
            $whereParams[] = $whereStmtParam;

            // Do not put our union query when we have a price to consider for a certain scenario
            if (($hotelSC->getPriceRange()[0] != 0 && $hotelSC->getPriceRange()[1] != $hotelSC->getMaxPrice()) || $isRest) {
                $allowUnion = false;
            }

            // max price
            if ($hotelSC->getPriceRange()[1] < $hotelSC->getMaxPrice()) {
                $whereStmt      = 'res.price <= ?';
                $whereStmtParam = $hotelSC->getPriceRange()[1];

                $where[]       = $whereStmt;
                $whereParams[] = $whereStmtParam;
            }
        }

        if (!empty($hotelSC->getBudgetRange())) {
            // Do not put our union query when we have a minimum and maximum budget range
            $allowUnion = false;

            $budgetQuery = '';
            foreach ($hotelSC->getBudgetRange() as $key => $value) {
                list($min, $max) = explode('-', $value);

                if ($max == '+') {
                    $budgetQuery   .= ' (res.avg_price >= ?) OR ';
                    $whereParams[] = $min;
                } else {
                    $budgetQuery   .= ' (res.avg_price BETWEEN ? AND ?) OR ';
                    $whereParams[] = $min;
                    $whereParams[] = $max;
                }
            }

            $whereStmt = '('.trim(substr($budgetQuery, 0, -3)).')';

            $where[] = $whereStmt;
        }

        if (!empty($hotelSC->getDistanceRange())) {
            $whereStmt      = '';
            $whereStmtParam = '';

            // Do not put our union query when we have a distance to consider for a certain scenario
            if (($hotelSC->getDistanceRange()[0] != 0 && $hotelSC->getDistanceRange()[1] != $hotelSC->getMaxDistance()) || $isRest) {
                $allowUnion = false;
                $where[]    = "res.distances <> ''";
            }

            // min distance
            $whereStmt      = 'res.distance >= ?';
            $whereStmtParam = $hotelSC->getDistanceRange()[0];

            $where[]       = $whereStmt;
            $whereParams[] = $whereStmtParam;

            $uHaving[]       = str_replace('res.distance', 'distance', $whereStmt);
            $uHavingParams[] = $whereStmtParam;

            // max distance
            if ($hotelSC->getDistanceRange()[1] < $hotelSC->getMaxDistance()) {
                $whereStmt      = 'res.distance <= ?';
                $whereStmtParam = $hotelSC->getDistanceRange()[1];

                $where[]       = $whereStmt;
                $whereParams[] = $whereStmtParam;

                $uHaving[]       = str_replace('res.distance', 'distance', $whereStmt);
                $uHavingParams[] = $whereStmtParam;
            }
        }

        if (!empty($hotelSC->isCancelable())) {
            if ($hotelSC->isCancelable() > 0) {
                $where[] = 'res.cancelable > 0';

                // no need for union query since union records are not-available hotels
                $allowUnion = false;
                //$uHaving[] = 'cancelable > 0';
            }
        }

        if (!empty($hotelSC->hasBreakfast())) {
            if ($hotelSC->hasBreakfast() > 0) {
                $where[] = 'res.breakfast > 0';

                // no need for union query since union records are not-available hotels
                $allowUnion = false;
                //$uHaving[] = 'breakfast > 0';
            }
        }

        if ($hotelSC->has360()) {
            $where[] = 'res.has_360 = 1';

            // no need for union query since union records are not-available hotels
            $allowUnion = false;
        }

        // initialize sql
        $sql = "
            SELECT
                res.hotel_id AS hotelId,
                res.category,
                res.district,
                res.price,
                res.avg_price AS avgPrice,
                res.distance,
                res.cancelable,
                res.breakfast
            FROM hotel_search_response res
            INNER JOIN amadeus_hotel h
                ON res.hotel_id = h.id ";
        $sql .= ((count($where) > 0) ? "WHERE ".implode(" AND ", $where) : "")." ";
        $sql .= "GROUP BY h.dupe_pool_id ";

        // we don't allow union when entityType is not equal to SOCIAL_ENTITY_HOTEL and SOCIAL_ENTITY_CITY (searching via longitude and latitude)
        if ($hotelSC->isGeoLocationSearch()) {
            $allowUnion = false;
        }

        // append hotels that should be included when we did search but was not yet inserted on our hotel_search_response table
        if ($allowUnion) {
            switch (strtoupper($hotelSC->getInfoSource())) {
                case 'LEISURE':
                    $uWhere[] = "ahs.source NOT LIKE 'gds'";
                    break;
                case 'DISTRIBUTION':
                    $uWhere[] = "ahs.source LIKE 'gds'";
                default:
                    break;
            }

            if (!empty($hotelSC->getCity()->getCode())) {
                $uWhere[]       = 'ahc.city_code LIKE ?';
                $uWhereParams[] = $hotelSC->getCity()->getCode();
            } elseif (!empty($hotelSC->getCity()->getId())) {
                $uWhere[]       = 'ahc.city_id = ?';
                $uWhereParams[] = $hotelSC->getCity()->getId();
            }

            if (count($where)) {
                $whereStmt = implode(" AND ", $where);
                $whereStmt = "WHERE ".str_replace('res.', 'hsr.', $whereStmt);

                $whereStmt = "
                    ahs.hotel_id NOT IN (
                        SELECT
                            hsr.hotel_id
                        FROM hotel_search_response hsr
                        {$whereStmt}
                    )";

                $uWhere[]     = $whereStmt;
                $uWhereParams = array_merge($uWhereParams, $whereParams);
            }

            // only include non-available hotels that are published=1.
            $uWhere[] = "ahs.published = 1";

            $whereStmt = "";
            if (count($uWhere) > 0) {
                $whereStmt = "WHERE ".implode(" AND ", $uWhere)." ";
            }

            $havingStmt = '';
            if (count($uHaving) > 0) {
                $havingStmt = "HAVING ".implode(" AND ", $uHaving)." ";
            }

            $sql = "
                ({$sql})

                UNION

                (SELECT
                    ahs.hotel_id AS hotelId,
                    ah.stars AS category,
                    ah.district AS district,
                    0.00 AS price,
                    0.00 AS avgPrice,
                    IFNULL(
                        (SELECT
                            TRUNCATE((poi.distance/1000.00), 2) AS poi_distance
                         FROM hotel_poi poi
                         INNER JOIN distance_poi_type dpt
                            ON poi.distance_poi_type_id = dpt.id
                         WHERE
                            dpt.name LIKE 'downtown'
                         AND poi.hotel_id = ahs.hotel_id
                         LIMIT 1)
                         , 0.00
                    ) AS distance,
                    0 AS cancelable,
                    0 AS breakfast
                FROM amadeus_hotel_source ahs
                INNER JOIN amadeus_hotel ah
                    ON ahs.hotel_id = ah.id
                INNER JOIN amadeus_hotel_city ahc
                    ON ah.amadeus_city_id = ahc.id
                {$whereStmt}
                GROUP BY ah.dupe_pool_id
                {$havingStmt}
                )
            ";

            $whereParams = array_merge($whereParams, $uWhereParams);
            $whereParams = array_merge($whereParams, $uHavingParams);
        }

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($whereParams);

        $result = $stmt->rowCount();

        $stmt->closeCursor();

        return $result;
    }

    /**
     * Amadeus Hotel Source - Get a specific hotel source column given a specific param (Amadeus)
     *
     * @param  String $field  The field/column name.
     * @param  Array  $params The field-value pair condition.
     * @return Mixed  The column value OR FALSE if not found.
     */
    public function getHotelSourceField($field, $params)
    {
        $query = $this->createQueryBuilder('hs')
            ->select('hs.'.$field)
            ->where('hs.'.$params[0].' = :'.$params[0])
            ->setParameter(':'.$params[0], $params[1])
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
     * Amadeus Hotel Source - Get hotel source id given hotel id and hotel code.
     *
     * @param  Integer $hotelId   The hotel id.
     * @param  String  $hotelCode The hotel code.
     * @return Mixed   The hotel source id OR FALSE if not found.
     */
    public function getHotelSourceId($hotelId, $hotelCode)
    {
        $query = $this->createQueryBuilder('hs')
            ->select('hs.id')
            ->where('hs.hotelId = :hotelId')
            ->andWhere('hs.hotelCode = :hotelCode')
            ->setParameter(':hotelId', $hotelId)
            ->setParameter(':hotelCode', $hotelCode)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * Amadeus Hotel - Get hotel data given its unique id (Amadeus).
     *
     * @param  Integer $hotelId
     * @return Mixed   The hotel information OR FALSE if not found.
     */
    public function getHotelDataById($hotelId)
    {
        $query = $this->createQueryBuilder('h')
            ->select("
                    h.id,
		    h.dupePoolId,
		    h.propertyName AS name,
		    h.propertyName AS nameTrans,
                    h.address1 AS street,
                    h.zipCode,
                    h.amadeusCityId,
                    h.latitude,
                    h.longitude,
                    h.stars,
		    h.description,
                    h.phone,
                    h.district,
                    hc.name AS city,
                    hc.countryCode,
                    h.amadeusCityId AS cityId,
                    hc.code AS cityCode,
                    hc.countryName AS country")
            ->leftJoin('HotelBundle:AmadeusHotelCity', 'hc', 'WITH', 'hc.id = h.amadeusCityId');

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
     * This method finds a hotel by id.
     *
     * @param Integer $hotelId
     * @return \HotelBundle\Entity\AmadeusHotel
     */
    public function getHotelById($hotelId)
    {
        $query = $this->createQueryBuilder('h')
            ->select("h, wgc.name AS geocityname, cc.name as countryName")
            ->innerJoin('HotelBundle:AmadeusHotelCity', 'hc', 'WITH', 'h.amadeusCityId = hc.id')
            ->leftJoin('TTBundle:Webgeocities', 'wgc', 'WITH', 'hc.cityId = wgc.id')
            ->leftJoin('TTBundle:CmsCountries', 'cc', 'WITH', 'wgc.countryCode = cc.code')
            ->where('h.id = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        $toreturn = null;
        if (!empty($result)) {
            $rowItem = $result[0];

            // city name and country should be retrieved from webgeocities and cmscountries respectively
            $geoCityName = $rowItem['geocityname'];
            $countryName = $rowItem['countryName'];

            if (isset($rowItem[0])) {
                $toreturn = $rowItem[0];

                $toreturn->getCity()->setName($geoCityName);
                $toreturn->getCity()->setCountryName($countryName);
            }
        }

        unset($hotelId, $query, $result);
        return $toreturn;
    }

    /**
     * Amadeus Hotel - Gets the Amadeus unique dupe_pool_id given the hotelId(PK)
     *
     * @param  Integer $hotelId The hotel id.
     * @return Mixed   The dupe pool id OR FALSE ifnot found.
     */
    public function getHotelDupePoolId($hotelId)
    {
        $query = $this->createQueryBuilder('h')
            ->select('h.dupePoolId')
            ->where('h.id = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['dupePoolId'];
        } else {
            return false;
        }
    }

    /**
     * Amadeus Hotel - Gets the hotel iso3 country code given the hotelId(PK)
     *
     * @param  Integer $hotelId The hotel id.
     * @return Mixed   The iso3 OR FALSE if not found.
     */
    public function getHotelIso3Country($hotelId)
    {
        $query = $this->createQueryBuilder('h')
            ->select('c.iso3')
            ->join('HotelBundle:AmadeusHotelCity', 'hc', 'WITH', 'hc.id = h.amadeusCityId')
            ->join('TTBundle:CmsCountries', 'c', 'WITH', 'c.code = hc.countryCode')
            ->where('h.id = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['iso3'];
        } else {
            return false;
        }
    }

    /**
     * Amadeus Hotel - Get all districts associated for a specific location_id (this is for now used only for Paris arrondissements) (Amadeus)
     *
     * @param  String       $cityCode
     * @return AmadeusHotel
     */
    public function getDistricts($cityCode)
    {
        $query = $this->createQueryBuilder('h')
            ->innerJoin("HotelBundle:AmadeusHotelCity", "hc", "WITH", "hc.id = h.amadeusCityId")
            ->andWhere("h.district != '' OR h.district IS NOT NULL")
            ->andWhere("hc.code = :code")
            ->setParameter(":code", $cityCode)
            ->groupBy("h.district")
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Amadeus Hotel - Get all districts associated for a specific city_id (this is for now used only for Paris arrondissements) (Amadeus)
     *
     * @param  String $cityId
     * @return Array  List of districts
     */
    public function getDistrictsByCityId($cityId)
    {
        $query = $this->createQueryBuilder('h')
            ->innerJoin("HotelBundle:AmadeusHotelCity", "hc", "WITH", "hc.id = h.amadeusCityId")
            ->andWhere("h.district != '' OR h.district IS NOT NULL")
            ->andWhere("hc.cityId = :cityId")
            ->setParameter(":cityId", $cityId)
            ->groupBy("h.district")
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Amadeus Hotel - get hotel's trust you id
     *
     * @param Int $hotelId
     *
     * @return Int
     */
    public function getTrustYouId($hotelId)
    {
        $query = $this->createQueryBuilder('h')
            ->select('ers.vendorReviewId')
            ->join('TTBundle:EntitiesReviewsSource', 'ers', 'WITH', 'h.id = ers.entityId')
            ->join('TTBundle:TTVendors', 'v', 'WITH', 'v.id = ers.vendorId AND v.name = :vendorName')
            ->join('TTBundle:TTModules', 'm', 'WITH', 'm.id = ers.moduleId AND m.name = :moduleName')
            ->where('h.id = :hotelId')
            ->setParameter(':vendorName', 'TrustYou')
            ->setParameter(':moduleName', 'Hotels')
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['vendorReviewId'];
        } else {
            return false;
        }
    }

    /**
     * Amadeus Hotel - Get hotel name and hotel code  of a hotel for the given the hotel id (Amadeus).
     *
     * @param  String $hotelId The hotel id.
     * @return Array  The hotel name and hotel id.
     */
    public function getHotelNameAndHotelCodeByHotelId($hotelId)
    {
        $result = $this->getHotelInformationByHotelId($hotelId, array('h.property_name AS name', 'hs.hotel_code AS hotelCode'));

        return $result;
    }

    /**
     * Amadeus Hotel - Get hotel information given the fields/columns to retrieved (Amadeus).
     * Example:
     *      $fields = array(
     *          'h.<columnName>',   // to get AmadeusHotel information
     *          'hs.<columnName>'   // to get AmadeusHotelSource information
     *      );
     *
     * @param  integer $hotelId The hotel id.
     * @param  array   $fields  The fields/columns information to retrieved (optional).
     * @return Array   The hotel information.
     */
    private function getHotelInformationByHotelId($hotelId, array $fields = array())
    {
        $conn = $this->getEntityManager()->getConnection();

        $select = "*";
        if (count($fields) > 0) {
            $select = implode(', ', $fields);
        }

        $sql = "SELECT {$select}
                     FROM amadeus_hotel h
                     LEFT OUTER JOIN amadeus_hotel_source hs
                        ON h.id= hs.hotel_id
                    WHERE h.id = ?
                    LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array($hotelId));

        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, 'HotelBundle\Model\Hotel');
        $stmt->closeCursor();

        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    /**
     * Amadeus Hotel City - Get city code of a city given its unique id
     *
     * @param  Integer $cityId The city id.
     * @return Mixed   The city code or FALSE if not found.
     */
    public function getCityCodeByCityId($cityId)
    {
        $query = $this->createQueryBuilder('hc')
            ->select('hc.code')
            ->where('hc.cityId = :cityId')
            ->setParameter(':cityId', $cityId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['code'];
        } else {
            return '';
        }
    }

    /**
     * Amadeus Hotel City - Get city code of a specific hotel given its unique id
     *
     * @param  Integer $hotelId The hotel id.
     * @return Mixed   The city code or FALSE if not found.
     */
    public function getCityCodeByHotelId($hotelId)
    {
        $query = $this->createQueryBuilder('hc')
            ->innerJoin('HotelBundle:AmadeusHotel', 'h', 'WITH', 'h.amadeusCityId = hc.id')
            ->select('hc.code')
            ->where('h.id = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['code'];
        } else {
            return '';
        }
    }

    /**
     * Amadeus Hotel City - Get city code of a specific hotel given its unique id
     *
     * @param  Integer $hotelId The hotel id.
     * @return Mixed   The city id or FALSE if not found.
     */
    public function getCityIdByHotelId($hotelId)
    {
        $query = $this->createQueryBuilder('hc')
            ->innerJoin('HotelBundle:AmadeusHotel', 'h', 'WITH', 'h.amadeusCityId = hc.id')
            ->select('hc.cityId')
            ->where('h.id = :hotelId')
            ->setParameter(':hotelId', $hotelId)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['cityId'];
        } else {
            return 0;
        }
    }

    /**
     * Amadeus Hotel Image - Get an image for a specific hotel (default image first)
     *
     * @param  Integer $hotelId The hotel id.
     * @return Mixed   The default image data OR FALSE if not found.
     */
    public function getHotelMainImage($hotelId)
    {
        $query = $this->createQueryBuilder('hi')
            ->where("hi.hotelId = :hotelId AND hi.mediaTypeId = :mediaTypeId")
            ->setParameter(':hotelId', $hotelId)
            ->setParameter(':mediaTypeId', 1)
            ->orderBy('hi.defaultPic', 'DESC')
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
     * Amadeus OTA - Get OTA value given its category and code
     *
     * @param  String $category The category.
     * @param  String $code     The code.
     * @return Mixed  The OTA value OR FALSE if not found.
     */
    public function getOTAValue($category, $code)
    {
        $query = $this->createQueryBuilder('oc')
            ->select('oc.value')
            ->where('oc.category = :category AND oc.code = :code')
            ->setParameter(':category', $category)
            ->setParameter(':code', $code)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['value'];
        } else {
            return false;
        }
    }

    /**
     * Amadeus OTA - Get OTA code given its category and value
     *
     * @param  String $category The category.
     * @param  Mixed  $value    The value.
     * @return Mixed  The OTA code OR FALSE if not found.
     */
    public function getOTACode($category, $value)
    {
        $query = $this->createQueryBuilder('oc')
            ->select('oc.code')
            ->where('oc.category = :category AND oc.value = :value')
            ->setParameter(':category', $category)
            ->setParameter(':value', $value)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['code'];
        } else {
            return false;
        }
    }

    /**
     * TTVendorSource - Get vendor sources from a given vendor name.
     * @param  String                              $vendorName
     * @return \HotelBundle\Model\TTApiHotelVendor
     */
    public function getHotelVendorSourceByVendorName($vendorName)
    {
        $results = array();

        $sql = "SELECT
                tv.id,
                tv.name,
                tvs.name AS code
             FROM tt_vendors_source tvs
             INNER JOIN tt_vendors tv
                ON tvs.tt_vendor_id = tv.id
             WHERE tv.name LIKE ?
             ORDER BY tv.id";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($vendorName));

        $prevVendorId = 0;
        $item         = null;

        while ($row = $stmt->fetch()) {
            if ($prevVendorId != $row['id']) {
                if ($item) {
                    $results[] = $item;
                    unset($item);
                }

                $item = new \HotelBundle\Model\TTApiHotelVendor();
                $item->setId($row['id']);
                $item->setName($row['name']);
            }

            $source = new \HotelBundle\Model\TTApiVendorSource();
            $source->setCode($row['code']);

            $item->addProvider($source);
            $prevVendorId = $row['id'];

            unset($row, $source);
        }

        if ($item) {
            $results[] = $item;
            unset($item);
        }

        $stmt->closeCursor();

        return $results;
    }

    /**
     * Error Messages - Get error message given its category and code
     *
     * @param  String $identifier    The identifier.
     * @param  type   $defaultReturn The default message to return (Optional; default='').
     * @return String The message.
     */
    public function getErrorMessage($identifier, $defaultReturn = '')
    {
        list($category, $code) = explode('_', $identifier);
        $query = $this->createQueryBuilder('em')
            ->select('em.value')
            ->where('em.category = :category AND em.code = :code')
            ->setParameter(':category', $category)
            ->setParameter(':code', $code)
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['value'];
        } else {
            return $defaultReturn;
        }
    }

    /**
     * This method updates the hotel reservation record in case error occurs with the reservation process.
     *
     * @param HotelReservation $hotelReservation
     * @param String           $reservationStatus
     */
    public function updateHotelBookingOnError($hotelReservation, $reservationStatus)
    {
        // In case of error,
        // update reservation
        $em = $this->getEntityManager();

        $hotelReservation->setReservationStatus($reservationStatus);
        $hotelReservation->setDetails(null);
        $em->persist($hotelReservation);
        $em->flush();

        // update room reservation
        $preQuery = $this->createQueryBuilder('UP')
            ->update('HotelBundle:HotelRoomReservation', 'r')
            ->set("r.roomStatus", ":status")
            ->where('r.hotelReservationId = :id')
            ->setParameter(':status', $reservationStatus)
            ->setParameter('id', $hotelReservation->getId())
            ->getQuery();

        $result = $preQuery->execute();
    }

    /**
     * This is a helper function to fix/initialize the AmadeusHotelCity name and country name
     * with webgeocities city name and cms_countries name respectively.
     *
     * @param Mixed $result The result.
     * @return Array
     */
    private function fixCityNameAndCountryName($result)
    {
        $toreturn = array();

        foreach ($result as $row) {
            $geoCityName = $row['geocityname'];
            $countryName = $row['countryName'];

            if (isset($row[0])) {
                $rowItem = $row[0];

                if (empty($rowItem->getHotel()->getCity())) {
                    $city = new \HotelBundle\Entity\AmadeusHotelCity();
                    $city->setId($rowItem->getHotel()->getAmadeusCityId());
                    $city->setCityId($rowItem->getHotel()->getCityId());

                    $rowItem->getHotel()->setCity($city);
                    unset($city);
                }

                $rowItem->getHotel()->getCity()->setName($geoCityName);
                $rowItem->getHotel()->getCity()->setCountryName($countryName);

                $toreturn[] = $rowItem;
                unset($rowItem);
            }

            unset($row, $geoCityName, $countryName);
        }

        return $toreturn;
    }

    //********************************************************************************************
    // User Booking Lists Functions
    public function searchUserHotelBookings($langCode, $options)
    {
        $where      = '';
        $whereArray = array();
        $params     = array();
        $nlimit     = '';
        $skip       = 0;

        $em = $this->getEntityManager();

        // WHERE
        $whereArray[]     = 'hr.userId = :userId';
        $params['userId'] = $options['userId'];
        $whereArray[]     = $em->createQueryBuilder()->expr()->eq('hr.transactionSourceId', $options['transactionSourceId']);
        if (isset($options['canceled']) && !is_null($options['canceled'])) {
            $whereArray[]             = $em->createQueryBuilder()->expr()->eq('hr.reservationStatus', ':canceledStatus');
            $params['canceledStatus'] = $this->container->getParameter('hotels')['reservation_canceled'];
        } elseif (isset($options['past']) && !is_null($options['past'])) {
            $whereArray[]        = $em->createQueryBuilder()->expr()->in('hr.reservationStatus', array($this->container->getParameter('hotels')['reservation_confirmed'], $this->container->getParameter('hotels')['reservation_modified']));
            $whereArray[]        = $em->createQueryBuilder()->expr()->lt('hr.fromDate', ':todayDate');
            $params['todayDate'] = date('Y-m-d');
        } elseif (isset($options['future']) && !is_null($options['future'])) {
            $whereArray[]        = $em->createQueryBuilder()->expr()->in('hr.reservationStatus', array($this->container->getParameter('hotels')['reservation_confirmed'], $this->container->getParameter('hotels')['reservation_modified']));
            $whereArray[]        = $em->createQueryBuilder()->expr()->gte('hr.fromDate', ':todayDate');
            $params['todayDate'] = date('Y-m-d');
        } else {
            $whereArray[] = $em->createQueryBuilder()->expr()->in('hr.reservationStatus', array(
                $this->container->getParameter('hotels')['reservation_confirmed'],
                $this->container->getParameter('hotels')['reservation_modified'],
                $this->container->getParameter('hotels')['reservation_canceled']
            ));
        }

        if (isset($options['fromDate']) && !empty($options['fromDate'])) {
            $whereArray[]       = $em->createQueryBuilder()->expr()->gte('hr.fromDate', ':fromDate');
            $params['fromDate'] = $options['fromDate'];
        }

        if (isset($options['toDate']) && !empty($options['toDate'])) {
            $whereArray[]     = $em->createQueryBuilder()->expr()->lte('hr.toDate', ':toDate');
            $params['toDate'] = $options['toDate'];
        }

        if (!empty($whereArray)) {
            $where = implode(" AND ", $whereArray);
        }

        // LIMIT
        if (isset($options['limit']) && !is_null($options['limit'])) {
            $nlimit = intval($options['limit']);
            $skip   = intval($options['page']) * $nlimit;
        }

        if (!isset($options['n_results'])) {
            $hotelName = ($langCode != 'en') ? 'COALESCE(ml.name, ch.name)' : 'ch.name';

            $qb = $em->createQueryBuilder()
                ->select('hr.id, hr.reference, hr.reservationProcessKey, hr.reservationProcessPassword, hr.controlNumber, '
                    .'hr.customerCurrency, hr.customerGrandTotal, '
                    .'hr.hotelCurrency, hr.hotelGrandTotal, '
                    .'hr.reservationStatus, hr.fromDate, hr.toDate, '
                    .'hr.hotelId, '.$hotelName.' AS hotelName, ch.stars')
                ->from('HotelBundle:HotelReservation', 'hr')
                ->innerJoin('HotelBundle:CmsHotel', 'ch', 'WITH', 'ch.id = hr.hotelId AND hr.reservationProcessKey IS NOT NULL');

            if ($langCode != 'en') {
                $qb->leftJoin('HotelBundle:MlHotel', 'ml', 'WITH', 'ch.id = ml.hotelId and ml.langCode=:langCode');
                $params['langCode'] = $langCode;
            }

            if (!empty($where)) {
                $qb->where("$where");
            }

            if (!empty($params)) {
                $qb->setParameters($params);
            }

            if (!empty($options['order'])) {
                foreach ($options['order'] as $col => $dir) {
                    $qb->orderBy('hr.'.$col, $dir);
                }
            } else {
                $qb->orderBy('hr.creationDate', 'DESC');
            }

            if (!empty($nlimit) && !is_null($nlimit)) {
                $qb->setMaxResults($nlimit)->setFirstResult($skip);
            }

            $query = $qb->getQuery();
            $res   = $query->getScalarResult();
        } else {
            $qb = $em->createQueryBuilder()
                ->select('COUNT(hr.id)')
                ->from('HotelBundle:HotelReservation', 'hr')
                ->innerJoin('HotelBundle:CmsHotel', 'ch', 'WITH', 'ch.id = hr.hotelId AND hr.reservationProcessKey IS NOT NULL');

            if (!empty($where)) {
                $qb->where("$where");
            }

            if (!empty($params)) {
                $qb->setParameters($params);
            }

            $query = $qb->getQuery();
            $res   = $query->getScalarResult();
            if (!empty($res) && isset($res)) {
                $res = $res[0][1];
            }
        }
        return $res;
    }

    public function getUserHotelBookingRoomInformation($reservationIds)
    {
        $qb = $this->createQueryBuilder('hrr')
            ->select('hrr.hotelReservationId, hrr.hotelRoomPrice, hrr.customerRoomPrice, hrr.roomStatus')
            ->innerJoin('HotelBundle:HotelReservation', 'hr', 'WITH', 'hr.id = hrr.hotelReservationId')
            ->where("hrr.hotelReservationId IN (:hotelReservationIds) AND hr.reservationStatus != 'Canceled'")
            ->setParameter('hotelReservationIds', $reservationIds);

        $query = $qb->getQuery();
        $res   = $query->getScalarResult();
        return $res;
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
    public function getHotelDivisions($hotelId, $mediaType, $categoryId, $divisionId, $withSubDivisions, $sortByGroup = false)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = 'SELECT
                        h.id AS hotel_id,
                        h.property_name AS hotel_name,
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
                        hi.media_settings AS media_settings,
                        hi.default_pic AS is_main_image
                    FROM
                        amadeus_hotel_city hc,
                        webgeocities wc,
                        hotel_divisions_categories hdc
                            LEFT JOIN(hotel_divisions_categories_group cg) ON (cg.id = hdc.hotel_division_category_group_id),
                        amadeus_hotel h,
                        (hotel_to_hotel_divisions hhd, hotel_to_hotel_divisions_categories hhdc)
                            LEFT JOIN(hotel_divisions hd) ON (hhdc.hotel_division_category_id = hd.hotel_division_category_id AND hhd.hotel_division_id = hd.id)
                            LEFT JOIN (amadeus_hotel_image hi) ON (hi.hotel_id = hhd.hotel_id AND hi.hotel_division_id = hd.id AND hi.tt_media_type_id = '.$mediaType.')

                            LEFT JOIN (hotel_divisions phd) ON (hd.parent_id = phd.id)
                            LEFT JOIN (hotel_to_hotel_divisions phhd) ON (phd.id = phhd.hotel_division_id AND phhd.hotel_id = hhd.hotel_id)
                    WHERE
                        hc.id = h.amadeus_city_id
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

        $result = array();
        while ($row    = $stmt->fetch()) {
            $division = new \HotelBundle\Model\HotelDivisionWith360Media();
            $division->setId($row['id']);
            $division->setName($row['name']);

            $hotel = new \HotelBundle\Model\HotelInfo();
            $hotel->setId($row['hotel_id']);
            $hotel->setName($row['hotel_name']);
            $hotel->setCountryCode($row['hotel_country_code']);
            $hotel->setLogo($row['hotel_logo']);
            $division->setHotel($hotel);

            $group = new \HotelBundle\Model\HotelDivisionCategoryGroup();
            $group->setId($row['group_id']);
            $group->setName($row['group_name']);

            $category = new \HotelBundle\Model\HotelDivisionCategory();
            $category->setId($row['category_id']);
            $category->setName($row['category_name']);
            $category->setGroup($group);
            $division->setCategory($category);

            $parent = new \HotelBundle\Model\HotelDivision();
            $parent->setId($row['parent_id']);
            $parent->setName($row['parent_name']);
            $division->setParent($parent);

            $image = new \HotelBundle\Model\HotelImage();
            $image->setFilename($row['image']);
            $image->setMediaType($row['media_type']);
            $image->setMediaSettings($row['media_settings']);
            $image->setDefaultPic($row['is_main_image']);
            $division->setImage($image);

            $result[] = $division;

            unset($row, $division, $hotel, $category, $parent, $image);
        }

        $stmt->closeCursor();

        return $result;
    }

    /**
     * This method checks if a given hotel has 360 images or not
     *
     * @param  Integer $hotelId
     * @param  Integer $mediaType
     * @return Boolean True if yes, false otherwise
     */
    public function has360($hotelId, $mediaType)
    {
        $query = $this->createQueryBuilder('hi')
            ->select('hi.hotelId')
            ->where("hi.hotelId = :hotelId AND hi.mediaTypeId = :mediaTypeId")
            ->setParameter(':hotelId', $hotelId)
            ->setParameter(':mediaTypeId', $mediaType)
            ->getQuery();

        $result = $query->getScalarResult();

        if (!empty($result) && isset($result)) {
            return (count($result) > 0);
        } else {
            return false;
        }
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
        $sql  = 'SELECT
                        hi.hotel_id AS hotelId,
                        h.property_name AS hotelName,
                        wc.country_code AS countryCode,
                        hi.filename AS imageSource,
                        hi.default_pic AS isMainImage,
                        hi.hotel_division_id AS divisionId,
                        IF( (hd.id IS NULL OR hhd.name is null OR hhd.name = \'\') , hd.name, hhd.name) AS divisionName,
                        hdc.name AS categoryName,
                        hd.hotel_division_category_id AS categoryId,
                        hd.parent_id AS parentId,
                        phd.name AS parentName,
                        phd.sort_order AS p_order,
                        hhd.sort_order
                    FROM
                        (amadeus_hotel_image hi, hotel_to_hotel_divisions hhd)
                        LEFT JOIN (hotel_divisions hd) ON (hi.hotel_division_id = hd.id AND hi.hotel_id = '.$hotelId.')
                        LEFT JOIN (hotel_divisions_categories hdc) ON (hd.hotel_division_category_id = hdc.id)
                        LEFT JOIN (hotel_to_hotel_divisions phd) ON (hd.parent_id = phd.hotel_division_id AND phd.hotel_id = hhd.hotel_id)
                        INNER JOIN (amadeus_hotel h) ON (hi.hotel_id = h.id)
                        INNER JOIN (amadeus_hotel_city hc) ON (h.amadeus_city_id = hc.id)
                        INNER JOIN (webgeocities wc) ON (hc.city_id = wc.id)
                    WHERE
                        hi.hotel_id = '.$hotelId.' AND hhd.hotel_id = '.$hotelId.' AND hi.hotel_division_id = hhd.hotel_division_id AND hi.tt_media_type_id = '.$mediaType.' AND hi.is_featured = 1
                    ORDER BY hi.sort_order ASC, hi.is_featured DESC, phd.sort_order ASC, hhd.sort_order ASC, hd.sort_order ASC, hd.parent_id ASC';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = array();
        while ($row    = $stmt->fetch()) {
            $hotelImage = new \HotelBundle\Model\HotelImage();
            $hotelImage->setFilename($row['imageSource']);
            $hotelImage->setDefaultPic($row['isMainImage']);

            $hotel = new \HotelBundle\Model\HotelInfo();
            $hotel->setId($row['hotelId']);
            $hotel->setName($row['hotelName']);
            $hotel->setCountryCode($row['countryCode']);
            $hotelImage->setHotel($hotel);

            $division = new \HotelBundle\Model\HotelDivision();
            $division->setId($row['divisionId']);
            $division->setName($row['divisionName']);
            $division->setOrder($row['sort_order']);

            $category = new \HotelBundle\Model\HotelDivisionCategory();
            $category->setId($row['categoryId']);
            $category->setName($row['categoryName']);
            $division->setCategory($category);

            $parent = new \HotelBundle\Model\HotelDivision();
            $parent->setId($row['parentId']);
            $parent->setName($row['parentName']);
            $parent->setOrder($row['p_order']);
            $division->setParent($parent);

            $hotelImage->setDivision($division);

            $result[] = $hotelImage;
            unset($row, $hotel, $division, $category);
        }

        $stmt->closeCursor();

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
                    amadeus_hotel h
                    LEFT JOIN (amadeus_hotel_city hc) ON (h.amadeus_city_id = hc.id)
                    LEFT JOIN (webgeocities wc) ON (hc.city_id = wc.id)
                    JOIN (cms_thingstodo ttd) ON (wc.id = ttd.city_id)
                WHERE
                    h.id = '.$hotelId.' LIMIT 1';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, 'HotelBundle\Model\Hotel360ThingsToDo');
        $stmt->closeCursor();

        return $result;
    }

    //********************************************************************************************
    // POI
    /**
     * HotelPoi - This method retrieves the POI for a certain hotel.
     * @param Integer $hotelId
     */
    public function getHotelPOI($hotelId)
    {
        $toReturn = array();

        if (!empty($hotelId)) {
            $conn = $this->getEntityManager()->getConnection();
            $sql  = "SELECT
                                poi.*,
                                d.name AS distance_poi_type_name,
                                h.property_name AS hotelName,
                                h.logo AS hotelLogo,
                                ahc.country_code AS hotelCountryCode
                            FROM hotel_poi poi
                            INNER JOIN amadeus_hotel h
                                ON poi.hotel_id = h.id
                            INNER JOIN amadeus_hotel_city ahc
                                ON h.amadeus_city_id = ahc.id
                            INNER JOIN distance_poi_type d
                                ON poi.distance_poi_type_id = d.id
                            WHERE poi.hotel_id = ? ";

            $stmt = $conn->prepare($sql);
            $stmt->execute(array($hotelId));

            while ($row = $stmt->fetch()) {
                $item = new \HotelBundle\Model\HotelPOI();

                $item->setName($row['name']);
                $item->setDistance($row['distance']);
                $item->setDistancePoiTypeId($row['distance_poi_type_id']);
                $item->setDistancePoiTypeName($row['distance_poi_type_name']);

                $hotelInfo = new \HotelBundle\Model\HotelInfo();
                $hotelInfo->setId($row['hotel_id']);
                $hotelInfo->setName($row['hotelName']);
                $hotelInfo->setLogo($row['hotelLogo']);
                $hotelInfo->setCountryCode($row['hotelCountryCode']);

                $item->setHotel($hotelInfo);

                $toReturn[] = $item;
            }

            $stmt->closeCursor();

            return $toReturn;
        }
    }
}
