<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


mysql_connect('172.16.124.204','mysql_root','Mr4+%FINDZm,:AGL');
mysql_select_db($database_name);
mysql_query("SET NAMES utf8");

//$couters =0;
$ct=0;
$ct2=0;
$ct3=0;
//
//$sql="SELECT n.`id` FROM amadeus_hotel_city n WHERE n.city_id =0 and n.published=1 order by n.id ASC limit 0,5000;";
//
//$results = mysql_query($sql) or die( mysql_error());
// while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//    $id = $r['id'];
//
//    $sql="SELECT amadeus_city_id FROM `amadeus_hotel` WHERE `dupe_pool_id` IS NOT NULL  AND `amadeus_city_id`=$id;";
//    $results1 = mysql_query($sql);
//    $total =mysql_num_rows($results1);
//    if($total && $total>0){
//
//    }else{
//        $ct++;
//        $sql2="UPDATE `amadeus_hotel_city` SET `published` = '-2' WHERE `id` =$id;";
//        mysql_query($sql2);
//    }
// }
//$sql="SELECT n.`id` FROM amadeus_hotel_city n WHERE n.city_id =0 and n.published=1 order by n.id ASC limit 5000,5000;";
//
//$results = mysql_query($sql) or die( mysql_error());
// while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//    $id = $r['id'];
//
//    $sql="SELECT amadeus_city_id FROM `amadeus_hotel` WHERE `dupe_pool_id` IS NOT NULL  AND `amadeus_city_id`=$id;";
//    $results1 = mysql_query($sql);
//    $total =mysql_num_rows($results1);
//    if($total && $total>0){
//
//    }else{
//        $ct++;
//        $sql2="UPDATE `amadeus_hotel_city` SET `published` = '-2' WHERE `id` =$id;";
//        mysql_query($sql2);
//    }
// }
//$sql="SELECT n.`id` FROM amadeus_hotel_city n WHERE n.city_id =0 and n.published=1 order by n.id ASC limit 10000,5000;";
//
//$results = mysql_query($sql) or die( mysql_error());
// while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//    $id = $r['id'];
//
//    $sql="SELECT amadeus_city_id FROM `amadeus_hotel` WHERE `dupe_pool_id` IS NOT NULL  AND `amadeus_city_id`=$id;";
//    $results1 = mysql_query($sql);
//    $total =mysql_num_rows($results1);
//    if($total && $total>0){
//
//    }else{
//        $ct++;
//        $sql2="UPDATE `amadeus_hotel_city` SET `published` = '-2' WHERE `id` =$id;";
//        mysql_query($sql2);
//    }
// }
//$sql="SELECT n.`id` FROM amadeus_hotel_city n WHERE n.city_id =0 and n.published=1 order by n.id ASC limit 15000,5000;";
//
//$results = mysql_query($sql) or die( mysql_error());
// while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//    $id = $r['id'];
//
//    $sql="SELECT amadeus_city_id FROM `amadeus_hotel` WHERE `dupe_pool_id` IS NOT NULL  AND `amadeus_city_id`=$id;";
//    $results1 = mysql_query($sql);
//    $total =mysql_num_rows($results1);
//    if($total && $total>0){
//
//    }else{
//        $ct++;
//        $sql2="UPDATE `amadeus_hotel_city` SET `published` = '-2' WHERE `id` =$id;";
//        mysql_query($sql2);
//    }
// }
//$sql="SELECT n.`id` FROM amadeus_hotel_city n WHERE n.city_id =0 and n.published=1 order by n.id ASC limit 20000,5000;";
//
//$results = mysql_query($sql) or die( mysql_error());
// while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//    $id = $r['id'];
//
//    $sql="SELECT amadeus_city_id FROM `amadeus_hotel` WHERE `dupe_pool_id` IS NOT NULL  AND `amadeus_city_id`=$id;";
//    $results1 = mysql_query($sql);
//    $total =mysql_num_rows($results1);
//    if($total && $total>0){
//
//    }else{
//        $ct++;
//        $sql2="UPDATE `amadeus_hotel_city` SET `published` = '-2' WHERE `id` =$id;";
//        mysql_query($sql2);
//    }
// }
// exit;

