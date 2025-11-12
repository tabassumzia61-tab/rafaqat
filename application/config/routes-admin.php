<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**************************************/
/**************************************/
/*********		ADMIN      ***********/
/**************************************/
/**************************************/

$user_url_var_name 	= 'admin';
$user_url_prefix 	= $user_url_var_name . '/';
$user_dir_prefix 	= 'admin/';


$route[$user_url_prefix .'dashboard.html']     = $user_dir_prefix  .'Admin/dashboard';


$route[$user_url_prefix .'categories.html']             = $user_dir_prefix  .'Categories/index';
$route[$user_url_prefix .'categories/create.html']      = $user_dir_prefix  .'Categories/create';
$route[$user_url_prefix .'categories/edit.html']        = $user_dir_prefix  .'Categories/edit';
$route[$user_url_prefix .'categories/delete.html']      = $user_dir_prefix  .'Categories/delete';


$route[$user_url_prefix .'units.html']                  = $user_dir_prefix  .'Units/index';
$route[$user_url_prefix .'units/create.html']           = $user_dir_prefix  .'Units/create';
$route[$user_url_prefix .'units/edit.html']             = $user_dir_prefix  .'Units/edit';
$route[$user_url_prefix .'units/delete.html']           = $user_dir_prefix  .'Units/delete';


$route[$user_url_prefix .'items.html']                  = $user_dir_prefix  .'Items/index';



$route[$user_url_prefix .'products.html']               = $user_dir_prefix  .'Products/index';
$route[$user_url_prefix .'products/create.html']        = $user_dir_prefix  .'Products/create';
$route[$user_url_prefix .'products/delete.html']        = $user_dir_prefix  .'Products/delete';

$route[$user_url_prefix .'units/ajax-save.html']        = $user_dir_prefix  .'Products/ajax_add_unit';














