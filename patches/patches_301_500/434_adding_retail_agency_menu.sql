
INSERT INTO `corpo_user_profiles` (`id`, `name`, `published`, `section_title`, `slug`, `level`) VALUES (NULL, 'Retail Agency', '1', 'Retail Agency', 'retail-agency', '999');
INSERT INTO `corpo_admin_menu` (`id`, `name`, `menu_key`, `order`, `url`, `parent_id`, `published`, `enable_for_mobile`, `mobile_trigger_method`, `path`, `onclick`, `cls`) VALUES ('25', 'Retail Agency', 'KEY_RETAIL_AGENCIES', '26', '/corporate/account/retail-agency', '8', '1', '0', NULL, ',8,14,', NULL, NULL);

UPDATE `corpo_admin_menu` SET `order` = '27', `path` = ',8,15,' WHERE menu_key = 'KEY_STATEMENT_OF_ACCOUNT';
UPDATE `corpo_admin_menu` SET `order` = '28', `path` = ',8,16,' WHERE menu_key = 'KEY_MANAGE_BOOKINGS';
