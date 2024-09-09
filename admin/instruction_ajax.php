<?php
    add_action('wp_ajax_WPDBTablesInstructions', 'WPDBTablesInstructions_response');
    add_action('wp_ajax_QueryToolInstructions', 'QueryToolInstructions_response');
    add_action('wp_ajax_BuildProcedureLesson', 'BuildProcedureLesson_response');
    add_action('wp_ajax_TestProcedureLesson', 'TestProcedureLesson_response');

    function WPDBTablesInstructions_response(){
        GetPage('https://wpdbpowertool.com/remote-instruction/');
    }

    function QueryToolInstructions_response(){
        GetPage('https://wpdbpowertool.com/query-tool-instructions/');
    }

    function BuildProcedureLesson_response(){
        GetPage('https://wpdbpowertool.com/build-procedure/');
    }

    function TestProcedureLesson_response(){
        GetPage('https://wpdbpowertool.com/test-procedure/');
    }

    function GetPage($page){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $page);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($ch);

        echo $page;
        wp_die();
    }
?>
