-- Add the new columns
ALTER TABLE `corpo_admin_menu` ADD `enable_for_mobile` TINYINT(1) NOT NULL DEFAULT '1' AFTER `published`;

ALTER TABLE `corpo_admin_menu` ADD `mobile_trigger_method` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `enable_for_mobile`;

-- Update the rows as needed to exclude some menu items to be sent to REST
UPDATE `corpo_admin_menu` SET `enable_for_mobile` = '0' WHERE `corpo_admin_menu`.`id` = 20;
UPDATE `corpo_admin_menu` SET `enable_for_mobile` = '0' WHERE `corpo_admin_menu`.`parent_id` = 20;

-- Update the needed trigger method names
UPDATE `corpo_admin_menu` SET `mobile_trigger_method` = 'OpenApprovalFlow' WHERE `corpo_admin_menu`.`id` = 12;
UPDATE `corpo_admin_menu` SET `mobile_trigger_method` = 'OpenUsers' WHERE `corpo_admin_menu`.`id` = 14;
UPDATE `corpo_admin_menu` SET `mobile_trigger_method` = 'OpenTravelApproval' WHERE `corpo_admin_menu`.`id` = 17;
UPDATE `corpo_admin_menu` SET `mobile_trigger_method` = 'OpenMyBookings' WHERE `corpo_admin_menu`.`id` = 21;