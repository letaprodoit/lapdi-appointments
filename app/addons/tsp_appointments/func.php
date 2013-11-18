<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	func.php
 * @version		2.0.1
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Hook implementations for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;
use Tygh\Mailer;
use Tygh\Navigation\LastView;

require_once 'lib/fn.appointments.php';

//---------------
// HOOKS
//---------------


/**
 * Change order status
 *
 * @since 2.0.0
 *
 * @param string $status_to New order status (one char)
 * @param string $status_from Old order status (one char)
 * @param array $order_info Array with order information
 * @param array $force_notification Array with notification rules
 * @param array $order_statuses Array of order statuses.
 * @param boolean $place_order Place order or not
 * @return boolean
 *
 * @return none
 */
function fn_tsp_appointments_change_order_status( $status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order )
{
	$order_id = $order_info['order_id'];	
	$appointment_id = db_get_field("SELECT `id` FROM ?:addon_tsp_appointments WHERE `order_id` = ?i", $order_id);
	
	// If the appointment has not been created and the admin is changing the status
	if (!$appointment_id && $status_to == 'P' )
	{
		// If the user purchased a service with an appointment
		fn_tspa_create_appointment($order_info);
	}//end if
	else 
	{
		// If the order is cancelled, failed or declined then cancel the appointment
		if ( in_array( $status_to, array('I','F','D') ) )
		{
			fn_tspa_update_appointment_status($appointment_id, 'X', 'Y');
		}//end if
		else
		{
			fn_tspa_update_appointment_status($appointment_id, 'O', 'Y');
		}//end elseif
	}
}//end fn_tsp_appointments_change_order_status

/**
 * Finish payment
 *
 * @since 1.0.0
 *
 * @param int $order_id Required - The Order ID
 * @param array $pp_response Required - Response from the payment processor
 * @param bool $force_notification Required - Force email notifications
 *
 * @return none
 */
function fn_tsp_appointments_finish_payment($order_id, $pp_response, $force_notification)
{
	$order_info = fn_get_order_info($order_id);
	
	// FIXME: What is the admin want's to override payment other than a test payment
	if (($order_info['payment_info']['order_status'] == 'P'))
	{
		// If the user purchased a service with an appointment
		fn_tspa_create_appointment($order_info);
	}//endif
}//end fn_tsp_appointments_finish_payment

/**
 * Since orders are directly related to appointments if an order is deleted, delete
 * the appointment as well
 *
 * @since 1.0.0
 *
 * @param int $order_id Required - The Order ID
 *
 * @return none
 */
function fn_tsp_appointments_delete_order($order_id)
{
	$appointment_id = db_get_field("SELECT `id` FROM ?:addon_tsp_appointments WHERE `order_id` = ?i", $order_id);
	
	fn_tspa_delete_appointment($appointment_id);
}//end fn_tsp_appointments_delete_order

/**
 * Delete product metadata
 *
 * @since 1.0.0
 *
 * @param int $product_id Required - The Product ID
 *
 * @return none
 */
function fn_tsp_appointments_delete_product_post($product_id)
{
	db_query("DELETE FROM ?:addon_tsp_appointments_product_metadata WHERE `product_id` = ?i", $product_id);
}//end fn_tsp_appointments_delete_product_post

/**
 * Function to update order_info with appointment information
 *
 * @since 1.0.0
 *
 * @param array $order_info Required (Ref) - Order information
 * @param array $additional_data Required (Ref) - Additional order information
 *
 * @return none
 */
function fn_tsp_appointments_get_order_info(&$order_info, &$additional_data)
{
	$key = 'products';
	
	if (array_key_exists( $key, $order_info ))
	{
		foreach ($order_info[$key] as $k => $v)
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
							$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
						}//endif
		
						$product_appointment[] = array(
								'title' => __($field_name),
								'value' => $value
						);
					}//endif
						
				}//endforeach;
					
				
				$order_info[$key][$k]['extra']['product_appointment'] = $product_appointment;
			}//endif
		}//endforeach;
	}//end if
}//end fn_tsp_appointments_get_order_info

/**
 * Function to update the product metadata
 *
 * @since 1.0.0
 *
 * @param array $product_data Required (Ref) - Product information
 * @param int $product_id Required - Product ID
 * @param string $lang_code Required - The language code for the product
 * @param bool $create Required - Boolean to create or update product
 *
 * @return none
 */
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