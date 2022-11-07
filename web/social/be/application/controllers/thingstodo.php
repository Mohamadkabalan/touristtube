<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thingstodo extends MY_Controller {	 

    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in'))
        { 
            redirect('login', 'refresh');
        }
        else{
            $session_data = $this->session->userdata('logged_in');
            $controller = $this->router->class;
            $action = $this->router->fetch_method();
            $role = $session_data['role'];
            if($role == 'copywriter' || $role == 'hotel_desc_writer'){
                $allowed = array('index', 'thingstodoplace', 'country_view', 'view', 'edit', 'item_edit', 'submit', 'item_submit');
                if(!in_array($action, $allowed)){
                    redirect('thingstodo/index', 'refresh');
                }
            }
        }
    }
    
    public function index(){
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Things to do Region';
        $data['content']= 'thingstodo/list_region';
        $h = new Thingstodo_Region_Model();
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'thingstodo/list_region';
        $total = $h->count();
        $config['total_rows'] = $total; 
        $res = $h->order_by('id')->get(500);
        $regionArr = $h->all_to_array();
        $contentArr = array();
        $i=0;
        foreach($regionArr as $item){
            $l = new Alias_Model();
            $l->where('id', $item['alias_id'])->get();			
            $lang = $l->language;
            $contentArr[$i]['id']=$item['id'];
            $contentArr[$i]['title']=$item['title'];
            $contentArr[$i]['description']=$item['description'];
            $contentArr[$i]['lang']=$lang;
            $contentArr[$i]['image']=$item['image'];
            $i++;
        }
        $data['thingstodo_region']= $contentArr;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['jsIncludes'] = array('thingstodo.js');
        $this->load->view('template', $data);
    }

    /**
    Method will call to show the form to add things-to-do-region
    */
    public function addregion(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add things to do Region';
        $data['content']= 'thingstodo/regionform';
        $data['jsIncludes'] = array('edit_page.js');
        $this->load->view('template', $data);
    }

    public function editregion($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit things to do Region';
        $data['content']= 'thingstodo/regionform';
        $h = new Thingstodo_Region_Model();
        $h->where('id', $id)->get();
        $data['thingstodo']= $h->to_array();
        $this->load->view('template', $data);
    }

	/**
	Method will call to submit the form data to add things-to-do-region
	*/
    public function regionsubmit(){
        $userdata = $this->session->userdata('logged_in');
        $id = $this->input->post('id');
        $h = new Thingstodo_Region_Model();
        if($id <> '' ){
            $h->where('id', $id)->get();
        }
        $h->title = $this->input->post('title');
        $h->description = $this->input->post('description');
        $h->h3 = $this->input->post('h3');
        $h->h4 = $this->input->post('h4');
        $h->p3 = $this->input->post('p3');
        $h->p4 = $this->input->post('p4');
        $h->language = $this->input->post('language');
        $this->load->helper('discover_title_helper');
        $preg = true;
        $clean_title = cleanTitle($h->title,$preg);

        if( $this->config->item('upload_src') == "s3" ){
            try{
                $s3 = new S3($this->config->item('accessKey'), $this->config->item('secretKey'));

                $base_name = 'media/thingstodo';

                $file_id = 'image';

                $file_name = $_FILES[$file_id]['tmp_name'];

                $file_base_name = $_FILES[$file_id]['name'];

                $ext_info = pathinfo($file_base_name);
                $ext = $ext_info["extension"];

                $file_base_name = $clean_title.'_'.time().".".$ext;

                $upload_success = $s3->putObjectFile($file_name, $this->config->item('bucketName'),$base_name . '/' .$file_base_name , S3::ACL_PUBLIC_READ);
                if ($upload_success) {
                 // $h->image = 'media/thingstodo/'.$file_base_name;
                 $h->image = $file_base_name;
             }
         }
         catch(Exception $e){
            $status = "error";
            $msg = $e->getMessage();
        }
    }
    else{
        $config['upload_path'] = '../media/thingstodo';
        $config['allowed_types'] = 'gif|jpeg|jpg|png';
        $config['max_size'] = 1024 * 10000;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $upload_success = $this->upload->do_upload('image');
        if($upload_success){
            if($h->image != '' || $id == ''){
                $old_image = '../media/thingstodo/'.$h->image;
                if($h->image != 'default.jpg' && $h->image != 'default1.jpg')
                    unlink($old_image);
            }
            $upload_data = $this->upload->data();
            $original_filename = $upload_data['file_name'];
            $extension = explode(".",$upload_data['file_name']);

            $new_name = $clean_title.'_'.time().'.'.end($extension);
            $basic_path = '../media/thingstodo/';
            rename($basic_path.$original_filename, $basic_path.$new_name);
            $path = $basic_path.$new_name;
            // $h->image = 'media/thingstodo/'.$new_name;
            $h->image = $new_name;
        }
    }


    $h->save();
    if($id == '' ){
        $alias = new Alias_Model();
        $alias->alias = $clean_title;
        $alias->entity_type = 'things-to-do-region';
        $alias->entity_id = 'id/'.$h->id;
        $alias->language = 'en';
        $alias->save();
        $h->alias_id = $alias->id;
        $h->save();
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOREGION_INSERT, $h->id);
//            $h = new Thingstodo_Region_Model();
//            $h->where('id', $h->id)->get();
//            $h->alias_id = $alias->id; 
//            $h->save();
    }
    else{
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOREGION_UPDATE, $h->id);
        $alias = new Alias_Model();
        $alias->where('id', $h->alias_id)->get();
        $alias->language = 'en';
        $alias->alias = $clean_title;
        $alias->save();
        if($upload_success){
            $original_filename = $h->image;
            $extension = explode(".",$original_filename);
            $this->load->helper('discover_title_helper');
            $clean_title = cleanTitle($h->title);
            $new_name = $clean_title.'_'.time().'.'.end($extension);
            if( $this->config->item('upload_src') != "s3" ){
                rename('../media/thingstodo/'.$original_filename, '../media/thingstodo/'.$new_name);
                // $h->image = 'media/thingstodo/'.$new_name;
                $h->image = $new_name;
            }
            $h->save();
        }
    }
    redirect('thingstodo/index', 'refresh');
}

public function division_category(){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Things to do division category';
    $data['content']= 'thingstodo/division_category';
    $category = new Thingstodo_Division_Category_Model();
    $res = $category->get();
    $data['categorys']= $category;
    $this->load->view('template', $data);
}

public function division_category_add(){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Edit Things to do division category';
    $data['content']= 'thingstodo/division_category_edit';
    $this->load->view('template', $data);
}

public function division_category_edit($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Edit Things to do division category';
    $data['content']= 'thingstodo/division_category_edit';
    $h = new Thingstodo_Division_Category_Model();
    $h->where('id', $id)->get();
    $data['category']= $h->to_array();
    $this->load->view('template', $data);
}

public function division_category_submit($id){
    $userdata = $this->session->userdata('logged_in');
    $id = $this->input->post('id');
    $name = $this->input->post('name');
    $h = new Thingstodo_Division_Category_Model();
    if($id <> '' ){
        $h->where('id', $id)->get();
    }
    $h->name = $name;
    $h->save();

    redirect('thingstodo/division_category/', 'refresh');

}

function ajax_division_category_delete($id){
    $userdata = $this->session->userdata('logged_in');
    $d = new Thingstodo_Division_Category_Model();
    if($id <>'') {
        $d->where('id',$id)->get();
    }
    $success = $d->delete();
    redirect('thingstodo/division_category/', 'refresh');
}

public function division(){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Things to do division ';
    $data['content']= 'thingstodo/division';
    $division = new Thingstodo_Division_Model();
    $res = $division->where('parent_id', null )->get();
    $data['divisions']= $division;
    $this->load->view('template', $data);
}

function ajax_division_delete($id){
    $userdata = $this->session->userdata('logged_in');
    $d = new Thingstodo_Division_Model();
    if($id <>'') {
        $d->where('id',$id)->get();
    }
    $success = $d->delete();
    if($success){
        $s = new Thingstodo_Division_Model();
        $s->where('parent_id',$id)->get();
        foreach($s as $item){
            $item->delete();
        }
    }
    redirect('thingstodo/division/', 'refresh');
}

