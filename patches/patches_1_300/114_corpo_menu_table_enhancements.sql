-- Add a new column parent_id
ALTER TABLE corpo_admin_menu ADD parent_id INT NULL AFTER type;

-- Have column url nullable
ALTER TABLE `corpo_admin_menu` CHANGE `url` `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

-- Remove the auto-increment from column id
ALTER TABLE `corpo_admin_menu` CHANGE `id` `id` INT( 11 ) NOT NULL;

-- Insert/Update the needed records
INSERT INTO `corpo_admin_menu` (`id` ,`name` ,`order` ,`url` ,`type` ,`parent_id` ,`published`) VALUES ('20', 'Admin', '0', NULL , '1', NULL , '0');
INSERT INTO `corpo_admin_menu` (`id` ,`name` ,`order` ,`url` ,`type` ,`parent_id` ,`published`) VALUES ('21', 'My Bookings', '12', '/corporate/my-bookings' , '3', NULL , '1');
UPDATE `corpo_admin_menu` SET `parent_id` = '20' WHERE `corpo_admin_menu`.`id` = 1;
UPDATE `corpo_admin_menu` SET `parent_id` = '20' WHERE `corpo_admin_menu`.`id` = 16;
UPDATE `corpo_admin_menu` SET `parent_id` = '20' WHERE `corpo_admin_menu`.`id` = 18;
UPDATE `corpo_admin_menu` SET `parent_id` = '20' WHERE `corpo_admin_menu`.`id` = 15;
UPDATE `corpo_admin_menu` SET `parent_id` = '20' WHERE `corpo_admin_menu`.`id` = 19;
UPDATE `corpo_admin_menu` SET `published` = '1'  WHERE `corpo_admin_menu`.`id` = 17;

-- Correct menu name
UPDATE `corpo_admin_menu` SET `name` = 'Approval Flow' WHERE `corpo_admin_menu`.`id` = 12;

-- New table creation with its predefined data
CREATE TABLE IF NOT EXISTS `corpo_admin_menu_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_user_group_id` int(11) NOT NULL,
  `corpo_admin_menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

INSERT INTO `corpo_admin_menu_roles` (`id`, `cms_user_group_id`, `corpo_admin_menu_id`) VALUES
(1, 4, 1),
(2, 4, 16),
(3, 4, 18),
(4, 4, 15),
(5, 4, 19),
(6, 4, 17),
(7, 4, 12),
(8, 4, 14),
(9, 2, 17),
(10, 2, 12),
(11, 2, 14),
(12, 3, 17),
(13, 4, 21),
(14, 2, 21),
(15, 3, 21),
(16, 4, 20);

-- Add the needed FKs
ALTER TABLE `corpo_admin_menu_roles` ADD CONSTRAINT `fk_cms_user_group_id` FOREIGN KEY (`cms_user_group_id`) REFERENCES `cms_user_group` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `corpo_admin_menu_roles` ADD CONSTRAINT `fk_corpo_admin_menu_id` FOREIGN KEY (`corpo_admin_menu_id`) REFERENCES `corpo_admin_menu` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;