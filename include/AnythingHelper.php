<?php
function genToken()
{
    return 't' . sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

date_default_timezone_set("Asia/Kolkata");
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime('tomorrow'));
$today2 = date("d/m/Y");
$day = date("l");
$year = date("Y");
$lastyear = date("Y", strtotime("-1 year"));
$month = date("F");
$year_month = date("Y-m");
$yearMonth = date("Ym");
$inv_month = date("m");
$current_date_time = date("Y-m-d H:i:s", time());
$current_time = date("H:i:s", time());
$current_time_lcl = date("h:i A", time());
$last_month = date("Y-m", strtotime("first day of previous month"));
$days_in_last_month = date("t", mktime(0, 0, 0, date("n") - 1));

date_default_timezone_set("Asia/Kolkata");
$current_date_time_local = date("Y-m-d H:i:s", time());
$today_local = date("Y-m-d");
date_default_timezone_set('UTC');
?>