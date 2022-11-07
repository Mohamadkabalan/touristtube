<?php

namespace DealBundle\vendors\citydiscovery\v1;

use Symfony\Component\HttpFoundation\Response;
use TTBundle\Utils\Utils;
use DealBundle\Entity\DealDetails;
use DealBundle\Entity\DealCancelPolicies;
use DealBundle\Entity\DealDetailsTmp;
use DealBundle\Entity\DealCountry;
use DealBundle\Entity\DealCity;
use DealBundle\Entity\DealImage;
use DealBundle\Entity\DealTemp;
use DealBundle\Entity\DealCategory;
use DealBundle\Entity\DealDetailToCategory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DealBundle\Entity\DealTextsTemp;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Services\EmailServices;
use TTBundle\Services\PayFortServices;
use TTBundle\Services\CurrencyService;
use DealBundle\vendors\citydiscovery\v1\CityDiscoveryNormalizer;
use DealBundle\vendors\citydiscovery\Config as CDCONFIG;
use DealBundle\Model\DealResponse;
use DealBundle\Model\DealBookingResponse;
use DealBundle\Model\DealCancellationPolicy;
use DealBundle\Model\DealPriceOption;
use DealBundle\Model\DealUnit;
use DealBundle\Model\DealQuote;
use DealBundle\Model\DealMandatoryFields;
use DealBundle\Model\DealSchedule;
use DealBundle\Model\DealNote;
use DealBundle\Model\DealFaq;
use DealBundle\Model\DealDirections;
use DealBundle\Model\DealStartingPlace;
use DealBundle\Model\DealReview;
use DealBundle\Model\DealTransferBooking;
use DealBundle\Model\DealTransferCountryListing;
use DealBundle\Model\DealTransferCityListing;
use DealBundle\Model\DealTransferAirportListing;
use DealBundle\Model\DealTransferVehiclesListing;
use DealBundle\Model\DealArrivalDeparture;
use DealBundle\Model\DealBookingCancellation;
use DealBundle\Model\DealAirport;
use DealBundle\Model\DealTransferAirportPrice;

if (!defined('RESPONSE_SUCCESS')) define('RESPONSE_SUCCESS', 0);

if (!defined('RESPONSE_ERROR')) define('RESPONSE_ERROR', 1);

class CityDiscoveryHandler
{
    protected $utils;
    protected $em;
    protected $emailServices;
    protected $payFortServices;
    protected $currencyService;
    private $requestBody           = '';
    private $logger;
    private $cityDiscoveryNormalizer;
    private $cdConfig;
    private $ADDITIONAL_HEADERS    = array("Content-Type" => "text/xml;charset=UTF-8",
        "SOAPAction" => "",
        "Connection" => "Keep-Alive",
        "Accept" => "text/xml",
        "Cache-Control" => "no-cache",
        "Pragma" => "no-cache",
        "Accept-Encoding" => "gzip,deflate");
    private $TRANSFER_SERVICE_CODE = array('oneWayFromAirport' => 0, 'oneWayToAirport' => 1, 'roundtrip' => 2);

    public function __construct(Utils $utils, EntityManager $em, $templating, EmailServices $emailservices, PayFortServices $payFortServices, LoggerInterface $logger, ContainerInterface $container,
                                CurrencyService $currencyService)
    {
        $this->container               = $container;
        $this->cdConfig                = new CDCONFIG($container);
        $this->STATIC_URL              = ($this->cdConfig->test_mode) ? $this->cdConfig->http_test_static_url : $this->cdConfig->http_prod_static_url;
        $this->TRANSFERS_STATIC_URL    = ($this->cdConfig->test_mode) ? $this->cdConfig->http_test_transfer_url : $this->cdConfig->http_prod_transfer_url;
        $this->HTTP_USERNAME           = ($this->cdConfig->test_mode) ? $this->cdConfig->http_test_username : $this->cdConfig->http_prod_username;
        $this->HTTP_PASSWORD           = ($this->cdConfig->test_mode) ? $this->cdConfig->http_test_password : $this->cdConfig->http_prod_password;
        $this->utils                   = $utils;
        $this->em                      = $em;
        $this->emailServices           = $emailservices;
        $this->templating              = $templating;
        $this->payFortServices         = $payFortServices;
        $this->logger                  = $logger;
        $this->currencyService         = $currencyService;
        $this->cityDiscoveryNormalizer = new CityDiscoveryNormalizer();
    }
    /*
     * This method send a ActivityDetails request to citydiscovery.
     * Then it will format the result base on how we handle it in deal_details template.
     *
     * @param $activityId
     *
     * @return array of deal details information.
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getDetails($activityId)
    {
        $activityDetailsRequest = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery  ProcessType="ActivityDetails">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<Tour ActivityID="'.$activityId.'">'
            .'<ActivityCurrency>USD</ActivityCurrency>'
            .'<ActivityContentLanguage>en</ActivityContentLanguage>'
            .'<NoCache>1</NoCache>'
            .'</Tour>'
            .'</CityDiscovery>';

        $arr     = $this->cityDiscoveryNormalizer->getDesiredXmlArray();
        $results = $this->getXmlResponse($activityDetailsRequest, $arr);

        return $results;
    }
    /*
     * This method sends a PriceDetails request to cityDiscory for a specific tourcode
     *
     * @param $params - array('tourCode','startDate' => date when tour starts)
     *
     * @return array of price details
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getPriceDetails($detailsObj)
    {
        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery  ProcessType="ActivityDetails">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<Tour ActivityID="'.$detailsObj->getCommonSC()->getPackage()->getCode().'">'
            .'<ActivityCurrency>USD</ActivityCurrency>'
            .'<ActivityContentLanguage>en</ActivityContentLanguage>'
            .'<ActivityDate>'.date_format(new \DateTime($detailsObj->getCommonSC()->getPackage()->getStartDate()), "Y-m-d").'</ActivityDate>'
            .'<NoCache>1</NoCache>'
            .'</Tour>'
            .'</CityDiscovery>';

        $arr     = $this->cityDiscoveryNormalizer->getDesiredXmlArray('priceDetails');
        $results = $this->getXmlResponse($this->requestBody, $arr);

        return $results;
    }
    /*
     * This method sends a Price Quotation request to cityDiscory based from user selection
     *
     * @param $quotingObj
     *
     * @return array of price quotation
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getQuotation($quotingObj)
    {
        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="Quote">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<ActivityID>'.$quotingObj->getCommonSC()->getPackage()->getCode().'</ActivityID>'
            .'<ActivityDate>'.$quotingObj->getCommonSC()->getPackage()->getStartDate().'</ActivityDate>'
            .'<ActivityPriceId>'.$quotingObj->getActivityPriceId().'</ActivityPriceId>'
            .'<PriceId>'.$quotingObj->getPriceId().'</PriceId>'
            .'<ActivityCurrency>USD</ActivityCurrency>'
            .'<Units>';

        foreach ($quotingObj->getUnits() as $unitKey => $unitVal) {
            $this->requestBody .= '<Unit>'
                .'<Id>'.$unitVal->getUnitId().'</Id>'
                .'<Qty>'.$unitVal->getQuantity().'</Qty>'
                .'</Unit>';
        }

        $this->requestBody .= '</Units>'
            .'</CityDiscovery>';

        $arr     = $this->cityDiscoveryNormalizer->getDesiredXmlArray('getQuotation');
        $results = $this->getXmlResponse($this->requestBody, $arr);

        return $results;
    }
    /*
     * The ActivityReview Function lists of a specific activity.
     *
     * @param $activityId
     * @param $order Order on how reviews are sorted
     *
     * @return array of customer reviews
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getActivityReviews($activityId = 0, $order = 'rating_desc')
    {
        $results = array();
        if ($activityId == 0) {
            return $results;
        }

        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="ActivityReview">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<ActivityID>'.$activityId.'</ActivityID>'
            .'</CityDiscovery>';

        $arr    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('activityReviews');
        $review = $this->getXmlResponse($this->requestBody, $arr);

        return $review;
    }
    /*
     * The getTransfersCountries Function lists of a specific activity.
     *
     * @return array of customer reviews
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getTransferCountryListing()
    {
        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="TransferCountryListing">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'</CityDiscovery>';

        $arr                    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('getTransferCountryListing');
        $arr['useTransfersUrl'] = true;

        $result = $this->getXmlResponse($this->requestBody, $arr);
        return $result;
    }
    /*
     * The getTransfersCountries Function lists of a specific activity.
     *
     * @param $country
     *
     * @return array of customer reviews
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getTransferCityListingByCountry($country)
    {
        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="TransferCityListing">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<TransferCountry>'.$country.'</TransferCountry>'
            .'</CityDiscovery>';

        $arr                    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('getTransferCityListingByCountry');
        $arr['useTransfersUrl'] = true;

        $result = $this->getXmlResponse($this->requestBody, $arr);
        return $result;
    }
    /*
     * The getTransferAirportListing by city and country
     *
     * @param $airportObj
     *
     * @return array of airport
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getTransferAirportListing($airportObj)
    {
        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="Airports">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<TransferCity>'.$airportObj->getCity()->getName().'</TransferCity>'
            .'<TransferCountry>'.$airportObj->getCountry()->getCode().'</TransferCountry>'
            .'</CityDiscovery>';

        $arr                    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('getTransferAirportListing');
        $arr['useTransfersUrl'] = true;

        $result = $this->getXmlResponse($this->requestBody, $arr);
        return $result;
    }
    /* The getTransferVehicles Function gets all vehicles for a specific transfer request
     * depending on Country, City, Airport, Date
     *
     * @return $dealTransferObj vehicles
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */

