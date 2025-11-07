<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Supplier extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('media_storage');
    }

    public function index($brnc_id = null){
        if (!$this->rbac->hasPrivilege('supplier', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'people');
        $this->session->set_userdata('sub_menu', 'supplier/index');
        $data['title']        = 'Supplier List';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $resultlist           = $this->supplier_model->searchSupplierFullText($brc_id,"","", 1);
        $data['resultlist']   = $resultlist;
        $data['search_text_supplier']     = '';
        $data['selected_value_supplier']  = '';
        $search             = $this->input->post("search");
        $search_text        = $this->input->post('search_text');
        if (isset($search)) {
            if ($search == 'search_full') {
                $this->form_validation->set_rules('text_supplier', $this->lang->line('search_by_keyword'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                } else {
                    $data['searchby']    = "text";
                    if(!empty($this->input->post('brc_id'))){
                        $brc_id = $this->input->post('brc_id');
                    }else{
                        $brc_id = $this->customlib->getBranchID();
                    }
                    $selected_category = $this->input->post('selected_value_supplier');
                    $search_text = trim($this->input->post('text_supplier'));
                    $data['selected_value_supplier'] = $selected_category;
                    $data['search_text_supplier'] = $search_text;
                    $resultlist          = $this->supplier_model->searchSupplierFullText($brc_id,$search_text,$selected_category, 1);
                    $data['resultlist']  = $resultlist;
                    $data['brc_id']      = $brc_id;
                    $data['title']       = $this->lang->line('search_details') . ': ' . $data['search_text_supplier'];
                }
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/supplier/suppliersearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create($brnc_id = null){
        if (!$this->rbac->hasPrivilege('supplier', 'can_add')) {
            access_denied();
        }
        $data['title']                = 'Add Supplier';
        if(!empty($brnc_id)){
            $brc_id = $brnc_id;
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['branchlist'] = $this->branchsettings_model->get();
        $sys_setting_detail           = $this->setting_model->getSetting();
        $data['sys_setting']          = $sys_setting_detail ;
        $data['customer_auto_insert'] = $sys_setting_detail->customer_auto_insert;
        $suppl_no  = 0;
        if ($sys_setting_detail->supplier_auto_insert) {
            if ($sys_setting_detail->supplier_update_status) {
                $suppl_no = $sys_setting_detail->supplier_prefix . $sys_setting_detail->supplier_start_from;
                $last_cus = $this->supplier_model->getlastrecord();
                $last_quotes_digit = str_replace($sys_setting_detail->supplier_prefix, "", $last_cus['supplier_id']);
                $suppl_no                = $sys_setting_detail->supplier_prefix . sprintf("%0" . $sys_setting_detail->supplier_no_digit . "d", $last_quotes_digit + 1);
                $data['supplier_id'] = $suppl_no;
            } else {
                $suppl_no                = $sys_setting_detail->supplier_prefix . sprintf("%0" . $sys_setting_detail->supplier_no_digit . "d", 1);
                $data['supplier_id'] = $suppl_no;
            }
            // $suppl_no_exists = $this->suppliers_model->check_supplier_exists($suppl_no);
            // if ($suppl_no_exists) {
            //     $insert = false;
            // }
        } else {
            $last_supplier = $this->supplier_model->getlastrecord();
            if (!empty($last_supplier)) {
                $suppl_no = $last_supplier['supplier_id'] + 1;
            }else{
                $suppl_no = 1;
            }
            $data['supplier_id'] = $suppl_no;
        }
        $genderList          = $this->customlib->getGender();
        $data['genderList']  = $genderList;
        $data['citylist']    = $this->city_model->get();
        $data['country_id'] = '';
        $data['state_id'] = '';
        $data['city_id'] = '';
        $data['area_id'] = '';
        $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payment_limit', $this->lang->line('payment_limit'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|xss_clean|valid_email');
        // $this->form_validation->set_rules('contact_person_phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        // $this->form_validation->set_rules('contact_person_email', $this->lang->line('email'), 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        //$this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/supplier/supplierCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $data = array(
                'brc_id'               => $brc_id,
                'supplier_id'          => $this->input->post('supplier_id'),
                'name'                 => $this->input->post('name'),
                'surname'              => $this->input->post('surname'),
                'phone'                => $this->input->post('phone'),
                'gender'               => $this->input->post('gender'),
                'company'              => $this->input->post('company'),
                'email'                => $this->input->post('email'),
                'cnic'                 => $this->input->post('cnic'),
                'ntn_no'               => $this->input->post('ntn_no'),
                'gstn'                 => $this->input->post('gstn'),
                'contact_person_name'  => $this->input->post('contact_person_name'),
                'contact_person_phone' => $this->input->post('contact_person_phone'),
                'contact_person_email' => $this->input->post('contact_person_email'),
                'postal_code'          => $this->input->post('postal_code'),
                'payment_limit'        => $this->input->post('payment_limit'),
                'is_tax_status'        => $this->input->post('is_tax_status'),
                'tax_percentage'       => $this->input->post('tax_percentage'),
                'country_id'           => $this->input->post('country_id'),
                'state_id'             => $this->input->post('state_id'),
                'city_id'              => $this->input->post('city_id'),
                'area_id'              => $this->input->post('area_id'),
                'address'              => $this->input->post('address'),
                'permanent_address'    => $this->input->post('permanent_address'),
                'description'          => $this->input->post('note'),
                'acc_type_id'          => 6,
                'is_active'            => 1,
            );
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                    $img_name             = $this->media_storage->fileupload("file", "./uploads/supplier_images/");
                    $data['image'] = $img_name;
            }
            $data_setting                             = array();
            $data_setting['id']                       = $sys_setting_detail->id;
            $data_setting['supplier_auto_insert']   = $sys_setting_detail->supplier_auto_insert;
            $data_setting['supplier_update_status'] = $sys_setting_detail->supplier_update_status;
            if ($data_setting['supplier_auto_insert']) {
                if ($data_setting['supplier_update_status'] == 0) {
                    $data_setting['supplier_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
            $supplier_id = $this->supplier_model->add($data);
            $upload_dir = './uploads/supplier_documents/' . $supplier_id . '/';
            if (!is_dir($upload_dir) && !mkdir($upload_dir)) {
                die("Error creating folder $upload_dir");
            }   
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                  $cnic = $this->media_storage->fileupload("first_doc", $upload_dir);
            } else {
                $cnic = "";
            }
            // if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
            //      $joining_letter = $this->media_storage->fileupload("second_doc", $upload_dir);
            // } else {
            //     $joining_letter = "";
            // }
            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                $resignation_letter = $this->media_storage->fileupload("third_doc", $upload_dir);
            } else {
                $resignation_letter = "";
            }
            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
               $fourth_doc = $this->media_storage->fileupload("fourth_doc", $upload_dir);
            } else {
                $fourth_title = "";
                $fourth_doc   = "";
            }
            $data_doc = array('id' => $supplier_id, 'cnic_image' => $cnic, 'resignation_letter' => $resignation_letter, 'other_document_file' => $fourth_doc);
                $this->supplier_model->add($data_doc);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/supplier/index/'.$brc_id);
        }
    }

    public function edit($id,$brc_id){
        if (!$this->rbac->hasPrivilege('supplier', 'can_edit')) {
            access_denied();
        }
        $data['title']        = 'Edit Supplier';
        $data['brc_id']       = $brc_id;
        $data['branchlist']   = $this->branchsettings_model->get();
        $data['citylist']     = $this->city_model->get();
        $genderList           = $this->customlib->getGender();
        $data['genderList']   = $genderList;
        $data['id']           = $id;
        $supplier             = $this->supplier_model->get($id);
        $data['supplier']     = $supplier;
        $data['country_id']   = $supplier['country_id'];
        $data['state_id']     = $supplier['state_id'];
        $data['city_id']      = $supplier['city_id'];
        $data['area_id']      = $supplier['area_id'];
        $cnicc                    = $this->input->post("cnic_doc");
        // $joining_letter            = $this->input->post("joining_letter");
        $resignation_letter        = $this->input->post("resignation_letter");
        $other_document_name       = $this->input->post("other_document_name");
        $other_document_file       = $this->input->post("other_document_file"); 
        $this->form_validation->set_rules('supplier_id', $this->lang->line('supplier_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payment_limit', $this->lang->line('payment_limit'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|xss_clean|valid_email');
        // $this->form_validation->set_rules('contact_person_phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        // $this->form_validation->set_rules('contact_person_email', $this->lang->line('email'), 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        //$this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/supplier/supplierEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $data = array(
                'id'                   => $id,
                'brc_id'               => $brc_id,
                'supplier_id'          => $this->input->post('supplier_id'),
                'name'                 => $this->input->post('name'),
                'surname'              => $this->input->post('surname'),
                'phone'                => $this->input->post('phone'),
                'gender'               => $this->input->post('gender'),
                'company'              => $this->input->post('company'),
                'email'                => $this->input->post('email'),
                'cnic'                 => $this->input->post('cnic'),
                'ntn_no'               => $this->input->post('ntn_no'),
                'gstn'                 => $this->input->post('gstn'),
                'contact_person_name'  => $this->input->post('contact_person_name'),
                'contact_person_phone' => $this->input->post('contact_person_phone'),
                'contact_person_email' => $this->input->post('contact_person_email'),
                'payment_limit'        => $this->input->post('payment_limit'),
                'is_tax_status'        => $this->input->post('is_tax_status'),
                'tax_percentage'       => $this->input->post('tax_percentage'),
                'postal_code'          => $this->input->post('postal_code'),
                'country_id'           => $this->input->post('country_id'),
                'state_id'             => $this->input->post('state_id'),
                'city_id'              => $this->input->post('city_id'),
                'area_id'              => $this->input->post('area_id'),
                'address'              => $this->input->post('address'),
                'permanent_address'    => $this->input->post('permanent_address'),
                'description'          => $this->input->post('note'),
            );
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $img_name       = $this->media_storage->fileupload("file", "./uploads/supplier_images/");
                $data['image'] = $img_name;

                if ($supplier['image'] != '') {
                    $this->media_storage->filedelete($supplier['image'], "uploads/supplier_images");
                }
            }
            $this->supplier_model->add($data);
            $upload_dir = './uploads/supplier_documents/' . $id . '/';
            if (!is_dir($upload_dir) && !mkdir($upload_dir)) {
                die("Error creating folder $upload_dir");
            }
            
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $cnic_doc = $this->media_storage->fileupload("first_doc", $upload_dir);
            } else {
                $cnic_doc = $cnicc;
            }

            // if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
            //     $joining_letter_doc = $this->media_storage->fileupload("second_doc", $upload_dir);
            // } else {
            //     $joining_letter_doc = $joining_letter;
            // }

            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {

                $resignation_letter_doc = $this->media_storage->fileupload("third_doc", $upload_dir);

            } else {
                $resignation_letter_doc = $resignation_letter;
            }

            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $fourth_doc = $this->media_storage->fileupload("fourth_doc", $upload_dir);
            } else {
                $fourth_title = 'Other Document';
                $fourth_doc   = $other_document_file;
            }
            $data_doc = array('id' => $id, 'cnic_image' => $cnic_doc, 'resignation_letter' => $resignation_letter_doc, 'other_document_file' => $fourth_doc);
            $this->supplier_model->add($data_doc);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/supplier/index/'.$brc_id);
        }
    }

    public function delete($id,$brc_id){
        if (!$this->rbac->hasPrivilege('supplier', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Supplier List';
        $this->supplier_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/supplier/index/'.$brc_id);
    }

    public function profile($id){
        if (!$this->rbac->hasPrivilege('supplier', 'can_view')) {
            access_denied();
        }
        $this->load->model("staffattendancemodel");
        $this->load->model("setting_model");
        $data['title']   = 'Staff Details';
        $data["id"]      = $id;
        $supplier_info   = $this->supplier_model->getProfile($id);
        $userdata        = $this->customlib->getUserData();
        $userid          = $userdata['id'];
        $data['supplier_doc_id']  = $id;
        $data['supplier']         = $supplier_info;
        $guarantor_info        = $this->supplier_model->getGuarantor($id);
        $data['guarantorlist'] = $guarantor_info;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/supplier/supplierprofile', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addguarantor(){
        $this->form_validation->set_rules('gura_name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gura_cnic', $this->lang->line('cnic'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gura_phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        //$this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'gura_name'  => form_error('gura_name'),
                'gura_cnic'  => form_error('gura_cnic'),
                'gura_phone'  => form_error('gura_phone'),
                'file'       => form_error('file'),
                'first_doc'  => form_error('first_doc'),
                'third_doc'  => form_error('third_doc'),
                'fourth_doc' => form_error('fourth_doc'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $dataarr = array(
                'name'          => $this->input->post('gura_name'),
                'cnic'          => $this->input->post('gura_cnic'),
                'phone'         => $this->input->post('gura_phone'),
                'supplier_id'   => $this->input->post('supplier_id'),
                'address'       => $this->input->post('address'),
                'permanent_address'   => $this->input->post('permanent_address'),
            );
             if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                    $img_name             = $this->media_storage->fileupload("file", "./uploads/guarantor_images/");
                    $dataarr['image'] = $img_name;
            }
            $guarantor_id = $this->supplier_model->addguarantor($dataarr);
            $upload_dir = './uploads/guarantor_documents/' . $guarantor_id . '/';
            if (!is_dir($upload_dir) && !mkdir($upload_dir)) {
                die("Error creating folder $upload_dir");
            }   
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                  $cnic = $this->media_storage->fileupload("first_doc", $upload_dir);
            } else {
                $cnic = "";
            }
            // if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
            //      $joining_letter = $this->media_storage->fileupload("second_doc", $upload_dir);
            // } else {
            //     $joining_letter = "";
            // }
            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                $resignation_letter = $this->media_storage->fileupload("third_doc", $upload_dir);
            } else {
                $resignation_letter = "";
            }
            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
               $fourth_doc = $this->media_storage->fileupload("fourth_doc", $upload_dir);
            } else {
                $fourth_title = "";
                $fourth_doc   = "";
            }
            $data_doc = array('id' => $guarantor_id, 'cnic_image' => $cnic, 'resignation_letter' => $resignation_letter, 'other_document_file' => $fourth_doc);
                $this->supplier_model->addguarantor($data_doc);
            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function getguarantoredit(){
        $id                         = $this->input->post('id');
        $data['guarantorlist']      = $this->supplier_model->getGuarantorByID($id);        
        $page                       = $this->load->view("admin/supplier/_edit_guarantor", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function editguarantor(){
        $this->form_validation->set_rules('gura_name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gura_cnic', $this->lang->line('cnic'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gura_phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        //$this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'gura_name'  => form_error('gura_name'),
                'gura_cnic'  => form_error('gura_cnic'),
                'gura_phone'  => form_error('gura_phone'),
                'file'       => form_error('file'),
                'first_doc'  => form_error('first_doc'),
                'third_doc'  => form_error('third_doc'),
                'fourth_doc' => form_error('fourth_doc'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $guarantor = $this->supplier_model->getGuarantorByID($this->input->post('id'));
            $dataarr = array(
                'id'            => $this->input->post('id'),
                'name'          => $this->input->post('gura_name'),
                'cnic'          => $this->input->post('gura_cnic'),
                'phone'         => $this->input->post('gura_phone'),
                'supplier_id'   => $this->input->post('supplier_id'),
                'address'       => $this->input->post('address'),
                'permanent_address'   => $this->input->post('permanent_address'),
            );
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $img_name       = $this->media_storage->fileupload("file", "./uploads/guarantor_images/");
                $dataarr['image'] = $img_name;

                if ($guarantor['image'] != '') {
                    $this->media_storage->filedelete($guarantor['image'], "uploads/guarantor_images");
                }
            }
            $this->supplier_model->addguarantor($dataarr);
            $upload_dir = './uploads/guarantor_documents/' . $this->input->post('id') . '/';
            if (!is_dir($upload_dir) && !mkdir($upload_dir)) {
                die("Error creating folder $upload_dir");
            }   
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                  $cnic = $this->media_storage->fileupload("first_doc", $upload_dir);
            } else {
                if ($guarantor['cnic_image'] != '') {
                    $cnic = $this->media_storage->fileupload($guarantor['cnic_image'], $upload_dir);
                }else{
                    $cnic = "";
                }
            }
            // if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
            //      $joining_letter = $this->media_storage->fileupload("second_doc", $upload_dir);
            // } else {
            //     $joining_letter = "";
            // }
            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                $resignation_letter = $this->media_storage->fileupload("third_doc", $upload_dir);
            } else {
                if ($guarantor['resignation_letter'] != '') {
                    $resignation_letter = $this->media_storage->fileupload($guarantor['resignation_letter'], $upload_dir);
                }else{
                    $resignation_letter = "";
                }
            }
            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
               $fourth_doc = $this->media_storage->fileupload("fourth_doc", $upload_dir);
            } else {
                if ($guarantor['other_document_file'] != '') {
                    $fourth_doc = $this->media_storage->fileupload($guarantor['other_document_file'], $upload_dir);
                }else{
                    $fourth_doc = "";
                }
            }
            $data_doc = array('id' => $this->input->post('id'), 'cnic_image' => $cnic, 'resignation_letter' => $resignation_letter, 'other_document_file' => $fourth_doc);
            $this->supplier_model->addguarantor($data_doc);
            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function delete_guarantor($id){
        if (!empty($id)) {
            $this->supplier_model->delete_guarantor($id);
        }
        $msg   = $this->lang->line('success_message');
        $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        echo json_encode($array);
    }

    public function getguarantorinfo(){
        $id                         = $this->input->post('id');
        $data['guarantor']      = $this->supplier_model->getGuarantorByID($id);        
        $page                       = $this->load->view("admin/supplier/_info_guarantor", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function handle_upload(){
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['file']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_first_upload(){
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {

            $file_type = $_FILES["first_doc"]['type'];
            $file_size = $_FILES["first_doc"]["size"];
            $file_name = $_FILES["first_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['first_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_second_upload(){
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {

            $file_type         = $_FILES["second_doc"]['type'];
            $file_size         = $_FILES["second_doc"]["size"];
            $file_name         = $_FILES["second_doc"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['second_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_third_upload(){
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {

            $file_type = $_FILES["third_doc"]['type'];
            $file_size = $_FILES["third_doc"]["size"];
            $file_name = $_FILES["third_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['third_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_fourth_upload(){
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {

            $file_type = $_FILES["fourth_doc"]['type'];
            $file_size = $_FILES["fourth_doc"]["size"];
            $file_name = $_FILES["fourth_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['fourth_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    


    public function ledgers($id){
        $this->session->set_userdata('top_menu', 'people');
        $this->session->set_userdata('sub_menu', 'supplier/ledgers');
        $data['title']   = 'Supplier Payable Detail';
        $supplier_result = $this->supplier_model->get($id);
        $data['itemsupplier'] = $supplier_result;
        $data['itemsupplierID'] = $id;
        if(!empty($supplier_result['brc_id'])){
            $brc_id = $supplier_result['brc_id'];
        }else{
            $brc_id = $this->customlib->getBranchID();
        }
        $data['brc_id'] = $brc_id;
        $data['searchlist'] = $this->customlib->get_searchtype();
        $date_from   = "";
        $date_to     = "";
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $between_date   = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
            $date_from   = date('Y-m-d',strtotime($between_date['from_date']));
            $date_to     = date('Y-m-d',strtotime($between_date['to_date']));
        } else {
            $between_date   = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
            $date_from   = date('Y-m-d',strtotime($between_date['from_date']));
            $date_to     = date('Y-m-d',strtotime($between_date['to_date']));
        }
        
        $search_type = $this->input->post('search_type');
        if ($search_type == 'period') {
            $date_from = date('Y-m-d',$this->customlib->datetostrtotime($this->input->post('date_from')));
            $date_to   = date('Y-m-d',$this->customlib->datetostrtotime($this->input->post('date_to')));
        }
        $data["acclist"]        = $this->accounts_model->getaccountsheadByID(2,1);
        $purchaseresult            = $this->supplier_model->getPurchaseBySupplier($brc_id,$id,'','');
        $paymentsresult         = $this->supplier_model->getPaymentsBySupplier($brc_id,$id,'','');
        $data['ledgerlist']     = array_merge($purchaseresult, $paymentsresult);
        $data['balam'] = 0;
        $search   = $this->input->post("search");
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                    $purchaseresult            = $this->supplier_model->getPurchaseBySupplier($brc_id,$id,'','');
                    $paymentsresult         = $this->supplier_model->getPaymentsBySupplier($brc_id,$id,'','');
                    $data['ledgerlist']     = array_merge($purchaseresult, $paymentsresult);
                } else {
                    $purchaseresult         = $this->supplier_model->getPurchaseBySupplier($brc_id,$id,$date_from,$date_to);
                    $paymentsresult         = $this->supplier_model->getPaymentsBySupplier($brc_id,$id,$date_from,$date_to);
                    $data['ledgerlist']     = array_merge($purchaseresult, $paymentsresult);
                    $purchasebalresult         = $this->supplier_model->getPurchaseBySupplierBal($brc_id,$id,$date_from,$date_to);
                    $salam = 0;
                    if(!empty($purchasebalresult)){
                        $salam = $purchasebalresult['amount'];
                    }
                    $paymentsbalresult         = $this->supplier_model->getPaymentsBySupplierBal($brc_id,$id,$date_from,$date_to);
                    $payam = 0;
                    if(!empty($paymentsbalresult)){
                        $payam = $paymentsbalresult['paid_amount'];
                    }
                    $data['balam'] = $salam - $payam;
                    $data['date_from'] = $date_from;
                    $data['date_to'] = $date_to;
                }
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/supplier/ledgers', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function getPaymentBill($id) {
        $data['id'] = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data['print_details'] = '';//$this->Printing_model->get('', 'pharmacy');
        $result = $this->supplier_model->printVocherByID($id);
        $data['result'] = $result;
        $this->load->view('admin/payments/printsupplierBill', $data);
        
    }

    public function getsupplierbilllist($id,$date_from=null,$date_to=null,$product_id=null){
        $m               = $this->supplier_model->getPurchasesBySupplier($id,3,$date_from,$date_to,$product_id);
        $m               = json_decode($m);
        $currency_symbol = $this->customlib->getSystemCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            $gtall = 0;
            $gtpqtyall = 0;
            $gtpptall = 0;
            $gtpkhqtyall = 0;
            $gtpkhptall = 0;
            $counts = 1;
            foreach ($m->data as $key => $value) {
                $items = $this->supplier_model->getItemsByPurchaseIDByPID($value->id,$product_id);
                $itemname = '';
                $itemqty = '';
                $itemprice = '';
                $tkhoyaqty = 0;
                $tunitcost = 0;
                $totalam   = 0;
                $totalp   = 0;
                $tkhoyaqtyper = 0;
                if(!empty($items)){
                    foreach($items as $ival){
                        $itemname .= $ival['item_name'].'<br/>';
                        $itemqty  .= $ival['qty'].'<br/>';
                        $itemprice  .= (float)$ival['price'].'<br/>';
                        if($ival['item_id'] == 1){
                            $khoyaqty = $this->purchases_model->getPurchaseItemsKhoya($value->id,$ival['item_id']);
                            if(!empty($khoyaqty->khoya_quantity)){
                                $tkhoyaqtyper =  $tkhoyaqty + $khoyaqty->khoya_par;
                                $tkhoyaqty =  $tkhoyaqty + $khoyaqty->khoya_quantity;
                                $tunitcost =  $tunitcost + $khoyaqty->khoya_unit_cost;
                                $totalam =  $totalam + ($khoyaqty->khoya_quantity * $khoyaqty->khoya_unit_cost);
                                $gtpkhqtyall = $gtpkhqtyall + $tkhoyaqty;
                            }
                        }else{
                            $totalp =  $totalp + ($ival['qty'] * $ival['price']);  
                            $gtpptall  = $gtpptall  + $totalp;
                        }
                        $gtpqtyall = $gtpqtyall + $ival['qty'];
                    }
                }
                $gtpkhptall = $gtpkhptall + $totalam;
                $gt = $totalam + $totalp;
                $gtall = $gtall + $gt; 
                $sts = '';
                if($value->status == 3){
                    $sts = "<span class='btn btn-primary'>Completed</span>";
                }
                $bill_no   = '<a href="#" onclick="viewDetail('.$value->id.')" data-toggle="tooltip"  title="'.$this->lang->line("show").'" >'.$value->bill_no.'</a>'; 
                $row       = array();
                $row[]     = $counts;
                $row[]     = $this->customlib->YYYYMMDDHisTodateFormat($value->datetime,'12-hour');
                $row[]     = $bill_no;
                //$row[]     = $value->name;
                //$row[]     = $sts;
                $row[]     = $itemname;
                $row[]     = $itemqty;
                $row[]     = $itemprice;
                $row[]     = $totalp;
                $row[]     = $tkhoyaqty;
                $row[]     = $tkhoyaqtyper;
                $row[]     = $tunitcost;
                $row[]     = number_format($totalam, 2, '.', '');
                $row[]     = number_format($totalam + $totalp, 2, '.', '');
                $row[]     = number_format($totalam + $totalp, 2, '.', '');
                //$row[]     = $documents;
                //$row[]     = $action;
                $dt_data[] = $row;
                $counts++; 
            }
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b style='font-weight:normal'>" . $this->lang->line('grand_total') . " : </b>";
            $footer_row[] = amountFormat($gtpqtyall);
            $footer_row[] = "";
            $footer_row[] = amountFormat($gtpptall);
            $footer_row[] = amountFormat($gtpkhqtyall);
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = amountFormat($gtpkhptall);
            $footer_row[] = "";
            $footer_row[] = amountFormat($gtall);
            //$footer_row[] = ""  ;
            $dt_data[]    = $footer_row;
        }
        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }   

    public function viewdetailpayment(){
        $supplier_id   = $this->input->post('supplier_id');
        $data['supplier_id'] = $supplier_id;
        $this->load->view('admin/supplier/viewpayment', $data);
    }

    public function addcashBySupplier(){
        $this->form_validation->set_rules('paid_types', 'Paid Types', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) { 
            $data = array(
                'paid_types' => form_error('paid_types'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $date_post = $this->input->post('date');
            $date_frmt = str_replace('/', '-', $date_post);
            $payment_mode = $this->input->post('payment_mode');
            $itemsupplierID = $this->input->post('itemsupplierID');
            $data = array(
                'supplier_id' => $itemsupplierID,
                //'feetype_id' => $val_fee->feetype_id,
                'paid_types' => $this->input->post('paid_types'),
                'paid_id' => $this->input->post('paid_id'),
                'date' => date('Y-m-d',$this->customlib->datetostrtotime($this->input->post('date'))),
                'paid_amount' => $this->input->post('paid_amount'),
                'par_acc_head_id' => $this->input->post('payment_mode'),
                'voucher_type_id' => $this->input->post('payment_type'),
                //'acc_head_id' => $acc_head_id,
                'note' => $this->input->post('description'),
            );
            $inster_id = $this->supplier_model->addcashpayment($data);
            // $notification = $this->input->post('sendnotice');
            // if(!empty($notification)){
            //     if (!empty($inster_id)) {
            //         $student_detail = $this->student_model->getByStudentSession($userid);
            //         $send_to     = $student_detail['father_phone'];
            //         $email       = $student_detail['guardian_email'];
            //         $sender_details = array('invoice_id' => $inster_id,'student_session_id' => $userid,  'contact_no' => $send_to, 'email' => $email);
            //         $this->mailsmsconf->mailsms('fee_collect', $sender_details);
            //     }
            // }
            // $id = $userid;
            $setting_result = $this->setting_model->get();
            $data['settinglist'] = $setting_result;
            $itemsupplier_result = $this->supplier_model->get($itemsupplierID);
            $data['itemsupplier'] = $itemsupplier_result;
            $cash_debit = $this->supplier_model->getpayableBySupplierID($itemsupplierID);
            $cash_credit = $this->supplier_model->getpaidBySupplierID($itemsupplierID);
            $supplierdetailskhoya = $this->supplier_model->getSupplierKhoyaDetailByID($itemsupplierID);
            $tkhampayable = 0;
            if(!empty($supplierdetailskhoya)){
                foreach($supplierdetailskhoya as $kh_val){
                    $tkhampayable = $tkhampayable + ($kh_val['khoya_unit_cost'] * $kh_val['khoya_quantity']);
                }
            } 
            if (!empty($cash_debit['payable_amount'])) {
                $debit_amount = $cash_debit['payable_amount'];
            }else{
                $debit_amount = 0;
            }
            if (!empty($cash_credit['paid_amount'])) {
                $credit_amount = $cash_credit['paid_amount'];
            }else{
                $credit_amount = 0;
            }
            $data['khpayable']  = $tkhampayable;
            $data['payable']    = $debit_amount;
            $data['paid']       = $credit_amount;
            $data['feemonthdate']  = date("Y-m-d" ,strtotime($date_frmt));//strtotime($this->input->post('date'))
            $data['voucherList'] = $this->supplier_model->printcashpayByInsertID($inster_id);
            $this->load->view('print/printCashPaymentreceipt', $data);
        }
    }
    
    public function disablestsupplier($id){
        if (!$this->rbac->hasPrivilege('disable_supplier', 'can_view')) {
            access_denied();
        }

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'date' => form_error('date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');

            echo json_encode($array);

        } else {
            if (isset($_POST['date'])) {
                $data = array('id' => $id, 'disable_at' => date('Y-m-d', $this->customlib->datetostrtotime($_POST['date'])), 'is_active' => 0);
            } else {
                $data = array('id' => $id, 'is_active' => 0);
            }
            $this->supplier_model->add($data);
            if (isset($_POST['date'])) {
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
                echo json_encode($array);
            } else {
                redirect('admin/supplier/profile/' . $id);
            }
        }
    }

    public function enablesupplier($id){
        $data = array('id' => $id, 'enable_at' => date('Y-m-d'), 'is_active' => 1);
        $this->supplier_model->add($data);
        redirect('admin/supplier/profile/' . $id);
    }
    
    public function disablesupplierlist(){
        if (!$this->rbac->hasPrivilege('disable_supplier', 'can_view')) {
            access_denied();
        }

        if (isset($_POST['role']) && $_POST['role'] != '') {
            $data['search_role'] = $_POST['role'];
        } else {
            $data['search_role'] = "";
        }

        $this->session->set_userdata('top_menu', 'people');
        $this->session->set_userdata('sub_menu', 'admin/supplier/disablesupplierlist');
        $data['title'] = 'Staff Search';
        $data['title']        = 'Supplier List';
        $resultlist           = $this->supplier_model->searchSupplierFullText("","", 0);
        $data['resultlist']   = $resultlist;
        $data['search_text_supplier']     = '';
        $data['selected_value_supplier']  = '';
        $search             = $this->input->post("search");
        $search_text        = $this->input->post('search_text');
        if (isset($search)) {
            if ($search == 'search_full') {
                $this->form_validation->set_rules('text_supplier', $this->lang->line('search_by_keyword'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                } else {
                    $data['searchby']    = "text";
                    $selected_category = $this->input->post('selected_value_supplier');
                    $search_text = trim($this->input->post('text_supplier'));
                    $data['selected_value_supplier'] = $selected_category;
                    $data['search_text_supplier'] = $search_text;
                    $resultlist          = $this->supplier_model->searchSupplierFullText($search_text,$selected_category, 0);
                    $data['resultlist']  = $resultlist;
                    $data['title']       = $this->lang->line('search_details') . ': ' . $data['search_text_supplier'];
                }
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/supplier/disablesupplier', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function deleteById(){
        $paymentdetailsList = json_decode($this->input->post('del_list'));
        if (!empty($paymentdetailsList)) {
            foreach ($paymentdetailsList as $del_key => $del_val) {
                $this->supplier_model->removeSupplierpayment($del_val->id);
            }
            $array = array('status' => 'success','error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
    
    public function editdetailpayment(){
        $supplier_id   = $this->input->post('supplier_id');
        $main_invoice_id   = $this->input->post('main_invoice_id');
        $data['main_invoice_id'] = $main_invoice_id;
        $data['supplier_id'] = $supplier_id;
        $cashpaylist = $this->supplier_model->cashpayByID($main_invoice_id);
        $data['cashpaylist'] = $cashpaylist;
        $data['voucher_type_id'] = $cashpaylist['voucher_type_id'];
        $data['account_mode_id'] = $cashpaylist['par_acc_head_id'];
        $this->load->view('admin/supplier/editpayment', $data);
    }
    
    public function updatecashBySupplier(){
        $this->form_validation->set_rules('paid_types', 'Paid Types', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) { 
            $data = array(
                'paid_types' => form_error('paid_types'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $date_post = $this->input->post('date');
            $date_frmt = str_replace('/', '-', $date_post);
            $payment_mode = $this->input->post('payment_mode');
            $itemsupplierID = $this->input->post('itemsupplierID');
            $data = array(
                'id' => $this->input->post('invoice_id'),
                'supplier_id' => $itemsupplierID,
                //'feetype_id' => $val_fee->feetype_id,
                'paid_types' => $this->input->post('paid_types'),
                'paid_id' => $this->input->post('paid_id'),
                'date' => date('Y-m-d',$this->customlib->datetostrtotime($this->input->post('date'))),
                'paid_amount' => $this->input->post('paid_amount'),
                'par_acc_head_id' => $this->input->post('payment_mode'),
                'voucher_type_id' => $this->input->post('payment_type'),
                //'acc_head_id' => $acc_head_id,
                'note' => $this->input->post('description'),
            );
            $this->supplier_model->updatecashpayment($data);
            // $notification = $this->input->post('sendnotice');
            // if(!empty($notification)){
            //     if (!empty($inster_id)) {
            //         $student_detail = $this->student_model->getByStudentSession($userid);
            //         $send_to     = $student_detail['father_phone'];
            //         $email       = $student_detail['guardian_email'];
            //         $sender_details = array('invoice_id' => $inster_id,'student_session_id' => $userid,  'contact_no' => $send_to, 'email' => $email);
            //         $this->mailsmsconf->mailsms('fee_collect', $sender_details);
            //     }
            // }
            // $id = $userid;
            // $setting_result = $this->setting_model->get();
            // $data['settinglist'] = $setting_result;
            // $itemsupplier_result = $this->supplier_model->get($itemsupplierID);
            // $data['itemsupplier'] = $itemsupplier_result;
            // $cash_debit = $this->supplier_model->getpayableBySupplierID($itemsupplierID);
            // $cash_credit = $this->supplier_model->getpaidBySupplierID($itemsupplierID);
            // $supplierdetailskhoya = $this->supplier_model->getSupplierKhoyaDetailByID($itemsupplierID);
            // $tkhampayable = 0;
            // if(!empty($supplierdetailskhoya)){
            //     foreach($supplierdetailskhoya as $kh_val){
            //         $tkhampayable = $tkhampayable + ($kh_val['khoya_unit_cost'] * $kh_val['khoya_quantity']);
            //     }
            // } 
            // if (!empty($cash_debit['payable_amount'])) {
            //     $debit_amount = $cash_debit['payable_amount'];
            // }else{
            //     $debit_amount = 0;
            // }
            // if (!empty($cash_credit['paid_amount'])) {
            //     $credit_amount = $cash_credit['paid_amount'];
            // }else{
            //     $credit_amount = 0;
            // }
            // $data['khpayable']  = $tkhampayable;
            // $data['payable']    = $debit_amount;
            // $data['paid']       = $credit_amount;
            // $data['feemonthdate']  = date("Y-m-d" ,strtotime($date_frmt));//strtotime($this->input->post('date'))
            // $data['voucherList'] = $this->supplier_model->printcashpayByInsertID($inster_id);
            // $this->load->view('print/printCashPaymentreceipt', $data);
            $array = array('status' => 'success','error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    function getsupplierByID(){
        $supplier_id = $this->input->get("supplier_id");
        $result = $this->supplier_model->get($supplier_id);
        echo json_encode($result);   
    }

}
