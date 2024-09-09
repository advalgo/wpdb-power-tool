<?php
class Add_WPDBPower_CSS_JS {
	function register(){
		//for backend
		add_action( 'admin_enqueue_scripts', array($this,'backendEnqueue'));
		//for frontend
		//add_action( 'wp_enqueue_scripts', array($this,'frontendEnqueue'));
	}

	function backendEnqueue(){// Only load for this plugin:
		if(isset($_REQUEST['page']) && ($_REQUEST['page'] == 'tabbed_wpdb')){
			// WPDB Power Tool CSS and JS files:
			wp_enqueue_style( 'WPDBTables', WPDBPT_POWERTOOL_ASSETS . '/css/wpdb_tables.css');
			wp_enqueue_style( 'Editor', WPDBPT_POWERTOOL_ASSETS . '/css/editor.css');
			wp_enqueue_style( 'Instructions', WPDBPT_POWERTOOL_ASSETS . '/css/instructions.css');
			wp_enqueue_style( 'QueryTool', WPDBPT_POWERTOOL_ASSETS . '/css/query_tool.css');
			wp_enqueue_style( 'StoredProcs', WPDBPT_POWERTOOL_ASSETS . '/css/stored_procedures.css');
			wp_enqueue_script( 'WPDB', WPDBPT_POWERTOOL_ASSETS . '/js/wpdb.js');
			// For Editor:
			if((isset($_REQUEST['tab'])) &&
				(($_REQUEST['tab'] == 'wpdbQueryTool') || ($_REQUEST['tab'] == 'wpdbStoredProcs'))){
				wp_enqueue_script( 'WPDB-Editor', WPDBPT_POWERTOOL_ASSETS . '/js/editor.js');
			}
			else{
				wp_dequeue_script('WPDB-Editor');
			}
			$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'sql'));
		   	wp_localize_script('jquery', 'cm_settings', $cm_settings);
		    wp_enqueue_script('wp-theme-plugin-editor', false, array('jquery'));
			wp_enqueue_style('wp-codemirror');
			wp_enqueue_script('jquery-ui-draggable', false, array('jquery'));
		}
		else{
			wp_dequeue_style('WPDBTables');
			wp_dequeue_style('Editor');
			wp_dequeue_style('Instructions');
			wp_dequeue_style('QueryTool');
			wp_dequeue_style('StoredProcs');
			wp_dequeue_script('WPDB');
			wp_dequeue_script('jquery');
			wp_dequeue_script('jquery-ui-draggable');
		}
	}

	/*function frontendEnqueue(){// Front end of site:
		wp_enqueue_style( 'WPDB', WPDBPT_POWERTOOL_FE_ASSETS. '/css/wpdb.css');
		wp_enqueue_script( 'WPDB', WPDBPT_POWERTOOL_FE_ASSETS . '/js/wpdb.js');
	}*/
}

if(class_exists('Add_WPDBPower_CSS_JS')){
	$Add_WPDBPower_CSS_JS = new Add_WPDBPower_CSS_JS();
	$Add_WPDBPower_CSS_JS->register();
}
?>
