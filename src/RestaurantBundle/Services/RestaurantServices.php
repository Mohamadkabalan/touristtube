<?php

namespace RestaurantBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class RestaurantServices
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
    * @get360FeaturedRestaurants function return list of 360 Featured Restaurants
    */
    public function get360FeaturedRestaurants($srch_options = array())
    {
        $featured_restaurants_360 = $this->em->getRepository('RestaurantBundle:Restaurant')->get360FeaturedRestaurants( $srch_options );

        $media_360_base_path = $this->container->getParameter('MEDIA_360_BASE_PATH').'hotels/';
        $restaurants_list = array();
        foreach ($featured_restaurants_360 as $item)
        {
            $item_array = array();
            $item_array['id']      = $item['r_id'];
            $item_array['hotel_id']= $item['r_hotelId'];
            $item_array['hdc_id']  = $item['hdc_id'];
            $item_array['hd_id']   = $item['hd_id'];
            $item_array['hd2_id']  = $item['hd2_id'];
            $item_array['country'] = strtolower( $item['rw_countryCode'] );
            $item_array['name']    = $this->utils->htmlEntityDecode($item['r_name']);
            $item_array['namealt'] = $this->utils->cleanTitleDataAlt($item['r_name']);
            $item_array['hotel_name']    = $this->utils->htmlEntityDecode($item['h_name']);
            $item_array['hotel_namealt'] = $this->utils->cleanTitleDataAlt($item['h_name']);
            $item_array['city']    = $this->utils->htmlEntityDecode($item['rw_name']);

            $sourcepath = $media_360_base_path.''.$item_array['country'].'/'.$item_array['hotel_id'].'/'.$item_array['hdc_id'].'/'.$item_array['hd_id'].'/'.$item_array['hd2_id'].'/';
            $sourcename = $item['hi_filename'];
            $image = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 284, 159, 'thumb284159', $sourcepath, $sourcepath, 50);

            $item_array['img']    = $image;
            $item_array['link']    = $this->container->get('TTRouteUtils')->returnRestaurant360Link($srch_options['lang'], $item_array['id'], $item_array['hotel_name'].' '.$item_array['name'], $item_array['city'] );
            $restaurants_list[]        = $item_array;
        }

        return $restaurants_list;
    }

    /**
     */
    public function getRestaurantInfo( $id, $language='en' )
    {
        return $this->em->getRepository('RestaurantBundle:Restaurant')->getRestaurantInfo( $id, $language );
    }

    /**
     */
    public function getRestaurantDivisions( $restaurantId, $categoryId, $divisionId, $withSubDivisions )
    {
        $restaurantInfo = $this->getRestaurantInfo( $restaurantId );

        if( $restaurantInfo )
        {
            $hotelId = $restaurantInfo['r_hotelId'];

            if( !$divisionId )
            {
                $divisionId = $restaurantInfo['r_divisionId'];
            }
        }
        else
        {
            return false;
        }

        $results = $this->container->get('HotelsServices')->getHotelDivisions($hotelId, $categoryId, $divisionId, $withSubDivisions, false, 'restaurants');
        $results['data']['restaurant_id'] = $restaurantId;
        $results['data']['hotel_id'] = $hotelId;
        return $results;
    }

}
