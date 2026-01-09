<?php
// Retrieve User Theme (Defaults to Light)
$theme = $_SESSION['theme'] ?? 'light';

// Color Palette
if ($theme == 'dark') {
    // UPDATED DARK THEME (DARK GRAY #191919)
    $bg_body = '#191919';
    $bg_card = '#212121'; // Slightly lighter than body
    $text_main = '#e0e0e0';
    $text_muted = '#b0b0b0';
    $sidebar_bg = '#111111'; // Pure dark for sidebar
    $border_color = '#333333';
    $input_bg = '#2a2a2a';
} else {
    // LIGHT THEME
    $bg_body = '#f8f9fa';
    $bg_card = '#ffffff';
    $text_main = '#212529';
    $text_muted = '#6c757d';
    $sidebar_bg = '#343a40';
    $border_color = '#dee2e6';
    $input_bg = '#ffffff';
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

    <style>
        :root {
            --bg-body: <?php echo $bg_body; ?>;
            --bg-card: <?php echo $bg_card; ?>;
            --text-main: <?php echo $text_main; ?>;
            --text-muted: <?php echo $text_muted; ?>;
            --sidebar-bg: <?php echo $sidebar_bg; ?>;
            --border-col: <?php echo $border_color; ?>;
            --input-bg: <?php echo $input_bg; ?>;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Segoe UI', sans-serif;
            transition: 0.2s;
        }

        /* CARD OVERRIDES FOR DARK MODE */
        .card,
        .modal-content,
        .list-group-item {
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
            border-color: var(--border-col) !important;
        }

        .bg-light {
            background-color: var(--bg-body) !important;
        }

        .text-dark {
            color: var(--text-main) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* TABLE */
        .table {
            color: var(--text-main);
            --bs-table-border-color: var(--border-col);
        }

        .table-hover tbody tr:hover {
            background-color: var(--border-col);
            color: white;
        }

        /* FORMS */
        .form-control,
        .form-select {
            background-color: var(--input-bg);
            border-color: var(--border-col);
            color: var(--text-main);
        }

        .form-control:focus {
            background-color: var(--input-bg);
            color: var(--text-main);
            border-color: #d11212;
        }

        /* SIDEBAR */
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background: var(--sidebar-bg);
            padding-top: 20px;
            z-index: 1000;
            left: 0;
            transition: 0.3s;
        }

        .sidebar a {
            padding: 15px;
            color: #999;
            text-decoration: none;
            display: flex;
            align-items: center;
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
            padding: 20px;
            transition: 0.3s;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
                box-shadow: 10px 0 20px rgba(0, 0, 0, 0.5);
            }

            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }

            .mobile-head {
                display: flex !important;
                position: fixed;
                top: 0;
                width: 100%;
                background: var(--sidebar-bg);
                z-index: 999;
                padding: 15px;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }
        }

        .mobile-head {
            display: none;
        }
    </style>
</head>

<body>

    <div class="mobile-head shadow-sm text-white">
        <span class="fw-bold"><i class="fas fa-bolt text-danger"></i> ADMIN</span>
        <button class="btn btn-sm btn-outline-secondary border-0 text-white" onclick="toggleMenu()"><i class="fas fa-bars fa-lg"></i></button>
    </div>

    <div class="sidebar d-flex flex-column" id="sidebar">
        <h4 class="text-center text-white mb-4 d-none d-md-block pt-2 fw-bold" style="letter-spacing:1px;"><i class="fas fa-bolt text-danger"></i> CEB ADMIN</h4>

        <a href="dashboard" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_panel.php' ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="meter_jobs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'meter_jobs.php' ? 'active' : ''; ?>"><i class="fas fa-tools"></i> Meter Jobs</a>

        <!-- User Links -->
        <div class="mt-4 px-3 mb-2 small text-secondary text-uppercase fw-bold">My Account</div>

        <a href="settings" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> Settings & Theme</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Super Admin'): ?>
            <a href="logs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'activity_logs.php' ? 'active' : ''; ?>"><i class="fas fa-history"></i> Audit Logs</a>
        <?php endif; ?>

        <div class="mt-auto p-3 text-center">
            <a href="logout" class="text-white-50 text-decoration-none small hover-red"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>