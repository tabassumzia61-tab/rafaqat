<?php
class State extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'Particles');
        $this->session->set_userdata('sub_menu', 'admin/state/index');
        $data['title'] = 'Add State';
        $data["name"] = "";
        $data["description"] = "";
        $data["country_id"] = "";
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $resultcountrylist = $this->state_model->getCountryByStates();
        $data["resultcountry"] = $resultcountrylist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/state/statelist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('state', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add state';
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $resultcountrylist = $this->state_model->getCountryByStates();
        $data["resultcountry"] = $resultcountrylist;
        $data["name"] = "";
        $data["description"] = "";
        $data["country_id"] = "";
        $this->form_validation->set_rules('country_id', $this->lang->line('country'), 'trim|required|xss_clean');
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->state_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/state/statelist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'country_id' => $this->input->post('country_id'),
                'note' => $this->input->post('description'),
            );
            $this->state_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">State added successfully</div>');
            redirect('admin/state/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('state', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit state';
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $resultcountrylist = $this->state_model->getCountryByStates();
        $data["resultcountry"] = $resultcountrylist;
        $data['id'] = $id;
        $result = $this->state_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["description"] = $result["note"];
        $data["country_id"] = $result["country_id"];
        $this->form_validation->set_rules('country_id', $this->lang->line('country'), 'trim|required|xss_clean');
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->state_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/state/statelist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'country_id' =>  $this->input->post('country_id'),
                'note' => $this->input->post('description'),
            );
            $this->state_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">State updated successfully</div>');
            redirect('admin/state/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('state', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $this->state_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">State Deleted successfully</div>');
        redirect('admin/state/index');
    }

}

?>