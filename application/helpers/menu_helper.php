<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('active_link')) {

    function activate_menu($controller, $action)
    {
        $CI     = get_instance();
        $method = $CI->router->fetch_method();
        $class  = $CI->router->fetch_class();
        return ($method == $action && $controller == $class) ? 'active' : '';
    }

    function set_Topmenu($top_menu_name)
    {
        $CI               = get_instance();
        $session_top_menu = $CI->session->userdata('top_menu');
        if ($session_top_menu == $top_menu_name) {
            return 'active';
        }
        return "";
    }

    function set_Submenu($sub_menu_name)
    {
        $CI               = get_instance();
        $session_sub_menu = $CI->session->userdata('sub_menu');
        if ($session_sub_menu == $sub_menu_name) {
            return 'active';
        }
        return "";
    }

    function set_SubSubmenu($sub_menu_name)
    {
        $CI               = get_instance();
        $session_sub_menu = $CI->session->userdata('subsub_menu');
        if ($session_sub_menu == $sub_menu_name) {
            return 'active';
        }
        return "";
    }

}

function access_denied()
{
    redirect('admin/unauthorized');
}

function update_config_installed()
{
    $CI          = &get_instance();
    $config_path = APPPATH . 'config/config.php';
    $CI->load->helper('file');
    @chmod($config_path, FILE_WRITE_MODE);
    $config_file = read_file($config_path);
    $config_file = trim($config_file);
    $config_file = str_replace("\$config['installed'] = false;", "\$config['installed'] = true;", $config_file);
    $config_file = str_replace("\$config['base_url'] = '';", "\$config['base_url'] = '" . site_url() . "';", $config_file);
    if (!$fp = fopen($config_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
        return false;
    }
    flock($fp, LOCK_EX);
    fwrite($fp, $config_file, strlen($config_file));
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($config_path, FILE_READ_MODE);
    return true;
}

function update_autoload_installed()
{
    $CI            = &get_instance();
    $autoload_path = APPPATH . 'config/autoload.php';
    $CI->load->helper('file');
    @chmod($autoload_path, FILE_WRITE_MODE);
    $autoload_file = read_file($autoload_path);
    $autoload_file = trim($autoload_file);
    $autoload_file = str_replace("\$autoload['libraries'] = array('database', 'session', 'form_validation')", "\$autoload['libraries'] = array('email','session', 'form_validation', 'upload', 'pagination','Customlib')", $autoload_file);
    if (!$fp = fopen($autoload_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
        return false;
    }
    flock($fp, LOCK_EX);
    fwrite($fp, $autoload_file, strlen($autoload_file));
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($config_path, FILE_READ_MODE);
    return true;
}

function delete_dir($dirPath)
{
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            delete_dir($file);
        } else {
            unlink($file);
        }
    }
    if (rmdir($dirPath)) {
        return true;
    }
    return false;
}

function admin_url($url = '')
{
    if ($url == '') {
        return site_url() . 'site/login';
    } else {
        return site_url() . 'site/login';
    }
}

