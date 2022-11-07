<?php

if( !isset($bootOptions) ){
	$path    = "../";

	$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1 );
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" ); 

	include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/webcams.php" );
	include_once ( $path . "inc/functions/users.php" );	
	
}
setcookie("time_nf", date("Y-m-d H:i:s"),0,'/');
$user_is_logged=1;
$userId =userGetID();
if( !userIsLogged() ){
	$user_is_logged=0;
	ob_clean();
	header('Location: ' . ReturnLink('register') );
	exit;
}

$userIschannel=userIsChannel();
$userIschannel = ($userIschannel) ? header('Location:' . ReturnLink('/') ) : 0;

$userscount=0;
$userscounthide='false';

if( $userIschannel == 0 && $user_is_logged==1){
	$profilePic = ReturnLink('media/tubers/small_'.$userInfo_loggedUser['profile_Pic'] );
	$fullNameStan = htmlEntityDecode($userInfo_loggedUser['FullName']);
        $fullName = returnUserDisplayName($userInfo_loggedUser, array('max_length' => 25));
}else{
	$profilePic = ReturnLink('media/tubers/small_'.$profilePic );
}

$is_owner_visible=1;
if($is_owner ==1){
	$is_owner_visible=-1;
}
$from_date = date('Y') . '-' . (date('n')) . '-1';
$to_date = date('Y-m-t', strtotime($from_date));

$feedlimit = 10;
$options = array ( 
    'limit' => $feedlimit , 
    'page' => 0 , 
    'userid' => $userId , 
    'orderby' => 'id' ,
    'order' => 'd',
    'channel_id' => null , 
    'is_visible' => $is_owner_visible
);
$search_string_post = $request->request->get('search_string', '');
//if( isset($_POST['search_string']) ){
//    $options['search_string'] = $_POST['search_string'];
if( $search_string_post ){
    $options['search_string'] = $search_string_post;
}
$userVideos = newsfeedPageSearch( $options );
$news_feed_count = sizeof($userVideos);
$options = array(
	'limit' => null, 
	'YourBday' => date( 'Y-m-d' ) ,
	'type' => array(1) ,
	'userid' => $userId
);
$friend_birthday_array = userFriendSearch($options);

?>

<script type="text/javascript">
    var no_socials_links = 1;
    var user_is_logged = '<?php echo $user_is_logged; ?>';
    var user_Is_channel = '<?php echo $userIschannel; ?>';
    var is_owner = '<?php echo $is_owner; ?>';
    //$(document).ready(function(){
        userGlobalID('<?php echo $userId; ?>');
    //});
</script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp<?php echo $MAP_KEY; ?>&amp;sensor=false&amp;libraries=places"></script>
<div id="sharepopup"></div>
<div class="upload-overlay-loading-fix"><div></div></div>
<div class="privacybuttonsOver">
    <div class="ProfileHeaderOverin"></div>
    <div class="icons-overtik"></div>
</div>
<div id="left_container_newsfeed">
<?php
    $limit=10;
    $div_val='';	
    if( $news_feed_count<=5){
        $userscounthide='true';
    }
echo '<div class="log_data_container_feed log_data_container" id="log_data_container">';	
	echo '<div class="userscounthide" data-value="'.$userscounthide.'"></div>';	
	
	foreach($friend_birthday_array as $news){
		
		$div_val .= '<div class="log_item_list">
			<div class="line1"></div>
			<div class="line2"></div>
			<div data-id="'.$news['id'].'" class="log_top_arrow"></div>
			<div class="log_top_buttons_container">
				<div data-entity-id="'.$news['id'].'" data-entity-type="birthday" id="log_hidden_buttons_'.$news['id'].'" class="log_hidden_buttons">';
				 if($is_owner==1){						
                                    $div_val .= '<div class="log_top_button" id="hide_all_button_log">'._("hide all from Ttuber").'</div>';
				 }
				$div_val .= '</div>
			</div>';
		$gender = 'his';
		if($news['gender']=='F'){
			$gender = 'her';
		}
		
		$uslnk= userProfileLink($news);
		$prfpic=ReturnLink('media/tubers/' . $news['profile_Pic']);
		$action_text = '%s birthday is today';
                $action_text_display = vsprintf( _($action_text) , array('<a href="' . $uslnk . '" target="_blank"><strong>' . returnUserDisplayName($news) . '</strong></a>') ) ;
		$action_text2 = 'write something to post on '.$gender.' %s';
                $action_text2_display = vsprintf( _($action_text2) , array('<a href="' . $uslnk . '" target="_blank">TT page...</a>') ) ;
		$div_val .= '
			<div class="log_tuber_header">
				<div class="arrow"></div>
				<a href="' . $uslnk . '" target="_blank"><img class="log_tuber_logo" src="' . $prfpic . '" /></a>
				<div class="log_header_text">'.$action_text_display.'</div><br />				
			</div>';		
		$div_val .= '<div class="log_slogan_container">
				<img class="left_text_birth_pic" src="' . ReturnLink("images/img/birth_icon.png"). '" />
				<div class="left_text_birth">'.$action_text2_display.'</div>
			</div>';
		 $div_val .= '</div>';
	} // end for 

	include($path.'parts/newsfeed_loop.php');
	echo $div_val;
	
	echo '</div>';
