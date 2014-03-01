<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Appointments for CS-Cart
 * @filename	admin.post.php
 * @version		2.0.6
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Admin post permissions for menus
 * 
 */

$schema['appointments'] = array (
	'permissions' => 'manage_appointments',
);
$schema['tools']['modes']['update_status']['param_permissions']['table']['addon_tsp_appointments'] = 'manage_appointments';

return $schema;

?>