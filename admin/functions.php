<?php
// Function: Add System Log (With Correct Timezone)
function addLog($conn, $user, $type, $desc)
{
    if (!$conn) return;

    // Set Timezone to Sri Lanka (Server Side Fix)
    date_default_timezone_set('Asia/Colombo');
    $current_time = date('Y-m-d H:i:s'); // Get current LK time

    $user = $conn->real_escape_string($user);
    $type = $conn->real_escape_string($type);
    $desc = $conn->real_escape_string($desc);

    // Insert with explicitly calculated time
    $sql = "INSERT INTO activity_logs (user_name, action_type, description, log_time) VALUES ('$user', '$type', '$desc', '$current_time')";
    $conn->query($sql);
}
