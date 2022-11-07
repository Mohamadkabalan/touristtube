<?php
    $entity_type = intval($request->request->get('entity_type', 0));
    $item_id = intval($request->request->get('item_id', 0));
    $data_country = $request->request->get('data_country', '');
    $data_state = $request->request->get('data_state', '');
    $data_city = intval($request->request->get('data_city', 0));
    $user_id = userGetID();
    if($data_city>0){
        $cityInfo = worldcitiespopInfo($data_city);
        if($cityInfo){
            $data_country = $cityInfo['country_code'];
            $data_state = $cityInfo['state_code'];
        }
    }
    if( $bag_item_count=userBagItemsAdd($user_id,$entity_type,$item_id,$data_country,$data_state,$data_city) ){
        $ret_arr['status'] = 'ok';
        $ret_arr['count'] = $bag_item_count;
    }else{
        $ret_arr['status'] = 'error';
        $ret_arr['msg'] = _('Couldn\'t save the information. Please try again later.');
    }