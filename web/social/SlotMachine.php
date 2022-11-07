<div id="slot-machine-tabs">
<ul id="tabsmainarr">
<li id="image1"></li>
<li id="image2"></li>
<li id="image3"></li>
<li id="image4"></li>
<li id="image5"></li>
</ul>
<ul id="tabsmain" class="visited" >
    <li>
        <a class="<?php if (in_array(tt_global_get('page'), array('things-to-do.php', 'things-to-do-search.php', 'top-things-to-do.php'))) echo 'selected'; ?>" href="<?php echo ReturnLink('things-to-do') ?>" title="<?php echo _('Things To Do') ?>">
            <span class="mntxt"><?php echo _('Things To Do') ?></span>
            <span class="sepright<?php if (in_array(tt_global_get('page'), array('channels.php'))) echo ' selected'; ?>"></span>
        </a>
    </li>
    <li class="largecell3">
        <a class="tabsofive requestinvite" href="<?php echo ReturnLink('channels', null , 0, 'channels'); ?>" title="<?php echo _('Tourist Channels') ?>">
            <span class="mntxt"><?php echo _('Tourist Channels') ?></span>
            <span class="sepright<?php if (in_array(tt_global_get('page'), array('live.php', 'live-cam.php'))) echo ' selected'; ?>"></span>
        </a>
    </li>
<li class="<?php if (in_array(tt_global_get('page'), array('live.php', 'live-cam.php'))) echo 'selected'; ?>"><a class="tabsofour requestinvite" href="<?php GetLink('live') ?>" title="<?php echo _('Tourist Live') ?>"><span class="mntxt"><?php echo _('Tourist Live') ?></span><span class="sepright<?php if (in_array(tt_global_get('page'), array('review.php'))) echo ' selected'; ?>"></span></a></li>

<li class="<?php if (in_array(tt_global_get('page'), array('review.php'))) echo 'selected'; ?>"><a class="tabsofour requestinvite" href="<?php GetLink('review') ?>" title="<?php echo _('Review and Rate Hotels and Restaurants') ?>"><span class="mntxt"><?php echo _('Review & Rate') ?>&nbsp;</span><span class="sepright<?php if (in_array(tt_global_get('page'), array('register.php','account.php'))) echo ' selected'; ?>"></span></a></li>
<?php if( userIsLogged() ){ ?>
<!--<li class="<?php //if (in_array(tt_global_get('page'), array('register.php','account.php'))) echo 'selected'; ?>"><a class="tabsofour requestinvite" href="<?php //GetLink('account/invite') ?>" title="<?php //echo _('Send Invitation') ?>"><span style="margin-right:10px;" class="mntxt underline"><?php //echo _('Send Invitation') ?>&nbsp;</span></a></li>-->
<?php }else{ ?>
<li class="<?php if (in_array(tt_global_get('page'), array('register.php','account.php'))) echo 'selected'; ?>"><a class="tabsofour requestinvite" href="<?php GetLink('register') ?>" title="<?php echo _('register') ?>"><span style="margin-right:10px;" class="mntxt underline"><?php echo _('register') ?>&nbsp;</span></a></li>
<?php } ?>
</ul>
<ul class="tabs onclose visited" id="tabs">
<li><a class="tabsone" href="javascript:;">1</a></li>
<li><a class="tabstwo" href="javascript:;">2</a></li>
<li><a class="tabsthree" href="javascript:;">3</a></li>
<li><a class="tabsfour" href="javascript:;">4</a></li>
<li><a class="tabsfive" href="javascript:;">5</a></li>
<li><a class="tabssix" href="javascript:;">6</a></li>
</ul>
<div class="box-wrapper onclose visited"></div>
</div>