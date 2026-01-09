<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include '../db_conn.php';
include 'functions.php';

$current_user_id = $_SESSION['user_id'];
$current_officer = $_SESSION['full_name'];
$my_role = $_SESSION['role'];
$msg = "";
$err = "";

// 1. CHANGE PASS
if (isset($_POST['change_pass'])) {
    $current = $_POST['curr_pass'];
    $new = $_POST['new_pass'];
    $row = $conn->query("SELECT password FROM users WHERE id='$current_user_id'")->fetch_assoc();
    if (password_verify($current, $row['password'])) {
        if (strlen($new) < 4) {
            $err = "New password too short!";
        } else {
            $h = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$h' WHERE id='$current_user_id'");
            addLog($conn, $current_officer, 'UPDATE', 'Changed own password');
            $msg = "Password Updated!";
        }
    } else {
        $err = "Incorrect Current Password!";
    }
}

// 2. ADD USER
if (isset($_POST['add_user']) && $my_role == 'Super Admin') {
    $u = trim($_POST['u_u']);
    $n = trim($_POST['u_n']);
    $p = password_hash($_POST['u_p'], PASSWORD_DEFAULT);
    $r = $_POST['u_r'];
    if ($conn->query("SELECT id FROM users WHERE username='$u'")->num_rows > 0) {
        $err = "Username exists!";
    } else {
        $conn->query("INSERT INTO users(username,password,full_name,role)VALUES('$u','$p','$n','$r')");
        $msg = "User Added!";
    }
}

// 3. DELETE USER
if (isset($_GET['del']) && $my_role == 'Super Admin') {
    $d = intval($_GET['del']);
    if ($d != $current_user_id) {
        $conn->query("DELETE FROM users WHERE id=$d");
        $msg = "User Deleted!";
    }
}

include 'layout/header.php';
?>

<div class="main-content">

    <div class="mb-4">
        <h3 class="fw-bold mb-0 text-dark"><i class="fas fa-cog text-secondary"></i> System Settings</h3>
        <p class="text-muted small">Manage account security and user access levels.</p>
    </div>

    <!-- Message Alerts -->
    <?php if ($msg) echo "<div class='alert alert-success border-0 shadow-sm mb-4'><i class='fas fa-check-circle me-2'></i> $msg</div>"; ?>
    <?php if ($err) echo "<div class='alert alert-danger border-0 shadow-sm mb-4'><i class='fas fa-exclamation-triangle me-2'></i> $err</div>"; ?>

    <div class="row g-4">

        <!-- LEFT SIDE: TABS (Updated) -->
        <div class="col-md-3">

            <!-- 1. APPEARANCE CARD (THEME SWITCHER) -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body text-center p-4">
                    <h6 class="text-muted small fw-bold mb-3">CURRENT THEME</h6>

                    <?php
                    // Check if theme is set, default to light
                    $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';

                    if ($theme == 'dark'):
                    ?>
                        <div class="mb-3 text-warning"><i class="fas fa-moon fa-3x"></i></div>
                        <h5 class="fw-bold mb-3">Dark Mode</h5>
                        <form action="toggle_theme.php" method="POST">
                            <button class="btn btn-outline-light w-100 border-secondary text-muted">Switch to Light</button>
                        </form>
                    <?php else: ?>
                        <div class="mb-3 text-warning"><i class="fas fa-sun fa-3x"></i></div>
                        <h5 class="fw-bold mb-3">Light Mode</h5>
                        <form action="toggle_theme.php" method="POST">
                            <button class="btn btn-dark w-100">Switch to Dark</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. MENU LINKS -->
            <div class="card shadow-sm border-0">
                <div class="list-group list-group-flush rounded-3">
                    <a href="#" class="list-group-item list-group-item-action active fw-bold py-3" onclick="showTab('tab1', this)">
                        <i class="fas fa-user-shield me-2"></i> Account Security
                    </a>

                    <?php if ($my_role == 'Super Admin'): ?>
                        <a href="#" class="list-group-item list-group-item-action py-3 text-muted" onclick="showTab('tab2', this)">
                            <i class="fas fa-user-plus me-2"></i> Add New Officer
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-3 text-muted" onclick="showTab('tab3', this)">
                            <i class="fas fa-users-cog me-2"></i> Manage Users
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: CONTENT AREAS -->
        <div class="col-md-9">

            <!-- TAB 1: PASSWORD CHANGE (Default) -->
            <div id="tab1" class="tab-content">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 fw-bold text-uppercase small text-muted border-bottom-0">Account Password</div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="curr_pass" class="form-control border-start-0" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-success">New Password</label>
                                <input type="password" name="new_pass" class="form-control" required placeholder="Min 4 chars">
                            </div>
                            <button name="change_pass" class="btn btn-dark w-100 fw-bold">Update My Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <?php if ($my_role == 'Super Admin'): ?>
                <!-- TAB 2: CREATE USER (Hidden) -->
                <div id="tab2" class="tab-content" style="display:none;">
                    <div class="card shadow-sm border-0 h-100 border-start border-4 border-success">
                        <div class="card-header bg-white py-3 fw-bold text-uppercase text-success border-bottom-0">Register New Officer</div>
                        <div class="card-body p-4">
                            <form method="POST">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6"><label class="small fw-bold">Full Name</label><input type="text" name="u_n" class="form-control" required></div>
                                    <div class="col-md-6"><label class="small fw-bold">Access Level</label><select name="u_r" class="form-select">
                                            <option value="Officer">Standard Officer</option>
                                            <option value="Super Admin">Super Admin</option>
                                        </select></div>
                                </div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6"><label class="small fw-bold">Username</label><input type="text" name="u_u" class="form-control" required></div>
                                    <div class="col-md-6"><label class="small fw-bold">Initial Password</label><input type="text" name="u_p" class="form-control" required></div>
                                </div>
                                <button name="add_user" class="btn btn-success text-white w-100 fw-bold">Create Account</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: USER LIST (Hidden) -->
                <div id="tab3" class="tab-content" style="display:none;">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-uppercase small text-muted">System Officers</span>
                            <span class="badge bg-secondary"><?php echo $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c']; ?> Users</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $uu = $conn->query("SELECT * FROM users");
                                        while ($rw = $uu->fetch_assoc()) {
                                            $badge = ($rw['role'] == 'Super Admin') ? 'bg-danger' : 'bg-primary';
                                            $del = ($rw['id'] == $current_user_id) ? '<span class="text-muted small">Current</span>' : "<a href='settings.php?del={$rw['id']}' onclick=\"return confirm('Delete user?');\" class='btn btn-sm btn-outline-danger'><i class='fas fa-trash'></i></a>";
                                        ?>
                                            <tr>
                                                <td class="fw-bold text-start ps-4"><?php echo $rw['full_name']; ?></td>
                                                <td><?php echo $rw['username']; ?></td>
                                                <td><span class="badge rounded-pill <?php echo $badge; ?> small" style="font-size:10px;"><?php echo $rw['role']; ?></span></td>
                                                <td><?php echo $del; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- SIMPLE JS TAB LOGIC -->
<script>
    function showTab(tabId, btn) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        // Show selected content
        document.getElementById(tabId).style.display = 'block';

        // Update active menu link
        document.querySelectorAll('.list-group-item').forEach(el => el.classList.remove('active', 'fw-bold'));
        btn.classList.add('active', 'fw-bold');
    }
</script>

<?php include 'layout/footer.php'; ?>