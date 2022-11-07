<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Datetime;

/**
 * Controller receiving actions related to the ajaxs
 */
class CorpoAdminAjaxController extends CorpoAdminController
{
    /**
     * controller action for getting a list of cities
     *
     * @return TWIG
     */
    public function cityComboAction(Request $request, $countryCode = '')
    {
        $res = $this->get('CorpoAdminServices')->getCityCombo($request, $countryCode);

        return $res;
    }

    /**
     * controller action for getting a list of countries
     *
     * @return TWIG
     */
    public function countryComboAction(Request $request)
    {
        $res = $this->get('CmsCountriesServices')->getCountryCombo($request);

        return $res;
    }

    /**
     * controller action for getting a list of currencies
     *
     * @return TWIG
     */
    public function currencyComboAction(Request $request)
    {
        $res = $this->get('CorpoAdminServices')->getCurrencyCombo($request);
        
        return $res;
    }

    /**
     * action for city information
     *
     * @return TWIG
     */
    public function getCityInfoAction(Request $request)
    {
        $post    = $request->request->all();
        $res = $this->get('CorpoAdminServices')->getCityInfo($post['cityId']);
        
        $response = new Response(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
