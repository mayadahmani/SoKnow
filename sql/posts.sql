SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `posts`
-- Stores help requests created from the dashboard.

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `doc_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Indexes for table `posts`
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_posts_user` (`user_id`),
  ADD KEY `idx_posts_created_at` (`created_at`);

-- AUTO_INCREMENT for table `posts`
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Constraints for table `posts`
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;

-- --------------------------------------------------------
-- Run these ALTER statements if the table already exists:
-- ALTER TABLE `posts` ADD COLUMN `image_path` varchar(500) DEFAULT NULL AFTER `content`;
-- ALTER TABLE `posts` ADD COLUMN `doc_path`   varchar(500) DEFAULT NULL AFTER `image_path`;
