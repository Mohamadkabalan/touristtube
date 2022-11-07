<?php

require_once("heart.php");

function checkHash($options) {
    $encType = $options['encType'];
    $unHashed = '';
    foreach ($options['values'] as $aVal) {
        $unHashed .= $aVal;
    }
//    exit($unHashed);
    if ($encType == 'md5') {
        return (md5($unHashed) == $options['hash']);
    } else if ($encType == 'sha1') {
//    exit(sha1($unHashed));
        return (sha1($unHashed) == $options['hash']);
    }
}

function saveMobileRelation($userId, $mac) {
    $query = "INSERT INTO cms_mobile_relation ";
    $keys = '';
    $values = '';
    if($userId != ''){
        $keys .= ',user_id';
        $values .= ','.$userId;
    }
    if($mac != ''){
        $keys .= ',mac';
        $values .= ','.$mac;
    }
    $keys = '('.substr($keys, 1).')';
    $values = '('.substr($values, 1).')';
    
    $query .= ' '.$keys.' values '.$values;
    return true;//db_query($query);
}

function saveApiRequest($userId, $mac, $status, $timestamp, $action) {
    $query = "INSERT INTO cms_mobile_request ";
    $keys = '';
    $values = '';
    if($userId != ''){
        $keys .= ',user_id';
        $values .= ','.$userId;
    }
    if($mac != ''){
        $keys .= ',mac';
        $values .= ','.$mac;
    }
    if($status != ''){
        $keys .= ',status';
        $values .= ','.$status;
    }
    if($timestamp != ''){
        $keys .= ',create_ts';
        $values .= ','.$timestamp;
    }
    if($action != ''){
        $keys .= ',action';
        $values .= ','."'".$action."'";
    }
    $keys = '('.substr($keys, 1).')';
    $values = '('.substr($values, 1).')';
    
    $query .= ' '.$keys.' values '.$values;
//    exit($query);
    return db_query($query);
}

if (isset($_REQUEST['mac']) &&
        /* isset($_REQUEST['key']) && */
        isset($_REQUEST['timestamp']) &&
        isset($_REQUEST['hash'])) {
    $mac = $_REQUEST['mac'];
//    $key = $_REQUEST['key'];
    $timestamp = $_REQUEST['timestamp'];
    $hash = $_REQUEST['hash'];
    $options = array(
        'encType' => 'sha1',
        'hash' => $hash,
        'values' => array($timestamp, $mac/* , $key */));

    if (checkHash($options)) {
//exit(print_r($options,true));
        if (saveMobileRelation('',$mac) &&
                saveApiRequest('', $mac, 1, $timestamp, 'install')) {

            $returnArr = array();
            $status = 1;
            $returnArr['res'] = 1;
            $returnArr['msg'] = apiTranslate($lang, 'done');
            $returnArr['timestamp'] = $timestamp;
            $returnArr['hash'] = sha1($timestamp + $mac + $status);

            echo json_encode($returnArr);
        }
    }
}