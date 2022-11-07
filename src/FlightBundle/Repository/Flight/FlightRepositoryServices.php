<?php

namespace FlightBundle\Repository\Flight;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use FlightBundle\Controller\FlightController;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use FlightBundle\Entity\PassengerNameRecord;
use FlightBundle\Entity\PassengerDetail;
use FlightBundle\Entity\FlightDetail;
use FlightBundle\Entity\FlightInfo;
use PaymentBundle\Entity\Payment;
use FlightBundle\Form\Type\PassengerNameRecordType;
use Symfony\Component\DependencyInjection\Container;
use PaymentBundle\Model\Payment as PaymentObj;

/**
 * Description of FlightRepositoryServices this is a service responsible to make queries to the database using entities, 
 * to prevent repetition of the queries in the services and controllers, and to make any update easier.  
 *
 * @author para-soft7
 */
class FlightRepositoryServices extends EntityRepository
{
    private $container;
    private $flightController;
    private  $j;
    public function __construct(EntityManager $em, Container $container, FlightController $flightController)
    {
        $this->em = $em;
        $this->container = $container;
        $this->flightController = $flightController;
        $this->j = 0;
    }

    /**
     * findAirline is a function tat get airline information based on the airline code 
     * @param type $code of the airline
     * 
     */
    public function findAirline($code)
    {

        $airline = $this->em->getRepository('TTBundle:Airline')->findOneByCode($code);

        return $airline;
    }

    /**
     * findAirport is a function tat get Airport information based on the findAirport code 
     * @param type $code of the Airpoty
     * 
     */
    public function findAirport($code)
    {

        $airport = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($code);

        return $airport;
    }
    /**
     * findAirport is a function tat get Airport information based on the findAirport code
     * @param type $code of the Airpoty
     *
     */
/*    public function getFlightsDiscount()
    {
        try {
            $date = date("Y-m-d H:i:s");
            $con = $this->em->getConnection();
            $sql = "SELECT * FROM flights_discount where '$date' between date_from and date_to";
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();

            if ($res) {

                return $res[0]['amount'];
            } else {

                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }*/
    /**
     * FLightCabinFinder is a function tat get FLightCabin information based on the FLightCabin code 
     * @param type $code of the cabin
     * 
     */
    public function FlightCabinFinder($code)
    {

        $flightCabin = $this->em->getRepository('FlightBundle:FlightCabin')->findOneByCode($code);

        return $flightCabin;
    }

    /**
     * findCountry is a function that get Country code based from Aiport code
     * @param type $code of the Airport
     *
     */
    public function findCountry($code)
    {

        $country = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($code);

        return $country;
    }

    
    public function addFlightsPassengersPNR($requestData, $passengersArray)
    {
	    $passengerNameRecord = new PassengerNameRecord();
	    $passengerNameRecord = $this->addFlightDetails($passengerNameRecord, $requestData);
	    $passengerNameReocrd = $this->addPassengerDetails($passengerNameRecord, $passengersArray);
        return $passengerNameRecord;
    }
    
