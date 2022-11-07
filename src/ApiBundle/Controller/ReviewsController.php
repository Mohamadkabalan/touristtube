<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReviewsController extends DefaultController {

    public function __construct() {
        
    }

    public function reviewAutocompleteSearchAction() {

        $request = Request::createFromGlobals();

        $entity_type = intval($request->query->get('t', 28));
        $limit = intval($request->query->get('limit', 10));
        if( $limit > 50 ) $limit = 50;
        $term = $request->query->get('term', '');


        $resp = $this->get('ApiReviewsServices')->getReviewAutocompleteQuery( $entity_type, $term, $limit, 'mobile' );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
}