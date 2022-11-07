<?php

namespace ApiBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//use TTBundle\Entity\Webgeocities;
//use TTBundle\Entity\CmsCountries;
//use TTBundle\Entity\DiscoverHotels;
//use Symfony\Component\Validator\Mapping\ClassMetadata;
//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\HttpFoundation\JsonResponse;

class SabreMobileController extends DefaultController
{
    public function __construct(){
        
    }
      public function flightBookingResultAction()
     {     
        $ttdrRep = "this is a test";
        $res = $this->convertToJson($ttdrRep);
        return $res;

    }
}