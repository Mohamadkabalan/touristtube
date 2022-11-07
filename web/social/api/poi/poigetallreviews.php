<?php
/*! \file
 * 
 * \brief This api returns point of interest all reviews
 * 
 * \todo <b><i>Change from XML to Json object and should be like/ remove like instead of upvote/downvote</i></b>
 * 
 * @param lid location id
 * @param limit number of records to return
 * @param p page number (starting from 0)
 * @param sb sort by 
 * @param s sort
 * 
 * @return <b>res</b> xml :
 * @return <pre> 
 * @return       <b>rating</b> point of interest average rating
 * @return       <b>review</b> point of interest review(comment)
 * @return       <b>review_ts</b> point of interest review date(comment date)
 * @return       <b>username</b> point of interest user name
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
//	if (isset($_GET['lid']))
        $lid_get = $request->query->get('lid','');
	if ($lid_get)
	{
//		$location_id =  $_GET['lid'];
		$location_id =  $lid_get;
	}else
	{
		exit();	
	}
	
	header("content-type: application/xml; charset=utf-8");  
	
	
	$expath = "../";
	include($expath."heart.php");
	
	$nlimit = 30;
	$page = 0;
	$sortby = "review_ts";
	$sort = "ASC";
	
        $limit_get  = $request->query->get('limit','');
        $p_get      = $request->query->get('p','');
        $sb_get     = $request->query->get('sb','');
        $s_get      = $request->query->get('s','');
//	if (isset($_GET['limit']))
//	{
//		$nlimit = $_GET['limit'];
	if ($limit_get)
	{
		$nlimit = $limit_get;
	}
//	if (isset($_GET['p']))
//	{
//		$page = $_GET['p'];
	if ($p_get)
	{
		$page = $p_get;
	}
//	if (isset($_GET['sb']))
//	{
//		$sortby = $_GET['sb'];
	if ($sb_get)
	{
		$sortby = $sb_get;
	}
//	if (isset($_GET['s']))
//	{
//		$sort = $_GET['s'];
	if ($s_get)
	{
		$sort = $s_get;
	}
	$datas = LocationGetComments($location_id, $nlimit, $page, $sortby, $sort );
	
	$res .= "<poi_reviews>";
	foreach ($datas as $data)
	{
		$res .= "<poi_review>";
			$res .= "<rating>".$data['rating']."</rating>";
			$res .= "<review>".$data['review']."</review>";
			$res .= "<review_ts>".$data['review_ts']."</review_ts>";
			$res .= "<username>".$data['username']."</username>";
		$res .= "</poi_review>";
	}
		
	$res .= "</poi_reviews>";
	
			
	echo $res;