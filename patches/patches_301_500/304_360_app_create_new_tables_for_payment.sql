CREATE TABLE `payment_status` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `payment_status` (`id`, `name`) VALUES (1, 'Pending');
INSERT INTO `payment_status` (`id`, `name`) VALUES (2, 'Success');
INSERT INTO `payment_status` (`id`, `name`) VALUES (3, 'Failed');



CREATE TABLE `payment_type` (
  `id` tinyint(4) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `payment_type` (`id`, `code`, `name`) VALUES (1, 'cc', 'Credit Card');



CREATE TABLE `360_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` char(3) NOT NULL,
  `amount_fbc` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `amount_sbc` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `customer_ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `device_fingerprint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `status` tinyint(4) NOT NULL,
  `payment_type` tinyint(4) NOT NULL,
  `module_id` tinyint(4) NOT NULL,
  `module_transaction_id` int(11) NOT NULL,
  `display_currency` char(3) NOT NULL,
  `display_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vendor_reference` varchar(50) DEFAULT NULL,
  `vendor_status` varchar(30) DEFAULT NULL,
  `response_code` varchar(10) DEFAULT NULL,
  `response_message` varchar(255) DEFAULT NULL,
  `payment_card_token` varchar(30) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;