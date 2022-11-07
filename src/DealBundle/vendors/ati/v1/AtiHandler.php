<?php

namespace DealBundle\vendors\citydiscovery\v1;

class CityDiscoveryHandler
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
                $arr['mainXml']     = array('Tour' => array('productCode' => 'ActivityID',
                        'ActivitySupplier' => 'ActivitySupplier',
                        'dealCountry' => 'ActivityCountry',
                        'dealCity' => 'ActivityCity',
                        'ActivityRegion' => 'ActivityRegion',
                        'dealRating' => 'ActivityReviewRating',
                        'dealHighlights' => 'ActivityHighlights',
                        'dealName' => 'ActivityName',
                        'ActivityReleaseDate' => 'ActivityReleaseDate',
                        'startingPlace' => 'ActivityStartingPlace',
                        'dealDescription' => 'ActivityDescription',
                        'dealLanguages' => 'ActivityLanguages',
                        'ActivityDateAdded' => 'ActivityDateAdded',
                        'ActivityDateModified' => 'ActivityDateModified',
                        'ActivityDuration' => 'ActivityDuration',
                        'dealDuration' => 'ActivityDurationText',
                        'ActivityAvailabilityType' => 'ActivityAvailabilityType',
                        'dealBlockOutDates' => 'ActivityBlockOutdates',
                        'dealTerms' => 'ActivityTermsConditions'));
                $arr['multipleXml'] = array('ActivityPriceId' => array('@xmlAttribute' => array('priceId' => 'ID'),
                        'priceDays' => array(
                            'ActivityPriceDays' => array('monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday')
                        ),
                        'optionLabel' => 'ActivityPriceOption',
                        'optionDepartureTime' => 'ActivityPriceOptionDepartureTime',
                        'optionDateBegins' => 'ActivityPriceDateBegins',
                        'optionDateEnd' => 'ActivityPriceDateEnds',
                        'optionGroupMinPax' => 'ActivityPriceGroupMinPax',
                        'optionGroupMaxPax' => 'ActivityPriceGroupMaxPax',
                        'optionPriceAdult' => 'ActivityPriceAdult',
                        'optionPriceAdultUSD' => 'ActivityPriceAdultUSD',
                        'optionPriceAdultNet' => 'ActivityPriceAdultNet',
                        'optionPriceChild' => 'ActivityPriceChild',
                        'optionPriceChildUSD' => 'ActivityPriceChildUSD',
                        'optionPriceChildNet' => 'ActivityPriceChildNet',
                        'optionPriceChildNetUSD' => 'ActivityPriceChildNetUSD',
                        'optionPriceCurrency' => 'ActivityPriceCurrency',
                ));
                $arr['xmlAttr']     = array('ActivityStartingPlace' => array('latitude' => 'Lat', 'longitude' => 'Long'),
                    'ActivityCategory' => array('activityCategoryId' => 'ID'),
                    'ActivitySubCategory' => array('activitySubCategoryId' => 'ID'),
                    'ActivityCancellationPolicy' => array('cancellationDay' => 'Day', 'cancellationDiscount' => 'Percentage'),
                    'OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;
            case 'checkAvailiblity':
                $arr['mainXml']     = array('Tour' => array('productCode' => 'ActivityID'));
                $arr['xmlAttr']     = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;
            case 'categoryListing':
                $arr['xmlAttr']     = array('Category' =>
                    array('categoryItemsId' => 'id',
                        'categoryItemsName' => 'name'));
                break;

            case 'subCategoryListing':
                $arr['xmlAttr'] = array('SubCategory' =>
                    array('subCategoryItemsId' => 'id',
                        'subCategoryItemsName' => 'name'));
                break;

            case 'priceDetails':
                $arr['multipleXml'] = array('ActivityPriceId' =>
                    array('@xmlAttribute' =>
                        array('priceId' => 'ID'),
                        'optionLabel' => 'ActivityPriceOption',
                        'optionDepartureTime' => 'ActivityPriceOptionDepartureTime',
                        'optionDateBegins' => 'ActivityPriceDateBegins',
                        'optionDateEnd' => 'ActivityPriceDateEnds',
                        'optionPriceGroup' => 'ActivityPriceGroup',
                        'optionGroupMinPax' => 'ActivityPriceGroupMinPax',
                        'optionGroupMaxPax' => 'ActivityPriceGroupMaxPax',
                        'optionPriceAdult' => 'ActivityPriceAdult',
                        'optionPriceAdultUSD' => 'ActivityPriceAdultUSD',
                        'optionPriceAdultNet' => 'ActivityPriceAdultNet',
                        'optionPriceChild' => 'ActivityPriceChild',
                        'optionPriceChildUSD' => 'ActivityPriceChildUSD',
                        'optionPriceChildNet' => 'ActivityPriceChildNet',
                        'optionPriceChildNetUSD' => 'ActivityPriceChildNetUSD',
                        'optionPriceCurrency' => 'ActivityPriceCurrency',
                        'optionChildAllowed' => 'ActivityChildAllowed',
                        'optionChildMaxAge' => 'ActivityChildMaxAge',
                        'optionInfantAllowed' => 'ActivityInfantAllowed',
                        'optionInfantMaxAge' => 'ActivityInfantMaxAge',
                        'optionPriceBeforeDiscount' => 'ActivityPriceBeforeDiscount',
                        'optionPriceDiscountMessage' => 'ActivityPriceDiscountMessage'));

                $arr['xmlAttr'] = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'activityReviews':
                $arr['multipleXml'] = array('Review' =>
                    array('@xmlAttribute' =>
                        array('reviewId' => 'ID'),
                        'comment' => 'comment',
                        'rating' => 'rate',
                        'owner' => 'owner',
                        'country' => 'country',
                        'date' => 'date'));
                break;

            case 'activityBooking':
                $arr['mainXml'] = array('CityDiscovery' =>
                    array('productCode' => 'ActivityID',
                        'priceId' => 'ActivityPriceId',
                        'optionDepartureTime' => 'ActivityPriceOptionDepartureTime',
                        'bookingReference' => 'BookingReferenceCityDiscovery',
                        'bookingStatus' => 'BookingStatus',
                        'bookingVoucherInformation' => 'BookingVoucherInformation',
                        'bookingPrice' => 'BookingPrice',
                        'bookingCurrency' => 'BookingCurrency',
                        'creditCardTransactionType' => 'CreditCardTransactionType',
                        'creditCardTransactionID' => 'CreditCardTransactionID'));

                $arr['xmlAttr'] = array('ActivityCancellationPolicy' => array('cancellationDay' => 'Day', 'cancellationDiscount' => 'Percentage'),
                    'OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'activityCancellation':
                $arr['mainXml'] = array('CityDiscovery' => array(
                        'BookingReference' => 'BookingReferenceCityDiscovery',
                        'bookingStatus' => 'BookingStatus',
                        'price' => 'BookingPriceNet'));

                $arr['xmlAttr'] = array('BookingCancellationFee' => array('cancellationDay' => 'Day', 'cancellationDiscount' => 'Percentage'),
                    'OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;

            case 'activityBookingStatus':
                $arr['mainXml'] = array('CityDiscovery' => array(
                        'BookingReference' => 'BookingReferenceCityDiscovery',
                        'bookingStatus' => 'BookingStatus',
                        'price' => 'BookingPrice'));

                $arr['xmlAttr']     = array('OTA_ErrorRS' => array('errorCode' => 'ErrorCode', 'errorMessage' => 'ErrorMessage'));
                break;
            case 'getTransferCountryListing':
                $arr['multipleXml'] = array('TransferCountry' =>
                    array('@xmlAttribute' =>
                        array('code' => 'Code'),
                        'name' => 'CountryName',
                        'continent' => 'Continent'));
                break;
            case 'getTransferCityListingByCountry':
                $arr['xmlAttr']     = array('TransferCity' =>
                    array('cityName' => 'Name'));
                break;
            case 'getTransferAirportListing':
                $arr['multipleXml'] = array('Airport' =>
                    array('@xmlAttribute' =>
                        array('id' => 'ID'),
                        'code' => 'TransferAirportCode',
                        'name' => 'TransferAirport',
                        'type' => 'TransferTerminalType'));
                break;
            default:
                $arr['mainXml']     = array();
                $arr['xmlAttr']     = array();
        }

        return $arr;
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

        $requestUrl = (isset($resultAttr['useTransfersUrl']) && $resultAttr['useTransfersUrl']) ? $this->HTTP_TEST_TRANSFER_URL : $this->STATIC_URL;

        $data                  = array("data" => $requestBody);
        $getStaticDataResponse = $this->utils->send_data($requestUrl, $data, \HTTP_Request2::METHOD_POST);

        // just added error message for error handling
        if (!isset($getStaticDataResponse['response_text']) || empty($getStaticDataResponse['response_text'])) {
            return array('errorAPIMessage' => 'Error From City Discovery API call');
        }

        $dom = new \DOMDocument();
        $dom->loadXml($getStaticDataResponse['response_text']);

        // build details array
        $details = array();

        //This will handle the xml tag names
        if (isset($resultAttr['mainXml'])) {
            foreach ($resultAttr['mainXml'] as $raKey => $raVal) {
                $raElem = $dom->getElementsByTagName($raKey);
                if ($raElem && $raElem->length) {
                    for ($i = 0; $i < $raElem->length; $i++) {
                        foreach ($raVal as $raKey2 => $raVal2) {
                            $details[$raKey2] = $raElem->item($i)->getElementsByTagName($raVal2)->item(0)->nodeValue;
                        }
                    }
                }
            }
        }

        //This will handle the xml tag that will be place inside a mutidimensional array
        if (isset($resultAttr['multipleXml'])) {
            $multipleArray = array();
            foreach ($resultAttr['multipleXml'] as $raKey => $raVal) {
                $raElem = $dom->getElementsByTagName($raKey);
                if ($raElem && $raElem->length) {
                    for ($i = 0; $i < $raElem->length; $i++) {
                        foreach ($raVal as $raKey2 => $raVal2) {
                            //If you want to get an attribute of you main(ONLY) <xmlTag>
                            if ('@xmlAttribute' == $raKey2) {
                                foreach ($raVal2 as $raKey3 => $raVal3) {
                                    $multipleArray[$i][$raKey3] = $raElem->item($i)->getAttribute($raVal3);
                                }
                            }
                            if (is_array($raVal2)) {
                                foreach ($raVal2 as $raKey3 => $raVal3) {
                                    if (is_array($raVal3)) {
                                        $traElem = $dom->getElementsByTagName($raKey3);
                                        foreach ($raVal3 as $raKey4 => $raVal4) {
                                            $multipleArray[$i][$raKey2][$raKey4] = $traElem->item($i)->getElementsByTagName($raVal4)->item(0)->nodeValue;
                                        }
                                    }
                                }
                            } else {
                                $multipleArray[$i][$raKey2] = $raElem->item($i)->getElementsByTagName($raVal2)->item(0)->nodeValue;
                            }
                        }
                    }
                }
            }
            $details[$raKey] = $multipleArray;
        }

        //This will handle the tag attributes
        if (isset($resultAttr['xmlAttr'])) {
            foreach ($resultAttr['xmlAttr'] as $raKey3 => $raVal3) {
                $raElem2 = $dom->getElementsByTagName($raKey3);
                if ($raElem2 && $raElem2->length) {
                    $len = $raElem2->length;
                    for ($i = 0; $i < $len; $i++) {
                        foreach ($raVal3 as $raKey4 => $raVal4) {
                            if ($len == 1) {
                                $details[$raKey4] = $raElem2->item($i)->getAttribute($raVal4);
                            } else {
                                $details[$raKey4][$i] = $raElem2->item($i)->getAttribute($raVal4);
                            }
                        }
                    }
                }
            }
        }
        return $details;
    }

}