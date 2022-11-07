<?php

namespace TTBundle\Utils;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;

class TTMediaUtils
{
    protected $utils;
    protected $container;
    
    public function __construct(Utils $utils, ContainerInterface $container)
    {
        $this->utils         = $utils;
        $this->container     = $container;
    }

    public function ReturnAlbumUri( $albumInfo, $lang )
    {
        return $this->utils->generateLangURL($lang, '/album/'.$albumInfo->getAlbumUrl(), 'media');
    }

    public function ReturnAlbumUriFromArray( $albumInfo, $lang )
    {
        return $this->utils->generateLangURL($lang, '/album/'.$albumInfo['a_albumUrl'], 'media');
    }

    public function returnMediaUriHashed( $vidInfo, $lang )
    {
        $titlers = $this->utils->cleanTitle($vidInfo->getTitle());
        $titlers = $this->utils->getMultiByteSubstr( $titlers, 43, NULL, $lang, false );

        if ($titlers == '') $titlers = '+';
        if ($vidInfo->getImageVideo() == "i") {
            return $this->utils->generateLangURL($lang, '/best-travel-images/'.$titlers.'?id='.$vidInfo->getHashId(), 'media');
        } else {
            return $this->utils->generateLangURL($lang, '/best-travel-videos/'.$titlers.'?id='.$vidInfo->getHashId(), 'media');
        }
    }

    public function returnMediaUriHashedFromArray( $vidInfo, $lang )
    {
        $titlers = $this->utils->cleanTitle($vidInfo['v_title']);
        $titlers = $this->utils->getMultiByteSubstr( $titlers, 43, NULL, $lang, false );

        if ($titlers == '') $titlers = '+';
        if ($vidInfo['v_imageVideo'] == "i") {
            return $this->utils->generateLangURL($lang, '/best-travel-images/'.$titlers.'?id='.$vidInfo['v_hashId'], 'media');
        } else {
            return $this->utils->generateLangURL($lang, '/best-travel-videos/'.$titlers.'?id='.$vidInfo['v_hashId'], 'media');
        }
    }

    public function returnMediaUriHashedElastic($vidInfo, $lang)
    {
        $titlers = $this->utils->cleanTitle($vidInfo['_source']['title']);
        $titlers = $this->utils->getMultiByteSubstr( $titlers, 43, NULL, $lang, false );

        if ($titlers == '') $titlers = '+';
        if ($vidInfo['_source']['image_video'] == "i") {
            return $this->utils->generateLangURL($lang, '/best-travel-images/'.$titlers.'?id='.$vidInfo['_source']['hash_id'], 'media');
        } else {
            return $this->utils->generateLangURL($lang, '/best-travel-videos/'.$titlers.'?id='.$vidInfo['_source']['hash_id'], 'media');
        }
    }

    public function returnMediaAlbumsUriHashed( $vidInfo, $lang )
    {
        $titlers = $this->utils->cleanTitle($vidInfo->getTitle());
        $titlers = $this->utils->getMultiByteSubstr( $titlers, 37, NULL, $lang, false );
        if ($titlers == '') $titlers = '+';
        if ($vidInfo->getImageVideo() == "i") {
            return $this->utils->generateLangURL($lang, '/best-travel-images-album/'.$titlers.'?id='.$vidInfo->getHashId(), 'media');
        } else {
            return $this->utils->generateLangURL($lang, '/best-travel-videos-album/'.$titlers.'?id='.$vidInfo->getHashId(), 'media');
        }
    }

    public function returnMediaAlbumsUriHashedFromArray($vidInfo, $lang)
    {
        $titlers = $this->utils->cleanTitle($vidInfo['v_title']);
        $titlers = $this->utils->getMultiByteSubstr( $titlers, 37, NULL, $lang, false );
        if ($titlers == '') $titlers = '+';
        if ($vidInfo['v_imageVideo'] == "i") {
            return $this->utils->generateLangURL($lang, '/best-travel-images-album/'.$titlers.'?id='.$vidInfo['v_hashId'], 'media');
        } else {
            return $this->utils->generateLangURL($lang, '/best-travel-videos-album/'.$titlers.'?id='.$vidInfo['v_hashId'], 'media');
        }
    }

    public function returnImageFromLink($link)
    {
        if (strpos($link, 'facebook')) {
            return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/channel/fb--small.png');
        } else if (strpos($link, 'google')) {
            return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/channel/google-plus-small.png');
        } else if (strpos($link, 'twitter')) {
            return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/channel/twiter-small.png');
        } else if (strpos($link, 'pinterest')) {
            return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/channel/Pr-small.png');
        } else {
            return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/channel/web.png');
        }
    }

