<?php

namespace RestBundle\Controller\restaurant;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Controller\TTRestController;
use RestBundle\Model\RestBookingResponseVO;

class RestaurantController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request             = Request::createFromGlobals();
        $this->utils               = $this->get('app.utils');
    }

    /**
     * This method will be called by our TT Rest API. It will return the restaurant divisions and available media if any.
     *
     * @param integer  $restaurantId
     * @param integer  $categoryId
     * @param integer  $divisionId
     * @param boolean  $withSubDivisions
     * @return object  $results response
     */
    public function divisionsAction( $restaurantId )
    {        
        $request = Request::createFromGlobals();
        $categoryId       = $request->get('categoryId', null);
        $divisionId       = $request->get('divisionId', null);
        $withSubDivisions = $request->get('withSubDivisions', false);
        
        $results = $this->get('RestaurantServices')->getRestaurantDivisions( $restaurantId, $categoryId, $divisionId, $withSubDivisions );
        
        if ($results) {
            return $results;
        } else {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        }
    }
}
