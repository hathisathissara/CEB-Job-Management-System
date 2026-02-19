<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

include '../db_conn.php';
include 'functions.php';

$current_officer = $_SESSION['full_name'];
date_default_timezone_set('Asia/Colombo');

$msg = "";
$err = "";

// --- 1. ADD NEW JOB ---
if (isset($_POST['add_job'])) {
    $j = trim($_POST['job_no']);
    $a = trim($_POST['acc_no']);
    $m = trim($_POST['meter_no']);
    $now = date('Y-m-d H:i:s');

    if ($conn->query("SELECT id FROM meter_removal WHERE job_no='$j'")->num_rows > 0) {
        $err = "Job Number '$j' already exists!";
    } else {
        $dev_time = !empty($_POST['device_time']) ? $_POST['device_time'] : $now;
        if ($conn->query("INSERT INTO meter_removal (job_no, acc_no, meter_no, created_at) VALUES ('$j', '$a', '$m', '$dev_time')")) {
            addLog($conn, $current_officer, 'ADD JOB', "Created: $j");
            $msg = "Job Registered!";
        } else {
            $err = $conn->error;
        }
    }
}

// --- 2. UPDATE JOB ---
if (isset($_POST['update_job'])) {
    $id = intval($_POST['job_id']);
    $nj = $_POST['e_job'];
    $na = $_POST['e_acc'];
    $nm = $_POST['e_met'];
    $st = $_POST['status_opt'];
    $rd = $_POST['reading'];
    $nt = $_POST['officer_note'];
    $dn = $_POST['done_by'];
    $rm_d = !empty($_POST['rem_date']) ? "'" . $_POST['rem_date'] . "'" : "NULL";

    if ($conn->query("UPDATE meter_removal SET job_no='$nj', acc_no='$na', meter_no='$nm', meter_reading='$rd', removing_date=$rm_d, done_by='$dn', officer_note='$nt', status='$st' WHERE id=$id")) {
        addLog($conn, $current_officer, 'UPDATE JOB', "Updated $nj ($st)");
        $msg = "Updated Successfully!";
    }
}

// --- 3. DELETE JOB (Super Admin Only) ---
if (isset($_GET['del']) && $_SESSION['role'] == 'Super Admin') {
    $del_id = intval($_GET['del']);
    // Get Job No for Log
    $jn_query = $conn->query("SELECT job_no FROM meter_removal WHERE id=$del_id");
    $jn = ($jn_query && $jn_query->num_rows > 0) ? $jn_query->fetch_assoc()['job_no'] : 'Unknown';

    if ($conn->query("DELETE FROM meter_removal WHERE id=$del_id")) {
        addLog($conn, $current_officer, 'DELETE JOB', "Deleted Removal Job: $jn");
        $msg = "Job Deleted Successfully!";
    }
}

// --- DASHBOARD COUNTS ---
$mj_loc = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$mj_pend = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$mj_rem = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Removed'")->fetch_assoc()['c'];
$mj_ret = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Returned - Paid'")->fetch_assoc()['c'];

include 'layout/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0"><i class="fas fa-tools text-danger"></i> Meter Removal Operations</h3>
        <span class="text-muted small">Manage job cards and history</span>
    </div>
    <button class="btn btn-dark shadow-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addJobModal">
        <i class="fas fa-plus-circle me-2"></i> New Job
    </button>
</div>

