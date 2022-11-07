<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use TTBundle\Entity\DiscoverHotels;
use TTBundle\Entity\DiscoverHotelsImages;
use TTBundle\Entity\MlAllcategories;
use TTBundle\Entity\CmsChannel;
use TTBundle\Entity\CmsChannelPrivacy;
use TTBundle\Entity\CmsChannelPrivacyExtand;
use TTBundle\Entity\CmsVideos;
use TTBundle\Entity\Airport;
use TTBundle\Entity\AirportReviews;
use TTBundle\Entity\DiscoverPoi;
use TTBundle\Entity\DiscoverHotelsReviews;
use TTBundle\Entity\DiscoverPoiReviews;
use TTBundle\Entity\Webgeocities;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DiscoverController extends DefaultController
{

    public function __construct()
    {
        define('SOCIAL_ENTITY_HOTEL', 28);
        define('SOCIAL_ENTITY_LANDMARK', 30);
        define('SOCIAL_ENTITY_USER', 2);
        define('SOCIAL_ENTITY_RESTAURANT', 29);
        define('SOCIAL_ENTITY_MEDIA', 1);
        $_global_channel      = null;
        $_tt_global_variables = array();
        /**
         * the media is ready to be viewed
         */
        define('MEDIA_READY', 1);
        /**
         * the object can be shared with the public
         */
        define('USER_PRIVACY_PUBLIC', 2);
        /**
         * the object is private
         */
        define('USER_PRIVACY_PRIVATE', 0);
        /**
         * the object will be shared with followers
         */
        define('USER_PRIVACY_FOLLOWERS', 5);
        /**
         * the object can be shaed with the friends of friends
         */
        define('USER_PRIVACY_COMMUNITY_EXTENDED', 3);
        /**
         * the object will be shared with custom
         */
        define('USER_PRIVACY_SELECTED', 4);
        /**
         * the object can be shared with friends
         */
        define('USER_PRIVACY_COMMUNITY', 1);
    }

    public function getAppUrl()
    {
        $request  = Request::createFromGlobals();
        $baseUrl  = $this->getRequest()->getSchemeAndHttpHost();
        $baseurl2 = $request->getBaseUrl();
        $env      = $this->get('kernel')->getEnvironment();
        if ($env == 'dev') {
            return $baseUrl.$baseurl2."/";
        } else {
            return $baseUrl.'/';
        }
    }

    /**
     * Method that will call on click of discover home page listing content
     */
    public function mapAction($str = "", $ccode = "", $scode = "", $seotitle,
                              $seodescription, $seokeywords)
    {
        return $this->discoverAction($seotitle, $seodescription, $seokeywords);
        //return $this->redirectToLangRoute('_discover',array(),301);
    }

    /**
     * returns the global channed_id
     * @return array | false the global channed record or false if none
     */
    function channelGlobalGet()
    {
        global $_global_channel;
        return is_null($_global_channel) ? false : $_global_channel;
    }

    /**
     * checks if 2 users are freinds
     * @param integer $user_id the user rejecting a friend request
     * @param integer $friend_id the second user
     * @return boolean true|false if friends or not
     */
    function userIsFriend($user_id, $friend_id)
    {
        $frnd_accept = FRND_STAT_ACPT;
        $em          = $this->getDoctrine()->getManager();
        $qb          = $em->createQueryBuilder('RE')
            ->select('V')
            ->from('TTBundle:CmsFriends', 'V')
            ->where("V.published=1 AND V.status=:Frnd_accept AND V.requesterId=:User_id AND V.receipientId=:Friend_id")
            ->setParameter(':Frnd_accept', $frnd_accept)
            ->setParameter(':User_id', $user_id)
            ->setParameter(':Friend_id', $friend_id);
        $query       = $qb->getQuery();
        $ret         = count($query->getArrayResult());
        if ($res && ( $ret != 0 )) {
            return true;
        } else {
            return false;
        }
    }

    function _searchStringDontSearch($string)
    {
        //TODO: get all prepositions conjuctions
        $dont_search = array('and', 'or', 'the');
        return in_array($string, $dont_search);
    }

    /**
     * gets a list of a users followings
     * @param integer the user who we want to get his followings
     * @return array the list of users followings (could be empty list)
     */
    function userSubscribedList($user_id)
    {
        $userSubscribedList = $this->tt_global_get('userSubscribedList');
        if (isset($userSubscribedList[$user_id]) && $userSubscribedList[$user_id]
            != '') {
            return $userSubscribedList[$user_id];
        }
        $params   = array();
        $cs_query = "SELECT
					U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic
				FROM
					TTBundle:CmsSubscriptions F
					INNER JOIN TTBundle:CmsUsers U ON U.id=F.user_id
				WHERE
					 F.published = 1 AND F.subscriber_id=:User_id
				ORDER BY YourUserName ASC";
        $query    = $em->createQuery($cs_query);
        $query->setParameter(':User_id', $user_id);
        $ret_arr  = array();
        $ret      = count($query->getArrayResult());
        $row      = $query->getArrayResult();

        if (!$res2 || $ret == 0) {
            $userSubscribedList[$user_id] = array();
            return array();
        } else {
            $userSubscribedList[$user_id] = $row;
            return $row;
        }
    }

    /**
     * gets a list of a users freinds
     * @param integer the user who we want to get his friends
     * @return array the list of users friends (could be empty list)
     */
    function userGetFreindList($user_id)
    {
        $userGetFreindList = $this->tt_global_get('userGetFreindList');
        $params            = array();
        if (isset($userGetFreindList[$user_id]) && $userGetFreindList[$user_id] != '') {
            return $userGetFreindList[$user_id];
        }
        $cfquery = "SELECT
					U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic, U.gender
				FROM
					TTBundle:CmsFriends F
					INNER JOIN TTBundle:CmsUsers U ON U.id=F.receipient_id
				WHERE
					F.published=1 AND F.requester_id=:User_id AND F.status=".FRND_STAT_ACPT."
				ORDER BY U.YourUserName ASC
				";
        $query   = $em->createQuery($cfquery);
        $query->setParameter(':User_id', $user_id);
        $ret     = count($query->getArrayResult());

        $ret_arr = array();
        if (!$res || $ret == 0) {
            $userGetFreindList[$user_id] = array();
            return array();
        } else {
            $ret_arr                     = $query->getArrayResult();
            $userGetFreindList[$user_id] = $ret_arr;
            return $ret_arr;
        }
    }

    /**
     * gets a list of a users freinds
     * @param integer the user who we want to get his friends
     * @return array the list of users friends (could be empty list)
     */
    function userGetExtendedFriendList($users)
    {
        $in_users = implode(',', $users);
        global $dbConn;
        $em       = $this->getDoctrine()->getManager();
        $querycf  = "SELECT
					U.id, U.FullName, U.YourUserName, U.displayFullname, U.profilepic, F
				FROM
					TTBundle:CmsFriends F
					INNER JOIN TTBundle:CmsUsers AS U ON U.id=F.receipientId
				WHERE
					F.published=1 AND find_in_set(cast(F.requesterId as char), :In_users) AND F.status=".FRND_STAT_ACPT."
				ORDER BY YourUserName ASC";
        $query    = $em->createQuery($querycf);
        $query->setParameter(':In_users', $in_users);
        $ret_arr  = array();
        $ret      = count($query->getArrayResult());
        if (!$res || $ret == 0) return array();

        $ids = array();
        $row = $query->getArrayResult();
        foreach ($row as $row_item) {
            if (!in_array($row_item['id'], $ids)) {
                $ret_arr[] = $row_item;
                $ids[]     = $row_item['id'];
            }
        }
        return $ret_arr;
    }

    /**
     * Calculates the difference between 2 locations in meters
     * @param double $lat1 first location latitude
     * @param double $lon1 first location longitude
     * @param double $lat2 second location latitude
     * @param double $lon2 second location longitude
     * @return integer the distance in meters
     */
    function LocationDiff($lat1, $lon1, $lat2, $lon2)
    {
        $R        = 6371; // km
        $dLat     = deg2rad($lat2 - $lat1);
        $dLon     = deg2rad($lon2 - $lon1);
        $lat1_rad = deg2rad($lat1);
        $lat2_rad = deg2rad($lat2);

        $a  = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($lat1)
            * cos($lat2);
        $c  = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d  = $R * $c;
        $dm = intval($d * 1000);
        return $dm;
    }

    /**
     * return the cache name of records similar to a video given by id
     * @param integer $id the cms_videos id
     */
    function videoSimCacheName($id)
    {
        return 'media_sim_'.$id;
    }

    /**
     * converts a fullname to a username
     * @param array $userInfo the cms_uesrs record
     * @param array $display_options options include:<br/>
     * <b>max_length</b> the maximum length of the resulting string. default 15 .
     * return string
     */
    function returnUserName($userInfo, $display_options = null)
    {
        $default_options = array(
            'max_length' => null
        );
        if (!is_null($display_options))
                $options         = array_merge($default_options,
                $display_options);
        else $options         = $default_options;

        if ($userInfo['displayFullname'] == 1) {
            $fullname = $this->get('app.utils')->htmlEntityDecode($userInfo['fullname']);
            $fullname = htmlspecialchars($fullname);
            if (strlen($fullname) <= 1) {
                $fullname = $userInfo['yourusername'];
            }
        } else {
            $fullname = $userInfo['yourusername'];
        }
        if ((!is_null($options['max_length'])) && (strlen($fullname) > $options['max_length'])) {
            $fullname = substr($fullname, 0, $options['max_length']).'...';
        }
        return $fullname;
    }

    /**
     * get the city name
     * @param int $cityId the city id
     * @return string the city name
     */
    function getCityName($cityId)
    {
        $em    = $this->getDoctrine()->getManager();
        $qb    = $em->createQueryBuilder('CO')
            ->select('c.name')
            ->from('TTBundle:Webgeocities', 'c')
            ->where("c.id =:CityId")
            ->setParameter(':CityId', $cityId);
        $query = $qb->getQuery();
        if (count($query->getResult()) == 0) {
            return false;
        } else {
            $city_name = $query->getArrayResult();
            //print_r($city_name);exit;
            return $city_name[0]['name'];
        }
    }

    /**
     * get the a user display location
     * @param array $userInfo the cms_users record of the user
     * @return string the location
     */
    function userGetLocation($userInfo)
    {
        //print_r($userInfo);exit;
        $city_id  = $userInfo['cityId'];
        $city     = $this->getCityName($city_id);
        $location = $city;
        if (($location != '') && ($userInfo['yourcountry'] != 'ZZ'))
                $location .= ', '.$userInfo['yourcountry'];
        return $location;
    }

    /**
     * search users through position
     * @param float $longitude0 and $longitude1 the longitude between these two value
     * @param float $latitude0 and $latitude1 the latitude between these two value
     * @return boolean array of users | false if success|fail
     */
    function userSearchPosition($longitude0, $longitude1, $latitude0,
                                $latitude1, $limit = '')
    {
        $em    = $this->getDoctrine()->getManager();
        $qb    = $em->createQueryBuilder('UE')
            ->select('U')
            ->from('TTBundle:CmsUsers', 'U')
            ->where("U.longitude BETWEEN :Longitude_search0 AND :Longitude_search1 AND U.latitude BETWEEN :Latitude_search0 AND :Latitude_search1 AND U.published=1 AND U.ischannel=0")
            ->setParameter(':Longitude_search0', $longitude0)
            ->setParameter(':Longitude_search1', $longitude1)
            ->setParameter(':Latitude_search0', $latitude0)
            ->setParameter(':Latitude_search1', $latitude1)
            ->setMaxResults($limit);
        $query = $qb->getQuery();
        $ret   = count($query->getArrayResult());
        if ($ret != 0) {
            $media = array();
            $row   = $query->getArrayResult();
            $i     = 0;
            foreach ($row as $row_item) {
                if ($row_item['profilePic'] == '') {
                    $row_item['profilePic'] = 'he.jpg';
                    if ($row_item['gender'] == 'F') {
                        $row_item['profilePic'] = 'she.jpg';
                    }
                }
                $row_item['level']       = 1;
                $row_item['show_on_map'] = 1;
                $row_item['categ']       = SOCIAL_ENTITY_USER;
                $media[]                 = $row_item;
                $i++;
            }
            return $media;
        } else {
            return false;
        }
    }

    /**
     * gets a category name given the id
     * @param integer $cat_id
     * @return string | false category name if found or false if nothing found
     */
    function categoryGetName($cat_id)
    {
        $em           = $this->getDoctrine()->getManager();
        $lang_code    = $this->LanguageGet();
        $params       = array();
        $languageSel  = '';
        $languageJoin = '';
        $languageAnd  = '';

        if ($lang_code != 'en') {
            $languageSel  = ',ml.title as mtitle';
            $languageJoin = ' INNER JOIN TTBundle:MlAllcategories ml on c.id = ml.entityId ';
            $languageAnd  = " and ml.langCode=:Lang_code";
            $params[]     = array("key" => ":Lang_code",
                "value" => $lang_code);
        }
        $queryCh = "SELECT distinct c.title as title $languageSel FROM TTBundle:CmsAllcategories c INNER JOIN TTBundle:MlAllcategories ml with c.id = ml.entityId  AND c.published='1' and c.id=:Cat_id $languageAnd";
        $query   = $em->createQuery($queryCh);
        $query->setParameter(':Cat_id', $cat_id);
        $ret     = count($query->getArrayResult());
        if ($ret != 0) {
            $row = $query->getArrayResult();
            if ($lang_code == 'en') {
                return $row[0]['title'];
            } else {
                return $row[0]['mtitle'];
            }
        } else {
            return false;
        }
    }

    /**
     * gets the channel thumb for channles page/search given the channel info
     * @param array $channelInfo
     * @param start x position $coord_x
     * @param start y position $coord_y
     * @param image width $coord_w
     * @param image height $coord_h
     * @return initeger the image link
     */
    function createchannelThumb($channelInfo, $coord_x, $coord_y, $coord_w,
                                $coord_h, $path = '')
    {
        $filename      = $channelInfo->getHeader();
        $savedfilename = "preview_".$filename;

        if (!file_exists("/media/channel/".$channelInfo->getId()."/thumb/".$filename)) {
            return false;
        } else {
            $thumbpath = "/media/channel/".$channelInfo->getId()."/thumb/";
            if (!file_exists("/media/channel/".$channelInfo->getId())) {
                mkdir('/media/discover/'.$channelInfo->getId(), 0777, true);
            }
            if (!file_exists("/media/channel/".$channelInfo->getId()."/thumb/")) {
                mkdir("/media/discover/".$channelInfo->getId()."/thumb/", 0777,
                    true);
            }
            $filePath       = $path.''.$thumbpath.$filename;
            $savedThumbPath = $path.''.$thumbpath.$savedfilename;
            if (!file_exists($savedThumbPath)) {
                // read image
                $ext = strtolower(substr(strrchr($filePath, '.'), 1)); // get the file extension
                switch ($ext) {
                    case 'jpg':     // jpg
                        $image_rs = imagecreatefromjpeg($filePath) or notfound();
                        break;
                    case 'png':     // png
                        $image_rs = imagecreatefrompng($filePath) or notfound();
                        break;
                    case 'gif':     // gif
                        $image_rs = imagecreatefromgif($filePath) or notfound();
                        break;
                    case 'JPG':     // JPG
                        $image_rs = imagecreatefromjpeg($filePath) or notfound();
                        break;
                    case 'jpeg':     // jpeg
                        $image_rs = imagecreatefromjpeg($filePath) or notfound();
                        break;
                    default:     // jpeg
                        $image_rs = imagecreatefromjpeg($filePath) or notfound();
                }
                $new_rs = @imagecreatetruecolor($coord_w, $coord_h);
                // copy resized image to new canvas
                imagecopyresampled($new_rs, $image_rs, 0, 0, 160, 0, 238, 76,
                    800, 256);

                imagejpeg($new_rs, $savedThumbPath);
            }
            return $savedThumbPath;
        }
    }

    function notfound()
    {
        return false;
    }

    function userAllBagItemsCount($user_id)
    {
        $em    = $this->getDoctrine()->getManager();
        $qb    = $em->createQueryBuilder()
            ->select('count(cmsbagitem.id)')
            ->from('TTBundle:CmsBagitem', 'cmsbagitem')
            ->where('user_id=:User_id')
            ->setParameter('User_id', $user_id);
        $count = $qb->getQuery()->getSingleScalarResult();
        return $count;
    }

    function returnHotelReviewLink($id, $title)
    {
        $titled = $this->cleanTitle($title);
        return $titled.'-review-H'.$id;
    }

    function displayReviewsCount($num, $add_num = 1)
    {
        if (intval($num) > 1 || intval($num) == 0) {
            $data_val = _('reviews');
        } else {
            $data_val = _('review');
        }
        if ($add_num) {
            return $this->displayValueNum($num).' '.$data_val;
        } else {
            return $data_val;
        }
    }

    function displayValueNum($num)
    {
        if (intval($num) < 0) {
            $num = 0;
        }
        return $this->tt_number_format($num);
    }

    /**
     * for mat a number so that the maximum
     * @param integer $in
     * @return string the formatted output
     */
    function tt_number_format($in)
    {
        if ($in == '') return 0;
        if ($in == 0) return 0;
        $out = intval($in);
        if ($out >= 1000) {
            $out = intval($out / 100);
            $out = $out / 10;
            $out .= 'k';
        }
        return $out;
    }

    /**
     * Converts all accent characters to ASCII characters.
     *
     * If there are no accent characters, then the string given is just returned.
     *
     * @param string $string Text that might have accent characters
     * @return string Filtered string with replaced "nice" characters.
     */
    function remove_accents($string)
    {
        if (!preg_match('/[\x80-\xff]/', $string)) return $string;
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        return $string;
    }

    function cleanTitle($title)
    {
        $ret = html_entity_decode($title);
        $ret = $this->remove_accents($ret);
        $ret = str_replace(' ', '-', $ret);
        $ret = str_replace(' ', '-', $ret);
        $ret = str_replace('.', '', $ret);
        $ret = preg_replace('/[^a-z0-9A-Z\-]/', '', $ret);
        $ret = str_replace('--', '-', $ret);

        return $ret;
    }

    function getReviewsImage($entity_type, $entity_id)
    {
        $params       = array();
        $table        = "";
        $entity_value = "";
        if ($entity_type == SOCIAL_ENTITY_HOTEL) {
            $table        = "DiscoverHotelsImages";
            $entity_value = "hotelId";
        } else if ($entity_type == SOCIAL_ENTITY_LANDMARK) {
            $table        = "DiscoverPoiImages";
            $entity_value = "poi";
        } else if ($entity_type == SOCIAL_ENTITY_AIRPORT) {
            $table        = "AirportImages";
            $entity_value = "airportId";
        }

        if ($table == "") {
            return 0;
        } else {
            $query2 = "SELECT COALESCE(s.filename, '') FROM  TTBundle:$table s WHERE s.$entity_value = :Entity_id";
            $em     = $this->getDoctrine()->getManager();
            $query  = $em->createQuery($query2);
            $query->setParameter(':Entity_id', $entity_id);
            $row    = $query->getArrayResult();

            if ($row) {
                return $row[0];
            } else {
                return 0;
            }
        }
    }

    /**
     * gets the passed uri argument
     * @param integer|string $which the argument to get
     * return null|string the arguments value or null if not found
     */
    function UriGetArg($which)
    {
        global $_tt_global_args;
        if (is_null($which)) {
            return implode('/', $_tt_global_args);
        } else if (is_int($which)) {
            if (count($_tt_global_args) == 0) return null;
            if ($which >= count($_tt_global_args)) return null;
            else return $_tt_global_args[$which];
        }else {
            $i = 0;
            while ($i < count($_tt_global_args) - 1) {
                if ($_tt_global_args[$i] == $which) {
                    return $_tt_global_args[$i + 1];
                }
                $i++;
            }
            return null;
        }
    }

    /**
     * gets the cms_countries record given the name
     * @param string $name the name of the country
     * @return array|false the cms_countries record or false if none found
     */
    function countryNameInfo($name)
    {
        $lower_name = strtolower($name);
        $_name      = ucfirst($lower_name);
        $em         = $this->getDoctrine()->getManager();
        $qb         = $em->createQueryBuilder('CO')
            ->select('s')
            ->from('TTBundle:CmsCountries', 's')
            ->where('s.name=:Name')
            ->setParameter(':Name', $_name);
        $query      = $qb->getQuery();
        $ret        = count($query->getResult());
        if ($ret == 1) {
            $row = $query->getResult();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * gets the city record (webgeocities not webgeocities) for a city_name
     * @param string $city_name
     * @return false|array false if no city or the webgeocities record
     */
    function cityFind($city_name, $strict = false)
    {
        $l_city_name = strtolower($city_name);
        if ($strict) {
            $query1   = "SELECT w FROM TTBundle:Webgeocities w WHERE w.name=:L_city_name ORDER BY w.orderDisplay DESC";
            $params[] = array("key" => ":L_city_name",
                "value" => $l_city_name);
        } else {
            $query1   = "SELECT w FROM TTBundle:Webgeocities w WHERE w.name LIKE :L_city_name ORDER BY w.orderDisplay DESC";
            $params[] = array("key" => ":L_city_name",
                "value" => '%'.$l_city_name.'%');
        }
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery($query1);
        if (count($params) > 0) {
            foreach ($params as $value) {
                $query->setParameter($value['key'], $value['value']);
            }
        }
        $query->setMaxResults(1);
        $media = $query->getArrayResult();
        $ret   = count($query->getResult());
        if ($ret == 0) {
            return false;
        } else {
            $row = $query->getResult();
            return $row;
        }
    }

    function getCityInfoRow($cityName, $countryCode, $state_code = '')
    {
        $params          = array();
        $cityName        = trim($cityName);
        $countryCode     = trim($countryCode);
        $lower_cc        = strtolower($countryCode);
        $upper_cc        = strtoupper($countryCode);
        $lower_city_name = strtolower($cityName);
        $em              = $this->getDoctrine()->getManager();
        $qb              = $em->createQueryBuilder();
        if ($state_code == '') {
            $qb->select('w')
                ->from('TTBundle:Webgeocities', 'w')
                ->where($qb->expr()->andX(
                        $qb->expr()->like('LOWER(w.name)', ':Lower_city_name'),
                        $qb->expr()->like('UPPER(w.countryCode)', ':Upper_cc')
                ))
                ->setParameter(':Lower_city_name', $lower_city_name)
                ->setParameter(':Upper_cc', $upper_cc)
                ->orderBy('w.countryCode', 'ASC');
        } else {
            $qb->select('w')
                ->from('TTBundle:Webgeocities', 'w')
                ->where($qb->expr()->andX(
                        $qb->expr()->like('LOWER(w.name)', ':Lower_city_name'),
                        $qb->expr()->like('LOWER(w.stateCode)',
                            'LOWER(:State_code)'),
                        $qb->expr()->like('UPPER(w.countryCode)', ':Upper_cc')
                ))
                ->setParameter(':Lower_city_name', $lower_city_name)
                ->setParameter(':State_code', $state_code)
                ->setParameter(':Upper_cc', $upper_cc)
                ->orderBy('w.countryCode', 'ASC');
        }
        $query = $qb->getQuery();
        $ret   = count($query->getResult());
        if ($ret == 0) {
            return false;
        } else {
            $cityidres = $query->getResult();
            return $cityidres;
        }
    }

    public function photoReturnSrcLink($photoInfo, $size = '', $is_media = 1)
    {
        if ($photoInfo['imageVideo'] == 'v') {
            $mediaPath = $this->container->getParameter('CONFIG_SERVER_ROOT').$photoInfo['fullpath'].'';
            $videoCode = $photoInfo['code'];
            $thumbs    = glob($mediaPath."_*_".$videoCode."_*.jpg");
            if ($thumbs && count($thumbs) > 0) {
                $path_parts   = pathinfo($thumbs[0]);
                $filename     = $path_parts['filename'];
                $relativepath = $photoInfo['relativepath'];
                $relativepath = str_replace('/', '', $relativepath);
                $fullPath     = 'u'.$relativepath.'/'.(!empty($size) ? $size.'_'
                        : '').$filename.'.jpg';
            } else {
                $fullPath = 'media/images/unavailable-preview.gif';
                $is_media = 1;
            }
        } else {
            $relativepath   = $photoInfo['relativepath'];
            $relativepath   = str_replace('/', '', $relativepath);
            $fullPath       = 'u'.$relativepath.'/'.(!empty($size) ? $size.'_' : '').$photoInfo['name'];
            $fullPath_exist = $this->container->getParameter('CONFIG_SERVER_ROOT').$photoInfo['fullpath'].''.(!empty($size)
                    ? $size.'_' : '').$photoInfo['name'];

            try {
                if (!file_exists($fullPath_exist)) {
                    $fullPath = 'media/images/unavailable-preview.gif';
                    $is_media = 1;
                }
            } catch (Exception $e) {

            }
        }
        //return ReturnLink($fullPath, null, $is_media);
        return '/'.$fullPath;
    }
}