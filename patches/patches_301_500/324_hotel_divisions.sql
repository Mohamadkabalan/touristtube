
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (1343, 'Reception', '1');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (1344, 'Reception 1.1', '1');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (1345, 'Reception 1.2', '1');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (1346, 'Reception 1.3', '1');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (1347, 'Reception 1.4', '1');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (1348, 'Reception 1.5', '1');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (1349, 'Reception 1.6', '1');

UPDATE `hotel_divisions` SET `parent_id`='1343' WHERE `id`='1344';
UPDATE `hotel_divisions` SET `parent_id`='1343' WHERE `id`='1345';
UPDATE `hotel_divisions` SET `parent_id`='1343' WHERE `id`='1346';
UPDATE `hotel_divisions` SET `parent_id`='1343' WHERE `id`='1347';
UPDATE `hotel_divisions` SET `parent_id`='1343' WHERE `id`='1348';
UPDATE `hotel_divisions` SET `parent_id`='1343' WHERE `id`='1349';

