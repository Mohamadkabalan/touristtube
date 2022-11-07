<?php
//ini_set('display_errors', 1);
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$latitude = floatval($submit_post_get['latitude']);
$longitude = floatval($submit_post_get['longitude']);
$distance = $submit_post_get['distance'] ? intval($submit_post_get['distance']).'m' : '600m';
$limit = $submit_post_get['limit'] ? intval($submit_post_get['limit']) : 200;
$restaurants = 0;//isset($submit_post_get['restaurants']) ? intval($submit_post_get['restaurants']) : 1;
$hotels = isset($submit_post_get['hotels']) ? intval($submit_post_get['hotels']) : 1;
$pois = isset($submit_post_get['pois']) ? intval($submit_post_get['pois']) : 1;
if($limit > 300){
    $limit = 300;
}
$limit_divider = 0;
if($restaurants == 1){
    $limit_divider++;
}
if($hotels == 1){
    $limit_divider++;
}
if($pois == 1){
    $limit_divider++;
}
if($limit_divider == 0){
    $limit = 0;
    $limit_divider = 1;
}
else{
    $limit = intval($limit / $limit_divider);
}
try{
    $params1 = array('hosts' => array($CONFIG [ 'elastic' ] [ 'ip' ] ));
    $client = new Elasticsearch\Client($params1);
}
catch(Exception $ex){
    echo $ex->getMessage();
    exit();
}
$searchParams['index'] = $CONFIG [ 'elastic' ] [ 'index' ];
//$searchParams['index'] = "tt50";

$restaurants_arr = array();
$hotels_arr = array();
$pois_arr = array();
$remaining = 0;

