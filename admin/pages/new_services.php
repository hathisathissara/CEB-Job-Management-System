<?php
// ============================================
// 1. AUTH MIDDLEWARE (Security, DB, Session Vars)
// ============================================
require_once '../middleware/authGuard.php';

// ============================================
// INCLUDE CONTROLLER (Add, Update, Delete Logic, Dashboard Counts)
// ============================================
include '../controllers/NewServiceController.php';

include '../layout/header.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><h3 class="fw-bold text-dark mb-0"><i class="fas fa-plug text-primary me-2"></i> New Connections</h3><span class="text-muted small">Application Workflow Management</span></div>
        <button class="btn btn-dark shadow-sm px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-circle me-2"></i> New Application</button>
    </div>

    <?php if($msg) echo "<div class='alert alert-success shadow-sm'>$msg</div>"; ?>
    <?php if($err) echo "<div class='alert alert-danger shadow-sm'>$err</div>"; ?>

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-info h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">ACTIVE FILES</h6><h2 class="fw-bold text-dark mb-0"><?php echo $c_tot; ?></h2><small class="text-muted" style="font-size:10px">Not Completed Yet</small></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-danger h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">SHORTCOMINGS</h6><h2 class="fw-bold text-danger mb-0"><?php echo $c_adu; ?></h2><small class="text-muted" style="font-size:10px">Adu Padu</small></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-warning h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">PENDING APPROVAL</h6><h2 class="fw-bold text-warning mb-0"><?php echo $c_appr; ?></h2><small class="text-muted" style="font-size:10px">At Area Office</small></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-success h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">PENDING CONNECTIONS</h6><h2 class="fw-bold text-success mb-0"><?php echo $c_job; ?></h2><small class="text-muted" style="font-size:10px">Jobs Created</small></div></div></div>
    </div>

    <!-- FILTER SECTION -->
    <div class="card shadow-sm border-0 mb-3 bg-white">
        <div class="card-body p-3 bg-light rounded">
            <form action="new_services" method="GET" class="row g-2 align-items-end">
                <?php $s=isset($_GET['s'])?trim($_GET['s']):''; $f=isset($_GET['f'])?trim($_GET['f']):''; $t=isset($_GET['t'])?trim($_GET['t']):''; ?>
                <div class="col-md-4"><label class="small fw-bold text-muted mb-1">Search Keyword</label>
                <input type="text" name="s" class="form-control" placeholder="App No / EST No / Name / NIC..." value="<?php echo htmlspecialchars($s); ?>"></div>
                <div class="col-md-3"><label class="small fw-bold text-muted mb-1">Service Type</label><select name="t" class="form-select "><option value="">All Types</option><option value="Normal" <?php if($t=='Normal')echo'selected';?>>Normal</option><option value="3 Phase" <?php if($t=='3 Phase')echo'selected';?>>3 Phase</option><option value="Augmentation" <?php if($t=='Augmentation')echo'selected';?>>Augmentation</option><option value="Over 100k" <?php if($t=='Over 100k')echo'selected';?>>Over 100k</option></select></div>
                <div class="col-md-3"><label class="small fw-bold text-muted mb-1">Status</label><select name="f" class="form-select"><option value="">All Status</option><option value="Registered" <?php if($f=='Registered')echo'selected';?>>Registered</option><option value="Shortcoming" <?php if($f=='Shortcoming')echo'selected';?>>Shortcoming (Adu Padu)</option><option value="Estimated" <?php if($f=='Estimated')echo'selected';?>>Estimated</option><option value="Pending Approval" <?php if($f=='Pending Approval')echo'selected';?>>Pending Approval</option><option value="Approved" <?php if($f=='Approved')echo'selected';?>>Approved</option><option value="Job Created" <?php if($f=='Job Created')echo'selected';?>>Job Created</option><option value="Completed" <?php if($f=='Completed')echo'selected';?>>Completed</option></select></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-filter"></i> Filter</button></div>
            </form>
            <?php if(!empty($s) || !empty($f) || !empty($t)) echo '<div class="mt-2"><a href="new_services" class="text-danger small fw-bold text-decoration-none"><i class="fas fa-times me-1"></i>Clear Filters</a></div>'; ?>        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark text-uppercase small"><tr><th width="20%">App No (Edit)</th><th width="30%">Customer Details</th><th width="25%">Status & Numbers</th><th width="25%">Dates Log</th></tr></thead>
                <tbody>
                    <?php
                    // --- QUERY BUILDER ---
                    $w = "WHERE 1=1";
                    $ignore_filters = false;
                    
                    if(!empty($s)) { 
                        $sc = $conn->real_escape_string($s); 
                        
                        // Smart Search: If the user searches an EXACT uniquely identifying number, ignore the dropdown filters
                        $check = $conn->query("SELECT id FROM new_connections WHERE app_no='$sc' OR est_no='$sc' OR id_number='$sc'");
                        if($check && $check->num_rows > 0) {
                            $ignore_filters = true;
                        }
                        
                        $w .= " AND (app_no LIKE '%$sc%' OR customer_name LIKE '%$sc%' OR id_number LIKE '%$sc%' OR est_no LIKE '%$sc%')"; 
                    }
                    
                    if(!$ignore_filters) {
                        if(!empty($f)) $w .= " AND status='$f'";
                        if(!empty($t)) $w .= " AND service_type='$t'";
                    }

                    $results_per_page=15; 

                    $results_per_page=15; $page=isset($_GET['page'])?(int)$_GET['page']:1; if($page<1)$page=1; $offset=($page-1)*$results_per_page;
                    $tot_res = $conn->query("SELECT COUNT(*) as t FROM new_connections $w")->fetch_assoc()['t']; $tot_pages = ceil($tot_res/$results_per_page);
                    
                    $res = $conn->query("SELECT * FROM new_connections $w ORDER BY id DESC LIMIT $results_per_page OFFSET $offset");

                    if($res->num_rows>0){
                        while($row=$res->fetch_assoc()){
                            $bg='bg-secondary';
                            if($row['status']=='Shortcoming')$bg='bg-danger';
                            if($row['status']=='Estimated')$bg='bg-info text-dark';
                            if($row['status']=='Pending Approval')$bg='bg-warning text-dark';
                            if($row['status']=='Approved')$bg='bg-primary';
                            if($row['status']=='Job Created')$bg='bg-dark';
                            if($row['status']=='Completed')$bg='bg-success';
                    ?>
                    <tr>
                        <td>
                            <a href="#" onclick='edit(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, "UTF-8"); ?>)' class="fw-bold text-decoration-none fs-6 comp-ref"><?php echo $row['app_no']; ?></a><br>
                            <span class="badge bg-light border text-secondary mt-1"><?php echo $row['service_type']; ?></span>
                        </td>
                        <td>
                            <b class="text-secondary"><?php echo $row['customer_name']; ?></b><br>
                            <small class="text-secondary">NIC: <?php echo $row['id_number']; ?></small>
                            <?php if($row['officer_note']): ?><div class="text-danger small mt-1 fst-italic"><i class="fas fa-comment-dots"></i> <?php echo $row['officer_note']; ?></div><?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $bg; ?> rounded-pill mb-1 px-3"><?php echo $row['status']; ?></span><br>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <?php if($row['est_no']) echo "<li>Est: <b class='text-dark'>{$row['est_no']}</b></li>"; ?>
                                <?php if($row['job_no']) echo "<li>Job: <b class='text-dark'>{$row['job_no']}</b></li>"; ?>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li>Reg: <?php echo date('Y-m-d', strtotime($row['created_at'])); ?></li>
                                <?php if($row['sent_date']) echo "<li>Sent: <span class='text-warning fw-bold'>{$row['sent_date']}</span></li>"; ?>
                                <?php if($row['approved_date']) echo "<li>Appr: <span class='text-primary fw-bold'>{$row['approved_date']}</span></li>"; ?>
                                <?php if($row['completed_date']) echo "<li>Done: <span class='text-success fw-bold'>{$row['completed_date']}</span></li>"; ?>
                            </ul>
                              <!-- NEW: DELETE BUTTON -->
                            <?php if ($_SESSION['role'] == 'Super Admin'): ?>
                                <div class="mt-2 text-end border-top pt-1">
                                    <a href="new_services?del=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to permanently delete App No: <?php echo $row['app_no']; ?>?');" class="text-danger small text-decoration-none hover-underline">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } } else { echo "<tr><td colspan='4' class='text-center py-5'>No Records</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
        
        <?php if($tot_pages > 1): ?>
        <nav class="p-3 border-top"><ul class="pagination justify-content-center mb-0">
            <li class="page-item <?php if($page<=1)echo'disabled';?>"><a class="page-link" href="?page=<?php echo $page-1;?>&s=<?php echo urlencode($s);?>&f=<?php echo $f;?>&t=<?php echo $t;?>">Previous</a></li>
            <li class="page-item disabled"><span class="page-link text-dark fw-bold">Page <?php echo $page; ?> / <?php echo $tot_pages; ?></span></li>
            <li class="page-item <?php if($page>=$tot_pages)echo'disabled';?>"><a class="page-link" href="?page=<?php echo $page+1;?>&s=<?php echo urlencode($s);?>&f=<?php echo $f;?>&t=<?php echo $t;?>">Next</a></li>
        </ul></nav>
        <?php endif; ?>
    </div>
</div>

<!-- 1. ADD MODAL (Initial Entry) -->
<div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST"><div class="modal-header bg-dark text-white"><h5 class="modal-title">New Application</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
    <div class="mb-3"><label class="fw-bold small">App Number (Req)</label><input type="text" name="app_no" class="form-control" value="535.20/NC/26/000" required></div>
    <div class="mb-3">
        <label class="fw-bold small">Initial Service Type</label>
        <select name="service_type" class="form-select">
            <option value="Normal">Normal</option>
            <option value="3 Phase">3 Phase</option>
        </select>
        <small class="text-muted" style="font-size:11px;">(Augmentation/Over 100k can be updated later)</small>
    </div>
    <div class="mb-3"><label class="fw-bold small">Customer Name (Req)</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3">
        <label class="fw-bold small">NIC Number (Req)</label>
        <input type="text" name="nic" id="nicInput" class="form-control" oninput="validateNIC(this)" required>
        <div id="nic-feedback" class="mt-1"></div>
    </div>
    <div class="mb-3"><label class="fw-bold small">Address (Opt)</label><textarea name="address" class="form-control" rows="2"></textarea></div>
</div><div class="modal-footer"><button type="submit" name="add_app" id="addAppBtn" class="btn btn-primary w-100 fw-bold">Register Application</button></div></form></div></div></div>

<!-- 2. UPDATE MODAL (Dynamic Workflow) -->
<div class="modal fade" id="updModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><form method="POST"><div class="modal-header bg-primary text-white"><h5 class="modal-title">Update Workflow</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
    <input type="hidden" name="app_id" id="u_id">
    
    <div class="row mb-3 bg-light p-2 rounded border">
        <div class="col-md-6"><h6 class="text-danger fw-bold mb-0" id="u_app"></h6><small class="text-muted" id="u_name"></small></div>
        <div class="col-md-6 text-end">
            <!-- Ability to change Service Type mid-way -->
            <label class="small fw-bold text-primary">Actual Service Type:</label>
            <select name="e_type" id="u_type" class="form-select form-select-sm border-primary" onchange="toggleWorkflow()">
                <option value="Normal">Normal</option>
                <option value="3 Phase">3 Phase</option>
                <option value="Augmentation">Augmentation</option>
                <option value="Over 100k">Over 100k</option>
            </select>
        </div>
    </div>

    <!-- STATUS DROP DOWN -->
    <div class="mb-3">
        <label class="fw-bold text-dark mb-1">Workflow Status</label>
        <select name="status" id="u_st" class="form-select fw-bold border-dark" onchange="toggleWorkflow()">
            <option value="Registered">1. Registered (New)</option>
            <option value="Shortcoming">2. Shortcoming (Adu Padu)</option>
            <option value="Estimated">3. Estimated (Est No Ready)</option>
            <option value="Pending Approval" id="opt_pendapp">4. Pending Approval (Sent to Area Office)</option>
            <option value="Approved" id="opt_appr">5. Approved (Returned from Area Office)</option>
            <option value="Job Created">6. Job Created (Fee Paid)</option>
            <option value="Completed">7. Completed (Connection Given & File Sent)</option>
        </select>
    </div>

    <!-- DYNAMIC FIELDS -->
    <div class="row g-3 mb-3">
        <div class="col-md-6" id="box_est"><label class="small fw-bold text-secondary">Estimate Number</label><input type="text" name="est_no" id="u_est"  class="form-control form-control-sm" ></div>
        <div class="col-md-6" id="box_job"><label class="small fw-bold text-secondary">Job Number</label><input type="text" name="job_no" id="u_job"  class="form-control form-control-sm" ></div>
    </div>

    <!-- DATES BOX (For Approvals & Completion) -->
    <div class="row g-3 mb-3 border-top pt-2" id="box_dates" style="display:none;">
        <div class="col-md-4" id="box_ds"><label class="small fw-bold text-warning">Sent for Approval</label><input type="date" name="sent_date" id="u_ds" class="form-control form-control-sm"></div>
        <div class="col-md-4" id="box_da"><label class="small fw-bold text-primary">Approval Received</label><input type="date" name="appr_date" id="u_da" class="form-control form-control-sm"></div>
        <div class="col-md-4" id="box_dc"><label class="small fw-bold text-success">Completed Date</label><input type="date" name="comp_date" id="u_dc" class="form-control form-control-sm"></div>
    </div>

    <div class="mb-2"><label class="small fw-bold text-danger">Remarks / Adu Padu Note:</label><textarea name="note" id="u_nt" rows="2" class="form-control form-control-sm"></textarea></div>

</div><div class="modal-footer"><button name="update_app" class="btn btn-primary w-100 fw-bold shadow">Save Progress</button></div></form></div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function validateNIC(input) {
        const val = input.value.trim();
        const feedback = document.getElementById('nic-feedback');
        const registerBtn = document.getElementById('addAppBtn');
        
        // Regex for old NIC: 9 digits + V/X
        const oldNicRegex = /^[0-9]{9}[vVxX]$/;
        // Regex for new NIC: 12 digits
        const newNicRegex = /^[0-9]{12}$/;

        if (val === "") {
            feedback.innerText = "";
            input.classList.remove('is-invalid', 'is-valid');
            registerBtn.disabled = false;
            return;
        }

        if (oldNicRegex.test(val) || newNicRegex.test(val)) {
            feedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> Valid NIC Format';
            feedback.className = "text-success small fst-italic";
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            registerBtn.disabled = false;
        } else {
            feedback.innerHTML = '<i class="fas fa-times-circle me-1"></i> Invalid NIC Format (Old: 9 digits+V/X, New: 12 digits)';
            feedback.className = "text-danger small fst-italic";
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            registerBtn.disabled = true;
        }
    }

    function edit(d) {
        document.getElementById('u_id').value = d.id;
        document.getElementById('u_app').innerText = d.app_no;
        document.getElementById('u_name').innerText = d.customer_name + " (NIC: " + d.id_number + ")";
        document.getElementById('u_type').value = d.service_type; // Set Type
        
        document.getElementById('u_st').value = d.status;
        document.getElementById('u_est').value = d.est_no || '';
        document.getElementById('u_job').value = d.job_no || '';
        document.getElementById('u_nt').value = d.officer_note || '';
        
        document.getElementById('u_ds').value = d.sent_date || '';
        document.getElementById('u_da').value = d.approved_date || '';
        document.getElementById('u_dc').value = d.completed_date || '';
        
        toggleWorkflow();
        new bootstrap.Modal(document.getElementById('updModal')).show();
    }

    function toggleWorkflow() {
        var st = document.getElementById('u_st').value;
        var type = document.getElementById('u_type').value;
        
        var isSpecial = (type === '3 Phase' || type === 'Augmentation' || type === 'Over 100k');

        // Show/Hide Approval options in dropdown based on Type
        document.getElementById('opt_pendapp').style.display = isSpecial ? 'block' : 'none';
        document.getElementById('opt_appr').style.display = isSpecial ? 'block' : 'none';
        
        // Hide all fields first
        document.getElementById('box_est').style.display = 'none';
        document.getElementById('box_job').style.display = 'none';
        document.getElementById('box_dates').style.display = 'none';
        document.getElementById('box_ds').style.display = 'none';
        document.getElementById('box_da').style.display = 'none';
        document.getElementById('box_dc').style.display = 'none';

        // Reveal based on Status
        if(st === 'Shortcoming') {
            // only note
        } else if(st === 'Estimated') {
            document.getElementById('box_est').style.display = 'block';
        } else if(st === 'Pending Approval') {
            document.getElementById('box_est').style.display = 'block';
            document.getElementById('box_dates').style.display = 'flex';
            document.getElementById('box_ds').style.display = 'block';
        } else if(st === 'Approved') {
            document.getElementById('box_est').style.display = 'block';
            document.getElementById('box_dates').style.display = 'flex';
            document.getElementById('box_ds').style.display = 'block';
            document.getElementById('box_da').style.display = 'block';
        } else if(st === 'Job Created') {
            document.getElementById('box_est').style.display = 'block';
            document.getElementById('box_job').style.display = 'block';
            if(isSpecial) {
                document.getElementById('box_dates').style.display = 'flex';
                document.getElementById('box_da').style.display = 'block';
            }
        } else if(st === 'Completed') {
            document.getElementById('box_est').style.display = 'block';
            document.getElementById('box_job').style.display = 'block';
            document.getElementById('box_dates').style.display = 'flex';
            if(isSpecial) {
                document.getElementById('box_da').style.display = 'block';
            }
            document.getElementById('box_dc').style.display = 'block';
        }
    }
</script>

<?php include '../layout/footer.php'; ?>