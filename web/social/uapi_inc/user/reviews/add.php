<?php
//	$entity_type = intval($_POST['entity_type']);
//	$item_id = intval($_POST['item_id']);
//	$title = xss_sanitize($_POST['title']);
//	$description = xss_sanitize($_POST['description']);	
	$entity_type = intval($request->request->get('entity_type', 0));
	$item_id = intval($request->request->get('item_id', 0));
	$title = $request->request->get('title', '');
	$description = $request->request->get('description', '');
	$user_id = userGetID();
        if ( !userIsLogged() ) {
            $ret_arr['status'] = 'error';
            $ret_arr['msg'] = _('you need to have a').' <a class="black_link" href="'+ReturnLink('/register')+'">'.t("TT account").'</a> '.t("in order to write a review");
        }else{
            if( $review_id = userReviewsAdd( $user_id , $entity_type , $item_id , $title , $description ) ){
                $ret_arr['status'] = 'ok';
                $ret_arr['id'] = $review_id;
            }else{
                $ret_arr['status'] = 'error';
                $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
            }
        }
?>
