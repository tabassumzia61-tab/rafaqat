<?php
class City_model extends CI_model {

    public function get($id = null) {
        if (!empty($id)) {
            $this->db->select('cities.*');
            $this->db->from('cities');
            $this->db->where('cities.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('cities.*');
            $this->db->from('cities');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getCountryByStatesByCity(){
        $this->db->select('countries.id as country_id,countries.name as country_name');
        $this->db->join('countries','countries.id = cities.country_id');
        $this->db->join('states','states.id = cities.state_id');
        $this->db->from('cities');
        $this->db->group_by('cities.country_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getstatebycountry($country_id){
        $this->db->select('countries.id as country_id,countries.name as country_name,states.id as state_id,states.name as state_name');
        $this->db->join('countries','countries.id = cities.country_id');
        $this->db->join('states','states.id = cities.state_id');
        $this->db->from('cities');
        $this->db->where('cities.country_id',$country_id);
        $this->db->group_by('cities.country_id,cities.state_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getcitybycountrybystate($country_id,$state_id){
        $this->db->select('cities.*,countries.name as country_name,states.name as state_name');
        $this->db->join('countries','countries.id = cities.country_id');
        $this->db->join('states','states.id = cities.state_id');
        $this->db->from('cities');
        $this->db->where('cities.country_id',$country_id);
        $this->db->where('cities.state_id',$state_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("cities", $data);
        } else {
            $this->db->insert("cities", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("cities");
    }

    function check_data_exists($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('cities');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function check_exists($str) {
        $name = $this->security->xss_clean($str);
        $res = $this->check_data_exists($name);
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
}

?>