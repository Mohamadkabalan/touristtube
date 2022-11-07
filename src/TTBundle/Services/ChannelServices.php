<?php

namespace TTBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ChannelServices
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
     * This method gets CmsChannel data of a user limit 1
     * @param userId @return boolean success or fail if user is edited or not
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userDefaultChannelGet($userId)
    {
        if (isset($userId) && !empty($userId)) {
            return $this->em->getRepository('TTBundle:CmsChannel')->userDefaultChannelGet($userId);
        } else {
            return false;
        }
    }

    /*
    * @channelInfoFromURL function return channel Info From URL
    */
    public function channelInfoFromURL( $channel_url, $lang_code='en' )
    {
        return $this->em->getRepository('TTBundle:CmsChannel')->channelInfoFromURL( $channel_url, $lang_code );
    }

    /**
     * Channel Random List.
     *
     * @return array
     */
    public function getChannelRandomList( $limit=null )
    {
        return $this->em->getRepository('TTBundle:CmsChannel')->getChannelRandomList( $limit );
    }

    /**
    * check the channel owner
    * @param integer $id the channel's id
    * @param integer $user_id the user's id
    * @return array | false the cms_channel record or null if not found
    */
    public function checkChannelOwner( $id, $user_id )
    {
        return $this->em->getRepository('TTBundle:CmsChannel')->checkChannelOwner( $id, $user_id );
    }

    /*
     * Adding a new channel detail
     *
     * @param $channel_id
     * @param $detail_text
     * @param $detail_type (CHANNEL_DETAIL_COVER: 1, CHANNEL_DETAIL_PROFILE: 2, CHANNEL_DETAIL_SLOGAN: 3, CHANNEL_DETAIL_INFO: 4)
     *
     * @return channelDetailId
    */
    public function addChannelDetail($channel_id, $detail_text, $detail_type)
    {
        return $this->em->getRepository('TTBundle:CmsChannelDetail')->addChannelDetail($channel_id, $detail_text, $detail_type);
    }

    /**
    * gets channel detail for a given channel id
    * @param integer $channel_id the channel record
    * @return array the channel detail
    */
    public function getChannelDetailInfo( $channel_id, $id )
    {
        return $this->em->getRepository('TTBundle:CmsChannelDetail')->getChannelDetailInfo( $channel_id, $id );
    }

    /**
    * edits a channel
    * @param array $options the new CmsChannel info
    * @return boolean true|false if success or fail
    */
    public function channelEdit( $options )
    {
        return $this->em->getRepository('TTBundle:CmsChannel')->channelEdit( $options );
    }

    /*
    * delete a channel
    * @channelDelete
    */
    public function channelDelete( $id, $channelInfo=null )
    {
        if( !$channelInfo )
        {
            $channelInfo = $this->channelGetInfo( $channel_id );
        }

        $srch_options = array (
            'limit' => null,
            'is_owner' => 1,
            'channel_id' => $id
        );
        $medialist = $this->container->get('PhotosVideosServices')->getAlbumSearch( $srch_options );
        foreach ($medialist as $item) 
        {
            $this->container->get('PhotosVideosServices')->albumDelete( $item['a_id'], null );
        }

        $srch_options = array(
            'limit' => null,
            'is_public' => 2,
            'channel_id' => $id,
            'owner_id' => $channelInfo['c_ownerId']
        );
        $medialist = $this->container->get('PhotosVideosServices')->mediaSearch($srch_options);
        foreach ($medialist as $item)
        {
            $this->container->get('PhotosVideosServices')->photosVideosDelete( $item->getId(), null );
        }

        $CONFIG_SERVER_ROOT = $this->container->getParameter('CONFIG_SERVER_ROOT');
        $channel_path = $CONFIG_SERVER_ROOT.'media/channel/'.$id;

        $this->deleteDirectory($channel_path);

        return $this->em->getRepository('TTBundle:CmsChannel')->channelDelete( $id );
    }

    public function deleteDirectory($dir) {
        @system('rm -rf ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

    /*
    * @channelGetInfo function return channel Info From id
    */
    public function channelGetInfo( $id, $lang_code='en' )
    {
        return $this->em->getRepository('TTBundle:CmsChannel')->channelGetInfo( $id, $lang_code );
    }

    /*
    * @getCityChannelCount function return channel count for a location
    */
    public function getCityChannelCount( $county_code='', $state_code='', $city_id=0 )
    {
        return $this->em->getRepository('TTBundle:CmsChannel')->getCityChannelCount( $county_code, $state_code, $city_id );
    }

    /**
    * Check if the channel URL is unique and provide a new, unique one if the one provided is already taken.
    * @param string $url the provided URL.
    * @return the same channel URL if it's unique, a new one if it is not unique.
    */
    public function channelUrlRename( $url )
    {
        return $this->em->getRepository('TTBundle:CmsChannel')->channelUrlRename( $url );
    }

    /*
    * @getChannelExternalLinks function return channel external links From channel id
    */
    public function getChannelExternalLinks( $in_options )
    {
        return $this->em->getRepository('TTBundle:CmsChannelLinks')->getChannelExternalLinks( $in_options );
    }

    /**
    * edits a channel links
    * @param array $options the new CmsChannelLinks info
    * @return boolean true|false if success or fail
    */
    public function updateChannelExternalLinks( $options )
    {
        return $this->em->getRepository('TTBundle:CmsChannelLinks')->updateChannelExternalLinks( $options );
    }

    /*
     * Adding a new channel links
     *
     * @param $options
     *
     * @return channelLinksId
    */
    public function addChannelExternalLinks( $srch_options )
    {
        return $this->em->getRepository('TTBundle:CmsChannelLinks')->addChannelExternalLinks( $srch_options );
    }

    /*
    * @channelCategoryInfo function return channel category Info related to a selected channel category id
    */
    public function channelCategoryInfo($id, $lang='en')
    {
        return $this->em->getRepository('TTBundle:CmsChannelCategory')->channelCategoryInfo($id, $lang);
    }

    /*
    * @channelCategoryGetID function return channel category ID related to a selected channel category name
    */
    public function channelCategoryGetID($cat_name)
    {
        return $this->em->getRepository('TTBundle:CmsChannelCategory')->channelCategoryGetID($cat_name);
    }

    /*
    * @channelcategoryGetHash function return list of channel categories
    */
    public function channelcategoryGetHash($srch_options = array())
    {        
        $default_opts = array(
            'pagesearch' => false,
            'channelscount' => 0,
            'pagesearchlink' => '',
            'term' => ''
        );
        $options        = array_merge($default_opts, $srch_options);
        $pagesearchlink = $options['pagesearchlink'];
        $pagesearch     = $options['pagesearch'];
        $term           = $options['term'];
        $channelscount  = $options['channelscount'];
        
        $channelCatItems = $this->em->getRepository('TTBundle:CmsChannelCategory')->channelcategoryGetHash($options);
        $category_list = array();
        foreach ($channelCatItems as $item) {
            $items_array['title'] = $this->utils->htmlEntityDecode($item['cca_title']);
            $items_array['id'] = $item['cca_id'];
            if (isset($item['mlcca_title']) && $item['mlcca_title']) {
                $items_array['title'] = $this->utils->htmlEntityDecode($item['mlcca_title']);
            }
            $items_array['titlealt'] = $this->utils->cleanTitleDataAlt($items_array['title']);

            if ($pagesearch && $channelscount > 0) {
                $items_array['link'] = $this->utils->generateLangURL( $options['lang'],'/channels-search-' . $term . '-', 'channels');
                if ($pagesearchlink) {
                    $items_array['link'] .= $pagesearchlink . '_';
                }
                $items_array['link'] .= 'cca_' . $this->utils->cleanTitleData($item['cca_title']);
            } else {
                $items_array['link'] = $this->container->get('TTRouteUtils')->returnChannelsCategoryLink( $options['lang'], $item['cca_title']);
            }
            $category_list[] = $items_array;
        }
        return $category_list;
    }

    /**
    * adds a channel
    * @param string $name the channel's name
    * @param string $url the channel's url
    * @param string $desc the channels description
    * @param string $header the channels description
    * @param string $bg the the background image
    * @param string $default_link a link to the channel website
    * @param string $slogan slogan
    * @param char(2) $country country code
    * @param integer $city webgeocities name
    * @param integer $city_id webgeocities id
    * @param string $street street address
    * @param string $zip_code zipcode
    * @param string $phone phoner number
    * @param string $category pointer to cms_channel_category record
    * @return integer | false the newly inserted cms_channel id or false if not inserted
    */
    public function channelAdd( $user_id, $name='', $url='', $desc='', $header='', $bg='', $default_link='', $slogan='', $country='', $city_id=0, $city='', $street='', $zip_code='', $phone='', $category=0, $email, $fullname, $lang='en' )
    {
        $channel_id = $this->em->getRepository('TTBundle:CmsChannel')->channelAdd( $user_id, $name, $url, $desc, $header, $bg, $default_link, $slogan, $country, $city_id, $city, $street, $zip_code, $phone, $category );

        if( $channel_id )
        {
            $emailVars                            = array();
            $emailVars['fullname']   = $fullname;
            $emailVars['link']   = $this->utils->generateLangURL($lang, 'TT-confirmation/channel/'.md5($channel_id), 'channels' );
            $msg = $this->container->get('templating')->render('emails/channel_email_confirmation.twig', $emailVars);
            $this->container->get('emailServices')->addEmailData($email, $msg, $this->translator->trans('TouristTube Channel Activation'), 'TouristTube.com', 0);
        }

        return $channel_id;
    }

    /*
    * @getDiscoverToChannelList function return discover review pages related to a selected channel id
    */
    public function getDiscoverToChannelList( $channel_id )
    {
        $options = array();
        $options['channel_id'] = $channel_id;
        $options['hotel'] = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $options['landmark'] = $this->container->getParameter('SOCIAL_ENTITY_LANDMARK');
        $options['airport'] = $this->container->getParameter('SOCIAL_ENTITY_AIRPORT');
        return $this->em->getRepository('TTBundle:CmsChannelReviewpage')->getDiscoverToChannelList( $options );
    }

    /*
    * getHotelAdressReview function return discover hotel address
    */
    public function getHotelAdressReview( $srch_options )
    {
        $default_opts = array(
            'lang' => 'en',
            'address' => '',
            'location' => '',
            'phone' => '',
            'city_name' => '',
            'state_name' => '',
            'country_name' => '',
            'country_iso3' => '',
            'country_code' => ''
        );
        $options      = array_merge($default_opts, $srch_options);
        
        $locationTextSeo        = '';
        $locationStructureddata = '';
        $locationText           = $options['address'];
        if ( $options['city_name'] != '' )
        {
            $city_name  = $this->utils->htmlEntityDecode( $options['city_name'] );
            if ($locationText == '') $locationText = $city_name;
            if ($locationStructureddata) $locationStructureddata .= ', ';
            $locationStructureddata .= $city_name;
            $locationTextSeo        = $city_name;
            $locationTextSeo = $this->utils->getMultiByteSubstr( $locationTextSeo, 11, NULL, $options['lang'], false );

            $state_name = '';
            if ( $options['country_code'] == 'US' && $options['state_name']!= '' )
            {
                $state_name = $this->utils->htmlEntityDecode( $options['state_name'] );
                if ($locationText == '') $locationText           .= ', '.$state_name;
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $state_name;
            }

            $country_name  = $this->utils->htmlEntityDecode( $options['country_name'] );
            if ( $country_name !='' )
            {
                if ($locationText == '') $locationText .= ', '.$country_name;
                if ($locationTextSeo) $locationTextSeo .= ' ';
                $locationTextSeo .= $options['country_iso3'];
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $country_name;
            }
        }else {

            if ($locationText == '') $locationText = $options['location'];
            $locationTextSeo = $options['location'];
            $locationTextSeo = $this->utils->getMultiByteSubstr( $locationTextSeo, 14, NULL, $options['lang'], false );

            if ($locationStructureddata) $locationStructureddata .= ', ';
            $locationStructureddata .= $options['location'];
        }

        if ( $options['phone'] !='' )
        {
            if ( $locationText !='' ) $locationText .= '<br/>';
            $locationText .= $options['phone'];
        }

        if ( $options['address'] !='' )
        {
            $locationStructureddata = $options['address'];
        }
        
        return [$locationText, $locationTextSeo, $locationStructureddata, $options['phone']];
    }

    /*
    * getPoiAdressReview function return discover POI address
    */
    public function getPoiAdressReview( $srch_options )
    {
        $default_opts = array(
            'lang' => 'en',
            'address' => '',
            'phone' => '',
            'city_name' => '',
            'state_name' => '',
            'country_name' => '',
            'country_iso3' => '',
            'country_code' => ''
        );
        $options      = array_merge($default_opts, $srch_options);
        
        $locationText           = '';
        $locationTextSeo        = '';
        $locationStructureddata = '';
        $cityTextSeo            = '';

        if ( $options['city_name'] != '' )
        {
            $city_name  = $this->utils->htmlEntityDecode(  $options['city_name']  );
            if ($locationText) $locationText    .= '<br/>';
            $locationText    .= $city_name;
            $locationTextSeo .= $city_name;
            $locationTextSeo = $this->utils->getMultiByteSubstr( $locationTextSeo, 11, NULL, $options['lang'], false );
            $locationStructureddata .= $city_name;
            $cityTextSeo            .= $city_name;

            $state_name  = '';
            if ( $options['state_name']!= '' )
            {
                $state_name = $this->utils->htmlEntityDecode( $options['state_name'] );
                if ($city_name == '') $locationText           .= '<br/>';
                $locationText           .= ', '.$state_name;
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $state_name;
            }

            $country_name  = $this->utils->htmlEntityDecode( $options['country_name'] );
            if ( $country_name !='' )
            {
                if ($city_name == '' && $state_name == '') $locationText           .= '<br/>';
                $locationText           .= ', '.$country_name;
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $country_name;
                $locationTextSeo        .= ' '.$options['country_iso3'];
            }

        } else {
            $locationText = $options['address'];
        }

        if ($locationText == '')
        {
            $locationText = $options['address'];
        } else if ( $options['address'] !='' )
        {
            $locationText .= '<br>'.$options['address'];
        }

        if ( $options['phone'] !='' )
        {
            if ($locationText) $locationText .= '<br/>';
            $locationText .= $options['phone'];
        }

        if ($options['address'])
        {
            $locationStructureddata = $options['address'];
        }
        
        return [$locationText, $locationTextSeo, $cityTextSeo];
    }

    /*
    * getAirportAdressReview function return discover airport address
    */
    public function getAirportAdressReview( $srch_options )
    {
        $default_opts = array(
            'lang' => 'en',
            'address' => '',
            'phone' => '',
            'city' => '',
            'airport_code' => '',
            'city_name' => '',
            'state_name' => '',
            'country_name' => '',
            'country_iso3' => '',
            'country_code' => ''
        );
        $options      = array_merge($default_opts, $srch_options);
        
        $locationText           = '';
        $locationTextSeo        = '';
        $locationStructureddata = '';

        if ( $options['city_name'] != '')
        {
            $city_name  = $this->utils->htmlEntityDecode( $options['city_name'] );
            if ($locationText) $locationText           .= '<br/>';
            $locationText           .= $city_name;
            if ($locationStructureddata) $locationStructureddata .= ', ';
            $locationStructureddata .= $city_name;
            $locationTextSeo        .= $city_name;
            $locationTextSeo = $this->utils->getMultiByteSubstr( $locationTextSeo, 11, NULL, $options['lang'], false );
            
            $state_name  = '';
            if ( $options['state_name']!= '' )
            {
                $state_name = $this->utils->htmlEntityDecode( $options['state_name'] );
                if ($city_name == '') $locationText           .= '<br/>';
                $locationText           .= ', '.$state_name;
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $state_name;
            }

            $country_name  = $this->utils->htmlEntityDecode( $options['country_name'] );
            if ( $country_name !='' )
            {
                if ($city_name == '' && $state_name == '') $locationText           .= '<br/>';
                $locationText           .= ', '.$country_name;
                $locationTextSeo        .= ' '.$options['country_iso3'];
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $country_name;
            }
        }

        if ( $locationText == '' )
        {
            $locationStructureddata = $locationTextSeo = $locationText = $options['city'];
            $locationTextSeo = $this->utils->getMultiByteSubstr( $locationTextSeo, 11, NULL, $options['lang'], false );
        }

        if ( $options['phone'] !='' )
        {
            if ($locationText) $locationText .= '<br/>';
            $locationText .= $options['phone'];
        }

        return [$locationText, $locationTextSeo];
    }

    public function getChannelsListDiscoverData( $srch_options )
    {
        $default_opts = array
        (
            'limit' => 4, 
            'page' => 0,
            'from' => 0,
            'city_id' => 0,
            'country' => '',
            'state_name' => '',
            'img_width' => 284,
            'img_height' => 91,
            'lang' => 'en',
            'url_source' => '',
            'channel_name' => '',
            'owner_id' => 0,
            'cityName' => '',
            'category' => '',
            'orderby' => 'id',
            'order' => 'a'
        );
        $options = array_merge($default_opts, $srch_options);
        
        $channels_array        = array();        
        $lang       = $options['lang'];
        $img_width  = $options['img_width'];
        $img_height = $options['img_height'];
        $url_source  = $options['url_source'];
        
        $channelss             = $this->container->get('ElasticServices')->getCityChannelListElastic($options,$url_source);
        $channelssarr          = $channelss[0];
        $channelsscount        = $channelss[1];
        foreach ($channelssarr as $item) {
            $item_array            = array();
            $item_array['id']      = intval($item['_source']['id']);
            $item_array['type']    = $this->container->getParameter('SOCIAL_ENTITY_CHANNEL');
            $item_array['name']    = $this->utils->htmlEntityDecode($item['_source']['name']);
            $item_array['namealt'] = $this->utils->cleanTitleDataAlt($item['_source']['name']);
            $item_array['link']    = $this->utils->generateLangURL( $lang, '/channel/'.$item['_source']['url'], 'channels' );
            
            $item_array['city']    = '';
            if (isset($item['_source']['location']['city']['name']) && $item['_source']['location']['city']['name']) 
            {
                $item_array['city'] = $item['_source']['location']['city']['name'];
            } 
            else if (isset($item['_source']['location']['country']['name']) && $item['_source']['location']['country']['name']) 
            {
                $item_array['city'] = $item['_source']['location']['country']['name'];
            }
            
            $dimage = 'coverphoto.jpg';
            $dimagepath = 'media/images/channel/';
            if ($item['_source']['media']['images']['header']) 
            {
                $dimage = $item['_source']['media']['images']['header'];
                $dimagepath = 'media/channel/' . $item_array['id'] . '/';
            }
            $item_array['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, $img_width, $img_height, 'thumb'.$img_width.$img_height);
            
            $item_array['description'] = $this->utils->htmlEntityDecode( $item['_source']['summary'] );
            $item_array['desc'] = $this->utils->getMultiByteSubstr( $item_array['description'], 185, NULL, $lang );
            
            $channels_array[] = $item_array;
        }
        
        return [$channels_array, $channelsscount];
    }
}
