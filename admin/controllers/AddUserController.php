<?php
$msg = "";
$err = "";

// Only Super Admin can access
if ($my_role !== 'Super Admin') {
    header('Location: settings');
    exit;
}

// Include mailer for welcome emails
require_once __DIR__ . '/../PHPMailer/mailer.php';

// ADD USER
if (isset($_POST['add_user'])) {
    $u           = $conn->real_escape_string(trim($_POST['u_u']));
    $e           = $conn->real_escape_string(trim($_POST['u_e']));
    $n           = $conn->real_escape_string(trim($_POST['u_n']));
    $plain_pass  = $_POST['u_p'];
    $p           = password_hash($plain_pass, PASSWORD_DEFAULT);
    $r           = $conn->real_escape_string($_POST['u_r']);

    $chk = $conn->query("SELECT id FROM users WHERE username='$u' OR email='$e'");
    if ($chk->num_rows > 0) {
        $err = "Username or Email already exists!";
    } else {
        $conn->query("INSERT INTO users(username, email, password, full_name, role, is_active) VALUES ('$u', '$e', '$p', '$n', '$r', 1)");
        addLog($conn, $current_officer, 'INSERT', "Created new user: $u ($r)");

        $mail_sent = sendWelcomeCredentials($e, $n, $u, $plain_pass, $r);
        $msg = $mail_sent
            ? "User '<b>$n</b>' created &amp; login credentials sent to <b>$e</b>!"
            : "User '<b>$n</b>' created, but failed to send welcome email. Check SMTP config.";
    }
}
?>
