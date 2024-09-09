<?php
    DropPluginTablesOnDeactivate();
    DropPluginProceduresOnDeactivate();

    function DropPluginTablesOnDeactivate(){
        global $wpdb;
        $wpPrefix = $wpdb->prefix;

        $sql = 'CALL sp_RemoveTablesOnDeactivate("' . DB_NAME . '","' . $wpPrefix . '");';
        $wpdb->query($sql);
    }

    function DropPluginProceduresOnDeactivate(){
        global $wpdb;
        include('wpdb_procedures.php');

        for($i = 0;$i < count($protectedProcs); $i++){
            $sql = 'DROP PROCEDURE IF EXISTS `' . $protectedProcs[$i] . '`;';

            $wpdb->query($sql);
        }
    }
?>
