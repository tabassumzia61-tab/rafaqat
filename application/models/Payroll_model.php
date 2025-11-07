<?php

class Payroll_model extends MY_Model
{

    protected $current_session;
    protected $current_date;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date    = $this->setting_model->getDateYmd();
    }

    public function searchEmployee($brach_id,$month, $year, $emp_name, $role)
    {
        $condition = "";
        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 1) {                 
                $condition = " and roles.id != 1";
            } 
        }
        $date_month = date("m", strtotime($year));
        if (!empty($role) && !empty($emp_name)) {
            $query = $this->db->query("select staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,sdesignation.designation as designation,department.department_name as department from staff left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join designation on staff_designation.id = staff.designation left join roles on staff.role_id = roles.id where roles.name = " . $this->db->escape($role) . " and name = " . $this->db->escape($emp_name) . " and staff.barch_id = " . $this->db->escape($brach_id) . " and staff.is_active = 1 $condition");
        } else if (!empty($role)) {
            $query = $this->db->query("select staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.*,designation.designation as designation,department.department_name as department ,roles.name as user_type from staff left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department  left join roles on staff.role_id = roles.id left join designation on designation.id = staff.designation where roles.name = " . $this->db->escape($role) . " and staff.barch_id = " . $this->db->escape($brach_id) ." and staff.is_active = 1 $condition");
        } else {
            $query = $this->db->query("select staff_payslip.status,
        IFNULL(staff_payslip.id, 0) as payslip_id ,staff.* ,roles.name as user_type ,designation.designation as designation,department.department_name as department  from staff left join staff_payslip on staff.id = staff_payslip.staff_id and month = " . $this->db->escape($month) . " and year = " . $this->db->escape($year) . " left join department on department.id = staff.department left join roles on staff.role_id = roles.id left join designation on designation.id = staff.designation where staff.is_active = 1 and staff.barch_id = " . $this->db->escape($brach_id) ." $condition");
        }
        return $query->result_array();
    }

     public function update_allowance($insert_data, $update_data, $delete_data,$payslipid,$type)
    {
        $this->db->trans_begin();
        
        if (!empty($delete_data)) {
            $this->db->where('cal_type', $type);
            $this->db->where('payslip_id', $payslipid);
            $this->db->where_not_in('id', $delete_data);
            $this->db->delete('staff_payslip_allowance');
        }

        if (!empty($insert_data)) {
            $this->db->insert_batch('staff_payslip_allowance', $insert_data);
        }
        if (!empty($update_data)) {
            $this->db->update_batch('staff_payslip_allowance', $update_data, 'id');
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

   public function createPayslip($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_payslip', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Staff Payslip id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            //$this->log($message, $record_id, $action);
            //======================Code End==============================
            $this->db->trans_complete(); # Completing transaction
            /* Optional */
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                return $record_id;
            }
        } else {
            $this->db->insert('staff_payslip', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Staff Payslip id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            //$this->log($message, $record_id, $action);
            //======================Code End==============================
            $this->db->trans_complete(); # Completing transaction
            /* Optional */
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
            return $insert_id;
        }
    }
    
    public function paydetslip($data_pay) {
        if (isset($data_pay['id'])) {
            $this->db->where('id', $data_pay['id']);
            $this->db->update('staff_payslp', $data_pay);
        } else {
            $this->db->insert('staff_payslp', $data_pay);
        }
    }
    
    public function paydeddetslip($data_pay) {
        if (isset($data_pay['id'])) {
            $this->db->where('id', $data_pay['id']);
            $this->db->update('staff_payslip_ded', $data_pay);
        } else {
            $this->db->insert('staff_payslip_ded', $data_pay);
        }
    }

    public function checkPayslip($month, $year, $staff_id)
    {

        $query = $this->db->where(array('month' => $month, 'year' => $year, 'staff_id' => $staff_id))->get("staff_payslip");

        if ($query->num_rows() > 0) {
            return false;
        } else {

            return true;
        }
    }

    public function add_allowance($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_payslip_allowance', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On payslip allowance id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_payslip_allowance', $data);
            $id = $this->db->insert_id();

            $message   = INSERT_RECORD_CONSTANT . " On payslip allowance id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    } 

    function allowpaymentSuccess($data, $payslipid) {
        $cal_type = array('pay','allowance','positive');
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("staff_payslip_allowance.payslip_id", $payslipid)->where_in('staff_payslip_allowance.cal_type', $cal_type)->update("staff_payslip_allowance", $data);
        $message      = UPDATE_RECORD_CONSTANT." On staff payslip id ".$payslipid;
            $action       = "Update";
            $record_id    = $payslipid;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /*Optional*/

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;

            } else {
                //return $return_value;
            }
    }

    public function searchPaylist($name, $month, $year)
    {
        $query = $this->db->select('staff.*,designation as desg,department.department_name as department')->where(array('staff.name' => $name, 'staff_payslip.month' => $month, 'staff_payslip.year' => $year))->join("staff_payslip", "staff.id = staff_payslip.staff_id")->join("designation", "staff.designation = designation.id")->join("department", "staff.department = department.id")->get("staff");

        return $query->result_array();
    }

    public function count_attendance($month, $year, $staff_id, $attendance_type = 1)
    {
        $date_month = date("m", strtotime($month));
        $query      = $this->db->select('count(*) as att')->where(array('staff_id' => $staff_id, 'month(date)' => $month, 'year(date)' => $year, 'staff_attendance_type_id' => $attendance_type))->get("staff_attendance");
        return $query->result_array();
    }

    public function count_attendance_obj($month, $year, $staff_id, $attendance_type = 1)
    {
        $query = $this->db->select('count(*) as attendence')->where(array('staff_id' => $staff_id, 'month(date)' => $month, 'year(date)' => $year, 'staff_attendance_type_id' => $attendance_type))->get("staff_attendance");

        return $query->row()->attendence;
    }

    public function updatePaymentStatus($status, $id)
    {
        $data = array('status' => $status);
        $this->db->where("id", $id)->update("staff_payslip", $data);
    }

    public function searchEmployeeById($id)
    {
        $query = $this->db->select('staff.*,roles.name as user_type ,designation.designation,department.department_name as department')->join("designation", "designation.id = staff.designation", "left")->join("department", "department.id = staff.department", "left")->join("roles", "staff.role_id = roles.id", "left")->where("staff.id", $id)->get("staff");

        return $query->row_array();
    }

    public function searchPayment($id, $month, $year)
    {
        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff.basic_salary,staff_payslip.*')->where(array('staff_payslip.month' => $month, 'staff_payslip.year' => $year, 'staff_payslip.staff_id' => $id))->join("staff_payslip", "staff.id = staff_payslip.staff_id")->get("staff");
        return $query->row_array();
    }

    public function paymentSuccess($data, $payslipid)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $payslipid)->update("staff_payslip", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On staff payslip id " . $payslipid;
        $action    = "Update";
        $record_id = $payslipid;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getPayslip($id)
    {
        $query = $this->db->select("staff.name,staff.surname,department.department_name as department,designation.designation,staff.employee_id,staff_payslip.*")->join("staff", "staff.id = staff_payslip.staff_id")->join("designation", "staff.designation = designation.id", "left")->join("department", "staff.department = department.id", "left")->where("staff_payslip.id", $id)->get("staff_payslip");

        return $query->row_array();
    }

    public function getAllowance($id, $type = null)
    {
        if (!empty($type)) {

            $query = $this->db->select("id,allowance_type,amount,cal_type")->where(array('payslip_id' => $id, 'cal_type' => $type))->get("staff_payslip_allowance");
        } else {

            $query = $this->db->select("id,allowance_type,amount,cal_type")->where("payslip_id", $id)->get("staff_payslip_allowance");
        }

        return $query->result_array();
    }     

    public function getSalaryDetails($id)
    {
        $query = $this->db->select("sum(net_salary) as net_salary, sum(total_earning) as earnings, sum(total_deduction) as deduction, sum(basic_salary) as basic_salary, sum(tax) as tax")->where(array('staff_id' => $id, 'status' => 'paid'))->get("staff_payslip");
        return $query->row_array();
    }

    public function getpayrollReport($month, $year, $role)
    {
        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);  
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $this->db->where("roles.id !=", 7);                 
            } 
        }
        
        if ($role == "select" && $month != "") {
            $data = array('staff_payslip.month' => $month, 'staff_payslip.year' => $year, 'staff_payslip.status' => 'paid');
        } else if ($role == "select" && $month == "") {

            $data = array('staff_payslip.year' => $year, 'staff_payslip.status' => 'paid');
        } else if ($role != "select" && $month == "") {

            $data = array('staff_payslip.year' => $year, 'roles.name' => $role, 'staff_payslip.status' => 'paid');
        } else {

            $data = array('staff_payslip.month' => $month, 'staff_payslip.year' => $year, 'roles.name' => $role, 'staff_payslip.status' => 'paid');
        }
        $data['staff.is_active'] = 1;

        $query = $this->db->select('staff.id,staff.employee_id,staff.name,roles.name as user_type,staff.surname,designation.designation,department.department_name as department,staff_payslip.*')->join("staff_payslip", "staff_payslip.staff_id = staff.id", "inner")->join("designation", "staff.designation = designation.id", "left")->join("department", "staff.department = department.id", "left")->join("roles", "staff.role_id = roles.id", "left")->where($data)->get("staff");

        return $query->result_array();
    }

    public function deletePayslip($payslipid)
    {
        $this->db->where("id", $payslipid)->delete("staff_payslip");
        $this->db->where("payslip_id", $payslipid)->delete("staff_payslip_allowance");
    }

    public function revertPayslipStatus($payslipid)
    {
        $data = array('status' => "generated");
        $this->db->where("id", $payslipid)->update("staff_payslip", $data);
    }

    public function payrollYearCount()
    {
        $query = $this->db->select("distinct(year) as year")->get("staff_payslip");
        return $query->result_array();
    }

    public function getbetweenpayrollReport($start_date, $end_date)
    {      
        
        $condition = "date_format(staff_payslip.payment_date,'%Y-%m-%d') between '" . $start_date . "' and '" . $end_date . "'";       
       
        $this->db->select('staff.id,staff.employee_id,staff.name,roles.name as user_type,staff.surname,designation.designation,department.department_name as department,staff_payslip.*');
        $this->db->join("staff_payslip", "staff_payslip.staff_id = staff.id", "inner");
        $this->db->join("designation", "staff.designation = designation.id", "left");
        $this->db->join("department", "staff.department = department.id", "left");
        $this->db->join("roles", "staff.role_id = roles.id", "left");        
        $this->db->where($condition); 
        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            
            $superadmin_rest = $this->customlib->superadmin_visible(); 
            if ($superadmin_rest == 'disabled' && $staffrole->id != 7) {
                $this->db->where("roles.id !=", 7)  ;          
            } 
        }
        
        $query = $this->db->get("staff");         
        return $query->result_array(); 
    }

    public function getStaffpaymentByBranch($id =null,$brach_id=null){
        $this->db->select('staffpayment.*,accountshead.name as accountsheadname,staff.name,staff.surname,branch.name as branch_name');
        $this->db->from('staffpayment');
        $this->db->join("accountshead", "accountshead.id = staffpayment.acc_head_id");
        $this->db->join("staff", "staff.id = staffpayment.staff_id");
        $this->db->join("branch", "branch.id = staff.barch_id");
        $this->db->join("designation", "designation.id = staff.designation", "left");
        $this->db->join("department", "department.id = staff.department", "left");
        $this->db->join("roles", "staff.role_id = roles.id", "left");
        if($id !=null){
            $this->db->where("staffpayment.id", $id);
        }
        $this->db->where("staff.barch_id", $brach_id);
        //$this->db->where("staff.is_active", "1");
        $this->db->order_by('staff.id', 'asc');
        $query = $this->db->get();
        if($id !=null){
            return $query->row_array();
        }else{
            return $query->result_array();
        }
    }

    function addstaffpayment($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staffpayment', $data);
        } else {
            $this->db->insert('staffpayment', $data);
            return $this->db->insert_id();
        }
    }

    function removestaffpayment($id) {
        $this->db->where('id', $id);
        $this->db->delete('staffpayment');
    }

    public function getAssignPayByID($id){
        $this->db->select('staff_assign_pay.id as staff_pay_id,staff_assign_pay.staff_id,staff_assign_pay.payallownc_id,staff_assign_pay.amount,accountshead.name as type');
        $this->db->from('staff_assign_pay');
        $this->db->join("staff", "staff.id = staff_assign_pay.staff_id");
        $this->db->join("accountshead", "accountshead.id = staff_assign_pay.payallownc_id");
        $this->db->where("staff_assign_pay.staff_id", $id);
        $this->db->where("staff.is_active", "1");
        $this->db->order_by('staff_assign_pay.payallownc_id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getDedByAccByStaff($staff_id,$acc_head_id,$call_type,$cal_type_head){
        $this->db->select('SUM(amount) as `tot_ded_amount`');
        $this->db->from('staff_payslip_allowance');
        $this->db->where("staff_payslip_allowance.staff_id", $staff_id);
        $this->db->where("staff_payslip_allowance.allowance_type", $acc_head_id);
        $this->db->where("staff_payslip_allowance.cal_type", $call_type);
        $this->db->where("staff_payslip_allowance.cal_type_head", $cal_type_head);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getotherpayByAccByStaff($staff_id,$acc_head_id){
        $this->db->select('SUM(amount) as `tot_othpay_amount`');
        $this->db->from('staffpayment');
        $this->db->where("staffpayment.staff_id", $staff_id);
        $this->db->where("staffpayment.acc_head_id", $acc_head_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getAttendancebyTypeByMonth($month, $year, $staff_id, $attendance_type){
        $query = $this->db->select('count(*) as attendence')->where(array('staff_id' => $staff_id, 'month(date)' => $month, 'year(date)' => $year, 'staff_attendance_type_id' => $attendance_type))->get("staff_attendance");
        return $query->row()->attendence;
    }

}
