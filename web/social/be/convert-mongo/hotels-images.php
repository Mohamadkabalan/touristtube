<pre><?php
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
$database_name = "touristtube";
mysql_connect('localhost','tourist','touristMysqlP@ssw0rd');
mysql_select_db($database_name);

//MongoDB settings
$mongodb = "tt";






function check_character_code($string) {
if( !mb_check_encoding($string,'UTF-8')) {
return mb_convert_encoding($string,'UTF-8','ISO-8859-1');
}else {return $string;}
}

try {
$my_mongo_connect = new Mongo();
$mongodb = $my_mongo_connect->selectDB($mongodb);
}

catch(MongoConnectionException $e) {
die($e."error during MongoDB initialization. Please restart MongoDB server.");

}

//$keyz = mysql_query("DESCRIBE discover_hotels_images") or die( mysql_error() );
//while($data = mysql_fetch_array($keyz)) $structure[]=$data['Field'];

$sql_results = mysql_query("SELECT * FROM discover_hotels_images") or die( mysql_error() );

while($data = mysql_fetch_array($sql_results)) {
    $hotel = $mongodb->discover_hotels->findOne(array("id" => $data['hotel_id']));
    $img= array('filename'=> $data['filename']);

    if(!isset($hotel['images'])) $hotel['images']= array();
        array_push( $hotel['images'], $img);
   print_r($hotel['images']);
   $mongodb->discover_hotels->update(array('id' => $hotel['id']), $hotel);

}

echo "MongoDB database conversion completed.";


