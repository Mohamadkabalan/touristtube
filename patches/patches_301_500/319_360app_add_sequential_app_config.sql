
ALTER TABLE `360_app_configuration` ADD `seq_invoice` INT(11) NOT NULL DEFAULT '0' AFTER `contract_terms`, ADD `seq_receipt` INT(11) NOT NULL DEFAULT '0' AFTER `seq_invoice`;

ALTER TABLE `360_accounts` ADD `created_by` INT NOT NULL AFTER `renewal_period_id`;

UPDATE `360_accounts` SET `created_by` = 1;
ALTER TABLE `360_accounts` ADD CONSTRAINT `fk_account_created_by` 
    FOREIGN KEY (`created_by`) REFERENCES `backend_users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

    