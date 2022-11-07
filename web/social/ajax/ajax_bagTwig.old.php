<?php
$path="../";
$bootOptions=array("loadDb"=>1,"loadLocation"=>0,"requireLogin"=>0);
include_once($path."inc/common.php");
include_once($path."inc/bootstrap.php");
include_once($path."inc/functions/videos.php");
include_once($path."inc/functions/users.php");
include_once($path."inc/functions/bag.php");

//$link="http://para-tube/ttback/uploads/";
$link=ReturnLink('media/discover').'/';
$user_id=userGetID();
//$bag_id = intval(@$_POST['value']);
$bag_id = intval($request->request->get('value', 0));
$user_is_logged=0;
if(userIsLogged()){
    $user_is_logged=1;
}
$str='';

global $dbConn;
$restaurants = array();
$hotels = array();
$todos = array();
$media_hotels = array();
$media_resto = array();
$media_poi = array();
$media_channel_event = array();
$poi = array();
$all = userBagListByName($user_id,$bag_id);
foreach($all as $eachBag){        
    $BagItm = userBagItemsList($user_id,$eachBag['id']);
    foreach($BagItm as $each){
        $params = array(); 
        $item_id = $each['item_id'];
        $params[] = array(  "key" => ":Item_id", "value" =>$item_id);
        if($each['type'] == SOCIAL_ENTITY_RESTAURANT){
            $query_reviews = "SELECT COUNT(id) FROM `discover_restaurants_reviews` WHERE restaurant_id = :Item_id AND published=1";
            
            $select = $dbConn->prepare($query_reviews);
            PDO_BIND_PARAM($select,$params);
            $retquery_reviews_count    = $select->execute();
//            $retquery_reviews_count = db_query($query_reviews);
//            $row_reviews_count = db_fetch_array($retquery_reviews_count);
            $row_reviews_count = $select->fetch();
            $n_results = $row_reviews_count[0];          
            $query_restaurants = "SELECT $each[0] as bag,$n_results as review, id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
            
//            $ret_restaurants = db_query($query_restaurants);
//            $media_resto[] = db_fetch_array($ret_restaurants);
            $select = $dbConn->prepare($query_restaurants);
            PDO_BIND_PARAM($select,$params);
            $ret_restaurants    = $select->execute();
            $rows_res = $select->fetch();
            $rows_res['bag'] = $each[0];
            $media_resto[] = $rows_res;
            $n_results = $row_reviews_count[0];
            
            $query = "SELECT h.*, d.image as dimage FROM `global_restaurants` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_RESTAURANT." WHERE h.id = :Item_id LIMIT 1";
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $retR    = $select->execute();
            $restaurants[] = $select->fetch();
        }else if($each['type'] == SOCIAL_ENTITY_HOTEL){
            
//            $query_reviews = "SELECT COUNT(id) FROM `discover_hotels_reviews` WHERE hotel_id = $item_id AND published=1";
            $query_reviews = "SELECT COUNT(id) FROM `discover_hotels_reviews` WHERE hotel_id = :Item_id AND published=1";
            
//            $retquery_reviews_count = db_query($query_reviews);
//            $row_reviews_count = db_fetch_array($retquery_reviews_count);
            $select = $dbConn->prepare($query_reviews);
            PDO_BIND_PARAM($select,$params);
            $retquery_reviews_count    = $select->execute();
            $row_reviews_count = $select->fetch();
            $n_results = $row_reviews_count[0];
            $query_hotels = "SELECT $each[0] as bag,$n_results as review, id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Item_id and filename != '' ORDER BY default_pic DESC LIMIT 1";
            
//            $ret_hotels = db_query($query_hotels);
//            $media_hotels[] = db_fetch_array($ret_hotels);
            $select = $dbConn->prepare($query_hotels);
            PDO_BIND_PARAM($select,$params);
            $ret_hotels    = $select->execute();
            $rows_res = $select->fetch();
            $rows_res['bag'] = $each[0];
            $media_hotels[] = $rows_res;

            $query = "SELECT h.*, d.image as dimage FROM `discover_hotels` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_HOTEL." WHERE h.id = :Item_id LIMIT 1";
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $retH    = $select->execute();
            $hotels[] = $select->fetch();
        }else if($each['type'] == SOCIAL_ENTITY_LANDMARK){             
//            $query_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = $item_id AND published=1";
            $query_reviews = "SELECT COUNT(id) FROM `discover_poi_reviews` WHERE poi_id = :Item_id AND published=1";
            
//            $retquery_reviews_count = db_query($query_reviews);
//            $row_reviews_count = db_fetch_array($retquery_reviews_count);
            $select = $dbConn->prepare($query_reviews);
            PDO_BIND_PARAM($select,$params);
            $retquery_reviews_count    = $select->execute();
            $row_reviews_count = $select->fetch();
            $n_results = $row_reviews_count[0];

            $query_poi = "SELECT $each[0] as bag,$n_results as review, id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Item_id ORDER BY default_pic DESC LIMIT 1";
            
//            $ret_poi = db_query($query_poi);
//            $media_poi[] = db_fetch_array($ret_poi);
            $select = $dbConn->prepare($query_poi);
            PDO_BIND_PARAM($select,$params);
            $ret_poi    = $select->execute();
            $rows_res = $select->fetch();
            $rows_res['bag'] = $each[0];
            $media_poi[] = $rows_res;
             $query = "SELECT h.*, d.image as dimage FROM `discover_poi` as h LEFT JOIN cms_thingstodo_details as d on d.entity_id=:Item_id AND d.entity_type=".SOCIAL_ENTITY_LANDMARK." WHERE h.id = :Item_id LIMIT 1";
            
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $retP    = $select->execute();
            $poi[] = $select->fetch();
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <end>
        }else if($each['type'] == SOCIAL_ENTITY_EVENTS){
            $arrEventDetails   = channelEventInfo($item_id,-1);
            $arrEventDetails['bag'] = $each['id'];
            $media_channel_event[] = $arrEventDetails;
        }
//  Changed by Anthony Malak 14-05-2015 to PDO database
//  <end>
    }
}

