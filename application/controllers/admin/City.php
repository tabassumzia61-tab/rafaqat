<?php
class City extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'Particles');
        $this->session->set_userdata('sub_menu', 'admin/city/index');
        $data['title'] = 'Add City';
        $data["name"] = "";
        $data["country_id"] = "";
        $data["state_id"] = "";
        $data["description"] = "";
        $resultlist = $this->city_model->getCountryByStatesByCity();
        $data["resultlist"] = $resultlist;
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $statelist = $this->state_model->get();
        $data["statelist"] = $statelist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/city/citylist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('city', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add city';
        $resultlist = $this->city_model->getCountryByStatesByCity();
        $data["resultlist"] = $resultlist;
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $statelist = $this->state_model->get();
        $data["statelist"] = $statelist;
        $data["country_id"] = "";
        $data["state_id"] = "";
        $data["name"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules('country_id', $this->lang->line('country'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('state_id', $this->lang->line('state'), 'trim|required|xss_clean');
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->city_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/city/citylist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'note' => $this->input->post('description'),
            );
            $this->city_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">City added successfully</div>');
            redirect('admin/city/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('city', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit city';
        $resultlist = $this->city_model->getCountryByStatesByCity();
        $data["resultlist"] = $resultlist;
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $statelist = $this->state_model->get();
        $data["statelist"] = $statelist;
        $data['id'] = $id;
        $result = $this->city_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["state_id"] = $result["state_id"];
        $data["country_id"] = $result["country_id"];
        $data["description"] = $result["note"];
        $this->form_validation->set_rules('country_id', $this->lang->line('country'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('state_id', $this->lang->line('state'), 'trim|required|xss_clean');
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->city_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/city/citylist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'note' => $this->input->post('description'),
            );
            $this->city_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">City updated successfully</div>');
            redirect('admin/city/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('city', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $this->city_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">City Deleted successfully</div>');
        redirect('admin/city/index');
    }

}

?>