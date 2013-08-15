<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	appointments.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Appointments dispatch for addon
 * 
 */

define('DEBUG', false);


if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

use Tygh\Registry;

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	fn_trusted_vars('appointment_data', 'appointments', 'delete');
	$suffix = '';
	if ($mode == 'do_delete') 
	{
		if (!empty($_REQUEST['appointment_ids'])) 
		{
			foreach ($_REQUEST['appointment_ids'] as $id) 
			{
				fn_tspa_delete_appointment($id);
			}//endforeach
		}//endif

		$suffix = '.manage';
	} 
	elseif ($mode == 'update')
	{
		$appointment_id = $_POST['appointment_id'];
		$data = $_POST['appointment_data'];
	
		if (!empty($data['status']))
		{
			fn_tspa_update_appointment_status($appointment_id,$data['status']);
		}//endif
		
		if (!empty($data['date']) && !empty($data['time']) && !empty($data['location']))
		{
			$date = $data['date'];
			$time = $data['time'];
			$location = $data['location'];
			
			fn_tspa_update_appointment($appointment_id, $date, $time, $location);
			 
		}//endif
		
		$suffix = ".update?appointment_id=$appointment_id";
	}//endif

	return array(CONTROLLER_STATUS_OK, "appointments$suffix");
}//endif

if ($mode == 'manage') 
{
	list($appointments, $search) = fn_tspa_get_appointments($_REQUEST);

	Registry::get('view')->assign('appointments', $appointments);
	Registry::get('view')->assign('search', $search);

	list($user_list) = fn_get_users(array('status' => 'A'), $auth);

	$_user_list = array();
	foreach ($user_list as $item) 
	{
		$_user_list[$item['user_id']] = "{$item['lastname']}, {$item['firstname']} (Email: {$item['email']})";
	}//endforeach

	Registry::get('view')->assign('user_list', $_user_list);

}//endif
elseif ($mode == 'update' && !empty($_REQUEST['appointment_id'])) 
{
	$appointment = db_get_row("SELECT * FROM ?:addon_tsp_appointments WHERE id = ?i", $_REQUEST['appointment_id']);
	
	if (empty($appointment)) 
	{
		return array(CONTROLLER_STATUS_NO_PAGE);	
	}//endif
	
	$appointment_id = $appointment['id'];
	
	if (!empty($appointment['user_id']))
	{
		$appointment['user'] = fn_get_user_info($appointment['user_id']);
	}//endif
		
	if (!empty($appointment['product_id']))
	{
		$appointment['product'] = fn_get_product_data($appointment['product_id'],$auth,CART_LANGUAGE,'product,product_code');
	}//endif

	if (!empty($appointment['order_id']))
	{
		$appointment['order'] = fn_get_order_info($appointment['order_id']);
	}//endif
	
	if (!empty($appointment['order_id']) && !empty($appointment['product_id']))
	{
		$appointment['date'] = fn_tspa_get_product_option_data($appointment['order_id'], $appointment['product_id'], Registry::get('tspa_product_option_date_field_id'));
		$appointment['time'] = fn_tspa_get_product_option_data($appointment['order_id'], $appointment['product_id'], Registry::get('tspa_product_option_time_field_id'));
		$appointment['location'] = fn_tspa_get_product_option_data($appointment['order_id'], $appointment['product_id'], Registry::get('tspa_product_option_location_field_id'), true);
	}//endif
	
	// [Breadcrumbs]
	fn_add_breadcrumb(fn_get_lang_var('tspa_appointments'), "appointments.manage.reset_view");
	fn_add_breadcrumb(fn_get_lang_var('search_results'), "appointments.manage.last_view");
	// [/Breadcrumbs]
	
	$locations = fn_tspa_get_product_option_select_values(Registry::get('tspa_product_option_location_field_id'),'variant_id');

	Registry::get('view')->assign('appointment_locations', $locations);
	Registry::get('view')->assign('appointment_statuses', Registry::get('tspa_appointment_statuses'));
	Registry::get('view')->assign('appointment', $appointment);

}//endelseif
elseif ($mode == 'update_status' && !empty($_REQUEST['appointment_id'])) 
{

	$status = $_REQUEST['status'];
	$notify_user = $_REQUEST['notify_user'];
	$appointment_id = $_REQUEST['appointment_id'];
	
	if (empty($notify_user))
	{
		$notify_user = 'N';
	}//endif
	
	if (!empty($status))
	{	
		fn_tspa_update_appointment_status($appointment_id,$status,$notify_user);
		
	}//endif
			
	return array(CONTROLLER_STATUS_REDIRECT, "appointments.manage");

}//endelseif
elseif ($mode == 'delete') 
{
	if (!empty($_REQUEST['appointment_id'])) 
	{
		fn_tspa_delete_appointment($_REQUEST['appointment_id']);
	}//endif

	return array(CONTROLLER_STATUS_REDIRECT, "appointments.manage");
	
}//endelseif
?>