function ajax_division_details_delete($id){
    $userdata = $this->session->userdata('logged_in');
    $a = new Thingstodo_Division_Model();
    $a->where('id',$id)->get();
    $a->delete();
    redirect('thingstodo/division/', 'refresh');
}

public function division_details($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Things to do division ';
    $data['content']= 'thingstodo/division_details';
    $data['id']= $id;
    $parent = new Thingstodo_Division_Model();
    $res = $parent->where('id', $id )->get();
    $division = new Thingstodo_Division_Model();
    $res = $division->where('parent_id', $id )->get();
    $thingstodo = $this->db->select('cms_thingstodo_details.id as thingstodoId , cms_thingstodo_details.title as thingstodoTitle')
    ->from('cms_thingstodo_details')
    ->order_by('cms_thingstodo_details.title', 'asc')
    ->get()
    ->result();
    $thingstodoDivisionCategorys = $this->db->select('thingstodo_division_category.id as thingstodoDivisionCategoryId , thingstodo_division_category.name as thingstodoDivisionCategoryTitle')
    ->from('thingstodo_division_category')
    ->get()
    ->result();

    $data['thingstodo'] = json_decode(json_encode($thingstodo), True);
    $data['thingstodoDivisionCategorys'] = json_decode(json_encode($thingstodoDivisionCategorys), True);
    $data['parent']= $parent->to_array();
    $data['divisions']= $division;
    $this->load->view('template', $data);
}

public function division_details_edit($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Things to do division ';
    $data['content']= 'thingstodo/division_details_edit';
    $division = new Thingstodo_Division_Model();
    $division->where('id', $id )->get();
    $data['division']= $division->to_array();
    $thingstodo = $this->db->select('cms_thingstodo_details.id as thingstodoId , cms_thingstodo_details.title as thingstodoTitle')
    ->from('cms_thingstodo_details')
    ->order_by('cms_thingstodo_details.title', 'asc')
    ->get()
    ->result();
    $thingstodoDivisionCategorys = $this->db->select('thingstodo_division_category.id as thingstodoDivisionCategoryId , thingstodo_division_category.name as thingstodoDivisionCategoryTitle')
    ->from('thingstodo_division_category')
    ->get()
    ->result();

    $data['thingstodo'] = json_decode(json_encode($thingstodo), True);
    $data['thingstodoDivisionCategorys'] = json_decode(json_encode($thingstodoDivisionCategorys), True);
    $this->load->view('template', $data);
}

public function division_details_add($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Things to do division ';
    $data['content']= 'thingstodo/division_details_edit';
    $data['parentId'] = $id;
    $thingstodo = $this->db->select('cms_thingstodo_details.id as thingstodoId , cms_thingstodo_details.title as thingstodoTitle')
    ->from('cms_thingstodo_details')
    ->order_by('cms_thingstodo_details.title', 'asc')
    ->get()
    ->result();
    $thingstodoDivisionCategorys = $this->db->select('thingstodo_division_category.id as thingstodoDivisionCategoryId , thingstodo_division_category.name as thingstodoDivisionCategoryTitle')
    ->from('thingstodo_division_category')
    ->get()
    ->result();

    $data['thingstodo'] = json_decode(json_encode($thingstodo), True);
    $data['thingstodoDivisionCategorys'] = json_decode(json_encode($thingstodoDivisionCategorys), True);
    $this->load->view('template', $data);
}

public function division_add($id=0){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Things to do division ';
    $data['content']= 'thingstodo/division_details_edit';
    $thingstodo = $this->db->select('cms_thingstodo_details.id as thingstodoId , cms_thingstodo_details.title as thingstodoTitle')
    ->from('cms_thingstodo_details')
    ->order_by('cms_thingstodo_details.title', 'asc')
    ->get()
    ->result();
    $thingstodoDivisionCategorys = $this->db->select('thingstodo_division_category.id as thingstodoDivisionCategoryId , thingstodo_division_category.name as thingstodoDivisionCategoryTitle')
    ->from('thingstodo_division_category')
    ->get()
    ->result();

    $data['thingstodo'] = json_decode(json_encode($thingstodo), True);
    $data['ttd_idas'] = $id;
    $data['thingstodoDivisionCategorys'] = json_decode(json_encode($thingstodoDivisionCategorys), True);
    $this->load->view('template', $data);
}

public function division_details_submit(){
    $userdata = $this->session->userdata('logged_in');
    $id = $this->input->post('id');
    $parentId = $this->input->post('parentId');
    if(!$parentId || $parentId == 0){
        $parentId = null;
    }
    $name = $this->input->post('name');
    $division_category_id = $this->input->post('division_category_id');
    $ttd_id = $this->input->post('ttd_id');
    $media_settings = $this->input->post('media_settings');
    $sort_order = $this->input->post('sort_order');
    $h = new Thingstodo_Division_Model();
    if($id <> '' ){
        $h->where('id', $id)->get();
    }
    $h->name = $name;
    $h->parent_id = $parentId;
    $h->division_category_id = $division_category_id;
    $h->ttd_id = $ttd_id;
    $h->media_settings = $media_settings;
    $h->sort_order = $sort_order;
    $h->save();

    if(!$parentId || $parentId == 0){
        redirect('thingstodo/division/', 'refresh');
    }else{
        redirect('thingstodo/division_details/'.$parentId, 'refresh');
    }

}

public function division_submit(){
    $userdata = $this->session->userdata('logged_in');
    $id = $this->input->post('id');
    $parentId = $this->input->post('parentId');
    if(!$parentId || $parentId == 0){
        $parentId = null;
    }
    $name = $this->input->post('name');
    $division_category_id = $this->input->post('division_category_id');
    $ttd_id = $this->input->post('ttd_id');
    $sort_order = $this->input->post('sort_order');
    $media_settings = $this->input->post('media_settings');
    $h = new Thingstodo_Division_Model();
    if($id <> '' ){
        $h->where('id', $id)->get();
    }
    $h->name = $name;
    $h->parent_id = $parentId;
    $h->division_category_id = $division_category_id;
    $h->ttd_id = $ttd_id;
    $h->sort_order = $sort_order;
    if($media_settings || $media_settings != '' || !empty($media_settings)){
        $h->media_settings = $media_settings;
    }
    $h->save();

    redirect('thingstodo/division_details/'.$id, 'refresh');

}


public function ajax_delete_country($id){
    $userdata = $this->session->userdata('logged_in');
    $d = new Thingstodo_Country_Model();
    $success = FALSE;
    if($id <>'') {
        $d->where('id', $id )->get();
        $aliasid = $d->alias_id;
        if( $this->config->item('upload_src') == "s3" ){
            S3::deleteObject($this->config->item('bucketName'),$d->image);
        }
        $success = $d->delete();
        $a = new Alias_Model();
        $a->where('id', $aliasid )->get(); 
        $a->delete();
                    //$this->load->model('activitylog_model');
                    //$this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
    }
    echo json_encode (array('success' => $success));
}

