<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Products extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('products', 'can_view'))
        {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'purchases');
        $this->session->set_userdata('sub_menu', 'products/index.html');

        $data['title']        = 'Supplier List';
 // start pagination
        $search     = $this->input->get('search', true);
        $page       = (int) $this->input->get('per_page') ?? 0;

        $limit      = 10;
        $offset     = ($page && $page > 0) ? $page : 0;

		$total_rows = $this->products_model->count_products($search);
        $resultlist = $this->products_model->get_products($limit, $offset, $search);

        // Build pagination config
        $config = array();
        $config['base_url']             = base_url('admin/products.html'); 
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $limit;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'per_page';

        // Keep the search parameter in the pagination links
        if (!empty($search))
        {
            $config['suffix']       = '&search=' . urlencode($search);
            $config['first_url']    = $config['base_url'] . '?search=' . urlencode($search);
        }

        // Basic bootstrap 4/5 friendly pagination markup
        $config['full_tag_open']    = '<nav aria-label="Page navigation"><ul class="pagination justify-content-end">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '</span></li>';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['attributes']       = array('class' => 'page-link');
    
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data['pagination_links']   = $this->pagination->create_links();
        $data['search']             = $search;
        $data['total_rows']         = $total_rows;
        // end pagination

        $data['productslist']       = $resultlist;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/products/productsList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('products', 'can_add'))
        {
            access_denied();
        }

        $data['title']           = 'Add Product';

        $this->load->view('layout/header', $data);
        $this->load->view('admin/products/productsCreate', $data);
        $this->load->view('layout/footer', $data);


        $data['unitlist']        = $this->units_model->get();
        $data['categories']      = $this->categories_model->getAllCategories();
        $data['producttype']     = $this->producttype_model->getAllProductType();
        $data['tax_rates']       = $this->taxrates_model->get();
        $data['sale_acc']        = $this->accounts_model->getAccountsHeadBytypeIDByID(12,1);
        $data['cost_sale_acc']   = $this->accounts_model->getAccountsHeadBytypeIDByID(14,1);
        $lastcode = $this->products_model->getlastrecord();
        if (!empty($lastcode)) {
            $codeno = $lastcode['code'] + 1;
        }else{
            $codeno = 1;
        }
        $data['codeno'] = $codeno;
        $data['product_type']    = $this->input->post('product_type');
        $data['product_subtype'] = $this->input->post('product_subtype');
        $data['category']        = $this->input->post('category');
        $data['subcategory']     = $this->input->post('subcategory');
        $this->form_validation->set_rules('name', $this->lang->line('product').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('code', $this->lang->line('product').' '.$this->lang->line('code'), 'is_unique[products.code]|alpha_dash');
        $this->form_validation->set_rules('product_type', $this->lang->line('product_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('product_subtype', $this->lang->line('product_subtype'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('cost', $this->lang->line('product').' '.$this->lang->line('cost'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('unit', $this->lang->line('product').' '.$this->lang->line('unit'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/products/productsCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $img_name = $this->media_storage->fileupload("documents", "./uploads/puproduct/");
            $data = array(
                'product_type'          => $this->input->post('product_type'),
                'product_subtype'       => $this->input->post('product_subtype'),
                'name'                  => $this->input->post('name'),
                'code'                  => $this->input->post('code'),
                'barcode_symbology'     => $this->input->post('barcode_symbology'),
                'cost'                  => $this->input->post('cost'),
                'price'                 => $this->input->post('sale_price'),
                'unit'                  => $this->input->post('unit'),
                'alert_quantity'        => $this->input->post('alert_quantity'),
                'second_name'           => $this->input->post('second_name'),
                'category_id'           => $this->input->post('category'),
                'subcategory_id'        => $this->input->post('subcategory'),
                'product_details'       => $this->input->post('product_details'),
                'image'                 => $img_name,
                'details'               => $this->input->post('details'),
                'initial_quantity'      => $this->input->post('initial_quantity'),
                'initial_quantity'      => $this->input->post('initial_quantity'),
                'tax_method'            => $this->input->post('tax_method'),
                'tax_rate'              => $this->input->post('tax_rate'),
                'acc_type_id'           => 5,
                'is_active'             => 'yes',
            );
            $products_id = $this->products_model->add($data);
            if(!empty($products_id)){
                $resultnew = $this->accounts_model->getnewaccount(12);
                if(!empty($resultnew)){
                    $acc_tot = $this->products_model->getTotalaccounthead(12);
                    if(!empty($acc_tot)){
                        $code = $resultnew['code'].($acc_tot->total_code_no + 1 );
                    }else{
                        $code = $resultnew['code'].'1';
                    }
                    $sdata = array(
                        'accounts_head_id' => 4,
                        'new_accounts_id' => 12,
                        'item_id' => $products_id,
                        'code' => $code,
                        'name' => 'Sales Of '.$this->input->post('name'),
                        'is_active' => 'yes',
                    );
                    $sh = $this->products_model->addaccountshead($sdata);
                    $sudata = array(
                        'id'      => $products_id,
                        'acc_head_sales_id'      => $sh,
                    );
                    $this->products_model->add($sudata);
                }
                $resultnew = $this->accounts_model->getnewaccount(13);
                if(!empty($resultnew)){
                    $acc_tot = $this->products_model->getTotalaccounthead(13);
                    if(!empty($acc_tot)){
                        $rcode = $resultnew['code'].($acc_tot->total_code_no + 1 );
                    }else{
                        $rcode = $resultnew['code'].'1';
                    }
                    $srdata = array(
                        'accounts_head_id' => 4,
                        'new_accounts_id' => 13,
                        'item_id' => $products_id,
                        'code' => $rcode,
                        'name' => 'Sales Returns Of '.$this->input->post('name'),
                        'is_active' => 'yes',
                    );
                    $srh = $this->products_model->addaccountshead($srdata);
                    $srudata = array(
                        'id'      => $products_id,
                        'acc_head_sales_returns_id'      => $srh,
                    );
                    $this->products_model->add($srudata);
                }
                $resultnew = $this->accounts_model->getnewaccount(15);
                if(!empty($resultnew)){
                    $acc_tot = $this->products_model->getTotalaccounthead(15);
                    if(!empty($acc_tot)){
                        $pcode = $resultnew['code'].($acc_tot->total_code_no + 1 );
                    }else{
                        $pcode = $resultnew['code'].'1';
                    }
                    $pdata = array(
                        'accounts_head_id' => 5,
                        'new_accounts_id' => 15,
                        'item_id' => $products_id,
                        'code' => $pcode,
                        'name' => 'Purchases Of '.$this->input->post('name'),
                        'is_active' => 'yes',
                    );
                    $ph = $this->products_model->addaccountshead($pdata);
                    $pudata = array(
                        'id'      => $products_id,
                        'acc_head_purchases_id' => $ph,
                    );
                    $this->products_model->add($pudata);
                }
                $resultnew = $this->accounts_model->getnewaccount(16);
                if(!empty($resultnew)){
                    $acc_tot = $this->products_model->getTotalaccounthead(16);
                    if(!empty($acc_tot)){
                        $prcode = $resultnew['code'].($acc_tot->total_code_no + 1 );
                    }else{
                        $prcode = $resultnew['code'].'1';
                    }
                    $prdata = array(
                        'accounts_head_id' => 5,
                        'new_accounts_id' => 16,
                        'item_id' => $products_id,
                        'code' => $prcode,
                        'name' => 'Purchases Returns Of '.$this->input->post('name'),
                        'is_active' => 'yes',
                    );
                    $prh = $this->products_model->addaccountshead($prdata);
                    $prudata = array(
                        'id'      => $products_id,
                        'acc_head_purchases_returns_id' => $prh,
                    );
                    $this->products_model->add($prudata);
                }
                $resultnew = $this->accounts_model->getnewaccount(17);
                if(!empty($resultnew)){
                    $acc_tot = $this->products_model->getTotalaccounthead(17);
                    if(!empty($acc_tot)){
                        $coscode = $resultnew['code'].($acc_tot->total_code_no + 1 );
                    }else{
                        $coscode = $resultnew['code'].'1';
                    }
                    $cosdata = array(
                        'accounts_head_id' => 5,
                        'new_accounts_id' => 17,
                        'item_id' => $products_id,
                        'code' => $coscode,
                        'name' => 'Cost of Sales Of '.$this->input->post('name'),
                        'is_active' => 'yes',
                    );
                    $cos = $this->products_model->addaccountshead($cosdata);
                    $cosudata = array(
                        'id'      => $products_id,
                        'acc_head_cost_sale_id' => $cos,
                    );
                    $this->products_model->add($cosudata);
                }
                
            }
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
            redirect('admin/products/index');
        }
    }

    public function edit($id){
        if (!$this->rbac->hasPrivilege('products', 'can_edit')) {
            access_denied();
        }
        $data['title']        = 'Edit products';
        $data['id']           = $id;
        $data['unitlist']     = $this->units_model->get();
        $data['categories']   = $this->categories_model->getAllCategories();
        $data['producttype']  = $this->producttype_model->getAllProductType();
        $data['tax_rates']    = $this->taxrates_model->get();
        $data['sale_acc']        = $this->accounts_model->getAccountsHeadBytypeIDByID(12,1);
        $data['cost_sale_acc']   = $this->accounts_model->getAccountsHeadBytypeIDByID(14,1);
        $products_result      = $this->products_model->get($id);
        $data['productsdet']     = $products_result;
        $data['product_type']    = $products_result['product_type'];
        $data['product_subtype'] = $products_result['product_subtype'];
        $data['category']     = $products_result['category_id'];
        $data['subcategory']  = $products_result['subcategory_id'];
        $this->form_validation->set_rules('name', $this->lang->line('product').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('product_type', $this->lang->line('product_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('product_subtype', $this->lang->line('product_subtype'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('code', $this->lang->line('product').' '.$this->lang->line('code'), 'is_unique[products.code]|alpha_dash');
        //$this->form_validation->set_rules('cost', $this->lang->line('product').' '.$this->lang->line('cost'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('unit', $this->lang->line('product').' '.$this->lang->line('unit'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/products/productsEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'                    => $id,
                'product_type'          => $this->input->post('product_type'),
                'product_subtype'       => $this->input->post('product_subtype'),
                'name'                  => $this->input->post('name'),
                'code'                  => $this->input->post('code'),
                'barcode_symbology'     => $this->input->post('barcode_symbology'),
                'cost'                  => $this->input->post('cost'),
                'price'                 => $this->input->post('sale_price'),
                'unit'                  => $this->input->post('unit'),
                'alert_quantity'        => $this->input->post('alert_quantity'),
                'second_name'           => $this->input->post('second_name'),
                'category_id'           => $this->input->post('category'),
                'subcategory_id'        => $this->input->post('subcategory'),
                'product_details'       => $this->input->post('product_details'),
                'details'               => $this->input->post('details'),
                'initial_quantity'      => $this->input->post('initial_quantity'),
                'initial_quantity_date' => $this->input->post('initial_quantity_date'),
                'tax_method'            => $this->input->post('tax_method'),
                'tax_rate'              => $this->input->post('tax_rate'),
            );
            $sdata = array(
                'id' => $products_result['acc_head_sales_id'],
                'accounts_head_id' => 4,
                'new_accounts_id' => 12,
                'item_id' => $id,
                'name' => 'Sales Of '.$this->input->post('name'),
            );
            $this->products_model->addaccountshead($sdata);
            $srdata = array(
            	'id' => $products_result['acc_head_sales_returns_id'],
                'accounts_head_id' => 4,
                'new_accounts_id' => 13,
                'item_id' => $id,
                'name' => 'Sales Returns Of '.$this->input->post('name')
            );
            $this->products_model->addaccountshead($srdata);
            $pdata = array(
            	'id' => $products_result['acc_head_purchases_id'],
                'accounts_head_id' => 5,
                'new_accounts_id' => 15,
                'item_id' => $id,
                'name' => 'Purchases Of '.$this->input->post('name')
            );
            $this->products_model->addaccountshead($pdata);
            $prdata = array(
            	'id' => $products_result['acc_head_purchases_returns_id'],
                'accounts_head_id' => 5,
                'new_accounts_id' => 16,
                'item_id' => $id,
                'name' => 'Purchases Returns Of '.$this->input->post('name')
            );
            $this->products_model->addaccountshead($prdata);
            $cosdata = array(
                'id' => $products_result['acc_head_cost_sale_id'],
                'accounts_head_id' => 5,
                'new_accounts_id' => 17,
                'item_id' => $id,
                'name' => 'Cost of Sales Of '.$this->input->post('name')
            );
            $this->products_model->addaccountshead($cosdata);
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $img_name = $this->media_storage->fileupload("documents", "./uploads/puproduct/");
            } else {
                $img_name = $products_result['image'];
            }
            $data['image'] = $img_name;
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $this->media_storage->filedelete($products_result['image'], "uploads/puproduct/");
            }
            $this->products_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/products/index');
        }
    }

    public function delete()
    {
        $product_id = $this->input->get('id');
        
        if (!$this->rbac->hasPrivilege('products', 'can_delete')) {
            access_denied();
        }

        $data['title'] = 'Products List';

        $this->products_model->remove($product_id);

        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/products.html');
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
        if ($rows = $this->products_model->getSubCategories($category_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }
    
    public function getSubProducttype() {
        $product_type_id = $this->input->get('product_type');
        if ($rows = $this->products_model->getSubProducttype($product_type_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }

}
