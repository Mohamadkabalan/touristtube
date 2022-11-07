<?php

$path = "../";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/users.php" );

$id = intval($request->request->get('id', 0));
$data_type = intval($request->request->get('data_type', 0));
$channel_id = intval($request->request->get('channel_id', 0));

$media_row = socialEntityInfo($data_type, $id);
$channel_info = channelGetInfo($channel_id);
$userid = userGetID();

$Result=array();
if($id>0 && $data_type>0 && $channel_info['owner_id']==$userid){
    if( AddDiscoverToChannel($id,$data_type,$channel_id) ){
        $Result['error'] = 0;
        $Result['msg'] = '';
    }else{
        $Result['error'] = 1;
        $Result['msg'] = _("Couldnt process. please try again later");
    }
} else {
    $Result['error'] = 1;
    $Result['msg'] = _("You are not the owner of this channel");
}
echo json_encode($Result);