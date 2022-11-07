<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
public function report() {
    $sql = "SELECT `cms_report`. * , users.`FullName` AS reporting_user, owners.`FullName` AS owner, `cms_videos`.`title` AS vedio_title, `cms_channel`.`channel_name` AS channel_names, `cms_report_reason`.`reason` AS reason_title
            FROM `cms_report`
            LEFT JOIN `cms_users` AS users ON users.`id` = `cms_report`.`user_id`
            LEFT JOIN `cms_users` AS owners ON owners.`id` = `cms_report`.`owner_id`
            LEFT JOIN `cms_videos` ON `cms_videos`.`id` = `cms_report`.`entity_id`
            AND `cms_report`.`entity_type` = '1'
            LEFT JOIN `cms_channel` ON `cms_channel`.`id` = `cms_report`.`channel_id`
            AND `cms_report`.`channel_id` != '0'
            LEFT JOIN `cms_report_reason` ON `cms_report_reason`.`id` = `cms_report`.`reason`
            GROUP BY `cms_report`.`id`";
    $query = $this->db->query($sql)->result();
    return $query;
   }

}

     