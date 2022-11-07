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

function Convert_Cuisines($mongodb){
    $cuisines_structure = Describe("DESCRIBE discover_cuisine");
    $cuisines_sql_result = mysql_query("SELECT * FROM discover_cuisine") or die( mysql_error());
    $cat_count = mysql_num_rows($cuisines_sql_result);
    $cuisines_collection = $mongodb->discover_cuisine;
    if($cat_count > 0){
        while($data = mysql_fetch_array($cuisines_sql_result)){
            $datasize = count($data);
            $mongo_sql = array();
            for( $i=0; $i < $datasize; $i++ ) {
                if(!empty($cuisines_structure[$i]))
                    $mongo_sql[$cuisines_structure[$i]] = check_character_code($data[$i]);
            }
            $cuisines_collection->insert($mongo_sql);
        }
    }
}

function Restaurant_Cuisines($rest_id, $mongodb){
    $cuisines_structure = Describe("DESCRIBE discover_restaurants_cuisine");
    $cuisines = Fetch_Data("SELECT * FROM discover_restaurants_cuisine WHERE restaurant_id = $rest_id", $cuisines_structure);

    $restaurant_cuisines = array();
    foreach($cuisines as $cuisine){
        $mongo_cuisine = $mongodb->discover_cuisine->findOne(array("id" => $cuisine['cuisine_id']));
        $cuisine_ref = MongoDBRef::create("discover_cuisine", $mongo_cuisine['_id']);
        $restaurant_cuisines[] = $cuisine_ref;
    }
    return $restaurant_cuisines;
}

try {
$my_mongo_connect = new MongoClient();
$mongodb = $my_mongo_connect->selectDB($mongodb);
}

catch(MongoConnectionException $e) {
die($e."error during MongoDB initialization. Please restart MongoDB server.");

}

Convert_Cuisines($mongodb);

$keyz = mysql_query("DESCRIBE discover_restaurants") or die( mysql_error() );
while($data = mysql_fetch_array($keyz)) $structure[]=$data['Field'];
//print_r($structure);

$sql_results = mysql_query("SELECT r.*, c.id as cityId, c.name as cityName  FROM discover_restaurants r LEFT JOIN webgeocities c ON r.city_id = c.id") or die( mysql_error());
$count = mysql_num_rows($sql_results);

$mycollection = $mongodb->discover_restaurants;
$mycollection->ensureIndex(array("loc" => "2d"));

if($count > 0) {

    while($data = mysql_fetch_array($sql_results)) {
        $datasize = count($data);
        $mongo_sql = array();
        $mongo_sql['loc'] = array('lon' => 0, 'lat' => 0);
        for( $i=0; $i < $datasize; $i++ ) {
            if(!empty($structure[$i]))
                if($structure[$i] == 'id' || $structure[$i] == 'zoom_order'){
                    $mongo_sql[$structure[$i]] = intval($data[$i]);
                }
                else if($structure[$i] == 'latitude')
                    $mongo_sql['loc']['lat'] = floatval($data[$i]);
                else if($structure[$i] == 'longitude')
                    $mongo_sql['loc']['lon'] = floatval($data[$i]);
                else if($structure[$i] == 'stars')
                    $mongo_sql[$structure[$i]] = floatval($data[$i]);
                else
                    $mongo_sql[$structure[$i]] = check_character_code($data[$i]);
        }
        $restaurant_id = $data['id'];
        $reviews_structure = Describe("DESCRIBE discover_restaurants_reviews");
        $mongo_sql['reviews'] = Fetch_Data("SELECT * FROM discover_restaurants_reviews WHERE restaurant_id = $restaurant_id", $reviews_structure, TRUE);
        $images_structure = Describe("DESCRIBE discover_restaurants_images");
        $mongo_sql['images'] = Fetch_Data("SELECT * FROM discover_restaurants_images WHERE restaurant_id = $restaurant_id", $images_structure, TRUE);
        $mongo_sql['discover_cuisine'] = Restaurant_Cuisines($restaurant_id, $mongodb);
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
}
$mycollection->ensureIndex(array("discover_cuisine" => 1));
$mycollection->ensureIndex(array("webgeocity.name" => 1));
//print_r($mongodb->discover_hotels->findOne());

echo "MongoDB database conversion completed.";


