--
-- Table structure for table `deal_booking_quote`
--

CREATE TABLE IF NOT EXISTS `deal_booking_quote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `tour_code` varchar(45) NOT NULL,
  `activity_price_id` varchar(45) NOT NULL,
  `price_id` varchar(45) NOT NULL,
  `quote_key` varchar(150) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `time_id` varchar(45) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dynamic_fields` text,
  `dynamic_fields_values` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;