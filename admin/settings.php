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
            addLog($conn, $current_officer, 'UPDATE', 'Changed password');
            $msg = "Password Updated!";
        }
    } else {
        $err = "Current password incorrect!";
    }
}

// 2. ADD USER (Admin Only)
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

// 3. DELETE USER (Admin Only)
if (isset($_GET['del']) && $my_role == 'Super Admin') {
    $d = intval($_GET['del']);
    if ($d != $current_user_id) {
        $conn->query("DELETE FROM users WHERE id=$d");
        $msg = "User Deleted!";
    }
}

// 4. UPDATE SYSTEM NOTICE (Admin Only) 
if (isset($_POST['update_notice']) && $my_role == 'Super Admin') {
    $m = trim($_POST['notice_msg']);
    $act = isset($_POST['notice_status']) ? 1 : 0;
    if ($conn->query("UPDATE system_settings SET notice_text='$m', is_active='$act' WHERE id=1")) {
        $msg = "Notice Updated!";
    }
}

include 'layout/header.php';
?>



<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark"><i class="fas fa-sliders-h text-danger"></i> Settings & Control</h3>
        <p class="text-muted small mb-0">Manage system preferences and user accounts.</p>
    </div>
</div>

<!-- NOTIFICATIONS -->
<?php if ($msg) echo "<div class='alert alert-success border-0 shadow-sm alert-dismissible fade show'>$msg <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>
<?php if ($err) echo "<div class='alert alert-danger border-0 shadow-sm alert-dismissible fade show'>$err <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

