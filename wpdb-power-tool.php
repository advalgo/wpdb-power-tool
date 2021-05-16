<?php
/**
 * Plugin Name			WPDB Power Tool
 *	@Package			WPDB-Power-Tool
 *	@Author				Advanced Algorythms LLC
 *	@copyright			2021 Advaned Algorythms LLC
 *	@license			GNU General Public Liscense v3.0
 *
 *	@wpdb-power-tool
 *  Plugin Name:		WPDB Power Tool
 *	Plugin URI: 		https://wpdbpowertool.com
 *	Description: 		This plugin is for a wordpress site administrator/developer/student to develop plugin, template or theme tables, perform data mining, create tables and or edit tables. It also provides the means to drop/delete tables so it is not for the indescreet!
 *	Version: 			1.0.0
 * 	Requires At Least:	4.9
 *	Requires PHP:		7.3
 *	Author: 			Advanced Algorythms LLC
 *	Author URI:			https://advalgo.com
 *	License:			GNU General Public Liscense v3.0
 *	License URI:		https://www.gnu.org/licenses/gpl-3.0.txt
 *
 *	WPDB Power Tool is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 2 of the License, or
 *	any later version.
 *
 *	WPDB Power Tool is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with WPDB Power Tool. If not, see https://www.gnu.org/licenses/gpl-3.0.txt
 *
 *	Installation:
 *	1. Download and unzip the latest release zip file.
 *	2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 *	3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 *	4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 *
 * MAJOR version when you make incompatible API changes,
 * MINOR version when you add functionality in a backwards compatible manner
 * PATCH version when you make backwards compatible bug fixes
 * Per https://semver.org specifications.
 **/

// If this file is called directly, abort.
if (!defined('WPINC')) die;

 // Making file paths easy!
define( 'WPDBPT_POWERTOOL_VERSION', '1.0.0' );
define( 'WPDBPT_POWERTOOL_ADMIN', plugin_dir_path( __FILE__ ) . 'admin');
define( 'WPDBPT_POWERTOOL_ASSETS', '/wp-content/plugins/wpdb-power-tool/assets');
define( 'WPDBPT_POWERTOOL_BACKUPS_URI', '/wp-content/plugins/wpdb-power-tool/backups');
define( 'WPDBPT_POWERTOOL_BACKUPS_DIR', plugin_dir_path( __FILE__ ) . 'backups');
define( 'WPDBPT_POWERTOOL_CLASSES', plugin_dir_path( __FILE__ ) . 'includes/classes');
define( 'WPDBPT_POWERTOOL_INCLUDES', plugin_dir_path( __FILE__ ) . 'includes');
define( 'WPDBPT_POWERTOOL_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPDBPT_DEBUG', true);

// Check for updates:
//require_once(WPDBPT_POWERTOOL_INCLUDES . '/updater.php');


/* The code that runs during plugin activation.
 * This action is documented in includes/classes/class-wpdb-power-tool-activator.php */
function activate_wpdb_power_tool() {
	require_once(WPDBPT_POWERTOOL_CLASSES .'/class.wpdb-power-tool-activator.php');
	Wpdb_Power_Tool_Activator::activate();
}

/* The code that runs during plugin deactivation.
 * This action is documented in includes/classes/class.wpdb-power-tool-deactivator.php  */
function uninstall_wpdb_power_tool() {
	require_once(WPDBPT_POWERTOOL_CLASSES .'/class.wpdb-power-tool-uninstall.php');
	Wpdb_Power_Tool_Deactivator::uninstall();
}

register_activation_hook( __FILE__, 'activate_wpdb_power_tool' );
register_uninstall_hook( __FILE__, 'uninstall_wpdb_power_tool' );
require(WPDBPT_POWERTOOL_CLASSES . '/class.wpdb_power_tool.php' );
?>
