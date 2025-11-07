<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Quotations extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('cart');
        $this->load->helper('url');
        $this->load->library('media_storage');
    }

    public function index($brnc_id = null){
        if (!$this->rbac->hasPrivilege('quotations', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'quotations');
        $this->session->set_userdata('sub_menu', 'quotations/index');
        $data['title']            = 'Quotations List';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['productslist']   = $this->products_model->get('',7);
        $data['customerslist']  = $this->customers_model->getall($brc_id);
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/quotations/quotationsList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function searchquotations(){
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
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to,'product_id' =>$product_id,'customer_id' =>$customer_id,'brc_id' => $brc_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchquotationslist(){
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $product_id  = $this->input->post('product_id');
        $customer_id = $this->input->post('customer_id');
        $brc_id = $this->input->post('brc_id');
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
            $resultList        = $this->quotations_model->getQuotations($brc_id,1,$product_id,$customer_id, $date_from, $date_to);
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
                if ($this->rbac->hasPrivilege('quotations', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/quotations/edit/" . $value->id . "/". $value->brc_id  ."'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('quotations', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/quotations/delete/" . $value->id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('quotations', 'can_view')) {
                    $quotationspdf = "<a href='" . base_url() . "report/pdfquotations/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('quotations') . "' data-toggle='tooltip'><i class='fa fa-download'></i><span>".$this->lang->line('quotations')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/quotations/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$quotationspdf."</li>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                // $items = $this->quotations_model->getItemsByQuotesByPID($value->id,$product_id);
                // $itemqty = '';
                // if(!empty($items)){
                //     foreach($items as $ival){
                //         $itemqty .= $ival['item_name'].' ('.$ival['qty'].')<br/>';
                //     }
                // }
                $quotes_no = "<a href='#' data-toggle='modal' data-target='#quotationsModal' data-quot_id='" . $value->id . "' data-toggle='tooltip'  title='".$this->lang->line('view_quotation')."'>" . $value->quotes_no . "</a>";
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-primary btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $quotes_no;
                $row[]     = $value->customer;
                $row[]     = number_format($value->total, 2, '.', ',');
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
        $result = $this->quotations_model->get($id);
        $this->media_storage->filedownload($result['attachment'], "./uploads/purchases");
    }

    public function getquotationslist($brc_id){
        $m               = $this->quotations_model->getQuotations(1);
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
                if ($this->rbac->hasPrivilege('quotations', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/quotations/edit/" . $value->id . "/". $value->brc_id  ."'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('quotations', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/quotations/delete/" . $value->id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('quotations', 'can_view')) {
                    $quotationspdf = "<a href='" . base_url() . "report/pdfquotations/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('quotations') . "' data-toggle='tooltip'><i class='fa fa-download'></i><span>".$this->lang->line('quotations')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/quotations/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$quotationspdf."</li>
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
                $quotes_no = "<a href='#' data-toggle='modal' data-target='#quotationsModal' data-quot_id='" . $value->id . "' data-toggle='tooltip'  title='".$this->lang->line('view_quotation')."'>" . $value->quotes_no . "</a>";
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-success btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $quotes_no;
                $row[]     = $value->customer;
                $row[]     = number_format($value->total, 2, '.', ',');
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

    public function create($brc_id){
        if (!$this->rbac->hasPrivilege('quotations', 'can_add')) {
            access_denied();
        }
        $data['title']          = 'Add Quotations';
        $data['branchlist']             = $this->branchsettings_model->get();
        $data['brc_id']                 = $brc_id;
        $data['productslist']           = $this->products_model->get('',7);
        $data['customerslist']          = $this->customers_model->getall($brc_id);
        $data['warehouseslist']         = $this->warehouses_model->get();
        $sys_setting_detail             = $this->setting_model->getSetting();
        $data['sys_setting']            = $sys_setting_detail ;
        $data['quotations_auto_insert'] = $sys_setting_detail->quotations_auto_insert;
        $quotes_no  = 0;
        if ($sys_setting_detail->quotations_auto_insert) {
            if ($sys_setting_detail->quotations_update_status) {
                $quotes_no = $sys_setting_detail->quotations_prefix . $sys_setting_detail->quotations_start_from;
                $last_quotes = $this->quotations_model->lastRecord();
                $last_quotes_digit = str_replace($sys_setting_detail->quotations_prefix, "", $last_quotes['quotes_no']);
                $quotes_no                = $sys_setting_detail->quotations_prefix . sprintf("%0" . $sys_setting_detail->quotations_no_digit . "d", $last_quotes_digit + 1);
                $data['quotes_no'] = $quotes_no;
            } else {
                $quotes_no                = $sys_setting_detail->quotations_prefix . sprintf("%0" . $sys_setting_detail->quotations_no_digit . "d", 1);
                $data['quotes_no'] = $quotes_no;
            }
            // $quotes_no_exists = $this->quotations_model->check_quotations_exists($quotes_no);
            // if ($quotes_no_exists) {
            //     $insert = false;
            // }
        } else {
            $last_quotations = $this->quotations_model->lastRecord();
            if (!empty($last_quotations)) {
                $quotes_no = $last_quotations['quotes_no'] + 1;
            }else{
                $quotes_no = 1;
            }
            $data['quotes_no'] = $quotes_no;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/quotations/quotationsCreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function quotationsSave(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('quotes_no', $this->lang->line('quotations_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('expiry_date', $this->lang->line('expiry').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'quotes_no'    => form_error('quotes_no'),
                'expiry_date'  => form_error('expiry_date'),
                'customer_id'  => form_error('customer_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
            $total            = str_replace(",", "",$this->input->post('total')); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = str_replace(",", "",$this->input->post('discount')); 
            $total_tax        = str_replace(",", "",$this->input->post('total_tax')); 
            $grand_total      = str_replace(",", "",$this->input->post('net_amount')); 
            $data = [
                'brc_id'              => $brc_id,
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'expiry_date'         => $this->customlib->dateFormatToYYYYMMDD($this->input->post('expiry_date')),
                'quotes_no'           => $this->input->post('quotes_no'),
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
            $this->db->insert('quotes', $data);
            $quotes_id = $this->db->insert_id();
            $data_setting                             = array();
            $data_setting['id']                       = $sys_setting_detail->id;
            $data_setting['quotations_auto_insert']   = $sys_setting_detail->quotations_auto_insert;
            $data_setting['quotations_update_status'] = $sys_setting_detail->quotations_update_status;
            if ($data_setting['quotations_auto_insert']) {
                if ($data_setting['quotations_update_status'] == 0) {
                    $data_setting['quotations_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
            if(!empty($quotes_id)){
                $total_rows   = $this->input->post('total_rows');
                if (isset($total_rows) && !empty($total_rows)) {
                    foreach ($total_rows as $row_key => $row_value) {
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,units.name as `unit_name`')->from('products')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        $o_details['quote_id ']         = $quotes_id;
                        $o_details['warehouse_id']      = 1;
                        $o_details['customer_id']       = $this->input->post('customer_id');
                        $o_details['product_id']        = $item_id;
                        $o_details['product_name']      = $product->name;
                        $o_details['unit']              = $product->unit_name;
                        $o_details['description']       = $this->input->post('description_'.$row_value);
                        $o_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $o_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $o_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']               = str_replace(",", "",$this->input->post('tax_'.$row_value));
                        $o_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']          = str_replace(",", "",$this->input->post('disct_'.$row_value));
                        $o_details['subtotal']          = str_replace(",", "",$this->input->post('amount_'.$row_value));
                        $o_details['created_by']        = $userdata['id'];
                        //save order details
                        $this->db->insert('quote_items', $o_details);
                    }
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function quotationsPrintSave(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('quotes_no', $this->lang->line('quotations_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('expiry_date', $this->lang->line('expiry').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'quotes_no'    => form_error('quotes_no'),
                'expiry_date'  => form_error('expiry_date'),
                'customer_id'  => form_error('customer_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
            $total            = str_replace(",", "",$this->input->post('total')); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = str_replace(",", "",$this->input->post('discount')); 
            $total_tax        = str_replace(",", "",$this->input->post('total_tax')); 
            $grand_total      = str_replace(",", "",$this->input->post('net_amount')); 
            $data = [
                'brc_id'              => $brc_id,
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'expiry_date'         => $this->customlib->dateFormatToYYYYMMDD($this->input->post('expiry_date')),
                'quotes_no'           => $this->input->post('quotes_no'),
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
            $this->db->insert('quotes', $data);
            $quotes_id = $this->db->insert_id();
            $data_setting                             = array();
            $data_setting['id']                       = $sys_setting_detail->id;
            $data_setting['quotations_auto_insert']   = $sys_setting_detail->quotations_auto_insert;
            $data_setting['quotations_update_status'] = $sys_setting_detail->quotations_update_status;
            if ($data_setting['quotations_auto_insert']) {
                if ($data_setting['quotations_update_status'] == 0) {
                    $data_setting['quotations_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
            if(!empty($quotes_id)){
                $total_rows   = $this->input->post('total_rows');
                if (isset($total_rows) && !empty($total_rows)) {
                    foreach ($total_rows as $row_key => $row_value) {
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,units.name as `unit_name`')->from('products')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        $o_details['quote_id ']         = $quotes_id;
                        $o_details['warehouse_id']      = 1;
                        $o_details['customer_id']       = $this->input->post('customer_id');
                        $o_details['product_id']        = $item_id;
                        $o_details['product_name']      = $product->name;
                        $o_details['unit']              = $product->unit_name;
                        $o_details['description']       = $this->input->post('description_'.$row_value);
                        $o_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $o_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $o_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']               = str_replace(",", "",$this->input->post('tax_'.$row_value));
                        $o_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']          = str_replace(",", "",$this->input->post('disct_'.$row_value));
                        $o_details['subtotal']          = str_replace(",", "",$this->input->post('amount_'.$row_value));
                        $o_details['created_by']        = $userdata['id'];
                        //save order details
                        $this->db->insert('quote_items', $o_details);
                    }
                }
                $data['settinglist'] = $sys_setting_detail;
                $data['quotationsList'] = $this->quotations_model->printquotationsInsertID($quotes_id);
                $this->load->view('print/printquotations', $data);
            }
            // $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            // echo json_encode($array);
        }
    }

    public function edit($id,$brc_id){
        if (!$this->rbac->hasPrivilege('quotations', 'can_edit')) {
            access_denied();
        }
        $data['title']          = 'Edit products';
        $data['id']             = $id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data['brc_id']         = $brc_id;
        $data['productslist']   = $this->products_model->get('',7);
        $data['customerslist']  = $this->customers_model->getall($brc_id);
        $data['warehouseslist'] = $this->warehouses_model->get();
        $data['quotations']     = $this->quotations_model->get($id);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/quotations/quotationsEdit', $data);
        $this->load->view('layout/footer', $data);
    }

    function quotationsUpdateSave($id){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('quotes_no', $this->lang->line('quotations_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('expiry_date', $this->lang->line('expiry').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'quotes_no'    => form_error('quotes_no'),
                'expiry_date'  => form_error('expiry_date'),
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
            $total            = str_replace(",", "",$this->input->post('total')); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = str_replace(",", "",$this->input->post('discount')); 
            $total_tax        = str_replace(",", "",$this->input->post('total_tax')); 
            $grand_total      = str_replace(",", "",$this->input->post('net_amount')); 
            $data = [
                'id'                  => $id,
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'expiry_date'         => $this->customlib->dateFormatToYYYYMMDD($this->input->post('expiry_date')),
                'quotes_no'           => $this->input->post('quotes_no'),
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
            $this->db->where('quotes.id', $data['id']);
            $this->db->update('quotes', $data);
            $previous_id    = $this->input->post('previous_id');
            $carts_id       = $this->input->post('carts_id');
            $delete_result  = array_diff($previous_id, $carts_id);
            if (!empty($delete_result)) {
                $d_delete_array = array();
                foreach ($delete_result as $d_delete_key => $d_delete_value) {
                    $d_delete_array[] = $d_delete_value;
                }
                $this->quotations_model->remove_detial_product($d_delete_array);

            }
            $total_rows   = $this->input->post('total_rows');
            if (isset($total_rows) && !empty($total_rows)) {
                foreach ($total_rows as $row_key => $row_value) {
                    $update_id = $this->input->post('update_id_'.$row_value);
                    if(!empty($update_id)){
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,units.name as `unit_name`')->from('products')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        $o_details['id']                = $update_id;
                        $o_details['quote_id']          = $id;
                        $o_details['warehouse_id']      = 1;
                        $o_details['customer_id']       = $this->input->post('customer_id');
                        $o_details['product_id']        = $item_id;
                        $o_details['product_name']      = $product->name;
                        $o_details['unit']              = $product->unit_name;
                        $o_details['description']       = $this->input->post('description_'.$row_value);
                        $o_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $o_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $o_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']               = str_replace(",", "",$this->input->post('tax_'.$row_value));
                        $o_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']          = str_replace(",", "",$this->input->post('disct_'.$row_value));
                        $o_details['subtotal']          = str_replace(",", "",$this->input->post('amount_'.$row_value));
                        $o_details['created_by']        = $userdata['id'];
                        $this->db->where('quote_items.id', $o_details['id']);
                        $this->db->update('quote_items', $o_details);
                    }else{
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,units.name as `unit_name`')->from('products')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        $i_details['quote_id']          = $id;
                        $i_details['warehouse_id']      = 1;
                        $i_details['customer_id']       = $this->input->post('customer_id');
                        $i_details['product_id']        = $item_id;
                        $i_details['product_name']      = $product->name;
                        $i_details['unit']              = $product->unit_name;
                        $i_details['description']       = $this->input->post('description_'.$row_value);
                        $i_details['quantity']          = $this->input->post('quantity_'.$row_value);
                        $i_details['net_unit_price']    = $this->input->post('rate_'.$row_value);
                        $i_details['item_tax']          = $this->input->post('rate_tax_'.$row_value);
                        $i_details['tax']               = str_replace(",", "",$this->input->post('tax_'.$row_value));
                        $i_details['item_discount']     = $this->input->post('discount_per_'.$row_value);
                        $i_details['discount']          = str_replace(",", "",$this->input->post('disct_'.$row_value));
                        $i_details['subtotal']          = str_replace(",", "",$this->input->post('amount_'.$row_value));
                        $i_details['created_by']        = $userdata['id'];
                        //save order details
                        $this->db->insert('quote_items', $i_details);
                    }
                }
            }
            
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }


    public function delete($id){
        if (!$this->rbac->hasPrivilege('quotations', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Products List';
        $this->quotations_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/quotations/index');
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
        $product = $this->db->select('products.*,tax_rates.name as tax_name,tax_rates.rate,units.name as unit_name')->from('products')->join('tax_rates','tax_rates.id = products.tax_rate','left')->join('units','units.id = products.unit','left')->where('products.id',$id)->get('')->row();
        $data = array();
        //if ($available_qty > 0) {
            if(!empty($product)){
                $description = '';
                if(!empty($product->product_details)){
                    $description = strip_tags($product->product_details);
                }
                $tax_amount = 0;
                if(!empty($product->price)){
                    $subTotal = $product->price*1;
                    $tax_amount = (($subTotal*$product->rate) / 100);
                }
                if($product->product_type == 2){
                    $avabl_qty = '';
                }else if($product->product_type == 2){
                    $avabl_qty = '';
                }else{
                    $avabl_qty = 100;
                }
                $data = array(
                    'id'                    => $product->id,
                    'name'                  => $product->name,
                    'description'           => $description,
                    'available_quantity'    => $avabl_qty,
                    'qty'                   => 1,
                    'price'                 => $product->price,
                    'cost'                  => $product->cost,
                    'rate_tax'              => $product->rate,
                    'tax'                   => $tax_amount,
                    'unit_name'             => $product->unit_name,
                );
            }
            echo json_encode($data);
        //}else{
            //echo json_encode(array('status' => 'fail', 'msg' => ""));
        //}

    }
    
    public function viewquotationsinvoice(){
        $quot_id   = $this->input->post('quot_id');
        $data['quot_id'] = $quot_id;
        $data['inv'] = $this->quotations_model->printquotationsInsertID($quot_id);
        $this->load->view('admin/quotations/quotationsinvoiceview', $data);
    }
}
