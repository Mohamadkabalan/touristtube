<?php
/*! \file
 * 
 * \brief This api to disconnect to a channel
 * 
 * 
 * @param S  session id
 * @param channel_id  channel id
 * 
 * @return <b>ret</b> List of status information (array):
 * @return <pre> 
 * @return         <b>status</b> status message
 * @return         <b>msg</b> error message
 * @return </pre>
 * @author Anthony M
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */ 

$expath = "../";
header('Content-type: application/json');
include("../heart.php");

//$user_id = $_SESSION['id'];
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
$ret = array();
if( !$user_id ){
    $ret['status'] = 'error';
    $ret['type'] = 'session';
    $ret['msg'] = _('Please login to complete this task.');
    echo json_encode($ret);
    exit;
}
	
//$channel_id = intval($_REQUEST['channel_id']);
$channel_id = intval($submit_post_get['channel_id']);
if($channel_id == 0){
    $ret['status'] = 'error';
    $ret['msg'] = _('Couldn\'t Process Request. Please try again later.');
    echo json_encode($ret);
    exit();
}
$create_ts = date('Y-m-d');

if( disconnectedTubers($user_id,$channel_id,$create_ts) ){
        $ret['status'] = 'ok';
        $ret['msg'] = _('You are no longer connected to this channel');
}else{
        $ret['status'] = 'error';
        $ret['msg'] = _('Couldn\'t Process Request. Please try again later.');
}

echo json_encode($ret);