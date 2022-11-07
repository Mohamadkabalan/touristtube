<?php

$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );

$result = false;
$EmailForgotForm = $request->request->get('EmailForgotForm', '');
if ( strlen(trim($EmailForgotForm)) > 0 ) {

	$EmailForgotForm = stripslashes(strip_tags($EmailForgotForm));
	
	//the username condition was removed. not its added again.
        global $dbConn;
	$params =array();
	$SelectForgetUserSQL = "SELECT * FROM cms_users WHERE (YourEmail = :EmailForgotForm OR YourUserName = :EmailForgotForm ) AND published = 1";
        $params[] = array(  "key" => ":EmailForgotForm", "value" =>$EmailForgotForm);
        
        $select = $dbConn->prepare($SelectForgetUserSQL);
	PDO_BIND_PARAM($select,$params);
        $select->execute();	
        $SelectForgetUserNumRows = $select->rowCount();
        $SelectForgetUserResult  = $select->fetch();

	if ($SelectForgetUserNumRows == 0) {
		$ret = array('status' => 'error','msg' => 'Invalid Email');
		echo json_encode($ret);
		exit;
	} else {
		$global_link= currentServerURL().'';
		$SelectForgetUserRes = $SelectForgetUserResult;

		$UserFullName = htmlEntityDecode($SelectForgetUserRes['FullName']);
		$UserEmail = $SelectForgetUserRes['YourEmail'];
		$UserUserName = $SelectForgetUserRes['YourUserName'];
		$UserPassword = $SelectForgetUserRes['YourPassword'];
		$change_pass_lnk =$global_link.''.ReturnLink('user/password/forgot/emails/'.md5($SelectForgetUserRes['id'].''.$UserEmail));
		$tellus_lnk =$global_link.''.ReturnLink('user/tell-us/emails/'.md5($SelectForgetUserRes['id'].''.$UserEmail));
		$tt_help_lnk =$global_link.''.ReturnLink('help/');
		
		if ( !emailForgotPassword( $UserEmail , $UserFullName , $change_pass_lnk , $tellus_lnk , $tt_help_lnk )) {
			$ret = array('status' => 'error','msg' => 'An Error occured while sending your password reset information.<br/><br/>Kindly contact our support department on support@touristube.com');
			echo json_encode($ret);
			exit;
		} else {
			$ret = array('status' => 'ok','msg' => _("<b> We've emailed you password reset instructions. Check your email.</b><br/><br/>You can keep this page opened while you check your email. If you don't receive the email within a minute or two check your email's spam and junk filters."));
			echo json_encode($ret);
			exit;
		}
	}
} else {
	$ret = array('status' => 'error','msg' => 'Invalid Email');
	echo json_encode($ret);
	exit;
}