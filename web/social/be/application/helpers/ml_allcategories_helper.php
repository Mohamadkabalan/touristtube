<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_ml_allcategories_title')){

    function get_ml_allcategories_title($id,$lang){
        $CI =& get_instance();
        $query = $CI->db->select('ml_allcategories.title as title')
                ->from('cms_allcategories')
                ->join('ml_allcategories', 'ml_allcategories.entity_id = cms_allcategories.id', 'left')
                ->where('ml_allcategories.entity_id', $id)
                ->where('ml_allcategories.lang_code', $lang)
                ->get();
        return $query->row()->title;
       
    }
}