<?php
$database_name = "touristtube";
//mysql_connect('localhost','tourist','touristMysqlP@ssw0rd');
mysql_connect('localhost', 'root', 'mysql_root');
mysql_select_db($database_name);

//MongoDB settings
$mongodb = "ttmongo";

function check_character_code($string) {
if( !mb_check_encoding($string,'UTF-8')) {
return mb_convert_encoding($string,'UTF-8','ISO-8859-1');
}else {return $string;}
}

function Describe($query){
    $structure = array();
    $keyz = mysql_query($query) or die( mysql_error() );
    while($data = mysql_fetch_array($keyz)) $structure[] = $data['Field'];
    return $structure;
}
function Fetch_Data($query, $structure, $use_uuid = FALSE){

    $sql_results = mysql_query($query) or die( mysql_error());
    $count = mysql_num_rows($sql_results);
    $array = array();
    if($count > 0) {
        while($data = mysql_fetch_array($sql_results)) {
            $datasize = count($data);
            $mongo_sql = array();
            for( $i=0; $i < $datasize; $i++ ) {
                if(!empty($structure[$i]))
                    $mongo_sql[$structure[$i]] = check_character_code($data[$i]);
            }
            if($use_uuid)
                $mongo_sql['id'] = uniqid();
//            print_r($mongo_sql);
            $array[] = $mongo_sql;
        }
    }
    return $array;
}

function Convert_Facilities($mongodb){
    $facilities_structure = Describe("DESCRIBE discover_facilities");
    $facilities_sql_result = mysql_query("SELECT * FROM discover_facilities") or die( mysql_error());
    $fac_count = mysql_num_rows($facilities_sql_result);
    $facilities_collection = $mongodb->discover_facilities;
    if($fac_count > 0){
        while($data = mysql_fetch_array($facilities_sql_result)){
            $datasize = count($data);
            $mongo_sql = array();
            for( $i=0; $i < $datasize; $i++ ) {
                if(!empty($facilities_structure[$i]))
                    $mongo_sql[$facilities_structure[$i]] = check_character_code($data[$i]);
            }
            $facilities_collection->insert($mongo_sql);
        }
    }
}

function Hotel_Facilities($hotel_id, $mongodb){
    $facilities_structure = Describe("DESCRIBE discover_hotels_facilities");
    $facilities = Fetch_Data("SELECT * FROM discover_hotels_facilities WHERE hotel_id = $hotel_id", $facilities_structure);
//    print_r($facilities);
    $hotel_facilities = array();
    foreach($facilities as $facility){
        $mongo_facility = $mongodb->discover_facilities->findOne(array("id" => $facility['facility_id']));
        $facility_ref = MongoDBRef::create("discover_facilities", $mongo_facility['_id']);
        $hotel_facilities[] = $facility_ref;
    }
    return $hotel_facilities;
}

try {
$my_mongo_connect = new MongoClient();
$mongodb = $my_mongo_connect->selectDB($mongodb);
}

catch(MongoConnectionException $e) {
die($e."error during MongoDB initialization. Please restart MongoDB server.");

}

Convert_Facilities($mongodb);

$keyz = mysql_query("DESCRIBE discover_hotels") or die( mysql_error() );
while($data = mysql_fetch_array($keyz)) $structure[]=$data['Field'];
//print_r($structure);

$sql_results = mysql_query("SELECT h.*, c.id as cityId, c.name as cityName FROM discover_hotels h LEFT JOIN webgeocities c ON h.city_id = c.id") or die( mysql_error());
$count = mysql_num_rows($sql_results);

$mycollection = $mongodb->discover_hotels;
$mycollection->ensureIndex(array("loc" => "2d"));

if($count > 0) {
$total = 0;
    while($data = mysql_fetch_array($sql_results)) {
//        if($total == 10)
//            break;
        try{
            $datasize = count($data);
            $mongo_sql = array();
            $mongo_sql['loc'] = array('lon' => 0, 'lat' => 0);
            for( $i=0; $i < $datasize; $i++ ) {
                if(!empty($structure[$i]))
                    if($structure[$i] == 'stars' || $structure[$i] == 'price' || $structure[$i] == 'latlong' || $structure[$i] == 'zoom_order'){
                        $mongo_sql[$structure[$i]] = intval($data[$i]);
                    }
                    else if($structure[$i] == 'latitude')
                        $mongo_sql['loc']['lat'] = floatval($data[$i]);
                    else if($structure[$i] == 'longitude')
                        $mongo_sql['loc']['lon'] = floatval($data[$i]);
                    else if($structure[$i] == 'last_modified')
                        $mongo_sql[$structure[$i]] = strtotime($data[$i]);
                    else
                        $mongo_sql[$structure[$i]] = check_character_code($data[$i]);
            }
            $hotel_id = $data['id'];
            $rooms_structure = Describe("DESCRIBE discover_hotels_rooms");
            $mongo_sql['rooms'] = Fetch_Data("SELECT * FROM discover_hotels_rooms WHERE hotel_id = $hotel_id", $rooms_structure, TRUE);
            $reviews_structure = Describe("DESCRIBE discover_hotels_reviews");
            $mongo_sql['reviews'] = Fetch_Data("SELECT * FROM discover_hotels_reviews WHERE hotel_id = $hotel_id", $reviews_structure, TRUE);
            $images_structure = Describe("DESCRIBE discover_hotels_images");
            $mongo_sql['images'] = Fetch_Data("SELECT * FROM discover_hotels_images WHERE hotel_id = $hotel_id", $images_structure, TRUE);
            $mongo_sql['discover_facilities'] = Hotel_Facilities($hotel_id, $mongodb);
            if($data['cityId']){
                $mongo_sql['webgeocity'] = array(
                    'id' => $data['cityId'],
                    'name' => check_character_code($data['cityName'])
                );
            }
            else{
                $mongo_sql['webgeocity'] = array();
            }
    //        print_r($mongo_sql);
            $mycollection->insert($mongo_sql);
        }
        catch(Exception $ex){
            
        }
        $total++;
    }
}
$mycollection->ensureIndex(array("discover_facilities" => 1));
$mycollection->ensureIndex(array("webgeocity.name" => 1));
//$mycollection->ensureIndex(array("loc" => "2d"));
//print_r($mongodb->discover_hotels->findOne());

echo "MongoDB database conversion completed.";


