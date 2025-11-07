<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Production extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('media_storage');
    }

    public function index(){
        if (!$this->rbac->hasPrivilege('production_products', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'production');
        $this->session->set_userdata('sub_menu', 'products/index');
        $data['title']        = 'Products List';
        $products_result      = $this->production_model->get();
        $data['productslist'] = $products_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/production/productsList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create(){
        if (!$this->rbac->hasPrivilege('production_products', 'can_add')) {
            access_denied();
        }
        $data['title']            = 'Add Products';
        $data['unitlist'] = $this->units_model->get();
        $data['categories'] = $this->categories_model->getAllCategories();
        $data['category'] = $this->input->post('category');
        $data['subcategory'] = $this->input->post('subcategory');
        $this->form_validation->set_rules('name', $this->lang->line('product').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('code', $this->lang->line('product').' '.$this->lang->line('code'), 'is_unique[products.code]|alpha_dash');
        $this->form_validation->set_rules('cost', $this->lang->line('product').' '.$this->lang->line('cost'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('unit', $this->lang->line('product').' '.$this->lang->line('unit'), 'trim|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/production/productsCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $img_name = $this->media_storage->fileupload("documents", "./uploads/saleproduct/");
            $data = array(
                'add_type'          => 'sales',
                'name'              => $this->input->post('name'),
                'code'              => $this->input->post('code'),
                'barcode_symbology' => $this->input->post('barcode_symbology'),
                'price'             => $this->input->post('sale_price'),
                'unit'              => $this->input->post('unit'),
                'alert_quantity'    => $this->input->post('alert_quantity'),
                'second_name'       => $this->input->post('second_name'),
                'category_id'       => $this->input->post('category'),
                'subcategory_id'    => $this->input->post('subcategory'),
                'product_details'   => $this->input->post('product_details'),
                'image'             => $img_name,
                'details'           => $this->input->post('details'),
                'is_active'         => 'yes',
            );
            $products_id = $this->production_model->add($data);
            $variants = $this->input->post('attributes');
            $variant = explode(",", $variants);
            if(!empty($variant)){
                foreach($variant as $val){
                    $datav = array(
                        'product_id'  => $products_id,
                        'name'        => $val
                    );
                    $this->products_model->addvariant($datav);
                }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/production/index');
        }
    }

    public function edit($id){
        if (!$this->rbac->hasPrivilege('production_products', 'can_edit')) {
            access_denied();
        }
        $data['title']        = 'Edit products';
        $data['id']           = $id;
        $data['unitlist']     = $this->units_model->get();
        $data['categories']   = $this->categories_model->getAllCategories();
        $products_result      = $this->production_model->get($id);
        $data['productsdet']     = $products_result;
        $data['category']     = $products_result['category_id'];
        $data['subcategory']  = $products_result['subcategory_id'];
        $this->form_validation->set_rules('name', $this->lang->line('product').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('code', $this->lang->line('product').' '.$this->lang->line('code'), 'is_unique[products.code]|alpha_dash');
        $this->form_validation->set_rules('cost', $this->lang->line('product').' '.$this->lang->line('cost'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('unit', $this->lang->line('product').' '.$this->lang->line('unit'), 'trim|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/production/productsEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'                => $id,
                'name'              => $this->input->post('name'),
                'code'              => $this->input->post('code'),
                'barcode_symbology' => $this->input->post('barcode_symbology'),
                'price'             => $this->input->post('sale_price'),
                'unit'              => $this->input->post('unit'),
                'alert_quantity'    => $this->input->post('alert_quantity'),
                'second_name'       => $this->input->post('second_name'),
                'category_id'       => $this->input->post('category'),
                'subcategory_id'    => $this->input->post('subcategory'),
                'product_details'   => $this->input->post('product_details'),
                'details'           => $this->input->post('details'),
            );
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $img_name = $this->media_storage->fileupload("documents", "./uploads/saleproduct/");
            } else {
                $img_name = $products_result['image'];
            }
            $data['image'] = $img_name;
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $this->media_storage->filedelete($products_result['image'], "uploads/saleproduct/");
            }
            $this->production_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/production/index');
        }
    }

    public function delete($id){
        if (!$this->rbac->hasPrivilege('production_products', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Products List';
        $this->production_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/production/index');
    }

    public function assign_branch($branch_id = null)
    {
        if (!$this->rbac->hasPrivilege('assign_branch', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'production');
        $this->session->set_userdata('sub_menu', 'products/index');
        $data['title']        = 'Assign products to branches';
        // $products_result      = $this->production_model->get();
        // $data['productslist'] = $products_result;
        // if (!empty($branch_id)) {
        //     $brach_id = $branch_id;
        // }else{
        //     $brach_id = $this->customlib->getBranchID();
        // }
        $brach_id = 0;
        $branch_result               = $this->branchsettings_model->get();
        $data["branchlist"]          = $branch_result;

        $submit                      = $this->input->post("search");
        $data["resultlist"] = array();
        if (isset($submit) && $submit == "search") {
            if (!empty($this->input->post('brach_id'))) {
                $brach_id = $this->input->post('brach_id');
                $products_result      = $this->production_model->get();
                $data['productslist'] = $products_result;
            }
           
        }
        if ($branch_id) {
            $brach_id = $branch_id;
        }
        if ($brach_id) {
            $searchBranchProducts = $this->production_model->searchBranchProducts($brach_id);
            //echo "<pre>"; print_r($searchBranchProducts); exit;
            $data["resultlist"] = $searchBranchProducts;
        }

        $data["brach_id"]            = $brach_id;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/production/assignBranch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_branch_products($branch_id = null)
    {
        if($branch_id > 0){
            $product_ids = $this->input->post('product_ids');
               // echo "<pre>"; print_r($product_ids); exit;
            $this->production_model->delete_branch_products($branch_id);
            if($product_ids){
                
            foreach($product_ids as $product_id){
                $product_price = $this->input->post('product_prices_'.$product_id);
                if($product_price){
                    $data["branch_id"]            = $branch_id;
                    $data["product_id"]            = $product_id;
                    $data["product_price"]            = $product_price;
                    $this->production_model->assign_branch_products($data);
                
                }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/production/assign_branch/'.$branch_id);

            }else{
                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
                redirect('admin/production/assign_branch/'.$branch_id);
            }
        }else{
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Branch is not selected</div>');
            redirect('admin/production/assign_branch');
        }

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

    public function getSubCategories() {
        $category_id = $this->input->get('category');
        if ($rows = $this->production_model->getSubCategories($category_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }

}
