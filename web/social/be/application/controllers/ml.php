<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ML extends MY_Controller {
    
    public function index(){
        $session_data = $this->session->userdata('logged_in');
        $this->load->library('pagination');
        $data['username'] = $session_data['username'];
        $data['title'] = 'Media - Translation';
        $data['content'] = 'ml/list';
		//code added by sushma mishra on 28-08-2015 to load the cms users model starts from here
		$this->load->model('cms_users_model');
		// code added by sushma mishra on 28-08-2015 to load the cms users model ends here
        $m = new Media_Model();
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'ml/ajax_list';
        if($session_data['role'] == 'user_translator'){
            $total = $m->where('userid', $session_data['ttuid'])->count();
        }
		//condition added by sushma mishra on 28-08-2015 to get the count for user translator admin type user starts from here
		elseif($session_data['role'] == 'user_translator_admin'){
			$result_ids = $this->cms_users_model->get_users_id();
			$result = array();
			foreach ($result_ids as $key => $value) {
				$result[] = $value->id;
			}
			$total = $m->where_in('userid', $result)->count();
		}
		// code added by sushma mishra on 28-08-2015 ends here
        else{
            $total = $m->count();
        }
        $config['total_rows'] = $total;
        if($session_data['role'] == 'user_translator'){
            $m->where('userid', $session_data['ttuid'])->order_by('id')->get(200);
        }
		//condition added by sushma mishra on 28-08-2015 to add the where clause for user translator admin type user starts from here
		elseif($session_data['role'] == 'user_translator_admin'){
			$m->where_in('userid', $result)->order_by('id')->get(200);
		}
		// code added by sushma mishra on 28-08-2015 ends here
        else{
            $m->order_by('DATE(last_modified) desc, userid asc')->get(200);
        }		
        $data['media']= $m;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['jsIncludes'] = array('ml.js');
        $this->load->view('template', $data);
    }
    
    public function ajax_list($start = 0){
        $session_data = $this->session->userdata('logged_in');
        $this->load->library('pagination');
        $cityName = $this->input->post('ci');
        $name = $this->input->post('m');
        $countryCode = $this->input->post('cc');
		$vid = $this->input->post('vid');
        $hashid = trim($this->input->post('hid'));
        $m = new Media_Model();
        $config['uri_segment'] = 3;
        $config['per_page'] = 200;
        $config["num_links"] = 14;
        $config['base_url'] = 'ml/ajax_list';
		//code added by sushma mishra on 28-08-2015 to load the cms users model starts from here
		$this->load->model('cms_users_model');
		//code added by suhsma mishra on 28-08-2015 to load the cms users model ends here
        if($session_data['role'] == 'user_translator'){
            if(!empty($hashid)){
                $total = $m->where('userid', $session_data['ttuid'])->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->where('hash_id', $hashid)->count();
            }
            else{
                $total = $m->where('userid', $session_data['ttuid'])->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->count();
            }
        }
        //condition added by sushma mishra on 28-08-2015 to get the count for user translator admin type user starts from here
        elseif($session_data['role'] == 'user_translator_admin'){
            $result_ids = $this->cms_users_model->get_users_id();
            $result = array();
            foreach ($result_ids as $key => $value) {
                    $result[] = $value->id;
            }		
            if(!empty($hashid)){
                $total = $m->where_in('userid', $result)->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->where('hash_id', $hashid)->count();
            }
            else{
                $total = $m->where_in('userid', $result)->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->count();
            }
        }
		// code added by sushma mishra on 28-08-2015 ends here
        else{
            if(!empty($hashid)){
                $total = $m->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->where('hash_id', $hashid)->count();
            }
            else{
                $total = $m->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->count();
            }
        }
        $config['total_rows'] = $total;
        if($session_data['role'] == 'user_translator'){
            if(!empty($hashid)){
                $m->where('userid', $session_data['ttuid'])->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->where('hash_id', $hashid)->order_by('id')->get(200, $start);
            }
            else{
                $m->where('userid', $session_data['ttuid'])->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->order_by('id')->get(200, $start);
            }
        }
		//condition added by sushma mishra on 28-08-2015 to add the where clause for user translator admin type user
		elseif($session_data['role'] == 'user_translator_admin'){
                    if(!empty($hashid)){
                        $m->where_in('userid', $result)->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->where('hash_id', $hashid)->order_by('id')->get(200, $start);			
                    }
                    else{
                        $m->where_in('userid', $result)->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->order_by('id')->get(200, $start);			
                    }
        }
		// code added by sushma mishra on 28-08-2015 ends here
        else{
            if(!empty($hashid)){
                $m->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->where('hash_id', $hashid)->order_by('id')->get(200, $start);
            }
            else{
                $m->like('lower(title)', strtolower($name))->like('lower(cityname)', strtolower($cityName))->like('lower(country)', strtolower($countryCode))->like('id', $vid, 'after')->order_by('id')->get(200, $start);
            }
        }
        $data['media']= $m;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('ml/ajax_list', $data);
    }
    
    public function edit($id, $page_lang = 'en'){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Media translation';
        $data['content']= 'ml/form';
        $m = new Media_Model();
        $m->where('id', $id)->get();
        $res = $m->to_array();
        if($session_data['role'] == 'user_translator' && $res['userid'] <> $session_data['ttuid']){
            redirect('ml', 'refresh');
        }
        else{
            $ml_all = $m->ml_videos->all_to_array();
            $languages = array('en', 'hi', 'fr', 'zh', 'es', 'pt', 'it', 'de', 'tl');
            $ml_result = array();
            $ml_result['source'] = array(
                'title' => $res['title'],
                'description' => $res['description'],
                'placetakenat' => $res['placetakenat'],
                'keywords' => $res['keywords'],
                'type' => $res['image_video'],
                'name' => $res['name'],
                'id' => $res['id']
            );
            if(isset($res['hash_id'])){
                $ml_result['source']['hash_id'] = $res['hash_id'];
            }
            foreach($languages as $lang){
                $ml_result[$lang] = array(
                    'title' => '',
                    'description' => '',
                    'placetakenat' => '',
                    'keywords' => ''
                );
            }
            foreach($ml_all as $item){
                $ml_result[$item['lang_code']]['title'] = $item['title'];
                $ml_result[$item['lang_code']]['description'] = $item['description'];
                $ml_result[$item['lang_code']]['placetakenat'] = $item['placetakenat'];
                $ml_result[$item['lang_code']]['keywords'] = $item['keywords'];
            }
            $data['result'] = $ml_result;
            $data['main'] = $res;
            $data['cssIncludes'] = array('video_trans.css');
            $data['lang'] = $page_lang;
            $this->load->view('template', $data);
        }
    }
    
    public function submit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $m = new Media_Model();
        $m->where('id', $id)->get();
        $r = $m->to_array();
        if($userdata['role'] == 'user_translator' && $r['userid'] <> $userdata['ttuid']){
            redirect('ml', 'refresh');
        }
        else{
            $lang = $this->input->post('lang');
            $title = $this->input->post($lang.'_title');
            $description = $this->input->post($lang.'_description');
            $placetakenat = $this->input->post($lang.'_placetakenat');
            $keywords = $this->input->post($lang.'_keywords');
            $ml = new Ml_Videos_Model();
            $ml->where('video_id', $id)->where('lang_code', $lang)->get();
            $res = $ml->to_array();
            if(!isset($res['id'])){
                $ml = new Ml_Videos_Model();
                $ml->video_id = $id;
                $ml->lang_code = $lang;
            }
            $ml->title = $title;
            $ml->description = $description;
            $ml->placetakenat = $placetakenat;
            $ml->keywords = $keywords;
            $ml->save();
            $this->load->model('activitylog_model');
            switch ($lang){
                case 'en':
                    $activity_code = Media_Translate_English;
                    break;
                case 'hi':
                    $activity_code = Media_Translate_Hindi;
                    break;
                case 'fr':
                    $activity_code = Media_Translate_French;
                    break;
                case 'zh':
                    $activity_code = Media_Translate_Chinese;
                    break;
                case 'es':
                    $activity_code = Media_Translate_Spanish;
                    break;
                case 'pt':
                    $activity_code = Media_Translate_Portuguese;
                    break;
                case 'it':
                    $activity_code = Media_Translate_Italian;
                    break;
                case 'de':
                    $activity_code = Media_Translate_Deutsch;
                    break;
                case 'tl':
                    $activity_code = Media_Translate_Filipino;
                    break;
            }
            $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $id);
            redirect('ml/edit/'.$id.'/'.$lang, 'refresh');
        }
    }
    /*
     * Mukesh
     * Show Media Categories
     * Date:4 Dec 2014
     */            
    public function mediaCategory(){
        $userdata = $this->session->userdata('logged_in');
        $data['title']= 'Manage Media Category';
        $c = new Cms_Allcategories_Model();
        $data['categories']= $c->get()->all_to_array();
        $data['content']= 'media/media_category';
        $data['jsIncludes'] = array('mediaCategory.js');
        $this->load->view('template', $data);
    }
    
    function ajax_mediaCategory_save(){
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $c = new Cms_Allcategories_Model();
      $c->where('id', $id[1])->get();
      $c->title=$value;
      $success = $c->save();
      echo json_encode(array('success' => $success));
   }
   
    function ajax_ml_mediaCategory_save(){
      $this->load->model('ml_allcategories_model');
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $entity_id = $id[1];
      $lang_code = $id[0];
      $success = $this->ml_allcategories_model->update_ml_allcategories($entity_id,$lang_code,$value);
      echo json_encode(array('success' => $success));
    }
     /*
     * Mukesh
     * Show Channel Categories
     * Date:4 Dec 2014
     */             
    public function channelCategory(){
        $userdata = $this->session->userdata('logged_in');
        $data['title']= 'Manage Channel Category';
        $c = new Cms_Channel_Category_Model();
        $data['channel_categories']= $c->get()->all_to_array();
        $data['content']= 'media/channel_category';
        $data['jsIncludes'] = array('channelCategory.js');
        $this->load->view('template', $data);
    }
    
    function ajax_channelCategory_save(){
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $c = new Cms_Channel_Category_Model();
      $c->where('id', $id[1])->get();
      $c->title=$value;
      $success = $c->save();
      echo json_encode(array('success' => $success));
   }
   
    function ajax_ml_channelCategory_save(){
      $this->load->model('ml_channel_category_model');
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $entity_id = $id[1];
      $lang_code = $id[0];
      $success = $this->ml_channel_category_model->update_ml_channel_category($entity_id,$lang_code,$value);
      echo json_encode(array('success' => $success));
    }
    /*
     * Mukesh
     * Show Report Reaon
     * Date:5 Dec 2014
     */ 
    public function reportReason(){
        $userdata = $this->session->userdata('logged_in');
        $data['title']= 'Manage Report Reason';
        $c = new Cms_Report_Reason_Model();
        $data['reason']= $c->get()->all_to_array();
        //echo "<pre>";print_r($data['reason']);
        $data['content']= 'media/report_reason';
        $data['jsIncludes'] = array('reportReason.js');
        $this->load->view('template', $data);
    }
    
    function ajax_reportReason_save(){
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $c = new Cms_Report_Reason_Model();
      $c->where('id', $id[1])->get();
      $c->reason=$value;
      $success = $c->save();
      echo json_encode(array('success' => $success));
   }
   
    function ajax_ml_reportReason_save(){
      $this->load->model('ml_report_reason_model');
      $id = explode('_',$this->input->post('id'));
      $value = $this->input->post('title');
      $entity_id = $id[1];
      $lang_code = $id[0];
      $success = $this->ml_report_reason_model->update_ml_report_reason($entity_id,$lang_code,$value);
      echo json_encode(array('success' => $success));
    }
    
