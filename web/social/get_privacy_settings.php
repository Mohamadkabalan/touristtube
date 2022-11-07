<?php

if( !isset($bootOptions) ){
	$path = "";

	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );

	include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/users.php" );
}


?>
<div id="leftTimeCat">
	<div id="leftTimeCatTitle"><span><?php echo _('View');?> <br> <?php echo _('chat history');?></span></div>
    <div id="chat_time_history">
    	<?php echo $chatHistoryDate; ?>
    </div>
</div>
<div id="rightChatHistory">
	<?php echo $chatHistory; ?>
</div>	
