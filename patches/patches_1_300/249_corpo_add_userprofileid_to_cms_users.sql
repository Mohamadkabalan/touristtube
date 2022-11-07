ALTER TABLE `cms_users` 
ADD COLUMN `corpo_user_profile_id` INT(11) NULL AFTER `corpo_account_payment_type`,
ADD INDEX `fk_userprofile_id_idx` (`corpo_user_profile_id` ASC);
ALTER TABLE `cms_users` 
ADD CONSTRAINT `fk_userprodile_id`
  FOREIGN KEY (`corpo_user_profile_id`)
  REFERENCES `corpo_user_profiles` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;