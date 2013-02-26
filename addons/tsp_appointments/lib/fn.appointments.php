<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	fun.appointments.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Helper functions for addon
 * 
 */

if ( !defined('AREA') )	{ die('Access denied');	}

//
// [Functions - Addon.xml Handlers]
//

/***********
 *
 * Function to uninstall languages
 *
 ***********/
function fn_tspa_uninstall_languages ()
{
	$names = array(
		'tsp_appointments',
		'tspa_appointment',
		'tspa_appointment_notification',
		'tspa_appointment_notification_msg',
		'tspa_appointments',
		'tspa_appointments_menu_description',
		'tspa_editing_appointment',
		'tspa_meeting_details',
		'tspa_meeting_date',
		'tspa_meeting_time',
		'tspa_meeting_location',
		'tspa_meeting_duration',
		'tspa_meeting_info',
		'tspa_scheduled'
	);
	
	if (!empty($names)) 
	{
		db_query("DELETE FROM ?:language_values WHERE name IN (?a)", $names);
	}//endif
}//end fn_tspa_uninstall_languages

/***********
 *
 * Function to uninstall product fields
 *
 ***********/
function fn_tspa_install_product_fields () 
{	
	// Install the global option fields
	$date_id = db_query('INSERT INTO ?:product_options ?e', array('position' => 0, 'option_type' => 'D', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A'));
	$time_id = db_query('INSERT INTO ?:product_options ?e', array('position' => 5, 'option_type' => 'I', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A', 'regexp' => '(\\d\\d:\\d\\d) (AM|PM) (\\w\\w\\w)'));
	$duration_id = db_query('INSERT INTO ?:product_options ?e', array('position' => 10, 'option_type' => 'I', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A', 'regexp' => '(\\d+) (Minutes|Hour|Hours|Day|Days|Week|Weeks|Month|Months|Year|Years)'));
	$location_id = db_query('INSERT INTO ?:product_options ?e', array('position' => 15, 'option_type' => 'S', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A'));
	$info_id = db_query('INSERT INTO ?:product_options ?e', array('position' => 20, 'option_type' => 'T', 'inventory' => 'N', 'required' => 'N', 'status' => 'A'));
	
	// Store the global option fields
	db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`) VALUES ('tspa_product_option_date_field_id',$date_id), 
	('tspa_product_option_time_field_id',$time_id), 
	('tspa_product_option_duration_field_id',$duration_id), 
	('tspa_product_option_location_field_id',$location_id),
	('tspa_product_option_additional_info_field_id',$info_id)");
	
	// Install descriptions
	db_query('INSERT INTO ?:product_options_descriptions ?e', array('option_id' => $date_id, 'option_name' => 'Appointment Date', 'option_text' => '', 'description' => '', 'comment' => 'Enter in the date of the appointment', 'inner_hint' => '', 'incorrect_message' => ''));
	db_query('INSERT INTO ?:product_options_descriptions ?e', array('option_id' => $time_id, 'option_name' => 'Appointment Time', 'option_text' => '', 'description' => '', 'comment' => 'Enter in the time of the appointment (format: 07:00 PM EST)', 'inner_hint' => '12:00 AM EST', 'incorrect_message' => 'Incorrect time format.'));
	db_query('INSERT INTO ?:product_options_descriptions ?e', array('option_id' => $duration_id, 'option_name' => 'Appointment Duration', 'option_text' => '', 'description' => '', 'comment' => 'Enter in the duration of the appointment in Minutes, Hours, Days, Weeks, Months or Years (format: 1 Hour, 10 Minutes)', 'inner_hint' => '10 Minutes', 'incorrect_message' => 'Incorrect duration format.'));
	db_query('INSERT INTO ?:product_options_descriptions ?e', array('option_id' => $location_id, 'option_name' => 'Appointment Location', 'option_text' => '', 'description' => '', 'comment' => 'In-Home or On-Site Appointment', 'inner_hint' => '', 'incorrect_message' => ''));
	db_query('INSERT INTO ?:product_options_descriptions ?e', array('option_id' => $info_id, 'option_name' => 'Appointment Additional Information', 'option_text' => '', 'description' => '', 'comment' => 'Enter in any additional information you wish to provide.', 'inner_hint' => '', 'incorrect_message' => ''));


	// Install option variants
	$var1 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 0, 'option_id' => $location_id, 'modifier' => 0.00));
	$var2 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 5, 'option_id' => $location_id, 'modifier' => 0.00));

	// Store the global option fields
	db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`,`variant_id`) VALUES 
	('tspa_product_option_location_field_vars',$location_id,$var1), 
	('tspa_product_option_location_field_vars',$location_id,$var2)");

	// Install option variant descriptions
	db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('variant_id' => $var1, 'variant_name' => 'In-Home'));
	db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('variant_id' => $var2, 'variant_name' => 'On-Site'));
}//end fn_tspa_install_product_fields

/***********
 *
 * Function to uninstall product filed metadata
 *
 ***********/
function fn_tspa_uninstall_product_field_metadata () 
{
	// Get the product options
	$product_options = db_get_fields("SELECT `option_id` FROM ?:addon_tsp_appointments_product_field_metadata");
	
	if (!empty($product_options) && is_array($product_options))
	{
		// Delete the product options from all tables
		foreach ($product_options as $val)
		{
			db_query("DELETE FROM ?:product_options WHERE `option_id` = ?i", $val);
			db_query("DELETE FROM ?:product_options_descriptions WHERE `option_id` = ?i", $val);
		}//endforeach
	}//endif
	
	// Get the Product options variants
	$product_option_variants = db_get_fields("SELECT `variant_id` FROM ?:addon_tsp_appointments_product_field_metadata");
	
	if (!empty($product_option_variants) && is_array($product_option_variants))
	{
		// Delete the product options variants from all tables
		foreach ($product_option_variants as $val)
		{
			db_query("DELETE FROM ?:product_option_variants WHERE `variant_id` = ?i", $val);
			db_query("DELETE FROM ?:product_option_variants_descriptions WHERE `variant_id` = ?i", $val);
		}//endforeach
	}//endif
		
	// After all data removed drop the storage table
	db_query("DROP TABLE IF EXISTS `?:addon_tsp_appointments_product_field_metadata`");
}//end fn_tspa_uninstall_product_field_metadata

/***********
 *
 * Function to uninstall product metadata
 *
 ***********/
function fn_tspa_uninstall_product_metadata() 
{
	if (Registry::get('addons.tsp_appointments.delete_appointment_data') == 'Y') 
	{
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_appointments_product_metadata`");
	}//endif
}//end fn_tspa_uninstall_product_metadata


//
// [Functions - General]
//

/***********
 *
 * Add appointment information to an array in HTML format
 *
 ***********/
function fn_tspa_add_appointment_data_to_array(&$appointments,$key)
{
	foreach ($appointments as $appt_id => $appt)
	{	
		$order_info = fn_get_order_info($appt['order_id']);
		
		// Search through ordered items to find the product that has an appointment
		foreach ($order_info['items'] as $order_id => $product)
		{		
			$data = "";
			
			// if the appointment product ID equals this product id and the product has options
			// continue
			if ($appt['product_id'] == $product['product_id'] && fn_tspa_product_contains_appointment($product['extra']['product_options']))
			{			
				$product_options = $product['extra']['product_options'];
				
				foreach ($product_options as $option_id => $option_value)
				{
					list ($description, $value) = fn_tspa_get_product_option_info($option_id, $option_value);
					$data .= "<strong>$description:</strong>  $value<br>\n";
										
				}//endforeach;
			
			}//endif
			
			$appointments[$appt_id][$key] = $data;
								
		}//endforeach;	
		
	}//endforeach;
}


/***********
 *
 * Function to change permissions on directories
 *
 ***********/
function fn_tspa_chmodr($path, $filemode) 
{ 
    if (!is_dir($path))
    {
        return chmod($path, $filemode); 
    }//endif

    $dh = opendir($path); 
    while (($file = readdir($dh)) !== false) 
    { 
        if($file != '.' && $file != '..') 
        { 
            $fullpath = $path.'/'.$file; 
            if(is_link($fullpath))
            {
                return FALSE; 
            }//endif
            elseif(!is_dir($fullpath) && !chmod($fullpath, $filemode)) 
            {
                return FALSE; 
            }//endelseif
            elseif(!fn_tspa_chmodr($fullpath, $filemode)) 
            {
                return FALSE; 
            }//endelseif
        }//endif
    }//endwhile

    closedir($dh); 

    if(chmod($path, $filemode)) 
    {
        return TRUE; 
    }//endif
    else
    {
        return FALSE;
    }//endif
}//end fn_tspa_chmodr

/***********
 *
 * Store the appoint in the database for the first time
 * and if for some reason
 *
 ***********/
function fn_tspa_create_appointment($order_info) 
{
	$user_id = $order_info['user_id'];
	$order_id = $order_info['order_id'];
	
	// Search through ordered items to find the product that has an appointment
	foreach ($order_info['items'] as $item_id => $product)
	{	
		$product_id = $product['product_id'];
		
		if (fn_tspa_product_contains_appointment($product['extra']['product_options']))
		{			
			$data = array(
				'status' => 'O',
				'order_id' => $order_id,
				'product_id' => $product_id,
				'user_id' => $user_id,
				'date_created' => time(),
			);
						
			db_query("INSERT INTO ?:addon_tsp_appointments ?e", $data);
		}//endif
		
	}//endforeach;	
}//end fn_tspa_create_appointment


/***********
 *
 * Delete the appointment
 *
 ***********/
function fn_tspa_delete_appointment($id) 
{	
	// Change the appointment in the order details
	fn_tspa_update_appointment($id, 'Deleted', 'Deleted', 'Deleted');

	db_query("DELETE FROM ?:addon_tsp_appointments WHERE `id` = ?i", $id);
}//end fn_tspa_delete_appointment

/***********
 *
 * Get appointment informaiton for display
 *
 ***********/
function fn_tspa_get_appointments($params, $items_per_page = 0)
{
	// Init filter
	$params = fn_init_view('appointments', $params);

	// Set default values to input params
	$params['page'] = empty($params['page']) ? 1 : $params['page']; // default page is 1

	// Define fields that should be retrieved
	$fields = array (
		'?:addon_tsp_appointments.*',
		'?:users.user_id',
		'?:users.firstname',
		'?:users.lastname',
		'?:users.email'
	);

	// Define sort fields
	$sortings = array (
		'user_id' => "?:users.user_id",
		'user' => "?:users.lastname",
		'email' => '?:users.email',
		'date_created' => "?:addon_tsp_appointments.date_created",
		'date_completed' => "?:addon_tsp_appointments.date_completed",
		'status' => "?:addon_tsp_appointments.status",
	);

	$directions = array (
		'asc' => 'asc',
		'desc' => 'desc'
	);

	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) 
	{
		$params['sort_order'] = 'desc';
	}//endif

	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) 
	{
		$params['sort_by'] = 'date_created';
	}//endif

	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']] . ', ', $sortings[$params['sort_by']]) : $sortings[$params['sort_by']]) . " " . $directions[$params['sort_order']];

	// Reverse sorting (for usage in view)
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';

	$join = $condition = '';

	if (!empty($params['company'])) 
	{
		$condition .= db_quote(" AND ?:companies LIKE ?l", "%{$params['company']}%");
	}//endif

	if (!empty($params['email'])) 
	{
		$condition .= db_quote(" AND ?:users.email LIKE ?l", "%{$params['email']}%");
	}//endif

	if (!empty($params['user_id'])) 
	{
		$condition .= db_quote(" AND ?:users.user_id = ?i", $params['user_id']);
	}//endif

	if (!empty($params['appointment_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_appointments.id = ?i", $params['appointment_id']);
	}//endif

	if (!empty($params['id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_appointments.id = ?i", $params['id']);
	}//endif

	if (!empty($params['period']) && $params['period'] != 'A') 
	{
		list($params['time_from'], $params['time_to']) = fn_create_periods($params);

		$condition .= db_quote(" AND (?:addon_tsp_appointments.date_created >= ?i AND ?:addon_tsp_appointments.date_created <= ?i)", $params['time_from'], $params['time_to']);
	}//endif

	if (!empty($params['status'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_appointments.status = ?s", $params['status']);
	}//endif

	if (!empty($params['order_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_appointments.order_id = ?i", $params['order_id']);
	}//endif

	if (empty($items_per_page)) 
	{
		$items_per_page = Registry::get('settings.Appearance.admin_elements_per_page');
	}//endif

	$total = db_get_field("SELECT COUNT(*) FROM ?:addon_tsp_appointments LEFT JOIN ?:users ON ?:addon_tsp_appointments.user_id = ?:users.user_id WHERE 1 $condition");
	$limit = fn_paginate($params['page'], $total, $items_per_page);

	$appointments = db_get_hash_array("SELECT " . implode(', ', $fields) . " FROM ?:addon_tsp_appointments LEFT JOIN ?:users ON ?:addon_tsp_appointments.user_id = ?:users.user_id WHERE 1 $condition ORDER BY $sorting $limit", 'id');
	
	fn_tspa_add_appointment_data_to_array($appointments,'info');
	
	fn_view_process_results('appointments', $appointments, $params, $items_per_page);

	return array($appointments, $params);
}//end fn_tspa_get_appointments

/***********
 *
 * There are 3 fields that are required for any appointment and they are
 * time, date and location, this function will get the option keys from
 * the appointments product metadata table
 *
 ***********/
function fn_tspa_get_product_field_id($key)
{
	$field_id = -1;
	
	$table = '?:addon_tsp_appointments_product_field_metadata';
	$table_exists = db_get_row("SHOW TABLES LIKE '$table'");
	
	if ($table_exists)
	{
		$id = db_get_field("SELECT `option_id` FROM `?:addon_tsp_appointments_product_field_metadata` WHERE `key` = '$key'");
		
		if (!empty($id))
		{
			$field_id = $id;
		}//endif
	}//endif
	
	return $field_id;
}//end fn_tspa_get_product_field_id

/***********
 *
 * Get the product option descripiton and value
 *
 ***********/
function fn_tspa_get_product_option_data($order_id, $product_id, $option_id, $value_only = false)
{
	$description = "";
	$value = "";
	
	$order_info = fn_get_order_info($order_id);
	
	// Search through ordered items to find the product that has an appointment
	foreach ($order_info['items'] as $order_id => $product)
	{	
		// if the appointment product ID equals this product id and the product has options
		// continue
		if ($product_id == $product['product_id'] && fn_tspa_product_contains_appointment($product['extra']['product_options']))
		{		
			$product_options = $product['extra']['product_options'];
			
			foreach ($product_options as $id => $val)
			{
			
				if ($option_id == $id)
				{
					list ($description, $value) = fn_tspa_get_product_option_info($id, $val, $value_only);
				}//endif
									
			}//endforeach;
		
		}//endif
									
	}//endforeach;
	
	return array('description' => $description, 'value' => $value);
}//end fn_tspa_get_product_option_data

/***********
 *
 * Given a products option id and value determine what the product
 * option description and value is
 *
 ***********/
function fn_tspa_get_product_option_info($option_id, $option_value, $value_only = false) 
{

	$desc = db_get_field("SELECT `option_name` FROM ?:product_options_descriptions WHERE `option_id` = ?i", $option_id);
	$val = $option_value;
	
	$option_type = db_get_field("SELECT `option_type` FROM ?:product_options WHERE `option_id` = ?i", $option_id);

	if ($option_type == 'S' && !$value_only)
	{
		$val = db_get_field("SELECT opt_desc.variant_name FROM ?:product_option_variants_descriptions AS opt_desc LEFT JOIN ?:product_option_variants AS opt_var ON opt_desc.variant_id = opt_var.variant_id WHERE opt_var.option_id = ?i AND opt_var.variant_id = ?i", $option_id,$option_value);
	}//endif

	return array($desc,$val);
}//end fn_tspa_get_product_option_info

/***********
 *
 * Get the the values for a select field given the product option_id
 *
 ***********/
function fn_tspa_get_product_option_select_values($option_id,$key)
{

	return db_get_hash_array("SELECT opt_desc.* FROM ?:product_option_variants_descriptions AS opt_desc 
	LEFT JOIN ?:product_option_variants AS opt_var ON opt_desc.variant_id = opt_var.variant_id 
	WHERE opt_var.option_id = $option_id",$key);
}//end fn_tspa_get_product_option_select_values

/***********
 *
 * Function to notify user of appointment change
 *
 ***********/
function fn_tspa_notify_user($id)
{
	$appointment = db_get_row("SELECT * FROM ?:addon_tsp_appointments WHERE id = ?i", $id);
	$statuses = Registry::get('tspa_appointment_statuses');
	
	$appointment['status'] = $statuses[$appointment['status']];
	
	// get order/appointment data
	$extra = db_get_field("SELECT `extra` FROM ?:order_details WHERE `order_id` = ?i", $appointment['order_id']);
	$extra = @unserialize($extra);
			
	$appointment['info'] = '';
	
	foreach ($extra['product_options'] as $option_id => $option_value)
	{	
		list($description, $value) = fn_tspa_get_product_option_info($option_id, $option_value);
		$appointment['info'] .= "<strong>$description:</strong>  $value<br>\n";
		
	}//endforeach;
	
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

	Registry::get('view_mail')->assign('appointment', $appointment);
	Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', $supplier['lang_code']));
	
	// Send a copy to the customer
	fn_send_mail($appointment['user']['email'], Registry::get('settings.Company.company_orders_department'), 'addons/tsp_appointments/appointment_notification_subj.tpl', 'addons/tsp_appointments/appointment_notification.tpl', '', $appointment['user']['lang_code'], Registry::get('settings.Company.company_orders_department'));

	// Send a copy to the staff
	fn_send_mail(Registry::get('settings.Company.company_orders_department'), Registry::get('settings.Company.company_orders_department'), 'addons/tsp_appointments/appointment_notification_subj.tpl', 'addons/tsp_appointments/appointment_notification.tpl', '', $appointment['user']['lang_code'], Registry::get('settings.Company.company_orders_department'));
}//end fn_tspa_notify_user

/***********
 *
 * check product options to check to see if it is an appointment
 * appointments all contain a date, time and location
 *
 ***********/
function fn_tspa_product_contains_appointment($product_options)
{
	$contains_appointment = false;
	// required fields
	$date_found = false;
	$time_found = false;
	$location_found = false;
	
	foreach ($product_options as $option_id => $value)
	{	
		if ($option_id == Registry::get('tspa_product_option_date_field_id'))
		{
			$date_found = true;
		}//endif
		elseif ($option_id == Registry::get('tspa_product_option_time_field_id'))
		{
			$time_found = true;
		}//endelseif
		elseif ($option_id == Registry::get('tspa_product_option_location_field_id'))
		{
			$location_found = true;
		}//endelseif;
		
	}//endforeach;
	
	if ($date_found && $time_found && $location_found)
	{
		$contains_appointment = true;
	}//endif
	
	return $contains_appointment;
}//end fn_tspa_product_contains_appointment

/***********
 *
 * Update the appointment in the database
 *
 ***********/
function fn_tspa_update_appointment($id, $date, $time, $location)
{

	$appointment = db_get_row("SELECT * FROM ?:addon_tsp_appointments WHERE id = ?i", $id);
	
	$extra = db_get_field("SELECT `extra` FROM ?:order_details WHERE `order_id` = ?i", $appointment['order_id']);
	$extra = @unserialize($extra);
	
	$extra['product_options'][Registry::get('tspa_product_option_date_field_id')] = $date;
	$extra['product_options'][Registry::get('tspa_product_option_time_field_id')] = $time;
	$extra['product_options'][Registry::get('tspa_product_option_location_field_id')] = $location;
	
	db_query('UPDATE ?:order_details SET ?u WHERE order_id = ?i', array('extra' => @serialize($extra)), $appointment['order_id']);
}//end fn_tspa_update_appointment

/***********
 *
 * Function to update appointment status
 *
 ***********/
function fn_tspa_update_appointment_status($id, $status, $notify_user = 'N')
{
	db_query("UPDATE ?:addon_tsp_appointments SET `status` = ?s WHERE `id` = ?i", $status, $id);
	
	// If the appointment is completed then change the date completd
	// if its not completed then null out the date completed
	if ($status == 'C')
	{
		db_query("UPDATE ?:addon_tsp_appointments SET `date_completed` = ?i", time());
	}//endif
	else
	{
		db_query("UPDATE ?:addon_tsp_appointments SET `date_completed` = ?i", $null);
	}//endelse
	
	if ($notify_user)
	{
		fn_tspa_notify_user($id);
	}//endif
}//end fn_tspa_update_appointment_status

/***********
 *
 * Function to update product metadata
 *
 ***********/
function fn_tspa_update_product_metadata($product_id, $field_name, $value) {
			
	if (!empty($value))
	{
		$data = array(
			'product_id' => $product_id, 
			'field_name' => $field_name,
			'value' => htmlentities(trim($value))
		);
		db_query("REPLACE INTO ?:addon_tsp_appointments_product_metadata ?e", $data);
	}//endif
	else
	{
		// Don't store a bunch of null values in the database, if a field has no value
		// simply delete it from the table
		db_query("DELETE FROM ?:addon_tsp_appointments_product_metadata WHERE `product_id` = ?i AND `field_name` = ?s", $product_id, $field_name);
	}//endif
}//end fn_tspa_update_product_metadata
?>