<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Quotations_model extends MY_Model{

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
        $this->db->select('quotes.*');
        $this->db->from('quotes');
        if ($id != null) {
            $this->db->where('quotes.id', $id);
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
            $this->db->update('quotes', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item quotes id " . $data['id'];
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
            $this->db->insert('quotes', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item quotes id " . $insert_id;
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
    public function remove($id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('quote_items.quote_id', $id);
        $this->db->delete('quote_items');
        $this->db->where('id', $id);
        $this->db->delete('quotes');
        $message   = DELETE_RECORD_CONSTANT . " On item quotes id " . $id;
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

    public function check_quotations_exists($quotes_no){
        $this->db->where(array('quotes_no' => $quotes_no));
        $query = $this->db->get('quotes');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function lastRecord() {
       $last_row = $this->db->select('quotes_no')->order_by('id', "desc")->limit(1)->get('quotes')->row_array();
        return $last_row;
    }

    public function printquotationsInsertID($quotes_id){
        $this->db->select('quotes.*,customers.customers_id as customers_no,customers.name,customers.surname,customers.phone,customers.email,customers.company,customers.address');
        $this->db->from('quotes');
        $this->db->join('customers','customers.id = quotes.customer_id');
        $this->db->where('quotes.id', $quotes_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($quotes_id);
        }
        return $result;
    }

    public function getorderitemsByID($quotes_id){
        $this->db->select('quote_items.*')->from('quote_items');
        $this->db->where('quote_items.quote_id', $quotes_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getQuotations($brc_id,$status = null,$product_id = null,$customer_id = null,$start_date = null,$end_date = null){
        $this->datatables->select('quotes.id,quotes.brc_id,quotes.date,quotes.quotes_no,quotes.status,quotes.attachment,quotes.note,quotes.customer_id,quotes.customer,quotes.total');
        $this->datatables->searchable('quotes.id,quotes.brc_id,quotes.date,quotes.quotes_no,quotes.status,quotes.attachment,quotes.note,quotes.customer_id,quotes.customer,quotes.total');
        $this->datatables->orderable('quotes.id,quotes.brc_id,quotes.date,quotes.quotes_no,quotes.status,quotes.attachment,quotes.note,quotes.customer_id,quotes.customer,quotes.total');
        $this->datatables->where('quotes.brc_id',$brc_id);
        if($product_id != null){
            $this->datatables->join('quote_items','quotes.id = quote_items.quote_id','');
        }
        if($status != null){
            //$this->datatables->where('quotes.status',$status);
        }
        if($product_id != null){
            $this->datatables->where('quote_items.product_id',$product_id);
        }
        if($customer_id != null){
            $this->datatables->where('quotes.customer_id',$customer_id);
        }
        if($start_date != null){
            $this->datatables->where('quotes.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('quotes.date <=',$end_date);
        }
        $this->datatables->sort('quotes.id', 'desc');
        $this->datatables->from('quotes');
        return $this->datatables->generate('json');
    }


    public function getItemsByQuotesByPID($id =null,$pid =null){
        $this->db->select('quote_items.*');
        $this->db->from('quote_items');
        if($id !=null){
            $this->db->where('quote_items.quote_id', $id);
        }
        if($pid !=null){
            $this->db->where('quote_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getItemsByQuotesCartByPID($id =null,$pid =null){
        $this->db->select('quote_items.*');
        $this->db->from('quote_items');
        if($id !=null){
            $this->db->where('quote_items.quote_id', $id);
        }
        if($pid !=null){
            $this->db->where('quote_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->row_array();
    }

    public function getItemsByQuotesID($id =null){
        $this->db->select('quote_items.*');
        $this->db->from('quote_items');
        if($id !=null){
            $this->db->where('quote_items.quote_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("quote_items");
    }

}
