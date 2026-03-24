<?php
// Make sure DB connection exists before this
if (isset($conn)) {
    $not_res = $conn->query("SELECT * FROM system_settings WHERE id=1");
    if ($not_res && $not_res->num_rows > 0) {
        $sys_note = $not_res->fetch_assoc();

        // Only show if Active
        if ($sys_note['is_active'] == 1) {
?>
            <div class="system-notification-bar">
                <div class="container d-flex align-items-center justify-content-center">
                    <i class="fas fa-bullhorn fa-lg me-2 animate__animated animate__swing animate__infinite"></i>
                    <span><?php echo htmlspecialchars($sys_note['notice_text']); ?></span>
                </div>
            </div>

            <style>
                .system-notification-bar {
                    background: #ffc107;
                    /* Yellow Warning Color */
                    color: #212529;
                    text-align: center;
                    padding: 10px 0;
                    font-weight: 700;
                    font-family: 'Segoe UI', sans-serif;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    position: sticky;
                    top: 0;
                    z-index: 10000;
                    border-bottom: 2px solid #e0a800;
                }

                /* Pulse Animation Class (needs animate.css loaded) */
                .animate__swing {
                    --animate-duration: 2s;
                }
            </style>
<?php
        }
    }
}
?>