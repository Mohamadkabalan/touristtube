<div id="ChannelRight" class="marginright10">
    <?php if ($is_home == 1) { ?>        
        <div class="share_channel_button"><span class="share_cnt"><img width="20" height="20" src="<?php echo RetirnLink('media/images/share_link_button.png'); ?>" alt="<?php echo _('share button'); ?>" class="share_linkbkicon"/><span class="share_lnktext"><?php echo _('share page'); ?></span></span></div>
        <div class="Lineright"></div>
<!--        <a class="embed_channel_button" href="#popup_embed_channel_container"><?php //echo _('embed channel'); ?></a>
        <div id="popup_embed_channel_container" class="popup_embed_channel_container" data-uri="<?php //echo $embed_uri; ?>">
            <div id="embed_channel_title"><?php //echo _('select size'); ?></div>
            <select class="size_data" name="" onchange="checkSizeEmbed(this)">                        
                <option value="1" selected="selected">103 x 38</option>
                <option value="2">72 x 27</option>
                <option value="3">48 x 18</option>
                <option value="4">23 x 31</option>
            </select>
            <div class="embed_logo_view"></div>
            <textarea class="embed_content" readonly="readonly">

            </textarea>
        </div>
        <div class="Lineright"></div>-->        
        <script>
            $(document).ready(function(){
                $('.size_data').change();
            });
        </script>
    <?php } ?>
    <?php if ($is_owner == 1 && ( !isset($isnotificationspage) or $isnotificationspage!=1 ) ) { ?> 
<!--        <div class="not_channel_button">
            <span class="share_cnt">
                <span class="notifications_counter_box">        
                    <div id="notifications_counter" class="notifications_counter"></div>
                </span>
                <div class="plus" style="position:absolute; right:-3px;"></div>
                <span class="share_lnktext"><?php //echo _('notifications');?></span>
            </span>
            <div class="notifications_feedcontainer notifications_feedchannel">
                <link rel="stylesheet" type="text/css" href="<?php //echo ReturnLink("css/profile_TTpage_left_notifications.css?v=".PROFILE_TTPAGE_LEFT_NOTIFICATIONS_CSS_V);?>" />
            </div>
        </div>
        <div class="Lineright"></div>-->
    <?php } ?>
    <div id="edit_data_container1" class="edit_data_container" style="top:0; margin-top:15px; float:left; z-index:101;"></div>
