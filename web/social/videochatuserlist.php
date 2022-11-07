<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );
$user_ID = userGetID();
$userInfo= getUserInfo($user_ID);
$userName= returnUserDisplayName( $userInfo , array('max_length' => 12) );
$freinds = userGetChatList($user_ID);

/**
* gets the chat status of a user
* @param integer $user_id the user's id
* @return integer 0 => offline, 1=> online , 2=> away
*/
function chatGetUserStatus($user_id){

global $dbConn;
$params = array();  
//$query = "SELECT * FROM cms_chat_status WHERE user_id='$user_id'";
$query = "SELECT * FROM cms_chat_status WHERE user_id=:User_id";
$params[] = array(  "key" => ":User_id",
                    "value" =>$user_id);
$select = $dbConn->prepare($query);
PDO_BIND_PARAM($select,$params);
$res    = $select->execute();
$ret    = $select->rowCount();
//$ret = db_query($query);
if( !$res || ($ret==0) ){
return 0;
}else{
$row = $select->fetch();
//$row = db_fetch_array($ret);
$status = $row['status'];
$last_action = $row['last_action'];
return $status;
}
}

if(count($freinds)){

//echo $updatedstatus = ($_GET[1]+1);
//echo $updateduserid = $_GET[0];


// update user status as available here
$isupdated = chatGetUserStatus($user_ID);
if(empty($isupdated)){
global $dbConn;
$params = array();  
//$usrstatusquery = "INSERT INTO `cms_chat_status` (`user_id`, `status`) VALUES ('$user_ID', '1')";
$usrstatusquery = "INSERT INTO `cms_chat_status` (`user_id`, `status`) VALUES (:User_ID, '1')";
$params[] = array(  "key" => ":User_ID",
                    "value" =>$user_ID);
$select = $dbConn->prepare($usrstatusquery);
PDO_BIND_PARAM($select,$params);
$stattusret    = $select->execute();
//$stattusret = db_query($usrstatusquery);
}/*else { //exit($updatedstatus.'-'.$updateduserid);
$usrstatusquery = "update `cms_chat_status` set status ='$updatedstatus' WHERE user_id='$updateduserid'";
//$stattusret = db_query($usrstatusquery);
}*/
//ends


// predefined variables
define('CHAT_STATUS_OFFLINE',0);
define('CHAT_STATUS_ONLINE',1);
define('CHAT_STATUS_AWAY',2);
define('CHAT_STATUS_BUSY',3);
define('CHAT_STATUS_APPEAROFFLINE',100);

//related colors

$status = array(CHAT_STATUS_AWAY => 'yellow' ,
CHAT_STATUS_BUSY => 'red' ,
CHAT_STATUS_ONLINE => 'green' ,
CHAT_STATUS_OFFLINE => 'grey'
);


$status_string = array( CHAT_STATUS_AWAY => _('Away'),
CHAT_STATUS_BUSY => _('Busy')  , 
CHAT_STATUS_ONLINE => _('Available') , 
CHAT_STATUS_OFFLINE => _('Offline'), 
CHAT_STATUS_APPEAROFFLINE => _('Offline') 
);
    $status_image = array( CHAT_STATUS_AWAY => 'chat-away.png',
CHAT_STATUS_BUSY => 'chat-busy.png'  ,
CHAT_STATUS_ONLINE => 'chat-online.png' ,
CHAT_STATUS_OFFLINE => 'chat-offline.png',
CHAT_STATUS_APPEAROFFLINE => 'chat-offline.png'
);
?>
<script type="text/javascript" src="/video_chat/js/chat-behavior.js?v=<?= CHAT_JS_V;?>"></script>


<div id="chatSettingsList">
<div id="chatSettingsPointer"></div>
<div class="onelistChatSettings" id="turnOffChat">Switch off chat</div>
</div>
<div class="chat-overlay-loading-fix"><div></div></div>
<div class="one-chat-myimg"><img src="<?php GetLink('media/tubers/' . userCroppedPhoto($userInfo['profile_Pic']) )?>" border="0"></div>
<div class="one-chat-username" userid="<?php echo $user_ID; ?>"><?php echo $userName; ?></div>
<div id="my_bottom_border"></div>
<div class="one-chat-checkbox">
<div class="dd-container" id="demoBasic">
<div class="dd-select"><input value="<?php echo  $isupdated;?>" class="dd-selected-value" type="hidden"><a class="dd-selected">
<img class="dd-selected-image" src="<?php echo '/media/images/'.$status_image[$isupdated];?>">
<label class="dd-selected-text">&nbsp;<?php $status[$isupdated] ?></label></a>
<span class="dd-pointer dd-pointer-down"></span>
</div>
<ul class="dd-options dd-click-off-close">
<li><a class="dd-option dd-option-selected"> <input class="dd-option-value" value="1" type="hidden"> <img class="dd-option-image" src="/media/images/chat-online.png"> <label class="dd-option-text">&nbsp;Available</label></a></li>
<li><a class="dd-option"> <input class="dd-option-value" value="2" type="hidden"> <img class="dd-option-image" src="/media/images/chat-away.png"> <label class="dd-option-text">&nbsp;Away</label></a></li>
<li><a class="dd-option"> <input class="dd-option-value" value="3" type="hidden"> <img class="dd-option-image" src="/media/images/chat-busy.png"> <label class="dd-option-text">&nbsp;Busy</label></a></li>
<li><a class="dd-option"> <input class="dd-option-value" value="100" type="hidden"> <img class="dd-option-image" src="/media/images/chat-offline.png"> <label class="dd-option-text">&nbsp;invisible</label></a></li>
</ul>
</div>
</div>
<div id="onlineFav">
<div class="oneTabFav selectedFav" id="ALL"><?php print _("ALL")?></div>
<div class="oneTabFav" id="ONLINE"><?php print _("ONLINE")?></div>
<div class="oneTabFav" id="FAVORITES"><?php print _("FAVORITES");?></div>
</div>
<div id="chat-container">
<div id="chat-container-inside" class="scroll-panenew">
<?php foreach($freinds as $freind): 

$status_id = chatGetUserStatus($freind['id']);
$favoritesClass = "";
$isFavorite = userFavoriteUserAdded($user_ID,$freind['id']);

if($isFavorite) $isFavorite = "1";
else $isFavorite = "0";  

$profilepic = isset($freind['profile_Pic']) && !empty($freind['profile_Pic'])? $freind['profile_Pic']:'no_image.png';
?>

<div class="one-chat-rec" data-tuber="<?php echo $freind['id'] ?>" id="ChatListItem<?php echo $freind['id'] ?>" data-blocked="<?php echo $freind['blocked']; ?>" data-chat-status="<?php echo strtolower($status_string[$status_id]); ?>" data-chat-favorites="<?php echo $isFavorite; ?>">
    <div class="one-chat-overlay-grey"></div>
    <div class="one-chat-img <?php echo strtolower($status_string[$status_id]); ?>"><img border="0" src="<?php GetLink('media/tubers/' . userCroppedPhoto($profilepic) )?>" alt="<?php echo returnUserDisplayName($freind,array('max_length'=>18)); ?>" border="0"></div>
    <div class="one-chat-name" data-uname="<?php echo $freind['YourUserName'] ?>"><span><?php echo returnUserDisplayName($freind,array('max_length'=>18)); ?></span> </div>
    <div class="one-chat-remove-fav" title="remove"></div>
    <div class="one-chat-block"></div>
    <div class="one-chat-video"></div>
    <div class="one-chat-msg"><span></span></div>
</div>
<?php endforeach; ?>


</div>

</div>

<?php } else {
echo 0;
}?>   