<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	config.php
 * @version		2.0.1
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Configuration file for addon
 * 
 */

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

use Tygh\Registry;

require_once 'lib/fn.appointments.php';

Registry::set('tspa_appointment_statuses_long', array(
		'O' => array(
			'status_id' 	=> 1,
			'status' 		=> 'O',
			'color_status'	=> 'O',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> 'Open',
			'email_subj' 	=> 'has been created',
			'email_header' 	=> 'Your appointment has been created successfully.',
			'lang_code' 	=> 'en',
		),
		'S' => array(
			'status_id' 	=> 2,
			'status' 		=> 'S',
			'color_status'	=> 'B',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> 'Scheduled',
			'email_subj' 	=> 'has been scheduled',
			'email_header' 	=> 'Your appointment has been scheduled successfully.',
			'lang_code' 	=> 'en',
		),
		'X' => array(
			'status_id' 	=> 3,
			'status' 		=> 'X',
			'color_status'	=> 'F',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> 'Canceled',
			'email_subj' 	=> 'has been canceled',
			'email_header' 	=> 'Your appointment has been canceled successfully.',
			'lang_code' 	=> 'en',
		),
		'C' => array(
			'status_id' 	=> 4,
			'status' 		=> 'C',
			'color_status'	=> 'P',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> 'Completed',
			'email_subj' 	=> 'has been completed',
			'email_header' 	=> 'Your appointment has been completed successfully.',
			'lang_code' 	=> 'en',
		),
));

Registry::set('tspa_appointment_statuses_short', array(
		'O' => 'Open',
		'S' => 'Scheduled',
		'X' => 'Canceled',
		'C' => 'Completed'
));

Registry::set('tspa_appointment_status_params', array(
		'color' => array (
				'type' => 'color',
				'label' => 'color'
		),
		'notify' => array (
				'type' => 'checkbox',
				'label' => 'notify_customer',
				'default_value' => 'Y'
		),
));

// Field types: 
// admin_only (hidden on customer side), type [S (selectbox), H(selectbox, hash values),T (textarea),I (input),D (date),C (checkbox), U (URL)], 
// options (single dim array), options_func (function name to call at run-time, use with type H or S), 
// title, name (field name), value, icon (used with type U), width (with of field), class (css), hint, readonly (show text only)
Registry::set('tspa_product_data_field_names', array(
	'tspa_meeting_date' => array(
		'type' => 'D'
	),
	'tspa_meeting_time' => array(
		'type' => 'I',
		'hint' => '12:00 PM CST'
	),
	'tspa_meeting_duration' => array(
		'type' => 'I',
		'hint' => '30 Minutes'
	),
	'tspa_meeting_location' => array(
		'type' => 'S',
		'options' => array(
			'WebEx',
			'Skype',
			'Conference Call',
			'Classroom'
		)
	),
	'tspa_meeting_info' => array(
		'type' => 'T',
	),
));

// Fields necessary for storing product data
Registry::set('tspa_product_option_date_field_id', fn_tspa_get_product_field_id('tspa_product_option_date_field_id'));
Registry::set('tspa_product_option_time_field_id', fn_tspa_get_product_field_id('tspa_product_option_time_field_id'));
Registry::set('tspa_product_option_duration_field_id', fn_tspa_get_product_field_id('tspa_product_option_duration_field_id'));
Registry::set('tspa_product_option_location_field_id', fn_tspa_get_product_field_id('tspa_product_option_location_field_id'));
Registry::set('tspa_product_option_additional_info_field_id', fn_tspa_get_product_field_id('tspa_product_option_additional_info_field_id'));

?>