<?php include '../config/db_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Connection - EDL</title>
    <!-- Favicon -->
    <link rel="icon" href="https://img.icons8.com/color/48/d11212/flash-on.png" type="image/png">
    
    <!-- Fonts & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loader.css">
    <link rel="stylesheet" href="assets/css/newservice.css">
</head>

<body class="loading">
    <div id="loader-wrapper"><div class="spinner"></div></div>
    <div class="bg-base"></div>
    <div class="bg-grid"></div>
    <div class="orb orb-3"></div>

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
                        <i class="fas fa-plug text-info"></i>
                        <h4 class="mb-0 fw-bold text-white">New Connection Entry</h4>
                        <p class="small mb-0 mt-1" style="color:rgba(255,255,255,0.7);">Register new service application</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <!-- PHP SUBMIT LOGIC START -->
                        <?php
                        if(isset($_POST['submit_app'])) {
                            $app = trim($_POST['app_no']);
                            $name = trim($_POST['cust_name']);
                            $nic = trim($_POST['nic']);
                            $type = $_POST['service_type'];
                            $addr = trim($_POST['address']);

                            if(!empty($app) && !empty($name) && !empty($nic)) {
                                
                                // NIC Validation
                                if (!preg_match("/^([0-9]{9}[vVxX]|[0-9]{12})$/", $nic)) {
                                    echo "<div class='alert' style='background:rgba(192,57,43,0.1); border:1px solid var(--red-l); color:var(--red-l); text-align:center;'>
                                            <i class='fas fa-exclamation-circle d-block mb-1 fa-2x'></i> Invalid NIC Format!
                                          </div>";
                                } else {
                                    // Duplicate Check
                                    $check = $conn->query("SELECT id FROM new_connections WHERE app_no = '$app'");
                                    
                                    if ($check->num_rows > 0) {
                                        echo "<div class='alert' style='background:rgba(192,57,43,0.1); border:1px solid var(--red-l); color:var(--red-l); text-align:center;'>
                                                <i class='fas fa-times-circle fa-2x d-block mb-2'></i> 
                                                Application No '<b>$app</b>' already exists!
                                              </div>";
                                    } else {
                                        $sql = "INSERT INTO new_connections (app_no, customer_name, id_number, service_type, address) VALUES ('$app', '$name', '$nic', '$type', '$addr')";
                                        if($conn->query($sql)){
                                            echo "<div class='alert' style='background:rgba(46,213,115,0.1); border:1px solid #2ed573; color:#2ed573; text-align:center;'>
                                                    <i class='fas fa-check-circle fa-3x d-block mb-2'></i>
                                                    <h5 class='fw-bold text-white'>Successfully Registered!</h5>
                                                    Application <b>$app</b> saved.
                                                  </div>";
                                            echo "<script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>";
                                        } else {
                                            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                                        }
                                    }
                                }
                            } else {
                                echo "<div class='alert alert-warning text-dark text-center'>Fill required fields!</div>";
                            }
                        }
                        ?>
                        <!-- PHP LOGIC END -->

                        <form method="POST">
                            
                            <div class="mb-4">
                                <label class="form-label">Application Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" name="app_no" class="form-control" value="535.20/NC/26/000" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Requested Service Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-bolt"></i></span>
                                    <select name="service_type" class="form-select" required>
                                        <option value="Normal">Normal Connection</option>
                                        <option value="3 Phase">Three Phase Connection</option>
                                    </select>
                                </div>
                                <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> Augmentation and Over 100k can be updated later by an officer.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="cust_name" class="form-control" placeholder="Full Name" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">NIC Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" name="nic" class="form-control" placeholder="9 digits + V or 12 digits" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Address (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-textarea align-items-start"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Location Details"></textarea>
                                </div>
                            </div>

                            <button type="submit" name="submit_app" class="btn btn-primary w-100 btn-submit mt-2">
                                REGISTER APPLICATION <i class="fas fa-arrow-right ms-2"></i>
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

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">&copy; <?php echo date('Y'); ?> EDL SERVICES.</div>
    </footer>

    <script>
        window.addEventListener('load', function() {
            var loader = document.getElementById('loader-wrapper');
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