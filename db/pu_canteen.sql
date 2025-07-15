-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 04:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pu_canteen`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Naima', 'naimarima03@gmail.com', '123456', '2025-06-23 14:22:22'),
(2, 'admin', 'admin@gmail.com', '234', '2025-06-29 11:06:28');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `items` text DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `product_id` varchar(100) NOT NULL,
  `order_name` varchar(200) NOT NULL,
  `order_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `items`, `total`, `created_at`, `quantity`, `created_date`, `product_id`, `order_name`, `order_status`) VALUES
(47, 16, NULL, 130, '2025-07-08 13:22:02', 0, '0000-00-00', '4,2', 'Cofee(1), Burger(1)', 1),
(48, 8, NULL, 60, '2025-07-08 13:25:57', 0, '0000-00-00', '10', 'Sandwich(1)', 1),
(49, 8, NULL, 35, '2025-07-08 13:33:26', 0, '0000-00-00', '9', 'Noodles(1)', 2),
(50, 18, NULL, 500, '2025-07-08 13:52:05', 0, '0000-00-00', '1', 'Pizza(1)', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `pr_title` varchar(100) NOT NULL,
  `pr_desc` varchar(100) NOT NULL,
  `pr_price` int(11) NOT NULL,
  `img` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `pr_title`, `pr_desc`, `pr_price`, `img`) VALUES
(1, 'Pizza', 'Pizza', 500, 'pizza.webp'),
(2, 'Burger', 'Burger', 100, 'burger.jpeg'),
(3, 'Cha', 'Most demanding cha', 10, 'cha.png'),
(4, 'Cofee', 'Hot Coffee', 30, 'coffee.png'),
(5, 'Pasta', 'Delicious food', 150, 'uploads/1751182994_6860ee922ed2c.png'),
(6, 'Roll', 'Vegetable Roll', 50, 'uploads/1751183037_6860eebd2a9d3.png'),
(9, 'Noodles', 'Egg Noodles', 35, 'uploads/noodles.png'),
(10, 'Sandwich', 'Yummy Sandwich', 60, 'uploads/sandwich.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `customer_id` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `phone`, `email`, `customer_id`, `password`, `balance`) VALUES
(8, 'Nayeem Ahmed', '01769567983', 'naimahammed44@gmail.com', '12', '$2y$10$eHGEHA4Zk4U./Qfb8gbLB.G4Fjh/BiD4teH7YOwC5MdEjMoDYwApi', 400.00),
(18, 'Macaw', '01769567983', 'macawelevatorltd@gmail.com', '5', '$2y$10$NIxJEI3QxVA7ve78QKi9POBzPD1yAvSYq5C/nVS9EC8.qJcAgXgBa', 2000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users_balance_requests`
--

CREATE TABLE `users_balance_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_balance_requests`
--

INSERT INTO `users_balance_requests` (`id`, `user_id`, `amount`, `status`, `created_at`) VALUES
(12, 14, 50, 'approved', '2025-06-30 09:01:37'),
(16, 14, 100, 'rejected', '2025-06-30 10:06:25'),
(17, 8, 300, 'approved', '2025-06-30 10:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_order`
--

CREATE TABLE `user_order` (
  `id` int(11) NOT NULL,
  `pr_title` varchar(100) NOT NULL,
  `pr_desc` varchar(100) NOT NULL,
  `pr_price` int(11) NOT NULL,
  `img` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_order`
--

INSERT INTO `user_order` (`id`, `pr_title`, `pr_desc`, `pr_price`, `img`, `quantity`) VALUES
(1, 'Pizza', 'Pizza', 600, 'pizza.webp', 5),
(2, 'Pizza', 'Pizza', 600, 'pizza.webp', 5),
(3, 'Burger', 'Burger', 100, 'burger.jpeg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_balance_requests`
--
ALTER TABLE `users_balance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_order`
--
ALTER TABLE `user_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users_balance_requests`
--
ALTER TABLE `users_balance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_order`
--
ALTER TABLE `user_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
