<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);


//datastellar_chain	
//datastellar_country
//datastellar_country_sub	
//datastellar_country_sub_sub
//datastellar_country_sub_sub_sub
//datastellar_country_sub_sub_sub_to_hotel
//datastellar_country_sub_sub_to_hotel
//datastellar_country_sub_to_hotel
//datastellar_country_sub_type
//datastellar_hotel
//datastellar_hotel_feature
//datastellar_hotel_feature_to_hotel
//datastellar_hotel_feature_type
//datastellar_hotel_image
//datastellar_location
//datastellar_location_type
//datastellar_place_of_interest
//datastellar_place_of_interest_name
//datastellar_property_type

//$sql="SELECT * FROM datastellar_country_sub_sub_sub, datastellar_country WHERE datastellar_country_sub_sub_sub.country_id = datastellar_country.id LIMIT 100";
//$sql="SELECT count(*) FROM datastellar_hotel, datastellar_country_sub  WHERE datastellar_hotel.country_sub_id = datastellar_country_sub.id ";

//$sql="SELECT datastellar_hotel.id as id, datastellar_hotel.title as title1, datastellar_country.title as title2, datastellar_country.iso_country_code_2 as iso, datastellar_country_sub_sub_sub.title as title3  "
//        . "FROM datastellar_hotel, datastellar_country_sub, datastellar_country, datastellar_country_sub_sub_sub_to_hotel, datastellar_country_sub_sub_sub"
//        . "  WHERE datastellar_country_sub_sub_sub.country_sub_id = datastellar_country_sub.id"
//        . " AND datastellar_country_sub_sub_sub_to_hotel.country_sub_sub_sub_id = datastellar_country_sub_sub_sub.id"
//        . " AND datastellar_country_sub.country_id = datastellar_country.id "
//        . " AND datastellar_country_sub_sub_sub_to_hotel.hotel_id = datastellar_hotel.id "
//        . " AND datastellar_hotel.country_sub_id = datastellar_country_sub.id ";

$notfound =0;
$found =0;
$error =0;
$sql="SELECT datastellar_country_sub_sub_sub.id as id, datastellar_country.title as country , datastellar_country_sub.type as state_type, datastellar_country_sub.title as state , datastellar_country_sub_sub.title as city, datastellar_country_sub_sub_sub.title as district , datastellar_country.iso_country_code_2 as iso "
        . "FROM datastellar_country, datastellar_country_sub_sub_sub, datastellar_country_sub_sub, datastellar_country_sub "
        . "  WHERE datastellar_country_sub_sub_sub.country_id = datastellar_country.id "
        . " AND datastellar_country_sub_sub_sub.country_sub_sub_id = datastellar_country_sub_sub.id "
        . " AND datastellar_country_sub_sub_sub.country_sub_id = datastellar_country_sub.id ";
$results = mysql_query($sql) or die( mysql_error());

 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
     print_r($r);
     switch ($r['state_type']){
         case 'Cities';
         echo $sql1= sprintf("select * FROM webgeocities , states "
             . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
             . " AND  webgeocities.country_code = '%s' AND webgeocities.name IN( '%s') ",$r['iso'] , addslashes($r['state']) );
             break;
       //  case 'States';
       //  echo $sql1= sprintf("select * FROM webgeocities , states "
       //      . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
       //      . " AND  webgeocities.country_code = '%s'  AND states.state_name in('%s') AND webgeocities.name IN( '%s', '%s' ) ",$r['iso'] , addslashes($r['state']),addslashes($r['district']),addslashes($r['city']) );
       //      break;
         default:
       echo $sql1= sprintf("select * FROM webgeocities , states "
             . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
             . " AND  webgeocities.country_code = '%s'  AND states.state_name in('%s') AND webgeocities.name IN( '%s','%s'  ) ",$r['iso'] , addslashes(str_replace(' Province','',$r['state'])),addslashes($r['district']),addslashes($r['city']) );
             break;   
         
         
     }
    // echo $sql1= sprintf("select * FROM webgeocities , states "
    //         . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
    //         . " AND  webgeocities.country_code = '%s' AND webgeocities.name IN( '%s','%s') AND states.state_name in( '%s', '%s') ",$r['iso'] , addslashes($r['district']), addslashes(str_replace(' District','',$r['district'])), addslashes($r['state']),  addslashes(str_replace(' Province','',$r['state'])) );
     $results1 = mysql_query($sql1) or die( mysql_error());
     $num =mysql_num_rows($results1);
    switch($num){
        case 0: 
       //     echo PHP_EOL;
       //     echo $sql1= sprintf("select * FROM webgeocities , states "
       //      . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
       //      . " AND  webgeocities.country_code = '%s' AND webgeocities.name IN( '%s', '%s')  AND states.state_name in( '%s','%s') ",$r['iso'] , addslashes($r['city']), addslashes(str_replace(' District','',$r['district'])), addslashes($r['state']), addslashes($r['city']), addslashes(str_replace(' District','',$r['city'])));
       //     $results1 = mysql_query($sql1) or die( mysql_error());
       //         switch(mysql_num_rows($results1)){
       //             case 0:
      //                   echo PHP_EOL;
      //      echo $sql1= sprintf("select * FROM webgeocities , states "
      //       . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
      //       . " AND  webgeocities.country_code = '%s' AND webgeocities.name IN('%s','%s' ) ",$r['iso'] ,  addslashes(str_replace(' District','',$r['district'])),addslashes(str_replace(' District','',$r['city'])) );
      //      $results1 = mysql_query($sql1) or die( mysql_error());
      //          switch(mysql_num_rows($results1)){
      //              case 0:
      //                  $notfound++;
      //                   break; 
      //              case 1: $found ++; break;
      //              default: $error++; echo 'count:'.mysql_num_rows($results1);
      //          }
      //                   $notfound++; break; 
      //              case 1: $found ++; break;
      //              default: $error++; echo 'count:'.mysql_num_rows($results1);
      //          }
        $notfound++; break; 
        case 1: $found ++; break;
        default: $error++; echo 'count:'.$num;
    }
    
         while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {  echo PHP_EOL.'r1:';    print_r($r1);
}
echo PHP_EOL;
echo '==========================================================';
echo PHP_EOL;
 }
echo PHP_EOL;
echo "NOT FOUND:".$notfound;
echo PHP_EOL;
echo "FOUND:".$found;
echo PHP_EOL;
echo "ERROR:".$error;
echo PHP_EOL;
echo "TOTAL: ".($found+$notfound);
echo PHP_EOL;