<?php
// WPDB Tables AJAX Calls:
add_action( 'wp_ajax_backupTable', 'backupTable_response' );
add_action( 'wp_ajax_descTable', 'descTable_response' );
add_action( 'wp_ajax_finalDrop', 'finalDrop_response' );
add_action( 'wp_ajax_firstDrop', 'firstDrop_response' );
add_action( 'wp_ajax_restoreDrop', 'restoreDrop_response' );
add_action( 'wp_ajax_restoreTable', 'restoreTable_response' );
add_action( 'wp_ajax_showTable', 'showTable_response' ); // Shows 500 rows of table
add_action( 'wp_ajax_ShowTables', 'ShowTables_response');

function finalDrop_response(){
    global $wpdb;

    $dropTable = $_REQUEST['queryText'];
    $sql = 'CALL sp_FinalDrop("' . $dropTable . '");';
    $response = $wpdb->get_var($sql);

    echo $response;
    wp_die();
}

function restoreDrop_response(){
    global $wpdb;

    $backupTable = $_REQUEST['queryText'];
    $restoreData = str_replace('wpdrop_', '', $backupTable);
    $sql = "CALL sp_RestoreDrop('" . $backupTable . "','" . $restoreData . "');";
    $response  = $wpdb->get_var($sql);

    echo $response;
    wp_die();
}

function firstDrop_response(){
    global $wpdb;

    $table = $_REQUEST['queryText'];
    $tempStr = $wpdb->prefix;
    $newStr = $tempStr . 'wpdrop_';
    $dropTable = str_replace($tempStr, $newStr, $table);
    $createTableSQL = GetCreateTableSQL($table);
    $sql = 'CALL sp_FirstDrop("' . $table . '","' . $dropTable . '","' . $createTableSQL . '");';
    $response = $wpdb->get_var($sql);

    echo $response;
    wp_die();
}

function backupTable_response(){
    global $wpdb;

    $table = $_REQUEST['queryText'];
    $tablePrefix = $wpdb->prefix;
    $tempName = str_replace($tablePrefix, '', $table);
    $backupTable = $tablePrefix . 'wpdbackup_' . $tempName;
    $sql = "CALL sp_CreateBackupTable('" . $table . "','" . $backupTable . "');";
    $response = $wpdb->get_var($sql);

    echo $response;
    wp_die();
}

function restoreTable_response(){
    global $wpdb;

    $backupTable = $_REQUEST['queryText'];
    $restoreTable = str_replace('wpdbackup_', '', $backupTable);
    $sql = "CALL sp_RestoreTable('" . $restoreTable . "','" . $backupTable . "');";
    $response = $wpdb->get_var($sql);

    echo $response;
    wp_die();
}

