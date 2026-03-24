<?php
// ============================================
// 1. AUTH MIDDLEWARE (Security, DB, Session Vars)
// ============================================
require_once '../middleware/authGuard.php';

include '../layout/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark"><i class="fas fa-bug text-danger me-2"></i> Report System Error</h3>
        <span class="text-muted small">Submit bugs, errors, or feedback to the development team</span>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 mb-0 d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <strong>Found a bug?</strong>
                        <div class="small">Fill out this form and we will look into it immediately.</div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Success/Error Messages -->
                <div id="successMsg" class="alert alert-success mb-4 border-0 bg-success bg-opacity-10 text-success fw-bold text-center d-none">
                    <i class="fas fa-check-circle me-1"></i> Your report has been submitted successfully. Thank you!
                </div>
                <div id="errorMsg" class="alert alert-danger mb-4 border-0 bg-danger bg-opacity-10 text-danger fw-bold text-center d-none">
                    <i class="fas fa-exclamation-circle me-1"></i> Oops! There was a problem submitting your form.
                </div>

                <form id="errorForm" action="https://formspree.io/f/xyzzzwkg" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary text-uppercase mb-1">Your Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="e.g. yourname@domain.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary text-uppercase mb-1">Error Type</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-tag text-muted"></i></span>
                            <select name="type" class="form-select border-start-0 ps-0">
                                <option value="System Error / Bug">System Error / Bug</option>
                                <option value="UI Issue">Design / UI Issue</option>
                                <option value="Feature Request">Feature Request</option>
                                <option value="Other">Other Feedback</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary text-uppercase mb-1">Describe the Issue</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Please provide details about the error you encountered... (What page, what happened, etc.)" required style="resize: none;"></textarea>
                    </div>

                    <!-- Hidden context fields for developer use -->
                    <input type="hidden" name="reporting_user" value="<?php echo htmlspecialchars($current_officer); ?>">
                    <input type="hidden" name="system_time" value="<?php echo date('Y-m-d H:i:s'); ?>">

                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-danger py-2 text-uppercase fw-bold" style="border-radius: 8px;">
                            <i class="fas fa-paper-plane me-2"></i> Submit Error Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('errorForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const successMsg = document.getElementById('successMsg');
    const errorMsg = document.getElementById('errorMsg');
    
    // UI state loading
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
    successMsg.classList.add('d-none');
    errorMsg.classList.add('d-none');
    
    try {
        const response = await fetch(form.action, {
            method: form.method,
            body: new FormData(form),
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            successMsg.classList.remove('d-none');
            form.reset(); // clear form
        } else {
            errorMsg.classList.remove('d-none');
        }
    } catch (err) {
        errorMsg.classList.remove('d-none');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>

<?php include '../layout/footer.php'; ?>
