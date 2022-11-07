
ALTER TABLE otp_request CHANGE module_id module_id TINYINT(4) NOT NULL;

ALTER TABLE `otp_request` 
ADD CONSTRAINT `fk_otp_module_id`
  FOREIGN KEY (`module_id`)
  REFERENCES `tt_modules` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
