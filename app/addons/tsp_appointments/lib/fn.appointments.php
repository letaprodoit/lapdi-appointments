<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	fun.appointments.php
 * @version		2.1.2
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2014 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Helper functions for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;
use Tygh\Mailer;
use Tygh\Navigation\LastView;

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
	db_query("DELETE FROM ?:language_values WHERE name LIKE 'tspa_%'");
}//end fn_tspa_uninstall_languages

/***********
 *
 * Function to uninstall product fields
 *
 ***********/
function fn_tspa_install_product_fields () 
{	
	$default_option_fields = array(
		'tspa_product_option_date_field_id',
		'tspa_product_option_time_field_id',
		'tspa_product_option_duration_field_id',
		'tspa_product_option_location_field_id',
		'tspa_product_option_additional_info_field_id',
	);
	
	foreach ( $default_option_fields as $option_field_key )
	{
		// check to see if the field is already in the table (the global option already added) if it is not
		// then add it
		if ( !fn_tspa_get_product_field_id($option_field_key) )
		{
			if ($option_field_key == 'tspa_product_option_date_field_id')
			{
				// Install the global option fields
				$date_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 100, 'option_type' => 'D', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A'));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`) VALUES ('$option_field_key',$date_id)");
				
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $date_id, 'option_name' => __("tspa_appointment_date",array(),'en'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_date_comment",array(),'en'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $date_id, 'option_name' => __("tspa_appointment_date",array(),'el'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_date_comment",array(),'el'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $date_id, 'option_name' => __("tspa_appointment_date",array(),'es'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_date_comment",array(),'es'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $date_id, 'option_name' => __("tspa_appointment_date",array(),'fr'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_date_comment",array(),'fr'), 'inner_hint' => '', 'incorrect_message' => ''));
			}//end if
			elseif ($option_field_key == 'tspa_product_option_time_field_id')
			{
				// Install the global option fields
				$time_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 101, 'option_type' => 'I', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A', 'regexp' => '(\\d\\d:\\d\\d) (AM|PM) (\\w\\w\\w)'));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`) VALUES ('$option_field_key',$time_id)");
				
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $time_id, 'option_name' => __("tspa_appointment_time",array(),'en'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_time_comment",array(),'en'), 'inner_hint' => '12:00 AM EST', 'incorrect_message' => __("tspa_incorrect_format",array(),'en')));				
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $time_id, 'option_name' => __("tspa_appointment_time",array(),'el'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_time_comment",array(),'el'), 'inner_hint' => '12:00 AM EST', 'incorrect_message' => __("tspa_incorrect_format",array(),'el')));				
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $time_id, 'option_name' => __("tspa_appointment_time",array(),'es'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_time_comment",array(),'es'), 'inner_hint' => '12:00 AM EST', 'incorrect_message' => __("tspa_incorrect_format",array(),'es')));				
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $time_id, 'option_name' => __("tspa_appointment_time",array(),'fr'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_time_comment",array(),'fr'), 'inner_hint' => '12:00 AM EST', 'incorrect_message' => __("tspa_incorrect_format",array(),'fr')));				
			}//end if
			elseif ($option_field_key == 'tspa_product_option_duration_field_id')
			{
				// Install the global option fields
				$duration_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 102, 
						'option_type' => 'I', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A', 
						'regexp' => '(\\d+)(\\s)(\\w+)'));				
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`) VALUES ('$option_field_key',$duration_id)");
				
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $duration_id, 'option_name' => __("tspa_appointment_duration",array(),'en'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_duration_comment",array(),'en'), 'inner_hint' => '10 ' . __("tspa_minutes",array(),'en'), 'incorrect_message' => __("tspa_incorrect_format",array(),'en')));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $duration_id, 'option_name' => __("tspa_appointment_duration",array(),'el'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_duration_comment",array(),'el'), 'inner_hint' => '10 ' . __("tspa_minutes",array(),'el'), 'incorrect_message' => __("tspa_incorrect_format",array(),'el')));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $duration_id, 'option_name' => __("tspa_appointment_duration",array(),'es'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_duration_comment",array(),'es'), 'inner_hint' => '10 ' . __("tspa_minutes",array(),'es'), 'incorrect_message' => __("tspa_incorrect_format",array(),'es')));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $duration_id, 'option_name' => __("tspa_appointment_duration",array(),'fr'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_duration_comment",array(),'fr'), 'inner_hint' => '10 ' . __("tspa_minutes",array(),'fr'), 'incorrect_message' => __("tspa_incorrect_format",array(),'fr')));
			}//end if
			elseif ($option_field_key == 'tspa_product_option_location_field_id')
			{
				// Install the global option fields
				$location_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 103, 'option_type' => 'S', 'inventory' => 'N', 'required' => 'Y', 'status' => 'A'));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`) VALUES ('$option_field_key',$location_id)");
				
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $location_id, 'option_name' => __("tspa_appointment_location",array(),'en'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_location_comment",array(),'en'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $location_id, 'option_name' => __("tspa_appointment_location",array(),'el'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_location_comment",array(),'el'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $location_id, 'option_name' => __("tspa_appointment_location",array(),'es'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_location_comment",array(),'es'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $location_id, 'option_name' => __("tspa_appointment_location",array(),'fr'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_location_comment",array(),'fr'), 'inner_hint' => '', 'incorrect_message' => ''));
				
				// Install option variants
				$var1 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 0, 'option_id' => $location_id, 'modifier' => 0.00));
				$var2 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 5, 'option_id' => $location_id, 'modifier' => 0.00));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`,`variant_id`) VALUES
				('tspa_product_option_location_field_vars',$location_id,$var1),
				('tspa_product_option_location_field_vars',$location_id,$var2)");
				
				// Install option variant descriptions
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var1, 'variant_name' => __("tspa_in_home",array(),'en')));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var2, 'variant_name' => __("tspa_on_site",array(),'en')));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var1, 'variant_name' => __("tspa_in_home",array(),'el')));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var2, 'variant_name' => __("tspa_on_site",array(),'el')));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var1, 'variant_name' => __("tspa_in_home",array(),'es')));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var2, 'variant_name' => __("tspa_on_site",array(),'es')));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var1, 'variant_name' => __("tspa_in_home",array(),'fr')));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var2, 'variant_name' => __("tspa_on_site",array(),'fr')));
				
			}//end if
			elseif ($option_field_key == 'tspa_product_option_additional_info_field_id')
			{
				// Install the global option fields
				$info_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 104, 'option_type' => 'T', 'inventory' => 'N', 'required' => 'N', 'status' => 'A'));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_appointments_product_field_metadata (`key`,`option_id`) VALUES ('$option_field_key',$info_id)");
				
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $info_id, 'option_name' => __("tspa_appointment_info",array(),'en'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_info_comment",array(),'en'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $info_id, 'option_name' => __("tspa_appointment_info",array(),'el'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_info_comment",array(),'el'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $info_id, 'option_name' => __("tspa_appointment_info",array(),'es'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_info_comment",array(),'es'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $info_id, 'option_name' => __("tspa_appointment_info",array(),'fr'), 'option_text' => '', 'description' => '', 'comment' => __("tspa_appointment_info_comment",array(),'fr'), 'inner_hint' => '', 'incorrect_message' => ''));
				
			}//end if
		}//end if
	}//end foreach
}//end fn_tspa_install_product_fields

/***********
 *
 * Function to uninstall product filed metadata
 *
 ***********/
function fn_tspa_uninstall_product_field_metadata () 
{
	if (Registry::get('addons.tsp_appointments.delete_appointment_data') == 'Y')
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
	}//endif
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

/***********
 *
* Function to uninstall main table
*
***********/
function fn_tspa_uninstall_main()
{
	if (Registry::get('addons.tsp_appointments.delete_appointment_data') == 'Y')
	{
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_appointments`");
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
		$extra = fn_tspa_get_order_details( $appt['order_id'], $appt['product_id'] );
	
		if ( array_key_exists( 'product_options_value', $extra ) )
		{
			$product_options = $extra['product_options_value'];
			
			foreach ( $product_options as $pos => $field )
			{
				$option_id = $field['option_id'];
				
				list ($description, $value) = fn_tspa_get_product_option_info($option_id, $field['value']);
				
				if ( Registry::get('tspa_product_option_date_field_id') == $option_id )
				{
					$appointments[$appt_id][$key]['date'] = $value;
				}//endif
				elseif ( Registry::get('tspa_product_option_time_field_id') == $option_id )
				{
					$appointments[$appt_id][$key]['time'] = $value;
				}//end elseif
				elseif ( Registry::get('tspa_product_option_duration_field_id') == $option_id )
				{
					$appointments[$appt_id][$key]['duration'] = $value;
				}//end elseif
				elseif ( Registry::get('tspa_product_option_location_field_id') == $option_id )
				{
					$appointments[$appt_id][$key]['location'] = $value;
				}//end elseif
				elseif ( Registry::get('tspa_product_option_additional_info_field_id') == $option_id )
				{
					$appointments[$appt_id][$key]['additional_info'] = $value;
				}//end elseif										
			}//end foreach
		}//end if
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
	
	$order_key = 'products';
	
	// Search through ordered items to find the product that has an appointment
	foreach ($order_info[$order_key] as $item_id => $product)
	{	
		$product_id = $product['product_id'];
		
		$extra = fn_tspa_get_order_details( $order_id, $product_id );
		
		if ( array_key_exists('product_options_value', $extra) )
		{
			$product_options = $extra['product_options_value'];
			
			if (fn_tspa_product_contains_appointment($product_options))
			{
				$company_id = db_get_field("SELECT company_id FROM ?:products WHERE product_id = ?i", $product_id);
				
				$data = array(
						'status' => 'O',
						'company_id' => $company_id,
						'order_id' => $order_id,
						'product_id' => $product_id,
						'user_id' => $user_id,
						'date_created' => time(),
				);
			
				db_query("INSERT INTO ?:addon_tsp_appointments ?e", $data);
			}//endif
		}//end if		
	}//endforeach;	
}//end fn_tspa_create_appointment


