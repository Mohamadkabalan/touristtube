<?php

namespace RestBundle\Controller\countries;

use Symfony\Component\HttpFoundation\Response;
use RestBundle\Controller\TTRestController;

class CountriesController extends TTRestController
{

    /**
     * This method returns a list of all countries sorted by name
     *
     * @return A json that contains the id, the ISO-2 code and the name of the country
     */
    public function getCountriesAction()
    {
        $return = $this->get('CmsCountriesServices')->getCountries();

        if (empty($return)) {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        }

        return $return;
    }

    /**
     * This method returns a list of dialing codes of all countries sorted by name
     *
     * @return json data
     */
    public function getCountriesDialingCodesAction()
    {
        $return = $this->get('CmsCountriesServices')->getCountriesDialingCodes();

        if (empty($return)) {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        }

        return $return;
    }
}