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
$template = $twig->loadTemplate('things2do.twig');

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$userIschannel=userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;
$is_owner =0;

$user_id = userGetID();
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

if( $userIschannel == 0 && $user_is_logged==1){
    $profilePic = ReturnLink('media/tubers/small_'.$userInfo['profile_Pic'] );
    $fullNameStan = htmlEntityDecode($userInfo['FullName']);
    $fullName = returnUserDisplayName($userInfo, array('max_length' => 25));
}else{
    $profilePic = ReturnLink('media/tubers/small_he.jpg' );
}

$includes = array('css/hotel-resto.css', "js/jscal2.js", "js/jscal2.en.js", 'js/things2do.js', 'css/jscal2.css','media'=>'css_media_query/media_style_static_page.css?v='.MQ_MEDIA_STYLE_CSS_V,'media1'=>'css_media_query/things2-do-review_media.css?v='.MQ_HOTEL_REVIEW_CSS_V,'css/social-actions.css?v='.SOCIAL_ACTIONS_CSS_V,);

$includes[] = 'js/language_bar.js'; //the js of the language bar
$includes[] = 'css/language_bar.css'; //the css of the language bar
$includes[] = 'css/social-actions.css?v='.SOCIAL_ACTIONS_CSS_V;
$includes[] = 'js/social-actions.js';

$txt_id_init = intval(UriGetArg('id'));
$txt_id = ($txt_id_init == 0) ? null : $txt_id_init;
if ($txt_id_init == 0) {
    header("location:" . ReturnLink(''));
}

$search_val = xss_sanitize(UriGetArg('s'));

$dt_val = xss_sanitize(UriGetArg('d'));
$from_date = 'dd / mm / yyyy';
if ($dt_val != '') {
    $from_date = date('d/m/Y', strtotime($dt_val));
}
$time_val = xss_sanitize(UriGetArg('t'));
$time_val = isset($time_val) ? $time_val : '';

if ($dt_val != '') {
    $date_val .= $dt_val . ' ';
}
if ($time_val != '') {
    $date_val .= $time_val;
}
$person_val = intval(UriGetArg('persons'));

$data['search_val'] = $search_val;
$data['date_val'] = $date_val;
$data['dt_val']         =   $dt_val;
$data['person_val']         =   $person_val;
$data['from_date'] = $from_date;

global $dbConn;
$params = array();  
//$query_poi = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = $txt_id ORDER BY default_pic DESC LIMIT 29";
$query_poi = "SELECT id , filename as img , poi_id FROM `discover_poi_images` WHERE poi_id = :Txt_id ORDER BY default_pic DESC LIMIT 29";
$params[] = array(  "key" => ":Txt_id",
                    "value" =>$txt_id);
$select = $dbConn->prepare($query_poi);
PDO_BIND_PARAM($select,$params);
$ret_hotels    = $select->execute();
$media_poi = array();
//$ret_hotels = db_query($query_poi);
$media_poi = $select->fetchAll();
//while ($row = db_fetch_array($ret_hotels)) {
//    $media_poi[] = $row_item;
//}
$irating_average = socialRateAverage( $txt_id, array(SOCIAL_ENTITY_LANDMARK) );
$irating=socialRated($user_id, $txt_id, SOCIAL_ENTITY_LANDMARK );
if(!$irating) $irating=0;

$general_facilities = '';
$services = '';
$poi_data = getPoiInfo($txt_id);

$media_poi_reviews = userReviewsList($txt_id,SOCIAL_ENTITY_LANDMARK,6,0);
$media_poi_reviews_count = userReviewsList($txt_id,SOCIAL_ENTITY_LANDMARK,0,0,true);

$dims = imageGetDimensions($CONFIG['server']['root'] . 'media/discover/' . $media_poi[0]['img']);

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
$style_pic = "width: {$new_width}px; height: {$new_height}px;";

$title = htmlEntityDecode($poi_data['name']);
$locationText = '';
if ($poi_data['city_id'] != '0') {
    $city_array = worldcitiespopInfo(intval($poi_data['city_id']));
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
    $locationText = $poi_data['location'];
}
if ($locationText != '') {
    $title .= ', ' . $locationText;
}
tt_global_set('title', $title);


$data['link']           =   $link;
$data['user_id']        =   $user_id;
$data['user_is_logged']=   $user_is_logged;
$data['userIschannel']  =   $userIschannel;
$data['is_owner']       =   $is_owner;
$data['txt_id']         =   $txt_id;
$data['SOCIAL_ENTITY_LANDMARK'] =   SOCIAL_ENTITY_LANDMARK;
$data['media_poi']      =   $media_poi;
$data['poi_data']       =   $poi_data;
$data['style_pic']      =   $style_pic;
$data['poi_data_name']  =   htmlEntityDecode($poi_data['name']);
$data['media_poi_reviews_count'] =   $media_poi_reviews_count;
$data['irating_average']=   $irating_average;
$data['locationText']   =   $locationText;
$data['irating']        =   $irating;
$data['show_on_map']     =   ReturnLink('parts/show-on-map.php?type=p&id=' . $poi_data['id']);


