-- ========================================================
-- TRICORE DATA MEDIA - MySQL Database Export
-- Import langsung ke phpMyAdmin (cPanel, ProFreeHost, ByetHost, dll)
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'partner',
  `phone` varchar(50) DEFAULT NULL,
  `mitra_name` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `phone`, `mitra_name`) VALUES
(1, 'Admin TriCore', 'admin@tricoredatamedia.net', NULL, '$2y$12$ccy9ZY8Fpj.zptpF9itNA.FyEwyA96Www.tOwSnsG1GDt8sycSBtK', NULL, '2026-09-29 06:08:50', '2026-09-29 06:08:50', 'admin', '082138413292', 'Kantor Pusat Purwokerto'),
(2, 'Budi Santoso (Mitra Sokaraja)', 'mitra@tricoredatamedia.net', NULL, '$2y$12$pz3snjDtS.Ay57NmGE6YdusIcWFS/Xnhk4TPz3BN96zVF6DaWD/OS', NULL, '2026-09-29 06:08:51', '2026-09-29 06:08:51', 'mitra', '081234567890', 'Mitra WiFi Sokaraja Indah');

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `speed_mbps` int(10) UNSIGNED NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` json DEFAULT NULL,
  `device_recommendation` varchar(255) DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `packages` (`id`, `name`, `speed_mbps`, `price`, `badge`, `description`, `features`, `device_recommendation`, `is_popular`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Paket Hemat 15 Mbps', 15, 115000, NULL, 'Ideal untuk penggunaan harian ringan, browsing media sosial, chatting WhatsApp, dan sekolah online.', '[\"Kecepatan hingga 15 Mbps Simetris\",\"100% Kabel Fiber Optic Murni\",\"Unlimited Kuota (Tanpa FUP)\",\"Gratis Sewa Modem Wi-Fi Fiber\",\"Layanan Bantuan & Support 24\\/7\",\"Cocok untuk 1 - 3 Perangkat Sekaligus\"]', '1 - 3 Perangkat', 0, 1, 1, '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(2, 'Paket Family 20 Mbps', 20, 135000, NULL, 'Pilihan tepat untuk keluarga kecil dengan aktivitas streaming video HD dan bekerja dari rumah.', '[\"Kecepatan hingga 20 Mbps Simetris\",\"100% Kabel Fiber Optic Murni\",\"Unlimited Kuota (Tanpa FUP)\",\"Gratis Sewa Modem Wi-Fi Dual Band\",\"Layanan Bantuan & Support 24\\/7\",\"Cocok untuk 3 - 5 Perangkat Sekaligus\"]', '3 - 5 Perangkat', 0, 1, 2, '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(3, 'Paket Favorit 25 Mbps', 25, 150000, 'Paling Diminati', 'Paket paling banyak dipilih warga Purwokerto! Streaming lancar 4K UHD, game online bebas lag, meeting Zoom lancar.', '[\"Kecepatan hingga 25 Mbps Simetris\",\"100% Kabel Fiber Optic Murni\",\"Unlimited Kuota (Tanpa FUP)\",\"Gratis Sewa Modem Wi-Fi AC Dual Band Gigabit\",\"Layanan Bantuan & Support Prioritas 24\\/7\",\"Latency Rendah Khusus Gaming\",\"Cocok untuk 5 - 8 Perangkat\"]', '5 - 8 Perangkat', 1, 1, 3, '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(4, 'Paket Turbo 35 Mbps', 35, 170000, 'Kecepatan Tinggi', 'Performa kencang untuk rumah besar, konten kreator, live streaming tanpa buffering sedikitpun.', '[\"Kecepatan hingga 35 Mbps Simetris\",\"100% Kabel Fiber Optic Murni\",\"Unlimited Kuota (Tanpa FUP)\",\"Gratis Modem Wi-Fi Canggih Jangkauan Luas\",\"Layanan Bantuan & Support 24\\/7 Siaga\",\"Optimal untuk Upload Konten & Video HD\",\"Cocok untuk 8 - 12 Perangkat\"]', '8 - 12 Perangkat', 0, 1, 4, '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(5, 'Paket Ultimate 50 Mbps', 50, 200000, 'Pro & Bisnis', 'Solusi internet kelas bisnis untuk kantor, cafe, kos-kosan, dan pengguna profesional dengan transfer data intensif.', '[\"Kecepatan hingga 50 Mbps Simetris Real-Time\",\"100% Kabel Fiber Optic Murni Dedicated\",\"Unlimited Kuota (Tanpa FUP)\",\"Gratis Router High-Performance Multi-User\",\"SLA Jaminan Uptime 99.5% & Teknisi VIP 24\\/7\",\"Sangat Cocok untuk Cafe, Kantor & Kosan (12+ Perangkat)\"]', '12+ Perangkat / Bisnis', 0, 1, 5, '2026-09-29 06:08:51', '2026-09-29 06:08:51');

DROP TABLE IF EXISTS `coverage_areas`;
CREATE TABLE `coverage_areas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Purwokerto',
  `description` text DEFAULT NULL,
  `subdistricts` json DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Tersedia Fiber Optic',
  `total_odp` int(10) UNSIGNED NOT NULL DEFAULT 50,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `coverage_areas` (`id`, `name`, `city`, `description`, `subdistricts`, `status`, `total_odp`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Purwokerto Timur', 'Purwokerto', 'Jaringan fiber optic terpasang merata di pusat Purwokerto Timur dengan kapasitas ODP prima.', '[\"Arcawinangun\",\"Kranji\",\"Mersi\",\"Purwokerto Lor\",\"Sokanegara\",\"Kranji Kulon\",\"Gelora Indah\"]', 'Tersedia 100% Fiber Optic', 88, 1, '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(2, 'Purwokerto Wetan', 'Purwokerto', 'Cakupan internet cepat untuk area pemukiman padat dan ruko usaha di sekitar Purwokerto Wetan.', '[\"Purwokerto Wetan\",\"Karangpucung\",\"Tanjung\",\"Teluk\",\"Mersi Wetan\"]', 'Tersedia 100% Fiber Optic', 64, 1, '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(3, 'Sokaraja', 'Banyumas / Purwokerto', 'Ekspansi jaringan fiber optik aktif di koridor utama dan perumahan wilayah Sokaraja.', '[\"Sokaraja Kidul\",\"Sokaraja Lor\",\"Sokaraja Kulon\",\"Sokaraja Wetan\",\"Banjaranyar\",\"Karangduren\",\"Lemberang\"]', 'Tersedia 100% Fiber Optic', 52, 1, '2026-09-29 06:08:51', '2026-09-29 06:08:51');

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `identity_number` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `district` varchar(255) NOT NULL DEFAULT 'Purwokerto Timur',
  `subdistrict` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','active','isolated','cancelled') NOT NULL DEFAULT 'pending',
  `installation_date` date DEFAULT NULL,
  `odp_code` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `registered_by` varchar(255) NOT NULL DEFAULT 'online',
  `partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_customer_code_unique` (`customer_code`),
  KEY `customers_package_id_foreign` (`package_id`),
  KEY `customers_partner_id_foreign` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` (`id`, `customer_code`, `name`, `phone`, `email`, `identity_number`, `address`, `district`, `subdistrict`, `postal_code`, `package_id`, `status`, `installation_date`, `odp_code`, `ip_address`, `registered_by`, `partner_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'TDM-2601', 'Rizky Pratama', '082199887766', 'rizky.pratama@gmail.com', '3302101234560001', 'Jl. KAV. Gelora Indah II No. 14, RT 02 / RW 04', 'Purwokerto Timur', 'Arcawinangun', '53113', 3, 'active', '2026-08-01 00:00:00', 'ODP-PKT-012', '10.20.14.55', 'online', 2, 'Pemasangan lancar di tiang ODP depan rumah.', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(2, 'TDM-2602', 'Siti Nurhaliza', '085711223344', 'siti.nurhaliza@yahoo.com', '3302102345670002', 'Gg. Renang No. 8B, RT 01 / RW 03', 'Purwokerto Timur', 'Mersi', '53112', 2, 'active', '2026-08-15 00:00:00', 'ODP-PKT-018', '10.20.14.89', 'mitra', 2, 'Paket keluarga 20 Mbps.', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(3, 'TDM-2603', 'Agus Setiawan (Kopi Sokaraja)', '081344556677', 'agus.kopisokaraja@gmail.com', '3302103456780003', 'Jl. Suparjo Rustam No. 88, Ruko Kopi Sokaraja', 'Sokaraja', 'Sokaraja Kulon', '53181', 5, 'active', '2026-07-20 00:00:00', 'ODP-SKR-005', '10.30.10.12', 'mitra', 2, 'Usaha Kafe Kopi Sokaraja (Paket Bisnis 50 Mbps).', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(4, 'TDM-2604', 'Dwi Wahyuni', '087811992288', 'dwi.wahyuni@gmail.com', '3302104567890004', 'Perumahan Griya Satria Mandalatama Blok C-12', 'Purwokerto Wetan', 'Purwokerto Wetan', '53111', 1, 'isolated', '2026-06-10 00:00:00', 'ODP-PKW-021', '10.25.08.33', 'online', NULL, 'Menunggu pelunasan tagihan September 2026.', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(5, 'TDM-2605', 'Hendro Wijaya', '089622334455', 'hendro.w@gmail.com', '3302105678900005', 'Jl. Jenderal Soedirman Gg. Melati No. 4', 'Purwokerto Timur', 'Kranji', '53116', 4, 'pending', NULL, 'ODP-PKT-009', NULL, 'online', NULL, 'Pendaftaran baru lewat website, jadwal teknisi survey besok.', '2026-09-29 06:08:51', '2026-09-29 06:08:51');

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `billing_month` varchar(255) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('unpaid','paid','cancelled') NOT NULL DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `received_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_customer_id_foreign` (`customer_id`),
  KEY `invoices_package_id_foreign` (`package_id`),
  KEY `invoices_received_by_user_id_foreign` (`received_by_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_id`, `package_id`, `billing_month`, `period_start`, `period_end`, `due_date`, `amount`, `status`, `paid_at`, `payment_method`, `payment_reference`, `received_by_user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'INV-202609-001', 1, 3, 'September 2026', '2026-09-01 00:00:00', '2026-09-30 00:00:00', '2026-09-10 00:00:00', 150000, 'paid', '2026-09-05 14:30:00', 'Transfer Bank BCA', 'TRF-BCA-98124', 2, 'Pembayaran via m-BCA lunas.', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(2, 'INV-202609-002', 2, 2, 'September 2026', '2026-09-01 00:00:00', '2026-09-30 00:00:00', '2026-09-10 00:00:00', 135000, 'paid', '2026-09-05 14:30:00', 'Transfer Bank BCA', 'TRF-BCA-98124', 2, 'Pembayaran via m-BCA lunas.', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(3, 'INV-202609-003', 3, 5, 'September 2026', '2026-09-01 00:00:00', '2026-09-30 00:00:00', '2026-09-10 00:00:00', 200000, 'paid', '2026-09-05 14:30:00', 'Transfer Bank BCA', 'TRF-BCA-98124', 2, 'Pembayaran via m-BCA lunas.', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(4, 'INV-202609-004', 4, 1, 'September 2026', '2026-09-01 00:00:00', '2026-09-30 00:00:00', '2026-09-10 00:00:00', 115000, 'unpaid', NULL, NULL, NULL, NULL, 'Tagihan terlewat jatuh tempo. Layanan terisolir sementara sampai konfirmasi pembayaran.', '2026-09-29 06:08:51', '2026-09-29 06:08:51'),
(5, 'INV-202609-005', 5, 4, 'Tagihan Awal Pemasangan (September 2026)', '2026-09-29 00:00:00', '2026-10-28 00:00:00', '2026-10-05 00:00:00', 170000, 'unpaid', NULL, NULL, NULL, NULL, 'Tagihan bulan pertama paket baru.', '2026-09-29 06:08:51', '2026-09-29 06:08:51');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('oV6JquH65zHLCFQ4xuZ9fHv5QeMiMYmQq8j4vfWx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'eyJfdG9rZW4iOiJ2c3VyTktIdmZEeTlCYzUycDVGU1hBaVNHbUxnVG1zT0daT0dNYVdFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1790662284),
('5aq7Fv1WIrEtHQPTLe5Uyhno4y3XvfuVY60LTovD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'eyJfdG9rZW4iOiI4RnJ0ZlBobkNKOFJZSjFhemR3NnFQeVFRc2I2aVdQSkhRNzhab0dCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1790662299),
('IhhdiTVg16mWvxehHHHHlxJSAx0uxSA29R2fzBCm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'eyJfdG9rZW4iOiJtR2FhcG94QjlLTTJEeGV1ZlZTOUxwWVlwWE1sdzUxc0tDcDhvSnJNIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1790662680);

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