    /**
     * Adds Flight Details from PassengerNameRecord object
     * params object $passengerNameRecord, object $request
     * @return object $passengerNameRecord
     **/
    public function addFlightDetails($passengerNameRecord, $requestData)
    {


	    $segmentN = $requestData->getSegmentNumber();
	    $flightType = $requestData->getFlightType();
	    $departureDateTime = $requestData->getDepartureDateTime();
	    $arrivalDateTime = $requestData->getArrivalDateTime();
	    $resBookDesignCode = $requestData->getResBookDesigCode();
	    $destinationLocation = $requestData->getDestinationLocation();
	    $airlineCode = $requestData->getAirlineCode();
	    $flightNumber = $requestData->getFlightNumber();
	    $operatingAirlineCode = $requestData->getOperatingAirlineCode();
	    $originLocation = $requestData->getOriginLocation();
	    $stopIndicator = $requestData->getStopIndicator();
	    $stopDuration = $requestData->getStopDuration();
	    $cabinCode = $requestData->getCabinCode();
	    $flightDuration = $requestData->getFlightDuration();
	    $adt = $requestData->getADT();
        $fareBasisCode = $requestData->getFareBasisCode();
        $departure_terminal_id = $requestData->getOriginTerminalId();
        $arrival_terminal_id = $requestData->getDestinationTerminalId();
        $elapsedTime = $requestData->getElapsedTime();

        //for ($i = 0; $i < $requestData->getTotalSegments(); $i++) {
       $segmentNumber = 0;
	   
	    foreach($requestData->getTotalSegments() as $i => $segId){
	    	
	    	$flightDetail = new FlightDetail();
	    	// $this->j = $this->j + 1;
			$segmentNumber++;
	    	$flightDetail->setSegmentNumber($segmentNumber);
	    	$flightDetail->setType($flightType[$i]);
	    	$flightDetail->setDepartureDateTime($departureDateTime[$i]);
	    	$flightDetail->setArrivalDateTime($arrivalDateTime[$i]);
	    	$flightDetail->setResBookDesignCode($resBookDesignCode[$i]);
	    	$flightDetail->setArrivalAirport($destinationLocation[$i]);
	    	$flightDetail->setAirline($airlineCode[$i]);
	    	$flightDetail->setFlightNumber($flightNumber[$i]);
	    	$flightDetail->setOperatingAirline($operatingAirlineCode[$i]);
	    	$flightDetail->setDepartureAirport($originLocation[$i]);
	    	$flightDetail->setStopIndicator($stopIndicator[$i]);
            $flightDetail->setDepartureTerminalId($departure_terminal_id[$i]);
            $flightDetail->setArrivalTerminalId($arrival_terminal_id[$i]);
            $flightDetail->setArrivalTerminalId($arrival_terminal_id[$i]);
            $elapsedTimeArray=$this->container->get('app.utils')->mins_to_duration($elapsedTime[$i]);
            $elapsedTimeHours=$elapsedTimeArray['hours'];
            $elapsedTimeMinutes=$elapsedTimeArray['minutes'];
            $flightDetail->setElapsedTime($elapsedTimeHours."h ".$elapsedTimeMinutes."m");

	    	if($stopIndicator[$i]) $flightDetail->setStopDuration($stopDuration[$i]);
	    	
	    	$flightDetail->setCabin($cabinCode[$i]);
	    	$flightDetail->setFlightDuration($flightDuration[$i]);

            if (isset($adt[$i]['fare_calc_line'])){
                $flightDetail->setFareCalcLine($adt[$i]['fare_calc_line']);
            }

            if (isset($adt[$i]['leaving_baggage_info'])){
                $flightDetail->setLeavingBaggageInfo($adt[$i]['leaving_baggage_info']);
            }

            if (isset($adt[$i]['returning_baggage_info'])){
                $flightDetail->setReturningBaggageInfo($adt[$i]['returning_baggage_info']);
	    	}

            $flightDetail->setFareBasisCode($fareBasisCode[$i]);

	    	$passengerNameRecord->addFlightDetail($flightDetail);
	    	
	    }

	    return $passengerNameRecord;
    }
    
