<?php
/*! \file
 * 
 * \brief This api returns uploaded video information
 * 
 * 
 *\todo <b><i>Change from comma seprated string to Json object</i></b>
 * 
 * @param S session id
 * @param uploadfile contains all info of the file
 * 
 * @return comma seprated string:
 * @return <pre> 
 * @return       <b>name</b> video name
 * @return       <b>relativepath</b> video path
 * @return       <b>size</b> video size
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>

 * 
 *  */
// fixing session issue

 


$myPath    = "../../";
$expath = '../';
include_once("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$userId = mobileIsLogged($_REQUEST['S']);
$userId = mobileIsLogged($submit_post_get['S']);
if( !$userId ) {
    echo 'notSignedIn';
    exit();
}
//$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
//require_once ( $path . "inc/common.php" );
//require_once ( $path . "inc/bootstrap.php" ); 
//include_once("../../heart.php");
//echo 'test';
//exit();

//session_write_close(); 

//echo 'test';
//exit();
if ( !($uploadPath = getUploadDirTree ( $myPath . $CONFIG [ 'video' ] [ 'uploadPath' ] ) ) )
{
	die ( 'Error! the file cannot be uploaded! Please try again later' ); 
}
//print_r($_FILES);
if ( !isset ( $_FILES ['uploadfile'] ) || $_FILES ['uploadfile'] == '' )
{
	die ("Error Uploading the file ... Please try again later ...");
}

$videoFile = MOB_uploadVideo ( $_FILES, $uploadPath );
echo $videoFile;
//print_r($videoFile);die;

//$videoFile ['relativepath'] = str_replace ( $myPath . $CONFIG [ 'video' ] [ 'uploadPath' ], '', $uploadPath ); /*comment by mukesh*/

//echo  $videoFile ['name'].",". $videoFile ['relativepath'] . "," .  $videoFile ['size'];
//echo $videoFile;


/*
echo "<input type=\"hidden\" name=\"vName\" value=\"" . $videoFile ['name'] . "\" />";
echo "<input type=\"hidden\" name=\"vPath\" value=\"" . $videoFile ['relativepath'] . "\" />";
echo "<input type=\"hidden\" name=\"vSize\" value=\"" . $videoFile ['size'] . "\" />";
*/
/*
$target_path  = "mob-video/";
$target_path = $target_path . basename( $_FILES['uploadfile']['name']);
if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_path)) {
 echo "The file ".  basename( $_FILES['uploadfile']['name']).
 " has been uploaded";
} else{
 echo "There was an error uploading the file, please try again!";
}
?>*/