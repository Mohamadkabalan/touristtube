<?php

/**
 * User Functionality that deals with mostly with the cms_users tables and affiliated tables<br/>
 * such as cms_users_catalogs
 * @package users
 */
/**
 * The size of a user's profile pic thumb
 */
define('THUMB_SIZE', 230);
/**
 * the object is private  
 */
define('USER_PRIVACY_PRIVATE', 0);
/**
 * the object can be shared with friends
 */
define('USER_PRIVACY_COMMUNITY', 1);
/**
 * the object can be shared with the public
 */
define('USER_PRIVACY_PUBLIC', 2);
/**
 * the object can be shaed with the friends of friends
 */
define('USER_PRIVACY_COMMUNITY_EXTENDED', 3);
/**
 * the object will be shared with custom
 */
define('USER_PRIVACY_SELECTED', 4);
/**
 * the object will be shared with followers
 */
define('USER_PRIVACY_FOLLOWERS', 5);
/**
 * constants for web referrer
 */
define('WEB_REFERRER', 1);

/**
 * check if the email address is valid.
 * @param string $email
 * @return true|false if set or not
 */
function check_email_address($email) {
    // First, we check that there's one @ symbol,
    // and that the lengths are right.
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // Email invalid because wrong number of characters 
        // in one section or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if
        (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
â†ª'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }
    // Check if domain is IP. If not, 
    // it should be valid domain name
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if
            (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
â†ª([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}

/**
 * check if the permission has been set.
 * @param integer $user_id the mcs_users record id
 * @param string $which_permission the cms_users col name that is protected
 * @return true|false if set or not
 */
function userPermissionIsset($user_id, $which_permission) {    
    global $dbConn;
    $params = array(); 

    $query = "SELECT permission_value FROM cms_users_permissions WHERE user_id=:User_Id AND permission_type=:Which_Permission";  
    $params[] = array( "key" => ":User_Id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Which_Permission",
                        "value" =>$which_permission);      
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
                      
    if (!$res || ($ret == 0))
        return false;
    else
        return true;
}

/**
 * gets a user permission
 * @param integer $user_id the mcs_users record id
 * @param string $which_permission the cms_users col name that is protected
 * @return false|integer false if not set else the value: USER_PRIVACY_PRIVATE, USER_PRIVACY_COMMUNITY, USER_PRIVACY_PUBLIC
 */
function userPermissionGet($user_id, $which_permission) {
    global $dbConn;
    $params = array();  
    
    $query = "SELECT permission_value FROM cms_users_permissions WHERE user_id=:User_Id AND permission_type=:Which_Permission";  
    $params[] = array( "key" => ":User_Id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Which_Permission",
                        "value" =>$which_permission);                             
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
 
                      
    if (!$res || ($ret == 0))
        return 9999;
    else{
        $result = $select->fetch();
        return $result[0];
    }
}

/**
 * user set permission. TODO complete in_array after george addds all permissions
 * @param string $which_permission the cms_users col name that is protected
 * @param integer $permission_value USER_PRIVACY_PRIVATE, USER_PRIVACY_COMMUNITY, USER_PRIVACY_PUBLIC etc ..
 * @return true|false if sucess or fail
 */
function userPermissionSet($user_id, $which_permission, $permission_value) { 
    global $dbConn;
    $params = array(); 
    if ($permission_value > USER_PRIVACY_SELECTED)
        $permission_value = 0;
    $set = userPermissionIsset($user_id, $which_permission);
    if (!$set) {
        $query = "INSERT INTO cms_users_permissions (user_id,permission_type,permission_value) VALUES (:User_Id,:Which_Permission,:Permission_Value)";
    } else {
        $query = "UPDATE cms_users_permissions SET permission_value=:Permission_Value WHERE  user_id=:User_Id AND permission_type=:Which_Permission";
    }
    $params[] = array( "key" => ":User_Id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Which_Permission",
                        "value" =>$which_permission);
    $params[] = array( "key" => ":Permission_Value",
                        "value" =>$permission_value);
    $insert_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert_update,$params);
    $res   = $insert_update->execute();
    return $res;
}

/**
 * converts a fullname to a display name
 * @param array $userInfo the cms_uesrs record
 * @param array $display_options options include:<br/>
 * <b>max_length</b> the maximum length of the resulting string. default 15.
 * return string
 */
function returnUserDisplayName($userInfo, $display_options = null) {
    $default_options = array(
        'max_length' => null
    );
    if (!is_null($display_options))
        $options = array_merge($default_options, $display_options);
    else
        $options = $default_options;

    if ($userInfo['display_fullname'] == 1) {
        $fullname = htmlEntityDecode($userInfo['FullName']);
        $fullname = htmlspecialchars($fullname);
        if (strlen($fullname) <= 1) {
            $fullname = $userInfo['YourUserName'];
        }
    } else {
        $fullname = $userInfo['YourUserName'];
    }
    if ((!is_null($options['max_length'])) && (strlen($fullname) > $options['max_length'])) {
        $fullname = substr($fullname, 0, $options['max_length']) . '...';
    }
    return $fullname;
}

/*
 *   Core functions that manage users
 */

function getUserInfo($id) {
//  Changed by Anthony Malak 15-04-2015 to PDO database

    global $dbConn;
    $params = array();
    
    $getUserInfo    = tt_global_get('getUserInfo');
    if(isset($getUserInfo[$id]) && $getUserInfo[$id]!=''){
        //return $getUserInfo[$id];
    }
    //$q_user_info = "SELECT * FROM `cms_users` WHERE `id` = '$id' and published=1 LIMIT 1";
    $q_user_info = "SELECT * FROM `cms_users` WHERE `id` = :Id and published=1 LIMIT 1";
    $params[] = array( "key" => ":Id", "value" =>$id);
    
    $select = $dbConn->prepare($q_user_info);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $result = $select->fetch();
    $row    = $select->rowCount();
    
//    if (db_num_rows($r_user_info)) {
//        $row_user_info = db_fetch_assoc($r_user_info);
//        $row_user_info['profile_empty_pic'] = 0;
//        if (strlen($row_user_info['profile_Pic']) == 0) {
//            $row_user_info['profile_Pic'] = 'he.jpg';
//            $row_user_info['profile_empty_pic'] = 1;
//            if ($row_user_info['gender'] == 'F') {
//                $row_user_info['profile_Pic'] = 'she.jpg';
//            }
//        }
//     if($row_user_info['profile_id']==0) $row_user_info['profile_empty_pic'] = 1;
//     return $row_user_info;
//  
    
    if ($res && $row>0) {
        $row_user_info = $result;
        $row_user_info['profile_empty_pic'] = 0;
        if ( $row_user_info['profile_Pic'] == '' ) {
            $row_user_info['profile_Pic'] = 'he.jpg';
            $row_user_info['profile_empty_pic'] = 1;
            if ($row_user_info['gender'] == 'F') {
                $row_user_info['profile_Pic'] = 'she.jpg';
            }
        }
        if($row_user_info['profile_id']==0) $row_user_info['profile_empty_pic'] = 1;
        $getUserInfo[$id]   =   $row_user_info;
        return $row_user_info;
    }
    $getUserInfo[$id]   =   false;
    return false;
//  Chanegd by Anthony Malak 15-04-2015 to PDO database

}

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function getUserVideosCount($id, $options = array()) {
//
//    $status_constraint = "";
//    if (isset($options ['published']))
//        $status_constraint = " AND published = '" . $options ['published'] . "'";
//
//    $q_user_videos = "SELECT COUNT(id) AS nb_videos FROM `cms_videos` where userid = '$id' " . $status_constraint;
//    $r_user_videos = db_query($q_user_videos);
//    $row_user_videos = db_fetch_assoc($r_user_videos);
//
//    return $row_user_videos['nb_videos'];
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * suggest a username to the user given his firstname, lastname, email
 * @param string $fname user's firstname
 * @param string $lname user's last name
 * @param string $email user's email
 * @return string
 */
function suggestUserName($fname, $lname, $email) {
    list($email_user) = explode('@', $email, 1);
    $try_usernames = array($fname . '.' . $lname, ucfirst($fname) . ucfirst($lname), $email_user);

    foreach ($try_usernames as $try_username) {
        $try_username_san = preg_replace('/[^a-z0-9A-Z\.]/', '', $try_username);

        if (userNameisUnique(-1, $try_username_san)) {
            return $try_username_san;
        }
    }

    //no unqiue username so far try appending random numbers
    foreach ($try_usernames as $try_username) {
        $try_username_san = preg_replace('/[^a-z0-9A-Z\.]/', '', $try_username);
        $try_username_san = rand(10, 100);
        if (userNameisUnique(-1, $try_username_san)) {
            return $try_username_san;
        }
    }
}

function suggestUserNameNew($username) {
    $username = strtolower($username);
    $db_user_arr = array();
    $i = 1;
    $k = 0;
    while ($i < 1000000) {
        $new_username = $username . '' . $i;
        if (userNameisUnique(-1, $new_username)) {
            array_push($db_user_arr, $new_username);
            $k++;
        }
        if ($k >= 3)
            break;
        $i++;
    }
    return $db_user_arr;
}

/**
 * Gets a users statistics
 * @param integer $userID the user to query
 * @return array a hash of the user's statistics
 */
function userGetStatistics($userID,$case=0) {
    //TODO put all these in cms_users table as shortcuts and update as necessary
    $final_ret = array();
    if($case==1){
        $srch_optionsnew = array(
            'type' => array(1),
            'userid' => $userID,
            'is_visible' => -1,
            'n_results' => true
        );
        $final_ret['nFriends'] = userFriendSearch($srch_optionsnew);
        $srch_optionsnew = array(
            'userid' => $userID,
            'is_visible' => -1,
            'reverse' => false,
            'n_results' => true
        );
        $final_ret['nFollowers'] = userSubscriberSearch($srch_optionsnew);
        $srch_optionsnew = array(
            'userid' => $userID,
            'is_visible' => -1,
            'reverse' => true,
            'n_results' => true
        );
        $final_ret['nFollowings'] = userSubscriberSearch($srch_optionsnew);
    }else{
        if($case==2){
            $srch_optionsnew = array(
                'type' => array(1),
                'userid' => $userID,
                'is_visible' => -1,
                'n_results' => true
            );
            $final_ret['nFriends'] = userFriendSearch($srch_optionsnew);
            $srch_optionsnew = array(
                'userid' => $userID,
                'is_visible' => -1,
                'reverse' => false,
                'n_results' => true
            );
            $final_ret['nFollowers'] = userSubscriberSearch($srch_optionsnew);
            $srch_optionsnew = array(
                'userid' => $userID,
                'is_visible' => -1,
                'reverse' => true,
                'n_results' => true
            );
            $final_ret['nFollowings'] = userSubscriberSearch($srch_optionsnew);
        }
//      Changed by Anthony Malak 15-04-2015 to PDO database
//      <start>
        global $dbConn;
	$params  = array(); 
	$params2 = array(); 
	$params3 = array(); 
	$params4 = array(); 
	$params5 = array(); 
	$params6 = array(); 
	$params7 = array(); 
        
//        $query = "SELECT COUNT(id) FROM cms_videos WHERE userid='$userID' AND published=1 AND type NOT LIKE 'image/%' AND channelid=0";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
//        $final_ret['nVideos'] = $row[0];
        
        $query1  = "SELECT COUNT(id) FROM cms_videos WHERE userid=:UserID AND published=1 AND type NOT LIKE 'image/%' AND channelid=0";
	$params[] = array( "key" => ":UserID",
                            "value" =>$userID);
        $select1 = $dbConn->prepare($query1);
	PDO_BIND_PARAM($select1,$params);
        $res     = $select1 ->execute();
        $row     = $select1->fetch();
        $final_ret['nVideos'] = $row[0];
        
//        $query = "SELECT COUNT(id) FROM cms_videos WHERE userid='$userID' AND published=1 AND type LIKE 'image/%' AND channelid=0";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
//        $final_ret['nImages'] = $row[0];
        
        $query2  = "SELECT COUNT(id) FROM cms_videos WHERE userid=:UserID AND published=1 AND type LIKE 'image/%' AND channelid=0";
	$params2[] = array( "key" => ":UserID",
                             "value" =>$userID);
        $select2 = $dbConn->prepare($query2);
	PDO_BIND_PARAM($select2,$params2);
        $res2    = $select2 ->execute();
        $row     = $select2->fetch();
        $final_ret['nImages'] = $row[0];
        
//        $query = "SELECT COUNT(id) FROM cms_users_catalogs WHERE user_id='$userID' AND published=1 AND channelid=0";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
//        $final_ret['nCatalogs'] = $row[0];
        
        $query3  = "SELECT COUNT(id) FROM cms_users_catalogs WHERE user_id=:UserID AND published=1 AND channelid=0";
	$params3[] = array( "key" => ":UserID",
                             "value" =>$userID);
        $select3 = $dbConn->prepare($query3);
	PDO_BIND_PARAM($select3,$params3);
        $res3    = $select3 ->execute();
        $row     = $select3->fetch();
        $final_ret['nCatalogs'] = $row[0];

        
//        $query = "SELECT profile_views FROM cms_users WHERE id='$userID' AND published=1";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
//        $final_ret['nViews'] = $row[0];
        
        $query4  = "SELECT profile_views FROM cms_users WHERE id=:UserID AND published=1";
	$params4[] = array( "key" => ":UserID",
                             "value" =>$userID);
        $select4 = $dbConn->prepare($query4);
	PDO_BIND_PARAM($select4,$params4);
        $res4    = $select4 ->execute();
        $row     = $select4->fetch();
        $final_ret['nViews'] = $row[0];

        /*$query = "SELECT SUM(up_votes) FROM cms_videos WHERE userid='$userID' AND published=1 AND channelid=0";
        $ret = db_query($query);
        $row = db_fetch_array($ret);
        $final_ret['nLikes'] = $row[0];*/

//        $query = "SELECT SUM(nb_views) FROM cms_videos WHERE userid='$userID' AND image_video='v' AND published=1 AND channelid=0";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
//        $final_ret['nVideoViews'] = $row[0];
        
        $query5  = "SELECT SUM(nb_views) FROM cms_videos WHERE userid=:UserID AND image_video='v' AND published=1 AND channelid=0";
	$params5[] = array( "key" => ":UserID",
                             "value" =>$userID);
        $select5 = $dbConn->prepare($query5);
	PDO_BIND_PARAM($select5,$params5);
        $res5    = $select5 ->execute();
        $row     = $select5->fetch();
        $final_ret['nVideoViews'] = $row[0];

//        $query = "SELECT SUM(nb_views) FROM cms_videos WHERE userid='$userID' AND image_video='i' AND published=1 AND channelid=0";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
//        $final_ret['nPhotoViews'] = $row[0];
        
        $query6  = "SELECT SUM(nb_views) FROM cms_videos WHERE userid=:UserID AND image_video='i' AND published=1 AND channelid=0";
	$params6[] = array( "key" => ":UserID",
                             "value" =>$userID);
        $select6 = $dbConn->prepare($query6);
	PDO_BIND_PARAM($select6,$params6);
        $res6    = $select6 ->execute();
        $row     = $select6->fetch();
        $final_ret['nPhotoViews'] = $row[0];

        $final_ret['nMediaViews'] = $final_ret['nVideoViews'] + $final_ret['nPhotoViews'];

//        $query = "SELECT COUNT(subscriber_id) FROM cms_subscriptions WHERE user_id='$userID'";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
//        $final_ret['nSubscribers'] = $row[0];
        
        $query7  = "SELECT COUNT(subscriber_id) FROM cms_subscriptions WHERE published = 1 AND user_id=:UserID";
	$params7[] = array( "key" => ":UserID",
                             "value" =>$userID);
        $select7 = $dbConn->prepare($query7);
	PDO_BIND_PARAM($select7,$params7);
        $res     = $select7 ->execute();
        $row     = $select7->fetch();
        $final_ret['nSubscribers'] = $row[0];

        //TODO re-add this as a column in the users table
        $srch_optionsnew = array(
            'published' => 1,
            'user_id' => $userID,
            'types' => array(SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_WEBCAM),
            'n_results' => true
        );
        $final_ret['nFavorites'] = socialFavoritesGet($srch_optionsnew);
    }
    
    return $final_ret;
}

/**
 * Checks if a user is logged in
 * @return boolean true|false if loggedin|not logeed in 
 */
function userIsLogged() {
    global $request;
    if(tt_global_isset('isLogged')){
        return tt_global_get('isLogged');
    } 
    $lt_cookie = $request->cookies->get('lt','');
//    if(!isset($_COOKIE['lt']) || empty($_COOKIE['lt'])){
    if(!isset($lt_cookie) || empty($lt_cookie)){
        tt_global_set('isLogged', false);
        return false;
    }
//    $login_token = $_COOKIE['lt'];
    $login_token = $lt_cookie;
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_tubers WHERE uid = :Login_Token AND expiry_date > NOW()";
    $params[] = array( "key" => ":Login_Token",
                        "value" =>$login_token);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch();
        userSetSession(NULL, $row['user_id']);
        if($row['keep_me_logged'] == "1"){
            $query2 = "UPDATE cms_tubers SET expiry_date = DATE_ADD(NOW(), INTERVAL 7 DAY) WHERE id=:Id";
            $params2[] = array( "key" => ":Id",
                        "value" =>$row['id']);
            $update = $dbConn->prepare($query2);
            PDO_BIND_PARAM($update, $params2);
            $update->execute();
        }
        tt_global_set('isLogged', true);
        return true;
    } else {
        tt_global_set('isLogged', false);
        return false;
    }
}
/**
 * logs the user out 
 */
function userLogout() {
    global $request, $CONFIG;
    $lt_cookie =$request->cookies->get('lt', '');

//    $login_token = $_COOKIE['lt'];
    $login_token = $lt_cookie;
    
//  Changed by Anthony Malak 15-04-2015 to PDO database

//    $query = "DELETE FROM cms_tubers WHERE uid = '$login_token'";
//    db_query($query);
    global $dbConn;
    $params = array();  
    $query   = "DELETE FROM cms_tubers WHERE uid = :Login_Token";
    $params[] = array( "key" => ":Login_Token",
                        "value" =>$login_token);
    $delete  = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);  
    $delete->execute();
//  Changed by Anthony Malak 15-04-2015 to PDO database

    
    $pathcookie = '/';
    setcookie("lt", '', time()-3600, $pathcookie, $CONFIG['cookie_path']);
    userEndSession(session_id());
    unset($_SESSION);
    session_destroy();
}

/**
 * Gets the id of the logged in user
 * @return mixed either the id of the logged in user or false if not logged in 
 */
function userGetID() {
    if(!userIsLogged()){
        return false;
    }
    if(!tt_global_isset('userInfo')){
        return false;
    }
    $userInfo = tt_global_get('userInfo');
    return intval($userInfo['id']);
}

/**
 * Gets the if the user is exclusivley a channel user
 * @return boolean true|false if is channel or not
 */
function userIsChannel() {
    if(!tt_global_isset('userInfo')){
        return false;
    }
    $userInfo = tt_global_get('userInfo');
    return $userInfo['isChannel'] == 1;
}

/**
 * gets the logged user's profile pic
 * @return string
 */
function userGetProfilePic() {
    if (!userIsLogged()){
        return null;
    }
    else{
        $userInfo = tt_global_get('userInfo');
        return $userInfo['profile_Pic'];
    }
}


/**
 *  Gets the username of the logged in user
 *  @return mixed either the username of the logged in user or false if not logged in 
 */
function userGetName($display_options = array( 'max_length' => 17 ) ) {
    $userInfo = tt_global_get('userInfo');    
    return returnUserDisplayName($userInfo,$display_options);
}

/**
 * sets the logged in user's name
 * @param string $new_name the new username
 */
function userSetName($new_name) {
    //$_SESSION['YourUserName'] = $new_name;
}

/**
 * Subscribes one user to another
 * @param integer $user_id the user subscribing
 * @param integer $subscribeto the user being subscribed to
 * @return boolean true if subscribed false if already subscribed
 */
function userSubscribe($user_id, $subscribeto) {
    global $dbConn;
    $params  = array();
    $params2 = array();
    
    $query  = "SELECT * FROM cms_subscriptions where published = 1 AND user_id=:Subscribeto AND subscriber_id=:User_id";
    $params[] = array( "key" => ":Subscribeto", "value" =>$subscribeto);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret    = $select->rowCount();
    if ($res && ($ret == 1))
        return false;
    
    if (userNotifyOnSubscribe($subscribeto)) {
        emailSubscription($subscribeto, $user_id);
    }
    addPushNotification(SOCIAL_ACTION_FOLLOW, $subscribeto, $user_id);
            
    $query2  = "SELECT * FROM cms_subscriptions where published = -2 AND user_id=:Subscribeto AND subscriber_id=:User_id";
    $select2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($select2,$params);
    $res2    = $select2->execute();
    $ret2    = $select2->rowCount();
    $row2    = $select2->fetch(PDO::FETCH_ASSOC);
    if($ret2 == 1){
        $update_query  = "UPDATE cms_subscriptions SET published = 1 where published = -2 AND user_id=:Subscribeto AND subscriber_id=:User_id";
        $params2[] = array( "key" => ":Subscribeto", "value" =>$subscribeto);
        $params2[] = array( "key" => ":User_id", "value" =>$user_id);
        $update = $dbConn->prepare($update_query);
        PDO_BIND_PARAM($update,$params2);
        $res3    = $update->execute();
        $ret3    = $update->rowCount();

        $in_id = $row2['subscription_id'];
        if (!$res)
            return false;

        newsfeedAdd($user_id, $in_id, SOCIAL_ACTION_FOLLOW, $subscribeto, SOCIAL_ENTITY_USER, USER_PRIVACY_PUBLIC, NULL);
        return true; 
    }else{        
        $query  = "INSERT INTO cms_subscriptions (user_id,subscriber_id) VALUES (:Subscribeto, :User_id)";
        $params2[] = array( "key" => ":Subscribeto", "value" =>$subscribeto);
        $params2[] = array( "key" => ":User_id", "value" =>$user_id);
        $insert = $dbConn->prepare($query);
        PDO_BIND_PARAM($insert,$params2);
        $res4 = $insert->execute();
        $ret4   = $insert->rowCount();

        $in_id = $dbConn->lastInsertId();
        if (!$res)
            return false;

        newsfeedAdd($user_id, $in_id, SOCIAL_ACTION_FOLLOW, $subscribeto, SOCIAL_ENTITY_USER, USER_PRIVACY_PUBLIC, NULL);
        return true;    
    }
}

/**
 * checks if a user is subscribed to another
 * @param integer $user_id
 * @param integer $subscribeto
 * @return boolean returns true|false if user is subscribed or not.
 */
function userSubscribed($user_id, $subscribeto) {
//  Changed by Anthony Malak 15-04-2015 to PDO database

//    $query = "SELECT * FROM cms_subscriptions where user_id='$subscribeto' AND subscriber_id='$user_id'";
//    $ret = db_query($query);
    
    global $dbConn;
    $params = array(); 
    $query  = "SELECT * FROM cms_subscriptions where published = 1 AND user_id=:Subscribeto AND subscriber_id=:User_id";
    $params[] = array( "key" => ":Subscribeto", "value" =>$subscribeto);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    
//    if (db_num_rows($ret) == 1)
    if ($ret == 1)
        return true;
    else
        return false; 
//  Changed by Anthony Malak 15-04-2015 to PDO database

}

/**
 * gets a list of a users followers
 * @param integer the user who we want to get his followers 
 * @return array the list of users followers (could be empty list)
 */
function userFollowersList($user_id) {

//  Changed by Anthony Malak 15-04-2015 to PDO database

//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic
//			FROM
//				cms_subscriptions AS F
//				INNER JOIN cms_users AS U ON U.id=F.subscriber_id
//			WHERE
//				F.user_id=$user_id 
//			ORDER BY YourUserName ASC
//			";
//    $ret_arr = array();
//    $ret = db_query($query);
//    
//    if (!$ret || db_num_rows($ret) == 0)
//        return array();
//
//    while ($row = db_fetch_array($ret)) {
//        $ret_arr[] = $row;
//    }
    global $dbConn;
    $params = array();  
    $query = "SELECT U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic 
              FROM cms_subscriptions AS F INNER JOIN cms_users AS U ON U.id=F.subscriber_id
	      WHERE F.published = 1 AND F.user_id=:User_id ORDER BY YourUserName ASC";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $ret_arr = array();
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    $row    = $select->fetchAll();
    
    if (!$select || $ret == 0)
        return array(); 

    return $row;
//  Changed by Anthony Malak 15-04-2015 to PDO database

}

/**
 * gets a list of a users followings
 * @param integer the user who we want to get his followings 
 * @return array the list of users followings (could be empty list)
 */
function userSubscribedList($user_id) {
//  Changed by Anthony Malak 15-04-2015 to PDO database

//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic
//			FROM
//				cms_subscriptions AS F
//				INNER JOIN cms_users AS U ON U.id=F.user_id
//			WHERE
//				F.subscriber_id=$user_id 
//			ORDER BY YourUserName ASC
//			";
//    $ret_arr = array();
//    $ret = db_query($query);
//
//    if (!$ret || db_num_rows($ret) == 0)
//        return array();
//
//    while ($row = db_fetch_array($ret)) {
//        $ret_arr[] = $row;
//    }
//    return $ret_arr;
    global $dbConn;
    $userSubscribedList    = tt_global_get('userSubscribedList'); //Added by Devendra
    if(isset($userSubscribedList[$user_id]) && $userSubscribedList[$user_id]!=''){
        return $userSubscribedList[$user_id];
    }
    $params = array();  
    
    $query = "SELECT
				U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic
			FROM
				cms_subscriptions AS F
				INNER JOIN cms_users AS U ON U.id=F.user_id
			WHERE
				 F.published = 1 AND F.subscriber_id=:User_id 
			ORDER BY YourUserName ASC";
    $ret_arr = array();
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res2   = $select->execute();

    $ret    = $select->rowCount();
    $row    = $select->fetchAll();
    
    if (!$res2 || $ret== 0){
         $userSubscribedList[$user_id]  =   array();
        return array();
    }else{
        $userSubscribedList[$user_id]  =   $row;
        return $row;
    }
        
//  Changed by Anthony Malak 15-04-2015 to PDO database

}

/**
 * Unsubscribe from another user
 * @param integer $user_id user wanting to unscubscribe
 * @param integer $unscubscribefrom unsubscribe from this user
 * @return boolean true|false depending on the success of the operation
 */
function userUnsubscribe($user_id, $unscubscribefrom) {
    global $dbConn;
    $params  = array();  
    $params2 = array(); 
    $query_feed = "SELECT subscription_id FROM cms_subscriptions where published = 1 AND user_id=:Unscubscribefrom AND subscriber_id=:User_id LIMIT 1";
    $params[] = array( "key" => ":Unscubscribefrom",
                        "value" =>$unscubscribefrom);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query_feed);
    PDO_BIND_PARAM($select,$params);
    $res  = $select->execute();

    $ret    = $select->rowCount();

    if ($res && ($ret == 1)) {
        $row    = $select->fetch();
        newsfeedDeleteByAction($row['subscription_id'], SOCIAL_ACTION_FOLLOW);
    }
    $query = "UPDATE cms_subscriptions SET published = -2 where published = 1 AND user_id=:Unscubscribefrom AND subscriber_id=:User_id";
    $params2[] = array( "key" => ":Unscubscribefrom",
                        "value" =>$unscubscribefrom);
    $params2[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params2);
    $res  = $delete->execute();
    newsfeedAdd($user_id, $unscubscribefrom, SOCIAL_ACTION_UNFOLLOW, $unscubscribefrom, SOCIAL_ENTITY_USER, USER_PRIVACY_PUBLIC, null);

    if($res > 0){
        return $res;
    }
    else{
        return false;
    }
//  Changed by Anthony Malak 15-04-2015 to PDO database

}

/**
 * searchs for friends of friends. options include:<br/>
 * <b>limit</b>: integer - limit of record to return. default 6<br/>
 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
 * <b>userid</b>: integer - the user to search for his friends of friends.<br/>
 * <b>begins</b>: user names begin with this letter <br/>
 * <b>unique</b>: 1 return all friends of friends that are not friends else 0 return all  <br/>
 * <b>orderby</b>: string - the cms_users column to order the results by. default YourUserName<br/>
 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * @param array $srch_options search options
 * @return array of result records
 */
function userFriendsOfFriendsSearch($srch_options) {
    global $dbConn;
    $params  = array();  
    $params2 = array();  
    $default_opts = array(
        'limit' => 6,
        'page' => 0,
        'unique' => 1,
        'userid' => null,
        'begins' => null,
        'orderby' => 'YourUserName',
        'order' => 'a',
        'dont_show' => 0,
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    $orderby = $options['orderby'];
    $order='';

    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $user_id = $options['userid'];
    if (is_null($user_id)) {
        return array();
    }
    $where = '';

    if (!is_null($options['userid'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " F.requester_id={$options['userid']} ";
        $where .= " F.requester_id=:Userid ";
	$params[] = array( "key" => ":Userid",
                            "value" =>$options['userid']);
    }
    if ($options["dont_show"] != 0) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " U.id NOT IN (" . $options["dont_show"] . ") ";
        $where .= " NOT find_in_set(cast(U.id as char), :Dont_show) ";
	$params[] = array( "key" => ":Dont_show", "value" =>$options['dont_show']);
    }
    if (userIsLogged()) {
        $searcher_id = userGetID();
        $friends = userGetFreindList($searcher_id);

        $friends_ids = array($searcher_id);
        foreach ($friends as $freind) {
            $friends_ids[] = $freind['id'];
        }
        if (count($friends_ids) != 0) {
            if ($where != '')
                $where .= " AND ";
            $public = USER_PRIVACY_PUBLIC;
            $private = USER_PRIVACY_PRIVATE;
            $selected = USER_PRIVACY_SELECTED;
            $community = USER_PRIVACY_COMMUNITY;
            $privacy_where = '';

            $where .= "CASE";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
            $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
            
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Private AND PR.user_id= :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
            
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public", "value" =>$public);
            $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);            
            $params[] = array( "key" => ":Private", "value" =>$private);
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
//        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Public LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public",
                                "value" =>$public);
    }
    if (!is_null($options['begins'])) {
        if ($options['begins'] == '#') {
            if ($where != '')
                $where = " ( $where ) AND ";
            $where .= "( U.display_fullname=0 AND LOWER(U.YourUserName) REGEXP  '^[1-9]' )";
            //user names cant have numbers
        }else {
            if ($where != '')
                $where = " ( $where ) AND ";
//             $where .= "( 
//							(
//								U.display_fullname=0
//								AND
//								LOWER(U.YourUserName) LIKE '{$options['begins']}%'
//							)
//							OR
//							(
//								U.display_fullname=1
//								AND
//								(
//									LOWER(U.fname) LIKE '{$options['begins']}%' 
//									OR
//									LOWER(U.FullName) LIKE '{$options['begins']}%'
//								)
//							)
//						)";
            $where .= "( 
							(
								U.display_fullname=0
								AND
								LOWER(U.YourUserName) LIKE :Begins
							)
							OR
							(
								U.display_fullname=1
								AND
								(
									LOWER(U.fname) LIKE :Begins
									OR
									LOWER(U.FullName) LIKE :Begins
								)
							)
						)";
            $params[] = array( "key" => ":Begins", "value" =>$options['begins']."%");
        }
    }
    $frnd_accept = FRND_STAT_ACPT;
    $where .=' AND U.published=1 AND U.isChannel=0 ';
    if ($options['n_results'] == false) {
//        $query = "SELECT
//					U.*, F.*
//			FROM
//				cms_friends AS F
//				INNER JOIN cms_friends AS F2 ON F2.requester_id=F.receipient_id AND F2.status='$frnd_accept'
//				INNER JOIN cms_users AS U ON F2.receipient_id=U.id
//			WHERE $where AND F.status='$frnd_accept' AND U.id<>{$options['userid']}";
        $query = "SELECT
					U.*, F.*
			FROM
				cms_friends AS F
				INNER JOIN cms_friends AS F2 ON F2.published=1 AND F2.requester_id=F.receipient_id AND F2.status= :Frnd_accept
				INNER JOIN cms_users AS U ON F2.receipient_id=U.id
			WHERE $where AND F.status= :Frnd_accept AND U.id<>:Userid";
            $params[] = array( "key" => ":Frnd_accept",
                                "value" =>$frnd_accept);
            $params[] = array( "key" => ":Userid",
                                "value" =>$options['userid']);
        if (intval($options['unique']) == 1) {
//            $query .= " AND U.id NOT IN (SELECT receipient_id FROM cms_friends WHERE  requester_id={$options['userid']})";
            $query .= " AND U.id NOT IN (SELECT receipient_id FROM cms_friends WHERE published=1 AND requester_id=:Userid2)";
            $params[] = array( "key" => ":Userid2",
                                "value" =>$options['userid']);
        }
//        $query .= " GROUP BY U.id ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query .= " GROUP BY U.id ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
            $params[] = array( "key" => ":Skip",
                                "value" =>$skip,
                                "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit",
                                "value" =>$nlimit,
                                "type" =>"::PARAM_INT");

                   
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        $row    = $select->fetchAll();
//        $ret = db_query($query);

//        while ($res = db_fetch_array($ret)) {
//            if ($row['profile_Pic'] == '') {
//                //$row['profile_Pic'] = 'tuber.jpg';
//                $row['profile_Pic'] = 'he.jpg';
//                if ($row['gender'] == 'F') {
//                    $row['profile_Pic'] = 'she.jpg';
//                }
//            }
//
//            $media[] = $row;
//        }
            $media = array();
            foreach($row as $row_item){
                if( $row_item['profile_Pic'] == ''){
                    $row_item['profile_Pic'] = 'he.jpg';
                    if ( $row_item['gender'] == 'F') {
                        $row_item['profile_Pic'] = 'she.jpg';
                    }
                }
                $media[] = $row_item;
            }

        return $media;
    } else {
//        $query = "SELECT
//				COUNT(DISTINCT U.id)
//				FROM
//				cms_friends AS F
//				INNER JOIN cms_friends AS F2 ON F2.requester_id=F.receipient_id AND F2.status='$frnd_accept'
//				INNER JOIN cms_users AS U ON F2.receipient_id=U.id
//			WHERE $where AND F.status='$frnd_accept' AND U.id<>{$options['userid']}";
        $query = "SELECT
				COUNT(DISTINCT U.id)
				FROM
				cms_friends AS F
				INNER JOIN cms_friends AS F2 ON F2.published=1 AND F2.requester_id=F.receipient_id AND F2.status=:Frnd_accept
				INNER JOIN cms_users AS U ON F2.receipient_id=U.id
			WHERE $where AND F.status=:Frnd_accept AND U.id<>:Userid";
            $params[] = array( "key" => ":Frnd_accept",
                                "value" =>$frnd_accept);
            $params[] = array( "key" => ":Userid",
                                "value" =>$options['userid']);
                        
        if (intval($options['unique']) == 1) {
//            $query .= " AND U.id NOT IN (SELECT receipient_id FROM cms_friends WHERE  requester_id={$options['userid']})";
            $query .= " AND U.id NOT IN (SELECT receipient_id FROM cms_friends WHERE published=1 AND requester_id=:Userid2)";
            $params[] = array( "key" => ":Userid2",
                                "value" =>$options['userid']);
        }
        
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
        $row    = $select->fetch();
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);

        return $row[0];
//  Changed by Anthony Malak 15-04-2015 to PDO database

    }
}

/**
 * gets a list of friends of friends to a tuber
 * @param integer $user_id the user record
 * @return array the friends of friends id
 */
function getFriendsOfFriendsList($user_id) {
    global $dbConn;
    $params = array();  
    $frnd_accept = FRND_STAT_ACPT;
    $query = "SELECT F2.receipient_id FROM cms_friends AS F INNER JOIN cms_friends AS F2 ON F2.published=1 AND F2.requester_id=F.receipient_id AND F2.status=:Frnd_accept				
			WHERE F.published=1 AND F.requester_id=:User_id AND F.status=:Frnd_accept GROUP BY F2.receipient_id";
    $params[] = array( "key" => ":Frnd_accept", "value" =>$frnd_accept);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    
    $ret = $select->rowCount();

    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = array();
        $row     = $select->fetchAll();

        foreach($row as $row_item){
            $ret_arr[] = $row_item[0];
        }
        return $ret_arr;
    }
}

/**
 * searchs for tubers a tuber is subscribed to (following). options include:<br/>
 * <b>limit</b>: integer - limit of record to return. default 6<br/>
 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
 * <b>userid</b>: integer - the user to search for. required<br/>
 * <b>begins</b>: user names begin with this letter <br/>
 * <b>orderby</b>: string - the cms_friends column to order the results by. default request_ts<br/>
 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
 * <b>revrese</b>: boolean. if true gets the users that are follwing the passed userid. default false<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * <b>from_ts</b>: start date default null<br/>
 * <b>to_ts</b>: end date default null<br/>
 * @param array $srch_options search options
 * @return array of result records
 */
