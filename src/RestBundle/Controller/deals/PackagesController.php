<?php

namespace RestBundle\Controller\deals;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Form\Form;
use DealBundle\Model\DealAirport;
use RestBundle\Controller\TTRestController;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PackagesController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request = Request::createFromGlobals();
        $this->utils   = $this->get('app.utils');
    }

    /**
     * Method POST
     * Getting Booking Details
     *
     * @RequestParam(name="activityId", requirements="\d+", description="activity id")
     * @param ParamFetcher $paramFetcher
     * @Rest\View(statusCode=201)
     *
     */
    public function bookingsAction(ParamFetcher $paramFetcher)
    {
        $post = $paramFetcher->all();

        $activityId = 0;
        if (isset($post['activityId']) && !empty($post['activityId'])) {
            $activityId = $post['activityId'];
        } else {
            throw new HttpException(400, $this->translator->trans('No activityId entered. Please try again.'));
        }

        $bookingInfo = $this->get('DealServices')->findBookingById($activityId);
        if (empty($bookingInfo)) {
            throw new HttpException(400, $this->translator->trans('Invalid bookingInfo. Please try again.'));
        }

        return $bookingInfo;
    }

    /**
     * Method GET
     * Get Deal Images by dealDetailsId
     *
     * @param $dealDetailsId
     * @return response
     */
    public function getDealImagesAction($dealDetailsId)
    {
        if (trim($dealDetailsId) == '') {
            throw new HttpException(422, $this->translator->trans('Invalid Deal Detail Id'));
        }

        $imagesEncoded = $this->get('DealServices')->getDealImages($dealDetailsId);
        $imagesDecoded = json_decode($imagesEncoded, true);
        $result        = $imagesDecoded['data'];

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Top Deals
     *
     * @param $dealDetailsId
     * @return response
     */
    public function getTopDealsAction($dealDetailsId)
    {
        if (trim($dealDetailsId) == '') {
            throw new HttpException(422, $this->translator->trans('Invalid Deal Detail Id'));
        }

        $topDealsEncoded    = $this->get('DealServices')->getTopDeals($dealDetailsId);
        $topDealsDecoded    = json_decode($topDealsEncoded, true);
        $result['topDeals'] = $topDealsDecoded['data'];
        if (!$result['topDeals']) {
            throw new HttpException(400, $this->translator->trans('Data not found.'));
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Booking data by bookingId
     *
     * @param $bookingId
     * @return response
     */
    public function findBookingByIdAction($bookingId)
    {
        if (trim($bookingId) == '') {
            throw new HttpException(422, $this->translator->trans('BookingId Invalid'));
        }

        $bookingEncoded = $this->get('DealServices')->findBookingById($bookingId);
        $bookingDecoded = json_decode($bookingEncoded, true);
        $result         = $bookingDecoded['data'];
        if (!$result) {
            throw new HttpException(400, $this->translator->trans('Data not found.'));
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Deal Details by activityId/Id
     *
     * @param $id
     * @return response
     */
    public function getDealDetailsAction($id)
    {
        if (trim($id) == '') {
            throw new HttpException(422, $this->translator->trans('Invalid Deal Id'));
        }

        $detailsEncoded = $this->get('DealServices')->getDetails($id);
        $detailsDecoded = json_decode($detailsEncoded, true);

        if (!$detailsDecoded['success']) {
            throw new HttpException(400, $this->translator->trans('Data not found.'));
        }
        
        $response = new Response(json_encode($detailsDecoded));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Deal City by cityId/id
     *
     * @param $id
     * @return response
     */
    public function getDealCityByIdAction($id)
    {
        if (trim($id) == '') {
            throw new HttpException(422, $this->translator->trans('Invalid Deal City Id'));
        }

        $cityEncoded = $this->get('DealServices')->getDealCityById($id);
        $cityDecoded = json_decode($cityEncoded, true);
        $result      = $cityDecoded['data'];
        if (!$result) {
            throw new HttpException(400, $this->translator->trans('Data not found.'));
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * GET Top Activities
     *
     * @return Response
     */
    public function getTopActivitiesAction()
    {
        $params                   = array();
        $params['langCode']       = $this->LanguageGet();
        $params['isCorpo']        = false;
        $params['dynamicSorting'] = true;
        $params['limit']          = $this->container->getParameter('AVERAGE_QUERY_LIMIT');
        $dealSC                   = $this->get('DealServices')->getDealSearchCriteria($params);
        $resultEncoded            = $this->get('DealServices')->getLandingPageTopTours($dealSC);
        $result                   = json_decode($resultEncoded, true);

        if (!$result['success']) {
            throw new HttpException(400, "Data not found.");
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Update Booking
     *
     * @return Response
     */
    public function updateBookingAction($id)
    {
        // no required fields
        $requirements = array();
        $post         = $this->fetchRequestData($requirements);

        if (trim($id) == '') {
            throw new HttpException(422, $this->translator->trans('Id invalid.'));
        }

        $bookingEncoded = $this->get('DealServices')->findBookingById($id);
        $booking        = json_decode($bookingEncoded, true);
        if (!$booking['success']) {
            throw new HttpException(422, $this->translator->trans('Id invalid.'));
        }

        $post['bookingId'] = $id;
        $bookingResultsObj = $this->get('DealServices')->getDealBookingCriteria($post);
        $resultEncoded     = $this->get('DealServices')->updateBookingData($bookingResultsObj);
        $result            = json_decode($resultEncoded, true);

        $response = new Response(json_encode($result['data']));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Get Transfer Vehicles
     *
     * @return Response
     */
    public function getTransferVehiclesAction()
    {
        // specify required fields
        $requirements = array(
            'airportCode',
            'arrivalHour',
            'arrivalInput',
            'arrivalMinute',
            'departureHour',
            'departureInput',
            'departureMinute',
            'destinationCity',
            'destinationCountry',
            'numOfPassengers',
            'typeOfTransfer'
        );

        // fech post json data
        $post            = $this->fetchRequestData($requirements);
        $dealTransferObj = $this->get('DealServices')->getDealTransferVehiclesListingCriteria($post);
        $resultEncoded   = $this->get('DealServices')->getTransferVehicles($dealTransferObj);
        $result          = json_decode($resultEncoded, true);

        if (!$result['success'] || (isset($result['data']['count']) && !$result['data']['count'])) {
            throw new HttpException(400, "Data not found.");
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Transfer Airport Listing by city & country
     *
     * @param $city
     * @param $country
     * @return response
     */
    public function getTransferAirportListingAction($city, $country)
    {
        if (trim($city) == '' || trim($country) == '') {
            throw new HttpException(422, $this->translator->trans('Country or City invalid'));
        }

        $params         = array('city' => $city, 'country' => $country);
        $airportObj     = $this->get('DealServices')->getDealTransferAirportListingCriteria($params);
        $airportEncoded = $this->get('DealServices')->getTransferAirportListing($airportObj);
        $airportDecoded = json_decode($airportEncoded, true);
        if (!$airportDecoded['success']) {
            throw new HttpException(400, $this->translator->trans('Data not found.'));
        }

        $result   = $airportDecoded['data'];
        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Transfer Cities Listing by country
     *
     * @param $country
     * @return response
     */
    public function getTransferCitiesListingAction($country)
    {
        if (trim($country) == '') {
            throw new HttpException(422, $this->translator->trans('Country invalid'));
        }

        $citiesEncoded = $this->get('DealServices')->getTransferCityListingByCountry($country);
        $citiesDecoded = json_decode($citiesEncoded, true);
        if (!$citiesDecoded['success']) {
            throw new HttpException(400, $this->translator->trans('Data not found.'));
        }

        $result   = $citiesDecoded['data'];
        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Cancel booking
     *
     * @return response
     */
    public function cancelBookingAction()
    {
        // specify required fields
        $requirements = array(
            array('type' => 'string', 'name' => 'bookingReference')
        );

        $params = $this->fetchRequestData($requirements);

        $bookingEncoded = $this->get('DealServices')->getBookingDataForCancellation($params['bookingReference'], 'bookingReference');
        $bookingDecoded = json_decode($bookingEncoded, true);
        $bookingDetails = $bookingDecoded['data'];

        if (empty($bookingDetails)) {
            throw new HttpException(422, $this->translator->trans("Invalid bookingReference. Please try again."));
        }

        $cancelEncoded = $this->get('DealServices')->cancelBooking($params['bookingReference'], $bookingDetails['email'], $bookingDetails['dealType']);
        $cancelDecoded = json_decode($cancelEncoded, true);
        $cancelResults = $cancelDecoded['data'];

        $response = new Response(json_encode($cancelResults));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Check availability of deal
     *
     * @param $activityId - this is dealCode
     * @return $response
     */
    public function checkAvailabilityAction($activityId = 0)
    {
        if (!$activityId) {
            throw new HttpException(422, $this->translator->trans('Invalid AcitivityId'));
        }

        $response = $this->get('DealServices')->checkActivityAvailability($activityId);
        return $response;
    }

    /**
     * Method GET
     * Get Top Attractions
     *
     * @return $response
     */
    public function getTopAttractionsAction()
    {
        $dealsList   = array();
        $dealsList[] = array('name' => 'Louvre', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/louvre.jpg'));
        $dealsList[] = array('name' => 'Burj Khalifa', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/burj-khalifa.jpg'));
        $dealsList[] = array('name' => 'Vatican', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/vatican.jpg'));
        $dealsList[] = array('name' => 'Buckingham Palace', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/buckingham-palace.jpg'));
        $dealsList[] = array('name' => 'Sagrada Familia', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/sagrada-familia.jpg'));
        $dealsList[] = array('name' => 'Eiffel Tower', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/eiffel-tower.jpg'));
        $dealsList[] = array('name' => 'Empire State', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/empire-state.jpg'));
        $dealsList[] = array('name' => 'Mount Fuji', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/deals/attractions/mount-fuji.jpg'));

        $params                = array();
        $params['attractions'] = $dealsList;
        $params['langCode']    = $this->LanguageGet();
        $params['isCorpo']     = false;
        $dealSC                = $this->get('DealServices')->getDealSearchCriteria($params);

        $attractions = $this->get('DealServices')->getTopAttractions($dealSC);
        $results     = json_decode($attractions, true);

        $response = new Response(json_encode($results['data']));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Top Destinations
     *
     * @return $response
     */
    public function getTopDestinationsAction()
    {
        $params                   = array();
        $params['langCode']       = $this->LanguageGet();
        $params['isCorpo']        = false;
        $params['dynamicSorting'] = true;
        $params['limit']          = $this->container->getParameter('AVERAGE_QUERY_LIMIT');

        $dealSC       = $this->get('DealServices')->getDealSearchCriteria($params);
        $destinations = $this->get('DealServices')->getTopDestinations($dealSC);
        $results      = json_decode($destinations, true);

        $response = new Response(json_encode($results['data']));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Booking Status
     *
     * @return $response
     */
    public function getBookingStatusAction($bookingReference)
    {
        $bookingEncoded = $this->get('DealServices')->findBookingByReference($bookingReference);
        $bookingDecoded = json_decode($bookingEncoded, true);
        $bookingDetails = $bookingDecoded['data'];

        if (empty($bookingDetails)) {
            throw new HttpException(422, $this->translator->trans('BookingReference invalid.'));
        }

        $statusEncoded = $this->get('DealServices')->getBookingStatus($bookingReference, $bookingDetails['email']);
        $statusDecoded = json_decode($statusEncoded, true);

        $results  = (isset($statusDecoded['success']) && $statusDecoded['success']) ? $statusDecoded['data'] : $statusDecoded;
        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Add Booking
     *
     * @return $response
     */
    public function addBookingAction()
    {
        $requirements = array(
            'packageId',
            'bookingReference',
            'bookingStatus',
            'bookingVoucherInformation',
            'cancellationPolicy',
            'firstName',
            'lastName',
            'address',
            'numOfAdults',
            'numOfChildren',
            'numOfInfants',
            'bookingTime',
            'dealHighlights',
            'totalPrice',
            'country',
            'dealCityId',
            'dealTypeId',
            'email',
            'bookingDate',
            'title',
            'packageId',
            'tourCode',
            'bookingNotes',
            'bookingQuoteId'
        );

        $params = $this->fetchRequestData($requirements);

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $params['userAgent']           = $this->request->headers->get('User-Agent');
        $params['customerIP']          = $this->utils->getUserIP();
        $params['userId']              = $user->getId();
        $params['langCode']            = $this->LanguageGet();
        $params['transactionSourceId'] = (isset($params['transactionSourceId'])) ?: $this->container->getParameter('WEB_REFERRER');
        $params['ccBillingAddress']    = $params['address'];

        $dealBookingId = '';
        $paymentUuid = '';
        $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria($params);
        $resultEncoded     = $this->get('DealServices')->saveBookingData($bookingDetailsObj);
        $decoded_result    = json_decode($resultEncoded, true);
        $dealBookingId     = $decoded_result['data'];

        if(!empty($dealBookingId) && $dealBookingId){
            $bookingDetailsEncoded                     = $this->get('DealServices')->findBookingById($dealBookingId);
            $bookingDetailsDecoded                     = json_decode($bookingDetailsEncoded, true);
            $bookingDetailsFinalObj                  = $bookingDetailsDecoded['data'];
            $paymentUuid = $bookingDetailsFinalObj['paymentUuid'];
        }
        $response = new Response(json_encode(array('dealBookingId' => $dealBookingId, 'paymentUuid' => $paymentUuid)));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Deal Search
     *
     * @return $response
     */
    public function dealSearchAction()
    {
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        $dealSC = $this->get('DealServices')->getDealSearchCriteria($post);
        $result = $this->get('DealServices')->dealSearch($dealSC);

        $response = new Response($result);
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Getting of Mandatory fields
     *
     * @return $response
     */
    public function getMandatoryFieldsAction()
    {
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        $result = array();
        //bookingQuoteId could be an array()
        if (!isset($post['bookingQuoteId'])) {
            throw new HttpException(422, $this->translator->trans('BookingQuoteId is required.'));
        }

        $quoteEncoded = $this->get('DealServices')->getBookingQuotesById($post['bookingQuoteId']);
        $quoteDecoded = json_decode($quoteEncoded, true);

        if (!$quoteDecoded['success']) {
            throw new HttpException(400, $this->translator->trans('Booking Quote data not found. Please try again.'));
        }

        $quote = $quoteDecoded['data'];
        //building the times array
        foreach ($quote as $key => $val) {
            $time                         = array();
            $time['timeId']               = $val['id'];
            $time['timeText']             = (strlen(trim($val['time']))) ? $val['time'] : $this->translator->trans('Within operating hours.');
            $result['bookingQuoteTime'][] = $time;
        }

        //get always $quote[0] cause there just the same with the others
        $result['dynamicFields'] = json_decode($quote[0]['dynamicFields']);

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Saving of Mandatory fields
     *
     * @return $response
     */
    public function saveMandatoryFieldAnswersAction()
    {
        $requirements = array(
            'bookingQuoteId'
        );
        $post         = $this->fetchRequestData($requirements);

        $quoteEncoded = $this->get('DealServices')->findBookingQuoteById($post['bookingQuoteId']);
        $quoteDecoded = json_decode($quoteEncoded, true);

        if (!$quoteDecoded['success']) {
            throw new HttpException(400, $this->translator->trans('Booking Quote data not found. Please try again.'));
        }

        $mandatoryObj     = $this->get('DealServices')->getDealMandatoryFieldsCriteria($post);
        $mandatoryEncoded = $this->get('DealServices')->saveMandatoryFieldAnswers($mandatoryObj);
        $mandatoryDecoded = json_decode($mandatoryEncoded, true);

        $result['success'] = $mandatoryDecoded['success'];
        $response          = new Response(json_encode($result));
        return $response;
    }
    /*
     * Method POST
     * This method gets the booking box per date you select in the deal details page.
     * If deal is not available and error message will be returned.
     *
     * @return response
     */

    public function getTourPriceDetailsAction()
    {
        $requirements = array(
            'packageId',
            'tourCode',
            'startDate'
        );

        $post = $this->fetchRequestData($requirements);

        $dealPriceObj  = $this->get('DealServices')->getDealPriceOptionCriteria($post);
        $resultEncoded = $this->get('DealServices')->getPriceDetails($dealPriceObj);
        $resultDecoded = json_decode($resultEncoded, true);

        $priceDetails = array();
        if ($resultDecoded['success']) {
            $priceDetails              = $resultDecoded['data'];
            $priceDetails['packageId'] = $post['packageId'];
        } else {
            $priceDetails['errorMessage'] = $resultDecoded['message'];
        }

        $response = new Response(json_encode($priceDetails));
        return $response;
    }
    /*
     * Method POST
     * This method gets quotation selecting the date and number of passengers
     *
     * @return response
     */

    public function getQuoteBookingAction()
    {
        $requirements = array(
            'packageId',
            'tourCode',
            'activityPriceId',
            'priceId',
            'bookingDate',
            'currency',
            'units'
        );

        $post         = $this->fetchRequestData($requirements);
        $dealQuoteObj = $this->get('DealServices')->getDealQuotationCriteria($post);
        $quoteEncoded = $this->get('DealServices')->getQuotation($dealQuoteObj);
        $quoteDecoded = json_decode($quoteEncoded, true);

        $return = array();
        if ($quoteDecoded['success']) {
            $return = $quoteDecoded['data'];
        } else {
            $return['errorMessage'] = $quoteDecoded['message'];
        }

        $response = new Response(json_encode($return));
        return $response;
    }

    /**
     * Method POST
     * Process Booking
     *
     * @return $response
     */
    public function processBookingAction()
    {
        // specify required fields
        $requirements = array(
            'transactionId'
        );

        $post = $this->fetchRequestData($requirements);

        $transactionId  = $post['transactionId'];
        $payment        = $this->get('DealServices')->findPaymentByUuid($transactionId);
        $bookingEncoded = $this->get('DealServices')->findBookingByUuid($transactionId);
        $bookingDecoded = json_decode($bookingEncoded, true);
        $dealBookingObj = $bookingDecoded['data'];

        if (!$payment || !$bookingDecoded['success']) {
            $this->get('PaymentServiceImpl')->voidOnHoldPayment($transactionId);
            throw new HttpException(422, $this->translator->trans('Transaction ID is invalid'));
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $params                    = array();
        $params['isCorporate']     = isset($params['isCorporate']) ? $params['isCorporate'] : false;
        $params['paymentRequired'] = isset($params['paymentRequired']) ? $params['paymentRequired'] : false;
        $params['bookingId']       = $dealBookingObj['id'];
        $params['userId']          = $user->getId();

        //corporate account
        if ($params['isCorporate']) {
            $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
            $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

            $onAccountOrCCObj             = $this->get('CorpoAccountServices')->getCorpoAccountPaymentType($userCorpoAccountId);
            $onAccountOrCC                = $onAccountOrCCObj['code'];
            $params['moduleId']           = $payment->getModuleId();
            $params['reservationId']      = $payment->getModuleTransactionId();
            $params['userCorpoAccountId'] = $userCorpoAccountId;
            $params['paymentType']        = $onAccountOrCC;
        }
        $bookingDetailsObj = $this->get('DealServices')->getDealBookingCriteria($params);
        $resultsEncoded    = $this->get('DealServices')->processBooking($bookingDetailsObj);

        return $resultsEncoded;
    }

    /**
     * Method GET
     * Deal Search autocomplete
     *
     * @return $response
     */
    public function dealSearchAutoCompleteAction()
    {
        $request = Request::createFromGlobals();
        $term    = $request->query->get('term', '');

        $result = $this->forward('TTBundle:Ajax:DealSearchNew', array(
            'term' => $term
        ));


        $jsonToArray = json_decode($result->getContent(), true);

        foreach ($jsonToArray as $key => $element) {
            unset($jsonToArray[$key]["label"]);
        }


        $arrayToJson = json_encode($jsonToArray);

        $response = new Response($arrayToJson);
        return $response;
    }

    /**
     * Method POST
     * This is the method called when approval of a booking
     *
     * @return $response
     */
    public function proceedBookingWithApprovalAction()
    {
        // specify required fields
        $requirements = array(
            'reservationId',
            'accountId',
            'userId',
            'transactionUserId',
            'requestServicesDetailsId'
        );

        $request = Request::createFromGlobals();
        $post    = $this->fetchRequestData($requirements);

        if (isset($post['reservationId']) && !empty($post['reservationId'])) {
            $resultEncoded  = $this->get('DealServices')->checkDealAvailability($post['reservationId']);
            $decoded_result = json_decode($resultEncoded, true);

            if ($decoded_result['success']) {
                $isAvailable = $decoded_result['data'];

                $params                             = array();
                $params['bookingId']                = $post['reservationId'];
                $params['accountId']                = $post['accountId'];
                $params['userId']                   = $post['userId'];
                $params['transactionUserId']        = $post['transactionUserId'];
                $params['requestServicesDetailsId'] = $post['requestServicesDetailsId'];
                $params['isAvailable']              = $isAvailable;
                $params['userAgent']                = $request->headers->get('User-Agent');

                $bookingObj = $this->get('DealServices')->getDealBookingCriteria($params);
                $results    = $this->get('DealServices')->processPendingBookingApproval($bookingObj);
            } else {
                $bookingEncoded                     = $this->get('DealServices')->findBookingById($post['reservationId']);
                $bookingDecoded                     = json_decode($bookingEncoded, true);
                $bookingDetailsObj                  = $bookingDecoded['data'];
                $params                             = array();
                $params['reservationId']            = $post['reservationId'];
                $params['requestServicesDetailsId'] = $post['requestServicesDetailsId'];
                $params['cityId']                   = $bookingDetailsObj['dealCityId'];

                $bookingApprovalObj = $this->get('DealServices')->getDealBookingCriteria($params);
                $results            = $this->get('DealServices')->processExpiredBookingApproval($bookingApprovalObj);
            }
        } else {
            throw new HttpException(422, $this->translator->trans('You are not allowed to approve this request'));
        }

        $response = new Response(json_encode($results));
        return $response;
    }
}
