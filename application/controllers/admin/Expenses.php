<?php
class Expenses extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($brnc_id = null) {
        $this->session->set_userdata('top_menu', 'expenses');
        $this->session->set_userdata('sub_menu', 'admin/expenses/index');
        $data["title"] = $this->lang->line('add')." ".$this->lang->line('expense_bill');
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data["supplierlist"]   = $this->supplier_model->getall($brc_id);
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data["accheadlist"]    = $this->accounts_model->getAccountsByID($brc_id,5);
        $data['searchlist']     = $this->customlib->get_searchtype();
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $this->load->view("layout/header");
        $this->load->view("admin/expenses/expenseList", $data);
        $this->load->view("layout/footer");
    }
    
    public function searchexpenses(){
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
            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to,'accounts_id' =>$accounts_id,'supplier_id' =>$supplier_id,'brc_id' => $brc_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchexpenseslist(){
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $accounts_id  = $this->input->post('accounts_id');
        $supplier_id = $this->input->post('supplier_id');
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
            $resultList        = $this->expenses_model->getVoucherSearchDetails($brc_id,$date_from,$date_to,$accounts_id, "", "");
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
                if ($this->rbac->hasPrivilege('sales', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/expenses/edit/" . $value->id ."/" . $value->brc_id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('sales', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/expenses/delete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
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
                $row[]     = $value->note;
                $row[]     = number_format($value->grand_total, 2, '.', ',');
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
        $result = $this->sales_model->get($id);
        $this->media_storage->filedownload($result['attachment'], "./uploads/purchases");
    }

    public function getexpenseslist($brc_id){
        $m               = $this->expenses_model->getVoucherSearchDetails($brc_id,"","","","","");
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
                if ($this->rbac->hasPrivilege('expenses', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/expenses/edit/" . $value->id . "/". $value->brc_id  ."'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('expenses', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/expenses/delete/" . $value->id . "/". $value->brc_id  ."' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
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
                $row[]     = $value->note;
                $row[]     = number_format($value->grand_total, 2, '.', ',');
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
        if (!$this->rbac->hasPrivilege('expenses', 'can_add')) {
            access_denied();
        }
        $data['title']          = 'Add Expense';
        $data['brc_id']         = $brc_id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data["supplierlist"]   = $this->supplier_model->getall($brc_id);
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data["accheadlist"]    = $this->accounts_model->getAccountsByID($brc_id,5);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expenses/expenseCreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function printexpense(){
        if (!$this->rbac->hasPrivilege('expenses', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('expense_date', $this->lang->line('expense').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bill_type', $this->lang->line('bill_type'), 'trim|required|xss_clean');
        if(!empty($this->input->post('payment_mode'))){
            $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        }else {
            $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('account_head[]', $this->lang->line('accounts').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('debit_amount[]', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'expense_date'     => form_error('expense_date'),
                //'customer_id'    => form_error('customer_id'),
                'account_head[]'   => form_error('account_head[]'),
                'debit_amount[]'   => form_error('debit_amount[]'),
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
            $userlist     = $this->customlib->getUserData();
            $user_id      = $userlist["id"];
            $inster_id    = array();
            $userlist  = $this->customlib->getUserData();
            $user_id   = $userlist["id"];
            $data = array(
                'brc_id'             => $brc_id,
                'voucher_type'       => 3,
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('expense_date'))),
                'voucher_type_id'    => 2,
                'bill_type'          => $this->input->post('bill_type'),
                'invoice_no'         => $invoice_no,
                'invoice_type'       => 3,
                'par_acc_head_id'    => $this->input->post('payment_mode'),
                'supplier_id'        => $this->input->post('supplier_id'),
                'grand_total'        => $this->input->post('grand_total'),
                'note'               => $this->input->post('description'),
                'created_by'         => $user_id,
            );
            $exp_id = $this->expenses_model->addexpensebill($data);
            $acchead      = $this->input->post('account_head');
            $debit_amount = $this->input->post('debit_amount');
            if(!empty($exp_id)){
                if(!empty($acchead)){
                    foreach ($acchead as $acckey => $accval){
                        $data = array(
                            'expenses_bill_id'   => $exp_id,
                            'acc_head_id'        => $accval,
                            'debit_amount'       => $debit_amount[$acckey],
                            'credit_amount'      => $debit_amount[$acckey],
                            'created_by'         => $user_id,
                        );
                        $this->expenses_model->addexpensebillitems($data);
                    }
                }
            }
            
            $setting_result = $this->setting_model->getSetting($brc_id);
            $data['settinglist'] = $setting_result;
            $data['vouchertype'] = $this->input->post('bill_type');
            $data['date']  = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('expense_date')));
            $data['invoice_no']  = $invoice_no;
            $data['invoice_no']  = $invoice_no;
            $data['name']  = $this->input->post('name');
            $data['note']  = $this->input->post('description');
            $data['voucherList'] = $this->expenses_model->printExpenseBillByInsertID($exp_id);
            $this->load->view('admin/expenses/printexpBill', $data);
            
        }
    }
    
    public function saveexpense(){
        if (!$this->rbac->hasPrivilege('expenses', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('expense_date', $this->lang->line('expense').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bill_type', $this->lang->line('bill_type'), 'trim|required|xss_clean');
        if(!empty($this->input->post('payment_mode'))){
            $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        }else {
            $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('account_head[]', $this->lang->line('accounts').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('debit_amount[]', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'expense_date'     => form_error('expense_date'),
                //'customer_id'    => form_error('customer_id'),
                'account_head[]'   => form_error('account_head[]'),
                'debit_amount[]'   => form_error('debit_amount[]'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $lastinviice = $this->expenses_model->lastInvoiceNoRecord($brc_id,3);
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
                'voucher_type'       => 3,
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('expense_date'))),
                'voucher_type_id'    => 2,
                'bill_type'          => $this->input->post('bill_type'),
                'invoice_no'         => $invoice_no,
                'invoice_type'       => 3,
                'par_acc_head_id'    => $this->input->post('payment_mode'),
                'supplier_id'        => $this->input->post('supplier_id'),
                'grand_total'        => $this->input->post('grand_total'),
                'note'               => $this->input->post('description'),
                'created_by'         => $user_id,
            );
            $exp_id = $this->expenses_model->addexpensebill($data);
            $acchead      = $this->input->post('account_head');
            $debit_amount = $this->input->post('debit_amount');
            if(!empty($exp_id)){
                if(!empty($acchead)){
                    foreach ($acchead as $acckey => $accval){
                        $data = array(
                            'expenses_bill_id'   => $exp_id,
                            'acc_head_id'        => $accval,
                            'debit_amount'       => $debit_amount[$acckey],
                            'credit_amount'      => $debit_amount[$acckey],
                            'created_by'         => $user_id,
                        );
                        $this->expenses_model->addexpensebillitems($data);
                    }
                }
            }
            
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }
    
    public function edit($id,$brc_id){
        if (!$this->rbac->hasPrivilege('expenses', 'can_edit')) {
            access_denied();
        }
        $data['title']          = 'Edit Expenses';
        $data['id']             = $id;
        $data['brc_id']         = $brc_id;
        $data['brc_id']         = $brc_id;
        $data['branchlist']     = $this->branchsettings_model->get();
        $data["supplierlist"]   = $this->supplier_model->getall($brc_id);
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,$brc_id);
        $data["accheadlist"]    = $this->accounts_model->getAccountsByID($brc_id,5);
        $data['expense']        = $this->expenses_model->get($id);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expenses/expenseEdit', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function expensesUpdateSave(){
        if (!$this->rbac->hasPrivilege('expenses', 'can_add')) {
            access_denied();
        }
        $auto_staff_id = false;
        $this->form_validation->set_rules('expense_date', $this->lang->line('expense').' '.$this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bill_type', $this->lang->line('bill_type'), 'trim|required|xss_clean');
        if(!empty($this->input->post('payment_mode'))){
            $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        }else {
            $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('account_head[]', $this->lang->line('accounts').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('debit_amount[]', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'expense_date'     => form_error('expense_date'),
                //'customer_id'    => form_error('customer_id'),
                'account_head[]'   => form_error('account_head[]'),
                'debit_amount[]'   => form_error('debit_amount[]'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $id = $this->input->post('expid');
            $userlist  = $this->customlib->getUserData();
            $user_id   = $userlist["id"];
            $data = array(
                'id'                 => $id,
                'brc_id'             => $brc_id,
                'voucher_type'       => 3,
                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('expense_date'))),
                'voucher_type_id'    => 2,
                'bill_type'          => $this->input->post('bill_type'),
                'par_acc_head_id'    => $this->input->post('payment_mode'),
                'supplier_id'        => $this->input->post('supplier_id'),
                'grand_total'        => $this->input->post('grand_total'),
                'note'               => $this->input->post('description'),
                'updated_by'         => $user_id,
            );
            $this->expenses_model->addexpensebill($data);
            $previous_id    = $this->input->post('previous_id');
            $carts_id       = $this->input->post('carts_id');
            $delete_result  = array_diff($previous_id, $carts_id);
            if (!empty($delete_result)) {
                $d_delete_array = array();
                foreach ($delete_result as $d_delete_key => $d_delete_value) {
                    $d_delete_array[] = $d_delete_value;
                }
                $this->expenses_model->remove_expense_item($d_delete_array);

            }
            $acchead      = $this->input->post('account_head');
            $debit_amount = $this->input->post('debit_amount');
            if(!empty($id)){
                if(!empty($acchead)){
                    foreach ($acchead as $acckey => $accval){
                        $update_id = $this->input->post('update_id_'.$acckey);
                        if(!empty($update_id)){
                            $dataup = array(
                                'id'                 => $update_id,
                                'expenses_bill_id'   => $id,
                                'acc_head_id'        => $accval,
                                'debit_amount'       => $debit_amount[$acckey],
                                'credit_amount'      => $debit_amount[$acckey],
                                'updated_by'         => $user_id,
                            );
                            $this->expenses_model->addexpensebillitems($dataup);
                        }else{    
                            $data = array(
                                'expenses_bill_id'   => $id,
                                'acc_head_id'        => $accval,
                                'debit_amount'       => $debit_amount[$acckey],
                                'credit_amount'      => $debit_amount[$acckey],
                                'created_by'         => $user_id,
                            );
                            $this->expenses_model->addexpensebillitems($data);
                        }
                    }
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }
    
    public function delete($id,$brc_id){
        if (!$this->rbac->hasPrivilege('expenses', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Products List';
        $this->expenses_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/expenses/index/'.$brc_id);
    }

}

?>