-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2025 at 05:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jutta_sansaar`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(31, 6, 3, 1),
(34, 8, 3, 2),
(62, 7, 1, 7),
(63, 7, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Sports'),
(2, 'Casual'),
(3, 'Formal'),
(4, 'Kids'),
(5, 'Boots'),
(6, 'Sandals');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `address`, `payment_method`, `created_at`, `product_id`, `status`, `total`) VALUES
(16, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 12:58:38', NULL, 'Accepted', 13200.00),
(17, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 14:48:48', NULL, 'Pending', 36300.00),
(18, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 14:48:53', NULL, 'Rejected', 36300.00),
(19, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 14:48:57', NULL, 'Rejected', 36300.00),
(20, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 14:49:06', NULL, 'Rejected', 23100.00),
(21, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 14:49:19', NULL, 'Rejected', 23100.00),
(22, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 14:49:23', NULL, 'Rejected', 23100.00),
(23, 7, 'Yugesh Man Shrestha', '9823873851', 'Pashupati', 'Cash on Delivery', '2025-07-30 14:49:44', NULL, 'Accepted', 36844.50);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category`, `stock`) VALUES
(1, 'Black Sports Shoes', NULL, 3000.00, 'blue.jpg', 'Sports', 5),
(2, 'Classic Leather Loafers', NULL, 2499.00, 'loafers.jpg', 'Boots', 2),
(3, 'Casual White Sneakers', NULL, 1799.00, 'sneakers.jpg', 'Casual', 5),
(4, 'High-Top Basketball Shoes', NULL, 2899.00, 'basketball.jpg', 'Sports', 11),
(5, 'Running Shoes - Red', NULL, 2099.00, 'running_red.jpg', 'Sports', 4),
(6, 'Trail Hiking Boots', NULL, 3199.00, 'trail_boots.jpg', 'Boots', 4),
(7, 'Slip-On Canvas Shoes', NULL, 1599.00, 'slipon_canvas.jpg', 'Sandals', 5),
(8, 'Formal Oxford Shoes', NULL, 2999.00, 'oxford.jpg', 'Formal', 7),
(9, 'Kids Light-Up Shoes', NULL, 1899.00, 'kids_lightup.jpg', 'Kids', 9),
(10, 'Chunky Dad Sneakers', NULL, 2699.00, 'dad_sneakers.jpg', 'Casual', 4),
(11, 'Neon Green Trainers', NULL, 2199.00, 'neon_trainers.jpg', 'Kids', 9),
(12, 'Limited Edition Gold High-Tops', NULL, 3499.00, 'gold_hightops.jpg', 'Formal', 12),
(13, 'Winter Fur-lined Boots', NULL, 2799.00, 'fur_boots.jpg', 'Boots', 5),
(14, 'Summer Flip Flops', NULL, 899.00, 'flipflops.jpg', 'Sandals', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`) VALUES
(4, 'John Doe', 'john@example.com', '123456', 0),
(5, 'Yugesh Man Shrestha', 'yugeshmanshrestha@gmail.com', '$2y$10$SYo1XIYl.qWafzC8blwmKu4UHFdQkLce8daPtFMsHCzV94DkoDFoO', 0),
(6, 'Dragon', 'Dragon@gmail.com', '$2y$10$6QFZs3pLilyD/n8NSzCkSeUsgDe23TiOlVHT7dQ2gTBSTusTv36Ty', 1),
(7, 'John', 'john@gmail.com', '$2y$10$qoXoU.GXJKj.aXOE43ZBs.tINrtTU3RReJuN.6lDshaHw0TY2yQVO', 0),
(8, 'Gaurav', 'Gaurav@gmail.com', '$2y$10$Tdz7XZKaU7fopn3a8c/tBuF.FFzFAsPEQTTYREKx2amQ7lHgoNjI6', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
