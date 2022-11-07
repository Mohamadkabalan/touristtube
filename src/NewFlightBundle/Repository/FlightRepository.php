<?php
/**
 * Created by PhpStorm.
 * User: para-soft7
 * Date: 9/13/2018
 * Time: 6:38 PM
 */

namespace NewFlightBundle\Repository;

use Doctrine\ORM\EntityManager;
use NewFlightBundle\Entity\FlightSelectedSearchResult;
use NewFlightBundle\Entity\FlightDetailsSelectedSearchResult;
use NewFlightBundle\Entity\FlightBaggageInfo;
use NewFlightBundle\Entity\PassengerNameRecord;
use NewFlightBundle\Entity\PassengerDetail;
use NewFlightBundle\Entity\FlightDetail;
use NewFlightBundle\Entity\FlightInfo;

use NewFlightBundle\Model\CreateBargainRequest as FlightSelected;
use NewFlightBundle\Model\FlightDetails as FlightDetailsModel;
use NewFlightBundle\Model\PassengerNameRecord as PassengerNameRecordModel;


class FlightRepository
{
    /**
     * @var entityManager
     */
    protected $entityManager;

    function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Persist the search results to the database whenever the user search and select the flights
     *
     * @param $param
     *
     * @return $searchId
     */
    function insertSelectedFlightDetails(FlightSelected $flightSelected, $userId) {

        $flightSelectedSearchResult = new FlightSelectedSearchResult();
        $flightSelectedSearchResult->setUserId($userId);
        $flightSelectedSearchResult->setCustomerIp($_SERVER['REMOTE_ADDR'] );
        $flightSelectedSearchResult->setSearchDateTime(new \DateTime());
        $flightSelectedSearchResult->setFlightType($flightSelected->getTripType());
        $flightSelectedSearchResult->setAdtCount($flightSelected->getAdtCount());
        $flightSelectedSearchResult->setCnnCount($flightSelected->getCnnCount());
        $flightSelectedSearchResult->setInfCount($flightSelected->getInfCount());
        $flightSelectedSearchResult->setCabinSelected($flightSelected->getCabinPref());

        $this->entityManager->persist($flightSelectedSearchResult);
        $searchId= $flightSelectedSearchResult->getId();

        foreach ($flightSelected->getFlightDetailsSelectedSearchResult() as $detailsResults) {
            $flightDetailsSelectedSearchResult = new FlightDetailsSelectedSearchResult();
            $flightDetailsSelectedSearchResult->setFlightSelectedId($searchId);
            $flightDetailsSelectedSearchResult->setSegmentNumber($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getTotalSegment());
            $flightDetailsSelectedSearchResult->setFromLocation($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getFlightDeparture()->getAirportModel()->getName());
            $flightDetailsSelectedSearchResult->setToLocation($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getFlightArrival()->getAirportModel()->getName());
            $flightDetailsSelectedSearchResult->setIsStop($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->isStop());
            $flightDetailsSelectedSearchResult->setTerminalId($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getFlightDeparture()->getAirportModel()->getTerminalId());
            $flightDetailsSelectedSearchResult->setOperatingAirline($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getOperatingAirline()->getAirlineName());
            $flightDetailsSelectedSearchResult->setMarketingAirline($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getMarketingAirline()->getAirlineName());
            $flightDetailsSelectedSearchResult->setFlightNumber($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getFlightNumber());
            $flightDetailsSelectedSearchResult->setResBookDesignCode($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getResBookDesignCode());
            $flightDetailsSelectedSearchResult->setDuration($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getDuration());
            $flightDetailsSelectedSearchResult->setFareBasisCode($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getFareBasisCode());
            $flightDetailsSelectedSearchResult->setFareCalcLine($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getFareCalcLine());
            $flightDetailsSelectedSearchResult->setPrice($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getPrice());
            $flightDetailsSelectedSearchResult->setBaseFare($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getBaseFare());
            $flightDetailsSelectedSearchResult->setTaxes($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getTaxes());
            $flightDetailsSelectedSearchResult->setCurrency($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getCurrencyModel()->getName());

            $this->entityManager->persist($flightDetailsSelectedSearchResult);

            $flightSelectedDetailsId = $flightDetailsSelectedSearchResult->getId();

            $flightBaggageInfo = new FlightBaggageInfo();
            $flightBaggageInfo->setFlightSelectedDetailsId($flightSelectedDetailsId);
            $flightBaggageInfo->setPieces($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getBaggageAllowance()->getPiece());
            $flightBaggageInfo->setWeight($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getBaggageAllowance()->getWeight());
            $flightBaggageInfo->setUnit($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getBaggageAllowance()->getUnit());
            $flightBaggageInfo->setDescription($detailsResults->getFlightIteneraryGroupedModel()->getFlightIteneraryModel()->getFlightSegmentModel()->getBaggageAllowance()->getDescription());

            $this->entityManager->persist($flightBaggageInfo);

        }

        $this->entityManager->flush();

        return $searchId;
    }

