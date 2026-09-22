-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 02 ديسمبر 2025 الساعة 21:48
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventhub`
--

-- --------------------------------------------------------

--
-- بنية الجدول `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` enum('Credit Card','Mada','Apple Pay','STC Pay') NOT NULL,
  `payment_status` enum('Pending','Completed','Failed','Refunded') DEFAULT 'Pending',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_status` enum('Active','Cancelled','Completed') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `event_id`, `ticket_quantity`, `total_price`, `payment_method`, `payment_status`, `booking_date`, `booking_status`) VALUES
(11, 11, 16, 1, 350.00, '', '', '2025-12-02 08:37:46', ''),
(12, 10, 16, 1, 350.00, '', '', '2025-12-02 10:02:23', '');

--
-- القوادح `bookings`
--
DELIMITER $$
CREATE TRIGGER `trg_update_tickets_after_booking` AFTER INSERT ON `bookings` FOR EACH ROW BEGIN
    IF NEW.payment_status = 'Completed' THEN
        UPDATE events
        SET tickets_sold = tickets_sold + NEW.ticket_quantity,
            tickets_available = tickets_available - NEW.ticket_quantity
        WHERE event_id = NEW.event_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Education', 'Educational workshops and training sessions', '2025-11-01 21:15:36'),
(2, 'Entertainment', 'Music, comedy, and entertainment events', '2025-11-01 21:15:36'),
(3, 'Kids & Family', 'Family-friendly events and activities', '2025-11-01 21:15:36'),
(4, 'Social Events', 'Networking and social gatherings', '2025-11-01 21:15:36');

-- --------------------------------------------------------

