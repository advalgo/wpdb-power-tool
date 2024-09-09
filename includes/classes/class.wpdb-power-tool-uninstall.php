<?php
/**
 * Fired during plugin uninstall:
 */
class Wpdb_Power_Tool_Deactivator {
	//	Removes all plugin stored procedures and tables.
	public static function uninstall() {
		require(WPDBPT_POWERTOOL_INCLUDES . '/uninstall.php');
	}

}
