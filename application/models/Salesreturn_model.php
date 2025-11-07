<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Salesreturn_model extends MY_Model{

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
        $this->db->select('salesreturn.*');
        $this->db->from('salesreturn');
        if ($id != null) {
            $this->db->where('salesreturn.id', $id);
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
            $this->db->update('salesreturn', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item salesreturn id " . $data['id'];
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
            $this->db->insert('salesreturn', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item salesreturn id " . $insert_id;
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
        $this->db->where('account_voucher.sale_id', $id);
        $this->db->delete('account_voucher');
        $this->db->where('salesreturn_items.sale_id', $id);
        $this->db->delete('salesreturn_items');
        $this->db->where('id', $id);
        $this->db->delete('salesreturn');
        $message   = DELETE_RECORD_CONSTANT . " On item salesreturn id " . $id;
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

    public function getsalesreturnNo($brc_id) {
       $last_row = $this->db->select('sale_no')->where('salesreturn.brc_id',$brc_id)->order_by('id', "desc")->limit(1)->get('salesreturn')->row_array();
        return $last_row;
    }

    public function printsalesreturnInsertID($salesreturn_id){
        $this->db->select('salesreturn.*,customers.customers_id as customers_no,customers.name,customers.surname,customers.phone,customers.email,customers.company,customers.address');
        $this->db->from('salesreturn');
        $this->db->join('customers','customers.id = salesreturn.customer_id');
        $this->db->where('salesreturn.id', $salesreturn_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($salesreturn_id);
        }
        return $result;
    }

    public function getorderitemsByID($salesreturn_id){
        $this->db->select('salesreturn_items.*')->from('salesreturn_items');
        $this->db->where('salesreturn_items.sale_id', $salesreturn_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getsalesreturnInvoiceByID($salesreturn_id){
        $this->db->select('salesreturn.*,customers.customers_id as customers_no,customers.name,customers.surname,customers.phone,customers.email,customers.company,customers.address');
        $this->db->from('salesreturn');
        $this->db->join('customers','customers.id = salesreturn.customer_id');
        $this->db->where('salesreturn.id', $salesreturn_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($salesreturn_id);
        }
        return $result;
    }

    public function getsalesreturn($brc_id,$status = null,$product_id = null,$customer_id = null,$start_date = null,$end_date = null){
        $this->datatables->select('salesreturn.id,salesreturn.brc_id,salesreturn.date,salesreturn.sale_no,salesreturn.status,salesreturn.attachment,salesreturn.note,salesreturn.customer_id,salesreturn.customer,salesreturn.total');
        $this->datatables->searchable('salesreturn.id,salesreturn.brc_id,salesreturn.date,salesreturn.sale_no,salesreturn.status,salesreturn.attachment,salesreturn.note,salesreturn.customer_id,salesreturn.customer,salesreturn.total');
        $this->datatables->orderable('salesreturn.id,salesreturn.brc_id,salesreturn.date,salesreturn.sale_no,salesreturn.status,salesreturn.attachment,salesreturn.note,salesreturn.customer_id,salesreturn.customer,salesreturn.total');
        $this->datatables->where('salesreturn.brc_id',$brc_id);
        if($product_id != null){
            $this->datatables->join('salesreturn_items','salesreturn.id = salesreturn_items.sale_id','');
        }
        if($status != null){
            //$this->datatables->where('salesreturn.status',$status);
        }
        if($product_id != null){
            $this->datatables->where('salesreturn_items.product_id',$product_id);
        }
        if($customer_id != null){
            $this->datatables->where('salesreturn.customer_id',$customer_id);
        }
        if($start_date != null){
            $this->datatables->where('salesreturn.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('salesreturn.date <=',$end_date);
        }
        $this->datatables->sort('salesreturn.id', 'desc');
        $this->datatables->from('salesreturn');
        return $this->datatables->generate('json');
    }

    public function getPaymentBySalereturnID($id){
        $this->db->select('account_voucher.*');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.salereturn_id', $id);
        $query = $this->db->get('');
        return $query->row_array();
    }
    
    public function getItemsBysalesreturnByPID($id =null,$pid =null){
        $this->db->select('salesreturn_items.*');
        $this->db->from('salesreturn_items');
        if($id !=null){
            $this->db->where('salesreturn_items.sale_id', $id);
        }
        if($pid !=null){
            $this->db->where('salesreturn_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getsalesreturnByCustomersIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('salesreturn.id,salesreturn.brc_id,salesreturn.date,salesreturn.sale_no as `bill_no`,salesreturn.customer_id,salesreturn.note,salesreturn.customer,SUM(salesreturn.grand_total) as `cr`');
        $this->db->from('salesreturn');
        $this->db->join('customers','customers.id = salesreturn.customer_id');
        $this->db->where('salesreturn.brc_id', $brc_id);
        $this->db->where('salesreturn.date >=', $start_date);
        $this->db->where('salesreturn.date <=', $end_date);
        $this->db->order_by('salesreturn.id','asc');
        $this->db->group_by('salesreturn.customer_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getsalesreturnByCustomersIDDetails($brc_id,$customer_id,$start_date,$end_date){
        $this->db->select('salesreturn.id,salesreturn.brc_id,salesreturn.date,salesreturn.sale_no as `bill_no`,salesreturn.customer_id,salesreturn.note,salesreturn.customer,salesreturn.grand_total as `cr`');
        $this->db->from('salesreturn');
        $this->db->join('customers','customers.id = salesreturn.customer_id');
        $this->db->join('customers','customers.id = salesreturn.customer_id');
        $this->db->where('salesreturn.brc_id', $brc_id);
        $this->db->where('salesreturn.customer_id', $customer_id);
        $this->db->where('salesreturn.date >=', $start_date);
        $this->db->where('salesreturn.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getsalesreturnByItemsIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('salesreturn.id,salesreturn.brc_id,salesreturn.date,salesreturn.sale_no as `bill_no`,salesreturn.customer_id,salesreturn.note,salesreturn_items.product_name,SUM(salesreturn_items.subtotal) as `cr`');
        $this->db->from('salesreturn');
        $this->db->join('customers','customers.id = salesreturn.customer_id');
        $this->db->join('salesreturn_items','salesreturn_items.sale_id = salesreturn.id');
        $this->db->where('salesreturn.brc_id', $brc_id);
        $this->db->where('salesreturn.date >=', $start_date);
        $this->db->where('salesreturn.date <=', $end_date);
        $this->db->order_by('salesreturn_items.id','asc');
        $this->db->group_by('salesreturn_items.product_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getsalesreturnByItemsIDDetails($brc_id,$item_id,$start_date,$end_date){
        $this->db->select('salesreturn.id,salesreturn.brc_id,salesreturn.date,salesreturn.sale_no as `bill_no`,salesreturn.customer_id,salesreturn.note,salesreturn_items.product_name,salesreturn_items.subtotal as `cr`');
        $this->db->from('salesreturn');
        $this->db->join('customers','customers.id = salesreturn.customer_id');
        $this->db->join('salesreturn_items','salesreturn_items.sale_id = salesreturn.id');
        $this->db->where('salesreturn.brc_id', $brc_id);
        $this->db->where('salesreturn_items.product_id', $item_id);
        $this->db->where('salesreturn.date >=', $start_date);
        $this->db->where('salesreturn.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getItemsBysalesreturnCartByPID($id =null,$pid =null){
        $this->db->select('salesreturn_items.*');
        $this->db->from('salesreturn_items');
        if($id !=null){
            $this->db->where('salesreturn_items.sale_id', $id);
        }
        if($pid !=null){
            $this->db->where('salesreturn_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->row_array();
    }

    public function getItemsBysalesreturnID($id =null){
        $this->db->select('salesreturn_items.*');
        $this->db->from('salesreturn_items');
        if($id !=null){
            $this->db->where('salesreturn_items.sale_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("salesreturn_items");
    }

}
