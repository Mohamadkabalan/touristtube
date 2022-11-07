<?php

//	$switch_text = isset($_POST['id']) ? ($_POST['id']) : null;
	$switch_text = $request->request->get('id', '');
	
	$userid=userGetID();

	if(intval($switch_text)!=0){
		$channelInfo=channelGetInfo(intval($switch_text));
		if($userid==intval($channelInfo['owner_id'])){
			userSwitchChannel(intval($switch_text));
		}
		$ret_arr['status'] = 'ok';
	}else if($switch_text=="profile"){
		userCurrentChannelReset();
		$ret_arr['status'] = 'ok';	
	}else{
		$ret_arr['status'] = 'error';	
	}
?>
