
UPDATE cms_users SET cms_user_group_id = 1 WHERE cms_user_group_id IS NULL;
 
ALTER TABLE cms_users CHANGE COLUMN cms_user_group_id cms_user_group_id INT(11) NOT NULL DEFAULT 1;

ALTER TABLE cms_users ADD CONSTRAINT fk_cms_user_group FOREIGN KEY (cms_user_group_id) REFERENCES cms_user_group (id) ON UPDATE CASCADE ON DELETE RESTRICT;
