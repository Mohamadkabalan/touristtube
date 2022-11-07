<?php

namespace RestBundle\Controller\cities;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Controller\TTRestController;

class CitiesController extends TTRestController
{

    /**
     * The __construct when we make a new instance of CitiesController class.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request = Request::createFromGlobals();
    }

    /**
     * This method returns the matching items of the term searched.
     *
     * @param String $term The searched term
     * @return Json data
     */
    public function searchAction()
    {
        $term = $this->request->query->get('term');

        if (!isset($term) && empty($term)) {
            throw new HttpException(403, $this->translator->trans("Missing term parameter."));
        }

        $return = $this->get('CitiesServices')->getSearchSuggestions($term);

        if (empty($return)) {
            $response = new Response();
            $response->setStatusCode(204, $this->translator->trans("No data found."));
            return $response;
        }

        return $return;
    }
}
