-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2025 at 05:28 PM
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
(18, 1, 2, 1),
(19, 1, 3, 1),
(20, 1, 4, 1),
(21, 1, 14, 1),
(27, 3, 3, 1);

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
  `quantity` int(11) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `address`, `payment_method`, `created_at`, `product_id`, `quantity`, `status`) VALUES
(2, 3, 'dfgdg1', '323424', 'gsgfg23', 'Cash on Delivery', '2025-07-21 03:43:11', NULL, NULL, 'Pending'),
(3, 3, 'kjfnjernfv', '246r278r', 'mvjnsfjmnv', 'Khalti', '2025-07-21 04:03:34', NULL, NULL, 'Pending'),
(4, 3, 'fvsdvsv', '33434', 'fbdx b', 'Khalti', '2025-07-21 15:13:38', NULL, NULL, 'Pending'),
(5, 3, 'Xtreme Energy Drink Classic', '1234135', 'jhkhbhj66', 'Cash on Delivery', '2025-07-21 15:22:51', NULL, NULL, 'Pending');

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
(1, 'Black Sports Shoes', NULL, 1999.00, 'black_sports.jpg', NULL, 0),
(2, 'Classic Leather Loafers', NULL, 2499.00, 'loafers.jpg', NULL, 0),
(3, 'Casual White Sneakers', NULL, 1799.00, 'sneakers.jpg', NULL, 0),
(4, 'High-Top Basketball Shoes', NULL, 2899.00, 'basketball.jpg', NULL, 0),
(5, 'Running Shoes - Red', NULL, 2099.00, 'running_red.jpg', NULL, 0),
(6, 'Trail Hiking Boots', NULL, 3199.00, 'trail_boots.jpg', NULL, 0),
(7, 'Slip-On Canvas Shoes', NULL, 1599.00, 'slipon_canvas.jpg', NULL, 0),
(8, 'Formal Oxford Shoes', NULL, 2999.00, 'oxford.jpg', NULL, 0),
(9, 'Kids Light-Up Shoes', NULL, 1899.00, 'kids_lightup.jpg', NULL, 0),
(10, 'Chunky Dad Sneakers', NULL, 2699.00, 'dad_sneakers.jpg', NULL, 0),
(11, 'Neon Green Trainers', NULL, 2199.00, 'neon_trainers.jpg', NULL, 0),
(12, 'Limited Edition Gold High-Tops', NULL, 3499.00, 'gold_hightops.jpg', NULL, 0),
(13, 'Winter Fur-lined Boots', NULL, 2799.00, 'fur_boots.jpg', NULL, 0),
(14, 'Summer Flip Flops', NULL, 899.00, 'flipflops.jpg', NULL, 0),
(15, 'Sneakers', 'Comfy shoes', 49.99, 'shoe.jpg', NULL, 0);

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
(1, 'Gaurav Pandey', 'flashbarry74@gmail.com', '$2y$10$ANee1NTBlgIsBqvhOoEIUuO.fpqr/ZuSMW1Rzc56Js0ZBm0F8./76', 0),
(3, 'G P', '12wildsoul@gmail.com', '$2y$10$xORzqq3qSJQWdsXT6yZfau.GL3QeOyzWHYEpEBDEqWMyxljKjBDXW', 1),
(4, 'John Doe', 'john@example.com', '123456', 0);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product` (`product_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
