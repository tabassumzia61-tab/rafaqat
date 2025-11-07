<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**************************************/
/**************************************/
/*********		USER      ***********/
/**************************************/
/**************************************/

$user_url_var_name 	= 'user';
$user_url_prefix 	= $user_url_var_name . '/';
//$user_dir_prefix 	= 'user/';


$route[$user_url_prefix .'signin']					= 'Superadmin/signin';
$route[$user_url_prefix .'login']					= 'Superadmin/signin';
$route[$user_url_prefix .'login.html']				= 'Superadmin/login';

$route[$user_url_prefix .'logout.html']				= 'Superadmin/logout';



$route[$user_url_prefix .'forgot-password.html']    = 'Superadmin/forgotpassword';
$route[$user_url_prefix .'reset-password.html']     = 'Superadmin/reset_password';


//$route[$user_url_prefix .'change-password.html']     = 'Auth_Controller/change_password';







