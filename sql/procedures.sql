CREATE PROCEDURE `sp_BlankDelete`(IN exampleStr VARCHAR(1000), IN exampleInt INT(10), IN exampleDec DECIMAL(6,2))
BEGIN
	# Description:
	#	Template for building a DELETE stored procedure
	# Application:
	#	admin/stored_procedures_ajax.php
	# Function(s):
	#	function displayProcedures_response
	#	function getBlank_response
	-- Also works as a comment line
	#
	# !!!!!!!!!! VERY IMPORTANT !!!!!!!!!!!!
	# When doing a DELETE statement take care to have a WHERE clause as you will
	# delete everything in the table without one.

	DELETE FROM table_name WHERE field_1 = exampleStr OR field_2 = exampleInt OR field_3 < exampleDec;
END	#END PROC

CREATE PROCEDURE `sp_BlankInsert`(IN exampleStr VARCHAR(1000), IN exampleInt INT(10), IN exampleDec DECIMAL(6,2))
BEGIN
	# Description:
	#	Template for building a INSERT stored procedure
	# Application:
	#	admin/stored_procedures_ajax.php
	# Function(s):
	#	function displayProcedures_response
	#	function getBlank_response
	-- Also works as a comment line

	INSERT INTO table_name(field_1, field_2, field_3)
	VALUES(exampleStr, exampleInt, exampleDec);
END	#END PROC

CREATE PROCEDURE `sp_BlankSelect`(IN exampleStr VARCHAR(1000), IN exampleInt INT(10))
BEGIN
	# Description:
	#	Template for building a SELECT stored procedure
	# Application:
	#	admin/stored_procedures_ajax.php
	# Function(s):
	#	function displayProcedures_response
	#	function getBlank_response
	-- Also works as a comment line

	SELECT * FROM table_name WHERE str_field = exampleStr OR int_field = exampleInt;
END	#END PROC

CREATE PROCEDURE `sp_BlankUpdate`(IN exampleStr VARCHAR(1000), IN exampleInt INT(10), IN exampleDec DECIMAL(6,2))
BEGIN
	# Description:
	#	Template for building a UPDATE stored procedure
	# Application:
	#	admin/stored_procedures_ajax.php
	# Function(s):
	#	function displayProcedures_response
	#	function getBlank_response
	-- Also works as a comment line

	UPDATE table_name SET field_1 = exampleStr, field_2 = exampleInt, field_3 = exampleDec
	WHERE field_1 = exampleInt;
END	#END PROC

CREATE PROCEDURE `sp_CreateBackupTable`(IN myTable VARCHAR(64), IN backupTable VARCHAR(64))
BEGIN
	# Description:
	#	Creates a fresh backup table to store data in.
	# Application:
	#	admin/wpdbpt_tables_ajax.php
	# Function:
	#	function backupTable_response

	DECLARE theCount INT DEFAULT 0;
    DECLARE rowCount INT DEFAULT 0;
    DECLARE runSQL VARCHAR(1000);
    DECLARE output VARCHAR(1000);
    DECLARE tableID INT DEFAULT 0;
    DECLARE thisMoment DATETIME DEFAULT NOW();

    SELECT COUNT(*) INTO theCount FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = backupTable;
	IF theCount = 1 THEN
		SET @setSQL = CONCAT('DROP TABLE ', backupTable, ';');
        PREPARE runSQL FROM @setSQL;
        EXECUTE runSQL;
        DEALLOCATE PREPARE runSQL;
    END IF;

    SET output = CONCAT('BACKUP TABLE EXIST: <b class="blue-font">', theCount, '</b><br />');

    SET @setSQL = CONCAT("CREATE TABLE ", backupTable, " SELECT * FROM ", myTable, ";");
    PREPARE runSQL FROM @setSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SET output = CONCAT(output, 'TABLE: <b class="blue-font">', backupTable, '</b> CREATED.<br />');
	SET @setSQL = CONCAT("SELECT COUNT(*) INTO @rowCount FROM ", myTable,";");
    PREPARE runSQL FROM @setSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SELECT @rowCount INTO rowCount;
    SET output = CONCAT(output, 'TOTAL ROWS BACKED UP:<b class="blue-font">', rowCount, '</b><br />');
    SET output = CONCAT(output, 'ACTION LOGGED<br />');
    SELECT COUNT(*), id INTO theCount, tableID FROM WORDPRESS_wpdbpt_backup_log WHERE backup_table = myTable;

    IF theCount = 1 THEN
		SET SQL_SAFE_UPDATES=0;
		UPDATE WORDPRESS_wpdbpt_backup_log SET backup_date = thisMoment, backup_rows = rowCount WHERE backup_table = myTable;
    ELSE
		INSERT INTO WORDPRESS_wpdbpt_backup_log(backup_date, backup_table, backup_rows)
        VALUES(thisMoment, myTable, rowCount);
    END IF;

    SET output = CONCAT(output, 'Backup of table <b class="blue-font">', myTable,
		'</b>. Created at <b class="blue-font">', thisMoment, '</b> with <b class="blue-font">', rowCount,
		'</b> rows backed up.<br />Table to drop: ',backupTable);

    CALL sp_LogActivity('CREATE TABLE BACKUP', myTable, rowCount, output, thisMoment);

	SELECT output;
