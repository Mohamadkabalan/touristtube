<?php
/**
  * A summary informing the user what the associated element does.
  *
  * A *description*, that can span multiple lines, to go _in-depth_ into the details of this element
  * and to provide some background information or textual references.
  *
  * @param1 string $var1 test
  * @param2 string $var2 test
  * @param3 string $var3 test
  * @param4 string $var4 test
  *
  */


/**
  * @source
*/

require_once("heart.php");

file_put_contents("signin.log", print_r($_POST,true) . "\n", FILE_APPEND );
$EmailField1 = $request->request->get('EmailField', '');
$PasswordField1 = $request->request->get('PasswordField', '');
//if ((isset($_POST['EmailField'])) && (strlen(trim($_POST['EmailField'])) > 0) && (isset($_POST['PasswordField'])) && (strlen(trim($_POST['PasswordField'])) > 0))
if ( $EmailField1 && (strlen(trim($EmailField1)) > 0) && $PasswordField1 && (strlen(trim($PasswordField1)) > 0))
{

	global $dbConn;
	$params = array();  
//	$lat = $_POST['lat'];
//	$long = $_POST['long'];
	$lat = $request->request->get('lat', '');
	$long = $request->request->get('long', '');

//	$EmailField = stripslashes(strip_tags($_POST['EmailField']));
//
//	$PasswordField = stripslashes(strip_tags($_POST['PasswordField']));
	$EmailField = stripslashes(strip_tags($EmailField1));

	$PasswordField = stripslashes(strip_tags($PasswordField1));

//	$SelectSignInUserSQL = "SELECT * FROM cms_users WHERE (YourEmail = '".$EmailField."' OR YourUserName = '".$EmailField."') AND YourPassword = password('".$PasswordField."') AND published = 1";
	$SelectSignInUserSQL = "SELECT * FROM cms_users WHERE (YourEmail = :EmailField OR YourUserName = :EmailField) AND YourPassword = password(:PasswordField) AND published = 1";
	$params[] = array(  "key" => ":EmailField",
                            "value" =>$EmailField);
        $params[] = array(  "key" => ":PasswordField",
                            "value" =>$PasswordField);
	$select = $dbConn->prepare($SelectSignInUserSQL);
	PDO_BIND_PARAM($select,$params);
	$SelectSignInUserResult    = $select->execute();
	//echo $SelectSignInUserSQL;
//	$SelectSignInUserResult = db_query($SelectSignInUserSQL);

	

//	$SelectSignInUserNumRows = db_num_rows($SelectSignInUserResult);
	$SelectSignInUserNumRows    = $select->rowCount();

	

	

	if($SelectSignInUserNumRows == 0){

		echo 'Incorrect User information';

	}else{

		

//		$SelectSignInUserRes = db_fetch_array($SelectSignInUserResult);
                $SelectSignInUserRes = $select->fetch();

		

		session_start();

		$uid = session_id();

		$_SESSION['id'] = $SelectSignInUserRes['id'];

		$_SESSION['ssid'] = $uid;

		$userID = $SelectSignInUserRes['id'];
		
		//$query = db_query("UPDATE cms_tubers SET latitude='".$lat."', longitude='".$long."' WHERE user_id='".$userID."'");
		userSetLocation($uid, $userID, $lat, $long);
		
		echo $_SESSION['ssid'].','.$SelectSignInUserRes['YourUserName'].',',htmlEntityDecode($SelectSignInUserRes['FullName']);

	}

	

} else {

	

	echo 'Incorrect User information';

	

}