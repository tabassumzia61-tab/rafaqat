<?php
class Warehouses extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/warehouses');
        $data['title'] = 'Add Warehouse';
        $data["name"] = "";
        $data["code"] = "";
        $data["email"] = "";
        $data["phone"] = "";
        $data["address"] = "";
        $data["description"] = "";
        $resultlist = $this->warehouses_model->get();
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/warehouses/warehouseslist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('warehouses', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Warehouse';
        $resultlist = $this->warehouses_model->get();
        $data["resultlist"] = $resultlist;
        $data["name"] = "";
        $data["code"] = "";
        $data["email"] = "";
        $data["phone"] = "";
        $data["address"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->warehouses_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/warehouses/warehouseslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'note' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->warehouses_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Warehouse added successfully</div>');
            redirect('admin/warehouses/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('warehouses', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Warehouse';
        $resultlist = $this->warehouses_model->get();
        $data["resultlist"] = $resultlist;
        $data['id'] = $id;
        $result = $this->warehouses_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["code"] = $result["code"];
        $data["email"] = $result["email"];
        $data["phone"] = $result["phone"];
        $data["address"] = $result["address"];
        $data["description"] = $result["note"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->warehouses_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/warehouses/warehouseslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'note' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->warehouses_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Warehouse updated successfully</div>');
            redirect('admin/warehouses/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('warehouses', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->warehouses_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Warehouse Deleted successfully</div>');
        redirect('admin/warehouses/index');
    }

}

?>