//$couters =0;
//$limit=5000;
//while($couters<1){
//    $skval =$couters*$limit;
//    $sql="SELECT n.`id` , n.city_id, n.city_name, n.state_code, n.country_code, h.property_name, h.dupe_pool_id, h.address_line_1, h.latitude, h.longitude FROM amadeus_hotel_city n
//INNER JOIN amadeus_hotel h ON h.amadeus_city_id = n.id WHERE n.city_id =0 AND n.published =1 AND NOT EXISTS (SELECT m.`id` FROM `hotel_maps` m WHERE m.id=n.id) GROUP BY n.id ORDER BY n.id ASC;";
//    $couters++;
//    $prec1 =1;
//    $prec2 =10;
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $city_name = addslashes($r['city_name']);
//        $state_code = $r['state_code'];
//        $country_code = $r['country_code'];
//        $lat = $r['latitude'];
//        $log = $r['longitude'];
//
//        $url="https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$log&sensor=true&key=AIzaSyCL53RGsSAL-vteodkWJJZCaRksk3HB02E";
//        $geocode_stats = file_get_contents($url);
//        $result='no city found';
//        $result1='no city found';
//        $output_deals = json_decode($geocode_stats);
//        $status = $output_deals->status;
//        if($status=='OK'){
//          $address_components=$output_deals->results[0]->address_components;
//          $geometry_components=$output_deals->results[0]->geometry;
//
//          for($i=0;$i<count($address_components);++$i){
//            if(array("locality", "political")==$address_components[$i]->types){
//              $city_name1=addslashes($address_components[$i]->short_name);
//              break;
//            }
//          }
//          for($i=0;$i<count($address_components);++$i){
//            if(array("country", "political")==$address_components[$i]->types){
//              $country_code=$address_components[$i]->short_name;
//              break;
//            }
//          }
//          $lat1 = $geometry_components->location->lat;
//          $log1 = $geometry_components->location->lng;
//          $sql="INSERT INTO `hotel_maps`(`id`, `latitude`, `longitude`, `name`, `state_code`, `country_code`, `lat`, `lng`, `name1`) VALUES ($id,$lat,$log,'$city_name','$state_code','$country_code',$lat1,$log1,'$city_name1');";
//          mysql_query($sql) or die( mysql_error());
//          continue;
//        } else{
//            $result=$status;
//            $ct2++;
//            echo PHP_EOL;
//            echo "ct2: ";
//            print_r($ct2);
//            echo PHP_EOL;
//            echo "url: ";
//            print_r($url);
//            echo PHP_EOL;
//          continue;
//        }
//     }
//}
//exit;

//$couters =0;
//$limit=5000;
////$limit=10;
//while($couters<1){
//    $skval =$couters*$limit;
//    $sql="SELECT m . * FROM  `hotel_maps` m INNER JOIN amadeus_hotel_city c ON c.id = m.id AND c.city_id =0 WHERE m.name IS NOT NULL ORDER BY m.id ASC LIMIT $skval, $limit;";
//    $couters++;
//    $prec1 =1;
//    $prec2 =1;
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $name = addStringClean($r['name']);
//        $name1 = addStringClean($r['name1']);
//        $state_code = $r['state_code'];
//        $country_code = $r['country_code'];
//        $lat1 = $lat = $r['lat'];
//        $log1 = $log = $r['lng'];
//
//        $lat = (floor($lat*$prec1)/$prec1);
//        $log = (floor($log*$prec2)/$prec2);
//
//        $dist=1000;

