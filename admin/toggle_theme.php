<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) exit();
include '../db_conn.php';

$uid = $_SESSION['user_id'];
$current_theme = $_SESSION['theme'] ?? 'light';

// Switch Logic
$new_theme = ($current_theme == 'light') ? 'dark' : 'light';

// 1. Update DB
$conn->query("UPDATE users SET theme='$new_theme' WHERE id='$uid'");

// 2. Update Session (so no relogin needed)
$_SESSION['theme'] = $new_theme;

// 3. Go back
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: dashboard");
}
exit();
