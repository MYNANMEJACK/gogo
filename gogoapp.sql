-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2025-01-30 02:08:28
-- 伺服器版本： 11.2.2-MariaDB
-- PHP 版本： 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `gogoapp`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `role` enum('super_admin','admin') DEFAULT 'admin',
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `idx_username` (`username`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `full_name`, `created_at`, `last_login`, `status`, `role`, `remember_token`) VALUES
(1, 'admin', '$2y$10$SDiGgZb7F5PybMI.OkpCseD.2oAWUuiUbvm/OF1ryoYWjgupn3LvS', 'admin@example.com', '系統管理員', '2025-01-30 01:39:39', '2025-01-30 02:01:43', 'active', 'super_admin', '4646717c5503f79c71ee0b60e0a6f2de7af778b003cadd34ebec90975da7b419'),
(2, '51123043', '$2y$10$Ml6iww/qGC1omWb7sROyquzzGP7brj0uJJNZDpTimEqJofge8BaUi', 'chen51123043@gmail.com', 'jackjack', '2025-01-30 01:58:53', '2025-01-30 02:01:32', 'active', 'admin', '5717cb729024f76fd1ac843db2c987e57c9fa6ead9aa6a5c0ab7cc012b38aa90');

-- --------------------------------------------------------

--
-- 資料表結構 `dishes`
--

DROP TABLE IF EXISTS `dishes`;
CREATE TABLE IF NOT EXISTS `dishes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `profession_tag` varchar(255) DEFAULT NULL,
  `recipe` text DEFAULT NULL,
  `type` enum('素','肉') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `dishes`
--

INSERT INTO `dishes` (`id`, `name`, `profession_tag`, `recipe`, `type`) VALUES
(1, '麻婆豆腐', '中式', '豆腐和肉末炒制', '素'),
(2, '宫保鸡丁', '中式', '鸡肉、花生、辣椒炒制', '肉'),
(3, '红烧茄子', '中式', '茄子红烧', '素'),
(4, '鱼香肉丝', '中式', '猪肉丝和蔬菜炒制', '肉');

-- --------------------------------------------------------

--
-- 資料表結構 `dish_products`
--

DROP TABLE IF EXISTS `dish_products`;
CREATE TABLE IF NOT EXISTS `dish_products` (
  `dish_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`dish_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `dish_products`
--

INSERT INTO `dish_products` (`dish_id`, `product_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 5),
(4, 7);

-- --------------------------------------------------------

