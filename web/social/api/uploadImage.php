<?php



$expath = "";
include($expath . "heart.php");
$mypath = "../";
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$user_id = 0;
//$user_id = $_SESSION['id'];
$lang = 'en';
//if ( $user_id == 0 ){
//	die ( 'Invalid info!' );
//}
//if (isset($_REQUEST['lang'])) {
//    $lang = setLangGetText(xss_sanitize($_REQUEST['lang']), true) ? xss_sanitize($_REQUEST['lang']) : 'en';
if (isset($submit_post_get['lang'])) {
    $lang = setLangGetText(xss_sanitize($submit_post_get['lang']), true) ? $submit_post_get['lang'] : 'en';
}

$uploaddir = $mypath . 'media/tubers/';
$uploadfile = $_FILES['userfile']['name'];

if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $uploadfile)) {
    $returnArr['res'] = '-1';
    $returnArr['msg'] = _('Not Authorized');
} else {
    $returnArr['res'] = '1';
    $returnArr['msg'] = _('done');
    $returnArr['path'] = $uploadfile;
}
echo json_encode($returnArr);
ob_flush();