function userSubscriberSearch($srch_options) {
//  Changed by Anthony Malak 15-04-2015 to PDO database

    global $dbConn;
    $params = array();  
    $default_opts = array(
        'limit' => 6,
        'page' => 0,
        'userid' => null,
        'begins' => null,
        'from_ts' => null,
        'to_ts' => null,
        'distinct_user' => 0,
        'escape_user' => null,
        'is_visible' => -1,
        'search_string' => null,
        'orderby' => 'subscription_date',
        'order' => 'a',
        'dont_show' => 0,
        'reverse' => false,
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = '';
    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
    }

    $orderby = $options['orderby'];
    $order='';

    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $user_id = $options['userid'];
    if (is_null($user_id)) {
        return array();
    }

    $where = '';

    if ($where != '')
        $where .= ' AND ';
    if ($options['reverse'] == false) {
        $where .= "F.user_id=:User_id"; //find the users that are floowing me
        $params[] = array( "key" => ":User_id", "value" =>$options['userid']);
        if(!is_null($options['escape_user'])){
            if( $where != '') $where .= " AND ";
//            $where .= " F.subscriber_id NOT IN({$options['escape_user']}) ";
            $where .= " NOT find_in_set(cast(F.subscriber_id as char), :Escape_user) ";
            $params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
            }
    } else {
        $where .= "F.subscriber_id=:Subscriber_id"; //find the users I am following
        $params[] = array( "key" => ":Subscriber_id", "value" =>$options['userid']);
        if(!is_null($options['escape_user'])){
            if( $where != '') $where .= " AND ";
//            $where .= " F.user_id NOT IN({$options['escape_user']}) ";
            $where .= " NOT find_in_set(cast(F.user_id as char), :Escape_user) ";
            $params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
        }
    }

    if ($options["dont_show"] != 0) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " U.id NOT IN (" . $options["dont_show"] . ") ";
        $where .= " NOT find_in_set(cast(U.id as char), :Dont_show) ";
        $params[] = array( "key" => ":Dont_show", "value" =>$options['dont_show']);
    }
    if ($options['is_visible'] != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " F.is_visible='{$options['is_visible']}' ";
        $where .= " F.is_visible=:Is_visible ";
        $params[] = array( "key" => ":Is_visible", "value" =>$options['is_visible']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(F.subscription_date) >= '{$options['from_ts']}' ";
        $where .= " DATE(F.subscription_date) >= :From_ts ";
        $params[] = array( "key" => ":From_ts", "value" =>$options['from_ts']);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(F.subscription_date) <= '{$options['to_ts']}' ";
        $where .= " DATE(F.subscription_date) <= :To_ts ";
        $params[] = array( "key" => ":To_ts", "value" =>$options['to_ts']);
    }

    if (!is_null($options['begins'])) {
        if ($options['begins'] == '#') {
            if ($where != '')
                $where = " ( $where ) AND ";
            $where .= "( U.display_fullname=0 AND LOWER(U.YourUserName) REGEXP  '^[1-9]' )";
            //user names cant have numbers
        }else {
            if ($where != '')
                $where = " ( $where ) AND ";
//             $where .= "( 
//							(
//								U.display_fullname=0
//								AND
//								LOWER(U.YourUserName) LIKE '{$options['begins']}%'
//							)
//							OR
//							(
//								U.display_fullname=1
//								AND
//								(
//									LOWER(U.fname) LIKE '{$options['begins']}%' 
//									OR
//									LOWER(U.FullName) LIKE '{$options['begins']}%'
//								)
//							)
//						)";
            $where .= "( 
							(
								U.display_fullname=0
								AND
								LOWER(U.YourUserName) LIKE :Begins
							)
							OR
							(
								U.display_fullname=1
								AND
								(
									LOWER(U.fname) LIKE :Begins 
									OR
									LOWER(U.FullName) LIKE :Begins
								)
							)
						)";
            /* only search in firstname
             * OR
              LOWER(U.lname) LIKE '{$options['begins']}%'

             */
        $params[] = array( "key" => ":Begins", "value" =>$options['begins']."%");
        }
    }

    if (!is_null($options['search_string'])) {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '')
            $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "( 
                    (
                        U.display_fullname=0
                        AND
                        LOWER(U.YourUserName) LIKE :Search_string_loop$i
                    )
                    OR
                    (
                        U.display_fullname=1
                        AND
                        (
                            LOWER(U.fname) LIKE :Search_string_loop$i
                            OR
                            LOWER(U.lname) LIKE :Search_string_loop$i
                            OR
                            LOWER(U.FullName) LIKE :Search_string_loop$i
                        )
                    )
            )";
            $params[] = array( "key" => ":Search_string_loop$i", "value" =>'%'.$search_string_loop.'%');
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";       
    }

    if ($options['reverse'] == false) {
        $subscribe = " F.published = 1 AND F.subscriber_id=U.id AND U.published=1 ";
    } else {
        $subscribe = " F.published = 1 AND F.user_id=U.id AND U.published=1 ";
    }

    if ($options['n_results'] == false) {        
        if( $options['distinct_user']==1 ){            
            $query = "SELECT U.*,F.*, U.id AS UID FROM cms_subscriptions AS F INNER JOIN cms_users AS U ON $subscribe WHERE $where GROUP BY U.id ORDER BY $orderby $order";
        }else{
            $query = "SELECT U.*,F.*, U.id AS UID FROM cms_subscriptions AS F INNER JOIN cms_users AS U ON $subscribe WHERE $where ORDER BY $orderby $order";
        }
        if (!is_null($options['limit'])) {
//            $query .= " LIMIT $skip, $nlimit";
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        }

//        $ret = db_query($query);
//        $media = array();
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
        $row    = $select->fetchAll();

//        while ($row = db_fetch_array($ret)) {
//            if ($row['profile_Pic'] == '') {
//                //$row['profile_Pic'] = 'tuber.jpg';
//                $row['profile_Pic'] = 'he.jpg';
//                if ($row['gender'] == 'F') {
//                    $row['profile_Pic'] = 'she.jpg';
//                }
//            }
//
//            $media[] = $row;
//        }        
        $media = array();
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $media[] = $row_item;
        }

        return $media;
    } else {        
        if( $options['distinct_user']==1 ){
            $query = "SELECT COUNT(DISTINCT U.id) FROM cms_subscriptions AS F INNER JOIN cms_users AS U ON $subscribe WHERE $where";
        }else{
            $query = "SELECT COUNT(F.subscription_id) FROM cms_subscriptions AS F INNER JOIN cms_users AS U ON $subscribe WHERE $where";
        }
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
        $row    = $select->fetch();

        return $row[0];    
//  Changed by Anthony Malak 15-04-2015 to PDO database

    }
}

/**
 * checks if a users sent a friend request for other
 * @param integer $user_id the current user 
 * @param integer $friend_id the second user
 * @return boolean true|false if friends or not
 */
function sentRequestOccur($user_id, $friend_id) {
    
//  Changed by Anthony Malak 15-04-2015 to PDO database

//    $query = "SELECT * FROM
//                            cms_friends AS F
//                            INNER JOIN cms_users AS U 
//			WHERE
//                            U.id=F.receipient_id AND
//                            F.requester_id=$user_id AND
//                            F.receipient_id=$friend_id AND
//                            F.status=" . FRND_STAT_PENDING;
//    $res = db_query($query);
//    if ($res && ( db_num_rows($res) != 0 )) {
//        return true;
//    } else {
//        return false;
//    }  
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM
                            cms_friends AS F
                            INNER JOIN cms_users AS U 
			WHERE
                            F.published=1 AND
                            U.id=F.receipient_id AND
                            F.requester_id=:User_id AND
                            F.receipient_id=:Friend_id AND
                            F.status=" . FRND_STAT_PENDING;
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Friend_id",
                        "value" =>$friend_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret  != 0 )) {
        return true;
    } else {
        return false;
    }  
//  Changed by Anthony Malak 15-04-2015 to PDO database

}

/**
 * get count of followers not follow back and versal screw
 * @param intiger $user_id
 * @return count of followers not follow back and versal screw
 */
function getFollowersNotFollowings($user_id, $reverse) {
//  Changed by Anthony Malak 15-04-2015 to PDO database

//    if ($reverse == false) {
//        $where = " F1.user_id='$user_id' AND F1.subscriber_id  NOT IN (SELECT F2.user_id FROM cms_subscriptions as F2 WHERE F2.subscriber_id='$user_id') ";
//    } else {
//        $where = " F1.subscriber_id='$user_id' AND F1.user_id  NOT IN (SELECT F2.subscriber_id FROM cms_subscriptions as F2 WHERE F2.user_id='$user_id') ";
//    }
//    $query = "SELECT COUNT(F1.subscription_id) FROM cms_subscriptions AS F1 WHERE $where";
//
//    $ret = db_query($query);
//    $row = db_fetch_row($ret);
//
//    return $row[0];
    global $dbConn;
    $params = array();
    if ($reverse == false) {
        $where = " F1.published = 1 AND F1.user_id=:User_id AND F1.subscriber_id  NOT IN (SELECT F2.user_id FROM cms_subscriptions as F2 WHERE F2.published = 1 AND F2.subscriber_id=:User_id) ";
    } else {
        $where = " F1.published = 1 AND F1.subscriber_id=:User_id AND F1.user_id  NOT IN (SELECT F2.subscriber_id FROM cms_subscriptions as F2 WHERE F2.published = 1 AND F2.user_id=:User_id) ";
    }
    $query = "SELECT COUNT(F1.subscription_id) FROM cms_subscriptions AS F1 WHERE $where";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);

    $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
    $select->execute();

    $row    = $select->fetch();

    return $row[0];
//  Changed by Anthony Malak 15-04-2015 to PDO database

}

/**
 * get count of followings who follow back and not friends
 * @param intiger $user_id
 * @return count of followings who follow back and not friends
 */
function getFollowingsFollowersNotFriends($user_id) {
//  Changed by Anthony Malak 15-04-2015 to PDO database

//    $where = " F1.subscriber_id='$user_id' AND F1.user_id IN (SELECT F2.subscriber_id FROM cms_subscriptions as F2 WHERE F2.user_id='$user_id')  AND "
//            . "F1.user_id NOT IN (SELECT F2.receipient_id FROM cms_friends as F2 WHERE F2.requester_id='$user_id') ";
//
//    $query = "SELECT COUNT(F1.subscription_id) FROM cms_subscriptions AS F1 WHERE $where";
//
//    $ret = db_query($query);
//    $row = db_fetch_row($ret);
//
//    return $row[0];
    global $dbConn;
    $params = array();
    $where = " F1.published = 1 AND F1.subscriber_id=:User_id AND F1.user_id IN (SELECT F2.subscriber_id FROM cms_subscriptions as F2 WHERE F2.published = 1 AND F2.user_id=:User_id)  AND "
            . "F1.user_id NOT IN (SELECT F2.receipient_id FROM cms_friends as F2 WHERE F2.published=1 AND F2.published=1 AND F2.requester_id=:User_id) ";

    $query = "SELECT COUNT(F1.subscription_id) FROM cms_subscriptions AS F1 WHERE $where";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $row    = $select->fetch();

    return $row[0];
//  Changed by Anthony Malak 15-04-2015 to PDO database

}

/**
 * edits a  user subscriptions info
 * @param array $new_info the new cms_subscriptions info
 * @return boolean true|false if success|fail
 */
function userSubscriptionsEdit($new_info) {
    global $dbConn;
    $params = array();
    global $dbConn;
    $params = array();
    
    $query = "UPDATE cms_subscriptions SET ";
    foreach ($new_info as $key => $val) {
        if ($key != 'user_id' && $key != 'subscriber_id') {
            $query .= " $key = :Val".$i.",";
            $params[] = array( "key" => ":Val".$i,
                                "value" =>$val);
        }
    }
    $query = trim($query, ',');
    $query .= " WHERE published = 1 AND user_id=:User_id AND  subscriber_id= :Subscriber_Id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$new_info['user_id']);
    $params[] = array( "key" => ":Subscriber_Id",
                        "value" =>$new_info['subscriber_id']);
    
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    
    return ( $res ) ? true : false;
}

/**
 * new freind request 
 */
define('FRND_STAT_NEW', 0);
/**
 * pending friendrequest
 */
define('FRND_STAT_PENDING', 2);
/**
 * friend request accepted 
 */
define('FRND_STAT_ACPT', 1);
/**
 * fiend request rejected by receipient
 */
define('FRND_STAT_RJCT', -1);
/**
 * friend blocked by receipient 
 */
define('FRND_STAT_BLOCK_BYREC', -2);
/**
 * fiend blocked by requester 
 */
define('FRND_STAT_BLOCK_BYREQ', -3);
/**
 * friend blocked both ways 
 */
define('FRND_STAT_BLOCK_BYBOTH', -4);
/**
 * new freind ignore
 */
define('FRND_STAT_IGNORE', -5);

define('FRND_STAT_REJECT_REQ', -6);
/**
 * tuber not friend blocked
 */
define('FRND_STAT_BLOCK_TUBER', -7);

/**
 * checkes a freind request was made
 * @param integer $user_id the requester
 * @param integer $friend_id the receipient
 * @return false|status if request was made the status else false
 */
function userFreindRequestMade($user_id, $friend_id) {


//    $query = "SELECT status FROM cms_friends WHERE requester_id=$user_id AND receipient_id=$friend_id AND status<>" . FRND_STAT_BLOCK_TUBER;
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0))
//        return false;
//
//    $row = db_fetch_array($ret);
//    return $row[0];
    global $dbConn;
    $params = array();
    $query = "SELECT status FROM cms_friends WHERE published=1 AND requester_id=:User_id AND receipient_id=:Friend_Id AND status<>" . FRND_STAT_BLOCK_TUBER;
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Friend_Id", "value" =>$friend_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0))
        return false;

    $row    = $select->fetch();
    return $row[0];


}

/**
 * gets the number of friend a user has
 * @param integer $user_id 
 * @return integer the number of friends
 */
function userFriendNumber($user_id) {


//    $query = "SELECT COUNT(requester_id) FROM cms_friends WHERE requester_id='$user_id' AND status=" . FRND_STAT_ACPT;
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0))
//        return 0;
//    else {
//        $row = db_fetch_array($res);
//        return $row[0];
//    }
    global $dbConn;
    $params = array();
    $query = "SELECT COUNT(requester_id) FROM cms_friends WHERE published=1 AND requester_id=:User_id AND status=" . FRND_STAT_ACPT;
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0))
        return 0;
    else {
        $row    = $select->fetch();
        return $row[0];
    }


}

/**
 * makes a freind request. if a reverse request was already made then accpet it
 * @param integer $user_id the user adding a freind
 * @param integer $friend_id the freind
 * @param string a small request message
 * @return boolean true|false if success|fail
 */
function userAddFriend($user_id, $friend_id, $msg='') {
    global $dbConn;
    $params  = array();
    $params2 = array();
    $params3 = array();
    $params4 = array();
    $params5 = array();
    $params6 = array();
    $params7 = array();
    $status = userFreindRequestMade($user_id, $friend_id);
    
    if (($status == FRND_STAT_RJCT) || ($status == FRND_STAT_IGNORE)) {
        $query1 = "UPDATE cms_friends SET status=" . FRND_STAT_ACPT . " WHERE published=1 AND requester_id=:User_id AND receipient_id=:Friend_id";
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Friend_id", "value" =>$friend_id);
        $update1 = $dbConn->prepare($query1);
	PDO_BIND_PARAM($update1,$params);
        $update1->execute();
        $query2 = "UPDATE cms_friends SET status=" . FRND_STAT_ACPT . " WHERE published=1 AND requester_id=:Friend_id AND receipient_id=:User_id";
	$params2[] = array( "key" => ":User_id", "value" =>$user_id);
	$params2[] = array( "key" => ":Friend_id", "value" =>$friend_id);
        $update2 = $dbConn->prepare($query2);
	PDO_BIND_PARAM($update2,$params2);
        $res     = $update2->execute();
        return $res;
    }

    //try user_id as receipient and $friend_id as requester

    $status = userFreindRequestMade($friend_id, $user_id);
    
    if ($status != false) {
        $query1 = "UPDATE cms_friends SET status=" . FRND_STAT_ACPT . " WHERE published=1 AND requester_id=:User_id AND receipient_id=:Friend_id";
	$params3[] = array( "key" => ":User_id", "value" =>$user_id);
	$params3[] = array( "key" => ":Friend_id", "value" =>$friend_id);
        $update1 = $dbConn->prepare($query1);
	PDO_BIND_PARAM($update1,$params3);
        $update1->execute();
        $query2 = "UPDATE cms_friends SET status=" . FRND_STAT_ACPT . " WHERE published=1 AND requester_id=:Friend_id AND receipient_id=:User_id";
	$params4[] = array( "key" => ":User_id",
                             "value" =>$user_id);
	$params4[] = array( "key" => ":Friend_id",
                             "value" =>$friend_id);
        $update2 = $dbConn->prepare($query2);
	PDO_BIND_PARAM($update2,$params4);
        $res    = $update2->execute();
        return $res;
    }
    $delete_query = "DELETE FROM cms_friends WHERE (requester_id=:User_id AND receipient_id=:Friend_id) OR (requester_id=:Friend_id AND receipient_id=:User_id)";
    $params7[] = array( "key" => ":User_id", "value" =>$user_id);
    $params7[] = array( "key" => ":Friend_id", "value" =>$friend_id);
    $delete = $dbConn->prepare($delete_query);
    PDO_BIND_PARAM($delete,$params7);
    $res = $delete->execute();
    
    $query1 = "INSERT INTO cms_friends (requester_id,receipient_id,request_msg,status,blocked,notify) VALUES (:User_id,:Friend_id,:Msg," . FRND_STAT_PENDING . ",0,0)";
    $params5[] = array( "key" => ":User_id", "value" =>$user_id);
    $params5[] = array( "key" => ":Friend_id", "value" =>$friend_id);
    $params5[] = array( "key" => ":Msg", "value" =>$msg);
    $insert1 = $dbConn->prepare($query1);
    PDO_BIND_PARAM($insert1,$params5);
    $insert1->execute();
    $query2 = "INSERT INTO cms_friends (requester_id,receipient_id,request_msg,status,blocked,notify) VALUES (:Friend_id,:User_id,:Msg," . FRND_STAT_NEW . ",0,0)";
    $params6[] = array( "key" => ":User_id", "value" =>$user_id);
    $params6[] = array( "key" => ":Friend_id", "value" =>$friend_id);
    $params6[] = array( "key" => ":Msg", "value" =>$msg);
    $insert2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($insert2,$params6);
    $res = $insert2->execute();

    if ($res) {
        $userNotificationsArray = getUserNotifications($friend_id);
        $userNotificationsArray = $userNotificationsArray[0];

        if ($userNotificationsArray['tuber_friendrequest'] == 1 || count($userNotificationsArray) == 0) {
            //emailFreind($friend_id, $user_id);
            $db_insert_id = $dbConn->lastInsertId();
            $uinfo = getUserInfo($user_id);
            $finfo = getUserInfo($friend_id);
            $global_link= currentServerURL().'';
            $FullName = returnUserDisplayName($finfo);
            $globArray = array();
            $case_val_array = array();
            $globArray['invite'] = array();
            $globArray['ownerName'] = $FullName;
            $globArray['activateLink'] = ReturnLink('TT-confirmation/TFriendship/'.md5($friend_id.''.$user_id));
            $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> wants to be friends with you on touristtube</font>';
            $suser_link = userProfileLink($uinfo,1);
            $case_val_array['friends'] = array("0"=>array(ReturnLink('media/tubers/' . $uinfo['profile_Pic']), returnUserDisplayName($uinfo) ,"","","",$suser_link) );            
            if( $finfo['otherEmail'] !=''){
                $to_email = $finfo['otherEmail'];
            }else{
                $to_email = $finfo['YourEmail'];
            }
            $globArray['invite'][] = $case_val_array;
            $subject = "Someone wants to be friends with you on touristtube";
            displayEmailTuberFriendRequest( $to_email , $subject , $globArray , '' , '' , '' );
            addPushNotification(SOCIAL_ACTION_FRIEND_REQUEST, $friend_id, $user_id);
        }
        return true;
    } else {
        return false;
    }
}

/**
 * gets the number of pending friend requests
 * @param integer $user_id the cms_users id 
 * @return integer the number of friend requests
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function userGetFriendRequests($user_id) {
//    $query = "SELECT COUNT(requester_id) FROM cms_friends WHERE receipient_id=$user_id AND status=" . FRND_STAT_NEW;
//    $ret = db_query($query);
//    if (!$ret || db_num_rows($ret) == 0) {
//        return 0;
//    } else {
//        $row = db_fetch_array($ret);
//        return intval($row[0]);
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * accepts a freind request
 * @param integer $user_id the user accepting a friend request
 * @param integer $requester_id the person who made the originalrequest
 * @return boolean true|false if success|fail
 */
function userAcceptFriendRequest($user_id, $requester_id) {
    global $dbConn;
    $params  = array();  
    $params2 = array();
    $frnd_accpt = FRND_STAT_ACPT;
    $query1 = "UPDATE cms_friends SET status=$frnd_accpt WHERE published=1 AND requester_id=:Requester_id AND receipient_id=:User_id AND status=" . FRND_STAT_PENDING;
    $params[] = array( "key" => ":Requester_id", "value" =>$requester_id);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $update1 = $dbConn->prepare($query1);
    PDO_BIND_PARAM($update1,$params);
    $update1->execute();
    
    $query2 = "UPDATE cms_friends SET status=$frnd_accpt WHERE published=1 AND requester_id=:User_id AND receipient_id=:Requester_id";
    $params2[] = array( "key" => ":Requester_id", "value" =>$requester_id);
    $params2[] = array( "key" => ":User_id", "value" =>$user_id);
    $update2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($update2,$params2);
    $res     = $update2->execute();
    newsfeedAdd($user_id, $requester_id, SOCIAL_ACTION_FRIEND, $requester_id, SOCIAL_ENTITY_USER, USER_PRIVACY_PUBLIC, NULL);
    addPushNotification(SOCIAL_ACTION_FRIEND, $requester_id, $user_id);
    return $res;
}

/**
 * rejects a freind request
 * @param integer $user_id the user rejecting a friend request
 * @param integer $requester_id the person who made the original request
 * @return boolean true|false if success|fail
 */
function userRejectFriendRequest($user_id, $requester_id) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_friends SET published = -2 WHERE published=1 AND (requester_id=:User_id AND receipient_id=:Requester_id) OR (requester_id=:Requester_id AND receipient_id=:User_id)";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Requester_id", "value" =>$requester_id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();
    
    newsfeedDelete($user_id, $requester_id, SOCIAL_ACTION_FRIEND);
    newsfeedDelete($requester_id, $user_id, SOCIAL_ACTION_FRIEND);
    newsfeedAdd($user_id, $requester_id, SOCIAL_ACTION_UNFRIEND, $requester_id, SOCIAL_ENTITY_USER, USER_PRIVACY_PUBLIC, null);
    return $res;
}
/**
 * delete a freind
 * @param integer $user_id the user deleting a friend request
 * @param integer $requester_id the person who made the originalrequest
 * @return boolean true|false if success|fail
 */
function userDeleteFriend($user_id, $requester_id) {
    return userRejectFriendRequest($user_id, $requester_id);
}

/**
 * ignores a freind request
 * @param integer $user_id the user ignoring a friend request
 * @param integer $requester_id the person who made the original request
 * @return boolean true|false if success|fail
 */
function userIgnoreFriendRequest($user_id, $requester_id) {
    global $dbConn;
	$params = array();  
    $frnd_ignore = FRND_STAT_IGNORE;
    $query = "UPDATE cms_friends SET status=$frnd_ignore WHERE published=1 AND requester_id=:User_id AND receipient_id=:Requester_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Requester_id",
                        "value" =>$requester_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * checks if 2 users are freinds
 * @param integer $user_id the user rejecting a friend request
 * @param integer $friend_id the second user
 * @return boolean true|false if friends or not
 */
function userIsFriend($user_id, $friend_id) {


    global $dbConn;
	$params = array();  
//    $frnd_accept = FRND_STAT_ACPT;
//    $query = "SELECT
//					*
//			FROM
//				cms_friends
//			WHERE
//				status='$frnd_accept' AND requester_id=$user_id AND receipient_id=$friend_id";
//    $res = db_query($query);
//    if ($res && ( db_num_rows($res) != 0 )) {
//        return true;
//    } else {
//        return false;
//    }
    $frnd_accept = FRND_STAT_ACPT;
    $query = "SELECT * FROM cms_friends WHERE published=1 AND status=:Frnd_accept AND requester_id=:User_id AND receipient_id=:Friend_id";
    $params[] = array( "key" => ":Frnd_accept", "value" =>$frnd_accept);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Friend_id", "value" =>$friend_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    
    if ($res && ( $ret != 0 )) {
        return true;
    } else {
        return false;
    }


}

/**
 * checks if 2 users are freinds
 * @param integer $user_id the user rejecting a friend request
 * @param integer $friend_id the second user
 * @return boolean true|false if friends or not
 */
// CODE NOT USED - commented by KHADRA
//function userIsExtendedFriend($user_id, $friend_id) {
//    //TODO: make sure this function still works after having changed the cms_friends table structure
//    $frnd_accept = FRND_STAT_ACPT;
//    $query = "SELECT
//					F1.*
//			FROM
//				cms_friends AS F1
//				INNER JOIN cms_friends AS F2 ON F1.requester_id=F2.receipient_id AND F2.status='$frnd_accept'
//				INNER JOIN cms_friends AS F3 ON F3.requester_id=F1.receipient_id AND F3.status='$frnd_accept'
//			WHERE
//				F1.status='$frnd_accept' AND (F1.requester_id=$user_id AND F2.receipient_id=$friend_id) OR (F1.requester_id=$friend_id AND F3.receipient_id=$user_id) LIMIT 1";
//    $res = db_query($query);
//    if ($res && ( db_num_rows($res) != 0 )) {
//        return true;
//    } else {
//        return false;
//    }
//}

/**
 * gets the extended friend slist
 * @param integer $user_id the user rejecting a friend request
 * @return array an array of user ids that are extended freinds
 */
function userExtendedFriendList($user_id) {
    //TODO: make sure this function still works after having changed the cms_friends table structure


    global $dbConn;
    $params = array();
//    $frnd_accept = FRND_STAT_ACPT;
//    $query = "SELECT
//					DISTINCT(F1.requester_id) AS id
//			FROM
//				cms_friends AS F1
//				INNER JOIN cms_friends AS F2 ON F1.requester_id=F2.receipient_id AND F2.status='$frnd_accept'
//				INNER JOIN cms_friends AS F3 ON F1.receipient_id=F3.requester_id AND F3.status='$frnd_accept'
//			WHERE
//				F1.status='$frnd_accept' AND (F1.requester_id=$user_id OR F3.receipient_id=$user_id)";
//
//    $res = db_query($query);
//    if ($res && ( db_num_rows($res) != 0 )) {
//        $ret = array();
//        while ($row = db_fetch_assoc($res)) {
//            $ret[] = $row;
//        }
//        return $ret;
//    } else {
//        return false;
//    }
    $frnd_accept = FRND_STAT_ACPT;
    $query = "SELECT
					DISTINCT(F1.requester_id) AS id
			FROM
				cms_friends AS F1
				INNER JOIN cms_friends AS F2 ON F2.published=1 AND F1.requester_id=F2.receipient_id AND F2.status=:Frnd_accept
				INNER JOIN cms_friends AS F3 ON F3.published=1 AND F1.receipient_id=F3.requester_id AND F3.status=:Frnd_accept
			WHERE
				F1.published=1 AND F1.status=:Frnd_accept AND (F1.requester_id=:User_id OR F3.receipient_id=:User_id)";
    $params[] = array( "key" => ":Frnd_accept",
                        "value" =>$frnd_accept);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    if ($res && ( $ret != 0 )) {
        $ret = array();
        $row = $select->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * gets a list of a users freinds
 * @param integer the user who we want to get his friends 
 * @return array the list of users friends (could be empty list)
 */
function userGetFreindList($user_id) {


    global $dbConn;
    $userGetFreindList    = tt_global_get('userGetFreindList');
    $params = array();  
    if(isset($userGetFreindList[$user_id]) && $userGetFreindList[$user_id]!=''){
        return $userGetFreindList[$user_id];
    }

//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic, U.gender
//			FROM
//				cms_friends AS F
//				INNER JOIN cms_users AS U ON U.id=F.receipient_id
//			WHERE
//				F.requester_id=$user_id AND F.status=" . FRND_STAT_ACPT . "
//			ORDER BY YourUserName ASC
//			";
//    $ret_arr = array();
//    $ret = db_query($query);
//
//    if (!$ret || db_num_rows($ret) == 0)
//        return array();
//
//    while ($row = db_fetch_array($ret)) {
//        $ret_arr[] = $row;
//    }
//    return $ret_arr;

    $query = "SELECT
				U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic, U.gender
			FROM
				cms_friends AS F
				INNER JOIN cms_users AS U ON U.id=F.receipient_id
			WHERE
				F.published=1 AND F.requester_id=:User_id AND F.status=" . FRND_STAT_ACPT . "
			ORDER BY YourUserName ASC
			";
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    $ret_arr = array();
    if (!$res || $ret == 0){
         $userGetFreindList[$user_id]  =   array();
        return array();
    }else{    
    $ret_arr    = $select->fetchAll();
    $userGetFreindList[$user_id]  =   $ret_arr;
    return $ret_arr;
    }


}

/**
 * gets the list of a users freinds that had the field notify=1
 * @param integer the user who we want to get his friends 
 * @return array the list of users friends (could be empty list)
 */
function userGetFreindNotificationList($user_id) {


    global $dbConn;
    $params = array();  
//
//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic
//			FROM
//				cms_friends AS F
//				INNER JOIN cms_users AS U ON U.id=F.receipient_id
//			WHERE
//				F.requester_id=$user_id AND F.notify=1 AND status IN(" . FRND_STAT_ACPT . "," . FRND_STAT_PENDING . ")
//			ORDER BY YourUserName ASC
//			";
//    $ret_arr = array();
//    $ret = db_query($query);
//
//    if (!$ret || db_num_rows($ret) == 0)
//        return array();
//
//    while ($row = db_fetch_array($ret)) {
//        $ret_arr[] = $row;
//    }
//    return $ret_arr;
    $query = "SELECT
				U.id, U.FullName, U.YourUserName, U.display_fullname , U.profile_Pic
			FROM
				cms_friends AS F
				INNER JOIN cms_users AS U ON U.id=F.receipient_id
			WHERE
				F.published=1 AND F.requester_id=:User_id AND F.notify=1 AND status IN(" . FRND_STAT_ACPT . "," . FRND_STAT_PENDING . ")
			ORDER BY YourUserName ASC
			";

    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();

    $ret_arr = array();
    if (!$res || $ret == 0)
        return array();

    $ret_arr = $select->fetchAll();
    return $ret_arr;


}

/**
 * gets a list of a users freinds
 * @param integer the user who we want to get his friends 
 * @return array the list of users friends (could be empty list)
 */
function userGetChatList($user_id) {


    global $dbConn;
	$params = array();  
//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname, U.gender, U.profile_Pic, F.*
//			FROM
//				cms_friends AS F
//				INNER JOIN cms_users AS U ON U.id=F.receipient_id
//			WHERE
//				F.requester_id=$user_id 
//				
//				AND profile_blocked=0
//				AND F.status=" . FRND_STAT_ACPT . "
//				AND NOT EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$user_id AND receipient_id=U.id AND profile_blocked=1)				
//			ORDER BY YourUserName ASC
//			";
//
//    $ret_arr = array();
//    $ret = db_query($query);
//
//    if (!$ret || db_num_rows($ret) == 0)
//        return array();
//
//    while ($row = db_fetch_array($ret)) {
//        if ($row['profile_Pic'] == '') {
//            //$row['profile_Pic'] = 'tuber.jpg';
//            $row['profile_Pic'] = 'he.jpg';
//            if ($row['gender'] == 'F') {
//                $row['profile_Pic'] = 'she.jpg';
//            }
//        }
//        $new_row = $row;
//        $ret_arr[] = $new_row;
//    }
//    return $ret_arr;
    $query = "SELECT
				U.id, U.FullName, U.YourUserName, U.display_fullname, U.gender, U.profile_Pic, F.*
			FROM
				cms_friends AS F
				INNER JOIN cms_users AS U ON U.id=F.receipient_id
			WHERE
				F.published=1 AND F.requester_id=:User_id 
				
				AND profile_blocked=0
				AND F.status=" . FRND_STAT_ACPT . "
				AND NOT EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:User_id AND receipient_id=U.id AND profile_blocked=1)				
			ORDER BY YourUserName ASC
			";
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $ret_arr = array();
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    if (!$res || $ret == 0)
        return array();

    $row    = $select->fetchAll();    
    $media = array();
    foreach($row as $row_item){
        if( $row_item['profile_Pic'] == ''){
            $row_item['profile_Pic'] = 'he.jpg';
            if ( $row_item['gender'] == 'F') {
                $row_item['profile_Pic'] = 'she.jpg';
            }
        }
        $media[] = $row_item;
    }

    return $media;


}

/*
 * Get the chat history for 
 * @param integer $user_id the user who we want to get his chat History
 * @param integer $to_id the friend id 
 */

function userGetChatHistory($user_id, $to_id, $page = 0, $limit = 100) {


    global $dbConn;
	$params = array(); 
//    $getChatHistory = "SELECT * FROM `cms_chat_log` WHERE ( `to_user` = '" . $user_id . "' AND `from_user` = '" . $to_id . "' ) OR ( `to_user` = '" . $to_id . "' AND `from_user` = '" . $user_id . "' ) ORDER BY `id` DESC limit " . (intval($page) * intval($limit)) . "," . intval($limit) . "";
//
//    $sendChatHistory = db_query($getChatHistory);
//
//    $ret_arr = array();
//    if (db_num_rows($sendChatHistory) == 0)
//        return array();
//
//    while ($row = db_fetch_array($sendChatHistory)) {
//        $ret_arr[] = $row;
//    }
//    return array_reverse($ret_arr);
    $getChatHistory = "SELECT * FROM `cms_chat_log` WHERE ( `to_user` = :User_id AND `from_user` = :To_id ) OR ( `to_user` = :To_id AND `from_user` = :User_id ) ORDER BY `id` DESC limit :Page,:Limit";
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":To_id",
                        "value" =>$to_id);
    $params[] = array( "key" => ":Page",
                        "value" =>(intval($page) * intval($limit)),
                        "type" =>"::PARAM_INT");
    $params[] = array( "key" => ":Limit",
                        "value" =>intval($limit),
                        "type" =>"::PARAM_INT");
    $select = $dbConn->prepare($getChatHistory);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $sendChatHistory    = $select->rowCount();

    $ret_arr = array();
    if ($sendChatHistory == 0)
        return array();
    
    $ret_arr    = $select->fetchAll();
    
    return array_reverse($ret_arr);


}

/**
 * gets a list of a users freinds
 * @param integer the user who we want to get his friends 
 * @return array the list of users friends (could be empty list)
 */
function userGetChatListRemoved($user_id) {


    global $dbConn;
	$params = array(); 
//
//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname, U.profile_Pic, F.*
//			FROM
//				cms_friends AS F
//				INNER JOIN cms_users AS U ON U.id=F.receipient_id
//			WHERE
//				F.requester_id=$user_id AND F.status=" . FRND_STAT_ACPT . " AND notify=0
//			ORDER BY YourUserName ASC
//			";
//    $ret_arr = array();
//    $ret = db_query($query);
//
//    if (!$ret || db_num_rows($ret) == 0)
//        return array();
//
//    while ($row = db_fetch_array($ret)) {
//        $ret_arr[] = $row;
//    }
//    return $ret_arr;
    $query = "SELECT
				U.id, U.FullName, U.YourUserName, U.display_fullname, U.profile_Pic, F.*
			FROM
				cms_friends AS F
				INNER JOIN cms_users AS U ON U.id=F.receipient_id
			WHERE
				F.published=1 AND F.requester_id=:User_id AND F.status=" . FRND_STAT_ACPT . " AND notify=0
			ORDER BY YourUserName ASC
			";
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    $ret_arr = array();
    if (!$res || $ret == 0)
        return array();
    
    $ret_arr    = $select->fetchAll();
    return $ret_arr;


}

/**
 * gets a list of a users freinds
 * @param integer the user who we want to get his friends 
 * @return array the list of users friends (could be empty list)
 */
function userGetExtendedFriendList($users) {

    $in_users = implode(',', $users);


    global $dbConn;
	$params = array();  
//
//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname, U.profile_Pic, F.*
//			FROM
//				cms_friends AS F
//				INNER JOIN cms_users AS U ON U.id=F.receipient_id
//			WHERE
//				F.requester_id IN ($in_users) AND F.status=" . FRND_STAT_ACPT . "
//			ORDER BY YourUserName ASC
//			";
//    
//    $ret_arr = array();
//    $ret = db_query($query);
//
//    if (!$ret || db_num_rows($ret) == 0)
//        return array();
//
//    $ids = array();
//    while ($row = db_fetch_array($ret)) {
//        if (!in_array($row['id'], $ids)) {
//            $ret_arr[] = $row;
//            $ids[] = $row['id'];
//        }
//    }
//    return $ret_arr;
    $query = "SELECT
				U.id, U.FullName, U.YourUserName, U.display_fullname, U.profile_Pic, F.*
			FROM
				cms_friends AS F
				INNER JOIN cms_users AS U ON U.id=F.receipient_id
			WHERE
				F.published=1 AND find_in_set(cast(F.requester_id as char), :In_users) AND F.status=" . FRND_STAT_ACPT . "
			ORDER BY YourUserName ASC";
    
    $params[] = array( "key" => ":In_users", "value" =>$in_users);
    $ret_arr = array();
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();


    if (!$res || $ret == 0)
        return array();

    $ids = array();
    $row   = $select->fetchAll();
    foreach($row as $row_item){
        if (!in_array($row_item['id'], $ids)) {
            $ret_arr[] = $row_item;
            $ids[] = $row_item['id'];
        }
    }
    return $ret_arr;


}

function userGetFreindListNew($user_id) {


    global $dbConn;
	$params = array();  
//
//    $query = "SELECT
//				U.id, U.FullName, U.YourUserName, U.display_fullname, U.profile_Pic, F.*
//			FROM
//				cms_friends AS F
//				INNER JOIN cms_users AS U ON U.id=F.receipient_id
//			WHERE
//				F.requester_id=$user_id AND F.status =" . FRND_STAT_NEW;
//    $ret_arr = array();
//    $ret = db_query($query);
//
//    if (db_num_rows($ret) == 0)
//        return $ret_arr;
//
//    while ($row = db_fetch_array($ret)) {
//
//        $ret_arr[] = $row;
//    }
//    return $ret_arr;
    $query = "SELECT
				U.id, U.FullName, U.YourUserName, U.display_fullname, U.profile_Pic, F.*
			FROM
				cms_friends AS F
				INNER JOIN cms_users AS U ON U.id=F.receipient_id
			WHERE
				F.published=1 AND F.requester_id=:User_id AND F.status =" . FRND_STAT_NEW;
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();
    $ret    = $select->rowCount();

    if ($ret == 0)
        return $ret_arr;

    $ret_arr    = $select->fetchAll(PDO::FETCH_ASSOC);
    return $ret_arr;


}

/**
 * sets if a user wants to receive notifications from his friends or not
 * @param integer $user_id the user that want to set a notification status
 * @param integer $friend_id the target of the notification status change
 * @param integer $notif 
 */
function userFriendNotificationSet($user_id, $friend_id, $notif) {
    global $dbConn;
    $params  = array();  
    $params2 = array(); 
    $query = "SELECT * FROM cms_friends WHERE published=1 AND requester_id=:User_id AND receipient_id=:Friend_id";
    $params2[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params2[] = array( "key" => ":Friend_id",
                        "value" =>$friend_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $select->execute();
    $ret    = $select->rowCount();
    
    if ($ret) {
        $row    = $select->fetchAll();
        if ($row['status'] == FRND_STAT_BLOCK_TUBER && $notif == 1) {
            $query = "UPDATE cms_friends SET published= -2 WHERE published=1 AND (requester_id=:User_id AND receipient_id=:Friend_id) OR (requester_id=:Friend_id AND receipient_id=:User_id)";
            newsfeedDelete($user_id, $friend_id, SOCIAL_ACTION_FRIEND);
            newsfeedDelete($friend_id, $user_id, SOCIAL_ACTION_FRIEND);
            $params[] = array( "key" => ":User_id",
                                "value" =>$user_id);
            $params[] = array( "key" => ":Friend_id",
                                "value" =>$friend_id);
        } else {
            $query = "UPDATE cms_friends SET notify=:Notif WHERE published=1 AND requester_id=:User_id AND receipient_id=:Friend_id";
            $params[] = array( "key" => ":User_id",
                                "value" =>$user_id);
            $params[] = array( "key" => ":Friend_id",
                                "value" =>$friend_id);
            $params[] = array( "key" => ":Notif",
                                "value" =>$notif);
        }
    } else {
        $query = "INSERT INTO cms_friends (requester_id,receipient_id,status,blocked,profile_blocked,notify) VALUES (:User_id,:Friend_id," . FRND_STAT_BLOCK_TUBER . ",0,0,:Notif)";
            $params[] = array( "key" => ":User_id",
                                "value" =>$user_id);
            $params[] = array( "key" => ":Friend_id",
                                "value" =>$friend_id);
            $params[] = array( "key" => ":Notif",
                                "value" =>$notif);
    }
    $delete_update_insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update_insert,$params);
    $res    = $delete_update_insert->execute();

    $ret    = $delete_update_insert->rowCount();
    if (!$res)
        return false;
    if ($ret != 0)
        return true;

    return false;
}

/**
 * checks if a user can recieve chat message from another user
 * @param integer $user_id the user that want to check a notification status
 * @param integer $friend_id the target of the notification status query
 * @param boolean $notif 
 */
function userFriendNotification($user_id, $friend_id) {
    //if user_id is the requester set the flag that specifies if he wants to receive from the receipient


    global $dbConn;
    $params = array(); 
//    $query = "SELECT notify FROM cms_friends WHERE requester_id='$user_id' AND receipient_id='$friend_id'";
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0))
//        return false;
//
//    $row = db_fetch_row($res);
//
//    $nr1 = $row[0];
//
//    return ($nr1 == 1);
    $query = "SELECT notify FROM cms_friends WHERE published=1 AND requester_id=:User_id AND receipient_id=:Friend_id";
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Friend_id",
                        "value" =>$friend_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    if (!$res || ($ret == 0))
        return false;

    $row    = $select->fetch();
    $nr1 = $row[0];
    
    return ($nr1 == 1);


}

