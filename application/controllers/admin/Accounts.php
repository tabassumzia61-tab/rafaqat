<?php
class Accounts extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index($brnc_id = null) {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/index');
        $data['title'] = 'Add Chart of Accounts';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/chart_of_accounts_list', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function accountstype() {
        $this->session->set_userdata('top_menu', 'accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/accountstype');
        $data['title'] = 'Add New Accounts';
        $data["accounts_type_id"] = "";
        $data["name"] = "";
        $data["description"] = "";
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
        $data["resultlist"] = $this->accounts_model->getnewaccount();
        $data["resultacclist"] = $this->accounts_model->getaccountsBytypeID();
        //pr($data["resultacclist"]);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/accountstype', $data);
        $this->load->view('layout/footer', $data);
    }

    function accountstypecreate() {
        if (!$this->rbac->hasPrivilege('new_accounts', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add New Accounts';
        $data["accounts_type_id"] = "";
        $data["name"] = "";
        $data["description"] = "";
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
        $data["resultlist"] = $this->accounts_model->getnewaccount();
        $data["resultacclist"] = $this->accounts_model->getaccountsBytypeID();
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('new_check_exists', array($this->accounts_model, 'new_check_exists'))
                )
        );
        $this->form_validation->set_rules('accounts_type_id', $this->lang->line('account_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/accounts/accountstype', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $acc_tot = $this->accounts_model->getTotalnewaccount($this->input->post('accounts_type_id'));
            if(!empty($acc_tot)){
                $code = $this->input->post('accounts_type_id').$acc_tot->total_code_no + 1;
            }else{
                $code = 1;
            }
            $data = array(
                'accounts_type_id' => $this->input->post('accounts_type_id'),
                'code' => $code,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('description'),
            );
            $this->accounts_model->addnewaccounts($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">New Accounts added successfully</div>');
            redirect('admin/accounts/accountstype');
        }
    }

    function accountstypeedit($id = null) {
        if (!$this->rbac->hasPrivilege('new_accounts', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit New Accounts';
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
        $data["resultlist"] = $this->accounts_model->getnewaccount();
        $data["resultacclist"] = $this->accounts_model->getaccountsBytypeID();
        $data["id"] = $id;
        $result = $this->accounts_model->getnewaccount($id);
        $data["result"] = $result;
        $data["accounts_type_id"] = $result["accounts_type_id"];
        $data["name"] = $result["name"];
        $data["description"] = $result["note"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('new_check_exists', array($this->accounts_model, 'new_check_exists'))
                )
        );
        $this->form_validation->set_rules('accounts_type_id', $this->lang->line('account_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/accounts/accountstype', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'accounts_type_id' => $this->input->post('accounts_type_id'),
                'name' => $this->input->post('name'),
                'note' => $this->input->post('description'),
            );
            $this->accounts_model->addnewaccounts($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">New Accounts updated successfully</div>');
            redirect('admin/accounts/accountstype');
        }
    }
    
    public function accountshead($brnc_id = null) {
        $this->session->set_userdata('top_menu', 'accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/accountshead');
        $data['title'] = 'Add Accounts Head';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $data["accounts_head_id"] = "";
        $data["account_type_id"] = "";
        $data["name"] = "";
        $data["date"] = "";
        $data["amount"] = "";
        $data["description"] = "";
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
        $data["resultacclist"] = $this->accounts_model->getaccountsBytypeID();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/accountshead', $data);
        $this->load->view('layout/footer', $data);
    }

    function accountsheadcreate() {
        if (!$this->rbac->hasPrivilege('accounts_head', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Accounts head';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $data["accounts_head_id"] = "";
        $data["account_type_id"] = "";
        $data["name"] = "";
        $data["date"] = "";
        $data["amount"] = "";
        $data["description"] = "";
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
        $data["resultacclist"] = $this->accounts_model->getaccountsBytypeID();
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('head_check_exists', array($this->accounts_model, 'head_check_exists'))
                )
        );
        $this->form_validation->set_rules('accounts_head_id', $this->lang->line('account').' '.$this->lang->line('head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_type_id', $this->lang->line('account').' '.$this->lang->line('type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/accounts/accountshead', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $resultnew = $this->accounts_model->getnewaccount($this->input->post('account_type_id'));
            if(!empty($resultnew)){
                $acc_tot = $this->accounts_model->getTotalaccounthead($this->input->post('account_type_id'));
                if(!empty($acc_tot)){
                    $code = $resultnew['code'].($acc_tot->total_code_no + 1 );
                }else{
                    $code = $resultnew['code'].'1';
                }
                $data = array(
                    'accounts_head_id' => $this->input->post('accounts_head_id'),
                    'new_accounts_id' => $this->input->post('account_type_id'),
                    'code' => $code,
                    'brc_id' => $brc_id,
                    'name' => $this->input->post('name'),
                    'note' => $this->input->post('description'),
                    'is_active' => 'yes',
                );
                $insert_id = $this->accounts_model->addaccountshead($data);
                if(!empty($insert_id)){
                    if(!empty($this->input->post('opening_balance_amount'))){
                        $accounttype = $this->input->post('accounts_head_id');
                        if($accounttype == 1){
                            $dataob = array(
                                'brc_id' => $brc_id,
                                'par_acc_head_id' => 21,
                                'acc_head_id' => $insert_id,
                                'date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                                'debit_amount' => $this->input->post('opening_balance_amount'),
                                'credit_amount' => 0,
                                'note' => $this->input->post('description'),
                            );
                            $this->accounts_model->addopeningbalance($dataob);    
                        }else{
                            $dataob = array(
                                'brc_id' => $brc_id,
                                'par_acc_head_id' => 21,
                                'acc_head_id' => $insert_id,
                                'date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                                'debit_amount' => 0,
                                'credit_amount' => $this->input->post('opening_balance_amount'),
                                'note' => $this->input->post('description'),
                            );
                            $this->accounts_model->addopeningbalance($dataob);    
                        }
                        
                    }
                }
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Accounts Head added successfully</div>');
                redirect('admin/accounts/accountshead/'.$brc_id);
            }else{
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Please Create Sub Accounts</div>');
                redirect('admin/accounts/accountshead/'.$brc_id);
            }
        }
    }
    
    

    function accountsheadedit($id = null) {
        if (!$this->rbac->hasPrivilege('accounts_head', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Accounts Head';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
        $data["resultacclist"] = $this->accounts_model->getaccountsBytypeID();
        $oblist = $this->accounts_model->getob($brc_id,$id);
        $data['oblist'] = $oblist; 
        $data["id"] = $id;
        $result = $this->accounts_model->getaccounthead($id);
        $data["result"] = $result;
        $data["accounts_head_id"] = $result["accounts_head_id"];
        $data["account_type_id"] = $result["new_accounts_id"];
        $data["name"] = $result["name"];
        $data["date"] = '';
        if(!empty($oblist["date"])){
            $data["date"] = $oblist["date"];    
        }
        $data["amount"] = '';
        if(!empty($oblist["date"])){
            $data["amount"] = $oblist["debit_amount"];
        }
        $data["description"] = $result["note"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('head_check_exists', array($this->accounts_model, 'head_check_exists'))
                )
        );
        $this->form_validation->set_rules('accounts_head_id', $this->lang->line('account').' '.$this->lang->line('head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_type_id', $this->lang->line('account').' '.$this->lang->line('type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/accounts/accountshead', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $data = array(
                'id' => $id,
                'accounts_head_id' => $this->input->post('accounts_head_id'),
                'new_accounts_id' => $this->input->post('account_type_id'),
                //'brc_id' => $brc_id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('description'),
            );
            $this->accounts_model->addaccountshead($data);
            if(!empty($id)){
                if(!empty($this->input->post('opening_balance_amount'))){
                    $accounttype = $this->input->post('accounts_head_id');
                    if(!empty($oblist)){
                        if($accounttype == 1){
                            $dataob = array(
                                'id' => $oblist['id'],
                                'brc_id' => $brc_id,
                                'par_acc_head_id' => 21,
                                'acc_head_id' => $id,
                                'date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                                'debit_amount' => $this->input->post('opening_balance_amount'),
                                'credit_amount' => 0,
                                'note' => $this->input->post('description'),
                            );
                            $this->accounts_model->addopeningbalance($dataob);
                        }else{
                            $dataob = array(
                                'id' => $oblist['id'],
                                'brc_id' => $brc_id,
                                'par_acc_head_id' => 21,
                                'acc_head_id' => $id,
                                'date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                                'debit_amount' => 0,
                                'credit_amount' => $this->input->post('opening_balance_amount'),
                                'note' => $this->input->post('description'),
                            );
                            $this->accounts_model->addopeningbalance($dataob);
                        }
                    }else{
                        if($accounttype == 1){
                            $dataob = array(
                                'brc_id' => $brc_id,
                                'par_acc_head_id' => 21,
                                'acc_head_id' => $id,
                                'date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                                'debit_amount' => $this->input->post('opening_balance_amount'),
                                'credit_amount' => 0,
                                'note' => $this->input->post('description'),
                            );
                            $this->accounts_model->addopeningbalance($dataob);    
                        }else{
                            $dataob = array(
                                'brc_id' => $brc_id,
                                'par_acc_head_id' => 21,
                                'acc_head_id' => $id,
                                'date' =>  date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                                'debit_amount' => 0,
                                'credit_amount' => $this->input->post('opening_balance_amount'),
                                'note' => $this->input->post('description'),
                            );
                            $this->accounts_model->addopeningbalance($dataob);    
                        }
                    }
                }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Accounts Head updated successfully</div>');
            redirect('admin/accounts/accountshead');
        }
    }
    
    function getBynewaccounts() {
        $accounts_head_id = $this->input->get('accounts_head_id');
        $data = $this->accounts_model->getnewaccountsByID($accounts_head_id);
        echo json_encode($data);
    }
    
    function getAccountsNameByID() {
        if(!empty($this->input->get('brc_id'))){
            $brc_id = $this->input->get('brc_id');
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $accounts_head_id = $this->input->get('accounts_head_id');
        $account_type_id = $this->input->get('account_type_id');
        if($account_type_id == 1){
            $data = $this->customers_model->getall($brc_id);
        }else if($account_type_id == 5){
            $data = $this->products_model->get();
        }else if($account_type_id == 6){
            $data = $this->supplier_model->getall($brc_id);
        }else if($account_type_id == 12){
            $data = $this->accounts_model->getAccountsItemByID($brc_id,$accounts_head_id,$account_type_id);
        }else if($account_type_id == 13){
            $data = $this->accounts_model->getAccountsItemByID($brc_id,$accounts_head_id,$account_type_id);
        }else if($account_type_id == 15){
            $data = $this->accounts_model->getAccountsItemByID($brc_id,$accounts_head_id,$account_type_id);
        }else if($account_type_id == 16){
            $data = $this->accounts_model->getAccountsItemByID($brc_id,$accounts_head_id,$account_type_id);
        }else if($account_type_id == 17){
            $data = $this->accounts_model->getAccountsItemByID($brc_id,$accounts_head_id,$account_type_id);
        }else{
            $data = $this->accounts_model->getAccountsNameByID($brc_id,$accounts_head_id,$account_type_id);    
        }
        echo json_encode($data);
    }
    
    public function changestatuspost(){
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $id = $this->input->post('id');
            $acdetail = $this->accounts_model->getaccounthead($id);
            if(!empty($acdetail['is_posted'])){
                $data = array(
                    'id'    => $this->input->post('id'),
                    'is_posted' => null,
                );
                $this->accounts_model->addaccountshead($data);
            }else{
                $data = array(
                    'id'    => $this->input->post('id'),
                    'is_posted' => 1,
                );
                $this->accounts_model->addaccountshead($data);
            }
            $array                                     = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
    
    public function changestatus(){
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $id = $this->input->post('id');
            $acdetail = $this->accounts_model->getaccounthead($id);
            if($acdetail['is_active'] == 'yes'){
                $data = array(
                    'id'    => $this->input->post('id'),
                    'is_active' => 'no',
                );
                $this->accounts_model->addaccountshead($data);
            }else{
                $data = array(
                    'id'    => $this->input->post('id'),
                    'is_active' => 'yes',
                );
                $this->accounts_model->addaccountshead($data);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    function getvoucheraccountmode() {
        $voucher_type_id = $this->input->post('voucher_type_id');
        if(!empty($this->input->post('brc_id'))){
            $brc_id = $this->input->post('brc_id');
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        if($voucher_type_id == 1){
            $accounts_head_id = 2;
        }else if($voucher_type_id == 2){
            $accounts_head_id = 2;
        }else if($voucher_type_id == 3){
            $accounts_head_id = 2;
        }else if($voucher_type_id == 4){
            $accounts_head_id = 2;
        }
        $data = $this->accounts_model->getAccountsModeByHeadID($accounts_head_id,$brc_id);
        echo json_encode($data);
    }
    
    public function generalreports()    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/accounts/generalreports');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('admin/accounts/reports/generalreports');
        $this->load->view('layout/footer');
    }
    
    function generalledger($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('general_ledger', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/accounts/generalreports');
        $this->session->set_userdata('subsub_menu', 'Reports/accounts/generalledger');
        $data['title'] = 'General Ledger';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id']      = $brc_id;
        $data['accounts_head_id'] = '';
        $data['accounts_type_id'] = '';
        $data['account_name_id'] = '';
        $data['branchlist']  = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data["accountstypelist"] = $this->accounts_model->getaccountstype();
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
        $this->form_validation->set_rules('accounts_head_id', $this->lang->line('account').' '.$this->lang->line('head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_type_id', $this->lang->line('account').' '.$this->lang->line('type'), 'trim|required|xss_clean');
        if(!empty($this->input->post('account_name_id'))){
            $this->form_validation->set_rules('account_name_id', $this->lang->line('account').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        }else{
            
        }
        if ($this->form_validation->run() == false) {
        }else{
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $accounts_head_id = $this->input->post('accounts_head_id');
            $accounts_type_id = $this->input->post('account_type_id');
            $account_name_id  = $this->input->post('account_name_id');
            $start_date       = date('Y-m-d',strtotime($dates['from_date']));
            $end_date         = date('Y-m-d',strtotime($dates['to_date']));
            $data['start_date'] = $start_date;
            $data['end_date']   = $end_date;
            $data['accounts_head_id']       = $accounts_head_id;
            $data['accounts_type_id']       = $accounts_type_id;
            $data['account_name_id']        = $account_name_id;
            $data['aclist']                 = $this->accounts_model->getaccounthead($account_name_id);
            $invtryob                       = $this->accounts_model->getPurchaseByItemObID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $superob                        = $this->accounts_model->getPurchaseBySuppliersObID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purgstob                       = $this->accounts_model->getPurchaseByGstObID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purgstob                       = $this->accounts_model->getPurchasesPaymentsByParentsObID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $data['openbalance']            = array_merge($invtryob,$superob,$purgstob);
            /* Purchases Accounts  */
            $purchaselist                   = $this->accounts_model->getPurchaseByPurchasessID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchaseitemlist               = $this->accounts_model->getPurchaseByItemID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchasecostlist               = $this->accounts_model->getPurchaseByCostID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchasegstlist                = $this->accounts_model->getPurchaseByGstID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchasediscountlist           = $this->accounts_model->getPurchaseByDiscountID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchasedispervlist            = $this->accounts_model->getPurchaseByDispervID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchasesuplist                = $this->accounts_model->getPurchaseBySuppliersID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchasespaymentslist          = $this->accounts_model->getPurchasesPaymentsByParentsID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            /* Purchases Return Accounts  */
            $purchaserntlist                = [];//$this->accounts_model->getPurchaseRntByPurchasessID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchaserntitemlist            = [];//$this->accounts_model->getPurchaseRntByItemID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchaserntcostlist            = [];//$this->accounts_model->getPurchaseRntByCostID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchaserntgstlist             = [];//$this->accounts_model->getPurchaseRntByGstID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchaserntdiscountlist        = [];//$this->accounts_model->getPurchaseRntByDiscountID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchaserntsuplist             = [];//$this->accounts_model->getPurchaseRntBySuppliersID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $purchaserntpaymentslist        = [];//$this->accounts_model->getPurchasesRntPaymentsByParentsID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            /* Expenses Accounts  */
            $parentexpensesacclist          = $this->accounts_model->getParentExpensesAccounntsByID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $expensesacclist                = $this->accounts_model->getExpensesAccounntsByID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            /* Expenses Supplier Accounts  */
            $parentsupexpensesacclist       = $this->accounts_model->getParentSupplierExpensesAccounntsByID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $supexpensesacclist             = $this->accounts_model->getSupplierExpensesAccounntsByID($brc_id,$accounts_head_id,$accounts_type_id,$account_name_id,$start_date,$end_date);
            $data['genledgerlist']          = array_merge($purchaselist,$purchaseitemlist,$purchasecostlist,$purchasegstlist,$purchasediscountlist,$purchasedispervlist,$purchasesuplist,$purchasespaymentslist,$parentexpensesacclist,$expensesacclist,$parentsupexpensesacclist,$supexpensesacclist,$purchaserntlist,$purchaserntitemlist,$purchaserntcostlist,$purchaserntgstlist,$purchaserntdiscountlist,$purchaserntsuplist,$purchaserntpaymentslist);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/reports/generalledger', $data);
        $this->load->view('layout/footer', $data);
    }
    
    function trialbalance($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('general_ledger', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/accounts/generalreports');
        $this->session->set_userdata('subsub_menu', 'Reports/accounts/trialbalance');
        $data['title'] = 'Trial Balance';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id']      = $brc_id;
        $data['accounts_head_id'] = '';
        $data['accounts_type_id'] = '';
        $data['account_name_id'] = '';
        $data['branchlist']  = $this->branchsettings_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
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
            $start_date         = date('Y-m-d',strtotime($dates['from_date']));
            $end_date           = date('Y-m-d',strtotime($dates['to_date']));
            $data['acclist']    = $this->accounts_model->getAccountsHeadByTypesIDTrial($brc_id); 
            $data['start_date'] = $start_date;
            $data['end_date']   = $end_date;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/reports/trialbalance', $data);
        $this->load->view('layout/footer', $data);
    }


}

?>