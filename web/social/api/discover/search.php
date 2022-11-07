<?php

$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all()); 

$term = $submit_post_get['term'];
$type = isset($submit_post_get['type']) ? $submit_post_get['type'] :  null;
if(trim($type) == ''){
    $type = null;
}
$page = isset($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;
$limit = 2;
if(isset($type)){
    $limit = 10;
}
else{
    $page = 0;
}
$from = $page * $limit;

$params1 = array('hosts' => array($CONFIG [ 'elastic' ] [ 'ip' ] ));
$client = new Elasticsearch\Client($params1);

$searchParams['index'] = $CONFIG [ 'elastic' ] [ 'index' ];

$locality_results = array();
if(!isset($type) && $page == 0){
    $searchParams['type'] = 'locality';
    $searchParams['body'] = array (
      'size' => 2,
      'query' => 
      array (
        'bool' => 
        array (
          'should' => 
          array (
            0 => 
            array (
              'function_score' => 
              array (
                'query' => 
                array (
                  'filtered' => 
                  array (
                    'query' => 
                    array (
                      'multi_match' => 
                      array (
                        'query' => $term,
                        'type' => 'phrase',
                        'analyzer' => 'standard',
                        'fields' => 
                        array (
                          0 => 'countryName',
                          1 => 'countryThreeCode^3',
                          2 => 'countryFullname',
                          3 => 'countryISO3Code',
                        ),
                      ),
                    ),
                    'filter' => 
                    array (
                      'term' => 
                      array (
                        'type' => 3,
                      ),
                    ),
                  ),
                ),
                'field_value_factor' => 
                array (
                  'field' => 'popularity',
                  'modifier' => 'square',
                ),
                'boost' => 3,
              ),
            ),
            1 => 
            array (
              'function_score' => 
              array (
                'query' => 
                array (
                  'filtered' => 
                  array (
                    'query' => 
                    array (
                      'multi_match' => 
                      array (
                        'query' => $term,
                        'type' => 'phrase',
                        'analyzer' => 'standard',
                        'fields' => 
                        array (
                          0 => 'name',
                        ),
                      ),
                    ),
                    'filter' => 
                    array (
                      'term' => 
                      array (
                        'type' => 1,
                      ),
                    ),
                  ),
                ),
                'field_value_factor' => 
                array (
                  'field' => 'popularity',
                  'modifier' => 'square',
                ),
                'boost' => 1,
              ),
            ),
            2 => 
            array (
              'function_score' => 
              array (
                'query' => 
                array (
                  'filtered' => 
                  array (
                    'query' => 
                    array (
                      'multi_match' => 
                      array (
                        'query' => $term,
                        'type' => 'phrase',
                        'analyzer' => 'standard',
                        'fields' => 
                        array (
                          0 => 'stateName',
                        ),
                      ),
                    ),
                    'filter' => 
                    array (
                      'term' => 
                      array (
                        'type' => 2,
                      ),
                    ),
                  ),
                ),
                'field_value_factor' => 
                array (
                  'field' => 'popularity',
                  'modifier' => 'square',
                ),
                'boost' => 1,
              ),
            ),
          ),
        ),
      ),
    );
    try{
        $retDoc = $client->search($searchParams);
        $locality_results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'locality: ' . $ex->getMessage();
        echo '          '.json_encode($searchParams['body']);
        exit;
    }
}
$ttd_results = array();
if(!isset($type) || $type == "ttd"){
    $searchParams['body'] = array (
      'from' => $from,
      'size' => $limit,
      'query' => 
      array (
        'multi_match' => 
        array (
          'query' => $term,
          'type' => 'phrase_prefix',
          'analyzer' => 'standard',
          'fields' => 
          array (
            0 => 'title',
          ),
        ),
      ),
    );
    $searchParams['type'] = 'ttd';
//    $searchParams['type'] = 'thingsToDoPages';//comment before uploading online
    try{
        $retDoc = $client->search($searchParams);
        $ttd_results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'ttd: ' . $ex->getMessage();
        echo '<br><br><br>'.json_encode($searchParams['body']);
        exit;
    }
}
$channel_results = array();
if(!isset($type) || $type == "channel"){
    $searchParams['body'] = array (
      'from' => $from,
      'size' => $limit,
      'query' => 
      array (
        'function_score' => 
        array (
          'query' => 
          array (
            'multi_match' => 
            array (
              'query' => $term,
              'type' => 'phrase_prefix',
              'analyzer' => 'standard',
              'fields' => 
              array (
                0 => 'channel_name',
              ),
            ),
          ),
          'functions' => 
          array (
            0 => 
            array (
              'field_value_factor' => 
              array (
                'field' => 'popularity',
                'modifier' => 'square',
              ),
            ),
            1 => 
            array (
              'field_value_factor' => 
              array (
                'field' => 'country_popularity',
                'modifier' => 'square',
              ),
            ),
          ),
        ),
      ),
    );
    $searchParams['type'] = 'channels';
//    $searchParams['type'] = 'channels';//comment before uploading online
    try{
        $retDoc = $client->search($searchParams);
        $channel_results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'channel: ' . $ex->getMessage();
        echo '<br><br><br>'.json_encode($searchParams['body']);
        exit;
    }
}

$hotel_results = array();
if(!isset($type) || $type == "hotel"){
    $searchParams['body'] = 
    array (
        'from' => $from,
        'size' => $limit,
        'query' => 
        array (
          'bool' => 
          array (
            'should' => 
            array (
              0 => 
              array (
                'function_score' => 
                array (
                  'query' => 
                  array (
                    'multi_match' => 
                    array (
                      'query' => $term,
                      'type' => 'phrase',
                      'analyzer' => 'standard',
                      'fields' => 
                      array (
                        0 => 'hotelName^10',
                      ),
                    ),
                  ),
                  'boost' => 10,
                ),
              ),
              1 => 
              array (
                'function_score' => 
                array (
                  'query' => 
                  array (
                    'query_string' => 
                    array (
                      'default_field' => 'titleLocation',
                      'query' => $term,
                    ),
                  ),
                  'boost' => 5,
                ),
              ),
            ),
          ),
        ),
      );
    try{
        $searchParams['type'] = 'hotel';
        $retDoc = $client->search($searchParams);
        $hotel_results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'hotel: ' . $ex->getMessage();
        echo '<br><br><br>'.json_encode($searchParams['body']);
        exit;
    }
}
$searchParams['body'] = 
    array (
  'from' => $from,
  'size' => $limit,
  'query' => 
  array (
    'bool' => 
    array (
      'should' => 
      array (
        0 => 
        array (
          'function_score' => 
          array (
            'query' => 
            array (
              'multi_match' => 
              array (
                'query' => $term,
                'type' => 'phrase',
                'analyzer' => 'standard',
                'fields' => 
                array (
                  0 => 'name^10',
                ),
              ),
            ),
            'boost' => 10,
          ),
        ),
        1 => 
        array (
          'function_score' => 
          array (
            'query' => 
            array (
              'query_string' => 
              array (
                'default_field' => 'titleLocation',
                'query' => $term,
              ),
            ),
            'boost' => 5,
          ),
        ),
      ),
    ),
  ),
);

$restaurant_results = array();
//if(!isset($type) || $type == "restaurant"){
//    try{
//        $searchParams['type'] = 'restaurant';
//        $retDoc = $client->search($searchParams);
//        $restaurant_results = $retDoc['hits']['hits'];
//    } catch (Exception $ex) {
//        echo 'restaurant: ' . $ex->getMessage();
//        echo '<br><br><br>'.json_encode($searchParams['body']);
//        exit;
//    }
//}

$poi_results = array();
if(!isset($type) || $type == "poi"){
    try{
        $searchParams['type'] = 'poi';
        $retDoc = $client->search($searchParams);
        $poi_results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'poi: ' . $ex->getMessage();
        echo '<br><br><br>'.json_encode($searchParams['body']);
        exit;
    }
}

$all_result = array_merge($locality_results,$ttd_results,$poi_results,$hotel_results,$restaurant_results,$channel_results);
            
$sorted_items = array();
foreach ($all_result as $key => $row) {
    $sorted_items[$key]  = $row['_score'];
}
array_multisort($sorted_items, SORT_DESC, $all_result, SORT_DESC);
$final_res = array();
foreach($all_result as $item){
    $res_item = array();
    switch($item['_type']){
        case 'locality':
            
            switch($item['_source']['type']){
                case 1:
                    $res_item['id'] = $item['_source']['id'];
                    $res_item['name'] = $item['_source']['name'];
                    $res_item['type'] = 'city';
                    $res_item['address'] = $item['_source']['state'][0][0]['state_name'].", ".$item['_source']['state'][0][0]['name'];
                    break;
                case 2:
                    $res_item['id'] = $item['_id'];
                    $res_item['name'] = $item['_source']['stateName'];
                    $res_item['state_code'] = $item['_source']['state_code'];
                    $res_item['country_code'] = $item['_source']['country_code'];
                    $res_item['type'] = 'state';
                    $res_item['address'] = $item['_source']['country_name'];
                    break;
                case 3:
                    $res_item['id'] = $item['_id'];
                    $res_item['name'] = $item['_source']['countryName'];
                    $res_item['country_code'] = $item['_source']['countryCode'];
                    $res_item['type'] = 'country';
                    $res_item['address'] = '';
                    break;
            }
            
            break;
        case 'channels':
        case 'channel':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'channel';
            $res_item['name'] = $item['_source']['channel_name'];
            $res_item['address'] = $item['_source']['city_name'].", ".$item['_source']['state_name'].", ".$item['_source']['country_name'];
            break;
        case 'hotel':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'hotel';
            $res_item['name'] = $item['_source']['hotelName'];
            $res_item['address'] = format_address($item);// $item['_source']['city'].", ".$item['_source']['stateName'].", ".$item['_source']['country'];
            $res_item['phone'] = $item['_source']['phone'];
            break;
        case 'restaurant':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'restaurant';
            $res_item['name'] = $item['_source']['name'];
            $res_item['address'] = format_address($item);// $item['_source']['locality'].", ".$item['_source']['region'].", ".$item['_source']['countryName'];
            $res_item['phone'] = $item['_source']['tel'];
            break;
        case 'poi':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'poi';
            $res_item['name'] = $item['_source']['name'];
            $res_item['address'] = format_address($item);// $item['_source']['city'].", ".$item['_source']['state_name'].", ".$item['_source']['country'];
            break;
        case 'thingsToDoPages':
        case 'ttd':
            $res_item['id'] = $item['_source']['id'];
            $res_item['type'] = 'ttd';
            $res_item['name'] = $item['_source']['title'];
            $res_item['address'] = '';
            break;
        
    }
    $final_res[] = $res_item;
}

echo json_encode($final_res);
//echo json_encode($all_result);
