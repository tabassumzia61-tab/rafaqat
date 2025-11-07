<?php
class Area extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->session->set_userdata('top_menu', 'Particles');
        $this->session->set_userdata('sub_menu', 'admin/area/index');
        $data['title'] = 'Add Area';
        $data["name"] = "";
        $data["country_id"] = "";
        $data["state_id"] = "";
        $data["city_id"] = "";
        $data["description"] = "";
        $resultlist = $this->area_model->getCountryByStatesByCityByArea();
        $data["resultlist"] = $resultlist;
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/area/arealist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('area', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Area';
        $resultlist = $this->area_model->getCountryByStatesByCityByArea();
        $data["resultlist"] = $resultlist;
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $data["country_id"] = "";
        $data["city_id"] = "";
        $data["state_id"] = "";
        $data["name"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('country_id', $this->lang->line('country'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('state_id', $this->lang->line('state'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('city_id', $this->lang->line('city'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules(
        //         'name', 'Name', array(
        //     'required',
        //     array('check_exists', array($this->area_model, 'check_exists'))
        //         )
        // );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/area/arealist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'city_id' => $this->input->post('city_id'),
                'note' => $this->input->post('description'),
            );
            $this->area_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Area added successfully</div>');
            redirect('admin/area/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('area', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit area';
        $resultlist = $this->area_model->getCountryByStatesByCityByArea();
        $data["resultlist"] = $resultlist;
        $countrylist = $this->country_model->get();
        $data["countrylist"] = $countrylist;
        $data['id'] = $id;
        $result = $this->area_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["state_id"] = $result["state_id"];
        $data["country_id"] = $result["country_id"];
        $data["city_id"] = $result["city_id"];
        $data["description"] = $result["note"];
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('country_id', $this->lang->line('country'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('state_id', $this->lang->line('state'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('city_id', $this->lang->line('city'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules(
        //         'name', 'Name', array(
        //     'required',
        //     array('check_exists', array($this->area_model, 'check_exists'))
        //         )
        // );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/area/arealist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'city_id' => $this->input->post('city_id'),
                'note' => $this->input->post('description'),
            );
            $this->area_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Area updated successfully</div>');
            redirect('admin/area/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('area', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $this->area_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Area Deleted successfully</div>');
        redirect('admin/area/index');
    }



    function getstateByCountry(){
        $country_id = $this->input->get("country_id");
        $result = $this->area_model->getstateByCountryID($country_id);
        echo json_encode($result);   
    }

    function getCityByCountryState(){
        $country_id = $this->input->get("country_id");
        $state_id = $this->input->get("state_id");
        $result = $this->area_model->getCityByCountryStateID($country_id,$state_id);
        echo json_encode($result);   
    }

    function getAreaByCityByCountryState(){
        $country_id = $this->input->get("country_id");
        $state_id = $this->input->get("state_id");
        $city_id = $this->input->get("city_id");
        $result = $this->area_model->getAreaCityByCountryStateID($country_id,$state_id,$city_id);
        echo json_encode($result);   
    }

    function getAreaByCity(){
        $city_id = $this->input->get("city_id");
        $result = $this->area_model->getAreaByCity($city_id);
        echo json_encode($result);   
    }

}

?>