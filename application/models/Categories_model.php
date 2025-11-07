<?php
class Categories_model extends CI_model
{

    
    public function getAllCategories(){
        $this->db->where('parent_id', null)->or_where('parent_id', 0);
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getParentCategories(){
        $this->db->where('parent_id', null)->or_where('parent_id', 0);
        $q = $this->db->get('categories');
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
            $this->db->select('categories.*,c.name as parent');
            $this->db->from('categories');
            $this->db->join('categories c', 'c.id=categories.parent_id', 'left');
            $this->db->where('categories.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('categories.*,c.name as parent');
            $this->db->from('categories');
            $this->db->join('categories c', 'c.id=categories.parent_id', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("categories", $data);
        } else {
            $this->db->insert("categories", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("categories");
    }

    function check_data_exists($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('categories');
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


    public function get_categories($limit, $offset, $search = "")
    {
        $this->db->select('categories.*, c.name as parent');
        $this->db->from('categories');
        $this->db->join('categories c', 'c.id=categories.parent_id', 'left');

        if (!empty($search)) {
            $this->db->like('categories.name', $search);
        }

        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_categories($search = "")
    {
        $this->db->from('categories');

        if (!empty($search)) {
            $this->db->like('name', $search);
        }

        return $this->db->count_all_results();
    }

}

?>