<table><tr><td><div class="instr_content_div">
<h1 class="instructions_home"><img class="query-tool"
    src="/wp-content/plugins/wpdb-power-tool/assets/images/Racoon.webp"
    style="float:left;" height="40"
    width="40">WPDB Instructions and Important information</h1><b>WPDB Power Tool</b>
    <h4 class="has-text-align-center">WPDB Power Tool Instructions</h4>
    <h3 class="has-text-align-center red-font">
        <span style="color:#ff0000" class="has-inline-color"><strong>WARNING!</strong></span></h3>



    <h6 class="alignwide"><span style="color:#ffff00" class="has-inline-color">This tools is not a backup solution, a disaster recovery plan nor a means to secure your data. There are other tools for that type of application. So BE ADVISED! THIS TOOL IS NOT MEANT FOR BACKUP OR DISASTER RECOVERY PURPOSES! ONCE YOU DROP A TABLE THE SECOND TIME IT IS GONE FOREVER UNLESS YOU BUILD A NEW ONE FROM SCRATCH!</span></h6>



    <h6 class="alignwide"><span style="color:#ffff00" class="has-inline-color">The WPDB Tables Tab is for viewing, backing up and sampling table data. In addition to this it is for seeing the table structure and drop/delete tables no longer needed. So be careful! Mechanisms are in place to give you every chance to avoid deleting/removing a table you may need later on. The purpose of this tab is to have a quick way to view and see what the tables are storing as well as to see how they are constructed.</span></h6>



    <div class="wp-block-getwid-tabs alignwide alignwide" data-active-tab=""><ul class="wp-block-getwid-tabs__nav-links"></ul>
    <div class="wp-block-getwid-tabs__nav-link"><span class="wp-block-getwid-tabs__title-wrapper"><a href="#"><span class="wp-block-getwid-tabs__title"><span style="color:#65ffd8" class="has-inline-color">WPDB Tables Tab</span></span></a></span></div><div class="wp-block-getwid-tabs__tab-content-wrapper"><div class="wp-block-getwid-tabs__tab-content">
    <h3 class="has-text-align-center" id="WPDB_Tables">WPDB Power Tool Instructions for the WPDB Tables tab</h3>



    <p class="content-p">Purpose: Display all tables in the WordPress site database, backup tables, restore backed up tables and drop unneeded tables.</p>



    <p class="content-p">Functionality: Displays number of rows in tables and size of table. By clicking on the table name the table structure is displayed. By clicking on the rows the most recent 500 records are display on all tables using the first column as a primary key or index field. </p>



    <p class="content-p"><b><span class="tables_label white-bg">Table View Options</span>: </b>You can select the tables you want to view by selecting the option above the table. All tables, only tables without data and only tables with data.<br></p>



    <p class="content-p"><span class="desc-td desc-a"><b>Table Name</b></span>: By clicking on the table name the table structure will display in the upper right showing the Field name, type, allowed NULL, is a Key (primary or otherwise), the default value and anything extra.<br></p>



    <p class="content-p"><span class="show-td show-a">Row Count</span>: By clicking on the row count you will see a sample of the tables data restricted to 500 rows. The rows are typically ordered by the last 500 rows descending, depending on what the first field is. The purpose of this tab is to give you insight into the tables data, not to present all the data. That is what the <a class="instruction-a" href="https://advalgo.com/wp-admin/admin.php?page=tabbed_wpdb&amp;tab=wpdbQueryTool">Query Tool.</a> is for.  You will be able to slice and dice your data in any way imaginable with the Query Tool.</p>



    <p class="content-p"><span class="backup-td backup-a">BACKUP</span>: When you click on backup, a backup table with the data in the current table is created. Some important table structure is lost here (like auto increment, primary keys, special fields). This is simply a store for the data in the tables present state. Once you have made a backup of the table the option to restore the backup table displays as a restore table. Hit <span class="backup-td backup-a">BACKUP</span> again the last backup table you made is TRUNCATED and the backup table is loaded anew from the current tables state of data. So, in other words, at the moment you do the <span class="backup-td backup-a">BACKUP</span> you have a mirror of that table.</p>



    <p class="content-p"><span class="restore-td restore-a">RESTORE</span>: When you click on restore, the table you backed up is restored to the exact same state it was in when you backed it up. The process is TRUNCATE Table (this is a fast way of deleting all rows) then SELECT INTO the table. This ensures any primary keys and foreign keys are not lost and any AUTO INCREMENT is left alone in the state of the last insert. This tool is more useful for development and testing. Less so for creating a backup as a disaster recovery plan which this tool was not designed for.</p>



    <p class="content-p"><span class="drop-td drop-a">DROP</span>: The DROP process actually makes a copy of the CREATE TABLE sql as well as the data and still creates a backup. The original table is dropped from the mix. Removed, deleted, and no longer exists. The backup table has the text wpdrop in the table name (i.e. if the table were wp_posts the new table would be wp_wpdrop_posts). If you drop this table there is no recovery for that table or the data that was in it. So be certain you want to drop this if you click drop on the dropped table.</p>



    <p class="content-p"><span class="restore-drop-td">RESTORE</span> This option restores a dropped table to the state it was in in when it was originally dropped. Restoring all primary and foreign keys as well. It is then taken out of the dropped status and the <span class="restore-drop-td">RESTORE</span> <span class="final-drop-td">DROP</span> row is removed from the table list.</p>



    <p class="content-p"><span class="final-drop-td">DROP</span>: This is the final removal of the table. Once this is clicked on there is no easy recovery of this table. You will need to get the table back from a database backup if you have one available.</p>
    </div></div>



    <div class="wp-block-getwid-tabs__nav-link"><span class="wp-block-getwid-tabs__title-wrapper"><a href="#"><span class="wp-block-getwid-tabs__title"><span style="color:#65ffd8" class="has-inline-color">Query Tool Tab</span></span></a></span></div><div class="wp-block-getwid-tabs__tab-content-wrapper"><div class="wp-block-getwid-tabs__tab-content">
    <h3 class="has-text-align-center" id="Query_Tool">WPDB Power Tool Instructions for the Query Tool Tab</h3>



    <p class="content-p">The <span style="color:#00ff15" class="has-inline-color">Query Tool Tab</span> provides the ability to virtually run an SQL statement to use for a WordPress site and more.<br>There are two tools. The <span style="color:#00ff15" class="has-inline-color">Query Editor</span> which is built on the WordPress code editor (in fact is the WordPress editor) using the WordPress SQL styling. The second tool is the <span style="color:#00ff15" class="has-inline-color">SQL History</span> tool. This tracks all of your SQL queries so you can reuse them with a simple click. You can also easily delete them as you see fit.<br><br>To use the query tool in the editor type your query. For example, type <span style="color:#05ffc5" class="has-inline-color">SELECT DATABASE();</span> then press the Control and Enter key at the same time and you should see two things happen here. In the display pane you should see the name of your WordPress database appear under the field name <strong>DATABASE()</strong> on the right side of your screen. Then under the Query Editor in the SQL History pane your query should be displayed. Now let’s do another query. Type <span style="color:#05ffc5" class="has-inline-color">SHOW TABLES;</span> then press Control Enter. Now you should see a full list of the tables in your WordPress database. One of those tables should have the word <span style="color:#05ffc5" class="has-inline-color">posts</span> in it. Double click on that table so that it is highlighted then press Control C (to copy the table name). Click in the Query Editor and type <span style="color:#05ffc5" class="has-inline-color">SELECT * FROM</span> (and a space) and press Control and the V key to paste the table name and press Control Enter. Now you should see the contents of the WordPress posts table as well as the <span style="color:#05ffc5" class="has-inline-color">SELECT</span> query you just ran added to your <span style="color:#00ff15" class="has-inline-color">SQL History</span> pane. These are the pages and posts on your website.</p>



    <p class="content-p">In the <span style="color:#00ff15" class="has-inline-color">Query Editor</span> and the <span style="color:#00ff15" class="has-inline-color">SQL History</span> panes you can Shrink the pane and expand the pane. You can also change the height and width of these panes and move them to wherever you like.</p>



    <p class="content-p">In the <span style="color:#00ff15" class="has-inline-color">SQL History</span> pane you can rerun your SQL by clicking on the SQL statement as well as delete the SQL by clicking on the <span style="color:#fa6a6a" class="has-inline-color">Red SQL ID number</span>. </p>
    </div></div>



    <div class="wp-block-getwid-tabs__nav-link"><span class="wp-block-getwid-tabs__title-wrapper"><a href="#"><span class="wp-block-getwid-tabs__title"><span style="color:#65ffd8" class="has-inline-color">Stored Procedures Tab</span></span></a></span></div><div class="wp-block-getwid-tabs__tab-content-wrapper"><div class="wp-block-getwid-tabs__tab-content">
    <h3 class="has-text-align-center">WPDB Power Tool Instructions for the Stored Procedures Tab</h3>



    <p class="content-p">The <span style="color:#00ff15" class="has-inline-color">Stored Procedure</span> tab is to make developing and testing <span style="color:#00ff15" class="has-inline-color">Stored Procedures</span> with as little difficulty as possible. It has three main tools to achieve this. The <span style="color:#00ff15" class="has-inline-color">Stored Procedure Editor</span> which is the same WordPress Editor as used on the Query Tool tab except sized for Stored Procedure editing. The second tool is the <span style="color:#00ff15" class="has-inline-color">Stored Procedure log</span> that displays when clicking on the <strong><span style="color:#fc44fc" class="has-inline-color">View Log</span></strong> which tracks only successful <span style="color:#05ffc5" class="has-inline-color">CREATE</span>, <span style="color:#05ffc5" class="has-inline-color">UPDATE</span> and <span style="color:#05ffc5" class="has-inline-color">DROP</span> queries. It tracks all the successful queries in order that in the event of a failed <span style="color:#05ffc5" class="has-inline-color">CREATE</span> you can recover the previous stored procedure. You can think of it as a poor man’s Stored Procedure version control system. The third tool is the <span style="color:#00ff15" class="has-inline-color">Stored Procedure Tester</span>. In time, this feature will grow to have more advanced features however, at present it loads the stored procedure parameters for you then you can enter them to ensure it is working correctly.  In order to really see how to use the Stored Procedure tool go through the <a href="/building-a-stored-procedure/" target="_blank" rel="noreferrer noopener">Building a Stored Procedure</a> tutorial followed by the <a href="/testing-a-stored-procedure/" target="_blank" rel="noreferrer noopener">Testing a Stored Procedure</a> tutorial.</p>
    </div></div>
    </div>
