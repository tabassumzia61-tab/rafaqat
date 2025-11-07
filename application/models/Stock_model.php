<?php
class Stock_model extends MY_Model {
    
    /* Stock Reports */
    
    public function getSalesByCustomersID($brc_id,$account_name_id,$start_date,$end_date){
        $this->db->select('sales.id,sales.date,sales.sale_no as `bill_no`,sales.note as `note`,SUM(sale_items.quantity) as qtyout,sales.grand_total as `cr`,customers.name as `account_name`');
        $this->db->from('sales');
        $this->db->join('customers','customers.id = sales.customer_id');
        $this->db->join('sale_items','sale_items.sale_id = sales.id');
        $this->db->where('sale_items.product_id', $account_name_id);
        $this->db->where('sales.brc_id', $brc_id);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(sales.date, '%Y-%m-%d')<=",$end_date);
        //$this->db->where('sales.brc_id', $brc_id);
        $this->db->order_by('sales.date', 'asc');
        //$this->db->group_by('sales.par_rec_acc_head_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getPurchaseBySuppliersID($brc_id,$account_name_id,$start_date,$end_date) {
        $this->db->select('purchases.id,purchases.date,purchases.purchase_no as `bill_no`,purchases.note as `note`,SUM(purchase_items.quantity) as qtyin,purchases.gross_amount as `dr`,purchases.total_discount as `total_discount`,supplier.name as `account_name`');
        $this->db->from('purchases');
        $this->db->join('supplier','supplier.id = purchases.supplier_id');
        $this->db->join('purchase_items','purchase_items.purchase_id = purchases.id');
        $this->db->where('purchases.brc_id', $brc_id);
        $this->db->where('purchase_items.product_id', $account_name_id);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')>=",$start_date);
        $this->db->where("DATE_FORMAT(purchases.date, '%Y-%m-%d')<=",$end_date);
        $this->db->order_by('purchases.date', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
}