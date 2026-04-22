<?php
// ============================================
// 1. AUTH MIDDLEWARE (Security, DB, Session Vars)
// ============================================
require_once '../middleware/authGuard.php';

// ============================================
// 2. CONTROLLER LOGIC (Settings)
// ============================================
require_once '../controllers/SettingController.php';



include '../layout/header.php';
?>

<style>
/* ===== AVATAR ===== */
.settings-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #a855f7);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 8px 25px rgba(99,102,241,0.35);
}

/* ===== NAV ===== */
.settings-nav .list-group-item {
    border: none;
    border-radius: 12px !important;
    margin-bottom: 6px;
    padding: 12px 16px;
    font-weight: 500;
    color: #64748b;
    transition: all .25s ease;
}

.settings-nav .list-group-item:hover {
    background: #eef2ff;
    color: #3730a3;
    transform: translateX(3px);
}

.settings-nav .list-group-item.active {
    background: linear-gradient(135deg, #6366f1, #9333ea) !important;
    color: #fff !important;
    box-shadow: 0 6px 20px rgba(99,102,241,0.4);
}

/* ===== CARDS ===== */
.settings-card {
    border-radius: 18px !important;
    border: none !important;
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    transition: 0.3s;
}

.settings-card:hover {
    transform: translateY(-2px);
}

.settings-card .card-header {
    background: transparent !important;
    border-bottom: 1px solid #e2e8f0 !important;
}

/* ===== ICON ===== */
.section-icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ===== INPUT ===== */
.form-control,
.form-select {
    border-radius: 12px !important;
    border: 1px solid #e2e8f0;
    padding: 10px 14px;
    font-size: .9rem;
    transition: .2s;
}

.form-control:focus,
.form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.15);
}

/* ===== INPUT GROUP ===== */
.input-group-text {
    border-radius: 12px 0 0 12px !important;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}

/* ===== BUTTONS ===== */
.btn-save-profile {
    background: linear-gradient(135deg, #6366f1, #9333ea);
    color: #fff;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    border: none;
    transition: .3s;
    box-shadow: 0 6px 18px rgba(99,102,241,.3);
}

.btn-save-profile:hover {
    transform: translateY(-2px) scale(1.01);
}

.btn-change-pass {
    background: #0f172a;
    color: #fff;
    border-radius: 12px;
    padding: 12px;
    transition: .3s;
}

.btn-change-pass:hover {
    background: #020617;
}

/* ===== THEME CARD ===== */
.theme-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    box-shadow: 0 8px 25px rgba(0,0,0,.2);
}

/* ===== NOTICE CARD ===== */
.notice-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg, #020617, #1e293b);
}

/* ===== TABLE ===== */
.table tr {
    transition: .2s;
}

.table tr:hover {
    background: #f1f5f9;
}

/* ===== USER AVATAR SMALL ===== */
.user-row-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #9333ea);
    color: #fff;
    font-size: .75rem;
    font-weight: 700;
}

/* ===== MODAL ===== */
.modal-content {
    border-radius: 20px !important;
    overflow: hidden;
}

