<?php
$discover_lists = getDiscoverToChannelList($channelInfo['id']);
$discover_listshotel=array();
$discover_listsresto=array();
$discover_listspoi=array();
$discover_listsairport=array();
foreach($discover_lists as $discover_item){
    $locationText = '';
    if($discover_item['entity_type']==SOCIAL_ENTITY_HOTEL){
        $discover_data = getHotelInfo($discover_item['entity_id']);
        $title = htmlEntityDecode($discover_data['hotelName']);
        if ($discover_data['city_id'] != '0') {
            $city_array = worldcitiespopInfo(intval($discover_data['city_id']));
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
            $locationText .= '<br/>'.$discover_data['address'];
        } else {
            $locationText = $discover_data['location'];
        }
        if($locationText=='') $locationText = $discover_data['address'];
        $nb_votes = $discover_data['nb_votes'];
        if($discover_data['rating']>0){
            $irating_average = ceil(($discover_data['rating']/2)*10)/10;
        }else{
            $irating_average = socialRateAverage($discover_data['id'], array(SOCIAL_ENTITY_HOTEL));
        }
        $linkpoi = returnHotelReviewLink($discover_data['id'],$title);
        $discover_item['link'] = $linkpoi;
        $rate_text='';
        if($irating_average>0){
            $rate_text .= _('RATING').': '.$irating_average;
            if($nb_votes>0){
                $rate_text .= ' - '.$nb_votes.' '._('VOTES');
            }
        }
        $discover_item['irating_average'] = $rate_text;
        $discover_item['title'] = $title;
        $discover_item['location'] = $locationText;
        $discover_listshotel[] = $discover_item;
    }else if($discover_item['entity_type']==SOCIAL_ENTITY_RESTAURANT){
        continue;
        $discover_data = getRestaurantInfo($discover_item['entity_id']);
        $title = htmlEntityDecode($discover_data['name']);
        if ( intval($discover_data['city_id']) >0) {
            $city_array = worldcitiespopInfo(intval($discover_data['city_id']));
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
            $city_name = $discover_data['locality'];
            if ($city_name != '') {
                $locationText .= $city_name;
            }
            $region_name = $discover_data['region'];
            $admin_region_name = $discover_data['admin_region'];
            if ($region_name != '' && $region_name !=$city_name ) {
                $locationText .= ', ' . $region_name;
            }else if ($admin_region_name != '' && $admin_region_name !=$city_name) {
                $locationText .= ', ' . $admin_region_name;
            }
            $country_cd = $discover_data['country'];
            if ($country_cd != '') {
                $country_array = countryGetInfo($country_cd);
                $country_name = $country_array['name'];
                if ($country_name != '') {
                    $locationText .= ', ' . $country_name;
                }
            }
        }
        if ($locationText == '') {
            $locationText = $discover_data['address'];
        }else if ($discover_data['address'] != '') {
            $locationText .= '<br>'.$discover_data['address'];
        }
        $nb_votes = $discover_data['nb_votes'];
        if($discover_data['avg_rating']>0){
            $irating_average = $discover_data['avg_rating'];
        }else{
            $irating_average = socialRateAverage($discover_data['id'], array(SOCIAL_ENTITY_RESTAURANT));
        }
        $linkpoi = returnRestaurantReviewLink($discover_data['id'],$title);
        $discover_item['link'] = $linkpoi;
        $rate_text='';
        if($irating_average>0){
            $rate_text .= _('RATING').': '.$irating_average;
            if($nb_votes>0){
                $rate_text .= ' - '.$nb_votes.' '._('VOTES');
            }
        }
        $discover_item['irating_average'] = $rate_text;
        $discover_item['title'] = $title;
        $discover_item['location'] = $locationText;
        $discover_listsresto[] = $discover_item;
    }else if($discover_item['entity_type']==SOCIAL_ENTITY_LANDMARK){
        $discover_data = getPoiInfo($discover_item['entity_id']);
        $title = htmlEntityDecode($discover_data['name']);
        if ($discover_data['city_id'] != '0') {
            $city_array = worldcitiespopInfo(intval($discover_data['city_id']));
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
            $locationText = $discover_data['address'];
        }
        if ($locationText == '') {
            $locationText = $discover_data['address'];
        }else if ($discover_data['address'] != '') {
            $locationText .= '<br>'.$discover_data['address'];
        }
        $nb_votes = 0;
        $irating_average = socialRateAverage($discover_data['id'], array(SOCIAL_ENTITY_LANDMARK));
        $linkpoi = returnThingstodoReviewLink($discover_data['id'],$title);
        $discover_item['link'] = $linkpoi;
        $rate_text='';
        if($irating_average>0){
            $rate_text .= _('RATING').': '.$irating_average;
            if($nb_votes>0){
                $rate_text .= ' - '.$nb_votes.' '._('VOTES');
            }
        }
        $discover_item['irating_average'] = $rate_text;
        $discover_item['title'] = $title;
        $discover_item['location'] = $locationText;
        $discover_listspoi[] = $discover_item;
    }else if($discover_item['entity_type']==SOCIAL_ENTITY_AIRPORT){
        $discover_data = getAirportInfo($discover_item['entity_id']);
        $title = htmlEntityDecode($discover_data['name']);
        if ($discover_data['city_id'] != '0') {
            $city_array = worldcitiespopInfo(intval($discover_data['city_id']));
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
            $locationText = $discover_data['city'];
        }
        if ($locationText == '') {
            $locationText = $discover_data['city'];
        }
        $nb_votes = 0;
        $irating_average = socialRateAverage($discover_data['id'], array(SOCIAL_ENTITY_AIRPORT));
        $linkpoi = returnAirportReviewLink($discover_data['id'],$title);
        $discover_item['link'] = $linkpoi;
        $rate_text='';
        if($irating_average>0){
            $rate_text .= _('RATING').': '.$irating_average;
            if($nb_votes>0){
                $rate_text .= ' - '.$nb_votes.' '._('VOTES');
            }
        }
        $discover_item['irating_average'] = $rate_text;
        $discover_item['title'] = $title;
        $discover_item['location'] = $locationText;
        $discover_listsairport[] = $discover_item;
    }
}
$data['isOwner'] = $is_owner;
$data['discover_listscount'] = sizeof($discover_lists);
$data['discover_listshotel'] = $discover_listshotel;
$data['discover_listsresto'] = $discover_listsresto;
$data['discover_listspoi'] = $discover_listspoi;
$data['discover_listsairport'] = $discover_listsairport;
$data['channel_type'] = $channel_type;
$data['CHANNEL_TYPE_DIASPORA'] = CHANNEL_TYPE_DIASPORA;
if ($channel_type != CHANNEL_TYPE_DIASPORA) {
    $data['hidecreatedon'] = $channelInfo['hidecreatedon'];
    if ($channelInfo['hidecreatedon'] != 1) {
        $create_ts = returnSocialTimeFormat( $channelInfo['create_ts'] ,3);
        $data['createTsDate'] = $create_ts;
    }
    $data['hidecreatedby'] = $channelInfo['hidecreatedby'];
    if ($channelInfo['hidecreatedby'] != 1) {
        $data['userChannelFullName'] = returnUserDisplayName($userChannelInfo);
    }

    $catarr = allchannelGetCategory($channelInfo['category']);

    $data['firstCareerTitle'] = htmlEntityDecode($catarr[0]['title']);
    $data['category_link'] = ReturnLink('channels-category/'.seoEncodeURL($data['firstCareerTitle']));
    $data['hidelocation'] = $channelInfo['hidelocation'];
    if ($channelInfo['hidelocation'] != 1) {
        $data['channelOwnerLocationSmall'] = channelOwnerLocationSmall($channelInfo);
    }
    $data['default_link'] = '';
    if ($channelInfo['default_link'] != '') {
        $data['default_text'] = $channelInfo['default_link'];
        $data['default_link'] = returnExternalLink($channelInfo['default_link']);
    }
    
    $channel_desc = str_replace("\n", "<br/>", $channel_desc);
    $data['channel_desc_Right'] = $channel_desc;
    $data['privacy_activechannels'] = intval($channelPrivacyArray['privacy_activechannels']);
    $data['privacy_activetubers'] = intval($channelPrivacyArray['privacy_activetubers']);
    if (intval($channelPrivacyArray['privacy_activechannels']) == 1) {
        include("parts/mostActiveChannels1.php");
    }
    if (intval($channelPrivacyArray['privacy_activetubers']) == 1) {
        include("parts/mostActiveTubers1.php");
    }
} else {
    include("parts/channel_diaspora_ticker1.php");

    if ($user_is_logged):
        if ($channel_type == CHANNEL_TYPE_DIASPORA) {
            $options3 = array('status' => 3, 'order' => 'd', 'orderby' => 'todate', 'owner_id' => $userid);
            $upcommingCalendarEventArray = channeleventSearch($options3);
            $upcommingCalendarEventArray = ($upcommingCalendarEventArray) ? $upcommingCalendarEventArray : array();
            $sponsoringCalendarArray = socialSharesGet(array(
                'orderby' => 'share_ts',
                'order' => 'd',
                'unique' => 1,
                'is_visible' => $is_owner_visible,
                'owner_id' => $userid,
                'entity_type' => SOCIAL_ENTITY_EVENTS,
                'share_type' => SOCIAL_SHARE_TYPE_SPONSOR
            ));
            $sponsoringCalendarArray = ($sponsoringCalendarArray) ? $sponsoringCalendarArray : array();
        }

        $data['channel_url'] = $channelInfo['channel_url'];

        $i = 0;
        $len = sizeof($upcommingCalendarEventArray);
        $str = "";
        $upcomEvntArr = array();
        foreach ($upcommingCalendarEventArray as $channelUpcommingCalendarEvent) {
            $anUpcomEvntArr = array();
            $i++;

            $id = $channelUpcommingCalendarEvent['id'];
            $photo = ($channelUpcommingCalendarEvent['photo']) ? photoReturneventImage($channelUpcommingCalendarEvent) : ReturnLink('media/images/channel/eventthemephoto.jpg');
            $from_date = date('Y-m-d', strtotime($channelUpcommingCalendarEvent['fromdate']));
            $Title = htmlEntityDecode($channelUpcommingCalendarEvent['name']);
            if (strlen($Title) > 33)
                $Title = substr($Title, 0, 32) . '...';
    $Title = addslashes($Title);
            $from_date_arr = explode('-', $from_date);
            $from_date_display = $from_date_arr[0] . '' . $from_date_arr[1] . '' . $from_date_arr[2];
            $str .=$from_date_display . '[*]highlight[*]' . $photo . '[*]' . $Title . '[*]' . $id . '[*]1';

            $anUpcomEvntArr['from_date'] = $from_date_display;
            $anUpcomEvntArr['comma'] = '';
            if ($i < $len) {
                $anUpcomEvntArr['comma'] = ',';
                $str .="/*/";
            }
            $upcomEvntArr[] = $anUpcomEvntArr;
        }

        $data['upcomEvntArr'] = $upcomEvntArr;
        $i = 0;
        $len1 = sizeof($sponsoringCalendarArray);
        $sponsorCalArr = array();
        foreach ($sponsoringCalendarArray as $channelSponsoringCalendar) {
            $aSponsorCalArr = array();
            $aSponsorCalArr['comma2'] = '';
            if ($len > 0 && $i == 0) {
                $aSponsorCalArr['comma2'] = ',';
                $str .="/*/";
            }
            $i++;
            $channel_id_cal = $channelSponsoringCalendar['channel_id'];
            $entity_id = $channelSponsoringCalendar['entity_id'];
            $eventArray_init = unitGetChannelEvent($channel_id_cal, $entity_id);
            $eventArray = $eventArray_init[0];
            $id = $eventArray['id'];

            $photo = ($eventArray['photo']) ? photoReturneventImage($eventArray) : ReturnLink('media/images/channel/eventthemephoto.jpg');
            $from_date = date('Y-m-d', strtotime($eventArray['fromdate']));
            $Title = htmlEntityDecode($eventArray['name']);
            if (strlen($Title) > 33) {
                $Title = substr($Title, 0, 32) . '...';
            }
    $Title = addslashes($Title);
            $from_date_arr = explode('-', $from_date);
            $from_date_display = $from_date_arr[0] . '' . $from_date_arr[1] . '' . $from_date_arr[2];
            $str .=$from_date_display . '[*]highlight2[*]' . $photo . '[*]' . $Title . '[*]' . $id . '[*]2';
            $aSponsorCalArr['from_date'] = $from_date_display;
            $aSponsorCalArr['comma'] = '';
            if ($i < $len1) {
                $aSponsorCalArr['comma'] = ',';
                $str .="/*/";
            }

            $sponsorCalArr[] = $aSponsorCalArr;
        }
        $data['sponsorCalArr'] = $sponsorCalArr;
        $data['str2'] = $str;
    endif;
    $AllChannels = socialSharesGet(array(
        'orderby' => 'share_ts',
        'order' => 'd',
        'limit' => 100,
        'page' => 0,
        'from_user' => $channelInfo['id'],
        'is_visible' => $is_visible,
        'entity_type' => SOCIAL_ENTITY_CHANNEL,
        'share_type' => SOCIAL_SHARE_TYPE_SPONSOR
    ));
    $data['AllChannelsNotFalse'] = ($AllChannels != FALSE);
    if ($AllChannels) {
        $AllChannelsArr = array();
        foreach ($AllChannels as $channel) {
            $aAllChannelsArr = array();
            $ch_id = $channel['id'];
            $ch_name = htmlEntityDecode($channel['channel_name']);
            $aAllChannelsArr['ch_name'] = $ch_name;

            if ($channel['logo']) {
                $lo_logo = ReturnLink('media/channel/' . $channel['id'] . '/thumb/' . $channel['logo']);
                $ch_logo = '<a href="' . channelMainLink($channel) . '" title="' . $ch_name . '"><img src="' . photoReturnchannelLogo($channel) . '" alt="' . $ch_name . '"></a>';
            } else {
                $lo_logo = ReturnLink('/media/tubers/thumb_tuber.jpg');
                $ch_logo = '<a href="' . channelMainLink($channel) . '" title="' . $ch_name . '"><img width="28" height="28" src="' . ReturnLink('/media/tubers/xsmall_tuber.jpg') . '" alt="' . $ch_name . '"></a>';
            }
            $aAllChannelsArr['lo_logo'] = $lo_logo;
            $aAllChannelsArr['ch_logo'] = $ch_logo;
            $aAllChannelsArr['mailLink'] = channelMainLink($channel);
            $aAllChannelsArr['name'] = $ch_name;
            $AllChannelsArr[] = $aAllChannelsArr;
        }
        $data['AllChannelsArr'] = $AllChannelsArr;
    }
?>
    <?php

    $AllTubers = channelConnectedTubersSearch(array('limit' => 21, 'page' => 0, 'is_visible' => $is_visible, 'order' => 'a', 'channelid' => $channelInfo['id']));
    $data['AllTubersNotFalse'] = ($AllTubers != FALSE);
    if ($AllTubers) {
        $AllTubersArr = array();
        foreach ($AllTubers as $tuber) {
            $aAllTubersArr = array();
            $ch_id = $tuber['id'];
            $ch_name = returnUserDisplayName($tuber);
            $uslnk = userProfileLink($tuber);

            $aAllTubersArr['uslnk'] = $uslnk;
            $aAllTubersArr['displayName'] = returnUserDisplayName($tuber);
            $aAllTubersArr['ppic'] = $tuber['profile_Pic'];
            $aAllTubersArr['name'] = $ch_name;
            if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null) {
                continue;
            }
            $AllTubersArr[] = $aAllTubersArr;
        }
        $data['AllTubersArr'] = $AllTubersArr;
    }
    ?>
    <?php

    $AllTubers = channelConnectedTubersSearch(array('limit' => 21, 'page' => 1, 'is_visible' => $is_visible, 'order' => 'a', 'channelid' => $channelInfo['id']));
    $data['AllTubers1NotFalse'] = ($AllTubers != FALSE);
    if ($AllTubers) {

        $AllTubersArr1 = array();
        foreach ($AllTubers as $tuber) {
            $aAllTubersArr1 = array();
            $ch_id = $tuber['id'];
            $ch_name = returnUserDisplayName($tuber);
            $uslnk = userProfileLink($tuber);

            $aAllTubersArr1['uslnk'] = $uslnk;
            $aAllTubersArr1['displayName'] = returnUserDisplayName($tuber);
            $aAllTubersArr1['ppic'] = $tuber['profile_Pic'];
            $aAllTubersArr1['name'] = $ch_name;

            if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
                continue;
            $AllTubersArr1[] = $aAllTubersArr1;
        }
        $data['AllTubersArr1'] = $AllTubersArr1;
    }
    ?>
    <?php

    $AllTubers = channelConnectedTubersSearch(array('limit' => 21, 'page' => 2, 'is_visible' => $is_visible, 'order' => 'a', 'channelid' => $channelInfo['id']));
    $data['AllTubers2NotFalse'] = ($AllTubers != FALSE);
    if ($AllTubers) {

        $AllTubersArr2 = array();
        foreach ($AllTubers as $tuber) {
            $aAllTubersArr2 = array();
            $ch_id = $tuber['id'];
            $ch_name = returnUserDisplayName($tuber);
            $uslnk = userProfileLink($tuber);

            $aAllTubersArr2['uslnk'] = $uslnk;
            $aAllTubersArr2['displayName'] = returnUserDisplayName($tuber);
            $aAllTubersArr2['ppic'] = $tuber['profile_Pic'];
            $aAllTubersArr2['name'] = $ch_name;


            if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
                continue;
            $AllTubersArr2[] = $aAllTubersArr2;
        }
        $data['AllTubersArr2'] = $AllTubersArr2;
    }
} // end else diaspora