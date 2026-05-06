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
/* ===== SETTINGS PAGE — ENHANCED UI/UX ===== */

/* ── Page-level entrance animation ── */
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-in { animation: fadeSlideUp .45s ease both; }
.anim-in-1 { animation-delay: .06s; }
.anim-in-2 { animation-delay: .12s; }
.anim-in-3 { animation-delay: .18s; }

/* ── Sub-nav pill tabs ── */
.page-sub-nav {
    display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 28px;
}
.page-sub-nav a {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; border-radius: 50px; font-size: .84rem;
    font-weight: 600; text-decoration: none; transition: all .25s;
    border: 1.5px solid var(--edl-border); color: var(--text-muted);
    backdrop-filter: blur(6px);
}
.page-sub-nav a:hover {
    background: rgba(99,102,241,0.1); color: #818cf8;
    border-color: rgba(99,102,241,.5);
}
.page-sub-nav a.active {
    background: linear-gradient(135deg,#6366f1,#9333ea);
    color: #fff !important; border-color: transparent;
    box-shadow: 0 4px 18px rgba(99,102,241,.38);
}

/* ── Enhanced Cards ── */
.s-card {
    border-radius: 20px !important; border: 1.5px solid var(--edl-border) !important;
    box-shadow: 0 8px 32px rgba(0,0,0,.07) !important;
    background: var(--bg-card) !important;
    backdrop-filter: var(--card-filter);
    overflow: hidden;
    transition: box-shadow .3s, transform .3s;
}
.s-card:hover { box-shadow: 0 14px 40px rgba(0,0,0,.12) !important; }
.s-card .card-header {
    background: transparent !important;
    border-bottom: 1.5px solid var(--edl-border) !important;
    padding: 18px 24px;
}

/* ── Profile sidebar ── */
.profile-banner {
    height: 72px;
    background: linear-gradient(135deg, #6366f1 0%, #9333ea 60%, #a855f7 100%);
    position: relative;
}
.profile-avatar-wrap {
    position: relative; margin-top: -36px; margin-bottom: 10px;
}
.profile-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#a855f7);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 800; color: #fff;
    border: 3px solid var(--bg-card);
    box-shadow: 0 8px 24px rgba(99,102,241,.45);
    margin: 0 auto;
}
.role-badge-super { background: linear-gradient(135deg,#dc2626,#ef4444) !important; }
.role-badge-officer { background: linear-gradient(135deg,#2563eb,#3b82f6) !important; }

/* ── Section icon ── */
.s-section-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

/* ── Form inputs (enhanced) ── */
.s-input-group .input-group-text {
    border-radius: 12px 0 0 12px !important;
    border-right: none !important;
    background: var(--input-bg) !important;
    border-color: var(--edl-border) !important;
    color: var(--text-muted);
    padding: 0 14px;
}
.s-input-group .form-control,
.s-input-group .form-select {
    border-radius: 0 12px 12px 0 !important;
    border-left: none !important;
    background: var(--input-bg) !important;
    border-color: var(--edl-border) !important;
    color: var(--text-main) !important;
    padding: 11px 14px;
    font-size: .9rem;
    transition: all .25s;
}
.s-input-group .form-control:focus,
.s-input-group .form-select:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,.18) !important;
    background: var(--input-focus) !important;
}
/* password toggle btn inside input */
.s-input-group .pass-toggle {
    border-radius: 0 12px 12px 0 !important;
    border-left: none !important;
    background: var(--input-bg) !important;
    border-color: var(--edl-border) !important;
    color: var(--text-muted);
    transition: color .2s;
}
.s-input-group .pass-toggle:hover { color: #6366f1; }
.s-input-group .form-control.mid-input {
    border-radius: 0 !important;
    border-left: none !important;
    border-right: none !important;
}

/* ── Buttons ── */
.btn-save {
    background: linear-gradient(135deg,#6366f1,#9333ea);
    color: #fff; border-radius: 12px; padding: 12px 20px;
    font-weight: 700; border: none; transition: all .3s;
    box-shadow: 0 6px 20px rgba(99,102,241,.3); width: 100%;
    font-size: .92rem; letter-spacing: .3px;
}
.btn-save:hover { transform: translateY(-2px); color: #fff;
    box-shadow: 0 10px 28px rgba(99,102,241,.45); }
.btn-save:active { transform: translateY(0); }

.btn-change-pass {
    background: linear-gradient(135deg,#0f172a,#1e293b);
    color: #fff; border-radius: 12px; padding: 12px 20px;
    font-weight: 700; border: none; transition: all .3s; width: 100%;
    font-size: .92rem;
}
.btn-change-pass:hover { opacity: .9; transform: translateY(-1px); color: #fff; }

/* ── Theme toggle card ── */
.theme-pill-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg,#0f172a,#1e293b) !important;
    border: 1.5px solid rgba(255,255,255,.07) !important;
}
.theme-toggle-btn {
    background: rgba(255,255,255,.1) !important;
    color: #fff !important;
    border: 1.5px solid rgba(255,255,255,.18) !important;
    border-radius: 10px; font-size: .82rem; font-weight: 600;
    transition: all .25s;
}
.theme-toggle-btn:hover {
    background: rgba(255,255,255,.2) !important;
    border-color: rgba(255,255,255,.35) !important;
}

/* ── Notice card ── */
.notice-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg,#020617,#1e293b) !important;
    border: 1.5px solid rgba(251,191,36,.15) !important;
}
.notice-textarea {
    background: rgba(255,255,255,.07) !important;
    color: #fff !important;
    border: 1.5px solid rgba(255,255,255,.13) !important;
    border-radius: 10px !important;
    font-size: .85rem;
    resize: none;
}
.notice-textarea:focus {
    background: rgba(255,255,255,.1) !important;
    border-color: rgba(251,191,36,.4) !important;
    box-shadow: 0 0 0 3px rgba(251,191,36,.12) !important;
}

/* ── Alert banners ── */
.s-alert-success {
    background: rgba(16,185,129,.13);
    color: #10b981;
    border-radius: 14px; border: none;
    padding: 14px 18px; font-weight: 600; font-size: .88rem;
}
.s-alert-error {
    background: rgba(239,68,68,.13);
    color: #ef4444;
    border-radius: 14px; border: none;
    padding: 14px 18px; font-weight: 600; font-size: .88rem;
}

/* ── Form label ── */
.f-label {
    font-size: .8rem; font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 7px;
}
</style>

    <!-- PAGE HEADER -->
    <div class="d-flex align-items-center gap-3 mb-3 anim-in">
        <div class="s-section-icon" style="background:rgba(99,102,241,.12)">
            <i class="fas fa-user-shield" style="color:#818cf8"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--text-main)">Account Security</h4>
            <p class="text-muted small mb-0">Manage your profile and password</p>
        </div>
    </div>

    <!-- SUB NAV PILLS -->
    <div class="page-sub-nav anim-in">
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

    <?php if ($msg): ?>
    <div class="s-alert-success d-flex align-items-center gap-2 mb-4 anim-in">
        <i class="fas fa-check-circle fa-lg"></i> <?php echo $msg; ?>
    </div>
    <?php endif; ?>

    <?php if ($err): ?>
    <div class="s-alert-error d-flex align-items-center gap-2 mb-4 anim-in">
        <i class="fas fa-exclamation-circle fa-lg"></i> <?php echo $err; ?>
    </div>
    <?php endif; ?>

    <div class="row g-4">

        <!-- ====== LEFT SIDEBAR ====== -->
        <div class="col-md-3 anim-in anim-in-1">

            <!-- Profile Preview Card -->
            <div class="s-card mb-3">
                <div class="profile-banner"></div>
                <div class="card-body text-center px-4 pb-4 pt-0">
                    <div class="profile-avatar-wrap">
                        <div class="profile-avatar"><?php echo $initials; ?></div>
                    </div>
                    <div class="fw-bold mb-1" style="color:var(--text-main);font-size:.95rem"><?php echo htmlspecialchars($me['full_name']); ?></div>
                    <div class="small mb-2" style="color:var(--text-muted)"><?php echo htmlspecialchars($me['email']); ?></div>
                    <span class="badge rounded-pill px-3 py-1 text-white
                        <?php echo ($me['role'] == 'Super Admin') ? 'role-badge-super' : 'role-badge-officer'; ?>"
                        style="font-size:.72rem; font-weight:700;">
                        <?php echo ($me['role'] == 'Super Admin') ? '⚡ Super Admin' : '👤 Officer'; ?>
                    </span>
                </div>
            </div>

            <!-- Theme Toggle Card -->
            <div class="theme-pill-card shadow-sm mb-3 p-3">
                <?php $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light'; ?>
                <div class="small fw-bold text-white mb-2 text-uppercase" style="opacity:.45;font-size:.68rem;letter-spacing:.8px">Current Theme</div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas <?php echo ($theme == 'dark') ? 'fa-moon' : 'fa-sun'; ?> fa-lg text-warning"></i>
                    <span class="fw-semibold text-white"><?php echo ($theme == 'dark') ? 'Dark Mode' : 'Light Mode'; ?></span>
                </div>
                <form action="toggle_theme.php" method="POST">
                    <button class="btn btn-sm w-100 theme-toggle-btn">
                        <i class="fas <?php echo ($theme == 'dark') ? 'fa-sun' : 'fa-moon'; ?> me-2"></i>
                        Switch to <?php echo ($theme == 'dark') ? 'Light' : 'Dark'; ?>
                    </button>
                </form>
            </div>

            <!-- Public Notice (Super Admin only) -->
            <?php if ($my_role == 'Super Admin'): $set = $conn->query("SELECT * FROM system_settings WHERE id=1")->fetch_assoc(); ?>
                <div class="notice-card shadow-sm p-3">
                    <div class="small fw-bold text-warning text-uppercase mb-2" style="font-size:.68rem;letter-spacing:.8px">
                        <i class="fas fa-bullhorn me-1"></i> Public Notice
                    </div>
                    <form method="POST">
                        <textarea name="notice_msg" class="form-control notice-textarea mb-2" rows="3" required
                        ><?php echo htmlspecialchars($set['notice_text']); ?></textarea>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="notice_status" value="1" id="nSw"
                                <?php if ($set['is_active']) echo 'checked'; ?>>
                            <label class="form-check-label small text-white" for="nSw" style="opacity:.7">Show Notification</label>
                        </div>
                        <button name="update_notice" class="btn btn-sm btn-warning w-100 fw-bold" style="border-radius:10px;">
                            <i class="fas fa-broadcast-tower me-1"></i> Update Notice
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div><!-- /col-md-3 -->

        <!-- ====== RIGHT CONTENT ====== -->
        <div class="col-md-9">

            <!-- MY PROFILE CARD -->
            <div class="s-card mb-4 anim-in anim-in-2">
                <div class="card-header d-flex align-items-center gap-3">
                    <div class="s-section-icon" style="background:rgba(99,102,241,.12)">
                        <i class="fas fa-id-card" style="color:#818cf8"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:var(--text-main)">My Account Details</div>
                        <div class="small" style="color:var(--text-muted)">Update your display name and email address</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="f-label">Full Name</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="my_fullname" class="form-control"
                                        value="<?php echo htmlspecialchars($me['full_name']); ?>" required
                                        placeholder="Your full name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Email Address</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="my_email" class="form-control"
                                        value="<?php echo htmlspecialchars($me['email']); ?>" required
                                        placeholder="your@email.com">
                                </div>
                            </div>
                        </div>
                        <button name="update_my_profile" class="btn btn-save">
                            <i class="fas fa-save me-2"></i>Save Account Details
                        </button>
                    </form>
                </div>
            </div>

            <!-- PASSWORD CARD -->
            <div class="s-card anim-in anim-in-3">
                <div class="card-header d-flex align-items-center gap-3">
                    <div class="s-section-icon" style="background:rgba(15,23,42,.15)">
                        <i class="fas fa-lock" style="color:var(--text-muted)"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:var(--text-main)">Change Password</div>
                        <div class="small" style="color:var(--text-muted)">Keep your account safe with a strong password</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="f-label">Current Password</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="curr_pass" id="currPass" class="form-control mid-input"
                                        required placeholder="Enter current password">
                                    <button type="button" class="btn pass-toggle" onclick="togglePass('currPass','eyeCurr')">
                                        <i class="fas fa-eye" id="eyeCurr"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="f-label" style="color:#10b981">New Password</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" name="new_pass" id="newPass" class="form-control mid-input"
                                        required placeholder="Minimum 4 characters"
                                        oninput="checkPwdStrength(this.value)">
                                    <button type="button" class="btn pass-toggle" onclick="togglePass('newPass','eyeNew')">
                                        <i class="fas fa-eye" id="eyeNew"></i>
                                    </button>
                                </div>
                                <!-- Strength bar -->
                                <div class="mt-2" id="strWrap" style="display:none">
                                    <div style="height:4px;border-radius:4px;background:var(--edl-border);overflow:hidden;">
                                        <div id="strBar" style="height:100%;width:0;border-radius:4px;transition:all .4s;"></div>
                                    </div>
                                    <div id="strLabel" class="small mt-1 fw-semibold" style="font-size:.75rem;"></div>
                                </div>
                            </div>
                        </div>
                        <button name="change_pass" class="btn btn-change-pass">
                            <i class="fas fa-shield-alt me-2"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div><!-- /col-md-9 -->
    </div><!-- /row -->
</div><!-- closes container from header -->

<script>
function togglePass(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        ico.className = 'fas fa-eye';
    }
}

function checkPwdStrength(val) {
    const wrap  = document.getElementById('strWrap');
    const bar   = document.getElementById('strBar');
    const label = document.getElementById('strLabel');
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';
    let score = 0;
    if (val.length >= 4)  score++;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        {w:'20%', c:'#ef4444', t:'Very Weak'},
        {w:'40%', c:'#f97316', t:'Weak'},
        {w:'60%', c:'#f59e0b', t:'Fair'},
        {w:'80%', c:'#3b82f6', t:'Good'},
        {w:'100%',c:'#10b981', t:'Strong'},
    ];
    const lvl = levels[Math.max(0, score - 1)];
    bar.style.width      = lvl.w;
    bar.style.background = lvl.c;
    label.innerText      = lvl.t;
    label.style.color    = lvl.c;
}
</script>

<?php include '../layout/footer.php'; ?>