//        $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' AND ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT(".$log.", ".$lat.")) < $dist;";
//        $results1 = mysql_query($sql) or die( mysql_error());
//        $total =mysql_num_rows($results1);
//
//        if($total==1){
//            $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//            $aid = $r1['id'];
//            $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//            mysql_query($sql2) or die( mysql_error());
//        }else if($total>1){
//            $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' and c.name like '%$name%' AND ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT(".$log.", ".$lat.")) < $dist;";
//            $results1 = mysql_query($sql) or die( mysql_error());
//            $total =mysql_num_rows($results1);
//
//            if($total==1){
//                $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                $aid = $r1['id'];
//                $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//                mysql_query($sql2) or die( mysql_error());
//            }else{
//                $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' and c.name like '%$name1%' AND ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT(".$log.", ".$lat.")) < $dist;";
//                $results1 = mysql_query($sql) or die( mysql_error());
//                $total =mysql_num_rows($results1);
//
//                if($total==1){
//                    $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                    $aid = $r1['id'];
//                    $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//                    mysql_query($sql2) or die( mysql_error());
//                }else{
//                    $ct++;
//                }
//            }
//        }else{
//            $ct++;
//        }
//
//                $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' AND c.`latitude` LIKE '$lat%' AND c.`longitude` LIKE '$log%';";
//                $results1 = mysql_query($sql) or die( mysql_error());
//                $total =mysql_num_rows($results1);
//
//                if($total==1){
//                    $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                    $aid = $r1['id'];
//                    $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//                    mysql_query($sql2) or die( mysql_error());
//                }else if($total>1){
//                    $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' and c.name like '%$name%' AND c.`latitude` LIKE '$lat%' AND c.`longitude` LIKE '$log%';";
//                    $results1 = mysql_query($sql) or die( mysql_error());
//                    $total =mysql_num_rows($results1);
//
//                    if($total==1){
//                        $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                        $aid = $r1['id'];
//                        $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//                        mysql_query($sql2) or die( mysql_error());
//                    }else{
//                        $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' and c.name like '%$name1%' AND c.`latitude` LIKE '$lat%' AND c.`longitude` LIKE '$log%';";
//                        $results1 = mysql_query($sql) or die( mysql_error());
//                        $total =mysql_num_rows($results1);
//
//                        if($total==1){
//                            $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                            $aid = $r1['id'];
//                            $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//                            mysql_query($sql2) or die( mysql_error());
//                        }else{
//                            $ct++;
//                        }
//                    }
//                }else{
//                    $ct++;
//                }
//
//     }
//}
//exit;

//$couters =0;
//$limit=5000;
////$limit=10;
//while($couters<6){
//    $skval =$couters*$limit;
//    $sql="SELECT n.`id` , n.city_id, n.city_name, n.state_code, n.country_code, h.property_name, h.dupe_pool_id, h.address_line_1, h.latitude, h.longitude FROM amadeus_hotel_city n
//INNER JOIN amadeus_hotel h ON h.amadeus_city_id = n.id WHERE n.id>11242 and n.city_id =0 AND n.published =1 GROUP BY n.id ORDER BY n.id ASC LIMIT $skval, $limit;";
//    $couters++;
//    $prec1 =1;
//    $prec2 =10;
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $city_name = addslashes($r['city_name']);
//        $state_code = $r['state_code'];
//        $country_code = $r['country_code'];
//        $lat = $r['latitude'];
//        $log = $r['longitude'];
//
//        $url="https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$log&sensor=true&key=AIzaSyCL53RGsSAL-vteodkWJJZCaRksk3HB02E";
//        $geocode_stats = file_get_contents($url);
//        $result='no city found';
//        $result1='no city found';
//        $output_deals = json_decode($geocode_stats);
//        $status = $output_deals->status;
//        if($status=='OK'){
//          $address_components=$output_deals->results[0]->address_components;
//          $geometry_components=$output_deals->results[0]->geometry;
//
//            try{
////                $sql="INSERT INTO `hotel_maps`(`id`, `latitude`, `longitude`, `google_geocode_result`) VALUES ($id,$lat,$log,CAST('$geocode_stats' AS JSON));";
//
//    //          echo PHP_EOL;
//    //            echo "sql: ";
//    //            print_r($sql);
//    //            echo PHP_EOL;
//    //            exit;break;
//              mysql_query($sql) or die( mysql_error());
//            } catch (Exception $ex) {
//
//            }
//
//
//          for($i=0;$i<count($address_components);++$i){
//            if(array("locality", "political")==$address_components[$i]->types){
////              $city_name=addStringClean($address_components[$i]->short_name);
//              $city_name1=addslashes($address_components[$i]->short_name);
//              break;
//            }
//          }
//          for($i=0;$i<count($address_components);++$i){
//            if(array("country", "political")==$address_components[$i]->types){
//              $country_code=$address_components[$i]->short_name;
//              break;
//            }
//          }
////          $lat = $geometry_components->location->lat;
////          $log = $geometry_components->location->lng;
//          $lat1 = $geometry_components->location->lat;
//          $log1 = $geometry_components->location->lng;
//          $sql="INSERT INTO `hotel_maps`(`id`, `latitude`, `longitude`, `name`, `state_code`, `country_code`, `lat`, `lng`, `name1`) VALUES ($id,$lat,$log,'$city_name','$state_code','$country_code',$lat1,$log1,'$city_name1');";
//          mysql_query($sql) or die( mysql_error());
//          continue;
//        } else{
//            $result=$status;
//            $ct2++;
//            echo PHP_EOL;
//            echo "ct2: ";
//            print_r($ct2);
//            echo PHP_EOL;
//            echo "url: ";
//            print_r($url);
//            echo PHP_EOL;
//          continue;
//        }
//
//
//        $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' and c.name like '%$city_name%';";
//        echo PHP_EOL;
//        echo "sql: ";
//        print_r($sql);
//        echo PHP_EOL;
//
//        $results1 = mysql_query($sql) or die( mysql_error());
//        $total =mysql_num_rows($results1);
//
//        if($total>1){
//
//        } else if($total==1){
//            $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//            $aid = $r1['id'];
//            $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//            mysql_query($sql2) or die( mysql_error());
//        }else{
//            $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' AND ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT(".$log.", ".$lat.")) < 100;";
//            $results1 = mysql_query($sql) or die( mysql_error());
//            $total =mysql_num_rows($results1);
//
//            if($total>1){
//
//            } else if($total==1){
//                $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                $aid = $r1['id'];
//                $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//                mysql_query($sql2) or die( mysql_error());
//            }else{
//                $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' and c.name like '%$city_name%' AND ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT(".$log.", ".$lat.")) < 100;";
//                $results1 = mysql_query($sql) or die( mysql_error());
//                $total =mysql_num_rows($results1);
//
//                if($total>1){
//
//                } else if($total==1){
//                    $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                    $aid = $r1['id'];
//                    $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//                    mysql_query($sql2) or die( mysql_error());
//                }else{
//                    $ct++;
//                }
//            }
//        }
//     }
//}
//exit;
$couters =0;
$limit=5000;

