<?php
/*! \file
 * 
 * \brief This api returns all albums for a channel
 * 
 * 
 * @param S session id
 * @param channel_id id of the channel
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> album id
 * @return       <b>title</b> album title
 * @return       <b>catalog_date</b> photo catalog date
 * @return       <b>nb_comments</b> photo number of comments
 * @return       <b>like_value</b> photo number of like 
 * @return       <b>rating</b> photo average rating
 * @return       <b>nb_rating</b> photo number of rating
 * @return       <b>nb_views</b> photo number of views
 * @return       <b>nb_shares</b> photo number of shares
 * @return       <b>description</b> photo description
 * @return       <b>bgimage</b> bid photo link
 * @return       <b>media</b> List photos and videos (array)
 * @return              <b>id</b> media id
 * @return              <b>thumb</b> thumb path
 * @return              <b>type</b> media type (video or image)
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 * 
 *  */

$expath = "../";			
	header('Content-type: application/json');
include("../heart.php");

$submit_post_get = array_merge($request->query->all(), $request->request->all());

$uid = 0;
if (isset($submit_post_get['S']))
	$uid = $submit_post_get['S'];

$user_id = mobileIsLogged($uid);


$id = 0;
if (isset($submit_post_get['channel_id']))
	$id = intval($submit_post_get['channel_id']);

if (!$user_id || !$id)
{
	echo json_encode(array());
	
	exit;
}

$logged_user_id = $user_id;

$limit = ((isset($submit_post_get['limit']) && $submit_post_get['limit'])?intval($submit_post_get['limit']):100);
$page = ((isset($submit_post_get['page']) && $submit_post_get['page'])?intval($submit_post_get['page']):0);

if(isset($submit_post_get['all']) && intval($submit_post_get['all']) == 1 )
{
	$limit = 0;
}

$output = array();

$channelInfo = channelGetInfo($id);

$options = array ( 
	'channelid' => $channelInfo['id'],
	'user_id' => $channelInfo['owner_id'],
	'limit' => $limit,
	'page' => $page,
	'order' => 'd', 
	'orderby' => 'id'
);

$channelalbumInfo = userCatalogSearch( $options );
	$channelid = $channelInfo['id'];
	$real_channel_id = null;
	
	if ($channelid != 0) {
		$real_channel_id = $channelid;
	}

if($channelalbumInfo)
{	
	foreach($channelalbumInfo as $album)
	{
		$is_owner = ($logged_user_id && intval($logged_user_id) == intval($album['user_id']));
		
		/* big_image */
		$VideoInfo = userCatalogDefaultMediaGet($album['id']);
		$photoRecord = $VideoInfo;
		
		if ($photoRecord['image_video'] == "v")
		{
			$image_src = substr(getVideoThumbnail($VideoInfo['id'], $path . $VideoInfo['fullpath'], 0), strlen($path));
			$bgimage_type = 'video';
			if(!$image_src) { $image_src = ''; }
			// $image_src = photoReturnThumbSrc($photoRecord);
		}
		else
		{
			// $image_src = photoReturnSrcMed($photoRecord);
			$image_src = $VideoInfo['fullpath'] . $VideoInfo['name'];
			$bgimage_type = 'image';
			// $image_src = photoReturnSrcLink($photoRecord, '');

		}
		
		/* Other Images */
		$imLimit = 4;
		$permitted = userPermittedForMedia($logged_user_id, $photoRecord, SOCIAL_ENTITY_MEDIA);
		
		if(!$permitted)
			$imLimit = 5;
		
		$cat_id = $album['id'];
		
		$srch_options = array (
			'limit' => $imLimit,
			'page' => 0,
			'channel_id' => $real_channel_id,
			'catalog_id' => $cat_id,
			'is_owner' => $is_owner,
			'orderby' => 'id',
			'order' => 'd',
			'type' => 'a',
			'exclude_id' => $photoRecord['id']
		);
		
		$photos1 = mediaSearch($srch_options);
		
		$srch_options1 = array (
			'channel_id' => $real_channel_id,
			'catalog_id' => $cat_id,
			'is_owner' => $is_owner,
			'type' => 'a',
			'n_results' => true
		);

		$total = mediaSearch($srch_options1);
		
		$other_img = array();
		
		if ($photos1)
			foreach($photos1 as $photo)
			{
				if ($photo['image_video'] == "v")
				{
					$thumb_src = substr(getVideoThumbnail($photo['id'], $path . $photo['fullpath'], 0), strlen($path));
					if(!$thumb_src) { $thumb_src = ''; }
					$type = 'video';
				}
				else 
				{
					$thumb_src = $photo['fullpath'] . $photo['name'];
					$type = 'image';
				}
				
				$other_img[] = array(
					'id' => $photo['id'],
					'title' => htmlEntityDecode($photo['title']),
					'path' => $thumb_src,
					'type' => $type
				);
			}
		
		$isLiked = socialLiked($logged_user_id, $album['id'], SOCIAL_ENTITY_ALBUM);
		$isLiked = $isLiked ? "$isLiked" : "0";
		$user_rating = socialRateGet($logged_user_id, $album['id'], SOCIAL_ENTITY_ALBUM);
		$catalog_date = $album['catalog_ts'];
		$pdate = returnSocialTimeFormat($catalog_date,3);
		
		$output[] = array(
			'id'=>$album['id'],
			'title'=>htmlEntityDecode($album['catalog_name']),
			'catalog_date'=>$pdate,
			'nb_comments'=>$album['nb_comments'],
			'like_value'=>$album['like_value'],
			'rating'=>$album['rating'],
			'nb_rating'=>$album['nb_ratings'],
			'nb_views'=>$album['nb_views'],
			'nb_shares'=>$album['nb_shares'],
			'description'=>htmlEntityDecode($album['description']),
			'bgimage_id'=>$photoRecord['id'] ? $photoRecord['id'] : 0,
			'bgimage'=>$image_src,
			'bgimage_private'=> $permitted ? "0" : "1",
			'bgimage_type'=>$bgimage_type,
			'media'=>$other_img,
			'total'=>$total,
			'isLiked'=>$isLiked,
			'user_rating'=>$user_rating ? $user_rating : 0
		);
	}	
}

echo json_encode($output);