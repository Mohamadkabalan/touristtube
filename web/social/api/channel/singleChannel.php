<?php
/*! \file
 * 
 * \brief This api returns channel information
 * 
 * 
 * @param id channel id
 * @param S session id
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
 * @return       <b>bgcolor</b> channel background color path
 * @return       <b>hidecreatedon</b> channel hide created on(don't show date)
 * @return       <b>created_on</b> channel created on(date)
 * @return       <b>hidecreatedby</b> channel hide created by(don't show creator)
 * @return       <b>created_by</b> channel created by
 * @return       <b>phone</b> channel phone
 * @return       <b>hidelocation</b> channel hide location(don't show location)
 * @return       <b>location</b> channel location
 * @return       <b>url</b> channel url
 * @return       <b>statistic</b> list with the following keys:
 * @return       		<b>connected_tubers</b> channel connected tubers
 * @return       		<b>sponsors</b> channel sponsors
 * @return       		<b>videos</b> channel videos
 * @return       		<b>photos</b> channel photos
 * @return       		<b>albums</b> channel albums
 * @return       		<b>events</b> channel events
 * @return       		<b>brochures</b> channel brochures
 * @return       		<b>news</b> channel news
 * @return       <b>socialLinks</b> channel social Links
 * @return       <b>user_is_owner</b> logged in user is owner of the channel
 * @return       <b>user_is_connected</b> logged in user is connected to the channel
 * @return       <b>category_name</b> channel category name
 * @return       <b>category_id</b> channel category id
 * @return       <b>photos</b> channel photos path
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
	
	$expath = "../";			
	//header("content-type: application/xml; charset=utf-8");  
	header('Content-type: application/json');
	include("../heart.php");
        $uricurserver = currentServerURL();
        $submit_post_get = array_merge($request->query->all(),$request->request->all());
	
//	$id = intval( $_REQUEST['id'] );
//        $user_id = mobileIsLogged($_REQUEST['S']);
	$id = intval( $submit_post_get['id'] );
        $user_id = mobileIsLogged($submit_post_get['S']);
	$channelInfo = channelGetInfo($id);
	$userChannelInfo = getUserInfo($channelInfo['owner_id']);
	$catarr = allchannelGetCategory($channelInfo['category']);
	
	$channel_id = $channelInfo['id'];
	$channel_name = $channelInfo['channel_name'];
	$channel_slogan = htmlEntityDecode($channelInfo['slogan']);
	$channel_category = htmlEntityDecode($catarr[0]['title']);
	$channel_location = channelOwnerLocation($channelInfo);
	$channel_phone = $channelInfo['phone'];
//	$channel_logo = ($channelInfo['logo']) ? photoReturnchannelLogo($channelInfo) : ReturnLink('/media/tubers/tuber.jpg');
        if($channelInfo['logo'] == ''){
            $channel_logo = 'media/tubers/tuber.jpg';
        }else{
            $channel_logo = 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['logo'];
        }
//	$channel_header = photoReturnchannelHeader($channelInfo);
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
		/*$socialLinks = "<sociallink>"."\n";
			foreach($arraychannellinks as $arrlinks){
				$socialLinks .= "<link>".htmlEntityDecode($arrlinks['link'])."</link>"."\n";
			}
		$socialLinks .= "</sociallink>";*/
                
                $socialLinks = array();
			foreach($arraychannellinks as $arrlinks){
                            $socialLinks = array(
                                            'link'=>htmlEntityDecode($arrlinks['link']),
                            );
			}
		
	}else{
		$socialLinks = array();
	}
	
	$channel_connected_tubers = channelConnectedTubersSearch(array('channelid' => $channelInfo['id'],'n_results' => true ));
        $connected_tubers = getConnectedtubers($channelInfo['id']);
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
	
