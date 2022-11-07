<?php
	
	$expath = "../";			
	header("content-type: application/json; charset=utf-8");  
	include("../heart.php");
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	$id = intval( $_REQUEST['id'] );
	$id = intval( $submit_post_get['id'] );
	
	$options = array(
		'id' => $id
	);
	
	$channelbrochuresInfo = channelbrochureSearch($options);
	$brochure = $channelbrochuresInfo[0];

	$thumbnail = ($brochure['photo']) ? photoReturnbrochureThumb($brochure) : ReturnLink('media/images/channel/brochure-cover-phot.jpg');
	$pdf = ($brochure['pdf']) ? pdfReturnbrochure($brochure) : '';
            $output[] = array(
            'brochure' => array(
			'id'=>$brochure['id'],
			'brochure_name'=>htmlEntityDecode($brochure['name']),
			'brochure_thumbnail'=>$thumbnail,
			'brochure_pdf'=>$pdf,
			'brochure_create_date'=>date('d/m/Y', strtotime($brochure['create_ts'])),
		));
		
	echo json_encode($output);