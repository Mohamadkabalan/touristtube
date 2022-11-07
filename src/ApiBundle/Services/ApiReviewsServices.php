<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class ApiReviewsServices extends Controller{
    
    protected $em;
    protected $container;
    protected $utils;
    
    
    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
        $this->translator    = $this->container->get('translator');
    }
    
    public function getReviewAutocompleteQuery( $entity_type, $term, $limit = 10, $routepath = '' )
    {
        $Results = array();

        if( $term != '' )
        {
            list( $result_list, $type ) = $this->container->get('ReviewsServices')->getReviewAutocompleteQR( $entity_type, $term, $limit, $routepath );

            foreach ($result_list as $item)
            {
                $id    = $item['_source']['id'];
                $type  = strtolower($type);
                $title = $this->utils->htmlEntityDecode($item['_source']['name']);

                $address = '';
                if ( $type == 'h')
                {
                    $address = $this->container->get('ReviewsServices')->returnHRSHotelsLocation($item);
                }
                elseif ($type == 'p')
                {
                    $address = $this->container->get('ReviewsServices')->returnPoiLocation($item);
                } elseif ($type == 'ai')
                {
                    $address = $this->container->get('ReviewsServices')->returnAirportsLocation($item);
                }
                $item_array['id'] = $id;
                $item_array['title'] = $title;
                $item_array['address'] = $address;
                
                $Results[] = $item_array;
            }
        }
        
        return $Results;
    }
}
