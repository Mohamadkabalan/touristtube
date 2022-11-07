INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (24, 'Entertainment');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (464, 'Entertainment', '24');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (465, 'Entertainment 1.1', '24', '464');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (466, 'Entertainment 1.2', '24', '464');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (467, 'Entertainment 1.3', '24', '464');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (468, 'Kitchen', '15', '11');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (469, 'Maid Room', '15', '11');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (470, 'DIPLOMATIC SUITE', '15');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (471, 'Living Room / Master', '15', '470');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (472, 'Living Room', '15', '470');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (473, 'Living Room / Twin Room', '15', '470');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (474, 'Master Bedroom', '15', '470');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (475, 'Master Bedroom Bathroom', '15', '470');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (476, 'Twin Bedroom', '15', '470');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (477, 'Twin Bedroom Bathroom', '15', '470');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (478, 'Guest Bathroom', '15', '470');


