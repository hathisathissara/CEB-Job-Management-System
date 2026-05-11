<?php
// admin/controllers/MeterChangeController.php

$msg = ""; $err = "";

// 1. ADD NEW JOB
if (isset($_POST['add_mc_job'])) {
    $j = $conn->real_escape_string(trim($_POST['job_no'] ?? ''));
    $a = $conn->real_escape_string(trim($_POST['acc_no'] ?? ''));
    $om = $conn->real_escape_string(trim($_POST['old_met'] ?? ''));
    $ph = $conn->real_escape_string($_POST['phase'] ?? 'Single Phase');
    
    // Check Duplicate
    if($conn->query("SELECT id FROM meter_change WHERE job_no='$j'")->num_rows > 0) {
        $err = "Job Number '$j' already exists!";
    } else {
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO meter_change (job_no, acc_no, old_meter_no, phase_type, created_at) VALUES ('$j','$a','$om','$ph','$now')";
        
        if($conn->query($sql)) {
            addLog($conn, $current_officer, 'ADD MC JOB', "Created Change Job: $j"); 
            $msg = "Job Added Successfully!";
        } else { 
            $err = "Error: ".$conn->error; 
        }
    }
}

// 2. UPDATE JOB (COMPLETE)
if (isset($_POST['update_mc_job'])) {
    $id = intval($_POST['job_id']);
    
    // Editable Basic Info
    $ej = $conn->real_escape_string($_POST['e_job'] ?? '');
    $ea = $conn->real_escape_string($_POST['e_acc'] ?? '');
    $eom = $conn->real_escape_string($_POST['e_omet'] ?? '');
    $eph = $conn->real_escape_string($_POST['e_phase'] ?? '');

    // Completion Info
    $st = $conn->real_escape_string($_POST['status'] ?? 'Pending');
    $or = $conn->real_escape_string($_POST['old_read'] ?? '');
    $nm = $conn->real_escape_string($_POST['new_met'] ?? '');
    $nr = $conn->real_escape_string($_POST['new_read'] ?? '');
    $db = $conn->real_escape_string($_POST['done_by'] ?? '');
    $nt = $conn->real_escape_string($_POST['note'] ?? '');
    
    $dd = !empty($_POST['done_date']) ? "'".$conn->real_escape_string($_POST['done_date'])."'" : "NULL";

    $sql = "UPDATE meter_change SET 
            job_no='$ej', acc_no='$ea', old_meter_no='$eom', phase_type='$eph',
            old_reading='$or', new_meter_no='$nm', new_reading='$nr',
            done_by='$db', done_date=$dd, officer_note='$nt', status='$st' 
            WHERE id=$id";
    
    if($conn->query($sql)) {
        addLog($conn, $current_officer, 'UPDATE MC JOB', "Updated Job $ej ($st)"); 
        $msg = "Updated Successfully!";
    } else {
        $err = "Update Failed: " . $conn->error;
    }
}

// 3. DELETE JOB (Optional: Only if Super Admin clicks delete link)
if (isset($_GET['del']) && $_SESSION['role'] == 'Super Admin') {
    $del_id = intval($_GET['del']);
    $jn = $conn->query("SELECT job_no FROM meter_change WHERE id=$del_id")->fetch_assoc()['job_no'] ?? 'Unknown';
    if ($conn->query("DELETE FROM meter_change WHERE id=$del_id")) {
        addLog($conn, $current_officer, 'DELETE MC JOB', "Deleted Change Job: $jn");
        $msg = "Job Deleted Successfully!";
    }
}

// --- DASHBOARD COUNTS ---
function countMC($conn, $where = "1=1") {
    return $conn->query("SELECT COUNT(*) c FROM meter_change WHERE $where")->fetch_assoc()['c'];
}

// දින සඳහා Condition String සකසා ගැනීම
$tm_c = "MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())"; // මේ මාසයේ හැදුන ඒවා
$lm_c = "MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)"; // ගිය මාසයේ හැදුන ඒවා

$tm_d = "MONTH(done_date) = MONTH(CURRENT_DATE()) AND YEAR(done_date) = YEAR(CURRENT_DATE())"; // මේ මාසයේ ඉවර කරපු ඒවා
$lm_d = "MONTH(done_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(done_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)"; // ගිය මාසයේ ඉවර කරපු ඒවා


// 1. PENDING STATS
$pend_all = countMC($conn, "status='Pending'");
// Pending This Month
$p_tm_1p = countMC($conn, "status='Pending' AND phase_type='Single Phase' AND $tm_c");
$p_tm_3p = countMC($conn, "status='Pending' AND phase_type='Three Phase' AND $tm_c");
// Pending Last Month
$p_lm_1p = countMC($conn, "status='Pending' AND phase_type='Single Phase' AND $lm_c");
$p_lm_3p = countMC($conn, "status='Pending' AND phase_type='Three Phase' AND $lm_c");

// 2. COMPLETED STATS (Using done_date)
$comp_all = countMC($conn, "status='Completed'");
// Completed This Month
$c_tm_1p = countMC($conn, "status='Completed' AND phase_type='Single Phase' AND $tm_d");
$c_tm_3p = countMC($conn, "status='Completed' AND phase_type='Three Phase' AND $tm_d");
// Completed Last Month
$c_lm_1p = countMC($conn, "status='Completed' AND phase_type='Single Phase' AND $lm_d");
$c_lm_3p = countMC($conn, "status='Completed' AND phase_type='Three Phase' AND $lm_d");

// 3. NEW REQUESTS (Based on creation date)
$req_tm_1p = countMC($conn, "phase_type='Single Phase' AND $tm_c");
$req_tm_3p = countMC($conn, "phase_type='Three Phase' AND $tm_c");
$req_lm_1p = countMC($conn, "phase_type='Single Phase' AND $lm_c");
$req_lm_3p = countMC($conn, "phase_type='Three Phase' AND $lm_c");

$new_month_total = $req_tm_1p + $req_tm_3p; // මේ මාසයේ මුළු ගණන
?>


?>