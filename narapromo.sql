-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 07, 2025 at 05:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `narapromo`
--

-- --------------------------------------------------------

--
-- Table structure for table `boxers`
--

CREATE TABLE `boxers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `weight_class` varchar(255) NOT NULL,
  `wins` int(11) NOT NULL DEFAULT 0,
  `losses` int(11) NOT NULL DEFAULT 0,
  `draws` int(11) NOT NULL DEFAULT 0,
  `knockouts` int(11) NOT NULL DEFAULT 0,
  `kos_lost` int(11) NOT NULL DEFAULT 0,
  `age` int(11) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `reach` varchar(255) DEFAULT NULL,
  `stance` varchar(255) DEFAULT NULL,
  `hometown` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `full_bio` longtext DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `titles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`titles`)),
  `years_pro` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Professional',
  `global_ranking` int(11) DEFAULT NULL,
  `total_fighters_in_division` int(11) DEFAULT NULL,
  `career_start` year(4) DEFAULT NULL,
  `career_end` year(4) DEFAULT NULL,
  `debut_date` date DEFAULT NULL,
  `knockout_rate` int(11) DEFAULT NULL,
  `win_rate` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boxers`
--

INSERT INTO `boxers` (`id`, `name`, `slug`, `nickname`, `weight_class`, `wins`, `losses`, `draws`, `knockouts`, `kos_lost`, `age`, `height`, `reach`, `stance`, `hometown`, `country`, `bio`, `full_bio`, `image_path`, `titles`, `years_pro`, `status`, `global_ranking`, `total_fighters_in_division`, `career_start`, `career_end`, `debut_date`, `knockout_rate`, `win_rate`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 'Mike \"The Thunder\" Johnson', 'mike-the-thunder-johnson', 'Seru', 'Heavyweight', 28, 2, 1, 24, 1, 29, '6\'4\"', '78\"', 'Orthodox', 'Brooklyn', 'USA', 'Known for his devastating knockout power, Mike \"The Thunder\" Johnson has been dominating the heavyweight division with his explosive fighting style.', NULL, 'assets/images/boxers/boxer1.jpg', '\"[\\\"WBC Heavyweight Champion\\\",\\\"IBF Heavyweight Champion\\\"]\"', 8, 'active', 1, 278, NULL, NULL, '2017-06-07', 86, 90, 1, 0, '2025-06-07 05:31:39', '2025-06-07 10:37:14'),
(2, 'Carlos \"Lightning\" Rodriguez', 'carlos-lightning-rodriguez', NULL, 'Welterweight', 32, 1, 0, 18, 0, 27, '5\'10\"', '72\"', 'Southpaw', 'Mexico City', 'Mexico', 'A technical boxer with lightning-fast combinations, Carlos Rodriguez is known for his precision and ring IQ.', NULL, 'assets/images/boxers/boxer2.jpg', '\"[\\\"WBA Welterweight Champion\\\"]\"', 9, 'active', 1, 173, NULL, NULL, '2016-06-07', 55, 97, 1, 0, '2025-06-07 05:31:39', '2025-06-07 05:31:39'),
(3, 'Anthony \"The Beast\" Williams', 'anthony-the-beast-williams', NULL, 'Light Heavyweight', 25, 3, 2, 20, 2, 31, '6\'1\"', '74\"', 'Orthodox', 'London', 'UK', 'A power puncher with an aggressive style, Anthony Williams has built his reputation on delivering spectacular knockouts.', NULL, 'assets/images/boxers/boxer3.jpg', '\"[\\\"WBO Light Heavyweight Champion\\\"]\"', 10, 'active', 2, 187, NULL, NULL, '2015-06-07', 80, 83, 1, 0, '2025-06-07 05:31:39', '2025-06-07 05:31:39'),
(4, 'David \"The Ugandan Lion\" Ssemujju', 'david-the-ugandan-lion-ssemujju', NULL, 'Middleweight', 22, 1, 1, 16, 0, 26, '5\'11\"', '73\"', 'Orthodox', 'Kampala', 'Uganda', 'Pride of Uganda, David Ssemujju has quickly risen through the ranks with his combination of power and technical skill.', NULL, 'assets/images/boxers/boxer4.jpg', '\"[\\\"East African Middleweight Champion\\\"]\"', 6, 'active', 5, 261, NULL, NULL, '2019-06-07', 73, 92, 1, 0, '2025-06-07 05:31:39', '2025-06-07 05:31:39'),
(5, 'Tommy \"The Machine\" Chen', 'tommy-the-machine-chen', NULL, 'Featherweight', 30, 2, 0, 12, 0, 28, '5\'7\"', '68\"', 'Southpaw', 'Shanghai', 'China', 'Known for his relentless pace and conditioning, Tommy Chen overwhelms opponents with his work rate and precision.', NULL, 'assets/images/boxers/boxer5.jpg', '\"[\\\"WBC Featherweight Champion\\\"]\"', 7, 'active', 1, 257, NULL, NULL, '2018-06-07', 40, 94, 1, 0, '2025-06-07 05:31:39', '2025-06-07 05:31:39'),
(6, 'Roberto \"El Matador\" Fernandez', 'roberto-el-matador-fernandez', NULL, 'Super Welterweight', 27, 4, 1, 19, 2, 30, '5\'11\"', '74\"', 'Orthodox', 'Madrid', 'Spain', 'A crowd favorite known for his exciting fighting style and never-say-die attitude.', NULL, 'assets/images/boxers/boxer6.jpg', '\"[\\\"European Super Welterweight Champion\\\"]\"', 9, 'active', 3, 274, NULL, NULL, '2016-06-07', 70, 84, 1, 0, '2025-06-07 05:31:39', '2025-06-07 05:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `boxer_boxing_event`
--

CREATE TABLE `boxer_boxing_event` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boxer_id` bigint(20) UNSIGNED NOT NULL,
  `boxing_event_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `is_attending` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boxer_boxing_video`
--

CREATE TABLE `boxer_boxing_video` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boxer_id` bigint(20) UNSIGNED NOT NULL,
  `boxing_video_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boxer_news_article`
--

CREATE TABLE `boxer_news_article` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boxer_id` bigint(20) UNSIGNED NOT NULL,
  `news_article_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boxing_events`
--

CREATE TABLE `boxing_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `full_description` longtext DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `venue` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `network` varchar(255) DEFAULT NULL,
  `broadcast_type` varchar(255) DEFAULT NULL,
  `ppv_price` decimal(10,2) DEFAULT NULL,
  `stream_price` decimal(10,2) DEFAULT NULL,
  `early_access_stream` tinyint(1) NOT NULL DEFAULT 0,
  `require_ticket_for_stream` tinyint(1) NOT NULL DEFAULT 1,
  `image_path` varchar(255) DEFAULT NULL,
  `banner_path` varchar(255) DEFAULT NULL,
  `promo_video_url` varchar(255) DEFAULT NULL,
  `poster_image` varchar(255) DEFAULT NULL,
  `promo_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`promo_images`)),
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `weigh_in_photos` longtext DEFAULT NULL,
  `press_conference_photos` longtext DEFAULT NULL,
  `behind_scenes_photos` longtext DEFAULT NULL,
  `highlight_videos` longtext DEFAULT NULL,
  `gallery_videos` longtext DEFAULT NULL,
  `sponsors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sponsors`)),
  `status` varchar(255) NOT NULL DEFAULT 'upcoming',
  `weight_class` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `rounds` int(11) NOT NULL DEFAULT 12,
  `event_type` varchar(255) NOT NULL DEFAULT 'regular',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_ppv` tinyint(1) NOT NULL DEFAULT 0,
  `has_stream` tinyint(1) NOT NULL DEFAULT 0,
  `stream_url` text DEFAULT NULL,
  `stream_backup_url` text DEFAULT NULL,
  `youtube_stream_id` varchar(255) DEFAULT NULL,
  `stream_password` varchar(255) DEFAULT NULL,
  `stream_starts_at` datetime DEFAULT NULL,
  `stream_ends_at` datetime DEFAULT NULL,
  `main_event_boxer_1_id` bigint(20) UNSIGNED DEFAULT NULL,
  `main_event_boxer_2_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `tickets_available` tinyint(1) NOT NULL DEFAULT 1,
  `live_gate_open` tinyint(1) NOT NULL DEFAULT 1,
  `min_ticket_price` decimal(10,2) DEFAULT NULL,
  `max_ticket_price` decimal(10,2) DEFAULT NULL,
  `ticket_purchase_url` varchar(255) DEFAULT NULL,
  `meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_data`)),
  `organizer` varchar(255) DEFAULT NULL,
  `promoter` varchar(255) DEFAULT NULL,
  `sanctioning_body` varchar(255) DEFAULT NULL,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boxing_events`
--

INSERT INTO `boxing_events` (`id`, `name`, `tagline`, `slug`, `description`, `full_description`, `event_date`, `event_time`, `venue`, `city`, `country`, `address`, `network`, `broadcast_type`, `ppv_price`, `stream_price`, `early_access_stream`, `require_ticket_for_stream`, `image_path`, `banner_path`, `promo_video_url`, `poster_image`, `promo_images`, `photos`, `weigh_in_photos`, `press_conference_photos`, `behind_scenes_photos`, `highlight_videos`, `gallery_videos`, `sponsors`, `status`, `weight_class`, `title`, `rounds`, `event_type`, `is_featured`, `is_ppv`, `has_stream`, `stream_url`, `stream_backup_url`, `youtube_stream_id`, `stream_password`, `stream_starts_at`, `stream_ends_at`, `main_event_boxer_1_id`, `main_event_boxer_2_id`, `is_free`, `tickets_available`, `live_gate_open`, `min_ticket_price`, `max_ticket_price`, `ticket_purchase_url`, `meta_data`, `organizer`, `promoter`, `sanctioning_body`, `views_count`, `created_at`, `updated_at`) VALUES
(1, 'Championship Fight Night', 'Battle for the Belt', 'championship-fight-night', 'A thrilling night of championship boxing featuring the best fighters in the world.', NULL, '2025-07-07', NULL, 'Madison Square Garden', 'New York', 'USA', NULL, 'ESPN+', 'PPV', NULL, NULL, 0, 1, 'assets/images/events/event3.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'upcoming', 'heavyweight', NULL, 12, 'championship', 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 05:19:55', '2025-06-07 05:19:55'),
(2, 'Summer Showdown', 'Legends Collide', 'summer-showdown', 'The biggest boxing event of the summer featuring multiple title fights.', NULL, '2025-07-22', NULL, 'T-Mobile Arena', 'Las Vegas', 'USA', NULL, 'DAZN', 'Streaming', NULL, NULL, 0, 1, 'assets/images/events/event1.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'upcoming', 'welterweight', NULL, 12, 'title_defense', 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 05:19:55', '2025-06-07 05:19:55'),
(3, 'International Fight League', 'Global Boxing Showcase', 'international-fight-league', 'International boxing stars compete in a special tournament format.', NULL, '2025-08-06', NULL, 'O2 Arena', 'London', 'UK', NULL, 'Sky Sports', 'Cable', NULL, NULL, 0, 1, 'assets/images/events/event3.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'upcoming', 'middleweight', NULL, 12, 'tournament', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 05:19:55', '2025-06-07 05:19:55'),
(4, 'Spring Knockout', 'Champions Rise', 'spring-knockout', 'An action-packed night of boxing featuring multiple championship bouts.', NULL, '2025-05-08', NULL, 'Barclays Center', 'Brooklyn', 'USA', NULL, 'ESPN+', 'PPV', NULL, NULL, 0, 1, 'assets/images/events/event1.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'lightweight', NULL, 12, 'championship', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 05:19:55', '2025-06-07 05:19:55'),
(5, 'Winter Rumble', 'Battle of Champions', 'winter-rumble', 'Witness history as champions defend their titles against hungry challengers.', NULL, '2025-04-08', NULL, 'American Airlines Center', 'Dallas', 'USA', NULL, 'DAZN', 'Streaming', NULL, NULL, 0, 1, 'assets/images/events/event4.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'cruiserweight', NULL, 12, 'title_defense', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 05:19:55', '2025-06-07 05:19:55'),
(6, 'New Year Showdown', 'New Year, New Champions', 'new-year-showdown', 'Kick off the new year with explosive boxing action.', NULL, '2025-03-09', NULL, 'MGM Grand', 'Las Vegas', 'USA', NULL, 'Showtime', 'Cable', NULL, NULL, 0, 1, 'assets/images/events/event1.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'completed', 'super-middleweight', NULL, 12, 'regular', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-06-07 05:19:55', '2025-06-07 05:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `boxing_event_boxing_video`
--

CREATE TABLE `boxing_event_boxing_video` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boxing_event_id` bigint(20) UNSIGNED NOT NULL,
  `boxing_video_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boxing_event_news_article`
