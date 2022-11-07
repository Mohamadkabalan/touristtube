<?php
/*! \file
 * 
 * \brief This api is for mobile forgot password
 * 
 * 
 * @param EmailForgotForm email of the user
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>status</b> if not empty event succeed
 * @return       <b>msg</b> message for any error even for the success
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
require_once("heart.php");

$result = false;
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$EmailForgotForm = $submit_post_get['email'];
//if ((isset($_POST['EmailForgotForm'])) && (strlen(trim($_POST['EmailForgotForm'])) > 0)) { 
if ($EmailForgotForm && (strlen(trim($EmailForgotForm)) > 0)) { 
//if ((isset($_POST['EmailForgotForm']))) {     

	global $dbConn;
	$params = array();  
//	$EmailForgotForm = stripslashes(strip_tags($_POST['EmailForgotForm']));
	$EmailForgotForm = stripslashes(strip_tags($EmailForgotForm));
//	$SelectForgetUserSQL = "SELECT * FROM cms_users WHERE YourEmail = '" . $EmailForgotForm . "' AND published = 1"; //OR YourUserName = '" . $EmailForgotForm . "'
	$SelectForgetUserSQL = "SELECT * FROM cms_users WHERE YourEmail = :EmailForgotForm AND published = 1"; //OR YourUserName = '" . $EmailForgotForm . "'
	$params[] = array(  "key" => ":EmailForgotForm",
                            "value" =>$EmailForgotForm);
	$select = $dbConn->prepare($SelectForgetUserSQL);
	PDO_BIND_PARAM($select,$params);
	$SelectForgetUserResult    = $select->execute();
//	$SelectForgetUserResult = db_query($SelectForgetUserSQL);

	$SelectForgetUserNumRows    = $select->rowCount();

//	$SelectForgetUserNumRows = db_num_rows($SelectForgetUserResult);


	if ($SelectForgetUserNumRows == 0) {
		$ret = array('status' => 'error','msg' => _('Invalid Email'));
		echo json_encode($ret);
		exit;
	} else {
                
                $global_link= currentServerURL().'';
//		$SelectForgetUserRes = db_fetch_array($SelectForgetUserResult);
                $SelectForgetUserRes = $select->fetch();

		$UserFullName = htmlEntityDecode($SelectForgetUserRes['FullName']);
		$UserEmail = $SelectForgetUserRes['YourEmail'];
		$UserUserName = $SelectForgetUserRes['YourUserName'];
		$UserPassword = $SelectForgetUserRes['YourPassword'];
                
                $change_pass_lnk =$global_link.'/user/password/forgot/emails/'.md5($SelectForgetUserRes['id'].''.$UserEmail);
                $tellus_lnk =$global_link.'/user/tell-us/emails/'.md5($SelectForgetUserRes['id'].''.$UserEmail);
                $tt_help_lnk =$global_link.'/help/';
		
		
		if ( !emailForgotPassword( $UserEmail , $UserFullName , $change_pass_lnk , $tellus_lnk , $tt_help_lnk )) {
			$ret = array('status' => 'error','msg' => _('An Error occured while sending your password reset information. Kindly contact our support department on support@touristube.com'));
			echo json_encode($ret);
			exit;
		} else {
			$ret = array('status' => 'ok','msg' => _("<b>We've emailed you password reset instructions. Check your email.</b> You can keep this page opened while you check your email. If you don't receive the email within a minute or two check your email's spam and junk filters."));
			echo json_encode($ret);
			exit;
		}
                
	}
} else {
	$ret = array('status' => 'error','msg' => _('Invalid Email'));
	echo json_encode($ret);
	exit;
}
