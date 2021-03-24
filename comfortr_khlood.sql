-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2021 at 01:34 PM
-- Server version: 5.7.33
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comfortr_khlood`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `created_at`, `updated_at`, `name`, `phone`, `email`, `password`, `remember_token`) VALUES
(1, NULL, '2020-12-01 14:07:21', 'tqnee', '0580491109', 'tqnee.com.sa@gmail.com', '$2y$10$l453rgUqzlzQoeTCn7sZg.41pMnnKWmenYWutK8xWl4l4v2vT2/uy', 'JIaqcdbspjZibNzhvo0pWdV8B4k23nj0DdqYla3XHUckhapyYrlAqmtdfifh'),
(2, '2020-11-21 10:32:54', '2020-11-21 10:32:54', 'tqnee2Test', '01111973729', 'tqnee.com.sa2@gmail.com', '$2y$10$hmUFYv6kRr5Dm8SLeAFQeu0iyb8gbTeeCyDET6FJTgAvkcSv80f8q', NULL),
(3, '2020-11-21 10:40:23', '2020-11-21 10:41:35', 'tqnee3', '01095488022', 'tqnee.com.sa3@gmail.com', '$2y$10$rFXYeUDFbWAQb6Alv/OUku03EOfMzE/un2fTBWK/sVySwrzeUA3BS', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_role`
--

