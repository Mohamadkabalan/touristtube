<?php


$expath = "";
include($expath . "heart.php");
$mypath = "../";
//$user_id = 0;
//$user_id = $_SESSION['id'];
$lang = 'en';
$name = '';
$email = '';
$path = '';
$returnArr = array();
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//if (isset($_REQUEST['lang'])) {
//    $lang = setLangGetText(xss_sanitize($_REQUEST['lang']), true) ? xss_sanitize($_REQUEST['lang']) : 'en';
//}
//if (isset($_REQUEST['name'])) {
//    $name = xss_sanitize($_REQUEST['name']);
//}
//if (isset($_REQUEST['email'])) {
//    $email = xss_sanitize($_REQUEST['email']);
//}
//if (isset($_REQUEST['path'])) {
//    $path = xss_sanitize($_REQUEST['path']);
//}
if (isset($submit_post_get['lang'])) {
    $lang = setLangGetText($submit_post_get['lang'], true) ? $submit_post_get['lang'] : 'en';
}
if (isset($submit_post_get['name'])) {
    $name = $submit_post_get['name'];
}
if (isset($submit_post_get['email'])) {
    $email = $submit_post_get['email'];
}
if (isset($submit_post_get['path'])) {
    $path = $submit_post_get['path'];
}
//if ( $user_id == 0 ){
//	die ( 'Invalid info!' );
//}
if ($email == '') {
    $returnArr['res'] = '-1';
    $returnArr['msg'] = _('Please provide an email');
} else {
    if (userInviteRequest(-1, $name, $email, $path)) {
        $returnArr['res'] = '1';
        $returnArr['msg'] = _('done');
    } else {
        $returnArr['res'] = '-1';
        $returnArr['msg'] = _('error');
    }
}
echo json_encode($returnArr);