    public function getTransferVehicles($dealTransferObj)
    {
        // checkAvailability parameter means that booking record is already present in db. And that were only getting data from db.
        $params                        = array();
        $params['arrivalPickupDate']   = date("Y-m-d", strtotime($dealTransferObj->getArrivalDeparture()->getArrivalDate()));
        $params['departurePickupDate'] = date("Y-m-d", strtotime($dealTransferObj->getArrivalDeparture()->getDepartureDate()));
        if (!$dealTransferObj->getCheckAvailability()) {
            $params['serviceCode']         = $this->TRANSFER_SERVICE_CODE[$dealTransferObj->getTypeOfTransfer()->getName()];
            $params['arrivalPickupTime']   = $dealTransferObj->getArrivalDeparture()->getArrivalHour().':'.$dealTransferObj->getArrivalDeparture()->getArrivalMinute();
            $params['departurePickupTime'] = $dealTransferObj->getArrivalDeparture()->getDepartureHour().':'.$dealTransferObj->getArrivalDeparture()->getDepartureMinute();
        } else {
            $params['serviceCode']         = $dealTransferObj->getTypeOfTransfer()->getCode();
            $params['arrivalPickupTime']   = $dealTransferObj->getArrivalDeparture()->getArrivalTime();
            $params['departurePickupTime'] = $dealTransferObj->getArrivalDeparture()->getDepartureTime();
        }

        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="QuickQuote">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<TransferCity>'.$dealTransferObj->getCity()->getName().'</TransferCity>'
            .'<TransferCountry>'.$dealTransferObj->getCountry()->getCode().'</TransferCountry>'
            .'<TransferAirportCode>'.$dealTransferObj->getDealAirport()->getCode().'</TransferAirportCode>'
            .'<TransferPersons>'.$dealTransferObj->getNumOfPersons().'</TransferPersons>'
            .'<TransferServiceCode>'.$params['serviceCode'].'</TransferServiceCode>';

        switch ($params['serviceCode']) {
            case 0:
                $this->requestBody .= '<ArrivalPickupDate>'.$params['arrivalPickupDate'].'</ArrivalPickupDate>'
                    .'<ArrivalPickupTime>'.$params['arrivalPickupTime'].'</ArrivalPickupTime>';
                break;
            case 1:
                $this->requestBody .= '<DeparturePickupDate>'.$params['departurePickupDate'].'</DeparturePickupDate>'
                    .'<DeparturePickupTime>'.$params['departurePickupTime'].'</DeparturePickupTime>';
            default:
                $this->requestBody .= '<ArrivalPickupDate>'.$params['arrivalPickupDate'].'</ArrivalPickupDate>'
                    .'<ArrivalPickupTime>'.$params['arrivalPickupTime'].'</ArrivalPickupTime>'
                    .'<DeparturePickupDate>'.$params['departurePickupDate'].'</DeparturePickupDate>'
                    .'<DeparturePickupTime>'.$params['departurePickupTime'].'</DeparturePickupTime>';
        }

        $this->requestBody .= '</CityDiscovery>';

        $arr                    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('getTransferVehicles');
        $arr['useTransfersUrl'] = true;
        $result                 = $this->getXmlResponse($this->requestBody, $arr);
        return $result;
    }
    /*
     * This method sends a TransferBooking request to cityDiscovery which is used to book a transfer.
     *
     * @param $transportObj
     *
     * @return array of booking details
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function processTransportBooking($transportObj)
    {
        $arrivalPickupDate   = date("Y-m-d", strtotime($transportObj->getArrivalDeparture()->getArrivalDate()));
        $departurePickupDate = date("Y-m-d", strtotime($transportObj->getArrivalDeparture()->getDepartureDate()));

        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="TransferBooking">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<TransferServiceCode>'.$transportObj->getServiceCode().'</TransferServiceCode>'
            .'<FirstName>'.$transportObj->getBookingResponse()->getFirstName().'</FirstName>'
            .'<LastName>'.$transportObj->getBookingResponse()->getLastName().'</LastName>'
            .'<PhoneNumber>'.$transportObj->getBookingResponse()->getMobile().'</PhoneNumber>'
            .'<EmailAddress>'.$transportObj->getBookingResponse()->getBookingEmail().'</EmailAddress>'
            .'<SpecialComments>'.$transportObj->getBookingResponse()->getBookingNotes().'</SpecialComments>'
            .'<BillingAddress>'.$transportObj->getCcBillingAddress().'Billing address</BillingAddress>'
            .'<PostalCode>'.$transportObj->getBookingResponse()->getPostalCode().'</PostalCode>'
            .'<City>'.$transportObj->getBookingResponse()->getCommonSC()->getCity()->getName().'</City>'
            .'<Country>'.$transportObj->getBookingResponse()->getCommonSC()->getCountry()->getCode().'</Country>'
            .'<BookingTotal>'.$transportObj->getBookingResponse()->getTotalPrice().'</BookingTotal>'
            .'<TransferCurrency>'.$transportObj->getBookingResponse()->getCommonSC()->getPackage()->getCurrency().'</TransferCurrency>'
            .'<PostPayment>Y</PostPayment>';

        switch ($transportObj->getServiceCode()) {
            case 0:
                $this->requestBody .= '<ArrivalPriceID>'.$transportObj->getArrivalDeparture()->getArrivalPriceId().'</ArrivalPriceID>'
                    .'<ArrivalServiceTime>'.$transportObj->getArrivalDeparture()->getArrivalTime().'</ArrivalServiceTime>'
                    .'<ArrivalPassenger>'.$transportObj->getNumOfpassengers().'</ArrivalPassenger>'
                    .'<ArrivalDate>'.$arrivalPickupDate.'</ArrivalDate>'
                    .'<ArrivalPickupDate>'.$arrivalPickupDate.'</ArrivalPickupDate>'
                    .'<ArrivalPickupTime>'.$transportObj->getArrivalDeparture()->getArrivalTime().'</ArrivalPickupTime>'
                    .'<ArrivalflighNumber>'.$transportObj->getArrivalDeparture()->getArrivalFlightDetails().'</ArrivalflighNumber>'
                    .'<ArrivalFrom>'.$transportObj->getArrivalDeparture()->getArrivalFrom().'</ArrivalFrom>'
                    .'<ArrivalDestination>'.$transportObj->getArrivalDeparture()->getArrivalDestinationAddress().'</ArrivalDestination>';
                break;
            case 1:
                $this->requestBody .= '<DeparturePriceID>'.$transportObj->getArrivalDeparture()->getDeparturePriceId().'</DeparturePriceID>'
                    .'<DepartFlighTime>'.$transportObj->getArrivalDeparture()->getDepartureTime().'</DepartFlighTime>'
                    .'<DepartPassenger>'.$transportObj->getNumOfpassengers().'</DepartPassenger>'
                    .'<DepartDate>'.$departurePickupDate.'</DepartDate>'
                    .'<DeparturePickupDate>'.$departurePickupDate.'</DeparturePickupDate>'
                    .'<DeparturePickupTime>'.$transportObj->getArrivalDeparture()->getDepartureTime().'</DeparturePickupTime>'
                    .'<DepartFlighNumber>'.$transportObj->getArrivalDeparture()->getDepartureFlightDetails().'</DepartFlighNumber>'
                    .'<DepartTo>'.$transportObj->getGoingTo().'</DepartTo>'
                    .'<DepartPickup>'.$transportObj->getArrivalDeparture()->getDepartureDestinationAddress().'</DepartPickup>';
            default:
                $this->requestBody .= '<ArrivalPriceID>'.$transportObj->getArrivalDeparture()->getArrivalPriceId().'</ArrivalPriceID>'
                    .'<ArrivalServiceTime>'.$transportObj->getArrivalDeparture()->getArrivalTime().'</ArrivalServiceTime>'
                    .'<ArrivalPassenger>'.$transportObj->getNumOfpassengers().'</ArrivalPassenger>'
                    .'<ArrivalDate>'.$arrivalPickupDate.'</ArrivalDate>'
                    .'<ArrivalPickupDate>'.$arrivalPickupDate.'</ArrivalPickupDate>'
                    .'<ArrivalPickupTime>'.$transportObj->getArrivalDeparture()->getArrivalTime().'</ArrivalPickupTime>'
                    .'<ArrivalflighNumber>'.$transportObj->getArrivalDeparture()->getArrivalFlightDetails().'</ArrivalflighNumber>'
                    .'<ArrivalFrom>'.$transportObj->getArrivalDeparture()->getArrivalFrom().'</ArrivalFrom>'
                    .'<ArrivalDestination>'.$transportObj->getArrivalDeparture()->getArrivalDestinationAddress().'</ArrivalDestination>'
                    .'<DeparturePriceID>'.$transportObj->getArrivalDeparture()->getDeparturePriceId().'</DeparturePriceID>'
                    .'<DepartFlighTime>'.$transportObj->getArrivalDeparture()->getDepartureTime().'</DepartFlighTime>'
                    .'<DepartPassenger>'.$transportObj->getNumOfpassengers().'</DepartPassenger>'
                    .'<DepartDate>'.$departurePickupDate.'</DepartDate>'
                    .'<DeparturePickupDate>'.$departurePickupDate.'</DeparturePickupDate>'
                    .'<DeparturePickupTime>'.$transportObj->getArrivalDeparture()->getDepartureTime().'</DeparturePickupTime>'
                    .'<DepartFlighNumber>'.$transportObj->getArrivalDeparture()->getDepartureFlightDetails().'</DepartFlighNumber>'
                    .'<DepartTo>'.$transportObj->getGoingTo().'</DepartTo>'
                    .'<DepartPickup>'.$transportObj->getArrivalDeparture()->getDepartureDestinationAddress().'</DepartPickup>';
        }

        $this->requestBody .= '</CityDiscovery>';

        $arr                    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('transportBooking');
        $arr['useTransfersUrl'] = true;
        $results                = $this->getXmlResponse($this->requestBody, $arr);
        return $results;
    }
    /*
     * This method sends a ActivityBooking request to cityDiscovery which is used to book and activity.
     *
     * @param $bookingObj dealBookingObject
     *
     * @return array of booking details
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function processBooking($bookingObj, $quoteObj)
    {
        $dynamicFields     = json_decode($quoteObj->getDynamicFieldsValues(), true);
        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery ProcessType="ActivityBooking">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'" />'
            .'</POS>'
            .'<Customer>'
            .'<FirstName>'.$bookingObj->getFirstName().'</FirstName>'
            .'<LastName>'.$bookingObj->getLastName().'</LastName>'
            .'<Email>'.$bookingObj->getEmail().'</Email>'
            .'<ContactNumber>'.$bookingObj->getMobilePhone().'</ContactNumber>'
            .'</Customer>'
            .'<Payload>'
            .'<QuoteKey>'.$quoteObj->getQuoteKey().'</QuoteKey>'
            .'</Payload>'
            .'<BookingReferenceNumber>'.$bookingObj->getBookingReference().'</BookingReferenceNumber>';


        //build mandatory field block
        if (isset($dynamicFields['Mandatory']) && $dynamicFields['Mandatory']) {
            $this->requestBody .= '<Mandatory>';

            if (isset($dynamicFields['Mandatory']['perBooking']) && $dynamicFields['Mandatory']['perBooking']) {
                foreach ($dynamicFields['Mandatory']['perBooking'] as $pbKey => $pbVal) {
                    $this->requestBody .= '<Field>'
                        .'<FieldId>'.$pbKey.'</FieldId>'
                        .'<Answer>'.$pbVal.'</Answer>'
                        .'</Field>';
                }
            }

            if (isset($dynamicFields['Mandatory']['perPerson']) && $dynamicFields['Mandatory']['perPerson']) {
                foreach ($dynamicFields['Mandatory']['perPerson'] as $ppKey1 => $ppVal1) {
                    $this->requestBody .= '<Field>'
                        .'<FieldId>'.$ppKey1.'</FieldId>';
                    $this->requestBody .= '<Units>';
                    foreach ($ppVal1 as $ppKey2 => $ppVal2) {
                        $tmpAnswer         = implode(";", $ppVal2);
                        $this->requestBody .= '<Unit>'
                            .'<UnitId>'.$ppKey2.'</UnitId>'
                            .'<Answer>'.$tmpAnswer.'</Answer>'
                            .'</Unit>';
                    }
                    $this->requestBody .= '</Units>';
                    $this->requestBody .= '</Field>';
                }
            }

            $this->requestBody .= '</Mandatory>';
        }

        //build transportation field block
        if (isset($dynamicFields['Transportation']) && $dynamicFields['Transportation']) {
            $this->requestBody .= '<Transportation>'.$dynamicFields['Transportation'].'</Transportation>';
        }

        $this->requestBody .= '<PostPayment>Y</PostPayment>'
            .'</CityDiscovery>';

        $arr     = $this->cityDiscoveryNormalizer->getDesiredXmlArray('activityBooking');
        $results = $this->getXmlResponse($this->requestBody, $arr);

        return $results;
    }

    /**
     *  Function is used to cancel booking
     *
     * @param bookingReference the booking reference
     * @param email the booking reference
     *
     * @return array response
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    public function cancelBooking($bookingReference, $email, $dealType = 'deals')
    {
        switch ($dealType) {
            case 'transfers':
                $this->requestBody      = '<?xml version="1.0" encoding="UTF-8"?>'
                    .'<CityDiscovery  ProcessType="TransferBookingCancellation">'
                    .'<POS>'
                    .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
                    .'</Source>'
                    .'</POS>'
                    .'<BookingReferenceTransfer>'.$bookingReference.'</BookingReferenceTransfer>'
                    .'<EmailAddress>'.$email.'</EmailAddress>'
                    .'</CityDiscovery>';
                $arr                    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('transferCancellation');
                $arr['useTransfersUrl'] = true;
                break;
            default:
                $this->requestBody      = '<?xml version="1.0" encoding="UTF-8"?>'
                    .'<CityDiscovery  ProcessType="ActivityBookingCancellation">'
                    .'<POS>'
                    .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
                    .'</Source>'
                    .'</POS>'
                    .'<BookingReferenceCityDiscovery>'.$bookingReference.'</BookingReferenceCityDiscovery>'
                    .'<EmailAddress>'.$email.'</EmailAddress>'
                    .'</CityDiscovery>';
                $arr                    = $this->cityDiscoveryNormalizer->getDesiredXmlArray('activityCancellation');
        }

        $cancelResults = $this->getXmlResponse($this->requestBody, $arr);
        return $cancelResults;
    }
    /*
     * This method gets all the attractions of a city and a country
     *
     * @param $cityCode
     * @param $countryCode
     *
     * @return array of attractions per selected city and country
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getDiscoveryCityAttractions($cityCode, $countryCode)
    {

        $xmlRequest            = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<CityDiscovery ProcessType="ActivityDisplay">
   <POS>
      <Source AgentSine="4866TOURI" AgentDutyCode="tu63tourist38409184" />
   </POS>
   <ActivityCity>$cityCode</ActivityCity>
   <ActivityCountry>$countryCode</ActivityCountry>
   <ActivityCategory>2</ActivityCategory>
</CityDiscovery>
EOL;
        $data                  = array("data" => $xmlRequest);
        $getStaticDataResponse = $this->utils->send_data('https://api.city-discovery.com/api.1.1.3/test/api.php', $data, \HTTP_Request2::METHOD_POST);

        // Anna here I am removing the cdata elements from the response and display the text as is
        $getStaticDataResponse['response_text'] = str_replace(array('<![CDATA[', ']]>'), array('', ''), $getStaticDataResponse['response_text']);

        $xpath = $this->utils->xmlString2domXPath($getStaticDataResponse['response_text']);

        $items_node_list = $xpath->query("//Tour");

        $items = array();

        if ($items_node_list && $items_node_list->length) {

            foreach ($items_node_list as $item_info) {

                $new_item = array();

                $this->utils->fetch_node_info($item_info, $new_item);

                $items[] = $new_item;
            }
        } else {
            //adding more descriptive message why it returned error. Upon checking those ids dont have tour elements
            $items['errors'] = array();
            $new_error       = array('No Activity/tour available for Country: '.$countryCode.' |||||| '.$cityCode);
            $items['errors'] = $new_error;
        }

        return $items;
    }
    /*
     * This is a testing method to get packages response  for all deal cities and insert them in deal details table
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getCityPackages($closeEntityManagerOnCompletion = false)
    {
        return;
    }
    /*
     * This is a testing method for American Tours Internationals to get activities response  for all deal cities and insert them in deal details table
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getCityActivities($closeEntityManagerOnCompletion = false)
    {
        return;
    }

    /**
     * This method gets the list of countries from City Discovery API and insert it to our db table deal_country
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    public function getDiscoveryCityCountries()
    {

        $xmlRequest            = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<CityDiscovery ProcessType="CountryListing">
   <POS>
      <Source AgentSine="4866TOURI" AgentDutyCode="tu63tourist38409184" />
   </POS>
</CityDiscovery>
EOL;
        $data                  = array("data" => $xmlRequest);
        $getStaticDataResponse = $this->utils->send_data('https://api.city-discovery.com/api.1.1.3/api.php', $data, \HTTP_Request2::METHOD_POST);
        $xpath                 = $this->utils->xmlString2domXPath($getStaticDataResponse['response_text']);

        $items_node_list = $xpath->query("//Country");
        $items           = array();

        if ($items_node_list && $items_node_list->length) {

            foreach ($items_node_list as $item_info) {
                $new_item = array();

                $this->utils->fetch_node_info($item_info, $new_item);

                $items[] = $new_item;
            }
        } else {
            $items['errors']   = array();
            $new_error         = array('error in return');
            $items['errors'][] = $new_error;
        }
        if (isset($items['errors']) && !empty($items['errors'])) {
            echo "\n".date('c')." Error Return From City Discovery API";
        }
        return $items;
    }

    /**
     *  Function is used to check the status of booking
     * from the api call of get ActivityBookingStatus from city discovery services
     *
     * @param bookingReference the booking reference for city discovery
     * @param email email-address for city discovery
     *
     * @return array response
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    public function getBookingStatus($bookingReference, $email)
    {

        $this->requestBody = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<CityDiscovery  ProcessType="ActivityBookingStatus">
<POS>
  <Source AgentSine="$this->HTTP_USERNAME" AgentDutyCode="$this->HTTP_PASSWORD" />
</POS>
<BookingReferenceCityDiscovery>$bookingReference</BookingReferenceCityDiscovery>
<EmailAddress>$email</EmailAddress>
</CityDiscovery>
EOL;

        $arr                  = $this->cityDiscoveryNormalizer->getDesiredXmlArray('activityBookingStatus');
        $bookingStatusResults = $this->getXmlResponse($this->requestBody, $arr);

        return $bookingStatusResults;
    }
    /*
     *
     * This method checks the availability of a deal from city discovery by checking if any error returned
     * otherwise the activity will be available
     *
     * @param activityId
     *
     * @return bool
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function checkAvailability($activityId)
    {
        $this->requestBody = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<CityDiscovery  ProcessType="ActivityDetails">'
            .'<POS>'
            .'<Source AgentSine="'.$this->HTTP_USERNAME.'" AgentDutyCode="'.$this->HTTP_PASSWORD.'">'
            .'</Source>'
            .'</POS>'
            .'<Tour ActivityID="'.$activityId.'">'
            .'<ActivityCurrency>USD</ActivityCurrency>'
            .'<ActivityContentLanguage>en</ActivityContentLanguage>'
            .'</Tour>'
            .'</CityDiscovery>';

        $arr     = $this->cityDiscoveryNormalizer->getDesiredXmlArray('checkAvailiblity');
        $results = $this->getXmlResponse($this->requestBody, $arr);

        return $results;
    }

    /**
     * This method is used to format all the methods responses with standard json
     *
     * param array of results
     * @return json return with standard formatting: success, message, code, data
     */
    private function createJsonResponse($result = array())
    {
        $translator = $this->container->get('translator');
        if (empty($result)) {
            $responseArray['success'] = false;
            $responseArray['message'] = $translator->trans('No data returned from API');
            $responseArray['code']    = '';
            $responseArray['data']    = '';
        } else {
            if ((isset($result['errorAPIMessage']) && !empty($result['errorAPIMessage']))) {
                $responseArray['success'] = false;
                $responseArray['message'] = $translator->trans($result['errorAPIMessage']);
                $responseArray['code']    = '';
                $responseArray['data']    = '';
            } elseif (isset($result['errorCode']) && !empty($result['errorCode'])) {
                $responseArray['success'] = false;
                $responseArray['message'] = $translator->trans($result['errorMessage']);
                $responseArray['code']    = $result['errorCode'];
                $responseArray['data']    = '';
            } else {
                $responseArray['success'] = true;
                $responseArray['message'] = '';
                $responseArray['code']    = '';
                $responseArray['data']    = $result;
            }
        }
        $response = new Response(json_encode($responseArray));
        $response->headers->set('Content-Type', 'application/json');

        return json_encode($responseArray);
    }
    /*
     * This method is responsible for sending a request to cityDiscovery API.
     * You just need to pass the request text. And also the way the xml should be handled.
     *
     * @param $requestBody  - the request xml you want to use
     * @param $resultAttr['mainXml'] = array('Contiainer_Tag' => array('optional_name_that_will_be_used' => 'Tag_name_to_extract_data_from'))
     * @param $arr['multipleXml'] = array('Contiainer_Tag' =>
     * 		array('@xmlAttribute(IF YOU WANT TO RETRIEVE AN ATTRIBUTE FROM THE Contiainer_Tag ITSELF)' => array('optional_name_that_will_be_used' => 'Tag_name_to_extract_data_from'))
     * 		array('optional_name_that_will_be_used' => 'Tag_name_to_extract_data_from'))
     * @param $resultAttr['xmlAttr'] = array('Tag_name_to_extract_data_from' => array('optional_name_that_will_be_used' => 'atrribute_name_to_extract_data_from'))
     *
     * @return array of xml data based on what $resultAttr you pass
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getXmlResponse($requestBody, $resultAttr = array())
    {
        if (isset($resultAttr['useTransfersUrl']) && $resultAttr['useTransfersUrl']) {
            $requestUrl = $this->TRANSFERS_STATIC_URL;
            $data       = array("data" => $requestBody);
        } else {
            $requestUrl = $this->STATIC_URL;
            $data       = $requestBody;
        }

        $getStaticDataResponse = $this->utils->send_data($requestUrl, $data, \HTTP_Request2::METHOD_POST);
        $dealResObj            = new DealResponse();


        // just added error message for error handling
        if (!isset($getStaticDataResponse['response_text']) || empty($getStaticDataResponse['response_text'])) {
            return array('errorAPIMessage' => 'Error From City Discovery API call');
        }

        $dom = new \DOMDocument();

        try {
            $dom->loadXml($getStaticDataResponse['response_text']);
        } catch (\Exception $e) {
            return array('errorAPIMessage' => 'Error parsing XML response', 'responseText' => $getStaticDataResponse['response_text']);
        }

        /*
         * This will handle the xml tag names.
         * Anything placed in here is automatically part of main DealResponse Object.
         */
        if (isset($resultAttr['mainXml'])) {
            foreach ($resultAttr['mainXml'] as $raKey => $raVal) {
                if (!property_exists($dealResObj, $raKey)) {
                    continue;
                }

                $setterName = "set".ucfirst($raKey);
                $raElem     = $dom->getElementsByTagName($raVal);
                if ($raElem && $raElem->length) {
                    if (trim($raElem->item(0)->nodeValue) != '') {
                        $dealResObj->$setterName($raElem->item(0)->nodeValue);
                    }
                }
            }
        }

