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

function stripAccents($stripAccents){
    return iconv('UTF-8', 'ASCII//TRANSLIT', check_character_code($stripAccents));
  return strtr($stripAccents,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

try {
$my_mongo_connect = new Mongo();
$mongodb = $my_mongo_connect->selectDB($mongodb);
}

catch(MongoConnectionException $e) {
die($e."error during MongoDB initialization. Please restart MongoDB server.");

}

$keyz = mysql_query("DESCRIBE discover_restaurants") or die( mysql_error() );
while($data = mysql_fetch_array($keyz)) $structure[]=$data['Field'];
//print_r($structure);

$sql_results = mysql_query("SELECT * FROM discover_restaurants LIMIT 100") or die( mysql_error());
$count = mysql_num_rows($sql_results);

$mycollection = $mongodb->discover_restaurants_test;

if($count > 0) {

    while($data = mysql_fetch_array($sql_results)) {
        $datasize = count($data);
        $mongo_sql = array();
        for( $i=0; $i < $datasize; $i++ ) {
            if(!empty($structure[$i])){
                $mongo_sql[$structure[$i]] = check_character_code($data[$i]);
                if($structure[$i] == "name"){
                    print_r(stripAccents(check_character_code($data[$i])));
                    $mongo_sql["sa_name"] = stripAccents($data[$i]);
                }
            }
        }
        $restaurant_id = $data['id'];
        $reviews_structure = Describe("DESCRIBE discover_restaurants_reviews");
        $mongo_sql['reviews'] = Fetch_Data("SELECT * FROM discover_restaurants_reviews WHERE restaurant_id = $restaurant_id", $reviews_structure, TRUE);
        $images_structure = Describe("DESCRIBE discover_restaurants_images");
        $mongo_sql['images'] = Fetch_Data("SELECT * FROM discover_restaurants_images WHERE restaurant_id = $restaurant_id", $images_structure, TRUE);
//        print_r($mongo_sql);
        $mycollection->insert($mongo_sql);
    }
}
//print_r($mongodb->discover_hotels->findOne());

echo "MongoDB database conversion completed.";