END	#END PROC

CREATE PROCEDURE `sp_DisplayProcedures`(IN procSchema VARCHAR(64), IN display VARCHAR(5))
BEGIN
	# Description:
	#	Displays Stored Procedures for the Stored Procedures Tab
	# Applcation:
	#	admin/stored_procedures_ajax.php
    #	includes/functions.php
	# Function:
	#	displayProcedures_response - stored_procedures_ajax.php
    #	isCurrentProc - functions.php

	DECLARE theCount INT DEFAULT 0;

	IF display = 'Hide' THEN
		SELECT SPECIFIC_NAME, CREATED FROM INFORMATION_SCHEMA.ROUTINES
		WHERE (ROUTINE_TYPE = 'PROCEDURE' AND ROUTINE_SCHEMA = procSchema)
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
		ORDER BY SPECIFIC_NAME;

		SELECT COUNT(*) INTO theCount FROM WORDPRESS_wpdbpt_objects WHERE display_object = 'No' AND  object_type = 'PROCEDURE';
		IF theCount = 0 THEN
			UPDATE WORDPRESS_wpdbpt_objects SET display_object = 'No' WHERE object_type = 'PROCEDURE';
		END IF;
	ELSE
		SELECT SPECIFIC_NAME, CREATED FROM INFORMATION_SCHEMA.ROUTINES
		WHERE ROUTINE_TYPE = 'PROCEDURE' AND ROUTINE_SCHEMA = procSchema ORDER BY SPECIFIC_NAME;

		SELECT COUNT(*) INTO theCount FROM WORDPRESS_wpdbpt_objects WHERE display_object = 'Yes' AND  object_type = 'PROCEDURE';
		IF theCount = 0 THEN
			UPDATE WORDPRESS_wpdbpt_objects SET display_object = 'Yes' WHERE object_type = 'PROCEDURE';
		END IF;
	END IF;
END	#END PROC

CREATE PROCEDURE `sp_DisplayProcLog`()
BEGIN
	# Description:
	#	Displays all Stored Procedure activity.
	# Application:
	#	admin/stored_procedures_ajax.php
	# Function:
	#	function displayProcLog_response

	SELECT sp_name AS 'Name', sp_definition AS 'Procedure', sp_action AS 'Action', action_time AS 'Date'
    FROM WORDPRESS_wpdbpt_sp_activity_log ORDER BY id DESC LIMIT 0, 500;
END	#END PROC

CREATE PROCEDURE `sp_DisplaySQLHistory`()
BEGIN
	# Description:
	#	Displays all SQL activity that occurs on the Query Tool tab and includes SQL
	#	querys run in the Stored Procedures tab Populates the SQL History Panel.
	# Application:
	#	admin/query_tool_ajax.php
	# Function:
	#	function displaySQLHistory_response

	SELECT * FROM WORDPRESS_wpdbpt_sql_log ORDER BY sql_time DESC;
END	#END PROC

