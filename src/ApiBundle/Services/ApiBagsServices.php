<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class ApiBagsServices extends Controller{
    
    protected $em;
    protected $container;
    protected $utils;
    
    
    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
        $this->translator    = $this->container->get('translator');
    }

    public function editBagInfo( $options )
    {
        $Results = array();
        $Results = $this->container->get('UserServices')->editBagInfo( $options );
        
        return $Results;
    }

    public function deleteBagItem( $options )
    {
        $Results = array();
        $user_id = $options['user_id'];
        $id = $options['id'];
        
        if( $user_id != 0 )
        {
            $Results = $this->container->get('UserServices')->getBagItemInfo($id, $user_id);
            if ( $Results['success'] )
            {
                $Results = $this->container->get('UserServices')->deleteBagItem($id, $user_id);
            }
        }
        else
        {
            $Results['success'] = false;
            $Results['status'] = 'error';
        }
        return $Results;
    }

    public function deleteBag( $options )
    {
        $Results = array();
        $user_id = $options['user_id'];
        $id = $options['id'];

        if( $user_id != 0 )
        {
            $Results = $this->container->get('UserServices')->deleteBag( $id, $user_id );
        }
        else
        {
            $Results['success'] = false;
            $Results['status'] = 'error';
        }
        return $Results;
    }

    public function updateBagItemInfo( $options )
    {
        $Results = array();
        $user_id = $options['user_id'];
        $item_id = $options['item_id'];
        $bag_id = $options['bag_id'];

        if( $user_id != 0 )
        {

            $bagItemInfo = $this->container->get('UserServices')->getBagItemInfo( $item_id, $user_id );
            
            if( $bagItemInfo['success'] )
            {
                $bagInfo     = $this->container->get('UserServices')->getBagInfo( $bag_id );

                if ( $bagInfo && $bagInfo['cb_userId'] == $user_id)
                {
                    $entityType = $bagItemInfo['data']['cbi_type'];
                    $itemId     = $bagItemInfo['data']['cbi_id'];
                    $post = $this->builtPostBagItemInfoArray( array(), $bagInfo, $entityType, $itemId );
                    $post['bagId'] = $bag_id;
                    $post['id'] = $item_id;
                    $Results = $this->container->get('UserServices')->updateBagItemInfo( $post );
                }
                else
                {
                    $Results['success'] = false;
                    $Results['status'] = 'error';
                }
            }
            else
            {
                $Results = $bagItemInfo;
            }
        }
        else
        {
            $Results['success'] = false;
            $Results['status'] = 'error';
        }
        return $Results;
    }

    public function addItemToBag( $options )
    {
        $Results = array();

        $bid = $options['bag_id'];
        $user_id = $options['user_id'];
        
        if( $user_id != 0 )
        {
            if( $bid > 0 )
            {
                $bagInfo     = $this->container->get('UserServices')->getBagInfo( $bid );
                if ( $bagInfo && $bagInfo['cb_userId'] == $user_id)
                {
                    $params = $this->builtPostBagItemInfoArray( array(), $bagInfo, $options['entity_type'], $options['entity_id'] );
                    $params['bagId'] = $bid;
                    $params['user_id'] = $user_id;
                    $Results = $this->container->get('UserServices')->addBagItemNew( $params );
                }
                else
                {
                    $Results['success'] = false;
                    $Results['status'] = 'error';
                }
            }
            else
            {
                $name = $options['name'];
                if( $name != '' )
                {
                    $bagInfo['cb_imgpath'] = '';
                    $bagInfo['cb_imgname'] = '';
                    $params = $this->builtPostBagItemInfoArray( array(), $bagInfo, $options['entity_type'], $options['entity_id'] );
                    $Result = $this->container->get('UserServices')->addBagNew( $user_id, $name, $params );

                    if( $Result['success'] )
                    {
                        $params['bagId'] = $Result['id'];
                        $params['user_id'] = $user_id;
                        $params['cb_imgpath'] = '';
                        $params['cb_imgname'] = '';
                        $Results = $this->container->get('UserServices')->addBagItemNew( $params );
                    }
                }
                else
                {
                    $Results['success'] = false;
                    $Results['status'] = 'error';
                }
            }
        }
        else
        {
            $Results['success'] = false;
            $Results['status'] = 'error';
        }
        
        return $Results;
    }

    public function addBagNew( $user_id, $name )
    {
        $Results = array();
        if( $user_id != 0 )
        {
            $Results = $this->container->get('UserServices')->addBagNew( $user_id, $name );
        }
        else
        {
            $Results['success'] = false;
            $Results['status'] = 'error';
        }
        return $Results;
    }

    public function getUserBagsList( $user_id )
    {
        $Results = array();
        if( $user_id != 0 )
        {
            $Results = $this->container->get('UserServices')->getUserBagList( $user_id );
        }
        else
        {
            $Results['success'] = false;
            $Results['message'] = $this->translator->trans("Please login to complete this task.");
            $Results['count']   = 0;
            $Results['status'] = 'error';
            $Results['data']    = array();
        }
        return $Results;
    }

    public function getUserBagItems( $user_id, $id, $lang )
    {
        $Results = array();
        if( $user_id != 0 )
        {
            $bagInfo  = $this->container->get('UserServices')->getBagInfo( $id );

            if ( !$bagInfo || $bagInfo['cb_userId'] != $user_id)
            {
                $Results['success'] = false;
                $Results['status']  = 'error';
                $Results['data']    = array();
            }
            else
            {
                $bagItems = $this->container->get('UserServices')->returnBagItemData( $user_id, $id, $lang );
                $Results['success'] = true;
                $Results['status']  = 'ok';
                $Results['name']    = $this->utils->htmlEntityDecode( $bagInfo['cb_name'] );
                $Results['id']      = $id;
                $Results['data']    = $bagItems;
            }
        }
        else
        {
            $Results['success'] = false;
            $Results['status']  = 'error';
            $Results['data']    = array();
        }
        return $Results;
    }
    
    public function builtPostBagItemInfoArray( $post, $bagInfo, $entityType, $itemId )
    {
        $post['entityType'] = $entityType;
        $post['itemId']     = $itemId;
        $post['imgpath']    = '';
        $post['imgname']    = '';
        if ($bagInfo['cb_imgpath'] == '' || $bagInfo['cb_imgname'] == '') {
            if ($entityType == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK')) {
                $items_img = $this->container->get('ReviewsServices')->getPOIDefaultPic($itemId);
                if ($items_img) {
                    $post['imgpath'] = 'media/discover/';
                    $post['imgname'] = $items_img->getFilename();
                }
            } else if ($entityType == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
                $items_img = $this->container->get('ReviewsServices')->getHotelsDefaultPic($itemId);
                if ($items_img) {
                    $post['imgpath'] = 'media/discover/';
                    $post['imgname'] = $items_img->getFilename();
                }
            } else if ($entityType == $this->container->getParameter('SOCIAL_ENTITY_AIRPORT')) {
                $items_img = $this->container->get('ReviewsServices')->getAirportDefaultPic($itemId);
                if ($items_img) {
                    $post['imgpath'] = 'media/discover/';
                    $post['imgname'] = $items_img->getFilename();
                }
            }
        }
        return $post;
    }
}
