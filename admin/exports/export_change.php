<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) exit();
include '../db_conn.php';

// Headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Meter_Change_Report_' . date('Y-m-d_Hi') . '.csv');

$out = fopen('php://output', 'w');
fputcsv($out, array('ID', 'Job No', 'Account No', 'Phase', 'Old Meter No', 'Old Reading', 'Status', 'Date Added', 'Done By', 'Done Date', 'New Meter No', 'New Reading', 'Note'));

// --- FILTER LOGIC ---
$s = $_GET['s'] ?? '';
$f = $_GET['f'] ?? '';
$p = $_GET['p'] ?? '';
$d1 = $_GET['d1'] ?? '';
$d2 = $_GET['d2'] ?? '';

// Build Query
$w = "WHERE 1=1";

if (!empty($s)) {
    // Search in job_no, acc_no, old_meter_no, new_meter_no
    $w .= " AND (job_no LIKE '%$s%' OR acc_no LIKE '%$s%' OR old_meter_no LIKE '%$s%' OR new_meter_no LIKE '%$s%')";
}
if (!empty($f)) {
    $w .= " AND status='$f'";
}
if (!empty($p)) {
    $w .= " AND phase_type='$p'";
}
if (!empty($d1) && !empty($d2)) {
    $w .= " AND created_at BETWEEN '$d1 00:00:00' AND '$d2 23:59:59'";
}

$sql = "SELECT * FROM meter_change $w ORDER BY id DESC";

// Execute
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        fputcsv($out, array(
            $r['id'],
            $r['job_no'],
            $r['acc_no'],
            $r['phase_type'],
            $r['old_meter_no'],
            $r['old_reading'],
            $r['status'],
            $r['created_at'],
            $r['done_by'],
            $r['done_date'],
            $r['new_meter_no'],
            $r['new_reading'],
            $r['officer_note']
        ));
    }
}

fclose($out);
exit();
