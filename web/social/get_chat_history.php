<?php

if( !isset($bootOptions) ){
	$path = "";

	$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );

	include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/users.php" );
}

$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if(isset($_REQUEST['to']))
//{
//	$to_user = $_REQUEST['to'];
if(isset($submit_post_get['to']))
{
	$to_user = $submit_post_get['to'];
	$from_user = userGetID();
	$toUserName = getUserInfo($to_user);
	
	
	/*$getChatHistory = "SELECT * FROM `cms_chat_log` WHERE ( `to_user` = '".$to_user."' AND `from_user` = '".$from_user."' ) OR ( `to_user` = '".$from_user."' AND `from_user` = '".$to_user."' ) AND `client_ts`   ORDER BY `id` DESC ";
	
	//$sendChatHistory = db_query($getChatHistory);*/
	
	$dataChatHistories = userGetChatHistory($from_user,$to_user);
	
	$today = strtotime(date('Y-m-d'));
	
	$yesterday = date('Y-m-d',strtotime('-1 day'));
	//$stillYesterday = false;
	$sevenDays = date('Y-m-d',strtotime('-7 day'));
	//$stillSevenDays = false;
	$thirtyDays = date('Y-m-d',strtotime('-1 month'));
	//$stillThirtyDays = false;
	$threeMonths = date('Y-m-d',strtotime('-3 month'));
	//$stillThreeMonth = false;
	$sixMonths = date('Y-m-d',strtotime('-6 month'));
	//$stillSixMonths = false;
	$oneYear = date('Y-m-d',strtotime('-1 year'));
	
	//$stillOneYear = false;
	$chatHistory = "";
	$chatHistoryDate = "";
	
	//while($dataChatHistory = db_fetch_array($sendChatHistory))
	foreach($dataChatHistories as $dataChatHistory)
	{
		
		$chatUser = "";
		$client_ts = $dataChatHistory['msg_ts'];
		$currentDate = date('Y-m-d',strtotime($client_ts));
		
		
		if($currentDate != $oldDate)
		{
			$dateString = "";
			$divID = "";

			if( strtotime($currentDate) <= $today ) {

					$chatHistoryDate .= "<div class='chat_time_history grey' data-date='today'>"._('Today')."</div>";
					$dateString = "Today";
					$divID = " id='today'";
			}
			else if( $currentDate <= $yesterday ) {
					
					
					$chatHistoryDate .= "<div class='chat_time_history grey' data-date='yesterday'>"._('Yesterday')."</div>";
					$dateString = "Yesterday";
					$divID = " id='yesterday'";
			}
			else if( $currentDate  <= $sevenDays ) {
				
				
				$chatHistoryDate .= "<div class='chat_time_history grey' data-date='sevenDays'>"._('7 days')."</div>";
					$dateString = "7 Days";
					$divID = " id='sevenDays'";
			}
			else if(  $currentDate <= $oneMonth ) {
				$chatHistoryDate .= "<div class='chat_time_history grey' data-date='thirtyDays'>"._('30 days')."</div>";
					$dateString = "1 Month";
					$divID = " id='oneMonth'";
			}
			else if( $currentDat <= $threeMonths ) {
				$chatHistoryDate .= "<div class='chat_time_history grey' data-date='threeMonths'>"._('3 months')."</div>";
					$dateString = "3 Months";
					$divID = " id='threeMonths'";
			}
			else if( $currentDate <= $sixMonths ) {
				$chatHistoryDate .= "<div class='chat_time_history grey' data-date='sixMonths'>"._('6 months')."</div>";
					$dateString = "6 Months";
					$divID = " id='sixMonths'";
			}
			else if( $currentDate <= $oneYear ) {
				$chatHistoryDate .= "<div class='chat_time_history grey' data-date='oneYear'>"._('1 year')."</div>";
					$dateString = "1 Year";
					$divID = " id='oneYear'";
			}else{
				$dateString  = $currentDate;	
			}
			
			$chatHistory .= '<div class="ChatHisMessage historyMessage chatHistoryTitle" '.$divID.' > '.$dateString.' </div>';
		}
		
		
		if($dataChatHistory['from_user'] == $from_user)
		{
			$chatUser = "me";	
		}else{
			$chatUser = returnUserDisplayName($toUserName);	
		}
		
		//echo "from $from_user to $to_user ".$dataChatHistory['msg_txt']."<br>";	
		$chatHistory .= '<div class="ChatHisMessage historyMessage" >
		<span class="User">'.$chatUser.'</span>
		<span class="Date">'.date('h:i:s',strtotime($dataChatHistory['msg_ts'])).'</span>
		<br><span class="Msg">'.$dataChatHistory['msg_txt'].'</span>
		</div>';
		
		$oldDate = $currentDate;
		
	}
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