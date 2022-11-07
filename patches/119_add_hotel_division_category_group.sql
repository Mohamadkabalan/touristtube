
CREATE TABLE `hotel_divisions_categories_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



ALTER TABLE `hotel_divisions_categories` 
ADD COLUMN `hotel_division_category_group_id` INT(11) NULL AFTER `name`,
ADD INDEX `fk_hotel_division_category_group_id_idx` (`hotel_division_category_group_id` ASC);


ALTER TABLE `hotel_divisions_categories` 
ADD CONSTRAINT `fk_hotel_division_category_group_id`
  FOREIGN KEY (`hotel_division_category_group_id`)
  REFERENCES `hotel_divisions_categories_group` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;



INSERT INTO `hotel_divisions_categories_group` (`id`, `name`) VALUES ('1', 'ENTRANCE');
INSERT INTO `hotel_divisions_categories_group` (`id`, `name`) VALUES ('2', 'LOBBY');
INSERT INTO `hotel_divisions_categories_group` (`id`, `name`) VALUES ('3', 'DINNING');
INSERT INTO `hotel_divisions_categories_group` (`id`, `name`) VALUES ('4', 'AMENITIES');
INSERT INTO `hotel_divisions_categories_group` (`id`, `name`) VALUES ('5', 'ACTIVITIES');
INSERT INTO `hotel_divisions_categories_group` (`id`, `name`) VALUES ('6', 'ACCOMODATION');


UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='2' WHERE `id`='1';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='2';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='3';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='4';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='5';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='3' WHERE `id`='6';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='3' WHERE `id`='7';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='3' WHERE `id`='8';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='6' WHERE `id`='9';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='6' WHERE `id`='10';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='1' WHERE `id`='11';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='12';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='5' WHERE `id`='13';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='14';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='6' WHERE `id`='15';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='6' WHERE `id`='16';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='3' WHERE `id`='17';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='18';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='5' WHERE `id`='19';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='20';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='21';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='22';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='23';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='5' WHERE `id`='24';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='25';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='26';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='5' WHERE `id`='27';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='28';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='5' WHERE `id`='29';
UPDATE `hotel_divisions_categories` SET `hotel_division_category_group_id`='4' WHERE `id`='30';



ALTER TABLE `hotel_divisions_categories` 
DROP FOREIGN KEY `fk_hotel_division_category_group_id`;
ALTER TABLE `hotel_divisions_categories` 
CHANGE COLUMN `hotel_division_category_group_id` `hotel_division_category_group_id` INT(11) NOT NULL ;

ALTER TABLE `hotel_divisions_categories` 
ADD CONSTRAINT `fk_hotel_division_category_group_id`
  FOREIGN KEY (`hotel_division_category_group_id`)
  REFERENCES `hotel_divisions_categories_group` (`id`)
  ON UPDATE CASCADE;


