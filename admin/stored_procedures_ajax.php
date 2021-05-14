<?php
    add_action('wp_ajax_buildProcedure', 'buildProcedure_response');
    add_action('wp_ajax_displayProcedures', 'displayProcedures_response');
    add_action('wp_ajax_DisplayProcedureTest', 'DisplayProcedureTest_response');
    add_action('wp_ajax_displayProcLog', 'displayProcLog_response');
    add_action('wp_ajax_DisplayProtected', 'DisplayProtected_response');
    add_action('wp_ajax_DisplayTestForm', 'DisplayTestForm_response');
    add_action('wp_ajax_dropProcedure', 'dropProcedure_response');
    add_action('wp_ajax_getBlank', 'getBlank_response');
    add_action('wp_ajax_loadSP', 'loadSP_response');

    function DisplayProcedureTest_response(){
        global $wpdb;

        $wpdb->show_errors = true;
        $procValues = $_REQUEST['queryText'];
        $procValues = explode('^~#!#~^', $procValues);
        $procValueCount = count($procValues);
        $sql = '';

        if($procValueCount === 1){
            $sql = 'CALL ' . $procValues[0] . '();';
        }
        else{
            for($i = 0; $i < count($procValues); $i++){
                if($i === 0){
                    $sql = "CALL " . $procValues[$i] . "('";
                }
                else{
                    if($i < ($procValueCount - 1)){
                        $sql = $sql . $procValues[$i] . "','";
                    }
                    else{
                        $sql = $sql . $procValues[$i] . "');";
                    }
                }
            }
        }
        echo '<pre>' . $sql . '</pre><b>Executed at </b>' . GetTimeNow();
        $records = $wpdb->get_results($sql);
        $columnNames = $wpdb->get_col_info('name');

        echo '<table class="sp_results_table"><tr class="results_tr">';
        foreach ($columnNames as $name) {
            echo '<th class="sp_results_th">' . $name . '</th>';
        }
        echo '</tr>';
        foreach ($records as $key => $row) {
            echo '<tr class="sp_results_tr">';
            foreach ($row as $field => $value) {
                switch($field){
                    case 'the_sql':
                        echo '<td class="sp_results_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                        break;
                    case 'output':
                        echo '<td class="sp_results_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                        break;
                    default:
                        echo '<td class="sp_results_td">' . $value . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';

        if($wpdb->last_error !== ''){
            $lastError = $wpdb->last_error;
            $lastResult   = $wpdb->last_result;

            print "<p><strong>WordPress database error:</strong> [$lastResult]<br />
            $lastError</p>";
        }
        $wpdb->show_errors = false;
        wp_die();
    }

    function DisplayTestForm_response(){
        global $wpdb;

        $procedure = $_REQUEST['queryText'];
        $sql = 'CALL sp_GetProcedureParameters("' . $procedure . '","' . DB_NAME . '");';
        $records = $wpdb->get_results($sql);
        $totalRows = $wpdb->num_rows;

        echo '<form class="test_sp_form" method="post" enctype="multipart/form-data" name="test-' . $procedure . '" id="' . $procedure . '">';
        echo '<input type="hidden" class="test_sp_hiden" name="procedure" value="' . $procedure . '">';

        $rowCount = 0;
        if($totalRows != $rowCount){
            echo '<b class="test-proc-name">CALL ' . $procedure . '(';
            foreach ($records as $key => $row) {
                $rowCount = $rowCount + 1;
                $paramMode =  $row->PARAMETER_MODE;
                $paramName = $row->PARAMETER_NAME;
                $thisParam = strtoupper($row->DTD_IDENTIFIER);
                $paramType = GetParamType($thisParam);

                if($paramMode == 'IN'){ // Input Parameter add to form:
                    $placeHolder = $paramName . '-'. $thisParam;
                    $textWidth = strlen($placeHolder);
                    switch($paramType){
                        case 'HAR': // CHAR, VARCHAR, CHAR BYTE,
                            if($paramName == 'procSchema'){
                                echo "'<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                    "' size='" . $textWidth . "' value='" . DB_NAME . "'>'";
                                break;
                            }
                            echo "'<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>'";
                            break;
                        case 'EXT': // TEXT, TINYTTEXT, MEDIUMTEXT, LONGTEXT
                            echo "'<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>'";
                            break;
                        case 'SON': // JSON
                            echo "'<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>'";
                            break;
                        case 'ATETIME': // DATETIME
                            echo "'<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>'";
                            break;
                        case 'ATE': // DATE
                            echo "'<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>'";
                            break;
                        case 'IME': // TIME
                            echo "'<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>'";
                            break;
                        case 'NT':  // INTEGER
                            echo "<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>";
                            break;
                        case 'OOLEAN': // BOOLEAN
                            echo "<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>";
                            break;
                        case 'EC':  // DECIMAL
                            echo "<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>";
                            break;
                        case 'UMBER':   // NUMBER
                            echo "<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>";
                            break;
                        case 'LOAT': // FLOAT NUMBER
                            echo "<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>";
                            break;
                        case 'OUBLE':   // DOUBLE NUMBER
                            echo "<input class='test_sp_text' type='text' name='" . $paramName . "' placeholder='" .
                                $placeHolder . "' size='" . $textWidth . "'>";
                            break;
                        default:
                            echo 'Uhh Houston? Houston are you there?<br />';
                    }
                    if($rowCount < $totalRows){
                        echo ',';
                    }
                    else{
                        echo '); <a class="test_sp_submit_a" id="' . $procedure . '" name="test-sp" value="Run Test">Run Test</a>';
                    }
                }
            }
        }
        else{
            echo '<b class="test-proc-name">CALL ' . $procedure . '();' .
                '<a class="test_sp_submit_a" id="' . $procedure . '" name="test-sp" value="Run Test">Run Test</a>';
        }

        wp_die();
    }

    function displayProcLog_response(){
        global $wpdb;

        $sql = 'CALL sp_DisplayProcLog();';
        $records = $wpdb->get_results($sql);
        $col_names = $wpdb->get_col_info('name');
        $rowCount = $wpdb->num_rows;

        if($rowCount > 0){
            echo '<table class="results-table">';
            echo '<tr class="results-tr">';
            foreach($col_names as $name){
                echo '<th class="results-th">' . $name . '</td>';
            }
            echo '</tr>';
            foreach($records as $key => $row){
                echo '<tr class="results-tr">';
                foreach ($row as $field => $value) {
                    $fieldName = strtolower($field);

                    switch($fieldName){
                        case 'procedure':
                            echo '<td class="text_data_td"><pre class="text_data_pre">' . $value . '</pre></td>';
                            break;
                        default:
                            echo '<td class="results-td">' . $value . '</td>';
                            break;
                    }
                }
                echo '</tr>';
            }
            echo '</table>';
        }
        else{
            echo 'Nothing to display until you CREATE, UPDATE or DROP Procedures.';
        }

        wp_die();
    }

    function dropProcedure_response(){
        global $wpdb;

        $wpdb->show_errors = true;
        $spName = $_REQUEST['queryText'];
        $fullProcedure = $_REQUEST['queryText'];
        $parameters = GetParameters($spName) ;
        $definition = chr(13) . GetDefinition($spName);
        $proc = $parameters . $definition;
        $thisMoment = GetTimeNow();

        $proc = str_replace('        BEGIN','BEGIN', $proc);
        $sql = 'CALL sp_LogStoredProcActivity("' . $fullProcedure . '","' . $proc . '","DROP PROCEDURE");';
        $wpdb->query($sql);

        $sql = 'DROP PROCEDURE IF EXISTS ' . $spName . ';';
        $wpdb->query($sql);

        echo 'PROCEDURE <b class="blue-font">' . $spName .
            '</b> HAS BEEN DROPPED AND LOGGED AT <b class="blue-font">' . GetTimeNow() . '</b> SHOULD YOU NEED TO BUILD IT AGAIN.';

        wp_die();
    }

    function loadSP_response(){
        global $wpdb;

        $sp = $_REQUEST['queryText'];
        $parameters = trim(GetParameters($sp)) ;
        $definition = chr(13) . GetDefinition($sp);

        $procedure = $parameters . $definition;
        echo $procedure;
    }

    function DisplayProtected_response(){
        global $wpdb;

        $sp = $_REQUEST['queryText'];
        $parameters = trim(GetParameters($sp)) ;
        $definition = chr(13) . GetDefinition($sp);
        $procedure = '#### THIS IS FOR VIEWING ONLY ####' . chr(13) . $parameters .
            $definition . chr(13) . '###### THIS WILL NOT MODIFY ######';

        echo $procedure;
        wp_die();
    }

    function displayProcedures_response(){
        global $wpdb;
        $wpdb->show_errors = true;
        $display = '';

        if((isset($_REQUEST['display'])) && (($_REQUEST['display'] == 'Show') || ($_REQUEST['display'] == 'Hide'))){
            $display = $_REQUEST['display'];
        }
        else{
            $sql = 'CALL sp_GetShowStatus()';
            $display = $wpdb->get_var($sql);
            if($display == 'No'){
                $display = 'Hide';
            }
            else{
                $display = 'Show';
            }
        }

        $sql = 'CALL sp_displayProcedures("' . DB_NAME . '","' . $display . '");';
        $sps = $wpdb->get_results($sql);
        $col_names = $wpdb->get_col_info('name');
        // Show column names:
        echo '<table class="sp_table"><tr class="sp_tr">';
       foreach($col_names as $name){
            if($name == 'SPECIFIC_NAME'){
                $name = 'Click to View or Edit';
                echo '<th class="sp_th">' . $name . '</th><th class="sp_th"></th><th class="sp_th"></th>';
            }
            else{
                echo '<th class="sp_th">' . $name . '</th>';
            }
        }
        echo '</tr>';
        $procName = '';

        foreach($sps as $key => $row){
            echo '<tr class="sp_tr">';
            foreach($row as $field => $value){
                if($field == 'SPECIFIC_NAME'){
                    $procName = $value;
                    $noEditNoDrop = isProtectedList($value);
                    if($noEditNoDrop == 1){// nend = NO EDIT NO DROP - But you can view.
                        echo '<td class="nend_name_td"><a class="nend_a" id="' . $value . '">' . $value . '</a></td>
                            <td class="nend_td"></td>';
                    }
                    else{
                        echo '<td class="sp_name_td" id="'. $value . '">
                            <a class="sp_name_a" id="'. $value . '">'.  $value . '</a></td>
                            <td class="sp_drop_td" id="'. $value . '">
                                <a class="sp_drop_a" id="'. $value . '">DROP</a></td>';
                    }
                }
                else{
                    echo '<td class="sp_test_td"><a class="test_sp_a" id="' . $procName . '">TEST</a></td>';
                    echo '<td class="sp_date_td">' . $value . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';

        $wpdb->show_errors = false;
        if(isset($_REQUEST['fromQuery'])) wp_die(); //If this is done scripts do not load.
    }

    function buildProcedure_response(){
        global $wpdb;
        $wpdb->show_errors = true;

        $spAction = 'CREATE PROCEDURE';
        $sql = $_REQUEST['queryText'];
        $sql = stripcslashes($sql);
        $sqlArray = explode('(', $sql);
        $procedureName = str_replace('CREATE PROCEDURE ', '', $sqlArray[0]);

        $procedure = $sql;
        $protectProcedure = isProtectedProc($procedureName);
        if($protectProcedure > 0){
            $thisMoment = GetTimeNow();
            echo '<b class="red-font">This Stored Procedure is protected and cannot be edited.</b> ' . $thisMoment;
            wp_die();
        }

        $responseText = '';
        $isProtected = isProtectedProc($procedureName);
        $procExists = isCurrentProc($procedureName);

        if($procExists == 'noProc'){
            $spAction = 'CREATE PROCEDURE';
            $responseText = 'New Stored Procedure <b class="blue-font">' . $procedureName . '</b> added and logged at <b class="blue-font">' . GetTimeNow() . '</b>';
        }
        else{
            $spAction = 'UPDATE PROCEDURE';
            $dropSQL = "DROP procedure IF EXISTS `" . $procExists . "`;";
            $wpdb->query($dropSQL);
            $theMoment =
            $responseText = 'Procedure <b class="blue-font">' . $procedureName . '</b> has been updated and logged at <b class="blue-font">' . GetTimeNow() . '</b>';
        }

        $wpdb->query($sql); // Stored Proceudre created.
        if($wpdb->last_error != ''){
            $thisError = $wpdb->last_error;
            $responseText = '<b class="blue-font">MariaDB ERROR:</b> <b class="red-font">' . $thisError . '</b> <b class="blue-font">' . GetTimeNow() . '</b><br />';
            echo $responseText;
        }

        if(strpos($responseText, 'MariaDB ERROR') == 0){
            $thisMoment = GetTimeNow();
            $procedure = addslashes($procedure);

            $sql = 'CALL sp_LogStoredProcActivity("' . $procedureName . '","' . $procedure . '","' . $spAction . '");';
            $wpdb->query($sql);
            echo $responseText;
        }

        $wpdb->show_errors = false;
        wp_die();
    }

    function getBlank_response(){
        $sp = $_REQUEST['queryText'];

        $parameters = trim(GetParameters($sp)) ;
        $definition = chr(13) . GetDefinition($sp);
        $sp = $parameters . $definition . chr(13);
        $sp = '## DELETE COMMENTS AT TOP AND BOTTOM AND MODIFY PROCEDURE TO YOUR NEEDS ###' . chr(13) . $sp .
            '## DELETE COMMENTS AT TOP AND BOTTOM AND MODIFY PROCEDURE TO YOUR NEEDS ###';
        echo $sp;
        wp_die();
    }
