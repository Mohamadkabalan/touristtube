<?php

namespace TTBundle\Services;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Entity\Currency;

class CurrencyService
{
    protected $currencyUrl   = 'https://openexchangerates.org/api';
    protected $app_id        = 'cb77e48daec34fc49a968a42d10c711f';
    private $refreshTimeSpan = '+30 minutes';
    protected $currencyAction;
    protected $em;
    protected $utils;
    private $date;

    public function __construct(EntityManager $em, Utils $utils)
    {
        $this->em    = $em;
        $this->utils = $utils;
        $this->date  = new \DateTime();
    }

    public function listCurrencies()
    {
        $currencies    = array();
        $getCurrencies = $this->em->getRepository('TTBundle:CurrencyRate')->findBy(array('used' => 1), array('displayOrder' => 'asc', 'currencyName' => 'asc'));
        foreach ($getCurrencies as $key => $currency) {
            $currencies[$key]['name']         = $currency->getCurrencyName();
            $currencies[$key]['symbol']       = $currency->getSymbol();
            $currencies[$key]['code']         = $currency->getCurrencyCode();
            $currencies[$key]['rate']         = $currency->getCurrencyRate();
            $currencies[$key]['top_currency'] = $currency->isTopCurrency();

            if ($currencies[$key]['symbol'] == '$') {
                $currencies[$key]['symbol'] = $currencies[$key]['code'];
                switch ($currencies[$key]['symbol']) {
                    case 'ARS':
                        $currencies[$key]['symbol'] = 'AR$';
                        break;
                    case 'BRL':
                        $currencies[$key]['symbol'] = 'R$';
                        break;
                    case 'CLP':
                        $currencies[$key]['symbol'] = 'CL$';
                        break;
                    case 'HKD':
                        $currencies[$key]['symbol'] = 'HK$';
                        break;
                    case 'SGD':
                        $currencies[$key]['symbol'] = 'S$';
                        break;
                    case 'USD':
                        $currencies[$key]['symbol'] = 'US$';
                        break;
                }
            }
        }
        return $currencies;
    }

    public function getCurrencySymbol($currency_code)
    {
        $currencies    = array();
        $getCurrencies = $this->em->getRepository('TTBundle:CurrencyRate')->findBy(array('currencyCode' => $currency_code));
        foreach ($getCurrencies as $key => $currency) {
            $currencies['name']         = $currency->getCurrencyName();
            $currencies['symbol']       = $currency->getSymbol();
            $currencies['code']         = $currency->getCurrencyCode();
            $currencies['rate']         = $currency->getCurrencyRate();
            $currencies['top_currency'] = $currency->isTopCurrency();

            if ($currencies['symbol'] == '$') {
                $currencies['symbol'] = $currencies['code'];
                switch ($currencies['symbol']) {
                    case 'ARS':
                        $currencies['symbol'] = 'AR$';
                        break;
                    case 'BRL':
                        $currencies['symbol'] = 'R$';
                        break;
                    case 'CLP':
                        $currencies['symbol'] = 'CL$';
                        break;
                    case 'HKD':
                        $currencies['symbol'] = 'HK$';
                        break;
                    case 'SGD':
                        $currencies['symbol'] = 'S$';
                        break;
                    case 'USD':
                        $currencies['symbol'] = 'US$';
                        break;
                }
            }
        }
        return $currencies;
    }

    public function getCurrencyFullName()
    {
        $output               = array();
        $this->currencyAction = 'currencies.json';
        $currencyFullName     = file_get_contents("$this->currencyUrl/$this->currencyAction?app_id=$this->app_id");
        $json                 = json_decode($currencyFullName, true);
        foreach ($json as $key => $value1) {
            $output[$key] = $value1;
            $CurrencyRate = $this->em->getRepository('TTBundle:CurrencyRate')->findBycurrencyCode($key);
            foreach ($CurrencyRate as $value) {
                $value->setCurrencyName($value1);
                $value->setLastUpdate($this->date);
                $this->em->persist($value);
                $this->em->flush();
            }
        }
        return $output;
    }

    public function updateCurrencyRates()
    {
        $this->currencyAction = 'latest.json';
        $currencyRate         = file_get_contents("$this->currencyUrl/$this->currencyAction?app_id=$this->app_id");
        if (!empty($currencyRate)) {
            $rates = json_decode($currencyRate, true);

            $this->em->getRepository('TTBundle:CurrencyRate')->updateCurrencyRates($rates['rates']);
        }
    }

    public function refreshCurrencyRatesIfNeeded($currencyCode)
    {
        $currencyRateLastUpdatedTime = $this->em->getRepository('TTBundle:CurrencyRate')->findOneBycurrencyCode($currencyCode);

        if ($currencyRateLastUpdatedTime) {
            $lastUpdatedTime = $currencyRateLastUpdatedTime->getLastUpdate();
            if ($this->date >= $lastUpdatedTime->modify($this->refreshTimeSpan)) {
                $this->updateCurrencyRates();
            }
        }
    }

    /**
     * Convert a given amount from a currency to another
     *
     * @param double $amount
     * @param string $from  (Currency code ISO3)
     * @param string $to    (Currency code ISO3)
     * @return number|number[]
     */
    public function exchangeAmount($amount, $from, $to)
    {
        if ($from == $to)
			return $amount;
        
        return $this->currencyConvert($amount, $this->getConversionRate($from, $to));
    }

    /**
     * Get Conversion Rate from currency 'from' to currency 'to'
     *
     * @param string $from
     * @param string $to
     * @return number
     */
    public function getConversionRate($from, $to)
    {
        if ($to == $from) return 1;

        $this->refreshCurrencyRatesIfNeeded($from);

        $rateFrom = 1;
        $rateTo   = 1;

        $currencyRateFrom = $this->em->getRepository('TTBundle:CurrencyRate')->findOneBycurrencyCode($from);

        if ($currencyRateFrom) $rateFrom = $currencyRateFrom->getCurrencyRate();

        $currencyRateTo = $this->em->getRepository('TTBundle:CurrencyRate')->findOneBycurrencyCode($to);

        if ($currencyRateTo) $rateTo = $currencyRateTo->getCurrencyRate();

        $conversionRate = ($rateTo / $rateFrom);

        return $conversionRate;
    }

    /**
     * Convert a given amount with the given rate
     *
     * @param unknown $price
     * @param unknown $conversionRate
     * @return number|number[]
     */
    public function currencyConvert($price, $conversionRate)
    {
        if (is_array($price)) {
            $convertedPrice = array();
            foreach ($price as $priceIndex => $amount) {
                $convertedPrice[$priceIndex] = ($amount * $conversionRate);
            }
        } else {
            $convertedPrice = ($price * $conversionRate);
        }

        return $convertedPrice;
    }
}
