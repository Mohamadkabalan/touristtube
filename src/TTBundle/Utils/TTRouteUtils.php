<?php

namespace TTBundle\Utils;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;

class TTRouteUtils
{
    protected $utils;
    protected $container;
    private $storage_engine = '';
    private $subdomain_suffix = '';
    
    public function __construct(Utils $utils, ContainerInterface $container)
    {
        $this->utils         = $utils;
        $this->container     = $container;
        if ($this->container->hasParameter('STORAGE_ENGINE')) $this->storage_engine = $this->container->getParameter('STORAGE_ENGINE');
        if( $this->container->hasParameter('subdomain_suffix') ) $this->subdomain_suffix = $this->container->getParameter('subdomain_suffix');
        $this->translator    = $this->container->get('translator');
    }

    public function generateMediaURL($path, $full_path = false, $isRest = false)
    {
        if (substr($path, 0, 1) != '/') $path = '/'.$path;
        $prefix_route = $this->getMediaBucketURL($full_path);

        return ($isRest) ? preg_replace('/^\//', '', $path) : $prefix_route.$path;
    }

    public function getMediaBucketURL($full_path = false)
    {
        $prefix_route = '';
        if( $this->storage_engine == 'aws_s3' || $full_path )
        {
            $subdomain_root = 'www';
            if( $this->storage_engine == 'aws_s3' ) $subdomain_root = 'static2';
            $currentServerURL = $this->UriCurrentServerURL();
			
			$suffix = $this->subdomain_suffix;
			
            $prefix_route = $currentServerURL[0].$subdomain_root.$suffix.'.'.$currentServerURL[1];
        }
        return $prefix_route;
    }

    public function getRelatedDiscoverPagination($countItem, $vallimit, $page, $count = 2, $class = "discover_a")
    {
        $number_of_pagesH     = ceil($countItem / $vallimit);
        $search_paging_output = '';
        if ($number_of_pagesH > 1) {
            $pagenxt              = $page + 1;
            if ($pagenxt > $number_of_pagesH) $pagenxt              = $number_of_pagesH;
            $pageprev             = $page - 1;
            if ($pageprev < 1) $pageprev             = 1;
            $search_paging_output = '<ul>';
            $search_paging_output .= '<li data-page="1"><a rel="nofollow" class="first_pg" title="'.$this->translator->trans('first page').'"></a></li>';
            $search_paging_output .= '<li data-page="'.$pageprev.'"><a rel="nofollow" class="prev_pg" title="'.$this->translator->trans('previous page').'"></a></li>';
            $xStart               = $page - $count;
            if ($xStart < 1) $xStart               = 1;
            $xEnd                 = $xStart + $count * 2;
            //            $xStart = $page - 2;
            //            if ($xStart < 1) $xStart = 1;
            //            $xEnd = $xStart + 5;
            if ($xEnd > $number_of_pagesH) $xEnd                 = $number_of_pagesH;

            for ($x = $xStart; $x <= $xEnd; $x++) {
                if ($x == $page) $search_paging_output .= '<li data-page="'.$x.'" class="active"><a rel="nofollow" class="'.$class.'" title="/page '.$x.'">'.$x.'</a><span>.</span></li>';
                else $search_paging_output .= '<li data-page="'.$x.'"><a rel="nofollow" class="'.$class.'" title="/page '.$x.'">'.$x.'</a>'.($x < $xEnd ? '<span>.</span>' : '').'</li>';
            }
            $search_paging_output .= '<li data-page="'.$pagenxt.'"><a rel="nofollow" class="next_pg" title="'.$this->translator->trans('next page').'"></a></li>';
            $search_paging_output .= '<li data-page="'.$number_of_pagesH.'"><a rel="nofollow" class="last_pg" title="'.$this->translator->trans('last page').'"></a></li>';
            $search_paging_output .= '</ul>';
        }
        return $search_paging_output;
    }

