-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 27, 2025 lúc 04:36 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `thuctap`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dai_lies`
--

CREATE TABLE `dai_lies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_dai_ly` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(255) NOT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dai_lies`
--

INSERT INTO `dai_lies` (`id`, `ten_dai_ly`, `email`, `so_dien_thoai`, `dia_chi`, `created_at`, `updated_at`) VALUES
(1, 'Adam', 'adam@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(2, 'Vé xe rẻ', 'vexe@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(3, 'Redbus', 'redbus@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(4, 'Mobitrip', 'mobitrip@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(5, 'WEB/APP', 'webapp@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(6, 'QR', 'qr@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(7, 'Các văn phòng', 'offices@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(8, 'Vé thương gia', 'thuonggia@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(9, 'Distribusion', 'distribusion@example.com', '0123456789', 'HCM', '2025-05-25 10:04:00', '2025-05-25 10:04:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Marketing', 'Phòng ban phụ trách marketing', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(2, 'Sales', 'Phòng ban phụ trách bán hàng', '2025-05-23 12:14:38', '2025-05-23 12:14:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `employees`
--

INSERT INTO `employees` (`id`, `department_id`, `role_id`, `name`, `position`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'John Doe', 'Chuyên viên Marketing', 'khoaebanypk03641@gmail.com', '$2y$12$vsgKYK0qK0kVSwdzLZbwie5YiSJWa/GTZxK4CZh1cJVhLsJBL4Iym', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(2, 1, 2, 'Jane Smith', 'Quản lý Marketing', 'ykhoa11a13@gmail.com', '$2y$12$n.T0n0fU7rAfw2FsBfHUkugcgQxf4sKTAzdvrrKgKGvpJxW1qBQqu', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(3, 1, 3, 'Admin User', 'Quản trị viên', 'dauxanh008@gmail.com', '$2y$12$xQFA1qlqWuWyT54d5rwr../lLjDcYwxTEhdWl.mfsLiRglu4IzZVm', '2025-05-23 12:14:38', '2025-05-23 12:14:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `financial_record_id` bigint(20) UNSIGNED NOT NULL,
  `expense_type_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `expenses`
--

INSERT INTO `expenses` (`id`, `financial_record_id`, `expense_type_id`, `amount`, `description`, `created_at`, `updated_at`) VALUES
(5, 9, 2, 59000000.00, 'gsergsergsrg', '2025-05-27 05:16:20', '2025-05-27 05:16:20'),
(6, 10, 1, 10000000.00, 'êfefef', '2025-05-27 07:21:41', '2025-05-27 07:21:41');

--
-- Bẫy `expenses`
--
DELIMITER $$
CREATE TRIGGER `calculate_roas_after_expense` AFTER INSERT ON `expenses` FOR EACH ROW BEGIN
                DECLARE total_expenses DECIMAL(15, 2);
                DECLARE revenue_amount DECIMAL(15, 2);

                SELECT SUM(amount) INTO total_expenses
                FROM expenses
                WHERE financial_record_id = NEW.financial_record_id;

                SELECT revenue INTO revenue_amount
                FROM financial_records
                WHERE id = NEW.financial_record_id;

                IF total_expenses > 0 THEN
                    UPDATE financial_records
                    SET roas = revenue_amount / total_expenses
                    WHERE id = NEW.financial_record_id;
                ELSE
                    UPDATE financial_records
                    SET roas = NULL
                    WHERE id = NEW.financial_record_id;
                END IF;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_roas_after_expense_delete` AFTER DELETE ON `expenses` FOR EACH ROW BEGIN
                DECLARE total_expenses DECIMAL(15, 2);
                DECLARE revenue_amount DECIMAL(15, 2);

                SELECT SUM(amount) INTO total_expenses
                FROM expenses
                WHERE financial_record_id = OLD.financial_record_id;

                SELECT revenue INTO revenue_amount
                FROM financial_records
                WHERE id = OLD.financial_record_id;

                IF total_expenses > 0 THEN
                    UPDATE financial_records
                    SET roas = revenue_amount / total_expenses
                    WHERE id = OLD.financial_record_id;
                ELSE
                    UPDATE financial_records
                    SET roas = NULL
                    WHERE id = OLD.financial_record_id;
                END IF;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_roas_after_expense_update` AFTER UPDATE ON `expenses` FOR EACH ROW BEGIN
                DECLARE total_expenses DECIMAL(15, 2);
                DECLARE revenue_amount DECIMAL(15, 2);

                SELECT SUM(amount) INTO total_expenses
                FROM expenses
                WHERE financial_record_id = NEW.financial_record_id;

                SELECT revenue INTO revenue_amount
                FROM financial_records
                WHERE id = NEW.financial_record_id;

                IF total_expenses > 0 THEN
                    UPDATE financial_records
                    SET roas = revenue_amount / total_expenses
                    WHERE id = NEW.financial_record_id;
                ELSE
                    UPDATE financial_records
                    SET roas = NULL
                    WHERE id = NEW.financial_record_id;
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `expense_types`
--

CREATE TABLE `expense_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `expense_types`
--

INSERT INTO `expense_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Chi phí quảng cáo', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(2, 'Chi phí vận hành', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(3, 'Chi phí nhân sự', '2025-05-23 12:14:38', '2025-05-23 12:14:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `financial_records`
--

CREATE TABLE `financial_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `platform_id` bigint(20) UNSIGNED NOT NULL,
  `dai_ly_id` bigint(20) UNSIGNED NOT NULL,
  `office_id` bigint(20) UNSIGNED NOT NULL,
  `revenue` decimal(15,2) NOT NULL,
  `commission` decimal(15,2) DEFAULT 0.00,
  `roas` decimal(15,2) DEFAULT NULL,
  `record_date` date NOT NULL,
  `record_time` time NOT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `financial_records`
--

INSERT INTO `financial_records` (`id`, `department_id`, `platform_id`, `dai_ly_id`, `office_id`, `revenue`, `commission`, `roas`, `record_date`, `record_time`, `note`, `status`, `submitted_by`, `created_at`, `updated_at`) VALUES
(9, 1, 2, 1, 1, 150000000.00, 150000000.00, 2.54, '2025-05-27', '19:15:00', '{\"note\":\"wrfgwrewrvfg\",\"revenue_sources\":[{\"source_name\":\"Adam\",\"amount\":150000000}]}', 'pending', 1, '2025-05-27 05:16:20', '2025-05-27 05:16:20'),
(10, 1, 2, 2, 2, 15000000.00, 15000000000.00, 1.50, '2025-05-27', '21:21:00', '{\"note\":\"âefaefaefaefe\",\"revenue_sources\":[{\"source_name\":\"Google\",\"amount\":15000000}]}', 'pending', 1, '2025-05-27 07:21:41', '2025-05-27 07:21:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `financial_targets`
--

CREATE TABLE `financial_targets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_amount` decimal(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `financial_targets`
--

INSERT INTO `financial_targets` (`id`, `year`, `department_id`, `target_amount`, `created_at`, `updated_at`) VALUES
(1, '2025', 1, 35017977699.00, '2025-05-25 10:04:00', '2025-05-25 10:04:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `metric_values`
--

CREATE TABLE `metric_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `metric_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `recorded_at` timestamp NULL DEFAULT NULL,
  `financial_record_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `metric_values`
--

INSERT INTO `metric_values` (`id`, `metric_id`, `value`, `recorded_at`, `financial_record_id`, `created_at`, `updated_at`) VALUES
(1, 1, '15000', '2025-05-27 12:15:00', NULL, '2025-05-27 05:16:20', '2025-05-27 05:16:20'),
(2, 1, '15000', '2025-05-27 14:21:00', NULL, '2025-05-27 07:21:41', '2025-05-27 07:21:41'),
(3, 1, '15000', '2025-05-27 14:31:00', NULL, '2025-05-27 07:31:44', '2025-05-27 07:31:44'),
(4, 1, '15000', '2025-05-27 14:33:00', NULL, '2025-05-27 07:34:14', '2025-05-27 07:34:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_05_25_102015_create_thuctap_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `offices`
--

CREATE TABLE `offices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `offices`
--

INSERT INTO `offices` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'VP 49', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(2, 'VP BT', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(3, 'VP CMG', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(4, 'VP Q5', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(5, 'VP ĐL', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(6, 'VP NT', '2025-05-25 10:04:00', '2025-05-25 10:04:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `office_revenues`
--

CREATE TABLE `office_revenues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `office_id` bigint(20) UNSIGNED NOT NULL,
  `cash` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bank_transfer` decimal(15,2) NOT NULL DEFAULT 0.00,
  `expense` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `record_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `office_revenues`
--

INSERT INTO `office_revenues` (`id`, `office_id`, `cash`, `bank_transfer`, `expense`, `total`, `record_date`, `created_at`, `updated_at`) VALUES
(1, 1, 155876000.00, 131143000.00, 17949000.00, 378786000.00, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(2, 1, 124920000.00, 74955000.00, 13720000.00, 257705000.00, '2025-02-28', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(3, 1, 134343000.00, 108015000.00, 12101000.00, 312278000.00, '2025-03-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(4, 1, 112500000.00, 73100000.00, 10755000.00, 259540000.00, '2025-04-30', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(5, 2, 42318000.00, 0.00, 0.00, 42318000.00, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(6, 2, 20580000.00, 0.00, 0.00, 20580000.00, '2025-02-28', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(7, 2, 30560000.00, 0.00, 0.00, 30560000.00, '2025-03-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(8, 2, 40355000.00, 0.00, 0.00, 40355000.00, '2025-04-30', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(9, 3, 28850000.00, 0.00, 0.00, 28850000.00, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(10, 3, 21960000.00, 0.00, 0.00, 21960000.00, '2025-02-28', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(11, 3, 26200000.00, 0.00, 0.00, 26200000.00, '2025-03-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(12, 3, 20440000.00, 0.00, 0.00, 20440000.00, '2025-04-30', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(13, 4, 1170000.00, 0.00, 0.00, 1170000.00, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(14, 4, 900000.00, 0.00, 0.00, 900000.00, '2025-02-28', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(15, 4, 969000.00, 0.00, 0.00, 969000.00, '2025-03-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(16, 4, 1500000.00, 0.00, 0.00, 1500000.00, '2025-04-30', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(17, 5, 1180000.00, 0.00, 0.00, 1180000.00, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(18, 5, 670000.00, 0.00, 0.00, 670000.00, '2025-02-28', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(19, 5, 40000.00, 0.00, 0.00, 40000.00, '2025-03-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(20, 5, 800000.00, 0.00, 0.00, 800000.00, '2025-04-30', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(21, 6, 300000.00, 0.00, 0.00, 300000.00, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(22, 6, 0.00, 0.00, 0.00, 0.00, '2025-02-28', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(23, 6, 50000.00, 0.00, 0.00, 50000.00, '2025-03-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(24, 6, 90000.00, 0.00, 0.00, 90000.00, '2025-04-30', '2025-05-25 10:04:00', '2025-05-25 10:04:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Xem bản ghi tài chính', 'view_records', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(2, 'Chỉnh sửa bản ghi tài chính', 'edit_records', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(3, 'Phê duyệt bản ghi tài chính', 'approve_records', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(4, 'Xóa bản ghi tài chính', 'delete_records', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(5, 'Quản lý phòng ban', 'manage_departments', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(6, 'Quản lý vai trò', 'manage_roles', '2025-05-23 12:14:38', '2025-05-23 12:14:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `platforms`
--

CREATE TABLE `platforms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `platforms`
--

INSERT INTO `platforms` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Google Ads', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(2, 'Facebook Ads', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(3, 'TikTok Ads', '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(4, 'QR', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(5, 'WEB/APP', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(6, 'Các văn phòng', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(7, 'Vé thương gia', '2025-05-25 10:04:00', '2025-05-25 10:04:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `platform_metrics`
--

CREATE TABLE `platform_metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `platform_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `data_type` enum('int','float','string') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `platform_metrics`
--

INSERT INTO `platform_metrics` (`id`, `platform_id`, `name`, `unit`, `data_type`, `created_at`, `updated_at`) VALUES
(1, 2, 'Lượt Click', 'Lượt', 'int', '2025-05-23 12:16:23', '2025-05-23 12:16:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` enum('employee','manager','admin') NOT NULL COMMENT 'Phân cấp vai trò',
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `level`, `department_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Nhân viên Marketing', 'employee', 1, NULL, '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(2, 'Quản lý Marketing', 'manager', 1, NULL, '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(3, 'Admin Marketing', 'admin', 1, NULL, '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(4, 'Nhân viên Sales', 'employee', 2, NULL, '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(5, 'Quản lý Sales', 'manager', 2, NULL, '2025-05-23 12:14:38', '2025-05-23 12:14:38'),
(6, 'Admin Sales', 'admin', 2, NULL, '2025-05-23 12:14:38', '2025-05-23 12:14:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(4, 1),
(5, 1),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `routes`
--

CREATE TABLE `routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `routes`
--

INSERT INTO `routes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Sài Gòn - Buôn Ma Thuột', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(2, 'Buôn Ma Thuột - Sài Gòn', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(3, 'Sài Gòn - Đà Lạt', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(4, 'Đà Lạt - Sài Gòn', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(5, 'Sài Gòn - Nha Trang', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(6, 'Nha Trang - Sài Gòn', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(7, 'Sài Gòn - Mũi Né', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(8, 'Mũi Né - Sài Gòn', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(9, 'Buôn Ma Thuột - Đà Lạt', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(10, 'Đà Lạt - Buôn Ma Thuột', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(11, 'Xe hợp đồng', '2025-05-25 10:04:00', '2025-05-25 10:04:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`, `created_at`, `updated_at`) VALUES
('kThDPU1lemHjTG5mO0a0pAySn2YQaIcyhl1sN55T', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1h3clFod2w0c1FBRFpNaGJBN2FmbHIwRG5BaUN4RFlRcmZNaGUzRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvcmV2ZW51ZT9kYXRlMT0yMDI1LTA0LTAxJmRhdGUyPTIwMjUtMDUtMDEiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1748356514, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trips_passengers`
--

CREATE TABLE `trips_passengers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_id` bigint(20) UNSIGNED NOT NULL,
  `trips` int(11) NOT NULL DEFAULT 0,
  `passengers` int(11) NOT NULL DEFAULT 0,
  `record_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trips_passengers`
--

INSERT INTO `trips_passengers` (`id`, `route_id`, `trips`, `passengers`, `record_date`, `created_at`, `updated_at`) VALUES
(1, 1, 460, 8573, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(2, 2, 319, 3308, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(3, 3, 214, 3375, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(4, 4, 203, 2106, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(5, 5, 51, 622, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(6, 6, 49, 288, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(7, 7, 136, 459, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(8, 8, 135, 366, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(9, 9, 12, 80, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(10, 10, 11, 121, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00'),
(11, 11, 0, 0, '2025-01-31', '2025-05-25 10:04:00', '2025-05-25 10:04:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cache_key_unique` (`key`);

--
-- Chỉ mục cho bảng `dai_lies`
--
ALTER TABLE `dai_lies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dai_lies_email_unique` (`email`);

--
-- Chỉ mục cho bảng `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_department_id_foreign` (`department_id`),
  ADD KEY `employees_role_id_foreign` (`role_id`);

--
-- Chỉ mục cho bảng `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_financial_record_id_foreign` (`financial_record_id`),
  ADD KEY `expenses_expense_type_id_foreign` (`expense_type_id`);

--
-- Chỉ mục cho bảng `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `financial_records`
--
ALTER TABLE `financial_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_records_department_id_foreign` (`department_id`),
  ADD KEY `financial_records_platform_id_foreign` (`platform_id`),
  ADD KEY `financial_records_dai_ly_id_foreign` (`dai_ly_id`),
  ADD KEY `financial_records_office_id_foreign` (`office_id`),
  ADD KEY `financial_records_submitted_by_foreign` (`submitted_by`);

--
-- Chỉ mục cho bảng `financial_targets`
--
ALTER TABLE `financial_targets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `financial_targets_year_department_id_unique` (`year`,`department_id`),
  ADD KEY `financial_targets_department_id_foreign` (`department_id`);

--
-- Chỉ mục cho bảng `metric_values`
--
ALTER TABLE `metric_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metric_values_metric_id_foreign` (`metric_id`),
  ADD KEY `metric_values_financial_record_id_foreign` (`financial_record_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `office_revenues`
--
ALTER TABLE `office_revenues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `office_revenues_office_id_foreign` (`office_id`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_code_unique` (`code`);

--
-- Chỉ mục cho bảng `platforms`
--
ALTER TABLE `platforms`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `platform_metrics`
--
ALTER TABLE `platform_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `platform_metrics_platform_id_foreign` (`platform_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_department_id_foreign` (`department_id`);

--
-- Chỉ mục cho bảng `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Chỉ mục cho bảng `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `trips_passengers`
--
ALTER TABLE `trips_passengers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trips_passengers_route_id_foreign` (`route_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cache`
--
ALTER TABLE `cache`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dai_lies`
--
ALTER TABLE `dai_lies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `financial_targets`
--
ALTER TABLE `financial_targets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `metric_values`
--
ALTER TABLE `metric_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `offices`
--
ALTER TABLE `offices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `office_revenues`
--
ALTER TABLE `office_revenues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `platforms`
--
ALTER TABLE `platforms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `platform_metrics`
--
ALTER TABLE `platform_metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `trips_passengers`
--
ALTER TABLE `trips_passengers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_expense_type_id_foreign` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_financial_record_id_foreign` FOREIGN KEY (`financial_record_id`) REFERENCES `financial_records` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `financial_records`
--
ALTER TABLE `financial_records`
  ADD CONSTRAINT `financial_records_dai_ly_id_foreign` FOREIGN KEY (`dai_ly_id`) REFERENCES `dai_lies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financial_records_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financial_records_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financial_records_platform_id_foreign` FOREIGN KEY (`platform_id`) REFERENCES `platforms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financial_records_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `financial_targets`
--
ALTER TABLE `financial_targets`
  ADD CONSTRAINT `financial_targets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `metric_values`
--
ALTER TABLE `metric_values`
  ADD CONSTRAINT `metric_values_financial_record_id_foreign` FOREIGN KEY (`financial_record_id`) REFERENCES `financial_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `metric_values_metric_id_foreign` FOREIGN KEY (`metric_id`) REFERENCES `platform_metrics` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `office_revenues`
--
ALTER TABLE `office_revenues`
  ADD CONSTRAINT `office_revenues_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `platform_metrics`
--
ALTER TABLE `platform_metrics`
  ADD CONSTRAINT `platform_metrics_platform_id_foreign` FOREIGN KEY (`platform_id`) REFERENCES `platforms` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `trips_passengers`
--
ALTER TABLE `trips_passengers`
  ADD CONSTRAINT `trips_passengers_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