CREATE PROCEDURE `sp_FinalDrop`(IN dropTable VARCHAR(64))
BEGIN
	# Description:
	#	This procedure removes all memory of the table that is selected except in the activity log.
	# Application:
	#	admin/wpdbpt_tables_ajax.php
	# Function:
	#	function finalDrop_response

	DECLARE runSQL TEXT DEFAULT '';
    DECLARE output TEXT DEFAULT '';
    DECLARE thisCount INT DEFAULT 0;
    DECLARE rowCount INT DEFAULT 0;
    DECLARE tableName VARCHAR(64) DEFAULT '';
    DECLARE theMoment DATETIME DEFAULT NOW();

    SET SQL_SAFE_UPDATES = 0;	# SET SAFE UPDATES TO PREVENT ERRORS ON UPDATES WITH KEYS IN TABLE.
    SET tableName = REPLACE(dropTable, 'wpdrop_', '');
    SELECT DISTINCT table_rows INTO rowCount FROM WORDPRESS_wpdbpt_drop_table_log WHERE table_name = tableName;

    SET output = CONCAT('DROPPING TABLE <b class="blue-font">', tableName, '</b> WITH <b class="blue-font">', rowCount, '</b> ROWS.<br />');
    SET @thisSQL = CONCAT('DROP TABLE ', dropTable, ';');
	PREPARE runSQL FROM @thisSQL;
	EXECUTE runSQL;
	DEALLOCATE PREPARE runSQL;

    SET output = CONCAT(output, 'RUNNING:<pre>', @thisSQL, '<pre>');
    SELECT COUNT(*) INTO thisCount FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = tableName;

    IF thisCount = 1 THEN
		SET @thisSQL = CONCAT('DROP TABLE ', tableName, ';');

        SET output = CONCAT(output, 'RUNNING <pre>', @thisSQL, '</pre><br />');
        PREPARE runSQL FROM @thisSQL;
        EXECUTE runSQL;
        DEALLOCATE PREPARE runSQL;

        SET output = CONCAT('TABEL <b class="blue-font">', tableName, '</b> DROPPED.<br />');
    END IF;

    SET output = CONCAT(output, 'TABLE <b class="blue-font">', tableName, '</b> CAN NO LONGER BE RECORVERED.<br />');
    DELETE FROM WORDPRESS_wpdbpt_drop_table_log WHERE table_name = tableName;
    SET output = CONCAT(output, 'TABLE <b class="blue-font">', tableName, '</b> HAS BEEN CLEARED OUT OF DROP LOG.<br />');

    CALL sp_LogActivity("FINAL DROP", tableName, rowCount, output, theMoment);

    SET output = CONCAT(output, 'ACTION LOGGED.');
    SELECT output AS output;
END	#END PROC

CREATE PROCEDURE `sp_FirstDrop`(IN tableName VARCHAR(64), IN dropTable VARCHAR(64), IN createSQL TEXT)
BEGIN
	# Description:
	#	This procedure creates a backup of the data and the CREATE TABLE sql.
    #	in case the user decides to restore the table.
	# Application:
	#	admin/wpdbpt_tables_ajax.php
	# Function:
	#	function finalDrop_response

    DECLARE theMoment DATETIME DEFAULT NOW();
    DECLARE thisCount INT DEFAULT 0;
    DECLARE rowCount INT DEFAULT 0;
    DECLARE createTableSQL TEXT DEFAULT '';
    DECLARE runSQL TEXT DEFAULT '';
    DECLARE output TEXT DEFAULT '';
    DECLARE previousBackup VARCHAR(64) DEFAULT '';

    SET SQL_SAFE_UPDATES = 0;	# SET SAFE UPDATES TO PREVENT ERRORS ON UPDATES WITH KEYS IN TABLE.
    SET previousBackup = REPLACE(dropTable, 'wpdrop_', 'wpdbackup_');
    SET output = CONCAT('CHECKING for previous backup table: <b class="blue-font">', previousBackup, '</b><br />');
    SELECT COUNT(*) INTO thisCount FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = previousBackup;

    IF thisCount = 0 THEN
		SET output = CONCAT(output, 'NO PREVIOUS BACKUPS FOUND.<br />');
    ELSE
		SET output = CONCAT(output, 'PREVIOUS BACKUP OF <b class="blue-font">', previousBackup, '</b> FOUND GETTING DROPPED.</b><br />');
        SET output = CONCAT(output, 'RUNNING: <pre>DROP TABLE', previousBackup, '</pre><br />');
        SET @thisSQL = CONCAT('DROP TABLE ', previousBackup, ';');
		PREPARE runSQL FROM @thisSQL;
        EXECUTE runSQL;
        DEALLOCATE PREPARE runSQL;

        SET output = CONCAT(output, 'TABLE <b class="blue-font">', previousBackup, '</b> DROPPED.<br />');
    END IF;

    SET @thisSQL = CONCAT("SELECT COUNT(*) INTO @thisCount FROM ", tableName, ";");
    PREPARE runSQL FROM @thisSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SELECT @thisCount INTO rowCount;
    SET output = CONCAT(output, "SAVING YOUR CREATE TABLE:<pre>'", createSQL, "'</pre> Just in case.<br />");

    # CREATE backup drop table:
	SET @thisSQL = CONCAT("CREATE TABLE ", dropTable, " SELECT * FROM ", tableName, ";");
	SET output = CONCAT(output, 'RUNNING:<br><pre>', @thisSQL, '</pre><br />');

	PREPARE runSQL FROM @thisSQL;
	EXECUTE runSQL;
	DEALLOCATE PREPARE runSQL;

    IF rowCount > 0 THEN
		SET output = CONCAT(output, 'ROWS IN TABLE <b class="blue-font">', tableName, '</b> GETTING DROPPED:<b class="blue-font">', rowCount, '</b></br>');
		DELETE FROM WORDPRESS_wpdbpt_backup_log WHERE backup_table = tableName;
        DELETE FROM WORDPRESS_wpdbpt_restore_log WHERE table_name = tableName;

        SET output = CONCAT(output, 'TABLE <b class="blue-font">', tableName, '</b> Cleared from previous backups and restores.</br>');
		SET output = CONCAT(output, 'DROPPING TABLE <b class="blue-font">', tableName, '</b> INTO TABLE <b class="blue-font">', dropTable, '</b> FOR postierity.<br />');
		SET output = CONCAT(output, 'TABLE DATA Stored in DROP Table:<b class="blue-font">', dropTable, '</b><br />');
	ELSE
		SET output = CONCAT(output, 'TABLE <b class="blue-font">', tableName, '</b> HAD NO ROWS. TABLE STRUCTURE STORED in activity and drop log.<br />');
    END IF;

    SET @thisSQL = CONCAT('DROP TABLE ', tableName, ';');
    SET output = CONCAT(output, 'RUNNING: <pre>', @thisSQL, '</pre><br />');

    PREPARE runSQL FROM @thisSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SET output = CONCAT(output, 'TABLE <b class="blue-font">', tableName, '</b> DROPPED.</br>');

    INSERT INTO WORDPRESS_wpdbpt_drop_table_log (drop_date, table_name, create_table_sql, table_rows)
    VALUES(theMoment, tableName, createSQL, rowCount);

    SET output = CONCAT(output, 'DROP TABLE LOGGED ACTION ON TABLE <b class="blue-font">', tableName, '</b><br />');

    CALL sp_LogActivity('FIRST DROP', tableName, rowCount, output, theMoment);

    SET output = CONCAT(output, 'ACTION LOGGED.');
    SELECT output AS output;
