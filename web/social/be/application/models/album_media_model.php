<?php

class Album_Media_Model extends CI_Model{
    public function change_owner($entity_id, $user_id){
        $privacy = array('user_id' => $user_id);
        $newsfeed = array('owner_id' => $user_id, 'user_id' => $user_id);
        $this->db->where('entity_id', $entity_id);
        $this->db->where('entity_type', 1);
        $this->db->update('cms_users_privacy_extand', $privacy);
        $this->db->where('entity_id', $entity_id);
        $this->db->where('entity_type', 1);
        $this->db->update('cms_social_newsfeed', $newsfeed);
    }
    public function tubers_media($start, $limit){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published = 1 AND userid in (319, 321) AND cms_videos.channelid = 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid <> $userId")
                ->order_by('title', 'asc')
                ->limit($limit, $start)
                ->get()
                ->result();
        return $res;
    }
    
    public function tubers_media_count(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published =1 AND userid in (319, 321) AND cms_videos.channelid = 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid <> $userId")
                ->count_all_results();
        return $res;
    }
    
    public function accepted_tubers_media($start, $limit){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published <> -2 AND cms_videos.channelid = 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid = $userId")
                ->limit($limit, $start)
                ->get()
                ->result();
        return $res;
    }
    
    public function accepted_tubers_media_count(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published <> -2 AND cms_videos.channelid = 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid = $userId")
                ->count_all_results();
        return $res;
    }
    
    public function deleted_tubers_media($start, $limit){
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published = -2 AND cms_videos.channelid = 0 AND cms_videos_catalogs.video_id IS NULL")
                ->limit($limit, $start)
                ->get()
                ->result();
        return $res;
    }
    
    public function deleted_tubers_media_count(){
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published = -2 AND cms_videos.channelid = 0 AND cms_videos_catalogs.video_id IS NULL")
                ->count_all_results();
        return $res;
    }
    
    public function channels_media($start, $limit){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published <> -2 AND cms_videos.channelid <> 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid <> $userId")
                ->limit($limit, $start)
                ->get()
                ->result();
        return $res;
    }
    
    public function channels_media_count(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published <> -2 AND cms_videos.channelid <> 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid <> $userId")
                ->count_all_results();
        return $res;
    }
    
    public function accepted_channels_media($start, $limit){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published <> -2 AND cms_videos.channelid <> 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid = $userId")
                ->limit($limit, $start)
                ->get()
                ->result();
        return $res;
    }
    
    public function accepted_channels_media_count(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published <> -2 AND cms_videos.channelid <> 0 AND cms_videos_catalogs.video_id IS NULL AND cms_videos.userid = $userId")
                ->count_all_results();
        return $res;
    }
    
    public function deleted_channels_media($start, $limit){
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published = -2 AND cms_videos.channelid <> 0 AND cms_videos_catalogs.video_id IS NULL")
                ->limit($limit, $start)
                ->get()
                ->result();
        return $res;
    }
    
    public function deleted_channels_media_count(){
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'left')
                ->where("cms_videos.published = -2 AND cms_videos.channelid <> 0 AND cms_videos_catalogs.video_id IS NULL")
                ->count_all_results();
        return $res;
    }
    
    public function accepted_album_media($album_id){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'inner')
                ->where("cms_videos.published <> -2 AND cms_videos_catalogs.catalog_id = $album_id AND cms_videos.userid = $userId")
                ->get()
                ->result();
        return $res;
    }
    
    public function deleted_album_media($album_id){
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'inner')
                ->where("cms_videos.published = -2 AND cms_videos_catalogs.catalog_id = $album_id")
                ->get()
                ->result();
        return $res;
    }
    
    public function album_media($album_id){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('cms_videos.*')
                ->from('cms_videos')
                ->join('cms_videos_catalogs', 'cms_videos.id = cms_videos_catalogs.video_id', 'inner')
                ->where("cms_videos.published <> -2 AND cms_videos_catalogs.catalog_id = $album_id AND cms_videos.userid <> $userId")
                ->get()
                ->result();
        return $res;
    }
    
    public function tubers_albums(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('id, catalog_name')
                ->from('cms_users_catalogs')
                ->where("cms_users_catalogs.published <> -2 AND channelid = 0 AND user_id <> $userId")
                ->get()
                ->result();
        return $res;
    }
    
    public function channels_albums(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('id, catalog_name')
                ->from('cms_users_catalogs')
                ->where("cms_users_catalogs.published <> -2 AND channelid <> 0 AND user_id <> $userId")
                ->get()
                ->result();
        return $res;
    }
    
    public function accepted_tubers_albums(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('id, catalog_name')
                ->from('cms_users_catalogs')
                ->where("cms_users_catalogs.published <> -2 AND channelid = 0 AND user_id = $userId")
                ->get()
                ->result();
        return $res;
    }
    
    public function accepted_channels_albums(){
        $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
        $userId = $u_res->id;
        $res = $this->db->select('id, catalog_name')
                ->from('cms_users_catalogs')
                ->where("cms_users_catalogs.published <> -2 AND channelid <> 0 AND user_id = $userId")
                ->get()
                ->result();
        return $res;
    }
}