<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_Tubers_Login_Tracking_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function log_report($start, $limit) {
        //$sql = "SELECT * from `cms_tubers_login_tracking`";
        $sql = "SELECT `cms_tubers_login_tracking`. * , `cms_users`.`FullName` AS user_name
                FROM `cms_tubers_login_tracking` 
                LEFT JOIN `cms_users` ON `cms_users`.`id` = `cms_tubers_login_tracking`.`user_id` LIMIT $start, $limit";
        $query = $this->db->query($sql)->result();
        return $query;
    }

    public function log_report_total(){
        $sql = "SELECT count(id) as cnt FROM cms_tubers_login_tracking";
        $query = $this->db->query($sql)->result();
        return $query;
    }
}



     