public function ajax_delete($id){
    $userdata = $this->session->userdata('logged_in');
    $d = new Thingstodo_Model();
    $success = FALSE;
    if($id <>'') {
//            $ttddetails = new Thingstodo_Details_Model();
//            $ttddetails->where('parent_id', $id)->get();
//            foreach($ttddetails as $item){
//                $item->delete();
//            }
        $d->where('id', $id )->get();
        $aliasid = $d->alias_id;
        if( $this->config->item('upload_src') == "s3" ){
            S3::deleteObject($this->config->item('bucketName'),$d->image);
        }
        $success = $d->delete();
        $a = new Alias_Model();
        $a->where('id', $aliasid )->get(); 
        $a->delete();
                //$this->load->model('activitylog_model');
                //$this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
    }
    echo json_encode (array('success' => $success));
}

	/**
	Method will call to delete things-to-do-region
	*/
	public function ajax_regiondelete($id){
		$userdata = $this->session->userdata('logged_in');
		$d = new Thingstodo_Region_Model();
		$success = FALSE;
		if($id <>'') {
			$d->where('id', $id )->get();
			$aliasid = $d->alias_id;
            if( $this->config->item('upload_src') == "s3" ){
                S3::deleteObject($this->config->item('bucketName'),$d->image);
            }
            $success = $d->delete();
            $a = new Alias_Model();
            $a->where('id', $aliasid )->get(); 
            $success = $a->delete();

			//$this->load->model('activitylog_model');
			//$this->activitylog_model->insert_log($userdata['uid'], HOTEL_DELETE, $id);
        }
        echo json_encode (array('success' => $success));
    }
	//code added by sushma mishra ends here

    public function lang_regionedit($lang, $id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $h = new Thingstodo_Region_Model();
        $h->where('id', $id)->get();
        $region = $h->to_array();
        $language = '';
        switch($lang){
            case 'fr':
            $language = "French";
            break;
            case 'in':
            $language = "Hindu";
            break;
        }
        $data['key'] = $lang;
        $data['title']= 'Edit things to do Region - '.$region['title'].' - '.$language;
        $data['content']= 'thingstodo/region_trans';
        $ml = new Ml_Thingstodo_Region_Model();
        $ml->where('parent_id', $id)->where('language', $lang)->get();
        $data['regionid'] = $id;
        $data['thingstodo']= $ml->to_array();
        $this->load->view('template', $data);
    }

    public function lang_regionsubmit(){
        $id = $this->input->post('id');
        $lang = $this->input->post('lang');
        $title = $this->input->post($lang.'_title');
        $description = $this->input->post($lang.'_description');
        $h3 = $this->input->post($lang.'_h3');
        $h4 = $this->input->post($lang.'_h4');
        $p3 = $this->input->post($lang.'_p3');
        $p4 = $this->input->post($lang.'_p4');
        $ml = new Ml_Thingstodo_Region_Model();
        $ml->where('parent_id', $id)->where('language', $lang)->get();
        $res = $ml->to_array();
        if(!isset($res['id'])){
            $ml = new Ml_Thingstodo_Region_Model();
            $ml->parent_id = $id;
            $ml->language = $lang;
        }
        $ml->title = $title;
        $ml->description = $description;
        $ml->h3 = $h3;
        $ml->h4 = $h4;
        $ml->p3 = $p3;
        $ml->p4 = $p4;
        $ml->save();
        redirect('thingstodo/thingstodoplace/'.$id, 'refresh');
    }

    public function country_view($rid=0){
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Things to do countries';
        $data['content']= 'thingstodo/list_country';  
        $data['countryid']= $rid;
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'thingstodo/ajax_list_country/'.$rid;
		//code added by sushma mishra on 17th november 2015 starts from here
        if($rid!=0){
         $r = new Thingstodo_Country_Model();
         $r->where('id', $rid)->get();
         $data['countrydetail']= $r->to_array();
         $languages = array('hi', 'fr','cn');
         foreach($languages as $lang){
            $ml_result[$lang] = array(
                'title' => '',
                'description' => '',
                'h3' => '',
                'h4' => '',
                'p3' => '',
                'p4' => ''
            );
        }
        $h_lang = new Ml_Thingstodo_Country_Model();			
        $h_lang->where('parent_id', $rid)->get();
        $ml_all = $h_lang->all_to_array();
        foreach($ml_all as $item){
            $ml_result[$item['language']]['title'] = $item['title'];
            $ml_result[$item['language']]['description'] = $item['description'];
            $ml_result[$item['language']]['h3'] = $item['h3'];
            $ml_result[$item['language']]['p3'] = $item['p3'];
            $ml_result[$item['language']]['h4'] = $item['h4'];
            $ml_result[$item['language']]['p4'] = $item['p4'];
        }
        $data['result'] = $ml_result;


    }		
    $h = new Thingstodo_Model();
    if($rid!=0){
     $total = $h->where(array('parent_id'=> $rid))->count();
     $res = $h->where(array('parent_id'=> $rid))->order_by('order_display')->get(500);
 }else{
     $total = $h->count();
     $res = $h->order_by('order_display')->get(500);
 } 
		//code added by sushma mishra ends here
 $config['total_rows'] = $total;
 $data['thingstodo']= $h;
 $this->pagination->initialize($config);
 $data['links'] = $this->pagination->create_links();
 $data['jsIncludes'] = array('thingstodo.js');
 $this->load->view('template', $data);
}

public function thingstodoplace($rid=0){
    $this->load->library('pagination');
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Things to do';
    $data['content']= 'thingstodo/list';  
    $data['regionid']= $rid;
    $config['uri_segment'] = 3;
    $config['per_page'] = 500;
    $config["num_links"] = 14;
    $config['base_url'] = 'thingstodo/ajax_list/'.$rid;
		//code added by sushma mishra on 17th november 2015 starts from here
    if($rid!=0){
     $r = new Thingstodo_Region_Model();
     $r->where('id', $rid)->get();
     $data['regiondetail']= $r->to_array();
     $languages = array('hi', 'fr','cn');
     foreach($languages as $lang){
        $ml_result[$lang] = array(
            'title' => '',
            'description' => '',
            'h3' => '',
            'h4' => '',
            'p3' => '',
            'p4' => ''
        );
    }
    $h_lang = new Ml_Thingstodo_Region_Model();			
    $h_lang->where('parent_id', $rid)->get();
    $ml_all = $h_lang->all_to_array();
    foreach($ml_all as $item){
        $ml_result[$item['language']]['title'] = $item['title'];
        $ml_result[$item['language']]['description'] = $item['description'];
        $ml_result[$item['language']]['h3'] = $item['h3'];
        $ml_result[$item['language']]['h4'] = $item['h4'];
        $ml_result[$item['language']]['p3'] = $item['p3'];
        $ml_result[$item['language']]['p4'] = $item['p4'];
    }
    $data['result'] = $ml_result;


}		
$h = new Thingstodo_Country_Model();
if($rid!=0){
 $total = $h->where(array('parent_id'=> $rid))->count();
 $res = $h->where(array('parent_id'=> $rid))->order_by('order_display')->get(500);
}else{
 $total = $h->count();
 $res = $h->order_by('order_display')->get(500);
} 
		//code added by sushma mishra ends here
$config['total_rows'] = $total;
$data['thingstodo']= $h;
$this->pagination->initialize($config);
$data['links'] = $this->pagination->create_links();
$data['jsIncludes'] = array('thingstodo.js');
$this->load->view('template', $data);
}

function ajax_list($rid,$start = 0){
	
    $this->load->library('pagination');
    $h = new Thingstodo_Model();		
    $config['uri_segment'] = 3;
    $config['per_page'] = 500;
    $config["num_links"] = 14;
    $config['base_url'] = 'thingstodo/ajax_list/'.$rid;		
    $total = $h->where('parent_id', $rid)->count();
    $config['total_rows'] = $total;
		//where clause added by sushma mishra on 17th november 2015 to show region specific places
    $res = $h->where('parent_id', $rid)->order_by('order_display')->get(500, $start);
    $data['thingstodo']= $h;
    $this->pagination->initialize($config);
    $data['links'] = $this->pagination->create_links();
    $this->load->view('thingstodo/ajax_list', $data);
}

public function lang_countryedit($lang, $id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $h = new Thingstodo_Country_Model();
    $h->where('id', $id)->get();
    $item = $h->to_array();
    $language = '';
    switch($lang){
        case 'fr':
        $language = "French";
        break;
        case 'in':
        $language = "Hindu";
        break;
    }
    $data['key'] = $lang;
    $data['title']= 'Edit things to do country - '.$item['title'].' - '.$language;
    $data['content']= 'thingstodo/country_trans';
    $ml = new Ml_Thingstodo_Country_Model();
    $ml->where('parent_id', $id)->where('language', $lang)->get();
    $data['countryid'] = $id;
    $data['thingstodo']= $ml->to_array();
    $this->load->view('template', $data);
}