        /*
         * Should handle xml attributes found on xmltags with only 1 occurence.
         * Separated this case in order not to be confusing.
         * This means that attribute is part of the main DealResponse.
         */
        if (isset($resultAttr['mainXmlAttr'])) {
            foreach ($resultAttr['mainXmlAttr'] as $raKey => $raVal) {
                foreach ($raVal as $raKey2 => $raVal2) {
                    if (!property_exists($dealResObj, $raKey2)) {
                        continue;
                    }

                    $setterName = "set".ucfirst($raKey2);
                    $raElem     = $dom->getElementsByTagName($raKey);
                    if ($raElem && $raElem->length) {
                        $dealResObj->$setterName($raElem->item(0)->getAttribute($raVal2));
                    }
                }
            }
        }

        if (isset($resultAttr['dealBookingXml']) || isset($resultAttr['bookingStatusXml'])) {
            $dealBookingObj = new DealBookingResponse();
            $loopKey        = (isset($resultAttr['dealBookingXml'])) ? 'dealBookingXml' : 'bookingStatusXml';
            foreach ($resultAttr[$loopKey] as $raKey => $raVal) {
                if (!property_exists($dealBookingObj, $raKey)) {
                    continue;
                }

                $setterName = "set".ucfirst($raKey);
                $raElem     = $dom->getElementsByTagName($raVal);
                if ($raElem && $raElem->length) {
                    $dealBookingObj->$setterName($raElem->item(0)->nodeValue);
                }
            }
            $dealResObj->setDealBooking(array($dealBookingObj));
        }

        if (isset($resultAttr['transferBookingXml'])) {
            $tbArray          = array('serviceType', 'serviceCode', 'creditCardTransactionType', 'creditCardTransactionID');
            $bookingRespArray = array('productCode' => 'productCode',
                'bookingReference' => 'bookingReference',
                'bookingStatus' => 'bookingStatus',
                'firstName' => 'firstName',
                'lastName' => 'lastName',
                'email' => 'bookingEmail',
                'bookingVoucherInformation' => 'bookingVoucherInformation',
                'totalPrice' => 'totalPrice',
                'currency' => 'bookingCurrency',
            );

            $transferBookingObj      = new DealTransferBooking();
            $bookingRespObj          = new DealBookingResponse();
            $dealArrivalDepartureObj = new DealArrivalDeparture();

            foreach ($resultAttr['transferBookingXml'] as $raKey => $raVal) {
                $setterName = "set".ucfirst($raKey);
                $raElem     = $dom->getElementsByTagName($raVal);
                //if xml not found skip
                if (!($raElem && $raElem->length)) {
                    continue;
                }

                if (in_array($raKey, $tbArray)) {
                    if (!property_exists($transferBookingObj, $raKey)) {
                        continue;
                    }
                    $transferBookingObj->$setterName($raElem->item(0)->nodeValue);
                } elseif (in_array($raKey, $bookingRespArray)) {
                    if (!property_exists($bookingRespObj, $raKey)) {
                        continue;
                    }
                    $setterName = "set".ucfirst($bookingRespArray[$raKey]);
                    $transferBookingObj->getBookingResponse()->$setterName($raElem->item(0)->nodeValue);
                } else {
                    if (!property_exists($dealArrivalDepartureObj, $raKey)) {
                        continue;
                    }
                    $dealArrivalDepartureObj->$setterName($raElem->item(0)->nodeValue);
                }
            }
            $transferBookingObj->setDealArrivalDeparture(array($dealArrivalDepartureObj));
            $dealResObj->setTransferBooking(array($transferBookingObj));
        }

        /*
         * Build Highlights txt
         */
        if (isset($resultAttr['highlightsXml'])) {
            $highlightsArr = array();
            foreach ($resultAttr['highlightsXml'] as $raKey => $raVal) {
                $raElem = $dom->getElementsByTagName($raVal);
                if ($raElem && $raElem->length) {
                    for ($i = 0; $i < $raElem->length; $i++) {
                        $highlightsArr[$raKey][] = $raElem->item($i)->nodeValue;
                    }
                }
            }

            $highlights = '';
            if (isset($highlightsArr['activityHighlights'])) {
                $highlights .= $highlightsArr['activityHighlights'][0].'<br /><br />';
            }
            if (isset($highlightsArr['bulletHighlight']) && $highlightsArr['bulletHighlight']) {
                $highlights .= '<ul>';
                foreach ($highlightsArr['bulletHighlight'] as $val) {
                    $highlights .= '<li>'.$val.'</li>';
                }
                $highlights .= '</ul>';
            }

            $dealResObj->setDealHighlights($highlights);
        }