<?php if ($msg): ?>
    <div class='alert alert-success shadow-sm alert-dismissible fade show'>
        <?php echo $msg; ?>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<?php if ($err): ?>
    <div class='alert alert-danger shadow-sm alert-dismissible fade show'>
        <?php echo $err; ?>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-bottom border-4 border-danger h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">PENDING LOCATIONS</h6>
                    <h2 class="fw-bold text-danger mb-0"><?php echo $mj_loc; ?></h2>
                </div>
                <i class="fas fa-map-marker-alt fa-2x text-danger opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-bottom border-4 border-warning h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">TOTAL CARDS</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_pend; ?></h2>
                </div>
                <i class="fas fa-layer-group fa-2x text-warning opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-bottom border-4 border-primary h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">REMOVED</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_rem; ?></h2>
                </div>
                <i class="fas fa-screwdriver fa-2x text-primary opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-bottom border-4 border-success h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">RETURNED</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_ret; ?></h2>
                </div>
                <i class="fas fa-check-circle fa-2x text-success opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<!-- FILTER SECTION -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-4 bg-light rounded shadow-inner">
        <form method="GET" class="row g-2 align-items-end">
            <?php
            $s = $_GET['s'] ?? '';
            $f = $_GET['f'] ?? '';
            $d1 = $_GET['d1'] ?? '';
            $d2 = $_GET['d2'] ?? '';
            $nodup = (isset($_GET['nodup']) && $_GET['nodup'] == '1') ? 1 : 0;
            ?>
            <div class="col-md-4">
                <label class="small fw-bold text-muted mb-1">Search Keywords</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="s" class="form-control border-start-0 ps-0" placeholder="Job No / Acc No / Meter..." value="<?php echo htmlspecialchars($s); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Date Range</label>
                <div class="input-group">
                    <input type="date" name="d1" class="form-control form-control-sm" value="<?php echo $d1; ?>">
                    <span class="input-group-text bg-white border-0">-</span>
                    <input type="date" name="d2" class="form-control form-control-sm" value="<?php echo $d2; ?>">
                </div>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-1">Status</label>
                <select name="f" class="form-select">
                    <option value="">All Status</option>
                    <option value="Pending" <?php if ($f == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Removed" <?php if ($f == 'Removed') echo 'selected'; ?>>Removed</option>
                    <option value="Returned - Paid" <?php if ($f == 'Returned - Paid') echo 'selected'; ?>>Returned</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="nodup" value="1" id="ndCheck" <?php if ($nodup == 1) echo 'checked'; ?>>
                    <label class="form-check-label small fw-bold text-dark" for="ndCheck">
                        Group by Account<br>
                        <span class="text-muted" style="font-size:10px;">(One per Account)</span>
                    </label>
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
            </div>
            
        </form>
        <?php if (isset($_GET['f'])): ?>
            <div class="mt-2">
                <a href="meter_jobs" class="text-danger small fw-bold text-decoration-none">
                    <i class="fas fa-times"></i> Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- RESULT COUNTER & QUERY -->
<?php
    // BUILD WHERE CLAUSE
    $clauses = ["1=1"];
    if(!empty($s)) $clauses[] = "(job_no LIKE '%$s%' OR acc_no LIKE '%$s%' OR meter_no LIKE '%$s%')";
    if(!empty($f)) $clauses[] = "status='$f'";
    if(!empty($d1) && !empty($d2)) {
        $col = ($f == 'Removed') ? 'removing_date' : 'created_at';
        $clauses[] = "$col BETWEEN '$d1' AND '$d2 23:59:59'";
    }
    $where_sql = implode(' AND ', $clauses);

    // PAGINATION
    $results_per_page=10; $page=isset($_GET['page'])&&is_numeric($_GET['page'])?(int)$_GET['page']:1; if($page<1)$page=1; $offset=($page-1)*$results_per_page;

    // QUERY CONSTRUCTION
    if($nodup == 1) {
        // Show Latest Job per Account
        $sql_base = "SELECT * FROM meter_removal WHERE id IN (SELECT MAX(id) FROM meter_removal WHERE $where_sql GROUP BY acc_no)";
        $count_sql = "SELECT COUNT(DISTINCT acc_no) as t FROM meter_removal WHERE $where_sql";
    } else {
        $sql_base = "SELECT * FROM meter_removal WHERE $where_sql";
        $count_sql = "SELECT COUNT(*) as t FROM meter_removal WHERE $where_sql";
    }

    // EXECUTE COUNT
    $tot_res = $conn->query($count_sql)->fetch_assoc()['t'];
    $tot_pages = ceil($tot_res/$results_per_page);
    
    // EXECUTE MAIN QUERY
    $sql_base .= " ORDER BY id DESC LIMIT $results_per_page OFFSET $offset";
    $res = $conn->query($sql_base);
    ?>

<div class="d-flex justify-content-between align-items-center mb-3 px-2">
    <div>
        <?php if (!empty($s) || !empty($f) || $nodup == 1 || !empty($d1)): ?>
            <span class="badge bg-primary shadow-sm p-2 px-3"><i class="fas fa-search me-1"></i> Found: <?php echo $tot_res; ?></span>
            <a href="meter_jobs" class="text-danger fw-bold small text-decoration-none ms-2"><i class="fas fa-times-circle"></i> Clear</a>
        <?php else: ?>
            <span class="text-muted small fw-bold"><i class="fas fa-list-ul me-1"></i> Total Records: <?php echo $mj_pend + $mj_rem + $mj_ret; ?></span>
        <?php endif; ?>
    </div>
</div>

<!-- DATA TABLE -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark text-uppercase small">
                <tr>
                    <th width="20%">Job Details</th>
                    <th width="25%">Account Info & History</th>
                    <th width="15%">Status</th>
                    <th width="40%">Officer Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $bg = 'bg-secondary';
                        if ($row['status'] == 'Pending') $bg = 'bg-warning text-dark';
                        if ($row['status'] == 'Removed') $bg = 'bg-danger';
                        if ($row['status'] == 'Returned - Paid') $bg = 'bg-success';

                        $ac = $row['acc_no'];
                        $qj = $conn->query("SELECT job_no FROM meter_removal WHERE acc_no='$ac' AND id!={$row['id']}");
                        $oc = $qj->num_rows;
                        $jl = [];
                        while ($jx = $qj->fetch_assoc()) {
                            $jl[] = $jx['job_no'];
                        }
                        $tt = "Other Jobs: " . implode(", ", $jl);

                        // HISTORY ALERT
                        $hist_alert = "";
                        if ($row['status'] == 'Pending') {
                            $qh = $conn->query("SELECT job_no, removing_date FROM meter_removal WHERE acc_no='$ac' AND status='Removed' AND id < {$row['id']} ORDER BY id DESC LIMIT 1");
                            if ($qh->num_rows > 0) {
                                $hdata = $qh->fetch_assoc();
                                $hist_alert = "<div class='mt-2 p-2 rounded border border-danger shadow-sm' style='font-size:10px; background-color:var(--bg-card); color:var(--text-danger);'><i class='fas fa-history me-1'></i> <span class='fw-bold'>PREVIOUSLY REMOVED:</span><br>Job: <b>{$hdata['job_no']}</b><br>Date: {$hdata['removing_date']}</div>";
                            }
                        }
                ?>
                        <tr>
                            <td>
                                <a href="#" onclick='edit(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)' class="fw-bold text-decoration-none fs-5 comp-ref"><?php echo $row['job_no']; ?></a><br>
                                <small class="text-muted"><i class="far fa-clock me-1"></i> <?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td class="align-top pt-3">
                                <b class="text-dark fs-6"><?php echo $row['acc_no']; ?></b>
                                <?php if ($oc > 0) echo "<span class='badge bg-dark ms-2' data-bs-toggle='tooltip' title='$tt'>+{$oc} Jobs</span>"; ?>
                                <br><small class="text-secondary">Meter: <?php echo $row['meter_no'] ?: '-'; ?></small>
                                <?php echo $hist_alert; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $bg; ?> rounded-pill px-3 shadow-sm"><?php echo $row['status']; ?></span>
                            </td>
                            <td>
                                <?php if ($row['status'] != 'Pending'): ?>
                                    <div class="bg-light p-2 rounded border border-light">
                                        <ul class="list-unstyled small mb-0 text-secondary">
                                            <?php if ($row['status'] == 'Removed') echo "<li class='mb-1'><i class='fas fa-tachometer-alt me-2 text-primary'></i>Reading: <b class='text-dark'>{$row['meter_reading']}</b></li> <li class='mb-1'><i class='far fa-calendar-check me-2 text-primary'></i>Date: <b>{$row['removing_date']}</b></li>"; ?>
                                            <?php if ($row['done_by']) echo "<li class='mb-1'><i class='fas fa-user-check me-2'></i>Done: {$row['done_by']}</li>"; ?>
                                            <?php if ($row['officer_note']) echo "<li class='text-danger mt-1 fst-italic border-top pt-1'>\"{$row['officer_note']}\"</li>"; ?>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small fst-italic">Waiting for action...</span>
                                <?php endif; ?>

                                <?php if ($_SESSION['role'] == 'Super Admin'): ?>
                                    <div class="mt-2 text-end">
                                        <a href="meter_jobs.php?del=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete Job <?php echo $row['job_no']; ?>?');" class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete Job">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center py-5 text-muted'>No Records Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if ($tot_pages > 1): ?>
        <nav class="p-3 border-top bg-white">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&s=<?php echo urlencode($s); ?>&f=<?php echo $f; ?>&d1=<?php echo $d1; ?>&d2=<?php echo $d2; ?>&nodup=<?php echo $nodup; ?>">Previous</a>
                </li>
                <li class="page-item disabled"><span class="page-link text-dark fw-bold">Page <?php echo $page; ?> / <?php echo $tot_pages; ?></span></li>
                <li class="page-item <?php if ($page >= $tot_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&s=<?php echo urlencode($s); ?>&f=<?php echo $f; ?>&d1=<?php echo $d1; ?>&d2=<?php echo $d2; ?>&nodup=<?php echo $nodup; ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addJobModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" onsubmit="setDeviceTime()">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">New Job Card</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="device_time" id="d_time">
                    <div class="mb-3">
                        <label class="fw-bold small">Job No</label>
                        <input type="text" name="job_no" class="form-control" value="RC/I/26/" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Account No</label>
                        <input type="text" name="acc_no" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small">Meter No</label>
                        <input type="text" name="meter_no" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_job" class="btn btn-dark w-100 fw-bold shadow">REGISTER JOB</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- UPDATE MODAL -->
<div class="modal fade" id="updModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit / Update Action</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_id" id="u_id">
                    <div class="p-2 border rounded bg-light mb-3">
                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <label class="small fw-bold">Job No:</label>
                                <input type="text" name="e_job" id="u_job_input" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold">Acc No:</label>
                                <input type="text" name="e_acc" id="u_acc" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-12">
                                <label class="small fw-bold">Meter No:</label>
                                <input type="text" name="e_met" id="u_met" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-primary mb-1 small">Current Status</label>
                        <select name="status_opt" id="u_st" class="form-select border-primary" onchange="toggleFields()">
                            <option value="Pending">Pending</option>
                            <option value="Removed">Removed</option>
                            <option value="Returned - Paid">Returned / Paid</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="p-3 bg-light rounded border mb-3">
                        <label class="small fw-bold text-muted mb-1">Done By:</label>
                        <input type="text" name="done_by" id="u_done" class="form-control form-control-sm mb-2">
                        <label class="small fw-bold text-muted mb-1">Notes / Remarks:</label>
                        <textarea name="officer_note" id="u_note" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div id="remove_fields" style="display:none;">
                        <div class="row">
                            <div class="col-6">
                                <label class="small fw-bold">Reading:</label>
                                <input type="text" name="reading" id="u_read" class="form-control form-control-sm shadow-sm">
                            </div>
                            <div class="col-6">
                                <label class="small fw-bold">Remove Date:</label>
                                <input type="date" name="rem_date" id="u_rem" class="form-control form-control-sm shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button name="update_job" class="btn btn-primary w-100 fw-bold shadow">Save All Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setDeviceTime() {
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const d = String(now.getDate()).padStart(2, '0');
        const h = String(now.getHours()).padStart(2, '0');
        const i = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('d_time').value = `${y}-${m}-${d} ${h}:${i}:${s}`;
    }

    function edit(d) {
        document.getElementById('u_id').value = d.id;
        document.getElementById('u_job_input').value = d.job_no;
        document.getElementById('u_acc').value = d.acc_no;
        document.getElementById('u_met').value = d.meter_no || '';
        document.getElementById('u_st').value = d.status;
        document.getElementById('u_done').value = d.done_by || '';
        document.getElementById('u_note').value = d.officer_note || '';
        document.getElementById('u_read').value = d.meter_reading || '';
        document.getElementById('u_rem').value = d.removing_date || '';
        toggleFields();
        new bootstrap.Modal(document.getElementById('updModal')).show();
    }

    function toggleFields() {
        var st = document.getElementById('u_st').value;
        document.getElementById('remove_fields').style.display = (st === 'Removed') ? 'block' : 'none';
    }
</script>

<?php include 'layout/footer.php'; ?>