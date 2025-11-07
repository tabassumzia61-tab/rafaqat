<?php
class Brands extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/brands');
        $data['title'] = 'Add Brand';
        $data["name"] = "";
        $data["code"] = "";
        $data["slug"] = "";
        $data["description"] = "";
        $resultlist = $this->brands_model->get();
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/brands/brandslist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('brands', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Brand';
        $resultlist = $this->brands_model->get();
        $data["resultlist"] = $resultlist;
        $data["name"] = "";
        $data["code"] = "";
        $data["slug"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->brands_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('code', $this->lang->line('brand').' '.$this->lang->line('code'), 'trim|is_unique[brands.code]|required');
        $this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|is_unique[brands.slug]|alpha_dash');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/brands/brandslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $img_name = $this->media_storage->fileupload("documents", "./uploads/brands/");
            $data = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'code' => $this->input->post('code'),
                'image'   => $img_name,
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->brands_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Brand added successfully</div>');
            redirect('admin/brands/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('brands', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Brands';
        $resultlist = $this->brands_model->get();
        $data["resultlist"] = $resultlist;
        $data['id'] = $id;
        $result = $this->brands_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["code"] = $result["code"];
        $data["slug"] = $result["slug"];
        $data["description"] = $result["description"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->brands_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('code', $this->lang->line('brand').' '.$this->lang->line('code'), 'trim|required');
        if ($this->input->post('code') != $result["code"]) {
            $this->form_validation->set_rules('code', $this->lang->line('brand').' '.$this->lang->line('code'), 'trim|is_unique[brands.code]|required');
        }
        $this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|alpha_dash');
        if ($this->input->post('slug') != $result["slug"]) {
            $this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|is_unique[brands.slug]|alpha_dash');
        }
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/brands/brandslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $img_name = $this->media_storage->fileupload("documents", "./uploads/brands/");
            } else {
                $img_name = $result['image'];
            }
            $data['image'] = $img_name;
            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $this->media_storage->filedelete($result['image'], "uploads/brands");
            }
            $this->brands_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Brands updated successfully</div>');
            redirect('admin/brands/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('brands', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->brands_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Brands Deleted successfully</div>');
        redirect('admin/brands/index');
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

}

?>