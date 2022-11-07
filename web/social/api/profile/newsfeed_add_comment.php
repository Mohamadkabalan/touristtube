<?php
/*! \file
 * 
 * \brief This api returns status of adding a comment on any newsfeed
 * 
 * @param S session id
 * @param entity_id entity id
 * @param entity_type entity type
 * @param comment comment added
 * @param channel_id channel id
 * @param comments_user_array comments user array
 * 
 * @return <b>ret_arr</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>status</b> status if succeded or not
 * @return       <b>msg</b>  error message
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
$expath = "../";
header('Content-type: application/json');
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$user_id = mobileIsLogged($_REQUEST['S']);
$user_id = mobileIsLogged($submit_post_get['S']);
if( !$user_id ) die();

//$entity_id = intval($_REQUEST['entity_id']);
//$entity_type = intval($_REQUEST['entity_type']);
//$comment = xss_sanitize($_REQUEST['comment']);
//$channel_id = isset($_REQUEST['channel_id']) ? intval($_REQUEST['channel_id']) : null;
//$comments_user_array = isset($_REQUEST['comments_user_array']) ? $_REQUEST['comments_user_array'] : null;

$entity_id = intval($submit_post_get['entity_id']);
$entity_type = intval($submit_post_get['entity_type']);
$comment = $submit_post_get['comment'];
$channel_id = isset($submit_post_get['channel_id']) ? intval($submit_post_get['channel_id']) : 0;
$comments_user_array = isset($submit_post_get['comments_user_array']) ? $submit_post_get['comments_user_array'] : null;

$entity_info = socialEntityInfo($entity_type, $entity_id);
if( $entity_info != null){
    if(intval($entity_info['channel_id'])>0) $channel_id = $entity_info['channel_id'];
    else if(intval($entity_info['channelid'])>0) $channel_id = $entity_info['channelid'];
}

if($comment_id = socialCommentAdd($user_id, $entity_id, $entity_type, $comment, $channel_id) ){
    $ret_arr['status'] = 'ok';
    $ret_arr['comment_id'] = $comment_id;
    if( is_array($comments_user_array) ){
        global $CONFIG_EXEPT_ARRAY;
        $exept_array_sep = $CONFIG_EXEPT_ARRAY;
        foreach($comments_user_array as $comments_user){
            newsfeedAdd($comments_user, $comment_id, SOCIAL_ACTION_COMMENT, $entity_id, $entity_type , USER_PRIVACY_PRIVATE,null);
            if( in_array( $entity_type , $exept_array_sep ) ){
                $owner_id =0;
            }else{
                $owner_id = socialEntityOwner($entity_type, $entity_id );
            }
            if($owner_id!=$comments_user){
                $data_notification=getUserNotifications($comments_user);
                $data_notification=$data_notification[0];
                $send_mail = true;
                if(!$data_notification['email_notifications']){
                    $send_mail = false;
                }	
                $userInfo = getUserInfo($comments_user);
                if( $userInfo['otherEmail'] !=''){
                    $to_email = $userInfo['otherEmail'];
                }else{
                    $to_email = $userInfo['YourEmail'];
                }
                global $dbConn;
                $params = array();  
//                $query = "SELECT notify FROM cms_notifications_emails WHERE email='".$to_email."' LIMIT 1";
                $query = "SELECT notify FROM cms_notifications_emails WHERE email=:To_email LIMIT 1";
                $params[] = array(  "key" => ":To_email",
                                    "value" =>$to_email);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
//                $res = db_query($query);

                $ret    = $select->rowCount();
//                if( $res && db_num_rows($res) != 0){
                if( $res && $ret!= 0){
//                    $row = db_fetch_assoc($res);
                    $row = $select->fetch(PDO::FETCH_ASSOC);
                    $notify = ($row['notify'] == 1);
                    if( !$notify ) $send_mail = false;
                }
                if( !$data_notification['email_mentionedcomment'] ) $send_mail = false;
                if($send_mail){
                    $global_link= currentServerURL().'';
                    $tuber_array = array();
                    $from_userInfo = getUserInfo($user_id);
                    $suser_link = $global_link.''.userProfileLink($from_userInfo,1);
                    $send_arr=array(ReturnLink('media/tubers/' . $from_userInfo['profile_Pic']),returnUserDisplayName($from_userInfo,array('max_length'=>15)),$friend_array_count,$followers_array_count , returnUserDisplayName($from_userInfo) , $suser_link );
                    array_push( $tuber_array , $send_arr );
                    $globArray['comment'] = array();
					
					/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
                    //$FullName = htmlEntityDecode($userInfo['FullName']);
					$FullName = returnUserDisplayName($userInfo);
					/*code changed by sushma mishra on 30-sep-2015 ends here*/ 
					
                    $globArray['ownerName'] = $FullName;
                    $case = 1;
                    $txtdisplay='<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b"><a href="'.$tuber_array[0][5].'" target="_blank">'.$tuber_array[0][4].'</a></font><font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> mentioned you in a comment: </font>';
                    $case_val_array = array();
                    $case_val_array['case'] = $case;
                    $case_val_array['friends'] = $tuber_array;				
                    $case_val_array['partTitle'] = $txtdisplay;
                    $case_val_array['friendComment'] = htmlEntityDecode($comment);

                    $globArray['comment'][] = $case_val_array;

                    $socialLikes_count = getSocialCount($comments_user,SOCIAL_ACTION_LIKE);
                    $socialComments_count = getSocialCount($comments_user,SOCIAL_ACTION_COMMENT);
                    $socialShares_count = getSocialCount($comments_user,SOCIAL_ACTION_SHARE);
                    $socialRates_count = getSocialCount($comments_user,SOCIAL_ACTION_RATE);


                    $photosViews = getImageViews($comments_user);
                    $videoViews = getVideoViews($comments_user);
                    $albumViews = getUserAlbumViews($comments_user);

                    $socialArray = array();
                    $socialArray['views'] = ($photosViews+$videoViews+$albumViews);
                    $socialArray['likes'] = $socialLikes_count;
                    $socialArray['comments'] = $socialComments_count;
                    $socialArray['shares'] = $socialShares_count;
                    $socialArray['rating'] = $socialRates_count;
                    $socialArray['rateImg'] = '';

                    $not_settings= $global_link.''.ReturnLink('TT-confirmation/TSettings/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
                    $unsubscribe_lnk=$global_link.''.ReturnLink('unsubscribe/emails/'.md5($userInfo['id'].''.$userInfo['YourEmail']));

                    displayProfileMentionedEmail( $to_email , $globArray , $socialArray , $unsubscribe_lnk , $not_settings , '' );
                }
            }
        }
    }
}else{
    $ret_arr['status'] = 'error';
    $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
}
echo json_encode($ret_arr);
?>
