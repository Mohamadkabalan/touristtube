<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo extends MY_Controller {
    
    public function alias(){
        $this->load->model('alias_seo_model');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Alias SEO';
        $data['content']= 'seo/alias';
        $config['uri_segment'] = 3;
        $config["num_links"] = 14;
        $config['base_url'] = 'seo/ajax_alias';
        $a = new Alias_Seo_Model();
        $total = $a->count();
        $res = $a->order_by('id')->get();
        $config['total_rows'] = $total;
        $data['aitems']= $a;
        $data['jsIncludes'] = array('seo.js');
        $this->load->view('template', $data);
    }
	
    public function ajax_alias($start = 0){
        $this->load->model('alias_seo_model');
        $config['uri_segment'] = 3;
        $config["num_links"] = 14;
        $config['base_url'] = 'seo/ajax_alias';
        $a = new Alias_Seo_Model();
        $title = $this->input->post('ti');
        $url = $this->input->post('ur');
        $total = $a->like('title', $title)->like('url',$url)->count();
        $config['total_rows'] = $total;
        $limit = $total;
        $res = $a->like('title', $title)->like('url',$url)->order_by('id')->get();
        $data['aitems']= $a;
        $this->load->view('seo/ajax_alias', $data);
    }
	
    public function alias_edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit SEO Alias';
        $data['content']= 'seo/alias_form';
        $r = new Alias_Seo_Model();
        $r->where('id', $id)->get();
        $data['item']= $r->to_array();
        
        $ml_in = new Ml_Alias_Seo_Model();
        $ml_in->where('parent_id', $id)->where('language', 'in')->get();
        $data['item_in'] = $ml_in->to_array();
        
        $ml_fr = new Ml_Alias_Seo_Model();
        $ml_fr->where('parent_id', $id)->where('language', 'fr')->get();
        $data['item_fr'] = $ml_fr->to_array();
        
        $this->load->view('template', $data);
    }
    
    public function alias_add(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add SEO Alias';
        $data['content']= 'seo/alias_form';
        $this->load->view('template', $data);
    }
	
    public function alias_submit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        if($this->input->post('submit_in')){
            $id_in = intval($this->input->post('id_in'));
            $parent_id = intval($this->input->post('parent_id'));
            $title_in = $this->input->post('title_in');
            $description_in = $this->input->post('description_in');
            $keywords_in = $this->input->post('keywords_in');
            $ml_as = new Ml_Alias_Seo_Model();
            if($id_in <> 0){
                $ml_as->where('id', $id_in)->get();
            }
            $ml_as->parent_id = $parent_id;
            $ml_as->language = 'in';
            $ml_as->title = $title_in;
            $ml_as->description = $description_in;
            $ml_as->keywords = $keywords_in;
            $ml_as->save();
            $this->load->model('activitylog_model');
            $this->activitylog_model->insert_log($userdata['uid'], SEO_ALIAS_TRANSLATION, $parent_id);
            redirect('seo/alias_edit/'.$parent_id, 'refresh');
            return;
        }
        else if($this->input->post('submit_fr')){
            $id_fr = intval($this->input->post('id_fr'));
            $parent_id = intval($this->input->post('parent_id'));
            $title_fr = $this->input->post('title_fr');
            $description_fr = $this->input->post('description_fr');
            $keywords_fr = $this->input->post('keywords_fr');
            $ml_as = new Ml_Alias_Seo_Model();
            if($id_fr <> 0){
                $ml_as->where('id', $id_fr)->get();
            }
            $ml_as->parent_id = $parent_id;
            $ml_as->language = 'fr';
            $ml_as->title = $title_fr;
            $ml_as->description = $description_fr;
            $ml_as->keywords = $keywords_fr;
            $ml_as->save();
            $this->load->model('activitylog_model');
            $this->activitylog_model->insert_log($userdata['uid'], SEO_ALIAS_TRANSLATION, $parent_id);
            redirect('seo/alias_edit/'.$parent_id, 'refresh');
            return;
        }
        else{
            $h = new Alias_Seo_Model();
            if($id <> '' ){
                $h->where('id', $id)->get();
            }
            $h->title = $this->input->post('title');       
            $h->description = $this->input->post('description');
            $h->keywords = $this->input->post('keywords');        
            $url = $this->input->post('url');
            $explode = explode('.com/', $url);
            if(sizeof($explode) > 1){
                $url = $explode[1];
            }else{
                $explode = explode('.net/', $url);
                if(sizeof($explode) > 1){
                    $url = $explode[1];
                }
            }
            $h->url = $url;              
            $h->save();
            $new = $id == '';
            if($id == ''){
                $id = $h->id;
            }
            $this->load->model('activitylog_model');
            $this->activitylog_model->insert_log($userdata['uid'], $new ? SEO_ALIAS_INSERT : SEO_ALIAS_UPDATE , $h->id);
        }
        redirect('seo/alias_edit/'.$id, 'refresh');
    }
	
    public function ajaxalias_delete($id){
        $userdata = $this->session->userdata('logged_in');
        $d = new Alias_Seo_Model();
        $success = FALSE;
        if($id <>'') {
            $d->where('id', $id )->get();
            $success = $d->delete();
            if($success){
                $this->load->model('activitylog_model');
                $this->activitylog_model->insert_log($userdata['uid'], SEO_ALIAS_DELETE, $id);
            }
        }
        echo json_encode (array('success' => $success));
     }
	 
    public function whereis(){
        $this->load->model('entity_description_model');
//        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Where Is';
        $data['content']= 'seo/whereis';
//        $config['uri_segment'] = 3;
//        $config['per_page'] = 20;
//        $config["num_links"] = 14;
//        $config['base_url'] = 'seo/ajax_whereis';
//        $total = $this->entity_description_model->whereis_total();
//        $config['total_rows'] = $total;
        $r = $this->entity_description_model->whereis(0, 20);
        $data['items']= $r;
//        $this->pagination->initialize($config);
//        $data['links'] = $this->pagination->create_links();
//        $data['jsIncludes'] = array('poi.js');
        $this->load->view('template', $data);
    }
    
    public function ajax_whereis($start = 0){
        $this->load->model('entity_description_model');
        $this->load->library('pagination');
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'seo/ajax_whereis';
        $total = $this->entity_description_model->whereis_total();
        $config['total_rows'] = $total;
        $r = $this->entity_description_model->whereis($start, 20);
        $data['items']= $r;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('seo/ajax_whereis', $data);
    }
    
    public function whereis_edit($id){
        $this->load->model('entity_description_model');
        $this->load->model('ml_entity_description_model');
        $this->load->model('cms_countries_model');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit Where Is';
        $data['content']= 'seo/whereis_form';
        $item = $this->entity_description_model->getbyid($id);
        $item_fr = $this->ml_entity_description_model->getbyparentidandlang($id,fr); 
        $item_hi = $this->ml_entity_description_model->getbyparentidandlang($id,hi); 
        if($item['entity_type'] == SOCIAL_ENTITY_COUNTRY){
            $country = $this->cms_countries_model->getbyid($item['entity_id']);
            $item['entity_id'] = $country['code'];
        }
        $data['item']= $item;
        $data['item_fr']= $item_fr;
        $data['item_hi']= $item_hi;
        $data['jsIncludes'] = array('whereis.js');
        $this->load->view('template', $data);
    }
    
    public function whereis_add(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add Where Is';
        $data['content']= 'seo/whereis_form';
        $data['jsIncludes'] = array('whereis.js');
        $this->load->view('template', $data);
    }
    
    public function whereis_submit(){
        $this->load->model('entity_description_model');
        $this->load->model('ml_entity_description_model');
        $this->load->model('cms_countries_model');
        $userdata = $this->session->userdata('logged_in');
        $id = intval($this->input->post('id'));
        $main_id = $id;
       // $language = $this->input->post('lang');
        if($this->input->post('submit_hi')){  
            $id_hi = intval($this->input->post('id_hi'));
            $parent_id = $this->input->post('parent_id');
            $main_id = $parent_id;
            $language = $this->input->post('lang');
            $title_hi = $this->input->post('title_hi');
            $description_hi = $this->input->post('description_hi');
            if($id_hi <> '' ){
                $this->ml_entity_description_model->update($id_hi, $parent_id, $language, $description_hi, $title_hi);
            }else{
                $this->ml_entity_description_model->insert($parent_id, $language, $description_hi, $title_hi);
            }
            
        }else if($this->input->post('submit_fr')){
            $id_fr = intval($this->input->post('id_fr'));
            $parent_id = $this->input->post('parent_id');
            $main_id = $parent_id;
            $language = $this->input->post('lang');
            $title_fr = $this->input->post('title_fr');
            $description_fr = $this->input->post('description_fr');
            if($id_fr <> '' ){
                $this->ml_entity_description_model->update($id_fr, $parent_id, $language, $description_fr, $title_fr);
            }else{
                $this->ml_entity_description_model->insert($parent_id, $language, $description_fr, $title_fr);
            }
            
        }
        else{
        
            $title = $this->input->post('title');
            $link = $this->input->post('link');
            $entity_type = $this->input->post('entity_type');       
            $countrycode = $this->input->post('country');
            $state = $this->input->post('state');
            $city_id = $this->input->post('city_id');
            $description = $this->input->post('description');

            switch($entity_type){
                case SOCIAL_ENTITY_CITY:
                    $entity_id = $city_id;
                    break;
                case SOCIAL_ENTITY_COUNTRY:
                    $country = $this->cms_countries_model->getbycode($countrycode);
                    $entity_id = $country['country_id'];
                    break;
                case SOCIAL_ENTITY_STATE:
                    $entity_id = $state;
                    break;
            }

            if($id <> '' ){
                $this->entity_description_model->update($id, $entity_type, $entity_id, $description, $title, $link);
            }else{
                $main_id = $this->entity_description_model->insert($entity_type, $entity_id, $description, $title, $link);
            }
        }
        
        redirect('seo/whereis_edit/'.$main_id, 'refresh');
    }
    
     public function ajax_whereis_add_check($entity_type, $entity_id){
        $this->load->model('entity_description_model'); 
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add Where Is';
        $data['content']= 'seo/whereis_form';
        $data['jsIncludes'] = array('whereis.js');
        $entity_type = $this->input->get('entity_type', TRUE);
        $entity_id = $this->input->get('entity_id', TRUE);
        $sucess = $this->entity_description_model->check_entitytype_entityid_exist($entity_type, $entity_id);
        //print_r($sucess[0]);
        //$this->load->view('template', $data);
        echo json_encode($sucess[0]);
    }
    
    public function delete_whereis($id){
        $userdata = $this->session->userdata('logged_in');
        $this->load->model('entity_description_model');
        $res = $this->entity_description_model->delete($id);
        if($res){
            $success = true;
        }else{
            $success = false;
        }
        echo json_encode (array('success' => $success));
    }
    
    public function homepage(){
        
    }
    
    public function discover(){
        
    }
}