CREATE TABLE `admin_role` (
  `id` int(11) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_role`
--

INSERT INTO `admin_role` (`id`, `admin_id`, `role_id`) VALUES
(6, 1, 7),
(7, 2, 10),
(8, 3, 10);

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) DEFAULT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `is_pay` int(11) NOT NULL DEFAULT '0',
  `status` enum('1','2','3','4') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `created_at`, `updated_at`, `name`, `latitude`, `longitude`, `name_ar`) VALUES
(5, '2020-10-12 11:44:36', '2021-01-23 19:22:10', 'Dammam', 26.37749160, 50.10076664, 'الدمام');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `arranging` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `created_at`, `updated_at`, `name`, `name_ar`, `image`, `branch_id`, `arranging`, `active`) VALUES
(6, '2020-10-12 11:45:21', '2021-02-03 22:05:26', 'Electricity', 'كهرباء', 'category_1610578086.png', 5, 1, 0),
(7, '2020-10-14 08:01:48', '2021-01-31 11:24:40', 'Painting', 'ديكور', 'category_1610578294.png', 5, 5, 1),
(8, '2020-10-21 20:36:11', '2021-01-31 11:24:44', 'Gardening', 'حدائق', 'category_1610578303.png', 5, 6, 1),
(9, '2020-10-22 07:33:24', '2021-02-03 22:06:18', 'AC', 'تكييف', 'category_1610578097.png', 5, 2, 0),
(10, '2021-01-10 20:35:21', '2021-01-31 11:24:40', 'Plumbing', 'سباكة', 'category_1610578119.png', 5, 4, 1),
(11, '2021-01-10 20:35:49', '2021-01-31 11:24:38', 'Installation', 'تركيب', 'category_1610578108.png', 5, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_user`
--

CREATE TABLE `category_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_user`
--

INSERT INTO `category_user` (`id`, `category_id`, `user_id`, `created_at`, `updated_at`) VALUES
(105, 8, 65, NULL, NULL),
(106, 9, 66, NULL, NULL),
(112, 6, 73, NULL, NULL),
(113, 6, 74, NULL, NULL),
(114, 7, 74, NULL, NULL),
(115, 8, 74, NULL, NULL),
(116, 9, 74, NULL, NULL),
(117, 10, 74, NULL, NULL),
(118, 11, 74, NULL, NULL),
(120, 6, 82, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `message` text,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `created_at`, `updated_at`, `device_token`, `user_id`) VALUES
(56, '2021-01-26 12:45:25', '2021-02-18 14:48:12', 'co-J2GfOFU--sYKajWWdDJ:APA91bFVotmhgZTtrn75MT7lJ-C3n0UX9xQvrLVa3y4jyz0rMG1A83Sg4nV5YOfKCaAE77Fb604hZhfSI9LX18t8WGgw4vv0F8JPwmJGxwO7hLphukEfKtfUXPUAFViy43tDVNbSOgly', 64),
(57, '2021-01-26 12:45:30', '2021-02-03 22:12:56', 'fjnrCxNtRFiMsRncSjDySJ:APA91bFq-ZHoFALr0RFGIXDs1qffQHbGBAnQNf8lBpkOyy0Qb9ROqoG7yvXLXAf2812XGMD4JXLAvQgh-O0K-rcILeq_sXb1i_CLvMfu6ime7VPhbtJAUvvUNh6K3mLvsepSXQ-jrSdQ', 65),
(58, '2021-01-26 12:45:54', '2021-01-26 12:45:54', 'd5R052hdRg6t2GWPoRBk6K:APA91bFhzhC4JaTICSzdZlibL9Th84p41wtP_em7GVNdhNjCMXg08bGgzuJzsQVmlB0ybZG9X5fdn4Y3mFy_BZ-G3m8HGqt5OQPIolbWwqbGHh1kBP876InTb6DxW9LqK4UU0XXc2CgJ', 66),
(61, '2021-01-26 12:54:36', '2021-01-26 12:54:36', 'eDN0vxOmQAWpwHPCT8SqCS:APA91bGZR7WL9B0TF1KYXbP3gONp-Jd0Z2Nt1Ln9NwaOrW6_DT6MTtCCzxVNQAIBePjh9SxEZfVlM95GywzhVpasJmMkhz0XXeEXmmyLPLwV1P7aWnY-2fhJNXuPN0Wi5auenUixZ3Qg', 69),
(62, '2021-01-26 16:16:35', '2021-01-26 16:16:35', 'fS6dmHY5SZ-ldSs2jRX0Eg:APA91bGnwkl8abUAhkvFZ1Z_Qwa5vq-6e29pHCyf1ffSLjMCfAEoXZ46rAJ0fjBo3k3xcDFZfS1LrLI0wXC4vaEj0TA41ChrcDtYYOGx-CJtQdQWLV7ZGmOkBz215LKILK7rCTG72R-5', 70),
(63, '2021-01-26 16:26:32', '2021-01-26 16:26:32', 'cucjjnxfRE-l84xPcnVbyK:APA91bF44sG4Uep2cPVr6cF_NGziaq-Pjn6Idx1pqsTcOVMsqREfNoGHhzB78ElGm_yjFGw_ysi-V1JO-GnyrowmqwQmyl8FZrL8_Rj2_Z7dKlvqBdd0i01qs_5v2H12d-DU0QhwUtvq', 71),
(64, '2021-01-26 17:56:30', '2021-01-26 17:56:30', 'fAh_q-37TiSDgEV_gM0ICr:APA91bFk8L335UUuDyBZ9JtGydHTiIx62lOt2wD_ivoMvWxksSwOrp0AAIZJ4fHM81VfobDYL3y-px1zqRtHawcpO9iTGr31NOBBY57KUlbstdLlR6mYqrii9kBDSBINf_quFxSt_Nu1', 72),
(65, '2021-01-31 12:41:01', '2021-01-31 12:41:01', '123456', 73),
(66, '2021-01-31 12:43:58', '2021-02-18 14:55:42', 'd2xfbd3IrUWdsYzC9fzHOf:APA91bEVLjn9O0u2GZerfc7qBmRMlYFYfPmeksZQy72HN9rRjcOc1V_DCAEW8o-v3-4DR0YKFaGv7O5_C0TNSlg8ob75ut101CfMLPICsQVVKNG6EUTPjJBqC1yDACNx9VaN8eKKhgGA', 74),
(71, '2021-03-03 20:41:45', '2021-03-03 20:41:45', '123456789', 79),
(72, '2021-03-03 20:47:18', '2021-03-03 20:47:18', 'f6DxXjTFkkjennk9FSspCU:APA91bGJSMryggJO2FmojQnCU4aWHMlX1bTJwsfzw2qrQ8PfUadQ_iVg5_KnmiXznWegjB_UkFkXQW3YpBjNidlaehhVY5j85ln59s6mrKkdc41xgKc3Vj29TCHURtyt_xntgUby2wGw', 80),
(73, '2021-03-03 20:56:17', '2021-03-03 20:56:17', 'csv1ljh9IEaqsmoi_-NLu7:APA91bEq5vIJZy9Q_ERJSDhMGfnoNUvApKNft_jr5ZMvqRYMrfp2W-u4B_fiyMjWuaMnqRfWG06ldlk7HPAagK9e1zz1di-23YWpbrMw_jf2hmI16lkI3onpF1rlAWcokO-AeqfHYhxb', 81),
(74, '2021-03-03 21:12:56', '2021-03-03 21:12:56', 'dJ-LaEr210M_oURevylDBk:APA91bE8QvP8M7ncDm-3e1MKiihaBGgx9ZNiuO9cA4Pf7bdM3LRA7RULOg-sEUvXG-8zGKKUo0GlE5EN2VC9sG_CNrarkx5aq8PdlqL7RFSopRlsoGBBEiX8kgp0az1H7md-nKu3rC-O', 82),
(75, '2021-03-05 01:54:48', '2021-03-05 01:54:48', 'e1yNgGTo20uQpX4dUGMDn-:APA91bHTni-mUjqoWfUJyIBQs11qcA1Q88RTjBjV_TSxIek1ruT5ifjzyBIJ5_kVdmnKgAwk1uRxnWQZpTAe0krz_R-tKWHvd-dmCfwY_iWNzvjzdD5hPFgMmmzFLSlGd0YiolSKSaat', 83),
(76, '2021-03-05 11:54:51', '2021-03-05 11:54:51', 'fruvkukUFkYom7rkngvy5e:APA91bEbXXqUmyT3tFbAGIf1CXAy42XqfGqjgyKy6Gm6J2gE_l7aPdSpNxdcIqEzCggNZHrPfoAAUUgwxoSynGS2CFrh1AblSN-s1FNQ7RvGCb4ShuzOI6rKqtK6vpV_hXqUZqDowxmq', 84),
(77, '2021-03-05 13:46:56', '2021-03-05 13:46:56', 'fwdme3iSo0dlmHNcWkLd7O:APA91bEtwGbx7UsKa-I3iANwkr-gJEFvjJg6rNd5gtpLbqsktQSt8w4HXFxwD3g-Ps3LE7vncvBImE_RHf1gXkG9Pe9Gf9sSqFLONjNzJwqTX3auhUD44Af4DoO03W58Xx48aI3Ym0rL', 85);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `created_at`, `updated_at`, `image`, `order_id`) VALUES
(152, '2021-01-26 17:57:50', '2021-01-26 17:57:50', 'order_1611687470.jpg', 197),
(153, '2021-01-31 14:41:18', '2021-01-31 14:41:18', 'order_1612107678.png', 198),
(154, '2021-01-31 14:43:16', '2021-01-31 14:43:16', 'order_1612107796.png', 199);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `main` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `created_at`, `updated_at`, `name`, `longitude`, `latitude`, `user_id`, `main`) VALUES
(51, '2021-01-26 12:45:25', '2021-01-26 12:45:25', 'البيت', 50.10535084, 26.41564720, 64, 1),
(52, '2021-01-26 12:54:36', '2021-01-26 12:54:36', 'المكتب ‏', 50.10096274, 26.37718611, 69, 1),
(53, '2021-01-26 16:16:35', '2021-01-26 16:16:35', 'dammmam', 50.10096140, 26.37718940, 70, 1),
(55, '2021-01-26 17:56:30', '2021-01-26 17:56:30', 'al ‎wahah', 50.10096006, 26.37718221, 72, 1),
(59, '2021-03-03 20:41:45', '2021-03-03 20:41:45', 'home', 35.50000000, 33.50000000, 79, 1),
(60, '2021-03-03 20:47:18', '2021-03-03 20:47:18', 'home', 49.98436008, 26.39925009, 80, 1),
(61, '2021-03-03 20:56:17', '2021-03-03 20:56:17', 'Home', 50.12689266, 26.43638379, 81, 1),
(62, '2021-03-05 01:54:48', '2021-03-05 01:54:48', 'المنزل ‏', 50.00256121, 26.54096844, 83, 1),
(63, '2021-03-05 11:54:51', '2021-03-05 11:54:51', 'المنزل', 46.74449969, 24.76740862, 84, 1),
(64, '2021-03-05 13:46:56', '2021-03-05 13:46:56', 'Home 4446', 50.11493873, 26.37640635, 85, 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2020_10_06_104228_create_admins_table', 1),
(3, '2020_10_06_104228_create_bills_table', 1),
(4, '2020_10_06_104228_create_branches_table', 1),
(5, '2020_10_06_104228_create_categories_table', 1),
(6, '2020_10_06_104228_create_complaints_table', 1),
(7, '2020_10_06_104228_create_devices_table', 1),
(8, '2020_10_06_104228_create_images_table', 1),
(9, '2020_10_06_104228_create_locations_table', 1),
(10, '2020_10_06_104228_create_notifications_table', 1),
(11, '2020_10_06_104228_create_orders_table', 1),
(12, '2020_10_06_104228_create_rates_table', 1),
(13, '2020_10_06_104228_create_reports_table', 1),
(14, '2020_10_06_104228_create_settings_table', 1),
(15, '2020_10_06_104228_create_shifts_table', 1),
(16, '2020_10_06_104228_create_users_table', 1),
(17, '2020_10_06_104238_create_foreign_keys', 1),
(18, '2020_10_06_105827_create_phone_verifications_table', 2),
(19, '2020_10_07_150222_create_category_user_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `message` text NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `is_read` int(11) DEFAULT '0',
  `bill_id` int(10) UNSIGNED DEFAULT NULL,
  `description_ar` text COLLATE utf8mb4_unicode_ci,
  `title_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `created_at`, `updated_at`, `title`, `description`, `user_id`, `order_id`, `is_read`, `bill_id`, `description_ar`, `title_ar`) VALUES
(305, '2021-01-26 13:15:42', '2021-01-26 13:15:42', 'رامي ‏عميلorder from', 'see your new orders', 66, 195, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(306, '2021-01-26 13:15:42', '2021-01-26 13:15:42', 'your order send successfully', 'see your new orders', 64, 195, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(310, '2021-01-26 14:16:43', '2021-01-26 14:16:43', ' your order is active', 'Your order has been initiated ', 64, 195, 0, NULL, '  طلبك نشط الان ', 'تم البدء في طلب'),
(311, '2021-01-26 14:19:06', '2021-01-26 14:19:06', ' your order witll complete in another day', 'Your order has been uncompleted yet ', 64, 195, 0, NULL, '  طلبك لم يكتمل بعد  ', ' سيتم تكمله الطلب في يوم اخر'),
(314, '2021-01-26 15:37:24', '2021-01-26 15:37:24', 'test en', 'notification en', 64, NULL, 0, NULL, 'notification ar', 'test ar'),
(316, '2021-01-26 15:37:24', '2021-01-26 15:37:24', 'test en', 'notification en', 66, NULL, 0, NULL, 'notification ar', 'test ar'),
(319, '2021-01-26 15:37:24', '2021-01-26 15:37:24', 'test en', 'notification en', 69, NULL, 0, NULL, 'notification ar', 'test ar'),
(320, '2021-01-26 17:50:50', '2021-01-26 17:50:50', 'test en', 'yih nkkhk', 65, NULL, 0, NULL, 'lk,;;;kk,lll', 'test ar'),
(321, '2021-01-26 17:50:50', '2021-01-26 17:50:50', 'test en', 'yih nkkhk', 66, NULL, 0, NULL, 'lk,;;;kk,lll', 'test ar'),
(323, '2021-01-26 17:53:11', '2021-01-26 17:53:11', ' your order is reject', 'Contact the administration to find out the reason for canceling your order ', 64, 195, 0, NULL, '  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ', 'تم الغاء  طلبك'),
(324, '2021-01-26 17:57:50', '2021-01-26 17:57:50', 'Muhammad ‎order from', 'see your new orders', 66, 197, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : Muhammad ‎'),
(325, '2021-01-26 17:57:50', '2021-01-26 17:57:50', 'your order send successfully', 'see your new orders', 72, 197, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(326, '2021-01-26 18:00:25', '2021-01-26 18:00:25', 'order fromMuhammad ‎', 'see your new orders', 66, 197, 0, NULL, ' تفحص الطلبات الجديدة', 'Muhammad ‎طلب  من '),
(327, '2021-01-27 09:38:38', '2021-01-27 09:38:38', ' your order is reject', 'Contact the administration to find out the reason for canceling your order ', 72, 197, 0, NULL, '  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ', 'تم الغاء  طلبك'),
(328, '2021-01-31 14:41:18', '2021-01-31 14:41:18', 'Muhammad ‎order from', 'see your new orders', 65, 198, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : Muhammad ‎'),
(329, '2021-01-31 14:41:18', '2021-01-31 14:41:18', 'your order send successfully', 'see your new orders', 72, 198, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(330, '2021-01-31 14:43:16', '2021-01-31 14:43:16', 'Muhammad ‎order from', 'see your new orders', 65, 199, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : Muhammad ‎'),
(331, '2021-01-31 14:43:16', '2021-01-31 14:43:16', 'your order send successfully', 'see your new orders', 72, 199, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(332, '2021-02-01 20:56:35', '2021-02-01 20:56:35', 'رامي ‏عميلorder from', 'see your new orders', 74, 200, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(333, '2021-02-01 20:56:35', '2021-02-01 20:56:35', 'your order send successfully', 'see your new orders', 64, 200, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(334, '2021-02-02 09:08:59', '2021-02-02 09:08:59', 'check order', ' check order ', 74, 200, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(335, '2021-02-02 09:08:59', '2021-02-02 09:08:59', 'check order', ' check order ', 64, 200, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(336, '2021-02-02 22:03:27', '2021-02-02 22:03:27', ' your order is reject', 'Contact the administration to find out the reason for canceling your order ', 64, 200, 0, NULL, '  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ', 'تم الغاء  طلبك'),
(337, '2021-02-02 22:08:32', '2021-02-02 22:08:32', 'رامي ‏عميلorder from', 'see your new orders', 74, 201, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(338, '2021-02-02 22:08:32', '2021-02-02 22:08:32', 'your order send successfully', 'see your new orders', 64, 201, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(339, '2021-02-03 02:13:10', '2021-02-03 02:13:10', ' your order is active', 'Your order has been initiated ', 64, 201, 0, NULL, '  طلبك نشط الان ', 'تم البدء في طلب'),
(340, '2021-02-03 09:45:36', '2021-02-03 09:45:36', ' your order is complete', 'Your order has been endes ', 64, 201, 0, NULL, '  طلبك مكتمل الان ', 'تم انهاء الطلب'),
(341, '2021-02-03 11:03:18', '2021-02-03 11:03:18', 'رامي ‏عميلorder from', 'see your new orders', 74, 202, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(342, '2021-02-03 11:03:18', '2021-02-03 11:03:18', 'your order send successfully', 'see your new orders', 64, 202, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(343, '2021-02-03 11:03:36', '2021-02-03 11:03:36', 'رامي ‏عميلorder from', 'see your new orders', 74, 203, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(344, '2021-02-03 11:03:36', '2021-02-03 11:03:36', 'your order send successfully', 'see your new orders', 64, 203, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(345, '2021-02-03 11:12:25', '2021-02-03 11:12:25', ' your order is canceled', 'Your order has been canceled ', 64, 202, 0, NULL, '  تم الغاء طلبك ', ' تم الغاء طلبك '),
(346, '2021-02-03 12:42:01', '2021-02-03 12:42:01', 'رامي ‏عميلorder from', 'see your new orders', 74, 204, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(347, '2021-02-03 12:42:01', '2021-02-03 12:42:01', 'your order send successfully', 'see your new orders', 64, 204, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(348, '2021-02-03 22:10:13', '2021-02-03 22:10:13', ' your order is reject', 'Contact the administration to find out the reason for canceling your order ', 64, 204, 0, NULL, '  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ', 'تم الغاء  طلبك'),
(349, '2021-02-03 22:10:21', '2021-02-03 22:10:21', ' your order is reject', 'Contact the administration to find out the reason for canceling your order ', 72, 199, 0, NULL, '  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ', 'تم الغاء  طلبك'),
(350, '2021-02-03 22:10:44', '2021-02-03 22:10:44', 'رامي ‏عميلorder from', 'see your new orders', 65, 205, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(351, '2021-02-03 22:10:44', '2021-02-03 22:10:44', 'your order send successfully', 'see your new orders', 64, 205, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(352, '2021-02-03 22:11:37', '2021-02-03 22:11:37', ' your order is reject', 'Contact the administration to find out the reason for canceling your order ', 72, 198, 0, NULL, '  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ', 'تم الغاء  طلبك'),
(353, '2021-02-03 22:12:20', '2021-02-03 22:12:20', 'رامي ‏عميلorder from', 'see your new orders', 65, 206, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(354, '2021-02-03 22:12:21', '2021-02-03 22:12:21', 'your order send successfully', 'see your new orders', 64, 206, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(355, '2021-02-03 22:20:31', '2021-02-03 22:20:31', ' your order is active', 'Your order has been initiated ', 64, 205, 0, NULL, '  طلبك نشط الان ', 'تم البدء في طلب'),
(356, '2021-02-03 22:21:03', '2021-02-03 22:21:03', ' your order is complete', 'Your order has been endes ', 64, 205, 0, NULL, '  طلبك مكتمل الان ', 'تم انهاء الطلب'),
(357, '2021-02-14 17:11:22', '2021-02-14 17:11:22', 'فاتورة مياة', 'gfjfgjfjf', 74, NULL, 0, NULL, 'hdjffjdffg', 'اي عنوان'),
(358, '2021-02-14 17:38:45', '2021-02-14 17:38:45', 'رامي ‏عميلorder from', 'see your new orders', 65, 207, 0, NULL, ' تفحص الطلبات الجديدة', 'طلب  من  : رامي ‏عميل'),
(359, '2021-02-14 17:38:45', '2021-02-14 17:38:45', 'your order send successfully', 'see your new orders', 64, 207, 0, NULL, ' تفحص الطلبات الجديدة', 'تم ارسال طلبك بنجاح'),
(360, '2021-02-18 04:08:10', '2021-02-18 04:08:10', 'check order', ' check order ', 74, 203, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(361, '2021-02-18 04:08:10', '2021-02-18 04:08:10', 'check order', ' check order ', 64, 203, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(362, '2021-02-18 04:09:35', '2021-02-18 04:09:35', 'check order', ' check order ', 74, 203, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(363, '2021-02-18 04:09:35', '2021-02-18 04:09:35', 'check order', ' check order ', 64, 203, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(364, '2021-02-18 04:09:46', '2021-02-18 04:09:46', ' your order is active', 'Your order has been initiated ', 64, 203, 0, NULL, '  طلبك نشط الان ', 'تم البدء في طلب'),
(365, '2021-02-18 04:11:06', '2021-02-18 04:11:06', ' your order is complete', 'Your order has been endes ', 64, 203, 0, NULL, '  طلبك مكتمل الان ', 'تم انهاء الطلب'),
(366, '2021-02-18 04:11:56', '2021-02-18 04:11:56', 'order fromرامي ‏عميل', 'see your new orders', 74, 206, 0, NULL, ' تفحص الطلبات الجديدة', 'رامي ‏عميلطلب  من '),
(367, '2021-02-18 04:17:18', '2021-02-18 04:17:18', 'check order', ' check order ', 74, 206, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(368, '2021-02-18 04:17:18', '2021-02-18 04:17:18', 'check order', ' check order ', 64, 206, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(369, '2021-02-18 04:17:28', '2021-02-18 04:17:28', ' your order is active', 'Your order has been initiated ', 64, 206, 0, NULL, '  طلبك نشط الان ', 'تم البدء في طلب'),
(370, '2021-02-18 04:17:46', '2021-02-18 04:17:46', ' your order is complete', 'Your order has been endes ', 64, 206, 0, NULL, '  طلبك مكتمل الان ', 'تم انهاء الطلب'),
(371, '2021-02-18 04:23:09', '2021-02-18 04:23:09', 'order fromرامي ‏عميل', 'see your new orders', 74, 207, 0, NULL, ' تفحص الطلبات الجديدة', 'رامي ‏عميلطلب  من '),
(372, '2021-02-18 04:24:04', '2021-02-18 04:24:04', 'check order', ' check order ', 74, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(373, '2021-02-18 04:24:04', '2021-02-18 04:24:04', 'check order', ' check order ', 64, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(374, '2021-02-18 04:24:20', '2021-02-18 04:24:20', 'check order', ' check order ', 74, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(375, '2021-02-18 04:24:20', '2021-02-18 04:24:20', 'check order', ' check order ', 64, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(376, '2021-02-18 04:24:54', '2021-02-18 04:24:54', 'check order', ' check order ', 74, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(377, '2021-02-18 04:24:54', '2021-02-18 04:24:54', 'check order', ' check order ', 64, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(378, '2021-02-18 04:25:10', '2021-02-18 04:25:10', 'check order', ' check order ', 74, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(379, '2021-02-18 04:25:10', '2021-02-18 04:25:10', 'check order', ' check order ', 64, 207, 0, NULL, 'تفحص الطلب ', 'تفحص الطلب '),
(380, '2021-02-18 04:26:03', '2021-02-18 04:26:03', ' your order is active', 'Your order has been initiated ', 64, 207, 0, NULL, '  طلبك نشط الان ', 'تم البدء في طلب'),
(381, '2021-02-18 04:26:36', '2021-02-18 04:26:36', ' your order is complete', 'Your order has been endes ', 64, 207, 0, NULL, '  طلبك مكتمل الان ', 'تم انهاء الطلب');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `from` time DEFAULT NULL,
  `to` time DEFAULT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `order_shift_id` int(10) UNSIGNED NOT NULL,
  `number_order_in_time` int(11) DEFAULT NULL,
  `employee_note` text COLLATE utf8mb4_unicode_ci,
  `tax` int(11) DEFAULT NULL,
  `work_duration` time DEFAULT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `material_cost` decimal(8,2) DEFAULT NULL,
  `is_paid` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `vedio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_order_accepte_tax` int(11) DEFAULT NULL,
  `notes_on_what_was_done` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complete_in_another_day` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `created_at`, `updated_at`, `category_id`, `user_id`, `price`, `from`, `to`, `employee_id`, `note`, `status`, `date`, `order_shift_id`, `number_order_in_time`, `employee_note`, `tax`, `work_duration`, `location_id`, `latitude`, `longitude`, `material_cost`, `is_paid`, `total`, `vedio`, `completed_order_accepte_tax`, `notes_on_what_was_done`, `complete_in_another_day`) VALUES
(195, '2021-01-26 13:15:42', '2021-01-26 17:53:11', 9, 64, NULL, '18:16:00', '18:19:00', 66, NULL, 4, '2021-01-26', 1, NULL, 'Hy', 1, '00:03:00', 51, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1),
(197, '2021-01-26 17:57:50', '2021-01-27 09:38:38', 9, 72, NULL, NULL, NULL, 66, 'Ac work', 4, '2021-01-27', 2, NULL, 'Chacking', 1, NULL, 55, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0),
(198, '2021-01-31 14:41:18', '2021-02-03 22:11:36', 8, 72, NULL, NULL, NULL, 65, NULL, 4, '2021-02-03', 1, NULL, NULL, 1, NULL, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(199, '2021-01-31 14:43:16', '2021-02-03 22:10:21', 8, 72, NULL, NULL, NULL, 65, NULL, 4, '2021-01-31', 1, NULL, NULL, 1, NULL, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(200, '2021-02-01 20:56:35', '2021-02-02 22:03:27', 7, 64, NULL, NULL, NULL, 74, NULL, 4, '2022-02-02', 1, NULL, 'مزاجي', 1, NULL, 51, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 0),
(201, '2021-02-02 22:08:32', '2021-02-03 09:48:54', 8, 64, 2222.00, '05:13:00', '12:45:00', 74, NULL, 3, '2021-02-03', 1, NULL, NULL, 1, '07:32:00', 51, NULL, NULL, 22.00, 1, 2244.00, NULL, NULL, NULL, 0),
(202, '2021-02-03 11:03:18', '2021-02-03 11:12:25', 8, 64, NULL, NULL, NULL, 74, NULL, 6, '2021-02-03', 1, NULL, NULL, 1, NULL, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(203, '2021-02-03 11:03:36', '2021-02-18 04:14:36', 7, 64, 100.00, '07:09:00', '07:11:00', 74, NULL, 3, '2021-02-18', 1, NULL, NULL, 1, '00:02:00', 51, NULL, NULL, 0.00, 1, 100.00, NULL, NULL, 'ملاحظة', 0),
(204, '2021-02-03 12:42:01', '2021-02-03 22:10:12', 6, 64, NULL, NULL, NULL, 74, NULL, 4, '2021-02-24', 1, NULL, NULL, 1, NULL, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(205, '2021-02-03 22:10:43', '2021-02-03 22:21:51', 8, 64, 15.00, '01:20:00', '01:21:00', 65, 'يا خلود', 3, '2021-02-04', 1, NULL, NULL, 1, '00:01:00', 51, NULL, NULL, 10.00, 1, 25.00, NULL, NULL, NULL, 0),
(206, '2021-02-03 22:12:20', '2021-02-18 04:17:47', 8, 64, 100.00, '07:17:00', '07:17:00', 74, 'يا خلود يا خلود', 3, '2021-02-18', 1, NULL, NULL, 1, '00:00:00', 51, NULL, NULL, 0.00, NULL, 100.00, NULL, NULL, 'تست', 0),
(207, '2021-02-14 17:38:45', '2021-02-18 04:26:56', 8, 64, 100.00, '07:26:00', '07:26:00', 74, NULL, 3, '2021-02-18', 1, NULL, NULL, 1, '00:00:00', 51, NULL, NULL, 0.00, 1, 100.00, NULL, NULL, 'test', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_shifts`
--

CREATE TABLE `order_shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `from` time NOT NULL,
  `to` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_shifts`
--

INSERT INTO `order_shifts` (`id`, `name`, `from`, `to`, `created_at`, `updated_at`) VALUES
(1, NULL, '08:00:00', '12:00:00', '2020-10-26 12:25:07', '2021-01-10 20:47:57'),
(2, NULL, '16:00:00', '20:00:00', '2020-10-26 12:42:28', '2021-01-10 20:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `description_ar`, `name_ar`, `display_name_ar`, `routes`, `created_at`, `updated_at`) VALUES
(1, 'all category', 'all category', 'show all category', 'عرض كل الاقسام', 'كل الاقسام', 'كل الاقسام', 'categories.index', NULL, NULL),
(2, 'add catgeory', 'add catgeory', NULL, NULL, 'اضافه قسم', 'اضافه قسم', 'categories.create,categories.store', NULL, NULL),
(3, 'edit category', 'edit category', NULL, NULL, 'تعديل الاقسام', 'تعديل الاقسام', 'categories.edit,categories.update', NULL, NULL),
(4, 'delete category', 'delete category', NULL, NULL, 'حذف الاقسام', 'حذف الاقسام', 'deleteCategory', NULL, NULL),
(5, 'change-logo', 'change-logo', NULL, NULL, 'تغيير شعار الموقع', 'تغيير شعار الموقع', 'change_logo,changeLogo.store', NULL, NULL),
(6, 'settings', 'settings', NULL, NULL, 'اعدادات الموقع', 'اعدادات الموقع', 'setting.showPage,setting_storeFun,store_TermsAndConditions,store_aboutus', NULL, NULL),
(7, 'contact-us', 'contact-us', 'show contact-us page', 'مشاهده صفحه تواصل معنا', 'تواصل معنا', 'تواصل معنا', 'Contact', NULL, NULL),
(8, 'delete contact-us', 'delete contact-us', 'delete', 'حذف', 'حذف الرساله', 'حذف الرساله', 'deleteContact', NULL, NULL),
(9, 'show all complaint', 'show all complaint', NULL, NULL, 'مشاهده الشكاوي', 'مشاهده الشكاوي', 'all_complaint', NULL, NULL),
(10, 'show-orders', 'show-orders', NULL, NULL, 'مشاهده-الطلبات ', 'مشاهده-الطلبات ', 'new_orders,active_orders,completed_orders,completed_ordersNotPaid,canceled_orders,create_order,create_orderPage,edit_orderPage,show_orderPage,update_orderPage,remove_image,changeOrderStatus,redirect_order_to_another_employeePage,redirect_order_to_another_', NULL, NULL),
(11, 'sentences', 'sentences', NULL, NULL, 'جمل-التقييمات', 'جمل-التقييمات', 'sentences.index,sentences.create,sentences,store,sentences.edit,sentences.update', NULL, NULL),
(12, 'delete-sentences', 'delete-sentences', NULL, NULL, 'حذف-التقييم', 'حذف-التقييم', 'deleteSentence', NULL, NULL),
(13, 'attendance', 'attendance', NULL, NULL, 'الحضور والانصراف', 'الحضور والانصراف', 'attendance.index,attendance.show', NULL, NULL),
(14, 'order-Shifts', 'order-Shifts', NULL, NULL, 'الفترات', 'الفترات', 'orderShifts.index,orderShifts.create,orderShifts.store,orderShifts.edit,orderShifts.update', NULL, NULL),
(15, 'delete-order-Shift', 'delete-order-Shift', NULL, NULL, 'حذف الفترة', 'حذف الفترة', 'deleteOrderShift', NULL, NULL),
(16, 'category-notifications', 'category-notifications', NULL, NULL, 'الاشعارات المخصصه', 'الاشعارات المخصصه', 'notifications.categoryPage,notifications.category,notifications.userPage,notifications.user', NULL, NULL),
(17, 'general-notifications', 'general-notifications', NULL, NULL, 'اشعارات عامه', 'اشعارات عامه', 'notifications.generalPage,notifications.general', NULL, NULL),
(18, 'show branches', 'show branches', NULL, NULL, 'مشاهده الفروع', 'مشاهده الفروع', 'branches.index', NULL, NULL),
(19, 'add branches', 'add branches', NULL, NULL, 'اضافه فرع', 'اضافه فرع', 'branches.create,branches.store', NULL, NULL),
(20, 'edit branch', 'edit branch', NULL, NULL, 'تعديل الفرع', 'تعديل الفرع', 'branches.edit,branches.update', NULL, NULL),
(21, 'delete branch', 'delete branch', NULL, NULL, 'حذف الفرع', 'حذف الفرع', 'deleteBranch', NULL, NULL),
(22, 'roles', 'roles', NULL, NULL, 'الخصوصية', 'الخصوصية', 'roles.index,roles.create,roles.store,roles.edit,roles.update,delete_role', NULL, NULL),
(23, 'employee', 'employee', NULL, NULL, 'الموظفيين', 'الموظفيين', 'show_all_employees,add_new_employeePage,add_new_employee,edit_employee,update_employee,update_passEmployee,update_privacyEmployee,delete_employee', NULL, NULL),
(24, 'clients', 'clients', NULL, NULL, 'العملاء', 'العملاء', 'show_all_clients,add_clientPage,add_client,edit_clientPage,update_client,location_client,privacy_client', NULL, NULL),
(25, 'admins', 'admins', NULL, NULL, 'المديريين', 'المديريين', 'admins.index,admins.create,admins.store,admins.edit,admins.update', NULL, NULL),
(26, 'delete-admin', 'delete-admin', NULL, NULL, 'حذف مدير', 'حذف مدير', 'admin_delete', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`, `id`) VALUES
(1, 7, 13),
(2, 7, 14),
(3, 7, 15),
(4, 7, 20),
(5, 7, 21),
(6, 7, 22),
(7, 7, 23),
(8, 7, 24),
(9, 7, 25),
(10, 7, 26),
(11, 7, 27),
(12, 7, 28),
(13, 7, 29),
(14, 7, 30),
(15, 7, 31),
(16, 7, 32),
(17, 7, 33),
(18, 7, 34),
(19, 7, 36),
(20, 7, 37),
(21, 7, 38),
(22, 7, 39),
(23, 7, 40),
(24, 7, 41),
(25, 7, 42),
(26, 7, 43),
(10, 10, 44);

-- --------------------------------------------------------

--
-- Table structure for table `phone_verifications`
--

CREATE TABLE `phone_verifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(199) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phone_verifications`
--

INSERT INTO `phone_verifications` (`id`, `code`, `phone`, `created_at`, `updated_at`) VALUES
(64, '4306', '00966542473217', '2021-01-26 12:40:48', '2021-01-26 12:40:48'),
(65, '2044', '00966583186389', '2021-01-26 12:43:39', '2021-01-26 12:43:39'),
(66, '9833', '00966591889935', '2021-01-26 12:44:25', '2021-01-26 12:44:25'),
(67, '4714', '00966544273217', '2021-01-26 12:44:32', '2021-01-26 12:44:32'),
(68, '9030', '00966563352256', '2021-01-26 12:44:42', '2021-01-26 12:44:42'),
(69, '5935', '00966544273217', '2021-01-26 12:45:07', '2021-01-26 12:45:07'),
(70, '4971', '00966572565630', '2021-01-26 12:45:43', '2021-01-26 12:45:43'),
(71, '3863', '00966544273217', '2021-01-26 12:46:16', '2021-01-26 12:46:16'),
(72, '2010', '00966572565630', '2021-01-26 12:46:51', '2021-01-26 12:46:51'),
(73, '7905', '00966580724241', '2021-01-26 12:47:15', '2021-01-26 12:47:15'),
(74, '8998', '00966542473217', '2021-01-26 12:47:34', '2021-01-26 12:47:34'),
(75, '9299', '00966572565630', '2021-01-26 12:48:09', '2021-01-26 12:48:09'),
(76, '9784', '00966572565630', '2021-01-26 12:52:54', '2021-01-26 12:52:54'),
(77, '9537', '00966583004444', '2021-01-26 12:53:22', '2021-01-26 12:53:22'),
(78, '1039', '00966527655630', '2021-01-26 12:55:06', '2021-01-26 12:55:06'),
(79, '4033', '00966527655630', '2021-01-26 12:56:13', '2021-01-26 12:56:13'),
(80, '6372', '00966527655630', '2021-01-26 12:57:29', '2021-01-26 12:57:29'),
(81, '2342', '00966572655630', '2021-01-26 12:59:00', '2021-01-26 12:59:00'),
(82, '2072', '00966572655630', '2021-01-26 13:00:24', '2021-01-26 13:00:24'),
(83, '4425', '00966572655630', '2021-01-26 13:01:32', '2021-01-26 13:01:32'),
(84, '9156', '00966572655630', '2021-01-26 13:02:50', '2021-01-26 13:02:50'),
(85, '6796', '00966572655630', '2021-01-26 13:03:54', '2021-01-26 13:03:54'),
(86, '8966', '00966572655630', '2021-01-26 13:05:18', '2021-01-26 13:05:18'),
(87, '5035', '00966572655630', '2021-01-26 13:06:23', '2021-01-26 13:06:23'),
(88, '7350', '00966572655630', '2021-01-26 13:07:27', '2021-01-26 13:07:27'),
(89, '9985', '00966572655630', '2021-01-26 13:09:09', '2021-01-26 13:09:09'),
(90, '4824', '00966572655630', '2021-01-26 13:10:29', '2021-01-26 13:10:29'),
(91, '8960', '00966545571465', '2021-01-26 13:12:20', '2021-01-26 13:12:20'),
(92, '1735', '00966572556530', '2021-01-26 13:12:34', '2021-01-26 13:12:34'),
(93, '6450', '00966572655630', '2021-01-26 13:14:59', '2021-01-26 13:14:59'),
(94, '6637', '00966572655630', '2021-01-26 13:16:43', '2021-01-26 13:16:43'),
(95, '1893', '00966545571465', '2021-01-26 13:18:00', '2021-01-26 13:18:00'),
(96, '8900', '00966572655630', '2021-01-26 13:18:10', '2021-01-26 13:18:10'),
(97, '3407', '00966572655630', '2021-01-26 13:26:30', '2021-01-26 13:26:30'),
(98, '3434', '00966572655630', '2021-01-26 13:31:31', '2021-01-26 13:31:31'),
(99, '8146', '00966572655630', '2021-01-26 13:47:43', '2021-01-26 13:47:43'),
(100, '5370', '00966572655630', '2021-01-26 13:48:51', '2021-01-26 13:48:51'),
(101, '3576', '00966572655630', '2021-01-26 13:53:37', '2021-01-26 13:53:37'),
(102, '6701', '00966572655630', '2021-01-26 13:55:26', '2021-01-26 13:55:26'),
(103, '5713', '00966572655630', '2021-01-26 14:01:48', '2021-01-26 14:01:48'),
(104, '6856', '00966572655630', '2021-01-26 14:04:58', '2021-01-26 14:04:58'),
(105, '2304', '00966572655630', '2021-01-26 14:08:34', '2021-01-26 14:08:34'),
(106, '1603', '00966572655630', '2021-01-26 15:50:03', '2021-01-26 15:50:03'),
(107, '1457', '00966545571465', '2021-01-26 16:13:08', '2021-01-26 16:13:08'),
(108, '1523', '00966542473217', '2021-01-26 16:14:06', '2021-01-26 16:14:06'),
(109, '5623', '00966545571465', '2021-01-26 16:22:54', '2021-01-26 16:22:54'),
(110, '9951', '00966545571465', '2021-01-26 16:24:36', '2021-01-26 16:24:36'),
(111, '5975', '00966580724241', '2021-01-26 17:54:00', '2021-01-26 17:54:00'),
(112, '5445', '0096650000000000', '2021-01-31 12:07:03', '2021-01-31 12:07:03'),
(113, '4146', '009665000000000000', '2021-01-31 12:09:55', '2021-01-31 12:09:55'),
(114, '1969', '009665000000000000', '2021-01-31 12:30:50', '2021-01-31 12:30:50'),
(115, '9816', '00966500000000', '2021-01-31 12:33:58', '2021-01-31 12:33:58'),
(116, '6916', '0096659794935832', '2021-01-31 12:38:09', '2021-01-31 12:38:09'),
(117, '4929', '009665000000000', '2021-01-31 12:42:03', '2021-01-31 12:42:03'),
(118, '5832', '009665694935866', '2021-02-04 11:34:36', '2021-02-04 11:34:36'),
(119, '6577', '00966553484988', '2021-02-10 09:22:09', '2021-02-10 09:22:09'),
(120, '8898', '00966568098305', '2021-02-10 12:14:29', '2021-02-10 12:14:29'),
(121, '4920', '00966565555844', '2021-02-10 21:23:29', '2021-02-10 21:23:29'),
(122, '4839', '00966508121111', '2021-02-12 11:52:44', '2021-02-12 11:52:44'),
(123, '5959', '00966508121111', '2021-02-12 11:57:09', '2021-02-12 11:57:09'),
(124, '8444', '00966508121111', '2021-02-12 12:11:30', '2021-02-12 12:11:30'),
(125, '4826', '00966565555844', '2021-02-12 14:00:19', '2021-02-12 14:00:19'),
(126, '4238', '00966569823332', '2021-02-13 11:56:03', '2021-02-13 11:56:03'),
(127, '1480', '00966539411188', '2021-02-14 10:46:32', '2021-02-14 10:46:32'),
(128, '9399', '00966555810036', '2021-02-14 14:03:17', '2021-02-14 14:03:17'),
(129, '1216', '00966555810039', '2021-02-14 14:04:36', '2021-02-14 14:04:36'),
(130, '5642', '00966555810039', '2021-02-14 14:05:40', '2021-02-14 14:05:40'),
(131, '9620', '00966552566700', '2021-02-16 17:19:48', '2021-02-16 17:19:48'),
(132, '8048', '009665404448070', '2021-02-16 20:20:33', '2021-02-16 20:20:33'),
(133, '1053', '00966597123966', '2021-02-17 14:10:12', '2021-02-17 14:10:12'),
(134, '6429', '00966506942894', '2021-02-18 01:03:40', '2021-02-18 01:03:40'),
(135, '3123', '00966508121111', '2021-02-18 15:04:36', '2021-02-18 15:04:36'),
(136, '2201', '00966564903000', '2021-02-18 15:11:01', '2021-02-18 15:11:01'),
(137, '4774', '00966508121111', '2021-02-19 07:39:33', '2021-02-19 07:39:33'),
(138, '8658', '00966500512855', '2021-02-19 16:08:10', '2021-02-19 16:08:10'),
(139, '9103', '00966500512855', '2021-02-19 16:13:32', '2021-02-19 16:13:32'),
(140, '7198', '00966569823332', '2021-02-20 11:16:25', '2021-02-20 11:16:25'),
(141, '2606', '00966554244207', '2021-02-23 07:26:11', '2021-02-23 07:26:11'),
(142, '5390', '00966535899848', '2021-02-23 14:02:02', '2021-02-23 14:02:02'),
(143, '8131', '00966535899848', '2021-02-23 14:09:59', '2021-02-23 14:09:59'),
(144, '8215', '00966553896372', '2021-02-23 22:36:43', '2021-02-23 22:36:43'),
(145, '4359', '00966553896372', '2021-02-23 22:43:39', '2021-02-23 22:43:39'),
(146, '6434', '00966556702466', '2021-02-24 14:18:28', '2021-02-24 14:18:28'),
(147, '6931', '00966533357780', '2021-02-25 08:19:25', '2021-02-25 08:19:25'),
(148, '5136', '00966552992261', '2021-02-25 14:44:32', '2021-02-25 14:44:32'),
(149, '7282', '00966535899848', '2021-02-25 19:06:03', '2021-02-25 19:06:03'),
(150, '6303', '00966555493976', '2021-02-25 19:28:43', '2021-02-25 19:28:43'),
(151, '1270', '00966555493976', '2021-02-25 19:29:51', '2021-02-25 19:29:51'),
(152, '2608', '00966555493976', '2021-02-25 19:31:04', '2021-02-25 19:31:04'),
(153, '6253', '00966565555844', '2021-02-26 08:03:13', '2021-02-26 08:03:13'),
(154, '1640', '00966565555844', '2021-02-26 08:09:55', '2021-02-26 08:09:55'),
(155, '9043', '00966565555844', '2021-02-26 12:43:05', '2021-02-26 12:43:05'),
(156, '6404', '00966553896372', '2021-02-27 12:45:45', '2021-02-27 12:45:45'),
(157, '4503', '00966544517761', '2021-02-27 13:53:42', '2021-02-27 13:53:42'),
(158, '5656', '00966531224422', '2021-03-01 08:53:08', '2021-03-01 08:53:08'),
(159, '7827', '00966556239688', '2021-03-01 17:18:48', '2021-03-01 17:18:48'),
(160, '8583', '00966500120111', '2021-03-02 04:47:38', '2021-03-02 04:47:38'),
(161, '9408', '00966500512855', '2021-03-03 13:17:34', '2021-03-03 13:17:34'),
(162, '5610', '00966500512855', '2021-03-03 14:28:55', '2021-03-03 14:28:55'),
(163, '7438', '00966500512855', '2021-03-03 17:33:50', '2021-03-03 17:33:50'),
(164, '3275', '00966500512855', '2021-03-03 17:46:32', '2021-03-03 17:46:32'),
(165, '3106', '00966588888882', '2021-03-03 20:25:53', '2021-03-03 20:25:53'),
(166, '8511', '00966588888886', '2021-03-03 20:45:58', '2021-03-03 20:45:58'),
(167, '8938', '00966500512855', '2021-03-03 20:55:18', '2021-03-03 20:55:18'),
(168, '7793', '009665632145698', '2021-03-03 21:09:19', '2021-03-03 21:09:19'),
(169, '1312', '009665654863215', '2021-03-03 21:12:08', '2021-03-03 21:12:08'),
(170, '1523', '00966543611643', '2021-03-05 01:53:48', '2021-03-05 01:53:48'),
(171, '3392', '00966566145016', '2021-03-05 11:50:48', '2021-03-05 11:50:48'),
(172, '5478', '00966566145016', '2021-03-05 11:53:02', '2021-03-05 11:53:02'),
(173, '9835', '00966500120111', '2021-03-05 13:45:24', '2021-03-05 13:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

CREATE TABLE `rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rate` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rates`
--

INSERT INTO `rates` (`id`, `created_at`, `updated_at`, `rate`, `user_id`, `order_id`, `comment`) VALUES
(55, '2021-02-03 09:49:43', '2021-02-03 09:49:43', 3, 64, 201, NULL),
(56, '2021-02-03 09:53:26', '2021-02-03 09:53:26', 1, 64, 200, NULL),
(57, '2021-02-03 22:23:14', '2021-02-03 22:23:14', 3, 64, 205, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rate_sentence`
--

CREATE TABLE `rate_sentence` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rate_id` int(10) UNSIGNED NOT NULL,
  `sentence_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rate_sentence`
--

INSERT INTO `rate_sentence` (`id`, `created_at`, `updated_at`, `rate_id`, `sentence_id`) VALUES
(94, NULL, NULL, 55, 2),
(95, NULL, NULL, 56, 1),
(96, NULL, NULL, 57, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rejected_dates`
--

CREATE TABLE `rejected_dates` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reject_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reason_en` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rejected_dates`
--

INSERT INTO `rejected_dates` (`id`, `created_at`, `updated_at`, `reject_date`, `reason`, `reason_en`) VALUES
(3, '2021-01-10 20:47:05', '2021-01-13 21:06:31', '2021-01-15', 'يوم جمعة', 'sgsf'),
(4, '2021-01-12 08:04:29', '2021-01-13 21:06:40', '2021-01-20', 'العيد الوطني', 'dfhdf'),
(5, '2021-01-26 13:05:17', '2021-01-26 13:05:17', '2021-01-30', 'اجازة اسبوعية', 'test test');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `display_name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`, `display_name_ar`, `name_ar`, `description_ar`) VALUES
(7, 'owner', 'manager', 'manage all system', '2020-11-26 20:07:36', '2020-11-26 21:21:03', 'المدير', 'الowner', 'manage all system'),
(10, 'ادارة الطلبات', 'ادارة الطلبات', 'ادارة الطلبات', '2020-11-28 09:52:35', '2020-11-28 09:52:35', 'ادارة الطلبات', 'ادارة الطلبات', 'ادارة الطلبات');

-- --------------------------------------------------------

--
-- Table structure for table `sentences`
--

CREATE TABLE `sentences` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sentence` varchar(255) DEFAULT NULL,
  `sentence_ar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sentences`
--

INSERT INTO `sentences` (`id`, `created_at`, `updated_at`, `sentence`, `sentence_ar`) VALUES
(1, '2020-11-16 08:44:31', '2020-11-16 08:48:30', 'clean after work', 'التنظيف بعد العمل'),
(2, '2020-11-16 08:45:11', '2020-11-16 09:00:27', 'all tools collected', 'كل الادوات كانت موجوده');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term` text COLLATE utf8mb4_unicode_ci,
  `condition` text COLLATE utf8mb4_unicode_ci,
  `tax` decimal(10,2) DEFAULT NULL,
  `terms_ar` text COLLATE utf8mb4_unicode_ci,
  `condition_ar` text COLLATE utf8mb4_unicode_ci,
  `search_range` int(11) DEFAULT NULL,
  `shift_range` int(11) DEFAULT NULL,
  `about_us` text COLLATE utf8mb4_unicode_ci,
  `about_us_ar` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count_of_order_in_period` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `company_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accept_tax` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accept_tax_en` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_for_completed_order` int(10) DEFAULT '0',
  `cancel_order` int(11) NOT NULL DEFAULT '1',
  `tax_for_completed_order_active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `created_at`, `updated_at`, `phone`, `whatsapp`, `term`, `condition`, `tax`, `terms_ar`, `condition_ar`, `search_range`, `shift_range`, `about_us`, `about_us_ar`, `image`, `count_of_order_in_period`, `latitude`, `longitude`, `company_name`, `route_name`, `city_name`, `country_name`, `accept_tax`, `accept_tax_en`, `company_name_en`, `route_name_en`, `city_name_en`, `country_name_en`, `tax_for_completed_order`, `cancel_order`, `tax_for_completed_order_active`) VALUES
(1, NULL, '2021-01-26 16:00:36', '0583023083', '0583023083', 'احكام الموقع بس بالانجليزي', 'شروط الموقع بس بالانجليزي', 50.00, 'الاحكام', 'الشروط', 3000, 500, 'من نحن :هنكتب هنا شويه بيانات بس بالانجليزي', 'من نحن :هنكتب هنا شويه بيانات', 'logo_1610578064.png', 2, 26.43920000, 50.09440000, 'شركة نطاقات الراحة المحدودة', 'طريق الامير نايف بن عبدالعزيز', 'ح الواحة - الدمام', 'المملكة العربية السعودية', 'الموافقة علي دفع الكشفية ٥٠ ريال', 'The text is next to the tax price', 'شركة نطاقات الراحة المحدودة', 'طريق الامير نايف بن عبدالعزيز', 'ح الواحة - الدمام', 'المملكة العربية السعودية', 15, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `from` time DEFAULT NULL,
  `to` time DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `created_at`, `updated_at`, `image`, `link`, `category_id`) VALUES
(6, '2021-01-15 00:52:36', '2021-01-15 01:19:49', 'slider_1610676840.png', NULL, 7),
(7, '2021-01-15 00:53:07', '2021-01-15 00:53:07', 'slider_1610675587.png', NULL, 11),
(8, '2021-01-15 00:57:56', '2021-01-15 01:19:27', 'slider_1610675876.png', NULL, 8);

-- --------------------------------------------------------

--
-- Table structure for table `time_orders`
--

CREATE TABLE `time_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `date` date NOT NULL,
  `order_shift_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `time_orders`
--

INSERT INTO `time_orders` (`id`, `created_at`, `updated_at`, `order_id`, `start`, `end`, `date`, `order_shift_id`) VALUES
(23, '2021-01-26 14:19:06', '2021-01-26 14:19:06', 195, NULL, NULL, '2021-01-28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `available_orders` int(11) NOT NULL DEFAULT '0',
  `language` enum('en','ar') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created_at`, `updated_at`, `phone`, `name`, `active`, `type`, `password`, `api_token`, `verification_code`, `branch_id`, `remember_token`, `image`, `available_orders`, `language`) VALUES
(64, '2021-01-26 12:45:25', '2021-02-18 14:48:12', '0591889935', 'رامي ‏عميل', 1, '1', '$2y$10$l453rgUqzlzQoeTCn7sZg.41pMnnKWmenYWutK8xWl4l4v2vT2/uy', '4096fsvQu3q8kr', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(65, '2021-01-26 12:45:30', '2021-02-03 22:12:56', '0563352256', 'فهد ‏', 1, '2', '$2y$10$06bYOqDgHi3sTBSjTlM.uewJKfE7ZQyrsZHUEiHixK7C0NyAyh/ri', '4225glp83NhEz5', NULL, 5, NULL, 'default.png', 0, 'ar'),
(66, '2021-01-26 12:45:54', '2021-01-26 12:47:51', '0583186389', '‏Muhammad ‎ijaz', 1, '2', '$2y$10$NCA.si6XnfjpT.Ll3DnRcOVGN2fpKKa6kaiK.P3fRYMUHM4IDG6k2', '4356Isgpo3QvSR', NULL, 5, NULL, 'users_1611668871.jpg', 0, 'ar'),
(69, '2021-01-26 12:54:36', '2021-01-26 12:54:36', '0583004444', 'فايز ‏القرني', 1, '1', '$2y$10$X8Jc1FgIkxgawh93WZpiGuKPH8zI9V.0jtlpN33jOTp3mfPAe52Uu', '47619p0XO4CSRA', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(70, '2021-01-26 16:16:35', '2021-01-26 16:16:35', '0542473217', 'aron', 1, '1', '$2y$10$SySWY92DOUuZNJd950BdSegeKv1nResNspEMEC.4i3talKn4/mNFS', '4900GZ2KR6BocV', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(71, '2021-01-26 16:26:32', '2021-01-28 13:31:40', '0545571465', 'junil', 1, '1', '$2y$10$O/3ZhtRyXtcUXnU.uMAa2.AE0t3tZajI3YHF6LY/jWDDu04SeczqC', '5041lRy78V9MGP', '1597', NULL, NULL, 'default.png', 0, 'ar'),
(72, '2021-01-26 17:56:30', '2021-01-30 05:40:36', '0580724241', 'Muhammad ‎', 1, '1', '$2y$10$.PprXU/.f4Z1aNDJp26TceM2Qg1QRi6gaqKSNlJXt1xfn62TK9czC', '5184ZsoACuzHLi', '4487', NULL, NULL, 'default.png', 0, 'ar'),
(73, '2021-01-31 12:41:01', '2021-01-31 12:41:01', '059794935832', 'khlood-swidan', 0, '2', '$2y$10$Mnn5/z.W3RtGfotvYt7p..pJb/yjTiL0IsbTp7nXzIdoQde8Nbxtu', '53298dUFx0QaAv', NULL, 5, NULL, 'default.png', 0, 'ar'),
(74, '2021-01-31 12:43:58', '2021-02-18 14:55:42', '0580491109', 'عبدالله عزمي موظف', 1, '2', '$2y$10$l453rgUqzlzQoeTCn7sZg.41pMnnKWmenYWutK8xWl4l4v2vT2/uy', '54762p6T98Ss5h', '3477', 5, NULL, 'default.png', 0, 'ar'),
(79, '2021-03-03 20:41:45', '2021-03-03 20:41:45', '0588888882', 'khlood-khaled-ahmed-swidan', 1, '1', '$2y$10$9JgaV9QqHuBnZgEUHqYjGezB//rYfL9OekRzDtdJLrZChLa1FN/8e', '6241fX40j3TLIl', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(80, '2021-03-03 20:47:18', '2021-03-03 20:47:18', '0588888886', 'amirKhaledSwidan', 1, '1', '$2y$10$d.RygXddfYr0G3Y.ooRTNuzx8io2bfjiVQz6..Kxl7qaov6LQuHKy', '6400L9GIWY5z3Z', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(81, '2021-03-03 20:56:17', '2021-03-03 20:56:17', '0500512855', 'Saud ‎Alismail', 1, '1', '$2y$10$cGHJb7tPdfK84YLmuqx1meya2GrHmFMd6CJnh7sqsVkqyb7wQCIeW', '6561B6vObDdeNH', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(82, '2021-03-03 21:12:56', '2021-03-03 21:12:56', '05654863215', 'emanemish', 0, '2', '$2y$10$dgNu31LHX2KAGwnPdjnNFeLyqsegZkU6mvYqV2QEBRzbvTu.wLJsG', '6724eFdxsuW8JT', NULL, 5, NULL, 'default.png', 0, 'ar'),
(83, '2021-03-05 01:54:48', '2021-03-05 01:54:48', '0543611643', 'Zainab', 1, '1', '$2y$10$kdnt.5/OSZyX01nQMLWKweL42L82wuCNTIQk6.1oJkbdH.taxJYom', '68895od0Kubsv9', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(84, '2021-03-05 11:54:51', '2021-03-05 11:54:51', '0566145016', 'إيمان', 1, '1', '$2y$10$vGWateo68DX6oMi4JfEEieaHzRmgMnyUHhsUDGsSR56ol52wzyQAi', '7056odA1j6ufK3', NULL, NULL, NULL, 'default.png', 0, 'ar'),
(85, '2021-03-05 13:46:56', '2021-03-05 13:46:56', '0500120111', 'Anwer', 1, '1', '$2y$10$cdu8bIMF.TpSBnHuQi4CW.4JojxBCuzWHs1aNYl2YjitsOycNmf1W', '7225jdRtHiTrlK', NULL, NULL, NULL, 'default.png', 0, 'ar');

-- --------------------------------------------------------

--
-- Table structure for table `with_orders`
--

CREATE TABLE `with_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `main_order_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `with_order_categories`
--

CREATE TABLE `with_order_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bills_order_id_foreign` (`order_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `arranging` (`arranging`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `category_user`
--
ALTER TABLE `category_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_user_category_id_foreign` (`category_id`),
  ADD KEY `category_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_user_id_foreign` (`user_id`),
  ADD KEY `complaints_order_id_foreign` (`order_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devices_user_id_foreign` (`user_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_order_id_foreign` (`order_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `locations_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_order_id_foreign` (`order_id`),
  ADD KEY `notifications_bill_id_foreign` (`bill_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_category_id_foreign` (`category_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_employee_id_foreign` (`employee_id`),
  ADD KEY `order_shift_id` (`order_shift_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `order_shifts`
--
ALTER TABLE `order_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_id` (`permission_id`),
  ADD KEY `permission_role_ibfk_2` (`role_id`);

--
-- Indexes for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rates_user_id_foreign` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `rate_sentence`
--
ALTER TABLE `rate_sentence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rate_id` (`rate_id`),
  ADD KEY `sentence_id` (`sentence_id`);

--
-- Indexes for table `rejected_dates`
--
ALTER TABLE `rejected_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sentences`
--
ALTER TABLE `sentences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shifts_user_id_foreign` (`user_id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `time_orders`
--
ALTER TABLE `time_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_shift_id` (`order_shift_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `with_orders`
--
ALTER TABLE `with_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `main_order_id` (`main_order_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `with_order_categories`
--
ALTER TABLE `with_order_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_role`
--
ALTER TABLE `admin_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `category_user`
--
ALTER TABLE `category_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `order_shifts`
--
ALTER TABLE `order_shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `rates`
--
ALTER TABLE `rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `rate_sentence`
--
ALTER TABLE `rate_sentence`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `rejected_dates`
--
ALTER TABLE `rejected_dates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sentences`
--
ALTER TABLE `sentences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `time_orders`
--
ALTER TABLE `time_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `with_orders`
--
ALTER TABLE `with_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `with_order_categories`
--
ALTER TABLE `with_order_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_role`
--
ALTER TABLE `admin_role`
  ADD CONSTRAINT `admin_role_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category_user`
--
ALTER TABLE `category_user`
  ADD CONSTRAINT `category_user_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`order_shift_id`) REFERENCES `order_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rates`
--
ALTER TABLE `rates`
  ADD CONSTRAINT `rates_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rate_sentence`
--
ALTER TABLE `rate_sentence`
  ADD CONSTRAINT `rate_sentence_ibfk_1` FOREIGN KEY (`rate_id`) REFERENCES `rates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rate_sentence_ibfk_2` FOREIGN KEY (`sentence_id`) REFERENCES `sentences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shifts`
--
ALTER TABLE `shifts`
  ADD CONSTRAINT `shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sliders`
--
ALTER TABLE `sliders`
  ADD CONSTRAINT `sliders_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `time_orders`
--
ALTER TABLE `time_orders`
  ADD CONSTRAINT `time_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `time_orders_ibfk_2` FOREIGN KEY (`order_shift_id`) REFERENCES `order_shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `with_orders`
--
ALTER TABLE `with_orders`
  ADD CONSTRAINT `with_orders_ibfk_1` FOREIGN KEY (`main_order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `with_orders_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `with_order_categories`
--
ALTER TABLE `with_order_categories`
  ADD CONSTRAINT `with_order_categories_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `with_order_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
