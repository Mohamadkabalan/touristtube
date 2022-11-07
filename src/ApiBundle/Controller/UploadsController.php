<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadsController extends DefaultController {

    public function __construct() {
        
    }

    public function uploadImageSaveAction()
    {
        $request = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());
        $user_id = $this->getUserId();
        $channel_id = intval($criteria['channel_id']);
        $which_uploader = $criteria['which_uploader'];
        $options = array
        (
            'user_id' => $user_id,
            'channel_id' => $channel_id,
            'which_uploader' => $which_uploader,
            'lang' => $this->getLanguage(),
            'files' => $_FILES
        );

        $resp = $this->get('ApiUploadsServices')->uploadImageSaveQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
}