<?php 
$expath = "../../";
require_once ($expath."heart.php");			  
require_once ("../".$expath."formvalidator.php");			  

//$Firstname = db_sanitize($_POST['fname']);
//$Lasttname = db_sanitize($_POST['lname']);
//
//$YourEmail = db_sanitize($_POST['Email']);
//$YourEmail = str_replace(' ','',$YourEmail);
//
//$YourCountry = db_sanitize($_POST['Country']);
//
//$YourIP = db_sanitize($_SERVER['REMOTE_ADDR']);
//
//$YourBdaySave = db_sanitize($_POST['Birthdate']);
$Firstname = $request->request->get('fname', '');
$Lasttname = $request->request->get('lname', '');

$YourEmail = $request->request->get('Email', '');
$YourEmail = str_replace(' ','',$YourEmail);

$YourCountry = $request->request->get('Country', '');

//$YourIP = db_sanitize($_SERVER['REMOTE_ADDR']);
$YourIP = $request->server->get('REMOTE_ADDR', '');

$YourBdaySave = $request->request->get('Birthdate', '');
if($YourBdaySave == '' || $YourBdaySave == '0000-00-00'){
    $YourBdaySave = NULL;
}

//$YourUserName = db_sanitize($_POST['Username']);
//
//$gender = strtoupper($_POST['Gender']);
$YourUserName = $request->request->get('Username', '');

$gender = strtoupper($request->request->get('Gender', ''));

//autogeneratepassword
$YourPassword = "";//$_POST['ConfirmPassword'];

$Password = "";//$_POST['Password'];


if ($YourUserName == "")
{
	$YourUserName = substr($YourEmail,0,strpos($YourEmail,"@"));	
	$YourUserName .= rand(11,99);
}else{
	$allowed = str_replace('_','',$YourUserName);
	if( !ctype_alnum($allowed) ){
		die("Username can only be letters, numbers, and underscore");
	}
	if( (strlen($YourUserName) > 12) || (strlen($YourUserName) < 5) ){
		die("Username must be between 5 and 12 characters.");
	}
}

if ($YourPassword == "")
{
	$YourPassword = rand(10,99).rand(101,999).substr($YourEmail,0,1);
}
    global $dbConn;
    $params  = array();  
    $params2 = array();
    $params3 = array();
    $params4 = array();
    $params5 = array();
//$FindUserSQL = "SELECT * FROM cms_users WHERE YourUserName = '".db_sanitize($YourUserName)."'";
$FindUserSQL = "SELECT * FROM cms_users WHERE YourUserName = :YourUserName";
$params[] = array(  "key" => ":YourUserName",
                    "value" =>$YourUserName);
$select = $dbConn->prepare($FindUserSQL);
PDO_BIND_PARAM($select,$params);
$FindUserResult    = $select->execute() or die('');

//$FindUserResult = db_query($FindUserSQL) or die('');

//$FindUserNumRows = db_num_rows($FindUserResult);
$FindUserNumRows    = $select->rowCount();



//$FindEmailSQL = "SELECT * FROM cms_users WHERE YourEmail = '".db_sanitize($YourEmail)."'";
$FindEmailSQL = "SELECT * FROM cms_users WHERE YourEmail = :YourEmail";
$params2[] = array(  "key" => ":YourEmail",
                     "value" =>$YourEmail);
$select = $dbConn->prepare($FindEmailSQL);
PDO_BIND_PARAM($select,$params2);
$FindEmailResult    = $select->execute() or die('');

//$FindEmailResult = db_query($FindEmailSQL) or die('');

//$FindEmailNumRows = db_num_rows($FindEmailResult);
$FindEmailNumRows    = $select->rowCount();

if($FindUserNumRows != 0){

echo 'Your Username already exist';
exit();

}

if($FindEmailNumRows != 0){


echo 'Your Email already exist';
exit();
}


//	$GetCountryCode = "SELECT * FROM cms_countries WHERE name = '".$YourCountry."'";
	$GetCountryCode = "SELECT * FROM cms_countries WHERE name = :YourCountry";
        $params3[] = array(  "key" => ":YourCountry",
                             "value" =>$YourCountry);
        $select = $dbConn->prepare($GetCountryCode);
        PDO_BIND_PARAM($select,$params3);
        $CountryCodeResult    = $select->execute();

//	$CountryCodeResult = db_query($GetCountryCode);

//	$SelectCountryRes = db_fetch_array($CountryCodeResult);
	$SelectCountryRes = $select->fetch();

	$cCode = $SelectCountryRes['code'];


	$InsertUserSQL = "INSERT INTO cms_users(FullName, YourEmail, YourCountry, YourIP, YourBday, YourUserName, YourPassword, RegisteredDate, gender, published) ";
