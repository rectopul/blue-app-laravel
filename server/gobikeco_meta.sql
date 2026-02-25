-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 06, 2024 at 06:09 AM
-- Server version: 10.6.18-MariaDB
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gobikeco_meta`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `balance` double(20,2) NOT NULL DEFAULT 0.00,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salary_date` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `balance`, `name`, `photo`, `email`, `email_verified_at`, `password`, `salary_date`, `type`, `phone`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 0.00, 'Md Admin', '/public/admin/assets/images/profile/1706650015HOx.png', 'admin@gmail.com', '2023-11-29 18:37:08', '$2a$12$LVVZeNQa3brsKrAZIIWH.OnpOYk3mTuZhKTvFvFH8Q3wc49QFFn5S', '2024-05-03', 'admin', '01600000000', 'sd', 'kPnBINdiZz92S0d5K5I0WCfsJxTptpahnI8UXUIcYIk3cFrrXkJezDjsvKIb', '2023-11-28 11:11:57', '2024-05-03 14:53:54');

-- --------------------------------------------------------

--
-- Table structure for table `admin_ledgers`
--

CREATE TABLE `admin_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `perticulation` varchar(255) DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `status` enum('pending','approved','rejected','default') NOT NULL DEFAULT 'default',
  `date` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bonuses`
--

