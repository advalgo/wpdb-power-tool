jQuery(document).ready(function($) {
    // Initialize values:
    var thisTab = $('.nav-tab-active').text();
    document.title = thisTab;
    
    // For additional AJAX Calls:
    var timeDelay = 1000;
    // Instructions Tab ->>>>>---------->
    $(document).on('click', '.wpdb_tables_instructions', function(){
        $('.introduction_content').hide();
        $('.query_tool_content').hide();
        $('.stored_procedure_content').hide();
        $('.wpdb_tables_content').show();
    });

    $(document).on('click', '.query_tool_instructions', function(){
        $('.introduction_content').hide();
        $('.stored_procedure_content').hide();
        $('.wpdb_tables_content').hide();
        $('.query_tool_content').show();
    });

    $(document).on('click', '.stored_procedures_instructions', function(){
        $('.introduction_content').hide();
        $('.wpdb_tables_content').hide();
        $('.query_tool_content').hide();
        $('.stored_procedure_content').show();
    });
    /* <---<<<<<- End Instructions tab */
    // Support Tab: ->>>>>------------->>


    /* <<-------<<<<<- End Support Tab */
    /************** WPDB Tables Tab **************/
    $(document).on('click','.final-drop-a', function(){
        var dropTable = $(this).attr('id');
        var data = {
            'action': 'finalDrop',
            'dropTable': dropTable
        }

        // Last chance to bail:
        var sure = confirm('ARE YOU SURE YOU WANT TO DO THIS?'+
            ' THE TABLE '+ dropTable.replace(/wpdrop_/g, '') +
            ' WILL BE PERMANENTLY LOST?');

        if(sure != true) return;

        SimpleCall('finalDrop', dropTable, '.table-righttop');
        setTimeout(LoadTables, timeDelay);
    });

    $(document).on('click', '.restore-drop-a', function(){
        var restoreDropTable = $(this).attr('id');
        SimpleCall('restoreDrop', restoreDropTable, '.table-righttop');
        setTimeout(LoadTables, timeDelay);
    });
    // First Drop:
    $(document).on('click', '.drop-a', function(){
        var dropTable = $(this).attr('id');
        SimpleCall('firstDrop', dropTable, '.table-righttop');
        setTimeout(LoadTables, timeDelay);
    });
    // Backup Table:
    $(document).on('click', '.backup-a', function(){
        var table = $(this).attr('id');
        SimpleCall('backupTable', table, '.table-righttop');
        setTimeout(LoadTables, timeDelay);
    });
    // Describe Table:
    $(document).on('click', '.desc-a', function(){
        var table = $(this).attr('id');
        SimpleCall('descTable', table, '.table-righttop');
    });

    $(document).on('click', '.restore-a', function(){
        var backupTable = $(this).attr('id');
        SimpleCall('restoreTable', backupTable, '.table-righttop');
        setTimeout(LoadTables, timeDelay);
    });
    // Show tables selected (ALL, ROWS or EMPTY):
    $(document).on('click', '.tables_to_show', function (){
        var choice = '';

        $("input[type='radio'][name='tables_to_show']").each(function(){
            if($(this).is(":checked")){
                choice = $(this).val();
            }
        });
        SimpleCall('ShowTables', choice, '.table-leftcolumn');
    });
    // Show 500 rows of Table sorted DESC:
    $(document).on('click', '.show-a', function(){
        var queryText = $(this).attr('id');
        SimpleCall('showTable', queryText, '.table-rightbottom');
    });
    //////////////////////////////////////////////////////
    /*** Special functions: */
    function LoadTables(){
        var queryText = '';

        $("input[type='radio'][name='tables_to_show']").each(function(){
            if($(this).is(":checked")){
                queryText = $(this).val();
            }
        });

        if((queryText == undefined) || (queryText == '')) queryText = 'ALL';
        SimpleCall('ShowTables', queryText, '.table-leftcolumn');
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
                // without breaking myEditor.CodeMirror:
                $(destTag).html(response);
            },
            error: function(response){
                $(destTag).html('jQuery SimpleCall Response Error: '+response.error+
                    ' action: '+' data: '+dataValue);
            }
        });
    }
});
