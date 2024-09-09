<?php

?>
<h1 class="seagreen-font center-text">Select Options then file</h1>
    <form name="csv2table_form" class="csv2table_form center-text" id="csv2table_form">
    <table class="csv2table_table">
        <tr class="csv2table_tr">
            <td class="text-l">
                <input type="checkbox" name="fieldnames_firstrow" checked>
                Field names in first row (if not in first row fields will be named field_0, field_1, field_2 etc.)</td>
        </tr>
        <tr class="csv2table_tr">
            <td class="text-l">
                <b class="green-font">Field Delimiter:</b><br />
                <input type="radio" name="field_delimiter" id="comma" value="comma" checked>Comma Separated ( , )<br />
                <input type="radio" name="field_delimiter" id="semicolon" value="semicolon">Semi Colon Separated ( ; )<br />
                <b class="green-font">String Separator:</b><br />
                <input type="radio" name="string_separator" value="none" checked>None<br />
                <input type="radio" name="string_separator" value="sq">Single Quote/Tick ( ' )<br />
                <input type="radio" name="string_separator" value="dq">Double Quote ( " )<br />
                <input type="radio" name="string_separator" value="custom">Custom:
                <input type="text" name="custom_string_separator" size="1"><br />
                <b class="green-font">Table Name (WordPress Prefix will be added):</b><br />
                <input type="checkbox" class="use_csv_file_name" name="use_csv_file_name">Use CSV File Name<br />
                <div class="table_name">Table Name:<input type="text" name="table_name" class="table_name_text" size="20"></div>
            </td>
        </tr>
    </table>
    </form>
    <form method="post" enctype="multipart/form-data" id="upload_csv_form">
        <input type="hidden" name="" Select CSV:<input type="file" name="csv_file" id="csv_file">
        <input type="button" class="upload_csv_button" value="Upload CSV File">
    </form>
</form>
