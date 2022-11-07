<?php
$path = "";

$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/twigFct.php" );

$link = ReturnLink('media/discover') . '/';
$theLink = $CONFIG ['server']['root'];

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($theLink . 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'cache' => $theLink . 'twig_cache/', 'debug' => false,
        ));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('hotel-review.twig');
/*
tt_global_set('includes', array('css/social-actions.css', 'css/hotel-resto.css', 'css/hotel-review.css',
    'js/social-actions.js', 'js/hotel-review.js',"assets/channel/js/channel-home-upload-behavior.js")
);
*/

$includes = array('media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,'media1'=>'css_media_query/hotel-review.css?v='.MQ_HOTEL_REVIEW_CSS_V,'css/social-actions.css?v='.SOCIAL_ACTIONS_CSS_V, 'css/hotel-resto.css', 'css/hotel-review.css',
    'js/social-actions.js', 'js/hotel-review.js',"assets/channel/js/channel-home-upload-behavior.js");


$uricurpage = UriCurrentPageURL();
$uricurserver = currentServerURL();

$data['uricurpage'] = $uricurpage;
$data['uricurserver'] = $uricurserver;

$user_id = userGetID();
$data['user_id'] = $user_id;
$txt_id_init = intval(UriGetArg('id'));
if(!$txt_id_init){
    $txt_id_init = $request->query->get('id',0);
}
$txt_id = ($txt_id_init == 0) ? null : $txt_id_init;
if ($txt_id_init == 0) {
    header("location:" . ReturnLink('review'));
}


$user_is_logged = 0;
$AllChannelsOwner = array();
if (userIsLogged()) {
    $user_is_logged = 1;
    $options2 = array ( 'owner_id' => $user_id, 'page' => 0, 'limit' => null , 'orderby'=>'channel_name' );
    $ChannelsOwner = channelSearch($options2);
    if( !$ChannelsOwner ) $ChannelsOwner = array();
    $channelrelatedlist = getDiscoverToChannelid($txt_id,SOCIAL_ENTITY_RESTAURANT);
    foreach($ChannelsOwner as $channel_item){
        $id = $channel_item['id'];
        if( !in_array($id,$channelrelatedlist) ){
            $name = $channel_item['channel_name'];
            $name = htmlEntityDecode($name);
            $AllChannelsOwner[] = array( "id"=>$id , "name"=>$name );
        }
    }
    if( $user_id == 1744){
        $data['qr_link'] = ReturnLink('qrCode.php?url='.UriCurrentPageURL(),null);
    }
}
$data['AllChannelsOwner'] = $AllChannelsOwner;
$data['AllChannelsOwnerCount'] = sizeof($AllChannelsOwner);

$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;

$userInfo = getUserInfo($user_id);
$logo_src = ReturnLink('media/tubers/' . $userInfo['profile_Pic']);
$tuber_name = returnUserDisplayName($userInfo);

if ($userIschannel == 0 && $user_is_logged == 1) {
    $profilePic = ReturnLink('media/tubers/small_' . $userInfo['profile_Pic']);
    $fullNameStan = htmlEntityDecode($userInfo['FullName']);
    $fullName = returnUserDisplayName($userInfo, array('max_length' => 25));
        $s_user_link = userProfileLink($userInfo);
} else {
    $profilePic = ReturnLink('media/tubers/small_he.jpg');
}

$data['user_is_logged'] = $user_is_logged;
$data['userIschannel'] = $userIschannel;


$data['discover_img'] = '';
$data['discover_imgbig'] = '';
$media_hotels = getRestaurantDefaultPic($txt_id);
if( $media_hotels ){
    $data['discover_img'] = $link . 'thumb/' . $media_hotels['img'];
    $bigim=$media_hotels['img'];
    $for = strrpos($bigim, '_') + 1;
    $extbig = substr($bigim, $for);    
    if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
        $data['discover_imgbig'] = $link . 'large/' . $extbig;
    }else{
        $data['discover_imgbig'] = $link . 'large/' . $media_hotels['img'];
    }
}else{
    $data['discover_img'] = ReturnLink('images/restaurant-icon.jpg');
}
if($data['discover_imgbig'] != ''){
    $data['fbimg'] = $data['discover_imgbig'];
}else{
    $data['fbimg'] = $data['discover_img'];
}

