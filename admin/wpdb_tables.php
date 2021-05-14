<input type="radio" name="tables_to_show" class="tables_to_show" id="ALL" value="ALL" checked>
<label class="tables_label" for="ALL">Display All Tables</label>&nbsp;&nbsp;
<input type="radio" name="tables_to_show" class="tables_to_show" id="ROWS" value="ROWS">
<label class="tables_label" for="ROWS">Only Tables with rows</label>&nbsp;&nbsp;
<input type="radio" name="tables_to_show" class="tables_to_show" id="EMPTY" value="EMPTY">
<label for="EMPTY" class="tables_label">Only tables without rows</label><br>
<div class="plain_text">Click on Table name for Table Description. Click on Rows to see Table Rows.</div>
<div class="table-container">
    <div class="table-leftcolumn">
<?php   ShowTables_response();
?>  </div>
    <div class="table-righttop"></div>
    <div class="table-rightbottom"> </div>
</div>
