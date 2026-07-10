
CREATE DATABASE IF NOT EXISTS `compost`;
USE `compost`;

-- Volcando estructura para tabla compost.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `compostings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pile_num` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `total_kg` decimal(10,2) DEFAULT NULL,
  `efficiency` decimal(5,2) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compostings_created_by_foreign` (`created_by`),
  CONSTRAINT `compostings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `fertilizers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `composting_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `requester` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `received_by` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivered_by` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('Liquid','Solid') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fertilizers_composting_id_foreign` (`composting_id`),
  KEY `fk_fertilizers_created_by` (`created_by`),
  CONSTRAINT `fertilizers_composting_id_foreign` FOREIGN KEY (`composting_id`) REFERENCES `compostings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fertilizers_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `composting_id` bigint unsigned NOT NULL,
  `organic_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ingredients_composting_id_foreign` (`composting_id`),
  KEY `ingredients_organic_id_foreign` (`organic_id`),
  CONSTRAINT `ingredients_composting_id_foreign` FOREIGN KEY (`composting_id`) REFERENCES `compostings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ingredients_organic_id_foreign` FOREIGN KEY (`organic_id`) REFERENCES `organics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `machineries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_func` date NOT NULL,
  `maint_freq` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `next_maintenance_due_at` datetime DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `machineries_created_by_foreign` (`created_by`),
  CONSTRAINT `machineries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `maintenances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `machinery_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `type` enum('O','M') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsible` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenances_machinery_id_foreign` (`machinery_id`),
  KEY `maintenances_created_by_foreign` (`created_by`),
  CONSTRAINT `maintenances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `maintenances_machinery_id_foreign` FOREIGN KEY (`machinery_id`) REFERENCES `machineries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `from_user_id` bigint unsigned NOT NULL,
  `organic_id` bigint unsigned DEFAULT NULL,
  `composting_id` bigint unsigned DEFAULT NULL,
  `type` enum('delete_request','edit_request','maintenance_reminder') COLLATE utf8mb4_unicode_ci NOT NULL,
  `machinery_id` bigint unsigned DEFAULT NULL,
  `maintenance_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `usage_control_id` bigint unsigned DEFAULT NULL,
  `fertilizer_id` bigint unsigned DEFAULT NULL,
  `tracking_id` bigint unsigned DEFAULT NULL,
  `status` enum('pending','approved','rejected','processed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `message` text COLLATE utf8mb4_unicode_ci,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  KEY `notifications_from_user_id_foreign` (`from_user_id`),
  KEY `notifications_organic_id_foreign` (`organic_id`),
  KEY `notifications_composting_id_foreign` (`composting_id`),
  KEY `notifications_machinery_id_foreign` (`machinery_id`),
  KEY `notifications_maintenance_id_foreign` (`maintenance_id`),
  KEY `notifications_supplier_id_foreign` (`supplier_id`),
  KEY `notifications_usage_control_id_foreign` (`usage_control_id`),
  KEY `notifications_fertilizer_id_foreign` (`fertilizer_id`),
  KEY `notifications_tracking_id_foreign` (`tracking_id`),
  CONSTRAINT `notifications_composting_id_foreign` FOREIGN KEY (`composting_id`) REFERENCES `compostings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_fertilizer_id_foreign` FOREIGN KEY (`fertilizer_id`) REFERENCES `fertilizers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_machinery_id_foreign` FOREIGN KEY (`machinery_id`) REFERENCES `machineries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_maintenance_id_foreign` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenances` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_organic_id_foreign` FOREIGN KEY (`organic_id`) REFERENCES `organics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_tracking_id_foreign` FOREIGN KEY (`tracking_id`) REFERENCES `trackings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_usage_control_id_foreign` FOREIGN KEY (`usage_control_id`) REFERENCES `usage_controls` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE IF NOT EXISTS `organics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `type` enum('Kitchen','Beds','Leaves','CowDung','ChickenManure','PigManure','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `delivered_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `received_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organics_created_by_foreign` (`created_by`),
  CONSTRAINT `organics_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `module` enum('organics','compostings','trackings','fertilizers','machineries','usage_Controls') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `machinery_id` bigint unsigned NOT NULL,
  `maker` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `supplier` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_machinery_id_foreign` (`machinery_id`),
  KEY `suppliers_created_by_foreign` (`created_by`),
  CONSTRAINT `suppliers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `suppliers_machinery_id_foreign` FOREIGN KEY (`machinery_id`) REFERENCES `machineries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `trackings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `composting_id` bigint unsigned NOT NULL,
  `day` int NOT NULL,
  `date` date NOT NULL,
  `activity` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_hours` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temp_internal` decimal(5,2) DEFAULT NULL,
  `temp_time` time DEFAULT NULL,
  `temp_env` decimal(5,2) DEFAULT NULL,
  `hum_pile` decimal(5,2) DEFAULT NULL,
  `hum_env` decimal(5,2) DEFAULT NULL,
  `ph` decimal(4,2) DEFAULT NULL,
  `water` decimal(10,2) DEFAULT NULL,
  `lime` decimal(10,2) DEFAULT NULL,
  `others` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trackings_composting_id_foreign` (`composting_id`),
  KEY `trackings_created_by_foreign` (`created_by`),
  CONSTRAINT `trackings_composting_id_foreign` FOREIGN KEY (`composting_id`) REFERENCES `compostings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trackings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `usage_controls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `machinery_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `hours` int DEFAULT NULL,
  `responsible` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('operativa','mantenimiento') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operativa',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usage_controls_machinery_id_foreign` (`machinery_id`),
  KEY `usage_controls_created_by_foreign` (`created_by`),
  CONSTRAINT `usage_controls_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `usage_controls_machinery_id_foreign` FOREIGN KEY (`machinery_id`) REFERENCES `machineries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','aprendiz') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aprendiz',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_identification_unique` (`identification`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `warehouse_classification` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organic_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `type` enum('Kitchen','Beds','Leaves','CowDung','ChickenManure','PigManure','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_type` enum('entry','exit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `processed_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouse_classification_organic_id_foreign` (`organic_id`),
  CONSTRAINT `warehouse_classification_organic_id_foreign` FOREIGN KEY (`organic_id`) REFERENCES `organics` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


