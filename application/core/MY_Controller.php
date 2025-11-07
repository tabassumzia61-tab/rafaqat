<?php

define('THEMES_DIR', 'themes');
define('BASE_URI', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

class MY_Controller extends CI_Controller
{

    protected $langs = array();

    public function __construct(){
        parent::__construct();
        $this->load->library('auth');
        $this->load->helper(array('directory', 'custom','language'));
        if ($this->session->has_userdata('admin')) {
            $admin    = $this->session->userdata('admin');
            $language = ($admin['language']['language']);
        } else if ($this->session->has_userdata('student')) {
            $student = $this->session->userdata('student');
            $language = ($student['language']['language']);
        } else {
            $this->system_details = $this->setting_model->getSystemDetail();
            $language             = ($this->system_details->language);
        }
        $this->config->set_item('language', $language);
        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }
        $this->load->language($lang_array, $language);
    }

}

class Admin_Controller extends MY_Controller
{

    protected $aaaa = false;

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in();
        $this->load->library('rbac');
        $this->config->load('app-config');
        $this->config->load('ci-blog');
    }

}

class Public_Controller extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
    }

}

class Front_Controller extends CI_Controller{

    protected $data           = array();
    protected $school_details = array();
    protected $parent_menu    = '';
    protected $page_title     = '';
    protected $theme_path     = '';
    protected $front_setting  = '';

    public function __construct(){

        parent::__construct();
        $this->load->database();
                $this->load->library(array('Smsgateway', 'QDMailer'));
        $this->load->model(array('setting_model', 'language_model', 'Module_model', 'cms_program_model', 'cms_menu_model', 'cms_menuitems_model', 'cms_page_model', 'cms_page_content_model', 'class_model', 'category_model','notificationsetting_model'));

        if ($this->config->item('installed') == true) {

            $this->db->reconnect();
        }
        $this->load->helper('language');
        $this->school_details = $this->setting_model->getSchoolDetail();
        $this->customlib->initFrontSession();

        if ($this->school_details->maintenance_mode) {
            echo $this->load->view('maintenance', '', true);
            exit;
        }

        $this->load->model('frontcms_setting_model');

        $this->front_setting = $this->frontcms_setting_model->get();

        if (!$this->front_setting) {
            redirect('site/userlogin');
        } else {
            $front_cms_class  = $this->router->fetch_class();
            $front_cms_method = $this->router->fetch_method();
            if ($this->front_setting->is_active_front_cms) {
                $this->config->set_item('front_layout', true);
            }
            if (!$this->front_setting->is_active_front_cms) {
                $this->config->set_item('front_layout', false);
            }

            if (!$this->front_setting->is_active_front_cms && !$this->school_details->online_admission) {
                redirect('site/userlogin');
            }

            if ($this->school_details->online_admission) {
                if (!$this->front_setting->is_active_front_cms &&
                    !($front_cms_class == "welcome" && $front_cms_method == "admission") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "editonlineadmission") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "online_admission_review") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "getSections") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "submitadmission") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "index") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "successinvoice") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "paymentfailed") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "checkadmissionstatus")
                ) {
                    redirect('site/userlogin');
                }
            }

        }

        $this->theme_path = $this->front_setting->theme;
//================
        $language = ($this->school_details->language);
        $this->config->set_item('language', $language);
        $this->load->helper(array('directory', 'custom'));
        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }

        $this->load->language($lang_array, $language);
//===============

        $this->load->config('ci-blog');
    }

    protected function load_theme($content = null, $layout = true)
    {

        $this->data['main_menus']     = '';
        $this->data['school_setting'] = $this->school_details;
        $this->data['front_setting']  = $this->front_setting;
        $menu_list                    = $this->cms_menu_model->getBySlug('main-menu');

        $footer_menu_list = $this->cms_menu_model->getBySlug('bottom-menu');
        if (count($menu_list) > 0) {
            $this->data['main_menus'] = $this->cms_menuitems_model->getMenus($menu_list['id']);
        }

        if (count($footer_menu_list) > 0) {
            $this->data['footer_menus'] = $this->cms_menuitems_model->getMenus($footer_menu_list['id']);
        }
        $this->data['layout_type'] = $layout;
        $this->data['header']      = $this->load->view('themes/' . $this->theme_path . '/header', $this->data, true);

        $this->data['slider'] = $this->load->view('themes/' . $this->theme_path . '/home_slider', $this->data, true);

        $this->data['footer'] = $this->load->view('themes/' . $this->theme_path . '/footer', $this->data, true);

        $this->base_assets_url = 'backend/' . THEMES_DIR . '/' . $this->theme_path . '/';

        $this->data['base_assets_url'] = BASE_URI . $this->base_assets_url;
        $is_captcha                    = $this->captchalib->is_captcha('admission');
        $this->data["is_captcha"]      = $is_captcha;

        if ($layout == true) {
            $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
            $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/layout', $this->data);
        } else {
            $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
            $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/base_layout', $this->data);
        }
    }

    protected function load_theme_form($content = null, $layout = true)
    {

        $this->data['main_menus']     = '';
        $this->data['school_setting'] = $this->school_details;
        $this->data['front_setting']  = $this->front_setting;
        $menu_list                    = $this->cms_menu_model->getBySlug('main-menu');
        $footer_menu_list             = $this->cms_menu_model->getBySlug('bottom-menu');
        if (count($menu_list > 0)) {
            $this->data['main_menus'] = $this->cms_menuitems_model->getMenus($menu_list['id']);
        }

        if (count($footer_menu_list > 0)) {
            $this->data['footer_menus'] = $this->cms_menuitems_model->getMenus($footer_menu_list['id']);
        }
        $this->data['header'] = $this->load->view('themes/' . $this->theme_path . '/header', $this->data, true);

        $this->data['slider'] = $this->load->view('themes/' . $this->theme_path . '/home_slider', $this->data, true);

        $this->data['footer'] = $this->load->view('themes/' . $this->theme_path . '/footer', $this->data, true);

        $this->base_assets_url = 'backend/' . THEMES_DIR . '/' . $this->theme_path . '/';

        $this->data['base_assets_url'] = BASE_URI . $this->base_assets_url;

        $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
        $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/layout', $this->data);

    }

}
