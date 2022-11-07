<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deal_firas extends MY_Controller {
    
    public function packagesproducts(){
        $this->load->helper('deal_firas_helper');
        $packages = getPackageProduct();
        echo '<pre>';
        var_dump($packages);
        echo '</pre>';
        exit;
         $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Deal view destination';
         $data['cssIncludes'] = array('deal.css');
        $data['packages']= $packages;
        $this->load->view('deal/packages_firas', $data);
    }
}
