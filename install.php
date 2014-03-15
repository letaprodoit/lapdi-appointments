<?php
/*
 * TSP Appointments CS-Cart Addon
 *
 * @package		TSP Appointments CS-Cart Addon
 * @filename	install.php
 * @version		1.0.3
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Script to copy addon files to their respective locations
 * 
 */

$source_loc			= '.';
$target_loc 		= '';
$theme_folder_name  = '';

$addon_name 		= 'tsp_appointments';
$addon_dir			= 'addons';

$source_theme_dir		= 'skins/basic';
$source_theme_var_dir	= 'var/skins_repository/basic';

$target_theme_dir		= 'skins/'.$theme_folder_name;
$target_theme_var_dir	= 'var/skins_repository/'.$theme_folder_name;

if (empty( $target_loc ))
{
	echo "You must specify a target location on line #16 of this script.";
}//end if
elseif (!file_exists( $target_loc ))
{
	echo "The $target_loc (target location) specified does not exist on line #16 of this script.";
}//end elseif
elseif (empty( $theme_folder_name ))
{
	echo "You must specify a theme folder name location (ie `basic`) on line #17 of this script.";
}//end elseif
elseif(!file_exists( "$target_loc/$target_theme_dir" ))
{
	echo "The $target_theme_dir (target theme) specified does not exist on line #17 of this script.";
}//end elseif
else
{
	// Copy files from addons directory to the target addons directory
	shell_exec( " cp -R -v -a $source_loc/$addon_dir/$addon_name $target_loc/$addon_dir/ " );

	// Copy files from theme directory to the target theme directory
	shell_exec( " cp -R -v -a $source_loc/$source_theme_dir/admin/addons/$addon_name $target_loc/$target_theme_dir/admin/addons/ " );
	shell_exec( " cp -R -v -a $source_loc/$source_theme_dir/customer/addons/$addon_name $target_loc/$target_theme_dir/customer/addons/ " );
	shell_exec( " cp -R -v -a $source_loc/$source_theme_dir/mail/addons/$addon_name $target_loc/$target_theme_dir/mail/addons/ " );

	// Copy files from theme directory to the target var/ directory
	shell_exec( " cp -R -v -a $source_loc/$source_theme_var_dir/admin/addons/$addon_name $target_loc/$target_theme_var_dir/admin/addons/ " );
	shell_exec( " cp -R -v -a $source_loc/$source_theme_var_dir/customer/addons/$addon_name $target_loc/$target_theme_var_dir/customer/addons/ " );
	shell_exec( " cp -R -v -a $source_loc/$source_theme_var_dir/mail/addons/$addon_name $target_loc/$target_theme_var_dir/mail/addons/ " );
}//end else
?>