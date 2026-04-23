<?php
// ============================================
// 1. AUTH MIDDLEWARE
// ============================================
require_once '../middleware/superAdminGuard.php';

// ============================================
// 2. CONTROLLER LOGIC (Add User)
// ============================================
require_once '../controllers/AddUserController.php';

// Fetch current user info for sidebar
$me       = $conn->query("SELECT full_name, email, role, username FROM users WHERE id='$current_user_id'")->fetch_assoc();
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', trim($me['full_name'])))));
$initials = substr($initials, 0, 2);

include '../layout/header.php';
?>

<style>
.settings-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #a855f7);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 700; color: #fff;
    box-shadow: 0 8px 25px rgba(99,102,241,0.35);
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
    color: #fff !important; border-color: transparent;
    box-shadow: 0 4px 15px rgba(99,102,241,0.4);
}
.theme-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    box-shadow: 0 8px 25px rgba(0,0,0,.2);
}
.btn-add-user {
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff; border-radius: 12px; padding: 12px; font-weight: 600;
    border: none; transition: .3s; box-shadow: 0 6px 18px rgba(16,185,129,.3);
}
.btn-add-user:hover { transform: translateY(-2px) scale(1.01); color: #fff; opacity:.9; }

/* Password strength bar */
.strength-bar { height: 4px; border-radius: 4px; transition: all .4s; }
</style>

    <!-- PAGE HEADER -->
    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="section-icon bg-success bg-opacity-10">
            <i class="fas fa-user-plus text-success"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--text-main)">Add New Officer</h4>
            <p class="text-muted small mb-0">Register a new system account</p>
        </div>
    </div>

    <!-- SUB NAV PILLS -->
    <div class="page-sub-nav">
        <a href="settings">
            <i class="fas fa-user-shield"></i> Account Security
        </a>
        <a href="add_user" class="active">
            <i class="fas fa-user-plus"></i> Add New Officer
        </a>
        <a href="manage_users">
            <i class="fas fa-users-cog"></i> Manage Users
        </a>
    </div>

    <?php if ($msg) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:rgba(16,185,129,.15);color:#10b981;border-radius:12px'><i class='fas fa-check-circle'></i> $msg</div>"; ?>
    <?php if ($err) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:rgba(239,68,68,.15);color:#ef4444;border-radius:12px'><i class='fas fa-exclamation-circle'></i> $err</div>"; ?>

    <div class="row g-4">

        <!-- LEFT: Profile card + tip -->
        <div class="col-md-3">
            <div class="card settings-card shadow-sm mb-3">
                <div class="card-body text-center p-4">
                    <div class="settings-avatar mx-auto mb-3"><?php echo $initials; ?></div>
                    <div class="fw-bold mb-1" style="color:var(--text-main)"><?php echo htmlspecialchars($me['full_name']); ?></div>
                    <div class="small text-muted mb-2"><?php echo htmlspecialchars($me['email']); ?></div>
                    <span class="badge rounded-pill px-3 py-1 bg-danger" style="font-size:.75rem;">
                        <?php echo $me['role']; ?>
                    </span>
                </div>
            </div>

            <!-- Theme Toggle -->
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

            <!-- Info tip -->
            <div class="card settings-card p-3" style="border-left: 3px solid #10b981 !important;">
                <div class="d-flex gap-2 align-items-start">
                    <i class="fas fa-info-circle text-success mt-1"></i>
                    <div class="small text-muted">
                        New accounts are <strong style="color:#10b981">auto-activated</strong> and credentials are sent to their email automatically.
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Add User Form -->
        <div class="col-md-9">
            <div class="card settings-card shadow-sm">
                <div class="card-header py-3 px-4 d-flex align-items-center gap-3">
                    <div class="section-icon bg-success bg-opacity-10">
                        <i class="fas fa-user-plus text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:var(--text-main)">Register New Officer</div>
                        <div class="small text-muted">Fill in the details below to create a system account</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" id="addUserForm">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--text-main)">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="u_n" class="form-control" placeholder="Officer full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--text-main)">Access Level</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-shield-alt text-muted"></i></span>
                                    <select name="u_r" class="form-select">
                                        <option value="Officer">Standard Officer</option>
                                        <option value="Super Admin">Super Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color:var(--text-main)">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="u_e" class="form-control" placeholder="officer@edl.com" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--text-main)">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-at text-muted"></i></span>
                                    <input type="text" name="u_u" class="form-control" placeholder="Login username" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" style="color:var(--text-main)">Initial Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key text-muted"></i></span>
                                    <input type="text" name="u_p" id="u_p" class="form-control" placeholder="Temporary password" required oninput="checkStrength(this.value)">
                                </div>
                                <div class="mt-2">
                                    <div id="strengthBar" class="strength-bar" style="width:0%;background:#ef4444;"></div>
                                    <div class="small text-muted mt-1" id="strengthLabel"></div>
                                </div>
                            </div>
                        </div>

                        <button name="add_user" class="btn btn-add-user w-100">
                            <i class="fas fa-user-check me-2"></i>Create Account &amp; Send Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div><!-- /col-md-9 -->

    </div><!-- /row -->
</div><!-- /container -->

<script>
function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 4)  score++;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const map = [
        {w:'0%',   c:'#ef4444', t:''},
        {w:'25%',  c:'#ef4444', t:'Weak'},
        {w:'50%',  c:'#f59e0b', t:'Fair'},
        {w:'75%',  c:'#3b82f6', t:'Good'},
        {w:'100%', c:'#10b981', t:'Strong'},
        {w:'100%', c:'#10b981', t:'Strong'},
    ];
    bar.style.width      = map[score].w;
    bar.style.background = map[score].c;
    label.innerText      = map[score].t;
    label.style.color    = map[score].c;
}
</script>

<?php include '../layout/footer.php'; ?>
