<?php include 'db_conn.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meter Job Request</title>
    <!-- Favicon -->
    <link rel="icon" href="https://img.icons8.com/color/48/d11212/flash-on.png" type="image/png">
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            background: #d11212;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0 !important;
        }

        .form-control:focus {
            border-color: #d11212;
            box-shadow: 0 0 5px rgba(209, 18, 18, 0.3);
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

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home"><i class="fas fa-bolt text-danger"></i> CEB SERVICES</a>
            <a href="admin/login" class="btn btn-sm btn-outline-secondary">Officer Login</a>
        </div>
    </nav>

    <div class="main-container container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow border-0 rounded-3">
                    <div class="card-header card-header-ceb">
                        <i class="fas fa-tools fa-3x mb-2"></i>
                        <h4 class="mb-0 fw-bold">Meter Job Registration</h4>
                        <p class="small mb-0 opacity-75">Submit Removal Request</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <!-- PHP SUBMIT LOGIC START -->
                        <?php
                        if (isset($_POST['submit_job'])) {
                            $job = trim($_POST['job_no']);
                            $acc = trim($_POST['acc_no']);
                            $met = trim($_POST['meter_no']);
                            
                            // 1. Get Device Time from Hidden Input
                            $dev_time = !empty($_POST['device_time']) ? $_POST['device_time'] : date('Y-m-d H:i:s');

                            // Basic Validation
                            if (!empty($job) && !empty($acc)) {

                                // DUPLICATE CHECK
                                $check_sql = "SELECT id FROM meter_removal WHERE job_no = '$job'";
                                $check_res = $conn->query($check_sql);

                                if ($check_res->num_rows > 0) {
                                    echo "<div class='alert alert-danger text-center shadow-sm fw-bold border-0'>
                                            <i class='fas fa-exclamation-triangle fa-2x d-block mb-2 text-danger'></i> 
                                            Job No '<b>$job</b>' already exists!
                                          </div>";
                                } else {
                                    // SUCCESS: Insert Data using $dev_time
                                    $sql = "INSERT INTO meter_removal (job_no, acc_no, meter_no, created_at) VALUES ('$job', '$acc', '$met', '$dev_time')";
                                    if ($conn->query($sql)) {
                                        echo "<div class='alert alert-success text-center mb-4 border-0 shadow-sm'>
                                                <i class='fas fa-check-circle fa-3x mb-2 d-block text-success'></i>
                                                <h5 class='fw-bold'>Successfully Registered!</h5>
                                                Job: <b>$job</b> saved at $dev_time.
                                              </div>";
                                        echo "<script>if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href ); }</script>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Database Error: " . $conn->error . "</div>";
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-warning'>Please fill required fields!</div>";
                            }
                        }
                        ?>
                        <!-- PHP LOGIC END -->

                        <!-- JS Function Hook Added to Form -->
                        <form method="POST" onsubmit="setDeviceTime()">
                            
                            <!-- Hidden Input to store JS time -->
                            <input type="hidden" name="device_time" id="d_time">

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Job Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-file-contract"></i></span>
                                    <input type="text" name="job_no" class="form-control form-control-lg" value="RC/I/26/" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Account Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                                    <input type="number" name="acc_no" class="form-control form-control-lg" placeholder="10 Digits" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Meter Number (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-tachometer-alt"></i></span>
                                    <input type="text" name="meter_no" class="form-control form-control-lg" placeholder="Serial No">
                                </div>
                            </div>

                            <button type="submit" name="submit_job" class="btn btn-dark w-100 py-3 fw-bold fs-5 shadow-sm">
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
        <div class="container small">&copy; <?php echo date('Y'); ?> Ceylon Electricity Board.</div>
    </footer>

    <!-- JAVASCRIPT TO CAPTURE TIME -->
    <script>
        function setDeviceTime() {
            const now = new Date();
            const y = now.getFullYear();
            const m = String(now.getMonth() + 1).padStart(2, '0');
            const d = String(now.getDate()).padStart(2, '0');
            const h = String(now.getHours()).padStart(2, '0');
            const i = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            
            const formattedTime = `${y}-${m}-${d} ${h}:${i}:${s}`;
            document.getElementById('d_time').value = formattedTime;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>