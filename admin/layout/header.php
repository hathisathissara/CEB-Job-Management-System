<?php
// Ensure DB Connection Exists
if (!isset($conn)) {
    if (file_exists('../db_conn.php')) include '../db_conn.php';
    else include '../../db_conn.php';
}

// --- THEME SYNC ---
if (isset($_SESSION['user_id'])) {
    $uid_theme = $_SESSION['user_id'];
    $res_theme = $conn->query("SELECT theme FROM users WHERE id='$uid_theme'");
    if ($res_theme && $res_theme->num_rows > 0) {
        $row_theme = $res_theme->fetch_assoc();
        $_SESSION['theme'] = $row_theme['theme'];
        $theme = $row_theme['theme'];
    } else {
        $theme = 'light';
    }
} else {
    $theme = 'light';
}

// --- COLOR PALETTE ---
if ($theme == 'dark') {
    $bg_body = '#191919';
    $bg_card = '#212121';
    $text_main = '#e0e0e0';
    $text_muted = '#b0b0b0';
    $sidebar_bg = '#111111';
    $border_color = '#333333';
    $input_bg = '#2a2a2a';
    $table_strip = '#2c3034';
} else {
    $bg_body = '#f8f9fa';
    $bg_card = '#ffffff';
    $text_main = '#212529';
    $text_muted = '#6c757d';
    $sidebar_bg = '#343a40';
    $border_color = '#dee2e6';
    $input_bg = '#ffffff';
    $table_strip = '#f2f2f2';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEB Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ANIMATE CSS For Notification -->
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
        :root {
            --bg-body: <?php echo $bg_body; ?>;
            --bg-card: <?php echo $bg_card; ?>;
            --text-main: <?php echo $text_main; ?>;
            --text-muted: <?php echo $text_muted; ?>;
            --sidebar-bg: <?php echo $sidebar_bg; ?>;
            --border-col: <?php echo $border_color; ?>;
            --input-bg: <?php echo $input_bg; ?>;
            --table-strip: <?php echo $table_strip; ?>;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Segoe UI', sans-serif;
            transition: 0.2s;
        }

        /* --- DARK MODE OVERRIDES --- */
        .card,
        .modal-content,
        .list-group-item {
            background-color: var(--bg-card) !important;
            border-color: var(--border-col) !important;
            color: var(--text-main) !important;
        }

        .bg-light {
            background-color: var(--bg-body) !important;
        }

        .bg-white {
            background-color: var(--bg-card) !important;
        }

        /* Force Text Colors */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .card-title,
        .modal-title,
        label,
        td,
        th {
            color: var(--text-main) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .border {
            border-color: var(--border-col) !important;
        }

        /* Tables */
        .table {
            color: var(--text-main) !important;
            --bs-table-border-color: var(--border-col);
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            color: var(--text-main);
            background-color: var(--table-strip);
        }

        .table-hover tbody tr:hover>* {
            color: var(--text-main);
            background-color: var(--border-col);
        }

        /* FIX: Header Row Background */
        .table-light,
        .table-light th {
            background-color: var(--sidebar-bg) !important;
            color: var(--text-main) !important;
            border-color: var(--border-col);
        }

        /* Forms */
        .form-control,
        .form-select {
            background-color: var(--input-bg);
            border-color: var(--border-col);
            color: var(--text-main);
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--input-bg);
            color: var(--text-main);
            border-color: #d11212;
            box-shadow: none;
        }

        ::placeholder {
            color: var(--text-muted) !important;
            opacity: 0.7;
        }

        /* --- FIX COLORED BORDERS --- */
        .border-primary {
            border-color: #0d6efd !important;
        }

        .border-secondary {
            border-color: #6c757d !important;
        }

        .border-success {
            border-color: #198754 !important;
        }

        .border-danger {
            border-color: #dc3545 !important;
        }

        .border-warning {
            border-color: #ffc107 !important;
        }

        .border-info {
            border-color: #0dcaf0 !important;
        }

        .border-dark {
            border-color: #212529 !important;
        }

        /* --- FIX TEXT COLORS IN DARK MODE --- */
        <?php if ($theme == 'dark'): ?>

        /* This is the key fix: text-dark becomes white in dark mode */
        .text-dark {
            color: #e0e0e0 !important;
        }

        .text-danger {
            color: #ff6b6b !important;
        }

        .text-success {
            color: #51cf66 !important;
        }

        .text-primary {
            color: #4dabf7 !important;
        }

        .text-warning {
            color: #fcc419 !important;
        }

        <?php else: ?>.text-dark {
            color: #212529 !important;
        }

        <?php endif; ?>

        /* --- SIDEBAR STYLES --- */
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background: var(--sidebar-bg);
            top: 0;
            left: 0;
            z-index: 1000;
            transition: 0.3s;
            border-right: 1px solid var(--border-col);
            display: flex;
            flex-direction: column;
            padding-top: 0;
        }

        .sidebar a {
            padding: 15px 20px;
            color: #999;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
        }

        .sidebar a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar a.active {
            border-left: 4px solid #d11212;
            color: #fff;
            background: rgba(209, 18, 18, 0.15);
        }

        .sidebar i {
            width: 30px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            transition: 0.3s;
        }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
                top: 60px;
                height: calc(100vh - 60px);
            }

            .sidebar.active {
                left: 0;
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }

            .main-content {
                margin-left: 0;
                padding-top: 80px;
            }

            .mobile-head {
                display: flex !important;
                position: fixed;
                top: 0;
                width: 100%;
                height: 60px;
                background: var(--sidebar-bg);
                z-index: 1001;
                padding: 0 20px;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid var(--border-col);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }
        }

        .mobile-head {
            display: none;
        }
    </style>
