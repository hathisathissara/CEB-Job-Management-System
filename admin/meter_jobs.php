<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { header("Location: login.php"); exit(); }
include '../db_conn.php'; include 'functions.php';
$current_officer = $_SESSION['full_name'];
date_default_timezone_set('Asia/Colombo');
$msg = ""; $err = "";

// 1. ADD NEW
if (isset($_POST['add_job'])) {
    $j=trim($_POST['job_no']); $a=trim($_POST['acc_no']); $m=trim($_POST['meter_no']); $now=date('Y-m-d H:i:s');
    if($conn->query("SELECT id FROM meter_removal WHERE job_no='$j'")->num_rows>0){ $err="Job Number '$j' already exists!"; }
    else {
        $dev_time = !empty($_POST['device_time']) ? $_POST['device_time'] : $now;
        if($conn->query("INSERT INTO meter_removal (job_no,acc_no,meter_no,created_at) VALUES ('$j','$a','$m','$dev_time')")){
            addLog($conn, $current_officer, 'ADD JOB', "Created: $j"); $msg="Job Registered!";
        } else { $err=$conn->error; }
    }
}

// 2. UPDATE JOB
if (isset($_POST['update_job'])) {
    $id=intval($_POST['job_id']); 
    $nj=$_POST['e_job']; $na=$_POST['e_acc']; $nm=$_POST['e_met'];
    $st=$_POST['status_opt']; $rd=$_POST['reading']; $nt=$_POST['officer_note']; $dn=$_POST['done_by'];
    $rm_d = !empty($_POST['rem_date']) ? "'".$_POST['rem_date']."'" : "NULL";
    if($conn->query("UPDATE meter_removal SET job_no='$nj', acc_no='$na', meter_no='$nm', meter_reading='$rd', removing_date=$rm_d, done_by='$dn', officer_note='$nt', status='$st' WHERE id=$id")){
        addLog($conn, $current_officer, 'UPDATE JOB', "Updated $nj ($st)"); $msg="Updated Successfully!";
    }
}

// DASHBOARD COUNTS
$mj_loc = $conn->query("SELECT COUNT(DISTINCT acc_no) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$mj_pend = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Pending'")->fetch_assoc()['c'];
$mj_rem  = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Removed'")->fetch_assoc()['c'];
$mj_ret  = $conn->query("SELECT COUNT(*) c FROM meter_removal WHERE status='Returned - Paid'")->fetch_assoc()['c'];

