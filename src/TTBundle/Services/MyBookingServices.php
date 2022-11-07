<?php

namespace TTBundle\Services;

use Doctrine\ORM\EntityManager;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyBookingServices
{
    private $em;
    protected $utils;
    protected $container;

    public function __construct(EntityManager $em, Utils $utils, ContainerInterface $container)
    {
        $this->em        = $em;
        $this->utils     = $utils;
        $this->container = $container;
    }

    public function myFlightBookings($records,$bookingStatus='')
    {
        $myFlightBookings = array();
        $noLogo           = "no-logo.jpg";
        $flightStatus='';
        switch ($bookingStatus) {
            case 1:
                $flightStatus='past';
                break;
            case 2:
                $flightStatus='cancelled';
                break;
            case 3:
                $flightStatus='upcoming';
                break;
            default:
                $flightStatus='all';
                break;
        }
        foreach ($records as $index => $rec) {
            $myFlightBookings[$index]['price']           = number_format($rec->getPassengerNameRecord()->getFlightInfo()->getDisplayPrice(), 2, '.', ',');
            $myFlightBookings[$index]['multidestination'] =$rec->getPassengerNameRecord()->getFlightInfo()->isMultiDestination();
            $myFlightBookings[$index]['oneway'] =$rec->getPassengerNameRecord()->getFlightInfo()->isOneWay();
            $myFlightBookings[$index]['currency']        = $rec->getPassengerNameRecord()->getFlightInfo()->getDisplayCurrency();
            $symbol                                      = $this->em->getRepository('TTBundle:CurrencyRate')->findOneBycurrencyCode($myFlightBookings[$index]['currency']);
            $myFlightBookings[$index]['currencySymbol'] = $symbol ? $symbol->getSymbol() : $myFlightBookings[$index]['currency'];
            $myFlightBookings[$index]['transactionId']  = $rec->getUuid();
            $myFlightBookings[$index]['status']          = $rec->getPassengerNameRecord()->getStatus();
            $myFlightBookings[$index]['moduleId'] = 1;
            $myFlightBookings[$index]['statusName'] =$flightStatus;
            $flightDetail                                = $rec->getPassengerNameRecord()->getFlightDetails();
            $count                                       = 0;
            $stopsNumber                                 = 0;
            $firstSegment=$flightDetail[0];
            $lastSegment=$flightDetail[count($flightDetail) - 1];

            $myFlightBookings[$index]['fromdate'] =$firstSegment->getDepartureDateTime();
            $myFlightBookings[$index]['todate'] =$lastSegment->getArrivalDateTime();


            foreach ($flightDetail as $segment) {

                $departureAirport = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($segment->getDepartureAirport());
                $arrivalAirport   = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($segment->getArrivalAirport());
                $stopIndicator    = $segment->getStopIndicator();

                if (!$stopIndicator) {
                    $stopsNumber                              = 0;
                    $airline                                  = $this->em->getRepository('TTBundle:Airline')->findOneByCode($segment->getAirline());
                    $airlineLogo                              = ($airline) ? $airline->getLogo() : $noLogo;
                    $myFlightBookings[$index]['airlineLogo'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/airline-logos/'.$airlineLogo);
                    $myFlightBookings[$index]['airlineLogoMobile'] = '/media/images/airline-logos/mobile/'.$airlineLogo;
                    $myFlightBookings[$index]['airlineName'] = ($airline) ? $airline->getAlternativeBusinessName() : $segment->getAirline();

                    $operatingAirline                                                        = $this->em->getRepository('TTBundle:Airline')->findOneByCode($segment->getOperatingAirline());
                    $operatingAirlineLogo                                                    = ($airline) ? $airline->getLogo() : $noLogo;

                    $myFlightBookings[$index]['operatingAirlineLogo']                      = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/airline-logos/'.$operatingAirlineLogo);
                    $myFlightBookings[$index]['operatingAirlineLogoMobile']                      = '/media/images/airline-logos/mobile/'.$operatingAirlineLogo;
                    $myFlightBookings[$index]['operatingAirlineName']                      = ($operatingAirline) ? $operatingAirline->getAlternativeBusinessName() : $segment->getOperatingAirline();
                    $myFlightBookings[$index]["flightDetails"][$count]['flighType']       = $segment->getType();
                    $myFlightBookings[$index]["flightDetails"][$count]['departureDate']    = $segment->getDepartureDateTime();

                    $myFlightBookings[$index]["flightDetails"][$count]['arrivalDate']      = $segment->getArrivalDateTime();
                    $myFlightBookings[$index]["flightDetails"][$count]['departureAirport'] = ($departureAirport) ? $departureAirport->getName() : $segment->getDepartureAirport();
                    $myFlightBookings[$index]["flightDetails"][$count]['arrivalAirport']   = ($arrivalAirport) ? $arrivalAirport->getName() : $segment->getArrivalAirport();
                    $myFlightBookings[$index]["flightDetails"][$count]['departureAirportCode'] = $segment->getDepartureAirport();

                    $myFlightBookings[$index]["flightDetails"][$count]['airline'] = $segment->getAirline();
                    $myFlightBookings[$index]["flightDetails"][$count]['flightNumber'] = $segment->getFlightNumber();
                    $myFlightBookings[$index]["flightDetails"][$count]['flightDuration']   = $segment->getFlightDuration();
                    $myFlightBookings[$index]["flightDetails"][$count]['stopNumber']       = $stopsNumber;
                    $myFlightBookings[$index]["flightDetails"][$count]['arrivalAirportCode'] = $segment->getArrivalAirport();
                    $myFlightBookings[$index]["flightDetails"][$count]['stopNumber']     = $stopsNumber;




                    $count++;
                } else {
                    $stopsNumber++;
                    $myFlightBookings[$index]["flightDetails"][$count - 1]['arrivalAirportCode'] = $segment->getArrivalAirport();
                    $myFlightBookings[$index]["flightDetails"][$count - 1]['stopNumber']     = $stopsNumber;
                    $myFlightBookings[$index]["flightDetails"][$count - 1]['arrivalDate']    = $segment->getArrivalDateTime();

                    $myFlightBookings[$index]["flightDetails"][$count - 1]['arrivalAirport'] = ($arrivalAirport) ? $arrivalAirport->getName() : $segment->getArrivalAirport();

                }

            }
        }
        return $myFlightBookings;
    }

    public function flightDetails($flightDetail)
    {

        $flightSegments = array();

        foreach ($flightDetail as $flight) {

            $flightInfo       = array();
            $departureAirport = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($flight->getDepartureAirport());
            $arrivalAirport   = $this->em->getRepository('TTBundle:Airport')->findOneByAirportCode($flight->getArrivalAirport());

            $flightSegments[$flight->getType()]['destination_city'] = ($arrivalAirport) ? $arrivalAirport->getCity() : $flight->getArrivalAirport();

            if (!$flight->getStopIndicator()) {
                if ($flight->getType() == 'returning' || !isset($flightSegments[$flight->getType()]['origin_city']))
                        $flightSegments[$flight->getType()]['origin_city']             = ($departureAirport) ? $departureAirport->getCity() : $flight->getDepartureAirport();
                if ($flight->getType() == 'returning' || !isset($flightSegments[$flight->getType()]['departure_main_date']))
                        $flightSegments[$flight->getType()]['departure_main_date']     = ($arrivalAirport) ? $flight->getDepartureDateTime()->format('D d/m') : $flight->getArrivalAirport();
                if ($flight->getType() == 'returning' || !isset($flightSegments[$flight->getType()]['raw_departure_main_date']))
                        $flightSegments[$flight->getType()]['raw_departure_main_date'] = ($arrivalAirport) ? $flight->getDepartureDateTime()->format('Y-m-d H:i:s') : $flight->getArrivalAirport();
            }

            $mainAirline                                        = $this->em->getRepository('TTBundle:Airline')->findOneByCode($flight->getAirline());
            $flightSegments[$flight->getType()]['main_airline'] = ($mainAirline) ? $mainAirline->getAlternativeBusinessName() : $flight->getAirline();

            $airlineLogo                                        = ($mainAirline) ? $mainAirline->getLogo() : $noLogo;
            $flightSegments[$flight->getType()]['airline_logo'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/airline-logos/'.$airlineLogo);

            $flightInfo['departure_date']      = $flight->getDepartureDateTime()->format('D d/m');
            $flightInfo['raw_departure_date']  = $flight->getDepartureDateTime()->format('Y-m-d H:i:s');
            $flightInfo['departure_time']      = $flight->getDepartureDateTime()->format('H\:i');
            $flightInfo['origin_city']         = ($departureAirport) ? $departureAirport->getCity() : $flight->getDepartureAirport();
            $flightInfo['origin_airport']      = ($departureAirport) ? $departureAirport->getName() : $flight->getDepartureAirport();
            $flightInfo['origin_airport_code'] = $flight->getDepartureAirport();

            $flightInfo['arrival_date']             = $flight->getArrivalDateTime()->format('D d/m');
            $flightInfo['raw_arrival_date']         = $flight->getArrivalDateTime()->format('Y-m-d H:i:s');
            $flightInfo['arrival_time']             = $flight->getArrivalDateTime()->format('H\:i');
            $flightInfo['destination_airport_code'] = $flight->getArrivalAirport();
            $flightInfo['destination_airport']      = ($arrivalAirport) ? $arrivalAirport->getName() : $flight->getArrivalAirport();
            $flightInfo['destination_city']         = ($arrivalAirport) ? $arrivalAirport->getCity() : $flight->getArrivalAirport();

            $airlineName                          = $this->em->getRepository('TTBundle:Airline')->findOneByCode($flight->getAirline());
            $flightInfo['airline_name']           = ($airlineName) ? $airlineName->getAlternativeBusinessName() : $flight->getAirline();
            $flightInfo['flight_number']          = $flight->getFlightNumber();
            $flightInfo['airline_code']           = $flight->getAirline();
            $flightInfo['operating_airline_code'] = $flight->getOperatingAirline();

            $cabinName                           = $this->em->getRepository('FlightBundle:FlightCabin')->findOneByCode($flight->getCabin());
            $flightInfo['cabin']                 = ($cabinName) ? $cabinName->getName() : $flight->getCabin();
            $flightInfo['flight_duration']       = $flight->getFlightDuration();
            $flightInfo['stop_indicator']        = $flight->getStopIndicator();
            $flightInfo['stop_duration']         = $flight->isStopDuration();
            $flightInfo['departure_terminal_id'] = $flight->getDepartureTerminalId();
            $flightInfo['arrival_terminal_id']   = $flight->getArrivalTerminalId();
            $flightInfo['elapsedTime']           = $flight->getElapsedTime();
            $flightInfo['airline_pnr']           = $flight->getAirlinePnr();

            if (empty($flightInfo['departure_terminal_id'])) {
                $flightInfo['departure_terminal'] = '';
            } else {
                $flightInfo['departure_terminal'] = 'Terminal '.$flightInfo['departure_terminal_id'];
            }

            if (empty($flightInfo['arrival_terminal_id'])) {
                $flightInfo['arrival_terminal'] = '';
            } else {
                $flightInfo['arrival_terminal'] = 'Terminal '.$flightInfo['arrival_terminal_id'];
            }

            if ($flightInfo["stop_indicator"]) {
                $stop_index                                                                        = count($flightSegments[$flight->getType()]["flight_info"]);
                $flightSegments[$flight->getType()]["flight_info"][$stop_index - 1]['stop_info'][] = $flightInfo;
            } else {
                $flightSegments[$flight->getType()]["flight_info"][] = $flightInfo;
            }
        }

        return $flightSegments;
    }

    public function myBookingsSearchFlight($userArr, $bookingType, $limit = 3, $offset = 0, $fromDate = 0, $toDate = 0)
    {
        $data = array();

        if (empty($bookingType) || $bookingType == 0) {
            $flight = $this->getFlightsList($userArr, 0, $limit, $offset, $fromDate, $toDate);
            $data   = $this->myFlightBookings($flight);
        } else {
            $flight = $this->getFlightsList($userArr, $bookingType, $limit, $offset, $fromDate, $toDate);
            $data   = $this->myFlightBookings($flight);
        }

        return $data;
    }

    public function myBookingsSearchInvoice($userArr, $bookingType, $limit = 10, $offset = 0)
    {
        $data = array();

        if (empty($bookingType) || $bookingType == 0) {
            $flight = $this->getFlightsListInvoice($userArr, 0, $limit, $offset);
            $data   = $this->myFlightBookings($flight);
        } else {
            $flight = $this->getFlightsListInvoice($userArr, $bookingType, $limit, $offset);
            $data   = $this->myFlightBookings($flight);
        }

        return $data;
    }

    public function getFLights($where, $limit, $offset)
    {
        return $this->em->getRepository('PaymentBundle:Payment')->findBy($where, array('creationDate' => 'DESC'), $limit, $offset);
    }

    public function getFlightsCount($userArr,$fromDate = 0, $toDate = 0)
    {
        $userId = $userArr['id'];
        $userEm = $userArr['email'];
        $query  = $this->em->createQueryBuilder()
            ->select('COUNT(DISTINCT p.uuid)')
            ->from('PaymentBundle:Payment', 'p')
            ->innerJoin('p.passengerNameRecord', 'pnr')
            ->innerJoin('pnr.flightDetails', 'fd')
            ->where('p.userId = :id OR pnr.email = :email')
            ->andWhere('(p.status = :paymentSuccess AND pnr.status = :pnrSuccess) OR (p.status = :paymentCancelled AND pnr.status = :pnrCancelled)')
            ->andWhere('p.responseMessage NOT IN (:responseMessage)')
            ->andWhere('fd.segmentNumber = :segmentNumber');
        if ($fromDate) {
            $query->andWhere('pnr.creationDate >= :fromDate');
        }
        if ($toDate) {
            $query->andWhere('pnr.creationDate <= :toDate');
        }
        $query->setParameter('id', $userId)
            ->setParameter('email', $userEm)
            ->setParameter('paymentSuccess', '14')
            ->setParameter('paymentCancelled', '06')
            ->setParameter('pnrSuccess', 'SUCCESS')
            ->setParameter('pnrCancelled', 'CANCELLED')
            ->setParameter('segmentNumber', '1')
            ->setParameter('responseMessage', array('Auto refunded', 'AUTO_CANCEL'));
        if ($fromDate) {
            $query->setParameter('fromDate', $fromDate);
        }
        if ($toDate) {
            $query->setParameter('toDate', $toDate);
        }
        $getQuery=$query->getQuery();
        $getSingleScalarResult = $getQuery->getSingleScalarResult();

        return $getSingleScalarResult;
    }

    public function getFlightsList($userArr, $type = 0, $limit = 3, $offset = 0, $fromDate = 0, $toDate = 0)
    {
        $userId = $userArr['id'];
        $userEm = $userArr['email'];
        $query  = $this->em->createQueryBuilder()
            ->select('p')
            ->from('PaymentBundle:Payment', 'p')
            ->innerJoin('p.passengerNameRecord', 'pnr')
            ->innerJoin('pnr.flightDetails', 'fd')
            ->where('p.userId = :id OR pnr.email = :email')
            ->andWhere('p.responseMessage NOT IN (:responseMessage)')
            ->andWhere('pnr.isCorporateSite = 0');
        //            ->andWhere('fd.segmentNumber = :segmentNumber');
        switch ($type) {
            case 1:
                $query->andWhere('p.status = :paymentSuccess AND pnr.status = :pnrSuccess')
                    ->andWhere('fd.departureDateTime <= :date');
                break;
            case 2:
                $query->andWhere('p.status = :paymentCancelled AND pnr.status = :pnrCancelled');
                break;
            case 3:
                $query->andWhere('p.status = :paymentSuccess AND pnr.status = :pnrSuccess')
                    ->andWhere('fd.departureDateTime > :date');
                break;
            default:
                $query->andWhere('(p.status = :paymentSuccess AND pnr.status = :pnrSuccess) OR (p.status = :paymentCancelled AND pnr.status = :pnrCancelled)');
                break;
        }
        if ($fromDate) {
            $query->andWhere('pnr.creationDate >= :fromDate');
        }
        if ($toDate) {
            $query->andWhere('pnr.creationDate <= :toDate');
        }
        $query->groupBy('p.uuid')
            ->orderBy('pnr.creationDate', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter('id', $userId)
            ->setParameter('email', $userEm);
        if ($fromDate) {
            $query->setParameter('fromDate', $fromDate);
        }
        if ($toDate) {
            $query->setParameter('toDate', $toDate);
        }

        $query->setParameter('responseMessage', array('Auto refunded', 'AUTO_CANCEL'));
        //            ->setParameter('segmentNumber', '1');
        switch ($type) {
            case 1:
            case 3:
                $query->setParameter('date', new \DateTime())
                    ->setParameter('pnrSuccess', 'SUCCESS')
                    ->setParameter('paymentSuccess', '14');
                break;
            case 2:
                $query->setParameter('pnrCancelled', 'CANCELLED')
                    ->setParameter('paymentCancelled', '06');
                break;
            default:
                $query->setParameter('pnrSuccess', 'SUCCESS')
                    ->setParameter('pnrCancelled', 'CANCELLED')
                    ->setParameter('paymentSuccess', '14')
                    ->setParameter('paymentCancelled', '06');
                break;
        }
        $getQuery = $query->getQuery();

        $getResult = $getQuery->getResult();

        return $getResult;
    }

    public function getFlightsListInvoice($userArr, $type = 0, $limit = 10, $offset = 0)
    {

        $userId = $userArr['id'];
        $userEm = $userArr['email'];

        $query = $this->em->createQueryBuilder()
            ->select('p')
            ->from('PaymentBundle:Payment', 'p')
            ->innerJoin('p.passengerNameRecord', 'pnr');
        if ($type != 2) {
            $query->innerJoin('pnr.flightDetails', 'fd');
        }

        $query->where('p.userId = :id');
        $query->orWhere('pnr.email = :email');
        $query->andWhere('p.status IN (:paymentStatus)');
        $query->andWhere('pnr.status IN (:status)');

        $inTo = explode(" / ", $userArr['invoiceDate_to']);
        $inFr = explode(" / ", $userArr['invoiceDate_from']);

        $from = new \DateTime(implode("-", array_reverse($inFr)));
        $to   = new \DateTime(implode("-", array_reverse($inTo)));
        $to->modify('+1 day');

        $query->andWhere('p.creationDate >= :from AND p.creationDate <= :to');

        switch ($type) {
            case 1:
                $query->andWhere('fd.departureDateTime < :date');
                $query->andWhere('fd.segmentNumber = :segmentNumber');
                break;
            case 2:
                $query->andWhere('p.responseMessage != :responseMessage');
                break;
            case 3:
                $query->andWhere('fd.departureDateTime > :date');
                $query->andWhere('fd.segmentNumber = :segmentNumber');
                break;
            default:
                break;
        }
        $query->groupBy('p.uuid')
            ->orderBy('pnr.creationDate', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter('id', $userId)
            ->setParameter('email', $userEm)
            ->setParameter('from', $from->format('Y-m-d'))
            ->setParameter('to', $to->format('Y-m-d'));
        switch ($type) {
            case 1:
            case 3:
                $query->setParameter('date', new \DateTime());
                $query->setParameter('status', array('SUCCESS'));
                $query->setParameter('segmentNumber', '1');
                $query->setParameter('paymentStatus', array('14'));
                break;
            case 2:
                $query->setParameter('status', array('CANCELLED'));
                $query->setParameter('paymentStatus', array('06'));
                $query->setParameter('responseMessage', 'Auto refunded');
                break;
            default:
                $query->setParameter('status', array('SUCCESS'));
                $query->setParameter('paymentStatus', array('14', '06'));
                break;
        }
        $getQuery  = $query->getQuery();
        $getResult = $getQuery->getResult();
        return $getResult;
    }

    public function getFlightsListInvoiceCount($userArr, $type = 0, $limit = 1, $offset = 0)
    {

        $userId = $userArr['id'];
        $userEm = $userArr['email'];

        $query = $this->em->createQueryBuilder()
            ->select('count(DISTINCT(p.uuid))')
            ->from('PaymentBundle:Payment', 'p')
            ->innerJoin('p.passengerNameRecord', 'pnr');
        if ($type != 2) {
            $query->innerJoin('pnr.flightDetails', 'fd');
        }

        $query->where('p.userId = :id');
        $query->orWhere('pnr.email = :email');
        $query->andWhere('p.status IN (:paymentStatus)');
        $query->andWhere('pnr.status IN (:status)');

        $inTo = explode(" / ", $userArr['invoiceDate_to']);
        $inFr = explode(" / ", $userArr['invoiceDate_from']);

        $from = new \DateTime(implode("-", array_reverse($inFr)));
        $to   = new \DateTime(implode("-", array_reverse($inTo)));
        $to->modify('+1 day');

        $query->andWhere('p.creationDate >= :from AND p.creationDate <= :to');

        switch ($type) {
            case 1:
                $query->andWhere('fd.departureDateTime < :date');
                $query->andWhere('fd.segmentNumber = :segmentNumber');
                break;
            case 2:
                $query->andWhere('p.responseMessage != :responseMessage');
                break;
            case 3:
                $query->andWhere('fd.departureDateTime > :date');
                $query->andWhere('fd.segmentNumber = :segmentNumber');
                break;
            default:
                break;
        }
        $query->groupBy('p.uuid')
            ->orderBy('pnr.creationDate', 'DESC')
            ->setMaxResults(1)
            ->setFirstResult($offset)
            ->setParameter('id', $userId)
            ->setParameter('email', $userEm)
            ->setParameter('from', $from->format('Y-m-d'))
            ->setParameter('to', $to->format('Y-m-d'));
        switch ($type) {
            case 1:
            case 3:
                $query->setParameter('date', new \DateTime());
                $query->setParameter('status', array('SUCCESS'));
                $query->setParameter('segmentNumber', '1');
                $query->setParameter('paymentStatus', array('14'));
                break;
            case 2:
                $query->setParameter('status', array('CANCELLED'));
                $query->setParameter('paymentStatus', array('06'));
                $query->setParameter('responseMessage', 'Auto refunded');
                break;
            default:
                $query->setParameter('status', array('SUCCESS'));
                $query->setParameter('paymentStatus', array('14', '06'));
                break;
        }
        $getQuery  = $query->getQuery();
        $getResult = $getQuery->getResult();
        return $getResult;
    }

    public function getCancelledFLightsCount($userArr, $fromDate = 0, $toDate = 0)
    {
        $userId = $userArr['id'];
        $userEm = $userArr['email'];
        $query  = $this->em->createQueryBuilder()
            ->select('count(p.uuid)')
            ->from('PaymentBundle:Payment', 'p')
            ->innerJoin('p.passengerNameRecord', 'pnr')
            ->where('p.userId = :id OR pnr.email = :email')
            ->andWhere('p.status = :paymentStatus')
            ->andWhere('pnr.status = :status')
            ->andWhere('p.responseMessage NOT IN (:responseMessage)');
        if ($fromDate) {
            $query->andWhere('pnr.creationDate >= :fromDate');
        }
        if ($toDate) {
            $query->andWhere('pnr.creationDate <= :toDate');
        }
        if ($fromDate) {
            $query->setParameter('fromDate', $fromDate);
        }
        if ($toDate) {
            $query->setParameter('toDate', $toDate);
        }
        $query->setParameter('id', $userId)
            ->setParameter('paymentStatus', '06')
            ->setParameter('status', 'CANCELLED')
            ->setParameter('email', $userEm)
            ->setParameter('responseMessage', array('Auto refunded', 'AUTO_CANCEL'));
        $getQuery = $query->getQuery();


        $getResult = $getQuery->getSingleScalarResult();
        return $getResult;
    }

    public function getPastUpcomingFLightsCount($userArr, $type, $fromDate = 0, $toDate = 0)
    {
        $userId = $userArr['id'];
        $userEm = $userArr['email'];
        $query  = $this->em->createQueryBuilder()
            ->select('count(Distinct p.uuid)')
            ->from('PaymentBundle:Payment', 'p')
            ->innerJoin('p.passengerNameRecord', 'pnr')
            ->innerJoin('pnr.flightDetails', 'fd')
            ->where('p.userId = :id OR pnr.email = :email')
            ->andWhere('p.status = :paymentStatus')
            ->andWhere('pnr.status = :status')
            ->andWhere('p.responseMessage NOT IN (:responseMessage)')
            ->andWhere('fd.segmentNumber = :segmentNumber');
        if ($fromDate) {
            $query->andWhere('pnr.creationDate >= :fromDate');
        }
        if ($toDate) {
            $query->andWhere('pnr.creationDate <= :toDate');
        }
        if ($type == 1) {
            $query->andWhere('fd.departureDateTime < :date');
        } else {
            $query->andWhere('fd.departureDateTime > :date');
        }
        $query->setParameter('id', $userId)
            ->setParameter('email', $userEm)
            ->setParameter('paymentStatus', '14')
            ->setParameter('status', 'SUCCESS')
            ->setParameter('segmentNumber', '1')
            ->setParameter('responseMessage', array('Auto refunded', 'AUTO_CANCEL'))
            ->setParameter('date', new \DateTime());
        if ($fromDate) {
            $query->setParameter('fromDate', $fromDate);
        }
        if ($toDate) {
            $query->setParameter('toDate', $toDate);
        }
        $getQuery = $query->getQuery();


        $getResult = $getQuery->getSingleScalarResult();

        return $getResult;
    }

    /**
     * This method will retrieve all/upcomming/cancelled/pas DEAL booking.
     * @param $criteria Query ctriteria
     * @return doctrine object result of corresponding deals or false in case of no data
     *
     * */
    public function getMyDealBookings($criteria = array())
    {

        $return = array();
        $query  = $this->em->createQueryBuilder()
            ->from('DealBundle:DealBooking', 'db')
            ->innerJoin('DealBundle:DealCity', 'dc', 'WITH', "db.dealCityId=dc.id")
            ->innerJoin('DealBundle:DealType', 'dt', 'WITH', "db.dealTypeId=dt.id")
            ->where('db.userId = :userId')
            ->setParameter(':userId', $criteria['id'])
            ->orderBy('db.bookingDate', 'DESC');

        $nowDate = new \DateTime();

        switch ($criteria['type']) {
            case 'past':
                $query->Andwhere('db.bookingDate < :nowDate');
                $query->setParameter(':nowDate', $nowDate);
                break;
            case 'future':
                $query->Andwhere('db.bookingDate > :nowDate');
                $query->setParameter(':nowDate', $nowDate);
                break;
            case 'cancelled':
                $query->Andwhere('db.bookingStatus = :bookingStatus');
                $query->setParameter(':bookingStatus', 'CANCELLED');
                break;
            default:
                break;
        }

        //get all details
        if ($criteria['type'] == 'details' || (isset($criteria['showDetails']) && $criteria['showDetails'] == 1)) {
            $query->select('db.id as bookingId, '
                .'db.bookingDate as bookingDate, '
                .'db.dealDetailsId as dealDetailsId, '
                .'db.dealName as dealName, '
                .'db.bookingReference as bookingReference, '
                .'db.totalPrice as totalPrice, '
                .'db.bookingStatus as bookingStatus, '
                .'dc.cityName as cityName, '
                .'dt.category as dealType, '
                .'di.path as imgPath');
            $query->leftJoin('DealBundle:DealImage', 'di', 'WITH', "db.dealDetailsId=di.dealDetailId AND di.isDefault=:isDefault");
            $query->setParameter(':isDefault', 1);
            $getQuery  = $query->getQuery();
            $getResult = $getQuery->getScalarResult();

            if (count($getResult) && !empty($getResult)) {
                foreach ($getResult as $details) {
                    $item                          = array();
                    $dateValue                     = strtotime($details['bookingDate']);
                    $item['bookingDate']           = date("d", $dateValue);
                    $item['bookingMonth']          = date("M Y", $dateValue);
                    $item['bookingDay']            = ucfirst(date("l", $dateValue));
                    $item['bookingId']             = $details['bookingId'];
                    $item['detailsId']             = $details['dealDetailsId'];
                    $item['dealName']              = $details['dealName'];
                    $item['bookingReference']      = $details['bookingReference'];
                    $item['totalPrice']            = $details['totalPrice'];
                    $item['bookingStatus']         = $details['bookingStatus'];
                    $item['imagePath']             = $details['imgPath'];
                    $item['urlPath']               = $this->container->get('TTRouteUtils')->returnDealDetailsLink($details['dealDetailsId'], $details['dealName'], $details['cityName'], $details['dealType']);
                    $return[$details['bookingId']] = $item;
                }
            }
        } else {
            $query->select('COUNT(DISTINCT db.id)');
            $getQuery = $query->getQuery();
            $return   = $getQuery->getSingleScalarResult();
        }
        return $return;
    }

    /**
     * Getting all bookings per user
     *
     * @return array $results
     */
    public function getAllMyBookings($params)
    {
        $hotelsList=array();
        $flightsList=array();
        $flightsCount=0;
        if (isset($params['count']) && $params['count'] != '') {
            $hotelsList[0]['count(1)']=0;
        }
        if (!isset($params['types'])) {
         $hotelsList=$this->getBookedHotels($params);
         $flightsList=$this->getBookedFlights($params);
            $flightsCount=$this->getBookedFlightsCount($params);
        } elseif (isset($params['types']) && $params['types'] && count($params['types']) < 3) {
            foreach ($params['types'] as $types) {
                if ($types == $this->container->getParameter('MODULE_HOTELS')) {
                    $hotelsList=$this->getBookedHotels($params);

                }
                if ($types == $this->container->getParameter('MODULE_FLIGHTS')) {
                    $flightsList=$this->getBookedFlights($params);
                    $flightsCount=$this->getBookedFlightsCount($params);
                }
            }
        }
        $result=array_merge($hotelsList,$flightsList);

        if (!empty($result) && isset($result)) {
            if (isset($params['count']) && $params['count'] != '') {

                return  $hotelsList[0]['count(1)'] + $flightsCount;

            } else {
                return $result;
            }
        } else {
            return array();
        }
    }
    public function getBookedHotels($params)
    {
        $sql               = '';
        $dateFromHotelSql  = '';
        $dateFromDealSql   = '';
        $dateToHotelSql    = '';
        $hotelSql          = '';
        $orderOfDisplay    = 'DESC';
        $grpByHotel        = '';
        $hotelSql .= $this->getHotelBookingSQL($params);


        if (isset($params['count']) && $params['count'] != '') {
            $sql .= 'SELECT count(1) FROM( ';
        }

        $todayDate = date('Y-m-d');
        if (isset($params['past'])) {
            $dateFromHotelSql .= " AND date(hr.from_date) < date('" . $todayDate . "')";
        } elseif (isset($params['future'])) {
            $dateFromHotelSql .= " AND date(hr.from_date) >= date('" . $todayDate . "')";
        }

        if (isset($params['fromDate']) && $params['fromDate'] != '') {
            $dateFromHotelSql .= " AND date(hr.from_date) >= date('" . $params['fromDate'] . "')";
        }

        if (isset($params['toDate']) && $params['toDate'] != '') {
            $dateToHotelSql .= " AND date(hr.to_date) <= date('" . $params['toDate'] . "')";
        }

        $grpByHotel .= ' GROUP BY hr.id';
            $sql .= $hotelSql . $dateFromHotelSql . $dateToHotelSql . $grpByHotel;




            if (isset($params['count']) && $params['count'] != '') {
                $sql .= ') as countApp ORDER BY transactionDate ' . $orderOfDisplay;
            } else {
                if (!isset($params['start']) && !isset($params['limit'])) {
                    $sql .= ' ORDER BY transactionDate ' . $orderOfDisplay;
                } else {
                    $sql .= ' ORDER BY transactionDate ' . $orderOfDisplay . '
                          LIMIT ' . $params['start'] . ',' . $params['limit'];
                }
            }
            $conn = $this->em->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();

        return $res;
    }

    public function getBookedFlights($params)
    {
        $userArr['id']=$params['userId'];
        $userArr['email']=$params['userEmail'];
        $flightsCount=0;
        $flightBookings=array();
        if(isset($params['fromDate'])){ $fromDate=$params['fromDate']; }else{$fromDate='';}
        if(isset($params['toDate'])){ $toDate=$params['toDate']; }else{$toDate='';}
        if(isset($params['status'])){ $bookingStatus=$params['status']; }else{$bookingStatus='';}
        if($params['start']){

        }
            $flightBookings= $this->myBookingsSearchFlight($userArr, $bookingStatus, $params['limit'], $params['start'] , $fromDate, $toDate);
           return $flightBookings;
    }
    public function getBookedFlightsCount($params){
        $flightsCount=0;
        $userArr['id']=$params['userId'];
        $userArr['email']=$params['userEmail'];
        if(isset($params['fromDate'])){ $fromDate=$params['fromDate']; }else{$fromDate='';}
        if(isset($params['toDate'])){ $toDate=$params['toDate']; }else{$toDate='';}
        if(isset($params['status'])){ $bookingStatus=$params['status']; }else{$bookingStatus='';}
        switch ($bookingStatus) {
            case 1:
                $flightsCount = $this->getPastUpcomingFLightsCount($userArr, $bookingStatus, $fromDate, $toDate);
                break;
            case 3:
                $flightsCount = $this->getPastUpcomingFLightsCount($userArr, $bookingStatus, $fromDate, $toDate);
                break;
            case 2:
                $flightsCount = $this->getCancelledFLightsCount($userArr, $fromDate, $toDate);
                break;
            default:
                $flightsCount = $this->getFlightsCount($userArr, $fromDate, $toDate);
                break;
        }
        return $flightsCount;
    }
    /**
     * Preparing the hotel booking SQL
     *
     * @return sql
     */
    public function getHotelBookingSQL($params)
    {
        $transactionSourceId = $this->em->getRepository('TTBundle:TransactionSource')->findOneByCode('web')->getId();

        if (isset($params['canceled']) && !is_null($params['canceled'])) {
            $reservationStatus = array($this->container->getParameter('hotels')['reservation_canceled']);
        } elseif (isset($params['past']) || isset($params['future'])) {
            $reservationStatus = array(
                $this->container->getParameter('hotels')['reservation_confirmed'],
                $this->container->getParameter('hotels')['reservation_modified']
            );
        } else {
            $reservationStatus = array(
                $this->container->getParameter('hotels')['reservation_confirmed'],
                $this->container->getParameter('hotels')['reservation_modified'],
                $this->container->getParameter('hotels')['reservation_canceled']
            );
        }
        $reservationStatus = implode('","', $reservationStatus);

        $sql = 'SELECT
                    '.$this->container->getParameter('MODULE_HOTELS').' AS moduleId,
                    md.name AS "moduleName",
                    cu.FullName AS "userName",
                    cu.id AS "userId",
                    hr.hotel_id AS "hotelId",
                    hr.creation_date AS "transactionDate",
                    hr.reservation_status AS statusName,
                    hr.hotel_currency AS currency,
                    hr.hotel_grand_total AS amount,
                    hr.id AS "reservationId",
                    hr.control_number AS "controlNumber",
                    hr.reference AS "reference",
                    hr.reservation_process_password as "reservationProcessPassword",
                    hr.reservation_process_key as "reservationProcessKey",
                    ah.name AS "name",
                    wc.name AS "city",
                    cc.name AS "country",
                    CONCAT_WS(", ", ah.street, ah.district) AS "address",
                    hr.from_date AS "fromdate",
                    hr.to_date AS "todate",
                    null AS "starttime",
                    null AS "endtime",
                    "" AS dupePoolId,
                    ahi.location AS location,
                    ahi.filename AS filename,
                    "" AS "detailId",
                    "" AS "transactionId",
                    "" AS "apiId",
                    "" AS "dealCode",
                    "" AS "categoryName",
                    "" AS "cityName",
                    "" AS "imageId",
                    "" as "flight_duration",
                    "" as "departure_datetime",
                    ""as "arrival_datetime",
                    JSON_OBJECT("reference", hr.reference) AS "details",
                    ah.stars as "stars"
                 FROM
                       hotel_reservation hr
                    INNER JOIN
                       cms_hotel ah
                       ON hr.hotel_id = ah.id AND hr.reservation_process_key IS NOT NULL
                    INNER JOIN
                        cms_hotel_source hs
                        ON (ah.id = hs.hotel_id)
                    INNER JOIN
                        cms_hotel_city hc
                        ON (hs.location_id = hc.location_id)
                    INNER JOIN
                        webgeocities wc
                        ON (hc.city_id = wc.id AND wc.country_code IS NOT NULL)
                    INNER JOIN cms_countries cc ON (cc.code = wc.country_code)
                    LEFT JOIN
                       cms_hotel_image ahi
                       ON ahi.hotel_id = hr.hotel_id
                       AND ahi.tt_media_type_id = 1
                       AND ahi.default_pic = 1
                       AND ahi.id =
                       (
                          SELECT
                             MIN(id)
                          FROM
                             cms_hotel_image sahi
                          WHERE
                             sahi.hotel_id = hr.hotel_id
                             AND sahi.tt_media_type_id = 1
                             AND sahi.default_pic = 1
                       )
                    LEFT JOIN
                       cms_users cu
                       ON cu.id = hr.user_id
                    LEFT JOIN
                       tt_modules md
                       ON md.id = '.$this->container->getParameter('MODULE_HOTELS').
            ' WHERE hr.user_id = '.$params['userId'].' AND hr.transaction_source_id = '.$transactionSourceId
            .' AND hr.reservation_status IN ("'.$reservationStatus.'") AND md.id = '.$this->container->getParameter('MODULE_HOTELS').'';

        return $sql;
    }

    /**
     * Preparing the flight booking SQL
     *
     * @return sql
     */
    public function getFlightBookingSQL($params)
    {
        $sql = 'SELECT
                '.$this->container->getParameter('MODULE_FLIGHTS').' AS moduleId,
                md.name AS "moduleName",
                cu.FullName AS "userName",
                cu.id AS "userId",
                "" AS "hotelId",
                q.creation_date AS "transactionDate",
                q.status AS "statusName",
                pmt.currency AS "currency",
                pmt.amount AS "amount",
                q.pnr_id AS "reservationId",
                "" AS "controlNumber",
                q.pnr AS "reference",
                "" AS "reservationProcessPassword",
                "" as "reservationProcessKey",
                al.name AS "name",
                "" AS "city",
                q.country_of_residence AS "country",
                "" AS "address",
                q.departure_datetime AS "fromdate",
                fd_last.arrival_datetime AS "todate",
                "" AS "starttime",
                "" AS "endtime",
                "" AS "dupePoolId",
                "" AS "location",
                "" AS "filename",
                "" AS "detailId",
                pmt.uuid AS "transactionId",
                "" AS "apiId",
                "" AS "dealCode",
                "" AS "categoryName",
                "" AS "cityName",
                "" AS "imageId",
                fd_last.flight_duration as "flight_duration",
                fd_last.departure_datetime as "departure_datetime",
                fd_last.arrival_datetime as "arrival_datetime",
                JSON_OBJECT("logo", al.logo, "departureAirport" , q.departure_airport, "arrivalAirport" ,
                (
                   IF((fi.one_way = 1
                   and fi.multi_destination = 0), fd_last.arrival_airport, fd_last.departure_airport)
                )
             , "airline", q.airline, "flightNumber", q.flight_number,"nPassengers", q.n_passengers, "nStops", q.n_stops, "refundable", if(fi.multi_destination = 1, "refundable", "non - refundable"), "flightType", if(fi.multi_destination = 1, "multiple destination", if(fi.one_way = 1, "one way", "round trip"))) AS "details",
             "" as "stars"
             FROM
                (
                   SELECT
                      pnr.id AS pnr_id,
                      pnr.user_id,
                      fd_first.airline,
                      fd_first.flight_number,
                      fd_first.departure_datetime,
                      fd_first.departure_airport,
                      MAX(fd_last.segment_number) AS last_sn,
                      COUNT(DISTINCT pd.id) AS n_passengers,
                      COUNT(DISTINCT fd.id) AS n_stops,
                      pnr.country_of_residence as country_of_residence,
                      pnr.status,
                      pnr.creation_date,
                      pnr.pnr,
                      pnr.payment_uuid
                   FROM
                      passenger_name_record pnr
                      INNER JOIN
                         flight_detail fd_first
                         ON (fd_first.pnr_id = pnr.id
                         AND fd_first.segment_number = 1)
                      INNER JOIN
                         flight_detail fd_last
                         ON (fd_last.pnr_id = pnr.id)
                      LEFT JOIN
                         flight_detail fd
                         ON (fd.pnr_id = pnr.id
                         AND fd.stop_indicator = 1)
                      INNER JOIN
                         passenger_detail pd
                         ON (pd.pnr_id = pnr.id)
                   GROUP BY
                      pnr.id
                )
                q
                INNER JOIN
                   flight_detail fd_last
                   ON (fd_last.pnr_id = q.pnr_id
                   AND fd_last.segment_number = q.last_sn)
                INNER JOIN
                   payment pmt
                   ON (pmt.uuid = q.payment_uuid)
                INNER JOIN
                   flight_info fi
                   ON (fi.pnr_id = q.pnr_id)
                INNER JOIN
                   airline AS al
                   ON q.airline = al.code
                LEFT JOIN
                   cms_users cu
                   ON cu.id = q.user_id
                LEFT JOIN
                   tt_modules md
                   ON md.id = '.$this->container->getParameter('MODULE_FLIGHTS').
            ' WHERE q.user_id = '.$params['userId'].' AND md.id = '.$this->container->getParameter('MODULE_FLIGHTS').'';
        return $sql;
    }

    /**
     * Preparing the deal booking SQL
     *
     * @return sql
     */
    public function getDealBookingSQL($params)
    {
        $sql = 'SELECT
                '.$this->container->getParameter('MODULE_DEALS').' AS moduleId,
                md.name AS "moduleName",
                cu.FullName AS "userName",
                cu.id AS "userId",
                "" AS "hotelId",
                db.created_at AS "transactionDate",
                db.booking_status AS "statusName",
                db.currency AS "currency",
                db.total_price AS "amount",
                db.id AS "reservationId",
                "" AS "controlNumber",
                db.booking_reference AS "reference",
                "" AS "reservationProcessPassword",
                "" as "reservationProcessKey",
                db.deal_name AS "name",
                dc.city_name AS "city",
                dct.country_name AS "country",
                db.address AS "address",
                db.booking_date AS "fromdate",
                db.booking_date AS "todate",
                db.start_time AS "starttime",
                db.end_time AS "endtime",
                "" AS "dupePoolId",
                "" AS "location",
                "" AS "filename",
                db.deal_details_id AS "detailId",
                "" AS "transactionId",
                dd.deal_api_id AS "apiId",
                dd.deal_code AS "dealCode",
                dcat.name AS "categoryName",
                dc.city_name AS "cityName",
                di.id AS "imageId",
                "" as "flight_duration",
                "" as "departure_datetime",
                ""as "arrival_datetime",
                "" AS "details",
                "" as "stars"
             FROM
                   deal_booking db
                INNER JOIN
                   deal_city dc
                   ON (db.deal_city_id = dc.id)
                INNER JOIN
                   deal_country dct
                   ON (db.country_id = dct.id)
                LEFT JOIN
                   deal_details dd
                   ON db.deal_details_id = dd.id
                LEFT JOIN
                   deal_detail_to_category ddtc
                   ON ddtc.deal_details_id = dd.id
                LEFT JOIN
                   deal_category dcat
                   ON dcat.api_category_id = ddtc.deal_category_id
                LEFT JOIN
                   deal_image di
                   ON di.deal_detail_id = dd.id
                LEFT JOIN
                   cms_users cu
                   ON cu.id = db.user_id
                LEFT JOIN
                   tt_modules md
                   ON md.id = '.$this->container->getParameter('MODULE_DEALS').'
             WHERE
                db.user_id = '.$params['userId'].' AND md.id = '.$this->container->getParameter('MODULE_DEALS').'';
        return $sql;
    }
}
