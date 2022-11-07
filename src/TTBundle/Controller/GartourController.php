<?php

namespace TTBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GartourController extends DefaultController
{

    public function staticDataAction()
    {
        $request              = Request::createFromGlobals();
        $serviceType          = $request->query->get('serviceType', '');
//        print_r($request->request);
        $static_data_response = $this->get('GartourServices')->getStaticDataRequest(array(
            'serviceType' => $serviceType));
        $res                  = new Response();
        $res->setContent($static_data_response);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function serviceListAction()
    {
        $request               = Request::createFromGlobals();
        $service_list_response = $this->get('GartourServices')->getServiceListRequest();
        $res                   = new Response();
        $res->setContent($service_list_response);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function serviceInfoAction()
    {
        $request               = Request::createFromGlobals();
        $serviceId             = $request->query->get('serviceId', 3030);
        $service_info_response = $this->get('GartourServices')->getServiceInfoRequest(array(
            'serviceId' => $serviceId));
        $res                   = new Response();
        $res->setContent($service_info_response);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function pricesAvailabilityAction()
    {
        $request                      = Request:: createFromGlobals();
        $prices_availability_response = $this->get('GartourServices')->getServicesPricesAndAvailabilityRequest();
        $res                          = new Response();
        $res->setContent($prices_availability_response);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function atiAction()
    {
        $request               = Request::createFromGlobals();
        $service_info_response = $this->get('GartourServices')->atiRequest();
        $res                   = new Response();
        $res->setContent($service_info_response);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }
}