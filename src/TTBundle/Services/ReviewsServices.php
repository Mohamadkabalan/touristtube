<?php

namespace TTBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ReviewsServices
{
    protected $em;
    protected $utils;
    protected $container;

    public function __construct(EntityManager $em, Utils $utils, ContainerInterface $container)
    {
        $this->em            = $em;
        $this->utils         = $utils;
        $this->container     = $container;
        $this->translator    = $this->container->get('translator');
    }

    /**
     * add reviews entity for a given user
     * @param integer $user_id the user id
     * @param integer $entity_type the entity type
     * @param integer $entity_id the entity id
     * @param string $description the reviews description
     * @return integer | false the reviews id or false if not inserted
     */
    public function addReviews( $user_id, $entity_type, $entity_id, $description, $hideUser=0 )
    {
        $entity_details = array(
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL') => array('table' => 'DiscoverHotelsReviews', 'entity_value' => 'hotel'),
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') => array('table' => 'DiscoverPoiReviews', 'entity_value' => 'poi'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT') => array('table' => 'AirportReviews', 'entity_value' => 'airport'),
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL_HRS') => array('table' => 'CmsHotelReviews', 'entity_value' => 'hotel_hrs')
            );

        if (!isset($entity_details[$entity_type]))
            return false;
           
        $table = $entity_details[$entity_type]['table'];
        $entity_value = $entity_details[$entity_type]['entity_value'];

