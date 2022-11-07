<?php
/*! \file
 * 
 * \brief This api shows the suggested Tubers list of a user
 * 
 * 
 * @param S  session id
 * @param term  search string
 * @param section type of user ("blocked","ignored","removed","followers","followings","others")
 * 
 * @return <b>ret</b> List of Tuber information (array):
 * @return <pre> 
 * @return         <b>username</b> Tuber name
 * @return         <b>prof_pic</b> Tuber profile picture path
 * @return         <b>id</b> Tuber id
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
	
	$expath = "../";
        header('Content-type: application/json');
        include("../heart.php");
    
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$section = isset($_REQUEST['section']) ?  $_REQUEST['section'] : null;
//	$terms = isset($_REQUEST['term']) ? db_sanitize( strtolower($_REQUEST['term']) ) : null;
//	$userID = mobileIsLogged($_REQUEST['S']);
	$section = isset($submit_post_get['section']) ?  $submit_post_get['section'] : null;
	$terms = isset($submit_post_get['term']) ? strtolower($submit_post_get['term'])  : null;
	$userID = mobileIsLogged($submit_post_get['S']);
        if( !$userID ) die();
	$frnd_srch_options = array(
		'userid' => $userID,		
		'orderby' => 'YourUserName',
		'order' => 'a',
		'search_string' => $terms
	);
	
	$follow_srch_options = array(
		'userid' => $userID,		
		'orderby' => 'YourUserName',
		'order' => 'a',
		'search_string' => $terms
	);
	
	if($section == 'blocked'){
		$frnd_srch_options['type'] = array(2);
		$possible_users = userFriendSearch($frnd_srch_options);
	}else if($section == 'ignored'){
		$frnd_srch_options['type'] = array(3);
		$possible_users = userFriendSearch($frnd_srch_options);
	}else if($section == 'removed'){
		$frnd_srch_options['type'] = array(-1);
		$possible_users = userFriendSearch($frnd_srch_options);
	}else if($section == 'followers'){
		//my followers
		$follow_srch_options['reverse'] = false;
		$possible_users = userSubscriberSearch($follow_srch_options);
	}else if($section == 'followings'){
		//people im following
		$follow_srch_options['reverse'] = true;
		$possible_users = userSubscriberSearch($follow_srch_options);
	}else{
		//friends
		$frnd_srch_options['type'] = array(1);
		$possible_users = userFriendSearch($frnd_srch_options);
	}

	if( $possible_users == null ) die('');
	
	//$possible_users = array_s
	function cmp($a, $b) {
		$u1 = returnUserDisplayName($a,array('max_length' => null));
		$u2 = returnUserDisplayName($b,array('max_length' => null));
		
		return strcasecmp($u1, $u2);
	}
	uasort($possible_users, 'cmp');

        $ret = array();
	foreach($possible_users as $possible){
		$username = returnUserDisplayName($possible,array('max_length' => null));
                $ret[] = array(
                    'username'=>$username,
                    'prof_pic'=>"media/tubers/" .$possible['profile_Pic'],
                    'id'=>$possible['id']
                );
	}

	echo json_encode($ret);