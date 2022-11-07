INSERT INTO `corpo_admin_menu` (
`id` ,
`name` ,
`order` ,
`url` ,
`type` ,
`published`
)
VALUES ( NULL, 'Notification', 6, '/corporate/admin/notification', 1, 1);

ALTER TABLE  `corpo_employees` CHANGE  `country_id`  `country_id` INT( 11 ) NOT NULL ;
ALTER TABLE  `corpo_employees` CHANGE  `city_id`  `city_id` INT( 11 ) NULL ;
ALTER TABLE `corpo_account_payment` ADD `payment_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `description` ;

ALTER TABLE `corpo_approval_flow` ADD INDEX ( `user_id` ) ;
ALTER TABLE `corpo_approval_flow` ADD INDEX ( `parent_id` ) ;
ALTER TABLE `corpo_approval_flow` ADD INDEX ( `main_user_id` ) ;
ALTER TABLE `corpo_approval_flow` ADD INDEX ( `other_user_id` ) ;


delete from corpo_approval_flow where user_id not in (select id from cms_users) and id > 0;


ALTER TABLE `corpo_approval_flow` 
ADD CONSTRAINT `fk_corpo_approval_flow_userId`
  FOREIGN KEY (`user_id`)
  REFERENCES `cms_users` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