$str .='<div class="dotedline"></div>
    <!--  Things to do  -->
    <div class="bagPart">
        <div class="menu_header">
            <div class="menu_header_num">01</div>
            <div class="menu_header_tit">'._("Things To do").'</div>
        </div>
        <div class="things_cont things_cont_1">';
            foreach($media_channel_event as $event_item){
                $page_link = ReturnLink('channel-events-detailed/'.$event_item['id']);
                $image_pic = ($event_item['photo']) ? photoReturneventImage($event_item) : ReturnLink('images/channel/eventthemephoto.jpg');
                $event_name = htmlEntityDecode($event_item['name']);
                if (strlen($event_name) > 40) {
                    $event_name = cut_sentence_length($event_name, 40, 12);
                }
                $str .='<div class="hotels_cont_inside">
                    <div class="restoreviews">
                        <div class="hotelreview_txt">'.$event_name.'</div>
                    </div>
                    <div class="hotelsreviews_pic"><a href="'.$page_link.'" target="_blank"><img height="109" width="175" src="'.$image_pic.'" style="min-height:109px;max-width: 100%;min-width:175px;"/></a></div>
                    <div class="hotelsminus" data-id="'.$event_item['bag'].'"></div>
                </div>';
            }
            for($r=0;$r<count($poi);$r++){
                $page_link = ReturnLink('things2do/id/'.$poi[$r]['id']);
                if( !is_null($poi[$r]['dimage']) && $poi[$r]['dimage'] && $poi[$r]['dimage']!='' ){
                    $poi_img = ReturnLink("media/videos/uploads/thingstodo/".$poi[$r]['dimage']);
                }else if( $media_poi[$r]['img'] && $media_poi[$r]['img']!=''){
                    $poi_img =  $link.'thumb/'.$media_poi[$r]['img'];
                }else{
                    $poi_img = ReturnLink('images/landmark-icon1.jpg');
                }
                $poi_review = intval($media_poi[$r]['review']);
                if($poi_review>0) $poi_review=  displayReviewsCount($poi_review);
                else $poi_review ='';
                $poi_name = htmlEntityDecode($poi[$r]['name']);
                if (strlen($poi_name) > 40) {
                    $poi_name = cut_sentence_length($poi_name, 40, 12);
                }
                $str .='<div class="hotels_cont_inside">
                    <div class="restoreviews">
                        <a href="'.$page_link.'" target="_blank"><div class="hotelreview_txt">'.$poi_name.'</div></a>
                        <div class="hotelreview_txt2"><u>'. $poi_review .'</u></div>
                    </div>
                    <div class="hotelsreviews_pic"><a href="'.$page_link.'" target="_blank"><img height="109" width="175" src="'.$poi_img.'" style="min-height:109px;max-width: 100%;min-width:175px;"></a></div>
                    <div class="hotelsminus" data-id="'.$media_poi[$r]['bag'].'"></div>
                </div>';
            }
        $str .='</div>
    </div>
    <div class="dotedline"></div>
    <!--  Hotels  -->