public function lang_countrysubmit(){
    $id = $this->input->post('id');
    $lang = $this->input->post('lang');
    $title = $this->input->post($lang.'_title');
    $description = $this->input->post($lang.'_description');
    $h3 = $this->input->post($lang.'_h3');
    $p3 = $this->input->post($lang.'_p3');
    $h4 = $this->input->post($lang.'_h4');
    $p4 = $this->input->post($lang.'_p4');
    $ml = new Ml_Thingstodo_Country_Model();
    $ml->where('parent_id', $id)->where('language', $lang)->get();
    $res = $ml->to_array();
    if(!isset($res['id'])){
        $ml = new Ml_Thingstodo_Country_Model();
        $ml->parent_id = $id;
        $ml->language = $lang;
    }
    $ml->title = $title;
    $ml->description = $description;
    $ml->h3 = $h3;
    $ml->p3 = $p3;
    $ml->h4 = $h4;
    $ml->p4 = $p4;
    $ml->save();
    redirect('thingstodo/country_view/'.$id, 'refresh');
}

public function lang_itemedit($lang, $id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $h = new Thingstodo_Model();
    $h->where('id', $id)->get();
    $item = $h->to_array();
    $language = '';
    switch($lang){
        case 'fr':
        $language = "French";
        break;
        case 'in':
        $language = "Hindu";
        break;
    }
    $data['key'] = $lang;
    $data['title']= 'Edit things to do - '.$item['title'].' - '.$language;
    $data['content']= 'thingstodo/item_trans';
    $ml = new Ml_Thingstodo_Model();
    $ml->where('parent_id', $id)->where('language', $lang)->get();
    $data['itemid'] = $id;
    $data['thingstodo']= $ml->to_array();
    $this->load->view('template', $data);
}

public function lang_itemsubmit(){
    $id = $this->input->post('id');
    $lang = $this->input->post('lang');
    $title = $this->input->post($lang.'_title');
    $description = $this->input->post($lang.'_description');
    $h3 = $this->input->post($lang.'_h3');
    $p3 = $this->input->post($lang.'_p3');
    $h4 = $this->input->post($lang.'_h4');
    $p4 = $this->input->post($lang.'_p4');
    $desc_thingstodo = $this->input->post($lang.'_desc_thingstodo');
    $desc_discover = $this->input->post($lang.'_desc_discover');
    $desc_hotelsin = $this->input->post($lang.'_desc_hotelsin');
    $ml = new Ml_Thingstodo_Model();
    $ml->where('parent_id', $id)->where('language', $lang)->get();
    $res = $ml->to_array();
    if(!isset($res['id'])){
        $ml = new Ml_Thingstodo_Model();
        $ml->parent_id = $id;
        $ml->language = $lang;
    }
    $ml->title = $title;
    $ml->description = $description;
    $ml->h3 = $h3;
    $ml->p3 = $p3;
    $ml->h4 = $h4;
    $ml->p4 = $p4;
    $ml->desc_thingstodo = $desc_thingstodo;
    $ml->desc_discover = $desc_discover;
    $ml->desc_hotelsin = $desc_hotelsin;
    $ml->save();
    redirect('thingstodo/view/'.$id, 'refresh');
}

public function validate_title(){
    $title = $this->input->post('title');
    $tttId = intval($this->input->post('id'));
    $result = array('status' => 'success', 'msg' => '');
    $ttd = new Thingstodo_Model();
    $ttd->where('title', $title)->where("id <> $tttId")->get();
    $ttd_res = $ttd->all_to_array();
    if(count($ttd_res) > 0){
        $result['status'] = 'error';
        $result['msg'] = 'City with this name already exist';
    }
    $ttd_country = new Thingstodo_Country_Model();
    $ttd_country->where('title', $title)->where("id <> $tttId")->get();
    $ttd_country_res = $ttd_country->all_to_array();
    if(count($ttd_country_res) > 0){
        $result['status'] = 'error';
        $result['msg'] = 'Country with this name already exist';
    }
    echo json_encode($result);
}

public function view_ajax(){
    $id = $this->input->post('id');
    $ttdName = $this->input->post('ttdName');
    $ttdId = $this->input->post('ttdId');

    $items = new Thingstodo_Details_Model();
    if($id == ''){
        if($ttdId == ''){
            $items->like('title', $ttdName)->order_by('order_display')->get();
        }
        else{
            $items->where('id', $ttdId)->order_by('order_display')->get();
        }
    }
    else{
        if($ttdId == ''){
            $items->where('parent_id', $id)->like('title', $ttdName)->order_by('order_display')->get();
        }
        else{
            $items->where('parent_id', $id)->where('id', $ttdId)->order_by('order_display')->get();
        }
    }
    if($ttdId == ''){
        $data['items']= $items->all_to_array();
    }
    else{
        $data['items']= array($items->to_array());
    }

    $this->load->view('thingstodo/view_ajax', $data);
}

public function view($id){ 
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'View things to do';
    $data['content']= 'thingstodo/view';
    $data['jsIncludes'] = array('thingstodo.js', 'thingstodoview.js');
    if($id != 0){
        $h = new Thingstodo_Model();
        $h->where('id', $id)->get();
        $h->details->order_by('order_display')->get();
        $data['thingstodo']= $h->to_array();
        $data['items']=$h->details->all_to_array();

        $languages = array('hi', 'fr','cn');
        foreach($languages as $lang){
            $ml_result[$lang] = array(
                'title' => '',
                'description' => '',
                'h3' => '',
                'h4' => '',
                'p3' => '',
                'p4' => '',
                'desc_thingstodo' => '',
                'desc_discover' => '',
                'desc_hotelsin' => ''
            );
        }
        $h_lang = new Ml_Thingstodo_Model();			
        $h_lang->where('parent_id', $id)->get();
        $ml_all = $h_lang->all_to_array();
        foreach($ml_all as $item){
            $ml_result[$item['language']]['title'] = $item['title'];
            $ml_result[$item['language']]['description'] = $item['description'];
            $ml_result[$item['language']]['h3'] = $item['h3'];
            $ml_result[$item['language']]['h4'] = $item['h4'];
            $ml_result[$item['language']]['p3'] = $item['p3'];
            $ml_result[$item['language']]['p4'] = $item['p4'];
            $ml_result[$item['language']]['desc_thingstodo'] = $item['desc_thingstodo'];
            $ml_result[$item['language']]['desc_discover'] = $item['desc_discover'];
            $ml_result[$item['language']]['desc_hotelsin'] = $item['desc_hotelsin'];
        }
        $data['result'] = $ml_result;
    }
    else{
        $items = new Thingstodo_Details_Model();
        $res = $items->order_by('order_display')->get();
        $data['items']= $items->all_to_array();
    }

//        $h_en = new Thingstodo_Model();
//        $h_en->where(array('language' => 'en', 'parent_id'=> $id));
//        $h_en->details->get();
//        $data['count_en'] = $h_en->count();
//        
//        $h_fr = new Thingstodo_Model();
//        $h_fr->where(array('language' => 'fr', 'parent_id'=> $id));
//        $h_fr->details->get();
//        $data['count_fr'] = $h_fr->count();
//        
//        $h_in = new Thingstodo_Model();
//        $h_in->where(array('language' => 'in', 'parent_id'=> $id));
//        $h_in->details->get();
//        $data['count_in'] = $h_in->count();
//        $data['jsIncludes'] = array('hotel.js');
//        $data['cssIncludes'] = array('hotel.css');
        //        $map_img_name = 'uploads/map_h_'.$h->id.'.png';
//        if(isset($h->map_image) && $h->map_image <> '')
//            $data['map_image'] = 'uploads/'.$h->map_image;

    $this->load->view('template', $data);
}

public function country_edit($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Edit things to do country';
    $data['content']= 'thingstodo/countryform';
    $h = new Thingstodo_Country_Model();
    $h->where('id', $id)->get();
    $data['thingstodo']= $h->to_array();
    if($h->country_code != '' && $h->state_code != ''){
        $this->load->model('states_model');
        $ret = $this->states_model->getbycode($h->country_code, $h->state_code);
        $data['state_id'] = $ret['id'];
    }else{
        $data['state_id'] = '';
    }
//        $data['cssIncludes'] = array('imgareaselect-default.css', 'jquery.awesome-cropper.css');
//        $data['jsIncludes'] = array('jquery.imgareaselect.js', 'jquery.awesome-cropper.js', 'thingstodo.js');
    $data['jsIncludes'] = array('thingstodo_country.js', 'thingstodo_validation.js');
    $this->load->view('template', $data);
}

