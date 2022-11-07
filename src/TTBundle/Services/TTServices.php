<?php

namespace TTBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class TTServices
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

    /*
    * @getLanguagesList function return Languages List
    */
    public function getLanguagesList( $isUserLoggedIn )
    {
        return $this->em->getRepository('TTBundle:CmsLanguage')->getLanguagesList( $isUserLoggedIn );
    }

    /*
    * @cityHomeWhereIs function return cityHomeWhereIs List
    */
    public function cityHomeWhereIs( $lang, $limit = 15 )
    {
        $Results = array();
        $WhereIs_list = $this->em->getRepository('TTBundle:CmsHomeWhereIs')->cityHomeWhereIs( $lang, $limit );
        foreach ($WhereIs_list as $whereCity) 
        {
            $whereisCityId = $whereCity['h_cityId'];
            $cityname      = str_replace('Where is ', '', $whereCity['h_name']);
            if (!$whereCity['name']) {
                $whereisName = $whereCity['h_name'];
            } else {
                $whereisName = $whereCity['name'];
            }
            $awhereisAll['title']    = $this->utils->htmlEntityDecode($whereisName);
            $awhereisAll['titlealt'] = $this->utils->cleanTitleDataAlt($whereisName);
            $awhereisAll['link']     = $this->container->get('TTRouteUtils')->returnWhereIsLink( $lang, $cityname, $whereisCityId, '', '');
            $Results[]    = $awhereisAll;
        }
        return $Results;
    }

    /*
    * @aliasContentInfo function return alias info for selected id
    */
    public function aliasContentInfo( $id )
    {
        return $this->em->getRepository('TTBundle:Alias')->aliasContentInfo( $id );
    }

    /*
    * @getAliasInfo function return alias info for selected entityId
    */
    public function getAliasInfo( $entity_id )
    {
        return $this->em->getRepository('TTBundle:Alias')->getAliasInfo( $entity_id );
    }

    /*
    * @getAliasSeo function return alias seo info for selected url
    */
    public function getAliasSeo($url, $lang)
    {
        return $this->em->getRepository('TTBundle:AliasSeo')->getAliasSeo($url, $lang);
    }

    /*
    * @addPageNotFound function add PageNotFound url
    */
    public function addPageNotFound($url)
    {
        return $this->em->getRepository('TTBundle:PageNotFound')->addPageNotFound($url);
    }

    /*
    * @getMainEntityTypeGlobal function return MainEntityType list
    */
    public function getMainEntityTypeGlobal( $lang='en', $home_type = -1, $show_on_home = 1 )
    {
        $SOCIAL_ENTITY_THINGSTODO_CITY = $this->container->getParameter('SOCIAL_ENTITY_THINGSTODO_CITY');
        $SOCIAL_ENTITY_THINGSTODO_DETAILS = $this->container->getParameter('SOCIAL_ENTITY_THINGSTODO_DETAILS');
        $SOCIAL_ENTITY_HOTEL = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $SOCIAL_ENTITY_HOTEL_SELECTED_CITY = $this->container->getParameter('SOCIAL_ENTITY_HOTEL_SELECTED_CITY');
        $SOCIAL_ENTITY_RESTAURANT = $this->container->getParameter('SOCIAL_ENTITY_RESTAURANT');
        $SOCIAL_ENTITY_AIRPORT = $this->container->getParameter('SOCIAL_ENTITY_AIRPORT');
        $SOCIAL_ENTITY_FLIGHT = $this->container->getParameter('SOCIAL_ENTITY_FLIGHT');
        $SOCIAL_ENTITY_DEAL = $this->container->getParameter('SOCIAL_ENTITY_DEAL');
        $SOCIAL_ENTITY_DEAL_ATTRACTIONS = $this->container->getParameter('SOCIAL_ENTITY_DEAL_ATTRACTIONS');
        return $this->em->getRepository('TTBundle:MainEntityType')->getMainEntityTypeGlobal( $lang, $home_type, $show_on_home, $SOCIAL_ENTITY_THINGSTODO_CITY, $SOCIAL_ENTITY_THINGSTODO_DETAILS, $SOCIAL_ENTITY_HOTEL, $SOCIAL_ENTITY_HOTEL_SELECTED_CITY, $SOCIAL_ENTITY_RESTAURANT, $SOCIAL_ENTITY_AIRPORT, $SOCIAL_ENTITY_FLIGHT, $SOCIAL_ENTITY_DEAL, $SOCIAL_ENTITY_DEAL_ATTRACTIONS );
    }


    /*
    * @getMainEntityTypeGlobalData function return MainEntityType data
    */
    public function getMainEntityTypeGlobalData( $lang, $mainEntityType_array, $page_type = -1 )
    {
        $mainEntityArray = array();
        $itemArray       = array();
        
        $this->show_flights_block = 1;
        if ($this->container->hasParameter('SHOW_FLIGHTS_BLOCK')) $this->show_flights_block = $this->container->getParameter('SHOW_FLIGHTS_BLOCK');
        
        $this->show_deals_block = 0;
        if ($this->container->hasParameter('SHOW_DEALS_BLOCK')) $this->show_deals_block = $this->container->getParameter('SHOW_DEALS_BLOCK');
        
        foreach ($mainEntityType_array as $item) {
            if (!isset($itemArray[$item['m_id']])) {
                $itemArray[$item['m_id']]            = array();
                $itemArray[$item['m_id']]['id']      = $item['m_id'];
                $itemArray[$item['m_id']]['name']    = $this->utils->htmlEntityDecode($item['m_name']);
                $itemArray[$item['m_id']]['namealt'] = $this->utils->cleanTitleDataAlt($item['m_name']);
                if ($item['mlm_name'] != '') {
                    $itemArray[$item['m_id']]['name']    = $this->utils->htmlEntityDecode($item['mlm_name']);
                    $itemArray[$item['m_id']]['namealt'] = $this->utils->cleanTitleDataAlt($item['mlm_name']);
                }
                $itemArray[$item['m_id']]['entityType']    = $item['m_entityTypeId'];
                $itemArray[$item['m_id']]['entityId']      = $item['m_entityId'];
                $itemArray[$item['m_id']]['entityTypeKey'] = $item['e_entityTypeKey'];
                $itemArray[$item['m_id']]['entityName']    = '';
                $itemArray[$item['m_id']]['entityCode']    = '';
                $itemArray[$item['m_id']]['city']          = '';
                $itemArray[$item['m_id']]['viewAll']       = '';
                if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_FLIGHT') && $itemArray[$item['m_id']]['entityId'] > 0) {
                    if ($this->show_flights_block == 0) continue;
                    if ($item['ai_name'] != '') {
                        $airport_name                        = $this->utils->htmlEntityDecode($item['ai_name']);
                        $airport_code                        = $item['ai_airportCode'];
                        $sourcepath                          = 'media/hotels/hotelbooking/';
                        $sourcename                          = $item['ai_image'];
                        $itemArray[$item['m_id']]['img']     = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 285, 160, 'thumb285160', $sourcepath, $sourcepath, 65);
                        $itemArray[$item['m_id']]['viewAll'] = $this->container->get('TTRouteUtils')->returnFlyToAirportLink( $lang, $itemArray[$item['m_id']]['name'], $airport_name, $airport_code);
                    }
                } else if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_THINGSTODO_DETAILS')) {
                    $itemArray[$item['m_id']]['viewAll'] = $this->utils->generateLangRoute( $lang, '_virtual_tour');
                } else if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
                    $itemArray[$item['m_id']]['viewAll'] = $this->utils->generateLangRoute( $lang, '_best_hotels_in_the_world');
                } else if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_RESTAURANT')) {
                    $itemArray[$item['m_id']]['viewAll'] = $this->utils->generateLangRoute( $lang, '_best_restaurants_restaurants');
                } else if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_THINGSTODO_CITY') && $itemArray[$item['m_id']]['entityId'] > 0) {
                    if ($item['t_title'] != '') {
                        $itemArray[$item['m_id']]['city'] = $item['tw_city'];
                    }
                    if ($item['a_alias'] != '') {
                        $itemArray[$item['m_id']]['viewAll'] = $this->utils->generateLangURL($lang, '/'.$item['a_alias']);
                    }
                } else if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_THINGSTODO_CITY') && $itemArray[$item['m_id']]['entityId'] == 0) {
                    $itemArray[$item['m_id']]['viewAll'] = $this->utils->generateLangRoute( $lang, '_thingsToDo');
                } else if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_DEAL')) {
                    $itemArray[$item['m_id']]['viewAll'] = $this->utils->generateLangRoute( $lang, '_city_activities');
                } else if ($itemArray[$item['m_id']]['entityType'] == $this->container->getParameter('SOCIAL_ENTITY_DEAL_ATTRACTIONS')) {
                    $itemArray[$item['m_id']]['viewAll'] = $this->utils->generateLangRoute( $lang, '_attractions_skip_the_line');
                }
            }
            if (!isset($itemArray[$item['m_id']]['subItem'])) {
                $itemArray[$item['m_id']]['subItem'] = array();
            }
            $subItemArray = array();
            if ($page_type != $this->container->getParameter('PAGE_TYPE_FLIGHT')) {
                $subItemArray['id']      = $item['l_id'];
                $subItemArray['name']    = $this->utils->htmlEntityDecode($item['l_name']);
                $subItemArray['namealt'] = $this->utils->cleanTitleDataAlt($item['l_name']);
                if ($item['mll_name'] != '') {
                    $subItemArray['name']    = $this->utils->htmlEntityDecode($item['mll_name']);
                    $subItemArray['namealt'] = $this->utils->cleanTitleDataAlt($item['mll_name']);
                }
                $subItemArray['entityId']      = $item['l_entityId'];
                $subItemArray['cityId']        = $item['l_cityId'];
                $subItemArray['entityType']    = $item['l_entityTypeId'];
                $subItemArray['city']          = '';
                $subItemArray['img']           = '';
                $subItemArray['link']          = '';
                $subItemArray['reviews_count'] = 0;
                $subItemArray['tours_number']  = 0;
                $subItemArray['score']         = '';
                switch ($subItemArray['entityType']) {
                    case $this->container->getParameter('SOCIAL_ENTITY_RESTAURANT'):
                        if ($item['r_id'] == '') continue;

                        $rh_name         = $this->utils->htmlEntityDecode($item['rh_name']);
                        $rh_namealt      = $this->utils->cleanTitleDataAlt($item['rh_name']);
                        $objects_name    = $this->utils->htmlEntityDecode($item['r_name']);
                        $objects_namealt = $this->utils->cleanTitleDataAlt($item['r_name']);

                        if ($rh_name != '') {
                            $objects_name    = $rh_name.' - '.$objects_name;
                            $objects_namealt = $rh_namealt.' - '.$objects_namealt;
                        }

                        if ($subItemArray['name'] == '') {
                            $subItemArray['name']    = $objects_name;
                            $subItemArray['namealt'] = $objects_namealt;
                        }
                        $restaurantInfo      = $this->container->get('RestaurantServices')->getRestaurantInfo($item['r_id']);
                        $hotel_id            = $restaurantInfo['r_hotelId'];
                        $country             = strtolower($restaurantInfo['rw_countryCode']);
                        $media_360_base_path = $this->container->getParameter('MEDIA_360_BASE_PATH').'hotels/';
                        $sourcepath          = $media_360_base_path.''.$country.'/'.$hotel_id.'/'.$restaurantInfo['hdc_id'].'/'.$restaurantInfo['hd_id'].'/'.$restaurantInfo['hd2_id'].'/';
                        $sourcename          = $restaurantInfo['hi_filename'];
                        $subItemArray['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 284, 159, 'thumb284159', $sourcepath, $sourcepath, 50);

                        $subItemArray['city']    = $this->utils->htmlEntityDecode($restaurantInfo['rw_name']);
                        $subItemArray['link']    = $this->container->get('TTRouteUtils')->returnRestaurant360Link($lang, $item['r_id'], $item['rh_name'].' '.$item['r_name'], $subItemArray['city']);
                        break;
                    case $this->container->getParameter('SOCIAL_ENTITY_HOTEL'):
                        if ($item['h_name'] == '') continue;
                        $objects_name            = $this->utils->htmlEntityDecode($item['h_name']);
                        $objects_namealt         = $this->utils->cleanTitleDataAlt($item['h_name']);
                        //if ($subItemArray['name'] == '') {
                        $subItemArray['name']    = $objects_name;
                        $subItemArray['namealt'] = $objects_namealt;
                        //}

                        $subItemArray['img']     = $this->container->get("HRSServices")->createImageSource($item, 1, 4);
                        $subItemArray['city']    = $item['h_city'];
                        if ($item['hw_city'] != '') {
                            $subItemArray['city'] = $item['hw_city'];
                        }
                        $subItemArray['link'] = $this->container->get('TTRouteUtils')->returnHotelDetailedLink($lang, $objects_name, $subItemArray['entityId']);

                        if ($page_type != $this->container->getParameter('PAGE_TYPE_HOME')) {
//                            $trustyou             = $this->container->get('ReviewServices')->getMetaReview($item['hs_trustyouId']);
//                            $subItemArray['reviews_count'] = $trustyou['reviews_count'];
//                            $subItemArray['score']         = round(intval($trustyou['summary']['score']) / 2 * 10) / 10;
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ENTITY_HOTEL_SELECTED_CITY'):
                        if ($item['hc_name'] == '') continue;
                        $objects_name    = $this->utils->htmlEntityDecode($item['hc_name']);
                        $objects_namealt = $this->utils->cleanTitleDataAlt($item['hc_name']);
                        if ($subItemArray['name'] == '') {
                            $subItemArray['name']    = $objects_name;
                            $subItemArray['namealt'] = $objects_namealt;
                        }
                        $sourcepath          = 'media/hotels/hotelbooking/';
                        $sourcename          = $item['hc_image'];
                        $subItemArray['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 285, 160, 'thumb285160', $sourcepath, $sourcepath, 65);
                        if ($item['hc_cityId'] > 0) {
                            $subItemArray['link'] = $this->container->get('TTRouteUtils')->returnHotelsInLink($lang, $item['hc_name'], $item['hc_cityId']);
                        } else {
                            $subItemArray['link'] = $this->container->get('TTRouteUtils')->returnBookinSearchResultLink($lang, $item['hc_name'], '', $item['hc_locationId'], 0);
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ENTITY_THINGSTODO_CITY'):
                        if ($item['t_title'] == '') continue;
                        $objects_name    = $this->utils->htmlEntityDecode($item['t_title']);
                        $objects_namealt = $this->utils->cleanTitleDataAlt($item['t_title']);
                        if ($item['mlt_title'] != '') {
                            $objects_name    = $this->utils->htmlEntityDecode($item['mlt_title']);
                            $objects_namealt = $this->utils->cleanTitleDataAlt($item['mlt_title']);
                        }
                        if ($subItemArray['name'] == '') {
                            $subItemArray['name']    = $objects_name;
                            $subItemArray['namealt'] = $objects_namealt;
                        }
                        $sourcepath           = 'media/thingstodo/';
                        $sourcename           = $item['t_image'];
                        $subItemArray['img']  = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 285, 160, 'thingstodo285160', $sourcepath, $sourcepath, 65);
                        $subItemArray['link'] = '';
                        if ($item['a_alias'] != '') {
                            $subItemArray['link'] = $this->utils->generateLangURL($lang, '/'.$item['a_alias']);
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ENTITY_DEAL'):
                        if ($this->show_deals_block == 0) continue;
                        if ($subItemArray['cityId']) {
                            if ($item['dc_cityName'] == '') continue;
                            $objects_name    = $this->utils->htmlEntityDecode($item['dc_cityName']);
                            $objects_namealt = $this->utils->cleanTitleDataAlt($item['dc_cityName']);
                            if ($subItemArray['name'] == '') {
                                $subItemArray['name']    = $objects_name;
                                $subItemArray['namealt'] = $objects_namealt;
                            }
                            $subItemArray['img']  = $item['dc_image'];
                            $subItemArray['link'] = $this->container->get('TTRouteUtils')->returnDealsSearchLink($lang, $objects_name);
                            if ($page_type == $this->container->getParameter('PAGE_TYPE_DEALS')) {
                                $subItemArray['tours_number'] = $this->container->get('DealServices')->getDealTypeToursNumber(array('city' => $item['dc_cityId']), 'all');
                            }
                        } else {
                            if ($item['dd_dealName'] == '') continue;
                            $objects_name    = $this->utils->htmlEntityDecode($item['dd_dealName']);
                            $objects_namealt = $this->utils->cleanTitleDataAlt($item['dd_dealName']);
                            if (isset($item['mld_dealName']) && $item['mld_dealName'] != '') {
                                $objects_name    = $this->utils->htmlEntityDecode($item['mld_dealName']);
                                $objects_namealt = $this->utils->cleanTitleDataAlt($item['mld_dealName']);
                            }
                            if ($subItemArray['name'] == '') {
                                $subItemArray['name']    = $objects_name;
                                $subItemArray['namealt'] = $objects_namealt;
                            }
                            $subItemArray['city'] = $this->utils->htmlEntityDecode($item['ddc_cityName']);
                            $subItemArray['img']  = $this->container->get('DealServices')->getDealDefaultImage($subItemArray['entityId'], true, 284, 159);
                            $subItemArray['link'] = $this->container->get('TTRouteUtils')->returnDealDetailsLink($subItemArray['entityId'], $item['dd_dealName'], $item['ddc_cityName'], $item['dealType'], $lang);
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ENTITY_DEAL_ATTRACTIONS'):
                        if ($this->show_deals_block == 0) continue;
                        if ($item['dta_name'] == '') continue;
                        $objects_name    = $this->utils->htmlEntityDecode($item['dta_name']);
                        $objects_namealt = $this->utils->cleanTitleDataAlt($item['dta_name']);
                        if ($subItemArray['name'] == '') {
                            $subItemArray['name']    = $objects_name;
                            $subItemArray['namealt'] = $objects_namealt;
                        }
                        $subItemArray['city'] = $this->utils->htmlEntityDecode($item['dtaw_city']);
                        $subItemArray['img']  = $item['dta_imageUrl'];
                        $subItemArray['link'] = $this->container->get('TTRouteUtils')->returnDealNameSearchLink($lang, $objects_name);
                        if ($page_type == $this->container->getParameter('PAGE_TYPE_DEALS')) {
                            $subItemArray['tours_number'] = $this->container->get('DealServices')->getDealTypeToursNumber(array('dealName' => $item['dta_name']), 'attractions');
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'):
                        if ($item['ai_name'] == '') continue;
                        $objects_name    = $this->utils->htmlEntityDecode($item['ai_name']);
                        $objects_namealt = $this->utils->cleanTitleDataAlt($item['ai_name']);
                        if ($subItemArray['name'] == '') {
                            $subItemArray['name']    = $objects_name;
                            $subItemArray['namealt'] = $objects_namealt;
                        }
                        $subItemArray['link'] = $this->container->get('TTRouteUtils')->returnFlyToAirportLink($lang, $item['l_name'], $objects_name, $item['ai_airportCode']);
                        break;
                    case $this->container->getParameter('SOCIAL_ENTITY_THINGSTODO_DETAILS'):
                        if ($item['td_title'] == '') continue;
                        $objects_name         = $this->utils->htmlEntityDecode($item['td_title']);
                        $objects_namealt      = $this->utils->cleanTitleDataAlt($item['td_title']);
                        if ($item['mltd_title'] != '') {
                            $objects_name    = $this->utils->htmlEntityDecode($item['mltd_title']);
                            $objects_namealt = $this->utils->cleanTitleDataAlt($item['mltd_title']);
                        }
                        $objects_name = '360 '.$objects_name;
                        if ($subItemArray['name'] == '') {
                            $subItemArray['name']    = $objects_name;
                            $subItemArray['namealt'] = $objects_namealt;
                        }
                        $subItemArray['city'] = $this->utils->htmlEntityDecode($item['tdw_city']);
                        $sourcepath           = 'media/thingstodo/';
                        $sourcename           = $item['td_image'];
//                        if ($item['td_image360'] != '') {
//                            $sourcename = $item['td_image360'];
//                            $sourcepath = 'media/thingstodo/360-photos-virtual-tour/';
//                        }
                        $subItemArray['img']  = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 285, 160, 'thingstodo285160', $sourcepath, $sourcepath, 65);
                        if ($item['tdd_id'] != '') {
                            $subItemArray['link'] = $this->utils->generateLangURL($lang, '/'.$item['tdta_alias'].'/'.$item['td_slug']);
                        } else {
                            $topthingstodoList_array = array();
                            if (!isset($topthingstodoList[$item['td_parentId']])) {
                                $topthingstodoList[$item['td_parentId']] = $this->container->get('ThingsToDoServices')->getRelatedThingsToDoList(array(
                                    'parent_id' => $item['td_parentId'],
                                    'lang' => $lang,
                                    'orderby' => 'orderDisplay',
                                    'order' => 'd'
                                ));
                            }
                            foreach ($topthingstodoList[$item['td_parentId']] as $item_data) {
                                $topthingstodoList_array[] = $item_data['t_id'];
                            }
                            $itemposition = array_search($subItemArray['entityId'], $topthingstodoList_array) + 1;
                            $itempage     = ceil($itemposition / 10);
                            $link         = '';
                            if ($item['tdta_alias'] != '') {
                                $link = $this->utils->generateLangURL($lang, '/'.$item['tdta_alias']);
                            }
                            if ($itempage > 1) {
                                $link .= '/'.$itempage;
                            }
                            $subItemArray['link'] = $link.'#'.$itemposition;
                        }
                        break;
                }
            }
            $itemArray[$item['m_id']]['subItem'][] = $subItemArray;
        }
        $mainEntityArray = $itemArray;
        return $mainEntityArray;
    }

    /*
    * @activeCampaigns function return active Campaigns
    */
    public function activeCampaigns($campaignSpecs, $detailedInfo = true)
    {
        return $this->em->getRepository('TTBundle:Campaign')->activeCampaigns($campaignSpecs, $detailedInfo);
    }

    /*
    * @activeCampaignMarketingLabels function return active Campaign Marketing Labels
    */
    public function activeCampaignMarketingLabels($campaignSpecs)
    {
        return $this->em->getRepository('TTBundle:Campaign')->activeCampaignMarketingLabels($campaignSpecs);
    }

    /*
    * @isCouponUsed function check if coupon is used
    */
    public function isCouponUsed($campaign_id, $coupon_code)
    {
        return $this->em->getRepository('TTBundle:Coupon')->isCouponUsed($campaign_id, $coupon_code);
    }

    /*
    * @addNewCoupon function add New Coupon
    */
    public function addNewCoupon($campaign_id, $coupon_code, $entity_id, $entity_type_id)
    {
        return $this->em->getRepository('TTBundle:Coupon')->addNewCoupon($campaign_id, $coupon_code, $entity_id, $entity_type_id);
    }

    /*
    * @getCouponSource function coupon source info
    */
    public function getCouponSource($entity_type)
    {
        return $this->em->getRepository('TTBundle:CouponSource')->getCouponSource($entity_type);
    }

    /*
    * @getDiscountDetails function return Discount Details info
    */
    public function getDiscountDetails($discount_id)
    {
        return $this->em->getRepository('TTBundle:Discount')->getDiscountDetails($discount_id);
    }

    /*
     * Adding a new Passenger Type Quote
     *
     * @param $options
     *
     * @return passengerTypeQuoteId
    */
    public function addPassengerTypeQuote( $srch_options )
    {
        $default_opts = array(
            'module_id' => 1,
            'module_transaction_id' => 0,
            'passenger_type' => 'ADT',
            'price_quote' => NULL
        );
        $options = array_merge($default_opts, $srch_options);
        
        return $this->em->getRepository('TTBundle:PassengerTypeQuote')->addPassengerTypeQuote( $options );
    }
}
