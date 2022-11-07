--
-- Create the new column
--
ALTER TABLE amadeus_hotel ADD city_id INT(11) NOT NULL DEFAULT '0' AFTER amadeus_city_id;

--
-- Have amadeus_hotel_city:city_id not nullable
--
ALTER TABLE `amadeus_hotel_city` CHANGE `city_id` `city_id` INT(11) NOT NULL DEFAULT '0';

--
-- Copy amadeus_hotel_city:city_id to amadeus_hotel:city_id
--
UPDATE amadeus_hotel SET amadeus_hotel.city_id = (SELECT amadeus_hotel_city.city_id FROM amadeus_hotel_city WHERE amadeus_hotel_city.id = amadeus_hotel.amadeus_city_id);
  
--
-- Create the new column, populate it and add the FK
--
ALTER TABLE `amadeus_hotel_city` ADD `vendor_id` TINYINT(4) NOT NULL AFTER `popularity`;
UPDATE amadeus_hotel_city SET vendor_id = (SELECT id FROM tt_vendors WHERE name = 'Amadeus');
ALTER TABLE `amadeus_hotel_city` ADD CONSTRAINT `fk_hotel_city_vendor_id` FOREIGN KEY (`vendor_id`) REFERENCES `tt_vendors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;