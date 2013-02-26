<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	config.php
 * @version		1.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Configuration file for addon
 * 
 */

if ( !defined('AREA') ) { die('Access denied'); }

require_once 'lib/fn.appointments.php';

Registry::set('tspa_appointment_statuses', array(
		'O' => 'Open',
		'S' => 'Scheduled',
		'X' => 'Canceled',
		'C' => 'Completed'
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
Registry::set('tspa_product_option_location_field_id', fn_tspa_get_product_field_id('tspa_product_option_location_field_id'));

?>