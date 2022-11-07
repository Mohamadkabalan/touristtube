<?php
/**
 * functionality that deals with bag
 * @package videos
 */
function getBagHotels ( $array ){
    global $dbConn;
    $params = array();
    $str = implode(',', $array);
    $query = "SELECT * from discover_hotels, discover_hotels_images where discover_hotels.id = discover_hotels_images.id_hotel AND find_in_set(cast(discover_hotels.id as char), :Str) GROUP BY discover_hotels.id ORDER BY discover_hotels_images.default_pic DESC ";
	$params[] = array( "key" => ":Str", "value" =>$str);

    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $row = $select->fetchAll(PDO::FETCH_ASSOC);
    return $row;
}
/**
 * delete the bag item for a given user and item id
 * @param integer $id item id
 * @param integer $user_id user id
 * @return boolean true|false depending on the success of the operation
 */
function userBagItemDelete( $user_id , $id ){
    global $dbConn;
    $params = array(); 
    $params1 = array(); 
    $bagItemInfo = bagItemInfo($id);
    $query = "DELETE FROM cms_bagitem where id=:Id AND user_id=:User_id";
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();
    
    $deletequery = "DELETE FROM cms_bag WHERE NOT EXISTS(SELECT i.bag_id FROM cms_bagitem as i WHERE i.bag_id=cms_bag.id) AND cms_bag.id=:Id";
    $params1[] = array(  "key" => ":Id", "value" =>$bagItemInfo['bag_id']);
    $deletebag = $dbConn->prepare($deletequery);
    PDO_BIND_PARAM($deletebag,$params1);
    $deletebag->execute();
    return $res;
}
/**
 * delete the bag for a given id
 * @param integer $id item id
 * @return boolean true|false depending on the success of the operation
 */
function userBagIDelete( $id ){
    global $dbConn;
    $params = array(); 
    $query = "DELETE FROM cms_bag WHERE id=:Id";
    $params[] = array(  "key" => ":Id", "value" =>$id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();
    
    $deletequery = "DELETE FROM cms_bagitem where bag_id=:Id";
    $deletebag = $dbConn->prepare($deletequery);
    PDO_BIND_PARAM($deletebag,$params);
    $deletebag->execute();
    
    newsfeedDeleteAll($id, SOCIAL_ENTITY_BAG);

    //delete comments
    socialCommentsDelete($id, SOCIAL_ENTITY_BAG);

    //delete likes
    socialLikesDelete($id, SOCIAL_ENTITY_BAG);

    //delete shares
    socialSharesDelete($id, SOCIAL_ENTITY_BAG);
    return $res;
}
/**
 * gets the bag items for a given user
 * @param integer $user_id the user id, 
 * @param integer $bag_id the bag id, 
 * @param string $country_code the country code
 * @param string $state_code the state code
 * @param integer $city_id the $city id, 
 * @return array | false the cms_bagitem record or null if not found
 */
function userBagItemsList($user_id,$bag_id=0,$entity_type=0){
    global $dbConn;
    $params = array();  
    $bag_id = intval($bag_id);
    if($bag_id==0){        
       return false;
    }
    $query = "SELECT * FROM cms_bagitem WHERE user_id=:User_id AND bag_id=:Bag_id";
    if($entity_type>0){
        $query .=" AND type=:Entity_type";
        $params[] = array(  "key" => ":Entity_type", "value" =>$entity_type);
    }
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Bag_id", "value" =>$bag_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if($res && $ret!=0){
        $media = $select->fetchAll();
        return $media;
    }else{
        return false;
    }
}
/**
 * gets the bag for a given user
 * @param integer $user_id the user id, 
 * @return array | false the cms_bag record or null if not found
 */
function userBagList($user_id){

//  <start>
    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_bag WHERE user_id='$user_id'";
    $query = "SELECT * FROM cms_bag WHERE user_id=:User_id ORDER BY NAME ASC";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    if($res && $ret!=0){
        $media = $select->fetchAll();
        return $media;
    }else{
        return false;
    }


}
/**
 * gets the bag info for a given user
 * @param integer $user_id the user id, 
 * @param integer $id the bag id, 
 * @return array | false the cms_bag record or null if not found
 */
