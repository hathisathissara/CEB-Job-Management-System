<?php
session_start();
include '../../config/db_conn.php';
include '../PHPMailer/mailer.php';
date_default_timezone_set('Asia/Colombo');

$step = isset($_GET['step']) ? $_GET['step'] : 1;
$msg = ""; $err = "";


$reg_email = isset($_SESSION['temp_reg']['email']) ? $_SESSION['temp_reg']['email'] : '';

// ==========================================
// --- STEP 1: REGISTRATION FORM ---
// ==========================================meke user kenek otp verification eka nokara acc ekak create krnna puluwan eka nisa ekata mokakdda krnna puluwao
// ==========================================
// --- STEP 1: REGISTRATION FORM ---
// ==========================================
if (isset($_POST['register'])) {
    
    // 🛑 1. HONEYPOT CHECK (ANTI-BOT)
    // බොට් කෙනෙක් මේ hidden ෆීල්ඩ් එක පුරවලා තිබුනොත් මේක ඇතුලට යනවා
    if (!empty($_POST['contact_fax'])) {
        die("<div style='text-align:center; margin-top:50px; color:red;'><h2>Security Warning</h2><p>Bot behavior detected. Connection terminated.</p></div>");
    }

    $n = trim($_POST['full_name']);
    $u = trim($_POST['username']);
    $e = trim($_POST['email']);
    $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // 🛑 2. ANTI TEMP-EMAIL CHECK (ALLOWED DOMAINS ONLY)
    // තාවකාලික ඊමේල් නැවැත්වීම සඳහා අනුමත කරන ලද ඩොමේන් ලිස්ට් එක.
    $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'edl.com', 'edl.lk'];
    
    // ගැහුව ඊමේල් එකෙන් '@' ලකුණෙන් පස්සේ තියෙන කෑල්ල කපා ගන්නවා
    $email_domain = strtolower(substr(strrchr($e, "@"), 1));

    if (!in_array($email_domain, $allowed_domains)) {
        // Temp mail එකක් හෝ ලැයිස්තුවේ නැති එකක් නම්
        $err = "Security Alert: Disposable or untrusted emails are blocked. Use official email or Gmail.";
    } 
    else {
        // මේ කොටසට එන්නේ ඊමේල් එක Trust කරන එකක් සහ Bot කෙනෙක් නෙවෙයි නම් විතරයි!
        
        // Check if Username or Email exists in REAL Database
        $chk = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $chk->bind_param("ss", $u, $e);
        $chk->execute();
        
        if ($chk->get_result()->num_rows > 0) { 
            $err = "Username or Email already taken in the system!"; 
        } else {
            // Generate 6 Digit OTP
            $otp = rand(100000, 999999);
            $expiry = time() + (15 * 60); // Expire in 15 mins
            
            $_SESSION['temp_reg'] = [
                'name'  => $n, 'user'  => $u, 'email' => $e, 
                'pass'  => $p, 'otp'   => $otp, 'exp'   => $expiry
            ];
            
            // Email යවනවා
            if (sendOTP($e, $otp)) { 
                header("Location: register?step=2"); 
                exit(); 
            } else { 
                $err = "Failed to send OTP email. Please check internet/SMTP."; 
                unset($_SESSION['temp_reg']);
            }
        }
    }
}

// ==========================================
// --- STEP 2: VERIFY OTP & DB INSERT ---
// ==========================================
if (isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);
    
    // Session එකේ විස්තර තියෙනවද බලනවා (browser එක වැහුවොත් මේවා මැකෙනවා)
    if(isset($_SESSION['temp_reg'])) {
        $t_data = $_SESSION['temp_reg'];
        
        // ගහපු OTP එකයි, කාලයයි (විනාඩි 15) හරිද බලනවා
        if ($entered_otp == $t_data['otp'] && time() <= $t_data['exp']) {
            
            // 🚨 OTP හරි! දැන් තමයි Database එකට සේව් කරන්නේ (is_active = 0 සමග)
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role, is_active) VALUES (?, ?, ?, ?, 'Officer', 0)");
            $stmt->bind_param("ssss", $t_data['user'], $t_data['email'], $t_data['pass'], $t_data['name']);
            
            if ($stmt->execute()) {
                unset($_SESSION['temp_reg']); // Session එකේ දත්ත මකනවා
                $step = 3; 
            } else {
                $err = "Database Error: " . $conn->error;
            }
        } else {
            $err = "Invalid or Expired OTP Code!";
        }
    } else {
        $err = "Session expired! Please restart registration.";
        $step = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration - EDL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --red: #c0392b; --red-l: #e74c3c; --dark: #0d0f12; --border: rgba(255,255,255,0.07); --muted: rgba(255,255,255,0.45); }
        body { font-family: 'Inter', sans-serif; background: var(--dark); color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; overflow-x: hidden; }
        .bg-base { position: fixed; inset: 0; z-index: -2; background: linear-gradient(135deg, #0d0f12 0%, #13161c 60%, #0f1218 100%); }
        .bg-grid { position: fixed; inset: 0; z-index: -1; background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px); background-size: 56px 56px; }
        .orb { position: fixed; border-radius: 50%; filter: blur(100px); z-index: -1; animation: floatOrb 9s ease-in-out infinite; }
        .orb-1 { width:400px; height:400px; background: rgba(192,57,43,.12); top:-100px; left:-100px; }
        .orb-2 { width:300px; height:300px; background: rgba(52,152,219,.08); bottom:-50px; right:-50px; animation-delay:4s;}
        @keyframes floatOrb { 0%,100%{ transform:translate(0,0) scale(1); } 50% { transform:translate(30px,-20px) scale(1.05); } }

        .glass-card { background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 20px; backdrop-filter: blur(20px); box-shadow: 0 40px 80px rgba(0,0,0,0.5); padding: 40px; width: 100%; max-width: 550px; z-index: 10; }
        
        .login-header i { font-size: 3rem; color: var(--red-l); margin-bottom: 12px; text-shadow: 0 0 20px rgba(192,57,43,0.4); }
        .form-label { font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.7); text-transform: uppercase; margin-bottom: 6px; }
        .form-control { background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: white; padding: 12px 18px; border-radius: 12px; }
        .form-control:focus { background: rgba(255,255,255,0.08); border-color: var(--red); color: white; box-shadow: 0 0 0 3px rgba(192,57,43,0.15); }
        ::placeholder { color: var(--muted) !important; opacity: 0.7; }
        
        .btn-submit { background: linear-gradient(135deg, var(--red) 0%, var(--red-l) 100%); border: none; border-radius: 12px; padding: 15px; font-weight: 700; color: white; width: 100%; transition: 0.3s; margin-top: 10px;}
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 15px 40px rgba(192,57,43,0.4); color: white; }
    </style>
