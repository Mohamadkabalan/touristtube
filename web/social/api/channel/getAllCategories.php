<?php
/*! \file
 * 
 * \brief This api returns channel categories
 * 
 * 
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> category id
 * @return       <b>title</b> category title
 * @return       <b>image</b> category image path
 * @return       <b>published</b> category published
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
	
$expath = "../";			
header('Content-type: application/json');
//header('Content-type: text/html; charset=utf-8'); 
include("../heart.php");

$res  = array();

$allcat = allchannelGetCategory(0);
if ($allcat){
    foreach ($allcat as $cat){
        $count = getchannelCount($cat['id']);
        if($count == 0) continue;
        $res[] = array(
            'id'=>$cat['id'],
            'title'=> $cat['title'],// html_entity_decode($cat['title'], ENT_QUOTES | ENT_XHTML, 'UTF-8'),
            'image'=>"media/images/channel/mob_category/".$cat['image'],
            'published'=>$cat['published']
        );
    }
}	 
echo json_encode($res);