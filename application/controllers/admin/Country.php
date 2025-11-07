<?php
class Country extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'Particles');
        $this->session->set_userdata('sub_menu', 'admin/country/index');
        $data['title'] = 'Add Country';
        $data["name"] = "";
        $data["description"] = "";
        $resultlist = $this->country_model->get();
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/country/countrylist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('country', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Country';
        $resultlist = $this->country_model->get();
        $data["resultlist"] = $resultlist; 
        $data["name"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->country_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/country/countrylist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'note' => $this->input->post('description'),
            );
            $this->country_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Country added successfully</div>');
            redirect('admin/country/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('country', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Country';
        $resultlist = $this->country_model->get();
        $data["resultlist"] = $resultlist;
        $data['id'] = $id;
        $result = $this->country_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["description"] = $result["note"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->country_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/country/countrylist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('description'),
            );
            $this->country_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Country updated successfully</div>');
            redirect('admin/country/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('country', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $this->country_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Country Deleted successfully</div>');
        redirect('admin/country/index');
    }

}

?>