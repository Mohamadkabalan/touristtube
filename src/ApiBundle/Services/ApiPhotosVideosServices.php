<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class ApiPhotosVideosServices extends Controller{
    
    protected $em;
    protected $container;
    protected $utils;
    
    
    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
        $this->translator    = $this->container->get('translator');
    }
    
    public function categoryGetHash( $options )
    {
        $categories = $this->container->get('PhotosVideosServices')->categoryGetHash( $options );
        foreach ($categories as $cat_id => $ctitem) {
            $catArray['id']   = $cat_id;
            $catArray['value'] = $ctitem[0];
            $catArray['link'] = $ctitem[2];
            $Results[] = $catArray;
        }
        return $Results;
    }

    public function photosVideosSearchQuery( $options )
    {
        $Results = array();
        $from_mobile = ( isset($options['from_mobile']) )?$options['from_mobile']:1;
        $size = $options['pic_size'];
        $pic_width = $options['pic_width'];
        $media_bucket_url = $this->container->get("TTRouteUtils")->getMediaBucketURL();
        $mediaSizes = array(
            'xxx'   => array(   '1920', '1080'),
            'xx'    => array(   '1080', '607'),
            'x'     => array(   '720',  '404'),
            'h'     => array(   '560',  '315'),
            'm'     => array(   '370',  '208'),
            's'     => array(   '290',  '163'),
            'xs'    => array(   '192',  '108'),
            's_xx'  => array(   '270',  '270'),
            's_x'   => array(   '180',  '180'),
            's_h'   => array(   '140',  '140'),
            's_m'   => array(   '92',   '92')
        );

        if( $pic_width > 0 )
        {
            $t_width = $pic_width;
            $t_height = 0;
        }
        if( $size != '' )
        {
            $t_width = $mediaSizes[$size][0];
            $t_height = $mediaSizes[$size][1];
        }
        else
        {
            $t_width = $mediaSizes['m'][0];
            $t_height = $mediaSizes['m'][1];
        }
        

        $media_array = array();
        $medialist = $this->container->get('PhotosVideosServices')->mediaSearch($options);
        foreach ($medialist as $item)
        {
            $item_array = array();
            $item_array['id'] = $item['v_id'];
            $titles       = $item['v_title'];
            if($item['mlv_title']) $titles = $item['mlv_title'];
            $item_array['title']     = $this->utils->htmlEntityDecode( $titles );
            $description = $this->utils->htmlEntityDecode($item['v_description'], 0);
            if ( $item['mlv_description'] != '')
            {
                $description = $this->utils->htmlEntityDecode( $item['mlv_description'], 0);
            }

            if( $options['media_id'] )
            {
                $item_array['description'] = $description;
                $item_array['country'] = $item['v_country'];
                $item_array['cityname'] = $item['w_name'];
                $item_array['category'] = $item['v_category'];
                $item_array['isPublic'] = $item['v_isPublic'];
                $item_array['placetakenat'] = $item['v_placetakenat'];
                $item_array['keywords'] = $item['v_keywords'];
                $item_array['pdate'] = $item['v_pdate']->format('M d, Y');
                $item_array['cityid'] = $item['v_cityid'];

                $item_array['userId'] = $item['v_userid'];
                $item_array['user'] = $this->utils->returnUserArrayDisplayName( $item );

                $creator_img = $this->container->get("TTMediaUtils")->createItemThumbs( $item['u_profilePic'], 'media/tubers/', 0, 0, 64, 64, 'thumb6464');
                if( $media_bucket_url != '' && $creator_img != '' )
                {
                    $explode_array_media = explode( $media_bucket_url, $creator_img );
                    $creator_img = $explode_array_media[1];
                }
                if (substr($creator_img, 0, 1) == '/') $creator_img = ltrim($creator_img, '/');
                $item_array['userProfilePic'] = $creator_img;

                $item_array['is_channel'] = ($item['v_isChannel'])?$item['v_isChannel']:0;


                $videoResolutions = array();
                $videoPath = '';
                if ($item['v_imageVideo'] == "v")
                {
                    $fb_img = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $item, 'small');

                    $videoResolutionArray = array( 'full_path'=>$item['v_fullpath'], 'relative_path'=>$item['v_relativepath'], 'name'=>$item['v_name'] );
                    $dimenssions = $this->container->get('PhotosVideosServices')->getVideoResolutionsFromInfo( $videoResolutionArray, '' );                    
                    foreach ($dimenssions as $dimens) {
                        $videoResolutions[] = array(
                            "dimensions" => array(
                                "width" => $dimens['w'],
                                "height" => $dimens['h']
                            ),
                            "videoLink" => $dimens['0']
                        );
                    }
                    if( $pic_width > 0 )
                    {
                        $difference = array();
                        $sizes = array();

                        $differenceabs = array();

                        foreach ($dimenssions as $dim) {
                            $difference[] = $dim['w'] - $pic_width;
                            $sizes[] = $dim['w'];
                        }

                        foreach ($difference as $diff) {
                            if ($diff < 0) {
                                $differenceabs[] = intval(round(abs($diff / 3)));
                            } else {
                                $differenceabs[] = $diff;
                            }
                        }
                        $rightwidth = $sizes[array_search(min($differenceabs), $differenceabs)];
                        foreach ($dimenssions as $dimens) {
                            if ($dimens['w'] == $rightwidth) {
                                $videoPath = $dimens[0];
                            }
                        }
                    }
                } else {
                    $fb_img = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $item, 'med');
                }
                $item_array['share_image'] = $fb_img;
                $item_array['shareLink']       = $this->container->get("TTMediaUtils")->returnMediaUriHashedFromArray( $item, $options['lang'] );
                $item_array['resolutions'] = $videoResolutions;
                $item_array['videoLink'] = $videoPath;
            }
            $item_array['nViews'] = $item['v_nbViews'];
            $item_array['mediaLink']   = $item['v_fullpath'] . $item['v_name'];
            $item_array['mediaType']   = $item['v_imageVideo'];
            $item_array['size'] = $size;
            
            if( $from_mobile == 0 )
            {
                $item_array['titlealt']  = $this->utils->cleanTitleDataAlt( $titles );
                $t_date             = $item['v_pdate']->format('Y-m-d H:i');
                $commentsDate       = $this->utils->returnSocialTimeFormat($t_date, 1);
                $item_array['time'] = $commentsDate;
                
                $item_array['img_big'] = $item_array['img'] = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($item, 'small');
                if ( $item_array['mediaType'] == 'i' )
                {
                    $item_array['img_big'] = $item_array['img1'] = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($item, '');
                } else {
                    $item_array['img1'] = $item_array['img'];
                    $rpath     = $item['v_relativepath'];
                    $name      = $item['v_name'];
                    $videoResolutionArray = array( 'full_path'=>$item['v_fullpath'], 'relative_path'=>$rpath, 'name'=>$name );
                    $res                  = $this->container->get('PhotosVideosServices')->getVideoResolutions( $videoResolutionArray, '' );
                    $item_array['res_list']    = implode('/*/', $res);
                    $item_array['res_video']   = ($res)?$res[0]:'';
                    $item_array['res_listimg'] = $item_array['img_big'];
                }
                
                $item_array['link']        = $this->container->get("TTMediaUtils")->returnMediaUriHashedFromArray($item, $options['lang']);
                $item_array['description'] = $description;
            }

            $pic_link = $this->container->get("TTMediaUtils")->mediaReturnSrcLinkFromArray( $item, '', $t_width, $t_height);

            if( $media_bucket_url != '' && $pic_link != '' )
            {
                $explode_array_media = explode( $media_bucket_url, $pic_link );
                $thumb_link = $explode_array_media[1];
                $explode_array_size = explode( '?', $thumb_link );
                $full_link = $explode_array_size[0];
            }
            else
            {
                $thumb_link = $pic_link;
                $full_link = $item_array['mediaLink'];
                if( $item_array['mediaType'] == 'v' )
                {
                    $full_link = $thumb_link;
                }
            }
            if (substr($thumb_link, 0, 1) == '/') $thumb_link = ltrim($thumb_link, '/');
            if (substr($full_link, 0, 1) == '/') $full_link = ltrim($full_link, '/');
            
            $item_array['thumbLink'] = $thumb_link;
            $item_array['fulllink'] = $full_link;

            $media_array[] = $item_array;
        }

        $Results['media'] = $media_array;
        $Results['res'] = '1';
        $Results['msg'] = $this->translator->trans('done');
        return $Results;
    }
}
