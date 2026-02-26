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
if (isset($_POST['add_mc_job'])) {
    $j = $conn->real_escape_string($_POST['job_no']);
    $a = $conn->real_escape_string($_POST['acc_no']);
    $om = $conn->real_escape_string($_POST['old_met']);
    $ph = $_POST['phase'];

    // Check Duplicate
    if ($conn->query("SELECT id FROM meter_change WHERE job_no='$j'")->num_rows > 0) {
        $err = "Job Number '$j' already exists!";
    } else {
        $now = date('Y-m-d H:i:s');
        if ($conn->query("INSERT INTO meter_change (job_no, acc_no, old_meter_no, phase_type, created_at) VALUES ('$j','$a','$om','$ph','$now')")) {
            addLog($conn, $current_officer, 'ADD MC JOB', "Created Change Job: $j");
            $msg = "Job Added!";
        } else {
            $err = "Error: " . $conn->error;
        }
    }
}

// --- 2. UPDATE JOB (COMPLETE) ---
if (isset($_POST['update_mc_job'])) {
    $id = intval($_POST['job_id']);

    $ej = $conn->real_escape_string($_POST['e_job']);
    $ea = $conn->real_escape_string($_POST['e_acc']);
    $eom = $conn->real_escape_string($_POST['e_omet']);
    $eph = $_POST['e_phase'];
    $st = $_POST['status'];
    $or = $conn->real_escape_string($_POST['old_read']);
    $nm = $conn->real_escape_string($_POST['new_met']);
    $nr = $conn->real_escape_string($_POST['new_read']);
    $db = $conn->real_escape_string($_POST['done_by']);
    $dd = !empty($_POST['done_date']) ? "'" . $_POST['done_date'] . "'" : "NULL";
    $nt = $conn->real_escape_string($_POST['note']);

    $sql = "UPDATE meter_change SET 
            job_no='$ej', acc_no='$ea', old_meter_no='$eom', phase_type='$eph',
            old_reading='$or', new_meter_no='$nm', new_reading='$nr',
            done_by='$db', done_date=$dd, officer_note='$nt', status='$st' 
            WHERE id=$id";

    if ($conn->query($sql)) {
        addLog($conn, $current_officer, 'UPDATE MC JOB', "Updated Job $ej ($st)");
        $msg = "Updated Successfully!";
    }
}

// --- 3. DELETE JOB ---
if (isset($_GET['del']) && $_SESSION['role'] == 'Super Admin') {
    $del_id = intval($_GET['del']);
    $jn_query = $conn->query("SELECT job_no FROM meter_change WHERE id=$del_id");
    $jn = ($jn_query && $jn_query->num_rows > 0) ? $jn_query->fetch_assoc()['job_no'] : 'Unknown';

    if ($conn->query("DELETE FROM meter_change WHERE id=$del_id")) {
        addLog($conn, $current_officer, 'DELETE MC JOB', "Deleted Change Job: $jn");
        $msg = "Job Deleted!";
    }
}

// --- DASHBOARD COUNTS ---
function countMC($conn, $where = "1=1")
{
    return $conn->query("SELECT COUNT(*) c FROM meter_change WHERE $where")->fetch_assoc()['c'];
}

$pend_all = countMC($conn, "status='Pending'");
$pend_1ph = countMC($conn, "status='Pending' AND phase_type='Single Phase'");
$pend_3ph = countMC($conn, "status='Pending' AND phase_type='Three Phase'");
$comp_all = countMC($conn, "status='Completed'");
$comp_1ph = countMC($conn, "status='Completed' AND phase_type='Single Phase'");
$comp_3ph = countMC($conn, "status='Completed' AND phase_type='Three Phase'");
$today_date = date('Y-m-d');
$new_today = countMC($conn, "DATE(created_at) = '$today_date'");

include 'layout/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark"><i class="fas fa-exchange-alt text-primary"></i> Meter Change Jobs</h3>
        <span class="text-muted small">Manage replacements</span>
    </div>
    <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus-circle me-2"></i> New Request
    </button>
</div>

<?php if ($msg): ?>
    <div class='alert alert-success shadow-sm fade show'><?php echo $msg; ?></div>
<?php endif; ?>
<?php if ($err): ?>
    <div class='alert alert-danger shadow-sm fade show'><?php echo $err; ?></div>
<?php endif; ?>

