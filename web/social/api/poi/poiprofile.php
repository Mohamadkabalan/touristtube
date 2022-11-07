<?php
/*! \file
 * 
 * \brief This api returns point of interest profile 
 * 
 * \todo <b><i>Change from XML to Json object and should be like/ remove like instead of upvote/downvote</i></b>
 * 
 * @param lid location id
 * 
 * @return <b>res</b> xml :
 * @return <pre> 
 * @return       <b>accent_name</b> point of interest accent name
 * @return       <b>name</b> point of interest name
 * @return       <b>latitude</b> point of interest latitude
 * @return       <b>longitude</b> point of interest longitude
 * @return       <b>country</b> point of interest country
 * @return       <b>n_review</b> point of interest number of review
 * @return       <b>rating</b> point of interest average rating
 * @return       <b>city_id</b> point of interest city id
 * @return       <b>city_accent</b> point of interest city accent
 * @return       <b>category_id</b> point of interest category id
 * @return       <b>address</b> point of interest address
 * @return       <b>cmt</b> point of interest comment
 * @return       <b>desc</b> point of interest description
 * @return       <b>website_url</b> point of interest website url
 * @return       <b>n_views</b> point of interest number of views
 * @return       <b>n_likes</b> point of interest number of likes
 * @return       <b>up_likes</b> not used
 * @return       <b>down_likes</b> not used
 * @return       <b>n_rating</b> point of interest number of rating
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
        $lid_get = $request->query->get('lid','');
//	if (isset($_GET['lid']))
//	{
//		$location_id =  $_GET['lid'];
	if ($lid_get)
	{
		$location_id =  $lid_get;
	}else
	{
		exit();	
	}
	
	header("content-type: application/xml; charset=utf-8");  
	
	
	$expath = "../";
	include($expath."heart.php");

	$datas = locationGet($location_id);
	
	//var_dump($datas);
	$res = "<poi type='profile'>";
		$res .= "<accent_name>".$datas['accent_name']."</accent_name>";
		$res .= "<name>".safeXML($datas['name'])."</name>";
		$res .= "<latitude>".$datas['latitude']."</latitude>";
		$res .= "<longitude>".$datas['longitude']."</longitude>";
		$res .= "<country>".$datas['country']."</country>";
		$res .= "<n_review>".$datas['nb_ratings']."</n_review>";
		$res .= "<rating>".$datas['rating']."</rating>";
		
		$res .= "<city_id>".$datas['city_id']."</city_id>";
		$city =  getCityById($datas['city_id']);
		$res .= "<city_accent>".$city['accent']."</city_accent>";
		
		$res .= "<category_id>".$datas['category_id']."</category_id>";
		$res .= "<address>".$datas['address']."</address>";
		$res .= "<cmt>".safeXML($datas['cmt'])."</cmt>";
		$res .= "<desc>".safeXML($datas['desc'])."</desc>";
		$res .= "<website_url>".htmlEntityDecode($datas['website_url'])."</website_url>";
		//$res .= "<link>".$datas['link']."</link>";
		
		$datas2 = locationGetStatistics($location_id);
		//var_dump($datas2);
		$res .= "<n_views>".$datas2['nb_views']."</n_views>";
		$res .= "<n_likes>".$datas2['like_value']."</n_likes>";
		
		$res .= "<up_likes>".$datas['up_votes']."</up_likes>";
		$res .= "<down_likes>".$datas['down_votes']."</down_likes>";
		//$res .= "<n_comments>".$datas2['n_comments']."</n_comments>";
		$res .= "<n_rating>".$datas2['nb_ratings']."</n_rating>";
		//$res .= "<rating>".$datas2['rating']."</rating>";
		
	$res .= "</poi>";
	
			
	echo $res;