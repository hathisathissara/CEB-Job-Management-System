<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

include '../db_conn.php';

// App එකෙන් එවන JSON Data කියවීම
$data = json_decode(file_get_contents("php://input"));

if (isset($data->username) && isset($data->password)) {
    $u = $conn->real_escape_string($data->username);
    $p = $data->password;

    $res = $conn->query("SELECT * FROM users WHERE username='$u'");

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (password_verify($p, $row['password'])) {
            // Login Success
            echo json_encode([
                "status" => "success",
                "message" => "Login Successful",
                "user_id" => $row['id'],
                "name" => $row['full_name'],
                "role" => $row['role']
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid Password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete Data"]);
}
