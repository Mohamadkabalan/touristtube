<?php
//	$id = intval($_POST['id']);
	$id = intval($request->request->get('id', 0));
        $userID = userGetID();
	$ppinfo = socialPostsInfo($id);
        
        if($ppinfo['from_id'] == $userID || $ppinfo['user_id']==$userID){
            if(socialPostsDelete($id)){
                $ret_arr['status'] = 'ok';
            }else{
                $ret_arr['result'] = 'error';
                $ret_arr['error'] = _("Couldn't delete post please try again later.");
            }
        }else{
            $ret_arr['result'] = 'error';
            $ret_arr['error'] = _('invalid data');
        }
?>