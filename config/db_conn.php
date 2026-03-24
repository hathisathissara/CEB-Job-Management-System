<?php
date_default_timezone_set('Asia/Colombo');
$servername = "localhost";
$username = "root";        // XAMPP use කරනවා නම් සාමාන්‍යයෙන් 'root'
$password = "";            // XAMPP වල default password එක හිස්
$dbname = "ceb_project";   // උඩ සාදාගත් database නම

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
