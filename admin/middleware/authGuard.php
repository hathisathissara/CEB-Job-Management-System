<?php
// 1. Session එක Start කරලා නැත්නම් විතරක් Start කරන්න. (Error වැළැක්වීමට)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Security Check (Logged In ද කියලා බලනවා)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php"); // හෝ login ලින්ක් එක
    exit();
}

// 3. Database සහ Functions සම්බන්ධ කිරීම
// (Middleware එක Load වෙන්නේ admin folder එකේ පිටු වලින් නිසා Path එක `../` වෙන්න ඕන)
include_once '../db_conn.php'; 
include_once 'functions.php';

// 4. පොදුවේ පාවිච්චි වෙන විචල්‍යයන් (Variables) සෑදීම
$current_officer = $_SESSION['full_name'];
$current_user_id = $_SESSION['user_id'];
$my_role = $_SESSION['role'];

// 5. Timezone එක ලංකාවට හැදීම
date_default_timezone_set('Asia/Colombo');
?>