/***********
 *
 * Delete the appointment
 *
 ***********/
function fn_tspa_delete_appointment($id) 
{	
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
    $params = LastView::instance()->update('appointments', $params);

    $default_params = array (
        'page' => 1,
        'items_per_page' => $items_per_page,
    	'company' => Registry::get('runtime.company_id'),
    );

    $params = array_merge($default_params, $params);

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
		'appointment_id' => "?:addon_tsp_appointments.id",
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
		$params['sort_by'] = 'appointment_id';
	}//endif

	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']] . ', ', $sortings[$params['sort_by']]) : $sortings[$params['sort_by']]) . " " . $directions[$params['sort_order']];

	// Reverse sorting (for usage in view)
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';

	$join = $condition = '';

	if (!empty($params['company'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_appointments.company_id = ?i", $params['company']);
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
	$limit = db_paginate($params['page'], $total, $items_per_page);

	$appointments = db_get_hash_array("SELECT " . implode(', ', $fields) . " FROM ?:addon_tsp_appointments LEFT JOIN ?:users ON ?:addon_tsp_appointments.user_id = ?:users.user_id WHERE 1 $condition ORDER BY $sorting $limit", 'id');
	
	fn_tspa_add_appointment_data_to_array($appointments,'data');
	
	LastView::instance()->processResults('appointments', $appointments, $params, $items_per_page);

	return array($appointments, $params);
}//end fn_tspa_get_appointments

/**
 * Get order color
 *
 * @param char $status Required the appointment status
 * @return char the equivalent order status
 */
function fn_tspa_get_order_color_status ( $status )
{
	$statuses = Registry::get('tspa_appointment_statuses_long');
	
	if ( array_key_exists( $status, $statuses ) )
	{
		return $statuses[$status]['color_status'];
	}//end if
}//end fn_tspa_get_order_color_status


/**
 * Get order data
 *
 * @param int $order_id Required the order id
 * @return array statuses list
 */
function fn_tspa_get_order_data( $order_id )
{
	$data = db_get_field("SELECT `data` FROM ?:order_data WHERE `order_id` = ?i AND `type` = ?s", $order_id, 'G');	
	$data = @unserialize($data);
	
	if ( is_array($data) )
	{
		return $data;
	}//end if
	else 
	{
		return array();
	}//end else
}//end fn_tspa_get_order_data

/**
 * Get order details
 *
 * @param int $order_id Required the order id
 * @param int $product_id Required the product id
 * @return array statuses list
 */
function fn_tspa_get_order_details( $order_id, $product_id )
{
	$extra = db_get_field("SELECT `extra` FROM ?:order_details WHERE `order_id` = ?i AND `product_id` = ?i", $order_id, $product_id);
	$extra = @unserialize($extra);
	
	if ( is_array($extra) )
	{
		return $extra;
	}//end if
	else 
	{
		return array();
	}//end else
}//end fn_tspa_get_order_details

/***********
 *
 * There are 4 fields that are required for any appointment and they are
 * time, date and location, this function will get the option keys from
 * the appointments product metadata table
 *
 ***********/
function fn_tspa_get_product_field_id($key)
{
	$field_id = null;
	
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
		
	$extra = fn_tspa_get_order_details( $order_id, $product_id );
	
	if ( array_key_exists( 'product_options_value', $extra ) )
	{
		$product_options = $extra['product_options_value'];
	
		foreach ($product_options as $pos => $field)
		{
			if ($option_id == $field['option_id'])
			{
				list ($description, $value) = fn_tspa_get_product_option_info($option_id, $field['value'], $value_only);
			}//endif
				
		}//endforeach;
	}
		
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
	$store_lang = (DEFAULT_LANGUAGE != null) ? DEFAULT_LANGUAGE : CART_LANGUAGE;
	
	$desc = db_get_field("SELECT `option_name` FROM ?:product_options_descriptions WHERE `option_id` = ?i AND `lang_code` = ?s", $option_id, $store_lang);
	$val = $option_value;
	
	$option_type = db_get_field("SELECT `option_type` FROM ?:product_options WHERE `option_id` = ?i", $option_id);

	if ($option_type == 'S' && !$value_only)
	{
		$val = db_get_field("SELECT opt_desc.variant_name FROM ?:product_option_variants_descriptions AS opt_desc LEFT JOIN ?:product_option_variants AS opt_var ON opt_desc.variant_id = opt_var.variant_id WHERE opt_var.option_id = ?i AND opt_var.variant_id = ?i AND opt_desc.lang_code = ?s", $option_id, $option_value, $store_lang);
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
	$store_lang = (DEFAULT_LANGUAGE != null) ? DEFAULT_LANGUAGE : CART_LANGUAGE;
	
	return db_get_hash_array("SELECT opt_desc.* FROM ?:product_option_variants_descriptions AS opt_desc 
	LEFT JOIN ?:product_option_variants AS opt_var ON opt_desc.variant_id = opt_var.variant_id 
	WHERE opt_var.option_id = $option_id AND opt_desc.lang_code = '$store_lang'",$key);
}//end fn_tspa_get_product_option_select_values


/***********
 *
 * Function to notify user of appointment change
 *
 ***********/
function fn_tspa_notify_user($id)
{
	$appointment = db_get_row("SELECT * FROM ?:addon_tsp_appointments WHERE id = ?i", $id);
	
	// if the appointment exists
	if (!empty( $appointment ))
	{
		$statuses = Registry::get('tspa_appointment_statuses_short');
		
		$appointment['status'] = $statuses[$appointment['status']];
		$appointment['info'] = '';
		
		// get order/appointment data
		$extra = fn_tspa_get_order_details( $appointment['order_id'], $appointment['product_id'] );
		
		if ( array_key_exists( 'product_options_value', $extra ) )
		{
			$product_options = $extra['product_options_value'];
		
			foreach ( $product_options as $pos => $field )
			{
				$option_id = $field['option_id'];
					
				list($description, $value) = fn_tspa_get_product_option_info($option_id, $field['value']);
				$appointment['info'] .= "<strong>$description:</strong>  $value<br>\n";
			}//end foreach
		}//end if
		
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
		
		// Send a copy to the customer
		Mailer::sendMail(array(
		'to' => $appointment['user']['email'],
		'from' => 'default_company_orders_department',
		'reply_to' => Registry::get('settings.Company.company_orders_department'),
		'data' => array(
		'appointment' => $appointment,
		'profile_fields' => fn_get_profile_fields('I', '', CART_LANGUAGE),
		),
		'tpl' => 'addons/tsp_appointments/appointment_notification.tpl',
		), 'C', Registry::get('settings.Appearance.backend_default_language'));
		
		// Send a copy to the staff
		Mailer::sendMail(array(
		'to' => Registry::get('settings.Company.company_orders_department'),
		'from' => 'default_company_orders_department',
		'reply_to' => Registry::get('settings.Company.company_orders_department'),
		'data' => array(
		'appointment' => $appointment,
		'profile_fields' => fn_get_profile_fields('I', '', CART_LANGUAGE),
		),
		'tpl' => 'addons/tsp_appointments/appointment_notification.tpl',
		), 'C', Registry::get('settings.Appearance.backend_default_language'));
	}//end if
}//end fn_tspa_notify_user

/***********
 *
 * check product options to check to see if it is an appointment
 * appointments all contain a date, time and location
 *
 ***********/
function fn_tspa_product_contains_appointment(&$product_options)
{
	$contains_appointment = false;
	// required fields
	$date_found = false;
	$time_found = false;
	$duration_found = false;
	$location_found = false;
	
	foreach ($product_options as $pos => $field)
	{	
		$option_id = $field['option_id'];
		
		if ($option_id == Registry::get('tspa_product_option_date_field_id'))
		{
			$date_found = true;
		}//endif
		elseif ($option_id == Registry::get('tspa_product_option_time_field_id'))
		{
			$time_found = true;
		}//endelseif
		elseif ($option_id == Registry::get('tspa_product_option_duration_field_id'))
		{
			$duration_found = true;
		}//endelseif;
		elseif ($option_id == Registry::get('tspa_product_option_location_field_id'))
		{
			$location_found = true;
		}//endelseif;		
		
	}//endforeach;
	
	if ($date_found && $time_found && $duration_found && $location_found)
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
function fn_tspa_update_appointment($id, &$data)
{
	$appointment = db_get_row("SELECT * FROM ?:addon_tsp_appointments WHERE id = ?i", $id);
	
	if (!empty( $appointment ))
	{
		fn_tspa_update_order_details($appointment['order_id'], $appointment['product_id'], $data);
		fn_tspa_update_order_data($appointment['order_id'], $appointment['product_id'], $data);		
	}//end if
}//end fn_tspa_update_appointment

/***********
 *
 * Function to update appointment status
 *
 ***********/
function fn_tspa_update_appointment_status($id, $status, $notify_user = 'N')
{
	$updated = false;
	
	// get current status
	$current_status = db_get_field("SELECT `status` FROM ?:addon_tsp_appointments WHERE `id` = ?i", $id);
	
	// update the status only if its changed
	if ( $current_status != $status )
	{
		db_query("UPDATE ?:addon_tsp_appointments SET `status` = ?s WHERE `id` = ?i", $status, $id);
		
		// If the appointment is completed then change the date completd
		// if its not completed then null out the date completed
		if ($status == 'C')
		{
			db_query("UPDATE ?:addon_tsp_appointments SET `date_completed` = ?i WHERE `id` = ?i", time(), $id);
		}//endif
		else
		{
			db_query("UPDATE ?:addon_tsp_appointments SET `date_completed` = ?i WHERE `id` = ?i", null, $id);
		}//endelse
		
		if ($notify_user == 'Y')
		{
			fn_tspa_notify_user($id);
		}//endif
		
		$updated = true;
	}//end if
	
	return $updated;
}//end fn_tspa_update_appointment_status

/**
 * Update the order data
 *
 * @param int $order_id Required the order id
 * @param int $product_id Required the product id
 * @param array $data Required the data to update the record with
 * @return none
 */
function fn_tspa_update_order_data( $order_id, $product_id, &$data )
{
	$order_data = fn_tspa_get_order_data( $order_id );

	foreach ( $order_data as $pos => $record )
	{
		foreach ( $record as $key => $value )
		{
			if ( $key == 'products' )
			{
				foreach ( $value as $item_id => $product_record )
				{
					if ( $product_record['product_id'] == $product_id )
					{
						$product_options = $product_record['product_options'];
						
						if ( !empty( $product_options ) )
						{
							foreach ( $product_options as $option_id => $option_value )
							{									
								if ( $option_id == Registry::get('tspa_product_option_date_field_id'))
								{
									$order_data[$pos][$key][$item_id]['product_options'][$option_id] = $data['date'];
									$order_data[$pos][$key][$item_id]['extra']['product_options'][$option_id] = $data['date'];
								}//end if
								elseif ( $option_id == Registry::get('tspa_product_option_time_field_id'))
								{
									$order_data[$pos][$key][$item_id]['product_options'][$option_id] = $data['time'];
									$order_data[$pos][$key][$item_id]['extra']['product_options'][$option_id] = $data['time'];
								}//end elseif
								elseif ( $option_id == Registry::get('tspa_product_option_duration_field_id'))
								{
									$order_data[$pos][$key][$item_id]['product_options'][$option_id] = $data['duration'];
									$order_data[$pos][$key][$item_id]['extra']['product_options'][$option_id] = $data['duration'];
								}//end elseif
								elseif ( $option_id == Registry::get('tspa_product_option_location_field_id'))
								{
									$order_data[$pos][$key][$item_id]['product_options'][$option_id] = $data['location'];
									$order_data[$pos][$key][$item_id]['extra']['product_options'][$option_id] = $data['location'];
								}//end elseif
								elseif ( $option_id == Registry::get('tspa_product_option_additional_info_field_id'))
								{
									$order_data[$pos][$key][$item_id]['product_options'][$option_id] = $data['additional_info'];
									$order_data[$pos][$key][$item_id]['extra']['product_options'][$option_id] = $data['additional_info'];
								}//end elseif
							}//end foreach
						}//end if
					}//end if
				}//end foreach
			}//end if
		}//end foreach
	}//end foreach

	db_query('UPDATE ?:order_data SET ?u WHERE order_id = ?i AND type = ?s', array('data' => @serialize($order_data)), $order_id, 'G');								
}//end fn_tspa_update_order_data

/**
 * Update the order details
 *
 * @param int $order_id Required the order id
 * @param int $product_id Required the product id
 * @param array $data Required the data to update the record with
 * @return none
 */
function fn_tspa_update_order_details( $order_id, $product_id, &$data )
{
	$extra = fn_tspa_get_order_details( $order_id, $product_id );
	
	if ( array_key_exists( 'product_options_value', $extra ) )
	{
		$product_options = $extra['product_options_value'];
	
		foreach ( $product_options as $pos => $field )
		{
			$option_id = $field['option_id'];
				
			if ( $option_id == Registry::get('tspa_product_option_date_field_id'))
			{
				$extra['product_options'][$option_id] = $data['date'];
				$extra['product_options_value'][$pos]['value'] = $data['date'];
				$extra['product_options_value'][$pos]['variant_name'] = $data['date'];
			}//end if
			elseif ( $option_id == Registry::get('tspa_product_option_time_field_id'))
			{
				$extra['product_options'][$option_id] = $data['time'];
				$extra['product_options_value'][$pos]['value'] = $data['time'];
				$extra['product_options_value'][$pos]['variant_name'] = $data['time'];
			}//end elseif
			elseif ( $option_id == Registry::get('tspa_product_option_duration_field_id'))
			{
				$extra['product_options'][$option_id] = $data['duration'];
				$extra['product_options_value'][$pos]['value'] = $data['duration'];
				$extra['product_options_value'][$pos]['variant_name'] = $data['duration'];
			}//end elseif
			elseif ( $option_id == Registry::get('tspa_product_option_location_field_id'))
			{
				list($loc_desc, $loc_val) = fn_tspa_get_product_option_info($option_id, $data['location']);
				
				$extra['product_options'][$option_id] = $data['location'];
				$extra['product_options_value'][$pos]['value'] = $data['location'];
				$extra['product_options_value'][$pos]['variant_name'] = $loc_val;
			}//end elseif
			elseif ( $option_id == Registry::get('tspa_product_option_additional_info_field_id'))
			{
				$extra['product_options'][$option_id] = $data['additional_info'];
				$extra['product_options_value'][$pos]['value'] = $data['additional_info'];
				$extra['product_options_value'][$pos]['variant_name'] = $data['additional_info'];
			}//end elseif
		}//end foreach

		db_query('UPDATE ?:order_details SET ?u WHERE order_id = ?i AND product_id = ?i', array('extra' => @serialize($extra)), $order_id, $product_id);		
	}//end if
}//end fn_tspa_update_order_details

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
			'value' => trim($value)
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