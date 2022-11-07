<?php

namespace TTBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Hashids\Hashids;

class PhotosVideosServices
{
    protected $em;
    protected $utils;
    protected $container;
    private $storage_engine = '';

    public function __construct(EntityManager $em, Utils $utils, ContainerInterface $container)
    {
        $this->em            = $em;
        $this->utils         = $utils;
        $this->container     = $container;
        $this->translator    = $this->container->get('translator');
        if ($this->container->hasParameter('STORAGE_ENGINE')) $this->storage_engine = $this->container->getParameter('STORAGE_ENGINE');
    }

    /**
    * insert a temporary video
    * @param integer $user_id the user id
    * @param string $filename the filename of the media file
    * @param string $vpath the path to the uplaod
    * @param string $thumb the saved thumb
    * @param integer $album_id the album of the uploaded file
    * @param char $image_video 'i' => image, 'v' => video
    * @return boolean true|false if success fail
    */
    public function addVideoTemporary( $user_id, $filename, $vpath, $thumb, $album_id, $image_video, $channel_id = 0)
    {
        return $this->em->getRepository('TTBundle:CmsVideosTemp')->addVideoTemporary( $user_id, $filename, $vpath, $thumb, $album_id, $image_video, $channel_id );
    }

    /*
    * @getMediaTempInfo
    */
    public function getMediaTempInfo( $id )
    {
        return $this->em->getRepository('TTBundle:CmsVideosTemp')->getMediaTempInfo( $id );
    }

