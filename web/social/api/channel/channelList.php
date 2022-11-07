<?php
/*! \file
 * 
 * \brief This api returns channel categories
 * 
 * @param S session id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * @param category channel category
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> channel id
 * @return       <b>name</b> channel name
 * @return       <b>slogan</b> channel slogan
 * @return       <b>about</b> channel description
 * @return       <b>logo</b> channel logo path
 * @return       <b>header</b> channel header path
 * @return       <b>bgimage</b> channel background image path
 * @return       <b>bgcolor</b> channel background color
 * @return       <b>hidecreatedon</b> channel hide created on(don't show date)
 * @return       <b>created_on</b> channel created on date
 * @return       <b>hidecreatedby</b> channel hide created by(don't show user)
 * @return       <b>created_by</b> channel created by
 * @return       <b>phone</b> channel phone
 * @return       <b>hidelocation</b> channel hide location
 * @return       <b>location</b> channel location
 * @return       <b>url</b> channel url
 * @return       <b>user_is_connected</b> channel user is connected
 * @return       <b>statistic</b> list with the following keys:(array)
 * @return       		<b>connected_tubers</b> channel connected tubers
 * @return       		<b>sponsors</b> channel sponsors
 * @return       		<b>videos</b> channel videos
 * @return       		<b>photos</b> channel photos
 * @return       		<b>albums</b> channel albums
 * @return       		<b>events</b> channel events
 * @return       		<b>brochures</b> channel brochures
 * @return       		<b>news</b> channel news
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	include("../heart.php");
	
	
//	$user_id = mobileIsLogged($_REQUEST['S']);
//	$start = ($_REQUEST['page'])? intval($_REQUEST['page']) : 0;
//	$limit = ($_REQUEST['limit'])? intval($_REQUEST['limit']) : 100;
//	$category = ($_REQUEST['category'])? intval($_REQUEST['category']) : 0;
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	$user_id = mobileIsLogged($submit_post_get['S']);
	$start = ($submit_post_get['page'])? intval($submit_post_get['page']) : 0;
	$limit = ($submit_post_get['limit'])? intval($submit_post_get['limit']) : 100;
	$category = ($submit_post_get['category'])? intval($submit_post_get['category']) : 0;
		
	$output = array();
	$skip = $start * $limit;
	$channels = allchannelGetInfo($skip,$limit,$category);
	
	if ($channels){
		
		foreach ($channels as $channel){
			
			$id = intval( $channel['id'] );
			
			$channelInfo = channelGetInfo($id);
			$userChannelInfo = getUserInfo($channelInfo['owner_id']);
			//$channelStats = userGetStatistics($channelInfo['owner_id']);
			$catarr = allchannelGetCategory($channelInfo['category']);
			
			$channel_id = $channelInfo['id'];
			$channel_name = $channelInfo['channel_name'];
			$channel_slogan = htmlEntityDecode($channelInfo['slogan']);
			$channel_category = htmlEntityDecode($catarr[0]['title']);
			$channel_location = channelOwnerLocation($channelInfo);
			$channel_phone = $channelInfo['phone'];
//			$channel_logo = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/images/tubers/tuber.jpg');
                        if($channelInfo['logo'] == ''){
                            $channel_logo = 'media/tubers/tuber.jpg';
                        }else{
                            $channel_logo = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo'];
                        }
//			$channel_header = photoReturnchannelHeader($channelInfo);
                        if($channelInfo['header'] == ''){
                            $channel_header = 'media/images/channel/coverphoto.jpg';
                        }
                        else{
                            $channel_header = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['header'];
                        }
                        $channel_desc = htmlEntityDecode($channelInfo['small_description']);
			$channel_default_link = $channelInfo['default_link'];
			$channel_created_by = ($channelInfo['hidecreatedby']==0) ? returnUserDisplayName($userChannelInfo) : '';
			$channel_created_date = ($channelInfo['hidecreatedon']==0) ? date('d/m/Y', strtotime($channelInfo['create_ts'])) : '';
			
			$arraychannellinks = GetChannelExternalLinks($channelInfo['id']);
			
			if($arraychannellinks){
				
                                $socialLinks = array();
                                    foreach($arraychannellinks as $arrlinks){
                                        $socialLinks[] = array(
                                                        'link'=>htmlEntityDecode($arrlinks['link']),
                                                        );
                                    }
				
			}else{
				$socialLinks = array();
			}
			
			$channel_connected_tubers = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'],'n_results' => true ));
			$channel_sponsors = socialSharesGet(array(
				'orderby' => 'share_ts',
				'order' => 'd',
				'entity_id' => $channelInfo['id'],
				'entity_type' => SOCIAL_ENTITY_CHANNEL,
				'share_type' => SOCIAL_SHARE_TYPE_SPONSOR,
				'n_results' => true
			));
		
			$channel_photos = mediaSearch(array(
				'channel_id' => $channelInfo['id'],
				'type' => 'i',
				'catalog_status' => 0,
				'n_results' => true
			));
			$channel_video = mediaSearch(array(
				'channel_id' => $channelInfo['id'],
				'type' => 'v',
				'catalog_status' => 0,
				'n_results' => true
			));
			
			$options = array ( 
                                        'channelid' => $channelInfo['id'] 
                                    );
			$channelalbumInfoCount = userCatalogchannelSearch( $options );
			$channel_album = ($channelalbumInfoCount) ? count($channelalbumInfoCount) : "0";
			
			$options = array( 
                                    'channelid'=>$channelInfo['id'],
                                    'order'=>'d'
                                );
			$channelbrochuresInfoCount = channelbrochureSearch($options);
			$channel_brochure = ($channelbrochuresInfoCount) ? count($channelbrochuresInfoCount) : "0";
			
			$options = array(
                                        'channelid'=>$channelInfo['id'],
                                        'order'=>'d'
                                    );
			$channeleventsInfoCount = channeleventSearch($options);
			$channel_events = ($channeleventsInfoCount) ? count($channeleventsInfoCount) : "0";
			
			$options = array(
                                        'channelid'=>$channelInfo['id'],
                                        'n_results' => true
                                    );	
			$channel_news = channelnewsSearch($options);
			
//			$bg_src = ($channelInfo['bg']) ? photoReturnchannelBG($channelInfo) : '';
                        $bg_src = ($channelInfo['bg']) ? 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['bg'] : '';
			$bg_color = ($channelInfo['bgcolor']) ? ' #'.$channelInfo['bgcolor'] : '';
                        
                        $connected_tubers = getConnectedtubers($channelInfo['id']);
                        $is_connected = "0";
                        $is_owner = "0";
                        if($user_id){
                            if (( $user_id != $channelInfo['owner_id'] ) && ( in_array($user_id, $connected_tubers) )) {
                                $is_connected = "1";
                            }
                            else if (( $user_id == $channelInfo['owner_id'])) {
                                $is_owner = "1";
                            }
                        }

                        $output[]=array(
                            
                                'id'=>$channel_id,
                                'name'=>str_replace('"', "'", $channel_name),
                                'slogan'=>str_replace('"', "'", $channel_slogan),
                                'about'=>str_replace('"', "'",$channel_desc),
                                'logo'=>$channel_logo,
                                'header'=>$channel_header,
                                'bgimage'=>$bg_src,
                                'bgcolor'=>$bg_color,
                                'hidecreatedon'=>$channelInfo['hidecreatedon'],
                                'created_on'=>$channel_created_date,
                                'hidecreatedby'=>$channelInfo['hidecreatedby'],
                                'created_by'=>$channel_created_by,
                                'phone'=>$channel_phone,
                                'hidelocation'=>$channelInfo['hidelocation'],
                                'location'=>$channel_location,
                                'url'=>$channel_default_link,
                                'user_is_owner'=>$is_owner,
                                'user_is_connected'=>$is_connected,
                                'statistic'=>array(
                                    'connected_tubers'=>$channel_connected_tubers,
                                    'sponsors'=>$channel_sponsors,
                                    'videos'=>$channel_video,
                                    'photos'=>$channel_photos,
                                    'albums'=>$channel_album,
                                    'events'=>$channel_events,
                                    'brochures'=>$channel_album,
                                    'news'=>$channel_news
                                ),
			
                                'socialLinks'=>$socialLinks,
                            );
			
		}
	}
	
	echo json_encode($output);