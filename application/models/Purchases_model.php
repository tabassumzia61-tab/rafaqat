<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Purchases_model extends MY_Model{

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
        $this->db->select('purchases.*');
        $this->db->from('purchases');
        if ($id != null) {
            $this->db->where('purchases.id', $id);
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
            $this->db->update('purchases', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item purchases id " . $data['id'];
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
            $this->db->insert('purchases', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item purchases id " . $insert_id;
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
        $this->db->where('account_voucher.purchase_id', $id);
        $this->db->delete('account_voucher ');
        $this->db->where('purchase_items.purchase_id', $id);
        $this->db->delete('purchase_items');
        $this->db->where('id', $id);
        $this->db->delete('purchases');
        $message   = DELETE_RECORD_CONSTANT . " On item purchases id " . $id;
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

    public function getSubCategories($parent_id){
        $this->db->select('categories.*')
        ->where('parent_id', $parent_id)->order_by('name');
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    
    public function getSubProducttype($parent_id){
        $this->db->select('product_type.*')
        ->where('parent_id', $parent_id)->order_by('name');
        $q = $this->db->get('product_type');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }


    public function addvariant($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("product_variants", $data);
        } else {
            $this->db->insert("product_variants", $data);
        }
    }

    public function getpurchasesNo($brc_id) {
       $last_row = $this->db->select('purchase_no')->where('purchases.brc_id',$brc_id)->order_by('id', "desc")->limit(1)->get('purchases')->row_array();
        return $last_row;
    }
    
    public function getPurchaseBillByID($purchases_id){
        $this->db->select('purchases.*,supplier.supplier_id as supplier_no,supplier.name,supplier.surname,supplier.phone,supplier.email,supplier.company,supplier.address');
        $this->db->from('purchases');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->where('purchases.id', $purchases_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($purchases_id);
        }
        return $result;
    }


    public function printpurchasesInsertID($purchases_id){
        $this->db->select('purchases.*,supplier.supplier_id as supplier_no,supplier.name,supplier.surname,supplier.phone,supplier.email,supplier.company,supplier.address');
        $this->db->from('purchases');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->where('purchases.id', $purchases_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($purchases_id);
        }
        return $result;
    }

    public function getorderitemsByID($purchases_id){
        $this->db->select('purchase_items.*')->from('purchase_items');
        $this->db->where('purchase_items.purchase_id', $purchases_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getpurchases($brc_id,$status = null,$product_id = null,$supplier_id = null,$start_date = null,$end_date = null){
        $this->datatables->select('purchases.id,purchases.brc_id,purchases.date,purchases.purchase_no,purchases.status,purchases.attachment,purchases.note,purchases.supplier_id,purchases.supplier,purchases.grand_total');
        $this->datatables->searchable('purchases.id,purchases.brc_id,purchases.date,purchases.purchase_no,purchases.status,purchases.attachment,purchases.note,purchases.supplier_id,purchases.supplier,purchases.grand_total');
        $this->datatables->orderable('purchases.id,purchases.brc_id,purchases.date,purchases.purchase_no,purchases.status,purchases.attachment,purchases.note,purchases.supplier_id,purchases.supplier,purchases.grand_total');
        $this->datatables->where('purchases.brc_id',$brc_id);
        if($product_id != null){
            $this->datatables->join('purchase_items','purchases.id = purchase_items.purchase_id','');
        }
        if($status != null){
            //$this->datatables->where('purchases.status',$status);
        }
        if($product_id != null){
            $this->datatables->where('purchase_items.product_id',$product_id);
        }
        if($supplier_id != null){
            $this->datatables->where('purchases.supplier_id',$supplier_id);
        }
        if($start_date != null){
            $this->datatables->where('purchases.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('purchases.date <=',$end_date);
        }
        $this->datatables->sort('purchases.id', 'desc');
        $this->datatables->from('purchases');
        return $this->datatables->generate('json');
    }

    public function getPaymentByPurchaseID($id){
        $this->db->select('account_voucher.*');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.purchase_id', $id);
        $query = $this->db->get('');
        return $query->row_array();
    }
    
    public function getItemsBypurchasesByPID($id =null,$pid =null){
        $this->db->select('purchase_items.*');
        $this->db->from('purchase_items');
        if($id !=null){
            $this->db->where('purchase_items.purchase_id', $id);
        }
        if($pid !=null){
            $this->db->where('purchase_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getItemsBypurchasesCartByPID($id =null,$pid =null){
        $this->db->select('purchase_items.*');
        $this->db->from('purchase_items');
        if($id !=null){
            $this->db->where('purchase_items.purchase_id', $id);
        }
        if($pid !=null){
            $this->db->where('purchase_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->row_array();
    }

    public function getItemsBypurchasesID($id =null){
        $this->db->select('purchase_items.*');
        $this->db->from('purchase_items');
        if($id !=null){
            $this->db->where('purchase_items.purchase_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("purchase_items");
    }
    
    public function getPurchasesBySuppliersIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('purchases.id,purchases.brc_id,purchases.date,purchases.purchase_no as `bill_no`,purchases.supplier_id,purchases.note,purchases.supplier,SUM(purchases.gross_amount) as `dr`');
        $this->db->from('purchases');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->where('purchases.date >=', $start_date);
        $this->db->where('purchases.date <=', $end_date);
        $this->db->order_by('purchases.id','asc');
        $this->db->group_by('purchases.supplier_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getPurchasesBySuppliersIDDetails($brc_id,$supplier_id,$start_date,$end_date){
        $this->db->select('purchases.id,purchases.brc_id,purchases.date,purchases.purchase_no as `bill_no`,purchases.supplier_id,purchases.note,productsaccountshead.name as `account_name`,purchases.gross_amount as `dr`');
        $this->db->from('purchases');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->where('purchases.supplier_id', $supplier_id);
        $this->db->where('purchases.date >=', $start_date);
        $this->db->where('purchases.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getPurchasesByItemsIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('purchases.id,purchases.brc_id,purchases.date,purchases.purchase_no as `bill_no`,purchases.supplier_id,purchases.note,purchase_items.product_name,SUM(purchases.gross_amount) as `dr`');
        $this->db->from('purchases');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->where('purchases.date >=', $start_date);
        $this->db->where('purchases.date <=', $end_date);
        $this->db->order_by('purchase_items.id','asc');
        $this->db->group_by('purchase_items.product_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getPurchasesByItemsIDDetails($brc_id,$item_id,$start_date,$end_date){
        $this->db->select('purchases.id,purchases.brc_id,purchases.date,purchases.purchase_no as `bill_no`,purchases.supplier_id,purchases.note,supplier.name as `account_name`,purchases.gross_amount as `dr`');
        $this->db->from('purchases');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->where('purchase_items.product_id', $item_id);
        $this->db->where('purchases.date >=', $start_date);
        $this->db->where('purchases.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }

}