<div class="bagPart">
    <div class="menu_header">
        <div class="menu_header_num">02</div>
        <div class="menu_header_tit">'._("Hotels").'</div>
    </div>
    <div class="things_cont things_cont_2">';
    for($h=0;$h<count($hotels);$h++){
        $page_link = ReturnLink('thotel/id/'.$hotels[$h]['id']);
        $hotel_name = htmlEntityDecode($hotels[$h]['hotelName']);    
        if (strlen($hotel_name) > 32) {
            $hotel_name = cut_sentence_length($hotel_name, 32, 12);
        }
        if(intval($media_hotels[$h]['review'])>0) $hotelsreview =  displayReviewsCount(intval($media_hotels[$h]['review']));
        else $hotelsreview ='';
        if( !is_null($hotels[$h]['dimage']) && $hotels[$h]['dimage'] && $hotels[$h]['dimage']!='' ){
            $image_pic = ReturnLink("media/videos/uploads/thingstodo/".$hotels[$h]['dimage']);
        }else if( $media_hotels[$h]['img'] && $media_hotels[$h]['img']!=''){
            $image_pic =  $link.'thumb/'.$media_hotels[$h]['img'];
        }else{
            $image_pic = ReturnLink('images/hotel-icon-image1.jpg');
        }
        $str .='<div class="hotels_cont_inside">
            <div class="hotelsreviews">
                <a href="'.$page_link.'" target="_blank"><div class="hotelreview_txt">'.$hotel_name.'</div></a>
                <div class="whitestarContainer">';
        for($s=0;$s<min($hotels[$h]['stars'],5);$s++){
            $str .='<img src="'.ReturnLink('images/hotels/whiteStar.png').'" class="whitestar">';
        }
        $str .='</div>
                <div class="hotelreview_txt2"><u>'.$hotelsreview.'</u></div>
            </div>
            <div class="hotelsreviews_pic"><a href="'.$page_link.'" target="_blank"><img height="109" width="175" src="'.$image_pic.'" style="min-height:109px;max-width: 100%;min-width:175px;"></a></div>
            <div class="hotelsminus" data-id="'.$media_hotels[$h]['bag'].'"></div>
        </div>';
     }
    $str .='</div>
</div>
<div class="dotedline"></div>
<!--  Restaurant  -->
<div class="bagPart">
    <div class="menu_header">
        <div class="menu_header_num">03</div>
        <div class="menu_header_tit">'._("Restaurants").'</div>
    </div>
    <div class="things_cont things_cont_3">';
    for($r=0;$r<count($restaurants);$r++){
        $page_link = ReturnLink('trestaurant/id/'.$restaurants[$r]['id']);
        $restaurant_name = htmlEntityDecode($restaurants[$r]['name']);
        if (strlen($restaurant_name) > 40) {
            $restaurant_name = cut_sentence_length($restaurant_name, 40, 12);
        }
        if( !is_null($restaurants[$r]['dimage']) && $restaurants[$r]['dimage'] && $restaurants[$r]['dimage']!='' ){
            $image_pic = ReturnLink("media/videos/uploads/thingstodo/".$restaurants[$r]['dimage']);
        }else if( $media_resto[$r]['img'] && $media_resto[$r]['img']!=''){
            $image_pic = $link.'thumb/'.$media_resto[$r]['img'];
        }else{
            $image_pic = ReturnLink('images/restaurant-icon1.jpg');
        }
        if(intval($media_resto[$r]['review'])>0) $restaurants_review = displayReviewsCount(intval($media_resto[$r]['review']));
        else $restaurants_review ='';
        $str .='<div class="hotels_cont_inside">
                <div class="restoreviews">
                    <a href="'.$page_link.'" target="_blank"><div class="hotelreview_txt">'.$restaurant_name.'</div></a>
                    <div class="hotelreview_txt2"><u>'.$restaurants_review.'</u></div>
                </div>
                <div class="hotelsreviews_pic"><a href="'.$page_link.'" target="_blank"><img height="109" width="175" src="'.$image_pic.'" style="min-height:109px;max-width: 100%;min-width:175px;"></a></div>
                <div class="hotelsminus" data-id="'.$media_resto[$r]['bag'].'"></div>
            </div>';
    }
    $str .='</div>
</div>
<div class="dotedline"></div>';

$result['data']=$str;
//$result['departdate_str']=$departdate_str;
echo json_encode($result);