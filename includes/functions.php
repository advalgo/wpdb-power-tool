<?php
function DebugP($desc, $text){
    if(WPDBPT_DEBUG == true){
        echo 'DESC:' . $desc . ' Out: <pre class="text_data_pre">' . $text . '</pre>';
    }
}

function GetParamType($theParam){
    $theDataType = '';
    include('data_types.php');

    for($i = 0; $i < count($dataTypes); $i++){
        if(strpos($theParam, $dataTypes[$i]) > 0){
            $theDataType = $dataTypes[$i];
        }
        if(strlen($theDataType) > 0) break;
    }
    return $theDataType;
}

function GetParameters($procedure){
    global $wpdb;

    $sql = 'CALL sp_GetProcedureParameters("' . $procedure . '", "' . DB_NAME . '");';
    $procTop = 'CREATE PROCEDURE ' . $procedure;
    $parameters = $wpdb->get_results($sql);
    $paramsOut = '';
    $row_count = $wpdb->num_rows;
    $thisRowCount = 0;

    foreach($parameters as $key => $row){
        $thisRowCount = $thisRowCount + 1;
        foreach ($row as $field => $value) {
            switch($field){
                case 'PARAMETER_MODE':
                    $paramsOut = $paramsOut . $value . ' ';
                    break;
                case 'PARAMETER_NAME':
                    $paramsOut = $paramsOut . $value . ' ';
                    break;
                case 'DTD_IDENTIFIER':
                    if($thisRowCount < $row_count){
                        if(is_null($value)){
                            $paramsOut = $paramsOut . ', ';
                        }
                        else{
                            $paramsOut = $paramsOut . strtoupper($value) . ', ';
                        }
                    }
                    else{
                        if(is_null($value)){
                            $paramsOut = $paramsOut;
                        }
                        else{
                            $paramsOut = $paramsOut . strtoupper($value);
                        }
                    }
                    break;
            }
        }
    }

    $paramsOut = $procTop . '(' . $paramsOut . ')';
    return $paramsOut;
}

function GetDefinition($procedure){
    global $wpdb;

    $sql = 'CALL sp_GetProcedureDefinition("' . $procedure . '", "' . DB_NAME . '");';
    $results = $wpdb->get_var($sql);

    return $results;
}

function FixTableSize($theBytes){
    $readable = 0;

    if($theBytes >= 1048576){
        $readable = intdiv($theBytes, 1048576);
        $readable = $readable . ' MB';
    }
    if(($theBytes > 1024) && ($theBytes < 1048576)){
        $readable = intdiv($theBytes, 1024);
        $readable = $readable . ' KB';
    }
    if($theBytes < 1024){
        $readable = $readable . ' B';
    }
    return $readable;
}

function CreateTable($theSQL){
    global $wpdb;

    $wpdb->show_errors = true;

    $wpPrefix = $wpdb->prefix;
    $sqlParts = explode(chr(96), $theSQL);
    $tableName = $sqlParts[1];
    $newTableName = $wpdb->prefix . $tableName;

    $isWPName = strpos($tableName, $wpPrefix);
    $strLen = strlen($isWPName);

    if($strLen < 1){
        for($i = 0; $i < count($sqlParts); $i++){
            if($i == 0){
                $newSQL = 'CREATE TABLE ' . chr(96) . $newTableName;
            }

            if($i >= 2){
                $newSQL = $newSQL . chr(96) . $sqlParts[$i];
            }
        }
    }
    else{
        $newSQL = $theSQL;
    }
    echo 'TABLE CREATE SQL:<pre>' . $newSQL . '</pre>';
    echo 'Entered Table Name: ' . $tableName . ' WordPress Table Name: ' . $newTableName . '<br />';

    $wpdb->query($newSQL);
    if($wpdb->last_error !== ''){
        $error = $wpdb->last_error;
        $str   = $wpdb->last_result;

        print "<div id='error'><p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
        $error</p></div>";
    }
    else{
        echo 'Table <b class="blue-font">' . $newTableName . '</b> created.';
    }

    LogSQL($newSQL);
    $wpdb->show_errors = false;
    wp_die();
}

function LogSQL($theQuery){
    global $wpdb;
    $wpdb->show_errors = true;

    $currentTime = GetTimeNow();
    $sql = "CALL sp_LogSQL('" . $currentTime . "','" . $theQuery . "');";
    $wpdb->get_results($sql);

    $lastError = $wpdb->last_error;
    if($lastError != ''){
        echo '<b class="red-font">' . $lastError . '</b> ';
    }
}

function GetTimeNow(){
    global $wpdb;
    $sql = 'SELECT NOW();';
    $currentTime = $wpdb->get_var($sql);
    return $currentTime;
}

function GetBackupDate($thisTable){
    global $wpdb;
    $backupDate = '';

    if(strpos($thisTable, 'wpdbackup_') > 0){
        $restoreTable = str_replace('wpdbackup_', '', $thisTable);
        $sql = 'CALL sp_GetBackupDate("' . $restoreTable . '");';
    }

    if(strpos($thisTable, 'wpdrop_') > 0){
        $restoreTable = str_replace('wpdrop_', '', $thisTable);
        $sql = 'CALL sp_GetDropDate("' . $restoreTable . '");';
    }

    $backupDate = $wpdb->get_var($sql);
    return "From: " . $backupDate;
}

