<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
//mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);

function fnumber_format($number, $decimals=2) {
        return substr($number, 0, ((strpos($number, '.')+1)+$decimals));
}

$notfound0 =0;
$found0 =0;
$found1 =0;
$found2 =0;
$found3 =0;
$found4 =0;
$found5 =0;
$str_id = '';
$in_id ='1,2,3,5,7,8,9,14,21,27,33,35,53,54,55,77,87,92,1324,1508,1612,1678,4028,5168,5295,8956,9071,9922,10375,12420,13378,13762,15134,16739,16922,18439,19086,19329,21034,21623,21747,21812,22026,23783,24571,24946,26074,26078,26984,27572,33909,34505,40807,40836,40855,40873,174851,174852,174853,174854,174855,174865,174884,177556,177557,177558,177559,177560,177561,177562,177571,177572,177573,177574,177575,177576,177577,177578,177579,177580,177581,177582,177583,177584,177585,177586,177587,177588,177589,177590,177601,177602,177603,177604,177605,177606,177607,177608,177609,177610,177611,177612,177613,177614,177615,177616,177617,177618,177619,177620,177621,177623,177624,177625,177626,177627,177628,177629,177630,177631,177632,177633,177634,177635,177636,177637,177638,177639,177640,177641,177642,177643,177644,177645,177646,177647,177648,177649,177650,177651,177652,177653,177654,177655,177657,177658,177659,177660,177661,177662,8,35,1612,1678,4028,5295,8956,9071,12420,16739,16922,19329,21034,21623,21747,21812,23783,24571,24946,26074,26078,26984,27572,33909,34505,40836,40855,40873,174851,174853,174854,177556,177557,177558,177571,177572,177573,177574,177575,177576,177577,177578,177579,177580,177581,177582,177583,177584,177585,177586,177587,177588,177589,177590,177601,177602,177603,177604,177605,177606,177607,177608,177609,177610,177611,177612,177613,177615,177616,177617,177620,177623,177624,177625,177627,177628,177629,177635,177638,177641,177646,177647,177650,177651,177658';


//$escape_id='21034,21623,21747,21812,23783,24571,24946,26074,26984,177601,177603,177605,27,77,174854,177560,177562,177616,177617,177618,177620,177621,177623,177624,177625,177626,177627,177628,177629,177630,177631,177633,177636,177638,177639,177640,177644,177645,177646,177647,177648,177651,177652,177654,177582,177584,177585,177586,177588,177590,34505,40807,40836,40855,40873,177556,177557,177609,177610,177611,177613,177571,177572,177574,177575,177578,177580,177602,22026,19329,27572,177604,26078,2,174851,174852,174853,18439,14,16922,15134,55,87,9071,19086,177559,177561,9922,177650,174855,1,3,5,7,8,9,21,33,1324,53,54,13378,12420,1678,5295,4028,177581,35,5168,1612,92,177614,177615,177619,16739,10375,13762,177632,177634,177635,1508,177637,177641,177642,177643,177649,177653,8956,174865,174884,177583,177587,177589,177573,177576,177577,177579,33909,177558,177606,177607';

$escape_id='0';
$id_s='';

$sql="SELECT id,name,country,city_id,longitude,latitude,address FROM discover_restaurants WHERE id NOT IN($escape_id) AND id IN($in_id) ORDER BY country ASC";

