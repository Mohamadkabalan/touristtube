<?php

    $expath = "../";
    header('Content-type: application/json');
    include($expath."heart.php");

    $mypath = "../../";
$submit_post_get = array_merge($request->query->all(),$request->request->all());
    $user_id = mobileIsLogged($submit_post_get['S']); 
    if( !$user_id ) die();
    
    $entity_type = intval($submit_post_get['entity_type']);
    $entity_id = intval($submit_post_get['entity_id']);
    $hotel_new = (isset($submit_post_get['hotel_new']))?intval($submit_post_get['hotel_new']):0;
    if( $entity_type != SOCIAL_ENTITY_HOTEL ) $hotel_new =0;
    $uploadpath = 'media/discover/';
    $uploaddir = $mypath.$uploadpath;
    $uploadfile = $_FILES['userfile']['name'];
    $server_name = $CONFIG['server_name'];
    
    switch($entity_type){ 
        case SOCIAL_ENTITY_HOTEL:
	    if($hotel_new==1){
		$uploadpath = 'media/';
		$uploaddir = $mypath.$uploadpath;
		$hotel_data = getHotelHRSInfo($entity_id);
		$title = cleanTitle($hotel_data['name']);
		$uploadpath .= 'hotels/'.$hotel_data['id'].'/';
		$uploaddir .= 'hotels/'.$hotel_data['id'].'/';
	    }else{
		$hotel_data = getHotelInfo($entity_id);             
		$title = cleanTitle($hotel_data['hotelName']);
	    }
            $entity_type_name = $title.'_'.hotel.'_'; 
            
        break; 
    
        case SOCIAL_ENTITY_RESTAURANT:
            $hotel_data = getRestaurantInfo($entity_id); 
            $title = cleanTitle($hotel_data['name']);
            $entity_type_name = $title.'_'.restaurant.'_'; 
        break;  
    
        case SOCIAL_ENTITY_LANDMARK:
            $hotel_data = getPoiInfo($entity_id);
            $title = cleanTitle($hotel_data['name']);
            $entity_type_name = $title.'_'.poi.'_'; 
        break;  
    }
    
    //print_r($_FILES['userfile']);
    $errors= array();
    $array_images_ids = array();
    foreach($_FILES['userfile']['tmp_name'] as $key => $tmp_name ){ 
        $file_name = $_FILES['userfile']['name'][$key];
        $file_size = $_FILES['userfile']['size'][$key];
        $file_tmp = $_FILES['userfile']['tmp_name'][$key];
        $file_type= $_FILES['userfile']['type'][$key];
        if($file_size > 15728640){
            $errors[]='File size must be less than 2 MB';
        }
        
        $time           =   str_replace('.','',microtime(true));
        $imageFileType = pathinfo($file_name,PATHINFO_EXTENSION);
        $filenames = $entity_type_name.$time.'.'.$imageFileType;

        $check = check_actual_filetype_with_tmp_path($file_tmp);
        
        if($check == 'wrong_type'){
            die ("Error Uploading the file ... File is not an image ...");
        }
        
        if($imageFileType == $check){ 
            $filename = $filenames;    
        }else{ 
            $file_part = explode('.',$filenames);   
            $filename = $file_part[0].'.'.$check;
        }  
        
        $target = $uploaddir.$filename;
        //$target = $uploaddir.$filename;
        if(empty($errors)==true){
            if(move_uploaded_file($file_tmp, $target)){
		if($hotel_new==1) {
		    if(photoThumbnailCreate($uploaddir. $filename, $uploaddir. 'hotels50HS7842_' . $filename , 78, 42)){
			$var['upload_status']= 'success';  
		    }
		} else {
		    if(photoThumbnailCreate($uploaddir. $filename, $uploaddir . 'thumb/' . $filename , 175, 109)){
			$var['upload_status']= 'success';  
		    } 
		}
                
//                $for = strrpos($filename, '_') + 1;
//                $extbig = substr($filename, $for);

                $minfo = mediaFileInfo($target);
                $width = mediaFileWidth($minfo);
                $height = mediaFileHeight($minfo);

                $scalex = $width/994;
                $scaley = $height/530;
                $scale = $scalex;
                if($scaley>$scalex) $scale = $scaley;

                $ww = round($width/$scale);
                $hh = round($height/$scale);
//                echo $uploaddir.$extbig;exit;
//                echo $ww. ' '. $hh;exit;
		if($hotel_new==0) {
		    if(photoThumbnailCreate($target, $uploaddir . 'large/' . $filename , $ww, $hh)){
			$var['upload_status']='success';  
		    }
		}
                if($var['upload_status']=='success'){
		    if($hotel_new==1) {
			$img_url = '/'.$uploadpath.''.$filename;
			$img_id = userCmsHotelImagesAdd($user_id,$entity_id,$filename,'');
			$array_images_ids[] = array('id'=>$img_id,'url'=>$img_url);
		    } else {
			$img_id = userDiscoverImagesAdd( $user_id , $entity_type , $entity_id , $filename );
		    }
    //                $var['status'] = 'ok';
                    $var['id'] = $img_id;
                }
            }
        }
    }
    $var['array_images_ids'] = $array_images_ids;
    if($var['upload_status']=='success' && !empty($var['id'])){
        $var['status'] = 'Ok';
    }
    else{
        $var['status'] = 'Error';
    }
    echo json_encode($var);
    
   