public function country_add($rid){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Add things to do country'; 
        //region id stored in data array by sushma mishra on 17th november 2015
    $data['regionid']= $rid;
    $data['content']= 'thingstodo/countryform';
        //$data['jsIncludes'] = array('edit_page.js');
    $data['jsIncludes'] = array('thingstodo_country.js', 'thingstodo_validation.js');
    $this->load->view('template', $data);
}	

public function country_submit(){
    $userdata = $this->session->userdata('logged_in');
    $id = $this->input->post('id');
    $rid = $this->input->post('region');
    $h = new Thingstodo_Country_Model();
    if($id <> '' ){
        $h->where('id', $id)->get();
    }
    $countrycode = $this->input->post('country_code');
    $h->country_code = $countrycode;
    $this->load->model('states_model');
    $ret = $this->states_model->getbyid(intval($this->input->post('state_id')));
    if($ret){
        $h->state_code = $ret['code'];
    }else{
        $h->state_code = '';
    }
    $h->city_id = intval($this->input->post('city_id'));
    $h->title = $this->input->post('title');
    $h->h3 = $this->input->post('h3');
    $h->p3 = $this->input->post('p3');
    $h->h4 = $this->input->post('h4');
    $h->p4 = $this->input->post('p4');
    $h->description = $this->input->post('description');
    $h->order_display = intval($this->input->post('order_display'));
        //region id stored in thingstodod table by sushma mishra on 18th november 2015
    $h->parent_id = $rid;
    $this->load->helper('discover_title_helper');
    $preg = true;
    $clean_title = cleanTitle($h->title,$preg);

    if( $this->config->item('upload_src') == "s3" ){
        try{
            $s3 = new S3($this->config->item('accessKey'), $this->config->item('secretKey'));

            $base_name = 'media/thingstodo';

            $file_id = 'image';

            $file_name = $_FILES[$file_id]['tmp_name'];

            $file_base_name = $_FILES[$file_id]['name'];

            $ext_info = pathinfo($file_base_name);
            $ext = $ext_info["extension"];

            $file_base_name = $clean_title.'_'.time().".".$ext;

            $upload_success = $s3->putObjectFile($file_name, $this->config->item('bucketName'),$base_name . '/' .$file_base_name , S3::ACL_PUBLIC_READ);
            if ($upload_success) {
               // $h->image = 'media/thingstodo/'.$file_base_name;
               $h->image = $file_base_name;
           }
       }
       catch(Exception $e){
        $status = "error";
        $msg = $e->getMessage();
        echo $msg;exit;
    }
}
else{
    $config['upload_path'] = '../media/thingstodo';
    $config['allowed_types'] = 'gif|jpeg|jpg|png';
    $config['max_size'] = 1024 * 10000;
    $config['encrypt_name'] = TRUE;
    $this->load->library('upload', $config);
    $upload_success = $this->upload->do_upload('image');
    if($upload_success){
        if($h->image != '' || $id == ''){
            $old_image = '../media/thingstodo/'.$h->image;
            if($h->image != 'default.jpg' && $h->image != 'default1.jpg')
                unlink($old_image);
        }
        $upload_data = $this->upload->data();
        $original_filename = $upload_data['file_name'];
        $extension = explode(".",$upload_data['file_name']);

        $new_name = $clean_title.'_'.time().'.'.end($extension);
        $basic_path = '../media/thingstodo/';
        rename($basic_path.$original_filename, $basic_path.$new_name);
        $path = $basic_path.$new_name;
        $h->image = $new_name;
    }
}

$h->save();
if($id == '' ){
    $alias = new Alias_Model();
    $alias->alias = $clean_title;
    $alias->entity_type = 'things-to-do-country';
    $alias->entity_id = 'id/'.$h->id;
    $alias->language = 'en';
    $alias->save();
    $h->alias_id = $alias->id;
    $h->save();
    $this->load->model('activitylog_model');
    $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOCOUNTRY_INSERT, $h->id);
}
else{
    $alias = new Alias_Model();
    $alias->where('id', $h->alias_id)->get();
    $alias->language = 'en';
    $alias->alias = $clean_title;
    $alias->save();
    if($upload_success){
        $original_filename = $h->image;
        $extension = explode(".",$original_filename);
        $this->load->helper('discover_title_helper');
        $clean_title = cleanTitle($h->title);
        $new_name = $clean_title.'_'.time().'.'.end($extension);
        if( $this->config->item('upload_src') != "s3"){
            rename('../media/thingstodo/'.$original_filename, '../media/thingstodo/'.$new_name);
            $h->image = $new_name;
        }
        $h->save();
    }
    $this->load->model('activitylog_model');
    $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOCOUNTRY_UPDATE, $h->id);
}

//        $this->load->model('activitylog_model');
//        $activity_code = $id == '' ? HOTEL_INSERT : HOTEL_UPDATE;
//        $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $h->id);
redirect('thingstodo/country_view/'.$h->id, 'refresh');
}

public function edit($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Edit things to do';
    if($session_data['role'] == 'copywriter'){
        $data['content'] = 'thingstodo/cw_form';
    }
    else{
        $data['content']= 'thingstodo/form';
    }
    $h = new Thingstodo_Model();
    $h->where('id', $id)->get();
    $data['thingstodo']= $h->to_array();
    if($h->country_code != '' && $h->state_code != ''){
        $this->load->model('states_model');
        $ret = $this->states_model->getbycode($h->country_code, $h->state_code);
        $data['state_id'] = $ret['id'];
    }else{
        $data['state_id'] = '';
    }

//        $data['cssIncludes'] = array('imgareaselect-default.css', 'jquery.awesome-cropper.css');
//        $data['jsIncludes'] = array('jquery.imgareaselect.js', 'jquery.awesome-cropper.js', 'thingstodo.js');
//        $data['jsIncludes'] = array('edit_page.js');
    $data['jsIncludes'] = array('thingstodoitem.js', 'thingstodo_validation.js');
    $this->load->view('template', $data);
}

public function add($rid){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Add things to do'; 
		//region id stored in data array by sushma mishra on 17th november 2015
    $data['regionid']= $rid;
    $data['content']= 'thingstodo/form';
        //$data['jsIncludes'] = array('edit_page.js');
//        $data['jsIncludes'] = array('edit_page.js');
    $data['jsIncludes'] = array('thingstodoitem.js', 'thingstodo_validation.js');
    $this->load->view('template', $data);
}	

