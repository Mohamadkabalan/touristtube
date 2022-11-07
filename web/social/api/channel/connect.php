<?php
/*! \file
 * 
 * \brief This api to connect to a channel
 * 
 * 
 * @param S  session id
 * @param channel_id  channel id
 * 
 * @return <b>message</b> List of status information  (array):
 * @return <pre> 
 * @return         <b>error</b> error message
 * @return         <b>msg</b> succeed message
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

$expath = "../";
header('Content-type: application/json');
include("../heart.php");

//$user_id = $_SESSION['id'];
//$user_id = mobileIsLogged($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();
	
//$channel_id = intval($_REQUEST['channel_id']);
$channel_id = intval($submit_post_get['channel_id']);
if($channel_id == 0){
    $message['error'] = _('Error connecting, please try again');
    echo json_encode($message);
    exit();
}

$connectedTubers = getConnectedtubers($channel_id);

$message = array();
$message['error'] = '';
$message['msg'] = '';

if (in_array($user_id, $connectedTubers)) {
    $message['error'] = _('You are already connected to the following channel');
    echo json_encode($message);
}else{
    if($res=connectTochannel($user_id, $channel_id)){
        $channel = channelGetInfo($channel_id);
        $message['error'] = '';
        $message['msg'] = _('You are now connected to the following channel');
        echo json_encode($message);
        $channel_parent_list = getParentChannelRelationList($channel_id,'1');
        if($channel_parent_list!=''){
            $channel_parent_array = explode( ',' , $channel_parent_list );                    
            foreach ($channel_parent_array as $items){
                $channel_ar = channelGetInfo($items);
                if($channel_ar['owner_id']!=$user_id){
                    connectTochannel($user_id, $items);                            
                }
            }
        }
    }else{
        $message['error'] = _('Error connecting, please try again');
        echo json_encode($message);
    }

}