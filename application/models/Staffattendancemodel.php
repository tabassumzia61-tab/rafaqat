<?php

class Staffattendancemodel extends MY_Model {

    public $current_session;
    public $current_date;
    
    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function get($id = null) {
        $this->db->select()->join("staff", "staff.id = staff_attendance.staff_id")->from('staff_attendance');
        $this->db->where("staff.is_active", 1);
        if ($id != null) {
            $this->db->where('staff_attendance.id', $id);
        } else {
            $this->db->order_by('staff_attendance.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getUserType() {

        $query = $this->db->query("select distinct user_type from staff where is_active = 1");

        return $query->result_array();
    }

    public function searchAttendenceUserType($user_type, $date,$brach_id) {
        $condition = '';
        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 1) {                 
                $condition = " and roles.id != 1";
            } 
        }
        
        if ($user_type == "select") {   

            $query = $this->db->query("select staff_attendance.id,staff_attendance.created_at as attendence_dt, staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id from staff  left join roles on staff.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " where staff.is_active = 1 and staff.barch_id = " . $this->db->escape($brach_id) . " $condition");
        } else {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.created_at as attendence_dt,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id from staff  left join roles on (roles.id = staff.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 and staff.barch_id = " . $this->db->escape($brach_id) . "$condition");
        }
        return $query->result_array();
    }

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_attendance', $data);
            $message = UPDATE_RECORD_CONSTANT . " On staff attendance id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_attendance', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On staff attendance id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
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

    public function getAttByDate($s_val = null,$att_date=null) {
        $this->db->select('staff_attendance.*')->from('staff_attendance');
        if($s_val != ''){
            $this->db->where('staff_attendance.staff_id', $s_val);
        }
        $this->db->where('staff_attendance.date', $att_date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStaffByAttendID($brach_id,$staff_id = null,$date = null) {
        $this->db->select('staff_attendance.id as id,staff_attendance.date,staff_attendance.staff_attendance_type_id,staff_attendance.remark,staff.id as staff_id,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,roles.id as user_type_id')->join("staff", "staff.id = staff_attendance.staff_id")->join("roles", "roles.id = staff.role_id")->from('staff_attendance');
        $this->db->where("staff.is_active", 1);
        $this->db->where("staff.barch_id", $brach_id);
        if ($staff_id != null) {
            $this->db->where_in('staff.employee_id', $staff_id);
        }
        if ($date != null) {
           $this->db->where('staff_attendance.date', $date);
        }
        $this->db->order_by('staff_attendance.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStaffAttendanceType() {

        $query = $this->db->select('*')->where("is_active", 'yes')->get("staff_attendance_type");

        return $query->result_array();
    }

    public function searchAttendanceReport($user_type, $date) {

        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
             
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            $condition = '';
            if ($superadmin_visible == 'disabled' && $staffrole->id != 1) {
                $condition = "and staff.role_id != 1";       
            } 
        }
        
        if ($user_type == "select") {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id left join roles on staff.role_id = roles.id where staff.is_active = 1 $condition");
        } else {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join roles on (roles.id = staff.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id  where roles.name = '" . $user_type . "' and staff.is_active = 1 $condition");
        }

        return $query->result_array();
    }

    public function attendanceYearCount() {

        $query = $this->db->select("distinct year(date) as year")->get("staff_attendance");

        return $query->result_array();
    }

    public function searchStaffattendance($date, $staff_id, $active_staff = true) {

        $sql = "select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join roles on staff.role_id = roles.id left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.id = " . $this->db->escape($staff_id);
        if ($active_staff || !isset($active_staff)) {
            $sql .= " and staff.is_active = 1";
        }
        $query = $this->db->query($sql);
        return $query->row_array();
    }


        public function onlineattendence($data) {

        $this->db->where('staff_id', $data['staff_id']);
        $this->db->where('date', $data['date']);
        $q = $this->db->get('staff_attendance');

        if ($q->num_rows() == 0) {
            $this->db->insert('staff_attendance', $data);
            return ($this->db->affected_rows() != 1) ? false : true;
        }
        return false;
    }

    public function searchAttendancePrepareReport($user_type, $date,$brach_id) {
        if ($user_type == "select") {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " and staff_attendance.brach_id = " . $this->db->escape($brach_id) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id left join roles on staff.role_id = roles.id where staff.is_active = 1 and staff.barch_id = " . $this->db->escape($brach_id) . "");
        } else {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join roles on (roles.id = staff.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " and staff_attendance.brach_id = " . $this->db->escape($brach_id) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id  where staff.role_id = '" . $user_type . "' and staff.is_active = 1 and staff.barch_id = " . $this->db->escape($brach_id) . "");
        }
        return $query->result_array();
    }


}