function userBagInfo($user_id,$id){

//  <start>
	global $dbConn;
	$params = array();  
//    $query = "SELECT * FROM cms_bag WHERE user_id='$user_id' AND id='$id' LIMIT 1";
    $query = "SELECT * FROM cms_bag WHERE user_id=:User_id AND id=:Id LIMIT 1";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array(  "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    $ret = db_query($query);
        
    if($res && $ret!=0){ 
	$row = $select->fetch();       
//        return db_fetch_array($ret);
        return $row;
    }else{
        return false;
    }


}
/**
 * gets the bag info for a given bag id
 * @param integer $id the bag id, 
 * @return array | false the cms_bag record or null if not found
 */
function bagItemInfo($id){
    global $dbConn;
    $params = array(); 
    $query = "SELECT * FROM `cms_bagitem` WHERE id=:Id LIMIT 1";
    $params[] = array(  "key" => ":Id", "value" =>$id);    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();  
    if($res && $ret!=0){    
	$row = $select->fetch();
        return $row;
    }else{
        return false;
    }
}
function bagInfo($id){

//  <start>
	global $dbConn;
       $params = array(); 
        $bagInfo    = tt_global_get('bagInfo');
        if(isset($bagInfo[$id]) && $bagInfo[$id]!='')
            return $bagInfo[$id];
        $query = "SELECT * FROM `cms_bag` WHERE id=:Id LIMIT 1"; //Added by Devendra
	$params[] = array(  "key" => ":Id",
                            "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    
    if($res && $ret!=0){        
//        return db_fetch_array($ret);
	$row = $select->fetch();
                $bagInfo[$id]    =   $row;
                return $row;
        }else{
        $bagInfo[$id]    =   false;
        return false;
    }


}
function updateBagName($id,$name){
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_bag SET name = :Name WHERE id=:Id";
    $params[] = array( "key" => ":Name", "value" =>$name);
    $params[] = array( "key" => ":Id", "value" =>$id);
    $delete = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete,$params);
    $res    = $delete->execute();
    return $res;
}
function updateItemBagId($id,$bag_id,$type,$item_id){
    global $dbConn;
    $params = array();
    $imgpath ='';
    $imgname ='';
    $bag_info = bagInfo($bag_id);
    if( $bag_info['imgpath']=='' || $bag_info['imgname']=='' ){
        if($type==SOCIAL_ENTITY_LANDMARK){
            $items_img = getPOIDefaultPic($item_id);
            if ( $items_img ) {
                $imgpath = 'media/discover/';
                $imgname = $items_img['img'];
            }
        }else if($type==SOCIAL_ENTITY_RESTAURANT){            
//            $items_img = getRestaurantDefaultPic($item_id);
//            if ( $items_img ) {
//                $imgpath = 'media/discover/';
//                $imgname = $items_img['img'];
//            }
        }else if($type==SOCIAL_ENTITY_HOTEL){
            $items_img = getHotelDefaultPic($item_id);
            if ( $items_img ) {
                $imgpath = 'media/discover/';
                $imgname = $items_img['img'];
            }
        }else if($type==SOCIAL_ENTITY_AIRPORT){
            $items_img = getAirportDefaultPic($item_id);
            if ( $items_img ) {
                $imgpath = 'media/discover/';
                $imgname = $items_img['img'];
            }
        }else if($type==SOCIAL_ENTITY_EVENTS){
            $items_img = channelEventInfo($item_id);
            if ($items_img['photo'] != '') {
                $imgpath = 'media/channel/' . $items_img['channelid'] . '/event/thumb/';
                $imgname = $items_img['photo'];
            }
        }
    }
    if( $imgname!='' && $imgpath!=''){
        $query = "UPDATE cms_bag SET imgname = :Imgname, imgpath = :Imgpath WHERE id=:Id";
        $params[] = array( "key" => ":Imgname", "value" =>$imgname);
        $params[] = array( "key" => ":Imgpath", "value" =>$imgpath);
        $params[] = array( "key" => ":Id", "value" =>$bag_id);
        $update = $dbConn->prepare($query);
        PDO_BIND_PARAM($update,$params);
        $update->execute();
    }
    $params = array();
    $query = "SELECT id FROM cms_bagitem  WHERE bag_id = :Bag_id and item_id=:Item_id and type=:Entity_type";
    $params[] = array( "key" => ":Bag_id", "value" =>$bag_id);
    $params[] = array( "key" => ":Item_id", "value" =>$item_id);
    $params[] = array( "key" => ":Entity_type", "value" =>$type);    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if($res && $ret>0){
        $params = array();
        $query = "DELETE FROM cms_bagitem where id = :Id";
        $params[] = array( "key" => ":Id", "value" =>$id);
        $delete = $dbConn->prepare($query);
        PDO_BIND_PARAM($delete,$params);
        $res    = $delete->execute();
        return true;
    }else{
        $params = array();
        $query = "UPDATE cms_bagitem SET bag_id = :Bag_id WHERE id=:Id";
        $params[] = array( "key" => ":Bag_id", "value" =>$bag_id);
        $params[] = array( "key" => ":Id", "value" =>$id);
        $delete = $dbConn->prepare($query);
        PDO_BIND_PARAM($delete,$params);
        $res    = $delete->execute();
        return $res;
    }    
}
/**
 * gets the bag details by name for a given user
 * @param integer $user_id the user id,
 * @param intiger $bag_id the bag id, 
 * @return array | false the cms_bag record or null if not found
 */
function userBagListByName($user_id,$bag_id){

//  <start>
	global $dbConn;
	$params = array(); 
    if($bag_id == '0'){
        return userBagList($user_id);
    }else{
//        $query = "SELECT * FROM cms_bag WHERE id = '$bag_id' and user_id='$user_id'";
        $query = "SELECT * FROM cms_bag WHERE id = :Bag_id and user_id=:User_id";
	$params[] = array(  "key" => ":Bag_id",
                            "value" =>$bag_id);
	$params[] = array(  "key" => ":User_id",
                            "value" =>$user_id);
//        $ret = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if($ret && db_num_rows($ret)!=0){
        if($res && $ret!=0){
            $media = $select->fetchAll();
            return $media;
        }else{
            return false;
        }
    }


}
/**
 * gets count items bag for a given user
 * @param integer $user_id the user id, 
 * @param string $country_code the country code
 * @param string $state_code the state code
 * @param integer $city_id the $city id, 
 * @return count items bag
 */
function userBagItemsCount($user_id,$bag_id){

//  <start>
    global $dbConn;
    $params = array();  
    $query = "SELECT COUNT(id) FROM cms_bagitem WHERE user_id=:User_id AND bag_id=:Bag_id";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array(  "key" => ":Bag_id",
                        "value" =>$bag_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $row = $select->fetch();
    $n_results = $row[0];
    return $n_results;
}
/**
 * gets count items bag for a given user
 * @param integer $user_id the user id, 
 * @param string $country_code the country code
 * @param string $state_code the state code
 * @param integer $city_id the $city id, 
 * @return count items bag
 */
function userBagItemsCountOld($user_id,$country_code,$state_code,$city_id){

//  <start>
	global $dbConn;
	$params = array();  
    $bag_id = userBagAdd($user_id,$country_code,$state_code,$city_id,false);
    if(!$bag_id){
        return 0;
    }
    $query = "SELECT COUNT(id) FROM cms_bagitem WHERE user_id=:User_id AND bag_id=:Bag_id";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array(  "key" => ":Bag_id",
                        "value" =>$bag_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $row = $select->fetch();
    $n_results = $row[0];
    return $n_results;
}
/**
 * gets count items in All bags for a given user
 * @param integer $user_id the user id, 
 * @return count items in All bags
 */
function userAllBagItemsCount($user_id){
    global $dbConn;
    $params = array();
    $query = "SELECT COUNT(id) FROM cms_bagitem WHERE user_id=:User_id";
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $row = $select->fetch();
    $n_results = $row[0];
    return $n_results;
}
/**
 * add bag items for a given user 
 * @param integer $user_id the user id
 * @param string $type the item type
 * @param integer $item_id the item id
 * @param string $country_code the country code
 * @param string $state_code the state code
 * @param integer $city_id the $city id, 
 * @return integer | false the items count or false if not inserted
 */
function userBagItemsAdd($user_id,$type,$item_id,$bag_id){
    global $dbConn;
    $params  = array();
    $imgpath ='';
    $imgname ='';
    $bag_info = bagInfo($bag_id);
    if( $bag_info['imgpath']=='' || $bag_info['imgname']=='' ){
        if($type==SOCIAL_ENTITY_LANDMARK){
            $items_img = getPOIDefaultPic($item_id);
            if ( $items_img ) {
                $imgpath = 'media/discover/';
                $imgname = $items_img['img'];
            }
        }else if($type==SOCIAL_ENTITY_RESTAURANT){
//            $items_img = getRestaurantDefaultPic($item_id);
//            if ( $items_img ) {
//                $imgpath = 'media/discover/';
//                $imgname = $items_img['img'];
//            }
        }else if($type==SOCIAL_ENTITY_HOTEL){
            $items_img = getHotelImages($item_id);
            if ( sizeof($items_img)>0 ) {
                $imgpath = 'media/hotels/' . $items_img['hotel_id'] . '/' . $items_img['imageLocation'] . '/';
                $imgname = $items_img['image_source'];
            }
        }else if($type==SOCIAL_ENTITY_AIRPORT){
            $items_img = getAirportDefaultPic($item_id);
            if ( $items_img ) {
                $imgpath = 'media/discover/';
                $imgname = $items_img['img'];
            }
        }else if($type==SOCIAL_ENTITY_EVENTS){
            $items_img = channelEventInfo($item_id);
            if ($items_img['photo'] != '') {
                $imgpath = 'media/channel/' . $items_img['channelid'] . '/event/thumb/';
                $imgname = $items_img['photo'];
            }
        }
    }
    if( $imgname!='' && $imgpath!=''){
        $query = "UPDATE cms_bag SET imgname = :Imgname, imgpath = :Imgpath WHERE id=:Id";
        $params[] = array( "key" => ":Imgname", "value" =>$imgname);
        $params[] = array( "key" => ":Imgpath", "value" =>$imgpath);
        $params[] = array( "key" => ":Id", "value" =>$bag_id);
        $update = $dbConn->prepare($query);
        PDO_BIND_PARAM($update,$params);
        $update->execute();
    }
    $params = array();
    $params2 = array();  
    $params3 = array();  
    $params4 = array();
    if(!$bag_id){
        return false;
    }
    $query = "SELECT * FROM cms_bagitem WHERE user_id=:User_id AND item_id=:Item_id AND type=:Type AND bag_id=:Bag_id LIMIT 1";
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Item_id", "value" =>$item_id);
    $params[] = array(  "key" => ":Type", "value" =>$type);
    $params[] = array(  "key" => ":Bag_id","value" =>$bag_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if($res && $ret!=0){
        $query = "SELECT COUNT(id) FROM cms_bagitem WHERE user_id=:User_id AND bag_id=:Bag_id";
        $params2[] = array(  "key" => ":User_id",
                             "value" =>$user_id);
        $params2[] = array(  "key" => ":Bag_id",
                             "value" =>$bag_id);
	$select2 = $dbConn->prepare($query);
	PDO_BIND_PARAM($select2,$params2);
	$res    = $select2->execute();
	$row = $select2->fetch();
        $n_results = $row[0];
        return $n_results;
    }else{
        $query = "INSERT INTO cms_bagitem (user_id,type,bag_id,item_id)
                    VALUES (:User_id,:Type,:Bag_id,:Item_id)";
        $params3[] = array(  "key" => ":User_id", "value" =>$user_id);
        $params3[] = array(  "key" => ":Type", "value" =>$type);
        $params3[] = array(  "key" => ":Bag_id", "value" =>$bag_id);
        $params3[] = array(  "key" => ":Item_id", "value" =>$item_id);
	$select3 = $dbConn->prepare($query);
	PDO_BIND_PARAM($select3,$params3);
	$res    = $select3->execute();
        if( $res ){
            $query = "SELECT COUNT(id) FROM cms_bagitem WHERE user_id=:User_id AND bag_id=:Bag_id";
            $params4[] = array(  "key" => ":User_id", "value" =>$user_id);
            $params4[] = array(  "key" => ":Bag_id", "value" =>$bag_id);
            $select4 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select4,$params4);
            $res    = $select4->execute();
            $row = $select4->fetch();
            $n_results = $row[0];
            return $n_results;
        }else{
            return false;
        }
    }
}
/**
 * add bag items for a given user 
 * @param integer $user_id the user id
 * @param string $type the item type
 * @param integer $item_id the item id
 * @param string $country_code the country code
 * @param string $state_code the state code
 * @param integer $city_id the $city id, 
 * @return integer | false the items count or false if not inserted
 */
function userBagItemsAddOld($user_id,$type,$item_id,$country_code,$state_code,$city_id){

//  <start>
	global $dbConn;
	$params  = array();  
	$params2 = array();  
	$params3 = array();  
	$params4 = array();  
    $bag_id = userBagAdd($user_id,$country_code,$state_code,$city_id,true);
    if(!$bag_id){
        return false;
    }
    $query = "SELECT * FROM cms_bagitem WHERE user_id=:User_id AND item_id=:Item_id AND type=:Type AND bag_id=:Bag_id LIMIT 1";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array(  "key" => ":Item_id",
                        "value" =>$item_id);
    $params[] = array(  "key" => ":Type",
                        "value" =>$type);
    $params[] = array(  "key" => ":Bag_id",
                        "value" =>$bag_id);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    if($res && $ret!=0){
        $query = "SELECT COUNT(id) FROM cms_bagitem WHERE user_id=:User_id AND bag_id=:Bag_id";
        $params2[] = array(  "key" => ":User_id",
                             "value" =>$user_id);
        $params2[] = array(  "key" => ":Bag_id",
                             "value" =>$bag_id);
	$select2 = $dbConn->prepare($query);
	PDO_BIND_PARAM($select2,$params2);
	$res    = $select2->execute();
	$row = $select2->fetch();
        $n_results = $row[0];
        return $n_results;
    }else{
        $query = "INSERT INTO cms_bagitem (user_id,type,bag_id,item_id)
                    VALUES (:User_id,:Type,:Bag_id,:Item_id)";
        $params3[] = array(  "key" => ":User_id", "value" =>$user_id);
        $params3[] = array(  "key" => ":Type", "value" =>$type);
        $params3[] = array(  "key" => ":Bag_id", "value" =>$bag_id);
        $params3[] = array(  "key" => ":Item_id", "value" =>$item_id);
	$select3 = $dbConn->prepare($query);
	PDO_BIND_PARAM($select3,$params3);
	$res    = $select3->execute();
        if( $res ){
            $query = "SELECT COUNT(id) FROM cms_bagitem WHERE user_id=:User_id AND bag_id=:Bag_id";
            $params4[] = array(  "key" => ":User_id", "value" =>$user_id);
            $params4[] = array(  "key" => ":Bag_id", "value" =>$bag_id);
            $select4 = $dbConn->prepare($query);
            PDO_BIND_PARAM($select4,$params4);
            $res    = $select4->execute();
            $row = $select4->fetch();
            $n_results = $row[0];
            return $n_results;
        }else{
            return false;
        }
    }
}

/**
 * add bag for a given user 
 * @param integer $user_id the user id
 * @return bag id
 */
function addNewBag($user_id,$name){
    global $dbConn;
    $params  = array(); 
    $query = "INSERT INTO cms_bag (user_id,name) VALUES (:User_id,:Name)";
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Name", "value" =>$name);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    if( $res ){
        $insert_id=$dbConn->lastInsertId();
        return $insert_id;
    }else{
        return false;
    }
}
function addNewBagItem($user_id,$type,$bag_id,$item_id){
    global $dbConn;
    $params  = array();
    $imgpath ='';
    $imgname ='';
    $bag_info = bagInfo($bag_id);
    if( $bag_info['imgpath']=='' || $bag_info['imgname']=='' ){
        if($type==SOCIAL_ENTITY_LANDMARK){
            $items_img = getPOIDefaultPic($item_id);
            if ( $items_img ) {
                $imgpath = 'media/discover/';
                $imgname = $items_img['img'];
            }
        }else if($type==SOCIAL_ENTITY_RESTAURANT){
//            $items_img = getRestaurantDefaultPic($item_id);
//            if ( $items_img ) {
//                $imgpath = 'media/discover/';
//                $imgname = $items_img['img'];
//            }
        }else if($type==SOCIAL_ENTITY_HOTEL){
//            $items_img = getHotelDefaultPic($item_id);
//            if ( $items_img ) {
//                $imgpath = 'media/discover/';
//                $imgname = $items_img['img'];
//            }
            $items_img = getHotelImages($item_id);
            if ( sizeof($items_img)>0 ) {
                $imgpath = 'media/hotels/' . $items_img['hotel_id'] . '/' . $items_img['imageLocation'] . '/';
                $imgname = $items_img['image_source'];
            }
        }else if($type==SOCIAL_ENTITY_AIRPORT){
            $items_img = getAirportDefaultPic($item_id);
            if ( $items_img ) {
                $imgpath = 'media/discover/';
                $imgname = $items_img['img'];
            }
        }else if($type==SOCIAL_ENTITY_EVENTS){
            $items_img = channelEventInfo($item_id);
            if ($items_img['photo'] != '') {
                $imgpath = 'media/channel/' . $items_img['channelid'] . '/event/thumb/';
                $imgname = $items_img['photo'];
            }
        }
    }
    if( $imgname!='' && $imgpath!=''){
        $query = "UPDATE cms_bag SET imgname = :Imgname, imgpath = :Imgpath WHERE id=:Id";
        $params[] = array( "key" => ":Imgname", "value" =>$imgname);
        $params[] = array( "key" => ":Imgpath", "value" =>$imgpath);
        $params[] = array( "key" => ":Id", "value" =>$bag_id);
        $update = $dbConn->prepare($query);
        PDO_BIND_PARAM($update,$params);
        $update->execute();
    }
    $params = array();
    $query = "INSERT INTO cms_bagitem (user_id,type,bag_id,item_id) VALUES (:User_id,:Type,:Bag_id,:Item_id)";
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Type", "value" =>$type);
    $params[] = array(  "key" => ":Bag_id", "value" =>$bag_id);
    $params[] = array(  "key" => ":Item_id", "value" =>$item_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    if( $res ){
        $insert_id=$dbConn->lastInsertId();
        return $insert_id;
    }else{
        return false;
    }
}
/**
 * check user bag items for a given item id 
 * @param integer $user_id the user id
 * @param string $type the item type
 * @param double $item_id the item id
 * @return true | false 
 */
function checkUserBagItem($user_id,$type,$item_id){

//  <start>
    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_bagitem WHERE user_id='$user_id' AND item_id='$item_id' AND type='$type' LIMIT 1";
    $query = "SELECT * FROM cms_bagitem WHERE user_id=:User_id AND item_id=:Item_id AND type=:Type LIMIT 1";
    $params[] = array(  "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array(  "key" => ":Item_id",
                        "value" =>$item_id);
    $params[] = array(  "key" => ":Type",
                        "value" =>$type);
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

    if($res && $ret!=0){
        return true;
    }else{
        return false;
    }


}

/**
 * Retrieve bag items for sharing
 * @param integer $bag_id
 * @return bag items
 */
function bagItemsToShare($bag_id){

//  <start>
    global $dbConn;
    $params  = array();  
    $params2 = array(); 
    $params3 = array(); 
    $link = currentServerURL().'/media/discover/';
    $link1 = currentServerURL().'/';
    $bag_info = bagInfo($bag_id);
    $user_info = getUserInfo($bag_info['user_id']);
    if($bag_info){
        $bag_items = userBagItemsList($user_info['id'], $bag_info['id']);
        if($bag_items){
            $result = array();
            if ($user_info['gender'] == 'F') {
                $action_text_data = 'her own';
            } else {
                $action_text_data = 'his own';
            }
            $country_code = $bag_info['country_code'];
            $state_code = $bag_info['state_code'];
            $city_id = $bag_info['city_id'];
            $bag_name = '';
            if ($city_id != 0) {
                $city_info = worldcitiespopInfo($city_id);
                $bag_name = $city_info['name'];
                $country_code = strtoupper($city_info['country_code']);
                $state_code = $city_info['state_code'];
                $state_info = worldStateInfo($country_code, $state_code);
                $state_name = $state_info['state_name'];
                $bag_name .= ' - ' . $state_name;
                $country_info = countryGetInfo($country_code);
                $country_name = $country_info['name'];
                $bag_name .= ' - ' . $country_name;
            } else if ($state_code != '') {
                $state_info = worldStateInfo($country_code, $state_code);
                $state_name = $state_info['state_name'];
                $bag_name = $state_name;
                $country_info = countryGetInfo($country_code);
                $country_name = $country_info['name'];
                $bag_name .= ' - ' . $country_name;
            } else if ($country_code != '') {
                $country_info = countryGetInfo($country_code);
                $country_name = $country_info['name'];
                $bag_name = $country_name;
            }
        
            $result['ownerName'] = returnUserDisplayName($user_info);
            $result['city'] = $bag_name;
            $result['bagDesc'] = sprintf('<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">%s</font>
                                  <font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> shared '.$action_text_data.' TT bag </font>
                                  <font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">%s</font>', returnUserDisplayName($user_info) , $bag_name);
            
            $result['bag'] = array();
            foreach ($bag_items as $bag_item){
                $type = $bag_item['type'];
                $item_id = $bag_item['item_id'];
                if($type==SOCIAL_ENTITY_HOTEL){
//                   $query = "SELECT h.hotelName as name, count(r.id) as reviews, h.address as address, h.location as location, h.stars as stars, i.filename as img 
//                             FROM discover_hotels as h 
//                             INNER JOIN discover_hotels_images as i on i.hotel_id=h.id 
//                             LEFT JOIN discover_hotels_reviews as r on r.hotel_id=h.id AND r.published=1
//                             WHERE h.id=$item_id GROUP BY h.id ORDER BY i.default_pic DESC ";
                   $query = "SELECT h.hotelName as name, count(r.id) as reviews, h.address as address, h.location as location, h.stars as stars, i.filename as img 
                             FROM discover_hotels as h 
                             INNER JOIN discover_hotels_images as i on i.hotel_id=h.id 
                             LEFT JOIN discover_hotels_reviews as r on r.hotel_id=h.id AND r.published=1
                             WHERE h.id=:Item_id GROUP BY h.id ORDER BY i.default_pic DESC ";
//                   $ret = db_query($query);
                    $params[] = array(  "key" => ":Item_id",
                                        "value" =>$item_id);
                    $select = $dbConn->prepare($query);
                    PDO_BIND_PARAM($select,$params);
                    $res    = $select->execute();

                    $ret    = $select->rowCount();
//                   if($ret && db_num_rows($ret)!=0){
                   if($res && $ret!=0){
//                       $data_array = db_fetch_array($ret);
                       $data_array = $select->fetch();
                       $item = array();
                       $item['title'] = 'hotels';
                       $item['url'] = $link1.'en/thotel/id/'.$item_id;
                       $item['img'] = array($link.'thumb/'.$data_array['img'], htmlEntityDecode($data_array['name']));
                       $item['poiDetails'] = array();
                       $item['name'] = htmlEntityDecode($data_array['name']);
                       $item['address'] = $data_array['address'];
                       $item['location'] = htmlEntityDecode($data_array['location']);
                       $item['rate'] = $data_array['rating'];
                       $item['reviews'] = $data_array['reviews'];
                       $item['stars'] = $data_array['stars'];
                       $result['bag'][] = $item;
                   }else{
                      continue;
                   }
               }else if($type == SOCIAL_ENTITY_RESTAURANT){
                   continue;
                    $query = "SELECT dr.name as name, count(drr.id) as reviews, dr.address as address, '0' as stars, i.filename as img 
                              FROM discover_restaurants as dr 
                              INNER JOIN discover_restaurants_images as i on i.restaurant_id=dr.id 
                              LEFT JOIN discover_restaurants_reviews as drr on drr.restaurant_id=dr.id AND drr.published=1
                              WHERE dr.id=:Item_id GROUP BY dr.id ORDER BY i.default_pic DESC ";
                    $params2[] = array(  "key" => ":Item_id",
                                         "value" =>$item_id);
//                    $ret = db_query($query);
                    $select2 = $dbConn->prepare($query);
                    PDO_BIND_PARAM($select2,$params2);
                    $res    = $select2->execute();

                    $ret    = $select2->rowCount();
//                   if($ret && db_num_rows($ret)!=0){
                   if($res && $ret!=0){
//                       $data_array = db_fetch_array($ret);
                       $data_array = $select2->fetch();
                       $item = array();
                       $item['title'] = 'restaurants';
                       $item['url'] = $link1.'en/trestaurant/id/'.$item_id;
                       $item['img'] = array($link.'thumb/'.$data_array['img'], htmlEntityDecode($data_array['name']));
                       $item['poiDetails'] = array();
                       $item['name'] = htmlEntityDecode($data_array['name']);
                       $item['address'] = $data_array['address'];
                       $item['location'] = '';
                       $item['rate'] = 0;
                       $item['reviews'] = $data_array['reviews'];
                       $item['stars'] = $data_array['stars'];
                       $result['bag'][] = $item;
                   }else{
                      continue;
                   }
               }else if($type==SOCIAL_ENTITY_LANDMARK){
//                   $query = "SELECT poi.name as name, count(r.id) as reviews, poi.stars as stars, i.filename as img, ci.name as city, st.state_name as state, co.name as country
//                              FROM discover_poi as poi 
//                              INNER JOIN discover_poi_images as i on i.poi_id=poi.id 
//                              LEFT JOIN discover_poi_reviews as r on r.poi_id=poi.id AND r.published=1
//                              LEFT JOIN webgeocities as ci ON ci.id = poi.city_id
//                              LEFT JOIN states as st ON st.state_code = ci.state_code AND st.country_code = ci.country_code
//                              LEFT JOIN cms_countries as co ON co.code = ci.country_code
//                              WHERE poi.id=$item_id GROUP BY poi.id ORDER BY i.default_pic DESC ";
                   $query = "SELECT poi.name as name, count(r.id) as reviews, poi.stars as stars, i.filename as img, ci.name as city, st.state_name as state, co.name as country
                              FROM discover_poi as poi 
                              INNER JOIN discover_poi_images as i on i.poi_id=poi.id 
                              LEFT JOIN discover_poi_reviews as r on r.poi_id=poi.id AND r.published=1
                              LEFT JOIN webgeocities as ci ON ci.id = poi.city_id
                              LEFT JOIN states as st ON st.state_code = ci.state_code AND st.country_code = ci.country_code
                              LEFT JOIN cms_countries as co ON co.code = ci.country_code
                              WHERE poi.id=:Item_id GROUP BY poi.id ORDER BY i.default_pic DESC ";
                    $params3[] = array(  "key" => ":Item_id",
                                         "value" =>$item_id);
//                    $ret = db_query($query);
                    
                    $select3 = $dbConn->prepare($query);
                    PDO_BIND_PARAM($select3,$params3);
                    $res    = $select3->execute();

                    $ret    = $select3->rowCount();
                   if($res && $ret!=0){
//                       $data_array = db_fetch_array($ret);
                       $data_array = $select3->fetch();
                       $item = array();
                       $item['type'] = 'poi';
                       $item['title'] = 'things to do';
                       $item['url'] = $link1.'en/things2do/id/'.$item_id;
                       $item['img'] = array($link.'thumb/'.$data_array['img'], htmlEntityDecode($data_array['name']));
                       $item['poiDetails'] = array();
                       $item['name'] = htmlEntityDecode($data_array['name']);
                       $item['address'] = '';
                       $item['location'] = $data_array['city'].' - '.$data_array['state'].' - '.$data_array['country'];
                       $item['rate'] = 0;
                       $item['reviews'] = $data_array['reviews'];
                       $item['stars'] = $data_array['stars'];
                       $result['bag'][] = $item;
                   }else{
                      continue;
                   }
               }else if($type==SOCIAL_ENTITY_EVENTS){
                   $data_array   = channelEventInfo($item_id,-1);
                   $item = array();
                   $item['title'] = 'things to do';
                   if($data_array['photo'] != '')
                        $image = $link1.'media/channel/' . $data_array['channelid'] . '/event/thumb/' . $data_array['photo'];
                    else
                        $image = $link1.'media/images/channel/eventthemephoto.jpg';
                   
                   $item['img'] = array($image, htmlEntityDecode($data_array['name']), $image);
                   
                   $event_date = date( 'd / m / Y', strtotime($data_array['fromdate']) );
                   $fromtime_ts = strtotime($channeleventsInfo['fromtime']);
                   $fromtime = date('h:i A', $fromtime_ts);
                   $channel_array=channelGetInfo($data_array['channelid']);
                   $creator = htmlEntityDecode($channel_array['channel_name']);
                   $item['type'] = 'event';
                   $item['url'] = $link1.'en/channel-events-detailed/'.$item_id;
                   $item['poiDetails'] = array($event_date, $fromtime, htmlEntityDecode($data_array['location']), htmlEntityDecode($data_array['location_detailed']), $creator);
                   $item['name'] = htmlEntityDecode($data_array['name']);
                   $item['address'] = htmlEntityDecode($data_array['location_detailed']);
                   $item['location'] = htmlEntityDecode($data_array['location']);
                   $item['rate'] = 0;
                   $item['reviews'] = 0;
                   $item['stars'] = 0;
                   $result['bag'][] = $item;
                   
               }else{
                   continue;
               }
            }
            return $result;
        }
    }
    return false;


}