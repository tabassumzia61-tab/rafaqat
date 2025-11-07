<?php
class Payments extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function paymentin($brnc_id = null) {
        $this->session->set_userdata('top_menu', 'payments');
        $this->session->set_userdata('sub_menu', 'admin/payments/paymentin');
        $data["title"] = $this->lang->line('add')." ".$this->lang->line('payment');
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data["branchlist"]   = $this->branchsettings_model->get();
        $data["customerlist"] = $this->customers_model->getall($brc_id);
        $data["stafflist"]    = $this->staff_model->getStaffByBrach($brc_id);
        $data["acclist"]    = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data['searchlist']     = $this->customlib->get_searchtype();
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $this->load->view("layout/header");
        $this->load->view("admin/payments/paymentinList", $data);
        $this->load->view("layout/footer");
    }
    
    
    public function searchpaymentin(){
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
            $accounts_id   = $this->input->post('accounts_id');
            $customer_id   = $this->input->post('customer_id');
            $staff_id      = $this->input->post('staff_id');
            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to,'accounts_id' =>$accounts_id,'customer_id' =>$customer_id,'staff_id'=>$staff_id,'brc_id' => $brc_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchpaymentinlist(){
        $search_type  = $this->input->post('search_type');
        $button_type  = $this->input->post('button_type');
        $accounts_id  = $this->input->post('accounts_id');
        $customer_id  = $this->input->post('customer_id');
        $staff_id     = $this->input->post('staff_id');
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
            $resultList        = $this->payments_model->getVoucherSearchDetails($brc_id,$date_from,$date_to,1,$accounts_id, $customer_id,'', $staff_id);
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
                if ($this->rbac->hasPrivilege('payment_in', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/payments/paymentinedit/" . $value->id ."/" . $value->brc_id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('payment_in', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/payments/paymentindelete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $value->invoice_no;
                $row[]     = $value->accounts_name;
                $row[]     = $value->c_name;
                $row[]     = $value->staff_name.' '.$value->staff_surname;
                $row[]     = $value->note;
                $row[]     = number_format($value->debit_amount, 2, '.', ',');
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

    public function getpaymentinlist($brc_id){
        $m               = $this->payments_model->getVoucherSearchDetails($brc_id,"","",1,"","","","");
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
                if ($this->rbac->hasPrivilege('payment_in', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/payments/paymentinedit/" . $value->id . "/". $value->brc_id  ."'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('payment_in', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/payments/paymentindelete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $value->invoice_no;
                $row[]     = $value->accounts_name;
                $row[]     = $value->c_name;
                $row[]     = $value->staff_name.' '.$value->staff_surname;
                $row[]     = $value->note;
                $row[]     = number_format($value->debit_amount, 2, '.', ',');
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
    
    public function paymentincreate($brc_id){
        if (!$this->rbac->hasPrivilege('payment_in', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'payments');
        $this->session->set_userdata('sub_menu', 'admin/payments/paymentin');
        $data['title']          = 'Add Payment In';
        $data['brc_id'] = $brc_id;
        $data["branchlist"]   = $this->branchsettings_model->get();
        $data["customerlist"] = $this->customers_model->getall($brc_id);
        $data["stafflist"]    = $this->staff_model->getStaffByBrach($brc_id);
        $data["acclist"]    = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/payments/paymentinCreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function printpaymentin(){
        if (!$this->rbac->hasPrivilege('payment_in', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('received_in_id', $this->lang->line('received').' In ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'payment_date'     => form_error('payment_date'),
                //'customer_id'    => form_error('customer_id'),
                'received_in_id'   => form_error('received_in_id'),
                'amount'      => form_error('amount'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))),
                'voucher_type_id'    => 1,
                'payment_type_id'    => $this->input->post('payment_from'),
                'invoice_no'         => $invoice_no,
                'invoice_type'       => 1,
                'staff_id'           => $this->input->post('staff_id'),
                'customer_id'        => $this->input->post('customer_id'),
                'acc_head_id'        => '',
                'profit_acc_head_id' => '',
                'par_acc_head_id'    => $this->input->post('received_in_id'),
                'debit_amount'       => $this->input->post('amount'),
                'credit_amount'      => $this->input->post('amount'),
                'note'               => $this->input->post('description'),
                'created_by'         => $user_id,
            );
            $inster_id = $this->payments_model->addaccountvoucher($data);
            $setting_result = $this->setting_model->getSetting($brc_id);
            $data['settinglist'] = $setting_result;
            $data['vouchertype'] = $this->input->post('voucher_type_id');
            $data['date']  = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date')));
            $data['invoice_no']  = $invoice_no;
            $data['name']  = $this->input->post('name');
            $data['voucherList'] = $this->payments_model->printVocherByInsertID($inster_id);
            $this->load->view('print/printVocher', $data);
            
        }
    }
    
    public function savepaymentin(){
        if (!$this->rbac->hasPrivilege('payment_in', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('received_in_id', $this->lang->line('received').' In ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'payment_date'     => form_error('payment_date'),
                //'customer_id'    => form_error('customer_id'),
                'received_in_id'   => form_error('received_in_id'),
                'amount'      => form_error('amount'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))),
                'voucher_type_id'    => 1,
                'payment_type_id'    => $this->input->post('payment_from'),
                'invoice_no'         => $invoice_no,
                'invoice_type'       => 1,
                'staff_id'           => $this->input->post('staff_id'),
                'customer_id'        => $this->input->post('customer_id'),
                'acc_head_id'        => '',
                'profit_acc_head_id' => '',
                'par_acc_head_id'    => $this->input->post('received_in_id'),
                'debit_amount'       => $this->input->post('amount'),
                'credit_amount'      => $this->input->post('amount'),
                'note'               => $this->input->post('description'),
                'created_by'         => $user_id,
            );
            $this->payments_model->addaccountvoucher($data);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }
    
    function paymentindelete($id,$brc_id){
        $this->payments_model->removepaymentin($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Record Deleted successfully</div>');
        redirect('admin/payments/paymentin/'.$brc_id);
    }

    function paymentout($brnc_id = null) {
        $this->session->set_userdata('top_menu', 'payments');
        $this->session->set_userdata('sub_menu', 'admin/payments/paymentout');
        $data["title"] = $this->lang->line('payment')." Out List";
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data["branchlist"]   = $this->branchsettings_model->get();
        $data["supplierlist"] = $this->supplier_model->getall($brc_id);
        $data["stafflist"]    = $this->staff_model->getStaffByBrach($brc_id);
        $data["acclist"]      = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data['searchlist']     = $this->customlib->get_searchtype();
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $this->load->view("layout/header");
        $this->load->view("admin/payments/paymentoutList", $data);
        $this->load->view("layout/footer");
    }
    
    public function searchpaymentout(){
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
            $accounts_id   = $this->input->post('accounts_id');
            $supplier_id   = $this->input->post('supplier_id');
            $staff_id      = $this->input->post('staff_id');
            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to,'accounts_id' =>$accounts_id,'supplier_id' =>$supplier_id,'staff_id' => $staff_id,'brc_id' => $brc_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchpaymentoutlist(){
        $search_type  = $this->input->post('search_type');
        $button_type  = $this->input->post('button_type');
        $accounts_id  = $this->input->post('accounts_id');
        $supplier_id  = $this->input->post('supplier_id');
        $staff_id     = $this->input->post('staff_id');
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
            $resultList        = $this->payments_model->getVoucherSearchDetails($brc_id,$date_from,$date_to,2,$accounts_id, "",$supplier_id,$staff_id);
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
                if ($this->rbac->hasPrivilege('payment_out', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/payments/paymentoutedit/" . $value->id ."/" . $value->brc_id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('payment_out', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/payments/paymentoutdelete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $value->invoice_no;
                $row[]     = $value->accounts_name;
                $row[]     = $value->s_name;
                $row[]     = $value->staff_name.' '.$value->staff_surname;
                $row[]     = $value->note;
                $row[]     = number_format($value->debit_amount, 2, '.', ',');
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

    public function getpaymentoutlist($brc_id){
        $m               = $this->payments_model->getVoucherSearchDetails($brc_id,"","",2,"","","","");
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
                if ($this->rbac->hasPrivilege('payment_out', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/payments/paymentoutedit/" . $value->id . "/". $value->brc_id  ."'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('payment_out', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/payments/paymentoutdelete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                $row       = array();
                $row[]     = $counts;
                $row[]     = date($this->customlib->getSystemDateFormat(), strtotime($value->date));
                $row[]     = $value->invoice_no;
                $row[]     = $value->accounts_name;
                $row[]     = $value->s_name;
                $row[]     = $value->staff_name.' '.$value->staff_surname;
                $row[]     = $value->note;
                $row[]     = number_format($value->debit_amount, 2, '.', ',');
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
    
    function paymentoutcreate($brc_id) {
        $this->session->set_userdata('top_menu', 'payments');
        $this->session->set_userdata('sub_menu', 'admin/payments/paymentout');
        $data["title"] = $this->lang->line('add')." ".$this->lang->line('payment').' Out';
        $data["branchlist"]   = $this->branchsettings_model->get();
        $data["supplierlist"] = $this->supplier_model->getall($brc_id);
        $data["stafflist"]    = $this->staff_model->getStaffByBrach($brc_id);
        $data["acclist"]      = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data["brc_id"]       = $brc_id;
        $this->load->view("layout/header");
        $this->load->view("admin/payments/paymentoutCreate", $data);
        $this->load->view("layout/footer");
    }
    
    public function printpaymentout(){
        if (!$this->rbac->hasPrivilege('payment_in', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('received_in_id', $this->lang->line('received').' In ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'payment_date'     => form_error('payment_date'),
                //'supplier_id'    => form_error('supplier_id'),
                'received_in_id'   => form_error('received_in_id'),
                'amount'      => form_error('amount'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))),
                'voucher_type_id'    => 2,
                'payment_type_id'    => $this->input->post('payment_to'),
                'invoice_no'         => $invoice_no,
                'invoice_type'       => 1,
                'staff_id'           => $this->input->post('staff_id'),
                'supplier_id'        => $this->input->post('supplier_id'),
                'acc_head_id'        => '',
                'profit_acc_head_id' => '',
                'par_acc_head_id'    => $this->input->post('received_in_id'),
                'debit_amount'       => $this->input->post('amount'),
                'credit_amount'      => $this->input->post('amount'),
                'note'               => $this->input->post('description'),
                'created_by'         => $user_id,
            );
            $inster_id = $this->payments_model->addaccountvoucher($data);
            $setting_result = $this->setting_model->getSetting($brc_id);
            $data['settinglist'] = $setting_result;
            $data['vouchertype'] = $this->input->post('voucher_type_id');
            $data['date']  = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date')));
            $data['invoice_no']  = $invoice_no;
            $data['name']  = $this->input->post('name');
            $data['voucherList'] = $this->payments_model->printVocherByInsertID($inster_id);
            print_r($data['voucherList']);
            exit;
            $this->load->view('print/printVocher', $data);
            
        }
    }
    
    public function savepaymentout(){
        if (!$this->rbac->hasPrivilege('payment_in', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('received_in_id', $this->lang->line('received').' In ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'payment_date'     => form_error('payment_date'),
                //'supplier_id'    => form_error('supplier_id'),
                'received_in_id'   => form_error('received_in_id'),
                'amount'           => form_error('amount'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))),
                'voucher_type_id'    => 2,
                'payment_type_id'    => $this->input->post('payment_to'),
                'invoice_no'         => $invoice_no,
                'invoice_type'       => 1,
                'staff_id'           => $this->input->post('staff_id'),
                'supplier_id'        => $this->input->post('supplier_id'),
                'acc_head_id'        => '',
                'profit_acc_head_id' => '',
                'par_acc_head_id'    => $this->input->post('received_in_id'),
                'debit_amount'       => $this->input->post('amount'),
                'credit_amount'      => $this->input->post('amount'),
                'note'               => $this->input->post('description'),
                'created_by'         => $user_id,
            );
            $this->payments_model->addaccountvoucher($data);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }
    
     public function search($brnc_id = null){
        if (!$this->rbac->hasPrivilege('accounts_voucher_search', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'accounting_records');
        $this->session->set_userdata('sub_menu', 'admin/account/search');
        $data['title']           = 'Student Search';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist']  = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        if(isset($_POST['search_type']) && $_POST['search_type']!=''){
            $dates=$this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type']=$_POST['search_type'];
        }else{
            $dates=$this->customlib->get_betweendate('this_year');
            $data['search_type']=''; 
        }
        $data['vno'] = '';
        $data['voucher_type_id'] = '';
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/payments/paymentsearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        if(!empty($this->input->post('brc_id'))){
                            $brc_id = $this->input->post('brc_id');
                        }else{
                            $brc_id = $this->customlib->getBranchID();
                        }
                        $data['searchby']           = "filter";
                        $start_date                 = date('Y-m-d',strtotime($dates['from_date']));
                        $end_date                   = date('Y-m-d',strtotime($dates['to_date']));
                        $data['start_date']         = $start_date;
                        $data['end_date']           = $end_date;
                        $data['brc_id']             = $brc_id;
                        $data['vno']                = "";
                        $voucher_type_id            = $this->input->post('voucher_type_id');
                        $data['voucher_type_id']    = $voucher_type_id;
                        $data['voucherlist']        = $this->payments_model->getVoucherSearchDetails($brc_id,$start_date,$end_date,$voucher_type_id,'');
                    }
                } else if ($search == 'search_full') {
                    $this->form_validation->set_rules('vno', 'Search By Voucher No', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "text";
                        if(!empty($this->input->post('brc_id'))){
                            $brc_id = $this->input->post('brc_id');
                        }else{
                            $brc_id = $this->customlib->getBranchID();
                        }
                        $data['voucher_type_id'] = '';
                        $data['brc_id']          = $brc_id;
                        $vno = $this->input->post('vno');
                        $data['vno'] = $vno;
                        $data['voucherlist'] = $this->payments_model->getVoucherSearchDetails($brc_id,'','','',$vno);
                    }
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/payments/paymentsearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }
    
    function paymentinedit($id,$brc_id) {
        $this->session->set_userdata('top_menu', 'payments');
        $this->session->set_userdata('sub_menu', 'admin/payments/paymentinedit');
        $data["title"] = $this->lang->line('edit')." ".$this->lang->line('payment').' In';
        $data["branchlist"]   = $this->branchsettings_model->get();
        $data["customerlist"] = $this->customers_model->getall($brc_id);
        $data["stafflist"]    = $this->staff_model->getStaffByBrach($brc_id);
        $data["acclist"]      = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data["brc_id"]       = $brc_id;
        $data["id"]           = $id;
        $voucher_result = $this->payments_model->getVoucherDetails($id,$brc_id);
        $data['vouchers'] = $voucher_result;
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('customer_id', $this->lang->line('customer'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('received_in_id', $this->lang->line('received').' In ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if($this->form_validation->run() == FALSE) {
            $this->load->view("layout/header");
            $this->load->view("admin/payments/paymentinEdit", $data);
            $this->load->view("layout/footer");
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $userlist  = $this->customlib->getUserData();
            $user_id   = $userlist["id"];
            $data = array(
                'id'                 => $id,
                'brc_id'             => $brc_id,
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))),
                'payment_type_id'    => $this->input->post('payment_from'),
                'staff_id'           => $this->input->post('staff_id'),
                'customer_id'        => $this->input->post('customer_id'),
                'acc_head_id'        => '',
                'profit_acc_head_id' => '',
                'par_acc_head_id'    => $this->input->post('received_in_id'),
                'debit_amount'       => $this->input->post('amount'),
                'credit_amount'      => $this->input->post('amount'),
                'note'               => $this->input->post('description'),
                'updated_by'         => $user_id,
            );
            $this->payments_model->addaccountvoucher($data);
            $this->session->set_flashdata('msg', $this->lang->line('update_message'));
            redirect('admin/payments/paymentin/'.$brc_id);
        }
        
    }
    
    function paymentoutedit($id,$brc_id) {
        $this->session->set_userdata('top_menu', 'payments');
        $this->session->set_userdata('sub_menu', 'admin/payments/paymentoutedit');
        $data["title"] = $this->lang->line('edit')." ".$this->lang->line('payment').' Out';
        $data["branchlist"]   = $this->branchsettings_model->get();
        $data["supplierlist"] = $this->supplier_model->getall($brc_id);
        $data["stafflist"]    = $this->staff_model->getStaffByBrach($brc_id);
        $data["acclist"]      = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data["brc_id"]       = $brc_id;
        $data["id"]           = $id;
        $voucher_result = $this->payments_model->getVoucherDetails($id,$brc_id);
        $data['vouchers'] = $voucher_result;
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('received_in_id', $this->lang->line('received').' In ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if($this->form_validation->run() == FALSE) {
            $this->load->view("layout/header");
            $this->load->view("admin/payments/paymentoutEdit", $data);
            $this->load->view("layout/footer");
        } else {
           if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $userlist  = $this->customlib->getUserData();
            $user_id   = $userlist["id"];
            $data = array(
                'id'                 => $id,
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))),
                'payment_type_id'    => $this->input->post('payment_to'),
                'staff_id'           => $this->input->post('staff_id'),
                'supplier_id'        => $this->input->post('supplier_id'),
                'acc_head_id'        => '',
                'profit_acc_head_id' => '',
                'par_acc_head_id'    => $this->input->post('received_in_id'),
                'debit_amount'       => $this->input->post('amount'),
                'credit_amount'      => $this->input->post('amount'),
                'note'               => $this->input->post('description'),
                'updated_by'         => $user_id,
            ); 
            $this->payments_model->addaccountvoucher($data);
            $this->session->set_flashdata('msg', $this->lang->line('update_message'));
            redirect('admin/payments/paymentout/'.$brc_id);
        }
    }
    
    public function getaccountheadjv(){
        if(!empty($this->input->post('brc_id'))){
            $brc_id = $this->input->post('brc_id');
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $supplierlist['Suppliers'] = $this->supplier_model->getall($brc_id);
        $customerlist['Customers'] = $this->customers_model->getall($brc_id);
        $stafflist['Employee']    = $this->staff_model->getAlljv($brc_id);
        $acclist['Accounts']      = $this->accounts_model->getaccountheadall($brc_id);
        $aclist =  array_merge($acclist,$customerlist,$supplierlist,$stafflist);
        echo json_encode($aclist);
    }
    
    function journalvoucher($brnc_id = null) {
        $this->session->set_userdata('top_menu', 'payments');
        $this->session->set_userdata('sub_menu', 'admin/payments/journalvoucher');
        $data["title"] = $this->lang->line('add')." ".$this->lang->line('journal_voucher');
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data["branchlist"]   = $this->branchsettings_model->get();
        $supplierlist['Suppliers'] = $this->supplier_model->getall($brc_id);
        $customerlist['Customers'] = $this->customers_model->getall($brc_id);
        $stafflist['Employee']    = $this->staff_model->getAlljv($brc_id);
        $acclist['Accounts']      = $this->accounts_model->getaccountheadall($brc_id);
        $data['aclist'] =  array_merge($acclist,$customerlist,$supplierlist,$stafflist);
        $this->load->view("layout/header");
        $this->load->view("admin/payments/journalvoucher", $data);
        $this->load->view("layout/footer");
    }
    
    public function savejournalvoucher(){
        if (!$this->rbac->hasPrivilege('journal_voucher', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('journalvoucher_date', $this->lang->line('journal_voucher').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_head[]', $this->lang->line('account').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'voucher_date'     => form_error('voucher_date'),
                'account_head[]'  => form_error('account_head[]'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
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
            $accounthead   = $this->input->post('account_head');
            $debitamount   = $this->input->post('debit_amount');
            $creditamount  = $this->input->post('credit_amount');
            $acctypelist = [];
            if(!empty($accounthead)){
                foreach ($accounthead as $accounthead_key => $accounthead_val) {
                        $str = substr($accounthead_val, 0, -1);
                        $acctypelist[$str][] = intval(preg_replace('/[^0-9]+/', '', $accounthead_val), 10);
                }
            }
            if(!empty($acctypelist)){
                foreach ($acctypelist as $actype_key => $actype_val) {
                    if($actype_key == 'Accounts'){
                        foreach ($actype_val as $tkey =>  $tval){
                            $acc_data = array(
                                'brc_id'             => $brc_id,
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('journalvoucher_date'))),
                                'voucher_type_id'    => 5,
                                'payment_type_id'    => $this->input->post('journalvoucherfrom'),
                                'invoice_no'         => $invoice_no,
                                'invoice_type'       => 1,
                                'acc_head_id'        => $tval,
                                'profit_acc_head_id' => '',
                                'par_acc_head_id'    => $tval,
                                'debit_amount'       => $debitamount[$tkey],
                                'credit_amount'      => $creditamount[$tkey],
                                'note'               => $this->input->post('description'),
                                'created_by'         => $user_id,
                            );
                            $this->payments_model->addaccountvoucher($acc_data);
                        }
                    }elseif ($actype_key == 'Customers') {
                        foreach ($actype_val as $tkey =>  $tval){
                            $cu_data = array(
                                'brc_id'             => $brc_id,
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('journalvoucher_date'))),
                                'voucher_type_id'    => 5,
                                'payment_type_id'    => $this->input->post('journalvoucherfrom'),
                                'invoice_no'         => $invoice_no,
                                'invoice_type'       => 1,
                                'customer_id'        => $tval,
                                'acc_head_id'        => '',
                                'profit_acc_head_id' => '',
                                'par_acc_head_id'    => '',
                                'debit_amount'       => $debitamount[$tkey],
                                'credit_amount'      => $creditamount[$tkey],
                                'note'               => $this->input->post('description'),
                                'created_by'         => $user_id,
                            );
                            $this->payments_model->addaccountvoucher($cu_data);
                        }
                    }elseif ($actype_key == 'Suppliers') {
                        foreach ($actype_val as $tkey =>  $tval){
                            $supp_data = array(
                                'brc_id'             => $brc_id,
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('journalvoucher_date'))),
                                'voucher_type_id'    => 5,
                                'payment_type_id'    => $this->input->post('journalvoucherfrom'),
                                'invoice_no'         => $invoice_no,
                                'invoice_type'       => 1,
                                'supplier_id'        => $tval,
                                'acc_head_id'        => '',
                                'profit_acc_head_id' => '',
                                'par_acc_head_id'    => '',
                                'debit_amount'       => $debitamount[$tkey],
                                'credit_amount'      => $creditamount[$tkey],
                                'note'               => $this->input->post('description'),
                                'created_by'         => $user_id,
                            );
                            $this->payments_model->addaccountvoucher($supp_data);
                        }
                    }elseif ($actype_key == 'Employee') {
                        foreach ($actype_val as $tkey =>  $tval){
                            $ep_data = array(
                                'brc_id'             => $brc_id,
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('journalvoucher_date'))),
                                'voucher_type_id'    => 5,
                                'payment_type_id'    => $this->input->post('journalvoucherfrom'),
                                'invoice_no'         => $invoice_no,
                                'invoice_type'       => 1,
                                'staff_id'           => $tval,
                                'acc_head_id'        => '',
                                'profit_acc_head_id' => '',
                                'par_acc_head_id'    => '',
                                'debit_amount'       => $debitamount[$tkey],
                                'credit_amount'      => $creditamount[$tkey],
                                'note'               => $this->input->post('description'),
                                'created_by'         => $user_id,
                            );
                            $this->payments_model->addaccountvoucher($ep_data);
                        }
                    }
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }
    
    function paymentoutdelete($id,$brc_id){
        $this->payments_model->removepaymentout($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Record Deleted successfully</div>');
        redirect('admin/payments/paymentout/'.$brc_id);
    }

}

?>