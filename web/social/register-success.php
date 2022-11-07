<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );
$userid = userGetID();
$user_is_logged = 0;

$includes = array( 'css/register.css', 'assets/channel/js/channel-header.js', 'css/channel-header.css','media'=>'css_media_query/media_style_static_page.css?v='.MQ_MEDIA_STYLE_CSS_V,'media1'=>'css_media_query/register-success.css?v='.MQ_MEDIA_STYLE_CSS_V);

if (userIsLogged()) {
    $user_is_logged = 1;
}
if (userIsLogged() && userIsChannel()) {
    array_unshift($includes, 'css/channel-header.css');
   tt_global_set('includes', $includes);
    include("TopChannel.php");
} else {
   tt_global_set('includes', $includes);
    include("TopIndex.php");
}
$page = db_sanitize(UriGetArg(0));
$isChannel = NULL;
if ($page == 'channel') {
    $isChannel = 1;
}
?>
<div id="MiddleInsideNormal">

    <div id="InsideNormal">
        <div id="BecomeTuberForm">
            <?php
            if ($isChannel) {
                echo '<div class="activateMsg">'._('An email is sent to you to activate your channel account.').'</div>';
            } else {
                echo '<div class="activateMsg">'._('An email is sent to you to activate your account.').'</div>';
            }
            ?>
        </div>
    </div>
</div>
<?php include("closing-footer.php");?>