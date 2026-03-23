<?php


$msg = ""; $err = "";

// 1. ADD NEW JOB
if (isset($_POST['add_job'])) {
    $j=trim($_POST['job_no']); $a=trim($_POST['acc_no']); $m=trim($_POST['meter_no']); $now=date('Y-m-d H:i:s');
    
    // Escape Input
    $j = $conn->real_escape_string($j); $a = $conn->real_escape_string($a); $m = $conn->real_escape_string($m);

    if($conn->query("SELECT id FROM meter_removal WHERE job_no='$j'")->num_rows>0){ 
        $err="Job Number '$j' already exists!"; 
    } else {
        $dev_time = !empty($_POST['device_time']) ? $_POST['device_time'] : $now;
        if($conn->query("INSERT INTO meter_removal (job_no,acc_no,meter_no,created_at) VALUES ('$j','$a','$m','$dev_time')")){
            addLog($conn, $current_officer, 'ADD JOB', "Created: $j"); 
            $msg="Job Registered!";
        } else { 
            $err=$conn->error; 
        }
    }
}

// 2. UPDATE JOB
if (isset($_POST['update_job'])) {
    $id=intval($_POST['job_id']); 
    $nj=$conn->real_escape_string($_POST['e_job']); $na=$conn->real_escape_string($_POST['e_acc']); $nm=$conn->real_escape_string($_POST['e_met']);
    $st=$conn->real_escape_string($_POST['status_opt']); $rd=$conn->real_escape_string($_POST['reading']); 
    $nt=$conn->real_escape_string($_POST['officer_note']); $dn=$conn->real_escape_string($_POST['done_by']);
    $rm_d = !empty($_POST['rem_date']) ? "'".$conn->real_escape_string($_POST['rem_date'])."'" : "NULL";

    if($conn->query("UPDATE meter_removal SET job_no='$nj', acc_no='$na', meter_no='$nm', meter_reading='$rd', removing_date=$rm_d, done_by='$dn', officer_note='$nt', status='$st' WHERE id=$id")){
        addLog($conn, $current_officer, 'UPDATE JOB', "Updated $nj ($st)"); 
        $msg="Updated Successfully!";
    }
}

// 3. DELETE JOB
if (isset($_GET['del']) && $_SESSION['role'] == 'Super Admin') {
    $del_id = intval($_GET['del']);
    $jn = $conn->query("SELECT job_no FROM meter_removal WHERE id=$del_id")->fetch_assoc()['job_no'] ?? 'Unknown';
    if ($conn->query("DELETE FROM meter_removal WHERE id=$del_id")) {
        addLog($conn, $current_officer, 'DELETE JOB', "Deleted Removal Job: $jn");
        $msg = "Job Deleted!";
    }
}
?>