ALTER TABLE users ADD theme ENUM('light', 'dark') DEFAULT 'light';

ALTER TABLE meter_removal ADD INDEX (acc_no);
ALTER TABLE meter_removal ADD INDEX (status);

ALTER TABLE meter_removal ADD INDEX idx_m_status (status);
ALTER TABLE meter_removal ADD INDEX idx_m_acc (acc_no);
ALTER TABLE meter_removal ADD INDEX idx_m_job (job_no);