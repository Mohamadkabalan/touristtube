<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadsController extends DefaultController {

    public function homeUploadFancyAction( Request $request )
    {
        $pathto = $request->query->get('path', '');
        $curname = $request->query->get('name', '');
        $code = $request->query->get('code', '');
        $type = $request->query->get('type', '');
	$h = intval($request->query->get('h', 0));

        if( $h > 600 )
        {
            $h=600;
	}
        
        switch(($type))
        {
            case 'channel_profile':
                $ww=230;
                $hh=230;
            break;
            case 'account_pic':
                $ww=230;
                $hh=230;
            break;
            case 'channel_cover':
                $ww=800;
                $hh=256;
            break;
            default:
                $ww=100;
                $hh=100;
            break;
        }
        $link='/resizer?imgfile='.$pathto.''.$curname.'&ww='.$ww.'&hh='.$hh;

        $this->data['pathto'] = $pathto;
        $this->data['curname'] = $curname;
        $this->data['code'] = $code;
        $this->data['type'] = $type;
        $this->data['h'] = $h;
        $this->data['link'] = $link;
        $this->data['hh'] = $hh;
        $this->data['ww'] = $ww;
        return $this->render('uploads/home-upload-fancy.twig', $this->data);
    }

    public function imageCropSaveAction( Request $request )
    {
        $Result = array();
        $user_id = $this->data['USERID'];
        $owner_id = 0;
        
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
        $channel_id = intval( $submit_post_get['channel_id'] );
        $which_uploader = $submit_post_get['which_uploader'];
	$pathinfo = pathinfo($_FILES['file']['name']);

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Please login to complete this task.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if( $channel_id!=0 )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo( $channel_id, $this->data['LanguageGet'] );

            if( !$channelInfo )
            {
                $Result['msg'] = $this->translator->trans('Invalid channel information.');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if( $channelInfo['c_ownerId'] != $user_id )
            {
                $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
            $owner_id = $channelInfo['c_ownerId'];
	}
        else 
        {
            if( $which_uploader == 'channel_profile' || $which_uploader == 'channel_cover' )
            {
                $Result['msg'] = $this->translator->trans('Invalid channel information.');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }        

	$pic_name_time = time();
        switch( $which_uploader )
        {
            case 'channel_profile':
                //profile
                $picname='profile_'.$pic_name_time.'.jpg';
                $picnamejpg = 'profile_'.$pic_name_time.'.jpg';
                $wherepath = 'media/channel/' . $channel_id . '/';
            break;

            case 'channel_cover':
                //cover
                $picname='cover_'.$pic_name_time.'.jpg';
                $picnamejpg = 'cover_'.$pic_name_time.'.jpg';
                $wherepath = 'media/channel/' . $channel_id . '/';
            break;

            case 'account_pic':
                $picname=''.$pic_name_time.'_'.$user_id.'.jpg';
                $picnamejpg = $pic_name_time.'_'.$user_id.'.jpg';
                $wherepath = 'media/tubers/';
            break;

            default:
                $Result['msg'] = $this->translator->trans('Invalid information.');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
	}

        $whereto = $wherepath. '' . $picname;

        $uploadPathInfo = pathinfo($whereto);

        if( $this->storage_engine == 'aws_s3' )
        {
            $path_new = $wherepath;
            if( substr($path_new, -1) == '/' )
            {
                $path_new = substr($path_new, 0, -1);
            }

            $videoFile = $this->get("AwsS3Services")->uploadFile( $_FILES, $path_new, $picname );
            $videoFile = json_decode($videoFile, true);

            if ( isset($videoFile['error']) && $videoFile['error'] != '' )
            {
                $Result['msg'] = $this->translator->trans("Couldn't move file.");
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
            $moveFile = true;

            $whereto = $videoFile[$picname]['url'];
        }
        else
        {
            if(!$this->get("TTFileUtils")->fileExists($uploadPathInfo['dirname']))
            {
                @mkdir($uploadPathInfo['dirname'], 0777, true);
            }
            $moveFile = @move_uploaded_file( $_FILES['file']['tmp_name'], $whereto );
        }
        
        if ( $moveFile )
        {
            if( $this->storage_engine == 'aws_s3' )
            {

            }
            else
            {
                if( !in_array( strtolower($pathinfo['extension']) , array('jpg','png','gif','JPG','jpeg') ) )
                {
                    $this->get("TTMediaUtils")->convertImage( $whereto, $wherepath. '' . $picnamejpg );
                    $whereto = $wherepath. '' . $picnamejpg;
                    $picname = $picnamejpg;
                    $pathinfo['extension'] = 'jpg';
                }

                $this->get("TTMediaUtils")->mediaRotateImage( $whereto );
            }

            $size = $this->get("TTFileUtils")->getImageSizeFile( $whereto, false );
            $width = $size[0];
            $height = $size[1];

            $ww = 0;
            $hh = 0;
            $InsertId = 0;
            $send=true;
            $Result['is_video'] = 0;

            if($which_uploader=="account_pic" ){
                $ww = 230;
                $hh = 230;
                if( $width<230 || $height<230){
                    $send=false;
                    $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 230 x 230 pxls');
                    $Result['status'] = 'error';
                }
                else
                {
                    if( !$this->get('UserServices')->userSetProfilePic( $user_id, "Profile_". $picnamejpg, $wherepath ) )
                    {
                        $send=false;
                        $Result['msg'] = $this->translator->trans("Couldn't save file.");
                        $Result['status'] = 'error';
                    }else {
                        $this->get("TTFileUtils")->copyFile( $wherepath."". $picnamejpg, $wherepath."Profile_". $picnamejpg);
                    }
                }
            }else if( $which_uploader=="channel_profile" ){
                $ww = 230;
                $hh = 230;
                if(	$width<230 || $height<230){
                    $send=false;
                    $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 230 x 230 pxls');
                    $Result['status'] = 'error';
                }
                else
                {
                    $update_list['id'] = $channel_id;
                    $update_list['ownerId'] = $user_id;
                    $update_list['profileId'] = $picnamejpg;
                    $this->get('ChannelServices')->channelEdit($update_list);
                }
            }else if( $which_uploader=="channel_cover" ){
                $ww = 800;
                $hh = 256;
                if(	$width<800 || $height<256){
                    $send=false;
                    $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 800 x 256 pxls');
                    $Result['status'] = 'error';
                }
                else
                {
                    $update_list['id'] = $channel_id;
                    $update_list['ownerId'] = $user_id;
                    $update_list['coverId'] = $picnamejpg;
                    $this->get('ChannelServices')->channelEdit($update_list);
                }
            }
            if($send){
                $Result['status'] = 'ok';
                $Result['msg'] = '';
                $Result['path'] = $whereto;
            }
	}else{
            $Result['msg'] = $this->translator->trans("Couldn't move file.");
            $Result['status'] = 'error';
	}

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function createImageAction( Request $request )
    {
        header("Content-Type: image/jpeg");
        $Result = array();
        $submit_post_get = array_merge($request->query->all(),$request->request->all());

        $coord_x = intval( $submit_post_get['coord_x'] );
        $coord_y = intval( $submit_post_get['coord_y'] );
        $coord_w = intval( $submit_post_get['coord_w'] );
        $coord_h = intval( $submit_post_get['coord_h'] );
        $discover_nam = $submit_post_get['discover_nam'];
        $filetype = $submit_post_get['filetype'];
        $code = $submit_post_get['code'];
        $filename = $submit_post_get['filename'];
        $pathto = '/'.$submit_post_get['pathto'];

        if($filetype=='account_pic' )
        {
            $image = "cache/".$filename;
            $thumbpath = $pathto."Profile_".$filename;
        }
        else
        {
            $image = "cache/".$filename;
            $thumbpath = $pathto."thumb/".$filename;
        }

        $uploadPathInfo = pathinfo($thumbpath);

        $path_root = $CONFIG_SERVER_ROOT = $this->container->getParameter('CONFIG_SERVER_ROOT');
        if ( substr( $path_root, strlen($path_root)-1, strlen($path_root) ) == '/')
        {
            $path_root = substr( $path_root, 0, strlen($path_root)-1 );
        }

        if(!$this->get("TTFileUtils")->fileExists($path_root.$uploadPathInfo['dirname']))
        {
            @mkdir($path_root.$uploadPathInfo['dirname'], 0777, true);
        }

        if( $this->get("TTFileUtils")->fileExists($path_root.$thumbpath) )
        {
            $this->get("TTFileUtils")->unlinkFile($path_root.$thumbpath);
        }

        if ($coord_w && $coord_h)
        {
            $image_rs = @imagecreatefromjpeg($image);
            $new_rs = @imagecreatetruecolor($coord_w, $coord_h);

            imagecopy($new_rs, $image_rs, 0, 0, $coord_x, $coord_y, $coord_w, $coord_h);
            $extension = $this->get("TTMediaUtils")->showFileExtension( $thumbpath );
            if ($extension == "gif")
            {
                imagegif($new_rs,$path_root.$thumbpath);
            }
            else if ($extension == "png")
            {
                imagepng($new_rs,$path_root.$thumbpath);
            }
            else
            {
                imagejpeg($new_rs,$path_root.$thumbpath);
            }
        }


        $Result['status'] = 'ok';
        $Result['link'] = '';
        if($image)
        {
            if( $filetype=='account_pic' )
            {
                $wherepath = 'media/tubers/';
                $uploaddir = $CONFIG_SERVER_ROOT . $wherepath;
                $user_id = $this->data['USERID'];
                if( $this->get('UserServices')->userSetProfilePic( $user_id, "Profile_". $filename, $uploaddir ) )
                {
                    $this->get("TTMediaUtils")->createItemThumbs( "Profile_". $filename, $wherepath, 0, 0, $coord_w, $coord_h, 'cropable', $wherepath, '', 50);
                    $this->get("TTMediaUtils")->createItemThumbs( "Profile_". $filename, $wherepath, 0, 0, 45, 45, 'small', $wherepath, '', 50);
                    $this->get("TTMediaUtils")->createItemThumbs( "Profile_". $filename, $wherepath, 0, 0, 28, 28, 'xsmall', $wherepath, '', 50);
                    $this->get("TTMediaUtils")->createItemThumbs( "Profile_". $filename, $wherepath, 0, 0, 100, 100, 'thumb', $wherepath, '', 50);
                    $this->get("TTMediaUtils")->userCropPhoto( "Profile_". $filename );
                    $Result['link'] = $filename;
                }
                else
                {
                    $Result['status'] = 'error';
                }
            }
            else
            {
                $Result['link'] = $thumbpath;
            }
        }
        else
        {
            $Result['status'] = 'error';
        }
        
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function resizerAction( Request $request )
    {
        $max_width = 1000;
        $max_height = 800;

        $submit_post_get = array_merge($request->query->all(),$request->request->all());

        $image = $submit_post_get["imgfile"];
        $ww = intval($submit_post_get["ww"]);
        $hh = intval($submit_post_get["hh"]);

        if (isset($submit_post_get["max_width"]))
        {
            if($submit_post_get["max_width"] < $max_width)
            {
                $max_width = $submit_post_get["max_width"];
            }
        }

	if (isset($submit_post_get["max_height"]))
        {
            if($submit_post_get["max_height"] < $max_height)
            {
                $max_height = $submit_post_get["max_height"];
            }
        }

        if (strrchr($image, '/'))
        {
            $filename = substr(strrchr($image, '/'), 1); // remove folder references
	} else {
            $filename = $image;
	}

	$size = $this->get("TTFileUtils")->getImageSizeFile($image);
	$width = $size[0];
	$height = $size[1];
        if( $width < $max_width && $height < $max_height )
        {
            $max_width = $width;
            $max_height = $height;
        }
        
	// get the ratio needed
	$x_ratio = $max_width / $width;
	$y_ratio = $max_height / $height;

        if ( $width <= $max_width && $height <= $max_height )
        {
            $tn_width = $width;
            $tn_height = $height;
	} else if ( $x_ratio < $y_ratio )
        {
            $tn_height = ceil($x_ratio * $height);
            $tn_width = ceil($x_ratio * $width);
	} else {
            $tn_height = ceil($y_ratio * $height);
            $tn_width = ceil($y_ratio * $width);
	}

	if ( $tn_width < $ww )
        {
            $scl= $ww/$tn_width;
            $tn_height = ceil($scl * $tn_height);
            $tn_width = ceil($scl * $tn_width);
	}

	if ( $tn_height < $hh)
        {
            $scl= $hh/$tn_height;
            $tn_height = ceil($scl * $tn_height);
            $tn_width = ceil($scl * $tn_width);
	}

	$resized = 'cache/'.$filename;
	$imageModified = @filemtime($image);
	$thumbModified = @filemtime($resized);

	header("Content-type: image/jpeg");

        if( $imageModified < $thumbModified ) {
            header("Last-Modified: ".gmdate("D, d M Y H:i:s",$thumbModified)." GMT");
            readfile( $resized );
            exit;
	}

	// read image
	$ext = strtolower( substr( strrchr( $image, '.' ), 1 ) ); // get the file extension

        switch ($ext)
        {
            case 'jpg':     // jpg
                $src = imagecreatefromjpeg($image);
            break;
            case 'png':     // png
                $src = imagecreatefrompng($image);
            break;
            case 'gif':     // gif
                $src = imagecreatefromgif($image);
            break;
            case 'JPG':     // JPG
                $src = imagecreatefromjpeg($image);
            break;
            case 'jpeg':     // jpeg
                $src = imagecreatefromjpeg($image);
            break;
	}

        // set up canvas
	$dst = imagecreatetruecolor($tn_width,$tn_height);

	// copy resized image to new canvas
	imagecopyresampled ( $dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height );

	/* Sharpening adddition by Mike Harding */
	// sharpen the image (only available in PHP5.1)
	if (function_exists("imageconvolution")) {
            $matrix = array(array( -1, -1, -1 ),array( -1, 32, -1 ),array( -1, -1, -1 ));
            $divisor = 24;
            $offset = 0;
            imageconvolution($dst, $matrix, $divisor, $offset);
	}

	// send the header and new image
	imagejpeg($dst, null, 80);
	imagejpeg($dst, $resized, 80); // write the thumbnail to cache as well...

	// clear out the resources
	imagedestroy($src);
	imagedestroy($dst);
    }

    public function homeUploadFileAction( Request $request )
    {
        $Result = array();
        $user_id = $this->data['USERID'];

        $which_uploader = $request->request->get('which_uploader', '');
	$channel_id = intval($request->request->get('channel_id', 0));
	$hotel_id = intval($request->request->get('hotel_id', 0));
	$discover_nam = $request->request->get('discover_nam', '');
        $owner_id = 0;
	$pathinfo = pathinfo($_FILES['file']['name']);

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Please login to complete this task.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if( $channel_id!=0 )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo( $channel_id, $this->data['LanguageGet'] );

            if( !$channelInfo )
            {
                $Result['msg'] = $this->translator->trans('Invalid channel information.');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if( $channelInfo['c_ownerId'] != $user_id )
            {
                $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
            $owner_id = $channelInfo['c_ownerId'];
	}

	$pic_name_time = time();
        switch( $which_uploader )
        {
//            case 'channel_profile':
//                //profile
//                $picname='profile_'.$pic_name_time.'.' . $pathinfo['extension'];
//                $picnamejpg = 'profile_'.$pic_name_time.'.jpg';
//                $wherepath = 'media/channel/' . $channel_id . '/';
//            break;
//
//            case 'channel_cover':
//                //cover
//                $picname='cover_'.$pic_name_time.'.' . $pathinfo['extension'];
//                $picnamejpg = 'cover_'.$pic_name_time.'.jpg';
//                $wherepath = 'media/channel/' . $channel_id . '/';
//            break;
//
//            case 'account_pic':
//                $picname=''.$pic_name_time.'_'.$user_id.'.'. $pathinfo['extension'];
//                $picnamejpg = $pic_name_time.'_'.$user_id.'.jpg';
//                $wherepath = 'media/tubers/';
//            break;

            case 'uploadReview_Img':
                 //uploadReview_Img
                 $uploadPath = 'media/';
                 $pic_name_time = $pic_name_time.rand(10000, 99000);
                 $picname=$discover_nam.$pic_name_time.'.' . $pathinfo['extension'];
                 $picnamejpg = $discover_nam.$pic_name_time.'.jpg';
                 $wherepath = $uploadPath . 'discover/';
             break;

            case 'uploadHotelsReviews':
                 $uploadPath = 'media/';
                 $pic_name_time = $pic_name_time.rand(10000, 99000);
                 $picname=$discover_nam.$pic_name_time.'.' . $pathinfo['extension'];
                 $picnamejpg = $discover_nam.$pic_name_time.'.jpg';
                 $wherepath = $uploadPath . 'hotels/'.$hotel_id.'/';
             break;

            default:
                $Result['msg'] = $this->translator->trans('Invalid information.');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
	}

        $whereto = $wherepath. '' . $picname;

	$uploadPathInfo = pathinfo($whereto);

        if( $this->storage_engine == 'aws_s3' )
        {
            $path_new = $wherepath;
            if( substr($path_new, -1) == '/' )
            {
                $path_new = substr($path_new, 0, -1);
            }
            
            $videoFile = $this->get("AwsS3Services")->uploadFile( $_FILES, $path_new, $picname );
            $videoFile = json_decode($videoFile, true);

            if ( isset($videoFile['error']) && $videoFile['error'] != '' )
            {
                $Result['msg'] = $this->translator->trans("Couldn't move file.");
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
            $moveFile = true;

            $whereto = $videoFile[$picname]['url'];
        }
        else
        {
            if(!$this->get("TTFileUtils")->fileExists($uploadPathInfo['dirname']))
            {
                @mkdir($uploadPathInfo['dirname'], 0777, true);
            }
            $moveFile = @move_uploaded_file( $_FILES['file']['tmp_name'], $whereto );
        }

        if ( $moveFile )
        {
            if( $this->storage_engine == 'aws_s3' )
            {

            }
            else
            {
                if( !in_array( strtolower($pathinfo['extension']) , array('jpg','png','gif','JPG','jpeg') ) )
                {
                    $this->get("TTMediaUtils")->convertImage( $whereto, $wherepath. '' . $picnamejpg );
                    $whereto = $wherepath. '' . $picnamejpg;
                    $picname = $picnamejpg;
                    $pathinfo['extension'] = 'jpg';
                }

                $this->get("TTMediaUtils")->mediaRotateImage( $whereto );
            }
            
            $size = $this->get("TTFileUtils")->getImageSizeFile( $whereto, false );
            $width = $size[0];
            $height = $size[1];

            $ww = 0;
            $hh = 0;
            $InsertId = 0;
            $send=true;
            $Result['is_video'] = 0;

		if($which_uploader=="uploadHotelsReviews" ){
                    $ww = 585;
                    $hh = 334;
                    if( $width<585 || $height<334){
                        $send=false;
                        $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 585 x 334 pxls');
                        $Result['status'] = 'error';
                    }else{
                        $InsertId = $this->get('HRSServices')->addHotelImage( $user_id, $hotel_id, $picnamejpg, '' );
                        $this->get("TTMediaUtils")->createItemThumbs($picnamejpg, $wherepath, 0, 0, 134, 72, 'hotels50HS13472', $wherepath , '', 50);
                    }
		}else if($which_uploader=="uploadReview_Img" ){
                    $ww = 585;
                    $hh = 334;
                    if( $width<585 || $height<334){
                        $send=false;
                        $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 585 x 334 pxls');
                        $Result['status'] = 'error';
                    }else{
                        $scalex = $width/994;
                        $scaley = $height/530;
                        $scale = $scalex;
                        if($scaley>$scalex) $scale = $scaley;

                        $ww = round($width/$scale);
                        $hh = round($height/$scale);

                        $this->get("TTMediaUtils")->createItemThumbs($picname, $wherepath, 0, 0, $ww, $hh, '', $wherepath . 'large/', '', 50);
                        $this->get("TTMediaUtils")->createItemThumbs($picname, $wherepath, 0, 0, 175, 109, '', $wherepath . 'thumb/', '', 50);
                    }
		}else if($which_uploader=="account_pic" ){
                    $ww = 230;
                    $hh = 230;
                    if( $width<230 || $height<230){
                        $send=false;
                        $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 230 x 230 pxls');
                        $Result['status'] = 'error';
                    }
		}else if( $which_uploader=="channel_profile" ){
                    $ww = 230;
                    $hh = 230;
                    if(	$width<230 || $height<230){
                        $send=false;
                        $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 230 x 230 pxls');
                        $Result['status'] = 'error';
                    }
		}else if( $which_uploader=="channel_cover" ){
                    $ww = 800;
                    $hh = 256;
                    if(	$width<800 || $height<256){
                        $send=false;
                        $Result['msg'] = $this->translator->trans('File size error.<br/>minimum dimensions: 800 x 256 pxls');
                        $Result['status'] = 'error';
                    }
		}
		if($send){
                    $Result['status'] = 'ok';
                    if($width<1000 && $height<800){
                        $imageSize = $this->get("TTMediaUtils")->getImageDimensions($whereto,$width,$height,$ww,$hh);
                    }else{
                        $imageSize = $this->get("TTMediaUtils")->getImageDimensions($whereto,1000,800,$ww,$hh);
                    }
                    $stanWidth = $imageSize['width'];
                    $stanHeight = $imageSize['height'];

                    $Result['status'] = 'ok';
                    $Result['path'] = $wherepath;
                    $Result['InsertId'] = $InsertId;
                    $Result['name'] = $picname;
                    $Result['which_uploader'] = $which_uploader;
                    $Result['width'] = $width;
                    $Result['height'] = $height;
                    $Result['code'] = $pic_name_time;
                    $Result['stanwidth'] = $stanWidth;
                    $Result['stanheight'] = $stanHeight;
                    $Result['discover_nam'] = $discover_nam;
		}
	}else{
            $Result['msg'] = $this->translator->trans("Couldn't move file.");
            $Result['status'] = 'error';
	}

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function uploadFileAction( Request $request )
    {
        $Result = array();
        $Result['name'] = '';
        $Result['id'] = 0;
        $Result['img'] = '';
        $Result['path'] = '';
        $user_id = $this->data['USERID'];

	$channel_id = intval($request->request->get('channel_id', 0));
	$album_id = intval($request->request->get('album_id', 0));
	$pathinfo = pathinfo($_FILES['file']['name']);
        
        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Please login to complete this task.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $videoPath = $this->container->getParameter('CONFIG_VIDEO_UPLOADPATH');

        if ( !isset ( $_FILES ['file'] ) || $_FILES ['file'] == '' )
        {
            $Result['msg'] = $this->translator->trans('Error! the file cannot be uploaded! Please try again later.'). ' 101';
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( isset ( $_FILES ['file'] ) && $_FILES ['file']['error'] != 0 )
        {
            $Result['msg'] = $this->translator->trans('Error! the file cannot be uploaded! Please try again later.'). ' 103 - ' . $_FILES['file']['error'];
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if( $channel_id!=0 )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo( $channel_id, $this->data['LanguageGet'] );

            if( !$channelInfo )
            {
                $Result['msg'] = $this->translator->trans('Invalid channel information.');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if( $channelInfo['c_ownerId'] != $user_id )
            {
                $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
            $owner_id = $channelInfo['c_ownerId'];
	}

        if( $this->storage_engine == 'aws_s3' )
        {
            $uploadPath = $this->get("TTMediaUtils")->getUploadDirTreeS3( $videoPath );
            $fileName = time() . '-' . str_replace(array(' '), array('-'), $_FILES['file']['name']);
            $fileName = preg_replace('/[^a-z0-9A-Z\.]/', '-', $fileName);
            $fileSize = round( $_FILES['file']['size'] / 1024 );

            $videoFile = $this->get("AwsS3Services")->uploadFile( $_FILES, $uploadPath, $fileName );
            $videoFile = json_decode($videoFile, true);

            if ( isset($videoFile['error']) && $videoFile['error'] != '' )
            {
                $Result['msg'] = $videoFile['error'];
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            $file = $videoFile[$fileName]['url'];
            $videoFile['name'] = $fileName;
            $videoFile['size'] = $fileSize;
            $videoFile['type'] = $videoFile[$fileName]['type'];
            $videoFile['thumb_resize'] = $file;
            $uploadPath .='/';
            if( strstr($videoFile['type'],'image') != null )
            {
                $videoFile['thumb_resize'] = $this->get("TTMediaUtils")->createItemThumbs( $videoFile['name'], $uploadPath, 0, 0, 280, 280, 'thumb_resize', $uploadPath, '', 50);
            }
        }
        else
        {
            if ( !($uploadPath = $this->get("TTMediaUtils")->getUploadDirTree( $videoPath ) ) )
            {
                $Result['msg'] = $this->translator->trans('Error! the file cannot be uploaded! Please try again later.'). ' 100';
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            $videoFile = $this->get("TTMediaUtils")->uploadVideo( $_FILES, $uploadPath, 'file' );

            if( $videoFile == false || (strstr($videoFile['type'],'image') == null && !$this->get("TTMediaUtils")->mimeIsVideo($videoFile['type'])) )
            {
                $Result['msg'] = $this->translator->trans('Error! the file cannot be uploaded! Please try again later.') . ' 102';
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        $Result['name'] = $videoFile['name'];

        $videoFile['relativepath'] = str_replace ( $videoPath, '', $uploadPath );
        $uploadPath = $videoPath . $videoFile['relativepath'];
        $Result['path'] = $uploadPath;
        $video_image = ( strstr($videoFile['type'],'image') == null ) ? 'v' : 'i';
        
        if( $this->storage_engine == 'aws_s3' )
        {

        }
        else
        {
            if( strstr($videoFile['type'],'image') == null )
            {
                //video
                $videoConverter = $this->container->getParameter('CONFIG_VIDEO_VIDEOCONVERTER');
                $code = md5(time());

                $thumbWidth = 280;
                $thumbHeight = 280;
                $this->get("TTMediaUtils")->videoThumbnailCreate( $videoConverter, $uploadPath . $videoFile['name'], $uploadPath , $code, $thumbWidth, $thumbHeight);

                $thumbs = $this->get("TTFileUtils")->globFiles(  $uploadPath, "_*_" . $code . "*.jpg" );
                if ( $thumbs )
                {
                    $videoFile['thumb'] = $thumbs[0];
                    $videoFile['thumb_resize'] = '/'.$thumbs[0];
                }

                if( !$this->get("TTFileUtils")->fileExists($videoFile['thumb']) )
                {
                    $Result['msg'] = $this->translator->trans('Error! The file').' <b>'.$_FILES['file']['name'].'</b> '.$this->translator->trans('is damaged!');
                    $Result['status'] = 'error';
                    $res = new Response(json_encode($Result));
                    $res->headers->set('Content-Type', 'application/json');
                    return $res;
                }
            } else{
                //image
                $path_parts = pathinfo($uploadPath . $videoFile['name']);
                $thumb = $path_parts['dirname'] . '/thumb_' . $path_parts['filename'] . '.jpg';

                $videoFile['thumb_resize'] = $this->get("TTMediaUtils")->createItemThumbs( $videoFile['name'], $uploadPath, 0, 0, 280, 280, 'thumb_resize', $uploadPath, '', 50);
                $this->get("TTMediaUtils")->createItemThumbs( $videoFile['name'], $uploadPath, 0, 0, 237, 134, 'thumb', $uploadPath, '', 50);

                $videoFile['thumb'] = $videoPath. $videoFile['relativepath'] . 'thumb_' . $path_parts['filename'] . '.jpg';

                //maybe the input is a cmyk jpg?
                if( !$this->get("TTFileUtils")->fileExists($videoFile['thumb']) ){
                    $this->get("TTMediaUtils")->convertImageToRGB($uploadPath . $videoFile['name'], $uploadPath . $videoFile['name']);
                    $this->get("TTMediaUtils")->createItemThumbs( $videoFile['name'], $uploadPath, 0, 0, 237, 134, 'thumb', '', '', 50);
                    $this->get("TTMediaUtils")->createItemThumbs( $videoFile['name'], $uploadPath, 0, 0, 280, 280, 'thumb_resize', $uploadPath, '', 50);
                }

                if( !$this->get("TTFileUtils")->fileExists($videoFile['thumb']) )
                {
                    $Result['msg'] = $this->translator->trans('Error! The file').' <b>'.$_FILES['file']['name'].'</b> '.$this->translator->trans('is damaged!');
                    $Result['status'] = 'error';
                    $res = new Response(json_encode($Result));
                    $res->headers->set('Content-Type', 'application/json');
                    return $res;
                }
            }
        }

        $Result['id'] = $this->get('PhotosVideosServices')->addVideoTemporary($user_id, $videoFile['name'], $videoFile['relativepath'], basename($videoFile['thumb']), $album_id, $video_image, $channel_id );

        $Result['img'] = $videoFile['thumb_resize'];
        $Result['msg'] = '';
        $Result['status'] = 'ok';
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }
}
