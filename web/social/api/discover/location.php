<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all()); 

$type = isset($submit_post_get['type']) ? $submit_post_get['type'] :  null;
$city_id = isset($submit_post_get['city_id']) ? $submit_post_get['city_id'] : 0;
$country_code = isset($submit_post_get['country_code']) ? $submit_post_get['country_code'] : '';
$state_code = isset($submit_post_get['state_code']) ? $submit_post_get['state_code'] : '';

try{
    $params1 = array('hosts' => array($CONFIG [ 'elastic' ] [ 'ip' ] ));
    $client = new Elasticsearch\Client($params1);


$searchParams['index'] = $CONFIG [ 'elastic' ] [ 'index' ];
//$searchParams['index'] = 'tt10';
$restaurants_count = 0;
switch($type){
    case "city":
        
        $searchParams['body'] = array (
            'query' => 
            array (
              'match' => 
              array (
                'city_id' => $city_id,
              ),
            ),
          );
        $searchParams['type'] = 'hotel';
        $hotels_count = $client->count($searchParams)['count'];
        
        $searchParams['type'] = 'poi';
        $pois_count = $client->count($searchParams)['count'];
        
        $searchParams['type'] = 'channel';
        $channels_count = $client->count($searchParams)['count'];
        
        $city_info = worldcitiespopInfo($city_id);
        
//        $searchParams['body'] = array (
//            'query' =>
//            array (
//              'multi_match' =>
//              array (
//                'query' => $city_info['name'],
//                'type' => 'phrase',
//                'fields' =>
//                array (
//                  0 => 'locality',
//                  1 => 'region',
//                ),
//              ),
//            ),
//          );
        
//        $searchParams['type'] = 'restaurant';
//        $restaurants_count = $client->count($searchParams)['count'];
        
        $longitude = $city_info['longitude'];
        $latitude = $city_info['latitude'];
        $description = entity_description(SOCIAL_ENTITY_CITY, $city_id);
        break;
    case "country":
        $searchParams['type'] = 'hotel';
        $searchParams['body'] = array (
            'query' => 
            array (
              'match' => 
              array (
                'countryCode' => $country_code,
              ),
            ),
          );
        $hotels_count = $client->count($searchParams)['count'];
        
        $searchParams['body'] = array (
            'query' => 
            array (
              'match' => 
              array (
                'country_code' => $country_code,
              ),
            ),
          );
        
        $searchParams['type'] = 'poi';
        $pois_count = $client->count($searchParams)['count'];
        
        $searchParams['type'] = 'channel';
        $channels_count = $client->count($searchParams)['count'];
        
//        $searchParams['type'] = 'restaurant';
//        $searchParams['body'] = array (
//            'query' =>
//            array (
//              'match' =>
//              array (
//                'country' => $country_code,
//              ),
//            ),
//          );
//        $restaurants_count = $client->count($searchParams)['count'];
        $country_info = countryGetInfo($country_code);
        $longitude = $country_info['longitude'];
        $latitude = $country_info['latitude'];
        $description = entity_description(SOCIAL_ENTITY_COUNTRY, $country_info['id']);
        break;
    case "state":
        $searchParams['type'] = 'hotel';
        $searchParams['body'] = array (
            'query' => 
            array (
              'bool' => 
              array (
                'must' => 
                array (
                  0 => 
                  array (
                    'match' => 
                    array (
                      'countryCode' => $country_code,
                    ),
                  ),
                  1 => 
                  array (
                    'match' => 
                    array (
                      'state_code' => $state_code,
                    ),
                  ),
                ),
              ),
            ),
          );
        $hotels_count = $client->count($searchParams)['count'];
        
        $searchParams['body'] = array (
            'query' => 
            array (
              'bool' => 
              array (
                'must' => 
                array (
                  0 => 
                  array (
                    'match' => 
                    array (
                      'country_code' => $country_code,
                    ),
                  ),
                  1 => 
                  array (
                    'match' => 
                    array (
                      'state_code' => $state_code,
                    ),
                  ),
                ),
              ),
            ),
          );
        
        $searchParams['type'] = 'poi';
        $pois_count = $client->count($searchParams)['count'];
        
        $searchParams['type'] = 'channel';
        $channels_count = $client->count($searchParams)['count'];
        
        $state_info = worldStateInfo($country_code, $state_code);
        $country_info = countryGetInfo($country_code);
        $longitude = $country_info['longitude'];
        $latitude = $country_info['latitude'];
//        $searchParams['body']= array (
//            'query' =>
//            array (
//              'bool' =>
//              array (
//                'must' =>
//                array (
//                  0 =>
//                  array (
//                    'match' =>
//                    array (
//                      'country' => $country_code,
//                    ),
//                  ),
//                  1 =>
//                  array (
//                    'multi_match' =>
//                    array (
//                      'query' => $state_info['state_name'],
//                      'fields' =>
//                      array (
//                        0 => 'region',
//                        1 => 'admin_region',
//                      ),
//                    ),
//                  ),
//                ),
//              ),
//            ),
//          );
//        $searchParams['type'] = 'restaurant';
//        $restaurants_count = $client->count($searchParams)['count'];
        $description = entity_description(SOCIAL_ENTITY_STATE, $state_info['id']);
        break;
}

$result = array(
    'hotels_count' => $hotels_count,
    'restaurants_count' => $restaurants_count,
    'pois_count' => $pois_count,
    'channels_count' => $channels_count,
    'description' => $description,
    'longitude' => $longitude ? $longitude : '',
    'latitude' => $latitude ? $latitude : ''
);
echo json_encode($result);
}
catch(Exception $ex){
    echo $ex->getMessage();
    exit();
}