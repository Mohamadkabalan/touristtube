ALTER TABLE `corpo_account` 
ADD INDEX `fk_corpo_acc_city_id_idx` (`city_id` ASC),
ADD INDEX `fk_corpo_acc_pay_type_idx` (`payment_type_id` ASC),
ADD INDEX `fk_corpo_acc_parent_acc_idx` (`parent_account_id` ASC),
ADD INDEX `fk_corpo_acc_agency_idx` (`agency_id` ASC);
ALTER TABLE `corpo_account` 
ADD CONSTRAINT `fk_corpo_acc_city_id`
  FOREIGN KEY (`city_id`)
  REFERENCES `webgeocities` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_corpo_acc_pay_type`
  FOREIGN KEY (`payment_type_id`)
  REFERENCES `corpo_payment_type` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_corpo_acc_parent_acc`
  FOREIGN KEY (`parent_account_id`)
  REFERENCES `corpo_account` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_corpo_acc_agency`
  FOREIGN KEY (`agency_id`)
  REFERENCES `corpo_agencies` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

  
  
ALTER TABLE `corpo_account` 
ADD INDEX `fk_corpo_acc_createdby_idx` (`created_by` ASC),
ADD INDEX `fk_corpo_acc_updatedby_idx` (`updated_by` ASC);
ALTER TABLE `corpo_account` 
ADD CONSTRAINT `fk_corpo_acc_createdby`
  FOREIGN KEY (`created_by`)
  REFERENCES `cms_users` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_corpo_acc_updatedby`
  FOREIGN KEY (`updated_by`)
  REFERENCES `cms_users` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