    /**
     * Update Selected Flight Details, usually when PNR or Ticket has been created succesfully
     *
     * @param
     *
     * @return $searchID
     *
     */
    function updateSelectedFlightDetails(FlightSelectedSearchResult $flightSelectedSearchResult, $isPnrCreated = false, $isTicketIssued = false) {

        $flightSelectedSearchResult->setIsPnrCreated($isPnrCreated);
        $flightSelectedSearchResult->setIsTicketIssued($isTicketIssued);

        $this->entityManager->persist($flightSelectedSearchResult);
        $this->entityManager->flush();

        return $flightSelectedSearchResult->getId();
    }

    /**
     * Fetch Selected Flight Details
     *
     * @param array|mixed
     */
    function getSelectedFlightDetails($searchId) {

        $flightSelectedSearchResult = $this->entityManager->getRepository('NewFlightBundle:FlightSelectedSearchResult')->find($searchId);

        $flightDetailsSelectedSearchResult = $this->entityManager->getRepository('NewFlightBundle:FlightDetailsSelectedSearchResult')->findByflightSelectedId($flightSelectedSearchResult->getId());

        $flightBaggageInfo = $this->entityManager->getRepository('NewFlightBundle:FlightBaggageInfo')->findByFlightSelectedDetailsId($flightDetailsSelectedSearchResult->getId());

        return [
            'selectedSearchResult' => $flightSelectedSearchResult,
            'detailsSelectedSearchResult' => $flightDetailsSelectedSearchResult,
            'baggageInfo' => $flightBaggageInfo
        ];

    }

    /**
     * Create Passenger Name Record
     *
     * @param $param
     *
     * @return PassengerNameRecord
     */
    function createPassengerNameRecord(PassengerNameRecordModel $passengerNameRecordModel, $isCorporate = false) {

        $passengerNameRecord = new PassengerNameRecord();
        $passengerNameRecord->setUserId($passengerNameRecordModel->getId());
        $passengerNameRecord->setPnr($passengerNameRecordModel->getPNR());
        $passengerNameRecord->setPaymentUUID($passengerNameRecordModel->getPaymentUUID());
        $passengerNameRecord->setFirstName($passengerNameRecordModel->getPassengerDetailsModel->getFirstName());
        $passengerNameRecord->setSurname($passengerNameRecordModel->getPassengerDetailsModel->getLastName());
        $passengerNameRecord->setCountryOfResidence($passengerNameRecordModel->getCountryOfResidence()->getName());
        $passengerNameRecord->setEmail($passengerNameRecordModel->getEmail());
        $passengerNameRecord->setMobileCountryCode($passengerNameRecordModel->getCountryOfResidence()->getIso3());
        $passengerNameRecord->setAlternativeNumber($passengerNameRecordModel->getAlternativeNumber());
        $passengerNameRecord->setSpecialRequirement($passengerNameRecordModel->getSpecialRequirement());
        $passengerNameRecord->setStatus($passengerNameRecordModel->getStatus());
        $passengerNameRecord->setCreationDate(new \DateTime());
        $passengerNameRecord->setIsCorporateSite($isCorporate);

        $this->entityManager->persist($passengerNameRecord);
        $this->entityManager->flush();

        return $passengerNameRecord;
    }

