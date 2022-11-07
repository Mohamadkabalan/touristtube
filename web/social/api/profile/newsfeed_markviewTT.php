<?php
/*! \file
 * 
 * \brief This api is used to unlike any entity
 * 
 * 
 * @param S  session id
 * @param entity_type entity type
 * @param channel_id channel id
 * @param entity_id entity id
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>error</b> any error
 * @return       <b>status</b> if not empty event succeed
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

/*
* Returns the events sponsored by a given channel.
* Param S: The session id.
* Param [limit]: Optional, the max rows to get, default 100.
* Param [page]: Optional, the current page.
* Param [fromdate]: Optional, from date.
* Param [todate]: Optional, to date.
*/
	
//	session_id($_REQUEST['S']);
//	session_start();
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	include("../heart.php");
        $submit_post_get = array_merge($request->query->all(),$request->request->all());

        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();
        $type = $submit_post_get['type'];
        switch($type){
            case 'ids':
                $ids = $submit_post_get['ids'];
                $ids_arrays = explode(',', $ids);
                foreach($ids_arrays as $ids_array){
                    $res = newsfeedMarkAsViewedTT ($ids_array);
                }
                break;
            case 'channel':
                $channel_id = $submit_post_get['channel_id'];
                $res = newsfeedMarkAsViewed($channel_id, $user_id);
                break;
            case 'tuber':
                $res = newsfeedMarkAsViewedAllTT($user_id);
                break;
            case 'echoe':
                $res = newsfeedMarkAsViewedAllTT($user_id, true);
                break;
        }
        if($res){
                $Result['status'] = 'ok';
        }else{
            $Result['status'] = 'error';
        }
	echo json_encode( $Result );