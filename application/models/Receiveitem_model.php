<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receiveitem_model extends CI_Model {

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
        $query = $this->db->select('receiveitem.*,staff.name as staff_name')->join('staff','receiveitem.supervisor_id = staff.id')->order_by('id', 'desc')->get('receiveitem');
        return $query->result_array();
    }
    
    public function getreceiveitemAll() {
        $this->datatables
            ->select('receiveitem.id,receiveitem.datetime,receiveitem.bill_no,receiveitem.status,receiveitem.attachment,receiveitem.name,receiveitem.description,receiveitem.supervisor_id,staff.name as staff_name')
            ->searchable('receiveitem.id,receiveitem.datetime,receiveitem.bill_no,receiveitem.status,receiveitem.attachment,receiveitem.name,receiveitem.description,receiveitem.supervisor_id,staff.name as staff_name')
            ->orderable('receiveitem.id,receiveitem.datetime,receiveitem.bill_no,receiveitem.status,receiveitem.attachment,receiveitem.name,receiveitem.description,receiveitem.supervisor_id,staff.name as staff_name')
            ->join('staff','receiveitem.supervisor_id = staff.id','')
            ->sort('receiveitem.id', 'desc')
            ->from('receiveitem');
        return $this->datatables->generate('json');
    }

    public function getreceiveitemByID($product_id= null,$supervisor_id= null,$dept_id= null,$shift= null,$start_date = null,$end_date = null){
        $this->datatables->select('receiveitem.id,receiveitem.datetime,receiveitem.bill_no,receiveitem.status,receiveitem.attachment,receiveitem.name,receiveitem.description,receiveitem.supervisor_id,staff.name as staff_name');
        $this->datatables->searchable('receiveitem.id,receiveitem.datetime,receiveitem.bill_no,receiveitem.status,receiveitem.attachment,receiveitem.name,receiveitem.description,receiveitem.supervisor_id,staff.name as staff_name');
        $this->datatables->orderable('receiveitem.id,receiveitem.datetime,receiveitem.bill_no,receiveitem.status,receiveitem.attachment,receiveitem.name,receiveitem.description,receiveitem.supervisor_id,staff.name as staff_name');
        $this->datatables->join('staff','receiveitem.supervisor_id = staff.id','');
        if($product_id != null){
            $this->datatables->join('receiveitem_items','receiveitem_items.id = receiveitem_items.receiveitem_id','');
        }
        if($product_id != null){
            $this->datatables->where('receiveitem_items.item_id',$product_id);
        }
        if($supervisor_id != null){
            $this->datatables->where('receiveitem.supervisor_id',$supervisor_id);
        }
        if($shift != null){
            $this->datatables->where('receiveitem.shift',$shift);
        }
        if($dept_id != null){
            $this->datatables->where('receiveitem.dept_id',$dept_id);
        }
        if($start_date != null){
            $this->datatables->where('receiveitem.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('receiveitem.date <=',$end_date);
        }
        $this->datatables->sort('receiveitem.id', 'desc');
        $this->datatables->from('receiveitem');
        return $this->datatables->generate('json');
    }
    
    public function getItemsByreceiveitemIDByPID($id =null,$pid =null){
        $this->db->select('receiveitem_items.*');
        $this->db->from('receiveitem_items');
        if($id !=null){
            $this->db->where('receiveitem_items.receiveitem_id', $id);
        }
        if($pid !=null){
            $this->db->where('receiveitem_items.item_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getItemsByreceiveitemID($id) {
        $this->db->select('receiveitem_items.*');
        $this->db->from('receiveitem_items');
        $this->db->where('receiveitem_items.receiveitem_id', $id);
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getBillBasicBySupplier($supervisor_id) {
        $query = $this->db->select('receiveitem.*,staff.name as supplier_name')->join('staff','receiveitem.supervisor_id = staff.id')->where('receiveitem.supervisor_id',$supervisor_id)->order_by('date', 'asc')->get('receiveitem');
        return $query->result_array();
    }

    public function getBillNo() {
       $last_row = $this->db->select('bill_no')->order_by('id', "desc")->limit(1)->get('receiveitem')->row_array();
        return $last_row;
    }
    public function getBoxNameByPID($id) {
        $last_row = $this->db->select('name')->where('id', $id)->get('box')->row_array();
         return $last_row['name'];
     }
    public function getbilldetilproductbyid($id = null) {
        $this->db->select('receiveitem_items.*');
        $this->db->from('receiveitem_items');
        if ($id != null) {
            $this->db->where('receiveitem_items.receiveitem_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getBillDetails($id) {
        $query = $this->db->select('receiveitem.*,staff.name as staff_name')->join('staff','receiveitem.supervisor_id = staff.id')->where('receiveitem.id',$id)->order_by('id', 'desc')->get('receiveitem');
        return $query->row_array();
    }

    public function deletereceiveitem($id = null) {
        if ($id != null) {
            $this->db->where("receiveitem_items.receiveitem_id", $id)->delete("receiveitem_items");
            $this->db->where("id", $id)->delete("receiveitem");
        }
    }

    public function getbilldetilproduct($id) {
        $query = $this->db->select('purchase_bill_detail.*')->where('purchase_bill_detail.purchase_bill_id', $id)->get('purchase_bill_detail');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("receiveitem_items");
    }

    public function getProduct() {
        $this->db->select('id,name')->from('products')->where('add_type','sales');
        $q = $this->db->get();
        $result = $q->result_array();
        return $result;
    }

    public function getBox() {
        $this->db->select('id,name')->from('box');
        $q = $this->db->get();
        $result = $q->result_array();
        return $result;
    }
    
    public function getItemsByreceiveitemIDByProID($id,$pid){
        $this->db->select('receiveitem_items.*');
        $this->db->from('receiveitem_items');
        $this->db->where('receiveitem_items.receiveitem_id', $id);
        $this->db->where('receiveitem_items.item_id', $pid);
        $query = $this->db->get('');
        return $query->row_array();
    }

    

}
