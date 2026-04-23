<?php
session_start();
include '../../config/db_conn.php';
include '../PHPMailer/mailer.php'; // Email යවන ෆයිල් එක
date_default_timezone_set('Asia/Colombo');

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$reset_email = isset($_GET['email']) ? $_GET['email'] : '';
$msg = ""; $err = "";

// --- STEP 1: SEND OTP ---
if (isset($_POST['request_reset'])) {
    $e = $conn->real_escape_string(trim($_POST['email']));
    $chk = $conn->query("SELECT id FROM users WHERE email='$e'");
    if ($chk->num_rows > 0) {
        $otp = rand(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $conn->query("UPDATE users SET otp_code='$otp', otp_expiry='$expiry' WHERE email='$e'");
        if (sendOTP($e, $otp)) {
            header("Location: forgot_password?step=2&email=$e");
            exit();
        } else { $err = "Failed to send email. Please check internet or SMTP settings."; }
    } else { $err = "Email address not found in our records!"; }
}

// --- STEP 2: VERIFY OTP & RESET PASSWORD ---
if (isset($_POST['reset_password'])) {
    $e = $conn->real_escape_string($_POST['email']);
    $entered_otp = $conn->real_escape_string(trim($_POST['otp']));
    $new_pass = $_POST['new_password'];
    $now = date('Y-m-d H:i:s');

    if(strlen($new_pass) < 4) {
        $err = "Password must be at least 4 characters long!";
    } else {
        $res = $conn->query("SELECT id FROM users WHERE email='$e' AND otp_code='$entered_otp' AND otp_expiry >= '$now'");
        if ($res->num_rows > 0) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$hashed_pass', otp_code=NULL, otp_expiry=NULL WHERE email='$e'");
            $step = 3;
        } else { $err = "Invalid or Expired OTP Code!"; }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - EDL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --red: #c0392b; --red-l: #e74c3c; --dark: #0d0f12; --border: rgba(255,255,255,0.07); --muted: rgba(255,255,255,0.45); }
        body { font-family: 'Inter', sans-serif; background: var(--dark); color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; overflow: hidden; }
        .bg-base { position: fixed; inset: 0; z-index: -2; background: linear-gradient(135deg, #0d0f12 0%, #13161c 60%, #0f1218 100%); }
        .bg-grid { position: fixed; inset: 0; z-index: -1; background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px); background-size: 56px 56px; }
        .orb { position: fixed; border-radius: 50%; filter: blur(100px); z-index: -1; pointer-events: none; animation: floatOrb 9s ease-in-out infinite; }
        .orb-1 { width:400px; height:400px; background: rgba(192,57,43,.12); top:-100px; left:-100px; }
        .orb-2 { width:300px; height:300px; background: rgba(52,152,219,.08); bottom:-50px; right:-50px; animation-delay:4s;}
        @keyframes floatOrb { 0%,100%{ transform:translate(0,0) scale(1); } 50% { transform:translate(30px,-20px) scale(1.05); } }

        .glass-card { background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 20px; backdrop-filter: blur(20px); box-shadow: 0 40px 80px rgba(0,0,0,0.5); padding: 40px; width: 100%; max-width: 450px; z-index: 10; }
        .login-header i { font-size: 3rem; color: var(--red-l); margin-bottom: 12px; text-shadow: 0 0 20px rgba(192,57,43,0.4); }
        .form-label { font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.7); text-transform: uppercase; margin-bottom: 6px; }
        .form-control { background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: white; padding: 14px 20px; border-radius: 12px; }
        .form-control:focus { background: rgba(255,255,255,0.08); border-color: var(--red); color: white; box-shadow: 0 0 0 3px rgba(192,57,43,0.15); }
        ::placeholder { color: var(--muted) !important; opacity: 0.7; }
        
        .btn-submit { background: linear-gradient(135deg, var(--red) 0%, var(--red-l) 100%); border: none; border-radius: 12px; padding: 16px; font-weight: 700; color: white; width: 100%; transition: 0.3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 15px 40px rgba(192,57,43,0.4); color: white; }
    </style>
</head>
<body>
    <div class="bg-base"></div><div class="bg-grid"></div><div class="orb orb-1"></div><div class="orb orb-2"></div>

    <div class="container d-flex justify-content-center px-3">
        <div class="glass-card animate__animated animate__fadeInUp">
            
            <div class="text-center login-header">
                <i class="fas fa-unlock-alt"></i>
                <h4 class="fw-bold text-white mb-1">Reset Password</h4>
                <p class="small text-white-50 mb-4">EDL Authorized Personnel</p>
            </div>

            <?php if($err) echo "<div class='alert small text-center' style='background:rgba(192,57,43,0.1); border:1px solid var(--red-l); color:var(--red-l); border-radius:10px;'><i class='fas fa-exclamation-circle me-1'></i> $err</div>"; ?>

            <?php if($step == 1): ?>
            <!-- STEP 1: EMAIL -->
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Registered Email</label>
                    <input type="email" name="email" class="form-control" placeholder="user@edl.com" required autofocus>
                </div>
                <button type="submit" name="request_reset" class="btn btn-submit">SEND RECOVERY CODE <i class="fas fa-paper-plane ms-2"></i></button>
            </form>

            <?php elseif($step == 2): ?>
            <!-- STEP 2: OTP -->
            <form method="POST">
                <div class="p-3 mb-4 text-center rounded-3" style="background:rgba(255,255,255,0.05); border:1px dashed var(--border);">
                    <small class="text-white-50 d-block mb-1">Code sent to:</small>
                    <b class="text-white"><?php echo htmlspecialchars($reset_email); ?></b>
                </div>
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($reset_email); ?>">
                
                <div class="mb-3">
                    <label class="form-label text-center d-block">6-Digit Code</label>
                    <input type="text" name="otp" class="form-control text-center fw-bold text-white fs-4" style="letter-spacing: 10px;" maxlength="6" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label">New Secure Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" name="reset_password" class="btn btn-submit bg-success border-0" style="background:linear-gradient(135deg, #198754, #20c997);">SAVE PASSWORD <i class="fas fa-check ms-2"></i></button>
            </form>

            <?php elseif($step == 3): ?>
            <!-- STEP 3: SUCCESS -->
            <div class="text-center py-3">
                <div style="font-size:4rem; color:#20c997; text-shadow:0 0 20px rgba(32,201,151,0.5);"><i class="fas fa-check-circle"></i></div>
                <h5 class="fw-bold text-white mt-3">Account Recovered!</h5>
                <p class="text-white-50 small mt-2">Your password was reset successfully. Keep it safe.</p>
                <a href="login" class="btn btn-submit mt-4 d-block">GO TO LOGIN</a>
            </div>
            <?php endif; ?>

            <?php if($step != 3): ?>
            <div class="text-center mt-4 border-top border-secondary pt-3" style="border-color: rgba(255,255,255,0.1) !important;">
                <a href="login" class="small text-decoration-none text-white-50 fw-bold">← Back to Login</a>
            </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>