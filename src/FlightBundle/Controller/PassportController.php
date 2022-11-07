<?php

namespace FlightBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PaymentBundle\Model\Payment;

class PassportController extends \TTBundle\Controller\DefaultController
{
    public function processPassportAction(Request $request)
    {
        $query = $request->query->all();

        $transactionId = $query['transactionId'];
        $pnrId = $query['pnrId'];


        $passengerDetails = $this->get('FlightServices')->getPassengerDetailsFromPnr($pnrId,$transactionId);

        $this->data['passengerDetails'] = $passengerDetails;
        $this->data['passengerIdentifier']['transactionId'] = $transactionId;
        $this->data['passengerIdentifier']['pnr'] = $pnrId;
        $this->data['flight_type'] = $passengerDetails['flight_type'];
        $this->data['tickets'] = $passengerDetails['tickets'];

        return $this->render('@Flight/flight/flight_add_passport.twig', $this->data);

    }

    public function processPassportSubmissionAction(Request $request)
    {
        $query = $request->request->all();


        $passengerDetails = $this->get('FlightServices')->updatePassengerDetailsPassportDetails($query);

        $passengerDetails['passportCheck'] = true;

        $sabreVariables                           = $this->container->get('SabreServices')->getSabreConnectionVariables(false);

        $sessionRequest                           = $this->container->get('SabreServices')->createSabreSessionRequest($sabreVariables);

        $sabreVariables['Service']                = "PassengerDetailsRQ";
        $sabreVariables['Action']                 = "PassengerDetailsRQ";
        $sabreVariables['access_token']           = $sessionRequest['AccessToken'];
        $sabreVariables['returnedConversationId'] = $sessionRequest['ConversationId'];


        $pnr = $this->container->get('SabreServices')->createPassengerDetailsRequest($sabreVariables, $passengerDetails);

        return $this->redirectToLangRoute('_issue_ticket', array('transaction_id' => $query['transactionId']));

        //return $this->render('@Flight/flight/flight_add_passport.twig', $this->data);

    }
}
