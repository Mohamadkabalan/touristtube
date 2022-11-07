<?php

namespace TTBundle\Services;

use TTBundle\Utils\Utils;

if (!defined('RESPONSE_SUCCESS'))
    define('RESPONSE_SUCCESS', 0);

if (!defined('RESPONSE_ERROR'))
    define('RESPONSE_ERROR', 1);

class GartourServices {
    protected $utils;
    private $TEST_MODE = true;
    private $STATIC_URL = '';
    private $DYNAMIC_URL = '';
    private $LICENCE_KEY = '';
    private $TEST_LICENCE_KEY = 'DB81B95B-A532-4CB8-A1A5-2897089557EA';
    private $PROD_LICENCE_KEY = '';
    private $HTTP_TEST_STATIC_URL = 'http://212.31.251.151/B2CWS_phase2/TSStaticData.asmx';
    private $HTTP_TEST_DYNAMIC_URL = 'http://212.31.251.151/B2CWS_phase2/TravelStudioB2BWebService.asmx';
    private $HTTP_PROD_STATIC_URL = '';
    private $HTTP_PROD_DYNAMIC_URL = '';
    private $HTTP_AUTH_USER = null;
    private $HTTP_AUTH_PASSWORD = '';
    private $HTTP_AUTH_METHOD = 'none'; // none, basic, digest
    private $ADDITIONAL_HEADERS = array("Content-Type" => "text/xml;charset=UTF-8", "SOAPAction" => "OTA", "Connection" => "Keep-Alive", 'Accept' => 'text/xml', 'Cache-Control' => 'no-cache', 'Pragma' => 'no-cache');
    
    public function __construct(Utils $utils){
        $this->STATIC_URL = ($this->TEST_MODE) ? $this->HTTP_TEST_STATIC_URL : $this->HTTP_PROD_STATIC_URL;
        $this->DYNAMIC_URL = ($this->TEST_MODE) ? $this->HTTP_TEST_DYNAMIC_URL : $this->HTTP_PROD_DYNAMIC_URL;
        $this->LICENCE_KEY = ($this->TEST_MODE) ? $this->TEST_LICENCE_KEY : $this->PROD_LICENCE_KEY;
        $this->utils = $utils;
    }
    
    public function atiRequest(){
        $staticDataRequest = <<<EOL
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ati="http://www.americantours.com/ATI/2012/01">    
	<soap:Header/>
	<soap:Body>
		<ati:ATI_PkgAvailRQ Version="1.1">
			<ati:PackageRequest Type="ALL" Criteria="Search" CityCode="10319"/>
		</ati:ATI_PkgAvailRQ>
	</soap:Body>
</soap:Envelope>
EOL;
        //$this->ADDITIONAL_HEADERS['SOAPAction'] = 'http://tempuri.org/ATI_PkgAvailRQ';
        $this->ADDITIONAL_HEADERS['SOAPAction'] = '';
        $this->ADDITIONAL_HEADERS['Content-Length'] = strlen($staticDataRequest);
        $this->ADDITIONAL_HEADERS['Accept-Encoding'] = 'gzip,deflate';
        //$user_pass = '8rst4b5z2:RcGyYV3S9';
        $user_pass = '8rst4b5z2:Ei5KpZJ8A';
        $base64 = base64_encode($user_pass);
        $this->ADDITIONAL_HEADERS['Authorization'] = 'Basic ' . $base64;
        $credentials = array(
            'auth_method' => 'basic',
            'username' => '8rst4b5z2',
            'password' => 'Ei5KpZJ8A'
            //'password' => 'RcGyYV3S9'
        );
        $getStaticDataResponse = $this->utils->send_data('https://test.americantours.com/ati-services/ATIPkgInterface', $staticDataRequest, \HTTP_Request2::METHOD_POST, $credentials, $this->ADDITIONAL_HEADERS);
        print_r($getStaticDataResponse['response_text']);
    }
    
