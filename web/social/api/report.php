<?php
header('Content-type: application/json');
include("heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) { die(); }
$entity_id = intval($request->request->get('entity_id', 0));
$entity_type = intval($request->request->get('entity_type', 0));
$channel_id = intval($request->request->get('channel_id', 0));
$msg = $request->request->get('msg', '');
$reason = $request->request->get('reason', '');
$title = $request->request->get('title', '');
$email = $request->request->get('email', '');
$owner_id = socialEntityOwner($entity_type, $entity_id );
if($owner_id=='' || !$owner_id){
    $owner_id=0;
}
$report_id = AddReportData($user_id,$owner_id,$entity_id,$entity_type,$channel_id,$msg,$reason,$title,$email);
if($report_id){		
    $ret_arr['status'] = 'success';
}else{
    $ret_arr['status'] = 'error';
}
echo json_encode( $ret_arr );
