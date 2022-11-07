<?php

    $expath = "../";
    header('Content-type: application/json');
    include($expath."heart.php");

//    $user_id = mobileIsLogged($_REQUEST['S']); 
$submit_post_get = array_merge($request->query->all(),$request->request->all());
    $user_id = mobileIsLogged($submit_post_get['S']);  
  
    if( !$user_id ) die();
    $mypath = "../../";
    $vpath = 'media/flash/'.date('Y').'/'.date('W').'/';
    $uploaddir = $mypath.$vpath;
    
    $filename = $_FILES['filename']['name']; 
    
//    $flash_text = (isset($_POST['FlashText'])) ? xss_sanitize($_POST['FlashText']) : '';
//    $flash_link = (isset($_POST['flash_link'])) ? xss_sanitize($_POST['flash_link']) : '';
//    $flash_location = (isset($_POST['flash_location'])) ? xss_sanitize($_POST['flash_location']) : '';
    $flash_text = $request->request->get('FlashText', '');
    $flash_link = $request->request->get('flash_link', '');
    $flash_location = $request->request->get('flash_location', '');
    //$vpath = xss_sanitize($_POST['vpath']);

//    $FlashLong = ($_POST['FlashLong'] != '') ? doubleval($_POST['FlashLong']) : null;
//    $FlashLat = ($_POST['FlashLat'] != '') ? doubleval($_POST['FlashLat']) : null;
//    $replyTo = ($_POST['ReplyTo'] != '') ? intval($_POST['ReplyTo']) : null;
//    $rtype = intval($_POST['rtype']);
//    $location_name = xss_sanitize($_POST['label']);
    $FlashLong = doubleval($request->request->get('FlashLong', null));
    $FlashLat = doubleval($request->request->get('FlashLat', null));
    $replyTo = intval($request->request->get('ReplyTo', null));
    $rtype = intval($request->request->get('rtype', ''));
    $location_name = $request->request->get('label', '');
  
    $path_parts = pathinfo($filename);
    $extension = $path_parts['extension'];
    $filename_new= 'echoes'.'_'.time().'.' .$extension;
    $whereto=$uploaddir. '' . $filename_new;
    
    $uploadPathInfo = pathinfo($whereto);
    if(!file_exists($uploadPathInfo['dirname'])) mkdir($uploadPathInfo['dirname'], 0777, true);

    if(move_uploaded_file( $_FILES['filename']['tmp_name'], $whereto ) ){    
        
        if(photoThumbnailCreate($whereto, $uploaddir . 'thumb_' . $filename_new , 136, 76)){
            $ret['upload_status']= 'sucess';  
        }       

        if(photoThumbnailCreate($whereto, $uploaddir . 'small_' . $filename_new , 86, 48)){
            $ret['upload_status']='sucess';  
        }

    }
    
    $ret = array();
//    if (strstr($vpath, '..') != null){
//        $ret['status'] = 'error1';
//        $ret['type'] = 'save';
//        echo json_encode($ret);
//        die('');
//    }
    if (!file_exists($CONFIG['server']['root'] . $vpath . $filename_new)){
        $ret['status'] = 'error';
        $ret['type'] = 'save';
        echo json_encode($ret);
        die('');
    }
//
//    if ($rtype == 0) {
//        //city selected
//        $city = db_sanitize($_POST['city']);
//        $cc = db_sanitize($_POST['cc']);
//        $city_id = getCityId($city, '', $cc);
//        $location_id = null;
//    } else if ($rtype == 1) {
//        //location selected
//        $location_id = intval($_POST['loc_id']);
//        $location = locationGet($location_id);
//        if ($location == false){
//            $ret['status'] = 'error3';
//            $ret['type'] = 'save';
//            echo json_encode($ret);
//            die('');
//        }
//        $city_id = $location['city_id'];
//    }else {
//        $location_id = null;
//        $city_id = null;
//    }


    if ( $insertedFlashId = flashAdd($user_id, $flash_text,$flash_link,$flash_location, $filename_new, $vpath, $FlashLong, $FlashLat, $replyTo, $city_id, $location_id, $location_name)) {

        if(save_hashtags($flash_text,$insertedFlashId)){
            $ret['status'] = 'ok';
        }else{
            $ret['status'] = 'error';
            $ret['type'] = 'save tags';
        }
    } else {
        $ret['status'] = 'error';
        $ret['type'] = 'save';
        $ret['typesa'] = ' '.$flash_text;
    }

   echo json_encode($ret);
