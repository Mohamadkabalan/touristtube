<?php
	
			

	$expath = "../";			
	header("content-type: application/json; charset=utf-8");  
	include("../heart.php");
	
	
	
//	$start = ($_REQUEST['page'])? intval($_REQUEST['page']) : 0;
//	$limit = ($_REQUEST['limit'])? intval($_REQUEST['limit']) : 100;
//	$category = ($_REQUEST['category'])? intval($_REQUEST['category']) : 0;
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	$start = ($submit_post_get['page'])? intval($submit_post_get['page']) : 0;
	$limit = ($submit_post_get['limit'])? intval($submit_post_get['limit']) : 100;
	$category = ($submit_post_get['category'])? intval($submit_post_get['category']) : 0;
		
	$output = "";
	
	$channels = allchannelGetInfo($start,$limit,$category);
	
	if ($channels){
		
		$output .= "";
		
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
			$channel_logo = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/images/tubers/tuber.jpg');
			$channel_header = photoReturnchannelHeader($channelInfo);
                        $channel_desc = htmlEntityDecode($channelInfo['small_description']);
			$channel_default_link = $channelInfo['default_link'];
			$channel_created_by = ($channelInfo['hidecreatedby']==0) ? returnUserDisplayName($userChannelInfo) : '';
			$channel_created_date = ($channelInfo['hidecreatedon']==0) ? date('d/m/Y', strtotime($channelInfo['create_ts'])) : '';
			
			$arraychannellinks = GetChannelExternalLinks($channelInfo['id']);
			
			if($arraychannellinks){                            
					foreach($arraychannellinks as $arrlinks){
						$socialLinks[] = array('link'=>htmlEntityDecode($arrlinks['link']));
					}
			}else{
				$socialLinks = array("");
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
			
			$options = array ( 'channelid' => $channelInfo['id'] );
			$channelalbumInfoCount = userCatalogchannelSearch( $options );
			$channel_album = ($channelalbumInfoCount) ? count($channelalbumInfoCount) : 0;
			
			$options = array('channelid'=>$channelInfo['id'],'order'=>'d');
			$channelbrochuresInfoCount = channelbrochureSearch($options);
			$channel_brochure = ($channelbrochuresInfoCount) ? count($channelbrochuresInfoCount) : 0;
			
			$options = array('channelid'=>$channelInfo['id'],'order'=>'d');
			$channeleventsInfoCount = channeleventSearch($options);
			$channel_events = ($channeleventsInfoCount) ? count($channeleventsInfoCount) : 0;
			
			$options = array('channelid'=>$channelInfo['id'],'n_results' => true);	
			$channel_news = channelnewsSearch($options);
			
			$bg_src = ($channelInfo['bg']) ? photoReturnchannelBG($channelInfo) : '';
			$bg_color = ($channelInfo['bgcolor']) ? ' #'.$channelInfo['bgcolor'] : '';
			
			$output[] = array(
			'channel'=>array(
			'id'=>$channel_id,
			'name'=>safeXML($channel_name),
			'slogan'=>safeXML($channel_slogan),
			'about'=>safeXML($channel_desc),
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
			'statistic'=> array(
				'connected_tubers'=>$channel_connected_tubers,
				'sponsors'=>$channel_sponsors,
				'videos'=>$channel_video,
				'photos'=>$channel_photos,
				'albums'=>$channel_album,
				'events'=>$channel_events,
				'brochures'=>$channel_album,
				'news'=>$channel_news
			),
			'sociallink'=> $socialLinks,
			));
			
		}
	}
	
	echo json_encode($output);
	//echo $output;