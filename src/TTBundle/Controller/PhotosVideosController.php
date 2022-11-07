<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use \TTBundle\Model\ElasticSearchSC;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotosVideosController extends DefaultController {

    public function mediaAction($srch = '', $title = '', $fromalbum = 0, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'media_page';

//        $uploadPath = 'media/videos/uploads/2019/13/';
//        $thumbs = $this->get("TTFileUtils")->globFiles(   $uploadPath, "*". "The-Sunset-of-Abu-Dhabi-Corniche-in-UAE-Video-1418052314342"."*.mp4" );
//        $this->debug($thumbs);
//        $this->get("TTFileUtils")->unlinkFile( $thumbs[1] );
//        $thumbs = $this->get("TTFileUtils")->globFiles(   $uploadPath, "*". "The-Sunset-of-Abu-Dhabi-Corniche-in-UAE-Video-1418052314342"."*.mp4" );
//        $this->debug($thumbs);
//        exit;

        $request   = Request::createFromGlobals();
        $routepath = $this->getRoutePath($request);
        
        if ($request->query->get('id', '') != '')
        {
            $srch = $request->query->get('id', '');
        }

        if ($srch == '')
        {
            $srch     = $title;
            $title    = '';
            $sharr    = explode('/', $srch);
            $titleSEO = '';
            if (sizeof($sharr) > 1)
            {
                $srch     = $sharr[1];
                $titleSEO = $sharr[0];
            }
        }

        $sharr    = explode('/', $srch);
        $titleSEO = '';
        
        if (sizeof($sharr) > 1)
        {
            $srch     = $sharr[0];
            $titleSEO = $sharr[1];
        }

        if ($titleSEO && $title)
        {
            $titleSEO  .= ' ';
        }

        $titleSEO  .= $title;
        $titleSEO  = str_replace("-", " ", $titleSEO);
        $titleSEO  = str_replace("/", " ", $titleSEO);
        $titleSEO  = str_replace("+", " ", $titleSEO);
        $mediaInfo = $this->get('PhotosVideosServices')->mediaFromURLHashed( $srch, $this->data['LanguageGet'] );
        
        if (!$mediaInfo)
        {
            $mediaInfo = $this->get('PhotosVideosServices')->mediaFromURLId( $srch, $this->data['LanguageGet'] );
            if ($mediaInfo)
            {
                return $this->redirect( $this->get("TTMediaUtils")->returnMediaUriHashedFromArray( $mediaInfo, $this->data['LanguageGet']), 301 );
            }
        }

        if (!$mediaInfo)
        {
            return $this->pageNotFoundAction();
        }

        $this->setHreflangLinks( $this->get("TTMediaUtils")->returnMediaUriHashedFromArray( $mediaInfo, $this->data['LanguageGet']), true, true);
        $VPublished = $mediaInfo['v_published'];

        if ( !$this->checkUserPrivacyExtand( $mediaInfo['v_userid'], $this->data['USERID'], $mediaInfo ) )
        {
            return $this->redirectToLangRoute('_welcome', array(), 301);
        }
        
        if ( $this->data['USERID'] != intval($mediaInfo['v_userid']) )
        {
            $is_owner   = 0;
            $is_visible = 1;
        } else {
            $is_owner   = 1;
            $is_visible = -1;
        }

        $userInfo = $mediaInfo;
        
        if ( $userInfo['u_id'] =='' && $VPublished == 1 )
        {
            return $this->pageNotFoundAction();
        }

        $channelid = intval($mediaInfo['v_channelid']);
        
        $originalPP          = '';
        $creator_avatar_link = '';
        $creator_avatar_name = '';
		$creator_avatar_namealt = '';
        $creator_img         = '';
        $channelInfo_disable = 0;

        if ($channelid != 0)
        {
            $channelInfo_disable = 1;
            $channelInfo         = $this->get('ChannelServices')->channelGetInfo( $channelid, $this->data['LanguageGet'] );
            $originalPP          = ($channelInfo['c_logo']) ? $this->photoReturnchannelLogoBig($channelInfo) : '';
            if ( $userInfo['u_id'] == intval($channelInfo['c_ownerId']))
            {
                $creator_avatar_name = $this->get('app.utils')->htmlEntityDecode($channelInfo['c_channelName']);
                $creator_avatar_namealt = $this->get('app.utils')->cleanTitleDataAlt($channelInfo['c_channelName']);
                $creator_avatar_link = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/channel/'.$channelInfo['c_channelUrl'], 'channels');
                if ($channelInfo['c_logo'] == '')
                {
                    $creator_img = $this->get("TTRouteUtils")->generateMediaURL('/media/tubers/small_tuber.jpg');
                } else {
                    $creator_img = $this->get("TTMediaUtils")->createItemThumbs($channelInfo['c_logo'], 'media/channel/'.$channelInfo['c_id'].'/thumb/', 0, 0, 64, 64, 'thumb6464');
                }
            }
        } else {
            $creator_img = $this->get("TTMediaUtils")->createItemThumbs( $userInfo['u_profilePic'], 'media/tubers/', 0, 0, 64, 64, 'thumb6464');
            $originalPP  = $this->get("TTRouteUtils")->generateMediaURL('/media/tubers/'.$this->getOriginalPP( $userInfo['u_profilePic'] ));
            $creator_avatar_name = $this->get('app.utils')->returnUserArrayDisplayName( $userInfo );
            $creator_avatar_namealt = $this->get('app.utils')->cleanTitleDataAlt($creator_avatar_name);
            $creator_avatar_link = $this->userProfileLink( $userInfo, true ).'';
        }

        $title       = $this->get('app.utils')->htmlEntityDecode( $mediaInfo['v_title'] );
        $description = $this->get('app.utils')->htmlEntityDecode( $mediaInfo['v_description'] , 0);
        $image       = '';        
        if ($VPublished == 1)
        {
            $image = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $mediaInfo, '');
        }
        
        if ( $mediaInfo['mlv_title'] != '')
        {
            $title       = $this->get('app.utils')->htmlEntityDecode( $mediaInfo['mlv_title'] );
        }

        if ( $mediaInfo['mlv_description'] != '')
        {
            $description = $this->get('app.utils')->htmlEntityDecode( $mediaInfo['mlv_description'], 0);
        }

        $category_title = '';
        $category_titlealt = '';
        $catstitlelink = '';
        if (intval( $mediaInfo['v_category'] ) > 0) {
            $catsinfo = $this->get('PhotosVideosServices')->categoryGetName( $mediaInfo['v_category'], $this->data['LanguageGet'] );
            if ($catsinfo) {
                $catstitle1 = $category_title  = $this->get('app.utils')->htmlEntityDecode( $catsinfo['ac_title'] );
                $category_titlealt = $this->get('app.utils')->cleanTitleDataAlt( $catsinfo['ac_title'] );

                if( $catsinfo['mlac_title'] != '' ){
                    $category_title = $this->get('app.utils')->htmlEntityDecode( $catsinfo['mlac_title'] );
                    $category_titlealt = $this->get('app.utils')->cleanTitleDataAlt( $catsinfo['mlac_title'] );
                }
                
                $catstitlelink  = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$this->get('app.utils')->cleanTitleData($catstitle1), 'media');
            }
        }
        $this->data['category_title']      = $category_title;
        $this->data['category_titlealt']   = $category_titlealt;
        $this->data['category_link']       = $catstitlelink;

        $album = false;
        if( $fromalbum == 1 )
        {
            $album = $this->get('PhotosVideosServices')->mediaGetCatalog( $mediaInfo['v_id'] );
            if (!$album && $fromalbum == 1 && !$mediaInfo)
            {
                return $this->pageNotFoundAction();
            }
        }
        
        $seo_bread_crumbs = $this->seoBreadCrumbsMedia( array( 'entity_info' =>$mediaInfo, 'entity_id' => $mediaInfo['v_id'], 'entity_type' => $this->container->getParameter('SOCIAL_ENTITY_MEDIA') ) );
        
        if ($mediaInfo['v_imageVideo'] == "v")
        {
            $fb_img = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $mediaInfo, 'small');
        } else {
            $fb_img = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $mediaInfo, 'med');
        }

        if ($this->data['aliasseo'] == '')
        {
            $action_array = array();
            if ($titleSEO == '')
            {
                $titleSEO     = $title;
            }
            $sttre        = $titleSEO;
            
            if ($fromalbum == 1)
            {
                $sttre .= ' - album';
            }
            $sttre1 = $this->get('app.utils')->getMultiByteSubstr( $sttre, 33, NULL, $this->data['LanguageGet'], false );
            
            $action_array[]         = $sttre1.' '.$srch;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array = array();
            $sttre        = $titleSEO;

            if ($fromalbum == 1)
            {
                $sttre = $this->get('app.utils')->getMultiByteSubstr( $sttre, 45, NULL, $this->data['LanguageGet'], false );
                $sttre .= ' album';
            } else {
                $sttre = $this->get('app.utils')->getMultiByteSubstr( $sttre, 51, NULL, $this->data['LanguageGet'], false );
            }

            $action_array[]               = $sttre.' '.$srch;
            $action_array[]               = $srch;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display       = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $medialist           = array();
        $mediaarray          = array();
        $iarray              = array();
        $iarray['img']       = $fb_img;
        $iarray['title']     = $title;
        $iarray['titlealt']  = $this->get('app.utils')->cleanTitleDataAlt($title);
        $iarray['mediaType'] = $mediaInfo['v_imageVideo'];
        $iarray['img1']      = '';

        if ($iarray['mediaType'] == 'i')
        {
            $iarray['img1'] = $fb_img;
        }
        
        $t_date             = $mediaInfo['v_pdate']->format('Y-m-d H:i');
        $commentsDate       = $this->get('app.utils')->returnSocialTimeFormat($t_date, 1);
        $iarray['time']     = $commentsDate;
        $duration           = $mediaInfo['v_duration'];
        $subtr              = substr($duration, 0, 3);

        if ($subtr == "00:")
        {
            $duration           = substr($duration, 3, strlen($duration));
        }
        $iarray['duration'] = $duration;
        
        $iarray['id']       = $mediaInfo['v_id'];

        $this->data['res_list']    = '';
        $this->data['res_video'] = '';
        $this->data['res_listimg'] = '';

        if ($iarray['mediaType'] == 'v')
        {
            $rpath     = $mediaInfo['v_relativepath'];
            $name      = $mediaInfo['v_name'];
            $videoResolutionArray = array( 'full_path'=>$mediaInfo['v_fullpath'], 'relative_path'=>$rpath, 'name'=>$name );
            $res = $this->get('PhotosVideosServices')->getVideoResolutions( $videoResolutionArray, '' );
            $this->data['res_list'] = $iarray['res_list'] = implode('/*/', $res);
            $this->data['res_video'] = $iarray['res_video'] = ($res)?$res[0]:'';
            $this->data['res_listimg'] = $iarray['res_listimg'] = $fb_img;
        }
        
        $iarray['link']               = $this->get("TTMediaUtils")->returnMediaUriHashedFromArray( $mediaInfo, $this->data['LanguageGet'] );
        $iarray['description']        = $description;
        $mediaarray[]                 = $iarray;

        if ($fromalbum == 1 & $album)
        {
            $limit        = null;
            $srch_options = array
            (
                'limit' => $limit,
                'page' => 0,
                'user_id' => $album['a_userId'],
                'channel_id' => -1,
                'catalog_id' => $album['a_id'],
                'orderby' => 'id',
                'order' => 'd',
                'from_mobile' => '0',
                'escape_id' => $iarray['id'],
                'lang' => $this->data['LanguageGet']
            );
            
            $current_media_array = $mediaarray[0];
            $mediaarray = $this->get('ApiPhotosVideosServices')->photosVideosSearchQuery( $srch_options )['media'];
            array_unshift($mediaarray, $current_media_array);
            
        } else {
            $url_source = 'mediaAction - getPhotosVideosSearchData - URL: '.$routepath;
            $srch_options = array
            (
                'limit' => 12,
                'cityId' => $mediaInfo['v_cityid'],
                'type' => 'a',
                'imageTitle' => $mediaInfo['v_title'],
                'userId' => $mediaInfo['v_userid'],
                'escape_id' => $mediaInfo['v_id'],
                'return_resolution' => true,
                'url_source' => $url_source,
                'lang' => $this->data['LanguageGet'],
                'oldQuery' => 1
            );
            $queryStringResult = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );
            if( $queryStringResult[1] == 0 ){
                $srch_options['userId'] = NULL;
                $queryStringResult = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );
            }
            $current_media_array = $mediaarray[0];
            $mediaarray = $queryStringResult[0];
            array_unshift($mediaarray, $current_media_array);
        }
        
        $uricurpage                        = $this->get('TTRouteUtils')->UriCurrentPageURL();
        $uricurserver                      = $this->get('TTRouteUtils')->currentServerURL();

        $this->data['uricurpage']          = $uricurpage;
        $this->data['channelInfo_disable'] = $channelInfo_disable;
        $this->data['mediaarray']          = $mediaarray;
        $this->data['uricurpage']          = $uricurpage;
        $this->data['fbimg']               = $fb_img;
        $this->data['is_owner']            = $is_owner;
        $this->data['channelid']           = $channelid;
        $this->data['datePublished']       = $mediaInfo['v_pdate'];
        $this->data['mediaid']             = $mediaInfo['v_id'];
        $this->data['mediaType']           = $mediaInfo['v_imageVideo'];
        $this->data['seo_bread_crumbs']    = $seo_bread_crumbs;
        $this->data['originalPP']          = $originalPP;
        $this->data['entity_type']         = $this->container->getParameter('SOCIAL_ENTITY_MEDIA');
        $this->data['creator_img']         = $creator_img;
        $this->data['creator_avatar_name'] = $creator_avatar_name;
        $this->data['creator_avatar_namealt'] = $creator_avatar_namealt;
        $this->data['creator_avatar_link'] = $creator_avatar_link;
        $this->data['pageTitle']           = $title;
        $this->data['description']         = $description;
        $this->data['fbdesc']              = $description;
        $this->data['image']               = $image;
        $this->data['VPublished']          = $VPublished;

        return $this->render('photos_videos/media.twig', $this->data);
    }

    public function photoVideoSharingAction(Request $request, $type = 'a', $seotitle, $seodescription, $seokeywords)
    {
        $routepath = $this->getRoutePath($request);
        $len   = 10;  // total number of numbers
        $min   = 1; // minimum
        $max   = 18; // maximum
        $origin_type = $type;
        if ($type == 'i') {
            $this->setHreflangLinks($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/best-images", 'media'), true, true);
        } else if ($type == 'v') {
            $this->setHreflangLinks($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/best-videos", 'media'), true, true);
        } else if ($type == 'h') {
            $type = 'a';
            $len   = 5;
            $this->setHreflangLinks($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/", 'media'), true, true);
        } else {
            $this->setHreflangLinks($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/photo-video-sharing", 'media'), true, true);
        }

        $range = array();
        foreach (range(0, $len - 1) as $i) {
            while (in_array($num     = mt_rand($min, $max), $range));
            $range[] = $num;
        }

        $categories = $this->get('PhotosVideosServices')->categoryGetHash( array( 'orderby' => 'itemOrder', 'showHome' => 1, 'lang' => $this->data['LanguageGet'] ) );
        $catArray   = array();
        foreach ($categories as $cat_id => $ctitem) {
            $ctitem[]   = $cat_id;
            $catArray[] = $ctitem;
        }
        $photoListing = array();
        $url_source= 'photoVideoSharingAction - getPhotosVideosSearchData - URL: '.$routepath;

        foreach ($range as $range_id) {
            $media_arry  = array();
            $cat_Item    = $catArray[$range_id];
            $name        = $cat_Item[0];
            $url_replace = '/'.$this->get('app.utils')->cleanTitleData($cat_Item[1]);
            
            $srch_options = array
            (
                'limit' => 4,
                'catergoryId' => $cat_Item[3],
                'description_length' => 110,
                'type' => $type,
                'url_source' => $url_source,
                'lang' => $this->data['LanguageGet'],
                'oldQuery' => 1
            );
            $queryStringResult = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );            
            $media_arry = $queryStringResult[0]; 
            
            $newarray = array();
            if (sizeof($media_arry)) {
                $newarray['name']    = $this->get('app.utils')->htmlEntityDecode($name);
                $newarray['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($name);
                $newarray['viewAll']     = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],$url_replace, 'media');
                $newarray['subItem']    = $media_arry;
                $photoListing[]      = $newarray;
            }
        }
        $this->data['mainEntityArray'] = $photoListing;
        if ($this->data['aliasseo'] == '' || $origin_type == 'h' ) {
             $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
             $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
         }
        return $this->render('photos_videos/photo-video-sharing.twig', $this->data);
    }

    public function photosVideosRedirectAction($qr, $catName, $t, $page, $c, $orderby = '', $first_character = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_photos_videos_media', array('qr' => $qr, 'catName' => $catName, 't' => $t, 'page' => $page,
                 'c' => $c), 301);
    }

    public function photosVideosAction($qr, $catName, $t, $page, $c, $orderby = '', $first_character = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'photos_videos';

        $request   = Request::createFromGlobals();
        $routepath = $this->getRoutePath($request);

        $page = intval($page);
        if ($page < 1)
        {
            $page = 1;
        }
        $dontuse     = 0;
        $catNameInitOld = $catName;
        $qrInitOld      = $qr;
        $realname = $sort_string    = '';
        $maxPage        = $this->container->getParameter('MAX_RECORD');
        $c              = intval($c);
        $request        = $this->get('request');
        $qr             = $this->get('app.utils')->cleanTitleData($qr);
        $qr             = str_replace('-', ' ', $qr);
        $qr             = str_replace('?', '', $qr);
        $qr             = str_replace('+', ' ', $qr);
        $qr             = str_replace("\\", "\\\\", $qr);
        $qr             = str_replace("[", "", $qr);
        $qr             = str_replace("]", "", $qr);
        $qr             = str_replace('"', '', $qr);
        $orderby        = strtolower($orderby);

        if ($page >= $maxPage)
        {
            $page = $maxPage;
        }

        if ($t != 'v' && $t != 'i') $t = 'a';

        if ($c > 0)
        {
            $categoryArray = $this->get('PhotosVideosServices')->categoryGetHash( array( 'id' => $c, 'lang' => $this->data['LanguageGet'] ) );
            
            if ( $page == 1 && $t == 'a' && $qr == '' && $categoryArray[$c][2]=='')
            {
                return $this->pageNotFoundAction();
            }

            if ($categoryArray && isset($categoryArray[$c]))
            {
                $catName = $categoryArray[$c][0];
            } else {
                $catName = '';
            }
        }

        $url_source ='photosVideosAction - getPhotosVideosSearchData - URL: '.$routepath;
        if (!$orderby) {
            $orderby = 'Default';
        }
        $maxPage = $this->container->getParameter('MAX_RECORD');
        if ($page <= 1) {
            $from = 0;
        } elseif ($page > 1) {
            $from = ($page - 1) * 48;
        } elseif ($page >= $maxPage) {
            $from = ($maxPage - 1) * 48;
        }
        if ($orderby == 'Date') {
            $sort = 'pdate';
        } elseif ($orderby == 'Title') {
            $sort = 'title';
        } elseif ($orderby == 'Default' || $orderby == '') {
            $sort = 'stats.nb_views';
        }
        $qr = str_replace(' city', '', $qr);
        if( $qr =='' ) $qr = NULL;
        $srch_options = array
        (
            'limit' => 48,
            'from' =>$from,
            'description_length' => 110,
            'type' => $t,
            'catergoryId' => $c,
            'term' => $qr,
            'aggs' => 'category.id',
            'sortBy' => $sort,
            'url_source' => $url_source,
            'lang' => $this->data['LanguageGet']
        );
        $retDoc = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );
        $media_array = $retDoc[0];
        $media_array_count = $retDoc[1];
        $aggregationss = $retDoc[2];

        if( $c>0 )
        {
            $srch_options['catergoryId'] = 0;
            $retDocAggregations = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );
            $aggregationss = $retDocAggregations[2];
        }        
        
        
        $this->setHreflangLinks($this->get("TTMediaUtils")->returnSearchMediaCanonicalLink($this->data['LanguageGet'], $qrInitOld, $catName, $c), true, true);
        
        $seoNM = $qr;
        if ($catName) {
            if ($seoNM) $seoNM .= ' - ';
            $seoNM .= $catName;
        }else if ($catNameInitOld) {
            if ($seoNM) $seoNM .= ' - ';
            $seoNM .= $catNameInitOld;
        }
        $seoNM0     = $seoNMTitle = $seoNM;

        $type_txt = '';
        $type_txt_crop = 31;
        if ($t == 'v')
        {
            $type_txt = $this->translator->trans('videos');

        } else if ($t == 'i')
        {
            $type_txt = $this->translator->trans('photos');

        } else {
            $type_txt = $this->translator->trans('photos videos');
            $type_txt_crop = 24;

        }

        $seoNM0 = $type_txt.' '.$seoNM;
        $seoNM0 = $this->get('app.utils')->getMultiByteSubstr( $seoNM0, 22, NULL, $this->data['LanguageGet'], false );
        $seoNMTitle = $this->get('app.utils')->getMultiByteSubstr( $seoNMTitle, $type_txt_crop, NULL, $this->data['LanguageGet'], false );
        
        $seoNMTitle = $type_txt.' '.$seoNMTitle;
        
        $this->data['seoNM'] = $seoNM;

        if ($this->data['aliasseo'] == '')
        {
            $action_array           = array();
            $action_array[]         = $seoNMTitle.' '.$this->translator->trans('part').' '.$page;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $PHOTOS_VIDEOS_DESC_ARRAY = array( $this->translator->trans('%1$s, enjoy the best collection of travel pictures and best %2$s of the web, visit us on tourist tube'),
$this->translator->trans('%1$s, videos, photos and all the %2$s related to touristic places on tourist tube come and visit us now'),
$this->translator->trans('%1$s, want to know how Paris look like don\'t miss visiting tourist tube now and see all %2$s'),
$this->translator->trans('%1$s, photos of %2$s videos and all the information you need to know about Paris on tourist tube web'),
$this->translator->trans('%1$s, find all the media you need to see about %2$s on tourist tube web and book your next flight now'),
$this->translator->trans('%1$s, wondering about your next destination? Visit tourist tube, find out what you need to know about %2$s'),
$this->translator->trans('%1$s, visit our web sites and get to see all the %2$s and other touristic places on tourist tube'),
$this->translator->trans('%1$s, visit tourist tube share your touristic photos videos, find out everything you need to know about %2$s'),
$this->translator->trans('%1$s, searching for %2$s? Want to know how the touristic places look like on tourist tube'),
$this->translator->trans('%1$s, want to find out about your next destination, Tourist tube provides you with information about %2$s'),
$this->translator->trans('%1$s, the best %2$s on the net only on tourist tube web come visit us on tourist tube'),
$this->translator->trans('%1$s, excited about your next destination, next trip? Visit us on tourist tube and plan your trip about %2$s'),
$this->translator->trans('%1$s, We provide you with all the information you need to know about %2$s on tourist tube'),
$this->translator->trans('%1$s, want to discover %2$s and all touristic places, Visit tourist tube web, check latest photos and videos'),
$this->translator->trans('%1$s, discover the latest %2$s on the net. Tourist tube provides with all the information you need to know'),
$this->translator->trans('%1$s, Want to visit %2$s Check tourist tube, see everything you need to know about any location in the world'),
$this->translator->trans('%1$s, best touristic places, destination, %2$s on tourist tube platform. plan your trip and see the world'),
$this->translator->trans('%1$s, come visit tourist tube platform. Best destinations and best attractions on tourist tube about %2$s'),
$this->translator->trans('%1$s, Planning a trip Tourist tube would do that for you. Find out everything you need to know about %2$s'),
$this->translator->trans('%1$s, ever wondered how does %2$s look like Visit tourist tube platform to check our latest pictures, photos') );
            $arrayindex               = fmod(($page - 1), 20);
            $action_array             = array();
            if ($seoNM0) {
                $action_array[] = $seoNM0.' part '.$page;
                $seoNM = $this->get('app.utils')->getMultiByteSubstr( $seoNM, 30, NULL, $this->data['LanguageGet'], false );
                
                $action_array[] = $seoNM;
            } else {
                $action_array[] = 'part '.$page;
                $action_array[] = 'part '.$page;
            }
            $action_text_display          = vsprintf( $PHOTOS_VIDEOS_DESC_ARRAY[$arrayindex], $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_array[]            = $seoNM;
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        $this->data['noresultfound'] = 0;

        if ( $media_array_count <= 0 )
        {
            $this->data['noresultfound'] = 1;

            $srch_options = array
            (
                'aggs' => 'location.city.id',
                'term' => $qr
            );
            $url_source = 'photosVideosAction - getPoiNearLocation - URL: '.$routepath;
            $poiNearLocation = $this->get('ElasticServices')->getPoiNearLocation( $srch_options, $url_source );
            $retDocpoi['aggregations'] = $poiNearLocation[2];

            if(isset($retDocpoi['aggregations']['location.city.id']['buckets']))
            {
                $aggregation = $retDocpoi['aggregations']['location.city.id']['buckets'];

                if (sizeof($aggregation) >= 0)
                {
                    foreach ($aggregation as $aggs)
                    {
                        $city_id      = $aggs['key'];
                        $cityInfo     = $this->get('CitiesServices')->worldcitiespopInfo($city_id);
                        $cityName     = $cityInfo[0]->getName();
                        $countrycode  = $cityInfo[0]->getCountryCode();
                        $statecode    = $cityInfo[0]->getStateCode();
                        $query_string = '';

                        if (!$orderby)
                        {
                            $orderby = 'Default';
                        }

                        if ($page <= 1) {
                            $from = 0;
                        } elseif ($page > 1) {
                            $from = ($page - 1) * 10;
                        } elseif ($page >= $maxPage) {
                            $from = ($maxPage - 1) * 10;
                        }

                        if ($orderby == 'Date') {
                            $sort_string = 'pdate';
                        } elseif ($orderby == 'Title') {
                            $sort_string = 'title';
                        } elseif ($orderby == 'Default' || $orderby == '') {
                            $sort_string = 'stats.nb_views';
                        }

                        $url_source = 'searchLocalityAction - getPhotosVideosSearchData - URL: '.$routepath;
                        $srch_options = array
                        (
                            'limit' => 1,
                            'from' => $from,
                            'description_length' => 110,
                            'type' => $t,
                            'sortBy' => $sort_string,
                            'countryCode' => $countrycode,
                            'category' => $c,
                            'url_source' => $url_source,
                            'lang' => $this->data['LanguageGet'],
                            'oldQuery' => 1
                        );
                        $queryStringResult = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );
                        $count_result = $queryStringResult[1];

                        $dontuse     = 1;
                        $count3      = $count_result - 20;
                        if ($count3 < 0) {
                            $count3 = 0;
                        } elseif ($count3 >= 10000) {
                            $count3 = 9999;
                        }

                        $from = rand(0, $count3);

                        $srch_options['limit'] = 48;
                        $srch_options['from']  = $from;
                        $srch_options['type']  = 'a';
                        
                        $queryStringResult = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );
                        $media_array = $queryStringResult[0];
                        $media_array_count = $queryStringResult[1];
                        $retDoc['aggregations'] = $queryStringResult[2];

                        if(isset($retDoc['aggregations']['category.id']['buckets']))
                        {
                            $aggregationss = $retDoc['aggregations']['category.id']['buckets'];
                        }else{
                            $aggregationss = array();
                        }

                        if ( $media_array_count <= 0)
                        {
                            continue;
                        } else {
                            break;
                        }
                    }
                }
            }
        }

        if ( $media_array_count == 0)
        {
            $fromlimit = round(rand(0, 700));
            $url_source='photosVideosAction - getCityMediaElastic - URL: '.$routepath;
            
            $srch_options = array
            (
                'limit' => 24,
                'page' => $fromlimit,
                'description_length' => 110,
                'type' => $t,
                'url_source' => $url_source,
                'lang' => $this->data['LanguageGet'],
                'oldQuery' => 1
            );
            $retDoc = $this->get('ElasticServices')->getPhotosVideosSearchData( $srch_options );
            $media_array = $retDoc[0];
        }
        shuffle($media_array);
        $this->data['media_array'] = $media_array;

        $arrayOfId = array();
        if (sizeof($aggregationss))
        {
            if( isset($aggregationss['category.id']['buckets']) )
            {
                foreach ($aggregationss['category.id']['buckets'] as $agges)
                { 
                    if( isset($agges['key']) )
                    {
                        $arrayOfId[] = $agges['key'];
                    }
                }
            } else {
                foreach ($aggregationss as $agges)
                {
                    if( isset($agges['key']) )
                    {
                        $arrayOfId[] = $agges['key'];
                    }
                }
            }
            $stringOfId = implode(",", $arrayOfId);
            
        } else {
            $stringOfId = '';
            $qr         = '';
            $this->setHreflangLinks($this->get("TTMediaUtils")->returnSearchMediaCanonicalLink($this->data['LanguageGet'], '', '', 0), true, true);
        }
        $categories = $this->get('PhotosVideosServices')->categoryGetHash( array( 'orderby' => 'itemOrder', 'in' => $stringOfId, 'lang' => $this->data['LanguageGet'] ) );

        $category_list = array();
        $i=0;
        foreach ($categories as $cat_id => $namearray)
        {
            $name = $this->get('app.utils')->htmlEntityDecode($namearray[0]);
            $namealt = $this->get('app.utils')->cleanTitleDataAlt($namearray[0]);
            $url_replace = $namearray[2];

            if ( $qr && $this->data['noresultfound']==0 && $i>0 )
            {
                if ($cat_id == 0)
                {
                    $link = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $qr, '', $t, 1, $cat_id);
                } else {
                    $link = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $qr, $url_replace, $t, 1, $cat_id);
                }
                
            } else {
                $link = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$url_replace, 'media');
            }
            $category_list[] = array('id' => $cat_id, 'title' => $name, 'titlealt' => $namealt, 'link' => $link);
            $i++;
        }
        $this->data['category_list'] = $category_list;

        $number_of_pages = $media_array_count / 48;
        $number_of_pages = ceil($number_of_pages);

        if ($number_of_pages <= 0) {
            $number_of_pages = 1;
        }
        $search_paging_output = '';
        if ($number_of_pages > 1 && $dontuse == 0)
        {
            $page_num = 2;
            $search_paging_output = '<ul><li><a class="prev_pg" title="'.$this->translator->trans('previous page').'" rel="nofollow"><span></span></a></li>';
            $xStart = $page - $page_num;

            if ($xStart < 1)
            {
                $xStart = 1;
            }
            $xEnd = $xStart + $page_num*2;

            if ($xEnd > $number_of_pages)
            {
                $xEnd = $number_of_pages;
            }

            for ($x = $xStart; $x <= $xEnd; $x++)
            {
                if ($x <= $maxPage)
                {
                    if ($x == $page)
                    {
                            $search_paging_output .= '<li class="active"><a title="'.$qr.' '.$catName.' S'.$t.' '.$x.' '.$c.'" href="'.$this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $qr, $catName, $t, $x, $c).'">'.$x.'</a> <span>.</span></li>';

                    } else {

                            $search_paging_output .= '<li><a title="'.$qr.' '.$catName.' S'.$t.' '.$x.' '.$c.'" href="'.$this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $qr, $catName, $t, $x, $c).'">'.$x.'</a>'.($x
                            < $xEnd ? '<span>.</span>' : '').'</li>';

                    }
                }
            }
            $search_paging_output .= '<li><a class="next_pg" title="'.$this->translator->trans('next page').'" rel="nofollow"></a></li></ul>';
        }

