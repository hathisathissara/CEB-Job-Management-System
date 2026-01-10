<?php
session_start();
// Security
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include '../db_conn.php';
include 'functions.php';
$current_officer = $_SESSION['full_name'];
$msg = "";
$err = "";

// 1. ADD NEW (With Duplicate Check)
if (isset($_POST['add_job'])) {
    $j = trim($_POST['job_no']);
    $a = trim($_POST['acc_no']);
    $m = trim($_POST['meter_no']);

    // Check for Duplicate
    $check_dup = $conn->query("SELECT id FROM meter_removal WHERE job_no='$j'");

    if ($check_dup->num_rows > 0) {
        $err = "Cannot add! Job Number '<b>$j</b>' already exists in system.";
    } else {
        if ($conn->query("INSERT INTO meter_removal (job_no, acc_no, meter_no) VALUES ('$j','$a','$m')")) {
            addLog($conn, $current_officer, 'ADD JOB', "Created Meter Job: $j");
            $msg = "New Job Registered!";
        } else {
            $err = "Error: " . $conn->error;
        }
    }
}

// --- 2. UPDATE JOB LOGIC (Modified) ---
if (isset($_POST['update_job'])) {
    $id = intval($_POST['job_id']);

    // New editable fields
    $new_job = $_POST['e_job'];
    $new_acc = $_POST['e_acc'];
    $new_met = $_POST['e_met'];

    $stat = $_POST['status_opt'];
    $read = $_POST['reading'];
    $note = $_POST['officer_note'];
    $done = $_POST['done_by'];
    $rem_d = !empty($_POST['rem_date']) ? "'" . $_POST['rem_date'] . "'" : "NULL";

    // SQL UPDATE (Adding job/acc/meter update)
    $sql = "UPDATE meter_removal SET 
            job_no='$new_job', acc_no='$new_acc', meter_no='$new_met', 
            meter_reading='$read', removing_date=$rem_d, done_by='$done', 
            officer_note='$note', status='$stat' 
            WHERE id=$id";

    if ($conn->query($sql)) {
        addLog($conn, $current_officer, 'UPDATE JOB', "Updated Job $new_job Details");
        $msg = "Job Updated Successfully!";
    }
}

// Dashboard Counts (Pending Unique, Total Pending, Removed, Returned)
$mj_loc = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$mj_pend = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$mj_rem  = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Removed'")->fetch_assoc()['c'];
$mj_ret  = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Returned - Paid'")->fetch_assoc()['c'];

