ALTER TABLE `corpo_admin_menu` ADD COLUMN `onclick` VARCHAR(100) NULL AFTER `path`;
ALTER TABLE `corpo_admin_menu` ADD COLUMN `cls` VARCHAR(50) NULL AFTER `onclick`;
