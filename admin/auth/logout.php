<?php
session_start();
include '../../config/db_conn.php';

// 1. Clear the token in the DB (If user is logged in)
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $conn->query("UPDATE users SET remember_token=NULL WHERE id='$uid'");
}

// 2. Delete the browser Cookie (Set time to the past)
if (isset($_COOKIE['ceb_remember_token'])) {
    setcookie('ceb_remember_token', '', time() - 3600, "/"); // Time in past deletes it
}

// 3. Destroy Session
session_unset();
session_destroy();

header("Location: ../home");
exit();