    public function getStaticDataRequest($options){
        $serviceType = $options['serviceType'];
//        print_r($options);
        if(empty($serviceType)){
            return 'no service type';
        }
        
        $staticDataRequest = <<<EOL
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
   <soapenv:Header/>
   <soapenv:Body>
      <tem:GetServiceStaticData>
         <tem:SERVICE_STATIC_DATA_REQUEST>
            <tem:VERSION_HISTORY LICENCE_KEY="$this->LICENCE_KEY">
            </tem:VERSION_HISTORY>
            <tem:enmStaticDataType>$serviceType</tem:enmStaticDataType>
         </tem:SERVICE_STATIC_DATA_REQUEST>
      </tem:GetServiceStaticData>
   </soapenv:Body>
</soapenv:Envelope>   
EOL;
//        print_r($staticDataRequest);
        $this->ADDITIONAL_HEADERS['SOAPAction'] = 'http://tempuri.org/GetServiceStaticData';
        $this->ADDITIONAL_HEADERS['Content-Length'] = strlen($staticDataRequest);
//        print_r($this->ADDITIONAL_HEADERS);
        $getStaticDataResponse = $this->utils->send_data($this->STATIC_URL, $staticDataRequest, \HTTP_Request2::METHOD_POST, null, $this->ADDITIONAL_HEADERS);
        if($getStaticDataResponse['response_error'] == RESPONSE_ERROR){
            print_r($getStaticDataResponse['response_text']);
            return 'error:';
        }
//        print_r($getStaticDataResponse['response_text']);
//        echo '<textarea rows="20" cols="100">'.$getStaticDataResponse['response_text'].'</textarea><br/><br/>';
        $xpath = $this->utils->xmlString2domXPath($getStaticDataResponse['response_text']);
        // $regions = $xpath->query("/soap:Envelope/soap:Body/xmlns:GetServiceStaticDataResponse/xmlns:SERVICE_STATIC_DATA_RESPONSE/xmlns:ALL_STATIC_DATA");
        $regions = $xpath->query("/soap:Envelope/soap:Body/xmlns:GetServiceStaticDataResponse/xmlns:SERVICE_STATIC_DATA_RESPONSE/xmlns:ALL_STATIC_DATA");
        // $regions = $xpath->query("/soap:Envelope/soap:Body/xmlns:GetServiceStaticDataResponse/xmlns:SERVICE_STATIC_DATA_RESPONSE/xmlns:ALL_STATIC_DATA/xmlns:REGIONS/xmlns:REGION[xmlns:REGIONID = '9082']");
        
//        foreach($regions as $regions){
//            
//        }
        $regions = $regions->item(0);
        $node_info = array();
            $this->utils->fetch_node_info($regions, $node_info);
            echo '<pre>';
            print_r($node_info);
            echo '</pre>';
            exit;
        foreach($regions as $region){
            $node_info = array();
            $this->utils->fetch_node_info($region, $node_info);
            print_r($node_info);
            // break;
//            print_r($region->REGION->REGIONNAME->nodeValue);continue;
//            print_r($region);
//            continue;
            $region_name = $xpath->query('./@REGIONNAME', $region);
            $region_name = $region_name->item(0)->nodeValue;
            print_r($region_name);
        }
//        print_r($regions);
    }
    
    public function getServiceListRequest($options = array()){
//        $serviceType = $options['serviceType'];
        $region_param = '';
        $serviceType = 5;
        if($options){
            if(isset($options['regionId'])){
                $regionId = $options['regionId'];
                $region_param = <<<EOL
<tem:RegionID>$regionId</tem:RegionID>
<tem:ServiceTypeID>5</tem:ServiceTypeID>
EOL;
            }
            if(isset($options['serviceType'])){
                $serviceType = $options['serviceType'];
            }
        }
        $serviceListRequest = <<<EOL
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/">
   <soap:Header/>
   <soap:Body>
      <tem:GetServiceList>
         <tem:IMPORT_SERVICE_SEARCH_REQUEST>
            <tem:VERSION_HISTORY LICENCE_KEY="$this->LICENCE_KEY">
            </tem:VERSION_HISTORY>
            $region_param
            <tem:ServiceTypeID>$serviceType</tem:ServiceTypeID>
         </tem:IMPORT_SERVICE_SEARCH_REQUEST>
      </tem:GetServiceList>
   </soap:Body>
</soap:Envelope>
EOL;
        $this->ADDITIONAL_HEADERS['SOAPAction'] = 'http://tempuri.org/GetServiceList';
        $this->ADDITIONAL_HEADERS['Content-Length'] = strlen($serviceListRequest);
//        print_r($this->ADDITIONAL_HEADERS);
        $getServiceListResponse = $this->utils->send_data($this->STATIC_URL, $serviceListRequest, \HTTP_Request2::METHOD_POST, null, $this->ADDITIONAL_HEADERS);
        if($getServiceListResponse['response_error'] == RESPONSE_ERROR){
            print_r($getServiceListResponse['response_text']);
            return 'error:';
        }
        print_r($getServiceListResponse['response_text']);
        $xpath = $this->utils->xmlString2domXPath($getServiceListResponse['response_text']);
    }
    
