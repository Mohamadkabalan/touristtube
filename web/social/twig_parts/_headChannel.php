<?php

$includePart = '';
$searchCategoryBtn = '';
$userIschannel=userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;
$data['secondOpen'] = FALSE;
$data['pagecurname'] = _seoExecutingFile();
checkChannelSubdomain();
if (in_array(tt_global_get('page'), array('things2do.php', 'thotel.php', 'thotels.php', 'three-sixty.php', 'trestaurant.php', 'trestaurants.php','map.php','discover.php','planner.php','hotel-search.php','restaurant-search.php'))) { 
    $loggedUser = userGetID();
    if( $loggedUser !=70 && $loggedUser !=25 ) header('Location:' . ReturnLink('/') );
}
if (($includes = tt_global_get('includes'))) {
    foreach ($includes as $key=>$include) {
        if (strstr($include, '.js') != null) {
            $includePart .= sprintf('<script type="text/javascript" src="%s"></script>', ReturnLink($include));
        } else if (strstr($include, '.css') != null) {
            if( $key==='media' && RESPONSIVE){            
                $includePart .= sprintf('<link href="%s" rel="stylesheet" type="text/css" media="screen"/>', ReturnLink($include));
            }else{
                $includePart .= sprintf('<link href="%s" rel="stylesheet" type="text/css"/>', ReturnLink($include));
            }
        }
        $includePart .= "\r\n";
    }
}
if (($includesIE8 = tt_global_get('includesIE8'))) {
    foreach ($includesIE8 as $include) {
        if (strstr($include, '.js') != null) {
            $includePart .= sprintf('<!--[if lte IE 8]><script type="text/javascript" src="%s"></script><![endif]-->', ReturnLink($include));
        } else if (strstr($include, '.css') != null) {
            $includePart .= sprintf('<!--[if lte IE 8]><link href="%s" rel="stylesheet" type="text/css"/><![endif]-->', ReturnLink($include));
        }
        $includePart .= "\r\n";
    }
}

$category_id = UriArgIsset('cat_id') ? UriGetArg('cat_id') : tt_global_get('category_id');
$selectedCategory = UriArgIsset('cat_id') ? get_cat_name(UriGetArg('cat_id')) : tt_global_get('category_name');

//$t = ( isset($_GET['t']) && $_GET['t'] <> '' ) ? $_GET['t'] : 'a';
//$order = ( isset($_GET['order']) && $_GET['order'] <> '' ) ? $_GET['order'] : '';
$t = $request->query->get('t','a');
$order = $request->query->get('order','');

if ($t == 'a')
    $searchCategoryBtn = _('All Media');
elseif ($t == 'i')
    $searchCategoryBtn = _('Photos');
elseif ($t == 'v')
    $searchCategoryBtn = _('Videos');
//else if($search_cat == 'h') $searchCategoryBtn = _('Hotels');
//else if($search_cat == 'r') $searchCategoryBtn = _('Restaurants');
elseif ($t == 'u')
    $searchCategoryBtn = _('Friends');

$search = urldecode(UriGetArg(0));

if (channelGlobalSearchGet()) {
    $search = channelGlobalSearchGet();
} else {
    $search = _('Search Channels...');
}
$data['responsiveCondition'] = false;//( in_array(tt_global_get('page'), array('index.php','search.php','video.php','photo.php','album.php','photo-album.php','video-album.php')) );
$data['containVideo'] = ( in_array(tt_global_get('page'), array('photo.php', 'video.php', 'video1.php', 'photo1.php', 'photo-album1.php', 'photo-album.php', 'album.php', 'video-album.php')) );
$data['containFCBShare'] = (  in_array(tt_global_get('page'), array( 'photo.php' , 'video.php', 'video1.php' ,'photo1.php','photo-album1.php', 'photo-album.php', 'album.php' , 'video-album.php','live-cam.php','hotel-review.php','restaurant-review.php','things2do-review.php','airport-review.php','channel.php') ) );
$data['currentid'] = (isset($current_id))? $current_id:0;
$data['entitytype'] = (isset($entity_type))? $entity_type:0;
$data['isowner'] = $is_owner;
$data['userislogged2'] = $user_is_logged;
$data['thumbpath'] = '';
$data['channelGlobId'] = (isset($VideoInfo['channelid']))? $VideoInfo['channelid']:0;
$data['OriginalUrl']= UriCurrentPageURL();
$data['channels_page_link'] =ReturnLink('channels', null , 0, 'channels');
$data['login_page_link'] =ReturnLink('login');

