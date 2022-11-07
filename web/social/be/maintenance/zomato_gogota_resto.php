<pre><?php

set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
//mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);

/*$sql="SELECT * FROM `discover_poi` WHERE `name` = '' and from_source='gps'";
//$sql="SELECT * FROM `test1`";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
     $latitude= $r['latitude'] + 0.00001;
     $longitude= $r['longitude'] + 0.00001;
     $country= $r['country'];     
     $address= $r['address'];
     $sql2= "SELECT poi_name FROM `poi_gogobot` WHERE poi_longitude='".$longitude."' and poi_latitude='".$latitude."' and country_code='".$country."' and poi_address='".$address."' ";
     $results1 = mysql_query($sql2);
     $num =mysql_num_rows($results1);
     if($num==1 ) {
        $cdata =mysql_fetch_array($results1);
        $name=  $cdata['poi_name'];
        $name=  html_entity_decode($name);
        $name = str_replace("'","''",$name );        
        $sqr= "UPDATE `discover_poi` SET `name`='".$name."' WHERE id='".$r['id']."' and name=''";
        echo $sqr;
        echo PHP_EOL;
        mysql_query($sqr);    
     }else{
         echo $num.']['.$r['id'];
         echo PHP_EOL;
     }
 } */
/*$sql="SELECT * FROM `zomato_in_restaurant` WHERE `status` =1";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
        $name=  $r['res_name'];
        $name=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $name);        
        $address= $r['res_address'];
        $address=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $address);
        $locality= $r['city'];
        $locality=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$locality );
        $country= $r['country_code'];
        $tel= $r['res_contact'];
        $fax= $r['res_fax'];
        $latitude= $r['res_latitude'] - 0.00002;
        $longitude= $r['res_longitude'] - 0.00002;
        $website= $r['res_website'];
        $hours_display= $r['opening_hr'];
        $hours_display=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$hours_display );
        $sql= "INSERT INTO `global_restaurants`(`factual_id`, `name`, `address`, `address_extended`, `po_box`, `locality`, `region`, `post_town`, `admin_region`, `postcode`, `country`, `tel`, `fax`, `latitude`, `longitude`, `neighborhood`, `website`, `email`, `category_ids`, `category_labels`, `chain_name`, `chain_id`, `hours`, `hours_display`, `existence`, `city_id`, `zoom_order`, `published`, `from_source`) VALUES (0,'$name','$address','','','$locality','','','',0,'$country','$tel','$fax','$latitude','$longitude','','$website','','','','','','','$hours_display','',0,0,1,'zomato')";
        
       mysql_query($sql);
}
/*$sql="SELECT * FROM zomato_restaurant where res_latitude<>0 and res_longitude<>0";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
        $name=  $r['res_name'];
        $name=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $name);        
        $address= $r['res_address'];
        $address=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $address);
        $locality= $r['city'];
        $locality=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$locality );
        $country= $r['country_code'];
        $tel= $r['res_contact'];
        $fax= $r['res_fax'];
        $latitude= $r['res_latitude'] - 0.00002;
        $longitude= $r['res_longitude'] - 0.00002;
        $website= $r['res_website'];
        $hours_display= $r['opening_hr'];
        $hours_display=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$hours_display );
        $sql= "INSERT INTO `global_restaurants`(`factual_id`, `name`, `address`, `address_extended`, `po_box`, `locality`, `region`, `post_town`, `admin_region`, `postcode`, `country`, `tel`, `fax`, `latitude`, `longitude`, `neighborhood`, `website`, `email`, `category_ids`, `category_labels`, `chain_name`, `chain_id`, `hours`, `hours_display`, `existence`, `city_id`, `zoom_order`, `published`, `from_source`) VALUES (0,'$name','$address','','','$locality','','','',0,'$country','$tel','$fax','$latitude','$longitude','','$website','','','','','','','$hours_display','',0,0,1,'zomato')";
        
       mysql_query($sql);
}

$sql="SELECT * FROM gogobot_restaurant where res_latitude<>0 and res_longitude<>0";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
        $name=  $r['res_name'];
        $name=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $name);        
        $address= $r['res_address'];
        $address=  iconv("UTF-8", "ISO-8859-1//TRANSLIT", $address);
        $locality= $r['city'];
        $locality=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$locality );
        $country= $r['country_code'];
        $tel= $r['res_contact'];
        $fax= $r['res_fax'];
        $latitude= $r['res_latitude'] - 0.00002;
        $longitude= $r['res_longitude'] - 0.00002;
        $website= $r['res_link'];
        $hours_display= $r['opening_hr'];
        $hours_display=  iconv("UTF-8", "ISO-8859-1//TRANSLIT",$hours_display );
        $sql= "INSERT INTO `global_restaurants`(`factual_id`, `name`, `address`, `address_extended`, `po_box`, `locality`, `region`, `post_town`, `admin_region`, `postcode`, `country`, `tel`, `fax`, `latitude`, `longitude`, `neighborhood`, `website`, `email`, `category_ids`, `category_labels`, `chain_name`, `chain_id`, `hours`, `hours_display`, `existence`, `city_id`, `zoom_order`, `published`, `from_source`) VALUES (0,'$name','$address','','','$locality','','','',0,'$country','$tel','$fax','$latitude','$longitude','','$website','','','','','','','$hours_display','',0,0,1,'gogobot')";
        
       mysql_query($sql);
}*/