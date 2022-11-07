<?php
$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/twigFct.php" );

$link = ReturnLink('media/discover') . '/';
//$link = '/backend/uploads/';

$theLink = $CONFIG ['server']['root'];
//require_once $theLink . 'vendor/autoload.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('thotel.twig');

$user_id = userGetID();
$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;
$is_owner = 0;

$friend_array_count = '0';
$followers_array_count = '0';
if ($user_is_logged == 1) {
    $options = array(
        'type' => array(1),
        'userid' => $user_id,
        'n_results' => true
    );
    $friend_array_count = userFriendSearch($options);

    $options = array(
        'userid' => $user_id,
        'n_results' => true
    );
    $followers_array_count = userSubscriberSearch($options);
}
$userInfo = getUserInfo($user_id);
$logo_src = ReturnLink('media/tubers/' . $userInfo['profile_Pic']);
$tuber_name = returnUserDisplayName($userInfo);

if ($userIschannel == 0 && $user_is_logged == 1) {
    $profilePic = ReturnLink('media/tubers/small_' . $userInfo['profile_Pic']);
    $fullNameStan = htmlEntityDecode($userInfo['FullName']);
    $fullName = cut_sentence_length($fullNameStan, 25);
} else {
    $profilePic = ReturnLink('media/tubers/small_he.jpg');
}
$txt_id_init = intval(UriGetArg('id'));
$txt_id = ($txt_id_init == 0) ? null : $txt_id_init;
if ($txt_id_init == 0) {
    header("location:" . ReturnLink('thotels'));
}
$search_val = xss_sanitize(UriGetArg('s'));
$country_code = xss_sanitize(UriGetArg('CO'));
$state_code = xss_sanitize(UriGetArg('ST'));
$city_code = xss_sanitize(UriGetArg('C'));

$from_val = xss_sanitize(UriGetArg('from'));
$fr_txt = isset($from_val) ? $from_val : '';
$to_val = xss_sanitize(UriGetArg('to'));
$to_txt = isset($to_val) ? $to_val : '';
$from_date = "dd / mm / yyyy";
$to_date = "dd / mm / yyyy";
if ($fr_txt != '') {
    $from_date = date('d/m/Y', strtotime($fr_txt));
    $to_date = date('d/m/Y', strtotime($to_txt));
}
$date_val = '';
if ($from_val != '') {
    $date_val .= $from_val;
}
if ($to_val != '') {
    if ($date_val != '') {
        $date_val .= ' to ';
    }
    $date_val .= $to_val;
}

$room_val = xss_sanitize(UriGetArg('room'));
$room_text = '';
$roomArr = explode('-', $room_val);
if (count($roomArr) == 3) {
    if (intval($roomArr[1]) == 1) {
        $room_text .= '1 Adult';
    } else {
        $room_text .= $roomArr[1] . ' Adults';
    }
    if (intval($roomArr[2]) > 0) {
        if (intval($roomArr[2]) == 1) {
            $room_text .= 'and 1 child';
        } else {
            $room_text .= 'and ' . $roomArr[2] . ' children';
        }
    }
}
	global $dbConn;
	$params = array();  
//$guestText =
//$query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = $txt_id ORDER BY default_pic DESC LIMIT 29";
$query_hotels = "SELECT id , filename as img , hotel_id FROM `discover_hotels_images` WHERE hotel_id = :Txt_id ORDER BY default_pic DESC LIMIT 29";
$params[] = array(  "key" => ":Txt_id",
                    "value" =>$txt_id);
$select = $dbConn->prepare($query_hotels);
PDO_BIND_PARAM($select,$params);
$ret_hotels    = $select->execute();
$media_hotels = array();
//$ret_hotels = db_query($query_hotels);
$media_hotels = $select->fetchAll();
//while ($row = db_fetch_array($ret_hotels)) {
//    $media_hotels[] = $row_item;
//}
$irating_average = socialRateAverage($txt_id, array(SOCIAL_ENTITY_HOTEL));
$irating = socialRated($user_id, $txt_id, SOCIAL_ENTITY_HOTEL);
if (!$irating)
    $irating = 0;

