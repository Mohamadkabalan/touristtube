ALTER TABLE `corpo_account` 
ADD COLUMN `path` VARCHAR(100) NULL AFTER `account_type_id`,
ADD INDEX `idx_account_path` (`path` ASC);