        return $this->em->getRepository('TTBundle:'.$table)->addReviews( $user_id, $entity_id, $description, $hideUser, $entity_value, $table );
    }

    /**
     * add reviews entity for a given user
     * @param integer $user_id the user id
     * @param integer $entity_type the entity type
     * @param integer $entity_id the entity id
     * @param string $filename the image name
     * @return integer | false the reviews id or false if not inserted
     */
    public function addReviewPageImage( $user_id, $entity_type, $entity_id, $filename )
    {
        $entity_details = array(
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL') => array('table' => 'DiscoverHotelsReviews', 'entity_value' => 'hotel'),
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') => array('table' => 'DiscoverPoiReviews', 'entity_value' => 'poi'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT') => array('table' => 'AirportReviews', 'entity_value' => 'airport')
            );

        if (!isset($entity_details[$entity_type]))
            return false;

        $table = $entity_details[$entity_type]['table'];
        $entity_value = $entity_details[$entity_type]['entity_value'];

        return $this->em->getRepository('TTBundle:'.$table)->addReviewPageImage( $user_id, $entity_id, $filename, $entity_value, $table );
    }
    
    /*
    * @getReviewsList
    */
    public function getReviewsList( $entity_id, $entity_type, $limit = 6, $page = 0, $n_results = false)
    {
       $entity_details = array(
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL') => array('table' => 'DiscoverHotelsReviews', 'entity_value' => 'hotelId'),
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') => array('table' => 'DiscoverPoiReviews', 'entity_value' => 'poiId'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT') => array('table' => 'AirportReviews', 'entity_value' => 'airportId')
            );

        if (!isset($entity_details[$entity_type]))
            return false;

        $table = $entity_details[$entity_type]['table'];
        $entity_value = $entity_details[$entity_type]['entity_value'];

        return $this->em->getRepository('TTBundle:'.$table)->getReviewsList( $entity_id, $entity_value, $table, $limit, $page, $n_results );
    }

    /*
    * @getDiscoverImages
    */
    public function getDiscoverImages( $entity_id, $entity_type, $user_id = 0, $exclude_user = 0 )
    {
        $entity_details = array(
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL') => array('table' => 'DiscoverHotelsImages', 'entity_value' => 'hotelId'),
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') => array('table' => 'DiscoverPoiImages', 'entity_value' => 'poiId'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT') => array('table' => 'AirportImages', 'entity_value' => 'airportId')
            );

        if (!isset($entity_details[$entity_type]))
            return false;

        $table = $entity_details[$entity_type]['table'];
        $entity_value = $entity_details[$entity_type]['entity_value'];

        return $this->em->getRepository('TTBundle:'.$table)->getDiscoverImages( $entity_id, $entity_value, $user_id, $exclude_user );
    }

    /*
    * @getDiscoverImages
    */
    public function getCityDiscoverHotelsCount( $country_code, $state_code, $city_id )
    {
        return $this->em->getRepository('TTBundle:DiscoverHotels')->getCityDiscoverHotelsCount( $country_code, $state_code, $city_id );
    }

    /*
    * @getCityDiscoverPoiCount
    */
    public function getCityDiscoverPoiCount( $country_code, $state_code, $city_id )
    {
        return $this->em->getRepository('TTBundle:DiscoverPoi')->getCityDiscoverPoiCount( $country_code, $state_code, $city_id );
    }

    /*
    * @getCityDiscoverHotelsList
    */
    public function getCityDiscoverHotelsList( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        return $this->em->getRepository('TTBundle:DiscoverHotels')->getCityDiscoverHotelsList( $country_code, $state_code, $city_id, $limit );
    }

    /*
    * @getCityDiscoverHotelsListDiscover
    */
    public function getCityDiscoverHotelsListDiscover( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        return $this->em->getRepository('TTBundle:DiscoverHotels')->getCityDiscoverHotelsListDiscover( $country_code, $state_code, $city_id, $limit );
    }

    /*
    * @getCityDiscoverPoiListDiscover
    */
    public function getCityDiscoverPoiListDiscover( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        return $this->em->getRepository('TTBundle:DiscoverPoi')->getCityDiscoverPoiListDiscover( $country_code, $state_code, $city_id, $limit );
    }

    /*
    * @getCityDiscoverPoiList
    */
    public function getCityDiscoverPoiList( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        return $this->em->getRepository('TTBundle:DiscoverPoi')->getCityDiscoverPoiList( $country_code, $state_code, $city_id, $limit );
    }

    /*
    * @getDiscoverPoiNearByList
    */
    public function getDiscoverPoiNearByList( $limit = null, $orderby = null )
    {
        return $this->em->getRepository('TTBundle:DiscoverPoi')->getDiscoverPoiNearByList( $limit, $orderby );
    }

    /*
    * @getHotelsDefaultPic
    */
    public function getHotelsDefaultPic( $item_id )
    {
        return $this->em->getRepository('TTBundle:DiscoverHotelsImages')->getHotelsDefaultPic( $item_id );
    }

    /*
    * @getPoiDefaultPic
    */
    public function getPoiDefaultPic( $item_id )
    {
        return $this->em->getRepository('TTBundle:DiscoverPoiImages')->getPoiDefaultPic( $item_id );
    }

    /*
    * @getAirportDefaultPic
    */
    public function getAirportDefaultPic( $item_id )
    {
        return $this->em->getRepository('TTBundle:AirportImages')->getAirportDefaultPic( $item_id );
    }

    /*
    * @getHotelInfo
    */
    public function getHotelInfo( $item_id )
    {
        return $this->em->getRepository('TTBundle:DiscoverHotels')->getHotelInfo( $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL') );
    }

    /*
    * @getPoiInfo
    */
    public function getPoiInfo( $item_id )
    {
        return $this->em->getRepository('TTBundle:DiscoverPoi')->getPoiInfo( $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') );
    }

    /*
    * @getAirportInfo
    */
    public function getAirportInfo( $item_id )
    {
        return $this->em->getRepository('TTBundle:Airport')->getAirportInfo( $item_id );
    }

    /*
    * @getAirportInfoList
    */
    public function getAirportInfoList($list_id)
    {
        return $this->em->getRepository('TTBundle:Airport')->getAirportInfoList( $list_id );
    }

    /*
    * @getAirportByCodeInfo
    */
    public function getAirportByCodeInfo( $airport_code )
    {
        return $this->em->getRepository('TTBundle:Airport')->getAirportByCodeInfo( $airport_code );
    }

    /*
    * @getReviewsInfo
    */
    public function getReviewsInfo( $id, $entity_type )
    {
        $entity_details = array(
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL') => array('table' => 'DiscoverHotelsReviews', 'entity_value' => 'hotelId'),
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') => array('table' => 'DiscoverPoiReviews', 'entity_value' => 'poiId'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT') => array('table' => 'AirportReviews', 'entity_value' => 'airportId')
            );

        if (!isset($entity_details[$entity_type]))
            return false;

        $table = $entity_details[$entity_type]['table'];
        $entity_value = $entity_details[$entity_type]['entity_value'];

        return $this->em->getRepository('TTBundle:'.$table)->getReviewsInfo( $id );
    }

    /*
    * @removeReviews
    */
    public function removeReviews( $user_id, $id, $entity_type )
    {
        $entity_details = array(
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL') => array('table' => 'DiscoverHotelsReviews', 'entity_value' => 'hotelId'),
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') => array('table' => 'DiscoverPoiReviews', 'entity_value' => 'poiId'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT') => array('table' => 'AirportReviews', 'entity_value' => 'airportId')
            );

        if (!isset($entity_details[$entity_type]))
            return false;

        $table = $entity_details[$entity_type]['table'];
        $entity_value = $entity_details[$entity_type]['entity_value'];

        return $this->em->getRepository('TTBundle:'.$table)->removeReviews( $user_id, $id, $table );
    }

    /*
    * @removeReviewPageImage
    */
    public function removeReviewPageImage( $user_id, $id, $entity_type )
    {
        $entity_details = array(
            $this->container->getParameter('SOCIAL_ENTITY_HOTEL') => array('table' => 'DiscoverHotelsImages', 'entity_value' => 'hotelId'),
            $this->container->getParameter('SOCIAL_ENTITY_LANDMARK') => array('table' => 'DiscoverPoiImages', 'entity_value' => 'poiId'),
            $this->container->getParameter('SOCIAL_ENTITY_AIRPORT') => array('table' => 'AirportImages', 'entity_value' => 'airportId')
            );

        if (!isset($entity_details[$entity_type]))
            return false;

        $table = $entity_details[$entity_type]['table'];
        $entity_value = $entity_details[$entity_type]['entity_value'];

        return $this->em->getRepository('TTBundle:'.$table)->removeReviewPageImage( $user_id, $id, $table );
    }

    /*
    * @removeReviewPageImage
    */
    public function addRate( $user_id, $entity_id, $entity_type, $rating, $rate_type = 0 )
    {
        $rateRecord = $this->socialRateRecordGet( $user_id, $entity_id, $entity_type, $rate_type );

        if( !$rateRecord )
        {
            return $this->em->getRepository('TTBundle:CmsSocialRatings')->addRate( $user_id, $entity_id, $entity_type, $rating, $rate_type );
        }
        else
        {
            return $this->em->getRepository('TTBundle:CmsSocialRatings')->updateRate( $user_id, $entity_id, $entity_type, $rating, $rate_type );
        }
    }

    /*
    * @socialRateRecordGet
    */
    public function socialRateRecordGet( $user_id, $entity_id, $entity_type, $rate_type = 0 )
    {
        return $this->em->getRepository('TTBundle:CmsSocialRatings')->socialRateRecordGet( $user_id, $entity_id, $entity_type, $rate_type );
    }

    /*
    * @socialRated
    */
    public function socialRated( $user_id, $entity_id, $entity_type, $rate_type = 0 )
    {
        $rateRecord = $this->socialRateRecordGet( $user_id, $entity_id, $entity_type, $rate_type );
        if ( !$rateRecord ) return false;
        return intval($rateRecord['sr_ratingValue']);
    }

    /*
    * @socialRateAverage
    */
    public function socialRateAverage( $entity_id, $entity_type, $rate_type = 0 )
    {
        return $this->em->getRepository('TTBundle:CmsSocialRatings')->socialRateAverage( $entity_id, $entity_type, $rate_type );
    }

    /**
     * This method retrieves the map data of a selected bag id.
     * @return Array   The map data.
     */
    public function showOnMap( $type, $id, $user_id = 0, $lang = 'en' )
    {
        $twigData                    = array();
        $twigData['type']            = $type;
        $twigData['showInfobox']     = 1;
        $twigData['markerImageBlue'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot_blue.png');
        $twigData['markerImage'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_empty.png');
        $twigData['LanguageGet'] = $lang;
        $twigData['zoomdefault'] = 14;

        switch( $type )
        {
            case 'b':
                $bagInfo  = $this->container->get('UserServices')->getBagInfo( $id );
                $bagItems = $this->container->get('UserServices')->returnBagItemData( $user_id, $id, $lang );

                if ( $bagInfo['cb_userId'] != $user_id ) {
                    return false;
                }
                $items_data = array();
                foreach ( $bagItems as $item )
                {
                    $row = array();
                    $row['img']  = $item['img'];
                    $row['markerImage']  = $item['markerImage'];
                    $row['name']  = $item['namealt'];
                    $row['link'] = $item['link'];
                    $row['latitude'] = $item['latitude'];
                    $row['longitude'] = $item['logitude'];
                    $row['price'] = '';
                    $items_data[] = $row;
                }

                $twigData['data'] = $items_data;
                $twigData['mapName']     = " bag: ". $this->utils->cleanTitleDataAlt($bagInfo['cb_name']);
                $twigData['latdefault']  = 1;
                $twigData['longdefault'] = 1;
                $twigData['zoomdefault'] = 2;
            break;
            case 'h':
                $objects1 = $this->container->get('ReviewsServices')->getHotelInfo( $id );
                if (!$objects1) return false;
                $objects  = $objects1[0];
                $items_data = array();
                $row = array();

                $rows_res = $objects1[3];
                
                $dimagepath = '';
                $dimage = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/landmark-icon.jpg');
                if ($rows_res && $rows_res->getFilename())
                {
                    $dimagepath = 'media/discover/';
                    $dimage     = $rows_res->getFilename();
                }else if (sizeof($objects1) > 1 && isset($objects1[1]) && $objects1[1]->getImage() != null && $objects1[1]->getImage())
                {
                    $dimagepath = 'media/thingstodo/';
                    $dimage     = $objects1[1]->getImage();
                }

                if ($dimagepath)
                {
                    $row['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 140, 80, 'rate-review-14080');
                } else {
                    $row['img'] = $dimage;
                }

                $row['markerImage']  = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');
                $row['name']         = $this->utils->cleanTitleDataAlt( $objects->getHotelName() );
                $row['link']         = $this->container->get('TTRouteUtils')->returnHotelReviewLink( $lang, $objects->getId(), $row['name'] );
                $row['latitude']     = $objects->getLatitude();
                $row['longitude']    = $objects->getLongitude();
                $row['price'] = '';
                $items_data[] = $row;

                $twigData['data'] = $items_data;
                $twigData['mapName']     = " Hotel: ". $row['name'];
                $twigData['latdefault']  = $objects->getLatitude();
                $twigData['longdefault'] = $objects->getLongitude();
            break;
            case 'p':
                $objects1 = $this->container->get('ReviewsServices')->getPoiInfo( $id );
                if (!$objects1) return false;
                $objects  = $objects1[0];
                $items_data = array();
                $row = array();

                $rows_img = $objects->getImages();
                if (sizeof($rows_img)) {
                    $rows_res = $rows_img[0];
                }
                
                $dimagepath = '';
                $dimage = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/landmark-icon.jpg');
                if ($rows_res && $rows_res->getFilename())
                {
                    $dimagepath = 'media/discover/';
                    $dimage     = $rows_res->getFilename();
                }else if (sizeof($objects1) > 1 && isset($objects1[1]) && $objects1[1]->getImage() != null && $objects1[1]->getImage())
                {
                    $dimagepath = 'media/thingstodo/';
                    $dimage     = $objects1[1]->getImage();
                }

                if ($dimagepath)
                {
                    $row['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 140, 80, 'rate-review-14080');
                } else {
                    $row['img'] = $dimage;
                }

                $row['markerImage']  = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot_blue.png');
                $row['name']  = $this->utils->cleanTitleDataAlt( $objects->getName() );
                $row['link']  = $this->container->get('TTRouteUtils')->returnThingstodoReviewLink( $lang, $objects->getId(), $row['name'] );
                $row['latitude'] = $objects->getLatitude();
                $row['longitude'] = $objects->getLongitude();
                $row['price'] = '';
                $items_data[] = $row;

                $twigData['data'] = $items_data;
                $twigData['mapName']     = " Point Of Interest: ". $row['name'];
                $twigData['latdefault']  = $objects->getLatitude();
                $twigData['longdefault'] = $objects->getLongitude();
            break;
        }

        return $twigData;
    }

    public function getReviewAutocompleteQR( $entity_type, $term, $limit, $routepath = '' )
    {
        $type = '';
        $ret1 = array();
        if ($entity_type == $this->container->getParameter('SOCIAL_ENTITY_HOTEL'))
        {
            $type         = 'h';
            $srch_options = array
            (
                'page_src' => 'HRS_DIRECT',
                'term' => $term
            );

            $url_source = 'getReviewAutocompleteQR - getHotelSearch - URL: '.$routepath;
            $queryStringResultHotel = $this->container->get('ElasticServices')->getHotelSearch( $srch_options, $url_source );
            $ret1 = $queryStringResultHotel[0];

        } 
        elseif ($entity_type == $this->container->getParameter('SOCIAL_ENTITY_RESTAURANT'))
        {
            $type              = 'r';
            $ret1 = array();
        } 
        elseif ($entity_type == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'))
        {
            $type       = 'p';

            $srch_options = array
            (
                'limit' => $limit,
                'term' => $term
            );
            $url_source = 'getReviewAutocompleteQR - getPoiNearLocation - poiSearch - URL: '.$routepath;
            $poiNearLocation = $this->container->get('ElasticServices')->getPoiNearLocation( $srch_options, $url_source );
            $ret1 = $poiNearLocation[0];

        } 
        elseif ($entity_type == $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'))
        {
            $type           = 'ai';

            $srch_options = array
            (
                'limit' => $limit,
                'from' => 0,
                'term' => $term
            );

            $url_source = 'getReviewAutocompleteQR - getAirportsSearch - URL: '.$routepath;
            $queryStringResult = $this->container->get('ElasticServices')->getAirportsSearch( $srch_options, $url_source );
            $ret1 = $queryStringResult[0];

        }
        return array($ret1,$type);
    }

    public function returnAirportsLocation($document)
    {
        $str1 = '';

        if (isset($document['_source']['location']['city']['name']) && $document['_source']['location']['city']['name']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['city']['name'];
        }
        if (isset($document['_source']['location']['country']['name']) && $document['_source']['location']['country']['name'] == 'US') {
            if (isset($document['_source']['vendor']['state']['name']) && $document['_source']['vendor']['state']['name']) {
                if ($str1) $str1 .= ', ';
                $str1 .= $document['_source']['vendor']['state']['name'];
            }
        }
        if (isset($document['_source']['location']['country']['name']) && $document['_source']['location']['country']['name']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['country']['name'];
        }
        return $str1;
    }

    public function returnPoiLocation($document)
    {
        $str1 = '';
        if (isset($document['_source']['location']['address'])) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['address'];
        }
        if (isset($document['_source']['location']['city']['id']) && $document['_source']['location']['city']['id'] > 0) {
            $city_array = $this->container->get('CitiesServices')->worldcitiespopInfo(intval($document['_source']['location']['city']['id']));
            $city_array = $city_array[0];
            $city_name  = $this->utils->htmlEntityDecode($city_array->getName());
            if ($city_name) {
                if ($str1) $str1 .= ', ';
                $str1 .= $city_name;
            }
        }
        if (isset($document['_source']['location']['country']['code']) && $document['_source']['location']['country']['code'] == 'US') {
            if (isset($document['_source']['vendor']['state']['name']) && $document['_source']['vendor']['state']['name']) {
                if ($str1) $str1 .= ', ';
                $str1 .= $document['_source']['vendor']['state']['name'];
            }
        }
        if (isset($document['_source']['location']['country']['name']) && $document['_source']['location']['country']['name']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['country']['name'];
        }
        return $str1;
    }

    public function returnHRSHotelsLocation($document)
    {
        $document['_source']['location'] = $document['_source']['location'][0];
        $str1 = '';
        if ($document['_source']['location']['address_line_1']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['address_line_1'];
        }
        if ($document['_source']['location']['address_line_2']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['address_line_2'];
        }
        if ( $str1 == '') {
            if (isset($document['_source']['location']['city']['name']) && $document['_source']['location']['city']['name']) {
                if ($str1) $str1 .= ', ';
                $str1 .= $document['_source']['location']['city']['name'];
            }
            if (isset($document['_source']['location']['country']['code']) && $document['_source']['location']['country']['code'] == 'US') {
                if (isset($document['_source']['location']['state']['code']) && $document['_source']['location']['state']['code']) {
                    $state_array = $this->container->get('CitiesServices')->worldStateInfo($document['_source']['location']['country']['code'], $document['_source']['location']['state']['code']);
                    $state_name = $this->utils->htmlEntityDecode($state_array[0]->getStateName());
                    $state        = $state_name;
                    if ($str1) $str1 .= ', ';
                    $str1 .= $state_name;
                }
            }
        }
        if (isset($document['_source']['location']['country']['name']) && $document['_source']['location']['country']['name']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['country']['name'];
        }
        return $str1;
    }

    public function returnHotelsLocation($document)
    {
        $str1 = '';
        if ($document['_source']['location']['address']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['address'];
        }
        if ($document['_source']['location']['address'] == '') {
            if (isset($document['_source']['location']['city']['name']) && $document['_source']['location']['city']['name']) {
                if ($str1) $str1 .= ', ';
                $str1 .= $document['_source']['location']['city']['name'];
            }
            if (isset($document['_source']['location']['country']['code']) && $document['_source']['location']['country']['code'] == 'US') {
                if (isset($document['_source']['location']['state']['code']) && $document['_source']['location']['state']['code']) {
                    $state_array = $this->container->get('CitiesServices')->worldStateInfo($document['_source']['location']['country']['code'], $document['_source']['location']['state']['code']);
                    $state_name = $this->utils->htmlEntityDecode($state_array[0]->getStateName());
                    $state        = $state_name;
                if ($str1) $str1 .= ', ';
                    $str1 .= $state_name;
                }
                }
        }
        if (isset($document['_source']['location']['country']['name']) && $document['_source']['location']['country']['name']) {
            if ($str1) $str1 .= ', ';
            $str1 .= $document['_source']['location']['country']['name'];
        }
        return $str1;
    }

    public function getPoiListDiscoverData( $lang, $cityid, $countrycode, $reststate, $page, $vallimit, $url_source )
    {
        $pois_array = array();
        $options = array(
            'limit' => $page,
            'page' => $vallimit,
            'countryCode' => $countrycode,
            'cityId' => $cityid,
            'search_try' => 1,
            'imageExists' => 1, 
            'oldQuery' => 1
        );
        $poiss      = $this->container->get('ElasticServices')->getPoiNearLocation($options, $url_source);

        $poissarr   = $poiss[0];
        $poisscount = $poiss[1];
        
        if ($poisscount) {
            foreach ($poissarr as $v_item) {
                $varr              = array();
                $varr['stars']     = 0;
                $varr['id']        = intval($v_item['_source']['id']);
                $varr['longitude'] = $v_item['_source']['location']['coordinates']['lon'];
                $varr['latitude']  = $v_item['_source']['location']['coordinates']['lat'];
                $varr['type']      = $this->container->getParameter('SOCIAL_ENTITY_LANDMARK');
                $varr['name']      = $this->utils->htmlEntityDecode($v_item['_source']['name']);
                $varr['namealt']   = $this->utils->cleanTitleDataAlt($v_item['_source']['name']);
                $varr['title']     = addslashes($varr['name']);
                $varr['link']      = $this->container->get('TTRouteUtils')->returnThingstodoReviewLink( $lang, $v_item['_source']['id'], $v_item['_source']['name']);
                if (sizeof($v_item['_source']['images']) && $v_item['_source']['images'][0]['filename']) {
                    $dimage     = $v_item['_source']['images'][0]['filename'];
                    $dimagepath = 'media/discover/';
                    if (isset($v_item['_source']['images'][0]['extra']) && $v_item['_source']['images'][0]['extra'] == 1) {
                        $dimagepath = 'media/thingstodo/';
                    }
                    $varr['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 162, 'discover284162');
                } else {
                    $image = $this->container->get('ReviewsServices')->getPoiDefaultPic($v_item['_source']['id']);
                    if ($image) {
                        $dimagepath  = 'media/discover/';
                        $dimage      = $image->getFilename();
                        $image_pa    = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 162, 'discover284162');
                        $varr['img'] = $image_pa;
                    } else {
                        $dimagepath  = 'media/images/';
                        $dimage      = 'landmark-icon2.jpg';
                        $varr['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 162, 'discover284162');
                    }
                }
                $pois_array[] = $varr;
            }
        }

        return [$pois_array, $poisscount];
    }
}
