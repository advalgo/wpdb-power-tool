<?php   /***************** Tabbed Kickfire Stats ****************/
    $tab = '';

	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
        exit;
	}

	//Get the active tab from the $_GET param
	$default_tab = null;
	if(isset($_REQUEST['tab'])){
        $tab = $_REQUEST['tab'];
    }
	else{
    	$tab = 'wpdbTables';
    }

?>	<div class="wrap">
	<!-- Our admin page content should all be inside .wrap -->
	<!-- Print the page title -->
	<h1>WPDB Power Tool!</h1>
	<!-- Here are our tabs -->
	<nav class="nav-tab-wrapper">
        <a href="?page=tabbed_wpdb&tab=wpdbTables" class="nav-tab <?php if($tab==='wpdbTables') echo 'nav-tab-active'; ?>">WPDB Tables</a>
        <a href="?page=tabbed_wpdb&tab=wpdbQueryTool" class="nav-tab <?php if($tab==='wpdbQueryTool') echo 'nav-tab-active'; ?>">Query Tool</a>
        <a href="?page=tabbed_wpdb&tab=wpdbStoredProcs" class="nav-tab <?php if($tab==='wpdbStoredProcs') echo 'nav-tab-active'; ?>">Stored Procedures</a>
		<a href="?page=tabbed_wpdb&tab=wpdbInstructions" class="nav-tab <?php if($tab==='wpdbSupport') echo 'nav-tab-active'; ?>">Instructions</a>
        <a href="?page=tabbed_wpdb&tab=wpdbAbout" class="nav-tab <?php if($tab==='wpdbAbout') echo 'nav-tab-active'; ?>">About</a>
	</nav>

	<div class="tab-content">
<?php
    switch($tab){
		case 'wpdbTables':
?>          <h1 class="wpdb-table"><img src="/wp-content/plugins/wpdb-power-tool/assets/images/Octopus.webp"
                height="40" width="40">Explore the present WPDB Tables</h1>
<?php		include('wpdb_tables.php');
			break;
		case 'wpdbQueryTool':
            include(WPDBPT_POWERTOOL_INCLUDES . '/forms/query_tool_form.php');
			break;
		case 'wpdbStoredProcs':
		    include(WPDBPT_POWERTOOL_ADMIN . '/stored_procedures.php');
			break;
        case 'wpdbInstructions':
            include(WPDBPT_POWERTOOL_ADMIN . '/instructions.php');
            break;
		case 'wpdbAbout':
		    include(WPDBPT_POWERTOOL_ADMIN . '/about.php');
		    break;
		default:
			echo 'Do you know why you are here?<br>';
    }
?>	</div>
<?php   /********** End Tabbed Kickfire Stats ***************/
?>
