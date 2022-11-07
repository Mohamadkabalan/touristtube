<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_cms_users_YourUserName')){

    function get_cms_users_YourUserName($entity_id){
        $CI =& get_instance();
        $query = $CI->db->select('cms_users.YourUserName')
                ->from('cms_users')
                ->join('cms_report', 'cms_report.entity_id = cms_users.id', 'left')
                ->where('cms_users.id', $entity_id)
                ->get();
        return $query->row()->YourUserName;
       
    }
}

    function get_cms_videos_title($entity_id){
        $CI =& get_instance();
        $query = $CI->db->select('cms_videos.title')
                ->from('cms_videos')
                ->join('cms_report', 'cms_report.entity_id = cms_videos.id', 'left')
                ->where('cms_videos.id', $entity_id)
                ->get();
        return $query->row()->title;
       
    }
    
    function get_cms_channel_channel_name($entity_id){
        $CI =& get_instance();
        $query = $CI->db->select('cms_channel.channel_name')
                ->from('cms_channel')
                ->join('cms_report', 'cms_report.entity_id = cms_channel.id', 'left')
                ->where('cms_channel.id', $entity_id)
                ->get();
        return $query->row()->channel_name;
       
    }
    
    function get_cms_users_event_name($entity_id){
        $CI =& get_instance();
        $query = $CI->db->select('cms_users_event.name')
                ->from('cms_users_event')
                ->join('cms_report', 'cms_report.entity_id = cms_users_event.id', 'left')
                ->where('cms_users_event.id', $entity_id)
                ->get();
        return $query->row()->name;
       
    }
    
    function get_cms_channel_event_name($entity_id){
        $CI =& get_instance();
        $query = $CI->db->select('cms_channel_event.name')
                ->from('cms_channel_event')
                ->join('cms_report', 'cms_report.entity_id = cms_channel_event.id', 'left')
                ->where('cms_channel_event.id', $entity_id)
                ->get();
        return $query->row()->name;
       
    }
    
    function get_cms_report_reason_reasontitle($reason_array){
        $CI =& get_instance();
        $query = $CI->db->select('cms_report_reason.reason')
                ->from('cms_report_reason')
                ->where("`id` IN ($reason_array)", NULL, FALSE)
                ->get();
        return $query->result_array();
    }
    
    