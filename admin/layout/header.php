<?php
// Ensure DB Connection Exists
include '../../config/db_conn.php';

// --- THEME SYNC ---
if (isset($_SESSION['user_id'])) {
    $uid_theme = $_SESSION['user_id'];
    $res_theme = $conn->query("SELECT theme FROM users WHERE id='$uid_theme'");
    if ($res_theme && $res_theme->num_rows > 0) {
        $row_theme = $res_theme->fetch_assoc();
        $_SESSION['theme'] = $row_theme['theme'];
        $theme = $row_theme['theme'];
    } else {
        $theme = 'dark';
    }
} else {
    $theme = 'dark';
}

if ($theme == 'dark') {
    // Dark mode glassmorphism values
    $bg_body_fallback = '#0d0f12';
    $bg_card = 'rgba(255,255,255,0.04)';
    $text_main = '#e8eaf0';
    $text_muted = 'rgba(255,255,255,0.45)';
    $sidebar_bg = 'rgba(11,13,16,0.95)';
    $border_col = 'rgba(255,255,255,0.07)';
    $input_bg = 'rgba(255,255,255,0.06)';
    $table_strip = 'rgba(255,255,255,0.03)';
    $table_hover = 'rgba(255,255,255,0.06)';
    $table_head = 'rgba(255,255,255,0.05)';
    $bg_gr_1 = '#0d0f12';
    $bg_gr_2 = '#13161c';
    $bg_gr_3 = '#0f1218';
    $grid_color = 'rgba(255,255,255,0.018)';
    $input_focus = 'rgba(255,255,255,0.09)';
    $card_filter = 'blur(12px)';
} else {
    // Light mode glassmorphism values
    $bg_body_fallback = '#f4f6f9';
    $bg_card = 'rgba(255,255,255,0.9)';
    $text_main = '#212529';
    $text_muted = 'rgba(0,0,0,0.55)';
    $sidebar_bg = 'rgba(255,255,255,0.95)';
    $border_col = 'rgba(0,0,0,0.08)';
    $input_bg = '#ffffff';
    $table_strip = 'rgba(0,0,0,0.02)';
    $table_hover = 'rgba(0,0,0,0.04)';
    $table_head = 'rgba(0,0,0,0.05)';
    $bg_gr_1 = '#e6eaf0';
    $bg_gr_2 = '#f4f6f9';
    $bg_gr_3 = '#e6eaf0';
    $grid_color = 'rgba(0,0,0,0.03)';
    $input_focus = '#ffffff';
    $card_filter = 'blur(20px)';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDL Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" href="https://img.icons8.com/color/48/d11212/flash-on.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="manifest" href="/ceb/manifest.json">
    <link rel="stylesheet" href="../assets/css/loader.css">
    <!-- SERVICE WORKER REGISTRATION -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/ceb/sw.js')
                .then(() => console.log('Service Worker Registered'));
        }
    </script>
    <style>
        /* ═══════════════════════════════════════
           EDL ADMIN DARK GLASSMORPHISM THEME
        ═══════════════════════════════════════ */
        :root {
            --edl-red:      #c0392b;
            --edl-red-l:    #e74c3c;
            --edl-gold:     #f39c12;
            --sidebar-w:    248px;

            --bg-body:    <?php echo $bg_body_fallback; ?>;
            --bg-card:    <?php echo $bg_card; ?>;
            --text-main:  <?php echo $text_main; ?>;
            --text-muted: <?php echo $text_muted; ?>;
            --sidebar-bg: <?php echo $sidebar_bg; ?>;
            --edl-border: <?php echo $border_col; ?>;
            --input-bg:   <?php echo $input_bg; ?>;
            --table-strip: <?php echo $table_strip; ?>;
            --table-hover: <?php echo $table_hover; ?>;
            --table-head: <?php echo $table_head; ?>;
            --input-focus: <?php echo $input_focus; ?>;
            --card-filter: <?php echo $card_filter; ?>;

            --bg-gr-1: <?php echo $bg_gr_1; ?>;
            --bg-gr-2: <?php echo $bg_gr_2; ?>;
            --bg-gr-3: <?php echo $bg_gr_3; ?>;
            --grid-color: <?php echo $grid_color; ?>;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            transition: 0.2s;
            position: relative;
            min-height: 100vh;
        }

        /* ── Ambient Background ── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: -2;
            background: linear-gradient(135deg, var(--bg-gr-1) 0%, var(--bg-gr-2) 60%, var(--bg-gr-3) 100%);
        }
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: -1;
            background-image:
                linear-gradient(var(--grid-color) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
            background-size: 56px 56px;
            pointer-events: none;
        }

        /* ── Dark mode card overrides ── */
        .card, .modal-content, .list-group-item {
            background: var(--bg-card) !important;
            border-color: var(--edl-border) !important;
            color: var(--text-main) !important;
            backdrop-filter: var(--card-filter);
        }

        .card {
            border-radius: 16px !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1) !important;
        }

        .card-header {
            background: var(--bg-card) !important;
            border-color: var(--edl-border) !important;
        }

        .bg-light { background: var(--bg-card) !important; }
        .bg-white  { background: var(--bg-card) !important; }

        /* Text */
        h1,h2,h3,h4,h5,h6,.card-title,.modal-title,label,td,th {
            color: var(--text-main) !important;
        }
        .text-muted { color: var(--text-muted) !important; }
        .text-dark  { color: <?php echo $theme == 'dark' ? '#e0e0e0' : '#212529'; ?> !important; }
        .text-danger  { color: #ff6b6b !important; }
        .text-success { color: #51cf66 !important; }
        .text-primary { color: #4dabf7 !important; }
        .text-warning { color: #fcc419 !important; }

        /* Borders */
        .border { border-color: var(--edl-border) !important; }
        .border-primary  { border-color: #0d6efd !important; }
        .border-danger   { border-color: #dc3545 !important; }
        .border-success  { border-color: #198754 !important; }
        .border-warning  { border-color: #ffc107 !important; }

        /* Tables */
        .table {
            color: var(--text-main) !important;
            --bs-table-border-color: var(--edl-border);
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            color: var(--text-main);
            background-color: var(--table-strip);
        }
        .table-hover tbody tr:hover > * {
            color: var(--text-main);
            background-color: var(--table-hover);
        }
        .table-light, .table-light th {
            background-color: var(--table-head) !important;
            color: var(--text-main) !important;
            border-color: var(--edl-border);
        }

        /* Forms */
        .form-control, .form-select {
            background-color: var(--input-bg) !important;
            border-color: var(--edl-border) !important;
            color: var(--text-main) !important;
            border-radius: 10px !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--input-focus) !important;
            color: var(--text-main) !important;
            border-color: var(--edl-red) !important;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.15) !important;
        }
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23aaaaaa' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        }
        ::placeholder { color: var(--edl-muted) !important; opacity: 0.7; }

        /* Buttons */
        .btn-danger, .btn-primary {
            border: none;
            border-radius: 10px !important;
        }
        .btn-outline-danger {
            border-color: var(--edl-red) !important;
            color: var(--edl-red-l) !important;
        }
        .btn-outline-danger:hover {
            background: rgba(192,57,43,0.15) !important;
        }

        /* Badges */
        .badge { border-radius: 8px !important; }

        /* Modals */
        .modal-header { border-color: var(--edl-border) !important; }
        .modal-footer { border-color: var(--edl-border) !important; }
        .modal-body   { background: rgba(255,255,255,0.02) !important; }

        /* Alerts */
        .alert {
            border-radius: 12px !important;
            backdrop-filter: blur(10px);
        }

        /* ═══════════════════════════════════════
           SIDEBAR — Modern Accordion
        ═══════════════════════════════════════ */
        .sidebar {
            height: 100vh;
            position: fixed;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            top: 0; left: 0;
            z-index: 1000;
            border-right: 1px solid var(--edl-border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── Brand ── */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 18px;
            height: 66px;
            border-bottom: 1px solid var(--edl-border);
            text-decoration: none;
            flex-shrink: 0;
        }
        .sidebar-brand .bolt-icon {
            width: 38px; height: 38px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--edl-red) 0%, var(--edl-red-l) 100%);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; color: #fff;
            box-shadow: 0 4px 12px rgba(192,57,43,0.4);
        }
        .sidebar-brand .brand-name { font-size: 0.88rem; font-weight: 800; color: var(--text-main); letter-spacing: 0.5px; line-height: 1.2; }
        .sidebar-brand .brand-sub  { font-size: 0.64rem; color: var(--text-muted); font-weight: 500; }

        /* ── Scrollable Nav ── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 10px;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--edl-border); border-radius: 4px; }

        /* Section labels */
        .nav-label-group {
            font-size: 0.58rem; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--text-muted);
            padding: 14px 10px 5px;
            opacity: 0.6;
        }

        /* Direct nav link */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.845rem; font-weight: 500;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--table-strip); color: var(--text-main); }
        .nav-item.active {
            background: rgba(192,57,43,0.13);
            color: var(--edl-red-l);
            font-weight: 600;
        }
        .nav-item .ni { width: 20px; text-align: center; font-size: 0.88rem; flex-shrink: 0; }

        /* ── Accordion Group ── */
        .nav-group { margin-bottom: 2px; }

        .nav-group-toggle {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: var(--text-muted);
            font-size: 0.845rem; font-weight: 500;
            cursor: pointer;
            user-select: none;
            transition: background 0.15s, color 0.15s;
            list-style: none;
        }
        .nav-group-toggle:hover { background: var(--table-strip); color: var(--text-main); }
        .nav-group-toggle.open { color: var(--text-main); }
        .nav-group-toggle .ni { width: 20px; text-align: center; font-size: 0.88rem; flex-shrink: 0; }
        .nav-group-toggle .ng-label { flex: 1; }
        .nav-group-toggle .chevron {
            font-size: 0.65rem;
            transition: transform 0.22s ease;
            opacity: 0.5;
        }
        .nav-group-toggle.open .chevron { transform: rotate(90deg); opacity: 1; }

        /* Has-active state: tint the toggle */
        .nav-group-toggle.has-active {
            color: var(--edl-red-l);
        }
        .nav-group-toggle.has-active .chevron { opacity: 1; }

        /* Sub-items */
        .nav-sub {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.28s cubic-bezier(0.4,0,0.2,1);
        }
        .nav-sub.open { max-height: 300px; }

        .nav-sub .nav-item {
            padding-left: 42px;
            font-size: 0.82rem;
            position: relative;
        }
        .nav-sub .nav-item::before {
            content: '';
            position: absolute;
            left: 26px; top: 50%;
            transform: translateY(-50%);
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--edl-border);
            transition: background 0.15s;
        }
        .nav-sub .nav-item.active::before { background: var(--edl-red-l); }

        /* ── Footer ── */
        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid var(--edl-border);
            flex-shrink: 0;
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            background: var(--table-strip);
            border: 1px solid var(--edl-border);
            margin-bottom: 8px;
        }
        .sidebar-user .user-av {
            width: 32px; height: 32px; flex-shrink: 0;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--edl-red), var(--edl-red-l));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.8rem;
        }
        .sidebar-user .user-name { font-size: 0.79rem; font-weight: 600; color: var(--text-main); line-height: 1.2; }
        .sidebar-user .user-role { font-size: 0.64rem; color: var(--text-muted); }

        .logout-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px; border-radius: 10px;
            color: #ff6b6b !important; text-decoration: none;
            font-size: 0.82rem; font-weight: 600;
            border: 1px solid rgba(255,107,107,0.2);
            transition: all 0.18s;
        }
        .logout-btn:hover { background: rgba(255,107,107,0.1); border-color: rgba(255,107,107,0.4); }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding: 30px;
            min-height: 100vh;
        }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            .sidebar { left: calc(-1 * var(--sidebar-w)); transition: left 0.28s ease; }
            .sidebar.active { left: 0; box-shadow: 10px 0 40px rgba(0,0,0,0.5); }
            .main-content { margin-left: 0; padding-top: 80px; }
            .mobile-head { display: flex !important; }
        }
        .mobile-head {
            display: none;
            position: fixed; top: 0; width: 100%; height: 58px;
            background: var(--sidebar-bg); backdrop-filter: blur(20px);
            z-index: 1001; padding: 0 18px;
            justify-content: space-between; align-items: center;
            border-bottom: 1px solid var(--edl-border);
        }
    </style>
