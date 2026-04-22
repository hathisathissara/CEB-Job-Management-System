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
    
    // Email එක Database එකේ තියෙනවද බලනවා
    $chk = $conn->query("SELECT id FROM users WHERE email='$e'");
    if ($chk->num_rows > 0) {
        $otp = rand(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        
        // Update user with OTP
        $conn->query("UPDATE users SET otp_code='$otp', otp_expiry='$expiry' WHERE email='$e'");
        
        if (sendOTP($e, $otp)) {
            header("Location: forgot_password?step=2&email=$e");
            exit();
        } else {
            $err = "Failed to send email. Please check internet or SMTP settings.";
        }
    } else {
        $err = "Email address not found in our records!";
    }
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
        // OTP එකයි Time එකයි හරිද බලනවා
        $res = $conn->query("SELECT id FROM users WHERE email='$e' AND otp_code='$entered_otp' AND otp_expiry >= '$now'");
        
        if ($res->num_rows > 0) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            // Update Password & Clear OTP
            $conn->query("UPDATE users SET password='$hashed_pass', otp_code=NULL, otp_expiry=NULL WHERE email='$e'");
            $step = 3; // Success Message
        } else {
            $err = "Invalid or Expired OTP Code!";
        }
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
    <style>body{background:#f8f9fa;display:flex;align-items:center;justify-content:center;height:100vh;} .card-reg{max-width:450px;width:100%;border-top:5px solid #d11212;}</style>
</head>
<body>

    <div class="card card-reg p-4 shadow-lg border-0 rounded-4">
        
        <div class="text-center mb-4">
            <i class="fas fa-lock text-danger fa-2x mb-2"></i>
            <h4 class="fw-bold">Password Reset</h4>
            <p class="text-muted small">EDL Internal System</p>
        </div>

        <?php if($err) echo "<div class='alert alert-danger py-2 small text-center'><i class='fas fa-exclamation-circle'></i> $err</div>"; ?>

        <?php if($step == 1): ?>
        <!-- STEP 1: ENTER EMAIL -->
        <form method="POST">
            <div class="mb-4">
                <label class="small fw-bold text-secondary mb-1">Enter your registered Email Address</label>
                <input type="email" name="email" class="form-control bg-light" placeholder="example@edl.com" required autofocus>
            </div>
            <button type="submit" name="request_reset" class="btn btn-danger w-100 fw-bold">Send OTP Code</button>
            <div class="text-center mt-3"><a href="login" class="small text-decoration-none text-muted">← Back to Login</a></div>
        </form>

        <?php elseif($step == 2): ?>
        <!-- STEP 2: ENTER OTP & NEW PASSWORD -->
        <form method="POST">
            <div class="alert alert-warning small text-center mb-3">
                OTP sent to <b><?php echo htmlspecialchars($reset_email); ?></b>
            </div>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($reset_email); ?>">
            
            <div class="mb-3">
                <label class="small fw-bold text-secondary mb-1">Enter 6-Digit OTP</label>
                <input type="text" name="otp" class="form-control text-center fw-bold text-primary" style="letter-spacing: 5px;" maxlength="6" required autofocus>
            </div>
            <div class="mb-4">
                <label class="small fw-bold text-secondary mb-1">Create New Password</label>
                <input type="password" name="new_password" class="form-control" placeholder="Min 4 characters" required>
            </div>
            
            <button type="submit" name="reset_password" class="btn btn-success w-100 fw-bold">Reset Password</button>
        </form>

        <?php elseif($step == 3): ?>
        <!-- STEP 3: SUCCESS -->
        <div class="text-center py-3">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h5 class="fw-bold text-dark">Password Changed!</h5>
            <p class="text-muted small mt-2">You can now login with your new password.</p>
            <a href="login" class="btn btn-dark mt-2 px-4 rounded-pill">Go to Login</a>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>