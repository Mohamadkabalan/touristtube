INSERT INTO `hotel_divisions_categories` (`id`, `name`, `hotel_division_category_group_id`) VALUES ('31', 'Garden', '4');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES ('956', 'Garden', '31');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('957', 'Garden 1.1', '31', '956');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('958', 'Garden 1.2', '31', '956');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('959', 'Garden 1.3', '31', '956');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('960', 'Garden 1.4', '31', '956');

