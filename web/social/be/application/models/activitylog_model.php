<?php
class ActivityLog_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function All($startDate, $endDate){
        $ret = array();
        $i = 0;
        $where_date = "(date(timestamp) between '".$startDate."' and '".$endDate."')";
        $res = $this->db->query("
        SELECT u.username as username,u.id as userid, 
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".USER_LOGIN."' and $where_date) as logins,
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".USER_LOGOUT."' and $where_date) as logouts,
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".HOTEL_INSERT."' and $where_date) as hotel_insert_count,   
            (select count(id) from bo_activity_log where user_id = u.id and ( activity_code = '".HOTEL_UPDATE."' or (activity_code between ".HOTEL_ROOM_INSERT." and ".HOTEL_IMAGE_DELETE.")) and $where_date) as hotel_update_count,
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".HOTEL_DELETE."' and $where_date) as hotel_delete_count,
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".RESTAURANT_INSERT."' and $where_date) as restaurant_insert_count,
            (select count(id) from bo_activity_log where user_id = u.id and ( activity_code = '".RESTAURANT_UPDATE."' or (activity_code between ".RESTAURANT_REVIEW_INSERT." and ".RESTAURANT_IMAGE_DELETE.")) and $where_date) as restaurant_update_count,
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".RESTAURANT_DELETE."' and $where_date) as restaurant_delete_count,
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".POI_INSERT."' and $where_date) as poi_insert_count,
            (select count(id) from bo_activity_log where user_id = u.id and ( activity_code = '".POI_UPDATE."' or (activity_code between ".POI_REVIEW_INSERT." and ".POI_IMAGE_DELETE.")) and $where_date) as poi_update_count,
            (select count(id) from bo_activity_log where user_id = u.id and activity_code = '".POI_DELETE."' and $where_date) as poi_delete_count
        FROM `bo_users` as u
        GROUP BY u.username
                ");
        return $res->result();
    }
    
    public function UserHasActivities($user_id){
        $res = $this->db->query("select count(id) as cnt from bo_activity_log where user_id = $user_id and activity_code <> '".USER_LOGOUT."' and activity_code <> '".USER_LOGIN."'");
        $ret = $res->result();
        if($ret[0]->cnt == 0)
            return false;
        return true;
    }
    
    public function Details($user_id, $date){
        
    }
    
    public function insert_log($user_id, $activity_code, $entity_id){
        $date = date('Y/m/d H:i:s');
        $data = array(
            'user_id' => $user_id,
            'activity_code' => $activity_code,
            'entity_id' => $entity_id,
            'timestamp' => $date
        );
        $this->db->insert('bo_activity_log', $data);
        return $this->db->insert_id();
    }
	
	
	/*
	function name:userActivityDetail
	purpose: to get user activities with in a date range
	params: user_id, activity type, start date	, end date
	Author: Pradeep
	Modified: 26 Aug 2014
	*/
	 
	
	public function userActivityDetail($user_id, $activity_type, $startDate, $endDate, $hash_id, $cmsvideo_id){
	
            $where_date = "(date(bal.timestamp) between '".$startDate."' and '".$endDate."')";
            $whr_activitytype = '';
            if(!empty($activity_type)) {
                $whr_activitytype .= " AND bal.activity_code='".$activity_type."'";
            }
//          Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
//          <start>
            $whr_hash_id = '';
            $inner_join  = '';
            if(!empty($hash_id)) {
                $inner_join  .= "INNER JOIN cms_videos AS vid ON bal.entity_id = vid.id";
                $whr_hash_id .= " AND vid.hash_id = '".$hash_id."'";
            }
//          Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
//          <end>

			// code added by Sushma Mishra on 26-08-2015 to add the filter by the cms video id starts from here
            $whr_cmsvideo_id = '';
			if(empty($hash_id)){
				$inner_join  = '';			
			}           
            if(!empty($cmsvideo_id)) {
				if(empty($hash_id)){
					$inner_join  .= "INNER JOIN cms_videos AS vid ON bal.entity_id = vid.id";
				}
                $whr_cmsvideo_id .= " AND vid.id = '".$cmsvideo_id."'";
            }
			// code added by Sushma Mishra on 26-08-2015 to add the filter by the cms video id ends here


//          also fixed the select to check on the hash Id added by the user
           $query = "SELECT bu.username, bal.activity_code,bal.entity_id, max(bal.timestamp) as timestamp FROM bo_users AS bu
                        INNER JOIN bo_activity_log AS bal ON bu.id = bal.user_id
                        $inner_join
                        AND bal.user_id = '".$user_id."' AND $where_date $whr_activitytype $whr_hash_id $whr_cmsvideo_id  
                        group by bu.username, bal.activity_code,bal.entity_id order by bal.timestamp ASC";
            $res = $this->db->query($query);
            return $res->result();			
	}
        
        
	/*
	function name:getActivityList
	purpose: to get list of all activities 
	params: NA
	Author: Pradeep
	Modified: 26 Aug 2014
	*/
	
	
	public function getActivityList(){
		$query ="select code, description from bo_activity_type";   
		$res = $this->db->query($query);
		return $res->result();
	}
	
}