$general_facilities = '';
$media_hotels_facilities_str = '';
$services = '';
$hotel_data = getHotelInfo($txt_id);

$media_hotels_features = getHotelFeatures($txt_id);
foreach ($media_hotels_features as $item){
    if($item['feature_type']==2 || $item['feature_type']==1){
        $general_facilities .=$item['ftitle'].'<br/>';
    }else if($item['feature_type']==5){
        $services .=$item['ftitle'].'<br/>';
    }else{
        $media_hotels_facilities_str .=$item['ftitle'].'<br/>';
    }
}
$params2 = array();  
$query_hotels_rooms = "SELECT * FROM `discover_hotels_rooms` WHERE hotel_id = :Txt_id";
$params2[] = array(  "key" => ":Txt_id",
                     "value" =>$txt_id);
$select = $dbConn->prepare($query_hotels_rooms);
PDO_BIND_PARAM($select,$params2);
$ret_hotels_rooms    = $select->execute();
$media_hotels_rooms = array();
//$ret_hotels_rooms = db_query($query_hotels_rooms);
//while ($row = db_fetch_array($ret_hotels_rooms)) {
$media_hotels_rooms = $select->fetchAll();
//    $media_hotels_rooms[] = $row_item;
//}
//debug($media_hotels_rooms);

/*$facilities = $hotel_data['facilities'];
$facilities_array0 = explode('|', $facilities);
$facilities_array1 = array();
foreach ($facilities_array0 as $facilities_item) {
    if ($facilities_item != '')
        array_push($facilities_array1, $facilities_item);
}
$facilities_search = implode(',', $facilities_array1);

$query_hotels_facilities = "SELECT * FROM discover_facilities WHERE id IN ($facilities_search)";

//$ret_hotels_facilities = db_query($query_hotels_facilities);
while ($row = db_fetch_array($ret_hotels_facilities)) {
    if ($media_hotels_facilities_str != '') {
        $media_hotels_facilities_str .= ', ';
    }
    $media_hotels_facilities_str .= htmlEntityDecode($row['title']);
}*/


$media_hotels_reviews = userReviewsList($txt_id, SOCIAL_ENTITY_HOTEL, 6, 0);
$media_hotels_reviews_count = userReviewsList($txt_id, SOCIAL_ENTITY_HOTEL, 0, 0, true);

$dims = imageGetDimensions($CONFIG['server']['root'] . 'media/discover/' . $media_hotels[0]['img']);

$width = $dims['width'];
$height = $dims['height'];

$scaleWidth = 585 / $width;
$scaleHeight = 256 / $height;
if ($scaleWidth > $scaleHeight) {
    $new_width = $width * $scaleWidth;
    $new_height = $height * $scaleWidth;
} else {
    $new_width = $width * $scaleHeight;
    $new_height = $height * $scaleHeight;
}
$style_pic = "width: {$new_width}px; height: {$new_height}px;";

$title = $hotel_data['hotelName'].' / '.$hotel_data['title_type'].'';
$locationText = '';
if ($hotel_data['city_id'] != '0') {
    $city_array = worldcitiespopInfo(intval($hotel_data['city_id']));
    $city_name = $city_array['name'];
    if ($city_name != '') {
        $locationText .= $city_name;
    }
    $state_array = worldStateInfo($city_array['country_code'], $city_array['state_code']);
    $state_name = $state_array['state_name'];
    if ($state_name != '') {
        $locationText .= ', ' . $state_name;
    }
    $country_array = countryGetInfo($city_array['country_code']);
    $country_name = $country_array['name'];
    if ($country_name != '') {
        $locationText .= ', ' . $country_name;
    }
} else {
    $locationText = $hotel_data['location'];
}
if ($locationText != '') {
    $title .= ', ' . $locationText;
}
tt_global_set('title', $title);