<div class="row">

    <!-- LEFT SIDE MENU -->
    <div class="col-md-3 mb-4">

        <!-- 1. Theme Card -->
        <div class="card shadow-sm border-0 mb-3 bg-light">
            <div class="card-body text-center p-3">
                <?php $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light'; ?>
                <?php if ($theme == 'dark'): ?>
                    <i class="fas fa-moon fa-2x mb-2 text-warning"></i>
                    <form action="toggle_theme.php" method="POST"><button class="btn btn-outline-secondary btn-sm w-100">Switch to Light</button></form>
                <?php else: ?>
                    <i class="fas fa-sun fa-2x mb-2 text-warning"></i>
                    <form action="toggle_theme.php" method="POST"><button class="btn btn-dark btn-sm w-100">Switch to Dark</button></form>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. Settings Nav -->
        <div class="list-group shadow-sm border-0 rounded-3">
            <button onclick="switchTab('t1', this)" class="list-group-item list-group-item-action py-3 active fw-bold border-0">
                <i class="fas fa-lock me-2 text-muted"></i> Security
            </button>
            <?php if ($my_role == 'Super Admin'): ?>
                <button onclick="switchTab('t2', this)" class="list-group-item list-group-item-action py-3 fw-bold border-0">
                    <i class="fas fa-bullhorn me-2 text-muted"></i> Public Notice
                </button>
                <button onclick="switchTab('t3', this)" class="list-group-item list-group-item-action py-3 fw-bold border-0">
                    <i class="fas fa-users-cog me-2 text-muted"></i> User Manager
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT SIDE CONTENT -->
    <div class="col-md-9">

        <!-- TAB 1: PASSWORD CHANGE -->
        <div id="t1" class="tab-pane d-block animate__animated animate__fadeIn">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">CHANGE PASSWORD</h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Current Password</label>
                                <input type="password" name="curr_pass" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-success">New Password</label>
                                <input type="password" name="new_pass" class="form-control" placeholder="Min 4 chars" required>
                            </div>
                        </div>
                        <div class="mt-2"><button name="change_pass" class="btn btn-warning fw-bold px-4">Update Password</button></div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($my_role == 'Super Admin'): ?>
            <!-- TAB 2: SYSTEM NOTICE (For Admins Only) -->
            <?php $set = $conn->query("SELECT * FROM system_settings WHERE id=1")->fetch_assoc(); ?>
            <div id="t2" class="tab-pane d-none animate__animated animate__fadeIn">
                <div class="card shadow-sm border-0 border-start border-4 border-warning">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-warning">MANAGE PUBLIC ANNOUNCEMENT</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Message Content</label>
                                <textarea name="notice_msg" class="form-control" rows="3"><?php echo htmlspecialchars($set['notice_text']); ?></textarea>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="notice_status" value="1" id="sw" <?php if ($set['is_active']) echo 'checked'; ?>>
                                <label class="form-check-label small" for="sw">Display notice to users?</label>
                            </div>
                            <button name="update_notice" class="btn btn-dark btn-sm">Save Notice</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 3: USER MANAGEMENT -->
            <div id="t3" class="tab-pane d-none animate__animated animate__fadeIn">

                <!-- Create User Card -->
                <div class="card shadow-sm border-0 mb-4 border-top border-4 border-success">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 text-success">REGISTER NEW OFFICER</h6>
                        <form method="POST">
                            <div class="row g-2">
                                <div class="col-md-3"><input type="text" name="u_n" class="form-control form-control-sm" placeholder="Full Name" required></div>
                                <div class="col-md-3"><input type="text" name="u_u" class="form-control form-control-sm" placeholder="Username" required></div>
                                <div class="col-md-3"><input type="text" name="u_p" class="form-control form-control-sm" placeholder="Password" required></div>
                                <div class="col-md-2">
                                    <select name="u_r" class="form-select form-select-sm">
                                        <option value="Officer">Officer</option>
                                        <option value="Super Admin">Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-1"><button name="add_user" class="btn btn-success btn-sm w-100"><i class="fas fa-plus"></i></button></div>
                            </div>
                        </form>
                    </div>
                </div>

               <!-- Existing Users Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                        <small class="fw-bold text-muted text-uppercase"><i class="fas fa-users text-primary me-2"></i> Existing Accounts</small>
                        <span class="badge bg-light text-dark border shadow-sm">
                            <?php echo $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c']; ?> Users
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="table-dark text-uppercase small" style="letter-spacing: 0.5px;">
                                <tr>
                                    <th class="text-start ps-4" style="width: 40%;">Officer Name</th>
                                    <th style="width: 25%;">Username</th>
                                    <th style="width: 20%;">Access Level</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $res = $conn->query("SELECT * FROM users ORDER BY role ASC, full_name ASC");
                                while ($rw = $res->fetch_assoc()) {
                                    // Badge Colors
                                    $role_bg = ($rw['role'] == 'Super Admin') ? 'bg-danger' : 'bg-secondary';
                                    
                                    // Delete Button Logic
                                    if ($rw['id'] == $current_user_id) {
                                        $btn_del = '<span class="badge bg-light text-muted border px-2 py-1"><i class="fas fa-user-check me-1"></i> Current</span>';
                                    } else {
                                        $btn_del = "<a href='settings.php?del={$rw['id']}&t=3' onclick=\"return confirm('Are you sure you want to completely remove this user?');\" class='btn btn-sm btn-outline-danger py-0 px-2' title='Delete User'>
                                                        <i class='fas fa-trash-alt'></i>
                                                    </a>";
                                    }
                                ?>
                                    <tr>
                                        <!-- Column 1: Name -->
                                        <td class="text-start ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center border shadow-sm me-3" style="width: 35px; height: 35px;">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </div>
                                                <span class="fw-bold text-dark fs-6"><?php echo $rw['full_name']; ?></span>
                                            </div>
                                        </td>
                                        
                                        <!-- Column 2: Username -->
                                        <td>
                                            <span class="text-secondary fw-bold">@<?php echo $rw['username']; ?></span>
                                        </td>
                                        
                                        <!-- Column 3: Role -->
                                        <td>
                                            <span class="badge <?php echo $role_bg; ?> rounded-pill px-3 py-1 shadow-sm">
                                                <?php echo $rw['role']; ?>
                                            </span>
                                        </td>
                                        
                                        <!-- Column 4: Action -->
                                        <td>
                                            <?php echo $btn_del; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
</div>

<script>
    function switchTab(id, el) {
        document.querySelectorAll('.tab-pane').forEach(d => d.classList.replace('d-block', 'd-none'));
        document.getElementById(id).classList.replace('d-none', 'd-block');
        document.querySelectorAll('.list-group-item').forEach(b => b.classList.remove('active'));
        el.classList.add('active');

        // Optional: Save tab state (Requires page param handling)
    }
    // Keep User Tab Open if param 't=3' is set (after delete)
    <?php if (isset($_GET['t']) && $_GET['t'] == 3): ?>
        window.onload = function() {
            document.querySelectorAll('.list-group-item')[2].click();
        }
    <?php endif; ?>
</script>

<!-- ANIMATE CSS (Optional) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<?php include 'layout/footer.php'; ?>