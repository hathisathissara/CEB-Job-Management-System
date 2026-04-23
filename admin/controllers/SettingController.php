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

// 3. UPDATE NOTICE
// --- 5. UPDATE SYSTEM NOTICE (Super Admin Only) ---
if (isset($_POST['update_notice']) && $my_role == 'Super Admin') {
    $notice_text = $conn->real_escape_string(trim($_POST['notice_msg']));
    $is_active = isset($_POST['notice_status']) ? 1 : 0; 
    $sql = "UPDATE system_settings SET notice_text='$notice_text', is_active='$is_active' WHERE id=1";
    if ($conn->query($sql)) {
        addLog($conn, $current_officer, 'UPDATE NOTICE', 'Updated Public Notification Bar');
        $msg = "System Notice Updated Successfully!";
    } else {
        $err = "Failed to update notice: " . $conn->error;
    }
}


// Fetch current user info for profile display
$me = $conn->query("SELECT full_name, email, role, username FROM users WHERE id='$current_user_id'")->fetch_assoc();
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', trim($me['full_name'])))));
$initials = substr($initials, 0, 2);
?>