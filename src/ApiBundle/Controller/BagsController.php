<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BagsController extends DefaultController {

    public function __construct() {
        
    }

    public function bagsListAction()
    {
        $user_id = $this->getUserId();
        $resp = $this->get('ApiBagsServices')->getUserBagsList( intval($user_id) );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function bagItemsAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $this->getUserId();
        $id = intval($request->query->get('id', 0));

        $resp = $this->get('ApiBagsServices')->getUserBagItems( intval($user_id), $id, $this->getLanguage() );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function createBagAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $this->getUserId();
        $name = $request->query->get('name', '');

        $resp = $this->get('ApiBagsServices')->addBagNew( intval($user_id), $name );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function updateBagNameAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $this->getUserId();
        $name = $request->query->get('name', '');
        $id = intval($request->query->get('id', 0));

        $options = array
        (
            'user_id' => $user_id,
            'id' => $id,
            'name' => $name
        );

        $resp = $this->get('ApiBagsServices')->editBagInfo( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function deleteBagItemAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $this->getUserId();
        $id = intval($request->query->get('id', 0));

        $options = array
        (
            'user_id' => $user_id,
            'id' => $id
        );

        $resp = $this->get('ApiBagsServices')->deleteBagItem( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function deleteBagAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $this->getUserId();
        $id = intval($request->query->get('id', 0));

        $options = array
        (
            'user_id' => $user_id,
            'id' => $id
        );

        $resp = $this->get('ApiBagsServices')->deleteBag( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function addItemToBagAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $this->getUserId();
        $name = $request->query->get('name', '');
        $bag_id = intval($request->query->get('bag_id', 0));
        $entity_id = intval($request->query->get('entity_id', 0));
        $entity_type = intval($request->query->get('entity_type', 0));

        $options = array
        (
            'user_id' => $user_id,
            'bag_id' => $bag_id,
            'entity_id' => $entity_id,
            'entity_type' => $entity_type,
            'name' => $name
        );

        $resp = $this->get('ApiBagsServices')->addItemToBag( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function updateBagItemInfoAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $this->getUserId();
        $bag_id = intval($request->query->get('bag_id', 0));
        $item_id = intval($request->query->get('item_id', 0));

        $options = array
        (
            'user_id' => $user_id,
            'bag_id' => $bag_id,
            'item_id' => $item_id
        );

        $resp = $this->get('ApiBagsServices')->updateBagItemInfo( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
}