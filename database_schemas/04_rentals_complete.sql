-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: farmshop
-- ------------------------------------------------------
-- Server version	11.5.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping routines for database 'farmshop'
--

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'France',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `newsletter_subscribed` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(500) DEFAULT NULL COMMENT 'Description courte du produit',
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL COMMENT 'Code SKU du produit',
  `price` decimal(10,2) NOT NULL,
  `rental_price_per_day` decimal(8,2) DEFAULT NULL COMMENT 'Prix de location par jour',
  `deposit_amount` decimal(8,2) DEFAULT NULL COMMENT 'Montant de la caution pour la location',
  `min_rental_days` int(11) NOT NULL DEFAULT 1,
  `max_rental_days` int(11) NOT NULL DEFAULT 7,
  `available_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Jours de la semaine disponibles (1=Lundi, 6=Samedi)' CHECK (json_valid(`available_days`)),
  `type` enum('sale','rental','both') NOT NULL DEFAULT 'sale' COMMENT 'Type de produit: vente, location ou les deux',
  `weight` decimal(8,3) DEFAULT NULL COMMENT 'Poids du produit en kg',
  `dimensions` varchar(255) DEFAULT NULL COMMENT 'Dimensions du produit',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `critical_threshold` int(11) NOT NULL DEFAULT 5,
  `low_stock_threshold` int(11) DEFAULT NULL COMMENT 'Seuil de stock faible',
  `out_of_stock_threshold` int(11) DEFAULT NULL COMMENT 'Seuil de rupture de stock',
  `unit_symbol` enum('kg','pi├¿ce','litre','gramme','tonne') NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `image_alt` text DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Images du produit au format JSON' CHECK (json_valid(`images`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `category_id` bigint(20) unsigned NOT NULL,
  `rental_category_id` bigint(20) unsigned DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_name_is_active_index` (`name`,`is_active`),
  KEY `products_category_id_is_active_index` (`category_id`,`is_active`),
  KEY `products_price_index` (`price`),
  KEY `products_quantity_index` (`quantity`),
  KEY `products_rental_category_id_foreign` (`rental_category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_rental_category_id_foreign` FOREIGN KEY (`rental_category_id`) REFERENCES `rental_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `icon` varchar(10) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `food_type` enum('alimentaire','non_alimentaire') NOT NULL DEFAULT 'alimentaire',
  `is_returnable` tinyint(1) NOT NULL DEFAULT 0,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rental_categories`
--

DROP TABLE IF EXISTS `rental_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rental_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rental_categories_name_unique` (`name`),
  UNIQUE KEY `rental_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_locations`
--

DROP TABLE IF EXISTS `order_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `rental_days` int(11) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `total_rental_cost` decimal(10,2) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL,
  `late_fee_per_day` decimal(8,2) NOT NULL DEFAULT 10.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 21.00,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','active','completed','closed','inspecting','finished','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','deposit_paid','paid','failed','refunded','partial_refund') NOT NULL DEFAULT 'pending',
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`billing_address`)),
  `delivery_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`delivery_address`)),
  `late_days` int(11) NOT NULL DEFAULT 0,
  `late_fees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `actual_return_date` timestamp NULL DEFAULT NULL,
  `inspection_status` enum('pending','in_progress','completed') DEFAULT NULL,
  `product_condition` enum('excellent','good','poor') DEFAULT NULL,
  `damage_cost` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_penalties` decimal(8,2) NOT NULL DEFAULT 0.00,
  `deposit_refund` decimal(8,2) NOT NULL DEFAULT 0.00,
  `inspection_notes` text DEFAULT NULL,
  `inspection_completed_at` timestamp NULL DEFAULT NULL,
  `inspected_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_locations_order_number_unique` (`order_number`),
  KEY `order_locations_inspected_by_foreign` (`inspected_by`),
  KEY `order_locations_user_id_status_index` (`user_id`,`status`),
  KEY `order_locations_start_date_end_date_index` (`start_date`,`end_date`),
  KEY `order_locations_status_index` (`status`),
  CONSTRAINT `order_locations_inspected_by_foreign` FOREIGN KEY (`inspected_by`) REFERENCES `users` (`id`),
  CONSTRAINT `order_locations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_item_locations`
--

DROP TABLE IF EXISTS `order_item_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_location_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `daily_rate` decimal(8,2) NOT NULL,
  `rental_days` int(11) NOT NULL,
  `deposit_per_item` decimal(8,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total_deposit` decimal(10,2) NOT NULL,
  `tax_amount` decimal(8,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `condition_at_pickup` enum('excellent','good','fair','poor') NOT NULL DEFAULT 'excellent',
  `condition_at_return` enum('excellent','good','fair','poor') DEFAULT NULL,
  `item_damage_cost` decimal(8,2) NOT NULL DEFAULT 0.00,
  `item_inspection_notes` text DEFAULT NULL,
  `damage_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`damage_details`)),
  `item_late_days` int(11) NOT NULL DEFAULT 0,
  `item_late_fees` decimal(8,2) NOT NULL DEFAULT 0.00,
  `item_deposit_refund` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_item_locations_product_id_foreign` (`product_id`),
  KEY `order_item_locations_order_location_id_product_id_index` (`order_location_id`,`product_id`),
  CONSTRAINT `order_item_locations_order_location_id_foreign` FOREIGN KEY (`order_location_id`) REFERENCES `order_locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_item_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cart_locations`
--

DROP TABLE IF EXISTS `cart_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deposit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_tva` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_with_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_items` int(11) NOT NULL DEFAULT 0,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `default_start_date` date DEFAULT NULL,
  `default_end_date` date DEFAULT NULL,
  `default_duration_days` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_locations_user_id_index` (`user_id`),
  KEY `cart_locations_created_at_index` (`created_at`),
  CONSTRAINT `cart_locations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cart_item_locations`
--

DROP TABLE IF EXISTS `cart_item_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_item_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_location_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_days` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price_per_day` decimal(8,2) NOT NULL,
  `unit_deposit` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subtotal_amount` decimal(10,2) NOT NULL,
  `subtotal_deposit` decimal(10,2) NOT NULL,
  `tva_amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `rental_category_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `availability_checked_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_product_per_cart_location` (`cart_location_id`,`product_id`),
  KEY `cart_item_locations_cart_location_id_index` (`cart_location_id`),
  KEY `cart_item_locations_product_id_index` (`product_id`),
  KEY `cart_item_locations_start_date_end_date_index` (`start_date`,`end_date`),
  KEY `cart_item_locations_created_at_index` (`created_at`),
  CONSTRAINT `cart_item_locations_cart_location_id_foreign` FOREIGN KEY (`cart_location_id`) REFERENCES `cart_locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_item_locations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-14 20:50:22