    /**
     * Add Passenger Details from PassengerNameRecord object
     * @param object $passengerNameRecord, array $passengersArray
     * @return object $passengerNameRecord
     **/
    public function addPassengerDetails($passengerNameRecord, $passengersArray)
    {
	    $x = 2;
        
        foreach ($passengersArray as $passenger) {
            $passengerDetail = new PassengerDetail();
            $passengerDetail->setType($passenger['type']);
            $passengerDetail->setFareCalcLine($passenger['fare_calc_line']);
            $passengerDetail->setLeavingBaggageInfo($passenger['leaving_baggage_info']);
            $passengerDetail->setReturningBaggageInfo($passenger['returning_baggage_info']);
            $passengerDetail->setTicketRph('0'.$x++);
            if (isset($passenger['passportNo'])) $passengerDetail->setPassportNo($passenger['passportNo']);
            if (isset($passenger['idNo'])) $passengerDetail->setIdNo($passenger['idNo']);
            if (isset($passenger['passportExpiry']) && is_array($passenger['passportExpiry'])) {
                $passengerDetail->setPassportExpiry(implode("-", $passenger['passportExpiry']));
            }
            /* if(isset($passenger['passportIssueCountry'])) $passengerDetail->setPassportIssueCountry($this->get('CommonRepositoryServices')->cmsCountriesInfo($passenger['passportIssueCountry']));
              if(isset($passenger['passportNationalityCountry'])) $passengerDetail->setPassportNationalityCountry($this->get('CommonRepositoryServices')->cmsCountriesInfo($passenger['passportNationalityCountry'])); */
            $passengerNameRecord->addPassengerDetail($passengerDetail);
        }
        
        return $passengerNameRecord;
    }

    public function addFlightInfo($passengerNameRecord, $pnr, $requestData, $discount, $currencyPCC)
    {

        $passengerNameRecord->setPnr($pnr->getPnrId());
                    
        $pricingInfo = $pnr->getPricingInfo();
        $conversionRate = $requestData->getConversionRate();
        $currencyService = $this->container->get('CurrencyService');

        $displayBaseFare  = ($pricingInfo['EquivFare'] <= $discount) ? $pricingInfo['EquivFare'] :  ($pricingInfo['EquivFare'] - $discount);
        $displayTaxes     = $pricingInfo['Taxes'];
        $displayTotalFare = ($pricingInfo['TotalFare'] <= $discount) ? $pricingInfo['TotalFare'] : ($pricingInfo['TotalFare'] - $discount);

        if ($conversionRate != 1) {
            $equivFare = ($pricingInfo['EquivFare'] <= $discount) ? $pricingInfo['EquivFare'] : ($pricingInfo['EquivFare'] - $discount);
            $displayBaseFare  = $currencyService->currencyConvert($equivFare, $conversionRate);
            $displayTaxes     = $currencyService->currencyConvert($pricingInfo['Taxes'], $conversionRate);
            $_totalFare = ($pricingInfo['TotalFare'] <= $discount) ? $pricingInfo['TotalFare'] : ($pricingInfo['TotalFare'] - $discount);
            $displayTotalFare = $currencyService->currencyConvert($_totalFare, $conversionRate);
        }

        $flightInfo = new FlightInfo();
        $flightInfo->setBaseFare($pricingInfo['EquivFare']);
        $flightInfo->setDisplayBaseFare($displayBaseFare);
        $flightInfo->setTaxes($pricingInfo['Taxes']);
        $flightInfo->setDisplayTaxes($displayTaxes);
        $flightInfo->setPrice($pricingInfo['TotalFare']);
        $flightInfo->setDisplayPrice($displayTotalFare);
        $flightInfo->setCurrency($currencyPCC);
        $flightInfo->setDisplayCurrency($requestData->getDisplayedCurrency());
        $flightInfo->setRefundable($requestData->getRefundable());
        $flightInfo->setOneWay($requestData->getOneWay());
        $flightInfo->setMultiDestination($requestData->getMultiDestination());

        $flightInfo->setPenaltiesInfo($requestData->getPenaltiesInfo());
        $passengerNameRecord->setFlightInfo($flightInfo);
		
		return $passengerNameRecord;
    }
    
