<?php
session_start();
// Security
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include '../db_conn.php';
$current_officer = $_SESSION['full_name'];

// --- COUNTS (METER JOBS ONLY) ---
// Pending Unique Accounts (Locations)
$r_mloc = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Pending'");
$mj_loc = ($r_mloc) ? $r_mloc->fetch_assoc()['c'] : 0;

// Job Cards Statuses
$mj_pend = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$mj_rem  = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Removed'")->fetch_assoc()['c'];
$mj_ret  = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Returned - Paid'")->fetch_assoc()['c'];
$mj_canc = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Cancelled'")->fetch_assoc()['c'];

include 'layout/header.php';
?>

<!-- Charts Lib (Optional, keeping in case you add charts later) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-content">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-line text-danger"></i> Meter Ops Dashboard</h3>
            <p class="text-muted small mb-0">Overview for <strong><?php echo date('F Y'); ?></strong></p>
        </div>
        <div><span class="badge bg-light text-dark border px-3 py-2"><i class="fas fa-user-tie"></i> <?php echo $current_officer; ?></span></div>
    </div>

    <!-- MAIN SECTION: METER JOBS OVERVIEW -->
    <h6 class="text-secondary fw-bold text-uppercase border-bottom pb-2 mb-3"><i class="fas fa-tools text-dark"></i> Meter Removal Operations</h6>

    <!-- STAT CARDS (ROW 1) -->
    <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
        <!-- 1. PENDING LOCATIONS -->
        <div class="col">
            <div class="card shadow-sm bg-white h-100 p-2 border-start border-4 border-danger">
                <div class="card-body p-2 text-center">
                    <h2 class="mb-0 fw-bold text-danger"><?php echo $mj_loc; ?></h2>
                    <small class="text-muted fw-bold">PENDING LOCATIONS</small>
                </div>
            </div>
        </div>
        <!-- 2. TOTAL PENDING CARDS -->
        <div class="col">
            <div class="card shadow-sm bg-white h-100 p-2 border-start border-4 border-warning">
                <div class="card-body p-2 text-center">
                    <h2 class="mb-0 fw-bold text-dark"><?php echo $mj_pend; ?></h2>
                    <small class="text-muted fw-bold">PENDING CARDS</small>
                </div>
            </div>
        </div>
        <!-- 3. REMOVED -->
        <div class="col">
            <div class="card shadow-sm bg-white h-100 p-2 border-start border-4 border-primary">
                <div class="card-body p-2 text-center">
                    <h2 class="mb-0 fw-bold text-dark"><?php echo $mj_rem; ?></h2>
                    <small class="text-muted fw-bold">METERS REMOVED</small>
                </div>
            </div>
        </div>
        <!-- 4. PAID / RETURNED -->
        <div class="col">
            <div class="card shadow-sm bg-white h-100 p-2 border-start border-4 border-success">
                <div class="card-body p-2 text-center">
                    <h2 class="mb-0 fw-bold text-dark"><?php echo $mj_ret; ?></h2>
                    <small class="text-muted fw-bold">PAID / RETURNED</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Section (Keeping the design filled) -->
    <div class="row g-3">
        <!-- 1. Recent Meter Activity Table (Just top 5 latest meter jobs) -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-2 d-flex justify-content-between">
                    <span><i class="fas fa-history text-secondary"></i> Recently Added Jobs</span>
                    <a href="meter_jobs" class="btn btn-sm btn-link text-decoration-none p-0">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-center" style="font-size:13px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Job No</th>
                                    <th>Account</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rr = $conn->query("SELECT * FROM meter_removal ORDER BY id DESC LIMIT 5");
                                if ($rr->num_rows > 0) {
                                    while ($w = $rr->fetch_assoc()) {
                                        $bg = 'bg-secondary';
                                        if ($w['status'] == 'Pending') $bg = 'bg-warning text-dark';
                                        if ($w['status'] == 'Removed') $bg = 'bg-danger';
                                        if ($w['status'] == 'Returned - Paid') $bg = 'bg-success';

                                        echo "<tr>
                                        <td class='fw-bold text-danger'>{$w['job_no']}</td>
                                        <td>{$w['acc_no']}</td>
                                        <td>" . date('m-d', strtotime($w['created_at'])) . "</td>
                                        <td><span class='badge rounded-pill $bg'>{$w['status']}</span></td>
                                    </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-muted py-3'>No recent data</td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Action Area -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-laptop-medical fa-3x text-secondary mb-3 opacity-50"></i>
                    <h5 class="fw-bold">Ready for New Tools?</h5>
                    <p class="small text-muted mb-4">You can integrate other modules here.</p>
                    <a href="meter_jobs" class="btn btn-outline-dark w-100">Go to Meter Jobs</a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include 'layout/footer.php'; ?>