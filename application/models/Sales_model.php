<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sales_model extends MY_Model{

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
        $this->db->select('sales.*');
        $this->db->from('sales');
        if ($id != null) {
            $this->db->where('sales.id', $id);
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
            $this->db->update('sales', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item sales id " . $data['id'];
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
            $this->db->insert('sales', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item sales id " . $insert_id;
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
        $this->db->where('sale_items.sale_id', $id);
        $this->db->delete('sale_items');
        $this->db->where('id', $id);
        $this->db->delete('sales');
        $message   = DELETE_RECORD_CONSTANT . " On item sales id " . $id;
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

    public function getsalesNo($brc_id) {
       $last_row = $this->db->select('sale_no')->where('sales.brc_id',$brc_id)->order_by('id', "desc")->limit(1)->get('sales')->row_array();
        return $last_row;
    }

    public function printsalesInsertID($sales_id){
        $this->db->select('sales.*,customers.customers_id as customers_no,customers.name,customers.surname,customers.phone,customers.email,customers.company,customers.address');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->where('sales.id', $sales_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($sales_id);
        }
        return $result;
    }

    public function getorderitemsByID($sales_id){
        $this->db->select('sale_items.*')->from('sale_items');
        $this->db->where('sale_items.sale_id', $sales_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSalesInvoiceByID($sales_id){
        $this->db->select('sales.*,customers.customers_id as customers_no,customers.name,customers.surname,customers.phone,customers.email,customers.company,customers.address');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->where('sales.id', $sales_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($sales_id);
        }
        return $result;
    }

    public function getSales($brc_id,$status = null,$product_id = null,$customer_id = null,$start_date = null,$end_date = null){
        $this->datatables->select('sales.id,sales.brc_id,sales.date,sales.sale_no,sales.status,sales.attachment,sales.note,sales.customer_id,sales.customer,sales.total');
        $this->datatables->searchable('sales.id,sales.brc_id,sales.date,sales.sale_no,sales.status,sales.attachment,sales.note,sales.customer_id,sales.customer,sales.total');
        $this->datatables->orderable('sales.id,sales.brc_id,sales.date,sales.sale_no,sales.status,sales.attachment,sales.note,sales.customer_id,sales.customer,sales.total');
        $this->datatables->where('sales.brc_id',$brc_id);
        if($product_id != null){
            $this->datatables->join('sale_items','sales.id = sale_items.sale_id','');
        }
        if($status != null){
            //$this->datatables->where('sales.status',$status);
        }
        if($product_id != null){
            $this->datatables->where('sale_items.product_id',$product_id);
        }
        if($customer_id != null){
            $this->datatables->where('sales.customer_id',$customer_id);
        }
        if($start_date != null){
            $this->datatables->where('sales.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('sales.date <=',$end_date);
        }
        $this->datatables->sort('sales.id', 'desc');
        $this->datatables->from('sales');
        return $this->datatables->generate('json');
    }

    public function getPaymentBySaleID($id){
        $this->db->select('account_voucher.*');
        $this->db->from('account_voucher');
        $this->db->where('account_voucher.sale_id', $id);
        $query = $this->db->get('');
        return $query->row_array();
    }
    
    public function getItemsBysalesByPID($id =null,$pid =null){
        $this->db->select('sale_items.*');
        $this->db->from('sale_items');
        if($id !=null){
            $this->db->where('sale_items.sale_id', $id);
        }
        if($pid !=null){
            $this->db->where('sale_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getSalesByCustomersIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.brc_id,sales.date,sales.sale_no as `bill_no`,sales.customer_id,sales.note,sales.customer,SUM(sales.grand_total) as `cr`');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->where('sales.date >=', $start_date);
        $this->db->where('sales.date <=', $end_date);
        $this->db->order_by('sales.id','asc');
        $this->db->group_by('sales.customer_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getSalesByCustomersIDDetails($brc_id,$customer_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.brc_id,sales.date,sales.sale_no as `bill_no`,sales.customer_id,sales.note,sales.customer,sales.grand_total as `cr`');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->where('sales.customer_id', $customer_id);
        $this->db->where('sales.date >=', $start_date);
        $this->db->where('sales.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getSalesByItemsIDSummary($brc_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.brc_id,sales.date,sales.sale_no as `bill_no`,sales.customer_id,sales.note,sale_items.product_name,SUM(sale_items.subtotal) as `cr`');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->join('sale_items','sale_items.sale_id = sales.id');
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->where('sales.date >=', $start_date);
        $this->db->where('sales.date <=', $end_date);
        $this->db->order_by('sale_items.id','asc');
        $this->db->group_by('sale_items.product_id');
        $query = $this->db->get('');
        return $query->result_array();
    }
    
    public function getSalesByItemsIDDetails($brc_id,$item_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.brc_id,sales.date,sales.sale_no as `bill_no`,sales.customer_id,sales.note,sale_items.product_name,sale_items.subtotal as `cr`');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->join('sale_items','sale_items.sale_id = sales.id');
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->where('sale_items.product_id', $item_id);
        $this->db->where('sales.date >=', $start_date);
        $this->db->where('sales.date <=', $end_date);
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getItemsBysalesCartByPID($id =null,$pid =null){
        $this->db->select('sale_items.*');
        $this->db->from('sale_items');
        if($id !=null){
            $this->db->where('sale_items.sale_id', $id);
        }
        if($pid !=null){
            $this->db->where('sale_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->row_array();
    }

    public function getItemsBysalesID($id =null){
        $this->db->select('sale_items.*');
        $this->db->from('sale_items');
        if($id !=null){
            $this->db->where('sale_items.sale_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("sale_items");
    }

}
