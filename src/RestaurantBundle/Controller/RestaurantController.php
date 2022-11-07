<?php

namespace RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantController extends DefaultController
{

    public function bestRestaurantsHomeAction($seotitle, $seodescription, $seokeywords)
    {
        
        $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
        $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));

        $this->setHreflangLinks($this->generateLangRoute('_best_restaurants_home'), true, true);

        $options = array(
            'limit' => 24,
            'orderby' => 'rand',
            'lang' => $this->data['LanguageGet']
        );

        $featured_restaurants_360 = $this->get('RestaurantServices')->get360FeaturedRestaurants( $options );
        
        $this->data['featured_restaurants_360'] = $featured_restaurants_360;

        return $this->render('@Restaurant/restaurant/best-restaurants.twig', $this->data);
    }

    public function bestRestaurantsAction($seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '')
        {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->setHreflangLinks($this->generateLangRoute('_best_restaurants_restaurants'), true, true);

        $options = array(
            'limit' => 32,
            'is_featured' => 1,
            'lang' => $this->data['LanguageGet']
        );

        $featured_restaurants_360 = $this->get('RestaurantServices')->get360FeaturedRestaurants( $options );

        $this->data['featured_restaurants_360'] = $featured_restaurants_360;

        return $this->render('@Restaurant/restaurant/best-restaurants.twig', $this->data);
    }

    public function restaurantDetails360Action( $name, $city, $id, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'restaurant_360';
        $this->data['name']  = $name;
        $this->data['city']  = $city;
        $this->data['id']  = $id;

        $restaurantInfo = $this->get('RestaurantServices')->getRestaurantInfo( $id );

        if( !$restaurantInfo )
        {
            return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
        }

        $this->setHreflangLinks( $this->get('TTRouteUtils')->returnRestaurant360Link( $this->data['LanguageGet'], $id, $name, $city ), true, true );

        $title = $restaurantInfo['r_name'];
        if( $restaurantInfo['h_name'] !='' ) $title = $restaurantInfo['h_name'].' - '.$title;
        $titlealtSEO = $titlealt   = $this->get('app.utils')->cleanTitleDataAlt($title);
        $titlealt    = $this->translator->trans('View').' '.$titlealt.' '.$this->translator->trans('in 360 virtual tour');
        $title       = $this->translator->trans('View').' '.$this->get('app.utils')->htmlEntityDecode($title).' '.$this->translator->trans('in 360 virtual tour');
        $this->data['title']  = $title;
        $this->data['titlealt']  = $titlealt;

        $description = $restaurantInfo['r_description'];
        $description = $this->get('app.utils')->htmlEntityDecode($description);
        $description = nl2br($description);
        $description = stripslashes($description);
        $this->data['desc']  = $description;

        $this->data['latitude']  = $restaurantInfo['r_latitude'];
        $this->data['longitude']  = $restaurantInfo['r_longitude'];

        $hotel_id = $restaurantInfo['r_hotelId'];
        $country = strtolower( $restaurantInfo['rw_countryCode'] );

        $media_360_base_path = $this->container->getParameter('MEDIA_360_BASE_PATH').'hotels/';
        $imagePath = $country.'/'.$hotel_id.'/'.$restaurantInfo['hdc_id'].'/'.$restaurantInfo['hd_id'].'/'.$restaurantInfo['hd2_id'].'/';
        
        $this->data['img']  = $this->get("TTMediaUtils")->createItemThumbs("360_Preview.jpg", $media_360_base_path.$imagePath, 0, 0, 895, 503, 'thumb50HS895503', $media_360_base_path.$imagePath, $media_360_base_path.$imagePath, 50);

        $restaurantDivisions = $this->container->get('RestaurantServices')->getRestaurantDivisions( $id, NULL, $restaurantInfo['r_divisionId'], true, 'restaurants' );
        
        $divisions_list =array();
        foreach ( $restaurantDivisions['data']['divisions'] as $item)
        {
            $item_array = array();

            if( $item['parent_id'] == '' ) continue;

            $item_array['id'] = $id;
            $item_array['country'] = strtolower($restaurantDivisions['data']['country_code']);
            $item_array['category_id'] = $item['category_id'];
            $item_array['parentdiv_id'] = $item['parent_id'];
            $item_array['division_id'] = $item['id'];
            $item_array['namealt']   = $this->get('app.utils')->cleanTitleDataAlt($item['name']);
            $item_array['name']      = $this->get('app.utils')->htmlEntityDecode($item['parent_name']).' '.$this->get('app.utils')->htmlEntityDecode($item['name']);

            $imagePath = $item_array['country'].'/'.$restaurantDivisions['data']['id'].'/'.$item_array['category_id'].'/'.$restaurantInfo['hd_id'].'/'.$item_array['division_id'].'/';
            $sourcepath = $media_360_base_path.''.$imagePath;
            $sourcename = $item['image'];
            $image = $this->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 181, 104, 'thumb181104', $sourcepath, $sourcepath, 50);
            $item_array['img'] = $image;
            $divisions_list[] = $item_array;
        }
        $this->data['divisions_list']  = $divisions_list;

        if ($this->data['aliasseo'] == '') {
            $action_array                 = array();
            $action_array[]               = $name;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);


            $action_array                 = array();
            $action_array[]               = $name;
            $action_array[]               = $city;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        return $this->render('@Restaurant/restaurant/restaurant_details_360.twig', $this->data);
    }

    public function restaurantDetails360FullTourAction(Request $request, $name, $city, $id, $seotitle, $seodescription, $seokeywords)
    {

        $restaurantInfo = $this->get('RestaurantServices')->getRestaurantInfo( $id );

        if( !$restaurantInfo )
        {
            return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
        }

        $restaurantDivisions = $this->container->get('RestaurantServices')->getRestaurantDivisions( $id, NULL, $restaurantInfo['r_divisionId'], true, 'restaurants' );
        $mainData  = $restaurantDivisions['data'];
        $divisions = $mainData['divisions'];

        $menu360 = array();
        $image = '';
        $media_360_base_path = $this->container->getParameter('MEDIA_360_BASE_PATH').'hotels/';
        foreach ($divisions as $item)
        {
            $item_array = array();
            $divId = $item['id'];
            $subDivId = null;
            if( $item['parent_id'] == '' ) continue;
            if( $item['parent_id'] != '' )
            {
                $subDivId = $divId;
                $divId    = $item['parent_id'];
            }
            if( $image == '' )
            {
                $imagePath = strtolower($mainData['country_code']).'/'.$mainData['id'].'/'.$item['category_id'].'/'.$restaurantInfo['r_divisionId'].'/'.$item['id'].'/';
                $sourcepath = $media_360_base_path.''.$imagePath;
                $sourcename = $item['image'];
                $image = $this->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 190, 186, 'thumb190186', $sourcepath, $sourcepath, 50);
                $currentServerURL = $this->get('TTRouteUtils')->UriCurrentServerURL();
                $image_array = explode( $currentServerURL[1].'/', $image);
                $image = $image_array[ count($image_array) - 1 ];
            }

            $item_array['name']      = $this->get('app.utils')->htmlEntityDecode($item['parent_name']).' '.$this->get('app.utils')->htmlEntityDecode($item['name']);
            $item_array['type'] = 'restaurants';
            $item_array['entityName']      = $this->get('app.utils')->htmlEntityDecode($mainData['name']);
            $item_array['country'] = strtolower($mainData['country_code']);
            $item_array['entityId'] = $id;
            $item_array['divisionId'] = $subDivId;
            $item_array['groupId'] = $item['group_id'];
            $item_array['groupName'] = $item['group_name'];
            $item_array['catgId'] = $item['category_id'];
            $item_array['subDivisionId'] = '';
            $item_array['data_icon'] = false;

            $menu360[] = $item_array;
        }
        $this->data['menu360'] = $menu360;
        
        $homeTT               = array();
        $homeTT[]             = array('name' => '', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/360-photos/en-logo256.png'), 'link' => '/', 'title' => 'Tourist Tube');
        $this->data['homeTT'] = $homeTT;

        $menuTT = array();

        $options    = array(
            'lang' => $this->data['LanguageGet'],
            'show_main' => null,
            'city_id' => $restaurantInfo['r_cityId'],
            'from_mobile' => 0
        );
        
        $menuTT = $this->get('ApiDiscoverServices')->thingsTodoSearchQuery( $options );

        $this->data['menuTT'] = $menuTT;

        // Page title
        $this->data['pageTitle'] = "360-photos ".$restaurantInfo['r_name'];
        
        $this->data['logo']  = $image;
        return $this->render('media_360/photos-360.twig', $this->data);
    }

    public function restaurantDetails360RedirectAction( $name, $city, $id, $seotitle, $seodescription, $seokeywords )
    {
        return $this->redirectToLangRoute('_restaurant_details_360', array( 'name'=>$name, 'city'=>$city, 'id'=>$id ), 301);
    }

    public function bestRestaurantsRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }

    public function restaurantsInRedirectAction($dest, $srch = '', $seotitle, $seodescription, $seokeywords)
    {
        $dest = $dest.''.$srch;
        return $this->redirectToLangRoute('_restaurants_in_restaurants', array('dest' => $dest), 301);
    }

    public function restaurantsInAction($dest, $srch = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }

    public function restaurantsReviewRedirectAction($name, $srch, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_restaurantsReview_restaurants', array('name' => $name, 'srch' => $srch), 301);
    }

    public function restaurantsReviewAction($name, $srch, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }

    public function oldrestaurantsReviewAction($name, $srch)
    {
        return $this->redirectToLangRoute('_restaurantsReview_restaurants', array('name' => $name, 'srch' => $srch), 301);
    }

    public function restaurantsNearByRedirectAction($dest, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_restaurants_near_restaurants', array('dest' => $dest), 301);
    }

    public function restaurantsNearByAction($dest, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }

    public function prefixHotelRedirectAction($catName, $qr, $l, $np, $c, $seotitle, $seodescription, $seokeywords)
    {
        if ($c == 1) {
            return $this->redirectToLangRoute('_prefix_hotel_restaurants', array('catName' => $catName, 'qr' => $qr, 'l' => $l, 'np' => $np, 'c' => $c), 301);
        } else {
            return $this->prefixHotelAction($catName, $qr, $l, $np, $c, $seotitle, $seodescription, $seokeywords);
        }
    }

    public function prefixHotelAction($catName, $qr, $l, $np, $c, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }

    public function searchRestaurantResultRedirectAction($srch, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_search_restaurant_target_restaurants', array('srch' => $srch), 301);
    }

    public function searchRestaurantResultAction($srch, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }
}
