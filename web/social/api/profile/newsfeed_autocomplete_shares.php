<?php
/*! \file
 * 
 * \brief This api is used to search for friend
 * 
 * 
 * @param term  searched term
 * @param ds don't show
 * @param cid current channel id
 * @param S session id
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>user_pic</b> user picture path
 * @return       <b>username</b> user name
 * @return       <b>user_id</b> user id
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
        
//	$terms = isset($_REQUEST['term']) ? db_sanitize($_REQUEST['term']) : null;
//	$dont_show = isset($_GET['ds']) ? db_sanitize($_GET['ds']) : null;
	$terms = $request->query->get('term',null);
	$dont_show = $request->query->get('ds',null);
	
	// Get the current channel id to show only the connections if the user is a channel or is the owner of this channel.
//	$current_channel_id = isset($_GET['cid']) ? db_sanitize($_GET['cid']) : null;
	$current_channel_id = $request->query->get('cid',null);
	
	// Get if the user is a channel.
	$user_is_channel = userIsChannel();
	$user_is_channel = ($user_is_channel) ? 1 : 0;
	
	// Get if the user owns this channel.
	$channelInfo = channelFromID($current_channel_id);
//	$user_id = userGetID();
//        $user_id = mobileIsLogged($_REQUEST['S']);
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();

	$is_owner=1;
	if($user_id != intval($channelInfo['owner_id'])){
		$is_owner=0;
	}
	
	
	if($terms == null) die('2');
	$terms = strtolower(trim($terms));
	$terms = remove_accents($terms);	
	
	
	// If the user is the owner if the channel or the user is a channel show only the connections.
	if($is_owner == 1 || $user_is_channel == 1){
		// Get the list of connections (ttubers).
		$options = array(
                                'channelid' => $current_channel_id,
                                'is_visible'=>1,
                                'begins' => $terms,
                                'dont_show' => $dont_show
                                );
		$possible_users = channelConnectedTubersSearch($options);
	
	} else {
		$options = array(
                        'userid' => $user_id,
                        'limit' => 20,
                        'type' => 1,
                        'begins' => $terms,
                        'dont_show' => $dont_show
                );
                $friends_res = userFriendSearch($options);
               
                foreach($friends_res as $friends):
                        if (!in_array($friends['id'], $possible_users_in)) {
                            $friends['uid']=$friends['id'];
                                $possible_users[] = $friends;
                                $possible_users_in[] = $friends['id'];
                        }
                endforeach;
                $options = array(
                        'userid' => $user_id,
                        'limit' => 20,
                        'reverse' => false,
                        'begins' => $terms,
                        'dont_show' => $dont_show
                );
                $followers_res = userSubscriberSearch($options);

                foreach($followers_res as $followers):
                        if (!in_array($followers['UID'], $possible_users_in)) {
                            $followers['uid']=$followers['UID'];
                            $possible_users[] = $followers;                                
                            $possible_users_in[] = $followers['UID'];
                        }
                endforeach;
	}
	
	if( $possible_users != null ) $changed = true;
	
	//$possible_users = array_s
	function cmp($a, $b) {
		$u1 = returnUserDisplayName($a,array('max_length' => null));
		$u2 = returnUserDisplayName($b,array('max_length' => null));

		return strcasecmp($u1, $u2);
	}
	uasort($possible_users, 'cmp');
        $res = array();
	if( $changed ){
		$i = 0;
		foreach($possible_users as $possible){
			$username = returnUserDisplayName($possible,array('max_length' => null));

			/*
			$dkey = '<div class="peoplesearch"><img src="'. ReturnLink('media/tubers/' . $possible['profile_Pic']) .'" class="autocompletImage" alt="'.$username.'"/><div class="peoplesearchtext">'.$username.'</div></div>';
			$ret[$i]['label'] = $dkey;
			$ret[$i]['username'] = $username;
			$ret[$i]['user_id'] = $possible['uid'];
			$i++;
                         */
                        $res[]=array(
                            'user_pic' => ReturnLink('media/tubers/' . $possible['profile_Pic']),
                            'username'=> $username,
                            'user_id' => $possible['uid'],

                        );
		}

		echo json_encode($res);
	}else{
		die('');
	}