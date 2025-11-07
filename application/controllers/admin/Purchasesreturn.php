<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Purchasesreturn extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('cart');
        $this->load->helper('url');
        $this->load->library('media_storage');
    }

    public function index($brnc_id = null){
        if (!$this->rbac->hasPrivilege('purchasesreturn', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'purchases');
        $this->session->set_userdata('sub_menu', 'purchasesreturn/index');
        $data['title']            = 'purchasesreturn List';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['productslist']   = $this->products_model->get('',7);
        $data['supplierlist']  = $this->supplier_model->getall($brc_id);
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/purchasesreturn/purchasesreturnList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function searchpurchasesreturn(){
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
            $supplier_id   = $this->input->post('supplier_id');
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to,'product_id' =>$product_id,'supplier_id' =>$supplier_id,'brc_id' => $brc_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchpurchasesreturnlist(){
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $product_id  = $this->input->post('product_id');
        $supplier_id = $this->input->post('supplier_id');
        if(!empty($this->input->post('brc_id'))){
            $brc_id = $this->input->post('brc_id');
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
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
            $resultList        = $this->purchasesreturn_model->getpurchasesreturn($brc_id,1,$product_id,$supplier_id, $date_from, $date_to);
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
                if ($this->rbac->hasPrivilege('purchasesreturn', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/purchasesreturn/edit/" . $value->id . "/". $value->brc_id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('purchasesreturn', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/purchasesreturn/delete/" . $value->id . "/". $value->brc_id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('purchasesreturn', 'can_view')) {
                    $purchasebillpdf = "<a href='" . base_url() . "report/pdfpurchasesreturnbill/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('purchases_return_bill') . "' data-toggle='tooltip'><i class='fa fa-download'></i><span>".$this->lang->line('purchases_return_bill')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/purchasesreturn/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>". $purchasebillpdf ."</li>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                // $items = $this->purchasesreturn_model->getItemsByQuotesByPID($value->id,$product_id);
                // $itemqty = '';
                // if(!empty($items)){
                //     foreach($items as $ival){
                //         $itemqty .= $ival['item_name'].' ('.$ival['qty'].')<br/>';
                //     }
                // }
                $bill_no = "<a href='#' data-toggle='modal' data-target='#purchasebillModal' data-purchase_id='" . $value->id . "' data-toggle='tooltip'  title='".$this->lang->line('view_purchase_return_bill')."'>" . $value->purchase_no . "</a>";
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-primary btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $bill_no;
                $row[]     = $value->supplier;
                $row[]     = number_format($value->grand_total, 2, '.', ',');
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
        $result = $this->purchasesreturn_model->get($id);
        $this->media_storage->filedownload($result['attachment'], "./uploads/purchasesreturn");
    }

    public function getpurchasesreturnlist($brc_id){
        $m               = $this->purchasesreturn_model->getpurchasesreturn($brc_id,1,"","","","");
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
                if ($this->rbac->hasPrivilege('purchasesreturn', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/purchasesreturn/edit/" . $value->id . "/". $value->brc_id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('purchasesreturn', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/purchasesreturn/delete/" . $value->id . "/". $value->brc_id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('purchasesreturn', 'can_view')) {
                    $purchasebillpdf = "<a href='" . base_url() . "report/pdfpurchasesreturnbill/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('purchases_return_bill') . "' data-toggle='tooltip'><i class='fa fa-download'></i><span>".$this->lang->line('purchases_return_bill')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/purchasesreturn/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$purchasebillpdf."</li>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                // $items = $this->purchasesreturn_model->getItemsByQuotesByPID($value->id);
                // $itemqty = '';
                // if(!empty($items)){
                //     foreach($items as $ival){
                //         $itemqty .= $ival['item_name'].' ('.$ival['qty'].')<br/>';
                //     }
                // }
                $bill_no = "<a href='#' data-toggle='modal' data-target='#purchasebillModal' data-purchase_id='" . $value->id . "' data-toggle='tooltip'  title='".$this->lang->line('view_purchase_return_bill')."'>" . $value->purchase_no . "</a>";
                $sts = '';
                if($value->status == 1){
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-danger btn-xs'  title='".$this->lang->line('pending')."'>".$this->lang->line('pending')."</a>";
                }else{
                    $sts = "<a href='#' data-toggle='tooltip' class='btn btn-success btn-xs'  title='".$this->lang->line('sent')."'>".$this->lang->line('sent')."</a>";
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $bill_no;
                $row[]     = $value->supplier;
                $row[]     = number_format($value->grand_total, 2, '.', ',');;
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
        if (!$this->rbac->hasPrivilege('purchasesreturn', 'can_add')) {
            access_denied();
        }
        $data['title']          = 'Add purchasesreturn';
        $data['brc_id']         = $brc_id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data['productslist']   = $this->products_model->get('',7);
        $data['supplierlist']   = $this->supplier_model->getall($brc_id);
        $data['warehouseslist'] = $this->warehouses_model->get();
        $sys_setting_detail             = $this->setting_model->getSetting();
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data['sys_setting']            = $sys_setting_detail ;
        $data['purchase_auto_insert'] = $sys_setting_detail->purchase_auto_insert;
        $purchase_no  = 0;
        if ($sys_setting_detail->purchase_auto_insert) {
            if ($sys_setting_detail->purchase_update_status) {
                $purchase_no = $sys_setting_detail->purchase_prefix . $sys_setting_detail->purchase_start_from;
                $last_purchase = $this->purchasesreturn_model->getpurchasesreturnNo($brc_id);
                if(!empty($last_purchase)){
                    $last_purchase_digit = str_replace($sys_setting_detail->purchase_prefix, "", $last_purchase['purchase_no']);
                }else{
                    $last_purchase_digit = 0;
                }
                $purchase_no                = $sys_setting_detail->purchase_prefix . sprintf("%0" . $sys_setting_detail->purchase_no_digit . "d", $last_purchase_digit + 1);
                $data['purchase_no'] = $purchase_no;
            } else {
                $purchase_no                = $sys_setting_detail->purchase_prefix . sprintf("%0" . $sys_setting_detail->purchase_no_digit . "d", 1);
                $data['purchase_no'] = $purchase_no;
            }
            // $purchase_no_exists = $this->purchase_model->check_purchase_exists($purchase_no);
            // if ($purchase_no_exists) {
            //     $insert = false;
            // }
        } else {
            $last_purchase = $this->purchasesreturn_model->getpurchasesreturnNo($brc_id);
            if (!empty($last_purchase)) {
                $purchase_no = $last_purchase['purchase_no'] + 1;
            }else{
                $purchase_no = 1;
            }
            $data['purchase_no'] = $purchase_no;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/purchasesreturn/purchasesreturncreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function purchasesreturnSave(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('purchase_no', $this->lang->line('purchase_no'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('due_date', $this->lang->line('due').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('supplier_type') == 1){
            $this->form_validation->set_rules('supplier_type', $this->lang->line('supplier_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('supplier_type', $this->lang->line('supplier_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'purchase_no'    => form_error('purchase_no'),
                //'due_date'  => form_error('due_date'),
                'supplier_id'  => form_error('supplier_id'),
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
            if($this->input->post('supplier_type') == 1){
                $supplier_id  = 1;
            }else{
                $supplier_id  = $this->input->post('supplier_id');    
            }
            $warehouse_id = 1;
            $note = $this->input->post('supplier_message');
            $supplier_details = $this->supplier_model->get($supplier_id);
            $supplier         = $supplier_details['company'] && $supplier_details['company'] != '-' ? $supplier_details['company'] : $supplier_details['name'];
            $userdata         = $this->customlib->getUserData();
            $total            = str_replace(",", "",$this->input->post('total')); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = str_replace(",", "",$this->input->post('discount')); 
            $total_tax        = str_replace(",", "",$this->input->post('total_tax')); 
            $exclusive      = str_replace(",", "",$this->input->post('exclusive'));
            $grand_total      = str_replace(",", "",$this->input->post('net_amount'));
            if($total_discount > 0){
                $discount_head_id = 18;
            }else{
                $discount_head_id = NULL;
            }
            if($total_tax > 0){
                $gst_head_id = 1;
            }else{
                $gst_head_id = NULL;
            }
            $data = [
                'brc_id'              => $brc_id,
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'purchase_no'         => $this->input->post('purchase_no'),
                'supplier_type'       => $this->input->post('supplier_type'),
                'payment_mode_id'     => $this->input->post('payment_mode_id'),
                'supplier_id'         => $supplier_id,
                'supplier'            => $supplier,
                'warehouse_id'        => $warehouse_id,
                'note'                => $note,
                'total'               => $total,
                'discount_head_id'    => $discount_head_id,
                'discount_percent'    => $discount_percent,
                'total_discount'      => $total_discount,
                'gst_head_id'         => $gst_head_id,
                'total_tax'           => $total_tax,
                'gross_amount'        => $exclusive,
                'grand_total'         => $grand_total,
                'status'              => 1,
                'created_by'          => $userdata['id'],
            ];
            $this->db->insert('purchasesreturn', $data);
            $purchase_id = $this->db->insert_id();
            $data_setting                             = array();
            $data_setting['id']                       = $sys_setting_detail->id;
            $data_setting['purchase_auto_insert']     = $sys_setting_detail->purchase_auto_insert;
            $data_setting['purchase_update_status']   = $sys_setting_detail->purchase_update_status;
            if ($data_setting['purchase_auto_insert']) {
                if ($data_setting['purchase_update_status'] == 0) {
                    $data_setting['purchase_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
            if(!empty($purchase_id)){
                $total_rows   = $this->input->post('total_rows');
                if (isset($total_rows) && !empty($total_rows)) {
                    foreach ($total_rows as $row_key => $row_value) {
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,units.name as `unit_name`')->from('products')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        $o_details['purchase_id ']               = $purchase_id;
                        $o_details['warehouse_id']               = 1;
                        $o_details['supplier_id']                = $supplier_id;
                        $o_details['product_id']                 = $item_id;
                        $o_details['purchase_returns_item_head_id']      = $product->acc_head_purchases_returns_id;
                        $o_details['purchase_cost_item_head_id'] = $product->acc_head_cost_sale_id;
                        $o_details['discount_head_id']           = $discount_head_id;
                        $o_details['gst_head_id']                = $gst_head_id;
                        $o_details['product_name']               = $product->name;
                        $o_details['description']                = $this->input->post('description_'.$row_value);
                        $o_details['quantity']                   = $this->input->post('quantity_'.$row_value);
                        $o_details['unit']                       = $product->unit_name;
                        $o_details['net_unit_cost']              = $this->input->post('rate_'.$row_value);
                        $o_details['item_tax']                   = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']                        = str_replace(",", "",$this->input->post('tax_'.$row_value));
                        $o_details['item_discount']              = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']                   = str_replace(",", "",$this->input->post('disct_'.$row_value));
                        $o_details['subtotal']                   = str_replace(",", "",$this->input->post('amount_'.$row_value));
                        $o_details['created_by']                 = $userdata['id'];
                        //save order details
                        $this->db->insert('purchasesreturn_items', $o_details);
                    }
                }
                if($this->input->post('supplier_type') == 1){
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
                        'voucher_type_id'    => 2,
                        'payment_type_id'    => 2,
                        'invoice_no'         => $invoice_no,
                        'invoice_type'       => 1,
                        'supplier_id'        => $supplier_id,
                        'acc_head_id'        => '',
                        'profit_acc_head_id' => '',
                        'par_acc_head_id'    => $this->input->post('payment_mode_id'),
                        'debit_amount'       => $grand_total,
                        'credit_amount'      => $grand_total,
                        'note'               => $note,
                        'created_by'         => $user_id,
                        'purchase_id'        => $purchase_id,
                    );
                    $this->payments_model->addaccountvoucher($data);
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function edit($id,$brc_id){
        if (!$this->rbac->hasPrivilege('purchasesreturn', 'can_edit')) {
            access_denied();
        }
        $data['title']          = 'Edit products';
        $data['id']             = $id;
        $data['brc_id']         = $brc_id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data['productslist']   = $this->products_model->get('',7);
        $data['supplierlist']   = $this->supplier_model->getall($brc_id);
        $data['warehouseslist'] = $this->warehouses_model->get();
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $purchasesreturn = $this->purchasesreturn_model->get($id);
        $data['purchasesreturn'] = $purchasesreturn;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/purchasesreturn/purchasesreturnedit', $data);
        $this->load->view('layout/footer', $data);
    }

    function purchasesreturnUpdateSave($id){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('purchase_no', $this->lang->line('purchase_no'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('due_date', $this->lang->line('due').' '.$this->lang->line('date') , 'trim|required|xss_clean');
        if($this->input->post('supplier_type') == 1){
            $this->form_validation->set_rules('supplier_type', $this->lang->line('supplier_type'), 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('supplier_type', $this->lang->line('supplier_type'), 'trim|required|xss_clean');    
            $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');    
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'date'         => form_error('date'),
                'purchase_no'    => form_error('purchase_no'),
                //'due_date'  => form_error('due_date'),
                'supplier_id'  => form_error('supplier_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            if($this->input->post('supplier_type') == 1){
                $supplier_id  = 1;
            }else{
                $supplier_id  = $this->input->post('supplier_id');    
            }
            $warehouse_id = 1;
            $note = $this->input->post('supplier_message');
            $supplier_details = $this->supplier_model->get($supplier_id);
            $supplier         = $supplier_details['company'] && $supplier_details['company'] != '-' ? $supplier_details['company'] : $supplier_details['name'];
            $userdata         = $this->customlib->getUserData();
            $total            = str_replace(",", "",$this->input->post('total')); 
            $discount_percent = $this->input->post('discount_percent'); 
            $total_discount   = str_replace(",", "",$this->input->post('discount')); 
            $total_tax        = str_replace(",", "",$this->input->post('total_tax')); 
            $exclusive        = str_replace(",", "",$this->input->post('exclusive')); 
            $grand_total      = str_replace(",", "",$this->input->post('net_amount')); 
            $data = [
                'id'                  => $id,
                'brc_id'              => $brc_id,
                'date'                => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'purchase_no'         => $this->input->post('purchase_no'),
                'supplier_type'       => $this->input->post('supplier_type'),
                'payment_mode_id'     => $this->input->post('payment_mode_id'),
                'supplier_id'         => $supplier_id,
                'supplier'            => $supplier,
                'warehouse_id'        => $warehouse_id,
                'note'                => $note,
                'total'               => $total,
                'discount_percent'    => $discount_percent,
                'total_discount'      => $total_discount,
                'total_tax'           => $total_tax,
                'gross_amount'        => $exclusive,
                'grand_total'         => $grand_total,
                'status'              => 1,
                'created_by'          => $userdata['id'],
            ];
            $this->db->where('purchasesreturn.id', $data['id']);
            $this->db->update('purchasesreturn', $data);
            $previous_id    = $this->input->post('previous_id');
            $carts_id       = $this->input->post('carts_id');
            $delete_result  = array();
            if(!empty($previous_id)){
                $delete_result  = array_diff($previous_id, $carts_id);
            }
            if (!empty($delete_result)) {
                $d_delete_array = array();
                foreach ($delete_result as $d_delete_key => $d_delete_value) {
                    $d_delete_array[] = $d_delete_value;
                }
                $this->purchasesreturn_model->remove_detial_product($d_delete_array);

            }
            $total_rows   = $this->input->post('total_rows');
            if (isset($total_rows) && !empty($total_rows)) {
                foreach ($total_rows as $row_key => $row_value) {
                    $update_id = $this->input->post('update_id_'.$row_value);
                    if(!empty($update_id)){
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,units.name as `unit_name`')->from('products')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        $o_details['id']                         = $update_id;
                        $o_details['purchase_id']                = $id;
                        $o_details['warehouse_id']               = 1;
                        $o_details['supplier_id']                = $supplier_id;
                        $o_details['product_id']                 = $item_id;
                        $o_details['purchase_returns_item_head_id']      = $product->acc_head_purchases_returns_id;
                        $o_details['purchase_cost_item_head_id'] = $product->acc_head_cost_sale_id;
                        $o_details['product_name']               = $product->name;
                        $o_details['description']                = $this->input->post('description_'.$row_value);
                        $o_details['quantity']                   = $this->input->post('quantity_'.$row_value);
                        $o_details['net_unit_cost']              = $this->input->post('rate_'.$row_value);
                        $o_details['unit']                       = $product->unit_name;
                        $o_details['item_tax']                   = $this->input->post('rate_tax_'.$row_value);
                        $o_details['tax']                        = str_replace(",", "",$this->input->post('tax_'.$row_value));
                        $o_details['item_discount']              = $this->input->post('discount_per_'.$row_value);
                        $o_details['discount']                   = str_replace(",", "",$this->input->post('disct_'.$row_value));
                        $o_details['subtotal']                   = str_replace(",", "",$this->input->post('amount_'.$row_value));
                        $o_details['created_by']                 = $userdata['id'];
                        $this->db->where('purchasesreturn_items.id', $o_details['id']);
                        $this->db->update('purchasesreturn_items', $o_details);
                    }else{
                        $item_id = $this->input->post('item_id_'.$row_value);
                        $product = $this->db->select('products.*,units.name as `unit_name`')->from('products')->join('units','units.id = products.unit','left')->where('products.id',$item_id)->get('')->row();
                        $i_details['purchase_id']                = $id;
                        $i_details['warehouse_id']               = 1;
                        $i_details['supplier_id']                = $supplier_id;
                        $i_details['product_id']                 = $item_id;
                        $i_details['purchase_returns_item_head_id']      = $product->acc_head_purchases_returns_id;
                        $i_details['purchase_cost_item_head_id'] = $product->acc_head_cost_sale_id;
                        $i_details['product_name']               = $product->name;
                        $i_details['description']                = $this->input->post('description_'.$row_value);
                        $i_details['quantity']                   = $this->input->post('quantity_'.$row_value);
                        $i_details['net_unit_cost']              = $this->input->post('rate_'.$row_value);
                        $i_details['unit']                       = $product->unit_name;
                        $i_details['item_tax']                   = $this->input->post('rate_tax_'.$row_value);
                        $i_details['tax']                        = str_replace(",", "",$this->input->post('tax_'.$row_value));
                        $i_details['item_discount']              = $this->input->post('discount_per_'.$row_value);
                        $i_details['discount']                   = str_replace(",", "",$this->input->post('disct_'.$row_value));
                        $i_details['subtotal']                   = str_replace(",", "",$this->input->post('amount_'.$row_value));
                        $i_details['created_by']                 = $userdata['id'];
                        //save order details
                        $this->db->insert('purchasesreturn_items', $i_details);
                    }
                }
            }
            if($this->input->post('supplier_type') == 1){
                $pdet = $this->purchasesreturn_model->getPaymentByPurchaseID($id);
                if(!empty($pdet)){
                    $userlist  = $this->customlib->getUserData();
                    $user_id   = $userlist["id"];
                    $data = array(
                        'id'                 => $pdet['id'],
                        'brc_id'             => $brc_id,
                        'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                        'voucher_type_id'    => 2,
                        'payment_type_id'    => 2,
                        'invoice_type'       => 1,
                        'supplier_id'        => $supplier_id,
                        'acc_head_id'        => '',
                        'profit_acc_head_id' => '',
                        'par_acc_head_id'    => $this->input->post('payment_mode_id'),
                        'debit_amount'       => $grand_total,
                        'credit_amount'      => $grand_total,
                        'note'               => $note,
                        'updated_by'         => $user_id,
                        'purchase_id'        => $id,
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
                        'voucher_type_id'    => 2,
                        'payment_type_id'    => 2,
                        'invoice_no'         => $invoice_no,
                        'invoice_type'       => 1,
                        'supplier_id'        => $supplier_id,
                        'acc_head_id'        => '',
                        'profit_acc_head_id' => '',
                        'par_acc_head_id'    => $this->input->post('payment_mode_id'),
                        'debit_amount'       => $grand_total,
                        'credit_amount'      => $grand_total,
                        'note'               => $note,
                        'created_by'         => $user_id,
                        'purchase_id'        => $id,
                    );
                    $this->payments_model->addaccountvoucher($data);
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function delete($id,$brc_id){
        if (!$this->rbac->hasPrivilege('purchasesreturn', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Products List';
        $this->purchasesreturn_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/purchasesreturn/index/'.$brc_id);
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
                    'qty'                   => 1,
                    'price'                 => $product->cost,
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
    
    public function getBillDetails($id) {
        $data['id'] = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data['print_details'] = '';//$this->Printing_model->get('', 'pharmacy');
        $result = $this->purchasesreturn_model->getPurchaseBillByID($id);
        $data['result'] = $result;
        $this->load->view('admin/purchasesreturn/printBill', $data);
        
    }
    
    public function purchasesreturnreports(){
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/purchasesreturn/purchasesreturnreports');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('admin/purchasesreturn/reports/purchasesreturnreports');
        $this->load->view('layout/footer');
    }
    
    function supplierwisepurchasesreturnsum($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('supplier_wise_purchasesreturn_summary', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/purchasesreturn/purchasesreturnreports');
        $this->session->set_userdata('subsub_menu', 'Reports/purchasesreturn/supplierwisepurchasesreturnsum');
        $data['title'] = 'Supplier Wise purchasesreturn Details';
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
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $start_date                     = date('Y-m-d',strtotime($dates['from_date']));
            $end_date                       = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date']             = $start_date;
            $data['end_date']               = $end_date;
            $data['purchasesreturnlist']               = $this->purchasesreturn_model->getpurchasesreturnBySuppliersIDSummary($brc_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/purchasesreturn/reports/supplierwisepurchasesreturnsum', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function supplierwisepurchasesreturndet($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('supplier_wise_purchasesreturn_details', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/purchasesreturn/purchasesreturnreports');
        $this->session->set_userdata('subsub_menu', 'Reports/purchasesreturn/supplierwisepurchasesreturndet');
        $data['title'] = 'Customer Wise Sales Details';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id']      = $brc_id;
        $data['branchlist']  = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['supplierlist']  = $this->supplier_model->getall($brc_id);
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
        $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $supplier_id                    = $this->input->post('supplier_id');
            $start_date                     = date('Y-m-d',strtotime($dates['from_date']));
            $end_date                       = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date']             = $start_date;
            $data['end_date']               = $end_date;
            $data['supplier_id']            = $supplier_id;
            $data['purchasesreturnlist']          = $this->purchasesreturn_model->getpurchasesreturnBySuppliersIDDetails($brc_id,$supplier_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/purchasesreturn/reports/supplierwisepurchasesreturndet', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function itemwisepurchasesreturnsum($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('produts_wise_purchasesreturn_summary', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/purchasesreturn/purchasesreturnreports');
        $this->session->set_userdata('subsub_menu', 'Reports/purchasesreturn/itemwisepurchasesreturnsum');
        $data['title'] = 'Products Wise Sales Details';
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
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $start_date                     = date('Y-m-d',strtotime($dates['from_date']));
            $end_date                       = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date']             = $start_date;
            $data['end_date']               = $end_date;
            $data['purchasesreturnlist']          = $this->purchasesreturn_model->getpurchasesreturnByItemsIDSummary($brc_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/purchasesreturn/reports/itemwisepurchasesreturnsum', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function itemwisepurchasesreturndet($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('produts_wise_purchasesreturn_details', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/purchasesreturn/salesreports');
        $this->session->set_userdata('subsub_menu', 'Reports/purchasesreturn/itemwisepurchasesreturndet');
        $data['title'] = 'Customer Wise Sales Details';
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
            $data['purchasesreturnlist']          = $this->purchasesreturn_model->getpurchasesreturnByItemsIDDetails($brc_id,$item_id,$start_date,$end_date);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/purchasesreturn/reports/itemwisepurchasesreturndet', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function viewpurchasebill(){
        $purchase_id   = $this->input->post('purchase_id');
        $data['purchase_id'] = $purchase_id;
        $data['inv'] = $this->purchasesreturn_model->printpurchasesreturnInsertID($purchase_id);
        $this->load->view('admin/purchasesreturn/purchasereturnbillview', $data);
    }

}
