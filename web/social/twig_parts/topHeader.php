<?php

$loggedUser = userGetID();
$currentpage = UriCurrentPageURL();

//$data['login_div'] = include('parts/login.php'); 
if(!userIsLogged()){
    $data['userIsLogged'] = 0;
}else{
    $data['userIsLogged'] = 1;
    $data['userProfile'] = ReturnLink('myprofile');
    $data['userName'] = userGetName();
    $data['userNameWelcome'] = _('Welcome').' - '.$data['userName'];
}
$userIschannel=userIsChannel();
$data['userIsChannel'] =$userIschannel;
$options2 = array('owner_id' => $loggedUser, 'published' => 1, 'page' => 0, 'limit' => null);
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

$includePartCss = '';
$includePartJs = '';
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
//$ur_array = UriCurrentPageURLForLanguage();
//$langarray = array('en'=>'www','fr'=>'fr', 'hi'=>'in');
//$langarraystr = array('English', 'Français', 'हिन्दी');
$ur_arraydata='';
$lang_arraydata='';
$i=0;
//foreach($langarray as $lang_key => $lang_val){
//    if ($lang_key != 'en')
//            $langUrl      = $ur_array[0].$ur_array[1].'/'.$lang_val.'/'.$ur_array[2];
//    else $langUrl      = $ur_array[0].$ur_array[1].'/'.$ur_array[2];
//    $ur_arraydata .='<link rel="alternate" hreflang="'.$lang_key.'" href="'.$langUrl.'" itemprop="url"/>';
//    $lang_arraydata .='<option class="languageOption" value="'.$langUrl.'">'.$langarraystr[$i].'</option>';
//    $i++;
//}
$data['ur_arraydata'] =$ur_arraydata;
$data['lang_arraydata'] =$lang_arraydata;
list ($page_title, $page_desc, $page_meta) = seoTextGet();

$data['seoPageTitle'] = $page_title;
$data['seoPageDescription']= $page_desc;
$data['seoPageKeywords']= $page_meta;

$data['includePartCss'] = $includePartCss;
$data['includePartJs'] = $includePartJs;
$data['upload_link'] = ReturnLink('upload');

$data['logo_link'] = ReturnLink('/');
$data['register_link'] = ReturnLink('register');
$data['bag_link'] = ReturnLink('bag');
$data['things_to_do_link'] = ReturnLink('things-to-do');
$data['discover_link'] = ReturnLink('discover');
$data['channels_link'] = ReturnLink('channels', null , 0, 'channels');
$data['review_link'] = ReturnLink('review');