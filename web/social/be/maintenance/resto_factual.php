<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);



$notfound0 =0;
$found0 =0;
$found1 =0;
$found2 =0;
$found3 =0;
$found4 =0;
$found5 =0;
$str_id = '';
$in_id ='';


$escape_id="'th','cn','eg','hk','jp','kr','tw','ru'";
mysql_query("SET NAMES utf8");
$sql="SELECT * FROM global_restaurants_location WHERE city_id=0 and state_code='' and ( locality<>'' or admin_region<>'' or region<>'' ) and country NOT IN ($escape_id) ORDER BY country ASC";
$sql="SELECT * FROM global_restaurants_location WHERE city_id=0 and country='us' and state_code='' and ( locality<>'' or admin_region<>'' or region<>'' ) and country NOT IN ($escape_id) ORDER BY country ASC";
//$sql .=" LIMIT 0,200";

$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
     //if ( $r['country'] =='at' || $r['country'] =='au' ){
        $r['locality1'] =$r['locality'];
        $r['locality1'] = str_replace('Upper ','',$r['locality1'] );
        $r['locality1'] = str_replace('High ','',$r['locality1'] );
        $r['locality1'] = str_replace('North ','',$r['locality1'] );
        $r['locality1'] = str_replace(' View','',$r['locality1'] );
        $r['locality1'] = str_replace(' Tree','',$r['locality1'] );
        $r['locality1'] = str_replace(' Grove','',$r['locality1'] );
        $r['locality1'] = str_replace(' Springs','',$r['locality1'] );
        $r['locality1'] = str_replace(' Meadow','',$r['locality1'] );
        $r['locality1'] = str_replace(' bei Wien','',$r['locality1'] );
        $r['locality1'] = str_replace(' am Inn','',$r['locality1'] );
        $r['locality1'] = str_replace(' an der Mühl','',$r['locality1'] );        
        $r['locality1'] = str_replace(' East','',$r['locality1'] );
        $r['locality1'] = str_replace(' West','',$r['locality1'] );
        $r['locality1'] = str_replace(' Valley','',$r['locality1'] );
        $r['locality1'] = str_replace(' Downs','',$r['locality1'] );
        $r['locality1'] = str_replace(' Gardens','',$r['locality1'] );
        $r['locality1'] = str_replace(' Mountain','',$r['locality1'] );
        $r['locality1'] = str_replace(' South','',$r['locality1'] );
        $r['locality1'] = str_replace(' Central','',$r['locality1'] );
        $r['locality1'] = str_replace(' Bay','',$r['locality1'] );
        $r['locality1'] = str_replace(' Park','',$r['locality1'] );
        $r['locality1'] = str_replace(' Hill','',$r['locality1'] );
        $r['locality1'] = str_replace(' Point','',$r['locality1'] );
        $r['locality1'] = str_replace(' Heights','',$r['locality1'] );
        $r['locality1'] = str_replace(' Ponds','',$r['locality1'] );
        $r['locality1'] = str_replace(' Beach','',$r['locality1'] );
        $r['locality1'] = str_replace(' City','',$r['locality1'] );
        $r['locality1'] = str_replace(' Ridge','',$r['locality1'] );
        $r['locality1'] = str_replace(' Island','',$r['locality1'] );
        $r['locality1'] = str_replace(' Creek','',$r['locality1'] );
        $r['locality1'] = str_replace(' North','',$r['locality1'] );
        $r['locality1'] = str_replace(' Falls','',$r['locality1'] );
        $r['locality1'] = str_replace(' Waters','',$r['locality1'] );
        $r['locality1'] = str_replace(' Plains','',$r['locality1'] );
        $r['locality1'] = str_replace(' County','',$r['locality1'] );
        $r['locality1'] = str_replace(' Vale','',$r['locality1'] );
        $r['locality1'] = str_replace('South ','',$r['locality1'] );
        $r['locality1'] = str_replace('Lake ','',$r['locality1'] );
        $r['locality1'] = str_replace('The ','',$r['locality1'] );
        $r['locality1'] = str_replace('St ','',$r['locality1'] );
        $r['locality1'] = str_replace('East ','',$r['locality1'] );
        $r['locality1'] = str_replace('West ','',$r['locality1'] );
        $r['locality1'] = str_replace('Mount ','',$r['locality1'] );
        $r['locality1'] = str_replace('Cape ','',$r['locality1'] );
        $r['locality1'] = str_replace('Villach-','',$r['locality1'] );        
        $r['locality1'] = str_replace('ß','ss',$r['locality1'] );
        $r['locality1'] = str_replace('ö','oe',$r['locality1'] );
        $r['locality1'] = str_replace('ü','ue',$r['locality1'] );
        $r['locality1'] = str_replace('ä','ae',$r['locality1'] );
        $r['locality1'] = str_replace('Ö','oe',$r['locality1'] );
        $r['locality2'] = str_replace('-','',$r['locality1'] );
        $r['locality1'] = str_replace('-',' ',$r['locality1'] );
     //}
     if ( $r['admin_region'] !='' ){
         $r['state'] = str_replace(' Metropolitan','',$r['admin_region'] );
     }else{
         $r['state'] = str_replace(' Metropolitan','',$r['region'] );
         if ( $r['country'] =='au' ){
            $r['state'] = str_replace('NSW','New South Wales',$r['region'] );
            $r['state'] = str_replace('SA','South Australia',$r['state'] );
            $r['state'] = str_replace('QLD','Queensland',$r['state'] );
            $r['state'] = str_replace('WA','Western Australia',$r['state'] );
            $r['state'] = str_replace('TAS','Tasmania',$r['state'] );
            $r['state'] = str_replace('VIC','Victoria',$r['state'] );
            $r['state'] = str_replace('ACT','Australian Capital Territory',$r['state'] );
            $r['state'] = str_replace('NT','Northern Territory',$r['state'] );
            $r['region'] = $r['state'];
         }else if ( $r['country'] =='us' ){
            $r['state'] = str_replace('AK','Alaska',$r['region'] );
            $r['state'] = str_replace('AL','Alabama',$r['state'] );
            $r['state'] = str_replace('AR','Arkansas',$r['state'] );
            $r['state'] = str_replace('AZ','Arizona',$r['state'] );
            $r['state'] = str_replace('CA','California',$r['state'] );
            $r['state'] = str_replace('CO','Colorado',$r['state'] );
            $r['state'] = str_replace('CT','Connecticut',$r['state'] );
            $r['state'] = str_replace('DC','Columbia',$r['state'] );
            $r['state'] = str_replace('DE','Delaware',$r['state'] );
            $r['state'] = str_replace('FL','Florida',$r['state'] );
            $r['state'] = str_replace('GA','Georgia',$r['state'] );
            $r['state'] = str_replace('HI','Hawaii',$r['state'] );
            $r['state'] = str_replace('IA','Iowa',$r['state'] );
            $r['state'] = str_replace('ID','Idaho',$r['state'] );
            $r['state'] = str_replace('IL','Illinois',$r['state'] );
            $r['state'] = str_replace('IN','Indiana',$r['state'] );
            $r['state'] = str_replace('KS','Kansas',$r['state'] );
            $r['state'] = str_replace('KY','Kentucky',$r['state'] );
            $r['state'] = str_replace('LA','Louisiana',$r['state'] );
            $r['state'] = str_replace('MA','Massachusetts',$r['state'] );
            $r['state'] = str_replace('MD','Maryland',$r['state'] );
            $r['state'] = str_replace('ME','Maine',$r['state'] );
            $r['state'] = str_replace('MI','Michigan',$r['state'] );
            $r['state'] = str_replace('MN','Minnesota',$r['state'] );
            $r['state'] = str_replace('MO','Missouri',$r['state'] );
            $r['state'] = str_replace('MS','Mississippi',$r['state'] );
            $r['state'] = str_replace('MT','Montana',$r['state'] );
            $r['state'] = str_replace('NC','North Carolina',$r['state'] );
            $r['state'] = str_replace('ND','North Dakota',$r['state'] );
            $r['state'] = str_replace('NE','Nebraska',$r['state'] );
            $r['state'] = str_replace('NH','New Hampshire',$r['state'] );
            $r['state'] = str_replace('NJ','New Jersey',$r['state'] );
            $r['state'] = str_replace('NM','New Mexico',$r['state'] );
            $r['state'] = str_replace('NV','Nevada',$r['state'] );
            $r['state'] = str_replace('NY','New York',$r['state'] );
            $r['state'] = str_replace('OH','Ohio',$r['state'] );
            $r['state'] = str_replace('OK','Oklahoma',$r['state'] );
            $r['state'] = str_replace('OR','Oregon',$r['state'] );
            $r['state'] = str_replace('PA','Pennsylvania',$r['state'] );
            $r['state'] = str_replace('RI','Rhode Island',$r['state'] );
            $r['state'] = str_replace('SC','South Carolina',$r['state'] );
            $r['state'] = str_replace('SD','South Dakota',$r['state'] );
            $r['state'] = str_replace('TN','Tennessee',$r['state'] );
            $r['state'] = str_replace('TX','Texas',$r['state'] );
            $r['state'] = str_replace('UT','Utah',$r['state'] );
            $r['state'] = str_replace('VA','Virginia',$r['state'] );
            $r['state'] = str_replace('VT','Vermont',$r['state'] );
            $r['state'] = str_replace('WA','Washington',$r['state'] );
            $r['state'] = str_replace('WI','Wisconsin',$r['state'] );
            $r['state'] = str_replace('WV','West Virginia',$r['state'] );
            $r['state'] = str_replace('WY','Wyoming',$r['state'] );
            $r['region'] = $r['state'];
         }
     }
     if ( $r['admin_region'] !='' ){
         if ( $r['locality'] !='' ){
             $sql1= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , addslashes($r['admin_region']), addslashes($r['locality']), addslashes($r['locality1']), addslashes($r['locality2']) );
         }else if ( $r['region'] !='' ){
             $sql1= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , addslashes($r['admin_region']), addslashes($r['region']) );
         }else{
             $sql1= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.accent like '%s' ) ",$r['country'] , addslashes($r['admin_region']), addslashes($r['admin_region']) );
         }         
        $results1 = mysql_query($sql1) or die( mysql_error());
        $num =mysql_num_rows($results1);
        if($num==1 ) {            
            $cdata =mysql_fetch_array($results1);
            $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
            mysql_query($sqr);
            $found0 ++;                 
        }else {
            if ( $r['locality'] !='' ){
                $sql2= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , '%'.addslashes($r['admin_region']).'%', '%'.addslashes($r['locality']).'%', '%'.addslashes($r['locality1']).'%', '%'.addslashes($r['locality2']).'%');
            }else if ( $r['region'] !='' ){
                $sql2= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , '%'.addslashes($r['admin_region']).'%', '%'.addslashes($r['region']).'%' );
            }else{
                $sql2= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.accent like '%s' ) ",$r['country'] , '%'.addslashes($r['admin_region']).'%', '%'.addslashes($r['admin_region']).'%' );
            }           
           $results1 = mysql_query($sql2) or die( mysql_error());
           $num =mysql_num_rows($results1);
           if($num==1 ) {
                $cdata =mysql_fetch_array($results1);
                $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                mysql_query($sqr);
               $found1 ++;             
           }else {
                $sql3= sprintf("select * FROM webgeocities , states  WHERE webgeocities.country_code =states.country_code AND webgeocities.state_code = states.state_code AND  webgeocities.country_code = '%s'  AND states.state_name LIKE '%s'  AND (webgeocities.name LIKE '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , addslashes($r['state']), addslashes($r['locality']), addslashes($r['locality1']), addslashes($r['locality2']) );
                       
                $results1 = mysql_query($sql3) or die( mysql_error());
                $num =mysql_num_rows($results1);
                if($num==1 ) {
                     $cdata =mysql_fetch_array($results1);
                     $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                     mysql_query($sqr);
                    $found1 ++;             
                }else {
                     $sql4= sprintf("select * FROM webgeocities , states  WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code AND  webgeocities.country_code = '%s'  AND states.state_name LIKE '%s'  AND (webgeocities.name LIKE '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s') ",$r['country'] , '%'.addslashes($r['state']).'%', '%'.addslashes($r['locality']).'%', '%'.addslashes($r['locality1']).'%', '%'.addslashes($r['locality2']).'%' ); 
                     
                    $results1 = mysql_query($sql4) or die( mysql_error());
                    $num =mysql_num_rows($results1);
                    if($num==1 ) {
                         $cdata =mysql_fetch_array($results1);
                         $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                         mysql_query($sqr);
                        $found1 ++;             
                    }else {
                         $sql4= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , addslashes($r['admin_region']), addslashes($r['region']) );   
                        $results1 = mysql_query($sql4) or die( mysql_error());
                        $num =mysql_num_rows($results1);
                        if($num==1 ) {
                             $cdata =mysql_fetch_array($results1);
                             $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                             mysql_query($sqr);
                            $found1 ++;             
                        }else {
                             $notfound0 ++;
                             print_r($r);
                             echo PHP_EOL;
                             echo $sql1;
                             echo PHP_EOL;
                             echo $sql2;
                             echo PHP_EOL;
                             echo $sql3;
                             echo PHP_EOL;
                             echo $sql4;
                             echo PHP_EOL; 
                        }
                    }
                }
           }
        } 
    }else{
         if ( $r['locality'] !='' ){
             $sql1= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , addslashes($r['locality']), addslashes($r['locality1']), addslashes($r['locality2']) );
         }else {
             $sql1= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , addslashes($r['region']), addslashes($r['region']) );
         }        
        $results1 = mysql_query($sql1) or die( mysql_error());
        $num =mysql_num_rows($results1);
        if($num==1 ) {            
            $cdata =mysql_fetch_array($results1);
            $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
            mysql_query($sqr);
            $found0 ++;                 
        }else {
            if ( $r['locality'] !='' ){
                $sql2= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , '%'.addslashes($r['locality']).'%', '%'.addslashes($r['locality1']).'%', '%'.addslashes($r['locality2']).'%' );
            }else {
                $sql2= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , '%'.addslashes($r['region']).'%', '%'.addslashes($r['region']).'%' );
            }         
           $results1 = mysql_query($sql2) or die( mysql_error());
           $num =mysql_num_rows($results1);
           if($num==1 ) {
                $cdata =mysql_fetch_array($results1);
                $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                mysql_query($sqr);
               $found1 ++;             
           }else {
                $sql3= sprintf("select * FROM webgeocities , states  WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code AND  webgeocities.country_code = '%s'  AND states.state_name LIKE '%s'  AND (webgeocities.name LIKE '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s') ",$r['country'] , addslashes($r['state']), addslashes($r['locality']),addslashes($r['locality1']), addslashes($r['locality2']) );
                       
                $results1 = mysql_query($sql3) or die( mysql_error());
                $num =mysql_num_rows($results1);
                if($num==1 ) {
                     $cdata =mysql_fetch_array($results1);
                     $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                     mysql_query($sqr);
                    $found1 ++;             
                }else {
                     $sql4= sprintf("select * FROM webgeocities , states  WHERE webgeocities.country_code =states.country_code   AND webgeocities.state_code = states.state_code AND  webgeocities.country_code = '%s'  AND states.state_name LIKE '%s'  AND (webgeocities.name LIKE '%s' OR webgeocities.name like '%s' OR webgeocities.name like '%s') ",$r['country'] , '%'.addslashes($r['state']).'%', '%'.addslashes($r['locality']).'%', '%'.addslashes($r['locality1']).'%', '%'.addslashes($r['locality2']).'%' ); 
                     
                    $results1 = mysql_query($sql4) or die( mysql_error());
                    $num =mysql_num_rows($results1);
                    if($num==1 ) {
                         $cdata =mysql_fetch_array($results1);
                         $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                         mysql_query($sqr);
                        $found1 ++;             
                    }else {
                         $sql4= sprintf("select * FROM webgeocities WHERE webgeocities.country_code = '%s' AND ( webgeocities.name like '%s' OR webgeocities.name like '%s' ) ",$r['country'] , addslashes($r['region']), addslashes($r['region']) );
                        $results1 = mysql_query($sql4) or die( mysql_error());
                        $num =mysql_num_rows($results1);
                        if($num==1 ) {
                             $cdata =mysql_fetch_array($results1);
                             $sqr= "UPDATE `global_restaurants_location` SET `city_id`=".$cdata['id']." WHERE id=".$r['id'];
                             mysql_query($sqr);
                            $found1 ++;             
                        }else {
                             $notfound0 ++;
                             print_r($r);
                             echo PHP_EOL;
                             echo $sql1;
                             echo PHP_EOL;
                             echo $sql2;
                             echo PHP_EOL;
                             echo $sql3;
                             echo PHP_EOL;
                             echo $sql4;
                             echo PHP_EOL; 
                        }
                    }
                }
           }
        } 
    }    
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
echo "ID:".$str_id;
echo PHP_EOL;