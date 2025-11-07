<?php
class Department extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->session->set_userdata('top_menu', 'Particles');
        $this->session->set_userdata('sub_menu', 'admin/department/department');
        $DepartmentTypes = $this->department_model->getDepartmentType();
        $data["departmenttype"] = $DepartmentTypes;
        $departmenttypeid = $this->input->post("departmenttypeid");
        $this->form_validation->set_rules('type', $this->lang->line('name'), array('required',array('check_exists', array($this->department_model, 'valid_department'))));
        $data["title"] =$this->lang->line('add')." ".$this->lang->line('department');
        if ($this->form_validation->run()) {
            $type = $this->input->post("type");
            $departmenttypeid = $this->input->post("departmenttypeid");
            $status = $this->input->post("status");
            if (empty($departmenttypeid)) {

                if (!$this->rbac->hasPrivilege('department', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('department', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($departmenttypeid)) {
                $data = array('department_name' => $type, 'is_active' => 'yes', 'id' => $departmenttypeid);
            } else {

                $data = array('department_name' => $type,'is_active' => 'yes');
            }
            $insert_id = $this->department_model->addDepartmentType($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$this->lang->line('success_message').'</div>');
            redirect("admin/department/index");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/department/departmentType", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($id) {
        $result = $this->department_model->getDepartmentType($id);
        $data["result"] = $result;
        $data["title"] = $this->lang->line('edit')." ".$this->lang->line('department');
        $departmentTypes = $this->department_model->getDepartmentType();
        $data["departmenttype"] = $departmentTypes;
        $this->load->view("layout/header");
        $this->load->view("admin/department/departmentType", $data);
        $this->load->view("layout/footer");
    }

    function delete($id) {
        $this->department_model->deleteDepartment($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">'.$this->lang->line('delete_message').'</div>');
        redirect('admin/department/index');
    }

}

?>