    public function addPayment($passengerNameRecord, $pnr, $requestData, $discount, $currencyPCC, $request, $validUnusedCoupon, $campaign_info, $em, $methodOneByYourEmail)
    {

        $fareBasisCodes = $pnr->getFareBasisCodes();

        foreach($passengerNameRecord->getFlightDetails() as $flightDetail){
            $airlinePnr = $pnr->getAirlinePnr();
            $flightDetail->setAirlinePnr($airlinePnr[$flightDetail->getSegmentNumber()]);
            if (isset($fareBasisCodes[$flightDetail->getSegmentNumber()])) {
                $flightDetail->setFareBasisCode($fareBasisCodes[$flightDetail->getSegmentNumber()]);
            }
        }

        $pricingInfo = $pnr->getPricingInfo();

        $conversionRate  = $requestData->getConversionRate();

        $currencyService = $this->container->get('CurrencyService');

        $displayBaseFare  = $pricingInfo['EquivFare'] - $discount;
        $displayTaxes     = $pricingInfo['Taxes'];
        $displayTotalFare = ($pricingInfo['TotalFare'] <= $discount) ? $pricingInfo['TotalFare'] : ($pricingInfo['TotalFare'] - $discount);

        if ($conversionRate != 1) {

            $displayBaseFare  = $currencyService->currencyConvert($pricingInfo['EquivFare'] - $discount, $conversionRate);
            $displayTaxes     = $currencyService->currencyConvert($pricingInfo['Taxes'], $conversionRate);
            $_totalFare = ($pricingInfo['TotalFare'] <= $discount) ? $pricingInfo['TotalFare'] : ($pricingInfo['TotalFare'] - $discount);
            $displayTotalFare = $currencyService->currencyConvert($_totalFare, $conversionRate);
        }



        $userId = 0;

        if (!$this->flightController->data['USERID']) {
            $user   = $this->container->get('CommonRepositoryServices')->cmsUsersInfo($passengerNameRecord->getEmail(), $methodOneByYourEmail);
            $userId = ($user) ? $user->getId() : 0;
        } else {
            $userId = $this->flightController->data['USERID'];
        }

        $passengerNameRecord->setUserId($userId);
        $passengerNameRecord->setStatus('SUCCESS');
        $passengerNameRecord->setCreationDate(new \DateTime("now"));
        $passengerNameRecord->setIsCorporateSite($this->container->get('app.utils')->isCorporateSite());

        $uuid            = $this->container->get('app.utils')->GUID();

        $paymentStatus   = ($this->flightController->data['is_corporate_account'] ? '14' : '');
        $responseCode    = ($this->flightController->data['is_corporate_account'] ? '14000' : '00101');
        $responseMessage = ($this->flightController->data['is_corporate_account'] ? 'BYPASSED' : 'Pending');


        $payment = new Payment();

        $payment->setStatus($paymentStatus);
        $payment->setUuid($uuid);
        $payment->setUserId($userId);
        $payment->setMerchantReference(substr($uuid, 0, 20));
        $payment->setResponseCode($responseCode);
        $payment->setResponseMessage($responseMessage);
        $payment->setType('flights');
        $payment->setLanguage('en');
        $payment->setUserAgent($request->headers->get('User-Agent'));
        $payment->setCustomerIp($this->container->get('app.utils')->getUserIP());
        $payment->setCreationDate(new \DateTime("now"));
        $payment->setUpdatedDate(new \DateTime("now"));
        $payment->setPassengerNameRecord($passengerNameRecord);

        $payment->setCurrency($currencyPCC);

        $payment->setOriginalAmount($pricingInfo['TotalFare']);
        $payment->setDisplayCurrency($requestData->getDisplayedCurrency());

        $payment->setDisplayOriginalAmount($displayTotalFare);

        $paymentCurrencyInfo = $this->container->get('PaymentServiceImpl')->getPaymentCurrencyInfo($displayTotalFare,$requestData->getDisplayedCurrency());
        $payment->setAmountFBC($paymentCurrencyInfo['amountFBC']);
        $payment->setAmountSBC($paymentCurrencyInfo['amountSBC']);
        $payment->setAccountCurrencyAmount($paymentCurrencyInfo['accountCurrencyAmount']);

        $payment->setModuleAmount($pricingInfo['TotalFare']);
        $payment->setModuleId($this->container->getParameter('MODULE_FLIGHTS'));
        $payment->setModuleCurrency($pricingInfo['CurrencyCode']);

        if ($validUnusedCoupon) {

            $discountedAmountInfo = $this->flightController->applyDiscount($campaign_info['c_discountId'], $campaign_info['currency_code'], $currencyPCC, $pricingInfo['TotalFare']);

            if ($discountedAmountInfo['status']) {
                $payment->setAmount($discountedAmountInfo['amount']);

                $discountedAmountInfo = $this->applyDiscount($campaign_info['c_discountId'], $campaign_info['currency_code'], $displayedCurrency, $displayTotalFare);
                $payment->setDisplayAmount($discountedAmountInfo['amount']);

                $payment->setCampaignId($campaign_info['c_id']);
                $payment->setCouponCode($couponCode);
            }
        } else {

            $payment->setAmount($displayTotalFare);
            $payment->setDisplayAmount($displayTotalFare);
        }

        $this->container->get('FlightServices')->addFlightLog('Saving payment and flight info for PNR:: '.$pnr->getPnrId().", UUID:: $uuid");
        //$em->persist($passengerNameRecord);
        $em->persist($payment);

        $em->flush();

        $pnr_id = $this->getPnrCodeMatch($pnr->getPnrId())->getId();
        $pnr_user_id = $this->getPnrCodeMatch($pnr->getPnrId())->getUserId();

        $payment = $this->em->getRepository('PaymentBundle:Payment')->findOneByUuid($uuid);
        $payment->setModuleTransactionId($pnr_id);

        $userArray      = $this->container->get('UserServices')->getUserDetails(array('id' => $pnr_user_id));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];
        $onAccountOrCC      = $this->container->get('CorpoAccountServices')->getCorpoAccountPaymentType($userCorpoAccountId);

