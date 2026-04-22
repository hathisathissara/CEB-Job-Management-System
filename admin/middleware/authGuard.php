<?php
// 1. Start the session only if it hasn't been started yet (to prevent errors)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Security Check (Check if the user is logged in)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login"); // or login link
    exit();
}

// 3. Connect Database and Functions
// (Middleware is loaded from pages within the admin folder, so the path should be '../')
include_once '../../config/db_conn.php'; 
include_once '../functions.php';

// 4. Define commonly used variables
$current_officer = $_SESSION['full_name'];
$current_user_id = $_SESSION['user_id'];
$my_role = $_SESSION['role'];

// 5. Set the timezone to Sri Lanka
date_default_timezone_set('Asia/Colombo');
?>