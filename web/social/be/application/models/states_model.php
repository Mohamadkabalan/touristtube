<?php
class States_Model extends CI_Model {
    public function search($term, $country){
        $ret = array();
        $i = 0;
        $ci_res = $this->db->select('states.id as id, states.state_name, cms_countries.name as country_name')
                ->from('states')
                ->join('cms_countries', 'cms_countries.code = states.country_code', 'inner')
                ->like('states.state_name', $term, 'after')
                ->like('states.country_code', $country)
                ->order_by('states.state_name', 'asc')
                ->get()
                ->result();
        foreach($ci_res as $row){
            $ret[$i]['id'] = $row->id;
            $ret[$i]['name'] = $row->state_name;
            $ret[$i]['text'] =  $row->state_name.' - '.$row->country_name;
            $i++;
        }
        return $ret;
    }
    
    public function search_country_id($term, $country_id){
        $ret = array();
        $i = 0;
        $ci_res = $this->db->select('states.id as id, states.state_name, cms_countries.name as country_name')
                ->from('states')
                ->join('cms_countries', 'cms_countries.code = states.country_code', 'inner')
                ->like('states.state_name', $term, 'after')
                ->like('cms_countries.id', $country_id)
                ->order_by('states.state_name', 'asc')
                ->get()
                ->result();
        foreach($ci_res as $row){
            $ret[$i]['id'] = $row->id;
            $ret[$i]['name'] = $row->state_name;
            $ret[$i]['text'] =  $row->state_name.' - '.$row->country_name;
            $i++;
        }
        return $ret;
    }
    
    public function getbyid($id){
        $ci_res = $this->db->select('states.id as id, states.state_name, cms_countries.name as country_name, states.state_code as state_code')
                ->from('states')
                ->join('cms_countries', 'cms_countries.code = states.country_code', 'inner')
                ->where('states.id', $id)
                ->limit(1)
                ->get()
                ->result();
        $ret = array();
        if(count($ci_res) > 0){
            $ret['id'] = $ci_res[0]->id;
            $ret['name'] = $ci_res[0]->state_name;
            $ret['text'] =  $ci_res[0]->state_name.' - '.$ci_res[0]->country_name;
            $ret['code'] = $ci_res[0]->state_code;
        }
        return $ret;
    }
    
    public function getbycode($country_code, $state_code){
        $ci_res = $this->db->select('states.id as id, states.state_name, cms_countries.name as country_name')
                ->from('states')
                ->join('cms_countries', 'cms_countries.code = states.country_code', 'inner')
                ->where('states.country_code', $country_code)
                ->where('states.State_code', $state_code)
                ->limit(1)
                ->get()
                ->result();
        $ret = array();
        if(count($ci_res) > 0){
            $ret['id'] = $ci_res[0]->id;
            $ret['name'] = $ci_res[0]->state_name;
            $ret['text'] =  $ci_res[0]->state_name.' - '.$ci_res[0]->country_name;
        }
        return $ret;
    }
}