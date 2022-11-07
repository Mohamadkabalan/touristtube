ALTER TABLE `hotel_divisions` ADD INDEX `Idx_hotel_div_parentID` (`parent_id` ASC);


ALTER TABLE `hotel_divisions` ADD CONSTRAINT `fk_hotel_div_parentId` FOREIGN KEY (`parent_id`) REFERENCES `hotel_divisions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `hotel_divisions` ADD INDEX `fk_hotel_div_catgId_idx` (`hotel_division_category_id` ASC);

INSERT INTO hotel_divisions (name) VALUES ('Conference Room');

# without the INSERT statement above,  we would get 
# ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`touristtube`.`# sql-1d10a_15ff0e9f`,  CONSTRAINT `fk_hotel_div_catgId` FOREIGN KEY (`hotel_division_category_id`) REFERENCES `hotel_divisions_categories` (`id`) ON UPDATE CASCADE)

ALTER TABLE `hotel_divisions` ADD CONSTRAINT `fk_hotel_div_catgId` FOREIGN KEY (`hotel_division_category_id`) REFERENCES `hotel_divisions_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `hotel_to_hotel_divisions` CHANGE COLUMN `hotel_id` `hotel_id` INT(11) NOT NULL;

# without hotel_to_hotel_divisions.hotel_id INT(11) we would get error 
# ERROR 1215 (HY000): Cannot add foreign key constraint
# while trying to add constraint fk_hth_div_hotelId_idx
# INT(10) cannot refer to an INT(11),  it doesn''t have enough room to do so
# It didn''t work without removing the UNSIGNED keyword,  something I didn''t expect too!

ALTER TABLE `hotel_to_hotel_divisions` ADD CONSTRAINT `fk_hth_div_hotelId_idx` FOREIGN KEY (`hotel_id`) REFERENCES `amadeus_hotel` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `hotel_to_hotel_divisions` ADD CONSTRAINT `fk_hth_div_divId` FOREIGN KEY (`hotel_division_id`) REFERENCES `hotel_divisions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


# same story as above,  fix so that fk_hth_div_catg_hotelId works below,  ERROR 1215 (HY000): Cannot add foreign key constraint
ALTER TABLE `hotel_to_hotel_divisions_categories` CHANGE COLUMN `hotel_id` `hotel_id` INT(11) NOT NULL;

ALTER TABLE `hotel_to_hotel_divisions_categories` ADD INDEX `fk_hth_div_catg_hotelId_idx` (`hotel_id` ASC);


ALTER TABLE `hotel_to_hotel_divisions_categories` ADD CONSTRAINT `fk_hth_div_catg_hotelId` FOREIGN KEY (`hotel_id`) REFERENCES `amadeus_hotel` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `hotel_to_hotel_divisions_categories` ADD INDEX `fk_hth_div_catg_catgId_idx` (`hotel_division_category_id` ASC);

ALTER TABLE `hotel_to_hotel_divisions_categories` ADD CONSTRAINT `fk_hth_div_catg_catgId` FOREIGN KEY (`hotel_division_category_id`) REFERENCES `hotel_divisions_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
