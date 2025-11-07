<?php

class Payroll extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->config->load("mailsms");
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->load->library('media_storage');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance  = $this->config->item('staffattendance');
        $this->payment_mode      = $this->config->item('payment_mode');
        $this->load->model("payroll_model");
        $this->load->model("staff_model");
        $this->load->model('staffattendancemodel');
        $this->payroll_status     = $this->config->item('payroll_status');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index($branch_id = null){
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'payroll');
        $this->session->set_userdata('sub_menu', 'admin/payroll');
        if (!empty($branch_id)) {
            $brach_id = $branch_id;
        }else{
            $brach_id = $this->customlib->getBranchID();
        }
        $data["brach_id"]            = $brach_id;
        $branch_result               = $this->branchsettings_model->get();
        $data["branchlist"]          = $branch_result;
        $data["staff_id"]            = "";
        $data["name"]                = "";
        $data["month"]               = date("F", strtotime("-1 month"));
        $data["year"]                = date("Y");
        $data["present"]             = 0;
        $data["absent"]              = 0;
        $data["late"]                = 0;
        $data["half_day"]            = 0;
        $data["holiday"]             = 0;
        $data["leave_count"]         = 0;
        $data["alloted_leave"]       = 0;
        $data["basic"]               = 0;
        $data["payment_mode"]        = $this->payment_mode;
        $user_type                   = $this->staff_model->getStaffRole();
        $data['classlist']           = $user_type;
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $submit                      = $this->input->post("search");
        if (isset($submit) && $submit == "search") {
            if (!empty($this->input->post('brach_id'))) {
                $brach_id = $this->input->post('brach_id');
            }else{
                $brach_id = $this->customlib->getBranchID();
            }
            $month    = $this->input->post("month");
            $year     = $this->input->post("year");
            $emp_name = $this->input->post("name");
            $role     = $this->input->post("role");

            $searchEmployee = $this->payroll_model->searchEmployee($brach_id,$month, $year, $emp_name, $role);

            $data["resultlist"] = $searchEmployee;
            $data["name"]       = $emp_name;
            $data["month"]      = $month;
            $data['monthnum']   = date('m', strtotime($month));
            $data["year"]       = $year;
        }

        $data["payroll_status"] = $this->payroll_status;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/stafflist", $data);
        $this->load->view("layout/footer", $data);
    }
    
    public function genpay(){
        $this->form_validation->set_rules('brc_id', $this->lang->line('branch').' '.$this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('year', $this->lang->line('year'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('staff_id[]', $this->lang->line('staff'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'brc_id'        => form_error('brc_id'),
                'month'         => form_error('month'),
                'year'          => form_error('year'),
                'staff_id'     => form_error('staff_id[]')
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $userlist  = $this->customlib->getUserData();
            $user_id   = $userlist["id"];
            if(!empty($this->input->post('brc_id'))){
                $brc_id = $this->input->post('brc_id');
            }else{
                $brc_id = $this->customlib->getBranchID();
            }
            $month = $this->input->post('month');
            $year  = $this->input->post('year');
            $stafflist  = $this->input->post('staff_id');
            if(!empty($stafflist)){
                foreach ($stafflist as $skey => $sval){
                    $staff_id = $sval;
                    $checkForUpdate = $this->payroll_model->checkPayslip($month, $year, $staff_id);
                    if (!$checkForUpdate) {
                        $totalpayamount = 0;  
                        $paydetails = $this->staff_model->getpaybyID($staff_id);
                        if(!empty($paydetails)){
                            foreach($paydetails as $paykey => $payval){
                                $totalpayamount = $totalpayamount + $payval['amount'];
                            }
                        }
                        $totalpaydedamount = 0;
                        $paydeddetails = $this->staff_model->getpaydedbyID($staff_id);
                        if(!empty($paydeddetails)){
                            foreach($paydeddetails as $paydedkey => $paydedval){
                                $totalpaydedamount = $totalpaydedamount + $paydedval['amount'];
                            }
                        }
                        $netsalary = $totalpayamount - $totalpaydedamount; 
                        $data = array(
                            'id'              => $this->input->post('sys_id'),
                            'brc_id'          => $brc_id,
                            'session_id'      => $this->input->post('sch_session_id'),
                            'name'            => $this->input->post('sch_name'),
                            'phone'           => $this->input->post('sch_phone'),
                            'dise_code'       => $this->input->post('sch_dise_code'),
                            'address'         => $this->input->post('sch_address'),
                            'email'           => $this->input->post('sch_email'),
                            'timezone'        => $this->input->post('sch_timezone'),
                            'date_format'     => $this->input->post('sch_date_format'),
                            'currency'        => $this->input->post('currency_id'),
                            'currency_format' => $this->input->post('currency_format'),
                            'currency_place'  => $this->input->post('currency_place'),
                            'base_url'        => $this->input->post('base_url'),
                            'folder_path'     => $this->input->post('folder_path'),
                        );
                        $this->setting_model->add($data);
                    }else{
                        $totalpaybsamount = 0;  
                        $totalpayamount = 0;  
                        $paydetails = $this->staff_model->getpaybyID($staff_id);
                        if(!empty($paydetails)){
                            foreach($paydetails as $paykey => $payval){
                                if($payval['frequency'] == 'Basic Pay'){
                                    $totalpaybsamount = $totalpaybsamount + $payval['amount'];
                                }else{
                                    $totalpayamount = $totalpayamount + $payval['amount'];
                                }
                                
                            }
                        }
                        $totalpaydedamount = 0;
                        $paydeddetails = $this->staff_model->getpaydedbyID($staff_id);
                        if(!empty($paydeddetails)){
                            foreach($paydeddetails as $paydedkey => $paydedval){
                                $totalpaydedamount = $totalpaydedamount + $paydedval['amount'];
                            }
                        }
                        $netbssalary = $totalpaybsamount; 
                        $totsalary   = $totalpayamount + $totalpaybsamount; 
                        $dedsalary   = $totalpaydedamount; 
                        $netsalary   = $totalpayamount + $totalpaybsamount - $totalpaydedamount; 
                        $monthdays   = 30;
                        $perdayam    = $totalpaybsamount/$monthdays;
                        $halfday     = $perdayam/2;
                        $monthnum     = date('m', strtotime($month));
                        $salary_month = $year . "-" . $monthnum . "-" . '01'; 
                        $present        = $this->payroll_model->getAttendancebyTypeByMonth($monthnum,$year,$staff_id,1);
                        $absent         = $this->payroll_model->getAttendancebyTypeByMonth($monthnum,$year,$staff_id,2);
                        $leave          = $this->payroll_model->getAttendancebyTypeByMonth($monthnum,$year,$staff_id,3);
                        $late           = $this->payroll_model->getAttendancebyTypeByMonth($monthnum,$year,$staff_id,4);
                        $half_day       = $this->payroll_model->getAttendancebyTypeByMonth($monthnum,$year,$staff_id,5);
                        $holiday        = $this->payroll_model->getAttendancebyTypeByMonth($monthnum,$year,$staff_id,6);
                        $netday         = $monthdays - $absent - $leave - $late - $half_day;
                        $presentam      = $present * $perdayam;
                        $netdayam       = $netday * $perdayam;
                        $absentam       = $absent * $perdayam;
                        $leaveam        = $leave * $perdayam;
                        $lateam         = $late * $perdayam;
                        $halfdayam      = $half_day * $halfday;
                        $holidayam      = $holiday * $perdayam;
                        $t_ded_salary = $absentam + $lateam + $halfdayam;
                        $net_salary   = $netsalary + $leaveam - $t_ded_salary;
                        $data = array(
                            'brc_id'            => $brc_id,
                            'staff_id'          => $staff_id,
                            'net_basic_pay'     => $netbssalary,
                            'net_pay'           => $totsalary,
                            'net_pay_ded'       => $dedsalary,
                            'net_final_pay'     => $netsalary,
                            'total_deduction'   => $t_ded_salary,
                            'days'              => $monthdays,
                            'net_days'          => $netday,
                            'net_days_am'       => $netdayam,
                            'present'           => $present,
                            'absent'            => $absent,
                            'leave'             => $leave,
                            'half_day'          => $half_day,
                            'holiday'           => $holiday,
                            'present_am'        => $presentam,
                            'absent_am'         => $absentam,
                            'leave_am'          => $leaveam,
                            'late_am'           => $lateam,
                            'half_day_am'       => $halfdayam,
                            'holiday_am'        => $holidayam,
                            'salary_month_date' => $salary_month,
                            'net_salary'        => $net_salary,
                            'status'            => 'generated',
                            'month'             => $month,
                            'year'              => $year,
                            'created_by'        => $user_id,
                            'created_at'        => date('Y-m-d H:i:s'),
                        );
                        $insert_id        = $this->payroll_model->createPayslip($data);
                        $payslipid        = $insert_id;
                        if($payslipid){
                            $paydetails = $this->staff_model->getpaybyID($staff_id);
                            if(!empty($paydetails)){
                                foreach($paydetails as $paykey => $payval){
                                    $data_pay = array(
                                        'payslip_id' => $payslipid,
                                        'staff_id' => $staff_id,
                                        'frequency' => $payval['frequency'],
                                        'type_id' => $payval['type_id'],
                                        'brc_id' => $brc_id,
                                        'amount' => $payval['amount'],
                                        'created_by' => $user_id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    );
                                    $this->payroll_model->paydetslip($data_pay);
                                }
                            }
                            $paydeddetails = $this->staff_model->getpaydedbyID($staff_id);
                            if(!empty($paydeddetails)){
                                foreach($paydeddetails as $paydedkey => $paydedval){
                                    $data_pay_ded = array(
                                        'payslip_id' => $payslipid,
                                        'staff_id' => $staff_id,
                                        'type_id' => $paydedval['type_id'],
                                        'brc_id' => $brc_id,
                                        'amount' => $paydedval['amount'],
                                        'created_by' => $user_id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    );
                                    $this->payroll_model->paydeddetslip($data_pay_ded);
                                }
                            }
                        }
                    }
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function create($month, $year, $id){       
        $data["staff_id"]            = $id;
        $data["basic"]               = "";
        $data["name"]                = "";
        $data["month"]               = "";
        $data["year"]                = "";
        $data["present"]             = 0;
        $data["absent"]              = 0;
        $data["late"]                = 0;
        $data["half_day"]            = 0;
        $data["holiday"]             = 0;
        $data["leave_count"]         = 0;
        $data["alloted_leave"]       = 0;
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $user_type                   = $this->staff_model->getStaffRole();
        $data['classlist']           = $user_type;
        $date = $year . "-" . $month;
        $mothnum = $this->customlib->getMonthNumber($month);
        $searchEmployee = $this->payroll_model->searchEmployeeById($id);
        $data['result'] = $searchEmployee;
        $data["month"]  = $month;
        $data["year"]   = $year;
        $alloted_leave = $this->staff_model->alloted_leave($id);
        $newdate = date('Y-m-d', strtotime($date . " +1 month"));
        $totalgetSundays = $this->getSundays($year,$month);
        $totalSundays = count($totalgetSundays);
        $monthAttendances = $this->monthAttendance($newdate, 1, $id);
        $att_present  = 0;
        $att_absent   = 0;
        $att_leave    = 0;
        $att_late     = 0;
        $att_half_day = 0;
        if(!empty($monthAttendances)){
            foreach($monthAttendances as $att_val){
                $att_present  = $att_val['present'];
                $att_absent   = $att_val['absent'];
                $att_leave    = $att_val['leave'];
                $att_late     = $att_val['late'];
                $att_half_day = $att_val['half_day'];
            }
        }
        if($att_present >= 0){
            $data['att_present']  = $att_present;
        }else{
            $data['att_present']  = $att_present; //+ $totalSundays;
        }
        $data['att_absent']   = $att_absent;
        $data['att_leave']    = $att_leave;
        $data['att_late']     = $att_late;
        $data['att_half_day'] = $att_half_day;
        $data['monthAttendance'] = $this->monthAttendance($newdate, 3, $id);
        $data['monthLeaves']     = $this->monthLeaves($newdate, 3, $id);
        $data["attendanceType"]  = $this->staffattendancemodel->getStaffAttendanceType();
        $data["alloted_leave"]   = $alloted_leave[0]["alloted_leave"];
        $data['current_month_days'] = cal_days_in_month(CAL_GREGORIAN, $mothnum, $year);
        $assignpayresult = $this->payroll_model->getAssignPayByID($id);
        $data['assignpayList'] = $assignpayresult;
        $paytype_result = [];//$this->accounts_model->getaccountsheadFeetypeByID(3);
        $data['paytypeList'] = $paytype_result;
        $advloantype = [];//$this->accounts_model->getaccountsheadFeetypeByID(13);
        $gpsectype = [];//$this->accounts_model->getaccountsheadFeetypeByID(14);
        $paydeduction = array_merge($advloantype, $gpsectype);
        $data['paydeductionList'] = $paydeduction;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/create", $data);
        $this->load->view("layout/footer", $data);
    }

    public function edit($id)
    {

        $data["staff_id"]         = "";
        $data["basic"]            = "";
        $data["name"]             = "";
        $data["month"]            = "";
        $data["year"]             = "";
        $data["present"]          = 0;
        $data["absent"]           = 0;
        $data["late"]             = 0;
        $data["half_day"]         = 0;
        $data["holiday"]          = 0;
        $data["leave_count"]      = 0;
        $data["alloted_leave"]    = 0;
        $user_type                = $this->staff_model->getStaffRole();
        $employee_payroll         = $this->payroll_model->getPayslip($id);
        $data['employee_payroll'] = $employee_payroll;
        $data['classlist']        = $user_type;
        $data['sch_setting']      = $this->sch_setting_detail;
        $searchEmployee           = $this->payroll_model->searchEmployeeById($employee_payroll['staff_id']);
        $date                     = $employee_payroll['year'] . "-" . $employee_payroll['month'];
        $data['result']           = $searchEmployee;
        $data["month"]            = $employee_payroll['month'];
        $data["year"]             = $employee_payroll['year'];
        

        $data["earnings"]   = $this->payroll_model->getAllowance($id, 'positive');
        $data["deductions"] = $this->payroll_model->getAllowance($id, 'negative');

        $alloted_leave           = $this->staff_model->alloted_leave($employee_payroll['staff_id']);
        $newdate                 = date('Y-m-d', strtotime($date . " +1 month"));
        $data['monthAttendance'] = $this->monthAttendance($newdate, 3, $employee_payroll['staff_id']);
        $data['monthLeaves']     = $this->monthLeaves($newdate, 3, $employee_payroll['staff_id']);
        $data["attendanceType"]  = $this->staffattendancemodel->getStaffAttendanceType();
        $data["alloted_leave"]   = $alloted_leave[0]["alloted_leave"];
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/edit", $data);
        $this->load->view("layout/footer", $data);
    }

    public function editpayroll()
    {
        $id              = $this->input->post("id");
        $basic           = $this->input->post("basic");
        $total_allowance = $this->input->post("total_allowance");
        $total_deduction = $this->input->post("total_deduction");
        $net_salary      = $this->input->post("net_salary");
        $status          = $this->input->post("status");
        $staff_id        = $this->input->post("staff_id");
        $month           = $this->input->post("month");
        $name            = $this->input->post("name");
        $year            = $this->input->post("year");
        $tax             = $this->input->post("tax_percent");
        $leave_deduction = $this->input->post("leave_deduction");
        $this->form_validation->set_rules('net_salary', $this->lang->line('net_salary'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->create($month, $year, $staff_id);
        } else {

            $data = array(
                'id'              => $id,
                'staff_id'        => $staff_id,
                'basic'           => convertCurrencyFormatToBaseAmount($basic),
                'total_allowance' => convertCurrencyFormatToBaseAmount($total_allowance),
                'total_deduction' => convertCurrencyFormatToBaseAmount($total_deduction),
                'net_salary'      => convertCurrencyFormatToBaseAmount($net_salary),
                'payment_date'    => date("Y-m-d"),
                'status'          => $status,
                'month'           => $month,
                'year'            => $year,
                'tax'             => convertCurrencyFormatToBaseAmount($tax),
                'leave_deduction' => '0',
                'generated_by'    => $this->customlib->getStaffID(),
            );

            $checkForUpdate = $this->payroll_model->checkPayslip($month, $year, $staff_id);
            if (!$checkForUpdate) {
                $insert_id         = $this->payroll_model->createPayslip($data);
                $payslipid         = $insert_id;
                $allowance_type    = $this->input->post("allowance_type");
                $deduction_type    = $this->input->post("deduction_type");
                $allowance_prev_id = $this->input->post("allowance_prev_id");
                $deduction_prev_id = $this->input->post("deduction_prev_id");
                $allowance_amount  = $this->input->post("allowance_amount");
                $deduction_amount  = $this->input->post("deduction_amount");

                if (!empty($allowance_type)) {

                    $i                        = 0;
                    $insert_payslip_allowance = array();
                    $update_payslip_allowance = array();
                    foreach ($allowance_type as $key => $all) {
                        if ($allowance_prev_id[$i] != 0) {
                            $update_payslip_allowance[] = array(
                                'id'             => $allowance_prev_id[$i],
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $allowance_type[$i],
                                'amount'         => convertCurrencyFormatToBaseAmount($allowance_amount[$i]),
                                'staff_id'       => $staff_id,
                                'cal_type'       => "positive",
                            );
                        } else {
                            $insert_payslip_allowance[] = array(
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $allowance_type[$i],
                                'amount'         => convertCurrencyFormatToBaseAmount($allowance_amount[$i]),
                                'staff_id'       => $staff_id,
                                'cal_type'       => "positive",
                            );
                        }

                        $i++;
                    }

                    $insert_payslip_allowance = $this->payroll_model->update_allowance($insert_payslip_allowance, $update_payslip_allowance, $allowance_prev_id, $payslipid, 'positive');
                } else {

                    $insert_payslip_allowance = $this->payroll_model->update_allowance([], [], [0], $payslipid, 'positive');
                }

                if (!empty($deduction_type)) {
                    $j                        = 0;
                    $insert_payslip_allowance = array();
                    $update_payslip_allowance = array();

                    foreach ($deduction_type as $key => $type) {
                        if ($deduction_prev_id[$j] != 0) {
                            $update_payslip_allowance[] = array(
                                'id'             => $deduction_prev_id[$j],
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $deduction_type[$j],
                                'amount'         => convertCurrencyFormatToBaseAmount($deduction_amount[$j]),
                                'staff_id'       => $staff_id,
                                'cal_type'       => "negative",
                            );
                        } else {
                            $insert_payslip_allowance[] = array(
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $deduction_type[$j],
                                'amount'         => convertCurrencyFormatToBaseAmount($deduction_amount[$j]),
                                'staff_id'       => $staff_id,
                                'cal_type'       => "negative",
                            );
                        }
                        $j++;
                    }

                    $insert_payslip_allowance = $this->payroll_model->update_allowance($insert_payslip_allowance, $update_payslip_allowance, $deduction_prev_id, $payslipid, 'negative');
                } else {
                    $insert_payslip_allowance = $this->payroll_model->update_allowance([], [], [0], $payslipid, 'negative');
                }

                redirect('admin/payroll');
            } else {

                $this->session->set_flashdata("msg", "<div class='alert alert-warning'>" . $this->lang->line('payslip_not_generated') . "</div>");

                redirect('admin/payroll');
            }
        }
    }

    function getSundays($y,$m){ 
        $date = "$y-$m-01";
        $first_day = date('N',strtotime($date));
        $first_day = 7 - $first_day + 1;
        $last_day =  date('t',strtotime($date));
        $days = array();
        for($i=$first_day; $i<=$last_day; $i=$i+7 ){
            $days[] = $i;
        }
        return  $days;
    }

    public function monthAttendance($st_month, $no_of_months, $emp){
        $record = array();
        for ($i = 1; $i <= $no_of_months; $i++) {
            $r     = array();
            $month = date('m', strtotime($st_month . " -$i month"));
            $year  = date('Y', strtotime($st_month . " -$i month"));
            foreach ($this->staff_attendance as $att_key => $att_value) {
                $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);
                $r[$att_key] = $s;
            }
            $record['01-' . $month . '-' . $year] = $r;
        }
        return $record;
    }

    public function monthLeaves($st_month, $no_of_months, $emp)
    {
        $record = array();
        for ($i = 1; $i <= $no_of_months; $i++) {

            $r           = array();
            $month       = date('m', strtotime($st_month . " -$i month"));
            $year        = date('Y', strtotime($st_month . " -$i month"));
            $leave_count = $this->staff_model->count_leave($month, $year, $emp);
            if (!empty($leave_count["tl"])) {
                $l = $leave_count["tl"];
            } else {
                $l = "0";
            }

            $record[$month] = $l;
        }

        return $record;
    }

    public function payslip()
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_add')) {
            access_denied();
        }

        $basic           = $this->input->post("basic") - $this->input->post("absent_amount") - $this->input->post("leave_amount") - $this->input->post("late_amount") - $this->input->post("halfday_amount") - $this->input->post("total_deduction");//convertCurrencyFormatToBaseAmount($this->input->post("basic"));
        $total_allowance = $this->input->post("total_allowance");
        $total_deduction = $this->input->post("total_deduction");
        $net_salary      = $this->input->post("net_salary");
        $status          = $this->input->post("status");
        $staff_id        = $this->input->post("staff_id");
        $month           = $this->input->post("month");
        $name            = $this->input->post("name");
        $year            = $this->input->post("year");
        $tax             = convertCurrencyFormatToBaseAmount($this->input->post("tax"));
        $per_day = $this->input->post("per_day");
        $present = $this->input->post("present");
        $present_amount = $this->input->post("present_amount");
        $absent = $this->input->post("absent");
        $absent_amount = $this->input->post("absent_amount");
        $leave = $this->input->post("leave");
        $leave_amount = $this->input->post("leave_amount");
        $late = $this->input->post("late");
        $late_amount = $this->input->post("late_amount");
        $leave_deduction = $this->input->post("leave_deduction");
        $halfday = $this->input->post("halfday");
        $halfday_amount = $this->input->post("halfday_amount");
        $this->form_validation->set_rules('net_salary', $this->lang->line('net_salary'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->create($month, $year, $staff_id);
        } else {
            $data = array(
                'staff_id' => $staff_id,
                'basic' => $basic,
                'total_earning' => $total_allowance,
                'total_deduction' => $total_deduction,
                'per_day' => $per_day,
                'present' => $present,
                'present_amount' => $present_amount,
                'absent' => $absent,
                'absent_amount' => $absent_amount,
                'leave_t' => $leave,
                'leave_amount' => $leave_amount,
                'late' => $late,
                'late_amount' => $late_amount,
                'halfday' => $halfday,
                'halfday_amount' => $halfday_amount,
                'net_salary'             => $net_salary,
                'payment_date'           => date("Y-m-d"),
                'status'                 => $status,
                'month'                  => $month,
                'year'                   => $year,
                'tax'                    => $tax,
            );

            $checkForUpdate = $this->payroll_model->checkPayslip($month, $year, $staff_id);
 
            if ($checkForUpdate == true) {
                $insert_id        = $this->payroll_model->createPayslip($data);
                $payslipid        = $insert_id;
                $allowances_m_type = $this->input->post("allowances_m_type");
                $allowances_m_amount = $this->input->post("allowances_m_amount");
                if (!empty($allowances_m_type)) {
                    $e = 0;
                    foreach ($allowances_m_type as $key => $val) {
                        $pay_data = array(
                            'payslip_id' => $payslipid,
                            'allowance_type' => $allowances_m_type[$e],
                            'amount' => $allowances_m_amount[$e] - $absent_amount - $leave_amount - $late_amount - $halfday_amount - $total_deduction,
                            'staff_id' => $staff_id,
                            'profit_acc_head_id' => 49,
                            'cal_type' => "pay",
                        );
                        $this->payroll_model->add_allowance($pay_data);
                        $e++;
                    }
                }
                $allowances_e_type = $this->input->post("allowances_e_type");
                $allowances_e_amount = $this->input->post("allowances_e_amount");
                if (!empty($allowances_e_type)) {
                    $i = 0;
                    foreach ($allowances_e_type as $key => $val) {
                        $allw_data = array(
                            'payslip_id' => $payslipid,
                            'allowance_type' => $allowances_e_type[$i],
                            'amount' => $allowances_e_amount[$i],
                            'staff_id' => $staff_id,
                            'profit_acc_head_id' => 49,
                            'cal_type' => "allowance",
                        );
                        $insert_payslip_allowance = $this->payroll_model->add_allowance($allw_data);
                        $i++;
                    }
                }
                $allowance_type = $this->input->post("allowance_type");
                $allowance_amount = $this->input->post("allowance_amount");
                $deduction_type = $this->input->post("deduction_type");
                $deduction_amount = $this->input->post("deduction_amount");
                if (!empty($allowance_type)) {
                    $j = 0;
                    foreach ($allowance_type as $key => $val) {
                        if($val !=''){
                            $all_data = array(
                                'payslip_id' => $payslipid,
                                'allowance_type' => $allowance_type[$j],
                                'amount' => $allowance_amount[$j],
                                'staff_id' => $staff_id,
                                'profit_acc_head_id' => 49,
                                'cal_type' => "positive",
                            );
                            $insert_payslip_allowance = $this->payroll_model->add_allowance($all_data);
                            $j++;
                        }
                    }
                }
                if (!empty($deduction_type)) {
                    $k= 0;
                    foreach ($deduction_type as $key => $val) {
                        if($val != ""){
                            if($deduction_type[$k] == 45){
                                $type_data = array(
                                    'payslip_id' => $payslipid,
                                    'allowance_type' => $deduction_type[$k],
                                    'amount' => $deduction_amount[$k],
                                    'staff_id' => $staff_id,
                                    'cal_type' => "negative",
                                    'profit_acc_head_id' => 49,
                                    'cal_type_head' => "gp",
                                );
                                $insert_payslip_allowance = $this->payroll_model->add_allowance($type_data);
                                $k++;
                            }elseif($deduction_type[$k] == 48){
                                $type_data = array(
                                    'payslip_id' => $payslipid,
                                    'allowance_type' => $deduction_type[$k],
                                    'amount' => $deduction_amount[$k],
                                    'staff_id' => $staff_id,
                                    'cal_type' => "negative",
                                    'profit_acc_head_id' => 49,
                                    'cal_type_head' => "sec",
                                );
                                $insert_payslip_allowance = $this->payroll_model->add_allowance($type_data);
                                $k++;
                            }elseif($deduction_type[$k] == 44){
                                $type_data = array(
                                    'payslip_id' => $payslipid,
                                    'allowance_type' => $deduction_type[$k],
                                    'amount' => $deduction_amount[$k],
                                    'staff_id' => $staff_id,
                                    'cal_type' => "negative",
                                    'profit_acc_head_id' => 49,
                                    'cal_type_head' => "adv",
                                );
                                $insert_payslip_allowance = $this->payroll_model->add_allowance($type_data);
                                $k++;
                            }elseif($deduction_type[$k] == 47){
                                $type_data = array(
                                    'payslip_id' => $payslipid,
                                    'allowance_type' => $deduction_type[$k],
                                    'amount' => $deduction_amount[$k],
                                    'staff_id' => $staff_id,
                                    'cal_type' => "negative",
                                    'profit_acc_head_id' => 49,
                                    'cal_type_head' => "loan",
                                );
                                $insert_payslip_allowance = $this->payroll_model->add_allowance($type_data);
                                $k++;
                            }else{
                                $type_data = array(
                                    'payslip_id' => $payslipid,
                                    'allowance_type' => $deduction_type[$k],
                                    'amount' => $deduction_amount[$k],
                                    'staff_id' => $staff_id,
                                    'profit_acc_head_id' => 49,
                                    'cal_type' => "negative",
                                );
                                $insert_payslip_allowance = $this->payroll_model->add_allowance($type_data);
                                $k++;
                            }
                            
                        }
                    }
                }
                $this->session->set_flashdata("msg", $this->lang->line('payslip_generated'));
                redirect('admin/payroll');
            } else {

                $this->session->set_flashdata("msg", $this->lang->line('payslip_already_generated'));
                redirect('admin/payroll');
            }
        }
    }

    public function search($month, $year, $role = '')
    {
        $user_type              = $this->staff_model->getStaffRole();
        $data['classlist']      = $user_type;
        $data['monthlist']      = $this->customlib->getMonthDropdown();
        $searchEmployee         = $this->payroll_model->searchEmployee(1,$month, $year, $emp_name = '', $role);
        $data["resultlist"]     = $searchEmployee;
        $data["name"]           = $emp_name;
        $data["month"]          = $month;
        $data["year"]           = $year;
        $data['sch_setting']    = $this->sch_setting_detail;
        $data["payroll_status"] = $this->payroll_status;
        $data["resultlist"]     = $searchEmployee;
        $data["payment_mode"]   = $this->payment_mode;
        $data["brach_id"]   = 1;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/stafflist", $data);
        $this->load->view("layout/footer", $data);
    }

    public function paymentRecord()
    {
        $month              = $this->input->get_post("month");
        $year               = $this->input->get_post("year");
        $id                 = $this->input->get_post("staffid");
        $searchEmployee     = $this->payroll_model->searchPayment($id, $month, $year);
        $data['result']     = $searchEmployee;
        $data['net_salary'] = amountFormat($searchEmployee['net_salary']);
          $data['monthlist']           = $this->customlib->getMonthDropdown();

        $data["month"]      = $data['monthlist'][$month];

           


        $data["year"]       = $year;
        echo json_encode($data);
    }

    public function paymentStatus($status)
    {
        $id          = $this->input->get('id');
        $updateStaus = $this->payroll_model->updatePaymentStatus($status, $id);
        redirect("admin/payroll");
    }

    public function paymentSuccess()
    {
        $voucher_type_id = $this->input->post("voucher_type_id");
        $account_mode_id = $this->input->post("account_mode_id");
        $payment_mode = $this->input->post("payment_mode");
        $date         = $this->input->post("payment_date");
        $payment_date = date('Y-m-d', strtotime($date));
        $remark       = $this->input->post("remarks");
        $status       = 'paid';
        $payslipid    = $this->input->post("paymentid");
        $this->form_validation->set_rules('voucher_type_id', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_mode_id', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == false) {

            $msg = array(
                'payment_mode'    => form_error('payment_mode'),
                'payment_date'    => form_error('payment_date'),
                'account_mode_id' => form_error('account_mode_id'),
                'voucher_type_id' => form_error('voucher_type_id'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $dataallow = array('par_acc_head_id' => $account_mode_id,'voucher_type_id' => $voucher_type_id);
            $this->payroll_model->allowpaymentSuccess($dataallow, $payslipid);
            $data = array('par_acc_head_id' => $account_mode_id,'voucher_type_id' => $voucher_type_id,'payment_mode' => $payment_mode, 'payment_date' => $this->customlib->dateFormatToYYYYMMDD($date), 'remark' => $remark, 'status' => $status);
            $this->payroll_model->paymentSuccess($data, $payslipid);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function payslipView()
    {
        $data["payment_mode"] = $this->payment_mode;
        $this->load->model("setting_model");
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result[0];
        $id                  = $this->input->post("payslipid");
        $result              = $this->payroll_model->getPayslip($id);
        $data['sch_setting'] = $this->sch_setting_detail;

        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        if (!empty($result)) {
            $allowance                  = $this->payroll_model->getAllowance($result["id"]);
            $data["allowance"]          = $allowance;
            $positive_allowance         = $this->payroll_model->getAllowance($result["id"], "positive");
            $data["positive_allowance"] = $positive_allowance;
            $negative_allowance         = $this->payroll_model->getAllowance($result["id"], "negative");
            $data["negative_allowance"] = $negative_allowance;
            $data["result"]             = $result;
            $this->load->view("admin/payroll/payslipview", $data);
        } else {
            echo "<div class='alert alert-info'>" . $this->lang->line('no_record_found') . "</div>";
        }
    }

    public function payslippdf()
    {
        $this->load->model("setting_model");
        $setting_result             = $this->setting_model->get();
        $data['settinglist']        = $setting_result[0];
        $id                         = 15;
        $result                     = $this->payroll_model->getPayslip($id);
        $allowance                  = $this->payroll_model->getAllowance($result["id"]);
        $data["allowance"]          = $allowance;
        $positive_allowance         = $this->payroll_model->getAllowance($result["id"], "positive");
        $data["positive_allowance"] = $positive_allowance;
        $negative_allowance         = $this->payroll_model->getAllowance($result["id"], "negative");
        $data["negative_allowance"] = $negative_allowance;
        $data["result"]             = $result;
        $this->load->view("admin/payroll/payslippdf", $data);
    }

    public function payrollreport()
    {
        if (!$this->rbac->hasPrivilege('payroll_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/human_resource');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/attendance_report');
        $month                = $this->input->post("month");
        $year                 = $this->input->post("year");
        $role                 = $this->input->post("role");
        $data["month"]        = $month;
        $data["year"]         = $year;
        $data["role_select"]  = $role;
        $data['monthlist']    = $this->customlib->getMonthDropdown();
        $data['yearlist']     = $this->payroll_model->payrollYearCount();
        $staffRole            = $this->staff_model->getStaffRole();
        $data["role"]         = $staffRole;
        $data["payment_mode"] = $this->payment_mode;

        $this->form_validation->set_rules('year', $this->lang->line('year'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->load->view("layout/header", $data);
            $this->load->view("admin/payroll/payrollreport", $data);
            $this->load->view("layout/footer", $data);
        } else {

            $result = $this->payroll_model->getpayrollReport($month, $year, $role);

            $data["result"] = $result;
            $this->load->view("layout/header", $data);
            $this->load->view("admin/payroll/payrollreport", $data);
            $this->load->view("layout/footer", $data);
        }
    }

    public function deletepayroll($payslipid, $month, $year, $role = '')
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_delete')) {
            access_denied();
        }
        if (!empty($payslipid)) {
            $this->payroll_model->deletePayslip($payslipid);
        }

        redirect('admin/payroll/search/' . $month . "/" . $year . "/" . $role);
    }

    public function revertpayroll($payslipid, $month, $year, $role = '')
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_delete')) {
            access_denied();
        }
        if (!empty($payslipid)) {
            $this->payroll_model->revertPayslipStatus($payslipid);
        }
        redirect('admin/payroll/search/' . $month . "/" . $year . "/" . $role);

    }

    function staffpayment($branch_id = null) {
        if (!$this->rbac->hasPrivilege('staff_payment', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'payroll');
        $this->session->set_userdata('sub_menu', 'admin/payroll/staffpayment');
        $data['title'] = 'Add Advance';
        $data['title_list'] = 'Recent Advance';
        if (!empty($branch_id)) {
            $brach_id = $branch_id;
        }else{
            $brach_id = $this->customlib->getBranchID();
        }
        $data['brach_id']   = $brach_id;
        $branch_result      = $this->branchsettings_model->get();
        $data["branchlist"] = $branch_result;
        $staffList = $this->staff_model->getStaffByBrach($brach_id);
        $data["staffList"] = $staffList;
        $staffotherpaymentList = $this->payroll_model->getStaffpaymentByBranch('',$brach_id);
        $data["staffotherpaymentList"] = $staffotherpaymentList;
        $paytype_result = [];//$this->accounts_model->getAccountsHeadBytypeIDByID(13);
        $data['paytypeList'] = $paytype_result;
        $data["voucher_type_id"] = '';
        $data["account_mode_id"] = '';
        $data["staff_id"]        = '';
        $this->form_validation->set_rules('staff_id', $this->lang->line('staff'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('acc_head_id', $this->lang->line('account_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('voucher_type_id', $this->lang->line('voucher').' '.$this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_mode_id', $this->lang->line('account').' '.$this->lang->line('mode'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            if (!empty($this->input->post('brach_id'))) {
                $brach_id = $this->input->post('brach_id');
            }else{
                $brach_id = $this->customlib->getBranchID();
            }
            $data = array(
                'brach_id' => $brach_id,
                'staff_id' => $this->input->post('staff_id'),
                'voucher_type_id' => $this->input->post('voucher_type_id'),
                'par_acc_head_id' => $this->input->post('account_mode_id'),
                'acc_head_id' => $this->input->post('acc_head_id'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount' => $this->input->post('amount'),
                'note' => $this->input->post('description')
            ); 
            $this->payroll_model->addstaffpayment($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('success_message').'</div>');
            redirect('admin/payroll/staffpayment/'.$brach_id);
        }
        
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/staffpaymentList", $data);
        $this->load->view("layout/footer", $data);
    }

    public function getStaffByBrach(){
        $brach_id = $this->input->post("brach_id");
        $data = $this->staff_model->getStaffByBrach($brach_id);
        echo json_encode($data);
    }

    function staffpaymentedit($id,$brach_id){
        if (!$this->rbac->hasPrivilege('staff_payment', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Other Payment';
        $branch_result      = $this->branchsettings_model->get();
        $data["branchlist"] = $branch_result;
        $data['id'] = $id;
        $data['brach_id'] = $brach_id;
        $staffList = $this->staff_model->getStaffByBrach($brach_id);
        $data["staffList"] = $staffList;
        $paytype_result = $this->accounts_model->getaccountsheadFeetypeByID(13);
        $data['paytypeList'] = $paytype_result;
        $staffotherpaymentList = $this->payroll_model->getStaffpaymentByBranch('',$brach_id);
        $data["staffotherpaymentList"] = $staffotherpaymentList;
        $staffpayment = $this->payroll_model->getStaffpaymentByBranch($id,$brach_id);
        //pr($staffadvance);
        $data["staffpayment"] = $staffpayment;
        $data["voucher_type_id"] = $staffpayment['voucher_type_id'];
        $data["account_mode_id"] = $staffpayment['par_acc_head_id'];
        $this->form_validation->set_rules('staff_id', $this->lang->line('staff'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('acc_head_id', $this->lang->line('account_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('voucher_type_id', $this->lang->line('voucher').' '.$this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_mode_id', $this->lang->line('account').' '.$this->lang->line('mode'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/payroll/staffpaymentEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!empty($this->input->post('brach_id'))) {
                $brach_id = $this->input->post('brach_id');
            }else{
                $brach_id = $this->customlib->getBranchID();
            }
            $payment_mode = $this->input->post('payment_mode');
            if($payment_mode == 1){
                $acc_head_id = 12;
            }else{
                $acc_head_id = 13;
            }
            $data = array(
                'id' => $id,
                'brach_id' => $brach_id,
                'staff_id' => $this->input->post('staff_id'),
                'voucher_type_id' => $this->input->post('voucher_type_id'),
                'par_acc_head_id' => $this->input->post('account_mode_id'),
                'acc_head_id' => $this->input->post('acc_head_id'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount' => $this->input->post('amount'),
                'note' => $this->input->post('description')
            );
            //pr($data);
            $this->payroll_model->addstaffpayment($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('update_message').'</div>');
            redirect('admin/payroll/staffpayment/'.$brach_id);
        }
        
    }

    function staffpaymentdelete($id,$brach_id) {
        if (!$this->rbac->hasPrivilege('staff_payment', 'can_delete')) {
            access_denied();
        }
        $this->payroll_model->removestaffpayment($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">'.$this->lang->line('delete_message').'</div>');
        redirect('admin/payroll/staffpayment/'.$brach_id);
    }

}
