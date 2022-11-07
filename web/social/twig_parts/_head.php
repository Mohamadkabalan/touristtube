<?php
$includePartCss = '';
$includePartJs = '';
$searchCategoryBtn = '';
$userIschannel=userIsChannel();
$data['secondOpen'] = FALSE;
$loggedUser = userGetID();

if (in_array(tt_global_get('page'), array('three-sixty.php'))) { 
    $loggedUser = userGetID();
    if( $loggedUser !=70 && $loggedUser !=25 ) header('Location:' . ReturnLink('/') );
}
//var_dump(tt_global_get('includes'));
if( ($includes = tt_global_get('includes')) ){
    foreach($includes as $key=>$include){
        if(strstr($include,'.css') != null){
            if($key==='media' && RESPONSIVE ){
                $includePartCss .= sprintf('<link href="%s" rel="stylesheet" type="text/css" media="screen"/>',ReturnLink($include));
            }else{
                $includePartCss .= sprintf('<link href="%s" rel="stylesheet" type="text/css"/>',ReturnLink($include));
            }
            $includePartCss .= "\r\n";
        }else if(strstr($include, '.js') != null){
            $includePartJs .= sprintf('<script type="text/javascript" src="%s"></script>',ReturnLink($include));
            $includePartJs .= "\r\n";
        }
    }
}
if( ($includesIE8 = tt_global_get('includesIE8')) ){
    foreach($includesIE8 as $include){
        if(strstr($include,'.css') != null){
            $includePartCss .= sprintf('<!--[if lte IE 8]><link href="%s" rel="stylesheet" type="text/css"/><![endif]-->',ReturnLink($include));
            $includePartCss .= "\r\n";
        }else if(strstr($include, '.js') != null){
            $includePartJs .= sprintf('<!--[if lte IE 8]><script type="text/javascript" src="%s"></script><![endif]-->',ReturnLink($include));
            $includePartJs .= "\r\n";
        }
    }
}
$data['OriginalUrl']= UriCurrentPageURL();
$data['loggedUser'] = $loggedUser;
$data['RESPONSIVE'] = RESPONSIVE;
$data['responsiveCondition'] = false;//( in_array(tt_global_get('page'), array('index.php','search.php','video.php','photo.php','album.php','photo-album.php','video-album.php')) );
$data['notContainIndex'] = ( !in_array(tt_global_get('page'), array( 'index.php' ) ) );
$data['notContainVideo'] = ( !in_array(tt_global_get('page'), array( 'photo.php' , 'video.php', 'video1.php' , 'photo-album.php', 'photo-album1.php', 'album.php' , 'video-album.php' ) ) );
$data['containVideo'] = (  in_array(tt_global_get('page'), array( 'photo.php' , 'video.php', 'video1.php' ,'photo1.php','photo-album1.php', 'photo-album.php', 'album.php' , 'video-album.php' ) ) );
$data['containFCBShare'] = (  in_array(tt_global_get('page'), array( 'photo.php' , 'video.php', 'video1.php' ,'photo1.php','photo-album1.php', 'photo-album.php', 'album.php' , 'video-album.php','live-cam.php','hotel-review.php','restaurant-review.php','things2do-review.php','airport-review.php') ) );
$data['notContainVideoIndex'] = ( !in_array(tt_global_get('page'), array( 'photo.php' , 'video.php', 'video1.php' ,'photo1.php','photo-album1.php', 'photo-album.php', 'album.php' , 'video-album.php' , 'index.php' ) ) );
$data['notContainVideoOnly'] = ( in_array(tt_global_get('page'), array( 'video.php', 'video1.php' , 'video-album.php' ) ) );
$data['notContainMapIndex'] = (!in_array(tt_global_get('page'), array( 'm_map.php', 'map.php' , 'planner.php', 'planner2.php' , 'index.php' )));

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
if(isset($current_id)) $data['currentid'] = $current_id; else $data['currentid'] = 0;

if( !isset($entity_type) ) $entity_type='';
$data['entitytype'] = $entity_type;
if( !isset($is_owner) ) $is_owner=0;
$data['isowner'] = $is_owner;
$data['thumbpath'] = '';
if( isset($VideoInfo['channelid']) ) $data['channelGlobId'] = $VideoInfo['channelid']; else $data['channelGlobId'] ='';
//$data['isOnline'] = (strstr($_SERVER['SERVER_NAME'],'touristtube.com')!=null);
$data['isOnline'] = (strstr($request->server->get('SERVER_NAME', ''),'touristtube.com')!=null);


if (!isset($section))
    $section = '';
$data['sectionCond'] = ( in_array(tt_global_get('page'), array('upload.php', 'myprofile.php', 'profile.php', 'upload-list.php', 'upload-album.php', 'channel-upload.php', 'channel-upload-list.php', 'channel-upload-album.php')));