public function submit(){
    $userdata = $this->session->userdata('logged_in');
    $id = $this->input->post('id');
    $rid = $this->input->post('region');
    $h = new Thingstodo_Model();
    if($id <> '' ){
        $h->where('id', $id)->get();
    }
    if($userdata['role'] == 'copywriter'){
        $h->h3 = $this->input->post('h3');
        $h->p3 = $this->input->post('p3');
        $h->h4 = $this->input->post('h4');
        $h->p4 = $this->input->post('p4');
        $h->description = $this->input->post('description');
        $h->save();
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOCITY_UPDATE, $h->id);
    }
    else{
        $h->title = $this->input->post('title');
        $h->h3 = $this->input->post('h3');
        $h->p3 = $this->input->post('p3');
        $h->h4 = $this->input->post('h4');
        $h->p4 = $this->input->post('p4');
        $countrycode = $this->input->post('country_code');
        $h->country_code = $countrycode;
        $this->load->model('states_model');
        $ret = $this->states_model->getbyid(intval($this->input->post('state_id')));
        if($ret){
            $h->state_code = $ret['code'];
        }else{
            $h->state_code = '';
        }
        $h->city_id = intval($this->input->post('city_id'));
        $h->description = $this->input->post('description');
        $h->desc_thingstodo = $this->input->post('desc_thingstodo');
        $h->desc_discover = $this->input->post('desc_discover');
        $h->desc_hotelsin = $this->input->post('desc_hotelsin');
        $h->order_display = intval($this->input->post('order_display'));
            //region id stored in thingstodod table by sushma mishra on 18th november 2015
        $h->parent_id = intval($rid);
        $this->load->helper('discover_title_helper');
        $preg = true;
        $clean_title = cleanTitle($h->title,$preg);

        if( $this->config->item('upload_src') == "s3" ){
            try{
                $s3 = new S3($this->config->item('accessKey'), $this->config->item('secretKey'));

                $base_name = 'media/thingstodo';

                $file_id = 'image';

                $file_name = $_FILES[$file_id]['tmp_name'];

                $file_base_name = $_FILES[$file_id]['name'];

                $ext_info = pathinfo($file_base_name);
                $ext = $ext_info["extension"];

                $file_base_name = $clean_title.'_'.time().".".$ext;

                $upload_success = $s3->putObjectFile($file_name, $this->config->item('bucketName'),$base_name . '/' .$file_base_name , S3::ACL_PUBLIC_READ);
                if ($upload_success) {
                 // $h->image = 'media/thingstodo/'.$file_base_name;
                 $h->image = $file_base_name;
             }
         }
         catch(Exception $e){
            $status = "error";
            $msg = $e->getMessage();
            echo $msg;exit;
        }
    }
    else{
        $config['upload_path'] = '../media/thingstodo';
        $config['allowed_types'] = 'gif|jpeg|jpg|png';
        $config['max_size'] = 1024 * 10000;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $upload_success = $this->upload->do_upload('image');
        if($upload_success){
            if($h->image != '' || $id == ''){
                $old_image = '../media/thingstodo/'.$h->image;
                if($h->image != 'default.jpg' && $h->image != 'default1.jpg')
                    unlink($old_image);
            }
            $upload_data = $this->upload->data();
            $original_filename = $upload_data['file_name'];
            $extension = explode(".",$upload_data['file_name']);

            $new_name = $clean_title.'_'.time().'.'.end($extension);
            $basic_path = '../media/thingstodo/';
            rename($basic_path.$original_filename, $basic_path.$new_name);
            $path = $basic_path.$new_name;
            $h->image = $new_name;
        }
    }

    $h->save();
    if($id == '' ){
        $alias = new Alias_Model();
        $alias->alias = $clean_title;
        $alias->entity_type = 'top-things-to-do';
        $alias->entity_id = 'id/'.$h->id;
        $alias->language = 'en';
        $alias->save();
        $h->alias_id = $alias->id;
        $h->save();
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOCITY_INSERT, $h->id);
    }
    else{
        $alias = new Alias_Model();
        $alias->where('id', $h->alias_id)->get();
        $alias->language = 'en';
        $alias->alias = $clean_title;
        $alias->save();
        if($upload_success){
            $original_filename = $h->image;
            $extension = explode(".",$original_filename);
            $this->load->helper('discover_title_helper');
            $clean_title = cleanTitle($h->title);
            $new_name = $clean_title.'_'.time().'.'.end($extension);
            if( $this->config->item('upload_src') != "s3" ){
                rename('../media/thingstodo/'.$original_filename, '../media/thingstodo/'.$new_name);
                $h->image = $new_name;
            }
            $h->save();
        }
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOCITY_UPDATE, $h->id);
    }
}



//        $this->load->model('activitylog_model');
//        $activity_code = $id == '' ? HOTEL_INSERT : HOTEL_UPDATE;
//        $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $h->id);
redirect('thingstodo/view/'.$h->id, 'refresh');
}

public function lang_detailssubmit(){
    $id = $this->input->post('id');
    $lang = $this->input->post('lang');
    $title = $this->input->post($lang.'_title');
    $description = $this->input->post($lang.'_description');

    if($title == '' && $description == ''){
        $ml1 = new Ml_Thingstodo_Details_Model();
        $ml1->where('parent_id', $id)->where('language', $lang)->get();
        $res1 = $ml1->to_array();
        if(isset($res1['id'])){
            $ml1->where('id', $res1['id']  )->get();
            $ml1->delete();
            redirect('thingstodo/item_edit/'.$id, 'refresh');
        }
    }else{

        $ml = new Ml_Thingstodo_Details_Model();
        $ml->where('parent_id', $id)->where('language', $lang)->get();
        $res = $ml->to_array();
        if(!isset($res['id'])){
            $ml = new Ml_Thingstodo_Details_Model();
            $ml->parent_id = $id;
            $ml->language = $lang;
        }
        $ml->title = $title;
        $ml->description = $description;
        $ml->save();
        redirect('thingstodo/item_edit/'.$id, 'refresh');
    }
}

public function item_edit($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Edit item';
    $r = new Thingstodo_Details_Model();
    $r->where('id', $id)->get();
    $r->thingstodo->get();
    $data['parent_id']= $r->thingstodo->id;
    $data['item']= $r->to_array();
    if($session_data['role'] == 'copywriter'){
        $data['content']= 'thingstodo/cw_form_item';
    }
    else{
        $data['content']= 'thingstodo/form_item';
        $languages = array('hi', 'fr','cn');
        foreach($languages as $lang){
            $ml_result[$lang] = array(
                'title' => '',
                'description' => ''
            );
        }
        $h_lang = new Ml_Thingstodo_Details_Model();			
        $h_lang->where('parent_id', $id)->get();
        $ml_all = $h_lang->all_to_array();
        
        foreach($ml_all as $item){
            $ml_result[$item['language']]['title'] = $item['title'];
            $ml_result[$item['language']]['description'] = $item['description'];
        }
        
        $data['result'] = $ml_result;
    }
    $data['jsIncludes'] = array('thingstodo.js');
    $this->load->view('template', $data);
}

public function item_add($id){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['title']= 'Add item';
    $data['parent_id']= $id;
    $data['content']= 'thingstodo/form_item';
    $data['jsIncludes'] = array('thingstodo.js');
    $this->load->view('template', $data);
}

function item_delete($id){
    $userdata = $this->session->userdata('logged_in');
    $d = new Thingstodo_Details_Model();
    if($id <>'') {
        $d->where('id', $id )->get();
    }
    $parent_id = $d->parent_id;
    if( $this->config->item('upload_src') == "s3" ){
        S3::deleteObject($this->config->item('bucketName'),$d->image);
    }
    $success = $d->delete();
//        $this->load->model('activitylog_model');
//        $this->activitylog_model->insert_log($userdata['uid'], HOTEL_ROOM_DELETE, $id);
    echo json_encode (array('success' => $success));
//        redirect('thingstodo/view/'.$parent_id, 'refresh');
}

