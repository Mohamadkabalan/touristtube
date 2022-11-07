<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all()); 
$user_id = mobileIsLogged($submit_post_get['S']);
$type = isset($submit_post_get['locality_type']) ? $submit_post_get['locality_type'] :  null;
$entity_type = isset($submit_post_get['entity_type']) ? intval($submit_post_get['entity_type']) :  0;
$city_id = isset($submit_post_get['city_id']) ? $submit_post_get['city_id'] : 0;
$country_code = isset($submit_post_get['country_code']) ? $submit_post_get['country_code'] : '';
$state_code = isset($submit_post_get['state_code']) ? $submit_post_get['state_code'] : '';
$page = isset($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;
$limit = 10;
$from = $page * $limit;
try{
    $params1 = array('hosts' => array($CONFIG [ 'elastic' ] [ 'ip' ] ));
    $client = new Elasticsearch\Client($params1);


$searchParams['index'] = $CONFIG [ 'elastic' ] [ 'index' ];
//$searchParams['index'] = 'tt10';
$hotel_results = array();
$restaurant_results = array();
$poi_results = array();
$channel_results = array();
switch($type){
    case "city":
        $searchParams['body'] = array (
            'size' => $limit,
            'from' => $from,
            'query' => 
            array (
              'match' => 
              array (
                'city_id' => $city_id,
              ),
            ),
          );
        switch($entity_type){
            case SOCIAL_ENTITY_HOTEL:
                $searchParams['type'] = 'hotel';
                $retDoc = $client->search($searchParams);
                $hotel_results = $retDoc['hits']['hits'];
                break;
//            case SOCIAL_ENTITY_RESTAURANT:
//                $city_info = worldcitiespopInfo($city_id);
//                $searchParams['body'] = array (
//                    'size' => $limit,
//                    'from' => $from,
//                    'query' =>
//                    array (
//                      'multi_match' =>
//                      array (
//                        'query' => $city_info['name'],
//                        'type' => 'phrase',
//                        'fields' =>
//                        array (
//                          0 => 'locality',
//                          1 => 'region',
//                        ),
//                      ),
//                    ),
//                  );
//                $searchParams['type'] = 'restaurant';
//                $retDoc = $client->search($searchParams);
//                $restaurant_results = $retDoc['hits']['hits'];
//                break;
            case SOCIAL_ENTITY_LANDMARK:
                $searchParams['type'] = 'poi';
                $retDoc = $client->search($searchParams);
                $poi_results = $retDoc['hits']['hits'];
                break;
            case SOCIAL_ENTITY_CHANNEL:
                $searchParams['type'] = 'channel';
                $retDoc = $client->search($searchParams);
                $channel_results = $retDoc['hits']['hits'];
                break;
        }
        break;
    case "country":
        $searchParams['body'] = array (
            'size' => $limit,
            'from' => $from,
            'query' => 
            array (
              'match' => 
              array (
                'country_code' => $country_code,
              ),
            ),
          );
        switch($entity_type){
            case SOCIAL_ENTITY_HOTEL:
                $searchParams['type'] = 'hotel';
                $searchParams['body'] = array (
                    'size' => $limit,
                    'from' => $from,
                    'query' => 
                    array (
                      'match' => 
                      array (
                        'countryCode' => $country_code,
                      ),
                    ),
                  );
                $retDoc = $client->search($searchParams);
                $hotel_results = $retDoc['hits']['hits'];
                break;
//            case SOCIAL_ENTITY_RESTAURANT:
//                $searchParams['type'] = 'restaurant';
//                $searchParams['body'] = array (
//                    'size' => $limit,
//                    'from' => $from,
//                    'query' =>
//                    array (
//                      'match' =>
//                      array (
//                        'country' => $country_code,
//                      ),
//                    ),
//                  );
//                $retDoc = $client->search($searchParams);
//                $restaurant_results = $retDoc['hits']['hits'];
//                break;
            case SOCIAL_ENTITY_LANDMARK:
                $searchParams['type'] = 'poi';
                $retDoc = $client->search($searchParams);
                $poi_results = $retDoc['hits']['hits'];
                break;
            case SOCIAL_ENTITY_CHANNEL:
                $searchParams['type'] = 'channel';
                $retDoc = $client->search($searchParams);
                $channel_results = $retDoc['hits']['hits'];
                break;
        }
        break;
    case "state":
        $searchParams['body'] = array (
            'size' => $limit,
            'from' => $from,
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
        switch($entity_type){
            case SOCIAL_ENTITY_HOTEL:
                $searchParams['type'] = 'hotel';
                $searchParams['body'] = array (
                    'size' => $limit,
                    'from' => $from,
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
                $retDoc = $client->search($searchParams);
                $hotel_results = $retDoc['hits']['hits'];
                break;
//            case SOCIAL_ENTITY_RESTAURANT:
//                $state_info = worldStateInfo($country_code, $state_code);
//                $searchParams['type'] = 'restaurant';
//                $searchParams['body'] = array (
//                    'query' =>
//                    array (
//                      'bool' =>
//                      array (
//                        'must' =>
//                        array (
//                          0 =>
//                          array (
//                            'bool' =>
//                            array (
//                              'should' =>
//                              array (
//                                0 =>
//                                array (
//                                  'multi_match' =>
//                                  array (
//                                    'query' => $state_info['state_name'],
//                                    'fields' =>
//                                    array (
//                                      0 => 'region',
//                                      1 => 'admin_region',
//                                    ),
//                                  ),
//                                ),
//                                1 =>
//                                array (
//                                  'multi_match' =>
//                                  array (
//                                    'query' => $state_code,
//                                    'fields' =>
//                                    array (
//                                      0 => 'region',
//                                      1 => 'admin_region',
//                                    ),
//                                  ),
//                                ),
//                              ),
//                            ),
//                          ),
//                          1 =>
//                          array (
//                            'match' =>
//                            array (
//                              'country' => $country_code,
//                            ),
//                          ),
//                        ),
//                      ),
//                    ),
//                  );
//                $retDoc = $client->search($searchParams);
//                $restaurant_results = $retDoc['hits']['hits'];
//                break;
            case SOCIAL_ENTITY_LANDMARK:
                $searchParams['type'] = 'poi';
                $retDoc = $client->search($searchParams);
                $poi_results = $retDoc['hits']['hits'];
                break;
            case SOCIAL_ENTITY_CHANNEL:
                $searchParams['type'] = 'channel';
                $retDoc = $client->search($searchParams);
                $channel_results = $retDoc['hits']['hits'];
                break;
        }
        break;
}

$all_result = array_merge($poi_results,$hotel_results,$restaurant_results,$channel_results);
            
$sorted_items = array();
foreach ($all_result as $key => $row) {
    $sorted_items[$key]  = $row['_score'];
}
array_multisort($sorted_items, SORT_DESC, $all_result, SORT_DESC);
$final_res = array();
foreach($all_result as $item){
    $res_item = array();
    switch($item['_type']){
        case 'channels':
        case 'channel':
            $res_item['id'] = $item['_source']['id'];
            $channelInfo = channelGetInfo($res_item['id']);
            if($channelInfo['logo'] == ''){
                $channel_logo = 'media/tubers/tuber.jpg';
            }else{
                $channel_logo = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo'];
            }
            if($channelInfo['header'] == ''){
                $channel_header = 'media/images/channel/coverphoto.jpg';
            }
            else{
                $channel_header = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['header'];
            }
            $date = returnSocialTimeFormat( $channelInfo['create_ts'] ,3);
            $channel_created_date = ($channelInfo['hidecreatedon']==0) ? $date : '';
            $connected_tubers = getConnectedtubers($channelInfo['id']);
            $is_connected = "0";
            $is_owner = "0";
            if($user_id){
                if (( $user_id != $channelInfo['owner_id'] ) && ( in_array($user_id, $connected_tubers) )) {
                    $is_connected = "1";
                } 
                else if (( $user_id == $channelInfo['owner_id'])) {
                    $is_owner = "1";
                }
            }
            $channel_connected_tubers = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'],'n_results' => true ));
            $channel_photos = mediaSearch(array(
                'channel_id' => $channelInfo['id'],
                'type' => 'i',
                'catalog_status' => 0,
                'n_results' => true
            ));
            $channel_video = mediaSearch(array(
                'channel_id' => $channelInfo['id'],
                'type' => 'v',
                'catalog_status' => 0,
                'n_results' => true
            ));
            $optionsC = array ( 'channelid' => $channelInfo['id'] , 'user_id' => $channelInfo['owner_id'] , 'n_results' => true );
            $channel_album = userCatalogSearch( $optionsC );
            $res_item['type'] = 'channel';
            $res_item['name'] = $item['_source']['channel_name'];
            $res_item['address'] = $item['_source']['city_name'].", ".$item['_source']['state_name'].", ".$item['_source']['country_name'];
            $res_item['logo'] = $channel_logo;
            $res_item['header'] = $channel_header;
            $res_item['user_is_owner'] = $is_owner;
            $res_item['user_is_connected'] = $is_connected;
            $res_item['hidecreatedon'] = $channelInfo['hidecreatedon'];
            $res_item['created_on'] = $channel_created_date;
            $res_item['statistic'] = array(
                'connected_tubers'=>$channel_connected_tubers,
                'videos'=>$channel_video,
                'photos'=>$channel_photos,
                'albums'=>$channel_album
            );
            break;
        case 'hotel':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'hotel';
            $res_item['name'] = $item['_source']['hotelName'];
            $res_item['address'] = format_address($item); //$item['_source']['city'].", ".$item['_source']['stateName'].", ".$item['_source']['country'];
            $res_item['phone'] = $item['_source']['phone'];
            break;
        case 'restaurant':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'restaurant';
            $res_item['name'] = $item['_source']['name'];
            $res_item['address'] = format_address($item); //$item['_source']['locality'].", ".$item['_source']['region'].", ".$item['_source']['countryName'];
            $res_item['phone'] = $item['_source']['tel'];
            break;
        case 'poi':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'poi';
            $res_item['name'] =  $item['_source']['name'];
            $res_item['address'] = format_address($item); //$item['_source']['city'].", ".$item['_source']['state_name'].", ".$item['_source']['country'];
            break;
        
    }
    $final_res[] = $res_item;
}
echo json_encode($final_res);
}
catch(Exception $ex){
    echo $ex->getMessage();
    exit();
}