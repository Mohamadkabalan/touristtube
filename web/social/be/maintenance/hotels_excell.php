<?php //header('Content-Type: text/html; charset=utf-8');?>
<pre><?php 
set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


mysql_connect('172.16.124.204','mysql_root','Mr4+%FINDZm,:AGL');
//mysql_connect('MYSQL','touristtube','sN2HxLDj89Dym9BR');
mysql_select_db($database_name);
    
$sql="SELECT * FROM cms_hotel WHERE published=1 ORDER BY id limit 20;";
//$sql="SELECT * FROM cms_hotel WHERE published=1 ORDER BY id ASC";
$results = mysql_query($sql) or die( mysql_error());
while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $id = $r['id'];
    $name = htmlEntityDecode($r['name']);
    $link = 'https://www.touristtube.com'.returnHotelDetailedLink($r['name'], $id);
    $img = '';
    
    $sql1="SELECT * FROM cms_hotel_image WHERE hotel_id=$id ORDER BY default_pic DESC LIMIT 1;";
    $results1 = mysql_query($sql1) or die( mysql_error());
    $total1 =mysql_num_rows($results1);
    if($total1==1){
        $r1 = mysql_fetch_array($results1, MYSQL_ASSOC);
        if( $r1['filename'] && $r1['filename']!=''){
            $img='https://www.touristtube.com/media/hotels/' . $r1['hotel_id'] . '/' . $r1['location'] . '/'.$r1['filename'];
        }
    }
    echo $id.','.$name.','.$link.','.$img;
    echo PHP_EOL;
}

function htmlEntityDecode($val, $stripslashe = 0) {
    $val = str_replace("’", "'", $val);
    if ($stripslashe == 1) {
        $val = stripslashes($val);
    }
    $val = html_entity_decode($val);
    if ($stripslashe == 0) {
        $val = preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $val);
        $val = stripslashes($val);
    }
    return $val;
}
function returnHotelDetailedLink($title, $id) {
    $titled = cleanTitleData($title);
    $titled = str_replace('-', '+', $titled);
    $lnk = '/hotel-details-' . $titled . '-' . $id;
    return $lnk;
}
function cleanTitleData($titles) {
    $titles = html_entity_decode($titles, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $title = str_replace("'", " ", $titles);
    $title = preg_replace('/\r\n|\r|\n/', '', $title);
    $title = trim($title);
    $title = str_replace("’", " ", $title);
    $title = str_replace("`", " ", $title);
    $title = str_replace('"', " ", $title);
    $title = str_replace(',', "+", $title);
    $title = str_replace('(', "+", $title);
    $title = str_replace(')', "+", $title);
    $title = str_replace('?', "+", $title);
    $title = str_replace('#', "", $title);
    $title = str_replace('!', "+", $title);
    $title = str_replace('}', "+", $title);
    $title = str_replace('.', "+", $title);
    $title = str_replace('/', "+", $title);
    $title = str_replace(' & ', '+', $title);
    $title = str_replace('&', '+and+', $title);
    $title = str_replace(">", "+", $title);
    $title = str_replace("<", "+", $title);
    $title = str_replace(' ', '+', $title);
    $title = str_replace('-', '+', $title);
    $title = str_replace("%+", "+", $title);
    $title = str_replace("%-", "-", $title);
    $title = str_replace("100%", "100", $title);
    $title = str_replace("%", "+", $title);
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $title;
}