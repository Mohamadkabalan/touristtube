ALTER TABLE `hotel_divisions_categories_group` 
ADD COLUMN `sort_order` INT NULL DEFAULT 999 AFTER `name`;

UPDATE `hotel_divisions_categories_group` SET `sort_order`='1' WHERE `id`='1';
UPDATE `hotel_divisions_categories_group` SET `sort_order`='2' WHERE `id`='2';
UPDATE `hotel_divisions_categories_group` SET `sort_order`='3' WHERE `id`='3';
UPDATE `hotel_divisions_categories_group` SET `sort_order`='4' WHERE `id`='4';
UPDATE `hotel_divisions_categories_group` SET `sort_order`='5' WHERE `id`='6';
UPDATE `hotel_divisions_categories_group` SET `sort_order`='6' WHERE `id`='5';
