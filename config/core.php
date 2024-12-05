<?php
    // set your default time-zone
    date_default_timezone_set('America/Los_Angeles');

    $page= isset($_GET["page"]) ? $_GET["page"] : 1;

    $records_per_page = 4;

    $from_record_num =($records_per_page*$page)-$records_per_page ;
?>