</head>

<body class="loading">

    <!-- 1. LOADER -->
    <div id="loader-wrapper">
        <div class="spinner"></div>
    </div>

    <!-- MOBILE HEADER -->
    <div class="mobile-head shadow-sm text-white">
        <span class="fw-bold"><i class="fas fa-bolt text-danger"></i> ADMIN</span>
        <button class="btn btn-sm btn-outline-secondary border-0 text-white" onclick="toggleMenu()"><i class="fas fa-bars fa-lg"></i></button>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <h4 class="text-center text-white mb-4 d-none d-md-block pt-4 fw-bold" style="letter-spacing:1px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px;">
            <i class="fas fa-bolt text-danger"></i> CEB ADMIN
        </h4>
        <a href="dashboard" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_panel.php' ? 'active' : ''; ?>"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
        <a href="meter_jobs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'meter_jobs.php' ? 'active' : ''; ?>"><i class="fas fa-tools me-2"></i> Meter Jobs</a>
        <a href="meter_change" class="<?php echo basename($_SERVER['PHP_SELF']) == 'meter_change.php' ? 'active' : ''; ?>"><i class="fas fa-exchange-alt me-2"></i> Meter Change</a>
        <a href="reports" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>"><i class="fas fa-file-alt me-2"></i> Reports Center</a>

        <div class="mt-4 px-3 mb-2 small text-uppercase fw-bold" style="color:var(--text-muted); opacity:0.6;">System</div>

        <a href="settings" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>"><i class="fas fa-cog me-2"></i> Settings</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Super Admin'): ?>
            <a href="logs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'activity_logs.php' ? 'active' : ''; ?>"><i class="fas fa-history me-2"></i> Audit Logs</a>
        <?php endif; ?>

        <div class="mt-auto p-3">
            <a href="logout" class="text-danger fw-bold text-decoration-none small text-center d-block py-2 border border-danger rounded hover-red"><i class="fas fa-sign-out-alt me-2"></i> Log Out</a>
        </div>
    </div>
    <div class="main-content">
        <?php include '../notification_component.php'; ?>