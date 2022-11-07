<?php
//	$id = intval($_POST['id']);
	$id = intval($request->request->get('id', 0));
	$visited_places_array = userVisitedPlacesInfo($id);
	$user_id = userGetID();
	if($visited_places_array['user_id']!=$user_id){
		$ret_arr['status'] = 'error';
		$ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
	}else if( userVisitedPlacesDelete($id) ){
		$ret_arr['status'] = 'ok';
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
	}
?>