-- 1. DROP ALL OLD TABLES (අලුත් කරන විට පැරණි ඒවා ගැටීමට ඇති ඉඩ නැවැත්වීම)
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `company_events`;
DROP TABLE IF EXISTS `system_settings`;
DROP TABLE IF EXISTS `new_connections`;
DROP TABLE IF EXISTS `meter_change`;
DROP TABLE IF EXISTS `meter_removal`;
DROP TABLE IF EXISTS `users`;

-- ==============================================
-- 2. USERS (STAFF & ADMIN) TABLE
-- ==============================================
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(150) NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` ENUM('Super Admin', 'Officer') NOT NULL DEFAULT 'Officer',
  `is_active` TINYINT(1) DEFAULT 0,
  `otp_code` VARCHAR(10) NULL,
  `otp_expiry` DATETIME NULL,
  `theme` ENUM('light', 'dark') DEFAULT 'dark',
  `remember_token` VARCHAR(100) NULL,
  `last_login_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==============================================
-- 3. METER REMOVAL TABLE
-- ==============================================
CREATE TABLE `meter_removal` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_no` VARCHAR(50) NOT NULL UNIQUE,
  `acc_no` VARCHAR(50) NOT NULL,
  `meter_no` VARCHAR(50) NULL,
  `phase_type` ENUM('Single Phase', 'Three Phase') DEFAULT 'Single Phase',
  `status` ENUM('Pending', 'Removed', 'Returned - Paid', 'Cancelled') DEFAULT 'Pending',
  `meter_reading` VARCHAR(50) NULL,
  `removing_date` DATE NULL,
  `done_by` VARCHAR(100) NULL,
  `officer_note` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================
-- 4. METER CHANGE TABLE
-- ==============================================
CREATE TABLE `meter_change` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_no` VARCHAR(50) NOT NULL UNIQUE,
  `acc_no` VARCHAR(50) NOT NULL,
  `old_meter_no` VARCHAR(50) NOT NULL,
  `phase_type` ENUM('Single Phase', 'Three Phase') NOT NULL,
  `status` ENUM('Pending', 'Completed', 'Cancelled') DEFAULT 'Pending',
  `old_reading` VARCHAR(50) NULL,
  `new_meter_no` VARCHAR(50) NULL,
  `new_reading` VARCHAR(50) NULL,
  `done_by` VARCHAR(100) NULL,
  `done_date` DATE NULL,
  `officer_note` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================
-- 5. NEW CONNECTIONS (SERVICES) TABLE
-- ==============================================
CREATE TABLE `new_connections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `app_no` VARCHAR(100) NOT NULL UNIQUE,
  `customer_name` VARCHAR(255) NOT NULL,
  `id_number` VARCHAR(20) NOT NULL,
  `service_type` ENUM('Normal', '3 Phase', 'Augmentation', 'Over 100k') DEFAULT 'Normal',
  `address` TEXT NULL,
  `est_no` VARCHAR(100) NULL,
  `job_no` VARCHAR(100) NULL,
  `status` ENUM('Registered', 'Shortcoming', 'Estimated', 'Pending Approval', 'Approved', 'Job Created', 'Completed') DEFAULT 'Registered',
  `sent_date` DATE NULL,
  `approved_date` DATE NULL,
  `completed_date` DATE NULL,
  `officer_note` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================
-- 6. SYSTEM AUDIT & ACTIVITY LOGS TABLE
-- ==============================================
CREATE TABLE `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_name` VARCHAR(100) NOT NULL,
  `action_type` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `log_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================
-- 7. SYSTEM SETTINGS & NOTIFICATIONS TABLE
-- ==============================================
CREATE TABLE `system_settings` (
  `id` INT PRIMARY KEY,
  `notice_text` VARCHAR(255) DEFAULT 'System maintenance scheduled.',
  `is_active` TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- එකතු කරන මූලික නිවේදනය (Off තත්ත්වයේ)
INSERT INTO `system_settings` (`id`, `notice_text`, `is_active`) VALUES (1, 'EDL Database upgrade is successfully integrated.', 0);

-- ==============================================
-- 8. CSR / EVENTS & NEWS POSTS TABLE
-- ==============================================
CREATE TABLE `company_events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` VARCHAR(50) NOT NULL, 
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `posted_by` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- පද්ධතිය වඩාත් වේගවත් (Optimized) කිරීමට අත්‍යවශ්‍ය දර්ශක (Indexing Keys)
ALTER TABLE `meter_removal` ADD INDEX(`acc_no`);
ALTER TABLE `meter_change` ADD INDEX(`acc_no`);
ALTER TABLE `new_connections` ADD INDEX(`app_no`);
ALTER TABLE `new_connections` ADD INDEX(`est_no`);