<div class="wrap">
    <h1>Contact Form Entries</h1>
    <hr />
    <table class="tg">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>
        </tr>
<?php
    global $wpdb;

    $result = $wpdb->get_results ( "SELECT * FROM wp_form_entries ORDER BY `date` DESC" );

    foreach ( $result as $entry ) { $db_time = strtotime($entry->date); $html_time = date('F j, Y, g:i a', $db_time);?>
        <tr>
            <th><?= $entry->name; ?></th>
            <th><?= $entry->email; ?></th>
            <th><?= $html_time; ?></th>
        </tr>
    <?php } ?>
    </table>

    <h3>Entries are ordered from newest to oldest.</h3>

</div>


<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
    .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
    .tg .tg-yw4l{vertical-align:top}
</style>

