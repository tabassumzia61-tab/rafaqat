<?php
class Variants extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index() {

        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/variants');
        $data['title'] = 'Add Variant';
        $data["name"] = "";
        $data["code"] = "";
        $data["description"] = "";
        $resultlist = $this->variants_model->get();
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/variants/variantslist', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('variants', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Variant';
        $resultlist = $this->variants_model->get();
        $data["resultlist"] = $resultlist;
        $data["name"] = "";
        $data["code"] = "";
        $data["description"] = "";
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->variants_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/variants/variantslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->variants_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Variant added successfully</div>');
            redirect('admin/variants/index');
        }
    }

    function edit($id = null) {
        if (!$this->rbac->hasPrivilege('variants', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Variant';
        $resultlist = $this->variants_model->get();
        $data["resultlist"] = $resultlist;
        $data['id'] = $id;
        $result = $this->variants_model->get($id);
        $data["result"] = $result;
        $data["name"] = $result["name"];
        $data["code"] = $result["code"];
        $data["description"] = $result["description"];
        $this->form_validation->set_rules(
                'name', 'Name', array(
            'required',
            array('check_exists', array($this->variants_model, 'check_exists'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/variants/variantslist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
                'is_active' => 'yes'
            );
            $this->variants_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Variant updated successfully</div>');
            redirect('admin/variants/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('variants', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->variants_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Variant Deleted successfully</div>');
        redirect('admin/variants/index');
    }

}

?>