<?php
// ============================================
// 1. AUTH MIDDLEWARE (Security, DB, Session Vars)
// ============================================
require_once '../middleware/authGuard.php';

// ============================================
// 2. CONTROLLER LOGIC (Dashboard Stats)
// ============================================
require_once '../controllers/IndexController.php';

include '../layout/header.php';
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

<style>
/* PortPro style card layout with original colors */
.port-card {
    border-radius: 16px !important;
    padding: 24px;
    height: 100%;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
}
.port-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.port-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    font-size: 16px;
    z-index: 2;
}
.port-title {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}
.port-value {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 5px;
    line-height: 1;
    z-index: 2;
}
.port-sub {
    font-size: 12px;
    opacity: 0.7;
    margin-top: auto;
    z-index: 2;
}
/* A faint background shape to mimic PortPro card graphics */
.port-bg-shape {
    position: absolute;
    top: -30px;
    right: -30px;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    opacity: 0.05;
    z-index: 0;
}
</style>

<!-- SECTION 1: METER REMOVAL -->
<h6 class="text-secondary fw-bold text-uppercase border-bottom pb-2 mb-3"><i class="fas fa-tools text-dark me-2"></i> Removal Operations</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-danger border-bottom border-4 port-card">
            <div class="port-bg-shape bg-danger"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="port-title text-secondary">PENDING LOCATIONS</div>
                <div class="port-value text-danger mb-0"><?php echo $mj_loc; ?></div>
                <div class="port-sub text-danger"><i class="fas fa-exclamation-circle me-1"></i> Removal Ops</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-warning border-bottom border-4 port-card">
            <div class="port-bg-shape bg-warning"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="port-title text-secondary">PENDING CARDS</div>
                <div class="port-value text-dark mb-0"><?php echo $mj_pend; ?></div>
                <div class="port-sub text-warning"><i class="fas fa-clock me-1"></i> Awaiting return</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-primary border-bottom border-4 port-card">
            <div class="port-bg-shape bg-primary"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-screwdriver"></i>
                </div>
                <div class="port-title text-secondary">REMOVED</div>
                <div class="port-value text-dark mb-0"><?php echo $mj_rem; ?></div>
                <div class="port-sub text-primary"><i class="fas fa-check me-1"></i> Successfully uninstalled</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-success border-bottom border-4 port-card">
            <div class="port-bg-shape bg-success"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="port-title text-secondary">PAID / RETURNED</div>
                <div class="port-value text-dark mb-0"><?php echo $mj_ret; ?></div>
                <div class="port-sub text-success"><i class="fas fa-check-double me-1"></i> Closed operations</div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: METER CHANGE (NEW) -->
<h6 class="text-secondary fw-bold text-uppercase border-bottom pb-2 mb-3 mt-4"><i class="fas fa-exchange-alt text-dark me-2"></i> Change Operations</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-dark border-start border-4 port-card">
            <div class="port-bg-shape bg-dark"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-dark bg-opacity-10 text-dark">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="port-title text-secondary">TOTAL REQUESTS</div>
                <div class="port-value text-dark mb-0"><?php echo $mc_all; ?></div>
                <div class="port-sub text-muted"><i class="fas fa-list me-1"></i> Overall volume</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-warning border-start border-4 port-card">
            <div class="port-bg-shape bg-warning"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="port-title text-secondary">PENDING CHANGE</div>
                <div class="port-value text-warning mb-0"><?php echo $mc_pend; ?></div>
                <div class="port-sub text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Needs attention</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-success border-start border-4 port-card">
            <div class="port-bg-shape bg-success"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="port-title text-secondary">COMPLETED CHANGE</div>
                <div class="port-value text-success mb-0"><?php echo $mc_comp; ?></div>
                <div class="port-sub text-success"><i class="fas fa-check-circle me-1"></i> Changed successfully</div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: NEW CONNECTIONS -->
<h6 class="text-secondary fw-bold text-uppercase border-bottom pb-2 mb-3 mt-4"><i class="fas fa-plug text-primary me-2"></i> New Connection Applications <a href="new_services" class="btn btn-sm btn-outline-primary ms-2 text-decoration-none" style="font-size:11px; text-transform:none; font-weight:600;">View All →</a></h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-info border-bottom border-4 port-card">
            <div class="port-bg-shape bg-info"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="port-title text-secondary">ACTIVE FILES</div>
                <div class="port-value text-dark mb-0"><?php echo $nc_active; ?></div>
                <div class="port-sub text-info"><i class="fas fa-spinner me-1"></i> Not completed yet</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-danger border-bottom border-4 port-card">
            <div class="port-bg-shape bg-danger"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="port-title text-secondary">SHORTCOMINGS</div>
                <div class="port-value text-danger mb-0"><?php echo $nc_adu; ?></div>
                <div class="port-sub text-danger"><i class="fas fa-times-circle me-1"></i> Adu Padu</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-warning border-bottom border-4 port-card">
            <div class="port-bg-shape bg-warning"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="port-title text-secondary">PENDING APPROVAL</div>
                <div class="port-value text-warning mb-0"><?php echo $nc_appr; ?></div>
                <div class="port-sub text-warning"><i class="fas fa-clock me-1"></i> At Area Office</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-success border-bottom border-4 port-card">
            <div class="port-bg-shape bg-success"></div>
            <div style="position:relative; z-index:1; display:flex; flex-direction:column; height:100%;">
                <div class="port-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-hammer"></i>
                </div>
                <div class="port-title text-secondary">JOBS CREATED</div>
                <div class="port-value text-success mb-0"><?php echo $nc_job; ?></div>
                <div class="port-sub text-success"><i class="fas fa-tools me-1"></i> Fee paid, pending connection</div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS & TABLES -->
<div class="row g-3 mb-4">
    <!-- 1. WEEKLY TREND CHART (INTAKE) -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-chart-area text-secondary me-2"></i> Weekly Job Intake
            </div>
            <div class="card-body">
                <canvas id="trendChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- 2. WEEKLY TREND CHART (COMPLETED) -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-chart-line text-success me-2"></i> Weekly Job Completed
            </div>
            <div class="card-body">
                <canvas id="compChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- 3. RECENT ACTIVITY (MIXED) -->
    <div class="col-md-12">
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
    const chartOptions = {
        responsive: true,
        plugins: { legend: { display: true, position: 'top' } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    };

    // Intake Chart
    const ctx1 = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($week_labels); ?>,
            datasets: [
                {
                    label: 'Removal Intake',
                    data: <?php echo json_encode($rem_data); ?>,
                    borderColor: '#d11212',
                    backgroundColor: 'rgba(209, 18, 18, 0.1)',
                    tension: 0.4, fill: true
                },
                {
                    label: 'Change Intake',
                    data: <?php echo json_encode($chg_data); ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4, fill: true
                }
            ]
        },
        options: chartOptions
    });

    // Completed Chart
    const ctx2 = document.getElementById('compChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($week_labels); ?>,
            datasets: [
                {
                    label: 'Removal Completed',
                    data: <?php echo json_encode($rem_comp_data); ?>,
                    borderColor: '#198754', // Success Green
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.4, fill: true
                },
                {
                    label: 'Change Completed',
                    data: <?php echo json_encode($chg_comp_data); ?>,
                    borderColor: '#0dcaf0', // Cyan/Info
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    tension: 0.4, fill: true
                }
            ]
        },
        options: chartOptions
    });
</script>
<?php include '../layout/footer.php'; ?>