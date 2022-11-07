<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_ml_discover_cuisine_title')){

    function get_ml_discover_cuisine_title($id,$lang){
        $CI =& get_instance();
        $query = $CI->db->select('ml_discover_cuisine.title as title')
                ->from('discover_cuisine')
                ->join('ml_discover_cuisine', 'ml_discover_cuisine.entity_id = discover_cuisine.id', 'left')
                ->where('ml_discover_cuisine.entity_id', $id)
                ->where('ml_discover_cuisine.lang_code', $lang)
                ->get();
        return $query->row()->title;
       
    }
}