<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deal_Supplier_Model extends DataMapper{
    var $table = 'deal_supplier';
    function __construct($id = NULL)
    {
        parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}

