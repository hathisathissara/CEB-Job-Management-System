<?php
session_start();
include '../../config/db_conn.php';
include '../PHPMailer/mailer.php';
date_default_timezone_set('Asia/Colombo');

$step = isset($_GET['step']) ? $_GET['step'] : 1;
$reg_email = isset($_GET['email']) ? $_GET['email'] : '';
$msg = ""; $err = "";

// --- STEP 1: HANDLE REGISTRATION FORM ---
if (isset($_POST['register'])) {
    $n = $conn->real_escape_string(trim($_POST['full_name']));
    $u = $conn->real_escape_string(trim($_POST['username']));
    $e = $conn->real_escape_string(trim($_POST['email']));
    $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if Username or Email exists
    $chk = $conn->query("SELECT id FROM users WHERE username='$u' OR email='$e'");
    if ($chk->num_rows > 0) {
        $err = "Username or Email already taken!";
    } else {
        // Generate 6 Digit OTP
        $otp = rand(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes')); // Expires in 15 mins
        
        // Insert user as INACTIVE (is_active = 0) with OTP
        $sql = "INSERT INTO users (username, email, password, full_name, role, is_active, otp_code, otp_expiry) 
                VALUES ('$u', '$e', '$p', '$n', 'Officer', 0, '$otp', '$expiry')";
        
        if ($conn->query($sql)) {
            // Send Email
            if (sendOTP($e, $otp)) {
                // Go to Step 2 (OTP Verification)
                header("Location: register?step=2&email=$e");
                exit();
            } else {
                $err = "User saved, but failed to send email. Please contact Admin.";
            }
        } else {
            $err = "Database Error: " . $conn->error;
        }
    }
}

// --- STEP 2: HANDLE OTP VERIFICATION ---
if (isset($_POST['verify_otp'])) {
    $e = $conn->real_escape_string($_POST['email']);
    $entered_otp = $conn->real_escape_string(trim($_POST['otp']));
    $now = date('Y-m-d H:i:s');

    // Check OTP
    $res = $conn->query("SELECT id FROM users WHERE email='$e' AND otp_code='$entered_otp' AND otp_expiry >= '$now'");
    
    if ($res->num_rows > 0) {
        // OTP Correct -> Clear OTP fields (BUT DO NOT ACTIVATE YET)
        $conn->query("UPDATE users SET otp_code=NULL, otp_expiry=NULL WHERE email='$e'");
        $step = 3; // Move to Success Message
    } else {
        $err = "Invalid or Expired OTP Code!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Registration - EDL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body{background:#f8f9fa;display:flex;align-items:center;justify-content:center;height:100vh;} .card-reg{max-width:450px;width:100%;border-top:5px solid #d11212;}</style>
</head>
<body>

    <div class="card card-reg p-4 shadow-lg border-0 rounded-4">
        
        <div class="text-center mb-4">
            <i class="fas fa-bolt text-danger fa-2x mb-2"></i>
            <h4 class="fw-bold">Staff Registration</h4>
            <p class="text-muted small">EDL Internal System</p>
        </div>

        <?php if($err) echo "<div class='alert alert-danger py-2 small'><i class='fas fa-exclamation-circle'></i> $err</div>"; ?>

        <?php if($step == 1): ?>
        <!-- STEP 1: REGISTRATION FORM -->
        <form method="POST">
            <div class="mb-3">
                <label class="small fw-bold text-secondary">Full Name</label>
                <input type="text" name="full_name" class="form-control bg-light" required>
            </div>
            <div class="mb-3">
                <label class="small fw-bold text-secondary">Email Address (Must be valid)</label>
                <input type="email" name="email" class="form-control bg-light" required>
            </div>
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <label class="small fw-bold text-secondary">Username</label>
                    <input type="text" name="username" class="form-control bg-light" required>
                </div>
                <div class="col-6">
                    <label class="small fw-bold text-secondary">Password</label>
                    <input type="password" name="password" class="form-control bg-light" required>
                </div>
            </div>
            <button type="submit" name="register" class="btn btn-danger w-100 fw-bold">Sign Up & Send OTP</button>
            <div class="text-center mt-3"><a href="login" class="small text-decoration-none">Already have an account? Login</a></div>
        </form>

        <?php elseif($step == 2): ?>
        <!-- STEP 2: OTP FORM -->
        <form method="POST">
            <div class="alert alert-warning small text-center mb-4">
                We sent a 6-digit code to <b><?php echo htmlspecialchars($reg_email); ?></b>.
            </div>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($reg_email); ?>">
            <div class="mb-4 text-center">
                <label class="small fw-bold text-secondary mb-2">Enter OTP Code</label>
                <input type="text" name="otp" class="form-control form-control-lg text-center fw-bold text-primary" style="letter-spacing: 5px;" maxlength="6" required autofocus>
            </div>
            <button type="submit" name="verify_otp" class="btn btn-primary w-100 fw-bold">Verify Email</button>
        </form>

        <?php elseif($step == 3): ?>
        <!-- STEP 3: SUCCESS & WAIT FOR ADMIN -->
        <div class="text-center py-4">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h5 class="fw-bold text-dark">Email Verified!</h5>
            <p class="text-muted small mt-2">Your account has been registered successfully. However, you cannot login until a Super Admin <b>Activates</b> your account.</p>
            <a href="login" class="btn btn-outline-dark mt-3 px-4 rounded-pill">Go to Login</a>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>