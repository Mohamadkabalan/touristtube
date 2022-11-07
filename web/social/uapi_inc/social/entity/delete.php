<?php

//	$entity_id = intval($_POST['entity_id']);
//	$entity_type = intval($_POST['entity_type']);
	$entity_id = intval($request->request->get('entity_id', 0));
	$entity_type = intval($request->request->get('entity_type', 0));
	
	if( socialEntityDelete($entity_type, $entity_id) ){
		$ret_arr['status'] = 'ok';
	}else{
		$ret_arr['error'] = 'ok';
		$ret_arr['error_msg'] = _('Couldn\'t delete. please try again later');
	}

?>
