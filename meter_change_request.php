<?php include 'db_conn.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Meter - CEB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="loader.css">

    <style>
        body {
            background: #f4f6f8;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-container {
            flex: 1;
        }

        .card-header-ceb {
            background: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0 !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #343a40;
            box-shadow: 0 0 5px rgba(52, 58, 64, 0.3);
        }

        .footer {
            background: #343a40;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <div id="loader-wrapper">
        <div class="spinner"></div>
    </div>
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home"><i class="fas fa-bolt text-danger"></i> CEB SERVICES</a>
            <a href="admin/login" class="btn btn-sm btn-outline-light">Officer Login</a>
        </div>
    </nav>

    <div class="main-container container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow border-0 rounded-3">
                    <div class="card-header card-header-ceb">
                        <i class="fas fa-exchange-alt fa-3x mb-2 text-warning"></i>
                        <h4 class="mb-0 fw-bold">Request Meter Change</h4>
                        <p class="small mb-0 opacity-75">Submit Replacement Request</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <?php
                        if (isset($_POST['submit_mc'])) {
                            $j = trim($_POST['job_no']);
                            $a = trim($_POST['acc_no']);
                            $om = trim($_POST['old_met']);
                            $p = $_POST['phase'];

                            if (!empty($j) && !empty($a) && !empty($om)) {
                                $chk = $conn->query("SELECT id FROM meter_change WHERE job_no = '$j'");
                                if ($chk->num_rows > 0) {
                                    echo "<div class='alert alert-danger text-center shadow-sm border-0'><i class='fas fa-exclamation-triangle'></i> Job No '<b>$j</b>' exists!</div>";
                                } else {
                                    $dev_time = !empty($_POST['dev_time']) ? $_POST['dev_time'] : date('Y-m-d H:i:s');
                                    $sql = "INSERT INTO meter_change (job_no, acc_no, old_meter_no, phase_type, created_at) VALUES ('$j','$a','$om','$p','$dev_time')";

                                    if ($conn->query($sql)) {
                                        echo "<div class='alert alert-success text-center mb-4 border-0 shadow-sm'><i class='fas fa-check-circle fa-2x mb-2 d-block text-success'></i><h5 class='fw-bold'>Registered!</h5>Job: <b>$j</b> saved.</div>";
                                        echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-warning'>Fill all fields!</div>";
                            }
                        }
                        ?>

                        <form method="POST" onsubmit="setTime()">
                            <input type="hidden" name="dev_time" id="dt">

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Job Number</label>
                                <input type="text" name="job_no" class="form-control" value="MC/I/26/" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">Account No</label>
                                    <input type="number" name="acc_no" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">Phase</label>
                                    <select name="phase" class="form-select">
                                        <option>Single Phase</option>
                                        <option>Three Phase</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Old Meter No</label>
                                <input type="text" name="old_met" class="form-control" required>
                            </div>

                            <button type="submit" name="submit_mc" class="btn btn-dark w-100 py-3 fw-bold shadow-sm">
                                SUBMIT REQUEST <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="home" class="text-decoration-none text-muted fw-bold">← Back to Main Menu</a>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container small">&copy; <?php echo date('Y'); ?> CEB Services.</div>
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