include 'layout/header.php';
?>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-tools text-danger"></i> Meter Removal Jobs</h3>
        <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addJobModal"><i class="fas fa-plus-circle"></i> Add Job</button>
    </div>

    <!-- Error/Success Messages -->
    <?php if ($msg) echo "<div class='alert alert-success shadow-sm alert-dismissible fade show'>$msg <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
    <?php if ($err) echo "<div class='alert alert-danger shadow-sm alert-dismissible fade show'><i class='fas fa-exclamation-circle'></i> $err <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-danger h-100 p-2">
                <div class="card-body">
                    <h6 class="text-secondary small fw-bold">PENDING VISITS</h6>
                    <h2 class="fw-bold text-danger mb-0"><?php echo $mj_loc; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-warning h-100 p-2">
                <div class="card-body">
                    <h6 class="text-secondary small fw-bold">TOTAL JOBS</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_pend; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-primary h-100 p-2">
                <div class="card-body">
                    <h6 class="text-secondary small fw-bold">REMOVED</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_rem; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-success h-100 p-2">
                <div class="card-body">
                    <h6 class="text-secondary small fw-bold">RETURNED</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_ret; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light">
            <form method="GET" class="row g-2 align-items-center">
                <?php $s = $_GET['s'] ?? '';
                $f = $_GET['f'] ?? '';
                $d1 = $_GET['d1'] ?? '';
                $d2 = $_GET['d2'] ?? ''; ?>
                <div class="col-md-3"><input type="text" name="s" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($s); ?>"></div>
                <div class="col-md-2"><input type="date" name="d1" class="form-control" value="<?php echo $d1; ?>"></div>
                <div class="col-md-2"><input type="date" name="d2" class="form-control" value="<?php echo $d2; ?>"></div>
                <div class="col-md-2"><select name="f" class="form-select">
                        <option value="">All Status</option>
                        <option value="Pending" <?php if ($f == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Removed" <?php if ($f == 'Removed') echo 'selected'; ?>>Removed</option>
                        <option value="Returned - Paid" <?php if ($f == 'Returned - Paid') echo 'selected'; ?>>Returned</option>
                    </select></div>
                <div class="col-md-1"><button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button></div>
                <div class="col-md-2"><a href="export_meter.php?s=<?php echo urlencode($s); ?>&f=<?php echo $f; ?>" class="btn btn-success w-100 fw-bold">CSV</a></div>
            </form>
            <?php if (isset($_GET['f'])) echo '<div class="mt-2"><a href="meter_jobs" class="text-danger small fw-bold text-decoration-none">Clear</a></div>'; ?>
        </div>
    </div>

    <!-- 2. DATA TABLE & PAGINATION -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Job Info (Edit)</th>
                        <th>Account Info</th>
                        <th>Status</th>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // --- PAGINATION SETTINGS ---
                    $results_per_page = 10;
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                    if ($page < 1) $page = 1; // Correction
                    $offset = ($page - 1) * $results_per_page;

                    // Build WHERE clause based on filters
                    $w = "WHERE 1=1";
                    if (!empty($s)) $w .= " AND (job_no LIKE '%$s%' OR acc_no LIKE '%$s%' OR meter_no LIKE '%$s%')";
                    if (!empty($f)) $w .= " AND status='$f'";
                    if (!empty($d1) && !empty($d2)) $w .= " AND created_at BETWEEN '$d1 00:00:00' AND '$d2 23:59:59'";

                    // Get Total Count for Pagination Logic
                    $total_res = $conn->query("SELECT COUNT(*) as t FROM meter_removal $w")->fetch_assoc()['t'];
                    $total_pages = ceil($total_res / $results_per_page);

                    // Fetch Actual Data (LIMIT added)
                    $sql_final = "SELECT * FROM meter_removal $w ORDER BY id DESC LIMIT $results_per_page OFFSET $offset";
                    $res = $conn->query($sql_final);

                    if ($res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {

                            // (Badge Colors)
                            $bg = 'bg-secondary';
                            if ($row['status'] == 'Pending') $bg = 'bg-warning text-dark';
                            if ($row['status'] == 'Removed') $bg = 'bg-danger';
                            if ($row['status'] == 'Returned - Paid') $bg = 'bg-success';

                            // Tooltip Logic
                            $ac = $row['acc_no'];
                            $qj = $conn->query("SELECT job_no FROM meter_removal WHERE acc_no='$ac' AND id!={$row['id']}");
                            $oc = $qj->num_rows;
                            $jl = [];
                            while ($jx = $qj->fetch_assoc()) {
                                $jl[] = $jx['job_no'];
                            }
                            $tt = "Others: " . implode(", ", $jl);
                    ?>
                            <tr>
                                <td>
                                    <a href="#" onclick="edit(<?php echo htmlspecialchars(json_encode($row)); ?>)" class="fw-bold text-decoration-none fs-5 comp-ref"><?php echo $row['job_no']; ?></a><br>
                                    <small class="text-muted"><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></small>
                                </td>
                                <td>
                                    <b>Acc: <?php echo $row['acc_no']; ?></b>
                                    <?php if ($oc > 0) echo "<span class='badge bg-dark ms-2' data-bs-toggle='tooltip' title='$tt'>+{$oc} More</span>"; ?>
                                    <br><small class="text-muted">Met: <?php echo $row['meter_no'] ?: '-'; ?></small>
                                </td>
                                <td><span class="badge <?php echo $bg; ?> rounded-pill px-3"><?php echo $row['status']; ?></span></td>
                                <td>
                                    <?php if ($row['status'] != 'Pending'): ?>
                                        <ul class="list-unstyled small mb-0 text-muted">
                                            <?php if ($row['status'] == 'Removed') echo "<li>Read: <b class='text-dark'>{$row['meter_reading']}</b></li>"; ?>
                                            <?php if ($row['done_by']) echo "<li>Done: {$row['done_by']}</li>"; ?>
                                            <?php if ($row['officer_note']) echo "<li class='text-danger mt-1 fst-italic'>\"{$row['officer_note']}\"</li>"; ?>
                                        </ul>
                                    <?php else: echo '<span class="text-muted small">---</span>';
                                    endif; ?>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-5'>No Records Found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- 3. PAGINATION BUTTONS (Smart Links) -->
        <?php if ($total_pages > 1): ?>
            <nav class="p-3 border-top bg-white">
                <ul class="pagination justify-content-center mb-0">

                    <!-- PREVIOUS LINK -->
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php echo "?page=" . ($page - 1) . "&s=" . urlencode($s) . "&f=$f&d1=$d1&d2=$d2"; ?>">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    </li>

                    <!-- INFO TEXT -->
                    <li class="page-item disabled"><span class="page-link text-dark fw-bold">Page <?php echo $page; ?> / <?php echo $total_pages; ?> (Total: <?php echo $total_res; ?>)</span></li>

                    <!-- NEXT LINK -->
                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php echo "?page=" . ($page + 1) . "&s=" . urlencode($s) . "&f=$f&d1=$d1&d2=$d2"; ?>">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
        <?php endif; ?>

    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addJobModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">New Job</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="fw-bold">Job No</label><input type="text" name="job_no" class="form-control" required></div>
                        <div class="mb-3"><label class="fw-bold">Acc No</label><input type="text" name="acc_no" class="form-control" required></div>
                        <div class="mb-3"><label class="fw-bold">Meter No</label><input type="text" name="meter_no" class="form-control"></div>
                    </div>
                    <div class="modal-footer"><button type="submit" name="add_job" class="btn btn-dark w-100">Create</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. UPDATE MODAL (Full Edit Access) -->
    <div class="modal fade" id="updModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Job Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="job_id" id="u_id">

                        <!-- EDITABLE BASIC INFO -->
                        <div class="p-2 border rounded bg-light mb-3">
                            <small class="text-secondary fw-bold text-uppercase">Basic Information (Editable)</small>
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

                        <hr>

                        <!-- STATUS & WORK DETAILS -->
                        <div class="mb-3">
                            <label class="fw-bold text-primary mb-1">Current Status</label>
                            <select name="status_opt" id="u_st" class="form-select border-primary" onchange="toggleFields()">
                                <option value="Pending">Pending</option>
                                <option value="Removed">Removed</option>
                                <option value="Returned - Paid">Returned / Paid</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="p-3 bg-light rounded border mb-3">
                            <label class="small fw-bold text-muted mb-1">Done By (Officer):</label>
                            <input type="text" name="done_by" id="u_done" class="form-control form-control-sm mb-2">

                            <label class="small fw-bold text-muted mb-1">Notes:</label>
                            <textarea name="officer_note" id="u_note" rows="2" class="form-control form-control-sm"></textarea>
                        </div>

                        <!-- REMOVED SPECIFIC FIELDS -->
                        <div id="remove_fields" style="display:none;">
                            <div class="row">
                                <div class="col-6"><label class="small">Reading:</label><input type="text" name="reading" id="u_read" class="form-control form-control-sm"></div>
                                <div class="col-6"><label class="small">Remove Date:</label><input type="date" name="rem_date" id="u_rem" class="form-control form-control-sm"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button name="update_job" class="btn btn-primary w-100 fw-bold">Save All Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function edit(d) {
            document.getElementById('u_id').value = d.id;

            // New Fields Filling
            document.getElementById('u_job_input').value = d.job_no;
            document.getElementById('u_acc').value = d.acc_no;
            document.getElementById('u_met').value = d.meter_no || '';

            // Existing Fields
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