    public function getRelatedChannelPagination($channelscount, $limit, $page, $link, $pageseperate, $count, $page_type = '', $lang = 'en' )
    {
        $number_of_pagesCh    = ceil($channelscount / $limit);
        $search_paging_output = '';
        if ($number_of_pagesCh > 1) {
            $pagenxt              = $page + 1;
            if ($pagenxt > $number_of_pagesCh) $pagenxt              = $number_of_pagesCh;
            $pageprev             = $page - 1;
            if ($pageprev < 1) $pageprev             = 1;
            if ($pageprev != 1) $pageprev             = $pageseperate.$pageprev;
            else $pageprev             = '';
            $search_paging_output = '<ul>';
            $search_paging_output .= '<li><a class="first_pg" title="'.$link.' '.$this->translator->trans('first page').'" href="'.$this->utils->generateLangURL( $lang, '/'.$link, $page_type).'"></a></li>';
            $search_paging_output .= '<li><a class="prev_pg" title="'.$link.' '.$this->translator->trans('previous page').'" href="'.$this->utils->generateLangURL( $lang, '/'.$link, $page_type).''.$pageprev.'"></a></li>';
            $xStart               = $page - $count;
            if ($xStart < 1) $xStart               = 1;
            $xEnd                 = $xStart + $count * 2;
            if ($xEnd > $number_of_pagesCh) $xEnd                 = $number_of_pagesCh;

            for ($x = $xStart; $x <= $xEnd; $x++)
            {
                $liclass              = '';
                $pgnumbr              = $pageseperate.$x;
                if ($x == $page) $liclass              = 'active';
                if ($x == 1) $pgnumbr              = '';
                $search_paging_output .= '<li data-page="'.$x.'" class="'.$liclass.'"><a class="discover_a" title="'.$link.' page '.$x.'" href="'.$this->utils->generateLangURL( $lang, '/'.$link.''.$pgnumbr, $page_type).'">'.$x.'</a>'.($x
                    < $xEnd ? '<span>.</span>' : '').'</li>';
            }

            if ($pagenxt != 1)
            {
                $pagenxt              = $pageseperate.$pagenxt;
            } else {
                $pagenxt              = '';
            }

            if ($number_of_pagesCh != 1) $number_of_pagesCh    = $pageseperate.$number_of_pagesCh;
            else $number_of_pagesCh    = '';
            $search_paging_output .= '<li><a class="next_pg" title="'.$link.' '.$this->translator->trans('next page').'" href="'.$this->utils->generateLangURL( $lang, '/'.$link.''.$pagenxt, $page_type).'"></a></li>';
            $search_paging_output .= '<li><a class="last_pg" title="'.$link.' '.$this->translator->trans('last page').'" href="'.$this->utils->generateLangURL( $lang, '/'.$link.''.$number_of_pagesCh, $page_type).'"></a></li>';
            $search_paging_output .= '</ul>';
        }
        return $search_paging_output;
    }

    public function returnBookinSearchResultLink($lang, $title, $country, $loc_id, $h_id, $fromD = '', $fromDC = '', $toD = '', $toDC = '', $stars = 0, $dates = '', $src = '')
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk1   = '';
        if (!empty($src)) {
            $lnk = '/hotel-booking-resultsTT?city[name]='.$titled;
            if ($loc_id > 0) {
                $lnk1 .= '&city[id]='.$loc_id;
            }
            $lnk1 .= '&entityType='.$this->container->getParameter('SOCIAL_ENTITY_CITY');
        } else {
            $lnk = '/hotel-booking-results?hotelCityName='.$titled;
            if ($loc_id > 0) {
                $lnk1 .= '&locationId='.$loc_id;
            }
        }

        if ($country != '') {
            $lnk1 .= '&country='.$country;
        }
        if ($h_id > 0) {
            $lnk1 .= '&hotelId='.$h_id;
        }
        if ($fromD != '') {
            $lnk1 .= '&fromDate='.$fromD;
        }
        if ($fromDC != '') {
            $lnk1 .= '&fromDateC='.$fromDC;
        }
        if ($toD != '') {
            $lnk1 .= '&toDate='.$toD;
        }
        if ($toDC != '') {
            $lnk1 .= '&toDateC='.$toDC;
        }
        if ($stars > 0) {
            $lnk1 .= '&stars='.$stars;
        }
        if ($dates != '') {
            $lnk1 .= '&dates='.$dates;
        }

