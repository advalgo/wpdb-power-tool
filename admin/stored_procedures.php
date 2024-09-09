<h1 class="query-tool"><img class="query-tool"
    src="/wp-content/plugins/wpdb-power-tool/assets/images/Sloth.webp"
    style="float:left;" height="40"
    width="40">Stored Procedures Toolbox</h1>
<a class="sp_blank" id="sp_BlankSelect">Blank SELECT</a>
<a class="sp_blank" id="sp_BlankUpdate">Blank UPDATE</a>
<a class="sp_blank" id="sp_BlankInsert">Blank INSERT</a>
<a class="sp_blank" id="sp_BlankDelete">Blank DELETE</a>
<?php
    global $wpdb;

    $sql = 'CALL sp_GetShowStatus()';
    $status = $wpdb->get_var($sql);
    
    if($status === 'No'){
        $displayStatus = 'Hide';
?>      <a class="builtin_display" id="Show">Show Built-in</a>
<?php }
    else{
        $displayStatus = 'Show';
?>      <a class="builtin_display" id="Hide">Hide Built-in</a>
<?php }
?>
<div class="display_status" id="<?php echo $displayStatus ?>" style="display:none;"></div>
<a class="sp_log" id="sp_log">View Log</a>
<a class="sp_show_procedures">Show Procedures</a>
<a class="sp_show_output">Show Output</a>
<div class="sp_wrapper">
    <div class="alert-div"></div>
<?php   include(WPDBPT_POWERTOOL_INCLUDES . '/forms/editor.php');
?>  <div class="results-div">
        <pre class="editor_results"></pre>
    </div>

    <div class="stored_procedures">
<?php   displayProcedures_response();
?>  </div>
</div>
