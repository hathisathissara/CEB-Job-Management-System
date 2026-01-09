<!-- admin/layout/header.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEB Admin Portal</title>
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Unified Styles -->
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }

        /* --- SIDEBAR STYLES --- */
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background: #343a40;
            color: white;
            padding-top: 20px;
            z-index: 1000;
            top: 0;
            left: 0;
            transition: all 0.3s ease;
            /* Smooth slide effect */
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            color: #ccc;
            display: flex;
            align-items: center;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar a:hover {
            color: #fff;
            background: #495057;
        }

        .sidebar a.active {
            background: #d11212;
            color: white;
            border-left: 4px solid white;
        }

        .sidebar i {
            width: 30px;
            text-align: center;
        }

        /* --- MOBILE TOP BAR (Hidden on Desktop) --- */
        .mobile-top-bar {
            display: none;
            /* Hide on PC */
            background: #343a40;
            color: white;
            padding: 15px;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* --- MAIN CONTENT AREA --- */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        /* --- RESPONSIVE LOGIC (MOBILE) --- */
        @media (max-width: 768px) {

            /* Hide Sidebar by default on mobile */
            .sidebar {
                left: -250px;
            }

            /* Show Sidebar when this class is added via JS */
            .sidebar.show-sidebar {
                left: 0;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
            }

            /* Main content takes full width on mobile */
            .main-content {
                margin-left: 0;
                padding-top: 80px;
                /* Space for top bar */
            }

            /* Show Top Bar */
            .mobile-top-bar {
                display: flex;
            }

            /* Dark Overlay when menu is open */
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 900;
            }

            .overlay.active {
                display: block;
            }
        }

        /* Card & Other Styles */
        .stat-card {
            transition: transform 0.2s;
            border-left: 5px solid transparent;
            background: white;
            border-radius: 8px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .b-blue {
            border-left-color: #0d6efd;
        }

        .b-warning {
            border-left-color: #ffc107;
        }

        .b-info {
            border-left-color: #0dcaf0;
        }

        .b-success {
            border-left-color: #198754;
        }

        .comp-ref {
            font-weight: bold;
            color: #dc3545;
            cursor: pointer;
            text-decoration: underline;
        }

        .audit-text {
            font-size: 0.75rem;
            color: #6c757d;
            font-style: italic;
            border-top: 1px dotted #ccc;
            margin-top: 4px;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .page-link {
            color: #d11212;
            border-color: #dee2e6;
        }

        .page-item.active .page-link {
            background-color: #d11212;
            border-color: #d11212;
            color: white;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- START LOADER CODE -->
    <link rel="stylesheet" href="../loader.css"> <!-- Make sure path works for Admin -->

    <!-- HTML For Spinner -->
    <div id="loader-wrapper">
        <div class="spinner"></div>
    </div>

    <script>
        // Page load event (When everything is ready)
        window.addEventListener('load', function() {
            var loader = document.getElementById('loader-wrapper');
            // Fade Out effect
            loader.style.opacity = '0';
            // Remove from DOM after fade out
            setTimeout(function() {
                loader.style.display = 'none';
            }, 500);
        });
    </script>
    <!-- END LOADER CODE -->
    <!-- OVERLAY (Closes menu when clicked outside) -->
    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>

    <!-- MOBILE TOP BAR -->
    <div class="mobile-top-bar">
        <h4 class="m-0"><i class="fas fa-bolt text-danger"></i> CEB ADMIN</h4>
        <!-- BURGER BUTTON -->
        <button class="btn btn-outline-light border-0" onclick="toggleMenu()">
            <i class="fas fa-bars fa-lg"></i>
        </button>
    </div>

    <!-- SIDEBAR SECTION -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <!-- Close Button (Mobile Only) -->
        <div class="text-end d-md-none p-3">
            <button class="btn btn-sm btn-secondary" onclick="toggleMenu()">X Close</button>
        </div>

        <h4 class="text-center mb-4 pt-2 d-none d-md-block">
            <i class="fas fa-bolt text-danger"></i> <span class="fw-bold">CEB ADMIN</span>
        </h4>

        <!-- Links -->
        <a href="dashboard" class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'dashboard' || basename($_SERVER['PHP_SELF']) == 'admin_panel.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
        </a>

        <a href="meter_jobs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'meter_jobs.php' ? 'active' : ''; ?>">
            <i class="fas fa-tools"></i> <span>Meter Removing</span>
        </a>
        <a href="settings" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> <span>Settings</span>
        </a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Super Admin'): ?>
            <a href="logs" class="<?php echo basename($_SERVER['PHP_SELF']) == 'activity_logs.php' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i> <span>Audit Logs</span>
            </a>
        <?php endif; ?>

        <div class="mt-auto pb-4">
            <a href="logout" class="text-white-50">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- JAVASCRIPT TO TOGGLE MENU -->
    <script>
        function toggleMenu() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('overlay');
            sidebar.classList.toggle('show-sidebar');
            overlay.classList.toggle('active');
        }
    </script>