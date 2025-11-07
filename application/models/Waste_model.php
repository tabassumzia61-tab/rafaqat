<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Waste_model extends MY_Model{

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
        $this->db->select('waste.*');
        $this->db->from('waste');
        if ($id != null) {
            $this->db->where('waste.id', $id);
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
            $this->db->update('waste', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item waste id " . $data['id'];
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
            $this->db->insert('waste', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item waste id " . $insert_id;
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
        $this->db->where('waste_items.waste_id', $id);
        $this->db->delete('waste_items');
        $this->db->where('id', $id);
        $this->db->delete('waste');
        $message   = DELETE_RECORD_CONSTANT . " On item waste id " . $id;
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

    public function getwasteNo() {
       $last_row = $this->db->select('bill_no')->order_by('id', "desc")->limit(1)->get('waste')->row_array();
        return $last_row;
    }

    public function printwasteInsertID($waste_id){
        $this->db->select('waste.*,customers.customers_id as customers_no,customers.name,customers.surname,customers.phone,customers.email,customers.company,customers.address');
        $this->db->from('waste');
        $this->db->join('customers','customers.id = waste.customer_id');
        $this->db->where('waste.id', $waste_id);
        $query  = $this->db->get();
        $result =  $query->row_array(); 
        if (!empty($result)) {
            $result['orderitem'] = $this->getorderitemsByID($waste_id);
        }
        return $result;
    }

    public function getorderitemsByID($waste_id){
        $this->db->select('waste_items.*')->from('waste_items');
        $this->db->where('waste_items.waste_id', $waste_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getwaste($status = null,$product_id = null,$customer_id = null,$start_date = null,$end_date = null){
        $this->datatables->select('waste.id,waste.date,waste.bill_no,waste.status,waste.attachment,waste.note,waste.customer_id,waste.customer,waste.total');
        $this->datatables->searchable('waste.id,waste.date,waste.bill_no,waste.status,waste.attachment,waste.note,waste.customer_id,waste.customer,waste.total');
        $this->datatables->orderable('waste.id,waste.date,waste.bill_no,waste.status,waste.attachment,waste.note,waste.customer_id,waste.customer,waste.total');
        if($product_id != null){
            $this->datatables->join('waste_items','waste.id = waste_items.waste_id','');
        }
        if($status != null){
            //$this->datatables->where('waste.status',$status);
        }
        if($product_id != null){
            $this->datatables->where('waste_items.product_id',$product_id);
        }
        if($customer_id != null){
            $this->datatables->where('waste.customer_id',$customer_id);
        }
        if($start_date != null){
            $this->datatables->where('waste.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('waste.date <=',$end_date);
        }
        $this->datatables->sort('waste.id', 'desc');
        $this->datatables->from('waste');
        return $this->datatables->generate('json');
    }


    public function getItemsBywasteByPID($id =null,$pid =null){
        $this->db->select('waste_items.*');
        $this->db->from('waste_items');
        if($id !=null){
            $this->db->where('waste_items.waste_id', $id);
        }
        if($pid !=null){
            $this->db->where('waste_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function getItemsBywasteCartByPID($id =null,$pid =null){
        $this->db->select('waste_items.*');
        $this->db->from('waste_items');
        if($id !=null){
            $this->db->where('waste_items.waste_id', $id);
        }
        if($pid !=null){
            $this->db->where('waste_items.product_id', $pid);
        }
        $query = $this->db->get('');
        return $query->row_array();
    }

    public function getItemsBywasteID($id =null){
        $this->db->select('waste_items.*');
        $this->db->from('waste_items');
        if($id !=null){
            $this->db->where('waste_items.waste_id', $id);
        }
        $query = $this->db->get('');
        return $query->result_array();
    }

    public function remove_detial_product($d_delete_array) {
        $this->db->where_in("id", $d_delete_array)->delete("waste_items");
    }

}
