ALTER TABLE `hotel_divisions_categories_group` 
ADD COLUMN `last_updated` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP AFTER `sort_order`;

ALTER TABLE `hotel_divisions_categories` 
ADD COLUMN `last_updated` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP AFTER `hotel_division_category_group_id`;

ALTER TABLE `hotel_to_hotel_divisions` 
ADD COLUMN `last_updated` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP AFTER `sort_order`;