--
-- 資料表結構 `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `images`
--

INSERT INTO `images` (`id`, `url`) VALUES
(1, 'https://i.imgur.com/m215dRS.png'),
(2, 'https://photos.app.goo.gl/B4wt8XMrVUooSQPz6'),
(3, 'https://imgur.com/a/kXDS0rs');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `delivery_method` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `pickup_location` text DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT '待確認',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `delivery_method`, `payment_method`, `address`, `pickup_location`, `total_price`, `created_at`, `status`) VALUES
(34, '12', '自取', '微信支付', NULL, '尖沙咀海港城', 39.99, '2024-12-04 10:05:40', '待確認'),
(33, '12', '自取', '信用卡', NULL, '深水埗黃金商場', 39.99, '2024-12-04 09:14:37', '待確認'),
(32, '12', '自取', '微信支付', NULL, '尖沙咀海港城', 199.94, '2024-12-04 09:13:57', '待確認'),
(31, '12', '地址配送', 'FPS', '深水埗基隆街西九龙中心', NULL, 69.98, '2024-12-02 07:36:25', '待確認'),
(30, '12', '自取', '信用卡', NULL, '尖沙咀海港城', 39.99, '2024-11-29 01:04:00', '配送中'),
(22, '12', '自取', '支付寶', NULL, '深水埗黃金商場', 69.98, '2024-11-28 08:29:31', '已完成'),
(23, '12', '自取', 'FPS', NULL, '尖沙咀海港城', 69.98, '2024-11-28 08:41:44', '到達自取點'),
(24, '12', '自取', '支付寶', NULL, '深水埗黃金商場', 29.99, '2024-11-28 08:47:28', '待確認'),
(25, '12', '自取', '支付寶', NULL, '尖沙咀海港城', 89.97, '2024-11-28 09:02:08', '待確認'),
(26, '12', '自取', '支付寶', NULL, '深水埗黃金商場', 69.98, '2024-11-28 09:24:32', '待確認'),
(27, '12', '自取', '支付寶', NULL, '深水埗黃金商場', 69.98, '2024-11-28 09:32:37', '待確認'),
(28, '12', '自取', 'FPS', NULL, '尖沙咀海港城', 69.98, '2024-11-28 09:33:56', '待確認'),
(29, '12', '自取', '支付寶', NULL, '尖沙咀海港城', 69.98, '2024-11-28 09:53:42', '待確認'),
(35, '1', '自取', '微信支付', NULL, '深水埗黃金商場', 29.99, '2024-12-04 10:09:14', '待確認'),
(36, '12', '自取', 'FPS', NULL, '深水埗黃金商場', 39.99, '2025-01-07 06:21:44', '待確認'),
(37, '13', '自取', '支付寶', NULL, '深水埗黃金商場', 49.99, '2025-01-09 07:59:46', '待確認'),
(38, '12', '自取', 'FPS', NULL, '深水埗黃金商場', 59.98, '2025-01-11 07:30:55', '待確認'),
(39, '13', '地址配送', 'FPS', 'sdsd', '香港IFC商場', 179.97, '2025-01-15 07:26:34', '待確認'),
(40, '13', '自取', 'FPS', NULL, '深水埗黃金商場', 339.93, '2025-01-15 07:30:53', '待確認'),
(41, '13', '自取', 'FPS', NULL, '深水埗黃金商場', 249.96, '2025-01-15 07:31:13', '待確認'),
(42, '13', '自取', 'FPS', NULL, '深水埗黃金商場', 249.96, '2025-01-15 07:31:15', '待確認'),
(43, '13', '自取', 'FPS', NULL, '深水埗黃金商場', 249.96, '2025-01-15 07:31:15', '待確認'),
(44, '13', '自取', 'FPS', NULL, '尖沙咀海港城', 49.99, '2025-01-15 07:31:34', '待確認'),
(45, '13', '自取', '微信支付', NULL, '深水埗黃金商場', 119.98, '2025-01-15 07:34:09', '待確認'),
(46, '13', '自取', '微信支付', NULL, '銅鑼灣時代廣場', 129.97, '2025-01-15 07:35:48', '待確認'),
(47, '13', '自取', '微信支付', NULL, '深水埗黃金商場', 309.94, '2025-01-15 07:38:27', '待確認'),
(48, '13', '自取', '微信支付', NULL, '深水埗黃金商場', 199.97, '2025-01-20 06:55:13', '待確認');

-- --------------------------------------------------------

--
-- 資料表結構 `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`) VALUES
(47, 31, 'Product 3', 1, 39.99),
(46, 30, 'Product 3', 1, 39.99),
(45, 29, '鷄蛋', 1, 29.99),
(44, 29, 'Product 3', 1, 39.99),
(43, 28, 'Product 3', 1, 39.99),
(42, 28, '鷄蛋', 1, 29.99),
(41, 27, '鷄蛋', 1, 29.99),
(40, 27, 'Product 3', 1, 39.99),
(39, 26, 'Product 3', 1, 39.99),
(38, 26, '鷄蛋', 1, 29.99),
(37, 25, 'Product 3', 1, 39.99),
(36, 25, '鷄蛋', 1, 29.99),
(35, 25, '米', 1, 19.99),
(34, 24, '鷄蛋', 1, 29.99),
(33, 23, '鷄蛋', 1, 29.99),
(32, 23, 'Product 3', 1, 39.99),
(31, 22, '鷄蛋', 1, 29.99),
(30, 22, 'Product 3', 1, 39.99),
(48, 31, '鷄蛋', 1, 29.99),
(49, 32, 'Product 3', 3, 39.99),
(50, 32, '米', 1, 19.99),
(51, 32, '鷄蛋', 2, 29.99),
(52, 33, 'Product 3', 1, 39.99),
(53, 34, 'Product 3', 1, 39.99),
(54, 35, '鷄蛋', 1, 29.99),
(55, 36, 'Product 3', 1, 39.99),
(56, 37, 'Product 4', 1, 49.99),
(57, 38, '米', 1, 19.99),
(58, 38, 'Product 3', 1, 39.99),
(59, 39, 'Product 6', 1, 69.99),
(60, 39, '鷄蛋', 1, 29.99),
(61, 39, 'Product 7', 1, 79.99),
(62, 40, '鷄蛋', 1, 29.99),
(63, 40, 'Product 4', 1, 49.99),
(64, 40, '红烧茄子', 1, 59.99),
(65, 40, '麻婆豆腐', 1, 19.99),
(66, 40, '鱼香肉丝', 1, 79.99),
(67, 40, '宫保鸡丁', 1, 19.99),
(68, 40, 'Product 7', 1, 79.99),
(69, 41, '鷄蛋', 1, 29.99),
(70, 41, '红烧茄子', 1, 59.99),
(71, 41, '鱼香肉丝', 1, 79.99),
(72, 41, 'Product 7', 1, 79.99),
(73, 42, '鷄蛋', 1, 29.99),
(74, 42, '红烧茄子', 1, 59.99),
(75, 42, '鱼香肉丝', 1, 79.99),
(76, 42, 'Product 7', 1, 79.99),
(77, 43, '鷄蛋', 1, 29.99),
(78, 43, '红烧茄子', 1, 59.99),
(79, 43, '鱼香肉丝', 1, 79.99),
(80, 43, 'Product 7', 1, 79.99),
(81, 44, 'Product 4', 1, 49.99),
(82, 45, 'Product 4', 1, 49.99),
(83, 45, 'Product 6', 1, 69.99),
(84, 46, '鷄蛋', 2, 29.99),
(85, 46, 'Product 6', 1, 69.99),
(86, 47, '鷄蛋', 2, 29.99),
(87, 47, 'Product 6', 1, 69.99),
(88, 47, 'Product 4', 2, 49.99),
(89, 47, 'Product 7', 1, 79.99),
(90, 48, 'Product 7', 1, 79.99),
(91, 48, 'Product 4', 1, 49.99),
(92, 48, 'Product 6', 1, 69.99);

-- --------------------------------------------------------

--
-- 資料表結構 `pickup_locations`
--

DROP TABLE IF EXISTS `pickup_locations`;
CREATE TABLE IF NOT EXISTS `pickup_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `pickup_locations`
--

