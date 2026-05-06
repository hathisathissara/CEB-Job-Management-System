<?php
require_once '../middleware/superAdminGuard.php';
require_once '../controllers/ManageUsersController.php';
$me       = $conn->query("SELECT full_name,email,role,username FROM users WHERE id='$current_user_id'")->fetch_assoc();
$initials = substr(strtoupper(implode('',array_map(fn($w)=>$w[0],explode(' ',trim($me['full_name']))))),0,2);
$total    = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$active   = $conn->query("SELECT COUNT(*) c FROM users WHERE is_active=1")->fetch_assoc()['c'];
$admins   = $conn->query("SELECT COUNT(*) c FROM users WHERE role='Super Admin'")->fetch_assoc()['c'];
include '../layout/header.php';
?>
<style>
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.au{animation:fadeUp .4s ease both}.au1{animation-delay:.06s}.au2{animation-delay:.12s}.au3{animation-delay:.18s}
.page-sub-nav{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px}
.page-sub-nav a{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;border-radius:50px;font-size:.84rem;font-weight:600;text-decoration:none;transition:all .25s;border:1.5px solid var(--edl-border);color:var(--text-muted)}
.page-sub-nav a:hover{background:rgba(99,102,241,.1);color:#818cf8;border-color:rgba(99,102,241,.5)}
.page-sub-nav a.active{background:linear-gradient(135deg,#6366f1,#9333ea);color:#fff!important;border-color:transparent;box-shadow:0 4px 18px rgba(99,102,241,.38)}
.s-card{border-radius:20px!important;border:1.5px solid var(--edl-border)!important;box-shadow:0 8px 32px rgba(0,0,0,.07)!important;background:var(--bg-card)!important;backdrop-filter:var(--card-filter);overflow:hidden;transition:box-shadow .3s}
.s-card:hover{box-shadow:0 14px 40px rgba(0,0,0,.12)!important}
.s-card .card-header{background:transparent!important;border-bottom:1.5px solid var(--edl-border)!important;padding:18px 24px}
/* stat mini cards */
.stat-mini{border-radius:16px;padding:16px 20px;border:1.5px solid var(--edl-border);background:var(--bg-card);backdrop-filter:var(--card-filter);display:flex;align-items:center;gap:14px;transition:transform .25s,box-shadow .25s}
.stat-mini:hover{transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,.1)}
.stat-icon{width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;flex-shrink:0}
.stat-val{font-size:1.5rem;font-weight:800;line-height:1;color:var(--text-main)}
.stat-lbl{font-size:.74rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px}
/* profile sidebar */
.profile-banner{height:68px;background:linear-gradient(135deg,#6366f1,#9333ea 60%,#a855f7)}
.p-av{width:68px;height:68px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;border:3px solid var(--bg-card);box-shadow:0 8px 24px rgba(99,102,241,.45);margin:-34px auto 10px}
/* search */
.search-wrap{position:relative}
.search-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:.82rem;z-index:2;pointer-events:none}
.search-box{border-radius:50px!important;padding-left:40px!important;font-size:.85rem;border:1.5px solid var(--edl-border)!important;background:var(--input-bg)!important;color:var(--text-main)!important;transition:all .25s}
.search-box:focus{border-color:#6366f1!important;box-shadow:0 0 0 3px rgba(99,102,241,.18)!important}
/* filter chips */
.filter-chip{padding:5px 14px;border-radius:50px;font-size:.78rem;font-weight:700;border:1.5px solid var(--edl-border);background:transparent;color:var(--text-muted);cursor:pointer;transition:all .2s}
.filter-chip:hover,.filter-chip.active{background:linear-gradient(135deg,#6366f1,#9333ea);color:#fff;border-color:transparent;box-shadow:0 3px 12px rgba(99,102,241,.35)}
/* table */
.u-table{width:100%}
.u-table thead th{padding:12px 16px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);background:var(--table-head);border-bottom:1.5px solid var(--edl-border)}
.u-table tbody tr{border-bottom:1px solid var(--edl-border);transition:background .2s}
.u-table tbody tr:hover td{background:var(--table-hover)}
.u-table tbody td{padding:13px 16px;vertical-align:middle}
.u-table tbody tr:last-child{border-bottom:none}
/* user avatar in table */
.u-av{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;color:#fff;flex-shrink:0}
/* badges */
.b-admin{background:linear-gradient(135deg,#dc2626,#ef4444)!important}
.b-officer{background:linear-gradient(135deg,#2563eb,#3b82f6)!important}
.b-active{background:linear-gradient(135deg,#059669,#10b981)!important}
.b-pending{background:linear-gradient(135deg,#d97706,#f59e0b)!important;color:#111!important}
.pill-badge{border-radius:50px;padding:4px 12px;font-size:.7rem;font-weight:700;color:#fff;letter-spacing:.2px}
/* action btns */
.act-btn{width:32px;height:32px;border-radius:9px;border:1.5px solid var(--edl-border);background:transparent;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;transition:all .2s;cursor:pointer}
.act-btn:hover{transform:scale(1.12)}
.act-edit:hover{border-color:#6366f1;color:#818cf8;background:rgba(99,102,241,.1)}
.act-del:hover{border-color:#ef4444;color:#ef4444;background:rgba(239,68,68,.1)}
/* modal */
.mu-modal-header{background:linear-gradient(135deg,#6366f1,#9333ea);padding:22px 24px}
.mu-av-lg{width:62px;height:62px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#9333ea);display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:800;color:#fff;margin:0 auto 10px;box-shadow:0 6px 18px rgba(99,102,241,.4)}
.modal-content{border-radius:22px!important;overflow:hidden;border:none!important}
.mu-inp-group .input-group-text{border-radius:12px 0 0 12px!important;border-right:none!important;background:var(--input-bg)!important;border-color:var(--edl-border)!important;color:var(--text-muted)}
.mu-inp-group .form-control,.mu-inp-group .form-select{border-radius:0 12px 12px 0!important;border-left:none!important;background:var(--input-bg)!important;border-color:var(--edl-border)!important;color:var(--text-main)!important;padding:10px 14px;font-size:.88rem}
.mu-inp-group .form-control:focus,.mu-inp-group .form-select:focus{border-color:#6366f1!important;box-shadow:0 0 0 3px rgba(99,102,241,.18)!important}
.btn-mu-save{background:linear-gradient(135deg,#6366f1,#9333ea);color:#fff;border-radius:12px;padding:12px;font-weight:700;border:none;width:100%;transition:all .3s;box-shadow:0 5px 18px rgba(99,102,241,.3)}
.btn-mu-save:hover{transform:translateY(-2px);color:#fff;box-shadow:0 8px 24px rgba(99,102,241,.45)}
.theme-pill-card{border-radius:18px!important;background:linear-gradient(135deg,#0f172a,#1e293b)!important;border:1.5px solid rgba(255,255,255,.07)!important}
.theme-toggle-btn{background:rgba(255,255,255,.1)!important;color:#fff!important;border:1.5px solid rgba(255,255,255,.18)!important;border-radius:10px;font-size:.82rem;font-weight:600;transition:all .25s}
.theme-toggle-btn:hover{background:rgba(255,255,255,.2)!important}
.s-alert-success{background:rgba(16,185,129,.13);color:#10b981;border-radius:14px;border:none;padding:14px 18px;font-weight:600;font-size:.88rem}
.s-alert-error{background:rgba(239,68,68,.13);color:#ef4444;border-radius:14px;border:none;padding:14px 18px;font-weight:600;font-size:.88rem}
.f-label{font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:7px;display:block}
</style>

    <!-- PAGE HEADER -->
    <div class="d-flex align-items-center gap-3 mb-3 au">
        <div style="width:40px;height:40px;border-radius:12px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center">
            <i class="fas fa-users-cog" style="color:#818cf8"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--text-main)">Manage Users</h4>
            <p class="text-muted small mb-0">Control roles, status and system access</p>
        </div>
    </div>

    <!-- SUB NAV -->
    <div class="page-sub-nav au">
        <a href="settings"><i class="fas fa-user-shield"></i> Account Security</a>
        <a href="add_user"><i class="fas fa-user-plus"></i> Add New Officer</a>
        <a href="manage_users" class="active"><i class="fas fa-users-cog"></i> Manage Users</a>
    </div>

    <?php if ($msg): ?>
    <div class="s-alert-success d-flex align-items-center gap-2 mb-4 au"><i class="fas fa-check-circle fa-lg"></i> <?php echo $msg; ?></div>
    <?php endif; ?>
    <?php if ($err): ?>
    <div class="s-alert-error d-flex align-items-center gap-2 mb-4 au"><i class="fas fa-exclamation-circle fa-lg"></i> <?php echo $err; ?></div>
    <?php endif; ?>

    <!-- STAT MINI CARDS -->
    <div class="row g-3 mb-4 au au1">
        <div class="col-4">
            <div class="stat-mini">
                <div class="stat-icon" style="background:rgba(99,102,241,.12)"><i class="fas fa-users" style="color:#818cf8"></i></div>
                <div><div class="stat-val"><?php echo $total; ?></div><div class="stat-lbl">Total Users</div></div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-mini">
                <div class="stat-icon" style="background:rgba(16,185,129,.12)"><i class="fas fa-check-circle" style="color:#10b981"></i></div>
                <div><div class="stat-val"><?php echo $active; ?></div><div class="stat-lbl">Active</div></div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-mini">
                <div class="stat-icon" style="background:rgba(239,68,68,.12)"><i class="fas fa-user-shield" style="color:#ef4444"></i></div>
                <div><div class="stat-val"><?php echo $admins; ?></div><div class="stat-lbl">Admins</div></div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- LEFT SIDEBAR -->
        <div class="col-md-3 au au2">
            <div class="s-card mb-3">
                <div class="profile-banner"></div>
                <div class="card-body text-center px-4 pb-4 pt-0">
                    <div class="p-av"><?php echo $initials; ?></div>
                    <div class="fw-bold mb-1" style="color:var(--text-main);font-size:.92rem"><?php echo htmlspecialchars($me['full_name']); ?></div>
                    <div class="small mb-2" style="color:var(--text-muted)"><?php echo htmlspecialchars($me['email']); ?></div>
                    <span class="pill-badge <?php echo ($me['role']=='Super Admin')?'b-admin':'b-officer'; ?>" style="font-size:.7rem">
                        <?php echo ($me['role']=='Super Admin')?'⚡ Super Admin':'👤 Officer'; ?>
                    </span>
                </div>
            </div>

            <!-- Theme -->
            <div class="theme-pill-card shadow-sm mb-3 p-3">
                <?php $theme=isset($_SESSION['theme'])?$_SESSION['theme']:'light'; ?>
                <div class="small fw-bold text-white mb-2" style="opacity:.45;font-size:.68rem;text-transform:uppercase;letter-spacing:.8px">Theme</div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas <?php echo($theme=='dark')?'fa-moon':'fa-sun'; ?> fa-lg text-warning"></i>
                    <span class="fw-semibold text-white"><?php echo($theme=='dark')?'Dark':'Light'; ?> Mode</span>
                </div>
                <form action="toggle_theme.php" method="POST">
                    <button class="btn btn-sm w-100 theme-toggle-btn">
                        <i class="fas <?php echo($theme=='dark')?'fa-sun':'fa-moon'; ?> me-2"></i>Switch to <?php echo($theme=='dark')?'Light':'Dark'; ?>
                    </button>
                </form>
            </div>

            <a href="add_user" class="btn w-100 fw-bold text-white" style="background:linear-gradient(135deg,#059669,#10b981);border:none;border-radius:14px;padding:12px;box-shadow:0 4px 14px rgba(16,185,129,.3)">
                <i class="fas fa-user-plus me-2"></i>Add New Officer
            </a>
        </div>

        <!-- RIGHT: Users Table -->
        <div class="col-md-9 au au3">
            <div class="s-card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:40px;height:40px;border-radius:12px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-users-cog" style="color:#818cf8"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:var(--text-main)">System Officers</div>
                            <div class="small" style="color:var(--text-muted)">Manage roles, status and access</div>
                        </div>
                    </div>
                    <div class="search-wrap" style="min-width:220px">
                        <i class="fas fa-search text-muted"></i>
                        <input type="text" id="userSearch" class="form-control search-box" placeholder="Search officers…" oninput="filterTable(this.value)">
                    </div>
                </div>

                <!-- Filter chips -->
                <div class="px-4 pt-3 pb-0 d-flex gap-2 flex-wrap">
                    <button class="filter-chip active" onclick="setFilter('all',this)">All</button>
                    <button class="filter-chip" onclick="setFilter('active',this)">Active</button>
                    <button class="filter-chip" onclick="setFilter('pending',this)">Pending</button>
                    <button class="filter-chip" onclick="setFilter('Super Admin',this)">Super Admin</button>
                </div>

                <div class="table-responsive mt-2">
                    <table class="u-table" id="usersTable">
                        <thead>
                            <tr>
                                <th class="ps-4">Officer</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $avatarColors = ['#6366f1','#8b5cf6','#ec4899','#0ea5e9','#f59e0b','#10b981'];
                        $ci = 0;
                        $uu = $conn->query("SELECT * FROM users ORDER BY role ASC, id DESC");
                        while ($rw = $uu->fetch_assoc()):
                            $isCurrent = ($rw['id'] == $current_user_id);
                            $u_init = substr(strtoupper(implode('',array_map(fn($w)=>$w[0],explode(' ',trim($rw['full_name']))))),0,2);
                            $avColor = $avatarColors[$ci % count($avatarColors)]; $ci++;
                        ?>
                        <tr data-search="<?php echo strtolower($rw['full_name'].' '.$rw['username'].' '.$rw['email'].' '.$rw['role'].' '.($rw['is_active']?'active':'pending')); ?>"
                            data-role="<?php echo htmlspecialchars($rw['role']); ?>"
                            data-status="<?php echo $rw['is_active']?'active':'pending'; ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="u-av" style="background:<?php echo $avColor; ?>"><?php echo $u_init; ?></div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:.88rem;color:var(--text-main)">
                                            <?php echo htmlspecialchars($rw['full_name']); ?>
                                            <?php if ($isCurrent): ?><span class="pill-badge ms-1" style="background:rgba(99,102,241,.15);color:#818cf8;font-size:.62rem;padding:2px 8px">You</span><?php endif; ?>
                                        </div>
                                        <div class="small" style="color:var(--text-muted)">@<?php echo $rw['username']; ?> &bull; <?php echo $rw['email']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="pill-badge <?php echo ($rw['role']=='Super Admin')?'b-admin':'b-officer'; ?>">
                                    <?php echo ($rw['role']=='Super Admin')?'⚡ Super Admin':'👤 Officer'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="pill-badge <?php echo $rw['is_active']?'b-active':'b-pending'; ?>">
                                    <?php echo $rw['is_active']?'● Active':'◌ Pending'; ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <?php if ($isCurrent): ?>
                                    <span class="text-muted small">—</span>
                                <?php else: ?>
                                    <button onclick='editUser(<?php echo json_encode($rw); ?>)' class="act-btn act-edit me-1 text-muted" title="Edit User"><i class="fas fa-pen"></i></button>
                                    <a href="manage_users?del=<?php echo $rw['id']; ?>"
                                        onclick="return confirm('Delete <?php echo htmlspecialchars($rw['full_name']); ?> permanently?')"
                                        class="act-btn act-del text-muted" title="Delete User"><i class="fas fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="mu-modal-header text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    <div class="mu-av-lg" id="eu_avatar">AB</div>
                    <h6 class="fw-bold mb-0 text-white" id="eu_name"></h6>
                    <div class="small text-white mt-1" style="opacity:.7" id="eu_uname"></div>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="eu_id">
                    <div class="mb-3">
                        <label class="f-label">Email Address</label>
                        <div class="input-group mu-inp-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="e_email" id="eu_email_input" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="f-label">System Role</label>
                        <div class="input-group mu-inp-group">
                            <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                            <select name="e_role" id="eu_role" class="form-select">
                                <option value="Officer">👤 Standard Officer</option>
                                <option value="Super Admin">⚡ Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="f-label">Account Status</label>
                        <div class="input-group mu-inp-group">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                            <select name="e_active" id="eu_stat" class="form-select">
                                <option value="1">✅ Active (Can Login)</option>
                                <option value="0">⏸ Pending / Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 pb-4 border-0 pt-0">
                    <button type="submit" name="edit_user_role" class="btn btn-mu-save">
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
    const init = name.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2);
    document.getElementById('eu_id').value          = data.id;
    document.getElementById('eu_name').innerText    = name;
    document.getElementById('eu_uname').innerText   = '@'+(data.username||'');
    document.getElementById('eu_avatar').innerText  = init;
    document.getElementById('eu_email_input').value = data.email||'';
    document.getElementById('eu_role').value        = data.role;
    document.getElementById('eu_stat').value        = data.is_active;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

let currentFilter = 'all';
function filterTable(q) {
    const rows = document.querySelectorAll('#usersTable tbody tr');
    const ql = q.toLowerCase();
    rows.forEach(r => {
        const matchSearch = r.dataset.search.includes(ql);
        const matchFilter = currentFilter==='all' ||
            (currentFilter==='active'   && r.dataset.status==='active') ||
            (currentFilter==='pending'  && r.dataset.status==='pending') ||
            (r.dataset.role === currentFilter);
        r.style.display = (matchSearch && matchFilter) ? '' : 'none';
    });
}

function setFilter(f, btn) {
    currentFilter = f;
    document.querySelectorAll('.filter-chip').forEach(c=>c.classList.remove('active'));
    btn.classList.add('active');
    filterTable(document.getElementById('userSearch').value);
}
</script>

<?php include '../layout/footer.php'; ?>
