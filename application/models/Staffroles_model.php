<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staffroles_model extends CI_Model {

    protected $current_session; 
    
    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getStaffRoles($role_id) {
        $this->db->select('roles.*');
        $this->db->from('roles');
        $this->db->where('roles.id', $role_id);
        $query = $this->db->get();
        return $query->row();
    }

}
