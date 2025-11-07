<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Salesreturn extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('cart');
        $this->load->helper('url');
        $this->load->library('media_storage');
    }

    public function index($brnc_id = null){
        if (!$this->rbac->hasPrivilege('salesreturn', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'salesreturn');
        $this->session->set_userdata('sub_menu', 'salesreturn/index');
        $data['title']            = 'Sales Return List';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
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
        $this->load->view('admin/salesreturn/salesreturnList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function searchsalesreturn(){
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

    public function getsearchsalesreturnlist(){
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
            $resultList        = $this->salesreturn_model->getsalesreturn($brc_id,1,$product_id,$customer_id, $date_from, $date_to);
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
                $saleinvoicepdf = '';
                $documents = '';
                if ($this->rbac->hasPrivilege('salesreturn', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/salesreturn/edit/" . $value->id ."/" . $value->brc_id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('salesreturn', 'can_delete')) {
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/salesreturn/delete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('salesreturn', 'can_view')) {
                    $saleinvoicepdf = "<a href='" . base_url() . "report/pdfsalereturn/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('sale_return') . "' data-toggle='tooltip'><i class='fa fa-download'></i><span>".$this->lang->line('sale_return')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/salesreturn/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$saleinvoicepdf."</li>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                // $items = $this->salesreturn_model->getItemsByQuotesByPID($value->id,$product_id);
                // $itemqty = '';
                // if(!empty($items)){
                //     foreach($items as $ival){
                //         $itemqty .= $ival['item_name'].' ('.$ival['qty'].')<br/>';
                //     }
                // }
                
                 $invoice_no = "<a href='#' data-toggle='modal' data-target='#salesreturninvoiceModal' data-salesreturn_id='" . $value->id . "' data-toggle='tooltip'  title='".$this->lang->line('view_sale_return')."'>" . $value->sale_no . "</a>";
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-primary btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $invoice_no;
                $row[]     = $value->customer;
                $row[]     = number_format($value->total, 2, '.', ',');
                $row[]     = $sts;
                //$row[]   = $documents;
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
        $result = $this->salesreturn_model->get($id);
        $this->media_storage->filedownload($result['attachment'], "./uploads/purchases");
    }

    public function getsalesreturnlist($brc_id){
        $m               = $this->salesreturn_model->getsalesreturn($brc_id,1,"","","","");
        $m               = json_decode($m);
        $currency_symbol = $this->customlib->getSystemCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            $counts = 1;
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $saleinvoicepdf = '';
                $documents = '';
                if ($this->rbac->hasPrivilege('salesreturn', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/salesreturn/edit/" . $value->id . "/". $value->brc_id  ."'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('salesreturn', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/salesreturn/delete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('salesreturn', 'can_view')) {
                    $saleinvoicepdf = "<a href='" . base_url() . "report/pdfsalereturn/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('sale_return') . "' data-toggle='tooltip'><i class='fa fa-download'></i><span>".$this->lang->line('sale_return')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/salesreturn/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$saleinvoicepdf."</li>
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
                $invoice_no = "<a href='#' data-toggle='modal' data-target='#salesreturninvoiceModal' data-salesreturn_id='" . $value->id . "' data-toggle='tooltip'  title='".$this->lang->line('view_sale_return')."'>" . $value->sale_no . "</a>";
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-success btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $invoice_no;
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
        if (!$this->rbac->hasPrivilege('salesreturn', 'can_add')) {
            access_denied();
        }
        $data['title']          = 'Add salesreturn';
        $data['brc_id']         = $brc_id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data['productslist']   = $this->products_model->get('',7);
        $data['customerslist']  = $this->customers_model->getall($brc_id);
        $data['warehouseslist'] = $this->warehouses_model->get();
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $sys_setting_detail             = $this->setting_model->getSetting();
        $data['sys_setting']            = $sys_setting_detail ;
        $data['salereturn_auto_insert'] = $sys_setting_detail->salereturn_auto_insert;
        $sale_no  = 0;
        if ($sys_setting_detail->salereturn_auto_insert) {
            if ($sys_setting_detail->salereturn_update_status) {
                $sale_no = $sys_setting_detail->salereturn_prefix . $sys_setting_detail->salereturn_start_from;
                $last_sale = $this->salesreturn_model->getsalesreturnNo($brc_id);
                if(!empty($last_sale)){
                    $last_sale_digit = str_replace($sys_setting_detail->salereturn_prefix, "", $last_sale['sale_no']);
                }else{
                    $last_sale_digit = 0;
                }
                $sale_no                = $sys_setting_detail->salereturn_prefix . sprintf("%0" . $sys_setting_detail->sale_no_digit . "d", $last_sale_digit + 1);
                $data['sale_no'] = $sale_no;
            } else {
                $sale_no                = $sys_setting_detail->salereturn_prefix . sprintf("%0" . $sys_setting_detail->salereturn_no_digit . "d", 1);
                $data['sale_no'] = $sale_no;
            }
            // $sale_no_exists = $this->sale_model->check_sale_exists($sale_no);
            // if ($sale_no_exists) {
            //     $insert = false;
            // }
        } else {
            $last_sale = $this->salesreturn_model->getsalesreturnNo($brc_id);
            if (!empty($last_sale)) {
                $sale_no = $last_sale['sale_no'] + 1;
            }else{
                $sale_no = 1;
            }
            $data['sale_no'] = $sale_no;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/salesreturn/salesreturnCreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function salesreturnSave(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sale_no', $this->lang->line('sale_no'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('due_date', $this->lang->line('due').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('payment_mode_id', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'sale_no'    => form_error('sale_no'),
                //'due_date'  => form_error('due_date'),
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
            if(!empty($this->input->post('due_date'))){
                $due_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date'));
            }else{
                $due_date = '';
            }
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
                'due_date'            => $due_date,
                'sale_no'             => $this->input->post('sale_no'),
                'customer_type'       => $this->input->post('customer_type'),
                'payment_mode_id'     => $this->input->post('payment_mode_id'),
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
            $this->db->insert('salesreturn', $data);
            $sale_id = $this->db->insert_id();
            $data_setting                             = array();
            $data_setting['id']                       = $sys_setting_detail->id;
            $data_setting['salereturn_auto_insert']   = $sys_setting_detail->salereturn_auto_insert;
            $data_setting['salereturn_update_status'] = $sys_setting_detail->salereturn_update_status;
            if ($data_setting['salereturn_auto_insert']) {
                if ($data_setting['salereturn_update_status'] == 0) {
                    $data_setting['salereturn_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
            if(!empty($sale_id)){
                $total_rows   = $this->input->post('total_rows');
                if (isset($total_rows) && !empty($total_rows)) {
                    foreach ($total_rows as $row_key => $row_value) {
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,tax_rates.name as tax_name,tax_rates.rate,units.name as `unit_name`')->from('products')->join('tax_rates','tax_rates.id = products.tax_rate','left')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        if(!empty($product)){
                            $o_details['sale_id ']                      = $sale_id;
                            $o_details['warehouse_id']                  = 1;
                            $o_details['customer_id']                   = $this->input->post('customer_id');
                            $o_details['product_id']                    = $item_id;
                            $o_details['salereturn_item_head_id']       = $product->acc_head_sales_returns_id;
                            $o_details['salereturn_cost_item_head_id']  = $product->acc_head_cost_sale_id;
                            $o_details['product_name']                  = $product->name;
                            $o_details['description']                   = $this->input->post('description_'.$row_value);
                            $o_details['quantity']                      = $this->input->post('quantity_'.$row_value);
                            $o_details['unit']                          = $product->unit_name;
                            $o_details['net_unit_price']                = $this->input->post('rate_'.$row_value);
                            $o_details['unit_cost']                     = $product->cost;
                            $o_details['item_tax']                      = $this->input->post('rate_tax_'.$row_value);
                            $o_details['tax']                           = str_replace(",", "",$this->input->post('tax_'.$row_value));
                            $o_details['item_discount']                 = $this->input->post('discount_per_'.$row_value);
                            $o_details['discount']                      = str_replace(",", "",$this->input->post('disct_'.$row_value));
                            $o_details['subtotal']                      = str_replace(",", "",$this->input->post('amount_'.$row_value));
                            $o_details['created_by']                    = $userdata['id'];
                            //save order details
                            $this->db->insert('salesreturn_items', $o_details);
                        }
                    }
                }
                if($this->input->post('customer_type') == 1){
                    $lastinviice = $this->payments_model->lastInvoiceNoRecord($brc_id,1);
                    $countyear =  date('y');
                    if(isset($lastinviice)){
                        $countyear = substr($lastinviice['invoice_no'], 0, 4);
                    }
                    $matchdate = date('y').date('m');
                    if($countyear == $matchdate){
                        $invoice_no = $lastinviice['invoice_no'] + 1;
                    }else{
                        $invoice_no = date('y').date('m').'1';
                    }
                    $userlist  = $this->customlib->getUserData();
                    $user_id   = $userlist["id"];
                    $data = array(
                        'brc_id'             => $brc_id,
                        'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                        'voucher_type_id'    => 1,
                        'payment_type_id'    => 1,
                        'invoice_no'         => $invoice_no,
                        'invoice_type'       => 1,
                        'customer_id'        => 1,
                        'acc_head_id'        => '',
                        'profit_acc_head_id' => '',
                        'par_acc_head_id'    => $this->input->post('payment_mode_id'),
                        'debit_amount'       => $grand_total,
                        'credit_amount'      => $grand_total,
                        'note'               => $note,
                        'created_by'         => $user_id,
                        'salereturn_id'      => $sale_id,
                    );
                    $this->payments_model->addaccountvoucher($data);
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function salesreturnPrintSave(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sale_no', $this->lang->line('sale_no'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('due_date', $this->lang->line('due').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('payment_mode_id', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'sale_no'    => form_error('sale_no'),
                //'due_date'  => form_error('due_date'),
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
            if(!empty($this->input->post('due_date'))){
                $due_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date'));
            }else{
                $due_date = '';
            }
            $customer_details = $this->customers_model->get($customer_id);
            $customer         = $customer_details['company'] && $customer_details['company'] != '-' ? $customer_details['company'] : $customer_details['name'];
            $userdata         = $this->customlib->getUserData();
            $total            = str_replace(",", "", $this->input->post('total')); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = str_replace(",", "", $this->input->post('discount')); 
            $total_tax        = str_replace(",", "", $this->input->post('total_tax')); 
            $grand_total      = str_replace(",", "", $this->input->post('net_amount')); 
            $data = [
                'brc_id'            => $brc_id,
                'date'              => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'due_date'          => $due_date,
                'sale_no'           => $this->input->post('sale_no'),
                'customer_type'       => $this->input->post('customer_type'),
                'payment_mode_id'     => $this->input->post('payment_mode_id'),
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
            $this->db->insert('salesreturn', $data);
            $sale_id = $this->db->insert_id();
            $data_setting                             = array();
            $data_setting['id']                       = $sys_setting_detail->id;
            $data_setting['salereturn_auto_insert']   = $sys_setting_detail->salereturn_auto_insert;
            $data_setting['salereturn_update_status'] = $sys_setting_detail->salereturn_update_status;
            if ($data_setting['salereturn_auto_insert']) {
                if ($data_setting['salereturn_update_status'] == 0) {
                    $data_setting['salereturn_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
            if(!empty($sale_id)){
                $total_rows   = $this->input->post('total_rows');
                if (isset($total_rows) && !empty($total_rows)) {
                    foreach ($total_rows as $row_key => $row_value) {
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,tax_rates.name as tax_name,tax_rates.rate,units.name as `unit_name`')->from('products')->join('tax_rates','tax_rates.id = products.tax_rate','left')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        if(!empty($product)){
                            $o_details['sale_id ']                      = $sale_id;
                            $o_details['warehouse_id']                  = 1;
                            $o_details['customer_id']                   = $this->input->post('customer_id');
                            $o_details['product_id']                    = $item_id;
                            $o_details['salereturn_item_head_id']       = $product->acc_head_sales_returns_id;
                            $o_details['salereturn_cost_item_head_id']  = $product->acc_head_cost_sale_id;
                            $o_details['product_name']                  = $product->name;
                            $o_details['description']                   = $this->input->post('description_'.$row_value);
                            $o_details['quantity']                      = $this->input->post('quantity_'.$row_value);
                            $o_details['unit']                          = $product->unit_name;
                            $o_details['net_unit_price']                = $this->input->post('rate_'.$row_value);
                            $o_details['unit_cost']                     = $product->cost;
                            $o_details['item_tax']                      = $this->input->post('rate_tax_'.$row_value);
                            $o_details['tax']                           = str_replace(",", "",$this->input->post('tax_'.$row_value));
                            $o_details['item_discount']                 = $this->input->post('discount_per_'.$row_value);
                            $o_details['discount']                      = str_replace(",", "",$this->input->post('disct_'.$row_value));
                            $o_details['subtotal']                      = str_replace(",", "", $this->input->post('amount_'.$row_value));
                            $o_details['created_by']                    = $userdata['id'];
                            //save order details
                            $this->db->insert('salesreturn_items', $o_details);
                        }
                    }
                }
                if($this->input->post('customer_type') == 1){
                    $lastinviice = $this->payments_model->lastInvoiceNoRecord($brc_id,1);
                    $countyear =  date('y');
                    if(isset($lastinviice)){
                        $countyear = substr($lastinviice['invoice_no'], 0, 4);
                    }
                    $matchdate = date('y').date('m');
                    if($countyear == $matchdate){
                        $invoice_no = $lastinviice['invoice_no'] + 1;
                    }else{
                        $invoice_no = date('y').date('m').'1';
                    }
                    $userlist  = $this->customlib->getUserData();
                    $user_id   = $userlist["id"];
                    $data = array(
                        'brc_id'             => $brc_id,
                        'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                        'voucher_type_id'    => 1,
                        'payment_type_id'    => 1,
                        'invoice_no'         => $invoice_no,
                        'invoice_type'       => 1,
                        'customer_id'        => 1,
                        'acc_head_id'        => '',
                        'profit_acc_head_id' => '',
                        'par_acc_head_id'    => $this->input->post('payment_mode_id'),
                        'debit_amount'       => $grand_total,
                        'credit_amount'      => $grand_total,
                        'note'               => $note,
                        'created_by'         => $user_id,
                        'sale_id'            => $sale_id,
                    );
                    $this->payments_model->addaccountvoucher($data);
                }
                $data['settinglist'] = $sys_setting_detail;
                $data['salesreturnList'] = $this->salesreturn_model->printsalesreturnInsertID($sale_id);
                $this->load->view('print/printsalesreturn', $data);
            }
            // $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            // echo json_encode($array);
        }
    }

    public function edit($id,$brc_id){
        if (!$this->rbac->hasPrivilege('salesreturn', 'can_edit')) {
            access_denied();
        }
        $data['title']          = 'Edit products';
        $data['id']             = $id;
        $data['brc_id']         = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $data['productslist']   = $this->products_model->get('',7);
        $data['customerslist']  = $this->customers_model->getall($brc_id);
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data['warehouseslist'] = $this->warehouses_model->get();
        $salesreturn = $this->salesreturn_model->get($id);
        $data['salesreturn'] = $salesreturn;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/salesreturn/salesreturnEdit', $data);
        $this->load->view('layout/footer', $data);
    }

    function salesreturnUpdateSave($id){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sale_no', $this->lang->line('sale_no'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('due_date', $this->lang->line('due').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('customer_type') == 1){
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('payment_mode_id', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('customer_type', $this->lang->line('customer_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'sale_no'    => form_error('sale_no'),
                //'due_date'  => form_error('due_date'),
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
            if($this->input->post('customer_type') == 1){
                $customer_id  = 1;
            }else{
                $customer_id  = $this->input->post('customer_id');    
            }
            $warehouse_id = 1;
            $note = $this->input->post('customer_message');
            if(!empty($this->input->post('due_date'))){
                $due_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date'));
            }else{
                $due_date = '';
            }
            $customer_details = $this->customers_model->get($customer_id);
            $customer         = $customer_details['company'] && $customer_details['company'] != '-' ? $customer_details['company'] : $customer_details['name'];
            $userdata         = $this->customlib->getUserData();
            $total            = str_replace(",", "", $this->input->post('total')); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = str_replace(",", "", $this->input->post('discount')); 
            $total_tax        = str_replace(",", "", $this->input->post('total_tax')); 
            $grand_total      = str_replace(",", "", $this->input->post('net_amount')); 
            $data = [
                'id'                  => $id,
                'brc_id'              => $brc_id,
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'due_date'            => $due_date,
                'sale_no'             => $this->input->post('sale_no'),
                'customer_type'       => $this->input->post('customer_type'),
                'payment_mode_id'     => $this->input->post('payment_mode_id'),
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
            $this->db->where('salesreturn.id', $data['id']);
            $this->db->update('salesreturn', $data);
            $previous_id    = $this->input->post('previous_id');
            $carts_id       = $this->input->post('carts_id');
            $delete_result  = array();
            if(!empty($carts_id)){
                $delete_result  = array_diff($previous_id, $carts_id);
            }
            if (!empty($delete_result)) {
                $d_delete_array = array();
                foreach ($delete_result as $d_delete_key => $d_delete_value) {
                    $d_delete_array[] = $d_delete_value;
                }
                $this->salesreturn_model->remove_detial_product($d_delete_array);
            }
            $total_rows   = $this->input->post('total_rows');
            if (isset($total_rows) && !empty($total_rows)) {
                foreach ($total_rows as $row_key => $row_value) {
                    $update_id = $this->input->post('update_id_'.$row_value);
                    if(!empty($update_id)){
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,tax_rates.name as tax_name,tax_rates.rate,units.name as `unit_name`')->from('products')->join('tax_rates','tax_rates.id = products.tax_rate','left')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        if(!empty($product)){
                            $o_details['id']                            = $update_id;
                            $o_details['sale_id']                       = $id;
                            $o_details['warehouse_id']                  = 1;
                            $o_details['customer_id']                   = $this->input->post('customer_id');
                            $o_details['product_id']                    = $item_id;
                            $o_details['salereturn_item_head_id']       = $product->acc_head_sales_returns_id;
                            $o_details['salereturn_cost_item_head_id']  = $product->acc_head_cost_sale_id;
                            $o_details['product_name']                  = $product->name;
                            $o_details['description']                   = $this->input->post('description_'.$row_value);
                            $o_details['quantity']                      = $this->input->post('quantity_'.$row_value);
                            $o_details['unit']                          = $product->unit_name;
                            $o_details['net_unit_price']                = $this->input->post('rate_'.$row_value);
                            $o_details['unit_cost']                     = $product->cost;
                            $o_details['item_tax']                      = $this->input->post('rate_tax_'.$row_value);
                            $o_details['tax']                           = str_replace(",", "", $this->input->post('tax_'.$row_value));
                            $o_details['item_discount']                 = $this->input->post('discount_per_'.$row_value);
                            $o_details['discount']                      = str_replace(",", "", $this->input->post('disct_'.$row_value));
                            $o_details['subtotal']                      = str_replace(",", "", $this->input->post('amount_'.$row_value));
                            $o_details['created_by']                    = $userdata['id'];
                            $this->db->where('salesreturn_items.id', $o_details['id']);
                            $this->db->update('salesreturn_items', $o_details);
                        }
                    }else{
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,tax_rates.name as tax_name,tax_rates.rate,units.name as `unit_name`')->from('products')->join('tax_rates','tax_rates.id = products.tax_rate','left')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        if(!empty($product)){
                            $i_details['sale_id']                       = $id;
                            $i_details['warehouse_id']                  = 1;
                            $i_details['customer_id']                   = $this->input->post('customer_id');
                            $i_details['product_id']                    = $item_id;
                            $i_details['salereturn_item_head_id']       = $product->acc_head_sales_returns_id;
                            $i_details['salereturn_cost_item_head_id']  = $product->acc_head_cost_sale_id;
                            $i_details['product_name']                  = $product->name;
                            $i_details['description']                   = $this->input->post('description_'.$row_value);
                            $i_details['quantity']                      = $this->input->post('quantity_'.$row_value);
                            $i_details['unit']                          = $product->unit_name;
                            $i_details['net_unit_price']                = $this->input->post('rate_'.$row_value);
                            $i_details['unit_cost']                     = $product->cost;
                            $i_details['item_tax']                      = $this->input->post('rate_tax_'.$row_value);
                            $i_details['tax']                           = str_replace(",", "", $this->input->post('tax_'.$row_value));
                            $i_details['item_discount']                 = $this->input->post('discount_per_'.$row_value);
                            $i_details['discount']                      = str_replace(",", "", $this->input->post('disct_'.$row_value));
                            $i_details['subtotal']                      = str_replace(",", "", $this->input->post('amount_'.$row_value));
                            $i_details['created_by']                    = $userdata['id'];
                            //save order details
                            $this->db->insert('salesreturn_items', $i_details);
                        }
                    }
                }
            }
            if($this->input->post('customer_type') == 1){
                $sdet = $this->salesreturn_model->getPaymentBySalereturnID($id);
                if(!empty($sdet)){
                    $userlist  = $this->customlib->getUserData();
                    $user_id   = $userlist["id"];
                    $data = array(
                        'id'                 => $sdet['id'],
                        'brc_id'             => $brc_id,
                        'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                        'voucher_type_id'    => 1,
                        'payment_type_id'    => 1,
                        'invoice_type'       => 1,
                        'customer_id'        => 1,
                        'acc_head_id'        => '',
                        'profit_acc_head_id' => '',
                        'par_acc_head_id'    => $this->input->post('payment_mode_id'),
                        'debit_amount'       => $grand_total,
                        'credit_amount'      => $grand_total,
                        'note'               => $note,
                        'updated_by'         => $user_id,
                        'salereturn_id'      => $id,
                    );
                    $this->payments_model->addaccountvoucher($data);
                }else{
                    $lastinviice = $this->payments_model->lastInvoiceNoRecord($brc_id,1);
                    $countyear =  date('y');
                    if(isset($lastinviice)){
                        $countyear = substr($lastinviice['invoice_no'], 0, 4);
                    }
                    $matchdate = date('y').date('m');
                    if($countyear == $matchdate){
                        $invoice_no = $lastinviice['invoice_no'] + 1;
                    }else{
                        $invoice_no = date('y').date('m').'1';
                    }
                    $userlist  = $this->customlib->getUserData();
                    $user_id   = $userlist["id"];
                    $data = array(
                        'brc_id'             => $brc_id,
                        'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                        'voucher_type_id'    => 1,
                        'payment_type_id'    => 1,
                        'invoice_no'         => $invoice_no,
                        'invoice_type'       => 1,
                        'customer_id'        => 1,
                        'acc_head_id'        => '',
                        'profit_acc_head_id' => '',
                        'par_acc_head_id'    => $this->input->post('payment_mode_id'),
                        'debit_amount'       => $grand_total,
                        'credit_amount'      => $grand_total,
                        'note'               => $note,
                        'created_by'         => $user_id,
                        'salereturn_id'            => $id,
                    );
                    $this->payments_model->addaccountvoucher($data);
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function delete($id,$brc_id){
        if (!$this->rbac->hasPrivilege('salesreturn', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Products List';
        $this->salesreturn_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/salesreturn/index/'.$brc_id);
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
        $product = $this->db->select('products.*,tax_rates.name as tax_name,tax_rates.rate,units.name as `unit_name`')->from('products')->join('tax_rates','tax_rates.id = products.tax_rate','left')->join('units','units.id = products.unit','left')->where('products.id',$id)->get('')->row();
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
                    'qty'                   => '',
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

    public function getsalesreturnInvoice($id) {
        $data['id'] = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data['print_details'] = '';//$this->Printing_model->get('', 'pharmacy');
        $result = $this->salesreturn_model->getsalesreturnInvoiceByID($id);
        $data['result'] = $result;
        $this->load->view('admin/salesreturn/printInvoice', $data);
        
    }
    
    public function salesreturnreports(){
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/salesreturn/salesreturnreports');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('admin/salesreturn/reports/salesreturnreports');
        $this->load->view('layout/footer');
    }
    
    function customerwisesalesreturnsum($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('customer_wise_details', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/salesreturn/salesreturnreports');
        $this->session->set_userdata('subsub_menu', 'Reports/salesreturn/customerwisesalesreturnsum');
        $data['title'] = 'Customer Wise salesreturn Details';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id']      = $brc_id;
        $data['branchlist']  = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['customerslist']  = $this->customers_model->getall($brc_id);
        if(isset($_POST['search_type']) && $_POST['search_type']!=''){
            $dates=$this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type']=$_POST['search_type'];
        }else{
            $dates=$this->customlib->get_betweendate('this_year');
            $data['search_type']=''; 
        }
        if(!empty($this->input->post('brc_id'))){
            $this->form_validation->set_rules('brc_id', $this->lang->line('branch'), 'trim|required|xss_clean');
        }else{
            
        }
        $this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $customer_id                    = $this->input->post('customer_id');
            $start_date                     = date('Y-m-d',strtotime($dates['from_date']));
            $end_date                       = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date']             = $start_date;
            $data['end_date']               = $end_date;
            $data['customer_id']            = $customer_id;
            $data['salelist']               = $this->salesreturn_model->getsalesreturnByCustomersIDSummary($brc_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/salesreturn/reports/customerwisesalesreturnsum', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function customerwisesalesreturndet($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('customer_wise_details', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/salesreturn/salesreturnreports');
        $this->session->set_userdata('subsub_menu', 'Reports/salesreturn/customerwisesalesreturndet');
        $data['title'] = 'Customer Wise salesreturn Details';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id']      = $brc_id;
        $data['branchlist']  = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['customerslist']  = $this->customers_model->getall($brc_id);
        if(isset($_POST['search_type']) && $_POST['search_type']!=''){
            $dates=$this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type']=$_POST['search_type'];
        }else{
            $dates=$this->customlib->get_betweendate('this_year');
            $data['search_type']=''; 
        }
        if(!empty($this->input->post('brc_id'))){
            $this->form_validation->set_rules('brc_id', $this->lang->line('branch'), 'trim|required|xss_clean');
        }else{
            
        }
        $this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $customer_id                    = $this->input->post('customer_id');
            $start_date                     = date('Y-m-d',strtotime($dates['from_date']));
            $end_date                       = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date']             = $start_date;
            $data['end_date']               = $end_date;
            $data['customer_id']            = $customer_id;
            $data['salelist']               = $this->salesreturn_model->getsalesreturnByCustomersIDDetails($brc_id,$customer_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/salesreturn/reports/customerwisesalesreturndet', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function itemwisesalesreturnsum($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('customer_wise_details', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/salesreturn/salesreturnreports');
        $this->session->set_userdata('subsub_menu', 'Reports/salesreturn/itemwisesalesreturnsum');
        $data['title'] = 'Products Wise salesreturn Details';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id']      = $brc_id;
        $data['branchlist']  = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['customerslist']  = $this->customers_model->getall($brc_id);
        if(isset($_POST['search_type']) && $_POST['search_type']!=''){
            $dates=$this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type']=$_POST['search_type'];
        }else{
            $dates=$this->customlib->get_betweendate('this_year');
            $data['search_type']=''; 
        }
        if(!empty($this->input->post('brc_id'))){
            $this->form_validation->set_rules('brc_id', $this->lang->line('branch'), 'trim|required|xss_clean');
        }else{
            
        }
        $this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $customer_id                    = $this->input->post('customer_id');
            $start_date                     = date('Y-m-d',strtotime($dates['from_date']));
            $end_date                       = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date']             = $start_date;
            $data['end_date']               = $end_date;
            $data['customer_id']            = $customer_id;
            $data['salelist']               = $this->salesreturn_model->getsalesreturnByItemsIDSummary($brc_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/salesreturn/reports/itemwisesalesreturnsum', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function itemwisesalesreturndet($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('customer_wise_details', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/salesreturn/salesreturnreports');
        $this->session->set_userdata('subsub_menu', 'Reports/salesreturn/itemwisesalesreturndet');
        $data['title'] = 'Customer Wise salesreturn Details';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id']       = $brc_id;
        $data['branchlist']   = $this->branchsettings_model->get();
        $data['searchlist']   = $this->customlib->get_searchtype();
        $data["productslist"] = $this->products_model->get();
        if(isset($_POST['search_type']) && $_POST['search_type']!=''){
            $dates=$this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type']=$_POST['search_type'];
        }else{
            $dates=$this->customlib->get_betweendate('this_year');
            $data['search_type']=''; 
        }
        if(!empty($this->input->post('brc_id'))){
            $this->form_validation->set_rules('brc_id', $this->lang->line('branch'), 'trim|required|xss_clean');
        }else{
            
        }
        $this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('item_id', $this->lang->line('products_services'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $item_id                        = $this->input->post('item_id');
            $start_date                     = date('Y-m-d',strtotime($dates['from_date']));
            $end_date                       = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date']             = $start_date;
            $data['end_date']               = $end_date;
            $data['item_id']                = $item_id;
            $data['salelist']               = $this->salesreturn_model->getsalesreturnByItemsIDDetails($brc_id,$item_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/salesreturn/reports/itemwisesalesreturndet', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function viewsalesreturninvoice(){
        $sale_id   = $this->input->post('sale_id');
        $data['sale_id'] = $sale_id;
        $data['inv'] = $this->salesreturn_model->printsalesreturnInsertID($sale_id);
        $this->load->view('admin/salesreturn/salesreturninvoiceview', $data);
    }

}
