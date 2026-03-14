<?php
// Ensure DB Connection Exists
include '../db_conn.php';

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
    <link rel="stylesheet" href="../loader.css">
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
            --sidebar-w:    260px;

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

        /* ─────────────────────────────────────
           SIDEBAR
        ───────────────────────────────────── */
        .sidebar {
            height: 100vh;
            position: fixed;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            top: 0;
            left: 0;
            z-index: 1000;
            transition: 0.3s;
            border-right: 1px solid var(--edl-border);
            display: flex;
            flex-direction: column;
            padding-top: 0;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 24px;
            border-bottom: 1px solid var(--edl-border);
            text-decoration: none;
        }
        .sidebar-brand .bolt-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--edl-red) 0%, var(--edl-red-l) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: #fff;
            box-shadow: 0 4px 14px rgba(192,57,43,0.35);
            flex-shrink: 0;
        }
        .sidebar-brand .brand-text { line-height: 1.2; }
        .sidebar-brand .brand-name { font-size: 0.9rem; font-weight: 800; color: var(--text-main); letter-spacing: 0.5px; }
        .sidebar-brand .brand-sub  { font-size: 0.68rem; color: var(--text-muted); font-weight: 500; }

        .sidebar-nav { padding: 12px 0; flex: 1; }

        .sidebar-section {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--edl-muted);
            padding: 14px 24px 6px; opacity: 0.6;
        }

        .sidebar a {
            padding: 11px 24px;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            position: relative;
        }
        .sidebar a:hover {
            color: var(--text-main);
            background: var(--table-strip);
        }
        .sidebar a.active {
            border-left-color: var(--edl-red);
            color: var(--text-main);
            background: rgba(192,57,43,0.1);
        }
        .sidebar a .nav-icon {
            width: 20px; text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        .sidebar a.active .nav-icon { opacity: 1; color: var(--edl-red-l); }

        /* Sidebar user/logout panel */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--edl-border);
            margin-top: auto;
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 12px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--edl-border);
            margin-bottom: 10px;
        }
        .sidebar-user .user-av {
            width: 34px; height: 34px; border-radius: 9px;
            background: linear-gradient(135deg, var(--edl-red), var(--edl-red-l));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.9rem; flex-shrink: 0;
        }
        .sidebar-user .user-name { font-size: 0.82rem; font-weight: 600; color: #fff; }
        .sidebar-user .user-role { font-size: 0.7rem; color: var(--edl-muted); }

        .logout-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px; border-radius: 10px;
            color: #ff6b6b !important;
            text-decoration: none;
            font-size: 0.85rem; font-weight: 600;
            border: 1px solid rgba(255,107,107,0.2);
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: rgba(255,107,107,0.1);
            border-color: rgba(255,107,107,0.4);
            color: #ff6b6b !important;
        }

        /* ─────────────────────────────────────
           MAIN CONTENT
        ───────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding: 30px;
            transition: 0.3s;
            min-height: 100vh;
        }

        /* ─────────────────────────────────────
           MOBILE
        ───────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { left: -260px; top: 60px; height: calc(100vh - 60px); }
            .sidebar.active { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
            .main-content { margin-left: 0; padding-top: 80px; }
            .mobile-head { display: flex !important; }
        }

        .mobile-head {
            display: none;
            position: fixed; top: 0; width: 100%; height: 60px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            z-index: 1001; padding: 0 20px;
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
        <span class="fw-bold text-white"><i class="fas fa-bolt text-danger me-1"></i> EDL ADMIN</span>
        <button class="btn btn-sm border-0 text-white" onclick="toggleMenu()" style="background: rgba(255,255,255,0.07); border-radius:8px; padding:6px 12px;">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <!-- Brand -->
        <a href="dashboard" class="sidebar-brand">
            <div class="bolt-icon"><i class="fas fa-bolt"></i></div>
            <div class="brand-text">
                <div class="brand-name">EDL ADMIN</div>
                <div class="brand-sub">Management Portal</div>
            </div>
        </a>

        <!-- Navigation -->
        <div class="sidebar-nav">
            <div class="sidebar-section">Main</div>
            <a href="dashboard" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line nav-icon"></i> Dashboard
            </a>
            <a href="meter_jobs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'meter_jobs.php' ? 'active' : ''; ?>">
                <i class="fas fa-tools nav-icon"></i> Meter Jobs
            </a>
            <a href="meter_change" class="<?php echo basename($_SERVER['PHP_SELF']) == 'meter_change.php' ? 'active' : ''; ?>">
                <i class="fas fa-exchange-alt nav-icon"></i> Meter Change
            </a>
            <a href="reports" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt nav-icon"></i> Reports Center
            </a>

            <div class="sidebar-section">System</div>
            <a href="settings" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog nav-icon"></i> Settings
            </a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Super Admin'): ?>
            <a href="logs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'activity_logs.php' ? 'active' : ''; ?>">
                <i class="fas fa-history nav-icon"></i> Audit Logs
            </a>
            <?php endif; ?>
        </div>

        <!-- User / Logout -->
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
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="main-content">
        <?php include '../notification_component.php'; ?>