//$ur_array = UriCurrentPageURLForLanguage();
//$langarray = array('en'=>'www','fr'=>'fr', 'hi'=>'in');
$ur_arraydata='';
//foreach($langarray as $lang_key => $lang_val){
//    if ($lang_key != 'en')
//            $langUrl      = $ur_array[0].$ur_array[1].'/'.$lang_val.'/'.$ur_array[2];
//    else $langUrl      = $ur_array[0].$ur_array[1].'/'.$ur_array[2];
//    $ur_arraydata .='<link rel="alternate" hreflang="'.$lang_key.'" href="'.$langUrl.'" itemprop="url"/>';
//}
$data['ur_arraydata'] =$ur_arraydata;

$data['searchTxt'] = $search;
$data['searchValueTxt'] = htmlspecialchars(urldecode(UriGetArg('t')));
if ($selectedCategory == '') {
    $selectedCategory = 'all categories';
}
//head Part
$data['favicon'] = ReturnLink('media/images/favicon.ico');
$data['RESPONSIVE'] = RESPONSIVE;

list ($page_title, $page_desc, $page_meta) = seoTextGet();

$data['seoPageTitle'] = $page_title;
$data['seoPageDescription'] = $page_desc;
$data['seoPageKeywords'] = $page_meta;

$data['jqueryLink'] = ReturnLink("assets/vendor/jquery/dist/jquery-1.9.1.min.js");
$data['migrateLink'] = ReturnLink("js/jquery-migrate-1.1.1.js");
$data['cookieLink'] = ReturnLink("js/jquery.cookie.js");
$data['jqueryi18n'] = ReturnLink("assets/vendor/jquery/plugins/il8n/js/jquery.i18n.js");
$data['pofiledistpofile'] = ReturnLink("js/pofile/dist/pofile.js");
$data['requestinvitationLink'] = ReturnLink("js/requestinvitation.js");
$data['mousestopLink'] = ReturnLink("js/jquery.mousestop.js");
$data['utilsLink'] = ReturnLink("assets/common/js/utils.js");
$data['initLink'] = ReturnLink("js/init.js");
$data['mousewheelLatestLink'] = ReturnLink("js/jquery.mousewheel_latest.js");
$data['jscrollpaneLink'] = ReturnLink("js/jquery.jscrollpane.js");
$data['runActiveContentLink'] = ReturnLink("js/AC_RunActiveContent.js");
$data['toolsLink'] = ReturnLink("js/jquery.tools.js");
$data['hammer4Link'] = ReturnLink("js/hammer4.js");
$data['hammerLink'] = ReturnLink("js/jquery.hammer.js");
$data['noselectLink'] = ReturnLink("js/jquery.noselect.js");
$data['sizesLink'] = ReturnLink("js/jquery.sizes.js");
$data['scrollFixLink'] = ReturnLink("js/scroll-fix.js");
$data['preloader2Link'] = ReturnLink("js/jquery.preloader2.js");
$data['pluploadFullLink'] = ReturnLink("assets/uploads/js/plupload.full.js");
$data['jqueryUiLink'] = ReturnLink("js/jquery-ui-1.10.0.custom.js");
$data['placeholdersLink'] = ReturnLink("js/placeholders.jquery.js");
$data['json2Link'] = ReturnLink("js/json2.js");
$data['fullscreenLinkJs'] = ReturnLink("js/jquery.fullscreen.js");
$data['carouselLiteJs'] = ReturnLink("js/jcarousellite_1.0.1c5.js");
$data['jscal2Js'] = ReturnLink("js/jscal2.js");
$data['jscal2EnJs'] = ReturnLink("js/jscal2.en.js");
$data['allCss'][] = ReturnLink("css/jquery.jscrollpane.css");
$data['allCss'][] = ReturnLink("css/css.css");
$data['allCss'][] = ReturnLink("css/jquery-ui-1.10.0.custom.css");
$data['allCss'][] = ReturnLink("css/jquery.fancybox-new.css?v=2.0.5");
if (isset($myEventsCalendarPage))
    $myEventsCalendarPage = intval($myEventsCalendarPage);
