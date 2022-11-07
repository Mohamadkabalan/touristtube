
ALTER TABLE `deal_city` CHANGE COLUMN `city_id` `city_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `deal_city` ADD INDEX `FK_deal_tt_city_id_idx` (`city_id` ASC);

ALTER TABLE `deal_city` ADD CONSTRAINT `FK_deal_tt_city_id` FOREIGN KEY (`city_id`) REFERENCES `webgeocities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
