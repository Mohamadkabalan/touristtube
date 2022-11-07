<div id="ChannelRight" class="marginright10">
    <div id="edit_data_container1" class="edit_data_container" style="top:0; margin-top:15px; float:left; z-index:101;"></div>
    <?php if ($is_owner == 1) { ?> 
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
    <div id="ChannelRight_data1" class="txt">
    	<?php if($is_owner==1 && $is_home==1){?>
            <div class="editChannelRight_data1 butEditChannelRight_data butEditChannelRight_data_toreset"></div>
        <?php }?>
        <div class="ChannelRight_data1_content">
			<?php if($channelInfo['hidecreatedon']!=1){?>
            <div class="marginbottom5 margintop5">
                <span class="grey"><?php echo _('created on');?></span><br />
                <?php
                    $create_ts = returnSocialTimeFormat( $channelInfo['create_ts'] ,3);
                ?>
                <span class="font11"><?php echo $create_ts; ?></span>
            </div>
            <?php }?>
			<?php if($channelInfo['hidecreatedby']!=1){?>
            <div class="marginbottom5">
                <span class="grey"><?php echo _('created by');?></span><br />
                <span class="font11"><?php echo returnUserDisplayName($userChannelInfo); ?></span>
            </div>
            <?php } 
                $catarr = allchannelGetCategory($channelInfo['category']);
            ?>
            <div class="marginbottom5">
                <span class="grey"><?php echo _('category'); ?></span><br />
                <a href="<?php echo ReturnLink('channels-category/'.seoEncodeURL(htmlEntityDecode($catarr[0]['title']))); ?>" class="font11 category_chR"><?php echo htmlEntityDecode($catarr[0]['title']); ?></a>
            </div>
            <?php if($channelInfo['hidelocation']!=1){?>
            <div class="marginbottom5">
                <span class="grey"><?php echo _('location');?></span><br />
                <span class="font11"><?php echo channelOwnerLocationSmall($channelInfo); ?></span>
            </div>
            <?php }?>
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
		<?php if($is_owner==1 && $is_home==1){?>
            <div class="editChannelRight_data2 butEditChannelRight_data"></div>
            <div class="editChannelRight_button_container">
                <div class="editChannelRight_data2_buts1 editChannelRight_buts"><?php echo _('cancel');?></div>
                <div class="editChannelRight_seperator"></div>
                <div class="editChannelRight_data2_buts2 editChannelRight_buts"><?php echo _('save');?></div>
            </div>
            <div id="edit_data_container2">
            	<textarea id="edit_aboutchannel" class="ChaFocus" style="font-family:Arial, Helvetica, sans-serif; width:213px; height:116px; margin-left:3px;" type="text" name="edit_aboutchannel"></textarea>
            </div>
        <?php }
			$channel_desc=str_replace("\n","<br/>",$channel_desc);
		?>
        
        <div class="mustopen">
            <div class="stanTXT" id="stanTXT">
                <span class="yellow bold font12 yellowabout"><?php echo _('ABOUT');?></span><br/> <?php echo $channel_desc; ?>
            </div>
        </div>
        <div class="font10 yellow italic bold more marginbottom10" id="more">> <?php echo _('more');?></div>
        <script type="text/javascript">
        	if($("#stanTXT").height()<=105) $("#more").hide();
        </script>
    </div>
    <?php } ?>
    <div class="sep"></div>
   <?php if($user_is_logged):?>
       <a href="<?php GetLink('channel-events-calendar/');?>"><div class="font11bold yellow formContainer100 marginleft9"><?php echo _('my events calendar');?></div></a>
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
    <?php endif; ?>
    
</div>