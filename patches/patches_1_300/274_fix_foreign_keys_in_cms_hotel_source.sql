
ALTER TABLE cms_hotel_source DROP FOREIGN KEY fk_hotel_src_id;

ALTER TABLE `cms_hotel_source` ADD CONSTRAINT `fk_hotel_source_hotel_id` FOREIGN KEY (`hotel_id`) REFERENCES `cms_hotel` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- disabled until 
-- ALTER TABLE `cms_hotel_source` ADD CONSTRAINT 'fk_hotel_source_location_id' FOREIGN KEY (`location_id`) REFERENCES `cms_hotel_city` (`location_id`) ON DELETE RESTRICT ON UPDATE CASCADE;
