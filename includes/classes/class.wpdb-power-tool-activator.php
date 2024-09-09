<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wpdbpowertool.com
 * @since      1.0.0
 *
 * @package    Wpdb_Power_Tool
 * @subpackage Wpdb_Power_Tool/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpdb_Power_Tool
 * @subpackage Wpdb_Power_Tool/includes/classes
 * @author     Jim Kerr <jim@advalgo.com>
 */
class Wpdb_Power_Tool_Activator {
	/* Short Description. (use period)	 *
	 * Long Description.	             *
	 * @since    1.0.0	                 */

	public static function activate() {
		require(WPDBPT_POWERTOOL_INCLUDES . '/install.php');
	}
}
