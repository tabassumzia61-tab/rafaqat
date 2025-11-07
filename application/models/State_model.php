<?php
class State_model extends CI_model {

    public function get($id = null) {
        if (!empty($id)) {
            $this->db->select('states.*');
            $this->db->from('states');
            $this->db->where('states.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('states.*');
            $this->db->from('states');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getCountryByStates(){
        $this->db->select('countries.id as country_id,countries.name as country_name')->join('countries','countries.id = states.country_id');
        $this->db->from('states');
        $this->db->group_by('states.country_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getstatebycountry($country_id){
        $this->db->select('states.*');
        $this->db->from('states');
        $this->db->where('states.country_id',$country_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("states", $data);
        } else {
            $this->db->insert("states", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("states");
    }

    function check_data_exists($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('states');
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