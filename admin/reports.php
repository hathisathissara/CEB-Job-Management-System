<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include '../db_conn.php';
$current_officer = $_SESSION['full_name'];
include 'layout/header.php';
?>


<div class="mb-4 border-bottom pb-2">
    <h3 class="fw-bold text-dark"><i class="fas fa-file-alt text-success"></i> Reports Center</h3>
    <p class="text-muted small">Generate and download CSV reports.</p>
</div>

<div class="row">

    <!-- REPORT TYPE 1: METER REMOVAL -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 border-top border-4 border-danger h-100">
            <div class="card-header bg-white fw-bold py-3"><i class="fas fa-tools me-2 text-danger"></i> Meter Removal Reports</div>
            <div class="card-body p-4">
                <form action="export_meter.php" method="GET">

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">Date Range (Created At)</label>
                        <div class="input-group">
                            <input type="date" name="d1" class="form-control" required>
                            <span class="input-group-text">-</span>
                            <input type="date" name="d2" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">Status</label>
                            <select name="f" class="form-select">
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Removed">Removed</option>
                                <option value="Returned - Paid">Returned</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">Options</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="dup" value="1" id="remDup">
                                <label class="form-check-label small" for="remDup">Unique Accounts Only</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 fw-bold shadow-sm">
                        <i class="fas fa-download me-2"></i> Download Removal CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- REPORT TYPE 2: METER CHANGE -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 border-top border-4 border-primary h-100">
            <div class="card-header bg-white fw-bold py-3"><i class="fas fa-exchange-alt me-2 text-primary"></i> Meter Change Reports</div>
            <div class="card-body p-4">
                <form action="export_change.php" method="GET">

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">Date Range</label>
                        <div class="input-group">
                            <input type="date" name="d1" class="form-control" required>
                            <span class="input-group-text">-</span>
                            <input type="date" name="d2" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">Status</label>
                            <select name="f" class="form-select">
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted">Phase</label>
                            <select name="p" class="form-select">
                                <option value="">All Phases</option>
                                <option value="Single Phase">Single Phase</option>
                                <option value="Three Phase">Three Phase</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                        <i class="fas fa-download me-2"></i> Download Change CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
</div>

<?php include 'layout/footer.php'; ?>