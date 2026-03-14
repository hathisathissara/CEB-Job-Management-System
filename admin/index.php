<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include '../db_conn.php';
$current_officer = $_SESSION['full_name'];

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
}

// --- GET ACTIVE NOTICE ---
$notice = $conn->query("SELECT notice_text FROM system_settings WHERE is_active=1 AND id=1")->fetch_assoc();

include 'layout/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- LOADER HTML -->


<!-- WELCOME HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-line text-danger"></i> Master Dashboard</h3>
        <p class="text-muted small mb-0">Overview for <strong><?php echo date('F Y'); ?></strong></p>
    </div>
    <div>
        <span class="badge bg-white text-dark border px-3 py-2 shadow-sm"><i class="fas fa-user-circle text-success me-2"></i> <?php echo $current_officer; ?></span>
    </div>
</div>

<!-- SECTION 1: METER REMOVAL -->
<h6 class="text-secondary fw-bold text-uppercase border-bottom pb-2 mb-3"><i class="fas fa-tools text-dark me-2"></i> Removal Operations</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-bottom border-4 border-danger h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">PENDING LOCATIONS</h6>
                    <h2 class="fw-bold text-danger mb-0"><?php echo $mj_loc; ?></h2>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle"><i class="fas fa-map-marker-alt fa-2x text-danger"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-bottom border-4 border-warning h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">PENDING CARDS</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_pend; ?></h2>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle"><i class="fas fa-layer-group fa-2x text-warning"></i></div>
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
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle"><i class="fas fa-screwdriver fa-2x text-primary"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-bottom border-4 border-success h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">PAID / RETURNED</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mj_ret; ?></h2>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle"><i class="fas fa-check-circle fa-2x text-success"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: METER CHANGE (NEW) -->
<h6 class="text-secondary fw-bold text-uppercase border-bottom pb-2 mb-3 mt-4"><i class="fas fa-exchange-alt text-dark me-2"></i> Change Operations</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-dark h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">TOTAL REQUESTS</h6>
                    <h2 class="fw-bold text-dark mb-0"><?php echo $mc_all; ?></h2>
                </div>
                <div class="bg-dark bg-opacity-10 p-3 rounded-circle"><i class="fas fa-folder-open fa-2x text-dark"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">PENDING CHANGE</h6>
                    <h2 class="fw-bold text-warning mb-0"><?php echo $mc_pend; ?></h2>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle"><i class="fas fa-clock fa-2x text-warning"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small fw-bold mb-1">COMPLETED CHANGE</h6>
                    <h2 class="fw-bold text-success mb-0"><?php echo $mc_comp; ?></h2>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle"><i class="fas fa-check-double fa-2x text-success"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS & TABLES -->
<div class="row g-3">

    <!-- 1. WEEKLY TREND CHART (REMOVALS) -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-chart-area text-secondary me-2"></i> Weekly Job Intake (Removal vs Change)
            </div>
            <div class="card-body">
                <canvas id="trendChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- 2. RECENT ACTIVITY (MIXED) -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between">
                <span><i class="fas fa-history text-secondary me-2"></i> Recent Removals</span>
                <a href="meter_jobs" class="text-decoration-none small fw-bold">View All</a>
            </div>
            <div class="list-group list-group-flush">
                <?php
                $rec = $conn->query("SELECT * FROM meter_removal ORDER BY id DESC LIMIT 5");
                if ($rec->num_rows > 0) {
                    while ($r = $rec->fetch_assoc()) {
                        $badge = ($r['status'] == 'Pending') ? 'bg-warning' : (($r['status'] == 'Removed') ? 'bg-danger' : 'bg-success');
                        echo "<div class='list-group-item d-flex justify-content-between align-items-center small'>
                                    <div><span class='fw-bold text-dark'>{$r['job_no']}</span><br><span class='text-muted'>{$r['acc_no']}</span></div>
                                    <span class='badge $badge rounded-pill'>{$r['status']}</span>
                                  </div>";
                    }
                } else {
                    echo "<div class='p-4 text-center text-muted'>No recent activity</div>";
                }
                ?>
            </div>
        </div>
    </div>

</div>

</div>

<!-- CHART SCRIPT -->
<script>
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($week_labels); ?>,
            datasets: [{
                    label: 'Removal Jobs',
                    data: <?php echo json_encode($rem_data); ?>,
                    borderColor: '#d11212', // Red
                    backgroundColor: 'rgba(209, 18, 18, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Change Jobs',
                    data: <?php echo json_encode($chg_data); ?>,
                    borderColor: '#0d6efd', // Blue
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                } // Legend එක පෙන්නන්න
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<?php include 'layout/footer.php'; ?>