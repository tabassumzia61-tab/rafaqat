<?php
class Brands_model extends CI_model {

    public function get($id = null) {
        if (!empty($id)) {
            $this->db->select('brands.*');
            $this->db->from('brands');
            $this->db->where('brands.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('brands.*');
            $this->db->from('brands');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("brands", $data);
        } else {
            $this->db->insert("brands", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("brands");
    }

    function check_data_exists($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('brands');
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