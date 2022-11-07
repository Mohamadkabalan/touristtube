<?php
/*! \file
 * 
 * \brief This api returns all categories
 * 
 * \todo <b><i>Change from XML to Json object</i></b>
 * 
 * @param videoID video id
 * 
 * @return <b>res</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> category id
 * @return       <b>title</b> category title
 * @return       <b>image</b> category image pathe
 * @return       <b>published</b> category published or not 
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
	
			

	$expath = "../";			
	header("content-type: application/xml; charset=utf-8");  
	include("../heart.php");
	
	
	
	$res  = "";
	
	$allcat = allchannelGetCategory();
	
	if ($allcat){
		
		$res .= "<channel_categories>";
		
		foreach ($allcat as $cat){
			$res .= "<channel_category>";
				$res .= "<id>".safeXML($cat['id'])."</id>";
				$res .= "<title>".safeXML($cat['title'])."</title>";
				$res .= "<image>".safeXML("media/images/channel/mob_category/".$cat['image'])."</image>";
				$res .= "<published>".safeXML($cat['published'])."</published>";
			$res .= "</channel_category>";
		}
		$res .= "</channel_categories>";
	
	}
	 
	echo $res;