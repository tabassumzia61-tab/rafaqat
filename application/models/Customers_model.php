<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Customers_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select('customers.*,cities.name as city_name,area.name as area_name')->from('customers');
        $this->db->join('cities','cities.id = customers.city_id','left');
        $this->db->join('area','area.id = customers.area_id','left');
        if ($id != null) {
            $this->db->where('customers.id', $id);
        } else {
            $this->db->order_by('customers.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    
    public function getall($brc_id = null){
        $this->db->select('customers.id,customers.customers_id as code,customers.name')->from('customers');
        $this->db->group_start();
        $this->db->where('customers.brc_id',NULL);
        $this->db->or_where('customers.brc_id',$brc_id);
        $this->db->group_end();
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('customers', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item customers id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('customers', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item customers id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('customers');
        $message   = DELETE_RECORD_CONSTANT . " On item customers id " . $id;
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

    public function addguarantor($data){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('guarantor', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item guarantor id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('guarantor', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item guarantor id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function getGuarantor($customers_id = null){
        $this->db->select('guarantor.*')->from('guarantor');
        $this->db->where('guarantor.customers_id', $customers_id);
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getGuarantorByID($guarantor_id = null){
        $this->db->select('guarantor.*')->from('guarantor');
        $this->db->where('guarantor.id', $guarantor_id);
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function delete_guarantor($id){
        $this->db->where('id', $id);
        $this->db->delete('guarantor');
    }

    public function getlastrecord($brc_id) {
       $last_row = $this->db->select('customers_id')->where('customers.brc_id',$brc_id)->order_by('id', "desc")->limit(1)->get('customers')->row_array();
        return $last_row;
    }

    public function getProfile($id){
        $this->db->select('customers.*');
        $this->db->where("customers.id", $id);
        $this->db->from('customers');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function searchcustomersFullText($brc_id,$searchterm,$selected_category, $active){
        $this->db->select("customers.*,cities.name as city_name,area.name as area_name")->from('customers');
        $this->db->join('cities','cities.id = customers.city_id','left');
        $this->db->join('area','area.id = customers.area_id','left');
        $this->db->where("customers.is_active", $active);
        $this->db->where("customers.brc_id", $brc_id);
        if($selected_category === 'customers_id') {
            $this->db->where('customers.customers_id', $searchterm);
        }elseif($selected_category === 'name') {
            $this->db->group_start();
            $this->db->like('customers.name', $searchterm);
            $this->db->or_like('customers.surname', $searchterm);
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getcustomersDetailByID($id){
        $query = $this->db->select('customers_detail.*,sales.sale_no,customers.name as customers_name')->join('customers','customers_detail.customers_id = customers.id')->join('sales','sales.id = customers_detail.customers_bill_id','left')->where('customers_detail.customers_id',$id)->order_by('date', 'asc')->get('customers_detail');
        return $query->result_array();
    }

    public function getcustomersKhoyaDetailByID($id){
        $query = $this->db->select('purchase_items_khoya.*,customers.name as customers_name')->join('customers','purchase_items_khoya.customers_id = customers.id')->where('purchase_items_khoya.customers_id',$id)->order_by('date', 'asc')->get('purchase_items_khoya');
        return $query->result_array();
    }
    
    public function getreceivableBycustomersID($id){
        $this->db->select('SUM(amount) as `receivable_amount`');
        $this->db->from('customers_detail');
        $this->db->join('customers','customers.id = customers_detail.customers_id');
        $this->db->where('customers_detail.customers_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getreceivedBycustomersID($id){
        $this->db->select('SUM(revd_amount) as `received_amount`');
        $this->db->from('customers_detail');
        $this->db->join('customers','customers.id = customers_detail.customers_id');
        $this->db->where('customers_detail.customers_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function addcashpayment($data) {
        $this->db->insert('customers_detail', $data);
        return $this->db->insert_id();
    }
    
    public function printcashpayByInsertID($id){
        $this->db->select('customers_detail.*');
        $this->db->from('customers_detail');
        $this->db->where('customers_detail.id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function printVocherByID($id){
        $this->db->select('account_voucher.*,customers.name as `c_name`,customers.surname as `c_sname`')->from('account_voucher');
        //$this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id');
        $this->db->join('customers','customers.id = account_voucher.customer_id');
        $this->db->where('account_voucher.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    
    public function getSalesBycustomersBal($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('sales.id,sales.date,sales.sale_no as `bill_no`,sales.note as `note`,SUM(sales.grand_total) as `amount`');
        $this->db->from('sales');
        $this->db->where('sales.customer_id', $id);
        $this->db->where('sales.brc_id', $brc_id);
        if($start_date != null){
            $this->db->where('sales.date <',$start_date);
        }
        // if($end_date != null){
        //     $this->db->where('sales.date <=',$end_date);
        // }
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getPaymentsBycustomersBal($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,SUM(account_voucher.credit_amount) as `paid_amount`');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.customer_id', $id);
        $this->db->where('account_voucher.brc_id', $brc_id);
        if($start_date != null){
            $this->db->where('account_voucher.date <',$start_date);
        }
        // if($end_date != null){
        //     $this->db->where('account_voucher.date <=',$end_date);
        // }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getSalesBycustomers($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('sales.id,sales.date,sales.sale_no as `bill_no`,sales.note as `note`,sales.grand_total as `amount`');
        $this->db->from('sales');
        $this->db->where('sales.customer_id', $id);
        $this->db->where('sales.brc_id', $brc_id);
        if($start_date != null){
            $this->db->where('sales.date >=',$start_date);
        }
        if($end_date != null){
            $this->db->where('sales.date <=',$end_date);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPaymentsBycustomers($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.credit_amount as `paid_amount`');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.customer_id', $id);
        $this->db->where('account_voucher.brc_id', $brc_id);
        if($start_date != null){
            $this->db->where('account_voucher.date >=',$start_date);
        }
        if($end_date != null){
            $this->db->where('account_voucher.date <=',$end_date);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function cashpayByID($id){
        $this->db->select('customers_detail.*');
        $this->db->from('customers_detail');
        $this->db->where('customers_detail.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updatecashpayment($data) {
        $this->db->where('id', $data['id']);
        $this->db->update('customers_detail', $data);
    }

    public function removepayment($id){
        $this->db->where('id', $id);
        $this->db->delete('customers_detail');
    }

}
