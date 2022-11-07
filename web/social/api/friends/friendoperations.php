<?php
/*! \file
 * 
 * \brief This api returns string if any operation done by a friend was successful or not
 * 
 * \todo <b><i>Change from comma seprated string to Json object</i></b>
 * 
 * @param S session id
 * @param rq requester id
 * @param w type of operation
 * @param msg message sent whenn adding new friend
 * 
 * @return String successfuly in case their is no error else failed
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */

	//w  = type of operation;
	//S  = As usual Session ID;
	//rq = requester_id Their ID(Lamin ID);
//	if (isset($_REQUEST['S']) && (isset($_REQUEST['rq'])) && (isset($_REQUEST['w'])) )
//	{
//		$rq = $_REQUEST['rq'];
//		$accept = $_REQUEST['w'];
$expath = "../";
include($expath."heart.php");
include($path."services/lib/chat.inc.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$result = array('status' => 'error');
	if (isset($submit_post_get['S']) && (isset($submit_post_get['rq'])) && (isset($submit_post_get['w'])) )
	{
		$rq = $submit_post_get['rq'];
		$accept = $submit_post_get['w'];
		
//		session_id($_REQUEST['S']); 
//        session_start();
		
		
		
//		$userID = $_SESSION['id'];
//                $userID = mobileIsLogged($_REQUEST['S']);
                $userID = mobileIsLogged($submit_post_get['S']);
                if( !$userID ) die();
                
		if ($accept == "accept")
		{
			if (userAcceptFriendRequest($userID,$rq))
			{
                            $result['status'] = 'success';
//				echo $accept." successfully";	
			}else
			{
                            $result['status'] = 'error';
//				echo $accept." failed";
			}
		}
		else if ($accept == "decline")
		{
			if (userRejectFriendRequest($userID,$rq))
			{
                            $result['status'] = 'success';
//				echo $accept." successfully";	
			}else
			{
                            $result['status'] = 'error';
//				echo $accept." failed";
			}
		}
		else if ($accept == "block")
		{
			
			if (userProfileBlockFriend($userID, $rq))
			{
                            $result['status'] = 'success';
//				echo $accept." successfully";	
			}else
			{
                            $result['status'] = 'error';
//				echo $accept." failed";
			}
			
		}
		else if ($accept == "unblock")
		{
			if (userProfileUnblockFriend($userID, $rq))
			{
                            $result['status'] = 'success';
//				echo $accept." successfully";	
			}else
			{
                            $result['status'] = 'error';
//				echo $accept." failed";
			}
		}else if ($accept == "ignore")
		{
			if (userIgnoreFriendRequest($userID, $rq))
			{
                            $result['status'] = 'success';
//				echo $accept." successfully";	
			}else
			{
                            $result['status'] = 'error';
//				echo $accept." failed";
			}
		}
		else if ($accept == "delete")
		{
			if (userDeleteFriend($userID,$rq))
			{
                            $result['status'] = 'success';
//				echo $accept." successfully";	
			}else
			{
                            $result['status'] = 'error';
//				echo $accept." failed";
			}
		}
		else if ($accept == "add")
		{
                    $msg="";
                    $msg_get = $request->query->get('msg','');
                    $friendStatus = userFreindRequestMade($userID, $rq);
                    if($userID == $rq){
                        $result['status'] = 'error';
//                        echo $accept." failed";
//                            $ret['status'] = 'error';
//                            $ret['msg'] = _('Can\'t friend yourself!');
                    }else if( $friendStatus == FRND_STAT_ACPT || $friendStatus == FRND_STAT_PENDING){
                        $result['status'] = 'error';
//                        echo $accept." failed";
//                            $ret['status'] = 'error';
//                            $ret['msg'] = _('Friend request already sent');
                    }else if( userAddFriend($userID, $rq, $msg) ){
                        $result['status'] = 'success';
//                        echo $accept." successfully";
//                            $ret['status'] = 'ok';
//                            $ret['msg'] = _('Friend request sent');
                    }else{
                        $result['status'] = 'error';
//                        echo $accept." failed";
//                            $ret['status'] = 'error';
//                            $ret['msg'] = _('Couldn\'t process friend request. Please Try again later');
                    }
//                    echo $userID.' '.$rq;exit;
//			$msg="";
//                        $msg_get = $request->query->get('msg','');
////			if (isset($_GET['msg']))
//			if ($msg_get)
//			{
////				$msg = $_GET['msg'];	
//				$msg = $msg_get;
//			}
//			if (intval($userID) != intval($rq) && userAddFriend($userID,$rq,$msg))
//			{
//				echo $accept." successfully";		
//			}else
//			{
//				echo $accept." failed";
//			}	
		}
	}
        echo json_encode($result);