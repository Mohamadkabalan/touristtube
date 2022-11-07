<?php
if($loggedUser==42 || $loggedUser==1674 ){
    $path = "";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/twigFct.php" );

    $user_ID = userGetID();
    $userInfo = getUserInfo($user_ID);
    $data['chat_lang']=  LanguageGet();
    $data['user_ID']=  $user_ID;
    $data['profile_pic'] = ReturnLink('media/tubers/' . $userInfo['profile_Pic'] );
    $data['full_name'] = $userInfo['FullName'];
    $data['crop_profile_pic']   =   userCroppedPhoto($userInfo['profile_Pic']);
    //$options = array(
    //    'orderby' => 'id',
    //    'is_visible' => -1,
    //    'order' => 'd',
    //    'type' => array(4) , 
    //    'userid' => $user_ID
    //);
    //$blocked_users = userFriendSearch($options);
    $data['chat_date']=  date('Y-m-d H:i:s');
}