<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class PhotosVideosController extends DefaultController {

    public function __construct() {
        
    }

    public function categoriesListAction()
    {
        $request = Request::createFromGlobals();
        $hide_all = intval($request->query->get('hide_all', 0));

        $options = array
        (
            'hide_all' => $hide_all,
            'lang' => $this->getLanguage()
        );
        $resp = $this->get('ApiPhotosVideosServices')->categoryGetHash( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function photosVideosSearchAction() {

        $request = Request::createFromGlobals();

        $size = $request->query->get('size', '');
        $w = $request->query->get('w', 0);

        $limit = intval($request->query->get('limit', 10));
        if( $limit > 50 ) $limit = 50;
        $page = intval($request->query->get('page', 0));
        if( $page > 50 ) $page = 50;
        $lang = $this->getLanguage();
//        $user_id = intval($request->query->get('user_id', 0));
//        if( $user_id == 0 ) $user_id = null; 
        $owner_id = null;
        
        $media_type = $request->query->get('media', null);
        $orderby = $request->query->get('orderby', 'id');
        $order = $request->query->get('order', 'a');
        $album = intval($request->query->get('album', 0));
        if( $album == 0 ) $album = null;
        $city_id = intval($request->query->get('city_id', 0));
        if( $city_id == 0 ) $city_id = null;
        $statename = $request->query->get('statename', null);
        $country = $request->query->get('country', null);
        $n_results = $request->query->get('n_results', false);
        $media_id = intval($request->query->get('id', 0));
        if( $media_id == 0 ) $media_id = null;
        $hash_id = $request->query->get('hash_id', null);
        $date_from = $request->query->get('date_from', null);
        $date_to = $request->query->get('date_to', null);
        $channel_id = intval($request->query->get('channel_id', 0));
        if( $channel_id == 0 ) $channel_id = null;
        $catalog_status = intval($request->query->get('catalog_status', -1));
        $featured = intval($request->query->get('featured', 0));

        $type = $request->query->get('type', 'most');
        if ( $type == 'latest' ) {
            $orderby = 'pdate';
            $order = 'd';
        } else if ( $type == 'most' ) {
            $orderby = 'nbViews';
            $order = 'd';
        } 

        $options = array
        (
            'limit' => $limit,
            'page' => $page,
            'lang' => $lang,
            'is_public' => 2,
            'owner_id' => $owner_id,
            'type' => $media_type,
            'orderby' => $orderby,
            'order' => $order,
            'catalog_id' => $album,
            'city_id' => $city_id,
            'statename' => $statename,
            'country' => $country,
            'n_results' => $n_results,
            'media_id' => $media_id,
            'hash_id' => $hash_id,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'channel_id' => $channel_id,
            'catalog_status' => $catalog_status,
            'featured' => $featured,
            'pic_width' => $w,
            'pic_size' => $size
        );

        $resp = $this->get('ApiPhotosVideosServices')->photosVideosSearchQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
}