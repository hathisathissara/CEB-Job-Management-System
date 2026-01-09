<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEB Office Portal</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        /* LOADER STYLES */
        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease-out;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #d11212;
            /* CEB Red */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        body.loading {
            overflow: hidden;
        }

        /* MAIN PAGE STYLES */
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .main-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            max-width: 900px;
            width: 95%;
            overflow: hidden;
            display: flex;
        }

        .row-fix {
            width: 100%;
            margin: 0;
        }

        .brand-side {
            background: linear-gradient(135deg, #2b323a 0%, #1a1e23 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 400px;
        }

        .ceb-logo i {
            font-size: 4rem;
            color: #d11212;
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(209, 18, 18, 0.5);
        }

        .system-title {
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .system-desc {
            opacity: 0.7;
            font-weight: 300;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .footer-credits {
            margin-top: 30px;
            font-size: 0.8rem;
            opacity: 0.6;
        }

        .footer-credits a {
            color: #f1c40f;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-credits a:hover {
            text-decoration: underline;
            color: white;
        }

        .menu-side {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .menu-btn {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            margin-bottom: 15px;
            border: 2px solid #eee;
            border-radius: 12px;
            text-decoration: none;
            color: #444;
            transition: all 0.3s ease;
            background: white;
        }

        .menu-btn:hover {
            background: #fffbfb;
            border-color: #d11212;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(209, 18, 18, 0.1);
        }

        .btn-icon {
            background: #f8f9fa;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.3rem;
            color: #d11212;
            margin-right: 15px;
            transition: 0.3s;
            flex-shrink: 0;
        }

        .menu-btn:hover .btn-icon {
            background: #d11212;
            color: white;
        }

        .btn-text-group {
            text-align: left;
        }

        .btn-title {
            font-weight: 700;
            font-size: 1.05rem;
            display: block;
            color: #222;
        }

        .btn-desc {
            font-size: 0.8rem;
            color: #777;
            margin: 0;
            display: block;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 0;
                display: block;
                overflow-y: auto;
                height: auto;
            }

            .main-box {
                flex-direction: column;
                margin: 20px auto;
            }

            .brand-side {
                padding: 40px 20px;
                min-height: auto;
            }

            .menu-side {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body class="loading">

    <!-- LOADER HTML -->
    <div id="loader-wrapper">
        <div class="spinner"></div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-box animate__animated animate__fadeIn">
        <div class="row row-fix">

            <!-- LEFT: Branding -->
            <div class="col-md-5 brand-side">
                <div class="ceb-logo"><i class="fas fa-bolt"></i></div>
                <h2 class="system-title">CEB OFFICE<br>PORTAL</h2>
                <p class="system-desc">
                    Meter Removal & Job Management System.<br>
                    <span class="badge bg-danger mt-2">Internal Use Only</span>
                </p>
                <div class="footer-credits">
                    <p class="mb-1">&copy; <?php echo date('Y'); ?> Ceylon Electricity Board</p>
                    <small>Version 3.0</small><br>
                    Developed by <a href="https://hathisathissara.unaux.com/" target="_blank">Hathisa Thissara</a>
                </div>
            </div>

            <!-- RIGHT: Menu Options -->
            <div class="col-md-7 menu-side">
                <h5 class="mb-4 text-secondary fw-bold text-uppercase small"><i class="fas fa-th-large me-2"></i> Select Tool</h5>

                <!-- Admin Link -->
                <a href="admin/login" class="menu-btn">
                    <div class="btn-icon"><i class="fas fa-user-shield"></i></div>
                    <div class="btn-text-group">
                        <span class="btn-title">Officer Login</span>
                        <span class="btn-desc">Dashboard, Reports, User Settings</span>
                    </div>
                </a>

                <!-- Job Entry Link -->
                <a href="job" class="menu-btn">
                    <div class="btn-icon"><i class="fas fa-pen-nib"></i></div>
                    <div class="btn-text-group">
                        <span class="btn-title">New Job Record</span>
                        <span class="btn-desc">Enter Meter Removal Requests</span>
                    </div>
                </a>

                <!-- Placeholder Link -->
                <div class="menu-btn" style="opacity: 0.6; cursor: not-allowed; border-style: dashed;">
                    <div class="btn-icon text-muted"><i class="fas fa-plus"></i></div>
                    <div class="btn-text-group">
                        <span class="btn-title text-muted">More Tools</span>
                        <span class="btn-desc">Future updates coming soon...</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- LOADER SCRIPT -->
    <script>
        window.addEventListener('load', function() {
            var loader = document.getElementById('loader-wrapper');
            // Slight delay for smooth feeling
            setTimeout(function() {
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                    document.body.classList.remove('loading');
                }, 500);
            }, 300);
        });
    </script>

</body>

</html>