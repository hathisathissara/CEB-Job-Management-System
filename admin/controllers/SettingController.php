<?php
$msg = "";
$err = "";

// Include mailer for welcome emails
require_once __DIR__ . '/../PHPMailer/mailer.php';

// 1. UPDATE OWN PROFILE (All users — name & email, NOT role)
if (isset($_POST['update_my_profile'])) {
    $my_name  = $conn->real_escape_string(trim($_POST['my_fullname']));
    $my_email = $conn->real_escape_string(trim($_POST['my_email']));

    if (empty($my_name) || empty($my_email)) {
        $err = "Name and Email cannot be empty!";
    } else {
        // Check duplicate email (exclude self)
        $chk = $conn->query("SELECT id FROM users WHERE email='$my_email' AND id != $current_user_id");
        if ($chk->num_rows > 0) {
            $err = "That email is already used by another account!";
        } else {
            $conn->query("UPDATE users SET full_name='$my_name', email='$my_email' WHERE id=$current_user_id");
            addLog($conn, $current_officer, 'UPDATE', 'Updated own profile (name/email)');
            $msg = "Profile Updated Successfully!";
        }
    }
}

// 2. CHANGE PASS
if (isset($_POST['change_pass'])) {
    $current = $_POST['curr_pass']; $new = $_POST['new_pass'];
    $row = $conn->query("SELECT password FROM users WHERE id='$current_user_id'")->fetch_assoc();
    if (password_verify($current, $row['password'])) {
        if (strlen($new) < 4) { $err = "New password too short!"; } 
        else {
            $h = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$h' WHERE id='$current_user_id'");
            addLog($conn, $current_officer, 'UPDATE', 'Changed own password');
            $msg = "Password Updated!";
        }
    } else { $err = "Incorrect Current Password!"; }
}

// 3. ADD USER (Admin Only)
if (isset($_POST['add_user']) && $my_role == 'Super Admin') {
    $u           = $conn->real_escape_string(trim($_POST['u_u']));
    $e           = $conn->real_escape_string(trim($_POST['u_e']));
    $n           = $conn->real_escape_string(trim($_POST['u_n']));
    $plain_pass  = $_POST['u_p'];                              // keep plain copy for email
    $p           = password_hash($plain_pass, PASSWORD_DEFAULT);
    $r           = $conn->real_escape_string($_POST['u_r']);

    // Check duplicate username or email
    $chk = $conn->query("SELECT id FROM users WHERE username='$u' OR email='$e'");
    if ($chk->num_rows > 0) {
        $err = "Username or Email already exists!";
    } else {
        // Admin creations are auto-activated (is_active = 1)
        $conn->query("INSERT INTO users(username, email, password, full_name, role, is_active) VALUES ('$u', '$e', '$p', '$n', '$r', 1)");
        addLog($conn, $current_officer, 'INSERT', "Created new user: $u ($r)");

        // Send welcome email with login credentials
        $mail_sent = sendWelcomeCredentials($e, $n, $u, $plain_pass, $r);
        $msg = $mail_sent
            ? "User '<b>$n</b>' created & login credentials sent to <b>$e</b>!"
            : "User '<b>$n</b>' created, but failed to send welcome email. Check SMTP config.";
    }
}
// 3. EDIT USER ROLE, STATUS & EMAIL (Admin Only)
if (isset($_POST['edit_user_role']) && $my_role == 'Super Admin') {
    $edit_id   = intval($_POST['edit_id']);
    $new_role  = $conn->real_escape_string($_POST['e_role']);
    $new_status = intval($_POST['e_active']);
    $new_email = $conn->real_escape_string(trim($_POST['e_email']));

    if ($edit_id != $current_user_id) {
        // Check if email is already used by another user
        $chk = $conn->query("SELECT id FROM users WHERE email='$new_email' AND id != $edit_id");
        if ($chk->num_rows > 0) {
            $err = "That email is already in use by another account!";
        } else {
            $conn->query("UPDATE users SET role='$new_role', is_active=$new_status, email='$new_email' WHERE id=$edit_id");
            addLog($conn, $current_officer, 'UPDATE', "Edited user ID $edit_id (role/status/email)");
            $msg = "User Permissions & Email Updated!";
        }
    } else {
        $err = "You cannot edit your own permissions here!";
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

// Fetch current user info for profile display
$me = $conn->query("SELECT full_name, email, role, username FROM users WHERE id='$current_user_id'")->fetch_assoc();
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', trim($me['full_name'])))));
$initials = substr($initials, 0, 2);
?>