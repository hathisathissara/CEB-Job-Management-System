<?php


// --- STATS: METER REMOVAL ---
$r_mloc = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Pending'");
$mj_loc = ($r_mloc) ? $r_mloc->fetch_assoc()['c'] : 0;
$mj_pend = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
// Count Unique Accounts Only
$mj_rem = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Removed'")->fetch_assoc()['c'];
$mj_ret = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Returned - Paid'")->fetch_assoc()['c'];

// --- STATS: METER CHANGE (NEW) ---
$mc_all = $conn->query("SELECT COUNT(*) c FROM meter_change")->fetch_assoc()['c'];
$mc_pend = $conn->query("SELECT COUNT(*) c FROM meter_change WHERE status='Pending'")->fetch_assoc()['c'];
$mc_comp = $conn->query("SELECT COUNT(*) c FROM meter_change WHERE status='Completed'")->fetch_assoc()['c'];

// --- WEEKLY TREND CHART DATA (BOTH REMOVAL & CHANGE) ---
$week_labels = [];
$rem_data = [];
$chg_data = [];
$rem_comp_data = [];
$chg_comp_data = [];

$date_start = date('Y-m-d', strtotime('-6 days'));

// Loop last 7 days
for ($i = 0; $i < 7; $i++) {
    $d = date('Y-m-d', strtotime($date_start . " +$i days"));
    $week_labels[] = date('D', strtotime($d)); // Mon, Tue...

    // Count Removal Jobs
    $rem_c = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE DATE(created_at) = '$d'")->fetch_assoc()['c'];
    $rem_data[] = $rem_c;

    // Count Change Jobs
    $chg_c = $conn->query("SELECT COUNT(*) c FROM meter_change WHERE DATE(created_at) = '$d'")->fetch_assoc()['c'];
    $chg_data[] = $chg_c;

    // Count Completed Removals
    $rem_cmp = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE removing_date = '$d' AND status IN ('Removed', 'Returned - Paid')")->fetch_assoc()['c'];
    $rem_comp_data[] = $rem_cmp;

    // Count Completed Changes
    $chg_cmp = $conn->query("SELECT COUNT(*) c FROM meter_change WHERE done_date = '$d' AND status = 'Completed'")->fetch_assoc()['c'];
    $chg_comp_data[] = $chg_cmp;
}

// --- GET ACTIVE NOTICE ---
$notice = $conn->query("SELECT notice_text FROM system_settings WHERE is_active=1 AND id=1")->fetch_assoc();

?>