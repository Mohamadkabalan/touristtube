ALTER TABLE `360_accounts` ADD `renewal_period_id` INT(11) NULL AFTER `expiry_date`;
UPDATE `360_accounts` SET `renewal_period_id` = 1;
ALTER TABLE `360_accounts` ADD CONSTRAINT `fk_periodId_idx` FOREIGN KEY (`renewal_period_id`) REFERENCES `360_renewal_period` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `360_renewal_history` ADD `renewal_period_id` INT(11) NOT NULL AFTER `currency`;
UPDATE `360_renewal_history` SET `renewal_period_id` = 1;
ALTER TABLE `360_renewal_history` ADD CONSTRAINT `renewal_history_fx` FOREIGN KEY (`renewal_period_id`) REFERENCES `360_renewal_period` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
