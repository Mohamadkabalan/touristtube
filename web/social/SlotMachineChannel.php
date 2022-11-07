<div id="slot-machine-tabs">
<ul id="tabsmain" class="visited">
    <li>
        <a class="<?php if (in_array(tt_global_get('page'), array('things-to-do.php', 'things-to-do-search.php', 'top-things-to-do.php'))) echo 'selected'; ?>" href="<?php echo ReturnLink('things-to-do') ?>" title="<?php echo _('Things To Do') ?>">
            <span class="mntxt"><?php echo _('things to do') ?></span>
            <span class="sepright  selected"></span>
        </a>
    </li>
<li class="selected"><a class="tabsofive" href="<?php echo ReturnLink('channels', null , 0, 'channels'); ?>" title="<?php echo _('Tourist Channels') ?>"><span class="mntxt"><?php echo _('Tourist Channels') ?></span><span class="sepright"></span></a></li>
<li><a class="tabsofour requestinvite" href="<?php GetLink('live') ?>" title="<?php echo _('Tourist Live') ?>"><span class="mntxt"><?php echo _('Tourist Live') ?></span><span class="sepright"></span></a></li>

<li><a class="tabsofour requestinvite" href="<?php GetLink('review') ?>" title="<?php echo _('Review and Rate Hotels and Restaurants') ?>"><span class="mntxt"><?php echo _('Review & Rate') ?>&nbsp;</span><span class="sepright"></span></a></li>
<?php if( userIsLogged() ){ ?>
<!--<li><a class="tabsofour requestinvite" href="<?php //GetLink('account/invite') ?>" title="<?php //echo _('Send Invitation') ?>"><span class="mntxt underline"><?php //echo _('Send Invitation') ?>&nbsp;</span></a></li>-->
<?php }else{ ?>
<li><a class="tabsofour requestinvite" href="<?php GetLink('register') ?>" title="<?php echo _('register') ?>"><span class="mntxt underline"><?php echo _('register') ?>&nbsp;</span></a></li>
<?php } ?>
</ul>        
</div>