<?php
$data['SlotMachine_mapLink'] = ReturnLink('discover');
$data['SlotMachine_discoverTxt'] = _('Tourist Discover');
$data['SlotMachine_discoverImg'] = ( in_array( tt_global_get('page'),array('map.php','discover.php') ) ) ? ReturnLink('media/images/menu2/tourist_discover_selected.png'): ReturnLink('media/images/menu2/tourist_discover.png');
$data['SlotMachine_discoverTxt2'] = sprintf(_('Tourist%sDiscover'),"<br/>") ;
$data['SlotMachine_discoverTxt3'] = _('Discover');
$data['SlotMachine_plannerLink'] = ReturnLink('planner');
$data['SlotMachine_plannerTxt'] = _('Tourist Planner');
$data['SlotMachine_advisorImg'] = ( in_array( tt_global_get('page'),array('planner.php') ) ) ? ReturnLink('media/images/menu2/tourist_advisor_selected.png'): ReturnLink('media/images/menu2/tourist_advisor.png');
$data['SlotMachine_plannerTxt2'] = sprintf(_('Tourist%sPlanner'),"<br/>") ;

$data['SlotMachine_searchHotelLink'] = ReturnLink('thotels');
$data['SlotMachine_touristHotelsImg'] = ( in_array( tt_global_get('page'),array('search-location.php','hotel-search.php','hotel.php','thotel.php','thotels.php') ) ) ? ReturnLink('media/images/menu2/tourist_hotels_selected.png'): ReturnLink('media/images/menu2/tourist_hotels.png');
$data['SlotMachine_hotelsTxt'] = _('Tourist Hotels');
$data['SlotMachine_hotelsTxt2'] = sprintf(_('Tourist%sHotels'),"<br/>") ;
$data['SlotMachine_channelsLink'] = ReturnLink('channels', null , 0, 'channels');
$data['SlotMachine_channelsTxt'] = _('Tourist Channels');
$data['SlotMachine_channel2Img'] = ReturnLink('media/images/menu2/channel2.png');
$data['SlotMachine_channelsTxt2'] = sprintf( _('Tourist%sChannels') ,"<br/>") ;
$data['SlotMachine_ThingstodoTxt'] = _('Things To Do');
$data['SlotMachine_ThingstodoLink'] = ReturnLink('things-to-do');

$data['SlotMachine_reviewLink'] = ReturnLink('review');
$data['SlotMachine_reviewTxt'] = _('Review & Rate');
if( userIsLogged() ){
//    $data['SlotMachine_rinviteLink'] = ReturnLink('account/invite');
//    $data['SlotMachine_rinviteTxt'] = _('Send Invitation');
}else{
    $data['SlotMachine_rinviteLink'] = ReturnLink('register');
    $data['SlotMachine_rinviteTxt'] = _('register');
}

$data['SlotMachine_datingLink'] = ReturnLink('dating');
$data['SlotMachine_datingImg'] = ( in_array( tt_global_get('page'),array('dating.php') ) ) ? ReturnLink('media/images/menu2/tourist_dating_selected.png'): ReturnLink('media/images/menu2/tourist_dating.png');
$data['SlotMachine_datingTxt'] = _('Tourist Dating');
$data['SlotMachine_datingTxt2'] = sprintf(_('Tourist%sDating'),"<br/>") ;
$data['SlotMachine_liveLink'] = ReturnLink('live');
$data['SlotMachine_liveTxt'] = _('Tourist Live');
$data['SlotMachine_liveImg'] = ( in_array( tt_global_get('page'),array('live.php','live-cam.php') ) ) ? ReturnLink('media/images/menu2/tourist_live_selected.png'): ReturnLink('media/images/menu2/tourist_live.png');
$data['SlotMachine_liveTxt3'] = _('Live Cams');
$data['SlotMachine_liveTxt2'] = sprintf(_('Tourist%sLive'),"<br/>") ;
$data['SlotMachine_proLink'] = ReturnLink('pro');
$data['SlotMachine_proImg'] = ReturnLink('media/images/menu2/tourist_pro.png');
$data['SlotMachine_proTxt'] = _('Tourist Pro');
$data['SlotMachine_proTxt2'] = _('Tourist<br/>Pro');
$data['SlotMachine_echoImg'] = ( in_array( tt_global_get('page'),array('flash.php') ) ) ? ReturnLink('media/images/menu2/tourist_echoes_selected.png'): ReturnLink('media/images/menu2/tourist_echoes.png');
$data['SlotMachine_cond10'] = ( in_array(tt_global_get('page'), array('channels.php')) ) ? 'selected':'';
$data['SlotMachine_cond11'] = ( in_array(tt_global_get('page'), array('things-to-do.php', 'things-to-do-search.php', 'top-things-to-do.php')) ) ? 'selected':'';