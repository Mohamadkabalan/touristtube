ALTER TABLE `hotel_divisions` 
ADD COLUMN `last_updated` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP AFTER `sort_order`;