        $onAccountCCType = (isset($onAccountOrCC['code'])?$onAccountOrCC['code']:'cc');


        $payment->setPaymentType($onAccountCCType);
        
        $vendors = $this->container->getParameter('payment_parameters')['vendors'][$onAccountCCType];
        $vendorClass = current($vendors);

        $payment->setPaymentProvider($vendorClass['id']);

        $em->persist($payment);

        $em->flush();

		$passengerTypePricingInfo = $pnr->getPassengerTypePricingInfo();
		
		if ($passengerTypePricingInfo)
		{
			foreach ($passengerTypePricingInfo as $passengerType => $priceQuote)
			{
				$passenger_type_data = array('module_id' => $this->container->getParameter('MODULE_FLIGHTS'), 'module_transaction_id' => $pnr_id, 'passenger_type' => $passengerType, 'price_quote' => $priceQuote);
				
				$this->container->get('TTServices')->addPassengerTypeQuote($passenger_type_data);
			}
		}
		
        $this->container->get('FlightServices')->addFlightLog('Saved payment and flight info for PNR:: '.$pnr->getPnrId()." UUID:: $uuid");
              
        $response = array();
        $response['payment'] = $payment;
        $response['uuid'] = $uuid;
        $response['responseMessage'] = $responseMessage;
        $response['responseCode'] = $responseCode;
        $response['paymentStatus'] = $paymentStatus;
        $response['displayTotalFare'] = $displayTotalFare;

