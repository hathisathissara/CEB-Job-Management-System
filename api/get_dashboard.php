<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include '../db_conn.php';

// Get Counts
$c_loc = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$c_pend = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$c_rem = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Removed'")->fetch_assoc()['c'];
$c_ret = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Returned - Paid'")->fetch_assoc()['c'];

echo json_encode([
    "locations" => $c_loc,
    "pending" => $c_pend,
    "removed" => $c_rem,
    "returned" => $c_ret
]);
