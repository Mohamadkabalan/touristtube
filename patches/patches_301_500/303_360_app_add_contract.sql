CREATE TABLE `360_accounts_contract` ( 
    `id` int(11) NOT NULL AUTO_INCREMENT , 
    `account_id` INT NOT NULL , 
    `contract` TEXT NOT NULL , 
    `created_on` DATETIME NOT NULL , 
    `created_by` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_account_id` (`account_id`),
    KEY `fk_created_by` (`created_by`),
    CONSTRAINT `fk_account_id` FOREIGN KEY (`account_id`) REFERENCES `360_accounts`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `backend_users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;