<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class ApiDiscoverServices extends Controller{
    
    protected $em;
    protected $container;
    protected $utils;
    
    
    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
    }

    public function thingsTodoRegionQuery($options)
    {
        $from_mobile = ( isset($options['from_mobile']) )?$options['from_mobile']:1;
        $lang = ( isset($options['lang']) )?$options['lang']:'en';

        $resRegion      = $this->container->get('ThingsToDoServices')->getThingstodoRegionList($options);

        $thingstodoRegion_All = array();
        foreach ($resRegion as $itemData)
        {
            $id          = $itemData['t_id'];
            $title_init       = $itemData['t_title'];
            if ($itemData['ml_title'] != '') $title_init = $itemData['ml_title'];
            $title       = $this->utils->htmlEntityDecode($title_init);

            $description = $itemData['t_description'];
            if ($itemData['ml_description'] != '') $description = $itemData['ml_description'];
            $description = $this->utils->htmlEntityDecode($description);

            if( $itemData['t_image'] && $itemData['t_mobileImage'])
            {
                $image = 'media/thingstodo/'.$itemData['t_mobileImage'];
            }
            else
            {
                $image = 'media/thingstodo/'.$itemData['t_image'];
            }

            $ttd_item = array(
                'id'=>$id,
                'title' => $title,
                'image' => $image,
                'description' => $description
            );
            
            if( $from_mobile == 0 )
            {
                $titlealt    = $this->utils->cleanTitleDataAlt($title_init);
                $description = $this->utils->htmlEntityDecode($description);
                $description = $this->utils->getMultiByteSubstr( $description, 310, NULL, $lang );

                $sourcepath = 'media/thingstodo/';
                $sourcename = $itemData['t_image'];
                $img = '';
                if( $sourcename !='' )
                {
                    $img = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 360, 360, 'thumb360360', $sourcepath, $sourcepath, 80);
                }
            
                $page_link = $this->utils->generateLangURL( $lang,'/'.$itemData['a_alias']);
                
                $ttd_item['description'] = $description;
                $ttd_item['titlealt'] = $titlealt;
                $ttd_item['img'] = $img;
                $ttd_item['link'] = $page_link;
            }
            
            $thingstodoRegion_All[] = $ttd_item;
        }
        return $thingstodoRegion_All;
    }
    
    public function thingsTodoCountryQuery( $options )
    {
        $from_mobile = ( isset($options['from_mobile']) )?$options['from_mobile']:1;
        $lang = ( isset($options['lang']) )?$options['lang']:'en';
        $media_bucket_url = $this->container->get("TTRouteUtils")->getMediaBucketURL();
        
        $thingstodo_All = array();
        $thingstodoCountryList = $this->container->get('ThingsToDoServices')->getThingstodoCountryList( $options );

        foreach ($thingstodoCountryList as $itemData)
        {
            $id = $itemData['t_id'];
            $title_init       = $itemData['t_title'];
            if ($itemData['ml_title'] != '') $title_init       = $itemData['ml_title'];
            $title       = $this->utils->htmlEntityDecode($title_init);

            $description = $itemData['t_description'];
            if ($itemData['ml_description'] != '') $description = $itemData['ml_description'];
            $description = $this->utils->htmlEntityDecode($description);
            
            $sourcepath = 'media/thingstodo/';
            $sourcename = $itemData['t_image'];            
            $image = '';
            $image_init = '';
            if( $sourcename !='' )
            {
                $image_init = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 360, 360, 'thumb360360', $sourcepath, $sourcepath, 80);
            }
            $image = $image_init;
            if( $media_bucket_url != '' && $image != '' )
            {
                $explode_array_media = explode( $media_bucket_url, $image );
                $image = $explode_array_media[1];
                $explode_array_size = explode( '?', $image );
                $image = $explode_array_size[0];
            }
            if (substr($image, 0, 1) == '/') $image = substr($image, 1, strlen($image));
            
            $ttd_item = array(
                'id'=>$id,
                'title' => $title,
                'image' => $image,
                'description' => $description
            );
            
            if( $from_mobile == 0 )
            {
                $titlealt    = $this->utils->cleanTitleDataAlt($title_init);
                $description = $this->utils->htmlEntityDecode($description);
                $description = $this->utils->getMultiByteSubstr( $description, 310, NULL, $lang );

                $page_link = $this->utils->generateLangURL( $lang,'/'.$itemData['a_alias']);
                
                $ttd_item['description'] = $description;
                $ttd_item['titlealt'] = $titlealt;
                $ttd_item['img'] = $image_init;
                $ttd_item['link'] = $page_link;
            }
            
            $thingstodo_All[] = $ttd_item;
        }
        return $thingstodo_All;
    }
    public function thingsTodoSearchQuery( $options )
    {
        $from_mobile = ( isset($options['from_mobile']) )?$options['from_mobile']:1;
        $lang = ( isset($options['lang']) )?$options['lang']:'en';
        $img_width = ( isset($options['img_width']) )?$options['img_width']:360;
        $img_height = ( isset($options['img_height']) )?$options['img_height']:360;
        $desc_length = ( isset($options['desc_length']) )?$options['desc_length']:310;
        $media_bucket_url = $this->container->get("TTRouteUtils")->getMediaBucketURL();
        
        $thingstodoCountryList = $this->container->get('ThingsToDoServices')->getThingstodoList($options);
        $thingstodo_All = array();

        foreach ($thingstodoCountryList as $itemData)
        {
            $id = $itemData['t_id'];
            $title_init       = $itemData['t_title'];
            if ($itemData['ml_title'] != '') $title_init = $itemData['ml_title'];
            $title       = $this->utils->htmlEntityDecode($title_init);

            $description = $itemData['t_description'];
            if ($itemData['ml_description'] != '') $description = $itemData['ml_description'];
            $description = $this->utils->htmlEntityDecode($description);

            $sourcepath = 'media/thingstodo/';
            $sourcename = $itemData['t_image'];
            $image = '';
            $image_init = '';
            if( $sourcename !='' )
            {
                $image_init = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, $img_width, $img_height, 'thumb'.$img_width.$img_height, $sourcepath, $sourcepath, 60);
            }
            $image = $image_init;
            if( $media_bucket_url != '' && $image != '' )
            {
                $explode_array_media = explode( $media_bucket_url, $image );
                $image = $explode_array_media[1];
                $explode_array_size = explode( '?', $image );
                $image = $explode_array_size[0];
            }
            if (substr($image, 0, 1) == '/') $image = substr($image, 1, strlen($image));
            
            $ttd_item = array(
                'id'=>$id,
                'title' => $title,
                'image' => $image,
                'description' => $description
            );
            
            if( $from_mobile == 0 )
            {
                $titlealt     = $this->utils->cleanTitleDataAlt($title_init);
                $description  = $this->utils->htmlEntityDecode($description);
                $description  = $this->utils->getMultiByteSubstr( $description, $desc_length, NULL, $lang );

                $page_link    = $this->utils->generateLangURL( $lang,'/'.$itemData['a_alias']);
                $parent_alias = $this->utils->generateLangURL( $lang, $itemData['pa_alias']);
                $parent_name  = $this->utils->htmlEntityDecode( $itemData['pt_title'] );
                
                $ttd_item['description'] = $description;
                $ttd_item['name'] = $ttd_item['title'];
                $ttd_item['titlealt'] = $titlealt;
                $ttd_item['img'] = $image_init;
                $ttd_item['link'] = $page_link;
                $ttd_item['parent_alias'] = $parent_alias;
                $ttd_item['parent_name'] = $parent_name;
            }
            
            $thingstodo_All[] = $ttd_item;
        }
        return $thingstodo_All;
    }
    
    public function thingsTodoDetailsQuery( $options )
    {
        $from_mobile = ( isset($options['from_mobile']) )?$options['from_mobile']:1;
        $lang = ( isset($options['lang']) )?$options['lang']:'en';
        $media_bucket_url = $this->container->get("TTRouteUtils")->getMediaBucketURL();

        $thingstodoDetails = $this->container->get('ThingsToDoServices')->getRelatedThingsToDoList($options);
        $output = array();
        
        if($thingstodoDetails){
	    $xmlMediaPath = "media/360/ttd/";
            $CONFIG_SERVER_ROOT = $this->container->getParameter('CONFIG_SERVER_ROOT');
            foreach($thingstodoDetails as $thingstodo){
                $image_init  = $this->container->get("TTRouteUtils")->generateMediaURL("media/thingstodo/default_big.jpg");
                if ($thingstodo['t_image'] != "") {
                    $sourcepath = 'media/thingstodo/';
                    $sourcename = $thingstodo['t_image'];
                    $image_init = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 599, 280, 'thumb599280', $sourcepath, $sourcepath, 80);
                }
                $image = $image_init;
                if( $media_bucket_url != '' && $image != '' )
                {
                    $explode_array_media = explode( $media_bucket_url, $image );
                    $image = $explode_array_media[1];
                    $explode_array_size = explode( '?', $image );
                    $image = $explode_array_size[0];
                }
                if (substr($image, 0, 1) == '/') $image = substr($image, 1, strlen($image));

                $list360 = array();
                $fullTourURL = '';
                $thingstodoDetailDivisions = $this->container->get('ThingsToDoServices')->getThingstodoDivisions( $thingstodo['t_id'], NULL, NULL, true );
                
                if( $thingstodoDetailDivisions )
                {
                    $fullTourURL = '360-photos-vr/'.$thingstodo['a_alias'].'/'.$thingstodo['t_slug'];
                    foreach ($thingstodoDetailDivisions['data']['divisions'] as $item)
                    {
                        if( $item['parent_id'] == '' ) continue;

                        $thumburl = strtolower($thingstodoDetailDivisions['data']['country_code']).'/'.$thingstodo['t_id'].'/'.$item['category_id'].'/'.$item['parent_id'].'/'.$item['id'].'/';
                        $thumbpath = $thumburl."thumb.jpg";
                        $sphere360 = $thumburl."360_sphere.jpg";
                        if($this->container->get("TTFileUtils")->fileExists($CONFIG_SERVER_ROOT.$xmlMediaPath.$sphere360)){
                            $list360[] = array("image"=>"/".$xmlMediaPath.$thumbpath,"image360"=>"/".$xmlMediaPath.$sphere360);
                        }
                    }
                }
                $title_init = $thingstodo['t_title'];
                if ($thingstodo['ml_title'] != '') $title_init = $thingstodo['ml_title'];
                $title       = $this->utils->htmlEntityDecode($title_init);
                $description = $thingstodo['t_description'];
                if ($thingstodo['ml_description'] != '') $description = $thingstodo['ml_description'];
                $description = $this->utils->htmlEntityDecode($description);

                $ttd_item = array(
                    'id'=>$thingstodo['t_id'],
                    'title' => $title,
                    'image' => $image,
                    'description' => $description,
                    'list360'=> $list360,
                    'fullTourURL'=> $fullTourURL
                );

                if( $from_mobile == 0 )
                {
                    $special_deals = '';
                    $deals_starting_from = 0;
                    if ( $this->container->getParameter('SHOW_DEALS_BLOCK') == 1 ) {
                        $dealEnhancedSearchByDealNameEncoded = $this->container->get('DealServices')->getEnhancedSearchByDealName($thingstodo['t_title'],null,1);
                        $dealEnhancedSearchByDealNameDecoded = json_decode($dealEnhancedSearchByDealNameEncoded, true);
                        $dealEnhancedSearchByDealName = $dealEnhancedSearchByDealNameDecoded['data'];
                        if($dealEnhancedSearchByDealName){
                            foreach ($dealEnhancedSearchByDealName as $itemDeal) {
                                $special_deals = $itemDeal['link'];
                                $deals_starting_from = intVal($itemDeal['price']);
                            }
                        }
                    }
                    
                    $exists_360 = 0;
                    if( $thingstodo['td_divisionCategoryId'] !='' )
                    {
                        $exists_360 = 1;
                    }
                    $page_link = $this->utils->generateLangURL( $lang,'/'.$thingstodo['a_alias'].'/'.$thingstodo['t_slug']);                    
                    $titlealt    = $this->utils->cleanTitleDataAlt($title_init);
                    $description = nl2br($description);
                    $description = stripslashes($description);

                    $ttd_item['titlealt'] = $titlealt;
                    $ttd_item['img'] = $image_init;
                    $ttd_item['description'] = $description;
                    $ttd_item['special_deals'] = $special_deals;
                    $ttd_item['deals_starting_from'] = $deals_starting_from;
                    $ttd_item['exists_360'] = $exists_360;
                    $ttd_item['country'] = $thingstodo['t_country'];
                    $ttd_item['category_id'] = $thingstodo['td_divisionCategoryId'];
                    $ttd_item['division_id'] = $thingstodo['td_id'];
                    $ttd_item['link'] = $page_link;
                }

                $output[] = $ttd_item;
            }
	}
        return $output;
    }
}
