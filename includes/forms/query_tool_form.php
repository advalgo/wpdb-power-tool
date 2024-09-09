<h1 class="query-tool"><img class="query-tool"
    src="/wp-content/plugins/wpdb-power-tool/assets/images/Cuz.webp"
    style="float:left;" height="40"
    width="40">Type query and press CTRL+ENTER (just like MySQL Workbench)</h1>
<a class="query_options" id="select_builder">SELECT Table</a>
<a class="query_options" id="show_table">Show Table</a>
<a class="query_options" id="show_output">Show Output</a>
<div class="query-tool-tab">
    <div class="results-div"></div>
<?php   include(WPDBPT_POWERTOOL_INCLUDES . '/forms/editor.php');
        include(WPDBPT_POWERTOOL_INCLUDES . '/forms/history.php');
?>  <div class="alert-div"></div>
    <div class="select_table_div">
        <span class="select_table_span">Click on Table to SELECT from. Only tables with rows are  displayed.
        </span>
    </div>
    <div class="builder_tables"></div>
    <div class="builder_select">
        <div class="query_div" id="select_id">SELECT</div>
        <div class="query_div" id="where_id">WHERE</div>
        <div class="query_div" id="order_id">ORDER BY</div>
        <div class="query_div" id="limit_id">LIMIT</div>
        <div class="reset_div" id="reset_select">Reset SELECT</div>
        <div class="reset_div" id="reset_where">Reset WHERE</div>
        <div class="reset_div" id="reset_order">Reset ORDER BY</div>
        <div class="note">Click on Fields you want to select in the order you want them displayed.</div>
    </div>
    <div class="builder_table"></div>
</div>
<!-- Query Builder Invisible Containers -->
<div class="select_fields"></div>
<div class="from_table"></div>
<div class="where_fields"></div>
<div class="order_by_fields"></div>
<div class="limit_fields"></div>
