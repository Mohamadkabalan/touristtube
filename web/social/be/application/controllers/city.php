<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City extends CI_Controller {
    
    public function search(){
        $term = $this->input->post('term');
        $country = $this->input->post('country');
        $this->load->model('city_search_model');
        $ret = $this->city_search_model->search($term, $country);
        echo json_encode($ret);
    }
    
    public function search_country_id(){
        $term = $this->input->post('term');
        $country_id = $this->input->post('country_id');
        $this->load->model('city_search_model');
        $ret = $this->city_search_model->search_country_id($term, $country_id);
        echo json_encode($ret);
    }
    
    public function getbyid(){
        $id = $this->input->post('id');
        $this->load->model('city_search_model');
        $ret = $this->city_search_model->getbyid($id);
        echo json_encode($ret);
    }
    
    public function getbycountryid(){
        $id = $this->input->post('id');
        $this->load->model('cms_countries_model');
        $ret = $this->cms_countries_model->getbyid($id);
        echo json_encode($ret);
    }
    
    public function getbycountrycode(){
        $code = $this->input->post('code');
        $this->load->model('cms_countries_model');
        $ret = $this->cms_countries_model->getbycode($code);
        echo json_encode($ret);
    }
    
    public function country_search_id(){
        $term = $this->input->post('term');
        $this->load->model('cms_countries_model');
        $ret = $this->cms_countries_model->id_search($term);
        echo json_encode($ret);
    }
    
    public function country_search(){
        $term = $this->input->post('term');
        $this->load->model('cms_countries_model');
        $ret = $this->cms_countries_model->search($term);
        echo json_encode($ret);
    }
    
    public function getbystateid(){
        $id = $this->input->post('id');
        $this->load->model('states_model');
        $ret = $this->states_model->getbyid($id);
        echo json_encode($ret);
    }
    
    public function state_search(){
        $term = $this->input->post('term');
        $country = $this->input->post('country');
        $this->load->model('states_model');
        $ret = $this->states_model->search($term, $country);
        echo json_encode($ret);
    }
    
    public function state_search_country_id(){
        $term = $this->input->post('term');
        $country_id = $this->input->post('country_id');
        $this->load->model('states_model');
        $ret = $this->states_model->search_country_id($term, $country_id);
        echo json_encode($ret);
    }
}