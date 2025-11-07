<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Products_model extends MY_Model
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
    public function get($id = null, $subtype=null){
        $this->db->select('products.*,categories.name as category,units.name as unit_name');
        $this->db->from('products');
        $this->db->join('categories','categories.id = products.category_id','left');
        $this->db->join('units','units.id = products.unit','left');
        if ($id != null) {
            $this->db->where('products.id', $id);
        } else {
            if($subtype != null){
                $this->db->where('products.product_subtype', $subtype);
            }
            $this->db->order_by('id');
        }


        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function count_products($search = null, $subtype = null)
    {
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('units', 'units.id = products.unit', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('products.name', $search);
            $this->db->or_like('products.code', $search);
            $this->db->or_like('categories.name', $search);
            $this->db->or_like('units.name', $search);
            $this->db->group_end();
        }

        if ($subtype != null) {
            $this->db->where('products.product_subtype', $subtype);
        }

        return $this->db->count_all_results();
    }

    public function get_products($limit, $offset, $search = null, $subtype = null)
    {
        $this->db->select('products.*, categories.name as category, units.name as unit_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('units', 'units.id = products.unit', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('products.name', $search);
            $this->db->or_like('products.code', $search);
            $this->db->or_like('categories.name', $search);
            $this->db->or_like('units.name', $search);
            $this->db->group_end();
        }

        if ($subtype != null) {
            $this->db->where('products.product_subtype', $subtype);
        }

        $this->db->order_by('products.id', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
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
            $this->db->update('products', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  item products id " . $data['id'];
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
            $this->db->insert('products', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On item products id " . $insert_id;
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
        $this->db->delete('products');
        $message   = DELETE_RECORD_CONSTANT . " On item products id " . $id;
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
    
    public function getlastrecord() {
       $last_row = $this->db->select('code')->order_by('id', "desc")->limit(1)->get('products')->row_array();
        return $last_row;
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
    
    public function getTotalaccounthead($account_type_id) {
        $query = "SELECT count(*) as `total_code_no` FROM `productsaccountshead`  where productsaccountshead.new_accounts_id=" . $this->db->escape($account_type_id);
        $query = $this->db->query($query);
        return $query->row();
    }
    
    public function addaccountshead($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->where('item_id',$data["item_id"])->where('new_accounts_id',$data["new_accounts_id"])->update("productsaccountshead", $data);
        } else {
            $this->db->insert("productsaccountshead", $data);
            $id = $this->db->insert_id();
            return $id ;
        }
    }

}