--

CREATE TABLE `boxing_event_news_article` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boxing_event_id` bigint(20) UNSIGNED NOT NULL,
  `news_article_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boxing_videos`
--

CREATE TABLE `boxing_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `video_type` varchar(255) NOT NULL,
  `video_id` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `boxer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'published',
  `views_count` int(11) NOT NULL DEFAULT 0,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_data`)),
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boxing_videos`
--

INSERT INTO `boxing_videos` (`id`, `title`, `slug`, `description`, `thumbnail`, `thumbnail_path`, `video_type`, `video_id`, `video_url`, `video_path`, `boxer_id`, `event_id`, `duration`, `is_premium`, `is_featured`, `status`, `views_count`, `likes_count`, `published_at`, `tags`, `meta_data`, `category`, `created_at`, `updated_at`) VALUES
(10, 'Championship Fight Highlights', 'championship-fight-highlights', 'Highlights from the epic championship bout that had fans on their feet.', NULL, 'assets/images/videos/video1.webp', 'highlights', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '12:34', 0, 1, 'published', 2541, 0, '2025-06-02 08:28:23', '\"[\\\"highlights\\\",\\\"championship\\\",\\\"knockout\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23'),
(11, 'Pre-Fight Interview - Main Event', 'pre-fight-interview-main-event', 'Exclusive interview with both fighters before their highly anticipated showdown.', NULL, 'assets/images/videos/video2.webp', 'interview', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '08:45', 0, 1, 'published', 1876, 0, '2025-05-31 08:28:23', '\"[\\\"interview\\\",\\\"pre-fight\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23'),
(12, 'Training Camp: Behind the Scenes', 'training-camp-behind-the-scenes', 'Exclusive access to the champion\'s training camp as they prepare for their title defense.', NULL, 'assets/images/videos/video3.webp', 'documentary', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '15:20', 1, 1, 'published', 942, 0, '2025-05-28 08:28:23', '\"[\\\"training\\\",\\\"documentary\\\",\\\"behind the scenes\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23'),
(13, 'Knockout of the Year Contender', 'knockout-of-the-year-contender', 'The spectacular knockout that has everyone talking - a strong contender for KO of the year.', NULL, 'assets/images/videos/video4.webp', 'highlights', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '02:15', 0, 1, 'published', 5284, 0, '2025-05-23 08:28:23', '\"[\\\"knockout\\\",\\\"highlights\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23'),
(14, 'Post-Fight Press Conference', 'post-fight-press-conference', 'Complete post-fight press conference with fighters and promoters discussing the event.', NULL, 'assets/images/videos/video5.webp', 'press conference', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '42:18', 1, 1, 'published', 1209, 0, '2025-06-01 08:28:23', '\"[\\\"press conference\\\",\\\"post-fight\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23'),
(15, 'Fight Analysis with Boxing Experts', 'fight-analysis-with-boxing-experts', 'In-depth technical breakdown of the championship fight with expert commentary.', NULL, 'assets/images/videos/video6.webp', 'analysis', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '18:45', 1, 1, 'published', 873, 0, '2025-06-03 08:28:23', '\"[\\\"analysis\\\",\\\"expert\\\",\\\"technical\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23'),
(16, 'Rising Star Profile: Future Champion', 'rising-star-profile-future-champion', 'Profile of the exciting prospect who\'s making waves in the boxing world.', NULL, 'assets/images/videos/video7.webp', 'profile', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '10:30', 0, 1, 'published', 1456, 0, '2025-05-26 08:28:23', '\"[\\\"profile\\\",\\\"prospect\\\",\\\"rising star\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23'),
(17, 'Historical Fight: Title Unification Classic', 'historical-fight-title-unification-classic', 'Relive one of boxing\'s most memorable unification bouts from the archives.', NULL, 'assets/images/videos/video8.webp', 'full fight', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL, NULL, '28:15', 1, 1, 'published', 2187, 0, '2025-05-18 08:28:23', '\"[\\\"classic\\\",\\\"historical\\\",\\\"unification\\\"]\"', NULL, NULL, '2025-06-07 05:28:23', '2025-06-07 05:28:23');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('narapromotionz_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1749303488),
('narapromotionz_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1749303488;', 1749303488),
('narapromotionz_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1749303317),
('narapromotionz_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1749303317;', 1749303317),
('narapromotionz_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1749389657);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_tickets`
--

CREATE TABLE `event_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boxing_event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'USD',
  `quantity_available` int(11) NOT NULL DEFAULT 0,
  `quantity_sold` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image_path` varchar(255) DEFAULT NULL,
  `ticket_type` varchar(255) NOT NULL DEFAULT 'regular',
  `seating_area` varchar(255) DEFAULT NULL,
  `ticket_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ticket_features`)),
  `seating_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seating_info`)),
  `sale_starts_at` datetime DEFAULT NULL,
  `sale_ends_at` datetime DEFAULT NULL,
  `max_per_purchase` int(11) NOT NULL DEFAULT 10,
  `transferable` tinyint(1) NOT NULL DEFAULT 1,
  `benefits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`benefits`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_tickets`
--

INSERT INTO `event_tickets` (`id`, `boxing_event_id`, `name`, `description`, `price`, `currency`, `quantity_available`, `quantity_sold`, `is_active`, `image_path`, `ticket_type`, `seating_area`, `ticket_features`, `seating_info`, `sale_starts_at`, `sale_ends_at`, `max_per_purchase`, `transferable`, `benefits`, `created_at`, `updated_at`) VALUES
(1, 1, 'General Admission', 'Standard seating with great views of the action.', 75.00, 'USD', 500, 0, 1, NULL, 'general', NULL, NULL, NULL, '2025-06-07 09:56:34', '2025-07-06 22:00:00', 8, 1, '[\"Access to event\",\"Standard seating\",\"Concession access\"]', '2025-06-07 06:56:34', '2025-06-07 06:56:34'),
(2, 1, 'VIP Seating', 'Premium seating with enhanced amenities and closer views.', 150.00, 'USD', 200, 0, 1, NULL, 'vip', NULL, NULL, NULL, '2025-06-07 09:56:34', '2025-07-06 22:00:00', 6, 1, '[\"Premium seating\",\"Complimentary drinks\",\"VIP entrance\",\"Event program\",\"Meet & greet opportunity\"]', '2025-06-07 06:56:34', '2025-06-07 06:56:34'),
(3, 1, 'Ringside', 'The ultimate boxing experience with ringside seats.', 300.00, 'USD', 50, 0, 1, NULL, 'ringside', NULL, NULL, NULL, '2025-06-07 09:56:34', '2025-07-06 22:00:00', 4, 1, '[\"Ringside seating\",\"Premium bar access\",\"Exclusive entrance\",\"Signed memorabilia\",\"Photo opportunities\",\"Post-fight reception\"]', '2025-06-07 06:56:34', '2025-06-07 06:56:34'),
(4, 1, 'Student Discount', 'Special pricing for students with valid ID.', 45.00, 'USD', 100, 0, 1, NULL, 'student', NULL, NULL, NULL, '2025-06-07 09:56:34', '2025-07-06 22:00:00', 2, 1, '[\"Student pricing\",\"Standard seating\",\"Valid student ID required\"]', '2025-06-07 06:56:34', '2025-06-07 06:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fight_records`
--

CREATE TABLE `fight_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `boxer_id` bigint(20) UNSIGNED NOT NULL,
  `opponent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `boxing_event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fight_date` date NOT NULL,
  `result` varchar(255) NOT NULL,
  `method` varchar(255) DEFAULT NULL,
  `rounds` int(11) DEFAULT NULL,
  `round_time` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `title_fight` varchar(255) DEFAULT NULL,
  `weight_class` varchar(255) DEFAULT NULL,
  `is_main_event` tinyint(1) NOT NULL DEFAULT 0,
  `referee` varchar(255) DEFAULT NULL,
  `judges` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`judges`)),
  `scorecards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scorecards`)),
  `video_id` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_sliders`
--

CREATE TABLE `hero_sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) DEFAULT NULL COMMENT 'Path to the slider image',
  `title` varchar(255) DEFAULT NULL COMMENT 'Main heading text, can contain HTML',
  `subtitle` text DEFAULT NULL COMMENT 'Sub-heading paragraph text',
  `cta_text` varchar(255) DEFAULT NULL COMMENT 'Call-to-action button text',
  `cta_link` varchar(255) DEFAULT NULL COMMENT 'Call-to-action button URL',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT 'Display order of slides',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Toggle slide visibility',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_sliders`
