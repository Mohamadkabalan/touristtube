<?php
class Cms_Hotel_Image_Model extends DataMapper {
    var $table = 'cms_hotel_image';
    var $has_one = array(
        'hotel' => array(	
            'class' => 'cms_hotel_model',
            'other_field' => 'image',
            'join_self_as' => 'image',
            'join_other_as' => 'hotel'
        )
    );
  
  function __construct($id = NULL)
    {
      parent::__construct($id);
    }
    function post_model_init($from_cache = FALSE)
    {
      
    }
    public function insert_file($filename,$location, $id)
    {
        $data = array(
            'filename'      => $filename,
            'location'      => $location,
            'hotel_id'      => $id
        );
        $this->db->insert('cms_hotel_image', $data);
        return $this->db->insert_id();
    }
    
    public function update_default_pic($hotel_id,$image_id){
        $default_pic = array('default_pic'=> 0);
        $update_default_pic= array('default_pic'=> 1);
        $this->db->where('hotel_id', $hotel_id);
        $this->db->where('tt_media_type_id', 1);
        $result = $this->db->update('cms_hotel_image', $default_pic);
        if($result == TRUE){
            $this->db->where('hotel_id', $hotel_id);
            $this->db->where('id', $image_id);
            $result = $this->db->update('cms_hotel_image', $update_default_pic);
            $result = TRUE;
        }
        return $result;
    }
    
    public function update_location_pic($image_id,$location,$new_location){
        $locations= array('location'=> $new_location);
        $this->db->where('location', $location);
        $this->db->where('id', $image_id);
        $result = $this->db->update('cms_hotel_image', $locations);
        $result = TRUE;
        return $result;
    }

}