//    public function placesToStay(){
//        $session_data = $this->session->userdata('logged_in');
//        $this->load->library('pagination');
//        $data['username'] = $session_data['username'];
//        $data['title'] = 'Media - Translation';
//        $data['content'] = 'ml/list';
//		//code added by sushma mishra on 28-08-2015 to load the cms users model starts from here
//		$this->load->model('cms_users_model');
//		// code added by sushma mishra on 28-08-2015 to load the cms users model ends here
//        $m = new Media_Model();
//        $config['uri_segment'] = 3;
//        $config['per_page'] = 200;
//        $config["num_links"] = 14;
//        $config['base_url'] = 'ml/ajax_list';
//        if($session_data['role'] == 'user_translator'){
//            $total = $m->where('userid', $session_data['ttuid'])->count();
//        }
//		//condition added by sushma mishra on 28-08-2015 to get the count for user translator admin type user starts from here
//		elseif($session_data['role'] == 'user_translator_admin'){
//			$result_ids = $this->cms_users_model->get_users_id();
//			$result = array();
//			foreach ($result_ids as $key => $value) {
//				$result[] = $value->id;
//			}
//			$total = $m->where_in('userid', $result)->count();
//		}
//		// code added by sushma mishra on 28-08-2015 ends here
//        else{
//            $total = $m->count();
//        }
//        $config['total_rows'] = $total;
//        if($session_data['role'] == 'user_translator'){
//            $m->where('userid', $session_data['ttuid'])->order_by('id')->get(200);
//        }
//		//condition added by sushma mishra on 28-08-2015 to add the where clause for user translator admin type user starts from here
//		elseif($session_data['role'] == 'user_translator_admin'){
//			$m->where_in('userid', $result)->order_by('id')->get(200);
//		}
//		// code added by sushma mishra on 28-08-2015 ends here
//        else{
//            $m->order_by('DATE(last_modified) desc, userid asc')->get(200);
//        }		
//        $data['media']= $m;
//        $this->pagination->initialize($config);
//        $data['links'] = $this->pagination->create_links();
//        $data['jsIncludes'] = array('ml.js');
//        $this->load->view('template', $data);
//    }
    
}