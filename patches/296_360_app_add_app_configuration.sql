
DROP TABLE IF EXISTS 360_app_configuration;

CREATE TABLE `360_app_configuration` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `renewal_amount` DOUBLE NOT NULL , 
    `renewal_amount_currency` VARCHAR(3) NOT NULL , 
    `contract_terms` TEXT NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `360_app_configuration` (`renewal_amount`,`renewal_amount_currency`,`contract_terms`)
VALUES ('1000', 'USD', '');


INSERT INTO `backend_admin_menu` (`id`, `name`, `action`, `cls`, `parent_id`, `depth`, `sort_order`) 
VALUES (17, 'Default Settings', 'defaultsettings/new', 'fa fa-circle-o', '0', '1', '900');

