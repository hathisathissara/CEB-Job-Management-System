<?php
session_start();
include '../db_conn.php';

// 1. DB එකේ Token එක මකන්න (User logged in නම්)
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $conn->query("UPDATE users SET remember_token=NULL WHERE id='$uid'");
}

// 2. Browser එකේ Cookie එක මකන්න (Time එක පරණ කරන්න)
if (isset($_COOKIE['ceb_remember_token'])) {
    setcookie('ceb_remember_token', '', time() - 3600, "/"); // Time in past deletes it
}

// 3. Destroy Session
session_unset();
session_destroy();

header("Location: ../home");
exit();
