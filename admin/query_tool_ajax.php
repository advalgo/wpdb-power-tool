<?php
    // Query Tool Tab AJAX Calls:
    add_action( 'wp_ajax_runSQL', 'runSQL_response' );
    add_action( 'wp_ajax_displaySQLHistory', 'displaySQLHistory_response' );
    add_action( 'wp_ajax_removeSQL', 'removeSQL_response');
    add_action( 'wp_ajax_DisplayQueryTables', 'DisplayQueryTables_response' );
    add_action( 'wp_ajax_DisplaySelectTable', 'DisplaySelectTable_response' );

    function DisplaySelectTable_response(){
        global $wpdb;

        $table = $_REQUEST['queryText'];
        $sql = 'DESC ' . $table . ';';
        $tableDesc = $wpdb->get_results($sql);

        echo '<table class="table_fields">
            <tr class="table_name_tr">
                <th class="table_name_th" colspan="2">' . $table . '</th>
            </tr>
            <tr class="fields_tr">
                <th class="field_th">Field</th>
                <th class="field_th">Type</th>
            </tr>';

        foreach ($tableDesc as $key => $row) {
            echo '<tr class="field_tr">';
            echo '<td class="field_name_td" scope="SELECT" id="' . $row->Field . '">' . $row->Field . '</td>';
            echo '<td class="field_type_td" id="' . $row->Type . '">' . $row->Type . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        wp_die();
    }

    function DisplayQueryTables_response(){
        global $wpdb;

        $sql = 'CALL sp_ShowTables("All");';

        $tables = $wpdb->get_results($sql);
        echo '<table class="select_tables">
            <tr class="select_tr">
                <th class="select_table_th">TABLE</th>
                <th class="select_rows_th">ROWS</th>
            </tr>';
        foreach ($tables as $key => $row) {
            $tableRows = GetRowCount($row->TABLE);

            if($tableRows > 0){
                echo '<tr class="select_tr">';
                echo '<td class="select_table_td" id="' . $row->TABLE . '">' . $row->TABLE . '</td>';
                echo '<td class="select_rows_td" id="'. $tableRows . '">'. $tableRows . '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
        wp_die();
    }

    function removeSQL_response(){
        global $wpdb;
        $id = 0;

        if(isset($_REQUEST['queryText'])){
            $id = $_REQUEST['queryText'];
            $sql = 'CALL sp_RemoveSQL('. $id . ');';

            $wpdb->get_results($sql);
            echo 'Removed ID ' . $id . ' at <b class="blue-font">' . GetTimeNow() . '</b>';
        }

        wp_die();
    }

    function displaySQLHistory_response(){
        global $wpdb;

        $sql = 'CALL sp_DisplaySQLHistory();';
        $records = $wpdb->get_results($sql);

        echo '<table class="history-table">';
        foreach($records as $key => $row){
            echo '<tr class="history-tr">';
            foreach($row as $field => $value){
                switch($field){
                    case 'id':
                        echo '<td title="Click to delete." class="remove-sql-td" id="' . $value . '">' . $value. '</td>';
                        break;
                    case 'sql_time':
                        echo '<td class="sql-time-td">' . $value . '</td>';
                        break;
                    case 'the_sql':
                        echo '<td title="Click to run again." class="history-td">' . $value . '</td>';
                        break;
                    default:
                        echo 'Why am I here?';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
        // If you put wp_die() in here form does not complete dispay for Query Tool Page.
        if(isset($_REQUEST['queryText'])){
            // Do this for load of SQL History form on initial start.
            wp_die();
        }
    }

    function runSQL_response(){
        $thisQuery = '';

        // Make sure there is a query:
        if((isset($_REQUEST['queryText'])) && (trim(strlen($_REQUEST['queryText'])) > 0)){
            $thisQuery = $_REQUEST['queryText'];
            $thisQuery = stripcslashes($thisQuery);
            $thisQuery = str_replace('&gt;', '>', $thisQuery);
            $thisQuery = str_replace('&lt;', '<', $thisQuery);
        }
        else{
            echo 'The query is empty.';
            exit;
        }

        // Crazy way to have to do this but in this instance 0 is the same as blank.
        $dropTest = strlen(strpos(strtoupper($thisQuery), strtoupper('DROP ')));
        $alterTest = strlen(strpos(strtoupper($thisQuery), strtoupper('ALTER ')));

        if($dropTest > 0 || $alterTest > 0) {
            $isWP = isWordPressTable($thisQuery);
            $isWPDB = isWPDBTable($thisQuery);

            if(($isWP == 1) || ($isWPDB == 1)){
                echo 'You cannot DROP or ALTER WordPress or WPDB Power Tool Tables with the Query Tool.';
                wp_die();
            }
        }

        // Check for CREATE TABLE SQL:
        $createTableTest = strpos(strtoupper($thisQuery), 'ATE TABLE');
        $showCreateTest = strpos(strtoupper($thisQuery), 'HOW CREATE TABLE');
        $showCreateTableExists = strlen($showCreateTest);
        $isUpdateSQL = CheckForUpdateSQL($thisQuery);
        //$isInsertSQL = CheckForInsertSQL($thisQuery);

        // Watch for CREATE TABLE and SHOW CREATE SQL and process differently:
        if(($createTableTest > 0) && ($showCreateTableExists === 0)) CreateTable($thisQuery);

        global $wpdb;
        $wpdb->show_errors = true;

        $records = $wpdb->get_results($thisQuery);
        $row_count = $wpdb->num_rows;
        $col_names = $wpdb->get_col_info('name');
        $col_count = count($col_names);

        if($row_count > 0) echo 'Total Rows: ' . $row_count . ' at <b class="blue-font">' . GetTimeNow() . '</b>';
        if($isUpdateSQL > 0){
            echo $isUpdateSQL . ' TABLE ROWS UPDATED AT <b class="blue-font">' . GetTimeNow() . '</b>';
        }

        echo '<table class="results-table">';

        if($col_count > 0){
            echo '<tr class="results-tr">';
            $fieldCount = 0;
            foreach($col_names as $name){
                $fieldCount = $fieldCount + 1;
                echo '<th class="results-th">' . $name . '</td>';
            }
            echo '</tr>';
        }

        if(($row_count == 0) && ($isUpdateSQL < 1)){
            if($isUpdateSQL < 1)
            echo '<tr class="no-rows-tr"><td class="no-rows-td" colspan="' . $fieldCount . '">NO ROWS PRESENT OR UPDATED.</td></tr></table>';
        }

        foreach($records as $key => $row){
            echo '<tr class="results-tr">';
            foreach($row as $field => $value){
                if(strpos($thisQuery, 'SHOW CREATE') == 0){
                    //echo '<td class="query-td"><pre class="query-pre">' . $value . '</pre></td>';
                    if(($field == 'Create Table') || ($field == 'Create Procedure')){
                        echo '<td height="500px" class="query-td" width="200px">
                            <textarea class="query-textarea" cols="100" rows="30">' . $value . '</textarea></td>';
                    }
                    else{
                        switch(strtoupper($field)){
                            case 'DATA':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'OUTPUT':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'SP_DEFINITION':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'CREATE TABLE':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'POST_CONTENT':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'META_VALUE':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'ROUTINE_DEFINITION':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'EXTERNAL_NAME':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'OPTION_VALUE':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            case 'OPTION_NAME':
                                echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                                break;
                            default:
                                echo '<td class="results-td">' . $value . '</td>';
                                break;
                        }
                    }
                }
                else{
                    echo '<td class="results-td">' . $value . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
        $isInsert = strpos(strtoupper($thisQuery), 'NSERT INTO');

        if($isInsert > 0){
            if($wpdb->last_error == '') {
                echo '<b>RECORD(S) INSERTED at <b> ' . GetTimeNow();
                LogSQL(addslashes($thisQuery));
            }
            else{
                $lastError = $wpdb->last_error;
                echo '<b class="red-font">' . $lastError . '</b>';
            }
        }

        if($wpdb->last_error == '' && $isInsert == 0) {
            LogSQL(addslashes($thisQuery));
        }
        else{
            $lastError = $wpdb->last_error;
            echo '<b class="red-font">' . $lastError . '</b>';
        }

        $wpdb->show_errors = false;
        wp_die();
    }
?>
