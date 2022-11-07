ALTER TABLE `cms_users` ADD `parent_user_id` INT NULL DEFAULT NULL AFTER `allow_access_to_sub_accounts_users`;
ALTER TABLE `cms_users` ADD CONSTRAINT `fk_parent_user_id` FOREIGN KEY (`parent_user_id`) REFERENCES `cms_users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
