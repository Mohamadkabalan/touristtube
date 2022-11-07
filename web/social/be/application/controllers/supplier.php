<?php 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Supplier extends MY_Controller {

    function index(){
        $session_data = $this->session->userdata('logged_in');
        $this->load->model('cms_countries_model');
        $data['username'] = $session_data['username'];
        $data['title']= 'Manage suppliers';
        $data['content']= 'supplier/list';
        $s = new Deal_Supplier_Model();
        $s->get();
        $data['suppliers'] = $s->all_to_array();
       
        $u = new User_Model();
        $i=0;
        foreach($s as $user){
            $ret = $this->cms_countries_model->getbycode($user->country);
            if($ret){
              $data['suppliers'][$i]['country_name'] = $ret['text'];
            }else{
              $data['suppliers'][$i]['country_name'] = "";
            }
            
            $u->where('id', $user->user_id)->get();
            $data['suppliers'][$i]['user'] = $u->to_array();
            $i++; 
            
        }
        
        $this->load->view('template', $data);
    }
    
    
    public function add(){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Add Supplier';
        $data['content']= 'supplier/form';
        $data['cssIncludes'] = array('supplier_view.css');
        $data['jsIncludes'] = array('supplier.js');
        $this->load->view('template', $data);
    }
    
    public function edit($id){
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Edit Supplier';
        $data['content']= 'supplier/form';
        $data['cssIncludes'] = array('supplier_view.css');
        $data['jsIncludes'] = array('supplier.js');
        $s = new Deal_Supplier_Model();
        $s->where('id', $id)->get();
        $data['supplier'] = $s->to_array();
      
        $u = new User_Model();
        $u->where('id', $data['supplier']['user_id'])->get();
        $data['user'] = $u->to_array();
        $this->load->view('template', $data);
    }
    
    public function submit(){
        $userdata = $this->session->userdata('logged_in');
        $this->load->model('cms_countries_model');
        $id = $this->input->post('id');
        $this->form_validation->set_error_delimiters('<em>&nbsp;</em><span> &nbsp;</span><p class="errormsg">', '</p>');
        $this->form_validation->set_rules('fname', 'first name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lname', 'last name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('country_code', 'country_code', 'trim|required|xss_clean');
        if($id){
          $this->form_validation->set_rules('password', 'password', 'trim|xss_clean');
        }else{
            $this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
        }
        
        $this->form_validation->set_rules('role', 'role', 'trim|required|xss_clean');
        if ($this->form_validation->run() == TRUE) {
            $password = $this->input->post('password');
            $role = $this->input->post('role');
            $u = new User_Model();
            if($id <> ''){
                $u->where('id', $id)->get();
            }
            $u->fname = $this->input->post('fname');
            $u->lname = $this->input->post('lname');
            $u->username = $this->input->post('username');
            if($password <> ''){
                $u->password = MD5($password);
            }
                    // added the user translator admin role in below condition by Sushma Mishra on 28-08-2015
            if($role <> 'admin' && $role <> 'editor' && $role <> 'translator' && $role <> 'user_translator' && $role <> 'user_translator_admin' && $role <> 'dealer' && $role <> 'copywriter' && $role <> 'hotel_chain')
                $role = 'editor';
            $u->role = $role;
            $u->save();
            $user_id = $u->id;
            //save data in supplier table
            
            $s = new Deal_Supplier_Model();
            $s->user_id = $user_id;
            $s->name = $u->fname .' '. $u->lname; 
            $s->email = $this->input->post('email');
            $s->country = $this->input->post('country_code');
            $s->save(); 
            $supplier_id = $s->id;
           
            $s->where('id', $supplier_id)->get();
            $data['supplier']= $s->to_array();   
            $s->code = $this->cms_countries_model->getContinentByCode($this->input->post('country_code')) . $this->input->post('country_code') . $data['supplier']['id'];
            $s->save();
            
            redirect('supplier/view/'.$s->id, 'refresh');
        }else{
             if($id <> ''){
                    $data['title']= 'Edit Supplier';
                    $h = new User_Model();
                    $h->where('id', $id)->get();
                    $data['user']= $h->to_array();
                }else{
                   $data['title']= 'Add Supplier'; 
                }
                $data['content']= 'supplier/form';
                $data['cssIncludes'] = array('supplier_view.css');
                $data['jsIncludes'] = array('supplier.js');
                $this->load->view('template', $data);
        }
     }
     
     function view($id){
        $session_data = $this->session->userdata('logged_in');
        $this->load->model('cms_countries_model');
        $data['username'] = $session_data['username'];
        $data['title']= 'View Supplier';
        $data['content']= 'supplier/view';
        $s = new Deal_Supplier_Model();
        $s->where('id', $id)->get();
        $data['supplier'] = $s->to_array();
        
        $ret = $this->cms_countries_model->getbycode($data['supplier']['country']);
         if($ret){
            $data['supplier']['country_code'] = $ret['text'];
         }else{
            $data['supplier']['country_code'] = "";
         }
        $u = new User_Model();
        $u->where('id', $data['supplier']['user_id'])->get();
        $data['user'] = $u->to_array();
        
        $this->load->view('template', $data);
    }
    
      function ajax_delete($id,$user_id){
        $session_data = $this->session->userdata('logged_in');
        if(!$session_data || !$session_data['is_admin'])
            return;
        
        $success = FALSE;
        if($id <>'' && $user_id <>'') {
            $u = new User_Model();
            $u->where('id', $user_id )->get();
            $u->delete();
            
            $d = new Deal_Supplier_Model();
            $d->where('id', $id )->get();
            $success = $d->delete();
        }
        echo json_encode (array('success' => $success));
    }
    
}