<pre><?php
if (php_sapi_name() != 'cli')
	exit;

$base_scripts_dir = __DIR__;

$include_dir = dirname(dirname(dirname($base_scripts_dir))).'/inc';

include_once($include_dir.'/config.php');

$conn = mysqli_connect($CONFIG['db']['host'], $CONFIG['db']['user'], $CONFIG['db']['pwd'], $CONFIG['db']['name']);
$conn->set_charset("utf8");

$couters =91;
//$couters =0;
$ct=0;
$ct2=0;
$ct3=0;
$limit=2500;

while($couters<111){
    $skval =$couters*$limit;    
//    $sql="SELECT id, name, latitude, longitude,country_code,city FROM cms_hotel WHERE published=1 AND longitude <> 0 AND latitude <> 0 LIMIT $skval, $limit;";
    $sql="SELECT id, name, latitude, longitude, country_code, city, city_id FROM cms_hotel WHERE longitude <> 0 AND latitude <> 0 LIMIT $skval, $limit;";
    $couters++;
    $results = mysqli_query( $conn, $sql) or die( mysqli_error());
    $total = mysqli_num_rows($results);
     while($r = mysqli_fetch_array($results, MYSQLI_ASSOC)) {    
        $lat = $r['latitude'];
        $log = $r['longitude'];
        $countryCode = $r['country_code'];
        $city = addslashes($r['city']);
        $city_id = intval($r['city_id']);
        $hotelName = addslashes($r['name']);
        if( $log && $lat && $lat<90 && $lat>-90 && $log<180 && $log>-180 ){
//            $sql="SELECT id FROM discover_hotels WHERE published=1 AND hotel_id=0 AND latitude>-90 AND latitude<90 AND longitude>-180 AND longitude<180 AND longitude <> 0 AND latitude <> 0 AND ST_Distance_Sphere(POINT(longitude, latitude), POINT(".$log.", ".$lat.")) < 50 AND '".$hotelName."' like CONCAT('%', hotelName ,'%') LIMIT 0, 3;";
            $sql="SELECT id FROM discover_hotels WHERE published=1 AND hotel_id=0 AND latitude>-90 AND latitude<90 AND longitude>-180 AND longitude<180 AND longitude <> 0 AND latitude <> 0 AND ST_Distance_Sphere(POINT(longitude, latitude), POINT(".$log.", ".$lat.")) < 50 AND hotelName like '%$hotelName%' LIMIT 0, 3;";
        }else{
            continue;
        }
//        $sql="SELECT id FROM discover_hotels WHERE published=1 AND hotel_id=0 AND longitude <> 0 AND latitude <> 0 AND hotelName like '%".$hotelName."%' AND countryCode='$countryCode' AND cityName like '%".$city."%' LIMIT 0, 3;";
//        $sql="SELECT id FROM discover_hotels WHERE published=1 AND hotel_id=0 AND longitude <> 0 AND latitude <> 0 AND hotelName like '%".$hotelName."%' AND countryCode='$countryCode' AND city_id =$city_id LIMIT 0, 3;";
        $results1 = mysqli_query( $conn, $sql) or die( mysqli_error());
        $total1 =mysqli_num_rows($results1);
        
        if($total1==1){
            $r1 = mysqli_fetch_array($results1, MYSQLI_ASSOC);
            $sql="UPDATE `discover_hotels` SET `hotel_id`=".$r['id']." WHERE id=".$r1['id']."";            
            mysqli_query( $conn, $sql) or die( mysqli_error());  
//            echo PHP_EOL;
//            echo "total1: ".$total1;
        }else if($total1==2){
             $ct2++;   
        }else if($total1>2){
             $ct3++;   
        }else{
            $ct++;
        }
     }
//    echo PHP_EOL;
//    echo "ct2: ".$ct2;
//    echo PHP_EOL;
//    echo "ct3: ".$ct3;
    echo PHP_EOL;
    echo "ct: ".$ct;
    echo PHP_EOL;
    echo "step: ";
    print_r($couters);
}


//$sql="SELECT d.id ,d.hotelName,d.city_id ,d.cityName,d.latitude,d.longitude, h.id as id2,h.name,h.city_id as city_id2,h.city,h.latitude as latitude2,h.longitude as longitude2 FROM `discover_hotels` as d inner join cms_hotel as h on d.`hotelName`=h.`name` and d.`countryCode`=h.`country_code` WHERE d.hotel_id=0 "; //LIMIT 0, 100

/*while($couters<101){
    $skval =$couters*$limit;
    $sql="SELECT d.id, d.hotelName, d.city_id, d.cityName, d.latitude, d.longitude,d.countryCode FROM discover_hotels AS d WHERE d.hotel_id=0 AND d.published=1 AND d.longitude <> 0 AND d.latitude <> 0 LIMIT $skval, $limit;";
    $couters++;
    $results = mysqli_query( $conn, $sql) or die( mysqli_error());
    $total =mysqli_num_rows($results);
     while($r = mysqli_fetch_array($results, MYSQLI_ASSOC)) {    
        $lat = $r['latitude'];
        $log = $r['longitude'];
        $countryCode = $r['countryCode'];
        $cityName = addslashes($r['cityName']);
        $hotelName = addslashes($r['hotelName']);
//        $sql="SELECT h.id FROM cms_hotel AS h WHERE h.published=1 AND h.longitude <> 0 AND h.latitude <> 0 AND ST_Distance_Sphere(POINT(h.longitude, h.latitude), POINT(".$log.", ".$lat.")) < 50 AND '".$hotelName."' like CONCAT('%', h.name ,'%') LIMIT 0, 2;";
//        $sql="SELECT h.id FROM cms_hotel AS h WHERE h.published=1 AND h.longitude <> 0 AND h.latitude <> 0 AND h.name like '%".$hotelName."%' AND h.country_code='$countryCode' AND h.city like '%".$cityName."%' LIMIT 0, 3;";
        $sql="SELECT h.id FROM cms_hotel AS h WHERE h.name='$hotelName' AND h.country_code='$countryCode' AND h.longitude <> 0 AND h.latitude <> 0 AND ST_Distance_Sphere(POINT(h.longitude, h.latitude), POINT(".$log.", ".$lat.")) < 16000 LIMIT 0, 3;";
        $results1 = mysqli_query( $conn, $sql) or die( mysqli_error());
        $total1 =mysqli_num_rows($results1);
        if($total1==1){
            $r1 = mysqli_fetch_array($results1, MYSQLI_ASSOC);
            $sql="UPDATE `discover_hotels` SET `hotel_id`=".$r1['id']." WHERE id=".$r['id']."";
            mysqli_query( $conn, $sql) or die( mysqli_error());        
        }else if($total1==2){
            $ct2++;   
        }else if($total1>2){
            $ct3++;   
        }else{
            $ct++;
        }
     }
    echo PHP_EOL;
    echo "step: ";
    print_r($couters);
}*/
 
echo PHP_EOL;

echo "count > 2: ";
print_r($ct3);
echo PHP_EOL;
echo "count = 2: ";
print_r($ct2);
echo PHP_EOL;
echo "other: ";
print_r($ct);
echo PHP_EOL;