        /*
         * Build Schedules Array
         */
        if (isset($resultAttr['schedulesXml'])) {
            $schedulesArr = $this->getmultipleXml($dom, $resultAttr['schedulesXml']);
            $schedules    = array();

            if (isset($schedulesArr['ActivitySchedules']['ScheduleGroup'])) {
                //to organize the schedule
                $scheduleGroupId = 1;
                foreach ($schedulesArr['ActivitySchedules']['ScheduleGroup'] as $raKey => $raVal) {

                    if (isset($raVal['ActivitySchedule'])) {
                        foreach ($raVal['ActivitySchedule'] as $raKey2 => $raVal2) {
                            $dealScheduleObj = new DealSchedule();
                            $dealScheduleObj->setOrder(isset($raVal2['order']) ? $raVal2['order'] : '');
                            $dealScheduleObj->setTitle(isset($raVal2['title']) ? $raVal2['title'] : '');
                            $dealScheduleObj->setTime(isset($raVal2['time']) ? $raVal2['time'] : '');
                            $dealScheduleObj->setDescription(isset($raVal2['description']) ? $raVal2['description'] : '');
                            $dealScheduleObj->setGroupId($scheduleGroupId);

                            $schedules[] = $dealScheduleObj;
                        }
                    }
                    $scheduleGroupId++;
                }
            }

            $dealResObj->setDealSchedules($schedules);
        }

        /*
         * Build Notes Array
         */
        if (isset($resultAttr['notesXml'])) {
            $notesArr    = $this->getmultipleXml($dom, $resultAttr['notesXml']);
            $notes       = array();
            $noteGroupId = 1;

            if (isset($notesArr['ActivityNotes']['Note'])) {
                foreach ($notesArr['ActivityNotes']['Note'] as $raKey => $raVal) {
                    if (isset($raVal['Items'])) {
                        foreach ($raVal['Items'] as $raKey2 => $raVal2) {

                            if (isset($raVal2['Item'])) {
                                foreach ($raVal2['Item'] as $raKey3 => $raVal3) {
                                    $dealNotesObj = new DealNote();
                                    $dealNotesObj->setTitle(isset($raVal['title']) ? $raVal['title'] : '');
                                    $dealNotesObj->setInfo(isset($raVal3['ApplyTo'][0]['info']) ? $raVal3['ApplyTo'][0]['info'] : '');
                                    $dealNotesObj->setNote(isset($raVal3['note']) ? $raVal3['note'] : '');
                                    $dealNotesObj->setPdfPath(isset($raVal3['pdfPath']) ? $raVal3['pdfPath'] : '');
                                    $dealNotesObj->setGroupId($noteGroupId);

                                    $notes[] = $dealNotesObj;
                                }
                            }
                        }
                    }
                    $noteGroupId++;
                }
            }

            $dealResObj->setNotes($notes);
        }

        /*
         * Build Starting Place Array
         */
        if (isset($resultAttr['startingPlaceXml'])) {
            $startingPlaceArr = $this->getmultipleXml($dom, $resultAttr['startingPlaceXml']);
            $startingPlace    = array();

            if (isset($startingPlaceArr['ActivityStartingPlaces']['ActivityStartingPlace'])) {
                foreach ($startingPlaceArr['ActivityStartingPlaces']['ActivityStartingPlace'] as $raKey => $raVal) {
                    $startingPlaceObj = new DealStartingPlace();
                    $startingPlaceObj->setAddress(isset($raVal['address']) ? $raVal['address'] : '');
                    $startingPlaceObj->setLat(isset($raVal['lat']) ? $raVal['lat'] : '');
                    $startingPlaceObj->setLong(isset($raVal['lang']) ? $raVal['lang'] : '');

                    $startingPlace[] = $startingPlaceObj;
                }
            }

            $dealResObj->setStartingPlace($startingPlace);
        }

        /*
         * Build Frequently Asked Questions Array
         */
        if (isset($resultAttr['faqXml'])) {
            $faqArr = $this->getmultipleXml($dom, $resultAttr['faqXml']);
            $faq    = array();

            if (isset($faqArr['ActivityFaq']['Faq'])) {
                foreach ($faqArr['ActivityFaq']['Faq'] as $raKey => $raVal) {
                    $faqObj = new DealFaq();
                    $faqObj->setQuestion(isset($raVal['question']) ? $raVal['question'] : '');
                    $faqObj->setAnswer(isset($raVal['answer']) ? $raVal['answer'] : '');

                    $faq[] = $faqObj;
                }
            }

            $dealResObj->setFaq($faq);
        }

        /*
         * Build Location & Directions Array
         */
        if (isset($resultAttr['directionsXml'])) {
            $directionArr = $this->getmultipleXml($dom, $resultAttr['directionsXml']);
            $direction    = array();

            if (isset($directionArr['ActivityLocationDirection']['Direction'])) {
                foreach ($directionArr['ActivityLocationDirection']['Direction'] as $raKey => $raVal) {
                    $directionObj = new DealDirections();
                    $directionObj->setTitle(isset($raVal['title']) ? $raVal['title'] : '');
                    $directionObj->setDescription(isset($raVal['description']) ? $raVal['description'] : '');
                    $directionObj->setImage(isset($raVal['image']) ? $raVal['image'] : '');

                    $direction[] = $directionObj;
                }
            }

            $dealResObj->setDirections($direction);
        }

        /*
         * Build Cancellation Policy Array
         */
        if (isset($resultAttr['cancellationPolicyXml'])) {
            $cancellationPolicyArr = $this->getmultipleXml($dom, $resultAttr['cancellationPolicyXml']);
            $cancellationPolicy    = array();

            if (isset($cancellationPolicyArr['ActivityCancellation']['ActivityCancellationPolicy']) || isset($cancellationPolicyArr['TransferCancellation']['TransferCancellationPolicy'])) {

                $cancellationArray = isset($cancellationPolicyArr['ActivityCancellation']['ActivityCancellationPolicy']) ? $cancellationPolicyArr['ActivityCancellation']['ActivityCancellationPolicy'] : $cancellationPolicyArr['TransferCancellation']['TransferCancellationPolicy'];
                foreach ($cancellationArray as $raKey => $raVal) {
                    $cancellationPolicyObj = new DealCancellationPolicy();
                    $cancellationPolicyObj->setCancellationDay(isset($raVal['cancellationDay']) ? $raVal['cancellationDay'] : '');
                    $cancellationPolicyObj->setCancellationDiscount(isset($raVal['cancellationDiscount']) ? $raVal['cancellationDiscount'] : '');

                    $cancellationPolicy[] = $cancellationPolicyObj;
                }
            }
            $dealResObj->setCancellationPolicy($cancellationPolicy);
        }

        /*
         * Build Booking Cancellation Array
         */
        if (isset($resultAttr['bookingCancellationXml'])) {
            $dealBookingCancellationObj = new DealBookingCancellation();
            foreach ($resultAttr['bookingCancellationXml'] as $raKey => $raVal) {
                // meaning where trying to get a xml attribute
                if (is_array($raVal)) {
                    foreach ($raVal as $raKey2 => $raVal2) {
                        if (!property_exists($dealBookingCancellationObj, $raKey2)) {
                            continue;
                        }

                        $setterName = "set".ucfirst($raKey2);
                        $raElem     = $dom->getElementsByTagName($raKey);
                        if ($raElem && $raElem->length) {
                            $dealBookingCancellationObj->$setterName($raElem->item(0)->getAttribute($raVal2));
                        }
                    }
                } else {

                    if (!property_exists($dealBookingCancellationObj, $raKey)) {
                        continue;
                    }

                    $setterName = "set".ucfirst($raKey);
                    $raElem     = $dom->getElementsByTagName($raVal);
                    if ($raElem && $raElem->length) {
                        $dealBookingCancellationObj->$setterName($raElem->item(0)->nodeValue);
                    }
                }
            }
            $dealResObj->setDealBookingCancellation(array($dealBookingCancellationObj));
        }

        /*
         * Build Price Options Array
         */
        if (isset($resultAttr['priceOptionXml'])) {
            $priceOptionArr  = $this->getmultipleXml($dom, $resultAttr['priceOptionXml']);
            $priceOptions    = array();
            $scheduleGroupId = 1;
            if (isset($priceOptionArr['ActivityPrices']['ActivityPriceId'])) {
                foreach ($priceOptionArr['ActivityPrices']['ActivityPriceId'] as $raKey => $raVal) {

                    //schedules for each activityPriceId
                    $schedules = array();
                    if (isset($raVal["Schedules"][0]['ScheduleGroup'])) {
                        foreach ($raVal["Schedules"][0]['ScheduleGroup'] as $raKey4 => $raVal4) {
                            $tmpSchedules = array();
                            if (isset($raVal4['Schedule'])) {
                                foreach ($raVal4['Schedule'] as $raKey5 => $raVal5) {
                                    $dealScheduleObj = new DealSchedule();
                                    $dealScheduleObj->setOrder(isset($raVal5['order']) ? $raVal5['order'] : '');
                                    $dealScheduleObj->setTitle(isset($raVal5['title']) ? $raVal5['title'] : '');
                                    $dealScheduleObj->setTime(isset($raVal5['time']) ? $raVal5['time'] : '');
                                    $dealScheduleObj->setDescription(isset($raVal5['description']) ? $raVal5['description'] : '');
                                    $dealScheduleObj->setGroupId($scheduleGroupId);

                                    $tmpSchedules[] = $dealScheduleObj;
                                }
                            }
                            $schedules[] = $tmpSchedules;
                            $scheduleGroupId++;
                        }
                    }

                    if (isset($raVal['PackagePrices'][0]['Price'])) {
                        foreach ($raVal['PackagePrices'][0]['Price'] as $raKey2 => $raVal2) {
                            $dealPriceOptionsObj = new DealPriceOption();
                            $dealPriceOptionsObj->setActivityPriceId(isset($raVal['activityPriceId']) ? $raVal['activityPriceId'] : '');
                            $dealPriceOptionsObj->setOptionLabel(isset($raVal['optionLabel']) ? $raVal['optionLabel'] : '');
                            $dealPriceOptionsObj->setInclusions(isset($raVal['inclusions']) ? $raVal['inclusions'] : '');
                            $dealPriceOptionsObj->setPriceId(isset($raVal2['priceId']) ? $raVal2['priceId'] : '');
                            $dealPriceOptionsObj->setPriceDays(isset($raVal2['AvailableDays']) ? $raVal2['AvailableDays'] : array());
                            $dealPriceOptionsObj->setOptionDateBegins(isset($raVal2['optionDateBegins']) ? $raVal2['optionDateBegins'] : '');
                            $dealPriceOptionsObj->setOptionDateEnd(isset($raVal2['optionDateEnd']) ? $raVal2['optionDateEnd'] : '');
                            $dealPriceOptionsObj->setSchedules($schedules ? $schedules : array());

                            $units = array();
                            if (isset($raVal2['Units'][0]['Unit'])) {
                                foreach ($raVal2['Units'][0]['Unit'] as $raKey3 => $raVal3) {
                                    $unitsObj = new DealUnit();
                                    $unitsObj->setUnitId(isset($raVal3['unitId']) ? $raVal3['unitId'] : '');
                                    $unitsObj->setLabel(isset($raVal3['label']) ? $raVal3['label'] : '');
                                    $unitsObj->setMinimum(isset($raVal3['minimum']) ? $raVal3['minimum'] : '');
                                    $unitsObj->setMaximum(isset($raVal3['maximum']) ? $raVal3['maximum'] : '');
                                    $unitsObj->setCapacityCount(isset($raVal3['capacityCount']) ? $raVal3['capacityCount'] : '');
                                    $unitsObj->setRequiredOtherUnits(isset($raVal3['requiredOtherUnits']) ? $raVal3['requiredOtherUnits'] : '');
                                    $unitsObj->setChargePrice(isset($raVal3['chargePrice']) ? $raVal3['chargePrice'] : '');
                                    $unitsObj->setNetPrice(isset($raVal3['netPrice']) ? $raVal3['netPrice'] : '');
                                    $unitsObj->setCurrency(isset($raVal3['currency']) ? $raVal3['currency'] : '');

                                    $units[] = $unitsObj;
                                }
                            }
                            $dealPriceOptionsObj->setUnits($units);
                            $priceOptions[] = $dealPriceOptionsObj;
                        }
                    }
                }
            }
            $dealResObj->setPriceOptions($priceOptions);
        }

