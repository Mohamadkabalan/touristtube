<?php
$path = "../";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" =>0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

//$link = "http://para-tube/ttback/uploads/";
$link = ReturnLink('media/discover').'/';

$user_id = userGetID();

$user_is_logged=0;
if(userIsLogged()){
    $user_is_logged=1;
}

//$from = xss_sanitize(@$_POST['from']);
//$to = xss_sanitize(@$_POST['to']);
//$restaurant = intval(@$_POST['restaurant']);
$from = $request->request->get('from', '');
$to = $request->request->get('to', '');
$restaurant = intval($request->request->get('restaurant', 0));

$showedPerPage = 18;
$str='';
$restaurants = getRestaurantMedia($restaurant,$showedPerPage,$to,$CONFIG['server']['root']);

//return images for a specific restaurant
function getRestaurantMedia($txt_id,$showedPerPage,$to,$spath){
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();  
    global $link;
    $pg = ($to-1) * $showedPerPage;
//    $query_restaurants = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id ORDER BY default_pic DESC LIMIT $pg, 18";
    $query_restaurants = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id ORDER BY default_pic DESC LIMIT :Pg, 18";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $params[] = array(  "key" => ":Pg",
                        "value" =>$pg,
                        "type" =>"::PARAM_INT");
    $media_restaurants = array();
    $select = $dbConn->prepare($query_restaurants);
    PDO_BIND_PARAM($select,$params);
    $ret_restaurants    = $select->execute();
//    $ret_restaurants = db_query($query_restaurants);
    $row = $select->fetchAll();
    foreach($row as $row_item){
//    while($row = db_fetch_array($ret_restaurants)){
        $imgArr = array();
        $imgArr['img'] = $link.$row_item['img'];
        $imgArr['thumb'] = $link."thumb/".$row_item['img'];

        $dims = imageGetDimensions( $spath . 'media/discover/' . $row_item['img']);
        //$dims = imageGetDimensions( $link. $row['img']);
        
        $width = $dims['width'];
        $height = $dims['height'];
        //for large size
        $new_height = 256;
        $scaleWidth= 476/$width;
        $scaleHeight= 256/$height;
        if($scaleWidth>$scaleHeight){
            $new_width = $width*$scaleWidth;
            $new_height = $height*$scaleWidth;
        }else{
            $new_width = $width*$scaleHeight;
            $new_height = $height*$scaleHeight;
        }
        $dataw=$new_width;
        $datah = $new_height;
        //for medium size
        $new_height2 = 112;
        $scaleWidth2= 219/$width;
        $scaleHeight2= 112/$height;
        if($scaleWidth2>$scaleHeight2){
            $new_width2 = $width*$scaleWidth2;
            $new_height2 = $height*$scaleWidth2;
        }else{
            $new_width2 = $width*$scaleHeight2;
            $new_height2 = $height*$scaleHeight2;
        }

        $imgArr['imgWidth'] = $dataw;
        $imgArr['imgHeight'] = $datah;
        $imgArr['style'] = 'style="width:'.round($dataw).'px;height:'.round($datah).'px"';
        $imgArr['mediumStyle'] = 'style="width:'.round($new_width2).'px;height:'.round($new_height2).'px"';
        $media_restaurants[] = $imgArr;
    }
    return $media_restaurants;
}
function getRestaurantMediaNb($txt_id){
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array();  
    $nb = '';
//    $query_restaurants = "SELECT count(*) FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id ORDER BY default_pic DESC";
    $query_restaurants = "SELECT count(*) FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id ORDER BY default_pic DESC";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $select = $dbConn->prepare($query_restaurants);
    PDO_BIND_PARAM($select,$params);
    $ret_restaurants    = $select->execute();
//    $ret_restaurants = db_query($query_restaurants);
//    $row = db_fetch_array($ret_restaurants);
    $row = $select->fetch();
    $nb = $row[0];
    return $nb;
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <end>
}
function navigatePhoto($txt_id,$page){
    $arr = array();
    $showedPerPage = 18;
    $total = getRestaurantMediaNb($txt_id);
    $totalPage = ceil($total / $showedPerPage);
    //next
    if($totalPage == $page){
        $arr[1] = '';
    }else if($totalPage >= ($page+1)){
        $arr[1] = ($page+1);
    }
    //prev
    if($page == 1){
        $arr[0] = '';
    }else{
        $arr[0] = $page-1;
    }
    return $arr;
}
foreach($restaurants as $h){
    $str .= '<img src="'.$h['thumb'].'" data-value="'.$h['img'].'" data-w="'.$h['imgWidth'].'" data-h="'.$h['imgHeight'].'" alt="" width="36" height="36" class="smallImg"/>';
}
$mediaNav = navigatePhoto($restaurant,$to);
$Result["mediaPrev"] = $mediaNav[0];
$Result["mediaNext"] = $mediaNav[1];
$Result['media'] = $str;
echo json_encode( $Result );