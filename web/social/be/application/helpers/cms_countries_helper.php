<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('countrygetbyid')){

   /*function get_ml_report_reason_title($id,$lang){
        $CI =& get_instance();
        $query = $CI->db->select('ml_report_reason.title as title')
                ->from('cms_report_reason')
                ->join('ml_report_reason', 'ml_report_reason.entity_id = cms_report_reason.id', 'left')
                ->where('ml_report_reason.entity_id', $id)
                ->where('ml_report_reason.lang_code', $lang)
                ->get();
        return $query->row()->title;
       
    }*/
    
    function countrygetbyid($id){
        $CI =& get_instance();
        $query = $CI->db->select('cms_countries.id, cms_countries.code as code, cms_countries.name as name')
                    ->from('cms_countries')
                    ->where('cms_countries.id', $id)
                    ->limit(1)
                    ->get()
                    ->result();
        $ret = array();
        if(count($query) > 0){
            $ret['id'] = $query[0]->id;
            $ret['code'] = $query[0]->code;
            $ret['text'] = $query[0]->name;
        }
        return $ret;
    }
}