        /*
         * Build Quotes Array
         */
        if (isset($resultAttr['quoteXml'])) {
            $quoteArr        = $this->getmultipleXml($dom, $resultAttr['quoteXml']);
            $quote           = array();
            $mandatoryFields = array();

            if (isset($quoteArr['SelectPayload']['Payload'])) {
                foreach ($quoteArr['SelectPayload']['Payload'] as $raKey => $raVal) {
                    $quoteObj = new DealQuote();
                    $quoteObj->setQuoteKey(isset($raVal['quoteKey']) ? $raVal['quoteKey'] : '');
                    $quoteObj->getCommonSC()->getPackage()->setId(isset($raVal['packageId']) ? $raVal['packageId'] : '');
                    $quoteObj->getCommonSC()->getPackage()->setCode(isset($raVal['activityId']) ? $raVal['activityId'] : '');
                    $quoteObj->getCommonSC()->getPackage()->setStartDate(isset($raVal['activityDate']) ? $raVal['activityDate'] : '');
                    $quoteObj->setTotal(isset($raVal['total']) ? $raVal['total'] : '');
                    $quoteObj->getCommonSC()->getPackage()->setCurrency(isset($raVal['currency']) ? $raVal['currency'] : '');
                    // we expect only one timeId per Quote
                    $quoteObj->setTimeId(isset($raVal['ActivityTime'][0]['timeId']) ? $raVal['ActivityTime'][0]['timeId'] : '');
                    $quoteObj->setTime(isset($raVal['ActivityTime'][0]['time']) ? $raVal['ActivityTime'][0]['time'] : '');

                    $units = array();
                    if (isset($raVal['Units'][0]['Unit'])) {
                        foreach ($raVal['Units'][0]['Unit'] as $raKey3 => $raVal3) {
                            $unitsObj = new DealUnit();
                            $unitsObj->setUnitId(isset($raVal3['unitId']) ? $raVal3['unitId'] : '');
                            $unitsObj->setQuantity(isset($raVal3['quantity']) ? $raVal3['quantity'] : '');

                            $units[] = $unitsObj;
                        }
                    }

                    $quoteObj->setUnits($units);
                    $quote[] = $quoteObj;
                }
            }

            $dealResObj->setQuote($quote);

            if (isset($quoteArr['Mandatory']['Field'])) {
                foreach ($quoteArr['Mandatory']['Field'] as $raKey2 => $raVal2) {
                    $mandatoryFieldsObj = new DealMandatoryFields();
                    $mandatoryFieldsObj->setFieldId(isset($raVal2['fieldId']) ? $raVal2['fieldId'] : '');
                    $mandatoryFieldsObj->setKey(isset($raVal2['key']) ? $raVal2['key'] : '');
                    $mandatoryFieldsObj->setLabel(isset($raVal2['label']) ? $raVal2['label'] : '');
                    $mandatoryFieldsObj->setPer(isset($raVal2['per']) ? $raVal2['per'] : '');
                    $mandatoryFieldsObj->setFormat(isset($raVal2['format']) ? $raVal2['format'] : '');
                    $mandatoryFieldsObj->setData(isset($raVal2['data']) ? $raVal2['data'] : '');

                    $mandatoryFields[] = $mandatoryFieldsObj;
                }
            }

            $dealResObj->setMandatoryFields($mandatoryFields);
        }

        /*
         * Build Reviews Array
         */
        if (isset($resultAttr['reviewsXml'])) {
            $reviewsArr = $this->getmultipleXml($dom, $resultAttr['reviewsXml']);
            $reviews    = array();

            if (isset($reviewsArr['ReviewList']['Review'])) {
                foreach ($reviewsArr['ReviewList']['Review'] as $raKey => $raVal) {
                    $dealReviewsObj = new DealReview();
                    $dealReviewsObj->setReviewId(isset($raVal['reviewId']) ? $raVal['reviewId'] : '');
                    $dealReviewsObj->setComment(isset($raVal['comment']) ? $raVal['comment'] : '');
                    $dealReviewsObj->setRating(isset($raVal['rating']) ? $raVal['rating'] : '');
                    $dealReviewsObj->setOwner(isset($raVal['owner']) ? $raVal['owner'] : '');
                    $dealReviewsObj->setCountry(isset($raVal['country']) ? $raVal['country'] : '');
                    $dealReviewsObj->setDate(isset($raVal['date']) ? $raVal['date'] : '');

                    $reviews[] = $dealReviewsObj;
                }
            }
            $dealResObj->setReviews($reviews);
        }

        /*
         * Build Transfer Countries Array
         */
        if (isset($resultAttr['transferCountryListingXml'])) {
            $transferCountriesArr = $this->getmultipleXml($dom, $resultAttr['transferCountryListingXml']);
            $transferCountries    = array();

            if (isset($transferCountriesArr['Countries']['TransferCountry'])) {
                foreach ($transferCountriesArr['Countries']['TransferCountry'] as $raKey => $raVal) {
                    $dealTransferCountryObj = new DealTransferCountryListing();
                    $dealTransferCountryObj->setCode(isset($raVal['code']) ? $raVal['code'] : '');
                    $dealTransferCountryObj->setName(isset($raVal['name']) ? $raVal['name'] : '');
                    $dealTransferCountryObj->setContinent(isset($raVal['continent']) ? $raVal['continent'] : '');

                    $transferCountries[] = $dealTransferCountryObj;
                }
            }
            $dealResObj->setTransferCountries($transferCountries);
        }

        /*
         * Build Transfer Cities Array
         */
        if (isset($resultAttr['transferCityListingXml'])) {
            $transferCitiesArr = $this->getmultipleXml($dom, $resultAttr['transferCityListingXml']);
            $transferCities    = array();

            if (isset($transferCitiesArr['Cities']['TransferCity'])) {
                foreach ($transferCitiesArr['Cities']['TransferCity'] as $raKey => $raVal) {
                    $dealTransferCityObj = new DealTransferCityListing();
                    $dealTransferCityObj->setName(isset($raVal['cityName']) ? $raVal['cityName'] : '');

                    $transferCities[] = $dealTransferCityObj;
                }
            }
            $dealResObj->setTransferCities($transferCities);
        }

        /*
         * Build Transfer Airports Array
         */
        if (isset($resultAttr['transferAirportListingXml'])) {
            $transferAirportArr = $this->getmultipleXml($dom, $resultAttr['transferAirportListingXml']);
            $transferAirport    = array();

            if (isset($transferAirportArr['Airports']['Airport'])) {
                foreach ($transferAirportArr['Airports']['Airport'] as $raKey => $raVal) {
                    $dealTransferAirportObj = new DealTransferAirportListing();
                    $dealTransferAirportObj->setId(isset($raVal['id']) ? $raVal['id'] : '');
                    $dealTransferAirportObj->setCode(isset($raVal['code']) ? $raVal['code'] : '');
                    $dealTransferAirportObj->setName(isset($raVal['name']) ? $raVal['name'] : '');
                    $dealTransferAirportObj->setType(isset($raVal['type']) ? $raVal['type'] : '');

                    $transferAirport[] = $dealTransferAirportObj;
                }
            }
            $dealResObj->setTransferAirports($transferAirport);
        }

        /*
         * Build Transfer Vehicles Array
         */
        if (isset($resultAttr['transferVehiclesListingXml'])) {
            $transferVehicleArr = $this->getmultipleXml($dom, $resultAttr['transferVehiclesListingXml']);
            $transferVehicle    = array();

            if (isset($transferVehicleArr['TransferInformationItems']['Quotes'])) {
                foreach ($transferVehicleArr['TransferInformationItems']['Quotes'] as $raKey => $raVal) {
                    $dealTransferVehicleObj = new DealTransferVehiclesListing();
                    $dealTransferVehicleObj->getCountry()->setCode(isset($raVal['country']) ? $raVal['country'] : '');
                    $dealTransferVehicleObj->setServiceType(isset($raVal['serviceType']) ? $raVal['serviceType'] : '');
                    $dealTransferVehicleObj->setNumOfPersons(isset($raVal['numOfPersons']) ? $raVal['numOfPersons'] : '');
                    $dealTransferVehicleObj->setTransferVehicle(isset($raVal['transferVehicle']) ? $raVal['transferVehicle'] : '');
                    $dealTransferVehicleObj->setTransferMinimumHourBooking(isset($raVal['transferMinimumHourBooking']) ? $raVal['transferMinimumHourBooking'] : '');
                    $dealTransferVehicleObj->setTransferPickupHour(isset($raVal['transferPickupHour']) ? $raVal['transferPickupHour'] : '');

                    $dealAirportObj = new DealAirport();
                    $dealAirportObj->setCode(isset($raVal['airportCode']) ? $raVal['airportCode'] : '');
                    $dealAirportObj->setName(isset($raVal['airportName']) ? $raVal['airportName'] : '');

                    $dealArrivalDepartureObj = new DealArrivalDeparture();
                    $dealArrivalDepartureObj->setArrivalPickupDate(isset($raVal['arrivalPickupDate']) ? $raVal['arrivalPickupDate'] : '');
                    $dealArrivalDepartureObj->setArrivalPickupTime(isset($raVal['arrivalPickupTime']) ? $raVal['arrivalPickupTime'] : '');
                    $dealArrivalDepartureObj->setArrivalFlightDate(isset($raVal['arrivalFlightDate']) ? $raVal['arrivalFlightDate'] : '');
                    $dealArrivalDepartureObj->setArrivalFlightTime(isset($raVal['arrivalFlightTime']) ? $raVal['arrivalFlightTime'] : '');
                    $dealArrivalDepartureObj->setDeparturePickupDate(isset($raVal['departurePickupDate']) ? $raVal['departurePickupDate'] : '');
                    $dealArrivalDepartureObj->setDeparturePickupTime(isset($raVal['departurePickupTime']) ? $raVal['departurePickupTime'] : '');
                    $dealArrivalDepartureObj->setDepartureFlightDate(isset($raVal['departureFlightDate']) ? $raVal['departureFlightDate'] : '');
                    $dealArrivalDepartureObj->setDepartureFlightTime(isset($raVal['departureFlightTime']) ? $raVal['departureFlightTime'] : '');

                    $dealTransferAirportPriceObj = new DealTransferAirportPrice();
                    $dealTransferAirportPriceObj->setArrivalPriceId(isset($raVal['arrivalPriceId']) ? $raVal['arrivalPriceId'] : '');
                    $dealTransferAirportPriceObj->setDeparturePriceId(isset($raVal['departurePriceId']) ? $raVal['departurePriceId'] : '');
                    $dealTransferAirportPriceObj->setPriceResort(isset($raVal['priceResort']) ? $raVal['priceResort'] : '');
                    $dealTransferAirportPriceObj->setPriceTotal(isset($raVal['priceTotal']) ? $raVal['priceTotal'] : '');
                    $dealTransferAirportPriceObj->setPriceTotalNet(isset($raVal['priceTotalNet']) ? $raVal['priceTotalNet'] : '');
                    $dealTransferAirportPriceObj->setPricePerPaxCar(isset($raVal['pricePerPaxCar']) ? $raVal['pricePerPaxCar'] : '');
                    $dealTransferAirportPriceObj->setPriceCurrency(isset($raVal['priceCurrency']) ? $raVal['priceCurrency'] : '');
                    $dealTransferAirportPriceObj->setPriceType(isset($raVal['priceType']) ? $raVal['priceType'] : '');
                    $dealTransferAirportPriceObj->setPriceRoundtrip(isset($raVal['priceRoundtrip']) ? $raVal['priceRoundtrip'] : '');
                    $dealTransferAirportPriceObj->setPriceZip(isset($raVal['priceZip']) ? $raVal['priceZip'] : '');
                    $dealTransferAirportPriceObj->setPriceCarType(isset($raVal['priceCarType']) ? $raVal['priceCarType'] : '');
                    $dealTransferAirportPriceObj->setPriceMinPax(isset($raVal['priceMinPax']) ? $raVal['priceMinPax'] : '');
                    $dealTransferAirportPriceObj->setPriceMaxPax(isset($raVal['priceMaxPax']) ? $raVal['priceMaxPax'] : '');
                    $dealTransferAirportPriceObj->setPriceSeasonFrom(isset($raVal['priceSeasonFrom']) ? $raVal['priceSeasonFrom'] : '');
                    $dealTransferAirportPriceObj->setPriceSeasonTo(isset($raVal['priceSeasonTo']) ? $raVal['priceSeasonTo'] : '');
                    $dealTransferAirportPriceObj->setPriceCarCategory(isset($raVal['priceCarCategory']) ? $raVal['priceCarCategory'] : '');
                    $dealTransferAirportPriceObj->setPriceCarModel(isset($raVal['priceCarModel']) ? $raVal['priceCarModel'] : '');
                    $dealTransferAirportPriceObj->setPriceCarMinimumPax(isset($raVal['priceCarMinimumPax']) ? $raVal['priceCarMinimumPax'] : '');

                    $dealTransferVehicleObj->setAirport(array($dealAirportObj));
                    $dealTransferVehicleObj->setDealArrivalDeparture(array($dealArrivalDepartureObj));
                    $dealTransferVehicleObj->setDealTransferAirportPrice(array($dealTransferAirportPriceObj));
                    $transferVehicle[] = $dealTransferVehicleObj;
                }
            }
            $dealResObj->setTransferVehicles($transferVehicle);
        }