<!-- DASHBOARD CARDS -->
<div class="row g-3 mb-4">

    <!-- CARD 1: PENDING -->
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-4 border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase">Pending Jobs</h6>
                        <h2 class="fw-bold text-warning mb-0"><?php echo $pend_all; ?></h2>
                    </div>
                    <i class="fas fa-clock fa-2x text-warning opacity-25"></i>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between small text-muted">
                    <span title="Single Phase"><i class="fas fa-plug me-1 text-warning"></i> 1-Ph: <b><?php echo $pend_1ph; ?></b></span>
                    <span title="Three Phase"><i class="fas fa-industry me-1 text-danger"></i> 3-Ph: <b><?php echo $pend_3ph; ?></b></span>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 2: COMPLETED -->
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-4 border-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase">Completed Jobs</h6>
                        <h2 class="fw-bold text-success mb-0"><?php echo $comp_all; ?></h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success opacity-25"></i>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between small text-muted">
                    <span title="Single Phase"><i class="fas fa-plug me-1 text-success"></i> 1-Ph: <b><?php echo $comp_1ph; ?></b></span>
                    <span title="Three Phase"><i class="fas fa-industry me-1 text-success"></i> 3-Ph: <b><?php echo $comp_3ph; ?></b></span>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD 3: NEW TODAY -->
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-4 border-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase">New Requests Today</h6>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $new_today; ?></h2>
                    </div>
                    <i class="fas fa-calendar-day fa-2x text-primary opacity-25"></i>
                </div>
                <div class="mt-3 pt-2 border-top text-muted small">
                    Daily Intake Summary
                </div>
            </div>
        </div>
    </div>

</div>