--
-- بنية الجدول `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `event_name` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(50) NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `tickets_sold` int(11) DEFAULT 0,
  `tickets_available` int(11) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `event_status` enum('Pending','Approved','Rejected','Cancelled','Completed') DEFAULT 'Pending',
  `admin_feedback` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `events`
--

INSERT INTO `events` (`event_id`, `organizer_id`, `category_id`, `venue_id`, `event_name`, `description`, `event_date`, `event_time`, `ticket_price`, `capacity`, `tickets_sold`, `tickets_available`, `image_path`, `event_status`, `admin_feedback`, `created_at`, `updated_at`) VALUES
(16, 11, 1, 1, 'Digital Marketing Workshop 2025', 'Master the art of digital marketing in this comprehensive workshop designed for professionals and entrepreneurs.', '2025-11-15', '14:00', 350.00, 100, 2, 98, 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=800&q=80', 'Approved', NULL, '2025-11-01 21:15:37', '2025-12-02 20:42:32'),
(18, 11, 3, 3, 'Kids Science Fair 2025', 'An exciting and educational science fair designed for children! Watch amazing experiments and participate in hands-on activities.', '2025-11-18', '10:00', 100.00, 300, 0, 300, 'https://images.unsplash.com/photo-1523294587484-bae6cc870010?auto=format&fit=crop&w=800&q=80', 'Approved', NULL, '2025-11-01 21:15:37', '2025-12-01 19:36:06'),
(19, 11, 4, 4, 'Business Networking Summit', 'Connect with business leaders, entrepreneurs, and professionals in Riyadh premier networking event.', '2025-11-25', '18:00', 250.00, 200, 0, 200, 'https://images.unsplash.com/photo-1556761175-129418cb2dfe?auto=format&fit=crop&w=800&q=80', 'Approved', NULL, '2025-11-01 21:15:37', '2025-12-02 20:42:32'),
(20, 11, 1, 5, 'AI & Technology Conference', 'Explore the future of artificial intelligence and emerging technologies at this comprehensive conference.', '2025-12-10', '09:00', 750.00, 150, 0, 150, 'https://images.unsplash.com/photo-1535223289827-42f1e9919769?auto=format&fit=crop&w=800&q=80', 'Approved', NULL, '2025-11-01 21:15:37', '2025-12-02 20:43:08');

--
-- القوادح `events`
--
DELIMITER $$
CREATE TRIGGER `trg_event_tickets_available` BEFORE INSERT ON `events` FOR EACH ROW BEGIN
    SET NEW.tickets_available = NEW.capacity;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- بنية الجدول `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type` varchar(100) NOT NULL,
  `title` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `related_event_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_via` enum('Email','SMS','Both','System') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `notification_type`, `title`, `message`, `related_event_id`, `is_read`, `sent_via`, `created_at`) VALUES
(13, 11, 'Booking Confirmation', 'Booking Confirmed', 'Your booking for event \"Digital Marketing Workshop 2025\" is confirmed.', 16, 0, 'Both', '2025-12-02 08:37:47'),
(14, 10, 'Booking Confirmation', 'Booking Confirmed', 'Your booking for event \"Digital Marketing Workshop 2025\" is confirmed.', 16, 0, 'Both', '2025-12-02 10:02:23');

-- --------------------------------------------------------

--
-- بنية الجدول `support_issues`
--

CREATE TABLE `support_issues` (
  `issue_id` int(11) NOT NULL,
  `support_user_id` int(11) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `issue_status` enum('Open','In Progress','Resolved','Closed') DEFAULT 'Open',
  `priority` enum('Low','Medium','High','Critical') DEFAULT 'Medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `support_issues`
--

INSERT INTO `support_issues` (`issue_id`, `support_user_id`, `subject`, `description`, `issue_status`, `priority`, `created_at`, `resolved_at`) VALUES
(1, 14, 'ff', 'fff', 'Open', 'Medium', '2025-11-21 16:14:36', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `support_messages`
--

CREATE TABLE `support_messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `support_user_id` int(11) DEFAULT NULL,
  `message_text` text NOT NULL,
  `sender_type` enum('User','Support') NOT NULL,
  `conversation_status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `qr_code` varchar(500) NOT NULL,
  `ticket_status` enum('Valid','Used','Cancelled','Expired') DEFAULT 'Valid',
  `checked_in` tinyint(1) DEFAULT 0,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `booking_id`, `user_id`, `event_id`, `qr_code`, `ticket_status`, `checked_in`, `check_in_time`, `created_at`) VALUES
(11, 11, 11, 16, '2a0343d5c7e22636112bd91635dcd62f8ed4cee4408d4f98d00f7ce82a6753e3', '', 0, NULL, '2025-12-02 08:37:47'),
(12, 12, 10, 16, '0d6207bc7456954b7f64eec4544928c50274dc4d1d37f1ffd3b1ea613a652591', '', 0, NULL, '2025-12-02 10:02:23');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_role` enum('Event Enthusiast','Event Organizer','Admin','Support') NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `phone_verified` tinyint(1) DEFAULT 0,
  `notification_enabled` tinyint(1) DEFAULT 1,
  `notification_type` enum('All Updates','Only Important','None') DEFAULT 'All Updates',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `account_status` enum('Active','Suspended','Deleted') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone_number`, `password_hash`, `user_role`, `email_verified`, `phone_verified`, `notification_enabled`, `notification_type`, `created_at`, `updated_at`, `last_login`, `account_status`) VALUES
(10, 'rania', 'user@gmail.com', '0522222222', '$2y$10$hFE3zeXecYzGsCOKXOyZX.YadJcq22N3ce1T3Qv.7Sl9Y3lEgPKJO', 'Event Enthusiast', 0, 0, 1, 'All Updates', '2025-11-01 21:24:09', '2025-11-22 16:19:30', '2025-11-02 12:37:42', 'Active'),
(11, 'sara', 'orgnizer@gmail.com', '0533333333', '$2y$10$yAX7bQtZ9.1K24TFV9E3y.gBoSDETRgOOXent7pMMnZaSozVgBvWK', 'Event Organizer', 0, 0, 1, 'All Updates', '2025-11-01 21:25:46', '2025-11-22 16:16:26', '2025-11-02 17:42:35', 'Active'),
(12, 'yara', 'admin1@eventhub.com', '0544444444', '$2y$10$8JeQwwPjZJuuUSmSEY6/juIonmfyVm2qvQKauCcMewcaZ.rU8SMdi', 'Admin', 0, 0, 1, 'All Updates', '2025-11-01 21:39:32', '2025-11-22 16:17:33', '2025-11-21 12:11:11', 'Active'),
(14, 'nora', 'support1@eventhub.com', '0555555555', '$2y$10$Dwu82SUbVjriKaEVPpFGmO5UOutRe8sQtDPye8xupdKZETfq9S4UW', 'Support', 0, 0, 1, 'All Updates', '2025-11-01 21:41:46', '2025-11-22 16:18:27', '2025-11-21 12:14:14', 'Active');

-- --------------------------------------------------------

--
-- بنية الجدول `venues`
--

CREATE TABLE `venues` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(200) NOT NULL,
  `location` varchar(500) NOT NULL,
  `city` varchar(100) DEFAULT 'Riyadh',
  `address` varchar(500) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `venues`
--

INSERT INTO `venues` (`venue_id`, `venue_name`, `location`, `city`, `address`, `capacity`, `description`, `created_at`) VALUES
(1, 'Riyadh Conference Center', 'Al Narjis, Riyadh', 'Riyadh', NULL, 500, 'Modern conference facility with state-of-the-art equipment', '2025-11-01 21:15:37'),
(2, 'Al Olaya Hall', 'Al Olaya, Riyadh', 'Riyadh', NULL, 1000, 'Large venue suitable for concerts and festivals', '2025-11-01 21:15:37'),
(3, 'Family Park Arena', 'Al Munsiyah, Riyadh', 'Riyadh', NULL, 300, 'Family-friendly outdoor venue', '2025-11-01 21:15:37'),
(4, 'KAFD Convention Hall', 'King Abdullah Financial District, Riyadh', 'Riyadh', NULL, 200, 'Premium business event venue', '2025-11-01 21:15:37'),
(5, 'Tech Hub Auditorium', 'Al Sahafa, Riyadh', 'Riyadh', NULL, 150, 'Technology-focused event space', '2025-11-01 21:15:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_event` (`event_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `idx_status` (`event_status`),
  ADD KEY `idx_date` (`event_date`),
  ADD KEY `idx_organizer` (`organizer_id`),
  ADD KEY `idx_category` (`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `related_event_id` (`related_event_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `support_issues`
--
ALTER TABLE `support_issues`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `support_user_id` (`support_user_id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `support_user_id` (`support_user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `qr_code` (`qr_code`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_event` (`event_id`),
  ADD KEY `idx_qr` (`qr_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_phone` (`phone_number`),
  ADD KEY `idx_role` (`user_role`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `support_issues`
--
ALTER TABLE `support_issues`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- قيود الجداول `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`) ON DELETE CASCADE;

--
-- قيود الجداول `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`related_event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- قيود الجداول `support_issues`
--
ALTER TABLE `support_issues`
  ADD CONSTRAINT `support_issues_ibfk_1` FOREIGN KEY (`support_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- قيود الجداول `support_messages`
--
ALTER TABLE `support_messages`
  ADD CONSTRAINT `support_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_messages_ibfk_2` FOREIGN KEY (`support_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- قيود الجداول `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
