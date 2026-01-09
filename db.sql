

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `year_digits` varchar(5) NOT NULL,
  `serial_no` int NOT NULL,
  `complaint_ref_no` varchar(30) NOT NULL,
  `area_code` varchar(5) NOT NULL,
  `date_of_complaint` date NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `tel_1` varchar(15) NOT NULL,
  `tel_2` varchar(15) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `ceb_account_number` varchar(50) DEFAULT NULL,
  `complaint_text` text NOT NULL,
  `letter_image` varchar(255) NOT NULL,
  `who_checked` varchar(100) DEFAULT NULL,
  `date_of_inspection` date DEFAULT NULL,
  `task_to_perform` text,
  `estimate_no` varchar(50) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `year_digits`, `serial_no`, `complaint_ref_no`, `area_code`, `date_of_complaint`, `customer_name`, `address`, `tel_1`, `tel_2`, `nic`, `ceb_account_number`, `complaint_text`, `letter_image`, `who_checked`, `date_of_inspection`, `task_to_perform`, `estimate_no`, `status`, `updated_by`, `created_at`) VALUES
(5, '25', 2, '25/BT-2', 'BT', '2025-12-12', 'hathisa Thissara', '108,seelathanna ,haldummulla', '3748734873', '', '', '', 'test', 'uploads/1765547413_Screenshot (17).png', 'kamal', '2025-12-12', 'done', '', 'Completed', 'Officer hathisa (2025-12-12 14:36)', '2025-12-12 13:50:13'),
(4, '25', 1, '25/BT-1', 'BT', '2025-12-12', 'hathisa Thissara', '108,seelathanna ,haldummulla', '3748734873', '', '', '', 'test', 'uploads/1765547194_Screenshot (17).png', '', NULL, '', '', 'In Progress', 'Officer hathisa (2025-12-15 14:27)', '2025-12-12 13:46:34'),
(9, '25', 4, '25/WR-4', 'WR', '2025-12-01', 'hathisa Thissara', '108,seelathanna ,haldummulla', '0701207991', '', '', '', 'test for red alert', 'uploads/1765808331_2021_08_18_21_10_24F2AF3A-BFB9-44DB-9497-4B2B803F495F.PNG', NULL, NULL, NULL, NULL, 'Pending', NULL, '2025-12-15 14:18:51'),
(8, '25', 3, '25/PR-3', 'PR', '2025-12-12', 'hathisa Thissara', '108,seelathanna ,haldummulla', '0701207991', '', '', '', 'test complain', 'uploads/1765552952_Gemini_Generated_Image_fms8ebfms8ebfms8.png', NULL, NULL, NULL, NULL, 'Pending', NULL, '2025-12-12 15:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `created_at`) VALUES
(1, 'admin', '$2y$10$2oiuFG6QR0sjqWC53HOk9eAfLHsnJkp.dV76GBcz10gZTqi9GRPem', 'Electrical Superintendent', '2025-12-12 14:27:31'),
(2, 'hathisa', '$2y$10$vVp0YBfX3vYw9mLKkWZRduox6CxOjbLoyjD4UNoBHl1PgEJb0bir2', 'hathisa thissara', '2025-12-12 14:28:03');
