<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include '../db_conn.php';
$current_officer = $_SESSION['full_name'];

if ($_SESSION['role'] !== 'Super Admin') {
    // Show Access Denied Message and Stop Script
    echo "<div style='font-family:sans-serif; text-align:center; padding:50px; color:#d11212;'>
            <h1>⛔ Access Denied!</h1>
            <p>You do not have permission to view Audit Logs.</p>
            <a href='dashboard' style='padding:10px 20px; background:#333; color:white; text-decoration:none; border-radius:5px;'>Go Back to Dashboard</a>
          </div>";
    exit(); // IMPORTANT: Stops loading the rest of the page
}

include 'layout/header.php';
?>



<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-secondary"><i class="fas fa-history text-dark"></i> System Activity Logs</h3>
    <button class="btn btn-sm btn-outline-dark" onclick="window.location.reload();"><i class="fas fa-sync-alt"></i> Refresh</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Date & Time</th>
                        <th width="15%">User</th>
                        <th width="10%">Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get last 50 logs
                    $sql = "SELECT * FROM activity_logs ORDER BY id DESC LIMIT 50";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Badge Color
                            $cls = 'bg-secondary';
                            if ($row['action_type'] == 'LOGIN') $cls = 'bg-info text-dark';
                            if ($row['action_type'] == 'UPDATE') $cls = 'bg-warning text-dark';
                            if ($row['action_type'] == 'ADD') $cls = 'bg-success';
                    ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td class="text-muted small"><?php echo $row['log_time']; ?></td>
                                <td class="fw-bold"><?php echo $row['user_name']; ?></td>
                                <td><span class="badge <?php echo $cls; ?>"><?php echo $row['action_type']; ?></span></td>
                                <td><?php echo $row['description']; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No activities recorded yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white small text-muted">
        Showing last 50 records only for performance.
    </div>
</div>

</div>

<?php include 'layout/footer.php'; ?>