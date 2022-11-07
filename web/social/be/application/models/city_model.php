<?php
class City_Model extends CI_Model {
    public function search($term){
        $ret = array();
        $i = 0;
        $ci_res = $this->db->select('webgeocities.id as id, webgeocities.name as city_name, states.state_name, cms_countries.name as country_name')
                ->from('webgeocities')
                ->join('states', 'webgeocities.state_code = states.state_code and webgeocities.country_code = states.country_code', 'inner')
                ->join('cms_countries', 'cms_countries.code = webgeocities.country_code', 'inner')
                ->like('webgeocities.name', $term, 'after')
                ->order_by('webgeocities.order_display', 'desc')
                ->get()
                ->result();
        foreach($ci_res as $row){
            $ret[$i]['id'] = $row->id;
            $ret[$i]['name'] = $row->city_name;
            $ret[$i]['text'] =  $row->city_name.' - '.$row->state_name.' - '.$row->country_name;
            $i++;
        }
        return $ret;
    }
    
    public function getbyid($id){
        $ci_res = $this->db->select('webgeocities.id as id, webgeocities.name as city_name, states.state_name, cms_countries.name as country_name')
                ->from('webgeocities')
                ->join('states', 'webgeocities.state_code = states.state_code and webgeocities.country_code = states.country_code', 'inner')
                ->join('cms_countries', 'cms_countries.code = webgeocities.country_code', 'inner')
                ->where('webgeocities.id', $id)
                ->order_by('webgeocities.order_display', 'desc')
                ->limit(1)
                ->get()
                ->result();
        $ret = array();
        if(count($ci_res) > 0){
            $ret['id'] = $ci_res[0]->id;
            $ret['name'] = $ci_res[0]->city_name;
            $ret['text'] =  $ci_res[0]->city_name.' - '.$ci_res[0]->state_name.' - '.$ci_res[0]->country_name;
        }
        return $ret;
    }
}