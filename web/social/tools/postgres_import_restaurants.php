<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$dbconn3 = pg_connect("host=89.249.212.14 port=5432 dbname=osm user=postgres password=ezycmt546");

$query = "SELECT tags->'addr:street' as street, tags->'addr:city' as city, tags->'addr:postcode' as postcode, tags->'url' as url, tags->'smoking' as smoking, tags->'wheelchair' as wheelchair,
    tags->'internet_access' as internet_access, tags->'internet_access:fee' as internet_access_fee, concat( tags->'phone',' ',tags->'contact:phone') as phone, tags->'website' as website,
    tags->'alt_name' as alt_name, tags->'fax' as fax, tags->'stars' as stars, tags->'addr:country' as country, tags->'email' as email, tags->'rooms' as rooms, tags->'old_name' as old_name,
    tags->'official_name' as official_name, tags->'beds' as beds, tags->'wifi' as wifi, tags->'contact:housenumber' as housenumber,
  name ,planet_osm_point.amenity as amenity , planet_osm_point.tourism as tourism, st_asText(st_transform(way, 4326)) as longlat
, ST_X(st_transform(way, 4326)) as longitude, ST_Y(st_transform(way, 4326)) as latitude FROM 
public.planet_osm_point WHERE name!='' AND ( planet_osm_point.amenity in ('restaurant', 'fast food') OR planet_osm_point.tourism in ('restaurant', 'fast food'))";

$ret = pg_query($dbconn3,$query);

$ret_arr = array();
if($ret && pg_num_rows($ret)!=0 ){
    while($row = pg_fetch_array($ret)){
        $ret_arr[] = $row;
    }
}
$conn   = mysql_connect( "localhost" , "root", "mysql_root" ); 
mysql_select_db( 'geonames' );
mysql_query("SET NAMES 'UTF8'");
foreach($ret_arr as $item){
    
    $street = addslashes($item['street']);
    $city = addslashes($item['city']);
    $postcode = addslashes($item['postcode']);
    $url = addslashes($item['url']);
    $smoking = addslashes($item['smoking']);
    $wheelchair = addslashes($item['wheelchair']);
    $internet_access = addslashes($item['internet_access']);
    $internet_access_fee = addslashes($item['internet_access_fee']);
    $phone = addslashes($item['phone']);
    $website = addslashes($item['website']);
    $alt_name = addslashes($item['alt_name']);
    $fax = addslashes($item['fax']);
    $stars = addslashes($item['stars']);
    $country = addslashes($item['country']);
    $email = addslashes($item['email']);
    $rooms = addslashes($item['rooms']);
    $old_name = addslashes($item['old_name']);
    $official_name = addslashes($item['official_name']);
    $name = addslashes($item['name']);
    $beds = addslashes($item['beds']);
    $wifi = addslashes($item['wifi']);
    $housenumber = addslashes($item['housenumber']);
    $amenity = addslashes($item['amenity']);
    $tourism = addslashes($item['tourism']);
    $longlat = addslashes($item['longlat']);
    $longitude = addslashes($item['longitude']);
    $latitude = addslashes($item['latitude']);
    $insert_query = "INSERT INTO `postgres_restaurants`( `street`, `city`, `postcode`, `url`, `smoking`, `wheelchair`, `internet_access`, `internet_access_fee`, `phone`, `website`, `alt_name`, `fax`, "
            . "`stars`, `country`, `email`, `rooms`, `old_name`, `official_name`, `name`, `beds`, `wifi`, `housenumber`, `amenity`, `tourism`, `longlat`, `longitude`, `latitude`) "
            . "VALUES ('$street','$city','$postcode','$url','$smoking','$wheelchair','$internet_access','$internet_access_fee','$phone','$website','$alt_name','$fax','$stars','$country','$email','$rooms','$old_name',"
            . "'$official_name','$name','$beds','$wifi','$housenumber','$amenity','$tourism','','$longitude','$latitude')";
    mysql_query($insert_query);
    echo mysql_error();
    echo '<br>';
}
echo 'done';

