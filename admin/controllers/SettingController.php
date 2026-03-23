<?php
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

?>