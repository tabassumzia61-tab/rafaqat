<?php
class Area_model extends CI_model {

    public function get($id = null, $camp_id = null) {
        if (!empty($id)) {
            $this->db->select('area.*');
            $this->db->from('area');
            $this->db->where('area.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('area.*');
            $this->db->from('area');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getCountryByStatesByCityByArea(){
        $this->db->select('countries.id as country_id,countries.name as country_name');
        $this->db->join('countries','countries.id = area.country_id');
        $this->db->join('states','states.id = area.state_id');
        $this->db->join('cities','cities.id = area.city_id');
        $this->db->from('area');
        $this->db->group_by('area.country_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getstatebycountry($country_id){
        $this->db->select('countries.id as country_id,countries.name as country_name,states.id as state_id,states.name as state_name');
        $this->db->join('countries','countries.id = area.country_id');
        $this->db->join('states','states.id = area.state_id');
        $this->db->from('area');
        $this->db->where('area.country_id',$country_id);
        $this->db->group_by('area.country_id,area.state_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getcitybycountrybystate($country_id,$state_id){
        $this->db->select('cities.id as city_id,cities.name as city_name');
        $this->db->join('countries','countries.id = area.country_id');
        $this->db->join('states','states.id = area.state_id');
        $this->db->join('cities','cities.id = area.city_id');
        $this->db->from('area');
        $this->db->where('area.country_id',$country_id);
        $this->db->where('area.state_id',$state_id);
        $this->db->group_by('area.country_id,area.state_id,area.state_id,area.city_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getareabycountrybystatebycity($country_id,$state_id,$city_id){
        $this->db->select('area.*');
        $this->db->join('countries','countries.id = area.country_id');
        $this->db->join('states','states.id = area.state_id');
        $this->db->join('cities','cities.id = area.city_id');
        $this->db->from('area');
        $this->db->where('area.country_id',$country_id);
        $this->db->where('area.state_id',$state_id);
        $this->db->where('area.city_id',$city_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("area", $data);
        } else {
            $this->db->insert("area", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("area");
    }

    function check_data_exists($country_id,$state_id,$city_id,$name) {
        $this->db->where('name', $name);
        $this->db->where('country_id', $country_id);
        $this->db->where('state_id', $state_id);
        $this->db->where('city_id', $city_id);
        $query = $this->db->get('area');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function check_exists($str) {
        $name = $this->security->xss_clean($str);
        $country_id = $this->input->post('country_id');
        $state_id  = $this->input->post('state_id');
        $city_id = $this->input->post('city_id');
        $this->input->post('country_id');
        $res = $this->check_data_exists($country_id,$state_id,$city_id,$name);
        if ($res) {
            $id = $this->input->post('id');
            if (isset($id)) {
                if ($res->id == $id) {
                    return TRUE;
                }
            }
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getCountry() {
        $this->db->select('countries.*');
        $this->db->from('countries');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getstateByCountryID($country_id) {
        $this->db->select('states.*');
        $this->db->from('states');
        $this->db->where('states.country_id',$country_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getCityByCountryStateID($country_id,$state_id) {
        $this->db->select('cities.*');
        $this->db->from('cities');
        $this->db->where('cities.country_id',$country_id);
        $this->db->where('cities.state_id',$state_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAreaCityByCountryStateID($country_id,$state_id,$city_id) {
        $this->db->select('area.*');
        $this->db->from('area');
        $this->db->where('area.country_id',$country_id);
        $this->db->where('area.state_id',$state_id);
        $this->db->where('area.city_id',$city_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAreaByCity($city_id) {
        $this->db->select('area.*');
        $this->db->from('area');
        $this->db->where('area.city_id',$city_id);
        $query = $this->db->get();
        return $query->result_array();
    }
}

?>