//        $photos_link = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $qr, $catName, 'i', 1, $c);
//        $videos_link = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $qr, $catName, 'v', 1, $c);
//        $this->data['photos_link']      = $photos_link;
//        $this->data['videos_link']      = $videos_link;
  
        $this->data['categoryid'] = $c;
        $this->data['realname'] = $seoNM;
        $this->data['search_paging_output'] = $search_paging_output;

        return $this->render('photos_videos/photos.videos.twig', $this->data);
    }

    public function albumRedirectAction($srch, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_album_media', array('srch' => $srch), 301);
    }

    public function albumAction($srch, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'album';
        $urlexplode = explode('/', $srch);
        $srch       = $urlexplode[0];
        
        $limit = 21;
        $srch_options = array (
            'limit' => $limit,
            'lang' => $this->data['LanguageGet'],
            'url' => $srch
        );
        $albumContentlist = $this->get('PhotosVideosServices')->albumContentFromURL( $srch_options );
        
        if (!$albumContentlist) {
            return $this->pageNotFoundAction();
        }

        $AlbumInfo = $albumContentlist[0];
        $this->setHreflangLinks($this->get("TTMediaUtils")->ReturnAlbumUriFromArray( $AlbumInfo, $this->data['LanguageGet'] ), true, true);

        $this->data['realname'] = $this->get('app.utils')->htmlEntityDecode($AlbumInfo['a_catalogName']);
        $this->data['album_id'] = $AlbumInfo['a_id'];
        
        $this->data['noresultfound'] = 0;
        if( sizeof( $albumContentlist ) <=1 && $AlbumInfo['v_id'] =='' )
        {
            $this->data['noresultfound'] = 1;
        }

        if ($this->data['aliasseo'] == '') {
            $action_array = array();
            $albumsnames  = $this->data['realname'];
            $albumsnames = $this->get('app.utils')->getMultiByteSubstr( $albumsnames, 42, NULL, $this->data['LanguageGet'], false );

            $action_array[]         = $albumsnames.' '.$AlbumInfo['a_id'];
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_array[]               = $this->data['realname'];
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display       = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $channel_id = intval( $AlbumInfo['v_channelid'] );
        $user_id = intval( $AlbumInfo['a_userId'] );

        $is_owner = 0;
        if( $user_id==$this->data['USERID'] ) $is_owner = 1;
        $this->data['is_owner'] = $is_owner;


        $srch_options      = array(
            'user_id' => $user_id,
            'channel_id' => $channel_id,
            'catalog_id' => $this->data['album_id'],
            'n_results' => true
        );
        $medialistCount    = $this->get('PhotosVideosServices')->mediaSearch($srch_options);
        $this->data['page_count'] = ceil($medialistCount / $limit) - 1;

        $srch_options = array (
            'limit' => null,
            'channel_id' => $channel_id,
            'user_id' => $user_id
        );
        $Albumlist = $this->get('PhotosVideosServices')->getAlbumSearch( $srch_options );

        $category_list = array();
        foreach ($Albumlist as $v_item)
        {
            $varr             = array();
            $varr['id']       = $v_item['a_id'];
            $varr['link']     = $this->get("TTMediaUtils")->ReturnAlbumUriFromArray( $v_item, $this->data['LanguageGet'] );
            $varr['title']    = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
            $varr['titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($v_item['a_catalogName']);
            $category_list[]    = $varr;
        }
        $this->data['category_list'] = $category_list;

        $media_array = array();
        foreach ($albumContentlist as $media)
        {
            $varr             = array();
            $title = $media['v_title'];
            if( $media['mlv_title']!='' ) $title = $media['mlv_title'];
            $description = $media['v_description'];
            if( $media['mlv_description']!='' ) $description = $media['mlv_description'];
            $varr['title']       = $this->get('app.utils')->htmlEntityDecode($title);
            $varr['titlealt']    = $this->get('app.utils')->cleanTitleDataAlt($title);
            $varr['description'] = $this->get('app.utils')->htmlEntityDecode($description);
            $varr['description'] = $this->get('app.utils')->getMultiByteSubstr( $varr['description'], 110, NULL, $this->data['LanguageGet'] );

            $realpath     = $media['v_relativepath'];
            $relativepath = str_replace('/', '', $realpath);
            $fullPath     = $media['v_fullpath'];
            $itemsname    = $media['v_name'];

            if ($media['v_imageVideo'] == "v")
            {
                $varr['img']       = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($media, '');
            } else {
                $varr['img'] = $this->get("TTMediaUtils")->createItemThumbs($itemsname, $fullPath, 0, 0, '284', '162', 'MediaSearch284162', $fullPath, $fullPath);
            }

            if (!$varr['img'])
            {
                $varr['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($media, '');
            }

            $varr['id']      = $media['v_id'];
            $varr['type']    = $media['v_imageVideo'];
            $varr['link']    = $this->get("TTMediaUtils")->returnMediaUriHashedFromArray($media, $this->data['LanguageGet']);
            $media_array[]   = $varr;
        }
        $this->data['media_array'] = $media_array;
        $this->data['channel_id'] = $channel_id;
        return $this->render('photos_videos/album.twig', $this->data);
    }

    public function addAlbumAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $channel_id = intval($request->query->get('channel_id', 0));

        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if( $channel_id>0 )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

            if (!$channelInfo) {
                return $this->redirectToLangRoute('_channels', array(), 301);
            }

            if ( $channelInfo['c_ownerId'] != $user_id ) {
                return $this->redirectToLangRoute('_channel', array('srch' => $channelInfo['c_channelUrl']), 301);
            }
        }
        
        $countries_list = $this->get('CmsCountriesServices')->getCountries();
        $this->data['countries_list'] = $countries_list;

        $categories = $this->get('PhotosVideosServices')->categoryGetHash( array( 'orderby' => 'itemOrder', 'hide_all' => true, 'lang' => $this->data['LanguageGet'] ) );
        $catArray   = array();
        foreach ($categories as $cat_id => $ctitem) {
            $item = array();
            $item['id']   = $cat_id;
            $item['name']   = $ctitem[0];
            $catArray[] = $item;
        }
        $this->data['cat_array'] = $catArray;

        return $this->render('photos_videos/add_album.twig', $this->data);
    }

    public function updateAlbumAddAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        if ($user_id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $name = $request->request->get('name', '');
        $category = intval($request->request->get('category', 0));
        $country = $request->request->get('country', '');
        $city = $request->request->get('city', '');
        $city_id = intval($request->request->get('city_id', 0));
        $channel_id = intval($request->request->get('channel_id', 0));
        $description = $request->request->get('description', '');

        if( $channel_id>0 )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

            if (!$channelInfo) {
                $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if ($channelInfo['c_ownerId'] != $user_id) {
                $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        if ($country == '') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a country.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($city_id == 0) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a city.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($category == 0) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a category.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($name == '') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please insert the title.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( strlen($name) >100 ) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Title must be maximum 100 characters long.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $album_id = $this->get('PhotosVideosServices')->addUserAlbum( $user_id, $name, $city_id, $city, $country, $description, $category, $channel_id );

        if ( $album_id ) {
            $Result['status'] = 'ok';
            $Result['msg'] = $this->translator->trans('Album added successfully.');
            $Result['id'] = $album_id;
            $Result['name'] = $this->get('app.utils')->htmlEntityDecode( $name );
        } else {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Error adding album, please try again');
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function editMediaAlbumAction(Request $request) {
        $user_id = $this->data['USERID'];
        $id = intval($request->query->get('id', 0));
        $data_type = $request->query->get('data_type', 'i');
        $channel_id = intval($request->query->get('channel_id', 0));
        $album_id = intval($request->query->get('album_id', 0));
        $temp_id = 0;

        if ($this->data['isUserLoggedIn'] == 0 || $id == 0) {
            return new Response($this->translator->trans('You are not the owner'));
        }

        $user_owner_id = 0;
        $msg = '';
        if ($data_type != 'albums') {
            $mediaInfo = $this->get('PhotosVideosServices')->getMediaInfo($id);
            if ($mediaInfo) {
                $user_owner_id = $mediaInfo['v_userid'];
                $msg = $this->translator->trans('You are not the owner of this media.');
            }
        } else {
            $album_info = $this->get('PhotosVideosServices')->getAlbumInfo($id);
            if ($album_info) {
                $user_owner_id = $album_info['a_userId'];
                $msg = $this->translator->trans('You are not the owner of this album.');
            }
        }

        if ($user_id != $user_owner_id) {
            return new Response($msg);
        }

        if ($channel_id > 0) {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

            if (!$channelInfo) {
                return $this->redirectToLangRoute('_channels', array(), 301);
            }

            if ($channelInfo['c_ownerId'] != $user_id) {
                return $this->redirectToLangRoute('_channel', array('srch' => $channelInfo['c_channelUrl']), 301);
            }
        }

        if ($album_id > 0) {
            $album_info = $this->get('PhotosVideosServices')->getAlbumInfo($album_id);

            if (!$album_info) {
                return $this->redirectToLangRoute('_welcome', array(), 301);
            }

            if ($album_info['a_userId'] != $user_id) {
                return $this->redirectToLangRoute('_welcome', array(), 301);
            }
        }

        $countries_list = $this->get('CmsCountriesServices')->getCountries();
        $this->data['countries_list'] = $countries_list;

        $categories = $this->get('PhotosVideosServices')->categoryGetHash(array('orderby' => 'itemOrder', 'hide_all' => true, 'lang' => $this->data['LanguageGet']));
        $catArray = array();
        foreach ($categories as $cat_id => $ctitem) {
            $item = array();
            $item['id'] = $cat_id;
            $item['name'] = $ctitem[0];
            $catArray[] = $item;
        }
        $this->data['cat_array'] = $catArray;
        $album_array = array();
        $usedValues = array();

        if ($data_type != 'albums') {

            $srch_options = array(
                'limit' => null,
                'is_owner' => 1,
                'show_empty' => 1,
                'orderby' => 'catalogName',
                'order' => 'a',
                'channel_id' => $channel_id,
                'user_id' => $user_id
            );
            $albumlist = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);
            foreach ($albumlist as $v_item) {
                $varr = array();
                $varr['id'] = $v_item['a_id'];
                $varr['name'] = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
                $album_array[] = $varr;
            }
            $usedValues['id'] = $id;
            $usedValues['type'] = $data_type;
            $usedValues['country'] = $mediaInfo['v_country'];
            $usedValues['cityId'] = $mediaInfo['v_cityid'];
            $usedValues['cityName'] = $mediaInfo['v_cityname'];
            $usedValues['category'] = $mediaInfo['v_category'];
            $usedValues['album'] = intval($mediaInfo['va_catalogId']);
            $usedValues['title'] = $mediaInfo['v_title'];
            $usedValues['description'] = $mediaInfo['v_description'];
        } else {
            $usedValues['id'] = $id;
            $usedValues['type'] = $data_type;
            $usedValues['country'] = $album_info['a_country'];
            $usedValues['cityId'] = $album_info['a_cityid'];
            $usedValues['cityName'] = $album_info['a_cityname'];
            $usedValues['category'] = $album_info['a_category'];
            $usedValues['title'] = $album_info['a_catalogName'];
            $usedValues['description'] = $album_info['a_description'];
        }
        $this->data['usedValues'] = $usedValues;
        $this->data['album_array'] = $album_array;
        $this->data['temp_id'] = $temp_id;
        return $this->render('photos_videos/add_media.twig', $this->data);
    }

    public function addMediaAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $channel_id = intval($request->query->get('channel_id', 0));
        $album_id = intval($request->query->get('album_id', 0));
        $temp_id = intval($request->query->get('temp_id', 0));

        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if( $channel_id>0 )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

            if (!$channelInfo) {
                return $this->redirectToLangRoute('_channels', array(), 301);
            }

            if ( $channelInfo['c_ownerId'] != $user_id ) {
                return $this->redirectToLangRoute('_channel', array('srch' => $channelInfo['c_channelUrl']), 301);
            }
        }

        if( $album_id>0 )
        {
            $album_info = $this->get('PhotosVideosServices')->getAlbumInfo( $album_id );

            if ( !$album_info ) {
                return $this->redirectToLangRoute('_welcome', array(), 301);
            }

            if ( $album_info['a_userId'] != $user_id ) {
                return $this->redirectToLangRoute('_welcome', array(), 301);
            }
        }

        $countries_list = $this->get('CmsCountriesServices')->getCountries();
        $this->data['countries_list'] = $countries_list;

        $categories = $this->get('PhotosVideosServices')->categoryGetHash( array( 'orderby' => 'itemOrder', 'hide_all' => true, 'lang' => $this->data['LanguageGet'] ) );
        $catArray   = array();
        foreach ($categories as $cat_id => $ctitem) {
            $item = array();
            $item['id']   = $cat_id;
            $item['name']   = $ctitem[0];
            $catArray[] = $item;
        }
        $this->data['cat_array'] = $catArray;

        $srch_options = array(
            'limit' => null,
            'is_owner' => 1,
            'show_empty' => 1,
            'orderby' => 'catalogName',
            'order' => 'a',
            'channel_id' => $channel_id,
            'user_id' => $user_id
        );
        $albumlist = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);

        $album_array = array();
        foreach ($albumlist as $v_item) {
            $varr = array();
            $varr['id'] = $v_item['a_id'];
            $varr['name'] = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
            $album_array[] = $varr;
        }
        $this->data['album_array'] = $album_array;
        $this->data['album_id'] = $album_id;
        $this->data['temp_id'] = $temp_id;

        return $this->render('photos_videos/add_media.twig', $this->data);
    }

    public function updateMediaAddAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        if ($user_id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $title = $request->request->get('name', '');
        $category = intval($request->request->get('category', 0));
        $country = $request->request->get('country', '');
        $city = $request->request->get('city', '');
        $city_id = intval($request->request->get('city_id', 0));
        $temp_id = intval($request->request->get('temp_id', 0));
        $album_id = intval($request->request->get('album_id', 0));
        $channel_id = intval($request->request->get('channel_id', 0));
        $description = $request->request->get('description', '');

        if ($temp_id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if( $channel_id>0 )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

            if (!$channelInfo) {
                $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if ($channelInfo['c_ownerId'] != $user_id) {
                $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        if( $album_id>0 )
        {
            $album_info = $this->get('PhotosVideosServices')->getAlbumInfo( $album_id );

            if (!$album_info) {
                $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if ( $album_info['a_userId'] != $user_id ) {
                $Result['msg'] = $this->translator->trans('You are not the owner of this album');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        if ($country == '') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a country.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($city_id == 0) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a city.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($category == 0) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a category.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($title == '') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please insert the title.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( strlen($title) >100 ) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Title must be maximum 100 characters long.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $media_id = $this->get('PhotosVideosServices')->addMedia( $user_id, $title, $description, $category, $country, $city_id, $city, $temp_id, $album_id, $channel_id );
        
        if ( $media_id ) {
            $Result['status'] = 'ok';
            $Result['msg'] = '';
        } else {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Error adding media, please try again');
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }
    
    public function mediaEditAction(Request $request) {
        $user_id = $this->data['USERID'];
        $id = intval($request->request->get('id', 0));
        $type = $request->request->get('type', 'i');
        $Result = array();

        if ($user_id == 0 || $id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }
        $user_owner_id = 0;
        $msg = '';
        if ($type != 'albums') {
            $mediaInfo = $this->get('PhotosVideosServices')->getMediaInfo($id);
            if ($mediaInfo) {
                $user_owner_id = $mediaInfo['v_userid'];
                $msg = $this->translator->trans('Couldn\'t save your information, You are not the owner of this media');
            }
        } else {
            $album_info = $this->get('PhotosVideosServices')->getAlbumInfo($id);
            if ($album_info) {
                $user_owner_id = $album_info['a_userId'];
                $msg = $this->translator->trans('Couldn\'t save your information, You are not the owner of this album');
            }
        }

        if ($user_id != $user_owner_id) {
            $Result['msg'] = $msg;
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }
        $title = $request->request->get('name', '');
        $category = intval($request->request->get('category', 0));
        $country = $request->request->get('country', '');
        $city = $request->request->get('city', '');
        $city_id = intval($request->request->get('city_id', 0));
        $album_id = intval($request->request->get('album_id', 0));
        $channel_id = intval($request->request->get('channel_id', 0));
        $description = $request->request->get('description', '');

        if ($channel_id > 0) {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

            if (!$channelInfo) {
                $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if ($channelInfo['c_ownerId'] != $user_id) {
                $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        if ($album_id > 0) {
            $album_info = $this->get('PhotosVideosServices')->getAlbumInfo($album_id);

            if (!$album_info) {
                $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if ($album_info['a_userId'] != $user_id) {
                $Result['msg'] = $this->translator->trans('You are not the owner of this album');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        if ($country == '') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a country.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($city_id == 0) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a city.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($category == 0) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please choose a category.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($title == '') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please insert the title.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if (strlen($title) > 100) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Title must be maximum 100 characters long.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }
        $options = array(
            'id' => $id,
            'user_id' => $user_id,
            'title' => $title,
            'category' => $category,
            'country' => $country,
            'city' => $city,
            'city_id' => $city_id,
            'channel_id' => $channel_id,
            'description' => $description
        );
        if ($type != 'albums') {
            $options['album_id'] = $album_id;
            $object_edit = $this->get('PhotosVideosServices')->mediaEdit($options);
        } else {
            $object_edit = $this->get('PhotosVideosServices')->albumEdit($options);
        }
        if ($object_edit) {
            $Result['status'] = 'ok';
            $Result['msg'] = '';
        } else {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function mediaDeleteAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();
        $id = intval($request->request->get('id', 0));

        if ( $user_id == 0 || $id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $mediaInfo = $this->get('PhotosVideosServices')->getMediaInfo( $id, $this->data['LanguageGet'] );

        if( !$mediaInfo )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if( $mediaInfo['v_userid'] != $user_id )
        {
            $Result['msg'] = $this->translator->trans('You are not the owner of this media');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $this->get('PhotosVideosServices')->photosVideosDelete( $id, $mediaInfo );
        
        $Result['msg'] = $this->translator->trans('Media deleted!');
        $Result['status'] = 'ok';
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function mediaTempDeleteAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();
        $id = intval($request->request->get('id', 0));
        $name = $request->request->get('name', '');
        $path = $request->request->get('path', '');

        if ( $user_id == 0 || $name == '' || $path == '' )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $this->get('PhotosVideosServices')->photosVideosTempDelete( $id, $user_id, $name, $path );

        $Result['msg'] = $this->translator->trans('Media deleted!');
        $Result['status'] = 'ok';
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function incrementObjectsViewsAction( Request $request )
    {
        $Result = array();
        $entity_type = intval($request->request->get('entity_type', 0));
        $id = intval($request->request->get('id', 0));

        $this->get('PhotosVideosServices')->photosVideosIncrementViews( $id );

        $Result['msg'] = '';
        $Result['status'] = 'ok';
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function albumDeleteAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();
        $id = intval($request->request->get('id', 0));

        if ( $user_id == 0 || $id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $limit = null;
        $srch_options = array (
            'limit' => $limit,
            'id' => $id
        );
        $albumContentlist = $this->get('PhotosVideosServices')->albumContentFromURL( $srch_options );

        if( !$albumContentlist )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $AlbumInfo = $albumContentlist[0];

        if( $AlbumInfo['a_userId'] != $user_id )
        {
            $Result['msg'] = $this->translator->trans('You are not the owner of this album');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $this->get('PhotosVideosServices')->albumDelete( $id, $albumContentlist );

        $Result['msg'] = $this->translator->trans('Media deleted!');
        $Result['status'] = 'ok';
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function categoryMediaRedirectAction(Request $request, $id, $seotitle, $seodescription, $seokeywords)
    {
        $routepath = $this->getRoutePath($request);
        return $this->redirect($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$routepath, 'media'), 301);
    }

    public function categoryMediaAction($id, $seotitle, $seodescription, $seokeywords)
    {
        $name        = explode('/', $this->get('TTRouteUtils')->UriCurrentPageURL());
        $last_record = count($name) - 1;
        $category    = $name[$last_record];
        return $this->photosVideosAction('', $category, '', 1, intval($id), '', '', $seotitle, $seodescription, $seokeywords);
    }

    public function categoryMediaWrongAction($id, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_photos_videos_media', array('qr' => '', 'catName' => 'Health+and+Spa', 't' => 'a', 'page' => 1,
            'c' => intval($id)), 301);
    }

    public function albumsRedirectAction($srch = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_albums_media', array('srch' => $srch), 301);
    }

    public function albumsAction($srch = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->render('photos_videos/albums.twig', $this->data);
    }

    public function getVideoRelatedXMLAction( Request $request )
    {
        $routepath = $this->getRoutePath($request);
        $id        = $request->query->get('id', 0);
        $VideoInfo = $this->get('PhotosVideosServices')->getMediaInfo( $id, $this->data['LanguageGet'] );
        $url_source ='getVideoRelatedXMLAction - getRelatedImages - URL: '.$routepath;

        $medialist = $this->get('ElasticServices')->getRelatedImages( $VideoInfo, 8, 'v', true, $url_source, $this->data['isUserLoggedIn'] );

        $res = "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\">\r\n"
            ."<channel>\r\n";
            foreach ($medialist as $data) {
                $title      = $this->get('app.utils')->safeXML($data['_source']['title']);
                $title_text = (strlen($title) > 20) ? substr($title, 0, 17).'...' : $title;
                $res        .= '<item>';
                $res        .= '<title>'.$title_text.'</title>';
                $res        .= '<link>'.$this->get("TTMediaUtils")->returnMediaUriHashedElastic($data, $this->LanguageGet()).'</link>';
                $res        .= '<media:thumbnail url="'.$this->get("TTMediaUtils")->mediaReturnSrcLinkElastic($data, 'small').'"/>';
                $res        .= '<media:content url="" type="video/mp4" />';
                $res        .= '</item>'."\r\n";
            }
            $res .= "</channel>";
            $res .= "\r\n";
            $res .= "</rss>";

            //echo $res;
            $res = new Response($res);
            $res->headers->set('Content-Type', 'application/rss+xml');
            return $res;
    }

    public function getThumbRelatedVTTAction( Request $request )
    {
        header("Content-Type:text/vtt;charset=utf-8");
        $path = $this->container->getParameter('CONFIG_SERVER_ROOT');
	$limit=12;

        $submit_post_get = array_merge($request->query->all(),$request->request->all());

	$id  = intval($submit_post_get['id']);
	$is_post  = intval($submit_post_get['is_post']);

        function format_time($t,$f=':') {
          return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
        }

        $VideoInfo = $this->get('PhotosVideosServices')->getMediaInfo( $id, $this->data['LanguageGet'] );
        $thumbFolder = $path . $this->get("TTMediaUtils")->videoGetInstantThumbPath2( $VideoInfo ) . "/";
        $folder = $this->get('TTRouteUtils')->currentServerURL().'/'.$this->get("TTMediaUtils")->videoGetInstantThumbPath2( $VideoInfo ) . "/";

        $nbBigImg = count( $this->get("TTFileUtils")->globFiles( $thumbFolder, 'out*.jpg'));
        
	$res = "WEBVTT\r\n\r\n";
        $oldval=0;
	for ($i = 1; $i <= $nbBigImg; $i++) {
            $dip0= format_time($oldval);
            $oldval++;
            $dip1= format_time($oldval);

            $res .= $dip0.'.000'.' --> '.$dip1.'.000'. "\r\n";
            $res .= ''.$folder . 'out' . $i . '.jpg?no_cach='.rand(). "\r\n\r\n";
	}
        $res = new Response($res);
        $res->headers->set('Content-Type', 'text/vtt');
        return $res;
    }

    public function touristtubeVideoAction( $seotitle, $seodescription, $seokeywords )
    {
        $this->data['datapagename'] = 'touristtube_video';
         if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        return $this->render('photos_videos/touristtube-video.twig', $this->data);
    }

    public function mediaTestAction($srch = '', $title = '', $fromalbum = 0, $seotitle, $seodescription, $seokeywords)
    {
        $videos                  = $this->get('PhotosVideosServices')->getCityMediaList('', '', 0, 'v', 1);
        $this->data['res_list']    = '';
        $this->data['res_listimg'] = '';
        foreach ($videos as $v_item)
        {
            $varr                      = array();
            $rpath     = $v_item['v_relativepath'];
            $name      = $v_item['v_name'];
            $videoResolutionArray = array( 'full_path'=>$v_item['v_fullpath'], 'relative_path'=>$rpath, 'name'=>$name );
            $res                       = $this->get('PhotosVideosServices')->getVideoResolutions( $videoResolutionArray, '' );
            $this->data['res_list']    = implode('/*/', $res);
            $this->data['mediaid']     = $v_item['v_id'];
            $this->data['res_listimg'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $v_item );
        }

        return $this->render('photos_videos/mediaTest.twig', $this->data);
    }

    public function mediaRedirectAction($srch = '', $title = '', $fromalbum = 0, $seotitle, $seodescription, $seokeywords)
    {
        $request = Request::createFromGlobals();
        if ($request->query->get('id', '') != '') $srch    = $request->query->get('id', '');
        if ($srch == '') {
            $srch  = $title;
            $sharr = explode('/', $srch);
            if (sizeof($sharr) > 1) {
                $srch = $sharr[1];
            }
        }
        $sharr = explode('/', $srch);
        if (sizeof($sharr) > 1) {
            $srch = $sharr[0];
        }
        $mediaInfo = $this->get('PhotosVideosServices')->mediaFromURLHashed( $srch, $this->data['LanguageGet'] );
        if (!$mediaInfo) {
            $mediaInfo = $this->get('PhotosVideosServices')->mediaFromURLId( $srch, $this->data['LanguageGet'] );
        }
        if (!$mediaInfo) {
            return $this->pageNotFoundAction();
        }
        return $this->redirect( $this->get("TTMediaUtils")->returnMediaUriHashedFromArray( $mediaInfo, $this->data['LanguageGet']), 301 );
    }

    public function photoVideoSharingRedirectAction(Request $request, $type = 'a', $seotitle, $seodescription, $seokeywords)
    {
        if ($type == 'i') return $this->redirectToLangRoute('_best_images_media', array(), 301);
        else if ($type == 'v') return $this->redirectToLangRoute('_best_videos_media', array(), 301);
        else return $this->redirectToLangRoute('_photo_video_sharing_media', array(), 301);
    }

    public function oldmediaAction($srch, $id)
    {
        $srcharr   = explode('/', $srch);
        $srch      = $srcharr[0];
        $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLHashed( $srch, $this->data['LanguageGet'] );
        if (!$videoInfo) {
            $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLId( $srch, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            $srchnew   = explode('-', $srch);
            $image_id  = $srchnew[sizeof($srchnew) - 1];
            $videoInfo = $this->get('PhotosVideosServices')->getMediaInfo( $image_id, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            return $this->pageNotFoundAction();
        }
        return $this->redirect( $this->get("TTMediaUtils")->returnMediaAlbumsUriHashedFromArray($videoInfo, $this->data['LanguageGet']), 301);
    }

    public function oldmedia2Action($srch)
    {
        $srcharr   = explode('/', $srch);
        $srch      = $srcharr[0];
        $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLHashed( $srch, $this->data['LanguageGet'] );
        if (!$videoInfo) {
            $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLId( $srch, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            $srchnew   = explode('-', $srch);
            $image_id  = $srchnew[sizeof($srchnew) - 1];
            $videoInfo = $this->get('PhotosVideosServices')->getMediaInfo( $image_id, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            return $this->pageNotFoundAction();
        }
        return $this->redirect( $this->get("TTMediaUtils")->returnMediaUriHashedFromArray($videoInfo, $this->data['LanguageGet']), 301);
    }

    public function oldmedia3Action($srch)
    {
        $srcharr   = explode('/', $srch);
        $srch      = $srcharr[0];
        $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLHashed( $srch, $this->data['LanguageGet'] );
        if (!$videoInfo) {
            $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLId( $srch, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            $srchnew   = explode('-', $srch);
            $image_id  = $srchnew[sizeof($srchnew) - 1];
            $videoInfo = $this->get('PhotosVideosServices')->getMediaInfo( $image_id, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            return $this->pageNotFoundAction();
        }
        return $this->redirect( $this->get("TTMediaUtils")->returnMediaUriHashedFromArray($videoInfo, $this->data['LanguageGet']), 301);
    }

    public function oldmedia4Action($srch)
    {
        $srcharr   = explode('/', $srch);
        $srch      = $srcharr[0];
        $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLHashed( $srch, $this->data['LanguageGet'] );
        if (!$videoInfo) {
            $videoInfo = $this->get('PhotosVideosServices')->mediaFromURLId( $srch, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            $srchnew   = explode('-', $srch);
            $image_id  = $srchnew[sizeof($srchnew) - 1];
            $videoInfo = $this->get('PhotosVideosServices')->getMediaInfo( $image_id, $this->data['LanguageGet'] );
        }
        if (!$videoInfo) {
            return $this->pageNotFoundAction();
        }
        return $this->redirect( $this->get("TTMediaUtils")->returnMediaAlbumsUriHashedFromArray($videoInfo, $this->data['LanguageGet']), 301);
    }

    public function oldSearchMediaAction($all = '', $seotitle, $seodescription, $seokeywords)
    {
        $url_current = $this->get('TTRouteUtils')->UriCurrentPageURLForLG();
        if ($this->data['LanguageGet'] != 'en') $langUrl     = $url_current[1].'/'.$this->data['LanguageGet'].'/'.$url_current[2];
        else $langUrl     = $url_current[1].'/'.$url_current[2];
        $url         = str_replace('touristtube.com', '', $langUrl);
        $params      = array();
        $qr          = '';
        $t           = 'a';
        $c           = 0;
        $orderby     = 'Default';
        $page        = 1;
        $catName     = '';
        if (strpos($url, '?') !== false) {
            global $request;
            $qr      = $request->query->get('qr', '');
            $t       = $request->query->get('t', '');
            $c       = $request->query->get('c', '');
            $orderby = $request->query->get('orderby', '');
            $page    = $request->query->get('page', '');
            if ($t == '') {
                $t = 'a';
            }
            if ((strpos($qr, '/') !== false)) {
                $qr = str_replace('/', '', $qr);
            }
        } else {
            $url_explode = explode('/', $all);
            for ($i = 0; $i < count($url_explode); $i++) {
                if ($url_explode[$i] == 'qr') {
                    $qr = $url_explode[$i + 1];
                }
                if ($url_explode[$i] == 't') {
                    $t = $url_explode[$i + 1];
                }
                if ($url_explode[$i] == 'c') {
                    $c = $url_explode[$i + 1];
                }
                if ($url_explode[$i] == 'orderby') {
                    $orderby = $url_explode[$i + 1];
                }
                if ($url_explode[$i] == 'page') {
                    $page = $url_explode[$i + 1];
                }
            }
        }
        return $this->redirectToLangRoute('_photos_videos_media', array('qr' => $qr, 'catName' => $catName, 't' => $t, 'page' => 1, 'c' => $c, 'orderby' => ''), 301);
    }

    public function oldSearchAction($t = 'a', $qr = '', $seotitle, $seodescription, $seokeywords)
    {
        $urlExplode  = explode('/', $qr);
        $last_record = count($urlExplode) - 2;
        $np          = intval($urlExplode[$last_record]);
        $qr          = $urlExplode[0];
        if (!$np || $np == 0) {
            $np = 1;
        }
        return $this->redirectToLangRoute('_photos_videos_media', array('qr' => $qr, 'catName' => 'All', 't' => $t, 'page' => 1, 'c' => 0, 'orderby' => ''), 301);
    }

    public function oldCategoryAction($srch)
    {
        return $this->redirect('/'.$srch, 301);
    }

    public function oldfestivalAction()
    {
        return $this->redirectToLangRoute('_Festivals', array(), 301);
    }

    public function photosVideosWrongAction($qr, $catName, $t, $orderby = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_photos_videos_media', array('qr' => $qr, 'catName' => $catName, 't' => $t, 'page' => 1,
                'c' => 0), 301);
    }

    public function photosVideosWrong2Action($qr, $t, $page, $c, $orderby = '', $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_photos_videos_media', array('qr' => $qr, 'catName' => '', 't' => $t, 'page' => 1,
                'c' => $c), 301);
    }

    public function issueAction()
    {
        return $this->redirectToLangRoute('_photos_videos_media', array('qr' => 'San+Diego', 'catName' => '', 't' => 'a', 'page' => 1,
                'c' => 0), 301);
    }

    public function seoBreadCrumbsMedia($in_options)
    {
        $default_opts = array(
            'entity_info' => null,
            'entity_id' => null,
            'entity_type' => null
        );
        $options      = array_merge($default_opts, $in_options);

        $crumbs_arr    = array();
        $cityInfo      = null;
        $stateInfo     = null;
        $countryInfo   = null;
        $continentInfo = null;
        if (($options['entity_id'] != null && $options['entity_type'] != null) || $options['entity_info'] != null) {
            $entity_info = $options['entity_info'];

            if ($options['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_MEDIA') && $entity_info['v_cityid'] > 0) {
                $cityInfo        = $this->get('CitiesServices')->worldcitiespopInfo($entity_info['v_cityid']);
                $countryInfo     = $cityInfo[1];
                $stateInfo       = $cityInfo[2];
                $continentInfo   = $cityInfo[3];
                $options['word'] = $this->get('app.utils')->htmlEntityDecode($entity_info['v_title']);
            }

            if (isset($entity_info['v_title'])) {
                $options['word'] = $this->get('app.utils')->htmlEntityDecode($entity_info['v_title']);
            } else if (isset($entity_info['v_name'])) {
                $options['word'] = $this->get('app.utils')->htmlEntityDecode($entity_info['v_name']);
            }
        }

        if (!$continentInfo) {
            return '';
        }

        if (sizeof($continentInfo) && $continentInfo->getName()) {
            $crumb_text   = $this->get('app.utils')->htmlEntityDecode($continentInfo->getName());
            $crumb_link   = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $crumb_text, '', 'a', 1, 0);
            $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
        }
        if ($countryInfo != null && sizeof($countryInfo) && $countryInfo->getName()) {
            $crumb_link   = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $countryInfo->getName(), '', 'a', 1, 0);
            $crumb_text   = $this->get('app.utils')->htmlEntityDecode($countryInfo->getName());
            $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
        }
        if ($stateInfo != null && sizeof($stateInfo) && $stateInfo->getStateName()) {
            $crumb_link   = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $stateInfo->getStateName(), '', 'a', 1, 0);
            $crumb_text   = $this->get('app.utils')->htmlEntityDecode($stateInfo->getStateName());
            $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
        }
        if ($cityInfo != null && sizeof($cityInfo[0]) && $cityInfo[0]->getName()) {
            $crumb_link   = $this->get("TTMediaUtils")->returnSearchMediaLink($this->data['LanguageGet'], $cityInfo[0]->getName(), '', 'a', 1, 0);
            $crumb_text   = ucwords($this->get('app.utils')->htmlEntityDecode($cityInfo[0]->getName()));
            $crumbs_arr[] = array('link' => $crumb_link, 'text' => $crumb_text);
        }
        $crumbs      = '<ul class="breadcrumb cities" itemscope itemtype="http://schema.org/BreadcrumbList">';
        $ix          = 1;
        $crumbsArray = array();
        foreach ($crumbs_arr as $crumb_arr) {
            $crumb_link = $crumb_arr['link'];
            $crumb_text = $crumb_arr['text'];
            $crumbs     .= sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%s" class="seoCrumbLink" title="%s" itemprop="item"><span itemprop="name">%s</span></a><span class="seoCrumbLinkSep"></span><meta itemprop="position" content="%s"></li>', $crumb_link, $crumb_text, $crumb_text, $ix);

            if ($ix < sizeof($crumbs_arr)) {
                $crumbs .= '<li> > </li>';
            }

            $crumbsArray[] = $crumb_text;
            $ix++;
        }
        $crumbs                    .= '</ul>';
        $all_crumbs_link           = $crumbs;
        $this->data['crumbsArray'] = $crumbsArray;
        return $all_crumbs_link;
    }
}
