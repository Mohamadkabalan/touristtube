<?php

namespace NewFlightBundle\vendors\sabre;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{

    public function __construct(ContainerInterface $container)
    {
        $this->{'HTTP_TEST_URL'}      = $container->getParameter('flights')['vendors']['sabre']['HTTP_TEST_URL'];
        $this->{'HTTP_TEST_USERNAME'} = $container->getParameter('flights')['vendors']['sabre']['HTTP_TEST_USERNAME'];
        $this->{'HTTP_TEST_PASSWORD'} = $container->getParameter('flights')['vendors']['sabre']['HTTP_TEST_PASSWORD'];

        $this->{'HTTP_PROD_URL'}      = $container->getParameter('flights')['vendors']['sabre']['HTTP_PROD_URL'];
        $this->{'HTTP_PROD_USERNAME'} = $container->getParameter('flights')['vendors']['sabre']['HTTP_PROD_USERNAME'];
        $this->{'HTTP_PROD_PASSWORD'} = $container->getParameter('flights')['vendors']['sabre']['HTTP_PROD_PASSWORD'];

        $this->{'TEST_MODE'}        = $container->getParameter('flights')['vendors']['sabre']['TEST_MODE'];
        $this->{'HTTP_AUTH_METHOD'} = $container->getParameter('flights')['vendors']['sabre']['HTTP_AUTH_METHOD'];

        $this->{'PROJECTDOMAIN'}        = $container->getParameter('flights')['vendors']['sabre']['PROJECTDOMAIN'];
        $this->{'PROJECTPCC'}           = $container->getParameter('flights')['vendors']['sabre']['PROJECTPCC'];
        $this->{'PROJECTIPCC_TEST'}     = $container->getParameter('flights')['vendors']['sabre']['PROJECTIPCC_TEST'];
        $this->{'PROJECTIPCC_PROD'}     = $container->getParameter('flights')['vendors']['sabre']['PROJECTIPCC_PROD'];
        $this->{'PROJECTPASSWORD_TEST'} = $container->getParameter('flights')['vendors']['sabre']['PROJECTPASSWORD_TEST'];
        $this->{'PROJECTPASSWORD_PROD'} = $container->getParameter('flights')['vendors']['sabre']['PROJECTPASSWORD_PROD'];
        $this->{'PROJECTUSERNAME_TEST'} = $container->getParameter('flights')['vendors']['sabre']['PROJECTUSERNAME_TEST'];
        $this->{'PROJECTUSERNAME_PROD'} = $container->getParameter('flights')['vendors']['sabre']['PROJECTUSERNAME_PROD'];
        $this->{'PARTY_ID_FROM'}        = $container->getParameter('flights')['vendors']['sabre']['PARTY_ID_FROM'];
        $this->{'PARTY_ID_TO'}          = $container->getParameter('flights')['vendors']['sabre']['PARTY_ID_TO'];
        $this->{'COMPANYNAME'}          = $container->getParameter('flights')['vendors']['sabre']['COMPANYNAME'];

        $this->{'PRODUCTION_SERVER'}    = ($container->hasParameter('ENVIRONMENT') && $container->getParameter('ENVIRONMENT') == 'production');

        $this->{'PAUSE_BETWEEN_RETRIES_SECS'}   = $container->getParameter('PAUSE_BETWEEN_RETRIES_SECS');
        $this->{'TIME_LIMIT_MINS'}              = $container->getParameter('TIME_LIMIT_MINS');
        $this->{'MAX_API_CALL_ATTEMPTS'}        = $container->getParameter('MAX_API_CALL_ATTEMPTS');
        $this->{'ATTEMPT_NUMBER'}               = $container->getParameter('ATTEMPT_NUMBER');

        $this->{'CHECK_AIRLINE_LOCATORS'}   = $container->getParameter('CHECK_AIRLINE_LOCATORS');
        $this->{'CHECK_FIRST_SEGMENT_ONLY'} = $container->getParameter('CHECK_FIRST_SEGMENT_ONLY');
        $this->{'FETCH_AIRLINE_LOCATORS'}   = $container->getParameter('FETCH_AIRLINE_LOCATORS');
        $this->{'DISCOUNT'} = $container->getParameter('flights')['discount'];
        $this->{'DEFAULT_CURRENCY'} = $container->getParameter('flights')['default_currency'];
        $this->{'CURRENCY_PCC'} = $container->getParameter('flights')['currency_pcc'];

        $this->{'ENABLE_CANCELATION'} = $container->getParameter('flights')['enable_cancelation'];
        $this->{'ENABLE_REFUNDABLE'} = $container->getParameter('flights')['enable_refundable'];

    }
}
