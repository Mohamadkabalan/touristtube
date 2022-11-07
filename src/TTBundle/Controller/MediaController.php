<?php

namespace TTBundle\Controller;

header("Access-Control-Allow-Origin: *");

use TTBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends DefaultController {

    public $data = array();

    /**
     * @param $module
     * @param $hotel_name
     * @param $country_iso3
     * @param $hotel_id
     * @param $grp
     * @param $div
     * @param $sdiv
     * @param $physical_file
     * @param $fake_file_name
     * @param Request $request
     * This function get the module, hotel name, country code, hotel id, grp id, div id and sub div id and creates the url to download the required image
     */
    public function get360MediaAction($module, $hotel_name, $country_iso3, $hotel_id, $grp, $div, $sdiv, $physical_file, $fake_file_name, Request $request) {
        $w = $request->query->get('w');
        $h = $request->query->get('h');
        //
        $basePath = $this->container->getParameter('MEDIA_360_BASE_PATH') . $module . "/";

        if( $module =='thingstodo' )
        {
            $basePath = $this->container->getParameter('TTD_MEDIA_360_BASE_PATH');
        }
        
        $path = $basePath . $country_iso3 . "/" . $hotel_id . "/" . $grp . "/" . $div . "/" . $sdiv . "/" . $physical_file;
        $this->loadMediaFile($path, $fake_file_name, $w, $h, $hotel_id);

        $result = array();
        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * @param $module
     * @param $hotel_name
     * @param $country_iso3
     * @param $hotel_id
     * @param $grp
     * @param $div
     * @param $sdiv
     * @param $physical_file
     * @param $device
     * @param $fake_file_name
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * This function get all the required parameters and generates a link to dwonload images related to the mobile version
     */
    public function get360MediaMobileAction($module, $hotel_name, $country_iso3, $hotel_id, $grp, $div, $sdiv, $physical_file, $fake_file_name, Request $request) {
        $w = $request->query->get('w');
        $h = $request->query->get('h');
        //
        $basePath = $this->container->getParameter('MEDIA_360_BASE_PATH') . $module . "/";
        
        if( $module =='thingstodo' )
        {
            $basePath = $this->container->getParameter('TTD_MEDIA_360_BASE_PATH');
        }
        
        $path =  $basePath. $country_iso3 . "/" . $hotel_id . "/" . $grp . "/" . $div . "/" . $sdiv . "/mobile/" . $physical_file;
        $this->loadMediaFile($path, $fake_file_name, $w, $h, $hotel_id);

        $result = array();
        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     *
     * @param $module
     * @param $hotel_name
     * @param $country_iso3
     * @param $hotel_id
     * @param $grp
     * @param $div
     * @param $physical_file
     * @param $fake_file_name
     * @param Request $request
     * This function get the module, hotel name, country code, hotel id, grp id, div id WHITHOUT the sub div id and creates the url to download the required image
     */
    public function get360MediaMainDivAction($module, $hotel_name, $country_iso3, $hotel_id, $grp, $div, $physical_file, $fake_file_name, Request $request) {
        $w = $request->query->get('w');
        $h = $request->query->get('h');
        //
        $basePath = $this->container->getParameter('MEDIA_360_BASE_PATH') . $module . "/";

        if( $module =='thingstodo' )
        {
            $basePath = $this->container->getParameter('TTD_MEDIA_360_BASE_PATH');
        }

        $path = $basePath . $country_iso3 . "/" . $hotel_id . "/" . $grp . "/" . $div . "/" . $physical_file;
        $this->loadMediaFile($path, $fake_file_name, $w, $h, $hotel_id);

        $result = array();
        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     *
     * @param $module
     * @param $hotel_name
     * @param $country_iso3
     * @param $hotel_id
     * @param $grp
     * @param $div
     * @param $device
     * @param $physical_file
     * @param $fake_file_name
     * @param Request $request
     * This function get the module, hotel name, country code, hotel id, grp id, div id WHITHOUT the sub div id and creates the url for mobile version images
     */
    public function get360MediaMainDivMobileAction($module, $hotel_name, $country_iso3, $hotel_id, $grp, $div, $physical_file, $fake_file_name, Request $request) {
        $w = $request->query->get('w');
        $h = $request->query->get('h');
        //
        $basePath = $this->container->getParameter('MEDIA_360_BASE_PATH') . $module . "/";

        if( $module =='thingstodo' )
        {
            $basePath = $this->container->getParameter('TTD_MEDIA_360_BASE_PATH');
        }

        $path = $basePath . $country_iso3 . "/" . $hotel_id . "/" . $grp . "/" . $div . "/mobile/" . $physical_file;
        $this->loadMediaFile($path, $fake_file_name, $w, $h, $hotel_id);

        $result = array();
        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     *
     * @param $module
     * @param $hotel_name
     * @param $country_iso3
     * @param $hotel_id
     * @param $fake_file_name
     * @param Request $request
     * @return NULL
     * This function receives all the parameter from the url and then generates the adequate URL and then sends it to the function responsible of resizing the image
     */
    public function get360MediaParamsAction($module, $fake_file_name, Request $request) {
        $hotel_name = $request->query->get('hotelName');
        $country_code = $request->query->get('countryCode');
        $hotel_id = $request->query->get('hotelId');
        $group = $request->query->get('grp');
        $division = $request->query->get('div');
        $sub_division = $request->query->get('sdiv');
        $device = $request->query->get('device');
        if ($sub_division != null) {
            $sub_division = $sub_division . "/";
        }
        if ($device != null) {
            $device = $device . "/";
        }
        $physical_file = $request->query->get('physicalFile');
        $w = $request->query->get('w');
        $h = $request->query->get('h');
        //
        $basePath = $this->container->getParameter('MEDIA_360_BASE_PATH') . $module . "/";

        if( $module =='thingstodo' )
        {
            $basePath = $this->container->getParameter('TTD_MEDIA_360_BASE_PATH');
        }

        $path = $basePath . $country_code . "/" . $hotel_id . "/" . $group . "/" . $division . "/" . $sub_division . $device . $physical_file;
        $this->loadMediaFile($path, $fake_file_name, $w, $h);

        $result = array();
        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     *
     * @param $path
     * @param $fileName
     * @param $width
     * @param $height
     * This function calls the function for resizing the image from the Media services and then preapres the header params
     */
    private function loadMediaFile($path, $fileName = null, $width = null, $height = null, $hotel_id = null) {
		
		$allowed_hotels = $this->container->getParameter('MEDIA_360_SPHERE_ALLOWED_HOTELS');
		
		if ($hotel_id != null && strpos($path, '/360_sphere.jpg') !== false && !in_array($hotel_id, $allowed_hotels))
		{
			exit;
		}
		
        $image = $this->get('MediaServices')->downloadMedia($path, $fileName, $width, $height);
        // $this->prepareMediaHeader($path, $fileName);
        //
        // echo $image;
    }

    /**
     * Method that will call to show Media on Page
     */
    // public function categoryMediaAction(Request $request)
    // {
    // $pathinfo = $request->getPathInfo();
    // $pathinfo = explode('/',$pathinfo);
    // $path = $pathinfo['1'];
    // $routepath = $path;
    // $session = $request->getSession();
    // $session->set('catLink', $routepath);
    // $em = $this->getDoctrine()->getManager();
    // $qb = $em->createQueryBuilder('DS')
    // ->select('a.id,a.alias,a.entityId')
    // ->from('TTBundle:Alias','a')
    // ->where("a.alias = :Alias AND a.entityType='search'")
    // ->setParameter(':Alias', $routepath)
    // ->setMaxResults(1);
    // $query = $qb->getQuery();
    // $aliasRes = $query->getArrayResult();
    // $aliasid = explode("/",$aliasRes['0']['entityId']);
    // $cat_id = $aliasid[1];
    // $order_by = 'c.id';
    // $order = 'DESC';
    // $em = $this->getDoctrine()->getManager();
    // $qb = $em->createQueryBuilder('CT')
    // ->select('v')
    // ->from('TTBundle:CmsVideos','v')
    // ->innerJoin('TTBundle:CmsAllcategories', 'c', 'WITH', 'c.id = v.category')
    // ->where("c.published = 1 AND c.id = :In");
    // $qb->setParameter(':In',$cat_id )->setMaxResults(6);
    // $qb->orderBy($order_by,$order);
    // $query = $qb->getQuery();
    // $mediaRes = $query->getResult();
    // $str = '';
    // foreach($mediaRes as $media){
    // $server_url = 'http://'.$_SERVER['HTTP_HOST'];
    // $image_pic = $server_url.'/'.$media->getFullpath().$media->getName();
    // $str.='<div class="col-sm-6 col-lg-4 col-xs-6 col-md-6">
    // <div class="ttd_box_outer">
    // <div class="ttd_box">
    // <img src="'.$image_pic.'">
    // <div class="below_img">
    // <div class="title">'.$media->getTitle().'</div>
    // <div class="desc">'.$media->getDescription().'</div>
    // </div>
    // </div>
    // </div>
    // </div>';
    // }
    // $this->data['catname']=$this->categoryGetName($cat_id);
    // $this->data['media']=$str;
    // $categories = $this->categoryGetHash(array('orderby' => 'itemOrder'));
    // $catArray = array();
    // foreach ($categories as $cat_id => $name) {
    // $url_replace = str_replace(" ", "+", $name);
    // $catArray[] = array('catId' => $cat_id, 'name' => $name, 'link' => $url_replace);
    // }
    // $this->data['catArray'] = $catArray;
    // return $this->render('social/category_media.html.twig',$this->data);
    // }
}