if($hotels == 1){
    $searchParams['type'] = 'hotel';
    $searchParams['body'] = array (
       'sort' => 
        array (
          array (
            '_geo_distance' => 
            array (
              'geolocation' =>
              array (
                'lat' => $latitude,
                'lon' => $longitude,
              ),
              'order' => 'asc',
              'unit' => 'm',
              'distance_type' => 'arc',
            ),
          ),
        ),
      'size' => $limit,
      'fields' => 
      array (
        0 => '_source',
      ),
//      'script_fields' => 
//      array (
//        'distance' => 
//        array (
//          'params' => 
//          array (
//            'lat' => $latitude,
//            'lon' => $longitude,
//          ),
//          'script' => 'doc[\'geolocation\'].arcDistance(lat,lon)',
//        ),
//      ),
      'query' => 
      array (
        'filtered' => 
        array (
          'filter' => 
          array (
            'bool' => 
            array (
              'must' => 
              array (
                0 => 
                array (
                  'geo_distance' => 
                  array (
                    'distance_type' => 'arc',
                    'distance' => $distance,
                    'geolocation' => 
                    array (
                      'lat' => $latitude,
                      'lon' => $longitude,
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
    $retDoc = $client->search($searchParams);
    $results = $retDoc['hits']['hits'];
    $hotels_total = $retDoc['hits']['total'];
    if($hotels_total < $limit){
        $remaining = ($limit - $hotels_total) / 2;
        $limit += $remaining;
    }
	
	if ($results)
		foreach($results as $item){
			$image = "";
			if(is_array($item['_source']['images']) && count($item['_source']['images']) > 0){
				$image = 'media/discover/'.$item['_source']['images'][0]['filename'];
			}
			$hotels_arr[] = array(
				"id" => $item["_source"]["id"],
				"name" => $item["_source"]["hotelName"],
				"type" => "hotel",
				"latitude" => $item["_source"]["latitude"],
				"longitude" => $item["_source"]["longitude"],
				"zipcode" => isset($item["_source"]["zipcode"]) ? $item["_source"]["zipcode"] : "",
				"address" => format_address($item),
				//"distance" => $item["fields"]["distance"][0],
				"phone" => $item['_source']['phone'],
	//            "images" => $item['_source']['images'],
				"image" => $image
			);
		}
}


if($pois == 1){
    $searchParams['type'] = 'poi';
    $searchParams['body'] = array (
       'sort' => 
        array (
          array (
            '_geo_distance' => 
            array (
              'geolocation' =>
              array (
                'lat' => $latitude,
                'lon' => $longitude,
              ),
              'order' => 'asc',
              'unit' => 'm',
              'distance_type' => 'arc',
            ),
          ),
        ),
      'size' => $limit,
      'fields' => 
      array (
        0 => '_source',
      ),
//      'script_fields' => 
//      array (
//        'distance' => 
//        array (
//          'params' => 
//          array (
//            'lat' => $latitude,
//            'lon' => $longitude,
//          ),
//          'script' => 'doc[\'geolocation\'].arcDistance(lat,lon)',
//        ),
//      ),
      'query' => 
      array (
        'filtered' => 
        array (
          'filter' => 
          array (
            'bool' => 
            array (
              'must' => 
              array (
                0 => 
                array (
                  'geo_distance' => 
                  array (
                    'distance_type' => 'arc',
                    'distance' => $distance,
                    'geolocation' => 
                    array (
                      'lat' => $latitude,
                      'lon' => $longitude,
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
    $retDoc = $client->search($searchParams);
    $results = $retDoc['hits']['hits'];
    $pois_total = $retDoc['hits']['total'];
    if($pois_total < $limit){
        $remaining = $limit - $pois_total;
        $limit += $remaining;
    }
	
	if ($results)
		foreach($results as $item){
			$image = "";
			if($item['_source']['images']){
				$image_to_use = $item['_source']['images'][0];
				if($image_to_use['extra'] == 1){
					$image = "media/thingstodo/".$image_to_use['filename'];
				}
				else{
					$image = 'media/discover/'.$image_to_use['filename'];
				}
			}
			$pois_arr[] = array(
				"id" => $item["_source"]["id"],
				"name" => $item["_source"]["name"],
				"type" => "poi",
				"latitude" => $item["_source"]["latitude"],
				"longitude" => $item["_source"]["longitude"],
	//            "address" => $item["_source"]["city"].", ".$item["_source"]["state_name"].", ".$item["_source"]["country_name"],
				"zipcode" => isset($item["_source"]["zipcode"]) ? $item["_source"]["zipcode"] : "",
				"address" => format_address($item),
				"distance" => (isset($item["fields"]["distance"])?$item["fields"]["distance"][0]:0),
	//            "images" => $item['_source']['images'],
				"image" => $image
			);
		}
}

if($restaurants == 1){
    $searchParams['type'] = 'restaurant';
    $searchParams['body'] = array (
       'sort' => 
        array (
          array (
            '_geo_distance' => 
            array (
              'geolocation' =>
              array (
                'lat' => $latitude,
                'lon' => $longitude,
              ),
              'order' => 'asc',
              'unit' => 'm',
              'distance_type' => 'arc',
            ),
          ),
        ),
      'size' => $limit,
      'fields' => 
      array (
        0 => '_source',
      ),
//      'script_fields' => 
//      array (
//        'distance' => 
//        array (
//          'params' => 
//          array (
//            'lat' => $latitude,
//            'lon' => $longitude,
//          ),
//          'script' => 'doc[\'geolocation\'].arcDistance(lat,lon)',
//        ),
//      ),
      'query' => 
      array (
        'filtered' => 
        array (
          'filter' => 
          array (
            'bool' => 
            array (
              'must' => 
              array (
                0 => 
                array (
                  'geo_distance' => 
                  array (
                    'distance_type' => 'arc',
                    'distance' => $distance,
                    'geolocation' => 
                    array (
                      'lat' => $latitude,
                      'lon' => $longitude,
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
    $retDoc = $client->search($searchParams);
    $results = $retDoc['hits']['hits'];
	
	if ($results)
		foreach($results as $item){
			$image = "";
			if($item['_source']['images']){
				$image = 'media/discover/'.$item['_source']['images'][0]['filename'];
			}
			$restaurants_arr[] = array(
				"id" => $item["_source"]["id"],
				"name" => $item["_source"]["name"],
				"type" => "restaurant",
				"latitude" => $item["_source"]["latitude"],
				"longitude" => $item["_source"]["longitude"],
	//            "address" => $item["_source"]["locality"].", ".$item["_source"]["region"].", ".$item["_source"]["countryName"],
				"zipcode" => isset($item["_source"]["zipcode"]) ? $item["_source"]["zipcode"] : "",
				"address" => format_address($item),
				"distance" => (isset($item["fields"]["distance"])?$item["fields"]["distance"][0]:0),
				"phone" => $item['_source']['tel'],
	//            "images" => $item['_source']['images'],
				"image" => $image
			);
		}
}



//echo json_encode($searchParams['body']);exit;


echo json_encode(array(
    'restaurants' => $restaurants_arr,
    'hotels' => $hotels_arr,
    'pois' => $pois_arr
));