    /*
    * @mediaSearch function return photos videos list
    */
    public function mediaSearch( $srch_options )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->mediaSearch( $srch_options, $this->container );
    }

    /*
    * @mediaSearch function return photos videos list
    */
    public function mediaSearchCommonArray( $srch_options )
    {
        $default_opts = array
        (
            'limit' => 6,
            'page' => 0,
            'skip' => 0,
            'lang' => 'en',
            'is_public' => 2,
            'user_id' => null,
            'type' => null,
            'orderby' => 'id',
            'order' => 'a',
            'catalog_id' => null,
            'city_id' => null,
            'statename' => null,
            'country' => null,
            'n_results' => false,
            'media_id' => null,
            'date_from' => null,
            'date_to' => null,
            'channel_id' => null,
            'owner_id' => null,
            'catalog_status' => -1,
            'featured' => 0
        );
        $options      = array_merge($default_opts, $srch_options);

        $medialist = $this->mediaSearch( $options, $this->container );
        $media_array = array();
        foreach ($medialist as $v_item) {
            $varr = array();
            $varr['img'] = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $v_item, 'small');
            $varr['id'] = $v_item['v_id'];
            $varr['type'] = $v_item['v_imageVideo'];
            $varr['link'] = $this->container->get("TTMediaUtils")->returnMediaUriHashedFromArray( $v_item, $options['lang'] );
            $titles       = $v_item['v_title'];
            if ($v_item['mlv_title']) $titles = $v_item['mlv_title'];
            $varr['title'] = $this->utils->htmlEntityDecode( $titles );
            $varr['titlealt'] = $this->utils->cleanTitleDataAlt( $titles );
            $varr['city'] = $v_item['w_name'];
            $varr['country'] = $v_item['c_name'];
            $location = '';
            if( $varr['city'] != '' ) {
                $location .= $varr['city'];
            }
            if( $location != '' && $varr['country'] !='' ) {
                $location .= ', '.$varr['country'];
            }
            $varr['location'] = $location;
            $media_array[] = $varr;
        }
        return $media_array;
    }

    /*
    * @getAlbumSearch function return album list
    */
    public function getAlbumSearch( $srch_options )
    {
        return $this->em->getRepository('TTBundle:CmsUsersCatalogs')->getAlbumSearch( $srch_options, $this->container );
    }

    /*
    * @albumContentFromURL
    */
    public function albumContentFromURL( $srch_options )
    {
        return $this->em->getRepository('TTBundle:CmsUsersCatalogs')->albumContentFromURL( $srch_options, $this->container );
    }

    /*
    * @getAlbumInfo function return album Info From id
    */
    public function getAlbumInfo( $id )
    {
        return $this->em->getRepository('TTBundle:CmsUsersCatalogs')->getAlbumInfo( $id );
    }

    /*
    * @mediaGetCatalog function return album info
    */
    public function mediaGetCatalog( $id )
    {
        return $this->em->getRepository('TTBundle:CmsUsersCatalogs')->mediaGetCatalog( $id );
    }

    /*
    * @get mediaFromURLId function return media info
    */
    public function mediaFromURLId( $url, $lang = 'en' )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->mediaFromURLId( $url, $lang );
    }

    /*
    * @get mediaFromURLHashed function return media info from hashed id
    */
    public function mediaFromURLHashed( $url, $lang = 'en' )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->mediaFromURLHashed( $url, $lang );
    }

    /*
    * @getMediaInfo function return media Info From id
    */
    public function getMediaInfo( $id, $lang = 'en' )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->getMediaInfo( $id, $lang );
    }

    /*
    * @albumDelete
    */
    public function albumDelete( $id, $albumContentlist=null )
    {
        if( !$albumContentlist )
        {
            $srch_options = array (
                'limit' => null,
                'id' => $id
            );
            $albumContentlist = $this->albumContentFromURL( $srch_options );
        }
        
        foreach ($albumContentlist as $media)
        {
            $this->photosVideosDelete( $media['v_id'], $media );

        }
        return $this->em->getRepository('TTBundle:CmsUsersCatalogs')->albumDelete( $id );
    }

    /**
    * Increent a photo videos number of views
    * @param integer $id the photo videos being viewed
    */
    public function photosVideosIncrementViews( $id )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->photosVideosIncrementViews( $id );
    }

    /*
    * @photosVideosDelete
    */
    public function photosVideosDelete( $id, $mediaInfo = null )
    {
        $CONFIG_SERVER_ROOT = $this->container->getParameter('CONFIG_SERVER_ROOT');
        if( !$mediaInfo )
        {
            $mediaInfo = $this->getMediaInfo( $id );
        }

        $photosVideosDelete = $this->em->getRepository('TTBundle:CmsVideos')->photosVideosDelete( $id );

        $mask = $this->container->get("TTFileUtils")->globFiles( $CONFIG_SERVER_ROOT . $mediaInfo['v_fullpath'], "*". $mediaInfo['v_name']);
        foreach ($mask as $thumb) {
            $this->container->get("TTFileUtils")->unlinkFile($thumb);
        }

        if( $mediaInfo['v_imageVideo']=='v' )
        {
            $filename = pathinfo($mediaInfo['v_name'],PATHINFO_FILENAME);
            $mask = $this->container->get("TTFileUtils")->globFiles($CONFIG_SERVER_ROOT . $mediaInfo['v_fullpath'], "*". $filename."*");
            foreach ($mask as $thumb)
            {
                $this->container->get("TTFileUtils")->unlinkFile($thumb);
            }
        }

        $videoResolutionArray = array( 'full_path'=>$mediaInfo['v_fullpath'], 'relative_path'=>$mediaInfo['v_relativepath'], 'name'=>$mediaInfo['v_name'] );
        $resolutions = $this->getVideoResolutionsFromInfo( $videoResolutionArray, $CONFIG_SERVER_ROOT );
        foreach ($resolutions as $vid) {
            $this->container->get("TTFileUtils")->unlinkFile($CONFIG_SERVER_ROOT . $mediaInfo['v_fullpath'] . $vid[0]);
        }

        $thumbs = $this->container->get("TTFileUtils")->globFiles($CONFIG_SERVER_ROOT . $mediaInfo['v_fullpath'], '_*_'. $mediaInfo['v_code'] . "*.jpg");
        foreach ($thumbs as $thumb) {
            $this->container->get("TTFileUtils")->unlinkFile($thumb);
        }

        return $photosVideosDelete;
    }

    /*
    * @photosVideosTempDelete
    */
    public function photosVideosTempDelete( $id=0, $user_id, $name, $path )
    {
        $CONFIG_SERVER_ROOT = $this->container->getParameter('CONFIG_SERVER_ROOT');
        if( $id > 0 )
        {
            $photosVideosTempDelete = $this->em->getRepository('TTBundle:CmsVideosTemp')->photosVideosTempDelete( $id, $user_id );

            if( !$photosVideosTempDelete ) return false;
        }
        
        $mask = $this->container->get("TTFileUtils")->globFiles($CONFIG_SERVER_ROOT . $path, "*". $name);
        foreach ($mask as $thumb) {
            $this->container->get("TTFileUtils")->unlinkFile($thumb);
        }

        $filename = pathinfo( $name,PATHINFO_FILENAME);
        $mask = $this->container->get("TTFileUtils")->globFiles($CONFIG_SERVER_ROOT . $path, "*". $filename."*");
        foreach ($mask as $thumb)
        {
            $this->container->get("TTFileUtils")->unlinkFile($thumb);
        }

        return true;
    }

    /*
    * @getVideoResolutions
    */
    public function getVideoResolutions( $videoResolutionArray, $path = '' )
    {
        $all = $this->getVideoResolutionsFromInfo( $videoResolutionArray, $path );
        $ret = array();
        foreach ($all as $row) {
            $ret[] = $row[0];
        }
        return $ret;
    }

    /*
    * @getVideoResolutionsFromInfo
    */
    public function getVideoResolutionsFromInfo( $videoResolutionArray, $path = '' )
    {
        $widthArray  = array(430, 640, 860, 1280, 1920);
        $heightArray = array(240, 360, 480, 720, 1080);
        $i           = 0;
        $ret         = array();
        $j           = 0;
        foreach ($heightArray as $h) {
            $dimensions = $widthArray[$i].'x'.$h;
            $videopath  = $this->videoGetFakePath( $videoResolutionArray, $dimensions );
            if ($this->container->get("TTFileUtils")->fileExists($path.$videopath['real'])) {
                $ret[$j][0]   = $videopath['fake'];
                $ret[$j]['w'] = $widthArray[$i];
                $ret[$j]['h'] = $h;

                $j++;
            }
            $i++;
        }
        return $ret;
    }

    public function videoGetFakePath($videoResolutionArray, $dim = '' )
    {
        $videoPath = $this->container->getParameter('CONFIG_VIDEO_UPLOADPATH');
        $rpath     = $videoResolutionArray['relative_path'];
        $full_path     = $videoResolutionArray['full_path'];
        $name      = $videoResolutionArray['name'];

        if ((($pos = strrpos($name, '.')) != false) && ($dim)) {
            $name = substr($name, 0, $pos);
            //$name = $name . '.flv';
            $name = $name.'.mp4';
        }
        $tpath     = $videoPath.$rpath.$dim.$name;
        $fakerpath = str_replace('/', '', $rpath);
        $fakepath  = $full_path.$dim.$name;
        $tpath     = $videoPath.$rpath.$dim.$name;
        return array('real' => $tpath, 'fake' => $fakepath);
    }

    /*
    * @categoryGetHash function return list of media categories
    */
    public function categoryGetHash( $srch_options = array() )
    {
        $default_opts = array(
            'hide_all' => false,
            'orderby' => 'title',
            'id' => null,
            'showHome' => 0,
            'lang' => 'en',
            'order' => 'a',
            'in' => ''
        );
        $options = array_merge($default_opts, $srch_options);

        $categories = $this->em->getRepository('TTBundle:CmsAllcategories')->categoryGetHash( $options );

        $result = array();
        if ( !$options['hide_all'] ) {
            $result[0] = array( $this->translator->trans('Photo video sharing (All)'), 'Photo video sharing', 'photo-video-sharing' );
        }

        foreach ( $categories as $catarray )
        {
            $title_lang = $title = $this->utils->htmlEntityDecode($catarray['ac_title']);
            if( $catarray['mlac_title'] != '' ) $title_lang = $catarray['mlac_title'];
            $result[$catarray['ac_id']] = array( $title_lang, $title, $catarray['a_alias'] );
        }
        
        return $result;
    }

    /*
    * @categoryGetName function return category info
    */
    public function categoryGetName( $id, $lang='en' )
    {
        return $this->em->getRepository('TTBundle:CmsAllcategories')->categoryGetName( $id, $lang );
    }

    /**
    * edit a media
    * @return boolean true|false if success fail
    */
    public function mediaEdit( $srch_options )
    {
        $default_opts = array(
            'id' => 0,
            'user_id' => 0,
            'title' => '',
            'category' => 0,
            'country' => '',
            'city' => '',
            'city_id' => 0,
            'album_id' => 0,
            'channel_id' => 0,
            'description' => ''
        );
        $options      = array_merge($default_opts, $srch_options);

        if ( $options['id'] == 0 || $options['user_id'] == 0 )
        {
            return false;
        }
        
        $album_id = intval($options['album_id']);
        $this->em->getRepository('TTBundle:CmsVideosCatalogs')->videosCatalogsDelete( $options['id'] );
        if ( $album_id > 0)
        {
            $this->addVideoAlbum( $options['id'], $album_id );
        }

        $mediaEdit = $this->em->getRepository('TTBundle:CmsVideos')->mediaEdit( $options );

        return true;
    }

    /**
    * insert a media
    * @return boolean true|false if success fail
    */
    public function addMedia( $user_id, $title, $description, $category, $country, $city_id, $city, $temp_id, $album_id, $channel_id )
    {
        $mediaTempInfo = $this->getMediaTempInfo( $temp_id );
        if( !$mediaTempInfo ) return false;

        $filename = $mediaTempInfo['vt_filename'];
        $videoPath = $this->container->getParameter('CONFIG_VIDEO_UPLOADPATH');
        $relativepath = $mediaTempInfo['vt_vpath'];
        $fullpath = $videoPath.$relativepath;

        //move the upload to a new filename.
        $pathinfo = pathinfo( $filename );
        $ext = $pathinfo['extension'];

        $title_new = $this->utils->cleanTitleData( trim($title) );
        $title_new = trim($title_new,'-');
        $title_new = trim($title_new,'+');
        $title_new = str_replace('+', '-', $title_new);

        $ts = time() . rand(100, 999);
        $new_filename = $title_new . '-' . $ts . '.' . $ext;
        $file = $fullpath . $filename;

        $this->container->get("TTFileUtils")->renameFile($file, $fullpath . $new_filename);

        $file = $fullpath . $new_filename;
        $filename = $new_filename;

        $image_video = $mediaTempInfo['vt_imageVideo'];
        $duration = 0;
        if($image_video == 'i' )
        {
            $videoDetails = $this->container->get("TTFileUtils")->getImageSizeFile( $file );
            $videoWidth = intval($videoDetails[0]);
            $videoHeight = intval($videoDetails[1]);
        }
        else
        {
            $videoConverter = $this->container->getParameter('CONFIG_VIDEO_VIDEOCONVERTER');
            $videoDetails = $this->container->get("TTMediaUtils")->videoDetails( $videoConverter, $file );
            $VideoDetails = explode('|', $videoDetails);
            $duration = $VideoDetails[0];
            $videoWidth = $VideoDetails[1];
            $videoHeight = $VideoDetails[2];
        }
        $dimension = $videoWidth . ' X ' . $videoHeight;
        

        $type = $this->container->get("TTMediaUtils")->mime_by_extension($file);
        if ( strstr($type, 'image') == null && !$this->container->get("TTMediaUtils")->mimeIsVideo($type))
        {
            return false;
        }
        
        $published = ($image_video == 'i') ? 1 : 0;

        $renamed = null;
        if ( $type == 'image/tiff' && $this->storage_engine != 'aws_s3' )
        {
            $path_parts = pathinfo($filename);
            $new_filename = $path_parts['filename'] . '.jpg';
            $renamed = 'org_' . $filename;
            $this->container->get("TTMediaUtils")->convertImage( $file, $fullpath . $new_filename);
            $this->container->get("TTFileUtils")->renameFile( $file, $fullpath . $renamed);
            $file = $fullpath . $new_filename;
            $type = 'image/jpeg';
            $filename = $new_filename;
        }
        $code = md5($filename);

        $media_id = $this->em->getRepository('TTBundle:CmsVideos')->addMedia( $user_id, $code, $filename, $fullpath, $relativepath, $type, $title, $description, $category, $country, $dimension, $duration, $published, $city_id, $city, $image_video, '', '', '', $channel_id );

        if( $media_id )
        {
            $url = $this->utils->nameDataToURL( $media_id, $title );
            
            $hashids = new Hashids($this->container->getParameter('hash_salt'), 8);
            $hash_id = $hashids->encode( $media_id );
            
            if( $this->setMediaHashId( $media_id, $hash_id, $url ) )
            {
                if ( $album_id > 0)
                {
                    $this->addVideoAlbum( $media_id, $album_id );
                }
                $this->photosVideosTempDelete( $temp_id, $user_id, $mediaTempInfo['vt_filename'], $fullpath );

                if( $this->storage_engine == 'aws_s3' )
                {
                    if( strstr($type,'image') == null )
                    {
                        $pathinfo = pathinfo( $filename );
                        $ext = $pathinfo['extension'];
                        $new_filename = $pathinfo['filename'].'_'.$media_id.'.'. $ext;
                        $this->container->get("TTFileUtils")->renameFile( $file, $fullpath . $new_filename);
                        $this->setMediaName( $media_id, $new_filename );
                        $file = $fullpath . $new_filename;
                        $new_filename = 'uploadvideoprocess/' . $new_filename;
                        $this->container->get("TTFileUtils")->copyFile( $file, $new_filename);
                    }
                }
                else 
                {
                    if( strstr($type,'image') == null )
                    {
                        $this->container->get("TTMediaUtils")->videoThumbnailCreate( $videoConverter, $file, $fullpath , $code, 310, 172);
                    } else {
                        //make a copy of original
                        $this->container->get("TTFileUtils")->copyFile($file, $fullpath . 'org_' . $filename);

                        $this->container->get("TTMediaUtils")->createItemThumbs($filename, $fullpath, 0, 0, 1000, 0, '', '', '', 50);
                        $this->container->get("TTMediaUtils")->createItemThumbs($filename, $fullpath, 0, 0, 700, 375, 'med', '', '', 50);
                        $this->container->get("TTMediaUtils")->createItemThumbs($filename, $fullpath, 0, 0, 355, 197, 'small', '', '', 50);
                        $this->container->get("TTMediaUtils")->createItemThumbs($filename, $fullpath, 0, 0, 136, 76, 'xsmall', '', '', 50);
                        $this->container->get("TTMediaUtils")->createItemThumbs($filename, $fullpath, 0, 0, 237, 134, 'thumb', '', '', 50);
                    }
                }
                return $media_id;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
    * insert a media to album
    * @return boolean true|false if success fail
    */
    public function addVideoAlbum( $media_id, $album_id )
    {
        return $this->em->getRepository('TTBundle:CmsVideosCatalogs')->addVideoAlbum( $media_id, $album_id );
    }

    /*
    * @setMediaHashId
    */
    public function setMediaHashId( $id, $hash_id, $url='' )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->setMediaHashId( $id, $hash_id, $url );
    }

    /*
    * @setMediaPublished
    */
    public function setMediaPublished( $id, $published, $relativepath='', $fullpath='' )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->setMediaPublished( $id, $published, $relativepath, $fullpath );
    }

    /*
    * @setMediaName
    */
    public function setMediaName( $id, $name )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->setMediaName( $id, $name );
    }

    /*
    * @setAlbumUrl
    */
    public function setMediaUrl( $id, $url )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->setMediaUrl( $id, $url );
    }

    /**
    * insert an album
    * @return boolean true|false if success fail
    */
    public function addUserAlbum( $user_id, $name, $city_id, $city, $country, $description, $category, $channel_id = 0)
    {
        $album_url = '';
        $album_id = $this->em->getRepository('TTBundle:CmsUsersCatalogs')->addUserAlbum( $user_id, $album_url, $name, $city_id, $city, $country, $description, $category, $channel_id );

        if( $album_id )
        {
            $url = $this->utils->nameDataToURL( $album_id, $name );
            if( $this->setAlbumUrl( $album_id, $url ) )
            {
                return $album_id;
            } else {
                return false;
            }
        }
        return false;
    }

    /*
    * @setAlbumUrl
    */
    public function setAlbumUrl( $id, $url )
    {
        return $this->em->getRepository('TTBundle:CmsUsersCatalogs')->setAlbumUrl( $id, $url );
    }

    /*
    * @getCityMediaList function return list of media per location
    */
    public function getCityMediaList( $county_code='', $state_code='', $city_id=0, $type='i', $limit=10 )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->getCityMediaList( $county_code, $state_code, $city_id, $type, $limit );
    }

    /*
    * @getVideoFeatured function return list of featured media
    */
    public function getVideoFeatured( $featured, $type='a', $limit=10 )
    {
        return $this->em->getRepository('TTBundle:CmsVideos')->getVideoFeatured( $featured, $type, $limit );
    }

    /**
    * edit a album
    * @return boolean true|false if success fail
    */
    public function albumEdit( $srch_options )
    {
        $default_opts = array(
            'id' => 0,
            'user_id' => 0,
            'title' => '',
            'category' => 0,
            'country' => '',
            'city' => '',
            'city_id' => 0,
            'channel_id' => 0,
            'description' => ''
        );
        $options      = array_merge($default_opts, $srch_options);

        if ( $options['id'] == 0 || $options['user_id'] == 0 )
        {
            return false;
        }

        $this->em->getRepository('TTBundle:CmsUsersCatalogs')->albumEdit( $options );
        return true;
    }
}
