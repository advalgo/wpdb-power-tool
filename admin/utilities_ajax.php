<?php
    /* Contants for utilities_ajax.php app:
     * WPDBPT_POWERTOOL_BACKUPS_DIR is full directory from root of server
     * WPDBPT_POWERTOOL_BACKUPS_URI is path for webserver (ie  /-wp-content/plugins/wpdbpowertool/backups
     * WPDBPT_POWERTOOL_INCLUDES Directory
     */
    /* Backups Methods: ->>>>>--------------->> */
    add_action( 'wp_ajax_DisplayBackups', 'DisplayBackups_response' );
    add_action( 'wp_ajax_CreateBackup', 'CreateBackup_response' );
    add_action( 'wp_ajax_RestoreBackup', 'RestoreBackup_response' );
    add_action( 'wp_ajax_DeleteBackup', 'DeleteBackup_response' );
    /* <<----------------<<<<<- Backups Methods */
    /* CSV2Table Methods: ->>>>-------------->> */
    add_action( 'wp_ajax_CSV2Table', 'CSV2Table_response' );
    /* <<---------------<<<<- CSV2Table Methods */

    function CSV2Table_response(){
        $csv2tablePage = WPDBPT_POWERTOOL_INCLUDES . '/utilities_csv2table.php';
        //echo $csv2tablePage;
        include($csv2tablePage);

        wp_die();
    }

    function DeleteBackup_response(){
        $deleteFile = WPDBPT_POWERTOOL_BACKUPS_DIR . '/' . $_REQUEST['queryText'];
        unlink($deleteFile);

        echo $_REQUEST['queryText'] . ' <b>Deleted.</b><br />';
        DisplayBackups_response();
        wp_die();
    }

    function CreateBackup_response(){
        $tempDate = GetTimeNow();
        $tempDate = str_replace(' ', '.', $tempDate);
        $tempDate = str_replace(':','', $tempDate);
        $cryptKey = generateRandomString(10);
        $sqlFile = DB_NAME . '.' . $tempDate . '.' . $cryptKey . '.sql';
        $backupFile = WPDBPT_POWERTOOL_BACKUPS_DIR . '/' . $sqlFile;

        BackupDatabase($backupFile);
        DisplayBackups_response();
        wp_die();
    }

    function RestoreBackup_response(){
        $restoreFile = WPDBPT_POWERTOOL_BACKUPS_DIR . '/' . $_REQUEST['queryText'];

        RestoreBackup($restoreFile);
        DisplayBackups_response();
        wp_die();
    }

    function DisplayBackups_response(){
        $backupPath = WPDBPT_POWERTOOL_BACKUPS_DIR;
        $tempDate = GetTimeNow();
        $tempDate = str_replace(' ', '.', $tempDate);
        $tempDate = str_replace(':','', $tempDate);
        $cryptKey = generateRandomString(10);
        $sqlFile = DB_NAME . '.' . $tempDate . '.' . $cryptKey . '.sql';
        $backupFile = WPDBPT_POWERTOOL_BACKUPS_DIR . '/' . $sqlFile;

        if(is_dir($backupPath)){
            echo '<h3>Available Backups</h3>';
            echo '<p>It is best not to keep these files here for any length of time as if you know the path
            you can download these files if you can guess the second and the randome key (which will take
            68x10 to the 18th power number of possibilitites to guess). A Hacker could attempt to query the path
            where the file is stored once they know the file format and the second the file was created.</p>';
        }
        else{
            echo 'No Backups present - creating folder ' . $backupPath . ' now.<br />';
            mkdir($backupPath, 0777, true) or die('Failed to create backup directory.');
            $indexFile = fopen($backupPath . "/index.php", "w");
            $txt = '<?php // Is it quite here?';
            fwrite($indexFile, $txt);
            fclose($indexFile);

            echo 'Backup Directory Created.<br />';
            echo 'Creating first backup <b class="blu-font">' . $backupFile . '</b><br />';

            BackupDatabase($backupFile);
        }

        $backupFiles = GetBackupByDateOrder($backupPath);
        $i = 1;
        echo '<table class="backups_table">';
        foreach ($backupFiles as $fileName) {
            $fileSize = GetFileSize($backupPath . '/' . $fileName);
            echo '<tr class="backups_tr">
                <td class="filename_td" id="' . $fileName . '">
                    <a href="' . WPDBPT_POWERTOOL_BACKUPS_URI . '/' . $fileName . '">' . $fileName . '</a>
                </td>
                <td class="backups_td">Size ' . $fileSize . '</td>
                <td class="restore_td"  id="' . $fileName . '"><a class="restore_a" id="' . $fileName . '">Restore Backup</a></td>
                <td class="delete_td"><a class="delete_a" id="' . $fileName . '">Delete Backup</td>
                </tr>';
            $i = $i + 1;
        }
        echo '<tr class="backups_tr"><td class="backups_td" colspan="4"><a class="create_a">Create Backup</a></td>';
        echo '</table>';
        wp_die();
    }

    function generateRandomString($length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function GetFileSize($file){
        $filesize = filesize($file);

        if($filesize > 0 && $filesize < 1024){
            $filesize = $filesize . ' Bytes';
        }

        if($filesize >= 1024 && $filesize < 1048576){
            $filesize = round($filesize / 1024, 2);
            $filesize = $filesize . ' KB';
        }

        if($filesize >= 1048576 && $filesize < 1073741824){
            $filesize = round($filesize / 1048576, 2);
            $filesize = $filesize . ' MB';
        }

        if($filesize >= 1073741824){
            $filesize = round($filesize / 1073741824, 2);
            $filesize = $filesize . ' GB';
        }
        return $filesize;
    }

    function GetBackupByDateOrder($backupDirectory){
        $fileList = [];
        chdir($backupDirectory);
        array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_DESC, $files);
        foreach($files as $filename){
            $isSql =  strpos($filename, '.sql');
            if($isSql > 0){
                $fileList[] = $filename;
            }
        }

        return $fileList;
    }

    function BackupDatabase($backupFile){
        $cmd = "mysqldump  --routines --comments -h" . DB_HOST . " -u" . DB_USER . " -p'" . DB_PASSWORD ."' " . DB_NAME ." > " . $backupFile;
        echo 'Running: ' . $cmd . '<br />';
        exec($cmd, $output, $worked);

        switch($worked){
            case 0:
                echo 'The database <b>' . DB_NAME .'</b> was successfully backed up to ' . $backupFile . '</b><br />';
                break;
            case 1:
                echo 'An error occurred when exporting <b>' . DB_NAME .'</b> to '. $backupFile .'</b><br />';
                break;
            case 2:
                echo 'An export error has occurred, please check the following information: <br /><br />
                    <table>
                        <tr><td>MySQL Database Name:</td><td><b>' . DB_NAME .'</b></td></tr>
                        <tr><td>MySQL User Name:</td><td><b>' . DB_USER .'</b></td></tr>
                        <tr><td>MySQL Password:</td><td><b>' . DB_PASSWORD . '</b></td></tr>
                        <tr><td>MySQL Host Name:</td><td><b>' . DB_HOST .'</b></td></tr>
                    </table>';
                break;
        }
    }

    function RestoreBackup($backupFile){
        $worked = '';
        $cmd = "mysql -h" . DB_HOST . " -u " . DB_USER . " -p'" . DB_PASSWORD . "' " . DB_NAME . " < " . $backupFile;
        echo 'Running:<br />' . $cmd . '<br />';
        //exec($cmd, $output, $worked);

        switch($worked){
            case 0:
                echo 'The data from the file <b>' . $backupFile .
                    '</b> were successfully imported into the database <b>' . $backupFile .'</b>';
                break;
            case 1:
                echo 'An error occurred during the import. Please check if the file is in the same folder
                as this script. Also check the following data again:<br/><br/>
                <table>
                    <tr><td>MySQL Database Name:</td><td><b>' . DB_NAME .'</b></td></tr>
                    <tr><td>MySQL User Name:</td><td><b>' . DB_USER .'</b></td></tr>
                    <tr><td>MySQL Password:</td><td><b>' . DB_PASSWORD . '</b></td></tr>
                    <tr><td>MySQL Host Name:</td><td><b>' . DB_HOST .'</b></td></tr>
                    <tr><td>MySQL Import Dateiname:</td><td><b>' .$backupFile .'</b></td></tr>
                </table>';
                break;
            default:
                echo 'Testing';
        }
    }
?>
