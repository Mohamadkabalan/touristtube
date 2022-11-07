
UPDATE corpo_approval_flow SET parent_id = NULL WHERE parent_id = 0;


ALTER TABLE `corpo_approval_flow` 
ADD CONSTRAINT `fk_corpo_approval_flow_parentUserId`
  FOREIGN KEY (`parent_id`)
  REFERENCES `cms_users` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
