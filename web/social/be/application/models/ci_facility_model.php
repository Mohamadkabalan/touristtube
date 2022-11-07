<?php
class Ci_Facility_Model extends CI_Model {
    
    public function hotel_facilities($hotel_id){
        $ret = array();
        $i = 0;
        $hotel_facilities_res = $this->db->select('f.id as id, f.name as name, f.type_id, t.name as type_name, (CASE WHEN hf.hotel_id IS NULL THEN 0 ELSE 1 END) AS selected')
                ->from('cms_facility as f')
                ->join('cms_facility_type as t', "f.type_id = t.id", 'inner')
                ->join('cms_hotel_facility as hf', "f.id = hf.facility_id AND hf.hotel_id = $hotel_id", 'left')
                ->where('f.published = 1')
                ->get()
                ->result();
        foreach($hotel_facilities_res as $row){
            $ret[$i]['id'] = $row->id;
            $ret[$i]['name'] = $row->name;
            $ret[$i]['type_id'] =  $row->type_id;
            $ret[$i]['type_name'] = $row->type_name;
            $ret[$i]['selected'] = $row->selected;
            $i++;
        }
        return $ret;
    }
    
    public function delete_hotel_facilities($hotel_id, $facility_ids){
        if(intval($hotel_id) == 0 || !is_array($facility_ids) || count($facility_ids) == 0){
            return;
        }
        foreach($facility_ids as $id){
            $data = array(
                'hotel_id' => $hotel_id,
                'facility_id' => $id
            );
            $this->db->delete('cms_hotel_facility', $data);
        }
    }
    
    public function add_hotel_facilites($hotel_id, $facility_ids){
        if(intval($hotel_id) == 0 || !is_array($facility_ids) || count($facility_ids) == 0){
            return;
        }
        foreach($facility_ids as $id){
            $data = array(
                'hotel_id' => $hotel_id,
                'facility_id' => $id
            );
            $this->db->insert('cms_hotel_facility', $data);
        }
    }
}