<?php
//	$entity_type = intval($_POST['entity_type']);
//	$entity_id = intval($_POST['entity_id']);
//	$privacyValue = intval($_POST['privacyValue']);
//	$privacyArray = $_POST['privacyArray'];
	$entity_type = intval($request->request->get('entity_type', 0));
	$entity_id = intval($request->request->get('entity_id', 0));
	$privacyValue = intval($request->request->get('privacyValue', -1));
	$privacyArray = $request->request->get('privacyArray', '');
	
	
	$user_id = userGetID();	
	$ret_arr['status'] = 'ok';
	
	$users_ids = array();
        if(intval($user_id)==0){
            $ret_arr['status'] = 'error';
            $ret_arr['error'] = _('Your session timed out. Please login.');
            return;
        }
	$privacy_kind = array();
	if($privacyValue==USER_PRIVACY_SELECTED){
            foreach($privacyArray as $privacy_with){
                if( isset($privacy_with['id']) ){			
                    $users_id = intval( $privacy_with['id'] );
                    if (!in_array($users_id, $users_ids)) {
                            $users_ids[] = $users_id;
                    }	

                }

            }
	}else{
            $privacy_kind[] = $privacyValue;
	}
	if(sizeof($privacy_kind)>=2){
            $users_ids = array();	
	}
	$users_ids_str=join(",",$users_ids);
	$privacy_kind_str=join(",",$privacy_kind);
	if($privacyValue!=-1){
            if($privacy_kind_str=='' && sizeof($privacy_kind)>1){
                $privacy_kind_str=USER_PRIVACY_SELECTED;
            }
            if(sizeof($users_ids)>0 && $privacy_kind_str==''){
                $privacy_kind_str=USER_PRIVACY_SELECTED;	
            }
            userPrivacyExtandEdit(array('user_id'=>$user_id,'entity_type'=>$entity_type,'entity_id'=>$entity_id,'kind_type'=>$privacy_kind_str,'users'=>$users_ids_str));	
	}
?>
