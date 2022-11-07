<?php
namespace RestBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use RestBundle\Controller\FlightsController;
use PaymentBundle\Repository\PaymentRepository;

class FlightsServices
{
    protected $SabreflightServices;
    private $passengerNormaliser;
    private $em;
    private $translator;
    private $passengerHandler;
    private $sabreHandler;
    private $flightHandler;
    private $container;
    private $templating;
    /**
     * @var paymentRepository
     */
    protected $paymentRepository;
    public function __construct(Container $container, PaymentRepository $paymentRepository)
    {

        $this->container = $container;
        $this->paymentRepository = $paymentRepository;
    }

    public function airBookAction ($request){


        $userID = $request->request->get("userID");
        $sabreVariables['total_segments'] = $request->request->get("total_segments");
        $sabreVariables['DepartureDateTime'] = $request->request->get("DepartureDateTime");
        $sabreVariables['ArrivalDateTime'] = $request->request->get("ArrivalDateTime");
        $sabreVariables['FlightNumber'] = $request->request->get("FlightNumber");
        $sabreVariables['NumberInParty'] = $request->request->get("NumberInParty");
        $sabreVariables['ResBookDesigCode'] = $request->request->get("ResBookDesigCode");
        $sabreVariables['DestinationLocation'] = $request->request->get("DestinationLocation");
        $sabreVariables['MarketingAirline'] = $request->request->get("MarketingAirline");
        $sabreVariables['OperatingAirline'] = $request->request->get("OperatingAirline");
        $sabreVariables['OriginLocation'] = $request->request->get("OriginLocation");
        $sabreVariables["AdultsQuantity"] = $request->request->get("AdultsQuantity");
        $sabreVariables["ChildrenQuantity"] = $request->request->get("ChildrenQuantity");
        $sabreVariables["InfantsQuantity"] = $request->request->get("InfantsQuantity");
        $sabreVariables["PriceAmount"] = $request->request->get("PriceAmount");
        $sabreVariables["CurrencyCode"] = $request->request->get("CurrencyCode");
        $sabreVariables['ProjectUserName'] =$this->container->getParameter('flights')['vendors']['sabre']['PROJECTUSERNAME_TEST'];
        $sabreVariables['CompanyName'] = $this->container->getParameter('flights')['vendors']['sabre']['COMPANYNAME'];
        $sabreVariables['ProjectIPCC'] = $this->container->getParameter('flights')['vendors']['sabre']['PROJECTIPCC_TEST'];//PROJECTIPCC_PROD on production
        $sabreVariables['ProjectPCC'] =$this->container->getParameter('flights')['vendors']['sabre']['PROJECTPCC'];
        $sabreVariables['ProjectPassword'] =$this->container->getParameter('flights')['vendors']['sabre']['PROJECTPASSWORD_TEST'];//PROJECTPASSWORD_PROD on production
        $sabreVariables['ProjectDomain'] = $this->container->getParameter('flights')['vendors']['sabre']['PROJECTDOMAIN'];
        $sabreVariables['party_id_from'] = $this->container->getParameter('flights')['vendors']['sabre']['PARTY_ID_FROM'];
        $sabreVariables['party_id_to'] = $this->container->getParameter('flights')['vendors']['sabre']['PARTY_ID_TO'];
        $sabreVariables['Timestamp'] = date("Y-m-d\TH:i:s\Z", strtotime("now"));
        $sabreVariables['TimeToLive'] = date("Y-m-d\TH:i:s\Z", strtotime("now +15 minutes"));
        $sabreVariables['salt'] = "615ad516e0c17c90e09541f3f1a6badd";
        $sabreVariables['Service'] = "EnhancedAirBookRQ";
        $sabreVariables['Action'] = "EnhancedAirBookRQ";
        $from_mobile = 0;
        $connection_type = 2;// connection_type parameter is to differentiate between session for browsing flight availbilty with the number 1, and the sessions for issuing a ticket that have the number 2
        $sabreVariables['access_token'] = '';
        $sabreVariables['returnedConversationId'] = '';
        $sabreVariables['ConversationId'] = '';
        $sabreVariables['message_id'] = '';
        $create_session_response = $this->container->get('SabreServices')->createSabreSessionRequest($sabreVariables, $userID, $connection_type, ($from_mobile ? 'mobile' : 'web'));


        $sabreVariables['access_token'] = $create_session_response['AccessToken'];
        $sabreVariables['returnedConversationId'] = $create_session_response['ConversationId'];
        $sabreVariables['ConversationId'] = $create_session_response['ConversationId'];
        $sabreVariables['message_id'] = "mid:".$create_session_response['ConversationId'];

        $hiddenFields['access_token'] = $sabreVariables['access_token'];
        $hiddenFields['returnedConversationId'] = $sabreVariables['returnedConversationId'];



        $bookFlight = $this->container->get('SabreServices')->createEnhancedAirBookRequest($sabreVariables);

        $bookFlight['hiddenFields'] = $hiddenFields;
      //  $this->container->get('SabreServices')->closeSabreSessionRequest($sabreVariables, ($from_mobile ? 'mobile' : 'web'));
        return $bookFlight;
    }

    public function getPaymentByTransactionId($transactionId)
    {

        $payment = $this->paymentRepository->findOneByUuid($transactionId);

        return $payment;
    }
}
