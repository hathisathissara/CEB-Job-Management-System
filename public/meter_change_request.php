<?php include '../config/db_conn.php'; ?>
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
    
   <link rel="stylesheet" href="assets/css/meterchange.css">
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