<!-- FILTER SECTION -->
<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <?php
            $s = $_GET['s'] ?? '';
            $f = $_GET['f'] ?? '';
            $p = $_GET['p'] ?? '';
            $d1 = $_GET['d1'] ?? '';
            $d2 = $_GET['d2'] ?? '';
            ?>
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Search</label>
                <input type="text" name="s" class="form-control" placeholder="Job/Acc/Meter..." value="<?php echo htmlspecialchars($s); ?>">
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Date Range</label>
                <div class="input-group">
                    <input type="date" name="d1" class="form-control form-control-sm" value="<?php echo $d1; ?>">
                    <span class="input-group-text">-</span>
                    <input type="date" name="d2" class="form-control form-control-sm" value="<?php echo $d2; ?>">
                </div>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-1">Phase</label>
                <select name="p" class="form-select">
                    <option value="">All</option>
                    <option value="Single Phase" <?php if ($p == 'Single Phase') echo 'selected'; ?>>Single Phase</option>
                    <option value="Three Phase" <?php if ($p == 'Three Phase') echo 'selected'; ?>>Three Phase</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-1">Status</label>
                <select name="f" class="form-select">
                    <option value="">All</option>
                    <option value="Pending" <?php if ($f == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Completed" <?php if ($f == 'Completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
            </div>

        </form>
        <?php if (isset($_GET['s'])): ?>
            <div class="mt-2">
                <a href="meter_change" class="text-danger small fw-bold">Clear Filters</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- TABLE -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Job Info (Click)</th>
                    <th>Account / Phase</th>
                    <th>Old Meter</th>
                    <th>New Details</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // --- FILTER QUERY ---
                $w = "WHERE 1=1";
                if (!empty($s)) $w .= " AND (job_no LIKE '%$s%' OR acc_no LIKE '%$s%' OR old_meter_no LIKE '%$s%' OR new_meter_no LIKE '%$s%')";
                if (!empty($f)) $w .= " AND status='$f'";
                if (!empty($p)) $w .= " AND phase_type='$p'";
                if (!empty($d1) && !empty($d2)) $w .= " AND created_at BETWEEN '$d1 00:00:00' AND '$d2 23:59:59'";

                // Pagination
                $results_per_page = 10;
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($page < 1) $page = 1;
                $offset = ($page - 1) * $results_per_page;

                $tot_res_query = $conn->query("SELECT COUNT(*) as t FROM meter_change $w");
                $tot_res = $tot_res_query ? $tot_res_query->fetch_assoc()['t'] : 0;
                $tot_pages = ceil($tot_res / $results_per_page);

                $res = $conn->query("SELECT * FROM meter_change $w ORDER BY id DESC LIMIT $results_per_page OFFSET $offset");

                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $bg = ($row['status'] == 'Pending') ? 'bg-warning text-dark' : 'bg-success';
                ?>
                        <tr>
                            <td>
                                <a href="#" onclick='edit(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)' class="fw-bold text-decoration-none fs-5 comp-ref"><?php echo $row['job_no']; ?></a><br>
                                <small class="text-muted"><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <b><?php echo $row['acc_no']; ?></b><br>
                                <span class="badge bg-secondary"><?php echo $row['phase_type']; ?></span>
                            </td>
                            <td>
                                Serial: <b><?php echo $row['old_meter_no']; ?></b><br>
                                <small>Final: <?php echo $row['old_reading'] ?: '-'; ?></small>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Completed'): ?>
                                    <small class="text-primary fw-bold">New: <?php echo $row['new_meter_no']; ?></small><br>
                                    <small>Init: <?php echo $row['new_reading']; ?></small><br>
                                    <small class="text-muted">By: <?php echo $row['done_by']; ?></small>
                                <?php else: ?>
                                    <span class="text-muted small">Not Installed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $bg; ?> rounded-pill"><?php echo $row['status']; ?></span>
                                <!-- DELETE BUTTON -->
                                <?php if ($_SESSION['role'] == 'Super Admin'): ?>
                                    <a href="meter_change.php?del=<?php echo $row['id']; ?>" onclick="return confirm('Delete Job?');" class="text-danger ms-2" style="font-size:0.9rem;">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-4'>No Data</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <?php if ($tot_pages > 1): ?>
        <nav class="p-3 border-top bg-white">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&s=<?php echo urlencode($s); ?>&f=<?php echo $f; ?>&p=<?php echo $p; ?>&d1=<?php echo $d1; ?>&d2=<?php echo $d2; ?>">Previous</a>
                </li>
                <li class="page-item disabled"><span class="page-link text-dark fw-bold">Page <?php echo $page; ?> / <?php echo $tot_pages; ?></span></li>
                <li class="page-item <?php if ($page >= $tot_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&s=<?php echo urlencode($s); ?>&f=<?php echo $f; ?>&p=<?php echo $p; ?>&d1=<?php echo $d1; ?>&d2=<?php echo $d2; ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">New Meter Change</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="fw-bold small">Job No</label>
                        <input type="text" name="job_no" class="form-control" value="MC/I/26/" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="fw-bold small">Acc No</label>
                            <input type="text" name="acc_no" class="form-control" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-bold small">Old Meter No</label>
                            <input type="text" name="old_met" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="fw-bold small">Phase</label>
                        <select name="phase" class="form-select">
                            <option value="Single Phase">Single Phase</option>
                            <option value="Three Phase">Three Phase</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button name="add_mc_job" class="btn btn-dark w-100">Create Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- UPDATE MODAL -->
<div class="modal fade" id="updModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Job Completion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_id" id="u_id">
                    <div class="row g-2 mb-3 bg-light p-2 rounded border">
                        <div class="col-12"><small class="text-uppercase fw-bold text-muted">Original Info (Editable)</small></div>
                        <div class="col-md-4">
                            <label class="small">Job No</label>
                            <input type="text" name="e_job" id="u_job" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="small">Acc No</label>
                            <input type="text" name="e_acc" id="u_acc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="small">Phase</label>
                            <select name="e_phase" id="u_ph" class="form-select form-select-sm">
                                <option>Single Phase</option>
                                <option>Three Phase</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="small">Old Meter No</label>
                            <input type="text" name="e_omet" id="u_omet" class="form-control form-control-sm">
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-6 border-end">
                            <h6 class="text-danger small fw-bold">OLD METER REMOVAL</h6>
                            <div class="mb-2">
                                <label class="small">Final Reading</label>
                                <input type="text" name="old_read" id="u_or" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success small fw-bold">NEW METER INSTALLATION</h6>
                            <div class="mb-2">
                                <label class="small">New Meter No</label>
                                <input type="text" name="new_met" id="u_nm" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label class="small">Initial Reading</label>
                                <input type="text" name="new_read" id="u_nr" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 bg-light p-2 rounded">
                        <div class="col-md-4">
                            <label class="small fw-bold">Status</label>
                            <select name="status" id="u_st" class="form-select form-select-sm">
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Done By</label>
                            <input type="text" name="done_by" id="u_db" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Date</label>
                            <input type="date" name="done_date" id="u_dd" class="form-control form-control-sm">
                        </div>
                        <div class="col-12 mt-2">
                            <textarea name="note" id="u_nt" class="form-control form-control-sm" placeholder="Any remarks..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button name="update_mc_job" class="btn btn-primary w-100 fw-bold">Save & Complete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function edit(d) {
        document.getElementById('u_id').value = d.id;
        document.getElementById('u_job').value = d.job_no;
        document.getElementById('u_acc').value = d.acc_no;
        document.getElementById('u_omet').value = d.old_meter_no;
        document.getElementById('u_ph').value = d.phase_type;
        document.getElementById('u_st').value = d.status;
        document.getElementById('u_or').value = d.old_reading || '';
        document.getElementById('u_nm').value = d.new_meter_no || '';
        document.getElementById('u_nr').value = d.new_reading || '';
        document.getElementById('u_db').value = d.done_by || '';
        document.getElementById('u_dd').value = d.done_date || '';
        document.getElementById('u_nt').value = d.officer_note || '';
        new bootstrap.Modal(document.getElementById('updModal')).show();
    }
</script>

<?php include 'layout/footer.php'; ?>