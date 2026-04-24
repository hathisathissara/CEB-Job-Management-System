<?php
$msg = "";
$err = "";

// Only Super Admin can access
if ($my_role !== 'Super Admin') {
    header('Location: settings');
    exit;
}

// Include mailer for account updates
require_once __DIR__ . '/../PHPMailer/mailer.php';

// EDIT USER ROLE, STATUS & EMAIL
if (isset($_POST['edit_user_role'])) {
    $edit_id    = intval($_POST['edit_id']);
    $new_role   = $conn->real_escape_string($_POST['e_role']);
    $new_status = intval($_POST['e_active']);
    $new_email  = $conn->real_escape_string(trim($_POST['e_email']));

    if ($edit_id != $current_user_id) {
        $chk = $conn->query("SELECT id FROM users WHERE email='$new_email' AND id != $edit_id");
        if ($chk->num_rows > 0) {
            $err = "That email is already in use by another account!";
        } else {
            // Fetch current user data for the email
            $user_q = $conn->query("SELECT full_name FROM users WHERE id=$edit_id");
            $user_data = $user_q->fetch_assoc();
            
            $conn->query("UPDATE users SET role='$new_role', is_active=$new_status, email='$new_email' WHERE id=$edit_id");
            addLog($conn, $current_officer, 'UPDATE', "Edited user ID $edit_id (role/status/email)");
            
            // Send email notification
            $status_text = ($new_status == 1) ? '✅ Active (Can Login)' : '⏸ Pending / Suspended';
            $mail_sent = sendAccountUpdateNotification($new_email, $user_data['full_name'], $new_role, $status_text);
            
            if ($mail_sent) {
                $msg = "User Permissions Updated & Notification Email Sent!";
            } else {
                $msg = "User Permissions Updated, but failed to send email. Check SMTP config.";
            }
        }
    } else {
        $err = "You cannot edit your own permissions here!";
    }
}

// DELETE USER
if (isset($_GET['del'])) {
    $d = intval($_GET['del']);
    if ($d != $current_user_id) {
        $conn->query("DELETE FROM users WHERE id=$d");
        addLog($conn, $current_officer, 'DELETE', "Deleted user ID $d");
        $msg = "User Deleted!";
    }
}
?>
