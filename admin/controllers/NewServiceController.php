<?php
$msg = ""; $err = "";
// ============================================
// 1. ADD NEW APPLICATION
if (isset($_POST['add_app'])) {
    $app = $conn->real_escape_string(trim($_POST['app_no']));
    $name = $conn->real_escape_string(trim($_POST['name']));
    $nic = $conn->real_escape_string(trim($_POST['nic']));
    $type = $conn->real_escape_string($_POST['service_type']); // Only Normal or 3 Phase initially
    $addr = $conn->real_escape_string(trim($_POST['address']));
    
    if($conn->query("SELECT id FROM new_connections WHERE app_no='$app'")->num_rows > 0) {
        $err = "Application No '$app' already exists!";
    } else {
        $sql = "INSERT INTO new_connections (app_no, customer_name, id_number, service_type, address) VALUES ('$app', '$name', '$nic', '$type', '$addr')";
        if($conn->query($sql)) {
            addLog($conn, $current_officer, 'NEW SERVICE', "Registered App: $app"); $msg="Application Registered!";
        } else { $err="Error: ".$conn->error; }
    }
}

// 2. UPDATE WORKFLOW STATUS
if (isset($_POST['update_app'])) {
    $id = intval($_POST['app_id']);
    
    $st = $_POST['status'];
    $type = $_POST['e_type']; // Updated Service Type
    $est = $conn->real_escape_string($_POST['est_no']);
    $job = $conn->real_escape_string($_POST['job_no']);
    $note = $conn->real_escape_string($_POST['note']);
    
    // Dates Handling
    $d_sent = !empty($_POST['sent_date']) ? "'".$conn->real_escape_string($_POST['sent_date'])."'" : "NULL";
    $d_appr = !empty($_POST['appr_date']) ? "'".$conn->real_escape_string($_POST['appr_date'])."'" : "NULL";
    $d_comp = !empty($_POST['comp_date']) ? "'".$conn->real_escape_string($_POST['comp_date'])."'" : "NULL";

    $sql = "UPDATE new_connections SET 
            service_type='$type', est_no='$est', job_no='$job', status='$st', officer_note='$note',
            sent_date=$d_sent, approved_date=$d_appr, completed_date=$d_comp 
            WHERE id=$id";
    
    if($conn->query($sql)) {
        addLog($conn, $current_officer, 'UPDATE SERVICE', "App ID $id updated to $st ($type)"); $msg="Record Updated Successfully!";
    } else { $err="Update Failed: ".$conn->error; }
}

// 3. DELETE APPLICATION (SUPER ADMIN ONLY)
if (isset($_GET['del']) && $_SESSION['role'] == 'Super Admin') {
    $del_id = intval($_GET['del']);
    $jn_query = $conn->query("SELECT app_no FROM new_connections WHERE id=$del_id");
    $jn = ($jn_query && $jn_query->num_rows > 0) ? $jn_query->fetch_assoc()['app_no'] : 'Unknown';

    if ($conn->query("DELETE FROM new_connections WHERE id=$del_id")) {
        addLog($conn, $current_officer, 'DELETE SERVICE', "Deleted Application: $jn");
        $msg = "Application Deleted Successfully!";
    } else {
        $err = "Error Deleting: " . $conn->error;
    }
}

// DASHBOARD COUNTS
$c_tot = $conn->query("SELECT COUNT(*) c FROM new_connections WHERE status != 'Completed'")->fetch_assoc()['c'];
$c_adu = $conn->query("SELECT COUNT(*) c FROM new_connections WHERE status = 'Shortcoming'")->fetch_assoc()['c'];
$c_appr = $conn->query("SELECT COUNT(*) c FROM new_connections WHERE status = 'Pending Approval'")->fetch_assoc()['c'];
$c_job = $conn->query("SELECT COUNT(*) c FROM new_connections WHERE status = 'Job Created'")->fetch_assoc()['c'];