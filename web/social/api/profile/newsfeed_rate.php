<?php
/*! \file
 * 
 * \brief This api is used to rate any entity
 * 
 * 
 * @param S  session id
 * @param entity_id entity id
 * @param entity_type entity type
 * @param score rating
 * @param globchannelid channel id
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>error</b> any error
 * @return       <b>status</b> if not empty succeed
 * @return       <b>rating</b> average rating
 * @return       <b>nb_ratings</b> number of rating
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */


	$expath = "../";
        header('Content-type: application/json');
	include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
        
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();
        
        $userInfo = getUserInfo($user_id);
	
        $entity_id = intval($submit_post_get['entity_id']);
        $entity_type = $submit_post_get['entity_type'];
        $new_rate_value = intval($submit_post_get['score']);
        $globchannelid = intval($submit_post_get['globchannelid']);
	$rate_type = (isset($submit_post_get['rate_type']))?intval($submit_post_get['rate_type']):0;
	if( $entity_type != SOCIAL_ENTITY_HOTEL ) $rate_type =0;
	
        $newVal;

        $globchannelid = ($globchannelid) ? $globchannelid : 0;

        $old_rate = socialRateGet($user_id, $entity_id, $entity_type, $rate_type);

        $entity_info = socialEntityInfo($entity_type, $entity_id);
        if ($entity_info != null) {
            if (intval($entity_info['channel_id']) > 0)
                $globchannelid = $entity_info['channel_id'];
            else if (intval($entity_info['channelid']) > 0)
                $globchannelid = $entity_info['channelid'];
        }
        if ($old_rate == $new_rate_value) {

            //$ret['status'] = 'error';
            //$ret['error'] = _('already rated this');
            $ret['status'] = 'ok';
        } else if (socialRated($user_id, $entity_id, $entity_type, $rate_type) === false) {
            $ret['status'] = 'ok';
            $ret['type'] = 'add';
            $newVal = socialRateAdd($user_id, $entity_id, $entity_type, $new_rate_value, $globchannelid, $rate_type);
            $ret['rating'] = round($newVal['rating_value']);
        } else if ($newVal = socialRateEdit($user_id, $entity_id, $entity_type, $new_rate_value, $rate_type)) {
            $ret['status'] = 'ok';
            $ret['type'] = 'edit';
            $ret['rating'] = round($newVal['rating_value']);
        } else {
            $ret['status'] = 'error';
            $ret['error'] = _("Couldn't process rate. Please try again later");
        }

        echo json_encode($ret);
        exit;
