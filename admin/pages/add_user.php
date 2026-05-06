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
/* ===== ADD USER PAGE — ENHANCED UI/UX ===== */
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-in { animation: fadeSlideUp .45s ease both; }
.anim-in-1 { animation-delay: .06s; }
.anim-in-2 { animation-delay: .12s; }

/* ── Sub-nav ── */
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
.page-sub-nav a:hover { background: rgba(99,102,241,.1); color: #818cf8; border-color: rgba(99,102,241,.5); }
.page-sub-nav a.active {
    background: linear-gradient(135deg,#6366f1,#9333ea);
    color: #fff !important; border-color: transparent;
    box-shadow: 0 4px 18px rgba(99,102,241,.38);
}

/* ── Cards ── */
.s-card {
    border-radius: 20px !important; border: 1.5px solid var(--edl-border) !important;
    box-shadow: 0 8px 32px rgba(0,0,0,.07) !important;
    background: var(--bg-card) !important;
    backdrop-filter: var(--card-filter);
    overflow: hidden;
    transition: box-shadow .3s;
}
.s-card:hover { box-shadow: 0 14px 40px rgba(0,0,0,.12) !important; }
.s-card .card-header {
    background: transparent !important;
    border-bottom: 1.5px solid var(--edl-border) !important;
    padding: 18px 24px;
}

/* ── Profile banner ── */
.profile-banner { height: 72px; background: linear-gradient(135deg,#6366f1,#9333ea 60%,#a855f7); }
.profile-avatar-wrap { position: relative; margin-top: -36px; margin-bottom: 10px; }
.profile-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#a855f7);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 800; color: #fff;
    border: 3px solid var(--bg-card);
    box-shadow: 0 8px 24px rgba(99,102,241,.45);
    margin: 0 auto; transition: all .3s;
}

/* ── Live Preview ── */
.preview-name { font-size: .95rem; font-weight: 700; color: var(--text-main); transition: all .3s; }
.preview-uname { font-size: .8rem; color: var(--text-muted); transition: all .3s; }
.preview-role-badge {
    font-size: .72rem; font-weight: 700; border-radius: 50px;
    padding: 3px 12px; color: #fff; display: inline-block; margin-top: 6px;
    background: linear-gradient(135deg,#2563eb,#3b82f6);
    transition: all .3s;
}
.preview-role-badge.is-admin { background: linear-gradient(135deg,#dc2626,#ef4444); }

/* ── Section icon ── */
.s-section-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* ── Form field divider ── */
.form-section-divider {
    display: flex; align-items: center; gap: 12px;
    margin: 22px 0 18px;
}
.form-section-divider hr { flex: 1; margin: 0; border-color: var(--edl-border); }
.form-section-divider span {
    font-size: .7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .8px; color: var(--text-muted); white-space: nowrap;
}

/* ── Inputs ── */
.s-input-group .input-group-text {
    border-radius: 12px 0 0 12px !important; border-right: none !important;
    background: var(--input-bg) !important; border-color: var(--edl-border) !important;
    color: var(--text-muted); padding: 0 14px;
}
.s-input-group .form-control,
.s-input-group .form-select {
    border-radius: 0 12px 12px 0 !important; border-left: none !important;
    background: var(--input-bg) !important; border-color: var(--edl-border) !important;
    color: var(--text-main) !important; padding: 11px 14px; font-size: .9rem;
    transition: all .25s;
}
.s-input-group .form-control:focus,
.s-input-group .form-select:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,.18) !important;
    background: var(--input-focus) !important;
}
.s-input-group .form-control.mid-input {
    border-radius: 0 !important; border-right: none !important;
}
.s-input-group .pass-toggle {
    border-radius: 0 12px 12px 0 !important; border-left: none !important;
    background: var(--input-bg) !important; border-color: var(--edl-border) !important;
    color: var(--text-muted); transition: color .2s;
}
.s-input-group .pass-toggle:hover { color: #6366f1; }

/* ── Strength bar ── */
.str-track { height: 5px; border-radius: 5px; background: var(--edl-border); overflow: hidden; margin-top: 8px; }
.str-fill  { height: 100%; border-radius: 5px; transition: width .4s ease, background .4s; }

/* ── Info tip ── */
.tip-card {
    border-radius: 16px !important; border: 1.5px solid rgba(16,185,129,.25) !important;
    background: rgba(16,185,129,.06) !important;
    padding: 14px;
}

/* ── Theme card ── */
.theme-pill-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg,#0f172a,#1e293b) !important;
    border: 1.5px solid rgba(255,255,255,.07) !important;
}
.theme-toggle-btn {
    background: rgba(255,255,255,.1) !important; color: #fff !important;
    border: 1.5px solid rgba(255,255,255,.18) !important;
    border-radius: 10px; font-size: .82rem; font-weight: 600; transition: all .25s;
}
.theme-toggle-btn:hover { background: rgba(255,255,255,.2) !important; }

/* ── Submit button ── */
.btn-create {
    background: linear-gradient(135deg,#059669,#10b981);
    color: #fff; border-radius: 14px; padding: 14px 20px;
    font-weight: 700; border: none; transition: all .3s; width: 100%;
    font-size: .95rem; letter-spacing: .3px;
    box-shadow: 0 6px 20px rgba(16,185,129,.3);
    position: relative; overflow: hidden;
}
.btn-create:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 10px 28px rgba(16,185,129,.45); }
.btn-create:active { transform: translateY(0); }

/* ── Alerts ── */
.s-alert-success { background: rgba(16,185,129,.13); color: #10b981; border-radius: 14px; border: none; padding: 14px 18px; font-weight: 600; font-size: .88rem; }
.s-alert-error   { background: rgba(239,68,68,.13);  color: #ef4444;  border-radius: 14px; border: none; padding: 14px 18px; font-weight: 600; font-size: .88rem; }

.f-label { font-size: .78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 7px; display: block; }
</style>

    <!-- PAGE HEADER -->
    <div class="d-flex align-items-center gap-3 mb-3 anim-in">
        <div class="s-section-icon" style="background:rgba(16,185,129,.12)">
            <i class="fas fa-user-plus text-success"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--text-main)">Add New Officer</h4>
            <p class="text-muted small mb-0">Register a new system account</p>
        </div>
    </div>

    <!-- SUB NAV PILLS -->
    <div class="page-sub-nav anim-in">
        <a href="settings"><i class="fas fa-user-shield"></i> Account Security</a>
        <a href="add_user" class="active"><i class="fas fa-user-plus"></i> Add New Officer</a>
        <a href="manage_users"><i class="fas fa-users-cog"></i> Manage Users</a>
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

        <!-- LEFT: Sidebar -->
        <div class="col-md-3 anim-in anim-in-1">

            <!-- Profile Preview (Live) -->
            <div class="s-card mb-3">
                <div class="profile-banner"></div>
                <div class="card-body text-center px-4 pb-4 pt-0">
                    <div class="profile-avatar-wrap">
                        <div class="profile-avatar" id="prev_avatar">?</div>
                    </div>
                    <div class="preview-name" id="prev_name">New Officer</div>
                    <div class="preview-uname" id="prev_uname">@username</div>
                    <div class="preview-role-badge" id="prev_role">👤 Officer</div>
                </div>
            </div>

            <!-- Theme Toggle -->
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

            <!-- Info tip -->
            <div class="tip-card">
                <div class="d-flex gap-2 align-items-start">
                    <i class="fas fa-info-circle text-success mt-1 flex-shrink-0"></i>
                    <div class="small" style="color:var(--text-muted)">
                        New accounts are <strong style="color:#10b981">auto-activated</strong> and credentials are sent to their email automatically.
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Add User Form -->
        <div class="col-md-9 anim-in anim-in-2">
            <div class="s-card">
                <div class="card-header d-flex align-items-center gap-3">
                    <div class="s-section-icon" style="background:rgba(16,185,129,.12)">
                        <i class="fas fa-user-plus text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:var(--text-main)">Register New Officer</div>
                        <div class="small" style="color:var(--text-muted)">Fill in the details below to create a system account</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" id="addUserForm">

                        <!-- Section: Identity -->
                        <div class="form-section-divider">
                            <span><i class="fas fa-id-card me-1"></i> Identity</span><hr>
                        </div>
                        <div class="row g-3 mb-1">
                            <div class="col-md-6">
                                <label class="f-label">Full Name</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="u_n" id="inp_name" class="form-control"
                                        placeholder="Officer full name" required
                                        oninput="updatePreview()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Access Level</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                    <select name="u_r" id="inp_role" class="form-select" onchange="updatePreview()">
                                        <option value="Officer">👤 Standard Officer</option>
                                        <option value="Super Admin">⚡ Super Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Contact -->
                        <div class="form-section-divider mt-4">
                            <span><i class="fas fa-envelope me-1"></i> Contact</span><hr>
                        </div>
                        <div class="mb-1">
                            <label class="f-label">Email Address</label>
                            <div class="input-group s-input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="u_e" class="form-control"
                                    placeholder="officer@ceb.lk" required>
                            </div>
                        </div>

                        <!-- Section: Credentials -->
                        <div class="form-section-divider mt-4">
                            <span><i class="fas fa-key me-1"></i> Login Credentials</span><hr>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="f-label">Username</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    <input type="text" name="u_u" id="inp_uname" class="form-control"
                                        placeholder="Login username" required
                                        oninput="updatePreview()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Initial Password</label>
                                <div class="input-group s-input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" name="u_p" id="u_p" class="form-control mid-input"
                                        placeholder="Temporary password" required
                                        oninput="checkStrength(this.value)">
                                    <button type="button" class="btn pass-toggle" onclick="togglePass('u_p','eyePass')">
                                        <i class="fas fa-eye" id="eyePass"></i>
                                    </button>
                                </div>
                                <div id="strWrap" style="display:none">
                                    <div class="str-track">
                                        <div class="str-fill" id="strengthBar" style="width:0;background:#ef4444;"></div>
                                    </div>
                                    <div class="small fw-semibold mt-1" id="strengthLabel" style="font-size:.74rem;"></div>
                                </div>
                            </div>
                        </div>

                        <button name="add_user" class="btn btn-create" id="submitBtn">
                            <i class="fas fa-user-check me-2"></i>Create Account &amp; Send Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div><!-- /col-md-9 -->

    </div><!-- /row -->
</div><!-- /container -->

<script>
function updatePreview() {
    const name  = document.getElementById('inp_name').value.trim();
    const uname = document.getElementById('inp_uname').value.trim();
    const role  = document.getElementById('inp_role').value;

    // Avatar initials
    const initials = name
        ? name.split(' ').filter(Boolean).map(w => w[0]).join('').toUpperCase().slice(0, 2)
        : '?';
    document.getElementById('prev_avatar').innerText = initials;
    document.getElementById('prev_name').innerText   = name  || 'New Officer';
    document.getElementById('prev_uname').innerText  = uname ? '@' + uname : '@username';

    const badge = document.getElementById('prev_role');
    if (role === 'Super Admin') {
        badge.innerText = '⚡ Super Admin';
        badge.className = 'preview-role-badge is-admin';
    } else {
        badge.innerText = '👤 Officer';
        badge.className = 'preview-role-badge';
    }
}

function togglePass(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    inp.type = (inp.type === 'password') ? 'text' : 'password';
    ico.className = (inp.type === 'password') ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function checkStrength(val) {
    const wrap  = document.getElementById('strWrap');
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';
    let score = 0;
    if (val.length >= 4)            score++;
    if (val.length >= 8)            score++;
    if (/[A-Z]/.test(val))          score++;
    if (/[0-9]/.test(val))          score++;
    if (/[^A-Za-z0-9]/.test(val))   score++;
    const levels = [
        {w:'20%', c:'#ef4444', t:'Very Weak'},
        {w:'40%', c:'#f97316', t:'Weak'},
        {w:'60%', c:'#f59e0b', t:'Fair'},
        {w:'80%', c:'#3b82f6', t:'Good'},
        {w:'100%',c:'#10b981', t:'Strong ✓'},
    ];
    const lvl = levels[Math.max(0, score - 1)];
    bar.style.width      = lvl.w;
    bar.style.background = lvl.c;
    label.innerText      = lvl.t;
    label.style.color    = lvl.c;
}

// Submit button loading state
document.getElementById('addUserForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account…';
    btn.disabled = true;
});
</script>

<?php include '../layout/footer.php'; ?>
