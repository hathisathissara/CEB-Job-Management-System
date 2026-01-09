<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include '../db_conn.php';
$current_user_id = $_SESSION['user_id'];
$current_officer = $_SESSION['full_name'];
$my_role = $_SESSION['role']; // Get Role
if (isset($_GET['del_id']) && $my_role == 'Super Admin') {
    $del_id = intval($_GET['del_id']); // Security: Convert to integer

    // Prevent Deleting Your Own Self
    if ($del_id != $current_user_id) {
        $conn->query("DELETE FROM users WHERE id=$del_id");
        $msg = "User removed successfully!";
    } else {
        $err = "You cannot delete your own account!";
    }
}
$msg = "";
$err = "";

// 1. CHANGE MY PASSWORD (හැමෝටම පුළුවන්)
if (isset($_POST['change_pass'])) {
    $new_pass = $_POST['new_pass'];
    if (strlen($new_pass) < 4) {
        $err = "Password too short!";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='$hashed' WHERE id='$current_user_id'";
        if ($conn->query($sql)) {
            $msg = "Your password updated successfully!";
        }
    }
}

// 2. ADD USER (Admin විතරයි)
if (isset($_POST['add_user']) && $my_role == 'Super Admin') {
    $u_user = trim($_POST['u_username']);
    $u_pass = password_hash($_POST['u_password'], PASSWORD_DEFAULT);
    $u_name = trim($_POST['u_fullname']);
    $u_role = $_POST['u_role']; // Role selection (New Feature)

    $check = $conn->query("SELECT * FROM users WHERE username='$u_user'");
    if ($check->num_rows > 0) {
        $err = "Username already taken!";
    } else {
        $sql2 = "INSERT INTO users (username, password, full_name, role) VALUES ('$u_user', '$u_pass', '$u_name', '$u_role')";
        if ($conn->query($sql2)) {
            $msg = "New User Created!";
        }
    }
}

include 'layout/header.php';
?>

<div class="main-content">
    <h3 class="mb-4 fw-bold"><i class="fas fa-cog text-secondary"></i> My Profile</h3>

    <?php if ($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
    <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>

    <div class="row g-4">

        <!-- 1. CHANGE PASSWORD CARD (Open for Everyone) -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white fw-bold"><i class="fas fa-key"></i> Change My Password</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3"><label>User:</label><input type="text" class="form-control" value="<?php echo $current_officer; ?> (<?php echo $my_role; ?>)" disabled></div>
                        <div class="mb-3"><label>New Password:</label><input type="password" name="new_pass" class="form-control" required></div>
                        <button type="submit" name="change_pass" class="btn btn-warning text-dark w-100 fw-bold">Update</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. ADD NEW OFFICER CARD (ONLY FOR SUPER ADMIN) -->
        <?php if ($my_role == 'Super Admin'): ?>
            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-success" style="border-top:4px solid #198754">
                    <div class="card-header bg-white text-success fw-bold"><i class="fas fa-user-plus"></i> Create New User</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-2"><label class="small fw-bold">Name:</label><input type="text" name="u_fullname" class="form-control" required></div>
                            <div class="row">
                                <div class="col-6 mb-3"><label class="small fw-bold">Username:</label><input type="text" name="u_username" class="form-control" required></div>
                                <div class="col-6 mb-3"><label class="small fw-bold">Role:</label>
                                    <select name="u_role" class="form-select">
                                        <option value="Officer">Officer</option>
                                        <option value="Super Admin">Super Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3"><label class="small fw-bold">Password:</label><input type="text" name="u_password" class="form-control" required></div>
                            <button type="submit" name="add_user" class="btn btn-success w-100 fw-bold">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- Table of Existing Users (Admin View Only) -->
        <?php if ($my_role == 'Super Admin'): ?>
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-white fw-bold"><i class="fas fa-users text-primary"></i> Current User List</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM users ORDER BY id ASC");
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        // Highlight "Super Admin" Badge
                                        $role_badge = ($row['role'] == 'Super Admin') ?
                                            '<span class="badge bg-danger">Admin</span>' :
                                            '<span class="badge bg-secondary">Officer</span>';

                                        // Prevent Deleting Logged In User
                                        $del_btn = '';
                                        if ($row['id'] != $current_user_id) {
                                            // Add onclick confirmation for safety
                                            $del_btn = '<a href="settings.php?del_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to REMOVE this user?\');" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>';
                                        } else {
                                            $del_btn = '<span class="text-muted small italic">(Current)</span>';
                                        }

                                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td class='fw-bold'>{$row['full_name']}</td>
                                <td>{$row['username']}</td>
                                <td>{$role_badge}</td>
                                <td>{$del_btn}</td>
                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center p-3'>No users found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'layout/footer.php'; ?>