else
    $myEventsCalendarPage = '';
if ($myEventsCalendarPage != 1) {
    $data['allCss'][] = ReturnLink("css/jscal2.css");
} else {
    $data['allCss'][] = ReturnLink("css/jscal2_events_calendar.css");
}
$MAP_KEY = '';        
$pagehttp = getUriPageURLHTTP();
if($pagehttp =='https') $MAP_KEY = '&key='.MAP_KEY;
$data['mapscrip'] ='';
if (in_array(tt_global_get('page'), array('map.php', 'live.php', 'channel-upload.php', 'channel-upload-list.php', 'channel-upload-album.php', 'channel-log.php', 'channel-notifications.php'))) {
    $data['mapscrip'] .= '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true'.$MAP_KEY.'"></script>';
}
$data['mapscrip'] .= '<script type="text/javascript" src="' . ReturnLink("js/jquery.fancybox.js") . '"></script>';
$data['userIsLogged'] = userIsLogged() ? 1 : 0;
$data['uploadMaxFileSize'] = intval(ini_get('upload_max_filesize'));
$data['page'] = tt_global_get('page');
$data['mapPhp'] = array('map.php');
$data['underscoreJs'] = ReturnLink("js/underscore-min.js");
$data['elasticJs'] = ReturnLink("js/jquery.elastic.js");
$data['timeentryJs'] = ReturnLink("js/jquery.timeentry.js");
$data['timeentryJs'] = ReturnLink("js/jquery.timeentry.js");
$data['mentionsInputJs'] = ReturnLink("js/jquery.mentionsInput.js");
$data['ratyJs'] = ReturnLink("js/jquery.raty.js");

$data['curvycornersJs'] = ReturnLink("js/curvycorners.js");
$data['ie7Css'] = ReturnLink("css/css-ie7.css");
$data['fancyboxJs'] = ReturnLink("js/jquery.fancybox.js");
$data['fancyboxCss'] = ReturnLink("css/jquery.fancybox-new.css");
$data['includePart'] = $includePart;
$data['js_local'] = $js_local;
$data['lang_local'] = LanguageGet();

$current_channel = userCurrentChannelGet();
$channel_arrayDF=channelGetInfo($current_channel['id']);
if($channel_arrayDF['published']==0){
    userCurrentChannelReset();
    $current_channel = userCurrentChannelGet();
}
$welcome_name = userGetName();
$loggedUser = userGetID();
$data['loggedUser'] = $loggedUser;
$welcome_link = userProfileLink(getUserInfo($loggedUser));
$switch_id = 0;
if(!isset($myEventsPage)) $myEventsPage=0;
$myEventsPage = intval($myEventsPage);
$data['is_owner'] = $is_owner;
$welcome_nameinit='';
if ($is_owner == 1) {
    $val_db = htmlEntityDecode($channelInfo['channel_name']);
    $welcome_nameinit = $val_db;
    $welcome_name = substr($val_db, 0, 17);
    $welcome_link = ReturnLink('channel/' . $channelInfo['channel_url']);
    if (strlen($val_db) > 18) {
        $welcome_name = $welcome_name . ' ...';
    }
} else if ($current_channel && sizeof($current_channel) != 0) {
    $switch_id = $current_channel['id'];
    $val_db = htmlEntityDecode($current_channel['channel_name']);
    $welcome_nameinit = $val_db;
    $welcome_name = substr($val_db, 0, 17);
    $welcome_link = ReturnLink('channel/' . $current_channel['channel_url']);
    if (strlen($val_db) > 18) {
        $welcome_name = $welcome_name . ' ...';
    }
    $userIschannel = 1;
}
$data['welcome_name'] = $welcome_name;
$data['welcome_nameinit'] = $welcome_nameinit;
$data['welcome_link'] = $welcome_link;
$channelupID = false;

