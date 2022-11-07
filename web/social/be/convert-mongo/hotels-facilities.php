<pre><?php
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
$database_name = "touristtube";
mysql_connect('localhost','tourist','touristMysqlP@ssw0rd');
mysql_select_db($database_name);

//MongoDB settings
$mongodb = "tt";




try {
$my_mongo_connect = new Mongo();
$mongodb = $my_mongo_connect->selectDB($mongodb);
}

catch(MongoConnectionException $e) {
die($e."error during MongoDB initialization. Please restart MongoDB server.");

}

$hotels = $mongodb->discover_hotels->find();

foreach($hotels as $hotel){

    if($hotel['facilities']<>''){
        $hotel['facilities1']=array();
        $f=  explode('|', $hotel['facilities']);
        foreach($f as $ff) if($ff <>'') array_push( $hotel['facilities1'], array( 'facility_id'=> $ff));
        $mongodb->discover_hotels->update(array('id' => $hotel['id']), $hotel);
    }
    
}

/*
 while($data = mysql_fetch_array($sql_results)) {

    
	$room= array('title'=> $data['title'],'description'=> $data['description'],
	'num_person'=> $data['num_person'], 'price'=> $data['price'], 'pic1'=> $data['pic1'], 'pic2'=> $data['pic2'],'pic3'=> $data['pic3']  );

    if(!isset($hotel['room_details'])) $hotel['room_details']= array();
    foreach($room as $k=>$v) $clean_room[$k]=check_character_code($v);
        array_push( $hotel['room_details'], $clean_room);
   print_r($hotel);
   $mongodb->discover_hotels->update(array('id' => $hotel['id']), $hotel);

} */

echo "MongoDB database conversion completed.";


