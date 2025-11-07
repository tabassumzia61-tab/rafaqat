<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staffattendance extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->config->load("mailsms");
        $this->config->load("payroll");
       // $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance  = $this->config->item('staffattendance');
        $this->load->model("staffattendancemodel");
        $this->load->model("staff_model");
        $this->load->model("payroll_model"); 
    }

    public function index(){
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'attendance');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $data['title']        = 'Staff Attendance List';
        $data['title_list']   = 'Staff Attendance List';
        $user_type            = $this->staff_model->getStaffRole();
        $data['sch_setting']  = $this->setting_model->getSetting();
        $data['classlist']    = $user_type;
        $branch_result        = $this->branchsettings_model->get();
        $data["branchlist"]   = $branch_result;
        $data['brach_id']     = "";
        $data['class_id']     = "";
        $data['section_id']   = "";
        $data['date']         = "";
        $user_type_id         = $this->input->post('user_id');
        $data["user_type_id"] = $user_type_id;
        if (!(isset($user_type_id))) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $brach_id             = $this->input->post('brach_id');
            $user_type            = $this->input->post('user_id');
            $date                 = $this->input->post('date');
            $user_list            = [];//$this->staffattendancemodel->get();
            $data['brach_id']     = $brach_id;
            $data['userlist']     = $user_list;
            $data['class_id']     = $user_list;
            $data['user_type_id'] = $user_type_id;
            $data['section_id']   = "";
            $data['date']         = $date;
            $dates = date('d/m/Y', $this->customlib->datetostrtotime($date));
            $datetimeformat = DateTime::createFromFormat('d/m/Y', $dates);
            $datetime = $datetimeformat->format('Y-m-d H:i:s');
            $search               = $this->input->post('search');
            $holiday              = $this->input->post('holiday');
            $this->session->set_flashdata('msg', '');
            if ($search == "saveattendence") {
                $user_type_ary       = $this->input->post('student_session');
                $absent_student_list = array();
                foreach ($user_type_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);                    
                    if ($checkForUpdate != 0 && !empty($checkForUpdate)) {
                        if (isset($holiday)) {
                            $arr = array(
                                'id'                       => $checkForUpdate,
                                'staff_id'                 => $value,
                                'brach_id'                 => $brach_id,
                                'staff_attendance_type_id' => 6,
                                'remark'                   => $this->input->post("remark" . $value),
                                'date'                     => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'punch_in' => 0,
                                'date_time_in' => $datetime
                            );
                        } else {
                            $arr = array(
                                'id'                       => $checkForUpdate,
                                'staff_id'                 => $value,
                                'brach_id'                 => $brach_id,
                                'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                                'remark'                   => $this->input->post("remark" . $value),
                                'date'                     => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'punch_in' => 0,
                                'date_time_in' => $datetime
                            );
                        }

                        $insert_id = $this->staffattendancemodel->add($arr);
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'staff_id'                 => $value,
                                'brach_id'                 => $brach_id,
                                'staff_attendance_type_id' => 6,
                                'date'                     => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'remark'                   => '',
                                'punch_in' => 0,
                                'date_time_in' => $datetime
                            );
                        } else {
                            $arr = array(
                                'staff_id'                 => $value,
                                'brach_id'                 => $brach_id,
                                'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                                'date'                     => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                                'remark'                   => $this->input->post("remark" . $value),
                                'punch_in' => 0,
                                'date_time_in' => $datetime
                            );
                        }
                        $insert_id     = $this->staffattendancemodel->add($arr);
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['staff_attendance_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }

                // $absent_config = $this->config_attendance['absent'];
                // if (!empty($absent_student_list)) {

                //     $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                // }
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/staffattendance/index');
            }

            $attendencetypes             = $this->attendencetype_model->getStaffAttendanceType();
            $data['attendencetypeslist'] = $attendencetypes;        
            
            $resultlist                  = $this->staffattendancemodel->searchAttendenceUserType($user_type, date('Y-m-d', $this->customlib->datetostrtotime($date)),$brach_id);
            $data['resultlist']          = $resultlist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function staffattendancebrach(){
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view') )) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'attendance');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/staffattendancebrach');
        $data['title'] = 'Staff Attendance List';
        $data['title_list'] = 'Staff Attendance List';
        $branch_result        = $this->branchsettings_model->get();
        $data["branchlist"]   = $branch_result;
        $data['brach_id'] = '';
        $data['selected_value_staff'] = '';
        $data['search_text_staff'] = '';
        $search = $this->input->post('search');
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
                $this->form_validation->set_rules('text_staff', $this->lang->line('search_by_staff'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                    $data["resultlist"] = array();
                } else {
                    $data['searchby']    = "filter";
                    if(!empty($this->input->post('brach_id'))){
                        $brach_id = $this->input->post('brach_id');
                    }else{
                        $brach_id = $this->input->post('brach_id');
                    }
                    $selected_category = $this->input->post('selected_value_staff');
                    $search_text = trim($this->input->post('text_staff'));
                    $date = $this->input->post('date');
                    $staff_id = explode(",",$search_text);
                    $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
                    $data['attendencetypeslist'] = $attendencetypes;
                    $resultlist = $this->staffattendancemodel->getStaffByAttendID($brach_id,$staff_id, date('Y-m-d', $this->customlib->datetostrtotime($date)));
                    $data['selected_value_staff'] = $selected_category;
                    $data['search_text_staff'] = $search_text;
                    $data['resultlist']  = $resultlist;
                    $data['brach_id']  = $brach_id;
                    $data['date']  = $date;
                }
            }
        }
        $this->load->view('layout/header');
        $this->load->view('admin/staffattendance/staffattendancebrach', $data);
        $this->load->view('layout/footer');

    }

    public function staffAttendenceByAttID(){
        $date = $this->input->post('date');
        $search = $this->input->post('search');
        $holiday = $this->input->post('holiday');
        $dates = date('d/m/Y', $this->customlib->datetostrtotime($date));
        $datetimeformat = DateTime::createFromFormat('d/m/Y', $dates);
        $datetime = $datetimeformat->format('Y-m-d H:i:s');
        if ($search == "saveattendence") {
            if(!empty($this->input->post('brach_id'))){
                $brach_id = $this->input->post('brach_id');
            }else{
                $brach_id = $this->input->post('brach_id');
            }
            $user_type_ary = $this->input->post('staff_id_arr');
            $absent_student_list = array();
            foreach ($user_type_ary as $key => $value) {
                $checkForUpdate = $this->input->post('attendendence_id' . $value);
                if ($checkForUpdate != 0) {
                    if (isset($holiday)) {
                        $arr = array(
                            'id' => $checkForUpdate,
                            'staff_id' => $value,
                            'brach_id' => $brach_id,
                            'staff_attendance_type_id' => 6,
                            'remark' => $this->input->post("remark" . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            'punch_in' => 0,
                            'date_time_in' => $datetime
                        );
                    } else {
                        $arr = array(
                            'id' => $checkForUpdate,
                            'staff_id' => $value,
                            'brach_id' => $brach_id,
                            'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                            'remark' => $this->input->post("remark" . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            'punch_in' => 0,
                            'date_time_in' => $datetime
                        );
                    }

                    $insert_id = $this->staffattendancemodel->add($arr);
                } else {
                    if (isset($holiday)) {
                        $arr = array(
                            'staff_id' => $value,
                            'brach_id' => $brach_id,
                            'staff_attendance_type_id' => 6,
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            'remark' => '',
                            'punch_in' => 0,
                            'date_time_in' => $datetime
                        );
                    } else {
                        $arr = array(
                            'staff_id' => $value,
                            'brach_id' => $brach_id,
                            'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                            'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            'remark' => $this->input->post("remark" . $value),
                            'punch_in' => 0,
                            'date_time_in' => $datetime
                        );
                    }
                    $insert_id = $this->staffattendancemodel->add($arr);
                    // $absent_config = $this->staff_attendance['absent'];
                    // $leave_config = $this->staff_attendance['leave'];
                    // if ($arr['staff_attendance_type_id'] == $absent_config) {
                    //     $absent_staff_list[] = $value;
                    // }
                    // if ($arr['staff_attendance_type_id'] == $leave_config) {
                    //     $leave_staff_list[] = $value;
                    // }       
                }
            }
            // $absent_config = $this->staff_attendance['absent'];
            // $leave_config = $this->staff_attendance['leave'];
            // if (!empty($absent_staff_list)) {

            //     $this->mailsmsconf->mailsms('staff_absent_attendence', $absent_staff_list, $date);
            // }
            // //pr($leave_staff_list);
            // if (!empty($leave_staff_list)) {

            //     $this->mailsmsconf->mailsms('staff_leave_attendence', $leave_staff_list, $date);
            // }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Attendance Edit Successfully</div>');
            redirect('admin/staffattendance/staffattendancebrach');
        }
    }

    public function staffattendancebrachsubmit(){
        $this->form_validation->set_rules('att_date', 'Search Keyword', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'att_date' => form_error('att_date'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            if(!empty($this->input->post('brach_id'))){
                $brach_id = $this->input->post('brach_id');
            }else{
                $brach_id = $this->input->post('brach_id');
            }
            $att_dates = date('d/m/Y', $this->customlib->datetostrtotime($this->input->post('att_date')));
            $datetimeformat = DateTime::createFromFormat('d/m/Y', $att_dates);
            $datetime = $datetimeformat->format('Y-m-d H:i:s');
            $allstaff = $this->staff_model->getStaffByBrach($brach_id);
            foreach ($allstaff as $s_key => $s_val) {
                $attendence = $this->staffattendancemodel->getAttByDate($s_val['id'],date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('att_date'))));
                if (empty($attendence)) {
                    $data = array(
                    'staff_id'=> $s_val['id'],
                    'brach_id' => $brach_id,
                    'date'=> date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('att_date'))),
                    'punch_in' => 0,
                    'date_time_in' => $datetime,
                    'staff_attendance_type_id'=>1);
                    $this->staffattendancemodel->add($data);
                }
            }
            $attendencedateatted = $this->staffattendancemodel->getAttByDate(date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('att_date'))));
            if (empty($attendencedateatted)) {
                $array = array('statuss' => 'success', 'error' => '', 'message' => 'Record Added Successfully');
            }else{
                $array = array('statuss' => 'fail', 'error' => '', 'message' => 'Attendance Already Submitted This Date');
            }
            echo json_encode($array);
        }
    }

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        $this->load->model("payroll_model");
        $record = array();
        $r     = array();
        $month = date('m', strtotime($st_month));
        $year  = date('Y', strtotime($st_month));
        foreach ($this->staff_attendance as $att_key => $att_value) {
            $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);
            $r[$att_key] = $s;
        }

        $record[$emp] = $r;
        return $record;
    }

    public function profileattendance(){
        $monthlist             = $this->customlib->getMonthDropdown();
        $startMonth            = $this->setting_model->getStartMonth();
        $data["monthlist"]     = $monthlist;
        $data['yearlist']      = $this->staffattendancemodel->attendanceYearCount();
        $staffRole             = $this->staff_model->getStaffRole();
        $data["role"]          = $staffRole;
        $data["role_selected"] = "";
        $j                     = 0;
        for ($i = 1; $i <= 31; $i++) {

            $att_date = sprintf("%02d", $i);
            $attendence_array[] = $att_date;
            foreach ($monthlist as $key => $value) {
                $datemonth       = date("m", strtotime($value));
                $att_dates       = date("Y") . "-" . $datemonth . "-" . sprintf("%02d", $i);
                $date_array[]    = $att_dates;
                $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($att_dates, $staff_id = 8);
            }

            $j++;
        }

        $data["resultlist"]       = $res;
        $data["attendence_array"] = $attendence_array;
        $data["date_array"]       = $date_array;
        $this->load->view("layout/header");
        $this->load->view("admin/staff/staffattendance", $data);
        $this->load->view("layout/footer");
    }

    function attendencedayreport($campus_id = null) {
        if (!$this->rbac->hasPrivilege('attendance_by_date', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'attendance');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/attendencedayreport');
        $branch_result        = $this->branchsettings_model->get();
        $data["branchlist"]   = $branch_result;
        $staffRole        = $this->staff_model->getStaffRole();
        $data["role"]     = $staffRole;
        $data["brach_id"] = '';
        $data['date']     = "";
        $data["role_id"]  = "";
        $user_type_id = $this->input->post('user_id');
        $this->form_validation->set_rules('brach_id', $this->lang->line('brach_id'), 'trim|required|xss_clean');
        //$this->form_validation->set_rules('user_id', $this->lang->line('role'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendencedayreport', $data);
            $this->load->view('layout/footer', $data);

        } else {
             if(!empty($this->input->post('branch_id'))){
                $brach_id = $this->input->post('brach_id');
            }else{
                $brach_id = $this->input->post('brach_id');
            }
            $user_id = $this->input->post('user_id');
            $date = $this->input->post('date');
            // $student_list = $this->stuattendence_model->get();
            // $data['studentlist'] = $student_list;
            $data['role_id'] = $user_id;
            $data['date'] = $date;
            $dates = date('d/m/Y', $this->customlib->datetostrtotime($date));
            $datetimeformat = DateTime::createFromFormat('d/m/Y', $dates);
            $datetime = $datetimeformat->format('Y-m-d H:i:s');
            $attendencetypes = $this->attendencetype_model->getStaffAttendanceType();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist = $this->staffattendancemodel->searchAttendancePrepareReport($user_id, date('Y-m-d', $this->customlib->datetostrtotime($date)),$brach_id);
            $data['resultlist'] = $resultlist;
            $data['brach_id'] = $brach_id;
            //pr($data['resultlist']);
            // exit;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/attendencedayreport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

}