    public function getServiceInfoRequest($options = array()){
        $serviceId = 0;
        $isRatingDataRequired = 0;
        $images = 'All';
        if($options){
            if(isset($options['serviceId'])){
                $serviceId = $options['serviceId'];
            }
        }
        if($serviceId == 0){
            return 'error';
        }
        $serviceInfoRequest = <<<EOL
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
   <soapenv:Header/>
   <soapenv:Body>
      <tem:GetServiceInformation>
         <tem:ServiceInfoRequest>
            <tem:Authenticate>
               <tem:LICENSEKEY>$this->LICENCE_KEY</tem:LICENSEKEY>
               <tem:Connector>enmTS</tem:Connector>
            </tem:Authenticate>
            <tem:ServiceId>$serviceId</tem:ServiceId>
            <tem:IsRatingDataRequired>$isRatingDataRequired</tem:IsRatingDataRequired>
            <tem:Images>$images</tem:Images>
         </tem:ServiceInfoRequest>
      </tem:GetServiceInformation>
   </soapenv:Body>
</soapenv:Envelope>
EOL;
        $this->ADDITIONAL_HEADERS['SOAPAction'] = 'http://tempuri.org/GetServiceInformation';
        $this->ADDITIONAL_HEADERS['Content-Length'] = strlen($serviceInfoRequest);
//        print_r($this->ADDITIONAL_HEADERS);
        $getServiceInfoResponse = $this->utils->send_data($this->STATIC_URL, $serviceInfoRequest, \HTTP_Request2::METHOD_POST, null, $this->ADDITIONAL_HEADERS);
        if($getServiceInfoResponse['response_error'] == RESPONSE_ERROR){
            print_r($getServiceInfoResponse['response_text']);
            return 'error:';
        }
        print_r($getServiceInfoResponse['response_text']);
        $xpath = $this->utils->xmlString2domXPath($getServiceInfoResponse['response_text']);
    }
    
