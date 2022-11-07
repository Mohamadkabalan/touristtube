<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_ml_channel_category_title')){

    function get_ml_channel_category_title($id,$lang){
        $CI =& get_instance();
        $query = $CI->db->select('ml_channel_category.title as title')
                ->from('cms_channel_category')
                ->join('ml_channel_category', 'ml_channel_category.entity_id = cms_channel_category.id', 'left')
                ->where('ml_channel_category.entity_id', $id)
                ->where('ml_channel_category.lang_code', $lang)
                ->get();
        return $query->row()->title;
       
    }
}