$media_poiArray =   array();
for ($i = 0; $i < min(count($media_poi), 29); $i++) {
    $aMedia_poiArray  =   array();
    $dims = imageGetDimensions($CONFIG['server']['root'] . 'media/discover/' . $media_poi[$i]['img']);
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

    $media_poiArray[] =    '<img src="' . $link . 'thumb/' . $media_poi[$i]['img'] . '" alt="' . $media_poi[$i][1] . '" data-src="' . $link . $media_poi[$i]['img'] . '" data-w="' . $new_width . '" data-h="' . $new_height . '" class="hotelListPic" id="hotelListPic_' . $i . '" />';
   
}
$data['media_poiArray'] =   $media_poiArray;

$data['count_media_poi'] =   count($media_poi);

$channelInfo_disable=NULL;
if ( $media_poi_reviews_count > 0):
    $media_poi_reviewsArray =   array();
    foreach ($media_poi_reviews as $rev) {
        $aMedia_poi_reviewsArray =   array();

        $rev_id = $rev['id'];
        $user_id_rev = $rev['user_id'];
        $userRevInfo = getUserInfo($user_id_rev);
        $logo_userRev_src = ReturnLink('media/tubers/' . $userRevInfo['profile_Pic']);
        $description_db = htmlEntityDecode($rev['description']);

        $aMedia_poi_reviewsArray['rev_id']       =   $rev_id;
        $aMedia_poi_reviewsArray['user_id_rev']  =   $rev['user_id'];
        $aMedia_poi_reviewsArray['userRevInfo']  =   getUserInfo($user_id_rev);
        $aMedia_poi_reviewsArray['logo_userRev_src']  = ReturnLink('media/tubers/' . $userRevInfo['profile_Pic']);
        $aMedia_poi_reviewsArray['description_db']  =   htmlEntityDecode($rev['description']);
        $aMedia_poi_reviewsArray['rev_title']       =   htmlEntityDecode($rev['title']);

        $div_val = '<div id="commentDiv" class="social_data_all" data-category="" data-enable="1" data-id="'.$rev_id.'" data-type="'.SOCIAL_ENTITY_LANDMARK_REVIEWS.'">';
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
                            <div class="likeTTL">'._("LIKES").' (<span class="likesNumber">'.$likeNumber.'</span>)</div>';
        if( $user_is_logged==1  && ($userIschannel==0 || $is_owner==1)){
            $div_val .= '<div class="meDiv">';
            $div_val .= '<img src="'.$profilePic.'" alt="'.$fullName.'" width="45" height="45"/>
                                            <div class="mynameDiv">'.$fullName.'</div>';
            if(is_null($channelInfo_disable)){
                $div_val .= '<div class="likeDiv" data-action="" data-media=""></div>';
            }
            $div_val .= '</div>';

        }else{
            $div_val .= '<div class="toplikes">
                                                    <div class="likepop"></div>
                                                    <div class="meStanDiv displaynone">
                                                            <div class="meStanDivText">'._('you need to have a').' <a class="black_link" href="'.ReturnLink('/register').'" target="_blank">'._('TT account').'</a><br />'._('in order to like').'</div>
                                                    </div>
                                            </div>';
        }
        $div_val .= '<div class="containerDiv" style="height:auto; overflow:hidden;">

                                    <div class="commentsAll" style="max-height:446px;"><div class="social_not_refreshed"></div></div>
                            </div>
                            <div class="showMore_like showMore">'._('show more').'</div>
                        </div>';

        //-- Comment DIV--
        $div_val .= '<div class="commentsDiv socialInitDiv hide shadow">
                             <div class="closeDiv"></div>
                             <div class="likeTTL">'._("COMMENTS").' (<span class="commentsNumber">'.$commentsNumber.'</span>)</div>';
        $comlang=_('write a comment / @T Tuber to reply');
        if( $user_is_logged==1 && ($userIschannel==0 || $is_owner==1)){
            $div_val .= '<div class="meDiv">';
            $div_val .= '<img src="'.$profilePic.'" alt="'.$fullName.'" width="45" height="45"/>
                                     <div class="mynameDiv">'.$fullName.'</div>';

            $div_val .= '<div class="writecommentDiv writecommentDivAuto">
                                             <div class="examples">
                                               <textarea class="mention textareaclass" placeholder="'.$comlang.'" style="height: 28px; overflow: hidden;"></textarea>
                                             </div>
                                     </div>
                             </div>';
        }else{
            $div_val .= '<div class="meStanDiv_comment displaynone disabledmessage">
                                             '._('you need to have a').' <a class="black_link" href="'.ReturnLink('/register').'" target="_blank">'._('TT account').'</a><br />'._('in order to write a comment').'
                                     </div>
                                     <div class="meDiv">
                                             <div class="writecommentDiv"><input type="text" name="commentText" value="'.$comlang.'" onblur="addValueInput(this)" onFocus="removeValueInput(this)" data-value="'.$comlang.'" class="notsigned" data-mode="photo"/></div>

                                     </div>';
        }
        $div_val .= '<div class="containerDiv">
                                     <div class="commentsAll" style="max-height:412px;"></div>
                             </div>
                             <div class="showMore_comments showMore">'._('show more').'</div>

                        </div>';

        //-- Share DIV--
        $div_val .= '<div class="sharesDiv socialInitDiv hide shadow">
                                <div class="closeDiv"></div>
                                <div class="likeTTL">'._('SHARES').' (<span class="sharesNumber">'.$sharesNumber.'</span>)</div>';
        if( $user_is_logged==1  && ($userIschannel==0 || $is_owner==1)){

            $div_val .= '<div class="meDiv" style="width:243px;">';
            $div_val .= '<img src="'.$profilePic.'" alt="'.$fullName.'" width="45" height="45"/>
                                                <div class="mynameDiv" style="width:195px;">'.$fullName.'</div>';

            $div_val .= '</div>';


            $div_val .= '<div class="formttl13 formContainer100 margintop26" style="margin-top:6px;">'._("write something").'</div>
                                        <textarea id="invitetext" class="ChaFocus margintop5" onBlur="addValue2(this)" onFocus="removeValue2(this)" data-value="'._("write something...").'" style="font-family:Arial, Helvetica, sans-serif; width:644px; height:38px; background-color:#ebebeb;" type="text" name="invitetext">'._('write something...').'</textarea>';

            $div_val .= '<div>
                                                <select id="share_select" style="margin-top:14px;">
                                                        <option value="0" selected="selected" disabled="disabled">'._('share this entry with...').'</option>';

            $div_val .= '<option value="2" data-text="friends and followers">'._('friends and followers').'</option>
                                                        <option value="1" data-text="friends">'._('friends').'</option>
                                                        <option value="3" data-text="followers">'._('followers').'</option>';

            $div_val .= '<option value="4" data-text="custom">'._('custom').'</option>
                                                        <option value="5" data-text="by mail">'._('by mail').'</option>
                                                </select>
                                        </div>';

            $div_val .= '<div class="formttl13 formContainer100 margintop15" style="margin-top:12px;">'._("add people (T tubers, emails)").'</div>
                                        <div class="peoplecontainer peoplecontainer_boxed formContainer100 margintop2" style="width:646px; background:none">
                                                <div class="emailcontainer_boxed emailcontainer_boxed_share" style="width:640px;">
                                                        <div class="addmore"><input name="addmoretext_brochure" id="addmoretext_brochure" type="text" class="addmoretext_css" value="'._("add more").'" data-value="'._("add more").'" onFocus="removeValue2(this)" onBlur="addValue2(this)" data-id=""/></div>
                                                </div>
                                                <div id="share_boxed_send" class="sharepopup_but2 sharepopup_buts">'._('send').'</div>
                                        </div>';


        }else{
            $div_val .= '<select id="share_select1" onChange="share_selectDisabled(this)">
                                                <option value="0" selected="selected" disabled="disabled">'._('share this entry with...').'</option>
                                                <option value="2">'._('friends and followers').'</option>
                                                <option value="1">'._('friends').'</option>
                                                <option value="3">'._('followers').'</option>
                                                <option value="4">'._('custom').'</option>
                                                <option value="5">'._('by mail').'</option>
                                        </select>';
            $div_val .= '<div class="meStanDiv_comment meStanDiv_share displaynone disabledmessage">
                                                '._('you need to have a').' <a class="black_link" href="'.ReturnLink('/register').'" target="_blank">'._('TT account').'</a><br />'._('in order to share this entry.').'
                                        </div>';
        }
        $div_val .= '<div class="containerDiv">
                                        <div class="commentsAll" style="max-height:446px;"></div>
                                </div>
                                <div class="showMore_shares showMore">'._('show more').'</div>
                                </div>';
        $div_val .= '</div>'; //-- CommentDiv. --
        //   echo $div_val;
        $aMedia_poi_reviewsArray['div_val']    =  $div_val;
        $media_poi_reviewsArray[]    =   $aMedia_poi_reviewsArray;

    }
    $data['media_poi_reviewsArray'] =   $media_poi_reviewsArray;
    endif;

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
$data['tubersArray']    =   $tubersArray;

$data['friend_array_count'] =   $friend_array_count;
$data['followers_array_count'] =   $followers_array_count;


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