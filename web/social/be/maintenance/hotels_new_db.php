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

$notfound0 =0;
$found0 =0;
$found1 =0;
$found2 =0;
$found3 =0;
$found4 =0;
$found5 =0;

$sql="SELECT datastellar_country_sub_sub_sub.id as id, datastellar_country.title as country , datastellar_country_sub.type as state_type, datastellar_country_sub.title as state , datastellar_country_sub_sub.title as city, datastellar_country_sub_sub_sub.title as district , datastellar_country.iso_country_code_2 as iso, datastellar_country_sub_sub_sub.count as cnt "
        . "FROM datastellar_country, datastellar_country_sub_sub_sub, datastellar_country_sub_sub, datastellar_country_sub "
        . "  WHERE datastellar_country_sub_sub_sub.country_id = datastellar_country.id "
        . " AND datastellar_country_sub_sub_sub.country_sub_sub_id = datastellar_country_sub_sub.id "
        . " AND datastellar_country_sub_sub_sub.country_sub_id = datastellar_country_sub.id AND datastellar_country_sub_sub_sub.city_id=0 LIMIT 0, 5000";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
     $r['state'] = str_replace('Province of ','',$r['state'] );
     $r['state'] = str_replace('Province Of ','',$r['state'] );
     $r['state'] = str_replace(' Province','',$r['state'] );
     $r['state'] = str_replace(' State','',$r['state'] );
     $r['state'] = str_replace('Province ','',$r['state'] );
     $r['state'] = str_replace(' Region','',$r['state'] );
     $r['state'] = str_replace(' City','',$r['state'] );
     
     $r['city1'] = str_replace(' Province','',$r['city'] );
     $r['city1'] = str_replace('St. ','',$r['city1'] );
     $r['city1'] = str_replace(' City','',$r['city1'] );
     $r['city1'] = str_replace('State Of ','',$r['city1'] );
     $r['city1'] = str_replace('State of ','',$r['city1'] );
     
     $r['district1'] = str_replace(' Village','',$r['district'] );
     
     /*print_r($r['state_type']);
     echo '<br/>';
     continue;*/
    
     if ($r['state_type'] =='Cities' ){
        $sql1= sprintf("select * FROM webgeocities , states "
        . " WHERE webgeocities.country_code =states.country_code AND webgeocities.state_code = states.state_code   "
        . " AND  webgeocities.country_code = '%s' AND webgeocities.name IN( '%s') ",$r['iso'] , addslashes($r['state']) );
        $results1 = mysql_query($sql1) or die( mysql_error());
        $num =mysql_num_rows($results1);
        if($num==1 ) {
            $cdata =mysql_fetch_array($results1);
            $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
            mysql_query($sqr);
            $found0 ++;                 
        }else {                 
           $sql11= sprintf("select * FROM webgeocities , states "
           . " WHERE webgeocities.country_code =states.country_code AND webgeocities.state_code = states.state_code   "
           . " AND  webgeocities.country_code = '%s' AND webgeocities.name IN( '%s')  AND states.state_name in('%s') and webgeocities.name IN( '%s') ",$r['iso'] , addslashes($r['state']), 'California', 'San Francisco' );
           $results1 = mysql_query($sql11) or die( mysql_error());
           $num =mysql_num_rows($results1);
           if($num==1 ) {
                $cdata =mysql_fetch_array($results1);
                $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                mysql_query($sqr);
               $found0 ++;             
           }else {                    
               $notfound0 ++;
           }
        } 
     }else{         
        $sql12= sprintf("select * FROM webgeocities , states "
        . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
        . " AND  webgeocities.country_code = '%s'  AND states.state_name in('%s') AND webgeocities.name IN( '%s','%s'  ) ",$r['iso'] , addslashes($r['state']), addslashes($r['city']), addslashes($r['city1']) );
        $results1 = mysql_query($sql12) or die( mysql_error());
        $num =mysql_num_rows($results1);
        if($num==1 ) {
            $cdata =mysql_fetch_array($results1);
            $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
            mysql_query($sqr);
            $found1 ++;            
        }else{      
            $sql13= sprintf("select * FROM webgeocities , states "
            . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
            . " AND  webgeocities.country_code = '%s'  AND states.state_name in('%s') AND webgeocities.name IN( '%s','%s') ",$r['iso'] , addslashes($r['state']), addslashes($r['district']), addslashes($r['district1']) );
            $results1 = mysql_query($sql13) or die( mysql_error());
            $num =mysql_num_rows($results1);
            if($num==1 ) {
                $cdata =mysql_fetch_array($results1);
                $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                mysql_query($sqr);
                $found2 ++;            
            }else{
                $sql14= sprintf("select * FROM webgeocities , states "
                . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
                . " AND webgeocities.country_code = '%s'  AND webgeocities.name IN( '%s','%s') ",$r['iso'] , addslashes($r['district']), addslashes($r['district1']) );
                $results1 = mysql_query($sql14) or die( mysql_error());
                $num =mysql_num_rows($results1);
                if($num==1 ) {
                    $cdata =mysql_fetch_array($results1);
                    $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                    mysql_query($sqr);
                    $found3 ++;                    
                }else{
                    $sql15= sprintf("select * FROM webgeocities , states "
                    . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
                    . " AND  webgeocities.country_code = '%s'  AND states.state_name in('%s') AND webgeocities.name IN( '%s') ",$r['iso'] , addslashes($r['state']), addslashes($r['state']) );
                    $results1 = mysql_query($sql15) or die( mysql_error());
                    $num =mysql_num_rows($results1);
                    if($num==1 ) {
                        $cdata =mysql_fetch_array($results1);
                        $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                        mysql_query($sqr);
                        $found4 ++;                        
                    }else{           
                        $sql16= sprintf("select * FROM webgeocities , states "
                        . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
                        . " AND  webgeocities.country_code = '%s'  AND states.state_name LIKE '%s'  AND webgeocities.name LIKE '%s' ",$r['iso'] , addslashes('%'.$r['state'].'%'), addslashes('%'.$r['state'].'%') );
                        $results1 = mysql_query($sql16) or die( mysql_error());
                        $num =mysql_num_rows($results1);
                        if($num==1 ) {
                            $cdata =mysql_fetch_array($results1);
                            $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                            mysql_query($sqr);
                            $found5 ++;                            
                        }else{
                            $sql17= sprintf("select * FROM webgeocities , states "
                            . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
                            . " AND  webgeocities.country_code = '%s'  AND states.state_name LIKE '%s'  AND webgeocities.name LIKE '%s' ",$r['iso'] , addslashes($r['state']), addslashes($r['city1']) );
                            $results1 = mysql_query($sql17) or die( mysql_error());
                            $num =mysql_num_rows($results1);
                            if($num==1 ) {
                                $cdata =mysql_fetch_array($results1);
                                $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                                mysql_query($sqr);
                                $found5 ++;                                
                            }else{
                                $sql18= sprintf("select * FROM webgeocities , states "
                                . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
                                . " AND  webgeocities.country_code = '%s' AND webgeocities.name LIKE '%s' ",$r['iso'], addslashes($r['city1']) );
                                $results1 = mysql_query($sql18) or die( mysql_error());
                                $num =mysql_num_rows($results1);
                                if($num==1 ) {
                                    $cdata =mysql_fetch_array($results1);
                                    $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                                    mysql_query($sqr);
                                    $found5 ++;                                    
                                }else{
                                    $sql19= sprintf("select * FROM webgeocities , states "
                                    . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
                                    . " AND  webgeocities.country_code = '%s'  AND states.state_name in('%s') AND webgeocities.name IN( '%s','%s') ",$r['iso'] , addslashes($r['city1']), addslashes($r['district']), addslashes($r['district1']) );
                                    $results1 = mysql_query($sql19) or die( mysql_error());
                                    $num =mysql_num_rows($results1);
                                    if($num==1 ) {
                                        $cdata =mysql_fetch_array($results1);
                                        $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                                        mysql_query($sqr);
                                        $found5 ++;                                    
                                    }else{
                                        $sql110= sprintf("select * FROM webgeocities , states "
                                        . " WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code   "
                                        . " AND  webgeocities.country_code = '%s'  AND states.state_name in('%s') AND webgeocities.name LIKE '%s' ",$r['iso'] , addslashes($r['state']), addslashes(''.$r['district'].' %') );
                                        $results1 = mysql_query($sql110) or die( mysql_error());
                                        $num =mysql_num_rows($results1);
                                        if($num==1 ) {
                                            $cdata =mysql_fetch_array($results1);
                                            $sqr= "UPDATE `datastellar_country_sub_sub_sub` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                                            mysql_query($sqr);
                                            $found5 ++;                                    
                                        }else{
                                            //echo PHP_EOL; echo 'HERESS';
                                            $notfound0 ++;
                                            print_r($r);
                                            echo PHP_EOL;
                                            echo $sql110;
                                            echo PHP_EOL;
                                            echo PHP_EOL;
                                        }
                                    }
                                }
                            }
                        }         
                    }
                }
            }
        }
    }
    /*while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {  
        echo PHP_EOL.'r1:';    print_r($r1);
    }
    echo PHP_EOL;
    echo '==========================================================';
    echo PHP_EOL;*/
}
echo PHP_EOL;
echo "TOTAL:".$total;
echo PHP_EOL;
echo "FOUND 0:".$found0;
echo PHP_EOL;
echo "NOT FOUND 0:".$notfound0;
echo PHP_EOL;
echo "FOUND 1:".$found1;
echo PHP_EOL;
echo "FOUND 2:".$found2;
echo PHP_EOL;
echo "FOUND 3:".$found3;
echo PHP_EOL;
echo "FOUND 4:".$found4;
echo PHP_EOL;
echo "FOUND 5:".$found5;
echo PHP_EOL;
echo "TOTAL: ".($found0+$found1+$found2+$found3+$found4+$found5);
echo PHP_EOL;