    /**
     * Create Flight Info
     *
     * @param FlightItinerary $flightItinerary
     * @param PassengerNameRecord $passengerNameRecord
     *
     * @return FlightInfo
     */
    function createFlightInfo(FlightItinerary $flightItinerary, PassengerNameRecord $passengerNameRecord) {

        $flightInfo = new FlightInfo();
        $flightInfo->setPassengerNameRecord($passengerNameRecord);
        $flightInfo->setPrice($flightItinerary->getPrice());
        $flightInfo->setDisplayPrice($flightItinerary->getDisplayPrice());
        $flightInfo->setBaseFare($flightItinerary->getBaseFare());
        $flightInfo->setDisplayBaseFare($flightItinerary->getDisplayBaseFare());
        $flightInfo->setTaxes($flightItinerary->getTaxes());
        $flightInfo->setDisplayTaxes($flightItinerary->getDisplayTaxes());
        $flightInfo->setCurrency($flightItinerary->getCurrencyModel()->getName());
        $flightInfo->setDisplayCurrency($flightItinerary->getDisplayCurrency());
        $flightInfo->setRefundable($flightItinerary->getRefundable());
        $flightInfo->setOneWay($flightItinerary->getOneWay());
        $flightInfo->setMultiDestination($flightItinerary->getMultiDestination());

        $this->entityManager->persist($flightInfo);
        $this->entityManager->flush();

        return $flightInfo;
    }

    /**
     * Create Flight Detail
     *
     * @param FlightDetailsModel $flightDetailsModel
     * @param PassengerNameRecord $passengerNameRecord
     *
     * @return FlightDetail
     */
    function createFlightDetail(FlightDetailsModel $flightDetailsModel, PassengerNameRecord $passengerNameRecord) {

        $flightDetail = new FlightDetail();
        $flightDetail->setPassengerNameRecord($passengerNameRecord);
        $flightDetail->setSegmentNumber($flightDetailsModel->getSegmentNumber());
        $flightDetail->setDepartureAirport($flightDetailsModel->getDepartureAirport());
        $flightDetail->setArrivalAirport($flightDetailsModel->getArrivalAirport());
        $flightDetail->setDepartureDateTime($flightDetailsModel->getDepartureDateTime());
        $flightDetail->setArrivalDateTime($flightDetailsModel->getArrivalDateTime());
        $flightDetail->setAirline($flightDetailsModel->getAirline());
        $flightDetail->setOperatingAirline($flightDetailsModel->getOperatingAirline());
        $flightDetail->setFlightNumber($flightDetailsModel->getFlightNumber());
        $flightDetail->setCabin($flightDetailsModel->getCabin());
        $flightDetail->setFlightDuration($flightDetailsModel->getFlightDuration());
        $flightDetail->setStopIndicator($flightDetailsModel->getStopIndicator());
        $flightDetail->setStopDuration($flightDetailsModel->getStopDuration());
        $flightDetail->setType($flightDetailsModel->getType());
        $flightDetail->setResBookDesignCode($flightDetailsModel->getResBookDesignCode());
        $flightDetail->setFareCalcLine($flightDetailsModel->getFareCalcLine());
        $flightDetail->setLeavingBaggageInfo($flightDetailsModel->getLeavingBaggageInfo());
        $flightDetail->setReturningBaggageInfo($flightDetailsModel->getReturningBaggageInfo());
        $flightDetail->setDepartureTerminalId($flightDetailsModel->getDepartureTerminalId());
        $flightDetail->setArrivalTerminalId($flightDetailsModel->getArrivalTerminalId());
        $flightDetail->setFareBasisCode($flightDetailsModel->getFareBasisCode());
        $flightDetail->setAirlinePnr($flightDetailsModel->getAirlinePnr());

        $this->entityManager->persist($flightDetail);
        $this->entityManager->flush();

        return $flightDetail;
    }

