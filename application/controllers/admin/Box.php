<?php
class Box extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/box');
        $data['title'] = 'Add Box';
        $data["name"] = "";
        $data["description"] = "";
        $resultlist = $this->box_model->get();
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/box/boxlist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('box', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Box';
        $resultlist = $this->box_model->get();
        $data["resultlist"] = $resultlist;
        $data["name"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->box_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/box/boxlist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->box_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Box added successfully</div>');
            redirect('admin/box/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('box', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Box';
        $resultlist = $this->box_model->get();
        $data["resultlist"] = $resultlist;
        $data['id'] = $id;
        $result = $this->box_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["description"] = $result["description"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->box_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/box/boxlist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->box_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Box updated successfully</div>');
            redirect('admin/box/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('box', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->box_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Box Deleted successfully</div>');
        redirect('admin/box/index');
    }

}

?>