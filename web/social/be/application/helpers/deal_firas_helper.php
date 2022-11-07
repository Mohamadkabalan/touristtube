<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 function getPackageProduct( ){
//    $url = "http://api.amazingchina.com/api/PackageProduct";
//    $username = "OTA000046";
//    $password = "AMC20160606002";
//    
//    $ch = curl_init($url);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
//    $results = curl_exec($ch);
//    curl_close ($ch);
//    
        $CI =& get_instance();
//        $query = $CI->db->select('roger.j')
//                ->from('roger')
//                ->where('roger.a', 'a')
//                ->get();
//        $result =  $query->row()->j;
        $privacy = array('j' => json_encode(array('hotels' => array(1, 2), 'restaurants' => array(100, 200))));      
        $CI->db->where('roger.a', 'a');
        $CI->db->update('roger', $privacy);
                 

//        $result_decode = json_decode($result);
        return "true";
//    if(isset($results) && !empty($results) ){
//        $results_decoded = json_decode($results);
//        return $results_decoded;
//    }
//     return "No Results from API calling";
 }