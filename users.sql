-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2022 at 08:45 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fb_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `id_card_number` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'default-image.png',
  `birthday` date NOT NULL,
  `weight` varchar(255) NOT NULL DEFAULT '-',
  `height` varchar(255) NOT NULL DEFAULT '-',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `phone_number`, `id_card_number`, `image`, `birthday`, `weight`, `height`, `created_at`, `updated_at`) VALUES
(6, 'Ittikorn', 'Sirivejaband', 'xxdadixx', 'sanshop4@hotmail.com', '$2y$10$VtI3vj3yTe1iwtRPB.ZFAOqTKPFuW8qX/V5FsGB3sv405SmCFivLO', '0910711894', '1509966122528', 'xxdadixx62850a83c12793.88386579.jpg', '2001-03-08', '50', '174', '2022-05-10 02:58:14', '2022-05-10 02:58:14'),
(19, 'asd', 'asd', 'xxdadixx1', 'sanshop3@hotmail.com', '$2y$10$1VJsOlZ05KxXu5cDGtw5luz/2zeth.928pbLHQEVu2vUvtZrG/7r.', '0863992217', '1509966122527', 'default-image.png', '2014-07-08', '-', '-', '2022-05-10 03:34:00', '2022-05-10 03:34:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
