<?php
$limit = 8;
$currentpage = 0;
$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
if ( !$realUser ) {
     header("Location: " . ReturnLink('/'));
}
$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;

$is_r = 1;

if (UriArgIsset('page')):
    $page = intval(UriGetArg('page'));
else:
    $page = 0;
endif;

if ($userIschannel == 0) {
    $profilePic = $userInfo_loggedUser['profile_Pic'];
    $fullNameStan = htmlEntityDecode($userInfo_loggedUser['FullName']);
    $fullName = cut_sentence_length($fullNameStan, 25);
}
$is_owner = 1;
$event_text = _('MY POSTS');

$linestyle_container = 763;
$page = 0;
$limit = 10;
$reallimit=$limit;
$realpage = $page;
$frtxt = null;
$totxt = null;

$srch_options = array(
   'from_id' => $userInfo['id'],
   'n_results' => true
);
$post_listcount = socialPostsSearch($srch_options);

?>

<script type="text/javascript">
    var pagename = "echoes";
    userGlobalID(<?php echo $userId; ?>);
    var txt_srch_init = '';
    var is_owner = '<?php echo $is_owner; ?>';
    var user_Is_channel = '<?php echo $userIschannel; ?>';
    var user_is_logged = '<?php echo $user_is_logged; ?>';
    var page = 0;
    <?php if ($post_listcount > 0) { ?>
        $(document).ready(function() {
            $(".calendarcontainer").show();
        });
    <?php } ?>
</script>

<div class="upload-overlay-loading-fix"><div></div></div>
<div id="echoes_container">
    <div class="echoes_text"><?php echo $event_text; ?> <span class="yellowbold12"><?php echo $post_listcount; ?></span></div>
    
    <div class="clearboth"></div>
    <div class="linestyle_container" style="width:<?php echo $linestyle_container; ?>px;">
        <div class="linestyle1"></div>
        <div class="linestyle2"></div>
    </div>
    <div class="calendarcontainer">
        <div id="frombutcontainer">
            <div id="frombut" class="calbut"><?php echo _('From'); ?></div>
            <div id="fromtxt" class="caltxt" data-cal=""></div>
        </div>
        <div id="tobutcontainer" class="marginleft10">
            <div id="tobut" class="calbut"><?php echo _('To'); ?></div>
            <div id="totxt" class="caltxt" data-cal=""></div>
        </div>
        <div id="searchCalendarbut"></div>
        <div id="resetpagebut" class="load_more_previous_new"><?php echo _('reset');?></div>
    </div>
    <?php if($post_listcount>0){ ?>
        <div class="echoesList">
            <div class="echoesVerticalline"></div>
            <div class="echoesContent">
                <?php include('parts/profile_post_list.php'); ?>
            </div>
        </div>    
        <div class="flashBlock2">
            <?php if($post_listcount>0) include('parts/social-posts-buttons.php'); ?>
        </div>
        <div class="loadmore_container_up loadMorContLive" data-wd="735">
            <div id="loadmore"><span><?php echo _('Load More...'); ?></span></div>
        </div>
    <?php }else{
                echo '<div class="notFoundCon">' . _("No echoes found") . "</div>";
            }        
        ?>
</div>