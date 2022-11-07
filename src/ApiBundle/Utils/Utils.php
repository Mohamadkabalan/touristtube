<?php

namespace ApiBundle\Utils;

require_once 'HTTP/Request2.php';

class Utils {

    
    public function convertToJson($param) {
        $res = new Response();
        $res->setContent(json_encode($param));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

}
