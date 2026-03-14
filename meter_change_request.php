<?php include 'db_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Meter - EDL</title>
    <!-- Favicon -->
    <link rel="icon" href="https://img.icons8.com/color/48/d11212/flash-on.png" type="image/png">
    
    <!-- Fonts & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loader.css">
    
    <style>
        :root {
            --red:      #c0392b;
            --red-l:    #e74c3c;
            --gold:     #f39c12;
            --dark:     #0d0f12;
            --dark2:    #13161c;
            --border:   rgba(255,255,255,0.07);
            --muted:    rgba(255,255,255,0.45);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        body.loading { overflow: hidden; }

        /* Background */
        .bg-base {
            position: fixed; inset: 0; z-index: -2;
            background: linear-gradient(135deg, #0d0f12 0%, #13161c 60%, #0f1218 100%);
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: -1;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 56px 56px;
        }
        
        .orb {
            position: fixed; border-radius: 50%; filter: blur(100px); z-index: -1; pointer-events: none;
            animation: floatOrb 9s ease-in-out infinite;
        }
        .orb-2 { width:400px; height:400px; background: rgba(243, 156, 18, 0.1); bottom:-100px; right:-100px; }

        @keyframes floatOrb {
            0%,100%{ transform:translate(0,0) scale(1); }
            50%    { transform:translate(-30px,20px) scale(0.95); }
        }

        .main-container { flex: 1; position: relative; z-index: 10; padding: 60px 20px; }

        /* Navbar */
        .navbar-edl {
            background: rgba(13,15,18,0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 15px 40px;
        }

        /* Glass Form Card */
        .glass-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
            overflow: hidden;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header-edl {
            background: rgba(243, 156, 18, 0.1);
            border-bottom: 1px solid rgba(243, 156, 18, 0.2);
            padding: 30px 20px;
            text-align: center;
        }
        
        .card-header-edl i {
            font-size: 2.5rem;
            color: var(--gold);
            margin-bottom: 10px;
            text-shadow: 0 0 20px rgba(243, 156, 18, 0.4);
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .input-group-text, .form-control, .form-select {
            background-color: rgba(255,255,255,0.05); /* Avoid overriding BS form-select backgrounds completely */
            border: 1px solid var(--border);
            color: white;
            padding: 12px 18px;
        }
        
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }
        .form-select option {
            background: var(--dark);
            color: white;
        }

        .input-group-text { color: var(--muted); border-right: none; }
        .form-control { border-left: none; }

        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.08);
            border-color: var(--gold);
            color: white;
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.15);
        }

        .btn-submit {
            background: linear-gradient(135deg, #d35400 0%, #f39c12 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 10px 30px rgba(243, 156, 18, 0.2);
            transition: all 0.3s;
            color: white;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(243, 156, 18, 0.35);
            color: white;
        }

        .back-link {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.2s;
        }
        .back-link:hover { color: white; }

        .footer {
            border-top: 1px solid var(--border);
            padding: 20px 0;
            text-align: center;
            font-size: 0.8rem;
            color: var(--muted);
            background: rgba(255,255,255,0.02);
            margin-top: auto;
        }
    </style>
</head>

<body class="loading">
    <div id="loader-wrapper"><div class="spinner"></div></div>
    <div class="bg-base"></div>
    <div class="bg-grid"></div>
    <div class="orb orb-2"></div>

    <nav class="navbar-edl d-flex justify-content-between align-items-center">
        <a class="text-decoration-none fw-bold" href="home" style="color:white; font-size:1.1rem;">
            <i class="fas fa-bolt" style="color:var(--red-l); margin-right:8px;"></i> EDL SERVICES
        </a>
        <a href="admin/login" class="btn btn-sm" style="background:rgba(255,255,255,0.1); color:white; border-radius:8px;"><i class="fas fa-user-shield me-2"></i>Officer Login</a>
    </nav>

    <div class="main-container container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="glass-card">
                    <div class="card-header-edl">
                        <i class="fas fa-exchange-alt text-warning"></i>
                        <h4 class="mb-0 fw-bold text-white">Request Meter Change</h4>
                        <p class="small mb-0 mt-1" style="color:rgba(255,255,255,0.7);">Submit Replacement Request</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <!-- PHP SUBMIT LOGIC START -->
                        <?php
                        if (isset($_POST['submit_mc'])) {
                            $j = trim($_POST['job_no']);
                            $a = trim($_POST['acc_no']);
                            $om = trim($_POST['old_met']);
                            $p = $_POST['phase'];

                            if (!empty($j) && !empty($a) && !empty($om)) {
                                $chk = $conn->query("SELECT id FROM meter_change WHERE job_no = '$j'");
                                if ($chk->num_rows > 0) {
                                    echo "<div class='alert' style='background:rgba(192,57,43,0.1); border:1px solid var(--red-l); color:var(--red-l); text-align:center;'>
                                            <i class='fas fa-exclamation-triangle fa-2x d-block mb-2'></i> 
                                            Job No '<b>$j</b>' exists!
                                          </div>";
                                } else {
                                    $dev_time = !empty($_POST['dev_time']) ? $_POST['dev_time'] : date('Y-m-d H:i:s');
                                    $sql = "INSERT INTO meter_change (job_no, acc_no, old_meter_no, phase_type, created_at) VALUES ('$j','$a','$om','$p','$dev_time')";

                                    if ($conn->query($sql)) {
                                        echo "<div class='alert' style='background:rgba(46,213,115,0.1); border:1px solid #2ed573; color:#2ed573; text-align:center;'>
                                                <i class='fas fa-check-circle fa-3x d-block mb-2'></i>
                                                <h5 class='fw-bold text-white'>Successfully Registered!</h5>
                                                Job: <b>$j</b> saved.
                                              </div>";
                                        echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-warning text-dark text-center'>Fill all fields!</div>";
                            }
                        }
                        ?>
                        <!-- PHP LOGIC END -->

                        <form method="POST" onsubmit="setTime()">
                            <input type="hidden" name="dev_time" id="dt">

                            <div class="mb-4">
                                <label class="form-label">Job Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-file-contract"></i></span>
                                    <input type="text" name="job_no" class="form-control" value="MC/I/26/" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Account No</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <input type="number" name="acc_no" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Phase</label>
                                    <select name="phase" class="form-select">
                                        <option>Single Phase</option>
                                        <option>Three Phase</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Old Meter No</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                    <input type="text" name="old_met" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" name="submit_mc" class="btn btn-primary w-100 btn-submit mt-2">
                                SUBMIT REQUEST <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </form>

                    </div>
                </div>

                <div class="text-center mt-5">
                    <a href="home" class="back-link"><i class="fas fa-arrow-left me-2"></i>Back to Main Menu</a>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">&copy; <?php echo date('Y'); ?> EDL SERVICES.</div>
    </footer>

    <script>
        function setTime() {
            const n = new Date();
            const f = `${n.getFullYear()}-${String(n.getMonth()+1).padStart(2,'0')}-${String(n.getDate()).padStart(2,'0')} ${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')}`;
            document.getElementById('dt').value = f;
        }

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
