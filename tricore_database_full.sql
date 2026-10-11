-- ================================================================
-- TRICORE DATA MEDIA - Layanan WiFi
-- SQL Database Lengkap untuk phpMyAdmin InfinityFree
-- Database: if0_43041974_wifi
-- ================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+07:00";
SET NAMES utf8mb4;

-- ================================================================
-- TABEL 1: migrations (Laravel migration tracker)
-- ================================================================
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_09_29_055059_add_role_to_users_table', 1),
('2026_09_29_055100_create_packages_table', 1),
('2026_09_29_055101_create_coverage_areas_table', 1),
('2026_09_29_055102_create_customers_table', 1),
('2026_09_29_055103_create_invoices_table', 1);

-- ================================================================
-- TABEL 2: users (Admin & Mitra)
-- ================================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'mitra',
  `phone` varchar(255) DEFAULT NULL,
  `mitra_name` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `mitra_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin TriCore', 'admin@tricoredatamedia.net', '$2y$12$Qx1d6uqQGfwc/NOdeDLV4erg1NJgcVOGCuT5bBm9etgu.4aNQRUlO', 'admin', '082138413292', 'Kantor Pusat Purwokerto', NOW(), NOW()),
(2, 'Budi Santoso (Mitra Sokaraja)', 'mitra@tricoredatamedia.net', '$2y$12$Qx1d6uqQGfwc/NOdeDLV4erg1NJgcVOGCuT5bBm9etgu.4aNQRUlO', 'mitra', '081234567890', 'Mitra WiFi Sokaraja Indah', NOW(), NOW());

-- ================================================================
-- TABEL 3: password_reset_tokens
-- ================================================================
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- TABEL 4: sessions
-- ================================================================
CREATE TABLE IF NOT EXISTS `sessions` (
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

-- ================================================================
-- TABEL 5: cache
-- ================================================================
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- TABEL 6: jobs, job_batches, failed_jobs
-- ================================================================
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- TABEL 7: packages (Paket Internet TRICORE)
-- ================================================================
CREATE TABLE IF NOT EXISTS `packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `speed_mbps` int(10) UNSIGNED NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` longtext DEFAULT NULL,
  `device_recommendation` varchar(255) DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `packages` (`id`, `name`, `speed_mbps`, `price`, `badge`, `description`, `features`, `device_recommendation`, `is_popular`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Paket Hemat 15 Mbps', 15, 115000.00, NULL, 'Ideal untuk penggunaan harian ringan, browsing media sosial, chatting WhatsApp, dan sekolah online.', '["Kecepatan hingga 15 Mbps Simetris","100% Kabel Fiber Optic Murni","Unlimited Kuota (Tanpa FUP)","Gratis Sewa Modem Wi-Fi Fiber","Layanan Bantuan & Support 24/7","Cocok untuk 1 - 3 Perangkat Sekaligus"]', '1 - 3 Perangkat', 0, 1, 1, NOW(), NOW()),
(2, 'Paket Family 20 Mbps', 20, 135000.00, NULL, 'Pilihan tepat untuk keluarga kecil dengan aktivitas streaming video HD dan bekerja dari rumah.', '["Kecepatan hingga 20 Mbps Simetris","100% Kabel Fiber Optic Murni","Unlimited Kuota (Tanpa FUP)","Gratis Sewa Modem Wi-Fi Dual Band","Layanan Bantuan & Support 24/7","Cocok untuk 3 - 5 Perangkat Sekaligus"]', '3 - 5 Perangkat', 0, 1, 2, NOW(), NOW()),
(3, 'Paket Favorit 25 Mbps', 25, 150000.00, 'Paling Diminati', 'Paket paling banyak dipilih warga Purwokerto! Streaming lancar 4K UHD, game online bebas lag, meeting Zoom lancar.', '["Kecepatan hingga 25 Mbps Simetris","100% Kabel Fiber Optic Murni","Unlimited Kuota (Tanpa FUP)","Gratis Sewa Modem Wi-Fi AC Dual Band Gigabit","Layanan Bantuan & Support Prioritas 24/7","Latency Rendah Khusus Gaming","Cocok untuk 5 - 8 Perangkat"]', '5 - 8 Perangkat', 1, 1, 3, NOW(), NOW()),
(4, 'Paket Turbo 35 Mbps', 35, 170000.00, 'Kecepatan Tinggi', 'Performa kencang untuk rumah besar, konten kreator, live streaming tanpa buffering sedikitpun.', '["Kecepatan hingga 35 Mbps Simetris","100% Kabel Fiber Optic Murni","Unlimited Kuota (Tanpa FUP)","Gratis Modem Wi-Fi Canggih Jangkauan Luas","Layanan Bantuan & Support 24/7 Siaga","Optimal untuk Upload Konten & Video HD","Cocok untuk 8 - 12 Perangkat"]', '8 - 12 Perangkat', 0, 1, 4, NOW(), NOW()),
(5, 'Paket Ultimate 50 Mbps', 50, 200000.00, 'Pro & Bisnis', 'Solusi internet kelas bisnis untuk kantor, cafe, kos-kosan, dan pengguna profesional dengan transfer data intensif.', '["Kecepatan hingga 50 Mbps Simetris Real-Time","100% Kabel Fiber Optic Murni Dedicated","Unlimited Kuota (Tanpa FUP)","Gratis Router High-Performance Multi-User","SLA Jaminan Uptime 99.5% & Teknisi VIP 24/7","Sangat Cocok untuk Cafe, Kantor & Kosan (12+ Perangkat)"]', '12+ Perangkat / Bisnis', 0, 1, 5, NOW(), NOW());

-- ================================================================
-- TABEL 8: coverage_areas (Area Jangkauan TRICORE)
-- ================================================================
CREATE TABLE IF NOT EXISTS `coverage_areas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Purwokerto',
  `description` text DEFAULT NULL,
  `subdistricts` longtext DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Tersedia Fiber Optic',
  `total_odp` int(10) UNSIGNED NOT NULL DEFAULT 50,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `coverage_areas` (`id`, `name`, `city`, `description`, `subdistricts`, `status`, `total_odp`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Purwokerto Timur', 'Purwokerto', 'Jaringan fiber optic terpasang merata di pusat Purwokerto Timur dengan kapasitas ODP prima.', '["Arcawinangun","Kranji","Mersi","Purwokerto Lor","Sokanegara","Kranji Kulon","Gelora Indah"]', 'Tersedia 100% Fiber Optic', 88, 1, NOW(), NOW()),
(2, 'Purwokerto Wetan', 'Purwokerto', 'Cakupan internet cepat untuk area pemukiman padat dan ruko usaha di sekitar Purwokerto Wetan.', '["Purwokerto Wetan","Karangpucung","Tanjung","Teluk","Mersi Wetan"]', 'Tersedia 100% Fiber Optic', 64, 1, NOW(), NOW()),
(3, 'Sokaraja', 'Banyumas / Purwokerto', 'Ekspansi jaringan fiber optik aktif di koridor utama dan perumahan wilayah Sokaraja.', '["Sokaraja Kidul","Sokaraja Lor","Sokaraja Kulon","Sokaraja Wetan","Banjaranyar","Karangduren","Lemberang"]', 'Tersedia 100% Fiber Optic', 52, 1, NOW(), NOW());

-- ================================================================
-- TABEL 9: customers (Pelanggan TRICORE)
-- ================================================================
CREATE TABLE IF NOT EXISTS `customers` (
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
  KEY `customers_partner_id_foreign` (`partner_id`),
  CONSTRAINT `customers_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customers_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` (`id`, `customer_code`, `name`, `phone`, `email`, `identity_number`, `address`, `district`, `subdistrict`, `postal_code`, `package_id`, `status`, `installation_date`, `odp_code`, `ip_address`, `registered_by`, `partner_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'TDM-2601', 'Rizky Pratama', '082199887766', 'rizky.pratama@gmail.com', '3302101234560001', 'Jl. KAV. Gelora Indah II No. 14, RT 02 / RW 04', 'Purwokerto Timur', 'Arcawinangun', '53113', 3, 'active', '2026-08-01', 'ODP-PKT-012', '10.20.14.55', 'online', 2, 'Pemasangan lancar di tiang ODP depan rumah.', NOW(), NOW()),
(2, 'TDM-2602', 'Siti Nurhaliza', '085711223344', 'siti.nurhaliza@yahoo.com', '3302102345670002', 'Gg. Renang No. 8B, RT 01 / RW 03', 'Purwokerto Timur', 'Mersi', '53112', 2, 'active', '2026-08-15', 'ODP-PKT-018', '10.20.14.89', 'mitra', 2, 'Paket keluarga 20 Mbps.', NOW(), NOW()),
(3, 'TDM-2603', 'Agus Setiawan (Kopi Sokaraja)', '081344556677', 'agus.kopisokaraja@gmail.com', '3302103456780003', 'Jl. Suparjo Rustam No. 88, Ruko Kopi Sokaraja', 'Sokaraja', 'Sokaraja Kulon', '53181', 5, 'active', '2026-07-20', 'ODP-SKR-005', '10.30.10.12', 'mitra', 2, 'Usaha Kafe Kopi Sokaraja (Paket Bisnis 50 Mbps).', NOW(), NOW()),
(4, 'TDM-2604', 'Dwi Wahyuni', '087811992288', 'dwi.wahyuni@gmail.com', '3302104567890004', 'Perumahan Griya Satria Mandalatama Blok C-12', 'Purwokerto Wetan', 'Purwokerto Wetan', '53111', 1, 'isolated', '2026-06-10', 'ODP-PKW-021', '10.25.08.33', 'online', NULL, 'Menunggu pelunasan tagihan September 2026.', NOW(), NOW()),
(5, 'TDM-2605', 'Hendro Wijaya', '089622334455', 'hendro.w@gmail.com', '3302105678900005', 'Jl. Jenderal Soedirman Gg. Melati No. 4', 'Purwokerto Timur', 'Kranji', '53116', 4, 'pending', NULL, 'ODP-PKT-009', NULL, 'online', NULL, 'Pendaftaran baru lewat website, jadwal teknisi survey besok.', NOW(), NOW());

-- ================================================================
-- TABEL 10: invoices (Tagihan Pelanggan)
-- ================================================================
CREATE TABLE IF NOT EXISTS `invoices` (
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
  KEY `invoices_received_by_user_id_foreign` (`received_by_user_id`),
  CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_received_by_user_id_foreign` FOREIGN KEY (`received_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_id`, `package_id`, `billing_month`, `period_start`, `period_end`, `due_date`, `amount`, `status`, `paid_at`, `payment_method`, `payment_reference`, `received_by_user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'INV-202609-001', 1, 3, 'September 2026', '2026-09-01', '2026-09-30', '2026-09-10', 150000.00, 'paid', '2026-09-05 14:30:00', 'Transfer Bank BCA', 'TRF-BCA-98124', 2, 'Pembayaran via m-BCA lunas.', NOW(), NOW()),
(2, 'INV-202609-002', 2, 2, 'September 2026', '2026-09-01', '2026-09-30', '2026-09-10', 135000.00, 'paid', '2026-09-05 14:30:00', 'Transfer Bank BCA', 'TRF-BCA-98124', 2, 'Pembayaran via m-BCA lunas.', NOW(), NOW()),
(3, 'INV-202609-003', 3, 5, 'September 2026', '2026-09-01', '2026-09-30', '2026-09-10', 200000.00, 'paid', '2026-09-05 14:30:00', 'Transfer Bank BCA', 'TRF-BCA-98124', 2, 'Pembayaran via m-BCA lunas.', NOW(), NOW()),
(4, 'INV-202609-004', 4, 1, 'September 2026', '2026-09-01', '2026-09-30', '2026-09-10', 115000.00, 'unpaid', NULL, NULL, NULL, NULL, 'Tagihan terlewat jatuh tempo. Layanan terisolir sementara sampai konfirmasi pembayaran.', NOW(), NOW()),
(5, 'INV-202609-005', 5, 4, 'Tagihan Awal Pemasangan (September 2026)', '2026-09-29', '2026-10-28', '2026-10-05', 170000.00, 'unpaid', NULL, NULL, NULL, NULL, 'Tagihan bulan pertama paket baru.', NOW(), NOW());

COMMIT;

-- ================================================================
-- SELESAI! Semua tabel dan data TRICORE DATA MEDIA sudah terisi.
-- Total: 10+ tabel, 2 user, 5 paket, 3 area, 5 pelanggan, 5 tagihan
-- Password admin & mitra: password123
-- ================================================================
