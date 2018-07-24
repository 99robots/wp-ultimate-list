<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly   ?>
<?php

function wpulist_cleanData(&$str) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"'))
        $str = '"' . str_replace('"', '""', $str) . '"';
}

// filename for download
$filename = "website_data_sadf.xls";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

$flag = false;
$result = mysqli_query("SELECT * FROM wpf_wpulist_emails ORDER BY date_added") or die('Query failed!');
while (false !== ($row = pg_fetch_assoc($result))) {
    if (!$flag) {
        // display field/column names as first row
        echo implode("\t", array_keys($row)) . "\r\n";
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\wpulist_cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
}
exit;
?>