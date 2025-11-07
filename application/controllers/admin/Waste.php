<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Waste extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('cart');
        $this->load->helper('url');
        $this->load->library('media_storage');
    }

    public function index(){
        if (!$this->rbac->hasPrivilege('waste', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'waste');
        $this->session->set_userdata('sub_menu', 'waste/index');
        $data['title']            = 'waste List';
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['productslist']   = $this->products_model->get();
        $data['customerslist']  = $this->customers_model->get();
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/waste/wasteList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function searchwaste(){
        $button_type = $this->input->post('button_type');
        if ($button_type == "search_filter") {
            $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'required|trim|xss_clean');
        } 
        if ($this->form_validation->run() == false) {
            $error = array();
            if ($button_type == "search_filter") {
                $error['search_type'] = form_error('search_type');
            } 
            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $button_type = $this->input->post('button_type');
            $date_from   = "";
            $date_to     = "";
            $search_type = $this->input->post('search_type');
            if ($search_type == 'period') {
                $date_from = $this->input->post('date_from');
                $date_to   = $this->input->post('date_to');
            }
            $product_id   = $this->input->post('product_id');
            $customer_id   = $this->input->post('customer_id');
            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to,'product_id' =>$product_id,'customer_id' =>$customer_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchwastelist(){
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $product_id  = $this->input->post('product_id');
        $customer_id = $this->input->post('customer_id');
        if ($button_type == 'search_filter') {
            if ($search_type != "") {
                if ($search_type == 'all') {
                    $dates = $this->customlib->get_betweendate('this_year');
                } else {
                    $dates = $this->customlib->get_betweendate($search_type);
                }
            } else {
                $dates       = $this->customlib->get_betweendate('this_year');
                $search_type = '';
            }
            $dateformat        = $this->customlib->getSystemDateFormat();
            $date_from         = date('Y-m-d', strtotime($dates['from_date']));
            $date_to           = date('Y-m-d', strtotime($dates['to_date']));
            $date_from         = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_from));
            $date_to           = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_to));
            $resultList        = $this->waste_model->getwaste(1,$product_id,$customer_id, $date_from, $date_to);
        }
        $m               = json_decode($resultList);
        $currency_symbol = $this->customlib->getSystemCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($m->data)) {
            $counts = 1;
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $addrevdqty = '';
                $documents = '';
                if ($this->rbac->hasPrivilege('waste', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/waste/edit/" . $value->id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('waste', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/waste/delete/" . $value->id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/waste/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                // $items = $this->waste_model->getItemsByQuotesByPID($value->id,$product_id);
                // $itemqty = '';
                // if(!empty($items)){
                //     foreach($items as $ival){
                //         $itemqty .= $ival['item_name'].' ('.$ival['qty'].')<br/>';
                //     }
                // }
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-primary btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $value->sale_no;
                $row[]     = $value->customer;
                $row[]     = $value->total;
                $row[]     = $sts;
                //$row[]     = $documents;
                $row[]     = $action;
                $dt_data[] = $row;
                $counts++; 
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function download($id)
    {
        $result = $this->waste_model->get($id);
        $this->media_storage->filedownload($result['attachment'], "./uploads/purchases");
    }

    public function getwastelist(){
        $m               = $this->waste_model->getwaste(1);
        $m               = json_decode($m);
        $currency_symbol = $this->customlib->getSystemCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            $counts = 1;
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $addrevdqty = '';
                $documents = '';
                if ($this->rbac->hasPrivilege('waste', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/waste/edit/" . $value->id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('waste', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/waste/delete/" . $value->id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/waste/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                // $items = $this->purchases_model->getItemsByQuotesByPID($value->id);
                // $itemqty = '';
                // if(!empty($items)){
                //     foreach($items as $ival){
                //         $itemqty .= $ival['item_name'].' ('.$ival['qty'].')<br/>';
                //     }
                // }
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-success btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $value->bill_no;
                $row[]     = $value->customer;
                $row[]     = $value->total;
                $row[]     = $sts;
                //$row[]     = $documents;
                $row[]     = $action;
                $dt_data[] = $row;
                $counts++; 
            }
        }
        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function create(){
        if (!$this->rbac->hasPrivilege('waste', 'can_add')) {
            access_denied();
        }
        $data['title']          = 'Add waste';
        $data['productslist']   = $this->products_model->get();
        $data['customerslist']  = $this->customers_model->get();
        $data['warehouseslist'] = $this->warehouses_model->get();
        $sys_setting_detail             = $this->setting_model->getSetting();
        $data['sys_setting']            = $sys_setting_detail ;
        $data['sale_auto_insert'] = $sys_setting_detail->sale_auto_insert;
        // $sale_no  = 0;
        // if ($sys_setting_detail->sale_auto_insert) {
        //     if ($sys_setting_detail->sale_update_status) {
        //         $sale_no = $sys_setting_detail->sale_prefix . $sys_setting_detail->sale_start_from;
        //         $last_sale = $this->waste_model->getwasteNo();
        //         $last_sale_digit = str_replace($sys_setting_detail->sale_prefix, "", $last_sale['sale_no']);
        //         $sale_no                = $sys_setting_detail->sale_prefix . sprintf("%0" . $sys_setting_detail->sale_no_digit . "d", $last_sale_digit + 1);
        //         $data['sale_no'] = $sale_no;
        //     } else {
        //         $sale_no                = $sys_setting_detail->sale_prefix . sprintf("%0" . $sys_setting_detail->sale_no_digit . "d", 1);
        //         $data['sale_no'] = $sale_no;
        //     }
        //     // $sale_no_exists = $this->sale_model->check_sale_exists($sale_no);
        //     // if ($sale_no_exists) {
        //     //     $insert = false;
        //     // }
        // } else {
        //     $last_sale = $this->waste_model->getwasteNo();
        //     if (!empty($last_sale)) {
        //         $sale_no = $last_sale['sale_no'] + 1;
        //     }else{
        //         $sale_no = 1;
        //     }
        //     $data['sale_no'] = $sale_no;
        // }
        $last_sale = $this->waste_model->getwasteNo();
        if(!empty($last_sale)){
            $last_sale_digit = str_replace('wa-', "", $last_sale['bill_no']);
            $bill_no = 'wa-' . sprintf("%0" . 1 . "d", $last_sale_digit + 1);
            $data['bill_no'] = $bill_no;
        }else{
            $fisrt_sale = 1;
            $last_sale_digit = str_replace('wa', "", $fisrt_sale);
            $bill_no = 'wa-' . sprintf("%0" . 1 . "d", $last_sale_digit);
            $data['bill_no'] = $bill_no;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/waste/wasteCreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function wasteSave(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bill_no', $this->lang->line('bill_no'), 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'bill_no'    => form_error('bill_no'),
                'customer_id'  => form_error('customer_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $sys_setting_detail = $this->setting_model->getSetting();
            if($this->input->post('customer_type') == 1){
                $customer_id  = 1;
            }else{
                $customer_id  = $this->input->post('customer_id');    
            }
            $warehouse_id = 1;
            $note = $this->input->post('customer_message');
            $customer_details = $this->customers_model->get($customer_id);
            $customer         = $customer_details['company'] && $customer_details['company'] != '-' ? $customer_details['company'] : $customer_details['name'];
            $userdata         = $this->customlib->getUserData();
            $total            = $this->input->post('total'); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = $this->input->post('discount'); 
            $total_tax        = $this->input->post('total_tax'); 
            $grand_total      = $this->input->post('net_amount'); 
            $data = [
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'bill_no'             => $this->input->post('bill_no'),
                'customer_type'       => $this->input->post('customer_type'),
                'customer_id'         => $customer_id,
                'customer'            => $customer,
                'warehouse_id'        => $warehouse_id,
                'note'                => $note,
                'total'               => $total,
                'discount_percent'    => $discount_percent,
                'total_discount'      => $total_discount,
                'total_tax'           => $total_tax,
                'grand_total'         => $grand_total,
                'status'              => 1,
                'acc_head_tax'        => 1,
                'acc_head_dis'        => 2,
                'created_by'          => $userdata['id'],
            ];
            $this->db->insert('waste', $data);
            $waste_id = $this->db->insert_id();
            // $data_setting                             = array();
            // $data_setting['id']                       = $sys_setting_detail->id;
            // $data_setting['sale_auto_insert']   = $sys_setting_detail->sale_auto_insert;
            // $data_setting['sale_update_status'] = $sys_setting_detail->sale_update_status;
            // if ($data_setting['sale_auto_insert']) {
            //     if ($data_setting['sale_update_status'] == 0) {
            //         $data_setting['sale_update_status'] = 1;
            //         $this->setting_model->add($data_setting);
            //     }
            // }
            if(!empty($waste_id)){
                $total_rows   = $this->input->post('total_rows');
                if (isset($total_rows) && !empty($total_rows)) {
                    foreach ($total_rows as $row_key => $row_value) {
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*')->from('products')->where('products.id',$item_id)->get('')->row();
                        $o_details['waste_id']          = $waste_id;
                        $o_details['warehouse_id']      = 1;
                        $o_details['customer_id']       = $this->input->post('customer_id');
                        $o_details['product_id']        = $item_id;
                        $o_details['product_name']      = $product->name;
                        $o_details['description']       = $this->input->post('description_'.$row_value);
                        $o_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $o_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $o_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']               = $this->input->post('tax_'.$row_value);
                        $o_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']          = $this->input->post('disct_'.$row_value);
                        $o_details['subtotal']          = $this->input->post('amount_'.$row_value);
                        $o_details['created_by']        = $userdata['id'];
                        //save order details
                        $this->db->insert('waste_items', $o_details);
                    }
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function wastePrintSave(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bill_no', $this->lang->line('bill_no'), 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'bill_no'    => form_error('bill_no'),
                'due_date'  => form_error('due_date'),
                'customer_id'  => form_error('customer_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $sys_setting_detail = $this->setting_model->getSetting();
            if($this->input->post('customer_type') == 1){
                $customer_id  = 1;
            }else{
                $customer_id  = $this->input->post('customer_id');    
            }
            $warehouse_id = 1;
            $note = $this->input->post('customer_message');
            $customer_details = $this->customers_model->get($customer_id);
            $customer         = $customer_details['company'] && $customer_details['company'] != '-' ? $customer_details['company'] : $customer_details['name'];
            $userdata         = $this->customlib->getUserData();
            $total            = $this->input->post('total'); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = $this->input->post('discount'); 
            $total_tax        = $this->input->post('total_tax'); 
            $grand_total      = $this->input->post('net_amount'); 
            $data = [
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'bill_no'             => $this->input->post('bill_no'),
                'customer_type'       => $this->input->post('customer_type'),
                'customer_id'         => $customer_id,
                'customer'            => $customer,
                'warehouse_id'        => $warehouse_id,
                'note'                => $note,
                'total'               => $total,
                'discount_percent'    => $discount_percent,
                'total_discount'      => $total_discount,
                'total_tax'           => $total_tax,
                'grand_total'         => $grand_total,
                'status'              => 1,
                'created_by'          => $userdata['id'],
            ];
            $this->db->insert('waste', $data);
            $waste_id = $this->db->insert_id();
            // $data_setting                             = array();
            // $data_setting['id']                       = $sys_setting_detail->id;
            // $data_setting['sale_auto_insert']   = $sys_setting_detail->sale_auto_insert;
            // $data_setting['sale_update_status'] = $sys_setting_detail->sale_update_status;
            // if ($data_setting['sale_auto_insert']) {
            //     if ($data_setting['sale_update_status'] == 0) {
            //         $data_setting['sale_update_status'] = 1;
            //         $this->setting_model->add($data_setting);
            //     }
            // }
            if(!empty($waste_id)){
                $total_rows   = $this->input->post('total_rows');
                if (isset($total_rows) && !empty($total_rows)) {
                    foreach ($total_rows as $row_key => $row_value) {
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*')->from('products')->where('products.id',$item_id)->get('')->row();
                        $o_details['waste_id']          = $waste_id;
                        $o_details['warehouse_id']      = 1;
                        $o_details['customer_id']       = $this->input->post('customer_id');
                        $o_details['product_id']        = $item_id;
                        $o_details['product_name']      = $product->name;
                        $o_details['description']       = $this->input->post('description_'.$row_value);
                        $o_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $o_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $o_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']               = $this->input->post('tax_'.$row_value);
                        $o_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']          = $this->input->post('disct_'.$row_value);
                        $o_details['subtotal']          = $this->input->post('amount_'.$row_value);
                        $o_details['created_by']        = $userdata['id'];
                        //save order details
                        $this->db->insert('waste_items', $o_details);
                    }
                }
                $data['settinglist'] = $sys_setting_detail;
                $data['wasteList'] = $this->waste_model->printwasteInsertID($waste_id);
                $this->load->view('print/printwaste', $data);
            }
            // $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            // echo json_encode($array);
        }
    }

    public function edit($id){
        if (!$this->rbac->hasPrivilege('waste', 'can_edit')) {
            access_denied();
        }
        $data['title']          = 'Edit products';
        $data['id']             = $id;
        $data['productslist']   = $this->products_model->get();
        $data['customerslist']  = $this->customers_model->get();
        $data['warehouseslist'] = $this->warehouses_model->get();
        $waste = $this->waste_model->get($id);
        $data['waste'] = $waste;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/waste/wasteEdit', $data);
        $this->load->view('layout/footer', $data);
    }

    function wasteUpdateSave($id){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bill_no', $this->lang->line('bill_no'), 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'bill_no'      => form_error('bill_no'),
                'customer_id'  => form_error('customer_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if($this->input->post('customer_type') == 1){
                $customer_id  = 1;
            }else{
                $customer_id  = $this->input->post('customer_id');    
            }
            $warehouse_id = 1;
            $note = $this->input->post('customer_message');
            $customer_details = $this->customers_model->get($customer_id);
            $customer         = $customer_details['company'] && $customer_details['company'] != '-' ? $customer_details['company'] : $customer_details['name'];
            $userdata         = $this->customlib->getUserData();
            $total            = $this->input->post('total'); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = $this->input->post('discount'); 
            $total_tax        = $this->input->post('total_tax'); 
            $grand_total      = $this->input->post('net_amount'); 
            $data = [
                'id'                  => $id,
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'bill_no'             => $this->input->post('bill_no'),
                'customer_type'       => $this->input->post('customer_type'),
                'customer_id'         => $customer_id,
                'customer'            => $customer,
                'warehouse_id'        => $warehouse_id,
                'note'                => $note,
                'total'               => $total,
                'discount_percent'    => $discount_percent,
                'total_discount'      => $total_discount,
                'total_tax'           => $total_tax,
                'grand_total'         => $grand_total,
                'status'              => 1,
                'created_by'          => $userdata['id'],
            ];
            $this->db->where('waste.id', $data['id']);
            $this->db->update('waste', $data);
            $previous_id    = $this->input->post('previous_id');
            $carts_id       = $this->input->post('carts_id');
            $delete_result  = array_diff($previous_id, $carts_id);
            if (!empty($delete_result)) {
                $d_delete_array = array();
                foreach ($delete_result as $d_delete_key => $d_delete_value) {
                    $d_delete_array[] = $d_delete_value;
                }
                $this->waste_model->remove_detial_product($d_delete_array);

            }
            $total_rows   = $this->input->post('total_rows');
            if (isset($total_rows) && !empty($total_rows)) {
                foreach ($total_rows as $row_key => $row_value) {
                    $update_id = $this->input->post('update_id_'.$row_value);
                    if(!empty($update_id)){
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*')->from('products')->where('products.id',$item_id)->get('')->row();
                        $o_details['id']                = $update_id;
                        $o_details['waste_id']          = $id;
                        $o_details['warehouse_id']      = 1;
                        $o_details['customer_id']       = $this->input->post('customer_id');
                        $o_details['product_id']        = $item_id;
                        $o_details['product_name']      = $product->name;
                        $o_details['description']       = $this->input->post('description_'.$row_value);
                        $o_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $o_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $o_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']               = $this->input->post('tax_'.$row_value);
                        $o_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']          = $this->input->post('disct_'.$row_value);
                        $o_details['subtotal']          = $this->input->post('amount_'.$row_value);
                        $o_details['created_by']        = $userdata['id'];
                        $this->db->where('waste_items.id', $o_details['id']);
                        $this->db->update('waste_items', $o_details);
                    }else{
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*')->from('products')->where('products.id',$item_id)->get('')->row();
                        $i_details['waste_id']          = $id;
                        $i_details['warehouse_id']      = 1;
                        $i_details['customer_id']       = $this->input->post('customer_id');
                        $i_details['product_id']        = $item_id;
                        $i_details['product_name']      = $product->name;
                        $i_details['description']       = $this->input->post('description_'.$row_value);
                        $i_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $i_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $i_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $i_details['tax']               = $this->input->post('tax_'.$row_value);
                        $i_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $i_details['discount']          = $this->input->post('disct_'.$row_value);
                        $i_details['subtotal']          = $this->input->post('amount_'.$row_value);
                        $i_details['created_by']        = $userdata['id'];
                        //save order details
                        $this->db->insert('waste_items', $i_details);
                    }
                }
            }
            
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function delete($id){
        if (!$this->rbac->hasPrivilege('waste', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Products List';
        $this->waste_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/waste/index');
    }

    public function handle_upload(){
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
            $file_type         = $_FILES["documents"]['type'];
            $file_size         = $_FILES["documents"]["size"];
            $file_name         = $_FILES["documents"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['documents']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    function addtocart(){
        $id = $this->input->post('item_id');
        $product = $this->db->select('products.*,tax_rates.name as tax_name,tax_rates.rate')->from('products')->join('tax_rates','tax_rates.id = products.tax_rate','left')->where('products.id',$id)->where('products.product_subtype',8)->get('')->row();
        $data = array();
        //if ($available_qty > 0) {
            if(!empty($product)){
                $description = '';
                if(!empty($product->product_details)){
                    $description = strip_tags($product->product_details);
                }
                $data = array(
                    'id'                    => $product->id,
                    'name'                  => $product->name,
                    'description'           => $description,
                    'qty'                   => 1,
                    'price'                 => $product->price,
                );
            }
            echo json_encode($data);
        //}else{
            //echo json_encode(array('status' => 'fail', 'msg' => ""));
        //}

    }

}