function item_submit(){
    $userdata = $this->session->userdata('logged_in');
    $d = new Thingstodo_Details_Model();
    $id = $this->input->post('id');
    if($id <>'') {
        $d->where('id', $id )->get();
    }
    if($userdata['role'] == 'copywriter'){
        $d->description=$this->input->post('description');
        $d->save();
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOPOI_UPDATE, $d->id);
    }
    else{
        $tag=intval($this->input->post('tag'));
        if($tag == 0){
            $tabName = null;
        }elseif($tag == 1){
            $tabName = 'Side trip';
        }elseif($tag == 2){
            $tabName = 'Nearby';
        }
        $d->parent_id=intval($this->input->post('parent_id'));
        $d->title=$this->input->post('title');
        $d->description=$this->input->post('description');
        $d->entity_type=$this->input->post('entity_type');
        $d->entity_id=intval($this->input->post('entity_id'));
        $d->city_id = intval($this->input->post('city_id'));
        $d->longitude = floatval($this->input->post('longitude'));
        $d->latitude = floatval($this->input->post('latitude'));
        $d->tag = $tabName;
        $d->order_display = intval($this->input->post('order_display'));
        $d->xml_360 = $this->input->post('xml_360');
        if(!empty($d->city_id)){
            $geo_city = new WebGeoCity_Model();
            $geo_city->where('id', $d->city_id)->get();
            $city_ret = $geo_city->to_array();
            $d->country = $city_ret['country_code'];
            $d->state = $city_ret['state_code'];
        }
        else{
            $d->country = $this->input->post('country');
            $d->state = '';
        }

        $this->load->helper('discover_title_helper');

        if( $this->config->item('upload_src') == "s3" ){
            try{
                $s3 = new S3($this->config->item('accessKey'), $this->config->item('secretKey'));

                $base_name = 'media/thingstodo';

                $file_id = 'image';

                $file_name = $_FILES[$file_id]['tmp_name'];

                $file_base_name = $_FILES[$file_id]['name'];

                $ext_info = pathinfo($file_base_name);
                $ext = $ext_info["extension"];

                $clean_title = cleanTitle($d->title);

                $file_base_name = $clean_title.'_'.time().".".$ext;

                $upload_success = $s3->putObjectFile($file_name, $this->config->item('bucketName'),$base_name . '/' .$file_base_name , S3::ACL_PUBLIC_READ);
                if ($upload_success) {
                 // $d->image = 'media/thingstodo/'.$file_base_name;
                 $d->image = $file_base_name;
             }
         }
         catch(Exception $e){
            $status = "error";
            $msg = $e->getMessage();
            echo $msg;exit;
        }
    }
    else{
        $config['upload_path'] = '../media/thingstodo';
        $config['allowed_types'] = 'gif|jpeg|jpg|png';
        $config['max_size'] = 1024 * 10000;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $upload_success = $this->upload->do_upload('image');
    //        echo $this->upload->display_errors('', '');
    //        print_r($upload_data);
        if($upload_success){
            if($d->image != ''){
                $old_image = '../media/thingstodo/'.$d->image;
                if($d->image != 'default.jpg' && $d->image != 'default1.jpg')
                    unlink($old_image);
            }
            $upload_data = $this->upload->data();
            $original_filename = $upload_data['file_name'];
            $extension = explode(".",$upload_data['file_name']);
            
            $clean_title = cleanTitle($d->title);
            $new_name = $clean_title.'_'.time().'.'.end($extension);
            rename('../media/thingstodo/'.$original_filename, '../media/thingstodo/'.$new_name);
            $d->image = $new_name;
        }
    }

    //        else if(!$upload_success){
    //
    //            $original_filename = $d->image;
    //            $extension = explode(".",$original_filename);
    //            $this->load->helper('discover_title_helper');
    //            $clean_title = cleanTitle($d->title);
    //            $new_name = $clean_title.'_'.time().'.'.end($extension);
    //            rename('../media/thingstodo/'.$original_filename, '../media/thingstodo/'.$new_name);
    //            $d->image = $new_name;
    //            
    //        }
    $d->save();
    if($id == ''){
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOPOI_INSERT, $d->id);
    }else{
        $this->load->model('activitylog_model');
        $this->activitylog_model->insert_log($userdata['uid'], THINGSTODOPOI_UPDATE, $d->id);
    }
}

        //   $this->load->model('activitylog_model');
        //   $activity_code = $id == '' ? HOTEL_ROOM_INSERT : HOTEL_ROOM_UPDATE;
        //   $this->activitylog_model->insert_log($userdata['uid'], $activity_code, $d->id);
redirect('thingstodo/view/'.$this->input->post('parent_id'), 'refresh');
}

public function city_search(){
    $term = $this->input->post('term');
    $h = new Thingstodo_Model();
    $h->where('published', 1)->like('title', $term)->get(30);
    $ret = $h->all_to_array();
    $res = array();
    foreach($ret as $item){
        $id = $item['id'];
        $text = $item['title'];            
        $res[] = array('id'=> $id, 'text'=> $text);
    }
    echo json_encode($res);
}
public function citybyid(){
    $id = $this->input->post('id');
    $res = array();
    $h = new Thingstodo_Model();
    $h->where('id', $id)->get();
    $ret = $h->to_array();
    $res = array('id' => $ret['id'], 'text' => $ret['title']);
    echo json_encode($res);
}

public function country_search(){
    $term = $this->input->post('term');
    $h = new Thingstodo_Country_Model();
    $h->where('published', 1)->where('published', 1)->like('title', $term)->get(30);
    $ret = $h->all_to_array();
    $res = array();
    foreach($ret as $item){
        $id = $item['id'];
        $text = $item['title'];            
        $res[] = array('id'=> $id, 'text'=> $text);
    }
    echo json_encode($res);
}
public function countrybyid(){
    $id = $this->input->post('id');
    $res = array();
    $h = new Thingstodo_Country_Model();
    $h->where('id', $id)->get();
    $ret = $h->to_array();
    $res = array('id' => $ret['id'], 'text' => $ret['title']);
    echo json_encode($res);
}

public function region_search(){
    $term = $this->input->post('term');
    $h = new Thingstodo_Region_Model();
    $h->where('published', 1)->where('published', 1)->like('title', $term)->get(30);
    $ret = $h->all_to_array();
    $res = array();
    foreach($ret as $item){
        $id = $item['id'];
        $text = $item['title'];            
        $res[] = array('id'=> $id, 'text'=> $text);
    }
    echo json_encode($res);
}
public function regionbyid(){
    $id = $this->input->post('id');
    $res = array();
    $h = new Thingstodo_Region_Model();
    $h->where('id', $id)->get();
    $ret = $h->to_array();
    $res = array('id' => $ret['id'], 'text' => $ret['title']);
    echo json_encode($res);
}
public function discover_search(){
    $term = $this->input->post('term');
    $entity_type = $this->input->post('type');
    $city_id = $this->input->post('city_id');
    switch($entity_type){
        case 28:
        $h = new Hotel_Model();
        $h->where('published', 1)->where('city_id', $city_id)->like('title', $term)->get(30);
        $ret = $h->all_to_array();
        break;
        case 29:
        $h = new Global_Restaurant_Model();
        $h->where('published', 1)->like('name', $term)->get(30);
        $ret = $h->all_to_array();
        break;
        case 30:
        $h = new Poi_Model();
        $h->where('published', 1)->where('city_id', $city_id)->like('name', $term)->get(30);
        $ret = $h->all_to_array();
        break;
        case 63:
        $h = new Airport_Model();
        $h->where('published', 1)->where('city_id', $city_id)->like('name', $term)->get(30);
        $ret = $h->all_to_array();
        break;
    }
    $res = array();
    foreach($ret as $item){
        $id = $item['id'];
        $text = '';
        if($entity_type == 28){
            $text = $item['title'];
        }
        else{
            $text = $item['name'];
        }
        $res[] = array('id'=> $id, 'text'=> $text);
    }
    echo json_encode($res);
}

public function discoverbyid(){
    $id = $this->input->post('id');
    $entity_type = $this->input->post('type');
    $res = array();
    switch($entity_type){
        case 28:
        $h = new Hotel_Model();
        $h->where('id', $id)->get();
        $ret = $h->to_array();
        $res = array('id' => $ret['id'], 'text' => $ret['title']);
        break;
        case 29:
        $h = new Global_Restaurant_Model();
        $h->where('id', $id)->get();
        $ret = $h->to_array();
        $res = array('id' => $ret['id'], 'text' => $ret['name']);
        break;
        case 30:
        $h = new Poi_Model();
        $h->where('id', $id)->get();
        $ret = $h->to_array();
        $res = array('id' => $ret['id'], 'text' => $ret['name']);
        break;
        case 63:
        $h = new Airport_Model();
        $h->where('id', $id)->get();
        $ret = $h->to_array();
        $res = array('id' => $ret['id'], 'text' => $ret['name']);
        break;
    }
    echo json_encode($res);
}

