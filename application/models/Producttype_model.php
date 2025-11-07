<?php
class Producttype_model extends CI_model {

    
    public function getAllProductType(){
        $this->db->where('parent_id', null)->or_where('parent_id', 0);
        $q = $this->db->get('product_type');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getParentProductType(){
        $this->db->where('parent_id', null)->or_where('parent_id', 0);
        $q = $this->db->get('product_type');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get($id = null) {
        if (!empty($id)) {
            $this->db->select('product_type.*,c.name as parent');
            $this->db->from('product_type');
            $this->db->join('product_type c', 'c.id=product_type.parent_id', 'left');
            $this->db->where('product_type.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('product_type.*,c.name as parent');
            $this->db->from('product_type');
            $this->db->join('product_type c', 'c.id=product_type.parent_id', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("product_type", $data);
        } else {
            $this->db->insert("product_type", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("product_type");
    }

    function check_data_exists($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('product_type');
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