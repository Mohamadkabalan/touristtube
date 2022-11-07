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
//$hotel = intval(@$_POST['hotel']);
$from = $request->request->get('from', '');
$to = $request->request->get('to', '');
$hotel = intval($request->request->get('hotel', 0));
$showedPerPage = 29;
$str='';
$hotels = getHotelMedia($hotel,$showedPerPage,$to,$CONFIG['server']['root']);

//return images for a specific hotel
function getHotelMedia($txt_id,$showedPerPage,$to,$spath){
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
    global $dbConn;
    $params = array(); 
    global $link;
    $pg = ($to-1) * $showedPerPage;
//    $query_hotels = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $txt_id ORDER BY default_pic DESC LIMIT $pg, $showedPerPage";
    $query_hotels = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Txt_id ORDER BY default_pic DESC LIMIT :Pg, :ShowedPerPage";
    $media_hotels = array();
//    $ret_hotels = db_query($query_hotels);
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $params[] = array(  "key" => ":Pg",
                        "value" =>$pg,
                        "type" =>"::PARAM_INT");
    $params[] = array(  "key" => ":ShowedPerPage",
                        "value" =>$showedPerPage,
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    while($row = db_fetch_array($ret_hotels)){
    foreach($row as $row_item){
        $imgArr = array();
        $imgArr['img'] = $link.$row_item['img'];
        $imgArr['thumb'] = $link."thumb/".$row_item['img'];
        $dims = imageGetDimensions( $spath . 'media/discover/' . $row_item['img']);
        //$dims = imageGetDimensions( $link. $row['img']);
        $width = $dims['width'];
        $height = $dims['height'];
        //for large size
        $new_height = 256;
        $scaleWidth= 585/$width;
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
        $media_hotels[] = $imgArr;
    }
    return $media_hotels;
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <end>
}
function getHotelMediaNb($txt_id){
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <start>
	global $dbConn;
	$params = array();  
    $nb = '';
//    $query_hotels = "SELECT count(*) FROM `discover_poi_images` WHERE poi_id = $txt_id ORDER BY default_pic DESC";
    $query_hotels = "SELECT count(*) FROM `discover_poi_images` WHERE poi_id = :Txt_id ORDER BY default_pic DESC";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $select = $dbConn->prepare($query_hotels);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    $ret_hotels = db_query($query_hotels);
//    $row = db_fetch_array($ret_hotels);
    $row = $select->fetch();
    $nb = $row[0];
    return $nb;
}
function navigatePhoto($txt_id,$page){
    $arr = array();
    global $showedPerPage;
    $total = getHotelMediaNb($txt_id);
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
foreach($hotels as $h){
    $str .= '<img src="'.$h['thumb'].'" data-src="'.$h['img'].'" data-w="'.$h['imgWidth'].'" data-h="'.$h['imgHeight'].'" alt="" width="36" height="36" class="hotelListPic"/>';
}
$mediaNav = navigatePhoto($hotel,$to);
$Result["mediaPrev"] = $mediaNav[0];
$Result["mediaNext"] = $mediaNav[1];
$str .= '<div class="hotelListnav" > <div class="thumbsBack ';
if($mediaNav[0] != ''){
    $str .= 'thumbsBackEnable';
}
$str .= '" data-page="'.$mediaNav[0].'" data-hotel="'.$hotel.'"></div><div class="thumbsForward ';
if($mediaNav[1] != ''){
    $str .= 'thumbsForwardEnable';
}
$str .= '" data-page="'.$mediaNav[1].'" data-hotel="'.$hotel.'"></div></div>';
$Result['media'] = $str;
echo json_encode( $Result );