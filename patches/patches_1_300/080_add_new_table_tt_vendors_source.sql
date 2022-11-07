CREATE TABLE IF NOT EXISTS `tt_vendors_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tt_vendor_id` tinyint(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

ALTER TABLE `tt_vendors_source` ADD CONSTRAINT `FK_tt_vendor_id` FOREIGN KEY (`tt_vendor_id`) REFERENCES `tt_vendors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Dumping data for table `tt_vendors_source`
--
INSERT INTO `tt_vendors_source` (`id`, `tt_vendor_id`, `name`) VALUES
(1, 4, 'gds'),
(2, 4, 'hotelbeds'),
(3, 4, 'hrs');
