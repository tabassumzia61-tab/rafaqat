<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Issueitem extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('cart');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('issueitem', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'issueitem');
        $this->session->set_userdata('sub_menu', 'issueitem/index');
        $data['title'] = 'issueitem List';
        $itemcategory = $this->issueitem_model->getProduct();
        $data['itemcatlist'] = $itemcategory;
        $data['searchlist'] = $this->customlib->get_searchtype();
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $supervisor         = $this->staff_model->getStaffByRoleByBranch(4,1);
        $data['supervisor'] = $supervisor;
        $department         = $this->staff_model->getDepartment();
        $data["department"] = $department;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/issueitem/issueitemList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($id) {
        $result = $this->issueitem_model->getBillDetails($id);
        $this->media_storage->filedownload($result['attachment'], "./uploads/issueitem");
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

    function create(){
        $this->session->set_userdata('top_menu', 'issueitem');
        $this->session->set_userdata('sub_menu', 'issueitem/index');
        $this->cart->destroy();
        $data = array(
            'tax' => 0,
            'discount' => 0,
            'issueitemid' => '',
        );
        $this->session->set_userdata($data);
        //$vendor_result = $this->trader_model->get_vendor();
        $data['vendorlist'] = '';//$vendor_result;
        $itemcategory = $this->issueitem_model->getProduct();
        $data['itemcatlist'] = $itemcategory;
        $supervisor = $this->staff_model->getStaffByRoleByBranch(4,1);
        $data['supervisor'] = $supervisor;
        $department   = $this->staff_model->getDepartment();
        $data["department"] = $department;
        $warehouses_result = $this->warehouses_model->get();
        $data['warehouseslist'] = $warehouses_result;
        $result = $this->issueitem_model->getBillNo();
        if (!empty($result["bill_no"])) {
            $bill_no = $result["bill_no"] + 1;
        } else {
            $bill_no = 1;
        }
        $data['bill_no'] = $bill_no;
        // echo '<pre>';
        // print_r($data['itemcatlist']);
        // exit;
        //print_r($data['productlist']);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/issueitem/issueitemcreate', $data);
        $this->load->view('layout/footer', $data);

    }

    function add_to_cart(){
        $id = $this->input->post('item_id');
        $product = $this->db->get_where('products', array( 'id' => $id ))->row_array();
        if(!empty($this->input->post('rowid'))){
            $data = array(
                'rowid' => $this->input->post('rowid'),
                'qty'   => 0
            );
            $this->cart->update($data);
        }
        if(count($product)){
            $data = array(
                'id'                => $product['id'],
                'avbil_qty'         => '',
                'qty'               => 1,
                'price'             => '',//$product->purchase_price,
                'name'              => $product['name'],
                //'description'       => $product->description,
                //'options' => array('Size' => 'L', 'Color' => 'Red')
            );
            $this->cart->insert($data);
        }
    }

    function show_cart(){
        $itemcategory = $this->issueitem_model->getProduct();
        $data['itemcatlist'] = $itemcategory;
        $this->load->view('admin/issueitem/cart/add_product_cart',$data);
    }

    function update_cart_item(){
        $type = $this->input->post('type');
        if($type === 'qty')
        {
            $data = array(
                'rowid' => $this->input->post('rowid'),
                'qty'   => $this->input->post('o_val')
            );
        }
        elseif ($type === 'prc'){
            $data = array(
                'rowid' => $this->input->post('rowid'),
                'price'   => (float)$this->input->post('o_val')
            );
        }
        elseif ($type === 'des'){
            $data = array(
                'rowid' => $this->input->post('rowid'),
                'description'   => $this->input->post('o_val')
            );
        }
        $this->cart->update($data);
    }

    function remove_item(){
        $data = array(
            'rowid' => $this->input->post('rowid'),
            'qty'   => 0
        );
        $this->cart->update($data);
    }

    function order_discount(){
        $discount = $this->input->post('discount');
        if(!empty($discount)){
            $data = array(
                'discount' => $discount
            );
        }else{
            $data = array(
                'discount' => 0
            );
        }
        $this->session->set_userdata($data);
    }

    function order_tax(){
        $tax = $this->input->post('tax');
        if(!empty($tax)){
            $data = array(
                'tax' => $tax
            );
        }else{
            $data = array(
                'tax' => 0
            );
        }
        $this->session->set_userdata($data);
    }

    function save_issueitem(){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('supervisor_id', $this->lang->line('supervisor'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('warehouse_id', $this->lang->line('warehouse'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dept_id', $this->lang->line('department'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('shift', $this->lang->line('shift'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $data = array(
                'date' => form_error('date'),
                'supervisor_id' => form_error('supervisor_id'),
                'name' => form_error('name'),
                'dept_id' => form_error('dept_id'),
                'shift' => form_error('shift'),
                'warehouse_id' => form_error('warehouse_id'),
                'documents' => form_error('documents'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $userdata        = $this->customlib->getUserData();
            $img_name = $this->media_storage->fileupload("documents", "./uploads/issueitem/");
            $data['bill_no']            = $this->input->post('reference_no');
            $data['supervisor_id']      = $this->input->post('supervisor_id');
            $data['name']               = $this->input->post('name');
            $data['warehouse_id']       = $this->input->post('warehouse_id');
            $data['date']               = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date'));
            $data['datetime']           = $this->customlib->dateFormatToYYYYMMDDHis($this->input->post('date'), '12-hour');
            $data['shift']              = $this->input->post('shift');
            $data['dept_id']            = $this->input->post('dept_id');
            $data['status']             = $this->input->post('status');
            $data['description']        = $this->input->post('order_note');
            $data['attachment']         = $img_name;
            $data['created_by']         = $userdata['id'];
            $data['cart']               = json_encode($this->cart->contents());
            //save purchase_order table
            $this->db->insert('issueitem', $data);
            $issueitem_id = $this->db->insert_id();
            foreach ($this->cart->contents() as $item){
                $o_details['issueitem_id']   = $issueitem_id;
                $o_details['warehouse_id']   = $this->input->post('warehouse_id');
                $o_details['supervisor_id']  = $this->input->post('supervisor_id');
                $o_details['dept_id']        = $this->input->post('dept_id');
                $o_details['item_id']        = $item['id'];
                $o_details['item_name']      = $item['name'];
                $o_details['qty']            = $item['qty'];
                $o_details['created_by']     = $userdata['id'];
                //save order details
                $this->db->insert('issueitem_items', $o_details);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function getBillDetails($id) {
        $data['id'] = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data['print_details'] = '';//$this->Printing_model->get('', 'pharmacy');
        $result = $this->issueitem_model->getBillDetails($id);
        $data['result'] = $result;
        $this->load->view('admin/issueitem/printBill', $data);
        
    }

    public function edit($id) {
        $data['vendorlist'] = '';//$vendor_result;
        $itemcategory = $this->issueitem_model->getProduct();
        $data['itemcatlist'] = $itemcategory;
        $supervisor = $this->staff_model->getStaffByRoleByBranch(4,1);
        $data['supervisor'] = $supervisor;
        $department   = $this->staff_model->getDepartment();
        $data["department"] = $department;
        $warehouses_result = $this->warehouses_model->get();
        $data['warehouseslist'] = $warehouses_result;
        $result = $this->issueitem_model->getBillDetails($id);
        $data['result'] = $result;
        $this->cart->destroy();
        $cartItem = json_decode($result['cart']);
        //pr($cartItem);
        foreach ($cartItem as $item ){
            $cart[] = array(
                'id'                => $item->id,
                'avbil_qty'         => $item->avbil_qty,
                'qty'               => $item->qty,
                'price'             => $item->price,
                'name'              => $item->name,
                // 'description'       => $item->description,
            );
        }

        $this->cart->insert($cart);
        $s_data = array(
            'tax' => '',//$result['tax'],
            'discount' => '',//$result['discount'],
            'issueitemid' => $result['id'],
        );
        $this->session->set_userdata($s_data);
        $this->load->view('layout/header');
        $this->load->view("admin/issueitem/issueitemedit", $data);
        $this->load->view('layout/footer');
    }

    function save_edit_issueitem($id){
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('supervisor_id', $this->lang->line('supervisor'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('warehouse_id', $this->lang->line('warehouse'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dept_id', $this->lang->line('department'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('shift', $this->lang->line('shift'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $data = array(
                'date' => form_error('date'),
                'supervisor_id' => form_error('supervisor_id'),
                'name' => form_error('name'),
                'dept_id' => form_error('dept_id'),
                'shift' => form_error('shift'),
                'warehouse_id' => form_error('warehouse_id'),
                'documents' => form_error('documents'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $result = $this->issueitem_model->getBillDetails($id);
            $data['id']        = $id;
            $data['bill_no']            = $this->input->post('reference_no');
            $data['supervisor_id']      = $this->input->post('supervisor_id');
            $data['name']               = $this->input->post('name');
            $data['warehouse_id']       = $this->input->post('warehouse_id');
            $data['date']               = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date'));
            $data['datetime']           = $this->customlib->dateFormatToYYYYMMDDHis($this->input->post('date'), '12-hour');
            $data['shift']              = $this->input->post('shift');
            $data['dept_id']            = $this->input->post('dept_id');
            $data['status']             = $this->input->post('status');
            $data['description']        = $this->input->post('order_note');
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $img_name = $this->media_storage->fileupload("documents", "./uploads/issueitem/");
            } else {
                $img_name = $result['attachment'];
            }
            $data['attachment'] = $img_name;
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $this->media_storage->filedelete($result['attachment'], "uploads/issueitem");
            }
            $data['cart']               = json_encode($this->cart->contents());
            $this->db->where('issueitem.id', $data['id']);
            $this->db->update('issueitem', $data);
            
            $prodetil_id = $this->input->post('detil_id');
            $pdetil_id = $this->input->post('pdetil_id');
            $carts_id = $this->input->post('carts_id');
            $add_result = array_diff($pdetil_id,$prodetil_id);
            $delete_result = array_diff($prodetil_id, $pdetil_id);
            // print_r($delete_result);
            // exit;
            if (!empty($add_result)) {
                foreach ($add_result as $add_key => $add_val) {
                    $C_id = $carts_id[$add_key];
                    foreach ($this->cart->contents() as $item){
                        if ($C_id === $item['id']) {
                            $o_details['issueitem_id']   = $data['id'];
                            $o_details['warehouse_id']   = $this->input->post('warehouse_id');
                            $o_details['supervisor_id']  = $this->input->post('supervisor_id');
                            $o_details['dept_id']        = $this->input->post('dept_id');
                            $o_details['item_id']        = $item['id'];
                            $o_details['item_name']      = $item['name'];
                            $o_details['qty']            = $item['qty'];
                            //save order details
                            $this->db->insert('issueitem_items', $o_details);
                        }
                    }
                }
            }else{
                foreach ($pdetil_id as $key => $detailval){
                    $C_id = $carts_id[$key];
                    foreach ($this->cart->contents() as $item_key => $item_val) {
                        if ($C_id === $item_val['id']) {
                            $o_details['id']               = $detailval;
                            $o_details['issueitem_id']   = $data['id'];
                            $o_details['warehouse_id']   = $this->input->post('warehouse_id');
                            $o_details['supervisor_id']  = $this->input->post('supervisor_id');
                            $o_details['dept_id']        = $this->input->post('dept_id');
                            $o_details['item_id']        = $item_val['id'];
                            $o_details['item_name']      = $item_val['name'];
                            $o_details['qty']            = $item_val['qty'];
                            //save order details
                            $this->db->where('issueitem_items.id', $o_details['id']);
                            $this->db->update('issueitem_items', $o_details);
                        }
                    }
                }
            }
            if (!empty($delete_result)) {
                $d_delete_array = array();
                foreach ($delete_result as $d_delete_key => $d_delete_value) {

                    $d_delete_array[] = $d_delete_value;
                }
                $this->issueitem_model->remove_detial_product($d_delete_array);

            }
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function deleteissueitem($id) {
        if (!empty($id)) {
            $this->issueitem_model->deleteissueitem($id);
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Record Deleted successfully</div>');
            redirect('admin/issueitem/index/');
        }
    }
    
    public function getissueitemlist(){
        $m               = $this->issueitem_model->getIssueitemAll();
        $m               = json_decode($m);
        $currency_symbol = $this->customlib->getSystemCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            $counts = 1;
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $documents = '';
                if ($this->rbac->hasPrivilege('issueitem', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/issueitem/edit/" . $value->id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('issueitem', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/issueitem/deleteissueitem/" . $value->id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/issueitem/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                $items = $this->issueitem_model->getItemsByIssueItemID($value->id);
                $itemname = '';
                $itemqty = '';
                if(!empty($items)){
                    foreach($items as $ival){
                        $itemname .= $ival['item_name'].'<br/>';
                        $itemqty .= $ival['qty'].'<br/>';
                    }
                }
                $bill_no   = '<a href="#" onclick="viewDetail('.$value->id.')" data-toggle="tooltip"  title="'.$this->lang->line("show").'" >'.$value->bill_no.'</a>';
                $row       = array();
                $row[]     = $counts;
                $row[]     = $this->customlib->YYYYMMDDHisTodateFormat($value->datetime,'12-hour');
                $row[]     = $bill_no;
                $row[]     = $value->staff_name;
                $row[]     = $value->name;
                $row[]     = $itemname;
                $row[]     = $itemqty;
                $row[]     = $documents;
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
    
    
    public function searchissueitem(){
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
            $supervisor_id   = $this->input->post('supervisor_id');
            $shift           = $this->input->post('shift');
            $dept_id         = $this->input->post('dept_id');
            $params = array('button_type' => $button_type, 'search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to,'product_id' =>$product_id,'supervisor_id' =>$supervisor_id,'shift' => $shift,'dept_id' => $dept_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getsearchissueitemlist(){
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $product_id  = $this->input->post('product_id');
        $supervisor_id = $this->input->post('supervisor_id');
        $dept_id = $this->input->post('dept_id');
        $shift = $this->input->post('shift');
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
            $resultList        = $this->issueitem_model->getIssueItemByID($product_id,$supervisor_id,$dept_id,$shift, $date_from, $date_to);
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
                if ($this->rbac->hasPrivilege('issueitem', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/issueitem/edit/" . $value->id . "'   data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i><span>".$this->lang->line('edit')."</span></a>";
                }
                if ($this->rbac->hasPrivilege('issueitem', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/issueitem/deleteissueitem/" . $value->id . "' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i><span>".$this->lang->line('delete')."</span></a>";
                }
                if ($value->attachment) {
                    $documents = "<a href='" . base_url() . "admin/issueitem/download/" . $value->id . "' class='btn btn-warning btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $action = "<div class='btn-group actions_dropdown_wrap'><button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".$this->lang->line('action') ." <span class='caret'></span></button>
                                <ul class='dropdown-menu'>
                                    <li>".$editbtn."</li>
                                    <li>".$deletebtn."</li>
                                <ul>
                            </div>";
                $items = $this->issueitem_model->getItemsByIssueItemIDByPID($value->id,$product_id);
                $itemqty = '';
                $itemname = '';
                if(!empty($items)){
                    foreach($items as $ival){
                        $itemname .= $ival['item_name'].'<br/>';
                        $itemqty .= $ival['qty'].'<br/>';
                    }
                }
                $row       = array();
                $row[]     = $counts;
                $row[]     = $this->customlib->YYYYMMDDHisTodateFormat($value->datetime,'12-hour');
                $row[]     = $value->bill_no;
                $row[]     = $value->staff_name;
                $row[]     = $value->name;
                $row[]     = $itemname;
                $row[]     = $itemqty;
                $row[]     = $documents;
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

}

?>