?>
<div class="log_data_footer">
        <div style="height:26px;">
            <div class="buttonmorecontainer" style="float: none;width: 104px;margin-left: 280px;">
                <a id="log_load_more" class="loadmoreevents load_more_previous_new"><?php echo _('Load More...'); ?></a>
            </div>
        </div>
		<script type="text/ecmascript">
            <?php if($news_feed_count >= 5){ ?>
                    $(".buttonmorecontainer").show();
            <?php } else { ?>            
                    $(".buttonmorecontainer").hide();
            <?php } ?>
        </script>
    </div> <!-- log_data_footer -->
</div>
<div class="log_data_container_right_feed">
    <?php
	
        global $CountryIp;  
        $CountryCode = getCountryFromIP($CountryIp, "code");	
        
        $channel_created_array = getChannelHighestConnectionsByCountry($CountryCode,28);
        
        $options = array(
            'limit' => 28, 
            'page' => 0, 
            'user_id' => $loggedUser ,
            'orderby' => 'rand'
        );
        $suggested_friends_array = suggestedFriendsGet($options);
    ?>
        <?php if(count($suggested_friends_array)>0): ?>
            <div class="buttonlist_info_white_channels_created_feed">
                <div class="suggested_friends_title" style="height: auto; line-height: 16px;"><?php echo _("Ttubers you may know"); ?></div>
                <div class="suggested_friends_list">
                    <?php 
                       foreach ($suggested_friends_array as $tuber) {
							echo '<div class="suggested_friends_item">';
								echo '<a class="" href="'.userProfileLink($tuber).'">';									
                                                                        echo '<img class="preloader IndexTuberThumb left-middle" data-src="'.ReturnLink('media/tubers/thumb_' . $tuber['profile_Pic']).'" src="'.ReturnLink('media/tubers/xsmall_' . $tuber['profile_Pic']).'" data-title="'.returnUserDisplayName($tuber).'" data-alt="'.returnUserDisplayName($tuber).'">';
								echo '</a>';										
								echo '<span style="display:none" class="TuberDesc">'.returnUserDisplayName($tuber).'</span>';											
							echo '</div>';									
						 }
					?>
                </div> <!-- buttonlist_info_white_channel_list -->                
            </div> <!-- buttonlist_info_white_channels_created -->  
        <?php endif; ?> 
        <?php if(count($channel_created_array)>0): ?>
            <div class="buttonlist_info_white_channels_created_feed">          
                
                <div class="buttonlist_info_white_channel_title"><?php echo _("suggested TChannels"); ?></div>
                <div class="buttonlist_info_white_channel_list">
                    <?php 
                        
                        foreach ($channel_created_array as $topchannel) {										
                            $Tch_name = htmlEntityDecode($topchannel['channel_name']);
                            $Tch_url = $topchannel['channel_url'];
                            $Tch_link = channelMainLink($topchannel);
                            $ch_id = $channel['id'];
                            
                            
                            $ch_pop_logo = '<a href="'.$Tch_link.'" title="'.$Tch_name.'" target="_blank"><img src="'.photoReturnchannelLogo($topchannel).'" class="imgborder0" alt="'.$Tch_name.'"></a>';
                            
                            $poUpdata = $ch_pop_logo.'<br />'.$Tch_name;
                            
                            $Tch_logo = '<img class="channel_pic" src="'.photoReturnchannelLogo($topchannel).'" alt="'.$Tch_name.'">';
                            
                            echo '<div class="buttonlist_info_white_channel_item">';
                                echo '<a href="'.$Tch_link.'" title="'.$Tch_name.'" target="_blank">';
                                    echo $Tch_logo;
                                echo '</a>';
                                echo '<div class="channelpopUp popUp">';
                                        echo $poUpdata;												
                                echo '</div>';
                            echo '</div>';
                         }
                    ?>
                </div> <!-- buttonlist_info_white_channel_list -->
                              
                
            </div> <!-- buttonlist_info_white_channels_created -->  
        <?php endif; ?> 
        <div class="buttonlist_info_white_channels_created_feed margintop7">
            <?php
                $channel_most_connections = getChannelHighestConnections();
                $channel_most_connections = $channel_most_connections[0];
                $TubersConnected = channelConnectedTubersSearch(array('limit' =>12,'page' =>0,'orderby'=>'rand','is_visible'=>1,  'channelid' => $channel_most_connections['id']));

                $TubersNumConnected = channelConnectedTubersSearch(array('channelid' => $channel_most_connections['id'],'is_visible'=>1,'n_results' => true ));

                $Tch_pic_src = ($channel_most_connections['header']) ? photoReturnchannelHeader($channel_most_connections) : ReturnLink('/media/images/channel/coverphoto.jpg');
                $Tch_name = htmlEntityDecode($channel_most_connections['channel_name']);
                $Tch_link = channelMainLink($channel_most_connections);
				//debug($TubersNumConnected_count);
				//debug($TubersConnected);
			?>  
            <?php if(count($TubersNumConnected)>0): ?>
            <div class="news_people_know_head">
            	<div class="news_people_know_head_title"><?php echo _("popular channels"); ?></div>
                <div class="news_people_know_buttons">
                    <!--<div class="news_people_know_buttons_item">
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_bk1"></div>
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_over"></div>
                    </div>
                    <div class="news_people_know_buttons_seperator"></div>
                    <div class="news_people_know_buttons_item">
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_bk2"></div>
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_over"></div>
                    </div>
                    <div class="news_people_know_buttons_seperator"></div>
                    <div class="news_people_know_buttons_item">
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_bk3"></div>
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_over"></div>
                    </div>
                    <div class="news_people_know_buttons_seperator"></div>-->
                    <div class="news_people_know_buttons_item active">
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_bk4"></div>
                    	<div class="news_people_know_buttons_item_bk news_people_know_buttons_item_over"></div>
                    </div>
                </div>
            </div>
                <div class="news_people_know_content">
                    <div class="news_people_know_content_tuber">
                        <?php 
                           foreach ($TubersConnected as $tuber) {
                                echo '<div class="suggested_friends_item">';
                                    echo '<a class="" href="'.userProfileLink($tuber).'">';
                                        echo '<img class="preloader IndexTuberThumb left-middle" data-src="'.ReturnLink('media/tubers/thumb_' . $tuber['profile_Pic']).'" src="'.ReturnLink('media/tubers/xsmall_' . $tuber['profile_Pic']).'" data-title="'.returnUserDisplayName($tuber).'" data-alt="'.returnUserDisplayName($tuber).'">';
                                    echo '</a>';										
                                    echo '<span style="display:none" class="TuberDesc">'.returnUserDisplayName($tuber).'</span>';											
                                echo '</div>';									
                             }
                        ?>
                    </div> <!-- news_people_know_content_tuber -->
                    <a class="news_people_know_content_picholder" href="<?php echo $Tch_link; ?>">
                        <img class="news_people_know_content_pic" src="<?php echo $Tch_pic_src; ?>" alt="<?php echo $Tch_name; ?>">
                    </a> <!-- news_people_know_content_picholder -->
                    <a class="news_people_know_content_name social_link_a" href="<?php echo $Tch_link; ?>"><div class="news_people_know_content_namedata"><?php echo $Tch_name; ?></div></a>
                    <div class="news_people_know_content_count">
                    	<div class="news_people_know_content_logo"></div>
                    	<div class="news_people_know_content_text"><?php echo  sprintf( ngettext('%d connection', '%d connections' , $TubersNumConnected ), $TubersNumConnected);?></div>
                    </div>                     
                </div> <!-- buttonlist_info_white_channels_created -->  
            <?php endif; ?>
        </div> <!-- buttonlist_info_white_channels_created -->
    </div>