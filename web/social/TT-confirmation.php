<?php
	$path = "";
	$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0 );
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "services/lib/chat.inc.php" );
	
	$id_channel = UriGetArg('channel');
	$id_channel = ($id_channel=="") ? null : $id_channel;
	
	$id_channelHome = UriGetArg('channelHome');
	$id_channelHome = ( !isset($id_channelHome) ) ? null : $id_channelHome;
	
	$id_channelSettings = UriGetArg('channelSettings');
	$id_channelSettings = ( !isset($id_channelSettings) ) ? null : $id_channelSettings;
        
        $id_TSettings = UriGetArg('TSettings');
	$id_TSettings = ( !isset($id_TSettings) ) ? null : $id_TSettings;
	
        $id_TFriendship = UriGetArg('TFriendship');
	$id_TFriendship = ( !isset($id_TFriendship) ) ? null : $id_TFriendship;
        
        $id_TSubChannel = UriGetArg('TSubChannel');
	$id_TSubChannel = ( !isset($id_TSubChannel) ) ? null : $id_TSubChannel;
        
        $id_TFriend = UriGetArg('TFriend');
	$id_TFriend = ( !isset($id_TFriend) ) ? null : $id_TFriend;
	
        $id_TFollower = UriGetArg('TFollower');
	$id_TFollower = ( !isset($id_TFollower) ) ? null : $id_TFollower;
        
        $todisplayerror='';
	$userid=userGetID();
	$user_is_logged=0;
	if(userIsLogged()){
		$user_is_logged=1;
	}
	$user_is_owner=0;
	if(!is_null($id_channel) && $user_is_logged){
		$channel_info=checkChannelOwner( $id_channel , $userid );
		$user_is_owner=1;		
		
		if(!$channel_info){
			$user_is_owner=0;	
			chatDisconnect(userGetID());
			userLogout();
		}else{
                    $date_now=date('Y-m-d',strtotime($channel_info['create_ts']));
                    $date_now_before= date('Y-m-d', time() - 604800);
                    if($date_now>=$date_now_before){
                        header('Location:' . ReturnLink('create-channel/'.$channel_info['id']) );
                    }else{
                        channelEdit(array('id'=>$channel_info['id'],'published'=>-2,'deactivated_ts'=>0));
                        $todisplayerror= _('your<br/>activation link<br/>has expired');
                    }
		}
	}else if(!is_null($id_channelHome) && $user_is_logged){
		$channel_info=checkChannelOwner( $id_channelHome , $userid );
		$user_is_owner=1;		
		
		if(!$channel_info){
			$user_is_owner=0;	
			chatDisconnect(userGetID());
			userLogout();
		}else{
			header('Location:' .channelMainLink($channel_info) );	
		}
	}else if(!is_null($id_channelSettings) && $user_is_logged){
		$channel_info=checkChannelOwner( $id_channelSettings , $userid );
		$user_is_owner=1;		
		
		if(!$channel_info){
			$user_is_owner=0;	
			chatDisconnect(userGetID());
			userLogout();
		}else{
			header('Location:' .ReturnLink('channel-settings/'.$channel_info['id']) );	
		}
	}else if(!is_null($id_TSettings) && $user_is_logged){
		$user_info=unsubscribeUserEmail( $id_TSettings );
		$user_is_owner=1;
		if(!$user_info){
                    $user_is_owner=0;	
                    chatDisconnect(userGetID());
                    userLogout();
		}else if( $user_info['id'] !=$userid ){
                    $user_is_owner=0;	
                    chatDisconnect(userGetID());
                    userLogout();
		}else {
//                    header('Location:' .ReturnLink('account/notifications') );
                    header('Location:' .ReturnLink('account/info') );
		}
	}else if(!is_null($id_TSubChannel) && $user_is_logged){
            $relation_val = getRelationRequestMD5($id_TSubChannel);
            if($relation_val){
                $parent_id = $relation_val['parent_id'];
                $channel_id = $relation_val['channelid'];
                if($relation_val['relation_type']==CHANNEL_RELATION_TYPE_PARENT){
                    $channel_info = channelGetInfo($parent_id);
                }else{
                    $channel_info = channelGetInfo($channel_id);
                }
                $uinfo = getUserInfo($channel_info['owner_id']);
                if($uinfo['id']==$userid){
                    $opts = array(
                        'channelid' => $channel_id,
                        'parent_id' => $parent_id,
                        'published' => 1
                    );
                    channelRelationEdit($opts);
                    header('Location:/' );
                }else{                
                    $user_is_owner=0;	
                    chatDisconnect(userGetID());
                    userLogout();
                }
            }else{                
                header('Location:/' );
            }
	}else if(!is_null($id_TFriendship) && $user_is_logged){           
            $accept_val = acceptFriendshipRequest($id_TFriendship,$userid);            
            $user_info = getUserInfo($userid);
            
            if(!$accept_val){
                $user_is_owner=0;	
                chatDisconnect(userGetID());
                userLogout();
            }else{                
                $user_is_owner=1;
                header('Location:' .userProfileLink($user_info) );
            }
	}else if(!is_null($id_TFriend) && $user_is_logged){
		$user_info=unsubscribeUserEmail( $id_TFriend );
		$user_is_owner=1;
		if(!$user_info){
                    $user_is_owner=0;	
                    chatDisconnect(userGetID());
                    userLogout();
		}else if( $user_info['id'] !=$userid ){
                    $user_is_owner=0;	
                    chatDisconnect(userGetID());
                    userLogout();
		}else {
                    header('Location:' .userProfileLink($user_info) );
		}
	}else if(!is_null($id_TFollower) && $user_is_logged){
		$user_info=unsubscribeUserEmail( $id_TFollower );
		$user_is_owner=1;
		if(!$user_info){
                    $user_is_owner=0;	
                    chatDisconnect(userGetID());
                    userLogout();
		}else if( $user_info['id'] !=$userid ){
                    $user_is_owner=0;	
                    chatDisconnect(userGetID());
                    userLogout();
		}else {
                    header('Location:' .userProfileLink($user_info) );
		}
	}
	
	$includes = array("assets/tuber/js/TT-confirmation.js","css/TT-confirmation.css",
	'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,);

	if (userIsLogged() && userIsChannel()) {
            array_unshift($includes, 'css/channel-header.css');
           tt_global_set('includes', $includes);
            include("TopChannel.php");
        } else {
           tt_global_set('includes', $includes);
            include("TopIndex.php");
        }
?>

<div id="MiddleBody">
	<div class="StaticBody">
    
    	<?php if($user_is_owner==0){?>
            <div class="confirm_container">
                <div class="confirm_text"><?php echo _('Please enter your email or username'); ?></div>
                <input type="text" value="<?php print _("yourname@email.com");?>" name="confirm_name" id="confirm_email" class="confirm_input" data-value="<?php print _("yourname@email.com");?>" onfocus="removeValue2(this)" onblur="addValue2(this)"/>
                <div class="confirm_text margintop10"><?php echo _('Please enter your password'); ?></div>
                <input type="password" value="<?php print _("Your Password");?>" name="confirm_pass" id="confirm_pass" class="confirm_input" data-value="<?php print _("Your Password");?>" onfocus="removeValue2(this)" onblur="addValue2(this)" onKeyPress="return checkSubmitConfirm(event)"/>
                <div class="confirm_but" id="confirm_but1"><?php echo _('confirm'); ?></div>
                <!--<div class="confirm_seperator"></div>
                <div class="confirm_but" id="confirm_but2"><?php print _("cancel");?></div>-->
            </div>
        <?php } ?> 
          <div class="activation_wrong_credentials"><?php echo $todisplayerror; ?> </div>
	</div>
</div>
<?php include("closing-footer.php");?>