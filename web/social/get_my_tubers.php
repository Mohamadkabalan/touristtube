<?php
if( !isset($bootOptions) ){
    $path = "";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
}
$to_user = 0;
//if(isset($_GET['to_user']))
$status_get = $request->query->get('status','');
$to_user_get = $request->query->get('to_user','');
if($to_user_get)
{
//    $to_user = $_GET['to_user'];
    $to_user = $to_user_get;
    $user_ID = userGetID();
    $userInfo= getUserInfo($user_ID);
    $userName= userGetName();
    $friends = userGetFreindListNew($user_ID);

    
//    if(isset($_GET['status']))
    if($status_get)
    {
//        if($_GET['status'] == "notification")
        if($status_get == "notification")
        {
            $friends = userGetChatListRemoved($user_ID);
        }
    }
    $friendslist = "";
    foreach($friends as $oneFriend)
    {
        if($oneFriend['id'] != $to_user )
        {
        $imgSrc = ReturnLink("media/tubers/" . userCroppedPhoto($oneFriend['profile_Pic']));
        $friendslist .= "<div class='one_tuber_share' data-tuber='".$oneFriend['id']."' data-image='".$imgSrc."' >
                <div class='one_tuber_img'><img src='".$imgSrc."' alt='".returnUserDisplayName($oneFriend)."'></div>
                <div class='one_tuber_name'>".returnUserDisplayName($oneFriend)."</div>
        </div>";
        }
    }

    $javascriptFunction = 'shareTubersToChatWindow($to_user);';
    $btnName = "share";

}
//if(isset($_GET['status']))
//{
//    if($_GET['status'] == "notification")
if($status_get)
{
    if($status_get == "notification")
    {
        $javascriptFunction = 'getRemovedChecked();';
        $btnName = _('add');
    }
}
?>
<script>
function extraBoxClose()
{
    $('#blackTransparentDiv').trigger('click');
}
</script> 
<div id="shareTubersContainer">
	<div id="shareTuberTitle"><?php echo _('share tubers');?></div>
    <div id="shareTubersInside">
		<?php echo ($friendslist != "")?$friendslist:"<div class='one_tuber_notfound'>"._('No tubers found!!')."</div>"; ?>
    </div>
    <div id="sharebtns"> <a href='javascript:<?php echo $javascriptFunction; ?>'><?php echo $btnName; ?></a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href="javascript:extraBoxClose();"><?php echo _('cancel');?></a></div>
</div>