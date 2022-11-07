CREATE TABLE IF NOT EXISTS `payment_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,0) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `last_4_digits` varchar(4) DEFAULT NULL,
  `first_4_digits` varchar(4) DEFAULT NULL,
  `card_type` varchar(20) NOT NULL,
  `payment_date` varchar(50) NOT NULL,
  `secure_sign` varchar(100) DEFAULT NULL,
  `payment_id` varchar(36) NOT NULL,
  `response_code` varchar(20) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `payment_info`
  ADD CONSTRAINT `payment_info_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`uuid`) ON UPDATE CASCADE;
