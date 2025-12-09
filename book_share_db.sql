-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 02:48 AM
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
-- Database: `book_share_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','exchanged') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `user_id`, `title`, `author`, `description`, `image`, `status`, `created_at`) VALUES
(1, 1, 'ความสุขของกะทิ', 'งามพรรณ เวชชาชีวะ', 'เรื่องราวของเด็กหญิงกะทิกับชีวิตที่เรียบง่าย', 'images/kati.jpg', 'available', '2025-10-20 03:42:20'),
(2, 2, 'สี่แผ่นดิน', 'ม.ร.ว. คึกฤทธิ์ ปราโมช', 'เรื่องราวชีวิตของแม่พลอยในสี่รัชกาล', 'images/si-paen-din.jpg', 'available', '2025-10-20 03:42:20'),
(3, 3, 'ปีศาจ', 'เสนีย์ เสาวพงศ์', 'นวนิยายสะท้อนปัญหาสังคม การต่อสู้ของคนรุ่นใหม่', NULL, 'available', '2025-10-20 03:42:20'),
(4, 1, 'ข้างหลังภาพ', 'ศรีบูรพา', 'ความรักต่างวัยอันเป็นอมตะ ระหว่างคุณหญิงกีรติกับนพพร', 'images/behind-picture.jpg', 'exchanged', '2025-10-20 03:42:20'),
(5, 4, 'บุพเพสันนิวาส', 'รอมแพง', 'นวนิยายย้อนยุคที่โด่งดัง เกี่ยวกับเกศสุรางศในร่างการะเกด', 'images/buppesanniwat.jpg', 'available', '2025-10-20 03:42:20'),
(6, 5, '1984', 'George Orwell', 'A dystopian novel set in a totalitarian society under surveillance.', 'images/1984.jpg', 'available', '2025-10-20 03:42:20'),
(7, 2, 'The Lord of the Rings', 'J.R.R. Tolkien', 'High-fantasy epic about the quest to destroy the One Ring.', 'images/lotr.jpg', 'available', '2025-10-20 03:42:20'),
(8, 3, 'To Kill a Mockingbird', 'Harper Lee', 'A novel about injustice and racial inequality in the American South.', NULL, 'available', '2025-10-20 03:42:20'),
(9, 1, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'First book in the famous fantasy series about a young wizard.', 'images/hp1.jpg', 'available', '2025-10-20 03:42:20'),
(10, 5, 'The Great Gatsby', 'F. Scott Fitzgerald', 'A critique of the American Dream set in the Roaring Twenties.', 'images/gatsby.jpg', 'available', '2025-10-20 03:42:20'),
(11, 4, 'Dune', 'Frank Herbert', 'Epic science fiction novel set on the desert planet Arrakis.', 'images/dune.jpg', 'available', '2025-10-20 03:42:20'),
(12, 2, 'The Alchemist', 'Paulo Coelho', 'A philosophical story about a shepherd named Santiago who journeys in search of treasure.', NULL, 'exchanged', '2025-10-20 03:42:20'),
(13, 3, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'A look at the history of humankind, from the Stone Age to the present.', 'images/sapiens.jpg', 'available', '2025-10-20 03:42:20'),
(14, 1, 'Atomic Habits', 'James Clear', 'A guide to building good habits and breaking bad ones.', 'images/atomic-habits.jpg', 'available', '2025-10-20 03:42:20'),
(15, 5, 'Pride and Prejudice', 'Jane Austen', 'A classic novel of manners, focusing on the protagonist Elizabeth Bennet.', NULL, 'available', '2025-10-20 03:42:20'),
(16, 4, 'The Hobbit', 'J.R.R. Tolkien', 'Prequel to The Lord of the Rings, following Bilbo Baggins.', 'images/hobbit.jpg', 'available', '2025-10-20 03:42:20'),
(17, 2, 'แปดวัน', 'วินทร์ เลียววาริณ', 'นวนิยายแนวสืบสวนสอบสวน', 'images/8days.jpg', 'available', '2025-10-20 03:42:20'),
(18, 3, 'ผู้ชนะสิบทิศ', 'ยาขอบ', 'มหากาพย์อิงประวัติศาสตร์พม่าและมอญ', NULL, 'available', '2025-10-20 03:42:20'),
(19, 1, 'กาษานาคา', 'กิ่งฉัตร', 'เรื่องราวความรัก ความแค้น และพญานาค', 'images/kasanaka.jpg', 'available', '2025-10-20 03:42:20'),
(20, 5, 'The Catcher in the Rye', 'J.D. Salinger', 'A story about teenage angst and alienation.', 'images/catcher.jpg', 'available', '2025-10-20 03:42:20');

-- --------------------------------------------------------

--
-- Table structure for table `exchanges`
--

CREATE TABLE `exchanges` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL COMMENT 'ID เจ้าของหนังสือ',
  `requester_id` int(11) NOT NULL COMMENT 'ID ผู้ขอแลกเปลี่ยน',
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','member') NOT NULL DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$c6LLJSLevj0tY4uSDja4wOXTwLcOjYA1Cc5DwoeC/Q9EyjylwqA9O', 'admin', '2025-09-22 15:32:45'),
(2, 'gunn', '$2y$10$FyXpW7x6a96Bw.Ht6efRauS10Hx2ZerUxHiwwOJ2XgrNGdrXe5.Py', 'member', '2025-09-22 15:52:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_books_user` (`user_id`);

--
-- Indexes for table `exchanges`
--
ALTER TABLE `exchanges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `exchanges`
--
ALTER TABLE `exchanges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
