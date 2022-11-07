<?php
/*! \file
 * 
 * \brief This api returns news information of channel
 * 
 * 
 * @param id channel id
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * 
 * @return <b>output</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> news id
 * @return       <b>news_description</b> news description
 * @return       <b>news_create_date</b> news create date
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
	
$expath = "../";			
//header("content-type: application/xml; charset=utf-8");
header('Content-type: application/json');
include("../heart.php");

global $CONFIG;
$xmlServerPath = "https://www.touristtube.com/";
$xmlMediaPath = "media/360/ttd/";

$submit_post_get = array_merge($request->query->all(),$request->request->all());

$user_id = 0;
if (isset($submit_post_get['S']))
	$user_id = mobileIsLogged($submit_post_get['S']);

if(isset($submit_post_get['limit']))
	$limit = intval($submit_post_get['limit']);
else
	$limit = 10;

if(isset($submit_post_get['page']))
	$page = intval( $submit_post_get['page'] );
else
	$page = 0;

$id = intval($submit_post_get['id']);

$options = array(
	'limit' => $limit , 
	'page' => $page , 
	'parent_id' => $id,
	'orderby' => 'order_display',
	'order' => 'd',
	'lang' => $api_language
);

$thingstodoDetails = getThingstodoDetailList($options);
$output = array();

if($thingstodoDetails)
{
	foreach($thingstodoDetails as $thingstodo)
	{
		if($thingstodo['image'])
		{
			$image = 'media/thingstodo/'.$thingstodo['image'];
		}
		else
		{
			$image = '';
		}

		if($user_id)
		{
			$is_bag = checkUserBagItem($user_id, $entity_type, $item_data['entity_id']);
			
			if($is_bag)
			{
				$is_bag = "1";
			}
		}
		else
		{
			$is_bag = "0";
		}
                $list360 = array();

                if( $thingstodo["division_category_id"] !='' )
                {
                    $thingstodoDivisions = getThingstodoDivisions( $thingstodo['id'] );

                    foreach($thingstodoDivisions as $item) {
                        if( $item['parent_id']=='' ) continue;
                        
                        $thumburl = strtolower($thingstodo['country']).'/'.$thingstodo['id'].'/'.$item['division_category_id'].'/'.$item['parent_id'].'/'.$item['td_id'].'/';
                        $thumbpath = $thumburl."thumb.jpg";
                        $sphere360 = $thumburl."360_sphere.jpg";
                        if(file_exists($CONFIG['server']['root'].$xmlMediaPath.$sphere360)){
                            $list360[] = array("image"=>"/".$xmlMediaPath.$thumbpath,"image360"=>"/".$xmlMediaPath.$sphere360);
                        }
                    }
                }
//                $myxmlfilecontent = file_get_contents($CONFIG['server']['root'].$xmlMediaPath.$thingstodo["xml_360"]);
//                $xmlLink = iconv(iconv_get_encoding($myxmlfilecontent), "UTF-8//TRANSLIT", $myxmlfilecontent);
//                $xml = simplexml_load_string($xmlLink);
//                foreach($xml->scene as $scene) {
//                    $thumburl = $scene['thumburl'];
//                    $sphere360 = str_replace('thumb.jpg', "360_sphere.jpg", $thumburl);
//                    if(file_exists($CONFIG['server']['root'].$xmlMediaPath.$sphere360)){
//                        $list360[] = array("image"=>"/".$xmlMediaPath.$thumburl,"image360"=>"/".$xmlMediaPath.$sphere360);
//                    }
//                }
		$output[] = array(
			'id' => $thingstodo['id'],
			'title' => (isset($thingstodo['ml_title']) && $thingstodo['ml_title']?$thingstodo['ml_title']:$thingstodo['title']),
			'image' => $image,
			'description' => (isset($thingstodo['ml_description']) && $thingstodo['ml_description']?htmlEntityDecode($thingstodo['ml_description']):htmlEntityDecode($thingstodo['description'])),
			'entity_id' => $thingstodo['entity_id'],
			'entity_type' => $thingstodo['entity_type'],
			'city_id' => $thingstodo['city_id'],
			'added_to_bag'=> $is_bag,
			'list360'=> $list360
		);
	}
}

echo json_encode($output);