$category_id = UriArgIsset('cat_id') ? UriGetArg('cat_id') : tt_global_get('category_id');
$selectedCategory = UriArgIsset('cat_id') ? get_cat_name(UriGetArg('cat_id')) : tt_global_get('category_name');

//$t = ( isset($_GET['t'] ) && $_GET['t']<> '' ) ? $_GET['t'] : 'a';
$t = $request->query->get('t','a');
$id_type = UriGetArg('t');
if ($id_type != ''){  
    $t = $id_type;
}else{
    $id_type = (!isset($id_type) ) ? 'a' : $id_type;
}
$data['MAP_KEY'] = '';        
$pagehttp = getUriPageURLHTTP();
if($pagehttp =='https') $data['MAP_KEY'] = '&key='.MAP_KEY;

$q = $request->query->get('qr','');
$id_qr = UriGetArg('qr');
$id_qr = ( !isset($id_qr) ) ? '' : $id_qr;
if($id_qr!='') $q = $id_qr;
$q = str_replace('"', '', $q);
$q =  urldecode($q);

$c = $request->query->get('c','');
$id_category = UriGetArg('c');
$id_category = ( !isset($id_category) ) ? '' : $id_category;
if($id_category!='') $c = $id_category;

//$order = ( isset($_GET['order'] ) && $_GET['order']<> '' ) ? $_GET['order'] : '';
$order = $request->query->get('order','');
$id_orderby = UriGetArg('orderby');
$id_orderby = ( !isset($id_orderby) ) ? '' : $id_orderby;
if($id_orderby!='') $order = $id_orderby;

if($t == 'a') $searchCategoryBtn = _('All Media');
elseif($t == 'i') $searchCategoryBtn = _('Photos');
elseif($t == 'v') $searchCategoryBtn = _('Videos');

$search = urldecode( UriGetArg('ss') );
if( tt_global_get('page') == 'tubers.php') $search = UriGetArg('search-string');
else if( tt_global_get('page') == 'search-location.php') $search = UriGetArg('search-string');
if( $search == null){
    if( tt_global_get('page') == 'search.php'){
        //if we are on the search page and its null we are searching for "nothing"
    }else{
        $search = _('Search');
    }
}
if($selectedCategory==''){
    $selectedCategory = 'all categories';
}
//head Part
$data['favicon']= ReturnLink('media/images/favicon.ico');
list ($page_title, $page_desc, $page_meta) = seoTextGet();

$data['seoPageTitle'] = $page_title;
$data['seoPageDescription']= $page_desc;
$data['seoPageKeywords']= $page_meta;
$data['userIsLogged']= userIsLogged() ? 1 : 0;
$data['userislogged2'] = $data['userIsLogged'];
$data['uploadMaxFileSize']= intval( ini_get('upload_max_filesize') );
$data['page']= tt_global_get('page');
$data['includePartCss'] = $includePartCss;
$data['includePartJs'] = $includePartJs;
$data['js_local'] = $js_local;
$data['lang_local'] = LanguageGet();
//body Part
$data['searchCategoryBtn'] = $searchCategoryBtn;
if( isset($c) ) $data['c'] = $c; else $data['c'] = '';

$category_names = categoryGetInfo($c);
$data['category_names'] = $category_names <> '' ? $category_names : '';
if( isset($qr) ) $data['q'] = $qr; else $data['q'] = '';
$data['t'] = $t<>''? $t: 'a';
$data['visitedClass'] = 'class="visited"';
$data['order'] = $order;
$data['searchLink'] = ($t=='u' )? ReturnLink('tubers') : '';
$data['userGetName'] = userGetName();
$data['userIsChannel'] = userIsChannel();
$data['category_id'] = $category_id;
$data['myChannelsTxt'] = _('my channels');
$data['chat_server'] = $CONFIG ['chat_server'];
$this_page = _seoExecutingFile();
$data['pageFileName'] =$this_page;
$data['channels_page_link'] =ReturnLink('channels', null , 0, 'channels');
$data['login_page_link'] =ReturnLink('login');

//Channels list part
$options2 = array('owner_id' => userGetID(), 'published' => 1, 'page' => 0, 'limit' => null);
$channelListAll = channelSearch($options2);
if( !$channelListAll ) $channelListAll =array();
$data['channelListAllcnt'] = count($channelListAll);
$data['channelListAll'] = tt_number_format(count($channelListAll));
$data['channelDisabled'] = '';
if (count($channelListAll) <= 12) {
    $data['channelDisabled'] = ' disabled';
}
$m = 0;
$channelAll = array();
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
    $channelAll[] = $aChannel;
    $m++;
}
$data['channelAll'] = $channelAll;
require_once $CONFIG ['server']['root'].'twig_parts/slotMachine.php';
$data['tmzone'] = checkTimeZoneCookie(); 