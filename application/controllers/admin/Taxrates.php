<?php
class Taxrates extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index() {
        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/taxrates');
        $data['title'] = 'Add Tax Rate';
        $data["name"] = "";
        $data["code"] = "";
        $data["rate"] = "";
        $data["type"] = "";
        $data["description"] = "";
        $resultlist = $this->taxrates_model->get();
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/taxrates/taxrateslist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('tax_rates', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Tax Rate';
        $resultlist = $this->taxrates_model->get();
        $data["resultlist"] = $resultlist;
        $data["name"] = "";
        $data["code"] = "";
        $data["rate"] = "";
        $data["type"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->taxrates_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('rate', $this->lang->line('rate'), 'trim|required');
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/taxrates/taxrateslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'rate' => $this->input->post('rate'),
                'type' => $this->input->post('type'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->taxrates_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Tax Rate added successfully</div>');
            redirect('admin/taxrates/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('tax_rates', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Tax Rate';
        $resultlist = $this->taxrates_model->get();
        $data["resultlist"] = $resultlist;
        $data['id'] = $id;
        $result = $this->taxrates_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["code"] = $result["code"];
        $data["rate"] = $result["rate"];
        $data["type"] = $result["type"];
        $data["description"] = $result["description"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->taxrates_model, 'check_exists'))
                )
        );
        $this->form_validation->set_rules('rate', $this->lang->line('rate'), 'trim|required');
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/taxrates/taxrateslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'rate' => $this->input->post('rate'),
                'type' => $this->input->post('type'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->taxrates_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Tax Rate updated successfully</div>');
            redirect('admin/taxrates/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('taxrates', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->taxrates_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Tax Rate Deleted successfully</div>');
        redirect('admin/taxrates/index');
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