</head>

<body class="loading">

    <!-- LOADER -->
    <div id="loader-wrapper"><div class="spinner"></div></div>

    <!-- MOBILE HEADER -->
    <div class="mobile-head">
        <span class="fw-bold" style="color:var(--text-main);"><i class="fas fa-bolt text-danger me-1"></i> EDL ADMIN</span>
        <button class="btn btn-sm border-0" onclick="toggleMenu()" style="background:var(--table-strip);border-radius:8px;padding:6px 12px;color:var(--text-main);">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <!-- Brand -->
        <a href="dashboard" class="sidebar-brand">
            <div class="bolt-icon"><i class="fas fa-bolt"></i></div>
            <div>
                <div class="brand-name">EDL ADMIN</div>
                <div class="brand-sub">Management Portal</div>
            </div>
        </a>

        <!-- Navigation -->
        <div class="sidebar-nav">

            <!-- Dashboard -->
            <a href="dashboard" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line ni"></i> Dashboard
            </a>

            <!-- Meter Management (accordion group) -->
            <div class="nav-group" id="grp-meter">
                <?php
                    $meterActive = in_array(basename($_SERVER['PHP_SELF']), ['meter_jobs.php','meter_change.php']);
                ?>
                <div class="nav-group-toggle <?php echo $meterActive ? 'open has-active' : ''; ?>" onclick="toggleGroup('grp-meter')">
                    <i class="fas fa-tachometer-alt ni"></i>
                    <span class="ng-label">Meter Management</span>
                    <i class="fas fa-chevron-right chevron"></i>
                </div>
                <div class="nav-sub <?php echo $meterActive ? 'open' : ''; ?>" id="sub-grp-meter">
                    <a href="meter_jobs" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'meter_jobs.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tools ni"></i> Meter Remove
                    </a>
                    <a href="meter_change" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'meter_change.php' ? 'active' : ''; ?>">
                        <i class="fas fa-exchange-alt ni"></i> Meter Change
                    </a>
                </div>
            </div>

            <!-- New Services -->
            <a href="new_services" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'new_services.php' ? 'active' : ''; ?>">
                <i class="fas fa-plug ni"></i> New Services
            </a>

            <!-- Reports -->
            <a href="reports" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt ni"></i> Reports Center
            </a>

            <!-- System (accordion group) -->
            <div class="nav-label-group">System</div>
            <div class="nav-group" id="grp-system">
                <?php
                    $sysActive = in_array(basename($_SERVER['PHP_SELF']), ['settings.php','activity_logs.php']);
                ?>
                <div class="nav-group-toggle <?php echo $sysActive ? 'open has-active' : ''; ?>" onclick="toggleGroup('grp-system')">
                    <i class="fas fa-sliders-h ni"></i>
                    <span class="ng-label">System</span>
                    <i class="fas fa-chevron-right chevron"></i>
                </div>
                <div class="nav-sub <?php echo $sysActive ? 'open' : ''; ?>" id="sub-grp-system">
                    <a href="settings" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                        <i class="fas fa-cog ni"></i> Settings
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Super Admin'): ?>
                    <a href="logs" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'activity_logs.php' ? 'active' : ''; ?>">
                        <i class="fas fa-history ni"></i> Audit Logs
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Support -->
            <div class="nav-label-group">Support</div>
            <a href="report_error" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'report_error.php' ? 'active' : ''; ?>">
                <i class="fas fa-bug ni"></i> Report Error
            </a>

        </div><!-- /sidebar-nav -->

        <!-- Footer: user + logout -->
        <div class="sidebar-footer">
            <?php if(isset($_SESSION['full_name'])): ?>
            <div class="sidebar-user">
                <div class="user-av"><i class="fas fa-user"></i></div>
                <div>
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($_SESSION['role'] ?? 'Officer'); ?></div>
                </div>
            </div>
            <?php endif; ?>
            <a href="logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
        </div>

    </div><!-- /sidebar -->

    <!-- MAIN CONTENT WRAPPER -->
    <div class="main-content">
        <?php include '../../includes/notification_component.php'; ?>
