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

2021-04-23:
This plugin on activation creates a number of stored procedures and seven tables used by the plugin.
When not using the plugin all scripts are unloaded. On Delete of the plugin all database tables used
by this plugin are removed as well as the Stored Procedures that are for the plugin.
The stored procedures use the prefix sp_ to easily identify them as stored procedures.
