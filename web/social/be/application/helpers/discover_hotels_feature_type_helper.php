<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_discover_hotels_feature_type_title')){

    function get_discover_hotels_feature_type_title($id){
        $CI =& get_instance();
        $query = $CI->db->select('discover_hotels_feature_type.title')
                ->from('discover_hotels_feature_type')
                ->join('discover_hotels_feature', 'discover_hotels_feature.feature_type = discover_hotels_feature_type.id', 'left')
                ->where('discover_hotels_feature_type.id', $id)
                ->get();
        return $query->row()->title;
       
    }
    
    function get_facility_type_title($id){
        $CI =& get_instance();
        $query = $CI->db->select('cms_facility_type.name')
                ->from('cms_facility_type')
                ->join('cms_facility', 'cms_facility.type_id = cms_facility_type.id', 'left')
                ->where('cms_facility_type.id', $id)
                ->get();
        return $query->row()->name;
       
    }
}