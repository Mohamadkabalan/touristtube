<?php

//	session_id($_REQUEST['S']);
//	session_start();
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//        if( !$userID ) die();
        
//        $id=intval($_GET['id']);
//        $data_not=intval($_GET['data_not']);
        $id=intval($request->query->get('id',''));
        $data_not=intval($request->query->get('data_not',''));
//        $user_id = mobileIsLogged($_REQUEST['S']);
        $user_id = mobileIsLogged($submit_post_get['S']);
        if( !$user_id ) die();

        if($data_not==1){
            $options_log = array(
                'limit' => 1,
                'page' => 0,
                'feed_id' => $id,
                'orderby' => 'id',
                'order' => 'd',
                'is_notification' => 1,
                'userid' => $user_id
            );
            $news_feed = newsfeedGroupingNotificationsSearch( $options_log );
        }else{
            $options = array ( 
                'limit' => 1 , 
                'feed_id' => $id,
                'page' => 0 , 
                'userid' => $user_id ,
                'orderby' => 'id' ,
                'order' => 'd', 
                'channel_id' => null ,
                'is_visible' => -1 
            );
            $news_feed = newsfeedPageSearch( $options );
        }
        function getTubeDisp($action_row_otherIT,$action_type,$user_id){
            $buts ='';
            $str ='';
            $uslnkothPic ='';
            switch($action_type){
                case SOCIAL_ACTION_LIKE:
                case SOCIAL_ACTION_RATE:
                case SOCIAL_ACTION_FOLLOW:
                case SOCIAL_ACTION_FRIEND:
                case SOCIAL_ACTION_EVENT_JOIN:
                case SOCIAL_ACTION_CONNECT:
                    $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                    $uslnkothPic = ReturnLink('media/tubers/'.$action_profiletb['profile_Pic'] );
                    $uslnkoth= userProfileLink($action_profiletb);
                    $uslnkothname= returnUserDisplayName($action_profiletb);
                    $userIschannel = ($action_profiletb['isChannel']==1) ? 1 : 0;
                    if($userIschannel==0){
                        $usisfriend = userIsFriend($user_id,$action_profiletb['id']);
                        if(!$usisfriend){
                            $usisfriend = userFreindRequestMade($user_id,$action_profiletb['id']);	
                        }
                        if(!$usisfriend) $buts = 'add as friend';
                    }
                break;
                case SOCIAL_ACTION_SPONSOR:
                    $channel_arraygroup=channelGetInfo($action_row_otherIT['action_row']['from_user']);
                    $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                    $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);
                    $uslnkothPic = ($channel_arraygroup['logo']) ? photoReturnchannelLogo($channel_arraygroup) : ReturnLink('/media/tubers/tuber.jpg');
                break;
                case SOCIAL_ACTION_COMMENT:
                case SOCIAL_ACTION_REECHOE:
                    if( intval($action_row_otherIT['channel_id']) !=0 ){                                
                            $channel_arraygroup=channelGetInfo($action_row_otherIT['channel_id']);
                            if($channel_arraygroup['owner_id']==$action_row_otherIT['user_id']){
                                $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                $uslnkothPic = ($channel_arraygroup['logo']) ? photoReturnchannelLogo($channel_arraygroup) : ReturnLink('/media/tubers/tuber.jpg');
                                $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                            }else{
                                $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                                $uslnkothPic = ReturnLink('media/tubers/'.$action_profiletb['profile_Pic'] );
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);
                                $userIschannel = ($action_profiletb['isChannel']==1) ? 1 : 0;
                                if($userIschannel==0){
                                    $usisfriend = userIsFriend($user_id,$action_profiletb['id']);
                                    if(!$usisfriend){
                                        $usisfriend = userFreindRequestMade($user_id,$action_profiletb['id']);	
                                    }
                                    if(!$usisfriend) $buts = 'add as friend';
                                }
                            }
                    }else{
                        $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                        $uslnkothPic = ReturnLink('media/tubers/'.$action_profiletb['profile_Pic'] );
                        $uslnkoth= userProfileLink($action_profiletb);
                        $uslnkothname= returnUserDisplayName($action_profiletb);
                        $userIschannel = ($action_profiletb['isChannel']==1) ? 1 : 0;
                        if($userIschannel==0){
                            $usisfriend = userIsFriend($user_id,$action_profiletb['id']);
                            if(!$usisfriend){
                                $usisfriend = userFreindRequestMade($user_id,$action_profiletb['id']);	
                            }
                            if(!$usisfriend) $buts = 'add as friend';
                        }
                    }                        
                break;
                case SOCIAL_ACTION_SHARE:
                case SOCIAL_ACTION_INVITE:
                    if( intval($action_row_otherIT['channel_id']) !=0 ){
                            $channel_arraygroup=channelGetInfo($action_row_otherIT['channel_id']);
                            if($channel_arraygroup['owner_id']==$action_row_otherIT['action_row']['from_user']){
                                $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                                $uslnkothPic = ($channel_arraygroup['logo']) ? photoReturnchannelLogo($channel_arraygroup) : ReturnLink('/media/tubers/tuber.jpg');
                                $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);                                    
                            }else{
                                $action_profiletb = getUserInfo($action_row_otherIT['action_row']['from_user']);
                                $uslnkothPic = ReturnLink('media/tubers/'.$action_profiletb['profile_Pic'] );
                                $uslnkoth= userProfileLink($action_profiletb);
                                $uslnkothname= returnUserDisplayName($action_profiletb);
                                $userIschannel = ($action_profiletb['isChannel']==1) ? 1 : 0;
                                if($userIschannel==0){
                                    $usisfriend = userIsFriend($user_id,$action_profiletb['id']);
                                    if(!$usisfriend){
                                        $usisfriend = userFreindRequestMade($user_id,$action_profiletb['id']);	
                                    }
                                    if(!$usisfriend) $buts = 'add as friend';
                                }
                            }
                    }else{
                        $action_profiletb = getUserInfo($action_row_otherIT['action_row']['from_user']);
                        $uslnkothPic = ReturnLink('media/tubers/'.$action_profiletb['profile_Pic'] );
                        $uslnkoth= userProfileLink($action_profiletb);
                        $uslnkothname= returnUserDisplayName($action_profiletb);
                        $userIschannel = ($action_profiletb['isChannel']==1) ? 1 : 0;
                        if($userIschannel==0){
                            $usisfriend = userIsFriend($user_id,$action_profiletb['id']);
                            if(!$usisfriend){
                                $usisfriend = userFreindRequestMade($user_id,$action_profiletb['id']);	
                            }
                            if(!$usisfriend) $buts = 'add as friend';
                        }
                    }
                break;
                case SOCIAL_ACTION_RELATION_PARENT:
                case SOCIAL_ACTION_RELATION_SUB:
                    $channel_arraygroup=$action_row_otherIT['channel_row'];
                    $uslnkoth = ReturnLink('channel/'.$channel_arraygroup['channel_url']);
                    $uslnkothPic = ($channel_arraygroup['logo']) ? photoReturnchannelLogo($channel_arraygroup) : ReturnLink('/media/tubers/tuber.jpg');
                    $uslnkothname= htmlEntityDecode($channel_arraygroup['channel_name']);
                break;
                defaul:
                    $action_profiletb = getUserInfo($action_row_otherIT['user_id']);
                    $uslnkothPic = ReturnLink('media/tubers/'.$action_profiletb['profile_Pic'] );
                    $uslnkoth= userProfileLink($action_profiletb);
                    $uslnkothname= returnUserDisplayName($action_profiletb);
                    $userIschannel = ($action_profiletb['isChannel']==1) ? 1 : 0;
                    if($userIschannel==0){
                        $usisfriend = userIsFriend($user_id,$action_profiletb['id']);
                        if(!$usisfriend){
                            $usisfriend = userFreindRequestMade($user_id,$action_profiletb['id']);	
                        }
                        if(!$usisfriend) $buts = 'add as friend';
                    }
                break;
            }
            $str=array(
                'id' => $action_profiletb['id'],
                'link'=> $uslnkoth,
                'user_pic'=> $uslnkothPic,
                'user_name'=> $uslnkothname,    
                'add_friend' => $buts,
            );
            return $str;
        }
        $res = array();
         foreach( $news_feed as $news ){ 
                $action_type = $news['action_type'];
                $res[]= getTubeDisp($news,$action_type,$user_id);
                $action_row_other = $news['action_row_other'];
                foreach($action_row_other as $action_row_otherIT){
                    $res[]= getTubeDisp($action_row_otherIT,$action_type,$user_id);
                }
            }
        echo json_encode($res);    