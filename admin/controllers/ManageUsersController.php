<?php
$msg = "";
$err = "";

// Only Super Admin can access
if ($my_role !== 'Super Admin') {
    header('Location: settings');
    exit;
}

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
            $conn->query("UPDATE users SET role='$new_role', is_active=$new_status, email='$new_email' WHERE id=$edit_id");
            addLog($conn, $current_officer, 'UPDATE', "Edited user ID $edit_id (role/status/email)");
            $msg = "User Permissions & Email Updated!";
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