//	$bg_src = ($channelInfo['bg']) ? photoReturnchannelBG($channelInfo) : '';
        $bg_src = ($channelInfo['bg']) ? 'media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['bg'] : '';
	$bg_color = ($channelInfo['bgcolor']) ? ' #'.$channelInfo['bgcolor'] : '';
	

        $output = array();
	
	/*$output .= "
	<channel>
	<id>".$channel_id."</id>
	<name>".safeXML($channel_name)."</name>
	<slogan>".safeXML($channel_slogan)."</slogan>
	<about>".safeXML($channel_desc)."</about>
	<logo>".$channel_logo."</logo>
	<header>".$channel_header."</header>
	<bgimage>".$bg_src."</bgimage>
	<bgcolor>".$bg_color."</bgcolor>
	<hidecreatedon>".$channelInfo['hidecreatedon']."</hidecreatedon>
	<created_on>".$channel_created_date."</created_on>
	<hidecreatedby>".$channelInfo['hidecreatedby']."</hidecreatedby>
	<created_by>".$channel_created_by."</created_by>
	<phone>".$channel_phone."</phone>
	<hidelocation>".$channelInfo['hidelocation']."</hidelocation>
	<location>".$channel_location."</location>
	<url>".$channel_default_link."</url>
	<statistic>
		<connected_tubers>".$channel_connected_tubers."</connected_tubers>
		<sponsors>".$channel_sponsors."</sponsors>
		<videos>".$channel_video."</videos>
		<photos>".$channel_photos."</photos>
		<albums>".$channel_album."</albums>
		<events>".$channel_events."</events>
		<brochures>".$channel_album."</brochures>
		<news>".$channel_news."</news>
	</statistic>";
	$output .= "
	".$socialLinks."
	</channel>";*/
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
        $channel_cat = allchannelGetCategory($channelInfo['category']);
        
        $channelimageInfo = mediaSearch(array(
            'channel_id' => $channelInfo['id'],
            'type' => 'i',
            'limit' => 10,
            'page' => 0,
            'search_where' => 't',
            'similarity' => 't',
            'search_strict' => 0,
            'catalog_status' => 0,
            'orderby' => 'pdate',
            'order' => 'd'
        ));
        
        $photos = array();
        foreach($channelimageInfo as $photo){
//            $thumbLink = $data['fullpath'] . $data['name'];
//            $photos[] = resizepic($thumbLink, 'm', false, '1');
            if ($photo['image_video'] == "v") {
                $thumb_src = substr(getVideoThumbnail($photo['id'], $path . $photo['fullpath'], 0), strlen($path));
                if(!$thumb_src) { $thumb_src = ''; }
                $type = 'video';
            } else {
                $thumb_src = $photo['fullpath'] . $photo['name'];
                $type = 'image';
            }
            $photos[] = array(
                'id'=>$photo['id'],
                'title'=>$photo['title'],
                'path'=>$thumb_src
//				'path'=>resizepic($thumb_src, 'm', false, '1')
            );
        }
        
        $output[]=array(
            'id'=>$channel_id,
            'name'=>str_replace('"', "'",$channel_name),
            'slogan'=>str_replace('"', "'",$channel_slogan),
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
            'statistic'=>array(
                    'connected_tubers'=>$channel_connected_tubers,
                    'sponsors'=>$channel_sponsors,
                    'videos'=>$channel_video,
                    'photos'=>$channel_photos,
                    'albums'=>$channel_album,
                    'events'=>$channel_events,
                    'brochures'=>$channel_album,
                    'news'=>$channel_news,
            ),
            'socialLinks'=>$socialLinks,
            'user_is_owner'=>$is_owner,
            'user_is_connected'=>$is_connected,
            'category_name'=>$channel_cat[0]['title'],
            'category_id'=>$channelInfo['category'],
            'photos'=>$photos,
            'shareLink'=> channelMainLink($channelInfo),
            'share_image'=> $uricurserver.'/media/channel/' . $channelInfo['id'] . '/thumb/' . $channelInfo['header']
        );
        
	echo json_encode($output);