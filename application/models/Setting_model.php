<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
 
    public function get($id = null) {

        $this->db->select('settings.id,settings.base_url,settings.folder_path,settings.lang_id,settings.languages,settings.cron_secret_key, settings.timezone,settings.superadmin_restriction,settings.name,settings.email,settings.biometric,settings.biometric_device,settings.time_format,settings.phone,languages.language,settings.address,settings.dise_code,settings.date_format,settings.currency,settings.currency_place,settings.image,settings.theme,settings.admin_logo,settings.admin_small_logo,settings.mobile_api_url,settings.app_primary_color_code,settings.app_secondary_color_code,settings.app_logo,languages.is_rtl,settings.currency_format,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency,currencies.id as currency_id,settings.admin_login_page_background,settings.user_login_page_background,settings.low_attendance_limit');
     
        $this->db->from('settings');
        $this->db->join('languages', 'languages.id = settings.lang_id');
        $this->db->join('currencies', 'currencies.id = settings.currency');
        if ($id != null) {
            $this->db->where('settings.id', $id);
        } else {
            $this->db->order_by('settings.id');
        }
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            $result = $query->result_array();
            return $result;
        }
    }

    public function get_studentlang($id) {
        $data = $this->db->select('users.lang_id')->from('users')->where('user_id', $id)->get()->row_array();
        return $data;
    } 
    
    public function get_guestlang($id) {
        $data = $this->db->select('guest.lang_id')->from('guest')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function get_parentlang($id) {
        $data = $this->db->select('users.lang_id')->from('users')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function get_stafflang($id) {
        $data = $this->db->select('staff.lang_id')->from('staff')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function getSystemDetail($id = null) {

        $this->db->select('settings.id,settings.lang_id,settings.is_rtl,settings.timezone,
          settings.name,settings.email,settings.biometric,settings.biometric_device,settings.phone,languages.language,settings.address,settings.dise_code,settings.date_format,settings.currency,settings.image,settings.theme,settings.maintenance_mode,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency');
        $this->db->from('settings');
        $this->db->join('languages', 'languages.id = settings.lang_id');
        $this->db->join('currencies', 'currencies.id = settings.currency');
        $this->db->order_by('settings.id');
        $query = $this->db->get();
        return $query->row();
    }

    public function getSetting() {  

        $this->db->select('settings.id,settings.base_url,settings.folder_path,settings.lang_id,settings.is_rtl,settings.cron_secret_key,settings.timezone,
          settings.name,settings.email,settings.biometric,settings.attendence_type,settings.biometric_device,settings.phone,languages.language,settings.staffid_prefix,settings.staffid_start_from,settings.staffid_auto_insert,settings.staffid_no_digit,settings.staffid_update_status,settings.quotations_prefix,settings.quotations_start_from,settings.quotations_auto_insert,settings.quotations_no_digit,settings.quotations_update_status,settings.customer_prefix,settings.customer_start_from,settings.customer_auto_insert,settings.customer_no_digit,settings.customer_update_status,settings.supplier_prefix,settings.supplier_start_from,settings.supplier_auto_insert,settings.supplier_no_digit,settings.supplier_update_status,settings.sale_prefix,settings.sale_start_from,settings.sale_auto_insert,settings.sale_no_digit,settings.sale_update_status,settings.salereturn_prefix,settings.salereturn_start_from,settings.salereturn_auto_insert,settings.salereturn_no_digit,settings.salereturn_update_status,settings.purchase_prefix,settings.purchase_start_from,settings.purchase_auto_insert,settings.purchase_no_digit,settings.purchase_update_status,settings.superadmin_restriction,settings.address,settings.dise_code,settings.date_format,settings.currency,settings.currency_place,settings.image,settings.theme,settings.admin_logo,settings.admin_small_logo,settings.mobile_api_url,settings.app_primary_color_code,settings.app_secondary_color_code,settings.app_logo,languages.short_code as `language_code`,settings.languages as activelanguage,settings.single_page_print as single_page_print,settings.calendar_event_reminder,settings.currency_format,settings.admin_mobile_api_url,settings.admin_app_primary_color_code,settings.admin_app_secondary_color_code,settings.maintenance_mode,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency,currencies.id as currency_id,settings.admin_login_page_background,settings.user_login_page_background,settings.low_attendance_limit');
        $this->db->from('settings');
        $this->db->join('languages', 'languages.id = settings.lang_id');
		$this->db->join('currencies', 'currencies.id = settings.currency');
        $this->db->order_by('settings.id');
        $query = $this->db->get();    
        $result = $query->row();
        $json_languages = json_decode($result->activelanguage);        
        foreach ($json_languages as $key => $value) {       
            $langresult  =        $this->language_model->get($value);							
            $language[$key] =  $langresult;				
		}
        $result->activelanguage2    =   $language;        
        return 	$result;
    }

    public function remove($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('settings');
        $message = DELETE_RECORD_CONSTANT . " On settings id " . $id;
        $action = "Delete";
        $record_id = $id;
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

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('settings', $data);
            $message = UPDATE_RECORD_CONSTANT . " On settings id " . $data['id'];
            $action = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('settings', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On settings id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }
 
    public function getCurrentSession() {
        $session_result = $this->get();

        return 1;
    }

    public function getOnlineAdmissionStatus() {
        $setting_result = $this->get();

        if ($setting_result[0]['online_admission']) {
            return true;
        }
        return false;
    }

    public function getexamresultstatus() {
        $setting_result = $this->get();

        if ($setting_result[0]['exam_result']) {
            return true;
        }
        return false;
    }

    public function getCurrentSessionName() {
        $session_result = $this->get();
        return $session_result[0]['session'];
    }

    public function getCurrentSystemName() {
        $session_result = $this->get();
        return $session_result[0]['name'];
    }

    public function getStartMonth() {
        $session_result = $this->get();
        return $session_result[0]['start_month'];
    }

    public function getCurrentSessiondata() {
        $session_result = $this->get();
        return $session_result[0];
    }

    public function getCurrency() {
        $session_result = $this->get();
        return $session_result[0]['currency'];
    }

    public function getCurrencySymbol() {
        $session_result = $this->get();
        return $session_result[0]['currency_symbol'];
    }

    public function getDateYmd() {
        return date('Y-m-d');
    }

    public function getDateDmy() {
        return date('d-m-Y');
    }

    public function add_cronsecretkey($data, $id) {

        $this->db->where("id", $id)->update("settings", $data);
    }

    public function getLanguage() {

        $query = $this->db->select('languages.language,languages.short_code')->where('id', $this->session->userdata['admin']['language']['lang_id'])->get('languages');
        return $query->row_array();
    }

    
    public function getuserLanguage() {

        $query = $this->db->select('languages.language,languages.short_code')->where('id', $this->session->userdata['student']['language']['lang_id'])->get('languages');
        return $query->row_array();
    }

    public function getAdminlogo() {
        $query = $this->db->select('admin_logo')->get('settings');
        $logo = $query->row_array();
        return $logo['admin_logo'];
    }

    public function getAdminsmalllogo() {
        $query = $this->db->select('admin_small_logo')->get('settings');
        $logo = $query->row_array();
        return  $logo['admin_small_logo'];
    }

    public function get_appname() {

        $query = $this->db->select('name')->get('settings');
        $name = $query->row_array();
        echo $name['name'];
    }

    public function check_haederimage($type) {
        $check = $this->db->select('*')->from('print_headerfooter')->where('print_type', $type)->get()->row_array();


        if (empty($check['header_image'])) {
            return 0;
        } else {
            return 1;
        }
    }

    public function add_printheader($data) {

        $this->db->where('print_type', $data['print_type']);
        $this->db->update('print_headerfooter', $data);
       
    }

    public function get_printheader() {
        return $this->db->select('*')->from('print_headerfooter')->get()->result_array();
    }

    public function get_receiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function unlink_receiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function get_receiptfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['footer_content'];
    }

    public function get_payslipheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['header_image'];
    }

    public function unlink_payslipheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['header_image'];
    }

    public function get_payslipfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['footer_content'];
    }

    public function unlink_onlinereceiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function get_onlineadmissionheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        echo $image['header_image'];
    }

    public function get_onlineadmissionfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        echo $image['footer_content'];
    }
    
    public function get_onlineexamheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_exam')->get()->row_array();
        echo $image['header_image'];
    }
    
    public function get_onlineexamfooter() {
        $query = $this->db->select('footer_content,header_image')->from('print_headerfooter')->where('print_type', 'online_exam')->get()->row_array();        
        return $query ;
    }

}