</head>
<body>
    <div class="bg-base"></div><div class="bg-grid"></div><div class="orb orb-1"></div><div class="orb orb-2"></div>

    <div class="container d-flex justify-content-center py-4 px-3">
        <div class="glass-card animate__animated animate__fadeInUp">
            
            <div class="text-center login-header">
                <i class="fas fa-id-badge"></i>
                <h4 class="fw-bold text-white mb-1">Create Account</h4>
                <p class="small text-white-50 mb-4">EDL Staff Registration Portal</p>
            </div>

            <?php if($err) echo "<div class='alert small text-center' style='background:rgba(192,57,43,0.1); border:1px solid var(--red-l); color:var(--red-l); border-radius:10px;'><i class='fas fa-exclamation-circle me-1'></i> $err</div>"; ?>

            <?php if($step == 1): ?>
            <!-- FORM -->
            <form method="POST">
                  <!-- 🔴 HONEYPOT FIELD FOR BOTS (INVISIBLE) 🔴 -->
                <input type="text" name="contact_fax" value="" style="display:none !important;" tabindex="-1" autocomplete="off">
                <div class="mb-3"><label class="form-label">Full Legal Name</label><input type="text" name="full_name" class="form-control" placeholder="John Doe" required></div>
                <div class="mb-3"><label class="form-label">Office Email Address</label><input type="email" name="email" class="form-control" placeholder="name@edl.com" required></div>
                <div class="row g-2 mb-3">
                    <div class="col-6"><label class="form-label">Username</label><input type="text" name="username" class="form-control" placeholder="Login ID" required></div>
                    <div class="col-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control" placeholder="••••••" required></div>
                </div>
                <button type="submit" name="register" class="btn btn-submit">SIGN UP & VERIFY <i class="fas fa-paper-plane ms-1"></i></button>
                <div class="text-center mt-3"><a href="login" class="small text-decoration-none text-white-50">Already registered? <b>Login here</b></a></div>
            </form>

            <?php elseif($step == 2): ?>
            <!-- OTP -->
            <form method="POST">
                <div class="p-3 mb-4 text-center rounded-3" style="background:rgba(255,255,255,0.05); border:1px dashed var(--border);">
                    <small class="text-white-50 d-block mb-1">We sent a verification code to</small>
                    <b class="text-white"><?php echo htmlspecialchars($reg_email); ?></b>
                </div>
                
                <div class="mb-4 text-center">
                    <label class="form-label mb-2">Enter OTP Code</label>
                    <input type="text" name="otp" class="form-control text-center fw-bold text-white fs-4 py-3" style="letter-spacing:10px;" maxlength="6" required autofocus>
                </div>
                <button type="submit" name="verify_otp" class="btn btn-submit bg-primary" style="background:linear-gradient(135deg, #0d6efd, #0dcaf0);">VERIFY EMAIL <i class="fas fa-check-circle ms-1"></i></button>
                <div class="text-center mt-3"><a href="register" class="small text-decoration-none text-danger">Cancel and try again</a></div>
            </form>

            <?php elseif($step == 3): ?>
            <!-- WAIT FOR ADMIN -->
            <div class="text-center py-4">
                <div style="font-size:3.5rem; color:#f39c12; text-shadow:0 0 20px rgba(243,156,18,0.5);"><i class="fas fa-user-clock"></i></div>
                <h5 class="fw-bold text-white mt-3">Account Pending!</h5>
                <p class="text-white-50 small mt-2 lh-lg">Your email has been verified and registered. Please inform your <b>Super Admin</b> to activate your account access.</p>
                <a href="login" class="btn btn-submit mt-4 d-block bg-transparent border text-white hover-border-white" style="background:transparent; box-shadow:none; border-color:var(--border);">RETURN TO LOGIN</a>
            </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>