$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $r['name1'] = str_replace(' Restaurant ','',$r['name'] );
    $r['name1'] = str_replace('Restaurant ','',$r['name1'] );
    $r['name1'] = str_replace('Company ','',$r['name1'] );
    $r['name1'] = str_replace(' Company','',$r['name1'] );
    $r['name1'] = str_replace(' The','',$r['name1'] );
    $r['name1'] = str_replace('St. ','',$r['name1'] );
    $r['name1'] = str_replace(' International','',$r['name1'] );
    $r['name1'] = str_replace(' Pizzeria','',$r['name1'] );
    $r['longitude'] = fnumber_format($r['longitude'], 3);
    $r['latitude'] = fnumber_format($r['latitude'], 3);
    $r['longitude1'] = fnumber_format($r['longitude'], 2);
    $r['latitude1'] = fnumber_format($r['latitude'], 2);
    $r['longitude2'] = fnumber_format($r['longitude'], 1);
    $r['latitude2'] = fnumber_format($r['latitude'], 1);
    $sql1= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], addslashes($r['name']), $r['longitude'].'%', $r['latitude'].'%' );
    $results1 = mysql_query($sql1) or die( mysql_error());
    $num =mysql_num_rows($results1);
    if($num==1 ) {
        $cdata =mysql_fetch_array($results1);
        $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
        mysql_query($sqr);
        $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
        mysql_query($sqr);
        $found0 ++;                 
    }else{
        $sql12= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], '%'.addslashes($r['name1']).'%', $r['longitude'].'%', $r['latitude'].'%' );
        $results1 = mysql_query($sql12) or die( mysql_error());
        $num =mysql_num_rows($results1);
        if($num==1 ) {   
            $cdata =mysql_fetch_array($results1);
            $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
            mysql_query($sqr);
            $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
            mysql_query($sqr);    
            $found1 ++;
        }else{
            $sql12= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], '%'.addslashes($r['name']).'%', $r['longitude'].'%', $r['latitude'].'%' );
            $results1 = mysql_query($sql12) or die( mysql_error());
            $num =mysql_num_rows($results1);
            if($num==1 ) {
                $cdata =mysql_fetch_array($results1);
                $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                mysql_query($sqr);
                $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                mysql_query($sqr);
                $found1 ++;
            }else{
                $sql13= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], addslashes($r['name']), $r['longitude1'].'%', $r['latitude1'].'%' );
                $results1 = mysql_query($sql13) or die( mysql_error());
                $num =mysql_num_rows($results1);
                if($num==1 ) { 
                    $cdata =mysql_fetch_array($results1);
                    $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                    mysql_query($sqr);
                    $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                    mysql_query($sqr);
                    $found1 ++;
                }else{
                    $sql14= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], '%'.addslashes($r['name']).'%', $r['longitude1'].'%', $r['latitude1'].'%' );
                    $results1 = mysql_query($sql14) or die( mysql_error());
                    $num =mysql_num_rows($results1);
                    if($num==1 ) { 
                        $cdata =mysql_fetch_array($results1);
                        $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                        mysql_query($sqr);
                        $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                        mysql_query($sqr);
                        $found1 ++;
                    }else{
                        $sql15= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], addslashes($r['name']), $r['longitude2'].'%', $r['latitude2'].'%' );
                        $results1 = mysql_query($sql15) or die( mysql_error());
                        $num =mysql_num_rows($results1);
                        if($num==1 ) {
                            $cdata =mysql_fetch_array($results1);
                            $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                            mysql_query($sqr);
                            $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                            mysql_query($sqr);
                            $found1 ++;
                        }else{
                            $sql16= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], '%'.addslashes($r['name']).'%', $r['longitude2'].'%', $r['latitude2'].'%' );
                            $results1 = mysql_query($sql16) or die( mysql_error());
                            $num =mysql_num_rows($results1);
                            if($num==1 ) {
                                $cdata =mysql_fetch_array($results1);
                                $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                mysql_query($sqr);
                                $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                mysql_query($sqr);
                                $found1 ++;
                            }else{
                                $sql17= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND longitude like '%s' AND latitude like '%s' LIMIT 0,3",$r['country'], $r['longitude'].'%', $r['latitude'].'%' );
                                $results1 = mysql_query($sql17) or die( mysql_error());
                                $num =mysql_num_rows($results1);
                                if($num==1 ) { 
                                    $cdata =mysql_fetch_array($results1);
                                    $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                    mysql_query($sqr);
                                    $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                    mysql_query($sqr);
                                    $found3 ++;
                                }else{
                                    $sql18= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' AND address like '%s' LIMIT 0,3",$r['country'], addslashes($r['name']) , $r['longitude'].'%', $r['latitude'].'%', addslashes('%'.$r['address'].'%') );
                                    $results1 = mysql_query($sql18) or die( mysql_error());
                                    $num =mysql_num_rows($results1);
                                    
                                    if($num==1 ) { 
                                        $cdata =mysql_fetch_array($results1);
                                        $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                        mysql_query($sqr);
                                        $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                        mysql_query($sqr);
                                    }else{
                                        $sql19= sprintf("select id,longitude,latitude FROM global_restaurants WHERE published=1 and country = '%s' AND name like '%s' AND longitude like '%s' AND latitude like '%s' AND address like '%s' LIMIT 0,3",$r['country'], addslashes($r['name1']) , $r['longitude'].'%', $r['latitude'].'%', addslashes('%'.$r['address'].'%') );
                                        $results1 = mysql_query($sql19) or die( mysql_error());
                                        $num =mysql_num_rows($results1);
                                        if($num==1 ) { 
                                            $cdata =mysql_fetch_array($results1);
                                            $sqr= "UPDATE `discover_restaurants_images` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                            mysql_query($sqr);
                                            $sqr= "UPDATE `discover_restaurants_reviews` SET `restaurant_id`=".$cdata['id']." WHERE old_id=".$r['id'];
                                            mysql_query($sqr);
                                        }else{
                                            if($num>1 ) {        
                                                $found5 ++;
                                            }
                                            $notfound0 ++;
                                            print_r($r);
                                            echo PHP_EOL;
                                            echo $sql1;
                                            echo PHP_EOL;
                                            echo $sql12;
                                            echo PHP_EOL;
                                            echo $sql13;
                                            echo PHP_EOL;
                                            echo $sql14;
                                            echo PHP_EOL;
                                            echo $sql15;
                                            echo PHP_EOL;
                                            echo $sql16;
                                            echo PHP_EOL;
                                            echo $sql17;
                                            echo PHP_EOL;
                                            echo $sql18;
                                            echo PHP_EOL;
                                            echo $sql19;
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
echo "ID:".$id_s;
echo PHP_EOL;