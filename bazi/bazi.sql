-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2025 at 11:29 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bazi`
--

-- --------------------------------------------------------

--
-- Table structure for table `gym`
--

CREATE TABLE `gym` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `price` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `gym`
--

INSERT INTO `gym` (`id`, `name`, `city`, `address`, `price`, `day`, `created_at`) VALUES
(1, 'سالن شهید بابا نظر', 'مشهد', 'انتهای اندیشه 28', 440000, 1, '2024-05-04 20:45:39'),
(2, 'سالن ورزشی آزادی', 'تهران', 'خیابان مولوی کنار رودخانه طبرسی', 280000, 2, '2024-06-05 23:08:10'),
(3, 'سالن ورزشی فولاد رفسنجان', 'مشهد', 'بین الهیه 7 و 9', 900000, 0, '2024-07-06 23:09:13'),
(4, 'سالن فوتسال فرهنگیان', 'شیراز', 'خیابان صیاد شیرازی جنب بانک کشاورزی', 547000, 4, '2024-08-07 23:10:34'),
(5, 'سالن والیبال ترک زاده', 'تبریز', 'بلوار امامت بعد از پارک ملت', 675000, 3, '2024-09-05 23:11:40'),
(6, 'سالن فوتبال آزادی', 'مشهد', 'خیابان آزادی بعد از سه راه خیام', 800000, 5, '2024-12-31 23:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$10$UI6e2vT4f/XH8oN9R3qSAuLmvWhD2.XQ6DXsEKgcPkmz7B2tKEEqC'),
(4, 'user@gmail.com', '$2y$10$i/8LioY99iGKdIuG5/16lezvjKgzAX8dijIu2tEMcfpFSsBiaJo.6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gym`
--
ALTER TABLE `gym`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gym`
--
ALTER TABLE `gym`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