/**
 * searchs in the list of friends. options include:<br/>
 * <b>limit</b>: integer - limit of record to return. default 6<br/>
 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
 * <b>type</b>: integer or array of integers - 0 => friend requests, 1 => friends, 2=> blocked friends, 3=> ignored friends, -1 => removed, 4 => profile blocked friends, 5=>pending friend requests<br/>
 * <b>userid</b>: integer - the user to search for<br/>
 * <b>begins</b>: user names begin with this letter <br/>
 * <b>YourBday</b>: user birth day <br/>
 * <b>from_ts</b>: start date default null<br/>
 * <b>to_ts</b>: end date default null<br/>
 * <b>orderby</b>: string - the cms_friends column to order the results by. default request_ts<br/>
 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
 * <b>n_results</b>: returns the results or the number of results. default false
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * @param array $srch_options search options
 * @return array of result records
 */
function userFriendSearch($srch_options) {


    global $dbConn;
	$params = array();  
        
    $default_opts = array(
        'limit' => 6,
        'page' => 0,
        'type' => 1,
        'is_visible' => -1,
        'userid' => null,
        'begins' => null,
        'search_string' => null,
        'YourBday' => null,
        'from_ts' => null,
        'to_ts' => null,
        'distinct_user' => 0,
        'escape_user' => null,
        'orderby' => 'request_ts',
        'order' => 'a',
        'dont_show' => 0,
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = '';
    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $user_id = $options['userid'];
    if (is_null($user_id)) {
        return array();
    }

    if (is_integer($options['type'])) {
        $types = array($options['type']);
    } else {
        $types = $options['type'];
    }

    $where = '';

    foreach ($types as $type) {
        if ($where != '') $where .= " OR ";
        if ($type == 0) {
            $where .= " (F.requester_id= :User_id AND F.status=" . FRND_STAT_NEW . " ) ";
            $params[] = array( "key" => ":User_id", "value" =>$user_id);
        } else if ($type == 1) {
            $where .= "(F.requester_id= :User_id2 AND F.status=" . FRND_STAT_ACPT . " AND F.blocked=0 AND F.profile_blocked=0) ";
            $params[] = array( "key" => ":User_id2", "value" =>$user_id);
        } else if ($type == 2) {
            $where .= " (F.requester_id= :User_id3 AND F.status=" . FRND_STAT_ACPT . " AND F.blocked=1 AND F.profile_blocked=0) ";
            $params[] = array( "key" => ":User_id3", "value" =>$user_id);
        } else if ($type == 3) {
            $where .= " (F.requester_id= :User_id4 AND F.status=" . FRND_STAT_IGNORE . ") ";
            $params[] = array( "key" => ":User_id4", "value" =>$user_id);
        } else if ($type == -1) {
            $where .= " (F.requester_id= :User_id5 AND F.status=" . FRND_STAT_RJCT . ") ";
            $params[] = array( "key" => ":User_id5", "value" =>$user_id);
        } else if ($type == 4) {
            $where .= " (F.requester_id= :User_id6 AND F.profile_blocked=1) ";
            $params[] = array( "key" => ":User_id6", "value" =>$user_id);
        } else if ($type == 5) {
            $where .= " (F.requester_id= :User_id7 AND F.status=" . FRND_STAT_PENDING . " ) ";
            $params[] = array( "key" => ":User_id7", "value" =>$user_id);
        }
    }

    if ($options["dont_show"] != 0) {
        if ($where != '')
            $where .= " AND ";
        $where .= " NOT find_in_set(cast(U.id as char), :Dont_show) ";
        $params[] = array( "key" => ":Dont_show", "value" =>$options["dont_show"]);
    }
    if ($options['is_visible'] != -1) {
        if ($where != '')
            $where .= " AND ";
        $where .= " F.is_visible=:Is_visible ";
        $params[] = array( "key" => ":Is_visible",
                            "value" =>$options["is_visible"]);
    }
    if (!is_null($options['YourBday'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " MONTH(U.YourBday)=MONTH(:YourBday) AND DAY(U.YourBday)=DAY(:YourBday) ";
        $params[] = array( "key" => ":YourBday",
                            "value" =>$options["YourBday"]);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " DATE(F.request_ts) >= :From_ts ";
        $params[] = array( "key" => ":From_ts",
                            "value" =>$options["from_ts"]);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " DATE(F.request_ts) <= :To_ts ";
        $params[] = array( "key" => ":To_ts",
                            "value" =>$options["to_ts"]);
    }

    $not_blocked_where = " NOT EXISTS (SELECT * FROM cms_friends AS F2 WHERE F2.published=1 AND F2.receipient_id=F.requester_id AND F2.requester_id=F.receipient_id AND F2.profile_blocked=1 ) ";
    $where = " ( ( $where ) AND $not_blocked_where ) ";

    if (!is_null($options['begins']) && $options['begins']!= '') {
        if ($options['begins'] == '#') {
            if ($where != '') $where .= " AND ";
            $where .= "( U.display_fullname=0 AND LOWER(U.YourUserName) REGEXP  '^[1-9]' )";
        }else {
            if ($where != '') $where .= " AND ";
            $where .= " ( 
                            (
                                U.display_fullname=0
                                AND
                                LOWER(U.YourUserName) LIKE :Begins
                            )
                        OR
                            (
                                U.display_fullname=1
                                AND
                                (
                                        LOWER(U.fname) LIKE :Begins
                                        OR
                                        LOWER(U.FullName) LIKE :Begins
                                )
                            )
                        )";
            $params[] = array( "key" => ":Begins", "value" =>$options['begins']."%");
        }
    }

    if (!is_null($options['search_string']) && $options['search_string']!= '') {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '') $where .= " AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "( 
                        (
                                U.display_fullname=0
                                AND
                                LOWER(U.YourUserName) LIKE :Search_string_loop$i
                        )
                        OR
                        (
                                U.display_fullname=1
                                AND
                                (
                                        LOWER(U.fname) LIKE :Search_string_loop$i 
                                        OR
                                        LOWER(U.lname) LIKE :Search_string_loop$i
                                        OR
                                        LOWER(U.FullName) LIKE :Search_string_loop$i 
                                )
                        )
                )";
            $params[] = array( "key" => ":Search_string_loop$i", "value" =>'%'.$search_string_loop.'%');
            $i++;
        }
        $where .= "( " . implode(' AND ', $wheres) . ")";
    }
    if(!is_null($options['escape_user'])){
        if( $where != '') $where .= " AND ";
//        $where .= " U.id NOT IN({$options['escape_user']}) ";
	$where .= " NOT find_in_set(cast(U.id as char), :Escape_user) ";
        $params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if( $where != '') $where .= " AND ";
    $where .= "F.published=1 ";
    if ($options['n_results'] == false) {        
        if( $options['distinct_user']==1 ){            
            $query = "SELECT U.*,F.* FROM cms_friends AS F INNER JOIN cms_users AS U ON U.id=F.receipient_id WHERE $where AND U.published=1 GROUP BY U.id ORDER BY $orderby $order";
        }else{
            $query = "SELECT U.*,F.* FROM cms_friends AS F INNER JOIN cms_users AS U ON U.id=F.receipient_id WHERE $where AND U.published=1 ORDER BY $orderby $order";
        }
        if (!is_null($options['limit'])) {
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        }
        
        $select = $dbConn->prepare($query);
        //debug($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
        
        $row   = $select->fetchAll();        
        $media = array();
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $row_item['notifications'] = $row_item['notify'];
            $media[] = $row_item;
        }
        return $media;
    } else {        
        if( $options['distinct_user']==1 ){
            $query = "SELECT COUNT( DISTINCT U.id ) FROM cms_friends AS F INNER JOIN cms_users AS U ON U.id=F.receipient_id WHERE $where AND U.published=1";
        }else{
            $query = "SELECT COUNT(F.requester_id) FROM cms_friends AS F INNER JOIN cms_users AS U ON U.id=F.receipient_id WHERE $where AND U.published=1";
        }
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();
        return $row[0];
    }
}

/**
 * blocks a freind
 * @param integer $user_id the user requesting the block
 * @param integer $friend_id the friend_id
 * @return boolean true|false if success|fail 
 */
function userBlockFriend($user_id, $friend_id) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_friends SET blocked=1 WHERE published=1 AND  requester_id=:User_id AND receipient_id=:Friend_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Friend_id",
                        "value" =>$friend_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * unblocks a freind
 * @param integer $user_id the user requesting the unblock
 * @param integer $friend_id the friend_id
 * @return boolean true|false if success|fail 
 */
function userUnblockFriend($user_id, $friend_id) {
    global $dbConn;
	$params = array(); 
    $query = "UPDATE cms_friends SET blocked=0 WHERE published=1 AND  requester_id=:User_id AND receipient_id=:Friend_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Friend_id",
                        "value" =>$friend_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * blocks a freind
 * @param integer $user_id the user requesting the block
 * @param integer $friend_id the friend_id
 * @return boolean true|false if success|fail 
 */
function userProfileBlockFriend($user_id, $friend_id) {
    userUnsubscribe($user_id, $friend_id);
    userUnsubscribe($friend_id, $user_id);


    global $dbConn;
	$params = array(); 

    $query = "SELECT * FROM cms_friends WHERE requester_id=:User_id AND receipient_id=:Friend_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Friend_id",
                        "value" =>$friend_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret>0) {
        $query = "UPDATE cms_friends SET profile_blocked=1, published=1, status=-7 WHERE  requester_id=:User_id AND receipient_id=:Friend_id";
    } else {
        $query = "INSERT INTO cms_friends (requester_id,receipient_id,request_msg,status,blocked,profile_blocked) VALUES (:User_id,:Friend_id,''," . FRND_STAT_BLOCK_TUBER . ",0,1)";
    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;


}

/**
 * unblocks a freind
 * @param integer $user_id the user requesting the unblock
 * @param integer $friend_id the friend_id
 * @return boolean true|false if success|fail 
 */
function userProfileUnblockFriend($user_id, $friend_id) {
    
    global $dbConn;
    $params = array(); 
    $query = "SELECT * FROM cms_friends WHERE published=1 AND  requester_id=:User_id AND receipient_id=:Friend_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Friend_id",
                        "value" =>$friend_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret>0) {
        $row = $select->fetch();
        if ($row['status'] == FRND_STAT_BLOCK_TUBER) {
            $query = "UPDATE cms_friends SET published= -2 WHERE published=1 AND (requester_id=:User_id AND receipient_id=:Friend_id) OR (requester_id=:Friend_id AND receipient_id=:User_id)";
            newsfeedDelete($user_id, $friend_id, SOCIAL_ACTION_FRIEND);
            newsfeedDelete($friend_id, $user_id, SOCIAL_ACTION_FRIEND);
        } else {
            $query = "UPDATE cms_friends SET profile_blocked=0 WHERE published=1 AND  requester_id=:User_id AND receipient_id=:Friend_id";
        }
    } else {
        return true;
    }
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();

    return $res;
}

/**
 * NOT TO BE USED. DELETE ASAP
 * Immediately blocks another user wheather freind or not.
 * @param integer $user_id the user initaiting the block
 * @param integer $user2_id the target of the block
 * @return boolean true|false if success|fail  
 */
