DELETE FROM amadeus_hotel_source where hotel_id not in (select id from amadeus_hotel);
DELETE FROM amadeus_hotel_image where hotel_id not in (select id from amadeus_hotel);

ALTER TABLE hotel_to_hotel_divisions DROP FOREIGN KEY fk_hth_div_hotelId_idx;
ALTER TABLE hotel_to_hotel_divisions CHANGE COLUMN hotel_id hotel_id INT(11) UNSIGNED NOT NULL;


ALTER TABLE hotel_to_hotel_divisions_categories DROP FOREIGN KEY fk_hth_div_catg_hotelId;
ALTER TABLE hotel_to_hotel_divisions_categories CHANGE COLUMN hotel_id hotel_id INT(11) UNSIGNED NOT NULL;


ALTER TABLE amadeus_hotel CHANGE COLUMN id id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE hotel_to_hotel_divisions CHANGE COLUMN hotel_id hotel_id INT(11) UNSIGNED NOT NULL;

ALTER TABLE hotel_to_hotel_divisions ADD CONSTRAINT fk_hth_div_hotel_id FOREIGN KEY (hotel_id) REFERENCES amadeus_hotel (id) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE hotel_to_hotel_divisions_categories CHANGE COLUMN hotel_id hotel_id INT(11) UNSIGNED NOT NULL;

ALTER TABLE hotel_to_hotel_divisions_categories ADD CONSTRAINT fk_hth_div_catg_hotel_id FOREIGN KEY (hotel_id) REFERENCES amadeus_hotel (id) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE amadeus_hotel_image CHANGE COLUMN hotel_id hotel_id INT(11) UNSIGNED NOT NULL;



ALTER TABLE amadeus_hotel_source DROP FOREIGN KEY fk_amadeus_hotel_id;
ALTER TABLE amadeus_hotel_source DROP FOREIGN KEY fk_tt_vendor_src_id, DROP FOREIGN KEY fk_tt_vendors_id;
ALTER TABLE amadeus_hotel_source DROP INDEX fk_amadeus_hotel_id_idx;
ALTER TABLE amadeus_hotel_source DROP INDEX fk_tt_vendor_id_idx, DROP INDEX fk_tt_vendor_src_id_idx;
ALTER TABLE `amadeus_hotel_source` CHANGE COLUMN `hotel_id` `hotel_id` INT(11) UNSIGNED NOT NULL;


ALTER TABLE `amadeus_hotel_source` 
CHANGE COLUMN `hotel_id` `hotel_id` INT(11) UNSIGNED NOT NULL,
ADD INDEX `idx_amadeus_hotel_id` (`hotel_id` ASC),
DROP INDEX `hotel_id`;

ALTER TABLE `amadeus_hotel_source` 
ADD CONSTRAINT `fk_amadeus_hotel_id`
  FOREIGN KEY (`hotel_id`)
  REFERENCES `amadeus_hotel` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;


## ALTER TABLE amadeus_hotel_image DROP FOREIGN KEY fk_amadeus_hotel_id_idx;


ALTER TABLE `amadeus_hotel_image` 
CHANGE COLUMN `hotel_id` `hotel_id` INT(11) UNSIGNED NOT NULL DEFAULT '0', 
DROP INDEX `IDX_HOTEL_IMAGE_HOTEL_ID`, 
ADD INDEX `idx_amadeus_hotel_id` (`hotel_id` ASC);

ALTER TABLE `amadeus_hotel_image` 
ADD CONSTRAINT `fk_amadeus_images_hotel_id`
  FOREIGN KEY (`hotel_id`)
  REFERENCES `amadeus_hotel` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;


ALTER TABLE `amadeus_hotel_source` 
ADD COLUMN `tt_vendor_id` TINYINT(4) NOT NULL AFTER `published`,
ADD INDEX `idx_tt_vendor_id` (`tt_vendor_id` ASC);

UPDATE amadeus_hotel_source set tt_vendor_id = 4;


ALTER TABLE `amadeus_hotel_source` 
ADD CONSTRAINT `fk_tt_vendors_id`
  FOREIGN KEY (`tt_vendor_id`)
  REFERENCES `tt_vendors` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;



ALTER TABLE `amadeus_hotel_source` 
ADD COLUMN `tt_source_id` INT(11) NULL AFTER `tt_vendor_id`,
ADD INDEX `idx_tt_vendor_src_id` (`tt_source_id` ASC);

ALTER TABLE `amadeus_hotel_source` 
ADD CONSTRAINT `fk_tt_vendor_src_id`
  FOREIGN KEY (`tt_source_id`)
  REFERENCES `tt_vendors_source` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;


UPDATE amadeus_hotel_source SET tt_source_id = 1 where source = 'gds';
UPDATE amadeus_hotel_source SET tt_source_id = 2 where source = 'hotelbeds';
UPDATE amadeus_hotel_source SET tt_source_id = 3 where source = 'hrs';


INSERT INTO `tt_vendors_source` (`tt_vendor_id`, `name`) VALUES ('3', 'hrs');

ALTER TABLE `tt_vendors_source` 
ADD INDEX `idx_src_name` (`name` ASC);


UPDATE amadeus_hotel set city_id = 1327968 where city_id = 1327171;
