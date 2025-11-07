<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branchsettings extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'branchsettings/index');
        $data['title'] = 'Branch Setting';
        $branch_result = $this->branchsettings_model->get();
        $data['branchlist'] = $branch_result;
        $timezoneList = $this->customlib->timezone_list();
        $language_result = $this->language_model->get();
        $month_list = $this->customlib->getMonthList();
        $data['languagelist'] = $language_result;
        $data['timezoneList'] = $timezoneList;
        $data['monthList'] = $month_list;
        $dateFormat = $this->customlib->getDateFormat();
        $currency = $this->customlib->getCurrency();
        $data['dateFormatList'] = $dateFormat;
        $data['currencyList'] = $currency;
        $country = $this->country_model->get();
        $data['countrylist'] = $country;
        $data['name'] = '';
        $data['code'] = '';
        $data['phone'] = '';
        $data['email'] = '';
        $data['regd_date'] = '';
        $data['address'] = '';
        $data['country_id'] = '';
        $data['state_id'] = '';
        $data['city_id'] = '';
        $data['area_id'] = '';
        $this->load->view('layout/header', $data);
        $this->load->view('branch/branchList', $data);
        $this->load->view('layout/footer', $data);
    }

    function create() {
        if (!$this->rbac->hasPrivilege('multi_branch', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Branch Setting';
        $data['name'] = '';
        $data['code'] = '';
        $data['phone'] = '';
        $data['email'] = '';
        $data['regd_date'] = '';
        $data['address'] = '';
        $data['country_id'] = '';
        $data['state_id'] = '';
        $data['city_id'] = '';
        $data['area_id'] = '';
        $branch_result = $this->branchsettings_model->get();
        $data['branchlist'] = $branch_result;
        $timezoneList = $this->customlib->timezone_list();
        $language_result = $this->language_model->get();
        $month_list = $this->customlib->getMonthList();
        $data['languagelist'] = $language_result;
        $data['timezoneList'] = $timezoneList;
        $data['monthList'] = $month_list;
        $dateFormat = $this->customlib->getDateFormat();
        $currency = $this->customlib->getCurrency();
        $data['dateFormatList'] = $dateFormat;
        $data['currencyList'] = $currency;
        $country = $this->country_model->get();
        $data['countrylist'] = $country;
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('regd_date', 'Register Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('country_id', 'Country', 'trim|required|xss_clean');
        $this->form_validation->set_rules('state_id', 'State', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city_id', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('area_id', 'Area', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_lang_id', 'Language', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_timezone', 'timezone', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_date_format', 'Date Format', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_currency', 'Currency', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_currency_symbol', 'Currency Symbol', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('royalty', 'Royalty', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('website_url', 'Website URL', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('campus_status', 'Status', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('sch_is_rtl', 'RTL', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('theme', 'Theme', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
            $this->load->view('layout/header', $data);
            $this->load->view('branch/branchList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'code' => $this->input->post('code'),
                'address' => $this->input->post('address'),
                'email' => $this->input->post('email'),
                'regd_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('regd_date'))),
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'city_id' => $this->input->post('city_id'),
                'area_id' => $this->input->post('area_id'),
                // 'royalty' => $this->input->post('royalty'),
                // 'websiteurl' => $this->input->post('website_url'),
                'is_active' => 'no',
                'is_parent' => '1',
            );
            $this->branchsettings_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Branch added successfully</div>');
            redirect('branchsettings/index');
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('multi_branch', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Branch Setting';
        $data['id'] = $id;
        $branch_result = $this->branchsettings_model->get();
        $result = $this->branchsettings_model->get($id);
        $data['branchlist'] = $branch_result;
        $data['name'] = $result['name'];
        $data['code'] = $result['code'];
        $data['phone'] = $result['phone'];
        $data['email'] = $result['email'];
        $data['regd_date'] = $result['regd_date'];
        $data['address'] = $result['address'];
        $data['country_id'] = $result['country_id'];
        $data['state_id'] = $result['state_id'];
        $data['city_id'] = $result['city_id'];
        $data['area_id'] = $result['area_id'];
        $timezoneList = $this->customlib->timezone_list();
        $language_result = $this->language_model->get();
        $month_list = $this->customlib->getMonthList();
        $data['languagelist'] = $language_result;
        $data['timezoneList'] = $timezoneList;
        $data['monthList'] = $month_list;
        $dateFormat = $this->customlib->getDateFormat();
        $currency = $this->customlib->getCurrency();
        $data['dateFormatList'] = $dateFormat;
        $data['currencyList'] = $currency;
        $country = $this->country_model->get();
        $data['countrylist'] = $country;
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('regd_date', 'Register Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('country_id', 'Country', 'trim|required|xss_clean');
        $this->form_validation->set_rules('state_id', 'State', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city_id', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('area_id', 'Area', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_lang_id', 'Language', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_timezone', 'timezone', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_date_format', 'Date Format', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_currency', 'Currency', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('sch_currency_symbol', 'Currency Symbol', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('royalty', 'Royalty', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('website_url', 'Website URL', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('campus_status', 'Status', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('sch_is_rtl', 'RTL', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('theme', 'Theme', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('branch/branchList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'code' => $this->input->post('code'),
                'address' => $this->input->post('address'),
                'email' => $this->input->post('email'),
                'regd_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('regd_date'))),
                'country_id' => $this->input->post('country_id'),
                'state_id' => $this->input->post('state_id'),
                'city_id' => $this->input->post('city_id'),
                'area_id' => $this->input->post('area_id'),
                // 'royalty' => $this->input->post('royalty'),
                // 'websiteurl' => $this->input->post('website_url'),
                // 'is_active' => 'no',
                // 'is_parent' => '1',
            );
            $this->branchsettings_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Branch added successfully</div>');
            redirect('branchsettings/index');
        }
    }

    public function getbranchlist(){
        $m               = $this->branchsettings_model->getbranchslist();
        $m               = json_decode($m);
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $documents = '';

                if ($this->rbac->hasPrivilege('multi_branch', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "branchsettings/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('multi_branch', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "branchsettings/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }

                // if ($value->documents) {
                //     $documents = "<a href='" . base_url() . "admin/expense/download/" . $value->id . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                //          <i class='fa fa-download'></i> </a>";
                // }
                $row   = array();
                $row[] = $value->name;

                // if ($value->note == "") {
                //     $row[] = $this->lang->line('no_description');
                // } else {
                //     $row[] = $value->note;
                // }

                $row[]     = $value->code;
                $row[]     = $value->phone;
                $row[]     = $value->email;
                $row[]     = date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->regd_date));
                $row[]     = $value->country;
                $row[]     = $value->state;
                $row[]     = $value->city;
                $row[]     = $value->area;
                $row[]     = $value->address;
                $row[]     = $editbtn;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    function update_status() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $campuslist = $this->staff_model->get_staff_camp($id);
        $data = array(
            'id' => $id,
            'is_active' => $this->input->post('status')
        );
        $datass = array(
            'id' => $campuslist['id'],
            'is_active' => $this->input->post('status')
        );
        $this->staff_model->update_status_active($datass);
        $datas = $this->campussettings_model->add($data);
        echo json_encode($datas);
    }

}

?>