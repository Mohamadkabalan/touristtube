UPDATE `main_entity_type` SET `name` = 'Hotels 360' WHERE `main_entity_type`.`id` = 37;
UPDATE `hotel_selected_city` SET `location_id`=1745 WHERE `name`='bali';

UPDATE `airport` SET `city_id` = '2224674' WHERE `airport`.`id` = 4037; UPDATE `airport` SET `city_id` = '2224674' WHERE `airport`.`id` = 4042; UPDATE `airport` SET `city_id` = '2224674' WHERE `airport`.`id` = 4068;

UPDATE `restaurant` SET `latitude` = '33.900672', `longitude` = '35.472395' WHERE `restaurant`.`id` = 9; UPDATE `restaurant` SET `latitude` = '33.900672', `longitude` = '35.472395' WHERE `restaurant`.`id` = 10; UPDATE `restaurant` SET `latitude` = '33.900672', `longitude` = '35.472395' WHERE `restaurant`.`id` = 11;
UPDATE `restaurant` SET `city_id` = '1060078' WHERE `restaurant`.`id` = 142;

INSERT INTO `entity_type` (`id`, `entity_type_key`, `name`, `label`, `published`) VALUES ('29', 'SOCIAL_ENTITY_RESTAURANT', 'Restaurant', 'Restaurant', '1');

INSERT INTO `main_entity_type` (`id`, `name`, `entity_type_id`, `entity_id`, `display_order`, `show_on_home`, `published`) VALUES (38, 'Best restaurants in 360 virtual tour', '29', '0', '985', '1', '1');

INSERT INTO `main_entity_type_list` (`id`, `name`, `entity_type_id`, `main_entity_type_id`, `entity_id`, `display_order`, `show_on_home`, `city_id`, `published`) VALUES 
(NULL, 'The Polo Bar', '29', '38', '142', '1000', '1', '0', '1'), 
(NULL, 'Level Seven', '29', '38', '144', '990', '1', '0', '1'), 
(NULL, 'Le Restaurant INKA', '29', '38', '57', '980', '1', '0', '1'),
(NULL, 'Swiss Café Restaurant', '29', '38', '147', '970', '1', '0', '1');