function ShowTables_response(){
    global $wpdb;

    $choice = 'ALL';

    if(isset($_REQUEST['queryText']) && ($_REQUEST['queryText'] != '')) $choice = $_REQUEST['queryText'];

    $sql = "CALL sp_ShowTables( %s );";
    $tables = $wpdb->get_results( $wpdb->prepare($sql, $choice) );
    $col_names = $wpdb->get_col_info('name');

    echo '<table class="table-table"><tr class="table-tr">';

    foreach($col_names as $name){
        if($choice == 'EMPTY' && $name == 'ROWS'){
            $name = '';
        }
        else{
            echo '<th class="table-th">' . $name . '</th>';
        }
    }
    // Extra columns for DESCRIBE, SHOW, BACKUP DROP:
    switch($choice){
        case 'ALL':
            echo '<td></td>';
            break;
        case 'EMPTY':
            echo '<td></td>';
            break;
    }
    echo '</tr>';

    foreach($tables as $key => $row){
        foreach($row as $field => $value){
            if($field == 'TABLE'){// Check Tables for Application:
                // Set all to false:
                $isWPDBackup = false;
                $isWPDrop = false;
                $isWPDB = false;
                $isWP = false;

                $thisTable = $value;
                if(strpos($thisTable, 'wpdbackup_') > 0) $isWPDBackup = 1;
                if(strpos($thisTable, 'wpdrop_') > 0) $isWPDrop = 1;
                $isWPDB = isWPDBTable($thisTable);
                $isWP = isWordPressTable($thisTable);
                echo '<tr class="table-tr" id="' . $thisTable . '">';
            }

            if($field == 'TABLE'){
                // Describe/Show table name column:
                if(($isWPDBackup != 1) && ($isWPDrop != 1) && ($isWPDB != 1) && ($isWP != 1)){
                    echo '<td class="desc-td"><a class="desc-a" id="' . $value . '">' . $value . '</a></td>';
                }

                if($isWPDBackup == 1){
                    $showTableName = str_replace('wpdbackup_', '', $value);
                    echo '<td class="desc-backup-td"><a class="desc-backup-a" id="' . $value . '">
                    <a class="desc-a  white-font" id="' . $value . '">' . $showTableName . '</a></td>';
                }

                if($isWPDrop == 1){
                    $showTableName = str_replace('wpdrop_','', $value);
                    echo '<td class="desc-wpdrop-td" id="' . $value . '">
                        <a class="desc-a white-font" id="' . $value . '">' . $showTableName . '</a></td>';
                }

                if($isWPDB == 1){
                    $showName = GetFriendlyWPDBName($value);
                    echo '<td class="desc-wpdb-td"><a class="desc-a" id="' . $value . '">
                    <a class="desc-a" id="' . $value . '">' . $showName . '</a></td>';
                }

                if($isWP == 1){
                    echo '<td class="desc-wp-td"><a class="desc-a" id="' . $value . '">
                    <a class="desc-a" id="' . $value . '">' . $value . '</a></td>';
                }

            }
            else{// ROWS Column:
                if($field == 'ROWS'){
                    // The Row count column:
                    $rowCount = GetRowCount($thisTable);
                    if(($choice != 'EMPTY') && ($rowCount > 0)){
                        echo '<td class="show-rows-td"><a class="show-a" id="' . $thisTable . '">' . $rowCount . '</a></td>';
                    }
                    if(($choice != 'EMPTY') && ($rowCount == 0)) echo '<td class="show-rows-td">0</td>';
                }
                else{// SIZE Column:
                    $extra = '';
                    $size = FixTableSize($value);
                    if(strpos($size, 'MB') > 0) $extra = 'bold-font light-blue-bg';
                    echo '<td class="size_td ' . $extra . '">' . $size . '</td>';
                }
            }
        }

        // The restore/backup column
        if($choice != 'EMPTY'){
            if(($isWPDBackup != 1) && ($isWPDrop != 1) && ($isWPDB != 1) && ($rowCount > 0)){
                echo '<td class="backup-td"><a class="backup-a" id="' . $thisTable . '">BACKUP</a></td>';
            }

            if(($isWPDBackup != 1) && ($isWPDrop != 1) && ($isWPDB != 1) && ($rowCount == 0)){
                echo '<td class="show-rows-td">N/A</td>';
            }

            if($isWPDBackup == 1){
                $backupDate = GetBackupDate($thisTable);
                echo '<td title="' . $backupDate . '" class="restore-td" id="restore-td">
                    <a class="restore-a" id="' . $thisTable . '">RESTORE</a></td>';
            }

            if($isWPDrop == 1){
                $backupDate = GetBackupDate($thisTable);
                echo '<td title="' . $backupDate . '" class="restore-drop-td">
                    <a class="restore-drop-a white-font" id="' . $thisTable . '">RESTORE</a></td>';
            }

            if($isWPDB == 1){
                echo '<td class="drop-wpdbtable-td"></td>';
            }
        }

        // The Drop Column:
        if( ($isWPDB != 1) && ($isWPDrop != 1) && ($isWPDBackup != 1) && ($isWP !== 1)){
            echo '<td class="drop-td"><a class="drop-a" id="' . $thisTable . '">DROP</a></td>';
        }

        if($isWP == 1) echo '<td class="drop-wptable-td"></td>';
        if(($isWPDB == 1) || ($isWPDBackup == 1)) echo '<td class="drop-wpdbtable-td"></td>';
        if($isWPDrop == 1){
            $showTableName = str_replace('wpdrop_','', $thisTable);
            echo '<td class="final-drop-td"
                title="This is the last drop for the  table ' . $showTableName . '. Once clicked the table is permanently gone.">
                <a class="final-drop-a" id="' . $thisTable . '">DROP</a></td>';
        echo '</tr>';}
    }

    echo '</table>';
    if(isset($_REQUEST['queryText'])) wp_die();
}

function showTable_response(){
    global $wpdb;

    if(isset($_REQUEST)){
        $thisTable = $_REQUEST['queryText'];
        $records = $wpdb->get_results("CALL sp_ShowTableRows(' $thisTable ');");
        $col_names = $wpdb->get_col_info('name');
        echo '<h3 class="desc_table">' . $thisTable . ' at <b class="blue-font">' . GetTimeNow() . '</b></h3>';
        echo '<table class="show-table">';
        echo '<tr class="show-tr">';
        foreach($col_names as $name){
            echo '<th class="show-th">' . $name . '</th>';
        }
        echo '</tr>';
        foreach($records as $key => $row){
            echo '<tr class="show-tr">';
            foreach($row as $field => $value){
                $clickField = strtolower($field);
                switch($clickField){
                    case 'sp_definition':
                        echo '<td class="show_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                        break;
                    case 'option_value':
                        echo '<td class="show_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                        break;
                    case 'post_content':
                        echo '<td class="show_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                        break;
                    case 'meta_value':
                        echo '<td class="show_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                        break;
                    case 'data':
                        echo '<td class="show_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                        break;
                    default:
                        echo '<td class="show-td">' . $value . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    wp_die();
}

function descTable_response(){
    global $wpdb;

    if(isset($_REQUEST)){
        $thisTable = $_REQUEST['queryText'];
        $records = $wpdb->get_results("DESC $thisTable;");
        $col_names = $wpdb->get_col_info('name');
        echo '<h3 class="desc_table">' . $thisTable . ' DESCRIPTION at <b class="blue-font">' . GetTimeNow() . '</b></h3>';
        echo '<table class="desc-table">';
        echo '<tr class="desc-tr">';
        foreach($col_names as $name){
            echo '<th class="desc-th">' . $name . '</th>';
        }
        echo '</tr>';
        foreach($records as $key => $row){
            echo '<tr class="desc-tr">';
            foreach($row as $field => $value){
                echo '<td class="desc-td">' . $value . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    wp_die();
}
?>
