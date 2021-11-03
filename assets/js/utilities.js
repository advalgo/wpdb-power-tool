jQuery(document).ready(function($) {
    // Initialize values:
    var thisTab = $('.nav-tab-active').text();
    document.title = thisTab;
    
    /* Backup options: ->>>>>--------------->> */
    $(document).on('click', '.restore_a', function(){
        restoreFile = $(this).attr('id');
        SimpleCall('RestoreBackup', restoreFile, '.utilities_content');
    });

    $(document).on('click', '.delete_a', function(){
        deleteFile = $(this).attr('id');
        SimpleCall('DeleteBackup', deleteFile, '.utilities_content');
    });

    $(document).on('click', '.create_a', function(){
        SimpleCall('CreateBackup', '', '.utilities_content');
    });

    $(document).on('click', '#backups', function(){
        $('.utilities_item_header').html('<h1>Create, View, Restore, Delete Backups</h1>');
        SimpleCall('DisplayBackups','','.utilities_content');
    });
    /* <<----------------<<<<<- Backup Options */
    /* CSV2Table Tab: ->>>>>>--------------->> */
    $(document).on('click','#csv2table', function(){
        $('.utilities_item_header').html('<h1>Creates Table from Uploaded CSV File then loads data'+
            ' into Table</h1>')
        SimpleCall('CSV2Table','','.utilities_content');
    });

    $(document).on('click', '.use_csv_file_name', function(){
        var useFileName = $('#csv2table_form input[name="use_csv_file_name"]:checked').val();

        if(useFileName == undefined){
            $('.table_name').show();
        }
        else{
            $('.table_name').hide();
        }
    });

    $(document).on('click', '.upload_csv_button', function(){
        var firstRowName = $('#csv2table_form input[name="fieldnames_firstrow"]:checked').val();
        if(firstRowName == undefined){
            firstRowName = 'no';
        }
        var fieldDelimiter = $('#csv2table_form input[name="field_delimiter"]:checked').val();
        var stringSeparator = $('#csv2table_form input[name="string_separator"]:checked').val();
        var customStringSeparator = $('#csv2table_form input[name="custom_string_separator"]').val();;
        var tableName = $('#csv2table_form input[name="table_name"]').val();
        var useFileName = $('#csv2table_form input[name="use_csv_file_name"]:checked').val();
        if(useFileName == undefined){
            useFileName = 'no';
        }


        var formData = new FormData();
        var csvFile = $('#use_csv_file_name')[0].files[0];
        formData.append('csvFile', files);
        alert('firstRowName: '+ firstRowName + '\nfieldDelimiter: '+ fieldDelimiter+
            '\nstringSeparator: '+stringSeparator+'\ncustomStringSeparator: '+customStringSeparator+
            '\ntableName: '+tableName+'\nuserFileName: '+userFileName);


        var data = {
            'action': 'ProcessCSV';
            'formData': formData;
            'contentType': false;
            'processData': false;
        }


        return;
        /*.ajax({
            url: ajaxurl;
            type: 'post';
            data: data;
            success: function(response){

            }
        });*/
    });
    /* <<----------------<<<<<<- CSV2Table Tab */
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
                $(destTag).html(response);
            },
            error: function(response){
                $(destTag).html('jQuery SimpleCall Response Error: '+response.error+' on function: '+
                    action+' with values: '+dataValue);
            }
        });
    }
});
