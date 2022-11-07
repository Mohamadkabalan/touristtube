<?php
$path = "../";
include_once ( $path . "inc/service_bootstrap.php" );
include_once ( $path . "inc/classes/phpmailer.class.php" );
function appendToLog($msg){
    //file_put_contents("log/send_email_notification.log", date('Y-m-d H:i:s') . ' - ' . $msg . "\n", FILE_APPEND);
}
function initMailer($host){
    $mail = new PHPMailer();
    $mail->Host = $host['host'];
    if($host['is_smtp']){
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $host['security_type'];
        $mail->Port = $host['port'];
        $mail->Username = $host['username'];
        $mail->Password = $host['password'];
    }
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
    return $mail;
}
$hosts = array(
   '0' => array(
        'host' => 'smtp.socketlabs.com',
        'port' => '25',
        'username' => 'server12090',
        'password' => 'c6G4Xzf8ZLy2j',
        'is_smtp' => 'true',
        'security_type' => ''
    )

/*    '0' => array(
        'host' => '5.196.116.89',
        'port' => '587',
        'username' => 'tt',
        'password' => 'tt2014',
        'is_smtp' => 'true',
        'security_type' => 'tls'
    )
*/
/*    '0' => array(
        'host' => 'smtp.gmail.com',
        'port' => '587',
        'username' => 'elie@paravision.org',
        'password' => 'rvsDcmvc33',
        'is_smtp' => 'true',
        'security_type' => 'tls'
    )
*/


);

$host = NULL;
$start = NULL;
$limit = NULL;
if(isset($argv) && count($argv) > 0){
    foreach($argv as $param){
        $split = split('=', $param);
        $key = $split[0];
        $value = $split[1];
        switch($key){
            case 'host':
                $host = $hosts[$value];
                break;
            case 'start':
                $start = intval($value);
                break;
            case 'limit':
                $limit = intval($value);
        }
    }
    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array(); 
    $query = "SELECT * FROM cms_emails WHERE sent = 0 AND num_try<3 AND priority=0  ORDER BY create_ts asc LIMIT $start, $limit";
    
    $select = $dbConn->prepare($query);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if($res && ($ret > 0) ){
	$row = $select->fetchAll();
        $mail = initMailer($host);

        foreach($row as $email){
            $params2=array();
            $params3=array();
			$mail->ClearAddresses();
			$mail->CharSet = 'UTF-8';
            $mail->SetFrom('tt@touristtube.com',$email['title']);
            //$mail->ClearReplyTos();
			//$mail->AddReplyTo('info@touristtube.com',$email['title']); 
            $mail->Subject = $email['subject'];
            $mail->MsgHTML($email['msg']);
            $mail->AddAddress($email['to_email']);
			$mail->DKIM_domain = 'touristtube.com';
			$mail->DKIM_private = '/home/ffmpeg/keysnew/star_touristtube_com.key';
			$mail->DKIM_selector = 'phpmailer';
			$mail->DKIM_passphrase = '';
			$mail->DKIM_identifier = $mail->From;
            if($mail->Send()){
                   $query = "UPDATE cms_emails SET sent = 1 WHERE id=:Id";
                    $params2[] = array(  "key" => ":Id",
                                         "value" =>$email['id']);
                    $select1 = $dbConn->prepare($query);
                    PDO_BIND_PARAM($select1,$params2);
                    $res1    = $select1->execute();
            }
            else{
                $query = "UPDATE cms_emails SET num_try = num_try + 1 WHERE id=:Id";
                $params3[] = array(  "key" => ":Id",
                                     "value" =>$email['id']);
                $select2 = $dbConn->prepare($query);
                PDO_BIND_PARAM($select2,$params3);
                $res2    = $select2->execute();
                appendToLog($mail->ErrorInfo);
    //            echo 'Mailer error: ' . $mail->ErrorInfo;
            }
        }
        $mail->SmtpClose();
    }
}