$imageLink = array();
for ($i = 0; $i < min(count($media_hotels), 29); $i++) {
    $dims = imageGetDimensions($CONFIG['server']['root'] . 'media/discover/' . $media_hotels[$i]['img']);
    $width = $dims['width'];
    $height = $dims['height'];
    $new_height = 256;
    $scaleWidth = 585 / $width;
    $scaleHeight = 256 / $height;
    if ($scaleWidth > $scaleHeight) {
        $new_width = $width * $scaleWidth;
        $new_height = $height * $scaleWidth;
    } else {
        $new_width = $width * $scaleHeight;
        $new_height = $height * $scaleHeight;
    }
    $imageLink[] = '<img src="' . $link . 'thumb/' . $media_hotels[$i]['img'] . '" alt="' . $media_hotels[$i][1] . '" data-src="' . $link . $media_hotels[$i]['img'] . '" data-w="' . $new_width . '" data-h="' . $new_height . '" class="hotelListPic" id="hotelListPic_' . $i . '" />';
    if($i>=28) break;
}

$media_roomsArray = array();
for ($rm = 0; $rm < count($media_hotels_rooms); $rm++) {
    $media_hotels_roomsArray = array();
    $toshow = '';
    $media_hotels_roomsArray['title'] = htmlEntityDecode($media_hotels_rooms[$rm]['title']);
    $num_person = array();
    for ($y = 1; $y <= $media_hotels_rooms[$rm]['num_person']; $y++) { 
        $num_person[] = '<img src="'.returnLink('images/hotels/bedNum.png').'" alt="" class="hotelBedNum" />';
    }
    $media_hotels_roomsArray['num_person'] =$num_person;
    $media_hotels_roomsArray['price'] =$media_hotels_rooms[$rm]['price'];
    $media_hotels_roomsArray['pic1'] ='';
    $media_hotels_roomsArray['pic2'] ='';
    $media_hotels_roomsArray['pic3'] ='';
    if ($media_hotels_rooms[$rm]['pic1'] != '') {
        $toshow = $link . 'rooms/' .$media_hotels_rooms[$rm]['pic1'];
        $media_hotels_roomsArray['pic1'] = $link . 'rooms/' . $media_hotels_rooms[$rm]['pic1'];
    }
    if ($media_hotels_rooms[$rm]['pic2'] != '') {
        if ($toshow == '') {
            $toshow = $link . 'rooms/' .$media_hotels_rooms[$rm]['pic2'];
        }
        $media_hotels_roomsArray['pic2'] = $link . 'rooms/' . $media_hotels_rooms[$rm]['pic2'];
    }
    if ($media_hotels_rooms[$rm]['pic3'] != '') {
        if ($toshow == '') {
            $toshow = $link . 'rooms/' .$media_hotels_rooms[$rm]['pic3'];
        }
        $media_hotels_roomsArray['pic3'] = $link . 'rooms/' . $media_hotels_rooms[$rm]['pic3'];
    }
    $media_hotels_roomsArray['toshow'] =$toshow;
    $description_db = htmlEntityDecode($media_hotels_rooms[$rm]['description'],0);
    $media_hotels_roomsArray['description_db'] =str_replace("\n", "<br/>", $description_db);
    $media_roomsArray[] = $media_hotels_roomsArray;
}
$media_reviewsArray = array();
$channelInfo_disable=NULL;
foreach ($media_hotels_reviews as $rev) {
    $media_hotels_reviewsArray = array();
    $rev_id = $rev['id'];
    $user_id_rev = $rev['user_id'];
    $userRevInfo = getUserInfo($user_id_rev);
    $logo_userRev_src = ReturnLink('media/tubers/' . $userRevInfo['profile_Pic']);
    $description_db = htmlEntityDecode($rev['description']);
    $media_hotels_reviewsArray['rev_id'] =$rev_id;
    $media_hotels_reviewsArray['logo_userRev_src'] =$logo_userRev_src;
    $media_hotels_reviewsArray['title'] =htmlEntityDecode($rev['title']);
    $media_hotels_reviewsArray['description_db'] =$description_db;
    
    $div_val = '<div id="commentDiv" class="social_data_all" data-category="" data-enable="1" data-id="' . $rev_id . '" data-type="' . SOCIAL_ENTITY_HOTEL_REVIEWS . '">';
        $div_val .= '<div class="buttons">';
        $div_val .= '<div class="btn"><div id="likes"></div></div>';
        $div_val .= '<div class="btn_txt opacitynone"></div>';
        $div_val .= '<div class="btn btn_comments btn_enabled"><div id="comments"></div></div>';
        $div_val .= '<div class="btn_txt opacitynone btn_txt_comments btn_enabled"></div>';
        $div_val .= '<div class="btn btn_shares btn_enabled"><div id="shares"></div></div>';
        $div_val .= '<div class="btn_txt opacitynone btn_txt_shares btn_enabled"></div>';

        $div_val .= '</div>';

        // --Like DIV--
        $div_val .= '<div class="likesDiv socialInitDiv hide shadow">
        <div class="closeDiv"></div>
        <div class="likeTTL">'._("LIKES").' (<span class="likesNumber">' . $likeNumber . '</span>)</div>';
        if ($user_is_logged == 1 && ($userIschannel == 0 || $is_owner == 1)) {
            $div_val .= '<div class="meDiv">';
            $div_val .= '<img src="' . $profilePic . '" alt="' . $fullName . '" width="45" height="45"/>
                        <div class="mynameDiv">' . $fullName . '</div>';
            if (is_null($channelInfo_disable)) {
                $div_val .= '<div class="likeDiv" data-action="" data-media=""></div>';
            }
            $div_val .= '</div>';
        } else {
            $div_val .= '<div class="toplikes">
                                <div class="likepop"></div>
                                <div class="meStanDiv displaynone">
                                        <div class="meStanDivText">' . _('you need to have a') . ' <a class="black_link" href="' . ReturnLink('/register') . '" target="_blank">' . _('TT account') . '</a><br />' . _('in order to like') . '</div>
                                </div>
                        </div>';
        }
        $div_val .= '<div class="containerDiv" style="height:auto; overflow:hidden;">

                <div class="commentsAll" style="max-height:446px;"><div class="social_not_refreshed"></div></div>
        </div>
        <div class="showMore_like showMore">' . _('show more') . '</div>
    </div>';

        //-- Comment DIV--
        $div_val .= '<div class="commentsDiv socialInitDiv hide shadow">
         <div class="closeDiv"></div>
         <div class="likeTTL">'._("COMMENTS").' (<span class="commentsNumber">' . $commentsNumber . '</span>)</div>';
        $comlang = _('write a comment / @T Tuber to reply');
        if ($user_is_logged == 1 && ($userIschannel == 0 || $is_owner == 1)) {
            $div_val .= '<div class="meDiv">';
            $div_val .= '<img src="' . $profilePic . '" alt="' . $fullName . '" width="45" height="45"/>
                 <div class="mynameDiv">' . $fullName . '</div>';

            $div_val .= '<div class="writecommentDiv writecommentDivAuto">
                         <div class="examples">
                           <textarea class="mention textareaclass" placeholder="' . $comlang . '" style="height: 28px; overflow: hidden;"></textarea>
                         </div>
                 </div>
         </div>';
        } else {
            $div_val .= '<div class="meStanDiv_comment displaynone disabledmessage">
                         ' . _('you need to have a') . ' <a class="black_link" href="' . ReturnLink('/register') . '" target="_blank">' . _('TT account') . '</a><br />' . _('in order to write a comment') . '
                 </div>
                 <div class="meDiv">
                         <div class="writecommentDiv"><input type="text" name="commentText" value="' . $comlang . '" onblur="addValueInput(this)" onFocus="removeValueInput(this)" data-value="' . $comlang . '" class="notsigned" data-mode="photo"/></div>

                 </div>';
        }
        $div_val .= '<div class="containerDiv">
                 <div class="commentsAll" style="max-height:412px;"></div>
         </div>
         <div class="showMore_comments showMore">' . _('show more') . '</div>

    </div>';

        //-- Share DIV--
        $div_val .= '<div class="sharesDiv socialInitDiv hide shadow">
            <div class="closeDiv"></div>
            <div class="likeTTL">'._("SHARES").' (<span class="sharesNumber">' . $sharesNumber . '</span>)</div>';
        if ($user_is_logged == 1 && ($userIschannel == 0 || $is_owner == 1)) {

            $div_val .= '<div class="meDiv" style="width:243px;">';
            $div_val .= '<img src="' . $profilePic . '" alt="' . $fullName . '" width="45" height="45"/>
                            <div class="mynameDiv" style="width:195px;">' . $fullName . '</div>';

            $div_val .= '</div>';


            $div_val .= '<div class="formttl13 formContainer100 margintop26" style="margin-top:6px;">'._("write something").'</div>
                    <textarea id="invitetext" class="ChaFocus margintop5" onBlur="addValue2(this)" onFocus="removeValue2(this)" data-value="'._("write something...").'" style="font-family:Arial, Helvetica, sans-serif; width:644px; height:38px; background-color:#ebebeb;" type="text" name="invitetext">'._("write something...").'</textarea>';

            $div_val .= '<div>
                            <select id="share_select" style="margin-top:14px;">
                                    <option value="0" selected="selected" disabled="disabled">'._('share this entry with...').'</option>';

            $div_val .= '<option value="2" data-text="friends and followers">' . _('friends and followers') . '</option>
                                    <option value="1" data-text="friends">' . _('friends') . '</option>
                                    <option value="3" data-text="followers">' . _('followers') . '</option>';

            $div_val .= '<option value="4" data-text="custom">' . _('custom') . '</option>
                                    <option value="5" data-text="by mail">' . _('by mail') . '</option>
                            </select>
                    </div>';

            $div_val .= '<div class="formttl13 formContainer100 margintop15" style="margin-top:12px;">'._("add people (T tubers, emails)").'</div>
                    <div class="peoplecontainer peoplecontainer_boxed formContainer100 margintop2" style="width:646px; background:none">
                            <div class="emailcontainer_boxed emailcontainer_boxed_share" style="width:640px;">
                                    <div class="addmore"><input name="addmoretext_brochure" id="addmoretext_brochure" type="text" class="addmoretext_css" data-value="'. _("add more").'" value="'. _("add more").'" onFocus="removeValue2(this)" onBlur="addValue2(this)" data-id=""/></div>
                            </div>
                            <div id="share_boxed_send" class="sharepopup_but2 sharepopup_buts">' . _('send') . '</div>
                    </div>';
        } else {
            $div_val .= '<select id="share_select1" onChange="share_selectDisabled(this)">
                            <option value="0" selected="selected" disabled="disabled">' . _('share this entry with...') . '</option>
                            <option value="2">' . _('friends and followers') . '</option>
                            <option value="1">' . _('friends') . '</option>
                            <option value="3">' . _('followers') . '</option>
                            <option value="4">' . _('custom') . '</option>
                            <option value="5">' . _('by mail') . '</option>
                    </select>';
            $div_val .= '<div class="meStanDiv_comment meStanDiv_share displaynone disabledmessage">
                            ' . _('you need to have a') . ' <a class="black_link" href="' . ReturnLink('/register') . '" target="_blank">' . _('TT account') . '</a><br />' . _('in order to share this entry.') . '
                    </div>';
        }
        $div_val .= '<div class="containerDiv">
                    <div class="commentsAll" style="max-height:446px;"></div>
            </div>
            <div class="showMore_shares showMore">' . _('show more') . '</div>
    </div>';
        $div_val .= '</div>';
        
   $media_hotels_reviewsArray['div_val'] =$div_val;
   $media_reviewsArray[] = $media_hotels_reviewsArray;
}
$tubersArray= array();
$options = array(
    'limit' => 21,
    'page' => 0,
    'public' => 1,
    'orderby' => 'rand',
    'order' => 'desc',
    'profile_pic' => true
);
$tubers = userSearch($options);
foreach ($tubers as $tuber) {
    $tubersArray1= array();
    $location = userGetLocation($tuber);
    if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
        continue;
    $tubersArray1['userProfileLink'] =userProfileLink($tuber);
    $tubersArray1['profile_Pic'] =ReturnLink('media/tubers/xsmall_' . $tuber['profile_Pic']);
    $tubersArray1['profile_PicThumb'] =ReturnLink('media/tubers/thumb_' . $tuber['profile_Pic']);
    $tubersArray1['returnUserDisplayName'] =returnUserDisplayName($tuber);
    $tubersArray1['location'] =$location;
    $tubersArray[] = $tubersArray1;
}

