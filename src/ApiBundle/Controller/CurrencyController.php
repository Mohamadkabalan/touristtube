<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyController extends Controller {

    public function getCurrencyListAction() {
        $response = new JsonResponse();

        $currencyList = $this->get('CurrencyService')->listCurrencies();

        if (!$currencyList) {
            $response->setData(array(
                'data' => '',
                'status' => '202',
                'response_message' => 'Currency list is not available'
            ));
        }

        $response->setData(array(
            'data' => $currencyList,
            'status' => '200',
            'message' => 'Success'
        ));

        return $response;
    }
    
    public function currencyConversionRateAction() {
        $request = $this->getRequest();
        $response = new JsonResponse();

        $from = $request->request->get('from', '');
        $to = $request->request->get('to', '');

		$conversionRate = 1;

		if ($to != $from)
		    $conversionRate = $this->get('CurrencyService')->getConversionRate($to, $from);
        $response->setData(array(
            'conversion_rate' => $conversionRate,
            'status' => '200',
            'message' => 'Success'
        ));

        return $response;
    }
}