END	#END PROC

CREATE PROCEDURE `sp_GetBackupDate`(IN thisTable VARCHAR(64))
BEGIN
	# Description:
	#	Gets backup date for table to display on RESTORE option
	# Application:
	#	includes/table_tools.php
	# function GetBackupDate($thisTable)

	SELECT backup_date FROM WORDPRESS_wpdbpt_backup_log WHERE backup_table = thisTable;
END	#END PROC

CREATE PROCEDURE `sp_GetCreateTableSQL`(IN theTable VARCHAR(64))
BEGIN
	# Description:
	#	Gets TABLE CREATE sql for storage.
	# Application:
	#	includes/table_tools.php
	# Function:
	#	function GetCreateTableSQL

	DECLARE thisMoment DATETIME DEFAULT NOW();
	DECLARE createSQL TEXT DEFAULT '';
    DECLARE tableName VARCHAR(64);
    DECLARE runSQL TEXT DEFAULT '';
    DECLARE output TEXT DEFAULT '';

    SET @setSQL = CONCAT('SHOW CREATE TABLE ', theTable, ';');
	PREPARE runSQL FROM @setSQL;
	EXECUTE runSQL;
	DEALLOCATE PREPARE runSQL;
END	#END PROC

CREATE PROCEDURE `sp_GetDropDate`(IN thisTable VARCHAR(64))
BEGIN
	# Description:
	#	Gets backup date for table to display on RESTORE option that is on final drop.
	# Application:
	#	includes/table_tools.php
	# function GetBackupDate($thisTable)

	SELECT drop_date FROM WORDPRESS_wpdbpt_drop_table_log WHERE table_name = thisTable;
END	#END PROC

CREATE PROCEDURE `sp_GetProcedureDefinition`(IN routine VARCHAR(64), IN procSchema VARCHAR(64))
BEGIN
	# Description:
	#	Procedure used to build stored procedure for display in editor.
	#	works in tandem with sp_GetProcedureParameters
	# Application:
	#	admin/stored_procedures_ajax.php
	# Function:
	#	function GetDefinition

	SELECT ROUTINE_DEFINITION AS output FROM INFORMATION_SCHEMA.ROUTINES
	WHERE SPECIFIC_NAME = routine AND ROUTINE_SCHEMA = procSchema;
END	#END PROC

CREATE PROCEDURE `sp_GetProcedureParameters`(procName VARCHAR(64), procSchema VARCHAR(64))
BEGIN
	# Description:
	#	Build parameters part of stored procedure for editor. Works with
	#	sp_GetProcedureDefinition.
	# Application:
	#	admin/stored_procedures_ajax.php
	# Function:
	#	function GetParameters($procedure)
	#	function BuildTestProcedure($procedure)

	SELECT ORDINAL_POSITION, PARAMETER_MODE, PARAMETER_NAME, DTD_IDENTIFIER
	FROM INFORMATION_SCHEMA.PARAMETERS
    WHERE SPECIFIC_NAME = procName AND SPECIFIC_SCHEMA = procSchema
	ORDER BY ORDINAL_POSITION;