$data['currencySymbol'] = @$_SESSION['currencySymbol'];
$data['user_id'] = $user_id;
$data['user_is_logged'] = $user_is_logged;
$data['link'] = $link;
$data['userIschannel'] = $userIschannel;
$data['is_owner'] = $is_owner;
$data['friend_array_count'] = $friend_array_count;
$data['followers_array_count'] = $followers_array_count;
$data['SOCIAL_ENTITY_HOTEL'] = SOCIAL_ENTITY_HOTEL;
$data['logo_src'] = $logo_src;
$data['tuber_name'] = $tuber_name;
$data['profilePic'] = $profilePic;
$data['fullNameStan'] = $fullNameStan;
$data['fullName'] = $fullName;
$data['show_on_map']     =   ReturnLink('parts/show-on-map.php?type=h&id=' . $hotel_data['id']);

$data['txt_id'] = $txt_id;
$data['search_val'] = $search_val;
$data['from_date'] = $from_date;
$data['to_date'] = $to_date;
$data['state_code'] = $state_code;
$data['city_code'] = $city_code;
$data['country_code'] = $country_code;
$data['fr_txt'] = $fr_txt;
$data['to_txt'] = $to_txt;

$data['media_hotels_img'] = $link . $media_hotels[0]['img'];
$data['hotel_data_hotelName'] = $hotel_data['hotelName'].' <span class="yellowcolor">/ '.$hotel_data['title_type'].'</span>';
$data['hotel_data_hotelName1'] = $hotel_data['hotelName'];
$data['style_pic'] = $style_pic;
$data['hotel_data_stars'] = $hotel_data['stars'];
$data['locationText'] = $locationText;
$data['irating_average'] = $irating_average;
$data['media_hotels_reviews_count'] = $media_hotels_reviews_count;
$data['minCount_media_hotels'] = min(count($media_hotels), 29);
$data['imageLink'] = $imageLink;
$data['media_hotels_count'] = count($media_hotels);
$data['date_val'] = $date_val;
$data['room_text'] = $room_text;
$data['count_media_hotels_rooms'] = count($media_roomsArray);
$data['media_hotels_rooms'] = $media_roomsArray;
$data['media_hotels_reviewsArray'] = $media_reviewsArray;
$data['general_facilities'] = $general_facilities;
$data['media_hotels_facilities_str'] = $media_hotels_facilities_str;
$data['services'] = $services;
$data['hotel_data_about'] = $hotel_data['about'];
$data['irating'] = $irating;
$data['tubersArray'] = $tubersArray;
$data['channels_link'] = ReturnLink('channels.php');;
$data['is_owner'] = $is_owner;
$data['is_owner'] = $is_owner;

$data['userIsLogged'] = userIsLogged();
$data['userIsChannel'] = userIsChannel();
$data['hide_view_all'] = '0';
$includes = array("js/jscal2.js", "js/jscal2.en.js",'css/hotel-resto.css', 'js/hotels.js', 'css/jscal2.css');
$includes[] = 'js/language_bar.js'; //the js of the language bar
$includes[] = 'css/language_bar.css'; //the css of the language bar
$includes[] = 'css/social-actions.css?v='.SOCIAL_ACTIONS_CSS_V;
$includes[] = 'js/social-actions.js';

if (userIsLogged() && userIsChannel()) {
     array_unshift($includes, 'css/channel-header.css');
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_headChannel.php");
} else {
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_head.php");
}
require_once $theLink.'twig_parts/_language_bar.php';
include($theLink . "twig_parts/_foot.php");
echo $template->render($data);