<?php
/*! \file
 * 
 * \brief This api returns channel brochure list
 * 
 * 
 * @param S session id
 * @param id channel id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>output</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> brochure id
 * @return       <b>brochure_name</b> brochure name
 * @return       <b>brochure_thumbnail</b> brochure thumbnail path
 * @return       <b>brochure_pdf</b> brochure pdf
 * @return       <b>brochure_create_date</b> brochure created date
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */

$expath = "../";			
header('Content-type: application/json');
include("../heart.php");

$submit_post_get = array_merge($request->query->all(), $request->request->all());

$id = 0;
if (isset($submit_post_get['id']))
	$id = intval($submit_post_get['id']);

if (!$id)
{
	echo json_encode(array());
	
	exit;
}

$uid = 0;
if (isset($submit_post_get['S']))
	$uid = $submit_post_get['S'];

$userid = mobileIsLogged($uid);

$page = intval($submit_post_get['page']);
$limit = intval($submit_post_get['limit']);
$width = intval($submit_post_get['w']);
$height = intval($submit_post_get['h']);

$channelInfo = channelFromID($id);

if (!$channelInfo)
{
	echo json_encode(array());
	
	exit;
}

$is_owner = 1;
$is_owner_visible = -1;

if($userid != intval($channelInfo['owner_id']))
{
	$is_owner = 0;
	$is_owner_visible = 1;
}

$options = array (
		'limit' => $limit,
		'page' => $page,
		'is_visible' => $is_owner_visible,
		'channelid' => $id,
		'order' => 'd'
);

$channelbrochuresInfo = channelbrochureSearch($options);

$output = array();

if($channelbrochuresInfo)
{
	//$output .= "<channel_brochures>\n";

	foreach($channelbrochuresInfo as $brochure)
	{
		if ($brochure['photo'])
		{
			$thumbnail = 'media/channel/' . $brochure['channelid'] . '/brochure/thumb/' . $brochure['photo'];
		}
		else
		{
			$thumbnail = 'media/images/channel/brochure-cover-phot.jpg';
		}
		
		$thumbnail = ReturnLink(resizepic($thumbnail, '', false, 1, $width, $height));
		$pdf = ($brochure['pdf']) ? pdfReturnbrochure($brochure) : '';
		$date = returnSocialTimeFormat($brochure['create_ts'], 1);
		
		$output[] = array (
				'id' => $brochure['id'],
				'brochure_name' => $brochure['name'],
				'brochure_thumbnail' => $thumbnail,
				'brochure_pdf' => $pdf,
				'brochure_create_date' => $date,
			);
	}
	
	//$output .= "</channel_brochures>";
}

echo json_encode($output);