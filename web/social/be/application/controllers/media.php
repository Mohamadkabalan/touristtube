<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends MY_Controller{
    public function index(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Manage media';
        $data['content']= 'media/index';
        $this->load->library('pagination');
        $this->load->model('album_media_model');
        $tubersAlbums = $this->album_media_model->tubers_albums();
        $data['albums'] = $tubersAlbums;
        $firstAlbum = $tubersAlbums[0];
        $ret = $this->album_media_model->album_media($firstAlbum->id);
        $data['album_media'] = $ret;
        $data['accepted'] = FALSE;
        $data['jsIncludes'] = array('media.js');
        $this->load->view('template', $data);
    }
    
    public function album_media(){
        $albumId = $this->input->post('albumId');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->album_media($albumId);
        $data['album_media'] = $ret;
        $data['accepted'] = FALSE;
        $this->load->view('media/album_media', $data);
    }
    
    public function accepted_album_media(){
        $albumId = $this->input->post('albumId');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->accepted_album_media($albumId);
        $data['album_media'] = $ret;
        $data['accepted'] = TRUE;
        $this->load->view('media/album_media', $data);
    }
    
    public function deleted_album_media(){
        $albumId = $this->input->post('albumId');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->deleted_album_media($albumId);
        $data['album_media'] = $ret;
        $data['accepted'] = TRUE;
        $this->load->view('media/album_media', $data);
    }
    
    public function accepted_tubers_media($start = 0){
        $this->load->library('pagination');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->accepted_tubers_media($start, 200);
        $data['media'] = $ret;
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'media/accepted_tubers_media';
        $config['total_rows'] = $this->album_media_model->accepted_tubers_media_count();
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['accepted'] = TRUE;
        $this->load->view('media/other_media', $data);
    }
    
    public function deleted_tubers_media($start = 0){
        $this->load->library('pagination');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->deleted_tubers_media($start, 200);
        $data['media'] = $ret;
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'media/deleted_tubers_media';
        $config['total_rows'] = $this->album_media_model->deleted_tubers_media_count();
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['accepted'] = TRUE;
        $this->load->view('media/other_media', $data);
    }
    
    public function accepted_channels_media($start = 0){
        $this->load->library('pagination');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->accepted_channels_media($start, 200);
        $data['media'] = $ret;
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'media/accepted_channels_media';
        $config['total_rows'] = $this->album_media_model->accepted_channels_media_count();
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['accepted'] = TRUE;
        $this->load->view('media/other_media', $data);
    }
    
    public function deleted_channels_media($start = 0){
        $this->load->library('pagination');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->deleted_channels_media($start, 200);
        $data['media'] = $ret;
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'media/deleted_channels_media';
        $config['total_rows'] = $this->album_media_model->deleted_channels_media_count();
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['accepted'] = TRUE;
        $this->load->view('media/other_media', $data);
    }
    
    public function tubers_media($start = 0){
        $this->load->library('pagination');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->tubers_media($start, 200);
        $data['media'] = $ret;
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'media/tubers_media';
        $config['total_rows'] = $this->album_media_model->tubers_media_count();
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['accepted'] = FALSE;
        $this->load->view('media/other_media', $data);
    }
    
    public function channels_media($start = 0){
        $this->load->library('pagination');
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->channels_media($start, 200);
        $data['media'] = $ret;
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'media/channels_media';
        $config['total_rows'] = $this->album_media_model->channels_media_count();
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['accepted'] = FALSE;
        $this->load->view('media/other_media', $data);
    }
    
    public function tubers_albums(){
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->tubers_albums();
        $data['albums'] = $ret;
        $this->load->view('media/albums', $data);
    }
    
    public function channels_albums(){
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->channels_albums();
        $data['albums'] = $ret;
        $this->load->view('media/albums', $data);
    }
    
    function ajax_delete($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Media_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $d->published = -2;
            $success = $d->save();
            $this->load->model('activitylog_model');
            $this->activitylog_model->insert_log($userdata['uid'], MEDIA_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
    
    function ajax_accept($id){
        $success = FALSE;
        if($id <> ''){
            $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
            $userId = $u_res->id;
            $m = new Media_Model();
            $m->where('id', $id)->get();
            $m->userid = $userId;
            $success = $m->save();
            $this->load->model('album_media_model');
            $tubersAlbums = $this->album_media_model->change_owner($id, $userId);
        }
        echo json_encode (array('success' => $success));
    }
    
    function michel_accept($id){
        $success = FALSE;
        if($id <> ''){
            $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', 'msfeir')
              ->get()
              ->row();
            $userId = $u_res->id;
            $m = new Media_Model();
            $m->where('id', $id)->get();
            $m->userid = $userId;
            $success = $m->save();
        }
        echo json_encode (array('success' => $success));
    }
    
    function albums_list(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Manage albums';
        $data['content']= 'media/manage_albums';
        $this->load->model('album_media_model');
        $tubersAlbums = $this->album_media_model->tubers_albums();
        $data['albums'] = $tubersAlbums;
        $data['accepted'] = FALSE;
        $data['jsIncludes'] = array('albums.js');
        $this->load->view('template', $data);
    }
    
    function tubers_albums_list(){
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->tubers_albums();
        $data['albums'] = $ret;
        $data['accepted'] = FALSE;
        $this->load->view('media/albums_list', $data);
    }
    
    function accepted_tubers_albums_list(){
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->accepted_tubers_albums();
        $data['albums'] = $ret;
        $data['accepted'] = TRUE;
        $this->load->view('media/albums_list', $data);
    }
    
    function channels_albums_list(){
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->channels_albums();
        $data['albums'] = $ret;
        $data['accepted'] = FALSE;
        $this->load->view('media/albums_list', $data);
    }
    
    function accepted_channels_albums_list(){
        $this->load->model('album_media_model');
        $ret = $this->album_media_model->accepted_channels_albums();
        $data['albums'] = $ret;
        $data['accepted'] = TRUE;
        $this->load->view('media/albums_list', $data);
    }
    
    function album_accept($id){
        $success = FALSE;
        if($id <> ''){
            $u_res = $this->db->select()
              ->from('cms_users')
              ->where('YourUserName', INDIA_USER)
              ->get()
              ->row();
            $userId = $u_res->id;
            $m = new Users_Catalogs_Model();
            $m->where('id', $id)->get();
            $m->user_id = $userId;
            $success = $m->save();
        }
        echo json_encode (array('success' => $success));
    }
    
    function album_delete($id){
        $userdata = $this->session->userdata('logged_in');
        $success = FALSE;
        if($id <>'') {
            $d = new Users_Catalogs_Model();
            $d->where('id', $id )->get();
            $d->published = -2;
            $success = $d->save();
            $this->load->model('activitylog_model');
            $this->activitylog_model->insert_log($userdata['uid'], ALBUM_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
}