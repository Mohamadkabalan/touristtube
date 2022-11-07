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

$keyz = mysql_query("DESCRIBE discover_hotels") or die( mysql_error() );
while($data = mysql_fetch_array($keyz)) $structure[]=$data['Field'];

$sql_results = mysql_query("SELECT * FROM discover_hotels ") or die( mysql_error());
$count = mysql_num_rows($sql_results);

$mycollection = $mongodb->discover_hotels;

if($count > 0) {

while($data = mysql_fetch_array($sql_results)) {
    $datasize = count($data);
    $mongo_sql = array();
    for( $i=0; $i < $datasize; $i++ ) {
    if(!empty($structure[$i]))
        $mongo_sql[$structure[$i]] = check_character_code($data[$i]);

}
print_r($mongo_sql);
$mycollection->insert($mongo_sql);

}
}

echo "MongoDB database conversion completed.";


