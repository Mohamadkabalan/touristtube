<?php

namespace RestBundle\Controller\autocomplete;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Controller\TTRestController;

class AutocompleteController extends TTRestController
{

    /**
     * The __construct when we make a new instance of AutocompleteController class.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request = Request::createFromGlobals();
    }
    
    //review autocomplete 
    public function reviewAutocompleteSearchAction() 
    {
        // specify required fields
        $requirements = array(
            't',
            'term'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $entity_type = ( isset( $criteria['t'] ) && $criteria['t'] ) ? intval($criteria['t']) : 28;
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 50 ) $limit = 50;
        $term = ( isset( $criteria['term'] ) && $criteria['term'] ) ? $criteria['term'] : '';
        $lang = $this->LanguageGet();
        
        $options = array(
            'entity_type' => $entity_type,
            'term' => $term,
            'limit' => $limit,
            'lang' => $lang,
            'route' => 'mobile'
        );
        $resp = $this->get('ApiAutocompleteServices')->getReviewAutocompleteQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
    
    //hotel autocomplete 
    public function hotelAutocompleteSearchAction()
    {
        // specify required fields
        $requirements = array(
            'term'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $term = ( isset( $criteria['term'] ) && $criteria['term'] ) ? $criteria['term'] : '';
        $page_src = ( isset( $criteria['page_src'] ) && $criteria['page_src'] ) ? $criteria['page_src'] : $this->container->getParameter('hotels')['page_src']['hrs'];
        $cityId = ( isset( $criteria['cityId'] ) && $criteria['cityId'] ) ? intval($criteria['cityId']) : 0;
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 50 ) $limit = 50;
        
        $lang = $this->LanguageGet();
        $options = array(
            'term' => $term,
            'limit' => $limit,
            'page_src' => $page_src,
            'cityId' => $cityId,
            'lang' => $lang
        );
        
        $resp = $this->get('ApiAutocompleteServices')->getHotelAutocompleteQuery($options);
        
        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
    
    //flight autocomplete 
    public function flightAutocompleteSearchAction() 
    {
        // specify required fields
        $requirements = array(
            'term'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $term = ( isset( $criteria['term'] ) && $criteria['term'] ) ? $criteria['term'] : '';
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 50 ) $limit = 50;
        
        $options = array(
            'term' => $term, 
            'limit' => $limit  
        );
        $resp = $this->get('ApiAutocompleteServices')->getFlightAutocompleteQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function searchLocalityAction()
    {
        // specify required fields
        $requirements = array(
            'term'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $term = ( isset( $criteria['term'] ) && $criteria['term'] ) ? $criteria['term'] : '';
        $lang = $this->LanguageGet();
        $countryCode = ( isset( $criteria['countryCode'] ) && $criteria['countryCode'] ) ? $criteria['countryCode'] : '';
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 50 ) $limit = 50;

        $options = array(
            'countryCode' => $countryCode, 
            'term' => $term, 
            'limit' => $limit, 
            'lang' => $lang,
            'route' => 'mobile'
        );
        $resp = $this->get('ApiAutocompleteServices')->getSearchLocalityQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function searchThingsToDoAction()
    {
        // specify required fields
        $requirements = array(
            'term'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $term = ( isset( $criteria['term'] ) && $criteria['term'] ) ? $criteria['term'] : '';
        $lang = ( isset( $criteria['lang'] ) && $criteria['lang'] ) ? $criteria['lang'] : 'en';
        $from = ( isset( $criteria['from'] ) && $criteria['from'] ) ? $criteria['from'] : 0;
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 10 ) $limit = 10;

        $options = array(
            'limit' => $limit,
            'from' => $from,
            'lang' => $lang,
            'term' => $term,
            'route' => 'mobile'
        );
        $resp = $this->get('ApiAutocompleteServices')->getThingsToDoAutocompleteQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function searchChannelAction()
    {
        // specify required fields
        $requirements = array(
            'term'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $term = ( isset( $criteria['term'] ) && $criteria['term'] ) ? $criteria['term'] : '';
        $lang = ( isset( $criteria['lang'] ) && $criteria['lang'] ) ? $criteria['lang'] : 'en';
        $city = ( isset( $criteria['city'] ) && $criteria['city'] ) ? $criteria['city'] : '';
        $contryC = ( isset( $criteria['contryC'] ) && $criteria['contryC'] ) ? $criteria['contryC'] : '';
        $cityId = ( isset( $criteria['cityId'] ) && $criteria['cityId'] ) ? $criteria['cityId'] : 0;
        $type = ( isset( $criteria['type'] ) && $criteria['type'] ) ? $criteria['type'] : 0;
        $state = ( isset( $criteria['state'] ) && $criteria['state'] ) ? $criteria['state'] : '';

        $options = array(
            'term' => $term,
            'lang' => $lang,
            'city' => $city,
            'contryC' => $contryC,
            'cityId' => $cityId,
            'type' => $type,
            'state' => $state,
            'route' => 'mobile'
        );
        $resp = $this->get('ApiAutocompleteServices')->getChannelAutocompleteQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
    
}