//
//	$InsertUserSQL .= "VALUES('".$FullName."', '".$YourEmail."', '".$cCode."', '".$YourIP."', '".$YourBdaySave."', '".$YourUserName."', password('".$YourPassword."'), '".date('Y-m-d H:i:s')."','{$gender}', 0)";
	$InsertUserSQL .= "VALUES(:FullName, :YourEmail, :Code, :YourIP, :YourBdaySave, :YourUserName, password(:YourPassword), :Date, :Gender, 0)";

	
        $params4[] = array(  "key" => ":FullName",
                             "value" =>$FullName);
        $params4[] = array(  "key" => ":YourEmail",
                             "value" =>$YourEmail);
        $params4[] = array(  "key" => ":Code",
                             "value" =>$cCode);
        $params4[] = array(  "key" => ":YourIP",
                             "value" =>$YourIP);
        $params4[] = array(  "key" => ":YourBdaySave",
                             "value" =>$YourBdaySave);
        $params4[] = array(  "key" => ":YourUserName",
                             "value" =>$YourUserName);
        $params4[] = array(  "key" => ":YourPassword",
                             "value" =>$YourPassword);
        $params4[] = array(  "key" => ":Date",
                             "value" =>date('Y-m-d H:i:s'));
        $params4[] = array(  "key" => ":Gender",
                             "value" =>$gender);
        $select = $dbConn->prepare($InsertUserSQL);
        PDO_BIND_PARAM($select,$params4);
        $InsertUserResult    = $select->execute();

	//echo $InsertUserSQL;

//	$InsertUserResult = db_query($InsertUserSQL);// or die('');

	if( !$InsertUserResult ){
		die('not ins');
	}









	$SelectUserSQL = "SELECT * FROM cms_users where YourEmail = :YourEmail";
	$params[] = array(  "key" => ":YourEmail",
                            "value" =>$YourEmail);
	$select = $dbConn->prepare($SelectUserSQL);
	PDO_BIND_PARAM($select,$params);
	$SelectUserResult    = $select->execute();

//	$SelectUserResult = db_query($SelectUserSQL) or die('not sel after ins');

//	$SelectUserRes = db_fetch_array($SelectUserResult);
	$SelectUserRes = $select->fetch();

	$ITEmail = $SelectUserRes['YourEmail'];

	$Encrypted = md5($ITEmail);

	

	$EmailTitle = 'TOURIST TUBE account activation';

	

	$EmailHeaders = '';

	$EmailHeaders  = 'MIME-Version: 1.0' . "\r\n";

	$EmailHeaders .= 'Content-Type: text/html; charset=utf-8' . "\r\n";

	

	// Additional headers

	

	$Link = '';

	

	$EmailHeaders .= 'From: Tourist Tube Support <support@touristube.com>' . "\r\n";

	
/*
	$EmailMessage = 'Dear Sir<br><br>';

	$EmailMessage .= 'Kindly click on the link below or copy and paste the link in your browser in order to activate your account;<br>';

	$EmailMessage .= '<a href="'.$Link.'">Click Here</a><br>';

	$EmailMessage .= $Link.'<br><br>';

	$EmailMessage .= 'Thank You<br>';

	$EmailMessage .= 'Tourist Tube,<br>Support Team';

	
*/
$EmailMessage = '<html>
<head>
</head>
<body style="border: 0px; margin: 0px;">
<div style="background-color: white;">
	<div style="text-align:center;"><img src="https://www.touristtube.com/api/profile/auto/logo.jpg"/></div>
	<div align="center" style="font-size:16px;"><em><i></i></em></div><br/>
	<div><em>Thank  You for sharing a special moment with us.</em><br /><br />
	  Come in and see your  photo!<br /><br />
	  We  have created an account for you with the following credentials:<br />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$YourEmail.'<br />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username:&nbsp;&nbsp;&nbsp;&nbsp;'.$YourUserName.'<br />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password:&nbsp;&nbsp;&nbsp;&nbsp;'.$YourPassword.'<br /><br/>
	  <div style="text-align:center"><i><em>Direct </em><a href="https://www.touristtube.com"><em>access</em></a><em> to account sign in</em><i>
	  </div>
	</div>
	<br/>
</div>
</body>
</html>';
	//Send email to the recipient to

	//echo $EmailMessage;
 
	//mail($ITEmail,$EmailTitle,$EmailMessage,$EmailHeaders);
	mail("request@touristtube.com",$EmailTitle,$EmailMessage,$EmailHeaders);
	mail("msfeir@paravision.org",$EmailTitle,$EmailMessage,$EmailHeaders);
	
	echo $SelectUserRes['id'];