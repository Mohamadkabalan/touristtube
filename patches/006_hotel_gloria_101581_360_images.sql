# CREATE HOTEL DIVISION CATEGORIES
#
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES ('11', 'Entrance');
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES ('12', 'Playground');
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES ('13', 'Squash');


# ATTACH CATEGORIES WITH DIVISIONS
#
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '1');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '2');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '3');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '4');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '5');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '6');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '8');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '10');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '12');
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '13');
UPDATE `hotel_to_hotel_divisions_categories` SET `hotel_division_category_id` = '11' WHERE `id` = '12';
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '1');


# CREATE HOTEL DIVISIONS
#
UPDATE `hotel_divisions` SET `name` = 'Lobby Entrance' WHERE `id` = '1';
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`) VALUES ('Outside Entrance', '11');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Gym 1', '3', '3');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Gym 2', '3', '3');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Gym 3', '3', '3');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Gym 4', '3', '3');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`) VALUES ('Kids Playground', '12');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Kids Playground 1', '12', '84');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Kids Playground 2', '12', '84');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Kids Playground 3', '12', '84');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Kids Playground 4', '12', '84');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Kids Playground 5', '12', '84');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lobby 7', '1', '1');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lobby 8', '1', '1');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Outside Pool 2', '2', '2');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Pool Bar', '2', '2');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`) VALUES ('Lounge', '5');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lounge 1', '5', '94');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lounge 2', '5', '94');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lounge 3', '5', '94');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lounge 4', '5', '94');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lounge 5', '5', '94');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Lounge 6', '5', '94');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Spa 1', '4', '4');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Spa 2', '4', '4');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Spa 3', '4', '4');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Spa 4', '4', '4');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Spa 5', '4', '4');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Spa 6', '4', '4');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`) VALUES ('Squash', '13');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Squash 1', '13', '107');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Squash 2', '13', '107');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Restaurant 1.5', '6', '28');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Restaurant 1.6', '6', '28');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Restaurant 1.7', '6', '28');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Restaurant 1.8', '6', '28');
DELETE FROM `hotel_divisions` WHERE `id` = '113';
UPDATE `hotel_divisions` SET `name` = 'Bathroom 1' WHERE `id` = '65';
UPDATE `hotel_divisions` SET `name` = 'Living Room 1' WHERE `id` = '66';
UPDATE `hotel_divisions` SET `name` = 'Living Room 2' WHERE `id` = '68';
UPDATE `hotel_divisions` SET `name` = 'Bathroom 2' WHERE `id` = '69';
UPDATE `hotel_divisions` SET `name` = 'Bedroom 1' WHERE `id` = '67';
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Bedroom 2', '10', '13');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Balcony', '10', '13');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`) VALUES ('2 Bedroom Apartment 1', '10');
DELETE FROM `hotel_divisions` WHERE `id` = '116';
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`) VALUES ('1 Bedroom Apartment 1', '10');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Living Room', '10', '118');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Kitchen', '10', '118');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Bedroom', '10', '118');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Bathroom', '10', '118');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`) VALUES ('2 Bedroom Apartment 1', '10');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Living Room', '10', '123');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Bathroom 1', '10', '123');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Kitchen', '10', '123');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Balcony', '10', '123');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Bathroom 2', '10', '123');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Bedroom', '10', '123');
INSERT INTO `hotel_divisions` (`name`, `hotel_division_category_id`, `parent_id`) VALUES ('Entrance 1', '11', '79');


# ATTACH HOTEL TO DIVISIONS
#
INSERT INTO `hotel_to_hotel_divisions` (`name`, `hotel_id`, `hotel_division_id`, `sort_order`)
values (NULL, 101581, 1, 999),
(NULL, 101581, 2, 999),
(NULL, 101581, 3, 999),
(NULL, 101581, 4, 999),
(NULL, 101581, 8, 999),
(NULL, 101581, 13, 999),
(NULL, 101581, 14, 999),
("Lobby 1", 101581, 15, 999),
("Lobby 2", 101581, 16, 999),
("Lobby 3", 101581, 17, 999),
("Lobby 4", 101581, 18, 999),
("Lobby 5", 101581, 19, 999),
("Lobby Shops 1", 101581, 20, 999),
("Pool 1", 101581, 21, 999),
("Restaurant", 101581, 28, 999),
("Terrasse 1", 101581, 29, 999),
("Terrasse 2", 101581, 30, 999),
("Terrasse 3", 101581, 31, 999),
("Terrasse 4", 101581, 32, 999),
("LE Grand Cafee 1", 101581, 33, 999),
("LE Grand Cafee 2", 101581, 34, 999),
("Calofornian - Sea View - Bathroom 1", 101581, 65, 999),
("Calofornian - Sea View - Dining/Living", 101581, 66, 999),
("Calofornian - Sea View - Bedroom 1", 101581, 67, 999),
("Calofornian - Sea View - Dining/Living", 101581, 68, 999),
("Calofornian - Sea View - Bathroom 2", 101581, 69, 999),
("Calofornian - Sea View - Kitchen", 101581, 70, 999),
("Calofornian - Sea View - Bedroom", 101581, 73, 999),
("Calofornian - Sea View - Bathroom", 101581, 74, 999),
("Calofornian - Sea View - Living Room 1", 101581, 75, 999),
("Calofornian - Sea View - Living Room 2", 101581, 76, 999),
("Calofornian - Sea View - Kitchen", 101581, 78, 999),
(NULL, 101581, 79, 999),
("Gym 1", 101581, 80, 999),
("Gym 2", 101581, 81, 999),
("Gym 3", 101581, 82, 999),
("Gym 4", 101581, 83, 999),
(NULL, 101581, 84, 999),
("Kids Playground 1", 101581, 85, 999),
("Kids Playground 2", 101581, 86, 999),
("Kids Playground 3", 101581, 87, 999),
("Kids Playground 4", 101581, 88, 999),
("Kids Playground 5", 101581, 89, 999),
("Lobby Shops 2", 101581, 90, 999),
("Lobby Shops 3", 101581, 91, 999),
("Pool 2", 101581, 92, 999),
("Pool Bar", 101581, 93, 999),
(NULL, 101581, 94, 999),
("Sky Lounge 1", 101581, 95, 999),
("Sky Lounge 2", 101581, 96, 999),
("Sky Lounge 3", 101581, 97, 999),
("Sky Lounge 4", 101581, 98, 999),
("Sky Lounge 5", 101581, 99, 999),
("Sky Lounge 6", 101581, 100, 999),
("Spa 1", 101581, 101, 999),
("Spa 2", 101581, 102, 999),
("Spa 3", 101581, 103, 999),
("Spa 4", 101581, 104, 999),
("Spa 5", 101581, 105, 999),
("Spa 6", 101581, 106, 999),
(NULL, 101581, 107, 999),
("Squash 1", 101581, 108, 999),
("Squash 2", 101581, 109, 999),
("Terrasse 5", 101581, 110, 999),
("Terrasse 6", 101581, 111, 999),
("Terrasse 7", 101581, 112, 999),
("Calofornian - Sea View - Bedroom 2", 101581, 114, 999),
("Calofornian - Sea View - Balcony", 101581, 115, 999),
("1 Bedroom Apartment 1", 101581, 118, 999),
("Modern - Sea View - Living", 101581, 119, 999),
("Modern - Sea View - Kitchen", 101581, 120, 999),
("Modern - Sea View - Bedroom", 101581, 121, 999),
("Modern - Sea View - Bathroom", 101581, 122, 999),
("2 Bedroom Apartment 1", 101581, 123, 999),
("Modern - City View - Living", 101581, 124, 999),
("Modern - City View - Bathroom 1", 101581, 125, 999),
("Modern - City View - Kitchen", 101581, 126, 999),
("Modern - City View - Balcony", 101581, 127, 999),
("Modern - City View - Bathroom 2", 101581, 128, 999),
("Modern - City View - Bedroom", 101581, 129, 999),
("Exterior Entrance", 101581, 130, 999), 
('Entrance 1', '101581', '130', 999), 
('Outside Entrance', '101581', '79', 999);

UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '1' WHERE `id` = '179';
UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '2' WHERE `id` = '198';
UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '3' WHERE `id` = '207';
UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '4' WHERE `id` = '201';
UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '5' WHERE `id` = '222';
UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '7' WHERE `id` = '230';
UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '6' WHERE `id` = '208';


# INSERT HOTEL 360 IMAGES
#
INSERT INTO `amadeus_hotel_image` (`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `media_settings`, `location`, `default_pic`, `is_featured`, `dupe_pool_id`)
VALUES(0, 'thumb.jpg', 101581, 130, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 80, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 81, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 82, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 83, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 85, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 86, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 87, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 88, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 89, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 33, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 34, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 15, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 16, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 17, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 18, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 19, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 20, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 90, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 91, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 21, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 92, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 93, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 95, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 96, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 97, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 98, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 99, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 100, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 101, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 102, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 103, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 104, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 105, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 106, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 108, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 109, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 29, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 30, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 31, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 32, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 110, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 111, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 112, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 66, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 68, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 70, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 65, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 69, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 67, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 114, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 115, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 73, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 74, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 75, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 76, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 78, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 117, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 119, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 120, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 121, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 122, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 124, 2, NULL, '', 1, 1, 0),
(0, 'thumb.jpg', 101581, 125, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 126, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 127, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 128, 2, NULL, '', 0, 0, 0),
(0, 'thumb.jpg', 101581, 129, 2, NULL, '', 0, 0, 0);


# Copy 360 images from AMADEUS HOTEL IMAGES TO CMS HOTEL IMAGES
#
INSERT INTO cms_hotel_image(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 101581
);

