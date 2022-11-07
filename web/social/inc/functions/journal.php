<?php
	/**
	 * the journal functionality that deals with the cms_journals tables
	 * @package journal
	 */

	/**
	 * the journal date format
	 */
	define('JOURNAL_DATE_FMT','M-d-Y');
	/**
	 * no 3d or pdf for journal
	 */
	define('JOURNAL_EMPTY', 0);
	/**
	 * journal has 3d and pdf
	 */
	define('JOURNAL_3D_PDF', 1);
	/**
	 * journal has 3d
	 */
	define('JOURNAL_3D', 2);
	/**
	 * journal has pdf
	 */
	define('JOURNAL_PDF', 3);
	
	/**
	 * outputs the php journal date format
	 * @param mixed $_in_date if integere then timestamp. if string then date string. if null then current date
	 * @return string 
	 */
	function journalFormatDate($_in_date){
		
		$in_date = $_in_date;
		if(is_null($in_date)){
			
		}else if(intval($in_date) === $in_date){
			//already timestamp
			
		}else{
			//convert to timestamp
			$in_date = strtotime($in_date);
		}
		
		$out_date = is_null($in_date) ? date(JOURNAL_DATE_FMT) : date(JOURNAL_DATE_FMT,$in_date);
		
		return returnSocialTimeFormat($out_date,3);
	}

	/**
	 * checks if a journal name is unique
	 * @param type $journal_name
	 * @param type $journal_id
	 * @return boolean 
	 */
	function journalUniqueName($user_id,$journal_name,$journal_id=0){
            global $dbConn;
            $params = array();  
//		$query = "SELECT * FROM cms_journals WHERE user_id='$user_id' AND journal_name='$journal_name'";
//		if($journal_id!=0){
//			$query .=" AND id<>".$journal_id;		
//		}
//		$res = db_query($query);
//		if( !$res || db_num_rows($res) != 0 ) return false;
//		else return true;
            $query = "SELECT * FROM cms_journals WHERE user_id=:User_id AND journal_name=:Journal_name";
            if($journal_id!=0){
                    $query .=" AND id<>:Journal_id";
                    $params[] = array( "key" => ":Journal_id", "value" =>$journal_id);	
            }
            $params[] = array( "key" => ":User_id", "value" =>$user_id);
            $params[] = array( "key" => ":Journal_name", "value" =>$journal_name);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount();
            if( !$res || $ret != 0 ) return false;
            else return true;
	}
	
	/**
	 * Gets the journal details by id.
	 * @param $journal_id the journal id.
	 * @return array 
	 */
	function getJournalInfo($journal_id){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();
            $getJournalInfo = tt_global_get('getJournalInfo');
            if(isset($getJournalInfo[$journal_id]) && $getJournalInfo[$journal_id]!='')
                return $getJournalInfo[$journal_id];
//		$query = "SELECT * FROM cms_journals WHERE id = " . $journal_id . " LIMIT 1";
//		$res = db_query($query);		
//		if($res)
//			return db_fetch_array($res);
//		else
//			return false;
//          $query = "SELECT * FROM cms_journals WHERE id = :Journal_id LIMIT 1";  //Hide by Devendra
            $query = "SELECT `id`, `user_id`, `journal_name`, `journal_desc`, `journal_link`, `journal_ts`, `like_value`, `nb_comments`, `nb_shares`, `is_public`, `latitude`, `longitude`, `city_id`, `location_id`, `location_name`, `keywords`, `country`, `start_date`, `end_date`, `built`, `vpath`, `is_visible`, `enable_share_comment`, `published` FROM `cms_journals` WHERE id = :Journal_id LIMIT 1";  // Added by Devendra
            $params[] = array( "key" => ":Journal_id",
                                "value" =>$journal_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();		
            if($res){
                $row = $select->fetch();
            $getJournalInfo[$journal_id]    =   $row;
            return $row;
            } else {
                    $getJournalInfo[$journal_id]    =   false;
                    return false;
            } 
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * returns the cms_journals record given the link
	 * @param type $journal_name 
	 * @return array|false gets the journal 
	 */
	function journalGetByLink($journal_link){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array(); 
//            $query = "SELECT * FROM cms_journals WHERE journal_link='$journal_link'";
//            $res = db_query($query);
//            if( !$res || db_num_rows($res) == 0 ) return false;
//            $row = db_fetch_assoc($res);
//            return $row;
            $query = "SELECT * FROM cms_journals WHERE journal_link=:Journal_link";
            $params[] = array( "key" => ":Journal_link",
                                "value" =>$journal_link);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount();
            if( !$res || $ret == 0 ) return false;
            $row = $select->fetch(PDO::FETCH_ASSOC);
            return $row;
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * returns the link of a journal to be viewed
	 * @param array $journal_rec 
	 * @return string the link of the journal 
	 */
	function journalGetLink($journal_name,$id){
		$journal_link = trim($journal_name);
		$journal_link = remove_accents($journal_link);
		$journal_link = str_replace(' ', '-', $journal_link);
		while(strstr($journal_link,'--') != null)
			$journal_link = str_replace('--', '-', $journal_link);
		$journal_link = preg_replace('/[^a-z0-9A-Z\-]/', '', $journal_link);
		
		if(strlen($journal_link) == 0) return false;
		
		$journal_link = $journal_link . '-' . $id;
		$journal_link = str_replace('--', '-', $journal_link);
		return $journal_link;
	}
	
	/**
	 * Gets the journal-owner's id.
	 * @param type $journal_name
	 * @return integer
	 */
	function journalOwner($journal_id){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();  
//            $query = "SELECT user_id FROM cms_journals WHERE id='$journal_id'";
//            $res = db_query($query);
//            if( !$res || db_num_rows($res) == 0 ) return null;
//            $row = db_fetch_array($res);
//            return $row['user_id'];
            $query = "SELECT user_id FROM cms_journals WHERE id=:Journal_id";
            $params[] = array( "key" => ":Journal_id",
                                "value" =>$journal_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount();
            if( !$res || $ret == 0 ) return null;
            $row = $select->fetch();
            return $row['user_id'];
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * add a journal
	 * @param integer $user_id the cms_users id
	 * @param string $journal_name name of the journal
	 * @param string $journal_name description of the journal
	 * @return boolean 
	 */
	function journalAdd($user_id,$journal_name,$journal_desc , $placetakenat , $keywords , $cityid , $country , $location , $is_public , $date ){
            global $dbConn;
            $d = date($date);
            $date = date('Y-m-d',strtotime($d));
            $params  = array();  
            $params2 = array();
            $params3 = array();
            if(!journalGetLink($journal_name,0)) return false;
            $vpath = date('Y') . '/' . date('W') . '/';
            $query = "INSERT INTO cms_journals (user_id,journal_name,journal_desc,is_public,built,vpath,location_name,keywords,city_id,country,location_id,start_date,end_date,journal_link) VALUES (:User_id,:Journal_name,:Journal_desc,:Is_public,0,:Vpath, :Placetakenat , :Keywords , :Cityid , :Country , :Location , :Date, :Date ,'')";
            $params[] = array( "key" => ":User_id", "value" =>$user_id);
            $params[] = array( "key" => ":Journal_name", "value" =>$journal_name);
            $params[] = array( "key" => ":Journal_desc", "value" =>$journal_desc);
            $params[] = array( "key" => ":Is_public", "value" =>$is_public);
            $params[] = array( "key" => ":Vpath", "value" =>$vpath);
            $params[] = array( "key" => ":Placetakenat", "value" =>$placetakenat);
            $params[] = array( "key" => ":Keywords", "value" =>$keywords);
            $params[] = array( "key" => ":Cityid", "value" =>intval($cityid));
            $params[] = array( "key" => ":Country", "value" =>$country);
            $params[] = array( "key" => ":Location", "value" =>$location);
            $params[] = array( "key" => ":Date", "value" =>$date);
            $insert = $dbConn->prepare($query);
            PDO_BIND_PARAM($insert,$params);
            $res = $insert->execute();

            if( $res ){
                    $id = $dbConn->lastInsertId();
                    $journal_link = journalGetLink($journal_name,$id);
                    $query1  = "UPDATE cms_journals SET journal_link=:Journal_link WHERE id=:Id";
                    $params2[] = array( "key" => ":Journal_link",
                                         "value" =>$journal_link);
                    $params2[] = array( "key" => ":Id",
                                         "value" =>$id);
                    $update1 = $dbConn->prepare($query1);
                    PDO_BIND_PARAM($update1,$params2);
                    $update1->execute();
                    $query2  = "UPDATE cms_users SET n_journals=n_journals+1 WHERE id=:User_id";
                    $params3[] = array( "key" => ":User_id",
                                         "value" =>$user_id);
                    $update2 = $dbConn->prepare($query2);
                    PDO_BIND_PARAM($update2,$params3);
                    $update2->execute();

                    newsfeedAdd($user_id, $id , SOCIAL_ACTION_UPLOAD , $id , SOCIAL_ENTITY_JOURNAL , USER_PRIVACY_PUBLIC , NULL);

                    return $id;
            }
            else return false;
	}
	
	/**
	 * edit a journal
	 * @param integer $id the cms_journals' id
	 * @param string $journal_name name of the journal
	 * @return boolean true|false if success fail
	 */
	function journalEdit($id,$journal_name,$journal_desc,$is_public){
            global $dbConn;
            $params = array();
            $user_id = journalOwner($id);
            $journal_link = journalGetLink($journal_name,$user_id);
            $query  = "UPDATE cms_journals SET journal_name=:Journal_name,journal_desc=:Journal_desc,journal_link=:Journal_link,is_public=:Is_public WHERE id=:Id";
            $params[] = array( "key" => ":Journal_name", "value" =>$journal_name);
            $params[] = array( "key" => ":Journal_desc", "value" =>$journal_desc);
            $params[] = array( "key" => ":Journal_link", "value" =>$journal_link);
            $params[] = array( "key" => ":Is_public", "value" =>$is_public);
            $params[] = array( "key" => ":Id", "value" =>$id);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res = $update->execute();
            if( $res ) return true;
            else return false;
	}
	/**
	 * edits a  journal info
	 * @param array $new_info the new cms_journals info
	 * @return boolean true|false if success|fail
	 */
	function journalInfoEdit($new_info){
            global $dbConn;
            $params = array();
            $query = "UPDATE cms_journals SET ";
            $i = 0;
            foreach( $new_info as $key => $val){
                if( $key != 'id' && $key != 'user_id'){
                    if($val!='' ){
                        if($val=='NULL' || $val==NULL){
//                            $query .= " $key = $val,";
                            $query .= " $key = :Val".$i.",";
                            $params[] = array( "key" => ":Val".$i, "value" => NULL , 'type'=>'::PARAM_STR');
                        }else{
//                            $query .= " $key = '$val',";
                            $query .= " $key = :Val".$i.",";
                            $params[] = array( "key" => ":Val".$i, "value" => $val);
                        }
                        $i++;
                    }
                }
            }
            $query = trim($query,',');
            $query .= " WHERE id=:Id AND user_id=:User_id ";
            $params[] = array( "key" => ":Id", "value" => $new_info['id']);
            $params[] = array( "key" => ":User_id", "value" =>$new_info['user_id']);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $ret = $update->execute();
            return ( $ret ) ? true : false;
	}
	
	/**
	 * Edits any journal's field based on an array of the fields to change.
	 * @param integer $journal_id the journal's id.
	 * @param array $values_arr an array of fields => values to change.
	 * @return boolean true on success false on fail.
	 */
	function journalEditDetails($user_id, $journal_id, $values_arr){
            global $dbConn;
            $params = array(); 
            // Build the fields => values sql syntax.
            $values_str = '';
            $i=0;
            foreach($values_arr as $field => $value):
                $values_str .= $field . " = :Values$i,";
                $params[] = array( "key" => ":Values$i", "value" =>$value);
                $i++;
            endforeach;
            $values_str = trim($values_str, ',');
            $query  = "UPDATE cms_journals SET " . $values_str . " WHERE id = :journal_id AND user_id =:User_id LIMIT 1";
            $params[] = array( "key" => ":journal_id", "value" =>$journal_id);
            $params[] = array( "key" => ":User_id", "value" =>$user_id);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res = $update->execute();

            if( $res ){
                return true;
            }else{
                return false;
            }
	}
        
	function journalEditDetailsDelete($user_id, $journal_id, $values_arr){
            global $dbConn;
            $params = array();  
		
            // Build the fields => values sql syntax.
            $i=0;
            foreach($values_arr as $field => $value):
                $values_str .= $field . " = :Values$i,";
                $params[] = array( "key" => ":Values$i", "value" =>$value);
                $i++;
            endforeach;
            $values_str = trim($values_str, ',');
            $query  = "UPDATE cms_journals SET " . $values_str . " WHERE id = :Journal_id AND user_id = :User_id  LIMIT 1";
            $params[] = array( "key" => ":Journal_id", "value" =>$journal_id);
            $params[] = array( "key" => ":User_id", "value" =>$user_id);
            
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res = $update->execute();

            if( $res ){
                newsfeedDeleteAll($journal_id,SOCIAL_ENTITY_JOURNAL);
                journalDeleteItems($journal_id);
                    return true;
            }else{
                    return false;
            }
	}
	
	/**
	 * deletes a journal and all its items
	 * @param type $id 
	 */
	function journalDelete($id){
            global $dbConn;
            $params  = array();  
            $params2 = array();  
            $user_id = journalOwner($id);
//            $query = "DELETE FROM cms_journals WHERE id='$id'";
            $query  = "DELETE FROM cms_journals WHERE id=:Id";
            $params[] = array( "key" => ":Id", "value" =>$id);
            $delete = $dbConn->prepare($query);
            PDO_BIND_PARAM($delete,$params);
            $res = $delete->execute();
            if( $res ){
                journalDeleteItems($id);
                $query = "UPDATE cms_users SET n_journals=n_journals-1 WHERE id=:User_id";
                $params2[] = array( "key" => ":User_id", "value" =>$user_id);
                $update = $dbConn->prepare($query);
                PDO_BIND_PARAM($update,$params2);
                $res = $update->execute();

                return true;
            }
            else
                return false;
	}
	
	/**
	 * gets the journal item's owner 
	 * @param integer $item_id
	 * @return integer the owner user_id
	 */
	function journalItemOwner($item_id){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();  
//            $query = "SELECT journal_id FROM cms_journals_items WHERE id='$item_id'";
//            $res = db_query($query);
//            if(!$res || db_num_rows($res)==0) return null;
//            $row = db_fetch_array($res);
//            $journal_id = $row['journal_id'];
//            return journalOwner($journal_id);
            $query = "SELECT journal_id FROM cms_journals_items WHERE id=:Item_id";
            $params[] = array( "key" => ":Item_id",
                                "value" =>$item_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount();
            if(!$res || $ret==0) return null;
            $row = $select->fetch();
            $journal_id = $row['journal_id'];
            return journalOwner($journal_id);
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * adds an item to the journal
	 * @param integer $journal_id which journal
	 * @param string $desc the description of the item
	 * @param string $vpath the relative path to the pircture
	 * @param string $pic the picture associated with the item
	 * @param integer $order the order of the item in the journal
	 * @param double $_longitude longitude of locatio or null
	 * @param double $_latitude latitude of location or null
	 * @param integer $_city_id the cms_cmities record id or null or 0
	 * @param string $_location_id the cms_locations id or null
	 * @param string $location_name the name of the location
	 * @return boolean true|false
	 */
	function journalItemAdd($journal_id,$desc,$vpath,$pic,$_longitude,$_latitude,$_city_id,$_location_id,$location_name,$item_ts,$keywords,$title){
            global $dbConn;
            $params  = array();  
            $params2 = array();  

            $longitude = $_longitude;
            $latitude = $_latitude;
            $city_id = intval($_city_id);
            $location_id = $_location_id;

            if(is_null($longitude)) $longitude = 'NULL';
            if(is_null($latitude)) $latitude = 'NULL';
            if($city_id == 0) $city_id = 0;
            if(is_null($location_id)) $location_id = 0;

            $query = "SELECT MAX(item_order) FROM cms_journals_items WHERE journal_id=:Journal_id";
            $params[] = array( "key" => ":Journal_id", "value" =>$journal_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount();
            if(!$res || $ret == 0 ){
                $order = 1;
            }else{
                $row = $select->fetch();
                $order = intval($row[0]) + 1;
            }
            $params = array();
            $query = "INSERT INTO cms_journals_items (journal_id,item_desc,item_pic,item_order,pic_vpath,longitude,latitude,city_id,location_id,location_name,item_ts,keywords,name)
                                    VALUES (:Journal_id,:Desc,:Pic,:Order,:Vpath,:Longitude,:Latitude,:City_id,:Location_id,:Location_name,:Item_ts,:Keywords,:Title)";
            $params[] = array( "key" => ":Journal_id", "value" =>$journal_id);
            $params[] = array( "key" => ":Desc", "value" =>$desc);
            $params[] = array( "key" => ":Pic", "value" =>$pic);
            $params[] = array( "key" => ":Order", "value" =>$order);
            $params[] = array( "key" => ":Vpath", "value" =>$vpath);
            $params[] = array( "key" => ":Longitude", "value" =>$longitude);
            $params[] = array( "key" => ":Latitude", "value" =>$latitude);
            $params[] = array( "key" => ":City_id", "value" =>$city_id);
            $params[] = array( "key" => ":Location_id", "value" =>$location_id);
            $params[] = array( "key" => ":Location_name", "value" =>$location_name);
            $params[] = array( "key" => ":Item_ts", "value" =>$item_ts);
            $params[] = array( "key" => ":Keywords", "value" =>$keywords);
            $params[] = array( "key" => ":Title", "value" =>$title);
            $insert = $dbConn->prepare($query);
            PDO_BIND_PARAM($insert,$params);
            $res = $insert->execute();

            if( $res ){
                $insert_id = $dbConn->lastInsertId();
                return array('id'=>$insert_id,'item_order' => $order);
            } else return false;
	}
	
	/**
	 * edits a journals items
	 * @param integer $journal_item_id which journal item
	 * @param string $desc the description of the item
	 * @param string $vpath the relative path to the pircture
	 * @param string $pic the picture associated with the item
	 * @param integer $order the order of the item in the journal
	 * @param double $_longitude longitude of locatio or null
	 * @param double $_latitude latitude of location or null
	 * @param integer $_city_id the cms_cmities record id or null or 0
	 * @param string $_location_id the cms_locations id or null
	 * @param string $location_name the name of the location
	 * @return boolean true|false if success fail
	 */
	function journalItemEdit($journal_item_id,$desc,$vpath,$pic,$_longitude,$_latitude,$_city_id,$_location_id,$location_name,$item_ts){
            global $dbConn;
            $params = array();  
		
            $longitude = $_longitude;
            $latitude = $_latitude;
            $city_id = intval($_city_id);
            $location_id = $_location_id;

            if(is_null($longitude)) $longitude = 'NULL';
            if(is_null($latitude)) $latitude = 'NULL';
            if($city_id == 0) $city_id = 0;
            if(is_null($location_id)) $location_id = 0;
            $query = "UPDATE
                            cms_journals_items
                      SET
                            item_desc=:Desc,
                            item_pic=:Pic,
                            pic_vpath=:Vpath,
                            longitude=:Longitude,
                            latitude=:Latitude,
                            city_id=:City_id,
                            location_id=:Location_id,
                            location_name=:Location_name,
                            item_ts=:Item_ts
                      WHERE
                            id=:Journal_item_id";

            $params[] = array( "key" => ":Desc",
                                "value" =>$desc);
            $params[] = array( "key" => ":Pic",
                                "value" =>$pic);
            $params[] = array( "key" => ":Vpath",
                                "value" =>$vpath);
            $params[] = array( "key" => ":Longitude",
                                "value" =>$longitude);
            $params[] = array( "key" => ":Latitude",
                                "value" =>$latitude);
            $params[] = array( "key" => ":City_id",
                                "value" =>$city_id);
            $params[] = array( "key" => ":Location_id",
                                "value" =>$location_id);
            $params[] = array( "key" => ":Location_name",
                                "value" =>$location_name);
            $params[] = array( "key" => ":Item_ts",
                                "value" =>$item_ts);
            $params[] = array( "key" => ":Journal_item_id",
                                "value" =>$journal_item_id);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res = $update->execute();
            if( $res ) return true;
            else return false;
	}
	
	/**
	 * Updates a set if fields and values according to a provided array.
	 * @param integer $item_id the journal item id to be updated.
	 * @param array $updates the array of updates in the form: field => value.
	 * @return boolean true on success false on failure.
	 */
	function journalItemDynamicEdit($item_id, $updates){
            global $dbConn;
            $params  = array();  
            $params2 = array();  
            $updates_str = '';
            $i=0;
            foreach ($updates as $field => $value):
                    $updates_str .= $field . " = :Values$i, ";
                    $params2[] = array( "key" => ":Values$i", "value" =>$value);
                    $i++;
            endforeach;
            $updates_str .= " item_ts = NOW()";

            $journal_info =journalGetItemDetails($item_id);
            $journal_id = $journal_info['journal_id'];
            if( intval($updates['default_pic']) == 1 ){
                $query = "UPDATE cms_journals_items SET default_pic='0' WHERE journal_id = :Journal_id ";
                $params[] = array( "key" => ":Journal_id", "value" =>$journal_id);
                $update = $dbConn->prepare($query);
                PDO_BIND_PARAM($update,$params);
                $update->execute();
            }
            $query2  = "UPDATE cms_journals_items SET " . $updates_str . " WHERE id = :Item_id LIMIT 1";
            $params2[] = array( "key" => ":Item_id", "value" =>$item_id);
            
            $update2 = $dbConn->prepare($query2);
            PDO_BIND_PARAM($update2,$params2);
            $res     = $update2->execute();
            if( $res )
                return true;
            else
                return false;
	}
	
	/**
	 * helper function used by add, edit, and delete
	 * @param integer $jid the journal id
	 * @param string $date mysql date string or null in case of query
	 * @return array the new start_date,end_date as timestamps
	 */
	function journalDates($jid){
            global $dbConn;
            $params  = array();  
            $params2 = array();  
//            $query = "SELECT MAX(item_ts) AS max,MIN(item_ts)  AS min FROM cms_journals_items WHERE journal_id='$jid'";
//            $res = db_query($query);
            $query  = "SELECT MAX(item_ts) AS max,MIN(item_ts)  AS min FROM cms_journals_items WHERE journal_id=:Jid";
            $params[] = array( "key" => ":Jid", "value" =>$jid);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $ret    = $select->rowCount();
            
            if(!$res || ($ret == 0) ){
                    return array(null,null);
            }else{
                    $row = $select->fetch();
                    $params  = array();
                    $query = "UPDATE cms_journals SET start_date=:S_date,end_date=:E_date WHERE id=:Jid";
                    $params[] = array( "key" => ":S_date", "value" =>$row[1]);
                    $params[] = array( "key" => ":E_date", "value" =>$row[0]);
                    $params[] = array( "key" => ":Jid", "value" =>$jid);
                    $update = $dbConn->prepare($query);
                    PDO_BIND_PARAM($update,$params);
                    $update->execute();

                    $max_ts = strtotime($row[0]);
                    $min_ts = strtotime($row[1]);

                    return array('start_date' => $min_ts, 'end_date' => $max_ts);
            }
	}
	
	/**
	 * deletes an item
	 * @param type $item_id
	 * @return boolean true|false
	 */
	function journalItemDelete($item_id){
            global $dbConn;
            $params  = array();  
            $params2 = array();  
            $params3 = array();  
            global $CONFIG;

//            $query = "SELECT * FROM cms_journals_items WHERE id='$item_id' ";
//            $res = db_query($query);
            $query  = "SELECT * FROM cms_journals_items WHERE id=:Item_id ";
            $params[] = array( "key" => ":Item_id",
                                "value" =>$item_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res = $select->execute();

            $ret    = $select->rowCount();
            
//            if(!$res || db_num_rows($res)==0){
            if(!$res || $ret ==0){
                    return false;
            }else{
//                    $row = db_fetch_assoc($res);
                    $row = $select->fetch();
                    @unlink( $CONFIG ['server']['root'] . $row['pic_vpath'] . $row['item_pic'] );
                    @unlink( $CONFIG ['server']['root'] . $row['pic_vpath'] . "thumb_" . $row['item_pic'] );
//                    $query = "DELETE FROM cms_journals_items WHERE id='$item_id'";
//                    db_query($query);
                    $query  = "DELETE FROM cms_journals_items WHERE id=:Item_id";
                    $params2[] = array( "key" => ":Item_id",
                                         "value" =>$item_id);
                    $delete = $dbConn->prepare($query);
                    PDO_BIND_PARAM($delete,$params2);
                    $delete->execute();

                    //order
                    $order = $row['item_order'];
                    $journal_id = $row['journal_id'];
                    $query  = "UPDATE cms_journals_items SET item_order=item_order - 1 WHERE journal_id=:Journal_id AND item_order>:Order";
                    $params3[] = array( "key" => ":Journal_id",
                                         "value" =>$journal_id);
                    $params3[] = array( "key" => ":Order",
                                         "value" =>$order);
                    $update = $dbConn->prepare($query);
                    PDO_BIND_PARAM($update,$params3);
                    $update->execute();

                    return true;
            }
	}
	
	/**
	 * change the order of a journal item
	 * @param integer $item_id which item?
	 * @param integer $new_pos the items news position
	 * @return boolean true|false success|fail
	 */
	function journalItemOrder($item_id,$new_order){
            global $dbConn;
            $params  = array();  
            $params2 = array();  
            $params3 = array();  
            $query  = "SELECT * FROM cms_journals_items WHERE id=:Item_id ";
            $params[] = array( "key" => ":Item_id",
                                "value" =>$item_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $ret    = $select->rowCount();
            if(!$res || $ret==0){
                    return false;
            }else{
                    $row = $select->fetch();

                    $old_order = $row['item_order'];
                    $journal_id = $row['journal_id'];

                    if($new_order > $old_order){
                        $query2 = "UPDATE cms_journals_items SET item_order=item_order - 1 WHERE journal_id=:Journal_id AND item_order>:Old_order AND item_order<=:New_order";
                    }else{
                        $query2 = "UPDATE cms_journals_items SET item_order=item_order + 1 WHERE journal_id=:Journal_id AND item_order<:Old_order AND item_order>=:New_order";
                    }
                    $params2[] = array( "key" => ":Journal_id",
                                         "value" =>$journal_id);
                    $params2[] = array( "key" => ":Old_order",
                                         "value" =>$old_order);
                    $params2[] = array( "key" => ":New_order",
                                         "value" =>$new_order);
                    $update = $dbConn->prepare($query2);
                    PDO_BIND_PARAM($update,$params2);
                    $update->execute();
                    $query3  = "UPDATE cms_journals_items SET item_order=:New_order WHERE id=:Item_id";
                    $params3[] = array( "key" => ":Item_id",
                                         "value" =>$item_id);
                    $params3[] = array( "key" => ":New_order",
                                         "value" =>$new_order);
                    $update2 = $dbConn->prepare($query3);
                    PDO_BIND_PARAM($update2,$params3);
                    $update2->execute();
                    return true;
            }
	}
	
	/**
	 * Saves the positions of a batch of items from an array of item-ids, in the same order they are given in the array.
	 * @param array $items_arr an array of item-ids, will be saved in the same order this array is in.
	 * @return void
	 */
	function journalOrderAllItems($items_arr){
            global $dbConn;  
            $position = 0;
            foreach ($items_arr as $item):
                $params = array();
                $query = "UPDATE cms_journals_items SET item_order = :Position  WHERE id = :Item";
                $position++;
                $params[] = array( "key" => ":Position", "value" =>$position);
                $params[] = array( "key" => ":Item", "value" =>$item);
                $update = $dbConn->prepare($query);
                PDO_BIND_PARAM($update,$params);
                $update->execute();
            endforeach;
	}
	
	/**
	 * 
	 * @param type $item_id 
	 * @return boolean true|false if success fail
	 */
	function journalItemDefault($jid,$item_id){
            global $dbConn;
            $params  = array();  
            $params2 = array();  
            $params3 = array();  
            $params4 = array();
            $query  = "UPDATE cms_journals_items SET default_pic=0 WHERE journal_id=:Jid";
            $params[] = array( "key" => ":Jid", "value" =>$jid);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res    = $update->execute();
            if(!$res) return false;
            $query2  = "UPDATE cms_journals_items SET default_pic=1 WHERE id=:Item_id";
            $params2[] = array( "key" => ":Item_id", "value" =>$item_id);
            $update2 = $dbConn->prepare($query2);
            PDO_BIND_PARAM($update2,$params2);
            $res    = $update2->execute();
            if(!$res) return false;
            $query3 = "SELECT * FROM cms_journals_items WHERE id=:Item_id";            
            $params3[] = array( "key" => ":Item_id", "value" =>$item_id);
            $select = $dbConn->prepare($query3);
            PDO_BIND_PARAM($select,$params3);
            $select->execute();
            $row = $select->fetch();
            $latitude = $row['latitude'];
            $longitude = $row['longitude'];
            $location_id = $row['location_id'];
            $location_name = htmlEntityDecode($row['location_name']);
            $city_id = intval($row['city_id']);

            if(is_null($longitude)) $longitude = 'NULL';
            if(is_null($latitude)) $latitude = 'NULL';
            if($city_id == 0) $city_id = 0;
            if(is_null($location_id)) $location_id = 0;
            $params3=array();
            $query4 = "UPDATE cms_journals SET
                            location_id     =:Location_id,
                            latitude        =:Latitude,
                            longitude       =:Longitude,
                            city_id         =:City_id,
                            location_name   =:Location_name
                      WHERE id=:Jid";
            $params3[] = array( "key" => ":Location_id", "value" =>$location_id);
            $params3[] = array( "key" => ":Latitude", "value" =>$latitude);
            $params3[] = array( "key" => ":Longitude", "value" =>$longitude);
            $params3[] = array( "key" => ":City_id", "value" =>$city_id);
            $params3[] = array( "key" => ":Location_name", "value" =>$location_name);
            $params3[] = array( "key" => ":Jid", "value" =>$jid);
            $update3 = $dbConn->prepare($query4);
            PDO_BIND_PARAM($update3,$params3);
            $res    = $update3->execute();
            return true;
	}
	
	/**
	 * deletes a journal's items
	 * @param type $jid which journal?
	 * @return boolean true|false if success fail
	 */
	function journalDeleteItems($jid){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params  = array(); 
            $params2 = array(); 
            global $CONFIG;
//            $query = "SELECT * FROM cms_journals_items WHERE journal_id='$jid' ";
//            $res = db_query($query);
            $query  = "SELECT * FROM cms_journals_items WHERE journal_id=:Jid ";
            $params[] = array( "key" => ":Jid", "value" =>$jid);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $ret    = $select->rowCount();
//            if(!$res || db_num_rows($res)==0){
            if(!$res || $ret==0){
                    return false;
            }else{
//                    while($row = db_fetch_array($res)){
//                            @unlink( $CONFIG ['server']['root'] . $row['pic_vpath'] . $row['item_pic'] );
//                            @unlink( $CONFIG ['server']['root'] . $row['pic_vpath'] . "thumb_" . $row['item_pic'] );
//                    }
                $row = $select->fetch();
                foreach($row as $row_item){
                    @unlink( $CONFIG ['server']['root'] . $row_item['pic_vpath'] . $row_item['item_pic'] );
                    @unlink( $CONFIG ['server']['root'] . $row_item['pic_vpath'] . "thumb_" . $row_item['item_pic'] );
                }
            }
//            $query = "DELETE FROM cms_journals_items WHERE journal_id='$jid'";
            $query = "DELETE FROM cms_journals_items WHERE journal_id=:Jid";
            $params2[] = array( "key" => ":Jid", "value" =>$jid);
//            db_query($query);
            $delete = $dbConn->prepare($query);
            PDO_BIND_PARAM($delete,$params2);
            $res    = $delete->execute();
            return true;
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * gets all a journal's items
	 * @param integer $jid which journal?
	 * @return array list of items associated with a journal
	 */
	function journalGetItems($jid){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();  
//            $query = "SELECT * FROM cms_journals_items WHERE journal_id='$jid' AND published = 1 ORDER BY item_order ASC";
//            $res = db_query($query);
//            $ret = array();
//            if(!$res || db_num_rows($res) == 0) return $ret;
//            while( $row = db_fetch_assoc($res) ){
//                    $ret[] = $row;
//            }
//            return $ret;
            $query  = "SELECT * FROM cms_journals_items WHERE journal_id=:Jid AND published = 1 ORDER BY item_order ASC";
            $params[] = array( "key" => ":Jid",
                                "value" =>$jid);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $ret    = array();

            $ret1   = $select->rowCount();
            if(!$res || $ret1 == 0) return $ret;
            $ret = $select->fetchAll(PDO::FETCH_ASSOC);
            return $ret;
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * Gets the details of a journal items.
	 * @param integer $item_id the id of the item to search for.
	 * @return array list of the details of the item, false on error.
	 */
	function journalGetItemDetails($item_id){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array(); 
//            $query = "SELECT * FROM cms_journals_items WHERE (id = " . $item_id . ") LIMIT 1";
//            $res = db_query($query);
//
//            if(!$res)
//                return false;
//            else{
//                return db_fetch_assoc($res);
//            }
            $query  = "SELECT * FROM cms_journals_items WHERE id = :Item_id LIMIT 1";
            $params[] = array( "key" => ":Item_id", "value" =>$item_id);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            if(!$res)
                return false;
            else{
                $row = $select->fetch(PDO::FETCH_ASSOC);
                return $row;
            }
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>
	}
	
	
	/**
	 * checks if a user liked a journal item
	 * @param integer $item_id the journal item id
	 * @param integer $user_id the cms_users id
	 * @return false|integer false if not liked or the like value
	 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//	function journalItemLiked($item_id,$user_id){
//		return socialLiked($user_id, $item_id, SOCIAL_ENTITY_JOURNAL_ITEM);
//	}
	
	/**
	 * like a journal item
	 * @param integer $journal_id the journal id
	 * @param integer $user_id the cms_users id
	 * @param intger $like_value like (1) or dislike (-1)
	 * @return boolean true|false if operation succeeded or failed
	 */
//	function journalItemLike($item_id,$user_id,$like_value){
//		
//		if( ($like_value != -1) && ($like_value != 1) ) return false;
//		
//		if( socialLiked($user_id, $item_id, SOCIAL_ENTITY_JOURNAL_ITEM) ){
//			socialLikeEdit($user_id, $item_id, SOCIAL_ENTITY_JOURNAL_ITEM, $like_value);
//		}else{
//			socialLikeAdd($user_id, $item_id, SOCIAL_ENTITY_JOURNAL_ITEM, $like_value, null);
//		}
//	}
	
	/**
	 * checks if a user liked a journal
	 * @param integer $journal_id the journal id
	 * @param integer $user_id the cms_users id
	 * @return false|integer false if not liked or the like value
	 */
//	function journalLiked($journal_id,$user_id){
//		return socialLiked($user_id, $journal_id, SOCIAL_ENTITY_JOURNAL);
//	}
	
	/**
	 * like a journal
	 * @param integer $journal_id the journal id
	 * @param integer $user_id the cms_users id
	 * @param intger $like_value like (1) or dislike (-1)
	 * @return boolean true|false if operation succeeded or failed
	 */
//	function journalLike($journal_id,$user_id,$like_value){
//		
//		if( ($like_value != -1) && ($like_value != 1) ) return false;
//		
//		if( socialLiked($user_id, $journal_id, SOCIAL_ENTITY_JOURNAL) ){
//			socialLikeEdit($user_id, $journal_id, SOCIAL_ENTITY_JOURNAL, $like_value);
//		}else{
//			socialLikeAdd($user_id, $journal_id, SOCIAL_ENTITY_JOURNAL, $like_value, null);
//		}
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>
	
	/**
	* search for videos given certain options. options include:<br/>
	* <b>limit</b>: the maximum number of media records returned. default 20<br/>
	* <b>page</b>: the number of pages to skip. default 0<br/>
	* <b>public</b>: wheather the media file is public or not. 0 => private, 1=> community, 2=> public. default 2<br/>
	* <b>userid</b>: the media file's owner's id. default null<br/>
	* <b>orderby</b>: the order to base the result on. values include any column of table. default 'id' or similarity<br/>
	* <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
	* <b>latitude</b>: the latitude of the location to search within<br/>
	* <b>longitude</b>: the logitude of the location to search within<br/>
	* <b>radius</b>: the radius to search within (in meters)<br/>
	* <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
	* <b>search_string</b>: the string to search for. could be space separated. no default<br/>
	* <b>search_where</b>: where to search for the string (t)itle, (d)escription, (l)ocation (a)ll, or a comma separated combination. default is 'a'<br/>
	* <b>location_id<b/>: the location_id to search for.<br/>
	* <b>city_id<b/>: the city_id to search for.<br/>
	* <b>n_results</b>: gets the number of results rather than the rows. default false.
	* @param array $srch_options. the search options
	* @return array a number of media records
	*/
	function journalSearch($srch_options){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params  = array();  
            $params2 = array();

            $default_opts = array(
                    'limit' => 20,
                    'page' => 0,
                    'public' => 2,
                    'userid' => null,
                    'id' => null,
                    'orderby' => 'id',
                    'order' => 'a',
                    'from_ts' => null,
                    'to_ts' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'radius' => 1000,
                    'dist_alg' => 's',
                    'search_string' => null,
                    'search_where' => 'a',
                    'location_id' => null,
                    'is_visible' => -1,
                    'city_id' => null,
                    'published' => 1,
                    'built' => null,
                    'n_results' => false
            );

            $options = array_merge($default_opts, $srch_options);

            if( ($ch = channelGlobalGet()) != null ){
                    //$options['channel_id'] = $ch['id'];
                    $options['userid'] = $ch['owner_id'];
            }

            /* no trends from search queries
            if($options['search_string'] !== null)
                    queryAdd($options['search_string']);
            */

            $nlimit = intval($options['limit']);
            $skip = intval($options['page']) * $nlimit;

            $where = '';

            if( !is_null($options['city_id']) ){
                    if($where != '') $where .= ' AND ';
//                    $where .= " city_id={$options['city_id']} ";
                    $where .= " city_id=:City_id ";
                    $params[] = array( "key" => ":City_id",
                                        "value" =>$options['city_id']);
            }
            if ( $options['is_visible'] !=-1 ) {
                if ($where != '') $where .= ' AND ';
                $where .= " is_visible=:Is_visible ";
                $params[] = array(  "key" => ":Is_visible", "value" =>$options['is_visible']);
            }
            if( !is_null($options['id']) ){
                    if($where != '') $where .= ' AND ';
//                    $where .= " id={$options['id']} ";
                    $where .= " id=:Id ";
                    $params[] = array( "key" => ":Id",
                                        "value" =>$options['id']);
            }

            if( !is_null($options['location_id']) ){
                    if($where != '') $where .= ' AND ';
//                    $where .= " location_id={$options['location_id']} ";
                    $where .= " location_id=:Location_id ";
                    $params[] = array( "key" => ":Location_id",
                                        "value" =>$options['location_id']);
            }

            if( !is_null($options['userid']) ){

                    if($where != '') $where .= ' AND ';
//                    $where .= " user_id={$options['userid']} ";
                    $where .= " user_id=:Userid ";
                    $params[] = array( "key" => ":Userid",
                                        "value" =>$options['userid']);

            }

            if( !is_null($options['published']) ){
                    if( $where != '') $where .= ' AND ';
//                    $where .= " published={$options['published']} ";
                    $where .= " published=:Published ";
                    $params[] = array( "key" => ":Published",
                                        "value" =>$options['published']);
            }

            if(!is_null($options['built'])){
                    if($where != '') $where .= ' AND ';
//                    $where .= " built>='{$options['built']}' ";
                    $where .= " built>=:Built ";
                    $params[] = array( "key" => ":Built",
                                        "value" =>$options['built']);
            }

            if(!is_null($options['from_ts'])){
                    if( $where != '') $where .= " AND ";
//                    $where .= " DATE(end_date) >= '{$options['from_ts']}' ";
                    $where .= " DATE(end_date) >= :From_ts ";
                    $params[] = array( "key" => ":From_ts",
                                        "value" =>$options['from_ts']);
            }
            if(!is_null($options['to_ts'])){
                    if( $where != '') $where .= " AND ";
//                    $where .= " DATE(start_date) <= '{$options['to_ts']}' ";
                    $where .= " DATE(start_date) <= :To_ts ";
                    $params[] = array( "key" => ":To_ts",
                                        "value" =>$options['to_ts']);
            }

            if( userIsLogged() ) {
                $searcher_id = userGetID();
                $friends = userGetFreindList($searcher_id);
                $friends_ids = array($searcher_id);
                foreach($friends as $freind){
                    $friends_ids[] = $freind['id'];
                }
                if(count($friends_ids)!=0){
                    if($where != '') $where .= " AND ";
                    $public = USER_PRIVACY_PUBLIC;
                    $private = USER_PRIVACY_PRIVATE;
                    $selected = USER_PRIVACY_SELECTED;
                    $community = USER_PRIVACY_COMMUNITY;
                    $privacy_where = '';

                    $where .= "CASE";
//                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1) THEN 1";
                    $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 LIMIT 1 ) THEN 1";
//                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.user_id = '$searcher_id' LIMIT 1 ) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.user_id = :Searcher_id LIMIT 1 ) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1) THEN 1";
                    
//                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$searcher_id' LIMIT 1) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.kind_type= :Private AND PR.user_id=:Searcher_id LIMIT 1) THEN 1";
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$searcher_id' , CONCAT( PR.users ) ) )  )  LIMIT 1 ) THEN 1";
                    
                    $where .= " ELSE 0 END ";
                    
                    $params[] = array( "key" => ":Public", "value" =>$public);
                    $params[] = array( "key" => ":Searcher_id", "value" =>$searcher_id);
                    $params[] = array( "key" => ":Private", "value" =>$private);
                }
            }else{
                $public = USER_PRIVACY_PUBLIC;
                if($where != '') $where .= ' AND ';
                $where .= "CASE";
//                    $where .= " WHEN J.is_public='$public' THEN 1";
                $where .= " WHEN J.is_public=:Public THEN 1";
//                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1) THEN 1";
                $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1) THEN 1";
                $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=J.id AND PR.entity_type=".SOCIAL_ENTITY_JOURNAL." AND PR.published=1 LIMIT 1 ) THEN 1";
                $where .= " ELSE 0 END ";
                    $params[] = array( "key" => ":Public", "value" =>$public);
            }

            //in case vid is set dont search by location
            if( !is_null($options['latitude']) && !is_null($options['longitude']) && !is_null($options['radius']) ){
                    $lat = doubleval($options['latitude']);
                    $long = doubleval($options['longitude']);
                    $radius = intval($options['radius']);

                    if($where != '') $where .= ' AND ';

                    if( $options['dist_alg'] == 's'){
                            //the 1 latitude degree ~= 110km, 1 degree longitude = 110km at equater, 78 at 45 degrees, 0 at pole
                            //for longitude at [33,35] square 0.1 => 14km, 0.01 => 1.4km, 0.001 => 140m so. good approx for a degree square is approx 140km
                            //use formula for longitude
                            $long_rad = deg2rad($long);
                            $c = 40075;

                            $lat_conv = doubleval(110000.0);
                            $long_conv = (1000 * $c * cos($long_rad))/360;

                            $diff_lat = $radius/$lat_conv;
                            $diff_long = $radius/$long_conv;

//                            $where .= " squareLocationDiff(latitude,longitude,$lat,$long,$diff_lat,$diff_long) ";
                            $where .= " squareLocationDiff(latitude,longitude,:Lat,Long,:Diff_lat,:Diff_long) ";
                            $params[] = array( "key" => ":Lat", "value" =>$lat);
                            $params[] = array( "key" => ":Long", "value" =>$long);
                            $params[] = array( "key" => ":Diff_lat", "value" =>$diff_lat);
                            $params[] = array( "key" => ":Diff_long", "value" =>$diff_long);
                    }else{
//                            $where .= " LocationDIff(latitude,longitude,$lat,$long) <= $radius ";
                            $where .= " LocationDIff(latitude,longitude,:Lat,:Long) <= :Radius ";
                            $params[] = array( "key" => ":Lat", "value" =>$lat);
                            $params[] = array( "key" => ":Long", "value" =>$long);
                            $params[] = array( "key" => ":Radius", "value" =>$radius);
                    }

            }

            $searched = array();

            $sub_query = '';
            if( !is_null($options['search_string']) ){
                    $search_strings = explode(' ',$options['search_string']);
                    $search_where = explode(',',$options['search_where']);

                    $sub_where = array();

                    foreach($search_strings as $in_search_string){

                            $search_string = trim(strtolower($in_search_string));
                            $search_string = preg_replace('/[^a-z0-9A-Z]/', '', $search_string);

                            if( strlen($search_string) <= 2) continue;

                            if( in_array($search_string,$searched) ) continue;

                            $searched[] = $search_string;

                            if(in_array('t',$search_where) || in_array('a',$search_where) ){
                                    $sub_where[] = " LOWER(journal_name) LIKE '%{$search_string}%' ";
                            }
                            if(in_array('d',$search_where) || in_array('a',$search_where) ){
                                    $sub_where[] = " LOWER(journal_desc) LIKE '%{$search_string}%' ";
                            }
                            if(in_array('l',$search_where) || in_array('a',$search_where) ){
                                    $sub_where[] = " LOWER(location_name) LIKE '%{$search_string}%' ";
                            }
                    }

                    if( count($sub_where) != 0 ){
                            if($where != '') $where .= ' AND ';
                            $sub_where = '(' . implode(' OR ', $sub_where) . ')';
                            $where .= $sub_where;
                    }
            }

            $orderby = $options['orderby'];
            if($orderby == 'rand'){
                    $orderby = "RAND()";
            }else{
                    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
            }

            if( $options['n_results'] == false ){
                    $query = "SELECT * FROM `cms_journals` AS J WHERE $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
                    $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
                    $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");

                    $select = $dbConn->prepare($query);
                    PDO_BIND_PARAM($select,$params);
                    $select->execute();

                    $ret    = $select->rowCount();
                    
                    $row = $select->fetchAll();
                    return $row;
            }else{
                    $query = "SELECT COUNT(J.id) FROM `cms_journals` AS J WHERE $where";

                    $select = $dbConn->prepare($query);
                    PDO_BIND_PARAM($select,$params);
                    $select->execute();
//                    $ret = db_query($query);
                    $row = $select->fetch();
                    $n_results = $row[0];
                    return $n_results;
            }
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <end>

	}
	
	/**
	 * gets the default pic for a journal
	 * @param integer $jid the cms_journals id
	 * @return false|array false if no no pic.
	 */
	function journalDefaultPic($jid){
//  Changed by Anthony Malak 23-04-2015 to PDO database
//  <start>
            global $dbConn;
            $params = array();  
//            $query = "SELECT * FROM cms_journals_items WHERE journal_id='$jid' ORDER BY default_pic DESC LIMIT 1";
//            $res = db_query($query);
//            if(!$res || (db_num_rows($res) == 0) ) return false;
//
//            $row = db_fetch_array($res);
//            return $row;
            $query  = "SELECT * FROM cms_journals_items WHERE journal_id=:Jid ORDER BY default_pic DESC LIMIT 1";
            $params[] = array( "key" => ":Jid", "value" =>$jid);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    =$select->execute();

            $ret    = $select->rowCount();
            if(!$res || ($ret == 0) ) return false;

            $row = $select->fetch();
            return $row;
	}
	/**
	 * builds a journal's pdf and images. requires tcpdf
	 * @param integer $jid which journal?
	 * @return boolean true|false if success fail
	 */
	function journalBuild($jid,$journalTemplate,$journalPdf,$journalFlash3d){
            global $dbConn;
            $params  = array();  
            $params2 = array();  
            $params3 = array();  
		
            global $CONFIG;
            $built_pdf_3d = JOURNAL_EMPTY;
            if($journalPdf == '1'){
                    if($journalFlash3d =='1'){
                            $built_pdf_3d = JOURNAL_3D_PDF;
                    }else{
                            $built_pdf_3d = JOURNAL_PDF;
                    }
            }else if($journalFlash3d =='1'){
                    $built_pdf_3d = JOURNAL_3D;
            }
            $query  = "SELECT * FROM cms_journals WHERE id=:Jid";
            $params[] = array( "key" => ":Jid", "value" =>$jid);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $ret    = $select->rowCount();
            if(!$res || ($ret == 0) ) return false;

            $journal_record = $select->fetch(PDO::FETCH_ASSOC);
            $query2  = "SELECT * FROM cms_journals_items WHERE journal_id=:Jid and published = 1 ORDER BY item_order ASC";
            $params2[] = array( "key" => ":Jid", "value" =>$jid);
            $select2 = $dbConn->prepare($query2);
            PDO_BIND_PARAM($select2,$params2);
            $res     = $select2->execute();

            $ret     = $select2->rowCount();
            if(!$res || ($ret == 0) ) return false;

            $defaul_record = journalDefaultPic($jid);

            //////////////////////////
            //all pages
            $i=0;
            $defaul_record_id = $i;
            $row = $select2->fetchAll(PDO::FETCH_ASSOC);
            foreach($row as $row_item){

                    $journal_pic = $row_item['item_pic'];
                    $vpath = $row_item['pic_vpath'];
                    $desc = htmlEntityDecode($row_item['name']);

                    $image_path = $CONFIG ['server']['root'] . $vpath . $journal_pic;

                    $resized_image_path = $CONFIG ['server']['root'] . $vpath . 'res_' . $journal_pic;
                    $resized_image_path_cover = $CONFIG ['server']['root'] . $vpath . 'res_cover_' . $journal_pic;
                    if($journalTemplate == '1'){
                            $width = 376;
                            $height = 566;
                    }else{
                            $width = 566;
                            $height = 377;
                    }
                    $coverWidth = 566;
                    $coverHeight = 377;
                    photoThumbnailCreate($image_path, $resized_image_path, $width, $height);

                    if( $defaul_record['id'] == $row_item['id'] ){
                            $defaul_record_id = $i;
                            photoThumbnailCreate($image_path, $resized_image_path_cover, $coverWidth, $coverHeight);
                            $items[] = array('image' => $resized_image_path , 'text' => $desc, 'cover' =>$resized_image_path_cover);
                    }else
                    {				
                            $items[] = array('image' => $resized_image_path , 'text' => $desc);
                    }
                    $i++;
            }
            //////////////////////////////

            $options = array('output_path' => journalGetPath($journal_record),
                                            'output_name' => $journal_record['journal_link'],
                                            'background' => 'media/images/journal_book_bg.jpg',
                                            'cover_page' => 'media/images/journal_cover_page.png',
                                            'default_item' => $defaul_record_id,
                                            'template' => $journalTemplate,
                                            'journalPdf' => $journalPdf,
                                            'journalFlash3d' => $journalFlash3d,
                                            'items' => $items);

            include('book.php');
            bookBuild($options);
            $query3 = "UPDATE cms_journals SET built=:Built_pdf_3d WHERE id=:Jid";
            $params3[] = array( "key" => ":Built_pdf_3d", "value" =>$built_pdf_3d);
            $params3[] = array( "key" => ":Jid", "value" =>$jid);
            $update = $dbConn->prepare($query3);
            PDO_BIND_PARAM($update,$params3);
            $update->execute();
            return true;
	}
		
	/**
	 * gets the path to the journal files
	 * @global type $CONFIG
	 * @param array $journal_record a cms_journals record to get its path
	 * @return string the path to the journal files 
	 */
	function journalGetPath($journal_record){
		global $CONFIG;
		return $CONFIG['journal']['outputPath'] . $journal_record['vpath'] . $journal_record['id'] . '/';
	}