        return $dealResObj;
    }
    /*
     * This method is used to format all mutipleXmls
     *
     * @param $dom xmlObject
     * @param $desiredArray taken from normalizer desired array()
     *
     * @return formated array of xml fields
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getmultipleXml(&$dom, $desiredArray = array())
    {
        $return = array();
        foreach ($desiredArray as $parentKey => $parentVal) {
            $multipleArray = array();
            $parentElem    = $dom->getElementsByTagName($parentKey);
            if ($parentElem && $parentElem->length) {
                for ($i = 0; $i < $parentElem->length; $i++) {
                    // MUST be an array
                    // FIRST LEVEL
                    foreach ($parentVal as $raKey => $raVal) {
                        if (is_array($raVal)) {
                            //ActivityPriceId
                            $raElem = $parentElem->item($i)->getElementsByTagName($raKey);

                            if ($raElem && $raElem->length) {
                                for ($i0 = 0; $i0 < $raElem->length; $i0++) {

                                    foreach ($raVal as $raKey2 => $raVal2) {
                                        if ('@xmlAttribute' == $raKey2) {
                                            foreach ($raVal2 as $raKey2Attr => $raVal2Attr) {
                                                $multipleArray[$raKey][$i0][$raKey2Attr] = $raElem->item($i0)->getAttribute($raVal2Attr);
                                            }
                                        } elseif (is_array($raVal2)) {

                                            // SECOND LEVEL
                                            //PackagePrices, ActivityPriceCapacity
                                            $raElem3 = $raElem->item($i0)->getElementsByTagName($raKey2);
                                            if ($raElem3 && $raElem3->length) {
                                                for ($i1 = 0; $i1 < $raElem3->length; $i1++) {

                                                    foreach ($raVal2 as $raKey3 => $raVal3) {
                                                        if ('@xmlAttribute' == $raKey3) {
                                                            foreach ($raVal3 as $raKey3Attr => $raVal3Attr) {
                                                                $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3Attr] = $raElem3->item($i1)->getAttribute($raVal3Attr);
                                                            }
                                                        } elseif (is_array($raVal3)) {

                                                            //THIRD LEVEL
                                                            //Price
                                                            $raElem5 = $raElem3->item($i1)->getElementsByTagName($raKey3);
                                                            if ($raElem5 && $raElem5->length) {
                                                                for ($i2 = 0; $i2 < $raElem5->length; $i2++) {

                                                                    foreach ($raVal3 as $raKey4 => $raVal4) {
                                                                        if ('@xmlAttribute' == $raKey4) {
                                                                            foreach ($raVal4 as $raKey4Attr => $raVal4Attr) {
                                                                                $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4Attr] = $raElem5->item($i2)->getAttribute($raVal4Attr);
                                                                            }
                                                                        } elseif (is_array($raVal4)) {

                                                                            //FORTH LEVEL
                                                                            //AvailableDays
                                                                            $raElem7 = $raElem5->item($i2)->getElementsByTagName($raKey4);
                                                                            if ($raElem7 && $raElem7->length) {
                                                                                for ($i3 = 0; $i3 < $raElem7->length; $i3++) {

                                                                                    foreach ($raVal4 as $raKey5 => $raVal5) {
                                                                                        if ('@xmlAttribute' == $raKey5) {
                                                                                            foreach ($raVal5 as $raKey5Attr => $raVal5Attr) {
                                                                                                $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3][$raKey5Attr] = $raElem7->item($i3)->getAttribute($raVal5Attr);
                                                                                            }
                                                                                        } elseif (is_array($raVal5)) {

                                                                                            //FIFTH LEVEL
                                                                                            $raElem9 = $raElem7->item($i3)->getElementsByTagName($raKey5);
                                                                                            if ($raElem9 && $raElem9->length) {
                                                                                                for ($i4 = 0; $i4 < $raElem9->length; $i4++) {

                                                                                                    foreach ($raVal5 as $raKey6 => $raVal6) {
                                                                                                        if ('@xmlAttribute' == $raKey6) {
                                                                                                            foreach ($raVal6 as $raKey6Attr => $raVal6Attr) {
                                                                                                                $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3][$raKey5][$i4][$raKey6Attr]
                                                                                                                    = $raElem9->item($i4)->getAttribute($raVal6Attr);
                                                                                                            }
                                                                                                        } elseif (is_array($raVal6)) {

                                                                                                            //SIXTH LEVEL
                                                                                                            //@TODO - For now this part isnt being work on since my current response doesnt reach this yet.
                                                                                                        } else {
                                                                                                            switch ($raKey6) {
                                                                                                                case '@getNodeValue':
                                                                                                                    $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3][$raKey5][$i4]['nodeValue']
                                                                                                                        = $raElem9->item($i4)->nodeValue;
                                                                                                                    break;
                                                                                                                default:
                                                                                                                    $raElem10                                                                                        = $raElem9->item($i4)->getElementsByTagName($raVal6);
                                                                                                                    switch ($raElem10->length) {
                                                                                                                        case 0:
                                                                                                                            //Do nothing
                                                                                                                            break;
                                                                                                                        case 1:
                                                                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3][$raKey5][$i4][$raKey6]
                                                                                                                                = $raElem10->item($i)->nodeValue;
                                                                                                                            break;
                                                                                                                        default:
                                                                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3][$raKey5][$i4][$raKey6][]
                                                                                                                                = $raElem10->item($i)->nodeValue;
                                                                                                                            break;
                                                                                                                    }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            switch ($raKey5) {
                                                                                                case '@getNodeValue':
                                                                                                    $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3]['nodeValue'] = $raElem7->item($i3)->nodeValue;
                                                                                                    break;
                                                                                                default:
                                                                                                    $raElem8                                                                           = $raElem7->item($i3)->getElementsByTagName($raVal5);
                                                                                                    switch ($raElem8->length) {
                                                                                                        case 0:
                                                                                                            //Do nothing
                                                                                                            break;
                                                                                                        case 1:
                                                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3][$raKey5]   = $raElem8->item($i)->nodeValue;
                                                                                                            break;
                                                                                                        default:
                                                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][$i3][$raKey5][] = $raElem8->item($i)->nodeValue;
                                                                                                            break;
                                                                                                    }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                            switch ($raKey4) {
                                                                                case '@getNodeValue':
                                                                                    $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2]['nodeValue'] = $raElem5->item($i2)->nodeValue;
                                                                                    break;
                                                                                default:
                                                                                    $raElem6                                                             = $raElem5->item($i2)->getElementsByTagName($raVal4);
                                                                                    switch ($raElem6->length) {
                                                                                        case 0:
                                                                                            //Do nothing
                                                                                            break;
                                                                                        case 1:
                                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4]   = $raElem6->item($i)->nodeValue;
                                                                                            break;
                                                                                        default:
                                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][$i2][$raKey4][] = $raElem6->item($i)->nodeValue;
                                                                                            break;
                                                                                    }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            switch ($raKey3) {
                                                                case '@getNodeValue':
                                                                    $multipleArray[$raKey][$i0][$raKey2][$i1]['nodeValue'] = $raElem3->item($i1)->nodeValue;
                                                                    break;
                                                                default:
                                                                    $raElem4                                               = $raElem3->item($i1)->getElementsByTagName($raVal3);
                                                                    switch ($raElem4->length) {
                                                                        case 0:
                                                                            //Do nothing
                                                                            break;
                                                                        case 1:
                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3]   = $raElem4->item($i)->nodeValue;
                                                                            break;
                                                                        default:
                                                                            $multipleArray[$raKey][$i0][$raKey2][$i1][$raKey3][] = $raElem4->item($i)->nodeValue;
                                                                            break;
                                                                    }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            switch ($raKey2) {
                                                case '@getNodeValue':
                                                    $multipleArray[$raKey][$i0]['nodeValue'] = $raElem->item($i0)->nodeValue;
                                                    break;
                                                default:
                                                    $raElem2                                 = $raElem->item($i0)->getElementsByTagName($raVal2);
                                                    switch ($raElem2->length) {
                                                        case 0:
                                                            //Do nothing
                                                            break;
                                                        case 1:
                                                            $multipleArray[$raKey][$i0][$raKey2]   = $raElem2->item($i)->nodeValue;
                                                            break;
                                                        default:
                                                            $multipleArray[$raKey][$i0][$raKey2][] = $raElem2->item($i)->nodeValue;
                                                            break;
                                                    }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $raElem = $parentElem->item($i)->getElementsByTagName($raVal);
                            for ($i1 = 0; $i1 < $raElem->length; $i1++) {
                                $multipleArray[$i][$raKey][$i1] = $raElem->item($i1)->nodeValue;
                            }
                        }
                    }
                }
            }
            $return[$parentKey] = $multipleArray;
        }

        return $return;
    }
    /*
     *
     *
     * The following methods are used for scripts to retrieve different data
     * from City Discovery APIs and insert them to our DB
     *
     */

    /*
     * This method gets all the activities for all cities with all different deal types
     * We should loop through each city to get its activities
     *
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getCityDiscoveryActivities($closeEntityManagerOnCompletion = false)
    {

        $query         = $this->em->createQueryBuilder();
        $dealCityCodes = $query->select('dc.id, dc.cityCode, dc.countryCode, dcnt.id as countryId')
            ->from('DealBundle:DealCity', 'dc')
            ->innerJoin('DealBundle:DealCountry', 'dcnt', 'WITH', "dc.countryCode=dcnt.countryCode")
            ->where('dc.dealApiSupplierId = 5')
            ->getQuery();
        $city_codes    = $dealCityCodes->getResult();

        if (!sizeof($city_codes)) return;

        foreach ($city_codes as $city) {
            usleep(100000); // sleep for 100 milliseconds
            echo "\n".date('c').' '.$city["countryCode"].' |||||| '.$city["cityCode"].' |||||| '.$city["countryId"];
            $attractionsTextsss = $this->getDiscoveryCityAllActivities($city);
        }
        echo "\n ********** DONE INSERTING ALL RECORDS ********** ";

        if ($closeEntityManagerOnCompletion) $this->em->close();
    }
    /*
     * This method gets all the activities of a city and a country for all different deal types
     *
     * @param $cityCode
     * @param $countryCode
     *
     * @return array of activities per selected city and country
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getDiscoveryCityAllActivities($city)
    {
        /*
         * The above categories from city discovery will be distributed into three types
         * Tours :       1
         * Attractions : 5
         * Activities:   3
         * I collected these in an array with indexes the categories ids and value the deal type in our DB deal_type table
         *
         */
        $deal_types     = array();
        $deal_types[1]  = 2;
        $deal_types[2]  = 5;
        $deal_types[3]  = 2;
        $deal_types[4]  = 5;
        $deal_types[5]  = 5;
        $deal_types[6]  = 3;
        $deal_types[7]  = 2;
        $deal_types[8]  = 3;
        $deal_types[9]  = 2;
        $deal_types[10] = 2;
        $deal_types[11] = 2;
        $deal_types[12] = 2;
        $deal_types[13] = 2;
        $deal_types[14] = 2;
        $deal_types[15] = 2;
        $deal_types[16] = 3;
        $deal_types[17] = 3;
        $deal_types[19] = 3;
        $deal_types[20] = 5;
        $deal_types[21] = 5;
        $deal_types[22] = 2;
        $deal_types[23] = 3;
        $deal_types[24] = 2;
        $deal_types[25] = 5;
        $deal_types[26] = 5;
        $deal_types[31] = 5;
        $deal_types[35] = 2;
        $deal_types[36] = 2;
        $deal_types[37] = 2;
        $deal_types[38] = 2;
        $deal_types[39] = 2;
        $cityCode       = $city["cityCode"];
        $countryCode    = $city["countryCode"];

        $xmlRequest = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<CityDiscovery ProcessType="ActivityDisplay">
   <POS>
      <Source AgentSine="4866TOURI" AgentDutyCode="tu63tourist38409184" />
   </POS>
   <ActivityCity>$cityCode</ActivityCity>
   <ActivityCountry>$countryCode</ActivityCountry>
   <ActivityCategory></ActivityCategory>
   <ActivityContentLanguage>en</ActivityContentLanguage>
   <ActivityCurrency>USD</ActivityCurrency>
   <Page>1</Page>
