<?php
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all()); 
$term = $submit_post_get['term'];
if (!isset($term)){
    die('');
}

$params1 = array('hosts' => array($CONFIG [ 'elastic' ] [ 'ip' ] ));
$client = new Elasticsearch\Client($params1);
$searchParams['index'] = $CONFIG [ 'elastic' ] [ 'index' ];
$entity_type = $submit_post_get['t'];
$results = array();
$size = 10;
if($entity_type == SOCIAL_ENTITY_HOTEL){
    $searchParams['type'] = 'hotel';
    $searchParams['body'] = array (
        'from' => 0,
        'size' => $size,
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
                      'type' => 'phrase_prefix',
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
              2 => 
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
                        0 => 'city',
                        1 => 'country',
                        2 => 'state_name',
                      ),
                    ),
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
        $results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'hotel: ' . $ex->getMessage();
        echo '          '.json_encode($searchParams['body']);
        exit;
    }
}elseif($entity_type == SOCIAL_ENTITY_RESTAURANT){
    $searchParams['type'] = 'restaurant';
    $searchParams['body'] = array (
        'from' => 0,
        'size' => $size,
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
                      'type' => 'phrase_prefix',
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
              2 => 
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
                        0 => 'locality',
                        1 => 'region',
                        2 => 'admin_region',
                        3 => 'countryName',
                      ),
                    ),
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
        $results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'restaurant: ' . $ex->getMessage();
        echo '          '.json_encode($searchParams['body']);
        exit;
    }
}elseif($entity_type == SOCIAL_ENTITY_LANDMARK){
    $searchParams['type'] = 'poi';
    $searchParams['body'] = array (
        'from' => 0,
        'size' => $size,
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
                      'type' => 'phrase_prefix',
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
              2 => 
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
                        0 => 'city',
                        1 => 'country',
                        2 => 'state_name',
                      ),
                    ),
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
        $results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'poi: ' . $ex->getMessage();
        echo '          '.json_encode($searchParams['body']);
        exit;
    }
}elseif($entity_type == SOCIAL_ENTITY_AIRPORT){
    $searchParams['type'] = 'airport';
    $searchParams['body'] = array (
        'from' => 0,
        'size' => $size,
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
                      'type' => 'phrase_prefix',
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
              2 => 
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
                        0 => 'city',
                        1 => 'state_name',
                        2 => 'countryName',
                      ),
                    ),
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
        $results = $retDoc['hits']['hits'];
    } catch (Exception $ex) {
        echo 'airport: ' . $ex->getMessage();
        echo '          '.json_encode($searchParams['body']);
        exit;
    }
}
$final_res = array();
foreach($results as $item){
    $res_item = array();
    switch($entity_type){
        case SOCIAL_ENTITY_HOTEL:
            $res_item['id'] = $item['_source']['id'];
            $res_item['title'] = $item['_source']['hotelName'];
            $res_item['address'] = format_address($item);
            break;
        case SOCIAL_ENTITY_RESTAURANT:
            $res_item['id'] = $item['_source']['id'];
            $res_item['title'] = $item['_source']['name'];
            $res_item['address'] = format_address($item);
            break;
        case SOCIAL_ENTITY_LANDMARK:
            $res_item['id'] = $item['_source']['id'];
            $res_item['title'] = $item['_source']['name'];
            $res_item['address'] = format_address($item);
            break;
        case SOCIAL_ENTITY_AIRPORT:
            $res_item['id'] = $item['_source']['id'];
            $res_item['title'] = $item['_source']['name'];
            $res_item['address'] = format_address($item);
            break;
    }
    $final_res[] = $res_item;
}
echo json_encode($final_res);