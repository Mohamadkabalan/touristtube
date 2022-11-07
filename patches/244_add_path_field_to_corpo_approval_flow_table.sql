ALTER TABLE `corpo_approval_flow` ADD  `path` VARCHAR( 50 ) NULL AFTER  `approve_all_users` ;
ALTER TABLE `corpo_approval_flow` 
ADD INDEX `idx_corpo_approval_flow_path` (`path` ASC);
