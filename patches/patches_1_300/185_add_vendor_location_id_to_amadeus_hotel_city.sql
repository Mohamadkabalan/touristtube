ALTER TABLE `amadeus_hotel_city` 
ADD COLUMN `vendor_location_id` INT NULL DEFAULT NULL AFTER `published`,
ADD INDEX `idx_vendor_location_id` (`vendor_location_id` ASC);


ALTER TABLE `amadeus_hotel_city` 
DROP INDEX `city_code` ,
ADD UNIQUE INDEX `city_code` (`city_code` ASC, `city_name` ASC, `state_code` ASC, `country_code` ASC, `city_id` ASC, `vendor_id` ASC);


ALTER TABLE `amadeus_hotel_city` 
CHANGE COLUMN `city_code` `city_code` CHAR(10) NOT NULL ;


ALTER TABLE `amadeus_hotel` 
DROP FOREIGN KEY `fk_vendor_city_id`;
ALTER TABLE `amadeus_hotel` 
CHANGE COLUMN `amadeus_city_id` `amadeus_city_id` INT(11) NULL ;
ALTER TABLE `amadeus_hotel` 
ADD CONSTRAINT `fk_vendor_city_id`
  FOREIGN KEY (`amadeus_city_id`)
  REFERENCES `amadeus_hotel_city` (`id`)
  ON UPDATE CASCADE;
