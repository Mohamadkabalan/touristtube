<?php
class Restaurant_Image_Model extends CI_Model {
 
    public function insert_file($filename, $id)
    {
        $data = array(
            'filename'      => $filename,
            'restaurant_id'         => $id
        );
        $this->db->insert('discover_restaurants_images', $data);
        return $this->db->insert_id();
    }
    public function get_files($id)
   {
       return $this->db->select()
               ->from('discover_restaurants_images')
               ->where('restaurant_id', $id)
               ->get()
               ->result();
   }

   public function delete_file($file_id)
   {
       $file = $this->get_file($file_id);
       if (!$this->db->where('id', $file_id)->delete('discover_restaurants_images'))
       {
           return FALSE;
       }
        $new_file = explode("_", $file->filename);
        unlink('../media/discover/' . $file->filename);
        unlink('../media/discover/thumb/' . $file->filename);
        unlink('../media/discover/large/' . $file->filename);
        unlink('../media/discover/' . end($new_file));   
       return TRUE;
   }
    public function get_file($file_id)
   {
       return $this->db->select()
               ->from('discover_restaurants_images')
               ->where('id', $file_id)
               ->get()
               ->row();
   }

    public function update_default_pic($restaurant_id,$image_id){
        $default_pic = array('default_pic'=> 0);
        $update_default_pic= array('default_pic'=> 1);
        $this->db->where('restaurant_id', $restaurant_id);
        $result = $this->db->update('discover_restaurants_images', $default_pic);
        if($result == TRUE){
            $this->db->where('restaurant_id', $restaurant_id);
            $this->db->where('id', $image_id);
            $result = $this->db->update('discover_restaurants_images', $update_default_pic);
            $result = TRUE;
        }
        return $result;
    }
} 