    public function getServicesPricesAndAvailabilityRequest($options = array()){
        $mandatoryExtraPrices = 'true';
        $attachedOptionExtra = 'true';
        $currency = "Eur";
        $mealPlanRequired = 'true';
        $notesRequired = 'true';
        $serviceTypeId = 5;
        $geoLocationId = 6965;
        $startDate = '2016-10-25';
        $endDate = '';
        $number_of_nights = 0;
        $non_accom_services = 'true';
        $bookingTypeId = 1;
        $images = 'All';
        if($options){
            if(isset($options['serviceId'])){
                $serviceId = $options['serviceId'];
            }
        }
        
        $servicesPricesAndAvailabilityRequest = <<<EOL
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <soap:Body>
      <GetServicesPricesAndAvailability xmlns="http://tempuri.org/">
         <IncomingRequest>
            <VERSION_HISTORY APPLICATION_NAME="xxx" LANGUAGE="en" XML_FILE_NAME="abd" LICENCE_KEY="$this->LICENCE_KEY" TS_API_VERSION="21"/>
            <!--<ReturnMandatoryExtraPrices>$mandatoryExtraPrices</ReturnMandatoryExtraPrices>
            <ReturnAttachedOptionExtra>$attachedOptionExtra</ReturnAttachedOptionExtra>-->
            <CURRENCY>$currency</CURRENCY>
            <!--<ISMEALPLANSREQUIRED>$mealPlanRequired</ISMEALPLANSREQUIRED>
            <NotesRequired>$notesRequired</NotesRequired>-->
            <SERVICETYPEID>$serviceTypeId</SERVICETYPEID>
            <GEO_LOCATION_ID>$geoLocationId</GEO_LOCATION_ID>
            <START_DATE>$startDate</START_DATE>
            <NUMBER_OF_NIGHTS>$number_of_nights</NUMBER_OF_NIGHTS>
            <RETURN_ONLY_NON_ACCOM_SERVICES>$non_accom_services</RETURN_ONLY_NON_ACCOM_SERVICES>
            <ROOM_REPLY>
               <ANY_ROOM>true</ANY_ROOM>
            </ROOM_REPLY>
            <BOOKING_TYPE_ID>1</BOOKING_TYPE_ID>
            <ROOMS_REQUIRED>
               <ROOM>
                  <OCCUPANCY>0</OCCUPANCY>
                  <QUANTITY>1</QUANTITY>
                  <NO_OF_PASSENGERS>1</NO_OF_PASSENGERS>
               </ROOM>
            </ROOMS_REQUIRED>
         </IncomingRequest>
      </GetServicesPricesAndAvailability>
   </soap:Body>
</soap:Envelope>
EOL;
//        echo $servicesPricesAndAvailabilityRequest;
        $servicesPricesAndAvailabilityRequest = <<<EOL
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <soap:Body>
      <GetServicesPricesAndAvailability xmlns="http://tempuri.org/">
         <IncomingRequest>
            <VERSION_HISTORY LICENCE_KEY="$this->LICENCE_KEY">
            </VERSION_HISTORY>
            <CURRENCY>$currency</CURRENCY>
            <SERVICETYPEID>$serviceTypeId</SERVICETYPEID>
            <GEO_LOCATION_ID>$geoLocationId</GEO_LOCATION_ID>
            <START_DATE>$startDate</START_DATE>
            <NUMBER_OF_NIGHTS>$number_of_nights</NUMBER_OF_NIGHTS>
            <RETURN_ONLY_NON_ACCOM_SERVICES>$non_accom_services</RETURN_ONLY_NON_ACCOM_SERVICES>
            <ROOM_REPLY>
               <ANY_ROOM>true</ANY_ROOM>
            </ROOM_REPLY>
            <BOOKING_TYPE_ID>1</BOOKING_TYPE_ID>
            <ROOMS_REQUIRED>
               <ROOM>
                  <OCCUPANCY>0</OCCUPANCY>
                  <QUANTITY>1</QUANTITY>
                  <NO_OF_PASSENGERS>2</NO_OF_PASSENGERS>
               </ROOM>
            </ROOMS_REQUIRED>
         </IncomingRequest>
      </GetServicesPricesAndAvailability>
   </soap:Body>
</soap:Envelope>
EOL;
        $servicesPricesAndAvailabilityRequest = <<<EOL
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <soap:Body>
      <GetServicesPricesAndAvailability xmlns="http://tempuri.org/">
         <!--Optional:-->
         <IncomingRequest>
            <VERSION_HISTORY LICENCE_KEY="$this->LICENCE_KEY" TS_API_VERSION="21"/>
            <ReturnMandatoryExtraPrices>true</ReturnMandatoryExtraPrices>
            <ReturnAttachedOptionExtra>true</ReturnAttachedOptionExtra>
            <CURRENCY>Eur</CURRENCY>
            <ISMEALPLANSREQUIRED>true</ISMEALPLANSREQUIRED>
            <NotesRequired>true</NotesRequired>
            <SERVICETYPEID>5</SERVICETYPEID>
            <!--___________________________________________________-->
            <!--SEARCHING FOR A REGION OR FOR A SERVICE-->
            <!--it is necessary to use only one 
                 of this two following tag in search:
                 - use GEO_LOCATION_ID to search for a whole Region,
                 setting tag value with RegionID
                 - use SERVICEIDs to search for a specified service,
                 setting tag value with appropriate ServiceID-->
            <GEO_LOCATION_ID>6965</GEO_LOCATION_ID>
            <!-- <SERVICEIDs>19924</SERVICEIDs> -->
            <!--___________________________________________________-->
            <START_DATE>2016-10-25</START_DATE>
            <NUMBER_OF_NIGHTS>0</NUMBER_OF_NIGHTS>
            <RETURN_ONLY_NON_ACCOM_SERVICES>true</RETURN_ONLY_NON_ACCOM_SERVICES>
            <ROOM_REPLY>
               <ANY_ROOM>true</ANY_ROOM>
            </ROOM_REPLY>
            <BOOKING_TYPE_ID>1</BOOKING_TYPE_ID>
            <ROOMS_REQUIRED>
               <ROOM>
                  <!--OCCUPANCY value has to be set ever as 0-->
                  <OCCUPANCY>0</OCCUPANCY>
                  <QUANTITY>1</QUANTITY>
                  <NO_OF_PASSENGERS>2</NO_OF_PASSENGERS>
               </ROOM>
            </ROOMS_REQUIRED>
         </IncomingRequest>
      </GetServicesPricesAndAvailability>
   </soap:Body>
</soap:Envelope>      
EOL;
//        print_r($servicesPricesAndAvailabilityRequest);
        $this->ADDITIONAL_HEADERS['SOAPAction'] = 'http://tempuri.org/GetServicesPricesAndAvailability';
        $this->ADDITIONAL_HEADERS['Content-Length'] = strlen($servicesPricesAndAvailabilityRequest);
//        print_r($this->ADDITIONAL_HEADERS);
        $servicesPricesAndAvailabilityResponse = $this->utils->send_data($this->DYNAMIC_URL, $servicesPricesAndAvailabilityRequest, \HTTP_Request2::METHOD_POST, null, $this->ADDITIONAL_HEADERS);
        if($servicesPricesAndAvailabilityResponse['response_error'] == RESPONSE_ERROR){
            print_r($servicesPricesAndAvailabilityResponse['response_text']);
            return 'error:';
        }
        print_r($servicesPricesAndAvailabilityResponse['response_text']);
        $xpath = $this->utils->xmlString2domXPath($servicesPricesAndAvailabilityResponse['response_text']);
    }
}