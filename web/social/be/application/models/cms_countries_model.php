<?php
class Cms_Countries_Model extends CI_Model {
    
     function get_all_countries_name(){
//        $query = $this->db->select('discover_hotels.countryCode as hotelCountryCode, cms_countries.name as hotelCountryName')
//                    ->from('cms_countries')
//                    ->join('discover_hotels', 'discover_hotels.countryCode = cms_countries.code')
//                    ->get()
//                    ->result();
//        return $query;
        
        $query = $this->db->select('cms_countries.code as hotelCountryCode, cms_countries.name as hotelCountryName')
                    ->from('cms_countries')
                    ->order_by('cms_countries.name', 'asc')
                    ->get()
                    ->result();
        return $query;
    }

    function search($term){
        $ret = array();
        $i = 0;
        $query = $this->db->select('cms_countries.code as code, cms_countries.name as name')
                    ->from('cms_countries')
                    ->like('cms_countries.name', $term, 'after')
                    ->order_by('cms_countries.name', 'asc')
                    ->get()
                    ->result();
        foreach($query as $row){
            $ret[$i]['id'] = $row->code;
            $ret[$i]['text'] =  $row->name;
            $i++;
        }
        return $ret;
    }
    
    function id_search($term){
        $ret = array();
        $i = 0;
        $query = $this->db->select('cms_countries.id, cms_countries.code as code, cms_countries.name as name')
                    ->from('cms_countries')
                    ->like('cms_countries.name', $term, 'after')
                    ->order_by('cms_countries.name', 'asc')
                    ->get()
                    ->result();
        foreach($query as $row){
            $ret[$i]['id'] = $row->id;
            $ret[$i]['code'] = $row->code;
            $ret[$i]['text'] =  $row->name;
            $i++;
        }
        return $ret;
    }
    
    public function getbycode($code){
        $query = $this->db->select('cms_countries.id, cms_countries.code as code, cms_countries.name as name')
                    ->from('cms_countries')
                    ->where('cms_countries.code', $code)
                    ->limit(1)
                    ->get()
                    ->result();
        $ret = array();
        if(count($query) > 0){
            $ret['id'] = $query[0]->code;
            $ret['country_id'] = $query[0]->id;
            $ret['text'] = $query[0]->name;
        }
        return $ret;
    }
    
    public function getbyid($id){
        $query = $this->db->select('cms_countries.id, cms_countries.code as code, cms_countries.name as name')
                    ->from('cms_countries')
                    ->where('cms_countries.id', $id)
                    ->limit(1)
                    ->get()
                    ->result();
        $ret = array();
        if(count($query) > 0){
            $ret['id'] = $query[0]->id;
            $ret['code'] = $query[0]->code;
            $ret['text'] = $query[0]->name;
        }
        return $ret;
    }
    
    public function getContinentByCode($code){
        $query = $this->db->select('cms_countries.continent_code')
                    ->from('cms_countries')
                    ->where('cms_countries.code', $code)
                    ->get()
                    ->result();
        $continent = "";
        if(count($query) > 0){
            $continent = $query[0]->continent_code;
        }
        return $continent;
    }
}