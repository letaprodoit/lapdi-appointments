<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	func.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Hook implementations for addon
 * 
 */

if ( !defined('AREA') )	{ die('Access denied');	}

require_once 'lib/fn.appointments.php';

//---------------
// HOOKS
//---------------


/***********
 *
 * Modified by LGC - fn_tsp_appointments_finish_payment
 * Finish payment
 *
 ***********/
function fn_tsp_appointments_finish_payment($order_id, $pp_response, $force_notification)
{
	$order_info = fn_get_order_info($order_id);
	
	if (($order_info['payment_info']['order_status'] == 'P'))
	{
		// If the user purchased a service with an appointment
		fn_tspa_create_appointment($order_info);
	}//endif
}//end fn_tsp_appointments_finish_payment

/***********
 *
 * Since orders are directly related to appointments if an order is deleted, delete
 * the appointment as well
 *
 ***********/
function fn_tsp_appointments_delete_order($order_id)
{
	$appointment_id = db_get_field("SELECT `id` FROM ?:addon_tsp_appointments WHERE `order_id` = ?i", $order_id);
	
	fn_tspa_delete_appointment($appointment_id);
}//end fn_tsp_appointments_delete_order

/***********
 *
 * Delete product metadata
 *
 ***********/
function fn_tsp_appointments_delete_product_post($product_id)
{
	db_query("DELETE FROM ?:addon_tsp_appointments_product_metadata WHERE `product_id` = ?i", $product_id);
}//end fn_tsp_appointments_delete_product_post

/***********
 *
 * Function to update order_info with appointment information
 *
 ***********/
function fn_tsp_appointments_get_order_info(&$order_info, &$additional_data)
{

	foreach ($order_info['items'] as $k => $v)
	{
		$product_id = $v['product_id'];
		$product_metadata = db_get_hash_array("SELECT * FROM ?:addon_tsp_appointments_product_metadata WHERE `product_id` = $product_id", 'field_name');
		
		$product_appointment = array();
		
		// If the product has appointment data store it in the order
		if (!empty($product_metadata))
		{		
			$field_names = Registry::get('tspa_product_data_field_names');
			
			foreach ($field_names as $field_name => $fdata)
			{			
				$value = "";
				
				// only display fields that have data
				if (array_key_exists($field_name, $product_metadata))
				{				
					$value = $product_metadata[$field_name]['value'];
					if ($fdata['type'] == 'T')
					{
						$value = html_entity_decode($value);
					}//endif

					$product_appointment[] = array(
						'title' => fn_get_lang_var($field_name),
						'value' => $value
					);
				}//endif
							
			}//endforeach;
			
			$order_info['items'][$k]['extra']['product_appointment'] = $product_appointment;
			
		}//endif
				
	}//endforeach;
}//end fn_tsp_appointments_get_order_info

/***********
 *
 * Function to update the product metadata
 *
 ***********/
function fn_tsp_appointments_update_product_post(&$product_data, $product_id, $lang_code, $create){

	if (!empty($product_id) && !empty($product_data))
	{
		$field_names = Registry::get('tspa_product_data_field_names');
		
		foreach ($field_names as $field_name => $fdata)
		{
		
			if (array_key_exists($field_name, $product_data))
			{
				$value = $product_data[$field_name];
				fn_tspa_update_product_metadata($product_id, $field_name, $value);
			}//endif		
		}//endforeach;

	}//endif
}//end fn_tsp_appointments_update_product_post
?>