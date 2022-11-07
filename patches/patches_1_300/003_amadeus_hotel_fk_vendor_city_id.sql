
ALTER TABLE `amadeus_hotel` ADD INDEX `fk_vendor_city_id_idx` (`amadeus_city_id` ASC);

ALTER TABLE `amadeus_hotel` ADD CONSTRAINT `fk_vendor_city_id`  FOREIGN KEY (`amadeus_city_id`)  REFERENCES `amadeus_hotel_city` (`id`)  ON DELETE RESTRICT  ON UPDATE CASCADE;
