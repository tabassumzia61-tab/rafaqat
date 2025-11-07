<?php
class Designation extends Admin_Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->session->set_userdata('top_menu', 'Particles');
        $this->session->set_userdata('sub_menu', 'admin/designation/index');
        $data["title"] = $this->lang->line('add')." ".$this->lang->line('designation');
        $designation = $this->designation_model->get();
        $data["designation"] = $designation;
        $this->form_validation->set_rules('type', $this->lang->line('name'), array('required',array('check_exists', array($this->designation_model, 'valid_designation'))));
        if ($this->form_validation->run()) {
            
            $type = $this->input->post("type");
            $designationid = $this->input->post("designationid");
            $status = $this->input->post("status");
            if (empty($designationid)) {
                if (!$this->rbac->hasPrivilege('designation', 'can_add')) {
                    access_denied();
                }
            } else {
                if (!$this->rbac->hasPrivilege('designation', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($designationid)) {
                $data = array('designation' => $type, 'is_active' => 'yes','id' => $designationid);
            } else {
                $data = array('designation' => $type, 'is_active' => 'yes');
            }
            $insert_id = $this->designation_model->addDesignation($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$this->lang->line('success_message').'</div>');
            redirect("admin/designation/index");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/designation/designation", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($id) {
        $data["title"] = $this->lang->line('edit')." ".$this->lang->line('designation');
        $result = $this->designation_model->get($id);
        $data["result"] = $result;
        $designation = $this->designation_model->get();
        $data["designation"] = $designation;
        $this->load->view("layout/header");
        $this->load->view("admin/designation/designation", $data);
        $this->load->view("layout/footer");
    }

    function delete($id) {
        $this->designation_model->deleteDesignation($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">'.$this->lang->line('delete_message').'</div>');
        redirect('admin/designation/index');
    }
}
?>