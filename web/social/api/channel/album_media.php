<?php
$expath = "../";			
header('Content-type: application/json');
include("../heart.php");

$submit_post_get = array_merge($request->query->all(), $request->request->all());

$uid = 0;
if (isset($submit_post_get['S']))
	$uid = $submit_post_get['S'];

$user_id = mobileIsLogged($uid);

$album_id = 0;

if (isset($submit_post_get['id']))
	$album_id = intval($submit_post_get['id']);

$output = array();

if ($user_id && $album_id)
{
	//$page =  isset($_REQUEST['page'])  ? intval($_REQUEST['page']) : 0;
	$page = isset($submit_post_get['page'])  ? intval($submit_post_get['page']) : 0;

	/* big_image */
	$VideoInfo = userCatalogDefaultMediaGet($album_id);
	$photoRecord = $VideoInfo;

	if ($photoRecord['image_video'] == "v")
	{
		$image_src = substr(getVideoThumbnail($VideoInfo['id'], $path . $VideoInfo['fullpath'], 0), strlen($path));
		$bgimage_type = 'video';
		
		if(!$image_src)
		{
			$image_src = '';
		}
		// $image_src = photoReturnThumbSrc($photoRecord);
	}
	else
	{
		//$image_src = photoReturnSrcMed($photoRecord);
		$image_src = $VideoInfo['fullpath'] . $VideoInfo['name'];
		$bgimage_type = 'image';
		// $image_src = photoReturnSrcLink($photoRecord, '');
	}

	$AlbumInfo = userCatalogGet($album_id);

	$channelid = intval($AlbumInfo['channelid']);
	$real_channel_id = null;

	if ($channelid != 0)
	{
		$real_channel_id = $channelid;
	}

	$is_owner = ($user_id && intval($user_id) == intval($AlbumInfo['user_id']));

	/* Other Images */

	$imLimit =  isset($submit_post_get['limit'])  ? intval($submit_post_get['limit']) : 5;
	$permitted = userPermittedForMedia($user_id, $VideoInfo, SOCIAL_ENTITY_MEDIA);

	if($page == 0 && $permitted)
	{
		$imLimit = $imLimit - 1;
		
		$output[] = array (
			'id' => $photoRecord['id'],
			'title' => $photoRecord['title'],
			'path' => $image_src,
			'type' => $bgimage_type
		);
	}
	else
	{
		if($permitted)
			$skip = (($imLimit - 1) * $page) + ($page - 1);
		else
		{
			$skip = $imLimit * $page; 
		}
	}

	$srch_options = array ( 
		'limit' => $imLimit,
		'page' => $page,
		'channel_id' => $real_channel_id,
		'catalog_id' => $album_id,
		'orderby' => 'id',
		'order' => 'd',
		'is_owner' => $is_owner,
		'type' => 'a',
		'exclude_id' => $photoRecord['id']
	);

	if($page > 0)
	{
		$srch_options['page'] = 0;
		$srch_options['skip'] = $skip;
	}

	$photos1 = mediaSearch($srch_options);

	if ($photos1)
		foreach($photos1 as $photo)
		{
			if ($photo['image_video'] == "v")
			{
				$thumb_src = substr(getVideoThumbnail($photo['id'], $path . $photo['fullpath'], 0), strlen($path));
				if(!$thumb_src)
				{
					$thumb_src = '';
				}
				
				$type = 'video';
			}
			else
			{
				$thumb_src = $photo['fullpath'].$photo['name'];
				$type = 'image';
			}
			
			$output[] = array (
				'id' => $photo['id'],
				'title' => $photo['title'],
				'path' => $thumb_src,
				'type' => $type
			);
		}
}

echo json_encode($output);