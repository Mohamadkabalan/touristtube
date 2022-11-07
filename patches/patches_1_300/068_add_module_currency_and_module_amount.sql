ALTER TABLE  `payment` ADD  `module_currency` VARCHAR( 3 ) NULL DEFAULT NULL AFTER  `account_currency_amount` ,
ADD  `module_amount` DECIMAL( 20, 5 ) NULL DEFAULT NULL AFTER  `module_currency` ;