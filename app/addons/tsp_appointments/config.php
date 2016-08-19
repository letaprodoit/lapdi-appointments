<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	config.php
 * @version		2.1.4
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2016 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Configuration file for addon
 * 
 */

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

use Tygh\Registry;

require_once 'lib/fn.appointments.php';

$store_lang = (DEFAULT_LANGUAGE != null) ? DEFAULT_LANGUAGE : CART_LANGUAGE;

Registry::set('tspa_appointment_statuses_long', array(
		'O' => array(
			'status_id' 	=> 1,
			'status' 		=> 'O',
			'color_status'	=> 'O',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> __("tspa_open", array(), $store_lang),
			'email_subj' 	=> __("tspa_open_email_subj", array(), $store_lang),
			'email_header' 	=> __("tspa_open_email_header", array(), $store_lang),
			'lang_code' 	=> $store_lang,
		),
		'S' => array(
			'status_id' 	=> 2,
			'status' 		=> 'S',
			'color_status'	=> 'B',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> __("tspa_scheduled", array(), $store_lang),
			'email_subj' 	=> __("tspa_scheduled_email_subj", array(), $store_lang),
			'email_header' 	=> __("tspa_scheduled_email_header", array(), $store_lang),
			'lang_code' 	=> $store_lang,
		),
		'X' => array(
			'status_id' 	=> 3,
			'status' 		=> 'X',
			'color_status'	=> 'F',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> __("tspa_canceled", array(), $store_lang),
			'email_subj' 	=> __("tspa_canceled_email_subj", array(), $store_lang),
			'email_header' 	=> __("tspa_canceled_email_header", array(), $store_lang),
			'lang_code' 	=> $store_lang,
		),
		'C' => array(
			'status_id' 	=> 4,
			'status' 		=> 'C',
			'color_status'	=> 'P',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> __("tspa_completed", array(), $store_lang),
			'email_subj' 	=> __("tspa_completed_email_subj", array(), $store_lang),
			'email_header' 	=> __("tspa_completed_email_header", array(), $store_lang),
			'lang_code' 	=> $store_lang,
		),
));

Registry::set('tspa_appointment_statuses_short', array(
		'O' => __("tspa_open", array(), $store_lang),
		'S' => __("tspa_scheduled", array(), $store_lang),
		'X' => __("tspa_canceled", array(), $store_lang),
		'C' => __("tspa_completed", array(), $store_lang)
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
		'hint' => '30 '.__("tspa_minutes", array(), $store_lang)
	),
	'tspa_meeting_location' => array(
		'type' => 'S',
		'options' => array(
			__("tspa_webex", array(), $store_lang),
			__("tspa_skype", array(), $store_lang),
			__("tspa_call", array(), $store_lang),
			__("tspa_class", array(), $store_lang)
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