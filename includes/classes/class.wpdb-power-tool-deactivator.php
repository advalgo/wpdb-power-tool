<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://wpdbpowertool.com
 * @since      1.0.0
 * @package    Wpdb_Power_Tool
 *
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wpdb_Power_Tool
 * @subpackage Wpdb_Power_Tool/includes
 * @author     Jim Kerr <jim@advalgo.com>
 */
class Wpdb_Power_Tool_Deactivator {
	/**
	 * Removes all plugin stored procedures and tables only.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		require(WPDBPT_POWERTOOL_INCLUDES . '/uninstall.php');
	}

}
