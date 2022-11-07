<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HotelSelection extends MY_Controller {
 public function index(){//echo "hi";exit;
		$this->load->model('cms_hotels_selections_model');
        $this->load->library('pagination');
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Hotels Selections';
        $data['content']= 'hotel/hotelSelection';
        $config['uri_segment'] = 3;
        $config['per_page'] = 20;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotel/ajax_hotelSelection';
		$a = new Cms_Hotels_Selections_Model();
        $total = $a->count();
		$res = $a->order_by('id')->get();
	//echo "<pre>";	print_r($a->all_to_array());  exit;
        $config['total_rows'] = $total;        
        $data['aitems']= $a;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
		//echo "<pre>";	print_r($data);  exit;
        $this->load->view('template', $data);
	}
	
	public function ajax_hotelSelection($start = 0){
        $this->load->model('cms_hotels_selections_model');
        $config['uri_segment'] = 3;
        $config['per_page'] = 500;
        $config["num_links"] = 14;
        $config['base_url'] = 'hotel/ajax_hotelSelection';
        $a = new Cms_Hotels_Selections_Model();
        $total = $a->count();
        $config['total_rows'] = $total;
        $limit = $total;
		$res = $a->order_by('id')->get(500, $start);
        $data['aitems']= $a;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $this->load->view('hotel/ajax_hotelSelection', $data);
    }

}
