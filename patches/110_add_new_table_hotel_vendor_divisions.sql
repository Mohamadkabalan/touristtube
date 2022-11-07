--
-- Table structure for table `hotel_vendor_divisions`
--
CREATE TABLE IF NOT EXISTS `hotel_vendor_divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_to_hotel_divisions_id` int(11) NOT NULL,
  `tt_vendor_id` tinyint(4) NOT NULL,
  `vendor_div_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Add the needed FKs
--
ALTER TABLE `hotel_vendor_divisions` ADD CONSTRAINT `fk_hhd_id` FOREIGN KEY (`hotel_to_hotel_divisions_id`) REFERENCES `hotel_to_hotel_divisions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `hotel_vendor_divisions` ADD CONSTRAINT `fk_hvd_tt_vendor_id` FOREIGN KEY (`tt_vendor_id`) REFERENCES `tt_vendors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Add the needed indexes
--
ALTER TABLE `hotel_vendor_divisions` ADD INDEX `idx_vendor_div_name` (`vendor_div_name` ASC);