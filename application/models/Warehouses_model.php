<?php
class Warehouses_model extends CI_model {

    public function get($id = null) {
        if (!empty($id)) {
            $this->db->select('warehouses.*');
            $this->db->from('warehouses');
            $this->db->where('warehouses.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('warehouses.*');
            $this->db->from('warehouses');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("warehouses", $data);
        } else {
            $this->db->insert("warehouses", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("warehouses");
    }

    function check_data_exists($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('warehouses');
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