--

INSERT INTO `hero_sliders` (`id`, `image_path`, `title`, `subtitle`, `cta_text`, `cta_link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'hero-slider/01JX206PB4PND8F2AK0KE2QWS9.jpg', 'Coach IVO <span>IN ACTION - YMCA</span>', 'Busegas king showcases talent at YMCA - Sweet science season 4', 'View More Fights', 'http://127.0.0.1:8000/', 1, 1, '2025-06-06 03:13:48', '2025-06-06 03:54:34'),
(2, 'hero-slider/01JX20F5BFJ9GGKXE91ZVK46K4.jpg', 'Herbert <span>Poses for a photo</span>', 'Herbert matovu poses for a photo with fans after his win at sweet science S2', 'View Gallery', 'http://127.0.0.1:8000/', 2, 1, '2025-06-06 03:13:48', '2025-06-06 03:58:15'),
(3, 'hero-slider/01JX20P038PKYB7XB4G001GNRV.jpg', 'A photo with <span>JOHN SERUNJOGI - ABU</span>', 'Mr Lubowa Babu Hussein on the right, John in the middle, after a win', 'View Gallery', 'http://127.0.0.1:8000/', 3, 1, '2025-06-06 03:13:48', '2025-06-06 04:01:01'),
(4, 'hero-slider/01JX20ZMECFPSNDWGSCTVPHSER.jpg', 'YMCA <span>AT FULL POTEMTIAL - SS4</span>', 'YMCA - Stadium at full capacity during sweet science season 4', 'View More', 'http://127.0.0.1:8000/admin/hero-sliders', 4, 1, '2025-06-06 03:13:48', '2025-06-06 04:06:16');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_05_125652_create_permission_tables', 2),
(5, '2025_06_05_141200_create_news_table', 3),
(6, '2025_06_05_141206_create_news_comments_table', 3),
(7, '2025_06_05_142603_create_news_categories_table', 4),
(8, '2025_06_05_142608_create_news_articles_table', 4),
(9, '2025_06_05_142613_create_news_tags_table', 4),
(10, '2025_06_05_142618_create_news_comments_table', 4),
(11, '2025_06_05_142623_create_news_article_category_table', 4),
(12, '2025_06_05_142641_create_news_article_tag_table', 4),
(13, '2025_06_05_145034_add_missing_columns_to_news_categories_table', 5),
(14, '2025_06_05_173817_add_fields_to_users_table', 6),
(15, '2025_06_05_173823_update_news_articles_to_use_users', 6),
(16, '2025_06_05_175600_update_news_comments_table_structure', 7),
(17, '2025_06_06_061112_create_hero_sliders_table', 8),
(18, '2025_06_06_204704_add_is_main_article_to_news_articles_table', 9),
(19, '2023_10_18_create_boxers_table', 10),
(20, '2023_10_18_create_boxing_events_table', 10),
(21, '2023_10_18_create_boxing_videos_table', 10),
(22, '2023_10_18_create_event_tickets_table', 10),
(23, '2023_10_18_create_fight_records_table', 10),
(24, '2023_10_18_create_pivot_tables', 10),
(25, '2023_10_18_create_ticket_purchases_table', 10),
(26, '2023_10_18_create_ticket_templates_table', 10),
(27, '2025_06_07_081923_add_missing_fields_to_boxing_events_table', 11),
(28, '2025_06_07_082223_add_thumbnail_path_to_boxing_videos_table', 12),
(29, '2025_06_07_090920_add_streaming_and_media_columns_to_boxing_events_table', 13),
(30, '2025_06_07_092618_add_max_per_purchase_to_event_tickets_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `news_articles`
--

CREATE TABLE `news_articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_main_article` tinyint(1) NOT NULL DEFAULT 0,
  `allow_comments` tinyint(1) NOT NULL DEFAULT 1,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `comments_count` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `reading_time` int(11) DEFAULT NULL,
  `seo_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_articles`
--

INSERT INTO `news_articles` (`id`, `user_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `status`, `published_at`, `is_featured`, `is_main_article`, `allow_comments`, `views_count`, `comments_count`, `meta_title`, `meta_description`, `meta_keywords`, `reading_time`, `seo_data`, `created_at`, `updated_at`) VALUES
(1, 2, 'What mistakes you are making while muscle building', 'what-mistakes-you-are-making-while-muscle-building', 'Discover the common mistakes that prevent muscle growth and learn how to avoid them for better results.', '<p>Building muscle is a complex process that requires dedication, proper nutrition, and smart training. However, many fitness enthusiasts make critical mistakes that hinder their progress. Here are the most common muscle-building mistakes and how to avoid them.</p>\n\n<h3>1. Not Eating Enough Protein</h3>\n<p>Protein is the building block of muscle tissue. Without adequate protein intake, your muscles cannot repair and grow effectively. Aim for 1.6-2.2 grams of protein per kilogram of body weight daily.</p>\n\n<h3>2. Inconsistent Training</h3>\n<p>Muscle growth requires progressive overload and consistency. Skipping workouts or constantly changing your routine prevents your muscles from adapting and growing.</p>\n\n<h3>3. Ignoring Compound Movements</h3>\n<p>Isolation exercises have their place, but compound movements like squats, deadlifts, and bench presses should form the foundation of your routine.</p>\n\n<h3>4. Not Getting Enough Sleep</h3>\n<p>Sleep is when your body recovers and builds muscle. Aim for 7-9 hours of quality sleep each night to maximize your gains.</p>\n\n<h3>5. Rushing the Process</h3>\n<p>Muscle building takes time. Be patient and trust the process. Results typically become noticeable after 6-8 weeks of consistent training and proper nutrition.</p>', 'news-images/01JX0AVG93N9WDWGN2MHAQZM2D.webp', 'published', '2025-05-31 11:55:52', 1, 0, 1, 1251, 4, 'Common Muscle Building Mistakes to Avoid', 'Learn about the most common muscle building mistakes that prevent progress and discover how to avoid them for better results.', 'muscle building, fitness mistakes, bodybuilding, strength training', 1, NULL, '2025-06-05 11:51:10', '2025-06-05 14:57:48'),
(2, 1, 'HOW A GOOD PERSONAL TRAINER CAN CHANGE THE WAY OF YOUR LIFE', 'how-a-good-personal-trainer-can-change-your-life', 'Discover how working with a qualified personal trainer can transform not just your body, but your entire lifestyle.', '<p>A skilled personal trainer is more than just someone who counts your reps. They are a catalyst for transformation, helping you unlock your potential and achieve goals you never thought possible.</p>\n\n<h3>Personalized Approach</h3>\n<p>Every body is different, and a good trainer understands this. They create customized workout plans based on your fitness level, goals, and any physical limitations you may have.</p>\n\n<h3>Accountability and Motivation</h3>\n<p>Having someone invested in your success makes all the difference. Personal trainers provide the accountability you need to stay consistent and the motivation to push through challenges.</p>\n\n<h3>Proper Form and Injury Prevention</h3>\n<p>Learning correct exercise form is crucial for both effectiveness and safety. A trainer ensures you perform exercises correctly, reducing injury risk and maximizing results.</p>\n\n<h3>Nutritional Guidance</h3>\n<p>Many trainers also provide nutritional advice, helping you understand how to fuel your body properly for your fitness goals.</p>\n\n<h3>Long-term Lifestyle Changes</h3>\n<p>The best trainers teach you to develop healthy habits that extend beyond the gym, creating lasting lifestyle changes that improve your overall quality of life.</p>', 'news-images/01JX0KEPYP1FVSZPV7X8G2ZRPV.webp', 'published', '2025-06-02 11:55:52', 0, 0, 1, 892, 3, 'How a Personal Trainer Can Transform Your Life', 'Learn how working with a qualified personal trainer can change not just your fitness but your entire lifestyle.', 'personal trainer, fitness transformation, motivation, lifestyle change', 1, NULL, '2025-06-05 11:51:10', '2025-06-06 15:38:39'),
(3, 1, 'How To Make Cool Physique in Gym in 3 Months', 'how-to-make-cool-physique-in-gym-in-3-months', 'A comprehensive 3-month transformation guide to building an impressive physique through strategic training and nutrition.', '<p>Building an impressive physique in 3 months is challenging but achievable with the right approach. This guide outlines a strategic plan to maximize your transformation.</p>\n\n<h3>Month 1: Foundation Building</h3>\n<p>Focus on learning proper form and establishing consistency. Start with basic compound movements and gradually increase intensity.</p>\n<ul>\n<li>Full-body workouts 3 times per week</li>\n<li>Focus on form over weight</li>\n<li>Establish a consistent eating pattern</li>\n<li>Track your progress with photos and measurements</li>\n</ul>\n\n<h3>Month 2: Intensity Increase</h3>\n<p>Add more volume and introduce specialized techniques to accelerate progress.</p>\n<ul>\n<li>Transition to upper/lower split routine</li>\n<li>Increase training frequency to 4-5 days</li>\n<li>Fine-tune your nutrition for body composition goals</li>\n<li>Add cardio for fat loss if needed</li>\n</ul>\n\n<h3>Month 3: Peak Performance</h3>\n<p>Push your limits and make final adjustments for maximum results.</p>\n<ul>\n<li>Implement advanced training techniques</li>\n<li>Optimize recovery and sleep</li>\n<li>Consider a mini cut if fat loss is needed</li>\n<li>Prepare for long-term maintenance</li>\n</ul>\n\n<h3>Nutrition Essentials</h3>\n<p>Your diet is crucial for transformation. Focus on whole foods, adequate protein, and proper meal timing.</p>', 'news-images/01JX0KF7KQZTKY2NNNFCMG1TVS.webp', 'published', '2025-05-29 11:55:52', 1, 0, 1, 2101, 4, '3-Month Physique Transformation Guide', 'Complete guide to building an impressive physique in just 3 months with strategic training and nutrition.', 'physique transformation, 3 month plan, body transformation, fitness goals', 1, NULL, '2025-06-05 11:51:10', '2025-06-05 15:00:33'),
(4, 1, 'How Gym Cycling Can Help to Have Good Metabolism', 'how-gym-cycling-can-help-good-metabolism', 'Explore the metabolic benefits of cycling and how it can boost your overall health and fitness.', '<p>Cycling is one of the most effective forms of cardiovascular exercise for boosting metabolism and improving overall health. Here\'s how gym cycling can transform your metabolic health.</p>\n\n<h3>Metabolic Benefits of Cycling</h3>\n<p>Regular cycling sessions increase your metabolic rate both during and after exercise, leading to improved calorie burning throughout the day.</p>\n\n<h3>HIIT Cycling for Maximum Impact</h3>\n<p>High-Intensity Interval Training on a stationary bike can significantly boost your metabolism for hours after your workout.</p>\n\n<h3>Building Lean Muscle</h3>\n<p>Cycling engages multiple muscle groups, particularly in the lower body, helping to build lean muscle mass that burns more calories at rest.</p>\n\n<h3>Improved Insulin Sensitivity</h3>\n<p>Regular cycling improves your body\'s ability to process glucose, leading to better metabolic health and reduced diabetes risk.</p>\n\n<h3>Sample Cycling Workouts</h3>\n<p>Try these effective cycling workouts to boost your metabolism:</p>\n<ul>\n<li>30-second sprints with 90-second recovery (repeat 8-10 times)</li>\n<li>Steady-state cycling for 45-60 minutes at moderate intensity</li>\n<li>Pyramid intervals: 1, 2, 3, 4, 3, 2, 1 minute hard efforts</li>\n</ul>', 'news-images/01JX0KFY9K2P1986HGMA7EADYG.webp', 'published', '2025-05-26 11:55:52', 0, 0, 1, 750, 3, 'Cycling for Better Metabolism - Complete Guide', 'Learn how gym cycling can boost your metabolism and improve your overall health with effective workout strategies.', 'cycling, metabolism, cardio, HIIT, fat burning', 1, NULL, '2025-06-05 11:51:10', '2025-06-05 14:57:48'),
(5, 1, 'Nutrition Guide: Building Muscle and Losing Fat', 'nutrition-guide-building-muscle-losing-fat', 'Master the art of body recomposition with this comprehensive nutrition guide for simultaneous muscle gain and fat loss.', '<p>Body recomposition - building muscle while losing fat - is one of the most challenging yet rewarding fitness goals. Success requires a strategic approach to nutrition.</p>\n\n<h3>The Science of Body Recomposition</h3>\n<p>While traditional wisdom suggests you can\'t build muscle and lose fat simultaneously, research shows it\'s possible with the right approach, especially for beginners and those returning to training.</p>\n\n<h3>Protein: The Foundation</h3>\n<p>Aim for 1.6-2.2g of protein per kg of body weight. High protein intake supports muscle growth while increasing satiety and metabolic rate.</p>\n\n<h3>Strategic Carbohydrate Timing</h3>\n<p>Time your carbohydrates around workouts to fuel performance while maintaining a slight caloric deficit for fat loss.</p>\n\n<h3>Healthy Fats for Hormonal Health</h3>\n<p>Include 20-30% of calories from healthy fats to support hormone production and vitamin absorption.</p>\n\n<h3>Meal Timing and Frequency</h3>\n<p>While total calories matter most, strategic meal timing can optimize your results:</p>\n<ul>\n<li>Pre-workout: Moderate carbs and protein</li>\n<li>Post-workout: High protein with fast-digesting carbs</li>\n<li>Before bed: Casein protein for overnight muscle recovery</li>\n</ul>\n\n<h3>Supplementation</h3>\n<p>Consider these evidence-based supplements:</p>\n<ul>\n<li>Creatine monohydrate for strength and muscle gain</li>\n<li>Whey protein for convenience</li>\n<li>Caffeine for performance enhancement</li>\n</ul>', 'news-images/01JX0KGN3Y066NZFD68N01RD17.webp', 'published', '2025-06-03 11:55:52', 1, 0, 1, 1688, 3, 'Complete Nutrition Guide for Body Recomposition', 'Learn how to build muscle and lose fat simultaneously with this comprehensive nutrition guide.', 'nutrition, body recomposition, muscle building, fat loss, diet', 1, NULL, '2025-06-05 11:51:10', '2025-06-06 15:43:58'),
(6, 1, 'Conor Benn in talks for world title fight ahead of comeback', 'conor-benn-world-title-fight-talks-comeback', 'British boxer Conor Benn is reportedly in negotiations for a world title shot as he prepares for his highly anticipated return to the ring.', '<p>British boxer Conor Benn is reportedly in negotiations for a world title shot as he prepares for his highly anticipated return to the ring. The 28-year-old has been out of action but remains determined to claim world championship glory.</p><p>Sources close to the fighter suggest that talks are progressing well with several promotional companies interested in securing his services for a major title fight.</p><p>Benn, son of former world champion Nigel Benn, has maintained his training regime throughout his time away from professional boxing and is said to be in excellent physical condition.</p>', 'news-images/01JX3A95B6X1EQDG8A2810WEG6.jpg', 'published', '2025-06-05 16:07:18', 1, 1, 1, 2852, 0, NULL, NULL, NULL, 3, NULL, '2025-06-06 16:07:18', '2025-06-06 17:30:44'),
(7, 1, 'Pat Brown inks promotional deal with Matchroom Boxing', 'pat-brown-matchroom-boxing-promotional-deal', 'Ugandan-born boxer Pat Brown has signed an exclusive promotional deal with Eddie Hearn\'s Matchroom Boxing, marking a significant milestone in his career.', '<p>Ugandan-born boxer Pat Brown has signed an exclusive promotional deal with Eddie Hearn\'s Matchroom Boxing, marking a significant milestone in his career. The announcement was made at a press conference in London.</p><p>Brown, who has been making waves in the welterweight division, expressed his excitement about joining the Matchroom stable and working with one of boxing\'s premier promoters.</p><p>\"This is a dream come true,\" Brown said. \"Matchroom has a proven track record of developing world champions, and I\'m ready to seize this opportunity.\"</p>', 'news-images/01JX3A9NJQDQRG34E4NKCXB5SQ.jpg', 'published', '2025-06-04 16:07:18', 1, 0, 1, 1923, 0, NULL, NULL, NULL, 2, NULL, '2025-06-06 16:07:18', '2025-06-06 16:08:17'),
(8, 1, 'Former world champion signs co-promotion deal with Matchroom Boxing', 'former-world-champion-matchroom-co-promotion-deal', 'A former world champion has entered into a co-promotion agreement with Matchroom Boxing, setting the stage for exciting future matchups.', '<p>A former world champion has entered into a co-promotion agreement with Matchroom Boxing, setting the stage for exciting future matchups. The deal will see the fighter work closely with Eddie Hearn\'s promotional company.</p><p>The agreement opens up numerous possibilities for high-profile fights and gives the former champion access to Matchroom\'s extensive network of venues and broadcast partners.</p><p>Industry insiders believe this partnership could lead to some of the biggest fights in the sport over the coming months.</p>', 'news-images/01JX3AA3TDHCVZPBVBC200Z28D.jpg', 'published', '2025-06-03 16:07:18', 1, 0, 1, 1654, 0, NULL, NULL, NULL, 2, NULL, '2025-06-06 16:07:18', '2025-06-06 16:08:31'),
(9, 1, 'Nigel Benn reveals the origin of his hatred toward Chris Eubank Sr in the 90s', 'nigel-benn-chris-eubank-hatred-origin-90s', 'Boxing legend Nigel Benn opens up about his intense rivalry with Chris Eubank Sr and what sparked their legendary feud in the 1990s.', '<p>Boxing legend Nigel Benn has finally opened up about the origins of his intense rivalry with Chris Eubank Sr, revealing the personal and professional factors that fueled their legendary feud in the 1990s.</p><p>In a candid interview, Benn discussed how their contrasting personalities and fighting styles created natural animosity that captivated boxing fans worldwide.</p><p>\"It wasn\'t just about boxing,\" Benn explained. \"Our whole approach to life was different, and that created genuine tension between us.\"</p>', 'news-images/01JX3AAM3WVH29A8GYSC5B67MW.jpg', 'published', '2025-06-02 16:07:18', 1, 0, 1, 3241, 0, NULL, NULL, NULL, 4, NULL, '2025-06-06 16:07:18', '2025-06-06 16:08:48'),
(10, 1, 'Tyson Fury vows to reclaim the titles in his rematch against Oleksandr Usyk', 'tyson-fury-usyk-rematch-reclaim-titles', 'The Gypsy King promises to bounce back stronger in his highly anticipated rematch with the unified heavyweight champion.', '<p>Tyson Fury has made a bold promise to reclaim the heavyweight titles in his upcoming rematch against Oleksandr Usyk. The British fighter is determined to bounce back from his previous encounter with the Ukrainian champion.</p><p>Speaking at a press conference, Fury outlined his plans for the rematch and emphasized his commitment to regaining the championship belts.</p><p>\"I know what went wrong in the first fight, and I\'ve made the necessary adjustments,\" Fury stated confidently.</p>', 'news-images/01JX3ADX3B9E9XWAR9G8CXN3H3.webp', 'published', '2025-06-01 16:07:18', 1, 0, 1, 4156, 0, NULL, NULL, NULL, 3, NULL, '2025-06-06 16:07:18', '2025-06-06 16:10:36'),
(11, 1, 'Caleb Plant challenges Edgar Berlanga', 'caleb-plant-challenges-edgar-berlanga', 'Former IBF super middleweight champion Caleb Plant has issued a direct challenge to rising star Edgar Berlanga.', '<p>Former IBF super middleweight champion Caleb Plant has issued a direct challenge to rising star Edgar Berlanga, calling for a high-stakes showdown between the two fighters.</p><p>Plant believes a victory over Berlanga would position him for another world title shot and has been vocal about his desire to face the hard-hitting Puerto Rican.</p><p>\"Berlanga talks a big game, but I\'m ready to silence him,\" Plant declared during a recent interview.</p>', 'news-images/caleb-plant-berlanga.jpg', 'published', '2025-05-31 16:07:18', 0, 0, 1, 1872, 0, NULL, NULL, NULL, 2, NULL, '2025-06-06 16:07:18', '2025-06-06 16:07:18'),
(12, 1, 'Amanda Serrano could face Caroline Dubois', 'amanda-serrano-caroline-dubois-potential-fight', 'Multi-division world champion Amanda Serrano is reportedly considering a bout with British rising star Caroline Dubois.', '<p>Multi-division world champion Amanda Serrano is reportedly considering a bout with British rising star Caroline Dubois, which could become one of the biggest fights in women\'s boxing.</p><p>Dubois, sister of heavyweight contender Daniel Dubois, has been making impressive strides in the lightweight division and represents an exciting challenge for Serrano.</p><p>Both fighters have expressed interest in the matchup, and negotiations are said to be in the preliminary stages.</p>', 'news-images/serrano-dubois.jpg', 'published', '2025-05-30 16:07:18', 0, 0, 1, 1456, 0, NULL, NULL, NULL, 2, NULL, '2025-06-06 16:07:18', '2025-06-06 16:07:18'),
(13, 7, 'Rising Star: John Mugabi Jr Makes Professional Debut', 'rising-star-john-mugabi-jr-makes-professional-debut', 'John Mugabi Jr, son of Uganda\'s legendary boxer, makes a spectacular professional debut with a first-round knockout victory, showcasing the potential to follow in his famous father\'s footsteps.', '<p>John Mugabi Jr, son of Uganda\'s legendary boxer John \"The Beast\" Mugabi, has made his professional debut with a stunning first-round knockout victory. The young fighter, who has been training under some of the best coaches in the country, showed exceptional skill and power reminiscent of his father.</p>\n                <p>Mugabi Sr, who won a silver medal at the 1980 Olympics and challenged for world titles during his illustrious career, was ringside to witness his son\'s impressive debut. \"I\'m very proud of him. He has worked hard and has natural talent. I believe he can go even further than I did,\" said the proud father.</p>\n                <p>The boxing community in Uganda has high hopes for Mugabi Jr, seeing him as a potential future world champion who could bring glory to Ugandan boxing once again.</p>', 'news-images/01JX3D7Q4K71MCP036P4QR66VH.webp', 'published', '2025-06-04 16:50:50', 1, 0, 1, 364, 0, 'Rising Star: John Mugabi Jr Makes Professional Debut', 'John Mugabi Jr, son of Uganda\'s legendary boxer, makes a spectacular professional debut with a first-round knockout victory,', NULL, 1, NULL, '2025-06-06 16:50:50', '2025-06-06 16:59:39'),
(14, 7, 'Uganda Boxing Federation Announces New Development Program', 'uganda-boxing-federation-announces-new-development-program', 'The Uganda Boxing Federation launches a comprehensive national development program to discover and nurture young boxing talent, establishing training centers throughout the country.', '<p>The Uganda Boxing Federation (UBF) has unveiled an ambitious new development program aimed at identifying and nurturing young boxing talent across the country. The initiative, funded by a combination of government support and private sponsorships, will establish training centers in all major regions of Uganda.</p>\n                <p>UBF President Moses Muhangi announced the program at a press conference in Kampala, stating: \"This program represents a new chapter for Ugandan boxing. We are committed to building on our rich boxing heritage by providing young fighters with the resources, coaching, and opportunities they need to succeed on the international stage.\"</p>\n                <p>The program will include regular national competitions, scholarships for promising boxers, and partnerships with international boxing organizations to provide exposure and experience for Ugandan fighters.</p>', 'news-images/01JX3D8JFGQXQ1NB85QNR48Z6Y.avif', 'published', '2025-06-02 16:50:50', 1, 0, 1, 163, 0, 'Uganda Boxing Federation Announces New Development Program', 'The Uganda Boxing Federation launches a comprehensive national development program to discover and nurture young boxing talent,', NULL, 1, NULL, '2025-06-06 16:50:50', '2025-06-06 17:00:07'),
(15, 7, 'Kassim Ouma Launches Boxing Academy in Kampala', 'kassim-ouma-launches-boxing-academy-in-kampala', 'Former world champion Kassim \"The Dream\" Ouma establishes a modern boxing academy in Kampala to train young talent from disadvantaged backgrounds, providing both boxing skills and educational support.', '<p>Former IBF junior middleweight champion Kassim \"The Dream\" Ouma has returned to his roots by launching a state-of-the-art boxing academy in Kampala. The academy, named \"Ouma\'s Champions,\" aims to provide professional training to aspiring boxers from underprivileged backgrounds.</p>\n                <p>Ouma, who had a remarkable journey from child soldier to world champion, expressed his motivation for starting the academy: \"I want to give back to my country and create opportunities I didn\'t have. Boxing saved my life, and I believe it can transform the lives of many young Ugandans who are facing challenges.\"</p>\n                <p>The academy features modern equipment, experienced trainers, and a comprehensive program that addresses not only the technical aspects of boxing but also education, nutrition, and personal development. Several international boxing figures attended the launch, pledging their support for Ouma\'s initiative.</p>', 'news-images/01JX3D933CB2TFEKCF12GFBN34.webp', 'published', '2025-05-31 16:50:50', 1, 0, 1, 218, 0, 'Kassim Ouma Launches Boxing Academy in Kampala', 'Former world champion Kassim \"The Dream\" Ouma establishes a modern boxing academy in Kampala to train young talent ', NULL, 1, NULL, '2025-06-06 16:50:50', '2025-06-06 17:00:24'),
(16, 7, 'Ugandan Women\'s Boxing Team Secures Olympic Qualification', 'ugandan-womens-boxing-team-secures-olympic-qualification', 'Uganda\'s women\'s boxing team makes history by securing three Olympic qualification spots, highlighting the growing strength of female boxing in the country despite numerous challenges.', '<p>In a historic achievement for Ugandan sport, the national women\'s boxing team has secured three qualification spots for the upcoming Olympic Games. The team, led by coach Rebecca Amongin, impressed at the African Olympic Qualifying Tournament with standout performances in multiple weight categories.</p>\n                <p>Hellen Baleke (69kg), Doreen Nassali (57kg), and Catherine Nanziri (51kg) all qualified for the Olympics, marking the first time Uganda will send multiple female boxers to the Games. \"This is a breakthrough moment for women\'s boxing in Uganda,\" said Amongin. \"These women have overcome tremendous obstacles and social barriers to reach this level.\"</p>\n                <p>The achievement has been celebrated nationwide, with government officials pledging additional support for the team\'s Olympic preparation. The boxers will now enter an intensive training camp, including international sparring opportunities, as they prepare to compete on the world\'s biggest sporting stage.</p>', 'news-images/01JX3D9TDCN3CPFZTYZA51Q2XG.webp', 'published', '2025-05-29 16:50:50', 1, 0, 1, 290, 0, 'Ugandan Women\'s Boxing Team Secures Olympic Qualification', 'Uganda\'s women\'s boxing team makes history by securing three Olympic qualification spots, highlighting the growing strength ', NULL, 1, NULL, '2025-06-06 16:50:50', '2025-06-06 17:00:48'),
(17, 7, 'Ugandan Boxing Legends Honored in National Sports Hall of Fame', 'ugandan-boxing-legends-honored-in-national-sports-hall-of-fame', 'Five Ugandan boxing legends are inducted into the National Sports Hall of Fame in recognition of their world-class achievements and contributions to the country\'s sporting legacy.', '<p>Uganda\'s rich boxing heritage was celebrated as five boxing legends were inducted into the National Sports Hall of Fame. The ceremony, held at the Serena Hotel in Kampala, honored John \"The Beast\" Mugabi, Ayub Kalule, Cornelius Boza-Edwards, Kassim Ouma, and Justin Juuko for their outstanding contributions to Ugandan sports.</p>\n                <p>The inductees, who collectively held multiple world titles and represented Uganda on the global stage during the 1970s, 80s, and 90s, received commemorative plaques and lifetime achievement awards. President Yoweri Museveni, who attended the ceremony, praised the boxers for raising Uganda\'s flag high and inspiring generations of athletes.</p>\n                <p>\"These champions emerged during challenging times for our country, yet they persevered and conquered the world,\" Museveni stated. \"Their stories of determination and excellence should be taught to our youth.\"</p>\n                <p>The induction ceremony also featured the announcement of a new boxing museum to be established in Kampala, which will document the history of Ugandan boxing and display memorabilia from the careers of these legendary fighters.</p>', 'news-images/01JX3DAE096WA5HPF0V19DVXGD.webp', 'published', '2025-05-27 16:50:50', 1, 0, 1, 389, 0, 'Ugandan Boxing Legends Honored in National Sports Hall of Fame', 'Five Ugandan boxing legends are inducted into the National Sports Hall of Fame in recognition of their world-class ', NULL, 1, NULL, '2025-06-06 16:50:50', '2025-06-06 17:01:08');

