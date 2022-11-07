<?php
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

  

$sql_results = mysql_query("SELECT * FROM discover_facilities ") or die( mysql_error());

$mycollection = $mongodb->discover_facilities;

while($data = mysql_fetch_array($sql_results)) {
    $datasize = count($data);
    $mongo_sql = array('_id'=>$data['id'], 'title'=>$data['title']);


print_r($mongo_sql);
$mycollection->insert($mongo_sql);
}
echo "MongoDB database conversion completed.";


