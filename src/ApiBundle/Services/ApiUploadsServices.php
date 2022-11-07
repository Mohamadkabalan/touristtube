<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class ApiUploadsServices {

    protected $em;
    protected $container;
    protected $utils;
    protected $storage_engine = '';

    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
        $this->translator    = $this->container->get('translator');
        if ($this->container->hasParameter('STORAGE_ENGINE')) $this->storage_engine = $this->container->getParameter('STORAGE_ENGINE');
    }

    public function uploadImageSaveQuery( $options )
    {
        $owner_id = 0;
        $user_id = intval($options['user_id']);
        $channel_id = intval($options['channel_id']);
        $which_uploader = $options['which_uploader'];
        $lang = $options['lang'];
        $files = $options['files'];
        $return   = array();

        if( $user_id == 0 )
        {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Please login to complete this task.");
            return $return;
        }

        if( $channel_id !=0 )
        {
            $channelInfo = $this->container->get('ChannelServices')->channelGetInfo( $channel_id, $lang );

            if( !$channelInfo )
            {
                $return['success'] = false;
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans('Invalid channel information.');
                return $return;
            }

            if( $channelInfo['c_ownerId'] != $user_id )
            {
                $return['success'] = false;
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans('You are not the owner of this channel');
                return $return;
            }
            $owner_id = $channelInfo['c_ownerId'];
	}
        else 
        {
            if( $which_uploader == 'channel_profile' || $which_uploader == 'channel_cover' )
            {
                $return['success'] = false;
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans('Invalid channel information.');
                return $return;
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
                $return['success'] = false;
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans('Invalid information.');
                return $return;
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

            $videoFile = $this->container->get("AwsS3Services")->uploadFile( $files, $path_new, $picname );
            $videoFile = json_decode($videoFile, true);

            if ( isset($videoFile['error']) && $videoFile['error'] != '' )
            {
                $return['success'] = false;
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans("Couldn't move file.");
                return $return;
            }
            $moveFile = true;

            $whereto = $videoFile[$picname]['url'];
        }
        else
        {
            if(!$this->container->get("TTFileUtils")->fileExists($uploadPathInfo['dirname']))
            {
                @mkdir($uploadPathInfo['dirname'], 0777, true);
            }
            $moveFile = @move_uploaded_file( $files['file']['tmp_name'], $whereto );
        }

        if ( $moveFile )
        {
            if( $this->storage_engine == 'aws_s3' )
            {

            }
            else
            {
                if(!in_array(strtolower($uploadPathInfo['extension']), array('jpg', 'png', 'gif', 'jpeg')))
                {
                    $this->container->get("TTMediaUtils")->convertImage( $whereto, $wherepath. '' . $picnamejpg );
                    $whereto = $wherepath. '' . $picnamejpg;
                    $picname = $picnamejpg;
                    $uploadPathInfo['extension'] = 'jpg';
                }

                $this->container->get("TTMediaUtils")->mediaRotateImage( $whereto );
            }

            $size = $this->container->get("TTFileUtils")->getImageSizeFile( $whereto, false );
            $width = $size[0];
            $height = $size[1];

            $ww = 0;
            $hh = 0;
            $InsertId = 0;
            $send=true;
            $return['is_video'] = 0;

            if($which_uploader=="account_pic" ){
                $ww = 230;
                $hh = 230;
                if( $width<230 || $height<230){
                    $send=false;
                    $return['success'] = false;
                    $return['message'] = $this->translator->trans('File size error. minimum dimensions: 230 x 230 pxls');
                    $return['status'] = 'error';
                }
                else
                {
                    if( !$this->container->get('UserServices')->userSetProfilePic( $user_id, "Profile_". $picnamejpg, $wherepath ) )
                    {
                        $send=false;
                        $return['success'] = false;
                        $return['message'] = $this->translator->trans("Couldn't save file.");
                        $return['status'] = 'error';
                    }else {
                        $this->container->get("TTFileUtils")->copyFile( $wherepath."". $picnamejpg, $wherepath."Profile_". $picnamejpg);
                    }
                }
            }else if( $which_uploader=="channel_profile" ){
                $ww = 230;
                $hh = 230;
                if(	$width<230 || $height<230){
                    $send=false;
                    $return['success'] = false;
                    $return['message'] = $this->translator->trans('File size error. minimum dimensions: 230 x 230 pxls');
                    $return['status'] = 'error';
                }
                else
                {
                    $update_list['id'] = $channel_id;
                    $update_list['ownerId'] = $user_id;
                    $update_list['profileId'] = $picnamejpg;
                    $this->container->get('ChannelServices')->channelEdit($update_list);
                }
            }else if( $which_uploader=="channel_cover" ){
                $ww = 800;
                $hh = 256;
                if(	$width<800 || $height<256){
                    $send=false;
                    $return['success'] = false;
                    $return['message'] = $this->translator->trans('File size error. minimum dimensions: 800 x 256 pxls');
                    $return['status'] = 'error';
                }
                else
                {
                    $update_list['id'] = $channel_id;
                    $update_list['ownerId'] = $user_id;
                    $update_list['coverId'] = $picnamejpg;
                    $this->container->get('ChannelServices')->channelEdit($update_list);
                }
            }
            if($send){
                $return['success'] = true;
                $return['status'] = 'ok';
                $return['message'] = '';
                $return['path'] = $whereto;
            }
	}else{
            $return['success'] = false;
            $return['message'] = $this->translator->trans("Couldn't move file.");
            $return['status'] = 'error';
	}
        
        return $return;
    }
}
