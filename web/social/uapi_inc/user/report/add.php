<?php
//	$entity_id = intval($_POST['entity_id']);
//	$entity_type = intval($_POST['entity_type']);
//	$channel_id = intval($_POST['channel_id']);
//	$msg = ( isset($_POST['msg']) )? $_POST['msg']:'';
//	$reason = ( isset($_POST['reason']) )? $_POST['reason']:'';
//	$title = ( isset($_POST['title']) )? $_POST['title']:'';
//	$email = ( isset($_POST['email']) )? $_POST['email']:'';
	$entity_id = intval($request->request->get('entity_id', 0));
	$entity_type = intval($request->request->get('entity_type', 0));
	$channel_id = intval($request->request->get('channel_id', 0));
	$msg = $request->request->get('msg', '');
	$reason = $request->request->get('reason', '');
	$title = $request->request->get('title', '');
	$email = $request->request->get('email', '');
	
	$user_id = userGetID();
        $owner_id = socialEntityOwner($entity_type, $entity_id );
        if($owner_id=='' || !$owner_id) $owner_id=0;
	if( $report_id = AddReportData($user_id,$owner_id,$entity_id,$entity_type,$channel_id,$msg,$reason,$title,$email) ){		
            $ret_arr['status'] = 'ok';		
            $ret_arr['msg'] = _("Your report has been successfully submitted.<br/>We will take adequate action based on our terms of use and conditions.");
	}else{
            $ret_arr['status'] = 'error';
            $ret_arr['msg'] = _("Couldn't save the information. Please try again later.");
	}
	
?>
