ALTER TABLE `corpo_employees` 
ADD COLUMN `country_code` VARCHAR(2) NULL AFTER `user_id`,
ADD INDEX `idx_employee_countryCode` (`country_code` ASC);

UPDATE corpo_employees e, cms_countries c 
SET e.country_code = c.code
WHERE e.country_id = c.id;

ALTER TABLE `corpo_employees` 
CHANGE COLUMN `country_id` `country_id` INT(11) NOT NULL;