<?php if( $channel_type != CHANNEL_TYPE_DIASPORA ){ ?>
    <div id="ChannelRight_data1" class="txt">
        <?php if ($is_owner == 1 && $is_home == 1) { ?>
            <div class="editChannelRight_data1 butEditChannelRight_data butEditChannelRight_data_toreset"></div>
        <?php } ?>
        <div class="ChannelRight_data1_content">
            <?php if ($channelInfo['hidecreatedon'] != 1) { ?>
                <div class="marginbottom5 margintop5">
                    <span class="grey"><?php echo _('created on'); ?></span><br />
                    <?php
                        $create_ts = returnSocialTimeFormat( $channelInfo['create_ts'] ,3);
                    ?>
                    <span class="font11"><?php echo $create_ts; ?></span>
                </div>
            <?php } ?>
            <?php if ($channelInfo['hidecreatedby'] != 1) { ?>
                <div class="marginbottom5">
                    <span class="grey"><?php echo _('Name'); ?></span><br />
                    <span class="font11"><?php echo returnUserDisplayName($userChannelInfo); ?></span>
                </div>
            <?php } 
                $catarr = allchannelGetCategory($channelInfo['category']);
            ?>
            <div class="marginbottom5">
                <span class="grey"><?php echo _('category'); ?></span><br />
                <a href="<?php echo ReturnLink('channels-category/'.seoEncodeURL(htmlEntityDecode($catarr[0]['title']))); ?>" class="font11 category_chR"><?php echo htmlEntityDecode($catarr[0]['title']); ?></a>
            </div>
<?php if ($channelInfo['hidelocation'] != 1) { ?>
                <div class="marginbottom5">
                    <span class="grey"><?php print _("location");?></span><br />
                    <span class="font11"><?php echo channelOwnerLocationSmall($channelInfo); ?></span>
                </div>
<?php } ?>
<?php if ($channelInfo['default_link'] != '') { ?>
            <div class="marginbottom5">
                <span class="grey"><?php print _("url");?></span><br />
                <a class="font11" href="<?php echo returnExternalLink($channelInfo['default_link']); ?>" rel="nofollow" target="_blank"><?php echo $channelInfo['default_link']; ?></a>
            </div>
<?php } ?>
        </div>
    </div>
    <?php if($is_owner==1 || $channel_desc !=''){?>
    <div class="sep"></div>
    <div id="ChannelRight_data2" class="txt paddingtop0">
    <?php if ($is_owner == 1 && $is_home == 1) { ?>
            <div class="editChannelRight_data2 butEditChannelRight_data"></div>
            <div class="editChannelRight_button_container">
                <div class="editChannelRight_data2_buts1 editChannelRight_buts"><?php echo _('cancel'); ?></div>
                <div class="editChannelRight_seperator"></div>
                <div class="editChannelRight_data2_buts2 editChannelRight_buts"><?php echo _('save'); ?></div>
            </div>
            <div id="edit_data_container2">
                <textarea id="edit_aboutchannel" class="ChaFocus" style="font-family:Arial, Helvetica, sans-serif; width:213px; height:116px; margin-left:3px;" type="text" name="edit_aboutchannel"></textarea>
            </div>
        <?php
        }
        $channel_desc = str_replace("\n", "<br/>", $channel_desc);
        ?>
        <div class="mustopen">
            <div class="stanTXT" id="stanTXT">
                <span class="yellow bold font12 yellowabout"><?php echo _('ABOUT'); ?></span><br/> <?php echo $channel_desc; ?>
            </div>
        </div>
        <div class="font10 yellow italic bold more marginbottom10" id="more">> <?php echo _('more'); ?></div>
        <script type="text/javascript">
            if ($("#stanTXT").height() <= 105)
                $("#more").hide();
        </script>
    </div>
    <?php } ?>
    <?php if (intval($channelPrivacyArray['privacy_activechannels']) == 1): ?>

        <?php include("parts/most_active_channels.php"); ?>

    <?php endif; ?>
    <?php if (intval($channelPrivacyArray['privacy_activetubers']) == 1): ?>
        
        <?php include("parts/most_active_tubers.php");
    endif; ?>
<?php }else{
    include("parts/channel_diaspora_ticker.php");
 ?>   
    <div class="sep"></div>
    
   <?php if($user_is_logged):
       if( $channel_type == CHANNEL_TYPE_DIASPORA ){
            $options3 = array('status' => 3,'order'=>'d','orderby' => 'todate','owner_id' => $userid);
            $upcommingCalendarEventArray = channeleventSearch($options3);
            $upcommingCalendarEventArray= ($upcommingCalendarEventArray) ? $upcommingCalendarEventArray : array();
            $sponsoringCalendarArray = socialSharesGet(array(
                    'orderby' => 'share_ts',
                    'order' => 'd',
                    'unique' => 1,
                    'is_visible'=>$is_owner_visible,
                    'owner_id' => $userid,
                    'entity_type' => SOCIAL_ENTITY_EVENTS,
                    'share_type' => SOCIAL_SHARE_TYPE_SPONSOR
            ));
            
            $sponsoringCalendarArray= ($sponsoringCalendarArray) ? $sponsoringCalendarArray : array();
        }
       ?>
        
       <a href="<?php GetLink('channel-events/'.$channelInfo['channel_url']);?>"><div class="font11bold yellow formContainer100 marginleft9"><?php echo _('my events');?></div></a>
       <div id="idEventsCalendar" class="ed_Calendar formContainer100 marginleft9">
            <div class="ed_CalTooltipContent" id="ed_CalTooltipContent">
                <div class="ed_CalTooltip">
                    <a href="" class="ed_CalTooltip_a">
                        <img src='' class="ed_CalEventImg" width='86' height='56'>
                        <div class="ed_CalTooltipTitle"></div>
                        <div class='ed_CalTooltipViewEvent'><?php echo _('view event');?></div>
                    </a>
                    <img src='<?php GetLink('media/images/eventsdetailed/close-tooltip.png'); ?>' class="ed_CalTooltipClose" width='18' height='18'>
                </div>
            </div>
        </div>
       <script type="text/javascript">  
        var DATE_INFOInit =[]; 
	var DATE_INFO =[]; 
        <?php if( $channel_type == CHANNEL_TYPE_DIASPORA ){ ?>  
            if(parseInt(user_is_logged)!=0){ 
		DATE_INFO = {
				 <?php
					$i=0;
					$len=sizeof($upcommingCalendarEventArray);
					$str="";
					foreach($upcommingCalendarEventArray as $channelUpcommingCalendarEvent){
						$i++;
						
						$id = $channelUpcommingCalendarEvent['id'];
						$photo = ($channelUpcommingCalendarEvent['photo']) ? photoReturneventImage($channelUpcommingCalendarEvent) : ReturnLink('media/images/channel/eventthemephoto.jpg');
						$from_date = date( 'Y-m-d', strtotime($channelUpcommingCalendarEvent['fromdate']) );
						$Title = htmlEntityDecode($channelUpcommingCalendarEvent['name']);
						if(strlen($Title)>33)$Title = substr($Title,0,32).'...';
    $Title = addslashes($Title);
						$from_date_arr=explode('-',$from_date);					
						$from_date_display=$from_date_arr[0].''.$from_date_arr[1].''.$from_date_arr[2];
						echo $from_date_display.': { klass: "highlight"}';
						$str .=$from_date_display.'[*]highlight[*]'.$photo.'[*]'.$Title.'[*]'.$id.'[*]1';
						if($i<$len){
							echo ',';
							$str .="/*/";
						}
					}  
				
					$i=0;
					$len1=sizeof($sponsoringCalendarArray);
					
					foreach($sponsoringCalendarArray as $channelSponsoringCalendar){
						if($len>0 && $i==0){
							echo ',';
							$str .="/*/";
						}
						$i++;
						$channel_id_cal = $channelSponsoringCalendar['channel_id'];
						$entity_id = $channelSponsoringCalendar['entity_id'];
						$eventArray_init=unitGetChannelEvent($channel_id_cal,$entity_id);
						$eventArray=$eventArray_init[0];
						$id = $eventArray['id'];
						
						$photo = ($eventArray['photo']) ? photoReturneventImage($eventArray) : ReturnLink('media/images/channel/eventthemephoto.jpg');
						$from_date = date( 'Y-m-d', strtotime($eventArray['fromdate']) );
						$Title = htmlEntityDecode($eventArray['name']);
						if(strlen($Title)>33)$Title = substr($Title,0,32).'...';
    $Title = addslashes($Title);
						$from_date_arr=explode('-',$from_date);					
						$from_date_display=$from_date_arr[0].''.$from_date_arr[1].''.$from_date_arr[2];
						echo $from_date_display.': { klass: "highlight2"}';
						$str .=$from_date_display.'[*]highlight2[*]'.$photo.'[*]'.$Title.'[*]'.$id.'[*]2';					
						if($i<$len1){
							echo ',';
							$str .="/*/";
						}
					}
				?>
		};
		
		var infocalstring = '<?php echo $str; ?>';
		if(infocalstring!=''){
			for (var key in DATE_INFO){			
				DATE_INFOInit[key]=new Array();
			}		
			var infocalstringarr=infocalstring.split('/*/');
			
			var cal_event_id_array=new Array();
			var cal_event_key_array=new Array();
			
			for(var i=0;i<infocalstringarr.length;i++){
				var newarr=infocalstringarr[i].split('[*]');
				var unique_sponsor_id=true;
				if(parseInt(newarr[5])==2){
					for(var k=0;k<cal_event_id_array.length;k++){
						if(parseInt(cal_event_id_array[k])==parseInt(newarr[4]) && (cal_event_key_array[k])==(newarr[0])){
							unique_sponsor_id=false;
							break;
						}
					}
				}
				if(unique_sponsor_id){
					var newarr1={klass:''+newarr[1]+'',imageurl:''+newarr[2]+'',title:''+newarr[3]+'',id:''+newarr[4]+'',_type:''+newarr[5]+''};
					
					DATE_INFOInit[newarr[0]].push(newarr1);	
					if(parseInt(newarr[5])==2){
						cal_event_id_array.push(parseInt(newarr[4]));
						cal_event_key_array.push(parseInt(newarr[0]));
					}
				}
			}
		}
	}
  <?php } ?> 
           $(document).ready(function(){
               initCalendarEvents();
           });
       </script>
    <?php endif; ?>
    <?php $AllChannels =socialSharesGet(array(
                'orderby' => 'share_ts',
                'order' => 'd',
                'limit' =>100,
                'page' => 0,
                'from_user' => $channelInfo['id'],
                'is_visible'=>$is_visible,
                'entity_type' => SOCIAL_ENTITY_CHANNEL,
                'share_type' => SOCIAL_SHARE_TYPE_SPONSOR
            ));
    if ($AllChannels) {
        ?>
        <div class="sep margintop15"></div>
        <div class="txt ChannelRight_data4 channel_txt2 paddingtop0 zindex10">
            <h2 class="font16 yellow lineheight16 h1Style mostActiveCH"><?php echo _('MY SPONSORED<br/>CHANNELS'); ?></h2>
            <div class="list margintop15">
                <ul>
                <?php
                foreach ($AllChannels as $channel) {
                    $ch_id = $channel['id'];
                    $ch_name = htmlEntityDecode($channel['channel_name']);
                    if ($channel['logo']) {
                        $lo_logo = ReturnLink('media/channel/' . $channel['id'] . '/thumb/' . $channel['logo']);
                        $ch_logo = '<a href="' . channelMainLink($channel) . '" title="' . $ch_name . '"><img src="' . photoReturnchannelLogo($channel) . '" alt="' . $ch_name . '"></a>';
                    } else {
                        $lo_logo = ReturnLink('/media/tubers/thumb_tuber.jpg');
                        $ch_logo = '<a href="' . channelMainLink($channel) . '" title="' . $ch_name . '"><img width="28" height="28" src="' . ReturnLink('/media/tubers/xsmall_tuber.jpg') . '" alt="' . $ch_name . '"></a>';
                    }
                    $ch_pop_logo = '<a href="' . channelMainLink($channel) . '" title="' . $ch_name . '"><img width="100" height="100" src="' . $lo_logo . '" class="imgborder0" alt="' . $ch_name . '"></a>';
                    $ch_connect = '<img src="' . ReturnLink('media/images/channel/connect_channel.gif') . '">';
                    $poUpdata = $ch_pop_logo . '<br />' . $ch_name;
                    ?>
                    <li>
                        <?php echo $ch_logo; ?>
                        <div class="channelpopUp popUp">
                            <?php
                            echo $poUpdata;                            
                            ?>
                        </div>
                    </li>
            <?php } ?>
                </ul>
            </div>
        </div>
    <?php }?>
    <?php $AllTubers = channelConnectedTubersSearch(array('limit' =>21,'page' =>0,'is_visible'=>$is_visible, 'order' => 'a', 'channelid' => $channelInfo['id']));
    if ($AllTubers) {
        ?>
        <div class="sep margintop15"></div>
        <div class="txt ChannelRight_data4 channel_txt2 paddingtop0 zindex9">
            <h2 class="font16 yellow lineheight16 h1Style mostActiveCH"><?php echo _('CONNECTED TUBERS<br/>FROM LEBANON'); ?></h2>
            <div class="list margintop15">
                <ul>
                <?php
                foreach ($AllTubers as $tuber) {
                    $ch_id = $tuber['id'];
                    $ch_name = returnUserDisplayName($tuber);	
                    $uslnk= userProfileLink($tuber);
                    if($user_is_logged==1){
                        $ch_logo = '<a href="' . $uslnk . '" target="_blank"><img alt="' . returnUserDisplayName($tuber) . '" title="' . returnUserDisplayName($tuber) . '" src="' . ReturnLink('/media/tubers/' . $tuber['profile_Pic']) . '"/></a>';
                    }else{
                        $ch_logo = '<div><img alt="' . returnUserDisplayName($tuber) . '" title="' . returnUserDisplayName($tuber) . '" src="' . ReturnLink('/media/tubers/' . $tuber['profile_Pic']) . '"/></div>';
                    }
                    $poUpdata = $ch_logo . '<br />' . $ch_name;
                    if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
                        continue;
                ?>
                    <li>
                <?php echo $ch_logo; ?>
                        <div class="tuberpopUp popUp">
                <?php echo $poUpdata; ?>
                        </div>
                    </li>
        <?php } ?>
                </ul>
            </div>
        </div>
    <?php }?>
    <?php $AllTubers = channelConnectedTubersSearch(array('limit' =>21,'page' =>1,'is_visible'=>$is_visible, 'order' => 'a',  'channelid' => $channelInfo['id']));
    if ($AllTubers) {
        ?>
        <div class="sep margintop15"></div>
        <div class="txt ChannelRight_data4 channel_txt2 paddingtop0 zindex8">
            <h2 class="font16 yellow lineheight16 h1Style mostActiveCH"><?php echo _('CONNECTED TUBERS<br/>FROM USA'); ?></h2>
            <div class="list margintop15">
                <ul>
                <?php
                foreach ($AllTubers as $tuber) {
                    $ch_id = $tuber['id'];
                    $ch_name = returnUserDisplayName($tuber);	
                    $uslnk= userProfileLink($tuber);
                    if($user_is_logged==1){
                        $ch_logo = '<a href="' . $uslnk . '" target="_blank"><img alt="' . returnUserDisplayName($tuber) . '" title="' . returnUserDisplayName($tuber) . '" src="' . ReturnLink('/media/tubers/' . $tuber['profile_Pic']) . '"/></a>';
                    }else{
                        $ch_logo = '<div><img alt="' . returnUserDisplayName($tuber) . '" title="' . returnUserDisplayName($tuber) . '" src="' . ReturnLink('/media/tubers/' . $tuber['profile_Pic']) . '"/></div>';
                    }
                    $poUpdata = $ch_logo . '<br />' . $ch_name;
                    if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
                        continue;
                ?>
                    <li>
                <?php echo $ch_logo; ?>
                        <div class="tuberpopUp popUp">
                <?php echo $poUpdata; ?>
                        </div>
                    </li>
        <?php } ?>
                </ul>
            </div>
        </div>
    <?php }?>
    <?php $AllTubers = channelConnectedTubersSearch(array('limit' =>21,'page' =>2,'is_visible'=>$is_visible, 'order' => 'a',  'channelid' => $channelInfo['id']));
    if ($AllTubers) {
        ?>
        <div class="sep margintop15"></div>
        <div class="txt ChannelRight_data4 channel_txt2 paddingtop0 zindex7">
            <h2 class="font16 yellow lineheight16 h1Style mostActiveCH"><?php echo _('CONNECTED TUBERS<br/>FROM EUROPE'); ?></h2>
            <div class="list margintop15">
                <ul>
                <?php
                foreach ($AllTubers as $tuber) {
                    $ch_id = $tuber['id'];
                    $ch_name = returnUserDisplayName($tuber);	
                    $uslnk= userProfileLink($tuber);
                    if($user_is_logged==1){
                        $ch_logo = '<a href="' . $uslnk . '" target="_blank"><img alt="' . returnUserDisplayName($tuber) . '" title="' . returnUserDisplayName($tuber) . '" src="' . ReturnLink('/media/tubers/' . $tuber['profile_Pic']) . '"/></a>';
                    }else{
                        $ch_logo = '<div><img alt="' . returnUserDisplayName($tuber) . '" title="' . returnUserDisplayName($tuber) . '" src="' . ReturnLink('/media/tubers/' . $tuber['profile_Pic']) . '"/></div>';
                    }
                    
                    
                    $poUpdata = $ch_logo . '<br />' . $ch_name;
                    if (strstr($tuber['profile_Pic'], 'tuber.jpg') != null)
                        continue;
                ?>
                    <li>
                <?php echo $ch_logo; ?>
                        <div class="tuberpopUp popUp">
                <?php echo $poUpdata; ?>
                        </div>
                    </li>
        <?php } ?>
                </ul>
            </div>
        </div>
    <?php }?>
<?php } // end else diaspora ?>

</div>