function userBlockImmediate($user_id, $user2_id) {
    userUnsubscribe($user_id, $user2_id);
    userUnsubscribe($user2_id, $user_id);
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_friends SET profile_blocked=1 WHERE published=1 AND  requester_id=:User_id AND receipient_id=:User2_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":User2_id",
                        "value" =>$user2_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * edits a  user friend info
 * @param array $new_info the new cms_friends info
 * @return boolean true|false if success|fail
 */
function userFriendEdit($new_info) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_friends SET ";
    $i = 0;
    foreach ($new_info as $key => $val) {
        if ($key != 'requester_id' && $key != 'receipient_id') {
            $query .= " $key = :Val".$i.",";
            $params[] = array( "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query, ',');
    $query .= " WHERE published=1 AND requester_id=:Requester_id AND  receipient_id=:Receipient_id";
    $params[] = array( "key" => ":Requester_id",
                        "value" => $new_info['requester_id']);
    $params[] = array( "key" => ":Receipient_id",
                        "value" =>$new_info['receipient_id']);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return ( $res ) ? true : false;
}

/**
 * checks if a user is blocked by another user (profile-wise)
 * @param integer $blocker_id
 * @param integer $blocked_id
 * @return boolean true|false if blocked|not blocked
 */
function userIsProfileBlocked($blocker_id, $blocked_id) {


    global $dbConn;
	$params = array();  
//    if (!$blocked_id || !$blocker_id)
//        return;
//    $query = "SELECT profile_blocked FROM cms_friends WHERE (requester_id=$blocker_id AND receipient_id=$blocked_id)";
//    $ret = db_query($query);
//    if ($ret && (db_num_rows($ret) != 0)) {
//        $row = db_fetch_array($ret);
//        return ( $row['profile_blocked'] == 1 );
//    } else {
//        return false;
//    }
    if (!$blocked_id || !$blocker_id)
        return;
    $query = "SELECT profile_blocked FROM cms_friends WHERE (published=1 AND requester_id=:Blocker_id AND receipient_id=:Blocked_id)";
    $params[] = array( "key" => ":Blocker_id",
                        "value" => $blocker_id);
    $params[] = array( "key" => ":Blocked_id",
                        "value" =>$blocked_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    
    if ($res && ($ret != 0)) {
        $row    = $select->fetch();
        return ( $row['profile_blocked'] == 1 );
    } else {
        return false;
    }


}

/**
 * checks if a user is blocked by another user (chat-wise)
 * @param integer $blocker_id
 * @param integer $blocked_id
 * @return boolean true|false if blocked|not blocked
 */
function userIsBlocked($blocker_id, $blocked_id) {


    global $dbConn;
	$params = array();  
//    if (!$blocked_id || !$blocker_id)
//        return;
//    $query = "SELECT blocked,profile_blocked FROM cms_friends WHERE (requester_id=$blocker_id AND receipient_id=$blocked_id)";
//    $ret = db_query($query);
//    if ($ret && (db_num_rows($ret) != 0)) {
//        $row = db_fetch_array($ret);
//        return ( ($row['profile_blocked'] == 1) || ($row['blocked'] == 1) );
//    } else {
//        return false;
//    }
    if (!$blocked_id || !$blocker_id)
        return;
    $query = "SELECT blocked,profile_blocked FROM cms_friends WHERE (published=1 AND requester_id=:Blocker_id AND receipient_id=:Blocked_id)";
    $params[] = array( "key" => ":Blocker_id",
                        "value" => $blocker_id);
    $params[] = array( "key" => ":Blocked_id",
                        "value" =>$blocked_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    
    if ($res && ($ret != 0)) {
        $row    = $select->fetch();
        return ( ($row['profile_blocked'] == 1) || ($row['blocked'] == 1) );
    } else {
        return false;
    }


}

/**
 * checks if the user is blocked by the owner of a video
 * @param integer $vid the video id
 * @param integer $user_id the user to check if blocked
 * @return boolean true|false if blocked|not blocked
 */
// CODE NOT USED - commented by KHADRA
//function userIsBlockedVideo($vid, $user_id) {
//    if (!$user_id)
//        return;
//    $owner = getVideoInfo($vid);
//    return userIsProfileBlocked($owner['userid'], $user_id);
//}

/**
 * Disables a user
 * @param integer $uid the user id
 *  boolean true|false if success|fail
 */
function userDisable($uid) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_users SET published=-1 WHERE id=:Uid";
    $params[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * edits a users personal info
 * @param integer $user_id the user's id
 * @param string $fname first name
 * @param string $lname last name
 * @param string $website_url users website url
 * @param string $small_description small description of user
 * @param char $gender M|F|O for male|female|other
 * @param date $dob date of birth
 * @param integer $display_age 0|1 dont show | show age
 * @param string $city user's current city
 * @param string $country_code 2 letter country code
 * @param string $hometown user's hometown
 * @return boolean true|false if success|fail
 */
function userEditPersonalInfo($user_id, $fname, $lname, $website_url, $small_description, $gender, $dob, $display_age, $city_id, $city, $country_code, $hometown, $display_gender,$display_yearage=0) {
    global $dbConn;
    $params = array();
    $fullname = $fname . ' ' . $lname;
    $query = "UPDATE cms_users SET
				FullName=:Fullname,
				fname=:Fname,
				lname=:Lname,
				website_url=:Website_url,
				small_description=:Small_description,
				gender=:Gender,
				YourBday=:Dob,
				display_age=:Display_age,
				display_yearage=:Display_yearage,
				city_id=:City_id,
                                city=:City,
				hometown=:Hometown,
				YourCountry=:Country_code,
				display_gender=:Display_gender
			WHERE
				id=:User_id";
	$params[] = array( "key" => ":Fullname", "value" =>$fullname);
	$params[] = array( "key" => ":Fname", "value" =>$fname);
	$params[] = array( "key" => ":Lname", "value" =>$lname);
	$params[] = array( "key" => ":Website_url", "value" =>$website_url);
	$params[] = array( "key" => ":Small_description", "value" =>$small_description);
	$params[] = array( "key" => ":Gender", "value" =>$gender);
	$params[] = array( "key" => ":Dob", "value" =>$dob);
	$params[] = array( "key" => ":Display_age", "value" =>$display_age);
	$params[] = array( "key" => ":Display_yearage", "value" =>$display_yearage);
	$params[] = array( "key" => ":City_id", "value" =>$city_id);
	$params[] = array( "key" => ":City", "value" =>$city);
	$params[] = array( "key" => ":Hometown", "value" =>$hometown);
	$params[] = array( "key" => ":Country_code", "value" =>$country_code);
	$params[] = array( "key" => ":Display_gender", "value" =>$display_gender);
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();    
    return $res;
}

/**
 * update the user's number of profile views
 * @param integer $user_id the user's id
 * @return boolean true|false if success|fail 
 */
function userProfileViewed($user_id) {
    global $dbConn;
    $params = array(); 
    $query = "UPDATE cms_users SET profile_views = profile_views + 1 WHERE id=:User_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * update the user's position
 * @param float $longitude the longitude
 * @param float $latitude the latitude
 * @param integer $user_id the user's id
 * @return boolean true|false if success|fail 
 */
function userProfilePosition($user_id, $longitude, $latitude) {
    global $dbConn;
    $params = array(); 
    $query = "UPDATE cms_users SET longitude = :Longitude , latitude = :Latitude WHERE id=:User_id";
    $params[] = array( "key" => ":Longitude",
                        "value" =>$longitude);
    $params[] = array( "key" => ":Latitude",
                        "value" =>$latitude);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res = $update->execute();
    return $res;
}

/**
 * search users through position
 * @param float $longitude0 and $longitude1 the longitude between these two value
 * @param float $latitude0 and $latitude1 the latitude between these two value
 * @return boolean array of users | false if success|fail 
 */
function userSearchPosition($longitude0, $longitude1, $latitude0, $latitude1,$limit='',$orderby='') {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_users WHERE longitude BETWEEN :Longitude0 AND :Longitude1  AND latitude BETWEEN :Latitude0 AND :Latitude1 AND published=1 AND isChannel=0";
    if($orderby!=''){
       if($orderby =='rand') $query .=" ORDER BY RAND()";
       else $query .=" ORDER BY $orderby ASC";
    }
    if($limit!=''){
        $query .=" LIMIT 0,$limit";
    }
    $params[] = array( "key" => ":Longitude0", "value" =>$longitude0);
    $params[] = array( "key" => ":Longitude1", "value" =>$longitude1);
    $params[] = array( "key" => ":Latitude0", "value" =>$latitude0);
    $params[] = array( "key" => ":Latitude1", "value" =>$latitude1);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0 )) {
        $media = array();
        $row    = $select->fetchAll();
        foreach($row as $row_item){
            if($row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ($row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $row_item['level'] = 1;
            $row_item['show_on_map'] = 1;
            $row_item['categ'] = SOCIAL_ENTITY_USER;
            $media[]  = $row_item;
        }
        return $media;
    } else {
        return false;
    }


}

/**
 * checks if a user added a media a favorite
 * @param integer $user_id user adding a favorite
 * @param integer $video_id the video
 * @return boolean true|false if yes|no
 */
function userFavoriteAdded($user_id, $video_id) {
    return socialFavoriteAdded($user_id, $video_id, SOCIAL_ENTITY_MEDIA);
}

/**
 * add a favorite. DOEST CHECK IF ALREADY ADDED.
 * @param integer $user_id user adding a favorite
 * @param integer $video_id the video
 * @return boolean true|false if success|fail
 */
function userFavoriteAdd($user_id, $video_id) {
    return socialFavoriteAdd($user_id, $video_id, SOCIAL_ENTITY_MEDIA, null);
}

/**
 * delete a favorite
 * @param integer $user_id user adding a favorite
 * @param integer $video_id the video
 * @return boolean true|false if success|fail
 */
function userFavoriteDelete($user_id, $video_id) {
    return socialFavoriteDelete($user_id, $video_id, SOCIAL_ENTITY_MEDIA);
}

/**
 * checks if a user added a user as a favorite
 * @param integer $user_id user adding a favorite
 * @param integer $fav_id the video
 * @return boolean true|false if yes|no
 */
function userFavoriteUserAdded($user_id, $fav_id) {
    return socialFavoriteAdded($user_id, $fav_id, SOCIAL_ENTITY_USER);
}

/**
 * add a favorite. DOEST CHECK IF ALREADY ADDED.
 * @param integer $user_id user adding a favorite
 * @param integer $fav_id the video
 * @return boolean true|false if success|fail
 */
function userFavoriteUserAdd($user_id, $fav_id) {
    return socialFavoriteAdd($user_id, $fav_id, SOCIAL_ENTITY_USER, null);
}

/**
 * delete a favorite
 * @param integer $user_id user adding a favorite
 * @param integer $fav_id the the ather user id
 * @return boolean true|false if success|fail
 */
function userFavoriteUserDelete($user_id, $fav_id) {
    return socialFavoriteDelete($user_id, $fav_id, SOCIAL_ENTITY_USER);
}

/**
 * loads an image into an image resource using the bultin php functions
 * @param string $src the path to the image to be loaded
 */
function loadImage($src) {
    if (stristr($src, '.bmp') != null) {
        $img = @imagecreatefromwbmp($src);
    } else if (stristr($src, '.gif') != null) {
        $img = @imagecreatefromgif($src);
    } else if (stristr($src, '.png') != null) {
        $img = @imagecreatefrompng($src);
    } else if (stristr($src, '.jpg') != null || stristr($src, '.jpeg') != null) {
        $img = @imagecreatefromjpeg($src);
    } else if (stristr($src, '.tif') != null || stristr($src, '.tiff') != null) {
        $src_arr = pathinfo($src);
        $jpg_src = $src_arr['dirname'] . $src_arr['filename'] . '.jpg';
        $cmd = "convert $src $jpg_src";
        exec($cmd);
        @unlink($src);
        $img = @imagecreatefromjpeg($jpg_src);
    } else {
        return false;
    }
    return $img;
}

/**
 * sets the users profile pic
 * @param type $user_id the user
 * @param type $src the src image
 * @param type $dest the extracted profile pic (164x164)
 * @return boolean true|false if success|fail
 * @param integer $add_pic_feeds  0 don't add to news feed else 1 
 */
function userSetProfilePic($user_id, $src, $dest, $add_pic_feeds = 1) {

    $thumbWidth = THUMB_SIZE;
    $thumbHeight = THUMB_SIZE;

    $img = loadImage($src);

    if (!$img)
        return false;

    $width = imagesx($img);
    $height = imagesy($img);

    // calculate thumbnail size
    $new_width = $thumbWidth;
    $new_height = floor($height * ( $thumbWidth / $width ));

    if ($new_height > $thumbHeight) {
        $new_height = $thumbHeight;
        $new_width = floor($width * ( $thumbHeight / $height ));
    }
    global $dbConn;
    $params  = array();  
    $params2 = array();
    $query = "SELECT profile_Pic FROM cms_users WHERE id=:User_id ";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret    = $select->rowCount();
    if ( !$res || $ret == 0) return false;
    $row    = $select->fetch();
    $oldProfile = $row[0];
    $dirname = dirname($dest);
    @unlink($dirname . '/xsmall_' . $oldProfile);
    @unlink($dirname . '/thumb_' . $oldProfile);
    @unlink($dirname . '/small_' . $oldProfile);
    @unlink($dirname . '/cropable_' . $oldProfile);
    @unlink($dirname . '/crop_' . $oldProfile);
    //@unlink($dirname . '/' . $oldProfile);
    $small_dest = $dirname . '/small_' . basename($dest);
    //$xxs_dest = $dirname . '/xsmall_' . basename($dest);
    $xxs_dest = $dirname . '/xsmall_' . basename($dest);
    $thumb_dest = $dirname . '/thumb_' . basename($dest);

    photoThumbnailCreate($src, $dest, $new_width, $new_height);
    photoThumbnailCreate($src, $small_dest, 45, 45);
    photoThumbnailCreate($src, $xxs_dest, 28, 28);
    photoThumbnailCreate($src, $thumb_dest, 100, 100);

    // save new profile pic
    $dest_fname = basename($dest);

    if ($add_pic_feeds == 1) {
        $profile_id = AddUserDetail($user_id, $dest_fname, USER_DETAIL_PROFILE);
        newsfeedAdd($user_id, $profile_id, SOCIAL_ACTION_UPDATE, $profile_id, SOCIAL_ENTITY_USER_PROFILE, USER_PRIVACY_PUBLIC, NULL);
        $query = "UPDATE cms_users SET profile_Pic=:Dest_fname, profile_id=:Profile_id WHERE id=:User_id ";
        $params2[] = array( "key" => ":Dest_fname",
                             "value" =>$dest_fname);
        $params2[] = array( "key" => ":Profile_id",
                             "value" =>$profile_id);
        $params2[] = array( "key" => ":User_id",
                             "value" =>$user_id);
    } else {
        $profile_id = AddUserDetail($user_id, $dest_fname, USER_DETAIL_PROFILE);
        $query = "UPDATE cms_users SET profile_Pic=:Dest_fname WHERE id=:User_id ";
        $params2[] = array( "key" => ":Dest_fname",
                             "value" =>$dest_fname);
        $params2[] = array( "key" => ":User_id",
                             "value" =>$user_id);
    }
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params2);
    $res    = $update->execute();

    return $res;


}
/**
 * sets the users profile pic
 * @param type $user_id the user
 * @param type $src the src image
 * @param type $dest the extracted profile pic (164x164)
 * @return boolean true|false if success|fail
 */
function userUpdateProfilePic($user_id, $src, $dest, $profile_id,$dest_fname ) {

    $thumbWidth = THUMB_SIZE;
    $thumbHeight = THUMB_SIZE;

    $img = loadImage($src);
    if (!$img) return false;
    $width = imagesx($img);
    $height = imagesy($img);
    // calculate thumbnail size
    $new_width = $thumbWidth;
    $new_height = floor($height * ( $thumbWidth / $width ));

    if ($new_height > $thumbHeight) {
        $new_height = $thumbHeight;
        $new_width = floor($width * ( $thumbHeight / $height ));
    }
    global $dbConn;
    $params  = array();  
    $params2 = array();  
	
    $query = "SELECT profile_Pic FROM cms_users WHERE id=:User_id ";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret    = $select->rowCount();
    if ( !$res || $ret == 0) return false;
    $row    = $select->fetch();
    $oldProfile = $row[0];
    $dirname = dirname($dest);
    @unlink($dirname . '/xsmall_' . $oldProfile);
    @unlink($dirname . '/thumb_' . $oldProfile);
    @unlink($dirname . '/small_' . $oldProfile);
    @unlink($dirname . '/cropable_' . $oldProfile);
    @unlink($dirname . '/crop_' . $oldProfile);
    //@unlink($dirname . '/' . $oldProfile);
    $small_dest = $dirname . '/small_' . basename($dest);
    //$xxs_dest = $dirname . '/xsmall_' . basename($dest);
    $xxs_dest = $dirname . '/xsmall_' . basename($dest);
    $thumb_dest = $dirname . '/thumb_' . basename($dest);

    photoThumbnailCreate($src, $dest, $new_width, $new_height);
    photoThumbnailCreate($src, $small_dest, 45, 45);
    photoThumbnailCreate($src, $xxs_dest, 28, 28);
    photoThumbnailCreate($src, $thumb_dest, 100, 100);

    newsfeedAdd($user_id, $profile_id, SOCIAL_ACTION_UPDATE, $profile_id, SOCIAL_ENTITY_USER_PROFILE, USER_PRIVACY_PUBLIC, NULL);
	
    $query = "UPDATE cms_users SET profile_Pic=:Dest_fname, profile_id=:Profile_id WHERE id=:User_id ";
    $params2[] = array( "key" => ":Dest_fname", "value" =>$dest_fname);
    $params2[] = array( "key" => ":Profile_id", "value" =>$profile_id);
    $params2[] = array( "key" => ":User_id", "value" =>$user_id);
    
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params2);
    $res    = $update->execute();

    return $res;
}

/**
 * insert the users detail for a given user id
 * @param integer $user_id the desired user's id
 * @param string $detail_text the detail text
 * @param integer $detail_type the detail type (profile or info)
 * @return db_insert_id
 */
function AddUserDetail($user_id, $detail_text, $detail_type) {
    global $dbConn;
    $params = array();
    $query = "INSERT INTO cms_users_detail (user_id,detail_text,detail_type)
	      VALUES (:User_id,:Detail_text,:Detail_type)";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Detail_text",
                        "value" =>$detail_text);
    $params[] = array( "key" => ":Detail_type",
                        "value" =>$detail_type);

    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    $in_id  = $dbConn->lastInsertId();
    
    return ( $res ) ? $in_id : 0;
}

/**
 * edits a user detail info
 * @param array $news_info the new cms_users_detail info
 * @return boolean true|false if success|fail
 */
function userDetailEdit($news_info) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_users_detail SET ";
    $i = 0;
    foreach ($news_info as $key => $val) {
        if ($key != 'id' && $key != 'user_id') {
            $query .= " $key = :Val".$i.",";
            $params[] = array( "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query, ',');
    $query .= " WHERE id=:Id AND user_id=:User_id AND published=1";
    $params[] = array( "key" => "Id",
                        "value" => $news_info['id']);
    $params[] = array( "key" => "User_id",
                        "value" => $news_info['user_id']);
    
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();    
    return ( $res ) ? true : false;
}
/**
 * delete the user detail profile image
 * @param integer $user_id the user id
 * @param integer $id profile image id to be deleted
 * @return boolean true|false depending on the success of the operation
 */
function userDetailDelete($user_id, $id) {
    global $dbConn;
    $params2 = array();
    if (deleteMode() == TT_DEL_MODE_PURGE) {
        $query = "DELETE FROM cms_users_detail where user_id=:User_id AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query = "UPDATE cms_users_detail SET published=" . TT_DEL_MODE_FLAG . " WHERE user_id=:User_id AND id=:Id AND published=1";
    }
    newsfeedDeleteAll($id, SOCIAL_ENTITY_USER_PROFILE);

    $query_user = "UPDATE `cms_users` SET `profile_Pic`='',`profile_id`=0 WHERE `profile_id`=:Profile_id AND id=:User_id AND published=1";
    $params2[] = array( "key" => ":User_id", "value" =>$user_id);
    $params2[] = array( "key" => ":Profile_id", "value" =>$id);
    $select = $dbConn->prepare($query_user);
    PDO_BIND_PARAM($select,$params2);
    $select->execute();

    //delete comments
    socialCommentsDelete($id, SOCIAL_ENTITY_USER_PROFILE);

    //delete likes
    socialLikesDelete($id, SOCIAL_ENTITY_USER_PROFILE);
    
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Id", "value" =>$id);
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;
}
/**
 * gets user detail for a given id 
 * @param integer $id profile pic id
 * @return array the user detail
 */
function getUserDetail($id) {


    global $dbConn;
    $params = array();
    $getUserDetail = tt_global_get('getUserDetail');
    if(isset($getUserDetail[$id]) && $getUserDetail[$id]!='')
        return $getUserDetail[$id]; // added by rishav chhajer on 1st may 2015
    

    $query = "SELECT * FROM cms_users_detail where id=:Id AND published=1";
    $params[] = array( "key" => ":Id",
                        "value" =>$id);

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        $getUserDetail[$id] =   false;
        return false;
    } else {
        $ret_arr    = $select->fetchAll();
        $getUserDetail[$id] =   $ret_arr[0];
        return $ret_arr[0];
    }


}
/**
 * gets user detail for a given user id 
 * @param integer $user_id user id
 * @return array the user detail list
 */
function getUserDetailSearch($srch_options) {
    global $dbConn;
    $params = array();
    $default_opts = array(
        'limit' => 10,
        'page' => 0,
        'skip' => 0,
        'user_id' => NULL,
        'orderby' => 'id',
        'order' => 'a',
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = (intval($options['page']) * $nlimit) + intval($options['skip']);

    $where = '';
    if (!is_null($options['user_id'])) {
        if ($where != '') $where .= " AND";
        $where .= " user_id=:User_id";
        $params[] = array( "key" => ":User_id", "value" =>$options['user_id']);
    }
    if ($where != '') $where .= " AND";
    $where .=" published=1";
    if ($options['n_results'] == false) {
        $orderby = $options['orderby'];
        $order='';
        if ($orderby == 'rand') {
            $orderby = "RAND()";
        } else {
            $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
        }
        $query = "SELECT * FROM cms_users_detail WHERE $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
        $params[] = array( "key" => ":Skip", "value" =>$skip,"type" =>"::PARAM_INT" );
        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit,"type" =>"::PARAM_INT" );
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {        
            return array();
        } else {
            $ret_arr = $select->fetchAll();
            return $ret_arr;
        }
    }else{
        $query = "SELECT COUNT(id) FROM `cms_users_detail` WHERE $where";
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $select->execute();
        
        $row = $select->fetch();
        
        $n_results = $row[0];
        return $n_results;
    }
}
/**
 * gets user detail for a given id 
 * @param integer $id profile pic id + user id MD5
 * @return array the user detail
 */
function getUserDetailMD5($id) {
    global $dbConn;
    $params = array();
    $query = "SELECT * FROM cms_users_detail where MD5(concat(id,user_id))=:Id AND published=1";
    $params[] = array( "key" => ":Id", "value" =>$id);

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = $select->fetchAll();
        return $ret_arr[0];
    }
}

/**
 * gets user next previous profile picture
 * @param integer $id profile pic id 
 * @param String $next_prev argument for next or previous select
 * @return array the user profile pic
 */
function getUserNextPrevImage($id,$user_id,$next_prev) {


    global $dbConn;
    $params = array();  
    
    if($next_prev == 'next'){
         $query = "SELECT * FROM cms_users_detail where id > :Id and user_id =:User_id AND published=1 order by id ASC LIMIT 1";
    }else{
         $query = "SELECT * FROM cms_users_detail where id < :Id and user_id =:User_id AND published=1 order by id DESC LIMIT 1";
    }
    $params[] = array( "key" => ":Id", "value" =>$id);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    
    $ret    = $select->rowCount();
    if (!$res || $ret == 0 ) {
        return array();
    } else {
        $ret_arr    = $select->fetch(PDO::FETCH_ASSOC);
        return $ret_arr;
    }



}

/**
 * checks if a username is unique
 * @param integer $user_id the user's id
 * @param string $uname the username
 * @return boolean true|false if unique|not unique
 */
function userNameisUnique($user_id, $uname) {
    $l_username = strtolower($uname);


    global $dbConn;
	$params = array();  

//    $query = "SELECT id FROM cms_users WHERE LOWER(YourUserName)='$l_username' AND published <>-2";
//    $ret = db_query($query);
//    if (db_num_rows($ret) == 0)
//        return true;
//
//    $row = db_fetch_array($ret);
//    if ($row[0] == $user_id)
//        return true;
//
//    return false;
    $query = "SELECT id FROM cms_users WHERE LOWER(YourUserName)=:L_username AND published <>-2";
    $params[] = array( "key" => ":L_username",
                        "value" =>$l_username);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($ret == 0)
        return true;

    $row    = $select->fetch();
    if ($row[0] == $user_id)
        return true;

    return false;


}

/**
 * accept friendship request
 * @param MD5 $ID md5 your id + friend id
 * @param Int $loggeduser logged user id
 * @return true | false 
 */
function acceptFriendshipRequest($ID,$loggeduser) {


    global $dbConn;
	$params  = array();  
	$params2 = array();  
//    $query = "SELECT * FROM `cms_friends` WHERE MD5(concat(requester_id,receipient_id))='$ID' AND requester_id=$loggeduser AND status= ".FRND_STAT_NEW."";
//    $ret = db_query($query);
//
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        if(userAcceptFriendRequest($loggeduser, $row['receipient_id'])){
//            return true;
//        }else{
//           return false; 
//        }
//    } else {
//        $frnd_accept = FRND_STAT_ACPT;
//        $query = "SELECT * FROM cms_friends WHERE status='$frnd_accept' AND  MD5(concat(requester_id,receipient_id))='$ID' AND requester_id=$loggeduser";
//        $ret = db_query($query);
//        if ($ret && db_num_rows($ret) != 0) {
//            return true;
//        } else {
//            return false;
//        }
//    }
    $query = "SELECT * FROM `cms_friends` WHERE published=1 AND  MD5(concat(requester_id,receipient_id))=:ID AND requester_id=:Loggeduser AND status= ".FRND_STAT_NEW."";
    $params[] = array( "key" => ":ID",
                        "value" =>$ID);
    $params[] = array( "key" => ":Loggeduser",
                        "value" =>$loggeduser);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    if ($res && $ret != 0) {
        $row    = $select->fetch(PDO::FETCH_ASSOC);
        if(userAcceptFriendRequest($loggeduser, $row['receipient_id'])){
            return true;
        }else{
           return false; 
        }
    } else {
        $frnd_accept = FRND_STAT_ACPT;
        $query = "SELECT * FROM cms_friends WHERE published=1 AND status=:Frnd_accept AND  MD5(concat(requester_id,receipient_id))=:ID AND requester_id=:Loggeduser";
        $params2[] = array( "key" => ":Frnd_accept",
                             "value" =>$frnd_accept);
        $params2[] = array( "key" => ":ID",
                             "value" =>$ID);
        $params2[] = array( "key" => ":Loggeduser",
                            "value" =>$loggeduser);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params2);
        $res    = $select->execute();
        
        $ret    = $select->rowCount();
        
        if ($res && $ret != 0) {
            return true;
        } else {
            return false;
        }
    }


}
/**
 * unsubscribe user email
 * @param MD5 $emails md5 user id + email
 * @return array | false the cms_users record or null if not found
 */
function unsubscribeUserEmail($emails) {


    global $dbConn;
	$params = array();  
//
//    $query = "SELECT * FROM `cms_users` WHERE MD5(concat(id,YourEmail))='$emails'";
//
//    $ret = db_query($query);
//
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        AddNotificationsEmails($row['YourEmail'], 0);
//        return $row;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM `cms_users` WHERE MD5(concat(id,YourEmail))=:Emails";
    $params[] = array( "key" => ":Emails",
                        "value" =>$emails);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    if ($res && $ret != 0) {
        $row    = $select->fetch(PDO::FETCH_ASSOC);
        AddNotificationsEmails($row['YourEmail'], 0);
        return $row;
    } else {
        return false;
    }


}

/**
 * check invitation email
 * @param MD5 $emails md5 id + email
 * @return array | false the cms_invite record or null if not found
 */

function checkInvitationEmail($emails) {


    global $dbConn;
	$params = array();
//    $query = "SELECT * FROM `cms_invite` WHERE MD5(concat(id,to_email))='$emails'";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row['to_email'];
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM `cms_invite` WHERE MD5(concat(id,to_email))=:Emails";
    $params[] = array( "key" => ":Emails",
                        "value" =>$emails);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    
    $ret    = $select->rowCount();
    
    if ($res && $ret != 0) {
        $row    = $select->fetch(PDO::FETCH_ASSOC);
        return $row['to_email'];
    } else {
        return false;
    }


}

/**
 * delete user
 *  @param MD5 $emails md5 user id + email
 * @return boolean true|false depending on the success of the operation
 */
function deleteUserEmailMD5($emails) {


    global $dbConn;
	$params = array();  
//    $query = "DELETE FROM cms_users where MD5( concat(id,YourEmail) )='$emails'";
    $query = "DELETE FROM cms_users where MD5( concat(id,YourEmail) )=:Emails'";   
    $params[] = array( "key" => ":Emails",
                        "value" =>$emails);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute(); 
    
    return $res;


}

/**
 * check user email
 * @param MD5 $emails md5 user id + email
 * @return array | false the cms_users record or null if not found
 */
function checkUserEmailMD5($emails) {


    global $dbConn;
	$params = array();  
//    $emails = db_sanitize($emails);
//    $query = "SELECT * FROM `cms_users` WHERE MD5(concat(id,YourEmail))='$emails'";
//
//    $ret = db_query($query);
//
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row;
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM `cms_users` WHERE MD5(concat(id,YourEmail))=:Emails";
    $params[] = array( "key" => ":Emails",
                        "value" =>$emails);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    if ($res && $ret != 0) {
        $row    = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * add the emails notifications 
 * @param string $email the email
 * @param integer $notify 1 or 0 if false
 * @return true | false 
 */
function AddNotificationsEmails($email, $notify) {

    $row = getNotificationsEmails($email);


    global $dbConn;
	$params = array();  
    if ($row) {
        $query2 = "UPDATE `cms_notifications_emails` SET notify = :Notify WHERE id =:Id";
        $params[] = array( "key" => ":Notify",
                            "value" =>$notify);
        $params[] = array( "key" => ":Id",
                            "value" =>$row['id']);
    }
    // Row does not exist, create it.
    else {
        $query2 = "INSERT
					INTO
					cms_notifications_emails
						(email, notify)
					VALUES
					(:Email,:Notify)";
        $params[] = array( "key" => ":Email",
                            "value" =>$email);
        $params[] = array( "key" => ":Notify",
                            "value" =>notify);
    }
    $update_insert = $dbConn->prepare($query2);
    PDO_BIND_PARAM($update_insert,$params);
    $res     = $update_insert->execute();
    return ( $res ) ? true : false;
}

/**
 * get notification by email
 * @param string $email the email
 * @return record | false 
 */
function getNotificationsEmails($email) {


    global $dbConn;
	$params = array();  
//    $query = "SELECT * FROM cms_notifications_emails WHERE email like '" . $email . "'";
//    $ret = db_query($query);
//    return ( $ret && db_num_rows($ret) > 0 ) ? db_fetch_array($ret) : false;
    $query = "SELECT * FROM cms_notifications_emails WHERE email like :Email";
    $params[] = array( "key" => ":Email",
                        "value" =>$email);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    return ( $res && $ret > 0 ) ? $select->fetch() : false;


}

/**
 * check if email subscribe 
 * @param integer $userId
 * @return true | false 
 */
function checkNotification($userId) {
    $user = getUserInfo($userId);
    $email = '';
    if ($user["otherEmail"] != '') {
        $email = $user["otherEmail"];
    } else {
        $email = $user["YourEmail"];
    }
    $row = getNotificationsEmails($email);
    if ($row) {
        if ($row['notify'] == '0') {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

/**
 * edit the emails notifications 
 * @param string $email the email
 * @param integer $notify 1 or 0 if false
 * @return boolean true|false if success|fail
 */
function updateNotificationsEmails($email, $notify) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_notifications_emails SET notify=:Notify WHERE MD5(email)=:Email";
    $params[] = array( "key" => ":Notify",
                        "value" =>$notify);
    $params[] = array( "key" => ":Email",
                        "value" =>$email);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * edits a username
 * @param integer $user_id the user
 * @param string $uname  the new username
 * @return boolean true|false if success|fail
 */
function userEditUsername($user_id, $uname) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_users SET YourUserName=:Uname WHERE id=:User_id";
    $params[] = array( "key" => ":Uname",
                        "value" =>$uname);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}

/**
 * edits a users data
 * @param array $cols the cms_users columns to be edited + id
 * @return boolean true|false if success|fail
 */
function userEdit($cols) {
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_users SET ";
    $i = 0;
    foreach ($cols as $key => $value) {
        if ($key == 'id')
            continue;

        $query .= "$key=:Value".$i.", ";
	$params[] = array( "key" => ":Value".$i,
                            "value" =>$value);
        $i++;
    }
    $query = trim($query, ', ');
    $query .= " WHERE id=:Id ";
    $params[] = array( "key" => ":Id",
                        "value" =>$cols['id']);
    
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;


}
/*
 * userAlreadyAuthorized function will check weather the user is already authorized with twitter or any other social network
 */
function userAlreadyAuthorized($userId=null,$account_type='',$active='1'){


    global $dbConn;
    $params = array();
    $query = "select * from cms_users_social_tokens where `user_id`=:UserId and account_type=:Account_type and `status`=:Active";
    $params[] = array( "key" => ":UserId", "value" =>$userId);
    $params[] = array( "key" => ":Account_type", "value" =>$account_type);
    $params[] = array( "key" => ":Active", "value" =>$active);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();
    
    $ret    = $select->rowCount();
    if ($ret > 0){
        $row    = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    else{
        return array();
    }
}
/*
 * @updateUserSocialCredential function will update the user credential from status 0 to 1 for respective social media
 */

function updateUserSocialCredential($userId=null,$account_type='twitter'){
    global $dbConn;
	$params = array(); 
    $query = "UPDATE cms_users_social_tokens SET `status`=1 where `user_id`=:UserId and `account_type`=:Account_type";
    $params[] = array( "key" => ":UserId",
                        "value" =>$userId);
    $params[] = array( "key" => ":Account_type",
                        "value" =>$account_type);
    
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    
    if(!empty($res))
        return true;
    return false;
}
/*
 * @disconnectFromSocialMedia will disconnect the user to respective social media by changing its status from 1 to 0
 */
function disconnectFromSocialMedia($userId=null,$account_type=''){
    global $dbConn;
	$params = array();  
    $query = "UPDATE cms_users_social_tokens SET `status`=0 where `user_id`=:UserId and `account_type`=:Account_type";
    $params[] = array( "key" => ":UserId",
                        "value" =>$userId);
    $params[] = array( "key" => ":Account_type",
                        "value" =>$account_type);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    
    return $res;
}
/*
 * @setUserSocialCredential function will store the user credential for respective social network
 */
function setUserSocialCredential($userId=null,$access_token,$account_type='twitter'){


    global $dbConn;
	$params  = array();  
	$params2 = array();  


    $socialId = $access_token['user_id'];
    $oauth_token = $access_token['oauth_token'];
    $oauth_token_secret = $access_token['oauth_token_secret'];
    
    $query1 = "delete from cms_users_social_tokens where account_type='fb' and user_id=:UserId";
    $params[] = array( "key" => ":UserId",
                        "value" =>$userId);
    $delete = $dbConn->prepare($query1);
    PDO_BIND_PARAM($delete,$params);
    $delete->execute();
    $query = "INSERT into cms_users_social_tokens (`user_id`, `account_type`, `social_id`, `oauth_token`, `oauth_token_secret`, `status`) values(:UserId,:Account_type,:SocialId,:Oauth_token,:Oauth_token_secret,1)";
    $params2[] = array( "key" => ":UserId",
                        "value" =>$userId);
    $params2[] = array( "key" => ":Account_type",
                        "value" =>$account_type);
    $params2[] = array( "key" => ":SocialId",
                        "value" =>$socialId);
    $params2[] = array( "key" => ":Oauth_token",
                        "value" =>$oauth_token);
    $params2[] = array( "key" => ":Oauth_token_secret",
                        "value" =>$oauth_token_secret);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params2);
    $res    = $insert->execute();
//    $rs = db_query($query);
    if(!empty($res))
    return true;

    return false;



}

/**
 * checks if a user's password is correct
 * @param integer $user_id
 * @param string $old_pass
 * @return boolean true|false
 */
function userPasswordCorrect($user_id, $old_pass) {


    global $dbConn;
	$params = array();  
//    $query = "SELECT id FROM cms_users WHERE YourPassword=password('$old_pass') AND id='$user_id'";
//    $ret = db_query($query);
//    if (db_num_rows($ret) == 1)
//        return true;
//    else
//        return false;
    $query = "SELECT id FROM cms_users WHERE YourPassword=password(:Old_pass) AND id=:User_id";
    $params[] = array( "key" => ":Old_pass",
                        "value" =>$old_pass);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
    if ($ret == 1)
        return true;
    else
        return false;



}

/**
 * chnage a user password
 * @param integer $user_id the user id
 * @param string $new_pass the new password
 * @return boolean true|false if success|fail
 */
function userChangePassword($user_id, $new_pass) {
    if ($new_pass == '')
        return false;
    global $dbConn;
	$params = array(); 
    $query = "UPDATE cms_users SET YourPassword=password(:New_pass) WHERE id=:User_id";
    $params[] = array( "key" => ":New_pass",
                        "value" =>$new_pass);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}
/**
 * sets the secret string for a user password change request
 * @param integer $user_id the user id
 * @param string $secret the secret
 * @return boolean true|false if success|fail
 */
function userSecretCommit($user_id, $secret) {
    if ($secret == '')
        return false;
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_users SET YourPassword=password(:Secret) WHERE id=:User_id AND chkey=:Secret ";
    $params[] = array( "key" => ":Secret",
                        "value" =>$secret);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * checks if an email is unique
 * @param integer $user_id the user's id
 * @param string $uname the username
 * @return boolean true|false if unique|not unique
 */
function userEmailisUnique($user_id, $email) {


    global $dbConn;
	$params = array(); 
    $query = "SELECT id,YourEmail FROM cms_users WHERE YourEmail=:Email AND published <>-2";
    $params[] = array( "key" => ":Email",
                        "value" =>$email);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
    if ($ret == 0)
        return true;

    $row    = $select->fetch();
    if ($row[0] == $user_id)
        return true;

    return false;
}

/**
 * checks if an email is unique
 * @param integer $user_id the user's id
 * @param string $uname the username
 * @return boolean true|false if unique|not unique
 */
function EmailIsUnique($email) {


    global $dbConn;
    $params = array(); 
    $query = "SELECT * FROM cms_users WHERE YourEmail=:Email AND published <>-2";
    $params[] = array( "key" => ":Email",
                        "value" =>$email);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
    if ($ret > 0){
        $row    = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    else{
        return array();
    }


}

/**
 * sets a user's email
 * @param integer $user_id the user
 * @param string $new_email the new email
 */
function userSetEmail($user_id, $new_email) {
    global $dbConn;
	$params = array(); 
    $query = "UPDATE cms_users SET YourEmail=:New_email WHERE id=:User_id";
    $params[] = array( "key" => ":New_email",
                        "value" =>$new_email);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * notify user upon video/photo comments 
 */
define('NOTIF_COMMENT', 1);
/**
 * notify user upon freind request 
 */
define('NOTIF_FREIND', 2);
/**
 * notify user upon subscribtion to his/her channel
 */
define('NOTIF_SUBSCRIBE', 4);
/**
 * notify user upon reply to his/her comments
 */
define('NOTIF_REPLY', 8);
/**
 * notify user upon follow friend
 */
define('NOTIF_FOLLOW', 16);
/**
 * notify user upon subscribe to my channel
 */
define('NOTIF_CHANNEL', 32);

/**
 * sets the notification values for a user
 * @param integer $user_id the user
 * @param integer $notifs the or'd value for the notifications
 * @return boolean true|false if success|fail 
 */
function userSetNotifications($user_id, $notifs) {
    global $dbConn;
	$params = array();
    $query = "UPDATE cms_users SET notifs=:Notifs WHERE id=:User_id";
    $params[] = array( "key" => ":Notifs",
                        "value" =>$notifs);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * checks if a user should be notified
 * @param integer $user_id which user
 * @param integer $which the notification
 * @return boolean true|fasle if yes|no
 */
function userNotify($user_id, $which) {
    global $dbConn;
	$params = array();  
    $query = "SELECT notifs FROM cms_users WHERE id=:User_id";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
    if ($ret == 0)
        return false;
    $row    = $select->fetch();
    if ($row[0] & $which)
        return true;
}

/**
 * checks if a user is to be notified about comments
 * @param integer $user_id which user
 * @return boolean true|fasle if yes|no 
 */
function userNotifyOnComment($user_id) {
    return userNotify($user_id, NOTIF_COMMENT);
}

/**
 * checks if a user is to be notified about freind requests
 * @param integer $user_id 
 * @return boolean true|fasle if yes|no 
 */
function userNotifyOnFreind($user_id) {
    return userNotify($user_id, NOTIF_FREIND);
}

/**
 * checks if a user is to be notified about others subscribing ti his/her channel
 * @param integer $user_id 
 * @return boolean true|fasle if yes|no 
 */
function userNotifyOnSubscribe($user_id) {
    return userNotify($user_id, NOTIF_SUBSCRIBE);
}

/**
 * checks if a user is to be notified about others replying to his/her comments
 * @param integer $user_id 
 * @return boolean true|fasle if yes|no 
 */
function userNotifyOnReply($user_id) {
    return userNotify($user_id, NOTIF_REPLY);
}

/**
 * finds a user id given his username
 * @param string $username the username of the user to locate
 * @return integer | false the user's id if found or false if not found
 */
function userFindByUsername($username) {


    global $dbConn;
	$params = array();  
//    $query = "SELECT id FROM cms_users WHERE YourUserName='$username'";
//    $ret = db_query($query);
//    if (db_num_rows($ret) == 0)
//        return false;
//    $row = db_fetch_array($ret);
//    return $row[0];
    $query = "SELECT id FROM cms_users WHERE YourUserName=:Username";
    $params[] = array( "key" => ":Username",
                        "value" =>$username);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
    if ($ret == 0)
        return false;
    $row    = $select->fetch();
    return $row[0];


}

/**
 * sets the location of a tourist tuber
 * @param string $uid a unique id that describes the user in case not logged in.
 * @param integer $user_id the user's id
 * @param float $latitude the latitude
 * @param float $longitude the longitude
 */
function userSetLocation($uid, $user_id, $latitude, $longitude) {


    global $dbConn;
	$params  = array();
	$params2 = array();
    $_user_id = $user_id;
    $_w_user_id = '';

    if (is_null($_user_id)) {
        $_user_id = 'NULL';
        //$_w_user_id = 'OR  COALESCE(user_id ,0)=0';
    } else {
        //$_w_user_id = 'OR user_id=' . $user_id;
    }

//    $query = "DELETE FROM cms_tubers WHERE uid='$uid' OR log_ts < (NOW() - INTERVAL 24 HOUR) $_w_user_id";
    $query = "DELETE FROM cms_tubers WHERE uid=:Uid OR log_ts < (NOW() - INTERVAL 24 HOUR) $_w_user_id";
    $params[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $delete->execute();

    $query = "INSERT INTO cms_tubers (uid, user_id, latitude, longitude) VALUES (:Uid, :User_id, :Latitude, :Longitude )";
    $params2[] = array( "key" => ":Uid",
                         "value" =>$uid);
    $params2[] = array( "key" => ":User_id",
                         "value" =>$_user_id);
    $params2[] = array( "key" => ":Latitude",
                         "value" =>$latitude);
    $params2[] = array( "key" => ":Longitude",
                         "value" =>$longitude);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params2);
    $res = $insert->execute();
    return $res;


}

/**
 * web client
 */
define('CLIENT_WEB', 0);
/**
 * iphone client
 */
define('CLIENT_IOS', 1);
/**
 * android client
 */
define('CLIENT_ANDROID', 2);
/**
 * android client
 */
define('CLIENT_WINDOWS', 3);
/**
 * android client
 */
define('CLIENT_NOKIA', 4);
/**
 * android client
 */
define('CLIENT_BLACKBERRY', 5);

/**
 * gets the string representation of the client type
 * @param integer $in the client type integer
 * @return string|false the string if know client type, false otherwise
 */
function userClientType($in) {
    if ($in == CLIENT_WEB)
        return 'web';
    else if ($in == CLIENT_IOS)
        return 'ios';
    else if ($in == CLIENT_ANDROID)
        return 'android';
    else if ($in == CLIENT_NOKIA)
        return 'nokia';
    else if ($in == CLIENT_WINDOWS)
        return 'windows';
    else if ($in == CLIENT_BLACKBERRY)
        return 'blackberry';
    else
        return false;
}

/**
 * gets a cms_tubers record given the session
 * @param string $uid the session id
 * @return boolean|integer false if not found or user's id
 */
function userFromSession($uid) {


    global $dbConn;
	$params = array();  
//    $query = "SELECT * FROM cms_tubers WHERE uid='$uid'";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0))
//        return false;
//    $row = db_fetch_assoc($ret);
//    return $row;
    $query = "SELECT * FROM cms_tubers WHERE uid=:Uid";
    $params[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    
    $ret    = $select->rowCount();
    
    if (!$res || ($ret == 0))
        return false;
    $row    = $select->fetch(PDO::FETCH_ASSOC);
    return $row;


}

/**
 * ends a users session (mostly for mobile)
 * @param string $uid the session id
 * @return boolean|integer false if not found or user's id
 */
function userEndSession($uid) {
	
	if (!$uid)
		return 0;
	
    global $dbConn;
	$params = array();  
//    $query = "DELETE FROM cms_tubers WHERE uid='$uid'";
//    return db_query($query);
    $query = "DELETE FROM cms_tubers WHERE uid=:Uid";
    $params[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();
	
    return $res;
}

/**
 * search for users given certain options. options include:<br/>
 * <b>limit</b>: the maximum number of user records returned. default 42<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>skip</b>: the number of records to skip after page,limit calculations. default 0<br/>
 * <b>user_id</b>: which cms_user (typically the user who is logged in). required.<br/>
 * <b>channel_id</b>: the user must be subscribed to this channel. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>profile_pic</b>: the user must have a profile pic
 * <b>n_results</b>: true to return number of results. default false<br/>
 * @param array $srch_options. the search options
 * @return array a number of media records
 */
function userNotSubscribed($in_options) {


    global $dbConn;
	$params = array();  

    $default_opts = array(
        'limit' => 42,
        'page' => 0,
        'skip' => 0,
        'orderby' => 'id',
        'order' => 'a',
        'profile_pic' => false,
        'n_results' => false,
        'user_id' => null,
        'channel_id' => null
    );

    $options = array_merge($default_opts, $in_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit + intval($options['skip']);

    $where = '';

    if (!is_null($options['user_id'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " U.id<>{$options['user_id']} AND  NOT EXISTS (SELECT id FROM cms_subscriptions WHERE subscriber_id='{$options['user_id']}' AND user_id=U.id) ";
        $where .= " U.id<>:User_id AND  NOT EXISTS (SELECT id FROM cms_subscriptions WHERE published = 1 AND subscriber_id=:User_id AND user_id=U.id) ";
        $params[] = array( "key" => ":User_id",
                            "value" =>$options['user_id']);
    }
    if ($options['channel_id']) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " EXISTS (SELECT id FROM cms_channel_connections WHERE userid=U.id AND channelid='{$options['channel_id']}') ";
        $where .= " EXISTS (SELECT id FROM cms_channel_connections WHERE userid=U.id AND channelid=:Channel_id) ";
        $params[] = array( "key" => ":Channel_id",
                            "value" =>$options['channel_id']);
    }

    if ($options['profile_pic'] == true) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " profile_Pic <> '' ";
    }
    if ($where != '')
        $where .= ' AND ';
    $where .= " U.isChannel=0";

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    $where .=' AND U.published=1 AND U.isChannel=0 ';
    if ($where != '')
        $where = " WHERE $where";

    if ($options['n_results'] == false) {
//        $query = "SELECT * FROM `cms_users` AS U $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query = "SELECT * FROM `cms_users` AS U $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
        $params[] = array( "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
        $params[] = array( "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");
//        $ret = db_query($query);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
//        while ($row = db_fetch_array($ret)) {
//            if ($row['profile_Pic'] == '') {
//                $row['profile_Pic'] = 'he.jpg';
//                if ($row['gender'] == 'F') {
//                    $row['profile_Pic'] = 'she.jpg';
//                }
//            }
//            $media[] = $row;
//        }
        $row    = $select->fetchAll();        
        $media = array();
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $media[] = $row_item;
        }
    } else {
        $query = "SELECT COUNT(id) FROM `cms_users` AS U $where";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();
        $row    = $select->fetch();
        return $row[0];
    }

    return $media;
}



/**
 * search for users given certain options. options include:<br/>
 * <b>limit</b>: the maximum number of user records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>public</b>: wheather the user is public or not. default 1<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>search_where</b>: where to search for the string (u)sername, (n)ame, (e)mail, (c)ountry, (h)ometown, l(ocation), (a)ll, or a comma separated combination. default is 'a'<br/>
 * <b>search_strict</b>: if the search is strict or not default 0<br/>
 * <b>profile_pic</b>: the user must have a profile pic
 * <b>n_results</b>: true to return number of results. default false<br/>
 * @param array $srch_options. the search options
 * @return array a number of media records
 */
function userSearch($srch_options) {


    global $dbConn;
    $params  = array();  
    $params2 = array();  

    $default_opts = array(
        'limit' => 6,
        'page' => 0,
        'public' => 1,
        'orderby' => 'id',
        'order' => 'a',
        'get_statistic' => 0,
        'search_string' => null,
        'search_strict' => 0,
        'search_where' => 'a',
        'profile_pic' => false,
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = '';
    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
    }

    $where = '';

    if (userIsLogged()) {
        $searcher_id = userGetID();
        $friends = userGetFreindList($searcher_id);

        $friends_ids = array($searcher_id);
        foreach ($friends as $freind) {
            $friends_ids[] = $freind['id'];
        }
        if (count($friends_ids) != 0) {
            if ($where != '')
                $where .= " AND ";
            $public = USER_PRIVACY_PUBLIC;
            $private = USER_PRIVACY_PRIVATE;
            $selected = USER_PRIVACY_SELECTED;
            $community = USER_PRIVACY_COMMUNITY;
            $privacy_where = '';

            $where .= "CASE";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
            $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
            
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Private AND PR.user_id= :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
            
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public", "value" =>$public);
            $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);            
            $params[] = array( "key" => ":Private", "value" =>$private);
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
//        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
        $params[] = array( "key" => ":Public",
                            "value" =>$public);
    }

    if (!is_null($options['search_string'])) {
        $search_strings = explode(' ', trim($options['search_string']));
        $search_where = explode(',', $options['search_where']);
        $search_strict = $options['search_strict'];
        $sub_where = array();
        foreach ($search_strings as $search_string) {

            if ($search_string == '')
                continue;
            //if(	strlen($search_string) < 3) continue;

            $l_search_string = strtolower($search_string);

            if (in_array('c', $search_where) || in_array('a', $search_where)) {
                $country_code = countryGetCode($search_string);
                if ($country_code != false){
                    $sub_where[] = " U.YourCountry = :Sub_where1";
                    $params[] = array( "key" => ":Sub_where1", "value" =>$country_code);
                }
            }

            if (in_array('n', $search_where) || in_array('a', $search_where)) {
                if ($search_strict) {
                    $sub_where[] = " U.FullName= :Sub_where2 ";
                    $params[] = array( "key" => ":Sub_where2", "value" =>$search_string);
                } else {
                    $sub_where[] = " (U.display_fullname=1 AND LOWER(U.fname) LIKE :Sub_where3 )";
                    $sub_where[] = " (U.display_fullname=1 AND LOWER(U.lname) LIKE :Sub_where3 )";
                    $sub_where[] = " (U.display_fullname=1 AND LOWER(U.FullName) LIKE :Sub_where3 )";
                    $params[] = array( "key" => ":Sub_where3", "value" =>'%'.$l_search_string.'%');
                }
            }

            if (in_array('h', $search_where) || in_array('a', $search_where)) {
                if ($search_strict){
                    $sub_where[] = " U.hometown=:Sub_where4 ";
                    $params[] = array( "key" => ":Sub_where4", "value" =>$search_string);
                }else{
                    $sub_where[] = " LOWER(U.hometown) LIKE :Sub_where5 ";
                    $params[] = array( "key" => ":Sub_where5", "value" =>'%'.$l_search_string.'%');
                }
            }

            if (in_array('l', $search_where) || in_array('a', $search_where)) {
                if ($search_strict){
                    $sub_where[] = " U.city=:Sub_where6 ";
                    $params[] = array( "key" => ":Sub_where6", "value" =>$search_string);
                }else{
                    $sub_where[] = " LOWER(U.city) LIKE :Sub_where7 ";
                    $params[] = array( "key" => ":Sub_where7", "value" =>'%'.$l_search_string.'%');
                }
            }

            if (in_array('u', $search_where) || in_array('a', $search_where)) {
                if ($search_strict){
                    $sub_where[] = " U.YourUserName=:Sub_where8 ";
                    $params[] = array( "key" => ":Sub_where8", "value" =>$search_string);
                }else{
                    $sub_where[] = " (U.display_fullname=0 AND LOWER(U.YourUserName) LIKE :Sub_where9 )";
                    $params[] = array( "key" => ":Sub_where9", "value" =>'%'.$l_search_string.'%');
                }
            }
        }

        if (count($sub_where) != 0) {
            if ($where != '') $where .= ' AND ';
            $sub_where = '(' . implode(' OR ', $sub_where) . ')';
            $where .= $sub_where;
        }
    }

    if ($options['profile_pic'] == true) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " U.profile_Pic <> '' ";
    }    

    if (userIsLogged()) {
        if ($where != '')
            $where .= ' AND ';
        $user_id = userGetID();
//         $where .= "(
//                NOT EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$user_id AND receipient_id=U.id AND profile_blocked=1)
//                AND
//                NOT EXISTS (SELECT receipient_id FROM cms_friends WHERE receipient_id=$user_id AND requester_id=U.id AND profile_blocked=1) 
//        )";
        $where .= "(
                NOT EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:User_id AND receipient_id=U.id AND profile_blocked=1)
                AND
                NOT EXISTS (SELECT receipient_id FROM cms_friends WHERE published=1 AND receipient_id=:User_id AND requester_id=U.id AND profile_blocked=1) 
        )";
        $params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
        if ($where != ''){
//            $where .= " AND U.id<>$user_id ";
            $where .= " AND U.id<>:User_id2 ";
            $params[] = array( "key" => ":User_id2",
                                "value" =>$user_id);
        }
    }

    $orderby = $options['orderby'];
    $order='';

    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $orderby = "U.$orderby";
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $where .=' AND U.published=1 AND U.isChannel=0 ';
    if ($where != '')
        $where = " WHERE $where";

    if ($options['n_results'] == false) {
        if ($options['get_statistic'] == 1) {
            $query = "SELECT count(DISTINCT V1.id) AS nVideos, count(DISTINCT C1.id) AS nCatalogs, count(DISTINCT V2.id) AS nImages, U.profile_views AS nViews , U.* FROM `cms_users` AS U";
            $query .= " LEFT JOIN cms_videos as V1 ON V1.userid=U.id AND V1.image_video='v' AND V1.published=1 AND V1.channelid=0";
            $query .= " LEFT JOIN cms_videos as V2 ON V2.userid=U.id AND V2.image_video='i' AND V2.published=1 AND V2.channelid=0";
            $query .= " LEFT JOIN cms_users_catalogs as C1 ON C1.user_id=U.id AND C1.published=1 AND C1.channelid=0";
            $query .= " $where";
            $query .= " GROUP BY U.id";
            $query .= " ORDER BY $orderby $order";
        }else{
            $query = "SELECT U.* FROM `cms_users` AS U";
            $query .= " $where";
            $query .= " ORDER BY $orderby $order";
        }
                
        if (!is_null($options['limit'])) {
//            $query .= " LIMIT $skip, $nlimit";
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array( "key" => ":Skip",
                                "value" =>$skip,
                                "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit",
                                "value" =>$nlimit,
                                "type" =>"::PARAM_INT");
        }
//        $ret = db_query($query);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
        

//        while ($row = db_fetch_array($ret)) {
//            if ($row['profile_Pic'] == '') {
//                //$row['profile_Pic'] = 'tuber.jpg';
//                $row['profile_Pic'] = 'he.jpg';
//                if ($row['gender'] == 'F') {
//                    $row['profile_Pic'] = 'she.jpg';
//                }
//            }
//            $media[] = $row;
//        }
        $row    = $select->fetchAll();
        
        $media = array();
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $media[] = $row_item;
        }
    } else {
        $query = "SELECT COUNT(U.id) FROM `cms_users` AS U $where"; 

        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $row    = $select->fetch();
        return $row[0];
    }

    return $media;


}
// CODE NOT USED - commented by KHADRA
//function userSearchSolr($srch_options) {
//    global $path;
//    global $CONFIG;
//    require($path . 'vendor/autoload.php');
//    $userVideos = $ct = array();
//    $config = $CONFIG['solr_config'];
//    $client = new Solarium\Client($config);
//    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
//    $query = $client->createSelect();
//    $user_id = userGetID();
//    $searchString = '+title_t1:*' . $term . '* +type:u +(allowed_users:"" OR allowed_users:*|' . $user_id . '|*)';
//    $query->setQuery($searchString);
//    $resultset = $client->select($query);
//    $k = 0;
//    $ret = array();
//    foreach ($resultset as $document) {
//        $ret[$k]['id'] = $document->id;
//        $ret[$k]['FullName'] = $document->title_t1;
//        $ret[$k]['profile_Pic'] = $document->profile_pic;
//        $ret[$k]['RegisteredDate'] = $document->register_date;
//        $k++;
//    }
//    return $ret;
//}

/**
 * checks if the login is valid
 * @param string $username
 * @param string $pswd
 * @return fasle | integer false if not valid or the user's id
 */
function userCheckLogin($username, $pswd) {


    global $dbConn;
	$params = array();  
//    $l_username = strtolower($username);
//
//    $query = "SELECT * FROM cms_users WHERE ( LOWER(YourEmail) = '$l_username' OR LOWER(YourUserName) = '$l_username') AND YourPassword = password('$pswd') AND published <> -2";
//    $ret = db_query($query);
//
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_array($ret);
//
//        return $row['id'];
//    } else {
//        return false;
//    }
    $l_username = strtolower($username);

    $query = "SELECT * FROM cms_users WHERE ( LOWER(YourEmail) = :L_username OR LOWER(YourUserName) = :L_username) AND YourPassword = password(:Pswd) AND published <> -2";
	$params[] = array( "key" => ":L_username", "value" =>$l_username);
	$params[] = array( "key" => ":Pswd", "value" =>$pswd);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    if ($res && $ret != 0) {
        $row    = $select->fetch();        
        return $row['id'];
    } else {
        return false;
    }


}

/**
 * checks if the login is valid
 * @param string $username
 * @param string $pswd
 * @return fasle | integer false if not valid or the user's id
 */
function userReactivate($username, $pswd) {


    global $dbConn;
    global $CONFIG;
    $params  = array();
    $l_username = strtolower($username);
    $query = "SELECT id FROM cms_users WHERE ( LOWER(YourEmail) = :L_username OR LOWER(YourUserName) = :L_username) AND YourPassword = password(:Pswd) AND published = -1";
    $params[] = array( "key" => ":L_username", "value" =>$l_username);
    $params[] = array( "key" => ":Pswd", "value" =>$pswd);
   
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row    = $select->fetch();
        $id = $row['id'];
        $params  = array();
        $queryUpd = "UPDATE `cms_users` SET published = 1 where id = :Id";
        $params[] = array( "key" => ":Id", "value" =>$id);

        $update = $dbConn->prepare($queryUpd);
	PDO_BIND_PARAM($update,$params);
        $res    = $update->execute();
        if ($res) {
            userReactivateEntities($id);
            $userRec = userLogin($username, $pswd, CLIENT_WEB );            
            if ($userRec) {
                userSetSession($userRec['row']);
                $expire = time() + 365 * 24 * 3600;
                $pathcookie = '/';
                $current_channel = $userRec['row']['isChannel'] ? userDefaultChannelGet($userRec['row']['id']) : false;
                if( $current_channel !=false ) $current_channel = $current_channel['channel_url'];
                setcookie("current_channel", $current_channel , $expire, $pathcookie, $CONFIG['cookie_path']);
                if ($keep_me_logged == 1) {
                    setcookie("lt", $userRec['token'], $expire, $pathcookie, $CONFIG['cookie_path']);
                } else {
                    setcookie("lt", $userRec['token'], 0, $pathcookie, $CONFIG['cookie_path']);
                }
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }



}

/**
 * reactivate all user's entities
 * @param integer $user_id the cms_users id
 */
function userReactivateEntities($user_id) {


    global $dbConn;
	$params = array(); 
        $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $query1 = "UPDATE cms_videos SET published=" . MEDIA_READY . " WHERE userid=:User_id AND published=" . MEDIA_DISABLED . "";
    $update1 = $dbConn->prepare($query1);
    PDO_BIND_PARAM($update1,$params);
    $update1->execute();
    $query2 = "UPDATE cms_users_catalogs SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($update2,$params);
    $update2->execute();
    $query3 = "UPDATE cms_users_event SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update3 = $dbConn->prepare($query3);
    PDO_BIND_PARAM($update3,$params);
    $update3->execute();
    $query4 = "UPDATE cms_journals SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update4 = $dbConn->prepare($query4);
    PDO_BIND_PARAM($update4,$params);
    $update4->execute();
    $query5 = "UPDATE cms_social_shares SET published=" . MEDIA_READY . " WHERE from_user=:User_id AND share_type<>" . SOCIAL_SHARE_TYPE_SPONSOR . " AND published=" . MEDIA_DISABLED . "";
    $update5 = $dbConn->prepare($query5);
    PDO_BIND_PARAM($update5,$params);
    $update5->execute();
    $query6 = "UPDATE cms_social_ratings SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update6 = $dbConn->prepare($query6);
    PDO_BIND_PARAM($update6,$params);
    $update6->execute();
    $query7 = "UPDATE cms_social_posts SET published=" . MEDIA_READY . " WHERE (user_id=:User_id OR from_id=:User_id) AND channel_id=0 AND published=" . MEDIA_DISABLED . "";
    $update7 = $dbConn->prepare($query7);
    PDO_BIND_PARAM($update7,$params);
    $update7->execute();
    $query8 = "UPDATE cms_social_likes SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update8 = $dbConn->prepare($query8);
    PDO_BIND_PARAM($update8,$params);
    $update8->execute();
    $query9 = "UPDATE cms_social_comments SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update9 = $dbConn->prepare($query9);
    PDO_BIND_PARAM($update9,$params);
    $update9->execute();
    $query10 = "UPDATE cms_flash SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update10 = $dbConn->prepare($query10);
    PDO_BIND_PARAM($update10,$params);
    $update10->execute();
    $query11 = "UPDATE cms_channel_event_join SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update11 = $dbConn->prepare($query11);
    PDO_BIND_PARAM($update11,$params);
    $update11->execute();
    $query12 = "UPDATE cms_channel_connections SET published=" . MEDIA_READY . " WHERE userid=:User_id AND published=" . MEDIA_DISABLED . "";
    $update12 = $dbConn->prepare($query12);
    PDO_BIND_PARAM($update12,$params);
    $update12->execute();
    $query13 = "UPDATE cms_users_event_join SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update13 = $dbConn->prepare($query13);
    PDO_BIND_PARAM($update13,$params);
    $update13->execute();
    $query14 = "UPDATE cms_users_visited_places SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update14 = $dbConn->prepare($query14);
    PDO_BIND_PARAM($update14,$params);
    $update14->execute();
    $query15 = "UPDATE discover_hotels_reviews SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update15 = $dbConn->prepare($query15);
    PDO_BIND_PARAM($update15,$params);
    $update15->execute();
    $query16 = "UPDATE discover_poi_reviews SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update16 = $dbConn->prepare($query16);
    PDO_BIND_PARAM($update16,$params);
    $update16->execute();
    $query17 = "UPDATE discover_restaurants_reviews SET published=" . MEDIA_READY . " WHERE user_id=:User_id AND published=" . MEDIA_DISABLED . "";
    $update17 = $dbConn->prepare($query17);
    PDO_BIND_PARAM($update17,$params);
    $update17->execute();
    $query18 = "UPDATE cms_social_newsfeed SET published=" . MEDIA_READY . " WHERE ( (user_id=:User_id AND owner_id<>:User_id AND action_type<>" . SOCIAL_SHARE_TYPE_SPONSOR . ") OR (owner_id=:User_id AND COALESCE( channel_id,0)=0) ) AND published=" . MEDIA_DISABLED . "";
    $update18 = $dbConn->prepare($query18);
    PDO_BIND_PARAM($update18,$params);
    $update18->execute();


}

$secret_key = "fakesecretkey";
/**
 * gets a user login credentials
 * @param string $username
 * @param string $pswd
 * @param integer $client_type web,android,iphone so far
 * @return array | false cms_users record if login was ok or false if invalid login
 */
function userLogin($username, $pswd, $client_type, $keep_me_logged) {
    GLOBAL $secret_key;
    $l_username = strtolower($username);


    global $dbConn;
	$params = array();

//    $query = "SELECT * FROM cms_users WHERE ( LOWER(YourEmail) = '$l_username' OR LOWER(YourUserName) = '$l_username') AND YourPassword = password('$pswd') AND published = 1";
//    $ret = db_query($query);
    $query = "SELECT * FROM cms_users WHERE ( LOWER(YourEmail) = :L_username OR LOWER(YourUserName) = :L_username) AND YourPassword = password(:Pswd) AND published = 1";
	$params[] = array( "key" => ":L_username", "value" =>$l_username);
	$params[] = array( "key" => ":Pswd", "value" =>$pswd);
    
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    $res    = $select->fetchAll(PDO::FETCH_ASSOC);
    if ($res && $ret != 0) {
        $row    = $select->fetch();
        $data = time()."_".$row['id'];
        $token = hash('sha512', $secret_key.$data);
        return userToSession($token, $row['id'], $client_type, $keep_me_logged) ? array('token' => $token, 'row' => $row) : false;
        
    } else {
        return false;
    }


}
/**
 * gets a user login credentials
 * @param string $username
 * @param string $pswd
 * @param integer $client_type web,android,iphone so far
 * @return array | false cms_users record if login was ok or false if invalid login
 */
function userLoginFacebook($user_id, $fb_token, $client_type, $keep_me_logged) {
    GLOBAL $secret_key;


    global $dbConn;
	$params = array();

//    $query = "SELECT * FROM cms_users WHERE ( LOWER(YourEmail) = '$l_username' OR LOWER(YourUserName) = '$l_username') AND YourPassword = password('$pswd') AND published = 1";
//    $ret = db_query($query);
    $query = "SELECT * FROM cms_users WHERE  fb_user = :Fb_user AND published = 1";
	$params[] = array( "key" => ":Fb_user", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    $res    = $select->fetchAll(PDO::FETCH_ASSOC);
    if ($res && $ret != 0) {
        $row    = $select->fetch();
        $data = time()."_".$row['id'];
        $token = hash('sha512', $secret_key.$data);
        return userToSession($token, $row['id'], $client_type, $keep_me_logged, $fb_token) ? array('token' => $token, 'row' => $row) : false;
        
    } else {
        return false;
    }
}

/**
 * gets if a user is deactivated
 * @param string $username
 * @param string $pswd
 * @return true | false if user is deactivated
 */
function userIsDeactivated($username, $pswd) {


    global $dbConn;
	$params = array();  
//    $l_username = strtolower($username);
//    $query = "SELECT * FROM cms_users WHERE ( LOWER(YourEmail) = '$l_username' OR LOWER(YourUserName) = '$l_username') AND YourPassword = password('$pswd') AND published = -1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        return true;
//    } else {
//        return false;
//    }
    $l_username = strtolower($username);
    $query = "SELECT * FROM cms_users WHERE ( LOWER(YourEmail) = :L_username OR LOWER(YourUserName) = :L_username) AND YourPassword = password(:Pswd) AND published = -1";
	$params[] = array( "key" => ":L_username", "value" =>$l_username);
	$params[] = array( "key" => ":Pswd", "value" =>$pswd);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        return true;
    } else {
        return false;
    }


}
/**
 * stores a users session and location
 * @param string $uid the unique session id
 * @param integer $user_id the user's id
 * @return boolean true|false 
 */
function userToSession($uid, $user_id, $client_type, $keep_me_logged, $social_token = '') {
    if ($uid == 'undefined')
        return false;


    global $dbConn;
    $params1 = array(); 
    $del_query = "DELETE FROM cms_tubers WHERE uid = :Uid";
    $params1[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $delete = $dbConn->prepare($del_query);
    PDO_BIND_PARAM($delete,$params1);
    $delete->execute();
    if($keep_me_logged == 1){
        $expiry_date = 'DATE_ADD(NOW(), INTERVAL 7 DAY)';
    }
    else{
        $expiry_date = 'DATE_ADD(NOW(), INTERVAL 2 DAY)';
    }
	$params = array();  
    $query = "INSERT INTO cms_tubers (uid, user_id, latitude, longitude, client_type, expiry_date, keep_me_logged, social_token) VALUES (:Uid, :User_id, NULL, NULL, :Client_type, $expiry_date, $keep_me_logged, :Social_token )";
    $params[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Client_type",
                        "value" =>$client_type);
    $params[] = array( "key" => ":Social_token",
                        "value" =>$social_token);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res =  $insert->execute();//echo $res;exit();
    return $res;
}

/**
 * sets the user session values
 * @param array $row the cms_users record
 */
function userSetSession($row, $userid = NULL) {


    global $dbConn;
	$params = array(); 
    if(!tt_global_isset('userInfo')){
        if($userid){
//                $query = "SELECT * FROM cms_users WHERE id = '$userid' AND published = 1";
//                $ret = db_query($query);
                $query = "SELECT * FROM cms_users WHERE id = :Userid AND published = 1";
                $params[] = array( "key" => ":Userid", "value" =>$userid);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res = $select->execute();
                
                $ret    = $select->rowCount();
                if ($res && $ret != 0) {
                    $row    = $select->fetch();
                } else {
                    return false;
                }
        }
//        print_r($row);exit();
        $userInfo = array(
            'id' => $row['id'],
            'YourUserName' => $row['YourUserName'],
            'FullName' => $row['FullName'],
            'fname' => $row['fname'],
            'display_fullname' => $row['display_fullname'],
            'profile_Pic' => $row['profile_Pic'],
            'isChannel' => $row['isChannel'],
            'referrer' => WEB_REFERRER
            //'current_channel' => $row['isChannel'] ? userDefaultChannelGet($row['id']) : false
        );
        tt_global_set('userInfo', $userInfo);


    }
}

/**
 * user get default channel
 * @param integer $user_id
 * @return false|array the cms_channel record or flase if not found
 */
function userDefaultChannelGet($user_id) {


    global $dbConn;
	$params = array(); 
//    $query = "SELECT * FROM cms_channel WHERE owner_id='$user_id' ORDER BY id ASC LIMIT 1";
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0))
//        return false;
//    return db_fetch_assoc($res);
    $query = "SELECT * FROM cms_channel WHERE owner_id=:User_id ORDER BY id ASC LIMIT 1";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res  = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0))
        return false;
    $res    = $select->fetchAll(PDO::FETCH_ASSOC);
    return $res;


}

/**
 * gets the current channel associated with a user
 * return array|false the current channel or false if none found
 */
function userCurrentChannelGet() {
    global $request;
    $current_channel = $request->cookies->get('current_channel',false);
    if( $current_channel!=false ){
        return channelFromURL($current_channel);
    }
    return false;
}

/**
 * resets the current channel associated with a user session
 */
function userCurrentChannelReset() {
    global $CONFIG;
    $expire = time() + 365 * 24 * 3600;
    $pathcookie = '/';
    setcookie("current_channel", false , $expire, $pathcookie, $CONFIG['cookie_path']);
}

/**
 * switches the users currently showing channel
 * @param integer $channel_id the new channel_id
 * @return boolean true | false if operation success or not
 */
function userSwitchChannel($channel_id) {
    if (!userIsLogged()) return false;
    global $CONFIG;
    $new_channel_info = channelFromID($channel_id);
    if ($new_channel_info['owner_id'] == userGetID()) {
        $expire = time() + 365 * 24 * 3600;
        $pathcookie = '/';
        setcookie("current_channel", $new_channel_info['channel_url'] , $expire, $pathcookie, $CONFIG['cookie_path']);        
        return true;
    } else {
        return false;
    }
}

/**
 * get the a user display location
 * @param array $userInfo the cms_users record of the user
 * @return string the location
 */
function userGetLocation($userInfo) {
    $city_id = $userInfo['city_id'];
    $city = getCityName($city_id);
    $location = $city;
    if (($location != '') && ($userInfo['YourCountry'] != 'ZZ'))
        $location .= ', ' . $userInfo['YourCountry'];
    return $location;
}

/**
 * gets a list of poular tubers for the landing page
 * @return array a list of cms_users records 
 */
function getPopularTubers() {
    $options = array(
        'limit' => 56,
        'page' => 0,
        'public' => 1,
        'orderby' => 'profile_views',
        'order' => 'desc',
        'profile_pic' => true
    );
    return userSearch($options);
}

/**
 * gets the link to a users profile
 * @param array $userInfo the user record
 * @return string 
 */
function userProfileLink($userInfo, $is_email=0) {
    $userId = userGetID();
    if (userIsLogged() && ( isset($userInfo['id']) && $userInfo['id'] == $userId) && $is_email==0) {
        return ReturnLink('myprofile');
    } else {
        return ReturnLink('profile/' . $userInfo['YourUserName']);
    }
}

/**
 * returns the user record given his name
 * @param type $username 
 */
function userGetByUsername($username) {


    global $dbConn;
    $l_username = strtolower($username);
    $query = "SELECT * FROM cms_users WHERE LOWER(YourUserName)=:L_username and published=1 LIMIT 1";
    $params[] = array( "key" => ":L_username",
                        "value" =>$l_username);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    
    $ret    = $select->rowCount();
    
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $row_user_info = $select->fetch(PDO::FETCH_ASSOC);
        $row_user_info['profile_empty_pic'] = 0;
        if (strlen($row_user_info['profile_Pic']) == 0) {
            $row_user_info['profile_empty_pic'] = 1;
            $row_user_info['profile_Pic'] = 'he-big.jpg';
            if ($row_user_info['gender'] == 'F') {
                $row_user_info['profile_Pic'] = 'she-big.jpg';
            }
        }
        return $row_user_info;
    }


}

/**
 * register a user
 * @param string $fname
 * @param string $lname
 * @param string $FullName
 * @param string $YourEmail
 * @param string $YourCountry
 * @param string $YourIP
 * @param date $YourBdaySave
 * @param string $YourUserName
 * @param string $YourPassword
 * @return boolean true|false if succees|fail 
 */
function userRegister($FullName, $YourEmail, $YourIP, $YourBdaySave, $YourUserName, $YourPassword, $fname, $lname, $isChannel, $gender, $YourCountry, $default_published = 0 , $from_activate=0) {
    
    global $dbConn;
    $params = array(); 
    //list($fname,$lname) = explode(' ',$FullName,2);
    if ($YourCountry != "") {
        $insert = ",YourCountry";
        $value = ",:Value";
        $params[] = array( "key" => ":Value", "value" =>$YourCountry);
    } else {
        $insert = "";
        $value = "";
    }    
    if($YourUserName=='') $YourUserName = $YourEmail;    
    $InsertUserSQL = "INSERT INTO cms_users(FullName,fname,lname,YourEmail,YourIP,YourBday,YourUserName,YourPassword,RegisteredDate,published,isChannel,gender" . $insert . ") ";
    $InsertUserSQL .= "VALUES(:FullName,:Fname,:Lname,:YourEmail,:YourIP,:YourBdaySave,:YourUserName ,password(:YourPassword),:Date,:Default_published,:IsChannel,:Gender $value )";
    
    $params[] = array( "key" => ":FullName", "value" =>$FullName);
    $params[] = array( "key" => ":Fname", "value" =>$fname);
    $params[] = array( "key" => ":Lname", "value" =>$lname);
    $params[] = array( "key" => ":YourEmail", "value" =>$YourEmail);
    $params[] = array( "key" => ":YourIP", "value" =>$YourIP);
    $params[] = array( "key" => ":YourBdaySave", "value" =>$YourBdaySave);
    $params[] = array( "key" => ":YourUserName", "value" =>$YourUserName);
    $params[] = array( "key" => ":YourPassword", "value" =>$YourPassword);
    $params[] = array( "key" => ":Date", "value" =>date('Y-m-d H:i:s'));
    $params[] = array( "key" => ":Default_published", "value" =>$default_published);
    $params[] = array( "key" => ":IsChannel", "value" =>$isChannel);
    $params[] = array( "key" => ":Gender", "value" =>$gender);
    $insert = $dbConn->prepare($InsertUserSQL);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    if ($res) {
        $usid = $dbConn->lastInsertId();
        AddUserPrivacy($usid);        
        
        return $usid;
    } else {
        return false;
    }


}

function userInviteRequest($user_id, $to, $to_email, $path = '') {
    global $dbConn;
    $params = array();  
    $query = "INSERT INTO cms_invite (from_id,to_name,to_email,path) VALUES (:User_id,:To,:To_email,:Path)";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":To",
                        "value" =>$to);
    $params[] = array( "key" => ":To_email",
                        "value" =>$to_email);
    $params[] = array( "key" => ":Path",
                        "value" =>$path);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    if ($res) {
        return true;
    } else {
        return false;
    }
}

/**
 * gets the number of invites a user has made
 * @param integer $user_id the tuber's id
 * @return integer the number of invites
 */
function userGetInvitesNumber($user_id) {


    global $dbConn;
    $params = array(); 
    $query = "SELECT COUNT(from_id) FROM cms_invite WHERE from_id=:User_id";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();
    $row = $select->fetch();
    return intval($row[0]);


}


/**
 * gets the number o fuser invites
 * @param integer $user_id gets the user's invite records
 * @return array cms_invite records
 */
function userGetInvites($user_id) {
    //$query = "SELECT * FROM cms_invite WHERE from_id='$user_id' ORDER BY invite_ts DESC";


    global $dbConn;
	$params = array();  
    $query = "SELECT ci.*,count(cu.id) AS accepted "
            . "FROM cms_invite AS ci "
            . "LEFT JOIN cms_users AS cu ON cu.YourEmail = ci.to_email "
            . "WHERE ci.from_id=:User_id "
            . "GROUP BY ci.id "
            . "ORDER BY ci.invite_ts DESC";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return array();
    }
    $res    = $select->fetchAll();
    return $res;


}

/**
 * crops the users photo
 * @global array $CONFIG
 * @param string $photo photo file name name
 */
function userCropPhoto($photo) {
    global $CONFIG;

    $path = $CONFIG ['server']['root'] . 'media/tubers';
    $extension = '.'. ShowFileExtension( strtolower($photo) );
    $filename = basename($photo);
    $filename = substr($filename, 0, strlen($filename) - strlen($extension) );
    //$filename = basename($photo,$extension);
    
    //exec('convert -size 105x105 xc:none -fill '.$path."/".$photo.' -draw "circle 50,50 50,1" '.$path.'/crop_'.$filename.'.png');
    //exec("convert $path/thumbStroke.png  +append -quality 72 '$path/crop_$filename.png'");
    $cropped_size = 105;
    $diff = intval((THUMB_SIZE - $cropped_size) / 2);
    $mid = intval($cropped_size / 2) - 1;

    //convert test.jpg -resize 100x100 -background black -gravity center -extent 100x100 output.png
    //exec("convert -size {$ts}x{$ts} xc:none -fill $path/$photo -draw \"circle 50,50 50,3\" $path/crop_$filename.png");
    
    exec("convert -crop {$cropped_size}x{$cropped_size}+$diff+$diff $path/$photo $path/crop_$filename.png");
    exec("convert -size {$cropped_size}x{$cropped_size} xc:none -fill $path/crop_$filename.png -draw \"circle $mid,$mid $mid,4\" $path/crop_$filename.png");
    exec("convert -size {$cropped_size}x{$cropped_size} $path/crop_$filename.png $path/thumbStroke.png  -composite  $path/crop_$filename.png");
}

/**
 * gets all the trip names of a user
 * @param integer $user_id which user
 * @return array the cms_users_trips records
 */
function userGetTrips($user_id) {


    global $dbConn;
    $params = array(); 
    $query = "SELECT * FROM cms_users_trips WHERE user_id=:User_id";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    }
    $ret = array();
    $row    = $select->fetchAll(PDO::FETCH_ASSOC);
     foreach($row as $row_item){
        $ret[] = $row_item['trip_name'];
    }
    return $ret;


}

/**
 * gets the id of a users trip
 * @param integer $user_id the user's id
 * @param string $name the trip's name
 * @return integer the id of the user's trip
 */
function userGetTripID($user_id, $trip_name) {


    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $query = "SELECT id FROM cms_users_trips WHERE user_id=:User_id AND trip_name=:Trip_name";
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Trip_name",
                        "value" =>$trip_name);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        $query = "INSERT INTO cms_users_trips (user_id,trip_name) VALUES (:User_id,:Trip_name)";
        $params2[] = array( "key" => ":User_id",
                             "value" =>$user_id);
        $params2[] = array( "key" => ":Trip_name",
                             "value" =>$trip_name);
        $insert = $dbConn->prepare($query);
        PDO_BIND_PARAM($insert,$params2);
        $insert->execute();
        return $dbConn->lastInsertId();
    } else {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }
}

/**
 * gets a trip name given its id
 * @param integer $trip_id 
 * @param string|false 
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function userGetTripName($trip_id) {
//    $query = "SELECT trip_name FROM cms_users_trips WHERE id='$trip_id'";
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0)) {
//        return false;
//    } else {
//        $row = db_fetch_row($res);
//        return $row[0];
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * deactivates a user and all his media
 * @param integer $user_id the cms_users id
 * @return boolean success or fail
 */
function userDeactivate($user_id) {
    global $dbConn;
    $params = array(); 
    $query = "UPDATE cms_users SET published=-1 WHERE id=:User_id";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    if ($res) {
        userDeactivateEntities($user_id);
        return true;
    } else {
        return false;
    }
}

/**
 * deactivates all user's entities
 * @param integer $user_id the cms_users id
 */
function userDeactivateEntities($user_id) {


    global $dbConn;
	$params = array();  
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $query1  = "UPDATE cms_videos SET published=" . MEDIA_DISABLED . " WHERE userid=:User_id AND published=1";
    $update1 = $dbConn->prepare($query1);
    PDO_BIND_PARAM($update1,$params);
    $update1->execute();
    $query2  = "UPDATE cms_users_catalogs SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($update2,$params);
    $update2->execute();
    $query3  = "UPDATE cms_users_event SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update3 = $dbConn->prepare($query3);
    PDO_BIND_PARAM($update3,$params);
    $update3->execute();
    $query4  = "UPDATE cms_journals SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update4 = $dbConn->prepare($query4);
    PDO_BIND_PARAM($update4,$params);
    $update4->execute();
    $query5  = "UPDATE cms_social_shares SET published=" . MEDIA_DISABLED . " WHERE from_user=:User_id AND share_type<>" . SOCIAL_SHARE_TYPE_SPONSOR . " AND published=1";
    $update5 = $dbConn->prepare($query5);
    PDO_BIND_PARAM($update5,$params);
    $update5->execute();
    $query6  = "UPDATE cms_social_ratings SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update6 = $dbConn->prepare($query6);
    PDO_BIND_PARAM($update6,$params);
    $update6->execute();
    $query7  = "UPDATE cms_social_posts SET published=" . MEDIA_DISABLED . " WHERE (user_id=:User_id OR from_id=:User_id) AND channel_id=0 AND published=1";
    $update7 = $dbConn->prepare($query7);
    PDO_BIND_PARAM($update7,$params);
    $update7->execute();
    $query8  = "UPDATE cms_social_likes SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update8 = $dbConn->prepare($query8);
    PDO_BIND_PARAM($update8,$params);
    $update8->execute();
    $query9  = "UPDATE cms_social_comments SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update9 = $dbConn->prepare($query9);
    PDO_BIND_PARAM($update9,$params);
    $update9->execute();
    $query10  = "UPDATE cms_flash SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update10 = $dbConn->prepare($query10);
    PDO_BIND_PARAM($update10,$params);
    $update10->execute();
    $query11  = "UPDATE cms_channel_event_join SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update11 = $dbConn->prepare($query11);
    PDO_BIND_PARAM($update11,$params);
    $update11->execute();
    $query12  = "UPDATE cms_channel_connections SET published=" . MEDIA_DISABLED . " WHERE userid=:User_id AND published=1";
    $update12 = $dbConn->prepare($query12);
    PDO_BIND_PARAM($update12,$params);
    $update12->execute();
    $query13  = "UPDATE cms_users_event_join SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update13 = $dbConn->prepare($query13);
    PDO_BIND_PARAM($update13,$params);
    $update13->execute();
    $query14  = "UPDATE cms_users_visited_places SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update14 = $dbConn->prepare($query14);
    PDO_BIND_PARAM($update14,$params);
    $update14->execute();
    $query15  = "UPDATE discover_hotels_reviews SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update15 = $dbConn->prepare($query15);
    PDO_BIND_PARAM($update15,$params);
    $update15->execute();
    $query16  = "UPDATE discover_poi_reviews SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update16 = $dbConn->prepare($query16);
    PDO_BIND_PARAM($update16,$params);
    $update16->execute();
    $query17  = "UPDATE discover_restaurants_reviews SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update17 = $dbConn->prepare($query17);
    PDO_BIND_PARAM($update17,$params);
    $update17->execute();
    $query16  = "UPDATE airport_reviews SET published=" . MEDIA_DISABLED . " WHERE user_id=:User_id AND published=1";
    $update16 = $dbConn->prepare($query16);
    PDO_BIND_PARAM($update16,$params);
    $update16->execute();
    $query18  = "UPDATE cms_social_newsfeed SET published=" . MEDIA_DISABLED . " WHERE ( (user_id=:User_id AND owner_id<>:User_id AND action_type<>" . SOCIAL_SHARE_TYPE_SPONSOR . ") OR (owner_id=:User_id AND COALESCE( channel_id,0)=0) ) AND published IN (0,1)";
    $update18 = $dbConn->prepare($query18);
    PDO_BIND_PARAM($update18,$params);
    $update18->execute();
}

/**
 * delete a user and all his media
 * @param integer $user_id the cms_users id
 * @return boolean success or fail
 */
function userDelete($user_id) {
    global $dbConn;
    $params = array();
    $queryUser = "UPDATE cms_users SET published=-2 WHERE id=:User_id"; // Remove user 
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $update = $dbConn->prepare($queryUser);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    if ($res) {// if user is removed
        userDeleteEntities($user_id);
        return true;
    } else {// if user is not removed
        return false;
    }
}

/**
 * delete all user's entities
 * @param integer $user_id the cms_users id
 */
function userDeleteEntities($user_id) {


    global $dbConn;
    $params = array(); 
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $query1 = "UPDATE `cms_friends` SET published = -2 WHERE published = 1 AND `requester_id`=:User_id OR `receipient_id`=:User_id";
    $delete1 = $dbConn->prepare($query1);
    PDO_BIND_PARAM($delete1,$params);
    $delete1->execute(); 
//    $query2  = "DELETE FROM `cms_subscriptions` WHERE `user_id`=:User_id OR `subscriber_id`=:User_id";
    $query2  = "UPDATE `cms_subscriptions` SET published = -2 WHERE published = 1 `user_id`=:User_id OR `subscriber_id`=:User_id";
    $delete2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($delete2,$params);
    $delete2->execute();
    $query3  = "DELETE FROM `cms_users_privacy_extand` WHERE `user_id`=:User_id";
    $delete3 = $dbConn->prepare($query3);
    PDO_BIND_PARAM($delete3,$params);
    $delete3->execute();
    $query4  = "DELETE FROM `cms_videos_temp` WHERE `user_id`=:User_id";
    $delete4 = $dbConn->prepare($query4);
    PDO_BIND_PARAM($delete4,$params);
    $delete4->execute();
    $query5  = "UPDATE cms_videos SET published=" . MEDIA_DELETE . " WHERE userid=:User_id";
    $update1 = $dbConn->prepare($query5);
    PDO_BIND_PARAM($update1,$params);
    $update1->execute();
    $query6  = "UPDATE cms_users_catalogs SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update2 = $dbConn->prepare($query6);
    PDO_BIND_PARAM($update2,$params);
    $update2->execute();
    $query7  = "UPDATE cms_users_event SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update3 = $dbConn->prepare($query7);
    PDO_BIND_PARAM($update3,$params);
    $update3->execute();
    $query8  = "UPDATE cms_journals SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update4 = $dbConn->prepare($query8);
    PDO_BIND_PARAM($update4,$params);
    $update4->execute();
    $query9  = "UPDATE cms_social_shares SET published=" . MEDIA_DELETE . " WHERE from_user=:User_id AND share_type<>" . SOCIAL_SHARE_TYPE_SPONSOR . "";
    $update5 = $dbConn->prepare($query9);
    PDO_BIND_PARAM($update5,$params);
    $update5->execute();
    $query10 = "UPDATE cms_social_ratings SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update6 = $dbConn->prepare($query10);
    PDO_BIND_PARAM($update6,$params);
    $update6->execute();
    $query11 = "UPDATE cms_social_posts SET published=" . MEDIA_DELETE . " WHERE (user_id=:User_id' OR from_id=:User_id) AND channel_id=0";
    $update7 = $dbConn->prepare($query11);
    PDO_BIND_PARAM($update7,$params);
    $update7->execute();
    $query12 = "UPDATE cms_social_likes SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update8 = $dbConn->prepare($query12);
    PDO_BIND_PARAM($update8,$params);
    $update8->execute();
    $query13 = "UPDATE cms_social_comments SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update9 = $dbConn->prepare($query13);
    PDO_BIND_PARAM($update9,$params);
    $update9->execute();
    $query14  = "UPDATE cms_flash SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update10 = $dbConn->prepare($query14);
    PDO_BIND_PARAM($update10,$params);
    $update10->execute();
    $query15  = "UPDATE cms_channel_event_join SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update11 = $dbConn->prepare($query15);
    PDO_BIND_PARAM($update11,$params);
    $update11->execute();
    $query16  = "UPDATE cms_channel_connections SET published=" . MEDIA_DELETE . " WHERE userid=:User_id";
    $update12 = $dbConn->prepare($query16);
    PDO_BIND_PARAM($update12,$params);
    $update12->execute();
    $query17  = "UPDATE cms_users_event_join SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update13 = $dbConn->prepare($query17);
    PDO_BIND_PARAM($update13,$params);
    $update13->execute();
    $query18  = "UPDATE cms_users_visited_places SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update14 = $dbConn->prepare($query18);
    PDO_BIND_PARAM($update14,$params);
    $update14->execute();
    $query19  = "UPDATE discover_hotels_reviews SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update15 = $dbConn->prepare($query19);
    PDO_BIND_PARAM($update15,$params);
    $update15->execute();
    $query20  = "UPDATE discover_poi_reviews SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update16 = $dbConn->prepare($query20);
    PDO_BIND_PARAM($update16,$params);
    $update16->execute();
    $query21  = "UPDATE discover_restaurants_reviews SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update17 = $dbConn->prepare($query21);
    PDO_BIND_PARAM($update17,$params);
    $update17->execute();
    $query20  = "UPDATE airport_reviews SET published=" . MEDIA_DELETE . " WHERE user_id=:User_id";
    $update16 = $dbConn->prepare($query20);
    PDO_BIND_PARAM($update16,$params);
    $update16->execute();
    $query22 = "UPDATE cms_social_newsfeed SET published=" . MEDIA_DELETE . " WHERE ( (user_id=:User_id AND owner_id<>:User_id AND action_type<>" . SOCIAL_SHARE_TYPE_SPONSOR . ") OR (owner_id=:User_id AND COALESCE( channel_id,0)=0) )";
    $update18 = $dbConn->prepare($query22);
    PDO_BIND_PARAM($update18,$params);
    $update18->execute();
    $query23  = "UPDATE cms_social_newsfeed SET published=" . MEDIA_DELETE . " WHERE (user_id=:User_id OR owner_id=:User_id) AND COALESCE( channel_id,0)=0";
    $update18 = $dbConn->prepare($query23);
    PDO_BIND_PARAM($update18,$params);
    $update18->execute();
}

/**
 * gets the filename of a user's cropped photo given his main photo
 * @param string $profile_pic the user's main photo
 * @return string the filename of the user's cropped photo
 */
function userCroppedPhoto($profile_pic) {
    $pinfo = pathinfo($profile_pic);
    return 'crop_' . $pinfo['filename'] . '.png';
}

/**
 * checks if a user has permission to view a media file
 * @param integer $user_id the user id
 * @param array $vinfo the cms_videos record
 * @return boolean true|false
 */
function userPermittedForMedia($user_id, $vinfo, $entity_type) {
    if ($entity_type == SOCIAL_ENTITY_MEDIA) {
        return checkUserPrivacyExtand($vinfo['userid'], $user_id, $vinfo['id'], $entity_type);
    } else {
        return checkUserPrivacyExtand($vinfo['user_id'], $user_id, $vinfo['id'], $entity_type);
    }
}

/**
 * adds a catalog to a user
 * @param integer $user_id which cms_users 
 * @param string $catalog_name the catalog's name
 * @return boolean|integer false or the new catalog id
 */
function userCatalogAdd($user_id, $catalog_name) {
    return userCatalogAddDetailed(array('user_id' => $user_id, 'catalog_name' => $catalog_name));
}

/**
 * adds a catalog to a user
 * @param array $all_cols. all the table columns. including: <br/>
 * <b>user_id</b> integer. the catalog owner. required<br/>
 * <b>catalog_name</b> string. the catalog name. required<br/>
 * <b>placetakenat</b> string. the place taken at.<br/>
 * <b>location_id</b> integer. the cms_locations id.<br/>
 * <b>cityid</b> integer. the webgeocities id. required<br/>
 * <b>cityname</b> integer. the webgeocities id.<br/>
 * <b>country</b> char(2). the country code id.<br/>
 * <b>latitude</b> double. the latitude.<br/>
 * <b>longitude</b> double. the longitude.<br/>
 * <b>description</b> string. the small description.<br/>
 * <b>keywords</b> string. the keywords.<br/>
 * <b>is_public</b> integer. specifies who can view the album.<br/>
 * <b>category</b> integer. specifies the albums category.<br/>
 * <b>channelid</b> integer. specifies the channelid.<br/>
 * @return boolean|integer false or the new catalog id
 */
function userCatalogAddDetailed($all_cols) {

    $latitude = (isset($all_cols['latitude']) && !is_null($all_cols['latitude']) ) ? doubleval($all_cols['latitude']) : NULL;
    $longitude = (isset($all_cols['longitude']) && !is_null($all_cols['longitude']) ) ? doubleval($all_cols['longitude']) :NULL;
    $user_id = isset($all_cols['user_id']) ? intval($all_cols['user_id']) : 0;
    $catalog_name = isset($all_cols['catalog_name']) ? $all_cols['catalog_name'] : '';
    $placetakenat = isset($all_cols['placetakenat']) ? $all_cols['placetakenat'] : '';
    $location_id = isset($all_cols['location_id']) ? intval($all_cols['location_id']) :0;
    $cityid = isset($all_cols['cityid']) ? intval($all_cols['cityid']) :0;
    $cityname = isset($all_cols['cityname']) ? $all_cols['cityname'] : '';
    $country = isset($all_cols['country']) ? $all_cols['country'] : '';
    $description = isset($all_cols['description']) ? $all_cols['description'] : '';
    $keywords = isset($all_cols['keywords']) ? $all_cols['keywords'] : '';
    $is_public = isset($all_cols['is_public']) ? intval($all_cols['is_public']) : USER_PRIVACY_PRIVATE;
    $category = isset($all_cols['category']) ? intval($all_cols['category']) : 0;
    $channelid = isset($all_cols['channelid']) ? intval($all_cols['channelid']) : 0;

    if (strlen($country) != 2)
        $country = '';    

    global $dbConn;
	$params  = array();  
	$params2 = array();  

    //$user_id,$catalog_name,$category
    $vpath = date('Y/W/');
    $query = "INSERT
					INTO
				cms_users_catalogs
					(user_id,catalog_name,vpath,description,
					placetakenat,location_id,
					cityid,cityname,country,
					latitude,longitude,
					keywords,is_public,category,channelid)
				VALUES
					(:User_id,:Catalog_name,:Vpath,:Description,
					:Placetakenat,:Location_id,
					:Cityid,:Cityname,:Country,
					:Latitude,:Longitude,
					:Keywords,:Is_public,:Category,:Channelid
					)";
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Catalog_name", "value" =>$catalog_name);
	$params[] = array( "key" => ":Vpath", "value" =>$vpath);
	$params[] = array( "key" => ":Description", "value" =>$description);
	$params[] = array( "key" => ":Placetakenat", "value" =>$placetakenat);
	$params[] = array( "key" => ":Location_id", "value" =>$location_id);
	$params[] = array( "key" => ":Cityid", "value" =>$cityid);
	$params[] = array( "key" => ":Cityname", "value" =>$cityname);
	$params[] = array( "key" => ":Country", "value" =>$country);
	$params[] = array( "key" => ":Latitude", "value" =>$latitude);
	$params[] = array( "key" => ":Longitude", "value" =>$longitude);
	$params[] = array( "key" => ":Keywords", "value" =>$keywords);
	$params[] = array( "key" => ":Is_public", "value" =>$is_public);
	$params[] = array( "key" => ":Category", "value" =>$category);
	$params[] = array( "key" => ":Channelid", "value" =>$channelid);

    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res   = $insert->execute();

    if ($res) {
        $cat_id = $dbConn->lastInsertId();
        $all_cols['id'] = $cat_id;
        $album_url = albumToURL($all_cols);
        $query = "UPDATE cms_users_catalogs SET album_url=:Album_url WHERE id=:Cat_id";
	$params2[] = array( "key" => ":Album_url",
                             "value" =>$album_url);
	$params2[] = array( "key" => ":Cat_id",
                             "value" =>$cat_id);
        $update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params2);
        $update->execute();

        newsfeedAdd($user_id, $cat_id, SOCIAL_ACTION_UPLOAD, $cat_id, SOCIAL_ENTITY_ALBUM, USER_PRIVACY_PUBLIC, $channelid);

        return $cat_id;
    } else {
        return false;
    }
}

/**
 * converts a album name to url
 * @param array $vinfo a cms_users_catalogs record
 * @return string the url of the album
 */
function albumToURL($vinfo) {

    $url = remove_accents($vinfo['catalog_name']);
    //$url = strtolower( $url );
    $url = str_replace(' ', '-', $url);
    $url = preg_replace('/[^a-z0-9A-Z\-]/', '', $url);
    $url = str_replace('--', '-', $url);

    $url = substr($url, 0, 80);

    if ($url[strlen($url) - 1] != '-')
        $url = $url . '-';

    $url = $url . $vinfo['id'];

    return $url;
}

/**
 * gets the cms_users_catalogs record given the url
 * @param string $url
 * @return mixed false if no record or the cms_users_catalogs record. 
 */
function albumFromURL($url) {


    global $dbConn;
	$params = array();  
//    $query = "SELECT * FROM cms_users_catalogs WHERE album_url='$url'";
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0)) {
//        return false;
//    } else {
//        $row = db_fetch_array($res);
//        return $row;
//    }
    $query = "SELECT * FROM cms_users_catalogs WHERE album_url=:Url";
	$params[] = array( "key" => ":Url", "value" =>$url);
        
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $row = $select->fetch();
        return $row;
    }


}