$hotel_data = getRestaurantInfo($txt_id);
if(!$hotel_data) header('Location:' . ReturnLink('/') );
$data['longitudeVal'] = $hotel_data['longitude'];
$data['latitudeVal'] = $hotel_data['latitude'];
$data['mapImageURL'] = mapImageURL($hotel_data['latitude'],$hotel_data['longitude'],SOCIAL_ENTITY_RESTAURANT,12,'460x110',$hotel_data);
$data['show_on_map']     =   ReturnLink('parts/show-on-map.php?type=r&id=' . $hotel_data['id']);
$title = $hotel_data['name'];
$locationText = '';
$city_name='';

if ( intval($hotel_data['city_id']) >0) {
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
    $city_name = $hotel_data['locality'];
    if ($city_name != '') {
        $locationText .= $city_name;
    }
    $region_name = $hotel_data['region'];
    $admin_region_name = $hotel_data['admin_region'];
    if ($region_name != '' && $region_name !=$city_name ) {
        $locationText .= ', ' . $region_name;
    }else if ($admin_region_name != '' && $admin_region_name !=$city_name) {
        $locationText .= ', ' . $admin_region_name;
    }
    $country_cd = $hotel_data['country'];
    if ($country_cd != '') {
        $country_array = countryGetInfo($country_cd);
        $country_name = $country_array['name'];
        if ($country_name != '') {
            $locationText .= ', ' . $country_name;
        }
    }
}
if ($locationText == '') {
    $locationText = $hotel_data['address'];
}else if ($hotel_data['address'] != '') {
    $locationText .= '<br>'.$hotel_data['address'];
}
$page_link = ReturnLink('trestaurant/id/' . $hotel_data['id']);

$data['discover_title'] = $title;
$data['discover_title1'] = $hotel_data['name'];
$data['discover_nam'] = cleanTitle($hotel_data['name']).'_restaurant_';
$data['discover_location'] = $locationText;
$data['discover_link'] = $page_link;
$data['data_stars'] = 0;

$nb_votes = $hotel_data['nb_votes'];
$data['nb_votes'] = $nb_votes;
if($hotel_data['avg_rating']>0){
    $irating_average = $hotel_data['avg_rating'];
}else{
    $irating_average = socialRateAverage($hotel_data['id'], array(SOCIAL_ENTITY_RESTAURANT));
}
$data['irating_average'] = $irating_average;

$rating_image0 = ReturnLink('images/rating_0.png');
$irating = 2;
$data['irating_1'] = $irating;
$i = 0;
$str='';
while ($i < $irating) {
    $str .= '<img src="' . $rating_image0 . '" width="9" height="9" style="margin:0; padding:0;" class="rating_star"/>';
    $i++;
}
while ($i < 5) {
    $str .= '<img src="' . $rating_image0 . '" width="9" height="9" style="margin:0; padding:0;" class="rating_star"/>';
    $i++;
}
$data['str1'] = $str;

$data['txt_id'] = $txt_id;
$data['SOCIAL_ENTITY_TYPE'] = SOCIAL_ENTITY_RESTAURANT;
$data['evalText'] = _('your evaluation for this restaurant');
$data['nearText'] = _("what's near the restaurant?");
$data['certify'] = _('I certify that this review is based on my personal experience and is my genuine opinion of this institution');

$data['rev_time'] = _('draft saved at 3:18 AM');
$ter_val = '<a title="' . _("touristTube's Terms of Use") . '" target="_blank" href="' . ReturnLink('terms-and-conditions') . '">' . _("touristTube's Terms of Use") . '</a>';
//data['terms_cond'] = vsprintf(_('I am the owner of these photos, and my posting them on Tourist Tube does not infringe upon the rights of any third party. I accept %s'), array($ter_val));

$irating = socialRated($user_id, $txt_id, SOCIAL_ENTITY_RESTAURANT);
if (!$irating)
    $irating = 0;
$data['irating'] = $irating;

$data['userIsLogged'] = userIsLogged();
$data['userIsChannel'] = userIsChannel();
$data['hide_view_all'] = '0';

