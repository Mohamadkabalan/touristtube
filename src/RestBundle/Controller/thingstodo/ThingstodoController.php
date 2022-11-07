<?php

namespace RestBundle\Controller\thingstodo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Controller\TTRestController;
use RestBundle\Model\RestBookingResponseVO;

class ThingstodoController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request             = Request::createFromGlobals();
        $this->utils               = $this->get('app.utils');
    }

    /**
     * This method will be called by our TT Rest API. It will return the thingstodo divisions and available media if any.
     *
     * @param integer  $ttdId
     * @param integer  $categoryId
     * @param integer  $divisionId
     * @param boolean  $withSubDivisions
     * @return object  $results response
     */
    public function divisionsAction( $ttdId )
    {        
        $request = Request::createFromGlobals();
        $categoryId       = $request->get('categoryId', null);
        $divisionId       = $request->get('divisionId', null);
        $withSubDivisions = $request->get('withSubDivisions', false);

        $results = $this->get('ThingsToDoServices')->getThingstodoDivisions($ttdId, $categoryId, $divisionId, $withSubDivisions);
        
        if ($results) {
            return $results;
        } else {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        }
    }

    //Controller for Things To Do in Region
    public function thingstodoRegionAction() {
        $options = array(
            'lang' => $this->LanguageGet(),
            'show_main' => null
        );
        $ttdrRep = $this->get('ApiDiscoverServices')->thingsTodoRegionQuery( $options );
        return $this->convertToJson($ttdrRep);
    }

    public function thingstodoCountryAction() 
    {
        // specify required fields
        $requirements = array(
            'region_id'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);
        
        $region_id = ( isset( $criteria['region_id'] ) && $criteria['region_id'] ) ? intval($criteria['region_id']) : 0;
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 50 ) $limit = 50;
        $page = ( isset( $criteria['page'] ) && $criteria['page'] ) ? intval($criteria['page']) : 0;
        if( $page > 50 ) $page = 50;

        $options = array(
            'limit' => $limit,
            'page' => $page,
            'show_main' => null,
            'lang' => $this->LanguageGet(),
            'parent_id' => $region_id
        );

        $ttdCRep = $this->get('ApiDiscoverServices')->thingsTodoCountryQuery( $options );
        return $this->convertToJson($ttdCRep);
    }

    public function thingstodoSearchAction()
    {
        // specify required fields
        $requirements = array(
            'country_id'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $parent_id = ( isset( $criteria['country_id'] ) && $criteria['country_id'] ) ? intval($criteria['country_id']) : 0;
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 50 ) $limit = 50;
        $page = ( isset( $criteria['page'] ) && $criteria['page'] ) ? intval($criteria['page']) : 0;
        if( $page > 50 ) $page = 50;
        
        $Lang = $this->LanguageGet();

        $options = array(
            'parent_id' => $parent_id,
            'limit' => $limit,
            'page' => $page,
            'show_main' => null,
            'orderby' => 'orderDisplay',
            'order' => 'd',
            'lang' => $Lang
        );

        $ttdCRep = $this->get('ApiDiscoverServices')->thingsTodoSearchQuery( $options );
        $res = $this->convertToJson($ttdCRep);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function thingstodoDetailsAction()
    {
        // specify required fields
        $requirements = array(
            'id'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $id = ( isset( $criteria['id'] ) && $criteria['id'] ) ? intval($criteria['id']) : 0;
        $limit = ( isset( $criteria['limit'] ) && $criteria['limit'] ) ? intval($criteria['limit']) : 10;
        if( $limit > 50 ) $limit = 50;
        $page = ( isset( $criteria['page'] ) && $criteria['page'] ) ? intval($criteria['page']) : 0;
        if( $page > 50 ) $page = 50;
        
        $Lang = $this->LanguageGet();

        $options = array(
            'limit' => $limit,
            'page' => $page,
            'parent_id' => $id,
            'orderby' => 'orderDisplay',
            'order' => 'd',
            'lang' => $Lang
        );

        $ttdCRep = $this->get('ApiDiscoverServices')->thingsTodoDetailsQuery( $options );
        $res = $this->convertToJson($ttdCRep);
        return $res;
    }
}