/**
 * builds a users catalog into book format
 * @param integer $user_id which cms_users 
 * @param string $catalog_id the catalog's id
 * @return boolean true|false or success|fail
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function userCatalogBuild($user_id, $catalog_id) {
//    $query = "SELECT * FROM cms_users_catalogs WHERE id='$catalog_id' AND user_id='$user_id'";
//    if (db_query($query)) {
//
//        $media_files = mediaSearch(array('limit' => 100, 'userid' => $user_id, 'catalog_id' => $catalog_id, 'type' => 'i'));
//
//        $items = array();
//        foreach ($media_files as $media) {
//            $items[] = array('image' => $media['fullpath'] . $media['name'], 'text' => $media['title']);
//        }
//
//        $options = array('output_path' => catalogGetPath($journal_record),
//            'output_name' => $catalog_id,
//            'background' => 'media/images/journal_book_bg.jpg',
//            'cover_page' => 'media/images/journal_cover_page.png',
//            'items' => $items);
//
//        include('book.php');
//        bookBuild($options);
//    } else {
//        return false;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * gets the path to the journal files
 * @global type $CONFIG
 * @param array $journal_record a cms_journals record to get its path
 * @return string the path to the journal files 
 */
function catalogGetPath($catalog_record) {
    global $CONFIG;
    return $CONFIG['catalog']['outputPath'] . $catalog_record['vpath'] . $catalog_record['id'] . '/';
}

