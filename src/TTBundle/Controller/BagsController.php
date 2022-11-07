<?php

namespace TTBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BagsController extends DefaultController
{

    /**
     * New design -- Show bag lists per user
     *
     */
    public function bagsAction($seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_log_in', array(), 301);
        }

        $userId        = $this->userGetID();
        $bagsListarray = $this->get('UserServices')->getUserBagList($userId);
        $this->data['bagsListarray'] = $bagsListarray['data'];
        $this->data['bagsListcount'] = $bagsListarray['count'];

        return $this->render('bags/bags.twig', $this->data);
    }

    /**
     * New design -- Show the items within the bag per user
     *
     */
    public function bagAction($id, $seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_log_in', array(), 301);
        }

        $bagInfo  = $this->get('UserServices')->getBagInfo($id);
        $bagItems = $this->get('UserServices')->returnBagItemData( $this->data['USERID'], $id, $this->data['LanguageGet'] );

        if ($bagInfo['cb_userId'] != $this->data['USERID']) {
            return $this->redirectToLangRoute('_welcome', array(), 301);
        }

        $this->data['bagName']  = $this->get('app.utils')->htmlEntityDecode($bagInfo['cb_name']);
        $this->data['bagId']    = $id;
        $this->data['bagItems'] = $bagItems;
        return $this->render('bags/bag.twig', $this->data);
    }

    public function addToBagAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $id = intval($request->request->get('id', 0));
        $entity_type = intval($request->request->get('type', 0));

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

        $Result['msg'] = '';
        $Result['status'] = 'ok';

        $bag_list = $this->get('UserServices')->getUserBagList( $user_id );

        $data_list = array();
        $data_list['id'] = $id;
        $data_list['type'] = $entity_type;
        $data_list['bag_list'] = $bag_list;
        $Result['msg']  = $this->render('bags/add_to_bag.twig', $data_list)->getContent();

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function addItemToBagAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $id = intval($request->request->get('id', 0));
        $entity_type = intval($request->request->get('type', 0));
        $name = $request->request->get('name', '');

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('You need to have a TouristTube account to use this feature. Click').' <a class="black_link" href="' + $this->generateLangRoute('_register') + '">'.$this->translator->trans('here').'</a> '.$this->translator->trans('to register.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $id == 0 || $entity_type == 0 || $name == '' )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $bagInfo['cb_imgpath'] = '';
        $bagInfo['cb_imgname'] = '';
        $params = $this->builtPostBagItemInfoArray( array(), $bagInfo, $entity_type, $id );

        $new_bag = $this->get('UserServices')->addBagNew( $user_id, $name, $params );

        $Result['msg'] = '';
        $Result['status'] = 'ok';

        if( $new_bag['success'] )
        {
            $params['bagId'] = $new_bag['id'];
            $params['user_id'] = $user_id;
            $params['cb_imgpath'] = '';
            $params['cb_imgname'] = '';
            $new_bagitem = $this->get('UserServices')->addBagItemNew( $params );
            
            if( !$new_bagitem['success'] )
            {
                $Result['msg'] = $this->translator->trans('invalid data.');
                $Result['status'] = 'error';
            }
        } else {
            $Result['msg'] = $this->translator->trans('invalid data.');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function addItemToOldBagAction( Request $request )
    {
        $user_id = $this->data['USERID'];
        $Result = array();

        $id = intval($request->request->get('id', 0));
        $entity_type = intval($request->request->get('type', 0));
        $bid = intval($request->request->get('bid', 0));

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('You need to have a TouristTube account to use this feature. Click').' <a class="black_link" href="' + $this->generateLangRoute('_register') + '">'.$this->translator->trans('here').'</a> '.$this->translator->trans('to register.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $id == 0 || $entity_type == 0 || $bid == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = '';
        $Result['status'] = 'ok';

        $bagInfo     = $this->get('UserServices')->getBagInfo( $bid );

        if ( !$bagInfo || $bagInfo['cb_userId'] != $user_id )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $params = $this->builtPostBagItemInfoArray( array(), $bagInfo, $entity_type, $id );
        $params['bagId'] = $bid;
        $params['user_id'] = $user_id;
        $new_bagitem = $this->get('UserServices')->addBagItemNew( $params );

        if( !$new_bagitem['success'] )
        {
            $Result['msg'] = $this->translator->trans('invalid data.');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    /**
     * Adding a new bag for New design
     *
     */
    public function addBagNewAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $userId = $this->userGetID();
        $result = $this->get('UserServices')->addBagNew($userId, $post['name']);

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Get Bag Information for New design
     *
     */
    public function getBagInfoNewAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $userId = $this->userGetID();
        $result = $this->get('UserServices')->getBagInfo($post['bagId']);

        $return = array();
        if ($result && $result['cb_userId'] == $userId) {
            $return['success'] = true;
            $return['data']    = $result;
        } else {
            $return['success'] = false;
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }
        $response = new Response(json_encode($return));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Get Bag Item Information for New design
     *
     */
    public function getBagItemInfoAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $userId      = $this->userGetID();
        $bagItemInfo = $this->get('UserServices')->getBagItemInfo($post['id'], $userId);

        $response = new Response(json_encode($bagItemInfo));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Update bag item for New Design
     *
     */
    public function updateBagItemAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $userId      = $this->userGetID();
        $bagItemInfo = $this->get('UserServices')->getBagItemInfo($post['id'], $userId);
        $bagInfo     = $this->get('UserServices')->getBagInfo($post['bagId']);

        if ( $bagInfo && $bagInfo['cb_userId'] == $userId)
        {
            $entityType         = $bagItemInfo['data']['cbi_type'];
            $itemId             = $bagItemInfo['data']['cbi_id'];

            $post = $this->builtPostBagItemInfoArray( $post, $bagInfo, $entityType, $itemId );
            $update = $this->get('UserServices')->updateBagItemInfo($post);
        }
        else
        {
            $update['success'] = false;
            $update['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }
        $response = new Response(json_encode($update));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function builtPostBagItemInfoArray( $post, $bagInfo, $entityType, $itemId )
    {
        $post['entityType'] = $entityType;
        $post['itemId']     = $itemId;
        $post['imgpath']    = '';
        $post['imgname']    = '';
        if ($bagInfo['cb_imgpath'] == '' || $bagInfo['cb_imgname'] == '') {
            if ($entityType == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK')) {
                $items_img = $this->get('ReviewsServices')->getPOIDefaultPic($itemId);
                if ($items_img) {
                    $post['imgpath'] = 'media/discover/';
                    $post['imgname'] = $items_img->getFilename();
                }
            } else if ($entityType == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
                $items_img = $this->get('ReviewsServices')->getHotelsDefaultPic($itemId);
                if ($items_img) {
                    $post['imgpath'] = 'media/discover/';
                    $post['imgname'] = $items_img->getFilename();
                }
            } else if ($entityType == $this->container->getParameter('SOCIAL_ENTITY_AIRPORT')) {
                $items_img = $this->get('ReviewsServices')->getAirportDefaultPic($itemId);
                if ($items_img) {
                    $post['imgpath'] = 'media/discover/';
                    $post['imgname'] = $items_img->getFilename();
                }
            }
        }
        return $post;
    }

    /**
     * Delete bag item for New Design
     *
     */
    public function deleteBagItemAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();
        $result  = array();

        $userId      = $this->userGetID();
        $bagItemInfo = $this->get('UserServices')->getBagItemInfo($post['id'], $userId);

        if ($bagItemInfo['success']) {
            $result = $this->get('UserServices')->deleteBagItem($post['id'], $userId);
        } else {
            $result = $bagItemInfo;
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Edit Bag Information for New design
     *
     */
    public function editBagNewAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();
        $post['user_id'] = $this->data['USERID'];

        $result   = $this->get('UserServices')->editBagInfo($post);
        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Delete Bag record New design
     *
     */
    public function deleteBagNewAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $result  = $this->get('UserServices')->deleteBag( $post['id'], $this->data['USERID'] );

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}