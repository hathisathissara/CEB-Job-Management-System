
<?php
// Function: Add System Log
function addLog($conn, $user, $type, $desc) {
    if (!$conn) return;
    
    // Timezone Check
    date_default_timezone_set('Asia/Colombo');
    
    $user = $conn->real_escape_string($user);
    $type = $conn->real_escape_string($type);
    $desc = $conn->real_escape_string($desc);
    
    $sql = "INSERT INTO activity_logs (user_name, action_type, description) VALUES ('$user', '$type', '$desc')";
    $conn->query($sql);
}
?>