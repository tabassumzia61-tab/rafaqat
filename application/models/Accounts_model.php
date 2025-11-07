<?php
class Accounts_model extends CI_model {

    public function getaccountstype() {
        $this->db->select('accounts_type.*');
        $this->db->from('accounts_type');
        $this->db->where('accounts_type.is_active','no');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getnewaccount($id = null) {
        if (!empty($id)) {
            $this->db->select('accountsnew.*');
            $this->db->from('accountsnew');
            $this->db->where('accountsnew.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('accountsnew.*');
            $this->db->from('accountsnew');
            $this->db->order_by('accountsnew.accounts_type_id','ASC');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
    
    public function getaccountsBytypeID($id = null) {
        $this->db->select('accounts_type.*')->from('accounts_type');
        if ($id != null) {
            $this->db->where('accounts_type.id', $id);
        } else {
            $this->db->order_by('accounts_type.id', 'ASC');
        }
        $query = $this->db->get();
        if ($id != null) {
            $accounts_type = $query->result_array();
            $array = array();
            if (!empty($accounts_type)) {
                foreach ($accounts_type as $accounts_key => $accounts_value) {
                    $class = new stdClass();
                    $class->id = $accounts_value['id'];
                    $class->code = $accounts_value['code'];
                    $class->name = $accounts_value['name'];
                    $class->note = $accounts_value['note'];
                    $class->newaccounts = $this->getnewaccountsByID($accounts_value['id']);
                    $array[] = $class;
                }
            }
            return $array;
        } else {
            $accounts_type = $query->result_array();
            $array = array();
            if (!empty($accounts_type)) {
                foreach ($accounts_type as $accounts_key => $accounts_value) {
                    $class = new stdClass();
                    $class->id = $accounts_value['id'];
                    $class->code = $accounts_value['code'];
                    $class->name = $accounts_value['name'];
                    $class->note = $accounts_value['note'];
                    $class->newaccounts = $this->getnewaccountsByID($accounts_value['id']);
                    $array[] = $class;
                }
            }
            return $array;
        }
    }
    
    public function getnewaccountsByID($accounts_type_id) {
        $this->db->select('accountsnew.*')->from('accountsnew');
        $this->db->where('accountsnew.accounts_type_id', $accounts_type_id);
        $this->db->order_by('accountsnew.id', 'asc');
        $query = $this->db->get();
        return $classes = $query->result();
    }
    
    public function getAccountsNameByID($brc_id,$accounts_head_id,$accounts_type_id) {
        $this->db->select('accountshead.*')->from('accountshead');
        $this->db->where('accountshead.accounts_head_id', $accounts_head_id);
        $this->db->where('accountshead.new_accounts_id', $accounts_type_id);
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accountshead.id', 'asc');
        $query = $this->db->get();
        return $classes = $query->result();
    }
    
    public function getAccountsItemByID($brc_id,$accounts_head_id,$accounts_type_id) {
        $this->db->select('productsaccountshead.*')->from('productsaccountshead');
        $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);
        $this->db->where('productsaccountshead.new_accounts_id', $accounts_type_id);
        // $this->db->group_start();
        // $this->db->where('accountshead.brc_id',NULL);
        // $this->db->or_where('accountshead.brc_id',$brc_id);
        // $this->db->group_end();
        $this->db->order_by('productsaccountshead.id', 'asc');
        $query = $this->db->get();
        return $classes = $query->result();
    }
    
    public function getAccountsByID($brc_id,$accounts_head_id) {
        $this->db->select('accountshead.*')->from('accountshead');
        $this->db->where('accountshead.accounts_head_id', $accounts_head_id);
        //$this->db->where('accountshead.new_accounts_id', $accounts_type_id);
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accountshead.id', 'asc');
        $query = $this->db->get();
        return $classes = $query->result_array();
    }
    
    function new_check_data_exists($name,$accounts_type_id) {
        $this->db->where('name', $name);
        $this->db->where('accounts_type_id', $accounts_type_id);
        $query = $this->db->get('accountsnew');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function new_check_exists($str) {
        $name = $this->security->xss_clean($str);
        $accounts_type_id = $this->input->post('accounts_type_id');
        $res = $this->new_check_data_exists($name,$accounts_type_id);
        if ($res) {
            $id = $this->input->post('id');
            if (isset($id)) {
                if ($res->id == $id) {
                    return TRUE;
                }
            }
            $this->form_validation->set_message('new_check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function addnewaccounts($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("accountsnew", $data);
        } else {
            $this->db->insert("accountsnew", $data);
        }
    }

    public function getTotalnewaccount($accounts_type_id) {
        $query = "SELECT count(*) as `total_code_no` FROM `accountsnew`  where accountsnew.accounts_type_id=" . $this->db->escape($accounts_type_id);
        $query = $this->db->query($query);
        return $query->row();
    }
    
    public function getaccountsheadByID($accounts_type_id,$brc_id) {
        $this->db->select('accountshead.*')->from('accountshead');
        $this->db->where('accountshead.new_accounts_id', $accounts_type_id);
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        //$this->db->where('accountshead.is_active', 'yes');
        $this->db->order_by('accountshead.id', 'asc');
        $query = $this->db->get();
        return $classes = $query->result_array();
    }
    
    public function addaccountshead($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("accountshead", $data);
        } else {
            $this->db->insert("accountshead", $data);
            $id = $this->db->insert_id();
            return $id ;
        }
    }
    
    function head_check_data_exists($brc_id,$name,$accounts_head_id,$account_type_id){
        $this->db->where('name', $name);
        $this->db->where('accounts_head_id', $accounts_head_id);
        $this->db->where('new_accounts_id', $account_type_id);
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $query = $this->db->get('accountshead');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function head_check_exists($str) {
        $name = $this->security->xss_clean($str);
        if(!empty($this->input->post('brc_id'))){
            $brc_id = $this->input->post('brc_id');
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $accounts_head_id = $this->input->post('accounts_head_id');
        $account_type_id = $this->input->post('account_type_id');
        $res = $this->head_check_data_exists($brc_id,$name,$accounts_head_id,$account_type_id);
        if ($res) {
            $id = $this->input->post('id');
            if (isset($id)) {
                if ($res->id == $id) {
                    return TRUE;
                }
            }
            $this->form_validation->set_message('head_check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function getaccounthead($id = null) {
        if (!empty($id)) {
            $this->db->select('accountshead.*');
            $this->db->from('accountshead');
            $this->db->where('accountshead.id',$id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('accountshead.*');
            $this->db->from('accountshead');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
    
    public function getaccountheadall($brc_id) {
        $this->db->select('accountshead.id,accountshead.code,accountshead.name');
        $this->db->from('accountshead');
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accountshead.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    
    public function addopeningbalance($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("opening_balances", $data);
        } else {
            $this->db->insert("opening_balances", $data);
            $id = $this->db->insert_id();
            return $id ;
        }
    }
    
    public function getob($brc_id,$id) {
        $query = "SELECT opening_balances.* FROM `opening_balances`  where opening_balances.brc_id=" . $this->db->escape($brc_id)." and opening_balances.acc_head_id=" . $this->db->escape($id);
        $query = $this->db->query($query);
        return $query->row_array();
    }

    public function getTotalaccounthead($account_type_id) {
        $query = "SELECT count(*) as `total_code_no` FROM `accountshead`  where accountshead.new_accounts_id=" . $this->db->escape($account_type_id);
        $query = $this->db->query($query);
        return $query->row();
    }
    
    public function getAccountsHeadBytypeIDByID($accounts_type_id,$brc_id) {
        $this->db->select('accountshead.id,accountshead.name as type')->from('accountshead');
        $this->db->where('accountshead.new_accounts_id', $accounts_type_id);
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accountshead.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getAccountsHeadByTypesIDCoa($brc_id) {
        $this->db->select('accountshead.name as `account_name`,accountshead.code as `account_code`,accountsnew.name as `account_type`,accounts_type.name as `account_head`')->from('accountshead');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type',' accounts_type.id = accountsnew.accounts_type_id');
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accounts_type.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    /* Account Voucher  */
    
    public function getAccountsNameByIDs($type_id,$brc_id) {
        $this->db->select('accounts_type.*')->from('accounts_type');
        $this->db->join('accountsnew','accountsnew.accounts_type_id = accounts_type.id');
        $this->db->join('accountshead',' accountshead.new_accounts_id = accountsnew.id');
        $this->db->where_in('accounts_type.id', $type_id);
        //$this->db->where('accountshead.is_posted', 1);
        $this->db->where('accountshead.is_active', 'yes');
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accounts_type.id', 'asc');
        $this->db->group_by('accounts_type.id');
        $parent = $this->db->get();
        $acname = $parent->result_array();
        $i=0;
        foreach($acname as $cat){
            //print_r($p_cat);
            $acname[$i]['sub'] = $this->getAccountName($brc_id,$cat['id']);
            
            $i++;
        }
        return $acname;
    }
    
    public function getAccountName($brc_id,$type_id){
        $this->db->select('accountshead.*');
        $this->db->from('accountshead');
        $this->db->where('accountshead.accounts_head_id', $type_id);
        //$this->db->where('accountshead.is_posted', 1);
        $this->db->where('accountshead.is_active', 'yes');
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accountshead.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getAccountsModeByHeadID($accounts_type_id,$brc_id) {
        $this->db->select('accountshead.id,accountshead.name as type')->from('accountshead');
        $this->db->where('accountshead.new_accounts_id', $accounts_type_id);
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accountshead.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* General Ledger Reports */
    
    // public function getOpeningBalanceByAccount($brc_id,$account_name_id){
    //     $this->db->select('SUM(opening_balances.debit_amount) as dr ,SUM(opening_balances.credit_amount) as cr,opening_balances.date')->from('opening_balances');
    //     $this->db->join('accountshead','accountshead.id = opening_balances.acc_head_id');
    //     $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
    //     $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
    //     $this->db->where("opening_balances.acc_head_id",$account_name_id);
    //     //$this->db->where("DATE_FORMAT(opening_balances.date, '%Y-%m-%d')>=",$start_date);
    //     //$this->db->where("DATE_FORMAT(opening_balances.date, '%Y-%m-%d')<=",$end_date);
    //     $this->db->where('opening_balances.brc_id', $brc_id);
    //     //$this->db->order_by('opening_balances.date', 'asc');
    //     $query = $this->db->get();
    //     return $query->row_array();
    // }
    
    public function getSalesPheadByCustomersID($brc_id,$account_name_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.date,sales.sale_no as `bill_no`,sales.note as `note`,sales.grand_total as `cr`,customers.name as `account_name`');
        $this->db->from('sales');
        $this->db->join('sale_items','sale_items.sale_id = sales.id');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->where('sale_items.sale_item_head_id', $account_name_id);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->order_by('sales.date', 'asc');
        //$this->db->group_by('sales.par_rec_acc_head_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPaymentPheadByCustomersID($brc_id,$account_name_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.date,sales.sale_no as `bill_no`,sales.note as `note`,sales.grand_total as `dr`,customers.name as `account_name`');
        $this->db->from('sales');
        $this->db->join('sale_items','sale_items.sale_id = sales.id');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->where('sale_items.sale_item_head_id', $account_name_id);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->order_by('sales.date', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getSalesByCustomersID($brc_id,$account_name_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.date,sales.sale_no as `bill_no`,sales.note as `note`,sales.grand_total as `dr`,customers.name as `account_name`');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->where('sales.customer_id', $account_name_id);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->order_by('sales.date', 'asc');
        //$this->db->group_by('sales.par_rec_acc_head_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPaymentsByCustomersID($brc_id,$account_name_id,$start_date = null,$end_date= null) {
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.credit_amount as `cr`,accountshead.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('customers','customers.id = account_voucher.customer_id');
        $this->db->join('accountshead','accountshead.id = account_voucher.par_acc_head_id');
        $this->db->where('account_voucher.customer_id', $account_name_id);
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
    
    /* General Ledger Acccount techical function */
    
    /* Purchases */
    
    public function getPurchaseByPurchasessID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total as `dr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        // if($account_name_id !=null){
        //     $this->db->where('purchase_items.purchase_item_head_id', $account_name_id);
        // }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByItemID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total as `dr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('products','products.id = purchase_items.product_id');
        $this->db->join('accountsnew','accountsnew.id = products.acc_type_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('accounts_type.id', $accounts_head_id);
        if($accounts_head_id !=null){
            $this->db->where('products.acc_type_id', $account_type_id);    
        }
        //if($account_name_id !=null){
            //$this->db->where('purchase_items.product_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByCostID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total_discount as `discount`,purchases.total_tax as `tax`,purchases.grand_total as `dr`,productsaccountshead.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_cost_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByGstID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total_tax as `dr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.gst_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.gst_head_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByDiscountID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total_discount as `cr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.discount_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.discount_head_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByDispervID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total_discount as `cr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.discount_pervious_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.discount_pervious_head_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseBySuppliersID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.grand_total as `cr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountsnew','accountsnew.id = supplier.acc_type_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('accounts_type.id', $accounts_head_id);
        $this->db->where('purchases.supplier_type', 2);
        if($account_type_id !=null){
            $this->db->where('supplier.acc_type_id', $account_type_id);    
        }
        if($start_date != null){
            $this->datatables->where('purchases.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('purchases.date <=',$end_date);
        }
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchasesPaymentsByParentsID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.credit_amount as `cr`,supplier.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('supplier','supplier.id = account_voucher.supplier_id');
        $this->db->join('accountshead','accountshead.id = account_voucher.par_acc_head_id');
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        if($start_date != null){
            $this->datatables->where('account_voucher.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('account_voucher.date <=',$end_date);
        }
        $this->db->where('account_voucher.brc_id', $brc_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* END Purchases END */
    
    /* Opening Balance */
    public function getPurchaseByItemObID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total as `ob_dr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('products','products.id = purchase_items.product_id');
        $this->db->join('accountsnew','accountsnew.id = products.acc_type_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('accounts_type.id', $accounts_head_id);
        if($accounts_head_id !=null){
            $this->db->where('products.acc_type_id', $account_type_id);    
        }
        //if($account_name_id !=null){
            //$this->db->where('purchase_items.product_id', $account_name_id);
        //}
        $prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$prev_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseBySuppliersObID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('purchases.grand_total as `ob_cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountsnew','accountsnew.id = supplier.acc_type_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('purchases.supplier_type', 2);
        $this->db->where('accounts_type.id', $accounts_head_id);
        if($account_type_id !=null){
            $this->db->where('supplier.acc_type_id', $account_type_id);    
        }
        $prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$prev_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByGstObID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total_tax as `ob_dr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.gst_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.gst_head_id', $account_name_id);
        //}
        $prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$prev_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchasesPaymentsByParentsObID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('account_voucher.credit_amount as `ob_cr`');
        $this->db->from('account_voucher');
        $this->db->join('supplier','supplier.id = account_voucher.supplier_id');
        $this->db->join('accountshead','accountshead.id = account_voucher.par_acc_head_id');
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
         $prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where("DATE_FORMAT(account_voucher.date, '%Y-%m-%d')<=",$prev_date);
        $this->db->where('account_voucher.brc_id', $brc_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* END Opening Balance END */
    
    /* Purchase Return */
    
    public function getPurchaseRntByPurchasessID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.gross_amount as `cr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        // if($account_name_id !=null){
        //     $this->db->where('purchase_items.purchase_item_head_id', $account_name_id);
        // }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseRntByItemID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.gross_amount as `cr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('products','products.id = purchase_items.product_id');
        if($accounts_head_id !=null){
            $this->db->where('products.acc_type_id', $account_type_id);    
        }
        //if($account_name_id !=null){
            //$this->db->where('purchase_items.product_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseRntByCostID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total_discount as `discount`,purchases.total_tax as `tax`,purchases.grand_total as `cr`,productsaccountshead.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_cost_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseRntByGstID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total_tax as `cr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.gst_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.gst_head_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        //$this->db->group_by('purchases.par_rec_acc_head_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseRntByDiscountID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.total_discount as `dr`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.discount_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.discount_head_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        //$this->db->group_by('purchases.par_rec_acc_head_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseRntBySuppliersID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,purchases.grand_total as `dr`,productsaccountshead.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        $this->db->where('purchases.supplier_id', $account_name_id);
        if($accounts_head_id !=null){
            $this->db->where('supplier.acc_type_id', $accounts_head_id);    
        }
        if($start_date != null){
            $this->datatables->where('purchases.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('purchases.date <=',$end_date);
        }
        $this->db->where('purchases.brc_id', $brc_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchasesRntPaymentsByParentsID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.credit_amount as `dr`,supplier.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('supplier','supplier.id = account_voucher.supplier_id');
        $this->db->join('accountshead','accountshead.id = account_voucher.par_acc_head_id');
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        if($start_date != null){
            $this->datatables->where('account_voucher.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('account_voucher.date <=',$end_date);
        }
        $this->db->where('account_voucher.brc_id', $brc_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* END Purchase Return END */
    
    /* Expenses Bill  */
    
    public function getParentExpensesAccounntsByID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id= null,$start_date= null, $end_date= null){
        $this->db->select('expenses_bill.id,expenses_bill.date,expenses_bill.invoice_no as `bill_no`,expenses_bill.note as `note`,expenses_bill_items.credit_amount as `cr`,accountshead.name as `account_name`');
        $this->db->from('expenses_bill');
        $this->db->join('expenses_bill_items','expenses_bill_items.expenses_bill_id = expenses_bill.id');
        $this->db->join('accountshead','accountshead.id = expenses_bill_items.acc_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($account_type_id !=null){
            $this->db->where('accountsnew.id', $account_type_id);    
        }
        $this->db->where('expenses_bill.brc_id', $brc_id);
        $this->db->where('expenses_bill.par_acc_head_id', $account_name_id);
        $this->db->where('expenses_bill.date >=', $start_date);
        $this->db->where('expenses_bill.date <=', $end_date);
        $this->db->order_by('expenses_bill.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getExpensesAccounntsByID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id= null,$start_date= null, $end_date= null){
        $this->db->select('expenses_bill.id,expenses_bill.date,expenses_bill.invoice_no as `bill_no`,expenses_bill.note as `note`,expenses_bill_items.debit_amount as `dr`,accountshead.name as `account_name`');
        $this->db->from('expenses_bill');
        $this->db->join('expenses_bill_items','expenses_bill_items.expenses_bill_id = expenses_bill.id');
        $this->db->join('accountshead','accountshead.id = expenses_bill.par_acc_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($account_type_id !=null){
            $this->db->where('accountsnew.id', $account_type_id);    
        }
        $this->db->where('expenses_bill.brc_id', $brc_id);
        $this->db->where('expenses_bill_items.acc_head_id', $account_name_id);
        $this->db->where('expenses_bill.date >=', $start_date);
        $this->db->where('expenses_bill.date <=', $end_date);
        $this->db->order_by('expenses_bill.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getParentSupplierExpensesAccounntsByID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id= null,$start_date= null, $end_date= null){
        $this->db->select('expenses_bill.id,expenses_bill.date,expenses_bill.invoice_no as `bill_no`,expenses_bill.note as `note`,expenses_bill_items.credit_amount as `cr`,accountshead.name as `account_name`');
        $this->db->from('expenses_bill');
        $this->db->join('expenses_bill_items','expenses_bill_items.expenses_bill_id = expenses_bill.id');
        $this->db->join('accountshead','accountshead.id = expenses_bill_items.acc_head_id');
        //$this->db->join('supplier','supplier.id = expenses_bill.supplier_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($account_type_id !=null){
            $this->db->where('accountsnew.id', $account_type_id);    
        }
        $this->db->where('expenses_bill.brc_id', $brc_id);
        $this->db->where('expenses_bill.supplier_id', $account_name_id);
        $this->db->where('expenses_bill.date >=', $start_date);
        $this->db->where('expenses_bill.date <=', $end_date);
        $this->db->order_by('expenses_bill.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getSupplierExpensesAccounntsByID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id= null,$start_date= null, $end_date= null){
        $this->db->select('expenses_bill.id,expenses_bill.date,expenses_bill.invoice_no as `bill_no`,expenses_bill.note as `note`,expenses_bill_items.debit_amount as `dr`,supplier.name as `account_name`');
        $this->db->from('expenses_bill');
        $this->db->join('expenses_bill_items','expenses_bill_items.expenses_bill_id = expenses_bill.id');
        $this->db->join('supplier','supplier.id = expenses_bill.supplier_id');
        $this->db->where('expenses_bill.brc_id', $brc_id);
        $this->db->where('expenses_bill_items.acc_head_id', $account_name_id);
        $this->db->where('expenses_bill.date >=', $start_date);
        $this->db->where('expenses_bill.date <=', $end_date);
        $this->db->order_by('expenses_bill.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* END Expenses Bill END */
    
    /* END General Ledger Acccount Technical Function END */
    
    /* Trial Balance Acccount Technical Function */
    
    public function getAccountsHeadByTypesIDTrial($brc_id) {
        $this->db->select('accountshead.id as `acc_name_id`,accountsnew.id as `acc_type_id`,accounts_type.id as `acc_head_id`,accountshead.name as `account_name`,accountshead.code as `account_code`,accountsnew.name as `account_type`,accounts_type.name as `account_head`')->from('accounts_type');
        $this->db->join('accountsnew','accountsnew.accounts_type_id = accounts_type.id','left');
        $this->db->join('accountshead',' accountshead.new_accounts_id = accountsnew.id','left');
        $this->db->group_start();
        $this->db->where('accountshead.brc_id',NULL);
        $this->db->or_where('accountshead.brc_id',$brc_id);
        $this->db->group_end();
        $this->db->order_by('accountsnew.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* Purchases */
    
    public function getPurchaseID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total as `dr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        // if($account_name_id !=null){
        //     $this->db->where('purchase_items.purchase_item_head_id', $account_name_id);
        // }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchase_items.purchase_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByInventoriesID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total as `dr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('products','products.id = purchase_items.product_id');
        $this->db->join('accountsnew','accountsnew.id = products.acc_type_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('accounts_type.id', $accounts_head_id);
        if($accounts_head_id !=null){
            $this->db->where('products.acc_type_id', $account_type_id);    
        }
        //if($account_name_id !=null){
            //$this->db->where('purchase_items.product_id', $account_name_id);
        //}
        $prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$prev_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByCostOverID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.grand_total as `dr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_cost_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByGstAccID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total_tax as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.gst_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('accountshead.id', $account_name_id);
        //}
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByDisPervioustrialID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total_discount as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.discount_pervious_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('accountshead.id', $account_name_id);
        //}
        $prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$prev_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseByDistrialID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total_discount as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.discount_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('accountshead.id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseBySupplierstrialID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('purchases.grand_total as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountsnew','accountsnew.id = supplier.acc_type_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('accounts_type.id', $accounts_head_id);
        $this->db->where('purchases.supplier_type', 2);
        if($account_type_id !=null){
            $this->db->where('supplier.acc_type_id', $account_type_id);    
        }
        //$prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        //$this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$prev_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchasesPaymentsByParentstrialID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('account_voucher.credit_amount as `dr`');
        $this->db->from('account_voucher');
        //$this->db->join('supplier','supplier.id = account_voucher.supplier_id');
        $this->db->join('accountshead','accountshead.id = account_voucher.par_acc_head_id');
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        // if($start_date != null){
        //     $this->datatables->where('account_voucher.date >=',$start_date);
        // }
        if($end_date != null){
            $this->datatables->where('account_voucher.date <=',$end_date);
        }
        $this->db->where('account_voucher.brc_id', $brc_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* END Purchases END */
    
    /* Purchase Return */
    
    public function getTrialPurchaseRnt($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.gross_amount as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        // if($account_name_id !=null){
        //     $this->db->where('purchase_items.purchase_item_head_id', $account_name_id);
        // }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getTrialPurchaseRntByItemID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.gross_amount as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('products','products.id = purchase_items.product_id');
        if($accounts_head_id !=null){
            $this->db->where('products.acc_type_id', $account_type_id);    
        }
        //if($account_name_id !=null){
            //$this->db->where('purchase_items.product_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getTrialPurchaseRntByCostID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.grand_total as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_cost_item_head_id');
        if($accounts_head_id !=null){
            $this->db->where('productsaccountshead.accounts_head_id', $accounts_head_id);    
        }
        if($account_type_id !=null){
            $this->db->where('productsaccountshead.new_accounts_id', $account_type_id);    
        }
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        $this->db->group_by('purchases.id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getTrialPurchaseRntByGstID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total_tax as `cr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.gst_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.gst_head_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        //$this->db->group_by('purchases.par_rec_acc_head_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getTrialPurchaseRntByDiscountID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null){
        $this->db->select('purchases.total_discount as `dr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('accountshead','accountshead.id = purchases.discount_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        if($accounts_head_id !=null){
            $this->db->where('accounts_type.id', $accounts_head_id);    
        }
        //if($account_name_id !=null){
            $this->db->where('purchases.discount_head_id', $account_name_id);
        //}
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->order_by('purchases.date', 'asc');
        //$this->db->group_by('purchases.par_rec_acc_head_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getTrialPurchaseRntBySuppliersID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('purchases.grand_total as `dr`');
        $this->db->from('purchases');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('productsaccountshead','productsaccountshead.id = purchase_items.purchase_item_head_id');
        $this->db->where('purchases.supplier_id', $account_name_id);
        if($accounts_head_id !=null){
            $this->db->where('supplier.acc_type_id', $accounts_head_id);    
        }
        if($start_date != null){
            $this->datatables->where('purchases.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('purchases.date <=',$end_date);
        }
        $this->db->where('purchases.brc_id', $brc_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getTrialPurchasesRntPaymentsByParentsID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id = null,$start_date = null,$end_date = null) {
        $this->db->select('account_voucher.credit_amount as `dr`');
        $this->db->from('account_voucher');
        $this->db->join('supplier','supplier.id = account_voucher.supplier_id');
        $this->db->join('accountshead','accountshead.id = account_voucher.par_acc_head_id');
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        if($start_date != null){
            $this->datatables->where('account_voucher.date >=',$start_date);
        }
        if($end_date != null){
            $this->datatables->where('account_voucher.date <=',$end_date);
        }
        $this->db->where('account_voucher.brc_id', $brc_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* END Purchase Return END */
    
    /* Expenses Bill */
    
    public function getParentExpensesAccounntsTrialByID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id= null,$start_date= null, $end_date= null){
        $this->db->select('expenses_bill_items.credit_amount as `cr`');
        $this->db->from('expenses_bill');
        $this->db->join('expenses_bill_items','expenses_bill_items.expenses_bill_id = expenses_bill.id');
        $this->db->join('accountshead','accountshead.id = expenses_bill_items.acc_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        // if($account_type_id !=null){
        //     $this->db->where('accountsnew.id', $account_type_id);    
        // }
        $this->db->where('expenses_bill.brc_id', $brc_id);
        $this->db->where('expenses_bill.par_acc_head_id', $account_name_id);
        $this->db->where('expenses_bill.date >=', $start_date);
        $this->db->where('expenses_bill.date <=', $end_date);
        $this->db->order_by('expenses_bill.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getExpensesAccounntsTrialByID($brc_id,$accounts_head_id = null,$account_type_id = null,$account_name_id= null,$start_date= null, $end_date= null){
        $this->db->select('expenses_bill_items.debit_amount as `dr`');
        $this->db->from('expenses_bill');
        $this->db->join('expenses_bill_items','expenses_bill_items.expenses_bill_id = expenses_bill.id');
        $this->db->join('accountshead','accountshead.id = expenses_bill.par_acc_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        // if($account_type_id !=null){
        //     $this->db->where('accountshead.new_accounts_id', $account_type_id);    
        // }
        $this->db->where('expenses_bill.brc_id', $brc_id);
        $this->db->where('expenses_bill_items.acc_head_id', $account_name_id);
        $this->db->where('expenses_bill.date >=', $start_date);
        $this->db->where('expenses_bill.date <=', $end_date);
        $this->db->order_by('expenses_bill.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* END Expenses Bill END */
    
    /* END Trial Balance Acccount Technical Function END */
    
    public function getAccounntsByID($brc_id,$account_name_id,$start_date, $end_date){
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.debit_amount as `dr`,account_voucher.credit_amount as `cr`,accountshead.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('account_voucher.brc_id', $brc_id);
        $this->db->where('account_voucher.acc_head_id', $account_name_id);
        $this->db->where('account_voucher.date >=', $start_date);
        $this->db->where('account_voucher.date <=', $end_date);
        $this->db->order_by('account_voucher.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getParentAccounntsByID($brc_id,$account_name_id,$start_date, $end_date){
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.debit_amount as `dr`,accountshead.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('accountshead','accountshead.id = account_voucher.acc_head_id');
        $this->db->join('accountsnew','accountsnew.id = accountshead.new_accounts_id');
        $this->db->join('accounts_type','accounts_type.id = accountsnew.accounts_type_id');
        $this->db->where('account_voucher.brc_id', $brc_id);
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        $this->db->where('account_voucher.date >=', $start_date);
        $this->db->where('account_voucher.date <=', $end_date);
        $this->db->order_by('account_voucher.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getParentCutomerAccounntsByID($brc_id,$account_name_id,$start_date, $end_date){
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.debit_amount as `dr`,customers.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('customers','customers.id = account_voucher.customer_id');
        $this->db->where('account_voucher.brc_id', $brc_id);
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        $this->db->where('account_voucher.date >=', $start_date);
        $this->db->where('account_voucher.date <=', $end_date);
        $this->db->order_by('account_voucher.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getParentSupplierAccounntsByID($brc_id,$account_name_id,$start_date, $end_date){
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.debit_amount as `dr`,account_voucher.credit_amount as `cr`,supplier.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('supplier','supplier.id = account_voucher.supplier_id');
        $this->db->where('account_voucher.brc_id', $brc_id);
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        $this->db->where('account_voucher.date >=', $start_date);
        $this->db->where('account_voucher.date <=', $end_date);
        $this->db->order_by('account_voucher.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getParentEmployeeAccounntsByID($brc_id,$account_name_id,$start_date, $end_date){
        $this->db->select('account_voucher.id,account_voucher.date,account_voucher.invoice_no as `bill_no`,account_voucher.note as `note`,account_voucher.debit_amount as `dr`,staff.name as `account_name`');
        $this->db->from('account_voucher');
        $this->db->join('staff','staff.id = account_voucher.staff_id');
        $this->db->where('account_voucher.brc_id', $brc_id);
        $this->db->where('account_voucher.par_acc_head_id', $account_name_id);
        $this->db->where('account_voucher.date >=', $start_date);
        $this->db->where('account_voucher.date <=', $end_date);
        $this->db->order_by('account_voucher.id');
        //$this->db->group_by('account_voucher.date');
        $query = $this->db->get();
        return $query->result_array();
    }
}