INSERT INTO `pickup_locations` (`id`, `address`) VALUES
(1, '香港IFC商場'),
(2, '銅鑼灣時代廣場'),
(3, '深水埗黃金商場'),
(4, '尖沙咀海港城');

-- --------------------------------------------------------

--
-- 資料表結構 `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `products`
--

INSERT INTO `products` (`id`, `image_url`, `name`, `description`, `price`, `origin`, `tags`, `stock`, `type`) VALUES
(1, 'https://i.imgur.com/KTsDJhN.png', '米', '米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米米', 19.99, 'USA', 'off', 97, 'food'),
(2, 'https://i.imgur.com/rhaW7Iy.jpeg', '鷄蛋', '鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋鷄蛋', 29.99, 'USA', 'off', 18, 'vegetable'),
(3, 'https://i.imgur.com/ZIrP97I.png', 'Product 3', 'Description for product 3', 39.99, 'China', 'life', 35, 'life'),
(4, 'https://firebasestorage.googleapis.com/v0/b/lifeapp-bb6a4.appspot.com/o/e7b512c817aa32784deaa5a8222e955.png?alt=media&token=649e662d-9f05-4dea-a90a-3278a9e04532', 'Product 4', 'Description for product 4', 49.99, 'Category 2', 'off', 13, 'food'),
(5, 'https://firebasestorage.googleapis.com/v0/b/lifeapp-bb6a4.appspot.com/o/dd8b635bc9ece27f4fb218e161c8847.png?alt=media&token=463c9709-9107-4f28-a3e8-b4b0e1a34795', 'Product 5', 'Description for product 5', 59.99, 'Category 3', 'off', 0, 'drink'),
(6, 'https://firebasestorage.googleapis.com/v0/b/lifeapp-bb6a4.appspot.com/o/60d07c7f38c0a6acbb1a11e1664986e.png?alt=media&token=cc5f07d1-0cec-484e-b3ba-9381613fb464', 'Product 6', 'Description for product 6', 69.99, 'Category 3', 'tag2', 95, ''),
(7, 'https://firebasestorage.googleapis.com/v0/b/lifeapp-bb6a4.appspot.com/o/8a89d1610ee879f26439f9a1309ae92.png?alt=media&token=bed9cb0c-cdb2-434e-aba0-f707c10c874a', 'Product 7', 'Description for product 7', 79.99, 'Category 4', 'tag2', 83, ''),
(8, 'https://firebasestorage.googleapis.com/v0/b/lifeapp-bb6a4.appspot.com/o/60d07c7f38c0a6acbb1a11e1664986e.png?alt=media&token=cc5f07d1-0cec-484e-b3ba-9381613fb464', 'Product 8', 'Description for product 8', 89.99, 'Category 4', 'tag2', 10, '');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','women') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `password`, `name`, `email`, `phone`, `job`, `gender`) VALUES
(1, '$2y$10$HLUpUfDal.ttzw0cazk7a.6Dpm0jK6X3ngUgoz3NERZtV1qk9CT9i', '51123043', '', NULL, NULL, 'male'),
(12, '$2a$10$wXgvUirRSc4latwKLIZDK./1KuLW1ciu9/gWB9SjfDYucAfXGp5xa', '6113770', '958893732@QQ.COM', '4156454', '打工仔', 'male'),
(11, '$2a$10$q4wd2LIkpoFokW3qSFOiYOT4tmY.9wgdPXYK8JpPniJnDV9aUXiRG', '21231231', '486548564@gnm.com', '51123043', '家庭主婦', 'women'),
(10, '$2a$10$OJOfWV2Un8MNEC9RMxZ8ru1YG7HPJazUopq5V0F/UinqrFiBtR6R2', '411524', '4154156456@gma.com', '51123043', '家庭主婦', 'women'),
(9, '$2a$10$5l576iQD2RFO6FFb7mGAAuY2u8sirjMth0w/7fQQNpJgaEfZpYdcW', 'dasfdag', '941654856@qq.com', '511230423', '打工仔', 'women'),
(8, '$2a$10$TE1VHwNpDJKnTzR2WtHfOubUc5f6d51RUkdXiizmmBDjXcQ7aVIYi', 'dasfa', '958893732@qq.com', '51123043', '家庭主妇', 'male'),
(13, '$2a$10$4LfvgvzWjt3VcW5g0Iz1pOHMxUCvfhgh..LUMQG9jTFMJ37YOeQT6', 'meme', '958893732@qq.com', '123669789', '家庭主婦', 'women');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
