<?php
// ============================================
// 1. AUTH MIDDLEWARE
// ============================================
require_once '../middleware/superAdminGuard.php';

// ============================================
// 2. CONTROLLER LOGIC (Manage Users)
// ============================================
require_once '../controllers/ManageUsersController.php';

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
.settings-card .card-header {
    background: transparent !important; border-bottom: 1px solid var(--edl-border) !important;
}
.section-icon {
    width: 38px; height: 38px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
}
.user-row-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #9333ea);
    color: #fff; font-size: .75rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.table tr { transition: .2s; }
.table tr:hover > td { background: var(--table-hover) !important; }
.modal-content { border-radius: 20px !important; overflow: hidden; }
.modal-header-gradient { background: linear-gradient(135deg, #6366f1, #9333ea); padding: 20px; }
.edit-user-avatar {
    width: 65px; height: 65px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #9333ea);
    font-size: 1.4rem; font-weight: bold;
    box-shadow: 0 6px 18px rgba(99,102,241,.4);
    display: flex; align-items: center; justify-content: center;
    color: #fff; margin: 0 auto 10px;
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
    color: #fff !important; border-color: transparent;
    box-shadow: 0 4px 15px rgba(99,102,241,0.4);
}
.theme-card {
    border-radius: 18px !important;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    box-shadow: 0 8px 25px rgba(0,0,0,.2);
}
.btn-save-profile {
    background: linear-gradient(135deg, #6366f1, #9333ea); color: #fff;
    border-radius: 12px; padding: 12px; font-weight: 600; border: none;
    transition: .3s; box-shadow: 0 6px 18px rgba(99,102,241,.3); width: 100%;
}
.btn-save-profile:hover { transform: translateY(-2px); color: #fff; }
.search-box { border-radius: 50px !important; padding-left: 40px !important; }
.search-wrap { position: relative; }
.search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); }
</style>

    <!-- PAGE HEADER -->
    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="section-icon bg-secondary bg-opacity-10">
            <i class="fas fa-users-cog text-secondary"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--text-main)">Manage Users</h4>
            <p class="text-muted small mb-0">Control roles, status and system access</p>
        </div>
    </div>

    <!-- SUB NAV PILLS -->
    <div class="page-sub-nav">
        <a href="settings">
            <i class="fas fa-user-shield"></i> Account Security
        </a>
        <a href="add_user">
            <i class="fas fa-user-plus"></i> Add New Officer
        </a>
        <a href="manage_users" class="active">
            <i class="fas fa-users-cog"></i> Manage Users
        </a>
    </div>

    <?php if ($msg) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:rgba(16,185,129,.15);color:#10b981;border-radius:12px'><i class='fas fa-check-circle'></i> $msg</div>"; ?>
    <?php if ($err) echo "<div class='alert border-0 shadow-sm mb-4 d-flex align-items-center gap-2' style='background:rgba(239,68,68,.15);color:#ef4444;border-radius:12px'><i class='fas fa-exclamation-circle'></i> $err</div>"; ?>

    <div class="row g-4">

        <!-- LEFT: Profile card -->
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

            <!-- Quick stats -->
            <?php
            $total  = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
            $active = $conn->query("SELECT COUNT(*) c FROM users WHERE is_active=1")->fetch_assoc()['c'];
            $pend   = $total - $active;
            ?>
            <div class="card settings-card p-3 mb-2">
                <div class="small fw-bold text-muted text-uppercase mb-3" style="font-size:.7rem;letter-spacing:.5px;">Quick Stats</div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-muted"><i class="fas fa-users me-1"></i> Total Users</span>
                    <span class="badge bg-primary rounded-pill"><?php echo $total; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-muted"><i class="fas fa-check-circle me-1"></i> Active</span>
                    <span class="badge bg-success rounded-pill"><?php echo $active; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted"><i class="fas fa-clock me-1"></i> Pending</span>
                    <span class="badge bg-warning text-dark rounded-pill"><?php echo $pend; ?></span>
                </div>
            </div>

            <a href="add_user" class="btn w-100 fw-bold text-white mt-1" style="background:linear-gradient(135deg,#059669,#10b981);border:none;border-radius:12px;padding:11px;box-shadow:0 4px 12px rgba(16,185,129,.3);">
                <i class="fas fa-user-plus me-2"></i>Add New Officer
            </a>
        </div>

        <!-- RIGHT: Users Table -->
        <div class="col-md-9">
            <div class="card settings-card shadow-sm">
                <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="section-icon bg-secondary bg-opacity-10">
                            <i class="fas fa-users-cog text-secondary"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:var(--text-main)">System Officers</div>
                            <div class="small text-muted">Manage roles, status and email</div>
                        </div>
                    </div>
                    <!-- Search -->
                    <div class="search-wrap" style="min-width:220px;">
                        <i class="fas fa-search text-muted" style="font-size:.8rem;"></i>
                        <input type="text" id="userSearch" class="form-control search-box" placeholder="Search officers..." oninput="filterTable(this.value)" style="font-size:.85rem;">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="usersTable">
                        <thead style="background:var(--table-head);">
                            <tr class="text-uppercase small text-muted" style="font-size:.72rem;letter-spacing:.5px;">
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
                                $badge      = ($rw['role'] == 'Super Admin') ? 'bg-danger' : 'bg-primary';
                                $stat_badge = ($rw['is_active'] == 1) ? 'bg-success' : 'bg-warning text-dark';
                                $stat_txt   = ($rw['is_active'] == 1) ? 'Active' : 'Pending';
                                $u_init     = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', trim($rw['full_name'])))));
                                $u_init     = substr($u_init, 0, 2);
                                $isCurrent  = ($rw['id'] == $current_user_id);
                            ?>
                            <tr style="border-bottom:1px solid var(--edl-border);" data-search="<?php echo strtolower($rw['full_name'].' '.$rw['username'].' '.$rw['email'].' '.$rw['role']); ?>">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-row-avatar"><?php echo $u_init; ?></div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.9rem;color:var(--text-main)">
                                                <?php echo htmlspecialchars($rw['full_name']); ?>
                                                <?php if ($isCurrent): ?><span class="badge bg-primary bg-opacity-10 text-primary border ms-1" style="font-size:.65rem;">You</span><?php endif; ?>
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
                                        <a href="manage_users?del=<?php echo $rw['id']; ?>"
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
        </div><!-- /col-md-9 -->

    </div><!-- /row -->
</div><!-- /container -->

<!-- EDIT USER MODAL -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header-gradient text-center">
                    <div class="edit-user-avatar" id="eu_avatar">AB</div>
                    <h6 class="fw-bold mb-0 text-white" id="eu_name"></h6>
                    <div class="small text-white opacity-75 mt-1" id="eu_uname"></div>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="eu_id">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:var(--text-main)">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="e_email" id="eu_email_input" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:var(--text-main)">System Role</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-shield-alt text-muted"></i></span>
                            <select name="e_role" id="eu_role" class="form-select">
                                <option value="Officer">Standard Officer</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label small fw-semibold" style="color:var(--text-main)">Account Status</label>
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
                    <button type="submit" name="edit_user_role" class="btn btn-save-profile">
                        <i class="fas fa-save me-2"></i>Update Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(data) {
    const name = data.full_name || '';
    const initials = name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
    document.getElementById('eu_id').value          = data.id;
    document.getElementById('eu_name').innerText    = name;
    document.getElementById('eu_uname').innerText   = '@' + (data.username || '');
    document.getElementById('eu_avatar').innerText  = initials;
    document.getElementById('eu_email_input').value = data.email || '';
    document.getElementById('eu_role').value        = data.role;
    document.getElementById('eu_stat').value        = data.is_active;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

function filterTable(query) {
    const rows = document.querySelectorAll('#usersTable tbody tr');
    const q = query.toLowerCase();
    rows.forEach(row => {
        row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
}
</script>

<?php include '../layout/footer.php'; ?>
