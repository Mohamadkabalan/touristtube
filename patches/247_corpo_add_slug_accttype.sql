ALTER TABLE `corpo_account_type` ADD  `slug` VARCHAR( 100 ) NULL AFTER  `is_active` ;

ALTER TABLE `corpo_account_type`
  ADD INDEX `slug` (`slug`);