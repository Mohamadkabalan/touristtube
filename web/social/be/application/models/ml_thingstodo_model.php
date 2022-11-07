<?php
class Ml_Thingstodo_Model extends DataMapper {
    var $table = 'ml_thingstodo';
    function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
}