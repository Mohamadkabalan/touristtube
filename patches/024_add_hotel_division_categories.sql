#Add new division categories
#
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (20, 'Business Center');


INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (323, 'Business Center', '20');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (324, 'Business Center 1.1', '20', '323');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (325, 'Business Center 1.2', '20', '323');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (326, 'Business Center 1.3', '20', '323');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (327, 'Business Center 1.4', '20', '323');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (328, 'Business Center 1.5', '20', '323');


INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (329, 'Conference 6', '14');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (330, 'Conference 6.1', '14', '329');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (331, 'Conference 6.2', '14', '329');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (332, 'Conference 6.3', '14', '329');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (333, 'Conference 6.4', '14', '329');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (334, 'Conference 6.5', '14', '329');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (335, 'Bathroom', '9', '40');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (336, 'Living 2', '15', '10');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (337, 'Guest Bathroom', '15', '10');

INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (21, 'Flow Reception');
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (22, 'Center of the Complex');


INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (338, 'Flow Reception', '21');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (339, 'Flow Reception 1.1', '21', '338');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (340, 'Flow Reception 1.2', '21', '338');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (341, 'Flow Reception 1.3', '21', '338');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (342, 'Flow Reception 1.4', '21', '338');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (343, 'Flow Reception 1.5', '21', '338');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (344, 'Center of the Complex', '22');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (345, 'Center of the Complex 1.1', '22', '344');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (346, 'Center of the Complex 1.2', '22', '344');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (347, 'Center of the Complex 1.3', '22', '344');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (348, 'Center of the Complex 1.4', '22', '344');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (349, 'Center of the Complex 1.5', '22', '344');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (350, 'Bedroom', '10', '12');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (351, 'Bedroom 2', '10', '123');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (352, 'Bedroom 2 View 2', '10', '123');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (353, 'Living Room - View 2', '10', '123');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (354, 'Restaurant 2.8', '6', '167');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (355, 'Presidential Suite', '15');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (356, 'Salon - View 1', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (357, 'Kitchen', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (358, 'Guest Bathroom', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (359, 'Salon / Dining Room', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (360, 'Living Room / Salon', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (361, 'Bedroom 1 - View 1', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (362, 'Bedroom 1 - View 2', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (363, 'Bathroom 1', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (364, 'Bedroom 2 - View 1', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (365, 'Bedroom 2 - View 2', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (366, 'Bathroom 2', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (367, 'Terrace - View 1', '15', '355');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (368, 'Terrace - View 2', '15', '355');



INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (23, 'Residences Area');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (369, 'Residences Area', '23');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (372, 'View 1', '23', '369');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (373, 'View 2', '23', '369');


INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (374, 'Deluxe Two Bedroom Suite', '15');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (375, 'Living Room - View 1', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (376, 'Guest Bathroom', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (377, 'Kitchen', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (378, 'Living Room - View 2', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (379, 'Bedroom 1 - View 1', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (380, 'Bedroom 1 - View 2', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (381, 'Bedroom 2 - View 1', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (382, 'Bedroom 2 - View 2', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (383, 'Bathroom', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (384, 'Bedroom 2', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (385, 'Bathroom 2', '15', '374');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (386, 'Terrace', '15', '374');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (387, 'Guest Bathroom', '10', '14');

UPDATE `hotel_divisions` SET `name`='Bedroom 2 View 1' WHERE `id`='351';
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (388, 'Guest Bathroom', '10', '123');

