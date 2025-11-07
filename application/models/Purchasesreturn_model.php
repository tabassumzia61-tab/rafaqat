<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Purchasesreturn_model extends MY_Model{

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
        $this->db->select('purchasesreturn.*');
        $this->db->from('purchasesreturn');
        if ($id != null) {
            $this->db->where('purchasesreturn.id', $id);
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
            $this->db->update('purchasesreturn', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item purchasesreturn id " . $data['id'];
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
            $this->db->insert('purchasesreturn', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item purchasesreturn id " . $insert_id;
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
        $this->db->where('purchasesreturn_items.purchase_id', $id);
        $this->db->delete('purchasesreturn_items');
        $this->db->where('id', $id);
        $this->db->delete('purchasesreturn');
        $message   = DELETE_RECORD_CONSTANT . " On item purchasesreturn id " . $id;
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

    public function getpurchasesreturnNo($brc_id) {
       $last_row = $this->db->select('purchase_no')->where('purchasesreturn.brc_id',$brc_id)->order_by('id', "desc")->limit(1)->get('purchasesreturn')->row_array();
        return $last_row;
    }
    
    public function getPurchaseBillByID($purchasesreturn_id){
        $this->db->select('purchasesreturn.*,supplier.supplier_id as supplier_no,supplier.name,supplier.surname,supplier.phone,supplier.email,supplier.company,supplier.address');
        $this->db->from('purchasesreturn');
        $this->db->join('supplier','supplier.id = purchasesreturn.supplier_id');
        $this->db->where('purchasesreturn.id', $purchasesreturn_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($purchasesreturn_id);
        }
        return $result;
    }


    public function printpurchasesreturnInsertID($purchasesreturn_id){
        $this->db->select('purchasesreturn.*,supplier.supplier_id as supplier_no,supplier.name,supplier.surname,supplier.phone,supplier.email,supplier.company,supplier.address');
        $this->db->from('purchasesreturn');
        $this->db->join('supplier','supplier.id = purchasesreturn.supplier_id');
        $this->db->where('purchasesreturn.id', $purchasesreturn_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($purchasesreturn_id);
        }
        return $result;
    }

    public function getorderitemsByID($purchasesreturn_id){
        $this->db->select('purchasesreturn_items.*')->from('purchasesreturn_items');
        $this->db->where('purchasesreturn_items.purchase_id', $purchasesreturn_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getpurchasesreturn($brc_id,$status = null,$product_id = null,$supplier_id = null,$start_date = null,$end_date = null){
        $this->datatables->select('purchasesreturn.id,purchasesreturn.brc_id,purchasesreturn.date,purchasesreturn.purchase_no,purchasesreturn.status,purchasesreturn.attachment,purchasesreturn.note,purchasesreturn.supplier_id,purchasesreturn.supplier,purchasesreturn.grand_total');
        $this->datatables->searchable('purchasesreturn.id,purchasesreturn.brc_id,purchasesreturn.date,purchasesreturn.purchase_no,purchasesreturn.status,purchasesreturn.attachment,purchasesreturn.note,purchasesreturn.supplier_id,purchasesreturn.supplier,purchasesreturn.grand_total');
        $this->datatables->orderable('purchasesreturn.id,purchasesreturn.brc_id,purchasesreturn.date,purchasesreturn.purchase_no,purchasesreturn.status,purchasesreturn.attachment,purchasesreturn.note,purchasesreturn.supplier_id,purchasesreturn.supplier,purchasesreturn.grand_total');
        $this->datatables->where('purchasesreturn.brc_id',$brc_id);
        if($product_id != null){
            $this->datatables->join('purchasesreturn_items','purchasesreturn.id = purchasesreturn_items.purchase_id','');
        }
        if($status != null){
            //$this->datatables->where('purchasesreturn.status',$status);
        }
        if($product_id != null){
            $this->datatables->where('purchasesreturn_items.product_id',$product_id);
        }
        if($supplier_id != null){
            $this->datatables->where('purchasesreturn.supplier_id',$supplier_id);
        }
        if($start_date != null){
            $this->datatables->where('purchasesreturn.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('purchasesreturn.date <=',$end_date);
        }
        $this->datatables->sort('purchasesreturn.id', 'desc');
        $this->datatables->from('purchasesreturn');
        return $this->datatables->generate('json');
    }

    public function getPaymentByPurchaseID($id){
        $this->db->select('account_voucher.*');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.purchase_id', $id);
        $query = $this->db->get('');
        return $query->row_array();
    }
    
    public function getItemsBypurchasesreturnByPID($id =null,$pid =null){
        $this->db->select('purchasesreturn_items.*');
        $this->db->from('purchasesreturn_items');
        if($id !=null){
            $this->db->where('purchasesreturn_items.purchase_id', $id);
        }
        if($pid !=null){
            $this->db->where('purchasesreturn_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getItemsBypurchasesreturnCartByPID($id =null,$pid =null){
        $this->db->select('purchasesreturn_items.*');
        $this->db->from('purchasesreturn_items');
        if($id !=null){
            $this->db->where('purchasesreturn_items.purchase_id', $id);
        }
        if($pid !=null){
            $this->db->where('purchasesreturn_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->row_array();
    }

    public function getItemsBypurchasesreturnID($id =null){
        $this->db->select('purchasesreturn_items.*');
        $this->db->from('purchasesreturn_items');
        if($id !=null){
            $this->db->where('purchasesreturn_items.purchase_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("purchasesreturn_items");
    }
    
    public function getpurchasesreturnBySuppliersIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('purchasesreturn.id,purchasesreturn.brc_id,purchasesreturn.date,purchasesreturn.purchase_no as `bill_no`,purchasesreturn.supplier_id,purchasesreturn.note,purchasesreturn.supplier,SUM(purchasesreturn.gross_amount) as `dr`');
        $this->db->from('purchasesreturn');
        $this->db->join('supplier','supplier.id = purchasesreturn.supplier_id');
        $this->db->where('purchasesreturn.brc_id', $brc_id);
        $this->db->where('purchasesreturn.date >=', $start_date);
        $this->db->where('purchasesreturn.date <=', $end_date);
        $this->db->order_by('purchasesreturn.id','asc');
        $this->db->group_by('purchasesreturn.supplier_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getpurchasesreturnBySuppliersIDDetails($brc_id,$supplier_id,$start_date,$end_date){
        $this->db->select('purchasesreturn.id,purchasesreturn.brc_id,purchasesreturn.date,purchasesreturn.purchase_no as `bill_no`,purchasesreturn.supplier_id,purchasesreturn.note,productsaccountshead.name as `account_name`,purchasesreturn.gross_amount as `dr`');
        $this->db->from('purchasesreturn');
        $this->db->join('supplier','supplier.id = purchasesreturn.supplier_id');
        $this->db->join('purchasesreturn_items','purchasesreturn_items.purchase_id = purchasesreturn.id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchasesreturn_items.purchase_item_head_id');
        $this->db->where('purchasesreturn.brc_id', $brc_id);
        $this->db->where('purchasesreturn.supplier_id', $supplier_id);
        $this->db->where('purchasesreturn.date >=', $start_date);
        $this->db->where('purchasesreturn.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getpurchasesreturnByItemsIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('purchasesreturn.id,purchasesreturn.brc_id,purchasesreturn.date,purchasesreturn.purchase_no as `bill_no`,purchasesreturn.supplier_id,purchasesreturn.note,purchasesreturn_items.product_name,SUM(purchasesreturn.gross_amount) as `dr`');
        $this->db->from('purchasesreturn');
        $this->db->join('supplier','supplier.id = purchasesreturn.supplier_id');
        $this->db->join('purchasesreturn_items','purchasesreturn_items.purchase_id = purchasesreturn.id');
        $this->db->where('purchasesreturn.brc_id', $brc_id);
        $this->db->where('purchasesreturn.date >=', $start_date);
        $this->db->where('purchasesreturn.date <=', $end_date);
        $this->db->order_by('purchasesreturn_items.id','asc');
        $this->db->group_by('purchasesreturn_items.product_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getpurchasesreturnByItemsIDDetails($brc_id,$item_id,$start_date,$end_date){
        $this->db->select('purchasesreturn.id,purchasesreturn.brc_id,purchasesreturn.date,purchasesreturn.purchase_no as `bill_no`,purchasesreturn.supplier_id,purchasesreturn.note,supplier.name as `account_name`,purchasesreturn.gross_amount as `dr`');
        $this->db->from('purchasesreturn');
        $this->db->join('supplier','supplier.id = purchasesreturn.supplier_id');
        $this->db->join('purchasesreturn_items','purchasesreturn_items.purchase_id = purchasesreturn.id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchasesreturn_items.purchase_item_head_id');
        $this->db->where('purchasesreturn.brc_id', $brc_id);
        $this->db->where('purchasesreturn_items.product_id', $item_id);
        $this->db->where('purchasesreturn.date >=', $start_date);
        $this->db->where('purchasesreturn.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }

}
