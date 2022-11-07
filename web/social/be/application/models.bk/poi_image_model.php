<?php
class Poi_Image_Model extends CI_Model {
 
    public function insert_file($filename, $id)
    {
        $data = array(
            'filename'      => $filename,
            'poi_id'         => $id
        );
        $this->db->insert('discover_poi_images', $data);
        return $this->db->insert_id();
    }
 public function get_files($id)
{
    return $this->db->select()
            ->from('discover_poi_images')
            ->where('poi_id', $id)
            ->get()
            ->result();
}

public function delete_file($file_id)
{
    $file = $this->get_file($file_id);
    if (!$this->db->where('id', $file_id)->delete('discover_poi_images'))
    {
        return FALSE;
    }
    unlink('./uploads/' . $file->filename);   
    return TRUE;
}
 public function get_file($file_id)
{
    return $this->db->select()
            ->from('discover_poi_images')
            ->where('id', $file_id)
            ->get()
            ->row();
}

}