if (userIsLogged()) {
    $channelInfobyURL = channelFromURL(UriGetArg(0));
    $channelInfobyID = channelFromID(UriGetArg(0));

    $channelTopArray=array();
    if ($channelInfobyURL) {
        $channelTopArray = $channelInfobyURL;
    } else if ($channelInfobyID) {
        $channelTopArray = $channelInfobyID;
    }
    $channelupID = (sizeof($channelTopArray)>0)? $channelTopArray['id']:false;
    if( !$channelupID && $current_channel && sizeof($current_channel) != 0 ){
        $channelupID = $current_channel['id'];
        $channelTopArray = $current_channel;
    }
    $optionsnewlength = array(
        'user_id' => userGetID(),
        'channel_id' => $channelupID,
        'n_results' => true
    );
    $tempcount =videoTemporaryGetAll($optionsnewlength);
    if($tempcount==0){
        $srch_options2 = array(
            'user_id' => userGetID(),
            'channelid' => $channelupID,
            'n_results' => true
        );
        $tempcount=videoTemporaryGetAlbums($srch_options2);
    }
                                                    
    $data['channelUpLink'] = ReturnLink('channel-upload/' . $channelupID);
    $data['addContentTxt'] = _('add content');
    $data['tempcount'] = $tempcount;
    $data['channellistLink'] = ReturnLink('channel-upload/' . $channelupID);
}
$data['channelCondition'] = ( ($channelupID ) && ((userGetID() == $channelTopArray['owner_id'] && sizeof(UriGetArg(0)) != 0) || ($myEventsPage != 0 && $switch_id != 0)) );
//body Part
$data['searchCategoryBtn'] = $searchCategoryBtn;
$data['t'] = $t <> '' ? $t : 'a';
$data['visitedClass'] = 'class="visited"';
$data['order'] = $order;
$data['indexLink'] = ReturnLink('');
$data['uploadLink'] = ReturnLink('upload');
$data['registerLink'] = ReturnLink('register');
$data['myprofileLink'] = ReturnLink('myprofile');
$data['CreateChannelLink'] = ReturnLink('/channel-add');
$data['CreateChannelTxt'] = _('create my channel');
$data['searchLink'] = ReturnLink('search');
$data['userGetName'] = userGetName();
$data['userChannel'] = userIsChannel();
$data['userIsChannel'] = $userIschannel;
$data['category_id'] = $category_id;
$data['visited'] = ReturnLink('media/images/top_menu_expand.jpg');
$data['loginText'] = _('SIGN IN');
$data['uploadText'] = _('upload');
$data['registerText'] = _('register');
$data['welcomeText'] = _('Welcome');
$data['allMediaText'] = _('All Media');
$data['tubersText'] = _('Friends');

$data['infLink'] = ReturnLink('account/info');
$data['photosLink'] = ReturnLink('myprofile/photos');
$data['videosLink'] = ReturnLink('myprofile/videos');
$data['albumsLink'] = ReturnLink('myprofile/albums');
$data['logoutLink'] = ReturnLink('logout');
$data['channelSearchLink'] = ReturnLink('channel-search', null , 0, 'channels');
$data['channelUpAlbumLink'] = ReturnLink('channel-upload/' . $channelupID);
$data['channelAddAlbumLink'] = ReturnLink('channel-add-brochure/' . $channelupID);
$data['channelAddEventLink'] = ReturnLink('channel-add-event/' . $channelupID);
$data['channelAddNewsLink'] = ReturnLink('channel-add-news/' . $channelupID);
$data['viewAllTxt'] = _('view all');
$data['switchToTxt'] = _('switch to');
$data['chat_server'] = $CONFIG ['chat_server'];
$search = urldecode(UriGetArg(0));

if (channelGlobalSearchGet()) {
    $search = channelGlobalSearchGet();
} else {
    $search = _('Search Channels...');
}

$options = array('owner_id' => userGetID(), 'published' => 1, 'page' => 0, 'limit' => 4);
$channelList = channelSearch($options);

