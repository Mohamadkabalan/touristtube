CREATE TABLE IF NOT EXISTS `transfer_type_vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tt_transfer_type_id` int(11),
  `vendor_id` tinyint(4) NOT NULL,
  `vendor_transfer_type_code` varchar(50) NOT NULL,
  `vendor_transfer_type_name` varchar(255) NOT NULL,
  `vendor_transfer_type_description` varchar(255) NOT NULL,
  `vendor_transfer_type_image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `transfer_type_vendor_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `tt_vendors` (`id`),
  CONSTRAINT `transfer_type_vendor_ibfk_2` FOREIGN KEY (`tt_transfer_type_id`) REFERENCES `tt_transfer_type` (`id`),
  KEY `vendor_transfer_type_code` (`vendor_transfer_type_code`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `transfer_type_vendor` (`id`, `tt_transfer_type_id`, `vendor_id`, `vendor_transfer_type_code`, `vendor_transfer_type_name`, `vendor_transfer_type_description`, `vendor_transfer_type_image`) VALUES (1, 1, 2, 'shared', 'Shared', 'An affordable, convenient and secure solution', 'images/stepseven/stepsiximg1.png'), (2, 2, 2, 'private', 'Private', 'A comfortable, deluxe option with a dedicated driver ready for your safe transfer.', 'images/stepseven/stepsiximg2.png');