    public function returnSearchMediaLink($lang, $title = '', $catName = '', $t = 'a', $page = 1, $c = 0)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $c      = intval($c);
        if ($c <= 0) {
            $c       = 0;
            $catName = '';
        }
        $page = intval($page);
        if ($page < 1) $page = 1;
        if ($t != 'i' && $t != 'v') $t    = 'a';
        $lnk  = '/'.$titled.'-'.$catName.'-S'.$t.'_'.$page.'_'.$c;
        return $this->utils->generateLangURL($lang, $lnk, 'media');
    }

    public function returnSearchMediaCanonicalLink($lang, $title = '', $catName = '', $c = 0, $page = 1)
    {
        $titled = $this->utils->cleanTitleData($title);
        $titled = str_replace('-', '+', $titled);
        $page   = intval($page);
        $c      = intval($c);
        if ($page < 1) $page   = 1;
        if ($title != '') {
            $catName = '';
            $c       = 0;
        } else {
            if ($c <= 0) {
                $c       = 0;
                $catName = '';
            } else if ($catName == '') $c = 0;
        }
        $lnk = '/'.$titled.'-'.$catName.'-Sa_'.$page.'_'.$c;
        return $this->utils->generateLangURL($lang, $lnk, 'media');
    }

    public function mediaReturnSrcLinkElastic( $photoInfo, $size = '', $t_width=null, $t_height=null )
    {
        if ($photoInfo['_source']['image_video'] == 'v') {
            $mediaPath = $this->container->getParameter('CONFIG_SERVER_ROOT').$photoInfo['_source']['media']['fullpath'].'';
            $videoCode = $photoInfo['_source']['code'];
            $thumbs    = $this->container->get("TTFileUtils")->globFiles($mediaPath, "_*_".$videoCode."_*.jpg");
            if ($thumbs && count($thumbs) > 0) {
                $path_parts   = pathinfo($thumbs[0]);
                $filename     = $path_parts['filename'];
                $relativepath = $photoInfo['_source']['media']['relativepath'];
                $relativepath = str_replace('/', '', $relativepath);

                $fullPath   = $photoInfo['_source']['media']['fullpath'];
                $v_fullpath = $photoInfo['_source']['media']['fullpath'];
                $v_name     = $filename.'.jpg';
                return $this->getMediaVideosLink( $size, $v_name, $v_fullpath, $fullPath, $t_width, $t_height );
            } else {
                return $this->container->get("TTRouteUtils")->generateMediaURL('media/images/unavailable-preview.gif');
            }
        } else {
            $relativepath   = $photoInfo['_source']['media']['relativepath'];
            $relativepath   = str_replace('/', '', $relativepath);

            $fullPath   = $photoInfo['_source']['media']['fullpath'];
            $v_fullpath = $photoInfo['_source']['media']['fullpath'];
            $v_name     = $photoInfo['_source']['media']['name'];
            return $this->getMediaImagesLink( $size, $v_name, $v_fullpath, $fullPath, $t_width, $t_height );
        }
    }

    public function mediaReturnSrcLink( $photoInfo, $size = '', $t_width=null, $t_height=null )
    {
        if ($photoInfo->getImageVideo() == 'v') {
            $mediaPath = $this->container->getParameter('CONFIG_SERVER_ROOT').$photoInfo->getFullpath().'';
            $videoCode = $photoInfo->getCode();
            $thumbs    = $this->container->get("TTFileUtils")->globFiles($mediaPath, "_*_".$videoCode."_*.jpg");
            if ($thumbs && count($thumbs) > 0) {
                $path_parts   = pathinfo($thumbs[0]);
                $filename     = $path_parts['filename'];
                $relativepath = $photoInfo->getRelativepath();
                $relativepath = str_replace('/', '', $relativepath);

                $fullPath   = $photoInfo->getFullpath();
                $v_fullpath = $photoInfo->getFullpath();
                $v_name     = $filename.'.jpg';
                return $this->getMediaVideosLink( $size, $v_name, $v_fullpath, $fullPath, $t_width, $t_height );
            } else {
                return $this->container->get("TTRouteUtils")->generateMediaURL('media/images/unavailable-preview.gif');
            }
        } else {
            $relativepath   = $photoInfo->getRelativepath();
            $relativepath   = str_replace('/', '', $relativepath);

            $fullPath   = $photoInfo->getFullpath();
            $v_fullpath = $photoInfo->getFullpath();
            $v_name     = $photoInfo->getName();
            return $this->getMediaImagesLink( $size, $v_name, $v_fullpath, $fullPath, $t_width, $t_height );
        }
    }

    public function mediaReturnSrcLinkFromArray( $photoInfo, $size = '', $t_width=null, $t_height=null )
    {
        if ($photoInfo['v_imageVideo'] == 'v') {
            $mediaPath = $this->container->getParameter('CONFIG_SERVER_ROOT').$photoInfo['v_fullpath'].'';
            $videoCode = $photoInfo['v_code'];
            $thumbs    = $this->container->get("TTFileUtils")->globFiles($mediaPath, "_*_".$videoCode."_*.jpg");

            if ($thumbs && count($thumbs) > 0) {
                $path_parts   = pathinfo($thumbs[0]);
                $filename     = $path_parts['filename'];
                $relativepath = $photoInfo['v_relativepath'];
                $relativepath = str_replace('/', '', $relativepath);

                $fullPath   = $photoInfo['v_fullpath'];
                $v_fullpath = $photoInfo['v_fullpath'];
                $v_name     = $filename.'.jpg';
                return $this->getMediaVideosLink( $size, $v_name, $v_fullpath, $fullPath, $t_width, $t_height );
            } else {
                return $this->container->get("TTRouteUtils")->generateMediaURL('media/images/unavailable-preview.gif');
            }
        } else {
            $relativepath   = $photoInfo['v_relativepath'];
            $relativepath   = str_replace('/', '', $relativepath);
            $fullPath   = $photoInfo['v_fullpath'];
            $v_fullpath = $photoInfo['v_fullpath'];
            $v_name     = $photoInfo['v_name'];
            return $this->getMediaImagesLink( $size, $v_name, $v_fullpath, $fullPath, $t_width, $t_height );
        }
    }

    public function getMediaImagesLink( $size, $v_name, $v_fullpath, $fullPath, $t_width=null, $t_height=null )
    {
        if( $t_width != null && $t_height !=null )
        {
            return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, $t_width, $t_height, 'thumb'.$t_width.$t_height, $v_fullpath, $fullPath, 50);
        }
        switch( $size )
        {
            case 'thumb':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 237, 134, 'thumb', $v_fullpath, $fullPath, 50);
            break;
            case 'med':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 700, 375, 'med', $v_fullpath, $fullPath, 50);
            break;
            case 'small':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 355, 197, 'small', $v_fullpath, $fullPath, 50);
            break;
            case 'xsmall':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 136, 76, 'xsmall', $v_fullpath, $fullPath, 50);
            break;
            case '':
            case 'large':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 1000, 0, '', $v_fullpath, $fullPath, 50);
            break;
        }
        return $this->container->get("TTRouteUtils")->generateMediaURL('media/images/unavailable-preview.gif');
    }

    public function getMediaVideosLink( $size, $v_name, $v_fullpath, $fullPath, $t_width=null, $t_height=null )
    {
        if( $t_width != null && $t_height !=null )
        {
            return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, $t_width, $t_height, 'thumb'.$t_width.$t_height, $v_fullpath, $fullPath, 50);
        }
        switch( $size )
        {
            case 'thumb':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 237, 134, 'thumb', $v_fullpath, $fullPath, 50);
            break;
            case 'med':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 700, 375, 'med', $v_fullpath, $fullPath, 50);
            break;
            case '':
            case 'small':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 355, 197, 'small', $v_fullpath, $fullPath, 50);
            break;
            case 'discoverLong1':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 284, 325, 'discoverLong1', $v_fullpath, $fullPath, 50);
            break;
            case 'discoverLong2':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 578, 325, 'discoverLong2', $v_fullpath, $fullPath, 50);
            break;
            case 'xsmall':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 136, 76, 'xsmall', $v_fullpath, $fullPath, 50);
            break;
            case 'large':
                return $this->createItemThumbs( $v_name, $v_fullpath, 0, 0, 1000, 0, 'large', $v_fullpath, $fullPath, 50);
            break;
        }
        return $this->container->get("TTRouteUtils")->generateMediaURL('media/images/unavailable-preview.gif');
    }

    public function createItemThumbs($filename, $thumbpathOriginal, $coord_x, $coord_y, $coord_w, $coord_h, $namepic = 'bagthumbs', $newpath = '', $returnpath = '', $qualityres = 30, $isRest = false)
    {
        $storage_engine = '';
        if ($this->container->hasParameter('STORAGE_ENGINE')) $storage_engine = $this->container->getParameter('STORAGE_ENGINE');

        if ($newpath == '') $newpath    = $thumbpathOriginal;
        if ($returnpath == '') $returnpath = $newpath;
        $path       = $this->container->getParameter('CONFIG_SERVER_ROOT');

        if( $storage_engine == 'aws_s3' )
        {
            return $filePath = $this->container->get("TTRouteUtils")->generateMediaURL('/'.$returnpath.$filename.'?d='.$coord_w.'x'.$coord_h, null, $isRest);
        }
        else
        {
            if ($namepic) {
                $savedfilename = $namepic."_".$filename;
            } else {
                $savedfilename = $filename;
            }
            $filePath       = $path.''.$thumbpathOriginal.$filename;

            if (!$this->container->get("TTFileUtils")->fileExists($filePath)) {
                return '';
            }
            $savedThumbPath = $path.''.$newpath.$savedfilename;
            if (!$this->container->get("TTFileUtils")->fileExists($savedThumbPath) || $namepic == '') {
                $this->mediaSubsample(array(
                    'in_path' => $filePath,
                    'out_path' => $savedThumbPath,
                    'w' => $coord_w,
                    'h' => $coord_h,
                    'x' => $coord_x,
                    'y' => $coord_y,
                    'qualityres' => $qualityres
                ));
            }
            return '/'.$returnpath.$savedfilename;
        }
    }

    public function mediaSubsample($sampling_options)
    {
        $default_options = array(
            'in_path' => null,
            'out_path' => null,
            'w' => null,
            'h' => null,
            'x' => null,
            'y' => null,
            'keep_ratio' => false,
            'quality' => 100,
            'qualityres' => 30
        );
        $options         = array_merge($default_options, $sampling_options);
        $in_path         = $options['in_path'];
        $out_path        = $options['out_path'];
        if (!$in_path || !$out_path) {
            return false;
        }
        ob_start();
        $videoConverter = $this->container->getParameter('CONFIG_VIDEO_VIDEOCONVERTER');
        $minfo          = $this->mediaFileInfo($in_path);
        if (sizeof($minfo) == 0) return false;
        $width          = $this->mediaFileWidth($minfo);
        $height         = $this->mediaFileHeight($minfo);
        $w              = $options['w'];
        $h              = $options['h'];
        $x              = $options['x'];
        $y              = $options['y'];
        $keep_ratio     = $options['keep_ratio'];

        if (!$w && !$h) return false;
        if ($keep_ratio) {
            if (!$h) {
                $thumbWidth  = $w;
                $thumbHeight = intval($height * $w / $width);
            } else if (!$w) {
                $thumbHeight = $h;
                $thumbWidth  = intval($width * $h / $height);
            } else {
                $thumbWidth  = $w;
                $thumbHeight = intval($height * $w / $width);
                if ($thumbHeight > $h) {
                    $thumbHeight = $h;
                    $thumbWidth  = intval($width * $h / $height);
                }
            }
        } else if ($h != null && $w != null) {
            $thumbWidth  = $w;
            $thumbHeight = $h;
        } else {
            if (!$h) {
                if ($width > $w) {
                    $thumbWidth  = $w;
                    $thumbHeight = intval($height * $w / $width);
                } else {
                    $thumbWidth  = $width;
                    $thumbHeight = intval($height * $width / $width); // 1
                }
            } else {
                if ($height > $h) {
                    $thumbHeight = $h;
                    $thumbWidth  = intval($width * $h / $height);
                } else {
                    $thumbHeight = $height;
                    $thumbWidth  = intval($width * $height / $height); // 1
                }
            }
        }

        if (!$x || !$y) {
            $crop = $this->cropCompute($width, $height, $thumbWidth, $thumbHeight);
        } else {
            $crop        = ' -vf crop=';
            $crop_y      = abs($y);
            $crop_x      = abs($x);
            $scale1      = $w / $width;
            $scale2      = $h / $height;
            $scale       = $scale1;
            if ($scale2 > $scale) $scale       = $scale2;
            $crop_width  = $thumbWidth / $scale;
            $crop_height = $thumbHeight / $scale;
            $crop        .= "$crop_width:$crop_height:$crop_x:$crop_y";
        }
        $thumbnailSize = "{$thumbWidth}x{$thumbHeight}";
        $qscale        = 1 + (100 - $options['quality']) / 4;
        $cmd           = "$videoConverter -i \"$in_path\" -vframes 1 $crop -s $thumbnailSize -crf $qscale -y \"$out_path\" 2>&1";

        @system($cmd, $o);
        ob_clean();
        if ($this->container->get("TTFileUtils")->fileExists($out_path)) {
            $minfo         = $this->mediaFileInfo($out_path);
            $width         = $this->mediaFileWidth($minfo);
            $height        = $this->mediaFileHeight($minfo);
            $this->smart_resize_image($out_path, "", $width, $height, false, $out_path, false, false, $options['qualityres']);
        }
        return ($o == 0);
    }

    public function mediaFileInfo($filename)
    {
        $in_path = $filename;
        if (!$this->container->get("TTFileUtils")->fileExists($in_path)) {
            $in_path = $this->container->getParameter('CONFIG_SERVER_ROOT').$in_path;
        }
        $ffprobe = $this->container->getParameter('CONFIG_VIDEO_VIDEOCONVERTERFOLDER').'ffprobe';
        $cmd     = "$ffprobe -loglevel quiet -show_format -show_streams -print_format json ".escapeshellarg($in_path);
        $out     = shell_exec($cmd);
        $ret     = json_decode($out, true);
        return $ret;
    }

    public function mediaFileHeight($mediFileInfo)
    {
        if (!isset($mediFileInfo['streams'])) {
            return 0;
        }

        return intval($mediFileInfo['streams'][0]['height']);
    }

    public function mediaFileWidth($mediFileInfo)
    {
        if (!isset($mediFileInfo['streams'])) {
            return 0;
        }

        return intval($mediFileInfo['streams'][0]['width']);
    }

    // *****************************************************************************************
    // Images Functions
    public function cropCompute($width, $height, $thumbWidth, $thumbHeight)
    {
        $ar_in    = floatval($width) / floatval($height);
        $ar_thumb = floatval($thumbWidth) / floatval($thumbHeight);
        if ($ar_in < $ar_thumb) {
            $new_width  = $thumbWidth;
            $new_height = floor($height * ($thumbWidth / $width));

            $hpad = 0;
            $vpad = intval($new_height - $thumbHeight);
            $vpad = intval($vpad * ($width / $thumbWidth));
        } else {
            $new_height = $thumbHeight;
            $new_width  = floor($width * ($thumbHeight / $height));

            $vpad = 0;
            $hpad = intval($new_width - $thumbWidth);
            $hpad = intval($hpad * ($height / $thumbHeight));
        }
        $vpad = abs($vpad);
        $hpad = abs($hpad);
        $crop = ' -vf crop=';
        if ($hpad == 0) {
            $crop .= 'in_w:';
        } else {
            $crop .= "in_w-$hpad:";
        }
        if ($vpad == 0) {
            $crop .= 'in_h';
        } else {
            $crop .= "in_h-$vpad";
        }
        return $crop;
    }

    public function smart_resize_image($file, $string = "", $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false, $quality = 100)
    {
        if ($height <= 0 && $width <= 0) return false;
        if ($file === null && $string === null) return false;

        // Setting defaults and meta
        $info         = $file !== null ? $this->container->get("TTFileUtils")->getImageSizeFile($file) : $this->container->get("TTFileUtils")->getImageSizeFromStringFile($string);
        $image        = '';
        $final_width  = 0;
        $final_height = 0;
        list ($width_old, $height_old) = $info;
        $cropHeight   = $cropWidth    = 0;

        // Calculating proportionality
        if ($proportional) {
            if ($width == 0) $factor = $height / $height_old;
            elseif ($height == 0) $factor = $width / $width_old;
            else $factor = min($width / $width_old, $height / $height_old);

            $final_width  = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        } else {
            $final_width  = ($width <= 0) ? $width_old : $width;
            $final_height = ($height <= 0) ? $height_old : $height;
            $widthX       = $width_old / $width;
            $heightX      = $height_old / $height;

            $x          = min($widthX, $heightX);
            $cropWidth  = ($width_old - $width * $x) / 2;
            $cropHeight = ($height_old - $height * $x) / 2;
        }

        // Loading image to memory according to type
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
                break;
            case IMAGETYPE_GIF:
                $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);
                break;
            case IMAGETYPE_PNG:
                $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);
                break;
            default:
                return false;
        }

        // This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor($final_width, $final_height);
        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
            $transparency = imagecolortransparent($image);
            $palletsize   = imagecolorstotal($image);

            if ($transparency >= 0 && $transparency < $palletsize) {
                $transparent_color = imagecolorsforindex($image, $transparency);
                $transparency      = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            } elseif ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }
        imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);

        // Taking care of original, if needed
        if ($delete_original) {
            if ($use_linux_commands) exec('rm '.$file);
            else $this->container->get("TTFileUtils")->unlinkFile($file);
        }

        // Preparing a method of providing result
        switch (strtolower($output)) {
            case 'browser':
                $mime   = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = NULL;
                break;
            case 'file':
                $output = $file;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        // Writing image according to type to the output destination and image quality
        switch ($info[2]) {
            case IMAGETYPE_GIF:
                imagegif($image_resized, $output);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($image_resized, $output, $quality);
                break;
            case IMAGETYPE_PNG:
                $quality = 9 - (int) ((0.9 * $quality) / 10.0);
                imagepng($image_resized, $output, $quality);
                break;
            default:
                return false;
        }

        return true;
    }

    public function videoGetInstantThumbPath2( $vinfo ) {
        $videoFile = $this->videoGetPath($vinfo);
        return dirname($videoFile) . '/thumb_' . $vinfo['v_id'];
    }

    public function videoGetPath( $vinfo, $dim = '' ) {
        $videoPath = $this->container->getParameter('CONFIG_VIDEO_UPLOADPATH');
        $rpath = $vinfo['v_relativepath'];
        $name = $vinfo['v_name'];
        $code = $vinfo['v_code'];

        if ((($pos = strrpos($name, '.')) != false) && ($dim != '')) {
            $name = substr($name, 0, $pos);
            $name = $name . '.mp4';
        }

        return $tpath = $videoPath . $rpath . $dim . $name;
    }

    /**
     * auto rotate image
     */
    public function mediaRotateImage( $whereto )
    {
        $check = exif_read_data($whereto);
        if($check)
        {
            $imageOrientation=0;
            $rotateImage=0;
            if( isset($check['Orientation']) )
            {
                $orientation = $check['Orientation'];
                if (6 == $orientation)
                {
                    $rotateImage = 270;
                    $imageOrientation = 1;
                }
                elseif (3 == $orientation)
                {
                    $rotateImage = 180;
                    $imageOrientation = 1;
                }
                elseif (8 == $orientation)
                {
                    $rotateImage = 90;
                    $imageOrientation = 1;
                }
            }

            if($imageOrientation==1)
            {
                $source = imagecreatefromjpeg($whereto);
                $rotate = imagerotate($source,$rotateImage,0);
                $extension = $this->showFileExtension( $whereto );
                if ($extension == "gif")
                {
                    imagegif($rotate,$whereto);
                }
                else if ($extension == "png")
                {
                    imagepng($rotate,$whereto);
                } else {
                    imagejpeg($rotate,$whereto);
                }

                imagedestroy($source);
                imagedestroy($rotate);
            }
        }
    }

    public function showFileExtension( $filepath )
    {
        preg_match('/[^?]*/', $filepath, $matches);
        $string = $matches[0];

        $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);

        if (count($pattern) > 1) {
            $filenamepart = $pattern[count($pattern) - 1][0];
            preg_match('/[^?]*/', $filenamepart, $matches);
            return strtolower($matches[0]);
        }
        return false;
    }

    // upload video
    public function uploadVideo( $FILE, $uploadDir, $fileInput = 'uploadfile' ) {
        $fileInfo = array();
        $fileName = time() . '-' . str_replace(array(' '), array('-'), $FILE[$fileInput]['name']);
        $fileName = preg_replace('/[^a-z0-9A-Z\.]/', '-', $fileName);

        $fileSize = round( $FILE[$fileInput]['size'] / 1024 );
        $file = $uploadDir . basename($fileName);

        $dir = dirname($file);
        if (!$this->container->get("TTFileUtils")->fileExists($dir))
        {
            @mkdir($dir, 0777, $recursive = true);
        }

        if ( @move_uploaded_file( $FILE[$fileInput]['tmp_name'], $file ) )
        {
            $fileInfo ['name'] = $fileName;
            $fileInfo ['size'] = $fileSize;

            //first try to find the file's type using magic
            $fileInfo['type'] = $this->media_mime_type( $file );
            if( strstr($fileInfo['type'],'image') != null )
            {
                $this->mediaRotateImage( $file );
            }
            return $fileInfo;
        } else {
            return false;
        }
    }
    
    /**
     * tries to detect the mime type of a file
     * @param string $file the filename
     * @return string
     */
    public function media_mime_type( $file ) {
        ob_start();
        $ob = ob_get_clean();
        $mime = system("file --mime-type -b $file");
        ob_clean();
        echo $ob;

        if ($mime == 'application/octet-stream')
        {
            $mime = $this->mime_content_type($file);
        }

        if ($mime == 'application/octet-stream')
        {
            $mime = $this->mime_by_extension($file);
        }

        return $mime;
    }

    public function mime_content_type($filename) {
        return $this->mime_by_extension($filename);
    }

    public function mime_by_extension($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

            //mts
            //'video/avchd-stream',
            'm2ts' => 'video/m2ts',
            'mp2t' => 'video/mp2t',
            'mts' => 'video/vnd.dlna.mpeg-tts'
        );

        $ext = strtolower( array_pop( explode('.',$filename) ) );
        if ( array_key_exists($ext, $mime_types) ) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

    /**
     * loads an image into an image resource using the bultin php functions
     * @param string $src the path to the image to be loaded
     */
    public function loadImage($src) {
        if (stristr($src, '.bmp') != null) {
            $img = @imagecreatefromwbmp($src);
        } else if (stristr($src, '.gif') != null) {
            $img = @imagecreatefromgif($src);
        } else if (stristr($src, '.png') != null) {
            $img = @imagecreatefrompng($src);
        } else if (stristr($src, '.jpg') != null || stristr($src, '.jpeg') != null) {
            $img = @imagecreatefromjpeg($src);
        } else if (stristr($src, '.tif') != null || stristr($src, '.tiff') != null) {
            $src_arr = pathinfo($src);
            $jpg_src = $src_arr['dirname'] . $src_arr['filename'] . '.jpg';
            $cmd = "convert $src $jpg_src";
            exec($cmd);
            $this->container->get("TTFileUtils")->unlinkFile($src);
            $img = @imagecreatefromjpeg($jpg_src);
        } else {
            return false;
        }
        return $img;
    }

    /**
     * crops the users photo
     * @param string $photo photo file name name
     */
    public function userCropPhoto($photo)
    {
        $THUMB_SIZE = 230;
        $path = $this->container->getParameter('CONFIG_SERVER_ROOT') . 'media/tubers';
        $extension = '.'. $this->showFileExtension( strtolower($photo) );
        $filename = basename($photo);
        $filename = substr($filename, 0, strlen($filename) - strlen($extension) );

        $cropped_size = 105;
        $diff = intval(($THUMB_SIZE - $cropped_size) / 2);
        $mid = intval($cropped_size / 2) - 1;

        exec("convert -crop {$cropped_size}x{$cropped_size}+$diff+$diff $path/$photo $path/crop_$filename.png");
        exec("convert -size {$cropped_size}x{$cropped_size} xc:none -fill $path/crop_$filename.png -draw \"circle $mid,$mid $mid,4\" $path/crop_$filename.png");
        exec("convert -size {$cropped_size}x{$cropped_size} $path/crop_$filename.png $path/thumbStroke.png  -composite  $path/crop_$filename.png");
    }

    /*
    * get the image dimesions that fits in a box given width and height
    * $image is the full image path
    * $max_width is the maximum box width
    * $max_height is the maximum box height
    * return array of width and height of the image that must be resized
    */
    public function getImageDimensions($image, $max_width, $max_height,$ww=10, $hh=10) {

        $size = $this->container->get("TTFileUtils")->getImageSizeFile($image);
        $width = $size[0];
        $height = $size[1];

        $finalSize = array();

        // get the ratio needed
        $x_ratio = $max_width / $width;
        $y_ratio = $max_height / $height;

        // if image already meets criteria, load current values in
        // if not, use ratios to load new size info
        if (($width <= $max_width) && ($height <= $max_height)) {
            $tn_width = $width;
            $tn_height = $height;
        } else if ( $x_ratio < $y_ratio) {
                $tn_height = ceil($x_ratio * $height);
                $tn_width = ceil($x_ratio * $width);
        } else {
                $tn_height = ceil($y_ratio * $height);
                $tn_width = ceil($y_ratio * $width);
        }
        if ( $tn_width < $ww) {
            $scl= $ww/$tn_width;
            $tn_height = ceil($scl * $tn_height);
            $tn_width = ceil($scl * $tn_width);
        }
        if ( $tn_height < $hh) {
            $scl= $hh/$tn_height;
            $tn_height = ceil($scl * $tn_height);
            $tn_width = ceil($scl * $tn_width);
        }

        $finalSize['width'] = $tn_width;
        $finalSize['height'] = $tn_height;

        return $finalSize;
    }

    public function getUploadDirTree( $uploadRootDir )
    {
	$uploadDir = $uploadRootDir . date( "Y" ) .'/'. date("W");
	if ( !$this->container->get("TTFileUtils")->fileExists( $uploadDir ) )
        {
            @mkdir( $uploadDir , 0777 , TRUE);
	}
	return $uploadDir . "/" ;
    }

    public function getUploadDirTreeS3( $uploadRootDir )
    {
	$uploadDir = $uploadRootDir . date( "Y" ) .'/'. date("W");

	return $uploadDir;
    }

    /**
     * uses imagemagik converts an image to RGB pixel format. useful for converting from CMYK
     * @param string $in_image path to input (only useful if CMYK) image
     * @param string $out_image path to output RGB image
     */
    public function convertImageToRGB( $in_image, $out_image ) {
        ob_start();
        $cmd = "convert -colorspace RGB $in_image $out_image";
        system($cmd);
        ob_clean();
    }

    /**
    * converts an image from one format to another
    * @param type $file_in
    * @param type $file_out
    */
    public function convertImage($file_in, $file_out) {
        $op = ob_get_contents();
        ob_clean();

        $videoConverter = $this->container->getParameter('CONFIG_VIDEO_VIDEOCONVERTER');
        $cmd = "$videoConverter -i \"$file_in\" -q 1 -y \"$file_out\" 2>&1";
        system($cmd, $o);
        ob_clean();

        echo $op;
        return $op;
    }

    /**
     * gets the duration of the media file from its info array
     * @param array $mediFileInfo the media's Info returned from a call to mediaFileInfo
     * @return array
     */
    public function mediaFileDuration( $mediFileInfo ) {
        $duration = floor($mediFileInfo['format']['duration']);
        $duration_string = gmdate('H:i:s', $duration);
        return array( $duration, $duration_string );
    }

    /**
    * creates a thumbnail from a video
    * @param type $videoConverter
    * @param type $videoFile
    * @param type $videoPath
    * @param type $videoCode
    * @param type $w
    * @param type $h
    */
    public function videoThumbnailCreate( $videoConverter, $videoFile, $videoPath, $videoCode, $w, $h ) {
       ob_start();

       //getting video duration
       $minfo = $this->container->get("TTMediaUtils")->mediaFileInfo($videoFile);
       $width = $this->container->get("TTMediaUtils")->mediaFileWidth($minfo);
       $height = $this->container->get("TTMediaUtils")->mediaFileHeight($minfo);
       list( $totalDuration, $durationString ) = $this->mediaFileDuration($minfo);

       $thumbWidth = $w;
       $thumbHeight = $h;

       $crop = $this->container->get("TTMediaUtils")->cropCompute( $width, $height, $thumbWidth, $thumbHeight );
       $thumbnailSize = "{$thumbWidth}x{$thumbHeight}";

       $biggest_size = $this->getClosestResolution($width, $height);
       $thumbWidth2 = $biggest_size[0];
       $thumbHeight2 = $biggest_size[1];
       $crop2 = $this->container->get("TTMediaUtils")->cropCompute( $width, $height, $thumbWidth2, $thumbHeight2 );
       $thumbnailSize2 = "{$thumbWidth2}x{$thumbHeight2}";

       $crop3 = $this->container->get("TTMediaUtils")->cropCompute($width, $height, 355, 197);
       $thumbnailSize3 = 355 . "x" . 197;

       $crop4 = $this->container->get("TTMediaUtils")->cropCompute($width, $height, 136, 76);
       $thumbnailSize4 = 136 . "x" . 76;

       $crop5 = $this->container->get("TTMediaUtils")->cropCompute($width, $height, 237, 134);
       $thumbnailSize5 = 237 . "x" . 134;

       $path_parts = pathinfo($videoPath . $videoFile);
       $videoname = $path_parts['filename'];

        for ($t = 1; $t <= 1; $t++)
        {
            $thumbnail = $videoPath ."_". $videoname . "_". $videoCode . "_" . $t . "_.jpg";
            $thumbnail2 = $videoPath . 'large__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";
            $thumbnail3 = $videoPath . 'small__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";
            $thumbnail4 = $videoPath . 'xsmall__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";
            $thumbnail5 = $videoPath . 'thumb__' .$videoname. "_". $videoCode . "_" . $t . "_.jpg";

            //start taking screenshots after 2 seconds incase video begins with black
            $offset = 2;
            $start = $offset;
            $end = intval($totalDuration);
//           if ($t == 1) {
//               $start = $offset;
//               $end = intval($totalDuration / 3);
//           } else if ($t == 2) {
//               $start = intval($totalDuration / 3);
//               $end = intval(2 * $totalDuration / 3);
//           } else {
//               $start = intval(2 * $totalDuration / 3);
//               $end = intval($totalDuration);
//           }

            $interval = rand($start, $end);

            $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop -s $thumbnailSize -y \"$thumbnail\"";
            system($cmd, $o);

            $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop2 -s $thumbnailSize2 -y \"$thumbnail2\"";
            system($cmd, $o);

            $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop3 -s $thumbnailSize3 -y \"$thumbnail3\"";
            system($cmd, $o);

            $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop4 -s $thumbnailSize4 -y \"$thumbnail4\"";
            system($cmd, $o);
            $cmd = "$videoConverter -ss $interval -i \"$videoFile\" -vframes 1 $crop5 -s $thumbnailSize5 -y \"$thumbnail5\"";
            system($cmd, $o);
        }

        ob_clean();
        return ($o == 0);
    }

    public function videoDetails( $videoConverter, $videoFile )
    {
        $ob = ob_get_contents();
        ob_clean();
        $minfo = $this->container->get("TTMediaUtils")->mediaFileInfo($videoFile);
        $width = $this->container->get("TTMediaUtils")->mediaFileWidth($minfo);
        $height = $this->container->get("TTMediaUtils")->mediaFileHeight($minfo);
        list($totalDuration, $durationString) = $this->mediaFileDuration($minfo);

        echo $ob;

        return $durationString . '|' . $width . '|' . $height;
    }

    /**
     * gets the resultion closest to the standar resolutions
     * @param integer $width input width
     * @param integer $height input height
     * @return array contains <b>width</b> <b>height</b>
     */
    public function getClosestResolution($width, $height)
    {
        $widthArray = array(430, 640, 860, 1280, 1920);
        $heightArray = array(240, 360, 480, 720, 1080);

        $i = count($widthArray) - 1;

        if (($width > $widthArray[$i]) && ($height > $heightArray[$i])) {
            return array($widthArray[$i], $heightArray[$i]);
        }

        while ($i >= 0) {
            if (($width <= $widthArray[$i]) && ($height <= $heightArray[$i])) {
                return array($widthArray[$i], $heightArray[$i]);
            }
            $i--;
        }
        return array($widthArray[0], $heightArray[0]);
    }

    public function mimeIsVideo($mime) {
        if (!$mime) return false;
        $video_mime_types = array(
            'application/annodex',
            'application/mp4',
            'application/ogg',
            'application/vnd.rn-realmedia',
            'application/x-matroska',
            'video/3gpp',
            'video/3gpp2',
            'video/annodex',
            'video/divx',
            'video/flv',
            'video/h264',
            'video/mp4',
            'video/mp4v-es',
            'video/mpeg',
            'video/mpeg-2',
            'video/mpeg4',
            'video/ogg',
            'video/ogm',
            'video/quicktime',
            'video/ty',
            'video/vdo',
            'video/vivo',
            'video/vnd.rn-realvideo',
            'video/vnd.vivo',
            'video/webm',
            'video/x-bin',
            'video/x-cdg',
            'video/x-divx',
            'video/x-dv',
            'video/x-flv',
            'video/x-la-asf',
            'video/x-m4v',
            'video/x-matroska',
            'video/x-motion-jpeg',
            'video/x-ms-asf',
            'video/x-ms-dvr',
            'video/x-ms-wm',
            'video/x-ms-wmv',
            'video/x-msvideo',
            'video/x-sgi-movie',
            'video/x-tivo',
            'video/avi',
            'video/x-ms-asx',
            'video/x-ms-wvx',
            'video/x-ms-wmx',
            //mts
            'video/avchd-stream',
            'video/m2ts',
            'video/mp2t',
            'video/vnd.dlna.mpeg-tts'
        );
        return in_array($mime, $video_mime_types);
    }
}
