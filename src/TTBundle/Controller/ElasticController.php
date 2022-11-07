<?php

namespace TTBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Entity\Webgeocities;
use TTBundle\Entity\CmsCountries;
use \TTBundle\Model\ElasticSearchSC;

class ElasticController extends DefaultController
{

    public function hotelsNearByRedirectAction($dest, $dest1, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_hotels_near', array('dest' => $dest, 'dest1' => $dest1), 301);
    }

    public function hotelsNearByAction($dest, $dest1, $seotitle, $seodescription, $seokeywords)
    {
        $parameters = array(
            'dest'=>$dest, 
            'srch'=>$dest1, 
            'nearby' => 1,
            'seotitle'=>$seotitle, 
            'seodescription'=>$seodescription, 
            'seokeywords'=>$seokeywords
        );
        
        return $this->allHotelsIn($parameters);
    }
}