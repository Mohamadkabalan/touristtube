<pre><?php

set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


//mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);
$str='';
/*$sql="SELECT * FROM `global_restaurants` WHERE `email` LIKE '%[%'";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $address_extended = '';
    $po_box = addslashes($r['address_extended']);
    $locality = addslashes($r['po_box']);
    $region = addslashes($r['locality']);
    $post_town = addslashes($r['region']);
    $admin_region = addslashes($r['post_town']);
    $postcode = intval($r['admin_region']);
    $country = addslashes($r['postcode']);
    $tel = addslashes($r['country']);
    $fax = addslashes($r['tel']);
    $latitude = addslashes($r['fax']);
    $longitude = addslashes($r['latitude']);
    $neighborhood = addslashes($r['longitude']);
    $website = addslashes($r['neighborhood']);
    $email = addslashes($r['website']);
    $category_ids = addslashes($r['email']);
    $category_labels = addslashes($r['category_ids']);
    $chain_name = addslashes($r['category_labels']);
    $chain_id = addslashes($r['chain_name']);
    $hours = addslashes($r['chain_id']);
    $hours_display = addslashes($r['hours']);
    $existence = addslashes($r['hours_display']);
    
    $sqr= "UPDATE `global_restaurants` SET `address_extended`='".$address_extended."',`po_box`='".$po_box."',`locality`='".$locality."',`region`='".$region."',`post_town`='".$post_town."',`admin_region`='".$admin_region."',`postcode`=".$postcode.",`country`='".$country."',`tel`='".$tel."',`fax`='".$fax."',`latitude`='".$latitude."',`longitude`='".$longitude."',`neighborhood`='".$neighborhood."',`website`='".$website."',`email`='".$email."',`category_ids`='".$category_ids."',`category_labels`='".$category_labels."',`chain_name`='".$chain_name."',`chain_id`='".$chain_id."',`hours`='".$hours."',`hours_display`='".$hours_display."',`existence`='".$existence."' WHERE id=".$r['id'];
    mysql_query($sqr);
}
echo $str;
exit();*/
/*$sql="SELECT category_ids FROM global_restaurants where category_ids!='' group by category_ids";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
$str='';
while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $category_ids=  $r['category_ids'];
    $category_ids = str_replace("[","",$category_ids );
    $category_ids = str_replace("]","",$category_ids );
    $str .= "".$category_ids.",";
}
echo $str;
exit();*/
//for perfect matched duplicate poi
/*$sql="SELECT count(name) as cnt , id, name FROM `discover_poi_latest` WHERE `published` =1 AND `status` =2 group by name,city_id order by cnt ASC";
//$sql="SELECT * FROM `discover_poi_latest` WHERE `name` LIKE 'Abu Dhabi Mall' AND `published` =1 AND `status` =2 AND `city_id` =1060174";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $id=  intval($r['id']);
    $cnt=  intval($r['cnt']);
    
    if($cnt>1) $str .= "".$id.",";
    //$str .= "".$id.",";
 }*/
 
/*$sql="SELECT * FROM `discover_poi_latest` where (city_id<>0 or city<>'') AND status IN(1,2) and published=1";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
$str='';
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $city_id=  intval($r['city_id']);
    $city =  addslashes($r['city']);
    $country =  $r['country'];
    $str .= "UPDATE `discover_poi` SET `city_id`=".$city_id.",`city`='".$city."',`country`='".$country."', published=1 WHERE id=".$r['id']."; \n";
 }*/
 //for matched city but poi name not found on google
/*$sql="SELECT * FROM `discover_poi_latest` where status IN(2)";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $str .= "UPDATE `discover_poi` SET published=-2 WHERE id=".$r['id']."; \n";
 }*/
 //geolocation points to different country
