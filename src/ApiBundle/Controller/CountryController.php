<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CountryController extends DefaultController {

    public function getCountryListAction() {
        $response = new JsonResponse();

        $countryList = $this->getDoctrine()->getRepository('TTBundle:CmsCountries')->findBy(array(), array('name' => 'asc'));

        if (!$countryList) {
            $response->setData(array(
                'data' => '',
                'status' => '202',
                'response_message' => 'Country list is not available'
            ));
        }

        $countries = array();

        foreach ($countryList as $country) {
            $countries[] = array('id' => $country->getId(), 'code' => $country->getCode(), 'iso3' => $country->getIso3() , 'name' => $country->getName(), 'dialing_code' => '+' . $country->getDialingCode(), 'flag_icon' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/flag-icons/' . $country->getFlagIcon()));
        }

        $response->setData(array(
            'data' => $countries,
            'status' => '200',
            'message' => 'Success'
        ));

        return $response;
    }

}