        return $response;
    }
    
    public function addPaymentObj($pnr, $payment, $uuid, $requestData, $currencyPCC, $responseMessage, $responseCode, $paymentStatus, $displayTotalFare, $request)
    {

    	if ($this->flightController->data['is_corporate_account']) {

                        //Get Corporate Account ID
                        $userId             = $this->flightController->userGetID();
                        $userArray          = $this->container->get('UserServices')->getUserDetails(array('id' => $userId));
                        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

                        $getDefaultPaymentType  = $this->container->get('CorpoAccountServices')->getCorpoAccountPaymentType($userCorpoAccountId);
                        $defaultPaymentTypeCode = $getDefaultPaymentType['code'];
                    } else {

                        $defaultPaymentTypeCode = 'cc';
                    }
                    $pnrId = $payment->getPassengerNameRecord()->getId();
                    $pnrEmail = $payment->getPassengerNameRecord()->getEmail();
                    $pnrName = $payment->getPassengerNameRecord()->getFirstName();

                    $pricingInfo = $pnr->getPricingInfo();

                    $paymentObj = new PaymentObj();

//                        $module_transaction_id = rand(5, 90505050505);
                    $paymentObj->setNumber($uuid);
                    $paymentObj->setAmount($pricingInfo['TotalFare']);
                    $paymentObj->setOriginalAmount($pricingInfo['TotalFare']);
                    $paymentObj->setDisplayOriginalAmount($displayTotalFare);
                    $paymentObj->setDisplayedCurrency($requestData->getDisplayedCurrency());
                    $paymentObj->setCurrency($currencyPCC);
                    $paymentObj->setCustomerEmail($pnrEmail);
                    $paymentObj->setModuleTransactionId($pnrId);
                    $paymentObj->setCustomerFullName($pnrName);
                    $paymentObj->setCommand(PaymentObj::CMD_PROCESS_PAYMENT);
                    $paymentObj->setModuleName('flights');
                    $paymentObj->setTrxTypeId(PaymentObj::TRX_TYPE_FLIGHTS);
                    $paymentObj->setPaymentType($defaultPaymentTypeCode);
                    $paymentObj->setResponseMessage($responseMessage);
                    $paymentObj->setResponseCode($responseCode);
                    $paymentObj->setStatus($paymentStatus);
                    $paymentObj->setCustomerIp($this->container->get('app.utils')->getUserIP());
                    $paymentObj->setUserAgent($request->headers->get('User-Agent'));
                    $paymentObj->setModuleAmount($pricingInfo['TotalFare']);
                    $paymentObj->setModuleCurrency(($pricingInfo['CurrencyCode'] != '')? $pricingInfo['CurrencyCode'] : 'AED');

                    return $paymentObj;

    }
    
    public function creatPnrAPI($controller, $pnr, $pnrData, $methodOneByYourEmail, $currencyPCC, $discount)
    {

    	$pnrId = $pnr['pnrId'];
	    $passengerNameRecord = new PassengerNameRecord();
            $passengerNameRecord->setPnr($pnrId);

            $passengerNameRecord->setSpecialRequirement(preg_replace('/[^A-Za-z0-9 \-]/', '', $pnrData['passengerNameRecord']['specialRequirement']));
            $passengerNameRecord->setFirstName($pnrData['passengerNameRecord']['firstName']);
            $passengerNameRecord->setSurname($pnrData['passengerNameRecord']['surname']);

            $country = $this->container->get('CommonRepositoryServices')->cmsCountriesInfo($pnrData['passengerNameRecord']['countryOfResidence']);

            $passengerNameRecord->setCountryOfResidence($country);
            $passengerNameRecord->setEmail($pnrData['passengerNameRecord']['email']);

            $countryCode = $this->container->get('CommonRepositoryServices')->cmsCountriesInfo($pnrData['passengerNameRecord']['mobileCountryCode']);
            $passengerNameRecord->setMobileCountryCode($countryCode);

            $passengerNameRecord->setMobile($pnrData['passengerNameRecord']['mobile']);
            $passengerNameRecord->setAlternativeNumber($pnrData['passengerNameRecord']['alternativeNumber']);
            $passengerNameRecord->setStatus('SUCCESS');
            $passengerNameRecord->setCreationDate(new \DateTime("now"));

            $userId = 0;
            if (!$controller->data['USERID']) {
                $user   = $this->container->get('CommonRepositoryServices')->cmsUsersInfo($passengerNameRecord->getEmail(), $methodOneByYourEmail);
                $userId = ($user) ? $user->getId() : 0;
            } else {
                $userId = $controller->data['USERID'];
            }
            $passengerNameRecord->setUserId($userId);

            $paymentStatus   = '00';
            $responseMessage = 'Pending';
            $responseCode    = '00101';

            // $paymentStatus = ($this->data['is_corporate_account']?'14':'00');
            // $responseMessage = ($this->data['is_corporate_account']?'Success':'Pending');
            // $responseCode = ($this->data['is_corporate_account']?'14000':'00101');

            $uuid = $this->container->get('app.utils')->GUID();

            $payment = new Payment();
            $payment->setStatus($paymentStatus);
            $payment->setUuid($uuid);
            $payment->setUserId($userId);
            $payment->setMerchantReference(substr($uuid, 0, 20));
            $payment->setResponseCode($responseCode);
            $payment->setResponseMessage($responseMessage);
            $payment->setType('flights');
            $payment->setUserAgent($this->get("request")->headers->get('User-Agent'));
            $payment->setCustomerIp($this->container->get('app.utils')->getUserIP());
            $payment->setCreationDate(new \DateTime("now"));
            $payment->setUpdatedDate(new \DateTime("now"));

            $x = 2;
            foreach ($pnrData['passengerNameRecord']['passengerDetails'] as $passengerInfo) {
                $passengerDetail = new PassengerDetail();
                $passengerDetail->setType($passengerInfo['type']);
                $passengerDetail->setGender($passengerInfo['gender']);
                $passengerDetail->setFirstName($passengerInfo['firstName']);
                $passengerDetail->setSurname($passengerInfo['surname']);
                $passengerDetail->setDateOfBirth(new \DateTime($passengerInfo['dateOfBirth']['year'].'-'.$passengerInfo['dateOfBirth']['month'].'-'.$passengerInfo['dateOfBirth']['day']));
                $passengerDetail->setFareCalcLine($passengerInfo['fareCalcLine']);
                $passengerDetail->setLeavingBaggageInfo((isset($passengerInfo['leavingBaggageInfo']) && $passengerInfo['leavingBaggageInfo']) ? $passengerInfo['leavingBaggageInfo'] : '');
                $passengerDetail->setTicketRph('0'.$x++);
                $passengerDetail->setReturningBaggageInfo((isset($passengerInfo['returningBaggageInfo']) && $passengerInfo['returningBaggageInfo']) ? $passengerInfo['returningBaggageInfo'] : '');

                $passengerNameRecord->addPassengerDetail($passengerDetail);
            }
            $segmentCount = 0;
            foreach ($pnrData['passengerNameRecord']['flightDetails'] as $flight) {
                $flightDetail = new FlightDetail();
				$segmentCount++;
                $flightDetail->setSegmentNumber($segmentCount);
                $flightDetail->setType($flight['type']);
                $flightDetail->setDepartureDateTime($flight['departureDateTime']);
                $flightDetail->setArrivalDateTime($flight['arrivalDateTime']);
                $flightDetail->setArrivalAirport($flight['arrivalAirport']);
                $flightDetail->setAirline($flight['airline']);
                $flightDetail->setOperatingAirline($flight['operating_airline']);
                $flightDetail->setFlightNumber($flight['flightNumber']);
                $flightDetail->setDepartureAirport($flight['departureAirport']);
                $flightDetail->setStopIndicator($flight['stopIndicator']);
                $flightDetail->setStopDuration($flight['stopDuration']);
                $flightDetail->setCabin($flight['cabin']);
                $flightDetail->setFlightDuration($flight['flightDuration']);

                $passengerNameRecord->addFlightDetail($flightDetail);
            }

            $currencyService = null;
            $conversionRate  = 1;
            if ($pnrData['passengerNameRecord']['flightInfo']['display_currency'] != $this->currencyPCC) {
                $currencyService = $this->container->get('CurrencyService');

                $conversionRate = $currencyService->getConversionRate($currencyPCC, $pnrData['passengerNameRecord']['flightInfo']['display_currency']);
            }

            $displayBaseFare   = $currencyService->currencyConvert($pnr['pricingInfo']['EquivFare'] - $discount, $conversionRate);
            $displayTaxes      = $currencyService->currencyConvert($pnr['pricingInfo']['Taxes'], $conversionRate);
            $_totalFare = ($pnr['pricingInfo']['TotalFare'] <= $discount) ? $pnr['pricingInfo']['TotalFare'] : ($pnr['pricingInfo']['TotalFare'] - $discount);
            $displayTotalFare  = $currencyService->currencyConvert($_totalFare, $conversionRate);
            $displayedCurrency = $pnrData['passengerNameRecord']['flightInfo']['display_currency'];

            $flightInfo = new FlightInfo();

            $flightInfo->setBaseFare($pnr['pricingInfo']['EquivFare']);
            $flightInfo->setDisplayBaseFare($displayBaseFare);
            $flightInfo->setTaxes($pnr['pricingInfo']['Taxes']);
            $flightInfo->setDisplayTaxes($displayTaxes);
            $flightInfo->setPrice($pnr['pricingInfo']['TotalFare']);
            $flightInfo->setDisplayPrice($displayTotalFare);
            $flightInfo->setCurrency($this->currencyPCC);
            $payment->setCurrency($this->currencyPCC);
            $payment->setOriginalAmount($pnr['pricingInfo']['TotalFare']);
            $flightInfo->setDisplayCurrency($displayedCurrency);
            $payment->setDisplayCurrency($displayedCurrency);
            $payment->setDisplayOriginalAmount($displayTotalFare);
            $flightInfo->setRefundable($pnrData['passengerNameRecord']['flightInfo']['refundable']);
            $flightInfo->setOneWay($pnrData['passengerNameRecord']['flightInfo']['one_way']);
            $flightInfo->setMultiDestination($pnrData['passengerNameRecord']['flightInfo']['multi_destination']);

            $passengerNameRecord->setFlightInfo($flightInfo);

            $payment->setPassengerNameRecord($passengerNameRecord);

            $response = array();

            $response['payment'] = $payment;
            $response['passengerNameRecord'] = $passengerNameRecord;
            $response['displayBaseFare'] = $displayBaseFare;
            $response['displayTaxes'] = $displayTaxes;
            $response['displayTotalFare'] = $displayTotalFare;
            $response['displayedCurrency'] = $displayedCurrency;

            return $response;
    }

    /*
     * Get PNR by user and airline
     * @param type|integer $user_id of user
     * @param type|string $airline code
     *
     * @return object|PassengerNameRecord
     */
    public function getMemberShipIdByUserAndAirlineCode($user_id, $airline){

        $dql = "SELECT pnr FROM FlightBundle:PassengerNameRecord pnr JOIN pnr.flightDetails fd WITH pnr.userId = :userId AND pnr.status = :status AND pnr.membershipId IS NOT NULL AND fd.airline = :airline ORDER BY fd.id";
        $query = $this->em->createQuery( $dql );

        $query->setParameter( 'userId', $user_id );
        $query->setParameter( 'airline', $airline );
        $query->setParameter( 'status', 'SUCCESS' );
        $query->setMaxResults(1);

        return $query->getOneOrNullResult();
    }

    /*
     * Get AIRCRAFT_TYPE Info by iata_code
     * @param type|integer $iata_code
     *
     * @return object|AircraftType
     */
    public function getAircraftTypeInfo($iata_code){

        $aircraft = $this->em->getRepository('FlightBundle:AircraftType')->findOneByIataCode($iata_code);
        return $aircraft;
    }

    public function getCountryInfoByCode($code){
        $country = $this->em->getRepository('TTBundle:CmsCountries')->findOneByCode($code);
        return $country;
    }

    public function getPnrCodeMatch($code)
    {
        $pnr = $this->em->getRepository('FlightBundle:PassengerNameRecord')->findOneByPnr($code);
        return $pnr;
    }

}