-- --------------------------------------------------------

--
-- Table structure for table `news_article_category`
--

CREATE TABLE `news_article_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_article_id` bigint(20) UNSIGNED NOT NULL,
  `news_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_article_category`
--

INSERT INTO `news_article_category` (`id`, `news_article_id`, `news_category_id`, `created_at`, `updated_at`) VALUES
(11, 1, 1, NULL, NULL),
(12, 1, 3, NULL, NULL),
(13, 2, 8, NULL, NULL),
(14, 2, 1, NULL, NULL),
(15, 3, 3, NULL, NULL),
(16, 3, 1, NULL, NULL),
(17, 4, 4, NULL, NULL),
(18, 4, 6, NULL, NULL),
(19, 5, 2, NULL, NULL),
(20, 5, 6, NULL, NULL),
(21, 6, 10, NULL, NULL),
(22, 6, 11, NULL, NULL),
(23, 7, 10, NULL, NULL),
(24, 7, 11, NULL, NULL),
(25, 8, 10, NULL, NULL),
(26, 8, 11, NULL, NULL),
(27, 9, 10, NULL, NULL),
(28, 9, 11, NULL, NULL),
(29, 10, 10, NULL, NULL),
(30, 10, 11, NULL, NULL),
(31, 11, 10, NULL, NULL),
(32, 11, 11, NULL, NULL),
(33, 12, 10, NULL, NULL),
(34, 12, 11, NULL, NULL),
(35, 13, 10, NULL, NULL),
(36, 13, 11, NULL, NULL),
(37, 14, 10, NULL, NULL),
(38, 14, 11, NULL, NULL),
(39, 15, 10, NULL, NULL),
(40, 15, 11, NULL, NULL),
(41, 16, 10, NULL, NULL),
(42, 16, 11, NULL, NULL),
(43, 17, 10, NULL, NULL),
(44, 17, 11, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_article_tag`
--

CREATE TABLE `news_article_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_article_id` bigint(20) UNSIGNED NOT NULL,
  `news_tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_article_tag`
--

INSERT INTO `news_article_tag` (`id`, `news_article_id`, `news_tag_id`, `created_at`, `updated_at`) VALUES
(18, 1, 5, NULL, NULL),
(19, 1, 3, NULL, NULL),
(20, 1, 7, NULL, NULL),
(21, 2, 3, NULL, NULL),
(22, 2, 7, NULL, NULL),
(23, 3, 5, NULL, NULL),
(24, 3, 3, NULL, NULL),
(25, 3, 8, NULL, NULL),
(26, 3, 1, NULL, NULL),
(27, 4, 11, NULL, NULL),
(28, 4, 6, NULL, NULL),
(29, 4, 9, NULL, NULL),
(30, 4, 1, NULL, NULL),
(31, 5, 4, NULL, NULL),
(32, 5, 5, NULL, NULL),
(33, 5, 6, NULL, NULL),
(34, 5, 12, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

CREATE TABLE `news_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#6c757d',
  `icon` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `color`, `icon`, `is_active`, `sort_order`) VALUES
