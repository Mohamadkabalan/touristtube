ALTER TABLE `corpo_user_profiles` 
ADD COLUMN `level` INT NULL DEFAULT 999 AFTER `slug`,
ADD INDEX `idx_usr_profile_level` (`level` ASC);
