CREATE TABLE `payment_details` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `uuid` varchar(36) NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 `api_response` json NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
