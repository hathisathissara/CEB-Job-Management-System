

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_logs`
--

-- --------------------------------------------------------

--
-- Table structure for table `meter_removal`
--

DROP TABLE IF EXISTS `meter_removal`;
CREATE TABLE IF NOT EXISTS `meter_removal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_no` varchar(50) NOT NULL,
  `acc_no` varchar(50) NOT NULL,
  `meter_no` varchar(50) DEFAULT NULL,
  `meter_reading` varchar(50) DEFAULT NULL,
  `removing_date` date DEFAULT NULL,
  `done_by` varchar(100) DEFAULT NULL,
  `officer_note` text,
  `status` enum('Pending','Removed','Returned - Paid','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `acc_no` (`acc_no`),
  KEY `status` (`status`),
  KEY `acc_no_2` (`acc_no`),
  KEY `status_2` (`status`),
  KEY `idx_m_status` (`status`),
  KEY `idx_m_acc` (`acc_no`),
  KEY `idx_m_job` (`job_no`)
) ENGINE=MyISAM AUTO_INCREMENT=277 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('Super Admin','Officer') NOT NULL DEFAULT 'Officer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_token` varchar(100) DEFAULT NULL,
  `theme` enum('light','dark') DEFAULT 'light',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`, `created_at`, `remember_token`, `last_login_token`, `theme`) VALUES
(1, 'admin', '$2y$10$2oiuFG6QR0sjqWC53HOk9eAfLHsnJkp.dV76GBcz10gZTqi9GRPem', 'Electrical Superintendent', 'Super Admin', '2025-12-12 14:27:31', NULL, '', 'dark');
COMMIT;

-- username: admin
-- password: ceb123 
-- this is hashed using bcrypt for test purpose