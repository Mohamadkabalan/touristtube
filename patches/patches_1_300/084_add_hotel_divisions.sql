INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (26, 'Cave');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES ('597', 'Cave', '26');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('598', 'View 1', '26', '597');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('599', 'View 2', '26', '597');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('600', 'View 4', '9', '578');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES ('601', 'View 5', '9', '578');