</CityDiscovery>
EOL;

        $getStaticDataResponse = $this->utils->send_data('https://www.city-discovery.com/api/cd/init/1.4/beta/', $xmlRequest, \HTTP_Request2::METHOD_POST);
        $dom                   = new \DOMDocument();
        try {
            $dom->loadXml($getStaticDataResponse['response_text']);
        } catch (\Exception $e) {
            file_put_contents("/data/log/deals/scripts/activities/activity_display_".$cityCode.".xml", $getStaticDataResponse['response_text']);

            return array();
        }

        $destination = $dom->getElementsByTagName('Destination');
        $totalPages  = 0;
        $currentPage = 0;
        $totalCount  = 0;
        if ($destination && $destination->length) {
            $totalPages  = $destination->item(0)->getElementsByTagName('TotalPages')->item(0)->nodeValue;
            $currentPage = $destination->item(0)->getElementsByTagName('CurrentPage')->item(0)->nodeValue;
            $totalCount  = $destination->item(0)->getElementsByTagName('TotalCount')->item(0)->nodeValue;
        }
        echo "\n\nFound ".$totalCount." activities divided to ".$totalPages." pages \n";
        for ($p = 1; $p <= $totalPages; $p++) {
            echo "\n current page = ".$p."\n";
            $xmlRequest = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<CityDiscovery ProcessType="ActivityDisplay">
   <POS>
      <Source AgentSine="4866TOURI" AgentDutyCode="tu63tourist38409184" />
   </POS>
   <ActivityCity>$cityCode</ActivityCity>
   <ActivityCountry>$countryCode</ActivityCountry>
   <ActivityCategory></ActivityCategory>
   <ActivityContentLanguage>en</ActivityContentLanguage>
   <ActivityCurrency>USD</ActivityCurrency>
   <Page>$p</Page>
</CityDiscovery>
EOL;

            $getStaticDataResponse = $this->utils->send_data('https://www.city-discovery.com/api/cd/init/1.4/beta/', $xmlRequest, \HTTP_Request2::METHOD_POST);
            $dom                   = new \DOMDocument();
            try {
                $dom->loadXml($getStaticDataResponse['response_text']);
            } catch (\Exception $e) {
                file_put_contents("/data/log/deals/scripts/activities/activity_display_".$cityCode.".xml", $getStaticDataResponse['response_text']);

                return array();
            }
            $activities = $dom->getElementsByTagName('Tour');

            if ($activities && $activities->length) {
                echo "\n\nFound ".$activities->length." activities"."\n";
                for ($i = 0; $i < $activities->length; $i++) {
                    echo "  ITERATION NUMBER =============== ".$i."\n";
                    $priority                             = 0;
                    $activityPriceAdultBeforePromoCodeUSD = 0;
                    $latitude                             = 0;
                    $longitude                            = 0;
                    $activityDuration                     = 0;

                    echo "-------------------------------------------------- --------------------- -----------------------------------------------------".$i."\n";
                    $activityId = $activities->item($i)->getElementsByTagName('ActivityID')->item(0)->nodeValue;
                    echo "ActivityId  :  ".$activityId;
                    echo "\n";

                    $activityPrices = null;

                    try {
                        $activityPrices = $activities->item($i)->getElementsByTagName('ActivityPrices');
                    } catch (\Exception $e) {
                        echo " ERROR:: No ActivityPrices tag for ActivityId  :  ".$activityId;
                        echo "\n";
                        file_put_contents("/data/log/deals/scripts/activities/activity_display_".$cityCode."_".$activityId.".xml", $getStaticDataResponse['response_text']);
                        return array();
                    }

                    if (!$activityPrices || !$activityPrices->length) {
                        echo " ERROR:: No ActivityPrices children tags for ActivityId  :  ".$activityId;
                        echo "\n";
                        file_put_contents("/data/log/deals/scripts/activities/activity_display_".$cityCode."_".$activityId.".xml", $getStaticDataResponse['response_text']);
                        return array();
                    }

                    $unitNode                             = $activities->item($i)->getElementsByTagName('ActivityPrices')->item(0)
                            ->getElementsByTagName('ActivityPriceId')->item(0)
                            ->getElementsByTagName('PackagePrices')->item(0)
                            ->getElementsByTagName('Price')->item(0)
                            ->getElementsByTagName('Units')->item(0)
                            ->getElementsByTagName('Unit')->item(0);
                    //$activityPriceAdultBeforePromoCodeUSD = $activities->item($i)->getElementsByTagName('ActivityPrices')->item(0)->getElementsByTagName('ActivityPriceId')->item(0)->getElementsByTagName('ActivityPriceAdultBeforePromoCodeUSD')->item(0)->nodeValue;
                    $activityPriceAdultBeforePromoCodeUSD = $unitNode->getElementsByTagName('BeforeDiscount')->item(0)->nodeValue;
                    echo "ActivityPriceAdultBeforePromoCodeUSD  :  ".$activityPriceAdultBeforePromoCodeUSD;
                    echo "\n";

                    $activityPriceAdultNetUSD = $unitNode->getElementsByTagName('ChargePrice')->item(0)->nodeValue;
                    echo "ActivityPriceAdultNetUSD  :  ".$activityPriceAdultNetUSD;
                    echo "\n";

                    $activityDuration = $activities->item($i)->getElementsByTagName('ActivityDuration')->item(0)->nodeValue;
                    echo "activityDuration  :  ".$activityDuration;
                    echo "\n";

                    echo " Calling   getActivityDetails ::   \n";
                    $activityDetails     = $this->getActivityDetails($activityId);
                    $activityName        = 'NA';
                    $activityHighlights  = 'NA';
                    $activityDescription = 'NA';
                    $activityCity        = 'NA';
                    if (!empty($activityDetails)) {

                        if (isset($activityDetails['activityName']) && !empty($activityDetails['activityName'])) $activityName = $activityDetails['activityName'];

                        if (isset($activityDetails['activityHighlights']) && !empty($activityDetails['activityHighlights'])) $activityHighlights = $activityDetails['activityHighlights'];

                        if (isset($activityDetails['activityDescription']) && !empty($activityDetails['activityDescription'])) $activityDescription = $activityDetails['activityDescription'];

                        if (isset($activityDetails['activityCity']) && !empty($activityDetails['activityCity'])) $activityCity = $activityDetails['activityCity'];

                        if (isset($activityDetails['ActivityPriceCurrency']) && !empty($activityDetails['ActivityPriceCurrency'])) $activityPriceCurrency = $activityDetails['ActivityPriceCurrency'];

                        if (isset($activityDetails['ActivityPriceDateBegins']) && !empty($activityDetails['ActivityPriceDateBegins']))
                                $activityPriceDateBegins = $activityDetails['ActivityPriceDateBegins'];

                        if (isset($activityDetails['ActivityPriceDateEnds']) && !empty($activityDetails['ActivityPriceDateEnds'])) $activityPriceDateEnds = $activityDetails['ActivityPriceDateEnds'];

                        if (isset($activityDetails['longitude']) && !empty($activityDetails['longitude'])) $longitude = $activityDetails['longitude'];

                        if (isset($activityDetails['latitude']) && !empty($activityDetails['latitude'])) $latitude = $activityDetails['latitude'];

                        $createdAt  = new \DateTime("now");
                        $countryId  = $city["countryId"];
                        $dealCityId = $city["id"];
                        $dealApiId  = 5;
                        // we will set published = 3 now temprorarily for the new City Discovery API
                        // those records later will be updated to 1 at a laetr stage
                        $published  = 3;

                        echo "\n dealCode = ".$activityId;
                        echo "\n  dealname = ".$activityName;
                        echo "\n description = ".$activityDescription;
                        echo "\n startTime = ".$activityPriceDateBegins;
                        echo "  endTime = ".$activityPriceDateEnds;
                        echo '\n';

                        $dealDetails = new DealDetails();
                        $dealDetails->setDealCode($activityId);
                        $dealDetails->setDealName($activityName);
                        $dealDetails->setDescription($activityDescription);
                        $dealDetails->setStartTime(new \DateTime($activityPriceDateBegins));
                        $dealDetails->setEndTime(new \DateTime($activityPriceDateEnds));
                        $dealDetails->setCountryId($countryId);
                        $dealDetails->setDealCityId($dealCityId);
                        $dealDetails->setPrice($activityPriceAdultNetUSD);
                        $dealDetails->setCurrency($activityPriceCurrency);
                        $dealDetails->setCreatedAt($createdAt);
                        $dealDetails->setUpdatedAt($createdAt);
//                        $dealDetails->setDealTypeId($activityTypeId);
                        $dealDetails->setDealApiId($dealApiId);
                        $dealDetails->setPublished($published);

                        if ($longitude) {
                            echo "ActivityStartingPlace long :-> ".$longitude;
                            echo '\n';
                            $dealDetails->setLongitude($longitude);
                        }

                        if ($latitude) {
                            echo "ActivityStartingPlace Lat :-> ".$latitude;
                            echo '\n';
                            $dealDetails->setLatitude($latitude);
                        }
                        $dealDetails->setPriority($priority);
                        $dealDetails->setPriceBeforePromo($activityPriceAdultBeforePromoCodeUSD);

                        $dealDetails->setDuration($activityDuration);
                        $categorySubListCOunt = $activities->item($i)->getElementsByTagName('ActivityCategory')->length;
                        $categoryParent       = $activities->item($i)->getElementsByTagName('ActivityCategory');
                        $categoryParentId     = 0;
                        echo '\n categorySubListCOunt     =   '.$categorySubListCOunt."\n";
                        // Now guess the the type Id of the deal for the relative deal
                        for ($f = 0; $f < $categorySubListCOunt; $f++) {
                            $categoryParentId = $categoryParent->item($f)->getAttribute('ID');
                            if (isset($deal_types[$categoryParentId]) && !empty($deal_types[$categoryParentId])) {
                                $activityTypeId = $deal_types[$categoryParentId];
                                break;
                            }
                        }
                        if (isset($activityTypeId) && !empty($activityTypeId)) {
                            $dealDetails->setDealTypeId($activityTypeId);
                        } else {
                            //set default deal type to tour that is of value 2
                            $dealDetails->setDealTypeId(2);
                        }
                        try {

                            try {
                                $this->em->persist($dealDetails);
                            } catch (\Doctrine\ORM\ORMInvalidArgumentException $invalidArgumentException) {

                                echo "\n ORMInvalidArgumentException:: ".$invalidArgumentException->getMessage();

                                continue;
                            }

                            try {
                                $this->em->flush();
                            } catch (\Doctrine\ORM\OptimisticLockException $lockException) {

                                echo "\n OptimisticLockException:: ".$lockException->getMessage();

                                continue;
                            }
                        } catch (\Doctrine\ORM\ORMException $ormException) {

                            echo "\n ORMException:: ".$ormException->getMessage();

                            continue;
                        }

                        $last_inserted_id = $dealDetails->getId('id');

                        if ($last_inserted_id) {
                            echo "\n last_inserted_id to deal details table = $last_inserted_id";
                        }
                        // now getting the categories for this deal and insert them to the related tables deal_detail_to_category
                        for ($c = 0; $c < $categorySubListCOunt; $c++) {
                            $categoryParentId     = $categoryParent->item($c)->getAttribute('ID');
                            echo "\n\n categoryParentId ".$categoryParentId."\n";
                            $dealDetailToCategory = new DealDetailToCategory();
                            $dealDetailToCategory->setDealDetailsId($last_inserted_id);
                            $dealDetailToCategory->setDealCategoryId($categoryParentId);
                            try {

                                try {
                                    $this->em->persist($dealDetailToCategory);
                                } catch (\Doctrine\ORM\ORMInvalidArgumentException $invalidArgumentException) {

                                    echo "\n ORMInvalidArgumentException:: ".$invalidArgumentException->getMessage();

                                    continue;
                                }

                                try {
                                    $this->em->flush();
                                } catch (\Doctrine\ORM\OptimisticLockException $lockException) {

                                    echo "\n OptimisticLockException:: ".$lockException->getMessage();

                                    continue;
                                }
                            } catch (\Doctrine\ORM\ORMException $ormException) {

                                echo "\n ORMException:: ".$ormException->getMessage();

                                continue;
                            }

                            $last_inserted_detailtocategoryid = $dealDetailToCategory->getId('id');

                            if ($last_inserted_detailtocategoryid) {
                                echo "\n last_inserted_id to DealDetailToCategory table = $last_inserted_detailtocategoryid";
                            }
                        }
                    } else {
                        echo "\n NO ACTIVITIES TEXT RETURNED FOR activityId  ".$activityId."********** ";
                    }
                }
            } else {
                echo "\n NO ACTIVITIES RETURNED FOR country ".$countryCode.' |||||| '.$cityCode."********** ";
            }
        }
    }
    /*
     * This method gets all the needed data for a specific activity from City Discovery
     *
     * @param activityId the id of the activity as per city discovery reference
     *
     * @return array of fields needed for the requested activity
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getActivityDetails($activityId)
    {

        $username = "4866TOURI";
        $password = "tu63tourist38409184";

        $xmlRequest            = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<CityDiscovery  ProcessType="ActivityDetails">
   <POS>
       <Source AgentSine="4866TOURI" AgentDutyCode="tu63tourist38409184" />
   </POS>
   <Tour ActivityID="$activityId">
       <ActivityCurrency>USD</ActivityCurrency>
       <ActivityContentLanguage>en</ActivityContentLanguage>
       <NoCache>1</NoCache>
   </Tour>
</CityDiscovery>
EOL;
        //$data                  = array("data" => $xmlRequest);
        $getStaticDataResponse = $this->utils->send_data('https://www.city-discovery.com/api/cd/init/1.4/beta/', $xmlRequest, \HTTP_Request2::METHOD_POST);
        //$getStaticDataResponse['response_text'] = $this->cityDiscoveryHandler->curlResponse($xmlRequest);

        $dom = new \DOMDocument();
        try {
            $dom->loadXml($getStaticDataResponse['response_text']);
        } catch (\Exception $e) {
            file_put_contents("/data/log/deals/scripts/activities/activity_details_".$activityId.".xml", $getStaticDataResponse['response_text']);

            return array();
        }
        echo $getStaticDataResponse['response_text']."\n";

        $activities = $dom->getElementsByTagName('Tour');

        $items = array();
        if ($activities && $activities->length) {
            echo "\n\nFound ".$activities->length." Attractions"."\n";
            for ($i = 0; $i < $activities->length; $i++) {
                echo "-------------------------------------------------- --------------------- -----------------------------------------------------".$i."\n";
                $activityId          = $activities->item($i)->getElementsByTagName('ActivityID')->item(0)->nodeValue;
                echo "ActivityId  :  ".$activityId;
                echo "\n";
                $items['activityId'] = $activityId;

                $activityName          = $activities->item($i)->getElementsByTagName('ActivityName')->item(0)->nodeValue;
                $items['activityName'] = $activityName;

                $activityHighlights          = $activities->item($i)->getElementsByTagName('ActivityHighlights')->item(0)->nodeValue;
                $items['activityHighlights'] = $activityHighlights;

                $activityDescription          = $activities->item($i)->getElementsByTagName('ActivityDescription')->item(0)->nodeValue;
                $items['activityDescription'] = $activityDescription;

                $activityCity          = $activities->item($i)->getElementsByTagName('ActivityCity')->item(0)->nodeValue;
                $items['activityCity'] = $activityCity;

                //Checked Length first cause sometimes these fields are not present
                if ($activities->item($i)->getElementsByTagName('ActivityStartingPlaces')->length) {
                    if ($activities->item($i)->getElementsByTagName('ActivityStartingPlaces')->item(0)
                            ->getElementsByTagName('ActivityStartingPlace')->length) {

                        $ActivityStartingPlacesNode = $activities->item($i)->getElementsByTagName('ActivityStartingPlaces')->item(0)
                                ->getElementsByTagName('ActivityStartingPlace')->item(0);

                        if ($ActivityStartingPlacesNode->getElementsByTagName('Address')->length) {
                            $activityStartingPlace          = $ActivityStartingPlacesNode->getElementsByTagName('Address')->item(0)->nodeValue;
                            $items['ActivityStartingPlace'] = $activityStartingPlace;
                        }

                        if ($ActivityStartingPlacesNode->getElementsByTagName('Long')->length) {
                            $longitude          = $ActivityStartingPlacesNode->getElementsByTagName('Long')->item(0)->nodeValue;
                            $items['longitude'] = $longitude;
                        }

                        if ($ActivityStartingPlacesNode->getElementsByTagName('Lat')->length) {
                            $latitude          = $ActivityStartingPlacesNode->getElementsByTagName('Lat')->item(0)->nodeValue;
                            $items['latitude'] = $latitude;
                        }
                    }
                }
                //Checked Length of ActivityPrices first as sometimes these fields are not present
                if ($activities->item($i)->getElementsByTagName('ActivityPrices')->length) {

                    $priceNode          = $activities->item($i)->getElementsByTagName('ActivityPrices')->item(0)
                            ->getElementsByTagName('ActivityPriceId')->item(0)
                            ->getElementsByTagName('PackagePrices')->item(0)
                            ->getElementsByTagName('Price')->item(0);
                    $activityPriceAdult = $priceNode->getElementsByTagName('Units')->item(0)
                            ->getElementsByTagName('Unit')->item(0)
                            ->getElementsByTagName('ChargePrice')->item(0)->nodeValue;

                    $items['ActivityPriceAdult'] = $activityPriceAdult;

                    $activityPriceAdultBeforePromoCodeUSD          = $priceNode->getElementsByTagName('Units')->item(0)
                            ->getElementsByTagName('Unit')->item(0)
                            ->getElementsByTagName('BeforeDiscount')->item(0)->nodeValue;
                    $items['ActivityPriceAdultBeforePromoCodeUSD'] = $activityPriceAdultBeforePromoCodeUSD;

                    $activityPriceCurrency          = $priceNode->getElementsByTagName('Units')->item(0)
                            ->getElementsByTagName('Unit')->item(0)
                            ->getElementsByTagName('Currency')->item(0)->nodeValue;
                    $items['ActivityPriceCurrency'] = $activityPriceCurrency;

                    $activityPriceDateBegins          = $priceNode->getElementsByTagName('SeasonFrom')->item(0)->nodeValue;
                    $items['ActivityPriceDateBegins'] = $activityPriceDateBegins;

                    $activityPriceDateEnds          = $priceNode->getElementsByTagName('SeasonTo')->item(0)->nodeValue;
                    $items['ActivityPriceDateEnds'] = $activityPriceDateEnds;
                }

                $activityDuration          = $activities->item($i)->getElementsByTagName('ActivityDuration')->item(0)->nodeValue;
                $items['ActivityDuration'] = $activityDuration;

                $activityDurationText          = $activities->item($i)->getElementsByTagName('ActivityDurationText')->item(0)->nodeValue;
                $items['ActivityDurationText'] = $activityDurationText;


                $activityAvailabilityType          = $activities->item($i)->getElementsByTagName('ActivityAvailabilityType')->item(0)->nodeValue;
                $items['ActivityAvailabilityType'] = $activityAvailabilityType;

                $activityCountry = $activities->item($i)->getElementsByTagName('ActivityCountry')->item(0)->nodeValue;

                $items['ActivityCountry'] = $activityCountry;

                $activityCity          = $activities->item($i)->getElementsByTagName('ActivityCity')->item(0)->nodeValue;
                $items['ActivityCity'] = $activityCity;

                $cancelcount = $activities->item($i)->getElementsByTagName('ActivityCancellation')->item(0)->getElementsByTagName('ActivityCancellationPolicy')->length;

                for ($c = 0; $c < $cancelcount; $c++) {
                    $percentage = $activities->item($i)->getElementsByTagName('ActivityCancellation')->item(0)->getElementsByTagName('ActivityCancellationPolicy')->item($c)->getAttribute('Percentage');
                    echo "Percentage  :  ".$percentage;
                    echo " ";
                    echo "<br>";
                    $param15    = $activities->item($i)->getElementsByTagName('ActivityCancellation')->item(0)->getElementsByTagName('ActivityCancellationPolicy')->item($c)->getAttribute('Day');
                    echo "DAY  :  ".$param15;
                    echo "<br>";
                }

                $categorySubListCOunt = $activities->item($i)->getElementsByTagName('ActivityCategory')->length;
                $categoryListObject   = $activities->item($i)->getElementsByTagName('ActivityCategory');
                $categoryParentId     = 0;
                echo 'categorySubListCOunt     =   '.$categorySubListCOunt."\n";
                for ($c = 0; $c < $categorySubListCOunt; $c++) {
                    $categoryParentId = $activities->item($i)->getElementsByTagName('ActivityCategories')->item(0)->getElementsByTagName('ActivityCategory')->item($c)->getAttribute('ID');
                    echo "\n\n categoryParentId ".$categoryParentId."\n";
                    $categoryParent   = $activities->item($i)->getElementsByTagName('ActivityCategories')->item(0)->getElementsByTagName('ActivityCategory')->item($c);
                }
            }
        } else {
            echo "\n\n No Activity Details found for $activityId  \n\n";
        }

        return $items;
    }
    /*
     *
     * This method loops through all the deals from deal details and for each deal get the relative images
     * from the api call of get activity details from city discovery services
     * then insert those into the table deal_image
     *
     * @param closeEntityManagerOnCompletion bool to close the em manages connection or not
     *
     * @return
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getCityDiscoveryImages($closeEntityManagerOnCompletion = false)
    {
        $query         = $this->em->createQueryBuilder();
        $dealDetails   = $query->select('dd.id, dd.dealCode')
            ->from('DealBundle:DealDetails', 'dd')
            ->where('dd.dealApiId = 5')
            ->getQuery();
        $dealDetailIds = $dealDetails->getResult();

        if (!sizeof($dealDetailIds)) return;

        foreach ($dealDetailIds as $dealDetailId) {
            usleep(100000); // sleep for 100 milliseconds
            $isDefault  = 1;
            $dealId     = $dealDetailId['id'];
            echo "\n".date('c').' '.$dealId;
            $activityId = $dealDetailId['dealCode'];
            echo "\nactivityId $activityId\n";
            $xmlRequest = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<CityDiscovery  ProcessType="ActivityDetails">
   <POS>
       <Source AgentSine="4866TOURI" AgentDutyCode="tu63tourist38409184" />
   </POS>
   <Tour ActivityID="$activityId">
       <ActivityCurrency>USD</ActivityCurrency>
       <ActivityContentLanguage>en</ActivityContentLanguage>
       <NoCache>1</NoCache>
   </Tour>
</CityDiscovery>
EOL;

            //$data                  = array("data" => $xmlRequest);
            $getStaticDataResponse = $this->utils->send_data('https://www.city-discovery.com/api/cd/init/1.4/beta/', $xmlRequest, \HTTP_Request2::METHOD_POST);
            //$getStaticDataResponse['response_text'] = $this->cityDiscoveryHandler->curlResponse($xmlRequest);

            $dom = new \DOMDocument();
            try {
                $dom->loadXml($getStaticDataResponse['response_text']);
            } catch (\Exception $e) {
                file_put_contents("/data/log/deals/scripts/activities/attraction_description_".$activityId.".xml", $getStaticDataResponse['response_text']);

                continue;
            }
//	echo $getStaticDataResponse['response_text']."\n";

            $activities = $dom->getElementsByTagName('Tour');
            if ($activities && $activities->length) {
                echo "\n\nFound ".$activities->length." activities for deal_code:: $activityId\n";
                for ($i = 0; $i < $activities->length; $i++) {
                    echo "-------------------------------------------------- --------------------- ----------------------------------------------------- $i\n";
                    $activityImageGallery = $activities->item($i)->getElementsByTagName('ActivityImageGallery');
//		    $activityImageGalleryCount =  $activities->item($i)->getElementsByTagName('ActivityImageGallery')->length;
                    echo "\nactivityImageGallery->length: ".$activityImageGallery->length."\n\n";
                    if ($activityImageGallery && $activityImageGallery->length) {
//			$activityImgMain = $activityImageGallery->item(0)->getElementsByTagName('ActivityGallery')->item(0)->getElementsByTagName('ActivityImgMain')->item(0)->nodeValue;
                        $activityGallery = $activityImageGallery->item(0)->getElementsByTagName('ActivityImage');
//			echo "ActivityImgMain  :  ". $activityImgMain;echo "<br>";
                        echo "activityGallery->length: ".$activityGallery->length."\n\n";
                        if ($activityGallery && $activityGallery->length) {
                            for ($j = 0; $j < $activityGallery->length; $j++) {
                                $activityImgMain = $activityGallery->item($j)->getElementsByTagName('ActivityImgMain')->item(0)->nodeValue;
                                echo "\nActivityImgMain: $activityImgMain\n\n";
                                echo "isDefault: $isDefault\n\n";
                                $dealImage       = new DealImage();
                                $dealImage->setPath($activityImgMain);
                                $dealImage->setDealDetailId($dealId);
                                $dealImage->setIsDefault($isDefault);
                                $isDefault       = 0;

                                try {

                                    try {
                                        $this->em->persist($dealImage);
                                    } catch (\Doctrine\ORM\ORMInvalidArgumentException $invalidArgumentException) {

                                        echo "\nORMInvalidArgumentException:: ".$invalidArgumentException->getMessage();

                                        continue;
                                    }

                                    try {
                                        $this->em->flush();
                                    } catch (\Doctrine\ORM\OptimisticLockException $lockException) {

                                        echo "\nOptimisticLockException:: ".$lockException->getMessage();

                                        continue;
                                    }
                                } catch (\Doctrine\ORM\ORMException $ormException) {

                                    echo "\nORMException:: ".$ormException->getMessage();

                                    continue;
                                }

                                $img_last_inserted_id = $dealImage->getId('id');

                                if ($img_last_inserted_id) {
                                    echo "\ndeal_code:: $activityId, last_inserted_id to deal image table = $img_last_inserted_id";
                                }
                            }
                        }
                    }
                }
            } else {
                echo "\n\nNo Activity Details found for $activityId\n\n";
            }
        }

        echo "\n\n\n ********** DONE INSERTING ALL RECORDS ********** ";

        if ($closeEntityManagerOnCompletion) $this->em->close();
    }
}