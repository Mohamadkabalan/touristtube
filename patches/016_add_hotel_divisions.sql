#Add restaurant-5 division 
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `sort_order`) VALUES (205, 'Restaurant 5', '6', '999');

INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Restaurant 5.1', '6', '205', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Restaurant 5.2', '6', '205', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Restaurant 5.3', '6', '205', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Restaurant 5.4', '6', '205', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Restaurant 5.5', '6', '205', '999');


#Add Sauna sub division under SPA division 
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (211, 'Steam Sauna', '4', '4', '999');


#Add new Categories
#
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (15, 'Suite');
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (16, 'Villa');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('237237', '15');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('237237', '16');


#Fix Suite Divisions categories 
#
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='9';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='10';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='11';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='159';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='160';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='161';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='162';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='163';
UPDATE `hotel_divisions` SET `hotel_division_category_id`='15' WHERE `id`='164';


#Add new Junior Suit Division
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `sort_order`) VALUES (212, 'Junior Suite', '15', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (213, 'Living Room 1', '15', '212', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (214, 'Living Room 2', '15', '212', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (215, 'Living Room 3', '15', '212', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (216, 'Bedroom', '15', '212', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (217, 'Bathroom', '15', '212', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (218, 'Guest Bathroom', '15', '212', '999');


#Add sub divisions under Cafe
#
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `ota_id`, `sort_order`) VALUES ('Cafe 1.6', '8', '8', NULL, '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `ota_id`, `sort_order`) VALUES ('Cafe 1.7', '8', '8', NULL, '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `ota_id`, `sort_order`) VALUES ('Cafe 1.8', '8', '8', NULL, '999');


#Add new Cafe Division
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `sort_order`) VALUES (222, 'Cafe 2', '8', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.1', '8', '222', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.2', '8', '222', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.3', '8', '222', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.4', '8', '222', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.5', '8', '222', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.6', '8', '222', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.7', '8', '222', '999');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES ('Cafe 2.8', '8', '222', '999');


#Add Bar 2 Division
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `sort_order`) VALUES (231, 'Bar 2', '7', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (232, 'Bar 2.1', '7');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (233, 'Bar 2.2', '7');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (234, 'Bar 2.3', '7');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (235, 'Bar 2.4', '7');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (236, 'Bar 2.5', '7');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (237, 'Bar 2.6', '7');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (238, 'Bar 2.7', '7');

UPDATE `hotel_divisions` SET `parent_id`='231' WHERE `id`='232';
UPDATE `hotel_divisions` SET `parent_id`='231' WHERE `id`='233';
UPDATE `hotel_divisions` SET `parent_id`='231' WHERE `id`='234';
UPDATE `hotel_divisions` SET `parent_id`='231' WHERE `id`='235';
UPDATE `hotel_divisions` SET `parent_id`='231' WHERE `id`='236';
UPDATE `hotel_divisions` SET `parent_id`='231' WHERE `id`='237';
UPDATE `hotel_divisions` SET `parent_id`='231' WHERE `id`='238';


#Add new Night Club Category
#
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES ('17', 'Night Club');

INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES (240, 'View 1', '17', '239');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES (241, 'View 2', '17', '239');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES (242, 'View 3', '17', '239');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES (243, 'View 4', '17', '239');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES (244, 'View 5', '17', '239');


#Add bathroom to stantard deluxe room
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (245, 'Bathroom', '9', '36');


#Add Twin room
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (246, 'Twin Room', '9');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (247, 'View 1', '9', '246');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (248, 'View 2', '9', '246');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (249, 'View 3', '9', '246');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (250, 'View 4', '9', '246');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (251, 'Bathroom', '9', '246');


#Add sub div for conference room
#
UPDATE `hotel_divisions` SET `name`='View 1' WHERE `id`='132';
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (252, 'View 2', '14', '131');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (253, 'View 3', '14', '131');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (254, 'View 4', '14', '131');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (255, 'View 5', '14', '131');


#Add Beauty Salon division
#
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (18, 'Beauty Salon');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (256, 'Beauty Salon', '18');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (257, 'View 1', '18', '256');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (258, 'View 2', '18', '256');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (259, 'View 3', '18', '256');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (260, 'View 4', '18', '256');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (261, 'View 5', '18', '256');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (271, 'View 6', '18', '256');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (272, 'View 7', '18', '256');


#New Bedroom sub div under suite
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (262, 'Bedroom 2', '15', '159');


#Add triple room 
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (263, 'Triple Room', '9');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (264, 'View 1', '9', '263');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (265, 'View 2', '9', '263');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (266, 'View 3', '9', '263');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (267, 'View 4', '9', '263');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (268, 'View 5', '9', '263');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (269, 'View 6', '9', '263');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (270, 'View 7', '9', '263');

