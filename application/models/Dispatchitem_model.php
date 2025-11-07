<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dispatchitem_model extends CI_Model {

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
        $query = $this->db->select('dispatchitem.*,staff.name as staff_name')->join('staff','dispatchitem.supervisor_id = staff.id')->order_by('id', 'desc')->get('dispatchitem');
        return $query->result_array();
    }
    
    public function getdispatchitemAll() {
        $this->datatables
            ->select('dispatchitem.id,dispatchitem.datetime,dispatchitem.bill_no,dispatchitem.status,dispatchitem.attachment,dispatchitem.name,dispatchitem.description,dispatchitem.supervisor_id,staff.name as staff_name')
            ->searchable('dispatchitem.id,dispatchitem.datetime,dispatchitem.bill_no,dispatchitem.status,dispatchitem.attachment,dispatchitem.name,dispatchitem.description,dispatchitem.supervisor_id,staff.name as staff_name')
            ->orderable('dispatchitem.id,dispatchitem.datetime,dispatchitem.bill_no,dispatchitem.status,dispatchitem.attachment,dispatchitem.name,dispatchitem.description,dispatchitem.supervisor_id,staff.name as staff_name')
            ->join('staff','dispatchitem.supervisor_id = staff.id','')
            ->sort('dispatchitem.id', 'desc')
            ->from('dispatchitem');
        return $this->datatables->generate('json');
    }

    public function getdispatchitemByID($product_id= null,$supervisor_id= null,$dept_id= null,$shift= null,$start_date = null,$end_date = null){
        $this->datatables->select('dispatchitem.id,dispatchitem.datetime,dispatchitem.bill_no,dispatchitem.status,dispatchitem.attachment,dispatchitem.name,dispatchitem.description,dispatchitem.supervisor_id,staff.name as staff_name');
        $this->datatables->searchable('dispatchitem.id,dispatchitem.datetime,dispatchitem.bill_no,dispatchitem.status,dispatchitem.attachment,dispatchitem.name,dispatchitem.description,dispatchitem.supervisor_id,staff.name as staff_name');
        $this->datatables->orderable('dispatchitem.id,dispatchitem.datetime,dispatchitem.bill_no,dispatchitem.status,dispatchitem.attachment,dispatchitem.name,dispatchitem.description,dispatchitem.supervisor_id,staff.name as staff_name');
        $this->datatables->join('staff','dispatchitem.supervisor_id = staff.id','');
        if($product_id != null){
            $this->datatables->join('dispatchitem_items','dispatchitem_items.id = dispatchitem_items.dispatchitem_id','');
        }
        if($product_id != null){
            $this->datatables->where('dispatchitem_items.item_id',$product_id);
        }
        if($supervisor_id != null){
            $this->datatables->where('dispatchitem.supervisor_id',$supervisor_id);
        }
        if($shift != null){
            $this->datatables->where('dispatchitem.shift',$shift);
        }
        if($dept_id != null){
            $this->datatables->where('dispatchitem.dept_id',$dept_id);
        }
        if($start_date != null){
            $this->datatables->where('dispatchitem.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('dispatchitem.date <=',$end_date);
        }
        $this->datatables->sort('dispatchitem.id', 'desc');
        $this->datatables->from('dispatchitem');
        return $this->datatables->generate('json');
    }
    
    public function getItemsBydispatchitemIDByPID($id =null,$pid =null){
        $this->db->select('dispatchitem_items.*');
        $this->db->from('dispatchitem_items');
        if($id !=null){
            $this->db->where('dispatchitem_items.dispatchitem_id', $id);
        }
        if($pid !=null){
            $this->db->where('dispatchitem_items.item_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getItemsBydispatchitemID($id) {
        $this->db->select('dispatchitem_items.*');
        $this->db->from('dispatchitem_items');
        $this->db->where('dispatchitem_items.dispatchitem_id', $id);
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getBillBasicBySupplier($supervisor_id) {
        $query = $this->db->select('dispatchitem.*,staff.name as supplier_name')->join('staff','dispatchitem.supervisor_id = staff.id')->where('dispatchitem.supervisor_id',$supervisor_id)->order_by('date', 'asc')->get('dispatchitem');
        return $query->result_array();
    }

    public function getBillNo() {
       $last_row = $this->db->select('bill_no')->order_by('id', "desc")->limit(1)->get('dispatchitem')->row_array();
        return $last_row;
    }

    public function getbilldetilproductbyid($id = null) {
        $this->db->select('dispatchitem_items.*');
        $this->db->from('dispatchitem_items');
        if ($id != null) {
            $this->db->where('dispatchitem_items.dispatchitem_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getBillDetails($id) {
        $query = $this->db->select('dispatchitem.*,staff.name as staff_name')->join('staff','dispatchitem.supervisor_id = staff.id')->where('dispatchitem.id',$id)->order_by('id', 'desc')->get('dispatchitem');
        return $query->row_array();
    }

    public function deletedispatchitem($id = null) {
        if ($id != null) {
            $this->db->where("dispatchitem_items.dispatchitem_id", $id)->delete("dispatchitem_items");
            $this->db->where("id", $id)->delete("dispatchitem");
        }
    }

    public function getbilldetilproduct($id) {
        $query = $this->db->select('purchase_bill_detail.*')->where('purchase_bill_detail.purchase_bill_id', $id)->get('purchase_bill_detail');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("dispatchitem_items");
    }

    public function getProduct() {
        $this->db->select('id,name')->from('products')->where('add_type','purchases');
        $q = $this->db->get();
        $result = $q->result_array();
        return $result;
    }
    
    public function getItemsBydispatchitemIDByProID($id,$pid){
        $this->db->select('dispatchitem_items.*');
        $this->db->from('dispatchitem_items');
        $this->db->where('dispatchitem_items.dispatchitem_id', $id);
        $this->db->where('dispatchitem_items.item_id', $pid);
        $query = $this->db->get('');
        return $query->row_array();
    }

    

}
