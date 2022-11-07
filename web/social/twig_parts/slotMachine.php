<?php
$data['SlotMachine_visited'] = 1;
$data['SlotMachine_mapLink'] = ReturnLink('discover');
$data['SlotMachine_discoverTxt'] = _('Tourist Discover');
$data['SlotMachine_discoverImg'] = ( in_array( tt_global_get('page'),array('map.php','discover.php') ) ) ? ReturnLink('media/images/menu2/tourist_discover_selected.png'): ReturnLink('media/images/menu2/tourist_discover.png');
$data['SlotMachine_discoverTxt2'] = sprintf( _('Tourist%sDiscover') , '<br />' ) ;
$data['SlotMachine_discoverTxt3'] = _('Discover');
$data['SlotMachine_plannerLink'] = ReturnLink('planner');
$data['SlotMachine_plannerTxt'] = _('Tourist Planner') ;

$data['SlotMachine_advisorImg'] = ( in_array( tt_global_get('page'),array('planner.php') ) ) ? ReturnLink('media/images/menu2/tourist_advisor_selected.png'): ReturnLink('media/images/menu2/tourist_advisor.png');
$data['SlotMachine_plannerTxt2'] = sprintf( _('Tourist%sPlanner') , '<br />');

$data['SlotMachine_searchHotelLink'] = ReturnLink('thotels');
$data['SlotMachine_touristHotelsImg'] = ( in_array( tt_global_get('page'),array('search-location.php','hotel-search.php','hotel.php','thotel.php','thotels.php') ) ) ? ReturnLink('media/images/menu2/tourist_hotels_selected.png'): ReturnLink('media/images/menu2/tourist_hotels.png');
$data['SlotMachine_hotelsTxt'] = _('Tourist Hotels');
$data['SlotMachine_hotelsTxt2'] = sprintf( _('Tourist%sHotels'),'<br />');
$data['SlotMachine_channelsLink'] = ReturnLink('channels', null , 0, 'channels');
$data['SlotMachine_channelsTxt'] = _('Tourist Channels');
$data['SlotMachine_channel2Img'] = ReturnLink('media/images/menu2/channel2.png');
$data['SlotMachine_channelsTxt2'] = sprintf( _('Tourist%sChannels') , '<br />' );
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
$data['SlotMachine_datingTxt2'] = sprintf(_('Tourist%sDating'),'<br />');
$data['SlotMachine_liveLink'] = ReturnLink('live');
$data['SlotMachine_liveTxt'] = _('Tourist Live');
$data['SlotMachine_liveImg'] = ( in_array( tt_global_get('page'),array('live.php','live-cam.php') ) ) ? ReturnLink('media/images/menu2/tourist_live_selected.png'): ReturnLink('media/images/menu2/tourist_live.png');
$data['SlotMachine_liveTxt3'] = _('Live Cams');
$data['SlotMachine_liveTxt2'] = sprintf( _('Tourist%sLive') , '<br />' );
$data['SlotMachine_proLink'] = ReturnLink('pro');
$data['SlotMachine_proImg'] = ReturnLink('media/images/menu2/tourist_pro.png');
$data['SlotMachine_proTxt'] = _('Tourist Pro');
$data['SlotMachine_proTxt2'] = _('Tourist<br/>Pro');
$data['SlotMachine_echoImg'] = ( in_array( tt_global_get('page'),array('flash.php') ) ) ? ReturnLink('media/images/menu2/tourist_echoes_selected.png'): ReturnLink('media/images/menu2/tourist_echoes.png');
$data['SlotMachine_top1Img'] = ReturnLink('media/images/topslider/top1.png');
$data['SlotMachine_joinDesc'] = _('Hello!<br/>Welcome to <span>Tourist Tube...</span><br/>The online tourism sharing site.<br/>JOIN OUR COMMUNITY<br/>');
$data['SlotMachine_btnTop1Img'] = ReturnLink('media/images/topslider/btn_top1.png');
$data['SlotMachine_registerTxt'] = _('register');
$data['SlotMachine_top2Img'] = ReturnLink('media/images/topslider/top2.png');
$data['SlotMachine_btnTop2Img'] = ReturnLink('media/images/'.LanguageGet().'/topslider/btn_top2.png');
$data['SlotMachine_uploadDesc'] = _('<span>Upload Everywhere</span><br/>Share the fun <br>with your loved ones.');
$data['SlotMachine_top3Img'] = ReturnLink('media/images/'.LanguageGet().'/topslider/top3.png');
$data['SlotMachine_plannerDesc'] = _('<span>Love Traveling?</span><br/>Find the top places to visit <br/>Get the experts\' opinion.');
$data['SlotMachine_uploadLink'] = ReturnLink('upload');
$data['SlotMachine_uploadTxt'] = _('upload');
$data['SlotMachine_top6Img'] = ReturnLink('media/images/topslider/top6.png');
$data['SlotMachine_dateDesc'] = _('<span>Looking for a date?</span><br/>Find your significant other<br> and prepare to meet with them.');
$data['SlotMachine_registerLink'] = ReturnLink('register');
$data['SlotMachine_top4Img'] = ReturnLink('media/images/topslider/top4.png');
$data['SlotMachine_commentDesc'] = _('<span>Stay in Touch</span><br/>See the news and what they <br>have to say about it.<br> Add up with your comments.');
$data['SlotMachine_top5Img'] = ReturnLink('media/images/topslider/top5.png');
$data['SlotMachine_mobileDesc'] = _('<span>Connect with your Mobile</span><br/>Download our mobile application <br>and get the full package!');
$data['SlotMachine_cond1'] = ( in_array( tt_global_get('page'),array('map.php') ) ) ? 'selected':'';
$data['SlotMachine_cond2'] = ( in_array( tt_global_get('page'),array('planner.php') ) ) ? 'selected':'';
$data['SlotMachine_cond3'] = ( in_array( tt_global_get('page'),array('search-location.php','hotel-search.php','hotel.php','thotel.php','thotels.php') ) ) ? 'selected':'';
$data['SlotMachine_cond5'] = ( in_array( tt_global_get('page'),array('live.php','live-cam.php') ) ) ? 'selected':'';
$data['SlotMachine_cond6'] = ( in_array( tt_global_get('page'),array('live.php','live-cam.php') ) ) ? 'selected':'';
$data['SlotMachine_cond7'] = ( in_array( tt_global_get('page'),array('flash.php') ) ) ? 'selected':'';
$data['SlotMachine_cond8'] = ( in_array( tt_global_get('page'),array('review.php','hotel-review.php','restaurant-review.php','things2do-review.php','airport-review.php') ) ) ? 'selected':'';
$data['SlotMachine_cond9'] = ( in_array( tt_global_get('page'),array('register.php','account.php') ) ) ? 'selected':'';
$data['SlotMachine_cond10'] = ( in_array(tt_global_get('page'), array('channels.php')) ) ? 'selected':'';
$data['SlotMachine_cond11'] = ( in_array(tt_global_get('page'), array('things-to-do.php', 'things-to-do-search.php', 'top-things-to-do.php')) ) ? 'selected':'';