/*$sql="SELECT * FROM `discover_poi_latest` where status IN(3)";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $str .= "UPDATE `discover_poi` SET published=-2,status=5 WHERE id=".$r['id']."; \n";
 }*/
 
 $sql="SELECT * FROM `discover_poi_latest`";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
        $name=  addslashes($r['name']);
        $name=  html_entity_decode($name);        
        $latitude= $r['latitude'] - 0.00001;
        $longitude= $r['longitude'] - 0.00001;
        $stars= $r['stars'];
        $price= $r['price'];
        $country= $r['country'];
        $city= addslashes($r['city']);
        $zipcode= addslashes($r['zipcode']);
        $phone= addslashes($r['phone']);
        $fax= addslashes($r['fax']);
        $email= addslashes($r['email']);
        $website= addslashes($r['website']);
        $description= addslashes($r['description']);
        $address= addslashes($r['address']);
        $cat= addslashes($r['cat']);
        $sub_cat= addslashes($r['sub_cat']);
        $city_id= $r['city_id'];
        
        //$str .= "INSERT INTO `discover_poi`(`longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`, `published`, `address`, `from_source`,status) VALUES ($longitude,$latitude,'$name',$stars,'$country','$city',0,0,'$cat','$sub_cat','',$city_id,'$zipcode','$phone','$fax','$email','$website',$price,'$description',1,'$address','lonelyplanet',1); \n";        
       $str = "INSERT INTO `discover_poi`(`longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`, `published`, `address`, `from_source`,status) VALUES ($longitude,$latitude,'$name',$stars,'$country','$city',0,0,'$cat','$sub_cat','',$city_id,'$zipcode','$phone','$fax','$email','$website',$price,'$description',1,'$address','lonelyplanet',1)";
        mysql_query($str);
}
 echo $str;
/*$sql="SELECT * FROM `discover_poi` WHERE `name` LIKE '%??%' AND `published` = 1 ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $name=  $r['name'];  
    $name=  html_entity_decode($name);
    //$names=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $name);
    $name = str_replace("?????????????","",$name );
    $name = str_replace("????????????","",$name );
    $name = str_replace("???????????","",$name );
    $name = str_replace("??????????","",$name );
    $name = str_replace("?????????","",$name );
    $name = str_replace("????????","",$name );
    $name = str_replace("???????","",$name );
    $name = str_replace("??????","",$name );
    $name = str_replace("?????","",$name );
    $name = str_replace("????","",$name );
    $name = str_replace("???","",$name );
    $names = str_replace("??","",$name );
    if($names!=''){
        $names = str_replace("'","''",$names );        
        $sqr= "UPDATE `discover_poi` SET `name`='".$names."' WHERE id='".$r['id']."'";
        mysql_query($sqr);       
    }else{
        echo $r['id'];
        echo PHP_EOL;
    }
 }*/
/*$sql="SELECT * FROM `poi_gps` WHERE `status` =1";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
        $name=  $r['poi_name'];
        $name=  html_entity_decode($name);
        //$name=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $name);
        
        $latitude= $r['poi_latitude'] - 0.00001;
        $longitude= $r['poi_longitude'] - 0.00001;
        $country= $r['country_code'];
        $city= '';
        $tel= '';
        $website= '';
        $description= '';
        $address= '';
        $category= $r['category'];
        $category=  html_entity_decode($category);
        $category=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $category);
        
        $sql= "INSERT INTO `discover_poi`(`longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`, `published`, `address`, `from_source`) VALUES ($longitude,$latitude,'$name',0,'$country','$city',0,0,'$category','','',0,'','$tel','','','$website',NULL,'$description',1,'$address','gps')";
        
       mysql_query($sql);
}
$sql="SELECT * FROM `poi_gogobot` WHERE `status` =1";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
        $name=  $r['poi_name'];
        $name=  html_entity_decode($name);
        //$name=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $name);
        
        $latitude= $r['poi_latitude'] - 0.00001;
        $longitude= $r['poi_longitude'] - 0.00001;
        $country= $r['country_code'];
        $city= $r['city'];
        $city=  html_entity_decode($city);
        $city=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$city );
        $tel= $r['poi_contact'];
        $tel=  html_entity_decode($tel);
        $tel=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$tel );
        $website= $r['poi_website'];
        $description= $r['poi_description'];
        $description=  html_entity_decode($description);
        $description=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $description);
        $address= $r['poi_address'];
        $address=  html_entity_decode($address);
        $address=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $address);
        $category= $r['poi_about'];
        $category=  html_entity_decode($category);
        $category=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $category);
        
        $sql= "INSERT INTO `discover_poi`(`longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`, `published`, `address`, `from_source`) VALUES ($longitude,$latitude,'$name',0,'$country','$city',0,0,'$category','','',0,'','$tel','','','$website',NULL,'$description',1,'$address','gogobot')";
        
       mysql_query($sql);
}*/