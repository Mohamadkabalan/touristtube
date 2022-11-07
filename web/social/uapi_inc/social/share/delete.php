<?php
//	$id = intval($_POST['id']);
	$id = intval($request->request->get('id', 0));
	
	if( socialRateDelete($id, true) ){
		$ret_arr['status'] = 'ok';
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['error'] = _("Couldn't delete rating please try again later.");
	}
?>