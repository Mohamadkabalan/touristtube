<?php
/*! \file
 * 
 * \brief This api returns information of a video
 * 
 * 
 * @param videoID video id
 * 
 * @return <b>res</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b> video id
 * @return       <b>name</b> video name
 * @return       <b>title</b> video title
 * @return       <b>path</b> video path
 * @return       <b>thumb</b> video thumb path
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */

//	$videoID = $_GET["videoID"];
	$videoID = $request->query->get('videoID','');

	include("heart.php");
	
	$myConn;
	
	$raw = getVidThumbn($videoID);
	
	//header("content-type: application/xml; charset=utf-8");  
        header('Content-type: application/json');
	
	echo $raw;
	//echo $videoID;

	function getVidThumbn($id)

	{
		
		
		global $myConn;

		$res = array();
                global $dbConn;
                $params = array();  
//		$sql = "select * from `cms_videos` where `id`='".$id."'; ";
		$sql = "select * from `cms_videos` where `id`=:Id; ";
                $params[] = array(  "key" => ":Id",
                                    "value" =>$id);
                $select = $dbConn->prepare($sql);
                PDO_BIND_PARAM($select,$params);
                $query    = $select->execute();

//		$query = db_query($sql);
//		$data = db_fetch_array($query);
                $data = $select->fetch();
                $val = getVideoThumbnail($data['id'],"../".$data['fullpath'], 1);
                foreach($val as $value)
                {
                    $thumb = str_replace("../", "", $value);
                }
                
		$res[] =array( 
                            'id'=>$data['id'],
                            'name'=>$data['name'],
                            'title'=>$data['title'],
                            'path'=>$data['fullpath'].$data['name'],
                            'thumb'=>$thumb,
                        
                        );
		echo json_encode($res);
	}