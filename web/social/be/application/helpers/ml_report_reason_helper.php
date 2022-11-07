<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_ml_report_reason_title')){

    function get_ml_report_reason_title($id,$lang){
        $CI =& get_instance();
        $query = $CI->db->select('ml_report_reason.title as title')
                ->from('cms_report_reason')
                ->join('ml_report_reason', 'ml_report_reason.entity_id = cms_report_reason.id', 'left')
                ->where('ml_report_reason.entity_id', $id)
                ->where('ml_report_reason.lang_code', $lang)
                ->get();
        return $query->row()->title;
       
    }
}