    /**
     * Create Passenger Detail
     *
     * @param PassengerDetails $passengerDetails
     * @param PassengerNameRecord $passengerNameRecord
     *
     * @return PassengerDetail
     */
    function createPassengerDetail(PassengerDetails $passengerDetails, PassengerNameRecord $passengerNameRecord) {

        $passengerDetail = new PassengerDetail();
        $passengerDetail->setPassengerNameRecord($passengerNameRecord);
        $passengerDetail->setFirstName($passengerDetails->getFirstName());
        $passengerDetail->setSurname($passengerDetails->getSurname());
        $passengerDetail->setType($passengerDetails->getType());
        $passengerDetail->setGender($passengerDetails->getGender());
        $passengerDetail->setDateOfBirth($passengerDetails->getDateOfBirth());
        $passengerDetail->setFareCalcLine($passengerDetails->getFareCalcLine());
        $passengerDetail->setTicketNumber($passengerDetails->getTicketNumber());
        $passengerDetail->setTicketRph($passengerDetails->getTicketRph());
        $passengerDetail->setTicketStatus($passengerDetails->getTicketStatus());
        $passengerDetail->setLeavingBaggageInfo($passengerDetails->getLeavingBaggageInfo());
        $passengerDetail->setReturningBaggageInfo($passengerDetails->getReturningBaggageInfo());
        $passengerDetail->setPassportNo($passengerDetails->getPassportNo());
        $passengerDetail->setPassportExpiry($passengerDetails->getPassportExpiry());
        $passengerDetail->setPassportIssueCountry($passengerDetails->getPassportIssueCountry());
        $passengerDetail->setPassportNationalityCountry($passengerDetails->getPassportNationalityCountry());
        $passengerDetail->setIdNo($passengerDetails->getIdNo());


        $this->entityManager->persist($passengerDetail);
        $this->entityManager->flush();

        return $passengerDetail;
    }

    /**
     * Update Passenger Name Record
     *
     * @param PassengerNameRecord $passengerNameRecord
     *
     * @return PassengerNameRecord
     */
    function updatePassengerNameRecord(PassengerNameRecord $passengerNameRecord) {

        $this->entityManager->persist($passengerNameRecord);
        $this->entityManager->flush();

        return $passengerNameRecord;
    }

    /**
     * Update Flight Info
     *
     * @param FlightInfo $flightInfo
     *
     * @return FlightInfo
     */
    function updateFlightInfo(FlightInfo $flightInfo) {

        $this->entityManager->persist($flightInfo);
        $this->entityManager->flush();

        return $flightInfo;
    }

    /**
     * Update Flight Detail
     *
     * @param FlightDetail $flightDetail
     *
     * @return FlightDetail
     */
    function updateFlightDetail(FlightDetail $flightDetail) {

        $this->entityManager->persist($flightDetail);
        $this->entityManager->flush();

        return $flightDetail;
    }

    /**
     * Update Passenger Detail
     *
     * @param PassengerDetail $passengerDetail
     *
     * @return PassengerDetail
     */
    function updatePassengerDetail(PassengerDetail $passengerDetail) {

        $this->entityManager->persist($passengerDetail);
        $this->entityManager->flush();

        return $passengerDetail;
    }

    /**
     * Return Payment Entity
     *
     * @param string $uuid
     * @return Payment Object
     */
    public function getPaymentByUuid($uuid){
        return $this->entityManager->getRepository('PaymentBundle:Payment')->findOneByUuid($uuid);
    }

    /**
     * Update Payment
     *
     * @param object payment
     * @return Payment Object
     */
    public function updatePayment($payment)
    {
        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return $payment;
    }

    /** Get information of airline by given the airline code
     *
     * @param string $code of the airline
     * @return Airline Object
     */
    public function findAirline($code)
    {
        $airline = $this->entityManager->getRepository('TTBundle:Airline')->findOneByCode($code);

        return $airline;
    }

    /**
     * Get information of Airport by given the airport code
     *
     * @param string $code of the Airpot
     * @return Airport Object
     */
    public function findAirport($code)
    {
        $airport = $this->entityManager->getRepository('TTBundle:Airport')->findOneByAirportCode($code);

        return $airport;
    }

    /**
     * Get information of Cabin by given the cabin code
     *
     * @param string $code of the Cabin
     * @return Cabin Object
     */
    public function findCabin($code)
    {
        $cabin = $this->entityManager->getRepository('FlightBundle:FlightCabin')->findOneByCode($code);

        return $cabin;
    }
}