END	#END PROC

CREATE PROCEDURE `sp_GetShowStatus`()
BEGIN
	# Description:
	#	Find out if they want to show builtin procedures.
	# Application:
	#	admin/stored_procedures.php
	# Function(s):
	#	function displayProcedures_response
	#	function getBlank_response

	SELECT DISTINCT display_object FROM WORDPRESS_wpdbpt_objects WHERE object_type = 'PROCEDURE';
END	#END PROC

CREATE PROCEDURE `sp_GetTableRowCount`(IN theTable VARCHAR(64))
BEGIN
	# Definition:
	#	Procedure to get accurate row count from tables as INFORMATION_SCHEMA
	#	may be unreliable after many test.
	# Application:
	#	includes/table_tools.php
	# Function:
	#	function GetRowCount

	DECLARE theRows INT DEFAULT 0;

    SET @sql = CONCAT('SELECT COUNT(*) AS theRows FROM ', theTable, ';');
    PREPARE runSQL from @sql;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;
END	#END PROC

CREATE PROCEDURE `sp_LoadSystemObjects`(IN dbName VARCHAR(64), IN wpPrefix VARCHAR(64))
BEGIN
	# Description:
	#	Load all Wordpress system tables and WPDB Stored Procedures
	#	into wpdbpt_objects table.
	# Application:
	#	includes/install.php
	# Function(s):
	#	function displayProcedures_response
	#	function getBlank_response

	DECLARE theObject VARCHAR(64) DEFAULT '';
	DECLARE theType VARCHAR(20) DEFAULT '';
	DECLARE theStatus VARCHAR(10) DEFAULT 'SYSTEM';
	DECLARE theCount INT DEFAULT 0;
	DECLARE setTime DATETIME DEFAULT NOW();

	-- WPDB System Tables:
	DECLARE wpdbTable VARCHAR(64) DEFAULT wpPrefix;
	DECLARE wpdbProcedureObjects TEXT DEFAULT '';
	DECLARE testWP INT DEFAULT 0;
	DECLARE testWPDB INT DEFAULT 0;
	DECLARE testWPDBProcs INT DEFAULT 0;
	DECLARE wpdbCount INT DEFAULT 0;
	DECLARE wpdbProcCount INT DEFAULT 0;
	DECLARE retStr VARCHAR(4000) DEFAULT '';

	-- CURSOR Pieces:
	DECLARE csr_object VARCHAR(64) DEFAULT '';
	DECLARE csr_type VARCHAR(64) DEFAULT '';
	DECLARE csr_done INT DEFAULT 0;
	DECLARE csr_objects CURSOR FOR # Get all current table and procedure objects:
		SELECT TABLE_NAME AS OBJECT, 'TABLE' AS OBJECT_TYPE
		FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = dbName UNION
		SELECT SPECIFIC_NAME AS OBJECT, 'PROCEDURE' AS OBJECT_TYPE
		FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_SCHEMA = dbName;

	-- Cursor to find the end of the recordset:
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET csr_done=1;

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

	SET wpdbTable = CONCAT(wpdbTable, 'wpdb');

	OPEN csr_objects;
	get_objects:LOOP
		FETCH csr_objects INTO theObject, theType;

		SELECT COUNT(*) INTO theCount FROM WORDPRESS_wpdbpt_objects WHERE object_name = theObject;
		IF theCount = 0 THEN
			SET testWPDB = INSTR(theObject, wpdbTable);
			SET testWPDBProcs = INSTR(wpdbProcedureObjects, theObject);
		ELSE
			SET testWPDB = 0;
			SET testWPDBProcs = 0;
		END IF;

		IF testWPDBProcs > 0 THEN
			SET wpdbProcCount = wpdbProcCount + 1;
			INSERT INTO WORDPRESS_wpdbpt_objects(object_name, object_type, object_status, deactivate_drop, object_logged)
			VALUES(theObject, theType, 'SYSTEM', 'Yes', setTime);
		ELSEIF testWPDB > 0 THEN
			SET wpdbCount = wpdbCount + 1;
			INSERT INTO WORDPRESS_wpdbpt_objects(object_name, object_type, object_status, deactivate_drop, object_logged)
			VALUES(theObject, theType, 'SYSTEM', 'Yes', setTime);
		END IF;

		IF csr_done=1 THEN
			LEAVE get_objects;
		END IF;
	END LOOP get_objects;

	SET retStr = CONCAT(retStr, ' WPDB Procedures:', wpdbProcCount, ' WPDB Tables:', wpdbCount, ' - added to WPDB Objects.');
	SELECT retStr AS output;
