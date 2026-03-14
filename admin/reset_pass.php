<?php
include '../db_conn.php';
$message = "";

if (isset($_POST['reset_btn'])) {
    $target_user = $_POST['username'];
    $new_raw_pass = $_POST['new_password'];

    if (!empty($target_user) && !empty($new_raw_pass)) {

        // මේ ෆන්ෂන් එකෙන් ඔයාගේ සර්වර් එකට ගැලපෙන විදියට හරියටම HASH එක හැදෙනවා
        $new_hash = password_hash($new_raw_pass, PASSWORD_DEFAULT);

        // Database එක Update කිරීම
        $sql = "UPDATE users SET password = '$new_hash' WHERE username = '$target_user'";

        if ($conn->query($sql) === TRUE) {
            $message = "<div style='color:green; background:#d4edda; padding:10px; border:1px solid green;'>✅ Success! Password for user '<b>$target_user</b>' updated. You can login now.</div>";
        } else {
            $message = "<div style='color:red;'>Error updating: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div style='color:red;'>Please enter a password!</div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Emergency Password Reset</title>
    <style>
        body {
            font-family: sans-serif;
            background: #eee;
            padding: 50px;
            text-align: center;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 400px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #d11212;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #333;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #555;
        }
    </style>
</head>

<body>

    <div class="box">
        <h2 style="margin-top:0;">⚡ Fix Login Password</h2>

        <?php echo $message; ?>

        <form method="POST">
            <label style="float:left; font-weight:bold;">Select User:</label>
            <select name="username">
                <?php
                // Database එකේ ඉන්න userla ඔක්කොම drop down එකට ගන්නවා
                $sql = "SELECT username FROM users";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['username'] . "'>" . $row['username'] . "</option>";
                    }
                }
                ?>
            </select>

            <label style="float:left; font-weight:bold;">New Password:</label>
            <input type="text" name="new_password" placeholder="Enter new password (ex: ceb123)" required>

            <button type="submit" name="reset_btn">UPDATE PASSWORD</button>
        </form>

        <br>
        <a href="login.php">Go back to Login Page</a>
    </div>

</body>

</html>