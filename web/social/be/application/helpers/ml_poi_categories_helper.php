<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_ml_poi_categories_title')){

    function get_ml_report_reason_title($id,$lang){
        $CI =& get_instance();
        $query = $CI->db->select('ml_poi_categories.title as title')
                ->from('discover_categs')
                ->join('ml_poi_categories', 'ml_poi_categories.entity_id = discover_categs.id', 'left')
                ->where('ml_poi_categories.entity_id', $id)
                ->where('ml_poi_categories.lang_code', $lang)
                ->get();
        return $query->row()->title;
       
    }
}