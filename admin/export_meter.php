<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) exit();
include '../db_conn.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename=Meter_Jobs_Report.csv');
$out = fopen('php://output', 'w');
fputcsv($out, array('ID', 'Job No', 'Acc No', 'Meter No', 'Status', 'Date Added', 'Remove Date', 'Final Reading', 'Done By', 'Note'));

$w = "WHERE 1=1";
$s = $_GET['s'] ?? '';
$f = $_GET['f'] ?? '';
$d1 = $_GET['d1'] ?? '';
$d2 = $_GET['d2'] ?? '';

if (!empty($s)) $w .= " AND (job_no LIKE '%$s%' OR acc_no LIKE '%$s%' OR meter_no LIKE '%$s%')";
if (!empty($f)) $w .= " AND status='$f'";
if (!empty($d1) && !empty($d2)) $w .= " AND created_at BETWEEN '$d1 00:00:00' AND '$d2 23:59:59'";

$res = $conn->query("SELECT * FROM meter_removal $w ORDER BY id DESC");
while ($r = $res->fetch_assoc()) {
    fputcsv($out, array($r['id'], $r['job_no'], $r['acc_no'], $r['meter_no'], $r['status'], $r['created_at'], $r['removing_date'], $r['meter_reading'], $r['done_by'], $r['officer_note']));
}
fclose($out);
exit();
