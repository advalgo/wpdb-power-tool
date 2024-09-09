jQuery(document).ready(function($){
    // Initialize values:
    var thisTab = $('.nav-tab-active').text();
    var timeDelay = 1000;
    // Mobile resizable Editor!
    $(".editor-div").draggable();
    $(".hist-div").draggable();

    // Copy Text to clipboard:
    $(document).on('click', '.text_data_pre', function(){
        var copyText = $(this).html();
        document.execCommand("copy");
    });

    // Tweak display for Query Tool and Stored Procedure Tabs:
    if((thisTab == "Query Tool") || (thisTab = 'Stored Procedures')){
        if(thisTab == "Query Tool"){
            $('.editor_wrapper').css('width','600px');
            $('.editor_header_wrapper').css('width','604px');
            $('.editor_width_text').val('600');
            $('.editor_wrapper').css('height','250px');
            $('.editor_height_text').val('250');

            $('.history_wrapper').css('width','600px');
            $('.history_header_wrapper').css('width','604px');
            $('.history_width_text').val('600');
            $('.history_wrapper').css('height','250px');
            $('.history_height_text').val('250');

            $('.editor-div').css('grid-row','2');
            $('.editor-div').css('grid-column','5 / span 2');
            $('.hist-div').css('grid-row', '3 / SPAN 2');
            $('.hist-div').css('grid-column', '5 / span 2');

            var testStr = $('.builder_tables').html();
            var builderTablesLength =  testStr.length;

            if(builderTablesLength == 0){
                $('.results-div').hide();
                $('.builder_tables').show();
                $('.alert-div').hide();
                $('span.select_table_span').show();
                $('.query_div').show();
                $('.show_output').show();

                SimpleCall('DisplayQueryTables', '', '.builder_tables');
            }
            else{
                $('.builder_table').hide();
                $('.builder_tables').show();
                $('.select_tables').show();
            }
        }
        if(thisTab == 'Stored Procedures'){
            $('.editor_wrapper').css('width','950px');
            $('.editor_header_wrapper').css('width','954px');
            $('.editor_width_text').val('950');
            $('.editor_wrapper').css('height','615px');
            $('.editor_height_text').val('615');

            $('.editor-div').css('grid-row','2 / span 4');
            $('.editor-div').css('grid-column','3 / span 4');
        }
    }
    /* ---------------------- END ------------------------ */
    /******************** Query Builder ********************/
    $(document).on('click', '#select_builder', function(){
        $('.results-div').hide();
        $('.builder_tables').show();
        $('.alert-div').hide();
        $('span.select_table_span').show();
        $('.query_div').show();
        $('.show_output').show();
        $('.select_table_div').show();
        $('.builder_select').hide();

        var testStr = $('.builder_tables').html();
        var builderTablesLength =  testStr.length;
        if(builderTablesLength == 0){
            SimpleCall('DisplayQueryTables', '', '.builder_tables');
        }
        else{
            $('.builder_table').hide();
            $('.builder_tables').show();
            $('.select_tables').show();
        }
    });

    $(document).on('click', '#show_output', function(){
        $('.select_table_div').hide();
        $('.builder_tables').hide();
        $('.builder_select').hide();
        $('.builder_table').hide();
        $('.results-div').show();
        $('.alert-div').show();
    });

    $(document).on('click', '.select_table_td', function(){
        var note = 'Click on the fields you would like to SELECT '+
        'in the order you would like to view them.';
        $('.select_table_div').hide();
        $('#show_table').show();
        $('.builder_select').show();
        $('.builder_tables').hide();
        $('.select_tables').hide();
        $('.builder_table').show();

        var table = $(this).attr('id');
        $('.from_table').html('FROM '+table);
        $('.select_fields').html('');
        $('.where_fields').html('');
        $('.order_by_fields').html('');
        $('.limit_fields').html('');
        $('.select_table_span').show()
        $('.note').html(note);
        $('#reset_select').show();

        $('.note').show();

        myEditor.codemirror.setValue('');
        SimpleCall('DisplaySelectTable', table, '.builder_table');
    });

    $(document).on('click', '#show_table', function(){
        $('.alert-div').hide();
        $('.select_table_div').hide();
        $('.builder_tables').hide();
        $('.results-div').hide();
        $('.builder_table').show();
        $('.builder_select').show();
        $('.note').show();
        $('#show_output').show();
    });

    $(document).on('click', '#select_id', function(){
        var tempItems = $('.select_fields').text();
        var tempItemsLength = tempItems.length;

        $('.field_name_td').css('background-color', 'lightseagreen');
        $('.field_name_td').css('color', 'aqua');

        if(tempItemsLength > 0){
            tempItems = tempItems.replace('SELECT ', '');

            var selectedItems = tempItems.split(', ');
            var thisID = '';

            for(var i=0; i< selectedItems.length; i++){
                thisID = '#'+selectedItems[i];
                $(thisID).css('background-color', 'aqua');
                $(thisID).css('color', 'lightseagreen');
            }
        }

        $('.field_name_td').attr('scope', 'SELECT');
        $('span.select_table_span').hide();
        $('.note').html('Click on Fields you want to select in the order you want them displayed.');
        $('.note').show();

        $('#reset_where').hide();
        $('#reset_order').hide();
        $('#reset_select').show();
    });

    $(document).on('click', '#where_id', function(){
        var tempItems = $('.where_fields').text();
        var tempItemsLength = tempItems.length;

        $('.field_name_td').css('background-color', 'MidnightBlue');
        $('.field_name_td').css('color', 'aqua');

        if(tempItemsLength > 0){
            tempItems = tempItems.replace('WHERE ','');

            var selectedItems = tempItems.split(' ');
            var thisID = '';
            var thisItem = '';

            for(var i=0; i< selectedItems.length; i++){
                thisItem = selectedItems[i];
                // Get back to this - need to update togggle.
            }
        }
        $('.field_name_td').attr('scope', 'WHERE');
        $('span.select_table_span').hide();
        $('.note').html('You must add criteria =, !=, >. <,LIKE, NOT LIKE, to WHERE field followed by the value '+
            'and if you have more than one criteria you will need an AND or OR to combine them or you will '+
            'get an error Example Usage:<br /><b class="blue-font">WHERE text_field LIKE "%some text%" AND '+
            'number_field > 0 OR text_field != "some other  text"</b>');
        $('.note').show();

        $('#reset_select').hide();
        $('#reset_order').hide();
        $('#reset_where').show();
    });

    $(document).on('click', '#order_id', function(){
        $('.field_name_td').css('background-color', 'green');
        $('.field_name_td').css('color', 'aqua');
        $('.field_name_td').attr('scope', 'ORDER');
        $('span.select_table_span').hide();
        $('.note').html('ORDER BY will order ascending (ASC) unless it is followed by DESC. '+
            'You can have more than one ORDER BY as well however they will follow in order selected.<br />'+
            'Example Usage:<br /><b class="blue-font">ORDER BY post_date DESC, first_name</b>');
        $('.note').show();

        $('#reset_select').hide();
        $('#reset_where').hide();
        $('#reset_order').show();
    });

    $(document).on('click','#reset_select', function(){
        $('.field_name_td').css('background-color','lightseagreen');
        $('.field_name_td').css('color', 'aqua');
        $('.select_fields').html('');
        LoadQuery();
    });

    $(document).on('click', '#reset_where', function(){
        $('.field_name_td').css('background-color','midnightblue');
        $('.field_name_td').css('color', 'aqua');
        $('.where_fields').html('');
        LoadQuery();
    });

    $(document).on('click', '#reset_order', function(){
        $('.field_name_td').css('background-color','black');
        $('.field_name_td').css('color', 'aqua');
        $('.order_by_fields').html('');
        LoadQuery();
    });

    function LoadQuery(){
        var sql = $('.select_fields').html()+"\n"+
            $('.from_table').text()+"\n"+
            $('.where_fields').text()+"\n"+
            $('.order_by_fields').text();
        myEditor.codemirror.setValue(sql);
    }

    $(document).on('click', '#limit_id', function(){
        var sql = $('.select_fields').html()+"\n"+
            $('.from_table').html()+"\n"+
            $('.where_fields').html()+"\n"+
            $('.order_by_fields').html()+'LIMIT 0, 500';

        myEditor.codemirror.setValue(sql);
    });

    $(document).on('click', '.field_name_td', function(){
        var thisField = $(this).attr('id');
        var select = $('.select_fields').html();
        var where = $('.where_fields').html();
        var order_by = $('.order_by_fields').html();

        var selectLength = select.length;
        var whereLength = where.length;
        var orderByLength = order_by.length;

        var newSelect = '';
        var newWhere = '';
        var newOrderBy = '';
        var testStr = '';
        var hasComma = '';
        var extraComma = '';
        var note = '';
        var msgStr = '';

        var scope = $(this).attr('scope');
        var bg_color = $(this).css('background-color');
        var color = $(this).css('color');

        //alert('bgc: '+bg_color+' scope: '+scope);
        if(scope == 'SELECT'){
            if(bg_color == 'rgb(32, 178, 170)'){
                // Field SELECTED:
                $(this).css('background-color','aqua');
                $(this).css('color', 'lightseagreen');

                if(selectLength == 0){
                    select = 'SELECT '+thisField;
                }
                else{
                    select = select + ", " + thisField;
                }
            }
            else{ // Field UNSELECTED:
                $(this).css('background-color','lightseagreen');
                $(this).css('color', 'aqua');

                testStr = thisField+', ';
                hasComma = select.indexOf(testStr);
                if(hasComma > 0){
                    newSelect = select.replace(testStr, '');
                }
                else{
                    newSelect = select.replace(thisField, '');
                }
                if(newSelect == 'SELECT '){
                    select = '';
                }
                else{
                    select = newSelect;
                }
            }

            extraComma = select.indexOf(', ,');
            if(extraComma > 0){
                newSelect = select.replace(', ,', ',');
                select = newSelect;
            }

            $('.select_fields').text(select);
        }

        if(scope == 'WHERE'){
            if(bg_color == 'rgb(25, 25, 112)'){
                // Field SELECTED:
                if(whereLength == 0){
                    msgStr = 'Select criteria (=, !=, >, <, LIKE, NOT LIKE) for '+
                        thisField+' should look similiar to: '+thisField+' = "John "';
                }
                else{
                // Field UNSELECTED:
                    var tempWhere = $('.where_fields').text();

                    msgStr = 'You will need to add both criteria (=, !=, >, <, LIKE, NOT LIKE)'+
                        ' for '+thisField+' as well as an AND, OR clause so it will look'+
                        ' something like: '+tempWhere+'\nAND '+thisField+' LIKE "some value"';
                }

                var criteria = prompt(msgStr, thisField);
                if(criteria == null || criteria == ''){
                    return;
                }
                else{
                    $(this).css('background-color','aqua');
                    $(this).css('color','midnightblue');

                    if(whereLength == 0){
                        where = 'WHERE '+criteria;
                    }
                    else{
                        where = where +' '+criteria;
                    }
                }
            }
            else{
                $(this).css('background-color','midnightblue');
                $(this).css('color','aqua');

                testStr = thisField+', ';
                hasComma = where.indexOf(testStr);
                if(hasComma > 0){
                    newWhere = where.replace(testStr, '');
                }
                else{
                    newWhere = where.replace(thisField, '');
                }
                if(newWhere == 'WHERE '){
                    where = '';
                }
                else{
                    where = newWhere;
                }
            }
            $('.where_fields').html(where);
        }


        if(scope == 'ORDER'){
            if(bg_color == 'rgb(0, 128, 0)'){
            // Field SELECTED:
                $(this).css('background-color','aqua');
                $(this).css('color','green');

                if(orderByLength == 0){
                    order_by = 'ORDER BY '+thisField;
                }
                else{
                    order_by = order_by+', '+thisField;
                }
            }
            else{
            // Field UNSELECTED:
                $(this).css('background-color','green');
                $(this).css('color','aqua');

                testStr = ', '+thisField;
                hasComma = order_by.indexOf(testStr);
                if(hasComma > 0){
                    newOrderBy = order_by.replace(testStr, '');
                }
                else{
                    newOrderBy = order_by.replace(thisField, '');
                }
                if(newOrderBy == 'ORDER BY '){
                    order_by = '';
                }
                else{
                    order_by = newOrderBy;
                }
            }

            var lonelyComma = order_by.indexOf(' , ');
            if(lonelyComma > 0){
                newOrderBy = order_by.replace(' , ', ' ');
                order_by = newOrderBy;
            }

            $('.order_by_fields').text(order_by);
        }
        LoadQuery();
    });
    /* -------------- END Query Builder ------------------ */
    /*************** Editor sizing features ****************/
    $(document).on('blur', '.editor_height_text', function(){
        var editorHeight = $(this).val()+'px';
        $('.editor_wrapper').css('height', editorHeight);
    });

    $(document).on('blur', '.editor_width_text', function(){
        var thisWidth = $(this).val();
        if(parseInt(thisWidth) > 1275){
            alert('Maximum width is 1275px');
            $('.editor_width_text').val('1275');
            thisWidth = 1275;
        }

        if(parseInt(thisWidth) < 360){
            alert('360px is as narrow as allowed without lising the Editor');
            thisWidth = 360;
            $('.editor_width_text').val(360);
            $('.shrink_editor').hide();
            $('.expand_editor').show();
        }

        var editorWidth = thisWidth + 'px';
        var editorHeaderWidth = parseInt(thisWidth) + 4;
        editorHeaderWidth = editorHeaderWidth + 'px';
        $('.editor_wrapper').css('width', editorWidth);
        $('.editor_header_wrapper').css('width', editorHeaderWidth);
    });

    $(document).on('click', '.shrink_editor', function(){
        $('.editor_wrapper').hide();
        $('.editor_sizing').hide();
        $('.editor_header_wrapper').css('width', '68px');
        $('.shrink_editor').hide();
        $('.expand_editor').show();
        $('.editor-div').css('height', '35px');
        $('.editor-div').css('width', '72px');
    });
    $(document).on('click', '.expand_editor', function(){
        $('.editor_wrapper').show();
        $('.shrink_editor').show();
        $('.editor_sizing').show();
        $('.expand_editor').hide();

        var editorWidth = $('.editor_width_text').val();
        var editorHeight = $('.editor_height_text').val();
        var editorHeaderWidth = parseInt(editorWidth) + 4;

        editorWidth = editorWidth + 'px';
        editorHeaderWidth = editorHeaderWidth + 'px';
        $('.editor_wrapper').css('width', editorWidth);
        $('.editor_header_wrapper').css('width', editorHeaderWidth);
    });

    $(document).on('blur', '.history_height_text', function(){
        var historyHeight = $(this).val()+'px';
        $('.history_wrapper').css('height', historyHeight);
    });

    $(document).on('blur', '.history_width_text', function(){
        var thisWidth = $(this).val();
        if(parseInt(thisWidth) > 1275){
            alert('Maximum width is 1275px');
            $('.history_width_text').val('1275');
            thisWidth = 1275;
        }

        var historyWidth = thisWidth + 'px';
        var historyHeaderWidth = parseInt(thisWidth) + 4;
        historyHeaderWidth = historyHeaderWidth + 'px';
        $('.history_wrapper').css('width', historyWidth);
        $('.history_header_wrapper').css('width', historyHeaderWidth);
    });

    $(document).on('click', '.shrink_history', function(){
        $('.history_wrapper').hide();
        $('.history_sizing').hide();
        $('.history_header_wrapper').css('width', '68px');
        $('.shrink_history').hide();
        $('.expand_history').show();
        $('.hist-div').css('height', '35px');
        $('.hist-div').css('width', '72px');
    });

    $(document).on('click', '.expand_history', function (){
        $('.history_wrapper').show();
        $('.shrink_history').show();
        $('.history_sizing').show();
        $('.expand_history').hide();

        var historyWidth = $('.editor_width_text').val();
        var historyHeight = $('.editor_height_text').val();
        var historyHeaderWidth = parseInt(historyWidth) + 4;

        historyWidth = historyWidth + 'px';
        historyHeaderWidth = historyHeaderWidth + 'px';
        $('.history_wrapper').css('width', historyWidth);
        $('.history_header_wrapper').css('width', historyHeaderWidth);
    });
    /*************** End Editor sizing features *************/
    /**************** Stored Procedures Tab *****************/
    /*    -- Build Procedure is at line: 407 --    */
    /* nend is short for NO EDIT NO DELETE         */
    $(document).on('click', '.builtin_display', function(){
        var display = $(this).attr('id');
        if(display == 'Hide'){
            $(this).html('Show Built-in');
            $(this).attr('id', 'Show');
            $('.display_status').attr('id', 'Hide');
        }

        if(display == 'Show'){
            $(this).html('Hide Built-in');
            $(this).attr('id', 'Hide');
            $('.display_status').attr('id', 'Show');
        }

        SimpleCall('displayProcedures', display, '.stored_procedures');
    });

    $(document).on('click', '.test_sp_submit_a', function(){
        var procedure = $(this).attr('id');
        var formValues = '#' + procedure + ' input';
        var procValues = '';
        var i = 0;

        $(formValues).each(function(index){
            var input = $(this);
            if(i == 0){
                procValues = procValues + input.val();
            }
            else{
                procValues = procValues + '^~#!#~^' + input.val();
            }
            i = i + 1;
        });

        $('.stored_procedures').hide();
        $('.results-div').show();
        $('.sp_show_procedures').show();
        $('.sp_show_output').hide();

        SimpleCall('DisplayProcedureTest', procValues, '.results-div');
    });

    $('#formId input, #formId select').each(function(index){
            var input = $(this);
            alert('Type: ' + input.attr('type') + 'Name: ' + input.attr('name') + 'Value: ' + input.val());
    });

    $(document).on('click', '.test_sp_a', function(){
        var procedure = $(this).attr('id');
        SimpleCall('DisplayTestForm', procedure, '.alert-div');
    });

    $(document).on('click', '.sp_log', function(){
        $('.stored_procedures').hide();
        $('.results-div').show();
        $('.sp_show_procedures').show();
        $('.sp_show_output').hide();
        $('.alert-div').html('Stored Procedure History:');
        SimpleCall('displayProcLog', '', '.results-div');
    });

    $(document).on('click', '.sp_drop_a', function(){
        var sp = $(this).attr('id');
        SimpleCall('dropProcedure', sp, '.alert-div');
        setTimeout(LoadProcedures, timeDelay);
    });

    $(document).on('click', '.sp_show_output', function(){
        $('.stored_procedures').hide();
        $('.results-div').show();
        $('.sp_show_procedures').show();
        $('.sp_show_output').hide();
    });

    $(document).on('click', '.sp_show_procedures', function(){
        $('.stored_procedures').show();
        $('.results-div').hide();
        $('a.sp_show_procedures').hide();
        $('.sp_show_output').show();
    });

    $(document).on('click', '.sp_blank', function(){
        $('.stored_procedures').show();
        $('.results-div').hide();
        var blank = $(this).attr('id');

        LoadEditor('getBlank', blank);
    });

    $(document).on('click', '.sp_name_a', function(){
        var sp = $(this).attr('id');

        LoadEditor('loadSP', sp);
    });

    $(document).on('click', '.nend_a', function(){
        var sp = $(this).attr('id');
        LoadEditor('DisplayProtected', sp);
    });
    /*********** End Stored Procedures Tab **************/
    /****************** Query Tools Tab *****************/
    $(document).on('click', '.text_data_pre', function(){
        $(this).select();
    });

    $(document).on('click', '.history-td', function(){
        $('.select_table_div').hide();
        $('.builder_tables').hide();
        $('.builder_select').hide();
        $('.builder_table').hide();
        $('.results-div').show();
        $('.alert-div').show();

        var queryText = $(this).html();
        $('.alert-div').html('<pre class="text_data_pre">'+queryText+'</pre>');
        SimpleCall('runSQL', queryText, '.results-div');
        setTimeout(LoadHistory, timeDelay);
    });

    $(document).on('click', '.remove-sql-td', function(){
        var queryText = $(this).attr("id");
        SimpleCall('removeSQL', queryText, '.alert-div');
        setTimeout(LoadHistory, timeDelay);
    });

    $(document).on('click', '.get-query', function(){
        var queryText = $('.query-text').val();
        $(".alert-div").html('<pre>' + queryText + '</pre>');
        SimpleCall('runSQL', queryText, '.results-div');
        setTimeout(LoadHistory, timeDelay);
    });

    // CTRL+ENTER to execute and display SQL results:
    window.addEventListener('keydown', function(e) {
        if(e.keyCode == 13 && e.ctrlKey) {
		    var queryText = myEditor.codemirror.getValue();

            // Stored procedures cannot build through Stored Procedure:
            var protected = queryText.indexOf('# THIS');
            var procedure = queryText.indexOf('ATE PROCEDURE');

            if(protected > 0){// Check for protected stored procedure first:
                $('.stored_procedures').show();
                $('.results-div').hide();
                $('.alert-div').html('<b class="red-font">This is a built-in '+
                    'Stored Procedure and will not be updated.</b>');
                return;
            }

            if(procedure > 0){// Check for stored procedure build next:
                $('.stored_procedures').show();
                $('.results-div').hide();
                SimpleCall('buildProcedure', queryText, '.alert-div');
                setTimeout(LoadProcedures, timeDelay);
                return;
            }

            // If not protected or a CREATE PROCEDURE
            $('.alert-div').html('<pre class="text_data_pre">'+queryText+'</pre>');

            if(thisTab == 'Stored Procedures'){
                $('.stored_procedures').hide();
                $('.sp_show_procedures').show();
                $('.results-div').show();
            }

            SimpleCall('runSQL', queryText, '.results-div');
            if(thisTab === "Query Tool"){
                $('.builder_select').hide();
                $('.note').hide();
                $('.alert-div').show();
                $('.builder_table').hide();
                $('.results-div').show();

                setTimeout(LoadHistory, timeDelay);
            }
	    }
    });

    function LoadHistory(){
        SimpleCall('displaySQLHistory', 'isSet', '.history_wrapper');
    }

    function LoadProcedures(){
        SimpleCall('displayProcedures', '', '.stored_procedures');
    }

    function SimpleCall(action, dataValue, destTag){
        var display = $('.display_status').attr('id');
        var data = {
            'action': action,
            'fromQuery': 'isSet',   // For Query Tool Only
            'display': display,
            'queryText': dataValue
        }

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: data,
            dataType: 'text',
            success: function(response){
                // Scrubbing various $wpdb text responses:
                var message = response.replace('CREATED.0', 'CREATED.');
                message = message.replace('ted.0', 'ted.');
                // Weird 0 from wp_die() where it can't be used
                // without breaking myEditor.CodeMirror:
                message = message.replace('"0"','');
                $(destTag).html(message);
            },
            error: function(response){
                $(destTag).html('jQuery SimpleCall Response Error: '+response.error);
            }
        });
    }
    /*********** Wordpress CodeMirror Editor: *************/
    function LoadEditor(ajaxAction, dataValue){
        $('.working_on').html(dataValue);

        var data = {
            'action': ajaxAction,
            'queryText': dataValue
        }

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: data,
            dataType: 'text',
            success: function(response){
                $thisStr = response.replace('END0', 'END');
                myEditor.codemirror.setValue($thisStr);
            },
            error: function(response){
                myEditor.codemirror.setValue('Load Editor Error: '+response.error);
            }
        });
    }

    var myEditor = wp.codeEditor.initialize($('.query-text'), {
        lineNumbers: 'true' // This actually doesn't do anything
    });
    myEditor.codemirror.setSize(1200, 1200);
    /******************* END CodeMirror  ******************/
});
