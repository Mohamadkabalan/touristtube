<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class ApiLocalityServices extends Controller{
    
    protected $em;
    protected $container;
    protected $utils;
    
    
    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
        $this->translator    = $this->container->get('translator');
    }
    
    public function getSearchLocalityQuery( $options )
    {
        $Results = array();
        $countryCode = $options['countryCode']; 
        $term = $options['term'];
        $limit = $options['limit']; 
        $routepath = $options['route'];

        if( $term != '' )
        {
            $srch_options = array
            (
                'term' => $term
            );
            if( isset($countryCode) && $countryCode != '' )
            {
                $srch_options['countryCode'] = $countryCode;
            }

            $queryStringResult = $this->container->get('ElasticServices')->getLocationSearch( $srch_options, $routepath );
            $result_list = $queryStringResult[0];
            foreach ($result_list as $document)
            {
                $item_array['id']          = $document['_source']['id'];
                $item_array['fullName']    = $item_array['name'] = $document['_source']['name'];
                $item_array['stateCode']   = ( isset($document['_source']['stateCode']) && $document['_source']['stateCode']!='')?$document['_source']['stateCode']:'';
                $item_array['countryCode'] = $document['_source']['contryCode'];
                
                if (isset($document['_source']['type']) && $document['_source']['type'] == 'state')
                {
                    $country_array = $this->container->get('CmsCountriesServices')->countryGetInfo( $item_array['countryCode'] );
                    if( $country_array )
                    {
                        $countryName            = $this->utils->htmlEntityDecode( $country_array->getName() );
                        $item_array['fullName'] = $item_array['fullName'] .', '.$countryName;
                    }
                }
                else if (isset($document['_source']['type']) && $document['_source']['type'] == 'city')
                {
                    $state_array   = $this->container->get('CitiesServices')->worldStateInfo( $item_array['countryCode'], $item_array['stateCode'] );
                    if ($state_array && sizeof($state_array))
                    {
                        $countryName = '';
                        if( isset($state_array[1]) && $state_array[1] )
                        {
                            $country_array = $state_array[1];
                            $countryName = ' '.$this->utils->htmlEntityDecode($country_array->getName());
                        }
                        $stateName              = $this->utils->htmlEntityDecode($state_array[0]->getStateName());
                        $item_array['fullName'] = $item_array['fullName'] .', '.$stateName.$countryName;
                    }
                    else
                    {
                        $country_array = $this->container->get('CmsCountriesServices')->countryGetInfo( $item_array['countryCode'] );
                        if( $country_array )
                        {
                            $countryName            = $this->utils->htmlEntityDecode( $country_array->getName() );
                            $item_array['fullName'] = $item_array['fullName'] .', '.$countryName;
                        }
                    }
                }
                $item_array['type'] = $document['_source']['type'];
                
                $Results[] = $item_array;
            }
        }
        
        return $Results;
    }
}
