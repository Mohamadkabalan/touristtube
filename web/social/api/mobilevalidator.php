<?php
    require_once ("heart.php");
    $YourEmail = $request->request->get('Email', '');

    $YourBday = $request->request->get('Birthday', '');
    if ($YourBday == '') {
        $YourBday = '1000-01-01';
    }
    $YourUserName = $request->request->get('Username', '');
    $fName = $YourUserName;
    $FullName = $YourUserName;
    $YourPassword = $request->request->get('Password', '');

    $Gender = "O";

    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
//        $FindUserSQL = "SELECT * FROM cms_users WHERE YourUserName = '" . $formars['Username'] . "'";
    $FindUserSQL = "SELECT * FROM cms_users WHERE YourUserName = :Username";
    $params[] = array( "key" => ":Username",
                        "value" =>$YourUserName); 
    $select = $dbConn->prepare($FindUserSQL);
    PDO_BIND_PARAM($select,$params);
    $FindUserResult    = $select->execute();

    $FindUserNumRows   = $select->rowCount();

    $FindEmailSQL = "SELECT * FROM cms_users WHERE YourEmail = :Email";

    $params2[] = array( "key" => ":Email",
                        "value" =>$YourEmail); 
    $select2 = $dbConn->prepare($FindEmailSQL);
    PDO_BIND_PARAM($select2,$params2);
    $FindEmailResult    = $select2->execute();
    $FindEmailNumRows   = $select2->rowCount();

    $ret = array('status' => 'done', 'username_unique' => true, 'email_unique' => true, 'email_valid' => true);
    
    if($YourUserName!=''){

        if ($FindUserNumRows != 0) {
            $ret['status'] = 'error';
            $ret['username_unique'] = false;

        }
    }
    else{
        $YourUserName = $YourEmail;
    }

    if(check_email_address($YourEmail)){
        if (!userEmailisUnique(-1, $YourEmail)) {
            $ret['status'] = 'error';
            $ret['email_unique'] = false;

        }
    }
    else{
        $ret['status'] = 'error';
        $ret['email_valid'] = false;
    }


    if ($YourBday != '1000-01-01' && $YourBday != '') {
        $YourBdayArray = explode('/', $YourBday);
        $YourBdaySave = $YourBdayArray[2] . '-' . $YourBdayArray[0] . '-' . $YourBdayArray[1];
    } else {
        $YourBdaySave = '1000-01-01';
    }
    if($ret['status']=='done'){
        if (userRegister($FullName, $YourEmail, '', $YourBdaySave, $YourUserName, $YourPassword, $fName, '', 0, $Gender, '')) {
            $ret['status'] = 'done';
        } else {
            $ret['status'] = 'error';
        }
    } else {
        $ret['status'] = 'error';
        
    }
    echo json_encode($ret);