if (userIsLogged() && userIsChannel()) {
     array_unshift($includes, 'css/channel-header.css');
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_headChannel.php");
} else {
    tt_global_set('includes', $includes);
    include($theLink . "twig_parts/_head.php");
}
$dt0 = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$reDates = array();
$reDates[] = array('val' => date("m,Y", $dt0), 'disp' => date("F Y", $dt0));
for ($i = 0; $i < 11; $i++) {
    $dt1 = mktime(0, 0, 0, date("m") - $i, 0, date("Y"));
    $reDates[] = array('val' => date("m,Y", $dt1), 'disp' => date("F Y", $dt1));
}
$data['reDates'] = $reDates;
$media_hotelsi = array();
if (userIsLogged()) {
    global $dbConn;
    $params = array();  
//    $query_hotelsi = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id AND user_id=$user_id ORDER BY id DESC";
    $query_hotelsi = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id AND user_id=:User_id ORDER BY id DESC";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query_hotelsi);
    PDO_BIND_PARAM($select,$params);
    $ret_hotelsi    = $select->execute();
//    $ret_hotelsi = db_query($query_hotelsi);
    $row = $select->fetchAll();
    foreach($row as $row_item){
        $bigim=$row_item['img'];
        $for = strrpos($bigim, '_') + 1;
        $extbig = substr($bigim, $for);    
        if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
            $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
        }else{
            $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
        }
        $media_hotelsi[] = $row_item;
    }
}
$data['media_hotelsi'] = $media_hotelsi;
$params = array(); 
if (userIsLogged()) {
//    $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id AND user_id<>$user_id ORDER BY id DESC";
    $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id AND user_id<>$user_id ORDER BY id DESC";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
}else{
//    $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = $txt_id ORDER BY id DESC";
    $query_hotelsi1 = "SELECT id , filename as img , restaurant_id FROM `discover_restaurants_images` WHERE restaurant_id = :Txt_id ORDER BY id DESC";
    $params[] = array(  "key" => ":Txt_id",
                        "value" =>$txt_id);
}
$media_hotelsi1 = array();
$select = $dbConn->prepare($query_hotelsi1);
PDO_BIND_PARAM($select,$params);
$res    = $select->execute();
//$ret_hotelsi1 = db_query($query_hotelsi1);
$row = $select->fetchAll();
//while ($row = db_fetch_array($ret_hotelsi1)) {
foreach($row as $row_item){
    $bigim=$row_item['img'];
    $for = strrpos($bigim, '_') + 1;
    $extbig = substr($bigim, $for);    
    if(file_exists( $theLink . 'media/discover/large/' .$extbig ) ){
        $imgbig_path = $theLink . 'media/discover/large/' .$extbig;
        $row_item['discover_imgbig'] = $link . 'large/' . $extbig;
    }else{
        $imgbig_path = $theLink . 'media/discover/large/' . $row_item['img'];
        $row_item['discover_imgbig'] = $link . 'large/' . $row_item['img'];
    }
    $dims = getimagesize( $imgbig_path );
    $row_item['width'] = $dims[0];
    $row_item['height'] = $dims[1];
    $media_hotelsi1[] = $row_item;
}
$data['media_hotelsicount'] =sizeof($media_hotelsi1);
$data['media_hotelsi1'] = $media_hotelsi1;

$media_hotels_reviews = userReviewsList($txt_id, SOCIAL_ENTITY_RESTAURANT, 6, 0);
$media_hotels_reviews_count = userReviewsList($txt_id, SOCIAL_ENTITY_RESTAURANT, 0, 0, true);
$media_hotels_reviews = ( !$media_hotels_reviews ) ? array() : $media_hotels_reviews;
$data['media_hotels_reviews_count'] = $media_hotels_reviews_count;