END	#END PROC

CREATE PROCEDURE `sp_LogActivity`(IN theAction VARCHAR(64), IN theTable VARCHAR(64), IN theRowCount INT(11), IN _actionOutput TEXT, IN theMoment DATETIME)
BEGIN
	# Description:
	#	This procdedure is exlusively used in all Stored Procedures to log BACKUP, RESTORE, DROP
	#	and FINAL DROP Procedures.
	# Application:
	#	admin/wpdbpt_tables_ajax.php procedures.
	# Stored Procedures:
	#	sp_CreateBackupTable
	#	sp_FinalDrop
	#	sp_FirstDrop
	#	sp_RestoreDrop
	#	sp_RestoreTable

	INSERT INTO WORDPRESS_wpdbpt_activity_log(the_action, the_table, the_row_count, action_output, the_moment)
    VALUES(theAction, theTable, theRowCount, _actionOutput, theMoment);
END	#END PROC

CREATE PROCEDURE `sp_LogSQL`(IN theMoment DATETIME, IN theSQL MEDIUMTEXT)
BEGIN
	# Description:
	#	Procedure to log all SQL Activity on the Query Tool page and picks
	#	up SQL ran on the Stored Procedures page.
	# Aplications:
	#	admin/query_tools.php
	# Function:
	#	function runSQL_response

	DECLARE theCount INTEGER DEFAULT 0;

    SELECT COUNT(*) INTO theCount FROM WORDPRESS_wpdbpt_sql_log WHERE the_sql = theSQL;

    IF theCount > 0 THEN
		UPDATE WORDPRESS_wpdbpt_sql_log SET sql_time = theMoment WHERE the_sql = theSQL;
    ELSE
		INSERT INTO WORDPRESS_wpdbpt_sql_log(sql_time, the_sql) VALUES(theMoment, theSQL);
    END IF;
END	#END PROC

CREATE PROCEDURE `sp_LogStoredProcActivity`(IN spName VARCHAR(64), IN procDef MEDIUMTEXT, IN spAction VARCHAR(45))
BEGIN
	# Description:
	#	Logs CREATE, UPDATE (actually a DROP CREATE), DROP Stored Procedure Activity.
	# Application:
	# 	admin/stored_procedures_ajax.php
	# Functions:
	#	function buildProcedure_response
	#	function dropProcedure_response
	DECLARE theMoment DATETIME DEFAULT NOW();

	INSERT INTO WORDPRESS_wpdbpt_sp_activity_log(sp_name, sp_definition, sp_action, action_time)
	VALUES(spName, procDef, spAction, theMoment);
END	#END PROC

CREATE PROCEDURE `sp_RemoveSQL`(IN theID INT(11))
BEGIN
	# Description:
	#	Procedure to remove SQL from history panel in Query Tool Tab
	# Application:
	#	admin/query_tool_ajax.php
	# Function:
	#	function removeSQL

	DELETE FROM WORDPRESS_wpdbpt_sql_log WHERE id = theID;
END	#END PROC

CREATE PROCEDURE sp_RemoveTablesOnDeactivate(IN dbName VARCHAR(64), IN wpPrefix VARCHAR(100))
BEGIN
	# Description:
	#	Gets all wpdbackup and wpdrop tables for removal on dectivation and removes them.
	#	leaving wpdrop tables as rescue if needed.
	# Application:
	#	includes/uninstall.php
	# Function(s):
	#	function RemoveTablesOnDeactivate

	DECLARE lookForThis VARCHAR(64) DEFAULT wpPrefix;
	DECLARE orThis VARCHAR(64) DEFAULT wpPrefix;
	DECLARE orThisTo VARCHAR(64) DEFAULT wpPrefix;
	DECLARE output TEXT DEFAULT '';
	DECLARE runSQL TEXT DEFAULT '';

	-- CURSOR Stuff:
	DECLARE csr_dropTable VARCHAR(64) DEFAULT '';
	DECLARE csr_done INT DEFAULT 0;
	DECLARE csr_dropTables CURSOR FOR
		SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
		WHERE TABLE_SCHEMA = dbName AND (
			TABLE_NAME LIKE lookForThis OR TABLE_NAME LIKE orThis OR TABLE_NAME LIKE orThisTo
		);

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET csr_done=1;

	SET lookForThis = CONCAT(lookForThis, 'wpdbpt%');
	SET orThis = CONCAT(orThis, 'wpdrop%');
	SET orThisTo = CONCAT(orThisTo, 'wpdbackup%');
	-- Open CURSOR For Reading:
	OPEN csr_dropTables;
	get_Tables:LOOP
		FETCH csr_dropTables INTO csr_dropTable;
		IF csr_done=1 THEN
			LEAVE get_Tables;
		END IF;

		SET @dropSQL = CONCAT('DROP TABLE IF EXISTS ', csr_dropTable, ';');
		PREPARE runSQL FROM @dropSQL;
		EXECUTE runSQL;
		DEALLOCATE PREPARE runSQL;
	 END LOOP;

	 SELECT output;
