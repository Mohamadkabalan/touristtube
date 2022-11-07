ALTER TABLE `corpo_admin_menu` 
ADD INDEX `fk_menu_parent_id_idx` (`parent_id` ASC);
ALTER TABLE `corpo_admin_menu` 
ADD CONSTRAINT `fk_menu_parent_id`
  FOREIGN KEY (`parent_id`)
  REFERENCES `corpo_admin_menu` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
