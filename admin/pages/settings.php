<?php
// ============================================
// 1. AUTH MIDDLEWARE
// ============================================
require_once '../middleware/authGuard.php';

// ============================================
// 2. CONTROLLER LOGIC (Account Security only)
// ============================================
require_once '../controllers/SettingController.php';

include '../layout/header.php';
?>

<style>
/* ===== SHARED SETTINGS STYLES ===== */
.settings-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #a855f7);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 700; color: #fff;
    box-shadow: 0 8px 25px rgba(99,102,241,0.35);
}
.settings-nav .list-group-item {
    border: none; border-radius: 12px !important; margin-bottom: 6px;
    padding: 12px 16px; font-weight: 500; color: var(--text-muted);
    transition: all .25s ease; background: transparent !important;
}
.settings-nav .list-group-item:hover {
    background: rgba(99,102,241,0.1) !important; color: #818cf8;
    transform: translateX(3px);
}
.settings-nav .list-group-item.active {
    background: linear-gradient(135deg, #6366f1, #9333ea) !important;
    color: #fff !important; box-shadow: 0 6px 20px rgba(99,102,241,0.4);
}
.settings-card {
    border-radius: 18px !important; border: none !important;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08) !important; transition: 0.3s;
}
.settings-card:hover { transform: translateY(-2px); }
.settings-card .card-header {
    background: transparent !important; border-bottom: 1px solid var(--edl-border) !important;
}
.section-icon {
    width: 38px; height: 38px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
}
.form-control, .form-select { border-radius: 12px !important; padding: 10px 14px; font-size:.9rem; }
.input-group-text { border-radius: 12px 0 0 12px !important; }
.btn-save-profile {
    background: linear-gradient(135deg, #6366f1, #9333ea); color: #fff;
    border-radius: 12px; padding: 12px; font-weight: 600; border: none;
    transition: .3s; box-shadow: 0 6px 18px rgba(99,102,241,.3);
}
.btn-save-profile:hover { transform: translateY(-2px) scale(1.01); color: #fff; }
.btn-change-pass {
    background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;
    border-radius: 12px; padding: 12px; border: none; transition: .3s;
}
.btn-change-pass:hover { opacity: 0.9; transform: translateY(-1px); color: #fff; }
.theme-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    box-shadow: 0 8px 25px rgba(0,0,0,.2);
}
.notice-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg, #020617, #1e293b);
}
.page-sub-nav {
    display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px;
}
.page-sub-nav a {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 18px; border-radius: 50px; font-size: .85rem;
    font-weight: 600; text-decoration: none; transition: all .25s;
    border: 1px solid var(--edl-border); color: var(--text-muted);
}
.page-sub-nav a:hover { background: rgba(99,102,241,0.1); color: #818cf8; border-color: #6366f1; }
.page-sub-nav a.active {
    background: linear-gradient(135deg, #6366f1, #9333ea);
    color: #fff; border-color: transparent;
    box-shadow: 0 4px 15px rgba(99,102,241,0.4);
}
</style>

    <!-- PAGE HEADER -->
    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="section-icon bg-primary bg-opacity-10">
            <i class="fas fa-user-shield text-primary"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--text-main)">Account Security</h4>
            <p class="text-muted small mb-0">Manage your profile and password</p>
        </div>
    </div>

    <!-- SUB NAV PILLS -->
    <div class="page-sub-nav">
        <a href="settings" class="active">
            <i class="fas fa-user-shield"></i> Account Security
        </a>
        <?php if ($my_role == 'Super Admin'): ?>
        <a href="add_user">
            <i class="fas fa-user-plus"></i> Add New Officer
        </a>
        <a href="manage_users">
            <i class="fas fa-users-cog"></i> Manage Users
        </a>
        <?php endif; ?>
    </div>

    <?php if ($msg) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:rgba(16,185,129,.15);color:#10b981;border-radius:12px'><i class='fas fa-check-circle'></i> $msg</div>"; ?>
    <?php if ($err) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:rgba(239,68,68,.15);color:#ef4444;border-radius:12px'><i class='fas fa-exclamation-circle'></i> $err</div>"; ?>

    <div class="row g-4">

        <!-- ====== LEFT SIDEBAR ====== -->
        <div class="col-md-3">

            <!-- Profile Preview Card -->
            <div class="card settings-card shadow-sm mb-3">
                <div class="card-body text-center p-4">
                    <div class="settings-avatar mx-auto mb-3"><?php echo $initials; ?></div>
                    <div class="fw-bold mb-1" style="color:var(--text-main)"><?php echo htmlspecialchars($me['full_name']); ?></div>
                    <div class="small text-muted mb-2"><?php echo htmlspecialchars($me['email']); ?></div>
                    <span class="badge rounded-pill px-3 py-1 <?php echo ($me['role'] == 'Super Admin') ? 'bg-danger' : 'bg-primary'; ?>" style="font-size:.75rem;">
                        <?php echo $me['role']; ?>
                    </span>
                </div>
            </div>

            <!-- Theme Toggle Card -->
            <div class="theme-card shadow-sm mb-3 p-3">
                <?php $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light'; ?>
                <div class="small fw-bold text-white opacity-50 mb-2 text-uppercase" style="font-size:.7rem;letter-spacing:.5px;">Current Theme</div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas <?php echo ($theme == 'dark') ? 'fa-moon text-warning' : 'fa-sun text-warning'; ?> fa-lg"></i>
                    <span class="fw-semibold text-white"><?php echo ($theme == 'dark') ? 'Dark Mode' : 'Light Mode'; ?></span>
                </div>
                <form action="toggle_theme.php" method="POST">
                    <button class="btn btn-sm w-100 fw-semibold" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.2);border-radius:8px;">
                        <i class="fas <?php echo ($theme == 'dark') ? 'fa-sun' : 'fa-moon'; ?> me-1"></i>
                        Switch to <?php echo ($theme == 'dark') ? 'Light' : 'Dark'; ?>
                    </button>
                </form>
            </div>

            <!-- Public Notice (Super Admin only) -->
            <?php if ($my_role == 'Super Admin'): $set = $conn->query("SELECT * FROM system_settings WHERE id=1")->fetch_assoc(); ?>
                <div class="notice-card shadow-sm mb-3 p-3">
                    <div class="small fw-bold text-warning text-uppercase mb-2" style="font-size:.7rem;letter-spacing:.5px;">
                        <i class="fas fa-bullhorn me-1"></i> Public Notice
                    </div>
                    <form method="POST">
                        <textarea name="notice_msg" class="form-control mb-2" rows="2" required
                            style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.15);border-radius:8px;font-size:.85rem;"
                        ><?php echo htmlspecialchars($set['notice_text']); ?></textarea>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="notice_status" value="1" id="nSw" <?php if ($set['is_active']) echo 'checked'; ?>>
                            <label class="form-check-label small text-white opacity-75" for="nSw">Show Notification</label>
                        </div>
                        <button name="update_notice" class="btn btn-sm btn-warning w-100 fw-bold" style="border-radius:8px;">Update Notice</button>
                    </form>
                </div>
            <?php endif; ?>

        </div><!-- /col-md-3 -->

        <!-- ====== RIGHT CONTENT ====== -->
        <div class="col-md-9">

            <!-- MY PROFILE CARD -->
            <div class="card settings-card shadow-sm mb-4">
                <div class="card-header py-3 px-4 d-flex align-items-center gap-3">
                    <div class="section-icon bg-primary bg-opacity-10">
                        <i class="fas fa-id-card text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:var(--text-main)">My Account Details</div>
                        <div class="small text-muted">Update your name and email address</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--text-main)">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="my_fullname" class="form-control" value="<?php echo htmlspecialchars($me['full_name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--text-main)">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="my_email" class="form-control" value="<?php echo htmlspecialchars($me['email']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <button name="update_my_profile" class="btn btn-save-profile w-100">
                            <i class="fas fa-save me-2"></i>Save Account Details
                        </button>
                    </form>
                </div>
            </div>

            <!-- PASSWORD CARD -->
            <div class="card settings-card shadow-sm">
                <div class="card-header py-3 px-4 d-flex align-items-center gap-3">
                    <div class="section-icon bg-dark bg-opacity-10">
                        <i class="fas fa-lock text-muted"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:var(--text-main)">Change Password</div>
                        <div class="small text-muted">Keep your account safe with a strong password</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--text-main)">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="curr_pass" class="form-control" required placeholder="Enter current password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-success">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password" name="new_pass" class="form-control" required placeholder="Min 4 characters">
                                </div>
                            </div>
                        </div>
                        <button name="change_pass" class="btn btn-change-pass w-100">
                            <i class="fas fa-shield-alt me-2"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div><!-- /col-md-9 -->
    </div><!-- /row -->
</div><!-- closes container from header -->

<?php include '../layout/footer.php'; ?>