        return $this->utils->generateLangURL($lang, $lnk.$lnk1);
    }

    public function returnHotelReviewLink($lang, $id, $title)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        return $this->utils->generateLangURL($lang, '/'.$titled.'-review-H'.$id);
    }

    public function returnRestaurantReviewLink($lang, $id, $title)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        return $this->utils->generateLangURL($lang, '/'.$titled.'-review-R'.$id, 'restaurants');
    }

    public function returnThingstodoReviewLink($lang, $id, $title)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        return $this->utils->generateLangURL($lang, '/'.$titled.'-review-T'.$id);
    }

    public function returnAirportReviewLink($lang, $id, $title)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        return $this->utils->generateLangURL($lang, '/'.$titled.'-review-A'.$id);
    }

    public function returnRestaurant360Link($lang, $id, $title, $city )
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);

        $city = $this->utils->cleanTitleData($city);
        $city = str_replace('-', '+', $city);
        $lnk    = '/360-restaurant-'.$titled."-$city-".$id;

        return $this->utils->generateLangURL($lang, $lnk, 'restaurants');
    }

    public function returnDiscoverDetailedLink($lang, $title, $city_id = 0, $state = '', $country = '')
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/discover-'.$titled;
        $lnk1   = '';
        if ($city_id > 0) {
            if( $state =='' ) $state ='00';
            $lnk1 .= ($lnk1 != '') ? '_' : '-';
            $lnk1 .= $city_id;
        }
        if ($country != '') {
            $lnk1 .= ($lnk1 != '') ? '_' : '-';
            $lnk1 .= $country;
        }
        if ($state != '') {
            $lnk1 .= ($lnk1 != '') ? '_' : '-';
            $lnk1 .= $state;
        }
        return $this->utils->generateLangURL($lang, $lnk.$lnk1);
    }

    public function returnWhereIsLink($lang, $title, $city_id, $state = '', $country = '')
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/where-is-'.$titled;
        if ($city_id > 0) $lnk    .= '-C_'.$city_id;
        else if ($state != '') $lnk    .= '-S_'.$state.'_'.$country;
        else $lnk    .= '-CO_'.$country;
        return $this->utils->generateLangURL($lang, $lnk, 'where-is');
    }

    public function returnChannelsSearchLink($lang, $title='', $city_id=0, $state = '', $country = '')
    {
        $lnk    = '/channels-search-';
        if ($title !=''){
            $titled = $this->utils->cleanTitleData($title);
            $titled = str_replace('-', '+', $titled);
            $lnk   .= $titled.'-';
        }
        if ($city_id > 0) $lnk    .= '-CI_'.$city_id;
        else if ($state != '') $lnk    .= '-S_'.$state.'_'.$country;
        else $lnk    .= '-CO_'.$country;
        return $this->utils->generateLangURL($lang, $lnk, 'channels');
    }

    public function returnChannelsCategoryLink($lang, $title='')
    {
        $lnk    = '/channels-category-';
        if ($title !=''){
            $titled = $this->utils->cleanTitleData($title);
            $titled = str_replace('-', '+', $titled);
            $lnk   .= $titled;
        }
        return $this->utils->generateLangURL($lang, $lnk, 'channels');
    }

    public function returnChannelLink($lang, $section='', $parameter )
    {
        $lnk    = '/channel';

        if( $section == 'create' )
        {
            $lnk ='/create-channel';
        }
        else if( $section!= '' )
        {
            $lnk .='-'.$section;
        }

        $lnk   .= '/'.$parameter;

        return $this->utils->generateLangURL($lang, $lnk, 'channels');
    }

    public function returnSocialTypeFromLink($link)
    {
        if (strpos($link, 'facebook')) {
            return 'facebook';
        } else if (strpos($link, 'google')) {
            return 'google-plus';
        } else if (strpos($link, 'twitter')) {
            return 'twitter';
        } else if (strpos($link, 'instagram')) {
            return 'instagram';
        } else if (strpos($link, 'youtube')) {
            return 'youtube';
        } else if (strpos($link, 'linkedin')) {
            return 'linkedin';
        } else if (strpos($link, 'pinterest')) {
            return 'pinterest';
        } else {
            return 'www';
        }
    }

    public function returnExternalLink($link)
    {
        if (preg_match("#https?://#", $link) === 0) {
            $link = 'http://'.$link;
        }
        return $link;
    }

    public function returnHotelDetailedLink($lang, $title, $id, $minPriceOfferVars = null)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/hotel-details-'.$titled.'-'.$id;

        if ($minPriceOfferVars && is_array($minPriceOfferVars)) {
            $varIndex = 0;

            foreach ($minPriceOfferVars as $var => $value) {
                $lnk .= ($varIndex ? '&' : '?').$var.'='.$value;

                $varIndex ++;
            }
        }

        return $this->utils->generateLangURL($lang, $lnk);
    }

    public function returnHotelReviewsLink($lang, $title, $id)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/hotel-reviews-'.$titled.'-'.$id;
        return $this->utils->generateLangURL($lang, $lnk);
    }

    public function returnBookingDetailsLink($lang, $key)
    {
        $lnk = '/booking-details-'.$key;
        return $this->utils->generateLangURL($lang, $lnk);
    }

    public function returnFlyToAirportLink($lang, $city_name, $airport_name='', $airport_code='')
    {
        $city_name = $this->utils->cleanTitleData($city_name);
        $city_name = str_replace('-', '+', $city_name);
        $lnk    = '/fly-to-'.$city_name;
        $airport_name = $this->utils->cleanTitleData($airport_name);
        $airport_name = str_replace('-', '+', $airport_name);
        if ($airport_name !=''){
            $lnk    .= '/'.$airport_name;
            if ($airport_code !=''){
                $lnk    .= '-'.$airport_code;
            }
        }
        return $this->utils->generateLangURL($lang, $lnk);
    }

    public function returnHotelsInLink($lang, $title, $city_id, $state = '', $country = '', $page = 1, $src = '')
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/hotels-in-'.$titled;
        if ($city_id > 0) $lnk    .= '-C_'.$city_id;
        else if ($state != '') $lnk    .= '-S_'.$state.'_'.$country;
        else $lnk    .= '-CO_'.$country;
        if ($page > 1) $lnk    .= '_'.$page;
        if (!empty($src)) $lnk    .= '?src=TT&entityType='.$this->container->getParameter('SOCIAL_ENTITY_CITY');
        return $this->utils->generateLangURL($lang, $lnk);
    }

    public function returnRestaurantsInLink($lang, $title, $city_id, $state = '', $country = '', $page = 1)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/restaurants-in-'.$titled;
        if ($city_id > 0) $lnk    .= '-C_'.$city_id;
        else if ($state != '') $lnk    .= '-S_'.$state.'_'.$country;
        else $lnk    .= '-CO_'.$country;
        if ($page > 1) $lnk    .= '_'.$page;
        return $this->utils->generateLangURL($lang, $lnk, 'restaurants');
    }

    public function returnRestaurantsNearByLink($lang, $title, $item_id, $page = 1, $type = 1)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/restaurants-near-by-'.$titled;
        if ($item_id > 0) $lnk    .= '_'.$item_id;
        if ($page < 1) $page   = 1;
        $lnk    .= '_'.$page.'_'.$type;
        return $this->utils->generateLangURL($lang, $lnk, 'restaurants');
    }

    public function returnHotelsNearByLink($lang, $title, $item_id, $page = 1)
    {
        $titled = $this->utils->cleanTitleData($title);
        $lnk    = '/hotels-near-by-'.$titled;
        if ($item_id > 0) $lnk    .= '_'.$item_id;
        if ($page < 1) $page   = 1;
        $lnk    .= '_'.$page;
        return $this->utils->generateLangURL($lang, $lnk, 'nearby');
    }

    public function returnAttractionsNearByLink($lang, $title, $item_id, $page = 1)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/attractions-near-by-'.$titled;
        if ($item_id > 0) $lnk    .= '_'.$item_id;
        if ($page < 1) $page   = 1;
        $lnk    .= '_'.$page;
        return $this->utils->generateLangURL($lang, $lnk, 'nearby');
    }

    /*
     * Creating the City Activities URL
     *
     * @param $lang
     * @param $cityName
     *
     * @return url
     */
    public function returnDealsSearchLink($lang, $cityName)
    {
        $titled = $this->utils->cleanTitleData($cityName);
        $titled = str_replace('-', '+', $cityName);

        $lnk   = '/';
        $corpo = 'deals';
        if ($this->utils->isCorporateSite()) {
            $lnk   .= 'corporate/';
            $corpo = 'corporate';
        }
        $lnk .= 'activities/'.$titled;

        return $this->utils->generateLangURL($lang, $lnk, $corpo);
    }

    /*
     * Creating the DealName Search URL
     *
     * @param $lang
     * @param $title
     *
     * @return url
     */
    public function returnDealNameSearchLink($lang, $title)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $lnk    = '/';
        $corpo  = 'deals';
        if ($this->utils->isCorporateSite()) {
            $lnk   .= 'corporate/';
            $corpo = 'corporate';
        }

        $lnk .= 'attractions/'.$titled;
        return $this->utils->generateLangURL($lang, $lnk, $corpo);
    }

    /*
     * Creating the Deal Details URL
     *
     * @param $dealId
     * @param $dealName
     * @param $cityName
     * @param $dealType
     * @param $lang
     *
     * @return url
     */
    public function returnDealDetailsLink($dealId, $dealName, $cityName, $dealType, $lang = 'en')
    {
        $titled = $this->utils->cleanTitleData($dealName);
        $titled = str_replace(' ', '-', $dealName);

        $city = $this->utils->cleanTitleData($cityName);
        $city = str_replace(' ', '-', $cityName);

        $lnk   = '/';
        $corpo = 'deals';
        if ($this->utils->isCorporateSite()) {
            $lnk   .= 'corporate/';
            $corpo = 'corporate';
        }
        $lnk .= $dealType.'/'.strtolower($city).'/'.$titled.'/'.$dealId;

        return $this->utils->generateLangURL($lang, $lnk, $corpo);
    }

    public function UriCurrentServerURL()
    {
        global $request;
        $prefix                        = 'http';
        $HTTPS_server                  = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $SERVER_NAME_server            = $this->container->getParameter('domain'); //$request->server->get('SERVER_NAME', '');
        $SERVER_PORT_server            = $request->server->get('SERVER_PORT', '');
        if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
            $prefix .= "s";
        }
        $prefix .= "://";
        if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
            $SERVER_NAME_server = $SERVER_NAME_server.":".$SERVER_PORT_server;
        }
        return array(
            $prefix,
            $SERVER_NAME_server
        );
    }

    public function UriCurrentPageURLCanonicalForLG($url)
    {
        global $request;
        $prefix                        = 'http';
        $HTTPS_server                  = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $SERVER_PORT_server            = $request->server->get('SERVER_PORT', '');
        if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
            $prefix .= "s";
        }
        $prefix  .= "://";
        $pageURL = '';
        if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
            $pageURL .= "touristtube.com:".$SERVER_PORT_server.$url;
        } else {
            $pageURL .= "touristtube.com".$url;
        }
        return array($prefix, $pageURL);
    }

    /**
     * gets current page url for language
     * return string the page url
     */
    public function UriCurrentPageURLForLG()
    {
        global $request;
        $prefix                        = 'http';
        $HTTPS_server                  = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $REQUEST_URI_server            = $request->server->get('REQUEST_URI', '');
        $SERVER_NAME_server            = $request->server->get('HTTP_HOST', '');
        $SERVER_PORT_server            = $request->server->get('SERVER_PORT', '');
        if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
            $prefix .= "s";
        }
        $prefix .= "://";
        if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
            $prefix .= $SERVER_NAME_server.":".$SERVER_PORT_server;
        } else {
            $prefix .= $SERVER_NAME_server;
        }
        $pageURL = $REQUEST_URI_server;
        global $GLOBAL_LANG;
        if ($GLOBAL_LANG != '') {
            $linksArray = explode('/'.$GLOBAL_LANG.'/', $pageURL);
            if (substr($linksArray[0], 0, 1) == '/') {
                $linksArray[0] = substr($linksArray[0], 1, strlen($linksArray[0]));
            }
            if (substr($linksArray[0], -1) == '/') {
                $linksArray[0] = substr($linksArray[0], 0, strlen($linksArray[0]) - 1);
            }
            if (sizeof($linksArray) > 1) {
                if ($linksArray[0] != '') $linksArray[0] = '/'.$linksArray[0];
                return array($prefix, $linksArray[0], $linksArray[1]);
            }else {
                if (substr($linksArray[0], 0, 12) == 'app_dev.php/') {
                    $linksArray[1] = substr($linksArray[0], 12, strlen($linksArray[0]));
                    $linksArray[0] = '/app_dev.php';
                    return array($prefix, $linksArray[0], $linksArray[1]);
                } else if ($linksArray[0] == 'app_dev.php') return array($prefix, '/'.$linksArray[0], '');
                else return array($prefix, '', $linksArray[0]);
            }
        } else {
            if (substr($pageURL, 0, 1) == '/') $pageURL = substr($pageURL, 1, strlen($pageURL));
            if ($pageURL == 'app_dev.php') return array($prefix, '/'.$pageURL, '');
            else return array($prefix, '', $pageURL);
        }
    }

    public function UriCanonicalPageURLForLG($url = NULL)
    {
        global $request;
        $prefix                        = 'http';
        $HTTPS_server                  = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $REQUEST_URI_server            = $request->server->get('REQUEST_URI', '');
        $SERVER_NAME_server            = $request->server->get('HTTP_HOST', '');
        $SERVER_PORT_server            = $request->server->get('SERVER_PORT', '');
        if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
            $prefix .= "s";
        }
        $prefix .= "://";
        if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
            $prefix .= $SERVER_NAME_server.":".$SERVER_PORT_server;
        } else {
            $prefix .= $SERVER_NAME_server;
        }
        if ($url == NULL) $url                = $REQUEST_URI_server;
        $URI_server_arr     = explode($SERVER_NAME_server, $url);
        if (sizeof($URI_server_arr) > 1) $REQUEST_URI_server = $URI_server_arr[1];
        $pageURL            = $REQUEST_URI_server;
        global $GLOBAL_LANG;
        if ($GLOBAL_LANG != '') {
            $linksArray = explode('/'.$GLOBAL_LANG.'/', $pageURL);
            if (substr($linksArray[0], 0, 1) == '/') {
                $linksArray[0] = substr($linksArray[0], 1, strlen($linksArray[0]));
            }
            if (substr($linksArray[0], -1) == '/') {
                $linksArray[0] = substr($linksArray[0], 0, strlen($linksArray[0]) - 1);
            }
            if (sizeof($linksArray) > 1) {
                if ($linksArray[0] != '') $linksArray[0] = '/'.$linksArray[0];
                return array($prefix, $linksArray[0], $linksArray[1]);
            }else {
                if (substr($linksArray[0], 0, 12) == 'app_dev.php/') {
                    $linksArray[1] = substr($linksArray[0], 12, strlen($linksArray[0]));
                    $linksArray[0] = '/app_dev.php';
                    return array($prefix, $linksArray[0], $linksArray[1]);
                } else if ($linksArray[0] == 'app_dev.php') return array($prefix, '/'.$linksArray[0], '');
                else return array($prefix, '', $linksArray[0]);
            }
        } else {
            if (substr($pageURL, 0, 1) == '/') $pageURL = substr($pageURL, 1, strlen($pageURL));
            if ($pageURL == 'app_dev.php') return array($prefix, '/'.$pageURL, '');
            else return array($prefix, '', $pageURL);
        }
    }

    public function UriCurrentPageURLRoute()
    {
        global $request;
        $REQUEST_URI_server = $request->server->get('REQUEST_URI', '');
        $pageURL            = $REQUEST_URI_server;
        if ($pageURL == '/') $pageURL            = "";
        $char2              = substr($pageURL, 0, 1);
        if ($char2 == '/') $pageURL            = substr($pageURL, 1, strlen($pageURL));
        return $pageURL;
    }

    public function UriCurrentPageURL()
    {
        global $request;
        $pageURL                       = 'http';
        $HTTPS_server                  = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $SERVER_NAME_server            = $request->server->get('HTTP_HOST', '');
        $SERVER_PORT_server            = $request->server->get('SERVER_PORT', '');
        $REQUEST_URI_server            = $request->server->get('REQUEST_URI', '');
        if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
            $pageURL .= $SERVER_NAME_server.":".$SERVER_PORT_server.$REQUEST_URI_server;
        } else {
            $pageURL .= $SERVER_NAME_server.$REQUEST_URI_server;
        }
        return $pageURL;
    }

    public function currentServerURL()
    {
        global $request;
        $pageURL                       = 'http';
        $HTTPS_server                  = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $SERVER_NAME_server            = $request->server->get('HTTP_HOST', '');
        $SERVER_PORT_server            = $request->server->get('SERVER_PORT', '');
        if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if (isset($SERVER_PORT_server) && $SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
            $pageURL .= $SERVER_NAME_server.":".$SERVER_PORT_server;
        } else {
            $pageURL .= $SERVER_NAME_server;
        }
        return $pageURL;
    }

    public function getUriPageURLHTTP()
    {
        global $request;
        $pageURL                       = 'http';
        $HTTPS_server                  = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
            $pageURL .= "s";
        }
        return $pageURL;
    }
}
