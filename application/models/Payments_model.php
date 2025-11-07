<?php

class Payments_model extends MY_model {


    public function lastInvoiceNoRecord($brc_id,$invoice_type){
        $query = "SELECT id,invoice_no FROM account_voucher WHERE invoice_no = (SELECT MAX(invoice_no) FROM account_voucher WHERE account_voucher.brc_id=". $this->db->escape($brc_id) ." AND account_voucher.invoice_type=". $this->db->escape($invoice_type) .")";
        $query = $this->db->query($query);
        return $query->row_array();
    }
    
    public function printVocherByID($id){
        $this->db->select('account_voucher.*,accountshead.name as type,customers.name as `c_name`,customers.surname as `c_sname`')->from('account_voucher');
        $this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id');
        $this->db->join('customers','customers.id = account_voucher.customer_id');
        $this->db->where('account_voucher.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function printVocherByInsertID($inster_id){
        $this->db->select('account_voucher.*,accountshead.name as type')->from('account_voucher');
        $this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id','left');
        $this->db->where('account_voucher.id', $inster_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    public function addaccountvoucher($data){
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("account_voucher", $data);
        } else {
            $this->db->insert("account_voucher", $data);
            $id = $this->db->insert_id();
            return $id ;
        }
    }
    
    public function getVoucherSearchDetails($brc_id,$start_date=null,$end_date=null,$voucher_type_id=null,$accounts_id=null,$customer_id=null,$supplier_id=null,$staff_id=null){
        $this->datatables->select('account_voucher.id,account_voucher.brc_id,account_voucher.date,account_voucher.invoice_no,account_voucher.note,account_voucher.debit_amount,customers.name as `c_name`,staff.name as `staff_name`,staff.surname as `staff_surname`,accountshead.name as `accounts_name`,supplier.name as `s_name`');
        $this->datatables->searchable('account_voucher.id,account_voucher.brc_id,account_voucher.date,account_voucher.invoice_no,account_voucher.note,account_voucher.debit_amount,customers.name as `c_name`,staff.name as `staff_name`,staff.surname as `staff_surname`,accountshead.name as `accounts_name`,supplier.name as `s_name`');
        $this->datatables->orderable('account_voucher.id,account_voucher.brc_id,account_voucher.date,account_voucher.invoice_no,account_voucher.note,account_voucher.debit_amount,customers.name as `c_name`,staff.name as `staff_name`,staff.surname as `staff_surname`,accountshead.name as `accounts_name`,supplier.name as `s_name`');
        $this->datatables->join('branch','branch.id = account_voucher.brc_id','left');
        $this->datatables->join('staff','staff.id = account_voucher.staff_id','left');
        $this->datatables->join('accountshead','accountshead.id = account_voucher.par_acc_head_id','left');
        $this->datatables->join('customers','customers.id = account_voucher.customer_id','left');
        $this->datatables->join('supplier','supplier.id = account_voucher.supplier_id','left');
        $this->datatables->where('account_voucher.brc_id',$brc_id);
        if($accounts_id != null){
            $this->datatables->where('account_voucher.par_acc_head_id',$accounts_id);
        }
        if($customer_id != null){
            $this->datatables->where('account_voucher.customer_id',$customer_id);
        }
        if($supplier_id != null){
            $this->datatables->where('account_voucher.supplier_id',$supplier_id);
        }
        if($staff_id != null){
            $this->datatables->where('account_voucher.staff_id',$staff_id);
        }
        if($voucher_type_id != null){
            $this->datatables->where('account_voucher.voucher_type_id',$voucher_type_id);
        }
        if($start_date != null){
            $this->datatables->where('account_voucher.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('account_voucher.date <=',$end_date);
        }
        $this->datatables->sort('account_voucher.id', 'desc');
        $this->datatables->from('account_voucher');
        return $this->datatables->generate('json');
    }
    
    public function getVoucherDetails($id = null,$brc_id= null){
        if (!empty($id)) {
            $this->db->select('account_voucher.*');
            $this->db->from('account_voucher');
            //$this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id');
            $this->db->join('branch','branch.id = account_voucher.brc_id');
            $this->db->where('account_voucher.id',$id);
            $this->db->where('account_voucher.brc_id',$brc_id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('account_voucher.*');
            $this->db->from('account_voucher');
            //$this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id');
            $this->db->join('branch','branch.id = account_voucher.brc_id');
            $this->db->where('account_voucher.brc_id',$brc_id);
            $this->db->order_by('account_voucher.id', 'DESC');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
    
    public function removepaymentin($id){
        $this->db->where('id', $id);
        $this->db->delete('account_voucher');
        
    }
    
    public function removepaymentout($id){
        $this->db->where('id', $id);
        $this->db->delete('account_voucher');
        
    }

}

?>