$options2 = array('owner_id' => userGetID(), 'published' => 1, 'page' => 0, 'limit' => null);
$channelListAll = channelSearch($options2);
$data['channelListAll'] = tt_number_format(count($channelListAll));
$data['channelDisabled'] = '';
if (count($channelListAll) <= 12) {
    $data['channelDisabled'] = ' disabled';
}
$m = 0;
$channelAll = array();
if(is_array($channelListAll)){
    foreach ($channelListAll as $channel) {
        $aChannel = array();
        $top_channel_id = $channel['id'];
        $top_channel_name_stan = htmlEntityDecode($channel['channel_name']);
        $top_channel_name = substr($top_channel_name_stan, 0, 22);
        if (strlen($top_channel_name_stan) > 22) {
            $top_channel_name = $top_channel_name . ' ...';
        }
        $top_channel_url = $channel['channel_url'];
        $top_channel_logo = '<img src="' . photoReturnchannelLogo($channel) . '" alt="' . $top_channel_name_stan . '" width="28" height="28">';
        //
        if (is_arabic($top_channel_name_stan)) {
            $classnew = " arabic";
        } else {
            $classnew = "";
        }
        $aChannel['li'] = '';
        $aChannel['active'] = '';
        if ($m % 12 == 0 && $m != 0) {
            $aChannel['li'] = '</li><li>';
        }
        $aChannel['top_channel_id'] = $top_channel_id;
        $aChannel['top_channel_name_stan'] = $top_channel_name_stan;
        $aChannel['top_channel_name'] = $top_channel_name;
        $aChannel['top_channel_logo'] = $top_channel_logo;
        $aChannel['top_channel_url'] = ReturnLink('channel/' . $top_channel_url);
        $aChannel['classnew'] = $classnew;
        if ($switch_id == $top_channel_id) {
            $aChannel['active'] = ' active';
        }
        $channelAll[] = $aChannel;
        $m++;
    }
}
$data['channelAll'] = $channelAll;

if (!userIsChannel()) {
    $userInfo = getUserInfo(userGetID());
    $profilePic = $userInfo['profile_Pic'];
    $fullNameStan = htmlEntityDecode($userInfo['FullName']);
    $fullName = cut_sentence_length($fullNameStan, 25);
    $ownerPageCondition = ($is_owner == 0 && $myEventsPage == 0);
    if ($switch_id == 0) {
        $data['profileAcive'] = ' active';
    } else {
        $data['profileAcive'] = '';
    }
    $data['ownerPageCondition'] = $ownerPageCondition;
    $data['fullNameStan'] = $fullNameStan;
    $data['profilePicImg'] = ReturnLink('media/tubers/' . $profilePic);
    $data['fullName'] = $fullName;
}
$channelListArr = array();
if(is_array($channelList)){
    foreach ($channelList as $channel) {
        $aChannelListArr = array();
        $top_channel_id = $channel['id'];
        $top_channel_name_stan = htmlEntityDecode($channel['channel_name']);
        $top_channel_name = substr($top_channel_name_stan, 0, 21);
        if (strlen($top_channel_name_stan) > 21) {
            $top_channel_name = $top_channel_name . ' ...';
        }

        $top_channel_url = $channel['channel_url'];
        $top_channel_logo = '<img src="' . photoReturnchannelLogo($channel) . '" alt="' . $top_channel_name_stan . '" width="28" height="28">';

        if (is_arabic($top_channel_name_stan)) {
            $classnew = " arabic";
        } else {
            $classnew = "";
        }
        $aChannelListArr['active'] = '';
        $aChannelListArr['top_channel_id'] = $top_channel_id;
        $aChannelListArr['classnew'] = $classnew;
        $aChannelListArr['top_channel_name_stan'] = $top_channel_name_stan;
        $aChannelListArr['top_channel_name'] = $top_channel_name;
        $aChannelListArr['top_channel_url'] = $top_channel_url;
        $aChannelListArr['top_channel_logo'] = $top_channel_logo;
        $aChannelListArr['top_channel_url'] = ReturnLink('channel/' . $top_channel_url);
        if ($switch_id == $top_channel_id) {
            $aChannelListArr['active'] = ' active';
        }
        $channelListArr[] = $aChannelListArr;
        $data['channelListArr'] = $channelListArr;
    }
}
$data['toHideCond'] = ( isset($channelListAll) && sizeof($channelListAll) > 4 );

$data['switch_id'] = $switch_id;
$data['infText'] = _('Account Info');
$data['friendsText'] = _('Friends');
$data['newsfeedText'] = _('feeds');
$data['photosText'] = _('Photos');
$data['videosText'] = _('Videos');
$data['albumsText'] = _('Albums');
$data['albums2Text'] = _('albums');
$data['eventsText'] = _('events');
$data['newsText'] = _('news');
$data['brochuresText'] = _('brochures');
$data['journalText'] = _('journal');
$data['privacyText'] = _('my privacy');
$data['logoutText'] = _('sign out');

require_once $CONFIG ['server']['root'] . "twig_parts/slotMachineChannel.php";
$data['tmzone'] = checkTimeZoneCookie(); 
