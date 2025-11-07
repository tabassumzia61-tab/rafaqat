<?php
class Producttype extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index() {
        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/Producttype');
        $data['title'] = 'Add Product Type';
        $data["name"] = "";
        $data["code"] = "";
        $data["slug"] = "";
        $data["parent"] = "";
        $data["description"] = "";
        $data["producttypelist"] = $this->producttype_model->getParentProductType();
        $resultlist = $this->producttype_model->get();
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/producttype/producttypelist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('product_type', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Product Type';
        $data["producttypelist"] = $this->producttype_model->getParentProductType();
        $resultlist = $this->producttype_model->get();
        $data["resultlist"] = $resultlist;
        $data["name"] = "";
        $data["code"] = "";
        $data["slug"] = "";
        $data["parent"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->producttype_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('code', $this->lang->line('category').' '.$this->lang->line('code'), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|is_unique[categories.slug]|alpha_dash');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/producttype/producttypelist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $img_name = $this->media_storage->fileupload("documents", "./uploads/producttype/");
            $data = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'code' => $this->input->post('code'),
                'parent_id' => $this->input->post('parent'),
                'image'   => $img_name,
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->producttype_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Product Type added successfully</div>');
            redirect('admin/producttype/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('product_type', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Product type';
        $data["producttypelist"] = $this->producttype_model->getParentProductType();
        $resultlist = $this->producttype_model->get();
        $data["resultlist"] = $resultlist;
        $data['id'] = $id;
        $result = $this->producttype_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["code"] = $result["code"];
        $data["slug"] = $result["slug"];
        $data["parent"] = $result["parent_id"];
        $data["description"] = $result["description"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->producttype_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('code', $this->lang->line('category').' '.$this->lang->line('code'), 'trim|required');
        if ($this->input->post('code') != $result["code"]) {
            $this->form_validation->set_rules('code', $this->lang->line('category').' '.$this->lang->line('code'), 'trim|is_unique[categories.code]|required');
        }
        $this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|alpha_dash');
        if ($this->input->post('slug') != $result["slug"]) {
            $this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|is_unique[categories.slug]|alpha_dash');
        }
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/producttype/producttypelist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'code' => $this->input->post('code'),
                'parent_id' => $this->input->post('parent'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $img_name = $this->media_storage->fileupload("documents", "./uploads/producttype/");
            } else {
                $img_name = $result['image'];
            }
            $data['image'] = $img_name;
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $this->media_storage->filedelete($result['image'], "uploads/producttype");
            }
            $this->producttype_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Product Type updated successfully</div>');
            redirect('admin/producttype/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('product_type', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->producttype_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Product Type Deleted successfully</div>');
        redirect('admin/producttype/index');
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

    public function slug(){
        echo $this->customlib->slug($this->input->get('title', true), $this->input->get('type', true));
        exit();
    }

}

?>