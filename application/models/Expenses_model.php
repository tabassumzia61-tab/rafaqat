<?php

class Expenses_model extends MY_model {



    public function get($id = null){
        $this->db->select('expenses_bill.*');
        $this->db->from('expenses_bill');
        if ($id != null) {
            $this->db->where('expenses_bill.id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    
    public function lastInvoiceNoRecord($brc_id,$invoice_type){
        $query = "SELECT id,invoice_no FROM expenses_bill WHERE invoice_no = (SELECT MAX(invoice_no) FROM expenses_bill WHERE expenses_bill.brc_id=". $this->db->escape($brc_id) ." AND expenses_bill.invoice_type=". $this->db->escape($invoice_type) .")";
        $query = $this->db->query($query);
        return $query->row_array();
    }
    
    public function printVocherByID($id){
        $this->db->select('expenses_bill.*,accountshead.name as type,customers.name as `c_name`,customers.surname as `c_sname`')->from('expenses_bill');
        $this->db->join('accountshead','accountshead.id = expenses_bill.acc_head_id');
        $this->db->join('customers','customers.id = expenses_bill.customer_id');
        $this->db->where('expenses_bill.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getItemsByExpID($exp_id){
        $this->db->select('expenses_bill_items.*')->from('expenses_bill_items');
        $this->db->join('accountshead','accountshead.id = expenses_bill_items.acc_head_id');
        $this->db->where('expenses_bill_items.expenses_bill_id', $exp_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function printExpenseBillByInsertID($inster_id){
        $this->db->select('expenses_bill.*,supplier.name as supplier_name')->from('expenses_bill');
        $this->db->join('supplier','supplier.id = expenses_bill.supplier_id','left');
        $this->db->where('expenses_bill.id', $inster_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    public function addexpensebill($data){
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("expenses_bill", $data);
        } else {
            $this->db->insert("expenses_bill", $data);
            $id = $this->db->insert_id();
            return $id ;
        }
    }
    
    public function addexpensebillitems($data){
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("expenses_bill_items", $data);
        } else {
            $this->db->insert("expenses_bill_items", $data);
            $id = $this->db->insert_id();
            return $id ;
        }
    }
    
    public function getVoucherSearchDetails($brc_id,$start_date=null,$end_date=null,$voucher_type_id=null,$supplier=null,$vno=null){
        $this->datatables->select('expenses_bill.id,expenses_bill.brc_id,expenses_bill.date,expenses_bill.invoice_no,expenses_bill.note,expenses_bill.grand_total');
        $this->datatables->searchable('expenses_bill.id,expenses_bill.brc_id,expenses_bill.date,expenses_bill.invoice_no,expenses_bill.note,expenses_bill.grand_total');
        $this->datatables->orderable('expenses_bill.id,expenses_bill.brc_id,expenses_bill.date,expenses_bill.invoice_no,expenses_bill.note,expenses_bill.grand_total');
        //$this->datatables->join('expenses_bill_items','expenses_bill.id = expenses_bill_items.expenses_bill_id','left');
        $this->datatables->where('expenses_bill.brc_id',$brc_id);
        // if($voucher_type_id != null){
        //     $this->datatables->where('expenses_bill_items.acc_head_id',$voucher_type_id);
        // }
        if($start_date != null){
            $this->datatables->where('expenses_bill.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('expenses_bill.date <=',$end_date);
        }
        $this->datatables->sort('expenses_bill.id', 'desc');
        $this->datatables->from('expenses_bill');
        return $this->datatables->generate('json');
    }
    
    public function getVoucherDetails($id = null,$brc_id= null){
        if (!empty($id)) {
            $this->db->select('expenses_bill.*');
            $this->db->from('expenses_bill');
            //$this->db->join('accountshead','accountshead.id = expenses_bill.acc_head_id');
            $this->db->join('branch','branch.id = expenses_bill.brc_id');
            $this->db->where('expenses_bill.id',$id);
            $this->db->where('expenses_bill.brc_id',$brc_id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('expenses_bill.*');
            $this->db->from('expenses_bill');
            //$this->db->join('accountshead','accountshead.id = expenses_bill.acc_head_id');
            $this->db->join('branch','branch.id = expenses_bill.brc_id');
            $this->db->where('expenses_bill.brc_id',$brc_id);
            $this->db->order_by('expenses_bill.id', 'DESC');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
    
    public function remove_expense_item($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("expenses_bill_items");
    }
    
    public function remove($id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('expenses_bill_items.expenses_bill_id', $id);
        $this->db->delete('expenses_bill_items');
        $this->db->where('id', $id);
        $this->db->delete('expenses_bill');
        $message   = DELETE_RECORD_CONSTANT . " On expenses id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

}

?>