.modal-header-gradient {
    background: linear-gradient(135deg, #6366f1, #9333ea);
}

/* ===== EDIT AVATAR ===== */
.edit-user-avatar {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #9333ea);
    font-size: 1.4rem;
    font-weight: bold;
    box-shadow: 0 6px 18px rgba(99,102,241,.4);
}
</style>

    <!-- PAGE HEADER -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="section-icon bg-primary bg-opacity-10">
            <i class="fas fa-cog text-primary"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0 text-dark">System Settings</h4>
            <p class="text-muted small mb-0">Manage account security and user access levels.</p>
        </div>
    </div>

    <?php if ($msg) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:#f0fdf4;color:#166534;border-radius:12px'><i class='fas fa-check-circle'></i> $msg</div>"; ?>
    <?php if ($err) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:#fef2f2;color:#991b1b;border-radius:12px'><i class='fas fa-exclamation-circle'></i> $err</div>"; ?>

    <div class="row g-4">

        <!-- ====== LEFT SIDEBAR ====== -->
        <div class="col-md-3">

            <!-- Profile Preview Card -->
            <div class="card settings-card shadow-sm mb-3" style="border-radius:16px!important;">
                <div class="card-body text-center p-4">
                    <div class="settings-avatar mx-auto mb-3"><?php echo $initials; ?></div>
                    <div class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($me['full_name']); ?></div>
                    <div class="small text-muted mb-2"><?php echo htmlspecialchars($me['email']); ?></div>
                    <span class="badge rounded-pill px-3 py-1 <?php echo ($me['role'] == 'Super Admin') ? 'bg-danger' : 'bg-primary'; ?>" style="font-size:.75rem;">
                        <?php echo $me['role']; ?>
                    </span>
                </div>
            </div>

            <!-- Theme Toggle Card -->
            <div class="theme-card shadow-sm mb-3 p-3" style="border-radius:16px;">
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

            <!-- Public Notice (Super Admin) -->
            <?php if ($my_role == 'Super Admin'): $set = $conn->query("SELECT * FROM system_settings WHERE id=1")->fetch_assoc(); ?>
                <div class="notice-card shadow-sm mb-3 p-3" style="border-radius:16px;">
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

            <!-- Navigation Tabs -->
            <div class="card settings-card shadow-sm">
                <div class="card-body p-2">
                    <div class="list-group settings-nav list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active" onclick="showTab('tab1', this)">
                            <i class="fas fa-user-shield me-2 text-white"></i> Account Security
                        </a>
                        <?php if ($my_role == 'Super Admin'): ?>
                            <a href="#" class="list-group-item list-group-item-action" onclick="showTab('tab2', this)">
                                <i class="fas fa-user-plus me-2 text-muted"></i> Add New Officer
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" onclick="showTab('tab3', this)">
                                <i class="fas fa-users-cog me-2 text-muted"></i> Manage Users
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div><!-- /col-md-3 -->

        <!-- ====== RIGHT CONTENT ====== -->
        <div class="col-md-9">

            <!-- ========== TAB 1: ACCOUNT SECURITY ========== -->
            <div id="tab1" class="tab-content">

                <!-- MY PROFILE CARD -->
                <div class="card settings-card shadow-sm mb-4">
                    <div class="card-header py-3 px-4 d-flex align-items-center gap-3">
                        <div class="section-icon bg-primary bg-opacity-10">
                            <i class="fas fa-id-card text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">My Account Details</div>
                            <div class="small text-muted">Update your name and email address</div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" name="my_fullname" class="form-control" value="<?php echo htmlspecialchars($me['full_name']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Email Address</label>
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
                            <i class="fas fa-lock text-dark"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">Change Password</div>
                            <div class="small text-muted">Keep your account safe with a strong password</div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Current Password</label>
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

            </div><!-- /tab1 -->

            <?php if ($my_role == 'Super Admin'): ?>

                <!-- ========== TAB 2: ADD USER ========== -->
                <div id="tab2" class="tab-content" style="display:none;">
                    <div class="card settings-card shadow-sm">
                        <div class="card-header py-3 px-4 d-flex align-items-center gap-3">
                            <div class="section-icon bg-success bg-opacity-10">
                                <i class="fas fa-user-plus text-success"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Register New Officer</div>
                                <div class="small text-muted">Create a new system account — auto-activated</div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                                            <input type="text" name="u_n" class="form-control" placeholder="Officer full name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark">Access Level</label>
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
                                    <label class="form-label small fw-semibold text-dark">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" name="u_e" class="form-control" placeholder="example@edl.com" required>
                                    </div>
                                </div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-at text-muted"></i></span>
                                            <input type="text" name="u_u" class="form-control" placeholder="Login username" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark">Initial Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key text-muted"></i></span>
                                            <input type="text" name="u_p" class="form-control" placeholder="Temporary password" required>
                                        </div>
                                    </div>
                                </div>
                                <button name="add_user" class="btn w-100 fw-bold text-white" style="background:linear-gradient(135deg,#059669,#10b981);border:none;border-radius:10px;padding:11px;box-shadow:0 4px 12px rgba(16,185,129,.3);">
                                    <i class="fas fa-user-check me-2"></i>Create Account (Auto-Active)
                                </button>
                            </form>
                        </div>
                    </div>
                </div><!-- /tab2 -->

                <!-- ========== TAB 3: MANAGE USERS ========== -->
                <div id="tab3" class="tab-content" style="display:none;">
                    <div class="card settings-card shadow-sm">
                        <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="section-icon bg-secondary bg-opacity-10">
                                    <i class="fas fa-users-cog text-secondary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">System Officers</div>
                                    <div class="small text-muted">Manage roles, status and email</div>
                                </div>
                            </div>
                            <span class="badge bg-secondary rounded-pill px-3">
                                <?php echo $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c']; ?> Users
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background:#f8fafc;">
                                    <tr class="text-uppercase small text-black" style="font-size:.72rem;letter-spacing:.5px;">
                                        <th class="ps-4 py-3">Officer</th>
                                        <th class="py-3">Role</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3 text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $uu = $conn->query("SELECT * FROM users ORDER BY role ASC, id DESC");
                                    while ($rw = $uu->fetch_assoc()):
                                        $badge     = ($rw['role'] == 'Super Admin') ? 'bg-danger' : 'bg-primary';
                                        $stat_badge = ($rw['is_active'] == 1) ? 'bg-success' : 'bg-warning text-dark';
                                        $stat_txt  = ($rw['is_active'] == 1) ? 'Active' : 'Pending';
                                        $u_init    = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', trim($rw['full_name'])))));
                                        $u_init    = substr($u_init, 0, 2);
                                        $isCurrent = ($rw['id'] == $current_user_id);
                                    ?>
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="user-row-avatar"><?php echo $u_init; ?></div>
                                                <div>
                                                    <div class="fw-semibold text-dark" style="font-size:.9rem;">
                                                        <?php echo htmlspecialchars($rw['full_name']); ?>
                                                        <?php if ($isCurrent): ?><span class="badge bg-light text-primary border ms-1" style="font-size:.65rem;">You</span><?php endif; ?>
                                                    </div>
                                                    <div class="small text-muted">@<?php echo $rw['username']; ?> &bull; <?php echo $rw['email']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge <?php echo $badge; ?> rounded-pill px-2"><?php echo $rw['role']; ?></span></td>
                                        <td><span class="badge <?php echo $stat_badge; ?> rounded-pill px-2"><?php echo $stat_txt; ?></span></td>
                                        <td class="text-end pe-4">
                                            <?php if ($isCurrent): ?>
                                                <span class="text-muted small">—</span>
                                            <?php else: ?>
                                                <button onclick='editUser(<?php echo json_encode($rw); ?>)'
                                                    class="btn btn-sm btn-outline-primary me-1" style="border-radius:8px;padding:4px 10px;" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="settings?del=<?php echo $rw['id']; ?>"
                                                    onclick="return confirm('Delete <?php echo htmlspecialchars($rw['full_name']); ?> permanently?');"
                                                    class="btn btn-sm btn-outline-danger" style="border-radius:8px;padding:4px 10px;" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /tab3 -->

            <?php endif; ?>

        </div><!-- /col-md-9 -->
    </div><!-- /row -->
</div><!-- closes container from header -->

<!-- ====== EDIT USER MODAL ====== -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header-gradient text-white">
                    <div class="edit-user-avatar mb-2" id="eu_avatar">AB</div>
                    <h6 class="text-center fw-bold mb-0 text-white" id="eu_name"></h6>
                    <div class="text-center small text-white opacity-75 mt-1" id="eu_uname"></div>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="eu_id">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="e_email" id="eu_email_input" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">System Role</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-shield-alt text-muted"></i></span>
                            <select name="e_role" id="eu_role" class="form-select">
                                <option value="Officer">Standard Officer</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label small fw-semibold text-dark">Account Status</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-toggle-on text-muted"></i></span>
                            <select name="e_active" id="eu_stat" class="form-select">
                                <option value="1">✅ Active (Can Login)</option>
                                <option value="0">⏸ Pending / Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 pb-4 border-0">
                    <button type="submit" name="edit_user_role" class="btn btn-save-profile w-100">
                        <i class="fas fa-save me-2"></i>Update Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.getElementById(tabId).style.display = 'block';
        document.querySelectorAll('.settings-nav .list-group-item').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
    }

    function editUser(data) {
        const name = data.full_name || '';
        const initials = name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
        document.getElementById('eu_id').value         = data.id;
        document.getElementById('eu_name').innerText   = name;
        document.getElementById('eu_uname').innerText  = '@' + (data.username || '');
        document.getElementById('eu_avatar').innerText = initials;
        document.getElementById('eu_email_input').value = data.email || '';
        document.getElementById('eu_role').value        = data.role;
        document.getElementById('eu_stat').value        = data.is_active;
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }
</script>

<?php include '../layout/footer.php'; ?>