CREATE TABLE `bonuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bonus_name` varchar(255) NOT NULL,
  `counter` int(11) DEFAULT 0 COMMENT 'user get service count',
  `set_service_counter` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `winner` int(11) DEFAULT 0,
  `amount` double NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bonuses`
--

INSERT INTO `bonuses` (`id`, `bonus_name`, `counter`, `set_service_counter`, `code`, `winner`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'VIP Bonus', 1, 10, '101010', 5, 5, 'active', '2024-06-10 01:21:28', '2024-06-10 01:21:38');

-- --------------------------------------------------------

--
-- Table structure for table `bonus_ledgers`
--

CREATE TABLE `bonus_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bonus_id` bigint(20) UNSIGNED NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `bonus_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(255) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `date` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `token` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `method_name` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `system_order_id` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL COMMENT 'User Number',
  `amount` double(20,2) DEFAULT 0.00 COMMENT 'User Deposit Amount',
  `final_amount` double(20,3) NOT NULL DEFAULT 0.000,
  `date` varchar(255) DEFAULT NULL,
  `status` enum('pending','rejected','approved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `user_id`, `method_name`, `order_id`, `system_order_id`, `number`, `amount`, `final_amount`, `date`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, '20240706827872215697', NULL, NULL, 742.00, 742.000, '2024-07-06 02:21:48', 'pending', '2024-07-06 02:21:48', '2024-07-06 02:21:48'),
(2, 2, NULL, '20240706375058811378', NULL, NULL, 167000.00, 167000.000, '2024-07-06 02:29:23', 'pending', '2024-07-06 02:29:23', '2024-07-06 02:29:23'),
(3, 2, NULL, '20240706100397361244', NULL, NULL, 742.00, 742.000, '2024-07-06 02:29:40', 'pending', '2024-07-06 02:29:40', '2024-07-06 02:29:40'),
(4, 2, NULL, '20240706408526682358', NULL, NULL, 2700.00, 2700.000, '2024-07-06 03:31:17', 'pending', '2024-07-06 03:31:17', '2024-07-06 03:31:17'),
(5, 2, NULL, '2024070621685616465', NULL, NULL, 742.00, 742.000, '2024-07-06 11:26:39', 'pending', '2024-07-06 11:26:39', '2024-07-06 11:26:39'),
(6, 2, NULL, '20240706982513263798', NULL, NULL, 2700.00, 2700.000, '2024-07-06 11:31:16', 'pending', '2024-07-06 11:31:16', '2024-07-06 11:31:16'),
(7, 2, NULL, '20240706807852729806', NULL, NULL, 742.00, 742.000, '2024-07-06 11:37:06', 'pending', '2024-07-06 11:37:06', '2024-07-06 11:37:06'),
(8, 2, NULL, '20240706289526262891', NULL, NULL, 742.00, 742.000, '2024-07-06 12:02:38', 'pending', '2024-07-06 12:02:38', '2024-07-06 12:02:38'),
(9, 1, NULL, '2024070665834849412', NULL, NULL, 5700.00, 5700.000, '2024-07-06 12:04:48', 'pending', '2024-07-06 12:04:48', '2024-07-06 12:04:48'),
(10, 2, NULL, '20240706680517283038', NULL, NULL, 742.00, 742.000, '2024-07-06 12:08:03', 'pending', '2024-07-06 12:08:03', '2024-07-06 12:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `funds`
--

CREATE TABLE `funds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` longtext NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `commission` double(20,2) NOT NULL DEFAULT 0.00 COMMENT 'percent',
  `validity` bigint(20) NOT NULL,
  `minimum_invest` double(20,2) NOT NULL DEFAULT 0.00 COMMENT 'amount',
  `status` enum('upcoming','active') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `funds`
--

INSERT INTO `funds` (`id`, `name`, `title`, `photo`, `commission`, `validity`, `minimum_invest`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Fund 1', 'Fund oneFund oneFund oneFund one', '/public/upload/fund/1714067352YBp.jpg', 800.00, 5, 500.00, 'active', '2023-06-06 15:38:44', '2024-04-25 23:49:12'),
(18, 'Fund 2', 'Event', '/public/upload/fund/1714067554sbV.jpg', 2500.00, 2, 1500.00, 'active', '2024-03-24 11:28:12', '2024-05-01 07:27:28'),
(19, 'Fund 3', 'Event', '/public/upload/fund/1714067820Ksw.jpg', 3000.00, 1, 2000.00, 'active', '2024-03-24 11:29:44', '2024-05-01 07:25:27'),
(24, 'Fund 3', 'Vip4', '/public/upload/fund/171452681034u.jpg', 7500.00, 1, 5000.00, 'active', '2024-05-01 07:26:51', '2024-05-01 07:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `fund_invests`
--

CREATE TABLE `fund_invests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fund_id` bigint(20) UNSIGNED NOT NULL,
  `validity_expired` varchar(255) NOT NULL,
  `price` double(20,2) NOT NULL DEFAULT 0.00,
  `return_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lucky_ledgers`
--

CREATE TABLE `lucky_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `draw_id` bigint(20) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `current_date` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_03_16_074227_create_admins_table', 1),
(6, '2023_03_17_123007_create_packages_table', 2),
(8, '2023_03_27_103153_create_payment_methods_table', 3),
(10, '2023_03_28_074201_create_deposits_table', 4),
(11, '2023_03_28_142734_create_user_ledgers_table', 5),
(12, '2023_03_28_142802_create_admin_ledgers_table', 6),
(13, '2023_03_30_071745_create_vip_sliders_table', 7),
(14, '2023_03_30_150139_create_settings_table', 8),
(15, '2023_04_01_185541_create_bonuses_table', 9),
(16, '2023_04_01_205009_create_bonus_ledgers_table', 10),
(17, '2023_04_05_203304_create_purchases_table', 11),
(18, '2023_04_09_200835_create_minings_table', 12),
(19, '2023_05_05_092841_create_drows_table', 13),
(20, '2023_05_05_111428_create_lucky_ledgers_table', 14),
(21, '2023_05_05_161904_create_icons_table', 15),
(22, '2023_05_09_214610_create_hiru_notices_table', 16),
(23, '2023_06_06_210226_create_funds_table', 17),
(24, '2023_06_06_222047_create_fund_invests_table', 18),
(25, '2023_06_11_113547_create_checkins_table', 19),
(26, '2023_06_23_193458_create_improvments_table', 20),
(27, '2023_06_24_083626_create_commissions_table', 21),
(28, '2023_12_14_221116_create_tasks_table', 22),
(29, '2024_01_31_034808_create_rebates_table', 23);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `validity` varchar(255) NOT NULL COMMENT 'count days',
  `commission_with_avg_amount` double NOT NULL DEFAULT 0 COMMENT 'user get average amount after validity',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `title`, `photo`, `price`, `validity`, `commission_with_avg_amount`, `status`, `created_at`, `updated_at`) VALUES
(25, 'MTX No 1', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/1720207694Jzl.webp', 742, '30', 2160, 'active', '2024-01-07 17:03:27', '2024-07-06 01:28:14'),
(26, 'MTX No 2', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/1720207890hXB.webp', 2700, '30', 8520, 'active', '2024-01-07 17:07:38', '2024-07-06 01:31:30'),
(27, 'MTX No 3', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/1720207972RGa.webp', 5700, '30', 18900, 'active', '2024-01-09 18:21:25', '2024-07-06 01:32:52'),
(35, 'MTX No 4', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/1720208060MEZ.webp', 12700, '30', 44280, 'active', '2024-04-25 23:32:56', '2024-07-06 01:34:20'),
(36, 'MTX No 5', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/1720208148NMd.webp', 24000, '30', 86400, 'active', '2024-04-25 23:34:20', '2024-07-06 01:35:48'),
(37, 'MTX No 6', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/172020827189h.webp', 37000, '30', 138240, 'active', '2024-04-25 23:36:05', '2024-07-06 01:37:51'),
(38, 'MTX No 7', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/17202084101OD.webp', 72000, '30', 308550, 'active', '2024-04-25 23:42:36', '2024-07-06 01:40:10'),
(39, 'MTX No 8', 'Daily earnings are added to the account immediately after completion of 24 hours!', '/public/upload/package/1720208648okV.webp', 167000, '30', 834990, 'active', '2024-04-25 23:44:57', '2024-07-06 01:44:08');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `photo`, `address`, `status`, `created_at`, `updated_at`) VALUES
(12, 'TRC20', '/public/upload/setting/1717786270kYi.png', 'svcsghvcgsvcgsvcvsgvcgsv', 'active', '2023-09-15 10:48:03', '2024-06-07 23:53:56'),
(15, 'Nagad', '/public/upload/setting/1717786279b6U.png', '01620062488', 'active', '2023-11-02 09:50:36', '2024-06-07 23:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `daily_income` double(20,2) NOT NULL DEFAULT 0.00,
  `date` varchar(255) NOT NULL,
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'pending',
  `validity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `package_id`, `amount`, `daily_income`, `date`, `status`, `validity`, `created_at`, `updated_at`) VALUES
(1, 3, 26, 2700, 284.00, '2024-07-07 03:28:27', 'active', '2024-08-05 03:18:34', '2024-07-06 03:18:34', '2024-07-06 03:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `rebates`
--

CREATE TABLE `rebates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `interest_commission1` double NOT NULL,
  `interest_commission2` double NOT NULL,
  `interest_commission3` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rebates`
--

INSERT INTO `rebates` (`id`, `interest_commission1`, `interest_commission2`, `interest_commission3`, `created_at`, `updated_at`) VALUES
(1, 15, 0, 0, NULL, '2024-07-06 01:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `withdraw_charge` int(11) NOT NULL DEFAULT 0 COMMENT 'percent',
  `minimum_withdraw` double(20,2) NOT NULL DEFAULT 0.00,
  `maximum_withdraw` double(20,2) NOT NULL DEFAULT 0.00,
  `w_time_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `checkin` double(20,2) NOT NULL DEFAULT 0.00,
  `registration_bonus` double(20,2) NOT NULL DEFAULT 0.00,
  `total_member_register_reword` int(11) NOT NULL DEFAULT 0,
  `total_member_register_reword_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `withdraw_charge`, `minimum_withdraw`, `maximum_withdraw`, `w_time_status`, `checkin`, `registration_bonus`, `total_member_register_reword`, `total_member_register_reword_amount`, `created_at`, `updated_at`) VALUES
(1, 11, 280.00, 50000.00, 'active', 0.00, 0.00, 0, 0.00, '2022-01-18 11:03:22', '2024-07-06 01:45:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_by` varchar(255) DEFAULT NULL,
  `ref_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `investor` int(11) NOT NULL DEFAULT 0,
  `realname` varchar(255) DEFAULT NULL,
  `phone_code` varchar(20) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `balance` double(20,2) NOT NULL DEFAULT 0.00,
  `receive_able_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `photo` varchar(255) DEFAULT NULL,
  `gateway_method` varchar(50) DEFAULT NULL,
  `gateway_number` varchar(50) DEFAULT NULL,
  `withdraw_password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `ban_unban` enum('ban','unban') NOT NULL DEFAULT 'unban',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active_member` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ref_by`, `ref_id`, `name`, `investor`, `realname`, `phone_code`, `phone`, `ip`, `username`, `email`, `email_verified_at`, `password`, `type`, `balance`, `receive_able_amount`, `photo`, `gateway_method`, `gateway_number`, `withdraw_password`, `remember_token`, `status`, `ban_unban`, `created_at`, `updated_at`, `active_member`) VALUES
(1, '8928394157', '719281482', 'User32', 0, NULL, '+880', '1933690444', '203.76.146.145', 'uname1933690444', '1720207647557@gmail.com', NULL, '$2y$10$MYLVT8djCWP.9Du1RWSZTukXdav6LAIc2fkUyrFi68ZwojlMMbSae', NULL, 925.00, 0.00, NULL, NULL, NULL, '123456', NULL, 'active', 'unban', '2024-07-06 01:27:27', '2024-07-06 03:18:34', 0),
(2, '8697987764', '986563534', 'Build xi', 0, NULL, '+880', '01726008406', '27.147.206.126', 'uname01726008406', '1720208799313@gmail.com', NULL, '$2y$10$oDBBOJyiYIFg9JpA7IfjVehI9uExZSKz8zFgvQmCkysqQdjQaVr5y', NULL, 0.00, 0.00, NULL, 'Easypaisa', '01896586328', '222222', NULL, 'active', 'unban', '2024-07-06 01:46:40', '2024-07-06 02:23:45', 0),
(3, '719281482', '539341354', 'User53', 1, NULL, '+880', '1600208571', '203.76.146.145', 'uname1600208571', '1720214243212@gmail.com', NULL, '$2y$10$jo69GLGo8..8vFWZf46J1elLInYvq692KO6gcnH5qPcDy6OOVezWa', NULL, 2604.00, 0.00, NULL, NULL, NULL, '123456', NULL, 'active', 'unban', '2024-07-06 03:17:23', '2024-07-06 03:33:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_ledgers`
--

CREATE TABLE `user_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `get_balance_from_user_id` bigint(20) DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `perticulation` varchar(255) DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `debit` double NOT NULL DEFAULT 0,
  `credit` double NOT NULL DEFAULT 0,
  `status` enum('pending','approved','rejected','default') NOT NULL DEFAULT 'default',
  `date` varchar(255) DEFAULT NULL,
  `step` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_ledgers`
--

INSERT INTO `user_ledgers` (`id`, `user_id`, `get_balance_from_user_id`, `reason`, `perticulation`, `amount`, `debit`, `credit`, `status`, `date`, `step`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'commission', 'First Level Commission Received', 405, 405, 0, 'approved', '06-07-2024 03:18', 'first', '2024-07-06 03:18:34', '2024-07-06 03:18:34'),
(2, 3, NULL, 'daily_income', 'MTX No 2 Package commission', 284, 0, 284, 'approved', '2024-07-06 03:28:27', NULL, '2024-07-06 03:28:27', '2024-07-06 03:28:27'),
(3, 3, NULL, 'daily_income', 'Commission Received.', 284, 0, 284, 'approved', '2024-07-06 03:28:37', NULL, '2024-07-06 03:28:37', '2024-07-06 03:28:37'),
(4, 3, NULL, 'daily_income', 'Commission Received.', 20, 0, 20, 'approved', '2024-07-06 03:33:18', NULL, '2024-07-06 03:33:18', '2024-07-06 03:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `vip_sliders`
--

CREATE TABLE `vip_sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `page_type` enum('home_page','vip_page') NOT NULL DEFAULT 'home_page',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vip_sliders`
--

INSERT INTO `vip_sliders` (`id`, `photo`, `status`, `page_type`, `created_at`, `updated_at`) VALUES
(11, '/public/upload/slider/1688711605xsK.jpg', 'active', 'home_page', '2023-07-05 01:06:16', '2023-07-07 04:33:25'),
(12, '/public/upload/slider/1688711639ObA.jpg', 'active', 'home_page', '2023-07-05 01:06:35', '2023-07-07 04:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `method_name` varchar(255) DEFAULT NULL,
  `oid` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `charge` decimal(20,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','rejected','processing') NOT NULL DEFAULT 'pending' COMMENT '1=>success, 2=>pending, 3=>cancel,  ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_ledgers`
--
ALTER TABLE `admin_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bonuses`
--
ALTER TABLE `bonuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bonus_ledgers`
--
ALTER TABLE `bonus_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checkins_user_id_foreign` (`user_id`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `funds`
--
ALTER TABLE `funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fund_invests`
--
ALTER TABLE `fund_invests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_invests_user_id_foreign` (`user_id`),
  ADD KEY `fund_invests_fund_id_foreign` (`fund_id`);

--
-- Indexes for table `lucky_ledgers`
--
ALTER TABLE `lucky_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lucky_ledgers_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `purchases_package_id_foreign` (`package_id`);

--
-- Indexes for table `rebates`
--
ALTER TABLE `rebates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_ledgers`
--
ALTER TABLE `user_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vip_sliders`
--
ALTER TABLE `vip_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_ledgers`
--
ALTER TABLE `admin_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bonuses`
--
ALTER TABLE `bonuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bonus_ledgers`
--
ALTER TABLE `bonus_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `funds`
--
ALTER TABLE `funds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `fund_invests`
--
ALTER TABLE `fund_invests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lucky_ledgers`
--
ALTER TABLE `lucky_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rebates`
--
ALTER TABLE `rebates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_ledgers`
--
ALTER TABLE `user_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vip_sliders`
--
ALTER TABLE `vip_sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lucky_ledgers`
--
ALTER TABLE `lucky_ledgers`
  ADD CONSTRAINT `lucky_ledgers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
