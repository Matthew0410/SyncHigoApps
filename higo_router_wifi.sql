/*
SQLyog Ultimate
MySQL - 5.7.22-0ubuntu0.16.04.1 : Database - higo_router_wifi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `alogin` */

DROP TABLE IF EXISTS `alogin`;

CREATE TABLE `alogin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `log_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `log_id` (`log_id`),
  KEY `date` (`date`),
  KEY `higo_router_name` (`higo_router_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `answer` */

DROP TABLE IF EXISTS `answer`;

CREATE TABLE `answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `log_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `option` float(1,1) unsigned NOT NULL DEFAULT '0.0',
  `option2` float(1,1) unsigned NOT NULL DEFAULT '0.0',
  `option3` float(1,1) unsigned NOT NULL DEFAULT '0.0',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `matriks` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `log_id` (`log_id`),
  KEY `number` (`type`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `browsing_history` */

DROP TABLE IF EXISTS `browsing_history`;

CREATE TABLE `browsing_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `log_id` varchar(32) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time` time NOT NULL DEFAULT '00:00:00',
  `ip_address` varchar(32) NOT NULL DEFAULT '',
  `mac_address` varchar(32) NOT NULL DEFAULT '',
  `host_name` varchar(64) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  `pptp_ip_address` varchar(32) NOT NULL DEFAULT '',
  `pptp_name` varchar(64) NOT NULL DEFAULT '',
  `webshrink_category` varchar(255) NOT NULL DEFAULT '',
  `webshrink_category2` varchar(255) NOT NULL DEFAULT '',
  `webshrink_category3` varchar(255) NOT NULL DEFAULT '',
  `webshrink_category4` varchar(255) NOT NULL DEFAULT '',
  `webshrink_category5` varchar(255) NOT NULL DEFAULT '',
  `webshrink_category6` varchar(255) NOT NULL DEFAULT '',
  `webshrink_json` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `calendars` */

DROP TABLE IF EXISTS `calendars`;

CREATE TABLE `calendars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date_event` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendars_user_id_foreign` (`user_id`),
  CONSTRAINT `calendars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `confirmation_page` */

DROP TABLE IF EXISTS `confirmation_page`;

CREATE TABLE `confirmation_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `date` (`date`),
  KEY `higo_router_name` (`higo_router_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `critic_suggestion` */

DROP TABLE IF EXISTS `critic_suggestion`;

CREATE TABLE `critic_suggestion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(64) NOT NULL DEFAULT '',
  `critic_name` varchar(64) NOT NULL DEFAULT '',
  `critic_email` varchar(64) NOT NULL DEFAULT '',
  `critic_suggestion` text NOT NULL,
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `dismiss_ads` */

DROP TABLE IF EXISTS `dismiss_ads`;

CREATE TABLE `dismiss_ads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `url` text NOT NULL,
  `ip` varchar(32) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `higo_router` */

DROP TABLE IF EXISTS `higo_router`;

CREATE TABLE `higo_router` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `email` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `landing_page` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `higo_router_facebook_message` */

DROP TABLE IF EXISTS `higo_router_facebook_message`;

CREATE TABLE `higo_router_facebook_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `higo_router_id` int(10) unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `higo_router_gci_member` */

DROP TABLE IF EXISTS `higo_router_gci_member`;

CREATE TABLE `higo_router_gci_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `card_id` varchar(64) NOT NULL DEFAULT '',
  `customer_id` varchar(64) NOT NULL DEFAULT '',
  `distribution_id` varchar(64) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(64) NOT NULL DEFAULT '',
  `phone` varchar(16) NOT NULL DEFAULT '',
  `sex` varchar(32) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `city` varchar(32) NOT NULL DEFAULT '',
  `address` text NOT NULL,
  `card_number` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `higo_router_gci_member_login` */

DROP TABLE IF EXISTS `higo_router_gci_member_login`;

CREATE TABLE `higo_router_gci_member_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `higo_router_mikrotik` */

DROP TABLE IF EXISTS `higo_router_mikrotik`;

CREATE TABLE `higo_router_mikrotik` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user` varchar(64) NOT NULL DEFAULT '',
  `model` varchar(64) NOT NULL DEFAULT '',
  `serial_number` varchar(64) NOT NULL DEFAULT '',
  `current_firmware` varchar(8) NOT NULL DEFAULT '',
  `upgrade_firmware` varchar(8) NOT NULL DEFAULT '',
  `version` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `model` (`model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `higo_router_twitter_message` */

DROP TABLE IF EXISTS `higo_router_twitter_message`;

CREATE TABLE `higo_router_twitter_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `higo_router_id` int(10) unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `higo_router_vendor_content` */

DROP TABLE IF EXISTS `higo_router_vendor_content`;

CREATE TABLE `higo_router_vendor_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `content_id` int(10) unsigned NOT NULL DEFAULT '0',
  `higo_router_id` int(10) NOT NULL DEFAULT '0',
  `vendor_id` int(10) NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `number` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `image` text NOT NULL,
  `free_magz` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content_name` varchar(64) NOT NULL DEFAULT '',
  `vendor_name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `date` (`date`),
  KEY `status` (`status`),
  KEY `free_magz` (`free_magz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `injected_url` */

DROP TABLE IF EXISTS `injected_url`;

CREATE TABLE `injected_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `url` text NOT NULL,
  `ip` varchar(32) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `link_view` */

DROP TABLE IF EXISTS `link_view`;

CREATE TABLE `link_view` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` varchar(32) NOT NULL DEFAULT '',
  `number` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `url` text NOT NULL,
  `ip` varchar(32) NOT NULL DEFAULT '',
  `referer` text NOT NULL,
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `log` */

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `socmed_id` varchar(64) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `gender` varchar(32) NOT NULL DEFAULT '',
  `phone` varchar(16) NOT NULL DEFAULT '',
  `birthday` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image` varchar(320) NOT NULL DEFAULT '',
  `username` varchar(64) NOT NULL DEFAULT '',
  `followers_count` int(10) unsigned NOT NULL DEFAULT '0',
  `friends_count` int(10) unsigned NOT NULL DEFAULT '0',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `type` (`type`),
  KEY `date` (`date`),
  KEY `higo_router_name` (`higo_router_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `login` */

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `date` (`date`),
  KEY `higo_router_name` (`higo_router_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `media_counter` */

DROP TABLE IF EXISTS `media_counter`;

CREATE TABLE `media_counter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(64) NOT NULL DEFAULT '',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mol_player` */

DROP TABLE IF EXISTS `mol_player`;

CREATE TABLE `mol_player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `winner` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `mol_player_live` */

DROP TABLE IF EXISTS `mol_player_live`;

CREATE TABLE `mol_player_live` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `winner` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `mol_player_winner` */

DROP TABLE IF EXISTS `mol_player_winner`;

CREATE TABLE `mol_player_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `phone` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `mol_voucher_code` */

DROP TABLE IF EXISTS `mol_voucher_code`;

CREATE TABLE `mol_voucher_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mol_player_live_id` int(10) unsigned NOT NULL DEFAULT '0',
  `serial` varchar(64) NOT NULL DEFAULT '',
  `voucher` varchar(64) NOT NULL DEFAULT '',
  `used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `oauth_access_tokens` */

DROP TABLE IF EXISTS `oauth_access_tokens`;

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `oauth_auth_codes` */

DROP TABLE IF EXISTS `oauth_auth_codes`;

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `oauth_clients` */

DROP TABLE IF EXISTS `oauth_clients`;

CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `oauth_personal_access_clients` */

DROP TABLE IF EXISTS `oauth_personal_access_clients`;

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `oauth_refresh_tokens` */

DROP TABLE IF EXISTS `oauth_refresh_tokens`;

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sms_verification` */

DROP TABLE IF EXISTS `sms_verification`;

CREATE TABLE `sms_verification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `code` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tvc_winner` */

DROP TABLE IF EXISTS `tvc_winner`;

CREATE TABLE `tvc_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `log_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `winner` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `higo_router_id` (`higo_router_id`),
  KEY `log_id` (`log_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `uc_page` */

DROP TABLE IF EXISTS `uc_page`;

CREATE TABLE `uc_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `higo_router_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(32) NOT NULL,
  `higo_router_name` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `mac` varchar(32) NOT NULL DEFAULT '',
  `server` text NOT NULL,
  `get` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `higo_router_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_mobile` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `password_temp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_reminder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_higo_router_id_foreign` (`higo_router_id`),
  FULLTEXT KEY `full` (`token_mobile`),
  CONSTRAINT `users_higo_router_id_foreign` FOREIGN KEY (`higo_router_id`) REFERENCES `higo_router` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
