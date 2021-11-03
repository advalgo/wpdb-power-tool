<?php
    $protectedProcs = array(
        'sp_BlankDelete',
        'sp_BlankInsert',
        'sp_BlankSelect',
        'sp_BlankUpdate',
        'sp_CreateBackupTable',
        'sp_DisplayProcedures',
        'sp_DisplayProcLog',
        'sp_DisplaySQLHistory',
        'sp_FinalDrop',
        'sp_FirstDrop',
        'sp_GetBackupDate',
        'sp_GetCreateTableSQL',
        'sp_GetDropDate',
        'sp_GetProcedureDefinition',
        'sp_GetProcedureParameters',
        'sp_GetShowStatus',
        'sp_GetTableRowCount',
        'sp_LoadSystemObjects',
        'sp_LogActivity',
        'sp_LogSQL',
        'sp_LogStoredProcActivity',
        'sp_RemoveSQL',
        'sp_RemoveTablesOnDeactivate',
        'sp_RestoreDrop',
        'sp_RestoreTable',
        'sp_ShowTableRows',
        'sp_ShowTables'
    );
    /*  Code for sp_DisplayProcedures:
    AND (SPECIFIC_NAME != 'sp_BlankDelete' AND
        SPECIFIC_NAME != 'sp_BlankInsert' AND
        SPECIFIC_NAME != 'sp_BlankSelect' AND
        SPECIFIC_NAME != 'sp_BlankUpdate' AND
        SPECIFIC_NAME != 'sp_CreateBackupTable' AND
        SPECIFIC_NAME != 'sp_DisplayProcedures' AND
        SPECIFIC_NAME != 'sp_DisplayProcLog' AND
        SPECIFIC_NAME != 'sp_DisplaySQLHistory' AND
        SPECIFIC_NAME != 'sp_FinalDrop' AND
        SPECIFIC_NAME != 'sp_FirstDrop' AND
        SPECIFIC_NAME != 'sp_GetBackupDate' AND
        SPECIFIC_NAME != 'sp_GetCreateTableSQL' AND
        SPECIFIC_NAME != 'sp_GetDropDate' AND
        SPECIFIC_NAME != 'sp_GetProcedureDefinition' AND
        SPECIFIC_NAME != 'sp_GetProcedureParameters' AND
        SPECIFIC_NAME != 'sp_GetShowStatus' AND
        SPECIFIC_NAME != 'sp_GetTableRowCount' AND
        SPECIFIC_NAME != 'sp_LoadSystemObjects' AND
        SPECIFIC_NAME != 'sp_LogActivity' AND
        SPECIFIC_NAME != 'sp_LogSQL' AND
        SPECIFIC_NAME != 'sp_LogStoredProcActivity' AND
        SPECIFIC_NAME != 'sp_RemoveSQL' AND
        SPECIFIC_NAME != 'sp_RemoveTablesOnDeactivate' AND
        SPECIFIC_NAME != 'sp_RestoreDrop' AND
        SPECIFIC_NAME != 'sp_RestoreTable' AND
        SPECIFIC_NAME != 'sp_ShowTableRows' AND
        SPECIFIC_NAME != 'sp_ShowTables'
    )

    Code for sp_LoadSystemObjects:
	SET wpdbProcedureObjects = CONCAT(
        'sp_BlankDelete',
        'sp_BlankInsert',
        'sp_BlankSelect',
        'sp_BlankUpdate',
        'sp_CreateBackupTable',
        'sp_DisplayProcedures',
        'sp_DisplayProcLog',
        'sp_DisplaySQLHistory',
        'sp_FinalDrop',
        'sp_FirstDrop',
        'sp_GetBackupDate',
        'sp_GetCreateTableSQL',
        'sp_GetDropDate',
        'sp_GetProcedureDefinition',
        'sp_GetProcedureParameters',
        'sp_GetShowStatus',
        'sp_GetTableRowCount',
        'sp_LoadSystemObjects',
        'sp_LogActivity',
        'sp_LogSQL',
        'sp_LogStoredProcActivity',
        'sp_RemoveSQL',
        'sp_RemoveTablesOnDeactivate',
        'sp_RestoreDrop',
        'sp_RestoreTable',
        'sp_ShowTableRows',
        'sp_ShowTables'
    );
    */
?>