END	#END PROC

CREATE PROCEDURE `sp_RestoreDrop`(IN backupTable VARCHAR(64), IN restoreTable VARCHAR(64))
BEGIN
	# Description:
	#	Restores dropped table to its state prior to being dropped.
	# Application:
	#	admin/wpdbpt_table_tab_ajax.php
	# Function:
	#	function restoreDrop_response

	DECLARE theMoment DATETIME DEFAULT NOW();
    DECLARE thisCount INT DEFAULT 0;
    DECLARE createTableSQL TEXT DEFAULT '';
    DECLARE runSQL TEXT DEFAULT '';
    DECLARE rowCount INT DEFAULT 0;
    DECLARE output TEXT DEFAULT '';

    SET SQL_SAFE_UPDATES = 0;	# SET SAFE UPDATES TO PREVENT ERRORS ON UPDATES WITH KEYS IN TABLE.
    -- Make sure restore table does not exist:
    SELECT DISTINCT table_rows, create_table_sql INTO rowCount, createTableSQL FROM WORDPRESS_wpdbpt_drop_table_log WHERE table_name = restoreTable;
    SET output = CONCAT('CREATING TABLE <b class="blue-font">', restoreTable, '</b> WITH<pre>', createTableSQL, '</pre><br />');
    SET @thisSQL = createTableSQL;
	PREPARE runSQL FROM @thisSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    IF rowCount > 0 THEN
		SET output = CONCAT(output, 'TABLE <b class="blue-font">', restoreTable, '</b> IS BEING RESTORED WITH <b class="blue-font">', rowCount, '</b> ROWS.<br />');
        SET @thisSQL = CONCAT('INSERT INTO ', restoreTable, ' SELECT * FROM ', backupTable, ';');
        SET output = CONCAT(output, 'RUNNING:<pre>', @thisSQL, '</pre><br />');
        #Repopulate here:
        PREPARE runSQL FROM @thisSQL;
        EXECUTE runSQL;
        DEALLOCATE PREPARE runSQL;

        SET output = CONCAT(output, 'TABLE <b class="blue-font">', restoreTable, '</b> RESTORED WITH <b class="blue-font">', rowCount, '</b><br />');
	ELSE
		SET output = CONCAT(output, 'TABLE <b class="blue-font">', restoreTable, '</b> IS RESTORED. IT DID NOT HAVE ANY ROWS ROWS.<br />');
    END IF;

    # Clear out of wpdbpt_dropped_table_log:
    DELETE FROM WORDPRESS_wpdbpt_drop_table_log WHERE table_name = restoreTable;
    SET output = CONCAT(output, 'TABLE <b class="blue-font">', restoreTable, ' </b> REMOVED FROM DROPPED STATUS.<br />');

    SET @thisSQL = CONCAT('DROP TABLE ', backupTable, ';');

    SET output = CONCAT(output, 'RUNNING <pre>', @thisSQL, '</pre><br />');
    PREPARE runSQL FROM @thisSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SET output = CONCAT(output, 'TABLE <b class="blue-font">', backupTable, '</b> DROPPED<br />');
    # Log Action:
    CALL sp_LogActivity('RESTORE DROPPED TABLE', restoreTable, rowCount, output, theMoment);

    SET output = CONCAT(output, 'ACTION LOGGED');
    SELECT output AS output;
END	#END PROC

