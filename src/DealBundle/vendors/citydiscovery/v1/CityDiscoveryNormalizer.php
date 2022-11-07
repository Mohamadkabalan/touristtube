<?php

namespace DealBundle\vendors\citydiscovery\v1;

class CityDiscoveryNormalizer
{

    public function __construct()
    {

    }
    /*
     * This method builds your desired response set for each request
     *
     * @param $category
     * @param $arr['mainXml'] = array('Contiainer_Tag' => array('optional_name_that_will_be_used' => 'Tag_name_to_extract_data_from'))
     * @param $arr['multipleXml'] = array('Contiainer_Tag' =>
     * 		array('@xmlAttribute(IF YOU WANT TO RETRIEVE AN ATTRIBUTE FROM THE Contiainer_Tag ITSELF)' => array('optional_name_that_will_be_used' => 'Tag_name_to_extract_data_from'))
     * 		array('optional_name_that_will_be_used' => 'Tag_name_to_extract_data_from'))
     * @param $arr['xmlAttr'] = array('Tag_name_to_extract_data_from' => array('optional_name_that_will_be_used' => 'atrribute_name_to_extract_data_from'))
     *
     * @return array of your desired result set based on what is available on the XML response
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getDesiredXmlArray($category = 'getDetails')
    {
        $arr = array();
        switch ($category) {
            case 'getDetails':
                $arr['mainXml']          = array('productCode' => 'ActivityID',
                    'dealCountry' => 'ActivityCountryName',
                    'dealCountryCode' => 'ActivityCountry',
                    'dealCity' => 'ActivityCity',
                    'dealRating' => 'ActivityReviewRating',
                    'dealName' => 'ActivityName',
                    'inclusion' => 'ActivityInclusions',
                    'termsAndCondition' => 'ActivityTermsConditions',
                    'changePolicy' => 'ActivityChangePolicy',
                    //'dealDescription' => 'ActivityDescription',
                    'dealDuration' => 'ActivityDuration');
                $arr['highlightsXml']    = array('activityHighlights' => 'ActivityHighlights',
                    'bulletHighlight' => 'BulletHighlight');
                $arr['schedulesXml']     = array('ActivitySchedules' =>
                    array('ScheduleGroup' =>
                        array('ActivitySchedule' => array('order' => 'Sort',
                                'title' => 'WhatWhere',
                                'time' => 'FromTime',
                                'description' => 'Description',
                            ))
                ));
                $arr['notesXml']         = array('ActivityNotes' => array('Note' =>
                        array('title' => 'Title',
                            'Items' => array('Item' => array('ApplyTo' => array('info' => 'Info'),
                                    'note' => 'Note',
                                    'pdfPath' => 'PdfPath',
                                ))
                        )
                ));
                $arr['startingPlaceXml'] = array('ActivityStartingPlaces' =>
                    array('ActivityStartingPlace' =>
                        array('address' => 'Address',
                            'lat' => 'Lat',
                            'lang' => 'Long'
                        )
                ));
                $arr['faqXml']           = array('ActivityFaq' => array('Faq' =>
                        array('question' => 'Question',
                            'answer' => 'Answer')
                ));
                $arr['directionsXml']    = array('ActivityLocationDirection' => array('Direction' =>
                        array('title' => 'Title',
                            'description' => 'Description',
                            'image' => 'Image')
                ));
                $arr['priceOptionXml']   = array('ActivityPrices' => array('ActivityPriceId' => array('@xmlAttribute' => array('activityPriceId' => 'ID'),
                            'optionLabel' => 'ActivityPriceOption',
                            'inclusions' => 'Inclusions',
                            'PackagePrices' => array('Price' => array('@xmlAttribute' => array('priceId' => 'ID'),
                                    'AvailableDays' => array('monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ),
                                    'optionDateBegins' => 'SeasonFrom',
                                    'optionDateEnd' => 'SeasonTo',
                                    'Units' => array('Unit' => array('@xmlAttribute' => array('unitId' => 'ID'),
                                            'label' => 'Label',
                                            'minimum' => 'Minimum',
                                            'maximum' => 'Maximum',
                                            'capacityCount' => 'CapacityCount',
                                            'requiredOtherUnits' => 'RequiredOtherUnits',
                                            'chargePrice' => 'ChargePrice',
                                            'netPrice' => 'NetPrice',
                                            'currency' => 'Currency',
                                        )),
                                )
                            ),
                            'Schedules' => array('ScheduleGroup' => array('Schedule' => array(
                                        'order' => 'Sort',
                                        'title' => 'WhatWhere',
                                        'time' => 'FromTime',
                                        'description' => 'Description',
                                    )))
                        )
                    )
                );

                $arr['cancellationPolicyXml'] = array('ActivityCancellation' =>
                    array('ActivityCancellationPolicy' => array('@xmlAttribute' => array('cancellationDay' => 'Day', 'cancellationDiscount' => 'Percentage')))
                );
                $arr['mainXmlAttr']           = array('ActivityStartingPlace' => array('latitude' => 'Lat', 'longitude' => 'Long'),
                    'OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'checkAvailiblity':
                $arr['mainXml']     = array('productCode' => 'ActivityID');
                $arr['mainXmlAttr'] = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'priceDetails':
                $arr['mainXml']        = array('dealName' => 'ActivityName');
                $arr['priceOptionXml'] = array('ActivityPrices' => array('ActivityPriceId' => array('@xmlAttribute' => array('activityPriceId' => 'ID'),
                            'optionLabel' => 'ActivityPriceOption',
                            'PackagePrices' => array('Price' => array('@xmlAttribute' => array('priceId' => 'ID'),
                                    'AvailableDays' => array('monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ),
                                    'optionDateBegins' => 'SeasonFrom',
                                    'optionDateEnd' => 'SeasonTo',
                                    'Units' => array('Unit' => array('@xmlAttribute' => array('unitId' => 'ID'),
                                            'label' => 'Label',
                                            'minimum' => 'Minimum',
                                            'maximum' => 'Maximum',
                                            'rangeInfo' => 'RangeInfo',
                                            'capacityCount' => 'CapacityCount',
                                            'requiredOtherUnits' => 'RequiredOtherUnits',
                                            'chargePrice' => 'ChargePrice',
                                            'netPrice' => 'NetPrice',
                                            'currency' => 'Currency',
                                        )),
                                )
                            ))
                    )
                );
                $arr['xmlAttr']        = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'activityReviews':
                $arr['reviewsXml'] = array('ReviewList' => array('Review' =>
                        array('@xmlAttribute' => array('reviewId' => 'ID'),
                            'comment' => 'comment',
                            'rating' => 'rate',
                            'owner' => 'owner',
                            'country' => 'country',
                            'date' => 'date'))
                );
                break;

            case 'activityBooking':
                $arr['dealBookingXml'] = array('bookingReference' => 'BookingReferenceCityDiscovery',
                    'bookingStatus' => 'BookingStatus',
                    'bookingEmail' => 'CustomerEmail',
                    'bookingDate' => 'ActivityDate',
                    'bookingPrice' => 'Total',
                    'bookingCurrency' => 'Currency',
                    'activityName' => 'ActivityName',
                    'packageName' => 'PackageName');
                $arr['mainXmlAttr']    = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'activityCancellation':
                $arr['bookingCancellationXml'] = array(
                    'cancelBookingReference' => 'BookingReferenceCityDiscovery',
                    'cancelBookingStatus' => 'CancellationStatus');
                $arr['mainXmlAttr']            = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'transferCancellation':
                $arr['bookingCancellationXml'] = array(
                    'cancelBookingReference' => 'BookingReferenceTransfer',
                    'cancelBookingStatus' => 'BookingStatus',
                    'cancelBookingPrice' => 'BookingPriceNet');
                $arr['mainXmlAttr']            = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'activityBookingStatus':
                $arr['bookingStatusXml'] = array(
                    'bookingReference' => 'BookingReferenceCityDiscovery',
                    'bookingVeltraId' => 'BookingVeltraId',
                    'bookingStatus' => 'BookingStatus',
                    'firstName' => 'FirstName',
                    'lastName' => 'LastName',
                    'email' => 'Email',
                    'activityName' => 'ActivityName',
                    'packageName' => 'PackageName',
                    'activityDate' => 'ActivityDate',
                    'activitySupplier' => 'ActivitySupplier',
                    'bookingVoucherInformation' => 'BookingVoucherInformation');

                $arr['xmlAttr']                    = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;
            case 'getTransferCountryListing':
                $arr['transferCountryListingXml']  = array('Countries' => array('TransferCountry' =>
                        array('@xmlAttribute' =>
                            array('code' => 'Code'),
                            'name' => 'CountryName',
                            'continent' => 'Continent')));
                break;
            case 'getTransferCityListingByCountry':
                $arr['transferCityListingXml']     = array('Cities' => array('TransferCity' =>
                        array('@xmlAttribute' => array('cityName' => 'Name'))
                ));
                break;
            case 'getTransferAirportListing':
                $arr['transferAirportListingXml']  = array('Airports' => array('Airport' =>
                        array('@xmlAttribute' =>
                            array('id' => 'ID'),
                            'code' => 'TransferAirportCode',
                            'name' => 'TransferAirport',
                            'type' => 'TransferTerminalType')));
                $arr['mainXmlAttr']                = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;
            case 'getTransferVehicles':
                $arr['transferVehiclesListingXml'] = array('TransferInformationItems' => array('Quotes' =>
                        array('country' => 'TransferCountry',
                            'serviceType' => 'TransferServiceType',
                            'numOfPersons' => 'TransferPersons',
                            'airportName' => 'TransferAirportName',
                            'airportCode' => 'TransferAirportCode',
                            'arrivalPickupDate' => 'ArrivalPickupDate',
                            'arrivalPickupTime' => 'ArrivalPickupTime',
                            'arrivalFlightDate' => 'ArrivalFlightDate',
                            'arrivalFlightTime' => 'ArrivalFlightTime',
                            'departurePickupDate' => 'DeparturePickupDate',
                            'departurePickupTime' => 'DeparturePickupTime',
                            'departureFlightDate' => 'DepartureFlightDate',
                            'departureFlightTime' => 'DepartureFlightTime',
                            'transferVehicle' => 'TransferVehicle',
                            'transferMinimumHourBooking' => 'TransferMinimumHourBooking',
                            'transferPickupHour' => 'TransferPickupHour',
                            'arrivalPriceId' => 'ArrivalPriceID',
                            'departurePriceId' => 'DeparturePriceID',
                            'priceResort' => 'PriceResort',
                            'priceTotal' => 'PriceTotal',
                            'priceTotalNet' => 'PriceTotalNet',
                            'pricePerPaxCar' => 'PricePerPaxCar',
                            'priceCurrency' => 'PriceCurrency',
                            'priceType' => 'PriceType',
                            'priceRoundtrip' => 'PriceRoundtrip',
                            'priceZip' => 'PriceZip',
                            'priceCarType' => 'PriceCarType',
                            'priceMinPax' => 'PriceMinPax',
                            'priceCarType' => 'PriceCarType',
                            'priceMaxPax' => 'PriceMaxPax',
                            'priceSeasonFrom' => 'PriceSeasonFrom',
                            'priceSeasonTo' => 'PriceSeasonTo',
                            'priceCarCategory' => 'PriceCarCategory',
                            'priceCarModel' => 'PriceCarModel',
                            'priceCarMinimumPax' => 'PriceCarMaximumPax',
                            'priceCarModel' => 'PriceCarModel'
                )));
                $arr['mainXmlAttr']                = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'transportBooking':
                $arr['transferBookingXml'] = array('productCode' => 'TransferID',
                    'serviceType' => 'TransferType',
                    'serviceCode' => 'TransferServiceCode',
                    'bookingReference' => 'BookingReferenceTransfer',
                    'bookingStatus' => 'BookingStatus',
                    'firstName' => 'FirstName',
                    'lastName' => 'LastName',
                    'email' => 'EmailAddress',
                    'arrivalAirportPortTrain' => 'ArrivalAirportPortTrain',
                    'arrivalFlightTime' => 'ArrivalFlightTime',
                    'arrivalPassenger' => 'ArrivalPassenger',
                    'arrivalDate' => 'ArrivalPickupDate',
                    'arrivalTime' => 'ArrivalPickupTime',
                    'arrivalFlightDate' => 'ArrivalFlightDate',
                    'arrivalFlightDetails' => 'ArrivalflighNumber',
                    'arrivalFrom' => 'ArrivalFrom',
                    'arrivalDestinationAddress' => 'ArrivalDestination',
                    'departAirportPortTrain' => 'DepartAirportPortTrain',
                    'departureFlightTime' => 'DepartureFlightTime',
                    'departPassenger' => 'DepartPassenger',
                    'departureDate' => 'DeparturePickupDate',
                    'departureTime' => 'DeparturePickupTime',
                    'departureFlightDate' => 'DepartureFlightDate',
                    'departureFlightDetails' => 'DepartFlighNumber',
                    'departTo' => 'DepartTo',
                    'departurePickupAddress' => 'DepartPickup',
                    'bookingVoucherInformation' => 'BookingVoucherInformation',
                    'totalPrice' => 'BookingPrice',
                    'currency' => 'BookingCurrency',
                    'creditCardTransactionType' => 'CreditCardTransactionType',
                    'creditCardTransactionID' => 'CreditCardTransactionID');

                $arr['cancellationPolicyXml'] = array('TransferCancellation' =>
                    array('TransferCancellationPolicy' => array('@xmlAttribute' => array('cancellationDay' => 'Day', 'cancellationDiscount' => 'Percentage')))
                );
                $arr['mainXmlAttr']           = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'getQuotation':
                $arr['quoteXml']    = array('SelectPayload' => array('Payload' =>
                        array('quoteKey' => 'QuoteKey',
                            'packageId' => 'PackageId',
                            'activityId' => 'ActivityID',
                            'activityDate' => 'ActivityDate',
                            'total' => 'Total',
                            'currency' => 'Currency',
                            'Units' => array('Unit' => array('unitId' => 'UnitId', 'quantity' => 'Quantity')),
                            'ActivityTime' => array('timeId' => 'TimeId', 'time' => 'Time'))),
                    'Mandatory' => array('Field' => array('fieldId' => 'FieldId',
                            'key' => 'Key',
                            'label' => 'Label',
                            'per' => 'Per',
                            'format' => 'Format',
                            'data' => 'Data'))
                );
                $arr['mainXmlAttr'] = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            default:
                $arr['mainXml'] = array();
                $arr['xmlAttr'] = array();
        }

        return $arr;
    }
}