include 'layout/header.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-tools text-danger"></i> Meter Removal Jobs</h3>
        <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addJobModal"><i class="fas fa-plus-circle"></i> Add Job</button>
    </div>

    <!-- STATS -->
    <div class="row g-2 mb-4">
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-danger h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">PENDING VISITS</h6><h2 class="fw-bold text-danger mb-0"><?php echo $mj_loc; ?></h2></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-warning h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">TOTAL JOBS</h6><h2 class="fw-bold text-dark mb-0"><?php echo $mj_pend; ?></h2></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-primary h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">REMOVED</h6><h2 class="fw-bold text-dark mb-0"><?php echo $mj_rem; ?></h2></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-start border-4 border-success h-100 p-2"><div class="card-body p-1"><h6 class="text-secondary small fw-bold">RETURNED</h6><h2 class="fw-bold text-dark mb-0"><?php echo $mj_ret; ?></h2></div></div></div>
    </div>

    <!-- FILTER SECTION -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body bg-light rounded shadow-inner">
            <form method="GET" class="row g-2 align-items-center">
                <?php $s=$_GET['s']??''; $f=$_GET['f']??''; $d1=$_GET['d1']??''; $d2=$_GET['d2']??''; $dup=isset($_GET['dup'])?1:0; ?>
                <div class="col-md-3"><input type="text" name="s" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($s); ?>"></div>
                <div class="col-md-2"><input type="date" name="d1" class="form-control" value="<?php echo $d1; ?>"></div>
                <div class="col-md-2"><input type="date" name="d2" class="form-control" value="<?php echo $d2; ?>"></div>
                <div class="col-md-2"><select name="f" class="form-select"><option value="">All Status</option><option value="Pending" <?php if($f=='Pending')echo'selected';?>>Pending</option><option value="Removed" <?php if($f=='Removed')echo'selected';?>>Removed</option><option value="Returned - Paid" <?php if($f=='Returned - Paid')echo'selected';?>>Returned</option></select></div>
                <div class="col-md-2 d-flex align-items-center bg-white border rounded p-1"><div class="form-check ms-2"><input class="form-check-input" type="checkbox" name="dup" value="1" id="dCheck" <?php if($dup)echo'checked';?>><label class="form-check-label small fw-bold text-danger" for="dCheck">Duplicates</label></div></div>
                <div class="col-md-1"><button class="btn btn-primary w-100" name="btn_filter"><i class="fas fa-filter"></i></button></div>
            </form>
        </div>
    </div>

    <?php
    // --- FILTER & QUERY LOGIC ---
    $sql_base = "SELECT * FROM meter_removal WHERE 1=1";
    if($dup) { $sql_base = "SELECT t1.* FROM meter_removal t1 INNER JOIN (SELECT acc_no FROM meter_removal GROUP BY acc_no HAVING COUNT(id)>1) t2 ON t1.acc_no=t2.acc_no WHERE 1=1"; }
    if(!empty($s)) $sql_base .= " AND (job_no LIKE '%$s%' OR acc_no LIKE '%$s%' OR meter_no LIKE '%$s%')";
    if(!empty($f)) $sql_base .= " AND status='$f'";
    if(!empty($d1) && !empty($d2)) {
        if($f == 'Removed') $sql_base .= " AND removing_date BETWEEN '$d1' AND '$d2'";
        else $sql_base .= " AND created_at BETWEEN '$d1 00:00:00' AND '$d2 23:59:59'";
    }

    // 1. Get Filtered Total Results
    $c_q = str_replace("SELECT *", "SELECT COUNT(*) as t", str_replace("SELECT t1.*", "SELECT COUNT(*) as t", $sql_base));
    $tot_res = $conn->query($c_q)->fetch_assoc()['t'];

    // Pagination Settings
    $results_per_page=10; $page=isset($_GET['page'])&&is_numeric($_GET['page'])?(int)$_GET['page']:1; if($page<1)$page=1; $offset=($page-1)*$results_per_page;
    $tot_pages = ceil($tot_res/$results_per_page);
    ?>

    <!-- 2. RESULT COUNTER DISPLAY -->
    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
        <div>
            <?php if(isset($_GET['btn_filter']) || !empty($s) || !empty($f) || $dup): ?>
                <span class="badge bg-info text-dark shadow-sm py-2 px-3">
                    <i class="fas fa-search me-1"></i> Found <b><?php echo $tot_res; ?></b> Matching Records
                </span>
                <a href="meter_jobs" class="text-danger small fw-bold text-decoration-none ms-2">Clear Filters</a>
            <?php else: ?>
                <span class="text-muted small">Showing latest jobs (Total: <?php echo $tot_res; ?>)</span>
            <?php endif; ?>
        </div>
        <div>
            <a href="export_meter.php?s=<?php echo urlencode($s);?>&f=<?php echo $f;?>&d1=<?php echo $d1;?>&d2=<?php echo $d2;?>&dup=<?php echo $dup;?>" class="btn btn-success btn-sm fw-bold shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- 3. DATA TABLE -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark"><tr><th>Job Info (Edit)</th><th>Account Info</th><th>Status</th><th>Results</th></tr></thead>
                <tbody>
                    <?php
                    $sql_base .= " ORDER BY id DESC LIMIT $results_per_page OFFSET $offset";
                    $res = $conn->query($sql_base);

                    if($res->num_rows>0){
                        while($row=$res->fetch_assoc()){
                            $bg='bg-secondary';
                            if($row['status']=='Pending')$bg='bg-warning text-dark';
                            if($row['status']=='Removed')$bg='bg-danger';
                            if($row['status']=='Returned - Paid')$bg='bg-success';
                            
                            $ac=$row['acc_no']; $qj=$conn->query("SELECT job_no FROM meter_removal WHERE acc_no='$ac' AND id!={$row['id']}");
                            $oc=$qj->num_rows; $jl=[]; while($jx=$qj->fetch_assoc()){$jl[]=$jx['job_no'];} $tt="Others: ".implode(", ",$jl);
                    ?>
                    <tr>
                        <td>
                            <a href="#" onclick="edit(<?php echo htmlspecialchars(json_encode($row)); ?>)" class="fw-bold text-decoration-none fs-5 comp-ref"><?php echo $row['job_no']; ?></a><br>
                            <small class="text-muted"><?php echo date('Y-m-d H:i',strtotime($row['created_at'])); ?></small>
                        </td>
                        <td><b><?php echo $row['acc_no']; ?></b><?php if($oc>0) echo "<span class='badge bg-dark ms-2' data-bs-toggle='tooltip' title='$tt'>+{$oc} Dup</span>"; ?><br><small class="text-muted">Met: <?php echo $row['meter_no']?:'-'; ?></small></td>
                        <td><span class="badge <?php echo $bg; ?> rounded-pill px-3"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if($row['status']!='Pending'): ?>
                                <ul class="list-unstyled small mb-0 text-muted">
                                    <?php if($row['status']=='Removed') echo "<li>Read: <b class='text-dark'>{$row['meter_reading']}</b></li><li>Date: {$row['removing_date']}</li>"; ?>
                                    <?php if($row['done_by']) echo "<li>By: {$row['done_by']}</li>"; ?>
                                    <?php if($row['officer_note']) echo "<li class='text-danger'>\"{$row['officer_note']}\"</li>"; ?>
                                </ul>
                            <?php else: echo '<span class="text-muted small">---</span>'; endif; ?>
                        </td>
                    </tr>
                    <?php } } else { echo "<tr><td colspan='4' class='text-center py-5 text-muted'>No Records Found</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION -->
        <?php if($tot_pages > 1): ?>
        <nav class="p-3 border-top bg-white"><ul class="pagination justify-content-center mb-0">
            <li class="page-item <?php if($page<=1)echo'disabled';?>"><a class="page-link" href="?page=<?php echo $page-1;?>&s=<?php echo urlencode($s);?>&f=<?php echo $f;?>&d1=<?php echo $d1;?>&d2=<?php echo $d2;?>&dup=<?php echo $dup;?>">Previous</a></li>
            <li class="page-item disabled"><span class="page-link text-dark fw-bold">Page <?php echo $page; ?> / <?php echo $tot_pages; ?></span></li>
            <li class="page-item <?php if($page>=$tot_pages)echo'disabled';?>"><a class="page-link" href="?page=<?php echo $page+1;?>&s=<?php echo urlencode($s);?>&f=<?php echo $f;?>&d1=<?php echo $d1;?>&d2=<?php echo $d2;?>&dup=<?php echo $dup;?>">Next</a></li>
        </ul></nav>
        <?php endif; ?>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addJobModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" onsubmit="setDeviceTime()"><div class="modal-header bg-dark text-white"><h5 class="modal-title">New Job Card</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="hidden" name="device_time" id="d_time"><div class="mb-3"><label class="fw-bold small">Job No</label><input type="text" name="job_no" class="form-control" value="RC/I/26/" required></div><div class="mb-3"><label class="fw-bold small">Account No</label><input type="text" name="acc_no" class="form-control" required></div><div class="mb-3"><label class="fw-bold small">Meter No</label><input type="text" name="meter_no" class="form-control"></div></div><div class="modal-footer"><button type="submit" name="add_job" class="btn btn-dark w-100 fw-bold shadow">REGISTER JOB</button></div></form></div></div></div>

<!-- UPDATE MODAL -->
<div class="modal fade" id="updModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST"><div class="modal-header bg-primary text-white"><h5 class="modal-title">Edit / Update Action</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
    <input type="hidden" name="job_id" id="u_id">
    <div class="p-2 border rounded bg-light mb-3"><div class="row g-2 mt-1">
        <div class="col-md-6"><label class="small fw-bold">Job No:</label><input type="text" name="e_job" id="u_job_input" class="form-control form-control-sm" required></div>
        <div class="col-md-6"><label class="small fw-bold">Acc No:</label><input type="text" name="e_acc" id="u_acc" class="form-control form-control-sm" required></div>
        <div class="col-md-12"><label class="small fw-bold">Meter No:</label><input type="text" name="e_met" id="u_met" class="form-control form-control-sm"></div>
    </div></div>
    <div class="mb-3"><label class="fw-bold text-primary mb-1 small">Current Status</label><select name="status_opt" id="u_st" class="form-select border-primary" onchange="toggleFields()"><option value="Pending">Pending</option><option value="Removed">Removed</option><option value="Returned - Paid">Returned / Paid</option><option value="Cancelled">Cancelled</option></select></div>
    <div class="p-3 bg-light rounded border mb-3"><label class="small fw-bold text-muted mb-1">Done By:</label><input type="text" name="done_by" id="u_done" class="form-control form-control-sm mb-2"><label class="small fw-bold text-muted mb-1">Notes / Remarks:</label><textarea name="officer_note" id="u_note" rows="2" class="form-control form-control-sm"></textarea></div>
    <div id="remove_fields" style="display:none;"><div class="row"><div class="col-6"><label class="small fw-bold">Reading:</label><input type="text" name="reading" id="u_read" class="form-control form-control-sm shadow-sm"></div><div class="col-6"><label class="small fw-bold">Remove Date:</label><input type="date" name="rem_date" id="u_rem" class="form-control form-control-sm shadow-sm"></div></div></div>
</div><div class="modal-footer"><button name="update_job" class="btn btn-primary w-100 fw-bold shadow">Save All Changes</button></div></form></div></div></div>

<script>
    function setDeviceTime() {
        const now = new Date();
        const y = now.getFullYear(); const m = String(now.getMonth()+1).padStart(2,'0'); const d = String(now.getDate()).padStart(2,'0');
        const h = String(now.getHours()).padStart(2,'0'); const i = String(now.getMinutes()).padStart(2,'0'); const s = String(now.getSeconds()).padStart(2,'0');
        document.getElementById('d_time').value = `${y}-${m}-${d} ${h}:${i}:${s}`;
    }
    function edit(d) {
        document.getElementById('u_id').value=d.id; document.getElementById('u_job_input').value=d.job_no; document.getElementById('u_acc').value=d.acc_no; document.getElementById('u_met').value=d.meter_no||'';
        document.getElementById('u_st').value=d.status; document.getElementById('u_done').value=d.done_by||''; document.getElementById('u_note').value=d.officer_note||''; document.getElementById('u_read').value=d.meter_reading||''; document.getElementById('u_rem').value=d.removing_date||'';
        toggleFields(); new bootstrap.Modal(document.getElementById('updModal')).show();
    }
    function toggleFields(){ var st=document.getElementById('u_st').value; document.getElementById('remove_fields').style.display=(st==='Removed')?'block':'none'; }
</script>

<?php include 'layout/footer.php'; ?>