if (!function_exists('main_menu_array')) {

    function main_menu_array($find_array)
    {  
        $array = array(

            'system_settings' => array(  
                'schsettings'           => array('index','logo','miscellaneous','backendtheme','mobileapp','studentguardianpanel','fees','idautogeneration','attendancetype','maintenance'),                     
                'branchsettings'        => array('index','edit'),                     
                'notification'          => array('setting'),                     
                'smsconfig'             => array('index'),                     
                'emailconfig'           => array('index'),                     
                'paymentsettings'       => array('index'),                     
                'print_headerfooter'    => array('index'),                     
                'frontcms'              => array('index'),                     
                'roles'                 => array('index','permission'),                     
                'admin'                 => array('backup','filetype'),                     
                'language'              => array('index','create'),                     
                'currency'              => array('index'),                     
                'users'                 => array('index'),                     
                'module'                => array('index'),                     
                'customfield'           => array('index','edit'),                     
                'captcha'               => array('index'),                     
                'systemfield'           => array('index'),                     
                'student'               => array('profilesetting'),                     
                'onlineadmission'       => array('admissionsetting'),                  
                'updater'               => array('index'),                  
                'sidemenu'              => array('index'),                  
            ),

            'particles' => array(
                'country'         => array('index','create','edit'),
                'state'           => array('index','create','edit'),
                'city'            => array('index','create','edit'),
                'area'            => array('index','create','edit'),
                'department'      => array('index','create','edit'),
                'designation'     => array('index','create','edit'),
                'leavetypes'      => array('index','create','edit'),
                'categories'      => array('admin/categories.html','categories/create.html','categories/edit.html'),
                'units'           => array('admin/units.html','units/create.html','units/edit.html'),
                'brands'          => array('index','create','edit'), 
                'variants'        => array('index','create','edit'),
                'warehouses'      => array('index','create','edit'),
                
                'taxrates'        => array('index','create','edit'),
            ),

            'accounts' => array(
                'accounts'         => array('index','accountstype','accountstypeedit','accountshead','accountsheadedit'),
            ),

            'payments' => array(
                'payments'         => array('paymentin','paymentincreate','paymentinedit','paymentout','paymentoutcreate','paymentoutedit','search','journalvoucher','editjournalvoucher'),
            ),

            'human_resource' => array(                   
                'leaverequest'      => array('index','applyleave'),         
                'staffattendance'   => array('index','staffattendancebrach','attendencedayreport'),   
                'payroll'           => array('index','edit','create','staffpayment'),
            ),

            'people' => array(
                'staff'             => array('index','profile','edit','rating','disablestafflist','create'),
                'supplier'           => array('index','edit','create','profile','ledgers'),
                'customers'          => array('index','edit','create','profile','ledgers'),
            ),

            'products' => array(
                'products'           => array('index','edit','create'),
                'producttype'     => array('index','create','edit')
            ),

            'sales' => array(
                'quotations'     => array('index','edit','create'),
                'sales'          => array('index','edit','create'),
                'salesreturn'    => array('index','edit','create'),
                
            ),

            'purchases' => array(
                'purchases'          => array('index','edit','create','received','addrate','completed'),
                'expenses'           => array('index','edit','create'),
                'purchasesreturn'    => array('index','edit','create'),
            ),
            
            'reports' => array(     
                'accounts'           => array('generalreports','generalledger','trialbalance'), 
                'stock'              => array('stockreports','qtywisestock'), 
                'sales'              => array('salesreports','customerwisesalessum','customerwisesalesdet','itemwisesalessum','itemwisesalesdet'), 
                'purchases'          => array('purchasesreports','supplierwisepurchasessum','supplierwisepurchasesdet','itemwisepurchasessum','itemwisepurchasesdet'), 
            ),  
            
        );
        if (array_key_exists($find_array, $array)) {
            return $array[$find_array];
        }
        return false;
    }

}

if (!function_exists('activate_main_menu')) {

    function activate_main_menu($menu, $class_active = "active")
    {
        $CI     = get_instance();
        $class  = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();

        $return_array = main_menu_array($menu);
        if ($return_array) {
            if (array_key_exists($class, $return_array)) {
                $a = $return_array[$class];

                if (!empty($a)) {
                    foreach ($a as $method_key => $method_value) {
                        if ($method_value == $method) {
                            return $class_active;
                            break;
                        }
                    }
                }
            }
        }
    }

}

if (!function_exists("activate_submenu")) {

    function activate_submenu($arg_class = "", $arg_methods = array(), $class_active = "active")
    {
        $CI = get_instance();

        // Getting router class to active.
        $class  = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();
        if (is_array($arg_methods)) {
            foreach ($arg_methods as $arg_methods_key => $arg_methods_value) {
                if ($method == $arg_methods_value && $class == $arg_class) {
                    return $class_active;
                    break;
                }
            }
        }
    }

}

function side_menu_list($list = -1)
{
    $CI = &get_instance();

    $CI->load->model('sidebarmenu_model');
    $result = $CI->sidebarmenu_model->getMenuwithSubmenus($list);
    
    return $result;
}

function access_permission_sidebar_remove_pipe($access_permissions)
{
    // remove pipe sign ||
    $module_permission = array_map('trim', explode('||', preg_replace('/\(\'|\'|\)/', '', $access_permissions)));

    return $module_permission;
}

function access_permission_remove_comma($m_permission_value)
{
    // remove pipe sign ||
    $module_permission_seprated = array_map('trim', explode(',', preg_replace('/\s+/', '', $m_permission_value)));
    return $module_permission_seprated;
}
