<?php
class Stock extends Admin_Controller {

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
    
    
    
    public function stockreports()    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/stock/stockreports');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('admin/stock/reports/stockreports');
        $this->load->view('layout/footer');
    }
    
    function qtywisestock($brnc_id = null) {
        if (!$this->rbac->hasPrivilege('quantity_wise_stock', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/stock/stockreports');
        $this->session->set_userdata('subsub_menu', 'Reports/stock/qtywisestock');
        $data['title'] = 'Quantity Wise Stock';
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
        $data["supplierlist"] = $this->supplier_model->get();
        $data["customerlist"] = $this->customers_model->get();
        $data["stafflist"]    = $this->staff_model->get();
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
            $data['salelist']               = $this->stock_model->getSalesByCustomersID($brc_id,$item_id,$start_date,$end_date);
            $data['purchaselist']           = $this->stock_model->getPurchaseBySuppliersID($brc_id,$item_id,$start_date,$end_date);
            //echo '<pre>';
           // print_r($data['salelist']);
            //print_r($data['purchaselist']);
            //exit;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/stock/reports/qtywisestock', $data);
        $this->load->view('layout/footer', $data);
    }


}

?>