while($couters<1){
    $ct2++;
    $skval =$couters*$limit;
    //$sql="SELECT n.`id`,n.city_name,n.state_code,n.country_code,h.latitude,h.longitude,h.id as hid FROM amadeus_hotel_city n inner join amadeus_hotel h on h.amadeus_city_id=n.id WHERE n.city_id =0 and n.published=1 AND n.country_code IN ('us',  'AR',  'AU',  'BR',  'CA') group by n.id LIMIT $skval, $limit;";
    $sql="SELECT n.`id` , n.city_name, n.country_code,h.property_name, h.latitude, h.longitude,h.id as hid FROM amadeus_hotel_city n INNER JOIN amadeus_hotel h ON h.amadeus_city_id = n.id WHERE n.city_id =0 AND n.published =1 and h.published=1 GROUP BY n.id order by n.id ASC LIMIT $skval, $limit;";
    $couters++;
    $prec1 =1;
    $prec2 =10;
    $results = mysql_query($sql) or die( mysql_error());
     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
        $id = $r['id'];
        $hid = $r['hid'];
        $city_name = addStringClean($r['city_name']);
        $country_code = $r['country_code'];
        $lat = $r['latitude'];
        $log = $r['longitude'];

        $sql="SELECT m.* FROM `hotel_maps` m WHERE m.id=$id;";
        $results1 = mysql_query($sql) or die( mysql_error());
        $total =mysql_num_rows($results1);
        if($total>0){
            $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
            $lat = $r1['lat'];
            $log = $r1['lng'];
        }else{
            continue;
        }


//        $sql="SELECT c.city_id FROM cms_hotel c WHERE c.city_id>0 and ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT($log, $lat)) < 100;";
        $sql="SELECT c.city_id FROM amadeus_hotel h inner join amadeus_hotel_city c on c.id=h.amadeus_city_id and c.city_id>0 WHERE ST_Distance_Sphere(POINT(h.longitude, h.latitude), POINT($log, $lat)) < 1000;";


        $results1 = mysql_query($sql) or die( mysql_error());
        $total =mysql_num_rows($results1);

        if($total>=1){
            $ct3++;
            $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
            $city_id = $r1['city_id'];
            $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$city_id' WHERE `id` =$id;";
            mysql_query($sql2) or die( mysql_error());
        }else{
            $ct++;
        }
     }
}
//while($couters<6){
//    $skval =$couters*$limit;
//    $sql="SELECT n.`id`,n.city_name,n.state_code,n.country_code,h.latitude,h.longitude,h.id as hid FROM amadeus_hotel_city n inner join amadeus_hotel h on h.amadeus_city_id=n.id WHERE n.city_id =0 and n.published=1 AND n.country_code IN ('us',  'AR',  'AU',  'BR',  'CA') group by n.id LIMIT $skval, $limit;";
//    $couters++;
//    $prec1 =1;
//    $prec2 =10;
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $hid = $r['hid'];
//        $city_name = addStringClean($r['city_name']);
//        $state_code = $r['state_code'];
//        $country_code = $r['country_code'];
//        $lat = $r['latitude'];
//        $log = $r['longitude'];
//
//        $latitude = (floor($r['latitude']*$prec1)/$prec1);
//        //$latitude = floor($r['latitude']).'.';
//        $longitude = (floor($r['longitude']*$prec2)/$prec2);
//        //$longitude = floor($r['longitude']).'.';
//
//    //    $sql="SELECT h.latitude,h.longitude FROM amadeus_hotel h WHERE h.amadeus_city_id=$id and h.id<>$hid limit 0,1;";
//    //    $results1 = mysql_query($sql) or die( mysql_error());
//    //    $total =mysql_num_rows($results1);
//    //    if($total==1){
//    //        $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//    //        $lat = $r1['latitude'];
//    //        $log = $r1['longitude'];
//    //    }else{
//    //        continue;
//    //    }
//    //    $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' AND ((c.state_code = '$state_code' and c.country_code IN('us','AR','AU','BR','CA')) or c.country_code NOT IN('us','AR','AU','BR','CA')) and c.name like '%$city_name%';";
//
//        $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' AND ((c.state_code = '$state_code' and c.country_code IN('us','AR','AU','BR','CA')) or c.country_code NOT IN('us','AR','AU','BR','CA')) AND c.`latitude` LIKE '$latitude%' AND c.`longitude` LIKE '$longitude%';";
//
//    //    $sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' AND ((c.state_code = '$state_code' and c.country_code IN('us','AR','AU','BR','CA')) or c.country_code NOT IN('us','AR','AU','BR','CA')) and ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT(".$log.", ".$lat.")) < 100;";
//        //$sql="SELECT c.`id`,c.name,c.country_code,c.state_code FROM webgeocities c WHERE c.country_code = '$country_code' and ST_Distance_Sphere(POINT(c.longitude, c.latitude), POINT(".$log.", ".$lat.")) < 100;";
//    //    echo PHP_EOL;
//    //    echo "sql: ";
//    //    print_r($sql);
//
//        $results1 = mysql_query($sql) or die( mysql_error());
//        $total =mysql_num_rows($results1);
//
//        if($total>1){
//            //$r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//    //        echo PHP_EOL;
//    //        echo "r: ";
//    //        print_r($r);
//    //        echo PHP_EOL;
//    //        echo "sql: ";
//    //        print_r($sql);
//    //        echo PHP_EOL;
//    //        echo "r1: ";
//    //        print_r($r1);
//    //        echo PHP_EOL;
//    //        echo "total: ";
//    //        print_r($total);
//    //        echo PHP_EOL;
//    //        echo PHP_EOL;
//        } else if($total==1){
//            $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//            $aid = $r1['id'];
//            $sql2="UPDATE `amadeus_hotel_city` SET `city_id` = '$aid' WHERE `id` =$id;";
//    //        echo PHP_EOL;
//    //        echo "r: ";
//    //        print_r($r);
//    //        echo PHP_EOL;
//    //        echo "sql: ";
//    //        print_r($sql);
//    //        echo PHP_EOL;
//    //        echo "r1: ";
//    //        print_r($r1);
//    //        echo PHP_EOL;
//    //        echo "sql2: ";
//    //        print_r($sql2);
//    //        echo PHP_EOL;
//            mysql_query($sql2) or die( mysql_error());
//        }else{
//            $ct++;
//        }
//     }
//}
//echo PHP_EOL;
//echo "cnt2: ";
//print_r($ct2);
//echo PHP_EOL;
//echo "cnt3: ";
//print_r($ct3);
//echo PHP_EOL;
//echo "cnt: ";
//print_r($ct);
//exit;

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id having count( a.id )=1;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.address_line_1 like CONCAT('%', h.address ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.address_line_1 like CONCAT('%', h.address ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.provider_value,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hrs_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.address_line_1 like CONCAT('%', h.address ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_gds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.address_line_1 like CONCAT('%', h.address ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.gis_point IS NOT NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.gis_point IS NOT NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.gis_point IS NOT NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.gis_point IS NOT NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.gis_point IS NOT NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.property_name,a.`country_code`,a.city2;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.gis_point IS NOT NULL AND ST_Distance_Sphere(h.gis_point, a.gis_point) <= 100 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city = a.city2 group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND h.city like CONCAT('%', a.city2 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL AND a.city2 like CONCAT('%', h.city ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }


//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name=h.minimized_name and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on h.minimized_name like CONCAT('%', a.property_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.address_line_1 like CONCAT('%', h.address ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and h.address like CONCAT('%', a.address_line_1 ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }
//    $sql="SELECT h.id,h.name,h.city,h.country_code,count(a.dupe_pool_id) as cnt,a.dupe_pool_id,a.id as aid,a.property_name,a.city2,a.country_code FROM `cms_hotel` h INNER JOIN amadeus_hotelbeds_property a on a.property_name like CONCAT('%', h.minimized_name ,'%') and a.`country_code`=h.`country_code` WHERE h.hid is NULL and a.address_line_1 like CONCAT('%', h.address ,'%') group by a.dupe_pool_id;";
//    $results = mysql_query($sql) or die( mysql_error());
//    $total =mysql_num_rows($results);
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $aid = $r['aid'];
//        $cnt = $r['cnt'];
//        if($cnt==1){
//            $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//            mysql_query($sql) or die( mysql_error());
//        }else if($cnt==2){
//            echo PHP_EOL;
//            echo "data2: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct2++;
//        }else if($cnt>2){
//            echo PHP_EOL;
//            echo "data3: ";
//            print_r($r);
//            echo PHP_EOL;
//            echo "cnt: ";
//            print_r($cnt);
//             $ct3++;
//        }else{
//            $ct++;
//        }
//     }

//    $sql="SELECT id,name as mname,zip_code,country_code FROM `cms_hotel` WHERE `hid` IS NULL and zip_code is not null";
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $mname = $r['mname'];
//        $zip_code = $r['zip_code'];
//        $country_code = $r['country_code'];
//        $mnamearr= explode(' ', $mname);
//        $mname = addslashes($mnamearr[0]).' ';
//        if(sizeof($mnamearr)>=2){
//            $mname .= addslashes($mnamearr[1]).'';
//            $sql="SELECT a.id as aid FROM amadeus_hrs_property a WHERE a.property_name like '%$mname%' and a.`country_code`='$country_code' and a.zip_code like '$zip_code' group by a.dupe_pool_id;";
//            $results1 = mysql_query($sql) or die( mysql_error());
//            $total =mysql_num_rows($results1);
//            if($total==1){
//                $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                $aid = $r1['aid'];
//                $sql="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=$aid WHERE h.id=$id;";
//                mysql_query($sql) or die( mysql_error());
//            }
//        }
//     }
//
//    $sql="SELECT id,name as mname,zip_code,country_code FROM `cms_hotel` WHERE `hid` IS NULL and zip_code is not null";
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $mname = $r['mname'];
//        $zip_code = $r['zip_code'];
//        $country_code = $r['country_code'];
//        $mnamearr= explode(' ', $mname);
//        $mname = addslashes($mnamearr[0]).' ';
//        if(sizeof($mnamearr)>=2){
//            $mname .= addslashes($mnamearr[1]).'';
//            $sql="SELECT a.id as aid FROM amadeus_gds_property a WHERE a.property_name like '%$mname%' and a.`country_code`='$country_code' and a.zip_code like '$zip_code' group by a.dupe_pool_id;";
//            $results1 = mysql_query($sql) or die( mysql_error());
//            $total =mysql_num_rows($results1);
//            if($total==1){
//                $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                $aid = $r1['aid'];
//                $sql="UPDATE `cms_hotel` as h SET h.src = 'gds',h.`hid`=$aid WHERE h.id=$id;";
//                mysql_query($sql) or die( mysql_error());
//            }
//        }
//     }
//
//    $sql="SELECT id,name as mname,zip_code,country_code FROM `cms_hotel` WHERE `hid` IS NULL and zip_code is not null";
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $mname = $r['mname'];
//        $zip_code = $r['zip_code'];
//        $country_code = $r['country_code'];
//        $mnamearr= explode(' ', $mname);
//        $mname = addslashes($mnamearr[0]).' ';
//        if(sizeof($mnamearr)>=2){
//            $mname .= addslashes($mnamearr[1]).'';
//            $sql="SELECT a.id as aid FROM amadeus_hotelbeds_property a WHERE a.property_name like '%$mname%' and a.`country_code`='$country_code' and a.zip_code like '$zip_code' group by a.dupe_pool_id;";
//            $results1 = mysql_query($sql) or die( mysql_error());
//            $total =mysql_num_rows($results1);
//            if($total==1){
//                $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//                $aid = $r1['aid'];
//                $sql="UPDATE `cms_hotel` as h SET h.src = 'hotelbeds',h.`hid`=$aid WHERE h.id=$id;";
//                mysql_query($sql) or die( mysql_error());
//            }
//        }
//     }

//    $prec = 10;
//    $sql="SELECT n.id,n.property_name,h.city,h.country_code,h.city_id,h.address,h.street,h.latitude,h.longitude FROM `amadeus_hotel_new1` as n inner join cms_hotel h on h.id=n.id WHERE n.amadeus_city_id=0 AND n.published=-2 group by n.id;";
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $latitude = (floor($r['latitude']*$prec)/$prec);
//        //$latitude = floor($r['latitude']).'.';
//        $longitude = (floor($r['longitude']*$prec)/$prec);
//        //$longitude = floor($r['longitude']).'.';
//        $sql="SELECT amadeus_city_id FROM `amadeus_hotel_new1` WHERE amadeus_city_id!=0 AND `latitude` LIKE '$latitude%' AND `longitude` LIKE '$longitude%' GROUP BY amadeus_city_id;";
//        $results1 = mysql_query($sql) or die( mysql_error());
//        $total =mysql_num_rows($results1);
//
//        if($total>0){
//            $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
//            $amadeus_city_id = $r1['amadeus_city_id'];
//            $sql2="UPDATE `amadeus_hotel_new1` SET `amadeus_city_id` = $amadeus_city_id WHERE id =$id;";
//            mysql_query($sql2) or die( mysql_error());
//        }else{
//            echo PHP_EOL;
//            echo "sql: ";
//            print_r($sql);
//            echo PHP_EOL;
//            echo "total: ";
//            print_r($total);
//        }
//     }

    

//    $sql="SELECT id,published,hid,src,count(hid) FROM `cms_hotel` WHERE hid is not null group by hid,src having count(hid)>1 order by count(hid) ASC;";
//    //$sql="SELECT id,published,hid,src,count(hid) FROM `cms_hotel` WHERE hid =170152 group by hid,src having count(hid)>1 order by count(hid) ASC;";
//    $results = mysql_query($sql) or die( mysql_error());
//     while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
//        $id = $r['id'];
//        $hid = $r['hid'];
//        $src = $r['src'];
//        $matchedarr = array();
//        $matchedarr_name = array();
//        $tbl= "amadeus_".$src."_property";
//        $sql1="SELECT h.id,h.name,h.minimized_name,h.published,h.street,a.address_line_1,a.property_name FROM `cms_hotel` h INNER JOIN $tbl a ON a.id=h.hid WHERE h.hid =$hid and h.src='$src' ORDER BY h.id ASC;";
//        $results1 = mysql_query($sql1) or die( mysql_error());
//        while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
//            $id1 = $r1['id'];
//            $name = addStringClean($r1['name']);
//            $published = $r1['published'];
//            $minimized_name = addStringClean($r1['minimized_name']);
//            $property_name = addStringClean($r1['property_name']);
//            if( $name==$property_name && $published==1){
//                $matchedarr[] = $id1;
//                $matchedarr_name[] = $name;
//            }
//        }
//        echo PHP_EOL;
//        echo "matchedarr: ";
//        print_r($matchedarr);
//        if( sizeof($matchedarr)==0 ){
//            $results1 = mysql_query($sql1) or die( mysql_error());
//            while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
//                $id1 = $r1['id'];
//                $address_line_1 = addStringClean($r1['address_line_1']);
//                $street = addStringClean($r1['street']);
//                $published = $r1['published'];
//                echo PHP_EOL;
//                echo "street: ";
//                print_r($street);
//                echo PHP_EOL;
//                echo "address_line_1: ";
//                print_r($address_line_1);
//                echo PHP_EOL;
//                echo "bool: ";
//                var_dump( ($street==$address_line_1) );
//                if( $street==$address_line_1 && $published==1){
//                    $matchedarr[] = $id1;
//                    $matchedarr_name[] = $street;
//                }
//            }
//        }
//        echo PHP_EOL;
//        echo "matchedarr: ";
//        print_r($matchedarr);
//
//        if( sizeof($matchedarr)==0 ){
//            $results1 = mysql_query($sql1) or die( mysql_error());
//            while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
//                $id1 = $r1['id'];
//                $name = addStringClean($r1['name']);
//                $published = $r1['published'];
//                $minimized_name = addStringClean($r1['minimized_name']);
//                $property_name = addStringClean($r1['property_name']);
//                if( (strrpos($name, $property_name) || strrpos($property_name, $name)) && $published==1 ){
//                    $matchedarr[] = $id1;
//                    $matchedarr_name[] = $name;
//                }
//            }
//        }
//        if( sizeof($matchedarr)==0 ){
//            $results1 = mysql_query($sql1) or die( mysql_error());
//            while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
//                $id1 = $r1['id'];
//                $name = addStringClean($r1['name']);
//                $published = $r1['published'];
//                $minimized_name = addStringClean($r1['minimized_name']);
//                $property_name = addStringClean($r1['property_name']);
//                if( (strrpos($minimized_name, $property_name) || strrpos($property_name, $minimized_name)) && $published==1 ){
//                    $matchedarr[] = $id1;
//                    $matchedarr_name[] = $name;
//                }
//            }
//        }
//        if( sizeof($matchedarr)==0 ){
//            $results1 = mysql_query($sql1) or die( mysql_error());
//            while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
//                $id1 = $r1['id'];
//                $address_line_1 = addStringClean($r1['address_line_1']);
//                $street = addStringClean($r1['street']);
//                $published = $r1['published'];
//                if( (strrpos($street, $address_line_1) || strrpos($address_line_1, $street)) && $published==1 ){
//                    $matchedarr[] = $id1;
//                    $matchedarr_name[] = $street;
//                }
//            }
//        }
//        if( sizeof($matchedarr)==2 ){
//            $name1=$matchedarr_name[0];
//            $name2=$matchedarr_name[1];
//            if($name1==$name2){
//                $id1s =$matchedarr[0];
//                $matchedarr = array($id1s);
//            }
//        }
//        if( sizeof($matchedarr)>=1 ){
//            $results1 = mysql_query($sql1) or die( mysql_error());
//            while($r1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
//                $id1 = $r1['id'];
//                if( !in_array( $id1 , $matchedarr ) ){
//                    $sql2="UPDATE `cms_hotel` as h SET h.src = 'hrs',h.`hid`=NULL WHERE h.id=$id1;";
//                    mysql_query($sql2) or die( mysql_error());
//                }
//            }
//        }
//     }
 
//echo PHP_EOL;
//
//echo "count > 2: ";
//print_r($ct3);
//echo PHP_EOL;
//echo "count = 2: ";
//print_r($ct2);
//echo PHP_EOL;
//echo "other: ";
//print_r($ct);
//echo PHP_EOL;


function addStringClean($str){
    $str1 = $str;
    $str1 = str_replace("'",'',$str1 );
    $str1 = addslashes($str1);
    $str1 = strtolower($str1);
    $str1 = str_replace('ô','o',$str1 );
    $str1 = str_replace('ó','o',$str1 );
    $str1 = str_replace(' and ',' ',$str1 );
    $str1 = str_replace('-',' ',$str1 );
    $str1 = str_replace('/',' ',$str1 );
    $str1 = str_replace(' & ',' ',$str1 );
    $str1 = str_replace('  ',' ',$str1 );
    $str1 = str_replace('  ',' ',$str1 );
    $str1 = str_replace('  ',' ',$str1 );
    $str1 = str_replace('  ',' ',$str1 );
    $str1 = str_replace(',','',$str1 );
    $str1 = str_replace('(','',$str1 );
    $str1 = str_replace(')','',$str1 );
    $str1 = str_replace('...','',$str1 );
    $str1 = trim($str1);
    return $str1;
}