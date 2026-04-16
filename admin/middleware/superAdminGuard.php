<?php
// First, load the general Auth Guard
require_once 'authGuard.php';

// Then check if the user is a Super Admin
if ($my_role !== 'Super Admin') {
    echo "<div style='font-family:sans-serif; text-align:center; padding:50px; color:#d11212;'>
            <h1>⛔ Access Denied!</h1>
            <p>You do not have permission to view this page.</p>
            <a href='dashboard' style='padding:10px 20px; background:#333; color:white; text-decoration:none; border-radius:5px;'>Go Back</a>
          </div>";
    exit();
}
?>