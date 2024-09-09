<?php
/**
 *	Plugin Name: WPDB Power Tool
 *	Plugin URI: http://wpdbpowertool.com
 *	Description: This plugin is for a wordpress site administrator/developer/student to develop plugin, template or theme tables, perform data mining, create tables and or edit tables. It also provides the means to drop/delete tables so it is not for the indescreet!
 *	Version: 1.0
 *	Author: Jim Kerr - Advanced Algorythms LLC
 *	Author URI: http://advalgo.com/
 *	Advanced Algorythms Package: wpdb-power-tool
 *
 *	Installation:
 *	1. Download and unzip the latest release zip file.
 *	2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 *	3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 *	4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 *
 */
// If this file is called directly, abort.
if (!defined('WPINC')) die;
/* MAJOR version when you make incompatible API changes,
 * MINOR version when you add functionality in a backwards compatible manner
 * PATCH version when you make backwards compatible bug fixes
 * Per https://semver.org specifications.   */
 // Making file paths easy!
define( 'WPDBPT_POWERTOOL_VERSION', '1.0.0' );
define( 'WPDBPT_POWERTOOL_ADMIN', plugin_dir_path( __FILE__ ) . 'admin');
define( 'WPDBPT_POWERTOOL_ASSETS', '/wp-content/plugins/wpdb-power-tool/assets');
define( 'WPDBPT_POWERTOOL_CLASSES', plugin_dir_path( __FILE__ ) . 'includes/classes');
define( 'WPDBPT_POWERTOOL_INCLUDES', plugin_dir_path( __FILE__ ) . 'includes');
define( 'WPDBPT_POWERTOOL_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPDBPT_DEBUG', true);

/* The code that runs during plugin activation.
 * This action is documented in includes/classes/class-wpdb-power-tool-activator.php */
function activate_wpdb_power_tool() {
	require_once(WPDBPT_POWERTOOL_CLASSES .'/class.wpdb-power-tool-activator.php');
	Wpdb_Power_Tool_Activator::activate();
}

/* The code that runs during plugin deactivation.
 * This action is documented in includes/classes/class.wpdb-power-tool-deactivator.php  */
function deactivate_wpdb_power_tool() {
	require_once(WPDBPT_POWERTOOL_CLASSES .'/class.wpdb-power-tool-deactivator.php');
	Wpdb_Power_Tool_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpdb_power_tool' );
register_deactivation_hook( __FILE__, 'deactivate_wpdb_power_tool' );

require(WPDBPT_POWERTOOL_CLASSES . '/class.wpdb_power_tool.php' );
?>
