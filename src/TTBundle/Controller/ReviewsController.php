<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewsController extends DefaultController
{

    /**
     * This method prepares the point of interest review page
     *
     * @return twig
     */
    public function thingstodoReviewAction($name, $srch, $seotitle, $seodescription, $seokeywords)
    {
        $item_id = urldecode($srch);
        $this->getReviewDataNewContent($name, $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'), $seotitle, $seodescription, $seokeywords);
        
        return $this->render('default/review_details.twig', $this->data);
    }

    /**
     * This method prepares the airport review page
     *
     * @return twig
     */
    public function airportReviewAction($name, $srch, $seotitle, $seodescription, $seokeywords)
    {
        $item_id = urldecode($srch);
        $this->getReviewDataNewContent($name, $item_id, $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'), $seotitle, $seodescription, $seokeywords);
        
        return $this->render('default/review_details.twig', $this->data);
    }

    /**
     * This method prepares the hotel review page
     *
     * @return twig
     */
    public function hotelsReviewAction($name = '', $srch = 0, $seotitle, $seodescription, $seokeywords)
    {
        $item_id  = urldecode($srch);
        $objects1 = $this->get('ReviewsServices')->getHotelInfo( $item_id );
        if ($objects1[0]->getHotelId() > 0) {
//            return $this->hotelReviewsAction($name, $objects1[0]->getHotelId(), $seotitle, $seodescription, $seokeywords);
            $response = $this->forward('HotelBundle:Hotels:hotelReviews', array(
                'name'  => $name,
                'id' => $objects1[0]->getHotelId(),
                'seotitle' => $seotitle,
                'seodescription' => $seodescription,
                'seokeywords' => $seokeywords,
            ));

            return $response;
        }
        $this->getReviewDataNewContent($name, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL'), $seotitle, $seodescription, $seokeywords);
        
        return $this->render('default/review_details.twig', $this->data);
    }

    public function reviewAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['showHeaderSearch'] = 0;
        $this->setHreflangLinks($this->generateLangRoute('_review'),true,true);
        
        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        
        $this->data['SOCIAL_ENTITY_HOTEL']      = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $this->data['SOCIAL_ENTITY_RESTAURANT'] = $this->container->getParameter('SOCIAL_ENTITY_RESTAURANT');
        $this->data['SOCIAL_ENTITY_LANDMARK']   = $this->container->getParameter('SOCIAL_ENTITY_LANDMARK');
        $this->data['SOCIAL_ENTITY_AIRPORT']    = $this->container->getParameter('SOCIAL_ENTITY_AIRPORT');
        return $this->render('default/review.twig', $this->data);
    }

    public function reviewDetailsNewAction()
    {
        return $this->render('default/review_details.twig', $this->data);
    }

    public function getReviewDataNewContent($name = '', $item_id = 0, $type, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'rate_review';
        $objects1                   = false;
        $this->data['description']  = '';
        $this->data['iataCode']     = '';
        $objects_name               = $dimage = $objects_type = $dimagepath = $locationText = $locationTextSeo = $locationStructureddata = $cityTextSeo = $objects_country = $rows_res = '';
        $reviewtype                 = $this->container->getParameter('SOCIAL_ENTITY_HOTEL_REVIEWS');
        $stars                      = 0;
        $user_id                    = $this->userGetID();
        $toRateArr                  = array();
        $this->data['certify']      = $this->translator->trans('I certify that this review is based on my personal experience and is my genuine opinion of this institution');
        $this->data['nb_votes']     = 0;
        switch ($type) {
            case $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'):
                $objects1                   = $this->get('ReviewsServices')->getAirportInfo($item_id);
                if (!sizeof($objects1)) return $this->pageNotFoundAction();
                $objects                    = $objects1[0];
                $objects_name               = $objects->getName();
                $this->setHreflangLinks($this->get('TTRouteUtils')->returnAirportReviewLink($this->data['LanguageGet'], $objects->getId(), $objects_name), true, true);
                $rows_res                   = $objects1[1];                
                $dimage                     = $this->get("TTMediaUtils")->createItemThumbs('airport-icon.jpg', 'media/images/', 0, 0, '204', '116', 'thumb204116');
                $locationArray              = $this->getAirportAdressReview($objects);
                $locationText               = $locationArray[0];
                $locationTextSeo            = $locationArray[1];
                $reviewtype                 = $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_REVIEWS');
                $irating1                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_RECEPTION'));
                if (!$irating1) $irating1                   = 0;
                $irating2                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_LOUNGE'));
                if (!$irating2) $irating2                   = 0;
                $irating3                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_LUGGAGE'));
                if (!$irating3) $irating3                   = 0;
                $irating4                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_FOOD'));
                if (!$irating4) $irating4                   = 0;
                $irating5                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_DUTYFREE'));
                if (!$irating5) $irating5                   = 0;
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_RECEPTION'),
                    'val' => $irating1, 'name' => $this->translator->trans('Reception'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_LOUNGE'),
                    'val' => $irating2, 'name' => $this->translator->trans('Lounge'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_LUGGAGE'),
                    'val' => $irating3, 'name' => $this->translator->trans('Luggage service'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_FOOD'),
                    'val' => $irating4, 'name' => $this->translator->trans('Food'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_AIRPORT_DUTYFREE'),
                    'val' => $irating5, 'name' => $this->translator->trans('Duty free'));
                $objects_country            = $objects->getCountry();
                $this->data['evalText']     = $this->translator->trans('your evaluation for this airport');
                $this->data['closedText']     = $this->translator->trans("This airport is out of service");
                $this->data['nb_votes']     = 0;
                $this->data['discover_nam'] = $this->get('app.utils')->cleanTitle($objects_name).'_airport_';
                $irating_average            = $this->get('ReviewsServices')->socialRateAverage($objects->getId(), array($type));
            break;
            case $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'):
                $objects1                  = $this->get('ReviewsServices')->getPoiInfo($item_id);
                if (!$objects1) return $this->pageNotFoundAction();
                $objects                   = $objects1[0];
                $this->data['description'] = $objects->getDescription();
                $objects_name              = $objects->getName();
                $this->setHreflangLinks($this->get('TTRouteUtils')->returnThingstodoReviewLink($this->data['LanguageGet'], $objects->getId(), $objects_name), true, true);
                $rows_img                  = $objects->getImages();
                if (sizeof($rows_img)) {
                    $rows_res = $rows_img[0];
                }
                $dimage                     = $this->get("TTMediaUtils")->createItemThumbs('landmark-icon1.jpg', 'media/images/', 0, 0, '204', '116', 'thumb204116');
                $locationArray              = $this->getPoiAdressReview($objects);
                $locationText               = $locationArray[0];
                $locationTextSeo            = $locationArray[1];
                $cityTextSeo                = $locationArray[2];
                $reviewtype                 = $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_REVIEWS');
                $irating1                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE'));
                if (!$irating1) $irating1                   = 0;
                $irating2                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_STAIRS'));
                if (!$irating2) $irating2                   = 0;
                $irating3                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_WHEELCHAIR'));
                if (!$irating3) $irating3                   = 0;
                $irating4                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_PARKING'));
                if (!$irating4) $irating4                   = 0;
                $irating5                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES'));
                if (!$irating5) $irating5                   = 0;
                $irating6                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_STORAGE'));
                if (!$irating6) $irating6                   = 0;
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE'),
                    'val' => $irating1, 'name' => $this->translator->trans('Food available'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_STAIRS'),
                    'val' => $irating2, 'name' => $this->translator->trans('Stairs, elevator'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_WHEELCHAIR'),
                    'val' => $irating3, 'name' => $this->translator->trans('Wheelchair access'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_PARKING'),
                    'val' => $irating4, 'name' => $this->translator->trans('Stroller parking'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES'),
                    'val' => $irating5, 'name' => $this->translator->trans('Bathroom facilities'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_LANDMARK_STORAGE'),
                    'val' => $irating6, 'name' => $this->translator->trans('Lockers, storage'));
                $objects_country            = $objects->getCountry();
                $this->data['evalText']     = $this->translator->trans('your evaluation for this point of interest');
                $this->data['closedText']     = $this->translator->trans("This point of interest is out of service");
                $this->data['nb_votes']     = 0;
                $this->data['discover_nam'] = $this->get('app.utils')->cleanTitle($objects_name).'_poi_';
                $irating_average            = $this->get('ReviewsServices')->socialRateAverage($objects->getId(), array($type));
            break;
            case $this->container->getParameter('SOCIAL_ENTITY_HOTEL'):
                $objects1                  = $this->get('ReviewsServices')->getHotelInfo($item_id);
                if (!$objects1) return $this->pageNotFoundAction();
                $objects                   = $objects1[0];
                $this->data['description'] = $objects->getDescription();
                $objects_name              = $objects->getHotelName();
                $objects_type              = $this->get('app.utils')->htmlEntityDecode($objects1[2]->getTitle());
                $this->setHreflangLinks( $this->get('TTRouteUtils')->returnHotelReviewLink($this->data['LanguageGet'], $objects->getId(), $objects_name), true, true);
                $rows_res                  = $objects1[3];
                $dimage                    = $this->get("TTMediaUtils")->createItemThumbs('hotel-icon-image1.jpg', 'media/images/', 0, 0, '204', '116', 'thumb204116');
                $locationArray             = $this->getHotelAdressReview($objects);
                $locationText              = $locationArray[0];
                $locationTextSeo           = $locationArray[1];
                $stars                     = ceil($objects->getStars());
                if ($stars > 5) $stars                     = 5;
                $reviewtype                = $this->container->getParameter('SOCIAL_ENTITY_HOTEL_REVIEWS');

                $irating1                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_AIRPOT'));
                if (!$irating1) $irating1                   = 0;
                $irating2                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_SERVICE'));
                if (!$irating2) $irating2                   = 0;
                $irating3                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_CLEANLINESS'));
                if (!$irating3) $irating3                   = 0;
                $irating4                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_INTERIOR'));
                if (!$irating4) $irating4                   = 0;
                $irating5                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_PRICE'));
                if (!$irating5) $irating5                   = 0;
                $irating6                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_FOODDRINK'));
                if (!$irating6) $irating6                   = 0;
                $irating7                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_INTERNET'));
                if (!$irating7) $irating7                   = 0;
                $irating8                   = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $this->container->getParameter('SOCIAL_ENTITY_HOTEL_NOISE'));
                if (!$irating8) $irating8                   = 0;
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_AIRPOT'),
                    'val' => $irating1, 'name' => $this->translator->trans('Good for airport access'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_SERVICE'),
                    'val' => $irating2, 'name' => $this->translator->trans('Service'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_CLEANLINESS'),
                    'val' => $irating3, 'name' => $this->translator->trans('Cleanliness'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_INTERIOR'),
                    'val' => $irating4, 'name' => $this->translator->trans('Interior'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_PRICE'),
                    'val' => $irating5, 'name' => $this->translator->trans('Value for price'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_FOODDRINK'),
                    'val' => $irating6, 'name' => $this->translator->trans('Food and drink'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_INTERNET'),
                    'val' => $irating7, 'name' => $this->translator->trans('Internet'));
                $toRateArr[]                = array('id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL_NOISE'),
                    'val' => $irating8, 'name' => $this->translator->trans('Noise level'));
                $objects_country            = $objects->getCountryCode();
                $this->data['evalText']     = $this->translator->trans('your evaluation for this hotel');
                $this->data['closedText']     = $this->translator->trans("This hotel is out of service");
                $this->data['nb_votes']     = $objects->getNbVotes();
                $this->data['discover_nam'] = $this->get('app.utils')->cleanTitle($objects_name).'_hotel_';
                if ($objects->getRating() != null && $objects->getRating()) {
                    $irating_average = ceil(($objects->getRating() / 2) * 10) / 10;
                } else {
                    $irating_average = $this->get('ReviewsServices')->socialRateAverage($objects->getId(), array($type));
                }
            break;
        }
        $this->data['longitude']    = $objects->getLongitude();
        $this->data['latitude']     = $objects->getLatitude();
        $this->data['toRateArr']    = $toRateArr;
        $cityId                     = intval($objects->getCityId());
        $todoRelatedLinksArr        = $this->getThingstodoRelatedLinks($cityId);
        $todoLink                   = $todoRelatedLinksArr[0];
        $todoLinkName               = $todoRelatedLinksArr[1];
        $this->data['todoLink']     = $todoLink;
        $this->data['todoLinkName'] = $todoLinkName;

        $title = $this->get('app.utils')->htmlEntityDecode($objects_name);
        if ($objects_type) {
            $title .= ', '.$objects_type;
        }
        $link     = '/media/discover/';
        $theLink  = $this->container->getParameter('CONFIG_SERVER_ROOT');        
        $published                     = $objects->getPublished();
        $this->data['published']       = $published;
        $this->data['discover_imgbig'] = '';
        if ($rows_res && $rows_res->getFilename()) {
            $dimagepath = 'media/discover/';
            $dimage     = $rows_res->getFilename(); 
            $this->data['discover_imgbig'] = $this->get("TTRouteUtils")->generateMediaURL($dimagepath.$dimage);
        }else if (sizeof($objects1) > 1 && isset($objects1[1]) && $objects1[1]->getImage() != null && $objects1[1]->getImage()) {
            $dimagepath                    = 'media/thingstodo/';
            $dimage                        = $objects1[1]->getImage();
            $this->data['discover_imgbig'] = $this->get("TTRouteUtils")->generateMediaURL("/media/thingstodo/".$objects1[1]->getImage());
        }
        if ($dimagepath) {
            $this->data['discover_img'] = $this->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 204, 116, 'thumb204116');
        } else {
            $this->data['discover_img'] = $dimage;
        }
        if ($this->data['discover_imgbig']) {
            $this->data['fbimg'] = $this->data['discover_imgbig'];
        } else {
            $this->data['fbimg'] = $this->data['discover_img'];
        }
        if ($this->data['aliasseo'] == '') {
            $action_array      = array();
            $objects_nameSEO   = $objects_name;
            $objects_nameSEOTT = $this->get('app.utils')->htmlEntityDecodeSEO($objects_nameSEO);
            $objects_nameSEOTT = $this->get('app.utils')->getMultiByteSubstr( $objects_nameSEOTT, 20, NULL, $this->data['LanguageGet'], false );
            
            $action_array[]         = $objects_nameSEOTT;
            $action_array[]         = $locationTextSeo;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            if ($type == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK')) {
                $action_array       = array();
                $objects_nameSEO    = $objects_name;
                $objects_nameSEOTT1 = $objects_nameSEOTT  = $this->get('app.utils')->htmlEntityDecodeSEO($objects_nameSEO);
                $objects_nameSEOTT = $this->get('app.utils')->getMultiByteSubstr( $objects_nameSEOTT, 28, NULL, $this->data['LanguageGet'], false );
                
                $objects_nameSEOTT1 = $objects_nameSEOTT1.' '.$cityTextSeo;
                $objects_nameSEOTT1 = $this->get('app.utils')->getMultiByteSubstr( $objects_nameSEOTT1, 28, NULL, $this->data['LanguageGet'], false );
                
                $action_array[]               = $objects_nameSEOTT;
                $action_array[]               = $locationTextSeo;
                $action_array[]               = $objects_nameSEOTT1;
                $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
                $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

                $action_array              = array();
                $action_array[]            = $objects_nameSEOTT;
                $action_array[]            = $locationTextSeo;
                $action_array[]            = $cityTextSeo;
                $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
                $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            } else {
                $action_array      = array();
                $objects_nameSEO   = $objects_name;
                $objects_nameSEOTT = $this->get('app.utils')->htmlEntityDecodeSEO($objects_nameSEO);
                $objects_nameSEOTT = $this->get('app.utils')->getMultiByteSubstr( $objects_nameSEOTT, 35, NULL, $this->data['LanguageGet'], false );
                
                $action_array[]               = $objects_nameSEOTT.' '.$locationTextSeo;
                $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
                $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

                $action_array              = array();
                $action_array[]            = $locationTextSeo;
                $action_array[]            = $objects_nameSEOTT;
                $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
                $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            }
        }
        $uricurpage    = $this->get('TTRouteUtils')->UriCurrentPageURL();
        $media_hotelsi = array();
        $rows_imgarr   = $landmark_imgarr = array();

        if ($type == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'))
        {
            $landmark_imgarr = $rows_img;
        }
        else
        {
            $rows_imgarr = $this->get('ReviewsServices')->getDiscoverImages( $item_id, $type );
        }
        
        $reviews_images = array();
        foreach ($rows_imgarr as $row_item) {
            $arr       = array();
            $bigim     = $row_item['di_filename'];
            
            $dimagepath = 'media/discover/';
            $fullpath   = $theLink.$dimagepath.$bigim;

            $arr['discover_imgbig'] = $this->get("TTRouteUtils")->generateMediaURL($dimagepath.$bigim);
            $arr['img'] = $this->get("TTMediaUtils")->createItemThumbs($bigim, $dimagepath, 0, 0, 181, 108, 'rate-review-181108');
            $arr['id'] = $row_item['di_id'];
            $arr['disp_remove'] = false;
            if ($this->data['isUserLoggedIn'] && $user_id && $user_id==$row_item['di_userId']) {
                $arr['disp_remove'] = true;
            }
            $dimsval           = $this->get("TTFileUtils")->getImageSizeFile($fullpath);
            $wwpic             = $dimsval[0] / 852;
            $hhpic             = $dimsval[1] / 500;
            $imageclass        = " bgwidth";
            if ($hhpic > $wwpic) $imageclass        = " bgheight";
            $arr['imageclass'] = $imageclass;
            $reviews_images[]  = $arr;
        }
        foreach ($landmark_imgarr as $row_item) {
            $arr    = array();
            $bigim  = $row_item->getFilename();

            $dimagepath = 'media/discover/';
            $fullpath   = $theLink.$dimagepath.$bigim;

            $arr['discover_imgbig'] = $this->get("TTRouteUtils")->generateMediaURL($dimagepath.$bigim);
            $arr['img'] = $this->get("TTMediaUtils")->createItemThumbs($bigim, $dimagepath, 0, 0, 181, 108, 'rate-review-181108');
            $arr['id']       = $row_item->getId();
            $arr['disp_remove'] = false;
            if ($this->data['isUserLoggedIn'] && $user_id && $user_id==$row_item->getUserId()) {
                $arr['disp_remove'] = true;
            }
            $dimsval           = $this->get("TTFileUtils")->getImageSizeFile($fullpath);
            $wwpic             = $dimsval[0] / 852;
            $hhpic             = $dimsval[1] / 500;
            $imageclass        = " bgwidth";
            if ($hhpic > $wwpic) $imageclass        = " bgheight";
            $arr['imageclass'] = $imageclass;
            $reviews_images[]  = $arr;
        }
        if ($type == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK')) {
            $reviews_images = array_reverse($reviews_images);
        }
        $this->data['reviews_images']      = $reviews_images;
        
        
        $page_reviews      = $this->get('ReviewsServices')->getReviewsList($item_id, $type, 6);
        $page_reviews_list = array();
        foreach ($page_reviews as $rev) {
            $item                       = array();
            $rev_id       = $rev['r_id'];
            $user_id_rev  = $rev['r_userId'];
            $item['rev_id']             = $rev_id;
            $item['SOCIAL_ENTITY_TYPE'] = $reviewtype;
            $item['description_db']     = $this->get('app.utils')->htmlEntityDecode($rev['r_description']);
            $hideUser     = $rev['r_hideUser'];
            if ($hideUser == 1) {
                $item['user_link']            = '';
                $item['tuber_name_action']    = 'anonymous';
                $item['tuber_name_actionalt'] = 'anonymous';
            } else {
                $item['user_link']      = $this->userProfileLink( $rev, true );
                $tuber_name_action      = $this->get('app.utils')->returnUserArrayDisplayName( $rev );
                $item['tuber_name_action']    = $tuber_name_action;
                $item['tuber_name_actionalt'] = $this->get('app.utils')->cleanTitleDataAlt($tuber_name_action);
            }
            $item['disp_remove'] = false;
            if ($user_id == $user_id_rev) {
                $item['disp_remove'] = true;
            }
            $page_reviews_list[] = $item;
        }
        $this->data['page_reviews_list'] = $page_reviews_list;
        $irating                         = $this->get('ReviewsServices')->socialRated($user_id, $item_id, $type);
        if (!$irating) $irating                                 = 0;
        $this->data['irating']           = $irating; 
        $this->data['uricurpage']        = $uricurpage;
        $this->data['data_stars']        = $stars;
        $this->data['item_id']           = $item_id;
        $this->data['discover_location'] = $locationText;
        $irating_average                 = ceil(($irating_average) * 10) / 10;
        $this->data['irating_average']   = $irating_average;
        $this->data['dataType']          = $type;
        $this->data['discover_title']    = $title;
        $this->data['discover_titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($title);
    }

    public function addReviewAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $id = intval($request->request->get('id', 0));
        $entity_type = intval($request->request->get('data_type', 0));
        $description = $request->request->get('txt', '');
        $hideUser = intval($request->request->get('hideUser', 0));

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('You need to have a TouristTube account to use this feature. Click').' <a class="black_link" href="' + $this->generateLangRoute('_register') + '">'.$this->translator->trans('here').'</a> '.$this->translator->trans('to register.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $id == 0 || $entity_type == 0 || $description == '' )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = $this->translator->trans('Review added successfully.');
        $Result['status'] = 'ok';
        if( !$this->get('ReviewsServices')->addReviews( $user_id, $entity_type, $id, $description, $hideUser ) )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function removeReviewAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $id = intval($request->request->get('id', 0));
        $entity_type = intval($request->request->get('data_type', 0));

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('You need to have a TouristTube account to use this feature. Click').' <a class="black_link" href="' + $this->generateLangRoute('_register') + '">'.$this->translator->trans('here').'</a> '.$this->translator->trans('to register.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $id == 0 || $entity_type == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = $this->translator->trans('Review removed successfully.');
        $Result['status'] = 'ok';
        if( !$this->get('ReviewsServices')->removeReviews( $user_id, $id, $entity_type ) )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function addReviewPageImageAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $id = intval($request->request->get('item_id', 0));
        $entity_type = intval($request->request->get('entity_type', 0));
        $filename = $request->request->get('filename', '');

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('You need to have a TouristTube account to use this feature. Click').' <a class="black_link" href="' + $this->generateLangRoute('_register') + '">'.$this->translator->trans('here').'</a> '.$this->translator->trans('to register.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $id == 0 || $filename == '' || $entity_type == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = $this->translator->trans('Image added successfully.');
        $Result['status'] = 'ok';
        if( !$this->get('ReviewsServices')->addReviewPageImage( $user_id, $entity_type, $id, $filename ) )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function removeReviewPageImageAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $id = intval($request->request->get('id', 0));
        $entity_type = intval($request->request->get('entity_type', 0));

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('You need to have a TouristTube account to use this feature. Click').' <a class="black_link" href="' + $this->generateLangRoute('_register') + '">'.$this->translator->trans('here').'</a> '.$this->translator->trans('to register.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $id == 0 || $entity_type == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = $this->translator->trans('Image removed successfully.');
        $Result['status'] = 'ok';
        if( !$this->get('ReviewsServices')->removeReviewPageImage( $user_id, $id, $entity_type ) )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function addRateAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $entity_id = intval($request->request->get('entity_id', 0));
        $entity_type = intval($request->request->get('entity_type', 0));
        $score = intval($request->request->get('score', 0));
        $rate_type = intval($request->request->get('rate_type', 0));

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('You need to have a TouristTube account to use this feature. Click').' <a class="black_link" href="' + $this->generateLangRoute('_register') + '">'.$this->translator->trans('here').'</a> '.$this->translator->trans('to register.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $entity_id == 0 || $entity_type == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['score'] = $score;
        $Result['msg'] = $this->translator->trans('information saved successfully.');
        $Result['status'] = 'ok';
        if( !$this->get('ReviewsServices')->addRate( $user_id, $entity_id, $entity_type, $score, $rate_type ) )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function getHotelAdressReview($objects)
    {
        $locationTextSeo        = '';
        $locationStructureddata = '';
        $locationText           = $objects->getAddress();
        if (intval($objects->getCityId()) > 0) {
            $city_array = $this->get('CitiesServices')->worldcitiespopInfo(intval($objects->getCityId()));
            $city_array = $city_array[0];
            $city_name  = $this->get('app.utils')->htmlEntityDecode($city_array->getName());
            if ($city_name) {
                if ($locationText == '') $locationText           = $city_name;
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $city_name;
                $locationTextSeo        = $city_name;
                $locationTextSeo        = $this->get('app.utils')->getMultiByteSubstr($locationTextSeo, 11, NULL, $this->data['LanguageGet'], false);
            }
            $state_name = '';
            if ($city_array->getCountryCode() == 'US') {
                $state_array = $this->get('CitiesServices')->worldStateInfo($city_array->getCountryCode(), $city_array->getStateCode());
                if ($state_array && sizeof($state_array)) {
                    $state_name = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                    if ($state_name) {
                        if ($locationText == '') $locationText           .= ', '.$state_name;
                        if ($locationStructureddata) $locationStructureddata .= ', ';
                        $locationStructureddata .= $state_name;
                    }
                }
            }
            $country_array = $this->get('CmsCountriesServices')->countryGetInfo($city_array->getCountryCode());
            $country_name  = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
            if ($country_name) {
                if ($locationText == '') $locationText           .= ', '.$country_name;
                if ($locationTextSeo) $locationTextSeo        .= ' ';
                $locationTextSeo        .= $country_array->getIso3();
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $country_name;
            }
        }else {
            if ($locationText == '') $locationText    = $objects->getLocation();
            $locationTextSeo = $objects->getLocation();
            $locationTextSeo = $this->get('app.utils')->getMultiByteSubstr($locationTextSeo, 14, NULL, $this->data['LanguageGet'], false);

            if ($locationStructureddata) $locationStructureddata .= ', ';
            $locationStructureddata .= $objects->getLocation();
        }
        if ($objects->getPhone()) {
            if ($locationText) $locationText .= '<br/>';
            $locationText .= $objects->getPhone();
        }
        if ($objects->getAddress()) {
            $locationStructureddata = $objects->getAddress();
        }
        $this->data['address']   = $locationStructureddata;
        $this->data['telephone'] = $objects->getPhone();
        return [$locationText, $locationTextSeo];
    }

    public function getAirportAdressReview($objects)
    {
        $locationText           = '';
        $locationTextSeo        = '';
        $locationStructureddata = '';
        if ($objects->getCityId() != '0') {
            $city_array = $this->get('CitiesServices')->worldcitiespopInfo(intval($objects->getCityId()));
            $city_array = $city_array[0];
            $city_name  = $this->get('app.utils')->htmlEntityDecode($city_array->getName());
            if ($city_name) {
                if ($locationText) $locationText           .= '<br/>';
                $locationText           .= $city_name;
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $city_name;
                $locationTextSeo        .= $city_name;
                $locationTextSeo        = $this->get('app.utils')->getMultiByteSubstr($locationTextSeo, 11, NULL, $this->data['LanguageGet'], false);
            }
            $state_name  = '';
            $state_array = $this->get('CitiesServices')->worldStateInfo($city_array->getCountryCode(), $city_array->getStateCode());
            if ($state_array && sizeof($state_array)) {
                $state_name = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                if ($state_name) {
                    if ($city_name == '') $locationText           .= '<br/>';
                    $locationText           .= ', '.$state_name;
                    if ($locationStructureddata) $locationStructureddata .= ', ';
                    $locationStructureddata .= $state_name;
                }
            }
            $country_array = $this->get('CmsCountriesServices')->countryGetInfo($city_array->getCountryCode());
            $country_name  = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
            if ($country_name) {
                if ($city_name == '' && $state_name == '') $locationText           .= '<br/>';
                $locationText           .= ', '.$country_name;
                $locationTextSeo        .= ' '.$country_array->getIso3();
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $country_name;
            }
        }
        if ($locationText == '') {
            $locationStructureddata = $locationTextSeo        = $locationText           = $objects->getCity();
            $locationTextSeo        = $this->get('app.utils')->getMultiByteSubstr($locationTextSeo, 11, NULL, $this->data['LanguageGet'], false);
        }
        if ($objects->getTelephone()) {
            if ($locationText) $locationText .= '<br/>';
            $locationText .= $objects->getTelephone();
        }
        $this->data['address']   = $locationStructureddata;
        $this->data['telephone'] = $objects->getTelephone();
        $this->data['iataCode']  = $objects->getAirportCode();
        return [$locationText, $locationTextSeo];
    }

    public function getPoiAdressReview($objects)
    {
        $locationText           = '';
        $locationTextSeo        = '';
        $locationStructureddata = '';
        $cityTextSeo            = '';
        if (intval($objects->getCityId()) > 0) {
            $city_array = $this->get('CitiesServices')->worldcitiespopInfo(intval($objects->getCityId()));
            $city_array = $city_array[0];
            $city_name  = $this->get('app.utils')->htmlEntityDecode($city_array->getName());
            if ($city_name) {
                if ($locationText) $locationText           .= '<br/>';
                $locationText           .= $city_name;
                $locationTextSeo        .= $city_name;
                $locationTextSeo        = $this->get('app.utils')->getMultiByteSubstr($locationTextSeo, 11, NULL, $this->data['LanguageGet'], false);
                $locationStructureddata .= $city_name;
                $cityTextSeo            .= $city_name;
            }
            $state_name  = '';
            $state_array = $this->get('CitiesServices')->worldStateInfo($city_array->getCountryCode(), $city_array->getStateCode());
            if ($state_array && sizeof($state_array)) {
                $state_name = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                if ($state_name) {
                    if ($city_name == '') $locationText           .= '<br/>';
                    $locationText           .= ', '.$state_name;
                    if ($locationStructureddata) $locationStructureddata .= ', ';
                    $locationStructureddata .= $state_name;
                }
            }
            $country_array = $this->get('CmsCountriesServices')->countryGetInfo($city_array->getCountryCode());
            $country_name  = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
            if ($country_name) {
                if ($city_name == '' && $state_name == '') $locationText           .= '<br/>';
                $locationText           .= ', '.$country_name;
                if ($locationStructureddata) $locationStructureddata .= ', ';
                $locationStructureddata .= $country_name;
                $locationTextSeo        .= ' '.$country_array->getIso3();
            }
        } else {
            $locationText = $objects->getAddress();
        }
        if ($locationText == '') {
            $locationText = $objects->getAddress();
        } else if ($objects->getAddress()) {
            $locationText .= '<br>'.$objects->getAddress();
        }
        if ($objects->getPhone()) {
            if ($locationText) $locationText .= '<br/>';
            $locationText .= $objects->getPhone();
        }
        if ($objects->getAddress()) {
            $locationStructureddata = $objects->getAddress();
        }
        $this->data['address']   = $locationStructureddata;
        $this->data['telephone'] = $objects->getPhone();
        return [$locationText, $locationTextSeo, $cityTextSeo];
    }

    public function oldHotelReviewAction($srch)
    {
        return $this->redirectToLangRoute('_hotelsReview', array('name' => '', 'srch' => $srch), 301);
    }

    public function oldthingstodoReviewAction($name, $srch)
    {
        return $this->redirectToLangRoute('_ThingstodoReview', array('name' => $name, 'srch' => $srch), 301);
    }

    public function oldhotelsReviewAction($name, $srch)
    {
        return $this->redirectToLangRoute('_hotelsReview', array('name' => $name, 'srch' => $srch), 301);
    }

    public function oldairportReviewAction($name, $srch)
    {
        return $this->redirectToLangRoute('_AirportReview', array('name' => $name, 'srch' => $srch), 301);
    }

    public function getThingstodoRelatedLinks($cityId)
    {
        $todoLink     = '';
        $todoLinkName = '';
        if ($cityId > 0) {
            $todoLinkArr = $this->get('ThingsToDoServices')->getAliasLinkCmsThingstodoCountry($cityId);
            if ($todoLinkArr && $todoLinkArr[0]) {
                $todoLink = $todoLinkArr[0]->getAlias();
            } else {
                $todoLinkArr = $this->get('ThingsToDoServices')->getThingstodoCityAliasLink($cityId);
                if ($todoLinkArr && $todoLinkArr[0]) {
                    $todoLink = $todoLinkArr[0]->getAlias();
                }
            }
            $todoLinkName = str_replace('-', ' ', $todoLink);
        }
        if ($todoLink != '') $todoLink = $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], $todoLink);
        return array($todoLink, $todoLinkName);
    }
}
