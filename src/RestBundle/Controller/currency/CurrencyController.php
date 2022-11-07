<?php

namespace RestBundle\Controller\currency;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use RestBundle\Controller\TTRestController;

class CurrencyController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request    = Request::createFromGlobals();
        $this->translator = $this->get('translator');
    }

    /**
     * Method GET
     * Get FBC & SBC
     *
     * @return response
     */
    public function getFbcSbcAction()
    {
        $results['amountFBC'] = $this->container->getParameter('FBC_CODE');
        $results['amountSBC'] = $this->container->getParameter('SBC_CODE');

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Currency List
     *
     * @return response
     */
    public function getCurrencyListAction()
    {
        $result['currency'] = $this->get('CurrencyService')->listCurrencies();

        if (!$result['currency']) throw new HttpException(422, $this->translator->trans('Currency list is not available'));

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Currency Conversion Rate
     *
     * @param $fromCurrency
     * @param $toCurrency
     * @return response
     */
    public function currencyConversionRateAction($fromCurrency, $toCurrency)
    {
        if (trim($fromCurrency) == '' || trim($toCurrency) == '') {
            throw new HttpException(422, $this->translator->trans('Currency code is invalid'));
        }

        $result['conversionRate'] = $this->get('CurrencyService')->getConversionRate($fromCurrency, $toCurrency);

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Exchange Amount
     *
     * @return Response
     */
    public function exchangeAmountAction()
    {
        // specify required fields
        $requirements = array(
            'fromCurrency',
            'toCurrency',
            'amount'
        );

        // fech post json data
        $post = $this->fetchRequestData($requirements);

        if (trim($post['fromCurrency']) == '' || trim($post['toCurrency']) == '') {
            throw new HttpException(422, $this->translator->trans('Invalid Currency.'));
        }
        if (!$post['amount']) {
            throw new HttpException(422, $this->translator->trans('Invalid Amount.'));
        }

        $result['convertedAmount'] = $this->get('CurrencyService')->exchangeAmount($post['amount'], $post['fromCurrency'], $post['toCurrency']);

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }
}