ALTER TABLE `corpo_account` 
ADD COLUMN `account_type_id` INT(11) NULL AFTER `agency_id`;

ALTER TABLE `corpo_account` 
ADD CONSTRAINT `fk_corpo_accountTypeId`
  FOREIGN KEY (`account_type_id`)
  REFERENCES `corpo_account_type` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