CREATE PROCEDURE `sp_RestoreTable`(IN tableFrom VARCHAR(64), IN tableTo VARCHAR(64))
BEGIN
	# Description:
    #	Restores table to the state it was in when it was backed up
    # Application:
    #	admin/wpdbpt_tables_ajax.php
    # Function:
    #	function restoreTable_response

    DECLARE runSQL VARCHAR(1000);
    DECLARE output VARCHAR(1000);
    DECLARE rowCount INT DEFAULT 0;
    DECLARE theCount INT DEFAULT 0;
    DECLARE backupDate DATETIME;
	DECLARE theMoment DATETIME DEFAULT NOW();
    DECLARE restoreText VARCHAR(100) DEFAULT '';

    SET @setSQL = CONCAT("TRUNCATE ", tableFrom, ";");
    PREPARE runSQL FROM @setSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SET output = CONCAT('TABLE: <b class="blue-font">', tableFrom, '</b> Has been TRUNCATED.<br />');

    SET @setSQL = CONCAT("INSERT INTO ", tableFrom, " SELECT * FROM ", tableTo, ";");
    PREPARE runSQL FROM @setSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SELECT backup_date, backup_rows INTO backupDate, rowCount FROM WORDPRESS_wpdbpt_backup_log WHERE backup_table = tableFrom;
    SET output = CONCAT(output, 'TABLE: <b class="blue-font">', tableFrom, '</b> has been repopulated ');
    SET output = CONCAT(output, ' with <b class="blue-font">', rowCount, '</b> rows. From date: <b class="blue-font">', backupDate, '</b><br />');
    SELECT COUNT(*) INTO theCount FROM WORDPRESS_wpdbpt_restore_log WHERE table_name = tableFrom;

    IF theCount = 0 THEN
		SET @setSQL = CONCAT("INSERT INTO WORDPRESS_wpdbpt_restore_log(table_name, backup_date, restore_date, row_count)
			VALUES('", tableFrom, "','", backupDate, "','", theMoment, "','", rowCount, "');");
        SET output = CONCAT(output, 'TABLE <b class="blue-font">', tableFrom, '</b> Added to <b class="blue-font">WORDPRESS_wpdbpt_restore_log</b><br />');
    ELSE
		SET @setSQL = CONCAT("UPDATE WORDPRESS_wpdbpt_restore_log SET backup_date = '", backupDate, "', restore_date = '", theMoment,
			"', row_count = '", rowCount, "' WHERE table_name = '", tableFrom, "';");

        SET output = CONCAT(output, 'TABLE <b class="blue-font">', tableFrom, '</b> Updated in <b class="blue-font">WORDPRESS_wpdbpt_restore_log</b><br />');
    END IF;

	SET SQL_SAFE_UPDATES = 0;
    PREPARE runSQL FROM @setSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

    SET output = CONCAT('RESTORED TABLE WITH DATA FROM: ', backupDate, 'Removed Backup Table ', tableFrom, '<br />');
	SET @setSQL = CONCAT('DROP TABLE IF EXISTS ', tableTo, ';');
    PREPARE runSQL FROM @setSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;

	SET output = CONCAT(output, 'BACKUP table <b class="blue-font">', tableTo, '</b> removed.<br />');
    CALL sp_LogActivity('RESTORE TABLE', tableFrom, rowCount, output, theMoment);

    SET output = CONCAT(output, 'ACTION LOGGED.');

    SELECT output;
END	#END PROC

CREATE PROCEDURE `sp_ShowTableRows`(IN tableName VARCHAR(64))
BEGIN
	# Description:
	#	This displays 500 rows in the tables selected on the WPDB Tables tab
	#	ordered by the firsdt field DESC to show the 500 most recent records.
	# Application:
	#	admin/wpdbpt_tables_ajax.php
	# Function:
	#	function ShowTables_response

	DECLARE runSQL VARCHAR(1000) DEFAULT '';

	SET @thisSQL = CONCAT('SELECT * FROM ', tableName, ' ORDER BY 1 DESC LIMIT 0, 500;');
    PREPARE runSQL FROM @thisSQL;
    EXECUTE runSQL;
    DEALLOCATE PREPARE runSQL;
END	#END PROC

CREATE PROCEDURE `sp_ShowTables`(IN whichTables VARCHAR(5))
BEGIN
	# Description:
	#	This is to select what to display on the WPDB Tables tab. All Tables, only row and no rows.
	# Application:
	#	admin/wpdbpt_tables_ajax.php
	# Function:
	#	function ShowTables_response

	CASE whichTables
		WHEN 'ALL' THEN
			SELECT TABLE_NAME AS 'TABLE', TABLE_ROWS AS 'ROWS', ROUND(DATA_LENGTH + INDEX_LENGTH) AS 'SIZE'
            FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_ROWS = 0 OR TABLE_ROWS > 0 ORDER BY TABLE_NAME;
        WHEN 'EMPTY' THEN
			SELECT TABLE_NAME AS 'TABLE', TABLE_ROWS AS 'ROWS', ROUND(DATA_LENGTH + INDEX_LENGTH) AS 'SIZE'
            FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_ROWS = 0 ORDER BY TABLE_NAME;
        WHEN 'ROWS' THEN
			SELECT TABLE_NAME AS 'TABLE', TABLE_ROWS AS 'ROWS', ROUND(DATA_LENGTH + INDEX_LENGTH) AS 'SIZE'
            FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_ROWS > 0 ORDER BY TABLE_NAME;
	END CASE;
END	#END PROC
