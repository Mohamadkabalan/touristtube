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

function Convert_Categories($mongodb){
    $categories_structure = Describe("DESCRIBE discover_categs");
    $categories_sql_result = mysql_query("SELECT * FROM discover_categs") or die( mysql_error());
    $cat_count = mysql_num_rows($categories_sql_result);
    $categories_collection = $mongodb->discover_categories;
    if($cat_count > 0){
        while($data = mysql_fetch_array($categories_sql_result)){
            $datasize = count($data);
            $mongo_sql = array();
            for( $i=0; $i < $datasize; $i++ ) {
                if(!empty($categories_structure[$i]))
                    $mongo_sql[$categories_structure[$i]] = check_character_code($data[$i]);
            }
            $categories_collection->insert($mongo_sql);
        }
    }
}

function Poi_Categories($poi_id, $mongodb){
    $categories_structure = Describe("DESCRIBE discover_poi_categ");
    $categories = Fetch_Data("SELECT * FROM discover_poi_categ WHERE poi_id = $poi_id", $categories_structure);

    $poi_categories = array();
    foreach($categories as $category){
        $mongo_category = $mongodb->discover_categories->findOne(array("id" => $category['categ_id']));
        $category_ref = MongoDBRef::create("discover_categories", $mongo_category['_id']);
        $poi_categories[] = $category_ref;
    }
    return $poi_categories;
}

try {
$my_mongo_connect = new MongoClient();
$mongodb = $my_mongo_connect->selectDB($mongodb);
}

catch(MongoConnectionException $e) {
die($e."error during MongoDB initialization. Please restart MongoDB server.");

}

Convert_Categories($mongodb);

$keyz = mysql_query("DESCRIBE discover_poi") or die( mysql_error() );
while($data = mysql_fetch_array($keyz)) $structure[]=$data['Field'];
//print_r($structure);

$sql_results = mysql_query("SELECT p.*, c.id as cityId, c.name as cityName FROM discover_poi p LEFT JOIN webgeocities c ON p.city_id = c.id") or die( mysql_error());
$count = mysql_num_rows($sql_results);

$mycollection = $mongodb->discover_poi;
$mycollection->ensureIndex(array("loc" => "2d"));

if($count > 0) {

    while($data = mysql_fetch_array($sql_results)) {
        $datasize = count($data);
        $mongo_sql = array();
        $mongo_sql['loc'] = array('lon' => 0, 'lat' => 0);
        for( $i=0; $i < $datasize; $i++ ) {
            if(!empty($structure[$i]))
                if($structure[$i] == 'zoom_order' || $structure[$i] == 'price' || $structure[$i] == 'show_on_map'){
                    $mongo_sql[$structure[$i]] = intval($data[$i]);
                }
                else if($structure[$i] == 'latitude')
                    $mongo_sql['loc']['lat'] = floatval($data[$i]);
                else if($structure[$i] == 'longitude')
                    $mongo_sql['loc']['lon'] = floatval($data[$i]);
                else if( $structure[$i] == 'stars')
                    $mongo_sql[$structure[$i]] = floatval($data[$i]);
                else
                    $mongo_sql[$structure[$i]] = check_character_code($data[$i]);
        }
        $poi_id = $data['id'];
        $reviews_structure = Describe("DESCRIBE discover_poi_reviews");
        $mongo_sql['reviews'] = Fetch_Data("SELECT * FROM discover_poi_reviews WHERE poi_id = $poi_id", $reviews_structure, TRUE);
        $images_structure = Describe("DESCRIBE discover_poi_images");
        $mongo_sql['images'] = Fetch_Data("SELECT * FROM discover_poi_images WHERE poi_id = $poi_id", $images_structure, TRUE);
        $mongo_sql['discover_categories'] = Poi_Categories($poi_id, $mongodb);
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
$mycollection->ensureIndex(array("discover_categories" => 1));
$mycollection->ensureIndex(array("webgeocity.name" => 1));
//$mycollection->ensureIndex(array("loc" => "2d"));
//print_r($mongodb->discover_hotels->findOne());

echo "MongoDB database conversion completed.";


