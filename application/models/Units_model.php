<?php
class Units_model extends CI_model {

    public function getParentunits(){
        $this->db->where('base_unit', null)->or_where('base_unit', 0);
        $q = $this->db->get('units');
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
            $this->db->select('units.*,c.name as parent');
            $this->db->from('units');
            $this->db->join('units c', 'c.id=units.base_unit', 'left');
            $this->db->where('units.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('units.*,c.name as parent');
            $this->db->from('units');
            $this->db->join('units c', 'c.id=units.base_unit', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function add($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("units", $data);
        } else {
            $this->db->insert("units", $data);
        }
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("units");
    }

    function check_data_exists($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('units');
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


    /**
     * Get units with optional search + pagination
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @return array
     */
    public function get_units($limit = 0, $offset = 0, $search = null) {
        $this->db->select('units.*, c.name as parent');
        $this->db->from('units');
        $this->db->join('units c', 'c.id = units.base_unit', 'left');

        if (!empty($search)) {
            // search in name, description, operator, maybe parent name
            $this->db->group_start();
            $this->db->like('units.name', $search);
            $this->db->or_like('units.description', $search);
            $this->db->or_like('units.operator', $search);
            $this->db->or_like('c.name', $search);
            $this->db->group_end();
        }

        // Order by (optional) — change as needed
        $this->db->order_by('units.name', 'ASC');

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    /**
     * Count units for pagination (with optional search)
     *
     * @param string|null $search
     * @return int
     */
    public function count_units($search = null) {
        $this->db->from('units');
        $this->db->join('units c', 'c.id = units.base_unit', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('units.name', $search);
            $this->db->or_like('units.description', $search);
            $this->db->or_like('units.operator', $search);
            $this->db->or_like('c.name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // ... keep the rest of your methods ...
}

?>