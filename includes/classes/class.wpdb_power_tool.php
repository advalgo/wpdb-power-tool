<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpdbpowertool.com
 * @since      1.0.0
 * @package    Wpdb_Power_Tool
 * @subpackage Wpdb_Power_Tool/includes
 * The core plugin class.
 *
 * This is used to define admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpdb_Power_Tool
 * @subpackage Wpdb_Power_Tool/includes
 * @author     Advanced Algorythms LLC
 */
// Hook for adding admin menus
class WPDBPowerPlugin{
	private $my_plugin_screen_name;
    private static $instance;

    static function GetInstance(){
		if (!isset(self::$instance)){
        	self::$instance = new self();
        }
        return self::$instance;
	}

    public function PluginMenu(){
		$this->my_plugin_screen_name = add_menu_page('WPDB Power Tool', 'WPDB Power Tool', 'manage_options',
			'tabbed_wpdb', array($this, 'RenderTabbedPage'),
			WPDBPT_POWERTOOL_ASSETS . '/images/favicon.png');
	}

	public function RenderTabbedPage(){
        include(WPDBPT_POWERTOOL_ADMIN . '/wpdb_tabbed.php');
    }

	public function InitPlugin(){
		// Load PluginMenu:
		add_action('admin_menu', array($this, 'PluginMenu'));
		// Loads CSS and JS:
		require(WPDBPT_POWERTOOL_CLASSES . '/class.enque_scripts.php');
		// This is for AJAX Calls using a simple include will fail.
		// You must use require_once or require at the plugin root document:

		require(WPDBPT_POWERTOOL_ADMIN . '/query_tool_ajax.php');
		require(WPDBPT_POWERTOOL_ADMIN . '/stored_procedures_ajax.php');
		require(WPDBPT_POWERTOOL_ADMIN . '/wpdb_tables_ajax.php');
		require(WPDBPT_POWERTOOL_ADMIN . '/utilities_ajax.php');
		require(WPDBPT_POWERTOOL_INCLUDES . '/functions.php');
	}
}

$MyPlugin = WPDBPowerPlugin::GetInstance();
$MyPlugin->InitPlugin();
?>