(1, 'Fitness Tips', 'fitness-tips', 'Expert advice and tips for effective workouts and fitness routines', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#FF6B6B', 'fa-dumbbell', 1, 1),
(2, 'Nutrition', 'nutrition', 'Healthy eating guides, meal plans, and nutritional advice', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#4ECDC4', 'fa-apple-alt', 1, 2),
(3, 'Strength Training', 'strength-training', 'Weight lifting techniques, muscle building, and strength programs', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#45B7D1', 'fa-weight-hanging', 1, 3),
(4, 'Cardio Workouts', 'cardio-workouts', 'Cardiovascular exercises and endurance training', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#96CEB4', 'fa-running', 1, 4),
(5, 'Yoga & Flexibility', 'yoga-flexibility', 'Yoga poses, stretching routines, and flexibility improvement', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#FFEAA7', 'fa-peace', 1, 5),
(6, 'Weight Loss', 'weight-loss', 'Effective strategies and tips for healthy weight management', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#DDA0DD', 'fa-chart-line', 1, 6),
(7, 'Supplements', 'supplements', 'Information about fitness supplements and nutritional products', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#74B9FF', 'fa-pills', 1, 7),
(8, 'Motivation', 'motivation', 'Inspirational stories and motivational content for fitness journey', '2025-06-05 11:51:03', '2025-06-05 11:55:52', '#FD79A8', 'fa-fire', 1, 8),
(10, 'Boxing', 'boxing', 'Latest boxing news and updates', '2025-06-06 16:07:18', '2025-06-06 16:07:18', '#ff6b6b', NULL, 1, 1),
(11, 'Uganda', 'uganda', 'Uganda boxing news and events', '2025-06-06 16:07:18', '2025-06-06 16:07:18', '#4ecdc4', NULL, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `news_comments`
--

CREATE TABLE `news_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `user_avatar` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `user_ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `news_comments`
--

INSERT INTO `news_comments` (`id`, `news_id`, `parent_id`, `name`, `email`, `website`, `user_avatar`, `comment`, `status`, `user_ip`, `user_agent`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'John Smith', 'john@example.com', NULL, NULL, 'Great article! I\'ve been making mistake #2 for months. Time to get consistent with my training.', 'approved', NULL, NULL, NULL, '2025-06-01 14:57:48', '2025-06-05 14:57:48'),
(2, 1, 1, 'Jakki James', 'jakki@example.com', NULL, NULL, 'Thanks John! Consistency is definitely key. Keep at it and you\'ll see great results.', 'approved', NULL, NULL, NULL, '2025-06-02 14:57:48', '2025-06-05 14:57:48'),
(3, 1, NULL, 'Maria Garcia', 'maria@example.com', NULL, NULL, 'I had no idea about the protein requirements. I was only eating about half of what I should be!', 'approved', NULL, NULL, NULL, '2025-06-02 14:57:48', '2025-06-05 14:57:48'),
(4, 1, NULL, 'Alex Johnson', 'alex@example.com', NULL, NULL, 'The sleep point is so underrated. I started getting 8 hours and my recovery improved dramatically.', 'approved', NULL, NULL, NULL, '2025-06-03 14:57:48', '2025-06-05 14:57:48'),
(5, 2, NULL, 'Lisa Brown', 'lisa@example.com', NULL, NULL, 'I was skeptical about hiring a trainer, but after reading this, I think it\'s time to invest in one.', 'approved', NULL, NULL, NULL, '2025-06-03 14:57:48', '2025-06-05 14:57:48'),
(6, 2, 5, 'Mike Johnson', 'mike@example.com', NULL, NULL, 'It\'s definitely an investment worth making! Look for certified trainers with good reviews.', 'approved', NULL, NULL, NULL, '2025-06-04 14:57:48', '2025-06-05 14:57:48'),
(7, 2, NULL, 'Robert Wilson', 'robert@example.com', NULL, NULL, 'My trainer helped me lose 30 pounds and gain so much confidence. This article is spot on!', 'approved', NULL, NULL, NULL, '2025-06-04 14:57:48', '2025-06-05 14:57:48'),
(8, 3, NULL, 'David Kim', 'david@example.com', NULL, NULL, 'This is exactly what I needed! Starting month 1 tomorrow. Wish me luck!', 'approved', NULL, NULL, NULL, '2025-05-31 14:57:48', '2025-06-05 14:57:48'),
(9, 3, 8, 'Sarah Williams', 'sarah@example.com', NULL, NULL, 'Good luck David! Remember, consistency is more important than perfection.', 'approved', NULL, NULL, NULL, '2025-06-01 14:57:48', '2025-06-05 14:57:48'),
(10, 3, NULL, 'Jennifer Taylor', 'jennifer@example.com', NULL, NULL, 'I\'m halfway through month 2 following this plan. Already seeing amazing results!', 'approved', NULL, NULL, NULL, '2025-06-02 14:57:48', '2025-06-05 14:57:48'),
(11, 3, NULL, 'Chris Anderson', 'chris@example.com', NULL, NULL, 'The nutrition section is gold. Finally understand how to eat for my goals.', 'approved', NULL, NULL, NULL, '2025-06-03 14:57:48', '2025-06-05 14:57:48'),
(12, 4, NULL, 'Amanda White', 'amanda@example.com', NULL, NULL, 'I love cycling! Never realized how much it was helping my metabolism.', 'approved', NULL, NULL, NULL, '2025-05-28 14:57:48', '2025-06-05 14:57:48'),
(13, 4, NULL, 'Michael Davis', 'michael@example.com', NULL, NULL, 'Those HIIT cycling workouts are killer but so effective!', 'approved', NULL, NULL, NULL, '2025-05-29 14:57:48', '2025-06-05 14:57:48'),
(14, 4, 13, 'David Lee', 'david@example.com', NULL, NULL, 'They really are! Start with shorter intervals if you\'re new to HIIT.', 'approved', NULL, NULL, NULL, '2025-05-30 14:57:48', '2025-06-05 14:57:48'),
(15, 5, NULL, 'Rachel Green', 'rachel@example.com', NULL, NULL, 'This is the most comprehensive nutrition guide I\'ve read. Bookmarking for sure!', 'approved', NULL, NULL, NULL, '2025-06-05 02:57:48', '2025-06-05 14:57:48'),
(16, 5, NULL, 'Tom Wilson', 'tom@example.com', NULL, NULL, 'The meal timing section really opened my eyes. I\'ve been doing it all wrong!', 'approved', NULL, NULL, NULL, '2025-06-05 06:57:48', '2025-06-05 14:57:48'),
(17, 5, 16, 'Dr. Emily Chen', 'emily@example.com', NULL, NULL, 'Glad it helped, Tom! Small changes in timing can make a big difference.', 'approved', NULL, NULL, NULL, '2025-06-05 08:57:48', '2025-06-05 14:57:48'),
(18, 5, 15, 'smogcoders', 'smoggrafton@gmail.com', NULL, NULL, 'This is a comment', 'pending', NULL, NULL, NULL, '2025-06-05 14:59:18', '2025-06-05 14:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `news_tags`
--

CREATE TABLE `news_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#6c757d',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `news_tags`
--

INSERT INTO `news_tags` (`id`, `name`, `slug`, `description`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Workout', 'workout', 'General workout and exercise content', '#007bff', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(2, 'Gym', 'gym', 'Gym-related content and equipment', '#28a745', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(3, 'Training', 'training', 'Training programs and techniques', '#dc3545', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(4, 'Diet', 'diet', 'Diet plans and nutritional guidance', '#ffc107', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(5, 'Muscle Building', 'muscle-building', 'Muscle growth and hypertrophy', '#6f42c1', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(6, 'Fat Loss', 'fat-loss', 'Fat burning and weight loss', '#fd7e14', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(7, 'Beginner', 'beginner', 'Content for fitness beginners', '#20c997', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(8, 'Advanced', 'advanced', 'Advanced fitness techniques', '#6c757d', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(9, 'Equipment', 'equipment', 'Gym equipment and tools', '#17a2b8', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(10, 'Home Workout', 'home-workout', 'Exercises that can be done at home', '#e83e8c', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(11, 'HIIT', 'hiit', 'High-Intensity Interval Training', '#fd7e14', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(12, 'Protein', 'protein', 'Protein intake and supplements', '#6f42c1', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(13, 'Recovery', 'recovery', 'Rest and recovery techniques', '#28a745', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(14, 'Stretching', 'stretching', 'Stretching and flexibility exercises', '#ffc107', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14'),
(15, 'Wellness', 'wellness', 'Overall health and wellness', '#20c997', 1, '2025-06-05 11:53:14', '2025-06-05 11:53:14');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-06-05 09:58:28', '2025-06-05 09:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('o1S1hclLezt3qY2KHBTYPRZlTVqGot2s5pxGd3Sq', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSVpINUEwek9iYzBWek1zenl4NnJzQnJEUVVjWUd2RlB1eUEyaEVZUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ldmVudHMvY2hhbXBpb25zaGlwLWZpZ2h0LW5pZ2h0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiQuaXhOb3YzSHVFdldhazZ4ci9tbEJlOEI5TjBCQVlNNGs3Tjh3MDQ5NjFOWE9xUlBuek9adSI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1749308096);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_purchases`
--

CREATE TABLE `ticket_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `ticket_holder_name` varchar(255) NOT NULL,
  `ticket_holder_email` varchar(255) NOT NULL,
  `ticket_holder_phone` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `paid_at` datetime DEFAULT NULL,
  `ticket_generated_at` datetime DEFAULT NULL,
  `ticket_sent_at` datetime DEFAULT NULL,
  `ticket_pdf_path` varchar(255) DEFAULT NULL,
  `is_checked_in` tinyint(1) NOT NULL DEFAULT 0,
  `checked_in_at` datetime DEFAULT NULL,
  `checked_in_by` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_templates`
--

CREATE TABLE `ticket_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `width` int(11) NOT NULL DEFAULT 800,
  `height` int(11) NOT NULL DEFAULT 350,
  `qr_code_position` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`qr_code_position`)),
  `text_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`text_fields`)),
  `ticket_type` varchar(255) NOT NULL DEFAULT 'regular',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('admin','editor','author','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `bio`, `role`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mulinda Akiibu', 'smoggrafton@gmail.com', 'user-avatars/01JX0KK5QMGMW2SEP3E0X0T1MK.jpg', NULL, 'user', 1, '2025-06-05 09:58:28', '$2y$12$.ixNov3HuEvWak6xr/mlBe8B9N0BAYM4k7N8w04961NXOqRPnzOZu', NULL, '2025-06-05 09:58:28', '2025-06-05 14:53:03'),
(2, 'Jakki James', 'jakki@example.com', 'author-images/01JX0AVG98TH1EH6TWRY1TN6SF.jpg', NULL, 'author', 1, NULL, '$2y$12$n2inhWcUaGJFQ2xhTuX5buN6TGnuEhnzeCWr/XzLZm2UCboFBbEEe', NULL, '2025-06-05 14:43:46', '2025-06-05 14:43:46'),
(3, 'Mike Thompson', 'mike@example.com', NULL, 'Professional bodybuilder and strength training expert. Specializes in muscle building and powerlifting.', 'author', 1, '2025-06-05 14:49:21', '$2y$12$mzVecps1umHbFppfwI9q..ovh58DyK5JCq2ju5fNHcO8Gbqu9NRxS', NULL, '2025-06-05 14:43:46', '2025-06-05 14:49:22'),
(4, 'Dr. Sarah Johnson', 'sarah@example.com', NULL, 'Certified fitness trainer and nutritionist with over 10 years of experience in the health and wellness industry.', 'author', 1, '2025-06-05 14:49:21', '$2y$12$URLVVciMCred40EeJnLT1eQF./.uFfcYXs4gBvrYpFAzCFdwd04Lu', NULL, '2025-06-05 14:43:46', '2025-06-05 14:49:22'),
(5, 'David Lee', 'david@example.com', NULL, NULL, 'author', 1, NULL, '$2y$12$45J7CtfaTxxUrGcCx.rmeedgLFH5fVV0Sjd3ghddrMmc6hoXbkUQ.', NULL, '2025-06-05 14:43:47', '2025-06-05 14:43:47'),
(6, 'Dr. Emily Chen', 'emily@example.com', NULL, NULL, 'author', 1, NULL, '$2y$12$4/4v3/05FZxoCLAT1DBAGOZ6o6hElTtrzkHJ3IA4vqjc5B7bWPUMG', NULL, '2025-06-05 14:43:47', '2025-06-05 14:43:47'),
(7, 'Admin User', 'admin@narapromotionz.com', NULL, 'System administrator with full access to manage the platform.', 'admin', 1, '2025-06-05 14:49:20', '$2y$12$mC24frwzMZYKp5rqvSmmZe/Sh5nUVvW.QXTsC4TNMjSli0/Jm.G26', NULL, '2025-06-05 14:49:22', '2025-06-05 14:49:22'),
(8, 'Emma Davis', 'emma@example.com', NULL, 'Health and fitness editor with expertise in content creation and wellness journalism.', 'editor', 1, '2025-06-05 14:49:22', '$2y$12$lQOsxbGhewXcShmL85t5p.smr3viyEaMis.x1Qzpx7JSC49omSiX.', NULL, '2025-06-05 14:49:22', '2025-06-05 14:49:22'),
(9, 'John Smith', 'john@example.com', NULL, 'Yoga instructor and mindfulness coach focused on holistic wellness and mental health.', 'author', 1, '2025-06-05 14:49:22', '$2y$12$KVPHbQcfc6UIv7VTUEVrEOqAhw.7oxOXkWcTJNP1Af1Em2w5PcHPW', NULL, '2025-06-05 14:49:22', '2025-06-05 14:49:22'),
(10, 'Lisa Anderson', 'lisa@example.com', NULL, 'Sports nutritionist and former Olympic athlete with expertise in performance nutrition.', 'author', 1, '2025-06-05 14:49:22', '$2y$12$tVfADYh/fM0l4igM.7Mm1OIB.EZWkxPcTbCFF/okyFWdl29QQjYc6', NULL, '2025-06-05 14:49:22', '2025-06-05 14:49:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boxers`
--
ALTER TABLE `boxers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxers_slug_unique` (`slug`),
  ADD KEY `boxers_weight_class_index` (`weight_class`),
  ADD KEY `boxers_global_ranking_index` (`global_ranking`),
  ADD KEY `boxers_is_active_index` (`is_active`),
  ADD KEY `boxers_is_featured_index` (`is_featured`);

--
-- Indexes for table `boxer_boxing_event`
--
ALTER TABLE `boxer_boxing_event`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxer_boxing_event_boxer_id_boxing_event_id_unique` (`boxer_id`,`boxing_event_id`),
  ADD KEY `boxer_boxing_event_boxing_event_id_foreign` (`boxing_event_id`);

--
-- Indexes for table `boxer_boxing_video`
--
ALTER TABLE `boxer_boxing_video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxer_boxing_video_boxer_id_boxing_video_id_unique` (`boxer_id`,`boxing_video_id`),
  ADD KEY `boxer_boxing_video_boxing_video_id_foreign` (`boxing_video_id`);

--
-- Indexes for table `boxer_news_article`
--
ALTER TABLE `boxer_news_article`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxer_news_article_boxer_id_news_article_id_unique` (`boxer_id`,`news_article_id`),
  ADD KEY `boxer_news_article_news_article_id_foreign` (`news_article_id`);

--
-- Indexes for table `boxing_events`
--
ALTER TABLE `boxing_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxing_events_slug_unique` (`slug`),
  ADD KEY `boxing_events_event_date_index` (`event_date`),
  ADD KEY `boxing_events_status_index` (`status`),
  ADD KEY `boxing_events_is_featured_index` (`is_featured`);

--
-- Indexes for table `boxing_event_boxing_video`
--
ALTER TABLE `boxing_event_boxing_video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxing_event_boxing_video_boxing_event_id_boxing_video_id_unique` (`boxing_event_id`,`boxing_video_id`),
  ADD KEY `boxing_event_boxing_video_boxing_video_id_foreign` (`boxing_video_id`);

--
-- Indexes for table `boxing_event_news_article`
--
ALTER TABLE `boxing_event_news_article`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxing_event_news_article_boxing_event_id_news_article_id_unique` (`boxing_event_id`,`news_article_id`),
  ADD KEY `boxing_event_news_article_news_article_id_foreign` (`news_article_id`);

--
-- Indexes for table `boxing_videos`
--
ALTER TABLE `boxing_videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boxing_videos_slug_unique` (`slug`),
  ADD KEY `boxing_videos_video_type_index` (`video_type`),
  ADD KEY `boxing_videos_is_premium_index` (`is_premium`),
  ADD KEY `boxing_videos_is_featured_index` (`is_featured`),
  ADD KEY `boxing_videos_status_index` (`status`),
  ADD KEY `boxing_videos_category_index` (`category`),
  ADD KEY `boxing_videos_published_at_index` (`published_at`),
  ADD KEY `boxing_videos_boxer_id_foreign` (`boxer_id`),
  ADD KEY `boxing_videos_event_id_foreign` (`event_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `event_tickets`
--
ALTER TABLE `event_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_tickets_boxing_event_id_ticket_type_index` (`boxing_event_id`,`ticket_type`),
  ADD KEY `event_tickets_is_active_index` (`is_active`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fight_records`
--
ALTER TABLE `fight_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fight_records_boxer_id_result_index` (`boxer_id`,`result`),
  ADD KEY `fight_records_boxer_id_fight_date_index` (`boxer_id`,`fight_date`),
  ADD KEY `fight_records_opponent_id_index` (`opponent_id`),
  ADD KEY `fight_records_boxing_event_id_index` (`boxing_event_id`);

--
-- Indexes for table `hero_sliders`
--
ALTER TABLE `hero_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_articles_slug_unique` (`slug`),
  ADD KEY `news_articles_status_published_at_index` (`status`,`published_at`),
  ADD KEY `news_articles_is_featured_published_at_index` (`is_featured`,`published_at`),
  ADD KEY `news_articles_views_count_index` (`views_count`),
  ADD KEY `news_articles_user_id_foreign` (`user_id`),
  ADD KEY `news_articles_is_main_article_index` (`is_main_article`);
ALTER TABLE `news_articles` ADD FULLTEXT KEY `news_articles_title_content_fulltext` (`title`,`content`);

--
-- Indexes for table `news_article_category`
--
ALTER TABLE `news_article_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_article_category_news_article_id_news_category_id_unique` (`news_article_id`,`news_category_id`),
  ADD KEY `news_article_category_news_category_id_foreign` (`news_category_id`);

--
-- Indexes for table `news_article_tag`
--
ALTER TABLE `news_article_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_article_tag_news_article_id_news_tag_id_unique` (`news_article_id`,`news_tag_id`),
  ADD KEY `news_article_tag_news_tag_id_foreign` (`news_tag_id`);

--
-- Indexes for table `news_categories`
--
ALTER TABLE `news_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_categories_slug_unique` (`slug`);

--
-- Indexes for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_comments_news_article_id_foreign` (`news_id`),
  ADD KEY `news_comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `news_tags`
--
ALTER TABLE `news_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_tags_slug_unique` (`slug`),
  ADD KEY `news_tags_is_active_index` (`is_active`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `ticket_purchases`
--
ALTER TABLE `ticket_purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_purchases_order_number_unique` (`order_number`),
  ADD UNIQUE KEY `ticket_purchases_qr_code_unique` (`qr_code`),
  ADD KEY `ticket_purchases_user_id_foreign` (`user_id`),
  ADD KEY `ticket_purchases_order_number_index` (`order_number`),
  ADD KEY `ticket_purchases_qr_code_index` (`qr_code`),
  ADD KEY `ticket_purchases_status_index` (`status`),
  ADD KEY `ticket_purchases_ticket_holder_email_index` (`ticket_holder_email`),
  ADD KEY `ticket_purchases_event_ticket_id_status_index` (`event_ticket_id`,`status`);

--
-- Indexes for table `ticket_templates`
--
ALTER TABLE `ticket_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_templates_user_id_foreign` (`user_id`),
  ADD KEY `ticket_templates_ticket_type_index` (`ticket_type`),
  ADD KEY `ticket_templates_is_active_index` (`is_active`),
  ADD KEY `ticket_templates_is_default_index` (`is_default`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boxers`
--
ALTER TABLE `boxers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `boxer_boxing_event`
--
ALTER TABLE `boxer_boxing_event`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boxer_boxing_video`
--
ALTER TABLE `boxer_boxing_video`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boxer_news_article`
--
ALTER TABLE `boxer_news_article`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boxing_events`
--
ALTER TABLE `boxing_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `boxing_event_boxing_video`
--
ALTER TABLE `boxing_event_boxing_video`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boxing_event_news_article`
--
ALTER TABLE `boxing_event_news_article`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boxing_videos`
--
ALTER TABLE `boxing_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `event_tickets`
--
ALTER TABLE `event_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fight_records`
--
ALTER TABLE `fight_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_sliders`
--
ALTER TABLE `hero_sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `news_articles`
--
ALTER TABLE `news_articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `news_article_category`
--
ALTER TABLE `news_article_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `news_article_tag`
--
ALTER TABLE `news_article_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `news_tags`
--
ALTER TABLE `news_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_purchases`
--
ALTER TABLE `ticket_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_templates`
--
ALTER TABLE `ticket_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boxer_boxing_event`
--
ALTER TABLE `boxer_boxing_event`
  ADD CONSTRAINT `boxer_boxing_event_boxer_id_foreign` FOREIGN KEY (`boxer_id`) REFERENCES `boxers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `boxer_boxing_event_boxing_event_id_foreign` FOREIGN KEY (`boxing_event_id`) REFERENCES `boxing_events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `boxer_boxing_video`
--
ALTER TABLE `boxer_boxing_video`
  ADD CONSTRAINT `boxer_boxing_video_boxer_id_foreign` FOREIGN KEY (`boxer_id`) REFERENCES `boxers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `boxer_boxing_video_boxing_video_id_foreign` FOREIGN KEY (`boxing_video_id`) REFERENCES `boxing_videos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `boxer_news_article`
--
ALTER TABLE `boxer_news_article`
  ADD CONSTRAINT `boxer_news_article_boxer_id_foreign` FOREIGN KEY (`boxer_id`) REFERENCES `boxers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `boxer_news_article_news_article_id_foreign` FOREIGN KEY (`news_article_id`) REFERENCES `news_articles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `boxing_event_boxing_video`
--
ALTER TABLE `boxing_event_boxing_video`
  ADD CONSTRAINT `boxing_event_boxing_video_boxing_event_id_foreign` FOREIGN KEY (`boxing_event_id`) REFERENCES `boxing_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `boxing_event_boxing_video_boxing_video_id_foreign` FOREIGN KEY (`boxing_video_id`) REFERENCES `boxing_videos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `boxing_event_news_article`
--
ALTER TABLE `boxing_event_news_article`
  ADD CONSTRAINT `boxing_event_news_article_boxing_event_id_foreign` FOREIGN KEY (`boxing_event_id`) REFERENCES `boxing_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `boxing_event_news_article_news_article_id_foreign` FOREIGN KEY (`news_article_id`) REFERENCES `news_articles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `boxing_videos`
--
ALTER TABLE `boxing_videos`
  ADD CONSTRAINT `boxing_videos_boxer_id_foreign` FOREIGN KEY (`boxer_id`) REFERENCES `boxers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `boxing_videos_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `boxing_events` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_tickets`
--
ALTER TABLE `event_tickets`
  ADD CONSTRAINT `event_tickets_boxing_event_id_foreign` FOREIGN KEY (`boxing_event_id`) REFERENCES `boxing_events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fight_records`
--
ALTER TABLE `fight_records`
  ADD CONSTRAINT `fight_records_boxer_id_foreign` FOREIGN KEY (`boxer_id`) REFERENCES `boxers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fight_records_boxing_event_id_foreign` FOREIGN KEY (`boxing_event_id`) REFERENCES `boxing_events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fight_records_opponent_id_foreign` FOREIGN KEY (`opponent_id`) REFERENCES `boxers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD CONSTRAINT `news_articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `news_article_category`
--
ALTER TABLE `news_article_category`
  ADD CONSTRAINT `news_article_category_news_article_id_foreign` FOREIGN KEY (`news_article_id`) REFERENCES `news_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_article_category_news_category_id_foreign` FOREIGN KEY (`news_category_id`) REFERENCES `news_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_article_tag`
--
ALTER TABLE `news_article_tag`
  ADD CONSTRAINT `news_article_tag_news_article_id_foreign` FOREIGN KEY (`news_article_id`) REFERENCES `news_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_article_tag_news_tag_id_foreign` FOREIGN KEY (`news_tag_id`) REFERENCES `news_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD CONSTRAINT `news_comments_news_article_id_foreign` FOREIGN KEY (`news_id`) REFERENCES `news_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `news_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_purchases`
--
ALTER TABLE `ticket_purchases`
  ADD CONSTRAINT `ticket_purchases_event_ticket_id_foreign` FOREIGN KEY (`event_ticket_id`) REFERENCES `event_tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_templates`
--
ALTER TABLE `ticket_templates`
  ADD CONSTRAINT `ticket_templates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
