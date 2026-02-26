<?php
session_start();
include '../db_conn.php';

// --- 1. AUTO LOGIN (If Cookie Exists) ---
if (!isset($_SESSION['admin_logged_in']) && isset($_COOKIE['ceb_remember_token'])) {
    $cookie_token = $_COOKIE['ceb_remember_token'];

    // Check if this token exists in Database
    $stmt = $conn->prepare("SELECT id, full_name, role FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $cookie_token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();

        // Auto Login!
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['role'] = $row['role'];

        // --- NEW LINE: GET THEME ---
        $_SESSION['theme'] = $row['theme']; // Save user's theme preference
        // Add log
        include 'functions.php';
        if (function_exists('addLog')) addLog($conn, $row['full_name'], 'LOGIN', 'Auto-logged in via Remember Me');

        header("Location: dashboard");
        exit();
    }
}

// --- 2. REDIRECT IF ALREADY LOGGED IN ---
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard");
    exit();
}

// --- 3. MANUAL LOGIN LOGIC ---
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember_me']); // Checkbox click kalada?

    $stmt = $conn->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {

            // Login Success
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];

            // Log
            include_once 'functions.php';
            if (function_exists('addLog')) addLog($conn, $row['full_name'], 'LOGIN', 'Manually logged in');

            // --- SET COOKIE IF REMEMBER ME CHECKED ---
            if ($remember) {
                // Random Token ekak hadanawa
                $token = bin2hex(random_bytes(32));

                // 1. Save token in DB for this user
                $conn->query("UPDATE users SET remember_token='$token' WHERE id={$row['id']}");

                // 2. Save token in Browser Cookie (30 Days)
                setcookie('ceb_remember_token', $token, time() + (86400 * 30), "/");
            }

            header("Location: dashboard");
            exit();
        } else {
            $error_msg = "Incorrect Password! Please try again.";
        }
    } else {
        $error_msg = "User not found! Contact System Admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEB Portal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../loader.css">
    <style>
        body {
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            border: none;
            border-top: 5px solid #dc3545;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dc3545;
        }
    </style>
</head>

<body>
    <div id="loader-wrapper">
        <div class="spinner"></div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card p-4">
                    <div class="text-center pb-3">
                        <i class="fas fa-bolt text-danger fa-3x"></i>
                        <h4 class="fw-bold mt-2">Officer Login</h4>
                    </div>

                    <?php if (isset($error_msg)): ?>
                        <div class="alert alert-danger py-2 small"><i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="fw-bold small text-secondary">Username</label>
                            <input type="text" name="username" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small text-secondary">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <!-- REMEMBER ME CHECKBOX -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember_me" id="remMe">
                            <label class="form-check-label small" for="remMe">Keep me logged in</label>
                        </div>

                        <button type="submit" name="login" class="btn btn-danger w-100 fw-bold">Login</button>
                    </form>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Restricted Area | Officers Only</small><br>
                    <a href="../home" class="text-decoration-none small text-muted">← Back to Site</a>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    window.addEventListener('load', function() {
        var loader = document.getElementById('loader-wrapper');
        // Slight delay for smooth feeling
        setTimeout(function() {
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
                document.body.classList.remove('loading');
            }, 500);
        }, 300);
    });
</script>

</html>