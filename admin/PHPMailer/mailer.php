<?php
// admin/PHPMailer/mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

// ─── SMTP CONFIG (shared) ─────────────────────────────────────
define('SMTP_USER', 'htbuwaneka@gmail.com');
define('SMTP_PASS', 'siip ugsh jzya appe');
define('SMTP_FROM', 'htbuwaneka@gmail.com');
define('SMTP_NAME', 'EDL Admin Portal');

function _getMailer(): PHPMailer {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom(SMTP_FROM, SMTP_NAME);
    $mail->isHTML(true);
    return $mail;
}

// ─── 1. SEND OTP ──────────────────────────────────────────────
function sendOTP($to_email, $otp_code) {
    try {
        $mail = _getMailer();
        $mail->addAddress($to_email);
        $mail->Subject = 'Your OTP Verification Code - EDL Portal';
        $mail->Body = "
        <div style='font-family:Inter,sans-serif;background:#f4f4f4;padding:30px;'>
          <div style='max-width:480px;margin:auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);'>
            <div style='background:#d11212;padding:24px;text-align:center;'>
              <h2 style='color:#fff;margin:0;font-size:1.3rem;'>⚡ EDL Portal Verification</h2>
            </div>
            <div style='padding:30px;text-align:center;'>
              <p style='color:#64748b;'>Here is your one-time OTP code:</p>
              <div style='font-size:2.5rem;font-weight:900;letter-spacing:12px;color:#d11212;background:#fef2f2;border:2px dashed #d11212;border-radius:10px;padding:16px 24px;display:inline-block;margin:16px 0;'>$otp_code</div>
              <p style='color:#94a3b8;font-size:.85rem;'>This code expires in <b>15 minutes</b>.</p>
            </div>
          </div>
        </div>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// ─── 2. SEND WELCOME CREDENTIALS (Admin-created accounts) ─────
function sendWelcomeCredentials($to_email, $full_name, $username, $plain_password, $role) {
    try {
        $mail = _getMailer();
        $mail->addAddress($to_email);
        $mail->Subject = 'Welcome to EDL Portal - Your Login Credentials';
        $mail->Body = "
        <div style='font-family:Inter,sans-serif;background:#f0f4f8;padding:30px;'>
          <div style='max-width:500px;margin:auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.09);'>

            <div style='background:linear-gradient(135deg,#1e293b,#334155);padding:28px;text-align:center;'>
              <div style='font-size:2rem;margin-bottom:8px;'>⚡</div>
              <h2 style='color:#fff;margin:0;font-size:1.2rem;letter-spacing:.5px;'>EDL Admin Portal</h2>
              <p style='color:rgba(255,255,255,.5);font-size:.8rem;margin:6px 0 0;'>Your account has been created</p>
            </div>

            <div style='padding:32px;'>
              <p style='color:#1e293b;font-size:1rem;margin-top:0;'>Hello, <b>$full_name</b> 👋</p>
              <p style='color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:24px;'>
                A <b>Super Admin</b> has created an account for you on the <b>EDL Internal Portal</b>. You can log in using the credentials below.
              </p>

              <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:20px;'>
                <table style='width:100%;border-collapse:collapse;font-size:.9rem;'>
                  <tr>
                    <td style='color:#94a3b8;padding:10px 0;width:38%;'>Role</td>
                    <td style='color:#1e293b;font-weight:700;'>$role</td>
                  </tr>
                  <tr style='border-top:1px solid #f1f5f9;'>
                    <td style='color:#94a3b8;padding:10px 0;'>Username</td>
                    <td style='color:#1e293b;font-weight:700;font-family:monospace;font-size:1rem;'>$username</td>
                  </tr>
                  <tr style='border-top:1px solid #f1f5f9;'>
                    <td style='color:#94a3b8;padding:10px 0;'>Password</td>
                    <td style='color:#d11212;font-weight:700;font-family:monospace;font-size:1.1rem;'>$plain_password</td>
                  </tr>
                </table>
              </div>

              <div style='background:#fffbeb;border-left:4px solid #f59e0b;border-radius:6px;padding:14px 16px;margin-bottom:24px;'>
                <p style='margin:0;color:#92400e;font-size:.85rem;'>
                  ⚠️ <b>Important:</b> Please change your password immediately after your first login. Go to <b>Settings &rarr; Change Password</b>.
                </p>
              </div>

              <div style='text-align:center;'>
                <a href='http://localhost/CEB/admin/login'
                   style='display:inline-block;background:linear-gradient(135deg,#1e293b,#475569);color:#fff;text-decoration:none;padding:13px 34px;border-radius:10px;font-weight:600;font-size:.9rem;'>
                  Login to Portal &rarr;
                </a>
              </div>
            </div>

            <div style='background:#f8fafc;padding:16px;text-align:center;border-top:1px solid #e2e8f0;'>
              <p style='color:#94a3b8;font-size:.75rem;margin:0;'>EDL Internal System &bull; Do not share your credentials with anyone.</p>
            </div>

          </div>
        </div>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
// ─── 3. SEND ACCOUNT UPDATE NOTIFICATION ──────────────────────────
function sendAccountUpdateNotification($to_email, $full_name, $new_role, $new_status_text) {
    try {
        $mail = _getMailer();
        $mail->addAddress($to_email);
        $mail->Subject = 'Account Update Notification - EDL Portal';
        $mail->Body = "
        <div style='font-family:Inter,sans-serif;background:#f0f4f8;padding:30px;'>
          <div style='max-width:500px;margin:auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.09);'>

            <div style='background:linear-gradient(135deg,#6366f1,#9333ea);padding:28px;text-align:center;'>
              <div style='font-size:2rem;margin-bottom:8px;'>🔄</div>
              <h2 style='color:#fff;margin:0;font-size:1.2rem;letter-spacing:.5px;'>EDL Admin Portal</h2>
              <p style='color:rgba(255,255,255,.8);font-size:.8rem;margin:6px 0 0;'>Account Status Updated</p>
            </div>

            <div style='padding:32px;'>
              <p style='color:#1e293b;font-size:1rem;margin-top:0;'>Hello, <b>$full_name</b> 👋</p>
              <p style='color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:24px;'>
                An administrator has updated your account settings on the <b>EDL Internal Portal</b>. Below are your current account details:
              </p>

              <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:20px;'>
                <table style='width:100%;border-collapse:collapse;font-size:.9rem;'>
                  <tr>
                    <td style='color:#94a3b8;padding:10px 0;width:38%;'>New Role</td>
                    <td style='color:#1e293b;font-weight:700;'>$new_role</td>
                  </tr>
                  <tr style='border-top:1px solid #f1f5f9;'>
                    <td style='color:#94a3b8;padding:10px 0;'>Account Status</td>
                    <td style='color:#1e293b;font-weight:700;'>$new_status_text</td>
                  </tr>
                </table>
              </div>

              <div style='text-align:center;'>
                <a href='http://localhost/CEB/admin/login'
                   style='display:inline-block;background:linear-gradient(135deg,#1e293b,#475569);color:#fff;text-decoration:none;padding:13px 34px;border-radius:10px;font-weight:600;font-size:.9rem;'>
                  Login to Portal &rarr;
                </a>
              </div>
            </div>

            <div style='background:#f8fafc;padding:16px;text-align:center;border-top:1px solid #e2e8f0;'>
              <p style='color:#94a3b8;font-size:.75rem;margin:0;'>EDL Internal System &bull; This is an automated message.</p>
            </div>

          </div>
        </div>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>