/**
 * registers a view on a catalog
 * @param integer $cat_id which catalog 
 * @return boolean true|false or if success|fail
 */
function userCatalogView($cat_id) {
    global $dbConn;
    $params = array(); 
    $query = "UPDATE cms_users_catalogs SET nb_views= nb_views+1 WHERE id=:Cat_id";
	$params[] = array( "key" => ":Cat_id",
                            "value" =>$cat_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    if (!$res)
        return false;

    return true;
}

/**
 * edits a catalog
 * @param integer $user_id which cms_users 
 * @param string $catalog_name the catalog's name
 * @param string $placetakenat the catalog's placetaken at
 * @return boolean|integer false or the new catalog id
 */
function userCatalogEdit($catalog_id, $catalog_name, $placetakenat) {
    global $dbConn;
	$params = array();
    $query = "UPDATE cms_users_catalogs SET catalog_name=:Catalog_name,placetakenat=:Placetakenat WHERE id=:Catalog_id";
	$params[] = array( "key" => ":Catalog_name",
                            "value" =>$catalog_name);
	$params[] = array( "key" => ":Placetakenat",
                            "value" =>$placetakenat);
	$params[] = array( "key" => ":Catalog_id",
                            "value" =>$catalog_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * edits a catalog
 * @param integer $catalog_id which cms_users_catalogs 
 * @param integer $can_comment  1 | 0 
 * @param integer $can_share  1 | 0 
 * @return boolean|integer false or the new catalog id
 */
function userCatalogEditCommentShare($catalog_id, $can_comment, $can_share) {
    global $dbConn;
	$params = array();
    $query = "UPDATE cms_users_catalogs SET can_comment=:Can_comment,can_share=:Can_share WHERE id=:Catalog_id";
	$params[] = array( "key" => ":Can_comment", "value" =>$can_comment);
	$params[] = array( "key" => ":Can_share", "value" =>$can_share);
	$params[] = array( "key" => ":Catalog_id", "value" =>$catalog_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * adds a catalog to a user
 * @param array $all_cols. all the table columns. including: <br/>
 * <b>catalog_name</b> string. the catalog name. required<br/>
 * <b>placetakenat</b> string. the place taken at.<br/>
 * <b>location_id</b> integer. the cms_locations id.<br/>
 * <b>cityid</b> integer. the webgeocities id. required<br/>
 * <b>cityname</b> integer. the webgeocities id.<br/>
 * <b>country</b> char(2). the country code id.<br/>
 * <b>latitude</b> double. the latitude.<br/>
 * <b>longitude</b> double. the longitude.<br/>
 * <b>description</b> string. the small description.<br/>
 * <b>keywords</b> string. the keywords.<br/>
 * <b>is_public</b> integer. specifies who can view the album.<br/>
 * <b>category</b> integer. specifies the albums category.<br/>
 * @return boolean false or the new catalog id
 */
function userCatalogEditDetailed($all_cols) {
    global $dbConn;
    $query = "UPDATE cms_users_catalogs SET ";
    $i=0;
    foreach ($all_cols as $col_name => $col_val) {
        $val = null;
        switch ($col_name) {
            case 'latitude':
            case 'longitude':
                $val = !is_null($col_val) ? doubleval($col_val) : NULL;
                break;
            case 'location_id':
            case 'cityid':
            case 'category':
                $val = intval($col_val);
                if ($val == 0)
                    $val = NULL;
                break;
            case 'cityname':
            case 'keywords':
            case 'description':
            case 'catalog_name':
            case 'placetakenat':
            case 'is_public':
                $val = $col_val;
                break;
            case 'country':
                $val = (strlen($col_val) == 2) ? $col_val : NULL;
                break;
            default:
                break;
        }

        if ($col_name == 'id')
            continue;
        if (is_null($val))
            continue;

        $query .= " $col_name = :Val$i,";        
        $params[] = array( "key" => ":Val$i", "value" => $val);
        $i++;
    }
    $query = trim($query, ',');
//    $query .= " WHERE id='{$all_cols['id']}'";
    $query .= " WHERE id=:Id";
    $params[] = array( "key" => ":Id", "value" => $all_cols['id']);
   
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * deletes a users catalog
 * @param integer $catalog_id cms_users_catalogs id
 * @return boolean true|false
 */
function userCatalogDelete($catalog_id) {


    global $dbConn;
	$params  = array(); 
	$params2 = array(); 
	$params3 = array(); 
	$params4 = array(); 
	$params5 = array(); 
    
    $query = "SELECT user_id FROM cms_users_catalogs WHERE id=:Catalog_id";
    $params[] = array( "key" => ":Catalog_id", "value" =>$catalog_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    }
    $row = $select->fetch();
    $user_id = $row[0];
    
    $status = true;

    newsfeedDeleteAll($catalog_id, SOCIAL_ENTITY_ALBUM);

    socialCommentsDelete($catalog_id, SOCIAL_ENTITY_ALBUM);

    socialLikesDelete($catalog_id, SOCIAL_ENTITY_ALBUM);

    socialRatesDelete($catalog_id, SOCIAL_ENTITY_ALBUM);

    if (deleteMode() == TT_DEL_MODE_PURGE) {
        $query = "DELETE FROM cms_users_catalogs WHERE id=:Catalog_id";
        $params2[] = array( "key" => ":Catalog_id", "value" =>$catalog_id);
        $delete = $dbConn->prepare($query);
	PDO_BIND_PARAM($delete,$params2);
        $delete->execute();
        if ($delete) {
            $query = "DELETE FROM cms_videos_catalogs WHERE catalog_id=:Catalog_id";
            $params3[] = array( "key" => ":Catalog_id", "value" =>$catalog_id);
            $delete = $dbConn->prepare($query);
            PDO_BIND_PARAM($delete,$params3);
            $delete->execute();
        } else {
            $status = false;
        }
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query = "UPDATE cms_users_catalogs SET published=" . TT_DEL_MODE_FLAG . " WHERE id=:Catalog_id";
        $params4[] = array( "key" => ":Catalog_id", "value" =>$catalog_id);
        $update = $dbConn->prepare($query);
        PDO_BIND_PARAM($update,$params4);
        $update->execute();
        if ($update) {
            $query = "DELETE FROM cms_videos_catalogs WHERE catalog_id=:Catalog_id";
            $params5[] = array( "key" => ":Catalog_id","value" =>$catalog_id);
            $delete = $dbConn->prepare($query);
            PDO_BIND_PARAM($delete,$params5);
            $delete->execute();
        } else {
            $status = false;
        }
    }

    return $status;
}

/**
 * returns the like value that a user has invoked on a catalog or fale of not liked/disliked
 * @param integer $user_id the cms_users id
 * @param integer $catalog_id the cms_users_catalogs id
 * @return false|integer the like value if exists or false if not exists 
 */
function userCatalogLiked($user_id, $catalog_id) {
    return socialLiked($user_id, $catalog_id, SOCIAL_ENTITY_ALBUM);
}

/**
 * a user like a catalog
 * @param integer $user_id  the cms_users id
 * @param integer $catalog_id the cms_users_catalogs id
 * @param integer $like_value 1 if like or -1 if dislike
 * @return boolean true|false if sucess failed
 */
function userCatalogLike($user_id, $catalog_id, $like_value) {

    if (($like_value != -1) && ($like_value != 1))
        return false;
    $old_like = userCatalogLiked($user_id, $catalog_id);
    if ($old_like == false) {
        socialLikeAdd($user_id, $catalog_id, SOCIAL_ENTITY_ALBUM, $like_value, null);
    } else if ($old_like == $like_value) {
        socialLikeEdit($user_id, $catalog_id, SOCIAL_ENTITY_ALBUM, $like_value);
    }
}

/**
 * gets all user catalogs given the search options, options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>skip</b>: specific number of records skip. default 0<br/>
 * <b>channelid</b>: which channel do the albums belong to. no default<br/>
 * <b>id</b>: album id<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id' or similarity<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>date_from</b>: get records newer than this one's date. default null<br/>
 * <b>date_to</b>: get records older than this one's date. default null<br/>
 * <b>published</b>: published status of record. default 1. null => doesnt matter.<br/>
 * <b>n_results</b>: gets the number of results rather than the rows. default false.
 * <b>is_owner</b>: integer. 0 => not owner (get all media for this user related to the privacy ) , 1 => owner(it does check privacy for the user). default 0.<br/>
 * @param array $srch_options. the search options
 * @return array a list of cms_users_catalogs records or the number of records
 */
function userCatalogSearch($srch_options) {


    global $dbConn;
    $params  = array();
    $params2 = array();

    $default_opts = array(
        'limit' => 1000,
        'page' => 0,
        'skip' => 0,
        'is_owner' => 0,
        'user_id' => null,
        'id' => null,
        'search_string' => null,
        'orderby' => 'id',
        'order' => 'a',
        'n_results' => false,
        'published' => 1,
        'is_public' => null,
        'date_from' => null,
        'date_to' => null,
        'channelid' => null
    );

    $options = array_merge($default_opts, $srch_options);

    if (!is_null($options['search_string']) && (strlen($options['search_string']) == 0)) {
        $options['search_string'] = null;
    }

    $nlimit = intval($options['limit']);
    $skip = (intval($options['page']) * $nlimit) + intval($options['skip']);

    $where = '';
    if (is_null($options['user_id'])) {
        return false;
    } else {
        if ($where != '')
            $where .= " AND ";
//        $where .= " user_id='{$options['user_id']}' ";
        $where .= " user_id=:User_id ";
        $params[] = array( "key" => ":User_id", "value" =>$options['user_id']);
    }

    if (!is_null($options['is_public'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " is_public = :Is_public ";
        $params[] = array( "key" => ":Is_public", "value" =>$options['is_public']);
    }

    if (!is_null($options['published'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " published='{$options['published']}' ";
        $where .= " published=:Published ";
        $params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if (!is_null($options['id'])) {
        if ($where != '') $where .= " AND ";
        $where .= " id=:Id ";
        $params[] = array( "key" => ":Id", "value" =>$options['id']);
    }

    if (!is_null($options['date_from'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " DATE(catalog_ts) >= '{$options['date_from']}' ";
        $where .= " DATE(catalog_ts) >= :Date_from ";
        $params[] = array( "key" => ":Date_from", "value" =>$options['date_from']);
    }

    if (!is_null($options['date_to'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " DATE(catalog_ts) <= '{$options['date_to']}' ";
        $where .= " DATE(catalog_ts) <= :Date_to ";
        $params[] = array( "key" => ":Date_to", "value" =>$options['date_to']);
    }

    if (is_null($options['channelid'])) {
        if ($where != '')
            $where .= " AND ";
        $where .= " channelid='0' ";
    }else {
        if ($where != '')
            $where .= " AND ";
//        $where .= " channelid='{$options['channelid']}' ";
        $where .= " channelid=:Channelid ";
        $params[] = array( "key" => ":Channelid", "value" =>$options['channelid']);
    }
    if (intval($options['is_owner']) == 0) {
        if ($where != '')
            $where .= " AND ";
        $where .= " EXISTS (SELECT VC.id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=A.id) ";
    }
    if (userIsLogged()) {
        if (intval($options['is_owner']) == 0) {
            $searcher_id = userGetID();
            $friends = userGetFreindList($searcher_id);
            $friends_ids = array($searcher_id);
            foreach ($friends as $freind) {
                $friends_ids[] = $freind['id'];
            }
            if (count($friends_ids) != 0) {
                if ($where != '')
                    $where .= " AND ";
                $public = USER_PRIVACY_PUBLIC;
                $private = USER_PRIVACY_PRIVATE;
                $selected = USER_PRIVACY_SELECTED;
                $community = USER_PRIVACY_COMMUNITY;
                $privacy_where = '';

                $where .= "CASE";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1  LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1 AND PR.kind_type= :Community AND PR.user_id IN (".implode(',', $friends_ids).") LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1 AND PR.kind_type= :Private AND PR.user_id= :Searcher_id LIMIT 1 ) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1 AND ( ( FIND_IN_SET( :Argument17 , CONCAT( PR.kind_type ) ) AND PR.user_id IN (".implode(',', $friends_ids).") ) OR ( FIND_IN_SET( :Argument23 , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                $where .= " ELSE 0 END ";
                
                $params[] = array( "key" => ":Public", "value" =>$public);
                $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);
                $params[] = array( "key" => ":Community", "value" =>$community);                
                $params[] = array( "key" => ":Private", "value" =>$private);
                $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);
                $params[] = array( "key" => ":Argument17", "value" =>$community);
                $params[] = array( "key" => ":Argument23", "value" =>$searcher_id);
            }
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1 AND PR.kind_type= :Public LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=A.id AND PR.entity_type=" . SOCIAL_ENTITY_ALBUM . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
        $params[] = array( "key" => ":Public", "value" =>$public);
    }

    if (!is_null($options['search_string'])) {
        $search_strings = explode(' ', $options['search_string']);
        $i=0;
        foreach ($search_strings as $in_search_string) {

            $search_string = trim(strtolower($in_search_string));
            $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);

            if (in_array($search_string, $searched))
                continue;

            $searched[] = " LOWER(catalog_name) LIKE :Searched$i ";
            $params[] = array( "key" => ":Searched$i", "value" => '%'.$search_string.'%' );
            $i++;
        }

        if ($where != '') $where .= " AND ";
        $where .= '(' . implode(' OR ', $searched) . ')';
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    if ($options['n_results'] == false) {
//        $query = "SELECT * FROM cms_users_catalogs AS A WHERE $where ORDER BY $orderby $order LIMIT $skip, $nlimit";  
        if($nlimit > 0){
            $query = "SELECT * FROM cms_users_catalogs AS A WHERE $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit"; 
            $params[] = array( "key" => ":Skip", "value" =>$skip,"type" =>"::PARAM_INT" );
            $params[] = array( "key" => ":Nlimit", "value" =>$nlimit,"type" =>"::PARAM_INT" );   
        }
        else{
            $query = "SELECT * FROM cms_users_catalogs AS A WHERE $where ORDER BY $orderby $order"; 
        }
          
//        $res = db_query($query);
        
        $select = $dbConn->prepare($query);        
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        $row=array();
        if ($res && $ret != 0) {
//            while ($row = db_fetch_assoc($res)) {
//                $ret[] = $row;
//            }
            $row    = $select->fetchAll(PDO::FETCH_ASSOC);
        }
        return $row;
    } else {
//        $query = "SELECT COUNT(A.id) FROM `cms_users_catalogs` AS A WHERE $where";
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
        $query = "SELECT COUNT(A.id) FROM `cms_users_catalogs` AS A WHERE $where";
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $select->execute();
        
        $row = $select->fetch();
        
        $n_results = $row[0];
        return $n_results;
    }


}

function userCatalogRelatedSolr($album_info, $limit = 5, $start = 0) {
    global $CONFIG;
    $t = str_replace(':', ' ', $album_info['catalog_name']);
    $c = str_replace(':', ' ', $album_info['category']);
    $k = str_replace(':', ' ', $album_info['keywords']);
    $id = $album_info['id'];
    //require ('../../vendor/autoload.php');
    
    include_once $CONFIG['server']['root'] . 'vendor/autoload.php';
    
    $userVideos = $ct = array();
    $config = $CONFIG['solr_config'];

    $client = new Solarium\Client($config);
    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    $query = $client->createSelect();

    $helper = $query->getHelper();
    $t = $helper->escapeTerm($t);
    $c = $helper->escapeTerm($t);
    $k = $helper->escapeTerm($t);

    $searchString = "+is_public:2 +type:a";
    $searchString .= " -id:$id";
    $searchString .= " +( (title_t1:$t $c $k) (city_name_accent:$t $c $k) (description_t1:$t $c $k ) )"; //keywords:$t $c $k
    $query->setQuery($searchString);

    $query->setStart($start * $limit)->setRows($limit);
    $resultset = $client->select($query);
    $total = $resultset->getNumFound();
    $k = 0;
    $matches = 0;
    $userVideos['media']=array();
    foreach ($resultset as $document) {
        $VideoInfo_data = userCatalogDefaultMediaGet($document->id);
        if (!$VideoInfo_data)
            continue;
        $userVideos['media'][$k]['id'] = $document->id;
        $userVideos['media'][$k]['catalog_name'] = $document->title_t1;
        $userVideos['media'][$k]['like_value'] = $document->up_votes;
        $userVideos['media'][$k]['description'] = $document->description_t1;
        $userVideos['media'][$k]['rating'] = $document->rating;
        $userVideos['media'][$k]['userid'] = $document->userid;
        $userVideos['media'][$k]['album_url'] = $document->album_url;
        $userVideos['media'][$k]['nb_views'] = $document->nb_views;
        $userVideos['media'][$k]['channelid'] = $document->channelid;
        $k++;
        $matches++;
    }
    $userVideos['total'] = $total;
    $userVideos['matches'] = $matches;
    return $userVideos;
}

/**
 * gets a catalog record
 * @param integer $catalog_id 
 * @return array|false if not found the cms_users_catalogs record
 */
function userCatalogGet($catalog_id) {


    global $dbConn;
    $params = array();
    $userCatalogGet =   tt_global_get('userCatalogGet');
    if(isset($userCatalogGet[$catalog_id]) && $userCatalogGet[$catalog_id]!=''){
        return $userCatalogGet[$catalog_id];
    }
    
//    $query = "SELECT * FROM cms_users_catalogs WHERE id='$catalog_id'";
//    $res = db_query($query);
//    if ($res && db_num_rows($res) != 0) {
//        return db_fetch_assoc($res);
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_users_catalogs WHERE id=:Catalog_id";
    $params[] = array( "key" => ":Catalog_id",
                        "value" =>$catalog_id);
    $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        $userCatalogGet[$catalog_id]  =   $row;
        return $row;
    } else {
        $userCatalogGet[$catalog_id]  =   false;
        return false;
    }


}

/*
 * gets a catalog's owner
 * @param integer $catalog_id 
 * @return integer|false the owner id or false if now found
 */

function userCatalogOwner($catalog_id) {


    global $dbConn;
    $params = array(); 
//    $query = "SELECT * FROM cms_users_catalogs WHERE id='$catalog_id'";
//    $res = db_query($query);
//    if ($res && db_num_rows($res) != 0) {
//        $row = db_fetch_assoc($res);
//        return $row['user_id'];
//    } else {
//        return false;
//    }
    $query = "SELECT * FROM cms_users_catalogs WHERE id=:Catalog_id";
    $params[] = array( "key" => ":Catalog_id",
                        "value" =>$catalog_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res =$select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row['user_id'];
    } else {
        return false;
    }


}

/**
 * adds a media file to a user catalog 
 * @param integer $user_id the cms_users id
 * @param integer $media_id the cms_videos id
 * @param integer $catalog_id the cms_users_catalogs id
 * @return boolean true|false
 */
function userCatalogAddMedia($user_id, $media_id, $catalog_id) {


    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array(); 
    $params4 = array(); 
    $params5 = array(); 
    $params6 = array(); 

    $query = "SELECT userid FROM cms_videos WHERE id=:Media_id AND userid=:User_id";
    $params[] = array( "key" => ":Media_id",
                        "value" =>$media_id);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if (!$res || ($ret == 0))
        return false;

    //the media file is already part of this catalog
    $query = "SELECT catalog_id FROM cms_videos_catalogs WHERE catalog_id=:Catalog_id AND video_id=:Media_id";
    $params2[] = array( "key" => ":Catalog_id",
                         "value" =>$catalog_id);
    $params2[] = array( "key" => ":Media_id",
                         "value" =>$media_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $select->execute();
    $ret    = $select->rowCount();
    if (!$select || ($ret == 1))
        return false;

    $query = "DELETE FROM cms_videos_catalogs WHERE video_id=:Media_id";
    $params3[] = array( "key" => ":Media_id",
                         "value" =>$media_id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params3);
    $delete->execute();

    $query = "INSERT INTO cms_videos_catalogs (catalog_id,video_id) VALUES (:Catalog_id,:Media_id)";
    $params4[] = array( "key" => ":Catalog_id",
                         "value" =>$catalog_id);
    $params4[] = array( "key" => ":Media_id",
                         "value" =>$media_id);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params4);
    $res =$insert->execute();
    if ($res) {
        $query = "SELECT COUNT(id) FROM cms_videos_catalogs WHERE catalog_id=:Catalog_id ";
        $params5[] = array( "key" => ":Catalog_id",
                             "value" =>$catalog_id);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params5);
        $select->execute();
        $row = $select->fetch();
        $count = $row[0];
        $query2 = "UPDATE cms_users_catalogs SET n_media=:Count WHERE id=:Catalog_id";
        $params6[] = array( "key" => ":Count",
                             "value" =>$count);
        $params6[] = array( "key" => ":Catalog_id",
                             "value" =>$media_id);
        $update = $dbConn->prepare($query2);
        PDO_BIND_PARAM($update,$params6);
        $update->execute();
        return true;
    } else {
        return false;
    }
}

/**
 * deletes a media from a user catalog 
 * @param integer $user_id the cms_users id
 * @param integer $media_id the cms_videos id
 * @param integer $catalog_id the cms_users_catalogs id
 * @return boolean true|false
 */
function userCatalogDeleteMedia($user_id, $media_id, $catalog_id) {


    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array();
    $query = "DELETE FROM cms_videos_catalogs WHERE catalog_id=:Catalog_id AND video_id=:Media_id AND EXISTS (SELECT id FROM cms_videos WHERE id=:Media_id AND userid=:User_id) ";
        $params[] = array( "key" => ":Catalog_id",
                            "value" =>$catalog_id);
        $params[] = array( "key" => ":Media_id",
                            "value" =>$media_id);
        $params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res=$delete->execute();
    if ($res) {
        $query = "SELECT COUNT(id) FROM cms_videos_catalogs WHERE catalog_id=:Catalog_id ";
        $params2[] = array( "key" => ":Catalog_id",
                             "value" =>$catalog_id);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params2);
        $select->execute();
        $row    = $select->fetch();
        $count = $row[0];
        $query2 = "UPDATE cms_users_catalogs SET n_media=:Count WHERE id=:Catalog_id";
        $params3[] = array( "key" => ":Count",
                             "value" =>$count);
        $params3[] = array( "key" => ":Catalog_id",
                             "value" =>$catalog_id);
        $update = $dbConn->prepare($query2);
        PDO_BIND_PARAM($update,$params3);
        $update->execute();
        return true;
    } else {
        return false;
    }
}

/**
 * sets the default media file for the catalog 
 * @param integer $catalog_id the cms_users_catalogs id
 * @param integer $video_id the cms_videos id
 * @return boolean true|false
 */
function userCatalogDefaultMediaSet($catalog_id, $video_id) {
    global $dbConn;
	$params  = array();  
	$params2 = array();
    $query = "UPDATE cms_videos_catalogs SET default_pic=0 WHERE video_id<>:Video_id AND catalog_id=:Catalog_id";
    $params[] = array( "key" => ":Video_id",
                        "value" =>$video_id);
    $params[] = array( "key" => ":Catalog_id",
                        "value" =>$catalog_id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $update->execute();

    $query2  = "UPDATE cms_videos_catalogs SET default_pic=1 WHERE video_id=:Video_id AND catalog_id=:Catalog_id";
    $params2[] = array( "key" => ":Video_id",
                         "value" =>$video_id);
    $params2[] = array( "key" => ":Catalog_id",
                         "value" =>$catalog_id);
    $update2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($update2,$params2);
    $update2->execute();
}

/**
 * gets the cms_videos record that is the default of the catalog
 * @param integer $catalog_id which cms_users_catalogs record
 * @return array|false 
 */
function userCatalogDefaultMediaGet($catalog_id) {


    global $dbConn;
    $params = array();
    
    $userCatalogDefaultMediaGet = tt_global_get('userCatalogDefaultMediaGet'); 
    
    if(isset($userCatalogDefaultMediaGet[$catalog_id]) && $userCatalogDefaultMediaGet[$catalog_id]!=''){
        return $userCatalogDefaultMediaGet[$catalog_id];
    }
    
    
//    $query = "SELECT 
//				V.*
//			FROM
//				cms_videos AS V
//				INNER JOIN cms_videos_catalogs AS VC ON V.id=VC.video_id
//			WHERE
//				VC.catalog_id='$catalog_id'
//			ORDER BY
//				VC.default_pic DESC, VC.video_id ASC
//			LIMIT 1";
//
//    $res = db_query($query);
//    if (!$res || (db_num_rows($res) == 0)) {
//        return false;
//    } else {
//        return db_fetch_assoc($res);
//    }
    $query = "SELECT 
				V.*
			FROM
				cms_videos AS V
				INNER JOIN cms_videos_catalogs AS VC ON V.id=VC.video_id
			WHERE
				VC.catalog_id=:Catalog_id
			ORDER BY
				VC.default_pic DESC, VC.video_id ASC
			LIMIT 1";

    $params[] = array( "key" => ":Catalog_id", "value" =>$catalog_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    
    if (!$res || ($ret == 0)) {
        $userCatalogDefaultMediaGet[$catalog_id]    =   false;
        return false;
    } else {
        $row = $select->fetch();
        $userCatalogDefaultMediaGet[$catalog_id]    =   $row;
        return $row;
    }


}

/**
 * gets the suggestion of a single string
 * @param string $in_string the string to be checked for suggestions
 * @return array|null the suggested string or nul if no suggestions 
 */
function suggestionGetUser($in_string) {


    global $dbConn;
    $params  = array();
    $params2 = array();
    $len = strlen($in_string);
    $l_in_string = strtolower($in_string);
    //$query = "SELECT name,levenshtein_ratio('$in_string', SUBSTRING(name,1,$len) ) AS LR FROM webgeocities HAVING LR >= 70 ORDER BY LR DESC";

    $where = '';

    if (userIsLogged()) {
        if ($where != '')
            $where .= ' AND ';
        $user_id = userGetID();
//        $where .= "(
//						NOT EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$user_id AND receipient_id=id AND profile_blocked=1)
//						AND
//						NOT EXISTS (SELECT receipient_id FROM cms_friends WHERE receipient_id=$user_id AND requester_id=id AND profile_blocked=1) 
//					)";
        $where .= "(
						NOT EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:User_id AND receipient_id=id AND profile_blocked=1)
						AND
						NOT EXISTS (SELECT receipient_id FROM cms_friends WHERE published=1 AND receipient_id=:User_id AND requester_id=id AND profile_blocked=1) 
					)";
        if ($where != '')
            $where .= ' AND ';
//        $where .= " id <> $user_id ";
        $where .= " id <> :User_id ";
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
    }

    if (userIsLogged()) {
        $searcher_id = userGetID();
        $friends = userGetFreindList($searcher_id);

        $friends_ids = array($searcher_id);
        foreach ($friends as $freind) {
            $friends_ids[] = $freind['id'];
        }
        if (count($friends_ids) != 0) {
            if ($where != '')
                $where .= " AND ";
            $public = USER_PRIVACY_PUBLIC;
            $private = USER_PRIVACY_PRIVATE;
            $selected = USER_PRIVACY_SELECTED;
            $community = USER_PRIVACY_COMMUNITY;
            $privacy_where = '';

            $where .= "CASE";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='Public' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Public LIMIT 1 ) THEN 1";
            $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
            
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Private AND PR.user_id= :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
            
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public","value" =>$public);
            $params[] = array( "key" => ":Searcher_id","value" =>$searcher_id);            
            $params[] = array( "key" => ":Private", "value" =>$private);
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
//        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public",
                                "value" =>$public);
    }

    if ($where != '')
        $where .= ' AND ';
    $where .= " published=1 ";
    if ($where != '')
        $where .= ' AND ';
    $where .= " isChannel=0 ";

    if ($where != '')
        $where = $where . ' AND ';
    $search_strings = explode(' ', $l_in_string);
    $wheres = array();
    $i=0;
    foreach ($search_strings as $search_string_loop) {
        $wheres[] = "( 
					(
						display_fullname=0
						AND
						LOWER(YourUserName) LIKE :Search_string_loop$i
					)
					OR
					(
						display_fullname=1
						AND
						(
							LOWER(fname) LIKE :Search_string_loop$i 
							OR
							LOWER(FullName) LIKE :Search_string_loop$i
						)
					)
				)";
        $params[] = array( "key" => ":Search_string_loop$i", "value" => $search_string_loop.'%' );
        $i++;
    }
    $where .= " ( " . implode(' AND ', $wheres) . ") ";

    $query = "SELECT
					id, YourUserName, FullName, display_fullname, profile_Pic, gender
				FROM
					cms_users AS U
				WHERE
					$where AND U.published=1 AND U.isChannel=0 
				ORDER BY
					LOWER(YourUserName) ASC LIMIT 10";
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

//    $res = db_query($query);
    if ($res && ($ret != 0)) {
//        while ($row = db_fetch_assoc($res)) {
//            if ($row['profile_Pic'] == '') {
//                //$row['profile_Pic'] = 'tuber.jpg';
//                $row['profile_Pic'] = 'he.jpg';
//                if ($row['gender'] == 'F') {
//                    $row['profile_Pic'] = 'she.jpg';
//                }
//            }
//            $ret[] = $row; // array($row[0], $row[1]);
//        }
        $row   = $select->fetchAll(PDO::FETCH_ASSOC);
        
        $media = array();
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $media[] = $row_item;
        }
        return $media;
    } else {
        return null;
    }


}

/**
 * gets the suggestion of a single string
 * @param string $in_string the string to be checked for suggestions
 * @return array|null the suggested string or nul if no suggestions 
 */
function suggestionGetUserChannel($in_string, $dont_show) {
    global $dbConn;
    $params  = array();
    $params2 = array();
    $len = strlen($in_string);
    $l_in_string = strtolower($in_string);
    $where = '';
    if (userIsLogged()) {
        if ($where != '')
            $where .= ' AND ';
        $user_id = userGetID();
        $where .= "(NOT EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:User_id AND receipient_id=id AND profile_blocked=1) AND NOT EXISTS (SELECT receipient_id FROM cms_friends WHERE published=1 AND receipient_id=:User_id AND requester_id=id AND profile_blocked=1) )";
        if ($where != '') $where .= ' AND ';
        $where .= " id <> :User_id ";
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
    }
    if (userIsLogged()) {
        $searcher_id = userGetID();
        $friends = userGetFreindList($searcher_id);
        $friends_ids = array($searcher_id);
        foreach ($friends as $freind) {
            $friends_ids[] = $freind['id'];
        }
        if (count($friends_ids) != 0) {
            if ($where != '')
                $where .= " AND ";
            $public = USER_PRIVACY_PUBLIC;
            $private = USER_PRIVACY_PRIVATE;
            $selected = USER_PRIVACY_SELECTED;
            $community = USER_PRIVACY_COMMUNITY;
            $privacy_where = '';

            $where .= "CASE";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Public LIMIT 1 ) THEN 1";
            $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
            
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
            
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public", "value" =>$public);
            $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);            
            $params[] = array( "key" => ":Private", "value" =>$private);
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
//        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=$public LIMIT 1 ) THEN 1";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
        $params[] = array( "key" => ":Public",
                            "value" =>$public);
    }

    if ($where != '')
        $where .= ' AND ';
    $where .= " published=1 ";

    if ($where != '') $where .= ' AND ';
    $where .= " isChannel=0";

    if ($where != '') $where .= ' AND ';
    $search_strings = explode(' ', $l_in_string);
    $wheres = array();
    $i=0;
    foreach ($search_strings as $search_string_loop) {
        $wheres[] = "( 
                (
                        display_fullname=0
                        AND
                        LOWER(YourUserName) LIKE :Search_string_loop$i
                )
                OR
                (
                        display_fullname=1
                        AND
                        (
                                LOWER(fname) LIKE :Search_string_loop$i 
                                OR
                                LOWER(FullName) LIKE :Search_string_loop$i
                        )
                )
        )";
        $params[] = array( "key" => ":Search_string_loop$i", "value" =>$search_string_loop.'%');
        $i++;
    }
    $where .= " (".implode(' AND ', $wheres).") ";
    if (strlen($dont_show) != 0) {
        if ($where != '') $where .= ' AND ';
        $where .= " NOT find_in_set(cast(id as char), :Dont_show) ";
        $params[] = array( "key" => ":Dont_show", "value" =>$dont_show);
    }

    $query = "SELECT
					id, YourUserName, FullName, display_fullname, profile_Pic, gender, id as uid
				FROM
					cms_users AS U
				WHERE
					$where AND U.published=1 AND U.isChannel=0
				ORDER BY
					LOWER(YourUserName) ASC LIMIT 10";

    $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0)) {
        $ret_arr = array();
        $row   = $select->fetchAll(PDO::FETCH_ASSOC);
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $ret_arr[] = $row_item;
        }
        return $ret_arr;
    } else {
        return null;
    }


}

/* list the popup for the privacy of the users fro each field */

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function ShowPopupPrivacy() {
//    return '<div class="list">
//				<ul>
//					<li style="background:url(' . ReturnLink("/media/images/account_public.png") . ') no-repeat left;" rel="2">public</li>
//					<li style="background:url(' . ReturnLink("/media/images/account_comunity.png") . ') no-repeat left;" rel="1">community</li>
//					<li style="background:url(' . ReturnLink("/media/images/account_selected.png") . ') no-repeat left;" rel="4">selected</li>
//					<li style="background:url(' . ReturnLink("/media/images/account_extended.png") . ') no-repeat left;" rel="3">extended community</li>
//					<li style="background:url(' . ReturnLink("/media/images/account_private.png") . ') no-repeat left;" rel="0">private</li>
//				</ul>
//			</div>
//			<div class="btn"></div>';
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * gets the image of a single premission
 * @param int $flag the string to be checked for suggestions
 * @return image of the premission selected 
 */
function FindPremissionImage($flag) {
    $image = '';
    if ($flag == 0) {
        $image = ReturnLink("/media/images/account_private.png");
        return $image;
    } else if ($flag == 1) {
        $image = ReturnLink("/media/images/account_comunity.png");
        return $image;
    } else if ($flag == 2) {
        $image = ReturnLink("/media/images/account_public.png");
        return $image;
    } else if ($flag == 3) {
        $image = ReturnLink("/media/images/account_extended.png");
        return $image;
    } else if ($flag == 4) {
        $image = ReturnLink("/media/images/account_selected.png");
        return $image;
        //return '<img src="'.$image.'" width="18" height="18">';
    } else if ($flag == 9999) {
        return null;
    }
}

/**
 * gets the list of intrested in for the profile
 * options include:<br/>
 * <b>id</b>: the id of the intereted. returns string. default null<br/>
 * <b>title</b>: the interested in title. returns id. default null.<br/>
 * @return array|null a list of records or array 
 */
function getIntresedIn($srch_options=array()) {


    global $dbConn;
	$params = array();  

    $default_opts = array(
        'id' => null,
        'title' => null
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';
    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " id='{$options['id']}' ";
        $where .= " id=:Id ";
	$params[] = array( "key" => ":Id",
                            "value" =>$options['id']);
    }

    if (!is_null($options['title'])) {
        if ($where != '') $where .= ' AND ';
        $where .= " title=:Title ";
	$params[] = array( "key" => ":Title", "value" =>$options['title']);
    }

    if ($where != '')
        $where .= ' AND ';
    $where .= " published='1' ";

    if ($where != '')
        $where = " WHERE $where ";

    $query = "SELECT id,title FROM cms_intrestedin $where ORDER BY title ASC";
//    $res = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && ($ret != 0)) {
        $ret = array();
//        while ($row = db_fetch_array($res)) {
//            $ret[] = array($row[0], $row[1]);
//        }
	$row = $select->fetchAll();
        foreach($row as $row_item){
             $ret[] = array($row_item[0], $row_item[1]);
        }
        if (count($ret) == 1)
            return $ret[0];
        return $ret;
    }else {
        return null;
    }


}

/**
 * gets a list of suggested freinds for a user
 * @param array $user_id
 * <b>limit</b>: integer - limit of record to return. default 6<br/>
 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
 * <b>user_id</b>: integer - the user to search for. required<br/>
 * <b>orderby</b>: string - the cms_friends column to order the results by. default request_ts<br/>
 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
 * <b>n_results</b>: return the number of results or the resutls. default false.<br/>
 * @return array | false an array of users or false if none found
 */
function suggestedFriendsGet($srch_options) {


    global $dbConn;
    $params  = array();  
    $params2 = array();  

    $default_opts = array(
        'limit' => 28,
        'page' => 0,
        'user_id' => null,
        'orderby' => 'YourUserName',
        'order' => 'a',
        'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $user_id = $options['user_id'];
    if (is_null($user_id)) {
        return array();
    }

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;


    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $where = "";
    if (userIsLogged()) {
        $searcher_id = userGetID();
        $friends = userGetFreindList($searcher_id);
        $friends_ids = array($searcher_id);
        foreach ($friends as $freind) {
            $friends_ids[] = $freind['id'];
        }
        if (count($friends_ids) != 0) {
            if ($where != '')
                $where .= " AND ";
            $public = USER_PRIVACY_PUBLIC;
            $private = USER_PRIVACY_PRIVATE;
            $selected = USER_PRIVACY_SELECTED;
            $community = USER_PRIVACY_COMMUNITY;
            $privacy_where = '';

            $where .= "CASE";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Public LIMIT 1 ) THEN 1";
            $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
            
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type= :Private AND PR.user_id= :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
            
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public", "value" =>$public);
            $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);            
            $params[] = array( "key" => ":Private", "value" =>$private);
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
//        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=0 AND PR.user_id=U.id AND PR.entity_type=" . SOCIAL_ENTITY_USER . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public",
                                "value" =>$public);
    }
    $where .=' AND U.published=1 AND U.isChannel=0 ';
    if ($options['n_results'] == false) {
        $options = array(
            'type' => array(1),
            'userid' => $user_id,
            'n_results' => true
        );
        $friend_array_count = userFriendSearch($options);
        if ($friend_array_count > 0) {
            $query = "SELECT F.uid, U.FullName,U.gender, U.YourUserName, U.display_fullname, U.profile_Pic
				FROM 
					(SELECT DISTINCT(F2.receipient_id) AS uid
						FROM
							`cms_friends` AS F1
							INNER JOIN `cms_friends` AS F2 ON  F2.published=1 AND F1.receipient_id=F2.requester_id AND F2.status=" . FRND_STAT_ACPT . "
						WHERE
						F1.published=1 AND F1.requester_id=:User_id2 AND F1.status=" . FRND_STAT_ACPT . "
					) AS F
					INNER JOIN cms_users as U ON F.uid=U.id
					WHERE $where AND U.id <> :User_id2 AND  NOT EXISTS (SELECT receipient_id FROM cms_friends AS FF WHERE published=1 AND requester_id=:User_id2 AND receipient_id=F.uid ) 
					ORDER BY $orderby $order
					LIMIT :Skip,:Nlimit";
        } else {
            $query = "SELECT U.id AS uid, U.FullName,U.gender, U.YourUserName, U.display_fullname, U.profile_Pic
				FROM 
					cms_users AS U
					WHERE $where AND U.id <> :User_id2 AND  NOT EXISTS (SELECT receipient_id FROM cms_friends AS FF WHERE published=1 AND requester_id=:User_id2 ) 
					ORDER BY $orderby $order
					LIMIT :Skip,:Nlimit";
        }
            $params[] = array( "key" => ":User_id2", "value" =>$user_id);
            $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");

        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
	$row = $select->fetchAll();
        
        $media = array();
        foreach($row as $row_item){
            if( $row_item['profile_Pic'] == ''){
                $row_item['profile_Pic'] = 'he.jpg';
                if ( $row_item['gender'] == 'F') {
                    $row_item['profile_Pic'] = 'she.jpg';
                }
            }
            $media[] = $row_item;
        }

        return $media;
    } else {

        $query = "SELECT COUNT(F.uid)
			FROM 
				(SELECT DISTINCT(F2.receipient_id) AS uid
					FROM
						`cms_friends` AS F1
						INNER JOIN `cms_friends` AS F2 ON F2.published=1 AND  F1.receipient_id=F2.requester_id AND F2.status=" . FRND_STAT_ACPT . "
					WHERE
					F1.published=1 AND F1.requester_id=:User_id2 AND F1.status=" . FRND_STAT_ACPT . "
				) AS F
				INNER JOIN cms_users as U ON F.uid=U.id
				WHERE $where AND U.id <> :User_id2 AND  NOT EXISTS (SELECT receipient_id FROM cms_friends AS FF WHERE published=1 AND  requester_id=:User_id2 AND receipient_id=F.uid ) ";

//        $res = db_query($query);
        $params[] = array( "key" => ":User_id2", "value" =>$user_id);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {
            return 0;
        }

        $row = $select->fetch();
        return $row[0];
    }


}

/**
 * add the events for a given user 
 * @param integer $user_id the user id
 * @param string $nameevents the event name
 * @param string $descriptionevent the event description
 * @param string $data_location the event location
 * @param string $locationevent the event location detailed
 * @param double $data_lng longitude
 * @param double $data_lat latitude
 * @param date $fromdate the event from date
 * @param time $fromdatetime the event from date time
 * @param date $todate the event to date
 * @param time $todatetime the event to date time
 * @param integer $theme_id the event theme id
 * @param integer $guestevent the limit number of guests
 * @param integer $caninvite the value 1 if guest can invite and 0 if not
 * @param integer $showguests the value 1 show guests list and 0 hide them
 * @param integer $enablesharecomments the value 1 enable shares comments and 0 if not
 * @param integer $privacyValue (public or private or friends or friends of friends or custom)
 * @param integer $location_id city id
 * @return integer | false the newly inserted cms_users_event id or false if not inserted
 */
function AddUserEvents($user_id, $nameevents, $descriptionevent, $data_location, $fromdate, $fromdatetime, $todate, $todatetime, $theme_id, $themephotoStanInside, $guestevent, $caninvite, $showguests, $enablesharecomments, $privacyValue, $data_lng, $data_lat, $locationevent,$country='') {


    global $dbConn;
    $params = array(); 
    $relativepath = date('Y') . '/' . date('W') . '/';

    $query = "INSERT
				INTO
				cms_users_event
					(user_id,name,photo,description,location,country,location_detailed,longitude,lattitude,fromdate,fromtime,todate,totime,theme_id,limitnumber,caninvite,showguests,enable_share_comment,relativepath,published)
				VALUES
					(:User_id,:Nameevents,:ThemephotoStanInside,:Descriptionevent,:Data_location,:Country,:Locationevent,:Data_lng,:Data_lat,:Fromdate,:Fromdatetime,:Todate,:Todatetime,:Theme_id,:Guestevent,:Caninvite,:Showguests,:Enablesharecomments,:Relativepath,1)";
//    $ret = db_query($query);
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Nameevents", "value" =>$nameevents);
	$params[] = array( "key" => ":ThemephotoStanInside", "value" =>$themephotoStanInside);
	$params[] = array( "key" => ":Descriptionevent", "value" =>$descriptionevent);
	$params[] = array( "key" => ":Data_location", "value" =>$data_location);
	$params[] = array( "key" => ":Country", "value" =>$country);
	$params[] = array( "key" => ":Locationevent", "value" =>$locationevent);
	$params[] = array( "key" => ":Data_lng", "value" =>$data_lng);
	$params[] = array( "key" => ":Data_lat", "value" =>$data_lat);
	$params[] = array( "key" => ":Fromdate", "value" =>$fromdate);
	$params[] = array( "key" => ":Fromdatetime", "value" =>$fromdatetime);
	$params[] = array( "key" => ":Todate", "value" =>$todate);
	$params[] = array( "key" => ":Todatetime", "value" =>$todatetime);
	$params[] = array( "key" => ":Theme_id", "value" =>$theme_id);
	$params[] = array( "key" => ":Guestevent", "value" =>$guestevent);
	$params[] = array( "key" => ":Caninvite", "value" =>$caninvite);
	$params[] = array( "key" => ":Showguests", "value" =>$showguests);
	$params[] = array( "key" => ":Enablesharecomments", "value" =>$enablesharecomments);
	$params[] = array( "key" => ":Relativepath", "value" =>$relativepath);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();

    $event_id = $dbConn->lastInsertId();

    if ($event_id) {
        $privacyValue_Extand = $privacyValue;

        newsfeedAdd($user_id, $event_id, SOCIAL_ACTION_UPLOAD, $event_id, SOCIAL_ENTITY_USER_EVENTS, USER_PRIVACY_PUBLIC, null);
        return $event_id;
    } else {
        return false;
    }


}

/**
 * gets the events for a given id 
 * @param integer $id the event id
 * @return array | false the cms_users_event record or false if not found
 */
function userEventInfo($id, $published = 1) {


    global $dbConn;
    $params = array();
    $userEventInfo = tt_global_get('userEventInfo');
    if(isset($userEventInfo[$id][$published]) && $userEventInfo[$id]!='')
       return $userEventInfo[$id][$published];
    
//    if ($published != -1) {
//        $query = "SELECT * FROM cms_users_event WHERE id='$id' AND published='$published'";
//    } else {
//        $query = "SELECT * FROM cms_users_event WHERE id='$id'";
//    }
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_assoc($ret);
//        return $row;
//    } else {
//        return false;
//    }
    if ($published != -1) {
        $query = "SELECT * FROM cms_users_event WHERE id=:Id AND published=:Published";
	$params[] = array( "key" => ":Id", "value" =>$id);
	$params[] = array( "key" => ":Published", "value" =>$published);
    } else {
        $query = "SELECT * FROM cms_users_event WHERE id=:Id";
	$params[] = array( "key" => ":Id", "value" =>$id);
    }
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        $userEventInfo[$id][$published]   =   $row;
        return $row;
    } else {
        $userEventInfo[$id][$published]   =   false;
        return false;
    }


}

/**
 * delete the user event
 * @param integer $user_id the user id
 * @param integer $id event id to be deletd
 * @return boolean true|false depending on the success of the operation
 */
function unitDeleteUserEvent($user_id, $id) {
    global $dbConn;
	$params  = array();  
	$params2 = array();  

    if (deleteMode() == TT_DEL_MODE_PURGE) {
        $query = "DELETE FROM cms_users_event where user_id=:User_id AND id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query = "UPDATE cms_users_event SET published=" . TT_DEL_MODE_FLAG . " WHERE user_id=:User_id AND id=:Id AND published=1";
    }

    newsfeedDeleteAll($id, SOCIAL_ENTITY_USER_EVENTS);

    newsfeedAdd($user_id, $id, SOCIAL_ACTION_EVENT_CANCEL, $id, SOCIAL_ENTITY_USER_EVENTS, USER_PRIVACY_PUBLIC, null);

    $query_join = "SELECT * FROM cms_users_event_join WHERE event_id=:Id AND published=1";
	$params2[] = array( "key" => ":Id",
                            "value" =>$id);
    $select = $dbConn->prepare($query_join);
    PDO_BIND_PARAM($select,$params2);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    if ($res && $ret != 0) {
        $row    = $select->fetchAll();
        foreach($row as $row_item){
            $j_id = $row_item['id'];
            joinUserEventDelete($j_id);
            newsfeedAdd($row_item['user_id'], $j_id, SOCIAL_ACTION_EVENT_CANCEL, $id, SOCIAL_ENTITY_USER_EVENTS, USER_PRIVACY_PRIVATE, null);
            addPushNotification(SOCIAL_ACTION_EVENT_CANCEL, $row_item['user_id'], $user_id, 0, $id, SOCIAL_ENTITY_USER_EVENTS);
            sendUserEmailNotification_Cancel_Event_Join($row_item['user_id'], $id, SOCIAL_ACTION_EVENT_CANCEL);
        }
    }

    // remove news feed for the list of user invited to this event
    $invites = socialSharesGet($options = array(
        'entity_id' => $id,
        'entity_type' => SOCIAL_ENTITY_USER_EVENTS,
        'share_type' => SOCIAL_SHARE_TYPE_INVITE,
        'like_value' => 1,
        'limit' => null
    ));

    foreach ($invites as $invitesInfo) {
        $ids = $invitesInfo['id'];
        newsfeedDeleteJoinByAction($ids, SOCIAL_ACTION_INVITE, SOCIAL_ENTITY_USER_EVENTS);
    }


    //delete comments
    socialCommentsDelete($id, SOCIAL_ENTITY_USER_EVENTS);

    //delete likes
    socialLikesDelete($id, SOCIAL_ENTITY_USER_EVENTS);

    //delete shares and sponsors.
    socialSharesDelete($id, SOCIAL_ENTITY_USER_EVENTS);

    //delete ratings
    socialRatesDelete($id, SOCIAL_ENTITY_USER_EVENTS);

    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Id", "value" =>$id);
    
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;
}

/**
 * gets the event info of a user depending on search criteria. options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: event id<br/>
 * <b>user_id</b>: the user's id. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>name</b>: the user's name default null<br/>
 * <b>location</b>: the event location default null<br/>
 * <b>from_ts</b>: start date default null<br/>
 * <b>to_ts</b>: end date default null<br/>
 * <b>current_date</b>: specific date default null<br/>
 * <b>search_string</b>: the search string. default null<br/>
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * <b>status<b/> integer. current => 2. upcoming => 1. past => 0. current+upcoming=>3 overwrites date_from and date_to. default null (doesnt matter)<br/>
 * @param array $srch_options 
 * @return array | false an array of 'cms_users_event' records or false if none found.
 */
function userEventSearch($srch_options) {


    global $dbConn;
    $params  = array();  
    $params2 = array(); 
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'user_id' => null,
        'name' => null,
        'location' => null,
        'from_ts' => null,
        'to_ts' => null,
        'current_date' => null,
        'limitnumber' => null,
        'caninvite' => null,
        'showguests' => null,
        'hideguests' => null,
        'search_string' => null,
        'is_visible' => -1,
        'strict_search' => 1,
        'skip' => 0,
        'orderby' => 'id',
        'published' => 1,
        'order' => 'a',
        'n_results' => false,
        'status' => null
    );

    $options = array_merge($default_opts, $srch_options);

    $is_visible = $options['is_visible'];

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.id='{$options['id']}' ";
        $where .= " E.id=:Id ";
	$params[] = array( "key" => ":id", "value" =>$options['id']);
    }
    if (!is_null($options['user_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.user_id='{$options['user_id']}' ";
        $where .= " E.user_id=:User_id ";
	$params[] = array( "key" => ":User_id", "value" =>$options['user_id']);
    }
    if (!is_null($options['name'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.name='{$options['name']}' ";
        $where .= " E.name=:Name ";
	$params[] = array( "key" => ":Name", "value" =>$options['name']);
    }
    if (!is_null($options['location'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.location={$options['location']} ";
        $where .= " E.location=:Location ";
	$params[] = array( "key" => ":Location", "value" =>$options['location']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(E.todate) >= '{$options['from_ts']}' ";
        $where .= " DATE(E.todate) >= :From_ts ";
	$params[] = array( "key" => ":From_ts", "value" =>$options['from_ts']);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(E.fromdate) <= '{$options['to_ts']}' ";
        $where .= " DATE(E.fromdate) <= :To_ts ";
	$params[] = array( "key" => ":To_ts", "value" =>$options['to_ts']);
    }
    if (!is_null($options['current_date'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(E.fromdate) = '{$options['current_date']}' ";
        $where .= " DATE(E.fromdate) = :Current_date ";
	$params[] = array( "key" => ":Current_date", "value" =>$options['current_date']);
    }

    if (!is_null($options['limitnumber'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.limitnumber='{$options['limitnumber']}' ";
        $where .= " E.limitnumber=:Limitnumber ";
	$params[] = array( "key" => ":Limitnumber", "value" =>$options['limitnumber']);
    }
    if (!is_null($options['caninvite'])) {
        if ($where != '')
            $where .= ' AND ';
        $where .= " E.caninvite=:Argument9 ";
	$params[] = array( "key" => ":Argument9", "value" =>$options['caninvite']);
    }
    if (!is_null($options['hideguests'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " E.showguests='{$options['showguests']}' ";
        $where .= " E.showguests=:Showguests ";
	$params[] = array( "key" => ":Showguests", "value" =>$options['showguests']);
    }
    if ($is_visible != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " E.is_visible='{$options['is_visible']}' ";
        $where .= " E.is_visible=:Is_visible ";
	$params[] = array( "key" => ":Is_visible", "value" =>$options['is_visible']);
    }
    if (!is_null($options['search_string'])) {
        $options['search_string'] = strtolower($options['search_string']);
        if ($where != '')
            $where = " ( $where ) AND ";
        $search_strings = explode(' ', $options['search_string']);
        $wheres = array();
        $i=0;
        foreach ($search_strings as $search_string_loop) {
            $wheres[] = "( 
                LOWER(E.name) LIKE :Search_string_loop$i
            )";
            $params[] = array( "key" => ":Search_string_loop$i", "value" =>'%'.$search_string_loop.'%' );
            $i++;
        }
        $where .= "(".implode(' AND ', $wheres).")";
    }
    if (userIsLogged()) {
        $searcher_id = userGetID();
        $friends = userGetFreindList($searcher_id);
        $friends_ids = array($searcher_id);
        foreach ($friends as $freind) {
            $friends_ids[] = $freind['id'];
        }
        if (count($friends_ids) != 0) {
            if ($where != '')
                $where .= " AND ";
            $public = USER_PRIVACY_PUBLIC;
            $private = USER_PRIVACY_PRIVATE;
            $selected = USER_PRIVACY_SELECTED;
            $community = USER_PRIVACY_COMMUNITY;
            $privacy_where = '';

            $where .= "CASE";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
            $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1  LIMIT 1 ) THEN 1";
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
            
//            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$searcher_id' LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.kind_type= :Private AND PR.user_id=:Searcher_id LIMIT 1 ) THEN 1";
            $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
            
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public", "value" =>$public);
            $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);            
            $params[] = array( "key" => ":Private","value" =>$private);
        }
    }else {
        $public = USER_PRIVACY_PUBLIC;
        if ($where != '')
            $where .= ' AND ';
        $where .= "CASE";
//        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=E.id AND PR.entity_type=" . SOCIAL_ENTITY_USER_EVENTS . " AND PR.published=1  LIMIT 1 ) THEN 1";
        $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Public",
                                "value" =>$public);
    }

    if ($where != '')
        $where .= ' AND ';
    $where .= " E.published=1 ";

    if (!is_null($options['status'])) {

        $cur_date = date('Y-m-d');
        $cur_time = date('H:i:s');

        if ($options['status'] == 0) {
            //past
            if ($where != '')
                $where .= " AND ";
//            $where .= " (E.todate < '$cur_date') OR (E.todate = '$cur_date' AND E.totime<'$cur_time') ";
            $where .= " (E.todate < :Cur_date) OR (E.todate = :Cur_date AND E.totime<:Cur_time) ";
        }else if ($options['status'] == 1) {
            //upcoming
            if ($where != '')
                $where .= " AND ";
//            $where .= " (E.fromdate > '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime>'$cur_time') ";
            $where .= " (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ";
        }else if ($options['status'] == 2) {
            //current
            if ($where != '')
                $where .= " AND ";
//            $where .= " ( (E.fromdate < '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime<'$cur_time') ) ";
            $where .= " ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
            $where .= " AND ";
//            $where .= " ( (E.todate > '$cur_date') OR (E.todate = '$cur_date' AND E.totime>'$cur_time') ) ";
            $where .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
        }else if ($options['status'] == 3) {
            //current + upcoming
            if ($where != '')
                $where .= " AND ";
//            $where .= " ( ( (E.fromdate < '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime<'$cur_time') ) ";
            $where .= " ( ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
            $where .= " AND ";
//            $where .= " ( (E.todate > '$cur_date') OR (E.todate = '$cur_date' AND E.totime>'$cur_time') ) ";
            $where .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
            $where .= " OR ";
//            $where .= " ( (E.fromdate > '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime>'$cur_time') ) ) ";
            $where .= " ( (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ) ) ";
        }
            $params[] = array( "key" => ":Cur_date", "value" =>$cur_date);
            $params[] = array( "key" => ":Cur_time", "value" =>$cur_time);
    }

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }


    $nlimit = '';

    if (!is_null($options['limit'])) {
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit + intval($options['skip']);
    }

    if ($options['n_results'] == false) {

        $query = "SELECT E.* 
					FROM 
						cms_users_event AS E INNER JOIN cms_users AS U ON E.user_id=U.id 
					$where ORDER BY $orderby $order";

        if (!is_null($options['limit'])) {
//            $query .= " LIMIT $skip, $nlimit";
            $query .= " LIMIT :Skip, :Nlimit";
            $params[] = array( "key" => ":Skip",
                                "value" =>$skip,
                                "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit",
                                "value" =>$nlimit,
                                "type" =>"::PARAM_INT");
        }

//        $ret = db_query($query);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $ret    = $select->rowCount();
        if (!$select || ($ret == 0)) {
            return false;
        } else {
            $ret_arr = $select->fetchAll(PDO::FETCH_ASSOC);
            return $ret_arr;
        }
    } else {
        $query = "SELECT COUNT(E.id) FROM cms_users_event AS E INNER JOIN cms_users AS U ON E.user_id=U.id $where";

//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
        
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $row    = $select->fetch();
        return $row[0];
    }



}

/**
 * gets the events for a given user 
 * @param integer $user_id the user id
 * @return array | false the cms_users_event record or false if not found
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function GetUserEvents($user_id) {
//    $query = "SELECT * FROM cms_users_event WHERE user_id='$user_id' AND published=1";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
//        return $ret_arr;
//    } else {
//        return false;
//    }
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * gets the suggested user events
 * @param integer $user_id the user id
 * @return array | false the cms_users_event record or false if not found
 */
function getUserSuggestedEvents($user_id,$country, $start, $limit) {


    global $dbConn;
	$params  = array();  
	$params2 = array();  
    if($country=='') return array();
    $cur_date = date('Y-m-d');
    $cur_time = date('H:i:s');

    
//    $query = "SELECT event_id FROM cms_users_event_join WHERE user_id=" . $user_id . " AND published=1";
//    $ret = db_query($query);
    $query = "SELECT GROUP_CONCAT( DISTINCT event_id SEPARATOR ',' ) AS event_id FROM cms_users_event_join WHERE user_id=:User_id AND published=1";
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
    $row    = $select->fetch();

    $event_id_arr = $row['event_id'];
    if($event_id_arr=='') $event_id_arr='0';
    $query = "SELECT *, RAND() AS random FROM cms_users_event AS E WHERE E.user_id<>:User_id AND E.id NOT IN (".$event_id_arr.") AND E.published=1 AND E.country=:Country ";

    $query .= " AND ( ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
    $query .= " AND ";
    $query .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
    $query .= " OR ";
    $query .= " ( (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ) ) ";


    $query .= " ORDER BY random ASC";
    $params  = array(); 
    if ($start == 0 && $limit == 0) {
        $query = $query;
    } else {
        $query .= " LIMIT :Start, :Limit";
	$params[] = array( "key" => ":Start", "value" =>$start, "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Limit", "value" =>$limit, "type" =>"::PARAM_INT");
    }
    
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Country", "value" =>$country);
	$params[] = array( "key" => ":Cur_date", "value" =>$cur_date);
	$params[] = array( "key" => ":Cur_time", "value" =>$cur_time);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    $ret = db_query($query);
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return array();
    }


}

/**
 * join user event 
 * @param integer $user_id the user id
 * @param integer $event_id the event id
 * @param integer $guests the number of guests
 * @return integer | false the newly inserted cms_users_event_join id or false if not inserted
 */
function joinUserEventAdd($event_id, $user_id, $guests) {


    global $dbConn;
	$params  = array();  
	$params2 = array();  
	$params3 = array();  
	$params4 = array();  
    $join_event_id = '';

    // Check if the user was attending the event and then canceled.
//    $query = "SELECT id
//				FROM cms_users_event_join
//				WHERE (event_id = " . $event_id . " AND user_id = " . $user_id . " AND published = -2)";
//    $ret = db_query($query);
//    $row = db_fetch_row($ret);
    $query = "SELECT id
				FROM cms_users_event_join
				WHERE (event_id = :Event_id  AND user_id = :User_id AND published = -2)";
	$params[] = array( "key" => ":Event_id",
                            "value" =>$event_id);
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret2    = $select->rowCount();

    // If the field exists in the db (flagged as deleted), reset it.
    if ($ret > 0) {
        $join_event_id = $event_id;
        $query = "UPDATE cms_users_event_join SET guests = :Guests , published = 1
		WHERE (event_id = :Event_id AND user_id = :User_id AND published = -2)";
	$params2[] = array( "key" => ":Guests",
                             "value" =>$guests);
	$params2[] = array( "key" => ":Event_id",
                             "value" =>$event_id);
	$params2[] = array( "key" => ":User_id",
                             "value" =>$user_id);
        $update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params2);
        $ret = $update->execute();
    }
    // If it's the first time the user joins this event.
    else {
        $query = "INSERT
					INTO
					cms_users_event_join
						(event_id,user_id,create_ts,guests,published)
					VALUES
						(:Event_id,:User_id,NOW(),:Guests,1)";
	$params3[] = array( "key" => ":Guests",
                             "value" =>$guests);
	$params3[] = array( "key" => ":Event_id",
                             "value" =>$event_id);
	$params3[] = array( "key" => ":User_id",
                             "value" =>$user_id);
        $insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params3);
        $ret = $insert->execute();
    }

    if ($join_event_id == '')
        $join_event_id = $dbConn->lastInsertId();

    if ($event_id) {
        $query_join = "UPDATE cms_users_event SET nb_joining = nb_joining + 1 WHERE id=:Event_id";
	$params4[] = array( "key" => ":Event_id",
                             "value" =>$event_id);
        $insert = $dbConn->prepare($query_join);
	PDO_BIND_PARAM($insert,$params4);
        $insert->execute();

        if ($ret2 == 0) {
            newsfeedAdd($user_id, $join_event_id, SOCIAL_ACTION_EVENT_JOIN, $event_id, SOCIAL_ENTITY_USER_EVENTS, USER_PRIVACY_PUBLIC, null);
        }
        return $join_event_id;
    } else {
        return false;
    }
}

/**
 * delete the join user event for a given join id 
 * @param integer $id the join's id 
 * @return boolean true|false depending on the success of the operation
 */
function joinUserEventDelete($id) {


    global $dbConn;
	$params  = array();  
	$params2 = array();  

    if (deleteMode() == TT_DEL_MODE_PURGE) {
        $query = "DELETE FROM cms_users_event_join where id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query = "UPDATE cms_users_event_join SET published=" . TT_DEL_MODE_FLAG . " WHERE id=:Id AND published=1";
    }

    newsfeedDeleteJoinByAction($id, SOCIAL_ACTION_EVENT_JOIN, SOCIAL_ENTITY_USER_EVENTS);


    $join_us_info = joinUserEventInfo($id);
    $ev_id = $join_us_info['event_id'];
    $query_join = "UPDATE cms_users_event SET nb_joining = nb_joining - 1 WHERE id=:Ev_id";
	$params[] = array( "key" => ":Ev_id",
                            "value" =>$ev_id);
    $update = $dbConn->prepare($query_join);
    PDO_BIND_PARAM($update,$params);
    $update->execute();
    $params2[] = array( "key" => ":Id", "value" =>$id);
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params2);
    $res    = $delete_update->execute();
    return $res;
}
/**
 * edits a  join user event info
 * @param array $new_info the new cms_users_event_join info
 * @return boolean true|false if success|fail
 */
function joinUserEventEdit($new_info) {
    global $dbConn;
	$params = array();  
    $query = "UPDATE cms_users_event_join SET ";
    foreach ($new_info as $key => $val) {
        if ($key != 'id' && $key != 'event_id') {
            $query .= " $key = :Val".$i.",";
            $params[] = array( "key" => ":Val".$i,
                                "value" =>$val);
        }
    }
    $params[] = array( "key" => ":Id",
                        "value" =>$new_info['id']);
    $params[] = array( "key" => ":Event_id",
                        "value" =>$new_info['event_id']);
    $query = trim($query, ',');
    $query .= " WHERE id=:Id AND  event_id=:Event_id";
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return ( $res ) ? true : false;
}

/**
 * gets the join user event 
 * @param integer $id the join's id
 * @return array | false the cms_users_event_join record or null if not found
 */
function joinUserEventInfo($id) {


    global $dbConn;
    $params = array();  
    $query = "SELECT * FROM cms_users_event_join WHERE id=:Id";
	$params[] = array( "key" => ":Id",
                            "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * gets the joined user event info of an event . options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: join id<br/>
 * <b>event_id</b>: the event's id. default null<br/>
 * <b>user_id</b>: the user's id. default null<br/>
 * <b>from_ts</b>: search for joined user after this date. default null.<br/>
 * <b>to_ts</b>: search for joined user before this date. default null.<br/>
 * <b>is_visible</b>: is visible or not. default = -1 => doenst matter.<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * @return array | false an array of 'cms_users_event_join' records or false if none found.
 */
function joinUserEventSearch($srch_options) {


    global $dbConn;
	$params = array();  
    $default_opts = array(
        'limit' => 100,
        'page' => 0,
        'id' => null,
        'event_id' => null,
        'is_visible' => -1,
        'user_id' => null,
        'orderby' => 'id',
        'distinct_user' => 0,
        'escape_user' => null,
        'from_ts' => null,
        'to_ts' => null,
        'n_results' => false,
        'order' => 'a'
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if (!is_null($options['id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CJ.id='{$options['id']}' ";
        $where .= " CJ.id=:Id ";
	$params[] = array( "key" => ":Id",
                            "value" =>$options['id']);
    }
    if (!is_null($options['from_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(create_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(create_ts) >= :From_ts ";
	$params[] = array( "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }
    if (!is_null($options['to_ts'])) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " DATE(create_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(create_ts) <= :To_ts ";
	$params[] = array( "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if (!is_null($options['event_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CJ.event_id='{$options['event_id']}' ";
        $where .= " CJ.event_id=:Event_id ";
	$params[] = array( "key" => ":Event_id",
                            "value" =>$options['event_id']);
    }
    if (!is_null($options['user_id'])) {
        if ($where != '')
            $where .= ' AND ';
//        $where .= " CJ.user_id='{$options['user_id']}' ";
        $where .= " CJ.user_id=:User_id ";
	$params[] = array( "key" => ":User_id",
                            "value" =>$options['user_id']);
    }
    if ($options['is_visible'] != -1) {
        if ($where != '')
            $where .= " AND ";
//        $where .= " CJ.is_visible='{$options['is_visible']}' ";
        $where .= " CJ.is_visible=:Is_visible ";
	$params[] = array( "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if(!is_null($options['escape_user'])){
        if( $where != '') $where .= " AND ";
//        $where .= " CJ.user_id NOT IN({$options['escape_user']}) ";
	$where .= " NOT find_in_set(cast(CJ.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    
    if ($where != '')
        $where .= " AND ";
    $where .= " CJ.published=1 ";

    if ($where != '') {
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order='';
    if ($orderby == 'rand') {
        $orderby = "RAND()";
    } else {
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;


    if ($options['n_results'] == false) {        
        if( $options['distinct_user']==1 ){            
//            $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
//						users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age,users.display_yearage, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
//				FROM cms_users_event_join AS CJ
//				INNER JOIN cms_users AS users ON users.id = CJ.user_id AND users.published=1 AND users.isChannel=0
//				$where GROUP BY CJ.user_id ORDER BY $orderby $order LIMIT $skip, $nlimit";
            $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
						users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age,users.display_yearage, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
				FROM cms_users_event_join AS CJ
				INNER JOIN cms_users AS users ON users.id = CJ.user_id AND users.published=1 AND users.isChannel=0
				$where GROUP BY CJ.user_id ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
        }else{
//            $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
//						users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age,users.display_yearage, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
//				FROM cms_users_event_join AS CJ
//				INNER JOIN cms_users AS users ON users.id = CJ.user_id AND users.published=1 AND users.isChannel=0
//				$where ORDER BY $orderby $order LIMIT $skip, $nlimit";
            $query = "SELECT CJ.is_visible AS is_visible_join , CJ.id, CJ.event_id, CJ.user_id, CJ.create_ts, CJ.guests, CJ.published,
						users.id AS user_id, users.FullName, users.fname, users.lname, users.gender, users.YourEmail, users.website_url, users.small_description, users.YourCountry, users.hometown, users.city, users.YourIP, users.YourBday, users.YourUserName, users.profile_Pic, users.display_age,users.display_yearage, users.display_gender, users.YourPassword, users.RegisteredDate, users.profile_views, users.published, users.notifs, users.chkey, users.n_flashes, users.n_journals, users.occupation, users.employment, users.high_education, users.uni_education, users.intrested_in, users.display_interest, users.display_fullname, users.contact_privacy, users.search_engine, users.feeds_privacy, users.comment_privacy, users.isChannel, users.otherEmail
				FROM cms_users_event_join AS CJ
				INNER JOIN cms_users AS users ON users.id = CJ.user_id AND users.published=1 AND users.isChannel=0
				$where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
        }
                
//        $ret = db_query($query);
	$params[] = array( "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {
            return false;
        } else {
//            while ($row = db_fetch_array($ret)) {
//                if ($row['profile_Pic'] == '') {
//                    //$row['profile_Pic'] = 'tuber.jpg';
//                    $row['profile_Pic'] = 'he.jpg';
//                    if ($row['gender'] == 'F') {
//                        $row['profile_Pic'] = 'she.jpg';
//                    }
//                }
//
//                $media[] = $row;
//            }
	$row = $select->fetchAll();            
            $media = array();
            foreach($row as $row_item){
                if( $row_item['profile_Pic'] == ''){
                    $row_item['profile_Pic'] = 'he.jpg';
                    if ( $row_item['gender'] == 'F') {
                        $row_item['profile_Pic'] = 'she.jpg';
                    }
                }
                $media[] = $row_item;
            }

            return $media;
        }
    } else {
        if( $options['distinct_user']==1 ){
            $query = "SELECT COUNT( DISTINCT CJ.user_id ) FROM cms_users_event_join AS CJ $where";
        }else{
            $query = "SELECT COUNT(CJ.id) FROM cms_users_event_join AS CJ $where";
        }
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $row = $select->fetch();
        return $row[0];
    }


}

/**
 * gets a list of tubers joined
 * @param integer $event_id the event id
 * @return array the joined tuber
 */
function getUserJoinedEventTubersList($event_id) {


    global $dbConn;
	$params = array();
    $query = "SELECT DISTINCT user_id FROM cms_users_event_join WHERE event_id =:Event_id AND published=1";
	$params[] = array( "key" => ":Event_id",
                            "value" =>$event_id);
    $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = $select->fetch();
        return $ret_arr;
    }


}
/**
 * @return array | false the cms_event_themes record or false if not found
 */
function GetEventsThemes() {


    global $dbConn;
	$params = array();  
    $query = "SELECT * FROM cms_event_themes";
    $select = $dbConn->prepare($query);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetchAll();
        return $ret_arr;
    } else {
        return false;
    }


}

/**
 * <b>id</b>: theme id<br/>
 * @return array | false the cms_event_themes record or false if not found
 */
function eventsThemesInfo($id) {


    global $dbConn;
    $params = array();  
    $query = "SELECT * FROM cms_event_themes WHERE id=:Id";
    $params[] = array( "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * gets the visited places list for a given user 
 * @param integer $user_id the user id, 
 * @return array | false the cms_videos record or null if not found
 */
function userVisitedPlacesList($user_id, $n_results = false) {


    global $dbConn;
	$params  = array(); 
	$params2 = array(); 
//    $query0 = "(SELECT V.cityid AS cityid, '" . SOCIAL_ENTITY_MEDIA . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM `cms_videos` AS V INNER JOIN webgeocities AS C ON V.cityid=C.id INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.published=1 AND V.userid=$user_id and COALESCE( V.cityid ,0) and V.cityid !=0 AND channelid=0 GROUP BY V.cityid)";
    $query0 = "(SELECT V.cityid AS cityid, '" . SOCIAL_ENTITY_MEDIA . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM `cms_videos` AS V INNER JOIN webgeocities AS C ON V.cityid=C.id INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.published=1 AND V.userid=:User_id and COALESCE( V.cityid ,0) and V.cityid !=0 AND channelid=0 GROUP BY V.cityid)";

    $ent_list = userReviewsEntityList($user_id, SOCIAL_ENTITY_RESTAURANT);
    $query1 = "";
    if($ent_list!=''){
        //$query1 = "(SELECT V.city_id AS cityid, '" . SOCIAL_ENTITY_RESTAURANT . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM `global_restaurants` AS V INNER JOIN webgeocities AS C ON V.city_id=C.id INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.id IN ($ent_list) and V.city_id >0 GROUP BY V.city_id)";
        
        //$query1 = "(SELECT C.id AS cityid, '" . SOCIAL_ENTITY_RESTAURANT . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM `global_restaurants` AS V INNER JOIN webgeocities AS C ON C.country_code = V.country and (C.name like V.locality or C.name like V.region or C.name like V.admin_region) INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.id IN ($ent_list) and V.country<>'' and (V.locality<>'' or V.region<>'' or V.admin_region<>'' ) GROUP BY  V.locality, V.region, V.admin_region, V.country)";
        
        
        //$query1 = "(SELECT C.id AS cityid, '" . SOCIAL_ENTITY_RESTAURANT . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name  FROM global_restaurants as V inner join `webgeocities` as C on V.latitude between (C.latitude - 0.001) and (C.latitude + 0.001) and V.longitude between (C.longitude - 0.001) and (C.longitude + 0.001) and V.latitude<>'' and V.country=C.country_code INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.id IN ($ent_list) and V.longitude<>'' and V.country<>'' group by C.id)";
        
    }
    
    $ent_list = userReviewsEntityList($user_id, SOCIAL_ENTITY_HOTEL);
    $query2 = "";
    if($ent_list!=''){
        $query2 = "(SELECT V.city_id AS cityid, '" . SOCIAL_ENTITY_HOTEL . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM `discover_hotels` AS V INNER JOIN webgeocities AS C ON V.city_id=C.id INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.id IN ($ent_list) and V.city_id >0 GROUP BY V.city_id)";
    }
    $ent_list = userReviewsEntityList($user_id, SOCIAL_ENTITY_LANDMARK);
    $query3 = "";
    if($ent_list!=''){
        $query3 = "(SELECT V.city_id AS cityid, '" . SOCIAL_ENTITY_LANDMARK . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM `discover_poi` AS V INNER JOIN webgeocities AS C ON V.city_id=C.id INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.id IN ($ent_list) and V.city_id >0 GROUP BY V.city_id)";
    }
    
    $ent_list = userReviewsEntityList($user_id, SOCIAL_ENTITY_AIRPORT);
    $query4 = "";
    if($ent_list!=''){
        $query4 = "(SELECT V.city_id AS cityid, '" . SOCIAL_ENTITY_AIRPORT . "' AS real_type, C.country_code, C.state_code, C.name, C.latitude, C.longitude, CO.name AS country_name, ST.state_name FROM `airport` AS V INNER JOIN webgeocities AS C ON V.city_id=C.id INNER JOIN cms_countries AS CO ON C.country_code=CO.code LEFT JOIN states AS ST ON C.country_code=ST.country_code AND C.state_code=ST.state_code WHERE V.id IN ($ent_list) and V.city_id >0 GROUP BY V.city_id)";
    }

    if ($n_results == false) {
        $query = "SELECT cityid , real_type, country_code, state_code, name, latitude, longitude, country_name, state_name";
        $query .= " FROM (" . $query0;
        if($query1 !='') $query .= " UNION " . $query1 ;
        if($query2 !='') $query .= " UNION " . $query2 ;
        if($query3 !='') $query .= " UNION " . $query3 ;
        if($query4 !='') $query .= " UNION " . $query4 ;
        $query .= " ) AS GBL GROUP BY cityid ORDER BY LOWER(name) ASC";        
        
//        $ret = db_query($query);
        $select = $dbConn->prepare($query);
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
	PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
        if (!$res || ($ret == 0)) {
            return false;
        } else {
//            $ret_arr = array();
            $ret_arr = $select->fetchAll();
//            while ($row = db_fetch_array($ret)) {
//                $ret_arr[] = $row;
//            }
            return $ret_arr;
        }
    } else {
        $query = "SELECT COUNT(DISTINCT cityid) ";
        $query .= " FROM (" . $query0;
        if($query1 !='') $query .= " UNION " . $query1 ;
        if($query2 !='') $query .= " UNION " . $query2 ;
        if($query3 !='') $query .= " UNION " . $query3 ;
        if($query4 !='') $query .= " UNION " . $query4 ;
        $query .= " ) AS GBL"; 
        
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
        
        $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
        $select->execute();

        $row = $select->fetch();
        return $row[0];
    }


}

/**
 * gets the visited places privacy list for a given user and city id
 * @param integer $user_id the user id, 
 * @return string comma separated | empty the cms_videos record or empty if not found
 */
function userVisitedPlacesPrivacyList($user_id, $city_id) {


    global $dbConn;
	$params = array();  
    $query = "SELECT GROUP_CONCAT( DISTINCT is_public SEPARATOR ',' ) AS is_public FROM cms_videos WHERE cityid=:City_id AND published =1 AND userid=:User_id";
	$params[] = array( "key" => ":City_id",
                            "value" =>$city_id);
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch();
        return $row['is_public'];
    } else {
        return '';
    }


}

/**
 * add the visited places for a given user 
 * @param integer $user_id the user id
 * @param string $location the location place
 * @param double $longitude the location longitude
 * @param double $lattitude the location lattitude
 * @return integer | false the newly inserted cms_users_visited_places id or false if not inserted
 */
function userVisitedPlacesAdd($user_id, $location, $longitude, $lattitude) {
    $published = 1;


    global $dbConn;
	$params = array(); 

    $query = "INSERT
				INTO
				cms_users_visited_places
					(user_id,location,create_ts,longitude,lattitude,published)
				VALUES
					(:User_id,:Location,NOW(),:Longitude,:Lattitude,:Published)";
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Location",
                        "value" =>$location);
    $params[] = array( "key" => ":Longitude",
                        "value" =>$longitude);
    $params[] = array( "key" => ":Lattitude",
                        "value" =>$lattitude);
    $params[] = array( "key" => ":Published",
                        "value" =>$published);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();

    return ( $res ) ? $dbConn->lastInsertId() : false;


}

/**
 * delete the visited places for a given user
 * @param integer $id visited places id
 * @return boolean true|false depending on the success of the operation
 */
function userVisitedPlacesDelete($id) {
    global $dbConn;
    $params = array(); 

    if (deleteMode() == TT_DEL_MODE_PURGE) {
        $query = "DELETE FROM cms_users_visited_places where id=:Id AND published=1";
    } else if (deleteMode() == TT_DEL_MODE_FLAG) {
        $query = "UPDATE cms_users_visited_places SET published=" . TT_DEL_MODE_FLAG . " WHERE id=:Id AND published=1";
    }
    $params[] = array( "key" => ":Id", "value" =>$id);
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;
}

/**
 * gets the visited places info
 * @param integer $id the visited places's id, 
 * @return array | false the cms_users_visited_places record or null if not found
 */
function userVisitedPlacesInfo($id) {


    global $dbConn;
	$params = array(); 
    $query = "SELECT * FROM cms_users_visited_places WHERE id=:Id";
	$params[] = array( "key" => ":Id",
                            "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $insert->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }


}

/**
 * gets the visited places info of a given user . options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: visited places id<br/>
 * <b>user_id</b>: the user's id. default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * @return array | false an array of 'cms_users_visited_places' records or false if none found.
 */
// CODE NOT USED - commented by KHADRA
//function userVisitedPlacesSearch($srch_options) {
//    $default_opts = array(
//        'limit' => 6,
//        'page' => 0,
//        'id' => null,
//        'user_id' => null,
//        'orderby' => 'id',
//        'n_results' => false,
//        'order' => 'a'
//    );
//
//    $options = array_merge($default_opts, $srch_options);
//
//    $where = '';
//
//    if (!is_null($options['id'])) {
//        if ($where != '')
//            $where .= ' AND ';
//        $where .= " id='{$options['id']}' ";
//    }
//    if (!is_null($options['user_id'])) {
//        if ($where != '')
//            $where .= ' AND ';
//        $where .= " user_id='{$options['user_id']}' ";
//    }
//
//    if ($where != '')
//        $where .= " AND ";
//    $where .= " published=1 ";
//
//    if ($where != '') {
//        $where = "WHERE $where";
//    }
//
//    $orderby = $options['orderby'];
//    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
//
//    $nlimit = '';
//    if (!is_null($options['limit'])) {
//        $nlimit = intval($options['limit']);
//        $skip = intval($options['page']) * $nlimit;
//    }
//
//    if ($options['n_results'] == false) {
//        $query = "SELECT * FROM cms_users_visited_places $where ORDER BY $orderby $order";
//        if (!is_null($options['limit'])) {
//            $query .= " LIMIT $skip, $nlimit";
//        }
//        $ret = db_query($query);
//        if (!$ret || (db_num_rows($ret) == 0)) {
//            return false;
//        } else {
//            $ret_arr = array();
//            while ($row = db_fetch_array($ret)) {
//                $ret_arr[] = $row;
//            }
//            return $ret_arr;
//        }
//    } else {
//        $query = "SELECT
//					COUNT(id)
//				FROM cms_users_visited_places $where";
//
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
//
//        return $row[0];
//    }
//}

/**
 * insert the default user privacy extand 
 * @param integer $user_id the desired user's id
 * @param integer $entity_id which entity key. required.
 * @param integer $entity_type what type of entity. required.
 * @return true the cms_users_privacy_extand record or false if not found 
 */
function AddUserPrivacyExtand($user_id, $entity_type, $entity_id) {


    global $dbConn;
    $params = array();  
    $query = "INSERT INTO cms_users_privacy_extand (user_id,entity_type,entity_id,kind_type,users,users_list)
	      VALUES (:User_id,:Entity_type,:Entity_id,2,'','')";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $ret    = $insert->execute();
    return ( $ret ) ? true : false;


}

/**
 * edits a user privacy extand info
 * @param array $new_info the new cms_users_privacy_extand info
 * @return boolean true|false if success|fail
 */
function userPrivacyExtandEdit($new_info) {
    global $dbConn;
	$params  = array();  
	$params2 = array();  
	$params3 = array();  

    $kind_type_array = explode(',', $new_info['kind_type']);
    $kind_users_array = explode(',', $new_info['users']);
    $users_list_array = array();
    if ($new_info['users'] != '' || sizeof($kind_type_array) > 1) {
        foreach ($kind_type_array as $kind_type) {
            if ($kind_type == USER_PRIVACY_COMMUNITY) {
                $friends_list = userGetFreindList($new_info['user_id']);
                foreach ($friends_list as $freind_row) {
                    if (!in_array($freind_row['id'], $users_list_array)) {
                        $users_list_array[] = $freind_row['id'];
                    }
                }
                if (!in_array($new_info['user_id'], $users_list_array)) {
                    $users_list_array[] = $new_info['user_id'];
                }
            } else if ($kind_type == USER_PRIVACY_COMMUNITY_EXTENDED) {
                $friends_list = userGetFreindList($new_info['user_id']);
                $friends = array();
                foreach ($friends_list as $freind_row) {
                    $friends[] = $freind_row['id'];
                }
                $friends[] = $new_info['user_id'];

                $extended_friends_list = userGetExtendedFriendList($friends);

                foreach ($extended_friends_list as $entended_friend_row) {
                    if (!in_array($entended_friend_row['id'], $users_list_array)) {
                        $users_list_array[] = $entended_friend_row['id'];
                    }
                }
                if (!in_array($new_info['user_id'], $users_list_array)) {
                    $users_list_array[] = $new_info['user_id'];
                }
            } else if ($kind_type == USER_PRIVACY_FOLLOWERS) {
                $followers_list = userFollowersList($new_info['user_id']);

                foreach ($followers_list as $followers_users_row) {
                    if (!in_array($followers_users_row['id'], $users_list_array)) {
                        $users_list_array[] = $followers_users_row['id'];
                    }
                }
                if (!in_array($new_info['user_id'], $users_list_array)) {
                    $users_list_array[] = $new_info['user_id'];
                }
            }
        }
    }

    if ($new_info['users'] != '') {
        $users_list_array_data = array_merge($users_list_array, $kind_users_array);
    } else {
        $users_list_array_data = $users_list_array;
    }
    $users_list_array = implode(',', $users_list_array_data);
    $query = "SELECT * FROM cms_users_privacy_extand WHERE user_id=:User_id AND entity_type=:Entity_type AND entity_id=:Entity_id";
	$params[] = array( "key" => ":User_id",
                            "value" =>$new_info['user_id']);
	$params[] = array( "key" => ":Entity_type",
                            "value" =>$new_info['entity_type']);
	$params[] = array( "key" => ":Entity_id",
                            "value" =>$new_info['entity_id']);        
    $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $query = "UPDATE cms_users_privacy_extand SET ";
        $i=0;
        foreach ($new_info as $key => $val) {
            if ($key != 'id' && $key != 'user_id' && $key != 'entity_type' && $key != 'entity_id') {
                $query .= " $key = :Val$i,";
                $params2[] = array( "key" => ":Val$i", "value" =>$val);
            }
            $i++;
        }
        $query = trim($query, ',');
        $query .= " , users_list='$users_list_array' WHERE user_id=:User_id AND entity_type=:Entity_type AND entity_id=:Entity_id";
	$params2[] = array( "key" => ":User_id", "value" =>$new_info['user_id']);
	$params2[] = array( "key" => ":Entity_type", "value" =>$new_info['entity_type']);
	$params2[] = array( "key" => ":Entity_id", "value" =>$new_info['entity_id']);
        $update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params2);
        $update->execute();
        return ( $update ) ? true : false;
    } else {
        $ret = AddUserPrivacyExtand($new_info['user_id'], $new_info['entity_type'], $new_info['entity_id']);
        if ($ret) {
            $query = "UPDATE cms_users_privacy_extand SET ";
            $i = 0;
            foreach ($new_info as $key => $val) {
                if ($key != 'id' && $key != 'user_id' && $key != 'entity_type' && $key != 'entity_id') {
                    $query .= " $key = :Val$i,";
                    $params3[] = array( "key" => ":Val$i", "value" =>$val);
                }
                $i++;
            }
            $query = trim($query, ',');
            $query .= " , users_list='$users_list_array' WHERE user_id=:User_id AND entity_type=:Entity_type AND entity_id=:Entity_id";
            $params3[] = array( "key" => ":User_id", "value" =>$new_info['user_id']);
            $params3[] = array( "key" => ":Entity_type", "value" =>$new_info['entity_type']);
            $params3[] = array( "key" => ":Entity_id", "value" => $new_info['entity_id'] );
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params3);
            $res    = $update->execute();
            return ( $res ) ? true : false;
        } else {
            return false;
        }
    }
}

/**
 * gets the user privacy extand
 * @param integer $user_id the user id
 * @param integer $entity_id which entity key. required.
 * @param integer $entity_type what type of entity. required.
 * @return array | false the cms_users_privacy_extand record or false if not found
 */
function GetUserPrivacyExtand($user_id, $entity_id, $entity_type) {


    global $dbConn;
	$params = array();  
    $query = "SELECT * FROM cms_users_privacy_extand WHERE user_id=:User_id AND entity_type=:Entity_type AND entity_id=:Entity_id AND published=1";

    
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
	$params[] = array( "key" => ":Entity_type",
                            "value" =>$entity_type);
	$params[] = array( "key" => ":Entity_id",
                            "value" =>$entity_id);
    $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = $select->fetch();
        return $ret_arr;
    } else {
        return false;
    }


}
/**
 * check the user privacy extand for a given user id
 * @param integer $user_id the user id
 * @param integer $from_id the from id
 * @param integer $entity_id the desired entity id
 * @param integer $entity_type the desired entity type
 * @return true | false 
 */
function checkUserPrivacyExtand($user_id, $from_id, $entity_id, $entity_type) {


    global $dbConn;
	$params = array(); 
    if ($user_id == $from_id) {
        return true;
    }
    $query = "SELECT * FROM cms_users_privacy_extand WHERE user_id=:User_id AND entity_type=:Entity_type AND entity_id=:Entity_id AND published=1";
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
	$params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch();
        $kind_type_array = explode(',', $row['kind_type']);

        // user not logged
        if (intval($from_id) == 0) {
            if (intval($row['kind_type']) == USER_PRIVACY_PUBLIC) {
                return true;
            } else {
                return false;
            }
        } else if (sizeof($kind_type_array) == 1 && $row['kind_type'] != '') {
            if (intval($row['kind_type']) == USER_PRIVACY_PUBLIC) {
                return true;
            } else if (intval($row['kind_type']) == USER_PRIVACY_PRIVATE) {
                return false;
            } else if (intval($row['kind_type']) == USER_PRIVACY_COMMUNITY) {
                if (userIsFriend($user_id, $from_id)) {
                    return true;
                } else {
                    $check_array = explode(',', $row['users']);
                    return checkExistFromId($from_id, $check_array);
                }
            } else if (intval($row['kind_type']) == USER_PRIVACY_COMMUNITY_EXTENDED) {
                $tuber_array = getFriendsOfFriendsList($user_id);
                if (checkExistFromId($from_id, $tuber_array) || userIsFriend($user_id, $from_id)) {
                    return true;
                } else {
                    $check_array = explode(',', $row['users']);
                    return checkExistFromId($from_id, $check_array);
                }
            } else if (intval($row['kind_type']) == USER_PRIVACY_FOLLOWERS) {
                if (userSubscribed($user_id, $from_id)) {
                    return true;
                } else {
                    $check_array = explode(',', $row['users']);
                    return checkExistFromId($from_id, $check_array);
                }
            } else {
                $check_array = explode(',', $row['users']);
                return checkExistFromId($from_id, $check_array);
            }
        } else {
            if (in_array(USER_PRIVACY_COMMUNITY, $kind_type_array)) {
                if (userIsFriend($user_id, $from_id)) {
                    return true;
                }
            }
            if (in_array(USER_PRIVACY_COMMUNITY_EXTENDED, $kind_type_array)) {
                $tuber_array = getFriendsOfFriendsList($user_id);
                if (checkExistFromId($from_id, $tuber_array) || userIsFriend($user_id, $from_id)) {
                    return true;
                }
            }
            if (in_array(USER_PRIVACY_FOLLOWERS, $kind_type_array)) {
                if (userSubscribed($user_id, $from_id)) {
                    return true;
                }
            }

            $check_array = explode(',', $row['users']);
            return checkExistFromId($from_id, $check_array);
        }
    } else {
        return true;
    }


}

/**
 * check the user privacy extand for a given user id
 * @param integer $user_id the user id
 * @param integer $from_id the from id
 * @param integer $entity_type the desired entity type (SOCIAL_ENTITY_USER_EVENTS, SOCIAL_ENTITY_PROFILE_FRIENDS, SOCIAL_ENTITY_PROFILE_FOLLOWERS, ...)
 * @return true | false 
 */
function checkUserPrivacyExtandNetwork($user_id, $from_id, $entity_type) {


    global $dbConn;
	$params = array();  
    $query = "SELECT * FROM cms_users_privacy_extand WHERE user_id=:User_id AND entity_type=:Entity_type AND published=1";
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch();
        $kind_type_array = explode(',', $row['kind_type']);

        // user not logged
        if (intval($from_id) == 0) {
            if (intval($row['kind_type']) == USER_PRIVACY_PUBLIC) {
                return true;
            } else {
                return false;
            }
        } else if (sizeof($kind_type_array) == 1 && $row['kind_type'] != '') {
            if (intval($row['kind_type']) == USER_PRIVACY_PUBLIC) {
                return true;
            } else if (intval($row['kind_type']) == USER_PRIVACY_PRIVATE) {
                return false;
            } else if (intval($row['kind_type']) == USER_PRIVACY_COMMUNITY) {
                if (userIsFriend($user_id, $from_id)) {
                    return true;
                } else {
                    $check_array = explode(',', $row['users']);
                    return checkExistFromId($from_id, $check_array);
                }
            } else if (intval($row['kind_type']) == USER_PRIVACY_COMMUNITY_EXTENDED) {
                $tuber_array = getFriendsOfFriendsList($user_id);
                if (checkExistFromId($from_id, $tuber_array) || userIsFriend($user_id, $from_id)) {
                    return true;
                } else {
                    $check_array = explode(',', $row['users']);
                    return checkExistFromId($from_id, $check_array);
                }
            } else if (intval($row['kind_type']) == USER_PRIVACY_FOLLOWERS) {
                if (userSubscribed($user_id, $from_id)) {
                    return true;
                } else {
                    $check_array = explode(',', $row['users']);
                    return checkExistFromId($from_id, $check_array);
                }
            } else {
                $check_array = explode(',', $row['users']);
                return checkExistFromId($from_id, $check_array);
            }
        } else {
            if (in_array(USER_PRIVACY_COMMUNITY, $kind_type_array)) {
                if (userIsFriend($user_id, $from_id)) {
                    return true;
                }
            }
            if (in_array(USER_PRIVACY_COMMUNITY_EXTENDED, $kind_type_array)) {
                $tuber_array = getFriendsOfFriendsList($user_id);
                if (checkExistFromId($from_id, $tuber_array) || userIsFriend($user_id, $from_id)) {
                    return true;
                }
            }
            if (in_array(USER_PRIVACY_FOLLOWERS, $kind_type_array)) {
                if (userSubscribed($user_id, $from_id)) {
                    return true;
                }
            }

            $check_array = explode(',', $row['users']);
            return checkExistFromId($from_id, $check_array);
        }
    } else {
        return true;
    }


}

function checkExistFromId($id, $array) {
    if (in_array($id, $array)) {
        return true;
    } else {
        return false;
    }
}

/**
 * returns the relative path to a user event directory
 * @param array $vinfo the cms_users_event record
 * @return string
 */
function GetRelativeImageEventPath($vinfo) {
    global $CONFIG;
    $videoPath = $CONFIG['video']['uploadPath'];
    $rpath = $vinfo['relativepath'];
    return $videoPath . 'events/' . $rpath;
}

function getEventThumbPath($vinfo,$pathlk="") {
    if ($vinfo['photo'] != '') {
        $repath = GetRelativeImageEventPath($vinfo);
        $repath .='thumb/' . $vinfo['photo'];
        $repath = $pathlk.ReturnLink($repath);
    } else {
        $repath = ReturnLink('media/images/' . LanguageGet() . '/eventsdetailed/eventthemephoto.jpg?x=' . rand());
    }
    return $repath;
}

/**
 * gets the user privacy for a given user 
 * @param integer $user_id the user id
 * @return array | false the cms_users_privacy record or false if not found
 */
function getUserNotifications($user_id) {


    global $dbConn;
	$params  = array();
	$params2 = array();
//    $query = "SELECT * FROM cms_users_privacy WHERE user_id='$user_id' LIMIT 1";
//    $ret = db_query($query);
    $query = "SELECT * FROM cms_users_privacy WHERE user_id=:User_id LIMIT 1";
	$params[] = array( "key" => ":User_id",
                            "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $select->execute();

    $ret    = $select->rowCount();
    if ($select && $ret != 0) {
//        $ret_arr = array();$select->fetchAll(PDO::FETCH_ASSOC);
        $ret_arr = $select->fetchAll(PDO::FETCH_ASSOC);
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row;
//        }
        return $ret_arr;
    } else {
        if (AddUserPrivacy($user_id)) {
//            $query = "SELECT * FROM cms_users_privacy WHERE user_id='$user_id' LIMIT 1";
//            $ret = db_query($query);
            $query = "SELECT * FROM cms_users_privacy WHERE user_id=:User_id LIMIT 1";
	$params2[] = array( "key" => ":User_id",
                            "value" =>$user_id);
            $select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params2);
            $res    = $select->execute();

            $ret    = $select->rowCount();
            if ($res && $ret != 0) {
//                $ret_arr = array();
                $ret_arr = $select->fetchAll();
//                while ($row = db_fetch_array($ret)) {
//                    $ret_arr[] = $row;
//                }
                return $ret_arr;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


}

/**
 * edits a user privacy info
 * @param array $new_info the new cms_users_privacy info
 * @return boolean true|false if success|fail
 */
function userPrivacyEdit($new_info) {
    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array(); 
    $query = "SELECT * FROM cms_users_privacy WHERE user_id=:User_id";
    $params[] = array( "key" => ":User_id", "value" =>$new_info['user_id']);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret = $select->rowCount();
    
    if ($res && $ret != 0) {
        $query = "UPDATE cms_users_privacy SET ";
        $i=0;
        foreach ($new_info as $key => $val) {
            if ($key != 'id' && $key != 'user_id') {
                $query .= " $key = :Val".$i.",";
                $params2[] = array( "key" => ":Val".$i, "value" =>$val);
                $i++;
            }
        }
        $query = trim($query, ',');
        $query .= " WHERE user_id=:User_id";
	$params2[] = array( "key" => ":User_id", "value" =>$new_info['user_id']);
            
        $update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params2);
        $res = $update->execute();
        
        return ( $res ) ? true : false;
    } else {
        $ret = AddUserPrivacy($new_info['user_id']);
        if ($ret) {
            $query = "UPDATE cms_users_privacy SET ";
            $i=0;
            foreach ($new_info as $key => $val) {
                if ($key != 'id' && $key != 'user_id') {
                    $query .= " $key = :Val".$i.",";
                    $params3[] = array( "key" => ":Val".$i, "value" =>$val);
                    $i++;
                }
            }
            $query = trim($query, ',');
            $params3[] = array( "key" => ":User_id", "value" =>$new_info['user_id']);
            $query .= " WHERE user_id=:User_id";        
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params3);
            $res    = $update->execute();
            return ( $res ) ? true : false;
        } else {
            return false;
        }
    }
}

/**
 * insert the default user privacy given the user id
 * @param integer $user_id the desired user id
 * @return true the cms_users_privacy record or false if not found 
 */
function AddUserPrivacy($user_id) {


    global $dbConn;
	$params  = array();  
	$params2 = array();  
    $query = "SELECT * FROM cms_users_privacy WHERE user_id=:User_id";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || $ret == 0) {
        $query = "INSERT
        INTO
        cms_users_privacy
                (user_id)
        VALUES
                (:User_id)";
	$params2[] = array( "key" => ":User_id",
                             "value" =>$user_id);
        $insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params2);
        $res    = $insert->execute();
        return ( $res ) ? true : false;
    } else {
        return true;
    }


}

/**
 * Gets Views number for a user' s albums
 * @param integer $user_id the desired user id
 * @param integer $channel_id the desired channel id
 * @return integer
 */
function getUserAlbumViews($user_id, $channel_id = 0) {


    global $dbConn;
	$params = array();  
//    $query = "SELECT SUM( `nb_views` )
//        FROM `cms_users_catalogs`
//        WHERE `user_id` ='" . $user_id . "' AND `channelid` ='" . $channel_id . "' and `published`= '1'";
//    $ret = db_query($query);
//    if ($ret && db_num_rows($ret) != 0) {
//        $row = db_fetch_array($ret);
//        return $row[0];
//    } else {
//        return 0;
//    }
    $query = "SELECT SUM( `nb_views` ) FROM `cms_users_catalogs` WHERE `user_id` =:User_id AND `channelid` =:Channel_id and `published`= '1'";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Channel_id", "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $row = $select->fetch();
        return $row[0];
    } else {
        return 0;
    }


}

/**
 * Gets report reason list for a givent entity type
 * @param integer $entity_type the desired entity type
 * @return array
 */
function getReportReasonList($entity_type) {


    global $dbConn;
    $params = array(); 
    $lang_code = LanguageGet();
    $languageSel = '';
    $languageAnd = '';
    $languageJoin = '';
    if ($lang_code != 'en') {
        $languageSel = ',ml.title as ml_title';
//        $languageAnd= " and ml.lang_code = $lang_code";
        $languageAnd= " and ml.lang_code = :Lang_code";
        $languageJoin = ' INNER JOIN ml_report_reason ml on c.id = ml.entity_id ';
        $params[] = array( "key" => ":Lang_code", "value" =>$lang_code);
    }
    $query = "SELECT c.id as id,c.reason as reason, c.entity_type as entity_type$languageSel FROM `cms_report_reason` as c $languageJoin WHERE FIND_IN_SET( :Entity_type , CONCAT( c.entity_type ) )$languageAnd  ORDER BY id ASC";
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
//    exit($query);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);    
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret != 0) {
        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            if ($lang_code == 'en') {
//                $ret1 = $row;
//            } else {
//                $ret1['id'] = $row['id'];
//                $ret1['reason'] = htmlEntityDecode($row['ml_title']);
//                $ret1['entity_type'] = htmlEntityDecode($row['entity_type']);
//            }
//            $ret_arr[] = $ret1;
//        }
        $row    = $select->fetchAll(PDO::FETCH_ASSOC);
        foreach($row as $row_item){
             if ($lang_code == 'en') {
                $ret1 = $row_item;
            } else {
                $ret1['id'] = $row_item['id'];
                $ret1['reason'] = htmlEntityDecode($row_item['ml_title']);
                $ret1['entity_type'] = htmlEntityDecode($row_item['entity_type']);
            }
            $ret_arr[] = $ret1;
        }
        return $ret_arr;
    } else {
        return array();
    }


}

/**
 * add report for a givent entity type 
 * @param integer $user_id the user id
 * @param integer $owner_id the owner of the entity type
 * @param integer $entity_id the entity id
 * @param integer $entity_type the entity type
 * @param integer $channel_id the channel id
 * @param string $msg the message
 * @param string $reason the reason
 * @param string $title the title
 * @param string $email the email
 * @return integer | false the newly inserted cms_report id or false if not inserted
 */
function AddReportData($user_id, $owner_id, $entity_id, $entity_type, $channel_id, $msg, $reason, $title, $email) {


    global $dbConn;
    $params = array();
    $query = "INSERT INTO cms_report (user_id, owner_id,entity_id,entity_type,channel_id,msg,reason,title,email)
            VALUES (:User_id,:Owner_id,:Entity_id,:Entity_type,:Channel_id,:Msg,:Reason,:Title,:Email)";
    
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Owner_id", "value" =>$owner_id);
    $params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array( "key" => ":Channel_id", "value" =>$channel_id);
    $params[] = array( "key" => ":Msg", "value" =>$msg);
    $params[] = array( "key" => ":Reason", "value" =>$reason);
    $params[] = array( "key" => ":Title", "value" =>$title);
    $params[] = array( "key" => ":Email", "value" =>$email);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    if( $res ) $sert_id = $dbConn->lastInsertId();
    return ( $res ) ? $sert_id : false;


}

/**
 * validation of date format
 * @return true | false 
 */
function validateDateFormat($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

/**
 * get the original Profile Pic size
 * @return the name of the pic
 */
function getOriginalPP($link) {
    if(strpos($link, 'Profile_') == 0){
        $link = substr($link, 8) ;
    }
    return $link;
}

/**
 * used to store user ip address and browser/device info
 * @param type $ip_address
 * @param type $forwarded_ip_address
 * @param type $user_agent
 * @param type $user_id
 * @return type 
 */
function user_login_track($ip_address, $forwarded_ip_address, $user_agent, $user_id){
    global $dbConn;
    $params = array();  
    $query = "INSERT INTO cms_tubers_login_tracking (ip_address, forwarded_ip_address, user_agent, user_id) VALUES (:Ip_address, :Forwarded_ip_address, :User_agent, :User_id)";
    $params[] = array( "key" => ":Ip_address",
                        "value" =>$ip_address);
    $params[] = array( "key" => ":Forwarded_ip_address",
                        "value" =>$forwarded_ip_address);
    $params[] = array( "key" => ":User_agent",
                        "value" =>$user_agent);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert, $params);
    $res =  $insert->execute();
    return $res;
}

/**
 * register a user
 * @param string $fname
 * @param string $lname
 * @param string $FullName
 * @param string $YourEmail
 * @param string $YourCountry
 * @param string $YourIP
 * @param date $YourBdaySave
 * @param string $YourUserName
 * @param string $YourPassword
 * @return boolean true|false if succees|fail 
 */
function userFacebookRegister($FullName, $YourEmail, $fname, $lname, $gender, $birthday ,$fb_token, $fb_user) {
//    echo $fb_token;exit;
    global $dbConn;
    $params  = array(); 
    switch($gender){
        case "male":
            $gender = "M";
            break;
        case "female":
            $gender = "F";
            break;
        default:
            $gender = "O";
            break;
    }
    $InsertUserSQL = "INSERT INTO cms_users(FullName,fname,lname,YourEmail,YourUserName,gender,YourBday,fb_token,fb_user,published) ";

    $InsertUserSQL .= "VALUES(:FullName,:Fname,:Lname,:YourEmail,:YourUserName,:Gender,:YourBday,:Fb_token,:Fb_user,1  )";
    
    $params[] = array( "key" => ":FullName", "value" =>$FullName);
    $params[] = array( "key" => ":Fname", "value" =>$fname);
    $params[] = array( "key" => ":Lname", "value" =>$lname);
    $params[] = array( "key" => ":YourEmail", "value" =>$YourEmail);
    $params[] = array( "key" => ":YourUserName", "value" =>$YourEmail);
    $params[] = array( "key" => ":Gender", "value" =>$gender);
    $params[] = array( "key" => ":YourBday", "value" =>$birthday);
    $params[] = array( "key" => ":Fb_token", "value" =>$fb_token);
    $params[] = array( "key" => ":Fb_user", "value" =>$fb_user);
    $insert = $dbConn->prepare($InsertUserSQL);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();    
    
    if ($res) {
        $usid = $dbConn->lastInsertId();
        AddUserPrivacy($usid);      
        
        return true;
    } else {
        return false;
    }
}

/**
 * Disables a user
 * @param integer $uid the user id
 *  boolean true|false if success|fail
 */
function userUpdateFbUser($uid,$fb_user) {
    global $dbConn;
    $params  = array();  
    $params2 = array();
    $query = "UPDATE cms_users SET fb_user = :Fb_user WHERE id=:Uid";
    $params[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $params[] = array( "key" => ":Fb_user",
                        "value" =>$fb_user);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return true;
}

/*
  Contact Privacy Constant PUBLIC
 */
define('CONTACT_PRIVACY_PUBLIC', 1);
/*
  Contact Privacy Constant COMMUNIY
 */
define('CONTACT_PRIVACY_COMMUNIY', 2);
/*
  Contact Privacy Constant FRIENDS COMMUNITY
 */
define('CONTACT_PRIVACY_FRIENDS_COMMUNITY', 3);

/*
  Content Privacy Constant PUBLIC
 */
define('CONTENT_PRIVACY_PUBLIC', 1);
/*
  Content Privacy Constant FRIENDS
 */
define('CONTENT_PRIVACY_FRIENDS', 2);
/*
  Content Privacy Constant SELECTED FRIENDS
 */
define('CONTENT_PRIVACY_SELECTED _FRIENDS', 3);
/*
  Content Privacy Constant PRIVATE
 */
define('CONTENT_PRIVACY_PRIVATE', 4);