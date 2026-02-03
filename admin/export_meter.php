<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) exit();
include '../db_conn.php';

// Headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Meter_Jobs_Report_'.date('Y-m-d_Hi').'.csv');

$out = fopen('php://output', 'w');
fputcsv($out, array('ID', 'Job No', 'Account No', 'Meter No', 'Status', 'Date Added', 'Remove Date', 'Final Reading', 'Done By', 'Note'));

// --- FILTER LOGIC ---
$s = $_GET['s'] ?? '';
$f = $_GET['f'] ?? '';
$d1 = $_GET['d1'] ?? '';
$d2 = $_GET['d2'] ?? '';
$dup = isset($_GET['dup']) ? (int)$_GET['dup'] : 0; // Check Duplicate Flag

// 1. Base Query Selection
if ($dup) {
    // If Duplicate Checked: Select ONLY duplicates
    $sql = "SELECT t1.* FROM meter_removal t1
            INNER JOIN (
                SELECT acc_no FROM meter_removal GROUP BY acc_no HAVING COUNT(id) > 1
            ) t2 ON t1.acc_no = t2.acc_no WHERE 1=1";
} else {
    // Normal Selection
    $sql = "SELECT * FROM meter_removal WHERE 1=1";
}

// 2. Apply Other Filters
if (!empty($s)) {
    $sql .= " AND (job_no LIKE '%$s%' OR acc_no LIKE '%$s%' OR meter_no LIKE '%$s%')";
}
if (!empty($f)) {
    $sql .= " AND status='$f'";
}
if (!empty($d1) && !empty($d2)) {
    if ($f == 'Removed') {
        $sql .= " AND removing_date BETWEEN '$d1' AND '$d2'";
    } else {
        $sql .= " AND created_at BETWEEN '$d1 00:00:00' AND '$d2 23:59:59'";
    }
}

$sql .= " ORDER BY id DESC"; // No Limit needed for export

// Execute
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        fputcsv($out, array(
            $r['id'], 
            $r['job_no'], 
            $r['acc_no'], 
            $r['meter_no'], 
            $r['status'], 
            $r['created_at'], 
            $r['removing_date'], 
            $r['meter_reading'], 
            $r['done_by'], 
            $r['officer_note']
        ));
    }
}

fclose($out);
exit();
?>