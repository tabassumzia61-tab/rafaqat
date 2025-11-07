<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Supplier_model extends MY_Model
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
    public function get($id = null){
        $this->db->select('supplier.*,cities.name as city_name,area.name as area_name');
        $this->db->from('supplier');
        $this->db->join('cities','cities.id = supplier.city_id','left');
        $this->db->join('area','area.id = supplier.area_id','left');
        if ($id != null) {
            $this->db->where('supplier.id', $id);
            $this->db->where('supplier.is_active', 1);
        } else {
            $this->db->order_by('supplier.id');
            $this->db->where('supplier.is_active', 1);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
    
    public function getall($brc_id = null){
        $this->db->select('supplier.id,supplier.supplier_id as code,supplier.name')->from('supplier');
        $this->db->group_start();
        $this->db->where('supplier.brc_id',NULL);
        $this->db->or_where('supplier.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->where('is_active', 1);
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
            $this->db->update('supplier', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item supplier id " . $data['id'];
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
            $this->db->insert('supplier', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item supplier id " . $insert_id;
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
        $this->db->delete('supplier');
        $message   = DELETE_RECORD_CONSTANT . " On item supplier id " . $id;
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

    public function getGuarantor($supplier_id = null){
        $this->db->select('guarantor.*')->from('guarantor');
        $this->db->where('guarantor.supplier_id', $supplier_id);
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

    public function getlastrecord() {
       $last_row = $this->db->select('supplier_id')->order_by('id', "desc")->limit(1)->get('supplier')->row_array();
        return $last_row;
    }

    public function getProfile($id){
        $this->db->select('supplier.*');
        $this->db->where("supplier.id", $id);
        $this->db->from('supplier');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function searchSupplierFullText($brc_id,$searchterm,$selected_category, $active){
        $this->db->select("supplier.*,cities.name as city_name,area.name as area_name");
        $this->db->from('supplier');
        $this->db->join('cities','cities.id = supplier.city_id','left');
        $this->db->join('area','area.id = area_id','left');
        $this->db->where("supplier.is_active", $active);
        $this->db->where("supplier.brc_id", $brc_id);
        if($selected_category === 'supplier_id') {
            $this->db->where('supplier.supplier_id', $searchterm);
        }elseif($selected_category === 'name') {
            $this->db->group_start();
            $this->db->like('supplier.name', $searchterm);
            $this->db->or_like('supplier.surname', $searchterm);
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function printVocherByID($id){
        $this->db->select('account_voucher.*,supplier.name as `s_name`,supplier.surname as `s_sname`')->from('account_voucher');
        //$this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id');
        $this->db->join('supplier','supplier.id = account_voucher.supplier_id');
        $this->db->where('account_voucher.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getPurchaseBySupplierBal($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,SUM(purchases.grand_total) as `amount`');
        $this->db->from('purchases');
        $this->db->where('purchases.supplier_id', $id);
        $this->db->where('purchases.brc_id', $brc_id);
        if($start_date != null){
            $this->datatables->where('purchases.date <=',$start_date);
        }
        // if($end_date != null){
        //     $this->datatables->where('purchases.date <=',$end_date);
        // }
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getPaymentsBySupplierBal($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,SUM(account_voucher.credit_amount) as `paid_amount`');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.supplier_id', $id);
        $this->db->where('account_voucher.brc_id', $brc_id);
        if($start_date != null){
            $this->datatables->where('account_voucher.date <=',$start_date);
        }
        // if($end_date != null){
        //     $this->datatables->where('account_voucher.date <=',$end_date);
        // }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getPurchaseBySupplier($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.grand_total as `amount`');
        $this->db->from('purchases');
        $this->db->where('purchases.supplier_id', $id);
        $this->db->where('purchases.brc_id', $brc_id);
        if($start_date != null){
            $this->datatables->where('purchases.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('purchases.date <=',$end_date);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPaymentsBySupplier($brc_id,$id,$start_date = null,$end_date= null) {
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.credit_amount as `paid_amount`');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.supplier_id', $id);
        $this->db->where('account_voucher.brc_id', $brc_id);
        if($start_date != null){
            $this->datatables->where('account_voucher.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('account_voucher.date <=',$end_date);
        }
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getSupplierDetailByID($id,$from_date = null,$to_date= null,$product_id = null){
        $condition = '';
        if($from_date != null && $to_date != null){
            $condition .=" date_format(supplier_detail.date,'%Y-%m-%d') between  '".$from_date."' and '".$to_date."'";
        }
        $this->db->select('supplier_detail.*,supplier.name as supplier_name,purchases.bill_no');
        $this->db->from('supplier_detail');
        $this->db->join('supplier','supplier_detail.supplier_id = supplier.id');
        $this->db->join('purchases','purchases.id = supplier_detail.purchase_bill_id','left');
        if($product_id !=null){
            $this->db->join('purchase_items','purchases.id = purchase_items.purchase_id');
        }
        if($product_id !=null){
            $this->db->where('purchase_items.item_id',$product_id);
        }
        $this->db->where('supplier_detail.supplier_id',$id);
        if($condition != ''){
            $this->db->where($condition);
        }
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getSupplierKhoyaDetailByID($id,$from_date = null,$to_date= null,$product_id = null){
        $condition = '';
        if($from_date != null && $to_date != null){
            $condition .=" date_format(purchase_items_khoya.date,'%Y-%m-%d') between  '".$from_date."' and '".$to_date."'";
        }
        $this->db->select('purchase_items_khoya.*,purchases.date as pur_date,supplier.name as supplier_name,purchases.bill_no');
        $this->db->from('purchase_items_khoya');
        $this->db->join('supplier','purchase_items_khoya.supplier_id = supplier.id');
        $this->db->join('purchases','purchases.id = purchase_items_khoya.purchase_id','left');
        $this->db->where('purchase_items_khoya.supplier_id',$id);
        if($product_id != null){
            $this->db->where('purchase_items_khoya.product_id',$product_id);
        }
        if($condition != ''){
            $this->db->where($condition);
        }
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getpayableBySupplierID($id,$from_date = null,$to_date= null,$product_id =null){
        $condition = '';
        if($from_date != null && $to_date != null){
            $condition .=" date_format(supplier_detail.date,'%Y-%m-%d') between  '".$from_date."' and '".$to_date."'";
        }
        $this->db->select('SUM(supplier_detail.amount) as `payable_amount`');
        $this->db->from('supplier_detail');
        $this->db->join('supplier','supplier.id = supplier_detail.supplier_id');
        $this->db->join('purchases','purchases.id = supplier_detail.purchase_bill_id','left');
        if($product_id !=null){
            $this->db->join('purchase_items','purchases.id = purchase_items.purchase_id');
        }
        if($product_id !=null){
            $this->db->where('purchase_items.item_id',$product_id);
        }
        $this->db->where('supplier_detail.supplier_id', $id);
        if($condition != ''){
            $this->db->where($condition);
        }
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getpaidBySupplierID($id,$from_date = null,$to_date= null,$product_id =null){
        $condition = '';
        if($from_date != null && $to_date != null){
            $condition .=" date_format(supplier_detail.date,'%Y-%m-%d') between  '".$from_date."' and '".$to_date."'";
        }
        $this->db->select('SUM(supplier_detail.paid_amount) as `paid_amount`');
        $this->db->from('supplier_detail');
        $this->db->join('supplier','supplier.id = supplier_detail.supplier_id');
        $this->db->join('purchases','purchases.id = supplier_detail.purchase_bill_id','left');
        if($product_id !=null){
            $this->db->join('purchase_items','purchases.id = purchase_items.purchase_id');
        }
        if($product_id !=null){
            $this->db->where('purchase_items.item_id',$product_id);
        }
        $this->db->where('supplier_detail.supplier_id', $id);
        if($condition != ''){
            $this->db->where($condition);
        }
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function addcashpayment($data) {
        $this->db->insert('supplier_detail', $data);
        return $this->db->insert_id();
    }
    
    public function updatecashpayment($data) {
        $this->db->where('id', $data['id']);
        $this->db->update('supplier_detail', $data);
    }
    
    public function printcashpayByInsertID($id){
        $this->db->select('supplier_detail.*');
        $this->db->from('supplier_detail');
        $this->db->where('supplier_detail.id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPurchasesBySupplier($id,$status,$from_date= null,$to_date= null,$product_id= null) {
        // $condition = '';
        // if($from_date != null && $to_date != null){
        //     $condition .=" date_format(purchases.date,'%Y-%m-%d') between  '".$from_date."' and '".$to_date."'";
        // }
        
        $this->datatables->select('purchases.id,purchases.datetime,purchases.bill_no,purchases.status,purchases.attachment,purchases.supplier_id,supplier.name');
        $this->datatables->searchable('purchases.id,purchases.datetime,purchases.bill_no,purchases.status,purchases.attachment,purchases.supplier_id,supplier.name');
        $this->datatables->orderable('purchases.id,purchases.datetime,purchases.bill_no,purchases.status,purchases.attachment,purchases.supplier_id,supplier.name');
        $this->datatables->join('supplier','supplier.id = purchases.supplier_id','');
        if($product_id != null){
            $this->datatables->join('purchase_items','purchases.id = purchase_items.purchase_id','');
        }
        $this->datatables->where('purchases.supplier_id', $id);
        $this->datatables->where('purchases.status', $status);
        if($product_id != null){
            $this->datatables->where('purchase_items.item_id',$product_id);
        }
        if($from_date != null){
            $this->datatables->where('purchases.date >=', $from_date);
        }
        if($to_date != null){
            $this->datatables->where('purchases.date <=', $to_date);
        }
        $this->datatables->sort('purchases.id', 'desc');
        $this->datatables->from('purchases');
        return $this->datatables->generate('json');
    }
    
    public function getItemsByPurchaseIDByPID($id =null,$pid =null){
        $this->db->select('purchase_items.*');
        $this->db->from('purchase_items');
        if($id !=null){
            $this->db->where('purchase_items.purchase_id', $id);
        }
        if($pid !=null){
            $this->db->where('purchase_items.item_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getPurchaseIDByPaymentID($purchase_id,$supplier_id){
        $this->db->select('supplier_detail.*');
        $this->db->from('supplier_detail');
        $this->db->where('supplier_detail.supplier_id', $supplier_id);
        $this->db->where('supplier_detail.purchase_bill_id', $purchase_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function removeSupplierpayment($id){
        $this->db->where('id', $id);
        $this->db->delete('supplier_detail');
    }
    
    public function cashpayByID($id){
        $this->db->select('supplier_detail.*');
        $this->db->from('supplier_detail');
        $this->db->where('supplier_detail.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

}