$media_reviewsArray = array();
$channelInfo_disable=NULL;
foreach ($media_hotels_reviews as $rev) {
    $media_hotels_reviewsArray = array();
    $rev_id = $rev['id'];
    $user_id_rev = $rev['user_id'];
    $userRevInfo = getUserInfo($user_id_rev);
    $logo_userRev_src = ReturnLink('media/tubers/' . $userRevInfo['profile_Pic']);
    $description_db = htmlEntityDecode($rev['description']);
    $media_hotels_reviewsArray['rev_id'] = $rev_id;
    $media_hotels_reviewsArray['logo_userRev_src'] = $logo_userRev_src;
    $media_hotels_reviewsArray['title'] = htmlEntityDecode($rev['title']);
    $media_hotels_reviewsArray['description_db'] = $description_db;
    $creator_ts = strtotime($rev['create_ts']);
    $media_hotels_reviewsArray['creator_ts'] = '<div class="log_header_time">'. formatDateAsString($creator_ts) .'</div>';
    $user_link = userProfileLink($userRevInfo);
    $media_hotels_reviewsArray['user_link'] = $user_link;
    $tuber_name_action = returnUserDisplayName($userRevInfo);
    $media_hotels_reviewsArray['tuber_name_action'] = $tuber_name_action;
    $media_hotels_reviewsArray['disp_remove'] = false;
    if($user_id==$user_id_rev){
        $media_hotels_reviewsArray['disp_remove'] = true;
    }

    $div_val = '<div id="commentDiv" class="social_data_all" data-category="" data-channel="0" data-enable="1" data-id="' . $rev_id . '" data-type="' . SOCIAL_ENTITY_RESTAURANT_REVIEWS . '">';
    $div_val .= '<div class="buttons">';
    $div_val .= '<div class="btn"><div id="likes"></div></div>';
    $div_val .= '<div class="btn_txt opacitynone"></div>';
    $div_val .= '<div class="btn btn_comments btn_enabled"><div id="comments"></div></div>';
    $div_val .= '<div class="btn_txt opacitynone btn_txt_comments btn_enabled"></div>';
    $div_val .= '<div class="btn btn_shares btn_enabled"><div id="shares"></div></div>';
    $div_val .= '<div class="btn_txt opacitynone btn_txt_shares btn_enabled"></div>';

    $div_val .= '</div>';

    $likeNumber=0;
    $commentsNumber=0;
    $sharesNumber=0;
    // --Like DIV--
    $div_val .= '<div class="likesDiv socialInitDiv hide shadow">
        <div class="closeDiv"></div>
        <div class="likeTTL">' . _("LIKES") . ' (<span class="likesNumber">' . $likeNumber . '</span>)</div>';
    if ($user_is_logged == 1 && ($userIschannel == 0 || $is_owner == 1)) {
        $div_val .= '<div class="meDiv">';
        $div_val .= '<a href="'.$s_user_link.'"><img src="' . $profilePic . '" alt="' . $fullName . '" width="45" height="45"/></a>
                        <a class="mynameDiv social_link_a" href="'.$s_user_link.'">'.$fullName.'</a>';
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
         <div class="likeTTL">' . _("COMMENTS") . ' (<span class="commentsNumber">' . $commentsNumber . '</span>)</div>';
    $comlang = _('write a comment / @T Tuber to reply');
    if ($user_is_logged == 1 && ($userIschannel == 0 || $is_owner == 1)) {
        $div_val .= '<div class="meDiv">';
        $div_val .= '<a href="'.$s_user_link.'"><img src="' . $profilePic . '" alt="' . $fullName . '" width="45" height="45"/></a>
                 <a class="mynameDiv social_link_a" href="'.$s_user_link.'">'.$fullName.'</a>';

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
            <div class="likeTTL">' . _("SHARES") . ' (<span class="sharesNumber">' . $sharesNumber . '</span>)</div>';
    if ($user_is_logged == 1 && ($userIschannel == 0 || $is_owner == 1)) {

        $div_val .= '<div class="meDiv" style="width:243px;">';
        $div_val .= '<a href="'.$s_user_link.'"><img src="' . $profilePic . '" alt="' . $fullName . '" width="45" height="45"/></a>
                            <a class="mynameDiv social_link_a" style="width:195px;" href="'.$s_user_link.'">' . $fullName . '</a>';

        $div_val .= '</div>';


        $div_val .= '<div class="formttl13 formContainer100 margintop26" style="margin-top:6px;">' . _("write something") . '</div>
                    <textarea id="invitetext" class="ChaFocus margintop5" onBlur="addValue2(this)" onFocus="removeValue2(this)" data-value="' . _("write something...") . '" style="font-family:Arial, Helvetica, sans-serif; width:644px; height:38px; background-color:#ebebeb;" type="text" name="invitetext">' . _("write something...") . '</textarea>';

        $div_val .= '<div>
                            <select id="share_select" style="margin-top:14px;">
                                    <option value="0" selected="selected" disabled="disabled">' . _('share this entry with...') . '</option>';

        $div_val .= '<option value="2" data-text="friends and followers">' . _('friends and followers') . '</option>
                                    <option value="1" data-text="friends">' . _('friends') . '</option>
                                    <option value="3" data-text="followers">' . _('followers') . '</option>';

        $div_val .= '<option value="4" data-text="custom">' . _('custom') . '</option>
                                    <option value="5" data-text="by mail">' . _('by mail') . '</option>
                            </select>
                    </div>';

        $div_val .= '<div class="formttl13 formContainer100 margintop15" style="margin-top:12px;">' . _("add people (T tubers, emails)") . '</div>
                    <div class="peoplecontainer peoplecontainer_boxed formContainer100 margintop2" style="width:646px; background:none">
                            <div class="emailcontainer_boxed emailcontainer_boxed_share" style="width:640px;">
                                    <div class="addmore"><input name="addmoretext_brochure" id="addmoretext_brochure" type="text" class="addmoretext_css" data-value="' . _("add more") . '" value="' . _("add more") . '" onFocus="removeValue2(this)" onBlur="addValue2(this)" data-id=""/></div>
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

    $media_hotels_reviewsArray['div_val'] = $div_val;
    $media_reviewsArray[] = $media_hotels_reviewsArray;
}
$data['media_hotels_reviewsArray'] = $media_reviewsArray;

$data['dataType'] = SOCIAL_ENTITY_RESTAURANT;
$data['dataId'] = $txt_id;

$toRateArr = array();
$irating1 = socialRated($user_id, $txt_id, SOCIAL_ENTITY_RESTAURANT_CUISINE);
if(!$irating1) $irating1=0;
$irating2 = socialRated($user_id, $txt_id, SOCIAL_ENTITY_RESTAURANT_SERVICE);
if(!$irating2) $irating2=0;
$irating3 = socialRated($user_id, $txt_id, SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE);
if(!$irating3) $irating3=0;
$irating4 = socialRated($user_id, $txt_id, SOCIAL_ENTITY_RESTAURANT_PRICE);
if(!$irating4) $irating4=0;
$irating5 = socialRated($user_id, $txt_id, SOCIAL_ENTITY_RESTAURANT_NOISE);
if(!$irating5) $irating5=0;
$irating6 = socialRated($user_id, $txt_id, SOCIAL_ENTITY_RESTAURANT_TIME);
if(!$irating6) $irating6=0;

$toRateArr[] = array('id'=>SOCIAL_ENTITY_RESTAURANT_CUISINE,'val'=>$irating1,'name'=>_('Cuisine'));
$toRateArr[] = array('id'=>SOCIAL_ENTITY_RESTAURANT_SERVICE,'val'=>$irating2,'name'=>_('Service'));
$toRateArr[] = array('id'=>SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE,'val'=>$irating3,'name'=>_('Atmosphere'));
$toRateArr[] = array('id'=>SOCIAL_ENTITY_RESTAURANT_PRICE,'val'=>$irating4,'name'=>_('Value for price'));
$toRateArr[] = array('id'=>SOCIAL_ENTITY_RESTAURANT_NOISE,'val'=>$irating5,'name'=>_('Noise level'));
$toRateArr[] = array('id'=>SOCIAL_ENTITY_RESTAURANT_TIME,'val'=>$irating6,'name'=>_('Waiting time'));

$data['toRateArr'] = $toRateArr;

$nearArr = array();
$nearArr[] = array('id' => '1', 'name' => _('Nightlife'));
$nearArr[] = array('id' => '2', 'name' => _('Museums'));
$nearArr[] = array('id' => '3', 'name' => _('Outdoor'));
$nearArr[] = array('id' => '4', 'name' => _('Shopping'));

$data['nearArr'] = $nearArr;
$data['nearArrCount'] = sizeof($nearArr);

include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
