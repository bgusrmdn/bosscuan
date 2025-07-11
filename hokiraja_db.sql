-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Jul 2025 pada 19.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hokiraja_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `last_seen` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `last_seen`, `created_at`) VALUES
(1, 'superadmin', '$2y$10$m.eSJr9QjP.TQhG6y/NtLeYCu3LrZqaxF9aAIgT8J3tir3QLPXmUm', 'Admin Utama', '2025-07-05 17:12:18', '2025-07-01 13:46:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_deposit_accounts`
--

CREATE TABLE `admin_deposit_accounts` (
  `id` int(11) NOT NULL,
  `method_code` varchar(20) NOT NULL COMMENT 'Kode metode (BCA, DANA, dll) dari payment_methods',
  `account_name` varchar(100) NOT NULL COMMENT 'Nama pemilik rekening admin',
  `account_number` varchar(50) NOT NULL COMMENT 'Nomor rekening/ID e-wallet admin',
  `min_deposit` decimal(15,2) NOT NULL DEFAULT 10000.00,
  `max_deposit` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `qris_image_url` varchar(255) DEFAULT NULL COMMENT 'URL gambar QRIS jika akun ini untuk QRIS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_deposit_accounts`
--

INSERT INTO `admin_deposit_accounts` (`id`, `method_code`, `account_name`, `account_number`, `min_deposit`, `max_deposit`, `is_active`, `created_at`, `updated_at`, `qris_image_url`) VALUES
(6, 'QRIS_STATIC', 'QRIS HOKIRAJA', 'N/A', 10000.00, 5000000.00, 1, '2025-07-05 15:20:16', '2025-07-05 15:21:43', 'qris_1751728903.png'),
(7, 'DANA', 'BAGUS RAMADHAN', '081217008016', 10000.00, 50000000.00, 1, '2025-07-05 17:38:22', '2025-07-05 17:38:22', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bank_status`
--

CREATE TABLE `bank_status` (
  `id` int(11) NOT NULL,
  `method_code` varchar(20) NOT NULL COMMENT 'Kode metode pembayaran, sama seperti di payment_methods',
  `status` enum('online','offline') NOT NULL DEFAULT 'online',
  `logo_url` varchar(255) DEFAULT NULL COMMENT 'URL/nama file logo bank',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bank_status`
--

INSERT INTO `bank_status` (`id`, `method_code`, `status`, `logo_url`, `last_updated`) VALUES
(1, 'BCA', 'online', 'bca_logo.png', '2025-07-04 12:13:28'),
(2, 'MANDIRI', 'online', 'mandiri_logo.png', '2025-07-04 12:13:28'),
(3, 'BRI', 'online', 'bri_logo.png', '2025-07-04 12:13:28'),
(4, 'BNI', 'online', 'bni_logo.png', '2025-07-04 12:13:28'),
(5, 'DANA', 'online', 'dana_logo.png', '2025-07-04 12:13:28'),
(6, 'OVO', 'offline', 'ovo_logo.png', '2025-07-04 12:13:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bonuses`
--

CREATE TABLE `bonuses` (
  `id` int(11) NOT NULL,
  `bonus_name` varchar(100) NOT NULL,
  `bonus_code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `min_deposit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `max_bonus_amount` decimal(15,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL COMMENT 'e.g., 5.00 for 5%',
  `turnover_multiplier` decimal(5,2) NOT NULL DEFAULT 1.00 COMMENT 'e.g., 3.00 for 3x TO',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bonuses`
--

INSERT INTO `bonuses` (`id`, `bonus_name`, `bonus_code`, `description`, `min_deposit`, `max_bonus_amount`, `percentage`, `turnover_multiplier`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tanpa Bonus', 'NONE', 'Tidak mengambil bonus apapun.', 0.00, NULL, NULL, 1.00, 1, '2025-07-04 12:10:45', '2025-07-04 12:10:45'),
(2, 'Bonus New Member 100%', 'NEW100', 'Bonus untuk member baru, 100% dari deposit pertama.', 50000.00, 500000.00, 100.00, 3.00, 1, '2025-07-04 12:10:45', '2025-07-04 12:10:45'),
(3, 'Bonus Harian 5%', 'DAILY5', 'Bonus deposit harian 5%.', 20000.00, 200000.00, 5.00, 1.00, 1, '2025-07-04 12:10:45', '2025-07-04 12:10:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon_class` varchar(50) DEFAULT 'fas fa-gamepad',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `icon_class`, `sort_order`, `is_active`) VALUES
(42, 'Slot', '686544712a044_1751467121.svg', '', 1, 1),
(43, 'Togel', '68654c5705282_1751469143.svg', '', 2, 1),
(44, 'Casino', '68654c63a68e7_1751469155.svg', '', 3, 1),
(45, 'Sports', '68654c72694ea_1751469170.svg', '', 4, 1),
(46, 'Arcade', '68654c81816bd_1751469185.svg', '', 5, 1),
(47, 'Crash-Game', '68654c918c648_1751469201.svg', '', 6, 1),
(48, 'Poker', '68654ca21a725_1751469218.svg', '', 7, 1),
(49, 'E-Sports', '68654cae7f940_1751469230.svg', '', 8, 1),
(50, 'Sabung Ayam', '68654cbc856e8_1751469244.svg', '', 9, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit_transactions`
--

CREATE TABLE `deposit_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL COMMENT 'Opsional: link ke tabel transactions utama jika ada',
  `channel_type` enum('qris','bank_transfer') NOT NULL,
  `payment_method_code` varchar(50) NOT NULL COMMENT 'Kode bank/ewallet/pulsa atau QRIS provider',
  `amount` decimal(15,2) NOT NULL,
  `bonus_id` int(11) DEFAULT NULL COMMENT 'Link ke tabel bonuses',
  `proof_of_transfer_url` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','expired') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `qris_reference` varchar(255) DEFAULT NULL COMMENT 'Reference ID dari API QRIS',
  `qris_qrcode_url` varchar(255) DEFAULT NULL COMMENT 'URL QR Code yang dihasilkan API',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_deposit_account_id` int(11) DEFAULT NULL COMMENT 'ID akun deposit admin yang dipilih',
  `processed_at` datetime DEFAULT NULL COMMENT 'Waktu transaksi diproses oleh admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `deposit_transactions`
--

INSERT INTO `deposit_transactions` (`id`, `user_id`, `transaction_id`, `channel_type`, `payment_method_code`, `amount`, `bonus_id`, `proof_of_transfer_url`, `remark`, `status`, `admin_notes`, `qris_reference`, `qris_qrcode_url`, `created_at`, `updated_at`, `admin_deposit_account_id`, `processed_at`) VALUES
(6, 5, NULL, 'qris', 'QRIS_STATIC', 58888.00, 1, NULL, NULL, 'approved', NULL, NULL, NULL, '2025-07-04 14:55:22', '2025-07-04 14:59:29', NULL, '2025-07-04 21:59:29'),
(7, 11, NULL, 'qris', 'QRIS_STATIC', 3323232.00, 2, NULL, NULL, 'approved', NULL, NULL, NULL, '2025-07-05 14:07:20', '2025-07-05 14:07:52', NULL, '2025-07-05 21:07:52'),
(8, 5, NULL, 'qris', 'QRIS_STATIC', 35444.00, 1, NULL, NULL, 'approved', NULL, NULL, NULL, '2025-07-05 15:29:15', '2025-07-05 15:29:26', NULL, '2025-07-05 22:29:26'),
(9, 5, NULL, 'qris', 'QRIS_STATIC', 50000.00, 1, NULL, NULL, 'approved', NULL, NULL, NULL, '2025-07-05 15:32:03', '2025-07-05 15:32:16', NULL, '2025-07-05 22:32:16'),
(10, 5, NULL, 'qris', 'QRIS_STATIC', 4354545.00, 1, NULL, NULL, 'approved', NULL, NULL, NULL, '2025-07-05 15:35:57', '2025-07-05 15:36:13', NULL, '2025-07-05 22:36:13'),
(11, 5, NULL, 'qris', 'QRIS_STATIC', 2323232.00, 1, NULL, NULL, 'approved', NULL, NULL, NULL, '2025-07-05 15:50:17', '2025-07-05 16:00:07', NULL, '2025-07-05 23:00:07'),
(12, 5, NULL, 'qris', 'QRIS_STATIC', 3232323.00, 1, NULL, NULL, 'approved', NULL, NULL, NULL, '2025-07-05 16:16:56', '2025-07-05 16:17:42', NULL, '2025-07-05 23:17:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `nama_game` varchar(100) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `gambar_thumbnail` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `games`
--

INSERT INTO `games` (`id`, `nama_game`, `provider`, `kategori`, `gambar_thumbnail`, `is_featured`, `is_active`, `created_at`) VALUES
(3, '1. Gates Of Olympus Super Scatter', 'Pragmatic Play', 'Slot', '68668233936f8_1751548467_0.png', 0, 1, '2025-07-03 13:14:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `live_chat_messages`
--

CREATE TABLE `live_chat_messages` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender_type` enum('user','admin') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `live_chat_messages`
--

INSERT INTO `live_chat_messages` (`id`, `session_id`, `user_id`, `sender_type`, `message`, `is_read`, `timestamp`) VALUES
(8, 'kgrrcml5roj6re4vhv53ph3v2s', 5, 'user', 'test', 0, '2025-07-05 16:31:38'),
(9, 'kgrrcml5roj6re4vhv53ph3v2s', 5, 'user', 'test123', 0, '2025-07-05 16:38:13'),
(10, 'kgrrcml5roj6re4vhv53ph3v2s', NULL, 'user', 'test', 0, '2025-07-05 16:40:19'),
(11, '5tpd8vv61lg7fcnj702s1b9qvq', NULL, 'user', 'Pengguna baru memulai obrolan:\nNama: asep\nEmail: asepadmi@gmail.com\nKendala: deposit tidak masuk', 0, '2025-07-05 16:40:54'),
(12, '5tpd8vv61lg7fcnj702s1b9qvq', NULL, 'user', 'halo kka', 0, '2025-07-05 16:40:58'),
(13, '5tpd8vv61lg7fcnj702s1b9qvq', NULL, 'admin', 'iya kak', 0, '2025-07-05 16:50:37'),
(14, '5tpd8vv61lg7fcnj702s1b9qvq', NULL, 'user', 'ada apa ya kak ?', 0, '2025-07-05 16:50:45'),
(15, '5tpd8vv61lg7fcnj702s1b9qvq', NULL, 'admin', 'ga papa kak', 0, '2025-07-05 16:58:35'),
(16, 'u2no5tueldaeiigu4ac0nv63ik', NULL, 'user', 'Pengguna baru memulai obrolan:\nNama: test\nEmail: asdadwdwad@gmail.com\nKendala: hallo', 0, '2025-07-05 17:06:03'),
(17, 'u2no5tueldaeiigu4ac0nv63ik', 5, 'user', 'halo kak', 0, '2025-07-05 17:09:55'),
(18, 'u2no5tueldaeiigu4ac0nv63ik', NULL, 'admin', 'iya jalo', 0, '2025-07-05 17:11:55'),
(19, 'u2no5tueldaeiigu4ac0nv63ik', NULL, 'admin', 'ada yang bisa saya bantu kak ??', 0, '2025-07-05 17:11:59'),
(20, 'u2no5tueldaeiigu4ac0nv63ik', NULL, 'admin', 'bisa dibantu userid nya kak ?', 0, '2025-07-05 17:12:05'),
(21, 'kgrrcml5roj6re4vhv53ph3v2s', NULL, 'admin', 'iya kjk', 0, '2025-07-05 17:12:15'),
(22, 'kgrrcml5roj6re4vhv53ph3v2s', NULL, 'admin', 'ada yang bisa saya bantu ?', 0, '2025-07-05 17:12:18'),
(23, 'u2no5tueldaeiigu4ac0nv63ik', 5, 'user', 'userid : bgusrmdn', 0, '2025-07-05 17:12:56'),
(24, 'u2no5tueldaeiigu4ac0nv63ik', 5, 'user', 'ga ada kak', 0, '2025-07-05 17:12:59'),
(25, 'u2no5tueldaeiigu4ac0nv63ik', 5, 'user', 'deposit belum masuk', 0, '2025-07-05 17:13:02'),
(26, '5tpd8vv61lg7fcnj702s1b9qvq', 5, 'user', 'iya kak', 0, '2025-07-05 17:16:01'),
(27, '5tpd8vv61lg7fcnj702s1b9qvq', 5, 'user', 'twst', 0, '2025-07-05 17:27:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `method_name` varchar(100) NOT NULL COMMENT 'Nama lengkap metode, cth: Bank Central Asia',
  `method_code` varchar(20) NOT NULL COMMENT 'Kode singkat unik, cth: BCA',
  `method_type` enum('Bank','E-Wallet','Virtual Account','Pulsa') NOT NULL COMMENT 'Jenis metode pembayaran',
  `logo` varchar(255) DEFAULT NULL COMMENT 'Nama file logo',
  `min_length` int(2) DEFAULT 8,
  `max_length` int(2) DEFAULT 16,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Aktif, 0 = Tidak Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `method_name`, `method_code`, `method_type`, `logo`, `min_length`, `max_length`, `is_active`) VALUES
(1, 'Bank Central Asia', 'BCA', 'Bank', NULL, 10, 10, 1),
(2, 'Bank Mandiri', 'MANDIRI', 'Bank', NULL, 13, 13, 1),
(3, 'Bank Rakyat Indonesia', 'BRI', 'Bank', NULL, 15, 15, 1),
(4, 'Bank Negara Indonesia', 'BNI', 'Bank', NULL, 10, 10, 1),
(5, 'CIMB Niaga', 'CIMB', 'Bank', NULL, 13, 13, 1),
(6, 'Bank Permata', 'PERMATA', 'Bank', NULL, 8, 16, 1),
(7, 'Bank Danamon', 'DANAMON', 'Bank', NULL, 8, 16, 1),
(8, 'Bank Panin', 'PANIN', 'Bank', NULL, 8, 16, 1),
(9, 'Bank OCBC NISP', 'OCBC', 'Bank', NULL, 8, 16, 1),
(10, 'Bank Syariah Indonesia', 'BSI', 'Bank', NULL, 8, 16, 1),
(11, 'Bank Tabungan Negara', 'BTN', 'Bank', NULL, 8, 16, 1),
(12, 'Bank BTPN', 'BTPN', 'Bank', NULL, 8, 16, 1),
(13, 'Bank Maybank', 'MAYBANK', 'Bank', NULL, 8, 16, 1),
(14, 'Bank Jago', 'JAGO', 'Bank', NULL, 8, 16, 1),
(15, 'SeaBank', 'SEABANK', 'Bank', NULL, 8, 16, 1),
(16, 'DANA', 'DANA', 'E-Wallet', NULL, 10, 13, 1),
(17, 'OVO', 'OVO', 'E-Wallet', NULL, 10, 13, 1),
(18, 'GoPay', 'GOPAY', 'E-Wallet', NULL, 10, 13, 1),
(19, 'LinkAja', 'LINKAJA', 'E-Wallet', NULL, 10, 13, 1),
(20, 'ShopeePay', 'SHOPEEPAY', 'E-Wallet', NULL, 10, 13, 1),
(21, 'BCA Virtual Account', 'BCA_VA', 'Virtual Account', NULL, 8, 16, 1),
(22, 'Mandiri Virtual Account', 'MANDIRI_VA', 'Virtual Account', NULL, 8, 16, 1),
(23, 'BRI Virtual Account', 'BRIVA', 'Virtual Account', NULL, 8, 16, 1),
(24, 'BNI Virtual Account', 'BNI_VA', 'Virtual Account', NULL, 8, 16, 1),
(25, 'Permata Virtual Account', 'PERMATA_VA', 'Virtual Account', NULL, 8, 16, 1),
(26, 'Telkomsel', 'TSEL', 'Pulsa', NULL, 8, 16, 1),
(27, 'XL / Axis', 'XL', 'Pulsa', NULL, 8, 16, 1),
(28, 'Indosat', 'ISAT', 'Pulsa', NULL, 8, 16, 1),
(29, 'Tri', 'TRI', 'Pulsa', NULL, 8, 16, 1),
(30, 'QRIS', 'QRIS', 'E-Wallet', NULL, 10, 13, 1),
(31, 'QRIS (Auto - Static)', 'QRIS_STATIC', 'E-Wallet', 'qris_default_logo.png', 10, 20, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `promotions`
--

INSERT INTO `promotions` (`id`, `title`, `description`, `image_url`, `link_url`, `is_active`, `sort_order`) VALUES
(3, 'NEW MEMBER 100%', 'Dapatkan keuntungan new member 100% setelah deposit pertamamu, TO rendah dan syarat gampang', '686407e562e93_1751386085.png', '', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `providers`
--

CREATE TABLE `providers` (
  `id` int(11) NOT NULL,
  `nama_provider` varchar(100) NOT NULL,
  `logo_provider` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `providers`
--

INSERT INTO `providers` (`id`, `nama_provider`, `logo_provider`, `sort_order`) VALUES
(28, 'Pragmatic Play', '6865448523216_1751467141.webp', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'site_title', 'BOSSCUAN69'),
(2, 'welcome_message', 'BOSSCUAN69 adalah situs terpercaya untuk permainan slot online, live casino, dan masih banyak lagi. Bergabunglah sekarang dan rasakan pengalaman bermain terbaik dengan bonus melimpah.'),
(3, 'main_logo', 'main_logo_1751469801.png'),
(4, 'admin_logo', 'logo-admin.png'),
(5, 'footer_logo', 'logo-footer.png'),
(6, 'footer_tagline', 'HOKIRAJA * Situs Slot Online Terbaik Gacor Hari Ini Slot88 Nomor 1 di Indonesia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `togel_results`
--

CREATE TABLE `togel_results` (
  `id` int(11) NOT NULL,
  `market_name` varchar(100) NOT NULL,
  `result_date` date NOT NULL,
  `result_number` varchar(20) NOT NULL,
  `period` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `togel_results`
--

INSERT INTO `togel_results` (`id`, `market_name`, `result_date`, `result_number`, `period`, `is_active`) VALUES
(1, 'SINGAPORE', '2025-06-30', '8710', 'SGP-1234', 1),
(2, 'NAMPHOPOOLS', '2025-06-30', '6722', 'NMP-5678', 1),
(3, 'NAGANO POOLS', '2025-06-30', '9475', 'NGN-9012', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('deposit','withdraw') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `status`, `admin_notes`, `created_at`, `processed_at`) VALUES
(1, 1, 'deposit', 100000.00, 'approved', NULL, '2025-07-01 13:46:06', '2025-07-02 20:52:03'),
(2, 1, 'withdraw', 25000.00, 'approved', NULL, '2025-07-01 13:46:06', '2025-07-02 22:39:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','suspended','banned') NOT NULL DEFAULT 'active',
  `last_seen` timestamp NULL DEFAULT NULL,
  `referral_code` varchar(10) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `balance`, `status`, `last_seen`, `referral_code`, `registration_date`) VALUES
(5, 'test123', '$2y$10$Y8wdrEU9.ec.U8EPTbGGUupT31YVqU5vnj20/j8xaXfqjwLkJHkji', 'asd', 'asdada@gmail.com', '0934343434', 10104432.00, 'active', '2025-07-05 17:57:26', 'WJN7XFRT', '2025-07-02 02:50:49'),
(11, 'ts', '$2y$10$yRZ.HjlmvH6WtL0khrjEm.VAiNMOjqHxH96srqRVTRE4T3iXaiVt2', 'adada', 'adada@gmail.com', '08674645653', 3323232.00, 'active', NULL, '5NQPO86X', '2025-07-05 13:57:38'),
(15, 'testt', '$2y$10$OD5248Z5wWpYjDnKAEzbguE8LLfThuVKvxf/NdZ3gRf2vNhzXyaDq', 'admin', 'adminn@gmail.com', '0821040348084', 0.00, 'active', NULL, 'C8LXO5GV', '2025-07-05 14:51:55'),
(16, 'coba', '$2y$10$s/us6PDwW7A3uW1YhdU78uCQext.MX2uUvNZmzDdsNBHZjpoE.hV2', 'coba', 'coba@gmail.com', '083484303488', 0.00, 'active', NULL, 'MUFKPDQE', '2025-07-05 14:57:13'),
(17, 'adminadmin', '$2y$10$05Wt2WnB3wMbvVcRstYSSeGPrqgq7GNDzYjQlkfjTJusQ3/ROk0Nu', 'adminadmin', 'admin@gmail.com', '210430483048', 0.00, 'active', NULL, '8UWHVC5L', '2025-07-05 15:04:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_banks`
--

CREATE TABLE `user_banks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bank_name` varchar(50) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_banks`
--

INSERT INTO `user_banks` (`id`, `user_id`, `bank_name`, `account_number`, `account_name`, `is_primary`, `created_at`) VALUES
(1, 1, 'BCA', '1234567890', 'Joko Susilo', 1, '2025-07-01 13:46:07'),
(2, 1, 'DANA', '081234567890', 'Joko Susilo', 0, '2025-07-01 13:46:07'),
(3, 2, 'BCA', '8190539068', 'bagus ramadhan', 1, '2025-07-01 16:30:22'),
(4, 3, 'MANDIRI', '3434353453534', 'efsfesfes', 1, '2025-07-01 16:49:27'),
(5, 4, 'JAGO', '2323212132323232', 'adwadwad', 1, '2025-07-01 16:53:36'),
(6, 5, 'BTPN', '234532343434', 'awdwwds', 1, '2025-07-02 02:50:49'),
(7, 6, 'BCA', '2323232323', 'asdadwa', 1, '2025-07-03 13:17:33'),
(8, 7, 'BCA', '2222222222', 'admin', 1, '2025-07-05 13:25:25'),
(9, 8, 'BTPN', '1323243243434343', 'adwdawd', 1, '2025-07-05 13:26:58'),
(10, 9, 'BCA', '1232323132', 'admin', 1, '2025-07-05 13:41:27'),
(11, 10, 'DANAMON', '2343434343433434', 'adadwad', 1, '2025-07-05 13:51:43'),
(12, 11, 'DANAMON', '2323232323232323', 'adwadwadwad', 1, '2025-07-05 13:57:38'),
(13, 12, 'DANAMON', '2043097420472097', 'adaimdwi', 1, '2025-07-05 14:09:29'),
(14, 13, 'JAGO', '2193294949349343', 'bisa', 1, '2025-07-05 14:46:39'),
(15, 14, 'BCA', '1234384038', 'adminn', 1, '2025-07-05 14:47:30'),
(16, 15, 'BCA', '3294934793', 'adminnn', 1, '2025-07-05 14:51:55'),
(17, 16, 'BCA', '0404830480', 'coba', 1, '2025-07-05 14:57:13'),
(18, 17, 'BCA', '2304304830', 'adminadmin', 1, '2025-07-05 15:04:25');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `admin_deposit_accounts`
--
ALTER TABLE `admin_deposit_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `method_code` (`method_code`);

--
-- Indeks untuk tabel `bank_status`
--
ALTER TABLE `bank_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `method_code` (`method_code`);

--
-- Indeks untuk tabel `bonuses`
--
ALTER TABLE `bonuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bonus_code` (`bonus_code`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `deposit_transactions`
--
ALTER TABLE `deposit_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bonus_id` (`bonus_id`);

--
-- Indeks untuk tabel `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `live_chat_messages`
--
ALTER TABLE `live_chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indeks untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `method_code` (`method_code`);

--
-- Indeks untuk tabel `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_provider` (`nama_provider`);

--
-- Indeks untuk tabel `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indeks untuk tabel `togel_results`
--
ALTER TABLE `togel_results`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `referral_code` (`referral_code`);

--
-- Indeks untuk tabel `user_banks`
--
ALTER TABLE `user_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `admin_deposit_accounts`
--
ALTER TABLE `admin_deposit_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `bank_status`
--
ALTER TABLE `bank_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `bonuses`
--
ALTER TABLE `bonuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `deposit_transactions`
--
ALTER TABLE `deposit_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `live_chat_messages`
--
ALTER TABLE `live_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `providers`
--
ALTER TABLE `providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `togel_results`
--
ALTER TABLE `togel_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `user_banks`
--
ALTER TABLE `user_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin_deposit_accounts`
--
ALTER TABLE `admin_deposit_accounts`
  ADD CONSTRAINT `admin_deposit_accounts_ibfk_1` FOREIGN KEY (`method_code`) REFERENCES `payment_methods` (`method_code`);

--
-- Ketidakleluasaan untuk tabel `deposit_transactions`
--
ALTER TABLE `deposit_transactions`
  ADD CONSTRAINT `deposit_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `deposit_transactions_ibfk_2` FOREIGN KEY (`bonus_id`) REFERENCES `bonuses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
