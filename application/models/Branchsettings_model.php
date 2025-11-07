<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branchsettings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null) {
        $this->db->select('branch.*,countries.name as country,states.name as state,cities.name as city,area.name as area');
        $this->db->from('branch');
        $this->db->join('countries','branch.country_id = countries.id','left');
        $this->db->join('states','branch.state_id = states.id','left');
        $this->db->join('cities','branch.city_id = cities.id','left');
        $this->db->join('area','branch.area_id = area.id','left');
        if ($id != null) {
            $this->db->where('branch.id', $id);
        } else {
            $this->db->order_by('branch.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getbranchslist($id = null)
    {
        $this->datatables
            ->select('branch.id,branch.regd_date,branch.name,branch.code,branch.phone,branch.email,branch.address,countries.name as country,branch.country_id,states.name as state,branch.state_id,cities.name as city,branch.city_id,area.name as area,branch.area_id')
            ->searchable('branch.id,branch.regd_date,branch.name,branch.code,branch.phone,branch.email,branch.address,countries.name as country,branch.country_id,states.name as state,branch.state_id,cities.name as city,branch.city_id,area.name as area,branch.area_id')
            ->orderable('branch.regd_date,branch.name,branch.code,branch.phone,branch.email,branch.address,countries.name as country,states.name as state,cities.name as city,area.name as area')
            ->join('countries', 'branch.country_id = countries.id', 'left')
            ->join('states', 'branch.state_id = states.id', 'left')
            ->join('cities', 'branch.city_id = cities.id', 'left')
            ->join('area', 'branch.area_id = area.id', 'left')
            ->sort('branch.id', 'desc')
            ->from('branch');
        return $this->datatables->generate('json');
    }

    public function getclass($camp_id=null){
        $this->db->select()->from('classes');
        $query = $this->db->get();
        return $classlist = $query->result_array();
    }

    public function getSettingbranch($id){
        $this->db->select('branch.*')->from('branch');
        $this->db->where('branch.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getSettingbranchBySchool(){
        $this->db->select('branch.name')->from('branch');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('branch');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('branch', $data);
        } else {
            $this->db->insert('branch', $data);
            return $this->db->insert_id();
        }
    }

}
