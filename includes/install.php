<?php
TestForTables();

function TestForTables(){ // For reactivation after deactivation:
    global $wpdb;

    $sql = "SELECT COUNT(*) AS theCount FROM INFORMATION_SCHEMA.TABLES WHERE LCASE(TABLE_NAME) LIKE('%wpdbpt%');";
    $count = $wpdb->get_var($sql);

    if($count == 0){
        BuildTables();
        buildProcedures();
        LoadObjects();
    }
}

function LoadObjects(){
    global $wpdb;

    $wpPrefix = $wpdb->prefix;
    $sql = "CALL sp_LoadSystemObjects('" . DB_NAME . "','" . $wpPrefix . "');";
    $wpdb->query($sql);
}

function buildTables(){
    global $wpdb;

    $tablesSQL = WPDBPT_POWERTOOL_PATH . 'sql/tables.sql';
    $wpPrefix = $wpdb->prefix;
    $tablesFile = fopen($tablesSQL, "r") or die("Unable to open file!");
    $thisSQL = '';

    while(!feof($tablesFile)){
        $thisSQL = $thisSQL . fgets($tablesFile);
        if((strpos($thisSQL, 'REATE TABLE') > 0) && (strpos($thisSQL, 'DB_CHARSET;') > 0)){
            $sql = str_replace('DB_CHARSET', DB_CHARSET, $thisSQL);
            $sql = str_replace('WORDPRESS_', $wpPrefix, $sql);
            $thisSQL = '';

            $wpdb->query($sql);
        }
    }
    fclose($tablesFile);
}

function buildProcedures(){
    global $wpdb;
    ini_set("auto_detect_line_endings", true);

    $proceduresFile = WPDBPT_POWERTOOL_PATH . 'sql/procedures.sql';
    $wpPrefix = $wpdb->prefix;
    $proceduresFile = fopen($proceduresFile, "r");
    $thisSQL = '';

    while(!feof($proceduresFile)){
        $thisSQL = $thisSQL . fgets($proceduresFile);
        if((strpos($thisSQL, 'REATE PROCEDURE') > 0) && (strpos($thisSQL, '#END PROC') > 0)){
            $sql = str_replace('WORDPRESS_', $wpPrefix, $thisSQL);
            $sql = str_replace(' #END PROC', '', $sql);
            $thisSQL = '';
            $wpdb->query($sql);
        }
    }
    fclose($proceduresFile);
}
?>
