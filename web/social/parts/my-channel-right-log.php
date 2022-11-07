<div id="ChannelRight" class="marginright10 notifications_feedcontainer">
<?php
$current_channel = channelFromURL (db_sanitize(UriGetArg(0)));
?>
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
</div>