function GetFriendlyWPDBName($thisTable){
    $returnName = '';
    if(strpos(strtolower($thisTable), 'wpdbpt_backup_log') > 0) $returnName = 'WPDB Backup Log';
    if(strpos(strtolower($thisTable), 'wpdbpt_activity_log') > 0) $returnName = 'WPDB Activity Log';
    if(strpos(strtolower($thisTable), 'wpdbpt_objects') > 0) $returnName = 'WPDB Objects';
    if(strpos(strtolower($thisTable), 'wpdbpt_sql_log') > 0) $returnName = 'WPDB SQL Log';
    if(strpos(strtolower($thisTable), 'wpdbpt_restore_log') > 0) $returnName = 'WPDB Restore Log';
    if(strpos(strtolower($thisTable), 'wpdbpt_drop_table_log') > 0) $returnName = 'WPDB Drop Table Log';
    if(strpos(strtolower($thisTable), 'wpdbpt_sp_activity_log') > 0) $returnName = 'WPDB Stored Proc Activity Log';
    return $returnName;
}

function isCurrentProc($theSQL){
    global $wpdb;

    $thisProcedure = 'noProc';
    $procedureExists = 0;
    $sql = 'CALL sp_displayProcedures("' . DB_NAME . '","Show");';
    $procedures = $wpdb->get_results($sql);

    foreach($procedures as $key => $row){
        foreach($row as $field => $value){
            switch($field){
                case 'SPECIFIC_NAME':
                    $testStr = strpos($theSQL, $value);
                    $testLen = strlen($testStr);

                    if($testLen > 0){
                        $thisProcedure = $value;
                        $procedureExists = 1;
                        break;
                    }
                default:
                    //DebugP('Procedure:', $value);
            }

            if($procedureExists == 1) break;
        }
        if($procedureExists == 1) break;
    }
    return $thisProcedure;
}

function isProtectedProc($theSQL){
    include('wpdb_procedures.php');
    $isProtected = 0;

    for($i = 0; $i < count($protectedProcs); $i++){
        if(strlen(strpos($theSQL, $protectedProcs[$i])) > 0){
            $isProtected = 1;
            break;
        }
    }
    return $isProtected;
}

function isProtectedList($theProc){
    include('wpdb_procedures.php');
    $isProtected = 0;

    for($i = 0; $i < count($protectedProcs); $i++){
        if($theProc == $protectedProcs[$i]){
            $isProtected = 1;
            break;
        }
    }
    return $isProtected;
}

function isWPDBTable($theTable){
    $isWPDBTable = '';
    include('wpdb_tables.php');

    for($i = 0;$i < count($wpdbTables);$i++){
        if(strpos($theTable, $wpdbTables[$i]) > 0){
            $isWPDBTable = 1;
            break;
        }
    }
    return $isWPDBTable;
}

function isWordPressTable($theTable){
    global $wpdb;

    $isWPTable = '';
    $prefix = $wpdb->prefix;
    include('wp_tables.php');

    for($i = 0; $i < count($wpTables); $i++){
        $wpTable = $prefix . $wpTables[$i];

        $test = strlen(strpos(strtoupper($theTable), strtoupper($wpTable)));
        if($test > 0){
            $isWPTable = 1;
            break;
        }
    }
    return $isWPTable;
}

function CheckForUpdateSQL($theSQL){
    global $wpdb;

    $sql = '';
    $rows = 0;
    $tableSQL = '';
    $whereSQL = '';
    $tempSQL = $theSQL; // Preserve the original

    if((strpos($tempSQL, 'PDATE ') > 0) || (strpos($tempSQL, 'pdate ') > 0)){
        // Update SQL Lets get count of rows to be updated:
        $tableSQL = explode(" set ", $tempSQL);
        if(count($tableSQL) === 1) $tableSQL = explode(' SET ', $tempSQL);
        // get upper or lower case UPDATE:
        $table = str_replace('update ', '', $tableSQL[0]);
        $table = str_replace('UPDATE ', '', $table);

        if((strpos($tempSQL, 'WHERE ') > 0) || (strpos($tempSQL, 'where ') > 0)){
            $lines = preg_split('/\n|\r\n?/', $tempSQL);
            $addLines = 0;

            for($i = 0; $i < count($lines); $i++){
                $hasWhere = strpos($lines[$i], 'where');
                $hasWHERE = strpos($lines[$i], 'WHERE');

                if((strlen($hasWhere == 1)) || (strlen($hasWHERE) == 1)){
                    $addLines = 1;
                }
                if($addLines == 1) $whereSQL = $whereSQL . $lines[$i] . ' ';
            }
        }
        $sql = 'SELECT COUNT(*) AS output FROM ' . $table . ' ' . $whereSQL;
        $rows = $wpdb->get_var($sql);
        if($rows == '') $rows = 0;
    }

    return $rows;
}

function CheckForInsertSQL($theSQL){

}

function GetRowCount($fromTable){
    global $wpdb;
    $sql = 'CALL sp_GetTableRowCount("' . $fromTable . '");';
    $results = $wpdb->get_row( $sql , OBJECT );
    return $results->theRows;
}

function GetCreateTableSQL($theTable){
    global $wpdb;
    $createSQL = '';

    $sql = "CALL sp_GetCreateTableSQL('" . $theTable . "');";
    $results = $wpdb->get_results($sql);

    foreach($results as $key => $row){
        foreach($row as $field => $value){
            if($field == 'Create Table'){
                $createSQL = $value;
            }
        }
    }
    return $createSQL;
}
?>
