<?php
session_start();
include '../db_conn.php';

// --- 1. AUTO LOGIN (If Cookie Exists) ---
if (!isset($_SESSION['admin_logged_in']) && isset($_COOKIE['ceb_remember_token'])) {
    $cookie_token = $_COOKIE['ceb_remember_token'];

    // Check if this token exists in Database
    $stmt = $conn->prepare("SELECT id, full_name, role, theme FROM users WHERE remember_token = ?");
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

    $stmt = $conn->prepare("SELECT id, username, password, full_name, role, theme FROM users WHERE username = ?");
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
            $_SESSION['theme'] = $row['theme'];

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
    <title>EDL Portal Login</title>
    <!-- Favicon -->
    <link rel="icon" href="https://img.icons8.com/color/48/d11212/flash-on.png" type="image/png">
    
    <!-- Fonts & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../loader.css">
    
    <style>
        :root {
            --red:      #c0392b;
            --red-l:    #e74c3c;
            --dark:     #0d0f12;
            --dark2:    #13161c;
            --border:   rgba(255,255,255,0.07);
            --muted:    rgba(255,255,255,0.45);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        body.loading { overflow: hidden; }

        /* Background */
        .bg-base {
            position: fixed; inset: 0; z-index: -2;
            background: linear-gradient(135deg, #0d0f12 0%, #13161c 60%, #0f1218 100%);
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: -1;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 56px 56px;
        }
        
        .orb {
            position: fixed; border-radius: 50%; filter: blur(100px); z-index: -1; pointer-events: none;
            animation: floatOrb 9s ease-in-out infinite;
        }
        .orb-1 { width:400px; height:400px; background: rgba(192,57,43,.12); top:-100px; left:-100px; }
        .orb-2 { width:300px; height:300px; background: rgba(52,152,219,.08); bottom:-50px; right:-50px; animation-delay:4s;}

        @keyframes floatOrb {
            0%,100%{ transform:translate(0,0) scale(1); }
            50%    { transform:translate(30px,-20px) scale(1.05); }
        }

        /* Glass Form Card */
        .glass-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.5);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.6s ease;
            position: relative;
            z-index: 10;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-header i {
            font-size: 3rem;
            color: var(--red-l);
            margin-bottom: 12px;
            text-shadow: 0 0 20px rgba(192,57,43,0.4);
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: white;
            padding: 14px 20px;
            border-radius: 12px;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.08);
            border-color: var(--red);
            color: white;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.15);
        }

        .form-check-input {
            background-color: rgba(255,255,255,0.1);
            border-color: var(--border);
        }
        .form-check-input:checked {
            background-color: var(--red-l);
            border-color: var(--red-l);
        }
        .form-check-label {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--red) 0%, var(--red-l) 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 10px 30px rgba(192,57,43,0.25);
            transition: all 0.3s;
            width: 100%;
            color: white;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(192,57,43,0.4);
            color: white;
        }

        .back-link {
            display: inline-block;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
            transition: 0.2s;
            margin-top: 25px;
        }
        .back-link:hover { color: white; }
    </style>
</head>

<body class="loading">
    <div id="loader-wrapper"><div class="spinner"></div></div>
    <div class="bg-base"></div>
    <div class="bg-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container d-flex justify-content-center">
        <div class="glass-card">
            <div class="text-center login-header">
                <i class="fas fa-bolt"></i>
                <h4 class="fw-bold text-white mb-1">Officer Login</h4>
                <p class="small text-white-50 mb-4">EDL Portal - Authorised Access Only</p>
            </div>

            <?php if (isset($error_msg)): ?>
                <div class="alert small text-center" style="background:rgba(192,57,43,0.1); border:1px solid var(--red-l); color:var(--red-l); border-radius:10px;">
                    <i class="fas fa-exclamation-circle me-1"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="mb-4 form-check d-flex align-items-center gap-2">
                    <input type="checkbox" class="form-check-input mt-0" name="remember_me" id="remMe">
                    <label class="form-check-label mb-0" for="remMe">Keep me logged in</label>
                </div>

                <button type="submit" name="login" class="btn btn-submit">
                    LOGIN TO PORTAL <i class="fas fa-sign-in-alt ms-2"></i>
                </button>
            </form>
            
            <div class="text-center">
                <a href="../home" class="back-link"><i class="fas fa-arrow-left me-2"></i>Back to Main Site</a>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            const ldr = document.getElementById('loader-wrapper');
            setTimeout(() => {
                ldr.style.opacity = '0';
                setTimeout(() => { ldr.style.display='none'; document.body.classList.remove('loading'); }, 500);
            }, 300);
        });
    </script>
</body>
</html>
