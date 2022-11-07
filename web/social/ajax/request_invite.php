<?php
	$path = "../";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/users.php" );
	
	$ret = array();
	$ret['status'] = 'error';
	$ur_array = UriCurrentPageURLForLanguage();
        $subdomain_link = $ur_array[0];

//	$fname = xss_sanitize($_POST['fname']);
//	$lname = xss_sanitize($_POST['lname']);
//	$email = xss_sanitize( filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) );
//	$uname = xss_sanitize($_POST['username']);
	$fname = $request->request->get('fname', '');
	$lname = $request->request->get('lname', '');
	$email = filter_var($request->request->get('email', ''), FILTER_SANITIZE_EMAIL) ;
	$uname = $request->request->get('username', '');
//	$ip = $_SERVER['REMOTE_ADDR'];$request->server->get('REMOTE_ADDR', '');
	$ip = $request->server->get('REMOTE_ADDR', '');
	$pswd = rand(10,99).rand(101,999).substr($email,0,1);
	
	$uinfo = userGetByUsername($uname);
	
	if($uinfo != false ){
		$ret['msg'] = 'Username already exists. please specify another';
		echo json_encode($ret);
		exit;
	}
	
	global $dbConn;
	$params = array();  
//	$query = "SELECT * FROM cms_users WHERE YourEmail = '$email'";
	$query = "SELECT * FROM cms_users WHERE YourEmail = :Email";
	$params[] = array(  "key" => ":Email",
                            "value" =>$email);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
//	$res = db_query($query);

	$ret    = $select->rowCount();
//	if( !$res || (db_num_rows($res) != 0) ){
	if( !$res || ($ret != 0) ){
		$ret['msg'] = 'Email already exists. please specify another';
		echo json_encode($ret);
		exit;
	}
	
	$allowed = str_replace('_','',$uname);
	if( !ctype_alnum($allowed) ){
		$ret['msg'] = 'Username can only be letters, numbers, and underscore.';
		echo json_encode($ret);
		exit;
	}
	if( (strlen($uname) > 12) || (strlen($uname) < 5) ){
		$ret['msg'] = 'Username must be between 5 and 12 characters.';
		echo json_encode($ret);
		exit;
	}
	
	$FullName = $fname . ' ' . $lname;
	
	$params2  = array();  
//	$query = "INSERT INTO cms_users(FullName, YourEmail, YourIP, YourUserName, YourPassword, RegisteredDate, gender, published) ";
//	$query .= "VALUES('".$FullName."', '".$email."', '".$ip."', '".$uname."', password('".$pswd."'), '".date('Y-m-d H:i:s')."','O', 0)";
	$query = "INSERT INTO cms_users(FullName, YourEmail, YourIP, YourUserName, YourPassword, RegisteredDate, gender, published) ";
	$query .= "VALUES(:FullName, :Email, :Ip, :Uname, password(:Pswd), :Date,'O', 0)";

	$params2[] = array(  "key" => ":FullName",
                             "value" =>$FullName);
	$params2[] = array(  "key" => ":Email",
                             "value" =>$email);
	$params2[] = array(  "key" => ":Ip",
                             "value" =>$ip);
	$params2[] = array(  "key" => ":Uname",
                             "value" =>$uname);
	$params2[] = array(  "key" => ":Pswd",
                             "value" =>$pswd);
	$params2[] = array(  "key" => ":Date",
                             "value" =>date('Y-m-d H:i:s'));
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params2);
	$res    = $select->execute();
//	if( !db_query($query) ){
	if( !$res ){
		$ret['msg'] = _('Couldnt process request. Please try again later.');
		echo json_encode($ret);
		exit;
	}

	
	$EmailTitle = _('TOURIST TUBE account activation');
	$EmailHeaders = '';
	$EmailHeaders  = 'MIME-Version: 1.0' . "\r\n";
	$EmailHeaders .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
	$EmailHeaders .= 'From: Tourist Tube Support <support@touristube.com>' . "\r\n";

$EmailMessage = '<html>
<head>
</head>
<body style="border: 0px; margin: 0px;">
<div style="background-color: white;">
	<div style="text-align:center;"><img src="'.$subdomain_link.'/api/profile/auto/logo.jpg" alt="logo"/></div>
	<div align="center" style="font-size:16px;"><em><i></i></em></div><br/>
	<div><em>'._('Thank  You for sharing a special moment with us.').'</em><br /><br />
	  '._('Come in and see your  photo!').'<br /><br />
	  '._('We  have created an account for you with the following credentials:').'<br />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$email.'<br />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username:&nbsp;&nbsp;&nbsp;&nbsp;'.$uname.'<br />
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password:&nbsp;&nbsp;&nbsp;&nbsp;'.$pswd.'<br /><br/>
	  <div style="text-align:center"><i><em>'._("Direct").' </em><a href="'.$subdomain_link.'"><em>'._("access").'</em></a><em> '._("to account sign in").'</em><i>
	  </div>
	</div>
	<br/>
</div>
</body>
</html>';
	//Send email to the recipient to

	mail("request@touristtube.com",$EmailTitle,$EmailMessage,$EmailHeaders);
	mail("msfeir@paravision.org",$EmailTitle,$EmailMessage,$EmailHeaders);
	
	$ret['status'] = 'ok';
	$ret['msg'] = 'Request sent.<br/>An invitation will be sent to you on the launch date.';
	echo json_encode($ret);
	exit;
