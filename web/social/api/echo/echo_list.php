<?php

    $expath = "../";
    header('Content-type: application/json');
    include($expath."heart.php");

$submit_post_get = array_merge($request->query->all(),$request->request->all());
//    $user_id = mobileIsLogged($_REQUEST['S']);  
    $user_id = mobileIsLogged($submit_post_get['S']);  
  
    if( !$user_id ) die();
    
//    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
//    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 0;
    $page = isset($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;
    $limit = isset($submit_post_get['limit']) ? intval($submit_post_get['limit']) : 0;
    
    $reallimit=$limit;
    $realpage = $page;

    $srch_options = array('limit' => $reallimit, 'page' => $realpage, 'orderby' => 'id', 'order' => 'DESC');

    if (isset($filterSearch)) {
        if($filterSearch==1) {
            $srch_options['user_id'] = $user_id;
        }else if($filterSearch==2) {
            $friends = userGetFreindList($user_id);
            $friends_ids = array();
            foreach($friends as $freind){
                $friends_ids[] = $freind['id'];
            }
            $followers_list = userSubscribedList($user_id);                
            foreach($followers_list as $followers_users_row){
                if( !in_array( $followers_users_row['id'],$friends_ids ) ){
                    $friends_ids[] = $followers_users_row['id'];
                }
            }
            $friends_list= implode(',',$friends_ids);
            $srch_options['user_id'] = "$friends_list";
        }
    } else {
//        if (isset($_GET['filter'])) {
        $filter_get = $request->query->get('filter','');
        if ($filter_get) {
//            $filterSearch = intval($_GET['filter']);
            $filterSearch = intval($filter_get);
            if($filterSearch==1) {
                $srch_options['user_id'] = $user_id;
            }else if($filterSearch==2) {
                $friends = userGetFreindList($user_id);
                $friends_ids = array();
                foreach($friends as $freind){
                    $friends_ids[] = $freind['id'];
                }
                $followers_list = userSubscribedList($user_id);                
                foreach($followers_list as $followers_users_row){
                    if( !in_array( $followers_users_row['id'],$friends_ids ) ){
                        $friends_ids[] = $followers_users_row['id'];
                    }
                }
                $friends_list= implode(',',$friends_ids);
                $srch_options['user_id'] = "$friends_list";
            }
        }
    }
    if (isset($month_search)) {
        $srch_options['month_search'] = $month_search;
    } else {
        $month_search_get = $request->query->get('month_search','');
//        if (isset($_GET['month_search'])) {
        if ($month_search_get) {
//            $month_search = $_GET['month_search'];
            $month_search = $month_search_get;
            $srch_options['month_search'] = $month_search;
        }
    }
    if (isset($day_search)) {
        $srch_options['day_search'] = $day_search;
    } else {
//        if (isset($_GET['day_search'])) {
        $day_search_get = $request->query->get('day_search','');
        if ($day_search_get) {
//            $day_search = $_GET['day_search'];
            $day_search = $day_search_get;
            $srch_options['day_search'] = $day_search;
        }
    }
    if (isset($field_search)) {
        $srch_options['field_search'] = $field_search;
    } else {
//        if (isset($_GET['field_search'])) {
        $field_search_get =$request->query->get('field_search','');;
        if (isset($field_search_get)) {
//            $field_search = $_GET['field_search'];
            $field_search = $field_search_get;
            $srch_options['field_search'] = $field_search;
        }
    }

    $flashes = flashSearch($srch_options);
    $options_count = array('n_results' => true);
    $options_count += $srch_options;
    $flashes_count = flashSearch($options_count);
    //var_dump($flashes);
    $first = 1;
    foreach ($flashes as $flash) {
        $id = $flash['id'];
        $flash_user = getUserInfo($flash['user_id']);
        $flash_user_link = userProfileLink($flash_user);
        $flash_pic = ($flash['vpath']) ? $flash['vpath'] . 'thumb_' . $flash['pic_file'] : null;
        $flash_pic_big = ($flash['vpath']) ? $flash['vpath'] . '' . $flash['pic_file'] : null;
        $flash_time = strtotime($flash['flash_ts']);

        $flash_text = htmlEntityDecode($flash['flash_text']);
        $flash_link = htmlEntityDecode($flash['flash_link']);
        $flash_location = htmlEntityDecode($flash['flash_location']);
        $reply_to = intval($flash['reply_to']);
        $n_replies = intval($flash['n_replies']);

        $flash_time_string = formatDateAsString($flash_time);

        $flash_poster = returnUserDisplayName($flash_user);
        
        $var[]=array(
            'id'=>$id,
            'user'=>$flash_poster,
            'user_link'=>$flash_user_link,
            'pic'=>$flash_pic,
            'pic_big'=>$flash_pic_big,
            'text'=>$flash_text,
            'link'=>$flash_link,
            'location'=>$flash_location,
            'reply_to'=>$reply_to,
            'n_replies'=>$n_replies,
            'time'=>$flash_time_string,
            'lng'=>$flash['longitude'],
            'lat'=>$flash['latitude'],
        );
        
    }
    
    
    echo json_encode($var);
    
   