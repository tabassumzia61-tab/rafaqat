<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Issueitem_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    
    public function getBillBasic() {
        $query = $this->db->select('issueitem.*,staff.name as staff_name')->join('staff','issueitem.supervisor_id = staff.id')->order_by('id', 'desc')->get('issueitem');
        return $query->result_array();
    }
    
    public function getIssueitemAll() {
        $this->datatables
            ->select('issueitem.id,issueitem.datetime,issueitem.bill_no,issueitem.status,issueitem.attachment,issueitem.name,issueitem.description,issueitem.supervisor_id,staff.name as staff_name')
            ->searchable('issueitem.id,issueitem.datetime,issueitem.bill_no,issueitem.status,issueitem.attachment,issueitem.name,issueitem.description,issueitem.supervisor_id,staff.name as staff_name')
            ->orderable('issueitem.id,issueitem.datetime,issueitem.bill_no,issueitem.status,issueitem.attachment,issueitem.name,issueitem.description,issueitem.supervisor_id,staff.name as staff_name')
            ->join('staff','issueitem.supervisor_id = staff.id','')
            ->sort('issueitem.id', 'desc')
            ->from('issueitem');
        return $this->datatables->generate('json');
    }

    public function getIssueItemByID($product_id= null,$supervisor_id= null,$dept_id= null,$shift= null,$start_date = null,$end_date = null){
        $this->datatables->select('issueitem.id,issueitem.datetime,issueitem.bill_no,issueitem.status,issueitem.attachment,issueitem.name,issueitem.description,issueitem.supervisor_id,staff.name as staff_name');
        $this->datatables->searchable('issueitem.id,issueitem.datetime,issueitem.bill_no,issueitem.status,issueitem.attachment,issueitem.name,issueitem.description,issueitem.supervisor_id,staff.name as staff_name');
        $this->datatables->orderable('issueitem.id,issueitem.datetime,issueitem.bill_no,issueitem.status,issueitem.attachment,issueitem.name,issueitem.description,issueitem.supervisor_id,staff.name as staff_name');
        $this->datatables->join('staff','issueitem.supervisor_id = staff.id','');
        if($product_id != null){
            $this->datatables->join('issueitem_items','issueitem_items.id = issueitem_items.issueitem_id','');
        }
        if($product_id != null){
            $this->datatables->where('issueitem_items.item_id',$product_id);
        }
        if($supervisor_id != null){
            $this->datatables->where('issueitem.supervisor_id',$supervisor_id);
        }
        if($shift != null){
            $this->datatables->where('issueitem.shift',$shift);
        }
        if($dept_id != null){
            $this->datatables->where('issueitem.dept_id',$dept_id);
        }
        if($start_date != null){
            $this->datatables->where('issueitem.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('issueitem.date <=',$end_date);
        }
        $this->datatables->sort('issueitem.id', 'desc');
        $this->datatables->from('issueitem');
        return $this->datatables->generate('json');
    }
    
    public function getItemsByIssueItemIDByPID($id =null,$pid =null){
        $this->db->select('issueitem_items.*');
        $this->db->from('issueitem_items');
        if($id !=null){
            $this->db->where('issueitem_items.issueitem_id', $id);
        }
        if($pid !=null){
            $this->db->where('issueitem_items.item_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getItemsByIssueItemID($id) {
        $this->db->select('issueitem_items.*');
        $this->db->from('issueitem_items');
        $this->db->where('issueitem_items.issueitem_id', $id);
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getBillBasicBySupplier($supervisor_id) {
        $query = $this->db->select('issueitem.*,staff.name as supplier_name')->join('staff','issueitem.supervisor_id = staff.id')->where('issueitem.supervisor_id',$supervisor_id)->order_by('date', 'asc')->get('issueitem');
        return $query->result_array();
    }

    public function getBillNo() {
       $last_row = $this->db->select('bill_no')->order_by('id', "desc")->limit(1)->get('issueitem')->row_array();
        return $last_row;
    }

    public function getbilldetilproductbyid($id = null) {
        $this->db->select('issueitem_items.*');
        $this->db->from('issueitem_items');
        if ($id != null) {
            $this->db->where('issueitem_items.issueitem_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getBillDetails($id) {
        $query = $this->db->select('issueitem.*,staff.name as staff_name')->join('staff','issueitem.supervisor_id = staff.id')->where('issueitem.id',$id)->order_by('id', 'desc')->get('issueitem');
        return $query->row_array();
    }

    public function deleteissueitem($id = null) {
        if ($id != null) {
            $this->db->where("issueitem_items.issueitem_id", $id)->delete("issueitem_items");
            $this->db->where("id", $id)->delete("issueitem");
        }
    }

    public function getbilldetilproduct($id) {
        $query = $this->db->select('purchase_bill_detail.*')->where('purchase_bill_detail.purchase_bill_id', $id)->get('purchase_bill_detail');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("issueitem_items");
    }

    public function getProduct() {
        $this->db->select('id,name')->from('products')->where('add_type','purchases');
        $q = $this->db->get();
        $result = $q->result_array();
        return $result;
    }
    
    public function getItemsByIssueItemIDByProID($id,$pid){
        $this->db->select('issueitem_items.*');
        $this->db->from('issueitem_items');
        $this->db->where('issueitem_items.issueitem_id', $id);
        $this->db->where('issueitem_items.item_id', $pid);
        $query = $this->db->get('');
        return $query->row_array();
    }

    

}