function ajax_lang_addregion($id,$lang){
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $thinstodo_id = $id;        
    $r = new Thingstodo_Region_Model();
        //$r->where(array('language' => $lang, 'parent_id'=> $thinstodo_id));
       // $r->details->get();
    $count = $r->where(array('language' => $lang, 'parent_id'=> $thinstodo_id))->count();
    if($count > 0){
        return;
    }
    $h = new Thingstodo_Region_Model();
    $h->where(array('id' => $thinstodo_id, 'parent_id'=> 0))->get();
        //$h->details->get();
    $thingstodo= $h->to_array();
		//print_r($thingstodo);
		//echo $thingstodo['title'];
    $this->load->helper('discover_title_helper');
    $h_lang = new Thingstodo_Region_Model();
    $h_lang->title = $thingstodo['title'];
    $h_lang->h2 = $thingstodo['h2'];
    $h_lang->h3 = $thingstodo['h3'];
    $h_lang->description = $thingstodo['description'];
    $h_lang->language = $lang;
    $h_lang->parent_id = $thinstodo_id;
    $image= $thingstodo['image'];
    $basic_path = '../media/thingstodo/';
    $image_path = $basic_path.$image;
    $preg = $h->language != 'in';
    $clean_title = cleanTitle($thingstodo['title'],$preg);
    if (file_exists($image_path)) 
    {
        $extension = explode(".", $image);           
        
        $lang_new_name = $clean_title.'_'.time().'_'.$lang.'.'.end($extension);

        $new_name_path = $basic_path.$lang_new_name;

        if(copy($image_path, $new_name_path)){ 
            $h_lang->image = $lang_new_name;
        }  
    }
    $h_lang->save();		
    $alias = new Alias_Model();
    $alias->alias = $clean_title.'-'.$lang;
    $alias->language = 'en';
    $alias->entity_type = 'things-to-do-region';
    $alias->entity_id = 'id/'.$h_lang->id;
    $alias->save();
    $h_lang->alias_id = $alias->id;
    $h_lang->save();
    $rid= $h_lang->id;
    $r = new Thingstodo_Model();
    $r->where(array('language' => $lang, 'parent_id'=> $thinstodo_id))->get();
		$rdetais_items=$r->all_to_array();//print_r($rdetais_items); 
        foreach($rdetais_items as $rdetais_item){
			//print_r($rdetais_item);
			//echo "<br />";echo $thinstodo_id;echo "<br />";
			//echo $rdetais_item['id'];
			//echo "<br />";
         $r = new Thingstodo_Model();
         $r->where(array('language' => $lang, 'parent_id'=> $thinstodo_id))->get();
         $r->details->get();
         $count = $r->where(array('language' => $lang, 'parent_id'=> $thinstodo_id))->count();
         if($count > 0){
            return;
        }
        $h = new Thingstodo_Model();
        $h->where(array('id' => $rdetais_item['id'], 'parent_id'=> $thinstodo_id))->get();
			//$h->details->get();
        $thingstodo= $h->to_array();
        if(count($thingstodo)>0){
           $this->load->helper('discover_title_helper');
           $h_lang = new Thingstodo_Model();
           $h_lang->title = $thingstodo['title'];
           $h_lang->h2 = $thingstodo['h2'];
           $h_lang->h3 = $thingstodo['h3'];
           $h_lang->description = $thingstodo['description'];
           $h_lang->parent_id = $thinstodo_id;
           $image= $thingstodo['image'];
           $basic_path = '../media/thingstodo/';
           $image_path = $basic_path.$image;
           if (file_exists($image_path)) 
           {
               $extension = explode(".", $image);
               $preg = true;			  
               $clean_title = cleanTitle($thingstodo['title'],$preg);				
               $lang_new_name = $clean_title.'_'.time().'_'.$lang.'.'.end($extension);
               $new_name_path = $basic_path.$lang_new_name;
               if(copy($image_path, $new_name_path)){ 
                  $h_lang->image = $lang_new_name;
              }  
          }
          $h_lang->save();
          $alias = new Alias_Model();
          $alias->alias = $clean_title.'-'.$lang;
          $alias->language = 'en';
          $alias->entity_type = 'top-things-to-do';
          $alias->entity_id = 'id/'.$h_lang->id;
          $alias->save();
          $h_lang->alias_id = $alias->id;
          $h_lang->save();
          $h_details=new Thingstodo_Details_Model();
          $h_details->where('parent_id', $thinstodo_id)->get();
          $detais_items=$h_details->all_to_array();        
          foreach($detais_items as $detais_item){				
           $h_new_details = new Thingstodo_Details_Model();
           $h_new_details->title= $detais_item['title'];
           $h_new_details->description= $detais_item['description'];
           $h_new_details->entity_id= $detais_item['entity_id'];
           $h_new_details->parent_id= $h_lang->id;
           $h_new_details->entity_type= $detais_item['entity_type'];
           $h_new_details->city_id= $detais_item['city_id'];
           $h_new_details->country= $detais_item['country'];
           $h_new_details->state= $detais_item['state'];
           $image_details= $detais_item['image'];
           $image_path_details = $basic_path.$image_details;
           if (file_exists($image_path_details)) 
           {
              $extension = explode(".", $image_details);
              $clean_title_details=cleanTitle($detais_item['title'], $preg);

              $lang_new_name_details = $clean_title_details.'_'.time().'.'.end($extension);

              $new_name_path_details = $basic_path.$lang_new_name_details;

              if(copy($image_path_details, $new_name_path_details)){ 
                 $h_new_details->image = $lang_new_name_details;
             }  
         }
         $h_new_details->save();
     }
 }
			//print_r($thingstodo);echo $thingstodo['title'];
}
}

function ajax_lang_add(){		
    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $thinstodo_id = $this->input->get('id');
    $lang = $this->input->get('lang');
    if($this->input->get('type')=='tregion'){		
     $rid = $this->ajax_lang_addregion($thinstodo_id,$lang);
 }else{		
     $r = new Thingstodo_Model();
     $r->where(array('language' => $lang, 'parent_id'=> $thinstodo_id));
     $r->details->get();
     $count = $r->count();
     if($count > 0){
        return;
    }			
    $h = new Thingstodo_Model();
    $h->get_where(array('id' => $thinstodo_id, 'parent_id'=> 0));
    $h->details->get();
    $thingstodo= $h->to_array();		
    $this->load->helper('discover_title_helper');
    $h_lang = new Thingstodo_Model();
    $h_lang->title = $thingstodo['title'];
    $h_lang->h2 = $thingstodo['h2'];
    $h_lang->h3 = $thingstodo['h3'];
    $h_lang->description = $thingstodo['description'];
    $h_lang->parent_id = $thingstodo['parent_id'];
    $image= $thingstodo['image'];
    $basic_path = '../media/thingstodo/';
    $image_path = $basic_path.$image;
    if (file_exists($image_path)) 
    {
        $extension = explode(".", $image);
        $preg = true;		  
        $clean_title = cleanTitle($thingstodo['title'],$preg);			
        $lang_new_name = $clean_title.'_'.time().'_'.$lang.'.'.end($extension);
        $new_name_path = $basic_path.$lang_new_name;
        if(copy($image_path, $new_name_path)){ 
           $h_lang->image = $lang_new_name;
       }  
   }
   $h_lang->save();
   $alias = new Alias_Model();
   $alias->alias = $clean_title.'-'.$lang;
   $alias->language = 'en';
   $alias->entity_type = 'top-things-to-do';
   $alias->entity_id = 'id/'.$h_lang->id;
   $alias->save();
   $h_lang->alias_id = $alias->id;
   $h_lang->save();
   /* start copy of Thingstodo_Details */
   $h_details=new Thingstodo_Details_Model();
   $h_details->where('parent_id', $thinstodo_id)->get();
   $detais_items=$h_details->all_to_array();        
   foreach($detais_items as $detais_item){

    $h_new_details = new Thingstodo_Details_Model();
    $h_new_details->title= $detais_item['title'];
    $h_new_details->description= $detais_item['description'];
    $h_new_details->entity_id= $detais_item['entity_id'];
    $h_new_details->parent_id= $h_lang->id;
    $h_new_details->entity_type= $detais_item['entity_type'];
    $h_new_details->city_id= $detais_item['city_id'];
    $h_new_details->country= $detais_item['country'];
    $h_new_details->state= $detais_item['state'];
    $image_details= $detais_item['image'];
    $image_path_details = $basic_path.$image_details;
    if (file_exists($image_path_details)) 
    {
       $extension = explode(".", $image_details);
       $clean_title_details=cleanTitle($detais_item['title'], $preg);

       $lang_new_name_details = $clean_title_details.'_'.time().'.'.end($extension);

       $new_name_path_details = $basic_path.$lang_new_name_details;

       if(copy($image_path_details, $new_name_path_details)){ 
          $h_new_details->image = $lang_new_name_details;
      }  
  }
  $h_new_details->save();
}
}
       // echo json_encode(array('success' => $rid));
        //redirect('thingstodo/view/'.$h->id, 'refresh');        
}	
}