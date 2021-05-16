jQuery(document).ready(function($) {
    $(document).on('click', '.restore_a', function(){
        restoreFile = $(this).attr('id');
        alert(restoreFile);
        
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
