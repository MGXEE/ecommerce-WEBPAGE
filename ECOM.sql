-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 10:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ECOM`
--
CREATE DATABASE IF NOT EXISTS `ECOM` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ECOM`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `status`) VALUES
(4, 5, 'OPEN'),
(5, 4, 'PAID'),
(6, 7, 'OPEN'),
(8, 10, 'PAID'),
(9, 10, 'PAID'),
(10, 10, 'PAID'),
(11, 10, 'PAID'),
(12, 4, 'OPEN');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`id`, `cart_id`, `product_id`, `quantity`) VALUES
(6, 4, 4, 1),
(9, 4, 7, 1),
(28, 12, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250819101406', '2025-08-25 22:05:18', 32),
('DoctrineMigrations\\Version20250821151804', '2025-08-25 22:05:18', 31),
('DoctrineMigrations\\Version20250825220451', '2025-08-25 22:05:18', 29);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `zip` varchar(150) NOT NULL,
  `city` varchar(150) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `total` double NOT NULL,
  `status` varchar(255) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `full_name`, `email`, `street`, `zip`, `city`, `country`, `phone`, `items`, `total`, `status`, `payment_method`, `paid_at`, `created_at`, `updated_at`, `user_id`) VALUES
(11, 'Nora Doe', 'nora@gmail.com', 'Mengergasse 8', '1210', 'Vienna', 'AT', '+436766767643', '[{\"product\":\"Marshall Headphones\",\"price\":\"298.99\",\"quantity\":1,\"line_total\":298.99},{\"product\":\"M4 Macbooc Air\",\"price\":\"1299.99\",\"quantity\":1,\"line_total\":1299.99}]', 1598.98, 'paid', 'Cash', '2025-08-26 17:18:16', '2025-08-26 17:18:16', NULL, 8),
(12, 'Nora Doe', 'nora@gmail.com', 'Mengergasse 66 1/8', '1210', 'Vienna', 'AU', '+436766767643', '[{\"product\":\"Marshall Headphones\",\"price\":\"298.99\",\"quantity\":2,\"line_total\":597.98},{\"product\":\"M4 Macbooc Air\",\"price\":\"1299.99\",\"quantity\":1,\"line_total\":1299.99}]', 1897.97, 'paid', 'Cash', '2025-08-26 17:21:20', '2025-08-26 17:21:20', NULL, 8),
(14, 'aasd', 'kaslmd@gmail.com', 'qomasmd', 'kopksd01209saon', 'oiasdojiij', 'AF', '9123890', '[{\"product\":\"Dell Pro 27\",\"price\":\"194.41\",\"quantity\":1,\"line_total\":194.41}]', 194.41, 'paid', 'Fake', '2025-08-27 05:55:46', '2025-08-27 05:55:46', NULL, 10),
(15, 'sadklmlk', 'ashdj@gmail.com', 'ndcjn', 'ioanacin', 'ainsci', 'AF', '32897489', '[{\"product\":\"Dell Pro 27\",\"price\":\"194.41\",\"quantity\":1,\"line_total\":194.41}]', 194.41, 'paid', 'Cash', '2025-08-27 05:57:17', '2025-08-27 05:57:17', NULL, 10),
(16, 'ftzfzt', 'vvhgvcxy@gmail.com', 'okldsain', 'siacb7897', 'iuascbi', 'AF', '28973896', '[{\"product\":\"PowerMax 20,000mAh\",\"price\":\"49.99\",\"quantity\":1,\"line_total\":49.99}]', 49.99, 'paid', 'Fake', '2025-08-27 14:43:34', '2025-08-27 14:43:34', NULL, 10),
(17, 'sakndjan', 'njdaskcnk@gmail.com', 'ijwofij', '32io4n', 'odnfio', 'AF', '8937489', '[{\"product\":\"PowerMax 20,000mAh\",\"price\":\"49.99\",\"quantity\":1,\"line_total\":49.99},{\"product\":\"M4 Macbooc Air\",\"price\":\"1299.99\",\"quantity\":1,\"line_total\":1299.99}]', 1349.98, 'paid', 'Fake', '2025-08-27 23:40:01', '2025-08-27 23:40:01', NULL, 10),
(18, 'oqdonq', 'oadsnc@gmail.com', 'iodsnc', 'indovn32r', 'nosvd', 'AF', '01938', '[{\"product\":\"Marshall Headphones\",\"price\":\"298.99\",\"quantity\":2,\"line_total\":597.98}]', 597.98, 'paid', 'Fake', '2025-08-27 23:56:08', '2025-08-27 23:56:08', NULL, 10),
(19, 'skamdklmas', 'samdan@gmail.com', 'oijdoij', 'oijd0j90', 'ojciojd', 'AF', '12313', '[{\"product\":\"Dell Pro 27\",\"price\":\"194.41\",\"quantity\":1,\"line_total\":194.41}]', 194.41, 'paid', 'Fake', '2025-08-28 01:20:05', '2025-08-28 01:20:05', NULL, 10),
(20, 'dsds', 'ahdbihu@gmail.com', 'iojdiu', 'iudsnsicn', 'iuniudc', 'AF', '13212', '[{\"product\":\"Marshall Headphones\",\"price\":\"298.99\",\"quantity\":1,\"line_total\":298.99},{\"product\":\"Dell Pro 27\",\"price\":\"194.41\",\"quantity\":1,\"line_total\":194.41}]', 493.4, 'paid', 'Fake', '2025-08-28 15:11:29', '2025-08-28 15:11:29', NULL, 10),
(21, 'asdas', 'asd@gmail.com', 'aidsad', 'dsad', 'asdads', 'AF', '3423', '[{\"product\":\"M4 Macbooc Air\",\"price\":\"1299.99\",\"quantity\":3,\"line_total\":3899.9700000000003},{\"product\":\"EcoCharge 10,000mAh\",\"price\":\"29.99\",\"quantity\":1,\"line_total\":29.99},{\"product\":\"Marshall Headphones\",\"price\":\"298.99\",\"quantity\":2,\"line_total\":597.98}]', 4527.94, 'paid', 'Fake', '2025-08-28 15:17:30', '2025-08-28 15:17:30', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `visibility` tinyint(1) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `special_offer` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product`, `price`, `description`, `image`, `category`, `visibility`, `discount`, `special_offer`) VALUES
(4, 'Marshall Headphones', 298.99, 'Overear headphones with integrated noisecancelling function.', 'https://cdn.pixabay.com/photo/2018/01/16/10/18/headphones-3085681_1280.jpg', 'MEDIA', 1, NULL, 0),
(6, 'Dell Pro 27', 194.41, 'A 27-inch Full HD Dell monitor with an included IPS panel.', 'https://static1.xdaimages.com/wordpress/wp-content/uploads/wm/2023/09/dell-ultrasharp-u2723qe-review-06.JPG', 'MEDIA', 1, NULL, 0),
(7, 'M4 Macbooc Air', 1299.99, 'Lightweight and powerful laptop with Apple’s new M4 chip.', 'https://techtoro.io/image/catalog/Blogs/macbook%20m4%20news/macbook-m4.png', 'MEDIA', 1, NULL, 0),
(8, 'UltraX Earbuds', 79.99, 'True wireless earbuds with crystal-clear sound, noise cancellation, and 24-hour battery life.', 'https://cdn.mos.cms.futurecdn.net/8QPwMttadtGHq7sPaxK22W.jpg', 'MEDIA', 1, NULL, 0),
(9, 'PowerMax 20,000mAh', 49.99, 'High-capacity power bank with fast charging and dual USB ports for all your devices on the go.', 'https://image.stern.de/35147062/t/ij/v6/w1440/r1.7778/-/powerbank-news.jpg', 'BATTERY', 1, NULL, 0),
(10, 'SmartHome Hub Pro', 129.99, 'Centralized smart home controller supporting voice commands, multiple devices, and easy setup.', 'https://zwave.eu/wp-content/uploads/2021/10/Blog_Hub.jpg', 'SMART HOME', 1, NULL, 0),
(11, 'Smart LED Bulb Set', 39.99, 'Energy-efficient LED bulbs controllable via app or voice assistant, offering millions of color options and schedules.', 'https://www.faz.net/kaufkompass/wp-content/uploads/2025/01/smarte-lampe-aufmacher-bunt-2240x1260.jpg', 'SMART HOME', 1, NULL, 0),
(12, 'HomeCam 360', 89.99, 'Full HD indoor security camera with 360° rotation, night vision, motion detection, and real-time mobile alerts.', 'https://i.ytimg.com/vi/1VRHrhOjDWY/maxresdefault.jpg', 'SMART HOME', 1, NULL, 0),
(13, 'EcoCharge 10,000mAh', 29.99, 'Compact and eco-friendly power bank with USB-C fast charging and intelligent power distribution.', 'https://www.macwelt.de/wp-content/uploads/2024/11/Anker_UltraSlimPowerbank_02.jpg?quality=50&strip=all', 'BATTERY', 1, NULL, 0),
(14, 'SoundBlitz Speaker', 59.99, 'Portable waterproof speaker with deep bass, 12-hour battery life, and hands-free calling function.', 'https://blaupunkt.com/wp-content/uploads/2021/03/BT_202_body__BT202_0.4.jpg', 'MEDIA', 1, NULL, 0),
(15, 'VisionPro Stick', 44.99, '4K media streaming stick compatible with all major platforms, with voice remote and fast dual-band Wi-Fi.', 'https://www.connect.de/bilder/118682560/landscapex1200-c0/hd-plus-ip-tv-stick.jpg', 'MEDIA', 1, NULL, 0),
(16, 'Aurora Home Theater', 349.99, '5.1 surround sound system with wireless subwoofer, Bluetooth, and HDMI ARC support.', 'https://i.ytimg.com/vi/Hb2mx9woBO8/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLD5jqecph7zNtWZLHdrENXbsOQZsA', 'MEDIA', 1, NULL, 0),
(17, 'Magsave Akkupack', 45.99, 'MegsSave Charging: Efficient, quick, and safe device charging.', 'https://platform.theverge.com/wp-content/uploads/sites/2/chorus/uploads/chorus_asset/file/22733323/DSC02727_dbohn_verge.jpg?quality=90&strip=all&crop=0,0,100,100', 'BATTERY', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `questions` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `questions`, `email`) VALUES
(5, 'test', '123sad@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `content` longtext NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `product_id`, `author`, `content`, `rating`, `created_at`) VALUES
(4, 6, 'Melanie', 'overall a very good product.', 4, '2025-08-21 18:30:45');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `fname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `fname`, `lname`) VALUES
(4, 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$f4yaLRtO6m0GO3PbFT6l8uJosvCrY3KWvzg.B4lGhYMsk8KcJeStW', 'Admin', 'Test'),
(5, 'test@mail.com', '[]', '$2y$13$pUqRfIqfEGsWhT5BcniX0u6V0LSbnkvcsc.W3t8HWYpxA1CKEsZVa', 'test', 'user'),
(7, 'melaniew1234mw@gmail.com', '[]', '$2y$13$tEpF1q3o.ER4Ws7WkiClLe9aCfqJxW8Vrd04LPRWv1b8gNa/syhZC', 'Melanie', 'Weidinger'),
(10, 'mohd@gmail.com', '[]', '$2y$13$AUTm/0z0kxiVCsRioRs.fOqSDpdGmL.vbnxbklJpPyxY20x0oALti', 'mohd', 'mohd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BA388B7A76ED395` (`user_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F0FE25271AD5CDBF` (`cart_id`),
  ADD KEY `IDX_F0FE25274584665A` (`product_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_794381C64584665A` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_BA388B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `FK_F0FE25271AD5CDBF` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`),
  ADD CONSTRAINT `FK_F0FE25274584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `FK_794381C64584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
